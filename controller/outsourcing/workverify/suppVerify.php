<?php
/**
 * @author Admin
 * @Date 2014年1月16日 13:36:19
 * @version 1.0
 * @description:外包供应商工作量确认单控制层
 */
class controller_outsourcing_workverify_suppVerify extends controller_base_action {

	function __construct() {
		$this->objName = "suppVerify";
		$this->objPath = "outsourcing_workverify";
		parent::__construct ();
	 }

	/**
	 * 跳转到外包供应商工作量确认单列表
	 */
    function c_page() {
      $this->view('list');
    }

    	/**
	 * 跳转到工作量确认单列表
	 */
    function c_toSuppList() {
		$this->assign('createId', $_SESSION['USER_ID']);
      $this->view('supp-list');
    }

   /**
	 * 跳转到新增外包供应商工作量确认单页面
	 */
	function c_toAdd() {
		$this->assign('formDate',date("Y-m-d"));
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('createName', $_SESSION['USERNAME']);
	     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑外包供应商工作量确认单页面
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
	 * 跳转到查看外包供应商工作量确认单页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

  	/**
	 * 获取周期内的工作量json
	 */
	function c_worklogListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$esmworklogDao=new model_engineering_worklog_esmworklog();
		$rows=$esmworklogDao->getTimeLogForSupp_d($_POST['beginDate'],$_POST['endDate']);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

   	 /**
	 *新增
	 *
	 */
	 function c_add(){
		$addType = isset ($_GET['addType']) ? $_GET['addType'] : null;
		if($addType=='submit'){
			$_POST[$this->objName]['status']=1;
		}
		$id=$this->service->add_d($_POST[$this->objName]);
		if($id){
			if($addType!=''){
				msg ( '提交成功！' );
			}else{
				msg ( '保存成功！' );
			}
		}else{
			if($addType!=''){
				msg ( '提交失败！' );
			}else{
				msg ( '保存失败！' );
			}
		}
	 }

	 /**
	 *新增
	 *
	 */
	 function c_edit(){
		$addType = isset ($_GET['addType']) ? $_GET['addType'] : null;
		if($addType=='submit'){
			$_POST[$this->objName]['status']=1;
		}
//		echo "<pre>";
//		print_r($_POST[$this->objName]);
		$id=$this->service->edit_d($_POST[$this->objName]);
		if($id){
			if($addType!=''){
				msg ( '提交成功！' );
			}else{
				msg ( '保存成功！' );
			}
		}else{
			if($addType!=''){
				msg ( '提交失败！' );
			}else{
				msg ( '保存失败！' );
			}
		}
	 }
	/**
	 * 改变状态(ajax)
	 *
	 */
	 function c_changeState(){
	 	$flag=$this->service->changeState_d($_POST);
	 	if($flag){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }

   /**
    * 直接提交
    */
	function c_changeStatus() {
		$arr = $this->service->update(array('id'=>$_POST['id']) ,array('status'=>'1'));
		echo $arr;
	}
 }
?>