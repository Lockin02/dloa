<?php

/**
 * ����������Ʋ�
 */
class controller_file_uploadfile_management extends controller_base_action
{

	function __construct() {
		$this->objName = "management";
		$this->objPath = "file_uploadfile";
		parent::__construct();
	}

	/**
	 * �����ϴ��ӿ�
	 */
	function c_toAddFile() {
		$_POST['serviceId'] = isset($_POST['serviceId']) ? $_POST['serviceId'] : '';
		$_POST['serviceNo'] = isset($_POST['serviceNo']) ? $_POST['serviceNo'] : '';
		$_POST['serviceType'] = isset($_POST['serviceType']) ? $_POST['serviceType'] : '';
		$_POST['isTemp'] = isset($_POST['isTemp']) ? $_POST['isTemp'] : '';
		$_POST['typeId'] = isset($_POST['typeId']) ? $_POST['typeId'] : '';
		$_POST['typeName'] = isset($_POST['typeId']) ? util_jsonUtil::iconvUTF2GB($_POST['typeName']) : '';
		//�����ϴ�����
		echo util_jsonUtil::encode($this->service->uploadFileAction($_FILES, $_POST));
	}

	/**
	 * �ļ����ؽӿ�
	 */
	function c_toDownFile() {
		$this->service->downFile2($_GET['inDocument'], $_GET['newName'], $_GET['originalName']);
	}

	/*
	 * ͨ������id���ظ���
	 */
	function c_toDownFileById() {
		$this->service->downFileById($_GET['fileId']);
	}

	function c_readInfo() {
		$rows = $this->service->get_d($_GET['id']);
		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->show->display($this->objPath . '_' . $this->objName . '-read');
	}

	function c_editInfo() {
		$this->show->assign('objId', $_GET['objId']);
		$this->show->assign('objTable', $_GET['objTable']);
		$projectId = $_GET['objId'];
		$rows = $this->service->get_d($_GET['objId']);
		$rows['file'] = $this->service->getFilesByObjId($projectId);
		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->show->display($this->objPath . '_uploadfile-onload');
	}

	/*
	 * ��дajax��ʽɾ����������
	 * 1.ɾ�������ļ�
	 * 2.ɾ�����ݿ��¼
	 */
	function c_ajaxdelete() {
		try {
			$service = $this->service;
			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			//			echo "111111" . $_POST['id'];
			$file = $this->service->get_d($_POST['id']);
			$this->service->delFile($file['serviceType'], $file['newName'], $file['createTime']);
			$this->service->deletes_d($_POST['id']);
			echo 1;
		} catch (Exception $e) {
			echo 0;
		}
	}

	/**
	 * @desription �з������б�
	 */
	function c_rdpage() {
		$service = $this->service;
		$service->resetParam();
		$service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
		if (isset($_GET['objId'])) {
			unset($this->searchArr['objId']);
			unset($this->searchArr['tabId']);
			$service->searchArr['serviceId'] = $_GET['objId'];
			$service->searchArr['serviceType'] = $_GET['objTable'];
		}
		$rows = $service->page_d();

		$projectId = $_GET['objId'];
		if ($_GET['objTable'] == 'oa_rd_task') {//����
			$taskDao = new model_rdproject_task_rdtask();
			$task = $taskDao->get_d($_GET['objId']);
			$projectId = $task['projectId'];
		} else if ($_GET['objTable'] == 'oa_rd_project_plan') {//�ƻ�
			$planDao = new model_rdproject_plan_rdplan();
			$plan = $planDao->get_d($_GET['objId']);
			$projectId = $plan['projectId'];
		}
		$this->show->assign('projectId', $projectId);
		$this->show->assign('objId', $_GET['objId']);
		$this->show->assign('objTable', $_GET['objTable']);
		$this->show->assign('tabId', $_GET['tabId']); //tabId
		$this->show->assign('jsUrl', $_GET['jsUrl']); //����tab�����js·��
		$this->show->assign('varName', $_GET['varName']); //����tab����ı�������
		$this->show->assign('list', $service->showRdList($rows, true));
		$this->show->display($this->objPath . '_uploadfile-list');
	}

