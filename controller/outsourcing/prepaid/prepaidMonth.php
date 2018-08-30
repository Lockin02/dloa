<?php
/**
 * @author Acan
 * @Date 2014年10月30日 11:03:34
 * @version 1.0
 * @description:外包预提_月份控制层 
 */
class controller_outsourcing_prepaid_prepaidMonth extends controller_base_action {

	function __construct() {
		$this->objName = "prepaidMonth";
		$this->objPath = "outsourcing_prepaid";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到外包预提_月份列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增外包预提_月份页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑外包预提_月份页面
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
	 * 跳转到查看外包预提_月份页面
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