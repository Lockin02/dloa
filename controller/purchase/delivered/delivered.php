<?php
/**
 * @author suxc
 * @Date 2011年5月6日 9:52:24
 * @version 1.0
 * @description:退料通知单信息控制层
 */
class controller_purchase_delivered_delivered extends controller_base_action {

	function __construct() {
		$this->objName = "delivered";
		$this->objPath = "purchase_delivered";
		parent::__construct ();
	 }

	/*
	 * 跳转到未执行退料通知单信息
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

	/*
	 * 跳转到已执行退料通知单信息
	 */
    function c_toCloseList() {
      $this->show->display($this->objPath . '_' . $this->objName . '-close-list');
    }

    /**
	 *跳转到添加退料通知单页面.
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_toAdd () {
		$act=isset($_GET['act'])?$_GET['act']:null;
		$this->assign('act',$act);
//		$deliveredCode="delivered-".generatorSerial();
		$this->assign("purchManId" , $_SESSION['USER_ID']);
		$this->assign("purchManName" , $_SESSION['USERNAME']);
//		$this->assign('returnCode',$deliveredCode);
		$this->assign('returnDate',date("Y-m-d"));
		$this->display('add-external');
	}

    /**
	 *在列表添加退料通知单页面.
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_toAddInGrid () {
		$act=isset($_GET['act'])?$_GET['act']:null;
		$this->assign('act',$act);
//		$deliveredCode="delivered-".generatorSerial();
		$this->assign("purchManId" , $_SESSION['USER_ID']);
		$this->assign("purchManName" , $_SESSION['USERNAME']);
//		$this->assign('returnCode',$deliveredCode);
		$this->assign('returnDate',date("Y-m-d"));
		$this->display('add');
	}

	/**重写初始化对象方法
	*author can
	*2011-2-22
	*/
	function c_init() {
		$this->permCheck ();//安全校验
		$returnObj = $this->objName;
		$returnObj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $returnObj as $key => $val ) {
			$this->show->assign ( $key, $val );
		$equipmentDao=new model_purchase_delivered_equipment();
		}
		//获取检验申请单物料清单
		$itemRows=$this->service->getEquipment_d($_GET ['id']);

		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$viewType=isset($_GET['actType'])?$_GET['actType']:"";
		    $list=$equipmentDao->showViewList($itemRows);;
			$this->show->assign('list',$list);
			$this->assign('viewType',$viewType);
			$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
		} else {
			$list=$equipmentDao->showEditList($itemRows);
			$this->show->assign('list',$list);
			$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
		}
	}

	/**
	 * 我的退料通知单
	 */
	function c_toMyList () {
		$this->display('my-list');
	}

	/**
	 * 我的退料通知单
	 */
	function c_toPushByArrival () {
		$this->assign("purchManId" , $_SESSION['USER_ID']);
		$this->assign("purchManName" , $_SESSION['USERNAME']);
		$this->assign('returnDate',date("Y-m-d"));
		$arrivalDao=new model_purchase_arrival_arrival();
		$arrivalObj=$arrivalDao->get_d($_GET['arrivalId']);
//		echo "<pre>";
//		print_r($arrivalObj);exit();
        $this->assign("formBelong", $arrivalObj['formBelong']);
        $this->assign("formBelongName", $arrivalObj['formBelongName']);
        $this->assign("businessBelong", $arrivalObj['businessBelong']);
        $this->assign("businessBelongName", $arrivalObj['businessBelongName']);

		$this->assign("purchManId", $arrivalObj['purchManId']);
		$this->assign("purchManName", $arrivalObj['purchManName']);
		$this->assign("supplierId", $arrivalObj['supplierId']);
		$this->assign("supplierName", $arrivalObj['supplierName']);
		$this->assign("sourceId", $arrivalObj['id']);
		$this->assign("sourceCode", $arrivalObj['arrivalCode']);
		$this->assign("deliveryPlace", $arrivalObj['deliveryPlace']);

		$purchMode = $this->getDataNameByCode($arrivalObj['purchMode']);
		$this->assign("purchMode", $purchMode);
		$equDao=new model_purchase_arrival_equipment();
		$rows=$equDao->getItemByBasicIdId_d($_GET['arrivalId']);
		//print_r($rows);
		//echo $this->service->showPurchAppProInfo($rows);
		$this->assign("itemsList", $this->service->showPurchAppProInfo($rows));

		$this->display('arrival-push');
	}


	/**我的退料通知单PageJson过滤方法
	*/
	function c_myApplyPJ() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$applyUserId = isset($_SESSION ['USER_ID'])?$_SESSION ['USER_ID']:null;
		$service->searchArr['createId'] = $applyUserId;
		$rows = $service->pageBySqlId();
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

    /**
	 * 添加退料通知单
	 *
	 */
	 function c_add(){
	 	$id=$this->service->add_d($_POST[$this->objName]);
	 	if($id){
	 		if($_GET['type']=="external"){     //在grid列表外添加退料通知单，保存后跳转页面
	 			msgGo('保存成功','?model=purchase_delivered_delivered&action=toAdd');
	 		}else{
				msg('保存成功');
	 		}
	 	}else{
	 		if($_GET['type']=="external"){
	 			msgGo('保存失败','?model=purchase_delivered_delivered&action=toAdd');
	 		}else{
	 		msg('保存失败，物料信息不完整');
	 		}
	 	}
	 }

	/**
	 * 添加固定资产退料通知单
	 *
	 */
	 function c_addAsset(){
	 	$id=$this->service->addAsset($_POST[$this->objName]);
	 	if($id){
	 		msg('保存成功!');
	 	}else{
	 		msg('保存失败!');
	 	}
	 }

	/**
	 * 进行采购退料时直接提交审批
	 */
	function c_toSubmitAudit () {
		$id=$this->service->add_d($_POST[$this->objName]);
		if($id){
	 		if($_GET['type']=="external"){     //在grid列表外添加退料通知单，保存后跳转页面
	 			succ_show ( 'controller/purchase/delivered/ewf_index.php?actTo=ewfSelect&billId='.$id.'&examCode=oa_purchase_delivered&formName=采购退料审批' );
	 		}else{
				succ_show ( 'controller/purchase/delivered/ewf_index_parent.php?actTo=ewfSelect&billId='.$id.'&examCode=oa_purchase_delivered&formName=采购退料审批' );
	 		}
		}else{
	 		if($_GET['type']=="external"){
	 			msgGo('提交失败','?model=purchase_delivered_delivered&action=toAdd');
	 		}else{
	 			msg('提交失败，物料信息不完整');
	 		}
		}
	}

	/**
	 * 进行采购退料时直接提交审批
	 */
	function c_edit () {
		$id=$this->service->edit_d($_POST[$this->objName]);
		if($id){
	 		if($_GET['type']=="aduit"){     //在grid列表外添加退料通知单，保存后跳转页面
	 			succ_show ( 'controller/purchase/delivered/ewf_index_parent.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id'].'&examCode=oa_purchase_delivered&formName=采购退料审批' );
	 		}else{
				msg('保存成功');
	 		}
		}else{
	 			msg('保存失败，物料信息不完整');
		}
	}
	/**
	 * 获取物料清单
	 */
	function c_addItemList () {
		$arrivalId=isset($_POST['arrivalId'])?$_POST['arrivalId']:null;
		$arrivalproDao=new model_purchase_arrival_equipment();
		$rows=$arrivalproDao->getItemByBasicIdId_d($arrivalId);
		$arrivalprostr = $this->service->showPurchAppProInfo($rows);
		echo $arrivalprostr;
	}
	/**
	 * 添加入库单时，动态添加从表模板
	 */
	function c_getItemList () {
		$arrivalId=isset($_POST['deliveredId'])?$_POST['deliveredId']:null;
		$list=$this->service->getEquList_d($arrivalId);
		echo $list;
	}

	/**
	 * 获取分页数据转成Json-我的采购，收料通知单
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		//$service->asc = false;
        $service->_isSetCompany =$service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * 退料审批通过后更新相对应收料数量
	 */
	 function c_updateApplyPrice(){
		$rows=isset($_GET['rows'])?$_GET['rows']:null;
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['examines']=="ok"){  //审批通过
				//更新收料数量
				$this->service->updateArrivalNum_d($folowInfo['objId']);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	 }
 }
?>