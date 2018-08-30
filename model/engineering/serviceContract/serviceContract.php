<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @description: 服务合同Model类
 * @date 2010-12-1 下午04:25:46
 */
class model_engineering_serviceContract_serviceContract extends model_base {
	function __construct(){
		include (WEB_TOR."model/common/mailConfig.php");
		$this->tbl_name = "oa_sale_service";
		$this->sql_map = "engineering/serviceContract/serviceContractSql.php";
		$this->mailArr=$mailUser[$this->tbl_name];
		parent::__construct();
	}
/*********************************************************************************************/
  	/*
	 * @desription 服务合同的保存方法
	 * @param tags
	 * @date 2010-12-2 上午11:43:05
	 */
	function addContract_d ($arr) {
		try{
			$this->start_d();
			//如果是自动产生编码
			$orderCodeDao = new model_common_codeRule ();
			if ($arr ['orderInput'] == "1") {
				if ($arr ['sign'] == "否") {
					$arr ['orderTempCode'] = $orderCodeDao->contractCode ( $this->tbl_name, $arr ['cusNameId'] );
					if(empty($arr['orderCode'])){
						$arr ['orderTempCode'] = "LS".$arr ['orderTempCode'];
					}

				} else if ($arr ['sign'] == "是") {
					$arr ['orderCode'] = $orderCodeDao->contractCode ( $this->tbl_name, $arr ['cusNameId'] );
				}
			}else{
				 if(!empty($arr['orderTempCode']) && empty($arr['orderCode'])){
					$arr ['orderTempCode'] = "LS".$arr ['orderTempCode'];
				}

			}
			$prinvipalId=$arr['orderPrincipalId'];
			$deptDao=new model_deptuser_dept_dept();
			$dept=$deptDao->getDeptByUserId($prinvipalId);
			$arr['objCode']=$orderCodeDao->getObjCode($this->tbl_name."_objCode",$dept['Code']);

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$arr['orderNatureName'] = $datadictDao->getDataNameByCode ( $arr['orderNature'] );
			//插入主表信息
			$contractId=parent::add_d($arr,true);
			$arr['id']=$contractId;

            //更新商机里的状态，变成“已生成合同”
			$chanceDao = new model_projectmanagent_chance_chance();
			$condiction = array('id'=>$arr['chanceId']);
			$flag = $chanceDao->updateField($condiction,"status","4");

			//插入从表信息
			//配置清单
			$serviceList = new model_engineering_serviceContract_servicelist();
			if(!empty($arr['servicelist'])){
				$serviceList -> createBatch($arr['servicelist'],array('orderId' => $contractId), 'serviceItem');
			}
			//产品清单
			$serviceequDao = new model_engineering_serviceContract_serviceequ();
            if(!empty($arr['serviceequ'])){
            	$serviceequDao -> createBatch($arr['serviceequ'],array('orderId'=> $contractId),'productName');
            	$serviceequDao->updateUniqueCode_d($contractId);

            	$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $contractId, 'objType' => $this->tbl_name , 'extType' => $serviceequDao->tbl_name ),
					'orderId',
					'license'
				);
            }
            //自定义清单
            $customizelistDao = new model_engineering_serviceContract_customizelist();
            if(!empty($arr['customizelist'])){
            	$customizelistDao -> createBatch($arr['customizelist'],array('orderId'=> $contractId),'productName');
            	$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $contractId, 'objType' => $this->tbl_name , 'extType' => $customizelistDao->tbl_name ),
					'orderId',
					'license'
				);
            }
			//培训计划
			$serviceTrainingplanDao = new model_engineering_serviceContract_trainingplan();
			if(!empty($arr['trainingplan'])){
				$serviceTrainingplanDao -> createBatch($arr['trainingplan'],array('orderId' => $contractId),'beginDT');
			}
			//合并后处理
            if(!empty($arr['orderCode']) && !empty($arr['orderTempCode'])){
				$this->changeOrderStatus_d($contractId,$arr['orderTempCode']);
            }
			//处理附件名称和Id
		     $this->updateObjWithFile($contractId);

