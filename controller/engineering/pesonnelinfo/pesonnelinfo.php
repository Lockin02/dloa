<?php
/**
 * @author Show
 * @Date 2012年8月1日 16:20:04
 * @version 1.0
 * @description:员工状况(oa_esm_pesonnelinfo)控制层 
 */
class controller_engineering_pesonnelinfo_pesonnelinfo extends controller_base_action {

	function __construct() {
		$this->objName = "pesonnelinfo";
		$this->objPath = "engineering_pesonnelinfo";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到员工状况(oa_esm_pesonnelinfo)列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增员工状况(oa_esm_pesonnelinfo)页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑员工状况(oa_esm_pesonnelinfo)页面
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
	 * 跳转到查看员工状况(oa_esm_pesonnelinfo)页面
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