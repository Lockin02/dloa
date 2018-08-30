<?php
/**
 * @author Administrator
 * @Date 2012年2月29日 19:19:15
 * @version 1.0
 * @description:发货需求进度备注控制层
 */
class controller_stock_outplan_contractrate extends controller_base_action {

	function __construct() {
		$this->objName = "contractrate";
		$this->objPath = "stock_outplan";
		parent::__construct ();
	 }
	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
    	$this->assign('relDocId',$_GET['docId']);
    	$this->assign('relDocType',$_GET['docType']);
    	$this->assign('rObjCode',$_GET['objCode']);
    	$this->assign('createName',$_SESSION['USERNAME']);
		$this->view ( 'add' );
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
	 	$docId = $_GET['docId'];
	 	$docType = $_GET['docType'];
	 	$this->service->searchArr['relDocType']=$docType;
		$rateDao->asc=false;
	 	$this->service->searchArr['relDocId']=$docId;
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