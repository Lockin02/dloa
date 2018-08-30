<?php
/**
 * @author Administrator
 * @Date 2012年2月20日 14:00:37
 * @version 1.0
 * @description:发货计划进度备注控制层
 */
class controller_stock_outplan_outplanrate extends controller_base_action {

	function __construct() {
		$this->objName = "outplanrate";
		$this->objPath = "stock_outplan";
		parent::__construct ();
	 }

	/*
	 * 跳转到发货计划进度备注
	 */
    function c_page() {
    	$planId = isset($_GET['id']) ? $_GET['id'] : null;
    	$this->assign('planId',$planId);
    	$this->view('list');
    }


	/*
	 * 跳转到发货计划进度备注
	 */
    function c_toAdd() {
    	$planId = isset($_GET['id']) ? $_GET['id'] : null;
    	$this->assign('createName',$_SESSION['USERNAME']);
    	$this->assign('planId',$planId);
	  $this->view('add');
    }
	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, true )) {
			msg ( '编辑成功！' );
		}
	}

	/**
	 * 跳转到录入进度界面
	 */
	 function c_updateRate(){
	 	$planId = $_GET['id'];
		$rateDao->asc=false;
	 	$this->service->searchArr['planId']=$planId;
	 	$row = $this->service->list_d();
	 	if(is_array($row)&&count($row)>0){
	 		foreach ( $row['0'] as $key=>$val){
	 			$this->assign($key,$val);
	 		}
			$this->permCheck (); //安全校验
			$this->view ( 'edit' );
	 	}else{
	 		$this->c_toAdd();
	 	}
	 }

 }
?>