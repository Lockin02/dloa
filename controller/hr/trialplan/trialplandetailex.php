<?php

/**
 * @author Show
 * @Date 2012��9��4�� ���ڶ� 13:30:45
 * @version 1.0
 * @description:������չ��Ϣ���Ʋ�
 */
class controller_hr_trialplan_trialplandetailex extends controller_base_action {

	function __construct() {
		$this->objName = "trialplandetailex";
		$this->objPath = "hr_trialplan";
		parent :: __construct();
	}

	/**
	 * ��ת��������չ��Ϣ�б�
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

	/**
	 * ��ת������������չ��Ϣҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭������չ��Ϣҳ��
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
	 * ��ת���鿴������չ��Ϣҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}


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
            $rs = util_jsonUtil::encode($rs);
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

	//���ּ���
	function c_calScore(){
		$ids = $_POST['ids'];
		$score = $_POST['score'];
		$baseScore = $this->service->calScore_d($ids,$score);

		if($baseScore){
			echo $baseScore;
		}else{
			echo 0;
		}
	}
}
?>