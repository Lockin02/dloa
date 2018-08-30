<?php

/**
 * @author Show
 * @Date 2012年8月20日 星期一 20:19:03
 * @version 1.0
 * @description:任职资格等级认证评价表模板控制层
 */
class controller_hr_baseinfo_certifytemplate extends controller_base_action {

	function __construct() {
		$this->objName = "certifytemplate";
		$this->objPath = "hr_baseinfo";
		parent :: __construct();
	}

	/******************* 列表信息 **********************/

	/**
	 * 跳转到任职资格等级认证评价表模板列表
	 */
	function c_page() {
		$this->view('list');
	}

	/******************** 增删改查 **********************/

	/**
	 * 跳转到新增任职资格等级认证评价表模板页面
	 */
	function c_toAdd() {
		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'));//申请发展通道
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'));//申请级别
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'));//申请级等
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
	 * 跳转到编辑任职资格等级认证评价表模板页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'),$obj['careerDirection']);//申请发展通道
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'),$obj['baseLevel']);//申请级别
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'),$obj['baseGrade']);//申请级等

		$this->view('edit');
	}

	/**
	 * 跳转到查看任职资格等级认证评价表模板页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('status',$this->service->rtStatus_d($obj['status']));

		$this->view('view');
	}

	/******************** 业务逻辑 ***********************/
	/**
	 * 判断是否已存在另外一个在启用的模版
	 */
	function c_isAnotherTemplate(){
		$id = isset($_POST['id']) ? $_POST['id'] : null;
		$careerDirection = isset($_POST['careerDirection']) ? $_POST['careerDirection'] : null;
		$baseLevel = isset($_POST['baseLevel']) ? $_POST['baseLevel'] : null;
		$baseGrade = isset($_POST['baseGrade']) ? $_POST['baseGrade'] : null;

		$rs = $this->service->isAnotherTemplate_d($careerDirection,$baseLevel,$baseGrade,$id);

		if($rs){
			echo $rs;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * 关闭模版
	 */
	function c_close(){
		$id = $_POST['id'];
		if($this->service->close_d($id)){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}
}
?>