<?php
class model_system_jobs extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'user_jobs';
		$this->pk = 'id';
	}
	/**
	 * 职位列表
	 * @param $dept_id
	 */
	function JobsList($dept_id=null)
	{
		if ($dept_id && is_array($dept_id))
		{
			$condition = "dept_id in (".implode(',',$dept_id).")";
		}else if (intval($dept_id)){
			$condition = "dept_id in (".$dept_id.")";
		}
		return $this->findAll($condition);
	}
	/**
	 * 添加部门
	 * @param $data
	 */
	function Add($data)
	{
		if (intval($data['dept_id']))
		{
			return $this->create($data);
		}else{
			return false;
		}
	}
}

?>