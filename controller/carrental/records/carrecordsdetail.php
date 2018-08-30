<?php

/**
 * @author Show
 * @Date 2011年12月27日 星期二 19:08:21
 * @version 1.0
 * @description:用车明细
 */
class controller_carrental_records_carrecordsdetail extends controller_base_action {

	function __construct() {
		$this->objName = "carrecordsdetail";
		$this->objPath = "carrental_records";
		parent :: __construct();
	}

	/**
	 * 跳转到用车明细
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增用车明细
	 */
	function c_toAdd() {
		$this->view('add');
	}

    /**
     * 日志新增临聘人员信息
     */
    function c_toAddInWorklog(){
        $worklogId = $_GET['worklogId'];
        //获取日志中的其他信息
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        $this->view('addinworklog');
    }

    /**
     * 日志新增临聘信息
     */
    function c_addInWorklog(){
        $object = $_POST[$this->objName];
        $countMoneyArr = $this->service->addBatch_d($object);
        if($countMoneyArr){
            $rtValue = util_jsonUtil::encode ( $countMoneyArr );
            echo "<script>alert('保存成功');if(window.opener){window.opener.returnValue = '$rtValue';}window.returnValue = '$rtValue';window.close();</script>";
        }else{
            echo "<script>alert('保存失败');window.close();</script>";
        }
        exit();
    }

	/**
	 * 跳转到编辑用车明细
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
		 * 跳转到查看用车明细
		 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * 重写列表
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam($_POST);
		$service->sort = 'c.useDate';
		$service->asc = false;
		$rows = $service->list_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * 获取用车记录数据返回json
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