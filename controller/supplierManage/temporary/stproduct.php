<?php
/**
 * @description: ��Ӧ����ʱ���Ʒ��Ϣ
 * @date 2010-11-9 ����02:51:01
 */
class controller_supplierManage_temporary_stproduct extends controller_base_action {
	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-11-9 ����08:53:05
	 */
	function __construct() {
		$this->objName = "stproduct";
		$this->objPath = "supplierManage_temporary";
		parent::__construct ();
	}

	/**
	 * @desription ��ת����ѡ��Ӧ�̲�Ʒ
	 */
	function c_stpToAdd() {
		//TODO:���ӵ�����
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : null;
		$parentCode = isset ( $_GET ['parentCode'] ) ? $_GET ['parentCode'] : null;

		$this->show->assign ( "parentId", $parentId );
		$this->show->assign ( "parentCode", $parentCode );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * @desription ��ת�༭��Ӧ�̲�Ʒ
	 */
	function c_stpToEdit() {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//��ȫУ��
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : null;
		$parentCode = isset ( $_GET ['parentCode'] ) ? $_GET ['parentCode'] : null;
		$this->service->searchArr = array ("parentId" => $parentId );
		$arr = $this->service->list_d ();
		$productIds = array ();
		$productNames = array ();
		if (is_array ( $arr )) {
			foreach ( $arr as $key => $value ) {
				$productIds [] = $value ['productId'];
				$productNames [] = $value ['productName'];
			}
		}
		$ids = implode ( ",", $productIds );
		$names = implode ( ",", $productNames );
		$this->show->assign ( 'productIds', $ids );
		$this->show->assign ( 'productNames', $names );
		$this->show->assign ( "parentId", $parentId );
		$this->show->assign ( "parentCode", $parentCode );
		$this->show->assign ( "perm", $_GET ['perm'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}

	/**
	 * ���Ĺ�Ӧ��Ʒ
	 */
	function c_add() {
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ($object , true );
		if ($id) {
			succ_show('?model=supplierManage_temporary_stasse&action=stsToAdd&parentId='. $object['parentId'] .'&parentCode=' .$object['parentCode']);
		}
	}
	/**
	 * �༭���Ĺ�Ӧ��Ʒ
	 */
	function c_edadd() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		if ($id) {
//			succ_show('?model=supplierManage_temporary_stasse&action=stsToAdd&parentId='. $_GET['parentId'] .'&parentCode=' .$_GET['parentCode']);
			showmsg ( '���Ĺ�Ӧ��Ʒ�ɹ���' );
		}
	}


	/**
	 * @desription ��ת����Ʒ�б�ҳ��
	 * @param tags
	 * @date 2010-11-8 ����02:18:04
	 */
	function c_toRdconlist() {
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : null;
		$parentCode = isset ( $_GET ['parentCode'] ) ? $_GET ['parentCode'] : null;
		$this->show->assign ( 'parentId', $parentId );
		$this->show->assign ( 'parentCode', $parentCode );

		$this->show->display ( $this->objPath . '_' . $this->objName . '-Rdprolist' );
	}

	/**
	 * @desription ������һҳ--��ע�������ҳ����ǰ��ת����Ʒ���ҳ�棬�����ڿɱ༭״̬
	 * @param tags
	 * @date 2010-11-25 ����10:11:21
	 */
	function c_toEditProd () {
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : null;
//		$parentCode = isset ( $_GET ['parentCode'] ) ? $_GET ['parentCode'] : null;
		$this->service->searchArr = array ("parentId" => $parentId );
		$arr = $this->service->list_d ();
		$productIds = array ();
		$productNames = array ();
		if (is_array ( $arr )) {
			foreach ( $arr as $key => $value ) {
				$productIds [] = $value ['productId'];
				$productNames [] = $value ['productName'];
			}
		}
		$ids = implode ( ",", $productIds );
		$names = implode ( ",", $productNames );
		$this->show->assign ( 'productIds', $ids );
		$this->show->assign ( 'productNames', $names );
		$this->show->assign ( "parentId", $parentId );
//		$this->show->assign ( "parentCode", $parentCode );
		$this->show->assign ( "perm", $_GET ['perm'] );
		$this->show->display( $this->objPath . '_' . $this->objName . '-edit1' );
	}

}
?>
