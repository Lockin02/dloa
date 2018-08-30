<?php
class controller_device_stock extends model_device_stock
{
	public $show; // ģ����ʾ
	/**
	 * ���캯��
	 *
	 */
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> tbl_name = '{table}';
		$this -> pk = 'id';
		$this -> show = new show ( );
	}
	/**
	 * Ĭ�Ϸ�����ʾ
	 *
	 */
	function c_index ( )
	{
		$this -> c_showlist ( );
	}
	//##############################################��ʾ����#################################################
	/**
	 * ��ʾ�б�
	 *
	 */
	function c_showlist ( )
	{
		$this -> show -> assign ( 'select_type' , $this -> select_type ( $_GET[ 'typeid' ] ) );
		$this -> show -> assign ( 'typeName' ,  $_GET[ 'typeName' ]?$_GET[ 'typeName' ]:$_POST[ 'typeName' ] );
		$this -> show -> assign ( 'list' , $this -> model_list ( ) );
		$this -> show -> display ( 'device_list' );
	}
	/**
	 * �豸����
	 * Enter description here ...
	 */
	function c_search ( )
	{
		$typeid = $_GET[ 'typeid' ] ? $_GET[ 'typeid' ] : $_POST[ 'typeid' ];
		$state = $_GET[ 'state' ] || $_GET[ 'state' ] == '0' ? $_GET[ 'state' ] : $_POST[ 'state' ];
		$dept_id = $_GET[ 'dept_id' ] ? $_GET[ 'dept_id' ] : $_POST[ 'dept_id' ];
		$field_name = $_GET[ 'field' ] ? $_GET[ 'field' ] : $_POST[ 'field' ];
		$field_name = is_array ( $field_name ) ? $field_name : ( $field_name ? explode ( ',' , $field_name ) : '' );
		$keyword = $_GET[ 'keyword' ] ? $_GET[ 'keyword' ] : $_POST[ 'keyword' ];
		$keyword = is_array ( $keyword ) ? $keyword : ( $keyword ? explode ( ',' , $keyword ) : '' );
		$symbol = $_GET[ 'symbol' ] ? $_GET[ 'symbol' ] : $_POST[ 'symbol' ];
		$symbol = is_array ( $symbol ) ? $symbol : ( $symbol ? explode ( ',' , $symbol ) : '' );
		if($dept_id&&!$typeid){
			$this -> tbl_name = 'device_type';
			$rs = $this -> findAll ( 'dept_id=' . $dept_id);
			if($rs&&is_array($rs)){
				foreach($rs as $key =>$val){
					if($val['id']){
						$lists=$this -> model_searchs ( $val['id']);
						if($lists){
							if($key>0){
							$list .= '</table><table class="table" width="98%" border="0" cellpadding="0" cellspacing="0" align="center" style="table-layout:inherit;">';
							}
							$list .= '<tr class="tableheader">';
							$list .= '<td colspan="20">'.$val['typename'].'</td>';
							$list .= '</tr>';
							$list .= '<tr class="tableheader">';
							$list .= '<td><input type="checkbox" onclick="set_all(this.checked);" /></td>';
							$list .= '<td width="4%">ID</td>';
							$list .= '<td width="8%">�豸����</td>';
							$list .= $this -> get_fixed_field_name ( $val['id'] );
							$list .= $this -> model_show_field_name ( $val['id'] );
							$list .= '<td width="70">���ڿ��</td>';
							$list .= '<td width="70">������</td>';
							$list .= '<td width="70">�豸״̬</td>';
							$list .= '<td width="55">����</td>';
							$list .= '<tr>';
							$list .=$lists;
						}
										
					}
				}
			}
			
			$this -> show -> assign ( 'list' ,$list );
		}elseif ( $typeid )
		 {
			$typename = $this -> get_table_fields ( "device_type" , "id=" . $typeid , 'typename' );
			$title .= '<tr class="tableheader">';
			$title .= '<td colspan="20">'.$typename.'</td>';
			$title .= '</tr>';
			$title .= '<tr class="tableheader">';
			$title .= '<td><input type="checkbox" onclick="set_all(this.checked);" /></td>';
			$title .= '<td width="4%">ID</td>';
			$title .= '<td width="8%">�豸����</td>';
			$title .= $this -> get_fixed_field_name ( $typeid );
			$title .= $this -> model_show_field_name ( $typeid );
			$title .= '<td width="70">���ڿ��</td>';
			$title .= '<td width="70">������</td>';
			$title .= '<td width="70">�豸״̬</td>';
			$title .= '<td width="55">����</td>';
			$title .= '<tr>';
			//$this -> show -> assign ( 'table_title' , $title );
			$this -> show -> assign ( 'list' , $title.$this -> model_search ( ) );
		
		} else
		{
			$this -> show -> assign ( 'typename' , '' );
			$this -> show -> assign ( 'table_title' , '' );
			$this -> show -> assign ( 'list' , '' );
		}
		
		global $func_limit;
		
		$dataI=$this ->model_deviceStock_searchCheckDept();
		$data = $this -> get_type_data ( $dataI[ 'deptIdStr' ] );
		if ( $data )
		{
			$type_options = '';
			foreach ( $data as $row )
			{
				$type_options .= '<option value="' . $row[ 'id' ] . '">' . $row[ 'typename' ] . (  ! $dept_id ? '--' . $row[ 'dept_name' ] : '' ) . '</option>';
			}
		}

		if ( $dataI['data'] )
		{
			$dept_option = '';
			foreach ( $dataI['data'] as $rs )
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
		if ( is_array ( $field_name ) )
		{
			$this -> show -> assign ( 'field' , $field_name[ 0 ] );
			if ( count ( $field_name ) > 1 )
			{
				foreach ( $field_name as $key => $val )
				{
					if ( $key > 0 )
					{
						$search_list .= '<tr id="tr_' . $key . '">';
						$search_list .= '<td>�������ݣ�<select name="field[]"><option value="">��ѡ����������</option>' . $this -> model_ajax_get_field_name ( $typeid , $val ) . '</select></td>';
						$search_list .= '<td width="80"><select name="symbol[]">' . $this -> symbol_option ( $symbol[ $key ] ) . '</select></td>';
						$search_list .= '<td>�ؼ��֣�<input type="text" size="12" name="keyword[]" value="' . $keyword[ $key ] . '" /></td>';
						$search_list .= '<td id="edit_' . $key . '" width="130"><input type="button" onclick="copy(' . $key . ');" value=" ���� " /> <input type="button" onclick="del_tr(' . $key . ');" value=" ɾ�� " /></td>';
						$search_list .= '</tr>';
					}
				}
				$this -> show -> assign ( 'none' , 'block' );
				$this -> show -> assign ( 'button' , '�ر�' );
				$this -> show -> assign ( 'search_list' , $search_list );
			} else
			{
				$this -> show -> assign ( 'search_list' , '' );
				$this -> show -> assign ( 'none' , 'none' );
				$this -> show -> assign ( 'button' , '�߼�' );
			}
		} else
		{
			$this -> show -> assign ( 'search_list' , '' );
			$this -> show -> assign ( 'none' , 'none' );
			$this -> show -> assign ( 'button' , '�߼�' );
		}
		$this -> show -> assign ( 'keyword' , $keyword[ 0 ] );
		$this -> show -> assign ( 'typeid' , $typeid );
		$this -> show -> assign ( 'state' , $state );
		$this -> show -> assign ( 'tid' , ( count ( $field_name ) - 1 ) );
		$this -> show -> assign ( 'dept_id' , $dept_id );
		$this -> show -> assign ( 'select_dept' , $dept_option );
		$this -> show -> assign ( 'select_type' , $type_options );
		$this -> show -> display ( 'device_search' );
	}
	/**
	 * �����������
	 * @param $selectindex
	 */
	function symbol_option ( $selectindex = null )
	{
		$symbol = array ( 
						'eq' => '����' , 
						'ne' => '����' , 
						'lt' => 'С��' , 
						'le' => 'С�ڵ���' , 
						'gt' => '����' , 
						'ge' => '���ڵ���' , 
						'bw' => '��ʼ��' , 
						'bn' => '����ʼ��' , 
						'in' => '����' , 
						'ni' => '������' , 
						'ew' => '������' , 
						'en' => '��������' , 
						'cn' => '����' , 
						'nc' => '������' 
		);
		$str = '';
		foreach ( $symbol as $key => $value )
		{
			$str .= '<option ' . ( $selectindex && $selectindex == $key ? 'selected' : '' ) . ' value="' . $key . '">' . $value . '</option>';
		}
		return $str;
	}
	/**
	 * �޸�ʹ��˵��
	 *
	 */
	function c_description ( )
	{
		if ( $_POST )
		{
			$this -> tbl_name = 'device_list';
			if ( $this -> update ( array ( 
											'id' => $_GET[ 'list_id' ] 
			) , array ( 
						'description' => $_POST[ 'description' ] 
			) ) )
			{
				showmsg ( '�����ɹ���' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '�޸�ʧ�ܣ��������Ա��ϵ��' );
			}
		} else
		{
			$description = $this -> get_table_fields ( 'device_list' , 'id=' . $_GET[ 'list_id' ] , 'description' );
			$this -> show -> assign ( 'description' , ( $description ? $description : '����ʹ��˵����' ) );
			$this -> show -> display ( 'device_description' );
		}
	}
	
	function c_show_description ( )
	{
		if ( $_GET[ 'list_id' ] )
		{
			$description = $this -> get_table_fields ( 'device_list' , 'id=' . $_GET[ 'list_id' ] , 'description' );
			$this -> show -> assign ( 'description' , $description );
			$this -> show -> display ( 'device_show-description' );
		}
	}
	/**
	 * ��ʾ���
	 *
	 */
	function c_show_add ( )
	{
		$class = $this -> newclass ( 'includes_class_global' );
		$area = $class -> area_select ( );
		$this -> show -> assign ( 'select_type' , $this -> select_type ( ) );
		$this -> show -> display ( 'device_adddevice' );
		unset ( $area );
	}
	
	/**
	 * �������ݽ���
	 */
	function c_import ( )
	{
		$this -> show -> display ( 'device_import' );
	}
	/**
	 * ���浼������
	 */
	function c_save_import ( )
	{
		if ( $_FILES[ 'upfile' ] )
		{
			set_time_limit ( 0 );
			if ( $this -> model_save_import ( ) )
			{
				showmsg ( '�����ɹ���' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '����ʧ�ܣ�' , 'self.parent.location.reload();' , 'button' );
			}
		} else
		{
			showmsg ( '��ѡ��Excel�����ļ���' );
		}
	}
	
	function c_shift_list ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_shift_list ( $_GET[ 'tid' ] ) );
		$this -> show -> display ( 'device_shift-list' );
	}
	//##############################################��������#################################################
	/**
	 * ���
	 */
	function c_insert ( )
	{
		if ( $this -> model_insert ( ) )
		{
			showmsg ( '��ӳɹ���' , 'self.parent.location.reload();' , 'button' );
		} else
		{
			showmsg ( '���ʧ�ܣ�' , 'self.parent.location.reload();' , 'button' );
		}
	}
	//##############################################�����豸����#############################################
	/**
	 * ��ʾ�����ҳ
	 *
	 */
	function c_show_addinfo ( )
	{
		$this -> show -> assign ( 'listid' , $_GET[ 'id' ] );
		
		$this -> show -> assign ( 'field' , $this -> model_show_field ( ) );
		$this -> show -> display ( 'device_addinfo' );
	}
	/**
	 * ��ӵ��豸
	 *
	 */
	function c_add_deviceinfo ( )
	{
		if ( $this -> model_add_deviceinfo ( ) )
		{
			showmsg ( '��ӳɹ���' , 'self.parent.location.reload();' , 'button' );
		} else
		{
			showmsg ( '���ʧ�ܣ�' , 'self.parent.location.reload();' , 'button' );
		}
	}
	/**
	 * ���豸�б�
	 *
	 */
	function c_device_info_list ( )
	{
		global $func_limit;
		if ( $_SESSION[ 'USER_ID' ] != 'admin' )
		{
			if (  ! $func_limit[ '��������' ] )
			{
				showmsg ( '��û�и��豸�κ��������Ȩ�ޣ�' );
			}
		}
		$arr = $_GET[ 'sort' ] ? explode ( '-' , $_GET[ 'sort' ] ) : '';
		$find = $arr[ 0 ];
		$sotr = $arr[ 1 ];
		//$this->show->assign ( 'fixed_title', $this->get_fixed_field_name ( $_GET['typeid'] ) );
		//$this->show->assign ( 'field_title', $this->model_show_field_name ( $_GET['typeid'] ) );
		

		$fixed_arr = $this -> get_fixed_field_name ( $_GET[ 'typeid' ] , false );
		$str = '';
		$field = '';
		if ( $fixed_arr )
		{
			foreach ( $fixed_arr as $key => $val )
			{
				if (  ! $val )
				{
					switch ( $key )
					{
						case '_coding' :
							$str .= "'������',";
							$field .= "{name:'coding',index:'a.coding',editable:true},";
							break;
						case '_dpcoding' :
							$str .= "'���ű��� ',";
							$field .= "{name:'dpcoding',index:'a.dpcoding',editable:true,searchoptions:{sopt:['cn']},editrules:{custom:true,custom_func:check_field}},";
							break;
						case '_fitting' :
							$str .= "'��� ',";
							$field .= "{name:'fitting',index:'a.fitting',editable:true,searchoptions:{sopt:['cn']}},";
							break;
						case '_price' :
							$str .= "'���� ',";
							$field .= "{name:'price',index:'a.price',editable:true,searchoptions:{sopt:['cn']}},";
							break;
						case '_notes' :
							$str .= "'��ע',";
							$field .= "{name:'notes',index:'a.notes',editable:true,searchoptions:{sopt:['cn']}},";
							break;
					}
				}
			}
		}
		$data = $this -> model_show_field_name ( $_GET[ 'typeid' ] , false );
		if ( $data )
		{
			foreach ( $data as $rs )
			{
				$str .= "'" . $rs[ 'fname' ] . "',";
				$field .= "{name:'" . $rs[ 'id' ] . "',index:'" . $rs[ 'id' ] . "',editable:true" . ( $rs[ 'only' ] == 1 ? ',editrules:{custom:true,custom_func:check_field}' : '' ) . "},";
			}
		}
		$gl = new includes_class_global ( );
		$area = array ();
		if ( $_SESSION[ 'USER_ID' ] != 'admin' )
		{
			$area = $gl -> get_area ( explode ( ',' , $func_limit[ '��������' ] ) );
		} else
		{
			$tmp = $gl -> area_call ( 'all' );
			foreach ( $tmp as $row )
			{
				$area[ $row[ 'ID' ] ] = $row[ 'Name' ];
			}
		}
		$this -> show -> assign ( 'area' , json_encode ( un_iconv ( $area ) ) );
		$this -> show -> assign ( 'field_title' , ( $str ? ',' . $str : ',' ) );
		$this -> show -> assign ( 'field_parameter' , ( $field ? ',' . $field : ',' ) );
		$this -> show -> assign ( 'id_sort_img' , $this -> sort_img ( 'id' , ( $find == 'id' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'date_sort_img' , $this -> sort_img ( 'date' , ( $find == 'date' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'state_sort_img' , $this -> sort_img ( 'state' , ( $find == 'state' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'amount_sort_img' , $this -> sort_img ( 'amount' , ( $find == 'amount' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'borrow_sort_img' , $this -> sort_img ( 'borrow_num' , ( $find == 'borrow_num' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'projectname_sort_img' , $this -> sort_img ( 'g.name' , ( $find == 'g.name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'user_name_sort_img' , $this -> sort_img ( 'e.user_name' , ( $find == 'e.user_name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'borrow_date_sort_img' , $this -> sort_img ( 'c.date' , ( $find == 'c.date' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'list' , $this -> model_device_info_list ( ) );
		$this -> show -> display ( 'device_info-list' );
	}
	/**
	 * ��ʽ�������
	 */
	function c_info_list ( )
	{
		global $func_limit;
		$data = $this -> model_device_info_list ( true );
		$rs = $this -> get_fixed_field_name ( $_GET[ 'typeid' ] , false );
		$responce -> page = $this -> page;
		$responce -> total = ceil ( $this -> num / pagenum );
		$responce -> records = $this -> num;
		if ( $data )
		{
			foreach ( $data as $key => $row )
			{
				$arr = array ();
				$arr[ ] = $row[ 'typeid' ];
				$arr[ ] = $row[ 'list_id' ];
				$arr[ ] = $row[ 'id' ];
				foreach ( $rs as $k => $v )
				{
					if (  ! $v )
					{
						switch ( $k )
						{
							case '_coding' :
								$arr[ ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
								break;
							case '_dpcoding' :
								$arr[ ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
								break;
							case '_fitting' :
								$arr[ ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
								break;
							case '_price' :
								$arr[ ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
								break;
							case '_notes' :
								$arr[ ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
								break;
						}
					}
				}
				if ( $row[ 'field' ] )
				{
					$field_data = $this -> get_field_content ( $row[ 'id' ] );
					foreach ( $row[ 'field' ] as $av )
					{
						
						if ( $field_data[ $av ] )
						{
							$arr[ ] = $field_data[ $av ];
						} else
						{
							$arr[ ] = '--';
						}
					}
					unset ( $field_data );
				}
				$arr[ ] = $row[ 'date' ] ? date ( 'Y-m-d' , $row[ 'date' ] ) : '--';
				switch ( $row[ 'state' ] )
				{
					case 0 :
						$arr[ ] = '����';
						break;
					case 1 :
						$arr[ ] = '���';
						break;
					case 2 :
						$arr[ ] = '�ȴ�ȷ��';
						break;
					case 3 :
						$arr[ ] = 'ά��';
						break;
					case 4 :
						$arr[ ] = '�˿�';
						break;
					case 5 :
						$arr[ ] = '������';
						break;
					case 6 :
						$arr[ ] = '������';
						break;
					default :
						$arr[ ] = '������';
				}
				$arr[ ] = $row[ 'areaname' ];
				$arr[ ] = $row[ 'amount' ];
				$arr[ ] = $row[ 'borrow_num' ];
				$arr[ ] = $row[ 'depreciation' ];
				$arr[ ] = $row[ 'depreciationYear' ];
				$arr[ ] = ( $row[ 'borrow_date' ] ? $this -> rate ( $row[ 'id' ] , $row[ 'date' ] ) . '%' : '' );
				$arr[ ] = ( $row[ 'returndate' ] ? '' : $row[ 'projectname' ] );
				$arr[ ] = ( $row[ 'returndate' ] ? '' : $row[ 'projectNo' ] );
				$arr[ ] = ( $row[ 'returndate' ] ? '' : $row[ 'user_name' ] );
				$arr[ ] = ( $row[ 'borrow_date' ] &&  ! $row[ 'returndate' ] ? date ( 'Y-m-d' , $row[ 'borrow_date' ] ) : '' );
				$arr[ ] = $func_limit[ '��������Ա' ] ? 1 : '';
				$responce -> rows[ $key ][ 'id' ] = $row[ 'id' ];
				$responce -> rows[ $key ][ 'cell' ] = un_iconv ( $arr );
			
			}
		}
		echo json_encode ( $responce );
	}
	/**
	 * ���ֶ��޸�
	 */
	function c_edit_field ( )
	{
		foreach ( $_POST as $key => $val )
		{
			if ( $this -> model_edit_field ( $_POST[ 'id' ] , $key , mb_iconv ( $val ) ) )
			{
				echo 1;
				break;
			}
		}
	
		//file_put_contents ( 'bb.txt', json_encode ( $_POST ) );
	}
	
	function c_add_info ( )
	{
		if ( $this -> model_add_info ( $_GET[ 'id' ] , $_GET[ 'typeid' ] , $_POST ) )
		{
			echo 1;
		} else
		{
			echo  - 1;
		}
	}
	
	/**
	 * ���豸������¼
	 */
	function c_operate ( )
	{
		foreach ( $_GET as $key => $val )
		{
			$this -> show -> assign ( $key , $val );
		}
		$this -> show -> display ( 'device_info-operate' );
	}
	/**
	 * �����������
	 *
	 */
	function c_borrow_operate ( )
	{
		$this -> tbl_name = 'device_list';
		$typeinfo = $this -> find ( 'id=' . $_GET[ 'list_id' ] , null , 'unit' );
		$this -> show -> assign ( 'unit' , $typeinfo[ 'unit' ] );
		$this -> tbl_name = 'device_info';
		$info = $this -> find ( 'id=' . $_GET[ 'id' ] , 'amount,area' );
		$this -> show -> assign ( 'amount' , ( $info[ 'amount' ] - $info[ 'borrow_num' ] ) );
		$this -> show -> assign ( 'area' , $info[ 'area' ] );
		$this -> show -> assign ( 'id' , $_GET[ 'id' ] );
		$this -> show -> assign ( 'typeid' , $_GET[ 'typeid' ] );
		$this -> show -> assign ( 'list_id' , $_GET[ 'list_id' ] );
		$this -> show -> assign ( 'date' , date ( 'Y-m-d H:i:s' ) );
		$this -> show -> display ( 'device_borrowoperate' );
	}
	function c_borrow_device_info ( )
	{
		if ( $this -> model_borrow_device_info ( ) )
		{
			showmsg ( '�����ɹ���' , 'self.parent.location.reload();' , 'button' );
		} else
		{
			showmsg ( '����ʧ�ܣ�' , 'self.parent.location.reload();' , 'button' );
		}
	}
	
	function c_show_borrow_info ( )
	{
		if ( intval ( $_GET[ 'id' ] ) )
		{
			$data = $this -> model_show_borrow_info ( $_GET[ 'id' ] );
			foreach ( $data as $key => $val )
			{
				if ( $key == 'targetdate' || $key == 'date' )
				{
					$this -> show -> assign ( $key , date ( 'Y-m-d' , $val ) );
				} elseif ( $key == 'name' &&  ! $val )
				{
					$this -> show -> assign ( $key , ( $val ? $val : '���˽���' ) );
				} else
				{
					$this -> show -> assign ( $key , $val );
				}
			}
			$this -> show -> display ( 'device_show-borrow-info' );
		} else
		{
			showmsg ( '�Ƿ�������' );
		}
	
	}
	function c_show_apply_info ( )
	{
		if ( intval ( $_GET[ 'id' ] ) )
		{
			$data = $this -> model_show_apply_info ( $_GET[ 'id' ] );
			foreach ( $data as $key => $val )
			{
				if ( $key == 'target_date' || $key == 'date' )
				{
					$this -> show -> assign ( $key , date ( 'Y-m-d' , $val ) );
				} elseif ( $key == 'name' &&  ! $val )
				{
					$this -> show -> assign ( $key , ( $val ? $val : '���˽���' ) );
				} else
				{
					$this -> show -> assign ( $key , $val );
				}
			}
			$this -> show -> display ( 'device_show-apply-info' );
		} else
		{
			showmsg ( '�Ƿ�������' );
		}
	
	}
	
	function c_update_stockdata ( )
	{
		echo $this -> model_update_stockdata ( );
	}
	function c_update_devicedata ( )
	{
		echo $this -> model_update_devicedata ( );
	}
	/**
	 * ���豸�б�
	 *
	 */
	function c_deviceInfoTypelist( )
	{
		global $func_limit;
		if ( $_SESSION[ 'USER_ID' ] != 'admin' )
		{
			if (  ! $func_limit[ '��������' ] )
			{
				showmsg ( '��û�и��豸�κ��������Ȩ�ޣ�' );
			}
		}
		$arr = $_GET[ 'sort' ] ? explode ( '-' , $_GET[ 'sort' ] ) : '';
		$find = $arr[ 0 ];
		$sotr = $arr[ 1 ];
		//$this->show->assign ( 'fixed_title', $this->get_fixed_field_name ( $_GET['typeid'] ) );
		//$this->show->assign ( 'field_title', $this->model_show_field_name ( $_GET['typeid'] ) );
		

		$fixed_arr = $this -> get_fixed_field_name ( $_GET[ 'typeid' ] , false );
		$str = '';
		$field = '';
		if ( $fixed_arr )
		{
			foreach ( $fixed_arr as $key => $val )
			{
				if (  ! $val )
				{
					switch ( $key )
					{
						case '_coding' :
							$str .= "'������',";
							$field .= "{name:'coding',index:'a.coding',editable:true},";
							break;
						case '_dpcoding' :
							$str .= "'���ű��� ',";
							$field .= "{name:'dpcoding',index:'a.dpcoding',editable:true,searchoptions:{sopt:['cn']},editrules:{custom:true,custom_func:check_field}},";
							break;
						case '_fitting' :
							$str .= "'��� ',";
							$field .= "{name:'fitting',index:'a.fitting',editable:true,searchoptions:{sopt:['cn']}},";
							break;
						case '_price' :
							$str .= "'���� ',";
							$field .= "{name:'price',index:'a.price',editable:true,searchoptions:{sopt:['cn']}},";
							break;
						case '_notes' :
							$str .= "'��ע',";
							$field .= "{name:'notes',index:'a.notes',editable:true,searchoptions:{sopt:['cn']}},";
							break;
					}
				}
			}
		}
		$data = $this -> model_show_field_name ( $_GET[ 'typeid' ] , false );
		if ( $data )
		{
			foreach ( $data as $rs )
			{
				$str .= "'" . $rs[ 'fname' ] . "',";
				$field .= "{name:'" . $rs[ 'id' ] . "',index:'" . $rs[ 'id' ] . "',editable:true" . ( $rs[ 'only' ] == 1 ? ',editrules:{custom:true,custom_func:check_field}' : '' ) . "},";
			}
		}
		$gl = new includes_class_global ( );
		$area = array ();
		if ( $_SESSION[ 'USER_ID' ] != 'admin' )
		{
			$area = $gl -> get_area ( explode ( ',' , $func_limit[ '��������' ] ) );
		} else
		{
			$tmp = $gl -> area_call ( 'all' );
			foreach ( $tmp as $row )
			{
				$area[ $row[ 'ID' ] ] = $row[ 'Name' ];
			}
		}
		$this -> show -> assign ( 'area' , json_encode ( un_iconv ( $area ) ) );
		$this -> show -> assign ( 'field_title' , ( $str ? ',' . $str : ',' ) );
		$this -> show -> assign ( 'field_parameter' , ( $field ? ',' . $field : ',' ) );
		$this -> show -> assign ( 'id_sort_img' , $this -> sort_img ( 'id' , ( $find == 'id' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'date_sort_img' , $this -> sort_img ( 'date' , ( $find == 'date' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'state_sort_img' , $this -> sort_img ( 'state' , ( $find == 'state' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'amount_sort_img' , $this -> sort_img ( 'amount' , ( $find == 'amount' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'borrow_sort_img' , $this -> sort_img ( 'borrow_num' , ( $find == 'borrow_num' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'projectname_sort_img' , $this -> sort_img ( 'g.name' , ( $find == 'g.name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'user_name_sort_img' , $this -> sort_img ( 'e.user_name' , ( $find == 'e.user_name' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'borrow_date_sort_img' , $this -> sort_img ( 'c.date' , ( $find == 'c.date' ? $sotr : '' ) ) );
		$this -> show -> assign ( 'list' , $this -> model_deviceInfoTypelist ( ) );
		$this -> show -> display ( 'device_infoTypelist' );
	}
	/**
	 * ��ʽ�������
	 */
	function c_infoTypelist( )
	{
		global $func_limit;
		$data = $this -> model_deviceInfoTypelist ( true );
		$rs = $this -> get_fixed_field_name ( $_GET[ 'typeid' ] , false );
		$responce -> page = $this -> page;
		$responce -> total = ceil ( $this -> num / pagenum );
		$responce -> records = $this -> num;
		if ( $data )
		{
			foreach ( $data as $key => $row )
			{
				$arr = array ();
				$arr[ ] = $row[ 'typeid' ];
				$arr[ ] = $row[ 'list_id' ];
				$arr[ ] = $row[ 'id' ];
				foreach ( $rs as $k => $v )
				{
					if (  ! $v )
					{
						switch ( $k )
						{
							case '_coding' :
								$arr[ ] = $row[ 'coding' ] ? $row[ 'coding' ] : '--';
								break;
							case '_dpcoding' :
								$arr[ ] = $row[ 'dpcoding' ] ? $row[ 'dpcoding' ] : '--';
								break;
							case '_fitting' :
								$arr[ ] = $row[ 'fitting' ] ? $row[ 'fitting' ] : '--';
								break;
							case '_price' :
								$arr[ ] = $row[ 'price' ] ? $row[ 'price' ] : '--';
								break;
							case '_notes' :
								$arr[ ] = $row[ 'notes' ] ? $row[ 'notes' ] : '--';
								break;
						}
					}
				}
				if ( $row[ 'field' ] )
				{
					$field_data = $this -> get_field_content ( $row[ 'id' ] );
					foreach ( $row[ 'field' ] as $av )
					{
						
						if ( $field_data[ $av ] )
						{
							$arr[ ] = $field_data[ $av ];
						} else
						{
							$arr[ ] = '--';
						}
					}
					unset ( $field_data );
				}
				$arr[ ] = $row[ 'date' ] ? date ( 'Y-m-d' , $row[ 'date' ] ) : '--';
				switch ( $row[ 'state' ] )
				{
					case 0 :
						$arr[ ] = '����';
						break;
					case 1 :
						$arr[ ] = '���';
						break;
					case 2 :
						$arr[ ] = '�ȴ�ȷ��';
						break;
					case 3 :
						$arr[ ] = 'ά��';
						break;
					case 4 :
						$arr[ ] = '�˿�';
						break;
					case 5 :
						$arr[ ] = '������';
						break;
					case 6 :
						$arr[ ] = '������';
						break;
					default :
						$arr[ ] = '������';
				}
				$arr[ ] = $row[ 'areaname' ];
				$arr[ ] = $row[ 'amount' ];
				$arr[ ] = $row[ 'borrow_num' ];
				$arr[ ] = $row[ 'depreciation' ];
				$arr[ ] = $row[ 'depreciationYear' ];
				$arr[ ] = ( $row[ 'borrow_date' ] ? $this -> rate ( $row[ 'id' ] , $row[ 'date' ] ) . '%' : '' );
				$arr[ ] = ( $row[ 'returndate' ] ? '' : $row[ 'projectname' ] );
				$arr[ ] = ( $row[ 'returndate' ] ? '' : $row[ 'projectNo' ] );
				$arr[ ] = ( $row[ 'returndate' ] ? '' : $row[ 'user_name' ] );
				$arr[ ] = ( $row[ 'borrow_date' ] &&  ! $row[ 'returndate' ] ? date ( 'Y-m-d' , $row[ 'borrow_date' ] ) : '' );
				$arr[ ] = $func_limit[ '��������Ա' ] ? 1 : '';
				$responce -> rows[ $key ][ 'id' ] = $row[ 'id' ];
				$responce -> rows[ $key ][ 'cell' ] = un_iconv ( $arr );
			
			}
		}
		echo json_encode ( $responce );
	}
	function c_infoLog ( )
	{
		if ( intval ( $_GET[ 'id' ] ) )
		{
			$this -> show -> assign ( 'list' , $this -> model_Loglist ('device_info', $_GET[ 'id' ] ) );
			$this -> show -> display ( 'device_logList' );
		}
	}
	function c_logManage ( )
	{
		    $this -> show -> assign ( 'startDate' ,$_GET['startDate']);
		    $this -> show -> assign ( 'endDate' , $_GET['endDate']);
		    $this -> show -> assign ( 'wordkey' , $_GET['wordkey']);
			$this -> show -> assign ( 'list' , $this -> model_infoLogManage ());
			$this -> show -> display ( 'device_logManage' );
	}
	//##########################################�ҵ��豸#################################################
	function c_mydevice ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_mydevice ( ) );
		$this -> show -> display ( 'device_mydevice' );
	}
	//##############################################����#################################################
	/**
	 * ��������
	 */
	function __destruct ( )
	{
	
	}
}
?>