<?php
class model_device_purchase extends model_base
{
	public $id;
	public $typename;
	function __construct()
	{
		parent::__construct ();
		$this->id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$this->typename = $_GET['typename'] ? $_GET['typename'] : $_POST['typename'];
	}
	
	function model_typelist()
	{
		$this->tbl_name = 'device_purchase_type';
		if ($_SESSION['USER_ID'] != 'admin')
		{
			$where = 'a.dept_id=' . $_SESSION['DEPT_ID'];
		} else
		{
			$where = null;
		}
		$where = $where ? 'where ' . $where : '';
		$query = $this->query ( "
								select
									a.*,b.typename as top_name
								from
									device_purchase_type as a
									left join device_purchase_type as b on b.id=a.tid
								$where
								order by id desc
								limit $this->start," . pagenum . "
								
		" );
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			$str .= '
					<tr>
						<td>' . $rs['id'] . '</td>
						<td>' . $rs['typename'] . '</td>
						<td>' . ($rs['top_name'] ? $rs['top_name'] : '一级项目') . '</td>
						<td>修改 | 删除</td>
					</tr>
			';
		}
		return $str;
	}
	//获取项目类别
	function model_get_type_list($tid = '', $sort = 'desc')
	{
		$this->tbl_name = 'device_purchase_type';
		if ($_SESSION['USER_ID'] != 'admin')
		{
			$where = 'a.dept_id=' . $_SESSION['DEPT_ID'];
		} else
		{
			$where = null;
		}
		if ($tid == '0' || $tid)
		{
			$where .= $where ? ' and a.tid=' . $tid : ' a.tid=' . $tid;
		}
		
		$where = $where ? 'where ' . $where : '';
		$query = $this->query ( "
								select
									a.*,b.typename as top_name
								from
									device_purchase_type as a
									left join device_purchase_type as b on b.id=a.tid
								$where
								order by a.id $sort
		" );
		$data = array();
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			$data[] = $rs;
		}
		return $data;
	}
	
	//类别下拉
	function select_type($typeid = '')
	{
		$this->tbl_name = 'device_purchase_type';
		if ($_SESSION['USER_ID'] != 'admin')
		{
			$where = 'dept_id=' . $_SESSION['DEPT_ID'];
		} else
		{
			$where = null;
		}
		$typearr = $this->findAll ( $where );
		foreach ( $typearr as $rs )
		{
			if ($rs['id'] == $typeid)
			{
				$str .= '<option selected value="' . $rs['id'] . '">' . $rs['typename'] . '</option>';
			} else
			{
				$str .= '<option value="' . $rs['id'] . '">' . $rs['typename'] . '</option>';
			}
		}
		return $str;
	}
	
	function model_get_field($typeid)
	{
		if ($typeid)
		{
			return $this->find ( 'id=' . $typeid, null, 'field_id' );
		} else
		{
			return false;
		}
	}
	function model_get_field_name($field_id)
	{
		if ($field_id)
		{
			if (is_array ( $field_id ))
			{
				$fid = rtrim ( implode ( ',', $field_id ), ',' );
				if ($fid)
				{
					return $this->findAll ( "id in($fid)" );
				}
			} else
			{
				return $this->findAll ( "id in($field_id)" );
			}
		} else
		{
			return false;
		}
	}
	/**
	 * 添加申请信息
	 *
	 * @return unknown
	 */
	function model_insert_apply($data)
	{
		$this->tbl_name = 'device_purchase_order';
		if ($data)
		{
			$orderid = $this->create ( array(
				
						'userid'=>$data['userid'],
						'dept_id'=>$data['deptid'],
						'phone'=>$data['phone'],
						'fixed'=>$data['fixed'],
						'laplop'=>$data['laplop'],
						'tid'=>$data['tid'],
						'typeid'=>$data['typeid'],
						'description'=>$data['description'],
						'date'=>time ()
			) );
			if ($orderid)
			{
				$amount = 0;
				$this->tbl_name = 'device_purchase_order_info';
				foreach ( $data['device_name'] as $key => $val )
				{
					if ($val)
					{
						$amount += $data['amount'][$key];
						$this->create ( array(
							
										'orderid'=>$orderid,
										'device_name'=>$val,
										'norm'=>$data['norm'][$key],
										'amount'=>$data['amount'][$key],
										'unit'=>$data['unit'][$key],
										'delivery_date'=>$data['delivery_date'][$key],
										'notse'=>$data['notse'][$key],
										'date'=>time ()
						) );
					}
				}
				$this->tbl_name = 'device_purchase_order';
				return $this->update ( 'id=' . $orderid, array(
					
							'amount'=>$amount
				) );
				return true;
			} else
			{
				return false;
			}
		}
	}
	
