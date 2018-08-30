<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:05
 * @version 1.0
 * @description:�����ù黹������Ʋ�
 */
class controller_projectmanagent_borrowreturn_borrowreturn extends controller_base_action {

	function __construct() {
		$this->objName = "borrowreturn";
		$this->objPath = "projectmanagent_borrowreturn";
		parent::__construct ();
	}

	/**
	 * ��ת�������ù黹�����б�
	 */
    function c_page() {
		$this->view('list');
    }

    /**
     * ������tabҳ
     */
    function c_viewList(){
        $this->assign("borrowId" , $_GET['borrowId']);
        $this->view('viewList');
    }

    /**
     * ��ȷ���⳥���б�
     */
    function c_waitCompensateList(){
        $this->view('waitCompensateList');
    }

    /**
     * �ҵĽ��ù黹�б�
     */
    function c_mylist(){
        $this->assign("userId" , $_SESSION['USER_ID']);
        $this->view('mylist');
    }

    /**
	 * ��ת�����������ù黹����ҳ��
	 */
	function c_toAdd() {
		$borrowId = $_GET['id'];
		$borrowDao = new model_projectmanagent_borrow_borrow();
		$borrowArr = $borrowDao->get_d($borrowId);
		//������Ⱦ
		$this->assignFunc($borrowArr);
		$this->showDatadicts(array('applyType' => 'JYGHSQLX'));
		// ��Ա������������������Ա(Ա�������õ���)����ȡ���ݴ�����
		$salesId = empty($borrowArr['salesNameId']) ? $borrowArr['createId'] : $borrowArr['salesNameId'];
		$salesName = empty($borrowArr['salesName']) ? $borrowArr['createName'] : $borrowArr['salesName'];
		// ��ȡ������Ϣ
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
	 * �����������
	 */
	function c_add() {
		$this->checkSubmit();
        $object = $_POST[$this->objName];
        $isTempSno = $this->service->checkHasTempSno_d($object);
        $object['ExaStatus'] = $isTempSno ? '���ύ' : '����';
        $id = $this->service->add_d ($object,true);
		if ($id) {
        	//������ʱ���к���֤
        	if($isTempSno){
            	exit("<script language=javascript>alert('���ݰ�����ʱ���кţ���Ҫ�ύ����');location.replace('".'controller/projectmanagent/borrowreturn/borrowreturn_ewf.php?actTo=ewfSelect&billId='.$id."');</script>");
            }else{
                msgRf ( '�ύ�ɹ���' );
            }
		}
	}

    /**
	 * ��ת���༭�����ù黹����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);
		$this->showDatadicts(array('applyType' => 'JYGHSQLX'),$obj['applyType']);
		$this->view ( 'edit');
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		$this->checkSubmit();
        $object = $_POST[$this->objName];
        $isTempSno = $this->service->checkHasTempSno_d($object);
        $object['ExaStatus'] = $isTempSno ? '���ύ' : '����';
		if ($this->service->edit_d ( $object )) {
        	//������ʱ���к���֤
            if($isTempSno){
            	exit("<script language=javascript>alert('���ݰ�����ʱ���кţ���Ҫ�ύ����');location.replace('".'controller/projectmanagent/borrowreturn/borrowreturn_ewf.php?actTo=ewfSelect&billId='.$object['id']."');</script>");
            }else{
            	msgRf ( '�ύ�ɹ���' );
            }
		}
	}

    /**
     * �޸Ķ��� - ��ȷ�Ϲ黹�����б���
     */
    function c_toEditManage(){
        $this->permCheck (); //��ȫУ��
        $obj = $this->service->get_d ( $_GET ['id'] );
        $this->assignFunc($obj);
        $this->showDatadicts(array('applyType' => 'JYGHSQLX'),$obj['applyType']);
        $this->assign('sendUserId', $obj['createId']);
        $this->assign('sendName', $obj['createName']);
        $this->view ( 'editmanage');
    }

    /**
     * �޸Ķ���
     */
    function c_editManage(){
        if ($this->service->editManage_d($_POST[$this->objName])){
            msgRf ( '�޸ĳɹ���' );
        }
    }

    /**
	 * ��ת���鿴�����ù黹����ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);
		$this->view ( 'view' );
	}

    /**
	 * ��ת���鿴�����ù黹����ҳ��
	 */
	function c_toAudit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);
		$this->view ( 'audit' );
	}

    /**
     * �б�ҳ�ύ����
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
	 * ȷ���б�--������
	 */
	function c_confirmList(){
		$this->view('confirmList');
	}

	/**
	 * ȷ��ҳ��
	 */
    function c_confirmView(){
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('equIdArr',$_GET['equIdArr']);
		$this->view ( 'confirmView' );
	}

    /**
	 * ȷ�Ϸ���
	 */
	function c_confirmEdit() {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		$disDao = new model_projectmanagent_borrowreturn_borrowreturnDis();
        $id = $disDao->add_d ( $object, true );
        $this->service->updateReturnState_d($object['id']);
		if ($id) {
			msgRf ( 'ȷ�ϳɹ���' );
		}
	}

	/**
	 * ��ת����ص���ҳ��
	 */
	function c_toDisposeback(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->assign('sendUserId', $obj['createId']);
		$this->assign('sendName', $obj['createName']);

		$this->view('back');
	}

    /**
     * ��ص���
     */
    function c_disposeback() {
    	$object = $_POST[$this->objName];
		if ($this->service->disposeback_d($object)) {
			msg('��سɹ�');
		}else{
			msg('���ʧ��');
		}
	}

    /**
     * ajax �����⳥״̬
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
	 * ��������
	 */
	function c_dealAfterAudit(){
       	$this->service->workflowCallBack($_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * �ֿ���Ա����ȷ��
	 */
	function c_ajaxReceive(){
		echo $this->service->ajaxReceive_d($_GET['id']) ? 1 : 0;
	}

	/**
	 * �ҵ�ȷ���б�--����
	 */
	function c_myConfirmList(){
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->view('myConfirmList');
	}

	/**
	 * ��ת������ȷ��ҳ��
	 */
	function c_toSaleConfirm() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);
		$this->view ( 'saleConfirm' );
	}

	/**
	 * ����ȷ�ϵ���
	 */
	function c_saleConfirm() {
		if ($this->service->saleConfirm_d($_POST[$this->objName])) {
			msg('ȷ�ϳɹ�');
		}else{
			msg('ȷ��ʧ��');
		}
	}
}