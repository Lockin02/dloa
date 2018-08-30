<?php
class model_ajax_administration_overtime_bus extends model_administration_overtime_bus
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
		if ($this->model_edit($_POST['id'],$_POST['key'],array('station'=>$_POST['station'])))
		{
			echo 1;
		}
	}
	
	function del()
	{
		
		if ($this->model_del($_POST['id'],$_POST['key']))
		{
			echo 1;
		}
	}
}
?>