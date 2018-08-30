<?php
/**
 * @author Michael
 * @Date 2014年7月21日 11:45:06
 * @version 1.0
 * @description:人事管理-导出勾选记录控制层 
 */
class controller_hr_personnel_excel extends controller_base_action {

	function __construct() {
		$this->objName = "excel";
		$this->objPath = "hr_personnel";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到人事管理-导出勾选记录列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增人事管理-导出勾选记录页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑人事管理-导出勾选记录页面
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
	 * 跳转到查看人事管理-导出勾选记录页面
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