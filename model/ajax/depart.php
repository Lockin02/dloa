<?php
class model_ajax_depart extends includes_class_depart 
{
	public $func_id ='';
	
	function __construct()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
		}
		parent::__construct();
	}
	function get_depart()
	{
		$rs = $this->db->get_one("select dept_id from user_jobs where id=".$_POST['id']);
		return $this->depart_select($rs['dept_id']);
	}
	
	function dept_select()
	{
		return $this->depart_select($_POST['dept_id']);
	}
	
	function get_deptid()
	{
		return parent:: get_deptid(($_POST['dept_name']?$_POST['dept_name'] : $_GET['dept_name']));
	}
}
?>