<?php
/**
 * @author sony
 * @Date 2013年7月10日 17:29:50
 * @version 1.0
 * @description:订票信息控制层
 */
class controller_flights_message_message extends controller_base_action {

	function __construct() {
		$this->objName = "message";
		$this->objPath = "flights_message";
		parent::__construct ();
	}

	/**
	 * 跳转到订票信息列表
	 */
	function c_page() {
		$auditState = isset($_GET['auditState']) ? $_GET['auditState'] : '';
		$this->assign('auditState',$auditState);
		$this->view ( 'list' );
	}

	/**
	 * 列表添加tab
	 */
	function c_pageTab(){
		$this->view ( 'listtab' );
	}

	/**
	 * 跳转到新增订票信息页面
	 */
	function c_toAdd() {
		$this->assign('thisDate',day_date);
        //判断当前部门是否需要省份
        $requireDao = new model_flights_require_require();
        $this->assignFunc($requireDao->deptNeedInfo_d($_SESSION['DEPT_ID']));
		$this->view ( 'add' );
	}

	//生成订票信息页面
	function c_toAddPush(){
		$requireDao=new model_flights_require_require();
		$obj = $requireDao->get_d( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

        //判断当前部门是否需要省份
        $this->assignFunc($requireDao->deptNeedInfo_d($obj['costBelongDeptId']));

		$this->assign("requireNo",$_GET['requireNo']);
		$this->assign("requireId",$_GET['id']);
		$this->assign('thisDate',day_date);
		$this->view("addpush");
	}

	//新增
	function c_add() {
		$obj = $_POST[$this->objName];
		$id = $this->service->add_d ( $_POST [$this->objName]);
		if ($id) {
			//机票管理邮件通知
			if(!empty($obj['email']['TO_ID']) || !empty($obj['email']['ADDIDS'])){
				$this->service->mailDeal_d('flightsInfo',$obj['email']['TO_ID'],array('requireNo' => $obj['requireNo']),$obj['email']['ADDIDS']);
			}
			msgRf ( "添加成功" );
		} else {
			msgRf ( "添加失败" );
		}
	}

	/**
	 * 跳转到编辑订票信息页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
        //判断当前部门是否需要省份
        $requireDao = new model_flights_require_require();
        $this->assignFunc($requireDao->deptNeedInfo_d($obj['costBelongDeptId']));

		$this->assign('businessState',$this->service->rtStatus_d($obj['businessState']));
		$this->view ( 'edit' );
	}

	/**
	 * 跳转到查看订票信息页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('ticketType', $this->service->rtStatus_d($obj['ticketType']));
		$this->assign('ticketTypeHidden', $obj['ticketType']);
		$this->assign('businessState',$this->service->rtStatus_d($obj['businessState']));
		$this->assign('isLow',$this->service->rtYesOrNo_d($obj['isLow']));
		$this->view ( 'view' );
	}

	/**
	 * 跳转到查看Tab订票信息页面
	 */
	function c_toViewTab() {
		$this->assign('id',$_GET['id']);
		$this->view ( 'message-list' );
	}

	//新主表子表审核控制
	function c_confirm() {
		$id = $_POST ['id'];
		if ($this->service->confirm_d ($id)) {
			echo 1;
		} else {
			echo 0;
		}
		exit();
	}

	//反核单
	function c_unconfirm(){
		$id = $_POST ['id'];
		if ($this->service->unconfirm_d ($id)) {
			echo 1;
		} else {
			echo 0;
		}
		exit();
	}

	/**
	 * 改签方法
	 */
	function c_toChange() {
		$this->assignFunc($this->service->get_d($_GET['id']));
		$this->view ( 'change' );
	}

	/**
	 * 改签
	 */
	function c_change(){
		$obj = $_POST[$this->objName];
		$rs = $this->service->change_d ( $obj );
		if ($rs) {
			$this->service->mailDeal_d('flightsChange',$obj['email'][TO_ID],array('requireNo' => $obj['requireNo']),$obj['email']['ADDIDS']);
			msg ( "改签成功" );
		} else {
			msg ( "改签失败" );
		}
	}

	/**
	 * 修改改签
	 */
	function c_toEditChange(){
		$this->assignFunc($this->service->get_d($_GET['id']));
		$this->view ( 'changeedit' );
	}

	/**
	 * 修改改签
	 */
	function c_changeEdit(){
		$object = $_POST[$this->objName];
		if ($this->service->changeEdit_d ( $object )) {
			msg ( '编辑成功！' );
		}else{
			msg ( '编辑失败！' );
		}
	}

	//跳转到退票
	function c_toBack() {
		$this->assignFunc($this->service->get_d($_GET['id']));
		$this->view ( 'back' );
	}

	//添加退票信息
	function c_back() {
		$object = $_POST[$this->objName];
		$rs = $this->service->back_d ( $object );
		if ($rs) {
			//退票邮件处理
			$this->service->mailDeal_d('flightsBack',$object['email']['TO_ID'],array('requireNo' => $object['requireNo']),$object['email']['ADDIDS']);
			msg ( "退票成功" );
		} else {
			msg ( "退票失败" );
		}
	}

	//编辑退票信息
	function c_toEditBack(){
		$this->assignFunc($this->service->get_d($_GET['id']));
		$this->view ( 'backedit' );
	}

	//编辑退票
	function c_editBack(){
		$object = $_POST[$this->objName];
		if ($this->service->editBack_d ( $object )) {
			msg ( '编辑成功！' );
		}else{
			msg ( '编辑失败！' );
		}
	}

	//重写listJsonMessage方法，查询订票信息
	function c_listJsonMessage() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ( "select_default" );
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

    //ajax获取需求信息
    function c_ajaxGet(){
        $id = $_POST['id'];
        $obj = $this->service->find(array('id' => $id));
        echo util_jsonUtil::encode($obj);
	}

    /**
	 * 生成结算订单
	 */
	function c_getConfirmedMsgJson(){
		$msgId = $_REQUEST['ids'];
		//主表信息
		$service = $this->service;
		$rows = $service->filterMes_d( $_REQUEST['ids'] );
		foreach($rows as $k=>$v){
			$rows[$k]['msgId'] = $rows[$k]['id'];
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * 获取列表
	 */
	function c_listHtml(){
		$result = $this->service->getlistHtml_d($_REQUEST);
		echo util_jsonUtil::iconvGB2UTF($result);
	}
	
	/**
	 * 计算列表金额
	 */
	function c_getAllCost(){
		echo $this->service->getAllCost_d($_POST);
	}
}