//            $this->rollBack();
			$this->commit_d();
			return $contractId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}

		return true;
	}


	/**
	 * 处理临时合同
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
 * 查看合并后的临时合同信息
 */
	function TempOrderView($row,$skey) {
		foreach( $row as $key=>$val){
             $temp = array('orderTempCode' => $val);
             $TempId = implode('',$this->find($temp,null,'id'));

            $row[$key].='<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=engineering_serviceContract_serviceContract&action=toViewTab&id='.$TempId.'&perm=view&skey='.$skey.'\')">';
		} ;

		return implode(',',$row);
	}

   /**
    * 渲染方法 -查看
    */
     function initView($object){

        if(!empty($object['servicelist'])){
        	$serviceList = new model_engineering_serviceContract_servicelist();
        	$object['servicelist'] = $serviceList->initTableView($object['servicelist']);
        }else{
        	$object['servicelist'] = '<tr><td colspan="4">暂无相关信息</td></tr>';
        }
     	if(!empty($object['serviceequ'])){
          $serviceequDao = new model_engineering_serviceContract_serviceequ();
          $object['serviceequ'] = $serviceequDao->initTableView($object['serviceequ'],$object['id']);
        }else{
        	$object['serviceequ'] = '<tr><td colspan="10">暂无相关信息</td></tr>';
        }
        if(!empty($object['customizelist'])){
        	$customizelist = new model_engineering_serviceContract_customizelist();
        	$object['customizelist'] = $customizelist->initTableView($object['customizelist']);
        }else{
        	$object['customizelist'] = '<tr><td colspan="10">暂无相关信息</td></tr>';
        }
        if(!empty($object['trainingplan'])){

          $serviceTrainingplanDao = new model_engineering_serviceContract_trainingplan();
          $object['trainingplan'] = $serviceTrainingplanDao->initTableView($object['trainingplan']);
        }else{
        	$object['trainingplan'] = '<tr><td colspan="10">暂无相关信息</td></tr>';
        }

        return $object;
     }


	/**
	 * 重写get_d方法
	 */
	function get_d($id,$selection = null){
		//提取主表信息
		$rows = parent::get_d($id);

		if(empty($selection)){
			//提取从表信息
			$serviceList = new model_engineering_serviceContract_servicelist();//配置清单
			$rows['servicelist'] = $serviceList ->getDetail_d($id);

			$orderequDao = new model_engineering_serviceContract_serviceequ();//设备清单
			$rows['serviceequ'] = $orderequDao->getDetail_d($id);

            $customizelistDao = new model_engineering_serviceContract_customizelist();//自定义清单
            $rows['customizelist'] = $customizelistDao->getDetail_d($id);
	        $orderTrainingplanDao = new model_engineering_serviceContract_trainingplan();//培训计划
	        $rows['trainingplan'] = $orderTrainingplanDao->getDetail_d($id);
		}else if(is_array($selection)){
			if(in_array('servicelist',$selection)){
                $seviceList = new model_engineering_serviceContract_servicelist();
                $rows['servicelist'] = $serviceList->getDetail_d($id);
			}

			if(in_array('serviceequ',$selection)){
				$orderequDao = new model_engineering_serviceContract_serviceequ();//设备清单
				$rows['serviceequ'] = $orderequDao->getDetail_d($id);
			}

			if(in_array('customizelist',$selection)){
				$customizelistDao = new model_engineering_serviceContract_customizelist();//自定义清单
				$rows['customizelist'] = $orderequDao->getDetail_d($id);
			}

			if(in_array('trainingplan',$selection)){
				$orderTrainingplanDao = new model_engineering_serviceContract_trainingplan();//培训计划
	       		$rows['trainingplan'] = $orderTrainingplanDao->getDetail_d($id);

			}
		}

		return $rows;
	}
    //get (发货用)
    function getShip_d($id,$selection = null){
		//提取主表信息
		$rows = parent::get_d($id);

		if(empty($selection)){
			//提取从表信息
			$serviceList = new model_engineering_serviceContract_servicelist();//配置清单
			$rows['servicelist'] = $serviceList ->getDetail_d($id);

			$orderequDao = new model_engineering_serviceContract_serviceequ();//设备清单
			$rows['serviceequ'] = $orderequDao->getShip_d($id);

            $customizelistDao = new model_engineering_serviceContract_customizelist();//自定义清单
            $rows['customizelist'] = $customizelistDao->getDetail_d($id);
	        $orderTrainingplanDao = new model_engineering_serviceContract_trainingplan();//培训计划
	        $rows['trainingplan'] = $orderTrainingplanDao->getDetail_d($id);
		}else if(is_array($selection)){
			if(in_array('servicelist',$selection)){
                $seviceList = new model_engineering_serviceContract_servicelist();
                $rows['servicelist'] = $serviceList->getDetail_d($id);
			}

			if(in_array('serviceequ',$selection)){
				$orderequDao = new model_engineering_serviceContract_serviceequ();//设备清单
				$rows['serviceequ'] = $orderequDao->getShip_d($id);
			}

			if(in_array('customizelist',$selection)){
				$customizelistDao = new model_engineering_serviceContract_customizelist();//自定义清单
				$rows['customizelist'] = $orderequDao->getDetail_d($id);
			}

			if(in_array('trainingplan',$selection)){
				$orderTrainingplanDao = new model_engineering_serviceContract_trainingplan();//培训计划
	       		$rows['trainingplan'] = $orderTrainingplanDao->getDetail_d($id);

			}
		}

		return $rows;
	}
    /**
     * 获取带权限过滤的get_d
     */
    function getByPurview_d($id,$selection = null){
        $rows = $this->get_d($id,$selection = null);
        //权限过滤,如果是合同负责人和区域负责人、合同创建人，则不限制字段权限过滤
        if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['orderPrincipalId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']){
            $rows = $this->filterWithoutField('服务合同金额',$rows,'form',array('orderMoney','orderTempMoney'));
            $rows['serviceequ'] = $this->filterWithoutField('服务设备金额',$rows['serviceequ'],'list',array('price','money'));
        }
        return $rows;
    }

	/**
	 * 渲染方法 - 编辑
	 */
	function initEdit($object){
//         echo "<pre>";
//         print_r($object);
        //配置清单
        $serviceList = new model_engineering_serviceContract_servicelist();
        $rows = $serviceList->initTableEdit($object['servicelist']);
        $object['productNumber'] = $rows[0];
        $object['servicelist'] = $rows[1];
		//设备
		$serviceDao = new model_engineering_serviceContract_serviceequ();
		$rows = $serviceDao->initTableEdit($object['serviceequ']);
		$object['productNumber'] = $rows[0];
		$object['serviceequ'] = $rows[1];
        //自定义清单
        $customizelistDao = new model_engineering_serviceContract_customizelist();
        $rows = $customizelistDao->initTableEdit($object['customizelist']);
        $object['PreNum'] = $rows[0];
	    $object['customizelist'] = $rows[1];

        //培训计划
        $serviceTrainingplanDao = new model_engineering_serviceContract_trainingplan();
        $rows = $serviceTrainingplanDao->initTableEdit($object['trainingplan']);
        $object['TraNumber'] = $rows[0];
        $object['trainingplan'] = $rows[1];
		return $object;
	}

	/**
	 * 渲染方法 - 转为正式合同
	 */
	function becomeEdit($object){
        //配置清单
        $serviceList = new model_engineering_serviceContract_servicelist();
        $rows = $serviceList->initTableEdit($object['servicelist']);
        $object['productNumber'] = $rows[0];
        $object['servicelist'] = $rows[1];
		//设备
		$serviceDao = new model_engineering_serviceContract_serviceequ();
		$rows = $serviceDao->proTableEdit($object['serviceequ']);
		$object['productNumber'] = $rows[0];
		$object['serviceequ'] = $rows[1];
        //自定义清单
        $customizelistDao = new model_engineering_serviceContract_customizelist();
        $rows = $customizelistDao->initTableEdit($object['customizelist']);
        $object['PreNum'] = $rows[0];
	    $object['customizelist'] = $rows[1];

        //培训计划
        $serviceTrainingplanDao = new model_engineering_serviceContract_trainingplan();
        $rows = $serviceTrainingplanDao->initTableEdit($object['trainingplan']);
        $object['TraNumber'] = $rows[0];
        $object['trainingplan'] = $rows[1];
		return $object;
	}
    /**
     * 单独物料修改 从表渲染
     */
    function editProduct($object){
		//设备
		$serviceDao = new model_engineering_serviceContract_serviceequ();
		$rows = $serviceDao->proTableEdit($object['serviceequ']);
		$object['productNumber'] = $rows[0];
		$object['serviceequ'] = $rows[1];
        return $object;
	}
	/**从表变更列表
	*author can
	*2011-6-2
	*/
	function initChange($object){
        //配置清单
        $serviceList = new model_engineering_serviceContract_servicelist();
        $rows = $serviceList->initTableChange($object['servicelist']);
        $object['productNumber'] = $rows[0];
        $object['servicelist'] = $rows[1];
		//设备
		$serviceDao = new model_engineering_serviceContract_serviceequ();
		$rows = $serviceDao->initTableChange($object['serviceequ']);
		$object['productNumber'] = $rows[0];
		$object['serviceequ'] = $rows[1];

        //培训计划
        $serviceTrainingplanDao = new model_engineering_serviceContract_trainingplan();
        $rows = $serviceTrainingplanDao->initTableChange($object['trainingplan']);
        $object['TraNumber'] = $rows[0];
        $object['trainingplan'] = $rows[1];
		return $object;
	}


	/**
	 * 重写编辑方法
	 */
	function edit_d($object){

		try{
			$this->start_d();

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object['orderNatureName'] = $datadictDao->getDataNameByCode ( $object['orderNature'] );

			//修改主表信息
			parent::edit_d($object,true);

			$serviceId = $object['id'];
			//插入从表信息
            //服务内容
            $serviceList = new model_engineering_serviceContract_servicelist();
            $serviceList ->delete(array('orderId' => $serviceId));
            $serviceList ->createBatch($object['servicelist'],array('orderId' => $serviceId), 'serviceItem');
			//设备
			$serviceDao = new model_engineering_serviceContract_serviceequ();
            $serviceDao->delete(array('orderId' => $serviceId,'isBorrowToorder' => '0'));
			$serviceDao->createBatch($object['serviceequ'],array('orderId' => $serviceId ),'productName');
            $serviceDao->updateUniqueCode_d($serviceId);
			if($object['serviceequ']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $serviceId, 'objType' => $this->tbl_name , 'extType' => $serviceDao->tbl_name ),
					'orderId',
					'license'
				);
			}

			//自定义清单
            $customizelistDao = new model_engineering_serviceContract_customizelist();
            $customizelistDao->delete(array('orderId' => $serviceId));
            $customizelistDao->createBatch($object['customizelist'],array('orderId' => $serviceId,'orderName' => $object['orderName']),'productName');
            if($object['customizelist']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $serviceId, 'objType' => $this->tbl_name , 'extType' => $customizelistDao->tbl_name ),
					'orderId',
					'license'
				);
			}
            //培训计划
            $serviceTrainingplanDao = new model_engineering_serviceContract_trainingplan();
            $serviceTrainingplanDao->delete(array('orderId' => $serviceId));
            $serviceTrainingplanDao->createBatch($object['trainingplan'],array('orderId' => $serviceId),'beginDT');


			$this->commit_d();
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
		}

		return true;
	}

	/**
	 * 合同转正
	 */
	function becomeEdit_d($object){

		try{
			$this->start_d();

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object['orderNatureName'] = $datadictDao->getDataNameByCode ( $object['orderNature'] );

			//修改主表信息
			parent::edit_d($object,true);
           //更新工程项目关联的数据
            $proDao = new model_engineering_project_esmproject();
            $proDao->updateContractCode_d($object['objCode'],$object['orderCode'],$contractTempCode = '');
			$serviceId = $object['id'];
			//插入从表信息
            //服务内容
            $serviceList = new model_engineering_serviceContract_servicelist();
            $serviceList ->delete(array('orderId' => $serviceId));
            $serviceList ->createBatch($object['servicelist'],array('orderId' => $serviceId), 'serviceItem');
			//设备
			$serviceDao = new model_engineering_serviceContract_serviceequ();
			 foreach($object['serviceequ'] as $k => $v){
				$object['serviceequ'][$k]['oldId'] = $v['proId'];
			}
			foreach($object['serviceequ'] as $k => $v){
            	 if($v['proId'] && empty($v['isEdit']) && empty($v['isDel'])){
            	 	$v['id'] = $v['proId'];
            	 	$serviceDao->edit_d($v);
            	 }
                 if($v['isDel']){
                     $sql = "update ".$serviceDao->tbl_name." set isDel = 1 where id = ".$v['proId']." ";
			         $this->_db->query($sql);
                 }
                 if($v['isEdit'] && empty($v['isDel'])){
                     $sql = "update ".$serviceDao->tbl_name." set isDel = 1 where id = ".$v['proId']." ";
			         $this->_db->query($sql);
			         $v['orderId'] = $serviceId;
			          if(!empty($v['productName'])){
                        $proId = $serviceDao->add_d($v);
			          }
                     $serviceDao->updateUniqueCode_d($serviceId);
                     $licenseDao = new model_yxlicense_license_tempKey();
						$licenseDao->updateLicenseBacth_d(
							array( 'objId' => $serviceId, 'objType' => $this->tbl_name , 'extType' => $serviceDao->tbl_name ),
							'orderId',
							'license'
						);
                 }
                 if($v['isAdd']){
                 	 $v['orderId'] = $serviceId;
                 	  if(!empty($v['productName'])){
                         $proId = $serviceDao->add_d($v);
                 	  }
                     $serviceDao->updateUniqueCode_d($serviceId);
                     $licenseDao = new model_yxlicense_license_tempKey();
						$licenseDao->updateLicenseBacth_d(
							array( 'objId' => $serviceId, 'objType' => $this->tbl_name , 'extType' => $serviceDao->tbl_name ),
							'orderId',
							'license'
						);
                 }
            }

			//自定义清单
            $customizelistDao = new model_engineering_serviceContract_customizelist();
            $customizelistDao->delete(array('orderId' => $serviceId));
            $customizelistDao->createBatch($object['customizelist'],array('orderId' => $serviceId,'orderName' => $object['orderName']),'productName');
            if($object['customizelist']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $serviceId, 'objType' => $this->tbl_name , 'extType' => $customizelistDao->tbl_name ),
					'orderId',
					'license'
				);
			}
            //培训计划
            $serviceTrainingplanDao = new model_engineering_serviceContract_trainingplan();
            $serviceTrainingplanDao->delete(array('orderId' => $serviceId));
            $serviceTrainingplanDao->createBatch($object['trainingplan'],array('orderId' => $serviceId),'beginDT');

            //发送邮件
            //获取默认发送人
		   include (WEB_TOR."model/common/mailConfig.php");
		   $emailDao = new model_common_mail();
		   $emailInfo = $emailDao->contractBecomeEmail('服务合同',1,$_SESSION['USERNAME'],$_SESSION['EMAIL'],"contractBeomce",$object['orderTempCode'],"",$mailUser['contractBecome']['sendUserId']);



			$this->commit_d();
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
		}

		return true;
	}
    /**
     * 单独物料修改
     */
    function proedit_d($object){
		try{
			$this->start_d();
			$serviceId = $object['id'];
			$serviceDao = new model_engineering_serviceContract_serviceequ();
			 foreach($object['serviceequ'] as $k => $v){
				$object['serviceequ'][$k]['oldId'] = $v['proId'];
			}
			$orderInfo = parent::get_d($serviceId);
            $orderInfo['oldId'] = $serviceId;
			$orderInfo['serviceequ'] = $object['serviceequ'];
            //变更处理
			$changeLogDao = new model_common_changeLog ( 'servicecontract',false );
			$tempObjId = $changeLogDao->addLog ( $orderInfo );

			foreach($object['serviceequ'] as $k => $v){
            	 if($v['proId'] && empty($v['isEdit']) && empty($v['isDel'])){
            	 	$v['id'] = $v['proId'];
            	 	$serviceDao->edit_d($v);
            	 }
                 if(isset($v['isDel']) && isset($v['proId'])){
                     $sql = "update ".$serviceDao->tbl_name." set isDel = 1 where id = ".$v['proId']." ";
			         $this->_db->query($sql);
                 }
                 if($v['isEdit'] && empty($v['isDel'])){
                     $sql = "update ".$serviceDao->tbl_name." set isDel = 1 where id = ".$v['proId']." ";
			         $this->_db->query($sql);
			         $v['orderId'] = $serviceId;
                     $proId = $serviceDao->add_d($v);
                     $serviceDao->updateUniqueCode_d($serviceId);
                     $licenseDao = new model_yxlicense_license_tempKey();
						$licenseDao->updateLicenseBacth_d(
							array( 'objId' => $serviceId, 'objType' => $this->tbl_name , 'extType' => $serviceDao->tbl_name ),
							'orderId',
							'license'
						);
                 }
                 if($v['isAdd']){
                 	 $v['orderId'] = $serviceId;
                     $proId = $serviceDao->add_d($v);
                     $serviceDao->updateUniqueCode_d($serviceId);
                     $licenseDao = new model_yxlicense_license_tempKey();
						$licenseDao->updateLicenseBacth_d(
							array( 'objId' => $serviceId, 'objType' => $this->tbl_name , 'extType' => $serviceDao->tbl_name ),
							'orderId',
							'license'
						);
                 }
            }
			$this->commit_d();
//			$this->rollBack();
			return $serviceId;
		}catch(exception $e){

			return false;
		}
	}
	/**变更服务合同
	*author can
	*2011-6-2
	*/
	function change_d($obj) {
		$this->start_d ();
		//删除设备处理
		foreach ( $obj ['serviceequ'] as $key => $val ) {
			if ($val ['number'] == 0) {
				$obj ['serviceequ'] [$key] ['isDel'] = 1;
			}
		}

		//处理数据字典字段
		$datadictDao = new model_system_datadict_datadict ();
		$obj['orderNatureName'] = $datadictDao->getDataNameByCode ( $obj['orderNature'] );

		//变更附件处理
		$changeLogDao = new model_common_changeLog ( 'servicecontract' );
		$obj ['uploadFiles'] = $changeLogDao->processUploadFile ( $obj, $this->tbl_name );
		//print_r($obj ['uploadFiles'] );
		//var_dump($obj ['uploadFiles']);
		if($obj['serviceequ']){
			//处理合同内外
			foreach($obj['serviceequ'] as $key=>$val){
	            $obj['serviceequ'][$key]['isSell'] = isset($obj['serviceequ'][$key]['isSell'])?$obj['serviceequ'][$key]['isSell']:null;
			}
		}
		//变更记录,拿到变更的临时主对象id
		$tempObjId = $changeLogDao->addLog ( $obj );
		//license变更处理
		if($obj['serviceequ']){

			$licenseDao = new model_yxlicense_license_tempKey();
			$licenseDao->updateLicenseBacth_d(
				array( 'objId' => $tempObjId, 'objType' => $this->tbl_name , 'extType' => 'oa_service_equ' ),
				'orderId',
				'license'
			);
		}
		$this->commit_d ();
		return $tempObjId;
	}


    /**
	 * 关闭合同方法
	 */
	function close_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		//加入数据字典处理 add by chengl 2011-05-15
		$this->processDatadict($object);
		return $this->updateById ( $object );
	}
