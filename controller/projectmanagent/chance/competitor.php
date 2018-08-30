<?php
/**
 * @author Administrator
 * @Date 2012-08-02 15:58:33
 * @version 1.0
 * @description:竞争对手控制层
 */
class controller_projectmanagent_chance_competitor extends controller_base_action {

	function __construct() {
		$this->objName = "competitor";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }

	/*
	 * 跳转到竞争对手列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增竞争对手页面
	 */
	function c_toAdd() {
	   $this->assign("chanceId",$_GET['chanceId']);
       $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑竞争对手页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看竞争对手页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
 }
?>