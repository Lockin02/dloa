<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @author LiuBo
 * @Date 2011��3��3�� 19:08:54
 * @version 1.0
 * @description:�������� Model�� ��Ŀ����
 */
 class model_projectmanagent_order_order  extends model_base {

	function __construct() {
		include (WEB_TOR."model/common/mailConfig.php");
		$this->tbl_name = "oa_sale_order";
		$this->sql_map = "projectmanagent/order/orderSql.php";
		$this->mailArr=$mailUser[$this->tbl_name];

		parent::__construct ();
        //���ó�ʼ�����������
		parent::setObjAss ();
		$this->statusDao = new model_common_status();
		$this->statusDao->status = array(
			0 => array (
				'statusEName' => 'uncommit',
				'statusCName' => 'δ�ύ',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'save',
				'statusCName' => '������',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'execute',
				'statusCName' => 'ִ����',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'close',
				'statusCName' => '�ѹر�',
				'key' => '3'
			),
			4 => array (
	            'statusEName' => 'running',
	            'statusCName' => '��ִ��',
	            'key' => '4'

			),
			5 => array (
	            'statusEName' => 'merge',
	            'statusCName' => '�Ѻϲ�',
	            'key' => '5'

			),
			6 => array (
	            'statusEName' => 'split',
	            'statusCName' => '�Ѳ��',
	            'key' => '6'

			),
			7 => array (
	            'statusEName' => 'shipmentsno',
	            'statusCName' => 'δ����',
	            'key' => '7'

			),
			8 => array (
	            'statusEName' => 'shipmentsyes',
	            'statusCName' => '�ѷ���',
	            'key' => '8'

			),
			9 => array (
			    'statusEname' => 'unClose',
			    'statusCname' => '�쳣�ر�',
			    'key' => '9'
			),
			10 => array (
			    'statusEname' => 'part',
			    'statusCname' => '���ַ���',
			    'key' => '10'
			),
			11 => array (
			    'statusEname' => 'Stop',
			    'statusCname' => 'ֹͣ����',
			    'key' => '11'
			),
			12 => array (
			    'statusEname' => 'change',
			    'statusCname' => '���������',
			    'key' => '12'
			)

		);


	}



	/**
	 * ��дadd_d����

	 */
	function add_d($object){
		try{
			$this->start_d();

			//������Զ���������
			$orderCodeDao = new model_common_codeRule ();
			if ($object ['orderInput'] == "1") {
				if ($object ['sign'] == "��") {
					$object ['orderTempCode'] = $orderCodeDao->contractCode ( $this->tbl_name, $object ['customerId'] );
					if(empty($object['orderCode'])){
						$object ['orderTempCode'] = "LS".$object ['orderTempCode'];
					}
				} else if ($object ['sign'] == "��") {
					$object ['orderCode'] = $orderCodeDao->contractCode ( $this->tbl_name, $object ['customerId'] );
				}
			}else{
				if(!empty($object['orderTempCode']) && empty($object['orderCode'])){
					$object ['orderTempCode'] = "LS".$object ['orderTempCode'];
				}
			}
			$prinvipalId=$object['prinvipalId'];
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
			//�ͻ���ϵ��
			if(!empty($object['linkman'])){
				$orderlinkmanDao = new model_projectmanagent_order_linkman();
				$orderlinkmanDao ->createBatch($object['linkman'],array('orderId' => $newId),'linkman');
			}
			//�豸
			 if(!empty($object['orderequ'])){
			 	$orderequDao = new model_projectmanagent_order_orderequ();
			    $orderequDao->createBatch($object['orderequ'],array('orderId' => $newId ,'orderCode'=>$object['orderCode']),'productName');
			    $orderequDao->updateUniqueCode_d($newId);

				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $newId, 'objType' => $this->tbl_name , 'extType' => $orderequDao->tbl_name ),
					'orderId',
					'license'
				);
			 }
			//�Զ����嵥
			if(!empty($object['customizelist'])){
				$customizelistDao = new model_projectmanagent_order_customizelist();
				$customizelistDao->createBatch($object['customizelist'],array('orderId' => $newId,'orderName' => $object['orderName']),'productName');
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $newId, 'objType' => $this->tbl_name , 'extType' => $customizelistDao->tbl_name ),
					'orderId',
					'license'
				);
			}
            //��Ʊ�ƻ�
            $orderInvoiceDao = new model_projectmanagent_order_invoice();
            if(!empty($object['invoice'])){
            	$orderInvoiceDao ->createBatch($object['invoice'],array('orderId' => $newId),'money');
            }

            //�տ�ƻ�
            $orderReceiptplanDao = new model_projectmanagent_order_receiptplan();
            if(!empty($object['receiptplan'])){
            	$orderReceiptplanDao -> createBatch($object['receiptplan'],array('orderId' => $newId),'money');
            }
            //��ѵ�ƻ�
            $orderTrainingplanDao = new model_projectmanagent_order_trainingplan();
            if(!empty($object['trainingplan'])){
            	$orderTrainingplanDao -> createBatch($object['trainingplan'],array('orderId' => $newId),'beginDT');
            }

            //�ϲ�����
            if(!empty($object['orderCode']) && !empty($object['orderTempCode'])){
				$this->changeOrderStatus_d($newId,$object['orderTempCode']);
            }
            //���������ƺ�Id
		     $this->updateObjWithFile($newId);
			$this->commit_d();
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
		return $this->query($sql);
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

			$orderId = $object['id'];

			//����ӱ���Ϣ
			//�ͻ���ϵ��

            $linkmanDao = new model_projectmanagent_order_linkman();
            $linkmanDao->delete(array('orderId' => $orderId));
            $linkmanDao->createBatch($object['linkman'],array('orderId' => $orderId),'linkman');
			//�豸
			$orderequDao = new model_projectmanagent_order_orderequ();
            $orderequDao->delete(array('orderId' => $orderId,'isBorrowToorder' => '0'));
			$orderequDao->createBatch($object['orderequ'],array('orderId' => $orderId ),'productName');
            $orderequDao->updateUniqueCode_d($orderId);
			if($object['orderequ']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $orderId, 'objType' => $this->tbl_name , 'extType' => $orderequDao->tbl_name ),
					'orderId',
					'license'
				);
			}

            //�Զ����嵥
            $orderCustomizeDao = new model_projectmanagent_order_customizelist();
            $orderCustomizeDao->delete(array('orderId' => $orderId));
            $orderCustomizeDao->createBatch($object['customizelist'],array('orderId' => $orderId,'orderName' => $object['orderName']),'productName');
            if($object['customizelist']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $orderId, 'objType' => $this->tbl_name , 'extType' => $orderCustomizeDao->tbl_name ),
					'orderId',
					'license'
				);
			}
            //��Ʊ��Ϣ
            $orderInvoiceDao = new model_projectmanagent_order_invoice();
            $orderInvoiceDao->delete(array('orderId' => $orderId));
            $orderInvoiceDao->createBatch($object['invoice'],array('orderId' => $orderId),'money');
            //�տ�ƻ�
            $orderReceiptplanDao = new model_projectmanagent_order_receiptplan();
            $orderReceiptplanDao->delete(array('orderId' => $orderId));
            $orderReceiptplanDao->createBatch($object['receiptplan'],array('orderId' => $orderId),'money');
            //��ѵ�ƻ�
            $orderTrainingplanDao = new model_projectmanagent_order_trainingplan();
            $orderTrainingplanDao->delete(array('orderId' => $orderId));
            $orderTrainingplanDao->createBatch($object['trainingplan'],array('orderId' => $orderId),'beginDT');
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
			$orderId = $object['id'];

			//����ӱ���Ϣ
			//�ͻ���ϵ��

            $linkmanDao = new model_projectmanagent_order_linkman();
            $linkmanDao->delete(array('orderId' => $orderId));
            $linkmanDao->createBatch($object['linkman'],array('orderId' => $orderId),'linkman');
			//�豸
			$orderequDao = new model_projectmanagent_order_orderequ();
			foreach($object['orderequ'] as $k => $v){
				$object['orderequ'][$k]['oldId'] = $v['proId'];
			}
            foreach($object['orderequ'] as $k => $v){
            	 if($v['proId'] && empty($v['isEdit']) && empty($v['isDel'])){
            	 	$v['id'] = $v['proId'];
            	 	$orderequDao->edit_d($v);
            	 }
                 if($v['isDel']){
                     $sql = "update ".$orderequDao->tbl_name." set isDel = 1 where id = ".$v['proId']." ";
			         $this->_db->query($sql);
                 }
                 if($v['isEdit'] && empty($v['isDel'])){
                     $sql = "update ".$orderequDao->tbl_name." set isDel = 1 where id = ".$v['proId']." ";
			         $this->_db->query($sql);
			         $v['orderId'] = $orderId;
			          if(!empty($v['productName'])){
                        $proId = $orderequDao->add_d($v);
			          }
                     $orderequDao->updateUniqueCode_d($orderId);
                     $licenseDao = new model_yxlicense_license_tempKey();
						$licenseDao->updateLicenseBacth_d(
							array( 'objId' => $orderId, 'objType' => $this->tbl_name , 'extType' => $orderequDao->tbl_name ),
							'orderId',
							'license'
						);
                 }
                 if($v['isAdd']){
                 	 $v['orderId'] = $orderId;
                 	  if(!empty($v['productName'])){
                         $proId = $orderequDao->add_d($v);
                 	  }
                     $orderequDao->updateUniqueCode_d($orderId);
                     $licenseDao = new model_yxlicense_license_tempKey();
						$licenseDao->updateLicenseBacth_d(
							array( 'objId' => $orderId, 'objType' => $this->tbl_name , 'extType' => $orderequDao->tbl_name ),
							'orderId',
							'license'
						);
                 }
                 $v['id'] = $v['proId'];
                 $v['oldId'] = $v['proId'];
                 $equ[$k] = $v;

            }

            //�Զ����嵥
            $orderCustomizeDao = new model_projectmanagent_order_customizelist();
            $orderCustomizeDao->delete(array('orderId' => $orderId));
            $orderCustomizeDao->createBatch($object['customizelist'],array('orderId' => $orderId,'orderName' => $object['orderName']),'productName');
            if($object['customizelist']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $orderId, 'objType' => $this->tbl_name , 'extType' => $orderCustomizeDao->tbl_name ),
					'orderId',
					'license'
				);
			}
            //��Ʊ��Ϣ
            $orderInvoiceDao = new model_projectmanagent_order_invoice();
            $orderInvoiceDao->delete(array('orderId' => $orderId));
            $orderInvoiceDao->createBatch($object['invoice'],array('orderId' => $orderId),'money');

            //�տ�ƻ�
            $orderReceiptplanDao = new model_projectmanagent_order_receiptplan();
            $orderReceiptplanDao->delete(array('orderId' => $orderId));
            $orderReceiptplanDao->createBatch($object['receiptplan'],array('orderId' => $orderId),'money');
            //��ѵ�ƻ�
            $orderTrainingplanDao = new model_projectmanagent_order_trainingplan();
            $orderTrainingplanDao->delete(array('orderId' => $orderId));
            $orderTrainingplanDao->createBatch($object['trainingplan'],array('orderId' => $orderId),'beginDT');

            //�����ʼ�
            //��ȡĬ�Ϸ�����
		   include (WEB_TOR."model/common/mailConfig.php");
		   $emailDao = new model_common_mail();
		   $emailInfo = $emailDao->contractBecomeEmail('���ۺ�ͬ',1,$_SESSION['USERNAME'],$_SESSION['EMAIL'],"contractBeomce",$object['orderTempCode'],"",$mailUser['contractBecome']['sendUserId']);


			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(exception $e){
            $this->rollBack();
			return false;
		}
	}
    /**
     * ���������޸�
     */
    function proedit_d($object){
		try{
			$this->start_d();
			$orderId = $object['id'];
			$orderequDao = new model_projectmanagent_order_orderequ();
			foreach($object['orderequ'] as $k => $v){
				$object['orderequ'][$k]['oldId'] = $v['proId'];
			}
			$orderInfo = parent::get_d($orderId);
            $orderInfo['oldId'] = $orderId;
			$orderInfo['orderequ'] = $object['orderequ'];
            //�������
			$changeLogDao = new model_common_changeLog ( 'order',false );
			$tempObjId = $changeLogDao->addLog ( $orderInfo );

            foreach($object['orderequ'] as $k => $v){
            	 if($v['proId'] && empty($v['isEdit']) && empty($v['isDel'])){
            	 	$v['id'] = $v['proId'];
            	 	$orderequDao->edit_d($v);
            	 }
                 if(isset($v['isDel']) && isset($v['proId'])){
                     $sql = "update ".$orderequDao->tbl_name." set isDel = 1 where id = ".$v['proId']." ";
			         $this->_db->query($sql);
                 }
                 if($v['isEdit'] && empty($v['isDel'])){
                     $sql = "update ".$orderequDao->tbl_name." set isDel = 1 where id = ".$v['proId']." ";
			         $this->_db->query($sql);
			         $v['orderId'] = $orderId;
                     $proId = $orderequDao->add_d($v);
                     $orderequDao->updateUniqueCode_d($orderId);
                     $licenseDao = new model_yxlicense_license_tempKey();
						$licenseDao->updateLicenseBacth_d(
							array( 'objId' => $orderId, 'objType' => $this->tbl_name , 'extType' => $orderequDao->tbl_name ),
							'orderId',
							'license'
						);
                 }
                 if($v['isAdd']){
                 	 $v['orderId'] = $orderId;
                     $proId = $orderequDao->add_d($v);
                     $orderequDao->updateUniqueCode_d($orderId);
                     $licenseDao = new model_yxlicense_license_tempKey();
						$licenseDao->updateLicenseBacth_d(
							array( 'objId' => $orderId, 'objType' => $this->tbl_name , 'extType' => $orderequDao->tbl_name ),
							'orderId',
							'license'
						);
                 }
                 $v['id'] = $v['proId'];
                 $v['oldId'] = $v['proId'];
                 $equ[$k] = $v;

            }

			$this->commit_d();
//			$this->rollBack();
			return $orderId;
		}catch(exception $e){

			return false;
		}
	}
	/**������ۺ�ͬ
	*author can
	*2011-6-1
	*/
	function change_d($obj) {
		try{
			$this->start_d ();
			//ɾ���豸����
			foreach ( $obj ['orderequ'] as $key => $val ) {
				if ($val ['number'] == 0) {
					$obj ['orderequ'] [$key] ['isDel'] = 1;
				}
			}
			//��ʱ�豸����
			if (is_array ( $obj ['orderequTemp'] )) {
				if (is_array ( $obj ['orderequ'] )) {			//�ж�ԭ��ͬ�Ƿ��в�Ʒ
					$obj ['orderequ'] = array_merge ( $obj ['orderequ'], $obj ['orderequTemp'] );
				}else{
					$obj ['orderequ'] = $obj ['orderequTemp'];
				}
			}
			//ɾ����ϵ��
			foreach ( $obj ['linkman'] as $key => $val ) {
				if ($val ['linkman'] == "") {
					$obj ['linkman'] [$key] ['isDel'] = 1;
				}
			}
			//��ʱ��ϵ�˴���
			if (is_array ( $obj ['linkmanTemp'] )) {
				if(is_array ( $obj ['linkman'] )){					//�ж�ԭ��ͬ�Ƿ�����ϵ��
					$obj ['linkman'] = array_merge ( $obj ['linkman'], $obj ['linkmanTemp'] );
				}else{
					$obj ['linkman'] =  $obj ['linkmanTemp'];
				}
			}
			//��ʱ��Ʊ�ƻ�����
			if (is_array ( $obj ['invoiceTemp'] )) {
				if(is_array ( $obj ['invoice'] )){					//�ж�ԭ��ͬ�Ƿ��п�Ʊ�ƻ�
					$obj ['invoice'] = array_merge ( $obj ['invoice'], $obj ['invoiceTemp'] );
				}else{
					$obj ['invoice'] =  $obj ['invoiceTemp'];
				}
			}
			//��ʱ�տ�ƻ�����
			if (is_array ( $obj ['receiptplanTemp'] )) {
				if(is_array ( $obj ['receiptplan'] )){					//�ж�ԭ��ͬ�Ƿ����տ�ƻ�
					$obj ['receiptplan'] = array_merge ( $obj ['receiptplan'], $obj ['receiptplanTemp'] );
				}else{
					$obj ['receiptplan'] =  $obj ['receiptplanTemp'];
				}
			}
			//��ʱ��ѵ�ƻ� ����
			if (is_array ( $obj ['trainingplanTemp'] )) {
				if(is_array ( $obj ['trainingplan'] )){					//�ж�ԭ��ͬ�Ƿ�����ѵ�ƻ�
					$obj ['trainingplan'] = array_merge ( $obj ['trainingplan'], $obj ['trainingplanTemp'] );
				}else{
					$obj ['trainingplan'] =  $obj ['trainingplanTemp'];
				}
			}

	        //���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$obj['orderNatureName'] = $datadictDao->getDataNameByCode ( $obj['orderNature'] );

			//�����������
			$changeLogDao = new model_common_changeLog ( 'order' );
			$obj ['uploadFiles'] = $changeLogDao->processUploadFile ( $obj, $this->tbl_name );
			if($obj['orderequ']){
			//�����ͬ����
				foreach($obj['orderequ'] as $key=>$val){
		            $obj['orderequ'][$key]['isSell'] = isset($obj['orderequ'][$key]['isSell'])?$obj['orderequ'][$key]['isSell']:null;
				}
			}
			//print_r($obj ['uploadFiles'] );
			//var_dump($obj ['uploadFiles']);
			//�����¼,�õ��������ʱ������id
			$tempObjId = $changeLogDao->addLog ( $obj );

			//license�������
			if($obj['orderequ']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $tempObjId, 'objType' => $this->tbl_name , 'extType' => 'oa_sale_order_equ' ),
					'orderId',
					'license'
				);
			}

			$this->commit_d ();
	//$this->rollBack();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return null;
		}

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
   /**
	 * ָ����ϵ��/�����˵��޸ķ���
	 */
	function ExecuteEdit_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		return $this->updateById ( $object );
	}
	/**
	 * ��дget_d����
	 */
	function get_d($id,$selection = null){
		//��ȡ������Ϣ
		$rows = parent::get_d($id);

		if(empty($selection)){
			//��ȡ�ӱ���Ϣ
			$orderlinkmanDao = new model_projectmanagent_order_linkman();//�ͻ���ϵ��
			$rows['linkman'] = $orderlinkmanDao -> getDetail_d($id);
			$orderequDao = new model_projectmanagent_order_orderequ();//�豸�嵥
			$rows['orderequ'] = $orderequDao->getDetail_d($id);
	        $orderCustomizeDao = new model_projectmanagent_order_customizelist();//�Զ����嵥
	        $rows['customizelist'] = $orderCustomizeDao->getDetail_d($id);
	        $orderInvoiceDao = new model_projectmanagent_order_invoice();//��Ʊ�ƻ�
	        $rows['invoice'] = $orderInvoiceDao->getDetail_d($id);
	        $orderReceiptplanDao = new model_projectmanagent_order_receiptplan();//�տ�ƻ�
	        $rows['receiptplan'] = $orderReceiptplanDao->getDetail_d($id);
	        $orderTrainingplanDao = new model_projectmanagent_order_trainingplan();//��ѵ�ƻ�
	        $rows['trainingplan'] = $orderTrainingplanDao->getDetail_d($id);
		}else if(is_array($selection)){
			if(in_array('orderequ',$selection)){
				$orderequDao = new model_projectmanagent_order_orderequ();//�豸�嵥
				$rows['orderequ'] = $orderequDao->getDetail_d($id);
			}
			if(in_array('customizelist',$selection)){
				$orderCustomizeDao = new model_projectmanagent_order_customizelist();//�Զ����嵥
	        	$rows['customizelist'] = $orderCustomizeDao->getDetail_d($id);
			}
			if(in_array('invoice',$selection)){
				$orderInvoiceDao = new model_projectmanagent_order_invoice();//��Ʊ�ƻ�
	      		$rows['invoice'] = $orderInvoiceDao->getDetail_d($id);
			}
			if(in_array('receiptplan',$selection)){
				$orderReceiptplanDao = new model_projectmanagent_order_receiptplan();//�տ�ƻ�
	        	$rows['receiptplan'] = $orderReceiptplanDao->getDetail_d($id);
			}
			if(in_array('trainingplan',$selection)){
				$orderTrainingplanDao = new model_projectmanagent_order_trainingplan();//��ѵ�ƻ�
	       		$rows['trainingplan'] = $orderTrainingplanDao->getDetail_d($id);
			}
		}

		return $rows;
	}


	/**
	 * ���ݺ�ͬ��Ż�ȡ��ͬ��Ϣ
	 */
	function getOrderByObjCode($objCode){
		 $objCode = explode(",",$objCode);
		foreach($objCode as $key => $val){
			$objCodeArr .=  "'$val'" .",";
		}
		  $objCodeArr = Trim($objCodeArr,',');
       if(!empty($objCodeArr)){
       	 $sql = "select * from view_oa_order where objCode in ($objCodeArr)";
         $rows = $this->_db->getArray($sql);
       }else{
       	 $rows = "";
       }
          return $rows;
	}
	/**
	 * ���ݺ�ͬid ���Һ�ͬ��Ϣ����ID��
	 */
	function getOrderByIds($objId){

       if(!empty($objId)){
       	 $sql = "select * from oa_sale_order where id in ($objId)";
         $rows = $this->_db->getArray($sql);
       }else{
       	 $rows = "";
       }
          return $rows;
	}
    //get_d ������
    function getShip_d($id,$selection = null){
		//��ȡ������Ϣ
		$rows = parent::get_d($id);

		if(empty($selection)){
			//��ȡ�ӱ���Ϣ
			$orderlinkmanDao = new model_projectmanagent_order_linkman();//�ͻ���ϵ��
			$rows['linkman'] = $orderlinkmanDao -> getDetail_d($id);
			$orderequDao = new model_projectmanagent_order_orderequ();//�豸�嵥
			$rows['orderequ'] = $orderequDao->getShip_d($id);
	        $orderCustomizeDao = new model_projectmanagent_order_customizelist();//�Զ����嵥
	        $rows['customizelist'] = $orderCustomizeDao->getDetail_d($id);
	        $orderInvoiceDao = new model_projectmanagent_order_invoice();//��Ʊ�ƻ�
	        $rows['invoice'] = $orderInvoiceDao->getDetail_d($id);
	        $orderReceiptplanDao = new model_projectmanagent_order_receiptplan();//�տ�ƻ�
	        $rows['receiptplan'] = $orderReceiptplanDao->getDetail_d($id);
	        $orderTrainingplanDao = new model_projectmanagent_order_trainingplan();//��ѵ�ƻ�
	        $rows['trainingplan'] = $orderTrainingplanDao->getDetail_d($id);
		}else if(is_array($selection)){
			if(in_array('orderequ',$selection)){
				$orderequDao = new model_projectmanagent_order_orderequ();//�豸�嵥
				$rows['orderequ'] = $orderequDao->getShip_d($id);
			}
			if(in_array('customizelist',$selection)){
				$orderCustomizeDao = new model_projectmanagent_order_customizelist();//�Զ����嵥
	        	$rows['customizelist'] = $orderCustomizeDao->getDetail_d($id);
			}
			if(in_array('invoice',$selection)){
				$orderInvoiceDao = new model_projectmanagent_order_invoice();//��Ʊ�ƻ�
	      		$rows['invoice'] = $orderInvoiceDao->getDetail_d($id);
			}
			if(in_array('receiptplan',$selection)){
				$orderReceiptplanDao = new model_projectmanagent_order_receiptplan();//�տ�ƻ�
	        	$rows['receiptplan'] = $orderReceiptplanDao->getDetail_d($id);
			}
			if(in_array('trainingplan',$selection)){
				$orderTrainingplanDao = new model_projectmanagent_order_trainingplan();//��ѵ�ƻ�
	       		$rows['trainingplan'] = $orderTrainingplanDao->getDetail_d($id);
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
        if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']&&$rows['prinvipalId'] != $_SESSION['USER_ID']){
            $rows = $this->filterWithoutField('���ۺ�ͬ���',$rows,'form',array('orderMoney','orderTempMoney'));
            $rows['orderequ'] = $this->filterWithoutField('�����豸���',$rows['orderequ'],'list',array('price','money'));
            $rows['linkman'] = $this->filterWithoutField('������ϵ�˿���',$rows['linkman'],'list',array('Email','telephone'));
        }
        return $rows;
    }

	/**
	 * ��Ⱦ���� - �鿴
	 */
	function initView($object){
        if(!empty($object['linkman'])){

        	$orderlinkmanDao = new model_projectmanagent_order_linkman();
        	$object['linkman'] = $orderlinkmanDao -> initTableView($object['linkman']);
        }else{
        	$object['linkman'] = '<tr><td colspan="10">���������Ϣ</td></tr>';
        }
		if(!empty($object['orderequ'])){

			$orderequDao = new model_projectmanagent_order_orderequ();
			$object['orderequ'] = $orderequDao->initTableView($object['orderequ'],$object['id']);
		}else{
			$object['orderequ'] = '<tr><td colspan="12">���������Ϣ</td></tr>';
		}
        if(!empty($object['customizelist'])){
        	$orderCustomizeDao = new model_projectmanagent_order_customizelist();
        	$object['customizelist'] = $orderCustomizeDao->initTableView($object['customizelist']);

        }else{
			$object['customizelist'] = '<tr><td colspan="11">���������Ϣ</td></tr>';
		}
		if(!empty($object['invoice'])){
			 $orderInvoiceDao = new model_projectmanagent_order_invoice();//��Ʊ�ƻ�
             $object['invoice'] = $orderInvoiceDao->initTableView($object['invoice']);
		}else{
			$object['invoice'] = '<tr><td colspan="6">���������Ϣ</td></tr>';
		}
		if(!empty($object['receiptplan'])){
			 $orderReceiptplanDao = new model_projectmanagent_order_receiptplan();//�տ�ƻ�
             $object['receiptplan'] = $orderReceiptplanDao->initTableView($object['receiptplan']);
		}else{
			$object['receiptplan'] = '<tr><td colspan="5">���������Ϣ</td></tr>';
		}
		if(!empty($object['trainingplan'])){
			$orderTrainingplanDao = new model_projectmanagent_order_trainingplan();//��ѵ�ƻ�
            $object['trainingplan'] = $orderTrainingplanDao->initTableView($object['trainingplan']);
		}else{
			$object['trainingplan'] = '<tr><td colspan="7">���������Ϣ</td></tr>';
		}
		return $object;
	}

	/**
	 * ��Ⱦ���� - �༭
	 */
	function initEdit($object){
        //�ͻ���ϵ��
        $linkmanDao = new model_projectmanagent_order_linkman();
        $rows = $linkmanDao->initTableEdit($object['linkman']);
        $object['linkNum'] = $rows[0];
        $object['linkman'] = $rows[1];
		//�豸
		$orderequDao = new model_projectmanagent_order_orderequ();
		$rows = $orderequDao->initTableEdit($object['orderequ']);
		$object['orderequ'] = $rows[0];
		$object['productNumber'] = $rows[1];
        //�Զ����嵥
		$orderCustomizelistDao = new model_projectmanagent_order_customizelist();
	    $rows = $orderCustomizelistDao->initTableEdit($object['customizelist']);
	    $object['PreNum'] = $rows[1];
	    $object['customizelist'] = $rows[0];
        //��Ʊ�ƻ�
        $orderInvoiceDao = new model_projectmanagent_order_invoice();
        $rows = $orderInvoiceDao->initTableEdit($object['invoice']);
        $object['InvNum'] = $rows[0];
        $object['invoice'] = $rows[1];
        //�տ�ƻ�
        $orderReceiptplanDao = new model_projectmanagent_order_receiptplan();
        $rows = $orderReceiptplanDao->initTableEdit($object['receiptplan']);
        $object['PayNum'] = $rows[0];
        $object['receiptplan'] = $rows[1];
        //��ѵ�ƻ�
        $orderTrainingplanDao = new model_projectmanagent_order_trainingplan();
        $rows = $orderTrainingplanDao->initTableEdit($object['trainingplan']);
        $object['TraNumber'] = $rows[0];
        $object['trainingplan'] = $rows[1];

		return $object;
	}

	/**
	 * ��Ⱦ���� - ת��
	 */
	function becomeEdit($object){
        //�ͻ���ϵ��
        $linkmanDao = new model_projectmanagent_order_linkman();
        $rows = $linkmanDao->initTableEdit($object['linkman']);
        $object['linkNum'] = $rows[0];
        $object['linkman'] = $rows[1];
		//�豸
		$orderequDao = new model_projectmanagent_order_orderequ();
		$rows = $orderequDao->proTableEdit($object['orderequ']);
		$object['orderequ'] = $rows[0];
		$object['productNumber'] = $rows[1];
        //�Զ����嵥
		$orderCustomizelistDao = new model_projectmanagent_order_customizelist();
	    $rows = $orderCustomizelistDao->initTableEdit($object['customizelist']);
	    $object['PreNum'] = $rows[1];
	    $object['customizelist'] = $rows[0];
        //��Ʊ�ƻ�
        $orderInvoiceDao = new model_projectmanagent_order_invoice();
        $rows = $orderInvoiceDao->initTableEdit($object['invoice']);
        $object['InvNum'] = $rows[0];
        $object['invoice'] = $rows[1];
        //�տ�ƻ�
        $orderReceiptplanDao = new model_projectmanagent_order_receiptplan();
        $rows = $orderReceiptplanDao->initTableEdit($object['receiptplan']);
        $object['PayNum'] = $rows[0];
        $object['receiptplan'] = $rows[1];
        //��ѵ�ƻ�
        $orderTrainingplanDao = new model_projectmanagent_order_trainingplan();
        $rows = $orderTrainingplanDao->initTableEdit($object['trainingplan']);
        $object['TraNumber'] = $rows[0];
        $object['trainingplan'] = $rows[1];

		return $object;
	}
	/**
	 * ���������޸���Ⱦ����
	 */
	 function editProduct($object){

		//�豸
		$orderequDao = new model_projectmanagent_order_orderequ();
		$rows = $orderequDao->proTableEdit($object['orderequ']);
		$object['orderequ'] = $rows[0];
		$object['productNumber'] = $rows[1];
		return $object;
	}
	/**
	 * ��Ⱦ���� -���
	 */
	function initChange($object){
        //�ͻ���ϵ��
        $linkmanDao = new model_projectmanagent_order_linkman();
        $rows = $linkmanDao->initTableChange($object['linkman']);
        $object['linkNum'] = $rows[0];
        $object['linkman'] = $rows[1];
		//�豸
		$orderequDao = new model_projectmanagent_order_orderequ();
		$rows = $orderequDao->initTableChange($object['orderequ']);
		$object['orderequ'] = $rows[0];
		$object['productNumber'] = $rows[1];
        //��Ʊ�ƻ�
        $orderInvoiceDao = new model_projectmanagent_order_invoice();
        $rows = $orderInvoiceDao->initTableChange($object['invoice']);
        $object['InvNum'] = $rows[0];
        $object['invoice'] = $rows[1];
        //�տ�ƻ�
        $orderReceiptplanDao = new model_projectmanagent_order_receiptplan();
        $rows = $orderReceiptplanDao->initTableChange($object['receiptplan']);
        $object['PayNum'] = $rows[0];
        $object['receiptplan'] = $rows[1];
        //��ѵ�ƻ�
        $orderTrainingplanDao = new model_projectmanagent_order_trainingplan();
        $rows = $orderTrainingplanDao->initTableChange($object['trainingplan']);
        $object['TraNumber'] = $rows[0];
        $object['trainingplan'] = $rows[1];

		return $object;
	}

	/**
	 * ����ת��Ŀ��Ϣ
	 */
	 function transition($obj) {

          $objDao = new model_projectmanagent_business_clues();
          $rows= $objDao->get_d($obj['id']);

          return $rows;

	 }

	 /**
	  * ����ǩ����ͬ--add����
	  */
	function salesAdd_d($rows) {

           try{
			$this->start_d();
           // �����ͬ��Ϣ
              $salesDao = new model_contract_sales_sales();
               $salesId = $salesDao->add_d($rows);
           //�����ͬ�豸��Ϣ
           $equipment = new model_contract_equipment_equipment();
		   $equ = $equipment->orderListEqu($rows['orderequ']);
		   $equipment->createBatch($equ);
           //�����������Ϣ
            foreach ( $rows as $key => $val) {
            	$sales[0] = Array(
            	    'cluseId' => "",
            	    'cluseCode' => "",
            	    'chanceId' => "",
                    'chanceCode' => "",
                    'projectId' => $rows['orderId'],
                    'projectCode' =>$rows['orderCode'],
                    'projectType' => "���۶���",
                    'contractUnique' => "",
                    'contractCode' => "",
                    'contractNumber' => $rows['contNumber'],
                    'contractId' => $salesId,
                    'contractType' => "���ۺ�ͬ"
            	);
            }

            $this->objass->addModelObjs('projectInfo' , $sales);

            //�ı䶩����״̬Ϊ����ǩ��ͬ��
            $state = $this->statusDao->statusEtoK('contract');
            $this->updateField(array( "id"=>$rows[orderId]) , "state" , $state );

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}
	/***********************************************************************************/
/**
 * ��ָı��ͬ״̬
 */
//	function thisUpdateOver_d($id){
//		return $this->updateField(array( "id"=> $id) , "state" , 0 );
//	}
	function thisUpdate_d($id){
		return $this->updateField(array( "id"=> $id) , "state" , 6 );
	}

/**
 * �鿴�ϲ������ʱ��ͬ��Ϣ
 */
	function TempOrderView($row,$skey) {
		foreach( $row as $key=>$val){
             $temp = array('orderTempCode' => $val);
             $TempId = implode('',$this->find($temp,null,'id'));

            $row[$key].='<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=projectmanagent_order_order&action=toViewTab&id='.$TempId.'&perm=view&skey='.$skey.'\')">';
		} ;

		return implode(',',$row);
	}

	/**
	 * ���ݷ�������޸ĺ�ͬ�������ƻ�״̬
	 */
	 function updateOrderShipStatus_d( $id ){
	 	$orderRemainSql = "select count(0) as countNum,(select sum(o.executedNum) from oa_sale_order_equ o where o.orderId=".$id." and o.isTemp=0 and o.isDel=0) as executeNum
						 from (select e.orderId,(e.number-e.executedNum) as remainNum from oa_sale_order_equ e
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

	 	$this->updateProjectProcess(array("id"=>$id,"tablename"=>"oa_sale_order"));//���¹��������ȡ������������Ⱥ�ͬ��
	 	return 0;
	 }
    /**
     * �ı䷢��״̬ --- �ر�
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
/***********************************************************************************/
     /**
      * ����������豸��Ϣ
      */
     function showDetaiInfo($rows) {

     	$orderequDao = new model_projectmanagent_order_orderequ();

     	$rows['orderequ'] =
     	$orderequDao->showDetailByOrder( $orderequDao->showEquListInByOrder($rows['id'],'oa_sale_order'));

     	return $rows;
     }


	/**
	 * ������Ŀ��Ż�ȡ��ͬ�б�
	 */
	function getContractByOrderCode($orderCode,$key = null ){
		$salesDao = new model_contract_sales_sales();
		if(!empty($key)){
			$salesDao->searchArr['exaStatus'] = AUDITED;
		}
		$salesDao->searchArr['equtemporaryNo'] = $orderCode;
		return $salesDao->listBySqlId();


	}

	/**
	 * ����ɾ������
	 */
	function deletesInfo_d($ids) {
		try {
			$this->deletes ( $ids );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}

	}


     /**
      * ǩ����ͬ�ӱ��滻����
      */
     function salesListByOrder($object) {
        //�豸

		$rows = $this->orderListEqu_d($object['orderequ']);
		$object['orderequ'] = $rows[1];
		$object['EquNum'] = $rows[0];
        //�Զ����嵥

	    $rows = $this->customizelist_d($object['customizelist']);
	    $object['PreNum'] = $rows[0];
	    $object['customizelist'] = $rows[1];
        //��Ʊ�ƻ�
        $orderInvoiceDao = new model_projectmanagent_order_invoice();
        $rows = $orderInvoiceDao->initTableEdit($object['invoice']);
        $object['InvNum'] = $rows[0];
        $object['invoice'] = $rows[1];
        //�տ�ƻ�
        $orderReceiptplanDao = new model_projectmanagent_order_receiptplan();
        $rows = $orderReceiptplanDao->initTableEdit($object['receiptplan']);
        $object['PayNum'] = $rows[0];
        $object['receiptplan'] = $rows[1];
        //��ѵ�ƻ�
        $orderTrainingplanDao = new model_projectmanagent_order_trainingplan();
        $rows = $orderTrainingplanDao->initTableEdit($object['trainingplan']);
        $object['TraNumber'] = $rows[0];
        $object['trainingplan'] = $rows[1];
		return $object;
     }

     //ajax ���ӱ�
     function ajaxListByOrder($row) {
     	//shebei
     	$rows = $this->orderListEqu_d($row['orderequ']);
		$row['orderequ'] = $rows[1];
		$row['EquNum'] = $rows[0];
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
        $sql = 'select id from '.$this->tbl_name ." where id = ".$id." and ( createId = '".$thisUserId."' or prinvipalId ='".$thisUserId."' or areaPrincipalId ='".$thisUserId."')";
        return $this->_db->getArray($sql);
    }

    /**
     * �жϵ�ǰ��¼���Ƿ��ͬ������,������,��������
     * ����Ȩ�޹���
     * 2011-07-29
     * createBy show
     */
    function isViewKeyMan_d($id,$type){
        $thisUserId = $_SESSION['USER_ID'];
        $sql = 'select id from orderview_all where orgId = '.$id." and tablename='$type' and ( createId = '".$thisUserId."' or prinvipalId ='".$thisUserId."' or areaPrincipalId ='".$thisUserId."')";
//        echo $sql;
        return $this->_db->getArray($sql);
    }
	/********************************************************ģ���滻�෽��*****************************************************/

	/**
	 * @description ����ǩ����ͬ---�豸��Ϣ
	 * @aurtho qian
	 * @date 2011-03-10 11:15
	 */
	function orderListEqu_d($rows){
		$i = 0;
		$str = "";
		if ($rows) {
			//��Ʒ�������ֵ�
			$datadictArr = $this->getDatadicts ( "CPX" );
			foreach ($rows as $val) {
				$tvalue1 = $tvalue2 = $tvalue3 = $tvalue4 = "";
				if($val['warrantyPeriod']=="����") {
					$tvalue1 = "selected";
				}elseif($val['warrantyPeriod']=="һ��"){
					$tvalue2 = "selected";
				}elseif($val['warrantyPeriod']=="����") {
					$tvalue3 = "selected";
				}else{
					$tvalue4 = "selected";
				}

				if(!empty($val['isSell'])){
					$checked = 'checked="checked"';
				}else{
					$checked = null;
				}
				$i++;
				$str .=<<<EOT
					<tr><td>$i</td>
					 	<td>
					 		<input type="text" name="order[equipment][$i][productNumber]" id="EquId$i" value="$val[productNo]" class="txtshort"/>
					 		<input type="hidden" name="order[equipment][$i][ptype]" value="soft"/>
					 	</td>
					 	<td>
					        <input type="text" name="order[equipment][$i][productName]" id="EquName$i" value="$val[productName]" class="txtmiddle"/>
					        <input type="hidden" name="order[equipment][$i][productId]" id="ProductId$i" value="$val[productId]" />
					 	</td>
					 	<td>
					 		<input type="text" name="order[equipment][$i][productModel]" id="EquModel$i" value="$val[productModel]" class="txtshort"/>
					 	</td>
					 	<td>
					 		<input type="text" name="order[equipment][$i][amount]" id="EquAmount$i" value="$val[number]" onblur="FloatMul('EquAmount$i','EquPrice$i','EquAllMoney$i')" class="txtshort"/>
					 	</td>
					 	<td>
					 		<input type="text" name="order[equipment][$i][price]" id="EquPrice$i" value="$val[price]" onblur="FloatMul('EquAmount$i','EquPrice$i','EquAllMoney$i')" class="formatMoney txtshort"/>
					 	</td>
					 	<td>
					 		<input type="text" name="order[equipment][$i][countMoney]" id="EquAllMoney$i" value="$val[money]" class="formatMoney txtshort"/>
					 	</td>
					 	<td>
					        <input type="text" name="order[equipment][$i][projArraDate]" id="EquDeliveryDT$i" value="$val[projArraDate]" onfocus="WdatePicker();" class="txtshort"/>
					    </td>
					 	<td>
					 		<select name="order[equipment][$i][warrantyPeriod]" id="warrantyPeriod$i" class="txtshort">
					 			<option value="����" $tvalue1>����</option>
					 			<option value="һ��" $tvalue2>һ��</option>
					 			<option value="����" $tvalue3>����</option>
					 			<option value="����" $tvalue4>����</option>
					 		</select>
					 	</td>
					 	<td>
					        <input type="checkbox" name="order[equipment][$i][isSell]" id="isSell$i" $checked/>
					    </td>
					 	<td>
							<img src="images/closeDiv.gif" onclick="mydel(this,'myequ')" title="ɾ����"/>
					 	</td>
					</tr>
EOT;
			}
			return array($i,$str);
		}

	}


	/**
	 * ����ǩ����ͬ---�Զ����嵥
	 */
	function customizelist_d($rows) {

		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				if(!empty($val['isSell'])){
					$checked = 'checked="checked"';
				}else{
					$checked = null;
				}
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="order[customizelist][$i][productnumber]" id="PequID$i" size="10" value="$val[productCode]">
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="order[customizelist][$i][name]" id="PequName$i" size="15" value="$val[productName]">
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="order[customizelist][$i][prodectmodel]" id="PreModel$i" size="10" value="$val[productModel]">
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="order[customizelist][$i][amount]" onblur="FloatMul('PreAmount$i','PrePrice$i','CountMoney$i')" id="PreAmount$i" size="8" maxlength="10" value="$val[amount]"/>
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="order[customizelist][$i][price]" onblur="FloatMul('PreAmount$i','PrePrice$i','CountMoney$i')" id="PrePrice$i" size="8" maxlength="10" class="formatMoney"  value="$val[price]"/>
					 	</td>
					 	<td>
					 		<input type="text" class="txtshort" name="order[customizelist][$i][countMoney]" id="CountMoney$i" size="8" maxlength="10" class="formatMoney"  value="$val[money]"/>
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="order[customizelist][$i][projArraDT]" id="PreDeliveryDT$i" size="10" value="$val[projArraDT]" onfocus="WdatePicker()"/>
					    </td>
					 	<td>
					 		<input class="txt" type="text" name="order[customizelist][$i][remark]" id="PRemark$i" size="18" maxlength="100" value="$val[remark]"/>
					 	</td>
				 		<td width="4%">
				        	<input type="checkbox" name="order[customizelist][$i][isSell]" $checked/>
						</td>
					 	<td>
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'mycustom')" title="ɾ����">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}


/*******************************************************************************************************************/
    /**
     * �̻�ת����--�豸��Ϣ
     */
    function chanceEquList($object){
    	$datadictArr = $this->getDatadicts ( "CPX" );
		$str = "";
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;
				$str .=<<<EOT
					<tr id="equTab_$i">
					    <td width="5%">$i
						</td>
						<td>
			                <input type="text" class="txtshort" name="order[orderequ][$i][productNo]" id="productNo$i"  value="$val[productNumber]"/>
			            </td>
			            <td>
			            	<input type="hidden" id="productId$i" name="order[orderequ][$i][productId]" value="$val[productId]"/>
			                <input type="text" name="order[orderequ][$i][productName]"
			                id="productName$i" class="txt" readonly="readonly" value="$val[productName]"/>
			            </td>
			            <td>
			                <input type="text" name="order[orderequ][$i][productModel]"
			                id="productModel$i" class="txtshort" readonly="readonly" value="$val[productModel]"/>
			            </td>
			            <td><input type="text" name="order[orderequ][$i][number]"
							id="number$i" class="txtshort "   value="$val[amount]"
							onblur="FloatMul('number$i','price$i','money$i')" /></td>
						<td><input type="text" name="order[orderequ][$i][price]"
							id="price$i" class="txtshort "
							onblur="FloatMul('number$i','price$i','money$i');countAll()" value="$val[price]"/></td>
						<td><input type="text" name="order[orderequ][$i][money]"
							id="money$i" class="txtshort " value="$val[money]"/></td>

            <td>
				<input class="txtshort" type="text" name="order[orderequ][$i][projArraDate]"
					id="projArraDate1"  onfocus="WdatePicker()" value="$val[projArraDate]"/>
				</td>
				<td nowrap width="8%">
					<select class="txtshort" name="order[orderequ][$i][warrantyPeriod]" id="warrantyPeriod1">

						<option value="����">
							����
						</option>
						<option value="һ��">
							һ��
						</option>
						<option value="����">
							����
						</option>
						<option value="����">
							����
						</option>
					</select>
				</td>
				<td>
 			         <input type="button" class="txt_btn_a" value="����" onclick="License('licenseId$i');" />
 			    </td>
				<td width="4%">
					<input type="checkbox" name="order[orderequ][$i][isSell]" id="isSell1"
					checked="checked" value="$val[isSell]"/>
				</td>
			            <td>
			                <img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="ɾ����"/>
			            </td>
					</tr>
EOT;
		}

		return array( $str,$i );
	}
/**********************************************************************************************************************************/
    /**
	 * ������ⵥʱ���������ϵ�ID��ȡ������ʾģ��
	 *
	 */
	function getEquList_d($orderId){
		$orderEquDao=new model_projectmanagent_order_orderequ();
		$rows=$orderEquDao->getItemByBasicIdId_d($orderId);
		$list=$orderEquDao->showAddList($rows);
		return $list;
	}
/*********************************************************************************************************************************/

/**
 * �쳣�ر������鿴---�鿴��ͬ
 */
	function closeOrderView($orderId) {

       $row='<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=projectmanagent_order_order&action=toViewTab&id='.$orderId.'&perm=view\')">';
		return $row;
	}
/*******************************************************************************************************************************/
/**
 * ��ͬǩ��
 */
function signin_d($object){
		try{
			$this->start_d();

            $changeLogDao = new model_common_changeLog ( 'orderSignin',false );
			//�����¼,�õ��������ʱ������id
			$changeLogDao->addLog ( $object );

			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object['orderNatureName'] = $datadictDao->getDataNameByCode ( $object['orderNature'] );

			//�޸�������Ϣ

			$object['id'] = $object['oldId'];
			parent::edit_d($object,true);

			$orderId = $object['oldId'];


			//����ӱ���Ϣ
			//�ͻ���ϵ��

            $linkmanDao = new model_projectmanagent_order_linkman();
            foreach ($object['linkman'] as $key => $val){
                if (isset ($val['oldId'])){
                     $val['id'] = $val['oldId'];
                     $linkmanDao->edit_d ( $val );
                }else {
		 	         $linkmanDao->createBatch(array( $object['linkman'][$key] ),array('orderId' => $orderId ),'linkman');
                }
                if(isset ($val['isDel'])){
                     $linkmanDao->delete(array('id' => $val['oldId']));
                }
			}
			//�豸

			$orderequDao = new model_projectmanagent_order_orderequ();
			foreach ($object['orderequ'] as $key => $val){
                if (isset ($val['oldId'])){
                     $val['id'] = $val['oldId'];
                     $orderequDao->edit_d ( $val );
                }else {
		 	         $orderequDao->createBatch(array( $object['orderequ'][$key] ),array('orderId' => $orderId ),'productName');
                }
                if(isset ($val['isDel'])){
                     $orderequDao->delete(array('id' => $val['oldId']));
                }
			}
            $orderequDao->updateUniqueCode_d($orderId);
			if($object['orderequ']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $object, 'objType' => $this->tbl_name , 'extType' => $orderequDao->tbl_name ),
					'orderId',
					'license'
				);
			}

//            //�Զ����嵥
//            $orderCustomizeDao = new model_projectmanagent_order_customizelist();
//            $orderCustomizeDao->delete(array('orderId' => $orderId));
//            $orderCustomizeDao->createBatch($object['customizelist'],array('orderId' => $orderId));
            //��Ʊ��Ϣ
            $orderInvoiceDao = new model_projectmanagent_order_invoice();
            foreach ($object['invoice'] as $key => $val){
                if (isset ($val['oldId'])){
                     $val['id'] = $val['oldId'];
                     $orderInvoiceDao->edit_d ( $val );
                }else {
		 	         $orderInvoiceDao->createBatch(array( $object['invoice'][$key] ),array('orderId' => $orderId ),'money');
                }
                if(isset ($val['isDel'])){
                     $orderInvoiceDao->delete(array('id' => $val['oldId']));
                }
			}
            //�տ�ƻ�
            $orderReceiptplanDao = new model_projectmanagent_order_receiptplan();
            foreach ($object['receiptplan'] as $key => $val){
                if (isset ($val['oldId'])){
                     $val['id'] = $val['oldId'];
                     $orderReceiptplanDao->edit_d ( $val );
                }else {
		 	         $orderReceiptplanDao->createBatch(array( $object['receiptplan'][$key] ),array('orderId' => $orderId ),'money');
                }
                if(isset ($val['isDel'])){
                     $orderInvoiceDao->delete(array('id' => $val['oldId']));
                }
			}
            //��ѵ�ƻ�
            $orderTrainingplanDao = new model_projectmanagent_order_trainingplan();
            foreach ($object['trainingplan'] as $key => $val){
                if (isset ($val['oldId'])){
                     $val['id'] = $val['oldId'];
                     $orderTrainingplanDao->edit_d ( $val );
                }else {
		 	         $orderTrainingplanDao->createBatch(array( $object['trainingplan'][$key] ),array('orderId' => $orderId ),'beginDT');
                }
                if(isset ($val['isDel'])){
                     $orderTrainingplanDao->delete(array('id' => $val['oldId']));
                }
			}
			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(exception $e){
			$this->rollBack();

			return false;
		}
	}

	/**
	 * ������ͬ��Ʊ�����б�
	 * ����1Ϊ����,����2Ϊ�����Ƿ����,Ĭ�ϲ�����
	 */
	function getInvoiceAndIncome_d($rows,$isFilter = 1,$objType=null){
		if($rows){
			$receviableDao = new model_finance_receviable_receviable();
			$otherDao = new model_common_otherdatas();

			$limitArr = array();

			if($isFilter == 1){
				foreach($rows as $key => $val){
					$moneyArr = array();

					$objType = isset($val['tablename'] ) ? $val['tablename'] : $objType;
					$objId = isset($val['orgid']) ? $val['orgid'] : $val['id'];

					switch($objType){
						case 'oa_sale_order' :
							$invoiceTab = $this->this_limit['���ۺ�ͬ��Ʊtab'];
							$incomeTab = $this->this_limit['���ۺ�ͬ����tab'];
							$moneyPriv = $this->this_limit['���ۺ�ͬ���'];
							$moneyArr = explode(',',$moneyPriv);
							break;
						case 'oa_sale_lease' :
							if(!isset($limitArr['oa_sale_lease'])){
								$limitArr['oa_sale_lease'] = $otherDao->getUserPriv('contract_rental_rentalcontract',$_SESSION['USER_ID'],$_SESSION['USER_JOBSID'],$_SESSION['DEPT_ID']);
							}
							$invoiceTab = $limitArr['oa_sale_lease']['���޺�ͬ��Ʊtab'];
							$incomeTab = $limitArr['oa_sale_lease']['���޺�ͬ����tab'];
							$moneyPriv = $limitArr['oa_sale_lease']['���޺�ͬ���'];
							$moneyArr = explode(',',$moneyPriv);
							break;
						case 'oa_sale_service' :
							if(!isset($limitArr['oa_sale_service'])){
								$limitArr['oa_sale_service'] = $otherDao->getUserPriv('engineering_serviceContract_serviceContract',$_SESSION['USER_ID'],$_SESSION['USER_JOBSID'],$_SESSION['DEPT_ID']);
							}
							$invoiceTab = $limitArr['oa_sale_service']['�����ͬ��Ʊtab'];
							$incomeTab = $limitArr['oa_sale_service']['�����ͬ����tab'];
							$moneyPriv = $limitArr['oa_sale_service']['�����ͬ���'];
							$moneyArr = explode(',',$moneyPriv);
							break;
						case 'oa_sale_rdproject' :
							if(!isset($limitArr['oa_sale_rdproject'])){
								$limitArr['oa_sale_rdproject'] = $otherDao->getUserPriv('rdproject_yxrdproject_rdproject',$_SESSION['USER_ID'],$_SESSION['USER_JOBSID'],$_SESSION['DEPT_ID']);
							}
							$invoiceTab = $limitArr['oa_sale_rdproject']['�з���ͬ��Ʊtab'];
							$incomeTab = $limitArr['oa_sale_rdproject']['�з���ͬ����tab'];
							$moneyPriv = $limitArr['oa_sale_rdproject']['�з���ͬ���'];
							$moneyArr = explode(',',$moneyPriv);
							break;
					}

//					print_r($moneyArr);

					$moneyRow = $receviableDao->getInvoiceAndIncome_d($objId,$objType);
					if(in_array($_SESSION['USER_ID'],$val)){//����Ǻ�ͬ�������Ա������Ȩ�޲鿴���
						$rows[$key]['invoiceMoney'] = $moneyRow['invoiceMoney'];
						$rows[$key]['incomeMoney'] = $moneyRow['incomeMoney'];
						$rows[$key]['softMoney'] = $moneyRow['softMoney'];
						$rows[$key]['hardMoney'] = $moneyRow['hardMoney'];
						$rows[$key]['serviceMoney'] = $moneyRow['serviceMoney'];
						$rows[$key]['repairMoney'] = $moneyRow['repairMoney'];
						$rows[$key]['applyedMoney'] = $moneyRow['applyedMoney'];
						$rows[$key]['unInvoiceMoney'] = bcsub($rows[$key]['orderMoney'],$rows[$key]['invoiceMoney'],2);
						$rows[$key]['unIncomeMoney'] = bcsub($rows[$key]['orderMoney'],$rows[$key]['incomeMoney'],2);
					}else{
						if($invoiceTab){//ֻ�п�ƱȨ��
							$rows[$key]['invoiceMoney'] = $moneyRow['invoiceMoney'];
							$rows[$key]['softMoney'] = $moneyRow['softMoney'];
							$rows[$key]['hardMoney'] = $moneyRow['hardMoney'];
							$rows[$key]['serviceMoney'] = $moneyRow['serviceMoney'];
							$rows[$key]['repairMoney'] = $moneyRow['repairMoney'];
							$rows[$key]['applyedMoney'] = $moneyRow['applyedMoney'];
							$rows[$key]['unInvoiceMoney'] = bcsub($rows[$key]['orderMoney'],$rows[$key]['invoiceMoney'],2);
						}
						if($incomeTab){//ֻ�е���Ȩ��
							$rows[$key]['incomeMoney'] = $moneyRow['incomeMoney'];
							$rows[$key]['unIncomeMoney'] = bcsub($rows[$key]['orderMoney'],$rows[$key]['incomeMoney'],2);
						}
						if(!in_array('orderMoney',$moneyArr)){
							$rows[$key]['orderMoney'] = '';
						}
						if(!in_array('orderTempMoney',$moneyArr)){
							$rows[$key]['orderTempMoney'] = '';
						}
					}
				}
			}else{
				foreach($rows as $key => $val){
					$objType = isset($objType) ? $objType : $val['tablename'];
					$objId = isset($val['orgid']) ? $val['orgid'] : $val['id'];
					$moneyRow = $receviableDao->getInvoiceAndIncome_d($objId,$objType);
					$rows[$key]['invoiceMoney'] = $moneyRow['invoiceMoney'];
					$rows[$key]['incomeMoney'] = $moneyRow['incomeMoney'];
					$rows[$key]['softMoney'] = $moneyRow['softMoney'];
					$rows[$key]['hardMoney'] = $moneyRow['hardMoney'];
					$rows[$key]['serviceMoney'] = $moneyRow['serviceMoney'];
					$rows[$key]['repairMoney'] = $moneyRow['repairMoney'];
					$rows[$key]['applyedMoney'] = $moneyRow['applyedMoney'];

					$rows[$key]['unInvoiceMoney'] = bcsub($rows[$key]['orderMoney'],$rows[$key]['invoiceMoney'],2);
					$rows[$key]['unIncomeMoney'] = bcsub($rows[$key]['orderMoney'],$rows[$key]['incomeMoney'],2);
				}
			}
		}
		return $rows;
	}

	/**
	 * Ȩ�޹��˺�ͬ���
	 */
	function filterContractMoney_d($rows,$objType=null){
		if($rows){
			$limitArr = array();
			$otherDao = new model_common_otherdatas();

			foreach($rows as $key => $val){
                $moneyArr = array();

				$objType = isset($val['tablename'] ) ? $val['tablename'] : $objType;
				$objId = isset($val['orgid']) ? $val['orgid'] : $val['id'];

				switch($objType){
					case 'oa_sale_order' :
						$moneyPriv = $this->this_limit['���ۺ�ͬ���'];
                        $moneyArr = explode(',',$moneyPriv);
						break;
					case 'oa_sale_lease' :
						if(!isset($limitArr['oa_sale_lease'])){
							$limitArr['oa_sale_lease'] = $otherDao->getUserPriv('contract_rental_rentalcontract',$_SESSION['USER_ID'],$_SESSION['USER_JOBSID'],$_SESSION['DEPT_ID']);
						}
						$moneyPriv = $limitArr['oa_sale_lease']['���޺�ͬ���'];
                        $moneyArr = explode(',',$moneyPriv);
						break;
					case 'oa_sale_service' :
						if(!isset($limitArr['oa_sale_service'])){
							$limitArr['oa_sale_service'] = $otherDao->getUserPriv('engineering_serviceContract_serviceContract',$_SESSION['USER_ID'],$_SESSION['USER_JOBSID'],$_SESSION['DEPT_ID']);
						}
						$moneyPriv = $limitArr['oa_sale_service']['�����ͬ���'];
                        $moneyArr = explode(',',$moneyPriv);
						break;
					case 'oa_sale_rdproject' :
						if(!isset($limitArr['oa_sale_rdproject'])){
							$limitArr['oa_sale_rdproject'] = $otherDao->getUserPriv('rdproject_yxrdproject_rdproject',$_SESSION['USER_ID'],$_SESSION['USER_JOBSID'],$_SESSION['DEPT_ID']);
						}
						$moneyPriv = $limitArr['oa_sale_rdproject']['�з���ͬ���'];
                        $moneyArr = explode(',',$moneyPriv);
						break;
				}

                if(!in_array($_SESSION['USER_ID'],$val)){//����Ǻ�ͬ�������Ա������Ȩ�޲鿴���
                    if(!in_array('orderMoney',$moneyArr)){
                        $rows[$key]['orderMoney'] = '';
                    }
                    if(!in_array('orderTempMoney',$moneyArr)){
                        $rows[$key]['orderTempMoney'] = '';
                    }
                }
			}
		}
		return $rows;
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
    /******************************/

	/**
	 * ��ȡ��������
	 */
	function getArea_d(){
		$regionDao = new model_system_region_region();
		return $regionDao->getUserAreaId($_SESSION['USER_ID'],0);
	}
	/*******************************/
	/**
	 * ���������ϴ�
	 */
	function uploadfile_d($row){
		try{
            //���������ƺ�Id
		   $this->updateObjWithFile($row['serviceId']);
			return true;
		}catch(exception $e){
			return false;
		}
	}

     /**
	  * ���ݺ�ͬ�Ż�ȡ ��ͬ��Ϣ
	  */
     function allOrderInfo($orderCode){
          $sql = "select * from view_oa_order where orderCode = '".$orderCode."' or orderTempCode = '".$orderCode."' ";
          $rows = $this->_db->getArray($sql);
          return $rows[0];
     }
/******************************************************************************/
    /*�����������*/
    function c_configuration($proId,$Num,$trId,$isEdit){
        $configurationDao = new model_stock_productinfo_configuration ();
        $sql = "select configId,configNum from ".$configurationDao->tbl_name." where hardWareId = $proId  and configId > 0";
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
	            $equDao = new model_projectmanagent_order_orderequ();
	            $configArr = $equDao->configTableEdit($infoArr,$Num);
	        }else{
	        	$equDao = new model_projectmanagent_order_orderequ();
	            $configArr = $equDao->configTable($infoArr,$Num);
	        }
        }

        return $configArr;
    }
   /********************************************************************/
   function configOrder_d($orderId){
   	     $orderExa = $this->find(array("id" => $orderId),null,"ExaStatus");
   	     $customizelistDao = new model_projectmanagent_order_customizelist();
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
   /************************************************************************/
   //�жϿͻ������ڿͻ������Ƿ����
   function cusName($cusName){
   	     $cusSql = "select id from customer where Name = '$cusName'";
         $cusName = $this->_db->getArray($cusSql);
         return $cusName;
   }
   function disCustomer(){
         $cusInfo = array();//������Ҫ������ �ͻ���Ϣ����
         // ���ۺ�ͬ
         $orderSql = "select * from oa_sale_order where customerId is null or customerId='' or customerId=0 ";
          $orderCus = $this->_db->getArray($orderSql);
          $cusInfo1 = array();
          foreach($orderCus as $k =>$v){
          	$cusNameIcon = $this->cusName($orderCus[$k]['customerName']);
          	   if(!empty($cusNameIcon)){
          	   	     $updateSql = "update oa_sale_order set customerId = ".$cusNameIcon[0]['id']." where id=".$orderCus[$k]['id']."";
          	   	     $this->_db->query($updateSql);
          	   }else{
          	   	    $cusInfo1[$k]['Name'] = $orderCus[$k]['customerName'];//�ͻ�����
          	   	    $cusInfo1[$k]['AreaName'] = $orderCus[$k]['areaName']; //��������
          	   	    $cusInfo1[$k]['AreaLeader'] = $orderCus[$k]['areaPrincipal'];// ��������
          	   	    $cusInfo1[$k]['TypeOne'] = $orderCus[$k]['customerType'];//�ͻ�����
          	   }
          }
          //�����ͬ
         $serviceSql = "select * from oa_sale_service where cusNameId is null or cusNameId='' or cusNameId=0 ";
          $serviceCus = $this->_db->getArray($serviceSql);
          $cusInfo2 = array();
          foreach($serviceCus as $k =>$v){
          	$cusNameIcon = $this->cusName($serviceCus[$k]['cusName']);
          	   if(!empty($cusNameIcon)){
          	   	     $updateSql = "update oa_sale_service set cusNameId = ".$cusNameIcon[0]['id']." where id=".$serviceCus[$k]['id']."";
          	   	     $this->_db->query($updateSql);
          	   }else{
          	   	    $cusInfo2[$k]['Name'] = $serviceCus[$k]['cusName'];//�ͻ�����
          	   	    $cusInfo2[$k]['AreaName'] = $serviceCus[$k]['areaName']; //��������
          	   	    $cusInfo2[$k]['AreaLeader'] = $serviceCus[$k]['areaPrincipal'];// ��������
          	   	    $cusInfo2[$k]['TypeOne'] = $serviceCus[$k]['customerType'];//�ͻ�����
          	   }
          }

          //���޺�ͬ
         $rentalSql = "select * from oa_sale_lease where tenantId is null or tenantId='' or tenantId=0";
          $rentalCus = $this->_db->getArray($rentalSql);
          $cusInfo3 = array();
          foreach($rentalCus as $k =>$v){
          	$cusNameIcon = $this->cusName($rentalCus[$k]['tenant']);
          	   if(!empty($cusNameIcon)){
          	   	     $updateSql = "update oa_sale_lease set tenantId = ".$cusNameIcon[0]['id']." where id=".$rentalCus[$k]['id']."";
          	   	     $this->_db->query($updateSql);
          	   }else{
          	   	    $cusInfo3[$k]['Name'] = $rentalCus[$k]['tenant'];//�ͻ�����
          	   	    $cusInfo3[$k]['AreaName'] = $rentalCus[$k]['areaName']; //��������
          	   	    $cusInfo3[$k]['AreaLeader'] = $rentalCus[$k]['areaPrincipal'];// ��������
          	   	    $cusInfo3[$k]['TypeOne'] = $rentalCus[$k]['customerType'];//�ͻ�����
          	   }
          }
          //�з���ͬ
         $rdprojectSql = "select * from oa_sale_rdproject where cusNameId is null or cusNameId='' or cusNameId=0 ";
          $rdprojectCus = $this->_db->getArray($rdprojectSql);
          $cusInfo4 = array();
          foreach($rdprojectCus as $k =>$v){
          	$cusNameIcon = $this->cusName($rdprojectCus[$k]['cusName']);
          	   if(!empty($cusNameIcon)){
          	   	     $updateSql = "update oa_sale_rdproject set cusNameId = ".$cusNameIcon[0]['id']." where id=".$rdprojectCus[$k]['id']."";
          	   	     $this->_db->query($updateSql);
          	   }else{
          	   	    $cusInfo4[$k]['Name'] = $rdprojectCus[$k]['cusName'];//�ͻ�����
          	   	    $cusInfo4[$k]['AreaName'] = $rdprojectCus[$k]['areaName']; //��������
          	   	    $cusInfo4[$k]['AreaLeader'] = $rdprojectCus[$k]['areaPrincipal'];// ��������
          	   	    $cusInfo4[$k]['TypeOne'] = $rdprojectCus[$k]['customerType'];//�ͻ�����
          	   }
          }

         $cusInfo = array_merge($cusInfo1,$cusInfo2,$cusInfo3,$cusInfo4);
         $cusInfo1 = array();
         foreach($cusInfo as $key => $val){
         	  $cusInfo1[$val['Name']]=$val;
         }
         $cusInfo2 = array();
         $i=0;
         foreach($cusInfo1 as $key => $val){
              $cusInfo2[$i]=$val;
              $i++;
         }

         return $cusInfo2;


   }
  /**
   * ���ݺ�ͬID����ͬ���Ͳ��� ��ͬ�����ˣ�Id��
   * @param $orderId ��ͬId  $orderType ��ͬ���ͣ�ȡ������
   */
  function findPrincipal($orderId,$orderType){
      $sql = "select prinvipalName,prinvipalId from view_oa_order where orgid = '$orderId' and tablename = '$orderType'";
      $prinvipal = $this->_db->getArray($sql);
      return $prinvipal;

  }
   /**
    * ���ݺ�ͬ���жϺ�ͬ�Ƿ����
    */
    function orderBe($orderTempCode,$orderCode){
               if((empty($orderTempCode) && !empty($orderCode)) || (!empty($orderTempCode) && !empty($orderCode))){
             $sql = "select id from view_oa_order where orderCode = '$orderCode'";
         }else if(!empty($orderTempCode) && empty($orderCode)){
             $sql = "select id from view_oa_order where orderTempCode = '$orderTempCode'";
         }
          $orderCode = $this->_db->getArray($sql);
          return $orderCode;
    }
    /**
     * ���ݺ�ͬID��������ȡ��ͬ��Ϣ
     */
     function findOrderInfo($orderId,$orderttype){
          $sql = "select * from view_oa_order where orgid = $orderId and tablename = '$orderttype'";
          $orderinfo = $this->_db->getArray($sql);
          return $orderinfo;
     }

	/**
     * ���ݺ�ͬID��������ȡ��ͬ��Ϣ
     */
	function getContractInfoByobjCode_d($objCode){
		$sql = "select * from view_oa_order where objCode = '$objCode'";
		$orderinfo = $this->_db->getArray($sql);
		return $orderinfo[0];
	}
/********************************************************************************************/
/**
 * ��֤ ��ͬ�����Ƿ���Ҫ�˻�
 */
function isGoodsReturn($rows){

      foreach($rows as $k => $v){
      	  $sql = "SELECT id FROM oa_sale_order_equ where orderId = '".$v['id']."' and isTemp = '0' and isDel = '0'  and (executedNum - number) > 0;";
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
  * �ӳٷ�������
  */
  function inform_d($inform){
		try{
			$this->start_d ();
				  //�������͸��º�ͬ�ķ�������
			      switch ($inform['tablename']){
			      	case "oa_sale_order" :     $sql = "update oa_sale_order set shipCondition = '0' where id=".$inform['orderid']." ";  $orderType = "���ۺ�ͬ"; break;
			      	case "oa_sale_service" :   $sql = "update oa_sale_service set shipCondition = '0' where id=".$inform['orderid']." ";$orderType = "�����ͬ";break;
			      	case "oa_sale_lease" :     $sql = "update oa_sale_lease set shipCondition = '0' where id=".$inform['orderid']." ";  $orderType = "���޺�ͬ";break;
			      	case "oa_sale_rdproject" : $sql = "update oa_sale_rdproject set shipCondition = '0' where id=".$inform['orderid']." ";$orderType = "�з���ͬ";break;
			      }
			      $this->_db->query($sql);
			      $emailArr = $inform['email'];
			      //�����ʼ�֪ͨ
			      $emailArr = $inform['email'];
			      if(empty($inform['orderCode'])){
			      	     $ordercode = $inform['orderTempCode'];
			      }else{ $ordercode = $inform['orderCode'];}
			      $addmsg = "�ӳٷ����ĺ�ͬ��Ҫ��������ͬ�ţ�$ordercode ,��ͬ���ͣ�$orderType ";
				if( !empty($emailArr['TO_ID'])){
					$emailDao = new model_common_mail();
					$emailInfo = $emailDao->batchEmail('1',$_SESSION['USERNAME'],$_SESSION['EMAIL'],$inform['tablename'],'���ͷ���֪ͨ','',$emailArr['TO_ID'],$addmsg);
				}
			$this->commit_d ();
			return 1;
		}catch(Exception $e){
			$this->rollBack();
	 		return 0;
		}

  }
 /**
  * ��������ID����ͬId ��ȡ���ϱ�ע��Ϣ
  */
  function proInfoRemark($productId,$orderId,$type){
  	 switch ($type){
  	 	case "order" :
  	 	    $dao = new model_projectmanagent_order_orderequ();
		      $remark = $dao->find(array("productId" => $productId , "orderId" => $orderId ),null,"remark");
		      return $remark;break;
		case "service" :
		    $dao = new model_engineering_serviceContract_serviceequ();
		      $remark = $dao->find(array("productId" => $productId , "orderId" => $orderId ),null,"remark");
		      return $remark;break;
	    case "lease" :
	        $dao = new model_contract_rental_tentalcontractequ();
		      $remark = $dao->find(array("productId" => $productId , "orderId" => $orderId ),null,"remark");
		      return $remark;break;
	    case "rdproject" :
	        $dao = new model_rdproject_yxrdproject_rdprojectequ();
		      $remark = $dao->find(array("productId" => $productId , "orderId" => $orderId ),null,"remark");
		      return $remark;break;
  	 }

  }

  /**
   * ����id���������������ۺ�ͬҵ����
   */
//  function batchCreateObjCode(){
//		$this->searchArr=array();
//		$this->asc=false;
//		$list=$this->list_d();
//		$orderCodeDao = new model_common_codeRule ();
//		$curCode="";
//		foreach($list as $key=>$val){
//			$prinvipalId=$val['prinvipalId'];
//			$deptDao=new model_deptuser_dept_dept();
//			$dept=$deptDao->getDeptByUserId($prinvipalId);
//			$createTime=$val['createTime'];
//			$val['objCode']=$orderCodeDao->getBatchCode($this->tbl_name."_objCode",$dept['Code'],$curCode,$createTime);
//			$curCode=$val['objCode'];
//			parent::edit_d($val);
//		}
//  }

	/**
	 * ��������ͳ�ƺ�ͬ����/���
	 */
	function getContractByArea($countField,$sqlPlus){
		$this->groupBy="o.areaPrincipal";
		$sql="select areaPrincipal as '1',$countField as '2' from oa_contract_contract o where  (o.ExaStatus='���' or o.ExaStatus='���������')and(o.state!=0 and o.state!=5 and o.state!=6) $sqlPlus";

		$rows = $this->listBySql($sql);
		return $rows;
	}

	/**
	 * ���ݺ�ͬ����ͳ�ƺ�ͬ����/���
	 */
	function getContractByType($countField,$sqlPlus){
		$this->groupBy="o.contractType";
		//��ǩ������ͳ��
		$sql="select  case  " .
				"when o.contractType='HTLX-XSHT' then '���ۺ�ͬ' " .
				"when o.contractType='HTLX-FWHT' then '���޺�ͬ'" .
				"when o.contractType='HTLX-ZLHT' then '�����ͬ'" .
				"when o.contractType='HTLX-YFHT' then '�з���ͬ'" .
				"else '' end  '1'" .
				",$countField as '2' from oa_contract_contract o where  (o.ExaStatus='���' or o.ExaStatus='���������')and(o.state!=0 and o.state!=5 and o.state!=6) $sqlPlus";
		$rows = $this->listBySql($sql);
		return $rows;
	}

	/**
	 * ���ݺ�ͬ״̬ͳ�ƺ�ͬ����/���
	 */
	function getContractByStatus($countField,$sqlPlus){
		$hasContractCondition="(o.winRate='100%')";//��ǩ����ͬ
		$notContractCondition="(o.winRate!='100%')";//δǩ����ͬ

		//DeliveryStatus 7 δ���� 8�ѷ��� 10���ַ���
//		$hasDelivery="s.DeliveryStatus=8";
//		$noDelivery="s.DeliveryStatus=7";
//		$partDelivery="s.DeliveryStatus=10";

		//$plusSql=" and (s.ExaStatus='���' or s.ExaStatus='���������') and(o.state!=0 and o.state!=5 and s.state!=6) $sqlPlus";
		//$joinOrderView="left join view_oa_order o on o.id=s.id";

		//��ǩ����ͬ������û�н�������ʵʩ����ɵ�
		//��ǩ��ͬ,δ���ִ��
		$sql="select $countField as num from oa_contract_contract o  where $hasContractCondition and o.state=2";
		$num1 = $this->queryCount($sql);

		//�Ѿ���������ʵʩ����ɣ���û��ǩ����ͬ�ģ�
		//δǩ��ͬ,�����ִ��
		$sql="select $countField as num from oa_contract_contract o  where $notContractCondition and o.state=4";
		$num2 = $this->queryCount($sql);

		//���ڽ�����������ʵʩ������û��ǩ����ͬ�ģ�
		$sql="select $countField as num from oa_contract_contract o  where $notContractCondition  and o.state=2";

		$num3 = $this->queryCount($sql);
		//�Ѿ�ǩ����ͬ��Ҳ�Ѿ���ɽ�������ʵʩ������û����ȫ��Ʊ�ģ�
		$sql="select $countField as num from oa_contract_contract o  left join financeview_is_03_sumorder f on o.id=f.objId and o.contractType=f.orderObjType" .
				" where $hasContractCondition  and  o.state=4 and (o.contractMoney>f.invoiceMoney or f.invoiceMoney is null)";
		$num4 = $this->queryCount($sql);

		//�Ѿ���Ʊ����û����ɽ�������ʵʩ���ĺ�ͬ��
//		$sql="select $countField as num from shipments_oa_order s $joinOrderView left join financeview_is_03_sumorder f on o.orgid=f.objId and o.tablename=f.orderObjType" .
//				" where $hasContractCondition  and s.DeliveryStatus in(7,10) $plusSql and o.orderMoney<=f.invoiceMoney";
		$countField1="count(f.objId)";
		if(strpos($countField,"orderMoney")>0){
			$countField1=$countField;
		}
//		$sql="select $countField1 as num from " .
//			"(select objId,objType,sum(if(isRed = 0,invoiceMoney,-invoiceMoney)) as invoiceMoney from financeView_invoice group by objId,objType) f ".
//			"left join  view_oa_order o on o.orgid=f.objId and o.tablename=f.objType ".
//			"where  o.orderMoney<=f.invoiceMoney and o.id in " .
//			"(select s.id from shipments_oa_order s  " .
//			"where $hasContractCondition and s.DeliveryStatus in(7,10) $plusSql )";
////echo $sql;
//
//
//		$num5 = $this->queryCount($sql);
		//�Ѿ���Ʊ3���¡�6���¡�һ�����ϣ���û������տ�ĺ�ͬ��
//		$sql="select $countField as num from view_oa_order o  left join financeview_is_03_sumorder f on o.orgid=f.objId and o.tablename=f.orderObjType" .
//				" left join (select objId,objType,max(invoiceTime) as maxInvoiceTime from oa_finance_invoice group by objId,objType) as i on i.objId=f.objId and i.objType=f.objType".
//				" where (o.ExaStatus='���' or o.ExaStatus='���������') and f.invoiceMoney>f.incomeMoney and (TO_DAYS(now())-TO_DAYS(i.maxInvoiceTime))>90";
//				//echo $sql;
//		$num6 = $this->queryCount($sql);
		$rows=array(
			array(
				"","��ǩ��ͬ,δ���ִ��",$num1
			),
			array(
				"","��ǩ��ͬ,�����ִ��,δ��ȫ��Ʊ",$num4
			),
			array(
				"","δǩ��ͬ,�����ִ��",$num2
			),
			array(
				"","δǩ��ͬ,δ���ִ��",$num3
			)
//			array(
//				"","�ѿ�Ʊδ����",$num5
//			),
//			array(
//				"","�ѿ�Ʊ3��������δ����տ�",$num6
//			)
		);
		return $rows;
	}

	/**
	 * ȷ�Ϻ�ͬ
	 */
	function confirmChange($spid){
		try{
			$this->start_d();
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $spid );
			$objId = $folowInfo ['objId'];
			if (! empty ( $objId )) {
				$contract = $this->get_d ( $objId );
				$contract1 = $this->get_d ( $contract['originalId'] );

				$changeLogDao = new model_common_changeLog ( 'order' );
				$changeLogDao->confirmChange_d ( $contract );
				 if ($contract ['ExaStatus'] == "���") { //������
				            $sql = "update oa_sale_order set isBecome = 0 where id = ".$contract['originalId']."";
							$this->query ( $sql );
							//�������޽�����ת�������ϣ�����ɾ��
							$dao = new model_projectmanagent_borrow_toorder();
			                $dao->getRelOrderequ($contract['originalId'],"order","change",$objId);
					}else{
						$orderInfo = $this->get_d ( $contract['originalId'] );
						foreach ( $contract['orderequ'] as $k => $v){
		                     $contractEqu1[$k] = $v['productId'];
		                     $contractNum1[$k] = $v['number'];
						}
						foreach ( $contract1['orderequ'] as $k => $v){
		                     $contractEqu2[$k] = $v['productId'];
		                     $contractNum2[$k] = $v['number'];
						}
					       //�������޽�����ת��������
					      	  $dao = new model_projectmanagent_borrow_toorder();
			                  $borrowChange = $dao->getRelOrderequ($contract['originalId'],"order","changeE",$objId);
						if (($contractEqu1 != $contractEqu2) || ($contractNum1 != $contractNum2)){
							  $this->updateOrderShipStatus_d($contract['originalId']);
						}else if($borrowChange == '1'){
							$sql = "update oa_sale_order set state = 2,DeliveryStatus = 10 where id = ".$contract['originalId']."";
							$this->query ( $sql );
						}
	                      //�������� ������������Զ����ɹ黹��
	                      $serOrderId = $objId;//���� �Զ����ɹ黹�� ���� �������к�
				          $toorderDao = new model_projectmanagent_borrow_toorder();
				          $toorderDao->findLoan($objId,"order","change");

					}
			}
			$this->commit_d();
		}catch(Exception $e){
			$this->rollBack();
		}
	}

	/**
	 * ��ͬ��Ϣ�б�ͳ�ƽ��
	 */
	 function getRowsallMoney_d($rows,$selectSql,$userId,$financeLimit){
	 	$otherdatasDao = new model_common_otherdatas();
	 	//��ȡ���Ȩ��
	 	$limitArrO = $this->this_limit ['���ۺ�ͬ���'];
		$limitArrSarr =$otherdatasDao->getUserPriv('engineering_serviceContract_serviceContract',$userId,$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
	 	$limitArrS = $limitArrSarr['�����ͬ���'];
	 	$limitArrLarr =$otherdatasDao->getUserPriv('contract_rental_rentalcontract',$userId,$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
	 	$limitArrL = $limitArrLarr['���޺�ͬ���'];
	 	$limitArrRarr =$otherdatasDao->getUserPriv('rdproject_yxrdproject_rdproject',$userId,$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
	 	$limitArrR = $limitArrRarr['�з���ͬ���'];
        //���ݻ�ȡ��Ȩ�� ƴ��sql����
	 	$sqlOrderMoney = $this->limitOrderMoney($limitArrO,$limitArrS,$limitArrL,$limitArrR);
	 	$sqlOrderTmepMoney = $this->limitOrderTempMoney($limitArrO,$limitArrS,$limitArrL,$limitArrR);
//echo "<pre>";
//print_R($sqlOrderTmepMoney);
	 	if($limitArrO == '' && $limitArrS == '' && $limitArrL == '' && $limitArrR == ''){
	 		//û��Ȩ��
	 		$this->searchArr ['department'] = "";
	 		$this->searchArr ['deptmentMoney'] = "sql:and (areaPrincipalId = '$userId' or createId = '$userId' or prinvipalId = '$userId') ";
		    $objArr = $this->listBySqlId($selectSql.'_sumMoney');
	 	}else if($limitArrO == 'orderMoney,orderTempMoney' && $limitArrS == 'orderMoney,orderTempMoney' && $limitArrL == 'orderMoney,orderTempMoney' && $limitArrR == 'orderMoney,orderTempMoney'){
	 		//ӵ��ȫ��Ȩ��
		    $objArr = $this->listBySqlId($selectSql.'_sumMoney');
	 	}else {
	 		//����Ȩ�޵�ʱ��
 		    $this->searchArr ['department'] = "";
//            $this->searchArr ['limitOrderMoney'] = $sqlOrderMoney;
            $this->searchArr ['deptmentMoney'] = "sql:and ( $sqlOrderMoney or (areaPrincipalId = '$userId' or createId = '$userId' or prinvipalId = '$userId')) ";
	        $objArrOrderMoney = $this->listBySqlId($selectSql.'_orderMoney');
            $this->searchArr ['limitOrderMoney'] = "";
//		    $this->searchArr ['limitOrderTempMoney'] = $sqlOrderTmepMoney;
		    $this->searchArr ['deptmentMoney'] = "sql:and ($sqlOrderTmepMoney or (areaPrincipalId = '$userId' or createId = '$userId' or prinvipalId = '$userId')) ";
		    $objArrOrderTempMoney = $this->listBySqlId($selectSql.'_orderTempMoney');
//	echo "<pre>";
//	print_R($objArrOrderMoney);
//	print_R($objArrOrderTempMoney);
		    $objArr[0]['orderMoney'] = $objArrOrderMoney[0]['orderMoney'];
		    $objArr[0]['orderTempMoney'] = $objArrOrderTempMoney[0]['orderTempMoney'];
		    $objArr[0]['invoiceMoney'] = $objArrOrderMoney[0]['invoiceMoney'] + $objArrOrderTempMoney[0]['invoiceMoney'];
		    $objArr[0]['incomeMoney'] = $objArrOrderMoney[0]['incomeMoney'] + $objArrOrderTempMoney[0]['incomeMoney'];
		    $objArr[0]['softMoney'] = $objArrOrderMoney[0]['softMoney'] + $objArrOrderTempMoney[0]['softMoney'];
		    $objArr[0]['hardMoney'] = $objArrOrderMoney[0]['hardMoney'] + $objArrOrderTempMoney[0]['hardMoney'];
		    $objArr[0]['repairMoney'] = $objArrOrderMoney[0]['repairMoney'] + $objArrOrderTempMoney[0]['repairMoney'];
		    $objArr[0]['serviceMoney'] = $objArrOrderMoney[0]['serviceMoney'] + $objArrOrderTempMoney[0]['serviceMoney'];

            $objArr[0]['serviceconfirmMoneyAll'] = $objArrOrderMoney[0]['serviceconfirmMoneyAll'] + $objArrOrderTempMoney[0]['serviceconfirmMoneyAll'];
		    $objArr[0]['financeconfirmMoneyAll'] = $objArrOrderMoney[0]['financeconfirmMoneyAll'] + $objArrOrderTempMoney[0]['financeconfirmMoneyAll'];
		    $objArr[0]['processMoney'] = $objArrOrderMoney[0]['processMoney'] + $objArrOrderTempMoney[0]['processMoney'];

            $objArr[0]['surplusInvoiceMoney'] = $objArrOrderMoney[0]['surplusInvoiceMoney'] + $objArrOrderTempMoney[0]['surplusInvoiceMoney'];
		    $objArr[0]['deductMoney'] = $objArrOrderMoney[0]['deductMoney'] + $objArrOrderTempMoney[0]['deductMoney'];
		    $objArr[0]['badMoney'] = $objArrOrderMoney[0]['badMoney'] + $objArrOrderTempMoney[0]['badMoney'];
		    $objArr[0]['serviceconfirmMoney'] = $objArrOrderMoney[0]['serviceconfirmMoney'] + $objArrOrderTempMoney[0]['serviceconfirmMoney'];
		    $objArr[0]['financeconfirmMoney'] = $objArrOrderMoney[0]['financeconfirmMoney'] + $objArrOrderTempMoney[0]['financeconfirmMoney'];
		    $objArr[0]['surOrderMoney'] = $objArrOrderMoney[0]['surOrderMoney'] + $objArrOrderTempMoney[0]['surOrderMoney'];
		    $objArr[0]['surincomeMoney'] = $objArrOrderMoney[0]['surincomeMoney'] + $objArrOrderTempMoney[0]['surincomeMoney'];
		    $objArr[0]['budgetAll'] = $objArrOrderMoney[0]['budgetAll'] + $objArrOrderTempMoney[0]['budgetAll'];
		    $objArr[0]['budgetOutsourcing'] = $objArrOrderMoney[0]['budgetOutsourcing'] + $objArrOrderTempMoney[0]['budgetOutsourcing'];
		    $objArr[0]['feeFieldCount'] = $objArrOrderMoney[0]['feeFieldCount'] + $objArrOrderTempMoney[0]['feeFieldCount'];
		    $objArr[0]['feeOutsourcing'] = $objArrOrderMoney[0]['feeOutsourcing'] + $objArrOrderTempMoney[0]['feeOutsourcing'];
		    $objArr[0]['feeAll'] = $objArrOrderMoney[0]['feeAll'] + $objArrOrderTempMoney[0]['feeAll'];
	 	}
        //��ѯ��¼�ϼ�
//		$objArr = $this->listBySqlId($selectSql.'_sumMoney');
//	echo "<pre>";
//	print_R($objArr);
		if(is_array($objArr)){
			$rsArr = $objArr[0];
			$rsArr['FinanceCon'] = $financeLimit;
			$rsArr['id']="allMoney";
			$rsArr['thisAreaName'] = '�ϼ�';
		}else{
			$rsArr = array(
				'id' => 'noId',
				'softMoney' => 0,
				'hardMoney' => 0,
				'repairMoney' => 0,
				'serviceMoney' => 0,
				'invoiceMoney' => 0
			);
		}

		$rows[] = $rsArr;
		return $rows;
	 }

/*
   * ���� ���Ȩ��
   */
  function limitOrderMoney($limitArrO,$limitArrS,$limitArrL,$limitArrR){
  	$sqlOrderMoneyA = "sql:and tablename in (";
  	$sqlOrderMoney = "";
	$sqlOrderMoneyEnd = ")";
	 	if($limitArrO == 'orderMoney' || $limitArrO == 'orderMoney,orderTempMoney'){
	 		$sqlOrderMoney .= "'oa_sale_order',";
	 	}
	 	if($limitArrS == 'orderMoney' || $limitArrS == 'orderMoney,orderTempMoney'){
	 		$sqlOrderMoney .= "'oa_sale_service',";
	 	}
	 	if($limitArrL == 'orderMoney' || $limitArrL == 'orderMoney,orderTempMoney'){
	 		$sqlOrderMoney .= "'oa_sale_lease',";
	 	}
	 	if($limitArrR == 'orderMoney' || $limitArrR == 'orderMoney,orderTempMoney'){
	 		$sqlOrderMoney .= "'oa_sale_rdproject',";
	 	}
	 	$sqlOrderMoney = rtrim($sqlOrderMoney,',');
	 	if(!empty($sqlOrderMoney)){
           return $sqlOrderMoneyA.$sqlOrderMoney.$sqlOrderMoneyEnd;
	 	}else{
           return "";
	 	}

  }
  function limitOrderTempMoney($limitArrO,$limitArrS,$limitArrL,$limitArrR){
  	$sqlOrderTempMoneyA = "sql:and tablename in (";
  	$sqlOrderTempMoney = "";
	$sqlOrderTempMoneyEnd = ")";
	 	if($limitArrO == 'orderTempMoney' || $limitArrO == 'orderMoney,orderTempMoney'){
	 		$sqlOrderTempMoney .= "'oa_sale_order',";
	 	}
	 	if($limitArrS == 'orderTempMoney' || $limitArrS == 'orderMoney,orderTempMoney'){
	 		$sqlOrderTempMoney .= "'oa_sale_service',";
	 	}
	 	if($limitArrL == 'orderTempMoney' || $limitArrL == 'orderMoney,orderTempMoney'){
	 		$sqlOrderTempMoney .= "'oa_sale_lease',";
	 	}
	 	if($limitArrR == 'orderTempMoney' || $limitArrR == 'orderMoney,orderTempMoney'){
	 		$sqlOrderTempMoney .= "'oa_sale_rdproject',";
	 	}
	 	$sqlOrderTempMoney = rtrim($sqlOrderTempMoney,',');
	 	if(!empty($sqlOrderTempMoney)){
	 		return $sqlOrderTempMoneyA.$sqlOrderTempMoney.$sqlOrderTempMoneyEnd;
	 	}else{
	 		return "";
	 	}

  }

    /**
     * ��ͬ�б���������
     */
     function getMoneyControl_d($rows,$isFilter = 1,$objType=null){
		if($rows){
			$receviableDao = new model_finance_receviable_receviable();
			$otherDao = new model_common_otherdatas();

			$limitArr = array();

			if($isFilter == 1){
				foreach($rows as $key => $val){
					$moneyArr = array();

					$objType = isset($val['tablename'] ) ? $val['tablename'] : $objType;
					$objId = isset($val['orgid']) ? $val['orgid'] : $val['id'];

					switch($objType){
						case 'oa_sale_order' :
							$invoiceTab = $this->this_limit['���ۺ�ͬ��Ʊtab'];
							$incomeTab = $this->this_limit['���ۺ�ͬ����tab'];
							$moneyPriv = $this->this_limit['���ۺ�ͬ���'];
							$moneyArr = explode(',',$moneyPriv);
							break;
						case 'oa_sale_lease' :
							if(!isset($limitArr['oa_sale_lease'])){
								$limitArr['oa_sale_lease'] = $otherDao->getUserPriv('contract_rental_rentalcontract',$_SESSION['USER_ID'],$_SESSION['USER_JOBSID'],$_SESSION['DEPT_ID']);
							}
							$invoiceTab = $limitArr['oa_sale_lease']['���޺�ͬ��Ʊtab'];
							$incomeTab = $limitArr['oa_sale_lease']['���޺�ͬ����tab'];
							$moneyPriv = $limitArr['oa_sale_lease']['���޺�ͬ���'];
							$moneyArr = explode(',',$moneyPriv);
							break;
						case 'oa_sale_service' :
							if(!isset($limitArr['oa_sale_service'])){
								$limitArr['oa_sale_service'] = $otherDao->getUserPriv('engineering_serviceContract_serviceContract',$_SESSION['USER_ID'],$_SESSION['USER_JOBSID'],$_SESSION['DEPT_ID']);
							}
							$invoiceTab = $limitArr['oa_sale_service']['�����ͬ��Ʊtab'];
							$incomeTab = $limitArr['oa_sale_service']['�����ͬ����tab'];
							$moneyPriv = $limitArr['oa_sale_service']['�����ͬ���'];
							$moneyArr = explode(',',$moneyPriv);
							break;
						case 'oa_sale_rdproject' :
							if(!isset($limitArr['oa_sale_rdproject'])){
								$limitArr['oa_sale_rdproject'] = $otherDao->getUserPriv('rdproject_yxrdproject_rdproject',$_SESSION['USER_ID'],$_SESSION['USER_JOBSID'],$_SESSION['DEPT_ID']);
							}
							$invoiceTab = $limitArr['oa_sale_rdproject']['�з���ͬ��Ʊtab'];
							$incomeTab = $limitArr['oa_sale_rdproject']['�з���ͬ����tab'];
							$moneyPriv = $limitArr['oa_sale_rdproject']['�з���ͬ���'];
							$moneyArr = explode(',',$moneyPriv);
							break;
					}

//					print_r($moneyArr);

					$moneyRow = $receviableDao->getInvoiceAndIncome_d($objId,$objType);
					if(in_array($_SESSION['USER_ID'],$val)){//����Ǻ�ͬ�������Ա������Ȩ�޲鿴���
						$rows[$key]['invoiceMoney'] = $rows[$key]['invoiceMoney'];
						$rows[$key]['incomeMoney'] = $rows[$key]['incomeMoney'];
						$rows[$key]['softMoney'] = $rows[$key]['softMoney'];
						$rows[$key]['hardMoney'] = $rows[$key]['hardMoney'];
						$rows[$key]['serviceMoney'] = $rows[$key]['serviceMoney'];
						$rows[$key]['repairMoney'] = $rows[$key]['repairMoney'];
						$rows[$key]['applyedMoney'] = $rows[$key]['applyedMoney'];
						$rows[$key]['unInvoiceMoney'] = bcsub($rows[$key]['orderMoney'],$rows[$key]['invoiceMoney'],2);
						$rows[$key]['unIncomeMoney'] = bcsub($rows[$key]['orderMoney'],$rows[$key]['incomeMoney'],2);
					}else{
						if(empty($invoiceTab)){//û�п�ƱȨ��
							$rows[$key]['invoiceMoney'] = "";
							$rows[$key]['softMoney'] = "";
							$rows[$key]['hardMoney'] = "";
							$rows[$key]['serviceMoney'] = "";
							$rows[$key]['repairMoney'] = "";
							$rows[$key]['applyedMoney'] = "";
							$rows[$key]['unInvoiceMoney'] = "";
						}
						if(empty($incomeTab)){//û�е���Ȩ��
							$rows[$key]['incomeMoney'] = "";
							$rows[$key]['unIncomeMoney'] = "";
						}
						if(!in_array('orderMoney',$moneyArr)){
							$rows[$key]['orderMoney'] = '';
						}
						if(!in_array('orderTempMoney',$moneyArr)){
							$rows[$key]['orderTempMoney'] = '';
						}
					}
				}
			}
		}
		return $rows;
	}

	/**
	 * ���� ��ͬID����ͬ���ͣ������� ��ȡ�ͻ���Ϣ
	 * ���ۺ�ͬoa_sale_order
	 * �����ͬoa_sale_service
	 * ���޺�ͬoa_sale_lease
	 * �з���ͬoa_sale_rdproject
	 */

	 function getCusinfoByorder($tablename,$id){
          $sql = "select customerId,customerName,prinvipalName,prinvipalId from view_oa_order where tablename= '".$tablename."' and orgid=$id";
          $cusarr = $this->_db->getArray($sql);
          return $cusarr[0];
	 }
	 /**
	  * ��ȡ��ͬ��������
	  */
	  function getAllOrder_d(){
	  	    $conarr = array();
			//��ȡ������Ϣ
            $conarr['info'] = $this->disposeOrderArr($this->exeSql($this->tbl_name));
			//��ȡ�ӱ���Ϣ
			$orderlinkmanDao = new model_projectmanagent_order_linkman();//�ͻ���ϵ��
			$conarr['linkman'] = $this->disposeLinkArr($this->exeSql($orderlinkmanDao->tbl_name));
			$orderequDao = new model_projectmanagent_order_orderequ();//�豸�嵥
	  		$conarr['orderequ'] = $this->disposeEquArr($this->exeSql($orderequDao->tbl_name));
//	        $orderCustomizeDao = new model_projectmanagent_order_customizelist();//�Զ����嵥
//	        $rows['customizelist'] = $this->exeSql($orderCustomizeDao->tbl_name);
            $orderInvoiceDao = new model_projectmanagent_order_invoice();//��Ʊ�ƻ�
            $conarr['invoice'] = $this->disposeInvoiceArr($this->exeSql($orderInvoiceDao->tbl_name));
	        $orderReceiptplanDao = new model_projectmanagent_order_receiptplan();//�տ�ƻ�
	        $conarr['receiptplan'] = $this->disposeIncomeArr($this->exeSql($orderReceiptplanDao->tbl_name));
	        $orderTrainingplanDao = new model_projectmanagent_order_trainingplan();//��ѵ�ƻ�
	        $conarr['trainingplan'] = $this->disposeTrainArr($this->exeSql($orderTrainingplanDao->tbl_name));
		return $conarr;
	}
	//��ѯsql
	function exeSql($tableName){
         $sql = " select * from ".$tableName." ";
         return $this->_db->getArray($sql);
	}
	//ѭ�������ͬ��Ϣ
	function disposeOrderArr($arr){
		$temparr = array();
           foreach($arr as $k => $v){
           	 $temparr[$k]['oldId'] = $v['id'];
           	 $temparr[$k]['oldTableName'] = "oa_sale_order";
           	 $temparr[$k]['objCode'] = $v['objCode'];
             $temparr[$k]['sign'] = $v['sign'];
             $temparr[$k]['signSubjectName'] = "���Ͷ���";
             $temparr[$k]['signSubject'] = "DL";
             $temparr[$k]['oldContractType'] = "HTLX-XSHT";
             if($v['sign']=="��"){
             	$temparr[$k]['winRate'] = "100%";
             }else{
             	$temparr[$k]['winRate'] = "80%";
             }
             if($v['signinType']=="service"){
             	$temparr[$k]['contractType'] = "HTLX-FWHT";
             	$temparr[$k]['contractTypeName'] = "�����ͬ";
             }else{
             	$temparr[$k]['contractType'] = "HTLX-XSHT";
             	$temparr[$k]['contractTypeName'] = "���ۺ�ͬ";
             }
             $temparr[$k]['contractNature'] = $v['orderNature'];
             $temparr[$k]['contractNatureName'] = $v['orderNatureName'];
             if(empty($v['orderCode'])){
             	$temparr[$k]['contractCode'] = $v['orderTempCode'];
             }else{
             	$temparr[$k]['contractCode'] = $v['orderCode'];
             }
             $temparr[$k]['contractName'] = $v['orderName'];
             $temparr[$k]['customerName'] = $v['customerName'];
             $temparr[$k]['customerId'] = $v['customerId'];
             $temparr[$k]['customerType'] = $v['customerType'];
             $temparr[$k]['address'] = $v['address'];
             $temparr[$k]['contractCountry'] = "�й�";
             $temparr[$k]['contractCountryId'] = "1";
             $temparr[$k]['contractProvince'] = $v['orderProvince'];
             $temparr[$k]['contractProvinceId'] = $v['orderProvinceId'];
             $temparr[$k]['contractCity'] = $v['orderCity'];
             $temparr[$k]['contractCityId'] = $v['orderCityId'];
             $temparr[$k]['prinvipalName'] = $v['prinvipalName'];
             $temparr[$k]['prinvipalId'] = $v['prinvipalId'];
             $temparr[$k]['contractSigner'] = $v['createName'];
             $temparr[$k]['contractSignerId'] = $v['createId'];
             if(empty($v['orderMoney']) || $v['orderMoney'] == '0'){
             	$temparr[$k]['contractMoney'] = $v['orderTempMoney'];
             }else{
             	$temparr[$k]['contractMoney'] = $v['orderMoney'];
             }
             $temparr[$k]['invoiceType'] = $v['invoiceType'];
             $temparr[$k]['deliveryDate'] = $v['deliveryDate'];
             $temparr[$k]['contractInputName'] = $v['createName'];
             $temparr[$k]['contractInputId'] = $v['createId'];
             $temparr[$k]['enteringDate'] = $v['createTime'];
             $temparr[$k]['updateTime'] = $v['updateTime'];
             $temparr[$k]['updateName'] = $v['updateName'];
             $temparr[$k]['updateId'] = $v['updateId'];
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
             $temparr[$k]['warrantyClause'] = $v['warrantyClause'];
             $temparr[$k]['afterService'] = $v['afterService'];
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
             if(empty($v['orderMoneyCur']) || $v['orderMoneyCur'] == '0'){
             	$temparr[$k]['contractMoneyCur'] = $v['orderTempMoneyCur'];
             }else{
             	$temparr[$k]['contractMoneyCur'] = $v['orderMoneyCur'];
             }
             $temparr[$k]['isTemp'] = $v['isTemp'];
             $temparr[$k]['originalId'] = $v['originalId'];
             $temparr[$k]['changeTips'] = $v['changeTips'];
             $temparr[$k]['isBecome'] = $v['isBecome'];
             $temparr[$k]['shipCondition'] = $v['shipCondition'];
             $temparr[$k]['contractState'] = $v['orderstate'];
             $temparr[$k]['state'] = $v['state'];
             switch($v['DeliveryStatus']){
                case '7' : $temparr[$k]['DeliveryStatus'] = "WFH"; break;
                case '8' : $temparr[$k]['DeliveryStatus'] = "YFH"; break;
                case '9' : $temparr[$k]['DeliveryStatus'] = "YCGB"; break;
                case '10' :$temparr[$k]['DeliveryStatus'] = "BFFH"; break;
                case '11' :$temparr[$k]['DeliveryStatus'] = "TZFH"; break;
             }
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
	   }
	   return $temparr;
	}
	function disposeEquArr($arr){
		$temparr=array();
		foreach($arr as $k => $v){
			    $temparr[$k]['tablename'] = "oa_sale_order_equ";
			    $temparr[$k]['oldId'] = $v['id'];
			    $temparr[$k]['oldorderId'] = $v['orderId'];
                $temparr[$k]['productName'] = $v['productName'];
                $temparr[$k]['productId'] = $v['productId'];
                $temparr[$k]['productCode'] = $v['productNo'];
                $temparr[$k]['productModel'] = $v['productModel'];
                $temparr[$k]['productType'] = $v['productType'];
                $temparr[$k]['projArraDate'] = $v['projArraDate'];
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
			return $temparr;
	}
	function disposeLinkArr($arr){
		$temparr=array();
		foreach($arr as $k=>$v){
			     $temparr[$k]['tablename'] = "oa_sale_order_linkman";
			 	 $temparr[$k]['oldId'] = $v['id'];
			 	 $temparr[$k]['oldorderId'] = $v['orderId'];
			 	 $temparr[$k]['linkmanId'] = $v['linkmanId'];
			 	 $temparr[$k]['linkmanName'] = $v['linkman'];
			 	 $temparr[$k]['section'] = $v['section'];
			 	 $temparr[$k]['post'] = $v['post'];
			 	 $temparr[$k]['telephone'] = $v['telephone'];
			 	 $temparr[$k]['Email'] = $v['Email'];
			 	 $temparr[$k]['remark'] = $v['remark'];
			 	 $temparr[$k]['isTemp'] = $v['isTemp'];
			 	 $temparr[$k]['originalId'] = $v['originalId'];
			 }
		return $temparr;
	}
	function disposeInvoiceArr($arr){
		if(empty($arr)){
			return "";
		}else{
			$temparr=array();
			foreach ($arr as $k=>$v){
				    $temparr[$k]['tablename'] = "oa_sale_order_invoice";
		        	$temparr[$k]['oldId'] = $v['id'];
				    $temparr[$k]['oldorderId'] = $v['orderId'];
		        	$temparr[$k]['money'] = $v['money'];
		        	$temparr[$k]['iTypeName'] = $v['iTypeName'];
		        	$temparr[$k]['iType'] = $v['iType'];
		        	$temparr[$k]['invDT'] = $v['invDT'];
		        	$temparr[$k]['remark'] = $v['remark'];
		        	$temparr[$k]['isOver'] = $v['isOver'];
		        	$temparr[$k]['overDT'] = $v['overDT'];
		        }
		     return $temparr;
		}
	}
	function disposeIncomeArr($arr){
		if(empty($arr)){
			return "";
		}else{
			$temparr=array();
			foreach ($arr as $k=>$v){
				    $temparr[$k]['tablename'] = "oa_sale_order_receiptplan";
		        	$temparr[$k]['oldId'] = $v['id'];
				    $temparr[$k]['oldorderId'] = $v['orderID'];
		        	$temparr[$k]['money'] = $v['money'];
		        	$temparr[$k]['payDT'] = $v['payDT'];
		        	$temparr[$k]['pTypeName'] = $v['pTypeName'];
		        	$temparr[$k]['pType'] = $v['pType'];
		        	$temparr[$k]['collectionTerms'] = $v['collectionTerms'];
		        	$temparr[$k]['isOver'] = $v['isOver'];
		        	$temparr[$k]['overDT'] = $v['overDT'];
		        }
		     return $temparr;
		}
	}
    function disposeTrainArr($arr){
		if(empty($arr)){
			return "";
		}else{
			$temparr=array();
			foreach ($arr as $k=>$v){
				    $temparr[$k]['tablename'] = "oa_sale_order_trainingplan";
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

/*****************************************************************************************************************/
	/**
	 * �ϴ�EXCEl������������
	 */
	function addFinalceMoneyExecel_d($objNameArr,$importInfo,$normType) {
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract ();
			$excelData = $upexcel->upExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' ); //�ı������ķ�ʽ
			if ($excelData) {
				$objectArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					foreach ( $objNameArr as $index => $fieldName ) {
						$objectArr [$rNum] [$fieldName] = $row [$index];
					}
				}
             $arrinfo = array ();//������
                foreach ($objectArr as $k => $v){
//                	 //�жϺ�ͬ�Ƿ����
                	 $isBe = $this->findContract($v['contractCode'],$normType);
                	 if(empty($isBe)){
                        array_push ( $arrinfo, array ("docCode" => $v['contractCode'],"result" => "����ʧ��,��ͬ��Ϣ������" ) );
                	 }else{
                	 	if(count($isBe) > 1){
                	 		array_push ( $arrinfo, array ("docCode" => $v['contractCode'],"result" => "����ʧ��,��ͬ���ظ���ʹ��ҵ���ŵ���" ) );
                	 	}else{
                	 	  //���º�ͬ��Ϣ
                           $this->updateConInfo($isBe[0]['orgid'],$isBe[0]['tablename'],$importInfo,$v);
                           $this->updateGross($isBe[0]['orgid'],$isBe[0]['tablename']);//���� ë����ë����,����ȷ�������룬����ȷ���ܳɱ������ࣩ
                            array_push ( $arrinfo, array ("docCode" => $v['contractCode'],"result" => "����ɹ���" ) );
                	 	}
                	 }
                }
				if ($arrinfo){
					echo util_excelUtil::finalceResult ( $arrinfo, "������", array ("��ͬ���", "���" ) );
				}
			} else {
				echo "�ļ������ڿ�ʶ������!";
			}
		} else {
			echo "�ϴ��ļ����Ͳ���EXCEL!";
		}

	}
    /**
     * ������루���µ������룩
     */
    function addFinalceMoneyExecelAlone_d($objNameArr,$infoArr,$normType) {
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract ();
			$excelData = $upexcel->upExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' ); //�ı������ķ�ʽ
			if ($excelData) {
				$objectArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					foreach ( $objNameArr as $index => $fieldName ) {
						$objectArr [$rNum] [$fieldName] = $row [$index];
					}
				}
//   echo "<pre>";
//   print_R($infoArr);
//   print_R($objectArr);
             $arrinfo = array ();//������
                foreach ($objectArr as $k => $v){
                	 //�ϲ���Ϣ
                	 $object = array_merge($v,$infoArr);
                	 //�жϺ�ͬ�Ƿ����
                	 $isBe = $this->findContract($v['contractCode'],$normType);
                	 if(empty($isBe)){
                        array_push ( $arrinfo, array ("docCode" => $v['contractCode'],"result" => "����ʧ��,��ͬ��Ϣ������" ) );
                	 }else{
                	 	if(count($isBe) > 1){
                	 		array_push ( $arrinfo, array ("docCode" => $v['contractCode'],"result" => "����ʧ��,��ͬ���ظ���ʹ��ҵ���ŵ���" ) );
                	 	}else{
                	 		//��ӵ�����Ϣ
	                        $this->addfinancialInfo($object,$isBe,$normType);
	                        $this->updateGross($isBe[0]['orgid'],$isBe[0]['tablename']);//���� ë����ë����,����ȷ�������룬����ȷ���ܳɱ������ࣩ
				            array_push ( $arrinfo, array ("docCode" => $v['contractCode'],"result" => "����ɹ���" ) );
                	 	}
                	 }
                }
				if ($arrinfo){
					echo util_excelUtil::finalceResult ( $arrinfo, "������", array ("��ͬ���", "���" ) );
				}
			} else {
				echo "�ļ������ڿ�ʶ������!";
			}
		} else {
			echo "�ϴ��ļ����Ͳ���EXCEL!";
		}

	}
   /*
    * �жϺ�ͬ�Ƿ����
    */
    function findContract($contractCode,$normType){
    	  if($normType=="��ͬ��"){
    	  	 $sql = "select orgid,tablename from view_oa_order where orderCode = '".$contractCode."' or orderTempCode = '".$contractCode."'";
    	  }else{
             $sql = "select orgid,tablename from view_oa_order where  objCode = '".$contractCode."'";
    	  }
    	    $cId = $this->_db->getArray($sql);
          return $cId;
    }
    /*
     * ���º�ͬ��Ϣ
     */
     function updateConInfo($cId,$talbeName,$importInfo,$row){
     	   if($importInfo == "deductMoney"){
              //�����˿���ۼӣ�
	           $updatedeductMoneySql = "update ".$talbeName." set deductMoney = deductMoney+".$row['money']." where id=".$cId."";
	           $this->query($updatedeductMoneySql);
     	   }else{
     	   	  //���½����Ϣ
	           $updateMoneySql = "update ".$talbeName." set $importInfo=".$row['money']." where id=".$cId."";
	           $this->query($updateMoneySql);
     	   }

     }
     /*
      * ������루���µ������룩 ��ӷ���
      */
    function addfinancialInfo($row,$conInfo){
    	  //�ж������Ƿ�Ϊ�ظ����������
    	  $findSql="select id from oa_contract_finalceMoney where contractType='".$conInfo[0]['tablename']."' and contractId='".$conInfo[0]['orgid']."' and importMonth='".$row['importMonth']."' and moneyType='".$row['moneyType']."'";
    	  $findId = $this->_db->getArray($findSql);
    	  if(!empty($findId)){
    	  	  //����ʷ�������ݵ� ʹ�ñ�־λ��Ϊ 1
    	  	  foreach($findId as $k=>$v){
    	  	  	 $updateSql="update oa_contract_finalceMoney set isUse=1 where id=".$v['id']."";
    	  	     $this->query($updateSql);
    	  	  }
    	  }
            $files  = "contractType,contractId,contractCode,importMonth,moneyType,moneyNum,importName,importNameId,importDate";
            $values = "'".$conInfo[0]['tablename']."','".$conInfo[0]['orgid']."','".$row['contractCode']."','".$row['importMonth']."','".$row['moneyType']."','".$row['money']."','".$row['importName']."','".$row['importNameId']."','".$row['importDate']."'";
          $addSql="insert into oa_contract_finalceMoney (".$files.") values (".$values.")";
          $this->query($addSql);
    }

    /**
     * ���ݺ�ͬID���������ֶΣ�ë����ë���ʣ�����ȷ�������룬����ȷ���ܳɱ���ȷ��������ִ�в��죩
     */
    function updateGross($cId,$talbeName){
    	//�������ȷ���ܳɱ�������ȷ��������
    	$sql="select   (c.serviceconfirmMoney +if(f.serviceconfirmMoney is null or f.serviceconfirmMoney='',0,f.serviceconfirmMoney) ) as serviceconfirmMoney,
	                   (c.financeconfirmMoney +if(f.financeconfirmMoney is null or f.financeconfirmMoney='',0,f.financeconfirmMoney) ) as financeconfirmMoney,
	                   (c.deductMoney +if(f.deductMoney is null or f.deductMoney='',0,f.deductMoney) ) as deductMoney,processMoney,signinType
    			 from ".$talbeName." c
    			  left join (
					 SELECT
					   contractId,
					   contractType,
						sum(if(moneyType = 'serviceconfirmMoney',moneyNum,0)) as  serviceconfirmMoney,
						sum(if(moneyType = 'financeconfirmMoney',moneyNum,0)) as  financeconfirmMoney,
					  sum(if(moneyType = 'deductMoney',moneyNum,0)) as  deductMoney
					FROM
					   oa_contract_finalceMoney
					WHERE
						isUse = 0 and contractType='".$talbeName."'
					group by contractId,contractType
					)f  on c.id = f.contractId where id=".$cId."";
          $infoArr = $this->_db->getArray($sql);
        $serviceconfirmMoney = $infoArr[0]['serviceconfirmMoney']; //ȷ������
        $financeconfirmMoney = $infoArr[0]['financeconfirmMoney']; //ȷ�ϳɱ�
        $deductMoney = $infoArr[0]['deductMoney']; //�ۿ���
        $processMoney = $infoArr[0]['processMoney'];//��Ŀ���Ⱥ�ͬ��
        $signinType = $infoArr[0]['signinType'];
       //����ë����ë����
       $gross = round($serviceconfirmMoney - $financeconfirmMoney,2);
       $rateOfGrossTemp = bcdiv($gross,$serviceconfirmMoney,4);
       $rateOfGross = bcmul($rateOfGrossTemp,'100',2);
       if($signinType == 'service'){
       	   //ȷ��������ִ�в���
	       $AffirmincomeDifference = ($processMoney - $serviceconfirmMoney)/$processMoney;//ȷ��������ִ�в���
	       if(empty($AffirmincomeDifference)){
				$AffirmincomeDifference = "0.00";
			}
	      //��������ֵ
	      $updateSql="update ".$talbeName." set gross=".$gross.",rateOfGross=".$rateOfGross."," .
	      		"serviceconfirmMoneyAll=".$serviceconfirmMoney.",financeconfirmMoneyAll=".$financeconfirmMoney.",AffirmincomeDifference=".$AffirmincomeDifference." " .
	      				"where id=".$cId."";
       }else{
       	 //��������ֵ
	      $updateSql="update ".$talbeName." set gross=".$gross.",rateOfGross=".$rateOfGross."," .
	      		"serviceconfirmMoneyAll=".$serviceconfirmMoney.",financeconfirmMoneyAll=".$financeconfirmMoney." " .
	      				"where id=".$cId."";
       }

      $this->query($updateSql);
    }
    /**
     * ���º�ͬ�����ֶΣ����������ȣ�������������ִ�к�ͬ���Ʊ��ִ�в��죬ȷ��������ִ�в��죩
     * ����˵��
     * $type = objcode $param Ϊҵ���š�
     * ���� Ĭ�� $param Ϊ�����ʽ����
     * array("id"=>"xx","tablename"=>"xx") idΪ��ͬid tablenameΪ��ͬ���ͣ�������
     */
     function updateProjectProcess($param,$type=""){
     	 //����ҵ���Ż�ȡ��ͬ��Ϣ
         if($type == "objCode"){
         	$sql="select orgid,tablename,signinType,state,orderMoney,orderTempMoney,deductMoney,objCode,serviceconfirmMoneyAll,DeliveryStatus from view_oa_order where objCode='".$param."'";
         }else{
         	$sql="select orgid,tablename,signinType,state,orderMoney,orderTempMoney,deductMoney,objCode,serviceconfirmMoneyAll,DeliveryStatus from view_oa_order where orgid='".$param['id']."' and tablename='".$param['tablename']."'";
         }
         $orderInfoArr=$this->_db->getArray($sql);
    	 $orderInfo = $orderInfoArr[0];

                switch ($orderInfo['tablename']){
                	case "oa_sale_order" : $sql="select count(id) as equNum from oa_sale_order_equ where orderId=".$orderInfo['orgid']."";break;
                    case "oa_sale_service" : $sql="select count(id) as equNum from oa_service_equ where orderId=".$orderInfo['orgid']."";break;
                    case "oa_sale_lease" : $sql="select count(id) as equNum from oa_lease_equ where orderId=".$orderInfo['orgid']."";break;
                    case "oa_sale_rdproject" : $sql="select count(id) as equNum from oa_rdproject_equ where orderId=".$orderInfo['orgid']."";break;
                }
                $contractTypeArr = array(
					'oa_sale_order' => 'GCXMYD-03',//���ۺ�ͬ
					'oa_sale_service' => 'GCXMYD-02',//�����ͬ
					'oa_sale_lease' => 'GCXMYD-05',//���޺�ͬ
					'oa_sale_rdproject' => 'GCXMYD-06'//�з���ͬ
				);
				$tablename=$orderInfo['tablename'];
                $equNum = $this->_db->getArray($sql);//�ж�����
                $proProcessSql = "select round(sum(if(c.status = 'GCXMZT03',100,c.projectProcess)*c.workRate/100),2) as projectProcess from oa_esm_project c where contractId=".$orderInfo['orgid']." and contractType='".$contractTypeArr[$tablename]."'";
			    $proProcess = $this->_db->getArray($proProcessSql);
			    if(empty($proProcess[0]['projectProcess'])){
			    	if($orderInfo['state'] == '4'){
	    				$process = "100.00";
	    			}else{
	    				$process = "0.00";
	    			}
			    }else{
			    	$process =  $proProcess[0]['projectProcess'];
			    }
                if($equNum[0]['equNum'] == '0'){
                    $processE = $process;
                }else{
                	if($orderInfo['DeliveryStatus'] == '8' || $orderInfo['DeliveryStatus'] == '11'){
	    				$processE = $process;
	    			}else{
	    				$processE = "0.00";
	    			}
                }
			    if(empty($orderInfo['orderMoney']) || $orderInfo['orderMoney'] == '0'){
                     $conMoney = $orderInfo['orderTempMoney'];
			    }else{
			    	 $conMoney = $orderInfo['orderMoney'];
			    }
			    if(empty($orderInfo['deductMoney'])){
                     $deductMoney = "0";
			    }else{
			    	 $deductMoney = $orderInfo['deductMoney'];
			    }
			$processMoney = ($processE/100) * ($conMoney - $deductMoney);//�����������Ⱥ�ͬ��
		  if($orderInfo['signinType'] == 'service'){
		  	//��ȡ��Ʊ���
			$inviceMoenySql="select i.objId,i.objType,sum(if(i.isRed = 0,invoiceMoney,-i.invoiceMoney)) as invoiceMoney from
							financeview_invoice i where i.objId='".$param['id']."' and i.objType='".$param['tablename']."'  group by i.objId,i.objType";
			$inviceMoenyArr = $this->_db->getArray($inviceMoenySql);
			$invoiceDifference = ($processMoney - $inviceMoenyArr[0]['incomeMoney'])/$processMoney; //��Ʊ��ִ�в���
			if(empty($invoiceDifference)){
				$invoiceDifference = "0.00";
			}
			$AffirmincomeDifference = ($processMoney - $orderInfo['serviceconfirmMoneyAll'])/$processMoney;//ȷ��������ִ�в���
			if(empty($AffirmincomeDifference)){
				$AffirmincomeDifference = "0.00";
			}
		    //��������ֵ
		      $updateSql="update ".$orderInfo['tablename']." set projectProcess=".$processE.",processMoney=".$processMoney.",invoiceDifference=".$invoiceDifference.",AffirmincomeDifference=".$AffirmincomeDifference." where id=".$orderInfo['orgid']."";
		  }else{
		  	//��������ֵ
		      $updateSql="update ".$orderInfo['tablename']." set projectProcess=".$processE.",processMoney=".$processMoney." where id=".$orderInfo['orgid']."";
		  }

		      $this->query($updateSql);

    }
     /**
      * ���Ұ��µ���������Ƿ��ظ�����
      */
    function getFimancialImport_d($importMonth,$importSub){
	    $month = date("Y").$importMonth;
        $findSql="select count(id) as num from oa_contract_finalceMoney where importMonth='".$month."' and moneyType='".$importSub."'";
    	$findId = $this->_db->getArray($findSql);
    	  if($findId[0]['num'] == '0'){
    	  	 return 0;
    	  }else{
    	  	 return 1;
    	  }
    }

    /**
     * ��ȡ����������ϸ��Ϣ
     */
    function getFinancialDetailInfo($conId,$tablename,$moneyType){
        $sql = "SELECT
				LEFT(c.importMonth,4) as year,
				sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'01') ,c.moneyNum,0)) as January,
				sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'02') ,c.moneyNum,0)) as February,
				sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'03') ,c.moneyNum,0)) as March,
				sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'04') ,c.moneyNum,0)) as April,
				sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'05') ,c.moneyNum,0)) as May,
				sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'06') ,c.moneyNum,0)) as June,
				sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'07') ,c.moneyNum,0)) as July,
				sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'08') ,c.moneyNum,0)) as August,
				sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'09') ,c.moneyNum,0)) as September,
				sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'10') ,c.moneyNum,0)) as October,
				sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'11') ,c.moneyNum,0)) as November,
				sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'12') ,c.moneyNum,0)) as December
			FROM
				oa_contract_finalcemoney c

			  where c.isUse=0 and c.contractId=".$conId." and c.contractType='".$tablename."' and moneyType='".$moneyType."'

			GROUP BY LEFT(c.importMonth,4)";
