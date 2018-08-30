<?php
/*
 * Created on 2010-9-16
 * 周志
 */
class controller_rdproject_worklog_rdweeklog extends controller_base_action{
	function __construct() {
		$this->objName = "rdweeklog";
		$this->objPath = "rdproject_worklog";
		parent::__construct ();
	}

	/**
	 * 填报工作日志-所有日志
	 */
	function c_page() {
		global $func_limit; //获取当前登陆用户拥有函数的限制
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * 我的日志
	 */
	function c_myWeeklog(){
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息

		$service->searchArr['user_id'] = $_SESSION['USER_ID'];

		$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showmylist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-myweeklog' );
	}

	/**
	 * 查看周报-读写
	 */
	function c_read(){
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}

	/**
	 * 查看周报-只读
	 */
	function c_view(){
		$rows = $this->service->get_d ( $_GET ['id'] ,'view');
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}


	/**
	 * 查看工作日报中添加备注
	 */
	function c_addremark(){
		$rows = $this->service->getBase ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName .'-addremark');
	}

	/**
	 * 保存周志
	 */
	function c_editLog(){
		$object = $_POST [$this->objName];
		if ($this->service->editLog ( $object )) {
			showmsg ( '保存成功' );
		} else {
			showmsg ( '保存失败' );
		}
	}

	/**
	 * 查询日志
	 */
	function c_searchLog(){
		$this->show->display( $this->objPath . '_' .$this->objName . '-searchlog');
	}

	/**
	 * 查询日志结果显示页
	 */
	function c_searchlogresult(){
		$service = $this->service;
		$object = array();
		$_GET = array_filter($_GET);
		//在列表中添加值
		if(isset($_GET['w_projectId'])){
			$object['w_projectId'] = $_GET['w_projectId'];
		}
		//在列表中添加值
		if(isset($_GET['departmentIds'])){
			if($_GET['departmentIds'] == 'ALL_DEPT'){
				unset($_GET['departmentIds']);
			}
		}
		$service->getParam ( $_GET ); //设置前台获取的参数信息

		$rows = $service->getSearchList ();
//		$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showResultList ( $rows ,$object) );
		$this->show->display( $this->objPath . '_' .$this->objName . '-resultlist');
	}

	/**
	 * 查询日志结果显示页
	 */
	function c_searchworklogresult(){
		$this->assignFunc($_GET);
		$this->view('resultlist-worklog');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonWorklogResult() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d ('select_inner');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/************************** 查询日志  项目经理 部分 **************************/
	/**
	 * 查询日志
	 */
	function c_searchLogForManeger(){
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->show->display( $this->objPath . '_' .$this->objName . '-searchlogformanager');
	}

	/**
	 * 对于SQL中需要使用 in 的 值进行处理
	 */
	function dealStr($str){
		$str = substr($str,0,-1);
		$arr = explode(',',$str);
		$returnstr = null;
		foreach($arr as $val){
			$returnstr .= '"'.$val .'",';
		}
		$returnstr = substr($returnstr,0,-1);
		return $returnstr;
	}

	/**
	 * 日志报告生成
	 */
	function c_logReport(){
		$this->show->display( $this->objPath .'_' . $this->objName .'-logreport');
	}

	/**
	 * 日志管理下的查询日志
	 */
	function c_searchalong(){
		$this->show->display( $this->objPath . '_' .$this->objName .'-searchalong');
	}

	/**
	 * 下属日志
	 */
	function c_subordinateLog(){
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$rows = $service->getSubordinateLog();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display( $this->objPath .'_'.$this->objName .'-subordinatelog');
	}

	/**
	 * 我的项目-打开项目中显示周报
	 */
	function c_listInProject() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		$service = $this->service;
		$service->searchArr ['projectId'] = $_GET ['pjId'];
		if( isset($_GET ['createName']) ){
			$service->searchArr ['createName1'] = $_GET ['createName'];
		}
		if( isset($_GET['depName']) ){
			$service->searchArr ['depName1'] = $_GET['depName'];
		}
		$service->groupBy = 'w.id';
		$rows = $service->pageBySqlId('view_inproject');
		$this->pageShowAssign();

		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		$this->show->assign ( 'projectId',$pjId );
		$this->show->assign ( 'jsUrl',$_GET ['jsUrl'] );
		$this->show->assign ( 'list', $service->showResultList ( $rows,array('w_projectId' => $_GET ['pjId']) ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listinproject' );
	}
}
?>
