<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:05
 * @version 1.0
 * @description:借试用归还管理控制层
 */
class controller_projectmanagent_borrowreturn_borrowreturn extends controller_base_action {

	function __construct() {
		$this->objName = "borrowreturn";
		$this->objPath = "projectmanagent_borrowreturn";
		parent::__construct ();
	}

	/**
	 * 跳转到借试用归还管理列表
	 */
    function c_page() {
		$this->view('list');
    }

    /**
     * 借试用tab页
     */
    function c_viewList(){
        $this->assign("borrowId" , $_GET['borrowId']);
        $this->view('viewList');
    }

    /**
     * 待确认赔偿的列表
     */
    function c_waitCompensateList(){
        $this->view('waitCompensateList');
    }

    /**
     * 我的借用归还列表
     */
    function c_mylist(){
        $this->assign("userId" , $_SESSION['USER_ID']);
        $this->view('mylist');
    }

    /**
	 * 跳转到新增借试用归还管理页面
	 */
	function c_toAdd() {
		$borrowId = $_GET['id'];
		$borrowDao = new model_projectmanagent_borrow_borrow();
		$borrowArr = $borrowDao->get_d($borrowId);
		//数据渲染
		$this->assignFunc($borrowArr);
		$this->showDatadicts(array('applyType' => 'JYGHSQLX'));
		// 人员处理，若不存在销售人员(员工借试用单据)，则取单据创建人
		$salesId = empty($borrowArr['salesNameId']) ? $borrowArr['createId'] : $borrowArr['salesNameId'];
		$salesName = empty($borrowArr['salesName']) ? $borrowArr['createName'] : $borrowArr['salesName'];
		// 获取部门信息
		$otherdatasDao = new model_common_otherdatas();
		$rs = $otherdatasDao->getUserDatas($salesId);
		$deptId = $rs['DEPT_ID'];
		$deptName = $rs['DEPT_NAME'];
		$this->assign('deptId',$deptId);
		$this->assign('deptName',$deptName);
        $this->assign('salesName',$salesName);
        $this->assign('salesId',$salesId);
		$this->view ( 'add' ,true);
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$this->checkSubmit();
        $object = $_POST[$this->objName];
        $isTempSno = $this->service->checkHasTempSno_d($object);
        $object['ExaStatus'] = $isTempSno ? '待提交' : '免审';
        $id = $this->service->add_d ($object,true);
		if ($id) {
        	//加入临时序列号验证
        	if($isTempSno){
            	exit("<script language=javascript>alert('单据包含临时序列号，需要提交审批');location.replace('".'controller/projectmanagent/borrowreturn/borrowreturn_ewf.php?actTo=ewfSelect&billId='.$id."');</script>");
            }else{
                msgRf ( '提交成功！' );
            }
		}
	}

    /**
	 * 跳转到编辑借试用归还管理页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);
		$this->showDatadicts(array('applyType' => 'JYGHSQLX'),$obj['applyType']);
		$this->view ( 'edit');
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		$this->checkSubmit();
        $object = $_POST[$this->objName];
        $isTempSno = $this->service->checkHasTempSno_d($object);
        $object['ExaStatus'] = $isTempSno ? '待提交' : '免审';
		if ($this->service->edit_d ( $object )) {
        	//加入临时序列号验证
            if($isTempSno){
            	exit("<script language=javascript>alert('单据包含临时序列号，需要提交审批');location.replace('".'controller/projectmanagent/borrowreturn/borrowreturn_ewf.php?actTo=ewfSelect&billId='.$object['id']."');</script>");
            }else{
            	msgRf ( '提交成功！' );
            }
		}
	}

    /**
     * 修改对象 - 待确认归还申请列表用
     */
    function c_toEditManage(){
        $this->permCheck (); //安全校验
        $obj = $this->service->get_d ( $_GET ['id'] );
        $this->assignFunc($obj);
        $this->showDatadicts(array('applyType' => 'JYGHSQLX'),$obj['applyType']);
        $this->assign('sendUserId', $obj['createId']);
        $this->assign('sendName', $obj['createName']);
        $this->view ( 'editmanage');
    }

    /**
     * 修改对象
     */
    function c_editManage(){
        if ($this->service->editManage_d($_POST[$this->objName])){
            msgRf ( '修改成功！' );
        }
    }

    /**
	 * 跳转到查看借试用归还管理页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);
		$this->view ( 'view' );
	}

    /**
	 * 跳转到查看借试用归还管理页面
	 */
	function c_toAudit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);
		$this->view ( 'audit' );
	}

    /**
     * 列表页提交申请
     */
    function c_ajaxSub() {
		try {
			$this->service->ajaxSub_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * 确认列表--交付部
	 */
	function c_confirmList(){
		$this->view('confirmList');
	}

	/**
	 * 确认页面
	 */
    function c_confirmView(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('equIdArr',$_GET['equIdArr']);
		$this->view ( 'confirmView' );
	}

    /**
	 * 确认方法
	 */
	function c_confirmEdit() {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		$disDao = new model_projectmanagent_borrowreturn_borrowreturnDis();
        $id = $disDao->add_d ( $object, true );
        $this->service->updateReturnState_d($object['id']);
		if ($id) {
			msgRf ( '确认成功！' );
		}
	}

	/**
	 * 跳转到打回单据页面
	 */
	function c_toDisposeback(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->assign('sendUserId', $obj['createId']);
		$this->assign('sendName', $obj['createName']);

		$this->view('back');
	}

    /**
     * 打回单据
     */
    function c_disposeback() {
    	$object = $_POST[$this->objName];
		if ($this->service->disposeback_d($object)) {
			msg('打回成功');
		}else{
			msg('打回失败');
		}
	}

    /**
     * ajax 更新赔偿状态
     */
    function c_ajaxState() {
		try {
			$this->service->updateState_d ( $_POST ['id'],$_POST['state'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * 审批处理
	 */
	function c_dealAfterAudit(){
       	$this->service->workflowCallBack($_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 仓库人员接收确认
	 */
	function c_ajaxReceive(){
		echo $this->service->ajaxReceive_d($_GET['id']) ? 1 : 0;
	}

	/**
	 * 我的确认列表--销售
	 */
	function c_myConfirmList(){
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->view('myConfirmList');
	}

	/**
	 * 跳转到销售确认页面
	 */
	function c_toSaleConfirm() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);
		$this->view ( 'saleConfirm' );
	}

	/**
	 * 销售确认单据
	 */
	function c_saleConfirm() {
		if ($this->service->saleConfirm_d($_POST[$this->objName])) {
			msg('确认成功');
		}else{
			msg('确认失败');
		}
	}
}