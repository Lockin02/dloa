<?php
class controller_device_borrow extends model_device_borrow
{
	public $show;
	//***************************构造函数**************************
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> show = new show ( );
	}
	
	//****************************数据处理***************************
	/**
	 * 保存批量借出
	 */
	function c_batch_borrow_save ( )
	{
		if ( $this -> model_batch_borrow_save ( $_POST ) )
		{
			showmsg ( '操作成功！' , '?model=device_borrow&action=showlist' );
		} else
		{
			showmsg ( '操作失败！' );
		}
	}
	/**
	 * 保存归还列表
	 *
	 */
	function c_save_return_list ( )
	{
		if ( $this -> model_save_return_list ( $_POST ) )
		{
			showmsg ( '操作成功！' , 'self.parent.location.reload();' , 'button' );
		} else
		{
			showmsg ( '操作失败' , 'self.parent.location.reload();' , 'button' );
		}
	}
	/**
	 * 保存批量归还
	 */
	function c_save_batch_return ( )
	{
		if ( $this -> model_save_batch_return ( ) )
		{
			showmsg ( '操作成功！' , '?model=device_borrow&action=return_order_list' );
		} else
		{
			showmsg ( '操作失败！' );
		}
	}
	//****************************显示数据****************************
	/**
	 * 默认访问
	 *
	 */
	function c_index ( )
	{
		$this -> showlist ( );
	}
	/**
	 * 借出单列表
	 */
	function c_showlist ( )
	{
		if ( $_GET[ 'status' ] == 1 )
		{
			$selected_1 = 'selected';
		} elseif ( $_GET[ 'status' ] == 2 )
		{
			$selected_2 = 'selected';
		}
		
		$arr = $_GET[ 'sort' ] ? explode ( '-' , $_GET[ 'sort' ] ) : '';
		$find = $arr[ 0 ];
		$sotr = $arr[ 1 ];
		$this -> show -> assign ( 'id_sort_img' , sort_img ( 'a.id' , $find == 'a.id' ? $sotr : '' ) );
		$this -> show -> assign ( 'projectname_sort_img' , sort_img ( 'c.name' , $find == 'c.name' ? $sotr : '' ) );
		$this -> show -> assign ( 'projectno_sort_img' , sort_img ( 'c.number' , ( $find == 'c.number' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'b2.user_name_sort_img' , sort_img ( 'b2.user_name' , ( $find == 'b2.user_name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'operatorname_sort_img' , sort_img ( 'operatorname' , ( $find == 'operatorname' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'user_name_sort_img' , sort_img ( 'b.user_name' , ( $find == 'b.user_name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'dept_name_sort_img' , sort_img ( 'd.dept_name' , ( $find == 'd.dept_name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'amount_sort_img' , sort_img ( 'a.amount' , ( $find == 'a.amount' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'date_sort_img' , sort_img ( 'a.date' , ( $find == 'a.date' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'confirm_sort_img' , sort_img ( 'a.confirm' , ( $find == 'a.confirm' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'areturn_sort_img' , sort_img ( 'areturn' , ( $find == 'areturn' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'notes_sort_img' , sort_img ( 'notes' , ( $find == 'notes' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'selected_1' , $selected_1 );
		$this -> show -> assign ( 'selected_2' , $selected_2 );
		$this -> show -> assign ( 'selected_3' , ( $_GET[ 'status' ] == 3 ? 'selected' : '' ) );
		$this -> show -> assign ( 'project_id' , $_GET[ 'project_id' ] );
		$this -> show -> assign ( 'project_name' , $_GET[ 'project_name' ] );
		$this -> show -> assign ( 'username' , $_GET[ 'username' ] );
		$this -> show -> assign ( 'userid' , $_GET[ 'userid' ] );
		$this -> show -> assign ( 'start_date' , $_GET[ 'start_date' ] );
		$this -> show -> assign ( 'end_date' , $_GET[ 'end_date' ] );
		$this -> show -> assign ( 'list' , $this -> model_showlist ( ) );
		$this -> show -> display ( 'device_borrowlist' );
	}
	/**
	 * 项目列表
	 */
	function c_project_list ( )
	{
		$arr = $_GET[ 'sort' ] ? explode ( '-' , $_GET[ 'sort' ] ) : '';
		$find = $arr[ 0 ];
		$sotr = $arr[ 1 ];
		
		$this -> show -> assign ( 'id_sort_img' , sort_img ( 'sortnum' , $find == 'sortnum' ? $sotr : '' ) );
		$this -> show -> assign ( 'project_name_sort_img' , sort_img ( 'project_name' , ( $find == 'project_name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'projectno_sort_img' , sort_img ( 'number' , $find == 'number' ? $sotr : '' ) );
		$this -> show -> assign ( 'user_name_sort_img' , sort_img ( 'user_name' , ( $find == 'user_name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'count_sort_img' , sort_img ( 'count' , ( $find == 'count' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'num_sort_img' , sort_img ( 'num' , ( $find == 'num' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'return_num_sort_img' , sort_img ( 'return_num' , ( $find == 'return_num' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'surplus_sort_img' , sort_img ( 'surplus' , ( $find == 'surplus' ? $sotr : '' ) ) );
		
		$this -> show -> assign ( 'list' , $this -> model_project_list ( ) );
		$this -> show -> display ( 'device_borrow-project-list' );
	}
	
	function c_project_order_list ( )
	{
		$arr = $_GET[ 'sort' ] ? explode ( '-' , $_GET[ 'sort' ] ) : '';
		$find = $arr[ 0 ];
		$sotr = $arr[ 1 ];
		
		$this -> show -> assign ( 'id_sort_img' , sort_img ( 'a.id' , ( $find == 'a.id' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'user_name_sort_img' , sort_img ( 'c.user_name' , ( $find == 'c.user_name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'amount_sort_img' , sort_img ( 'a.amount' , ( $find == 'a.amount' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'return_num_sort_img' , sort_img ( 'b.return_num' , ( $find == 'b.return_num' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'date_sort_img' , sort_img ( 'a.date' , ( $find == 'a.date' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'confirm_sort_img' , sort_img ( 'a.confirm' , $find == 'a.confirm' ? $sotr : '' ) );
		$this -> show -> assign ( 'areturn_sort_img' , sort_img ( 'areturn' , ( $find == 'areturn' ? $sotr : '' ) ) );
		
		$this -> show -> assign ( 'list' , $this -> model_project_order_list ( $_GET[ 'project_id' ] ) );
		$this -> show -> display ( 'device_borrow-project-order-list' );
	}
	
	/**
	 * 单设备详细借还记录
	 */
	function c_borrow_info_list ( )
	{
		global $func_limit;
		$dept_id=$_GET[ 'deptId' ] ? str_replace ( 'undefined' , '' , $_GET[ 'deptId' ] ) : $_POST[ 'deptId' ];
		$gl = new includes_class_global ( );
		$dept_data = $gl -> GetDept ( $func_limit[ '管理部门' ] );
		if ( $dept_data )
		{
			$dept_option = '';
			foreach ( $dept_data as $rs )
			{
				if ( $rs[ 'DEPT_ID' ] == 1 && $_SESSION[ 'USER_ID' ] != 'admin' )
					continue;
				if ( $rs[ 'DEPT_ID' ] == $dept_id )
				{
					$dept_option .= '<option selected value="' . $rs[ 'DEPT_ID' ] . '">' . $rs[ "DEPT_NAME" ] . '</option>';
				} else
				{
					$dept_option .= '<option value="' . $rs[ 'DEPT_ID' ] . '">' . $rs[ "DEPT_NAME" ] . '</option>';
				}
			}
		}
		$stock = new model_device_stock ( );
		$this -> show -> assign ( 'select_dept' , $dept_option );
		$this -> show -> assign ( 'select_type' , $stock -> select_type ( $_GET[ 'typeid' ] ) );
		$this -> show -> assign ( 'start_date' , ( $_GET[ 'start_date' ] ? $_GET[ 'start_date' ] : $_POST[ 'start_date' ] ) );
		$this -> show -> assign ( 'end_date' , ( $_GET[ 'end_date' ] ? $_GET[ 'end_date' ] : $_POST[ 'end_date' ] ) );
		$this -> show -> assign ( 'typeid' , $_GET[ 'typeid' ] );
		$this -> show -> assign ( 'list_id' , $_GET[ 'list_id' ] );
		$this -> show -> assign ( 'project_id' , $_GET[ 'project_id' ] );
		$this -> show -> assign ( 'project_name' , $_GET[ 'project_name' ] );
		$this -> show -> assign ( 'username' , ( $_GET[ 'username' ] ? $_GET[ 'username' ] : $_POST[ 'username' ] ) );
		$this -> show -> assign ( 'userid' , ( $_GET[ 'userid' ] ? $_GET[ 'userid' ] : $_POST[ 'userid' ] ) );
		$this -> show -> assign ( 'list' , $this -> model_borrow_info_list ( ) );
		$this -> show -> display ( 'device_borrow-info-list' );
	}
	/**
	 * 借出单类别设备
	 */
	function c_show_borrow_type_info ( )
	{
		if ( $_GET[ 'typeid' ] )
		{
			$stock = new model_device_stock ( );
			$device = $stock -> model_get_device ( $_GET[ 'typeid' ] );
			if ( $device )
			{
				foreach ( $device as $row )
				{
					$str .= '<option value="' . $row[ 'id' ] . '">' . $row[ 'device_name' ] . '</option>';
				}
			}
			$this -> show -> assign ( 'select_device' , $str );
			$this -> show -> assign ( 'title' , $_GET[ 'typename' ] );
			$this -> show -> assign ( 'userid' , $_GET[ 'userid' ] );
			$this -> show -> assign ( 'username' , $_GET[ 'username' ] );
			$this -> show -> assign ( 'start_date' , $_GET[ 'start_date' ] );
			$this -> show -> assign ( 'end_date' , $_GET[ 'end_date' ] );
			$this -> show -> assign ( 'fixed_title' , preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> get_fixed_field_name ( $_GET[ 'typeid' ] ) ) );
			$this -> show -> assign ( 'field_title' , preg_replace ( '/\<a(.*?)<\/a>/i' , '' , $stock -> model_show_field_name ( $_GET[ 'typeid' ] ) ) );
			$this -> show -> assign ( 'list' , $this -> model_borrow_type_info ( $_GET[ 'typeid' ] , true ) );
			$this -> show -> display ( 'device_borrow-type-info' );
		}
	}
	
	function c_noedevice_list ( )
	{
		if ( intval ( $_GET[ 'id' ] ) )
		{
			$this -> show -> assign ( 'list' , $this -> model_noedevice_list ( $_GET[ 'id' ] ) );
			$this -> show -> display ( 'device_borrow-noedevice-list' );
		}
	}
	/**
	 * 按单号显示借出设备列表
	 *
	 */
	function c_show_order_list ( )
	{
		/*$arr = $_GET['sort'] ? explode ( '-', $_GET['sort'] ) : '';
		$find = $arr[0];
		$sotr = $arr[1];*/
		$this -> show -> assign ( 'list' , $this -> model_borrow_order_info_list ( $_GET[ 'id' ] , true ) );
		$rs = $this -> get_borrow_order_info ( $_GET[ 'id' ] );
		$this -> show -> assign ( 'del_link' , ( $rs[ 'confirm' ] != 1 ? ' <input type="submit" onclick="$(\'#type\').val(1);" value="移除选择中设备"　/> ' : '' ) );
		$this -> show -> assign ( 'turn_button' , ( $rs[ 'confirm' ] == 1 ? ' <input type="submit" onclick="$(\'#type\').val(3);" value="借用归属转换"　/> ' : '' ) );
		$this -> show -> display ( 'device_borrow-order-list' );
	}
	/**
	 * 项目借用设备列表
	 *
	 */
	function c_show_project_list ( )
	{
		$data = $this -> model_get_project_type ( $_GET[ 'project_id' ] );
		if ( $data )
		{
			foreach ( $data as $key => $row )
			{
				$str .= '<option value="' . $row[ 'typeid' ] . '">' . $row[ 'typename' ] . '</option>';
			}
		}
		$this -> show -> assign ( 'start_date' , $_GET[ 'start_date' ] );
		$this -> show -> assign ( 'end_date' , $_GET[ 'end_date' ] );
		$this -> show -> assign ( 'type_select' , $str );
		$this -> show -> assign ( 'list' , $this -> model_show_project_list ( $_GET[ 'project_id' ] ) );
		
		//$this->show->assign('del_link','');
		$this -> show -> display ( 'device_borrow-project-info' );
	}
	/**
	 * 归还设备列表
	 *
	 */
	function c_return_device_list ( )
	{
		global $func_limit;
		if ( $_POST[ 'type' ] == 1 )
		{
			if ( $this -> model_del_order_info ( ) )
			{
				showmsg ( '移除成功！' , 'self.parent.location.reload();' , 'button' );
			}
		} else if ( $_POST[ 'type' ] == 3 )
		{
			$rs = $this -> get_borrow_order_info ( $_GET[ 'orderid' ] );
			if ( $rs[ 'project_id' ] )
			{
				$self = $this -> get_table_fields ( 'project_info' , 'id=' . $rs[ 'project_id' ] , 'name' );
				$to = $this -> get_table_fields ( 'user' , "user_id='" . $rs[ 'userid' ] . "'" , 'user_name' );
				$target = 'user';
			} else
			{
				$self = $this -> get_table_fields ( 'user' , "user_id='" . $rs[ 'userid' ] . "'" , 'user_name' );
				$to = '项目：<input type="hidden" id="project_id" name="project_id" value="" /><input type="text" size="50" id="xm_name" name="xm_name" value=""/> <span id="manager_name"></span>';
				$target = 'project';
			}
			foreach ( $rs as $key => $val )
			{
				if ( $key == 'manager' )
					$val = str_replace ( '--' , '' , $val );
				$this -> show -> assign ( $key , $val );
			}
			$this -> show -> assign ( 'operatorid' , $_SESSION[ 'USER_ID' ] );
			$this -> show -> assign ( 'target' , $target );
			$this -> show -> assign ( "self" , $self );
			$this -> show -> assign ( "To" , $to );
			$this -> show -> assign ( 'list' , $this -> model_borrow_info_turn ( $_POST[ 'id' ] ) );
			$this -> show -> display ( 'device_borrow-info-turn' );
		} else
		{
			$gl = new includes_class_global ( );
			$arr = $gl -> area_call ( $func_limit[ '管理区域' ] );
			if ( $arr )
			{
				foreach ( $arr as $rs )
				{
					$str .= '<option value="' . $rs[ 'ID' ] . '">' . $rs[ 'Name' ] . '</option>';
				}
			}
			$area = '<select id="areaid" name="areaid">' . $str . '</select>';
			$this -> show -> assign ( 'area' , $area );
			$this -> show -> assign ( 'USERNAME' , $_SESSION[ 'USERNAME' ] );
			$this -> show -> assign ( 'operatorid' , $_SESSION[ 'USER_ID' ] );
			$this -> show -> assign ( 'list' , $this -> model_return_orders_list ( ) );
			$this -> show -> display ( 'device_return-list' );
		}
	}
	
	function c_rutn ( )
	{
		if ( $_POST )
		{
			if ( $this -> model_rutn ( $_POST ) )
			{
				showmsg ( '操作成功！' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败，请与管理员联系！' , 'self.parent.location.reload();' , 'button' );
			}
		}
	}
	/**
	 * 显示最终归还结果列表
	 */
	function c_show_return_list ( )
	{
		$gl = new includes_class_global ( );
		$user = $gl -> GetUserinfo ( $_POST[ 'userid' ] , array ( 
																	
																	'user_name' , 
																	'dept_name' 
		) );
		
		$user_name = $user[ 'user_name' ];
		$dept_name = $user[ 'dept_name' ];
		$area = $gl -> get_area ( $_POST[ 'areaid' ] );
		
		$this -> show -> assign ( 'username' , $user[ 'user_name' ] );
		$this -> show -> assign ( 'dept_name' , $user[ 'dept_name' ] );
		$this -> show -> assign ( 'USERNAME' , $_SESSION[ 'USERNAME' ] );
		$this -> show -> assign ( 'areaid' , $_POST[ 'areaid' ] );
		$this -> show -> assign ( 'area' , $area );
		unset ( $gl );
		
		$str .= '<input type="hidden" name="user_name" value="' . $user_name . '" />';
		$str .= '<input type="hidden" name="dept_name" value="' . $dept_name . '" />';
		$str .= '<input type="hidden" name="areaname" value="' . $area . '" />';
		
		$this -> show -> assign ( 'hidden' , $str );
		$this -> show -> assign ( 'userid' , $_POST[ 'userid' ] );
		$this -> show -> assign ( 'dept_id' , $_POST[ 'dept_id' ] );
		$this -> show -> assign ( 'createDate' , $_POST[ 'createDate' ] );
		$this -> show -> assign ( 'operatorid' , $_POST[ 'operatorid' ] );
		$this -> show -> assign ( 'email' , $_POST[ 'email' ] );
		$this -> show -> assign ( 'list' , $this -> model_show_return_list ( ) );
		$this -> show -> display ( 'device_show-retrun-list' );
	}
	/**
	 * 归还单列表
	 *
	 */
	function c_return_order_list ( )
	{
		$arr = $_GET[ 'sort' ] ? explode ( '-' , $_GET[ 'sort' ] ) : '';
		$find = $arr[ 0 ];
		$sotr = $arr[ 1 ];
		$this -> show -> assign ( 'id_sort_img' , sort_img ( 'a.id' , ( $find == 'a.id' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'user_name_sort_img' , sort_img ( 'b.user_name' , ( $find == 'b.user_name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'amount_sort_img' , sort_img ( 'a.amount' , ( $find == 'a.amount' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'area_sort_img' , sort_img ( 'd.name' , ( $find == 'd.name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'date_sort_img' , sort_img ( 'a.date' , ( $find == 'a.date' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'c.user_name_sort_img' , sort_img ( 'c.user_name' , ( $find == 'c.user_name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'list' , $this -> model_return_order_list ( ) );
		$this -> show -> display ( 'device_return-order-list' );
	}
	/**
	 * 归还单详细
	 *
	 */
	function c_return_order_info ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_return_order_info_list ( $_GET[ 'id' ] ) );
		$this -> show -> display ( 'device_return-order-info' );
	}
	/**
	 * 批量借出
	 *
	 */
	function c_batch ( )
	{
		$device = new model_device_stock ( );
		$this -> show -> assign ( 'select_type' , $device -> select_type ( ) );
		$this -> show -> display ( 'device_batchborrow' );
	}
	/**
	 * 批量借出列表
	 *
	 */
	function c_batch_search ( )
	{
		global $func_limit;
		$gl = new includes_class_global ( );
		$arr = $gl -> area_call ( $func_limit[ '管理区域' ] );
		if ( $arr )
		{
			foreach ( $arr as $rs )
			{
				$str .= '<option value="' . $rs[ 'ID' ] . '">' . $rs[ 'Name' ] . '</option>';
			}
		}
		$area = '<select id="area" name="area">' . $str . '</select>';
		$this -> show -> assign ( 'area' , $area );
		unset ( $gl , $arr );
		$this -> show -> assign ( 'date' , date ( 'Y-m-d' ) );
		$this -> show -> assign ( 'USERNAME' , $_SESSION[ 'USERNAME' ] );
		$this -> show -> assign ( 'USER_ID' , $_SESSION[ 'USER_ID' ] );
		$this -> show -> assign ( 'list' , $this -> model_batch_search ( ) );
		$this -> show -> display ( 'device_batchlist' );
	}
	/**
	 * 批量归还
	 *
	 */
	function c_show_batch_retrun ( )
	{
		$device = new model_device_stock ( );
		$this -> show -> assign ( 'select_type' , $device -> select_type ( ) );
		$this -> show -> display ( 'device_batch-return' );
	}
	/**
	 * 批量归还列表
	 *
	 */
	function c_batch_order_info_search ( )
	{
		global $func_limit;
		if ( $func_limit[ '管理区域' ] )
		{
			$this -> tbl_name = 'area';
			$area = $this -> findAll ( 'id in(' . $func_limit[ '管理区域' ] . ')' , null , 'ID,Name' );
			foreach ( $area as $rs )
			{
				$str .= '<option value="' . $rs[ 'ID' ] . '">' . $rs[ 'Name' ] . '</option>';
			}
			$area = '<select id="areaid" name="areaid">' . $str . '</select>';
			$this -> show -> assign ( 'area' , $area );
			$this -> show -> assign ( 'operator' , $_SESSION[ 'USERNAME' ] );
			$this -> show -> assign ( 'operatorid' , $_SESSION[ 'USER_ID' ] );
			$this -> show -> assign ( 'createDate' , date('Y-m-d') );
			$this -> show -> assign ( 'list' , $this -> model_batch_order_info_search ( ) );
			$this -> show -> display ( 'device_batch-return-list' );
		} else
		{
			showmsg ( '对不起，您没有任何区域访问权限！' );
		}
	}
	/**
	 * 批量查询归还游览
	 *
	 */
	function c_batch_return_info_list ( )
	{
		$gl = new includes_class_global ( );
		$area = $gl -> get_area ( $_POST[ 'area' ] );
		$this -> show -> assign ( 'area' , $area );
		$this -> show -> assign ( 'user_name' , $_POST[ 'user_name' ] );
		$this -> show -> assign ( 'user_name' , $_POST[ 'username' ] );
		$this -> show -> assign ( 'dept_name' , $_POST[ 'dept_name' ] );
		$this -> show -> assign ( 'operator' , $_POST[ 'operator' ] );
		//===========隐藏部分==============
		$this -> show -> assign ( 'areaid' , $_POST[ 'area' ] );
		$this -> show -> assign ( 'userid' , $_POST[ 'userid' ] );
		$this -> show -> assign ( 'dept_id' , $_POST[ 'dept_id' ] );
		$this -> show -> assign ( 'operatorid' , $_POST[ 'operatorid' ] );
		$this -> show -> assign ( 'email' , $_POST[ 'email' ] );
		
		$this -> show -> assign ( 'list' , $this -> model_batch_return_info_list ( ) );
		$this -> show -> display ( 'device_batch-return-info-list' );
	}
	/**
	 * 显示最终批量借出列表
	 *
	 */
	function c_showborrowlist ( )
	{
		if ( $_POST )
		{
			$gl = new includes_class_global ( );
			$temp = '';
			foreach ( $_POST as $key => $val )
			{
				if ( $key == 'xm_no' && $val )
				{
					$this -> show -> assign ( $key , '<br />（' . $val . '）' );
				} else
				{
					$this -> show -> assign ( $key , ( $val ? $val : '--' ) );
					if (  ! is_array ( $val ) )
					{
						if ( $key == 'area' )
						{
							$val = $val ? $gl -> get_area ( $val ) : '--';
							$this -> show -> assign ( 'areaname' , $val );
						}
						$temp .= '<input type="hidden" name="_' . $key . '" value="' . $val . '" />';
					}
				}
			}
			$this -> show -> assign ( 'top' , $temp );
		}
		$this -> show -> assign ( 'date' , $_POST['createDate']);
		$data = $this -> model_borrow_info ( $_POST[ 'id' ] );
		preg_match_all ( '/<td id="amount_(.+?)">(.+?)<\/td>/' , $data , $arr );
		preg_match_all ( '/<td id="borrows_(.+?)">(.+?)<\/td>/' , $data , $arb );
		if ( $arr[ 0 ] )
		{
			foreach ( $arr[ 0 ] as $key => $val )
			{
				$target_date = $_POST[ 'target_date' ][ ( $arr[ 1 ][ $key ] ) ] ? $_POST[ 'target_date' ][ ( $arr[ 1 ][ $key ] ) ] : $_POST[ 'targettime' ];
				$data = str_replace ( '<td id="amount_' . $arr[ 1 ][ $key ] . '">' . $arr[ 2 ][ $key ] . '</td>' , '<td id="amount_' . $arr[ 1 ][ $key ] . '">' . $arr[ 2 ][ $key ] . '</td>' , $data );
				$data = str_replace ( '<td id="borrows_' . $arb[ 1 ][ $key ] . '">' . $arb[ 2 ][ $key ] . '</td>' , '<td id="borrows_' . $arb[ 1 ][ $key ] . '">' . $arb[ 2 ][ $key ] . '</td>
									<td><input type="hidden" name="amount[' . ( $arr[ 1 ][ $key ] ) . ']" value="' . $_POST[ 'amount' ][ ( $arr[ 1 ][ $key ] ) ] . '" />' . $_POST[ 'amount' ][ ( $arr[ 1 ][ $key ] ) ] . '</td>
									<td><input type="hidden" name="target_date[' . ( $arr[ 1 ][ $key ] ) . ']" value="' . $target_date . '" />' . $target_date . '</td>
									<td><input type="hidden" name="notse[' . ( $arr[ 1 ][ $key ] ) . ']" value="' . $_POST[ 'notse' ][ ( $arr[ 1 ][ $key ] ) ] . '" />' . $_POST[ 'notse' ][ ( $arr[ 1 ][ $key ] ) ] . '</td>' , $data );
			}
		}
		$data = str_replace ( '<td><input type="checkbox"' , '<td style="display:none;"><input type="hidden"' , $data );
		$data = '<table width="98%" cellspacing="0" cellpadding="0" align="center"><tr><td>共有　' . count ( $_POST[ 'id' ] ) . '　条记录</td></tr></table>' . $data;
		$this -> show -> assign ( 'list' , $data );
		$this -> show -> display ( 'device_showborrowlist' );
	}
	/**
	 * 借单归档
	 */
	function c_archive ( )
	{
		if ( $_GET[ 'save' ] == 'ok' )
		{
			$this -> tbl_name = 'device_borrow_order';
			if ( $this -> update ( array ( 
											
											'id' => $_GET[ 'id' ] , 
											'rand_key' => $_GET[ 'key' ] 
			) , array ( 
						
						'archive' => 1 
			) ) )
			{
				showmsg ( '归档成功！' , 'self.parent.location.reload();' , 'button' );
			}
		} else
		{
			showmsg ( '您确定要将该借单归档吗？' , "location.href='?model=" . $_GET[ 'model' ] . "&action=archive&id=" . $_GET[ 'id' ] . "&key=" . $_GET[ 'key' ] . "&save=ok'" , 'button' );
		}
	}
	
	function c_borrow_apply ( )
	{
		if ( $_POST )
		{
			foreach ( $_POST as $key => $val )
			{
				if ( $key == 'ls' )
				{
					if ( $val == 0 )
					{
						$this -> show -> assign ( 'xm_none' , 'none' );
						$this -> show -> assign ( 'dt_none' , '' );
					} else
					{
						$this -> show -> assign ( 'xm_none' , '' );
						$this -> show -> assign ( 'dt_none' , 'none' );
					}
				} else
				{
					$this -> show -> assign ( $key , $val );
				}
			}
			$data = $this -> model_borrow_info ( $_POST[ 'id' ] );
			preg_match_all ( '/<td id="borrows_(.+?)">(.+?)<\/td>/' , $data , $arr );
			if ( $arr[ 0 ] )
			{
				foreach ( $arr[ 0 ] as $key => $val )
				{
					$data = str_replace ( '<td id="borrows_' . $arr[ 1 ][ $key ] . '">' . $arr[ 2 ][ $key ] . '</td>' , '<td id="borrows_' . $arr[ 1 ][ $key ] . '">' . $arr[ 2 ][ $key ] . '</td>
										<td><input type="hidden" name="amount[' . ( $arr[ 1 ][ $key ] ) . ']" value="' . $_POST[ 'amount' ][ ( $arr[ 1 ][ $key ] ) ] . '" />' . $_POST[ 'amount' ][ ( $arr[ 1 ][ $key ] ) ] . '</td>
										<td><input type="hidden" name="target_date[' . ( $arr[ 1 ][ $key ] ) . ']" value="' . $_POST[ 'info_target_date' ][ ( $arr[ 1 ][ $key ] ) ] . '" />' . $_POST[ 'info_target_date' ][ ( $arr[ 1 ][ $key ] ) ] . '</td>
										<td><input type="hidden" name="notse[' . ( $arr[ 1 ][ $key ] ) . ']" value="' . $_POST[ 'notes' ][ ( $arr[ 1 ][ $key ] ) ] . '" />' . $_POST[ 'notes' ][ ( $arr[ 1 ][ $key ] ) ] . '</td>' , $data );
				}
			}
			$data = str_replace ( '<td><input type="checkbox"' , '<td style="display:none"><input type="hidden"' , $data );
			$this -> show -> assign ( 'list' , $data );
			$this -> show -> display ( 'device_borrow-accept-apply' );
		}
	}
	
	function c_borrow_accept ( )
	{
		if ( $_POST )
		{
			if ( $this -> model_borrow_accept ( $_POST , $_GET[ 'orderid' ] ) )
			{
				showmsg ( '操作成功！' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败，请与OA管理员联系！' );
			}
		}
	}
	
		function c_rebak(){
		if ( $this->model_rebak() )
			{
				showmsg ( '操作成功！' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败，请与OA管理员联系！' );
			}
		
	}
	
	//*****************************析构函数*****************************
	function __destruct ( )
	{
	
	}
}
?>