/*********************************************************************************************/

	/**
	 * 服务合同签收
	 */
	function signin_d($object){

		try{
			$this->start_d();

             $changeLogDao = new model_common_changeLog ( 'serviceSignin',false );
			//变更记录,拿到变更的临时主对象id
			$changeLogDao->addLog ( $object );

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object['orderNatureName'] = $datadictDao->getDataNameByCode ( $object['orderNature'] );

			//修改主表信息
			$object['id'] = $object['oldId'];
			parent::edit_d($object,true);

			$serviceId = $object['oldId'];
			//插入从表信息
            //服务内容
            $serviceList = new model_engineering_serviceContract_servicelist();
            foreach ($object['servicelist'] as $key => $val){
                if (isset ($val['oldId'])){
                     $val['id'] = $val['oldId'];
                     $serviceList->edit_d ( $val );
                }else {
		 	         $serviceList->createBatch(array( $object['servicelist'][$key] ),array('orderId' => $serviceId ),'serviceItem');
                }
                if(isset ($val['isDel'])){
                     $serviceList->delete(array('id' => $val['oldId']));
                }
			}
            if($object['servicelist']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $serviceId, 'objType' => $this->tbl_name , 'extType' => $serviceList->tbl_name ),
					'orderId',
					'license'
				);
			}
			//设备
			$serviceDao = new model_engineering_serviceContract_serviceequ();
			foreach ($object['serviceequ'] as $key => $val){
                if (isset ($val['oldId'])){
                     $val['id'] = $val['oldId'];
                     $serviceDao->edit_d ( $val );
                }else {
		 	         $serviceDao->createBatch(array( $object['serviceequ'][$key] ),array('orderId' => $serviceId ),'productName');
                }
                if(isset ($val['isDel'])){
                     $serviceDao->delete(array('id' => $val['oldId']));
                }
			}
            $serviceDao->updateUniqueCode_d($serviceId);
			if($object['serviceequ']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $serviceId, 'objType' => $this->tbl_name , 'extType' => $serviceDao->tbl_name ),
					'orderId',
					'license'
				);
			}
            //培训计划
            $serviceTrainingplanDao = new model_engineering_serviceContract_trainingplan();
             foreach ($object['trainingplan'] as $key => $val){
                if (isset ($val['oldId'])){
                     $val['id'] = $val['oldId'];
                     $serviceTrainingplanDao->edit_d ( $val );
                }else {
		 	         $serviceTrainingplanDao->createBatch(array( $object['trainingplan'][$key] ),array('orderId' => $serviceId ),'beginDT');
                }
                if(isset ($val['isDel'])){
                     $serviceTrainingplanDao->delete(array('id' => $val['oldId']));
                }
			}


			$this->commit_d();
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
		}

		return true;
	}


