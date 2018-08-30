<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @author Administrator
 * @Date 2011��5��5�� 14:02:56
 * @version 1.0
 * @description:���޽����ú�ͬ Model��
 */
 class model_contract_rental_rentalcontract  extends model_base {

	function __construct() {
		include (WEB_TOR."model/common/mailConfig.php");
		$this->tbl_name = "oa_sale_lease";
		$this->sql_map = "contract/rental/rentalcontractSql.php";
		$this->mailArr=$mailUser[$this->tbl_name];

		parent::__construct ();
	}

	/**
	 * ��дadd_d
	 */
	 function add_d($object){

          try{
          	$this->start_d();
			//������Զ���������
			$orderCodeDao = new model_common_codeRule ();
			if ($object ['orderInput'] == "1") {
				if ($object ['sign'] == "��") {
					$object ['orderTempCode'] = $orderCodeDao->contractCode ( $this->tbl_name, $object ['tenantId'] );
					if(empty($object['orderCode'])){
						$object ['orderTempCode'] = "LS".$object ['orderTempCode'];
					}
				} else if ($object ['sign'] == "��") {
					$object ['orderCode'] = $orderCodeDao->contractCode ( $this->tbl_name, $object ['tenantId'] );
				}
			}else{
				 if(!empty($object['orderTempCode'])  && empty($object['orderCode'])){
					$object ['orderTempCode'] = "LS".$object ['orderTempCode'];
				}
			}
			$prinvipalId=$object['hiresId'];
			$deptDao=new model_deptuser_dept_dept();
			$dept=$deptDao->getDeptByUserId($prinvipalId);
			$object['objCode']=$orderCodeDao->getObjCode($this->tbl_name."_objCode",$dept['Code']);

          //���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object['orderNatureName'] = $datadictDao->getDataNameByCode ( $object['orderNature'] );


          //����������Ϣ
          $newId = parent::add_d($object,true);

           //�����̻����״̬����ɡ������ɶ�����
			$chanceDao = new model_projectmanagent_chance_chance();
			$condiction = array('id'=>$object['chanceId']);
			$flag = $chanceDao->updateField($condiction,"status","4");

          //����ӱ���Ϣ
          if(!empty($object['rentalcontractequ'])){
             $equDao = new model_contract_rental_tentalcontractequ();
             $equDao ->createBatch($object['rentalcontractequ'],array('orderId' => $newId),'productName');
             $equDao->updateUniqueCode_d($newId);

             $licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $newId, 'objType' => $this->tbl_name , 'extType' => $equDao->tbl_name ),
					'orderId',
					'license'
				);
          }

          //�Զ����嵥
			if(!empty($object['customizelist'])){
				$customizelistDao = new model_contract_rental_customizelist();
				$customizelistDao->createBatch($object['customizelist'],array('orderId' => $newId,'orderName' => $object['orderName']),'productName');
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $newId, 'objType' => $this->tbl_name , 'extType' => $customizelistDao->tbl_name ),
					'orderId',
					'license'
				);
			}

          if(!empty($object['trainingplan'])){
          	$trainingplanDao = new model_contract_rental_trainingplan();
          	$trainingplanDao -> createBatch($object['trainingplan'],array('orderId' => $newId),'beginDT');
          }

          //�ϲ�����
            if(!empty($object['orderCode']) && !empty($object['orderTempCode'])){
				$this->changeOrderStatus_d($newId,$object['orderTempCode']);
            }
          //���������ƺ�Id
		     $this->updateObjWithFile($newId);
        $this->commit_d();
//        $this->rollBack();
        return $newId;
       }catch(exception $e){
       	 $this->rollBack();
       	 return false;
       }

	 }

	/**
	 * ������ʱ��ͬ
	 */
	function changeOrderStatus_d($id,$tempCode){
		$arr = explode(',',$tempCode);
		$temp = null;
		foreach($arr as $key => $val){
			if($key == 0){
				$temp .= "'" . $val . "'";
			}else{
				$temp .= ",'" . $val . "'";
			}
		}
		$sql = ' update '.$this->tbl_name . " set state = 5 where id<>".$id." and orderTempCode in ( ".$temp .")" ;
		return $this->_db->query($sql);
	}

