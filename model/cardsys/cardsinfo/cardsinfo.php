<?php

/**
 * @author Show
 * @Date 2012��1��5�� ������ 10:00:48
 * @version 1.0
 * @description:���Կ���Ϣ(oa_cardsys_cardsinfo) Model��
 */
class model_cardsys_cardsinfo_cardsinfo extends model_base {

	function __construct() {
		$this->tbl_name = "oa_cardsys_cardsinfo";
		$this->sql_map = "cardsys/cardsinfo/cardsinfoSql.php";
		parent :: __construct();
	}

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'cardType'
    );

	/************************** ��ɾ�Ĳ� ********************/
	/**
	 * ��дadd
	 */
	function add_d($object){
		$object = $this->processDatadict($object);

		return parent::add_d($object,true);
	}

	/**
	 * ��дedit
	 */
	function edit_d($object){
		$object = $this->processDatadict($object);
		return parent::edit_d($object,true);
	}
	/************************** �߼���Ϣ ********************/

	/**
	 * �ͷŲ��Կ�����
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
	 * ���²��Կ��ۼƽ��
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