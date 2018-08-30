<?php

class controller_contractTool_contractTool_authorize extends controller_base_action {

	function __construct() {
		$this->objName = "authorize";
		$this->objPath = "contractTool_contractTool";
//		$this->lang="contract";//���԰�ģ��
		parent :: __construct();
	}

	function  c_toAdd(){
		$userLimitStr = $this->service->getLimitHtml_d();
		$this->assign("userLimitStr",$userLimitStr);
		$this->view("add");
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsons() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d ();
		foreach($rows as $k=>$v){
			$rowArrs = array();
			$aff = explode(",",$v['limitInfo']);
			if(in_array("build",$aff)){
				array_push($rowArrs,"��ͬ��Ϣ���");
			}
			if(in_array("delivery",$aff)){
				array_push($rowArrs,"��ͬ����");
			}
			if(in_array("waiting",$aff)){
				array_push($rowArrs,"��ͬ����");
			}
			if(in_array("invoice",$aff)){
				array_push($rowArrs,"��ͬ��Ʊ�տ�");
			}
			if(in_array("archive",$aff)){
				array_push($rowArrs,"��ͬ�ı��鵵");
			}
			if(in_array("close",$aff)){
				array_push($rowArrs,"��ͬ�ر�");
			}
			$rowsss = implode(",",$rowArrs);
			$rows[$k]['limitInfos'] = $rowsss;
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

		/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$limitArr = explode(",",$obj['limitInfo']);
			$userLimitStr = $this->service->getLimitHtml_d($limitArr);
			$this->assign("userLimitStr",$userLimitStr);
			$this->view ('edit',true);
		}
	}


	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //��ȫУ��
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}


	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit();
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id == "0") {
			msg( "���û��Ѵ���!" );
		}else{
			msg ( $msg );
		}
	}

	function c_checkUser(){
		$userName = $_POST['userName'];
		$userCode = $_POST['userCode'];
		$result = $this->service->checkUser_d($userCode,$userName);
		if($result == 1){
			echo 1;
		}else{
			echo 0;
		}
	}
}
?>