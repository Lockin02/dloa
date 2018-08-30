<?php
/**
 * @author Administrator
 * @Date 2012-07-19 11:15:45
 * @version 1.0
 * @description:职位申请表-家庭成员 Model层
 */
 class model_hr_recruitment_family  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_employment_family";
		$this->sql_map = "hr/recruitment/familySql.php";
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