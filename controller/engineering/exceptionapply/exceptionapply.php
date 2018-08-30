<?php

/**
 * @author Show
 * @Date 2012年8月2日 星期四 19:35:41
 * @version 1.0
 * @description:工程超权限申请控制层
 */
class controller_engineering_exceptionapply_exceptionapply extends controller_base_action {

	function __construct() {
		$this->objName = "exceptionapply";
		$this->objPath = "engineering_exceptionapply";
		parent :: __construct();
	}

	/***************** 列表部分 ************************/

	/**
	 * 跳转到工程超权限申请列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 我的tab页
	 */
	function c_myTab(){
		$this->display('mytab');
	}

	/**
	 * 我的申请列表
	 */
	function c_myList(){
		$this->view('mylist');
	}

	/**
	 * 我的列表数据
	 */
	function c_myPageJson(){
		$service = $this->service;

		$_REQUEST['createId'] = $_SESSION['USER_ID'];
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

	/**
	 * 待审核申请列表
	 */
	function c_auditList(){
		$this->assign('ExaUserId',$_SESSION['USER_ID']);
		$this->view('auditList');
	}

	/******************* 增删改查 ***********************/

	/**
	 * 跳转到新增工程超权限申请页面
	 */
	function c_toAdd() {
		$this->showDatadicts(array('rentalType' => 'GCZCXZ'),null,true); //租车类型
		$this->showDatadicts(array('useRange' => 'GCYCSQSYFW')); //适用范围
		//类型转换
		$this->assign('applyTypeCN' ,$this->getDataNameByCode($_GET['applyType']));
		$this->assign('applyType',$_GET['applyType']);

		//申请信息加载
		$this->assign('deptName',$_SESSION['DEPT_NAME']);
		$this->assign('deptId',$_SESSION['DEPT_ID']);
		$this->assign('applyUserId',$_SESSION['USER_ID']);
		$this->assign('applyUserName',$_SESSION['USERNAME']);
		$this->assign('applyDate',day_date);

		$this->view (  $this->service->getBusinessCode($_GET['applyType']).'-add' );
	}

	/**
	 * 跳转到编辑工程超权限申请页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('rentalType' => 'GCZCXZ'),$obj['rentalType']); //租车类型
		$this->showDatadicts(array('useRange' => 'GCYCSQSYFW'),$obj['useRange']); //适用范围

		$this->view (  $this->service->getBusinessCode($obj['applyType']).'-edit' );
	}

	/**
	 * 跳转到查看工程超权限申请页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view (  $this->service->getBusinessCode($obj['applyType']).'-view' );
	}

	/**
	 * 跳转到查看工程超权限申请页面
	 */
	function c_toAudit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('thisDate',day_date);
		$this->view (  $this->service->getBusinessCode($obj['applyType']).'-audit' );
	}

	/**
	 * 审核
	 */
	function c_audit(){
		$object = $_POST[$this->objName];
		if($this->service->audit_d($object)){
			msg('审核完成');
		}else{
			msg('审核失败');
		}
	}
}
?>