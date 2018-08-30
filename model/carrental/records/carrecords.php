<?php
/**
 * @author Show
 * @Date 2011��12��27�� ���ڶ� 19:07:53
 * @version 1.0
 * @description:�ó���¼(oa_carrental_records) Model��
 */
class model_carrental_records_carrecords extends model_base {

	function __construct() {
		$this->tbl_name = "oa_carrental_records";
		$this->sql_map = "carrental/records/carrecordsSql.php";
		parent::__construct ();
	}

    /***************** ��ɾ�Ĳ� ***************************/

	//    /**
	//	 * ��дadd_d��������Ӷ���
	//	 */
	//
	//	function add_d($object){
	//		try{
	//			$this->start_d();
	//			$carrentalArr = $object['carrecordsdetail'];
	//			unset($object['carrecordsdetail']);
	//
	//			if (is_array ( $carrentalArr )) {
	//				//������������
	//				$id = parent::add_d($object,true);
	//
	//				//�����ӱ��¼
	//				$itemsArr = $this->setItemMainId("recordsId", $id, $carrentalArr);
	//				$carrentalDao = new model_carrental_records_carrecordsdetail();
	//				$carrentalDao->saveDelBatch($itemsArr);
	//
	//				$this->commit_d();
	//				return $id;
	//
	//			} else {
	//				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
	//			}
	//		}catch(exception $e){
	//			$this->rollBack();
	//			return false;
	//		}
	//
	//	}


	/**
	 * ֻ�����Լ����ݵķ���
	 */
	function addSelf_d($object) {
		return parent::add_d ( $object, true );
	}

	//	 /**
	//	 * ��дedit_d���������������޸Ķ���
	//	 */
	//
	//	function edit_d($object){
	//
	//		try{
	//			$this->start_d();
	//			$carrentalArr = $object['carrecordsdetail'];
	//			unset($object['carrecordsdetail']);
	//
	//			if (is_array ( $carrentalArr )) {
	//				//������������
	//				$editResult = parent::edit_d($object,true);
	//
	//				//�����ӱ��¼
	//				$itemsArr = $this->setItemMainId("recordsId", $object['id'], $carrentalArr);
	//				$carrentalDao = new model_carrental_records_carrecordsdetail();
	//				$carrentalDao->saveDelBatch($itemsArr);
	//
	//				$this->commit_d();
	//				return $editResult;
	//
	//			} else {
	//				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
	//			}
	//		}catch(exception $e){
	//			$this->rollBack();
	//			return false;
	//		}
	//
	//	}


	/**
	 * ���ù����ӱ��id��Ϣ
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ( $iteminfoArr as $key => $value ) {
			$value [$mainIdName] = $mainIdValue;
			array_push ( $resultArr, $value );
		}
		return $resultArr;
	}
	function get_d($id) {
		$allocationitemDao = new model_carrental_records_carrecordsdetail ();
		$allocationitemDao->searchArr ['recordsId'] = $id;
		$items = $allocationitemDao->listBySqlId ();
		$allocation = parent::get_d ( $id );
		$allocation ['details'] = $items; //details��c���ȡ
		return $allocation;
	}
}
?>