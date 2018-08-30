<?php
/**
 * @author Bingo
 * @Date 2015��5��11�� 
 * @version 1.0
 * @description:�����������뵥���Ʋ�
 */
class controller_produce_plan_back extends controller_base_action {

	function __construct() {
		$this->objName = "back";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }

	/**
	 * ��ת�������������뵥ҳ��
	 */
	function c_page() {
		$this->view('list-tab');
	}

	/**
	 * ��ת�������������뵥�б�
	 */
	function c_pageList() {
		$this->assign('finish' ,isset($_GET['backType']) ? 'yes' : 'no');
		$this->view('list');
	}

	/**
	 * ��ת�����������������ϼ�¼�б�
	 */
	function c_myPage() {
		$this->assign('userId' ,$_SESSION['USER_ID']);
		$this->view('mylist');
	}
	
	/**
	 * ��ת����������������б�-tab
	 */
	function c_pageManage() {
		$this->view('list-tab-manage');
	}
	
	/**
	 * ��ת����������������б�
	 */
	function c_pageListManage() {
		$this->assign('perform' ,isset($_GET['perform']) ? 'yes' : 'no');
		$this->view('list-manage');
	}
	
	/**
	 * ��ת�����������������뵥ҳ��-��������
	 */
	function c_toAdd() {
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('docDate' ,day_date);
		$this->view ('add',true);
	}
	
	/**
	 * ��ת�����������������뵥ҳ��-����������
	 */
	function c_toAddByPicking() {
		$pickingId = $_GET['pickingId'];
		$pickingDao = new model_produce_plan_picking();
		$obj = $pickingDao->get_d($pickingId);
		$this->assign('pickingId' ,$pickingId);
		$this->assign('pickingCode' ,$obj['docCode']);
		$this->assign('docType' ,$obj['docType']);
		$this->assign('docTypeCode' ,$obj['docTypeCode']);
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('docDate' ,day_date);
		$this->view ('add-picking',true);
	}
	
	/**
	 * �����������
	 */
	function c_add() {
		$this->checkSubmit();
		$obj = $_POST[$this->objName];
		if(isset($_GET['act']) && $_GET['act'] == 'sub'){
			$obj['docStatus'] = 1;
		}else{
			$obj['docStatus'] = 0;
		}
		$id = $this->service->add_d($obj);
		if ($id) {
			if(isset($_GET['act']) && $_GET['act'] == 'sub'){
				msg('�ύ�ɹ�');
			}else{
				msg('����ɹ�');
			}
		} else {
			if(isset($_GET['act']) && $_GET['act'] == 'sub'){
				msg('�ύʧ��');
			}else{
				msg('����ʧ��');
			}
		}
	}
	
	/**
	 * ��ת���༭������������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if(empty($obj['pickingCode'])){
			$this->view ('edit');
		}else{
			$this->view ('edit-picking');
		}
	}
	
	/**
	 * �༭������������
	 */
	function c_edit() {
		$obj = $_POST[$this->objName];
		if(isset($_GET['act']) && $_GET['act'] == 'sub'){
			$obj['docStatus'] = 1;
		}
		$id = $this->service->edit_d($obj);
		if ($id) {
			if(isset($_GET['act']) && $_GET['act'] == 'sub'){
				msg('�ύ�ɹ�');
			}else{
				msg('�༭�ɹ�');
			}
		} else {
			if(isset($_GET['act']) && $_GET['act'] == 'sub'){
				msg('�ύʧ��');
			}else{
				msg('�༭ʧ��');
			}
		}
	}
	
	/**
	 * ��ת��ȷ��������������ҳ��
	 */
	function c_toConfirm() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('confirm');
	}

	/**
	 * ȷ��������������
	 */
	function c_confirm() {
		$id = $this->service->confirm_d($_POST[$this->objName]);
		if ($id) {
			msg('ȷ�ϳɹ�');
		} else {
			msg('ȷ��ʧ��');
		}
	}
	
	/**
	 * ��ת���鿴���������������ϼ�¼ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if(empty($obj['pickingCode'])){
			$this->view ('view');
		}else{
			$this->view ('view-picking');
		}
	}
	
	/**
	 * ���
	 */
	function c_applyBack() {
		if ($this->service->applyBack_d($_POST['id'])) {
			echo 1;
		} else {
			echo 0;
		}
	}
 }