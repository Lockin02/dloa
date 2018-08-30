<?php

/**
 * @author Show
 * @Date 2012年8月23日 星期四 9:40:13
 * @version 1.0
 * @description:任职资格等级认证评价表控制层
 */
class controller_hr_certifyapply_cassess extends controller_base_action {

	function __construct() {
		$this->objName = "cassess";
		$this->objPath = "hr_certifyapply";
		parent :: __construct();
	}

	/******************* 列表部分 ********************/

	/**
	 * 跳转到任职资格等级认证评价表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 个人列表
	 */
	function c_myList(){
		$this->view('listmy');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_REQUEST['userAccount'] = $_SESSION['USER_ID'];
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 提供审核表数据
	 */
	function c_listJsonForResult(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ('select_forresult');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/******************* 增删改查 *******************/

	/**
	 * 跳转到新增任职资格等级认证评价表页面
	 */
	function c_toAdd() {
		//评估表重复生成验证
		$obj = $this->service->find(array('applyId' => $_GET['applyId']),null,'id');
		if($obj){
			msgRf('对应评价表已经生成，不能继续生成');
		}

		//获取申请信息，主要是申请的通道，级等等相关信息
		$applyInfo = $this->service->getApplyInfo_d($_GET['applyId']);

		//根据申请信息获取对应模板
		$template = $this->service->getTemplate_d($applyInfo['careerDirection'],$applyInfo['baseLevel'],$applyInfo['baseGrade']);
		if(empty($template)){
			msgRf('未配置 '.$applyInfo['careerDirectionName'].'/'.$applyInfo['baseLevelName'].'/'.$applyInfo['baseGradeName'].' 的模板，请先配置');
		}else{
			$this->assign('modelId',$template['id']);
			$this->assign('modelName',$template['modelName']);
		}

		//渲染申请信息
		$this->assignFunc($applyInfo);

		$this->view('add');
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			if($_GET['act']){
				succ_show('controller/hr/certifyapply/ewf_index.php?actTo=ewfSelect&billId=' . $id );
			}else{
				msgRf('保存成功');
			}
		}
	}

	/**
	 * 跳转到编辑任职资格等级认证评价表页面
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
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object )) {
			if($_GET['act']){
				succ_show('controller/hr/certifyapply/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] );
			}else{
				msgRf('保存成功');
			}
		}
	}

	/**
	 * 编辑明细 - 上传评价材料
	 */
	function c_toEditDetail(){
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('editdetail');
	}

	/**
	 * 跳转到查看任职资格等级认证评价表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('status',$this->service->rtStatus_c($obj['status']));
		$this->view('view');
	}

	/**
	 * 跳转到查看任职资格等级认证评价表页面
	 */
	function c_toAudit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('status',$this->service->rtStatus_c($obj['status']));
		$this->view('audit');
	}

	/**
	 * 指定评委
	 */
	function c_toSetManager() {
		$userArr = $this->service->getAssessUser_d($_GET['id']);
		$this->assignFunc($userArr);

		$this->assign('id',$_GET['id']);

		$this->view('setmanager');
	}

	/**
	 * 更新评委
	 */
	function c_setManager(){
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->setManager_d ( $object )) {
			msg ( '指定成功！' );
		}
	}

	/**
	 * 评分分数录入
	 */
	function c_toInScore(){
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//表头生成
		$thead = $this->service->initHead_v($obj);
		$this->assign('thead',$thead);

		//表内容生成
		$tBody = $this->service->initBody_v($obj);
		$this->assign('tbody',$tBody[0]);
		$this->assign('countNum',$tBody[1]);

		$this->assign('thisCols',$this->service->rtColspan_c($obj['managerId'],$obj['memberId']));

		$this->view('inscore');
	}

	/**
	 * 评分分数录入
	 */
	function c_inScore(){
		$object = $_POST [$this->objName];
		if ($this->service->inScore_d ( $object )) {
			msg ( '保存成功！' );
		}
	}

	/**
	 * 查看评分
	 */
	function c_toViewScore(){
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//表头生成
		$thead = $this->service->initHead_v($obj);
		$this->assign('thead',$thead);

		//表内容生成
		$tBody = $this->service->initBodyView_v($obj);
		$this->assign('tbody',$tBody);

		$this->assign('thisCols',$this->service->rtColspan_c($obj['managerId'],$obj['memberId']));

		$this->view('viewscore');
	}

	/************************** 业务逻辑 *******************/
	/**
	 * 提交认证准备
	 */
	function c_handUp(){
		$id = $_POST['id'];
		$rs = $this->service->handUp_d($id);
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * 审批完成后回调方法
	 */
	function c_dealAfterAudit(){
       	$this->service->dealAfterAudit_d( $_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}
}
?>