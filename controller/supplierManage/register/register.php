<?php
/**
 * ��Ӧ�̿��Ʋ���
 */
class controller_supplierManage_register_register extends controller_base_action {


	function __construct() {
		$this->objName = "register";
		$this->objPath = "supplierManage_register";
		parent::__construct ();
	}

/**
 * @desription ��ת����Ӧ���б�ҳ��
 * @param tags
 * @date 2010-11-8 ����02:18:04
 */
	function c_toSupplierlist () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}
	/**
	 * @desription ��ת���޸�ҳ��
	 * @param tags
	 * @date 2010-11-8 ����03:55:02
	 */
	function c_toEdit () {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}
	/*
     * ע�ṩӦ����Ϣ����
	 * @date 2010-9-20 ����02:06:22
	 */
	function c_addsupp() {
		$supp = $_POST [$this->objName];

		if ($_GET ['id']) {
			$supp['ExaStatus'] = 'WQD';
		}
		//echo("<pre>");
		//print_r($supp);
		$id = $this->service->add_d ( $supp, true );

		if ($id) {
			msgGo ( '��������ɹ���' ,"?model=supplierManage_register_supplinkman&action=tolinkmanlist&id=$id");
			}

		}

}
?>
