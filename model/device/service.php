<?php
class model_device_service extends model_base
{
	function __construct ( )
	{
		parent :: __construct ( );
	}
	
	/**
	 * 设备维修申请批量查询
	 *
	 */
	function model_search ( )
	{
		global $func_limit;
		if (  ! $func_limit[ '管理区域' ] )
		{
			showmsg ( '您没有任何访问区域权限！' );
		}
		if (  ! $func_limit[ '管理部门' ] )
		{
			showmsg ( '您没有任何访问管理权限！' );
		}
		$arr = explode ( "\r\n" , $_POST[ 'content' ] );
		foreach ( $arr as $v )
		{
			$content .= "'" . $v . "',";
		}
		$content = rtrim ( $content , ',' );
		$where = "where a." . $_POST[ 'field' ] . " in($content) and (a.amount-borrow_num) > 0 and a.area in (" . $func_limit[ '管理区域' ] . ")";
		if ( $_SESSION[ 'USER_ID' ] != 'admin' )
		{
			$where .= ' and a.dept_id in ('. $func_limit[ '管理部门' ] . ')';
		}
		$query = $this -> _db -> query ( "
		SELECT 	
			a.*,b.device_name,c.typename 
		FROM 
			device_info as a
			left join device_list as b on b.id=a.list_id 
			left join device_type as c on c.id=b.typeid 
			$where
		" );
		while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
		{
			$str .= '
			<tr>
				<td><input type="checkbox" name="id[]" value="' . $rs[ 'id' ] . '" /></td>
				<td>' . $rs[ 'id' ] . '</td>
				<td>' . $rs[ 'typename' ] . '</td>
				<td>' . $rs[ 'device_name' ] . '</td>
				<td>' . $rs[ 'dpcoding' ] . '</td>
				<td>' . $rs[ 'coding' ] . '</td>
				<td>' . $rs[ 'fitting' ] . '</td>
				<td id="amount_' . $rs[ 'id' ] . '">' . $rs[ 'amount' ] . '</td>
				<td id="borrow_' . $rs[ 'id' ] . '">' . $rs[ 'borrow_num' ] . '</td>
				<td><input type="text" size="5"  onKeyUp="this.value=value=this.value.replace(/\\D/g,\'\')" onblur="quite(' . $rs[ 'id' ] . ');" id="num_' . $rs[ 'id' ] . '" name="num[' . $rs[ 'id' ] . ']" value="1" />
				<td><input type="text" id="budget_time" name="budget_time[' . $rs[ 'id' ] . ']"  readonly onClick="WdatePicker({dateFmt:\'yyyy-MM-dd\',minDate:\'%y-%M-%d\'})" class="Wdate" value="" /></td>
				<td><input type="text" name="reason[' . $rs[ 'id' ] . ']" value="" /></td>
			</tr>
			';
		}
		return $str;
	}
	/**
	 * 维修单打印列表
	 *
	 * @return unknown
	 */
	function model_save_list ( )
	{
		if ( $_POST[ 'id' ] )
		{
			$info_id = implode ( ',' , $_POST[ 'id' ] );
			$query = $this -> _db -> query ( "
			SELECT 	
				a.*,b.device_name,c.typename 
			FROM 
				device_info as a
				left join device_list as b on b.id=a.list_id 
				left join device_type as c on c.id=b.typeid 
				where a.id in ($info_id) and quit=0
			" );
			while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
			{
				$str .= '
				<tr>
				<input type="hidden" name="id[]" value="' . $rs[ 'id' ] . '" />
				<td>' . $rs[ 'id' ] . '</td>
				<td>' . $rs[ 'typename' ] . '</td>
				<td>' . $rs[ 'device_name' ] . '</td>
				<td>' . $rs[ 'dpcoding' ] . '</td>
				<td>' . $rs[ 'coding' ] . '</td>
				<td>' . $rs[ 'fitting' ] . '</td>
				<input type="hidden" name="amount[' . $rs[ 'id' ] . ']" value="' . $_POST[ 'num' ][ $rs[ 'id' ] ] . '" />
				<td>' . $_POST[ 'num' ][ $rs[ 'id' ] ] . '</td>
				<input type="hidden" name="reason[' . $rs[ 'id' ] . ']" value="' . $_POST[ 'reason' ][ $rs[ 'id' ] ] . '" />
				<td>' . ( $_POST[ 'budget_time' ][ $rs[ 'id' ] ] ? $_POST[ 'budget_time' ][ $rs[ 'id' ] ] : $_POST[ 'budget_date' ] ) . '</td>
				<input type="hidden" name="budget_time[' . $rs[ 'id' ] . ']" value="' . ( $_POST[ 'budget_time' ][ $rs[ 'id' ] ] ? $_POST[ 'budget_time' ][ $rs[ 'id' ] ] : $_POST[ 'budget_date' ] ) . '" />
				<td>' . $_POST[ 'reason' ][ $rs[ 'id' ] ] . '</td>
				</tr>
			';
			}
			return $str;
		}
	}
	/**
	 * 保存维修单
	 *
	 */
	function model_save_order ( )
	{
		
		if ( $_POST[ 'id' ] )
		{
			$this -> tbl_name = 'device_service_order';
			$orderid = $this -> create ( array ( 
												'dept_id' => $_SESSION[ 'DEPT_ID' ] , 
												'username' => $_POST[ 'username' ] , 
												'operator' => $_POST[ 'operatorid' ] , 
												'budget_date' => strtotime ( $_POST[ 'budget_date' ] ) , 
												'date' => time ( ) 
			) );
			if ( $orderid )
			{
				try
				{
					$this -> _db -> query ( "START TRANSACTION" );
					$this -> tbl_name = 'device_service_order_info';
					$amount = 0;
					foreach ( $_POST[ 'id' ] as $val )
					{
						$amount = $amount + $_POST[ 'amount' ][ $val ];
						$this -> create ( array ( 
													'orderid' => $orderid , 
													'info_id' => $val , 
													'amount' => $_POST[ 'amount' ][ $val ] , 
													'reason' => $_POST[ 'reason' ][ $val ] , 
													'budget_time' => strtotime ( $_POST[ 'budget_time' ][ $val ] ) , 
													'date' => time ( ) 
						) );
						$this -> _db -> query ( "update device_info set state=3,amount=amount-1 where id=$val and amount>0" ); //设置设备状态
					}
					$this -> _db -> query ( "COMMIT" );
					$this -> tbl_name = 'device_service_order';
					return $this -> update ( 'id=' . $orderid , array ( 
																		'amount' => $amount 
					) );
				} catch ( Exception $e )
				{
					$this -> _db -> query ( "ROLLBACK" );
					return false;
				}
			
			}
		}
	
	}
	/**
	 * 维修单列表
	 *
	 */
	function model_order_list ( )
	{
		if ( $_SESSION[ 'USER_ID' ] != 'admin' )
		{
			$where .= ' where a.dept_id=' . $_SESSION[ 'DEPT_ID' ];
		}
		$where = $where ? $where : 'where a.id>0 ';
		$where .= $_GET[ 'start_date' ] ? ' and a.date>=' . strtotime ( $_GET[ 'start_date' ] ) : '';
		$where .= $_GET[ 'end_date' ] ? ' and a.date<=' . strtotime ( $_GET[ 'end_date' ] . ' 23:59:59' ) : '';
		if (  ! $this -> num )
		{
			$rs = $this -> _db -> get_one ( "select count(0) as num from device_service_order as a $where" );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num > 0 )
		{
			$query = $this -> _db -> query ( "
		SELECT 
			a.*,b.name as areaname,c.user_name 
		FROM 
			device_service_order as a
			left join area as b on b.id=a.area 
			left join user as c on c.user_id=a.operator 
			$where
			order by a.id desc
			limit $this->start," . pagenum . "
		" );
			while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
			{
				$str .= '
			<tr>
				<td>' . $rs[ 'id' ] . '</td>
				<td>' . $rs[ 'amount' ] . '</td>
				<td>' . $rs[ 'username' ] . '</td>
				<td>' . $rs[ 'user_name' ] . '</td>
				<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
				<td><a href="?model=device_service&action=order_info&orderid=' . $rs[ 'id' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450" title="查看《' . $rs[ 'username' ] . '》- ' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '日接收的维修单" class="thickbox">查看详细</a></td>
			</tr>
			';
			}
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array ( 
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num . '&start_date=' . $_GET[ 'start_date' ] . '&end_date=' . $_GET[ 'end_date' ] );
			return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
		} else
		{
			return false;
		}
	}
	/**
	 * 维修定单信息
	 */
	function model_order_info ( )
	{
		if ( intval ( $_GET[ 'orderid' ] ) )
		{
			$query = $this -> _db -> query ( "
			SELECT
				a.*,e.typename,d.device_name,c.dpcoding,c.coding
			FROM
				device_service_order_info as a
				left join device_service_order as b on b.id=a.orderid
				left join device_info as c on c.id=a.info_id
				left join device_list as d on d.id=c.list_id 
				left join device_type as e on e.id=d.typeid 
				where orderid=" . $_GET[ 'orderid' ] . "
			" );
			while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
			{
				$str .= '
				<tr>
					<td>' . $rs[ 'info_id' ] . '</td>
					<td>' . $rs[ 'typename' ] . '</td>
					<td>' . $rs[ 'device_name' ] . '</td>
					<td>' . $rs[ 'dpcoding' ] . '</td>
					<td>' . $rs[ 'coding' ] . '</td>
					<td>' . $rs[ 'fitting' ] . '</td>
					<td>' . $rs[ 'amount' ] . '</td>
					<td>' . date ( 'Y-m-d' , $rs[ 'budget_time' ] ) . '</td>
					<td>' . ( $rs[ 'actual_date' ] ? date ( 'Y-m-d' , $rs[ 'actual_date' ] ) : '尚未完成' ) . '</td>
					<td>' . $rs[ 'reason' ] . '</td>
				</tr>
				';
			}
			return $str;
		}
	}
	
	function model_info ( $tid = '' )
	{
		$tid = $tid ? $tid : $_GET[ 'tid' ];
		$query = $this -> query ( "
							select 
								a.*,b.coding,b.dpcoding,c.username,d.user_name 
							from 
								device_service_order_info as a
								left join device_info as b on b.id=a.info_id
								left join device_service_order as c on c.id=a.orderid
								left join user as d on d.user_id=c.operator
							where info_id=" . $tid );
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '<tr>
						<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
						<td>' . ( $rs[ 'actual_date' ] ? date ( 'Y-m-d' , $rs[ 'actual_date' ] ) : '' ) . '</td>
						<td>' . round ( $rs[ 'actual_date' ] ? ( $rs[ 'date' ] - $rs[ 'actual_date' ] ) / 86400 : ( time ( ) - $rs[ 'date' ] ) / 96400 ) . '</td>
						<td>' . $rs[ 'user_name' ] . '</td>
						<td>' . $rs[ 'username' ] . '</td>
						<td>' . $rs[ 'reason' ] . '</td>
					</tr>
					
			';
		}
		return $str;
	
	}
}
?>