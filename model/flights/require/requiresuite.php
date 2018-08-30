<?php
/**
 * @author Administrator
 * @Date 2013��7��12�� 9:47:40
 * @version 1.0
 * @description:������Ա�� Model��
 */
class model_flights_require_requiresuite extends model_base {

	function __construct() {
		$this->tbl_name = "oa_flights_require_suite";
		$this->sql_map = "flights/require/requiresuiteSql.php";
		parent :: __construct();
	}

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'cardType','tourAgency','employeeType'
    );

	/**
	 * add_d
	 */
	function add_d($object){
		$object = $this->processDatadict($object);

		//����һ����Ч����
		if($object['cardType'] == 'JPZJLX-01'){
			$object['validDate'] = '0000-00-00';
			$object['birthDate'] = '0000-00-00';
		}

		return parent::add_d($object);
	}

	/**
	 * edit_d
	 */
	function edit_d($object){
		$object = $this->processDatadict($object);

		//����һ����Ч����
		if($object['cardType'] == 'JPZJLX-01'){
			$object['validDate'] = '0000-00-00';
			$object['birthDate'] = '0000-00-00';
		}

		return parent::edit_d($object);
	}
}
?>