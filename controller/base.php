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
	 * ���
	 */
	function add($ajax=false)
	{
		if ($_POST)
		{
			if ($this->M->create($_POST))
			{
				$ajax ? exit(1) : showmsg('�����ɹ���');
			}else{
				$ajax ? exit(0) : showmsg('����ʧ�ܣ�');
			}
		}else{
			$this->show->display('add');
		}
	}
	/**
	 * ɾ��
	 * @param $ajax
	 */
	function del($ajax=false)
	{
		if ($_GET['id'] && $_GET['key'])
		{
			if ($this->M->delete(array('id'=>$_GET['id'],'rand_key'=>$_GET['key'])))
			{
				$ajax ? exit(1) : showmsg('�����ɹ���');
			}else{
				$ajax ? exit(0) : showmsg('����ʧ�ܣ�');
			}
		}else{
			return false;
		}
	}
	/**
	 * ����
	 * @param unknown_type $ajax
	 */
	function edit($ajax=false)
	{
		if ($_GET['id'] && $_GET['key'] && $_POST)
		{
			if ($this->M->update(array('id'=>intval(array($_GET['id'],'rand_key'=>$_GET['key'])))))
			{
				$ajax ? exit(1) : showmsg('�����ɹ���');
			}else{
				$ajax ? exit(0) : showmsg('����ʧ�ܣ�');
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