<?php
class model_ajax_device_apply extends model_base
{
	function __construct()
	{
		parent::__construct ();
		
		$_POST = $_POST ? mb_iconv ( $_POST ) : null;
		$_GET = $_GET ? mb_iconv ( $_GET ) : null;
	}
	/**
	 * 取消申请
	 */
	function exit_apply()
	{
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : $_POST ['id'];
		$key = isset ( $_GET ['key'] ) ? $_GET ['key'] : $_POST ['key'];
		
		$rs = $this->get_one ( "select id from device_apply_order where id=$id and `rand_key`='" . $key . "'" );
		if ($rs)
		{
			$this->query ( "update device_apply_order set status=-2 where id=$id and `rand_key`='" . $key . "'" );
			$this->query ( "update device_info set state=0 where id in(select info_id from device_apply_order_info where orderid=" . $id . ")" );
			//===新修改审批规则
			$query = $this->query ( "select 
										d.email
								from 
									purview as a 
									left join purview_type as b on b.tid=a.id
									left join purview_info as c on c.tid=a.id and c.typeid=b.id
									left join user as d on d.user_id=c.userid
									where 
										a.models = 'device_apply'
										and b.name='审批部门'
										and find_in_set('" . $_SESSION ['DEPT_ID'] . "',c.content)
									" );
			$email = array ();
			while ( ($rs = $this->fetch_array ( $query )) != false )
			{
				$email [] = $rs ['email'];
			}
			if ($email)
			{
				$mail_server = new includes_class_sendmail ();
				$body .= "您好，" . $_SESSION ['USERNAME'] . "取消了设备借用申请。<br />" . oaurlinfo;
				if ($mail_server->send ( $_SESSION ['USERNAME'] . '取消了设备借用申请', $body, $email ))
				{
					echo 1;
				}
			} else
			{
				echo 1;
			}
		} else
		{
			echo - 1;
		}
	
	}
	/**
	 * 恢复申请
	 */
	function recovery_apply()
	{
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : $_POST ['id'];
		$key = isset ( $_GET ['key'] ) ? $_GET ['key'] : $_POST ['key'];
		$rs = $this->get_one ( "select id from device_apply_order where id=$id and `rand_key`='" . $key . "'" );
		if ($rs)
		{
			$query = $this->query ( "select 
									b.state 
									from 
										device_apply_order_info as a 
										left join device_info as b on b.id=a.info_id
									where
										a.orderid=$id and rand_key='$key'
										" );
			$state = true;
			while ( ($rs = $this->fetch_array ( $query )) != false )
			{
				if ($rs ['state'] != 0)
				{
					$state = false;
					break;
				}
			}
			if ($state)
			{
				$this->query ( "update device_apply_order set status=0 where id=$id and `rand_key`='" . $key . "'" );
				$this->query ( "update device_info set state=5 where id in(select info_id from device_apply_order_info where orderid=" . $id . ")" );
				
				//===新修改审批规则
				$query = $this->query ( "select 
										d.email
								from 
									purview as a 
									left join purview_type as b on b.tid=a.id
									left join purview_info as c on c.tid=a.id and c.typeid=b.id
									left join user as d on d.user_id=c.userid
									where 
										a.models = 'device_apply'
										and b.name='审批部门'
										and find_in_set('" . $_SESSION ['DEPT_ID'] . "',c.content)
									" );
				$email = array ();
				while ( ($rs = $this->fetch_array ( $query )) != false )
				{
					$email [] = $rs ['email'];
				}
				if ($email)
				{
					$mail_server = new includes_class_sendmail ();
					$body .= "您好，" . $_SESSION ['USERNAME'] . " 提交了设备借用申请，需要您登录OA审批处理。<br />" . oaurlinfo;
					if ($mail_server->send ( $_SESSION ['USERNAME'] . ' 提交了设备借用申请', $body, $email ))
					{
						echo 1;
					}
				} else
				{
					echo 1;
				}
			}else{
				echo -2;
			}
		} else
		{
			echo - 1;
		}
	}
}
?>