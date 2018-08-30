<?php
/**
 *
 * 评估方案明细表控制层类
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
	 * 跳转到评估方案明细表
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->sort='id';
		$service->asc=false;
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}

?>