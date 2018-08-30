<?php
/**
 * @author Show
 * @Date 2013��8��2�� ������ 14:41:22
 * @version 1.0
 * @description:����ģ�����ñ� Model�� 
 */
 class model_stock_template_protemplate  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_product_template";
		$this->sql_map = "stock/template/protemplateSql.php";
		parent::__construct ();
	}   

 	/**
	 * ��дadd
	 */
	function add_d($object){
		//�޳������޹���Ϣ
		$items = $object['items'];
		unset($object['items']);
		try{
			$this->start_d();
			//���ø�������
			$newId = parent::add_d($object,true);
			//ʵ�����ӱ�
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
	 * ��дedit_d
	 */
	function edit_d($object){
		//�޳������޹���Ϣ
		$items = $object['items'];
		unset($object['items']);	
		try{
			$this->start_d();
			//���ø���༭
			parent::edit_d($object,true);
			//ʵ�����ӱ�
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