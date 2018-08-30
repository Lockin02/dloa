<?php
class model_develop_monthly extends model_base
{
	public $task_num = 0;
	public $next_task_num = 0;
	public $last_other_num = 0;
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> tbl_name = 'develop_monthly';
	}
	/**
	 * 月报列表
	 */
	function model_mylist ( )
	{
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from develop_monthly where userid='" . $_SESSION[ 'USER_ID' ] . "'" );
			$this -> num = $rs[ 'num' ];
		}
		
		if ( $this -> num > 0 )
		{
			$query = $this -> query ( "
										select 
											a.*,b.user_name as boss 
										from 
											develop_monthly as a 
											left join user as b on b.user_id=audit_userid 
										where 
											a.userid='" . $_SESSION[ 'USER_ID' ] . "' 
										order by 
											a.id desc 
										limit 
											$this->start," . pagenum );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$edit_link = thickbox_link ( '修改月报' , 'a' , 'id=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , '修改' . $rs[ 'month' ] . '月份工作报告' , null , 'edit' , null , 680 );
				$show_link = thickbox_link ( '查看' , 'a' , 'id=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , '查看' . $rs[ 'month' ] . '月份工作月报' , null , 'show_info' , null , 660 );
				$show_return_msg_link = thickbox_link ( '被打回' , 'a' , 'id=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , '查看' . $rs[ 'month' ] . '月份工作报告被打回的理由' , null , 'show_return_msg' , 400 , 300 );
				$str .= '
						<tr>
							<td>' . $rs[ 'month' ] . '月份工作报告</td>
							<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
							<td>' . $rs[ 'boss' ] . '</td>
							<td>' . ( $rs[ 'save' ] == 1 ? '<span>待提交</span>' : ( $rs[ 'status' ] == 1 ? '已通过审核' : ( $rs[ 'status' ] ==  - 1 ? $show_return_msg_link : '<span>待审核</span>' ) ) ) . '</td>
							<td>' . $rs[ 'score' ] . '</td>
							<td>' . ( $rs[ 'status' ] != 1 ? $edit_link : $show_link ) . '</td>
						</tr>
				';
			}
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array ( 
											
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num . '&year=' . $_GET[ 'year' ] . '&month=' . $_GET[ 'month' ] . '&status=' . $_GET[ 'status' ] . 'userid=' . $_GET[ 'userid' ] );
			return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
		}
	}
	/**
	 * 项目下拉
	 * @param $dept_id
	 */
	function project_option ( $dept_id , $porject_id = null )
	{
		$dept_obj = new model_system_dept ( );
		$dept_parent_id = $dept_obj -> GetParent_ID ( $dept_id );
		$dept_son_id = $dept_obj -> GetSon_ID ( $dept_id );
		$dept_arr = array_merge ( $dept_parent_id , $dept_son_id );
		$dept_id = $dept_arr ? implode ( ',' , $dept_arr ) . ',' . $dept_id : $dept_id;
		$datastr = '';
		$query = $this -> query ( "select * from project_info where dept_id in ($dept_id)" );
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			
			$checked = '';
			if (  ! $porject_id )
			{
				if ( $rs[ 'manager' ] && in_array ( $_SESSION[ 'USER_ID' ] , explode ( ',' , $rs[ 'manager' ] ) ) )
				{
					$checked = 'selected';
				} else if ( $rs[ 'teamleader' ] && in_array ( $_SESSION[ 'USER_ID' ] , explode ( ',' , $rs[ 'teamleader' ] ) ) )
				{
					$checked = 'selected';
				} else if ( $rs[ 'developer' ] && in_array ( $_SESSION[ 'USER_ID' ] , explode ( ',' , $rs[ 'developer' ] ) ) )
				{
					$checked = 'selected';
				} else if ( $rs[ 'tester' ] && in_array ( $_SESSION[ 'USER_ID' ] , explode ( ',' , $rs[ 'tester' ] ) ) )
				{
					$checked = 'selected';
				} else if ( $rs[ 'qc' ] && in_array ( $_SESSION[ 'USER_ID' ] , explode ( ',' , $rs[ 'qc' ] ) ) )
				{
					$checked = 'selected';
				}
			} else
			{
				if ( $rs[ 'id' ] == $porject_id )
				{
					$checked = 'selected';
				}
			}
			$datastr .= '<option ' . $checked . ' value="' . $rs[ 'id' ] . '">' . $rs[ 'name' ] . '</option>';
		}
		return $datastr;
	}
	/**
	 * 获取单个项目信息
	 * @param $project_id
	 */
	function model_one_porject ( $project_id )
	{
		return $this -> get_one ( "select * from project_info where id=" . $project_id );
	}
	/**
	 * 获取产品经理下级用户
	 * @param $userid
	 */
	function producmanager_lower ( $userid )
	{
		$query = $this -> query ( "select * from project_info where find_in_set('$userid',producmanager)" );
		$data = array ();
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			if ( $rs[ 'productassistant' ] )
			{
				$data = array_merge ( $data , explode ( ',' , $rs[ 'productassistant' ] ) );
			}
			if ( $rs[ 'manager' ] )
			{
				$data = array_merge ( $data , explode ( ',' , $rs[ 'manager' ] ) );
			}
			if ( $rs[ 'teamleader' ] )
			{
				$data = array_merge ( $data , explode ( ',' , $rs[ 'teamleader' ] ) );
			}
			if ( $rs[ 'developer' ] )
			{
				$data = array_merge ( $data , explode ( ',' , $rs[ 'developer' ] ) );
			}
			if ( $rs[ 'tester' ] )
			{
				$data = array_merge ( $data , explode ( ',' , $rs[ 'tester' ] ) );
			}
			if ( $rs[ 'qc' ] )
			{
				$data = array_merge ( $data , explode ( ',' , $rs[ 'qc' ] ) );
			}
		}
		
		return $data;
	}
	/**
	 * 审核月报列表
	 */
	function model_audit_list ( )
	{
		global $func_limit;
		$where = "where save=0 ";
		$lower_user = $this -> producmanager_lower ( $_SESSION[ 'USER_ID' ] );
		if ( $func_limit[ '查看所有' ] || $func_limit[ '所有权限' ] )
		{
			$where .= " and a.audit_userid!=''";
		} else if ( $func_limit[ '查看部门' ] )
		{
			$where .= ' and b.dept_id in(' . $func_limit[ '查看部门' ] . ')';
		} elseif ( $lower_user )
		{
			$temp = array ();
			foreach ( $lower_user as $val )
			{
				$temp[ ] = sprintf ( "'%s'" , $val );
			}
			$where .= " and (a.userid in(" . implode ( ',' , $temp ) . ") or a.audit_userid='" . $_SESSION[ 'USER_ID' ] . "')";
		} else
		{
			$where .= " and a.audit_userid='" . $_SESSION[ 'USER_ID' ] . "'";
		}
		if ( $_GET[ 'year' ] )
		{
			$start_date = strtotime ( $_GET[ 'year' ] . '-01-01' );
			$end_date = strtotime ( $_GET[ 'year' ] . '-12-31' );
			$where .= " and a.date > '$start_date' and a.date < '$end_date'";
		}
		if ( $_GET[ 'dept_name' ] )
		{
			$where .= " and a.dept_name='" . $_GET[ 'dept_name' ] . "'";
		}
		if ( $_GET[ 'month' ] )
		{
			$where .= " and a.month='" . $_GET[ 'month' ] . "'";
		}
		
		if ( $_GET[ 'status' ] || $_GET[ 'status' ] == '0' )
		{
			$where .= " and a.status='" . $_GET[ 'status' ] . "'";
		}
		if ( $_GET[ 'score' ] )
		{
			$where .= " and a.score='" . $_GET[ 'score' ] . "'";
		}
		if ( $_GET[ 'userid' ] )
		{
			$where .= " and a.userid='" . $_GET[ 'userid' ] . "'";
		}
		if ( $_GET[ 'audit_userid' ] )
		{
			$where .= " and a.audit_userid='" . $_GET[ 'audit_userid' ] . "'";
		}
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from develop_monthly as a left join user as b on b.user_id=a.userid $where" );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num > 0 )
		{
			$query = $this -> query ( "
									select
										a.*,b.user_name,c.user_name as audit_name
									from
										develop_monthly as a
										left join user as b on b.user_id=a.userid
										left join user as c on c.user_id=a.audit_userid
									$where
									order by date desc
									limit $this->start," . pagenum . "
			" );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$edit_link = thickbox_link ( '修改月报' , 'a' , 'id=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , '修改 ' . $rs[ 'user_name' ] . ' 的月报' , null , 'edit' , null , 680 );
				$show_link = thickbox_link ( '查看或审核' , 'a' , 'id=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , $rs[ 'user_name' ] . '的' . $rs[ 'month' ] . '月份月报' , null , 'show_info' , null , 660 );
				$show_return_msg_link = thickbox_link ( '被打回' , 'a' , 'id=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , '查看' . $rs[ 'month' ] . '月份工作报告被打回的理由' , null , 'show_return_msg' , 400 , 300 );
				$str .= '
						<tr>
							<td>' . $rs[ 'user_name' ] . '</td>
							<td>' . $rs[ 'audit_name' ] . '</td>
							<td>' . date ( 'Y-m-d H:i:s' , $rs[ 'date' ] ) . '</td>
							<td>' . $rs[ 'month' ] . '月份工作月报</td>
							<td>' . ( $rs[ 'status' ] == 1 ? '已通过审核' : ( $rs[ 'status' ] ==  - 1 ? $show_return_msg_link : '<span>待审核</span>' ) ) . '</td>
							<td>' . $rs[ 'score' ] . '</td>
							<td>' . ( $func_limit[ '修改权限' ] || $func_limit[ '所有权限' ] ? $edit_link . ' | ' : '' ) . $show_link . '</td>
						</tr>
				';
			}
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array ( 
											
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num . '&dept_name=' . $_GET[ 'dept_name' ] . '&year=' . $_GET[ 'year' ] . '&month=' . $_GET[ 'month' ] . '&status=' . $_GET[ 'status' ] . '&userid=' . $_GET[ 'userid' ] . '&score=' . $_GET[ 'score' ] . '&audit_userid=' . $_GET[ 'audit_userid' ] );
			return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
		}
	}
	/**
	 * 单份月报数据
	 * @param $id
	 * @param $key
	 */
	function get_info ( $id , $key )
	{
		return $this -> get_one ( "
								select 
									a.*,b.user_name,c.user_name as audit_name
								from
									develop_monthly as a
									left join user as b on b.user_id=a.userid
									left join user as c on c.user_id=a.audit_userid
								where
								    a.id=$id and a.rand_key='$key'	
							" );
	}
	/**
	 * 保存提交月报
	 * @param $data
	 */
	function model_save_add ( $data )
	{
		if ( $data )
		{
			$orderid = $this -> create ( array ( 
												
												'userid' => $_SESSION[ 'USER_ID' ] , 
												'dept_name' => $data[ 'dept_name' ] , 
												'project_id' => $data[ 'project_id' ] , 
												'audit_userid' => $data[ 'audit_userid' ] , 
												'month' => $data[ 'month' ] , 
												'share' => $data[ 'share' ] , 
												'study' => $data[ 'study' ] , 
												'save' => $data[ 'save' ] , 
												'date' => time ( ) 
			) );
			if ( $orderid > 0 )
			{
				//========上月
				$this -> tbl_name = 'develop_monthly_last';
				foreach ( $data[ 'last_content' ] as $key => $val )
				{
					if ( $val )
					{
						$this -> create ( array ( 
													
													'tid' => $orderid , 
													'userid' => $_SESSION[ 'USER_ID' ] , 
													'content' => $val , 
													'level' => $data[ 'level' ][ $key ] , 
													'end_date' => $data[ 'end_date' ][ $key ] , 
													'ontime' => $data[ 'ontime' ][ $key ] , 
													'score' => $data[ 'score' ][ $key ] , 
													'self_explain' => $data[ 'last_explain' ][ $key ] , 
													'time' => time ( ) 
						) );
					}
				}
				//====计划外
				if ( $data[ 'other_content' ] )
				{
					$this -> tbl_name = 'develop_monthly_other';
					foreach ( $data[ 'other_content' ] as $key => $val )
					{
						if ( $val )
						{
							$this -> create ( array ( 
														
														'tid' => $orderid , 
														'userid' => $_SESSION[ 'USER_ID' ] , 
														'content' => $val , 
														'end_date' => $data[ 'other_end_date' ][ $key ] , 
														'score' => $data[ 'other_score' ][ $key ] , 
														'self_explain' => $data[ 'other_explain' ][ $key ] , 
														'date' => time ( ) 
							) );
						}
					}
				}
				//========下月
				if ( $data[ 'next_content' ] )
				{
					$this -> tbl_name = 'develop_monthly_next';
					foreach ( $data[ 'next_content' ] as $key => $val )
					{
						if ( $val )
						{
							$this -> create ( array ( 
														
														'tid' => $orderid , 
														'userid' => $_SESSION[ 'USER_ID' ] , 
														'content' => $val , 
														'level' => $data[ 'next_level' ][ $key ] , 
														'end_date' => $data[ 'next_end_date' ][ $key ] , 
														'ask' => $data[ 'ask' ][ $key ] , 
														'date' => time ( ) 
							) );
						}
					}
				}
				if ( $data[ 'save' ] != 1 )
				{
					$gl = new includes_class_global ( );
					$email = new includes_class_sendmail ( );
					$user_email = $gl -> get_email ( $data[ 'audit_userid' ] );
					$oaurl .= '<hr /><br/><font size="2" >';
					$oaurl .= '内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br /><br />';
					$oaurl .= '外网地址：<a href="https://www.dinglicom.com/vpn">https://www.dinglicom.com/vpn/</a> </font>';
					if ( $email -> send ( $_SESSION[ 'USERNAME' ] . '提交了' . $data[ 'month' ] . '月份工作报告，需要您审核！' , '详情请等登录OA查看！' . $oaurl , $user_email ) )
					{
						$this -> tbl_name = 'develop_monthly';
						return $this -> update ( 'id=' . $orderid , array ( 
																			
																			'send_email' => 1 
						) );
					}
				} else
				{
					return true;
				}
			} else
			{
				return false;
			}
		
		}
	}
	/**
	 * 上月（修改或审核显示）
	 * @param $orderid
	 * @param $type
	 */
	function model_get_last ( $orderid , $type = 'edit' )
	{
		$audit_userid = $this -> get_table_fields ( 'develop_monthly' , 'id=' . $orderid , 'audit_userid' );
		$this -> tbl_name = 'develop_monthly_last';
		$data = $this -> findAll ( array ( 
											
											'tid' => $orderid 
		) );
		if ( $data )
		{
			foreach ( $data as $row )
			{
				$this -> task_num ++ ;
				if ( $type == 'edit' )
				{
					$str .= '
						<tr id="task_' . $this -> task_num . '" class="tr_last">
							<td width="38%" colspan="3" nowrap><textarea style="width:99%" class="last_content" name="last_content[' . $row[ 'id' ] . ']" cols="40" rows="5">' . $row[ 'content' ] . '</textarea></td>
							<td width="6%">' . $this -> model_level_select ( 'level[' . $row[ 'id' ] . ']' , $row[ 'level' ] ) . '</td>
							<td width="9%"><input type="text" size="12" readonly onClick="WdatePicker()"  class="Wdate last_date" name="end_date[' . $row[ 'id' ] . ']" value="' . ( $row[ 'end_date' ] != '0000-00-00' ? $row[ 'end_date' ] : '' ) . '" /></td>
							<td width="9%">' . $this -> model_bool ( 'ontime[' . $row[ 'id' ] . ']' , $row[ 'ontime' ] ) . '</td>
							<td width="4%">' . ( $this -> model_score_select ( 'score[' . $row[ 'id' ] . ']' , $row[ 'score' ] , 'last_score' ) ) . '</td>
							<td width="19%"><textarea style="width:99%" class="last_explain" name="last_explain[' . $row[ 'id' ] . ']" cols="20" rows="5">' . $row[ 'self_explain' ] . '</textarea></td>
							<td width="8%"><input type="button" onclick="del_task(' . $this -> task_num . ')" value="删除该项" /></td>
						</tr>
			';
				} else
				{
					$str .= '
						<tr id="task_' . $this -> task_num . '" class="tr_last">
							<td width="31%" colspan="3" nowrap>' . $row[ 'content' ] . '</td>
							<td width="6%">' . $row[ 'level' ] . '</td>
							<td width="9%">' . $row[ 'end_date' ] . '</td>
							<td width="10%">' . $row[ 'ontime' ] . '</td>
							<td width="3%">' . $row[ 'score' ] . '</td>
							<td width="15%" nowrap>' . $row[ 'self_explain' ] . '</td>
							<td width="6%">' . ( $audit_userid == $_SESSION[ 'USER_ID' ] ? $this -> model_score_select ( 'last_lead[' . $row[ 'id' ] . ']' , $row[ 'lead' ] , 'last_lead' ) : $row[ 'lead' ] ) . '</td>
							<td width="16%">' . ( $audit_userid == $_SESSION[ 'USER_ID' ] ? '<textarea style="width:100%" class="last_explain" name="last_explain[' . $row[ 'id' ] . ']" cols="20" rows="5">' . $row[ 'lead_explain' ] . '</textarea>' : $row[ 'lead_explain' ] ) . '
							</td>
						</tr>
					';
				}
			}
			return $str;
		} else
		{
			return false;
		}
	}
	/**
	 * 下月（修改或审核显示）
	 * @param unknown_type $order
	 * @param unknown_type $tyle
	 */
	function model_get_next ( $order , $type = 'edit' )
	{
		$this -> tbl_name = 'develop_monthly_next';
		$data = $this -> findAll ( array ( 
											
											'tid' => $order 
		) );
		if ( $data )
		{
			foreach ( $data as $row )
			{
				$this -> next_task_num ++ ;
				if ( $type == 'edit' )
				{
					$str .= '
						<tr id="next_task_' . $this -> next_task_num . '">
							<td width="41%"  colspan="4"><textarea style="width:99%" cols="40" rows="5" class="next_content" name="next_content[' . $row[ 'id' ] . ']">' . $row[ 'content' ] . '</textarea></td>
							<td width="6%" >' . $this -> model_level_select ( 'next_level[' . $row[ 'id' ] . ']' , $row[ 'level' ] ) . '</td>
							<td  width="9%"><input type="text" size="12" readonly onClick="WdatePicker()" class="Wdate next_end_date" name="next_end_date[' . $row[ 'id' ] . ']" value="' . ( $row[ 'end_date' ] != '0000-00-00' ? $row[ 'end_date' ] : '' ) . '" /></td>
							<td  width="35%" colspan="3"><textarea style="width:99%" cols="40" rows="5" class="next_ask" name="ask[' . $row[ 'id' ] . ']">' . $row[ 'ask' ] . '</textarea></td>
							<td width="8%"><input type="button" onclick="del_next_task(' . $this -> next_task_num . ');" value="删除该项"/></td>
						</tr>
					';
				} else
				{
					$str .= '
						<tr id="next_task_' . $this -> next_task_num . '">
							<td width="35%"  colspan="4">' . $row[ 'content' ] . '</td>
							<td width="6%" >' . $row[ 'level' ] . '</td>
							<td  width="9%">' . $row[ 'end_date' ] . '</td>
							<td  width="50%" colspan="5">' . $row[ 'ask' ] . '</td>
						</tr>
					';
				}
			}
			return $str;
		}
	}
	/**
	 * 上月工作计划外（修改或审核）
	 * @param unknown_type $orderid
	 * @param unknown_type $type
	 */
	function model_get_other ( $orderid , $type = 'edit' )
	{
		$audit_userid = $this -> get_table_fields ( 'develop_monthly' , 'id=' . $orderid , 'audit_userid' );
		$this -> tbl_name = 'develop_monthly_other';
		$data = $this -> findAll ( array ( 
											
											'tid' => $orderid 
		) );
		if ( $data )
		{
			foreach ( $data as $row )
			{
				$this -> last_other_num ++ ;
				if ( $type == 'edit' )
				{
					$str .= '
						<tr id="other_task_' . $this -> last_other_num . '">
							<td width="44%" colspan="4"><textarea style="width:99%" cols="40" rows="5" class="other_content" name="other_content[' . $row[ 'id' ] . ']">' . $row[ 'content' ] . '</textarea></td>
							<td width="18%" colspan="2"><input type="text" size="12" readonly onClick="WdatePicker()" class="Wdate other_date" name="other_end_date[' . $row[ 'id' ] . ']" value="' . ( $row[ 'end_date' ] != '0000-00-00' ? $row[ 'end_date' ] : '' ) . '" /></td>
							<td width="4%">' . $this -> model_score_select ( 'other_score[' . $row[ 'id' ] . ']' , $row[ 'score' ] , 'other_score' ) . '</td>
							<td width="19%"><textarea style="width:99%" class="other_explain" name="other_explain[' . $row[ 'id' ] . ']" cols="20" rows="5">' . $row[ 'self_explain' ] . '</textarea></td>
							<td width="8%"><input type="button" onclick="del_other_task(' . $this -> last_other_num . ');" value="删除该项"/></td>
						</tr>
						';
				} else
				{
					$str .= '
						<tr id="other_task_' . $this -> last_other_num . '">
							<td width="37%" colspan="4">' . $row[ 'content' ] . '</td>
							<td width="19%" colspan="2">' . $row[ 'end_date' ] . '</td>
							<td width="3%">' . $row[ 'score' ] . '</td>
							<td width="15%">' . $row[ 'self_explain' ] . '</td>
							<td width="6%">' . ( $audit_userid == $_SESSION[ 'USER_ID' ] ? $this -> model_score_select ( 'other_lead[' . $row[ 'id' ] . ']' , $row[ 'lead' ] , 'other_lead' ) : $row[ 'lead' ] ) . '</td>
							<td width="16%">' . ( $audit_userid == $_SESSION[ 'USER_ID' ] ? '<textarea style="width:100%" class="other_explain" name="other_explain[' . $row[ 'id' ] . ']" cols="20" rows="5">' . $row[ 'lead_explain' ] . '</textarea>' : $row[ 'lead_explain' ] ) . '</td>
						</tr>
						';
				}
			
			}
			return $str;
		}
	}
	/**
	 * 保存修改
	 * @param $id
	 * @param $key
	 * @param $data
	 */
	function model_save_edit ( $id , $key , $data )
	{
		if ( intval ( $id ) && $key )
		{
			//读取记录
			$rss = $this -> find ( array ( 
														
														'id' => $id , 
														'rand_key' => $key 
			) );
			if (  ! $rss )
				return false;
			
		//更新主表
			$this -> update ( array ( 
										
										'id' => $_GET[ 'id' ] , 
										'rand_key' => $_GET[ 'key' ] 
			) , array ( 
						
						'dept_name' => $data[ 'dept_name' ] , 
						'project_id' => $data[ 'project_id' ] , 
						'audit_userid' => $data[ 'audit_userid' ] , 
						'month' => $data[ 'month' ] , 
						'share' => $data[ 'share' ] , 
						'study' => $data[ 'study' ] , 
						'save' => $data[ 'save' ] , 
						'improve' => '' , 
						'status' => 0,
						'date' => $rss[ 'save' ]==0?$rss[ 'date' ]:time ( )  
			) );
			
			//=======更新上月
			$query = $this -> query ( "select id from develop_monthly_last where tid=$id" );
			$last_id_arr = array ();
			$this -> tbl_name = 'develop_monthly_last';
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$last_id_arr[ ] = $rs[ 'id' ];
				if ( $data[ 'last_content' ][ $rs[ 'id' ] ] )
				{
					$this -> update ( 'id=' . $rs[ 'id' ] , array ( 
																	
																	'content' => $data[ 'last_content' ][ $rs[ 'id' ] ] , 
																	'level' => $data[ 'level' ][ $rs[ 'id' ] ] , 
																	'end_date' => $data[ 'end_date' ][ $rs[ 'id' ] ] , 
																	'ontime' => $data[ 'ontime' ][ $rs[ 'id' ] ] , 
																	'score' => $data[ 'score' ][ $rs[ 'id' ] ] , 
																	'self_explain' => $data[ 'last_explain' ][ $rs[ 'id' ] ] 
					) );
				} else
				{
					$this -> delete ( 'id=' . $rs[ 'id' ] );
				}
			}
			//新增上月
			foreach ( $data[ 'last_content' ] as $key => $val )
			{
				if (  ! in_array ( $key , $last_id_arr ) && $val )
				{
					$this -> create ( array ( 
												
												'tid' => $id , 
												'userid' => $_SESSION[ 'USER_ID' ] , 
												'content' => $val , 
												'level' => $data[ 'level' ][ $key ] , 
												'end_date' => $data[ 'end_date' ][ $key ] , 
												'ontime' => $data[ 'ontime' ][ $key ] , 
												'score' => $data[ 'score' ][ $key ] , 
												'self_explain' => $data[ 'last_explain' ][ $key ] , 
												'date' => time ( ) 
					) );
				}
			}
			//更新上月工作外
			$this -> tbl_name = 'develop_monthly_other';
			$other = $this -> findAll ( 'tid=' . $id );
			if ( $other )
			{
				$other_id_arr = array ();
				foreach ( $other as $row )
				{
					$other_id_arr[ ] = $row[ 'id' ];
					if ( $data[ 'other_content' ][ $row[ 'id' ] ] )
					{
						$this -> update ( 'id=' . $row[ 'id' ] , array ( 
																		
																		'content' => $data[ 'other_content' ][ $row[ 'id' ] ] , 
																		'end_date' => $data[ 'other_end_date' ][ $row[ 'id' ] ] , 
																		'score' => $data[ 'other_score' ][ $row[ 'id' ] ] , 
																		'self_explain' => $data[ 'other_explain' ][ $row[ 'id' ] ] 
						) );
					} else
					{
						$this -> delete ( 'id=' . $row[ 'id' ] );
					}
				}
				
				//新增上月工作外
				foreach ( $data[ 'other_content' ] as $key => $val )
				{
					if (  ! in_array ( $key , $other_id_arr ) && $val )
					{
						$this -> create ( array ( 
													
													'tid' => $id , 
													'userid' => $_SESSION[ 'USER_ID' ] , 
													'content' => $val , 
													'end_date' => $data[ 'other_end_date' ][ $key ] , 
													'score' => $data[ 'other_score' ][ $key ] , 
													'self_explain' => $data[ 'other_explain' ][ $key ] , 
													'date' => time ( ) 
						) );
					}
				}
			} else
			{
				//新增上月工作外
				foreach ( $data[ 'other_content' ] as $key => $val )
				{
					if ( $val )
					{
						$this -> create ( array ( 
													
													'tid' => $id , 
													'userid' => $_SESSION[ 'USER_ID' ] , 
													'content' => $val , 
													'end_date' => $data[ 'other_end_date' ][ $key ] , 
													'score' => $data[ 'other_score' ][ $key ] , 
													'self_explain' => $data[ 'other_explain' ][ $key ] , 
													'date' => time ( ) 
						) );
					}
				}
			
			}
			//更新下月
			$this -> tbl_name = 'develop_monthly_next';
			$next_dtat = $this -> findAll ( 'tid=' . $id );
			if ( $next_dtat )
			{
				//更新下月
				$next_id_arr = array ();
				foreach ( $next_dtat as $row )
				{
					$next_id_arr[ ] = $row[ 'id' ];
					if ( $data[ 'next_content' ][ $row[ 'id' ] ] )
					{
						$this -> update ( 'id=' . $row[ 'id' ] , array ( 
																		
																		'content' => $data[ 'next_content' ][ $row[ 'id' ] ] , 
																		'level' => $data[ 'next_level' ][ $row[ 'id' ] ] , 
																		'end_date' => $data[ 'next_end_date' ][ $row[ 'id' ] ] , 
																		'ask' => $data[ 'ask' ][ $row[ 'id' ] ] 
						) );
					} else
					{
						$this -> delete ( 'id=' . $row[ 'id' ] );
					}
				}
				//增加下月
				foreach ( $data[ 'next_content' ] as $key => $val )
				{
					if (  ! in_array ( $key , $next_id_arr ) && $val )
					{
						$this -> create ( array ( 
													
													'tid' => $id , 
													'userid' => $_SESSION[ 'USER_ID' ] , 
													'content' => $val , 
													'level' => $data[ 'next_level' ][ $key ] , 
													'end_date' => $data[ 'next_end_date' ][ $key ] , 
													'ask' => $data[ 'ask' ][ $key ] , 
													'date' => time ( ) 
						) );
					}
				}
			} else
			{
				//增加下月
				foreach ( $data[ 'next_content' ] as $key => $val )
				{
					if ( $val )
					{
						$this -> create ( array ( 
													
													'tid' => $id , 
													'userid' => $_SESSION[ 'USER_ID' ] , 
													'content' => $val , 
													'level' => $data[ 'next_level' ][ $key ] , 
													'end_date' => $data[ 'next_end_date' ][ $key ] , 
													'ask' => $data[ 'ask' ][ $key ] , 
													'date' => time ( ) 
						) );
					}
				}
			}
			if ( $data[ 'save' ] != 1 && $rss[ 'send_email' ] == 0 )
			{
				$gl = new includes_class_global ( );
				$email = new includes_class_sendmail ( );
				$user_email = $gl -> get_email ( $data[ 'audit_userid' ] );
				$oaurl .= '<hr /><br/><font size="2" >';
				$oaurl .= '内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br /><br />';
				$oaurl .= '外网地址：<a href="https://www.dinglicom.com/vpn">https://www.dinglicom.com/vpn/</a> </font>';
				if ( $email -> send ( $_SESSION[ 'USERNAME' ] . '提交了' . $data[ 'month' ] . '月份工作报告，需要您审核！' , '详情请等登录OA查看！' . $oaurl , $user_email ) )
				{
					$this -> tbl_name = 'develop_monthly';
					return $this -> update ( 'id=' . $id , array ( 
																	
																	'send_email' => 1 
					) );
				}
			} else
			{
				return true;
			}
		} else
		{
			return false;
		}
	}
	/**
	 * 保存或修改审核
	 * @param $id
	 * @param $key
	 * @param $data
	 */
	function model_save_audit ( $id , $key , $data )
	{
		if ( intval ( $id ) && $key && $data )
		{
			try
			{
				$rs = $this -> get_one ( "
										select
											a.*,b.email,user_name
										from
											develop_monthly as a
											left join user as b on b.user_id=a.userid
										where
											a.id=$id and a.rand_key='" . $key . "'
				" );
				if ( $rs )
				{
					//更新主表
					$this -> tbl_name = 'develop_monthly';
					
					$this -> update ( array ( 
												
												'id' => $id , 
												'rand_key' => $key 
					) , array ( 
								
								'status' => 1 , 
								'score' => $data[ 'score' ] , 
								'score_explain' => $data[ 'score_explain' ] , 
								'improve' => $data[ 'improve' ] 
					) );
					
					//修改上月的上级评分
					$this -> tbl_name = 'develop_monthly_last';
					$last_data = $this -> findAll ( 'tid=' . $id , null , 'id' );
					if ( $last_data )
					{
						foreach ( $last_data as $row )
						{
							$this -> update ( 'id=' . $row[ 'id' ] , array ( 
																			
																			'lead' => $data[ 'last_lead' ][ $row[ 'id' ] ] , 
																			'lead_explain' => $data[ 'last_explain' ][ $row[ 'id' ] ] 
							) );
						}
					}
					//修改上月工作计划外的上级评分
					$this -> tbl_name = 'develop_monthly_other';
					$other_data = $this -> findAll ( 'tid=' . $id , null , 'id' );
					if ( $other_data )
					{
						foreach ( $other_data as $row )
						{
							$this -> update ( 'id=' . $row[ 'id' ] , array ( 
																			
																			'lead' => $data[ 'other_lead' ][ $row[ 'id' ] ] , 
																			'lead_explain' => $data[ 'other_explain' ][ $row[ 'id' ] ] 
							) );
						}
					}
					$title = $rs[ 'month' ] . '月份工作报告审核结果';
					$title = $rs[ 'status' ] != 0 ? $title . '已修改' : $title;
					$content = '您好！<br/>&nbsp;&nbsp;&nbsp;&nbsp;您的' . $rs[ 'month' ] . '月份的工作报告已审核' . ( $rs[ 'status' ] != 0 ? '已修改' : '通过' ) . '，请登录OA查看审核评分。';
					$emial = new includes_class_sendmail ( );
					return $emial -> send ( $title , $content , $rs[ 'email' ] );
				}
			} catch ( Exception $e )
			{
				return false;
			}
		} else
		{
			return false;
		}
	
	}
	/**
	 * 打回
	 * @param $id
	 * @param $key
	 * @param $notse
	 */
	function model_return ( $id , $key , $notse )
	{
		if ( intval ( $id ) && $key && $notse )
		{
			$rs = $this -> get_one ( "
										select
											a.*,b.email,user_name
										from
											develop_monthly as a
											left join user as b on b.user_id=a.userid
										where
											a.id=$id and a.rand_key='" . $key . "'
				" );
			if ( $rs )
			{
				$this -> update ( array ( 
											
											'id' => $id , 
											'rand_key' => $key 
				) , array ( 
							
							'status' =>  - 1 , 
							'notse' => $notse 
				) );
				
				$title = $rs[ 'month' ] . '月份工作报已被打回';
				$content = '您好！<br/>&nbsp;&nbsp;&nbsp;&nbsp;您的' . $rs[ 'month' ] . '月份的工作报告已被打回，请登录OA查看详情。';
				$emial = new includes_class_sendmail ( );
				return $emial -> send ( $title , $content , $rs[ 'email' ] );
			}
		} else
		{
			return false;
		}
	}
	/**
	 * 获取单条月报基本信息
	 * @param $id
	 * @param $key
	 */
	function model_getinfo ( $id , $key )
	{
		return $this -> get_one ( "
						select
							a.*,b.user_name as boss,c.USER_NAME,D.DEPT_NAME
						from
							develop_monthly as a
							left join user as b on b.user_id=a.audit_userid
							left join user as c on c.user_id=a.userid
							left join department as d on d.dept_id=c.dept_id
						where 
							a.id=$id and a.rand_key='" . $key . "'
		" );
	}
	/**
	 * 研发管理中的上月工作计划
	 * @param string $userid
	 */
	function model_Last_month_task ( $userid )
	{
		$last_month_start = mktime ( 0 , 0 , 0 , date ( "m" ) - 1 , 1 , date ( "Y" ) ); //上月第一日
		$last_month_end = mktime ( 23 , 59 , 59 , date ( "m" ) , 0 , date ( "Y" ) ); //上月最后一日
		$query = $this -> query ( "
						select 
							* 
						from 
							item_task 
						where 
							BurdenPeople='$userid' 
							and ( 	
									(	
										ProjBeginDT >= '" . date ( 'Y-m-d' , $last_month_start ) . "' 
										and ProjBeginDT <= '" . date ( 'Y-m-d' , $last_month_end ) . "'
									)
									or (
											ProjEndDT >= '" . date ( 'Y-m-d' , $last_month_start ) . "'
										)
								)
					" );
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$this -> task_num ++ ;
			$str .= '
			<tr id="task_' . $this -> task_num . '">
				<td width="38%" colspan="3" nowrap>' . strip_tags ( $rs[ 'TaskContent' ] ) . '</td>
				<input type="hidden" name="last_content[]" value="' . strip_tags ( $rs[ 'TaskContent' ] ) . '" />
				<td width="6%">' . $rs[ 'PriorityLevel' ] . '</td>
				<input type="hidden" name="level[]" value="' . $rs[ 'PriorityLevel' ] . '" />
				<td width="9%">' . $rs[ 'ProjEndDT' ] . '</td>
				<input type="hidden" class="new_last_date" name="end_date[]" value="' . $rs[ 'ProjEndDT' ] . '" />
				<td width="9%">
						<select name="ontime[]">
							<option  value="是">是</option>
							<option ' . ( $rs[ 'TrueEndDT' ] && ( $rs[ 'TrueEndDT' ] > $rs[ 'ProjEndDT' ] ) ? 'selected' : '' ) . ' value="否">否</option>
							<option ' . (  ! $rs[ 'TrueEndDT' ] && date ( 'Y-m-d' ) < $rs[ 'ProjEndDT' ] ? 'selected' : '' ) . ' value="进行中">进行中</option>
						</select>
				</td>
				<td width="4%">' . ( $this -> model_score_select ( 'score[]' ) ) . '</td>
				<td width="19%"><textarea style="width:99%" name="last_explain[]" cols="20" rows="5"></textarea></td>
				<td width="8%"><input type="button" onclick="del_task(' . $this -> task_num . ')" value="删除该项" /></td>
			</tr>
			';
		}
		return $str;
	}
	/**
	 * 研发管理中的下月工作计划
	 * @param string $userid
	 */
	function model_next_month_task ( $userid )
	{
		$this_month_start = mktime ( 0 , 0 , 0 , date ( "m" ) , 1 , date ( "Y" ) ); //本月第一日
		$query = $this -> query ( "
						select 
							* 
						from 
							item_task 
						where 
							BurdenPeople='$userid' 
							and ProjBeginDT>='" . date ( 'Y-m-d' , $this_month_start ) . "' 
					" );
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$this -> next_task_num ++ ;
			$str .= '
					<tr id="next_task_' . $this -> next_task_num . '">
						<td width="41%"  colspan="4">' . strip_tags ( $rs[ 'TaskContent' ] ) . '</td>
						<input type="hidden" name="next_content[]" value="' . strip_tags ( $rs[ 'TaskContent' ] ) . '" />
						<td width="6%" >' . $rs[ 'PriorityLevel' ] . '</td>
						<input type="hidden" name="next_level[]" value="' . $rs[ 'PriorityLevel' ] . '" />
						<td  width="9%">' . $rs[ 'ProjEndDT' ] . '</td>
						<input type="hidden" name="next_end_date[]" value="' . $rs[ 'ProjEndDT' ] . '" />
						<td  width="35%" colspan="3"><textarea style="width:99%" cols="40" rows="5" class="system" name="ask[]"></textarea></td>
						<td width="8%"><input type="button" onclick="del_next_task(' . $this -> next_task_num . ');" value="删除该项"/></td>
					</tr>
			';
		}
		return $str;
	}
	/**
	 * 上月计划外工作
	 */
	function model_last_month_other_task ( $userid )
	{
		$last_month_start = mktime ( 0 , 0 , 0 , date ( "m" ) - 1 , 1 , date ( "Y" ) ); //上月第一日
		$this_month_start = mktime ( 0 , 0 , 0 , date ( "m" ) , 1 , date ( "Y" ) ); //本月第一日
		$query = $this -> query ( "select * from develop_monthly_next where userid='$userid' and date>=$last_month_start and date < $this_month_start" );
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$this -> task_num ++ ;
			$str .= '
					<tr id="task_' . $this -> task_num . '">
					<td width="38%" colspan="3"><textarea style="width:99%" cols="40" rows="5" name="last_content[]">' . $rs[ 'content' ] . '</textarea></td>
					<td width="6%">' . $rs[ 'level' ] . '</td>
					<input type="hidden" name="level[]" value="' . $rs[ 'level' ] . '" />
					<td width="9%">' . $rs[ 'end_date' ] . '</td>
					<input type="hidden" class="new_last_date" name="end_date[]" value="' . $rs[ 'end_date' ] . '" />
					<td><select name="ontime[]">
							<option selected value="是">是</option>
							<option value="否">否</option>
							<option value="进行中">进行中</option>
						</select>
					</td>
					<td width="4%">' . ( $this -> model_score_select ( 'score[]' ) ) . '</td>
					<td width="19%"><textarea style="width:99%" name="last_explain[]" cols="20" rows="5"></textarea></td>
					<td width="8%"><input type="button" onclick="del_task(' . $this -> task_num . ');" value="删除该项"/></td>
				</tr>
			';
		}
		return $str;
	}
	
	function model_score_select ( $id , $ed = '' , $classname = '' )
	{
		$str .= '
			<select name="' . $id . '" ' . ( $classname ? 'class="' . $classname . '"' : '' ) . '>
				<option ' . ( $ed == '优' ? 'selected' : '' ) . ' value="优">优</option>
				<option ' . ( $ed == '良' ? 'selected' : '' ) . ' value="良">良</option>
				<option ' . ( $ed == '中' ? 'selected' : '' ) . ' value="中">中</option>
				<option ' . ( $ed == '差' ? 'selected' : '' ) . ' value="差">差</option>
			</select>
		';
		return $str;
	}
	
	function model_level_select ( $id , $ed = '' )
	{
		$str .= '
			<select name="' . $id . '">
				<option ' . ( $ed == '紧急' ? 'selected' : '' ) . ' value="紧急">紧急</option>
				<option ' . ( $ed == '高' ? 'selected' : '' ) . ' value="高">高</option>
				<option ' . ( $ed == '中' ? 'selected' : '' ) . ' value="中">中</option>
			</select>
		';
		return $str;
	}
	
	function model_bool ( $id , $ed = '' )
	{
		$str .= '
			<select name="' . $id . '">
				<option ' . ( $ed == '是' ? 'selected' : '' ) . ' value="是">是</option>
				<option ' . ( $ed == '否' ? 'selected' : '' ) . ' value="否">否</option>
				<option ' . ( $ed == '进行中' ? 'selected' : '' ) . ' value="进行中">进行中</option>
			</select>
		';
		return $str;
	}
	/**
	 * 按项目获取审批人
	 * 普通员工的审批人是项目经理，项目经理的审批人是产品经理，如果没有产品经理则有部门总监审批
	 * @param $project_id
	 */
	function model_project_audit_user ( $project_id )
	{
		$str = '';
		$user_arr = array ();
		$arr = array ();
		$rs = $this -> get_one ( "select * from project_info where id=" . $project_id );
		if ( $rs )
		{
			if ( $rs[ 'manager' ] && in_array ( $_SESSION[ 'USER_ID' ] , explode ( ',' , $rs[ 'manager' ] ) ) )
			{
				if ( $rs[ 'producmanager' ] )
				{
					$user_arr = explode ( ',' , $rs[ 'producmanager' ] );
				} else
				{
					$rs = $this -> get_one ( "select majorid,vicemanager from department where dept_id=" . $_SESSION[ 'DEPT_ID' ] );
					if ( $rs[ 'majorid' ] )
					{
						$user_arr = explode ( ',' , $rs[ 'majorid' ] );
					} else if ( $rs[ 'vicemanager' ] )
					{
						$user_arr = explode ( ',' , $rs[ 'vicemanager' ] );
					}
				}
			} else if ( $rs[ 'tester' ] && $rs[ 'testleader' ] && in_array ( $_SESSION[ 'USER_ID' ] , explode ( ',' , $rs[ 'tester' ] ) ) )
			{
				$user_arr = explode ( ',' , $rs[ 'testleader' ] );
			} else if ( $rs[ 'qc' ] && $rs[ 'productassistant' ] && in_array ( $_SESSION[ 'USER_ID' ] , explode ( ',' , $rs[ 'qc' ] ) ) )
			{
				$user_arr = explode ( ',' , $rs[ 'productassistant' ] );
			} else if ( $rs[ 'testleader' ] && in_array ( $_SESSION[ 'USER_ID' ] , explode ( ',' , $rs[ 'testleader' ] ) ) )
			{
				$rs = $this -> get_one ( "select majorid,vicemanager from department where dept_id=" . $_SESSION[ 'DEPT_ID' ] );
				if ( $rs[ 'majorid' ] )
				{
					$user_arr = explode ( ',' , $rs[ 'majorid' ] );
				} else if ( $rs[ 'vicemanager' ] )
				{
					$user_arr = explode ( ',' , $rs[ 'vicemanager' ] );
				}
			} else
			{
				$user_arr = explode ( ',' , $rs[ 'manager' ] );
			}
		}
		if ( $user_arr )
		{
			foreach ( $user_arr as $val )
			{
				$arr[ ] = "'$val'";
			}
		}
		if ( $arr )
		{
			$query = $this -> query ( "select user_id,user_name from user where user_id in(" . implode ( ',' , $arr ) . ")" );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$str .= $rs[ 'user_id' ] . '|' . $rs[ 'user_name' ] . '|';
			}
		}
		
		return $str;
	}
	
	function getPmsTaskData($currentUser, $lastMonth){
		
		$strArr = array(
			'last' => '',
			'next' => ''
		); 
		if(!empty($currentUser)){
			$api = new api_pms();
			
			$postList = array(
				'oa_user' => $currentUser,
				'last_month' => $lastMonth,
				'next_month' => date ( 'n', time () )
			);
			$result = $api->GetModule('task', 'getOaUserTask',un_iconv($postList),'post');
			//print_r($result);exit;
			if(isset($result->status) && $result->status == "success"){
				$returnData =  $this->object_array(json_decode($result->data));
				$arr = $this->json_to_array($returnData);
				
				// if last month has tasks,make a html string and return 
				if(count($arr['last']) > 0){
					$data = array();
					$data = mb_iconv($arr['last']);
					for($i = 0; $i< count($data); $i++){
						$this->task_num ++;
						$pri = "";
						if($data[$i]['pri'] == 3){
							$pri = "紧急";
						}else if($data[$i]['pri'] == 2 ){
							$pri = "高";
						}else if($data[$i]['pri'] == 1){
							$pri = "低";
						}
						
						$strArr['last'] .= '
						<tr id="task_' . $this->task_num . '">
							<td width="38%" colspan="3" nowrap>' . strip_tags ( $data[$i]['name'] ) . '</td>
							<input type="hidden" name="last_content[]" value="' . strip_tags ( $data[$i]['name'] ) . '" />
							<td width="6%">' . $pri . '</td>
							<input type="hidden" name="level[]" value="' . $pri . '" />
							<td width="9%">' . $data[$i]['deadline'] . '</td>
							<input type="hidden" class="new_last_date" name="end_date[]" value="' . $data[$i]['deadline'] . '" />
							<td width="9%">
									<select name="ontime[]">
										<option ' .($data[$i]['status'] == 'done' ? 'selected':'').' value="是">是</option>
										<option ' .($data[$i]['status'] == 'wait' ? 'selected':'').' value="否">否</option>
										<option '.($data[$i]['status'] == 'doing' ? 'selected' : '').' value="进行中">进行中</option>
										<option '.($data[$i]['status'] == 'cancel' ? 'selected' : '').' value="取消">取消</option>
									</select>
							</td>
							<td width="4%">' . ($this->model_score_select ( 'score[]' )) . '</td>
							<td width="19%"><textarea style="width:99%" name="last_explain[]" cols="20" rows="5">'.$data[$i]['desc'].'</textarea></td>
							<td width="8%"><input type="button" onclick="del_task(' . $this->task_num . ')" value="删除该项" /></td>
						</tr>
						';
					}
				}
				
				// if next month has tasks,make a html string and return 
				if(count($arr['next']) > 0){
					$data = array();
					$data = mb_iconv($arr['next']);
					for($i = 0; $i< count($data); $i++){
						$this->next_task_num ++;
						$pri = "";
						if($data[$i]['pri'] == 3){
							$pri = "紧急";
						}else if($data[$i]['pri'] == 2 ){
							$pri = "高";
						}else if($data[$i]['pri'] == 1){
							$pri = "低";
						}
						
						$strArr['next'] .= '
						<tr id="next_task_' . $this->next_task_num . '">
							<td width="41%"  colspan="4">' . strip_tags ( $data[$i]['name'] ) . '</td>
							<input type="hidden" name="next_content[]" value="' . strip_tags ( $data[$i]['name'] ) . '" />
							<td width="6%" >' . $pri . '</td>
							<input type="hidden" name="next_level[]" value="' . $pri . '" />
							<td  width="9%">' . $data[$i]['deadline'] . '</td>
							<input type="hidden" name="next_end_date[]" value="' . $data[$i]['deadline'] . '" />
							<td  width="35%" colspan="3"><textarea style="width:99%" cols="40" rows="5" class="system" name="ask[]">'.$data[$i]['desc'].'</textarea></td>
							<td width="8%"><input type="button" onclick="del_next_task(' . $this->next_task_num . ');" value="删除该项"/></td>
						</tr>
						';
					}
				}
			}
		}
		return $strArr;
	}
	
	private function object_array($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
     } if(is_array($array)) {  
         foreach($array as $key=>$value) {  
             $array[$key] = $this->object_array($value);  
         }  
     }  
     return $array;  
	}

	private function json_to_array($web){
		$arr = array();
		foreach($web as $k=>$w){
		    if(is_object($w)) $arr[$k]=$this->json_to_array($w); //判断类型是不是object
		    else $arr[$k]=$w;
		}
		return $arr;
	}
}

?>