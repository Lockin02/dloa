<?php
/**
 * @author huangzf
 * @Date 2013年9月6日 15:43:40
 * @version 1.0
 * @description:产品分类信息 Model层 
 */
class model_yxlicense_license_category extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_license_category";
		$this->sql_map = "yxlicense/license/categorySql.php";
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
			$newId = parent::add_d ($object, true);
			$categoryDao = new model_yxlicense_license_categoryitem();
			$items = util_arrayUtil :: setArrayFn(array ('categoryId' => $newId ,'licenseId' => $object['licenseId']), $items);
			if ($items) {
				$categoryDao->saveDelBatch($items);
			}
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
	function edit_d($object) {
		//剔除主表无关信息
		$items = $object['items'];
		unset ($object['items']);
		if(!isset($object['isHideTitle'])){
			$object['isHideTitle'] = '0';
		}
		try {
			$this->start_d();

			//调用父类编辑
			parent :: edit_d($object, true);

			//实例化从表
			$categoryDao = new model_yxlicense_license_categoryitem();
			$items = util_arrayUtil :: setArrayFn(array ('categoryId' => $object['id'],'licenseId' => $object['licenseId']), $items );
			if ($items) {
				$categoryDao->saveDelBatch($items);
			}
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}
	}
	
	/**
	 * 通过licenseId 查询有多少条分类
	 * @param unknown $obj
	 */
	function getCategotyId_d($licenseId){
		$sql = "select id,categoryName,appendDesc,showType,lineFeed,licenseId,isHideTitle,type from ".$this->tbl_name." where licenseId = '$licenseId' order by orderNum asc";
		return $this->_db->getArray($sql);
	}
	
}
?>