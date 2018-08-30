<?php
class C_base
{
	public $M;
	public $show;
	function __construct()
	{
		$model = $_GET['model'] ? $_GET['model'] : $_POST['model'];
		if ($model)
		{
			$table = $model;
			$model = 'model_'.$model;
			$this->M = new $model();
			//$this->M->tbl_name = $table;
		}
		$this->show = new show();
		$arr = explode('_',$table);
		array_pop($arr);
		if ($arr)
		{
			$this->show->path = implode('/',$arr);
		}
	}
	
	function showlist()
	{
		
	}
	/**
	 * 添加
	 */
	function add($ajax=false)
	{
		if ($_POST)
		{
			if ($this->M->create($_POST))
			{
				$ajax ? exit(1) : showmsg('操作成功！');
			}else{
				$ajax ? exit(0) : showmsg('操作失败！');
			}
		}else{
			$this->show->display('add');
		}
	}
	/**
	 * 删除
	 * @param $ajax
	 */
	function del($ajax=false)
	{
		if ($_GET['id'] && $_GET['key'])
		{
			if ($this->M->delete(array('id'=>$_GET['id'],'rand_key'=>$_GET['key'])))
			{
				$ajax ? exit(1) : showmsg('操作成功！');
			}else{
				$ajax ? exit(0) : showmsg('操作失败！');
			}
		}else{
			return false;
		}
	}
	/**
	 * 更新
	 * @param unknown_type $ajax
	 */
	function edit($ajax=false)
	{
		if ($_GET['id'] && $_GET['key'] && $_POST)
		{
			if ($this->M->update(array('id'=>intval(array($_GET['id'],'rand_key'=>$_GET['key'])))))
			{
				$ajax ? exit(1) : showmsg('操作成功！');
			}else{
				$ajax ? exit(0) : showmsg('操作失败！');
			}
		}else{
			$data = $this->M->find(array('id'=>$_GET['id'],'rand_key'=>$_GET['key']));
			if ($data)
			{
				foreach ($data as $key=>$val)
				{
					$this->show->assign($key,$val);
				}
			}
			
			$this->show->display('edit');
		}
	}
	
}

?>