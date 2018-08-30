<?php

/**
 * @author Show
 * @Date 2012��8��28�� ���ڶ� 11:17:10
 * @version 1.0
 * @description:��ְ�ʸ���֤���۽����˱���Ʋ�
 */
class controller_hr_certifyapply_certifyresult extends controller_base_action {

	function __construct() {
		$this->objName = "certifyresult";
		$this->objPath = "hr_certifyapply";
		parent :: __construct();
	}

	/****************** �б� ***************************/

	/**
	 * ��ת����ְ�ʸ���֤���۽����˱��б�
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

		$_REQUEST['formUserId'] = $_SESSION['USER_ID'];
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

	/********************  ��ɾ�Ĳ� ************************/

	/**
	 * ��ת��������ְ�ʸ���֤���۽����˱�ҳ��
	 */
	function c_toAdd() {
		$assessIds = $_GET['assessIds'];
		//ȡ������һ������
		$assessArr = $this->service->getOneAssess_d($assessIds);
		$this->assignFunc($assessArr);

		//��Ⱦ��ѡ����
		$this->assign('assessIds',$assessIds);

		//������Ϣ��Ⱦ
		$this->assign('thisUserName',$_SESSION['USERNAME']);
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$this->assign('thisDate',day_date);

		//��Ⱦ�ӱ�����
		$tbodyArr = $this->service->initBodyAdd_v($assessIds);
		$this->assign('tbody',$tbodyArr[0]);
		$this->assign('countNum',$tbodyArr[1]);

		$this->view('add');
	}

	/**
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName]);
		if ($id) {
			if($_GET['act']){
				succ_show('controller/hr/certifyapply/ewf_certifyresult.php?actTo=ewfSelect&billId=' . $id );
			}else{
				msgRf('����ɹ�');
			}
		}
	}

	/**
	 * ��ת���༭��ְ�ʸ���֤���۽����˱�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		//��Ⱦ�ӱ�����
		$tbodyArr = $this->service->initBodyEdit_v($_GET['id']);
		$this->assign('tbody',$tbodyArr[0]);
		$this->assign('countNum',$tbodyArr[1]);

		//����״̬
		$this->assign('status',$this->service->rtStatus_c($obj['status']));

		$this->view('edit');
	}

	/**
	 * �����������
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		$rs = $this->service->edit_d ( $object );
		if ($rs) {
			if($_GET['act']){
				succ_show('controller/hr/certifyapply/ewf_certifyresult.php?actTo=ewfSelect&billId=' . $object['id'] );
			}else{
				msgRf('����ɹ�');
			}
		}
	}

	/**
	 * ��ת���鿴��ְ�ʸ���֤���۽����˱�ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//��Ⱦ�ӱ�����
		$tbody = $this->service->initBodyView_v($_GET['id']);
		$this->assign('tbody',$tbody);

		//����״̬
		$this->assign('status',$this->service->rtStatus_c($obj['status']));

		$this->display('view');
	}

	/**
	 * ��ת���鿴��ְ�ʸ���֤���۽����˱�ҳ��
	 */
	function c_toAudit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//��Ⱦ�ӱ�����
		$tbody = $this->service->initBodyView_v($_GET['id']);
		$this->assign('tbody',$tbody);

		//����״̬
		$this->assign('status',$this->service->rtStatus_c($obj['status']));

		$this->display('audit');
	}

	/******************** ҵ���߼� ********************/
	/**
	 * ������ɺ�ص�����
	 */
	function c_dealAfterAudit(){
       	$this->service->dealAfterAudit_d( $_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}
}
?>