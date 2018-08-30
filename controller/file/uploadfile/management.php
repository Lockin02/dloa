<?php

/**
 * 附件管理控制层
 */
class controller_file_uploadfile_management extends controller_base_action
{

	function __construct() {
		$this->objName = "management";
		$this->objPath = "file_uploadfile";
		parent::__construct();
	}

	/**
	 * 附件上传接口
	 */
	function c_toAddFile() {
		$_POST['serviceId'] = isset($_POST['serviceId']) ? $_POST['serviceId'] : '';
		$_POST['serviceNo'] = isset($_POST['serviceNo']) ? $_POST['serviceNo'] : '';
		$_POST['serviceType'] = isset($_POST['serviceType']) ? $_POST['serviceType'] : '';
		$_POST['isTemp'] = isset($_POST['isTemp']) ? $_POST['isTemp'] : '';
		$_POST['typeId'] = isset($_POST['typeId']) ? $_POST['typeId'] : '';
		$_POST['typeName'] = isset($_POST['typeId']) ? util_jsonUtil::iconvUTF2GB($_POST['typeName']) : '';
		//附件上传处理
		echo util_jsonUtil::encode($this->service->uploadFileAction($_FILES, $_POST));
	}

	/**
	 * 文件下载接口
	 */
	function c_toDownFile() {
		$this->service->downFile2($_GET['inDocument'], $_GET['newName'], $_GET['originalName']);
	}

	/*
	 * 通过附件id下载附件
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
	 * 重写ajax方式删除单个附件
	 * 1.删除附件文件
	 * 2.删除数据库记录
	 */
	function c_ajaxdelete() {
		try {
			$service = $this->service;
			$service->getParam($_POST); //设置前台获取的参数信息
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
	 * @desription 研发附件列表
	 */
	function c_rdpage() {
		$service = $this->service;
		$service->resetParam();
		$service->getParam($_GET); //设置前台获取的参数信息
		if (isset($_GET['objId'])) {
			unset($this->searchArr['objId']);
			unset($this->searchArr['tabId']);
			$service->searchArr['serviceId'] = $_GET['objId'];
			$service->searchArr['serviceType'] = $_GET['objTable'];
		}
		$rows = $service->page_d();

		$projectId = $_GET['objId'];
		if ($_GET['objTable'] == 'oa_rd_task') {//任务
			$taskDao = new model_rdproject_task_rdtask();
			$task = $taskDao->get_d($_GET['objId']);
			$projectId = $task['projectId'];
		} else if ($_GET['objTable'] == 'oa_rd_project_plan') {//计划
			$planDao = new model_rdproject_plan_rdplan();
			$plan = $planDao->get_d($_GET['objId']);
			$projectId = $plan['projectId'];
		}
		$this->show->assign('projectId', $projectId);
		$this->show->assign('objId', $_GET['objId']);
		$this->show->assign('objTable', $_GET['objTable']);
		$this->show->assign('tabId', $_GET['tabId']); //tabId
		$this->show->assign('jsUrl', $_GET['jsUrl']); //设置tab数组的js路径
		$this->show->assign('varName', $_GET['varName']); //设置tab数组的变量名称
		$this->show->assign('list', $service->showRdList($rows, true));
		$this->show->display($this->objPath . '_uploadfile-list');
	}

	/**
	 * @desription 供应商管理列表
	 * @param tags
	 * @date 2010-11-18 下午08:18:57
	 */
	function c_supplyFile() {
		$this->show->assign('objId', $_GET['objId']);
		//		$this->show->assign ( 'list', $service->showSuppList( $rows,true ) );
		$this->show->assign('objTable', $_GET['objTable']);
		$this->show->display($this->objPath . '_uploadfile-supply-list');
	}

	/**
	 * @desription 上传附件
	 * @param tags
	 * @date 2010-12-24 下午16:18:57
	 */
	function c_onloadFile() {
		$this->show->assign('objId', $_GET['objId']);
		//		$this->show->assign ( 'list', $service->showSuppList( $rows,true ) );
		$this->show->assign('objTable', $_GET['objTable']);
		$this->show->display($this->objPath . '_uploadfile-onload-list');
	}

	/*
	 * 返回供应商信息附件json数据
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam($_REQUEST); //设置前台获取的参数信息
		$rows = $service->page_d();
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 *
	 * 研发定制附件
	 */
	function c_pageJsonProject() {
		$service = $this->service;
		$objId = $_GET['objId'];
		unset($_REQUEST['objId']);
		$service->getParam($_REQUEST); //设置前台获取的参数信息
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
	 * @desription 附件管理列表
	 * @editby zengzx 2011年10月8日 19:36:10 {$objId}
	 */
	function c_rdPageManage() {
		$objId = isset($_GET['objId']) ? $_GET['objId'] : exit();

		$proCenter = isset($_GET['proCenter']) ? $_GET['proCenter'] : 0;

		$service = $this->service;
		$service->resetParam();
		$service->getParam($_GET); //设置前台获取的参数信息
		if (isset($_GET['objId'])) {
			unset($this->searchArr['objId']);
			unset($this->searchArr['tabId']);
			$service->searchArr['serviceId'] = $_GET['objId'];
			$service->searchArr['serviceType'] = $_GET['objTable'];
		}
		$rows = $service->page_d();
		$this->pageShowAssign(); //设置分页显示
		$this->show->assign('objId', $objId);
		//加上proCenter url
		if ($proCenter) {
			$objId .= "&proCenter=1";
		}
		$this->show->assign('objIdPlus', $objId);
		$this->show->assign('tabId', $_GET['tabId']); //tabId
		$this->show->assign('objTable', $_GET['objTable']); //tabId
		$this->show->assign('jsUrl', $_GET['jsUrl']); //设置tab数组的js路径
		$this->show->assign('varName', $_GET['varName']); //设置tab数组的变量名称
		$this->show->assign('list', $service->showRdList($rows));
		//$this->show->display($this->objPath . '_uploadfile-list-manage');
		$this->view('list-manage');
	}

	/**
	 * ajax获取文件
	 */
	function c_getFilelist() {
		$files = $this->service->getFilesByObjId($_POST['serviceId'], $_POST['serviceType']);
		$isShowDel = empty($_POST['isShowDel']) ? true : false;
		$str = $this->service->showFilelist($files, $isShowDel);
		echo util_jsonUtil::iconvGB2UTF($str);
	}

    /**
     * ajax获取文件
     */
    function c_getFileListJson() {
        echo util_jsonUtil::encode($this->service->getFilesByObjId($_POST['serviceId'], $_POST['serviceType']));
    }

	/**
	 * 附件批量打包下载方法
	 */
	function c_downAllFile() {
		$this->service->downAllFileByIds($_GET['id'], $_GET['type'], $_GET['filename']);
	}

	/**
	 * 附件获取
	 */
	function c_getFileAjax() {
		$id = $_POST['id'];//id为serviceId
		$type = $_POST['type'];
		$rs = $this->service->find(array('serviceId' => $id, 'serviceType' => $type), 'id desc', 'id');
		if ($rs) {
			echo $rs['id'];
		} else {
			echo 0;
		}
	}
}