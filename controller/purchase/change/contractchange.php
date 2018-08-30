<?php
class controller_purchase_change_contractchange extends controller_base_action {
	/**
	 * 构造函数
	 */
	function __construct() {
		$this->objName = "contractchange";
		$this->objPath = "purchase_change";

		parent::__construct ();
	}

	/**
	 * @description 变更的保存方法
	 * @author qian
	 * @date 2011-2-14 11:26
	 */
	function c_change() {
		$service = $this->service;
		$contract = $_POST ['contract'];
		$id = $service->change_d ( $contract );

		if ($_GET ['act'] == "app") {
			$phpurl = 'controller/purchase/change/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic_version&formName=采购合同审批';
			$phpurl = str_replace ( " ", "", $phpurl );
			echo "<script>parent.location.replace('" . $phpurl . "');</script>";
		}

		if ($id) {
			//保存成功后跳转到“个人办公”-“我的申请”
			//		echo "<script>alert('保存成功');parent.location='?model=purchase_change_contractchange&action=toMyChangeList'</script>";
			msgGo ( '变更成功!', "?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab3" );
		}

	}

	/**
	 * @description 采购合同变更审批的Tab页
	 * @author qian
	 * @date 2011-2-14 16:32
	 */
	function c_toApprovalTab() {
		$this->display ( 'tab-approval' );
	}

	/**
	 * @description 未审批的采购合同列表页
	 * @author qian
	 * @date 2011-2-14 16:38
	 */
	function c_toApprovalNo() {
		$this->display ( 'change-approvalNo' );
	}

	/**
	 * add by chengl 2011-05-12
	 * 确认合同变更并且跳转到未审批的采购合同列表页
	 *
	 */
	function c_confirmChangeToApprovalNo() {
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			$objId = $folowInfo ['objId'];
			if (! empty ( $objId )) {
				$contractDao=new model_purchase_contract_purchasecontract();
				$contract = $this->service->get_d ( $objId );
				$contractDao->dealChange_d($contract);
				$changeLogDao = new model_common_changeLog ( 'purchasecontract' );
				$changeLogDao->confirmChange_d ( $contract );
			}
		}

		//防止重复刷新
		$urlType = isset($_GET['urlType']) ? $_GET['urlType'] : null;
		//防止重复刷新
		if($urlType){
			echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
		}else{
			echo "<script>this.location='?model=purchase_contract_purchasecontract&action=pcApprovalNo'</script>";
		}