	function model_update_applay()
	{
		if (intval ( $_GET['id'] ))
		{
			$v = array();
			foreach ( $_POST as $key => $val )
			{
				if ($key == 'use_date')
					$val = strtotime ( $val );
				$v[$key] = $val;
			}
			$v['date'] = time ();
			$v['state'] = 0;
			return $this->update ( 'id=' . $_GET['id'], $v );
		}
	}
	/**
	 * 申请记录列表
	 *
	 * @return unknown
	 */
	function model_applylist($state = null)
	{
		global $func_limit;
		if ($func_limit['审核权限'])
		{
			$where = "where a.dept_id='".$_SESSION['DEPT_ID']."'";
		}else{
			$where = "where a.userid='" . $_SESSION['USER_ID'] . "'";
		}
		if ($_GET['status'] != null)
		{
			$where .= " and status='" . $_GET['status'] . "'";
		}
		if (! $this->num)
		{
			$rs = $this->_db->get_one ( "select count(0) as num from device_purchase_order as a $where" );
			$this->num = $rs['num'];
		}
		if ($this->num > 0)
		{
			$query = $this->_db->query ( "
											select
												a.*,b.typename,c.typename as project_name,d.user_name,e.dept_name
											from 
												device_purchase_order as a
												left join device_purchase_type as b on b.id=a.tid
												left join device_purchase_type as c on c.id=a.typeid
												left join user as d on d.user_id=a.userid
												left join department as e on e.dept_id=a.dept_id
												$where
											order by a.id desc
											limit $this->start," . pagenum . "
			" );
			while ( ($rs = $this->_db->fetch_array ( $query )) != false )
			{
				
				$status = '待审核';
				switch ($rs['status'])
				{
					case 1 :
						$status = '待采购';
						break;
					case 2 :
						$status = '采购中';
						break;
					case 3 :
						$status = '采购完成';
						break;
					case - 1 :
						$status = '被打回';
						break;
					case - 2 :
						$status = '无法采购';
						break;
					case - 3 :
						$status = '取消采购';
						break;
				}
				$st = $rs['status'];
				$info_link = thickbox_link ( '查看', 'a', 'id=' . $rs['id'] . '&key=' . $rs['rand_key'], '查看采购申请', null, 'show_info', 600, 500 );
				$str .= '
						<tr>
							<td>' . $rs['id'] . '</td>
							<td>' . ($rs['fixed'] == 1 ? '其他' : '固定资产') . '</td>
							<td>' . $rs['typename'] . '</td>
							<td>' . $rs['project_name'] . '</td>
							<td>' . $rs['user_name'] . '</td>
							<td>' . $rs['dept_name'] . '</td>
							<td>' . $rs['amount'] . '</td>
							<td>' . $status . '</td>
							<td>' . date ( 'Y-m-d', $rs['date'] ) . '</td>
							<td>' . $info_link . '</td>
						</tr>
						';
			}
			$pageclass = new includes_class_page ();
			$pageclass->show_page ( array(
				
						'total'=>$this->num,
						'perpage'=>pagenum,
						'nowindex'=>$this->page
			) );
			$pageclass->_set_url ( 'model=' . $_GET['model'] . '&status=' . $_GET['status'] . '&num=' . $this->num );
			return $str . '<tr><td colspan="10" align="center">' . $pageclass->show ( 6 ) . '</td></tr>';
		} else
		{
			return false;
		}
	}
	/**
	 * 获取申请单信息
	 * @param unknown_type $id
	 * @param unknown_type $key
	 */
	function model_get_order($id, $key)
	{
		if (intval ( $id ) && $key)
		{
			return $this->_db->get_one ( "
											select 
												a.*,user_name,dept_name,d.typename,e.typename as project_name
											from 
												device_purchase_order as a
												left join user as b on b.USER_ID=a.userid 
												left join department as c on c.DEPT_ID=a.dept_id 
												left join device_purchase_type as d on d.id=a.tid
												left join device_purchase_type as e on e.id=a.typeid
											where 
												a.id=" . $_GET['id'] );
		} else
		{
			return false;
		}
	}
	/**
	 * 申请单详信息
	 * 
	 * @param unknown_type $orderid
	 */
	function model_get_order_info($orderid)
	{
		$query = $this->query ( "
								select
									a.*,c.user_name,d.dept_name,b.userid,b.phone,b.fixed,b.laplop,b.description,b.tid,b.typeid,e.typename,f.typename as project_name
								from
									device_purchase_order_info as a
									left join device_purchase_order as b on b.id=a.orderid
									left join user as c on c.USER_ID=b.userid 
									left join department as d on d.DEPT_ID=b.dept_id 
									left join device_purchase_type as e on e.id=b.tid
									left join device_purchase_type as f on f.id=b.typeid
								where
									a.orderid=$orderid
		" );
		$data = array();
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			$data[] = $rs;
		}
		return $data;
	}
	
	function model_save_edit($id, $key, $data)
	{
		if (intval ( $id ) && $key)
		{
			$this->tbl_name = 'device_purchase_order';
			$rs = $this->find ( array(
				
						'id'=>$id,
						'rand_key'=>$key
			) );
			if ($rs)
			{
				$this->update ( array(
					
							'id'=>$id,
							'rand_key'=>$key
				), array(
					
							'status'=>0,
							'phone'=>$data['phone'],
							'fixed'=>$data['fixed'],
							'laplop'=>$data['laplop'],
							'tid'=>$data['tid'],
							'typeid'=>$data['typeid'],
							'description'=>$data['description']
				) );
				$this->tbl_name = 'device_purchase_order_info';
				$info_data = $this->model_get_order_info ( $id );
				$info_id_arr = array();
				foreach ( $info_data as $key => $row )
				{
					$info_id_arr[] = $row['id'];
					if ($data['device_name'][$row['id']])
					{
						$this->update ( 'id=' . $row['id'], array(
							
										'device_name'=>$data['device_name'][$row['id']],
										'norm'=>$data['norm'][$row['id']],
										'amount'=>$data['amount'][$row['id']],
										'unit'=>$data['unit'][$row['id']],
										'delivery_date'=>$data['delivery_date'][$row['id']],
										'notse'=>$data['notse'][$row['id']]
						) );
					} else
					{
						$this->delete ( 'id=' . $row['id'] );
					}
				}
				if ($data['device_name'])
				{
					$amount = 0;
					foreach ( $data['device_name'] as $key => $val )
					{
						$amount = $data['amount'][$key] && $val ? ($amount+$data['amount'][$key]) : $amount+0;
						if (! in_array ( $key, $info_id_arr ) && $val)
						{
							$this->create ( array(
											'orderid'=>$id,
											'device_name'=>$data['device_name'][$key],
											'norm'=>$data['norm'][$key],
											'amount'=>$data['amount'][$key],
											'unit'=>$data['unit'][$key],
											'delivery_date'=>$data['delivery_date'][$key],
											'notse'=>$data['notse'][$key]
							) );
						}
					}
					
					$this->tbl_name = 'device_purchase_order';
					$this->update('id='.$id,array('amount'=>$amount));
				}
				return true;
			}else{
				return false;
			}
		}
	
	}
	/**
	 * 设置状态
	 *
	 */
	function model_set_state()
	{
		if (intval ( $_GET['id'] ) && $_GET['state'])
		{
			return $this->updateField ( 'id=' . $_GET['id'], 'state', $_GET['state'] );
		} else
		{
			showmsg ( '非法ID参数！' );
			return false;
		}
	}
}
?>