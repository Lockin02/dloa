<?php
/**
 * @author Administrator
 * @Date 2012��1��11�� 16:58:32
 * @version 1.0
 * @description:������Ա Model��
 */
 class model_supplierManage_assessment_assessmentmenber  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_suppasses_menber";
		$this->sql_map = "supplierManage/assessment/assessmentmenberSql.php";
		parent::__construct ();
	}

	/**
	 * ��������Id����ȡ������Ա��Ϣ
	 *
	 */
	 function getMenberByParentId($parentId){
	 	return $this->findAll(array('parentId'=>$parentId));
	 }
}
?>