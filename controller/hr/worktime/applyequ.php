<?php
/**
 * @author yxin1
 * @Date 2014年4月24日 13:37:38
 * @version 1.0
 * @description:节假日加班申请明细表控制层 
 */
class controller_hr_worktime_applyequ extends controller_base_action {

	function __construct() {
		$this->objName = "applyequ";
		$this->objPath = "hr_worktime";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到节假日加班申请明细表列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增节假日加班申请明细表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑节假日加班申请明细表页面
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
	 * 跳转到查看节假日加班申请明细表页面
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