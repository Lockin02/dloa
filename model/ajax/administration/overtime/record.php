<?php
class model_ajax_administration_overtime_record extends model_administration_overtime_record
{
	function __construct()
	{
		if ($_POST)
		{
			$_POST = mb_iconv ( $_POST );
		}
		parent::__construct ();
	}
	
	function edit()
	{
		if ($this->model_edit($_POST['id'],$_POST['key'],array('dining'=>$_POST['dining'],'station'=>$_POST['station'],'work'=>$_POST['work'],'remark'=>$_POST['remark'])))
		{
			return 1;
		}else{
			return -1;
		}
	}
	
	function del()
	{
		if ($this->model_del($_POST['id'],$_POST['key']))
		{
			return 1;
		}else{
			return -1;
		}
	}
}

?>