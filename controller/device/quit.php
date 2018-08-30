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
	 * 退库单列表
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
	 * 退库单详细设置信息
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
			showmsg ( '非法参数！' );
		}
	}
	/**
	 * 退库详细设备列表
	 */
	function c_info_list ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_info_list ( ) );
		$this -> show -> display ( 'device_quit-info-list' );
	}
	/**
	 * 申请退库
	 */
	function c_apply ( )
	{
		$device = new model_device_stock ( );
		$this -> show -> assign ( 'select_type' , $device -> select_type ( ) );
		$this -> show -> display ( 'device_quit-apply' );
	}
	/**
	 * 批量搜索
	 */
	function c_batch_search ( )
	{
		$info_list = $this -> model_batch_search ( );
		$info_list = str_replace ( '<td>借出数量</td>' , '<td>借出数量</td><td>退库数量</td><td>退库原因</td>' , $info_list );
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
	 * 搜索结果列表
	 */
	function c_search_list ( )
	{
		if ( $_POST[ 'id' ] )
		{
			$stock = new model_device_stock ( );
			$data = $stock -> model_device_info ( $_POST[ 'id' ] );
			$data = str_replace ( '<td>借出数量</td>' , '<td>退库数量</td><td>退库原因</td>' , $data );
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
	 * 保存退库单
	 */
	function c_save_order ( )
	{
		if ( $_POST[ 'id' ] )
		{
			if ( $this -> model_save_order ( $_POST ) )
			{
				showmsg ( '保存成功！' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '保存失败，请与管理员联系！' );
			}
		} else
		{
			showmsg ( '非法参数！' );
		}
	}
	/**
	 * 单设备退库
	 */
	function c_cancel_info ( )
	{
		if ( $_GET[ 'type' ] == 'save' )
		{
			if ( $this -> model_cancel_info ( $_GET[ 'id' ] , $_GET[ 'key' ] ) )
			{
				showmsg ( '操作成功！' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败，请与管理员联系！' , 'self.parent.location.reload();' , 'button' );
			}
		} else
		{
			showmsg ( '您确定要取消该设备退库吗？' , "location.href='?model=device_quit&action=cancel_info&type=save&id=" . $_GET[ 'id' ] . "&key=" . $_GET[ 'key' ] . "'" , 'button' );
		}
	}
	
	function c_cancel_order ( )
	{
		if ( $_GET[ 'type' ] == 'save' )
		{
			if ( $this -> model_cancel_order ( $_GET[ 'id' ] , $_GET[ 'key' ] ) )
			{
				showmsg ( '操作成功！' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败，请与管理员联系！' , 'self.parent.location.reload();' , 'button' );
			}
		} else
		{
			showmsg ( '您确定要把该单的所有设备取消退库吗?' , "location.href='?model=device_quit&action=cancel_order&type=save&id=" . $_GET[ 'id' ] . "&key=" . $_GET[ 'key' ] . "'" , 'button' );
		}
	}
}

?>