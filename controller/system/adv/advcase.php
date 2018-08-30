<?php
/**
 * 高级搜索方案控制层
 */
class controller_system_adv_advcase extends controller_base_action {

	function __construct() {
		$this->objName = "advcase";
		$this->objPath = "system_adv";
		parent::__construct ();
	 }


	 /**
	 * 新增对象操作
	 */
	function c_saveAjax() {
		$advcase=$_POST ['advcase'];
		$advcase['userId']=$_SESSION ['USER_ID'];
		if (util_jsonUtil::is_utf8 ( $advcase['caseName'] )) {
			$advcase['caseName'] = util_jsonUtil::iconvUTF2GB ( $advcase['caseName'] );
		}
		if (util_jsonUtil::is_utf8 ( $advcase['value'] )) {
			$advcase['value'] = util_jsonUtil::iconvUTF2GB ( $advcase['value'] );
		}
		if(empty($advcase['id'])){
			$id = $this->service->add_d ( $advcase );
		}else{
			$this->service->edit_d ( $advcase );
			$id=$advcase['id'];
		}
		echo $id;
	}

	/**
	 * 获取当前用户方案
	 */
	function c_listByCurJson(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->searchArr['userId']=$_SESSION ['USER_ID'];
		$rows = $service->list_d ();
		echo util_jsonUtil::encode ( $rows );
	}


	function c_getAdvsearchSql(){
		$this->service->processAdvsearch($_POST['advArr']);
		echo  util_jsonUtil::iconvGB2UTF(substr($this->service->searchArr['advSql'],4));

	}

 }
?>