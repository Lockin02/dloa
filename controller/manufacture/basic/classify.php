<?php
class controller_manufacture_basic_classify extends controller_base_action {

	function __construct() {
		$this->objName = "classify";
		$this->objPath = "manufacture_basic";
		parent::__construct ();
	 }

	function c_page() {
		$this->view('list');
	}
	function c_toView() {
		$this->permCheck ();
		$obj = $this->service->get_parent ( $_GET ['id'] );

//		print_r($obj);exit;
		$data = $obj[0];
		foreach ( $data as $key => $val ) {
			$this->assign ( $key, $val );
		}

		if(!empty($data['parent'])){
			$parent = $this->service->get_parent ( $data['parent'] );
			$this->assign ( 'parent', $parent['0']['classifyName'] );
		}else{
			$this->assign ( 'parent', '' );
		}

		$this->view ( 'view' );
	}

	function c_toAdd() {
		$this->permCheck (); //安全校验
		$parent = $this->service->get_parent();
		$s_parent = "<option value=''></option>";
		foreach($parent as $val){
			$s_parent .= "<option value='".$val['id']."'>".$val['classifyName']."</option>";
		}

		$this->assign('parent' ,$s_parent);
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION ['USER_ID']);
		$this->assign('createTime' ,date("Y-m-d H:i:s"));
		$this->view ( 'add' );
	}

	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_parent ( $_GET ['id'] );
		$parent = $this->service->get_parent ();
		$data = $obj[0];

		$this->assign('id',$data['id']);
		$this->assign('remark',$data['remark']);
		$this->assign('createName',$data['createName']);
		$this->assign('createTime',$data['createTime']);
		$this->assign('classifyName',$data['classifyName']);

		$s_parent = "<option value=''></option>";
		foreach($parent as $val){
			if($data['parent'] == $val['id']){
				$s_parent .= "<option  selected='selected' value='".$val['id']."'>".$val['classifyName']."</option>";
			}else{
				$s_parent .= "<option value='".$val['id']."'>".$val['classifyName']."</option>";
			}
		}
		$this->assign('parent',$s_parent);

		$this->view ( 'edit' );
	}
}
?>