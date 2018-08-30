<?php

/**
 * @author Show
 * @Date 2012��8��23�� ������ 9:40:13
 * @version 1.0
 * @description:��ְ�ʸ�ȼ���֤���۱���Ʋ�
 */
class controller_hr_certifyapply_cassess extends controller_base_action {

	function __construct() {
		$this->objName = "cassess";
		$this->objPath = "hr_certifyapply";
		parent :: __construct();
	}

	/******************* �б��� ********************/

	/**
	 * ��ת����ְ�ʸ�ȼ���֤���۱��б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * �����б�
	 */
	function c_myList(){
		$this->view('listmy');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_REQUEST['userAccount'] = $_SESSION['USER_ID'];
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �ṩ��˱�����
	 */
	function c_listJsonForResult(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ('select_forresult');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/******************* ��ɾ�Ĳ� *******************/

	/**
	 * ��ת��������ְ�ʸ�ȼ���֤���۱�ҳ��
	 */
	function c_toAdd() {
		//�������ظ�������֤
		$obj = $this->service->find(array('applyId' => $_GET['applyId']),null,'id');
		if($obj){
			msgRf('��Ӧ���۱��Ѿ����ɣ����ܼ�������');
		}

		//��ȡ������Ϣ����Ҫ�������ͨ�������ȵ������Ϣ
		$applyInfo = $this->service->getApplyInfo_d($_GET['applyId']);

		//����������Ϣ��ȡ��Ӧģ��
		$template = $this->service->getTemplate_d($applyInfo['careerDirection'],$applyInfo['baseLevel'],$applyInfo['baseGrade']);
		if(empty($template)){
			msgRf('δ���� '.$applyInfo['careerDirectionName'].'/'.$applyInfo['baseLevelName'].'/'.$applyInfo['baseGradeName'].' ��ģ�壬��������');
		}else{
			$this->assign('modelId',$template['id']);
			$this->assign('modelName',$template['modelName']);
		}

		//��Ⱦ������Ϣ
		$this->assignFunc($applyInfo);

		$this->view('add');
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			if($_GET['act']){
				succ_show('controller/hr/certifyapply/ewf_index.php?actTo=ewfSelect&billId=' . $id );
			}else{
				msgRf('����ɹ�');
			}
		}
	}

	/**
	 * ��ת���༭��ְ�ʸ�ȼ���֤���۱�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object )) {
			if($_GET['act']){
				succ_show('controller/hr/certifyapply/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] );
			}else{
				msgRf('����ɹ�');
			}
		}
	}

	/**
	 * �༭��ϸ - �ϴ����۲���
	 */
	function c_toEditDetail(){
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('editdetail');
	}

	/**
	 * ��ת���鿴��ְ�ʸ�ȼ���֤���۱�ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('status',$this->service->rtStatus_c($obj['status']));
		$this->view('view');
	}

	/**
	 * ��ת���鿴��ְ�ʸ�ȼ���֤���۱�ҳ��
	 */
	function c_toAudit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('status',$this->service->rtStatus_c($obj['status']));
		$this->view('audit');
	}

	/**
	 * ָ����ί
	 */
	function c_toSetManager() {
		$userArr = $this->service->getAssessUser_d($_GET['id']);
		$this->assignFunc($userArr);

		$this->assign('id',$_GET['id']);

		$this->view('setmanager');
	}

	/**
	 * ������ί
	 */
	function c_setManager(){
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->setManager_d ( $object )) {
			msg ( 'ָ���ɹ���' );
		}
	}

	/**
	 * ���ַ���¼��
	 */
	function c_toInScore(){
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//��ͷ����
		$thead = $this->service->initHead_v($obj);
		$this->assign('thead',$thead);

		//����������
		$tBody = $this->service->initBody_v($obj);
		$this->assign('tbody',$tBody[0]);
		$this->assign('countNum',$tBody[1]);

		$this->assign('thisCols',$this->service->rtColspan_c($obj['managerId'],$obj['memberId']));

		$this->view('inscore');
	}

	/**
	 * ���ַ���¼��
	 */
	function c_inScore(){
		$object = $_POST [$this->objName];
		if ($this->service->inScore_d ( $object )) {
			msg ( '����ɹ���' );
		}
	}

	/**
	 * �鿴����
	 */
	function c_toViewScore(){
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//��ͷ����
		$thead = $this->service->initHead_v($obj);
		$this->assign('thead',$thead);

		//����������
		$tBody = $this->service->initBodyView_v($obj);
		$this->assign('tbody',$tBody);

		$this->assign('thisCols',$this->service->rtColspan_c($obj['managerId'],$obj['memberId']));

		$this->view('viewscore');
	}

	/************************** ҵ���߼� *******************/
	/**
	 * �ύ��֤׼��
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
	 * ������ɺ�ص�����
	 */
	function c_dealAfterAudit(){
       	$this->service->dealAfterAudit_d( $_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}
}
?>