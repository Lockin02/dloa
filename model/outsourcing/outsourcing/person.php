<?php
/**
 * @author Administrator
 * @Date 2013年9月14日 15:51:51
 * @version 1.0
 * @description:人员租借详细 Model层
 */
 class model_outsourcing_outsourcing_person  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_person";
		$this->sql_map = "outsourcing/outsourcing/personSql.php";
		parent::__construct ();
	}

	/**
	 * 根据条件查找人员信息
	 *
	 */
	 function selectPersonnel_d($condition){
		$this->searchArr = $condition;
		$personnelRow= $this->listBySqlId ();
		return $personnelRow;
	 }
 }
?>