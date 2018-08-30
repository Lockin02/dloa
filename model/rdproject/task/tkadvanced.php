<?php
/**
 * @description: 项目任务高级信息Model
 * @date 2010-9-14 下午02:49:24
 * @author huangzf
 * @version V1.0
 */
class model_rdproject_task_tkadvanced extends model_base {

	/**
	 * @desription 构造函数
	 * @author huangzf
	 * @date 2010-9-15 下午02:50:04
	 * @version V1.0
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_task_advanced";
		$this->sql_map = "rdproject/task/tkadvancedSql.php";
		parent::__construct ();
	}

	/* ---------------------------------页面模板显示调用------------------------------------------*/



	/* -----------------------------------业务接口调用-------------------------------------------*/
	/*
	 * 通过关联项目任务id查找任务高级信息
	 */
	function getTkAdByPTId($tkBaseId){
		//return parent::echoSelect();
		$searchArr=array(
			"projectTaskId"=>$tkBaseId
		);
		$this->searchArr=$searchArr;
		$tkadvanceds=$this->listBySqlId();
		return $tkadvanceds[0];
	}

}
?>
