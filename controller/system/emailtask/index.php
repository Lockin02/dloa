<?php
class controller_system_emailtask_index extends model_system_emailtask_index
{
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'system/emailtask/';
	}
	
	function c_task_list()
	{
		$username = $_GET['username'] ? $_GET['username'] : $_POST['username'];
		$start_date = $_GET['start_date'] ? $_GET['start_date'] : $_POST['start'];
		$end_date = $_GET['end_date'] ? $_GET['end_date'] : $_POST['end_date'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$this->show->assign('username',$username);
		$this->show->assign('start_date',$start_date);
		$this->show->assign('end_date',$end_date);
		$this->show->assign('keyword',$keyword);
		$this->show->assign('list', $this->model_task_list());
		$this->show->display('list');
	}
	
	function c_show_task()
	{
		$data = $this->find(array('id'=>$_GET['id'],'rand_key'=>$_GET['key']));
		if ($data)
		{
			foreach ($data as $key=>$val)
			{
				if ($key == 'task_level')
				{
					if ($val==2)
					{
						$val = '����';
					}elseif ($val == 1){
						$val = '�е�';
					}else{
						$val = 'һ��';
					}
				}elseif ($key=='task_type')
				{
					if ($val == 1)
					{
						$val = '���ڷ���';
					}else{
						$val = '��ʱ����';
					}
				}elseif ($key == 'status'){
					if ($data['pause']==1){
						$val = '��ִͣ��';
					}elseif ($val==1)
					{
						$val = '�����';
					}elseif ($key == 0){
						$val = '��ִ��';
					}
				}
				$this->show->assign($key,$val);
			}
			
			if (!$data['userid'])
			{
				$this->show->assign('target',$data['address']);
			}else{
				$this->show->assign('run_week',($data['week_date'] ? 'ÿ�ܣ�'.$data['week_date'] : 'ÿ�£�'.$data['month_day']));
				$dept = $data['target_dept'] ? 'ָ������' : '���в���';
				$area = $data['target_area'] ? 'ָ������' : '��������';
				$jobs = $data['target_jobs'] ? 'ָ��ְλ' : '����ְλ';
				$user = $data['target_user'] ? 'ָ���û�' : '�����û�';
				if ($data['target_user'])
				{
					$this->show->assign('target',$data['target_user']);
				}else{
					$this->show->assign('target',$dept.'/'.$area.'/'.$jobs.'/'.$user);
				}
			}
		}
		
		$this->show->display('info');
	}
	/**
	 * ���ʹ����ʼ���ַ
	 */
	function c_erorr_email_list()
	{
		
	}
	/**
	 * ���Ⱥ������
	 */
	function c_add_task()
	{
		if ($_POST)
		{
			if ($this->model_save($_POST,'add'))
			{
				showmsg('�������ɹ���', 'self.parent.location.reload();', 'button' );
			}else{
				showmsg('�������ʧ�ܣ�');
			}
		}else{
			$this->show->display('add');
		}
	}
	/**
	 * �޸�����
	 */
	function c_edit_task()
	{
		if ($_POST)
		{
			if ($this->model_save($_POST,'edit'))
			{
				showmsg('�޸�����ɹ���', 'self.parent.location.reload();', 'button' );
			}else{
				showmsg('�޸�����ʧ�ܣ��������Ա��ϵ��');
			}
		}else{
			$this->tbl_name = 'email_task';
			$row = $this->find('id='.$_GET['id']);
			$info = new model_info_notice();
			$confirm = '<div style="text-align: center;"><input type="button" onclick="tb_remove();" value=" ȷ�� "/></div>';
			foreach ($row as $key=>$val)
			{
				if ($key == 'content')
				{
					$val = htmlspecialchars($val);
				}elseif ($key == 'task_level'){
					$this->show->assign('selected_level_0',($val==0 ? 'selected' : ''));
					$this->show->assign('selected_level_1',($val==1 ? 'selected' : ''));
					$this->show->assign('selected_level_2',($val==2 ? 'selected' : ''));
				}elseif ($key == 'task_type'){
					$this->show->assign('selected_type_0',($val==0 ? 'selected' : ''));
					$this->show->assign('selected_type_1',($val==1 ? 'selected' : ''));
					
					$this->show->assign('tr_week_type',($val==0 ? 'none' : ''));
					$this->show->assign('tr_datetime',($val==0 ? '' : 'none'));
					$this->show->assign('tr_time',($val==0 ? 'none' : ''));
				}elseif ($key == 'week_type'){
					$this->show->assign('selected_week_0',($val == 0 ? 'selected' : ''));
					$this->show->assign('selected_week_1',($val == 1 ? 'selected' : ''));
					$this->show->assign('tr_week',($val==0 && $row['task_type']==1 ? '' : 'none'));
					$this->show->assign('tr_day',($val == 0 ? 'none' : ''));
				}elseif ($key=='target_dept'){
					if ($val)
					{
						$this->show->assign('dept',$info->get_dept($val).$confirm);
						$this->show->assign('selected_dept','selected');
						$this->show->assign('show_dept','');
					}else{
						$this->show->assign('dept','');
						$this->show->assign('selected_dept','');
						$this->show->assign('show_dept','none');
					}
				}elseif ($key == 'target_area'){
					if ($val)
					{
						$this->show->assign('area',$info->get_area($val).$confirm);
						$this->show->assign('selected_area','selected');
						$this->show->assign('show_area','');
					}else{
						$this->show->assign('area','');
						$this->show->assign('selected_area','');
						$this->show->assign('show_area','none');
					}
				}elseif ($key == 'target_jobs'){
					if ($val)
					{
						$this->show->assign('jobs',$info->get_jobs($row['target_dept'],$val),$confirm);
						$this->show->assign('selected_jobs','selected');
						$this->show->assign('show_jobs','');
					}else{
						$this->show->assign('jobs','');
						$this->show->assign('selected_jobs','');
						$this->show->assign('show_jobs','none');
					}
				}elseif ($key == 'target_user'){
					if ($val)
					{
						$this->show->assign('user',$info->get_user_list($row['target_dept'],$row['target_area'],$row['target)jobs'],$val).$confirm);
						$this->show->assign('selected_user','selected');
						$this->show->assign('show_user','');
					}else{
						$this->show->assign('user','');
						$this->show->assign('selected_user','');
						$this->show->assign('show_user','none');
						
					}
				}elseif ($key == 'target_sql'){
					if ($val)
					{
						$this->show->assign('checked_0','');
						$this->show->assign('checked_1','checked');
						$this->show->assign('sql_display','');
						$this->show->assign('select_display','none');
					}else{
						$this->show->assign('checked_0','checked');
						$this->show->assign('checked_1','');
						$this->show->assign('sql_display','none');
						$this->show->assign('select_display','');
					}
				}
				$this->show->assign($key,$val);
			}
			$this->show->display('edit');
		}
	}
	/**
	 * ɾ������
	 */
	function c_del()
	{
		if ($_GET['id'] && $_GET['key'])
		{
			if ($this->model_del($_GET['id'],$_GET['key']))
			{
				showmsg('ɾ���ɹ���', 'self.parent.location.reload();', 'button' );
			}else{
				showmsg('ɾ��ʧ�ܣ��������Ա��ϵ��');
			}
		}else{
			showmsg('�Ƿ�������');
		}
	}
}

?>