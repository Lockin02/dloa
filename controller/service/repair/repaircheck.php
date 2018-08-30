<?php

/**
 * @author huangzf
 * @Date 2011年12月2日 10:22:13
 * @version 1.0
 * @description:检测维修任务控制层
 */
class controller_service_repair_repaircheck extends controller_base_action {

	function __construct() {
		$this->objName = "repaircheck";
		$this->objPath = "service_repair";
		parent::__construct ();
	}

	/**
	 * 跳转到下达检测维修列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 *
	 * 跳转到检测维修任务列表
	 */
	function c_taskPage() {
		$this->view ( 'task-list' );
	}
	
	/**
	 *
	 * 跳转到个人检测维修任务列表
	 */
	function c_myTaskPage() {
		$this->assign ( 'repairUserCode', $_SESSION ['USER_ID'] );
		$this->view ( 'mytask-list' );
	}
	
	/**
	 * AJAX 打回重检
	 */
	function c_ajaxStateBack(){
		try {
			$this->service->stateBack_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}


	/**
	 * 跳转到新增检测维修任务页面
	 */

	function c_toAdd() {
		$this->permCheck ( $_GET ['id'], "service_repair_repairapply" ); //安全校验
		$rs = $this->service->getRepairItems_d ( $_GET ['id'] );
		//从配置文件读取检测人员信息
		include(WEB_TOR.'includes/config.php');
		if(isset($defaultRepairUser[$_SESSION ['USER_ID']])){
			$userDao = new model_deptuser_user_user();
			$user = $userDao->getUserById($defaultRepairUser[$_SESSION ['USER_ID']]);
	
			$this->assign("repairDeptName",$user['DEPT_NAME']);
			$this->assign("repairDeptCode",$user['DEPT_ID']);
			$this->assign("repairUserName",$user['USER_NAME']);
			$this->assign("repairUserCode",$user['USER_ID']);
		}else{
			$this->assign("repairDeptName",'');
			$this->assign("repairDeptCode",'');
			$this->assign("repairUserName",'');
			$this->assign("repairUserCode",'');
		}
		$this->assign ( 'issuedUserName', $_SESSION ['USERNAME'] );
		$this->assign ( 'issuedUserCode', $_SESSION ['USER_ID'] );
		$this->assign ( 'auditDate', day_date );
		$this->assign ( "applyDocCode", $rs ['docCode'] );
		$this->assign ( "applyDocId", $rs ['id'] );
		$this->assign ( "itemsList", $this->service->showItemAtAdd ( $rs ['items'] ) );
		$this->assign ( "issuedTime", date ( 'Y-m-d H:i:s' ) );

		$this->view ( 'add' );
	}

	/**
	 * 跳转到检测反馈页面
	 */

	function c_toFeedback() {
		$this->permCheck (); //安全校验
		$obj = $this->processRelInfo($_GET ['id']);
		//序列号添加超链接
		$obj['serilnoName'] = '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=service_repair_repaircheck&action=toSerilnoNameLog&serilnoName=' . $obj['serilnoName'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100\',1)">' . $obj['serilnoName'] . '</a>';
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'feedback' );
	}

	/**
	 * 保存或提交检测反馈信息
	 */
	function c_feedback($isEditInfo = false) {
		$service = $this->service;
		$object = $_POST [$this->objName];
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'submit'){
			//提交时更改单据状态为【已检测】
			$object['docStatus']="YJC";
		}
		if ($service->feedback_d($object)) {
			if($actType == 'submit'){
				msg ( "反馈成功!" );
			}else{
				msg ( "保存成功!" );
			}
		} else {
			if($actType == 'submit'){
				msg ( "反馈失败!" );
			}else{
				msg ( "保存失败!" );
			}
		}
	}

	/**
	 * 跳转到检测是否同意维修页面
	 */

