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
	 * �豸����ά������
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
	 * �豸����ά�������б�
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
			showmsg ( '��ѯ���ݲ���Ϊ�գ�' );
		}
	}
	function c_shift_list ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_shift_list ( ) );
		$this -> show -> display ( 'device_shift-list' );
	}
	/**
	 * �豸ά�������б�
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
	 * ����ά�޵�
	 *
	 */
	function c_save_order ( )
	{
		if ( $this -> model_save_order ( ) )
		{
			showmsg ( '����ɹ���' , 'self.parent.location.reload();' , 'button' );
		} else
		{
			showmsg ( '����ʧ�ܣ�' , 'self.parent.location.reload();' , 'button' );
		}
	}
	/**
	 * ά�޵��б�
	 *
	 */
	function c_order_list ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_order_list ( ) );
		$this -> show -> display ( 'device_service-order-list' );
	}
	/**
	 * ά�޵���ϸ��Ϣ
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
	 * ���豸ά�޼�¼
	 */
	function c_info_list ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_info ( $_GET[ 'tid' ] ) );
		$this -> show -> display ( 'device_service-info-list' );
	}
}