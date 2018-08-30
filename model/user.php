<?php
class model_user extends model_base
{
	function __construct()
	{
		parent::__construct();
	}
	
	function GetUser($dept_id=null,$area=null,$jobs_id=null)
	{
		$dept_id = is_array($dept_id) ? implode(',',$dept_id) : $dept_id;
		$area = is_array($area) ? implode(',',$area) : $area;
		$jobs_id = is_array($jobs_id) ? implode(',',$jobs_id) : $jobs_id;
		
		$condition = "a.del=0 and has_left=0";
		$condition .= $dept_id ? " and a.dept_id in($dept_id)" : '';
		$condition .= $area ? " and a.area in($area)" : '';
		$condition .= $jobs_id ? " and a.jobs_id in ($jobs_id)" : '';
		
		$query = $this->query("
									select
										a.user_id,a.user_name,a.dept_id,a.area,a.jobs_id,b.parent_id,b.dept_name,c.name as jobs_name
									from
										user as a
										left join department as b on b.dept_id=a.dept_id
										left join user_jobs as c on c.id=a.jobs_id
									where
										$condition
		");
		$data = array();		
		while (($rs = $this->fetch_array($query))!=false)
		{
			$data[] = $rs;
		}
		return $data;
	}
}
?>