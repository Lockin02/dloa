<?php
/**
 * @author longqb
 * @Date 2011��11��27�� 15:50:15
 * @version 1.0
 * @description:������������Ʋ�
 */
class controller_service_accessorder_accessorder extends controller_base_action {

	function __construct() {
		$this->objName = "accessorder";
		$this->objPath = "service_accessorder";
		parent::__construct ();
	}
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ("select_list");
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
	 * ��ת������������б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * ��ת������������������б�
	 */
	function c_outstockpage() {
		$this->view ( 'outstock-list' );
	}

	/**
	 * ��ת���������������ҳ��
	 */
	function c_toAdd() {
		$this->assign ( 'chargeUserName', $_SESSION ['USERNAME'] );
		$this->assign ( 'chargeUserCode', $_SESSION ['USER_ID'] );
		$this->assign ( 'docDate', day_date );
		$configenumDao = new model_system_configenum_configenum ();
		$this->assign ( "warranty", $configenumDao->getEnumFieldVal ( "1", "configEnum1" ) );
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭���������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
        $obj['file'] = $this->service->getFilesByObjId($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtEdit ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->assign ( 'docDate', day_date );
		$configenumDao = new model_system_configenum_configenum ();
		$this->assign ( "warranty", $configenumDao->getEnumFieldVal ( "1", "configEnum1" ) );
		$this->view ( 'edit' );
	}

	/**
	 * ��ת���鿴���������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
        $obj['file'] = $this->service->getFilesByObjId($_GET['id'],false);
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
		if($obj['isBill']=="0"){
			$this->assign("isBill","��");
		}else{
			$this->assign("isBill","��");
		}
		if($obj['deliveryCondition']=="0"){
			$this->assign("deliveryCondition", "�����");
		}else{
			$this->assign("deliveryCondition","��������");
		}
		$this->view ( 'view' );
	}

	/**
	 * �����������
	 * @linzx
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/accessorder/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_service_accessorder&formName=�������������' );
			} else {
				echo "<script>alert('�����ɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('��������ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}
	}

	/**
	 * �޸Ķ������
	 * @linzx
	 */
	function c_edit() {
		$accessorderObj = $_POST [$this->objName];
		$id = $this->service->edit_d ( $accessorderObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/accessorder/ewf_index.php?actTo=ewfSelect&billId=' . $accessorderObj ['id'] . '&examCode=oa_service_accessorder&formName=�������������' );
			} else {
				echo "<script>alert('�޸ĳɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('�����޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				echo "<script>alert('�޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}
	}

	/**
	 * �鿴ҳ��Tab
	 */
	function c_viewTab() {
		$this->permCheck (); //��ȫУ��
		$this->assign ( 'id', $_GET ['id'] );
		$this->display ( 'view-tab' );
	}

	/**
	 *
	 * ȷ�Ϲر����
	 */
	function c_closeFinished() {
		$object = array ("docStatus" => "YWC", "id" => $_GET ['id'] );
		if ($this->service->updateById ( $object ))
			echo 0;
		else
			echo 1;
	}

	/**
	 * ����δ������϶���
	 *
	 */
	function c_toExportNotIncomeExcel() {
		$dataArr = $this->service->listBySqlId ("select_notincome");
		$dao = new model_service_accessorder_serviceExcelUtil ();
//
//		echo "<pre>";
//		print_r($dataArr);
		return $dao->ExportNotIncomeExcel ( $dataArr );
	}

	/**
	 * �жϽ���Ƿ�Ϊ0
	 */
	function c_orderMoneyIsZero(){
		$obj = $this->service->get_d($_POST['id']);
		exit($obj['saleAmount']);
	}
}
?>