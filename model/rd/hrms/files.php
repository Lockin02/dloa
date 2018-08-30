<?php
class model_rd_hrms_files extends model_base
{
	public $list_all_field_arr = array();
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'user';
		$this->pk='user_id';
	}
	/**
	 * 覆盖基类数据列表
	 */
	function GetDataList($condition=null,$page=1,$rows=10)
	{
		if ($page && $rows && !$this->num)
		{
			
			$rs = $this->get_one("select count(0) as num from user as a left join hrms as b on b.user_id=a.user_id where 1 ".($condition ? " and ".$condition : ''));
			$this->num = $rs['num'];
			//$this->num = $this->GetCount ( str_replace ( 'a.', '', $condition ) );
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page-1)*$pagenum) : $this->start;
			$limit = $page &&  $rows ? $start . ',' . $pagenum : '';
		}
		$sql = "
								select 
									b.*,a.user_name,a.jobs_id,if (a.sex=0,'男','女') as sex ,c.dept_name,d.name as jobs_name,e.name as project_name
								from
									user as a
									left join hrms as b on b.user_id=a.user_id
									left join department as c on c.dept_id=a.dept_id
									left join user_jobs as d on d.id=a.jobs_id
									left join project_info e on (
																	find_in_set(a.user_id,e.manager) 
																	or 
																	find_in_set(a.user_id,e.teamleader)
																	or 
																	find_in_set(a.user_id,e.developer)
																	or 
																	find_in_set(a.user_id,e.testleader)
																	or 
																	find_in_set(a.user_id,e.tester)
																	or 
																	find_in_set(a.user_id,e.qc)
																)
								where
									has_left=0 and del=0 ".($condition ? " and ".$condition : '')."
								order by b.usercard desc
								".($limit ? "limit ".$limit : '');
		$query = $this->query($sql);
		$data = array();
		$jobs_field_arr = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			//职位字段
			if (!$jobs_field_arr[$rs['jobs_id']])
			{
				$jobs_tmp = $this->field_title($rs['jobs_id']);
				if ($jobs_tmp)
				{
					$this->list_all_field_arr = $this->list_all_field_arr+$jobs_tmp;
					$jobs_field_arr[$rs['jobs_id']] = $jobs_tmp;
				}else{
					$jobs_field_arr[$rs['jobs_id']] = true;
				}
			}
			//职位内容
			if (is_array($jobs_field_arr[$rs['jobs_id']]))
			{
				foreach ($jobs_field_arr[$rs['jobs_id']] as $key=>$val)
				{
					$content = $this->field_content($key,$rs['USER_ID']);
					$rs[$key] = $content ? $content : '';
				}
			}
			
			$data[]=$rs;
		}
		return $data;
	}
	/**
	 * 获取相同名称的字段ID
	 * @param $field_id
	 */
	function Get_Field_Name_Id($field_id)
	{
		$rs = $this->get_one("select field_name from hrms_files_field where id=".$field_id);
		$data = array();
		if ($rs)
		{
			$query = $this->query("select id from hrms_files_field where field_name='".$rs['field_name']."'");
			while (($row = $this->fetch_array($query))!=false)
			{
				$data[] = $row['id'];
			}
			return $data;
		}else{
			return false;
		}
	}
	/**
	 * 根据搜索内容返回用户ID数组
	 * @param $keyword
	 * @param $field_id
	 */
	function Search_Field_Conten($keyword,$field_id=null)
	{
		if ($field_id && is_array($field_id))
		{
			$field_id = implode(',',$field_id);
		}
		$user_arr = array();
		$query = $this->query("select user_id from hrms_files_field_content where 1 ".($field_id ? " and field_id in($field_id)" : '')."  and content like '%$keyword%'");
		while (($rs = $this->fetch_array($query))!=false)
		{
			$user_arr[] ="'".$rs['user_id']."'";
		}
		return $user_arr;
	}
	/**
	 * 修改
	 * @param $data
	 * @param $id
	 * @param $key
	 */
	function Edit($data,$userid)
	{
		if ($data && $userid)
		{

				$this->query("update hrms set skill='".$data['skill']."',interest='".$data['interest']."' where user_id='$userid'");
				if (is_array($data['field']))
				{
					foreach ($data['field'] as $key=>$val)
					{
						$rs = $this->get_one("select * from hrms_files_field_content where field_id=$key and user_id='$userid'");
						if ($rs)
						{
							$this->query("update hrms_files_field_content set content='$val' where field_id=$key and user_id='$userid'");
						}else{
							$this->query("insert into hrms_files_field_content(field_id,user_id,content)values($key,'$userid','$val')");
						}
					}
				}
				return true;

		}
	}
	/**
	 * 按职位ID获取自定义字段
	 * @param $jobs_id
	 */
	function field_title($jobs_id)
	{
		$data = array();
		if ($jobs_id)
		{
			$query = $this->query("select id,field_name from hrms_files_field where jobs_id=".$jobs_id);
			while (($rs = $this->fetch_array($query))!=false)
			{
				$data[$rs['id']] = $rs['field_name'];
			}
		}
		return $data;
	}
	/**
	 * 自定义字段内容
	 * @param $field_id
	 * @param $user_id
	 */
	function field_content($field_id,$user_id)
	{
		$rs = $this->get_one("select content from hrms_files_field_content where field_id=".$field_id." and user_id='$user_id'");
		if ($rs)
		{
			return $rs['content'];
		}
	}
}

?>