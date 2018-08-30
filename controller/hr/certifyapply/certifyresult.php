<?php

/**
 * @author Show
 * @Date 2012年8月28日 星期二 11:17:10
 * @version 1.0
 * @description:任职资格认证评价结果审核表控制层
 */
class controller_hr_certifyapply_certifyresult extends controller_base_action {

	function __construct() {
		$this->objName = "certifyresult";
		$this->objPath = "hr_certifyapply";
		parent :: __construct();
	}

	/****************** 列表 ***************************/

	/**
	 * 跳转到任职资格认证评价结果审核表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 个人列表
	 */
	function c_myList(){
		$this->view('listmy');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_REQUEST['formUserId'] = $_SESSION['USER_ID'];
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/********************  增删改查 ************************/

	/**
	 * 跳转到新增任职资格认证评价结果审核表页面
	 */
	function c_toAdd() {
		$assessIds = $_GET['assessIds'];
		//取出其中一条数据
		$assessArr = $this->service->getOneAssess_d($assessIds);
		$this->assignFunc($assessArr);

		//渲染所选单据
		$this->assign('assessIds',$assessIds);

		//其他信息渲染
		$this->assign('thisUserName',$_SESSION['USERNAME']);
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$this->assign('thisDate',day_date);

		//渲染从表内容
		$tbodyArr = $this->service->initBodyAdd_v($assessIds);
		$this->assign('tbody',$tbodyArr[0]);
		$this->assign('countNum',$tbodyArr[1]);

		$this->view('add');
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName]);
		if ($id) {
			if($_GET['act']){
				succ_show('controller/hr/certifyapply/ewf_certifyresult.php?actTo=ewfSelect&billId=' . $id );
			}else{
				msgRf('保存成功');
			}
		}
	}

	/**
	 * 跳转到编辑任职资格认证评价结果审核表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		//渲染从表内容
		$tbodyArr = $this->service->initBodyEdit_v($_GET['id']);
		$this->assign('tbody',$tbodyArr[0]);
		$this->assign('countNum',$tbodyArr[1]);

		//单据状态
		$this->assign('status',$this->service->rtStatus_c($obj['status']));

		$this->view('edit');
	}

	/**
	 * 新增对象操作
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		$rs = $this->service->edit_d ( $object );
		if ($rs) {
			if($_GET['act']){
				succ_show('controller/hr/certifyapply/ewf_certifyresult.php?actTo=ewfSelect&billId=' . $object['id'] );
			}else{
				msgRf('保存成功');
			}
		}
	}

	/**
	 * 跳转到查看任职资格认证评价结果审核表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//渲染从表内容
		$tbody = $this->service->initBodyView_v($_GET['id']);
		$this->assign('tbody',$tbody);

		//单据状态
		$this->assign('status',$this->service->rtStatus_c($obj['status']));

		$this->display('view');
	}

	/**
	 * 跳转到查看任职资格认证评价结果审核表页面
	 */
	function c_toAudit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//渲染从表内容
		$tbody = $this->service->initBodyView_v($_GET['id']);
		$this->assign('tbody',$tbody);

		//单据状态
		$this->assign('status',$this->service->rtStatus_c($obj['status']));

		$this->display('audit');
	}

	/******************** 业务逻辑 ********************/
	/**
	 * 审批完成后回调方法
	 */
	function c_dealAfterAudit(){
       	$this->service->dealAfterAudit_d( $_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}
}
?>