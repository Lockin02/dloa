<?php
/**
 * @author Administrator
 * @Date 2011年3月4日 14:56:13
 * @version 1.0
 * @description:商机跟踪人 Model层
 */
 class model_projectmanagent_chancetracker_chancetracker  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_trackman";
		$this->sql_map = "projectmanagent/chancetracker/chancetrackerSql.php";
		parent::__construct ();
	}


	/**
	 * 根据项目id获取产品列表
	 */
	function getDetail_d($chanceId) {
		$this->searchArr['chanceId'] = $chanceId;
		return $this->list_d();
	}
 }
?>