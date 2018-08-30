<?php

/**
 * @author chengl
 * @Date 2013-4-16 13:38:04
 * @version 1.0
 * @description: 终端关联
 */
class controller_product_terminal_terminalfunction extends controller_base_action {
    function __construct() {
		$this->objName = "terminalfunction";
		$this->objPath = "product_terminal";
		parent::__construct ();
	}

	/**
	 * 保存关联
	 */
	function c_save(){
		$this->service->save($_POST['terminalfunction']);
		echo 1;
	}

	/**
	 * 版本保存
	 */
   function c_saveVersion(){

   	  $this->service->saveVeersion_d();
      echo 1;
   }

   /**
    * 版本查看
    */
  function c_versionJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->versionJson ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
  /**
	 * 获取分页数据转成Json
	 */
	function c_listJsonVersion() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->listJsonVersion_d ($_GET['productId'],$_GET['version']);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

}
?>
