<?php
/*
 * Created on 2010-12-2
 * 项目任务
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_engineering_pjtask_pjtask extends model_base{
	function __construct() {
		$this->tbl_name = "oa_esm_task";
		$this->sql_map = "engineering/pjtask/pjtaskSql.php";
		parent::__construct ();


//		$this->datadictDao = new model_system_datadict_datadict ();
//		$this->statusDao = new model_common_status ();
//		$this->statusDao->status = array (array ("statusEName" => "done", "statusCName" => "已完成", "key" => "1" ), array ("statusEName" => "doing", "statusCName" => "正执行", "key" => "2" )
//		, array ("statusEName" => "undo", "statusCName" => "未执行", "key" => "3" ), array ("statusEName" => "audit", "statusCName" => "待审核", "key" => "4" ) );

	}

	/**
	 * @desription 已完成任务列表获取数据方法
	 * @param tags
	 * @date 2010-12-2 下午03:39:11
	 * @qiaolong
	 */
	function getdoneinfo() {
		$this->searchArr['status'] = "已完成";
		$arr = $this->pageBySqlId ( 'pjtaskInfo' );
		return $arr;
	}
	/**
	 * @desription 在执行任务列表获取数据方法
	 * @param tags
	 * @date 2010-12-2 下午03:39:57
	 * @qiaolong
	 */
	function getdoinginfo () {
		$this->searchArr['status'] = "在执行";
		$arr = $this->pageBySqlId ( 'pjtaskInfo' );
		return $arr;
	}
	/**
	 * @desription 为执行任务列表获取数据方法
	 * @param tags
	 * @date 2010-12-2 下午03:41:11
	 * @qiaolong
	 */
	function getundoinfo () {
		$this->searchArr['status'] = "未执行";
		$arr = $this->pageBySqlId ( 'pjtaskInfo' );
		return $arr;
	}
	/**
	 * @desription 待审核任务列表获取数据方法
	 * @param tags
	 * @date 2010-12-2 下午03:41:47
	 * @qiaolong
	 */
	function getauditinfo () {
		$this->searchArr['status'] = "待审核";
		$arr = $this->pageBySqlId ( 'pjtaskInfo' );
		return $arr;
	}
 }
?>
