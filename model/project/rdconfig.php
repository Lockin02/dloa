<?php
class model_project_rdconfig extends model_base{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'project_config';
	}
	
	function getConfigManager($page = null,$rows = null){
		if ($page && $rows && !$this->num)
		{
			$this->num = $this->GetCount (" type = 'admin' ");
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page-1)*$pagenum) : $this->start;
			$limit = $page &&  $rows ? $start . ',' . $pagenum : '';
		}
		$SQL = "
				SELECT 
				pc.id AS id,
				pc.type AS type,
				pc.value AS account,
				u.USER_NAME AS username
				FROM {$this->tbl_name} AS pc  
				left join USER AS u on  u.USER_ID = pc.value 
				WHERE pc.type = 'admin'
				";
		$query = $this->query($SQL);
		$data = array();
		while (($rs = $this->fetch_array($query)) != false){
			$data[] = $rs;
		}
		
		return $data;
	}
	
	function createData($data){
		$newId = $this->Add($data);
		return $newId;
	}
	
	function updateData($data, $id){
		return $this->Edit($data, $id);
	}
	
	function removeData($id, $type){
		$conditions = " id = '{$id}' AND type = '{$type}' ";
		return $this->delete($conditions);
	}
	
	/**
	 * 获取所有用户名数组，KEY=USER_ID,VALUE=USER_NAME
	 */
	function get_username($key_type='user_id')
	{
		$query = $this->query("select user_id,user_name from user");
		$data = array();
		if ($key_type == 'user_id')
		{
			while (($rs = $this->fetch_array($query))!=false) {
				$data[$rs['user_id']] = $rs['user_name'];
			}
		}else{
			while (($rs = $this->fetch_array($query))!=false) {
					$data[$rs['user_name']] = $rs['user_id'];
				}
		}
		return $data;
	}
	
	/**
	 * 获取用户姓名返回用户ID数组
	 * 
	 * @param unknown_type $data
	 * @param unknown_type $username_str
	 */
	function get_userid_str($data,$username_str)
	{
	if ($data && $username_str)
		{
			$arr = explode(',',$username_str);
			$userid = array();
			if ($arr)
			{
				foreach ($arr as $val)
				{
					$val = trim($val);
					if ($val) $userid[$val] = $data[$val];
				}
			}
			return $userid;
		}else{
			return false;
		}
	}
	
	function isAdminExist($account){
		$conditions = " type = 'admin' AND value = '{$account}' "; 
		return $this->GetCount ($conditions);
	}
}