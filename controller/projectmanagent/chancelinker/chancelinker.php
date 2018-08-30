<?php
/**
 * @author Administrator
 * @Date 2011年3月4日 14:58:38
 * @version 1.0
 * @description:商机联系人信息表控制层
 */
class controller_projectmanagent_chancelinker_chancelinker extends controller_base_action {

	function __construct() {
		$this->objName = "chancelinker";
		$this->objPath = "projectmanagent_chancelinker";
		parent::__construct ();
	 }

	/*
	 * 跳转到商机联系人信息表
	 */
    function c_page() {
      $this->display('list');
    }

    function c_toAddLiner(){
    	$this->display('add');
    }
 }
?>