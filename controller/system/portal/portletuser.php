<?php
/**
 * �û��Ż�����portlet���Ʋ�
 */
class controller_system_portal_portletuser extends controller_base_action {

	function __construct() {
		$this->objName = "portletuser";
		$this->objPath = "system_portal";
		parent::__construct ();
	 }

	/**
	 * �Ż����portletʱ��������
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
	 * ��ȡ��ǰ��½��portlet
	 */
	function c_getCurUserPortlets(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->getCurUserPortlets ();
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��קportlet����˳��
	 */
	function c_saveOrder(){
		$savePanel=$_POST["savePanel"];
		$this->service->saveOrder($savePanel);

	}


 }
?>