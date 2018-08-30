<?php
class controller_device_buy extends model_device_buy
{
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'device/buy/';
	}
	/**
	 * 采购类型
	 */
	function c_typelist()
	{
		$this->show->assign('list',$this->model_typelist());
		$this->show->display('typelist');
	}
	/**
	 * 添加采购类型
	 */
	function c_add_type()
	{
		if ($_POST)
		{
			
		}else{
			$gl = new includes_class_global();
			$this->show->assign('select_dept',$gl->depart_select());
			$this->show->display('add-type');
		}
	}
	
	function c_mylist()
	{
		
		$this->show->display('mylist');
	}
	
	function c_audit_list()
	{
		
	}
	function c_add()
	{
		if ($_POST)
		{
			
		}else{
			$stock = new model_device_stock();
			$this->show->assign('username',$_SESSION['USERNAME']);
			$this->show->assign('select_type',$stock->select_type());
			$this->show->display('add');
		}
	}
	
	function c_edit()
	{
		if ($_POST)
		{
			
		}else{
			
		}
	}
	
	function c_audit()
	{
		if ($_POST)
		{
			
		}else{
			
		}
	}
	
}

?>