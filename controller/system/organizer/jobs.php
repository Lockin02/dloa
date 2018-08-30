<?php
class controller_system_organizer_jobs extends model_system_organizer_jobs
{
	public $func_id = '712';
	public $show;
	
	function __construct()
	{
		parent::__construct ();
		$this->show = new show ();
	}
	
	function c_index()
	{
		global $func_limit;
		$dept = new includes_class_depart ();
		$deptI=(array)explode(',',$func_limit['������Ȩ��']?$func_limit['������Ȩ��']:0);
		$this->show->assign ( 'dept_select', $dept->depart_select_limit ($deptI) );
		$this->show->assign ( 'url', '?model=' . $_GET['model'] . '&action' . $_GET['action'] );
		$this->show->assign ( 'search_dept', $dept->depart_select_limit ($deptI, $_GET['searchdept'] ) );
		$this->show->assign ( 'list', $this->showlist () );
		$add_str='<form action="?model=jobs&action=add" method="POST" onsubmit="return check();">
				<table border="0" width="60%" class="small" cellpadding="0" cellspacing="0" align="center" style="margin-bottom:3px;">
				
					<tr class="tablecontrol">
						<td>ְλ���ƣ�<input type="text" id="name" name="name" value="" /></td>
						<td>�������ţ�
							<select id="deprtid" name="deprtid">
								<option value="">��ѡ����</option>
								'.$dept->depart_select_limit ($deptI).'
							</select>
						</td>
						<td>����<input type="text" size="5" id="level" name="level" value="0" /></td>
						<td><input type="submit" value=" �� �� " /></td>
					</tr>
				
				</td>
				</table>
				</form>';
		$this->show->assign ( 'add_form', $func_limit['���Ȩ��']?$add_str:'');
		$this->show->display ( 'system_organizer_jobs_list' );
	}
	function c_show_user()
	{
		if (intval ( $_GET['id'] ))
		{
			$data = $this->model_dept_user ( $_GET['dept_id'] ,$_GET['id']);
			if ($data)
			{
				foreach ( $data as $key=>$row )
				{
					if ($row['jobs_id'] == $_GET['id'])
					{
						$str .= '<input type="checkbox" checked name="user_id[]" value="' . $row['USER_ID'] . '" />'.$row['USER_NAME'];
					} else
					{
						$str .= '<input type="checkbox" name="user_id[]" value="' . $row['USER_ID'] . '" />'.$row['USER_NAME'];
					}
					if ($key > 1&& $key%7 == 0) $str.='<br />';
				}
			}
			$this->show->assign ( 'list', $str );
			$this->show->display ( 'system_organizer_jobs_show-user' );
		}
	}
	function c_detail()
	{
		if (intval ( $_GET['id'] ))
		{
			$data = $this->model_dept_user ( $_GET['dept_id'] ,$_GET['id']);
			if ($data)
			{
				foreach ( $data as $key=>$row )
				{
					if ($row['jobs_id'] == $_GET['id'])
					{
						$str .= '<input type="checkbox" checked name="user_id[]" disabled value="' . $row['USER_ID'] . '" />'.$row['USER_NAME'];
					} else
					{
						$str .= '<input type="checkbox" name="user_id[]" disabled value="' . $row['USER_ID'] . '" />'.$row['USER_NAME'];
					}
					if ($key > 1&& $key%7 == 0) $str.='<br />';
				}
			}
			$this->show->assign ( 'list', $str );
			$this->show->display ( 'system_organizer_jobs_detail' );
		}
	}
	function c_edit_jobs_user()
	{
		if ($_GET['id'])
		{
			if ($this->model_edit_jobs_user($_GET['id'],$_POST))
			{
				showmsg('�޸ĳɹ���','self.parent.tb_remove();', 'button');
			}else{
				showmsg('�޸�ʧ�ܣ�');
			}
		}
	}
	
	function c_edit_func()
	{
		$this->show->assign ( 'id', $_GET['id'] );
		$this->show->assign ( 'list', $this->edit_purview () );
		$this->show->display ( 'system_organizer_jobs_editpurivew' );
	}
	function c_add()
	{
		if ($this->insert ())
		{
			showmsg ( '��ӳɹ���', '?model=system_organizer_jobs', 'link' );
		}
	}
	function c_save_func()
	{
		if ($this->model_save_func ())
		{
			showmsg ( '����ְλȨ�޳ɹ���', 'self.parent.tb_remove();', 'button' );
		} else
		{
			showmsg ( '����ְλȨ��ʧ�ܣ�', 'self.parent.tb_remove();', 'button' );
		}
	}
}
?>