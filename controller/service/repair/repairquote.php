<?php
/**
 * @author huangzf
 * @Date 2011��12��3�� 10:11:04
 * @version 1.0
 * @description:ά�ޱ����걨�����Ʋ�
 */
class controller_service_repair_repairquote extends controller_base_action {
	
	function __construct() {
		$this->objName = "repairquote";
		$this->objPath = "service_repair";
		parent::__construct ();
	}
	
	/**
	 * ��ת��ά�ޱ����걨���б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת��ά�ޱ��۹����б�
	 */
	function c_pageTab() {
		$this->view ( 'tab' );
	}
	
	/**
	 * ��ת������ά�ޱ����걨��ҳ��
	 */
	function c_toAdd() {
		$spplyItemDao = new model_service_repair_applyitem ();
		if (! empty ( $_GET ['itemIds'] )) {
			$spplyItemDao->searchArr ['ids'] = $_GET ['itemIds'];
			$rs = $spplyItemDao->listBySqlId ();
			$this->assign ( "itemsList", $this->service->showItemAtAdd ( $rs ) );
		}
		$this->assign ( "chargeUserCode", $_SESSION ['USER_ID'] );
		$this->assign ( "chargeUserName", $_SESSION ['USERNAME'] );
		$this->assign ( "docDate", date ( 'Y-m-d H:i:s' ) );
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭ά�ޱ����걨��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtEdit ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->assign ( "docDate", date ( 'Y-m-d H:i:s' ) );
		$this->view ( 'edit' );
	}
	
	/**
	 * ��ת���鿴ά�ޱ����걨��ҳ��
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
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ( $object, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$maxCost=$this->service->getItemMaxMoney($id);
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/repair/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $maxCost . '&examCode=oa_service_repair_quote&formName=ά�ޱ�������' );
			} else {
				echo "<script>alert('ȷ�ϱ��۳ɹ�!');window.opener.window.show_page();window.close();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('��������ʧ��!');window.opener.window.show_page();window.close();</script>";
			} else {
				echo "<script>alert('ȷ�ϱ���ʧ��!');window.opener.window.show_page();window.close();</script>";
			}
		
		}
	}
	
	/**
	 * �༭�������
	 *
	 */
	function c_edit() {
		$repairquoteObj = $_POST [$this->objName];
		$id = $this->service->edit_d ( $repairquoteObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$maxCost=$this->service->getItemMaxMoney($id);
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/repair/ewf_index.php?actTo=ewfSelect&billId=' . $repairquoteObj ['id'] . '&flowMoney=' . $maxCost . '&examCode=oa_service_repair_quote&formName=ά�ޱ�������' );
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
	 * 
	 * ��ȡ���������嵥������
	 */
	function c_getItemMaxMoney() {
		echo $this->service->getItemMaxMoney ( $_POST ['id'] );
	}
}
?>