	/**
	 * @desription ��Ӧ�̹����б�
	 * @param tags
	 * @date 2010-11-18 ����08:18:57
	 */
	function c_supplyFile() {
		$this->show->assign('objId', $_GET['objId']);
		//		$this->show->assign ( 'list', $service->showSuppList( $rows,true ) );
		$this->show->assign('objTable', $_GET['objTable']);
		$this->show->display($this->objPath . '_uploadfile-supply-list');
	}

	/**
	 * @desription �ϴ�����
	 * @param tags
	 * @date 2010-12-24 ����16:18:57
	 */
	function c_onloadFile() {
		$this->show->assign('objId', $_GET['objId']);
		//		$this->show->assign ( 'list', $service->showSuppList( $rows,true ) );
		$this->show->assign('objTable', $_GET['objTable']);
		$this->show->display($this->objPath . '_uploadfile-onload-list');
	}

	/*
	 * ���ع�Ӧ����Ϣ����json����
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d();
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 *
	 * �з����Ƹ���
	 */
	function c_pageJsonProject() {
		$service = $this->service;
		$objId = $_GET['objId'];
		unset($_REQUEST['objId']);
		$service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		if (!empty($_REQUEST['objTable'])) {
			$rows = $service->pageProject_d($objId, $_REQUEST['objTable']);
		} else {
			$rows = $service->pageProject_d($objId);
		}
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * @desription ���������б�
	 * @editby zengzx 2011��10��8�� 19:36:10 {$objId}
	 */
	function c_rdPageManage() {
		$objId = isset($_GET['objId']) ? $_GET['objId'] : exit();

		$proCenter = isset($_GET['proCenter']) ? $_GET['proCenter'] : 0;

		$service = $this->service;
		$service->resetParam();
		$service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
		if (isset($_GET['objId'])) {
			unset($this->searchArr['objId']);
			unset($this->searchArr['tabId']);
			$service->searchArr['serviceId'] = $_GET['objId'];
			$service->searchArr['serviceType'] = $_GET['objTable'];
		}
		$rows = $service->page_d();
		$this->pageShowAssign(); //���÷�ҳ��ʾ
		$this->show->assign('objId', $objId);
		//����proCenter url
		if ($proCenter) {
			$objId .= "&proCenter=1";
		}
		$this->show->assign('objIdPlus', $objId);
		$this->show->assign('tabId', $_GET['tabId']); //tabId
		$this->show->assign('objTable', $_GET['objTable']); //tabId
		$this->show->assign('jsUrl', $_GET['jsUrl']); //����tab�����js·��
		$this->show->assign('varName', $_GET['varName']); //����tab����ı�������
		$this->show->assign('list', $service->showRdList($rows));
		//$this->show->display($this->objPath . '_uploadfile-list-manage');
		$this->view('list-manage');
	}

	/**
	 * ajax��ȡ�ļ�
	 */
	function c_getFilelist() {
		$files = $this->service->getFilesByObjId($_POST['serviceId'], $_POST['serviceType']);
		$isShowDel = empty($_POST['isShowDel']) ? true : false;
		$str = $this->service->showFilelist($files, $isShowDel);
		echo util_jsonUtil::iconvGB2UTF($str);
	}

    /**
     * ajax��ȡ�ļ�
     */
    function c_getFileListJson() {
        echo util_jsonUtil::encode($this->service->getFilesByObjId($_POST['serviceId'], $_POST['serviceType']));
    }

	/**
	 * ��������������ط���
	 */
	function c_downAllFile() {
		$this->service->downAllFileByIds($_GET['id'], $_GET['type'], $_GET['filename']);
	}

	/**
	 * ������ȡ
	 */
	function c_getFileAjax() {
		$id = $_POST['id'];//idΪserviceId
		$type = $_POST['type'];
		$rs = $this->service->find(array('serviceId' => $id, 'serviceType' => $type), 'id desc', 'id');
		if ($rs) {
			echo $rs['id'];
		} else {
			echo 0;
		}
	}
}