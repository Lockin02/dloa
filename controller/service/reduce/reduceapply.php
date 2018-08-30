<?php
/**
 * @author longqb
 * @Date 2011��12��3�� 10:32:24
 * @version 1.0
 * @description:ά�޷��ü������뵥���Ʋ�
 */
class controller_service_reduce_reduceapply extends controller_base_action {

	function __construct() {
		$this->objName = "reduceapply";
		$this->objPath = "service_reduce";
		parent::__construct ();
	}

	/**
	 * ��ת��ά�޷��ü������뵥�б�
	 */
	function c_page() {
		$this->assign ( "userId", $_SESSION ['USER_ID'] );
		$this->view ( 'list' );
	}

	/**
	 * ��ת������ά�޷��ü������뵥ҳ��
	 */
	function c_toAdd() {
		$this->assign ( 'applyUserName', $_SESSION ['USERNAME'] );
		$this->assign ( "applyUserCode", $_SESSION ['USER_ID'] );
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭ά�޷��ü������뵥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtEdit ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );

		$this->view ( 'edit' );
	}

	/**
	 * ��ת���鿴ά�޷��ü������뵥ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtView ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		if (isset ( $_GET ['viewBtn'] )) {
			$this->assign ( 'showBtn', 1 );
		} else {
			$this->assign ( 'showBtn', 0 );
		}
		$this->view ( 'view' );
	}

	/**
	 * �����������
	 * @longqb
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/reduce/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_service_reduce_apply&formName=���ü������뵥����' );
			} else {
				echo "<script>alert('�����ɹ�!');window.opener.window.show_page();window.close();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('��������ʧ��!');window.opener.window.show_page();window.close();</script>";
			} else {
				echo "<script>alert('����ʧ��!');window.opener.window.show_page();window.close();</script>";
			}

		}
	}

	/**
	 * �޸Ķ������
	 * @longqb
	 */
	function c_edit() {
		$reduceapplyObj = $_POST [$this->objName];
		$id = $this->service->edit_d ( $reduceapplyObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/reduce/ewf_index.php?actTo=ewfSelect&billId=' . $reduceapplyObj ['id'] . '&examCode=oa_service_reduce_apply&formName=���ü������뵥����' );
			} else {
				echo "<script>alert('�޸ĳɹ�!');window.opener.window.show_page();window.close();</script>";
			}
		} else {
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
		$this->assign ( "skey", $_GET ['skey'] );
		$this->assign ( 'id', $_GET ['id'] );
		$this->display ( 'view-tab' );
	}

	/**
	 *
	 * ������ɵ�ʱ,���¶�Ӧ���뵥�ļ������
	 */
	function c_dealAfterAudit() {
		//�������ص�����
        $this->service->workflowCallBack($_GET['spid']);
		echo "<script>window.location =\"index1.php?model=common_workflow_workflow&action=auditingList\";</script>";

	}

	/**
	 * ���ݴ������Ϣ��ȡ���ü���������Ϣ
	 */
	function c_getReduceapplyList() {
		$this->assign ( "applyId", $_GET ['applyId'] );
		$this->view ( 'detail-list' );
	}

}
?>