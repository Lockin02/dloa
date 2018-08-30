<?php

/**
 * @author Show
 * @Date 2012年7月17日 星期二 19:13:43
 * @version 1.0
 * @description:临聘人员库 Model层
 */
class model_engineering_tempperson_tempperson extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_tempperson";
		$this->sql_map = "engineering/tempperson/temppersonSql.php";
		parent :: __construct();
	}

	/***************** 增删改查 ******************/
	/**
	 * 重写add_d
	 */
	function add_d($object){
		return parent::add_d($object,true);
	}

	/**
	 * 重写edit_d
	 */
	function edit_d($object){
		return parent::edit_d($object,true);
	}



	/************************** 逻辑信息 ********************/
	/**
	 * 更新测试卡累计金额
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