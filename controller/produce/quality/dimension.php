<?php
/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 9:56:38
 * @version 1.0
 * @description:检验项目控制层 
 */
class controller_produce_quality_dimension extends controller_base_action {

	function __construct() {
		$this->objName = "dimension";
		$this->objPath = "produce_quality";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到检验项目列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增检验项目页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑检验项目页面
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
	 * 跳转到查看检验项目页面
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