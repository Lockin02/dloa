<?php
/**
 *
 * ����������ϸ����Ʋ���
 * @author fengxw
 *
 */
class controller_supplierManage_scheme_schemeItem extends controller_base_action {

	function __construct() {
		$this->objName = "schemeItem";
		$this->objPath = "supplierManage_scheme";
		parent::__construct ();
	}

	/**
	 * ��ת������������ϸ��
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->sort='id';
		$service->asc=false;
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}

?>