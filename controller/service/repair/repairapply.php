<?php
/**
 * @author huangzf
 * @Date 2011��12��1�� 14:17:53
 * @version 1.0
 * @description:ά�����뵥���Ʋ�
 */
class controller_service_repair_repairapply extends controller_base_action {

	function __construct() {
		$this->objName = "repairapply";
		$this->objPath = "service_repair";
		parent::__construct ();
	}

	/**
	 * ��ת��ά�����뵥�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * ����ά������
	 */
	function c_myList(){
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->view("mylist");
	}


	/**
	 * ��ת������ά�����뵥ҳ��
	 */
	function c_toAdd() {
		$this->assign ( 'applyUserName', $_SESSION ['USERNAME'] );
		$this->assign ( 'applyUserCode', $_SESSION ['USER_ID'] );
		$this->assign ( 'applyDeptId', $_SESSION ['DEPT_ID'] );
		$this->assign ( 'applyDeptName', $_SESSION ['DEPT_NAME'] );
		$this->assign ( 'docDate', day_date );
		$this->view ( 'add' );
	}
	/**
	 * ��ת������ȷ�ϱ���
	 */
	function c_toQuote() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtQuote ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->view ( 'quote' );
	}

	/**
	 * ��ת���༭ά�����뵥ҳ��
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
	 * ��ת���鿴ά�����뵥ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtView ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->view ( 'view' );
	}

	/**
	 *
	 * ��������
	 */
	function c_toExportSearch() {
		$this->view ( 'export-search' );
	}

	/**
	 *
	 * ����ά����ʷEXCEL
	 */
	function c_toExportExcel() {
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$dataArr = $service->listBySqlId ( "select_export" );
		$dao = new model_service_accessorder_serviceExcelUtil ();
		return $dao->ExportHistoryRepairExcel ( $dataArr );
	}

	/**
	 *
	 * �������ձ�����Ϣ
	 */
	function c_quote() {
		$service = $this->service;
		if ($service->quote_d ( $_POST [$this->objName] )) {
			echo "<script>alert('ȷ�ϳɹ�!');window.opener.window.show_page();window.close();</script>";
		} else {
			echo "<script>alert('ȷ��ʧ��!');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 *
	 * ����ά�޷��ü���,��ȡ�ӱ����ݣ�ƴװ�ӱ��ģ��
	 */
	function c_getItemStrAtReduce() {
		$obj = $this->service->get_d ( $_POST ['reduceapplyId'] );
		$itemList = $this->service->showItemAtReduce ( $obj ['items'] );
		echo util_jsonUtil::iconvGB2UTF ( $itemList );
	}

	/**
	 *
	 * ����ά���豸����,��ȡ�ӱ����ݣ�ƴװ�ӱ��ģ��
	 */
	function c_getItemStrAtChange() {
		$obj = $this->service->get_d ( $_POST ['changeapplyId'] );
		$itemList = $this->service->showItemAtChange ( $obj ['items'] );
		echo util_jsonUtil::iconvGB2UTF ( $itemList );
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
	 * �����������
	 *
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		if ($id) {
			echo "<script>alert('�����ɹ�!');window.opener.window.show_page();window.close();  </script>";
		} else {
			echo "<script>alert('����ʧ��!');window.opener.window.show_page();window.close();  </script>";
		}
	}

	/**
	 * �޸Ķ������
	 *
	 */
	function c_edit() {
		if ($this->service->edit_d ( $_POST [$this->objName], true )) {
			echo "<script>alert('�޸ĳɹ�!');window.opener.window.show_page();window.close(); </script>";
		} else {
			echo "<script>alert('�޸�ʧ��!');window.opener.window.show_page();window.close(); </script>";
		}
	}

	/**
	 *
	 * �ر����
	 */
	function c_closeFinished() {
		$object = array ("docStatus" => "YWC", "id" => $_GET ['id'] );
		if ($this->service->updateById ( $object ))
			echo 0;
		else
			echo 1;
	}
}
?>