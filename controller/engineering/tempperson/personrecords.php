<?php
/**
 * @author Show
 * @Date 2012��7��17�� ���ڶ� 19:13:24
 * @version 1.0
 * @description:��Ƹ��Ա��¼���Ʋ�
 */
class controller_engineering_tempperson_personrecords extends controller_base_action {

	function __construct() {
		$this->objName = "personrecords";
		$this->objPath = "engineering_tempperson";
		parent :: __construct();
	}

	/*
	 * ��ת����Ƹ��Ա��¼�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������Ƹ��Ա��¼ҳ��
	 */
	function c_toAdd() {
		$this->assign('thisDate',day_date);
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
        $countMoney = $this->service->addBatch_d($object);
        if($countMoney){
            echo "<script>alert('����ɹ�');if(window.opener){window.opener.returnValue = $countMoney;}window.returnValue = $countMoney;window.close();</script>";
        }else{
        	echo "<script>alert('����ʧ��');window.close();</script>";
        }
        exit();
    }

	/**
	 * ��ת���༭��Ƹ��Ա��¼ҳ��
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
	 * ��ת���鿴��Ƹ��Ա��¼ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

    /*
	 * ��ת�����Կ�ʹ�ü�¼�б�
	 */
    function c_pageForProject() {
    	$this->assign('projectId',$_GET['projectId']);
		$this->view('listforproject');
    }
}
?>