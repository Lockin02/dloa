<?php
/**
 * @author show
 * @Date 2013��11��25�� 10:56:46
 * @version 1.0
 * @description:(��license)���ñ�ע��Ϣ Model��
 */
class model_yxlicense_license_categorytips extends model_base {

	function __construct() {
		$this->tbl_name = "oa_license_category_tips";
		$this->sql_map = "yxlicense/license/categorytipsSql.php";
		parent :: __construct();
	}	
	
	/**
	 * ������ı�ע�����ú�tips��Ϣ
	 */
	function tips_d($object){		
		$formId = $object['formId'];
		unset($object['formId']);

		foreach ($object as $key => $value){
			if($object[$key]['isDisable']!=null){
				$object[$key]['isDisable'] = 1;
			}
			else {
				$object[$key]['isDisable'] = 0;
			}
		}
		try{
			$this->start_d();
			
			$object = util_arrayUtil::setArrayFn(array('formId' => $formId),$object);
			$this->saveDelBatch($object);
									
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * ��ȡtips���������,ͨ��titleId,optionId�õ���ע�������Ϣ
	 */
	function getTipsData_d($formId){
		$sql = "select titleId,optionId,tips,isDisable from ".$this->tbl_name." where formId = ".$formId;
		$data = $this->_db->getArray($sql);
		$newdata = array();
	
		foreach ($data as $key=> $value){
			if(!isset($newdata[$value['titleId']])){
				$newdata[$value['titleId']] = array();
			}
			$newdata[$value['titleId']][$value['optionId']] = array(
				'tips' => $value['tips'] ,
				'isDisable' => $value['isDisable']
			);
		}
		return $newdata;
	}
}