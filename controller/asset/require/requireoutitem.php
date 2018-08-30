<?php
/**
 * @author show
 * @Date 2014年09月01日
 * @version 1.0
 * @description:资产转物料申请明细控制层
 */
class controller_asset_require_requireoutitem extends controller_base_action {

	function __construct() {
		$this->objName = "requireoutitem";
		$this->objPath = "asset_require";
		parent::__construct ();
	 }
    
	/**
	 * 获取所有数据返回json
	 */
	function c_listByRequireJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * 资产入库时，获取实际可入库的物料数量
	 */
	function c_getNumAtInStock(){
		if (is_numeric($_POST['id']) && strlen($_POST['id']) < 32) {
			$rs = $this->service->find(array('id' => $_POST['id']),null,'number,executedNum');
			echo $rs['number'] - $rs['executedNum'];
		}
	}
 }