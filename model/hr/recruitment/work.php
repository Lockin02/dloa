<?php
/**
 * @author Administrator
 * @Date 2012-07-19 11:15:08
 * @version 1.0
 * @description:职位申请表-工作经历 Model层
 */
 class model_hr_recruitment_work  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_employment_work";
		$this->sql_map = "hr/recruitment/workSql.php";
		parent::__construct ();
	}

	/**
	 * 根据职位申请ID获取信息
	 *
	 */
	 function getInfoByParentId_d($parentId){
		$this->searchArr = array ('employmentId' => $parentId );
		$rows= $this->listBySqlId ( "select_default" );
		return $rows;
	 }
 }
?>