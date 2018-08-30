<?php
class model_device extends model_base
{
	public $id;
	public $typename;
	/**
	 * 构造函数
	 *
	 */
	function __construct()
	{
		parent::__construct ();
		$this->id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$this->typename = $_GET['typename'] ? $_GET['typename'] : $_POST['typename'];
	}
	/**
	 * 类别下拉
	 *
	 * @param unknown_type $typeid
	 * @return unknown
	 */
	function select_type($typeid = '')
	{
		$this->tbl_name = 'device_type';
		if ($_SESSION['USER_ID'] != 'admin')
		{
			$where = 'dept_id=' . $_SESSION['DEPT_ID'];
		} else
		{
			$where = null;
		}
		$typearr = $this->findAll ( $where );
		foreach ( $typearr as $rs )
		{
			if ($rs['id'] == $typeid)
			{
				$str .= '<option selected value="' . $rs['id'] . '">' . $rs['typename'] . '</option>';
			} else
			{
				$str .= '<option value="' . $rs['id'] . '">' . $rs['typename'] . '</option>';
			}
		}
		return $str;
	}
	/**
	 * 显示类别列表
	 *
	 * @return unknown
	 */
	function model_typelist()
	{
		global $func_limit;
		$this->tbl_name = 'device_type';
		if ($_SESSION['USER_ID'] != 'admin')
		{
			if ($func_limit['管理部门'])
			{
				$where = 'where a.dept_id in(' . $func_limit['管理部门'] . ')';
			} else
			{
				$where = 'where a.dept_id=' . $_SESSION['DEPT_ID'];
			}
		}
		$query = $this->_db->query ( "
									select 
										a.*,b.dept_name
									from 
										device_type as a
										left join department as b on b.dept_id=a.dept_id 
									 $where
									" );
		while ( ($rs = $this->_db->fetch_array ( $query )) != false )
		{
			$str .= '
					<tr id="tr_' . $rs['id'] . '">
						<td>' . $rs['id'] . '</td>
						<td id="name_' . $rs['id'] . '">' . $rs['typename'] . '</td>
						<td id="dept_' . $rs['id'] . '">' . $rs['dept_name'] . '</td>
						<td id="edit_' . $rs['id'] . '" align="center"> <a href="javascript:edit(' . $rs['id'] . ')">修改</a> | <a href="javascript:del(' . $rs['id'] . ')">删除</a> <a href="?model=device&action=set_field&typeid=' .
					 $rs['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800" class="thickbox" title="设置 [' . $rs['typename'] . '] 类别自定义字段">自定义字段</a></td>
					</tr>
					';
		}
		return $str;
	}
	/**
	 * 更新类别
	 *
	 * @return unknown
	 */
	function model_update_type()
	{
		$this->tbl_name = 'device_type';
		return $this->update ( 'id=' . $this->id, array('typename'=>$_POST['typename'],'dept_id'=>($_POST['dept_id'] ? $_POST['dept_id'] : $_SESSION['DEPT_ID']),'area'=>($_POST['area'] ? $_POST['area'] : $_SESSION['AREA'])) );
	}
	/**
	 * 添加类别
	 *
	 * @return unknown
	 */
	function model_insert_type()
	{
		$this->tbl_name = 'device_type';
		if ($_POST['typename'])
		{
			$arr = array('typename'=>$_POST['typename'],'dept_id'=>($_POST['deptid'] ? $_POST['deptid'] : $_SESSION['DEPT_ID']),'area'=>($_POST['area'] ? $_POST['area'] : $_SESSION['AREA']));
			return $this->create ( $arr );
		} else
		{
			showmsg ( '类别名称不能为空！' );
		}
	}
	/**
	 * 删除类别
	 *
	 * @return unknown
	 */
	function model_delete_type()
	{
		$this->tbl_name = 'device_type';
		if ($this->id)
		{
			$rs = $this->_db->get_one ( "select id from device_list where typeid=" . $this->id );
			if ($rs)
			{
				return false;
			} else
			{
				return $this->delete ( 'id=' . $this->id );
			}
		} else
		{
			return false;
		}
	}
	/**
	 * 按字段获取
	 *
	 * @param unknown_type $field_id
	 * @return unknown
	 */
	function model_get_field_name($field_id)
	{
		$this->tbl_name = 'device_type';
		if ($field_id)
		{
			if (is_array ( $field_id ))
			{
				$fid = rtrim ( implode ( ',', $field_id ), ',' );
				if ($fid)
				{
					return $this->findAll ( "id in($fid)" );
				}
			} else
			{
				return $this->findAll ( "id in($field_id)" );
			}
		}
	}
	
	/**
	 * 更新和设置自定义字段
	 *
	 * @return unknown
	 */
	function model_update_field()
	{
		if ($_POST['fixed'])
		{
			$row = array();
			foreach ( $_POST['fixed'] as $key => $val )
			{
				$row[$key] = $val;
			}
			$this->tbl_name = 'device_type';
			$this->update ( 'id=' . $this->id, $row );
		}
		$this->tbl_name = 'device_type_field';
		if ($_POST && $this->id)
		{
			$rs = $this->findall ( 'typeid=' . $this->id, null, 'id' );
			if ($rs)
			{
				$field_id = array();
				foreach ( $rs as $key => $val )
				{
					if ($_POST['field'][$val['id']])
					{
						$field_id[] = $val['id'];
						$this->update ( 'id=' . $val['id'], array('fname'=>$_POST['field'][$val['id']],'property'=>$_POST['property'][$val['id']],'only'=>$_POST['only'][$val['id']],'sort'=>$_POST['sort'][$val['id']]) );
					} else
					{
						$this->delete ( 'id=' . $val['id'] );
					}
				}
			}
			
			if ($_POST['new_field'])
			{
				foreach ( $_POST['new_field'] as $key => $val )
				{
					if ($val)
						$this->create ( array('fname'=>$val,'typeid'=>$this->id,'property'=>$_POST['new_property'][$key],'only'=>$_POST['new_only'][$key],'sort'=>$_POST['new_sort'][$key]) );
				}
			}
			return true;
		} else
		{
			$this->delete ( 'typeid=' . $this->id );
			return true;
		}
	}
}

?>