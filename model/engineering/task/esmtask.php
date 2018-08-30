<?php
/*
 * Created on 2010-12-2
 * 项目任务
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_engineering_task_esmtask extends model_base{
	function __construct() {
		$this->tbl_name = "oa_esm_task";
		$this->sql_map = "engineering/task/esmtaskSql.php";
		parent::__construct ();


//		$this->datadictDao = new model_system_datadict_datadict ();
//		$this->statusDao = new model_common_status ();
//		$this->statusDao->status = array (array ("statusEName" => "done", "statusCName" => "已完成", "key" => "1" ), array ("statusEName" => "doing", "statusCName" => "正执行", "key" => "2" )
//		, array ("statusEName" => "undo", "statusCName" => "未执行", "key" => "3" ), array ("statusEName" => "audit", "statusCName" => "待审核", "key" => "4" ) );

	}

	/**
	 * @desription 已完成任务列表获取数据方法

	 */
	function getdoneinfo() {
		$this->searchArr['status'] = "TG";
		$arr = $this->pageBySqlId ( 'esmtaskInfo' );
		return $arr;
	}
	/**
	 * @desription 在执行任务列表获取数据方法

	 */
	function getdoinginfo () {


		$this->searchArr['status'] = "WTG";
		$this->searchArr['status2'] = "JXZ";
		$arr = $this->pageBySqlId ( 'esmtaskInfo' );
		return $arr;
	}
	/**
	 * @desription 未执行任务列表获取数据方法

	 */
	function getundoinfo () {
		$this->searchArr['status'] = "WQD";
		$arr = $this->pageBySqlId ( 'esmtaskInfo' );
		return $arr;
	}
	/**
	 * @desription 待审核任务列表获取数据方法

	 */
	function getauditinfo () {
		$this->searchArr['status'] = "DSH";
		$arr = $this->pageBySqlId ( 'esmtaskInfo' );
		return $arr;
	}
 }
?>
