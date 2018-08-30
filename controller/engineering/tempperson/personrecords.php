<?php
/**
 * @author Show
 * @Date 2012年7月17日 星期二 19:13:24
 * @version 1.0
 * @description:临聘人员记录控制层
 */
class controller_engineering_tempperson_personrecords extends controller_base_action {

	function __construct() {
		$this->objName = "personrecords";
		$this->objPath = "engineering_tempperson";
		parent :: __construct();
	}

	/*
	 * 跳转到临聘人员记录列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增临聘人员记录页面
	 */
	function c_toAdd() {
		$this->assign('thisDate',day_date);
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
        $countMoney = $this->service->addBatch_d($object);
        if($countMoney){
            echo "<script>alert('保存成功');if(window.opener){window.opener.returnValue = $countMoney;}window.returnValue = $countMoney;window.close();</script>";
        }else{
        	echo "<script>alert('保存失败');window.close();</script>";
        }
        exit();
    }

	/**
	 * 跳转到编辑临聘人员记录页面
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
	 * 跳转到查看临聘人员记录页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

    /*
	 * 跳转到测试卡使用记录列表
	 */
    function c_pageForProject() {
    	$this->assign('projectId',$_GET['projectId']);
		$this->view('listforproject');
    }
}
?>