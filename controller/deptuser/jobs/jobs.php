<?php

/**
 *
 * ½ÇÉ«¿ØÖÆ²ã
 * @author chris
 *
 */
class controller_deptuser_jobs_jobs extends controller_base_action {

	function __construct() {
		$this->objName = "jobs";
		$this->objPath = "deptuser_jobs";
		parent :: __construct();
	}

	function c_listTreeJson(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		function toBoolean($row) {
			//$row ['iconSkin'] = $row ['icon'];
			$row ['icon']="";
			$row ['isParent'] = "false";
			return $row;
		}
		echo util_jsonUtil::encode ( array_map ( "toBoolean", $rows ) );
	}

}
?>
