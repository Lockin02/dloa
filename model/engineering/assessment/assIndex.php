<?php
/*
 * Created on 2010-12-2
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_engineering_assessment_assIndex extends model_base{
	function __construct() {
		$this->tbl_name = "oa_esm_ass_index";
		$this->sql_map = "engineering/assessment/assIndexSql.php";
		parent::__construct ();
	}
//	/**
//	 * @desription 根据指标id获取配置选项
//	 * @param tags
//	 * @date 2010-12-8 上午09:51:18
//	 * @qiaolong
//	 */
//	function getConfigInfo ($id) {
//		$assConfigDao = new model_engineering_assessment_assConfig();
//		$assConfigDao->searchArr['parentId'] = $id;
//		return $assConfigDao->pageBySqlId('assConfigInfo');
//	}
}
?>