		//$this->display ( 'change-approvalNo' );
	}

	/**
	 * @description 已审批的采购合同列表页
	 * @author qian
	 * @date 2011-2-14 16:38
	 */
	function c_toApprovalYes() {
		$this->display ( 'change-approvalYes' );
	}

	/**
	 * @description 我申请的采购合同变更
	 * @author qian
	 * @date 2011-2-14 19:20
	 */
	function c_toMyChangeList() {
		$this->display ( 'list-change' );
	}

	/**
	 * @description 跳转到查看合同信息的Tab页
	 * @author qian
	 * @date 2011-2-22
	 */
	function c_toTabView() {
		$id = $_GET ['id'];
		$this->assign ( 'id', $id );
		$applyNumb = $_GET ['applyNumb'];
		$this->assign ( 'applyNumb', $applyNumb );

		$this->display ( 'tab-view' );
	}

	function c_toTabHistory() {
		$id = $_GET ['id'];
		$this->assign ( 'id', $id );
		$applyNumb = $_GET ['applyNumb'];
		$this->assign ( 'applyNumb', $applyNumb );

		$this->display ( 'list-history' );
	}

	/**
	 * 查看历史版本
	 */
	function c_toViewVersion() {
		$id = $_GET ['id'];
		$service = $this->service;
		$returnObj = $this->objName;
		//根据合同号获得变更后合同的数据
		$rows = $service->get_d ( $id );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//根据合同查找出对应的设备的信息
		$equs = $service->getEquipments_d ( $rows );
		$this->show->assign ( "file", $service->getFilesByObjId ( $id, false ) );
		$this->assign ( 'list', $service->addContractEquList_s ( $equs ) );
		//发票类型
		$billingType = $this->getDataNameByCode ( $rows ['billingType'] );
		$this->assign ( 'bType', $billingType );

		//付款类型
		$paymetType = $this->getDataNameByCode ( $rows ['paymetType'] );
		$this->assign ( 'pType', $paymetType );

		//开户银行
		$suppBank = $this->getDataNameByCode ( $rows ['suppBank'] );
		$this->assign ( 'suppBank', $suppBank );

		//签约状态
		$signStatus = $service->getSignStatus_d ( $rows ['signStatus'] );
		$this->assign ( 'signStatus', $signStatus );

		$this->display ( 'view' );
	}

	/**
	 * 审批的查看页面
	 */
	function c_toView() {
		$service = $this->service;
		$returnObj = $this->objName;
		$id = $_GET ['pjId'];
		//根据合同号获得变更后合同的数据
		$rows = $service->get_d ( $id );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//根据合同查找出对应的设备的信息
		$equs = $service->getEquipments_d ( $rows );
		$this->show->assign ( "file", $this->service->getFilesByObjId ( $id, false ) );
		$this->assign ( 'list', $service->addContractEquList_s ( $equs ) );
		//发票类型
		$billingType = $this->getDataNameByCode ( $rows ['billingType'] );
		$this->assign ( 'bType', $billingType );

		//付款类型
		$paymetType = $this->getDataNameByCode ( $rows ['paymetType'] );
		$this->assign ( 'pType', $paymetType );

		//开户银行
		$suppBank = $this->getDataNameByCode ( $rows ['suppBank'] );
		$this->assign ( 'suppBank', $suppBank );

		//历史版本
		$history = $service->toViewHistory_d ( $rows ['applyNumb'] );
		$this->assign ( 'history', $history );

		//签约状态
		$signStatus = $service->getSignStatus_d ( $rows ['signStatus'] );
		$this->assign ( 'signStatus', $signStatus );

		$this->display ( 'view2' );
	}

	/**
	 * 查看/编辑页面
	 */
	function c_init() {
		$service = $this->service;
		$returnObj = $this->objName;
		$id = $_GET ['id'];
		//根据合同号获得变更后合同的数据
		$rows = $service->get_d ( $id );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$equs = $service->getEquipments_d ( $rows );

		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {

			$this->assign ( "file", $this->service->getFilesByObjId ( $id, false ) );
			$this->assign ( 'list', $service->addContractEquList_s ( $equs ) );
			//发票类型
			$billingType = $this->getDataNameByCode ( $rows ['billingType'] );
			$this->assign ( 'bType', $billingType );

			//付款类型
			$paymetType = $this->getDataNameByCode ( $rows ['paymetType'] );
			$this->assign ( 'pType', $paymetType );

			//开户银行
			$suppBank = $this->getDataNameByCode ( $rows ['suppBank'] );
			$this->assign ( 'suppBank', $suppBank );

			//对签约状态的值进行转换
			$signStatus = $service->signStatus_d ( $rows ['signStatus'] );
			$this->assign ( 'signStatus', $signStatus );

			$this->display ( 'viewV' );
		} else {
			$this->assign ( "file", $this->service->getFilesByObjId ( $id, true ) );
			$this->assign ( 'list', $service->editContractEquList_s ( $equs ) );
			$this->showDatadicts ( array ('billingType' => 'FPLX' ), $rows ['billingType'] );
			$this->showDatadicts ( array ('paymetType' => 'fkfs' ), $rows ['paymetType'] );

			//签约状态
			$signStatus = $service->editSignStatus_d ( $rows ['signStatus'] );
			$this->assign ( 'signStatus', $signStatus );

			$this->display ( 'edit' );
		}
	}

	/**
	 * @description 修改变更的合同
	 * @date 2011-3-2
	 */
	function c_editchange() {
		$contract = $_POST ['contract'];
		$equs = $_POST ['equs'];
		$id = $contract ['id'];
		$condiction = array ('id' => $id );

		//更新合同的内容
		$this->service->update ( $condiction, $contract );
		//更新设备的内容
		$equDao = new model_purchase_change_equipmentchange ();
		foreach ( $equs as $key => $val ) {
			$condictionEqu = array ('basicId' => $id, 'id' => $val ['id'] );
			$equDao->update ( $condictionEqu, $val );
		}

		if ($_GET ['act'] == "app") {
			$phpurl = 'controller/purchase/change/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic_version&formName=采购合同审批';
			$phpurl = str_replace ( " ", "", $phpurl );
			echo "<script>parent.location=('" . $phpurl . "');</script>";
		} else {
			//	 	 echo "<script>alert('保存成功');location.href='?model=purchase_change_contractchange&action=init&id=$id';</script>";
			msgGo ( '保存成功', "?model=purchase_change_contractchange&action=toMyChangeList" );
		}

	}

	/**
	 * @description 我的申请-采购合同变更列表
	 * 需要过滤出旧的版本，列表只显示最新版本的合同
	 * 可查看历史变更版本
	 * @author qian
	 * @date 2011-2-17 14:57
	 */
	function c_pageJsonMy() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		$service->asc = true;
		$rows = $service->page_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description 查看历史版本
	 */
	function c_pageJsonHistory() {

		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['updateId'] = $_SESSION ['USER_ID'];
		$service->asc = true;
		$rows = $service->pageBySqlId ( 'select_history' );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );

	}

	/**
	 * 未审批的采购合同变更
	 */
	function c_pageJsonNo() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr ['Flag'] = 0;
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId ( 'sql_examine' );
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription 获取分页数据转成JSON--已审批
	 * @author qian
	 * @date 2011-1-6 下午03:23:39
	 */
	function c_pageJsonYes() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr ['Flag'] = 1;
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$service->groupBy = 'c.id';
		$rows = $service->pageBySqlId ( 'sql_examine2' );
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description 查看历史版本
	 * @author qian
	 * @date 2011-2-17 16:37
	 */
	//	function c_toViewHistory(){
	//		$service = $this->service;
	//		$applyNumb = $_GET['applyNumb'];
	//		$service->searchArr = array( "applyNumb" => $applyNumb );
	//		$rows = $service->pageBySqlId();
	//
	//		return $rows;
	//	}


	/**
	 * @description 将审批通过的采购合同变更数据覆盖到采购合同表里
	 * @author qian
	 * @date 2011-2-16 9:51
	 */
	function c_coverChange($id) {
		$id = $_POST ['id'];
		$service = $this->service;
		//根据版本表里审批通过的合同ID获得合同及设备的数据
		$rows = $service->getRows_d ( $id );

		//对采购合同及设备的数据进行覆盖
		$flag = $service->coverChange_d ( $rows );
		$condiction = array ("id" => $id );
		$service->updateField ( $condiction, "isChanged", "0" );
		if ($flag) {
			//      echo "<script>alert('保存成功');parent.parent.location = '?model=purchase_contract_purchasecontract&action=myPurchaseContractTab';</script>";
			//      echo "<script>alert('确认成功');location = '?model=purchase_change_contractchange&action=toApprovalYes';</script>";
			echo 1;
		}
	}

}
?>
