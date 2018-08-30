<?php
class controller_device_service extends model_device_service
{
	public $show;
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> show = new show ( );
	}
	
	/**
	 * 设备批量维修申请
	 *
	 */
	function c_device_service_apply ( )
	{
		if ( $_GET[ 'type' ] == 'list' )
		{
			$this -> c_search ( );
		} elseif ( $_GET[ 'type' ] == 'save_list' )
		{
			$this -> c_save_list ( );
		} elseif ( $_GET[ 'type' ] == 'save_order' )
		{
			$this -> c_save_order ( );
		} else
		{
			$this -> show -> display ( 'device_service-apply' );
		}
	}
	/**
	 * 设备批量维修申请列表
	 */
	function c_search ( )
	{
		if ( $_POST[ 'content' ] )
		{
			$this -> show -> assign ( 'operator' , $_SESSION[ 'USERNAME' ] );
			$this -> show -> assign ( 'operatorid' , $_SESSION[ 'USER_ID' ] );
			$this -> show -> assign ( 'date' , date ( 'Y-m-d' ) );
			$this -> show -> assign ( 'list' , $this -> model_search ( ) );
			$this -> show -> display ( 'device_service-search-list' );
		} else
		{
			showmsg ( '查询内容不能为空！' );
		}
	}
	function c_shift_list ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_shift_list ( ) );
		$this -> show -> display ( 'device_shift-list' );
	}
	/**
	 * 设备维修最终列表
	 */
	function c_save_list ( )
	{
		$this -> show -> assign ( 'username' , $_POST[ 'username' ] );
		$this -> show -> assign ( 'budget_date' , $_POST[ 'budget_date' ] );
		$this -> show -> assign ( 'operator' , $_POST[ 'operator' ] );
		$this -> show -> assign ( 'operatorid' , $_POST[ 'operatorid' ] );
		$this -> show -> assign ( 'areaid' , $_POST[ 'area' ] );
		$this -> show -> assign ( 'email' , $_POST[ 'email' ] );
		$this -> show -> assign ( 'date' , date ( 'Y-m-d' ) );
		$this -> show -> assign ( 'list' , $this -> model_save_list ( ) );
		$this -> show -> display ( 'device_service-save-list' );
	}
	/**
	 * 保存维修单
	 *
	 */
	function c_save_order ( )
	{
		if ( $this -> model_save_order ( ) )
		{
			showmsg ( '保存成功！' , 'self.parent.location.reload();' , 'button' );
		} else
		{
			showmsg ( '保存失败！' , 'self.parent.location.reload();' , 'button' );
		}
	}
	/**
	 * 维修单列表
	 *
	 */
	function c_order_list ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_order_list ( ) );
		$this -> show -> display ( 'device_service-order-list' );
	}
	/**
	 * 维修单详细信息
	 */
	function c_order_info ( )
	{
		$gl = new includes_class_global ( );
		$this -> tbl_name = 'device_service_order';
		$order = $this -> find ( 'id=' . $_GET[ 'orderid' ] );
		$this -> show -> assign ( 'username' , $order[ 'username' ] );
		$this -> show -> assign ( 'budget_date' , date ( 'Y-m-d' , $order[ 'date' ] ) );
		$user_name = $gl -> GetUserinfo ( $order[ 'operator' ] , 'user_name' );
		$this -> show -> assign ( 'operator' , $user_name );
		unset ( $gl , $order );
		$this -> show -> assign ( 'list' , $this -> model_order_info ( ) );
		$this -> show -> display ( 'device_service-order-info' );
	}
	/**
	 * 单设备维修记录
	 */
	function c_info_list ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_info ( $_GET[ 'tid' ] ) );
		$this -> show -> display ( 'device_service-info-list' );
	}
}