/**
 * �鿴�ϲ������ʱ��ͬ��Ϣ
 */
	function TempOrderView($row,$skey) {
		foreach( $row as $key=>$val){
             $temp = array('orderTempCode' => $val);
             $TempId = implode('',$this->find($temp,null,'id'));

            $row[$key].='<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=contract_rental_rentalcontract&action=toViewTab&id='.$TempId.'&perm=view&skey='.$skey.'\')">';
		} ;

		return implode(',',$row);
	}

	 /**
	  * ��дget_d
	  */
	  function get_d($id,$selection = null){
	  	 //��ȡ������Ϣ
	  	 $rows = parent::get_d($id);
	  	 if(empty($selection)){
	  	 	$equDao = new model_contract_rental_tentalcontractequ();
	  	 	$rows['rentalcontractequ'] = $equDao -> getDetail_d($id);

	  	 	$customizelistDao = new model_contract_rental_customizelist();
            $rows['customizelist'] = $customizelistDao -> getDetail_d($id);

            $trainingplanDao = new model_contract_rental_trainingplan();
            $rows['trainingplan'] =  $trainingplanDao -> getDetail_d($id);
	  	 }else if(is_array($selection)){
	  	 	if(in_array('orderequ',$selection)){
				$equDao = new model_contract_rental_tentalcontractequ();
				$rows['rentalcontractequ'] = $equDao->getDetail_d($id);
			}
			if(in_array('customizelist',$selection)){
				$customizelistDao = new model_contract_rental_customizelist();
	       		$rows['customizelist'] = $customizelistDao->getDetail_d($id);
			}
			if(in_array('trainingplan',$selection)){
				$trainingplanDao = new model_contract_rental_trainingplan();//��ѵ�ƻ�
	       		$rows['trainingplan'] = $trainingplanDao->getDetail_d($id);
			}
	  	 }

	  	 return $rows;
	  }
     //get_d ������
     function getShip_d($id,$selection = null){
	  	 //��ȡ������Ϣ
	  	 $rows = parent::get_d($id);
	  	 if(empty($selection)){
	  	 	$equDao = new model_contract_rental_tentalcontractequ();
	  	 	$rows['rentalcontractequ'] = $equDao -> getShip_d($id);

	  	 	$customizelistDao = new model_contract_rental_customizelist();
            $rows['customizelist'] = $customizelistDao -> getDetail_d($id);

            $trainingplanDao = new model_contract_rental_trainingplan();
            $rows['trainingplan'] =  $trainingplanDao -> getDetail_d($id);
	  	 }else if(is_array($selection)){
	  	 	if(in_array('orderequ',$selection)){
				$equDao = new model_contract_rental_tentalcontractequ();
				$rows['rentalcontractequ'] = $equDao->getShip_d($id);
			}
			if(in_array('customizelist',$selection)){
				$customizelistDao = new model_contract_rental_customizelist();
	       		$rows['customizelist'] = $customizelistDao->getDetail_d($id);
			}
			if(in_array('trainingplan',$selection)){
				$trainingplanDao = new model_contract_rental_trainingplan();//��ѵ�ƻ�
	       		$rows['trainingplan'] = $trainingplanDao->getDetail_d($id);
			}
	  	 }

	  	 return $rows;
	  }
    /**
     * ��ȡ��Ȩ�޹��˵�get_d
     */
    function getByPurview_d($id,$selection = null){
        $rows = $this->get_d($id,$selection = null);
        //Ȩ�޹���,����Ǻ�ͬ�����˺��������ˡ���ͬ�����ˣ��������ֶ�Ȩ�޹���
        if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['hiresId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']){
            $rows = $this->filterWithoutField('���޺�ͬ���',$rows,'form',array('orderMoney','orderTempMoney'));
            $rows['rentalcontractequ'] = $this->filterWithoutField('�����豸���',$rows['rentalcontractequ'],'list',array('price','money'));
        }
        return $rows;
    }


	/**
	 * ��Ⱦ���� - �鿴
	 */
	function initView($object){

        if(!empty($object['rentalcontractequ'])){

        	$equDao = new model_contract_rental_tentalcontractequ();
        	$object['rentalcontractequ'] = $equDao -> initTableView($object['rentalcontractequ'],$object['id']);
        }else{
        	$object['rentalcontractequ'] = '<tr><td colspan="10">���������Ϣ</td></tr>';
        }
        if(!empty($object['customizelist'])){
        	$customizelistDao = new model_contract_rental_customizelist();
        	$object['customizelist'] = $customizelistDao ->initTableView($object['customizelist']);
        }else{
        	$object['customizelist'] = '<tr><td colspan="10">���������Ϣ</td></tr>';
        }
		if(!empty($object['trainingplan'])){
			$trainingplanDao = new model_contract_rental_trainingplan();//��ѵ�ƻ�
            $object['trainingplan'] = $trainingplanDao->initTableView($object['trainingplan']);
		}else{
			$object['trainingplan'] = '<tr><td colspan="7">���������Ϣ</td></tr>';
		}
//		echo "<pre>";
//		print_r ($object);
		return $object;
	}


	/**
	 * ��Ⱦ���� - �༭
	 */
	function initEdit($object){


		//�豸
		$tentalcontractequDao = new model_contract_rental_tentalcontractequ();
		$rows = $tentalcontractequDao->initTableEdit($object['rentalcontractequ']);
		$object['productNumber'] = $rows[0];
		$object['rentalcontractequ'] = $rows[1];

        //�Զ����嵥
        $customizelistDao = new model_contract_rental_customizelist();
        $rows = $customizelistDao->initTableEdit($object['customizelist']);
        $object['PreNum'] = $rows[0];
	    $object['customizelist'] = $rows[1];


        //��ѵ�ƻ�
        $TrainingplanDao = new model_contract_rental_trainingplan();
        $rows = $TrainingplanDao->initTableEdit($object['trainingplan']);
        $object['TraNumber'] = $rows[0];
        $object['trainingplan'] = $rows[1];
		return $object;
	}


	/**
	 * ��Ⱦ���� - תΪ��ʽ��ͬ
	 */
	function becomeEdit($object){
		//�豸
		$tentalcontractequDao = new model_contract_rental_tentalcontractequ();
		$rows = $tentalcontractequDao->proTableEdit($object['rentalcontractequ']);
		$object['productNumber'] = $rows[0];
		$object['rentalcontractequ'] = $rows[1];

        //�Զ����嵥
        $customizelistDao = new model_contract_rental_customizelist();
        $rows = $customizelistDao->initTableEdit($object['customizelist']);
        $object['PreNum'] = $rows[0];
	    $object['customizelist'] = $rows[1];


        //��ѵ�ƻ�
        $TrainingplanDao = new model_contract_rental_trainingplan();
        $rows = $TrainingplanDao->initTableEdit($object['trainingplan']);
        $object['TraNumber'] = $rows[0];
        $object['trainingplan'] = $rows[1];
		return $object;
	}
	/**
	 * ���������޸Ĵӱ���Ⱦ
	 */
	function editProduct($object){
		//�豸
		$tentalcontractequDao = new model_contract_rental_tentalcontractequ();
		$rows = $tentalcontractequDao->proTableEdit($object['rentalcontractequ']);
		$object['productNumber'] = $rows[0];
		$object['rentalcontractequ'] = $rows[1];

		return $object;
	}
	/**����ӱ��б�
	*author can
	*2011-6-2
	*/
	function initChange($object){
		//�豸
		$tentalcontractequDao = new model_contract_rental_tentalcontractequ();
		$rows = $tentalcontractequDao->initTableChange($object['rentalcontractequ']);
		$object['productNumber'] = $rows[0];
		$object['rentalcontractequ'] = $rows[1];


        //��ѵ�ƻ�
        $TrainingplanDao = new model_contract_rental_trainingplan();
        $rows = $TrainingplanDao->initTableChange($object['trainingplan']);
        $object['TraNumber'] = $rows[0];
        $object['trainingplan'] = $rows[1];
		return $object;
	}


	/**
	 * ��д�༭����
	 */
	function edit_d($object){
		try{
			$this->start_d();

			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object['orderNatureName'] = $datadictDao->getDataNameByCode ( $object['orderNature'] );

			//�޸�������Ϣ
			parent::edit_d($object,true);

			$serviceId = $object['id'];
			//����ӱ���Ϣ

			//�豸
			$equDao = new model_contract_rental_tentalcontractequ();
            $equDao->delete(array('orderId' => $serviceId,'isBorrowToorder' => '0'));
			$equDao->createBatch($object['rentalcontractequ'],array('orderId' => $serviceId ),'productName');
            $equDao->updateUniqueCode_d($serviceId);
			if($object['rentalcontractequ']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $object, 'objType' => $this->tbl_name , 'extType' => $equDao->tbl_name ),
					'orderId',
					'license'
				);
			}
			//�Զ����嵥
            $customizelistDao = new model_contract_rental_customizelist();
            $customizelistDao->delete(array('orderId' => $serviceId));
            $customizelistDao->createBatch($object['customizelist'],array('orderId' => $serviceId,'orderName' => $object['orderName']),'productName');
             if($object['rentalcontractequ']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $serviceId, 'objType' => $this->tbl_name , 'extType' => $customizelistDao->tbl_name ),
					'orderId',
					'license'
				);
			}
            //��ѵ�ƻ�
            $TrainingplanDao = new model_contract_rental_trainingplan();
            $TrainingplanDao->delete(array('orderId' => $serviceId));
            $TrainingplanDao->createBatch($object['trainingplan'],array('orderId' => $serviceId),'beginDT');
			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(exception $e){

			return false;
		}
	}

	/**
	 * ת��
	 */
	function becomeEdit_d($object){
		try{
			$this->start_d();

			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object['orderNatureName'] = $datadictDao->getDataNameByCode ( $object['orderNature'] );

			//�޸�������Ϣ
			parent::edit_d($object,true);
            //���¹�����Ŀ����������
            $proDao = new model_engineering_project_esmproject();
            $proDao->updateContractCode_d($object['objCode'],$object['orderCode'],$contractTempCode = '');
			$rentalId = $object['id'];
			//����ӱ���Ϣ

			//�豸
			$equDao = new model_contract_rental_tentalcontractequ();
			foreach($object['rentalcontractequ'] as $k => $v){
				$object['rentalcontractequ'][$k]['oldId'] = $v['proId'];
			}
			foreach($object['rentalcontractequ'] as $k => $v){
            	 if($v['proId'] && empty($v['isEdit']) && empty($v['isDel'])){
            	 	$v['id'] = $v['proId'];
            	 	$equDao->edit_d($v);
            	 }
                 if($v['isDel']){
                     $sql = "update ".$equDao->tbl_name." set isDel = 1 where id = ".$v['proId']." ";
			         $this->_db->query($sql);
                 }
                 if($v['isEdit'] && empty($v['isDel'])){
                     $sql = "update ".$equDao->tbl_name." set isDel = 1 where id = ".$v['proId']." ";
			         $this->_db->query($sql);
			         $v['orderId'] = $rentalId;
			         if(!empty($v['productName'])){
			         	$proId = $equDao->add_d($v);
			         }
                     $equDao->updateUniqueCode_d($rentalId);
                     $licenseDao = new model_yxlicense_license_tempKey();
						$licenseDao->updateLicenseBacth_d(
							array( 'objId' => $rentalId, 'objType' => $this->tbl_name , 'extType' => $equDao->tbl_name ),
							'orderId',
							'license'
						);
                 }
                 if($v['isAdd']){
                 	 $v['orderId'] = $rentalId;
                 	 if(!empty($v['productName'])){
                        $proId = $equDao->add_d($v);
                 	 }
                     $equDao->updateUniqueCode_d($rentalId);
                     $licenseDao = new model_yxlicense_license_tempKey();
						$licenseDao->updateLicenseBacth_d(
							array( 'objId' => $rentalId, 'objType' => $this->tbl_name , 'extType' => $equDao->tbl_name ),
							'orderId',
							'license'
						);
                 }
            }
			//�Զ����嵥
            $customizelistDao = new model_contract_rental_customizelist();
            $customizelistDao->delete(array('orderId' => $rentalId));
            $customizelistDao->createBatch($object['customizelist'],array('orderId' => $rentalId,'orderName' => $object['orderName']),'productName');
             if($object['rentalcontractequ']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $rentalId, 'objType' => $this->tbl_name , 'extType' => $customizelistDao->tbl_name ),
					'orderId',
					'license'
				);
			}
            //��ѵ�ƻ�
            $TrainingplanDao = new model_contract_rental_trainingplan();
            $TrainingplanDao->delete(array('orderId' => $rentalId));
            $TrainingplanDao->createBatch($object['trainingplan'],array('orderId' => $rentalId),'beginDT');

            //�����ʼ�
            //��ȡĬ�Ϸ�����
		   include (WEB_TOR."model/common/mailConfig.php");
		   $emailDao = new model_common_mail();
		   $emailInfo = $emailDao->contractBecomeEmail('���޺�ͬ',1,$_SESSION['USERNAME'],$_SESSION['EMAIL'],"contractBeomce",$object['orderTempCode'],"",$mailUser['contractBecome']['sendUserId']);


			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(exception $e){

			return false;
		}
	}
	    /**
     * ���������޸�
     */
    function proedit_d($object){

		try{
			$this->start_d();
			$rentalId = $object['id'];
			//�豸
			$equDao = new model_contract_rental_tentalcontractequ();
			foreach($object['rentalcontractequ'] as $k => $v){
				$object['rentalcontractequ'][$k]['oldId'] = $v['proId'];
			}
			$orderInfo = parent::get_d($rentalId);
            $orderInfo['oldId'] = $rentalId;
			$orderInfo['rentalcontractequ'] = $object['rentalcontractequ'];
            //�������
			$changeLogDao = new model_common_changeLog ( 'rentalcontract',false );
			$tempObjId = $changeLogDao->addLog ( $orderInfo );

			foreach($object['rentalcontractequ'] as $k => $v){
            	 if($v['proId'] && empty($v['isEdit']) && empty($v['isDel'])){
            	 	$v['id'] = $v['proId'];
            	 	$equDao->edit_d($v);
            	 }
                 if(isset($v['isDel']) && isset($v['proId'])){
                     $sql = "update ".$equDao->tbl_name." set isDel = 1 where id = ".$v['proId']." ";
			         $this->_db->query($sql);
                 }
                 if($v['isEdit'] && empty($v['isDel'])){
                     $sql = "update ".$equDao->tbl_name." set isDel = 1 where id = ".$v['proId']." ";
			         $this->_db->query($sql);
			         $v['orderId'] = $rentalId;
                     $proId = $equDao->add_d($v);
                     $equDao->updateUniqueCode_d($rentalId);
                     $licenseDao = new model_yxlicense_license_tempKey();
						$licenseDao->updateLicenseBacth_d(
							array( 'objId' => $rentalId, 'objType' => $this->tbl_name , 'extType' => $equDao->tbl_name ),
							'orderId',
							'license'
						);
                 }
                 if($v['isAdd']){
                 	 $v['orderId'] = $rentalId;
                     $proId = $equDao->add_d($v);
                     $equDao->updateUniqueCode_d($rentalId);
                     $licenseDao = new model_yxlicense_license_tempKey();
						$licenseDao->updateLicenseBacth_d(
							array( 'objId' => $rentalId, 'objType' => $this->tbl_name , 'extType' => $equDao->tbl_name ),
							'orderId',
							'license'
						);
                 }
            }
			$this->commit_d();
//			$this->rollBack();
			return $rentalId;
		}catch(exception $e){

			return false;
		}
	}
