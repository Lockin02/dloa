<?php

class controller_manufacture_basic_template extends controller_base_action {

	function __construct() {

		$this->objName = "template";
		$this->objPath = "manufacture_basic";
		parent::__construct ();
	}

	function c_page() {

		$classify = $this->service->get_classify();
		foreach($classify as $val){
			if(!empty($val['classifyName'])){
				$classify_sel .= "<option value='".$val['id']."'>".$val['classifyName']."</option>";
			}
		}
		$this->assign('classify_sel' ,$classify_sel);
		$this->assign('t_model' ,'manufacture_basic_template');
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION ['USER_ID']);
		$this->assign('createTime' ,date("Y-m-d"));
		$this->view ( 'list' );
	}

	 /**
     * 获取树结构列表
     */
    function c_load_classify_tree() {
        $datas = $this->service->loadTree();
        header('Content-type:application/json');
        exit(json_encode(un_iconv($datas)));
    }

    function c_get_template_list() {
        $datas = $this->service->getTemplateList($_GET['id']);
        header('Content-type:application/json');
        exit(json_encode(un_iconv($datas)));
    }

    function c_add_edit() {
		$this->checkSubmit(); //验证是否重复提交
		if($_POST['type'] == 'add'){
			$status = $this->service->add( $_POST );
			if($status) {
				msg( '新增成功！' );
				succ_show('index1.php?model=manufacture_basic_template');
			} else {
				msg( '新增失败！' );
			}
		}
		if($_POST['type'] == 'edit'){
			$status = $this->service->edit( $_POST );
			if($status) {
				msg( '保存成功！' );
				succ_show('index1.php?model=manufacture_basic_template');
			} else {
				msg( '保存失败！' );
			}
		}

	}

	function c_template() {
		$data = $this->service->get_template( $_POST['id'] );
		header('Content-type:application/json');
		exit(json_encode(un_iconv($data)));
	}

	function c_product() {
		$id = isset($_GET['id'])?$_GET['id']:$_POST['id'];
		$datas = $this->service->get_product($id);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $datas );
		echo util_jsonUtil::encode ( $rows );
	}

	function c_del(){
		$status = $this->service->del( $_POST['id'] );
		header('Content-type:application/json');
		echo $status;
	}

	function c_temImport(){

		$filename = $_FILES["import"]["name"];
		$temp_name = $_FILES["import"]["tmp_name"];
		$pathinfo = pathinfo($filename);
		$mark = '0';
		if( $pathinfo['extension'] == 'xls'){
			$datas = $this->service->temImport($filename,$temp_name);
			$this->assign('datas' ,json_encode(un_iconv($datas)));
			$mark = '1';
		}else if(isset( $pathinfo['extension'])){
			msg( '模板类型错误，请下载正确的模板！' );
		}
		$this->assign('mark' ,$mark);
		$this->view ('import');
	}

	/*   -----------------  分类函数  -----------------  */

	function c_classifyAddEdit($isAddInfo = false) {
		$this->checkSubmit(); //验证是否重复提交
		if(isset($_POST['classify']['id']) && !empty($_POST['classify']['id'])){
			$status = $this->service->classify_edit( $_POST['classify'] );
			if($status) {
				msg( '保存成功！' );
			} else {
				msg( '保存失败！' );
			}
		}else{
			$id = $this->service->classify_add ($_POST['classify']);
			if ($id) {
				msg('添加成功！');
			}else{
				msg('添加失败，请重新操作');
			}
		}

		succ_show('index1.php?model=manufacture_basic_template');
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
		$obj = $this->service->get_parent ( $_POST['id'] );
		$parent = $this->service->get_parent ();
		$data = $obj[0];

		$s_parent = "<option value=''></option>";
		foreach($parent as $val){
			if($data['parent'] == $val['id']){
				$s_parent .= "<option  selected='selected' value='".$val['id']."'>".$val['classifyName']."</option>";
				$n_parent = $val['classifyName'];
			}else if($_POST['id'] != $val['id']){
				$s_parent .= "<option value='".$val['id']."'>".$val['classifyName']."</option>";
			}
		}

		$data['parent'] = $s_parent;
		$data['n_parent'] = $n_parent;
		header('Content-type:application/json');
		exit(json_encode(un_iconv($data)));
	}

}