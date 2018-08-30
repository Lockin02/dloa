<?php
/**
 * 用户组类，用于设置以及获取用户组各种信息
 * @author chengchao.huang
 *
 */
class model_system_usergroup_list extends model_base
{
	private $GroupId;
	/**
	 * 构造函数初始化
	 * @param $GroupId
	 */
	function __construct($GroupId=null)
	{
		parent::__construct();
		$this->tbl_name = 'group_user';
		if ($GroupId) $this->GroupId = $GroupId;
	}
	/**
	 * 覆盖基类中的获取列表数据的函数
	 * @param $condition
	 * @param $page
	 * @param $rows
	 */
	function GetDataList($condition=null,$page=null,$rows=null)
	{
		if ($page && $rows && !$this->num)
		{
			$rs = $this->get_one("select 
										count(0) as num 
									from 
										$this->tbl_name as a
										left join user as b on b.user_id=a.user_id
										left join department as c on c.dept_id=a.dept_id
									".($condition ? " where $condition" : '')."
										");
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page - 1) * $pagenum) : $this->start;
			$limit = $page && $rows ? $start . ',' . $pagenum : '';
		}
		
		$query = $this->query("select 
										a.*,b.user_name,c.dept_name
									from 
										$this->tbl_name as a
										left join user as b on b.user_id=a.user_id
										left join department as c on c.dept_id=a.dept_id
									".($condition ? " where $condition" : '')."
									order by a.id desc
								");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false) {
			$range = $rs['dept_id_str'] ? '指定部门/' : '所有部门/';
			$range .= $rs['area_id_str'] ? '指定区域/' : '所有区域/';
			$range .= $rs['jobs_id_str'] ? '指定职位/' : '所有职位/';
			$range .= $rs['user_id_str'] ? '指定用户' : '所有用户';
			
			$rs['range'] = $range;
			$data[] = $rs;
		}
		return $data;
	}
	/**
	 * 获取组ID
	 * @param $identification
	 */
	function GetId($identification)
	{
		$rs = $this->GetOneInfo("identification='$identification'");
		return $rs['id'];
	}
	/**
	 * 检查用户是否存在用户组
	 * @param $userid
	 */
	function CheckUser($GroupId,$userid)
	{
		$user_data = $this->GetGroupUserInfo($GroupId);
		if ($user_data)
		{
			foreach ($user_data as $rs)
			{
				if ($rs['USER_ID'] == $userid)
				{
					return true;
				}
			}
			return false;
		}else{
			return false;
		}
	}
	/**
	 * 获取组成员详细心性
	 * @param $GroupId
	 */
	function GetGroupUserInfo($GroupId=null)
	{
		$GroupId = $GroupId ? $GroupId : $this->GroupId;
		$sql="SELECT a.*,b.group_name,c.dept_name,d.name as area_name , e.name as jobs_name
				FROM user as a  LEFT JOIN $this->tbl_name as b on  (find_in_set(a.user_id,b.user_id_str)
					OR (
						IF(b.dept_id_str is null OR b.dept_id_str='','1=1',find_in_set(a.dept_id,b.dept_id_str))
						AND
						IF(b.jobs_id_str is null OR b.jobs_id_str='','1=1',find_in_set(a.jobs_id,b.jobs_id_str))
						AND
						IF(b.area_id_str is null OR b.area_id_str='','1=1',find_in_set(a.area,b.area_id_str))
						)			
					)
					LEFT JOIN department as c on c.dept_id=a.dept_id
					LEFT JOIN area as d on d.id=a.area
					LEFT JOIN user_jobs as e on e.id=a.jobs_id
				WHERE b.id='$GroupId' and b.id is not null";
		
		$query = $this->query($sql);
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$data[] = $rs;
		}
		return $data;
	}
	/**
	 * 获取用户组姓名，以数组形式返回
	 * @param $GroupId
	 */
	function GetGroupUserName($GroupId=null)
	{
		$GroupId = $GroupId ? $GroupId : $this->GroupId;
		$data = $this->GetGroupUserInfo($GroupId);
		$username_arr = array();
		if ($data)
		{
			foreach ($data as $rs)
			{
				$username_arr[$rs['USER_ID']] = $rs['USER_NAME'];
			}
		}
		
		return $username_arr;
		
	}
	
	/**
	 * 获取组成员邮件，以数组形式返回
	 * @param $GroupId
	 */
	function GetGroupUserEmail($GroupId=null)
	{
		$GroupId = $GroupId ? $GroupId : $this->GroupId;
		$data = $this->GetGroupUserInfo($GroupId);
		$useremail_arr = array();
		if ($data)
		{
			foreach ($data as $rs)
			{
				$useremail_arr[$rs['USER_ID']] = $rs['EMAIL'];
			}
		}
		return $useremail_arr;
	}
	/**
	 * 获取组成员某个字段，以数组形式返回
	 * @param $GroupId
	 * @param $Field
	 */
	function GetGroupUserField($GroupId=null,$Field='USER_ID')
	{
		$GroupId = $GroupId ? $GroupId : $this->GroupId;
		$data = $this->GetGroupUserInfo($GroupId);
		$userfield_arr = array();
		if ($data)
		{
			foreach ($data as $rs)
			{
				$userfield_arr[$rs['USER_ID']] = $rs[$Field];
			}
		}
		
		return $userfield_arr;
	}
}