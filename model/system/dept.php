<?php
class model_system_dept extends model_base
{
	function __construct()
	{
		parent::__construct ();
		$this->tbl_name = 'department';
		$this->pk = 'dept_id';
	}
	/**
	 * 部门列表
	 * @param $dept_id
	 */
	function DeptList($dept_id = null)
	{
		$data = array();
		if ($dept_id && is_array ( $dept_id ))
		{
			$dept_id = implode ( ',', $dept_id );
		}
		$strSql=$_SESSION['USER_COM']=='xs'?" and  a.comCode='xs' ":'';
		if ($dept_id)
		{
			$sql= "select a.*,b.DEPT_NAME AS PARENT_NAME from $this->tbl_name as a left join $this->tbl_name as b on b.DEPT_ID=a.PARENT_ID where a.dept_id in (".trim($dept_id,',').") and a.delflag='0' order by DEPT_SORT asc" ;
		
		} else
		{ 
			$sql= "select a.*,b.DEPT_NAME AS PARENT_NAME from $this->tbl_name as a left join $this->tbl_name as b on b.DEPT_ID=a.PARENT_ID  where 1  and  a.delflag='0'  $strSql order by a.DEPT_SORT asc" ;
		}
		
		$query = $this->query ($sql);
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			$data[] = $rs;
		}
		
