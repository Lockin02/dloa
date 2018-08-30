<?php
/**
 * @author Administrator
 * @Date 2012年5月16日 星期三 14:12:39
 * @version 1.0
 * @description:工作日志控制层 
 */
class controller_produce_log_worklog extends controller_base_action {
	
	function __construct() {
		$this->objName = "worklog";
		$this->objPath = "produce_log";
		parent::__construct ();
	}
	
	/**
	 * 跳转到所有工作日志列表
	 */
	function c_pageall() {
		//var_dump($_SESSION);
		$this->assign ( "weekId", $_GET ['id'] );
		$this->view ( 'listall' );
	}
	
	/**
	 * 跳转到工作日志列表
	 */
	function c_page() {
		//var_dump($_SESSION);
		$this->assign ( "weekId", $_GET ['id'] );
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到任务执行日报列表
	 */
	function c_toTaskList() {
		//var_dump($_SESSION);
		$this->assign ( "taskId", $_GET ['taskId'] );
		$this->view ( 'task-list' );
	}
	
	/**
	 * 跳转到新增工作日志页面
	 */
	function c_toAdd() {
		$produceTaskCode = isset ( $_GET ['produceTaskCode'] ) ? $_GET ['produceTaskCode'] : "";
		$produceTaskId = isset ( $_GET ['produceTaskId'] ) ? $_GET ['produceTaskId'] : "";
		$this->assign ( 'produceTaskCode', $produceTaskCode );
		$this->assign ( 'produceTaskId', $produceTaskId );
		$service = $this->service;
		$service->searchArr = array ("createId" => $_SESSION ['USER_ID'], "produceTaskId" => $produceTaskId, "executionDate" => date ( 'Y-m-d' ) );
		$rows = $service->listBySqlId ();
		if (is_array ( $rows )) {
			echo "<script>alert('此任务今天的日志已填写!')</script>";
			$this->assign ( 'executionDate', "" );
		} else {
			$this->assign ( 'executionDate', date ( 'Y-m-d' ) );
		}
		
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑工作日志页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	
	/**
	 * 判断是否存在该日志
	 */
	
	function c_isExit() {
		if ($getinfo = $this->service->isExit ( $_SESSION ['USER_ID'], $_POST ['code'] ))
			echo util_jsonUtil::encode ( $getinfo );
		else {
			return FALSE;
		}
	}
	
	/**
	 * 跳转到查看工作日志页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	
	/**
	 * 增加工作日志资料
	 */
	function c_add($isAddInfo = false) {
		//验证时间合法性	
		if (strtotime ( $_POST [$this->objName] ['executionDate'] ) < strtotime ( date ( 'y-m-d' ) )) {
			msg ( "插入失败，执行时间不能早于今天" );
			return;
		}
		if (strtotime ( $_POST [$this->objName] ['planEndDate'] ) <= strtotime ( date ( 'y-m-d' ) )) {
			msg ( "插入失败，预期结束时间不能早于和等于今天" );
			return;
		}
		
		$newweek = new model_produce_log_weeklog ();
		
		//echo($newweek);
		if ($weekid = $newweek->isExit ( $_SESSION ['USER_ID'] ))
			$_POST [$this->objName] ['weekId'] = $weekid;
		else
			$_POST [$this->objName] ['weekId'] = $newweek->createweek ();
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			echo "<script>alert('保存成功！');parent.tb_remove();parent.location.reload();</script>";
		}
	
		//$this->listDataDict();
	}
	
	/**
	 * 
	 *检查判断是否存在某个工作任务的工作日志
	 */
	function c_checkExsitLog() {
		$this->service->searchArr = array ("createId" => $_SESSION ['USER_ID'], "executionDate" => $_POST ['executionDate'], "produceTaskId" => $_POST ['produceTaskId'] );
		$workLogArr = $this->service->listBySqlId ();
		if (is_array ( $workLogArr )) {
			echo $workLogArr [0] ['id'];
		} else {
			echo 0;
		}
	}
}
?>