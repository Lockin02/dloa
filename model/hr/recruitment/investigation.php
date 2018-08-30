<?php
/**
 * @author Administrator
 * @Date 2012年8月18日 15:23:52
 * @version 1.0
 * @description:背景调查记录表 Model层
 */
class model_hr_recruitment_investigation  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitment_investigation";
		$this->sql_map = "hr/recruitment/investigationSql.php";
		parent::__construct ();
	}

	/*
	 * 重写add方法
	 */
	function add_d($obj){
		$obj['formCode']=date(YmdHis);
		$dictDao = new model_system_datadict_datadict();
		$obj['relationshipName'] = $dictDao->getDataNameByCode($obj['relationshipCode']);
		return parent::add_d($obj,true);
	}


	/*
	 * 重写edit方法
	 */
	function edit_d($object){
		$dictDao = new model_system_datadict_datadict();
		$object['relationshipName'] = $dictDao->getDataNameByCode($object['relationshipCode']);
		return parent::edit_d($object);
	}

}
?>