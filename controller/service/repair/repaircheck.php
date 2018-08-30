<?php

/**
 * @author huangzf
 * @Date 2011��12��2�� 10:22:13
 * @version 1.0
 * @description:���ά��������Ʋ�
 */
class controller_service_repair_repaircheck extends controller_base_action {

	function __construct() {
		$this->objName = "repaircheck";
		$this->objPath = "service_repair";
		parent::__construct ();
	}

	/**
	 * ��ת���´���ά���б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 *
	 * ��ת�����ά�������б�
	 */
	function c_taskPage() {
		$this->view ( 'task-list' );
	}
	
	/**
	 *
	 * ��ת�����˼��ά�������б�
	 */
	function c_myTaskPage() {
		$this->assign ( 'repairUserCode', $_SESSION ['USER_ID'] );
		$this->view ( 'mytask-list' );
	}
	
	/**
	 * AJAX ����ؼ�
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
	 * ��ת���������ά������ҳ��
	 */

	function c_toAdd() {
		$this->permCheck ( $_GET ['id'], "service_repair_repairapply" ); //��ȫУ��
		$rs = $this->service->getRepairItems_d ( $_GET ['id'] );
		//�������ļ���ȡ�����Ա��Ϣ
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
	 * ��ת����ⷴ��ҳ��
	 */

	function c_toFeedback() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->processRelInfo($_GET ['id']);
		//���к���ӳ�����
		$obj['serilnoName'] = '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=service_repair_repaircheck&action=toSerilnoNameLog&serilnoName=' . $obj['serilnoName'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100\',1)">' . $obj['serilnoName'] . '</a>';
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'feedback' );
	}

	/**
	 * ������ύ��ⷴ����Ϣ
	 */
	function c_feedback($isEditInfo = false) {
		$service = $this->service;
		$object = $_POST [$this->objName];
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'submit'){
			//�ύʱ���ĵ���״̬Ϊ���Ѽ�⡿
			$object['docStatus']="YJC";
		}
		if ($service->feedback_d($object)) {
			if($actType == 'submit'){
				msg ( "�����ɹ�!" );
			}else{
				msg ( "����ɹ�!" );
			}
		} else {
			if($actType == 'submit'){
				msg ( "����ʧ��!" );
			}else{
				msg ( "����ʧ��!" );
			}
		}
	}

	/**
	 * ��ת������Ƿ�ͬ��ά��ҳ��
	 */

	function c_toIsagree() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->processRelInfo($_GET ['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'isagree' );
	}
	/**
	 * �Ƿ�ͬ��ά��
	 */
	function c_isagree($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '����ɹ���';
			msg ( $msg );
		}
	}

	/**
	 * ��ת��ȷ�����ά��ҳ��
	 */
	function c_toConfirm() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->processRelInfo($_GET ['id']);
		$this->assign ( 'auditDate', day_date );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'confirm' );
	}
	/**
	 * ȷ�����ά��
	 */
	function c_confirm() {
		$object = $_POST [$this->objName];
		$object ['docStatus'] = "YWX";
		if ($this->service->edit_d ( $object )) {
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '����ɹ���';
			msg ( $msg );
		}
	}

	/**
	 * ��ת���༭���ά������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * ��ת���鿴���ά������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->processRelInfo($_GET ['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 *
	 * ��ת��ͨ�����뵥�嵥id�鿴�������ҳ��
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
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		if ($id) {
			echo "<script>alert('�´�ɹ�!');window.opener.window.show_page();window.close();</script>";
		} else {
			echo "<script>alert('�´�ʧ�ܣ�');window.opener.window.show_page();window.close();</script>";
		}
	}
	
	/**
	 * ��ת�����кŵ���ʷά�޼�¼ҳ��
	 */
	function c_toSerilnoNameLog() {
		$this->assign ( 'repairUserCode', $_SESSION ['USER_ID'] );
		$this->assign ( 'serilnoName', $_GET['serilnoName'] );
		$this->view ( 'serilnonamelog-list' );
	}
	
	/**
	 * ��ת���޸ļ�⴦����ҳ��
	 */
	function c_toEditCheckInfo(){
		$this->permCheck();//��ȫУ��
		$service = $this->service;
		$obj = $service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);
		//��ȡͨ���ʼ�������Ϣ������Ĭ�Ϸ�����
		$mailInfo = $service->getMailUser_d('editCheckInfo');
		$this->assign('TO_ID',$mailInfo['defaultUserId']);
		$this->assign('TO_NAME',$mailInfo['defaultUserName']);
		$this->display ('editcheckinfo' );
	}
	
	/**
	 * �޸ļ�⴦����
	 */
	function c_editCheckInfo() {
		if ($this->service->editCheckInfo_d ( $_POST [$this->objName]) ){
			msg ( '�༭�ɹ���' );
		}
	}
	
	/**
	 * ���������Ϣ
	 */
	function processRelInfo($id) {
		$service = $this->service;
		$obj = $service->get_d ($id);
		//��ȡ����ά���������Ϣ
		$repairapplyDao = new model_service_repair_repairapply();
		$applyitemDao = new model_service_repair_applyitem();
		$repairapplyInfo = $repairapplyDao->find(array('id'=>$obj['applyDocId']),null,'customerName,contactUserName,telephone');
		$applyitemInfo = $applyitemDao->find(array('id'=>$obj['applyItemId']),null,'isGurantee');
		$obj['customerName'] = $repairapplyInfo['customerName'];
		$obj['contactUserName'] = $repairapplyInfo['contactUserName'];
		$obj['telephone'] = $repairapplyInfo['telephone'];
		$obj['isGurantee'] = $applyitemInfo['isGurantee'];
		//ά�����뵥�����ӳ�����
		$skey = $this->md5Row($obj['applyDocId'],'service_repair_repairapply',null);
		$obj['applyDocCode'] = '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=service_repair_repairapply&action=toView&id=' . $obj['applyDocId'] . '&skey='.$skey.'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100\',1)">' . $obj['applyDocCode'] . '</a>';
		//��ȡ������Ϣ
		$obj['file'] = $service->getFilesByObjId ($id,true,$this->service->tbl_name);
		
		return $obj;
	}
}