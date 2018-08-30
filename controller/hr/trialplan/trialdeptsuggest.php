<?php

/**
 * @author Show
 * @Date 2012年9月12日 星期三 16:25:18
 * @version 1.0
 * @description:员工使用部门建议表控制层
 */
class controller_hr_trialplan_trialdeptsuggest extends controller_base_action {

	function __construct() {
		$this->objName = "trialdeptsuggest";
		$this->objPath = "hr_trialplan";
		parent :: __construct();
	}

	/**
	 * 跳转到员工使用部门建议表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增员工使用部门建议表页面
	 */
	function c_toAdd() {
		$this->view('add' ,true);
	}

	/**
	 * 跳转到编辑员工使用部门建议表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit' ,true);
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		$this->checkSubmit(); //检查是否重复提交
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object)) {
			if(isset($_GET['act']) && $_GET['act'] == 'audit'){
				if( $object['hrSalary']!= $object['afterSalary'] ||$object['personLevel'] != $object['beforePersonLv'] ){
					succ_show('controller/hr/trialplan/ewf_index1.php?actTo=ewfSelect&billId=' . $object['id'] . "&billDept=".$object['deptId']);
				}else{
					succ_show('controller/hr/trialplan/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . "&billDept=".$object['deptId']);
				}
			}else{
				msg('保存成功');
			}
		}
	}

	/**
	 * 跳转到查看员工使用部门建议表页面
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
	 * 审批
	 */
	function c_toAudit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('audit');
	}

	//审批后执行
	function c_dealAfterAudit(){
       	$this->service->dealAfterAudit_d( $_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}
}
?>