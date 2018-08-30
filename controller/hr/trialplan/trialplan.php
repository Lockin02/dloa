<?php

/**
 * @author Show
 * @Date 2012年8月31日 星期五 14:53:01
 * @version 1.0
 * @description:员工试用培训计划控制层
 */
class controller_hr_trialplan_trialplan extends controller_base_action {

	function __construct() {
		$this->objName = "trialplan";
		$this->objPath = "hr_trialplan";
		parent :: __construct();
	}

	/**
	 * 跳转到员工试用培训计划列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增员工试用培训计划页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msgRf ( $msg );
		}
	}

	/**
	 * 模板选择页
	 */
	function c_toSelectModel(){
		$this->assignFunc($_GET);
		$this->view('selectmodel');
	}

	/**
	 * 跳转到编辑员工试用培训计划页面
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
	 * 跳转到查看员工试用培训计划页面
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
     * 获取我的培养计划
     */
    function c_getMyPlans(){
        $userId = isset($_POST['userAccount']) ? $_POST['userAccount'] : $_SESSION['USER_ID'];
        $this->service->searchArr = array(
            'memberId' => $userId
        );
        $this->sort = "c.createTime";
        $rs = $this->service->list_d();
        if($rs){
            foreach($rs as $key => $val){
                $newArr[$key]['text'] = $val['planName'];
                $newArr[$key]['value'] = $val['id'];
            }
            echo util_jsonUtil::encode ( $newArr);
        }
    }
}
?>