<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:��Ŀ���������Model
 *
 */
 class model_rdproject_task_tkaudituser extends model_base {


	function __construct() {
		$this->tbl_name = "oa_rd_task_audit_user";
		$this->sql_map = "rdproject/task/tkaudituserSql.php";
		parent::__construct ();
	}

	/* ---------------------------------ҳ��ģ����ʾ����------------------------------------------*/


	/* -----------------------------------ҵ��ӿڵ���-------------------------------------------*/
 	/*
 	 * �ж��û��Ƿ�������������
	 */
	 function isAuditUser($taskId,$userId){
		$condition=" taskId=".$taskId." and auditId='".$userId."'";
		return $this->get_table_fields($this->tbl_name,$condition,"id");
	 }

	/**
	 * ��������ID��������������
	 */
	function getNameByTaskId($taskId){
		$this->searchArr['taskId'] = $taskId;
		return $this->listBySqlId('select_auditUser');
	}

}
?>