/*********************************************************************************************/
/**---------------------------------------------以下是模板调用显示方法--------------------------------------------------*/
	/*
	 * @desription 调用培训计划的接口，显示培训计划的内容
	 * @param tags
	 * @author qian
	 * @date 2010-12-14 上午11:19:24
	 */
	function showTrainList ($contractId) {
		$str = "";
		$i = 0;
		$condiction = array('contractId' => $contractId);
		$trainDao = new model_engineering_serviceTrain_servicetrain();
		$trainInfo = $trainDao->findAll($condiction);
		if($trainInfo){

			$str .=<<<EOT
			<table class="main_table">
			<tr>
				<td width="5%">序号</td>
				<td width="10%">开始日期</td>
				<td width="10%">结束日期</td>
				<td width="5%">参与人数</td>
				<td width="15%">培训地点</td>
				<td width="30%">培训内容</td>
				<td>培训工程师要求</td>
			</tr>
EOT;
			foreach($trainInfo as $key => $val){
				$i++;
				$iClass = (($i%2)==0)?"tr_even":"tr_odd";
				$str .=<<<EOT
					<tr class="$iClass">
						<td>$i</td>
						<td>$val[beginDT]</td>
						<td>$val[endDT]</td>
						<td>$val[traNum]</td>
						<td><textarea class="textarea_read">$val[adress]</textarea></td>
						<td><textarea class="textarea_read">$val[content]</textarea></td>
						<td><textarea class="textarea_read">$val[trainer]</textarea></td>
					</tr>
EOT;
			}
			$str .=<<<EOT
			</table>
EOT;
		}else{
			$str = "暂无培训计划";
		}
		return $str;
	}




