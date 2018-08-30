<?php
/**
 * @author Show
 * @Date 2013年8月2日 星期五 14:41:22
 * @version 1.0
 * @description:物料模板配置表 Model层 
 */
 class model_stock_template_protemplate  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_product_template";
		$this->sql_map = "stock/template/protemplateSql.php";
		parent::__construct ();
	}   

 	/**
	 * 重写add
	 */
	function add_d($object){
		//剔除主表无关信息
		$items = $object['items'];
		unset($object['items']);
		try{
			$this->start_d();
			//调用父类新增
			$newId = parent::add_d($object,true);
			//实例化从表
			$protemplateitemDao = new model_stock_template_protemplateitem();
			$items = util_arrayUtil::setArrayFn(array('mainId' => $newId),$items);
			$protemplateitemDao->saveDelBatch($items);
			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}
	
	/**
	 * 重写edit_d
	 */
	function edit_d($object){
		//剔除主表无关信息
		$items = $object['items'];
		unset($object['items']);	
		try{
			$this->start_d();
			//调用父类编辑
			parent::edit_d($object,true);
			//实例化从表
			$protemplateitemDao = new model_stock_template_protemplateitem();
			$items = util_arrayUtil::setArrayFn(array('mainId' => $object['id']),$items);
			$protemplateitemDao->saveDelBatch($items);
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