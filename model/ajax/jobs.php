<?php
class model_ajax_jobs extends model_system_organizer_jobs
{
	public $func_id;
	function __construct()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
		}
		$action = $_GET['action'] ? $_GET['action'] : $_POST['action'];
		parent::__construct();
		if ($action=='edit')
		{
			$this->func_id = '12';
		}
	}
	/**
	 * 获取职位
	 */
	function get_jobs_options() {
		$dept_id = $_GET ['dept_id'] ? $_GET ['dept_id'] : $_POST ['dept_id'];
		$jobs_id = $_GET['jobs_id'] ? $_GET['jobs_id'] : $_POST['jobs_id'];
		$jobs_id = is_array($jobs_id) ? $jobs_id : ($jobs_id ? explode(',',$jobs_id) : null);
		if ($dept_id && !is_array($dept_id))
		{
			$dept_id = explode(',',$dept_id);
		}
		$jobs = new model_system_jobs();
		$jobs_data = $jobs->JobsList($dept_id);
		$dept = new model_system_dept();
		$dept_data = $dept->DeptList($dept_id);
		$new_data = array();
		foreach ($dept_data as $key => $rs) {

			if (($temp = $dept->GetParent_ID($rs['DEPT_ID']))!=false)
			{
				asort($temp);
				$new_data = array_merge($new_data,$temp,array($rs['DEPT_NAME']=>$rs['DEPT_ID']));
			}
		}
		$new_data = $dept_id ? array_merge($dept_id,$new_data) : $new_data;
		$dept_data = $dept->DeptTree($new_data);
		if ($jobs_data)
		{
			$jobs_temp = array();
			foreach ($jobs_data as $row)
			{
				$jobs_temp[$row['dept_id']][] = $row;
			}
			$jobs_data = $jobs_temp;
		}
		if ($dept_data)
		{
			foreach ($dept_data as $rs)
			{
				$parent_data =array();
				$parent_data = $dept->GetParent($rs['DEPT_ID'],null,true);
				$dept_class = '';
				if ($parent_data)
				{
					foreach ($parent_data as $v)
					{
						$dept_class .='dept_'.$v['DEPT_ID'].' ';
					}
				}
				$dept_list .='<option notselect="true" '.($dept_class ? 'class="'.$dept_class.'"' : '').' level="'.($rs['level']-1).'" func="select_jobs('.$rs['DEPT_ID'].',this.checked);" value="'.$rs['DEPT_ID'].'"><b>'.$rs['DEPT_NAME'].'</b></option>';
				if ($jobs_data[$rs['DEPT_ID']])
				{
					foreach ($jobs_data[$rs['DEPT_ID']] as $v)
					{
						$dept_list .='<option '.($jobs_id && in_array($v['id'],$jobs_id) ? 'selected="selected"' : '').' class="'.$dept_class.' dept_'.$rs['DEPT_ID'].'" level="'.$rs['level'].'" value="'.$v['id'].'">'.$v['name'].'</option>';
					}
				}
			}
		}
		return $dept_list;
	}

	function jobs_select()
	{
		if ($_POST['departid'])
		{
			$query = $this->db->query("select id,name from user_jobs where dept_id=".$_POST['departid']." order by dept_id");
		}else{ //不重复显示职位名称，且过滤已禁用部门的职位
			$query = $this->db->query("SELECT	u.id,u.name FROM user_jobs u LEFT JOIN department d ON d.DEPT_ID = u.dept_id WHERE d.DelFlag = 0 GROUP BY u.name ORDER BY u.dept_id");
		}
		$str .='<option value="">请选择职位</option>';
		while (($rs = $this->db->fetch_array($query))!=false)
		{
			if ($rs['id']==$_POST['jobsid'])
			{
				$str .='<option selected value="'.$rs['id'].'">'.$rs['name'].'</option>';
			}else{
				$str .='<option value="'.$rs['id'].'">'.$rs['name'].'</option>';
			}
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