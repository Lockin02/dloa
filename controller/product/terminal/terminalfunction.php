<?php

/**
 * @author chengl
 * @Date 2013-4-16 13:38:04
 * @version 1.0
 * @description: �ն˹���
 */
class controller_product_terminal_terminalfunction extends controller_base_action {
    function __construct() {
		$this->objName = "terminalfunction";
		$this->objPath = "product_terminal";
		parent::__construct ();
	}

	/**
	 * �������
	 */
	function c_save(){
		$this->service->save($_POST['terminalfunction']);
		echo 1;
	}

	/**
	 * �汾����
	 */
   function c_saveVersion(){

   	  $this->service->saveVeersion_d();
      echo 1;
   }

   /**
    * �汾�鿴
    */
  function c_versionJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->versionJson ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
  /**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_listJsonVersion() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->listJsonVersion_d ($_GET['productId'],$_GET['version']);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

}
?>
