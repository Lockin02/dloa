<?php
/**
 * @author Administrator
 * @Date 2013��2��27�� ������ 14:28:01
 * @version 1.0
 * @description:��������������Ʋ� 
 */
class controller_stock_extra_produceproitem extends controller_base_action {

	function __construct() {
		$this->objName = "produceproitem";
		$this->objPath = "stock_extra";
		parent::__construct ();
	 }
    
	/**
	 * ��ת���������������б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������������������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭������������ҳ��
	 */
	function c_toEdit() {
   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}  
	 	$inventoryDao=new model_stock_inventoryinfo_inventoryinfo();
		$this->assign("actNum", $inventoryDao->getProActNum($val['productId']));
		
		$equDao=new model_purchase_contract_equipment();
		$this->assign("planInstockNum", $equDao->getEqusOnway(array("productId"=>$val['productId'])));
      	$this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴������������ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		
	 	$inventoryDao=new model_stock_inventoryinfo_inventoryinfo();
		$this->assign("actNum", $inventoryDao->getProActNum($val['productId']));
		
		$equDao=new model_purchase_contract_equipment();
		$this->assign("planInstockNum", $equDao->getEqusOnway(array("productId"=>$val['productId'])));
      $this->view ( 'view' );
   }
 }
?>