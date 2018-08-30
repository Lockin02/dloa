<?php
/**
 * @author Show
 * @Date 2011年12月14日 星期三 10:00:57
 * @version 1.0
 * @description:周报(oa_esm_weeklog)控制层
 */
class controller_engineering_worklog_esmweeklog extends controller_base_action {

	function __construct() {
		$this->objName = "esmweeklog";
		$this->objPath = "engineering_worklog";
		parent::__construct ();
	}

	/*
	 * 跳转到周报Tab
	 */
	function c_page() {
		$this->view ( 'listTab' );
	}

	/*
	 * 跳转到周报(oa_esm_weeklog)
	*/
	function c_pageTab() {

		//加入权限
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$deptLimit = $sysLimit['部门权限'];
		$deptIds = null;
		if(!strstr($deptLimit,';;')){
			if(empty($deptLimit)){
				$deptIds = $_SESSION['DEPT_ID'];
			}else{
				$deptIds = $deptLimit.','.$_SESSION['DEPT_ID'];
			}
		}
		$this->assign('deptIds',$deptIds);
		$this->view ( 'list' );
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->assign ( 'subStatus', $this->service->rtSubStatus ( $obj ['subStatus'] ) );
			$this->show->assign ( "exaResults", $this->getDataNameByCode ( $obj ['exaResults'] ) );

	        //考核指标部分
	        $indexInfo = $this->service->initAuditIndexView_d($obj);
	        $this->assign('indexTbl',$indexInfo);

			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * 根据传入的信息获取工作日志
	 */
	function c_myWeeklog() {
		$this->view ( 'mylist' );
	}

	/**
	 *
	 * 个人工作日志tab页
	 */
	function c_myWeeklogTab() {
		$obj = $this->service->get_d ( $_GET ['id'] );

		$this->view ( 'mylist-tab' );
	}

	/**
	 * 个人周报方法
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];

		//$service->asc = false;
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
	 *
	 * 跳转到提交周报页面
	 */
	function c_toSubmitLog() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( "submit" );
	}

	/**
	 *
	 * 提交周报
	 */
	function c_submitLog() {
		$service = $this->service;
		$object = $_POST [$this->objName];
		if ($service->submitLog_d ( $object)) {
			echo "<script>alert('提交成功!');window.opener.window.show_page();window.close();</script>";
		} else {
			echo "<script>alert('提交失败!');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 *
	 * 确认审核日志
	 */
	function c_toAuditLog() {
		$this->assign ( "loginId", $_SESSION ['USER_ID'] );
		$this->view ( "audit-list" );
	}

	/**
	 * 确认审核周报页面
	 */
	function c_toAudit() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('thisDate',day_date);
		$this->showDatadicts ( array ('exaResults' => 'ZBKHJZ' ) );

        //考核指标部分
        $indexInfo = $this->service->initAuditIndex_d($obj);
        $this->assign('indexTbl',$indexInfo);

		$this->view ( "audit" );
	}

	/**
	 *
	 * 确认审核日志
	 */
	function c_auditLog() {
		$service = $this->service;
		$object = $_POST [$this->objName];
		if ($service->auditLog_d ( $object,true )) {
			echo "<script>alert('考核成功!');window.opener.window.show_page();window.close();</script>";
		} else {
			echo "<script>alert('考核失败!');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * 直接打回周报
	 */
	function c_backLog(){
		$service = $this->service;
		$id = $_POST['id'];
		if ($service->backLog_d ( $id )) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 判断周报是否可继续录入日志
	 */
	function c_checkLogCanWrite(){
        $executionDate = $_POST ['executionDate'];
        $this->service->searchArr = array( 'worklogDate' =>  $executionDate ,'createId' => $_SESSION['USER_ID'] , 'subStatus' => 'YQR');
        $rs = $this->service->list_d();
        if ($rs)
            echo 0; //存在已确认周报 不可录
        else
            echo 1; //可录
        exit();
	}

	/**
	 * 跳转到excel导出页面
	 */
	function c_toExportExcel(){
		$this->assignFunc($_GET);
		$this->view('outexcel');
	}
	/**
	 * excel导出
	 */
	function c_exportExcel(){
		set_time_limit(0);
		$rows = $this->service->getExcelInfo_d($_POST[$this->objName]);
		model_engineering_util_esmexcelutil::exportWeeklogInfo($rows);
	}

	/******************** 外包部分
	/**
	 * 外包人员周报 - tab
	 */
	function c_toSuppUserLogTab(){
		$this->view ( 'suppuserlog-tab' );
	}

	/**
	 * 外包人员周报
	 */
	function c_toSuppUserLog(){
		$this->view ( 'suppuserlog-list' );
	}

	/**
	 * 外包人员周报
	 */
	function c_suppUserLogJson(){
		$service = $this->service;

		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$personnelDao = new model_outsourcing_supplier_personnel();
		$userStr = $personnelDao->getPersonIdList($_SESSION ['USER_ID']);
		$service->searchArr ['createIdArr'] = $userStr;

		//$service->asc = false;
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
}