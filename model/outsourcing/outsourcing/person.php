<?php
/**
 * @author Administrator
 * @Date 2013��9��14�� 15:51:51
 * @version 1.0
 * @description:��Ա�����ϸ Model��
 */
 class model_outsourcing_outsourcing_person  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_person";
		$this->sql_map = "outsourcing/outsourcing/personSql.php";
		parent::__construct ();
	}

	/**
	 * ��������������Ա��Ϣ
	 *
	 */
	 function selectPersonnel_d($condition){
		$this->searchArr = $condition;
		$personnelRow= $this->listBySqlId ();
		return $personnelRow;
	 }
 }
?>