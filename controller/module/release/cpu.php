<?php
class controller_module_release_cpu extends model_module_release_cpu
{ 
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'module/release/';
	}
	/**
	 * 列表数据
	 */
	function c_list_data()
	{
		$condition = null;

		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows']);
		$json = array (
						'total' => $this->num 
		);
		if ($data)
		{
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		
		echo json_encode ( $json );
	}
	/**
	 * 添加
	 */
	function c_add()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
			if ($this->Add($_POST))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			echo -2;
		}
	}
	/**
	 * 修改
	 */
	function c_edit()
	{
		if ($_POST && $_GET['id'])
		{
			$_POST = mb_iconv($_POST);
			if ($this->Edit($_POST,$_GET['id']))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			echo -1;
		}
	}
	/**
	 * 删除
	 */
	function c_del()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($id)
		{
			if ($this->Del($id))
			{
				echo 1;
			}else{
				ech -1;
			}
		}else{
			echo -1;
		}
		
	}
	
/**
	 * 检查平台cpu名称是否存在
	 */
	function c_check_name()
	{
		$cpu_name = $_GET['name'] ? $_GET['name'] : $_POST['name'];
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$condition = " cpu_name='$cpu_name'";
		$condition .= $id ? " and id!=$id" : '';
		$rs = $this->GetOneInfo($condition);
		if ($rs)
		{
			echo 1;
		}else{
			echo -1;
		}
	}
}