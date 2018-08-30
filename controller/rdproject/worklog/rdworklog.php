<?php
/*
 * Created on 2010-9-15
 * 日志
 */
class controller_rdproject_worklog_rdworklog extends controller_base_action{
	function __construct() {
		$this->objName = "rdworklog";
		$this->objPath = "rdproject_worklog";
		parent::__construct ();
	}
	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ('base_list');
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
	 * 重写新增
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('taskType' => 'XMRWLX' ) );
		$this->showDatadicts ( array ('priority' => 'XMRMYXJ' ) );
		$this->showDatadicts ( array ('status' => 'XMRWZT' ) );
		$this->assign('executionDate',date("Y-m-d"));
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 *  根据周志ID获取日志列表
	 */
	function c_logList(){
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
//		$service->searchArr['weekId'] = $_GET['id'];
		$projectId = isset($_GET['projectId']) ?  $_GET['projectId'] : null;

		if(!empty($projectId)){
			$service->searchArr['projectId'] = $projectId;
		}

		$service->sort = 'executionDate';
		$rows = $service->page_d ();
		$this->pageShowAssign();
		$this->show->assign ( 'weekId', $_GET['weekId'] );
		$this->show->assign ( 'projectId', $projectId );
		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * 我的工作日志
	 */
	function c_myLogList(){
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
//		$service->searchArr['weekId'] = $_GET['id'];

		if(isset($_GET['projectId'])){
			$service->searchArr['projectId'] = $_GET['projectId'];
		}

		$service->sort = 'executionDate';
		$rows = $service->page_d ();
		$this->pageShowAssign();
		$this->show->assign ( 'weekId', $_GET['weekId'] );
		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-mylist' );
	}

	/**
	 * 重写初始化
	 */
	function c_init(){
		$worklogrows = $this->service->get_d($_GET['id']);
		$object = $this->service->getDateInTask($worklogrows['taskId']);
		foreach ( $object as $key => $val ) {
			$this->show->assign ( "task".$key, $val );
		}
		foreach ( $worklogrows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('taskType' => 'XMRWLX' ) );
		$this->showDatadicts ( array ('priority' => 'XMRMYXJ' ) );
		$this->showDatadicts ( array ('status' => 'XMRWZT' ) );
		$this->show->assign ( 'id', $_GET['id'] );
		$this->show->display( $this->objPath .'_' .$this->objName .'-edit' );
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object)) {
			msg ( '编辑成功！' );
		}else{
			msg ( '编辑失败！' );
		}
	}

	/**
	 * 看向日志详细
	 */
	function c_viewWorkLog(){
		$object = $this->service->getBase ( $_GET ['id'] );

		$taskobject = $object['rdtask'];
		unset($object['rdtask']);
		foreach ( $object as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$taskobject['priority'] = $this->getDataNameByCode($taskobject['priority']);
		$taskobject['status'] = $this->getDataNameByCode($taskobject['status']);
		$taskobject['taskType'] = $this->getDataNameByCode($taskobject['taskType']);
		foreach ( $taskobject as $key => $val ) {
			$this->show->assign ( "task".$key, $val );
		}
//		print_r($taskobject);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}

	/**
	 * 看向日志详细
	 */
	function c_viewWorkLogByIdAndDate(){
		$condition = array ('taskId' => $_GET ['taskId'] , 'executionDate' => $_GET['executionDate'] ,'createId'=>$_GET['createId']);
		$rs = $this->service->findCount($condition);
		if($rs != 1){
			$this->c_worklogResultList($condition);
			exit();
		}

		$object = $this->service->getInfoByTaskAndDate ( $_GET ['taskId'],$_GET['executionDate'],$_GET['createId']);

		$taskobject = $object['rdtask'];
		unset($object['rdtask']);
		foreach ( $object as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$taskobject['priority'] = $this->getDataNameByCode($taskobject['priority']);
		$taskobject['status'] = $this->getDataNameByCode($taskobject['status']);
		$taskobject['taskType'] = $this->getDataNameByCode($taskobject['taskType']);
		foreach ( $taskobject as $key => $val ) {
			$this->show->assign ( "task".$key, $val );
		}
//		print_r($taskobject);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}

	/**
	 * 日志结果列表
	 */
	function c_worklogResultList($object){
		$this->assignFunc($object);
		$this->view('worklogresultlist');
	}

	/**
	 * 团队个人仪表盘
	 */
	function c_teamDashBoard(){
		$this->assign('member', $_SESSION['USERNAME']);
    	$this->assign('memberId', $_SESSION['USER_ID']);
    	$this->assign('flag' , 'flag');
		$this->show->display ( $this->objPath . '_' . $this->objName . '-dashboard' );
	}


	/**
	 * 个人仪表盘
	 */
	function c_dashBoard(){
		$this->assign('member', $_SESSION['USERNAME']);
    	$this->assign('memberId', $_SESSION['USER_ID']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-dashboard' );
	}

	/**
	 * 个人仪表盘-结果
	 */
	function c_dashBoradResult(){

		$service = $this->service;

		foreach ( $_GET as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$rows = $service->getDashResults ( $_GET );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}

		$this->show->display ( $this->objPath . '_' . $this->objName . '-dashboardresult' );
	}

	/**
	 * 个人仪表盘 - 工作量分布图
	 */
	function c_showLoadCharts(){
		$rows = $this->service->loadSpread($_GET['beginDate'],$_GET['overDate'],$_GET['memberId']);
		$this->show->assign( 'result',$this->service->showChartsLoadSpead($rows,'项目工作量分布图','无项目任务')  );
		$this->show->display( $this->objPath .'_' .$this->objName .'-planChartsView' );
	}

	/**
	 * 个人仪表盘 - 任务工作量分布图
	 */
	function c_showLoadByTask(){
		$rows = $this->service->loadSpreadByTask($_GET['beginDate'],$_GET['overDate'],$_GET['memberId']);
		$this->show->assign( 'result',$this->service->showChartsLoadSpead($rows,'任务工作量分布图')  );
		$this->show->display( $this->objPath .'_' .$this->objName .'-planChartsView' );
	}

	/**
	 * 根据ID串获取日志列表
	 */
	function c_dashDetail(){
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$service->searchArr['ids'] = $_GET['ids'];

		$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showlistInWindow ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-dashdetail' );
	}

	/**
	 * 添加一个日志在任务列表中
	 */
	function c_addInTask(){
		$object = $this->service->getDateInTask($_GET['id']);
//		print_r($object);
		foreach ( $object as $key => $val ) {
			$this->show->assign ( "task".$key, $val );
		}
		$this->showDatadicts ( array ('taskType' => 'XMRWLX' ) );
		$this->showDatadicts ( array ('priority' => 'XMRMYXJ' ) );
		$this->showDatadicts ( array ('status' => 'XMRWZT' ) );
		$this->show->display( $this->objPath .'_' .$this->objName .'-addintask' );
	}

	/**
	 * 任务详细-执行日报
	 */
	function c_worklogInTask(){
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息

		$rows = $service->worklogInTask ($_GET['id']);
		$this->pageShowAssign();

		$this->show->assign ( 'taskId', $_GET['id'] );
		$this->show->assign ( 'jsUrl', $_GET['jsUrl'] );
		$this->show->assign ( 'list', $service->showLogInTask ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-worklogInTask' );
	}

	/**
	 * 人员投入工作量透视图-图表
	 */
	function c_worklogCharts(){
		$rows = $this->service->getWorklogByUserGPid($_GET['pjId']);
		$this->show->assign( 'result',$this->service->worklogCharts($rows)  );
		$this->show->display( $this->objPath .'_' .$this->objName .'-planChartsView' );
	}

	/**
	 * 人员工作量透视图-表格
	 */
	function c_worklogTable(){
		$all = $this->service->getAllWorklogByPjId($_GET['pjId']);
		$rows = $this->service->getWorklogByUserGPid($_GET['pjId']);
		$this->show->assign( 'result',$this->service->worklogTable($rows,$all)  );
		$this->show->display( $this->objPath .'_' .$this->objName .'-planChartsView' );
	}

	/************项目组合统计人员投入工作量******By LiuB 2011年7月27日9:26:31********begin*********/

	/**
	 * 人员投入工作量透视图-图表
	 */
	function c_GroupWorklogCharts(){
		$rdNum = $this->service->getGroup_d ($_GET['gpId']);
       	 $arr = array();
			foreach ( $rdNum as $key => $val){
			$arr[$key] = $val['id'];

			}
	         $arr = implode(",",$arr);
	         if(empty ($arr)){
	            $rows = $arr;
	         }else
		       $rows = $this->service->GroupWorklogByUserGPid($arr);
		$this->show->assign( 'result',$this->service->worklogCharts($rows)  );
		$this->show->display( $this->objPath .'_' .$this->objName .'-planChartsView' );
	}

	/**
	 * 人员工作量透视图-表格
	 */
	function c_GroupWorklogTable(){
		$rdNum = $this->service->getGroup_d ($_GET['gpId']);
       	 $arr = array();
			foreach ( $rdNum as $key => $val){
			$arr[$key] = $val['id'];

			}
	         $arr = implode(",",$arr);
	         if(empty ($arr)){
	            $rows = $arr;
	         }else{
                $rows = $this->service->GroupWorklogByUserGPid($arr);
                $all = $this->service->GroupAllWorklogByPjId($arr);
                $rows = $this->service->GroupWorklogByUserGPid($arr);
	         }


		$this->show->assign( 'result',$this->service->worklogTable($rows,$all)  );
		$this->show->display( $this->objPath .'_' .$this->objName .'-planChartsView' );
	}
	/************项目组合统计人员投入工作量******By LiuB 2011年7月27日9:26:31*******end**********/

	/**
	 * 日志查询结果
	 */
	function c_resultList(){
		$this->assignFunc($_GET);
		$this->view('resultlist');
	}
}
?>