/**---------------------------------------------以上是模板调用显示方法--------------------------------------------------*/
/**--------------------------------------------以下是外部接口调用显示方法-------------------------------------------------*/

	/*
	 * @desription 服务合同编辑的保存方法
	 * @param tags
	 * @date 2010-12-5 上午11:01:11
	 */
	function editContract_d ($rows) {
		try{
			$this->start_d();
			$this->updateById($rows);
			$condiction = array('contractID' => $rows['id']);

			$trainDao = new model_engineering_serviceTrain_servicetrain();
			$trainDao->delete($condiction);
			if($rows['train']){
				$trainDao->addTrain_d($rows);
//				foreach($rows['train'] as $key=>$val){
//					if(!empty($val['beginDT'])&&!empty($val['endDT'])){
//						$val['contractID'] = $rows['id'];
//						$val['contractName'] = $rows['name'];
//						$val['contractNo'] = $rows['contractNo'];
//
//						$trainDao->add_d($val,true);
//					}
//				}

			}

			$this->commit_d();
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
		}

		return true;
	}

	/*
	 * @desription 获取未执行（未审核）服务合同数组列表
	 * @param tags
	 * @date 2010-12-2 下午04:16:01
	 */
	function list_d () {
		$arr = parent::list_d();
		$datadictDao = new model_system_datadict_datadict();
		//通过数据字典编码获取服务合同的状态
		foreach($arr as $key => $val){
			$arr[$key]['approvalStatus'] = $datadictDao->getDataNameByCode( $val['ExaStatus'] );
		}
		return $arr;
	}

		/**
	 * 批量删除对象
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

	/**##########################################审批工作流部分################################################*/
	/*
	 * @desription 待审批的列表
	 * @param tags
	 * @date 2010-12-4 上午10:18:32
	 */
	function approvalNo_d ($rows) {
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$i++;
				$str .=<<<EOT
					<tr class="$classCss" >
						<td>$i</td>
						<td>$val[name]</td>
						<td>$val[contractNo]</td>
						<td>$val[cusName]</td>
						<td>$val[cusContCode]</td>
						<td>$val[linkMan]</td>
						<td>$val[depHeads]</td>
						<td>
							<a href='controller/engineering/serviceContract/serviceContract/ewf_index.php?actTo=ewfExam&taskId=$val[task]&spid=$val[ID]&billId=$val[contractId]'>审批</a> |
							<a href='javascript:showOpenWin("?model=engineering_serviceContract_serviceContract&action=init&perm=view&contractId=$val[id]")'>查看</a>
						</td>
					</tr>
EOT;
			}
		}else{
			$str = "<tr><td colspan='8'>暂无相关信息</td></tr>";
		}
		return $str;
	}

	/*
	 * @desription 已审批列表
	 * @param tags
	 * @date 2010-12-4 上午11:47:52
	 */
	function approvalYes_d ($rows) {
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$i++;
				$str .=<<<EOT
					<tr class="$classCss" >
						<td>$i</td>
						<td>$val[name]</td>
						<td>$val[contractNo]</td>
						<td>$val[cusName]</td>
						<td>$val[cusContCode]</td>
						<td>$val[linkMan]</td>
						<td>$val[depHeads]</td>
						<td>
							<a href='controller/engineering/serviceContract/serviceContract/ewf_index.php?actTo=ewfExam&taskId=$val[task]&spid=$val[ID]&billId=$val[contractId]'>审批详情</a> |
							<a href='javascript:showOpenWin("?model=engineering_serviceContract_serviceContract&action=init&perm=view&contractId=$val[id]")'>查看</a>
						</td>
					</tr>
EOT;
			}
		}else{
			$str = "<tr><td colspan='8'>暂无相关信息</td></tr>";
		}
		return $str;
	}

