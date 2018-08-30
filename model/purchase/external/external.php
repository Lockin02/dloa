<?php
/* 采购申请统一接口Model层
 * Created on 2011-3-9
 * Created by can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_purchase_external_external extends model_base {
	public $_emailID = "";
	public $_emailName = "";
	function __construct() {
		$this->tbl_name = "oa_purch_plan_basic";
		$this->sql_map = "";
		parent::__construct ();

		$this->purchTypeArr = array (//不同类型采购申请策略类,根据需要在这里进行追加
				"oa_sale_order" => "model_purchase_external_contpurchase", //销售合同采购申请
				"oa_sale_service" => "model_purchase_external_servicepurchase", //服务合同采购申请
				"oa_sale_lease" => "model_purchase_external_rentalpurchase", //租赁合同采购申请
				"oa_sale_rdproject" => "model_purchase_external_rdprojectpurchase", //研发合同采购申请
				"oa_borrow_borrow" => "model_purchase_external_borrowpurchase", //借试用采购申请
				"oa_present_present" => "model_purchase_external_presentpurchase", //赠送采购申请
				"stock" => "model_purchase_external_stock", //补库采购申请
				"rdproject" => "model_purchase_external_rdproject", //研发采购申请
				"assets" => "model_purchase_external_assets", //固定资产采购申请
				"order" => "model_purchase_external_orderpurchase", //订单采购申请
				"produce" => "model_purchase_external_produce",  //生产采购申请
				"HTLX-XSHT" => "model_purchase_external_contract",   //合同采购
				"HTLX-FWHT" => "model_purchase_external_contract",   //合同采购
				"HTLX-ZLHT" => "model_purchase_external_contract",   //合同采购
				"HTLX-YFHT" => "model_purchase_external_contract"  //合同采购
				);


		//----------
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (0 => array ('statusEName' => 'execute', 'statusCName' => '执行中', 'key' => '0' ), 1 => array ('statusEName' => 'Locking', 'statusCName' => '锁定', 'key' => '1' ), 2 => array ('statusEName' => 'end', 'statusCName' => '完成', 'key' => '2' ), 3 => array ('statusEName' => 'close', 'statusCName' => '关闭', 'key' => '3' ), 4 => array ('statusEName' => 'change', 'statusCName' => '待变更', 'key' => '4' ) );
		//调用初始化对象关联类
		parent::setObjAss ();

		// 下达采购申请添加收件人信息
		$this->_emailID = "hao.yuan";
		$this->_emailName = "袁浩";
	}

	/*===================================公用接口开始======================================*/
	/**
	 *下达采购申请，清单显示模板
	 *
	 * @param $rows:物料清单信息
	 * @param $interface：采购申请接口
	 */
	function showAddList_d($rows, $mianRows, $type) {
		//TODO;
		$purchTypeDao = $this->newTypeDao_d ( $type );
		$productDao = new model_stock_productinfo_productinfo ();
		$newRows = array ();
		if($type=="stock"){
			$newRows=$rows;
		}else{
			//去除物料数组中的“成品”与“半成品”
			foreach ( $rows as $key => $val ) {
				$productRow = $productDao->get_d ( $val ['productId'] );
				if ($productRow ['statType'] != 'TJCP' && $productRow ['statType'] != 'TJBCP') {
					array_push ( $newRows, $val );
				}
			}
		}
		$list = $purchTypeDao->showAddList ( $rows, $mianRows );
		return $list;
	}
	/**
	 *根据不同的类型实例化对象
	 *
	 * @param $type		采购类型
	 */
	function newTypeDao_d($type) {
		$purchTypeModel = $this->purchTypeArr [$type];
		return new $purchTypeModel ();
	}

	/**
	 * 根据不同类型的单据的ID，获取其关联的物料清单信息
	 *
	 * @param $parentId   物料表所关联的主表的ID
	 * @return $interface
	 */
	function getItemsByParentId_d($type, $parentId) {
		$purchTypeDao = $this->newTypeDao_d ( $type );
		$itemRows = $purchTypeDao->getItemsByParentId ( $parentId );
		return $itemRows;
	}

	/**
	 * 根据采购类型的单据ID，获取其信息
	 *
	 * @param $id  采购类型的单据ID
	 * @return $interface
	 */
	function getInfoList_d($id, $type) {
		$purchTypeDao = $this->newTypeDao_d ( $type );
		$mainRows = $purchTypeDao->getInfoList ( $id );
		return $mainRows;
	}

	/**
	 * 根据不同的类型采购申请，进行业务处理
	 *
	 * @param $interface
	 * @param $paramArr      进行业务处理时所需要的参数数组
	 */
	function toDealByPurchType_d($type, $paramArr) {
		$purchTypeDao = $this->newTypeDao_d ( $type );
		return $purchTypeDao->dealInfoAtPlan ( $paramArr );
	}

	/**
	 *下达采购申请后页面的跳转，根据业务的不同跳转到不同的页面
	 *
	 * @param $purchType   采购申请类型
	 * @param $type
	 */
	function toShowPage($id, $purchType, $type) {
		$purchTypeDao = $this->newTypeDao_d ( $purchType );
		$purchTypeDao->toShowPage ( $id, $type );
	}

	/*===================================公用接口结束======================================*/

	/*===================================业务处理开始======================================*/
	/**
	 * 添加采购申请
	 *
	 * @param $object
	 * @return return_type
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			//如果物料信息为空同，则抛出异常，下达采购申请失败
			if (! is_array ( $object ['equipment'] )) {
				throw new Exception ( '物料信息不完整，下达失败！' );
			}

			if ($object ['purchType'] == "assets") { //如果采购申请类型为固定资产采购,因为目前还没有固定资产下拉选择，只能固定写死，待有固定资产下拉后，此部分可以去掉
				$object ['objAssName'] = "固定资产采购";
				$object ['objAssType'] = "assets";
				$object ['objAssCode'] = "";
				$object ['equObjAssType'] = "assets_equ";
			}
			if ($object ['purchType'] == "assets" || $object ['purchType'] == "rdproject" || $object ['purchType'] == "produce") {
				$object ['ExaStatus'] = "未提交";
			} else {
				$object ['ExaStatus'] = "完成";
			}

			//add by chengl 2012-04-06 物料确认状态,固定资产与研发采购为0
			if ($object ['purchType'] == "assets"||$object ['purchType']=="rdproject") {
				$object['productSureStatus']=1;
			}

			$applyEquArr = array();
			$applyEquIds = '';
			//add by chengl 2012-04-20 是否需要物料确认标识，如果物料都是从物料库中选择，则无需物料确认
			foreach ( $object ['equipment'] as $key => $equ ) {
				if(empty($equ['productId'])){
					$object['productSureStatus']=0;
				}
				$applyEquIds .= $equ['applyEquId'].",";
				$applyEquArr[$equ['applyEquId']] = $equ['amountAll'];
			}

			// add by huanghaojin 2017-08-04 PMS 2775 再次检查补库采购物料的下达数量,避免同时两人打开下达页面后,导致的重新下达问题
			if($object ['purchType'] == "stock"){
				$applyEquIds = rtrim($applyEquIds,",");
				if($applyEquIds != '' && !empty($applyEquArr)){
					$allEqus = $this->_db->getArray("select id,sequence,amountAllOld,issuedPurNum from oa_stock_fillup_detail where id in ({$applyEquIds});");
					$errorStr = "";
					foreach ($allEqus as $key => $equ){
						if(bcadd($applyEquArr[$equ['id']],$equ['issuedPurNum']) > $equ['amountAllOld']){// 错误条件为【申请数量+已下达数量 >= 原单申请总数】
							$errorStr .= "物料{$equ['sequence']}已下达采购数量为{$equ['issuedPurNum']}, ";
						}
					}
					if($errorStr != ""){
						$errorStr = "下达失败! ".$errorStr."请确认后重试。";
						throw new Exception ( $errorStr );
						exit();
					}
				}
			}

			if(isset($object['instruction'])&&trim($object['instruction'])=='请填写采购时需要特别注意的事项'){
				$object['instruction']='';
			}

			$object ['state'] = $this->statusDao->statusEtoK ( 'execute' );
			$object ['objCode'] = $this->objass->codeC ( "purch_plan" );
			$codeDao = new model_common_codeRule ();
			$object ['planNumb'] = $codeDao->purchApplyCode ( $this->tbl_name, $object ['purchType'] );
			$object ['isTemp'] = 0;
			$object ['isChange'] = 0;

			//获取归属公司名称
			$object['formBelong']         = $_SESSION['USER_COM'];
			$object['formBelongName']     = $_SESSION['USER_COM_NAME'];
			$object['businessBelong']     = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$planId = parent::add_d ( $object, true );

			$equDao = new model_purchase_plan_equipment ();
			$addAssObjs = array ();
			$interfObj = new model_common_interface_obj (); //对象接口类


			if ("rdprojectNew" == $object ['purchType']) { //研发采购(由于固定资产系统没上，研发采购暂时用回以前的)
				$itemsObj = $this->rdEdit_d ( $object ['equipment'], $planId ,$object ['planNumb']);
				foreach ( $itemsObj as $key => $itemObj ) {
					$addAssObjs [] = array ('planAssType' => $object ['purchType'], 'planAssCode' => $object ['sourceNumb'], 'planAssName' => $object ['objAssName'], 'planAssId' => $object ['sourceID'], 'planAssEquId' => $itemObj ['equObjAssId'], //关联合同设备id
'planCode' => $object ['planNumb'], 'planId' => $object ['id'], 'planEquType' => $object ['equObjAssType'], //申请设备关联类型
'planEquId' => $itemObj ['id'] );
				}

			} else {
				$datadictDao=new model_system_datadict_datadict();

				foreach ( $object ['equipment'] as $key => $equ ) {
					$isDelTag = isset($equ ['isDelTag']) ? $equ ['isDelTag'] : NULL;
					if(empty($isDelTag) && $isDelTag != 1){
						//申请数量大于0并且采购申请物料名称不为空才进行操作
						$productId=$equ ['productId'];
						$productName=$equ ['productName'];
						$inputProductName=$equ ['inputProductName'];
						if ($equ ['amountAll'] > 0 && (!empty($productId)||!empty($productName) ||!empty($inputProductName))) {
							if(empty($productId)&&!empty($productName)){//研发采购手工输入非生产物料
								$equ ['inputProductName']=$productName;
							}
							$i = isset ( $i ) ? (++ $i) : 1; //判断有多少条可用产品清单
							$equ ['basicId'] = $planId;
							$equ ['basicNumb'] = $object ['planNumb'];
							$equ ['purchType'] = $object ['purchType'];
							$equ ['objCode'] = $this->objass->codeC ( 'purch_plan_equ' );
							$equ ['status'] = $equDao->statusDao->statusEtoK ( 'execution' );
							$equ ['amountIssued'] = 0; //确保已下达数量一开始为0
							$equ ['amountAllOld'] = $equ ['amountAll'];
							$equ ['isTask'] = 0;
							if ($equ ['dateHope'] == '') {
								unset ( $equ ['dateHope'] );
							}
							if ($object ['purchType'] == 'produce') {
								$equ ['batchNumb']=$object ['batchNumb'];
							}

							//add by chengl 2012-04-06 添加采购物料类型判断
							$equ['productCategoryName']=$datadictDao->getDataNameByCode($equ['productCategoryCode']);
							$equ['qualityName']=$datadictDao->getDataNameByCode($equ['qualityCode']);


							//统一策略,进行各采购申请类型的业务处理
							$purchTypeDao = $this->purchTypeArr [$object ['purchType']];
							if ($purchTypeDao) {
								$paramArr = array ('uniqueCode' => $equ ['uniqueCode'], 'issuedPurNum' => $equ ['amountAll'], 'equObjAssId' => $equ ['equObjAssId'] );
							}
							$this->toDealByPurchType_d ( $object ['purchType'], $paramArr );

							if(substr($equ['purchType'],0,5)=='HTLX-'||$equ['purchType']=='oa_borrow_borrow'){
//								$equ ['amountAll'] = 0;
								$equ ['amountAll'] = $equ ['amountAllOld'];
							}
							$equId = $equDao->add_d ( $equ );
							//设置采购总关联表数据
							$addAssObjs [] = array ('planAssType' => $object ['purchType'], 'planAssCode' => $object ['sourceNumb'], 'planAssName' => $object ['objAssName'], 'planAssId' => $object ['sourceID'], 'planAssEquId' => $equ ['equObjAssId'], //关联合同设备id
	'planCode' => $object ['planNumb'], 'planId' => $planId, 'planEquType' => $object ['equObjAssType'], //申请设备关联类型
	'planEquId' => $equId );
						}
					}
				}
				//不存在产品时，抛出异常
				if ($i == 0) {
					throw new Exception ( '采购申请无可用设备' );
				}
			}


			//更新附件关联关系
			$this->updateObjWithFile($planId,$object['planNumb']);
			//附件处理
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $planId, $object ['planNumb'] );
			}

			//加入采购总关联表
			$this->objass->addModelObjs ( "purch", $addAssObjs );
			$this->commit_d ();
			return $planId;

		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * add by chengl 2012-04-21
	 * 编辑物料确认打回的采购申请
	 */
	function editBack_d($object){
		try {
			$this->start_d ();

			//如果物料信息为空同，则抛出异常，下达采购申请失败
			if (! is_array ( $object ['equipment'] )) {
				throw new Exception ( '物料信息不完整，下达失败！' );
			}
			$planId = parent::edit_d ( $object, true );
			$addAssObjs = array ();


			$equDao = new model_purchase_plan_equipment ();

			$interfObj = new model_common_interface_obj (); //对象接口类
			$datadictDao=new model_system_datadict_datadict();
			foreach ( $object ['equipment'] as $key => $equ ) {
				//申请数量大于0并且采购申请物料名称不为空才进行操作
				$productId=$equ ['productId'];
				$productName=$equ ['productName'];
				$inputProductName=$equ ['inputProductName'];
				if ($equ ['amountAll'] > 0 && (!empty($productId)||!empty($productName) ||!empty($inputProductName))) {
					if(empty($productId)&&!empty($productName)){//研发采购手工输入非生产物料
						$equ ['inputProductName']=$productName;
					}
					$equ['isProduce']=$equ['isProduce']=="on"?1:0;
					$i = isset ( $i ) ? (++ $i) : 1; //判断有多少条可用产品清单
					$equ ['basicId'] = $object ['id'];
					$equ ['basicNumb'] = $object ['planNumb'];
					$equ ['purchType'] = $object ['purchType'];
					$equ ['objCode'] = $this->objass->codeC ( 'purch_plan_equ' );
					$equ ['status'] = $equDao->statusDao->statusEtoK ( 'execution' );
					$equ ['amountIssued'] = 0; //确保已下达数量一开始为0
					if ($equ ['dateHope'] == '') {
						unset ( $equ ['dateHope'] );
					}
					if ($object ['purchType'] == 'produce') {
						$equ ['batchNumb']=$object ['batchNumb'];
					}
					//add by chengl 2012-04-06 添加采购物料类型判断
					$equ['productCategoryName']=$datadictDao->getDataNameByCode($equ['productCategoryCode']);
					$equ['qualityName']=$datadictDao->getDataNameByCode($equ['qualityCode']);
					$equId=$equ['id'];
					if(empty($equId)){
						$equId = $equDao->add_d ( $equ );
						//设置采购总关联表数据
						$addAssObjs [] = array ('planAssType' => $object ['purchType'], 'planAssCode' => $object ['sourceNumb'], 'planAssName' => $object ['objAssName'], 'planAssId' => $object ['sourceID'], 'planAssEquId' => $equ ['equObjAssId'], //关联合同设备id
						'planCode' => $object ['planNumb'], 'planId' => $object ['id'], 'planEquType' => $object ['equObjAssType'], //申请设备关联类型
						'planEquId' => $equId );
					}else{
						$equDao->edit_d ( $equ );
					}

				}
				//不存在产品时，抛出异常
				if ($i == 0) {
					throw new Exception ( '采购申请无可用设备' );
				}
			}

			//加入采购总关联表
			$this->objass->addModelObjs ( "purch", $addAssObjs );
			$this->commit_d ();
			return true;

		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * 编辑采购申请
	 *
	 * @param $object  编辑数组
	 * @return return_type
	 */
	function edit_d($object) {
		try {
			$this->start_d ();

			//如果物料信息为空同，则抛出异常，下达采购申请失败
			if (! is_array ( $object ['equipment'] )) {
				throw new Exception ( '物料信息不完整，下达失败！' );
			}
			$planId = parent::edit_d ( $object, true );
			$sql = "delete from oa_purch_objass where planId='" . $object ['id'] . "'";
			$this->query ( $sql );
			$addAssObjs = array ();

			if ("rdprojectNew" == $object ['purchType']) { //研发采购
				$itemsObj = $this->rdEdit_d ( $object ['equipment'], $object ['id'],$object ['planNumb'] );
				foreach ( $itemsObj as $key => $itemObj ) {
					$addAssObjs [] = array ('planAssType' => $object ['purchType'], 'planAssCode' => $object ['sourceNumb'], 'planAssName' => $object ['objAssName'], 'planAssId' => $object ['sourceID'], 'planAssEquId' => $itemObj ['equObjAssId'], //关联合同设备id
'planCode' => $object ['planNumb'], 'planId' => $object ['id'], 'planEquType' => $object ['equObjAssType'], //申请设备关联类型
'planEquId' => $itemObj ['id'] );
				}
			} else {
				$equDao = new model_purchase_plan_equipment ();
				//先删除从表数据
				$condiction = array ('basicId' => $object ['id'] );
				$equDao->delete ( $condiction );

				$interfObj = new model_common_interface_obj (); //对象接口类
				$datadictDao=new model_system_datadict_datadict();
				foreach ( $object ['equipment'] as $key => $equ ) {
					unset($equ['id']);
					//申请数量大于0并且采购申请物料名称不为空才进行操作
					$productId=$equ ['productId'];
					$productName=$equ ['productName'];
					$inputProductName=$equ ['inputProductName'];
					if ($equ ['amountAll'] > 0 && (!empty($productId)||!empty($productName) ||!empty($inputProductName))) {
						if(empty($productId)&&!empty($productName)){//研发采购手工输入非生产物料
							$equ ['inputProductName']=$productName;
						}
						$equ['isProduce']=$equ['isProduce']=="on"?1:0;
						$i = isset ( $i ) ? (++ $i) : 1; //判断有多少条可用产品清单
						$equ ['basicId'] = $object ['id'];
						$equ ['basicNumb'] = $object ['planNumb'];
						$equ ['purchType'] = $object ['purchType'];
						$equ ['objCode'] = $this->objass->codeC ( 'purch_plan_equ' );
						$equ ['status'] = $equDao->statusDao->statusEtoK ( 'execution' );
						$equ ['amountIssued'] = 0; //确保已下达数量一开始为0
						$equ ['amountAllOld'] = $equ ['amountAll'];
						if ($equ ['dateHope'] == '') {
							unset ( $equ ['dateHope'] );
						}
						if ($object ['purchType'] == 'produce') {
							$equ ['batchNumb']=$object ['batchNumb'];
						}
						//add by chengl 2012-04-06 添加采购物料类型判断
						$equ['productCategoryName']=$datadictDao->getDataNameByCode($equ['productCategoryCode']);
						$equ['qualityName']=$datadictDao->getDataNameByCode($equ['qualityCode']);

						$equId = $equDao->add_d ( $equ );
						//设置采购总关联表数据
						$addAssObjs [] = array ('planAssType' => $object ['purchType'], 'planAssCode' => $object ['sourceNumb'], 'planAssName' => $object ['objAssName'], 'planAssId' => $object ['sourceID'], 'planAssEquId' => $equ ['equObjAssId'], //关联合同设备id
						'planCode' => $object ['planNumb'], 'planId' => $object ['id'], 'planEquType' => $object ['equObjAssType'], //申请设备关联类型
						'planEquId' => $equId );
					}
				}
				//不存在产品时，抛出异常
				if ($i == 0) {
					throw new Exception ( '采购申请无可用设备' );
				}
			}

			//加入采购总关联表
			$this->objass->addModelObjs ( "purch", $addAssObjs );
			$this->commit_d ();
			return true;

		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 *
	 * 研发确认保存
	 */
	function confirm_d($object) {
		try {
			$this->start_d ();
			//如果物料信息为空同，则抛出异常，下达采购申请失败
			if (! is_array ( $object ['equipment'] )) {
				throw new Exception ( '物料信息不完整，下达失败！' );
			}
			$planId = parent::edit_d ( $object, true );
			$sql = "delete from oa_purch_objass where planId='" . $object ['id'] . "'";
			$this->query ( $sql );
			$addAssObjs = array ();

			$itemsObj = $this->rdEdit_d ( $object ['equipment'], $object ['id'] );

			$object = $this->get_d ( $object ['id'] );
			$assetApplyObj = array ("applyDetId" => $object ['departId'], //
			"applyDetName" => $object ['department'], //
			"purchCategory"=>"CGZL-YFL",
			"applicantId" => $object ['sendUserId'], //
			"applicantName" => $object ['sendName'], "applyTime" => $object ['sendTime'], //
			//"planCode" => $object ['departId'], "planYear" => $object ['departId'], //
			"useDetId" => $object ['departId'], "useDetName" => $object ['department'], //
			//"userId" => $object ['sendUserId'], "userName" => $object ['sendName'], //
			"userTel" => $object ['phone'], "createName" => $object ['createName'], //
			"createId" => $object ['createId'], "createTime" => $object ['createTime'], //
			"updateName" => $object ['updateName'], "updateId" => $object ['updateId'], //
			"projectId" => $object ['projectId'], "projectName" => $object ['projectName'], "projectCode" => $object ['projectCode'], //
			"state"=>"未提交",
			"updateTime" => $object ['updateTime'], "ExaStatus" => $object ['ExaStatus'], "ExaDT" => $object ['ExaDT'], "assetUse" => $object ['assetUse'], "remark" => $object ['instruction'], "applyItem" => "" );

						foreach ( $itemsObj as $key => $itemObj ) {
							$addAssObjs [] = array ('planAssType' => $object ['purchType'], 'planAssCode' => $object ['sourceNumb'], 'planAssName' => $object ['objAssName'], 'planAssId' => $object ['sourceID'], 'planAssEquId' => $itemObj ['equObjAssId'], //关联合同设备id
			'planCode' => $object ['planNumb'], 'planId' => $object ['id'], 'planEquType' => $object ['equObjAssType'], //申请设备关联类型
			'planEquId' => $itemObj ['id'] );

							if ($itemObj ['isAsset'] == "on") {
								$assetApplyItem = array ("productCode" => $itemObj ['productNumb'], "productName" => $itemObj ['productName'], "pattem" => $itemObj ['pattem'], //
			"unitName" => $itemObj ['unitName'], "supplierName" => $itemObj ['surpplierName'], //
			"supplierId" => $itemObj ['surpplierId'], "isAsset" => $itemObj ['isAsset'], //
			"planPrice" => $itemObj ['planPrice'], "equUseYear" => $itemObj ['equUseYear'], //
			"dateHope" => $itemObj ['dateHope'], "remark" => $itemObj ['remark'], "applyAmount" => $itemObj ['amountAll'] );
					$assetApplyObj ['applyItem'] [] = $assetApplyItem;

		//					array_push ( $assetApplyObj ['applyItem'], $assetApplyItem );
				}
			}
			//加入采购总关联表
			$this->objass->addModelObjs ( "purch", $addAssObjs );

			//copy数据到固定资产采购
			$assetApplyDao = new model_asset_purchase_apply_apply ();
			if (is_array ( $assetApplyObj ['applyItem'] )) {
				$assetApplyDao->add_d ( $assetApplyObj );
			}

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	/**
	 *
	 *研发采购编辑保存
	 */
	function rdEdit_d($itemArr, $id,$basicNumb) {
		$equDao = new model_purchase_plan_equipment ();
		$resultArr = array ();
		foreach ( $itemArr as $key => $value ) {
			$value ['status'] = $equDao->statusDao->statusEtoK ( 'execution' );
			$value ['basicId'] = $id;
			$value ['basicNumb'] = $basicNumb;
			$value ['purchType'] = "rdproject";
			$value ['amountIssued'] = 0; //确保已下达数量一开始为0
			//add by chengl 2012-04-06 添加采购物料类型判断
			$value['productCategoryName']=$datadictDao->getDataNameByCode($value['productCategoryCode']);
			array_push ( $resultArr, $value );
		}
//		$itemSaveArr = util_arrayUtil::setItemMainId ( "basicId", $id, $itemArr );
		return $equDao->saveDelBatch ( $resultArr );
	}

    /**
	 *  获取默认邮件发送人
	 */
	function getSendMen_d(){
		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = isset($mailUser[$this->tbl_name][0]) ? $mailUser[$this->tbl_name][0] : array('TO_ID'=>'',
				'TO_NAME'=>'');
		return $mailArr;
	}

	/**
	 * 下达采购申请，发送邮件
	 *@param $id 采购申请id
	 *
	 */
	 function sendEmail_d($id,$emailArr){
		try{
			$this->start_d();
			//获取采购申请单信息及物料信息
			$basicDao = new model_purchase_plan_basic();
			$rows=$basicDao->getPlan_d($id);
			if($emailArr['TO_ID']!=""){
				$addmsg="";

//				$addMsgBeforeContent = ($emailArr['typeName'] == '')? '' : '<table width="98%" border="0" cellpadding="0" cellspacing="0" align="center"><tbody><tr>'.
//				'<td align="center">'.$emailArr['typeName'].'</td></tr></tbody></table>';
				$addMsgBeforeContent = $emailArr['typeName'];
				if(is_array($rows['childArr'])){
					$testTypeArr = array(
						'0'=>'全检',
						'1'=>'免检',
						'2'=>'抽检',
					);
					foreach( $rows['childArr'] as $key => $val ){
						if( $val['testType']!='' ){
							$testType = $val['testType'];
							$rows['childArr'][$key]['testType']=$testTypeArr[$testType];
						}
					}
					$j=0;
					//构造表格详细信息
					$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>物料编码</b></td><td><b>检验方式</b></td><td><b>数量</b></td><td><b>下达日期</b></td><td><b>期望完成日期</b></td><td><b>备注</b></td></tr>";
					foreach($rows['childArr'] as $key => $equ ){
						$j++;
						$productName=$equ['productName'];
						$productCode=$equ ['productNumb'];
						$testType=$equ ['testType'];
						$amountAll=$equ ['amountAll'];
						$dateIssued=$equ ['dateIssued'];
						$dateHope=$equ['dateHope'];
						$remark=$equ['remark'];
						$addmsg .=<<<EOT
						<tr align="center" >
									<td>$j</td>
									<td>$productName</td>
									<td>$productCode</td>
									<td>$testType</td>
									<td>$amountAll</td>
									<td>$dateIssued</td>
									<td>$dateHope</td>
									<td>$remark</td>
								</tr>
EOT;
						}
						$addmsg.="</table>";
				}
				$operator = (isset($emailArr['operator']))? $emailArr['operator'] : $_SESSION['USERNAME'];
				$emailDao = new model_common_mail();
				$emailInfo = $emailDao->purchasePlanMail('y',$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,$operator.'下达新的采购申请,编号为：<font color=red><b>'.$rows['planNumb'].'</b></font>','',$emailArr['TO_ID'],$addmsg,1,$addMsgBeforeContent);

			}
			$this->commit_d();
			return true;
		}catch (Exception $e){
			$this->rollBack();
			return null;
		}

	 }
/*===================================业务处理结束======================================*/
}
?>
