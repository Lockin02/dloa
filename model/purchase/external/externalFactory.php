<?php
/**
 * 采购计划统一工厂策略类
 */
 class model_purchase_external_externalFactory  {

 	//不同类型采购计划策略类,根据需要在这里进行追加
 		static private  $purchTypeArr=array(  
 			"contract_sales"=>
 				array(
 					"name"=>"销售合同采购计划",
 					"model"=>"model_purchase_external_sale"
 				),
 			"stock"=>"model_purchase_external_stock",			  //补库采购计划
 			"rdproject"=>"model_purchase_external_rdproject",	  //研发采购计划
 			"assets"=>"model_purchase_external_assets",			  //固定资产采购计划
 			"order"=>"model_purchase_external_orderpurchase"	  //订单采购计划
 		);

		function getPurchType($type) {
			return self::$purchTypeArr [$type];
		}
		
		function getPurchTypeName($type) {
			return self::$purchTypeArr [$type]['name'];
		}
		
		function getPurchTypeModel($type) {
			return self::$purchTypeArr [$type]['model'];
		}
		
		function createPurchTypeModel($type) {
			return new $this->getPurchaseModel($type);
		}
		
		function getPurchTypeNames(){
			
		}
}
?>
