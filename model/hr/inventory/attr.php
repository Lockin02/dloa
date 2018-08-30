<?php
/**
 * @author zengq
 * @Date 2012��8��20�� 17:03:17
 * @version 1.0
 * @description:�̵����->���Թ��� Model��
 */
class model_hr_inventory_attr  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_attr";
		$this->sql_map = "hr/inventory/attrSql.php";
		parent::__construct ();
	}
	/**
	 * ��д��������
	 */
		function add_d($object){
		try{
			$this->start_d();
			$attrId=parent::add_d($object,true);
			if(!empty($object['attrvals'])){
			//������������ֵ�ֶ�
			$attrvalDao = new model_hr_inventory_attrval();
			$attrvalDao->createBatch($object['attrvals'],array(
					'attrId'=>$attrId
				),'valName');
			}
			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	/**
	 * ��д�༭����
	 */
		function edit_d($obj){
		try{
			$this->start_d();
			parent :: edit_d($obj, true);
			$attrId = $obj['id'];
			$attrvalDao = new model_hr_inventory_attrval();
			$mainArr=array("attrId"=>$obj ['id']);
			$itemsArr=util_arrayUtil::setArrayFn($mainArr,$obj ['attrvals']);
			$attrvalDao->saveDelBatch($itemsArr);
			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(Exception $e){
			return false;
		}
	}
}
?>
