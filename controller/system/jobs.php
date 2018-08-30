<?php
class controller_system_jobs extends C_base
{
	function __construct()
	{
		parent::__construct();
		$this->show->path = 'system/jobs/';
	}
	
	function c_list()
	{
		$dept_id = GetInt('dept_name');
		$data = $this->M->JobsList($dept_id);
		$str .='';
		if ($data)
		{
			foreach ($data as $rs)
			{
				
			}
		}
		$this->show->assign('list',$str);
		$this->show->assign('list');
	}
}

?>