		return $data;
	}
	/**
	 * 部门列表
	 * @param $dept_id
	 */
	function DeptListAll($dept_id = null)
	{
		$data = array();
		if ($dept_id && is_array ( $dept_id ))
		{
			$dept_id = implode ( ',', $dept_id );
		}
		if ($dept_id)
		{
			$query = $this->query ( "select a.*,b.DEPT_NAME AS PARENT_NAME from $this->tbl_name as a left join $this->tbl_name as b on b.DEPT_ID=a.PARENT_ID where a.dept_id in (".trim($dept_id,',').")  order by DEPT_SORT asc" );
		
		} else
		{ 
			$query = $this->query ( "select a.*,b.DEPT_NAME AS PARENT_NAME from $this->tbl_name as a left join $this->tbl_name as b on b.DEPT_ID=a.PARENT_ID  where 1   order by a.DEPT_SORT asc" );
		}
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			$data[] = $rs;
		}
		
		return $data;
	}
	/**
	 * 部门下拉
	 * @param $dept_id
	 */
	function options($dept_id=null)
	{
		$data = $this->DeptTree();
		$options = '';
		if ($data)
		{
			foreach ($data as $key=>$rs)
			{
				$options .='<option '.($dept_id && $rs['DEPT_ID']==$dept_id ? 'selected' : '').' value="'.$rs['DEPT_ID'].'">'.($rs['level'] ? str_repeat('&nbsp;',($rs['level']*3)).'|--'.$rs['DEPT_NAME'] : $rs['DEPT_NAME']).'</option>';
			}
		}
		return $options;
	}
	/**
	 * 重组数组
	 */
	function rest($data, $type = 'PARENT_ID')
	{
		$arr = array();
		if ($data)
		{
			foreach ( $data as $key => $val )
			{
				if ($type == 'PARENT_ID')
				{
					$arr[$val['PARENT_ID']][$val['DEPT_ID']] = $val;
				} else
				{
					$arr[$val['DEPT_ID']] = $val;
				}
			}
		}
		return $arr;
	}
	
	/**
	 * 部门树型
	 */
	function DeptTree($dept_id = null)
	{
		$data = $this->DeptList ( $dept_id );
		if ($data)
		{
			$temp = array();
			foreach ( $data as $key => $rs )
			{
				$temp[$rs['PARENT_ID']][$rs['DEPT_ID']] = $rs;
			}
			return $temp ? tree ( $temp, 0, 1 ) : false;
		} else
		{
			return false;
		}
	}
	/**
	 * 获取父部门
	 * @param unknown_type $dept_id
	 */
	function GetParent($dept_id, $data = null, $del_before = false)
	{
		$data = $data ? $data : $this->rest ( $this->DeptList ( null, true ), 'DEPT_ID' );
		static $tree = array();
		if ($del_before)
		{
			$tree = array();
		}
		$parent_id = $data[$dept_id]['PARENT_ID'];
		if ($dept_id && $parent_id)
		{
			$tree[] = $data[$parent_id];
			$this->GetParent ( $parent_id, $data );
		}
		return $tree;
	}
	/**
	 * 获取所有上级部门ID
	 */
	function GetParent_ID($dept_id)
	{
		$dept = $this->GetParent ( $dept_id );
		$arr = array();
		if ($dept)
		{
			foreach ( $dept as $key => $rs )
			{
				$arr[$rs['DEPT_NAME']] = $rs['DEPT_ID'];
			}
		}
		return $arr;
	}
	/**
	 * 获取子部门
	 * @param $dept_id
	 */
	function GetSon($dept_id, $data = null, $del_before = false)
	{
		$data = $data ? $data : $this->rest ( $this->DeptList ( null, true ), 'PARENT_ID' );
		static $tree = array();
		if ($del_before)
		{
			$tree = array();
		}
		if ($dept_id && $data[$dept_id])
		{
			foreach ( $data[$dept_id] as $key => $rs )
			{
				$tree[] = $rs;
				$this->GetSon ( $rs['DEPT_ID'], $data );
			}
		}
		return $tree;
	}
	/**
	 * 获取子部门
	 * @param $dept_id
	 */
	function GetSonAll($dept_id, $data = null, $del_before = false)
	{
		$data = $data ? $data : $this->rest ( $this->DeptListAll ( null, true ), 'PARENT_ID' );
		static $tree = array();
		if ($del_before)
		{
			$tree = array();
		}
		if ($dept_id && $data[$dept_id])
		{
			foreach ( $data[$dept_id] as $key => $rs )
			{
				$tree[] = $rs;
				$this->GetSon ( $rs['DEPT_ID'], $data );
			}
		}
		return $tree;
	}
	/**
	 * 获取所有子部门ID
	 */
	function GetSon_ID($dept_id,$del_before = false)
	{
		$dept = $this->GetSon ( $dept_id,null,$del_before);
		$arr = array();
		if ($dept)
		{
			foreach ( $dept as $key => $rs )
			{
				$arr[$rs['DEPT_NAME']] = $rs['DEPT_ID'];
			}
		}
		return $arr;
	}
	/**
	 * 获取所有子部门ID
	 */
	function GetSonIDAll($dept_id,$del_before = false)
	{
		$dept = $this->GetSonAll ( $dept_id,null,$del_before);
		$arr = array();
		if ($dept)
		{
			foreach ( $dept as $key => $rs )
			{
				$arr[$rs['DEPT_NAME']] = $rs['DEPT_ID'];
			}
		}
		return $arr;
	}
	/**
	 * 获取上下级别
	 * @param unknown_type $dept_id
	 */
	function GetUpperAndLower($dept_id)
	{
		$parent = $this->GetParent($dept_id);
		$son = $this->GetSon($dept_id);
		$arr = array_merge($parent,$son);
		asort($arr);
		return $arr;
	}
	/**
	 * 获取上下级别ID
	 * @param $dept
	 */
	function GetUpperAndLower_ID($dept)
	{
		$dept = $this->GetUpperAndLower($dept);
		$arr = array();
		if ($dept)
		{
			foreach ( $dept as $key => $rs )
			{
				$arr[$rs['DEPT_NAME']] = $rs['DEPT_ID'];
			}
		}
		return $arr;
	}
	/**
	 * 添加部门
	 * @param unknown_type $data
	 */
	function Add($data)
	{
		$DFLAG = 0;
		if ($data['PARENT_ID'])
		{
			$rs = $this->find ( array('DEPT_ID'=>$data['PARENT_ID']), null, '*' );
			$DFLAG = $rs['Dflag']+1;
		}
		$row = $this->get_one ( "select max(Depart_x) as max from department where Depart_x like '" . $rs['Depart_x'] . "__'" );
		if ($row['max'] == null)
		{
			$Depart_x = $rs['Depart_x'] * 100 + 1;
		} else
		{
			$Depart_x = $row['max'] + 1;
		}
		$Depart_x = str_pad ( $Depart_x, strlen ( $rs['Depart_x'] ) + 2, "0", STR_PAD_LEFT );
		$data['Dflag'] = $DFLAG;
		$data['Depart_x'] = $Depart_x;
		if ($data['PARENT_ID'])
		{
			$rs = $this->get_one("select max(DEPT_NO) as DEPT_NO ,max(DEPT_SORT) as DEPT_SORT from $this->tbl_name where PARENT_ID=".$data['PARENT_ID']);
			$data['DEPT_NO'] = $rs['DEPT_NO']+1;
			$data['DEPT_SORT'] = $rs['DEPT_NO']+1;
		}
		return $this->create($data);
	
	}
	
	/**
	 * 获取父部门
	 * @param unknown_type $dept_id
	 */
	function GetParents($dept_id, $data = null, $del_before = false)
	{
		$data = $data ? $data : $this->rest ( $this->DeptList ( null, true ), 'DEPT_ID' );
		$tree = array();
		$parent_id = $data[$dept_id]['PARENT_ID'];
		if ($dept_id && $parent_id&&$parent_id!=0)
		{
			$tree1[] = $data[$parent_id];
			$trees = $this->GetParents ( $parent_id, $data);
           $tree=array_reverse(array_merge($trees,$tree1));
		}
		return $tree;
	}
	
	/**
	 * 获取所有上级部门ID
	 */
	function GetParents_ID($dept_id)
	{
		$dept = $this->GetParents ( $dept_id );
		$arr = array();
		if ($dept)
		{
			foreach ( $dept as $key => $rs )
			{
				$arr[$rs['DEPT_NAME']] = $rs['DEPT_ID'];
			}
		}
		return $arr;
	}
	
}
?>