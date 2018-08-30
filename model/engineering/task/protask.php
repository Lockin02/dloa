<?php
/*
 * Created on 2010-12-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 项目任务 model
 */
 class model_engineering_task_protask extends model_base {
	public $db;

	function __construct(){
		$this->tbl_name = "oa_esm_task";
		$this->sql_map = "engineering/task/protaskSql.php";
		parent::__construct();
	}
    function showlist($rows)
    {
		return '';
	}
      /*
       * 发布任务,把任务状态改为WQD
      */
	function publishTask_d($id, $userId, $userName) {

		//$sql;
		$publishTask ['id'] = $id;
		$publishTask ['publishId'] = $userId;
		$publishTask ['publishName'] = $userName;
		$publishTask ['status'] = 'WQD';

		if (parent::updateById ( $publishTask ))
		{
			$tkinfo=$this->get_d($id);

			return "任务发布成功!";
		}
		else
			return "任务发布失败!";
	}
	  /*
       * 提交任务,把任务状态改为DSH
       */
	function putTask_d($id, $userId, $userName) {

		//$sql;
		$putTask ['id'] = $id;
		$putTask ['publishId'] = $userId;
		$putTask ['publishName'] = $userName;
		$putTask ['status'] = 'DSH';

		if (parent::updateById ( $putTask ))
		{
			$tkinfo=$this->get_d($id);

			return "提交审核成功!";
		}
		else
			return "提交审核失败!";
	}
 }
?>

