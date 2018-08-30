<?php
class model_device_quit extends model_base
{
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> pk = 'id';
	}
	/**
	 * 显示列表
	 */
	function model_order_list ( )
	{
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from device_quit_order" );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num > 0 )
		{
			$query = $this -> query ( "
			select
				a.*,c.user_name,b.num1,b.num2
			from 
				device_quit_order as a
				left join (select count(id) as num1,sum(status) as num2,orderid from device_quit_order_info group by orderid) as b on b.orderid=a.id
				left join user as c on c.user_id=a.operatorid
			order by a.id desc
		" );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$str .= '
						<tr>
							<td>' . $rs[ 'id' ] . '</td>
							<td>' . $rs[ 'user_name' ] . '</td>
							<td>' . $rs[ 'amount' ] . '</td>
							<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
							<td>' . ( $rs[ 'num1' ] == $rs[ 'num2' ] ? '<span>已全部取消</span>' : ( $rs[ 'num2' ] > 0 ? '已取消 ' . $rs[ 'num2' ] . ' 套设备退库' : '整单退库成功' ) ) . '</td>
							<td>
								<a href="?model=device_quit&action=order_info&orderid=' . $rs[ 'id' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=650" class="thickbox" title="查看退库单 ' . $rs[ 'id' ] . ' 设备表">查看详细</a>  
								| <a href="?model=device_quit&action=cancel_order&id=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=300" class="thickbox" title="取消 ' . $rs[ 'id' ] . ' 整单设备退库">整单取消</a></td>
						</tr>
				';
			}
			return $str;
		}
	}
	/**
	 * 退库单详细列表
	 *
	 * @return unknown
	 */
	function model_device_quit_list ( )
	{
		global $func_limit;
		set_time_limit ( 0 );
		$typid = $_POST[ 'typid' ];
		$stats = $_POST[ 'stats' ];
		$sdate = $_POST[ 'sdate' ];
		$edate = $_POST[ 'edate' ];
		$field_name = $_POST[ 'field' ];
		$keyword = $_POST[ 'keyword' ];
		$sqlstr = '';
		if ( $_SESSION[ 'USER_ID' ] != 'admin' )
		{
			$sqlstr = ' and d.dept_id in(' . ( $func_limit[ '管理部门' ] ? $func_limit[ '管理部门' ] : $_SESSION[ 'DEPT_ID' ] ) . ')';
		}
		if ( $typid )
		{
			$sqlstr .= " and d.id='$typid' ";
		}
		if ( $stats === '0' || $stats == '1' )
		{
			$sqlstr .= " and a.status='$stats' ";
		}
		if ( $sdate )
		{
			$sqlstr .= " and b.date>='" . strtotime ( "$sdate" ) . "' ";
		}
		if ( $edate )
		{
			$sqlstr .= "and a.date<='" . strtotime ( "$edate" ) . "' ";
		}
		if ( $field_name && $keyword )
		{
			if ( $field_name == 'device_name' )
			{
				$sqlstr .= " and c.$field_name like '%$keyword%' ";
			} elseif ( in_array ( $field_name , array ( 
														'project_id' , 
														'coding' , 
														'dpcoding' , 
														'fitting' , 
														'list_id' 
			) ) )
			{
				$sqlstr .= " and b.$field_name like '%$keyword%' ";
			} else
			{
				$sqlstr .= " and h.field_id='$field_name' and h.content like '%$keyword%' ";
			}
		}
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from device_quit_order_info where 1" );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num > 0 )
		{
			$query = $this -> query ( "select 
										a.id,a.info_id,a.status,a.notse,a.date,a.rand_key,b.date as b_date
										,c.typeid as tid,d.typename
									from 
										device_quit_order_info as a
										left join device_info as b on b.id=a.info_id
										left join device_list as c on c.id=b.list_id
									    left join device_type as d on d.id=c.typeid
									    left join area as e on e.id=b.area
									    left join device_type_field_content as h on h.info_id=b.id
									where 1 $sqlstr" );
			$info_id = array ();
			$notse = array ();
			$insert_date = array ();
			$quit_date = array ();
			$status = array ();
			$quit_id = array ();
			$doid = array ();
			$rand_key = array ();
			$stock = new model_device_stock ( );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$info_id[ ] = $rs[ 'info_id' ];
				$doid[ ] = $rs[ 'id' ];
				$notse[ $rs[ 'info_id' ] ] = $rs[ 'notse' ];
				$insert_date[ $rs[ 'info_id' ] ] = date ( 'Y-m-d' , $rs[ 'b_date' ] );
				$quit_date[ $rs[ 'info_id' ] ] = date ( 'Y-m-d' , $rs[ 'date' ] );
				$status[ $rs[ 'info_id' ] ] = $rs[ 'status' ];
				$quit_id[ $rs[ 'info_id' ] ] = $rs[ 'id' ];
				$rand_key[ $rs[ 'info_id' ] ] = $rs[ 'rand_key' ];
			}
			if ( $info_id )
			{
				$data = $stock -> model_device_info ( $info_id , $doid );
				$data = str_replace ( '<td>借出数量</td>' , '<td>入库日期</td><td>退库日期</td><td>退库原因</td><td>操作</td>' , $data );
				$data = str_replace ( '<td><input type="checkbox"' , '<td style="display:none;"><input type="hidden"' , $data );
				preg_match_all ( '/<td width="80" id="borrows_(.+?)">(.+?)<\/td>/' , $data , $arr );
				if ( $arr[ 0 ] )
				{
					foreach ( $arr[ 0 ] as $key => $val )
					{
						$data = str_replace ( '<td width="80" id="borrows_' . $arr[ 1 ][ $key ] . '">' . $arr[ 2 ][ $key ] . '</td>' , '<td>' . $insert_date[ $arr[ 1 ][ $key ] ] . '</td>
						<td>' . $quit_date[ $arr[ 1 ][ $key ] ] . '</td>
						<td>' . $notse[ $arr[ 1 ][ $key ] ] . '</td>
						<td>' . ( $status[ $arr[ 1 ][ $key ] ] != 1 ? '<a href="?model=device_quit&action=cancel_info&id=' . $quit_id[ $arr[ 1 ][ $key ] ] . '&key=' . $rand_key[ $arr[ 1 ][ $key ] ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=300" class="thickbox" title="取消 退库">取消退库</a>' : '已取消退库' ) . '</td>' , $data );
					}
				}
			}
			return $data;
		
		}
	
	}
	
	/**
	 * 退库单详细
	 * @param $orderid
	 */
	function model_order_info ( $orderid )
	{
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from device_quit_order_info where orderid=$orderid" );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num > 0 )
		{
			
			$query = $this -> query ( "select 
										a.id,a.info_id,a.status,a.notse,a.date,a.rand_key,b.date as b_date
									from 
										device_quit_order_info as a
										left join device_info as b on b.id=a.info_id
									where orderid=$orderid" );
			$info_id = array ();
			$notse = array ();
			$insert_date = array ();
			$quit_date = array ();
			$status = array ();
			$quit_id = array ();
			$rand_key = array ();
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$info_id[ ] = $rs[ 'info_id' ];
				$notse[ $rs[ 'info_id' ] ] = $rs[ 'notse' ];
				$insert_date[ $rs[ 'info_id' ] ] = date ( 'Y-m-d' , $rs[ 'b_date' ] );
				$quit_date[ $rs[ 'info_id' ] ] = date ( 'Y-m-d' , $rs[ 'date' ] );
				$status[ $rs[ 'info_id' ] ] = $rs[ 'status' ];
				$quit_id[ $rs[ 'info_id' ] ] = $rs[ 'id' ];
				$rand_key[ $rs[ 'info_id' ] ] = $rs[ 'rand_key' ];
			}
			if ( $info_id )
			{
				$stock = new model_device_stock ( );
				$data = $stock -> model_device_info ( $info_id );
				$data = str_replace ( '<td>库存数量</td>' , '' , $data );
				$data = str_replace ( '<td>借出数量</td>' , '<td>入库日期</td><td>退库日期</td><td>退库原因</td><td>操作</td>' , $data );
				$data = str_replace ( '<td><input type="checkbox"' , '<td style="display:none;"><input type="hidden"' , $data );
				preg_match_all ( '/<td width="80" id="amount_(.+?)">(.+?)<\/td>/' , $data , $arr );
				$data = preg_replace ( '/<td width="80" id="borrows_(.+?)">(.+?)<\/td>/' , '' , $data );
				if ( $arr[ 0 ] )
				{
					foreach ( $arr[ 0 ] as $key => $val )
					{
						$data = str_replace ( '<td width="80" id="amount_' . $arr[ 1 ][ $key ] . '">' . $arr[ 2 ][ $key ] . '</td>' , '<td>' . $insert_date[ $arr[ 1 ][ $key ] ] . '</td>
						<td>' . $quit_date[ $arr[ 1 ][ $key ] ] . '</td>
						<td>' . $notse[ $arr[ 1 ][ $key ] ] . '</td>
						<td>' . ( $status[ $arr[ 1 ][ $key ] ] != 1 ? '<a href="?model=device_quit&action=cancel_info&id=' . $quit_id[ $arr[ 1 ][ $key ] ] . '&key=' . $rand_key[ $arr[ 1 ][ $key ] ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=300" class="thickbox" title="取消 退库">取消退库</a>' : '已取消退库' ) . '</td>' , $data );
					}
				}
			}
			
			return $data;
			/*$query = $this->query ( "
				select
					a.*,d.coding,d.dpcoding,d.fitting,d.price,d.date as add_date,e.device_name,f.typename
				from 
					device_quit_order_info as a
					left join device_quit_order as b on b.id=a.orderid
					left join user as c on c.user_id=b.operatorid
					left join device_info as d on d.id=a.info_id
					left join device_list as e on e.id=d.list_id
					left join device_type as f on f.id=e.typeid
				where
					a.orderid=$orderid
			" );
			while ( ($rs = $this->fetch_array ( $query )) != false ) {
				$str .= '
					<tr>
						<td>' . $rs['info_id'] . '</td>
						<td>' . $rs['typename'] . '</td>
						<td>' . $rs['device_name'] . '</td>
						<td>' . $rs['coding'] . '</td>
						<td>' . $rs['dpcoding'] . '</td>
						<td>' . $rs['fitting'] . '</td>
						<td>' . $rs['price'] . '</td>
						<td>' . date ( 'Y-m-d', $rs['add_date'] ) . '</td>
						<td>' . date ( 'Y-m-d', $rs['date'] ) . '</td>
						<td>' . ($rs['status'] == 1 ? '已取消' : '成功') . '</td>
						<td>' . $rs['notse'] . '</td>
						<td>' . ($rs['status'] != 1 ? '<a href="?model=device_quit&action=cancel_info&id=' . $rs['id'] . '&key=' . $rs['rand_key'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=300" class="thickbox" title="取消 ' . $rs['device_name'] . ' 退库">取消退库</a>' : '') . '</td>
					</tr>
				';
			}
			return $str;*/
		}
	}
	
	/**
	 * 批量查询
	 * @param $id
	 */
	function model_batch_search ( $id = '' )
	{
		global $func_limit;
		if ( $_POST[ 'field' ] && $_POST[ 'content' ] )
		{
			$arr = explode ( "\r\n" , $_POST[ 'content' ] );
			foreach ( $arr as $v )
			{
				$content .= "'" . $v . "',";
			}
			$content = rtrim ( $content , ',' );
			if ( is_numeric ( $_POST[ 'field' ] ) )
			{
				$where = " where h.field_id=" . $_POST[ 'field' ] . " and h.content in ($content)";
			} else if ( $_POST[ 'field' ] == 'device_name' )
			{
				$where = " where b.device_name in ($content) ";
			} else
			{
				$where = " where a." . $_POST[ 'field' ] . " in ($content)";
			}
			if ( $_SESSION[ 'USER_ID' ] != 'admin' )
			{
				
				$where .= " and a.dept_id in (" . ( $func_limit[ '管理部门' ] ? $func_limit[ '管理部门' ] : $_SESSION[ 'DEPT_ID' ] ) . ")";
			}
		} elseif ( is_array ( $id ) )
		{
			$where = "where a.id in (" . implode ( ',' , $id ) . ")";
		} else
		{
			return false;
		}
		$query = $this -> query ( "
				select
					a.*,b.device_name,c.typename
				from 
					device_info as a
					left join device_list as b on b.id=a.list_id
					left join device_type as c on c.id=b.typeid
					left join device_type_field_content as h on h.info_id=a.id
				$where  AND a.state=IF(a.amount<=a.borrow_num,0,state)
			" );
		$info_id = array ();
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$info_id[ ] = $rs[ 'id' ];
			/*switch ($rs['state']) {
				case 0 :
					$status = '';
					break;
				case 1 :
					$status = '被借用';
					break;
				case 2 :
					$status = '待认领';
					break;
				case 3 :
					$status = '维修中';
					break;
				case 4 :
					$status = '已退库';
					break;
				default :
					$status = '不可用';
			}
			$tmp = '
						<td>' . $rs['id'] . '</td>
						<td>' . $rs['typename'] . '</td>
						<input type="hidden" name="typename[' . $rs['id'] . ']" value="' . $rs['typename'] . '" />
						<td>' . $rs['device_name'] . '</td>
						<input type="hidden" name="device_name[' . $rs['id'] . ']" value="' . $rs['device_name'] . '" />
						<td>' . $rs['coding'] . '</td>
						<input type="hidden" name="coding[' . $rs['id'] . ']" value="' . $rs['coding'] . '" />
						<td>' . $rs['dpcoding'] . '</td>
						<input type="hidden" name="dpcoding[' . $rs['id'] . ']" value="' . $rs['dpcoding'] . '" />
						<td>' . date ( 'Y-m-d', $rs['date'] ) . '</td>
						<input type="hidden" name="date[' . $rs['id'] . ']" value="' . $rs['date'] . '" />
						<td id="num_' . $rs['id'] . '">' . $rs['amount'] . '</td>
						<input type="hidden" name="amount[' . $rs['id'] . ']" value="' . $rs['amount'] . '" />
					';
			if ($id) {
				$str .= '<tr>
							<td><input type="checkbox" ' . ($rs['state'] > 0 ? 'disabled' : 'checked') . ' name="id[]" value="' . $rs['id'] . '" /></td>
							' . $tmp . '
							<td><input type="text" size="5" id="amount_' . $rs['id'] . '" onblur="quite(' . $rs['id'] . ')" name="quit_num[' . $rs['id'] . ']" ' . ($status ? 'disabled' : '') . ' value="' . ($status ? '0' : '1') . '" /></td>
							<td><input type="text" size="20" id="notse_' . $rs['id'] . ' name="notse[' . $rs['id'] . ']" ' . ($status ? 'disabled' : '') . ' value="' . $status . '" />
						</tr>
					';
			} else {
				$str .= '<tr>
							<td><input type="checkbox" ' . ($rs['state'] > 0 ? 'disabled' : 'checked') . ' name="id[]" value="' . $rs['id'] . '" /></td>
							' . $tmp . '
							<td><input type="text" size="5" id="amount_' . $rs['id'] . '" onblur="quite(' . $rs['id'] . ')" name="quit_num[' . $rs['id'] . ']" ' . ($status ? 'disabled' : '') . ' value="' . ($status ? '0' : '1') . '" /></td>
							<td><input type="text" size="20" id="notse_' . $rs['id'] . '" name="notse[' . $rs['id'] . ']" ' . ($status ? 'disabled' : '') . ' value="' . $status . '" />
						</tr>
					';
			}*/
		}
		if ( $info_id )
		{
			$stock = new model_device_stock ( );
			$data = $stock -> model_device_info ( $info_id );
			//$data = preg_replace ( '/<td width="80" id="borrows_(.+?)">(.+?)<\/td>/' , '' , $data );
			return $data;
		} else
		{
			return false;
		}
	
		//return $str;
	}
	/**
	 * 添加记录
	 * @param $data
	 */
	function model_save_order ( $data )
	{
		
		if ( is_array ( $data ) )
		{
			
			$this -> tbl_name = 'device_quit_order';
			$orderid = $this -> create ( array ( 
												'dept_id' => $_SESSION[ 'DEPT_ID' ] , 
												'operatorid' => $_SESSION[ 'USER_ID' ] , 
												'date' => time ( ) 
			) );
			if ( $orderid )
			{
				try
				{
					$this -> _db -> query ( "START TRANSACTION" );
					$amount = 0;
					foreach ( $data[ 'id' ] as $id )
					{
						$amount = $amount + intval ( $data[ 'amount' ][ $id ] );
						$this -> query ( "insert into device_quit_order_info set 
								dept_id=" . $_SESSION[ 'DEPT_ID' ] . ",
								orderid=$orderid,
								info_id=$id,
								amount='" . trim ( $data[ 'amount' ][ $id ] ) . "',
								notse='" . trim ( $data[ 'notse' ][ $id ] ) . "',
								date='" . time ( ) . "'
								" );
						$this -> query ( " UPDATE  `device_info` SET quit=IF(amount>" .(int) trim ( $data[ 'amount' ][ $id ] ) . ",quit,1),state=IF(amount>" .(int) trim ( $data[ 'amount' ][ $id ] ) . ",state,4),amount=IF(amount>" .(int) trim ( $data[ 'amount' ][ $id ] ) . ",amount-" .(int) trim ( $data[ 'amount' ][ $id ] ) . ",amount)    WHERE   id in ('$id')" );
							
					}
					$this -> update ( 'id=' . $orderid , array ( 
																'amount' => $amount 
					) );
					$this -> _db -> query ( "COMMIT" );
					return $this -> model_update_device_list_total ( );
				} catch ( Exception $e )
				{
					$this -> _db -> query ( "ROLLBACK" );
					return false;
				}
			
			}
		
		} else
		{
			return false;
		}
	
	}
	/**
	 * 单套设备取消退库
	 * @param $id
	 * @param $key
	 */
	function model_cancel_info ( $id , $key )
	{
		$rs = $this -> get_one ( "select info_id,amount from device_quit_order_info where id=" . $id . " and rand_key='" . $key . "'" );
		if ( $rs )
		{
			$this -> query ( "update device_quit_order_info set status=1,cancel_date='" . time ( ) . "' where id=" . $id . " and rand_key='" . $key . "'" );
			$this -> query ( "update device_info set state=IF(quit=0,state,0),borrow_num=IF(quit=0,borrow_num,0),amount=IF(quit=0,amount+" .(int) trim ( $rs[ 'amount' ]) . ",amount),quit=IF(quit=0,quit,0) where id='" . $rs[ 'info_id' ] . "'" );
			return $this -> model_update_device_list_total ( );
		} else
		{
			return false;
		}
	}
	/**
	 * 整单取消
	 * @param $orderid
	 */
	function model_cancel_order ( $orderid )
	{
		$query = $this -> query ( "select info_id,amount from device_quit_order_info where orderid=" . $orderid );
		$arr = array ();
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$this -> query ( "update device_info set state=IF(quit=0,state,0),borrow_num=IF(quit=0,borrow_num,0),amount=IF(quit=0,amount+" .(int) trim ( $rs[ 'amount' ]) . ",amount),quit=IF(quit=0,quit,0) where id='" . $rs[ 'info_id' ] . "'" );
		}
		$this -> query ( "update device_quit_order_info set status=1,cancel_date='" . time ( ) . "' where orderid=$orderid" );
		
		return $this -> model_update_device_list_total ( );
	}
	/**
	 * 获取退库单信息
	 * @param $orderid
	 */
	function model_get_order ( $orderid )
	{
		return $this -> get_one ( "
			select
				a.*,b.dept_name,d.user_name
			from 
				device_quit_order as a
				left join department as b on b.dept_id=a.dept_id
				left join user as d on d.user_id=a.operatorid
			where
				a.id=$orderid
		" );
	}
	/**
	 * 更新设备表
	 */
	function model_update_device_list_total ( )
	{
		return $this -> _db -> query ( "
									     UPDATE  device_list as a 
												LEFT JOIN (select sum(amount) as num ,sum(borrow_num) as borrow_num, avg(price) as average ,list_id  from device_info where quit=0 group by list_id   ) as b  
												ON a.id=b.list_id 
												SET
												a.total=b.num,
												a.average=b.average,
												a.borrow=b.borrow_num,
												a.surplus=b.num-b.borrow_num,
												a.rate=(CAST((b.borrow_num/b.num) AS DECIMAL(11,2)))
							" );
	}
}
?>