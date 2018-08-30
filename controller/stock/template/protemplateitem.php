<?php
/**
 * @author Show
 * @Date 2013年8月2日 星期五 14:41:42
 * @version 1.0
 * @description:物料模板配置明细表控制层 
 */
class controller_stock_template_protemplateitem extends controller_base_action {

	function __construct() {
		$this->objName = "protemplateitem";
		$this->objPath = "stock_template";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到物料模板配置明细表列表
	 */
    function c_page() {
      	$this->view('list');
    }
    
   /**
	 * 跳转到新增物料模板配置明细表页面
	 */
	function c_toAdd() {
     	$this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑物料模板配置明细表页面
	 */
	function c_toEdit() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}  
      	$this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看物料模板配置明细表页面
	 */
	function c_toView() {
      	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      	$this->view ( 'view' );
   }
 }
?>