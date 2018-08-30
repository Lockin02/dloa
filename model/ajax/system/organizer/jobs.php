<?php
class model_ajax_system_organizer_jobs extends model_system_organizer_jobs
{
	public $func_id;
	function __construct()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
		}
		@extract($_POST);
		@extract($_GET);
		parent::__construct();
		if ($action=='edit')
		{
			$this->func_id = '12';
		}
	}

	function jobs_select()
	{
		if ($_POST['departid'])
		{
			$query = $this->db->query("select id,name from user_jobs where dept_id=".$_POST['departid']." order by dept_id");
		}else{
			$query = $this->db->query("select id,name from user_jobs order by dept_id");
		}
		$str .='<option value="">«Î—°‘Ò÷∞Œª</option>';
		while ($rs = $this->db->fetch_array($query))
		{
			$str .='<option value="'.$rs['id'].'">'.$rs['name'].'</option>';
		}
		return $str;
	}
	
	function edit()
	{
		return $this->update();
	}
	function del()
	{
		return $this->delete();
	}
}
?>