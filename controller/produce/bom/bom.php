<?php
/**
 * @author Administrator
 * @Date 2011��12��30�� 11:45:00
 * @version 1.0
 * @description:BOM����Ʋ� ע:ͬһ���ϲ���ͬʱ������ͬһBOM�ĸ�������������������
 */
class controller_produce_bom_bom extends controller_base_action {
	
	function __construct() {
		$this->objName = "bom";
		$this->objPath = "produce_bom";
		parent::__construct ();
	}
	
	/*
	 * ��ת��BOM���б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת������BOM��ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭BOM��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtEdit ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->assign ( "propertiesName", $this->getDataNameByCode ( $obj ['properties'] ) );
		$this->view ( 'edit' );
	}
	
	/**
	 * ��ת���鿴BOM��ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtView ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->assign ( "propertiesName", $this->getDataNameByCode ( $obj ['properties'] ) );
		$this->view ( 'view' );
	}
	
	/**
	 *
	 * ɾ��
	 */
	function c_ajaxdeletes() {
		//$this->permDelCheck ();
		try {
			$this->service->deletes_d ( $_GET ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}
	
	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		try {
			$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
			if ($id) {
				msg ( $msg );
			}
		} catch ( Exception $e ) {
			echo "<script>alert('" . $e->getMessage () . "');self.parent.tb_remove();</script>";
		}
	}
	
	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		try {
			$object = $_POST [$this->objName];
			if ($this->service->edit_d ( $object, true )) {
				msg ( '�༭�ɹ���' );
			}
		} catch ( Exception $e ) {
			echo "<script>alert('" . $e->getMessage () . "');self.parent.tb_remove();</script>";
		}
	}
	
	/**
	 *
	 * ȷ�����
	 */
	function c_audit() {
		$object = array ("docStatus" => "YSH", "id" => $_GET ['id'] );
		if ($this->service->updateById ( $object ))
			echo 0;
		else
			echo 1;
	}
	
	/**
	 *
	 * ȷ�Ϸ����
	 */
	function c_cancelAudit() {
		$object = array ("docStatus" => "WSH", "id" => $_GET ['id'] );
		if ($this->service->updateById ( $object ))
			echo 0;
		else
			echo 1;
	}
	
	/**
	 * 
	 * �������ϱ�ż��汾�ж��Ƿ�������
	 */
	function c_checkBomReat() {
		$productCode = isset ( $_POST ['productCode'] ) ? $_POST ['productCode'] : false;
		$version = isset ( $_POST ['version'] ) ? $_POST ['version'] : false;
		$id = isset ( $_POST ['id'] ) ? $_POST ['id'] : null;
		$searchArr = array ("yproductCode" => $productCode, "version" => $version );
		$isRepeat = $this->service->isRepeat ( $searchArr, $id );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}
	
	/**
	 * 
	 * ��������BOM����
	 */
	function c_actUseStatus() {
		$productId = isset ( $_GET ['productId'] ) ? $_GET ['productId'] : false;
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		if ($this->service->actUseStatus_d ( $id, $productId ))
			echo "0";
		else
			echo "1";
	
	}

}
?>