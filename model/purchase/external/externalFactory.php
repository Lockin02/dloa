<?php
/**
 * �ɹ��ƻ�ͳһ����������
 */
 class model_purchase_external_externalFactory  {

 	//��ͬ���Ͳɹ��ƻ�������,������Ҫ���������׷��
 		static private  $purchTypeArr=array(  
 			"contract_sales"=>
 				array(
 					"name"=>"���ۺ�ͬ�ɹ��ƻ�",
 					"model"=>"model_purchase_external_sale"
 				),
 			"stock"=>"model_purchase_external_stock",			  //����ɹ��ƻ�
 			"rdproject"=>"model_purchase_external_rdproject",	  //�з��ɹ��ƻ�
 			"assets"=>"model_purchase_external_assets",			  //�̶��ʲ��ɹ��ƻ�
 			"order"=>"model_purchase_external_orderpurchase"	  //�����ɹ��ƻ�
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
