<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:��Ŀ���������Model
 *
 */
 class model_rdproject_task_tkactuser extends model_base {


	function __construct() {
		$this->tbl_name = "oa_rd_task_act_user";
		$this->sql_map = "rdproject/task/tkactuserSql.php";
		parent::__construct ();
	}

	/* ---------------------------------ҳ��ģ����ʾ����------------------------------------------*/

	/* -----------------------------------ҵ��ӿڵ���-------------------------------------------*/

	/*
	 * �жϵ�ǰ��¼���Ƿ�������ĸ�����
	 */
	 function isCharegeUser($taskId,$userId){
		$condition=" taskId=".$taskId." and actUserId='".$userId."'";
		return $this->get_table_fields($this->tbl_name,$condition,"max(isActUser)");
	 }

	 /**
	  * ��������Id�����������˺Ͳ�����
	  */
	  function findUserByTaskId_d( $id ){
	  	$sql = "select distinct actUserId from " . $this->tbl_name . " where taskId=" . $id;
	  	$rows = $this->_db->getArray( $sql );
	  	foreach( $rows as $key => $val ){
	  		$actUserStr .= $rows[$key]['actUserId'] . ',';
	  	}
	  	return $actUserStr;
	  }

}
?>
