<?php
/**
 * @author Administrator
 * @Date 2012年8月18日 15:23:52
 * @version 1.0
 * @description:背景调查记录表控制层
 */
class controller_hr_recruitment_investigation extends controller_base_action {

	function __construct() {
		$this->objName = "investigation";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到背景调查记录表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增背景调查记录表页面
	 */
	function c_toAdd() {
		$this->assign('thisUser',$_SESSION['USERNAME']);
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$today=date(Y.'-'.m.'-'.d);
		$this->assign("today", $today);
		$this->showDatadicts(array('relationshipName' => 'YZXRGX'));
		$this->view ('add' ,true);
	}

	/**
	 * 跳转到编辑背景调查记录表页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->find ( array ("parentId" => $_GET['id'] ) );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$today=date(Y.'-'.m.'-'.d);
		$this->assign("today", $today);
		$this->showDatadicts(array('relationshipName' => 'YZXRGX'));
		$this->view ('edit' ,true);
	}

	/**
	 * 跳转到查看背景调查记录表页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/*
	 * 背景调查新增方法
	 */
	function c_add(){
		$this->checkSubmit(); //检查是否重复提交
		$obj=$_POST[$this->objName];
		$id=$this->service->add_d($obj);
		if ($id) {
			if($_GET['type']=='list'){
				msgGo('保存成功',"index1.php?model=hr_recruitment_investigation");
			}else{
				msgGo('保存成功',"index1.php?model=hr_recruitment_interview&action=tolastpage");
			}
		}
	}

	/**
	 * 修改对象操作
	 */
	function c_edit() {
		$this->checkSubmit(); //检查是否重复提交
		$object = $_POST[$this -> objName];
		$id = $this -> service -> edit_d($object, true);
		if ($id) {
			msgGo('保存成功',"index1.php?model=hr_recruitment_interview&action=tolastpage");
		}
	}

	/*
	 * 跳转到个人背景调查列表
	 */
	function c_toPersonPage(){
		$this->assign( 'thisUserId',$_SESSION['USER_ID'] );
		$this->view('listbyperson');
	}

	/*
	 * 判断背景调查是否应该进入编辑页面
	 */
	function c_isToEdit(){
		$id=$_POST['id'];
		$arr=$this->service->find(array("parentId"=>$id));
		echo util_jsonUtil::encode($arr);
	}
}
?>