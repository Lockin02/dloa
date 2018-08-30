<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务参与人Model
 *
 */
 class model_rdproject_task_tkaudituser extends model_base {


	function __construct() {
		$this->tbl_name = "oa_rd_task_audit_user";
		$this->sql_map = "rdproject/task/tkaudituserSql.php";
		parent::__construct ();
	}

	/* ---------------------------------页面模板显示调用------------------------------------------*/


	/* -----------------------------------业务接口调用-------------------------------------------*/
 	/*
 	 * 判断用户是否是任务的审核人
	 */
	 function isAuditUser($taskId,$userId){
		$condition=" taskId=".$taskId." and auditId='".$userId."'";
		return $this->get_table_fields($this->tbl_name,$condition,"id");
	 }

	/**
	 * 根据任务ID返回任务的审核人
	 */
	function getNameByTaskId($taskId){
		$this->searchArr['taskId'] = $taskId;
		return $this->listBySqlId('select_auditUser');
	}

}
?>
