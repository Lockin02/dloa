<?php
class controller_device_quit extends model_device_quit
{
	public $show;
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> show = new show ( );
	}
	
	/**
	 * �˿ⵥ�б�
	 */
	function c_order_list ( )
	{
		$stock = new model_device_stock ( );
		$this -> show -> assign ( 'list' , $this -> model_order_list ( ) );
		$this -> show -> assign ( 'select_type' , $stock -> select_type ( $_GET[ 'typeid' ] ) );
		$this -> show -> display ( 'device_quit-order-list' );
	}
	function c_search_order_list ( )
	{
	 echo  un_iconv($this -> model_device_quit_list ( )); 
	}
	/**
	 * �˿ⵥ��ϸ������Ϣ
	 */
	function c_order_info ( )
	{
		if ( $_GET[ 'orderid' ] )
		{
			$data = $this -> model_get_order ( $_GET[ 'orderid' ] );
			$this -> show -> assign ( 'DEPT_NAME' , $data[ 'dept_name' ] );
			$this -> show -> assign ( 'USER_NAME' , $data[ 'user_name' ] );
			$this -> show -> assign ( 'DATE' , date ( 'Y-m-d' , $data[ 'date' ] ) );
			$this -> show -> assign ( 'ID' , $_GET[ 'orderid' ] );
			$this -> show -> assign ( 'list' , $this -> model_order_info ( $_GET[ 'orderid' ] ) );
			$this -> show -> display ( 'device_quit-order-info' );
		} else
		{
			showmsg ( '�Ƿ�������' );
		}
	}
	/**
	 * �˿���ϸ�豸�б�
	 */
	function c_info_list ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_info_list ( ) );
		$this -> show -> display ( 'device_quit-info-list' );
	}
	/**
	 * �����˿�
	 */
	function c_apply ( )
	{
		$device = new model_device_stock ( );
		$this -> show -> assign ( 'select_type' , $device -> select_type ( ) );
		$this -> show -> display ( 'device_quit-apply' );
	}
	/**
	 * ��������
	 */
	function c_batch_search ( )
	{
		$info_list = $this -> model_batch_search ( );
		$info_list = str_replace ( '<td>�������</td>' , '<td>�������</td><td>�˿�����</td><td>�˿�ԭ��</td>' , $info_list );
		$info_list = preg_replace ( '/<td width="80" id="amount_(.*?)">(.*?)<\/td><td width="80" id="borrows_(.+?)">(.+?)<\/td>/e' , '\'<td id="amount_\\1">\\2</td><td width="80" id="borrows_\\3">\\4</td>
		 <td class="amount"><input type="text" size="5" name="amount[\\1]" onKeyUp="value=this.value.replace(/\\D/g,\\\'\\\');
		 checkmax(\\1,this)" value="\'.' . 'intval(\\2-\\4)' . '.\'"/></td><td><input type="text" size="12" name="notes[\\1]" value="" /></td>\'' , $info_list );
		
		/*	
		$info_list= preg_replace ( '/<td width="80" id="amount_(.*?)">(.*?)<\/td><td width="80" id="borrows_(.+?)">(.+?)<\/td>/' , '<td id="amount_${1}">${2}</td><td width="80" id="borrows_(${3}">${4}</td>
											  	<td class="amount"><input type="text" size="5" name="amount[${1}]" onKeyUp="value=this.value.replace(/\\D/g,\'\');checkmax(${1},this)" value="${2}"/></td>
											  	<td><input type="text" size="12" name="notes[${1}]" value="" /></td>' , $info_list );
			*/
		$this -> show -> assign ( 'list' , $info_list );
		$this -> show -> display ( 'device_quit-batch-search' );
	}
	/**
	 * ��������б�
	 */
	function c_search_list ( )
	{
		if ( $_POST[ 'id' ] )
		{
			$stock = new model_device_stock ( );
			$data = $stock -> model_device_info ( $_POST[ 'id' ] );
			$data = str_replace ( '<td>�������</td>' , '<td>�˿�����</td><td>�˿�ԭ��</td>' , $data );
			$data = str_replace ( '<td><input type="checkbox"' , '<td style="display:none;"><input type="hidden"' , $data );
			preg_match_all ( '/<td width="80" id="amount_(.+?)">(.+?)<\/td>/' , $data , $arr );
			if ( $arr[ 0 ] )
			{
				foreach ( $arr[ 0 ] as $key => $val )
				{
					$data = str_replace ( '<td width="80" id="amount_' . $arr[ 1 ][ $key ] . '">' . $arr[ 2 ][ $key ] . '</td>' , '<td width="80" id="amount_' . $arr[ 1 ][ $key ] . '">' . $arr[ 2 ][ $key ] . '</td>
										<td><input type="hidden" name="amount[' . ( $arr[ 1 ][ $key ] ) . ']" value="' . $_POST[ 'amount' ][ ( $arr[ 1 ][ $key ] ) ] . '" />' . $_POST[ 'amount' ][ ( $arr[ 1 ][ $key ] ) ] . '</td>
										<td><input type="hidden" name="notse[' . ( $arr[ 1 ][ $key ] ) . ']" value="' . ( $_POST[ 'notes' ][ ( $arr[ 1 ][ $key ] ) ] ? $_POST[ 'notes' ][ ( $arr[ 1 ][ $key ] ) ] : $_POST[ 'temp_notes' ] ) . '" />' . ( $_POST[ 'notes' ][ ( $arr[ 1 ][ $key ] ) ] ? $_POST[ 'notes' ][ ( $arr[ 1 ][ $key ] ) ] : $_POST[ 'temp_notes' ] ) . '</td>' , $data );
				
				}
			}
			$data = preg_replace ( '/<td width="80" id="borrows_(.+?)">(.+?)<\/td>/' , '' , $data );
			$gl = new includes_class_global ( );
			$dept_name = $gl -> GetUserinfo ( $_SESSION[ 'USER_ID' ] , 'dept_name' );
			$this -> show -> assign ( 'DEPT_NAME' , $dept_name );
			unset ( $gl );
			$this -> tbl_name = 'device_quit_order';
			$row = $this -> find ( null , null , 'id' );
			$this -> show -> assign ( 'ID' , ( $row[ 'id' ] + 1 ) );
			$this -> show -> assign ( 'USER_NAME' , $_SESSION[ 'USER_ID' ] );
			$this -> show -> assign ( 'DATE' , date ( 'Y-m-d' ) );
			$this -> show -> assign ( 'list' , $data );
			$this -> show -> display ( 'device_quit-batch-search-list' );
		
		}
	}
	/**
	 * �����˿ⵥ
	 */
	function c_save_order ( )
	{
		if ( $_POST[ 'id' ] )
		{
			if ( $this -> model_save_order ( $_POST ) )
			{
				showmsg ( '����ɹ���' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '����ʧ�ܣ��������Ա��ϵ��' );
			}
		} else
		{
			showmsg ( '�Ƿ�������' );
		}
	}
	/**
	 * ���豸�˿�
	 */
	function c_cancel_info ( )
	{
		if ( $_GET[ 'type' ] == 'save' )
		{
			if ( $this -> model_cancel_info ( $_GET[ 'id' ] , $_GET[ 'key' ] ) )
			{
				showmsg ( '�����ɹ���' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '����ʧ�ܣ��������Ա��ϵ��' , 'self.parent.location.reload();' , 'button' );
			}
		} else
		{
			showmsg ( '��ȷ��Ҫȡ�����豸�˿���' , "location.href='?model=device_quit&action=cancel_info&type=save&id=" . $_GET[ 'id' ] . "&key=" . $_GET[ 'key' ] . "'" , 'button' );
		}
	}
	
	function c_cancel_order ( )
	{
		if ( $_GET[ 'type' ] == 'save' )
		{
			if ( $this -> model_cancel_order ( $_GET[ 'id' ] , $_GET[ 'key' ] ) )
			{
				showmsg ( '�����ɹ���' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '����ʧ�ܣ��������Ա��ϵ��' , 'self.parent.location.reload();' , 'button' );
			}
		} else
		{
			showmsg ( '��ȷ��Ҫ�Ѹõ��������豸ȡ���˿���?' , "location.href='?model=device_quit&action=cancel_order&type=save&id=" . $_GET[ 'id' ] . "&key=" . $_GET[ 'key' ] . "'" , 'button' );
		}
	}
}

?>