	function c_toIsagree() {
		$this->permCheck (); //安全校验
		$obj = $this->processRelInfo($_GET ['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'isagree' );
	}
	/**
	 * 是否同意维修
	 */
	function c_isagree($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '保存成功！';
			msg ( $msg );
		}
	}

	/**
	 * 跳转到确认完成维修页面
	 */
	function c_toConfirm() {
		$this->permCheck (); //安全校验
		$obj = $this->processRelInfo($_GET ['id']);
		$this->assign ( 'auditDate', day_date );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'confirm' );
	}
	/**
	 * 确认完成维修
	 */
	function c_confirm() {
		$object = $_POST [$this->objName];
		$object ['docStatus'] = "YWX";
		if ($this->service->edit_d ( $object )) {
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '保存成功！';
			msg ( $msg );
		}
	}

	/**
	 * 跳转到编辑检测维修任务页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * 跳转到查看检测维修任务页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->processRelInfo($_GET ['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 *
	 * 跳转到通过申请单清单id查看检测任务页面
	 */
	function c_toViewAtApply() {
		$service = $this->service;
		$service->searchArr = array ("applyItemId" => $_GET ['applyItemId'] );
		$checkArr = $service->listBySqlId ();
		foreach ( $checkArr [0] as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		if ($id) {
			echo "<script>alert('下达成功!');window.opener.window.show_page();window.close();</script>";
		} else {
			echo "<script>alert('下达失败！');window.opener.window.show_page();window.close();</script>";
		}
	}
	
	/**
	 * 跳转到序列号的历史维修记录页面
	 */
	function c_toSerilnoNameLog() {
		$this->assign ( 'repairUserCode', $_SESSION ['USER_ID'] );
		$this->assign ( 'serilnoName', $_GET['serilnoName'] );
		$this->view ( 'serilnonamelog-list' );
	}
	
	/**
	 * 跳转到修改检测处理方法页面
	 */
	function c_toEditCheckInfo(){
		$this->permCheck();//安全校验
		$service = $this->service;
		$obj = $service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);
		//获取通用邮件配置信息并设置默认发送人
		$mailInfo = $service->getMailUser_d('editCheckInfo');
		$this->assign('TO_ID',$mailInfo['defaultUserId']);
		$this->assign('TO_NAME',$mailInfo['defaultUserName']);
		$this->display ('editcheckinfo' );
	}
	
	/**
	 * 修改检测处理方法
	 */
	function c_editCheckInfo() {
		if ($this->service->editCheckInfo_d ( $_POST [$this->objName]) ){
			msg ( '编辑成功！' );
		}
	}
	
	/**
	 * 处理关联信息
	 */
	function processRelInfo($id) {
		$service = $this->service;
		$obj = $service->get_d ($id);
		//获取关联维修申请表信息
		$repairapplyDao = new model_service_repair_repairapply();
		$applyitemDao = new model_service_repair_applyitem();
		$repairapplyInfo = $repairapplyDao->find(array('id'=>$obj['applyDocId']),null,'customerName,contactUserName,telephone');
		$applyitemInfo = $applyitemDao->find(array('id'=>$obj['applyItemId']),null,'isGurantee');
		$obj['customerName'] = $repairapplyInfo['customerName'];
		$obj['contactUserName'] = $repairapplyInfo['contactUserName'];
		$obj['telephone'] = $repairapplyInfo['telephone'];
		$obj['isGurantee'] = $applyitemInfo['isGurantee'];
		//维修申请单编号添加超链接
		$skey = $this->md5Row($obj['applyDocId'],'service_repair_repairapply',null);
		$obj['applyDocCode'] = '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=service_repair_repairapply&action=toView&id=' . $obj['applyDocId'] . '&skey='.$skey.'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100\',1)">' . $obj['applyDocCode'] . '</a>';
		//获取附件信息
		$obj['file'] = $service->getFilesByObjId ($id,true,$this->service->tbl_name);
		
		return $obj;
	}
}