/**************************************************************************/

	/**
	 * ǩ�շ���
	 */
	function signin_d($object){
		try{
			$this->start_d();

             $changeLogDao = new model_common_changeLog ( 'rentalcontractSignin',false );
			//�����¼,�õ��������ʱ������id
			$changeLogDao->addLog ( $object );

			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object['orderNatureName'] = $datadictDao->getDataNameByCode ( $object['orderNature'] );

			//�޸�������Ϣ
			$object['id'] = $object['oldId'];
			parent::edit_d($object,true);

			$rentalId = $object['oldId'];
			//����ӱ���Ϣ

			//�豸
			$equDao = new model_contract_rental_tentalcontractequ();
			  foreach ($object['rentalcontractequ'] as $key => $val){
                if (isset ($val['oldId'])){
                     $val['id'] = $val['oldId'];
                     $equDao->edit_d ( $val );
                }else {
		 	         $equDao->createBatch(array( $object['rentalcontractequ'][$key] ),array('orderId' => $rentalId ),'productName');
                }
                if(isset ($val['isDel'])){
                     $equDao->delete(array('id' => $val['oldId']));
                }
			}
            $equDao->updateUniqueCode_d($rentalId);
			if($object['rentalcontractequ']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $object, 'objType' => $this->tbl_name , 'extType' => $equDao->tbl_name ),
					'orderId',
					'license'
				);
			}
            //��ѵ�ƻ�
            $TrainingplanDao = new model_contract_rental_trainingplan();
            foreach ($object['trainingplan'] as $key => $val){
                if (isset ($val['oldId'])){
                     $val['id'] = $val['oldId'];
                     $TrainingplanDao->edit_d ( $val );
                }else {
		 	         $TrainingplanDao->createBatch(array( $object['trainingplan'][$key] ),array('orderId' => $rentalId ),'beginDT');
                }
                if(isset ($val['isDel'])){
                     $TrainingplanDao->delete(array('id' => $val['oldId']));
                }
			}
			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(exception $e){

			return false;
		}
	}

