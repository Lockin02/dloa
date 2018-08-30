<?php
class controller_administration_overtime_bus extends model_administration_overtime_bus
{
	
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'administration/overtime/';
		
	}
	
	function c_index()
	{
		return $this->c_list();
	}
	
	function c_list()
	{
		$this->show->assign('list',$this->model_list());
		$this->show->display('bus-line-list');
	}
	
	function c_add()
	{
		if ($_POST)
		{
			if ($this->model_add($_POST))
			{
				showmsg('添加成功！','self.parent.location.reload();', 'button');
			}else{
				showmsg('添加失败，请与管理员联系！');
			}
		}else{
			$this->show->display('add-bus-line');
		}
	}
	
	function c_del()
	{
		
	}
	
	function c_edit()
	{
		
	}
}

?>