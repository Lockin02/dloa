<?php
/**
 * @author Administrator
 * @Date 2011年5月31日 14:51:11
 * @version 1.0
 * @description:销售退货设备表控制层
 */
class controller_projectmanagent_return_returnequ extends controller_base_action {

	function __construct() {
		$this->objName = "returnequ";
		$this->objPath = "projectmanagent_return";
		parent::__construct ();
	 }

	/*
	 * 跳转到销售退货设备表
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
		$rows = $service->list_d ("select_edit");
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

 }
?>