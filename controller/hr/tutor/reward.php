<?php

/**
 * @author Administrator
 * @Date 2012-08-29 17:09:19
 * @version 1.0
 * @description:导师奖励管理控制层
 */
class controller_hr_tutor_reward extends controller_base_action {

	function __construct() {
		$this->objName = "reward";
		$this->objPath = "hr_tutor";
		parent :: __construct();
	}

	/**
	 * 跳转到导师奖励管理列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增导师奖励管理页面
	 */
	function c_toAdd() {
		$this->assign('dept', $_SESSION['DEPT_NAME']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->view('hradd' ,true);
	}

	/**
	 * 跳转到编辑导师奖励管理页面
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
	 * 跳转到查看导师奖励管理页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		$obj['createTime'] = date("Y-m-d", strtotime($obj['createTime']));
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * 跳转到 导师奖励（部门） 查看导师奖励页面
	 */
	function c_toViewByDept() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		$obj['createTime'] = date("Y-m-d", strtotime($obj['createTime']));
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('deptId',$_SESSION['DEPT_ID']);
		$this->view('viewbydept');
	}

	function c_toRead() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('read');
	}

	/**
	* 导师奖励（部门列表）
	*/
	function c_toDeptList() {
		$this->view('deptlist');
	}

	/**
	 * 导师奖励（HR列表）
	 */
	function c_toHrList() {
		$this->view('hrlist');
	}

	/**
	 * 跳转到确认奖励发放页面
	 */
	 function c_toGrant(){
	 	$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		$obj['createTime'] = date("Y-m-d", strtotime($obj['createTime']));
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('grant');
	}

	/**
	 * 按部门获取当前 分数在80分以上的 未受到奖励的导师
	 */
	function c_rewardInfoJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		//获取数据

		$rows = $service->getRewardInfo_d();
		//数据加入安全码

		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * 获取当前 分数在80分以上的 未受到奖励的导师(不按部门过滤)
	 */
	function c_rewardInfo() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		//获取数据

		$rows = $service->getRewardList_d();
		//数据加入安全码

		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		$id = $this->service->add_d($_POST[$this->objName], $isAddInfo);
		$msg = $_POST["msg"] ? $_POST["msg"] : '添加成功！';
		if ($id) {
			//			msg ( $msg );
			succ_show('controller/hr/tutor/ewf_reward.php?actTo=ewfSelect&billId=' . $id);
		}

		//$this->listDataDict();
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		//		$this->permCheck (); //安全校验
		$object = $_POST[$this->objName];
		$id = $this->service->edit_d($object, $isEditInfo);
		if ($id) {
			//			msg ( '编辑成功！' );
			succ_show('controller/hr/tutor/ewf_reward.php?actTo=ewfSelect&billId=' . $id);
		}
	}

	/**
	 * 导师奖励方案通过后发送邮件
	 */
	function c_confirmExa() {
		if (!empty ($_GET['spid'])) {
			$otherdatas = new model_common_otherdatas();
			$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);

			if ($folowInfo['examines'] == "ok") {
				$obj = $this->service->get_d($folowInfo['objId']);
				//根据奖励id获取从表数据反写奖励状态
				$this->service->updateRewardState($folowInfo['objId'], "1");
				//发送邮件给导师
				//获取默认发送人
				include (WEB_TOR . "model/common/mailConfig.php");
				$addMsg = "您好 ：</br>       导师奖励方案已通过审批.奖励方案编号：“" . $obj['code'] . "”。请及时发放奖励";
				$emailDao = new model_common_mail();
				$emailDao->mailClear('导师奖励方案', $mailUser['tutorReward']['sendUserId'], $addMsg);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * 确认奖励发放
	 */
	function c_conGrant() {

		$object = $_POST[$this->objName];

		if($this->service->conGrant_d($object)){
				msg ( '提交成功！' );
		}
	}

	/**
	 * 确认发布奖励相关信息
	 */
	function c_publish() {
		try {
			$this->service->publish_d($_POST['id']);
			echo 1;
		} catch (Exception $e) {
			echo 0;
		}
	}
}
?>