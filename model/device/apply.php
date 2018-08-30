<?php
class model_device_apply extends model_base
{
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> tbl_name = 'device_apply_sim';
	}
	/**
	 * 我的申请列表
	 */
	function model_myapply_list ( )
	{
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from device_apply_sim as a where userid='" . $_SESSION[ 'USER_ID' ] . "'" );
			$this -> num = $rs[ 'num' ];
		}
		
		if ( $this -> num > 0 )
		{
			$query = $this -> query ( "
			select
				a.*,b.user_name
			from
				device_apply_sim as a
				left join user as b on b.user_id=a.audit_userid
			where
				userid='" . $_SESSION[ 'USER_ID' ] . "'
			order by a.id desc
			limit $this->start," . pagenum . "
			" );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$info_link = thickbox_link ( '查看' , 'a' , 'id=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , '申请SIM卡详细信息' , null , 'applyinfo' , null , 500 );
				$edit_iink = thickbox_link ( '修改' , 'a' , 'id=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , '修改SIM卡申请' , null , 'edit' , null , 500 );
				$str .= '
					<tr>
						<td>' . $rs[ 'user_name' ] . '</td>
						<td>' . $rs[ 'operators' ] . '</td>
						<td>' . $rs[ 'area' ] . '</td>
						<td>' . $rs[ 'card_name' ] . '</td>
						<td>' . $rs[ 'card_type' ] . '</td>
						<td>' . $rs[ 'flow' ] . '</td>
						<td>' . $rs[ 'amount' ] . '</td>
						<td width="20%" nowrap>' . $rs[ 'notse' ] . '</td>
						<td>' . ( $rs[ 'status' ] == 1 ? '已通过审核' : ( $rs[ 'status' ] ==  - 1 ? '被打回' : ( $rs[ 'status' ] ==  - 2 ? '已取消申请' : '<span>待审核</span>' ) ) ) . '</td>
						<td>' . ( $rs[ 'status' ] == 0 ? $edit_iink : $info_link ) . '</td>
					</tr>
				';
			}
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array ( 
											
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num . '&sort=' . $_GET[ 'sort' ] );
			return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
		}
	}
	/**
	 * SIM申请信息
	 * @param $id
	 */
	function model_apply_info ( $id )
	{
		return $this -> get_one ( "
			select
				a.*,b.user_name,c.user_name as audit_username
			from
				device_apply_sim as a
				left join user as b on b.user_id=a.userid
				left join user as c on c.user_id=a.audit_userid
			where 
				a.id=$id
		" );
	}
	/**
	 * 审批列表
	 */
	function model_audit_list ( )
	{
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from device_apply_sim where audit_userid='" . $_SESSION[ 'USER_ID' ] . "'" );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num > 0 )
		{
			$query = $this -> query ( "
					select
						a.*,b.user_name
					from
						device_apply_sim as a
						left join user as b on b.user_id=a.userid
					where
						audit_userid='" . $_SESSION[ 'USER_ID' ] . "'
					order by a.id desc
					limit $this->start," . pagenum );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$str .= '
				<tr>
					<td>' . $rs[ 'user_name' ] . '</td>
					<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
					<td>' . $rs[ 'operators' ] . '</td>
					<td>' . $rs[ 'area' ] . '</td>
					<td>' . $rs[ 'card_name' ] . '</td>
					<td>' . $rs[ 'card_type' ] . '</td>
					<td>' . $rs[ 'flow' ] . '</td>
					<td>' . $rs[ 'amount' ] . '</td>
					<td width="20%" nowrap>' . $rs[ 'notse' ] . '</td>
					<td>' . ( $rs[ 'status' ] == 1 ? '已通过审核' : ( $rs[ 'status' ] ==  - 1 ? '被打回' : '<span>待审核</span>' ) ) . '</td>
					<td>' . thickbox_link ( '查看' , 'a' , 'id=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , '申请SIM卡详细信息' , null , 'applyinfo' , null , 500 ) . '</td>
				</tr>
				';
			}
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array ( 
											
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num . '&sort=' . $_GET[ 'sort' ] );
			return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
		}
	}
	/**
	 * 保存申请
	 * @param $data
	 */
	function model_save_apply ( $data )
	{
		if ( $data[ 'username' ] )
		{
			$email_userid = array ();
			foreach ( $data[ 'username' ] as $key => $row )
			{
				$email_userid[ ] = $data[ 'userid' ][ $key ];
				$this -> create ( array ( 
											
											'userid' => $_SESSION[ 'USER_ID' ] , 
											'dept_id' => $_SESSION[ 'DEPT_ID' ] , 
											'audit_userid' => $data[ 'userid' ][ $key ] , 
											'operators' => $data[ 'operators' ][ $key ] , 
											'area' => $data[ 'area' ][ $key ] , 
											'card_name' => $data[ 'card_name' ][ $key ] , 
											'card_type' => $data[ 'card_type' ][ $key ] , 
											'flow' => ( $data[ 'flow' ][ $key ] == 'other' ? $data[ 'flow_data' ][ $key ] : $data[ 'flow' ][ $key ] ) , 
											'amount' => $data[ 'amount' ][ $key ] , 
											'notse' => $data[ 'notse' ][ $key ] , 
											'date' => time ( ) 
				) );
			}
			$gl = new includes_class_global ( );
			$address = $gl -> get_email ( array_unique ( $email_userid ) );
			$email = new includes_class_sendmail ( );
			return $email -> send ( $_SESSION[ 'USERNAME' ] . '提交了SIM卡申请，需要您登录OA审批！' , '详情请登录OA查看！' , $address );
		
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
		if ( $id && $key && $data )
		{
			$rs = $this -> get_one ( "
					select
						a.*,b.email
					from
						device_apply_sim as a
						left join user as b on b.user_id=a.audit_userid
					where
						a.id=$id and a.rand_key='$key'
			" );
			if ( $rs )
			{
				$this -> update ( array ( 
											
											'id' => $id , 
											'rand_key' => $key 
				) , array ( 
							
							'audit_userid' => $data[ 'audit_userid' ] , 
							'operators' => $data[ 'operators' ] , 
							'area' => $data[ 'area' ] , 
							'card_name' => $data[ 'card_name' ] , 
							'card_type' => implode ( ',' , $data[ 'card_type' ] ) , 
							'flow' => ( $data[ 'flow' ] == 'other' ? $data[ 'flow_data' ] : $data[ 'flow' ] ) , 
							'amount' => $data[ 'amount' ] , 
							'notse' => $data[ 'notse' ] , 
							'status' => 0 
				) );
				
				if ( $rs[ 'status' ] ==  - 1 )
				{
					$email = new includes_class_sendmail ( );
					return $email -> send ( $_SESSION[ 'USERNAME' ] . '修改了SIM卡申请，请您重新审核' , '详情请登录OA查看！' , $rs[ 'email' ] );
				} else
				{
					return true;
				}
			} else
			{
			
			}
		} else
		{
			showmsg ( '非法参数！' );
		}
	}
	/**
	 * 审批申请
	 * @param unknown_type $id
	 * @param unknown_type $key
	 * @param unknown_type $data
	 */
	function model_audit_apply ( $id , $key , $data )
	{
		$rs = $this -> get_one ( "
					select
						a.*,b.email
					from
						device_apply_sim as a
						left join user as b on b.user_id=a.userid
					where
						a.id=$id and a.rand_key='$key'
		" );
		if ( $rs )
		{
			$this -> update ( array ( 
										
										'id' => $id , 
										'rand_key' => $key 
			) , $data );
			if ( $rs[ 'email' ] )
			{
				$email = new includes_class_sendmail ( );
				return $email -> send ( $_SESSION[ 'USERNAME' ] . ( $data[ 'status' ] == 1 ? '审核通过了' : '打回了' ) . '您的SIM卡申请' , '详情请登录OA查看！' , $rs[ 'email' ] );
			} else
			{
				showmsg ( '审核成功，但发送Email失败！' , 'self.parent.location.reload();' , 'button' );
			}
		} else
		{
			return false;
		}
	}
	/**
	 * 添加设备申请
	 * @param $data
	 */
	function model_add ( $data )
	{
		if ( is_array ( $data ) && is_array ( $data[ 'id' ] ) )
		{
			$this -> query ( "
									insert into device_apply_order(
																		userid,
																		ls,
																		dept_id,
																		project_id,
																		description,
																		target_date,
																		total,
																		date
																	)values(
																		'" . $data[ 'userid' ] . "',
																		'" . $data[ 'ls' ] . "',
																		'" . $data[ 'dept_id' ] . "',
																		'" . ( $data[ 'ls' ] == 1 ? $data[ 'project_id' ] : '' ) . "',
																		'" . $data[ 'description' ] . "',
																		'" . strtotime ( $data[ 'target_date' ] ) . "',
																		'" . array_sum ( $data[ 'amount' ] ) . "',
																		'" . time ( ) . "'
																	) " );
			$orderid = $this -> _db -> insert_id ( );
			
			$query = $this -> query ( "select state from device_info where id in(" . implode ( ',' , $data[ 'id' ] ) . ")" );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				if ( $rs[ 'state' ] != 0 )
				{
					showmsg ( '您申请的设备已经被别人借用！' );
				}
			}
			foreach ( $data[ 'id' ] as $val )
			{
				$this -> query ( "insert into device_apply_order_info(orderid,info_id,amount)values('" . $orderid . "','" . $val . "','" . $data[ 'amount' ][ $val ] . "')" );
				$this -> query ( "update device_info set state=5 where id=" . $val );
			}
			
			/*if ($data['ls']==1)
			{
				$gl = new includes_class_global();
				$rt = $this->get_one("select manager from project_info where id='".$data['project_id']."'");
				if (($rt['manager'] && in_array($data['userid'],explode(',',$rt['manager']))) || !$rt['manager'])
				{
					$ra = $this->get_one("select majorid,vicemanager from department where dept_id='".$data['dept_id']."'");
					$audit_userid = $ra['majorid'] ? explode(',',$ra['majorid']) : ($ra['vicemanager'] ? explode(',',$ra['vicemanager']) : '');
					if ($audit_userid)
					{
						$email = $gl->get_email($audit_userid);
					}
				}else if($rt['manager']){
					$email = $gl->get_email((strpos($rt['manager'],',')!==false ? explode(',',$rt['manager']) : $rt['manager']));
				}
			}else{
				$query = $this->query("
										select 
											b.userid 
										from 
											purview as a
											left join purview_info as b on b.tid=a.id
										where 
											(a.models='".$_GET['model']."' and a.type=1 and a.control=1) 
											or 
											(a.models='".$_GET['model']."' and a.func='".$_GET['action']."')");
				$userid = array();
				while (($rr = $this->fetch_array($query))!=false)
				{
					$userid[] = sprintf("'%s'",$rr['userid']);
				}
				if ($userid)
				{
					$query = $this->query("select email from user where dept_id='".$data['dept_id']."' and user_id in(".implode(',',$userid).")");
					$email = array();
					while (($ra = $this->fetch_array($query))!=false)
					{
						$email[] = $ra['email'];
					}
				}
			}*/
			//===新修改审批规则
			$query = $this -> query ( "select 
										d.email
								from 
									purview as a 
									left join purview_type as b on b.tid=a.id
									left join purview_info as c on c.tid=a.id and c.typeid=b.id
									left join user as d on d.user_id=c.userid
									where 
										a.models = 'device_apply'
										and b.name='审批部门'
										and find_in_set('" . $_SESSION[ 'DEPT_ID' ] . "',c.content)
									" );
			$email = array ();
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$email[ ] = $rs[ 'email' ];
			}
			if ( $email )
			{
				$mail_server = new includes_class_sendmail ( );
				$body .= "您好，" . $_SESSION[ 'USERNAME' ] . "提交了设备借用申请，需要您登录OA审批处理。<br />" . oaurlinfo;
				if ( $mail_server -> send ( $_SESSION[ 'USERNAME' ] . '提交了设备借用申请' , $body , $email ) )
				{
					return $this -> query ( "update device_apply_order set send_email=1 where id=$orderid" );
				}
			}
			return true;
		} else
		{
			return false;
		}
	}
	/**
	 * 修改借用申请单
	 * @param unknown_type $orderid
	 * @param unknown_type $key
	 * @param unknown_type $data
	 */
	function model_edit_order ( $orderid , $key , $data )
	{
		$row = $this -> get_one ( "select a.*,b.manager from device_apply_order as a left join project_info as b on b.id=a.project_id  where a.id=$orderid and a.rand_key='$key'" );
		if ( $row )
		{
			$query = $this -> query ( "select 
									b.state 
									from 
										device_apply_order_info as a 
										left join device_info as b on b.id=a.info_id
									where
										a.orderid=$orderid and rand_key='$key'
										" );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				if ( $rs[ 'state' ] != 0 )
				{
					showmsg ( '您申请的设备已经被别人借用！' );
				}
			}
			
			$query = $this -> query ( "select id,info_id from device_apply_order_info where orderid=$orderid" );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				if (  ! in_array ( $rs[ 'info_id' ] , $data[ 'id' ] ) )
				{
					$this -> query ( "delete from device_apply_order_info where id=" . $rs[ 'id' ] );
				}
			}
			$this -> query ( "update device_info set state=5 where id in(select info_id from device_apply_order_info where orderid=" . $orderid . ")" );
			$this -> query ( "
									update 
										device_apply_order 
									set 
										ls='" . $data[ 'ls' ] . "',
										dept_id='" . $data[ 'dept_id' ] . "',
										project_id='" . ( $data[ 'ls' ] == 1 ? $data[ 'project_id' ] : '' ) . "',
										description='" . $data[ 'description' ] . "',
										target_date='" . strtotime ( $data[ 'target_date' ] ) . "',
										status=0
									where
										id=$orderid and rand_key='" . $key . "'
								" );
			if ( $row[ 'send_email' ] == 0 )
			{
				/*if ($data['ls']==1)
				{
					$gl = new includes_class_global();
					$rt = $this->get_one("select manager from project_info where id='".$data['project_id']."'");
					if (($rt['manager'] && in_array($data['userid'],explode(',',$rt['manager']))) || !$rt['manager'])
					{
						$ra = $this->get_one("select majorid,vicemanager from department where dept_id='".$data['dept_id']."'");
						$audit_userid = $ra['majorid'] ? explode(',',$ra['majorid']) : ($ra['vicemanager'] ? explode(',',$ra['vicemanager']) : '');
						if ($audit_userid)
						{
							$email = $gl->get_email($audit_userid);
						}
					}else if($rt['manager']){
						$email = $gl->get_email((strpos($rt['manager'],',')!==false ? explode(',',$rt['manager']) : $rt['manager']));
					}
				}else{
					$query = $this->query("
											select 
												b.userid 
											from 
												purview as a
												left join purview_info as b on b.tid=a.id
											where 
												(a.models='".$_GET['model']."' and a.type=1 and a.control=1) 
												or 
												(a.models='".$_GET['model']."' and a.func='".$_GET['action']."')");
					$userid = array();
					while (($rr = $this->fetch_array($query))!=false)
					{
						$userid[] = sprintf("'%s'",$rr['userid']);
					}
					if ($userid)
					{
						$query = $this->query("select email from user where dept_id='".$_SESSION['DEPT_ID']."' and user_id in(".implode(',',$userid).")");
						$email = array();
						while (($ra = $this->fetch_array($query))!=false)
						{
							$email[] = $ra['email'];
						}
					}
				}*/
				//===新修改审批规则
				$query = $this -> query ( "select 
											d.email
									from 
										purview as a 
										left join purview_type as b on b.tid=a.id
										left join purview_info as c on c.tid=a.id and c.typeid=b.id
										left join user as d on d.user_id=c.userid
										where 
											a.models = 'device_apply'
											and b.name='审批部门'
											and find_in_set('" . $_SESSION[ 'DEPT_ID' ] . "',c.content)
										" );
				$email = array ();
				while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
				{
					$email[ ] = $rs[ 'email' ];
				}
				if ( $email )
				{
					$mail_server = new includes_class_sendmail ( );
					$body .= "您好，" . $_SESSION[ 'USERNAME' ] . "修改了设备借用申请，需要您登录OA审批处理。<br />" . oaurlinfo;
					if ( $mail_server -> send ( $_SESSION[ 'USERNAME' ] . '修改了设备借用申请' , $body , $email ) )
					{
						return $this -> query ( "update device_apply_order set send_email=1 where id=$orderid and rand_key='$key'" );
					}
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
	 * 设备申请列表
	 */
	function model_apply_list ( )
	{
		global $func_limit;
		if ( $func_limit[ '管理员' ] || $_SESSION[ 'USER_ID' ] == 'admin' )
		{
			$where = "where a.userid!=''";
		} else
		{
			$where = "where a.userid='" . $_SESSION[ 'USER_ID' ] . "'";
		}
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from device_apply_order as a $where" );
			$this -> num = $rs[ 'num' ];
		}
		
		if ( $this -> num > 0 )
		{
			$query = $this -> query ( "
									select
										a.*,b.user_name,c.dept_name,d.name as project_name,d.number,e.user_name as audit_name
									from
										device_apply_order as a
										left join user as b on b.user_id=a.userid
										left join department as c on c.dept_id=a.dept_id
										left join project_info as d on d.id=a.project_id
										left join user as e on e.user_id=a.audit_userid
									$where
									order by a.status=0 desc,a.id desc
									limit $this->start," . pagenum );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$str .= '<tr>';
				$str .= '<td>' . $rs[ 'id' ] . '</td>';
				$str .= '<td>' . $rs[ 'user_name' ] . '</td>';
				$str .= '<td>' . ( $rs[ 'ls' ] == 1 ? $rs[ 'project_name' ] : $rs[ 'dept_name' ] ) . '</td>';
				$str .= '<td>' . $rs[ 'number' ] . '</td>';
				$str .= '<td width="25%">' . $rs[ 'description' ] . '</td>';
				$str .= '<td >' . $rs[ 'total' ] . '</td>';
				$str .= '<td >' . $rs[ 'accept_num' ] . '</td>';
				$str .= '<td >' . $rs[ 'accept_return_num' ] . '</td>';
				$str .= '<td >' . $rs[ 'accept_return_num' ] . '</td>';
				//$str .='<td>'.($rs['accept_status']==1 ? '<span class="purple">已受理</span>' : ($rs['status']==0 ? '<span>待审批</span>' :($rs['status']==1 ? '已审批' :($rs['status']==-2 ? '已取消申请' :'<a href="javascript:show_notes('.$rs['id'].');">被打回</a><span id="notes_'.$rs['id'].'" style="display:none">'.$rs['notes'].'</span>')))).'</td>';
				$str .= '<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>';
				$str .= '<td>' . date ( 'Y-m-d' , $rs[ 'target_date' ] ) . '</td>';
				$str .= '<td>' . $rs[ 'audit_name' ] . '</td>';
				$str .= '<td>' . ( $rs[ 'audit_date' ] ? date ( 'Y-m-d' , $rs[ 'audit_date' ] ) : '' ) . '</td>';
				$str .= '<td>' . thickbox_link ( '查看或修改' , 'a' , 'orderid=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , '设备借用详细' , null , 'edit_order' ) . '</td>';
				$str .= '</tr>';
			}
			
			if ( $this -> num > pagenum )
			{
				$showpage = new includes_class_page ( );
				$showpage -> show_page ( array ( 
												'total' => $this -> num , 
												'perpage' => pagenum 
				) );
				$showpage -> _set_url ( 'num=' . $this -> num );
				return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
			} else
			{
				return $str;
			}
		}
	}
	/**
	 * 设备审批列表
	 */
	function model_apply_audit_list ( )
	{
		/*global $func_limit;
		if ($func_limit['审批部门'])
		{
			$where = " where (a.dept_id='".$_SESSION['DEPT_ID']."' and a.ls=0) or (find_in_set(a.userid,d.manager) and a.dept_id='".$_SESSION['DEPT_ID']."')";
		}else{
			$where =" where ls=1 and find_in_set('".$_SESSION['USER_ID']."',d.manager)";
		}*/
		$rs = $this -> get_one ( "select 
										c.content
								from 
									purview as a 
									left join purview_type as b on b.tid=a.id
									left join purview_info as c on c.tid=a.id and c.typeid=b.id
								where
									a.models = 'device_apply'
									and b.name='审批部门'
									and c.userid='" . $_SESSION[ 'USER_ID' ] . "'
									" );
		if ( $rs )
		{
			$where = " where a.dept_id in(" . trim ( $rs[ 'content' ] , ',' ) . ")";
		} else
		{
			return false;
		}
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from device_apply_order as a left join project_info as d on d.id=a.project_id $where" );
			$this -> num = $rs[ 'num' ];
		}
		
		if ( $this -> num > 0 )
		{
			$query = $this -> query ( "
									select
										a.*,b.user_name,c.dept_name,d.name as project_name,d.number,e.user_name as audit_name
									from
										device_apply_order as a
										left join user as b on b.user_id=a.userid
										left join department as c on c.dept_id=a.dept_id
										left join project_info as d on d.id=a.project_id
										left join user as e on e.user_id=a.audit_userid
									$where
									order by a.status=0 desc,a.id desc
									limit $this->start," . pagenum );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$str .= '<tr>';
				$str .= '<td>' . $rs[ 'id' ] . '</td>';
				$str .= '<td>' . $rs[ 'user_name' ] . '</td>';
				$str .= '<td>' . ( $rs[ 'ls' ] == 1 ? $rs[ 'project_name' ] : $rs[ 'dept_name' ] ) . '</td>';
				$str .= '<td>' . $rs[ 'number' ] . '</td>';
				$str .= '<td width="20%">' . $rs[ 'description' ] . '</td>';
				$str .= '<td>' . ( $rs[ 'accept_status' ] == 1 ? '<span class="purple">已受理</span>' : ( $rs[ 'status' ] == 0 ? '<span>待审批</span>' : ( $rs[ 'status' ] == 1 ? '已审批' : '<a href="javascript:show_notes(' . $rs[ 'id' ] . ');">被打回</a><span id="notes_' . $rs[ 'id' ] . '" style="display:none">' . $rs[ 'notes' ] . '</span>' ) ) ) . '</td>';
				$str .= '<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>';
				$str .= '<td>' . date ( 'Y-m-d' , $rs[ 'target_date' ] ) . '</td>';
				$str .= '<td>' . $rs[ 'audit_name' ] . '</td>';
				$str .= '<td>' . ( $rs[ 'audit_date' ] ? date ( 'Y-m-d H:i' , $rs[ 'audit_date' ] ) : '' ) . '</td>';
				$str .= '<td>' . thickbox_link ( '查看或审批' , 'a' , 'audit=yes&orderid=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , '设备借用审批' , null , 'edit_order' ) . '</td>';
			}
			
			if ( $this -> num > pagenum )
			{
				$showpage = new includes_class_page ( );
				$showpage -> show_page ( array ( 
												'total' => $this -> num , 
												'perpage' => pagenum 
				) );
				$showpage -> _set_url ( 'num=' . $this -> num );
				return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
			} else
			{
				return $str;
			}
		}
	}
	/**
	 * 设备审核申请借用单
	 * @param $id
	 * @param $key
	 * @param $notse
	 */
	function model_apply_audit_order ( $id , $key , $data )
	{
		$rs = $this -> get_one ( "
								select 
									a.*,b.email
								from 
									device_apply_order as a
									left join user as b on b.user_id=a.userid
								where
									a.id=$id and a.rand_key='$key'
							" );
		if ( $rs )
		{
			$email_server = new includes_class_sendmail ( );
			if ( $data[ 'notes' ] )
			{
				if ( $this -> query ( "update device_apply_order set audit_userid='" . $_SESSION[ 'USER_ID' ] . "',audit_date='" . time ( ) . "', status=-1,send_email=0,notes='" . $data[ 'notes' ] . "' where id=$id and rand_key='$key'" ) )
				{
					$this -> query ( "update device_info set state=0 where id in(select info_id from device_apply_order_info where orderid=" . $id . ")" );
					if ( $email_server -> send ( '提醒：您的设备申请已被打回' , '以下为被打回原因<br /><br />' . $data[ 'notes' ] . oaurlinfo , $rs[ 'email' ] ) )
					{
						return true;
					} else
					{
						showmsg ( '操作成功，但发送邮件提醒失败！' );
					}
				} else
				{
					return false;
				}
			} else
			{
				$query = $this -> query ( "select * from device_apply_order_info where orderid=$id" );
				while ( ( $row = $this -> fetch_array ( $query ) ) != false )
				{
					
					if ( in_array ( $row[ 'info_id' ] , $data[ 'id' ] ) )
					{
						$this -> query ( "update device_apply_order_info set status=1 where id=" . $row[ 'id' ] );
						$this -> query ( "update device_info set state=6 where id=" . $row[ 'info_id' ] );
					} else
					{
						$this -> query ( "update device_apply_order_info set status=-1 where id=" . $row[ 'id' ] );
						$this -> query ( "update device_info set state=0 where id=" . $row[ 'info_id' ] );
					}
				}
				if ( $this -> query ( "update device_apply_order set audit_userid='" . $_SESSION[ 'USER_ID' ] . "',audit_date='" . time ( ) . "', status=1 where id=$id and rand_key='$key'" ) )
				{
					return $email_server -> send ( '提醒：您的设备申请已审批通过' , '详情请登录OA系统查看' . oaurlinfo , $rs[ 'email' ] );
				} else
				{
					return false;
				}
			}
		} else
		{
			return false;
		}
	}
	/**
	 * 设备申请每单详细
	 * @param $id
	 * @param $key
	 */
	function model_apply_order_info ( $id , $key )
	{
		return $this -> get_one ( "
								select
										a.*,b.user_name,c.dept_name,d.name as project_name,d.number,e.user_name as audit_name
									from
										device_apply_order as a
										left join user as b on b.user_id=a.userid
										left join department as c on c.dept_id=a.dept_id
										left join project_info as d on d.id=a.project_id
										left join user as e on e.user_id=a.audit_userid
									where
										a.id=$id and a.rand_key='$key'
		" );
	}
	/**
	 * 每单详细设备列表
	 * @param int $orderid
	 */
	function model_apply_order_info_list ( $orderid , $dept_id = '' , $accept = false )
	{
		global $func_limit;
		if ( $dept_id )
		{
			$query = $this -> query ( "
									select 
										a.info_id ,a.amount,a.status
									from 
										device_apply_order_info as a
										left join device_info as b on b.id=a.info_id
									where 
										b.dept_id in (" . ( $func_limit[ '管理部门' ] ? $func_limit[ '管理部门' ] : $dept_id ) . ")
										" . ( $accept ? 'and b.area in(' . $func_limit[ '管理区域' ] . ')' : '' ) . "
										and orderid=$orderid
									" );
		
		} else
		{
			$query = $this -> query ( "select info_id,amount,status from device_apply_order_info where orderid=$orderid" );
		
		}
		$infoid = array ();
		$amount_arr = array ();
		$status_arr = array ();
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$infoid[ ] = $rs[ 'info_id' ];
			$amount_arr[ $rs[ 'info_id' ] ] = $rs[ 'amount' ];
			$status_arr[ $rs[ 'info_id' ] ] = $rs[ 'status' ];
		}
		if ( $infoid )
		{
			return $this -> model_device_info ( $infoid , $amount_arr , $status_arr , $accept );
		} else
		{
			return false;
		}
	}
	/**
	 * 设备信息
	 * @param unknown_type $info
	 * @param unknown_type $amount
	 * @param unknown_type $status
	 * @param unknown_type $accept
	 */
	function model_device_info ( $info , $amount = null , $status = null , $accept )
	{
		$stock = new model_device_stock ( );
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
							<td><input type="checkbox" onclick="set_all(this.checked,' . $rs[ 'tid' ] . ')"</td>
							<td>序号</td>
							<td>设备名称</td>
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> get_fixed_field_name ( $rs[ 'tid' ] ) ) . '
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> model_show_field_name ( $rs[ 'tid' ] ) ) . '
							<td>所在库存</td>
							<td>库存数量</td>
							<td>借出数量</td>
							<td>借用数量</td>
							<td>审批状态</td>
						</tr>';
			$data = $stock -> model_type_info ( $rs[ 'tid' ] , $info , false );
			if ( $data )
			{
				foreach ( $data as $row )
				{
					if ( $row )
					{
						$str .= '<tr>';
						foreach ( $row as $key => $val )
						{
							if ( $key == 0 )
							{
								$str .= '<td><input type="checkbox" ' . ( $accept == true && $status[ $row[ 0 ] ] != 1 ? 'disabled' : '' ) . ' name="id[]" value="' . $val . '" /></td>';
							}
							$str .= '<td>' . $val . '</td>';
						}
						$str .= '<td class="amount_' . $row[ 0 ] . '">' . $amount[ $row[ 0 ] ] . '</td>';
						$str .= '<td>' . ( $status[ $row[ 0 ] ] == 1 ? '已审批' : ( $status[ $row[ 0 ] ] ==  - 1 ? '被打回' : ( $status[ $row[ 0 ] ] == 2 ? '已受理' : '待审批' ) ) ) . '</td>';
						$str .= '</tr>';
					}
				}
			}
			$str .= '</table><br />';
		}
		return $str;
	}
	/**
	 * 受理设备申请列表
	 */
	function model_apply_accept_list ( )
	{
		global $func_limit;
		//var_dump($func_limit);
		if (  ! $func_limit[ '管理区域' ] ||  ! $func_limit[ '管理部门' ] )
			return false;
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "
									select 
										count(distinct(a.id)) as num
									from 
										device_apply_order as a
										left join device_apply_order_info as b on b.orderid=a.id
										left join device_info as c on c.id=b.info_id
									where
										c.dept_id in (" . $func_limit[ '管理部门' ] . ")
										and c.area in (" . $func_limit[ '管理区域' ] . ")
										and a.status = 1
								" );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num > 0 )
		{
			$query = $this -> query ( "
									select
										distinct(a.id),a.status,a.total,a.accept_num,a.accept_return_num,a.target_date,a.audit_date,a.date,a.ls,a.accept_status,a.description,a.rand_key
										,b.user_name,c.dept_name,d.name as project_name,d.number,e.user_name as audit_name
									from
										device_apply_order as a
										left join user as b on b.user_id=a.userid
										left join department as c on c.dept_id=a.dept_id
										left join project_info as d on d.id=a.project_id
										left join user as e on e.user_id=a.audit_userid
										left join device_apply_order_info as f on f.orderid=a.id
										left join device_info as g on g.id=f.info_id
									where
										g.dept_id in (" . $func_limit[ '管理部门' ] . ")
										and g.area in (" . $func_limit[ '管理区域' ] . ")
										and a.status = 1
									order by a.audit_date desc
									limit $this->start , " . pagenum . "
								" );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$str .= '<tr>';
				$str .= '<td>' . $rs[ 'id' ] . '</td>';
				$str .= '<td>' . $rs[ 'user_name' ] . '</td>';
				$str .= '<td>' . ( $rs[ 'ls' ] == 1 ? $rs[ 'project_name' ] : $rs[ 'dept_name' ] ) . '</td>';
				$str .= '<td>' . $rs[ 'number' ] . '</td>';
				$str .= '<td width="20%">' . $rs[ 'description' ] . '</td>';
				$str .= '<td>' . $rs[ 'total' ] . '</td>';
				$str .= '<td><span>' . ( $rs[ 'total' ] - $rs[ 'accept_num' ] - $rs[ 'accept_return_num' ] ) . '</span></td>';
				$str .= '<td>' . $rs[ 'accept_num' ] . '</td>';
				$str .= '<td>' . $rs[ 'accept_return_num' ] . '</td>';
				//$str .='<td>'.($rs['accept_status']==0 ? '<span>待受理</span>' :(($rs['accept_num']-$rs['accept_return_num'])==$rs['total'] ? '已受理全部' :($rs['accept_return_num'] == $rs['total'] ? '<a href="javascript:show_notes('.$rs['id'].');">被打回</a><span id="notes_'.$rs['id'].'" style="display:none">'.$rs['notes'].'</span>' : '已受理'.$rs['accept_num']))).'</td>';
				$str .= '<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>';
				$str .= '<td>' . date ( 'Y-m-d' , $rs[ 'target_date' ] ) . '</td>';
				$str .= '<td>' . $rs[ 'audit_name' ] . '</td>';
				$str .= '<td>' . ( $rs[ 'audit_date' ] ? date ( 'Y-m-d' , $rs[ 'audit_date' ] ) : '' ) . '</td>';
				$str .= '<td>' . thickbox_link ( '查看或受理' , 'a' , 'audit=accept&orderid=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , '设备借用详细' , null , 'edit_order' , null , 500 ) . '</td>';
				$str .= '</tr>';
			
			}
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array ( 
											
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num );
			return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
		} else
		{
			return false;
		}
	}
	/**
	 * 受理时打回
	 * @param $orderid
	 * @param $data
	 */
	function accept_return ( $orderid , $info_id_data )
	{
		
		if ( $orderid && is_array ( $info_id_data ) )
		{
			try
			{
				$this -> _db -> query ( "START TRANSACTION" );
				$this -> query ( "update device_info set state=0 where id in (" . implode ( ',' , $info_id_data ) . ")" );
				$this -> query ( "update device_apply_order as a,device_apply_order_info as b set a.accept_return_num=(a.accept_return_num+" . count ( $info_id_data ) . "), b.status='-1' where b.orderid=a.id and a.id=$orderid and b.info_id in(" . implode ( ',' , $info_id_data ) . ")" );
				$this -> _db -> query ( "COMMIT" );
				return true;
			} catch ( Exception $e )
			{
				$this -> _db -> query ( "ROLLBACK" );
				return false;
			}
		}
	
	}
}

?>