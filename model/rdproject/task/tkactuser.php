<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务参与人Model
 *
 */
 class model_rdproject_task_tkactuser extends model_base {


	function __construct() {
		$this->tbl_name = "oa_rd_task_act_user";
		$this->sql_map = "rdproject/task/tkactuserSql.php";
		parent::__construct ();
	}

	/* ---------------------------------页面模板显示调用------------------------------------------*/

	/* -----------------------------------业务接口调用-------------------------------------------*/

	/*
	 * 判断当前登录人是否是任务的负责人
	 */
	 function isCharegeUser($taskId,$userId){
		$condition=" taskId=".$taskId." and actUserId='".$userId."'";
		return $this->get_table_fields($this->tbl_name,$condition,"max(isActUser)");
	 }

	 /**
	  * 根据任务Id查找任务负责人和参与者
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