//	echo $sql;
		$rows = $this->_db->getArray($sql);
          //��ʼ�����
          $initSql="select ".$moneyType." from ".$tablename." where id=".$conId."";
          $initMoney = $this->_db->getArray($initSql);
          $initarr = array(
              "year" => "��ʼ�����",
              "January" => $initMoney[0][$moneyType]
          );
          $rows[]=$initarr;
		return $rows;
    }
    function getFinancialImportDetailInfo($conId,$tablename,$moneyType){
        $sql = "select concat(LEFT(c.importMonth,4),'��',RIGHT(c.importMonth,2),'��') as improtMonth,
				       (case c.moneyType when 'serviceconfirmMoney' then '������'
				                         when 'financeconfirmMoney' then '����ȷ���ܳɱ�'
							             when 'deductMoney' then '�ۿ���'  end)  as moneyType,
				       c.moneyNum,c.moneyNum,c.importDate,c.importName,c.isUse
				  from oa_contract_finalcemoney c  where c.contractId=".$conId." and c.contractType='".$tablename."' and moneyType='".$moneyType."'";
		$rows = $this->_db->getArray($sql);
		return $rows;
    }

    /**
     * ��ͬ��Ϣ�б���������
     */
    function projectProcess_d($rows){
    	foreach($rows as $key => $val){
    		 switch ($val['tablename']){
                	case "oa_sale_order" : $sql="select count(id) as equNum from oa_sale_order_equ where orderId=".$val['orgid']."";break;
                    case "oa_sale_service" : $sql="select count(id) as equNum from oa_service_equ where orderId=".$val['orgid']."";break;
                    case "oa_sale_lease" : $sql="select count(id) as equNum from oa_lease_equ where orderId=".$val['orgid']."";break;
                    case "oa_sale_rdproject" : $sql="select count(id) as equNum from oa_rdproject_equ where orderId=".$val['orgid']."";break;
                }
                $contractTypeArr = array(
					'oa_sale_order' => 'GCXMYD-03',//���ۺ�ͬ
					'oa_sale_service' => 'GCXMYD-02',//�����ͬ
					'oa_sale_lease' => 'GCXMYD-05',//���޺�ͬ
					'oa_sale_rdproject' => 'GCXMYD-06'//�з���ͬ
				);
				$tablename=$val['tablename'];
                $equNum = $this->_db->getArray($sql);//�ж�����
                $proProcessSql = "select round(sum(if(c.status = 'GCXMZT03',100,c.projectProcess)*c.workRate/100),2) as projectProcess from oa_esm_project c where contractId=".$val['orgid']." and contractType='".$contractTypeArr[$tablename]."'";
			    $proProcess = $this->_db->getArray($proProcessSql);
			    if(empty($proProcess[0]['projectProcess'])){
			    	if($val['state'] == '4'){
	    				$process = "100.00";
	    			}else{
	    				$process = "0.00";
	    			}
			    }else{
			    	$process =  $proProcess[0]['projectProcess'];
			    }

                if($equNum[0]['equNum'] == '0'){
                    $processE = $process;
                }else{
                	if($val['DeliveryStatus'] == '8' || $val['DeliveryStatus'] == '11'){
	    				$processE = $process;
	    			}else{
	    				$processE = "0.00";
	    			}
                }
			    if(empty($val['orderMoney']) || $val['orderMoney'] == '0'){
                     $conMoney = $val['orderTempMoney'];
			    }else{
			    	 $conMoney = $val['orderMoney'];
			    }
			    if(empty($val['deductMoney'])){
                     $deductMoney = "0";
			    }else{
			    	 $deductMoney = $val['deductMoney'];
			    }
			$processMoney = ($processE/100) * ($conMoney - $deductMoney);
		    //��������ֵ
		      $updateSql="update ".$val['tablename']." set projectProcess=".$processE.",processMoney=".$processMoney." where id=".$val['orgid']."";
		      $this->query($updateSql);
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
	 * ��������ID ��ȡ ���ϵ�δִ������
	 *  $productIds = "xxx,yyy,zzz"
	 *  $type = "oa_sale_order,oa_sale_service,oa_sale_lease,oa_sale_rdproject"
	 *  $type Ϊ��ʱ��Ĭ��ȫ��
	 *  $productIds Ϊ��ʱ��Ĭ��ȫ��
	 */
	function getProductExecutedNum($productIds,$type){
		 if(empty($type)){
            $type = "oa_sale_order,oa_sale_service,oa_sale_lease,oa_sale_rdproject";
		 }
		 $typeArr = explode(",",$type);
		 //��ѯ����
		 $sqlArr = array(
		    "oa_sale_order" => "select o.productId,o.productName,o.productNo,sum(o.number) as number,sum(o.executedNum) as executedNum from oa_sale_order_equ o LEFT JOIN oa_sale_order oa on oa.id=o.orderId  where oa.state=2 GROUP BY productNo",
		    "oa_sale_service" => "select s.productId,s.productName,s.productNo,sum(s.number) as number,sum(s.executedNum) as executedNum from oa_service_equ s LEFT JOIN oa_sale_service sa on sa.id=s.id  where sa.state=2 GROUP BY productNo",
            "oa_sale_lease"=> "select l.productId,l.productName,l.productNo,sum(l.number) as number,sum(l.executedNum) as executedNum from oa_lease_equ l LEFT JOIN oa_sale_lease la on la.id=l.id where la.state=2 GROUP BY productNo",
            "oa_sale_rdproject" => "select r.productId,r.productName,r.productNo,sum(r.number) as number,sum(r.executedNum) as executedNum from oa_rdproject_equ r LEFT JOIN oa_sale_rdproject ra on ra.id=r.id where ra.state=2 GROUP BY productNo"
		 );

         foreach($typeArr as $k=>$v){
         	$typeArr[$k] = $sqlArr[$v];
         }
        $typeNum = count($typeArr);
        //���� type ��̬ƴ�� sql
        switch($typeNum) {
        	case "1" : $sqlLimit =  $typeArr[0];break;
        	case "2" : $sqlLimit =  $typeArr[0]." union ".$typeArr[1];break;
        	case "3" : $sqlLimit =  $typeArr[0]." union ".$typeArr[1]." union ".$typeArr[2];break;
        	case "4" : $sqlLimit =  $typeArr[0]." union ".$typeArr[1]." union ".$typeArr[2]." union ".$typeArr[3];break;
        }
        //where ����
        if(!empty($productIds)){
        	$productSql = "and c.productId in (".$productIds.") ";
        }else{
        	$productSql = "";
        }


		 if(!empty($productIds)){
		 	$sql = "select productId,productName,productNo,sum(number) - sum(executedNum) as num from (
				   $sqlLimit
			 	) c where 1=1 $productSql GROUP BY c.productNo ";
	        return $productInfo = $this->_db->getArray($sql);
		 }else{
		 	return "";
		 }
	}
/*************************************************************************************************/
	/**
	 * ���º�ͬ״̬
	 */
	function updateContractState_d($rows) {
		foreach($rows as $key => $val){
				$tablename=$val['tablename'];
				$state = $val['state'];
				$objCode = $val['objCode'];
				if(empty($val['orderMoney'])){
		        	$orderMoney=$val['orderTempMoney'];
		        }else{
		        	$orderMoney=$val['orderMoney'];
		        }
		        $deductMoney = $val['deductMoney'];
		        $money = $orderMoney-$deductMoney;
                $projectProcess = $val['projectProcess'];
				//��ȡ������Ŀ״̬
				$projectStateDao = new model_engineering_project_esmproject();
				$projectState = $projectStateDao->checkIsCloseByRobjcode_d($objCode);
				//�ж��Ƿ�������
				if($state == "2" || $state == "3" || $state == "4"){
					 $sqlLimit = $this->getContractState($val,$projectState,$projectProcess);
					 $updateSql = "update ".$tablename." set $sqlLimit where id=" . $val['orgid'] . "";
                     $this->query($updateSql);
				}
		}
	}

	/*
	 * �жϺ�ͬ״̬
	 */
	function getContractState($row,$projectState,$projectProcess){
        $tablename=$row['tablename'];
        $orderId = $row['orgid'];
        $DeliveryStatus = $row['DeliveryStatus'];
        $sate = $row['state'];
        if(empty($row['orderMoney']) || $row['orderMoney'] == "0.00"){
        	$orderMoney=$row['orderTempMoney'];
        }else{
        	$orderMoney=$row['orderMoney'];
        }
        $deductMoney = $row['deductMoney'];
        $invoiceMoney = $row['invoiceMoney'];
        $incomeMoney = $row['incomeMoney'];
        $money = $orderMoney-$deductMoney;
        if(empty($deductMoney)){
           $deductMoney = "0.00";
        }
        if(empty($invoiceMoney)){
           $invoiceMoney = "0.00";
        }
        if(empty($incomeMoney)){
           $incomeMoney = "0.00";
        }
          $equArr = array(
               "oa_sale_order" => "oa_sale_order_equ",
               "oa_sale_service" => "oa_service_equ",
               "oa_sale_lease" => "oa_lease_equ",
               "oa_sale_rdproject" => "oa_rdproject_equ"
           );
          $isEuqSql = "select count(*) as num from ".$equArr[$tablename]." where orderId=".$orderId."";
          $isEuqArr = $this->_db->getArray($isEuqSql);
          $shipTips = $isEuqArr[0]['num'];
         if($shipTips == "0"){
         	if($projectState == "2" ){
         		if((($orderMoney-$deductMoney) == $invoiceMoney) && ($invoiceMoney == $incomeMoney)){
//         			$state = 4;
         			$sql="state=4,projectProcess=100,processMoney=$money";
         		}else{
//         			$state = 2;
         			$sql="state=2";
         		}
         	}else{
         		if($projectState == '1'){
//         			$state = 4;
         			$sql="state=4";
         		}else{
//         			$state = 2;
                    $sql="state=2";
         		}
         	}
         }else if($shipTips != "0"){
         	 if($projectState == "2"){
         	 	 if($DeliveryStatus == "8" || $DeliveryStatus == "11"){
//         	 	 	 $state = 4;
         	 	 	 $sql="state=4";
         	 	 }else{
//         	 	 	 $state = 2;
         	 	 	 $sql="state=2";
         	 	 }
         	 }else{
         	 	if(($DeliveryStatus == "8" || $DeliveryStatus == "11") && ($projectState == "1" || $projectProcess == "100")){
//         	 	 	 $state = 4;
         	 	 	 $sql="state=4";
         	 	 }else{
//         	 	 	 $state = 2;
         	 	 	 $sql="state=2";
         	 	 }
         	 }
         }
         return $sql;
	}


	/**
	 * �ж��Ƿ����ʼ����ɹ�
	 */
	 function purchaseMail($proId){
         $sql = " select issuedPurNum,productNo,productName,productModel,number from oa_sale_order_equ where id=".$proId."";
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
				SELECT productId,orderId,count(*) as countNum,sum(isDel) as delNum FROM oa_sale_order_equ WHERE isTemp=0
				GROUP BY productId,orderId HAVING count(*)>1
				AND (sum(IF(isDel>0,issuedProNum,0))>0 OR sum(IF(isDel>0,issuedPurNum,0))>0 OR sum(IF(isDel>0,issuedShipNum,0))>0 )
				ORDER BY orderId
				)c LEFT JOIN oa_sale_order s ON (c.orderId=s.id) WHERE delNum>0
				GROUP BY orderId;";
			$idArr = $this->_db->getArray($idSql);
		  	$expectSql = "SELECT productId,orderId FROM (
				SELECT productId,orderId,count(*) as countNum,sum(isDel) as delNum FROM oa_sale_order_equ WHERE isTemp=0
				GROUP BY productId,orderId HAVING count(*)>1
				AND (sum(IF(isDel>0,issuedProNum,0))>0 OR sum(IF(isDel>0,issuedPurNum,0))>0 OR sum(IF(isDel>0,issuedShipNum,0))>0 )
				ORDER BY orderId
				)c LEFT JOIN oa_sale_order s ON (c.orderId=s.id) WHERE delNum>0 AND countNum-delNum>1;";
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
			//������������
			$this->updateOther();
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
	  	$equDao = new model_projectmanagent_order_orderequ();
		if( $docId ){
			$isDelSql = "SELECT * FROM oa_sale_order_equ WHERE orderId='".$docId."' and isTemp=0 AND isDel=1".$condition;
			$isDelArr = $this->_db->getArray($isDelSql);
			if( is_array($isDelArr)&&count($isDelArr)>0 ){
				$oldEquIdArr = array();
				$equSql = "SELECT * FROM oa_sale_order_equ WHERE orderId='".$docId."' and isTemp=0 AND isDel=0 ".$condition." order by id DESC";
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
	/**
	 * ���·����ƻ����ɹ������嵥������ͬid
	 */
	  function updateRelInfo($oldEquIdArr){
	  	foreach( $oldEquIdArr as $key=>$val ){
	  		$planSql = "update oa_stock_outplan_product set contEquId='".$val['newId']."' where docType='oa_sale_order' and contEquId='".$val['oldId']."'";
	  		$this->_db->query($planSql);
	  		$planSql = "update oa_purch_plan_equ set applyEquId='".$val['newId']."' where purchType='oa_sale_order' and applyEquId='".$val['oldId']."'";
	  		$this->_db->query($planSql);
	  	}
	  }

	  function updateEquNum($docId){
	  	//��������
	  	$outStockSql = "UPDATE  oa_sale_order_equ  eq,(
			select relDocId as relDocItemId,sum(actOutNum) as actOutNum from (
				select oi.relDocId ,IF(o.isRed=0,oi.actOutNum,-oi.actOutNum)as actOutNum  FROM  oa_stock_outstock_item oi
				INNER JOIN oa_stock_outstock o on(o.id=oi.mainId)
				WHERE oi.relDocId <> 0 AND o.relDocType = 'XSCKDLHT' AND o.contractType='oa_sale_order'
			UNION ALL
				select op.contEquId as relDocId,IF(o.isRed=0,oi.actOutNum,-oi.actOutNum)as actOutNum FROM oa_stock_outstock_item oi
				INNER JOIN oa_stock_outstock o on(o.id=oi.mainId)
				INNER JOIN oa_stock_outplan_product op on(oi.relDocId=op.id)
				WHERE oi.relDocId <> 0 AND o.relDocType = 'XSCKFHJH' AND o.contractType='oa_sale_order'
			)sub GROUP BY relDocId) sub1
			set eq.executedNum=sub1.actOutNum
				where eq.id=sub1.relDocItemId  and eq.executedNum<>sub1.actOutNum AND eq.orderId='".$docId."'";
			$this->_db->query($outStockSql);
	  	//�ƻ�����
	  	$outPlanSql = "UPDATE oa_sale_order_equ e LEFT JOIN (
						SELECT e.id,e.orderId,e.issuedShipNum,e.number,c.* FROM oa_sale_order_equ e LEFT JOIN (
						SELECT
						IFNULL(sum(op.number),0) AS pNumber,
						op.contEquId,
						o.id AS oId,
						o.docCode AS oDocCode
						FROM
						oa_stock_outplan_product op
						RIGHT JOIN oa_stock_outplan o ON (o.id = op.mainId)
						WHERE
						 op.contEquId is not NULL AND o.docType='oa_sale_order' AND op.isDelete=0
						GROUP BY
						op.contEquId,o.docId HAVING op.contEquId<>0
						)c ON e.id=c.contEquId
						) p
						ON (e.id=p.contEquId AND e.orderId=p.orderId)
						SET e.issuedShipNum=p.pNumber
						WHERE p.pNumber is not NULL and e.orderId='".$docId."'";
			$this->_db->query($outPlanSql);
	  	//�ɹ�����
	  	$purchSql = "UPDATE oa_sale_order_equ e LEFT JOIN (
							SELECT e.id,e.orderId,e.issuedPurNum,e.number,c.* FROM oa_sale_order_equ e LEFT JOIN (
							SELECT
							IFNULL(sum(op.amountAll),0) AS pNumber,
							op.applyEquId,
							o.id AS oId
							FROM
							oa_purch_plan_equ op
							RIGHT JOIN oa_purch_plan_basic o ON (o.id = op.basicId)
							WHERE
							 op.applyEquId is not NULL AND o.purchType='oa_sale_order'
							GROUP BY
							op.applyEquId,o.sourceID HAVING op.applyEquId<>0
							)c ON e.id=c.applyEquId
							) p
							ON (e.id=p.applyEquId AND e.orderId=p.orderId)
							SET e.issuedPurNum=p.pNumber
							WHERE p.pNumber is not NULL  and e.orderId='".$docId."'";
			$this->_db->query($purchSql);
	  	//����״̬
	  	$shipStatusSql = "UPDATE oa_sale_order c inner JOIN (
							SELECT c.orderId,
									CASE WHEN ( c.countNum<=0 ) THEN '8'
											 WHEN ( c.countNum>0 AND c.executedNum=0 ) THEN '7'
											 WHEN ( c.countNum>0 AND c.executedNum>0 ) THEN '10'
									END AS DeliveryStatus
							FROM (select count(0) as equCount,sum(IF (c.remainNum>0,1,0)) AS countNum,c.orderId,
											(select sum(o.executedNum) from oa_sale_order_equ o where o.orderId=c.orderId and o.isTemp=0 and o.isDel=0) as executedNum
											from (select e.id,e.orderId,(e.number-e.executedNum + e.backNum) as remainNum from oa_sale_order_equ e
											where e.isTemp=0 and e.isDel=0) c where 1=1 GROUP BY orderId HAVING orderId is NOT NULL) c
							)e ON ( c.id=e.orderId )
							SET c.DeliveryStatus=e.DeliveryStatus
							WHERE c.DeliveryStatus<>'11' and c.id='".$docId."'";
			$this->_db->query($shipStatusSql);
	  }

	  function updateOther(){
		$sql = "UPDATE oa_sale_order_equ SET executedNum=1 WHERE id=1489 AND orderId=310;";
		$this->_db->query($sql);
		$sql = "update oa_stock_outplan_product set contEquId=1489 where docType='oa_sale_order' and contEquId=1486;";
		$this->_db->query($sql);
		$sql = "update oa_purch_plan_equ set applyEquId=1489 where purchType='oa_sale_order' and applyEquId=1486;";
		$this->_db->query($sql);
		$sql = "UPDATE oa_sale_order_equ SET executedNum=1 WHERE id=8106 AND orderId=1936;";
		$this->_db->query($sql);
		$sql = "update oa_stock_outplan_product set contEquId=8106 where docType='oa_sale_order' and contEquId=7990;";
		$this->_db->query($sql);
		$sql = "update oa_purch_plan_equ set applyEquId=8106 where purchType='oa_sale_order' and applyEquId=7990;";
		$this->_db->query($sql);
		$sql = "UPDATE oa_sale_order_equ SET executedNum=3 WHERE id=6506 AND orderId=1939;";
		$this->_db->query($sql);
		$sql = "UPDATE oa_sale_order_equ SET executedNum=3 WHERE id=6508 AND orderId=1939;";
		$this->_db->query($sql);
		$sql = "update oa_stock_outplan_product set contEquId=6506 where docType='oa_sale_order' and contEquId=6510;";
		$this->_db->query($sql);
		$sql = "update oa_purch_plan_equ set applyEquId=6506 where purchType='oa_sale_order' and applyEquId=6510;";
		$this->_db->query($sql);
		$sql = "update oa_stock_outplan_product set contEquId=6508 where docType='oa_sale_order' and contEquId=6512;";
		$this->_db->query($sql);
		$sql = "update oa_purch_plan_equ set applyEquId=6508 where purchType='oa_sale_order' and applyEquId=6512;";
		$this->_db->query($sql);
	  }

 }
?>