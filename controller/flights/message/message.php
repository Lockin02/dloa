<?php
/**
 * @author sony
 * @Date 2013��7��10�� 17:29:50
 * @version 1.0
 * @description:��Ʊ��Ϣ���Ʋ�
 */
class controller_flights_message_message extends controller_base_action {

	function __construct() {
		$this->objName = "message";
		$this->objPath = "flights_message";
		parent::__construct ();
	}

	/**
	 * ��ת����Ʊ��Ϣ�б�
	 */
	function c_page() {
		$auditState = isset($_GET['auditState']) ? $_GET['auditState'] : '';
		$this->assign('auditState',$auditState);
		$this->view ( 'list' );
	}

	/**
	 * �б����tab
	 */
	function c_pageTab(){
		$this->view ( 'listtab' );
	}

	/**
	 * ��ת��������Ʊ��Ϣҳ��
	 */
	function c_toAdd() {
		$this->assign('thisDate',day_date);
        //�жϵ�ǰ�����Ƿ���Ҫʡ��
        $requireDao = new model_flights_require_require();
        $this->assignFunc($requireDao->deptNeedInfo_d($_SESSION['DEPT_ID']));
		$this->view ( 'add' );
	}

	//���ɶ�Ʊ��Ϣҳ��
	function c_toAddPush(){
		$requireDao=new model_flights_require_require();
		$obj = $requireDao->get_d( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

        //�жϵ�ǰ�����Ƿ���Ҫʡ��
        $this->assignFunc($requireDao->deptNeedInfo_d($obj['costBelongDeptId']));

		$this->assign("requireNo",$_GET['requireNo']);
		$this->assign("requireId",$_GET['id']);
		$this->assign('thisDate',day_date);
		$this->view("addpush");
	}

	//����
	function c_add() {
		$obj = $_POST[$this->objName];
		$id = $this->service->add_d ( $_POST [$this->objName]);
		if ($id) {
			//��Ʊ�����ʼ�֪ͨ
			if(!empty($obj['email']['TO_ID']) || !empty($obj['email']['ADDIDS'])){
				$this->service->mailDeal_d('flightsInfo',$obj['email']['TO_ID'],array('requireNo' => $obj['requireNo']),$obj['email']['ADDIDS']);
			}
			msgRf ( "��ӳɹ�" );
		} else {
			msgRf ( "���ʧ��" );
		}
	}

	/**
	 * ��ת���༭��Ʊ��Ϣҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
        //�жϵ�ǰ�����Ƿ���Ҫʡ��
        $requireDao = new model_flights_require_require();
        $this->assignFunc($requireDao->deptNeedInfo_d($obj['costBelongDeptId']));

		$this->assign('businessState',$this->service->rtStatus_d($obj['businessState']));
		$this->view ( 'edit' );
	}

	/**
	 * ��ת���鿴��Ʊ��Ϣҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
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
	 * ��ת���鿴Tab��Ʊ��Ϣҳ��
	 */
	function c_toViewTab() {
		$this->assign('id',$_GET['id']);
		$this->view ( 'message-list' );
	}

	//�������ӱ���˿���
	function c_confirm() {
		$id = $_POST ['id'];
		if ($this->service->confirm_d ($id)) {
			echo 1;
		} else {
			echo 0;
		}
		exit();
	}

	//���˵�
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
	 * ��ǩ����
	 */
	function c_toChange() {
		$this->assignFunc($this->service->get_d($_GET['id']));
		$this->view ( 'change' );
	}

	/**
	 * ��ǩ
	 */
	function c_change(){
		$obj = $_POST[$this->objName];
		$rs = $this->service->change_d ( $obj );
		if ($rs) {
			$this->service->mailDeal_d('flightsChange',$obj['email'][TO_ID],array('requireNo' => $obj['requireNo']),$obj['email']['ADDIDS']);
			msg ( "��ǩ�ɹ�" );
		} else {
			msg ( "��ǩʧ��" );
		}
	}

	/**
	 * �޸ĸ�ǩ
	 */
	function c_toEditChange(){
		$this->assignFunc($this->service->get_d($_GET['id']));
		$this->view ( 'changeedit' );
	}

	/**
	 * �޸ĸ�ǩ
	 */
	function c_changeEdit(){
		$object = $_POST[$this->objName];
		if ($this->service->changeEdit_d ( $object )) {
			msg ( '�༭�ɹ���' );
		}else{
			msg ( '�༭ʧ�ܣ�' );
		}
	}

	//��ת����Ʊ
	function c_toBack() {
		$this->assignFunc($this->service->get_d($_GET['id']));
		$this->view ( 'back' );
	}

	//�����Ʊ��Ϣ
	function c_back() {
		$object = $_POST[$this->objName];
		$rs = $this->service->back_d ( $object );
		if ($rs) {
			//��Ʊ�ʼ�����
			$this->service->mailDeal_d('flightsBack',$object['email']['TO_ID'],array('requireNo' => $object['requireNo']),$object['email']['ADDIDS']);
			msg ( "��Ʊ�ɹ�" );
		} else {
			msg ( "��Ʊʧ��" );
		}
	}

	//�༭��Ʊ��Ϣ
	function c_toEditBack(){
		$this->assignFunc($this->service->get_d($_GET['id']));
		$this->view ( 'backedit' );
	}

	//�༭��Ʊ
	function c_editBack(){
		$object = $_POST[$this->objName];
		if ($this->service->editBack_d ( $object )) {
			msg ( '�༭�ɹ���' );
		}else{
			msg ( '�༭ʧ�ܣ�' );
		}
	}

	//��дlistJsonMessage��������ѯ��Ʊ��Ϣ
	function c_listJsonMessage() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ( "select_default" );
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

    //ajax��ȡ������Ϣ
    function c_ajaxGet(){
        $id = $_POST['id'];
        $obj = $this->service->find(array('id' => $id));
        echo util_jsonUtil::encode($obj);
	}

    /**
	 * ���ɽ��㶩��
	 */
	function c_getConfirmedMsgJson(){
		$msgId = $_REQUEST['ids'];
		//������Ϣ
		$service = $this->service;
		$rows = $service->filterMes_d( $_REQUEST['ids'] );
		foreach($rows as $k=>$v){
			$rows[$k]['msgId'] = $rows[$k]['id'];
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * ��ȡ�б�
	 */
	function c_listHtml(){
		$result = $this->service->getlistHtml_d($_REQUEST);
		echo util_jsonUtil::iconvGB2UTF($result);
	}
	
	/**
	 * �����б���
	 */
	function c_getAllCost(){
		echo $this->service->getAllCost_d($_POST);
	}
}