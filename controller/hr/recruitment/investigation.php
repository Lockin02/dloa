<?php
/**
 * @author Administrator
 * @Date 2012��8��18�� 15:23:52
 * @version 1.0
 * @description:���������¼����Ʋ�
 */
class controller_hr_recruitment_investigation extends controller_base_action {

	function __construct() {
		$this->objName = "investigation";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת�����������¼���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת���������������¼��ҳ��
	 */
	function c_toAdd() {
		$this->assign('thisUser',$_SESSION['USERNAME']);
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$today=date(Y.'-'.m.'-'.d);
		$this->assign("today", $today);
		$this->showDatadicts(array('relationshipName' => 'YZXRGX'));
		$this->view ('add' ,true);
	}

	/**
	 * ��ת���༭���������¼��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->find ( array ("parentId" => $_GET['id'] ) );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$today=date(Y.'-'.m.'-'.d);
		$this->assign("today", $today);
		$this->showDatadicts(array('relationshipName' => 'YZXRGX'));
		$this->view ('edit' ,true);
	}

	/**
	 * ��ת���鿴���������¼��ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/*
	 * ����������������
	 */
	function c_add(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$obj=$_POST[$this->objName];
		$id=$this->service->add_d($obj);
		if ($id) {
			if($_GET['type']=='list'){
				msgGo('����ɹ�',"index1.php?model=hr_recruitment_investigation");
			}else{
				msgGo('����ɹ�',"index1.php?model=hr_recruitment_interview&action=tolastpage");
			}
		}
	}

	/**
	 * �޸Ķ������
	 */
	function c_edit() {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$object = $_POST[$this -> objName];
		$id = $this -> service -> edit_d($object, true);
		if ($id) {
			msgGo('����ɹ�',"index1.php?model=hr_recruitment_interview&action=tolastpage");
		}
	}

	/*
	 * ��ת�����˱��������б�
	 */
	function c_toPersonPage(){
		$this->assign( 'thisUserId',$_SESSION['USER_ID'] );
		$this->view('listbyperson');
	}

	/*
	 * �жϱ��������Ƿ�Ӧ�ý���༭ҳ��
	 */
	function c_isToEdit(){
		$id=$_POST['id'];
		$arr=$this->service->find(array("parentId"=>$id));
		echo util_jsonUtil::encode($arr);
	}
}
?>