/***********************************************************************************/
     /**
      * 订单处理的设备信息
      */
     function showDetaiInfo($rows) {

     	$orderequDao = new model_engineering_serviceContract_serviceequ();

     	$rows['orderequ'] =
     	$orderequDao->showDetailByOrder( $orderequDao->showEquListInByOrder($rows['id'],'oa_sale_service'));

     	return $rows;
     }
/********************************改变发货状态****************************************************/

	/**
	 * 根据发货情况修改合同及发货计划状态
	 */
	 function updateOrderShipStatus_d( $id ){
	 	$orderRemainSql = "select count(0) as countNum,(select sum(o.executedNum) from oa_service_equ o where o.orderId=".$id." and o.isTemp=0 and o.isDel=0) as executeNum
						 from (select e.orderId,(e.number-e.executedNum) as remainNum from oa_service_equ e
						where e.orderId=".$id." and e.isTemp=0 and e.isDel=0) c where c.remainNum>0";
	 	$remainNum = $this->_db->getArray( $orderRemainSql );
	 	if( $remainNum[0]['countNum'] <= 0 ){//已发货
	 		$DeliveryStatus = 8;
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => $DeliveryStatus,
		 		'state' => '4',
		 		'completeDate' => date("Y-m-d")
		 	);
		 	$this->updateById( $statusInfo );
	 	}elseif( $remainNum[0]['countNum']>0 && $remainNum[0]['executeNum']==0 ){//未发货
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => '7',
		 		'state' => '2'
		 	);
		 	$this->updateById( $statusInfo );
		} else {//部分发货
	 		$DeliveryStatus = 10;
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => $DeliveryStatus,
		 		'state' => '2'
		 	);
		 	$this->updateById( $statusInfo );
	 	}
        $dao= new model_projectmanagent_order_order();
	 	$dao->updateProjectProcess(array("id"=>$id,"tablename"=>"oa_sale_service"));//更新工作量进度、按工作量进度合同额
	 	return 0;
	 }
    /**
     * 改变发货状态
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

	/**##########################################审批工作流部分################################################*/

/**--------------------------------------------以上是外部接口调用显示方法-------------------------------------------------*/
/*********************************************************************************************************************************/

/**
 * 异常关闭审批查看---查看合同
 */
	function closeOrderView($orderId) {

       $row='<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=engineering_serviceCOntract_serviceContract&action=toViewTab&id='.$orderId.'&perm=view\')">';
		return $row;
	}

    /**
     * 判断当前登录人是否合同创建人,负责人,区域负责人
     * 用于权限过滤
     * 2011-07-29
     * createBy show
     */
    function isKeyMan_d($id){
        $thisUserId = $_SESSION['USER_ID'];
        $sql = 'select id from '.$this->tbl_name ." where id = ".$id." and ( createId = '".$thisUserId."' or orderPrincipalId ='".$thisUserId."' or areaPrincipalId ='".$thisUserId."')";
        return $this->_db->getArray($sql);
    }
  /******************************/
			/**
			 * 判断是否为变更的合同
			 */
            function isTemp($conId){
            	$cond = array("id" => $conId);
            	$isTemp = $this->find($cond,'','isTemp');
            	$isTemp = implode(',',$isTemp);
            	return $isTemp;
            }
/******************************************************************************/
    /*物料配件处理*/
    function c_configuration($proId,$Num,$trId,$isEdit){
        $configurationDao = new model_stock_productinfo_configuration ();
        $sql = "select configId,configNum from ".$configurationDao->tbl_name." where hardWareId = $proId and configId > 0";
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
	          $equDao = new model_engineering_serviceContract_serviceequ();
	          $configArr = $equDao->configTableEdit($infoArr,$Num);
	        }else{
	          $equDao = new model_engineering_serviceContract_serviceequ();
	          $configArr = $equDao->configTable($infoArr,$Num);
	        }
        }


        return $configArr;
    }
