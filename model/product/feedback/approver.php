<?php
class model_product_feedback_approver extends model_base
{
	
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'product';
	}
	
	function save_config($name, $value){
		$newinserid = 0;
		$sql = "INSERT INTO product_config (`name`, `value`) VALUES ('{$name}', '{$value}')";
		if( FALSE != $this->_db->query($sql) ){ // 获取当前新增的ID
			$newinserid = $this->_db->insert_id();
		}
		return $newinserid;
	}
	
	function update_config($name, $value){
		$sql = "UPDATE product_config SET `value` = '{$value}' WHERE `name` = '{$name}'";
		return $this->_db->query($sql);
	}
	
	function count_config($name){
		$rs = $this->get_one("select count(0) as num from product_config where name = '{$name}'");
		return  $rs['num'];
	}
	
	function getSettingByName($name){
		$rs = $this->get_one("select `value` as setting from product_config where name = '{$name}'");
		return  $rs['setting'];
	}
}