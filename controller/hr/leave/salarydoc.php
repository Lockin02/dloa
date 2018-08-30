<?php
/**
 * @author Administrator
 * @Date 2013��4��25�� ������ 16:33:08
 * @version 1.0
 * @description:���ʽ��ӵ����Ʋ�
 */
class controller_hr_leave_salarydoc extends controller_base_action {

	function __construct() {
		$this->objName = "salarydoc";
		$this->objPath = "hr_leave";
		parent::__construct ();
	}

	/**
	 * ��ת�����ʽ��ӵ��б�
	 */
	function c_page() {
		$this->view('list');
	}
	/**
	 *
	 * ��ְ�����嵥
	 */
	function c_toCheck(){
		$leaveId = $_GET['leaveId'];
		//�ж��Ƿ������̸��¼
		$sql = "select id  from oa_hr_leave_salarydoc where leaveId=".$leaveId."";
		$flagArr = $this->service->_db->getArray($sql);
		$leaveDao = new model_hr_leave_leave();
		if(empty($flagArr[0]['id'])){
			//��ȡ����
			$obj = $leaveDao->get_d ( $_GET['leaveId'] );
			$info = $leaveDao->getPersonnelInfo_d($obj['userAccount']);
			$obj['companyName'] = $info['companyName'];
			$obj['companyId'] = $info['companyId'];
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
			$this->showDatadicts(array ( 'quitTypeCode' => 'HRLZLX' ), $obj['quitTypeCode']);
			$this->assign("leaveId",$_GET['leaveId']);

			$this->assign('fromworkList',"<tr id='appendHtml'><td>���<td colspan='2'>��������</td><td colspan='3'>��ע</td></tr>");
			$this->view ('add');
		}else{
			$obj = $this->service->get_d ( $flagArr[0]['id'] );
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
			//������Ⱦ��ְ�����嵥��ϸ
			$Dao = new model_hr_leave_salarydocitem();
			$Dao->asc=false;
			$Dao->searchArr['mainId'] = $flagArr[0]['id'];
			$itemArr = $Dao->list_d ("select_default");
			$this->assign('itemList',$this->service->showItemAtEdit($itemArr));
			$this->view ('edit');
		}
	}

	/**
	 * ��ת���������ʽ��ӵ�ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭���ʽ��ӵ�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * ��ת���鿴���ʽ��ӵ�ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	/**
	 * ��������
	 * @see controller_base_action::c_add()
	 */
	function c_add(){
		$obj=$_POST[$this->objName];
		if($_GET['actType']=="audit"){
			$obj['ExaStatus']='YSH';
		}
		$id = $this->service->add_d ( $obj,true );
		$msgInfo = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msgInfo );
		}
	}
	/**
	 * �޸ı���
	 * @see controller_base_action::c_add()
	 */
	function c_edit(){
		$obj=$_POST[$this->objName];
		if($_GET['actType']=="audit"){
			$obj['ExaStatus']='YSH';
		}
		$id = $this->service->edit_d ( $obj,true );
		$msgInfo = $_POST ["msg"] ? $_POST ["msg"] : '�޸ĳɹ���';
		if ($id) {
			msg ( $msgInfo );
		}
	}
}
?>