/**************************************************************************/
	/**������޺�ͬ
	*author can
	*2011-6-2
	*/
	function change_d($obj) {
		$this->start_d ();
		//ɾ���豸����
		foreach ( $obj ['rentalcontractequ'] as $key => $val ) {
			if ($val ['number'] == 0) {
				$obj ['rentalcontractequ'] [$key] ['isDel'] = 1;
			}
		}

		//���������ֵ��ֶ�
		$datadictDao = new model_system_datadict_datadict ();
		$obj['orderNatureName'] = $datadictDao->getDataNameByCode ( $obj['orderNature'] );

		//�����������
		$changeLogDao = new model_common_changeLog ( 'rentalcontract' );
		$obj ['uploadFiles'] = $changeLogDao->processUploadFile ( $obj, $this->tbl_name );
		//print_r($obj ['uploadFiles'] );
		//var_dump($obj ['uploadFiles']);
		if($obj['rentalcontractequ']){
			//�����ͬ����
				foreach($obj['rentalcontractequ'] as $key=>$val){
		            $obj['rentalcontractequ'][$key]['isSell'] = isset($obj['rentalcontractequ'][$key]['isSell'])?$obj['rentalcontractequ'][$key]['isSell']:null;
				}
			}
		//�����¼,�õ��������ʱ������id
		$tempObjId = $changeLogDao->addLog ( $obj );//license�������
		if($obj['rentalcontractequ']){
			$licenseDao = new model_yxlicense_license_tempKey();
			$licenseDao->updateLicenseBacth_d(
				array( 'objId' => $tempObjId, 'objType' => $this->tbl_name , 'extType' => 'oa_lease_equ' ),
				'orderId',
				'license'
			);
		}
		$this->commit_d ();
		return $tempObjId;
	}


    /**
	 * �رպ�ͬ����
	 */
	function close_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		//���������ֵ䴦�� add by chengl 2011-05-15
		$this->processDatadict($object);
		return $this->updateById ( $object );
	}



/***********************************************************************************/
     /**
      * ����������豸��Ϣ
      */
     function showDetaiInfo($rows) {

     	$orderequDao = new model_contract_rental_tentalcontractequ();
		$rows['orderequ'] =
     	$orderequDao->showDetailByOrder( $orderequDao->showEquListInByOrder($rows['id'],'oa_sale_lease'));

     	return $rows;
     }

/*********************************�ı䷢��״̬*******************************************************/


	/**
	 * ���ݷ�������޸ĺ�ͬ�������ƻ�״̬
	 */
	 function updateOrderShipStatus_d( $id ){
	 	$orderRemainSql = "select count(0) as countNum,(select sum(o.executedNum) from oa_lease_equ o where o.orderId=".$id." and o.isTemp=0 and o.isDel=0) as executeNum
						 from (select e.orderId,(e.number-e.executedNum) as remainNum from oa_lease_equ e
						where e.orderId=".$id." and e.isTemp=0 and e.isDel=0) c where c.remainNum>0";
	 	$remainNum = $this->_db->getArray( $orderRemainSql );
	 	if( $remainNum[0]['countNum'] <= 0 ){//�ѷ���
	 		$DeliveryStatus = 8;
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => $DeliveryStatus,
		 		'state' => '4',
		 		'completeDate' => date("Y-m-d")
		 	);
		 	$this->updateById( $statusInfo );
	 	}elseif( $remainNum[0]['countNum']>0 && $remainNum[0]['executeNum']==0 ){//δ����
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => '7',
		 		'state' => '2'
		 	);
		 	$this->updateById( $statusInfo );
		} else {//���ַ���
	 		$DeliveryStatus = 10;
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => $DeliveryStatus,
		 		'state' => '2'
		 	);
		 	$this->updateById( $statusInfo );
	 	}
        $dao= new model_projectmanagent_order_order();
	 	$dao->updateProjectProcess(array("id"=>$id,"tablename"=>"oa_sale_lease"));//���¹��������ȡ������������Ⱥ�ͬ��
	 	return 0;
	 }
    /**
     * �ı䷢��״̬
     */
    function updateDeliveryStatus ($id) {
    	$condiction = array ("id" => $id);
    	$detail = array(
    		'DeliveryStatus'=>'11'
    	);
        if( $this->update( $condiction,$detail ) ){
        	echo 1;
        }else
        	echo 0;
    }
 /*********************************************************************************************************************************/

