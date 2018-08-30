<?php
/**
 * @author longqb
 * @Date 2011��12��3�� 10:33:49
 * @version 1.0
 * @description:�豸�������뵥���Ʋ�
 */
class controller_service_change_changeapply extends controller_base_action {

	function __construct() {
		$this->objName = "changeapply";
		$this->objPath = "service_change";
		parent::__construct ();
	}

	/**
	 * ��ת���豸�������뵥�б�
	 */
	function c_page() {
		$this->assign("userId", $_SESSION['USER_ID']);
		$this->view ( 'list' );
	}

	/**
	 * ��ת�������豸�������뵥ҳ��
	 */
	function c_toAdd() {
		$this->assign('applyUserName', $_SESSION['USERNAME']);
		$this->assign("applyUserCode", $_SESSION['USER_ID']);
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭�豸�������뵥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if($obj['relDocType']=="WXSQD"){
			$this->assign("relDocTypeName","ά�����뵥");
		}
		$this->assign ( "itemsList", $this->service->showItemAtEdit ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->view ( 'edit' );
	}

	/**
	 * ��ת���鿴�豸�������뵥ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtView ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		if(isset($_GET['viewBtn'])){
			$this->assign('showBtn',1);
		}else{
			$this->assign('showBtn',0);
		}
		$this->view ( 'view' );
	}


	 /**
	 * �����������
	 * 
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
        $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
        if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/change/ewf_index.php?actTo=ewfSelect&billId='.$id.'&examCode=oa_service_change_apply&formName=�豸�������뵥����' );
			} else {
				echo "<script>alert('�����ɹ�!');window.opener.window.show_page();window.close();</script>";
			}
		}
		else{
			if ("audit" == $actType) {
				 echo "<script>alert('��������ʧ��!');window.opener.window.show_page();window.close();</script>";
			} else {
				 echo "<script>alert('����ʧ��!');window.opener.window.show_page();window.close();</script>";
			}

		}
	}

	 /**
	 * �޸Ķ������
	 * @author longqb
	 */
      function c_edit() {
      	$changeapplyObj=$_POST [$this->objName];
		$id = $this->service->edit_d ($changeapplyObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/change/ewf_index.php?actTo=ewfSelect&billId='.$changeapplyObj['id'].'&examCode=oa_service_change_apply&formName=�豸�������뵥����' );
			} else {
				 echo "<script>alert('�޸ĳɹ�!');window.opener.window.show_page();window.close();</script>";
			}
		}
		else{
			if ("audit" == $actType) {
				 echo "<script>alert('�����޸�ʧ��!');window.opener.window.show_page();window.close();</script>";
			} else {
				 echo "<script>alert('�޸�ʧ��!');window.opener.window.show_page();window.close();</script>";
			}

		}
	}

	/**
	 * �鿴ҳ��Tab
	 */
	function c_viewTab() {
		$this->assign("skey",$_GET['skey']);
		$this->assign ( 'id', $_GET ['id'] );
		$this->display ( 'view-tab' );
	}


	/**
	 * ���ݴ������Ϣ��ȡ�豸����������Ϣ
	 */
	function c_getChangeapplyList(){
		$this->assign("relDocId", $_GET['relDocId']);
	    $this->display ( 'detail-list' );
	}

}
?>