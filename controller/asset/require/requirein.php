<?php
/**
 * @author show
 * @Date 2014��09��01��
 * @version 1.0
 * @description:����ת�ʲ�������Ʋ�
 */
class controller_asset_require_requirein extends controller_base_action {

	function __construct() {
		$this->objName = "requirein";
		$this->objPath = "asset_require";
		parent :: __construct();
	}
	
	/**
	 * ��ת������ת�ʲ������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת���ʲ������б�
	 */
	function c_awaitList() {
		$this->view('awaitList');
	}
	
	/**
	 * ��ת����ȷ���ʲ������б�
	 */
	function c_confirmList() {
		$this->view('confirmList');
	}
	
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );//����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 * ��ת����������ת�ʲ�����ҳ��
	 */
	function c_toAdd() {
		//������֤
		$requireId = $_GET['requireId'];	//�ʲ���������id
		$requireCode = isset ($_GET['requireCode']) ? $_GET['requireCode'] : null;	//�ʲ�����������
		$docItemDao = new model_asset_require_requireitem();
		$docItemRs = $docItemDao->requireinJsonApply_d($requireId);
		if(empty($docItemRs)){
			msgRf('����ת�ʲ�������������ϣ����ܼ�������');
			exit();
		}
		$docDao = new model_asset_require_requirement();
		$docRs = $docDao->get_d($requireId);
		$this->assign('applyDate', $docRs['applyDate']);
		$this->assign('applyId', $docRs['applyId']);
		$this->assign('applyName', $docRs['applyName']);
		$this->assign('applyDeptId', $docRs['applyDeptId']);
		$this->assign('applyDeptName', $docRs['applyDeptName']);
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign ( 'requireId', $requireId );
		$this->assign ( 'requireCode', $requireCode );

		$this->view('add',true);
	}
	
	/**
	 * �����������
	 */
	function c_add() {
		$this->checkSubmit(); //�����Ƿ��ظ��ύ
		$object = $_POST[$this->objName];
		
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'audit'){
			$object['status'] = "��ȷ��";
		}else{
			$object['status'] = "���ύ";
		}
		$id = $this->service->add_d($object,true);
		if ($id) {
			if($actType == 'audit'){
				msgRf('�ύ�ɹ�');
			}else{
				msgRf('����ɹ�');
			}
		}else{
			if($actType == 'audit'){
				msgRf('�ύʧ��');
			}else{
				msgRf('����ʧ��');
			}
		}
	}
	
	/**
	 * ��ת���༭����ת�ʲ�����ҳ��
	 */
	function c_toEdit() {
// 		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		$object = $_POST[$this->objName];
	
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'audit'){
			$object['status']="��ȷ��";
		}
		$flag = $this->service->edit_d($object, true);
		if ($flag) {
			if ($actType == 'audit') {
				msgRf('�ύ�ɹ�');;
			} elseif ($actType == 'confirm') {
				msgRf('ȷ�ϳɹ�');
			} else {
				msgRf('�༭�ɹ�');
			}
		}else{
			if ($actType == 'audit') {
				msgRf('�ύʧ��');
			} elseif ($actType == 'confirm') {
				msgRf('ȷ��ʧ��');
			} else {
				msgRf('�༭ʧ��');
			}
		}
	}

	/**
	 * ��ת���鿴����ת�ʲ�����ҳ��
	 */
	function c_toView() {
		// 		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
	
	/**
	 * ��ת��ȷ������ҳ��
	 */
	function c_toConfirm() {
		// 		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->view('confirm');
	}
	
	/**
	 * ȷ���ɲֹ��ʲ�����
	 */
	function c_ajaxConfirm(){
		echo $this->service->confirm_d($_POST['id']) ? 1 : 0 ;
	}
	
	/**
	 * ��ת������ת�ʲ������б� -- �����ʲ����������ת�ʲ��б�ҳ
	 */
	function c_listByRequire() {
		$this -> assign('requireId', $_GET['requireId']);
		$this -> view('listbyrequire');
	}
	
	/**
	 * ��ת����ص���ҳ��
	 */
	function c_toBack(){
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->assign('sendUserId', $obj['createId']);
		$this->assign('sendName', $obj['createName']);
	
		$this->view('back');
	}
	
	/**
	 * ��ص���
	 */
	function c_back() {
		$object = $_POST[$this->objName];
		if ($this->service->back_d($object)) {
			msg('��سɹ�');
		}else{
			msg('���ʧ��');
		}
	}
}