<?php
class controller_system_datamanage_index extends model_system_datamanage_index-wx
{
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'system/datamanage/';
	}
	/**
	 * Ĭ����ҳ
	 */
	function c_index()
	{
		$this->c_showlist();	
	}
	/**
	 * ���б�
	 */
	function c_showlist()
	{
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$this->show->assign('keyword',$keyword);
		$this->show->assign('list',$this->model_table_list($keyword));
		$this->show->display('table-list');
	}
	/**
	 * ���ֶ�
	 */
	function c_edit_field()
	{
		if ($_POST)
		{
			if ($this->model_field_comment($_POST))
			{
				showmsg('�����ɹ�','parent.tb_remove()','button');
			}else{
				showmsg('����ʧ�ܣ�');
			}
		}else{
			if ($_GET['table'])
			{
				$this->show->assign('list',$this->model_table_field($_GET['table']));
				$this->show->display('field-list');
			}
		}
	}
	
	function c_export()
	{
		if ($_POST)
		{
			$this->toWord($_POST['table']);
		}else if($_GET['table']){
			$this->toWord($_GET['table']);
		}
	}
	/**
	 * ���
	 */
	function c_add()
	{
		
	}
	/**
	 * �޸�
	 */
	function c_edit()
	{
		
	}
}

?>