<?php
/**
 * ���ú͹黹MODEL
 * @author 1
 *
 */
class model_device_borrow extends model_base
{
	public $page;
	public $num;
	public $start;
	//*******************************���캯��***********************************
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> page = intval ( $_GET[ 'page' ] ) ? intval ( $_GET[ page ] ) : 1;
		$this -> start = ( $this -> page == 1 ) ? 0 : ( $this -> page - 1 ) * pagenum;
		$this -> num = intval ( $_GET[ 'num' ] ) ? intval ( $_GET[ 'num' ] ) : false;
		//ϵͳ�Զ�����ȷ��״̬
		$this -> query ( "update 
						device_borrow_order_info as a,device_borrow_order as b,device_info as c
					 set
					 	b.confirm=1,a.claim=1,c.state=1
					 where
					 	b.id=a.orderid and c.id=a.info_id and a.claim=0 and ceil((UNIX_TIMESTAMP()-a.date) /86400) >=8
						" );
	}
	//*********************************���ݴ���********************************
	/**
	 * �Ƴ��赥��¼
	 *
	 */
	function model_del_order_info ( )
	{
		if ( $_POST[ 'id' ] )
		{
			try
			{
				$this -> _db -> query ( "START TRANSACTION" );
				$this -> tbl_name = 'device_info';
				$amount = 0;
				foreach ( $_POST[ 'id' ] as $val )
				{
					$rs = $this -> get_one ( "select info_id,list_id,amount from device_borrow_order_info where claim!=1 and id=" . $val );
					if ( $rs )
					{
						$amount = $amount + $rs[ 'amount' ];
						$this -> query ( "update device_info set state=0,borrow_num=(borrow_num-" . $rs[ 'amount' ] . ") where id=" . $rs[ 'info_id' ] );
						$this -> query ( "delete from device_borrow_order_info where id=" . $val );
						$this -> query ( "update device_borrow_order set amount=(amount-" . $rs[ 'amount' ] . ") where id=" . $_GET[ 'orderid' ] );
						$this -> query ( "update device_list set borrow=borrow-" . $rs[ 'amount' ] . ",surplus=surplus+" . $rs[ 'amount' ] . " , rate=(1-CAST((borrow/total) AS DECIMAL(11,2)))  where id=" . $rs[ 'list_id' ] );
					}
				
				}
				$row = $this -> get_one ( "
									select 
										sum(a.amount) as num 
									from
										device_borrow_order_info as a
										left join device_info as b on b.id=a.info_id
									where
										a.orderid=" . $_GET[ 'orderid' ] . " and b.state=1
			" );
				$rss = $this -> get_one ( "select amount from device_borrow_order where id=" . $_GET[ 'orderid' ] );
				$this -> query ( "update device_borrow_order set confirm=" . ( $rss[ 'amount' ] == $row[ 'num' ] ? 1 : 0 ) . " where id=" . $_GET[ 'orderid' ] );
				
				$this -> _db -> query ( "COMMIT" );
				return true;
			} catch ( Exception $e )
			{
				$this -> _db -> query ( "ROLLBACK" );
				return false;
			}
		}
	
	}
	/**
	 * �����������
	 */
	function model_batch_borrow_save ( $data )
	{
		
		if ( $data[ 'id' ] )
		{
			try
			{
				$createDate=$data[ 'createDate' ]?$data[ 'createDate' ]:date('Y-m-d H:i:s');
				$this -> _db -> query ( "START TRANSACTION" );
				$this -> tbl_name = 'device_borrow_order';
				$orderid = $this -> create ( array ( 
													'userid' => $data[ 'userid' ] , 
													'dept_id' => $data[ 'dept_id' ] , 
													'project_id' => $data[ 'project_id' ] , 
													'operatorid' => $data[ 'operatorid' ] , 
													'manager' => $data[ 'managerid' ] , 
													'area' => $data[ 'area' ] , 
													'targetdate' => strtotime ( $data[ 'targettime' ] ) , 
													'date' => strtotime ( $createDate )
				) );
				if ( $orderid )
				{
					$amount = 0;
					$this -> tbl_name = 'device_borrow_order_info';
					foreach ( $data[ 'id' ] as $key => $val )
					{
						$info = '';
						$rs = $this -> get_one ( "
											select 
												b.typeid,a.list_id 
											from 
												device_info as a 
												left join device_list as b on b.id=a.list_id
											where
												a.id=$val
										" );
						if ( $val > 0 )
						{
							$amount = $amount + intval ( $data[ 'amount' ][ $val ] );
							$info = $this -> create ( array ( 
																
																'orderid' => $orderid , 
																'info_id' => $val , 
																'typeid' => $rs[ 'typeid' ] , 
																'list_id' => $rs[ 'list_id' ] , 
																'amount' => intval ( $data[ 'amount' ][ $val ] ) , 
																'targetdate' => ( $data[ 'target_date' ][ $val ] ? strtotime ( $data[ 'target_date' ][ $val ] ) : strtotime ( $data[ 'targettime' ] ) ) , 
																'notse' => ( $data[ 'notse' ][ $val ] ? $data[ 'notse' ][ $val ] : '' ) , 
																'date' => strtotime ( $createDate )
							) );
						}
						if ( $info )
						{
							var_dump ( intval ( $data[ 'amount' ][ $val ] ) );
							$this -> _db -> query ( "
											update 
												device_info 
											set 
												borrow_num=borrow_num+" . intval ( $data[ 'amount' ][ $val ] ) . ",
												state=2
											where 
												id=" . $val );
							$this -> _db -> query ( "
						update 
							device_list
						set 
							borrow=borrow+" . intval ( $data[ 'amount' ][ $val ] ) . ",
							surplus=surplus-" . intval ( $data[ 'amount' ][ $val ] ) . " 
							where id=" . $rs[ 'list_id' ] . "
							" );
						}
					}
					if ( $amount > 0 )
					{
						$this -> tbl_name = 'device_borrow_order';
						$this -> update ( 'id=' . $orderid , array ( 
																	
																	'amount' => $amount 
						) );
						if ( $data[ '_email' ] )
						{
							$email_tpl = file_get_contents ( TPL_DIR . '/device/email-borrow.htm' );
							foreach ( $data as $key => $val )
							{
								$email_tpl = str_replace ( '{' . $key . '}' , $val , $email_tpl );
							}
							$email_tpl = str_replace ( '{_date}' , date ( 'Y-m-d' ) , $email_tpl );
							$email_tpl = str_replace ( '{list}' , $this -> model_borrow_order_info_list ( $orderid ) , $email_tpl );
							$email = new includes_class_sendmail ( );
							$email -> send ( $data[ '_username' ] . '������' . $amount . '���豸' , $email_tpl , explode ( "\r\n" , $data[ '_email' ] ) );
						}
					} else
					{
						$this -> _db -> query ( "ROLLBACK" );
						return false;
					}
				} else
				{
					$this -> _db -> query ( "ROLLBACK" );
					return false;
				}
				$this -> _db -> query ( "COMMIT" );
				return true;
			} catch ( Exception $e )
			{
				$this -> _db -> query ( "ROLLBACK" );
				return false;
			}
		
		} else
		{
			showmsg ( '�����������һ�����ϣ�' );
		}
	}
	/**
	 * ����ת������
	 * @param $data
	 */
	function model_rutn ( $data )
	{
		if ( $data )
		{
			$temp = array ();
			$query = $this -> query ( "
									select 
										a.id,a.info_id,(a.amount-a.return_num) as number,a.targetdate,a.status,a.notse ,b.targetdate as target_time
									from 
										device_borrow_order_info as a 
										left join device_borrow_order as b on b.id=a.orderid
									where 
										a.orderid=" . $data[ 'orderid' ] . " 
										and a.info_id in (" . implode ( ',' , $data[ 'id' ] ) . ")
								" );											
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$temp[ 'id' ][ ] = $rs[ 'id' ];
				$temp[ 'return_num' ][ $rs[ 'id' ] ] = $rs[ 'number' ];
				$temp[ 'status' ][ $rs[ 'id' ] ] = $rs[ 'status' ];
				$temp[ 'notse' ][ $rs[ 'id' ] ] = $rs[ 'notse' ];
				$data[ 'target_date' ][ $rs[ 'info_id' ] ] = date ( 'Y-m-d H:i:s' , $rs[ 'targetdate' ] );
				$data[ 'targettime' ] = date ( 'Y-m-d H:i:s' , $rs[ 'target_time' ] );
				$data[ 'amount' ][ $rs[ 'info_id' ] ] = $rs[ 'number' ];
			}
			$temp[ 'userid' ] = $data[ 'userid' ];
			$temp[ 'dept_id' ] = $data[ 'dept_id' ];
			$temp[ 'operatorid' ] = $data[ 'operatorid' ];
			$temp[ 'area' ] = $data[ 'area' ];
			if ( $this -> model_save_return_list ( $temp ) )
			{
				return $this -> model_batch_borrow_save ( $data );
			} else
			{
				showmsg ( '���ɹ黹��ʧ��,�������Ա��ϵ��' );
			}
		}
	}
	/**
	 * �������
	 * @param $data
	 */
	function model_borrow_accept ( $data , $apply_orderid )
	{
		$flag = false;
		try
		{
			set_time_limit ( 120 );
			$this -> _db -> query ( "START TRANSACTION" );
			if ( $data[ 'id' ] )
			{
				$this -> tbl_name = 'device_borrow_order';
				
				$orderid = $this -> create ( array ( 
													
													'userid' => $data[ 'userid' ] , 
													'dept_id' => $_SESSION[ 'DEPT_ID' ] , 
													'project_id' => $data[ 'project_id' ] , 
													'operatorid' => $_SESSION[ 'USER_ID' ] , 
													'targetdate' => strtotime ( $_POST[ 'targettime' ] ) , 
													'date' => time ( ) 
				) );
				
				if ( $orderid )
				{
					$amount = 0;
					$this -> tbl_name = 'device_borrow_order_info';
					foreach ( $data[ 'id' ] as $key => $val )
					{
						$info = '';
						if ( $val > 0 )
						{
							$amount = $amount + intval ( $data[ 'amount' ][ $val ] );
							$rs = $this -> get_one ( "
												select 
													a.list_id,b.typeid 
												from 
													device_info as a 
													left join device_list as b on b.id=a.list_id
												where
													a.id=$val
													
													" );
							$info = $this -> create ( array ( 
																
																'orderid' => $orderid , 
																'info_id' => $val , 
																'typeid' => $rs[ 'typeid' ] , 
																'list_id' => $rs[ 'list_id' ] , 
																'amount' => intval ( $data[ 'amount' ][ $val ] ) , 
																'targetdate' => ( $data[ 'info_target_date' ][ $val ] ? strtotime ( $data[ 'info_target_date' ][ $val ] ) : strtotime ( $data[ 'targettime' ] ) ) , 
																'notse' => $data[ 'notse' ][ $val ] , 
																'date' => time ( ) 
							) );
						}
						if ( $info )
						{
							$this -> query ( "update device_apply_order_info set status=2 where orderid=$apply_orderid and info_id=" . $val );
							$this -> _db -> query ( "
											update 
												device_info 
											set 
												borrow_num=borrow_num+" . intval ( $data[ 'amount' ][ $val ] ) . ",
												state=2
											where 
												id=" . $val );
							$this -> _db -> query ( "
						update 
							device_list
						set 
							borrow=borrow+" . intval ( $data[ 'amount' ][ $val ] ) . ",
							surplus=surplus-" . intval ( $data[ 'amount' ][ $val ] ) . " 
							where id=" . $rs[ 'list_id' ] . "
							" );
						}
					}
					if ( $amount > 0 )
					{
						$this -> tbl_name = 'device_borrow_order';
						$this -> update ( 'id=' . $orderid , array ( 
																	'amount' => $amount 
						) );
						$this -> query ( "update device_apply_order set accept_num=(accept_num+" . $amount . "),accept_status=1,accept_date='" . time ( ) . "' where id=$apply_orderid" );
						if ( $_POST[ 'email' ] )
						{
							$email_tpl = file_get_contents ( TPL_DIR . '/device/email-borrow-accept.htm' );
							foreach ( $_POST as $key => $val )
							{
								if ( $key == 'project_id' )
								{
									if ( $val )
									{
										$email_tpl = str_replace ( '{dt_none}' , 'none' , $email_tpl );
										$email_tpl = str_replace ( '{xm_none}' , '' , $email_tpl );
									} else
									{
										$email_tpl = str_replace ( '{dt_none}' , '' , $email_tpl );
										$email_tpl = str_replace ( '{xm_none}' , 'none' , $email_tpl );
									}
								}
								$email_tpl = str_replace ( '{' . $key . '}' , $val , $email_tpl );
							}
							$email_tpl = str_replace ( '{list}' , $this -> model_borrow_order_info_list ( $orderid ) , $email_tpl );
							$email = new includes_class_sendmail ( );
							$email -> send ( $_POST[ '_username' ] . '������' . $amount . '���豸' , $email_tpl , explode ( "\r\n" , $_POST[ 'email' ] ) );
						}
						$flag = true;
					}
				}
			}
			$this -> _db -> query ( "COMMIT" );
			return $flag;
		} catch ( Exception $e )
		{
			$this -> _db -> query ( "ROLLBACK" );
			return false;
		}
	
	}
	/**
	 * ���������黹
	 */
	function model_save_return_list ( $data )
	{
		$flag = true;
		if ( $data[ 'id' ] )
		{
			$createDate=$data['createDate']?strtotime($data['createDate']):time ( );
			try
			{
				$this -> _db -> query ( "START TRANSACTION" );
				$this -> tbl_name = 'device_return_order';
				$orderid = $this -> create ( array ( 
													
													'userid' => $data[ 'userid' ] , 
													'dept_id' => $data[ 'dept_id' ] , 
													'operatorid' => $data[ 'operatorid' ] , 
													'area' => $data[ 'area' ] , 
													'date' =>time ( ) 
				) );
				if ( $orderid )
				{
					$amount = 0;
					$this -> tbl_name = 'device_return_order_info';
					foreach ( $data[ 'id' ] as $key => $val )
					{
						$rs = $this -> _db -> get_one ( "
											select 
												a.id,a.info_id,b.list_id,b.area 
											from 
												device_borrow_order_info as a 
												left join device_info as b on b.id=a.info_id
											where 
												a.id=" . $val );
						if ( $rs )
						{
							$info = '';
							if ( $val > 0 )
							{
								$amount = $amount + intval ( $data[ 'return_num' ][ $rs[ 'id' ] ] );
								$info = $this -> create ( array ( 
																	
																	'orderid' => $orderid , 
																	'info_id' => $rs[ 'id' ] , 
																	'tid' => intval ( $rs[ 'info_id' ] ) , 
																	'area' => $rs[ 'area' ] , 
																	'to_area' => $data[ 'area' ] , 
																	'amount' => intval ( $data[ 'return_num' ][ $rs[ 'id' ] ] ) , 
																	'status' => $data[ 'status' ][ $rs[ 'id' ] ] , 
																	'notse' => $data[ 'notse' ][ $rs[ 'id' ] ] , 
																	'date' => $createDate 
								) );
							}
							if ( $info )
							{
								$this -> _db -> query ( "
											update 
												device_info 
											set 
												borrow_num=borrow_num-" . intval ( $data[ 'return_num' ][ $rs[ 'id' ] ] ) . ",
												area = '" . $data[ 'area' ] . "',
												state =IF((borrow_num-" . intval ( $data[ 'return_num' ][ $rs[ 'id' ] ] ) . ")>=0,'1','0')
											where 
												id='".$rs[ 'info_id' ]."'" );
								$this -> _db -> query ( "
											update 
												device_borrow_order_info 
											set 
												return_num=return_num+" . intval ( $data[ 'return_num' ][ $rs[ 'id' ] ] ) . ",
												returndate=IF((return_num)>=amount,'" .$createDate . "',NULL),
												status='" . $data[ 'status' ][ $rs[ 'id' ] ] . "',
												notse='" . $data[ 'notse' ][ $rs[ 'id' ] ] . "' 
											where 
											id=$val" );
								$this -> _db -> query ( "
											update 
												device_list as a,device_borrow_order_info as b 
											set 
												a.borrow=a.borrow-" . intval ( $data[ 'return_num' ][ $rs[ 'id' ] ] ) . ",
												a.surplus=a.surplus+" . intval ( $data[ 'return_num' ][ $rs[ 'id' ] ] ) . " 
											where 
												a.id=b.list_id and b.id=$val
										" );
								if ( $rs[ 'area' ] != $data[ 'area' ] )
								{
									$this -> _db -> query ( "insert into 
														device_shift_list
													set
														info_id=" . $rs[ 'info_id' ] . ",
														list_id='" . $rs[ 'list_id' ] . "',
														amount='" . $data[ 'return_num' ][ $rs[ 'id' ] ] . "',
														area='" . $rs[ 'area' ] . "',
														to_area='" . $data[ 'area' ] . "',
														userid='" . $data[ 'operatorid' ] . "',
														date='" . time ( ) . "'
												" );
								}
							}
						}
					}
				
				}
				
				if ( $amount > 0 )
				{
					$this -> _db -> query ( "update device_return_order set amount=$amount where id=" . $orderid );
					if ( $data[ 'email' ] )
					{
						$email_tpl = file_get_contents ( TPL_DIR . '/device/email-return.htm' );
						foreach ( $data as $key => $val )
						{
							$email_tpl = str_replace ( '{' . $key . '}' , $val , $email_tpl );
						}
						$email_tpl = str_replace ( '{operator}' , $_SESSION[ 'USERNAME' ] , $email_tpl );
						$email_tpl = str_replace ( '{list}' , $this -> model_return_order_info_list ( $orderid ) , $email_tpl );
						$email = new includes_class_sendmail ( );
						$email -> send ( $data[ 'user_name' ] . '�黹��' . $amount . '���豸' , $email_tpl , explode ( "\r\n" , $data[ 'email' ] ) );
					}
					$flag = true;
				
				}
				$this -> _db -> query ( "COMMIT" );
				return $flag;
			} catch ( Exception $e )
			{
				$this -> _db -> query ( "ROLLBACK" );
				return false;
			}
		
		}
	
	}
	/**
	 * ����������ѯ�黹
	 *
	 */
	function model_save_batch_return ( )
	{
		$flag = false;
		if ( $_POST[ 'id' ] )
		{
			try
			{
				$this -> _db -> query ( "START TRANSACTION" );
				$this -> tbl_name = 'device_return_order';
				$orderid = $this -> create ( array ( 
													
													'userid' => $_POST[ 'userid' ] , 
													'dept_id' => $_SESSION[ 'DEPT_ID' ] , 
													'operatorid' => $_POST[ 'operatorid' ] , 
													'area' => $_POST[ 'area' ] , 
													'date' => time ( ) 
				) );
				if ( $orderid )
				{
					$idarr = explode ( ',' , $_POST[ 'id' ] );
					$amount = 0;
					$this -> tbl_name = 'device_return_order_info';
					foreach ( $idarr as $key => $val )
					{
						$rs = $this -> _db -> get_one ( "
												select 
													a.info_id,b.area,a.list_id,a.typeid 
												from 
													device_borrow_order_info as a
													left join device_info as b on b.id=a.info_id
													where a.id='" . $val . "'" );
						if ( $rs )
						{
							$info = '';
							if ( $val > 0 )
							{
								$amount = $amount + intval ( $_POST[ 'return_num' ][ $val ] );
								$info = $this -> create ( array ( 
																	
																	'orderid' => $orderid , 
																	'info_id' => $val , 
																	'tid' => intval ( $rs['info_id']) , 
																	'amount' => intval ( $_POST[ 'return_num' ][ $val ] ) , 
																	'status' => $_POST[ 'status' ][ $val ] , 
																	'notse' => $_POST[ 'notse' ][ $val ] , 
																	'date' => time ( ) 
								) );
							}
							if ( $info )
							{
								if ( $rs[ 'area' ] != $_POST[ 'area' ] )
								{
									//��¼ת��
									$this -> _db -> query ( "
								insert into
									device_shift_list
									(info_id,list_id,amount,area,to_area,userid,date)
								values
									(
										'" . $_POST[ 'tid' ][ $val ] . "',
										'" . $rs[ 'list_id' ] . "',
										'" . intval ( $_POST[ 'return_num' ][ $val ] ) . "',
										'" . $rs[ 'area' ] . "',
										'" . $_POST[ 'area' ] . "',
										'" . $_POST[ 'operatorid' ] . "',
										'" . time ( ) . "'
									)
								" );
								}
								$this -> _db -> query ( "update device_info set borrow_num=borrow_num-1,state=0,area=" . $_POST[ 'area' ] . " where id=" . $_POST[ 'tid' ][ $val ] );
								$this -> _db -> query ( "update device_borrow_order_info set return_num=return_num+" . intval ( $_POST[ 'return_num' ][ $val ] ) . ",returndate='" . time ( ) . "',status='" . $_POST[ 'status' ][ $val ] . "',notse='" . $_POST[ 'notse' ][ $val ] . "' where id=$val" );
								$this -> _db -> query ( "
						update 
							device_list as a,device_borrow_order_info as b 
						set 
							a.borrow=a.borrow-" . intval ( $_POST[ 'return_num' ][ $val ] ) . ",
							a.surplus=a.surplus+" . intval ( $_POST[ 'return_num' ][ $val ] ) . " 
							where a.id=b.list_id and b.id=$val
							" );
							}
						}
					}
					if ( $amount > 0 )
					{
						$this -> _db -> query ( "update device_return_order set amount=$amount where id=" . $orderid );
						$this -> _db -> query ( "update device_return_order set amount=$amount where id=" . $orderid );
						if ( $_POST[ 'email' ] )
						{
							$email_tpl = file_get_contents ( TPL_DIR . '/device/email-return.htm' );
							foreach ( $_POST as $key => $val )
							{
								$email_tpl = str_replace ( '{' . $key . '}' , $val , $email_tpl );
							}
							$email_tpl = str_replace ( '{operator}' , $_SESSION[ 'USERNAME' ] , $email_tpl );
							$email_tpl = str_replace ( '{list}' , $this -> model_return_order_info_list ( $orderid ) , $email_tpl );
							$email = new includes_class_sendmail ( );
							$email -> send ( $_POST[ 'user_name' ] . '�黹��' . $amount . '���豸' , $email_tpl , explode ( "\r\n" , $_POST[ 'email' ] ) );
						}
						$flag = true;
					}
				}
				$this -> _db -> query ( "COMMIT" );
				return $flag;
			} catch ( Exception $e )
			{
				$this -> _db -> query ( "ROLLBACK" );
				return false;
			}
		
		}
	}
	//********************************��ʾ����**********************************
	/**
	 * ��������
	 *
	 */
	function select_type ( $typeid = '' )
	{
		global $func_limit;
		$this -> tbl_name = 'device_type';
		if ( $_SESSION[ 'USER_ID' ] == 'admin' )
		{
			$where = null;
		} else
		{
			if ( $func_limit[ '��������' ] )
			{
				$where = 'where a.dept_id in (' . ( $func_limit[ '������' ] ? $func_limit[ '������' ] : $_SESSION[ 'DEPT_ID' ] ) . ') and a.area in (' . $func_limit[ '��������' ] . ')';
			} else
			{
				showmsg ( '�Ա�������Ȩ���ʱ�ҳ��' );
			}
		}
		$query = $this -> _db -> query ( "select a.*,b.dept_name,c.name from device_type as a
			left join department as b on b.dept_id=a.dept_id 
			left join area as c on c.id=a.area $where order by a.dept_id,a.area ASC" );
		while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
		{
			if ( $rs[ 'id' ] == $typeid )
			{
				$str .= '<option selected value="' . $rs[ 'id' ] . '">' . $rs[ 'typename' ] . ( $_SESSION[ 'USER_ID' ] == 'admin' ? '--' . $rs[ 'dept_name' ] : '' ) . '--' . $rs[ 'name' ] . '</option>';
			} else
			{
				$str .= '<option value="' . $rs[ 'id' ] . '">' . $rs[ 'typename' ] . ( $_SESSION[ 'USER_ID' ] == 'admin' ? '--' . $rs[ 'dept_name' ] : '' ) . '--' . $rs[ 'name' ] . '</option>';
			}
		}
		return $str;
	}
	/**
	 * ��Ŀ�軹�б�
	 */
	function model_project_list ( )
	{
		global $func_limit;
		$sort = $_GET[ 'sort' ] ? $_GET[ 'sort' ] : 'sortnum-desc';
		$sort = str_replace ( '-' , ' ' , $sort );
		if ( $_SESSION[ 'USER_ID' ] != 'aadmin' )
		{
			$where = "where f.dept_id in (" . ( $func_limit[ '������' ] ? $func_limit[ '������' ] : $_SESSION[ 'DEPT_ID' ] ) . ")";
		}
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "
								select 
									count(distinct a.project_id) as num 
								from 
									device_borrow_order as a 
									left join device_borrow_order_info as e on e.orderid=a.id
									left join device_type as f on f.id=e.typeid
								$where 
								 " );
			$this -> num = $rs[ 'num' ];
		}
		
		if ( $this -> num > 0 )
		{
			$query = $this -> query ( "
				select 
					a.project_id,max(a.id) as sortnum,count(a.project_id) as count,sum(a.amount) as num,b.name as project_name ,b.number,c.return_num,d.user_name,(sum(a.amount) - c.return_num) as surplus
				from 
					device_borrow_order as a
					left join project_info as b on b.id = a.project_id 
					left join (
								select 	sum(a.return_num) as return_num,a.orderid ,b.project_id
								from device_borrow_order_info  as a
								left join device_borrow_order as b on a.orderid=b.id
								WHERE b.project_id IS NOT NULL AND b.project_id<>0
								group by b.project_id
								) as c on c.project_id=a.project_id
					left join user as d on d.user_id=a.manager 
					left join device_borrow_order_info as e on e.orderid=a.id
					left join device_type as f on f.id=e.typeid
				$where
				group by a.project_id	
				order by $sort
				limit $this->start," . pagenum . "
				" );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$project_name_link = thickbox_link ( ( $rs[ 'project_name' ] ? $rs[ 'project_name' ] : '����' ) , 'a' , 'project_id=' . $rs[ 'project_id' ] , '�鿴 ' . ( $rs[ 'project_name' ] ? $rs[ 'project_name' ] : '����' ) . ' �Ľ�����б�' , null , 'project_order_list' , null , 500 );
				$projectno_link = thickbox_link ( ( $rs[ 'number' ] ? $rs[ 'number' ] : '����' ) , 'a' , 'project_id=' . $rs[ 'project_id' ] , '�鿴 ' . ( $rs[ 'project_name' ] ? $rs[ 'project_name' ] : '����' ) . ' �Ľ����豸�б�' , null , 'show_project_list' , null , 500 );
				$str .= '
					<tr>
						<td>' . $rs[ 'sortnum' ] . '</td>
						<td align="left">' . $projectno_link . '</td>
						<td align="left">' . $project_name_link . '</td>
						<td>' . $rs[ 'user_name' ] . '</td>
						<td>' . $rs[ 'count' ] . '</td>
						<td>' . $rs[ 'num' ] . '</td>
						<td>' . $rs[ 'return_num' ] . '</td>
						<td>' . ( $rs[ 'num' ] - $rs[ 'return_num' ] ) . '</td>
					</tr>
				';
			}
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array ( 
											
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num . '&sort=' . $_GET[ 'sort' ] . '&' );
			return $str . '<tr><td colspan="11" style="text-align:center;">' . $showpage -> show ( 6 ) . '</td></tr>';
		}
	}
	/**
	 * ��Ŀ��
	 * @param $project_id
	 */
	function model_project_order_list ( $project_id )
	{
		global $func_limit;
		$sort = $_GET[ 'sort' ] ? $_GET[ 'sort' ] : 'a.id-desc';
		$sort = str_replace ( '-' , ' ' , $sort );
		if ( $_SESSION[ 'USER_ID' ] != 'admin' )
		{
			$where = "where a.dept_id in (" . ( $func_limit[ '������' ] ? $func_limit[ '������' ] : $_SESSION[ 'DEPT_ID' ] ) . ")";
		}
		$where = ( $where ? $where . ' and ' : ' where ' ) . " a.project_id='" . $project_id . "'";
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from device_borrow_order as a $where" );
			$this -> num = $rs[ 'num' ];
		
		}
		if ( $this -> num > 0 )
		{
			$query = $this -> query ( "
			select
				a.*,c.user_name,b.return_num,if(b.return_num >= a.amount,1,0) as areturn
			from
				device_borrow_order as a
				left join (select sum(return_num) as return_num,orderid from device_borrow_order_info group by orderid) as b on b.orderid=a.id
				left join user as c on c.user_id=a.userid
			$where
			order by $sort
			" );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$str .= '
					<tr>
						<td>' . $rs[ 'id' ] . '</td>
						<td>' . $rs[ 'user_name' ] . '</td>
						<td>' . $rs[ 'amount' ] . '</td>
						<td>' . $rs[ 'return_num' ] . '</td>
						<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
						<td>' . ( $rs[ 'confirm' ] == 1 ? '��' : '��' ) . '</td>
						<td>' . ( $rs[ 'return_num' ] >= $rs[ 'amount' ] ? '��' : '��' ) . '</td>
						<td>' . thickbox_link ( '�鿴��ϸ' , 'a' , 'id=' . $rs[ 'id' ] , '�鿴  ' . $rs[ 'id' ] . ' �ŵ��豸�б�' , null , 'show_order_list' , null , 500 ) . '</td>
					</tr>
				';
			}
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array ( 
											
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num . '&project_id=' . $_GET[ 'project_id' ] . '&sort=' . $_GET[ 'sort' ] );
			return $str . '<tr><td colspan="11" style="text-align:center;">' . $showpage -> show ( 6 ) . '</td></tr>';
		}
	}
	/**
	 * ��������б�
	 */
	function model_showlist ( )
	{
		global $func_limit;
		$sort = $_GET[ 'sort' ] ? $_GET[ 'sort' ] : 'a.id-desc';
		$sort = str_replace ( '-' , ' ' , $sort );
		if ( $_SESSION[ 'USER_ID' ] != 'aadmin' )
		{
			$where = "where g.dept_id in (" . ( $func_limit[ '������' ] ? $func_limit[ '������' ] : $_SESSION[ 'DEPT_ID' ] ) . ")";
		}
		if ( $_GET[ 'start_date' ] && $_GET[ 'end_date' ] )
		{
			$where .= $where ? ' and a.date>=' . strtotime ( $_GET[ 'start_date' ] ) . ' and a.date<=' . strtotime ( $_GET[ 'end_date' ] . ' 23:59:59' ) : 'where a.date>=' . strtotime ( $_GET[ 'start_date' ] ) . ' and a.date<=' . strtotime ( $_GET[ 'end_date' ] . ' 23:59:59' );
		}
		if ( $_GET[ 'status' ] != 3 )
		{
			$where .= $where ? ' and a.archive!=1' : 'where a.archive!=1';
		} else
		{
			$where .= $where ? ' and a.archive=1' : 'where a.archive=1';
		}
		if ( $_GET[ 'project_id' ] )
		{
			$where .= $where ? ' and a.project_id=' . $_GET[ 'project_id' ] : 'a.project_id=' . $_GET[ 'project_id' ];
		}
		if ( $_GET[ 'userid' ] )
		{
			$where .= $where ? " and a.userid='" . $_GET[ 'userid' ] . "'" : " a.userid='" . $_GET[ 'userid' ] . "'";
		}
		if (  ! $this -> num )
		{
			if ( $_GET[ 'status' ] == 1 )
			{
				$rs = $this -> _db -> get_one ( "
				select 
					count(0) as num 
				from 
					(
						select 
							a.amount 
						from 
							device_borrow_order as a 
							left join device_borrow_order_info as b on b.orderid=a.id 
							left join device_type as g on g.id=b.typeid
							$where 
							group by a.id having a.amount>sum(b.return_num)
						) as c" );
			
			} elseif ( $_GET[ 'status' ] == 2 )
			{
				$rs = $this -> _db -> get_one ( "
											select 
												count(0) as num 
											from 
												(select 
													a.amount 
												from 
													device_borrow_order as a 
													left join device_borrow_order_info as b on b.orderid=a.id 
													left join device_type as g on g.id=b.typeid
												$where 
												group by a.id having a.amount<=sum(b.return_num)) as c" );
			} else
			{
				$rs = $this -> _db -> get_one ( "
										select 
											count(distinct a.id) as num 
										from 
											device_borrow_order as a 
											left join device_borrow_order_info as b on b.orderid=a.id 
											left join device_type as g on g.id=b.typeid 
										$where 
										" );
			}
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num > 0 )
		{
			$sql = "
		select 
			a.*,b.user_name,b1.user_name as operatorname,b2.user_name as managername ,c.name as projectname,c.number,d.dept_name,sum(e.return_num) as return_num,if(return_num >=a.amount,1,0) as areturn,e.returndate
		from 
			device_borrow_order as a 
		left join user as b on b.user_id=a.userid 
		left join user as b1 on b1.user_id=a.operatorid 
		left join user as b2 on b2.user_id=a.manager 
		left join project_info as c on c.id = a.project_id 
		left join department as d on d.dept_id=a.dept_id 
		left join device_borrow_order_info as e on e.orderid=a.id 
		left join device_type as g on g.id=e.typeid
		$where
		group by a.id
		";
			if ( $_GET[ 'status' ] == 1 )
			{
				$sql .= " having a.amount>sum(e.return_num) ";
			} elseif ( $_GET[ 'status' ] == 2 )
			{
				$sql .= " having a.amount<=sum(e.return_num) ";
			}
			$sql .= "order by $sort limit $this->start," . pagenum;
			$query = $this -> _db -> query ( $sql );
			while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
			{
				$totals='';
				if($rs[ 'id' ]){
				  $totals=$this->model_return_order_count($rs[ 'id' ]);	
				}
				
				$str .= '
						<tr ' . ( ( $rs[ 'return_num' ] >= $rs[ 'amount' ] ) ? 'style="background:#EEEEEE;"' : '' ) . '>
							<td>' . $rs[ 'id' ] . '</td>
							<td><a href="?model=device_borrow&action=show_project_list&project_id=' . $rs[ 'project_id' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=650" class="thickbox" title="�鿴��' . $rs[ 'projectname' ] . '�����õ������豸">' . $rs[ 'projectname' ] . '</a></td>
							<td><a href="?model=device_borrow&action=show_project_list&project_id=' . $rs[ 'project_id' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=650" class="thickbox" title="�鿴��' . $rs[ 'projectname' ] . '�����õ������豸">' . $rs[ 'number' ] . '</a></td>
							<td>' . $rs[ 'managername' ] . '</td>
							<td>' . $rs[ 'operatorname' ] . '</td>
							<td>' . $rs[ 'user_name' ] . '</td>
							<td>' . $rs[ 'dept_name' ] . '</td>
							<td>' . $rs[ 'amount' ] . '</td>
							<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
							<td>' . ($rs[ 'returndate' ]?date ( 'Y-m-d' , $rs[ 'returndate' ] ):'') . '</td>
							<td>' . ( $totals ? '��ȷ������' . $totals : ( ( abs ( ( time ( ) - $rs[ 'date' ] ) / 86400 ) > 10 ) ? '��' : ( $rs[ 'return_num' ] >= $rs[ 'amount' ] ? '�ѳ���':'<span>��</span>' ) ) ) . '</td>
							<td>' . ( ( $rs[ 'return_num' ] >= $rs[ 'amount' ] ) ? '��' : '��' ) . '</td>
							<td> <span ondblclick="edit(' . $rs[ 'id' ] . ');" id="notes_' . $rs[ 'id' ] . '" style="width:100px;color:#000000" title="' . $rs[ 'notes' ] . '">' . $rs[ 'notes' ] . '</span></td>
							<td align="left"><a href="?model=device_borrow&action=show_order_list&id=' . $rs[ 'id' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=650" class="thickbox" title="�鿴 [' . $rs[ 'id' ] . '] ��ϸ��Ϣ">�鿴��ϸ</a>' . ( $rs[ 'return_num' ] >= $rs[ 'amount' ] ? ' | <a href="?model=' . $_GET[ 'model' ] . '&action=archive&id=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=150&width=300" class="thickbox" title="�赥�鵵">�鵵</a>' : '' ) . '</td>
						</tr>
						';
			}
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array ( 
											
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num . '&sort=' . $_GET[ 'sort' ] . '&' );
			return $str . '<tr><td colspan="11" style="text-align:center;">' . $showpage -> show ( 6 ) . '</td></tr>';
		
		} else
		{
			return false;
		}
	}
	/**
	 * �黹������ϸ
	 * @param unknown_type $orderid
	 */
	function model_return_order_count ( $orderid )
	{
		$query = $this -> query ( "
								select sum(amount) as total,orderid from device_borrow_order_info where claim=1 AND orderid=$orderid group by orderid
							" );
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$cost=$rs['total'];
		}
		return $cost;
	}
	/**
	 * ��������豸�б�
	 *
	 * @return unknown
	 */
	function model_show_order_list ( )
	{
		if ( intval ( $_GET[ 'id' ] ) )
		{
			global $func_limit;
			$sort = $_GET[ 'sort' ] ? $_GET[ 'sort' ] : 'a.id-desc';
			$sort = str_replace ( '-' , ' ' , $sort );
			if ( $_SESSION[ 'USER_ID' ] != 'admin' )
			{
				$where = "where a.orderid=" . $_GET[ 'id' ] . " and a.dept_id in (" . ( $func_limit[ '������' ] ? $func_limit[ '������' ] : $_SESSION[ 'DEPT_ID' ] ) . ")";
			} else
			{
				$where = "where a.orderid=" . $_GET[ 'id' ];
			}
			if (  ! $this -> num )
			{
				$rs = $this -> _db -> get_one ( "
					select 
						count(0) as num 
					from 
						device_borrow_order_info where orderid=" . $_GET[ 'id' ] );
				$this -> num = $rs[ 'num' ];
			}
			if ( $this -> num > 0 )
			{
				$query = $this -> _db -> query ( "
				select 
					a.*,b.typename,c.device_name,d.fitting,d.id as tid,d.dpcoding,d.state,f.user_name
				from 
					device_borrow_order_info as a
					left join device_type as b on b.id=a.typeid 
					left join device_list as c on c.id=a.list_id 
					left join device_info as d on d.id=a.info_id 
					left join device_borrow_order as e on e.id=a.orderid 
					left join user as f on f.user_id=e.userid
					where a.orderid=" . $_GET[ 'id' ] . "
					order by $sort 
					limit $this->start," . pagenum );
				while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
				{
					if ( $rs[ 'return_num' ] >= $rs[ 'amount' ] )
					{
						$disabled = 'disabled';
					} else
					{
						$disabled = '';
					}
					$str .= '
				<tr>
					<td><input type="checkbox" ' . $disabled . ' name="id[]" value="' . $rs[ 'id' ] . '" />
					<td>' . $rs[ 'tid' ] . '</td>
					<td>' . $rs[ 'typename' ] . '</td>
					<td>' . $rs[ 'device_name' ] . '</td>
					<td>' . $rs[ 'amount' ] . '</td>
					<td>' . $rs[ 'return_num' ] . '</td>
					<td>' . $rs[ 'fitting' ] . '</td>
					<td>' . $rs[ 'dpcoding' ] . '</td>
					<td>' . $rs[ 'user_name' ] . '</td>
					<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
					<td id="state_' . $rs[ 'id' ] . '">' . ( $rs[ 'claim' ] == 1 ? '��ȷ��' : '<span>��ȷ��</span>' ) . '</td>
					<td>' . date ( 'Y-m-d' , $rs[ 'targetdate' ] ) . '</td>
					<td>' . ( $rs[ 'returndate' ] ? date ( 'Y-m-d' , $rs[ 'returndate' ] ) : '��δ�黹' ) . '</td>
					<td>' . ( $rs[ 'status' ] ? $rs[ 'status' ] : '' ) . '</td>
					<td>' . $rs[ 'notse' ] . '</td>
				</tr>
				';
				}
				$showpage = new includes_class_page ( );
				$showpage -> show_page ( array ( 
												
												'total' => $this -> num , 
												'perpage' => pagenum 
				) );
				$showpage -> _set_url ( 'num=' . $this -> num . '&id=' . $_GET[ 'id' ] . '&sort=' . $_GET[ 'sort' ] );
				return $str . '<tr><td colspan="15" style="text-align:center;">' . $showpage -> show ( 6 ) . '</td></tr>';
			} else
			{
				return false;
			}
		}
	}
	/**
	 * ���������ϸ
	 * @param unknown_type $orderid
	 */
	function model_borrow_order_info_list ( $orderid , $checkbox = false , $confirm = false )
	{
		$stock = new model_device_stock ( );
		$query = $this -> query ( "
								select 
									distinct(a.typeid) as tid,b.typename 
								from 
									device_borrow_order_info as a
									left join device_type as b on b.id=a.typeid
								where 
									orderid=$orderid
							" );
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '<table class="table" border="1" width="100%" cellspacing="0" cellpadding="0" id="type_' . $rs[ 'tid' ] . '">
						<tr bgcolor="#D3E5FA">
							<td colspan="30">' . $rs[ 'typename' ] . '</td>
						</tr>
						<tr class="tableheader">
							' . ( $checkbox == true ? '<td><input type="checkbox" onclick="set_all(this.checked,' . $rs[ 'tid' ] . ')"</td>' : '' ) . '
							<td>���</td>
							<td>�豸����</td>
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> get_fixed_field_name ( $rs[ 'tid' ] ) ) . '
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> model_show_field_name ( $rs[ 'tid' ] ) ) . '
							<td>�������</td>
							<td>��������</td>
							<td>�黹����</td>
							<td>������</td>
							<td>ȷ��״̬</td>
							<td>��������</td>
							<td>Ԥ�ƹ黹����</td>
							<td>ʵ�ʹ黹����</td>
							<td>��ע</td>
						</tr>
						' . $this -> model_get_borrow_order_info ( $orderid , $rs[ 'tid' ] , $stock , $checkbox , $confirm ) . '
					</table><br />';
		}
		return $str;
	}
	
	/**
	 * �黹������ϸ
	 * @param unknown_type $orderid
	 */
	function model_return_order_info_list ( $orderid )
	{
		$stock = new model_device_stock ( );
		$query = $this -> query ( "
								select 
									distinct(b.typeid) as typeid,d.typename 
								from 
									device_return_order_info as a
									left join device_info as c on c.id=a.tid
									left join device_list as b on b.id=c.list_id
									left join device_type as d on d.id=b.typeid
								where 
									orderid=$orderid
							" );
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '<table class="table" border="1" width="100%" cellspacing="0" cellpadding="0">
						<tr bgcolor="#D3E5FA">
							<td colspan="20">' . $rs[ 'typename' ] . '</td>
						</tr>
						<tr class="tableheader">
							<td>���</td>
							<td>�豸����</td>
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> get_fixed_field_name ( $rs[ 'typeid' ] ) ) . '
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> model_show_field_name ( $rs[ 'typeid' ] ) ) . '
							<td>����</td>
							<td>ȷ��״̬</td>
							<td>��������</td>
							<td>Ԥ�ƹ黹����</td>
							<td>ʵ�ʹ黹����</td>
							<td>�豸״��</td>
							<td>�黹��ע</td>
						</tr>
						' . $this -> model_get_return_order_info ( $orderid , $rs[ 'typeid' ] , $stock ) . '
					</table><br />';
		}
		return $str;
	}
	/**
	 * ÿ�ݽ����������б�
	 * @param $orderid
	 * @param $typeid
	 * @param $stock
	 */
	function model_get_borrow_order_info ( $orderid , $typeid , $stock = null , $checkbox = false , $confirm )
	{
		$stock = $stock ? $stock : new model_device_stock ( );
		$query = $this -> query ( "
								select
									a.id as orderid,a.amount as a_num,a.return_num,a.targetdate,a.returndate,a.claim,a.date as Wdate,a.notse as ns,b.*,c.device_name,d.confirm,e.user_name
								from
									device_borrow_order_info as a
									left join device_info as b on b.id=a.info_id
									left join device_list as c on c.id=b.list_id
									left join device_borrow_order as d on d.id=a.orderid
									left join user as e on e.user_id=d.userid
								where
									a.orderid=$orderid
									and a.typeid = $typeid
		" );
		$rs = $stock -> get_fixed_field_name ( $typeid , false );
		$field = $stock -> model_show_field_name ( $typeid , false );
		while ( ( $row = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '<tr>';
			$str .= $checkbox ? '<td><input type="checkbox" ' . ( ( ( $row[ 'a_num' ] == $row[ 'return_num' ] ) || ( $confirm && ( $row[ 'confirm' ] || $row[ 'claim' ] == 1 ) ) ) ? 'disabled' : '' ) . ' name="id[]" value="' . $row[ 'orderid' ] . '" /></td>' : '';
			$str .= '<td>' . $row[ 'id' ] . '</td>';
			$str .= '<td>' . $row[ 'device_name' ] . '</td>';
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
								$str .= '<td>��' . number_format ( $row[ 'price' ] , 2 ) . '</td>';
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
				$field_data = $stock -> get_field_content ( $row[ 'id' ] );
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
			$str .= '<td>' . $row[ 'amount' ] . '</td>';
			$str .= '<td>' . $row[ 'a_num' ] . '</td>';
			$str .= '<td>' . $row[ 'return_num' ] . '</td>';
			$str .= '<td>' . $row[ 'user_name' ] . '</td>';
			$str .= '<td>' . ( $row[ 'claim' ] == 1 ? '��ȷ��' : ( $row[ 'return_num' ] >= $row[ 'a_num' ] ? '�ѳ���' : '<span>��ȷ��</span>' ) ) . '</td>';
			$str .= '<td>' . date ( 'Y-m-d' , $row[ 'Wdate' ] ) . '</td>';
			$str .= '<td>' . date ( 'Y-m-d' , $row[ 'targetdate' ] ) . '</td>';
			$str .= '<td>' . ( $row[ 'returndate' ] ? date ( 'Y-m-d' , $row[ 'returndate' ] ) : ( $row[ 'return_num' ]=='0' ?'<span>��δ�黹</span>' : '<span>���ݹ黹('.$row[ 'return_num' ].')</span>' ) ) . '</td>';
			$str .= '<td>' . $row[ 'ns' ] . '</td>';
			$str .= '</tr>';
		}
		
		return $str;
	}
	
	/**
	 * ÿ�ݹ黹��������б�
	 * @param $orderid
	 * @param $typeid
	 * @param $stock
	 */
	function model_get_return_order_info ( $orderid , $typeid , $stock = null )
	{
		$stock = $stock ? $stock : new model_device_stock ( );
		$query = $this -> query ( "
								select
									a.amount as a_num ,a.notse,a.status,b.*,c.device_name,d.date as Wdate,d.targetdate
								from
									device_return_order_info as a
									left join device_info as b on b.id=a.tid
									left join device_list as c on c.id=b.list_id
									left join device_borrow_order_info as d on d.id=a.info_id
								where
									a.orderid=$orderid
									and c.typeid = $typeid
		" );
		$rs = $stock -> get_fixed_field_name ( $typeid , false );
		$field = $stock -> model_show_field_name ( $typeid , false );
		while ( ( $row = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '<tr>';
			$str .= '<td>' . $row[ 'id' ] . '</td>';
			$str .= '<td>' . $row[ 'device_name' ] . '</td>';
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
								$str .= '<td>��' . number_format ( $row[ 'price' ] , 2 ) . '</td>';
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
				$field_data = $stock -> get_field_content ( $row[ 'id' ] );
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
			$str .= '<td>' . $row[ 'a_num' ] . '</td>';
			$str .= '<td>�ѹ黹</td>';
			$str .= '<td>' . date ( 'Y-m-d' , $row[ 'Wdate' ] ) . '</td>';
			$str .= '<td>' . date ( 'Y-m-d' , $row[ 'targetdate' ] ) . '</td>';
			$str .= '<td>' . date ( 'Y-m-d' ) . '</td>';
			$str .= '<td>' . $row[ 'status' ] . '</td>';
			$str .= '<td>' . $row[ 'notse' ] . '</td>';
			$str .= '</tr>';
		}
		
		return $str;
	}
	
	/**
	 * ����豸�б�
	 *
	 * @return unknown
	 */
	function model_borrow_info_list ( $checkbox = true , $export = false )
	{
		global $func_limit;
		$data = array ();
		$data[ 'deptId' ] = $_GET[ 'deptId' ] ? str_replace ( 'undefined' , '' , $_GET[ 'deptId' ] ) : $_POST[ 'deptId' ];
		$data[ 'typeid' ] = $_GET[ 'typeid' ] ? str_replace ( 'undefined' , '' , $_GET[ 'typeid' ] ) : $_POST[ 'typeid' ];
		$data[ 'userid' ] = $_GET[ 'userid' ] ? $_GET[ 'userid' ] : $_POST[ 'userid' ];
		$data[ 'username' ] = $_GET[ 'username' ] ? $_GET[ 'username' ] : $_POST[ 'username' ];
		$data[ 'status' ] = $_GET[ 'status' ] ? $_GET[ 'status' ] : $_POST[ 'status' ];
		$data[ 'start_date' ] = $_GET[ 'start_date' ] ? $_GET[ 'start_date' ] : $_POST[ 'start_date' ];
		$data[ 'end_date' ] = $_GET[ 'end_date' ] ? $_GET[ 'end_date' ] : $_POST[ 'end_date' ];
		$data[ 'project_id' ] = $_GET[ 'project_id' ] && $_GET[ 'project_name' ] ? $_GET[ 'project_id' ] : ( $_POST[ 'project_id' ] && $_POST[ 'project_name' ] ? $_POST[ 'project_id' ] : '' );
		
		$where .= $data[ 'deptId' ]?(" and c.dept_id='".$data[ 'deptId' ]."'" ): ($_SESSION[ 'USER_ID' ] != 'admin' ? " and c.dept_id in (" . ( $func_limit[ '������' ] ? $func_limit[ '������' ] : $_SESSION[ 'DEPT_ID' ] ) . ")" : '');
		$where .= $data[ 'typeid' ] ? ' and a.typeid=' . $data[ 'typeid' ] : '';
		$where .= $data[ 'userid' ] ? " and b.userid='" . $data[ 'userid' ] . "'" : '';
		$where .= $data[ 'status' ] == '1' ? " and a.amount<=a.return_num" : ( $data[ 'status' ] == '2' ? " and a.amount>a.return_num" : '' );
		$where .= $data[ 'start_date' ] ? " and a.date >='" . strtotime ( $data[ 'start_date' ] ) . "'" : '';
		$where .= $data[ 'end_date' ] ? " and a.date <='" . strtotime ( $data[ 'end_date' ] . ' 23:59:59' ) . "'" : '';
		$where .= $data[ 'project_id' ] ? " and b.project_id='" . $data[ 'project_id' ] . "'" : '';
		$where = $where ? 'where 1=1 ' . $where : ' where 1=1 ';
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "
									select 
										count(distinct(a.typeid)) as num 
									from 
										device_borrow_order_info as a
										left join device_borrow_order as b on b.id=a.orderid
										left join device_type as c on c.id=a.typeid
									$where
										" );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num )
		{
			$stock = new model_device_stock ( );
			$query = $this -> query ( "
									select
										a.typeid,c.typename
									from
										device_borrow_order_info as a
										left join device_borrow_order as b on b.id=a.orderid
										left join device_type as c on c.id=a.typeid
									$where
									group by a.typeid
										order by max(a.id) desc
										" . (  ! $export ? "limit " . $this -> start . " ,10" : "" ) );
			$data = array ();
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				if ( $export )
				{
					$fixed_arr = $stock -> get_fixed_field_name ( $rs[ 'typeid' ] , false );
					$field_arr = $stock -> model_show_field_name ( $rs[ 'typeid' ] , false );
					$title = array ( 
									'���' , 
									'�豸����' 
					);
					$temp = array ();
					foreach ( $fixed_arr as $key => $val )
					{
						if (  ! $val )
						{
							switch ( $key )
							{
								case '_coding' :
									$temp[ ] = '������ ';
									break;
								case '_dpcoding' :
									$temp[ ] = '���ű���';
									break;
								case '_fitting' :
									$temp[ ] = '���';
									break;
								case '_price' :
									$temp[ ] = '����';
									break;
								case '_notes' :
									$temp[ ] = '��ע';
									break;
							}
						}
					}
					$title = array_merge ( $title , $temp );
					if ( $field_arr )
					{
						foreach ( $field_arr as $row )
						{
							$title[ ] = $row[ 'fname' ];
						}
					}
					
					$title[ ] = 'ʹ������';
					$title[ ] = '�������';
					$title[ ] = 'ʹ����';
					$title[ ] = 'ʹ����Ŀ';
					$title[ ] = 'ȷ��״̬';
					$title[ ] = '��������';
					$title[ ] = 'Ԥ�ƹ黹����';
					$title[ ] = 'ʵ�ʹ黹����';
					$data[ 'sheet' ][ ] = $rs[ 'typename' ];
					$data[ 'column' ][ ] = $title;
					$data[ 'row' ][ ] = $this -> model_get_borrow_info ( $where , $rs[ 'typeid' ] , $stock , false , false );
				} else
				{
					$str .= '<table class="table" border="1" width="98%" cellspacing="0" cellpadding="0" align="center" style="position:relative;" id="type_' . $rs[ 'typeid' ] . '">
							<tr bgcolor="#D3E5FA">
								<td colspan="30">
									<a href="javascript:close(' . $rs[ 'typeid' ] . ')">' . $rs[ 'typename' ] . '</a> 
									<span style="position:absolute;right:5px;line-height:20px;">
										<a href="?model=' . $_GET[ 'model' ] . '&action=show_borrow_type_info&typeid=' . $rs[ 'typeid' ] . '&typename=' . $rs[ 'typename' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=650" class="thickbox" title="' . $rs[ 'typename' ] . ' �Ľ���豸�б�"><img src="images/menu/more5.png"  border="0" /></a>
										<a href="javascript:close(' . $rs[ 'typeid' ] . ')" id="a_' . $rs[ 'typeid' ] . '"><img src="images/work/sub.png" border="0" /></a>
									</span>
								</td>
							<tbody id="close_' . $rs[ 'typeid' ] . '">
							<tr class="tableheader">
								' . ( $checkbox == true ? '<td><input type="checkbox" onclick="set_all(this.checked,' . $rs[ 'typeid' ] . ')"</td>' : '' ) . '
								<td>���</td>
								<td>�豸����</td>
								' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> get_fixed_field_name ( $rs[ 'typeid' ] ) ) . '
								' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> model_show_field_name ( $rs[ 'typeid' ] ) ) . '
								
								<td>��������</td>
								<td>�������</td>
								<td>������</td>
								<td>ʹ����Ŀ</td>
								<td>ȷ��״̬</td>
								<td>��������</td>
								<td>Ԥ�ƹ黹����</td>
								<td>ʵ�ʹ黹����</td>
							</tr>
							' . $this -> model_get_borrow_info ( $where , $rs[ 'typeid' ] , $stock , $checkbox ) . '
							</tbody>
						</table><br />';
				}
			}
			if (  ! $export )
			{
				if ( $this -> num > pagenum )
				{
					$showpage = new includes_class_page ( );
					$showpage -> show_page ( array ( 
													
													'total' => $this -> num , 
													'perpage' => pagenum 
					) );
					$showpage -> _set_url ( 'num=' . $this -> num . '&sort=' . $_GET[ 'sort' ] . '&tid=' . $data[ 'tid' ] . '&typeid=' . $data[ 'typeid' ] . '&list_id=' . $data[ 'list_id' ] . '&start_date=' . $data[ 'start_date' ] . '&end_date=' . $data[ 'end_date' ] . '&status=' . $data[ 'status' ] . '&userid=' . $data[ 'userid' ] . '&username=' . $data[ 'username' ] );
					$pagehtml = '<tr>
								<td colspan="15" style="text-align:center;height:50px;">' . $showpage -> show ( 6 ) . '</td>
							</tr>';
				}
				$str .= '<table width="98%" border="0" cellpadding="0" cellspacing="0" align="center" id="mytbale">
							' . $pagehtml . '
							<tr>
								<td colspan="15" style="text-align:center;height:50px;"><input type="submit" value=" �����黹 " /></td>
							</tr>
						</table>
								';
			}
		}
		return $export ? $data : $str;
	}
	/**
	 * EXCEL��ͷ
	 * @param $typeid
	 */
	function format_title ( $typeid )
	{
		$stock = new model_device_stock ( );
		$fixed_arr = $stock -> get_fixed_field_name ( $typeid , false );
		$field_arr = $stock -> model_show_field_name ( $typeid , false );
		$title = array ( 
						'���' , 
						'�豸����' 
		);
		$temp = array ();
		foreach ( $fixed_arr as $key => $val )
		{
			if (  ! $val )
			{
				switch ( $key )
				{
					case '_coding' :
						$temp[ ] = '������ ';
						break;
					case '_dpcoding' :
						$temp[ ] = '���ű���';
						break;
					case '_fitting' :
						$temp[ ] = '���';
						break;
					case '_price' :
						$temp[ ] = '����';
						break;
					case '_notes' :
						$temp[ ] = '��ע';
						break;
				}
			}
		}
		$title = array_merge ( $title , $temp );
		if ( $field_arr )
		{
			foreach ( $field_arr as $row )
			{
				$title[ ] = $row[ 'fname' ];
			}
		}
		$title[ ] = '����';
		$title[ ] = 'ʹ����';
		$title[ ] = 'ʹ����Ŀ';
		$title[ ] = 'ȷ��״̬';
		$title[ ] = '��������';
		$title[ ] = 'Ԥ�ƹ黹����';
		$title[ ] = 'ʵ�ʹ黹����';
		return $title;
	}
	/**
	 * ������豸
	 * @param $typeid
	 * @param $checkbox
	 */
	function model_borrow_type_info ( $typeid , $checkbox = false , $html = true , $export = false )
	{
		$list_id = $_GET[ 'list_id' ] ? $_GET[ 'list_id' ] : $_POST[ 'list_id' ];
		$status = $_GET[ 'status' ] ? $_GET[ 'status' ] : $_POST[ 'status' ];
		$userid = $_GET[ 'userid' ] ? $_GET[ 'userid' ] : $_POST[ 'userid' ];
		$username = $_GET[ 'username' ] ? $_GET[ 'username' ] : $_POST[ 'username' ];
		$start_date = $_GET[ 'start_date' ] ? $_GET[ 'start_date' ] : $_POST[ 'start_date' ];
		$end_date = $_GET[ 'end_date' ] ? $_GET[ 'end_date' ] : $_POST[ 'end_date' ];
		
		$where .= $list_id ? " and a.list_id=" . $list_id : "";
		$where .= $userid ? " and b.userid='" . $userid . "'" : '';
		$where .= $status == 1 ? " and a.amount<=return_num" : ( $status == 2 ? " and a.amount>return_num" : '' );
		$where .= $start_date ? " and a.date >='" . strtotime ( $start_date ) . "'" : '';
		$where .= $end_date ? " and a.date <='" . strtotime ( $end_date . ' 23:59:59' ) . "'" : '';
		$where = $where ? "where a.typeid=$typeid " . $where : "where a.typeid=$typeid";
		
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "
									select 
										count(0) as num 
									from 
										device_borrow_order_info as a 
										left join device_borrow_order as b on b.id=a.orderid 
									$where
									" );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num )
		{
			$stock = new model_device_stock ( );
			$rs = $stock -> get_fixed_field_name ( $typeid , false );
			$field = $stock -> model_show_field_name ( $typeid , false );
			$query = $this -> query ( "
									select
										c.*,a.id as orderid,a.amount as a_num ,a.targetdate,a.returndate,a.date as Wdate,b.confirm,d.device_name,e.user_name,f.name as project_name
									from
										device_borrow_order_info as a
										left join device_borrow_order as b on b.id=a.orderid
										left join device_info as c on c.id=a.info_id
										left join device_list as d on d.id=a.list_id
										left join user as e on e.user_id=b.userid
										left join project_info as f on f.id=b.project_id
									$where
									order by a.id desc
									" . (  ! $export ? "limit " . $this -> start . "," . pagenum : "" ) );
			$data = array ();
			while ( ( $row = $this -> fetch_array ( $query ) ) != false )
			{
				if ( $html )
				{
					$str .= '<tr>';
					$str .= $checkbox ? '<td><input type="checkbox" ' . ( $row[ 'returndate' ] ? 'disabled' : '' ) . ' name="id[]" value="' . $row[ 'orderid' ] . '" /></td>' : '';
					$str .= '<td>' . $row[ 'id' ] . '</td>';
					$str .= '<td>' . $row[ 'device_name' ] . '</td>';
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
										$str .= '<td>��' . number_format ( $row[ 'price' ] , 2 ) . '</td>';
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
						$field_data = $stock -> get_field_content ( $row[ 'id' ] );
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
					$str .= '<td>' . $row[ 'a_num' ] . '</td>';
					$str .= '<td>' . $row[ 'amount' ] . '</td>';
					$str .= '<td>' . $row[ 'user_name' ] . '</td>';
					$str .= '<td>' . ( $row[ 'project_name' ] ? $row[ 'project_name' ] : '����ʹ��' ) . '</td>';
					$str .= '<td>' . ( $row[ 'returndate' ] ? '�ѹ黹' : ( $row[ 'confirm' ] == 1 || $row[ 'state' ] == 1 ? '��ȷ��' : '<span>��ȷ��</span>' ) ) . '</td>';
					$str .= '<td>' . date ( 'Y-m-d' , $row[ 'Wdate' ] ) . '</td>';
					$str .= '<td>' . date ( 'Y-m-d' , $row[ 'targetdate' ] ) . '</td>';
					$str .= '<td>' . ( $row[ 'returndate' ] ? date ( 'Y-m-d' , $row[ 'returndate' ] ) : '��δ�黹' ) . '</td>';
					$str .= '</tr>';
				} else
				{
					$temp = array ();
					$temp[ ] = $row[ 'id' ];
					$temp[ ] = $row[ 'device_name' ];
					if ( $rs )
					{
						foreach ( $rs as $key => $val )
						{
							if (  ! $val )
							{
								switch ( $key )
								{
									case '_coding' :
										$temp[ ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
										break;
									case '_dpcoding' :
										$temp[ ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
										$str .= '<td>' . $row[ 'dpcoding' ] . '</td>';
										break;
									case '_fitting' :
										$temp[ ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
										break;
									case '_price' :
										$temp[ ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
										break;
									case '_notes' :
										$temp[ ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
										break;
								}
							}
						}
					}
					if ( $field )
					{
						$field_data = $stock -> get_field_content ( $row[ 'id' ] );
						foreach ( $field as $key => $val )
						{
							$temp[ ] = $field_data[ $val[ 'id' ] ] ? $field_data[ $val[ 'id' ] ] : '--';
						}
						unset ( $field_data );
					}
					$temp[ ] = $row[ 'a_num' ];
					$temp[ ] = $row[ 'amount' ];
					$temp[ ] = $row[ 'user_name' ];
					$temp[ ] = $row[ 'project_name' ] ? $row[ 'project_name' ] : '����ʹ��';
					$temp[ ] = $row[ 'confirm' ] == 1 || $row[ 'state' ] == 1 ? '��ȷ��' : '<span>��ȷ��</span>';
					$temp[ ] = date ( 'Y-m-d' , $row[ 'Wdate' ] );
					$temp[ ] = date ( 'Y-m-d' , $row[ 'targetdate' ] );
					$temp[ ] = $row[ 'returndate' ] ? date ( 'Y-m-d' , $row[ 'returndate' ] ) : '��δ�黹';
					$data[ ] = $temp;
				}
			}
			if (  ! $html )
				return $data;
		}
		if ( $this -> num > pagenum )
		{
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array ( 
											
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num . '&typeid=' . $typeid . '&list_id=' . $list_id . '&userid=' . $userid . '&status=' . $status . '&start_date=' . $start_date . '&end_date=' . $end_date . '&typename=' . urlencode ( $_GET[ 'typename' ] ) );
			$pagehtml = '<tr>
							<td colspan="20" style="text-align:center;height:50px;">' . $showpage -> show ( 6 ) . '</td>
						</tr>';
		}
		return $str . $pagehtml;
	}
	/**
	 * ���豸������б�
	 * @param $typeid
	 * @param $stock
	 * @param $checkbox
	 */
	function model_get_borrow_info ( $where , $typeid , $stock , $checkbox = false , $html = true )
	{
		$stock = $stock ? $stock : new model_device_stock ( );
		$query = $this -> query ( "
								select
									a.id as orderid,a.amount as a_num ,a.targetdate,a.returndate,a.claim,a.date as Wdate,a.notse as ns,b.confirm,e.*,c.device_name,d.user_name,f.name as project_name
								from
									device_borrow_order_info as a
									left join device_borrow_order as b on b.id=a.orderid
									left join device_info as e on e.id=a.info_id
									left join device_list as c on c.id=e.list_id
									left join user as d on d.user_id=b.userid
									left join project_info as f on f.id=b.project_id
								$where
									and a.typeid = $typeid
								order by a.id desc
								" . ( $html ? "limit 10" : "" ) . "
		" );
		$rs = $stock -> get_fixed_field_name ( $typeid , false );
		$field = $stock -> model_show_field_name ( $typeid , false );
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
					$field_data = $stock -> get_field_content ( $row[ 'id' ] );
					foreach ( $field as $key => $val )
					{
						$data[ $i ][ ] = $field_data[ $val[ 'id' ] ] ? $field_data[ $val[ 'id' ] ] : '--';
					}
					unset ( $field_data );
				}
				$data[ $i ][ ] = $row[ 'a_num' ];
				$data[ $i ][ ] = $row[ 'amount' ];
				$data[ $i ][ ] = $row[ 'user_name' ];
				$data[ $i ][ ] = $row[ 'project_name' ] ? $row[ 'project_name' ] : '����ʹ��';
				$data[ $i ][ ] = $row[ 'returndate' ] ? '�ѹ黹' : ( $row[ 'confirm' ] == 1 || $row[ 'claim' ] == 1 ? '��ȷ��' : '<span>��ȷ��</span>' );
				$data[ $i ][ ] = date ( 'Y-m-d' , $row[ 'Wdate' ] );
				$data[ $i ][ ] = date ( 'Y-m-d' , $row[ 'targetdate' ] );
				$data[ $i ][ ] = $row[ 'returndate' ] ? date ( 'Y-m-d' , $row[ 'returndate' ] ) : '��δ�黹';
				$i ++ ;
			} else
			{
				$str .= '<tr>';
				$str .= $checkbox ? '<td><input type="checkbox" ' . ( $row[ 'returndate' ] ? 'disabled' : '' ) . ' name="id[]" value="' . $row[ 'orderid' ] . '" /></td>' : '';
				$str .= '<td>' . $row[ 'id' ] . '</td>';
				$str .= '<td>' . $row[ 'device_name' ] . '</td>';
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
									$str .= '<td>��' . number_format ( $row[ 'price' ] , 2 ) . '</td>';
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
					$field_data = $stock -> get_field_content ( $row[ 'id' ] );
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
				$str .= '<td>' . $row[ 'a_num' ] . '</td>';
				$str .= '<td>' . $row[ 'amount' ] . '</td>';
				$str .= '<td>' . $row[ 'user_name' ] . '</td>';
				$str .= '<td>' . $row[ 'project_name' ] . '</td>';
				$str .= '<td>' . ( $row[ 'returndate' ] ? '�ѹ黹' : ( $row[ 'confirm' ] == 1 || $row[ 'claim' ] == 1 ? '��ȷ��' : '<span>��ȷ��</span>' ) ) . '</td>';
				$str .= '<td>' . date ( 'Y-m-d' , $row[ 'Wdate' ] ) . '</td>';
				$str .= '<td>' . date ( 'Y-m-d' , $row[ 'targetdate' ] ) . '</td>';
				$str .= '<td>' . ( $row[ 'returndate' ] ? date ( 'Y-m-d' , $row[ 'returndate' ] ) : '��δ�黹' ) . '</td>';
				$str .= '</tr>';
			}
		}
		return $html ? $str : $data;
	
	}
	
	function model_noedevice_list ( $info_id )
	{
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from device_borrow_order_info where info_id=$info_id" );
			$this -> num = $rs[ 'num' ];
		}
		$query = $this -> query ( "
								select
									a.*,b.fitting,d.name as projectname,user_name
								from
									device_borrow_order_info as a
									left join device_info as b on b.id=a.info_id
									left join device_borrow_order as c on c.id=a.orderid 
									left join project_info as d on d.id = c.project_id 
									left join user as e on e.user_id=c.userid
								where
									a.info_id=$info_id
								order by a.id desc
								limit $this->start," . pagenum . "
		" );
		
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '
					<tr>
						<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
						<td>' . ( $rs[ 'returndate' ] ? date ( 'Y-m-d' , $rs[ 'returndate' ] ) : ( $rs[ 'return_num' ]==0 ?'��δ�黹':'���ݹ黹('.$rs[ 'return_num' ].')'  ) ) . '</td>
						<td>' . round ( $rs[ 'returndate' ] ? ( $rs[ 'returndate' ] - $rs[ 'date' ] ) / 86400 : ( time ( ) - $rs[ 'date' ] ) / 86400 , 2 ) . '</td>
						<td>' . $rs[ 'projectname' ] . '</td>
						<td>' . $rs[ 'user_name' ] . '</td>
						<td>' . $rs[ 'fitting' ] . '</td>
					</tr>
			';
		}
		
		$showpage = new includes_class_page ( );
		$showpage -> show_page ( array ( 
										
										'total' => $this -> num , 
										'perpage' => pagenum 
		) );
		$showpage -> _set_url ( 'num=' . $this -> num . '&id=' . $info_id );
		return $str . '<tr><td colspan="20" style="text-align:center;">' . $showpage -> show ( 6 ) . '</td></tr>';
	
	}
	/**
	 * ��Ŀ���豸����
	 * @param $project_id
	 */
	function model_get_project_type ( $project_id )
	{
		$query = $this -> query ( "
								select 
									distinct(a.typeid) as typeid,b.typename 
								from 
									device_borrow_order_info as a
									left join device_type as b on b.id=a.typeid
									left join device_borrow_order as d on d.id=a.orderid
								where
									d.project_id='" . $project_id . "'	
							" );
		$data = array ();
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$data[ ] = $rs;
		}
		return $data;
	}
	/**
	 * ��Ŀȫ���豸�б�
	 *
	 * @return unknown
	 */
	function model_show_project_list ( $porject_id , $checkbox = false , $export = false )
	{
		$typeid = $_GET[ 'typeid' ] ? $_GET[ 'typeid' ] : $_POST[ 'typeid' ];
		$return_status = $_GET[ 'return_status' ] ? $_GET[ 'return_status' ] : $_POST[ 'return_status' ];
		$start_date = $_GET[ 'start_date' ] ? $_GET[ 'start_date' ] : $_POST[ 'start_date' ];
		$end_date = $_GET[ 'end_date' ] ? $_GET[ 'end_date' ] : $_POST[ 'end_date' ];
		$stock = new model_device_stock ( );
		$query = $this -> query ( "
								select
									distinct(a.typeid) as typeid ,c.typename
								from
									device_borrow_order_info as a
									left join device_borrow_order as b on b.id=a.orderid
									left join device_type as c on  c.id=a.typeid
								where
									b.project_id=$porject_id
									" . ( $typeid ? " and a.typeid=$typeid" : "" ) . "
									" . ( $return_status == 1 ? " and (a.returndate is not null or a.returndate!='')" : ( $return_status == 2 ? " and (a.returndate is null or a.returndate='')" : "" ) ) . "
									" . ( $start_date ? " and a.date >='" . strtotime ( $start_date ) . "'" : "" ) . "
									" . ( $end_date ? " and a.date <='" . strtotime ( $end_date ) . "'" : "" ) . "
		" );
		$data = array ();
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			if ( $export )
			{
				$data[ 'column' ][ ] = $this -> format_title ( $rs[ 'typeid' ] );
				$data[ 'sheet' ][ ] = $rs[ 'typename' ];
				$data[ 'row' ][ ] = $this -> model_get_project_list ( $porject_id , $rs[ 'typeid' ] , $stock , $checkbox , $export );
			} else
			{
				$str .= '<table class="table" border="1" width="98%" cellspacing="0" cellpadding="0" align="center" style="position:relative;">
							<tr bgcolor="#D3E5FA">
								<td colspan="20"><a href="javascript:close(' . $rs[ 'typeid' ] . ')">' . $rs[ 'typename' ] . '</a> <span style="position:absolute;right:5px;line-height:20px;"><a href="javascript:close(' . $rs[ 'typeid' ] . ')" id="a_' . $rs[ 'typeid' ] . '"><img src="images/work/sub.png" border="0" /></a></span></td>
							<tbody id="close_' . $rs[ 'typeid' ] . '">
							<tr class="tableheader">
								<td>���</td>
								<td>�豸����</td>
								' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> get_fixed_field_name ( $rs[ 'typeid' ] ) ) . '
								' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> model_show_field_name ( $rs[ 'typeid' ] ) ) . '
								<td>����</td>
								<td>ȷ��״̬</td>
								<td>��������</td>
								<td>Ԥ�ƹ黹����</td>
								<td>ʵ�ʹ黹����</td>
								<td>��ע</td>
							</tr>
							' . $this -> model_get_project_list ( $porject_id , $rs[ 'typeid' ] , $stock ) . '
							</tbody>
						</table><br />';
			}
		}
		return $export ? $data : $str;
	}
	/**
	 * ��Ŀ�е����
	 * @param $porject_id
	 * @param $typeid
	 * @param $stock
	 * @param $checkbox
	 */
	function model_get_project_list ( $porject_id , $typeid , $stock = null , $checkbox = false , $export = false )
	{
		$return_status = $_GET[ 'return_status' ] ? $_GET[ 'return_status' ] : $_POST[ 'return_status' ];
		$start_date = $_GET[ 'start_date' ] ? $_GET[ 'start_date' ] : $_POST[ 'start_date' ];
		$end_date = $_GET[ 'end_date' ] ? $_GET[ 'end_date' ] : $_POST[ 'end_date' ];
		$stock = $stock ? $stock : new model_device_stock ( );
		$query = $this -> query ( "
								select
									a.id,a.amount as a_num ,a.targetdate,a.returndate,a.claim,a.date as Wdate,a.notse as ns,c.device_name,b.confirm,e.*,f.name as project_name
								from
									device_borrow_order_info as a
									left join device_borrow_order as b on b.id=a.orderid
									left join device_list as c on c.id=a.list_id
									left join user as d on d.user_id=b.userid
									left join device_info as e on e.id=a.info_id
									left join project_info as f on f.id=b.project_id
								where
									b.project_id=$porject_id
									and a.typeid = $typeid
									" . ( $return_status == 1 ? " and (a.returndate is not null or a.returndate!='')" : ( $return_status == 2 ? " and (a.returndate is null or a.returndate='')" : "" ) ) . "
									" . ( $start_date ? " and a.date >='" . strtotime ( $start_date ) . "'" : "" ) . "
									" . ( $end_date ? " and a.date <='" . strtotime ( $end_date ) . "'" : "" ) . "
									
		" );
		$rs = $stock -> get_fixed_field_name ( $typeid , false );
		$field = $stock -> model_show_field_name ( $typeid , false );
		$data = array ();
		$i = 0;
		while ( ( $row = $this -> fetch_array ( $query ) ) != false )
		{
			if ( $export )
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
					$field_data = $stock -> get_field_content ( $row[ 'id' ] );
					foreach ( $field as $key => $val )
					{
						$data[ $i ][ ] = $field_data[ $val[ 'id' ] ] ? $field_data[ $val[ 'id' ] ] : '--';
					}
					unset ( $field_data );
				}
				$data[ $i ][ ] = $row[ 'a_num' ];
				$data[ $i ][ ] = $row[ 'user_name' ];
				$data[ $i ][ ] = $row[ 'project_name' ] ? $row[ 'project_name' ] : '����ʹ��';
				$data[ $i ][ ] = $row[ 'confirm' ] == 1 || $row[ 'claim' ] == 1 ? '��ȷ��' : '<span>��ȷ��</span>';
				$data[ $i ][ ] = date ( 'Y-m-d' , $row[ 'Wdate' ] );
				$data[ $i ][ ] = date ( 'Y-m-d' , $row[ 'targetdate' ] );
				$data[ $i ][ ] = $row[ 'returndate' ] ? date ( 'Y-m-d' , $row[ 'returndate' ] ) : '��δ�黹';
				$i ++ ;
			} else
			{
				$str .= '<tr>';
				$str .= $checkbox ? '<td><input type="checkbox" ' . ( $row[ 'returndate' ] ? 'disabled' : '' ) . ' name="id[]" value="' . $row[ 'orderid' ] . '" /></td>' : '';
				$str .= '<td>' . $row[ 'id' ] . '</td>';
				$str .= '<td>' . $row[ 'device_name' ] . '</td>';
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
									$str .= '<td>��' . number_format ( $row[ 'price' ] , 2 ) . '</td>';
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
					$field_data = $stock -> get_field_content ( $row[ 'id' ] );
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
				$str .= '<td>' . $row[ 'a_num' ] . '</td>';
				$str .= '<td>' . ( $row[ 'confirm' ] == 1 || $row[ 'claim' ] == 1 ? '��ȷ��' : '<span>��ȷ��</span>' ) . '</td>';
				$str .= '<td>' . date ( 'Y-m-d' , $row[ 'Wdate' ] ) . '</td>';
				$str .= '<td>' . date ( 'Y-m-d' , $row[ 'targetdate' ] ) . '</td>';
				$str .= '<td>' . ( $row[ 'returndate' ] ? date ( 'Y-m-d' , $row[ 'returndate' ] ) : '��δ�黹' ) . '</td>';
				$str .= '<td>' . $row[ 'ns' ] . '</td>';
				$str .= '</tr>';
			}
		}
		
		return $export ? $data : $str;
	}
	/**
	 * ����������黹
	 *
	 * @return unknown
	 */
	function model_return_device_list ( )
	{
		if ( $_POST[ 'id' ] )
		{
			$infoid = implode ( ',' , $_POST[ 'id' ] );
			
			$query = $this -> query ( "
								select 
									a.id,a.info_id,a.amount,a.return_num,d.user_name
								from 
									device_borrow_order_info as a
									left join device_borrow_order as b on b.id=a.orderid
									left join user d on d.user_id=b.userid
								where a.id in (" . $infoid . ")" );
			$id_arr = array ();
			$info_arr = array ();
			$borrow_num = array ();
			$return_num = array ();
			$borrow_user = array ();
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$id_arr[ $rs[ 'info_id' ] ] = $rs[ 'id' ];
				$info_arr[ ] = $rs[ 'info_id' ];
				$borrow_num[ $rs[ 'info_id' ] ] = $rs[ 'amount' ];
				$return_num[ $rs[ 'info_id' ] ] = $rs[ 'return_num' ];
				$borrow_user[ $rs[ 'info_id' ] ] = $rs[ 'user_name' ];
			}
			$stock = new model_device_stock ( );
			$data = $stock -> model_device_info ( $info_arr );
			$data = str_replace ( '<td>�������</td>' , '<td>������</td><td>�������</td><td>�ѹ黹����</td><td>�黹����</td><td>�豸״̬</td><td>��ע</td>' , $data );
			preg_match_all ( '/\<input type="checkbox"  name="id\[\]" value="(.*?)" \/>/' , $data , $arr );
			$data = preg_replace ( '/\<input type="checkbox"  name="id\[\]" value="(.*?)" \/>/' , '<input id="${1}" type="checkbox"  name="id[]" value="${1}" />' , $data );
			$data = preg_replace ( '/<td width="80" id="amount_(.*?)">(.*?)<\/td>/' , '<td id="amount_${1}">${2}</td>
								<td>^${1}^</td>
								<td id="borrow_${1}">_${1}_</td>
								<td id="return_${1}">-${1}-</td>
								<td><input type="text" size="5" name="return_num[${1}]" onKeyUp="value=this.value.replace(/\\D/g,\'\');checkmax(${1},this)"  value="1" /></td>
								<td><input type="text" size="12" name="status[${1}]" value="" /></td>
								<td><input type="text" size="12" name="notse[${1}]" value="" /></td>
								' , $data );
			foreach ( $arr[ 1 ] as $val )
			{
				$data = str_replace ( '_' . $val . '_' , $borrow_num[ $val ] , $data );
				$data = str_replace ( '-' . $val . '-' , $return_num[ $val ] , $data );
				$data = str_replace ( '^' . $val . '^' , $borrow_user[ $val ] , $data );
				$data = str_replace ( 'value="' . $val . '"' , 'value="' . $id_arr[ $val ] . '"' , $data );
				//$data = str_replace ( 'id="borrow_' . $val . '"' , $id_arr[ $val ] , $data );
				//$data = str_replace ( 'id="return_' . $val . '"' , $id_arr[ $val ] , $data );
				$data = preg_replace ( '/<td width="80" id="borrows_(.+?)">(.+?)<\/td>/' , '' , $data );
			}
			return '<table width="98%" cellspacing="0" cellpadding="0" align="center"><tr><td>���С�<span>' . count ( $id_arr ) . '</span>���������ü�¼</td></tr></table>' . $data;
		}
	}
	/**
	 * ����������黹
	 *
	 * @return unknown
	 */
	function model_return_orders_list ( )
	{
		if ( $_POST[ 'id' ] )
		{
			$infoid = implode ( ',' , $_POST[ 'id' ] );
			
			$query = $this -> query ( "
								select 
									a.id,a.info_id,a.amount,a.return_num,d.user_name
								from 
									device_borrow_order_info as a
									left join device_borrow_order as b on b.id=a.orderid
									left join user d on d.user_id=b.userid
								where a.id in (" . $infoid . ")" );
			$id_arr = array ();
			$info_arr = array ();
			$borrow_num = array ();
			$return_num = array ();
			$borrow_user = array ();
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$id_arr[ $rs[ 'id' ] ] = $rs[ 'id' ];
				$info_arr[ ] = $rs[ 'info_id' ];
				$borrow_num[ $rs[ 'id' ] ] = $rs[ 'amount' ];
				$return_num[ $rs[ 'id' ] ] = $rs[ 'return_num' ];
				$borrow_user[ $rs[ 'id' ] ] = $rs[ 'user_name' ];
			}
			$stock = new model_device_stock ( );
			$data = $stock -> model_device_return_info ( $info_arr,'', $id_arr);
			$data = str_replace ( '<td>�������</td>' , '<td>������</td><td>�������</td><td>�ѹ黹����</td><td>�黹����</td><td>�豸״̬</td><td>��ע</td>' , $data );
			preg_match_all ( '/\<input type="checkbox"  name="id\[\]" value="(.*?)" \/>/' , $data , $arr );
			$data = preg_replace ( '/\<input type="checkbox"  name="id\[\]" value="(.*?)" \/>/' , '<input id="${1}" type="checkbox"  name="id[]" value="${1}" />' , $data );
			$data = preg_replace ( '/<td width="80" id="amount_(.*?)">(.*?)<\/td>/' , '<td id="amount_${1}">${2}</td>
								<td>^${1}^</td>
								<td id="borrow_${1}">_${1}_</td>
								<td id="return_${1}">-${1}-</td>
								<td><input type="text" size="5" name="return_num[${1}]" onKeyUp="value=this.value.replace(/\\D/g,\'\');checkmax(${1},this)"  value="1" /></td>
								<td><input type="text" size="12" name="status[${1}]" value="" /></td>
								<td><input type="text" size="12" name="notse[${1}]" value="" /></td>
								' , $data );
			foreach ( $arr[ 1 ] as $val )
			{
				$data = str_replace ( '_' . $val . '_' , $borrow_num[ $val ] , $data );
				$data = str_replace ( '-' . $val . '-' , $return_num[ $val ] , $data );
				$data = str_replace ( '^' . $val . '^' , $borrow_user[ $val ] , $data );
				$data = str_replace ( 'value="' . $val . '"' , 'value="' . $id_arr[ $val ] . '"' , $data );
				//$data = str_replace ( 'id="borrow_' . $val . '"' , $id_arr[ $val ] , $data );
				//$data = str_replace ( 'id="return_' . $val . '"' , $id_arr[ $val ] , $data );
				$data = preg_replace ( '/<td width="80" id="borrows_(.+?)">(.+?)<\/td>/' , '' , $data );
			}
			return '<table width="98%" cellspacing="0" cellpadding="0" align="center"><tr><td>���С�<span>' . count ( $id_arr ) . '</span>���������ü�¼</td></tr></table>' . $data;
		}
	}
	
	/**
	 * ���ù���ת��
	 * @param unknown_type $id
	 */
	function model_borrow_info_turn ( $id )
	{
		if ( is_array ( $id ) )
		{
			$id = implode ( ',' , $id );
		}
		$query = $this -> query ( "select id,info_id,amount,return_num,date from device_borrow_order_info where id in (" . $id . ")" );
		$id_arr = array ();
		$info_arr = array ();
		$borrow_num = array ();
		$return_num = array ();
		$date_array = array ();
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$id_arr[ $rs[ 'info_id' ] ] = $rs[ 'id' ];
			$info_arr[ ] = $rs[ 'info_id' ];
			$borrow_num[ $rs[ 'info_id' ] ] = $rs[ 'amount' ];
			$return_num[ $rs[ 'info_id' ] ] = $rs[ 'return_num' ];
			$date_array[ $rs[ 'info_id' ] ] = $rs[ 'date' ];
		}
		
		$stock = new model_device_stock ( );
		$data = $stock -> model_device_info ( $info_arr );
		$data = str_replace ( '<td>�������</td>' , '<td>�������</td><td>��������</td><td>��������</td><td>�ѹ黹����</td>' , $data );
		$data = preg_replace ( '/<td width="80" id="amount_(.*?)">(.*?)<\/td>/' , '<td id="amount_${1}">${2}</td><td>_${1}_</td><td>^${1}^</td><td>-${1}-</td>' , $data );
		preg_match_all ( '/\<input type="checkbox"  name="id\[\]" value="(.*?)" \/>/' , $data , $arr );
		foreach ( $arr[ 1 ] as $val )
		{
			$data = str_replace ( '_' . $val . '_' , $borrow_num[ $val ] , $data );
			$data = str_replace ( '^' . $val . '^' , date ( 'Y-m-d' , $date_array[ $val ] ) , $data );
			$data = str_replace ( '-' . $val . '-' , $return_num[ $val ] , $data );
		
		//$data = str_replace('value="'.$val.'"','value="'.$id_arr[$val].'"',$data);
		}
		return $data;
	}
	
	/**
	 * �黹�б�
	 *
	 * @return unknown
	 */
	function model_show_return_list ( )
	{
		if ( $_POST[ 'id' ] )
		{
			$infoid = implode ( ',' , $_POST[ 'id' ] );
			$query = $this -> query ( "
								select 
									a.id,a.info_id,a.amount,a.return_num,d.user_name
								from 
									device_borrow_order_info as a
									left join device_borrow_order as b on b.id=a.orderid
									left join user as d on d.user_id=b.userid
									
								where a.id in (" . $infoid . ")" );
			$id_arr = array ();
			$info_arr = array ();
			$borrow_num = array ();
			$return_num = array ();
			$borrow_user = array ();
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$id_arr[ $rs[ 'id' ] ] = $rs[ 'id' ];
				$info_arr[ ] = $rs[ 'info_id' ];
				$borrow_num[ $rs[ 'id' ] ] = $rs[ 'amount' ];
				$return_num[ $rs[ 'id' ] ] = $rs[ 'return_num' ];
				$borrow_user[ $rs[ 'id' ] ] = $rs[ 'user_name' ];
			}
			$stock = new model_device_stock ( );
			$data = $stock -> model_device_return_info ( $info_arr,'', $id_arr);
			$data = str_replace ( '<td>�������</td>' , '<td>�������</td><td>������</td><td>�ѹ黹����</td><td>�黹����</td><td>�豸״̬</td><td>��ע</td>' , $data );
			preg_match_all ( '/\<input type="checkbox"  name="id\[\]" value="(.*?)" \/>/' , $data , $arr );
			$data = preg_replace ( '/<td width="80" id="borrows_(.*?)">(.*?)<\/td>/' , '<td id="borrows_${1}">${2}</td>
								<td>^${1}^</td>
							   <td id="return_${1}">-${1}-</td>
								<td><input type="hidden" size="5" name="return_num[${1}]" value="return_num(${1})" />return_num(${1})</td>
								<td><input type="hidden" size="12" name="status[${1}]" value="status(${1})" />status(${1})</td>
								<td><input type="hidden" size="12" name="notse[${1}]" value="notse(${1})" />notse(${1})</td>
								' , $data );		
			foreach ( $arr[ 1 ] as $val )
			{
				$data = str_replace ( '_' . $val . '_' , $borrow_num[ $val ] , $data );
				$data = str_replace ( '-' . $val . '-' , $return_num[ $val ] , $data );
				$data = str_replace ( '^' . $val . '^' , $borrow_user[ $val ] , $data );
				$data = str_replace ( 'value="' . $val . '"' , 'value="' . $id_arr[ $val ] . '"' , $data );
				$data = str_replace ( '<td><input type="checkbox"' , '<td style="display:none;"><input type="hidden"' , $data );
				$data = str_replace ( 'return_num(' . $val . ')' , $_POST[ 'return_num' ][ $val ] , $data );
				$data = str_replace ( 'status(' . $val . ')' , $_POST[ 'status' ][ $val ] , $data );
				$data = str_replace ( 'notse(' . $val . ')' , $_POST[ 'notse' ][ $val ] , $data );
			
		//$data = preg_replace ( '/<td width="80" id="borrows_(.+?)">(.+?)<\/td>/' , '' , $data );
			}
			return '<table width="98%" cellspacing="0" cellpadding="0" align="center"><tr><td>���ι��黹��<span>' . count ( $id_arr ) . '</span>��������¼</td></tr></table>' . $data;
		}
	}
	
	/**
	 * �黹�����б�
	 */
	function model_return_order_list ( )
	{
		global $func_limit;
		$sort = $_GET[ 'sort' ] ? $_GET[ 'sort' ] : 'a.id-desc';
		$sort = str_replace ( '-' , ' ' , $sort );
		if ( $_SESSION[ 'USER_ID' ] != 'admin' )
		{
			$where = 'where j.dept_id in(' . ( $func_limit[ '������' ] ? $func_limit[ '������' ] : $_SESSION[ 'DEPT_ID' ] ) . ')';
		}
		$where .= $where ? ' and a.archive!=1 ' : ' where a.archive!=1 ';
		if (  ! $this -> num )
		{
			$rs = $this -> _db -> get_one ( "
										select 
											count(distinct(a.id)) as num 
										from 
											device_return_order as a
											left join user as b on b.user_id=a.userid 
											left join user as c on c.user_id=a.operatorid 
											left join area as d on d.ID=a.area 
											left join device_return_order_info as e on e.orderid=a.id
											left join device_info as g on g.id=e.tid
											left join device_list as h on h.id=g.list_id
											left join device_type as j on j.id=h.typeid
											$where" );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num > 0 )
		{
			$query = $this -> _db -> query ( "
			select 
				distinct(a.id),a.*,b.user_name,c.user_name as operatorname,d.name as areaname
			FROM 
				device_return_order as a
				left join user as b on b.user_id=a.userid 
				left join user as c on c.user_id=a.operatorid 
				left join area as d on d.ID=a.area 
				left join device_return_order_info as e on e.orderid=a.id
				left join device_info as g on g.id=e.tid
				left join device_list as h on h.id=g.list_id
				left join device_type as j on j.id=h.typeid
				$where 
				order by $sort limit $this->start," . pagenum . "
				" );
			while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
			{
				$str .= '
						<tr>
						<td>' . $rs[ 'id' ] . '</td>
						<td>' . $rs[ 'user_name' ] . '</td>
						<td>' . $rs[ 'amount' ] . '</td>
						<td>' . $rs[ 'areaname' ] . '</td>
						<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
						<td>' . $rs[ 'operatorname' ] . '</td>
						<td><a href="?model=device_borrow&action=return_order_info&id=' . $rs[ 'id' ] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=650" class="thickbox" title="�鿴 [' . $rs[ 'id' ] . '] ��ϸ��Ϣ">�鿴��ϸ��¼</a></td>
						</tr>
						';
			}
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array ( 
											
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num . '&project_id=' . $_GET[ 'project_id' ] . '&sort=' . $_GET[ 'sort' ] );
			return $str . '<tr><td colspan="8" style="text-align:center;">' . $showpage -> show ( 6 ) . '</td></tr>';
		}
	}
	/**
	 * �黹���豸�б�
	 *
	 * @return unknown
	 */
	function model_return_order_info ( )
	{
		if ( intval ( $_GET[ 'id' ] ) )
		{
			if (  ! $this -> num )
			{
				$rs = $this -> _db -> get_one ( "select count(0) as num from device_return_order_info where orderid=" . $_GET[ 'id' ] );
				$this -> num = $rs[ 'num' ];
			}
			if ( $this -> num )
			{
				$query = $this -> _db -> query ( "
				SELECT 
					a.*,c.fitting,d.device_name,e.typename,f.user_name,g.user_name as operatorname,h.amount as borrow_amount,h.return_num,h.date as hdate, k.user_name as  kusername 
				FROM 
					device_return_order_info AS a 
					left join device_return_order as b on b.id=a.orderid 
					left join device_info as c on c.id=a.tid 
					left join device_list as d on d.id=c.list_id 
					left join device_type as e on e.id=d.typeid 
					left join user as f on f.user_id=b.userid 
					left join user as g on g.user_id=b.operatorid 
					left join device_borrow_order_info as h on h.id=a.info_id 
					left join device_borrow_order as j on j.id=h.orderid 
					left join user as k on k.user_id=j.userid 
					where a.orderid=" . $_GET[ 'id' ] . " 
					order by id desc limit $this->start," . pagenum . "
				" );
				while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
				{
					$str .= '
						<tr>
						<td>' . $rs[ 'id' ] . '</td>
						<td>' . $rs[ 'typename' ] . '</td>
						<td>' . $rs[ 'device_name' ] . '</td>
						<td>' . $rs[ 'fitting' ] . '</td>
						<td>' . $rs[ 'borrow_amount' ] . '</td>
						<td>' . $rs[ 'return_num' ] . '</td>
						<td>' . $rs[ 'amount' ] . '</td>
						<td>' . $rs[ 'kusername' ] . '</td>
						<td>' . $rs[ 'user_name' ] . '</td>
						<td>' . date ( 'Y-m-d' , $rs[ 'hdate' ] ) . '</td>
						<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
						<td>' . $rs[ 'operatorname' ] . '</td>
						<td>' . $rs[ 'status' ] . '</td>
						<td>' . $rs[ 'notse' ] . '</td>
						</tr>
						';
				}
				return $str;
			}
		} else
		{
			return false;
		}
	}
	
	/**
	 * ������������б�
	 *
	 * @return unknown
	 */
	function model_batch_search ( )
	{
		global $func_limit;
		if ( $_POST[ 'field' ] && $_POST[ 'content' ] )
		{
			$arr = explode ( "\r\n" , $_POST[ 'content' ] );
			$content = array ();
			foreach ( $arr as $v )
			{
				if ( $v )
				{
					$content[ ] = sprintf ( "'%s'" , $v );
				}
			}
			$content = implode ( ',' , $content );
			if ( $_SESSION[ 'USER_ID' ] == 'admin' )
			{
				$where_dept = '';
			} else
			{
				$where_dept = 'and a.dept_id in(' . ( $func_limit[ '������' ] ? $func_limit[ '������' ] : $_SESSION[ 'DEPT_ID' ] ) . ')';
			}
			switch ( $_POST[ 'field' ] )
			{
				case 'device_name' :
					$query = $this -> _db -> query ( "select a.*,b.device_name,b.typeid,c.typename from device_info as a
						left join device_list as b on b.id=a.list_id 
						left join device_type as c on c.id=b.typeid 
						where 
							 b.device_name in (" . $content . ") $where_dept 
							 and a.quit=0 and if(a.amount>1,if(a.amount>a.borrow_num,1,0),a.state=0)
					" );
					break;
				case 'coding' :
					$query = $this -> _db -> query ( "select a.*,b.device_name,b.typeid,c.typename from device_info as a
						left join device_list as b on b.id=a.list_id 
						left join device_type as c on c.id=b.typeid 
						where  a.coding in(" . $content . ") $where_dept 
						and a.quit=0 and if(a.amount>1,if(a.amount>a.borrow_num,1,0),a.state=0)
					" );
					break;
				case 'dpcoding' :
					$query = $this -> _db -> query ( "select a.*,b.device_name,b.typeid,c.typename from device_info as a
						left join device_list as b on b.id=a.list_id 
						left join device_type as c on c.id=b.typeid 
						where a.dpcoding in(" . $content . ") $where_dept and a.quit=0 
						and if(a.amount>1,if(a.amount>a.borrow_num,1,0),a.state=0) 
					" );
					break;
				case 'id' :
					$query = $this -> _db -> query ( "select a.*,b.device_name,b.typeid,c.typename from device_info as a
						left join device_list as b on b.id=a.list_id 
						left join device_type as c on c.id=b.typeid 
						where  a.id in(" . $content . ") $where_dept 
						and a.quit=0 and if(a.amount>1,if(a.amount>a.borrow_num,1,0),a.state=0) 
					" );
					break;
				default :
					$query = $this -> _db -> query ( "select a.*,b.device_name,b.typeid,c.typename from device_info as a
						left join device_list as b on b.id=a.list_id 
						left join device_type as c on c.id=b.typeid 
						where  a.quit=0 and a.quit=0 and if(a.amount>1,if(a.amount>a.borrow_num,1,0),a.state=0)  and a.id 
						in (select info_id from device_type_field_content where content in (" . $content . ")) $where_dept
					" );
			
			}
			$num = $this -> _db -> num_rows ( $query );
			if ( $num > 0 )
			{
				$info_id = array ();
				$disabled = array ();
				$amount = 0;
				while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
				{
					if ( $rs[ 'amount' ] <= $rs[ 'borrow_num' ] )
					{
						$amount ++ ;
					}
					$disabled[ $rs[ 'id' ] ] = $rs[ 'amount' ] <= $rs[ 'borrow_num' ] ? 'disabled' : '';
					$info_id[ ] = $rs[ 'id' ];
				}
				$data = $this -> model_borrow_info ( $info_id );
				preg_match_all ( '/<td id="amount_(.+?)">(.+?)<\/td>/' , $data , $arr );
				preg_match_all ( '/<td id="borrows_(.+?)">(.+?)<\/td>/' , $data , $arb );
				if ( $arr[ 0 ] )
				{
					foreach ( $arr[ 0 ] as $key => $val )
					{
						$data = str_replace ( '<input type="checkbox" name="id[]" value="' . $arr[ 1 ][ $key ] . '" />' , '<input type="checkbox" name="id[]" ' . $disabled[ $arr[ 1 ][ $key ] ] . ' value="' . $arr[ 1 ][ $key ] . '" />' , $data );
						$data = str_replace ( '<td id="amount_' . $arr[ 1 ][ $key ] . '">' . $arr[ 2 ][ $key ] . '</td>' , '<td id="amount_' . $arr[ 1 ][ $key ] . '">' . $arr[ 2 ][ $key ] . '</td>' , $data );
						$data = str_replace ( '<td id="borrows_' . $arb[ 1 ][ $key ] . '">' . $arb[ 2 ][ $key ] . '</td>' , '<td id="borrows_' . $arb[ 1 ][ $key ] . '">' . $arb[ 2 ][ $key ] . '</td><td><input type="text" size="5" name="amount[' . ( $arr[ 1 ][ $key ] ) . ']" onKeyUp="value=this.value.replace(/\\D/g,\'\');if(' . ( $arr[ 2 ][ $key ] - $arb[ 2 ][ $key ] ) . '<this.value){alert(\'�����������ܴ��ڿ��������\');this.value=1;}" value="1" /></td>
											<td><input type="text" size="12" class="Wdate" readonly onClick="WdatePicker({dateFmt:\'yyyy-MM-dd\',minDate:\'%y-%M-%d\'})"  name="target_date[' . ( $arr[ 1 ][ $key ] ) . ']" value="' . $_POST[ 'info_target_date' ][ ( $arr[ 1 ][ $key ] ) ] . '" /></td>
											<td><input type="text" name="notse[' . ( $arr[ 1 ][ $key ] ) . ']" value="' . $_POST[ 'notes' ][ ( $arr[ 1 ][ $key ] ) ] . '" /></td>' , $data );
					}
				}
				return '<table width="98%" cellspacing="0" cellpadding="0" align="center"><tr><td>���С�<span>' . ( $num - $amount ) . '</span>������¼�ɽ���</td></tr></table>' . $data;
			} else
			{
				showmsg ( 'û���ҵ����ʵ����ݣ�' );
			}
		}
	}
	
	/**
	 * �����黹��ѯ����б�
	 *
	 * @return unknown
	 */
	function model_batch_order_info_search ( )
	{
		global $func_limit;
		if ( $_POST[ 'field' ] && $_POST[ 'content' ] )
		{
			$where = '';
			$arr = explode ( "\r\n" , $_POST[ 'content' ] );
			foreach ( $arr as $v )
			{
				if($v){
				  $content .= "'" . $v . "',";	
				}
			}
			$content = rtrim ( $content , ',' );
			if ( $_SESSION[ 'USER_ID' ] == 'admin' )
			{
				$where = '';
			} else
			{
				$where = ' and c.dept_id in(' . ( $func_limit[ '������' ] ? $func_limit[ '������' ] : $_SESSION[ 'DEPT_ID' ] ) . ')';
			}
			
			switch ( $_POST[ 'field' ] )
			{
				case 'device_name' :
					$where .= " and b.device_name in (" . $content . ") $where_dept and d.quit=0";
					break;
				case 'coding' :
					$where .= " and d.coding in(" . $content . ") ";
					break;
				case 'dpcoding' :
					$where .= " and d.dpcoding in(" . $content . ") ";
					break;
				case 'id' :
					$where .= " and d.id in(" . $content . ") ";
					break;
				default :
					$where .= " and a.info_id in (select info_id from device_type_field_content where field_id=" . $_POST[ 'field' ] . " and content in (" . $content . "))";
			
			}
			$query = $this -> query ( "
								select 
									a.*,b.device_name,b.typeid,c.typename ,f.user_name
								from 
									device_borrow_order_info as a
									left join device_list as b on b.id=a.list_id 
									left join device_type as c on c.id=a.typeid 
									left join device_info as d on d.id=a.info_id
									left join device_borrow_order as e on e.id=a.orderid
									left join user as f on f.user_id=e.userid
										where a.returndate is null $where
					" );
			$num = $this -> _db -> num_rows ( $query );
			if ( $num > 0 )
			{
				$id_arr = array ();
				$info_id = array ();
				$disabled = array ();
				$target_date = array ();
				$borrow_num = array ();
				$borrow_user = array ();
				$amount = 0;
				while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
				{
					if ( $rs[ 'amount' ] <= $rs[ 'return_num' ] )
					{
						$amount ++ ;
					}
					$disabled[ $rs[ 'id' ] ] = $rs[ 'amount' ] <= $rs[ 'return_num' ] ? 'disabled' : '';
					$info_id[ ] = $rs[ 'info_id' ];
					$id_arr[ $rs[ 'id' ] ] = $rs[ 'id' ];
					$target_date[ $rs[ 'id' ] ] = date ( 'Y-m-d' , $rs[ 'targetdate' ] );
					$borrow_num[ $rs[ 'id' ] ] = $rs[ 'amount' ];
					$borrow_user[ $rs[ 'id' ] ] = $rs[ 'user_name' ];
				}
				$data = $this -> model_return_info ( $info_id );
				$data = str_replace ( '<td>��������</td>' , '<td>������</td><td>��������</td><td>�黹����</td>' , $data );
				$data = str_replace ( '<td>��ע</td>' , '<td>�豸״̬</td><td>��ע</td>' , $data );
				preg_match_all ( '/<td id="borrows_(.*?)">(.*?)<\/td>/' , $data , $arr );
				//preg_match_all ( '/<td id="borrows_(.+?)">(.+?)<\/td>/' , $data , $arr );
				if ( $arr[ 0 ] )
				{
					foreach ( $arr[ 0 ] as $key => $val )
					{
						$data = str_replace ( '<input type="checkbox" name="id[]" value="' . $arr[ 1 ][ $key ] . '" />' , '<input type="checkbox" name="id[]" ' . $disabled[ $arr[ 1 ][ $key ] ] . ' value="' . $id_arr[ $arr[ 1 ][ $key ] ] . '" />' , $data );
						$data = str_replace ( '<td id="borrows_' . $arr[ 1 ][ $key ] . '">' . $arr[ 2 ][ $key ] . '</td>' , '<td id="borrows_' . $arr[ 1 ][ $key ] . '">' . $arr[ 2 ][ $key ] . '</td>
											<td>' . $borrow_user[ $arr[ 1 ][ $key ] ] . '</td>
											<td id="borrow_' . $arr[ 1 ][ $key ] . '">' . $borrow_num[ $arr[ 1 ][ $key ] ] . '</td>
											<td><input type="text" size="5" onKeyUp="value=this.value.replace(/\D/g,\'\');if(' . $borrow_num[ $arr[ 1 ][ $key ] ] . '<this.value){alert(\'�黹�������ܴ��ڽ���������\');this.value=\'\';};" name="return_num[' . $arr[ 1 ][ $key ] . ']" value="1" /></td>
											<td>' . $target_date[ $arr[ 1 ][ $key ] ] . '</td>
											<td><input type="text" name="status[' . ( $arr[ 1 ][ $key ] ) . ']" value="" /></td>
											<td><input type="text" name="notse[' . ( $arr[ 1 ][ $key ] ) . ']" value="' . $_POST[ 'notes' ][ ( $arr[ 1 ][ $key ] ) ] . '" /></td>
											' , $data );
					}
				}
				return '<table width="98%" cellspacing="0" cellpadding="0" align="center"><tr><td>���С�<span>' . ( $num - $amount ) . '</span>������¼�ɹ黹</td></tr></table>' . $data;
			} else
			{
				showmsg ( 'û���ҵ����ʵ����ݣ�' );
			}
		}
	}
	/**
	 * ������ѯ�黹�б�Ԥ��
	 *
	 * @return unknown
	 */
	function model_batch_return_info_list ( )
	{
		if ( $_POST[ 'id' ] )
		{
			$info_id = implode ( ',' , $_POST[ 'id' ] );
			$query = $this -> _db -> query ( "
			select 
				a.id,a.amount,a.return_num,a.targetdate,a.date,b.id as bid,b.dpcoding,b.fitting,c.device_name,d.typename,f.user_name
			from 
				device_borrow_order_info as a 
				left join device_info as b on b.id=a.info_id 
				left join device_list as c on c.id=a.list_id 
				left join device_type as d on d.id=c.typeid 
				left join device_borrow_order as e on e.id=a.orderid 
				left join user as f on f.user_id=e.userid 
				where a.id in($info_id)
			" );
			while ( ( $rs = $this -> _db -> fetch_array ( $query ) ) != false )
			{
				$str .= '
				<tr>
					<input type="hidden" name="return_num[' . $rs[ 'id' ] . ']" value="' . $_POST[ 'return_num' ][ $rs[ 'id' ] ] . '" />
					<input type="hidden" name="status[' . $rs[ 'id' ] . ']" value="' . $_POST[ 'status' ][ $rs[ 'id' ] ] . '" />
					<input type="hidden" name="tid[' . $rs[ 'id' ] . ']" value="' . $rs[ 'bid' ] . '" />
					<input type="hidden" name="notse[' . $rs[ 'id' ] . ']" value="' . $_POST[ 'notse' ][ $rs[ 'id' ] ] . '" />
					<td>' . $rs[ 'bid' ] . '</td>
					<td>' . $rs[ 'typename' ] . '</td>
					<td>' . $rs[ 'device_name' ] . '</td>
					<td>' . $rs[ 'dpcoding' ] . '</td>
					<td>' . $rs[ 'fitting' ] . '</td>
					<td>' . $rs[ 'amount' ] . '</td>
					<td>' . $_POST[ 'return_num' ][ $rs[ 'id' ] ] . '</td>
					<td>' . $rs[ 'user_name' ] . '</td>
					<td>' . date ( 'Y-m-d' , $rs[ 'date' ] ) . '</td>
					<td>' . date ( 'Y-m-d' ) . '</td>
					<td>' . $_POST[ 'status' ][ $rs[ 'id' ] ] . '</td>
				</tr>
				';
			}
			$str .= '<input type="hidden" name="id" value="' . $info_id . '" />';
			return $str;
		}
	}
	/**
	 * ���������ѯ�б�
	 *
	 * @return unknown
	 */
	function model_showborrowlist ( )
	{
		if ( $_POST[ 'id' ] )
		{
			$stock = new model_device_stock ( );
			return $stock -> model_device_info ( $_POST[ 'id' ] );
		}
	}
	/**
	 * ����Ŷ�ȡ
	 * @param unknown_type $info_id
	 */
	function model_borrow_info ( $info_id )
	{
		if ( is_array ( $info_id ) )
		{
			$where = "where a.id in (" . implode ( ',' , $info_id ) . ")";
		} else
		{
			$where = "where a.id in (" . $info_id . ")";
		}
		$stock = new model_device_stock ( );
		$query = $this -> query ( "
								select
									distinct(b.typeid) as tid,c.typename,count(a.id) as num
								from
									device_info as a
									left join device_list as b on b.id=a.list_id
									left join device_type as c on c.id=b.typeid
								$where group by b.typeid
							" );			
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '<table class="table" border="1" width="98%" cellspacing="0" cellpadding="0" align="center" id="type_' . $rs[ 'tid' ] . '">
						<tr bgcolor="#D3E5FA">
							<td colspan="30">' . $rs[ 'typename' ] . '(' . $rs[ 'num' ] . ')</td>
						</tr>
						<tr class="tableheader">
							<td><input type="checkbox" onclick="set_all(this.checked,' . $rs[ 'tid' ] . ')"/></td>
							<td>���</td>
							<td>�豸����</td>
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> get_fixed_field_name ( $rs[ 'tid' ] ) ) . '
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> model_show_field_name ( $rs[ 'tid' ] ) ) . '
							<td>���ڿ��</td>
							<td>�������</td>
							<td>�������</td>
							<td>��������</td>
							<td>Ԥ�ƹ黹����</td>
							<td>��ע</td>
						</tr>';
			$data = $stock -> model_type_info ( $rs[ 'tid' ] , $info_id , false );
			if ( $data )
			{
				foreach ( $data as $row )
				{
					$str .= '<tr>';
					$count = count ( $row );
					foreach ( $row as $key => $val )
					{
						if ( $key == 0 )
						{
							$str .= '<td><input type="checkbox" name="id[]" value="' . $val . '" /></td>';
						}
						if ( $key == ( $count - 2 ) )
						{
							$str .= '<td id="amount_' . $row[ 0 ] . '">' . $val . '</td>';
						} else if ( $key == ( $count - 1 ) )
						{
							$str .= '<td id="borrows_' . $row[ 0 ] . '">' . $val . '</td>';
						} else
						{
							$str .= "<td>$val</td>";
						}
					}
					$str .= '</tr>';
				}
			}
			$str .= '</table><br />';
		}
		return $str;
	}
		/**
	 * ����Ŷ�ȡ
	 * @param unknown_type $info_id
	 */
	function model_return_info ( $info_id )
	{
		if ( is_array ( $info_id ) )
		{
			$where = "where a.id in (" . implode ( ',' , $info_id ) . ")";
		} else
		{
			$where = "where a.id in (" . $info_id . ")";
		}
		$stock = new model_device_stock ( );
		$query = $this -> query ( "
								select
									distinct(b.typeid) as tid,c.typename,count(a.id) as num
								from
									device_info as a
									left join device_list as b on b.id=a.list_id
									left join device_type as c on c.id=b.typeid
								$where group by b.typeid
							" );			
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '<table class="table" border="1" width="98%" cellspacing="0" cellpadding="0" align="center" id="type_' . $rs[ 'tid' ] . '">
						<tr bgcolor="#D3E5FA">
							<td colspan="30">' . $rs[ 'typename' ] . '(' . $rs[ 'num' ] . ')</td>
						</tr>
						<tr class="tableheader">
							<td><input type="checkbox" onclick="set_all(this.checked,' . $rs[ 'tid' ] . ')"/></td>
							<td>���</td>
							<td>�豸����</td>
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> get_fixed_field_name ( $rs[ 'tid' ] ) ) . '
							' . preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> model_show_field_name ( $rs[ 'tid' ] ) ) . '
							<td>���ڿ��</td>
							<td>�������</td>
							<td>�������</td>
							<td>��������</td>
							<td>Ԥ�ƹ黹����</td>
							<td>��ע</td>
						</tr>';
			$data = $stock -> model_type_return_info ( $rs[ 'tid' ] , $info_id , false );
			if ( $data )
			{
				foreach ( $data as $row )
				{
					$str .= '<tr>';
					$count = count ( $row );
					foreach ( $row as $key => $val )
					{
						if ( $key == 0 )
						{
							$str .= '<td><input type="checkbox" name="id[]" value="' . $val . '" /></td>';
						}
						if ( $key == ( $count - 2 ) )
						{
							$str .= '<td id="amount_' . $row[ 0 ] . '">' . $val . '</td>';
						} else if ( $key == ( $count - 1 ) )
						{
							$str .= '<td id="borrows_' . $row[ 0 ] . '">' . $val . '</td>';
						} else
						{
							$str .= "<td>$val</td>";
						}
					}
					$str .= '</tr>';
				}
			}
			$str .= '</table><br />';
		}
		return $str;
	}
	
	//********************************��ȡ����*******************************
	
	/**
	 * ���������黹
	 */
	function model_rebak()
	{
		set_time_limit ( 0 );
		try
			{
				$this -> _db -> query ( "START TRANSACTION" );
				$sql="SELECT a.info_id,a.list_id,a.amount,a.area
					  FROM `device_shift_list` a 
					  LEFT JOIN device_info b ON a.info_id=b.id  
					  WHERE a.userid='bin.ling' AND b.dept_id IN (32,81,82,83,84,85,86,87,95,97,108,109,110,111);";
				$query = $this -> query ($sql);
				while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
				{
							$this -> _db -> query ( "
															update 
																device_info 
															set 
																borrow_num=borrow_num+" . intval ($rs[ 'amount' ] ) . ",
																area = '" . $rs[ 'area' ] . "',
																state ='1'
															where 
																id='".$rs[ 'info_id' ]."'" );
							$this -> _db -> query ( "
										update 
											device_borrow_order_info 
										set 
											return_num=return_num-" . intval ( $rs[ 'amount' ] ) . ",
											returndate='',
											status =status+'-10'
										where 
										FROM_UNIXTIME(returndate,'%Y%m%d')='20130705' and info_id='".$rs[ 'info_id' ]."' and list_id='".$rs[ 'list_id' ]."'" );
						$this -> _db -> query ( "
										update 
											device_return_order_info 
										set 
											status =status+'-10'
										where 
										FROM_UNIXTIME(date,'%Y%m%d')='20130705' and info_id='".$rs[ 'info_id' ]."'" );
				
				}
				$this -> _db -> query ( "COMMIT" );
				return true;
			} catch ( Exception $e )
			{
				$this -> _db -> query ( "ROLLBACK" );
				return false;
			}
		
		}
	

	function get_borrow_order_info ( $orderid )
	{
		$this -> tbl_name = 'device_borrow_order';
		return $this -> find ( 'id=' . $orderid , null , '*' );
	}
	
	//*********************************��������************************************
	/**
	 * ��������
	 */
	function __destruct ( )
	{
	
	}
}
?>