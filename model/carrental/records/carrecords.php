<?php
/**
 * @author Show
 * @Date 2011年12月27日 星期二 19:07:53
 * @version 1.0
 * @description:用车记录(oa_carrental_records) Model层
 */
class model_carrental_records_carrecords extends model_base {

	function __construct() {
		$this->tbl_name = "oa_carrental_records";
		$this->sql_map = "carrental/records/carrecordsSql.php";
		parent::__construct ();
	}

    /***************** 增删改查 ***************************/

	//    /**
	//	 * 重写add_d方法，添加对象
	//	 */
	//
	//	function add_d($object){
	//		try{
	//			$this->start_d();
	//			$carrentalArr = $object['carrecordsdetail'];
	//			unset($object['carrecordsdetail']);
	//
	//			if (is_array ( $carrentalArr )) {
	//				//新增主表内容
	//				$id = parent::add_d($object,true);
	//
	//				//新增从表记录
	//				$itemsArr = $this->setItemMainId("recordsId", $id, $carrentalArr);
	//				$carrentalDao = new model_carrental_records_carrecordsdetail();
	//				$carrentalDao->saveDelBatch($itemsArr);
	//
	//				$this->commit_d();
	//				return $id;
	//
	//			} else {
	//				throw new Exception ( "单据信息不完整，请确认！" );
	//			}
	//		}catch(exception $e){
	//			$this->rollBack();
	//			return false;
	//		}
	//
	//	}


	/**
	 * 只新增自己数据的方法
	 */
	function addSelf_d($object) {
		return parent::add_d ( $object, true );
	}

	//	 /**
	//	 * 重写edit_d方法，根据主键修改对象
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
	//				//新增主表内容
	//				$editResult = parent::edit_d($object,true);
	//
	//				//新增从表记录
	//				$itemsArr = $this->setItemMainId("recordsId", $object['id'], $carrentalArr);
	//				$carrentalDao = new model_carrental_records_carrecordsdetail();
	//				$carrentalDao->saveDelBatch($itemsArr);
	//
	//				$this->commit_d();
	//				return $editResult;
	//
	//			} else {
	//				throw new Exception ( "单据信息不完整，请确认！" );
	//			}
	//		}catch(exception $e){
	//			$this->rollBack();
	//			return false;
	//		}
	//
	//	}


	/**
	 * 设置关联从表的id信息
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
		$allocation ['details'] = $items; //details被c层获取
		return $allocation;
	}
}
?>