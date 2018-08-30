<?php
class controller_develop_dept extends model_develop_dept
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'develop/';
	}
	
	function c_list()
	{
		$this->show->assign('list',$this->model_list());
		$this->show->display('dept-list');
	}
	
	function c_add()
	{
		if ($_POST)
		{
			if ($this->model_save($_POST))
			{
				showmsg('添加成功！','self.parent.location.reload();', 'button');
			}else{
				showmsg('添加失败，请与管理员联系！');
			}
		}else{
			$this->show->assign('userid','');
			$this->show->assign('username_list','');
			$this->show->display('dept-add');
		}
	}
}

?>