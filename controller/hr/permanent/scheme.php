<?php
/**
 * @author jianjungki
 * @Date 2012年8月3日 11:01:18
 * @version 1.0
 * @description:员工考核方案控制层
 */
class controller_hr_permanent_scheme extends controller_base_action {

	function __construct() {
		$this->objName = "scheme";
		$this->objPath = "hr_permanent";
		parent::__construct ();
	 }

	/*
	 * 跳转到员工考核方案列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增员工考核方案页面
	 */
	function c_toAdd() {
		$this->view ('add' ,true);
	}

   /**
	 * 跳转到编辑员工考核方案页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('schemeType' => 'HRKHJB' ), $obj ['schemeTypeCode'] ,true);
		$this->view ('edit' ,true);
	}

   /**
	 * 跳转到查看员工考核方案页面
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