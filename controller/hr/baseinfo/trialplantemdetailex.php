<?php

/**
 * @author Show
 * @Date 2012��9��3�� ����һ 19:51:29
 * @version 1.0
 * @description:����ģ����չ��Ϣ���Ʋ�
 */
class controller_hr_baseinfo_trialplantemdetailex extends controller_base_action {

	function __construct() {
		$this->objName = "trialplantemdetailex";
		$this->objPath = "hr_baseinfo";
		parent :: __construct();
	}

	/***************** �б� *************************/

	/**
	 * ��ת������ģ����չ��Ϣ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		if(empty($_REQUEST['ids'])){
			return "";
		}
		$service->asc = false;
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/***************** ��ɾ�Ĳ� **********************/

	/**
	 * ��ת����������ģ����չ��Ϣҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭����ģ����չ��Ϣҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * ��ת���鿴����ģ����չ��Ϣҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/*************** ҵ���߼� ************************/
	/**
	 * ����������
	 */
	function c_toSetRule(){
		$this->assignFunc($_GET);
		$this->view('setrule');
	}

	/**
	 * ��������
	 */
	function c_setRule(){
		$object = $_POST[$this->objName];
		$rs = $this->service->setRule_d($object);
		if($rs){
			echo "<script>alert('����ɹ�');if(window.opener){window.opener.returnValue = '$rs';}window.returnValue = '$rs';window.close();</script>";
		}else{
            echo "<script>alert('����ʧ��');window.close();</script>";
        }
        exit();
	}

	/**
	 * ��ת���鿴����ģ����չ��Ϣҳ��
	 */
	function c_toViewRule() {
		$this->assignFunc($_GET);
		$this->view('viewrule');
	}
}
?>