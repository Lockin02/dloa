<?php
class model_product_management_list extends model_base
{
	
	function __construct()
	{
		$this->tbl_name = 'product_change_list';
		parent::__construct();
	}
	/**
	 * ¸²¸Ç»ùÀà
	 * @see model_base::GetDataList()
	 */
	function GetDataList($condition=null,$page=null,$rows=null,$group=false)
	{
		if ($page && $rows && !$this->num)
		{
			$rs = $this->get_one("
									select 
										count(".($group ? "distinct(a.identification)" : 0).") as num 
									from 
										$this->tbl_name as a
										left join product as p on p.id=a.product_id
										left join product_type as b on b.id=p.typeid
										left join user as c on c.user_id=a.owner
									".($condition ? " where ".$condition : "")."
									
			");
			$this->num = $rs['num'];
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page-1)*$pagenum) : $this->start;
			$limit = $page &&  $rows ? $start . ',' . $pagenum : '';
		}
		
		$query = $this->query("
								select
									a.*,b.typename,c.user_name as owner_name,p.product_name,
									d.id as d_id,d.product_id as d_product_id,d.en_name as d_en_name,d.cn_name as d_cn_name,d.code as d_code,
									d.desc as d_desc,d.status as d_status,d.flag as d_flag,d.time as d_time,d.version as d_version,
									d.status_desc as d_status_desc,d.unit as d_unit,d.owner as d_owner
								from
									".($group ? 
									"(select * from (select * from $this->tbl_name order by id desc) as tmp group by tmp.identification) as a"
									: "$this->tbl_name as a")."
									left join product as p on p.id=a.product_id
									left join product_type as b on b.id=p.typeid
									left join user as c on c.user_id=a.owner
									left join (select * from $this->tbl_name order by id desc) as d on d.identification=a.identification and d.id<a.id
									" . ($condition ? " where " . $condition : '')."
									".($group ? " group by identification " : 'group by id')."
									order by a.id desc
									" . ($limit ? "limit " . $limit : '') . "
								
		");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$rs['time'] = $rs['time'] && $rs['time']!='0000-00-00 00:00:00' ? date('Y-m-d',strtotime($rs['time'])) : '';
			$rs['d_time'] = $rs['d_time'] && $rs['d_time']!='0000-00-00 00:00:00' ? date('Y-m-d',strtotime($rs['d_time'])) : '';
			$data[] = $rs;
		}
		
		return $data;
		
	}
}
?>