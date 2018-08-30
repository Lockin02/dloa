<?php
class controller_system_dept extends C_base
{
	function __construct()
	{
		parent::__construct();
		$this->show->path = 'system/dept/';
	}
	
	function c_index()
	{
		$this->c_list();
	}
	function c_list()
	{
		$data = $this->M->DeptTree();
		$str = '';
		if ($data)
		{
			foreach ($data as $rs)
			{
				$str .='<tr>';
				$str .='<td>'.$rs['DEPT_ID'].'</td>';
				$str .='<td>'.($rs['PARENT_NAME'] ? $rs['PARENT_NAME'] : '<span>一级部门<span>').'</td>';
				$str .= '<td align="left"><span style="margin-left:'.($rs['level']*25).'px;color:blue;">'.($rs['level'] > 0 ? '|--':'').$rs['DEPT_NAME'].'</span></td>';
				$str .='<td>'.$rs['TEL_NO'].'</td>';
				$str .='<td>'.$rs['FAX_NO'].'</td>';
				$str .='<td>'.$rs['DEPT_FUNC'].'</td>';
				$str .='<td><a href="?model=system_dept&action=sort&key='.$rs['rand_key'].'&parent_id='.$rs['PARENT_ID'].'&sort='.$rs['DEPT_SORT'].'&type=down"><img src="images/top.png" border="0"></a> <a href="?model=system_dept&action=sort&key='.$rs['rand_key'].'&parent_id='.$rs['PARENT_ID'].'&sort='.$rs['DEPT_SORT'].'&type=top"><img src="images/down.png" border="0"></a></td>';
				$str .='<td>'.thickbox_link('添加子部门','a',array('dept_id'=>$rs['DEPT_ID'],'key'=>$rs['rand_key']),'添加子部门',null,'add',500).' | ';
				$str .=thickbox_link('设置职位','a','dept_id'.$rs['DEPT_ID'],'设置 '.$rs['DEPT_NAME'].' 的职位','system_jobs','list','600').' | ';
				$str .=thickbox_link('设置权限','a','dept_id'.$rs['DEPT_ID'],'设置 '.$rs['DEPT_NAME'].' 的权限','jobs','list','600');
				$str .='</td>';
				$str .='</tr>';
				
			}
		}else{
			$str = '<tr><td colspan="8">暂无数据</td></tr>';
		}
		
		$this->show->assign('list',$str);
		$this->show->display('dept-list');
		
	}
	/**
	 * 设置排序
	 */
	function c_sort()
	{
		if ($_GET['key'] && $_GET['sort'] && $_GET['type'])
		{
			if ($this->M->SetSort($_GET['key'],'DEPT_SORT',$_GET['sort'],$_GET['type'],array('PARENT_ID'=>$_GET['parent_id'])))
			{
				header('Location: ?model=system_dept');
			}else{
				showmsg('设置排序失败！');
			}
		}else{
			showmsg('非法请求！');
		}
	}
	
	function c_add()
	{
		if ($_POST)
		{
			if ($this->M->Add($_POST))
			{
				showmsg('添加成功','parent.location.reload();','button');
			}else{
				showmsg('添加失败！');
			}
		}else{
			$data = $this->M->DeptTree();
			$options = '';
			if ($data)
			{
				foreach ($data as $key=>$rs)
				{
					$options .='<option '.($rs['DEPT_ID']==$_GET['dept_id'] ? 'selected' : '').' value="'.$rs['DEPT_ID'].'">'.($rs['level'] ? str_repeat('&nbsp;',($rs['level']*3)).'|--'.$rs['DEPT_NAME'] : $rs['DEPT_NAME']).'</option>';
				}
			}
			$this->show->assign('options',$options);
			$this->show->display('dept-add');
		}
	}
}

?>