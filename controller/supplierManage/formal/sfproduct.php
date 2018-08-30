<?php
/**
 * @description: ��Ӧ�̲�Ʒ���Ʋ���
 * @date 2010-11-9 ����02:51:01
 */
class controller_supplierManage_formal_sfproduct extends controller_base_action {
	function __construct() {
		$this->objName = "sfproduct";
		$this->objPath = "supplierManage_formal";
		parent::__construct ();
	}

	/*
 * ��ת����ʽ�⹩Ӧ�̲�Ʒ�б�
 */
	function c_tosfprolist() {
		$this->show->assign ( 'parentId', $_GET ['parentId'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/*
 * ��ʽ����ϵ���б����ݻ�ȡ
 */
	function c_pageJson() {
		$service = $this->service;
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : null;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$this->searchVal ( 'productName' );
		$rows = $this->service->proInSupp ( $parentId );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * ��ת���༭ҳ�湩Ӧ��Ʒ�޸�ҳ��
	 */
	function c_toProEdit() {
		$this->permCheck ($_GET['parentId'],'supplierManage_formal_flibrary');//��ȫУ��

		$service = $this->service;
		$service->searchArr = array ("parentId" => $_GET ['parentId'] );
		$arr = $service->list_d ();
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
		$this->show->assign ( 'parentCode', $_GET ['parentCode'] );
		$this->show->assign ( 'parentId', $_GET ['parentId'] );
		$this->show->assign ( 'perm', $_GET ['perm'] );
		$this->display ('proE',true);
	}

	/**
	 * @desription ��ת��ʽ������ѡ��Ӧ�̲�Ʒ
	 * @param tags
	 * @date 2010-11-8 ����02:18:04
	 */
	function c_toAdd() {
		$sysCode = generatorSerial ();
		$objCode = generatorSerial ();
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : null;
		$parentCode = isset ( $_GET ['parentCode'] ) ? $_GET ['parentCode'] : null;
		$this->show->assign ( "parentId", $parentId );
		$this->show->assign ( "parentCode", $parentCode );
		$this->show->assign ( 'objCode', $objCode );
		$this->show->assign ( 'systemCode', $sysCode );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * ���Ĺ�Ӧ��Ʒ
	 */
	function c_add() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		if ($id) {
			showmsg ( '���Ĺ�Ӧ��Ʒ�ɹ���' );
		}
	}

}
?>
