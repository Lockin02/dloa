<?php
class controller_administration_overtime_record extends model_administration_overtime_record
{
	public $show;
	
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> show = new show ( );
		$this -> show -> path = "administration/overtime/";
	
	}
	
	function c_index ( )
	{
		$this -> c_add ( );
	}
	/**
	 * 管理列表
	 */
	function c_list ( )
	{
		$this -> show -> assign ( 'select_dept' , $this -> model_dept_select ( $_GET[ 'dept_id' ] ) );
		$this -> show -> assign ( 'select_station' , $this -> model_station_select ( $_GET[ 'station' ] ) );
		$this -> show -> assign ( 'work_selected_1' , ( $_GET[ 'work' ] == '20:15' ? 'selected' : '' ) );
		$this -> show -> assign ( 'work_selected_2' , ( $_GET[ 'work' ] == '21:30' ? 'selected' : '' ) );
		$this -> show -> assign ( 'start_date' , $_GET[ 'start_date' ] );
		$this -> show -> assign ( 'end_date' , $_GET[ 'end_date' ] );
		$this -> show -> assign ( 'list' , $this -> model_list ( ) );
		$this -> show -> display ( 'record-list' );
	}
	
	function c_mylist ( )
	{
		$this -> show -> assign ( 'select_station' , $this -> model_station_select ( $_GET[ 'station' ] ) );
		$this -> show -> assign ( 'work_selected_1' , ( $_GET[ 'work' ] == '20:15' ? 'selected' : '' ) );
		$this -> show -> assign ( 'work_selected_2' , ( $_GET[ 'work' ] == '21:30' ? 'selected' : '' ) );
		$this -> show -> assign ( 'start_date' , $_GET[ 'start_date' ] );
		$this -> show -> assign ( 'end_date' , $_GET[ 'end_date' ] );
		$this -> show -> assign ( 'list' , $this -> model_list ( 'user' ) );
		$this -> show -> display ( 'record-mylist' );
	}
	function c_add ( )
	{
		if ( $_POST )
		{
			if ( $this -> model_add ( $_POST ) )
			{
				showmsg ( '延迟下班申请提交成功！' );
			} else
			{
				showmsg ( '延迟下班申请提交失败，请与管理员联系！' );
			}
		} else
		{
			$rs = $this -> find ( array ( 
											'userid' => $_SESSION[ 'USER_ID' ] 
			) , 'id desc' , 'station' );
			$week = sprintf ( "%s" , substr ( "日一二三四五六" , date ( "w" ) * 2 , 2 ) );
			$this -> show -> assign ( 'date' , date ( 'Y年m月d日' ) );
			$this -> show -> assign ( 'week' , $week );
			$this -> show -> assign ( 'none' , ( $week != '五' ? 'none' : '' ) );
			$satur_date = date ( 'Y-m-d' , strtotime ( '+' . ( 6 - date ( "w" ) . ' day' ) ) );
			$sun_date = date ( 'Y-m-d' , strtotime ( '+' . ( 6 - date ( "w" ) + 1 ) . ' day' ) );
			$this -> show -> assign ( 'satur_date' , $satur_date );
			$this -> show -> assign ( 'sun_date' , $sun_date );
			$this -> show -> assign ( 'station_select' , $this -> model_station_select ( $rs[ 'station' ] ) );
			$this -> show -> display ( 'add-record' );
		
		}
	}
	
	function c_add_overtime ( )
	{
		if ( $_POST )
		{
			if ( $this -> model_add ( $_POST ) )
			{
				showmsg ( '延迟下班申请提交成功！' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '延迟下班申请提交失败，请与管理员联系！' );
			}
		} else
		{
			if ( time ( ) < strtotime ( date ( 'Y-m-d 16:30:00' ) ) )
			{
				$rs = $this -> find ( array ( 
												'userid' => $_SESSION[ 'USER_ID' ] 
				) , 'id desc' , 'station' );
				$week = sprintf ( "%s" , substr ( "日一二三四五六" , date ( "w" ) * 2 , 2 ) );
				$this -> show -> assign ( 'date' , date ( 'Y-m-d' ) );
				$this -> show -> assign ( 'week' , $week );
				$this -> show -> assign ( 'none' , ( $week != '五' ? 'none' : '' ) );
				$satur_date = date ( 'Y-m-d' , strtotime ( '+' . ( 6 - date ( "w" ) . ' day' ) ) );
				$sun_date = date ( 'Y-m-d' , strtotime ( '+' . ( 6 - date ( "w" ) + 1 ) . ' day' ) );
				$this -> show -> assign ( 'satur_date' , $satur_date );
				$this -> show -> assign ( 'sun_date' , $sun_date );
				$this -> show -> assign ( 'userid' , $_SESSION[ 'USER_ID' ] );
				$this -> show -> assign ( 'username' , $_SESSION[ 'USERNAME' ] );
				$this -> show -> assign ( 'station_select' , $this -> model_station_select ( $rs[ 'station' ] ) );
				$this -> show -> assign ( 'woption' , '' );
				if ( $week == '六' || $week == '日' )
				{
					$this -> show -> assign ( 'woption' , '<option value="17:30">17:30</option>' );
				}
				$this -> show -> display ( 'add' );
			} else
			{
				showmsg ( '对不起，提交延迟下班的时间已过，如须申请请联系您的部门助理。' , 'self.parent.location.reload();' , 'button' );
			}
		
		}
	}
	/**
	 * 新增延迟下班记录
	 */
	function c_new_add ( )
	{
		global $func_limit;
		if ( $func_limit[ '增删改' ] )
		{
			if ( $_POST )
			{
				if ( $this -> model_add ( $_POST ) )
				{
					showmsg ( '增加延迟下班记录成功！' , 'self.parent.location.reload();' , 'button' );
				} else
				{
					showmsg ( '操作失败，请与管理员联系！' );
				}
			} else
			{
				$this -> show -> assign ( 'station_select' , $this -> model_station_select ( ) );
				$this -> show -> display ( 'new-add' );
			}
		} else
		{
			showmsg ( '对不起，你没有权限执行改操作！' );
		}
	}
	
	function c_del ( )
	{
	
	}
	
	function c_edit ( )
	{
		if ( $_POST )
		{
		
		} else
		{
		
		}
	}
	
	function c_get_userid ( )
	{
		echo $this -> get_table_fields ( 'user' , "user_name='" . mb_iconv ( $_POST[ 'username' ] ) . "'" , 'user_id' );
	}
	
	function c_export_data ( )
	{
		return $this -> export_data ( );
	}
}

?>