<?php

class model_yxlicense_license_categoryform extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_license_category_form";
		$this->sql_map = "yxlicense/license/categoryformSql.php";
		parent::__construct ();
	}
	
	/**
	 * ��дadd
	 */
	function add_d($object){
		//�޳������޹���Ϣ
		$titles = $object['titles'];
		$options = $object['options'];
		unset($object['titles']);
		unset($object['options']);

		try{
			$this->start_d();
			$newId = parent::add_d ($object, true);
			$categoryDao = new model_yxlicense_license_categorytitle();
			$titles = util_arrayUtil :: setArrayFn(array ('formId' => $newId ), $titles);

			if ($titles) {
				$categoryDao->saveDelBatch($titles);
			}
			$this->commit_d();
			$categoryDao = new model_yxlicense_license_categoryoptions();
			$options = util_arrayUtil :: setArrayFn(array ('formId' => $newId),$options );
			//var_dump($options);exit();
			if ($options) {
				$categoryDao->saveDelBatch($options);
			}
			$this->commit_d();
			return $newId;
		}catch(Exception $e){
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}
	

		/**
	 * ��дedit_d
	 */
	function edit_d($object) {
		//�޳������޹���Ϣ
 		$items = $object['titles'];
		$options = $object['options'];
		if(!isset($object['isHideTitle'])){
			$object['isHideTitle'] = '0';
		}
//		print_r($object);die();
 		unset ($object['titles']);
		unset ($object['options']);
		try {
			$this->start_d();
			//���ø���༭
			parent :: edit_d($object, true);

			//ʵ�����ӱ�
			$categoryDao = new model_yxlicense_license_categorytitle();
			$items = util_arrayUtil :: setArrayFn(array ('formId' => $object['id']), $items );
			if ($items) {
				$categoryDao->saveDelBatch($items);
			}
			$this->commit_d();
			
			$categoryDao = new model_yxlicense_license_categoryoptions();
			$options = util_arrayUtil :: setArrayFn(array ('formId' => $object['id']),$options );
			//var_dump($options);exit();
			if ($options) {
				$categoryDao->saveDelBatch($options);
			}
			$this->commit_d();
			
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}
	
	/**
	 * ��ȡform���Ӧ����
	 */
	function getFormData_d($obj){
		$arr = array();
		$sql = "select id,itemId,formName,isHideTitle from ".$this->tbl_name." where itemId = '$obj' order by id asc";
		$data = $this->_db->getArray($sql);
		return $data;
	}
	/**
	 * ��ȡform���ӦID��������
	 */	
	function getFormNum_d($id){
		$arr = array();
		$sql = "select count(id) from ".$this->tbl_name." where itemId = '$id'";
		$data = $this->_db->getArray($sql);
		return $data;
	}

	
}
?>