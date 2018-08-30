<?php
/**
 * @author Show
 * @Date 2013��7��1�� ������ 14:18:47
 * @version 1.0
 */
class model_flights_balance_bill  extends model_base {
	function __construct() {
		$this->tbl_name = "oa_flights_balance_bill";
		$this->sql_map = "flights/balance/billSql.php";
		parent::__construct ();
	}

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'billType'
    );

	/**
	 * �鿴����
	 *
	 * @param  $billCode
	 */
	function getBillInfo_d($mainId){
		return $this->find(array('mainId' => $mainId));
	}

	/**
	 * ������Ʊ
	 * @param  $object
	 * @param string $isAddInfo
	 * @return Ambigous <boolean, number>
	 */
	function add_d($object) {
		try{
			$this->start_d();
			$object = $this->processDatadict($object);

			$newId = parent::add_d( $object ,true);

			//���������ƺ�Id
			$this->updateObjWithFile($newId);

			//ʵ��������
			$balanceDao = new model_flights_balance_balance();
			//���ø��·�����������ķ�Ʊ����ֶ�
			$balanceDao -> updateBill_d($object['mainId'],$object['billCode']);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * ���������޸Ķ���
	 */
	function edit_d($object) {
		try{
			$this->start_d();
			$object = $this->processDatadict($object);

			parent::edit_d( $object ,true);

			//ʵ��������
			$balanceDao = new model_flights_balance_balance();
			//���ø��·�����������ķ�Ʊ����ֶ�
			$balanceDao -> updateBill_d($object['mainId'],$object['billCode']);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}
}
?>