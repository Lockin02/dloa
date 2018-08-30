<?php
class model_device_mydevice extends model_base 
{
	public $page;
	public $num;
	public $start;
	//*******************************构造函数***********************************
	function __construct()
	{
		parent::__construct();
		$this->page = intval($_GET['page']) ? intval($_GET['page']) : 1;
		$this->start = ($this->page == 1) ? 0 : ($this->page - 1) * pagenum;
		$this->num = intval($_GET['num']) ? intval($_GET['num']) : false;
		//系统自动更新确认状态
		$this->query("update 
						device_borrow_order_info as a,device_borrow_order as b,device_info as c
					 set
					 	b.confirm=1,a.claim=1,c.state=1
					 where
					 	b.id=a.orderid and c.id=a.info_id and a.claim=0 and ceil((UNIX_TIMESTAMP()-a.date) /86400) >=8
						");
	}
	//*********************************数据处理********************************
	/**
	 * 保存确认订单
	 *
	 */
	function model_save_confirm()
	{
		$this->tbl_name = 'device_borrow_order_info';
		$data = $this->findAll('orderid='.$_GET['id'],null,'id,info_id,list_id,amount');
		if ($data)
		{
			$amount = 0;
			$count = 0;
			foreach ($data as $row)
			{
				if (in_array($row['id'],$_POST['id']))
				{
					$this->query("update device_info set state=1 where id=".$row['info_id']);
					$this->query("update device_borrow_order_info set claim=1 where orderid=".$_GET['id']." and info_id=".$row['info_id']);
					$count = $count+$row['amount'];
				}
				$amount = $amount+$row['amount'];
				
			}
			return $this->query("update device_borrow_order set confirm=".($count==$amount ? 1 : 0)." where id=".$_GET['id']);
		}else{
			return false;
		}
	}
	/**
	 * 更新数据
	 *
	 */
	function model_update()
	{
		
	}
	/**
	 * 删除数据
	 *
	 */
	function model_deltet()
	{
		
	}
	//********************************显示数据**********************************
	/**
	 * 我的设备列表
	 */
	function model_showlist()
	{
		if ($_GET['status']==1)
		{
			$where = "and (a.returndate!=null or a.returndate!='')";
		}elseif ($_GET['status']==2){
			$where = "and (a.returndate is null or a.returndate='')";
		}
		if ($_SESSION['USER_ID'])
		{
			if (!$this->num)
			{
				$rs = $this->_db->get_one("
					select 
						count(0) as num 
					from 
						device_borrow_order_info as a
						left join device_borrow_order as b on b.id=a.orderid
					where 
						b.userid='".$_SESSION['USER_ID']."' and (b.confirm=1 or DATEDIFF(now(),FROM_UNIXTIME(a.date)) >= 10) 
						$where
				");
				$this->num = $rs['num'];
			}
			$query = $this->_db->query("
				select
					a.*,c.dpcoding,c.coding,d.device_name,e.typename
				from
					device_borrow_order_info as a
					left join device_borrow_order as b on b.id=a.orderid
					left join device_info as c on c.id=a.info_id
					left join device_list as d on d.id=c.list_id
					left join device_type as e on e.id=d.typeid
				where
					b.userid='".$_SESSION['USER_ID']."'  and (b.confirm=1 or DATEDIFF(now(),FROM_UNIXTIME(a.date)) >= 10)
					$where
				order by 
					a.id desc
				limit $this->start,".pagenum."
			");
			while (($rs = $this->_db->fetch_array($query))!=false)
			{
				$str .='
				<tr>
					<td>'.$rs['info_id'].'</td>
					<td>'.$rs['typename'].'</td>
					<td>'.$rs['device_name'].'</td>
					<td>'.$rs['dpcoding'].'</td>
					<td>'.$rs['coding'].'</td>
					<td>'.$rs['amount'].'</td>
					<td>'.date('Y-m-d',$rs['date']).'</td>
					<td>'.($rs['returndate'] ? '是' : '<span>否</span>').'</td>
				</tr>
				';
			}
			$showpage = new includes_class_page();
			$showpage->show_page(array('total'=>$this->num,'perpage'=>pagenum));
			$showpage->_set_url('num='.$this->num);
			return $str.'<tr><td colspan="8">'.$showpage->show(6).'</td></tr>';
		}
	}
	
	function model_device_list()
	{
		if ($_GET['status']==1)
		{
			$where = "and (a.returndate!=null or a.returndate!='')";
		}elseif ($_GET['status']==2){
			$where = "and (a.returndate is null or a.returndate='')";
		}
		$query = $this->query("
								select
									distinct(a.typeid) as typeid,c.typename
								from
									device_borrow_order_info as a
									left join device_borrow_order as b on b.id=a.orderid
									left join device_type as c on c.id=a.typeid
								where
									b.userid='".$_SESSION['USER_ID']."'
									and (b.confirm=1 or DATEDIFF(now(),FROM_UNIXTIME(a.date)) >= 10) 
								$where
								order by a.id desc
					");
		$stock = new model_device_stock();
		while (($rs = $this->fetch_array($query))!=false)
		{
			if($rs['typename']){
			   $str .= '<table class="table" border="1" width="98%" cellspacing="0" cellpadding="0" align="center" style="position:relative;">
						<tr bgcolor="#D3E5FA">
							<td colspan="20"><a href="javascript:close('.$rs['typeid'].')">'.$rs['typename'].'</a> <span style="position:absolute;right:5px;line-height:20px;"><a href="javascript:close('.$rs['typeid'].')" id="a_'.$rs['typeid'].'"><img src="images/work/sub.png" border="0" /></a></span></td>
						<tbody id="close_'.$rs['typeid'].'">
						<tr class="tableheader">
							<td>序号</td>
							<td>设备名称</td>
							'.preg_replace('/\<a(.*?)<\/a>/i','',$stock->get_fixed_field_name($rs['typeid'])).'
							'.preg_replace('/\<a(.*?)<\/a>/i','',$stock->model_show_field_name($rs['typeid'])).'
							<td>库存数量</td>
							<td>借用数量</td>
							<td>借用日期</td>
							<td>预计归还日期</td>
							<td>实际归还日期</td>
							<td>备注</td>
						</tr>
						'.$this->model_get_borrow_order_info($_SESSION['USER_ID'],$rs['typeid'],$stock).'
						</tbody>
					</table><br />';	
			}
			
		}
		return $str;
	}
	/**
	 * 我的设备列表消息字段内容
	 * @param unknown_type $userid
	 * @param unknown_type $typeid
	 * @param unknown_type $stock
	 */
	function model_get_borrow_order_info($userid,$typeid,$stock = null)
	{
		if ($_GET['status']==1)
		{
			$where = "and (a.returndate!=null or a.returndate!='')";
		}elseif ($_GET['status']==2){
			$where = "and (a.returndate is null or a.returndate='')";
		}
		$stock = $stock ? $stock : new model_device_stock();
		$query = $this->query("
								select
									a.amount as a_num ,a.targetdate,a.returndate,a.notse as ns,b.*,c.device_name,d.date as Wdate
								from
									device_borrow_order_info as a
									left join device_info as b on b.id=a.info_id
									left join device_list as c on c.id=b.list_id
									left join device_borrow_order as d on d.id=a.orderid
								where
									a.typeid = $typeid
									and d.userid='$userid'
									$where
								order by a.returndate asc, a.id desc
		");
		$rs = $stock->get_fixed_field_name ( $typeid, false );
		$field = $stock->model_show_field_name ( $typeid, false );
		while (($row = $this->fetch_array($query))!=false)
		{
			$str .='<tr>';
			$str .='<td>'.$row['id'].'</td>';
			$str .='<td>'.$row['device_name'].'</td>';
			if ($rs)
			{
				foreach ( $rs as $key => $val )
				{
					if (! $val)
					{
						switch ($key)
						{
							case '_coding' :
								$row['coding'] = $row['coding'] ? $row['coding'] : '--';
								$str .= '<td>' . $row['coding'] . '</td>';
								break;
							case '_dpcoding' :
								$row['dpcoding'] = $row['dpcoding'] ? $row['dpcoding'] : '--';
								$str .= '<td>' . $row['dpcoding'] . '</td>';
								break;
							case '_fitting' :
								$row['fitting'] = $row['fitting'] ? $row['fitting'] : '--';
								$str .= '<td>' . $row['fitting'] . '</td>';
								break;
							case '_price' :
								$row['price'] = $row['price'] ? $row['price'] : '--';
								$str .= '<td>￥' . number_format ( $row['price'], 2 ) . '</td>';
								break;
							case '_notes' :
								$row['notes'] = $row['notes'] ? $row['notes'] : '--';
								$str .= '<td>' . $row['notes'] . '</td>';
								break;
						}
					}
				}
			}
			if ($field)
			{
				$field_data = $stock->get_field_content ( $row['id'] );
				foreach ( $field as $key => $val )
				{
					if ($field_data[$val['id']])
					{
						$str .= '<td style="word-wrap:break-word;word-break:break-all" title="' . $field_data[$val['id']] . '">' . $field_data[$val['id']] . '</td>';
					} else
					{
						$str .= '<td>--</td>';
					}
				}
				unset ( $field_data );
			}
			
			$str .='<td>'.$row['amount'].'</td>';
			$str .='<td>'.$row['a_num'].'</td>';
			$str .='<td>'.date('Y-m-d',$row['Wdate']).'</td>';
			$str .='<td>'.date('Y-m-d',$row['targetdate']).'</td>';
			$str .='<td>'.($row['returndate'] ? date('Y-m-d',$row['returndate']) : '尚未归还').'</td>';
			$str .='<td>'.$row['ns'].'</td>';
			$str .='</tr>';
		}
		
		return $str;
	}
	/**
	 * 借用定单列表
	 *
	 */
	//我的定单
	function model_mydevice_order_list()
	{
		if (!$this->num)
		{
			$rs = $this->_db->get_one("
			select count(0) as num from device_borrow_order where userid='".$_SESSION['USER_ID']."'
			");
			$this->num = $rs['num'];
		}
		$query = $this->_db->query("
			select
				a.*,b.user_name,c.user_name as operatorname,d.name as projectname,d.number,e.user_name as managername,sum(f.return_num) as return_num,g.total
			from
				device_borrow_order as a 
				left join user as b on b.user_id=a.userid 
				left join user as c on c.user_id=a.operatorid 
				left join project_info as d on d.ID = a.project_id 
				left join user as e on e.user_id=a.manager 
				left join device_borrow_order_info as f on f.orderid=a.id 
				left join (select sum(amount) as total,orderid from device_borrow_order_info where claim=1 group by orderid) as g on g.orderid=a.id
			where
				a.userid='".$_SESSION['USER_ID']."'
			group by a.id
			order by a.id desc 
			limit $this->start,".pagenum."
		");
		while (($rs = $this->_db->fetch_array($query))!=false)
		{
			$str .='
			<tr>
				<td>'.$rs['id'].'</td>
				<td>'.$rs['amount'].'</td>
				<td>'.$rs['operatorname'].'</td>
				<td>'.$rs['projectname'].'</td>
				<td>'.$rs['number'].'</td>
				<td>'.$rs['managername'].'</td>
				<td>'.date('Y-m-d',$rs['date']).'</td>
				<td>' . (($rs['return_num'] >= $rs['amount']) ? '是' : '<span>否</span>') . '</td>
				<td>'.($rs['total']==$rs['amount'] ? '已确认':(abs((time() - $rs['date']) / 86400) >=10 ? '已确认' :($rs['return_num'] >= $rs['amount'] ? '已撤单' : '<span>待确认数：'.($rs['amount']-$rs['total']).'</span>'))).'</td>
				<td><a href="?model=device_mydevice&action=show_order_list&id='.$rs['id'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false" class="thickbox" title="借单['.$rs['id'].']列表">查看详细</a></td>
			</tr>
			';
		}
		$showpage = new includes_class_page();
		$showpage->show_page(array('total'=>$this->num,'perpage'=>pagenum));
		$showpage->_set_url('num='.$this->num);
		return $str.'<tr><td colspan="8">'.$showpage->show(6).'</td></tr>';
	}
	
	/**
	 * 借出订单设备列表
	 *
	 * @return unknown
	 */
	function model_show_order_list()
	{
		$borrow = new model_device_borrow();
		return $borrow->model_borrow_order_info_list($_GET['id'],true,true);
	}
	/**
	 * 借用单类型列表
	 * @param unknown_type $orderid
	 * @param unknown_type $typeid
	 */
	function model_borrow_order_type_info($orderid,$typeid)
	{
		$query = $this->query("
								select 
									a.*
								from
									device_borrow_order_info as a
									left join device_type as b on b.id=a.typeid 
									left join device_list as c on c.id=a.list_id 
									left join device_info as d on d.id=a.info_id 
									left join device_borrow_order as e on e.id=a.orderid 
								where
									
		");
		
	}
	//********************************读取数据*******************************
	
	function get_info()
	{
		
	}
	
	//*********************************析构函数************************************
	function __destruct()
	{
		
	}
}
?>