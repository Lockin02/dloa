<?php
/**
 * @author Administrator
 * @Date 2012-07-19 11:15:13
 * @version 1.0
 * @description:ְλ�����-�������� Model��
 */
 class model_hr_recruitment_education  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_employment_education";
		$this->sql_map = "hr/recruitment/educationSql.php";
		parent::__construct ();
	}

	/**
	 * ����ְλ����ID��ȡ��Ϣ
	 *
	 */
	 function getInfoByParentId_d($parentId){
		$this->searchArr = array ('employmentId' => $parentId );
		$rows= $this->listBySqlId ( "select_default" );
		return $rows;
	 }
 }
?>