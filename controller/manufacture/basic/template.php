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
     * ��ȡ���ṹ�б�
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
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		if($_POST['type'] == 'add'){
			$status = $this->service->add( $_POST );
			if($status) {
				msg( '�����ɹ���' );
				succ_show('index1.php?model=manufacture_basic_template');
			} else {
				msg( '����ʧ�ܣ�' );
			}
		}
		if($_POST['type'] == 'edit'){
			$status = $this->service->edit( $_POST );
			if($status) {
				msg( '����ɹ���' );
				succ_show('index1.php?model=manufacture_basic_template');
			} else {
				msg( '����ʧ�ܣ�' );
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
		//���ݼ��밲ȫ��
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
			msg( 'ģ�����ʹ�����������ȷ��ģ�壡' );
		}
		$this->assign('mark' ,$mark);
		$this->view ('import');
	}

	/*   -----------------  ���ຯ��  -----------------  */

	function c_classifyAddEdit($isAddInfo = false) {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		if(isset($_POST['classify']['id']) && !empty($_POST['classify']['id'])){
			$status = $this->service->classify_edit( $_POST['classify'] );
			if($status) {
				msg( '����ɹ���' );
			} else {
				msg( '����ʧ�ܣ�' );
			}
		}else{
			$id = $this->service->classify_add ($_POST['classify']);
			if ($id) {
				msg('��ӳɹ���');
			}else{
				msg('���ʧ�ܣ������²���');
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
		$this->permCheck (); //��ȫУ��
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
		$this->permCheck (); //��ȫУ��
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