<?php
/**
 * @author Show
 * @Date 2013年12月10日 星期二 17:12:46
 * @version 1.0
 * @description:物料协议价信息 Model层
 */
 class model_purchase_material_material  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_purchase_material";
		$this->sql_map = "purchase/material/materialSql.php";
		parent::__construct ();
	}

	function add_d($object){
		try {
			$this->start_d();

			$dictDao = new model_system_datadict_datadict();
			$object['protocolType'] = $dictDao->getDataNameByCode($object['protocolTypeCode']);
			$id = parent :: add_d($object);

			$materialequDao = new model_purchase_material_materialequ();
			$productCode = $object['productCode'];
			$productId = $object['productId'];
			$productName = $object['productName'];
			if(is_array($object['materialequ'])){
				foreach($object['materialequ'] as $key => $arr){
					$arr['parentId'] = $id;
					$arr['productCode'] = $productCode;
					$arr['productId'] = $productId;
					$arr['productName'] = $productName;
					$arr['isEffective'] = empty($arr['isEffective']) ? 'no':'on';
					$materialequDao->add_d($arr);
				}
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	function edit_d($object){
		try {
			$this->start_d();

			$dictDao = new model_system_datadict_datadict();
			$object['protocolType'] = $dictDao->getDataNameByCode($object['protocolTypeCode']);
			$id = parent :: edit_d($object, true); //修改主表信息

			$materialequDao = new model_purchase_material_materialequ();
			$materialequDao->delete(array ('parentId' =>$object['id']));
			$productCode = $object['productCode'];
			$productId = $object['productId'];
			$productName = $object['productName'];
			if(is_array($object['materialequ'])){
				foreach($object['materialequ'] as $key => $arr){
					if ($arr['isDelTag'] != 1) {
						$arr['parentId'] = $object['id'];
						$arr['productCode'] = $productCode;
						$arr['productId'] = $productId;
						$arr['productName'] = $productName;
						$arr['isEffective'] = empty($arr['isEffective']) ? 'no':'on';
						$materialequDao->add_d($arr);
					}
				}
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

 }
?>