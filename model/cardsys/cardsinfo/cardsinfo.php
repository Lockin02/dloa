<?php

/**
 * @author Show
 * @Date 2012年1月5日 星期四 10:00:48
 * @version 1.0
 * @description:测试卡信息(oa_cardsys_cardsinfo) Model层
 */
class model_cardsys_cardsinfo_cardsinfo extends model_base {

	function __construct() {
		$this->tbl_name = "oa_cardsys_cardsinfo";
		$this->sql_map = "cardsys/cardsinfo/cardsinfoSql.php";
		parent :: __construct();
	}

    //数据字典字段处理
    public $datadictFieldArr = array(
    	'cardType'
    );

	/************************** 增删改查 ********************/
	/**
	 * 重写add
	 */
	function add_d($object){
		$object = $this->processDatadict($object);

		return parent::add_d($object,true);
	}

	/**
	 * 重写edit
	 */
	function edit_d($object){
		$object = $this->processDatadict($object);
		return parent::edit_d($object,true);
	}
	/************************** 逻辑信息 ********************/

	/**
	 * 释放测试卡操作
	 */
	function releaseCar_d($obj) {
		for ($i = 0; $i < count($obj); $i++) {
			$obj[$i]['projectId'] = "";
			$obj[$i]['projectCode'] = "";
			$obj[$i]['projectName'] = "";
		}
		$newId = $this->saveDelBatch($obj);
		return $newId;
	}

	/**
	 * 更新测试卡累计金额
	 */
	function updateAllMoney_d($cardId,$allMoney){
		try{
			$object = array(
				'id' => $cardId,
				'allMoney' => $allMoney
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