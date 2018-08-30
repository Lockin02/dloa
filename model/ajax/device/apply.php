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
	 * ȡ������
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
			//===���޸���������
			$query = $this->query ( "select 
										d.email
								from 
									purview as a 
									left join purview_type as b on b.tid=a.id
									left join purview_info as c on c.tid=a.id and c.typeid=b.id
									left join user as d on d.user_id=c.userid
									where 
										a.models = 'device_apply'
										and b.name='��������'
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
				$body .= "���ã�" . $_SESSION ['USERNAME'] . "ȡ�����豸�������롣<br />" . oaurlinfo;
				if ($mail_server->send ( $_SESSION ['USERNAME'] . 'ȡ�����豸��������', $body, $email ))
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
	 * �ָ�����
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
				
				//===���޸���������
				$query = $this->query ( "select 
										d.email
								from 
									purview as a 
									left join purview_type as b on b.tid=a.id
									left join purview_info as c on c.tid=a.id and c.typeid=b.id
									left join user as d on d.user_id=c.userid
									where 
										a.models = 'device_apply'
										and b.name='��������'
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
					$body .= "���ã�" . $_SESSION ['USERNAME'] . " �ύ���豸�������룬��Ҫ����¼OA��������<br />" . oaurlinfo;
					if ($mail_server->send ( $_SESSION ['USERNAME'] . ' �ύ���豸��������', $body, $email ))
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