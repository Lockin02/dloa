<?php
/**
 * @author Administrator
 * @Date 2013年2月27日 星期三 14:28:01
 * @version 1.0
 * @description:生产物料需求控制层 
 */
class controller_stock_extra_produceproitem extends controller_base_action {

	function __construct() {
		$this->objName = "produceproitem";
		$this->objPath = "stock_extra";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到生产物料需求列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增生产物料需求页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑生产物料需求页面
	 */
	function c_toEdit() {
   		$this->permCheck (); //安全校验
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
	 * 跳转到查看生产物料需求页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
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