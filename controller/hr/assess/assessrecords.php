<?php
/**
 * @author Administrator
 * @Date 2013年4月23日 星期二 16:38:39
 * @version 1.0
 * @description:季度考核信息控制层
 */
class controller_hr_assess_assessrecords extends controller_base_action {

	function __construct() {
		$this->objName = "assessrecords";
		$this->objPath = "hr_assess";
		parent::__construct ();
	 }

	/**
	 * 跳转到季度考核信息列表
	 */
    function c_page() {
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//员工编号
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//员工账号
      	$this->assign('userNo',$userNo);
      	$this->assign('userAccount',$userAccount);
      $this->view('list');
    }

   /**
	 * 跳转到新增季度考核信息页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑季度考核信息页面
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
	 * 跳转到查看季度考核信息页面
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