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
			$term = $_POST[ 'operation' ] == 'update' ? '����' : '';
			$filename = 'upfile/tmp/' . time ( ) . '_' . $_FILES[ 'upfile' ][ 'name' ];
			if ( move_uploaded_file ( str_replace ( '\\\\' , '\\' , $_FILES[ 'upfile' ][ 'tmp_name' ] ) , $filename ) )
			{
				$data = $this -> get_excel ( $filename , ( $_POST[ 'sheet' ] - 1 ) );
				$str .= '<option value="">ѡ��Excel������</option>';
				if ( $data[ ( $_POST[ 'sheet' ] - 1 ) ][( $_POST[ 'hang' ]-1) ] )
				{
					$str .= '<option value="title">' . mb_iconv ( $data[ ( $_POST[ 'sheet' ] - 1 ) ][ 'title' ] ) . '(��������)</option>';
					foreach ( mb_iconv ( $data[ ( $_POST[ 'sheet' ] - 1 ) ][ ($_POST[ 'hang' ] - 1)] ) as $key => $val )
					{
						$str .= '<option value="' . $key . '">' . $val . '</option>';
					}
				} else
				{
					showmsg ( '��ѡ��Ĺ�����ͱ�ͷ��û�����ݣ�' );
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
									$field_select .= '<option value="coding">������ </option>';
									break;
								case '_dpcoding' :
									$field_select .= '<option value="dpcoding">���ű��� </option>';
									break;
								case '_fitting' :
									$field_select .= '<option value="fitting">���</option>';
									break;
								case '_price' :
									$field_select .= '<option value="price">�۸� </option>';
									break;
								case '_notes' :
									$field_select .= '<option value="notes">��ע </option>';
									break;
							
							}
						}
					}
				}
				$none = 'none';
				if (  ! $_POST[ 'typeid' ] )
				{
					$none = '';
					$field_select .= '<option value="coding">������ </option>';
					$field_select .= '<option value="dpcoding">���ű��� </option>';
					$field_select .= '<option value="fitting">���</option>';
					$field_select .= '<option value="price">�۸� </option>';
					$this -> show -> assign ( 'type_select' , '<tr><td align="left">����<select id="typename" name="field_id_typeid">' . $str . '</select> ��Ϊ�豸���' . $term . '</td></tr>' );
				} else
				{
					$this -> show -> assign ( 'type_select' , '<input type="hidden" name="typeid" value="' . $_POST[ 'typeid' ] . '" />' );
				}
				//��ʾ�豸����
				if ( $_POST[ 'list_id' ] && $_POST[ 'list_id' ] != 'on' )
				{
					$this -> show -> assign ( 'device_select' , '<input type="hidden" name="list_id" value="' . $_POST[ 'list_id' ] . '" />' );
				} elseif ( $_POST[ 'list_id' ] != 'on' )
				{
					$none = '';
					$this -> show -> assign ( 'device_select' , '<tr><td align="left">����<select id="device" name="field_id_device">' . $str . '</select> ��Ϊ�豸����' . $term . '</td></tr>' );
				} else
				{
					$this -> show -> assign ( 'device_select' , '' );
				}
				//��ʾ����
				if ( $_POST[ 'area' ] )
				{
					$this -> show -> assign ( 'area_select' , '<input type="hidden" name="area" value="' . $_POST[ 'area' ] . '" />' );
				} else
				{
					$none = '';
					$this -> show -> assign ( 'area_select' , '<tr><td align="left">����<select id="area" name="field_id_area">' . $str . '</select> ��Ϊ�������' . $term . '</td></tr>' );
				}
				
				$this -> show -> assign ( 'none' , $none );
				$field_select .= '<option value="amount">���� </option>';
				$field_select .= '<option value="date">������� </option>';
				$field_select .= '<option value="depreciationYear">�۾�����(��)</option>';
				$field_select .= '<option value="excBorrowNum">�������(���ɽ���)</option>';
				$field_select .= '<option value="excProjectNo">��Ŀ��� (���ɽ���)</option>';
				$field_select .= '<option value="excBorrower">������(���ɽ���)</option>';
				$field_select .= '<option value="excBorrowDate">��������(���ɽ���)</option>';
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
				showmsg ( '�ϴ������ļ�ʧ�ܣ�' );
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
			showmsg ( '�������ݳɹ���' , 'self.parent.location.reload();' , 'button' );
		} else
		{
			showmsg ( '����ʧ�ܣ��������Ա��ϵ��' );
		}
	}
}

?>