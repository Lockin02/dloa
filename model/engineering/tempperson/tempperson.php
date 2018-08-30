<?php

/**
 * @author Show
 * @Date 2012��7��17�� ���ڶ� 19:13:43
 * @version 1.0
 * @description:��Ƹ��Ա�� Model��
 */
class model_engineering_tempperson_tempperson extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_tempperson";
		$this->sql_map = "engineering/tempperson/temppersonSql.php";
		parent :: __construct();
	}

	/***************** ��ɾ�Ĳ� ******************/
	/**
	 * ��дadd_d
	 */
	function add_d($object){
		return parent::add_d($object,true);
	}

	/**
	 * ��дedit_d
	 */
	function edit_d($object){
		return parent::edit_d($object,true);
	}



	/************************** �߼���Ϣ ********************/
	/**
	 * ���²��Կ��ۼƽ��
	 */
	function updateAllMoney_d($id,$allMoney,$allDays){
		try{
			$object = array(
				'id' => $id,
				'allMoney' => $allMoney,
				'allDays' => $allDays
			);
			$this->edit_d($object);

			return true;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}
}
?>