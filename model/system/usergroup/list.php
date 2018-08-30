<?php
/**
 * �û����࣬���������Լ���ȡ�û��������Ϣ
 * @author chengchao.huang
 *
 */
class model_system_usergroup_list extends model_base
{
	private $GroupId;
	/**
	 * ���캯����ʼ��
	 * @param $GroupId
	 */
	function __construct($GroupId=null)
	{
		parent::__construct();
		$this->tbl_name = 'group_user';
		if ($GroupId) $this->GroupId = $GroupId;
	}
	/**
	 * ���ǻ����еĻ�ȡ�б����ݵĺ���
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
			$range = $rs['dept_id_str'] ? 'ָ������/' : '���в���/';
			$range .= $rs['area_id_str'] ? 'ָ������/' : '��������/';
			$range .= $rs['jobs_id_str'] ? 'ָ��ְλ/' : '����ְλ/';
			$range .= $rs['user_id_str'] ? 'ָ���û�' : '�����û�';
			
			$rs['range'] = $range;
			$data[] = $rs;
		}
		return $data;
	}
	/**
	 * ��ȡ��ID
	 * @param $identification
	 */
	function GetId($identification)
	{
		$rs = $this->GetOneInfo("identification='$identification'");
		return $rs['id'];
	}
	/**
	 * ����û��Ƿ�����û���
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
	 * ��ȡ���Ա��ϸ����
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
	 * ��ȡ�û�����������������ʽ����
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
	 * ��ȡ���Ա�ʼ�����������ʽ����
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
	 * ��ȡ���Աĳ���ֶΣ���������ʽ����
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