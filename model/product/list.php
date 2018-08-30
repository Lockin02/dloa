<?php
class model_product_list extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'product';
	}
	/**
	 * 覆盖基类
	 * @see model_base::GetDataList()
	 */
	function GetDataList($condition=null,$page=null,$rows=null)
	{
		if ($page && $rows && !$this->num)
		{
			$rs = $this->get_one("select count(0) as num from $this->tbl_name where  ".($condition ? str_replace('a.', '', $condition)." and is_delete=0" : 'is_delete=0'));
			$this->num = $rs['num'];
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page-1)*$pagenum) : $this->start;
			$limit = $page &&  $rows ? $start . ',' . $pagenum : '';
		}
		$data = array();
		$query = $this->query ( "
								select 
									a.*,b.user_name as manager_name,d.typename
									
								from
									product as a
									left join user as b on b.user_id=a.manager
									left join product_type as d on d.id=a.typeid
									where ".($condition ? "$condition and a.is_delete = 0" : 'a.is_delete = 0')."
								order by a.id desc
								" . ($limit ? "limit " . $limit : '') . "
									" );
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			$rs['assistant_name'] = $this->GetUserNmaeOrUserId($rs['assistant'],'user_id');
			$rs['feedback_assistant_name'] = $this->GetUserNmaeOrUserId($rs['feedback_assistant'],'user_id');
			$rs['feedback_owner_name'] = $this->GetUserNmaeOrUserId($rs['feedback_owner'],'user_id');
			$data[] = $rs;
		}
		
		return $data;
	}
	/**
	 * 获取用户信息
	 * 
	 * @param unknown_type $parms
	 * @param unknown_type $type
	 */
	function GetUserNmaeOrUserId($parms,$type='user_id')
	{
		if ($parms)
		{
			$condition = '';
			$tmp = array();
			$parms =explode(',', $parms);
			foreach ($parms as $val)
			{
				if (trim($val)) $tmp[] = "'".trim($val)."'";
			}
			
			if ($type == 'user_id') 
			{
				$field = 'user_name';
				$condition = 'user_id in('.implode(',', $tmp).')';
			}else {
				$field = 'user_id';
				$condition = 'user_name in('.implode(',', $tmp).')';
			}
			if ($condition)
			{
				$query = $this->query("select $field from user where $condition");
				$arr=array();
				while (($rs = $this->fetch_array($query))!=false)
				{
					$arr[] = $rs[$field];
				}
				return $arr ? implode(',', $arr) : '';
			}
		
		}
	}
}
?>