<?php
/**
 * @author Administrator
 * @Date 2013年10月9日 9:41:37
 * @version 1.0
 * @description:生产能力表明细控制层 
 */
class controller_report_report_produceinfo extends controller_base_action {

	function __construct() {
		$this->objName = "produceinfo";
		$this->objPath = "report_report";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到生产能力表明细列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增生产能力表明细页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑生产能力表明细页面
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
	 * 跳转到查看生产能力表明细页面
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