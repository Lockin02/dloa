<?php
class controller_device_import extends model_device_import
{
	public $show;
	
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> show = new show ( );
		$this -> show -> path = 'device/';
	}
	
	function c_upfile ( )
	{
		set_time_limit ( 0 );
		include WEB_TOR . 'model/device/stock.php';
		$stock = new model_device_stock ( );
		if ( $_FILES )
		{
			$term = $_POST[ 'operation' ] == 'update' ? '条件' : '';
			$filename = 'upfile/tmp/' . time ( ) . '_' . $_FILES[ 'upfile' ][ 'name' ];
			if ( move_uploaded_file ( str_replace ( '\\\\' , '\\' , $_FILES[ 'upfile' ][ 'tmp_name' ] ) , $filename ) )
			{
				$data = $this -> get_excel ( $filename , ( $_POST[ 'sheet' ] - 1 ) );
				$str .= '<option value="">选择Excel内容列</option>';
				if ( $data[ ( $_POST[ 'sheet' ] - 1 ) ][( $_POST[ 'hang' ]-1) ] )
				{
					$str .= '<option value="title">' . mb_iconv ( $data[ ( $_POST[ 'sheet' ] - 1 ) ][ 'title' ] ) . '(工作表名)</option>';
					foreach ( mb_iconv ( $data[ ( $_POST[ 'sheet' ] - 1 ) ][ ($_POST[ 'hang' ] - 1)] ) as $key => $val )
					{
						$str .= '<option value="' . $key . '">' . $val . '</option>';
					}
				} else
				{
					showmsg ( '您选择的工作表和表头行没有数据！' );
				}
				$field_data = $_POST[ 'typeid' ] ? $stock -> model_show_field_name ( $_POST[ 'typeid' ] , false ) : '';
				if ( $field_data )
				{
					foreach ( $field_data as $key => $row )
					{
						$field_select .= '<option value="' . $row[ 'id' ] . '">' . $row[ 'fname' ] . '</option>';
					}
				}
				$field_data = $_POST[ 'typeid' ] ? $stock -> get_fixed_field_name ( $_POST[ 'typeid' ] , false ) : '';
				if ( $field_data )
				{
					foreach ( $field_data as $key => $val )
					{
						if (  ! $val )
						{
							switch ( $key )
							{
								case '_coding' :
									$field_select .= '<option value="coding">机身码 </option>';
									break;
								case '_dpcoding' :
									$field_select .= '<option value="dpcoding">部门编码 </option>';
									break;
								case '_fitting' :
									$field_select .= '<option value="fitting">配件</option>';
									break;
								case '_price' :
									$field_select .= '<option value="price">价格 </option>';
									break;
								case '_notes' :
									$field_select .= '<option value="notes">备注 </option>';
									break;
							
							}
						}
					}
				}
				$none = 'none';
				if (  ! $_POST[ 'typeid' ] )
				{
					$none = '';
					$field_select .= '<option value="coding">机身码 </option>';
					$field_select .= '<option value="dpcoding">部门编码 </option>';
					$field_select .= '<option value="fitting">配件</option>';
					$field_select .= '<option value="price">价格 </option>';
					$this -> show -> assign ( 'type_select' , '<tr><td align="left">将：<select id="typename" name="field_id_typeid">' . $str . '</select> 作为设备类别' . $term . '</td></tr>' );
				} else
				{
					$this -> show -> assign ( 'type_select' , '<input type="hidden" name="typeid" value="' . $_POST[ 'typeid' ] . '" />' );
				}
				//显示设备名称
				if ( $_POST[ 'list_id' ] && $_POST[ 'list_id' ] != 'on' )
				{
					$this -> show -> assign ( 'device_select' , '<input type="hidden" name="list_id" value="' . $_POST[ 'list_id' ] . '" />' );
				} elseif ( $_POST[ 'list_id' ] != 'on' )
				{
					$none = '';
					$this -> show -> assign ( 'device_select' , '<tr><td align="left">将：<select id="device" name="field_id_device">' . $str . '</select> 作为设备名称' . $term . '</td></tr>' );
				} else
				{
					$this -> show -> assign ( 'device_select' , '' );
				}
				//显示区域
				if ( $_POST[ 'area' ] )
				{
					$this -> show -> assign ( 'area_select' , '<input type="hidden" name="area" value="' . $_POST[ 'area' ] . '" />' );
				} else
				{
					$none = '';
					$this -> show -> assign ( 'area_select' , '<tr><td align="left">将：<select id="area" name="field_id_area">' . $str . '</select> 作为库存区域' . $term . '</td></tr>' );
				}
				
				$this -> show -> assign ( 'none' , $none );
				$field_select .= '<option value="amount">数量 </option>';
				$field_select .= '<option value="date">入库日期 </option>';
				$field_select .= '<option value="depreciationYear">折旧年限(月)</option>';
				$field_select .= '<option value="excBorrowNum">借出数量(生成借用)</option>';
				$field_select .= '<option value="excProjectNo">项目编号 (生成借用)</option>';
				$field_select .= '<option value="excBorrower">领用人(生成借用)</option>';
				$field_select .= '<option value="excBorrowDate">领用日期(生成借用)</option>';
				$this -> show -> assign ( 'content_select' , $str );
				$this -> show -> assign ( 'field_select' , $field_select );
				$this -> show -> assign ( 'typeid' , $_POST[ 'typeid' ] );
				$this -> show -> assign ( 'p_dept_id' , $_POST[ 'dept_id' ] );
				$this -> show -> assign ( 'filename' , $filename );
				if ( $_POST[ 'operation' ] == 'import' )
				{
					$this -> show -> display ( 'import-select' );
				} else
				{
					$this -> show -> display ( 'import-update' );
				}
			
			} else
			{
				showmsg ( '上传数据文件失败！' );
			}
		} else
		{
			$gl = new includes_class_global ( );
			$this -> show -> assign ( 'type_select' , $stock -> select_type ( ) );
			$this -> show -> assign ( 'area_select' , $gl -> area_select ( ) );
			$this -> show -> assign ( 'dept_select' , ( $_SESSION[ 'USER_ID' ] == 'admin' ? $gl -> depart_select ( ) : '' ) );
			$this -> show -> assign ( 'admin_none' , ( $_SESSION[ 'USER_ID' ] != 'admin' ? 'none' : '' ) );
			$this -> show -> display ( 'import-upfile' );
		}
	}
	
	function c_save_data ( )
	{
		set_time_limit ( 0 );
		if ( $this -> import_data ( $_POST ) )
		{
			showmsg ( '导入数据成功！' , 'self.parent.location.reload();' , 'button' );
		} else
		{
			showmsg ( '导入失败，请与管理员联系！' );
		}
	}
}

?>