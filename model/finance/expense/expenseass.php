<?php
/**
 * @author Show
 * @Date 2012��9��28�� ������ 11:13:27
 * @version 1.0
 * @description:������ϸ�� Model��
 */
class model_finance_expense_expenseass extends model_base {

	function __construct() {
		$this->tbl_name = "cost_detail_assistant";
		$this->sql_map = "finance/expense/expenseassSql.php";
		parent :: __construct();
	}

	//��дadd_d
	function add_d($object){
		//���ظ�����Ϣ
		$object = $this->addCreateInfo($object);
		$object['Place'] = '';
		$object['Note'] = '';

		return parent::add_d($object);
	}

	/**
	 * ���������޸Ķ���
	 */
	function editByAssId_d($object) {
		//idת��
		$object['id'] = $object['AssID'];
		unset($object['AssID']);
		//���ظ�����Ϣ
		$object = $this->addUpdateInfo($object);

		return $this->updateById ( $object );
	}

	/**
	 * ɾ�����ܱ�ʱ��ձ���Ϣ
	 */
	function clearBillNoInfo_d($BillNo){
		try{
			//����
			$this->update(array('BillNo' => $BillNo),array('BillNo' => '','Status' => '�༭'));

			return true;
		}catch(exception $e){
			echo $e->getMessage();
			throw $e;
			return false;
		}
	}

	/************************** ����ҵ���� ********************/
	/*
	 * Ϊ����Ķ����������ˣ����ʱ�䣬�޸��ˣ��޸�ʱ�䲢�����¶���һ��������Ӷ����ʱ��ʹ��
	 */
	function addCreateInfo($obj) {
		$obj ['Creator'] = $_SESSION ['USER_ID'];
		$obj ['CreateDT'] = date ( "Y-m-d H:i:s" );
		$obj ['Updator'] = $_SESSION ['USER_ID'];
		$obj ['UpdateDT'] = date ( "Y-m-d H:i:s" );
		return $obj;
	}

	/*
	 * Ϊ����Ķ�������޸��ˣ��޸�ʱ�䲢�����¶���һ�������޸Ķ����ʱ��ʹ��
	 */
	function addUpdateInfo($obj) {
		$obj ['Updator'] = $_SESSION ['USER_ID'];
		$obj ['UpdateDT'] = date ( "Y-m-d H:i:s" );
		return $obj;
	}
}
?>