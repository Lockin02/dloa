<?php
class model_flights_ticketagencies_ticket extends model_base {
	//ģ�Ͳ�
	function __construct() {
		$this->tbl_name = "oa_flights_ticketagencies";
		$this->sql_map = "flights/ticketagencies/ticketSql.php";
		parent::__construct ();
	}
	function ergodic($object) {
		foreach ( $object as $key => $val ) {
			if (is_array ( $val )) {
				$object [$key] = join ( " , ", $val );
			}
		}
		return $object;
	}

	/**
	 * ����
	 */
	function add_d($object){
		try{
			$this->start_d();
			//���ݱ������
			$codeDao = new model_common_codeRule ();
			$object ['institutionId'] = $codeDao->commonCodeEasy ('��Ʊ����',$this->tbl_name,'DPJG');
			$newId = parent::add_d($object,true);

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}
}
?>