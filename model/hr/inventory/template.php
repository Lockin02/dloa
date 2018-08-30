<?php
/**
 * @author chengl
 * @Date 2012年8月20日 17:03:17
 * @version 1.0
 * @description:盘点模板信息
 */
class model_hr_inventory_template  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_template";
		$this->sql_map = "hr/inventory/templateSql.php";
		parent::__construct ();
	}
	/**
	 * 重写新增方法
	 */
	function add_d($object){
		try{
			$this->start_d();
			$templateId=parent::add_d($object,true);
			if(!empty($object['attrvals'])){
			//处理属性属性值字段
			$attrvalDao = new model_hr_inventory_templateattr();
			$attrvalDao->createBatch($object['attrvals'],array(
					'templateId'=>$templateId
				),'attrName');
			}
			if(!empty($object['summary'])){
			//处理属性属性值字段
			$summaryDao = new model_hr_inventory_templatesummary();
			$summaryDao->createBatch($object['summary'],array(
					'templateId'=>$templateId
				),'question');
			}
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}
	/**
	 * 重写编辑方法
	 */
	function edit_d($obj){
		try{
			$this->start_d();
			parent :: edit_d($obj, true);
			$templateId = $obj['id'];
			$attrvalDao = new model_hr_inventory_templateattr();
			$summaryDao = new model_hr_inventory_templatesummary();
			$mainArr=array("templateId"=>$obj ['id']);
			$itemsArr=util_arrayUtil::setArrayFn($mainArr,$obj ['attrvals']);
			$attrvalDao->saveDelBatch($itemsArr);
			$itemsArr1=util_arrayUtil::setArrayFn($mainArr,$obj ['summary']);
			$summaryDao->saveDelBatch($itemsArr1);

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

}
?>
