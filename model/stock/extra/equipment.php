<?php
/**
 * @author huangzf
 * @Date 2012年7月11日 星期三 14:18:58
 * @version 1.0
 * @description:常用设备基本信息 Model层 
 */
class model_stock_extra_equipment extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_stock_extra_equipment";
		$this->sql_map = "stock/extra/equipmentSql.php";
		parent::__construct ();
	}
	
	/*--------------------------------------------业务操作--------------------------------------------*/
	
	/**
	 * 新增保存
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			
			if (is_array ( $object ['items'] )) {
				$this->start_d ();
				$id = parent::add_d ( $object, true );
				$equipmentproDao = new model_stock_extra_equipmentpro ();
				$itemsArr = util_arrayUtil::setItemMainId ( "mainId", $id, $object ['items'] );
				$itemsObj = $equipmentproDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $id;
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			if (is_array ( $object ['items'] )) {
				$this->start_d ();
				$editResult = parent::edit_d ( $object, true );
				$equipmentproDao = new model_stock_extra_equipmentpro ();
				$itemsArr = util_arrayUtil::setItemMainId ( "mainId", $object ['id'], $object ['items'] );
				$itemsObj = $equipmentproDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $editResult;
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 * 通过id获取详细信息
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d ( $id );
		$equipmentproDao = new model_stock_extra_equipmentpro ();
		$equipmentproDao->searchArr ['mainId'] = $id;
		$object ['items'] = $equipmentproDao->listBySqlId ();
		return $object;
	
	}
}
?>