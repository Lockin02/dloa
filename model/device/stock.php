<?php
class model_device_stock extends model_base
{
	public $page;
	public $num;
	public $start;
	/**
	 * 构造函数
	 *
	 */
	function __construct ( )
	{
		$this -> page = $_GET[ 'page' ] ? $_GET[ 'page' ] : 1;
		$this -> start = ( $this -> page == 1 ) ? 0 : ( $this -> page - 1 ) * pagenum;
		$this -> num = $_GET[ 'num' ] ? $_GET[ 'num' ] : false;
		parent :: __construct ( );
		//系统自动更新确认状态
		$this -> query ( "update 
						device_borrow_order_info as a,device_borrow_order as b,device_info as c
					 set
					 	b.confirm=1,a.claim=1,c.state=1
					 where
					 	b.id=a.orderid and c.id=a.info_id and a.claim=0   AND a.date>0 AND c.state=2 and ceil((UNIX_TIMESTAMP()-a.date) /86400) >=8
						" );
	}
	/**
	 * 获取设备类型
	 * @param $dept_id
	 */
	function get_type_data ( $dept_id = null )
	{
		$where = '';
		if ( $dept_id && is_array ( $dept_id ) )
		{
			$where = " where a.dept_id in (" . ( implode ( ',' , $dept_id ) ) . ")";
		} elseif ( $dept_id )
		{
			$where = " where a.dept_id in ($dept_id)";
		}
		
		$query = $this -> query ( "select 
											a.*,b.dept_name 
										from 
											device_type as a
										left join department as b on b.dept_id=a.dept_id 
										 $where 
										 order by a.dept_id ASC" );
		$data = array ();
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$data[ ] = $rs;
		}
		
		return $data;
	}
	/**
	 * 类型下拉
	 *
	 */
	function select_type ( $typeid = '' , $dept_id = '' , $limit = true )
	{
		global $func_limit;
		$this -> tbl_name = 'device_type';
		if ( $limit && $_SESSION[ 'USER_ID' ] != 'admin' )
		{
			$where = 'where a.dept_id in(' . ( $func_limit[ '管理部门' ] ? $func_limit[ '管理部门' ] : $_SESSION[ 'DEPT_ID' ] ) . ')';
		}
		if ( $dept_id )
		{
			$where .= $where ? $where . " and a.dept_id='$dept_id'" : " where a.dept_id='$dept_id'";
		}
		$query = $this -> _db -> query ( "
										select 
											a.*,b.dept_name 
										from 
											device_type as a
										left join department as b on b.dept_id=a.dept_id 
										 $where 
										 order by a.dept_id ASC" );
		$str .= '<option value="">全部</option>';
										 
		while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
		{
			if ( $rs[ 'id' ] == $typeid )
			{
				$str .= '<option selected value="' . $rs[ 'id' ] . '">' . $rs[ 'typename' ] . ( ( $_SESSION[ 'USER_ID' ] == 'admin' ||  ! $limit ) &&  ! $dept_id ? '--' . $rs[ 'dept_name' ] : '' ) . '</option>';
			} else
			{
				$str .= '<option value="' . $rs[ 'id' ] . '">' . $rs[ 'typename' ] . ( ( $_SESSION[ 'USER_ID' ] == 'admin' ||  ! $limit ) &&  ! $dept_id ? '--' . $rs[ 'dept_name' ] : '' ) . '</option>';
			}
		}
		return $str;
	}
		/**
	 * 类型下拉
	 *
	 */
	function get_type ()
	{
		global $func_limit;
		$arr=array();
		$this -> tbl_name = 'device_type';
		if ($_SESSION[ 'USER_ID' ] != 'admin' )
		{
			$where = 'where a.dept_id in(' . ( $func_limit[ '管理部门' ] ? $func_limit[ '管理部门' ] : $_SESSION[ 'DEPT_ID' ] ) . ')';
		}
		$query = $this -> _db -> query ( "
										select 
											a.*,b.dept_name 
										from 
											device_type as a
										left join department as b on b.dept_id=a.dept_id 
										 $where 
										 GROUP BY a.id  order by a.dept_id ASC " );
		while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
		{
			$arr[]=$rs[ 'id' ];
			
		}
		return $arr;
	}
	/**
	 * 库存总表
	 *
	 */
	function model_list ( $export = false )
	{
		global $func_limit;
		$typeId=$_GET[ 'typeid' ]?$_GET[ 'typeid' ]:$_POST[ 'typeid' ];
		$typeName=$_GET[ 'typeName' ]?$_GET[ 'typeName' ]:$_POST[ 'typeName' ];
		if ( $_SESSION[ 'USER_ID' ] == 'admin' )
		{
			$where = '';
		} else
		{
			$where = 'where a.dept_id in(' . ( $func_limit[ '管理部门' ] ? $func_limit[ '管理部门' ] : $_SESSION[ 'DEPT_ID' ] ) . ')';
		}
		if ( $typeName )
		{
			if ( $where )
			{
				$where .= "  and a.device_name like '%$typeName%'" ;
			} else
			{
				$where = "  where  a.device_name like '%$typeName%'" ;
			}
		}
		if ( intval ($typeId ) )
		{
			if ( $where )
			{
				$where .= ' and a.typeid=' . $typeId;
			} else
			{
				$where = 'where a.typeid=' . $typeId;
			}
		}
		if (  ! $this -> num )
		{
			$rs = $this -> _db -> get_one ( "select count(0) as num from device_list as a $where " );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num > 0 )
		{
			$last_month_start = mktime ( 0 , 0 , 0 , date ( "m" ) - 1 , 1 , date ( "Y" ) ); //上月第一日
			$last_month_end = mktime ( 23 , 59 , 59 , date ( "m" ) , 0 , date ( "Y" ) ); //上月最后一日
			$this_month_start = mktime ( 0 , 0 , 0 , date ( "m" ) , 1 , date ( "Y" ) ); //本月第一日
			$query = $this -> _db -> query ( "
			select 
				a.*,b.typename,c.name,sum(d.amount) as d_count,sum(e.amount) as e_count 
			from 
				device_list as a
				left join device_type as b on b.id=a.typeid 
				left join area as c on c.id=a.area 
				left join (select amount,list_id from device_info where date>=$this_month_start and date<=" . time ( ) . " and quit=0) as d on d.list_id=a.id
				left join (select sum(amount) as amount,list_id from device_info where date<=$last_month_end and quit=0 group by list_id) as e on e.list_id=a.id 
				$where
				group by a.id
				order by b.id desc,a.device_name asc
				" . (  ! $export ? "limit $this->start," . pagenum : '' ) );
			$data = array ();
			while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
			{
				$row = $this -> count_area ( $rs[ 'id' ] );
				if ( $export )
				{
					$temp = array ();
					$temp[ ] = $rs[ 'typename' ];
					$temp[ ] = $rs[ 'device_name' ];
					$temp[ ] = $rs[ 'budgetPrice' ];
					$temp[ ] = $rs[ 'unit' ];
					$temp[ ] = $rs[ 'total' ];
					$temp[ ] = $rs[ 'borrow' ];
					$temp[ ] = $rs[ 'surplus' ];
					
					//珠海
					$temp[ ] = $row[ 1 ][ 'num' ] ? $row[ 1 ][ 'num' ] : 0;
					$temp[ ] = $row[ 1 ][ 'borrow_num' ] ? $row[ 1 ][ 'borrow_num' ] : 0;
					$temp[ ] = $row[ 1 ][ 'num' ] - $row[ 1 ][ 'borrow_num' ];
					//西安
					$temp[ ] = $row[ 6 ][ 'num' ] ? $row[ 6 ][ 'num' ] : 0;
					$temp[ ] = $row[ 6 ][ 'borrow_num' ] ? $row[ 6 ][ 'borrow_num' ] : 0;
					$temp[ ] = $row[ 6 ][ 'num' ] - $row[ 6 ][ 'borrow_num' ];
					//沈阳
					$temp[ ] = $row[ 7 ][ 'num' ] ? $row[ 7 ][ 'num' ] : 0;
					$temp[ ] = $row[ 7 ][ 'borrow_num' ] ? $row[ 7 ][ 'borrow_num' ] : 0;
					$temp[ ] = $row[ 7 ][ 'num' ] - $row[ 7 ][ 'borrow_num' ];
					
					//广州
					$temp[ ] = $row[ 4 ][ 'num' ] ? $row[ 4 ][ 'num' ] : 0;
					$temp[ ] = $row[ 4 ][ 'borrow_num' ] ? $row[ 4 ][ 'borrow_num' ] : 0;
					$temp[ ] = $row[ 4 ][ 'num' ] - $row[ 4 ][ 'borrow_num' ];
					
					$temp[ ] = $rs[ 'e_count' ] ? $rs[ 'e_count' ] : 0;
					$temp[ ] = $rs[ 'd_count' ] ? $rs[ 'd_count' ] : 0;
					$temp[ ] = $rs[ 'lose' ];
					$temp[ ] = "￥" . number_format ( $rs[ 'average' ] );
					$temp[ ] = $rs[ 'discount' ];
					$temp[ ] = $rs[ 'rate' ];
					$temp[ ] = $rs[ 'inventory' ];
					$temp[ ] = $rs[ 'notse' ];
					$data[ ] = $temp;
				} else
				{
					$str .= '
					<tr id="tr_' . $rs[ 'id' ] . '">
						<td><a href="?model=device_stock&action=deviceInfoTypelist&typeid=' . $rs[ 'typeid' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600" class="thickbox" title="查看 [' . $rs[ 'typename' ] . '] 详细记录" >' . $rs[ 'typename' ] . '</a></td>
						<td id="device_name_' . $rs[ 'id' ] . '" align="left"><a href="?model=device_stock&action=device_info_list&typeid=' . $rs[ 'typeid' ] . '&id=' . $rs[ 'id' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600" class="thickbox" title="查看 [' . $rs[ 'device_name' ] . '] 详细记录" >' . $rs[ 'device_name' ] . '</a></td>
						<td id="budgetPrice_' . $rs[ 'id' ] . '">' . $rs[ 'budgetPrice' ] . '</td>
						<td id="unit_' . $rs[ 'id' ] . '">' . $rs[ 'unit' ] . '</td>
						
						<td class="total">' . $rs[ 'total' ] . '</td>
						<td class="total">' . $rs[ 'borrow' ] . '</td>
						<td class="total">' . $rs[ 'surplus' ] . '</td>
						
						<td class="none zhuhai">' . ( $row[ 1 ][ 'num' ] ? $row[ 1 ][ 'num' ] : 0 ) . '</td>
						<td class="none zhuhai">' . ( $row[ 1 ][ 'borrow_num' ] ? $row[ 1 ][ 'borrow_num' ] : 0 ) . '</td>
						<td class="none zhuhai">' . ( $row[ 1 ][ 'num' ] - $row[ 1 ][ 'borrow_num' ] ) . '</td>
						
						<td class="none beijing">' . ( $row[ 6 ][ 'num' ] ? $row[ 6 ][ 'num' ] : 0 ) . '</td>
						<td class="none beijing">' . ( $row[ 6 ][ 'borrow_num' ] ? $row[ 6 ][ 'borrow_num' ] : 0 ) . '</td>
						<td class="none beijing">' . ( $row[ 6 ][ 'num' ] - $row[ 6 ][ 'borrow_num' ] ) . '</td>
						
						<td class="none shenyang">' . ( $row[ 7 ][ 'num' ] ? $row[ 7 ][ 'num' ] : 0 ) . '</td>
						<td class="none shenyang">' . ( $row[ 7 ][ 'borrow_num' ] ? $row[ 7 ][ 'borrow_num' ] : 0 ) . '</td>
						<td class="none shenyang">' . ( $row[ 7 ][ 'num' ] - $row[ 7 ][ 'borrow_num' ] ) . '</td>
						
						
						<td class="none guangzhou">' . ( $row[ 4 ][ 'num' ] ? $row[ 4 ][ 'num' ] : 0 ) . '</td>
						<td class="none guangzhou">' . ( $row[ 4 ][ 'borrow_num' ] ? $row[ 4 ][ 'borrow_num' ] : 0 ) . '</td>
						<td class="none guangzhou">' . ( $row[ 4 ][ 'num' ] - $row[ 4 ][ 'borrow_num' ] ) . '</td>
						
						<td class="none td_bj">' . ( $rs[ 'e_count' ] ? $rs[ 'e_count' ] : 0 ) . '</td>
						<td class="none td_bj">' . ( $rs[ 'd_count' ] ? $rs[ 'd_count' ] : 0 ) . '</td>
						<td class="none td_bj" id="lose_' . $rs[ 'id' ] . '">' . $rs[ 'lose' ] . '</td>
						<td class="none">￥' . number_format ( $rs[ 'average' ] ) . '</td>
						<td class="none" id="discount_' . $rs[ 'id' ] . '">' . $rs[ 'discount' ] . '</td>
						<td class="none">' . $rs[ 'rate' ] . '</td>
						<td class="none" id="inventory_' . $rs[ 'id' ] . '">' . $rs[ 'inventory' ] . '</td>
						<td class="none" id="notse_' . $rs[ 'id' ] . '">' . $rs[ 'notse' ] . '</td>
						<td id="edit_' . $rs[ 'id' ] . '" width="20%">
							' . thickbox_link ( '使用说明' , 'a' , 'list_id=' . $rs[ 'id' ] , $rs[ 'device_name' ] . ' 的说明' , null , 'description' , 500 , 300 ) . '
							 | <a href="?model=device_stock&action=show_addinfo&id=' . $rs[ 'id' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400" class="thickbox" title="添加 [' . $rs[ 'device_name' ] . '] ">添加</a> 
							| <a href="javascript:edit(' . $rs[ 'id' ] . ',\'' . $rs[ 'rand_key' ] . '\')">修改</a>
							| <a href="javascript:del(' . $rs[ 'id' ] . ',\'' . $rs[ 'rand_key' ] . '\')">删除</a>
							' . ( $func_limit[ '超级管理员' ] ? '| <a href="javascript:update(' . $rs[ 'id' ] . ',\'' . $rs[ 'rand_key' ] . '\')">修正</a>' : '' ) . '
							</td>
					</tr>
					';
				}
			}
			if ( $export )
				return $data;
			if ( $this -> num > pagenum )
			{
				$showpage = new includes_class_page ( );
				$showpage -> show_page ( array ( 
												
												'total' => $this -> num , 
												'perpage' => pagenum 
				) );
				$showpage -> _set_url ( 'num=' . $this -> num . '&typeid=' . $_GET[ 'typeid' ] );
				return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
			} else
			{
				return $str;
			}
		}
	}
	/**
	 * 设备搜索
	 */
	function model_search ( )
	{
		global $func_limit;
		
		$typeid = $_GET[ 'typeid' ] ? $_GET[ 'typeid' ] : $_POST[ 'typeid' ];
		$state = $_GET[ 'state' ] || $_GET[ 'state' ] == '0' ? $_GET[ 'state' ] : $_POST[ 'state' ];
		$field_name = $_GET[ 'field' ] ? $_GET[ 'field' ] : $_POST[ 'field' ];
		$field_name = is_array ( $field_name ) ? $field_name : ( $field_name ? explode ( ',' , $field_name ) : '' );
		$keyword = $_GET[ 'keyword' ] ? $_GET[ 'keyword' ] : $_POST[ 'keyword' ];
		$keyword = is_array ( $keyword ) ? $keyword : ( $keyword ? explode ( ',' , $keyword ) : '' );
		$symbol = $_GET[ 'symbol' ] ? $_GET[ 'symbol' ] : $_POST[ 'symbol' ];
		$symbol = is_array ( $symbol ) ? $symbol : ( $symbol ? explode ( ',' , $symbol ) : '' );
		$sort = $_GET[ 'sort' ] ? $_GET[ 'sort' ] : $_POST[ 'sort' ];
		$sort = $sort ? $sort : 'id';
		//=============
		$where = $state || $state == '0' ? " and a.state='$state'" : '';
		if (  ! empty ( $field_name[ 0 ] ) && is_array ( $field_name ) )
		{
			$data = array ();
			$i = 0;
			$joinleft = '';
			foreach ( $field_name as $key => $val )
			{
				if ( is_numeric ( $val ) )
				{
					if ( $i > 0 )
					{
						$joinleft .= ' left join device_type_field_content as h' . $i . ' on h' . $i . '.info_id=h.info_id';
						$where .= " and h" . $i . ".field_id=$val " . jqgrid_sopt ( 'h' . $i . '.content' , $keyword[ $key ] , $symbol[ $key ] );
					} else
					{
						$joinleft .= ' left join device_type_field_content as h on h.info_id=a.id';
						$where .= " and h.field_id=$val " . jqgrid_sopt ( 'h.content' , $keyword[ $key ] , $symbol[ $key ] );
					}
					$i ++ ;
				} else
				{
					if ( $val == 'device_name' )
					{
						$where .= jqgrid_sopt ( 'b.' . $val , $keyword[ $key ] , $symbol[ $key ] );
					} else
					{
						$where .= jqgrid_sopt ( 'a.' . $val , $keyword[ $key ] , $symbol[ $key ] );
					}
				}
			}
		}
		if ( $typeid )
		{
			$rs = $this -> get_fixed_field_name ( $typeid , false );
			$field = $this -> model_show_field_name ( $typeid , false );
			if (  ! $this -> num )
			{
				$rrs = $this -> _db -> get_one ( "
					select 
						count(distinct a.id) as num
					from 
						device_info as a
						left join device_list as b on b.id=a.list_id
						$joinleft
					where 
						a.quit=0 
						and b.typeid = $typeid 
						$where
			" );
				$this -> num = $rrs[ 'num' ];
			}
			if ( $this -> num > 0 )
			{
				$query = $this -> query ( "
					select
						a.*,b.device_name,b.description,c.typename,f.user_name,g.name
						" . ( $joinleft ? ',h.content' : '' ) . "
					from
						device_info as a
						left join device_list as b on b.id=a.list_id
						left join device_type as c on c.id=b.typeid
						left join (select info_id,returndate,date,orderid from device_borrow_order_info order by id desc) as d on d.info_id=a.id
						left join device_borrow_order as e on e.id=d.orderid 
						left join user as f on f.user_id=e.userid 
						left join area as g on g.id=a.area
						$joinleft						
					where
						a.quit=0
						and b.typeid = $typeid 
						$where
					group by a.id
					order by b.device_name,a.id desc
					limit $this->start," . pagenum );
			} else
			{
				return '<tr><td colspan="35">没有数据</td></tr>';
			}
			while ( ( $row = $this -> fetch_array ( $query ) ) != false )
			{
				$str .= '<tr>';
				$str .= '<td><input type="checkbox" ' . ( $row[ 'state' ] != 0 ? 'disabled' : '' ) . ' name="id[]" value="' . $row[ 'id' ] . '" /></td>';
				$str .= '<td>' . $row[ 'id' ] . '</td>';
				$str .= '<td>' . ( $row[ 'description' ] ? thickbox_link ( $row[ 'device_name' ] , 'a' , 'list_id=' . $row[ 'list_id' ] , $row[ 'device_name' ] . ' 的使用说明' , null , 'show_description' , 500 , 400 ) : $row[ 'device_name' ] ) . '</td>';
				foreach ( $rs as $key => $val )
				{
					if (  ! $val )
					{
						switch ( $key )
						{
							case '_coding' :
								$row[ 'coding' ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
								$str .= '<td>' . $row[ 'coding' ] . '</td>';
								break;
							case '_dpcoding' :
								$row[ 'dpcoding' ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
								$str .= '<td>' . $row[ 'dpcoding' ] . '</td>';
								break;
							case '_fitting' :
								$row[ 'fitting' ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
								$str .= '<td>' . $row[ 'fitting' ] . '</td>';
								break;
							case '_price' :
								$row[ 'price' ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
								$str .= '<td>￥' . number_format ( $row[ 'price' ] , 2 ) . '</td>';
								break;
							case '_notes' :
								$row[ 'notes' ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
								$str .= '<td>' . $row[ 'notes' ] . '</td>';
								break;
						}
					}
				}
				if ( $field )
				{
					$field_data = $this -> get_field_content ( $row[ 'id' ] );
					foreach ( $field as $key => $val )
					{
						if ( $field_data[ $val[ 'id' ] ] )
						{
							$str .= '<td width="100" style="word-wrap:break-word;word-break:break-all" title="' . $field_data[ $val[ 'id' ] ] . '">' . $field_data[ $val[ 'id' ] ] . '</td>';
						} else
						{
							$str .= '<td>--</td>';
						}
					}
					unset ( $field_data );
				}
				$str .= '<td>' . $row[ 'name' ] . '</td>';
				$str .= '<td>' . ( $row[ 'state' ] == 1 || $row[ 'state' ] == 2 ? $row[ 'user_name' ] : '' ) . '</td>';
				switch ( $row[ 'state' ] )
				{
					case 0 :
						$str .= '<td>可用</td>';
						break;
					case 1 :
						$str .= '<td><span>' . thickbox_link ( '被借用' , 'a' , 'id=' . $row[ 'id' ] , '查看详细' , 'device_borrow' , 'noedevice_list' , 400 , 500 ) . '</span></td>';
						break;
					case 2 :
						$str .= '<td><span>等待确认</span></td>';
						break;
					case 3 :
						$str .= '<td><span>维修</span></td>';
						break;
					case 4 :
						$str .= '<td><span>退库</span></td>';
						break;
					case 5 :
						$_SESSION[ 'USER_ID' ] == 'admin' || $func_limit[ '申请查看' ] ? $str .= '<td><span>' . thickbox_link ( '<span>待审批</span>' , 'a' , 'id=' . $row[ 'id' ] , '查看详细' , null , 'show_apply_info' , 400 , 250 ) . '</span></span></td>' : $str .= '<td><span>待审批</span></td>';
						break;
					case 6 :
						$str .= '<td><span>待受理</span></td>';
						break;
					default :
						$str .= '<td><span>不可用</span></td>';
				}
				$str .= '<td>' . ( $row[ 'state' ] == 0 ? '<a href="javascript:add(' . $row[ 'id' ] . ')">我要借</a>' : '--' ) . '</td>';
			}
			if ( $this -> num > 0 )
			{
				$showpage = new includes_class_page ( );
				$showpage -> show_page ( array ( 
												
												'total' => $this -> num , 
												'perpage' => pagenum 
				) );
				$showpage -> _set_url ( 'num=' . $this -> num . '&sort=' . $sort . '&typeid=' . $typeid . '&field=' . ( $field_name ? implode ( ',' , $field_name ) : '' ) . '&state=' . $state . '&symbol=' . ( $symbol ? implode ( ',' , $symbol ) : '' ) . '&keyword=' . ( $keyword ? implode ( ',' , $keyword ) : '' ) );
				return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
			} else
			{
				return $str;
			}
		
		} else
		{
			return false;
		
		}
	}
		/**
	 * 设备搜索
	 */
	function model_searchs ($typeid )
	{
		global $func_limit;
		
		$state = $_GET[ 'state' ] || $_GET[ 'state' ] == '0' ? $_GET[ 'state' ] : $_POST[ 'state' ];
		$field_name = $_GET[ 'field' ] ? $_GET[ 'field' ] : $_POST[ 'field' ];
		$field_name = is_array ( $field_name ) ? $field_name : ( $field_name ? explode ( ',' , $field_name ) : '' );
		$keyword = $_GET[ 'keyword' ] ? $_GET[ 'keyword' ] : $_POST[ 'keyword' ];
		$keyword = is_array ( $keyword ) ? $keyword : ( $keyword ? explode ( ',' , $keyword ) : '' );
		$symbol = $_GET[ 'symbol' ] ? $_GET[ 'symbol' ] : $_POST[ 'symbol' ];
		$symbol = is_array ( $symbol ) ? $symbol : ( $symbol ? explode ( ',' , $symbol ) : '' );
		$sort = $_GET[ 'sort' ] ? $_GET[ 'sort' ] : $_POST[ 'sort' ];
		$sort = $sort ? $sort : 'id';
		//=============
		$where = $state || $state == '0' ? " and a.state='$state'" : '';
		if($keyword[0]){
			$where .= " and  ( a.coding like '%$keyword[0]%' or a.dpcoding like '%$keyword[0]%' or a.fitting like '%$keyword[0]%' or a.notes like '%$keyword[0]%' or b.device_name like '%$keyword[0]%' )";
		}
		
		if ( $typeid )
		{
			$rs = $this -> get_fixed_field_name ( $typeid , false );
			$field = $this -> model_show_field_name ( $typeid , false );
				$rrs = $this -> _db -> get_one ( "
					select 
						count(distinct a.id) as num
					from 
						device_info as a
						left join device_list as b on b.id=a.list_id
						$joinleft
					where 
						a.quit=0 
						and b.typeid = $typeid 
						$where
			" );
			if ($rrs[ 'num' ]  > 0 )
			{
				$query = $this -> query ( "
					select
						a.*,b.device_name,b.description,c.typename,f.user_name,g.name
						" . ( $joinleft ? ',h.content' : '' ) . "
					from
						device_info as a
						left join device_list as b on b.id=a.list_id
						left join device_type as c on c.id=b.typeid
						left join (select info_id,returndate,date,orderid from device_borrow_order_info WHERE return_num=0 order by id desc) as d on d.info_id=a.id
						left join device_borrow_order as e on e.id=d.orderid 
						left join user as f on f.user_id=e.userid 
						left join area as g on g.id=a.area
						$joinleft						
					where
						a.quit=0
						and b.typeid = $typeid 
						$where
					group by a.id
					order by b.device_name,a.id desc
					"  );
			} else
			{
				return false;//'<tr><td colspan="35">没有数据</td></tr>';
			}
			
			while ( ( $row = $this -> fetch_array ( $query ) ) != false )
			{
				$str .= '<tr>';
				$str .= '<td><input type="checkbox" ' . ( $row[ 'state' ] != 0 ? 'disabled' : '' ) . ' name="id[]" value="' . $row[ 'id' ] . '" /></td>';
				$str .= '<td>' . $row[ 'id' ] . '</td>';
				$str .= '<td>' . ( $row[ 'description' ] ? thickbox_link ( $row[ 'device_name' ] , 'a' , 'list_id=' . $row[ 'list_id' ] , $row[ 'device_name' ] . ' 的使用说明' , null , 'show_description' , 500 , 400 ) : $row[ 'device_name' ] ) . '</td>';
				foreach ( $rs as $key => $val )
				{
					if (  ! $val )
					{
						switch ( $key )
						{
							case '_coding' :
								$row[ 'coding' ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
								$str .= '<td>' . $row[ 'coding' ] . '</td>';
								break;
							case '_dpcoding' :
								$row[ 'dpcoding' ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
								$str .= '<td>' . $row[ 'dpcoding' ] . '</td>';
								break;
							case '_fitting' :
								$row[ 'fitting' ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
								$str .= '<td>' . $row[ 'fitting' ] . '</td>';
								break;
							case '_price' :
								$row[ 'price' ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
								$str .= '<td>￥' . number_format ( $row[ 'price' ] , 2 ) . '</td>';
								break;
							case '_notes' :
								$row[ 'notes' ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
								$str .= '<td>' . $row[ 'notes' ] . '</td>';
								break;
						}
					}
				}
				if ( $field )
				{
					$field_data = $this -> get_field_content ( $row[ 'id' ] );
					foreach ( $field as $key => $val )
					{
						if ( $field_data[ $val[ 'id' ] ] )
						{
							$str .= '<td width="100" style="word-wrap:break-word;word-break:break-all" title="' . $field_data[ $val[ 'id' ] ] . '">' . $field_data[ $val[ 'id' ] ] . '</td>';
						} else
						{
							$str .= '<td>--</td>';
						}
					}
					unset ( $field_data );
				}
				$str .= '<td>' . $row[ 'name' ] . '</td>';
				$str .= '<td>' . ( $row[ 'state' ] == 1 || $row[ 'state' ] == 2 ? $row[ 'user_name' ] : '' ) . '</td>';
				switch ( $row[ 'state' ] )
				{
					case 0 :
						$str .= '<td>可用</td>';
						break;
					case 1 :
						$str .= '<td><span>' . thickbox_link ( '被借用' , 'a' , 'id=' . $row[ 'id' ] , '查看详细' , 'device_borrow' , 'noedevice_list' , 400 , 500 ) . '</span></td>';
						break;
					case 2 :
						$str .= '<td><span>等待确认</span></td>';
						break;
					case 3 :
						$str .= '<td><span>维修</span></td>';
						break;
					case 4 :
						$str .= '<td><span>退库</span></td>';
						break;
					case 5 :
						$_SESSION[ 'USER_ID' ] == 'admin' || $func_limit[ '申请查看' ] ? $str .= '<td><span>' . thickbox_link ( '<span>待审批</span>' , 'a' , 'id=' . $row[ 'id' ] , '查看详细' , null , 'show_apply_info' , 400 , 250 ) . '</span></span></td>' : $str .= '<td><span>待审批</span></td>';
						break;
					case 6 :
						$str .= '<td><span>待受理</span></td>';
						break;
					default :
						$str .= '<td><span>不可用</span></td>';
				}
				$str .= '<td>' . ( $row[ 'state' ] == 0 ? '<a href="javascript:add(' . $row[ 'id' ] . ')">我要借</a>' : '--' ) . '</td>';
			}

				return $str;
		
		
		} else
		{
			return false;
		
		}
	}
	/**
	 * 区域统计
	 * Enter description here ...
	 * @param $id
	 */
	function count_area ( $id )
	{
		$query = $this -> query ( "select sum(amount) as num,sum(borrow_num)as borrow_num ,area from device_info where list_id=$id AND quit=0 group by area" );
		$row = array ();
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$row[ $rs[ 'area' ] ] = $rs;
		}
		return $row;
	}
	/**
	 * 转库记录列表
	 */
	function model_shift_list ( $tid )
	{
		$where = $tid ? 'where a.info_id=' . $tid : '';
		if (  ! $this -> num )
		{
			$rs = $this -> _db -> get_one ( "
                select
                    count(0) as num
                from
                    device_shift_list as a
                $where
                " );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num > 0 )
		{
			$query = $this -> _db -> query ( "
                select
                    a.*,b.typename,c.device_name,e.user_name,f.name as area_name,g.name as to_area_name
                from
                    device_shift_list as a
                    left join device_list as c on c.id=a.list_id
                    left join device_type as b on b.id=c.typeid
                    left join device_info as d on d.id=a.info_id
                    left join user as e on e.user_id=a.userid
                    left join area as f on f.id=a.area
                    left join area as g on g.id=a.to_area
                $where
                order by a.id desc
                limit $this->start," . pagenum . "
            " );
			while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
			{
				$str .= '
                  <tr>
                  	<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
                    <!--<td>' . $rs[ 'info_id' ] . '</td>
                    <td>' . $rs[ 'typename' ] . '</td>
                    <td>' . $rs[ 'device_name' ] . '</td>
                    <td>' . $rs[ 'amount' ] . '</td>-->
                    <td>' . $rs[ 'area_name' ] . '</td>
                    <td>' . $rs[ 'to_area_name' ] . '</td>
                    <td>' . $rs[ 'user_name' ] . '</td>
                </tr>
                ';
			}
			if ( $this -> num > pagenum )
			{
				$showpage = new includes_class_page ( );
				$showpage -> show_page ( array ( 
												
												'total' => $this -> num , 
												'perpage' => pagenum 
				) );
				$showpage -> _set_url ( 'num=' . $this -> num . '&tid=' . $_GET[ 'tid' ] );
				return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
			} else
			{
				return $str;
			}
		}
	}
	
	/**
	 * 添加
	 *
	 */
	function model_insert ( )
	{
		if ( $_POST )
		{
			$rs = $this -> _db -> get_one ( "select dept_id,area from device_type where id=" . $_POST[ 'typeid' ] );
			if ( $rs )
			{
				$this -> tbl_name = 'device_list';
				return $this -> create ( array ( 
												
												'typeid' => $_POST[ 'typeid' ] , 
												'dept_id' => $rs[ 'dept_id' ] , 
												'area' => $rs[ 'area' ] , 
												'device_name' => $_POST[ 'device_name' ] , 
												'unit' => $_POST[ 'unit' ] 
				) );
			} else
			{
			
			}
		} else
		{
			showmsg ( '非法提交！' );
		}
	}
	/**
	 * 修改
	 *
	 */
	function model_update ( )
	{
		$this -> tbl_name = 'device_list';
		if ( $_POST[ 'id' ] && $_POST[ 'key' ] )
		{
			return $this -> update ( array ( 
											'id' => $_POST[ 'id' ] , 
											'rand_key' => $_POST[ 'key' ] 
			) , array ( 
						'device_name' => $_POST[ 'device_name' ] , 
						'budgetPrice' => $_POST[ 'budgetPrice' ] , 
						'unit' => $_POST[ 'unit' ] , 
						'discount' => $_POST[ 'discount' ] , 
						'lose' => $_POST[ 'lose' ] , 
						'notse' => $_POST[ 'notse' ] , 
						'inventory' => $_POST[ 'inventory' ] 
			) );
		}
	}
	
	/**
	 * 删除
	 *
	 */
	function model_delete ( )
	{
	
	}
	/**
	 * 导入Excel数据
	 */
	function model_save_import ( )
	{
		$filename = $_FILES[ 'upfile' ][ 'tmp_name' ];
		require_once WEB_TOR . 'includes/classes/PHPExcel.php'; //包含类   
		require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
		require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel2007.php';
		$PHPExcel = new PHPExcel ( );
		$PHPReader = new PHPExcel_Reader_Excel2007 ( $PHPExcel );
		if (  ! $PHPReader -> canRead ( $filename ) )
		{
			$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
		}
		if ( $PHPReader -> canRead ( $filename ) )
		{
			$Excel = $PHPReader -> load ( $filename );
			$countnum = $Excel -> getSheetCount ( );
			$this -> _db -> ping ( );
			for ( $i = 0 ; $i < $countnum ; $i ++  )
			{
				if ( $i == 0 )
				{ //添加类别和设备
					$data = $Excel -> getSheet ( $i ) -> toArray ( );
					$data = mb_iconv ( $data );
					$type_name = $data[ 2 ][ 1 ];
					$device_name = $data[ 1 ][ 1 ];
					$dept_id = $_SESSION[ 'DEPT_ID' ];
					$rs = $this -> get_one ( "select id from device_type where typename='" . $type_name . "'" );
					if ( $rs )
					{
						$typeid = $rs[ 'id' ];
					} else
					{
						$this -> query ( "insert into device_type set dept_id='" . $dept_id . "',typename='$type_name'" );
						$typeid = $this -> _db -> insert_id ( );
					}
					if ( $typeid )
					{
						$rs = $this -> get_one ( "select id from device_list where device_name='" . $device_name . "'" );
						if ( $rs )
						{
							$list_id = $rs[ 'id' ];
						} else
						{
							$this -> query ( "insert into device_list set typeid='$typeid',dept_id='$dept_id',device_name='" . $device_name . "',unit='套',total=1" );
							$list_id = $this -> _db -> insert_id ( );
						}
					}
					$area = array ( 
									
									'珠海' => 1 , 
									'北京' => 2 , 
									'上海' => 3 , 
									'广州' => 4 , 
									'沈阳' => 5 
					);
					foreach ( $data as $key => $row )
					{
						if ( $key > 12 )
						{
							if ( $row[ 0 ] > 0 )
							{
								$rs = $this -> get_one ( "
											select id from device_info where coding='" . $row[ 1 ] . "' and dpcoding='" . $row[ 2 ] . "'
									" );
								if (  ! $rs )
								{
									$this -> query ( "
											insert into device_info set 
											dept_id='$dept_id',
											list_id='$list_id',
											coding='" . $row[ 1 ] . "',
											dpcoding='" . $row[ 2 ] . "',
											fitting='" . $row[ 7 ] . "',
											price='" . str_replace ( '￥' , '' , $row[ 4 ] ) . "',
											amount=1,
											date='" . strtotime ( $row[ 3 ] ) . "',
											area='" . ( $row[ 5 ] ? $area[ $row[ 5 ] ] : '' ) . "',
											notes='" . $row[ 6 ] . "'
										" );
								}
							}
						}
					}
					return $this -> _db -> query ( "
											update 
												device_list as a, 
												(select sum(amount)  as num ,sum(borrow_num)as borrow_num, avg(price) as average ,list_id  from device_info where quit=0 group by list_id   ) as b  
											set 
												a.total=b.num,
												a.average=b.average,
												a.surplus=b.num-b.borrow_num  
												where a.id=b.list_id
										" );
				
		//导入设备成功
				} else
				{ //借还记录
					

					break; //暂时不导入历史记录。
				}
			}
			unset ( $Excel , $PHPExcel , $PHPReader );
		} else
		{
			return false;
		}
	}
	//##########################################单设备管理##########################
	/**
	 * 单设备列表
	 *
	 * @return unknown
	 */
	function model_device_info_list ( $json = false , $exprot = false )
	{
		global $func_limit;
		if ( $_SESSION[ 'USER_ID' ] != 'admin' &&  ! $func_limit[ '管理区域' ] )
			showmsg ( '对不起，您没有任何区域管理权限！' );
		if ( $_GET[ '_search' ] == 'true' )
		{
			$filters = json_decode ( stripslashes ( stripslashes ( $_GET[ 'filters' ] ) ) , true );
			$filters = $filters ? mb_iconv ( $filters ) : null;
			$where = $this -> sopt ( $filters[ 'rules' ] );
		
		}
		//file_put_contents('rrr.txt',stripslashes(stripslashes ( $_GET['filters'] )));
		if ( intval ( $_GET[ 'id' ] ) )
		{
			$query = $this -> _db -> query ( "select distinct(id) as cid from device_type_field where typeid=" . $_GET[ 'typeid' ] . " order by sort" );
			$field = array ();
			while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
			{
				if ( $rs[ 'cid' ] )
				{
					$field[ ] = $rs[ 'cid' ];
				}
			}
			//==========================
			if (  ! $this -> num )
			{
				$rs = $this -> _db -> get_one ( "
												select 
													count(distinct a.id) as num
												from 
													device_info as a 
													left join (select info_id,returndate,date,orderid from device_borrow_order_info order by id desc) as c on c.info_id=a.id
													left join device_borrow_order as d on d.id=c.orderid 
													left join user as e on e.user_id=d.userid 
													" . ( $where ? "left join device_type_field_content as h on h.info_id=a.id" : '' ) . "
												where 
													a.list_id=" . $_GET[ 'id' ] . " 
													and a.quit=0 
													and a.area in(" . $func_limit[ '管理区域' ] . ") 
													$where
												" );
				$this -> num = $rs[ 'num' ];
			}
			//==========================
			if ( $this -> num > 0 )
			{
				$sidx = is_numeric ( $_GET[ 'sidx' ] ) ? 'k.content-' . $_GET[ 'sord' ] : ( $_GET[ 'sidx' ] ? $_GET[ 'sidx' ] . '-' . $_GET[ 'sord' ] : '' );
				$_GET[ 'sort' ] = $_GET[ 'sort' ] ? $_GET[ 'sort' ] : $sidx;
				$Prefix = $_GET[ 'sort' ] && strpos ( $_GET[ 'sort' ] , '.' ) ? '' : 'a.';
				$sort = $_GET[ 'sort' ] ? $Prefix . $_GET[ 'sort' ] : 'a.id-desc';
				$sort = str_replace ( '-' , ' ' , $sort );
				$rs = $this -> get_fixed_field_name ( $_GET[ 'typeid' ] , false );
				$query = $this -> _db -> query ( "
				select 
					 a.*,b.device_name,b.typeid,e.user_name,c.returndate,c.date as borrow_date,f.Name as areaname ,g.name as projectname,g.number as projectNo
				from 
					device_info as a 
					left join device_list as b on b.id=a.list_id 
					left join (select info_id,returndate,date,orderid from device_borrow_order_info WHERE return_num=0 order by id desc) as c on c.info_id=a.id
					left join device_borrow_order as d on d.id=c.orderid 
					left join user as e on e.user_id=d.userid 
					left join area as f on f.id=a.area 
					left join project_info as g on g.id=d.project_id 
					" . ( $where ? "left join device_type_field_content as h on h.info_id=a.id" : '' ) . "
				where 
					a.list_id=" . intval ( $_GET[ 'id' ] ) . " 
					and quit=0 
					and a.area in(" . $func_limit[ '管理区域' ] . ") 
					$where
					group by a.id
					order by $sort
					" . (  ! $exprot ? "limit $this->start," . pagenum : "" ) . "" );
					
				$data = array ();
				while ( ( $row = $this -> _db -> fetch_array ( $query ) ) != false )
				{
					if ( $json )
					{
						$row[ 'field' ] = $field;
						$data[ ] = $row;
					} else
					{
						if ( $row[ 'state' ] > 0 || ( $row[ 'amount' ] <= $row[ 'borrow_num' ] ) )
						{
							$style = 'style="background:#EEEEEE;"';
						} else
						{
							$style = '';
						}
						$str .= '
								<tr id="tr_' . $row[ 'id' ] . '" ' . $style . '>
								<td>' . $row[ 'id' ] . '</td>';
						foreach ( $rs as $key => $val )
						{
							if (  ! $val )
							{
								switch ( $key )
								{
									case '_coding' :
										$row[ 'coding' ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
										$str .= '<td id="coding_' . $row[ 'id' ] . '">' . $row[ 'coding' ] . '</td>';
										break;
									case '_dpcoding' :
										$row[ 'dpcoding' ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
										$str .= '<td id="dpcoding_' . $row[ 'id' ] . '">' . $row[ 'dpcoding' ] . '</td>';
										break;
									case '_fitting' :
										$row[ 'fitting' ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
										$str .= '<td id="fitting_' . $row[ 'id' ] . '">' . $row[ 'fitting' ] . '</td>';
										break;
									case '_price' :
										$row[ 'price' ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
										$str .= '<td id="price_' . $row[ 'id' ] . '">￥' . number_format ( $row[ 'price' ] , 2 ) . '</td>';
										break;
									case '_notes' :
										$row[ 'notes' ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
										$str .= '<td id="notes_' . $row[ 'id' ] . '">' . $row[ 'notes' ] . '</td>';
										break;
								}
							}
						}
						if ( $field )
						{
							$field_data = $this -> get_field_content ( $row[ 'id' ] );
							foreach ( $field as $key => $val )
							{
								if ( $field_data[ $val ] )
								{
									$str .= '<td name="' . $val . '" class="edit_' . $row[ 'id' ] . '">' . $field_data[ $val ] . '</td>';
								} else
								{
									$str .= '<td name="' . $val . '" class="edit_' . $row[ 'id' ] . '">--</td>';
								}
							}
							unset ( $field_data );
						}
						$str .= '<td id="date_' . $row[ 'id' ] . '">' . date ( 'Y-m-d' , $row[ 'date' ] ) . '</td>';
						switch ( $row[ 'state' ] )
						{
							case 0 :
								$str .= '<td>可用</td>';
								break;
							case 1 :
								$str .= '<td><span>借出</span></td>';
								break;
							case 2 :
								$str .= '<td><span>等待确认</span></td>';
								break;
							case 3 :
								$str .= '<td><span>维修</span></td>';
								break;
							case 4 :
								$str .= '<td><span>退库</span></td>';
								break;
							case 5 :
								$str .= '<td><span>待审批</span></td>';
								break;
							case 6 :
								$str .= '<td><span>待受理</span></td>';
								break;
							default :
								$str .= '<td><span>不可用</span></td>';
						}
						$str .= '
						<td>' . $row[ 'areaname' ] . '</td>
						<td>' . $row[ 'amount' ] . '</td>
						<td>' . $row[ 'borrow_num' ] . '</td>
						<td>' . ( $row[ 'borrow_date' ] ? $this -> rate ( $row[ 'id' ] , $row[ 'date' ] ) . '%' : '' ) . '</td>
						<td><span class="widget" style="width:80px;color:#000000" title="' . $row[ 'projectname' ] . '">' . $row[ 'projectname' ] . '</span></td>
						<td>' . ( $row[ 'returndate' ] ? $row[ 'user_name' ] : '' ) . '</td>
						<td>' . ( $row[ 'borrow_date' ] &&  ! $row[ 'returndate' ] ? date ( 'Y-m-d' , $row[ 'borrow_date' ] ) : '' ) . '</td>
						<td class="text_left" id="edit_' . $row[ 'id' ] . '"><a href="javascript:edit(' . $row[ 'id' ] . ')">修改</a> | 
						<a href="?model=device_stock&action=operate&typeid=' . $row[ 'typeid' ] . '&list_id=' . $row[ 'list_id' ] . '&tid=' . $row[ 'id' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500" class="thickbox" title="查看 ' . $row[ 'device_name' ] . ' ' . ( $row[ 'dpcoding' ] ? '部门编码为：' . $row[ 'dpcoding' ] : ' 序号为 ' . $row[ 'id' ] ) . ' 的操作记录">操作记录</a>
						';
						if ( ( $row[ 'amount' ] - $row[ 'borrow_num' ] > 0 ) && $row[ 'state' ] == 0 )
						{
							$str .= ' | <a href="?model=device_stock&action=borrow_operate&typeid=' . $row[ 'typeid' ] . '&list_id=' . $row[ 'list_id' ] . '&id=' . $row[ 'id' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" class="thickbox" title="借出 《' . $row[ 'device_name' ] . '》">借出</a>';
						}
						$str .= '</td></tr>';
					}
				}
				if ( $json )
					return $data;
				if ( $this -> num > 0 )
				{
					$showpage = new includes_class_page ( );
					$showpage -> show_page ( array ( 
													
													'total' => $this -> num , 
													'perpage' => pagenum 
					) );
					$showpage -> _set_url ( 'num=' . $this -> num . '&sort=' . $_GET[ 'sort' ] . '&typeid=' . $_GET[ 'typeid' ] . '&id=' . $_GET[ 'id' ] . '&&placeValuesBefore' );
					return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
				} else
				{
					return $str;
				}
			} else
			{
				return false;
			}
		}
	}
	/**
	 * 根据设备序号获取列表
	 * @param $info
	 */
	function model_device_info ( $info , $doid = array(),$borrowId='' )
	{
		if ( is_array ( $info ) )
		{
			$where = "where a.id in(" . implode ( ',' , $info ) . ")";
		} else
		{
			$where = "where a.id in($info)";
		}
		
		$query = $this -> query ( "
								select 
									distinct(b.typeid) as tid,c.typename 
								from 
									device_info as a
									left join device_list as b on b.id=a.list_id
									left join device_type as c on c.id=b.typeid
								$where
							" );
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '<table class="table" border="1" width="98%" cellspacing="0" cellpadding="0" id="type_' . $rs[ 'tid' ] . '" align="center">
						<tr bgcolor="#D3E5FA">
							<td colspan="30">' . $rs[ 'typename' ] . '</td>
						</tr>
						<tr class="tableheader">
							<td><input type="checkbox" onclick="set_all(this.checked,' . $rs[ 'tid' ] . ')"/></td>
							<td>序号</td>
							<td>设备名称</td>
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $this -> get_fixed_field_name ( $rs[ 'tid' ] ) ) . '
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $this -> model_show_field_name ( $rs[ 'tid' ] ) ) . '
							<td width="70">所在库存</td>
							<td>库存数量</td>
							<td>借出数量</td>
						</tr>
						' . ( $doid ? $this -> model_type_sinfo ( $rs[ 'tid' ] , $doid ) : $this -> model_type_info ( $rs[ 'tid' ] , $info,true ) ) . '
					</table><br />';
		}
		return $str;
	}

/**
	 * 根据设备序号获取列表
	 * @param $info
	 */
	function model_device_return_info ( $info , $doid = array(),$borrowId='' )
	{
		if ( is_array ( $info ) )
		{
			$where = "where a.id in(" . implode ( ',' , $info ) . ")";
		} else
		{
			$where = "where a.id in($info)";
		}
		
		$query = $this -> query ( "
								select 
									distinct(b.typeid) as tid,c.typename 
								from 
									device_info as a
									left join device_list as b on b.id=a.list_id
									left join device_type as c on c.id=b.typeid
								$where
							" );
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '<table class="table" border="1" width="98%" cellspacing="0" cellpadding="0" id="type_' . $rs[ 'tid' ] . '" align="center">
						<tr bgcolor="#D3E5FA">
							<td colspan="30">' . $rs[ 'typename' ] . '</td>
						</tr>
						<tr class="tableheader">
							<td><input type="checkbox" onclick="set_all(this.checked,' . $rs[ 'tid' ] . ')"/></td>
							<td>序号</td>
							<td>设备名称</td>
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $this -> get_fixed_field_name ( $rs[ 'tid' ] ) ) . '
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $this -> model_show_field_name ( $rs[ 'tid' ] ) ) . '
							<td width="70">所在库存</td>
							<td>库存数量</td>
							<td>借出数量</td>
						</tr>
						' . ( $doid ? $this -> model_type_sinfo ( $rs[ 'tid' ] , $doid ) : $this -> model_type_return_info ( $rs[ 'tid' ] , $info,true,$borrowId ) ) . '
					</table><br />';
		}
		return $str;
	}
	/**
	 * 指定设备类型及指定序号
	 * @param unknown_type $typeid
	 * @param unknown_type $info
	 * @param unknown_type $html
	 */
	function model_type_info ( $typeid , $info , $html = true )
	{
		$query = $this -> query ( "
								select
									a.*,b.device_name,c.typename,d.name
								from 
									device_info as a
									left join device_list as b on b.id=a.list_id
									left join device_type as c on c.id=b.typeid
									left join area as d on d.id=a.area
								where
									c.id=$typeid
									and a.id in (" . ( is_array ( $info ) ? implode ( ',' , $info ) : $info ) . ")
							" );
		$rs = $this -> get_fixed_field_name ( $typeid , false );
		$field = $this -> model_show_field_name ( $typeid , false );
		$data = array ();
		$i = 0;
		while ( ( $row = $this -> fetch_array ( $query ) ) != false )
		{
			if (  ! $html )
			{
				$data[ $i ][ ] = $row[ 'id' ];
				$data[ $i ][ ] = $row[ 'device_name' ];
				if ( $rs )
				{
					foreach ( $rs as $key => $val )
					{
						if (  ! $val )
						{
							switch ( $key )
							{
								case '_coding' :
									$data[ $i ][ ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
									break;
								case '_dpcoding' :
									$data[ $i ][ ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
									break;
								case '_fitting' :
									$data[ $i ][ ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
									break;
								case '_price' :
									$data[ $i ][ ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
									break;
								case '_notes' :
									$data[ $i ][ ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
									break;
							}
						}
					}
				}
				if ( $field )
				{
					$field_data = $this -> get_field_content ( $row[ 'id' ] );
					foreach ( $field as $key => $val )
					{
						$data[ $i ][ ] = $field_data[ $val[ 'id' ] ] ? $field_data[ $val[ 'id' ] ] : '--';
					}
					unset ( $field_data );
				}
				$data[ $i ][ ] = $row[ 'name' ];
				$data[ $i ][ ] = $row[ 'amount' ];
				$data[ $i ][ ] = $row[ 'borrow_num' ];
				$i ++ ;
			} else
			{
				$str .= '<tr id="tr_' . $row[ 'id' ] . '">';
				$str .= '<td><input type="checkbox" ' . ( $row[ 'returndate' ] ? 'disabled' : '' ) . ' name="id[]" value="' . $row[ 'id' ] . '" /></td>';
				$str .= '<td width="50">' . $row[ 'id' ] . '</td>';
				$str .= '<td width="100">' . $row[ 'device_name' ] . '</td>';
				if ( $rs )
				{
					foreach ( $rs as $key => $val )
					{
						if (  ! $val )
						{
							switch ( $key )
							{
								case '_coding' :
									$row[ 'coding' ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
									$str .= '<td>' . $row[ 'coding' ] . '</td>';
									break;
								case '_dpcoding' :
									$row[ 'dpcoding' ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
									$str .= '<td>' . $row[ 'dpcoding' ] . '</td>';
									break;
								case '_fitting' :
									$row[ 'fitting' ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
									$str .= '<td>' . $row[ 'fitting' ] . '</td>';
									break;
								case '_price' :
									$row[ 'price' ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
									$str .= '<td>￥' . number_format ( $row[ 'price' ] , 2 ) . '</td>';
									break;
								case '_notes' :
									$row[ 'notes' ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
									$str .= '<td>' . $row[ 'notes' ] . '</td>';
									break;
							}
						}
					}
				}
				if ( $field )
				{
					$field_data = $this -> get_field_content ( $row[ 'id' ] );
					foreach ( $field as $key => $val )
					{
						if ( $field_data[ $val[ 'id' ] ] )
						{
							$str .= '<td style="word-wrap:break-word;word-break:break-all" title="' . $field_data[ $val[ 'id' ] ] . '">' . $field_data[ $val[ 'id' ] ] . '</td>';
						} else
						{
							$str .= '<td>--</td>';
						}
					}
					unset ( $field_data );
				}
				$str .= '<td>' . $row[ 'name' ] . '</td>';
				$str .= '<td width="80" id="amount_' . $row[ 'id' ] . '">' . $row[ 'amount' ] . '</td>';
				$str .= '<td width="80" id="borrows_' . $row[ 'id' ] . '">' . $row[ 'borrow_num' ] . '</td>';
				$str .= '</tr>';
			}
		}
		return $html ? $str : $data;
	}
	/**
	 * 指定设备类型及指定序号
	 * @param unknown_type $typeid
	 * @param unknown_type $info
	 * @param unknown_type $html
	 */
	function model_type_return_info ( $typeid , $info='' , $html = true ,$borrowId='')
	{
		if($info){
			$strSql=" and d.id in (" . ( is_array ( $info ) ? implode ( ',' , $info ) : $info ) . ") ";
		}
		if($borrowId){
			$strSql=" and a.id in (" . ( is_array ( $borrowId ) ? implode ( ',' , $borrowId ) : $borrowId ) . ") ";
		}
		$query = $this -> query ( "
								select d.*,
									d.dept_id,d.area,d.list_id,d.coding,d.dpcoding,d.fitting,d.state,d.price,d.borrower,
									d.amount,d.borrow_num,d.rate,d.notes,d.date,d.depreciation,d.depreciationYear, 
									a.amount as borrow_amount,a.id as bid,b.device_name,b.typeid,c.typename ,f.user_name,e.userid,g.name
								from 
									device_borrow_order_info as a
									left join device_list as b on b.id=a.list_id 
									left join device_type as c on c.id=a.typeid 
									left join device_info as d on d.id=a.info_id
									left join device_borrow_order as e on e.id=a.orderid
									left join user as f on f.user_id=e.userid
									left join area as g on g.id=d.area
										where  a.returndate is null and 
									c.id=$typeid
									$strSql
							" );				
		$rs = $this -> get_fixed_field_name ( $typeid , false );
		$field = $this -> model_show_field_name ( $typeid , false );
		$data = array ();
		$i = 0;
		while ( ( $row = $this -> fetch_array ( $query ) ) != false )
		{
			if (  ! $html )
			{
				$data[ $i ][ ] = $row[ 'bid' ];
				$data[ $i ][ ] = $row[ 'device_name' ];
				if ( $rs )
				{
					foreach ( $rs as $key => $val )
					{
						if (  ! $val )
						{
							switch ( $key )
							{
								case '_coding' :
									$data[ $i ][ ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
									break;
								case '_dpcoding' :
									$data[ $i ][ ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
									break;
								case '_fitting' :
									$data[ $i ][ ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
									break;
								case '_price' :
									$data[ $i ][ ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
									break;
								case '_notes' :
									$data[ $i ][ ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
									break;
							}
						}
					}
				}
				if ( $field )
				{
					$field_data = $this -> get_field_content ( $row[ 'id' ] );
					foreach ( $field as $key => $val )
					{
						$data[ $i ][ ] = $field_data[ $val[ 'id' ] ] ? $field_data[ $val[ 'id' ] ] : '--';
					}
					unset ( $field_data );
				}
				$data[ $i ][ ] = $row[ 'name' ];
				$data[ $i ][ ] = $row[ 'amount' ];
				$data[ $i ][ ] = $row[ 'borrow_amount' ];
				$i ++ ;
			} else
			{
				$str .= '<tr id="tr_' . $row[ 'bid' ] . '">';
				$str .= '<td><input type="checkbox" ' . ( $row[ 'returndate' ] ? 'disabled' : '' ) . ' name="id[]" value="' . $row[ 'bid' ] . '" /></td>';
				$str .= '<td width="50">' . $row[ 'bid' ] . '</td>';
				$str .= '<td width="100">' . $row[ 'device_name' ] . '</td>';
				if ( $rs )
				{
					foreach ( $rs as $key => $val )
					{
						if (  ! $val )
						{
							switch ( $key )
							{
								case '_coding' :
									$row[ 'coding' ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
									$str .= '<td>' . $row[ 'coding' ] . '</td>';
									break;
								case '_dpcoding' :
									$row[ 'dpcoding' ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
									$str .= '<td>' . $row[ 'dpcoding' ] . '</td>';
									break;
								case '_fitting' :
									$row[ 'fitting' ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
									$str .= '<td>' . $row[ 'fitting' ] . '</td>';
									break;
								case '_price' :
									$row[ 'price' ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
									$str .= '<td>￥' . number_format ( $row[ 'price' ] , 2 ) . '</td>';
									break;
								case '_notes' :
									$row[ 'notes' ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
									$str .= '<td>' . $row[ 'notes' ] . '</td>';
									break;
							}
						}
					}
				}
				if ( $field )
				{
					$field_data = $this -> get_field_content ( $row[ 'id' ] );
					foreach ( $field as $key => $val )
					{
						if ( $field_data[ $val[ 'id' ] ] )
						{
							$str .= '<td style="word-wrap:break-word;word-break:break-all" title="' . $field_data[ $val[ 'id' ] ] . '">' . $field_data[ $val[ 'id' ] ] . '</td>';
						} else
						{
							$str .= '<td>--</td>';
						}
					}
					unset ( $field_data );
				}
				$str .= '<td>' . $row[ 'name' ] . '</td>';
				$str .= '<td width="80" id="amount_' . $row[ 'bid' ] . '">' . $row[ 'amount' ] . '</td>';
				$str .= '<td width="80" id="borrows_' . $row[ 'bid' ] . '">' . $row[ 'borrow_amount' ] . '</td>';
				$str .= '</tr>';
			}
		}
		return $html ? $str : $data;
	}
	
	/**
	 * 指定设备类型及指定序号
	 * @param unknown_type $typeid
	 * @param unknown_type $info
	 * @param unknown_type $html
	 */
	function model_type_sinfo ( $typeid , $info , $html = true )
	{
		$query = $this -> query ( "
								select
									a.*,b.device_name,c.typename,d.name
								from 
								    device_quit_order_info as di
									left join device_info as a on a.id=di.info_id
									left join device_list as b on b.id=a.list_id
									left join device_type as c on c.id=b.typeid
									left join area as d on d.id=a.area
								where
									c.id=$typeid
									and di.id in (" . ( is_array ( $info ) ? implode ( ',' , $info ) : $info ) . ")
							" );
		$rs = $this -> get_fixed_field_name ( $typeid , false );
		$field = $this -> model_show_field_name ( $typeid , false );
		$data = array ();
		$i = 0;
		while ( ( $row = $this -> fetch_array ( $query ) ) != false )
		{
			if (  ! $html )
			{
				$data[ $i ][ ] = $row[ 'id' ];
				$data[ $i ][ ] = $row[ 'device_name' ];
				if ( $rs )
				{
					foreach ( $rs as $key => $val )
					{
						if (  ! $val )
						{
							switch ( $key )
							{
								case '_coding' :
									$data[ $i ][ ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
									break;
								case '_dpcoding' :
									$data[ $i ][ ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
									break;
								case '_fitting' :
									$data[ $i ][ ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
									break;
								case '_price' :
									$data[ $i ][ ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
									break;
								case '_notes' :
									$data[ $i ][ ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
									break;
							}
						}
					}
				}
				if ( $field )
				{
					$field_data = $this -> get_field_content ( $row[ 'id' ] );
					foreach ( $field as $key => $val )
					{
						$data[ $i ][ ] = $field_data[ $val[ 'id' ] ] ? $field_data[ $val[ 'id' ] ] : '--';
					}
					unset ( $field_data );
				}
				$data[ $i ][ ] = $row[ 'name' ];
				$data[ $i ][ ] = $row[ 'amount' ];
				$data[ $i ][ ] = $row[ 'borrow_num' ];
				$i ++ ;
			} else
			{
				$str .= '<tr id="tr_' . $row[ 'id' ] . '">';
				$str .= '<td><input type="checkbox" ' . ( $row[ 'returndate' ] ? 'disabled' : '' ) . ' name="id[]" value="' . $row[ 'id' ] . '" /></td>';
				$str .= '<td width="50">' . $row[ 'id' ] . '</td>';
				$str .= '<td width="100">' . $row[ 'device_name' ] . '</td>';
				if ( $rs )
				{
					foreach ( $rs as $key => $val )
					{
						if (  ! $val )
						{
							switch ( $key )
							{
								case '_coding' :
									$row[ 'coding' ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
									$str .= '<td>' . $row[ 'coding' ] . '</td>';
									break;
								case '_dpcoding' :
									$row[ 'dpcoding' ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
									$str .= '<td>' . $row[ 'dpcoding' ] . '</td>';
									break;
								case '_fitting' :
									$row[ 'fitting' ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
									$str .= '<td>' . $row[ 'fitting' ] . '</td>';
									break;
								case '_price' :
									$row[ 'price' ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
									$str .= '<td>￥' . number_format ( $row[ 'price' ] , 2 ) . '</td>';
									break;
								case '_notes' :
									$row[ 'notes' ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
									$str .= '<td>' . $row[ 'notes' ] . '</td>';
									break;
							}
						}
					}
				}
				if ( $field )
				{
					$field_data = $this -> get_field_content ( $row[ 'id' ] );
					foreach ( $field as $key => $val )
					{
						if ( $field_data[ $val[ 'id' ] ] )
						{
							$str .= '<td style="word-wrap:break-word;word-break:break-all" title="' . $field_data[ $val[ 'id' ] ] . '">' . $field_data[ $val[ 'id' ] ] . '</td>';
						} else
						{
							$str .= '<td>--</td>';
						}
					}
					unset ( $field_data );
				}
				$str .= '<td>' . $row[ 'name' ] . '</td>';
				$str .= '<td width="80" id="amount_' . $row[ 'id' ] . '">' . $row[ 'amount' ] . '</td>';
				$str .= '<td width="80" id="borrows_' . $row[ 'id' ] . '">' . $row[ 'borrow_num' ] . '</td>';
				$str .= '</tr>';
			}
		}
		return $html ? $str : $data;
	}
	/**
	 * 搜索条件
	 * @param $rules
	 */
	function sopt ( $rules )
	{
		if ( is_array ( $rules ) )
		{
			$where = '';
			$number_where = array ();
			$string_where = array ();
			foreach ( $rules as $key => $row )
			{
				if ( is_numeric ( $row[ 'field' ] ) )
				{
					$number_where[ ] = ' (h.field_id=' . $row[ 'field' ] . jqgrid_sopt ( 'h.content' , $row[ 'data' ] , $row[ 'op' ] ) . ')';
				} else
				{
					$temp = jqgrid_sopt ( $row[ 'field' ] , $row[ 'data' ] , $row[ 'op' ] );
					if ( $temp )
					{
						$string_where[ ] = $temp;
					}
				}
			}
			$where .= $number_where ? ' and ' . implode ( ' or ' , $number_where ) : '';
			$where .= $string_where ? implode ( ' and ' , $string_where ) : '';
			//file_put_contents ( 'jjkk.txt',  $where);
			return $where;
		} else
		{
			return false;
		}
	}
	/**
	 * 设备使用效率
	 *
	 * @param int $id
	 * @param time $time
	 * @return unknown
	 */
	function rate ( $id , $time = '' )
	{
		if ( intval ( $id ) )
		{
			if (  ! $time )
			{
				$rs = $this -> _db -> get_one ( "select date from device_info where id=" . $id );
				$time = $rs[ 'date' ];
			}
			$dsys = ( time ( ) - $time ) / 86400; //入库天数
			$query = $this -> _db -> query ( "select returndate,date from device_borrow_order_info where info_id=" . $id );
			$num = 0;
			while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
			{
				$num = $num + ( ( $rs[ 'returndate' ] ? $rs[ 'returndate' ] : time ( ) ) - $rs[ 'date' ] ) / 86400;
			}
			return round ( ( $num / $dsys * 100 ) , 2 );
		}
	}
	/**
	 * 获取固定字段
	 *
	 * @param int $typeid
	 * @param boolean $html
	 * @return html or array
	 */
	function get_fixed_field_name ( $typeid , $html = true )
	{
		$sort_str = $_GET[ 'sort' ] ? explode ( '-' , rtrim ( $_GET[ 'sort' ] , '-' ) ) : '';
		$find = $sort_str[ 0 ];
		$sort = $sort_str[ 1 ];
		if ( $sort == 'desc' )
		{
			$by = 'asc';
		} elseif ( $sort == 'asc' )
		{
			$by = 'desc';
		} else
		{
			$by = 'asc';
		}
		if($typeid){
			$this -> tbl_name = 'device_type';
			$rs = $this -> find ( 'id=' . $typeid , null , '_coding,_dpcoding,_fitting,_price,_notes' );
			if ( $rs )
			{
				if ( $html )
				{
					$str = '';
					foreach ( $rs as $key => $val )
					{
						if ( $key == '_' . $find )
						{
							$img = $this -> sort_img ( $find , $sort );
						} else
						{
							$img = $this -> sort_img ( str_replace ( '_' , '' , $key ) , '' );
						}
						if (  ! $val )
						{
							switch ( $key )
							{
								case '_coding' :
									$str .= '<td>机身码  ' . $img . '</td>';
									break;
								case '_dpcoding' :
									$str .= '<td>部门编码  ' . $img . '</td>';
									break;
								case '_fitting' :
									$str .= '<td>配件  ' . $img . '</td>';
									break;
								case '_price' :
									$str .= '<td>单价 ' . $img . '</td>';
									break;
								case '_notes' :
									$str .= '<td>备注 ' . $img . '</td>';
									break;
							}
						}
					}
					return $str;
				} else
				{
					return $rs;
				}
			}
		}
	}
	
	/**
	 * 字段内容
	 *
	 * @param int $info_id
	 * @return string
	 */
	function get_field_content ( $info_id )
	{
		$this -> tbl_name = 'device_type_field_content';
		$data = $this -> findAll ( 'info_id=' . $info_id , null , 'field_id,content' );
		if ( $data )
		{
			$arr = array ();
			foreach ( $data as $rs )
			{
				$arr[ $rs[ 'field_id' ] ] = $rs[ 'content' ];
			}
			return $arr;
		} else
		{
			return false;
		}
	}
	/**
	 * 生成带排序URL
	 * @param $find
	 * @param $sort
	 */
	function sort_img ( $find , $sort )
	{
		$arr = array ();
		if ( $_GET )
		{
			foreach ( $_GET as $key => $value )
			{
				if ( $key != 'sort' )
				{
					$arr[ ] = sprintf ( "%s=%s" , $key , $value );
				}
			}
		}
		if ( $sort == 'desc' )
		{
			return '<a href="?' . implode ( '&' , $arr ) . '&sort=' . $find . '-asc"><img src="images/farrow_down.gif" border="0" /></a>';
		} elseif ( $sort == 'asc' )
		{
			return '<a href="?' . implode ( '&' , $arr ) . '&sort=' . $find . '-desc"><img src="images/farrow_up.gif" border="0" /></a>';
		} else
		{
			return '<a href="?' . implode ( '&' , $arr ) . '&sort=' . $find . '-desc"><img src="images/farrow.gif" border="0" /></a>';
		}
	}
	/**
	 * 获取下拉字段
	 *
	 * @return string
	 */
	function model_ajax_get_field_name ( $typeid , $selectindex = null )
	{
		if ( intval ( $typeid ) )
		{
			$row = $this -> get_fixed_field_name ( $typeid , false );
			foreach ( $row as $key => $val )
			{
				if (  ! $val )
				{
					switch ( $key )
					{
						case '_coding' :
							$str .= '<option ' . ( $selectindex && $selectindex == 'coding' ? 'selected' : '' ) . 'value="coding">机身码</option>';
							break;
						case '_dpcoding' :
							$str .= '<option ' . ( $selectindex && $selectindex == 'dpcoding' ? 'selected' : '' ) . ' value="dpcoding">部门编码</option>';
							break;
						case '_fitting' :
							$str .= '<option ' . ( $selectindex && $selectindex == 'fitting' ? 'selected' : '' ) . ' value="fitting">配件</option>';
							break;
						case '_price' :
							$str .= '<option ' . ( $selectindex && $selectindex == 'price' ? 'selected' : '' ) . ' value="price">单价</option>';
							break;
						case '_notes' :
							$str .= '<option ' . ( $selectindex && $selectindex == 'notes' ? 'selected' : '' ) . ' value="notes">备注</option>';
							break;
					}
				}
			}
			$this -> tbl_name = 'device_type_field';
			$data = $this -> findAll ( 'typeid=' . $typeid , null , 'id,fname' );
			if ( $data )
			{
				foreach ( $data as $key => $rs )
				{
					$str .= '<option ' . ( $selectindex && $selectindex == $rs[ 'id' ] ? 'selected' : '' ) . ' value="' . $rs[ 'id' ] . '">' . $rs[ 'fname' ] . '</option>';
				}
			}
			return $str;
		} elseif ( $typeid == 'all' )
		{
			$this -> tbl_name = 'device_type_field';
			$data = $this -> findAll ( null , null , 'id,fname' );
			foreach ( $data as $key => $rs )
			{
				$str .= '<option ' . ( $selectindex && $selectindex == $rs[ 'id' ] ? 'selected' : '' ) . ' value="' . $rs[ 'id' ] . '">' . $rs[ 'fname' ] . '</option>';
			}
			return $str;
		}
	}
	/**
	 * 字段名称
	 *
	 * @param int $typeid
	 * @return string
	 */
	function model_show_field_name ( $typeid , $html = true )
	{
		$this -> tbl_name = 'device_type_field';
		$data = $this -> findAll ( 'typeid=' . $typeid , 'sort' , 'id,fname,only' );
		if ( $data )
		{
			if ( $html )
			{
				foreach ( $data as $rs )
				{
					$str .= '<td width="100">' . $rs[ 'fname' ] . '</td>';
				}
			} else
			{
				return $data;
			}
		}
		return $str;
	}
	/**
	 * 显示字段
	 *
	 * @return unknown
	 */
	function model_show_field ( )
	{
		global $func_limit;
		$this -> tbl_name = 'device_list';
		$rs = $this -> find ( 'id=' . $_GET[ 'id' ] , null , 'typeid' );
		$this -> tbl_name = 'device_type';
		$row = $this -> find ( 'id=' . $rs[ 'typeid' ] , '_coding,_dpcoding,_fitting,_price,_notes' );
		$title = '';
		foreach ( $row as $key => $val )
		{
			if (  ! $val )
			{
				switch ( $key )
				{
					case '_coding' :
						$title .= '<td>机身码</td>';
						$str .= '<td><input type="text" size="15" name="coding[]" /></td>';
						break;
					case '_dpcoding' :
						$title .= '<td>部门编码</td>';
						$str .= '<td><input type="text" size="15" name="dpcoding[]" /></td>';
						break;
					case '_fitting' :
						$title .= '<td>配件</td>';
						$str .= '<td><input type="text" size="15" name="fitting[]" /></td>';
						break;
					case '_price' :
						$title .= '<td>单价</td>';
						$str .= '<td> <input type="text" onKeyUp="value=value.replace(/[^\d.]/g,\'\')" size="5" name="price[]" value="0" /></td>';
						break;
					case '_notes' :
						$title .= '<td>备注</td>';
						$str .= '<td> <input type="text"  size="15" name="notes[]" value="" /></td>';
						break;
				}
			}
		}
		$this -> tbl_name = 'device_type_field';
		$field = $this -> findAll ( 'typeid=' . $rs[ 'typeid' ] , 'sort' );
		if ( $field )
		{
			foreach ( $field as $key => $row )
			{
				$title .= '<td>' . $row[ 'fname' ] . '</td>';
				if ( $row[ 'property' ] == 'text' )
				{
					$str .= '<td width="180"><input type="text" size="15" id="text_' . $row[ 'id' ] . '" name="fname[' . $row[ 'id' ] . '][]" value="" /> </td>';
				} elseif ( $row[ 'property' ] == 'int' )
				{
					$str .= '<td><input type="text" size="8" onKeyUp="value=value.replace(/[^\d]/g,\'\')" name="fname[' . $row[ 'id' ] . '][]" value="" /> </td>';
				} elseif ( $row[ 'property' ] == 'date' )
				{
					$str .= '<td width="180"><input type="text" readonly size="12" onblur="chkdate(' . $row[ 'id' ] . ')" id="date_' . $row[ 'id' ] . '" name="fname[' . $row[ 'id' ] . '][]" onClick="WdatePicker()" class="Wdate" value="" /> </td>';
				}
			}
		}
		$title .= '<td>数量</td>';
		$str .= '<td><input type="text" onKeyUp="value=value.replace(/[^\d]/g,\'\')" onblur="if (this.value== 0) alert(\'数量至少为1\');this.value=\'1\';" size="5" name="amount[]" value="1" /></td>';
		$gl = new includes_class_global ( );
		$area_data = $gl -> get_area ( explode ( ',' , $func_limit[ '管理区域' ] ) );
		if ( $area_data )
		{
			$area_option = '';
			foreach ( $area_data as $key => $val )
			{
				$area_option .= '<option value="' . $key . '">' . $val . '</option>';
			}
		}
		$title .= '<td>库存</td>';
		$str .= '<td><select name="area[]">' . $area_option . '</select></td>';
		$title .= '<td>折旧价</td>';
		$str .= '<td><input type="text" onKeyUp="value=value.replace(/[^\d.]/g,\'\')" onblur="if (this.value==\'\') {alert(\'折旧价不能为空\');this.value=\'1\';}" size="5" name="depreciation[]" value="1" /></td>';
		$title .= '<td>折旧年限</td>';
		$str .= '<td><input type="text" onKeyUp="value=value.replace(/[^\d]/g,\'\')" onblur="if (this.value==\'\'){alert(\'折旧年限不能为空\');this.value=\'1\';}" size="5" name="depreciationYear[]" value="1" /></td>';
		
		return '<tr>' . $title . '<td width="100">操作</td><tr><tr id="tr_0">' . $str . '<td width="80" align="center" id="td_0"><input type="button" onclick="copy(0);" value="复制" /></td></tr>';
	}
	/**
	 * 添加单设备信息
	 *
	 * @return unknown
	 */
	function model_add_deviceinfo ( )
	{
		if ( intval ( $_GET[ 'listid' ] ) && $_POST[ 'amount' ] )
		{
			$rs = $this -> _db -> get_one ( "select dept_id,area from device_list where id=" . $_GET[ 'listid' ] );
			if ( $rs )
			{
				$savenum = count ( $_POST[ 'amount' ] );
				for ( $i = 0 ; $i < $savenum ; $i ++  )
				{
					$this -> tbl_name = 'device_info';
					$this -> pk = 'id';
					$info_id = $this -> create ( array ( 
														
														'dept_id' => $rs[ 'dept_id' ] , 
														'area' => $_POST[ 'area' ][ $i ] , 
														'list_id' => intval ( $_GET[ 'listid' ] ) , 
														'coding' => ( $_POST[ 'coding' ][ $i ] ? $_POST[ 'coding' ][ $i ] : '' ) , 
														'dpcoding' => ( $_POST[ 'dpcoding' ][ $i ] ? $_POST[ 'dpcoding' ][ $i ] : '' ) , 
														'fitting' => ( $_POST[ 'fitting' ][ $i ] ? $_POST[ 'fitting' ][ $i ] : '' ) , 
														'price' => $_POST[ 'price' ][ $i ] , 
														'notes' => $_POST[ 'notes' ][ $i ] , 
														'amount' => $_POST[ 'amount' ][ $i ] , 
														'depreciation' => $_POST[ 'depreciation' ][ $i ] , 
														'depreciationYear' => $_POST[ 'depreciationYear' ][ $i ] , 
														'date' => time ( ) 
					) );
					if ( $_POST[ 'fname' ] )
					{
						$this -> tbl_name = 'device_type_field_content';
						foreach ( $_POST[ 'fname' ] as $key => $row )
						{
							$this -> create ( array ( 
														
														'field_id' => $key , 
														'info_id' => $info_id , 
														'content' => $row[ $i ] 
							) );
						}
					}
				}
				if ( intval ( $_GET[ 'listid' ] ) )
				{
					$info = $this -> _db -> get_one ( '
													select 
														sum(amount) as num,sum(amount*price) as money 
													from 
														device_info 
													where 
														quit=0 and list_id=' . $_GET[ 'listid' ] );
					if ( $info )
					{
						$average = $info[ 'money' ] / $info[ 'num' ];
						$this -> tbl_name = 'device_list';
						$this -> update ( 'id=' . $_GET[ 'listid' ] , array ( 
																				
																				'total' => $info[ 'num' ] 
						) );
						return $this -> _db -> query ( "update device_list set surplus=(total-borrow),average=$average where id=" . $_GET[ 'listid' ] );
					} else
					{
						return false;
					}
				} else
				{
					return false;
				}
			} else
			{
				return false;
			}
		} else
		{
			return false;
		}
	}
	/**
	 * 更新单设备信息
	 *
	 * @return unknown
	 */
	function model_update_info ( )
	{
		if ( intval ( $_POST[ 'id' ] ) )
		{
			if ( $_POST[ 'fname' ] )
			{
				$this -> tbl_name = 'device_type_field_content';
				//var_dump($_POST['fname']);
				foreach ( $_POST[ 'fname' ] as $key => $val )
				{
					if ( $val !== '[object]' )
					{
						//echo $key.'=='.$val.'|';
						$this -> update ( array ( 
																			
																			'info_id' => $_POST[ 'id' ] , 
																			'field_id' => $key 
						) , array ( 
									
									'content' => $val 
						) );
					}
				}
			}
			//============
			$this -> tbl_name = 'device_info';
			$this -> update ( 'id=' . $_POST[ 'id' ] , array ( 
																
																'coding' => $_POST[ 'coding' ] , 
																'dpcoding' => $_POST[ 'dpcoding' ] , 
																'fitting' => $_POST[ 'fitting' ] , 
																'price' => $_POST[ 'price' ] , 
																'notes' => $_POST[ 'notes' ] 
			) );
			$rs = $this -> find ( 'id=' . $_POST[ 'id' ] , null , 'list_id' );
			$info = $this -> _db -> get_one ( 'select sum(amount) as num,sum(amount*price) as money from device_info where list_id=' . $rs[ 'list_id' ] );
			$average = $info[ 'money' ] / $info[ 'num' ];
			$this -> tbl_name = 'device_list';
			$this -> update ( 'id=' . $rs[ 'list_id' ] , array ( 
																
																'total' => $info[ 'num' ] 
			) );
			return $this -> _db -> query ( "update device_list set surplus=(total-borrow),average=$average where id=" . $rs[ 'list_id' ] );
		
		}
	}
	/**
	 * 借出设备
	 */
	function model_borrow_device_info ( )
	{
		if ( intval ( $_GET[ 'id' ] ) )
		{
			$this -> tbl_name = 'device_borrow_order';
			$orderid = $this -> create ( array ( 
												
												'userid' => $_POST[ 'userid' ] , 
												'dept_id' => $_SESSION[ 'DEPT_ID' ] , 
												'project_id' => $_POST[ 'project_id' ] , 
												'operatorid' => $_SESSION[ 'USER_ID' ] , 
												'manager' => $_POST[ 'managerid' ] , 
												'area' => $_POST[ 'area' ] , 
												'targetdate' => strtotime ( $_POST[ 'targettime' ] ) , 
												'date' => time ( ) 
			) );
			$this -> tbl_name = 'device_borrow_order_info';
			$info = $this -> create ( array ( 
												
												'orderid' => $orderid , 
												'info_id' => $_GET[ 'id' ] , 
												'typeid' => $_GET[ 'typeid' ] , 
												'list_id' => $_GET[ 'list_id' ] , 
												'amount' => intval ( $_POST[ 'amount' ] ) , 
												'targetdate' => strtotime ( $_POST[ 'targettime' ] ) , 
												'notse' => $_POST[ 'notse' ] , 
												'date' => strtotime ( $_POST[ 'date' ] ) 
			) );
			if ( $info )
			{
				$this -> _db -> query ( "update device_info set borrow_num=borrow_num+" . $_POST[ 'amount' ] . ",state=2 where id=" . $_GET[ 'id' ] );
				$row = $this -> _db -> get_one ( '
					select sum(amount) as num,sum(amount*price) as money from 
					device_info where list_id=' . $_GET[ 'list_id' ] );
				$average = $row[ 'money' ] / $row[ 'num' ];
				$this -> _db -> query ( "update device_list set total=" . $row[ 'num' ] . ",borrow=borrow+1,surplus=surplus-1,average=$average, rate=(CAST((borrow/total) AS DECIMAL(11,2)))  where id=" . $_GET[ 'list_id' ] );
			}
			return $this -> _db -> query ( "update device_borrow_order set amount='" . $_POST[ 'amount' ] . "' where id=$orderid" );
		}
	}
	
	/**
	 * 单字段修改内容
	 * @param unknown_type $id
	 * @param unknown_type $key
	 * @param unknown_type $val
	 */
	function model_edit_field ( $id , $key , $val )
	{
		$conAsNameI=array('coding'=>'机身码','dpcoding'=>'部门编码','fitting'=>'配件','price'=>'单价','notes'=>'备注',
							   'date'=>'入库日期','depreciation'=>'折旧价','depreciationYear'=>'折旧年限','85'=>'固定资产编号',
							''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',);
		if ( is_numeric ( $key ) )
		{
			$rs = $this -> _db -> get_one ( "select id,content from device_type_field_content where field_id=$key and info_id=$id"  );
			if($rs['content']!=$val){
					//更新主表
				$this -> tbl_name = 'device_log';
				$info_id = $this -> create ( array (
													'tableName' =>'device_info', 
													'conId' => intval ( $id ) , 
													'conName' => $key ,
													'conAsName' =>$conAsNameI[$key], 
													'oldValues' => $rs['content'] , 
													'values' => $val , 
													'userId' => $_SESSION['USER_ID'] , 
													'userName' => $_SESSION['USER_NAME'] , 
													'type' =>2, 
													'createDate' => date("Y-m-d H:i:s") 
				) );
			}
			if($rs['id']){
				return $this -> query ( "update device_type_field_content set content='$val' where field_id=$key and info_id=$id" );
			}else{
				
				//填充自定义字段内容
			 $this -> tbl_name = 'device_type_field_content';

					return $this -> create ( array ( 
												'field_id' => $key , 
												'info_id' => $id , 
												'content' => $val 
					) );
			}
			
		} else
		{
			file_put_contents ( 'nn.txt' , "update device_info set $key = '$val' where id=$id" );
			$rs = $this -> _db -> get_one ( "select $key from device_info where id=$id"  );			
			if($rs[$key]!=$val){
					//更新主表
				$this -> tbl_name = 'device_log';
				$info_id = $this -> create ( array (
													'tableName' =>'device_info', 
													'conId' => intval ( $id ) , 
													'conName' => $key ,
													'conAsName' =>$conAsNameI[$key], 
													'oldValues' => $rs[$key] , 
													'values' => $val , 
													'userId' => $_SESSION['USER_ID'] , 
													'userName' => $_SESSION['USER_NAME'] , 
													'type' =>1, 
													'createDate' => date("Y-m-d H:i:s") 
				) );
			}
			return $this -> query ( "update device_info set $key = '$val' where id=$id" );
		}
	}
	
	function model_Loglist ($tableName ,$conId )
	{
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from device_log where tableName='$tableName' and conId='$conId'" );
			$this -> num = $rs[ 'num' ];
		}
		$query = $this -> query ( "
								select
									a.*
								from
									device_log a
								where
									a.tableName='$tableName' and a.conId='$conId'
								order by a.id desc
								limit $this->start," . pagenum . "
		" );
		
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '
					<tr>
						<td>' . $rs[ 'userName' ]. '</td>
						<td>' . ($rs[ 'conAsName' ]?$rs[ 'conAsName' ]:$rs[ 'conName' ]) . '</td>
						<td>' . $rs[ 'oldValues' ]. '</td>
						<td>' . $rs[ 'values' ] . '</td>
						<td>' . $rs[ 'createDate' ] . '</td>
						
					</tr>
			';
		}
		
		$showpage = new includes_class_page ( );
		$showpage -> show_page ( array ( 
										
										'total' => $this -> num , 
										'perpage' => pagenum 
		) );
		$showpage -> _set_url ( 'num=' . $this -> num . '&id=' . $conId );
		return $str . '<tr><td colspan="20" style="text-align:center;">' . $showpage -> show ( 6 ) . '</td></tr>';
	
	}
	
	function model_infoLogManage ()
	{
		$startDate=$_GET['startDate'];
		$endDate=$_GET['endDate'];
		$wordkey=$_GET['wordkey'];
		
		if($startDate){
			$sqlStr.=" and a.createDate>='$startDate' ";
		}
		if($endDate){
			$sqlStr.=" and a.createDate<='$endDate' ";
		}
		if($wordkey){
			$sqlStr.=" and (d.typename like '%$wordkey%' or c.device_name like '%$wordkey%' or b.coding like '%$wordkey%' or b.dpcoding like '%$wordkey%' or a.oldValues like '%$wordkey%' or a.values like '%$wordkey%') ";
		}
		
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "	SELECT count(0) as num FROM  device_log a 
										LEFT JOIN device_info b ON a.conId=b.id
										LEFT JOIN device_list c ON b.list_id=c.id
										LEFT JOIN device_type d ON c.typeid=d.id
										WHERE	a.tableName='device_info' $sqlStr " );
			$this -> num = $rs[ 'num' ];
		}
		
		$query = $this -> query ( "
								SELECT
								d.typename,c.device_name,b.coding,b.dpcoding,a.*
								FROM  device_log a 
								LEFT JOIN device_info b ON a.conId=b.id
								LEFT JOIN device_list c ON b.list_id=c.id
								LEFT JOIN device_type d ON c.typeid=d.id
								WHERE	a.tableName='device_info'  $sqlStr
								ORDER BY a.id desc
								limit $this->start," . pagenum . "
		" );
		
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '
					<tr>
						<td>' . $rs[ 'typename' ]. '</td>
						<td>' . $rs[ 'device_name' ]. '</td>
						<td>' . $rs[ 'coding' ]. '</td>
						<td>' . $rs[ 'dpcoding' ]. '</td>
						<td>' . $rs[ 'userName' ]. '</td>
						<td>' . ($rs[ 'conAsName' ]?$rs[ 'conAsName' ]:$rs[ 'conName' ]) . '</td>
						<td>' . $rs[ 'oldValues' ]. '</td>
						<td>' . $rs[ 'values' ] . '</td>
						<td>' . $rs[ 'createDate' ] . '</td>
						
					</tr>
			';
		}
		
		$showpage = new includes_class_page ( );
		$showpage -> show_page ( array ( 
										
										'total' => $this -> num , 
										'perpage' => pagenum 
		) );
		$showpage -> _set_url ( 'num=' . $this -> num . '&id=' . $conId );
		return $str . '<tr><td colspan="20" style="text-align:center;">' . $showpage -> show ( 6 ) . '</td></tr>';
	
	}
	
	
	/**
	 * 添加
	 * @param $id
	 * @param $typeid
	 * @param $data
	 */
	function model_add_info ( $id , $typeid , $data )
	{
		$data = mb_iconv ( $data );
		$rs = $this -> _db -> get_one ( "select dept_id from device_list where id=" . $id );
		if ( $rs )
		{
			//更新主表
			$this -> tbl_name = 'device_info';
			$info_id = $this -> create ( array ( 
												
												'dept_id' => $rs[ 'dept_id' ] , 
												'area' => $data[ 'area' ] , 
												'list_id' => intval ( $id ) , 
												'coding' => ( $data[ 'coding' ] ? $data[ 'coding' ] : '' ) , 
												'dpcoding' => ( $data[ 'dpcoding' ] ? $data[ 'dpcoding' ] : '' ) , 
												'fitting' => ( $data[ 'fitting' ] ? $data[ 'fitting' ] : '' ) , 
												'price' => ( $data[ 'price' ] ? $data[ 'price' ] : '' ) , 
												'notes' => ( $data[ 'notes' ] ? $data[ 'notes' ] : '' ) , 
												'amount' => $data[ 'amount' ] , 
												'date' => time ( ) 
			) );
			//填充自定义字段内容
			$this -> tbl_name = 'device_type_field_content';
			foreach ( $data as $key => $val )
			{
				if ( is_numeric ( $key ) )
				{
					$this -> create ( array ( 
												
												'field_id' => $key , 
												'info_id' => $info_id , 
												'content' => $val 
					) );
				}
			}
			//更新数据统计
			$info = $this -> _db -> get_one ( '
					select sum(amount) as num,sum(amount*price) as money from 
					device_info where list_id=' . $id . ' and quit=0' );
			if ( $info )
			{
				$average = $info[ 'money' ] / $info[ 'num' ];
				$this -> tbl_name = 'device_list';
				$this -> update ( 'id=' . $id , array ( 
														
														'total' => $info[ 'num' ] 
				) );
				return $this -> _db -> query ( "update device_list set surplus=(total-borrow),average=$average where id=" . $id );
			} else
			{
				return false;
			}
		} else
		{
			return false;
		}
	}
	/**
	 * 按设备序号获取相关内容
	 * @param $id
	 */
	function model_show_borrow_info ( $id )
	{
		return $this -> get_one ( "
								select
									a.amount,a.targetdate,a.date,d.user_name,e.dept_name,f.name
								from
									device_borrow_order_info as a
									left join device_info as b on b.id=a.info_id
									left join device_borrow_order as c on c.id=a.orderid
									left join user as d on d.user_id=c.userid
									left join department as e on e.dept_id=d.dept_id
									left join project_info as f on f.id=c.project_id
								where
									a.info_id=$id
									order by a.id desc
								limit 1
							" );
	}
	/**
	 * 按设备序号获取申请相关内容
	 * @param $id
	 */
	function model_show_apply_info ( $id )
	{
		return $this -> get_one ( "
								select
									a.amount,c.target_date,c.date,d.user_name,e.dept_name,f.name
								from
									device_apply_order_info as a
									left join device_info as b on b.id=a.info_id
									left join device_apply_order as c on c.id=a.orderid
									left join user as d on d.user_id=c.userid
									left join department as e on e.dept_id=d.dept_id
									left join project_info as f on f.id=c.project_id
								where
									a.info_id=$id
									order by a.id desc
								limit 1
							" );
	}
	/**
	 * 按类别设备查询列表
	 * @param unknown_type $typeid
	 */
	function model_get_device ( $typeid )
	{
		$this -> tbl_name = 'device_list';
		return $this -> findAll ( 'typeid=' . $typeid );
	}
	/**
	 * 设备类型总数更新
	 */
	function model_update_stockdata ( )
	{
		$id = $_POST[ 'id' ];
		$key = $_POST[ 'key' ];
		if ( $id && $key )
		{
			$re = $this -> get_one ( "
								select
									 sum(amount) as cots,sum(borrow_num) as bcots
								from
									device_info 
								where
									list_id='$id'
							" );
			if ( $re )
			{
				$rs = $this -> _db -> query ( "update device_list set total='" . $re[ 'cots' ] . "' ,borrow='" . $re[ 'bcots' ] . "', surplus='" . ( int ) ( $re[ 'cots' ] - $re[ 'bcots' ] ) . "', rate=(CAST((borrow/total) AS DECIMAL(11,2)))  where rand_key='" . $key . "'" );
				if ( $rs )
				{
					return 1;
				}
			}
		
		}
		return false;
	}
	/**
	 * 单设备类型总数更新
	 */
	function model_update_devicedata ( )
	{
		$id = $_POST[ 'id' ];
		if ( $id )
		{
			
			$rs1 = $this -> _db -> query ( "update  device_info   set state=0   where 1 and quit=0  and   state=1 and borrow_num=0 and id='" . $id . "'" );
			$rs2 = $this -> _db -> query ( "update  device_info    set state=1   where 1 and quit=0  and   state=0 and (borrow_num<>''or borrow_num<>0 ) and id='" . $id . "'" );
			$rs3 = $this -> _db -> query ( "update  device_info    set state=0   where 1 and quit=0  and borrow_num=0 and id='" . $id . "'" );
			$rs4 = $this -> _db -> query ( "update  device_info    set state=1   where 1 and quit=0  and (borrow_num<>''or borrow_num<>0 ) and id='" . $id . "'" );
			if ( $rs1 || $rs2 || $rs3 || $rs4 )
			{
				return 1;
			}
		}
		return false;
	}
	
	function model_deviceInfoTypelist ( $json = false , $exprot = false )
	{
		global $func_limit;
		if ( $_SESSION[ 'USER_ID' ] != 'admin' &&  ! $func_limit[ '管理区域' ] )
			showmsg ( '对不起，您没有任何区域管理权限！' );
		if ( $_GET[ '_search' ] == 'true' )
		{
			$filters = json_decode ( stripslashes ( stripslashes ( $_GET[ 'filters' ] ) ) , true );
			$filters = $filters ? mb_iconv ( $filters ) : null;
			$where = $this -> sopt ( $filters[ 'rules' ] );
		
		}
		//file_put_contents('rrr.txt',stripslashes(stripslashes ( $_GET['filters'] )));
		if ( intval ( $_GET[ 'typeid' ] ) )
		{
			$query = $this -> _db -> query ( "select distinct(id) as cid from device_type_field where typeid=" . $_GET[ 'typeid' ] . " order by sort" );
			$field = array ();
			while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
			{
				if ( $rs[ 'cid' ] )
				{
					$field[ ] = $rs[ 'cid' ];
				}
			}
			//==========================
			if (  ! $this -> num )
			{
				$rs = $this -> _db -> get_one ( "
												select 
													count(distinct a.id) as num
												from 
													device_info as a
													left join device_list as b on b.id=a.list_id  
													left join (select info_id,returndate,date,orderid from device_borrow_order_info order by id desc) as c on c.info_id=a.id
													left join device_borrow_order as d on d.id=c.orderid 
													left join user as e on e.user_id=d.userid 
													" . ( $where ? "left join device_type_field_content as h on h.info_id=a.id" : '' ) . "
												where 
													b.typeid=" . intval ( $_GET[ 'typeid' ] ) . " 
													and a.quit=0 
													and a.area in(" . $func_limit[ '管理区域' ] . ") 
													$where
												" );
				$this -> num = $rs[ 'num' ];
			}
			//==========================
			if ( $this -> num > 0 )
			{
				$sidx = is_numeric ( $_GET[ 'sidx' ] ) ? 'k.content-' . $_GET[ 'sord' ] : ( $_GET[ 'sidx' ] ? $_GET[ 'sidx' ] . '-' . $_GET[ 'sord' ] : '' );
				$_GET[ 'sort' ] = $_GET[ 'sort' ] ? $_GET[ 'sort' ] : $sidx;
				$Prefix = $_GET[ 'sort' ] && strpos ( $_GET[ 'sort' ] , '.' ) ? '' : 'a.';
				$sort = $_GET[ 'sort' ] ? $Prefix . $_GET[ 'sort' ] : 'a.id-desc';
				$sort = str_replace ( '-' , ' ' , $sort );
				$rs = $this -> get_fixed_field_name ( $_GET[ 'typeid' ] , false );
				$query = $this -> _db -> query ( "
				select 
					 a.*,b.device_name,b.typeid,e.user_name,c.returndate,c.date as borrow_date,f.Name as areaname ,g.name as projectname,g.number as projectNo
				from 
					device_info as a 
					left join device_list as b on b.id=a.list_id 
					left join (select info_id,returndate,date,orderid from device_borrow_order_info WHERE return_num=0 order by id desc) as c on c.info_id=a.id
					left join device_borrow_order as d on d.id=c.orderid 
					left join user as e on e.user_id=d.userid 
					left join area as f on f.id=a.area 
					left join project_info as g on g.id=d.project_id 
					" . ( $where ? "left join device_type_field_content as h on h.info_id=a.id" : '' ) . "
				where 
					b.typeid=" . intval ( $_GET[ 'typeid' ] ) . " 
					and quit=0 
					and a.area in(" . $func_limit[ '管理区域' ] . ") 
					$where
					group by a.id
					order by $sort
					" . (  ! $exprot ? "limit $this->start," . pagenum : "" ) . "" );
					
				$data = array ();
				while ( ( $row = $this -> _db -> fetch_array ( $query ) ) != false )
				{
					if ( $json )
					{
						$row[ 'field' ] = $field;
						$data[ ] = $row;
					} else
					{
						if ( $row[ 'state' ] > 0 || ( $row[ 'amount' ] <= $row[ 'borrow_num' ] ) )
						{
							$style = 'style="background:#EEEEEE;"';
						} else
						{
							$style = '';
						}
						$str .= '
								<tr id="tr_' . $row[ 'id' ] . '" ' . $style . '>
								<td>' . $row[ 'id' ] . '</td>';
						foreach ( $rs as $key => $val )
						{
							if (  ! $val )
							{
								switch ( $key )
								{
									case '_coding' :
										$row[ 'coding' ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
										$str .= '<td id="coding_' . $row[ 'id' ] . '">' . $row[ 'coding' ] . '</td>';
										break;
									case '_dpcoding' :
										$row[ 'dpcoding' ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
										$str .= '<td id="dpcoding_' . $row[ 'id' ] . '">' . $row[ 'dpcoding' ] . '</td>';
										break;
									case '_fitting' :
										$row[ 'fitting' ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
										$str .= '<td id="fitting_' . $row[ 'id' ] . '">' . $row[ 'fitting' ] . '</td>';
										break;
									case '_price' :
										$row[ 'price' ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
										$str .= '<td id="price_' . $row[ 'id' ] . '">￥' . number_format ( $row[ 'price' ] , 2 ) . '</td>';
										break;
									case '_notes' :
										$row[ 'notes' ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
										$str .= '<td id="notes_' . $row[ 'id' ] . '">' . $row[ 'notes' ] . '</td>';
										break;
								}
							}
						}
						if ( $field )
						{
							$field_data = $this -> get_field_content ( $row[ 'id' ] );
							foreach ( $field as $key => $val )
							{
								if ( $field_data[ $val ] )
								{
									$str .= '<td name="' . $val . '" class="edit_' . $row[ 'id' ] . '">' . $field_data[ $val ] . '</td>';
								} else
								{
									$str .= '<td name="' . $val . '" class="edit_' . $row[ 'id' ] . '">--</td>';
								}
							}
							unset ( $field_data );
						}
						$str .= '<td id="date_' . $row[ 'id' ] . '">' . date ( 'Y-m-d' , $row[ 'date' ] ) . '</td>';
						switch ( $row[ 'state' ] )
						{
							case 0 :
								$str .= '<td>可用</td>';
								break;
							case 1 :
								$str .= '<td><span>借出</span></td>';
								break;
							case 2 :
								$str .= '<td><span>等待确认</span></td>';
								break;
							case 3 :
								$str .= '<td><span>维修</span></td>';
								break;
							case 4 :
								$str .= '<td><span>退库</span></td>';
								break;
							case 5 :
								$str .= '<td><span>待审批</span></td>';
								break;
							case 6 :
								$str .= '<td><span>待受理</span></td>';
								break;
							default :
								$str .= '<td><span>不可用</span></td>';
						}
						$str .= '
						<td>' . $row[ 'areaname' ] . '</td>
						<td>' . $row[ 'amount' ] . '</td>
						<td>' . $row[ 'borrow_num' ] . '</td>
						<td>' . ( $row[ 'borrow_date' ] ? $this -> rate ( $row[ 'id' ] , $row[ 'date' ] ) . '%' : '' ) . '</td>
						<td><span class="widget" style="width:80px;color:#000000" title="' . $row[ 'projectname' ] . '">' . $row[ 'projectname' ] . '</span></td>
						<td>' . ( $row[ 'returndate' ] ? $row[ 'user_name' ] : '' ) . '</td>
						<td>' . ( $row[ 'borrow_date' ] &&  ! $row[ 'returndate' ] ? date ( 'Y-m-d' , $row[ 'borrow_date' ] ) : '' ) . '</td>
						<td class="text_left" id="edit_' . $row[ 'id' ] . '"><a href="javascript:edit(' . $row[ 'id' ] . ')">修改</a> | 
						<a href="?model=device_stock&action=operate&typeid=' . $row[ 'typeid' ] . '&list_id=' . $row[ 'list_id' ] . '&tid=' . $row[ 'id' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500" class="thickbox" title="查看 ' . $row[ 'device_name' ] . ' ' . ( $row[ 'dpcoding' ] ? '部门编码为：' . $row[ 'dpcoding' ] : ' 序号为 ' . $row[ 'id' ] ) . ' 的操作记录">操作记录</a>
						';
						if ( ( $row[ 'amount' ] - $row[ 'borrow_num' ] > 0 ) && $row[ 'state' ] == 0 )
						{
							$str .= ' | <a href="?model=device_stock&action=borrow_operate&typeid=' . $row[ 'typeid' ] . '&list_id=' . $row[ 'list_id' ] . '&id=' . $row[ 'id' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" class="thickbox" title="借出 《' . $row[ 'device_name' ] . '》">借出</a>';
						}
						$str .= '</td></tr>';
					}
				}
				if ( $json )
					return $data;
				if ( $this -> num > 0 )
				{
					$showpage = new includes_class_page ( );
					$showpage -> show_page ( array ( 
													
													'total' => $this -> num , 
													'perpage' => pagenum 
					) );
					$showpage -> _set_url ( 'num=' . $this -> num . '&sort=' . $_GET[ 'sort' ] . '&typeid=' . $_GET[ 'typeid' ] . '&id=' . $_GET[ 'id' ] . '&&placeValuesBefore' );
					return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
				} else
				{
					return $str;
				}
			} else
			{
				return false;
			}
		}
	}
	function model_deviceInfoTypelists ( $typeId)
	{
		global $func_limit;
		//file_put_contents('rrr.txt',stripslashes(stripslashes ( $_GET['filters'] )));
		if ( intval ( $typeId) )
		{
			$query = $this -> _db -> query ( "select distinct(id) as cid from device_type_field where typeid=" . $typeId . " order by sort" );
			$field = array ();
			while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
			{
				if ( $rs[ 'cid' ] )
				{
					$field[ ] = $rs[ 'cid' ];
				}
			}
			$where=$func_limit[ '管理区域' ]?"  and a.area in(" . $func_limit[ '管理区域' ] . ") ":'';
			
				$sidx = is_numeric ( $_GET[ 'sidx' ] ) ? 'k.content-' . $_GET[ 'sord' ] : ( $_GET[ 'sidx' ] ? $_GET[ 'sidx' ] . '-' . $_GET[ 'sord' ] : '' );
				$_GET[ 'sort' ] = $_GET[ 'sort' ] ? $_GET[ 'sort' ] : $sidx;
				$Prefix = $_GET[ 'sort' ] && strpos ( $_GET[ 'sort' ] , '.' ) ? '' : 'a.';
				$sort = $_GET[ 'sort' ] ? $Prefix . $_GET[ 'sort' ] : 'a.id-desc';
				$sort = str_replace ( '-' , ' ' , $sort );
				$rs = $this -> get_fixed_field_name ( $_GET[ 'typeid' ] , false );
				$query = $this -> _db -> query ( "
				select 
					 a.*,b.device_name,b.typeid,e.user_name,c.returndate,c.date as borrow_date,f.Name as areaname ,g.name as projectname,g.number as projectNo
				from 
					device_info as a 
					left join device_list as b on b.id=a.list_id 
					left join (select info_id,returndate,date,orderid from device_borrow_order_info  WHERE return_num=0 order by id desc) as c on c.info_id=a.id
					left join device_borrow_order as d on d.id=c.orderid 
					left join user as e on e.user_id=d.userid 
					left join area as f on f.id=a.area 
					left join project_info as g on g.id=d.project_id 
					" . ( $where ? "left join device_type_field_content as h on h.info_id=a.id" : '' ) . "
				where 
					b.typeid=" . intval ( $typeId) . " 
					and quit=0 
					$where
					group by a.id
					order by $sort
					" );
					
				$data = array ();
				while ( ( $row = $this -> _db -> fetch_array ( $query ) ) != false )
				{

						$row[ 'field' ] = $field;
						$data[ ] = $row;
				}

				return $data;
			
		}
	}
	/**
	 * 获取设备类型
	 * @param $dept_id
	 */
	function model_deviceStock_searchCheckDept ()
	{
		global $func_limit;
		$publicDept='125,';
		$deptStr=trim($publicDept.$func_limit[ '搜索部门' ],',');
		$sqlStr="SELECT * FROM department  a WHERE  a.DEPT_ID IN ($deptStr) OR  a.PARENT_ID IN ( 
				SELECT b.PARENT_ID FROM department  b WHERE b.DEPT_ID='".$_SESSION['DEPT_ID']."'
				)  order by a.Depart_x;";
		$query = $this -> query ($sqlStr);
		$data = array ();
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$data[ ] = $rs;
			$deptIdStr= $rs['DEPT_ID'].',';
		}
		$dataI['data']=$data;
		$dataI['deptIdStr']=trim($deptIdStr,',');
		return $dataI;
	}
	//##################################我的设备######################################
	/**
	 * 释构函数

	 */
	function __destruct ( )
	{
	
	}
}
?>