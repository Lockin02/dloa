<?php
/*
 * Created on 2010-8-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * �����ͬ�����͹رյ������Ϣ
 */
class model_contract_common_bcinfo extends model_base{

	function __construct() {
		$this->tbl_name = "oa_contract_common_bcinfo";
		$this->sql_map = "contract/common/bcinfoSql.php";
		parent :: __construct();
	}

	/**
	 * ��Ӷ���
	 */
	function closeAdd($object) {
		try{
			$this->start_d();
			$this->create ( $object );
			$contract = new model_contract_sales_sales();
			$contract->contractClose2($object['contractId']);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���عر���Ϣ
	 */
	function getInfo($contractId,$getstyle = 0){
		if($getstyle == 0){
		}else{
			return $this->find(array( 'contractId' => $contractId ,'excuteType ' => '1'),null);
		}
	}
}
?>
