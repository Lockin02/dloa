<?php
/**
 * @author ACan
 * @Date 2015年4月13日 14:25:37
 * @version 1.0
 * @description:配置信息-工序控制层 
 */
class controller_produce_task_processequ extends controller_base_action {

	function __construct() {
		$this->objName = "processequ";
		$this->objPath = "produce_task";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到配置信息-工序列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增配置信息-工序页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑配置信息-工序页面
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
	 * 跳转到查看配置信息-工序页面
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