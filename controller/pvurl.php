<?php
class controller_pvurl extends model_pvurl
{
	public $show; // ģ����ʾ
	/**
	 * ���캯��
	 *
	 */
	function __construct()
	{
		parent::__construct();
		$this->pk = 'id';
		$this->show = new show();
	}
	/**
	 * Ĭ�Ϸ�����ʾ
	 *
	 */
	function c_index()
	{
		$this->show->assign('tid',$_GET['tid']);
		$this->show->assign('typeid',$_GET['typeid']);
		if (intval($_GET['id']))
		{
			$this->show->assign('id',$_GET['id']);
			$this->show->assign('checked_1',($_GET['type']==1 ? 'checked':''));
			$this->show->assign('checked_2',($_GET['type']==2 ? 'checked':''));
			$this->show->assign('checked_3',($_GET['type']==3 ? 'checked':''));
			$this->show->assign('userid',$_GET['userid']);
			$this->show->assign('jobsid',$_GET['jobsid']);
			$this->show->assign('deptid',$_GET['deptid']);
			$this->show->assign('dept_id',$_GET['dept_id']);
			$this->show->assign('type',$_GET['type']);
			$this->show->assign('username',$_GET['username']);
			$str = '<input type="radio" '.($_GET['content']=='1' ? 'checked' : '').' name="content[]" value="1" />�� ';
			$str .= '<input type="radio" '.($_GET['content']=='0' ? 'checked' : '').' name="content[]" value="0" />��';
			$this->show->assign('list',$str);
			$this->show->display('purview_edit-userpv');
		}else{
			$str = '<input type="radio" checked name="content[]" value="1" />�� ';
			$str .= '<input type="radio" name="content[]" value="0" />��';
			$this->show->assign('list',$str);
			$this->show->display('purview_add-userpv');
		}
	}

	function c_show_act()
	{
		if ($_GET['act'])
		{
			if (method_exists($this,'model_'.$_GET['act']))
			{
				$this->show->assign('tid',$_GET['tid']);
				$this->show->assign('typeid',$_GET['typeid']);
				$this->show->assign('list',$this->{'model_'.$_GET['act']}());
				if (intval($_GET['id']))
				{
					$this->show->assign('id',$_GET['id']);
					$this->show->assign('checked_1',($_GET['type']==1 ? 'checked':''));
					$this->show->assign('checked_2',($_GET['type']==2 ? 'checked':''));
					$this->show->assign('checked_3',($_GET['type']==3 ? 'checked':''));
					$this->show->assign('checked_4',($_GET['type']==4 ? 'checked':''));
					$this->show->assign('visit_1',($_GET['visit']==1 ? 'checked':''));
					$this->show->assign('visit_0',($_GET['visit']=='0' ? 'checked':''));
					$this->show->assign('userid',$_GET['userid']);
					$this->show->assign('jobsid',$_GET['jobsid']);
					$this->show->assign('deptid',$_GET['deptid']);
					$this->show->assign('dept_id',$_GET['dept_id']);
					$this->show->assign('type',$_GET['type']);
					$this->show->assign('username',$_GET['username']);
					$this->show->display('purview_edit-userpv');
				}else{
					$this->show->display('purview_add-userpv');
				}
			}else{
				showmsg('act��ֵ��'.$_GET['act'].'�������Ķ�Ӧ����������model�У����麯�����ƣ�');
			}
		}else{
			showmsg('act��������Ϊ�գ�');
		}
	}

	function c_show_field()
	{
		if ($_GET['act'])
		{
			$this->show->assign('tid',$_GET['tid']);
			$this->show->assign('typeid',$_GET['typeid']);
			$this->show->assign('list',$this->model_show_field());
			if (intval($_GET['id']))
			{
				$this->show->assign('id',$_GET['id']);
				$this->show->assign('checked_1',($_GET['type']==1 ? 'checked':''));
				$this->show->assign('checked_2',($_GET['type']==2 ? 'checked':''));
				$this->show->assign('checked_3',($_GET['type']==3 ? 'checked':''));
				$this->show->assign('userid',$_GET['userid']);
				$this->show->assign('jobsid',$_GET['jobsid']);
				$this->show->assign('deptid',$_GET['deptid']);
				$this->show->assign('dept_id',$_GET['dept_id']);
				$this->show->assign('type',$_GET['type']);
				$this->show->assign('username',$_GET['username']);
				$this->show->display('purview_edit-userpv');
			}else{
				$this->show->display('purview_add-userpv');
			}
		}

	}
	//##############################################����#################################################
	/**
	 * ��������
	 */
	function __destruct()
	{

	}
}
?>