<?php
/**
 * @author Administrator
 * @Date 2012年1月11日 16:58:32
 * @version 1.0
 * @description:评估人员 Model层
 */
 class model_supplierManage_assessment_assessmentmenber  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_suppasses_menber";
		$this->sql_map = "supplierManage/assessment/assessmentmenberSql.php";
		parent::__construct ();
	}

	/**
	 * 根据评估Id，获取评估成员信息
	 *
	 */
	 function getMenberByParentId($parentId){
	 	return $this->findAll(array('parentId'=>$parentId));
	 }
}
?>