<?php
/*
 * Created on 2010-7-8
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_contract_change_change extends controller_base_action{

	function __construct(){
		$this->objName = "change";
		$this->objPath = "contract_change";
		parent :: __construct();
	}

	/**
	 * 初始化对象
	 */
	function c_showAction(){
	   $rows = $this->service->initChange($_GET['id'],'new');
		$rowsT = $rows['change'];
		unset($rows['change']);

		$this->assignFunc($rowsT);

		$this->assignFunc($rows);

		$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
		$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
		$this->display( 'read-new');
	}

	/**
	 * 显示修改变更申请单页面
	 */
	function c_editChangeForm(){
		$service = $this->service;
		$rows = $service->initChangeEdit($_GET['id'],'new');
		$rowsT = $rows['change'];
		$rowsT['formId'] = $rows['change']['id'];
		unset($rows['change']);

		$this->assignFunc($rowsT);

		$this->assignFunc($rows);

		$this->showDatadicts(array ('invoiceType' => 'FPLX'), $rows['invoiceType']);
		$this->showDatadicts(array ('customerType' => 'KHLX'), $rows['customerType']);
		$this->display('edit');
	}

	/**
	 * 修改无审批记录的变更申请单
	 */
	function c_editChangeAction(){
		$object = $this->service->editChange ( $_POST ['sales'] );
		if ($object && $_GET ['act'] == 'save') {
			showmsg ( '保存成功！', 'index1.php?model=contract_change_change&action=myChangeAction' );
		} else if ($object) {
			succ_show('controller/contract/change/ewf_index.php?actTo=ewfSelect&billId=' . $object . '&examCode=oa_contract_change&formName=合同变更');
		} else {
			showmsg ( '编辑失败！', 'index1.php?model=contract_change_change&action=myChangeAction' );
		}
	}

	/**
	 * 显示变更打回后重新编辑页面
	 */
	 function c_backEditChangeForm(){
		$service = $this->service;
		$rows = $service->initChangeEdit($_GET['id'],'new');
		$rowsT = $rows['change'];
		$rowsT['formId'] = $rows['change']['id'];
		unset($rows['change']);

		$this->assignFunc($rowsT);

		$this->assignFunc($rows);

		$this->showDatadicts( array ('invoiceType' => 'FPLX' ) ,$rows['invoiceType']);

		$this->display('backedit');
	 }

	 /**
	  * 修改变更打回后编辑变更申请单
	  */
	function c_backEdit(){
		$object = $this->service->backEditChange ( $_POST ['sales'] );
		if ($object && $_GET ['act'] == 'save') {
			showmsg ( '保存成功！', 'index1.php?model=contract_change_change&action=myChangeAction' );
		} else if ($object) {
			succ_show('controller/contract/change/ewf_index.php?actTo=ewfSelect&billId=' . $object . '&examCode=oa_contract_change&formName=合同变更');
		} else {
			showmsg ( '编辑失败！', 'index1.php?model=contract_change_change&action=myChangeAction' );
		}
	}

	/**
	 * 删除对象-数据库操作阶段
	 */
	function c_delT() {
		if ($this->service->deletesT ( $_POST ['id'] )) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 删除对象-假删除-数据库操作阶段
	 */
	function c_notdel() {
		if ($this->service->notDel ( $_POST ['id'] )) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 启动合同变更
	 */
	function c_beginChange(){
		if($this->service->beginThisChange($_POST['id'])){
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 我的变更申请
	 */
	 /****专业分割线****/
	 function c_myChangeAction() {
        $this->display('mychange');
	 }
    /**
	 * 我的变更申请-获取分页数据转成Json
	 */
	function c_pageJsonMyChange() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息

        $service->searchArr['applyId'] = $_SESSION['USER_ID'];
        $rows = $service->pageBySqlId('change_list2');

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

     /**
      * 我的审批-合同审批-销售合同变更
      */

     function c_contractChangeTab() {
     	$this->display('auditTab');
     }
	/**
	 * 待审批的变更申请
	 */
	function c_waitExemineChangeAction(){
		$this->display( 'unaudit');
	}
    /**
	 * 待审批的变更申请Json
	 */
	function c_ConpageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//$service->asc = false;

	    $rows = $service->waitExaminingChange();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * 已审批的变更申请
	 */
	function c_exeminedChangeAction(){
		$this->display( 'audited');
	}

    /**
	 * 已审批的变更申请Json
	 */
	function c_ConYsppageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//$service->asc = false;

	    $rows = $service->examinedChange();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * 审批变更申请单时产看变更申请单及合同信息
	 */
	function c_showChangeAndNew(){
		$service = $this->service;
		$this->assign ( 'topPlan', $this->topPlan ( array (
															"clickNumb" => 1,
															 "numb" => 2,
															 "name1" => "当前申请变更的合同",
															 "title1" => "当前申请变更的合同",
															 "url1" => "index1.php?model=contract_change_change&action=showChangeAndNew&id=$_GET[id]",
															 "name2" => "变更前的合同",
															 "title2" => "变更前的合同",
															 "url2" => "index1.php?model=contract_change_change&action=showChangeAndOld&id=$_GET[id]" )
															 )
							 );
		$rows = $this->service->initChange($_GET['id'],'new');
		$rowsT = $rows['change'];
		unset($rows['change']);
		$this->assignFunc($rowsT);

		$this->assignFunc($rows);

		$this->assign ( 'invoiceType', $this->getDataNameByCode($rows['invoiceType']) );
		$this->assign ( 'customerType', $this->getDataNameByCode($rows['customerType']) );
		$this->display ('readnewinex' );
	}

	/**
	 * 查看看申请单以及旧的合同
	 */
	function c_showChangeAndOld(){
		$service = $this->service;
		$this->assign ( 'topPlan', $this->topPlan ( array (
															"clickNumb" => 2,
															 "numb" => 2,
															 "name1" => "当前申请变更的合同",
															 "title1" => "当前申请变更的合同",
															 "url1" => "index1.php?model=contract_change_change&action=showChangeAndNew&id=$_GET[id]",
															 "name2" => "变更前的合同",
															 "title2" => "变更前的合同",
															 "url2" => "index1.php?model=contract_change_change&action=showChangeAndOld&id=$_GET[id]" )
															 )
							 );
		$rows = $this->service->initChange($_GET['id'],'old');
		$rowsT = $rows['change'];
		unset($rows['change']);
		$this->assignFunc($rowsT);

		$this->assignFunc($rows);

		$this->assign ( 'invoiceType', $this->getDataNameByCode($rows['invoiceType']) );
		$this->assign ( 'customerType', $this->getDataNameByCode($rows['customerType']) );
		$this->display ('readnewinex' );
	}
}
?>
