<?php
class model_product_management_copyright extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'product_copyright';
	}
	
/**
	 * ¸²¸Ç»ùÀà
	 * @see model_base::GetDataList()
	 */
	function GetDataList($condition=NULL,$page=null,$rows=null)
	{
		if ($page && $rows && !$this->num)
		{
			$rs = $this->get_one("
									select 
										count(0) as num 
									from 
										$this->tbl_name as a
										left join product as p on p.id=a.product_id
										left join product_type as b on b.id=p.typeid
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
									a.*,b.typename,p.product_name
								from
									$this->tbl_name as a
									left join product as p on p.id=a.product_id
									left join product_type as b on b.id=p.typeid
									".($condition ? " where ".$condition : "")."
									order by a.id desc
									" . ($limit ? "limit " . $limit : '') . "
								
		");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$rs['time'] = date('Y-m-d',strtotime($rs['time']));
			$data[] = $rs;
		}
		return $data;
	}
}