/**
 * �쳣�ر������鿴---�鿴��ͬ
 */
	function closeOrderView($orderId) {

       $row='<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=contract_rental_rentalcontract&action=toViewTab&id='.$orderId.'&perm=view\')">';
		return $row;
	}

    /**
     * �жϵ�ǰ��¼���Ƿ��ͬ������,������,��������
     * ����Ȩ�޹���
     * 2011-07-29
     * createBy show
     */
    function isKeyMan_d($id){
        $thisUserId = $_SESSION['USER_ID'];
        $sql = 'select id from '.$this->tbl_name ." where id = ".$id." and ( createId = '".$thisUserId."' or hiresId ='".$thisUserId."' or areaPrincipalId ='".$thisUserId."')";
        return $this->_db->getArray($sql);
    }
   /******************************/
			/**
			 * �ж��Ƿ�Ϊ����ĺ�ͬ
			 */
            function isTemp($conId){
            	$cond = array("id" => $conId);
            	$isTemp = $this->find($cond,'','isTemp');
            	$isTemp = implode(',',$isTemp);
            	return $isTemp;
            }
/******************************************************************************/
    /*�����������*/
    function c_configuration($proId,$Num,$trId,$isEdit){
        $configurationDao = new model_stock_productinfo_configuration ();
        $sql = "select configId,configNum from ".$configurationDao->tbl_name." where hardWareId = $proId and configId >0";
        $configId = $this->_db->getArray($sql);
        if(!empty($configId)){
        	 foreach ($configId as $k => $v){
        	$configIdA[$k] = $v['configId'];
        }
         	$configIdA = implode(",",$configIdA);
        $productInfoDao = new model_stock_productinfo_productinfo();
        $sql = "select * from ".$productInfoDao->tbl_name." where id in($configIdA)";
        $infoArr = $this->_db->getArray($sql);
        foreach ($infoArr as $key => $val){
        	foreach($configId as $keyo => $valo){
	              if($infoArr[$key]['id'] == $configId[$keyo]['configId']){
	                  $infoArr[$key]['configNum'] = $configId[$keyo]['configNum'];
	                  $infoArr[$key]['isCon'] = $trId;
	        	}
        	}
        }
        if($isEdit == "1"){
            $equDao = new model_contract_rental_tentalcontractequ();
            $configArr = $equDao->configTableEdit($infoArr,$Num);
        }else{
        	$equDao = new model_contract_rental_tentalcontractequ();
            $configArr = $equDao->configTable($infoArr,$Num);
        }

        }


        return $configArr;
    }
/********************************************************************/
   function configOrder_d($orderId){
   	     $orderExa = $this->find(array("id" => $orderId),null,"ExaStatus");
   	     $customizelistDao = new model_contract_rental_customizelist();
   	     $cus = $customizelistDao->find(array("orderId" => $orderId),null,"productName");
   	     $orderExa = implode(",",$orderExa);
   	     if(!empty($cus)){
           $mailArr=$this->mailArr;
              $orderName = $this->find(array("id" => $orderId),null,"orderName");
              $orderName = implode(",",$orderName);
              $orderCode = $this->find(array("id" => $orderId),null,"orderCode");
              $orderCode = implode(",",$orderCode);
              if(empty($orderCode)){
                  $orderCode = $this->find(array("id" => $orderId),null,"orderTempCode");
                  $orderCode = implode(",",$orderCode);
              }
		     $addmsg = "�봦���ͬ����Ϊ��".$orderName."��,��ͬ��Ϊ��".$orderCode."����  �Զ����嵥�ڵ���ʱ������Ϣ";
	        $emailDao = new model_common_mail();
	        $emailInfo = $emailDao->batchEmail(1,$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,"����","ͨ��",$mailArr['sendUserId'],$addmsg);
   	     }
   }
    /********************************************************************************************/
/**
 * ��֤ ��ͬ�����Ƿ���Ҫ�˻�
 */
