<?php
/**
 * @author jianjungki
 * @Date 2012��8��6�� 14:33:32
 * @version 1.0
 * @description:Ա��������Ŀ Model�� 
 */
 class model_hr_permanent_standard  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_permanent_standard";
		$this->sql_map = "hr/permanent/standardSql.php";
		parent::__construct ();
	}
	/**
	 * ��Ӷ���
	 */
	function add_d($object, $isAddInfo = false) {
		try{
			$this->start_d();
			$object['standardCode'] = "ST".date("YmdHis");
			$object['formDate'] = date("Y-m-d");
			$newId = parent::add_d($object,true);
			$this->commit_d();
		}catch(exception $e){
			$this->rollback();
			return null;
		}
		return $newId;
	}
 }
?>