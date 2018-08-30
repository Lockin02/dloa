<?php
class controller_device_stock extends model_device_stock
{
	public $show; // 模板显示
	/**
	 * 构造函数
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
	 * 默认访问显示
	 *
	 */
	function c_index ( )
	{
		$this -> c_showlist ( );
	}
	//##############################################显示函数#################################################
	/**
	 * 显示列表
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
	 * 设备搜索
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
							$list .= '<td width="8%">设备名称</td>';
							$list .= $this -> get_fixed_field_name ( $val['id'] );
							$list .= $this -> model_show_field_name ( $val['id'] );
							$list .= '<td width="70">所在库存</td>';
							$list .= '<td width="70">借用人</td>';
							$list .= '<td width="70">设备状态</td>';
							$list .= '<td width="55">操作</td>';
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
			$title .= '<td width="8%">设备名称</td>';
			$title .= $this -> get_fixed_field_name ( $typeid );
			$title .= $this -> model_show_field_name ( $typeid );
			$title .= '<td width="70">所在库存</td>';
			$title .= '<td width="70">借用人</td>';
			$title .= '<td width="70">设备状态</td>';
			$title .= '<td width="55">操作</td>';
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
						$search_list .= '<td>搜索内容：<select name="field[]"><option value="">请选择搜索内容</option>' . $this -> model_ajax_get_field_name ( $typeid , $val ) . '</select></td>';
						$search_list .= '<td width="80"><select name="symbol[]">' . $this -> symbol_option ( $symbol[ $key ] ) . '</select></td>';
						$search_list .= '<td>关键字：<input type="text" size="12" name="keyword[]" value="' . $keyword[ $key ] . '" /></td>';
						$search_list .= '<td id="edit_' . $key . '" width="130"><input type="button" onclick="copy(' . $key . ');" value=" 复制 " /> <input type="button" onclick="del_tr(' . $key . ');" value=" 删除 " /></td>';
						$search_list .= '</tr>';
					}
				}
				$this -> show -> assign ( 'none' , 'block' );
				$this -> show -> assign ( 'button' , '关闭' );
				$this -> show -> assign ( 'search_list' , $search_list );
			} else
			{
				$this -> show -> assign ( 'search_list' , '' );
				$this -> show -> assign ( 'none' , 'none' );
				$this -> show -> assign ( 'button' , '高级' );
			}
		} else
		{
			$this -> show -> assign ( 'search_list' , '' );
			$this -> show -> assign ( 'none' , 'none' );
			$this -> show -> assign ( 'button' , '高级' );
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
	 * 运算符号下拉
	 * @param $selectindex
	 */
	function symbol_option ( $selectindex = null )
	{
		$symbol = array ( 
						'eq' => '等于' , 
						'ne' => '不等' , 
						'lt' => '小于' , 
						'le' => '小于等于' , 
						'gt' => '大于' , 
						'ge' => '大于等于' , 
						'bw' => '开始于' , 
						'bn' => '不开始于' , 
						'in' => '属于' , 
						'ni' => '不属于' , 
						'ew' => '结束于' , 
						'en' => '不结束于' , 
						'cn' => '包含' , 
						'nc' => '不包含' 
		);
		$str = '';
		foreach ( $symbol as $key => $value )
		{
			$str .= '<option ' . ( $selectindex && $selectindex == $key ? 'selected' : '' ) . ' value="' . $key . '">' . $value . '</option>';
		}
		return $str;
	}
	/**
	 * 修改使用说明
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
				showmsg ( '操作成功！' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '修改失败，请与管理员联系！' );
			}
		} else
		{
			$description = $this -> get_table_fields ( 'device_list' , 'id=' . $_GET[ 'list_id' ] , 'description' );
			$this -> show -> assign ( 'description' , ( $description ? $description : '暂无使用说明！' ) );
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
	 * 显示添加
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
	 * 导入数据界面
	 */
	function c_import ( )
	{
		$this -> show -> display ( 'device_import' );
	}
	/**
	 * 保存导入数据
	 */
	function c_save_import ( )
	{
		if ( $_FILES[ 'upfile' ] )
		{
			set_time_limit ( 0 );
			if ( $this -> model_save_import ( ) )
			{
				showmsg ( '操作成功！' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败！' , 'self.parent.location.reload();' , 'button' );
			}
		} else
		{
			showmsg ( '请选择Excel数据文件！' );
		}
	}
	
	function c_shift_list ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_shift_list ( $_GET[ 'tid' ] ) );
		$this -> show -> display ( 'device_shift-list' );
	}
	//##############################################操作函数#################################################
	/**
	 * 添加
	 */
	function c_insert ( )
	{
		if ( $this -> model_insert ( ) )
		{
			showmsg ( '添加成功！' , 'self.parent.location.reload();' , 'button' );
		} else
		{
			showmsg ( '添加失败！' , 'self.parent.location.reload();' , 'button' );
		}
	}
	//##############################################单类设备管理#############################################
	/**
	 * 显示添加面页
	 *
	 */
	function c_show_addinfo ( )
	{
		$this -> show -> assign ( 'listid' , $_GET[ 'id' ] );
		
		$this -> show -> assign ( 'field' , $this -> model_show_field ( ) );
		$this -> show -> display ( 'device_addinfo' );
	}
	/**
	 * 添加单设备
	 *
	 */
	function c_add_deviceinfo ( )
	{
		if ( $this -> model_add_deviceinfo ( ) )
		{
			showmsg ( '添加成功！' , 'self.parent.location.reload();' , 'button' );
		} else
		{
			showmsg ( '添加失败！' , 'self.parent.location.reload();' , 'button' );
		}
	}
	/**
	 * 单设备列表
	 *
	 */
	function c_device_info_list ( )
	{
		global $func_limit;
		if ( $_SESSION[ 'USER_ID' ] != 'admin' )
		{
			if (  ! $func_limit[ '管理区域' ] )
			{
				showmsg ( '您没有该设备任何区域管理权限！' );
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
							$str .= "'机身码',";
							$field .= "{name:'coding',index:'a.coding',editable:true},";
							break;
						case '_dpcoding' :
							$str .= "'部门编码 ',";
							$field .= "{name:'dpcoding',index:'a.dpcoding',editable:true,searchoptions:{sopt:['cn']},editrules:{custom:true,custom_func:check_field}},";
							break;
						case '_fitting' :
							$str .= "'配件 ',";
							$field .= "{name:'fitting',index:'a.fitting',editable:true,searchoptions:{sopt:['cn']}},";
							break;
						case '_price' :
							$str .= "'单价 ',";
							$field .= "{name:'price',index:'a.price',editable:true,searchoptions:{sopt:['cn']}},";
							break;
						case '_notes' :
							$str .= "'备注',";
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
			$area = $gl -> get_area ( explode ( ',' , $func_limit[ '管理区域' ] ) );
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
	 * 格式表格数据
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
						$arr[ ] = '可用';
						break;
					case 1 :
						$arr[ ] = '借出';
						break;
					case 2 :
						$arr[ ] = '等待确认';
						break;
					case 3 :
						$arr[ ] = '维修';
						break;
					case 4 :
						$arr[ ] = '退库';
						break;
					case 5 :
						$arr[ ] = '待审批';
						break;
					case 6 :
						$arr[ ] = '待受理';
						break;
					default :
						$arr[ ] = '不可用';
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
				$arr[ ] = $func_limit[ '超级管理员' ] ? 1 : '';
				$responce -> rows[ $key ][ 'id' ] = $row[ 'id' ];
				$responce -> rows[ $key ][ 'cell' ] = un_iconv ( $arr );
			
			}
		}
		echo json_encode ( $responce );
	}
	/**
	 * 单字段修改
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
	 * 单设备操作记录
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
	 * 借出操作界面
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
			showmsg ( '操作成功！' , 'self.parent.location.reload();' , 'button' );
		} else
		{
			showmsg ( '操作失败！' , 'self.parent.location.reload();' , 'button' );
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
					$this -> show -> assign ( $key , ( $val ? $val : '个人借用' ) );
				} else
				{
					$this -> show -> assign ( $key , $val );
				}
			}
			$this -> show -> display ( 'device_show-borrow-info' );
		} else
		{
			showmsg ( '非法参数！' );
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
					$this -> show -> assign ( $key , ( $val ? $val : '个人借用' ) );
				} else
				{
					$this -> show -> assign ( $key , $val );
				}
			}
			$this -> show -> display ( 'device_show-apply-info' );
		} else
		{
			showmsg ( '非法参数！' );
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
	 * 单设备列表
	 *
	 */
	function c_deviceInfoTypelist( )
	{
		global $func_limit;
		if ( $_SESSION[ 'USER_ID' ] != 'admin' )
		{
			if (  ! $func_limit[ '管理区域' ] )
			{
				showmsg ( '您没有该设备任何区域管理权限！' );
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
							$str .= "'机身码',";
							$field .= "{name:'coding',index:'a.coding',editable:true},";
							break;
						case '_dpcoding' :
							$str .= "'部门编码 ',";
							$field .= "{name:'dpcoding',index:'a.dpcoding',editable:true,searchoptions:{sopt:['cn']},editrules:{custom:true,custom_func:check_field}},";
							break;
						case '_fitting' :
							$str .= "'配件 ',";
							$field .= "{name:'fitting',index:'a.fitting',editable:true,searchoptions:{sopt:['cn']}},";
							break;
						case '_price' :
							$str .= "'单价 ',";
							$field .= "{name:'price',index:'a.price',editable:true,searchoptions:{sopt:['cn']}},";
							break;
						case '_notes' :
							$str .= "'备注',";
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
			$area = $gl -> get_area ( explode ( ',' , $func_limit[ '管理区域' ] ) );
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
	 * 格式表格数据
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
						$arr[ ] = '可用';
						break;
					case 1 :
						$arr[ ] = '借出';
						break;
					case 2 :
						$arr[ ] = '等待确认';
						break;
					case 3 :
						$arr[ ] = '维修';
						break;
					case 4 :
						$arr[ ] = '退库';
						break;
					case 5 :
						$arr[ ] = '待审批';
						break;
					case 6 :
						$arr[ ] = '待受理';
						break;
					default :
						$arr[ ] = '不可用';
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
				$arr[ ] = $func_limit[ '超级管理员' ] ? 1 : '';
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
	//##########################################我的设备#################################################
	function c_mydevice ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_mydevice ( ) );
		$this -> show -> display ( 'device_mydevice' );
	}
	//##############################################结束#################################################
	/**
	 * 析构函数
	 */
	function __destruct ( )
	{
	
	}
}
?>