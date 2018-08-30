<?php
/**
 * @author Administrator
 * @Date 2012年1月7日 14:57:01
 * @version 1.0
 * @description:评估项目控制层
 */
class controller_supplierManage_scheme_schemeproject extends controller_base_action {

	function __construct() {
		$this->objName = "schemeproject";
		$this->objPath = "supplierManage_scheme";
		parent::__construct ();
	 }

	/*
	 * 跳转到评估项目列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增评估项目页面
	 */
	function c_toAdd() {
	$this->assign ( 'formManName', $_SESSION ['USERNAME'] ); //创建人
	$this->assign ( 'formManId', $_SESSION ['USER_ID'] );
     $this->view ( 'add',true);
   }
   /**
    * 新增提交验证
    */
   function c_add(){
		$this->checkSubmit(); //验证是否重复提交
		$object = $_POST [$this->objName];
		$id = $this->service->add_d($object);
		if($id){
			msg("添加成功！");
		}else{
			msg("添加失败！");
		}
   }

   /**
	 * 跳转到编辑评估项目页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit',true);
   }
/**
    * 新增提交验证
    */
   function c_edit(){
		$this->checkSubmit(); //验证是否重复提交
		$object = $_POST [$this->objName];
		$id = $this->service->edit_d($object);
		if($id){
			msg("编辑成功！");
		}else{
			msg("编辑失败！");
		}
   }
   /**
	 * 跳转到查看评估项目页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
 }
?>