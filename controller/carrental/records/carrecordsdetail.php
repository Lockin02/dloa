<?php

/**
 * @author Show
 * @Date 2011��12��27�� ���ڶ� 19:08:21
 * @version 1.0
 * @description:�ó���ϸ
 */
class controller_carrental_records_carrecordsdetail extends controller_base_action {

	function __construct() {
		$this->objName = "carrecordsdetail";
		$this->objPath = "carrental_records";
		parent :: __construct();
	}

	/**
	 * ��ת���ó���ϸ
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�������ó���ϸ
	 */
	function c_toAdd() {
		$this->view('add');
	}

    /**
     * ��־������Ƹ��Ա��Ϣ
     */
    function c_toAddInWorklog(){
        $worklogId = $_GET['worklogId'];
        //��ȡ��־�е�������Ϣ
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        $this->view('addinworklog');
    }

    /**
     * ��־������Ƹ��Ϣ
     */
    function c_addInWorklog(){
        $object = $_POST[$this->objName];
        $countMoneyArr = $this->service->addBatch_d($object);
        if($countMoneyArr){
            $rtValue = util_jsonUtil::encode ( $countMoneyArr );
            echo "<script>alert('����ɹ�');if(window.opener){window.opener.returnValue = '$rtValue';}window.returnValue = '$rtValue';window.close();</script>";
        }else{
            echo "<script>alert('����ʧ��');window.close();</script>";
        }
        exit();
    }

	/**
	 * ��ת���༭�ó���ϸ
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
		 * ��ת���鿴�ó���ϸ
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
	 * ��д�б�
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam($_POST);
		$service->sort = 'c.useDate';
		$service->asc = false;
		$rows = $service->list_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * ��ȡ�ó���¼���ݷ���json
	 */
	function c_listJsonForLog() {
		$service = $this->service;
		$service->getParam($_POST);
		$service->sort = 'c.useDate';
		$service->asc = false;

		$rows = $this->service->list_d('select_forweeklog');
		echo util_jsonUtil :: encode($rows);
	}
}
?>