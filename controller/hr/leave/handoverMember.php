<?php
/**
 * @author Administrator
 * @Date 2012年10月30日 星期二 14:49:22
 * @version 1.0
 * @description:离职交接清单明细接收人控制层 
 */
class controller_hr_leave_handoverMember extends controller_base_action {

	function __construct() {
		$this->objName = "handoverMember";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到离职交接清单明细接收人列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增离职交接清单明细接收人页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑离职交接清单明细接收人页面
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
	 * 跳转到查看离职交接清单明细接收人页面
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