<?php
/**
 * @author Show
 * @Date 2013年10月8日 0:20:42
 * @version 1.0
 * @description:外包模板费用模板明细控制层 
 */
class controller_contract_outsourcing_outtemplateitem extends controller_base_action {

	function __construct() {
		$this->objName = "outtemplateitem";
		$this->objPath = "contract_outsourcing";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到外包模板费用模板明细列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增外包模板费用模板明细页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑外包模板费用模板明细页面
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
	 * 跳转到查看外包模板费用模板明细页面
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