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
						$val = '紧急';
					}elseif ($val == 1){
						$val = '中等';
					}else{
						$val = '一般';
					}
				}elseif ($key=='task_type')
				{
					if ($val == 1)
					{
						$val = '周期发送';
					}else{
						$val = '定时发送';
					}
				}elseif ($key == 'status'){
					if ($data['pause']==1){
						$val = '暂停执行';
					}elseif ($val==1)
					{
						$val = '已完成';
					}elseif ($key == 0){
						$val = '待执行';
					}
				}
				$this->show->assign($key,$val);
			}
			
			if (!$data['userid'])
			{
				$this->show->assign('target',$data['address']);
			}else{
				$this->show->assign('run_week',($data['week_date'] ? '每周：'.$data['week_date'] : '每月：'.$data['month_day']));
				$dept = $data['target_dept'] ? '指定部门' : '所有部门';
				$area = $data['target_area'] ? '指定区域' : '所有区域';
				$jobs = $data['target_jobs'] ? '指定职位' : '所有职位';
				$user = $data['target_user'] ? '指定用户' : '所有用户';
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
	 * 发送错误邮件地址
	 */
	function c_erorr_email_list()
	{
		
	}
	/**
	 * 添加群发任务
	 */
	function c_add_task()
	{
		if ($_POST)
		{
			if ($this->model_save($_POST,'add'))
			{
				showmsg('添加任务成功！', 'self.parent.location.reload();', 'button' );
			}else{
				showmsg('添加任务失败！');
			}
		}else{
			$this->show->display('add');
		}
	}
	/**
	 * 修改任务
	 */
	function c_edit_task()
	{
		if ($_POST)
		{
			if ($this->model_save($_POST,'edit'))
			{
				showmsg('修改任务成功！', 'self.parent.location.reload();', 'button' );
			}else{
				showmsg('修改任务失败，请与管理员联系！');
			}
		}else{
			$this->tbl_name = 'email_task';
			$row = $this->find('id='.$_GET['id']);
			$info = new model_info_notice();
			$confirm = '<div style="text-align: center;"><input type="button" onclick="tb_remove();" value=" 确定 "/></div>';
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
	 * 删除任务
	 */
	function c_del()
	{
		if ($_GET['id'] && $_GET['key'])
		{
			if ($this->model_del($_GET['id'],$_GET['key']))
			{
				showmsg('删除成功！', 'self.parent.location.reload();', 'button' );
			}else{
				showmsg('删除失败，请与管理员联系！');
			}
		}else{
			showmsg('非法参数！');
		}
	}
}

?>