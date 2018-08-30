<?php

/**
 * @author Show
 * @Date 2012年12月15日 星期六 15:21:37
 * @version 1.0
 * @description:项目变更人力预算 Model层
 */
class model_engineering_change_esmchangeper extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_change_person";
		$this->sql_map = "engineering/change/esmchangeperSql.php";
		parent :: __construct();
	}

	/**
	 * 获取变更任务的费用信息
	 */
	function getChangePerson_d($changeactId){
		$rs = $this->findAll(array('activityId' => $changeactId));
		return $rs;
	}
}
?>