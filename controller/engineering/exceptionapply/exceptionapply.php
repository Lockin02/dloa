<?php

/**
 * @author Show
 * @Date 2012��8��2�� ������ 19:35:41
 * @version 1.0
 * @description:���̳�Ȩ��������Ʋ�
 */
class controller_engineering_exceptionapply_exceptionapply extends controller_base_action {

	function __construct() {
		$this->objName = "exceptionapply";
		$this->objPath = "engineering_exceptionapply";
		parent :: __construct();
	}

	/***************** �б��� ************************/

	/**
	 * ��ת�����̳�Ȩ�������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * �ҵ�tabҳ
	 */
	function c_myTab(){
		$this->display('mytab');
	}

	/**
	 * �ҵ������б�
	 */
	function c_myList(){
		$this->view('mylist');
	}

	/**
	 * �ҵ��б�����
	 */
	function c_myPageJson(){
		$service = $this->service;

		$_REQUEST['createId'] = $_SESSION['USER_ID'];
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
	 * ����������б�
	 */
	function c_auditList(){
		$this->assign('ExaUserId',$_SESSION['USER_ID']);
		$this->view('auditList');
	}

	/******************* ��ɾ�Ĳ� ***********************/

	/**
	 * ��ת���������̳�Ȩ������ҳ��
	 */
	function c_toAdd() {
		$this->showDatadicts(array('rentalType' => 'GCZCXZ'),null,true); //�⳵����
		$this->showDatadicts(array('useRange' => 'GCYCSQSYFW')); //���÷�Χ
		//����ת��
		$this->assign('applyTypeCN' ,$this->getDataNameByCode($_GET['applyType']));
		$this->assign('applyType',$_GET['applyType']);

		//������Ϣ����
		$this->assign('deptName',$_SESSION['DEPT_NAME']);
		$this->assign('deptId',$_SESSION['DEPT_ID']);
		$this->assign('applyUserId',$_SESSION['USER_ID']);
		$this->assign('applyUserName',$_SESSION['USERNAME']);
		$this->assign('applyDate',day_date);

		$this->view (  $this->service->getBusinessCode($_GET['applyType']).'-add' );
	}

	/**
	 * ��ת���༭���̳�Ȩ������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('rentalType' => 'GCZCXZ'),$obj['rentalType']); //�⳵����
		$this->showDatadicts(array('useRange' => 'GCYCSQSYFW'),$obj['useRange']); //���÷�Χ

		$this->view (  $this->service->getBusinessCode($obj['applyType']).'-edit' );
	}

	/**
	 * ��ת���鿴���̳�Ȩ������ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view (  $this->service->getBusinessCode($obj['applyType']).'-view' );
	}

	/**
	 * ��ת���鿴���̳�Ȩ������ҳ��
	 */
	function c_toAudit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('thisDate',day_date);
		$this->view (  $this->service->getBusinessCode($obj['applyType']).'-audit' );
	}

	/**
	 * ���
	 */
	function c_audit(){
		$object = $_POST[$this->objName];
		if($this->service->audit_d($object)){
			msg('������');
		}else{
			msg('���ʧ��');
		}
	}
}
?>