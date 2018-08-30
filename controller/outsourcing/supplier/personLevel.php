<?php
/**
 * @author Administrator
 * @Date 2013年11月5日 星期二 14:17:30
 * @version 1.0
 * @description:人员技术类型控制层 
 */
class controller_outsourcing_supplier_personLevel extends controller_base_action {

	function __construct() {
		$this->objName = "personLevel";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到人员技术类型列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增人员技术类型页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑人员技术类型页面
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
	 * 跳转到查看人员技术类型页面
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