/********************************************************************/
   function configOrder_d($orderId){
   	     $orderExa = $this->find(array("id" => $orderId),null,"ExaStatus");
   	     $customizelistDao = new model_engineering_serviceContract_customizelist();
   	     $cus = $customizelistDao->find(array("orderId" => $orderId),null,"productName");
   	     $orderExa = implode(",",$orderExa);
   	     if($orderExa == "完成" && !empty($cus)){
           $mailArr=$this->mailArr;
              $orderName = $this->find(array("id" => $orderId),null,"orderName");
              $orderName = implode(",",$orderName);
              $orderCode = $this->find(array("id" => $orderId),null,"orderCode");
              $orderCode = implode(",",$orderCode);
              if(empty($orderCode)){
                  $orderCode = $this->find(array("id" => $orderId),null,"orderTempCode");
                  $orderCode = implode(",",$orderCode);
              }
		      $addmsg = "请处理合同名称为《".$orderName."》,合同号为《".$orderCode."》的  自定义清单内的临时物料信息";
	        $emailDao = new model_common_mail();
	        $emailInfo = $emailDao->batchEmail(1,$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,"审批","通过",$mailArr['sendUserId'],$addmsg);
   	     }
   }

 /********************************************************************************************/
	/**
	 * 验证 合同物料是否需要退货
	 */
	function isGoodsReturn($rows){

		foreach($rows as $k => $v){
			$sql = "SELECT id FROM oa_service_equ where orderId = '".$v['id']."' and isTemp = '0' and isDel = '0'  and (executedNum - number) > 0;";
			$isR = $this->_db->getArray($sql);
			if(!empty($isR)){
				$rows[$k]['isR'] = "1";
			}else{
				$rows[$k]['isR'] = "0";
			}
		}
		return $rows;
	}

    /**************************** 工程项目添加方法**************************/
	/**
	 * 服务合同获取办事处权限
	 */
	function getProvinceNames_d($limitArr){
		$officeIdArr = array();

		//首先获取对应的办事处权限id
//		$otherDataDao = new model_common_otherdatas();
//		$limitArr = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID']);

		if(!empty($limitArr['办事处'])){
			array_push($officeIdArr,$limitArr['办事处']);
		}

		//获取办事处的id
		$officeInfoDao = new model_engineering_officeinfo_officeinfo();
		$officeIds = $officeInfoDao->getOfficeIds_d($_SESSION['USER_ID']);
		if(!empty($officeIds)){
			array_push($officeIdArr,$officeIds);
		}

		$proNames = "";
		//如果返回的办事处id不为空，则查找省份名称
		if(!empty($officeIdArr)){
			$officeIds = implode($officeIdArr,',');
			$proNames = $officeInfoDao->getProNamesByIds_d($officeIds);
		}
		return $proNames;
	}
	/********************************************************************************/
	/**
	 * 获取合同所以数据
	 */
	function getAllOrder_d() {
		$conarr = array ();
		//提取主表信息
		$conarr['info'] = $this->disposeOrderArr($this->exeSql($this->tbl_name));
		//提取从表信息
		$orderequDao = new model_engineering_serviceContract_serviceequ(); //设备清单
		$conarr['orderequ'] = $this->disposeEquArr($this->exeSql($orderequDao->tbl_name));
		$orderTrainingplanDao = new model_engineering_serviceContract_trainingplan(); //培训计划
		$conarr['trainingplan'] = $this->disposeTrainArr($this->exeSql($orderTrainingplanDao->tbl_name));
		return $conarr;
	}
	//查询sql
	function exeSql($tableName) {
		$sql = " select * from " . $tableName . " ";
		return $this->_db->getArray($sql);
	}
	//循环处理合同信息
	function disposeOrderArr($arr) {
		if (empty ($arr)) {
			return "";
		} else {
			$temparr = array ();
			foreach ($arr as $k => $v) {
				$temparr[$k]['oldId'] = $v['id'];
				$temparr[$k]['oldTableName'] = "oa_sale_service";
				$temparr[$k]['objCode'] = $v['objCode'];
				$temparr[$k]['sign'] = $v['sign'];
				$temparr[$k]['signSubjectName'] = "世纪鼎利";
				$temparr[$k]['signSubject'] = "DL";
				$temparr[$k]['oldContractType'] = "HTLX-FWHT";
				if ($v['sign'] == "是") {
					$temparr[$k]['winRate'] = "100%";
				} else {
					$temparr[$k]['winRate'] = "80%";
				}
				if ($v['signinType'] == "service") {
					$temparr[$k]['contractType'] = "HTLX-FWHT";
					$temparr[$k]['contractTypeName'] = "服务合同";
				} else {
					$temparr[$k]['contractType'] = "HTLX-XSHT";
					$temparr[$k]['contractTypeName'] = "销售合同";
				}
				$temparr[$k]['contractNature'] = $v['orderNature'];
				$temparr[$k]['contractNatureName'] = $v['orderNatureName'];
				if (empty ($v['orderCode'])) {
					$temparr[$k]['contractCode'] = $v['orderTempCode'];
				} else {
					$temparr[$k]['contractCode'] = $v['orderCode'];
				}
				$temparr[$k]['contractName'] = $v['orderName'];
				$temparr[$k]['customerName'] = $v['cusName'];
				$temparr[$k]['customerId'] = $v['cusNameId'];
				$temparr[$k]['customerType'] = $v['customerType'];
				$temparr[$k]['contractCountry'] = "中国";
				$temparr[$k]['contractCountryId'] = "1";
				$temparr[$k]['contractProvince'] = $v['orderProvince'];
				$temparr[$k]['contractProvinceId'] = $v['orderProvinceId'];
				$temparr[$k]['contractCity'] = $v['orderCity'];
				$temparr[$k]['contractCityId'] = $v['orderCityId'];
				$temparr[$k]['prinvipalName'] = $v['orderPrincipal']; //负责人
				$temparr[$k]['prinvipalId'] = $v['orderPrincipalId'];
				$temparr[$k]['contractSigner'] = $v['createName'];
				$temparr[$k]['contractSignerId'] = $v['createId'];
				if (empty ($v['orderMoney']) || $v['orderMoney'] == '0') {
					$temparr[$k]['contractMoney'] = $v['orderTempMoney'];
				} else {
					$temparr[$k]['contractMoney'] = $v['orderMoney'];
				}
				$temparr[$k]['invoiceType'] = $v['invoiceType'];
				$temparr[$k]['deliveryDate'] = $v['timeLimit'];
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
                $temparr[$k]['isShipments'] = $v['isShipments'];
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
				$temparr[$k]['tablename'] = "oa_service_equ";
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
		}
		return $temparr;
	}
	function disposeTrainArr($arr) {
		if (empty ($arr)) {
			return "";
		} else {
			$temparr = array ();
			foreach ($arr as $k => $v) {
				$temparr[$k]['tablename'] = "oa_service_trainingplan";
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
	  * 根据合同号获取 合同信息
	  */
     function getContractInfo_d($orderCode){
          $sql = "select * from ".$this->tbl_name." where orderCode = '".$orderCode."' or orderTempCode = '".$orderCode."' ";
          $rows = $this->_db->getArray($sql);
          return $rows[0];
     }
    /**
	 * 根据合同id 查找合同信息（多ID）
	 */
	function getOrderByIds($objId){

       if(!empty($objId)){
       	 $sql = "select * from oa_sale_service where id in ($objId)";
         $rows = $this->_db->getArray($sql);
       }else{
       	 $rows = "";
       }
          return $rows;
	}

	/**
	 * 检查对象是否重复
	 * @新增检查重复无需传入checkId
	 * @修改检查重复需要排除修改对象id
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
	 * 判断是否发送邮件给采购
	 */
	 function purchaseMail($proId){
         $sql = " select issuedPurNum,productNo,productName,productModel,number from oa_service_equ where id=".$proId."";
         $arr = $this->_db->getArray($sql);
         if(!empty($arr[0]['issuedPurNum']) && $arr[0]['issuedPurNum'] > 0){
         	 return $arr[0];
         }else{
         	return "";
         }
	 }


	 /**
	  * 批量更新
	  */
	  function updateEquByType(){
	  	try{
	 		$this->start_d();
		 	$idSql = "SELECT productId,orderId,countNum,orderCode,orderTempCode,delNum FROM (
				SELECT productId,orderId,count(*) as countNum,sum(isDel) as delNum FROM oa_service_equ WHERE isTemp=0
				GROUP BY productId,orderId HAVING count(*)>1
				AND (sum(IF(isDel>0,issuedProNum,0))>0 OR sum(IF(isDel>0,issuedPurNum,0))>0 OR sum(IF(isDel>0,issuedShipNum,0))>0 )
				ORDER BY orderId
				)c LEFT JOIN oa_sale_service s ON (c.orderId=s.id) WHERE delNum>0
				GROUP BY orderId;";
			$idArr = $this->_db->getArray($idSql);
		  	$expectSql = "SELECT productId,orderId FROM (
				SELECT productId,orderId,count(*) as countNum,sum(isDel) as delNum FROM oa_service_equ WHERE isTemp=0
				GROUP BY productId,orderId HAVING count(*)>1
				AND (sum(IF(isDel>0,issuedProNum,0))>0 OR sum(IF(isDel>0,issuedPurNum,0))>0 OR sum(IF(isDel>0,issuedShipNum,0))>0 )
				ORDER BY orderId
				)c LEFT JOIN oa_sale_service s ON (c.orderId=s.id) WHERE delNum>0 AND countNum-delNum>1;";
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
	  * 更新销售合同错误数据
	  */
	  function updateEquInfo($docId,$productId=0){
  		if( $productId==0 ){
  			$condition = "";
  		}else{
			$condition = " and productId not in('".$productId."') ";
  		}
	  	$equDao = new model_engineering_serviceContract_serviceequ();
		if( $docId ){
			$isDelSql = "SELECT * FROM oa_service_equ WHERE orderId='".$docId."' and isTemp=0 AND isDel=1".$condition;
			$isDelArr = $this->_db->getArray($isDelSql);
			if( is_array($isDelArr)&&count($isDelArr)>0 ){
				$oldEquIdArr = array();
				$equSql = "SELECT * FROM oa_service_equ WHERE orderId='".$docId."' and isTemp=0 AND isDel=0 ".$condition." order by id DESC";
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
	  		$planSql = "update oa_stock_outplan_product set contEquId='".$val['newId']."' where docType='oa_sale_service' and contEquId='".$val['oldId']."'";
	  		$this->_db->query($planSql);
	  		$planSql = "update oa_purch_plan_equ set applyEquId='".$val['newId']."' where purchType='oa_sale_service' and applyEquId='".$val['oldId']."'";
	  		$this->_db->query($planSql);
	  	}
	  }

	  function updateEquNum($docId){
	  	//发货数量
	  	$outStockSql = "UPDATE  oa_service_equ  eq,(
			select relDocId as relDocItemId,sum(actOutNum) as actOutNum from (
				select oi.relDocId ,IF(o.isRed=0,oi.actOutNum,-oi.actOutNum)as actOutNum  FROM  oa_stock_outstock_item oi
				INNER JOIN oa_stock_outstock o on(o.id=oi.mainId)
				WHERE oi.relDocId <> 0 AND o.relDocType = 'XSCKDLHT' AND o.contractType='oa_sale_service'
			UNION ALL
				select op.contEquId as relDocId,IF(o.isRed=0,oi.actOutNum,-oi.actOutNum)as actOutNum FROM oa_stock_outstock_item oi
				INNER JOIN oa_stock_outstock o on(o.id=oi.mainId)
				INNER JOIN oa_stock_outplan_product op on(oi.relDocId=op.id)
				WHERE oi.relDocId <> 0 AND o.relDocType = 'XSCKFHJH' AND o.contractType='oa_sale_service'
			)sub GROUP BY relDocId) sub1
			set eq.executedNum=sub1.actOutNum
				where eq.id=sub1.relDocItemId  and eq.executedNum<>sub1.actOutNum AND eq.orderId='".$docId."'";
			$this->_db->query($outStockSql);
	  	//计划数量
	  	$outPlanSql = "UPDATE oa_service_equ e LEFT JOIN (
						SELECT e.id,e.orderId,e.issuedShipNum,e.number,c.* FROM oa_service_equ e LEFT JOIN (
						SELECT
						IFNULL(sum(op.number),0) AS pNumber,
						op.contEquId,
						o.id AS oId,
						o.docCode AS oDocCode
						FROM
						oa_stock_outplan_product op
						RIGHT JOIN oa_stock_outplan o ON (o.id = op.mainId)
						WHERE
						 op.contEquId is not NULL AND o.docType='oa_sale_service' AND op.isDelete=0
						GROUP BY
						op.contEquId,o.docId HAVING op.contEquId<>0
						)c ON e.id=c.contEquId
						) p
						ON (e.id=p.contEquId AND e.orderId=p.orderId)
						SET e.issuedShipNum=p.pNumber
						WHERE p.pNumber is not NULL and e.orderId='".$docId."'";
			$this->_db->query($outPlanSql);
	  	//采购数量
	  	$purchSql = "UPDATE oa_service_equ e LEFT JOIN (
							SELECT e.id,e.orderId,e.issuedPurNum,e.number,c.* FROM oa_service_equ e LEFT JOIN (
							SELECT
							IFNULL(sum(op.amountAll),0) AS pNumber,
							op.applyEquId,
							o.id AS oId
							FROM
							oa_purch_plan_equ op
							RIGHT JOIN oa_purch_plan_basic o ON (o.id = op.basicId)
							WHERE
							 op.applyEquId is not NULL AND o.purchType='oa_sale_service'
							GROUP BY
							op.applyEquId,o.sourceID HAVING op.applyEquId<>0
							)c ON e.id=c.applyEquId
							) p
							ON (e.id=p.applyEquId AND e.orderId=p.orderId)
							SET e.issuedPurNum=p.pNumber
							WHERE p.pNumber is not NULL  and e.orderId='".$docId."'";
			$this->_db->query($purchSql);
	  	//发货状态
	  	$shipStatusSql = "UPDATE oa_sale_service c inner JOIN (
							SELECT c.orderId,
									CASE WHEN ( c.countNum<=0 ) THEN '8'
											 WHEN ( c.countNum>0 AND c.executedNum=0 ) THEN '7'
											 WHEN ( c.countNum>0 AND c.executedNum>0 ) THEN '10'
									END AS DeliveryStatus
							FROM (select count(0) as equCount,sum(IF (c.remainNum>0,1,0)) AS countNum,c.orderId,
											(select sum(o.executedNum) from oa_service_equ o where o.orderId=c.orderId and o.isTemp=0 and o.isDel=0) as executedNum
											from (select e.id,e.orderId,(e.number-e.executedNum + e.backNum) as remainNum from oa_service_equ e
											where e.isTemp=0 and e.isDel=0) c where 1=1 GROUP BY orderId HAVING orderId is NOT NULL) c
							)e ON ( c.id=e.orderId )
							SET c.DeliveryStatus=e.DeliveryStatus
							WHERE c.DeliveryStatus<>'11' and c.id='".$docId."'";
			$this->_db->query($shipStatusSql);
	  }
}
?>