function isGoodsReturn($rows){

      foreach($rows as $k => $v){
      	  $sql = "SELECT id FROM oa_lease_equ where orderId = '".$v['id']."' and isTemp = '0' and isDel = '0'  and (executedNum - number) > 0;";
      	  $isR = $this->_db->getArray($sql);
      	  if(!empty($isR)){
              $rows[$k]['isR'] = "1";
      	  }else{
      	  	  $rows[$k]['isR'] = "0";
      	  }
      }
      return $rows;
   }

	/**
	 * ���ݺ�ͬid ���Һ�ͬ��Ϣ����ID��
	 */
	function getOrderByIds($objId){

       if(!empty($objId)){
       	 $sql = "select * from ".$this->tbl_name." where id in ($objId)";
         $rows = $this->_db->getArray($sql);
       }else{
       	 $rows = "";
       }
          return $rows;
	}

	/**
	 * �������Ƿ��ظ�
	 * @��������ظ����贫��checkId
	 * @�޸ļ���ظ���Ҫ�ų��޸Ķ���id
	 */
	function isRepeatAll($searchArr, $checkId) {
		$countsql = "select count(id) as num  from view_oa_order c";
		$countsql = $this->createQuery ( $countsql, $searchArr );
		if ($checkId != '') {
			$countsql .= " and c.id!=" . $checkId;
		}
		//echo $countsql;
		$num = $this->queryCount ( $countsql );
//		echo $num;
		return ($num == 0 ? false : true);
	}

	/**
	 * �ж��Ƿ����ʼ����ɹ�
	 */
	 function purchaseMail($proId){
         $sql = " select issuedPurNum,productNo,productName,productModel,number from oa_lease_equ where id=".$proId."";
         $arr = $this->_db->getArray($sql);
         if(!empty($arr[0]['issuedPurNum']) && $arr[0]['issuedPurNum'] > 0){
         	 return $arr[0];
         }else{
         	return "";
         }
	 }
	 /**
	  * ��������
	  */
	  function updateEquByType(){
	  	try{
	 		$this->start_d();
		 	$idSql = "SELECT productId,orderId,countNum,orderCode,orderTempCode,delNum FROM (
				SELECT productId,orderId,count(*) as countNum,sum(isDel) as delNum FROM oa_lease_equ WHERE isTemp=0
				GROUP BY productId,orderId HAVING count(*)>1
				AND (sum(IF(isDel>0,issuedProNum,0))>0 OR sum(IF(isDel>0,issuedPurNum,0))>0 OR sum(IF(isDel>0,issuedShipNum,0))>0 )
				ORDER BY orderId
				)c LEFT JOIN oa_sale_lease s ON (c.orderId=s.id) WHERE delNum>0
				GROUP BY orderId;";
			$idArr = $this->_db->getArray($idSql);
		  	$expectSql = "SELECT productId,orderId FROM (
				SELECT productId,orderId,count(*) as countNum,sum(isDel) as delNum FROM oa_lease_equ WHERE isTemp=0
				GROUP BY productId,orderId HAVING count(*)>1
				AND (sum(IF(isDel>0,issuedProNum,0))>0 OR sum(IF(isDel>0,issuedPurNum,0))>0 OR sum(IF(isDel>0,issuedShipNum,0))>0 )
				ORDER BY orderId
				)c LEFT JOIN oa_sale_lease s ON (c.orderId=s.id) WHERE delNum>0 AND countNum-delNum>1;";
			$expectArr = $this->_db->getArray($expectSql);
			if( is_array($idArr)&&count($idArr) ){
				foreach( $idArr as $key=>$val ){
					if( is_array($expectArr) && count($expectArr) ){
						$productIdArr = array();
						foreach( $expectArr as $rows=>$row ){
							if( $val['orderId'] == $row['orderId'] ){
								$productIdArr[]=$row['productId'];
							}
						}
						if( is_array($productIdArr) && count($productIdArr) ){
							$productIdStr = implode(',',$productIdArr);
							$this->updateEquInfo($val['orderId'],$productIdStr);
						}else{
							$this->updateEquInfo($val['orderId']);
						}
					}else{
						$this->updateEquInfo($val['orderId']);
					}
				}
			}
	 		$this->commit_d();
	 		return count($idArr);
	 	}catch(Exception $e){
	 		$this->rollBack();
	 		return -1;
	 	}
	  }

	 /**
	  * �������ۺ�ͬ��������
	  */
	  function updateEquInfo($docId,$productId=0){
  		if( $productId==0 ){
  			$condition = "";
  		}else{
			$condition = " and productId not in('".$productId."') ";
  		}
	  	$equDao = new model_contract_rental_tentalcontractequ();
		if( $docId ){
			$isDelSql = "SELECT * FROM oa_lease_equ WHERE orderId='".$docId."' and isTemp=0 AND isDel=1".$condition;
			$isDelArr = $this->_db->getArray($isDelSql);
			if( is_array($isDelArr)&&count($isDelArr)>0 ){
				$oldEquIdArr = array();
				$equSql = "SELECT * FROM oa_lease_equ WHERE orderId='".$docId."' and isTemp=0 AND isDel=0 ".$condition." order by id DESC";
				$equArr = $this->_db->getArray($equSql);
				if( is_array($equArr)&&count($equArr) ){
					foreach( $isDelArr as $key=>$val ){
						foreach( $equArr as $index=>$row ){
							if( $val['productId']==$row['productId'] ){
								$this->updateEquNum($docId);
								$oldEquIdArr[$key]['oldId']=$val['id'];
								$oldEquIdArr[$key]['newId']=$row['id'];
								break;
							}
						}
					}
					$this->updateRelInfo($oldEquIdArr);
				}
			}
  			return 1;
		}else{
			return 0;
		}
	  }


 function updateRelInfo($oldEquIdArr){
	  	foreach( $oldEquIdArr as $key=>$val ){
	  		$planSql = "update oa_stock_outplan_product set contEquId='".$val['newId']."' where docType='oa_sale_lease' and contEquId='".$val['oldId']."'";
	  		$this->_db->query($planSql);
	  		$planSql = "update oa_purch_plan_equ set applyEquId='".$val['newId']."' where purchType='oa_sale_lease' and applyEquId='".$val['oldId']."'";
	  		$this->_db->query($planSql);
	  	}
	  }

	  function updateEquNum($docId){
	  	//��������
	  	$outStockSql = "UPDATE  oa_lease_equ  eq,(
			select relDocId as relDocItemId,sum(actOutNum) as actOutNum from (
				select oi.relDocId ,IF(o.isRed=0,oi.actOutNum,-oi.actOutNum)as actOutNum  FROM  oa_stock_outstock_item oi
				INNER JOIN oa_stock_outstock o on(o.id=oi.mainId)
				WHERE oi.relDocId <> 0 AND o.relDocType = 'XSCKDLHT' AND o.contractType='oa_sale_lease'
			UNION ALL
				select op.contEquId as relDocId,IF(o.isRed=0,oi.actOutNum,-oi.actOutNum)as actOutNum FROM oa_stock_outstock_item oi
				INNER JOIN oa_stock_outstock o on(o.id=oi.mainId)
				INNER JOIN oa_stock_outplan_product op on(oi.relDocId=op.id)
				WHERE oi.relDocId <> 0 AND o.relDocType = 'XSCKFHJH' AND o.contractType='oa_sale_lease'
			)sub GROUP BY relDocId) sub1
			set eq.executedNum=sub1.actOutNum
				where eq.id=sub1.relDocItemId  and eq.executedNum<>sub1.actOutNum AND eq.orderId='".$docId."'";
			$this->_db->query($outStockSql);
	  	//�ƻ�����
	  	$outPlanSql = "UPDATE oa_lease_equ e LEFT JOIN (
						SELECT e.id,e.orderId,e.issuedShipNum,e.number,c.* FROM oa_lease_equ e LEFT JOIN (
						SELECT
						IFNULL(sum(op.number),0) AS pNumber,
						op.contEquId,
						o.id AS oId,
						o.docCode AS oDocCode
						FROM
						oa_stock_outplan_product op
						RIGHT JOIN oa_stock_outplan o ON (o.id = op.mainId)
						WHERE
						 op.contEquId is not NULL AND o.docType='oa_sale_lease' AND op.isDelete=0
						GROUP BY
						op.contEquId,o.docId HAVING op.contEquId<>0
						)c ON e.id=c.contEquId
						) p
						ON (e.id=p.contEquId AND e.orderId=p.orderId)
						SET e.issuedShipNum=p.pNumber
						WHERE p.pNumber is not NULL and e.orderId='".$docId."'";
			$this->_db->query($outPlanSql);
	  	//�ɹ�����
	  	$purchSql = "UPDATE oa_lease_equ e LEFT JOIN (
							SELECT e.id,e.orderId,e.issuedPurNum,e.number,c.* FROM oa_lease_equ e LEFT JOIN (
							SELECT
							IFNULL(sum(op.amountAll),0) AS pNumber,
							op.applyEquId,
							o.id AS oId
							FROM
							oa_purch_plan_equ op
							RIGHT JOIN oa_purch_plan_basic o ON (o.id = op.basicId)
							WHERE
							 op.applyEquId is not NULL AND o.purchType='oa_sale_lease'
							GROUP BY
							op.applyEquId,o.sourceID HAVING op.applyEquId<>0
							)c ON e.id=c.applyEquId
							) p
							ON (e.id=p.applyEquId AND e.orderId=p.orderId)
							SET e.issuedPurNum=p.pNumber
							WHERE p.pNumber is not NULL  and e.orderId='".$docId."'";
			$this->_db->query($purchSql);
	  	//����״̬
	  	$shipStatusSql = "UPDATE oa_sale_lease c inner JOIN (
							SELECT c.orderId,
									CASE WHEN ( c.countNum<=0 ) THEN '8'
											 WHEN ( c.countNum>0 AND c.executedNum=0 ) THEN '7'
											 WHEN ( c.countNum>0 AND c.executedNum>0 ) THEN '10'
									END AS DeliveryStatus
							FROM (select count(0) as equCount,sum(IF (c.remainNum>0,1,0)) AS countNum,c.orderId,
											(select sum(o.executedNum) from oa_lease_equ o where o.orderId=c.orderId and o.isTemp=0 and o.isDel=0) as executedNum
											from (select e.id,e.orderId,(e.number-e.executedNum + e.backNum) as remainNum from oa_lease_equ e
											where e.isTemp=0 and e.isDel=0) c where 1=1 GROUP BY orderId HAVING orderId is NOT NULL) c
							)e ON ( c.id=e.orderId )
							SET c.DeliveryStatus=e.DeliveryStatus
							WHERE c.DeliveryStatus<>'11' and c.id='".$docId."'";
			$this->_db->query($shipStatusSql);
	  }

   	/********************************************************************************/
	/**
	 * ��ȡ��ͬ��������
	 */
	function getAllOrder_d() {
		$conarr = array ();
		//��ȡ������Ϣ
		$conarr['info'] = $this->disposeOrderArr($this->exeSql($this->tbl_name));
		//��ȡ�ӱ���Ϣ
		$equDao = new model_contract_rental_tentalcontractequ();
		$conarr['orderequ'] = $this->disposeEquArr($this->exeSql($equDao->tbl_name));
		$trainingplanDao = new model_contract_rental_trainingplan(); //��ѵ�ƻ�
		$conarr['trainingplan'] = $this->disposeTrainArr($this->exeSql($trainingplanDao->tbl_name));
		return $conarr;
	}
	//��ѯsql
	function exeSql($tableName) {
		$sql = " select * from " . $tableName . " ";
		return $this->_db->getArray($sql);
	}
	//ѭ�������ͬ��Ϣ
	function disposeOrderArr($arr) {
		if (empty ($arr)) {
			return "";
		} else {
			$temparr = array ();
			foreach ($arr as $k => $v) {
				$temparr[$k]['oldId'] = $v['id'];
				$temparr[$k]['oldTableName'] = "oa_sale_lease";
				$temparr[$k]['objCode'] = $v['objCode'];
				$temparr[$k]['sign'] = $v['sign'];
				$temparr[$k]['signSubjectName'] = "���Ͷ���";
				$temparr[$k]['signSubject'] = "DL";
				$temparr[$k]['oldContractType'] = "HTLX-ZLHT";
				if ($v['sign'] == "��") {
					$temparr[$k]['winRate'] = "100%";
				} else {
					$temparr[$k]['winRate'] = "80%";
				}
				$temparr[$k]['contractType'] = "HTLX-ZLHT";
				$temparr[$k]['contractTypeName'] = "���޺�ͬ";
				$temparr[$k]['contractNature'] = $v['orderNature'];
				$temparr[$k]['contractNatureName'] = $v['orderNatureName'];
				if (empty ($v['orderCode'])) {
					$temparr[$k]['contractCode'] = $v['orderTempCode'];
				} else {
					$temparr[$k]['contractCode'] = $v['orderCode'];
				}
				$temparr[$k]['contractName'] = $v['orderName'];
				$temparr[$k]['customerName'] = $v['tenant'];
				$temparr[$k]['customerId'] = $v['tenantId'];
				$temparr[$k]['customerType'] = $v['customerType'];
				$temparr[$k]['contractCountry'] = "�й�";
				$temparr[$k]['contractCountryId'] = "1";
				$temparr[$k]['contractProvince'] = $v['orderProvince'];
				$temparr[$k]['contractProvinceId'] = $v['orderProvinceId'];
				$temparr[$k]['contractCity'] = $v['orderCity'];
				$temparr[$k]['contractCityId'] = $v['orderCityId'];
				$temparr[$k]['prinvipalName'] = $v['hiresName']; //������
				$temparr[$k]['prinvipalId'] = $v['hiresId'];
				$temparr[$k]['contractSigner'] = $v['createName'];
				$temparr[$k]['contractSignerId'] = $v['createId'];
				if (empty ($v['orderMoney']) || $v['orderMoney'] == '0') {
					$temparr[$k]['contractMoney'] = $v['orderTempMoney'];
				} else {
					$temparr[$k]['contractMoney'] = $v['orderMoney'];
				}
				$temparr[$k]['invoiceType'] = $v['invoiceType'];
				$temparr[$k]['deliveryDate'] = $v['beginTime'];
				$temparr[$k]['contractInputName'] = $v['createName'];
				$temparr[$k]['contractInputId'] = $v['createId'];
				$temparr[$k]['enteringDate'] = $v['createTime'];
				$temparr[$k]['createTime'] = $v['createTime'];
				$temparr[$k]['createName'] = $v['createName'];
				$temparr[$k]['createId'] = $v['createId'];
				$temparr[$k]['ExaStatus'] = $v['ExaStatus'];
				$temparr[$k]['ExaDT'] = $v['ExaDT'];
				$temparr[$k]['closeName'] = $v['closeName'];
				$temparr[$k]['closeId'] = "";
				$temparr[$k]['closeTime'] = $v['closeTime'];
				$temparr[$k]['closeType'] = $v['closeType'];
				$temparr[$k]['closeRegard'] = $v['closeRegard'];
				$temparr[$k]['signStatus'] = $v['signIn'];
				$temparr[$k]['signName'] = $v['signName'];
				$temparr[$k]['signNameId'] = $v['signNameId'];
				$temparr[$k]['signDetail'] = $v['signDetail'];
				$temparr[$k]['signRemark'] = $v['signRemark'];
				$temparr[$k]['areaName'] = $v['areaName'];
				$temparr[$k]['areaPrincipal'] = $v['areaPrincipal'];
				$temparr[$k]['areaPrincipalId'] = $v['areaPrincipalId'];
				$temparr[$k]['areaCode'] = $v['areaCode'];
				$temparr[$k]['remark'] = $v['remark'];
				$temparr[$k]['currency'] = $v['currency'];
				$temparr[$k]['rate'] = $v['rate'];
				if (empty ($v['orderMoneyCur']) || $v['orderMoneyCur'] == '0') {
					$temparr[$k]['contractMoneyCur'] = $v['orderTempMoneyCur'];
				} else {
					$temparr[$k]['contractMoneyCur'] = $v['orderMoneyCur'];
				}
				$temparr[$k]['isTemp'] = $v['isTemp'];
				$temparr[$k]['originalId'] = $v['originalId'];
				$temparr[$k]['changeTips'] = $v['changeTips'];
				$temparr[$k]['isBecome'] = $v['isBecome'];
				$temparr[$k]['shipCondition'] = $v['shipCondition'];
				$temparr[$k]['contractState'] = $v['orderstate'];
				$temparr[$k]['state'] = $v['state'];
				$temparr[$k]['deductMoney'] = $v['deductMoney'];
                $temparr[$k]['badMoney'] = $v['badMoney'];
                $temparr[$k]['serviceconfirmMoney'] = $v['serviceconfirmMoney'];
                $temparr[$k]['financeconfirmMoney'] = $v['financeconfirmMoney'];
                $temparr[$k]['serviceconfirmMoneyAll'] = $v['serviceconfirmMoneyAll'];
                $temparr[$k]['financeconfirmMoneyAll'] = $v['financeconfirmMoneyAll'];
                $temparr[$k]['gross'] = $v['gross'];
                $temparr[$k]['rateOfGross'] = $v['rateOfGross'];
                $temparr[$k]['projectProcessAll'] = $v['projectProcess'];
                $temparr[$k]['processMoney'] = $v['processMoney'];
				switch ($v['DeliveryStatus']) {
					case '7' :
						$temparr[$k]['DeliveryStatus'] = "WFH";
						break;
					case '8' :
						$temparr[$k]['DeliveryStatus'] = "YFH";
						break;
					case '9' :
						$temparr[$k]['DeliveryStatus'] = "YCGB";
						break;
					case '10' :
						$temparr[$k]['DeliveryStatus'] = "BFFH";
						break;
					case '11' :
						$temparr[$k]['DeliveryStatus'] = "TZFH";
						break;
				}
			}
		}
		return $temparr;
	}
	function disposeEquArr($arr) {
		if (empty ($arr)) {
			return "";
		} else {
			$temparr = array ();
			foreach ($arr as $k => $v) {
				$temparr[$k]['tablename'] = "oa_lease_equ";
				$temparr[$k]['oldId'] = $v['id'];
				$temparr[$k]['oldorderId'] = $v['orderId'];
				$temparr[$k]['productName'] = $v['productName'];
				$temparr[$k]['productId'] = $v['productId'];
				$temparr[$k]['productCode'] = $v['productNo'];
				$temparr[$k]['productModel'] = $v['productModel'];
				$temparr[$k]['productType'] = $v['productType'];
				$temparr[$k]['number'] = $v['number'];
				$temparr[$k]['remark'] = $v['remark'];
				$temparr[$k]['price'] = $v['price'];
				$temparr[$k]['unitName'] = $v['unitName'];
				$temparr[$k]['money'] = $v['money'];
				$temparr[$k]['warrantyPeriod'] = $v['warrantyPeriod'];
				$temparr[$k]['license'] = $v['license'];
				$temparr[$k]['issuedShipNum'] = $v['issuedShipNum'];
				$temparr[$k]['executedNum'] = $v['executedNum'];
				$temparr[$k]['backNum'] = $v['backNum'];
				$temparr[$k]['onWayNum'] = $v['onWayNum'];
				$temparr[$k]['purchasedNum'] = $v['purchasedNum'];
				$temparr[$k]['issuedPurNum'] = $v['issuedPurNum'];
				$temparr[$k]['issuedProNum'] = $v['issuedProNum'];
				$temparr[$k]['changeTips'] = $v['changeTips'];
				$temparr[$k]['isTemp'] = $v['isTemp'];
				$temparr[$k]['originalId'] = $v['originalId'];
				$temparr[$k]['isDel'] = $v['isDel'];
				$temparr[$k]['isCon'] = $v['isCon'];
				$temparr[$k]['isConfig'] = $v['isConfig'];
				$temparr[$k]['isNeedDelivery'] = $v['isNeedDelivery'];
				$temparr[$k]['isBorrowToorder'] = $v['isBorrowToorder'];
			}
		}
		return $temparr;
	}
	function disposeTrainArr($arr) {
		if (empty ($arr)) {
			return "";
		} else {
			$temparr = array ();
			foreach ($arr as $k => $v) {
				$temparr[$k]['tablename'] = "oa_lease_trainingplan";
				$temparr[$k]['oldId'] = $v['id'];
				$temparr[$k]['oldorderId'] = $v['orderId'];
				$temparr[$k]['beginDT'] = $v['beginDT'];
				$temparr[$k]['endDT'] = $v['endDT'];
				$temparr[$k]['traNum'] = $v['traNum'];
				$temparr[$k]['adress'] = $v['adress'];
				$temparr[$k]['content'] = $v['content'];
				$temparr[$k]['trainer'] = $v['trainer'];
				$temparr[$k]['isOver'] = $v['isOver'];
				$temparr[$k]['overDT'] = $v['overDT'];
			}
			return $temparr;
		}
	}
	/**
	 * ���ݺ�ͬid ���Һ�ͬ��Ϣ����ID��
	 */
	function getOrderByIds($objId){

       if(!empty($objId)){
       	 $sql = "select * from ".$this->tbl_name." where id in ($objId)";
         $rows = $this->_db->getArray($sql);
       }else{
       	 $rows = "";
       }
          return $rows;
	}
 }
?>