<?php
/**
 * 用户门户订制portlet控制层
 */
class controller_system_portal_portletuser extends controller_base_action {

	function __construct() {
		$this->objName = "portletuser";
		$this->objPath = "system_portal";
		parent::__construct ();
	 }

	/**
	 * 门户添加portlet时新增关联
	 */
	function c_add() {
		$portletNames=util_jsonUtil::iconvUTF2GB($_POST['portletNames']);
		$portletIdArr=explode(",",$_POST['portletIds']);
		$portletNameArr=explode(",",$portletNames);
		$idArr=$this->service->addBatch_d ( $portletIdArr, $portletNameArr);
		$ids=implode ( ",", $idArr );
		echo $ids;
	}

	/**
	 * 获取当前登陆者portlet
	 */
	function c_getCurUserPortlets(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->getCurUserPortlets ();
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 拖拽portlet更新顺序
	 */
	function c_saveOrder(){
		$savePanel=$_POST["savePanel"];
		$this->service->saveOrder($savePanel);

	}


 }
?>