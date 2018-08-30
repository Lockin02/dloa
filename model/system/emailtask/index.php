<?php
class model_system_emailtask_index extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'email_task';
	}
	
	/**
	 * 任务列表
	 */
	function model_task_list()
	{
		global $func_limt;
		$task_type = $_GET['task_type'] ? $_GET['task_type'] : $_POST['task_type'];
		$level = $_GET['level'] ? $_GET['level'] : $_POST['level'];
		$start_date = $_GET['start_date'] ? $_GET['start_date'] : $_POST['start'];
		$end_date = $_GET['end_date'] ? $_GET['end_date'] : $_POST['end_date'];
		$username = $_GET['username'] ? $_GET['username'] : $_POST['username'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		
		$where = $task_type!='' ? " a.task_type='$task_type'" : '';
		$where .= $level!='' ? ($where ? ' and ' : '')." a.task_level='$level'" : '';
		if ($start_date && $end_date)
		{
			$where .= ($where ? ' and ' : '')." a.send_time >='$start_date' and a.send_time <='$end_date'";
		}
		if ($username)
		{
			if ($username=='系统')
			{
				$where .= ($where ? ' and ' : '')." a.userid is null ";
			}else{
				$userid = $this->get_table_fields('user',"user_name='$username'",'user_id');
				$where .= ($where ? ' and ' : '')." a.userid='$userid'";
			}
			
		}
		if ($keyword)
		{
			$where .= ($where ? ' and ' : '')." (a.title like '%$keyword%' or a.task_name like '%$keyword%')";
		}
		
		$where = $where ? ' where '.$where : '';
		
		if(!$this->num)
		{
			$rs = $this->get_one("select count(0) as num from email_task as a $where");
			$this->num = $rs['num'];
		}
		if ($this->num)
		{
			$query = $this->query("
									select 
										a.*,b.user_name,d.num
									from
										email_task as a
										left join user as b on b.user_id=a.userid
										left join (select count(0) as num,tid from email_error group by tid) as d on d.tid=a.id
									$where
										order by id desc
										limit $this->start,".pagenum);
			while (($rs = $this->fetch_array($query))!=false) {
				$str .='<tr>';
				$str .='<td>'.$rs['id'].'</td>';
				$str .='<td>'.($rs['user_name'] ? $rs['user_name'] : '系统').'</td>';
				$str .='<td>'.$rs['task_name'].'</td>';
				$str .='<td>'.($rs['task_level']==1 ? '中等' : ($rs['task_level']==2 ? '紧急' : '一般')).'</td>';
				$str .='<td>'.($rs['task_type']==1 ? '周期发送': '定时发送').'</td>';
				$str .='<td align="left">'.(($rs['task_type']==1 && $rs['week_type']==1) ? '每月：'.$rs['month_day'] : ($rs['task_type']==1 ? '每周：'.$rs['week_date'] : '')).'</td>';
				$str .='<td nowrap width="20%">'.$rs['title'].'</td>';
				$str .='<td>'.($rs['task_type'] ? $rs['week_send_time'] : $rs['send_time']).'</td>';
				$str .='<td>'.$rs['run_time'].'</td>';
				$str .='<td>'.$rs['end_time'].'</td>';
				$str .='<td>'.$rs['run_num'].'</td>';
				$str .='<td>'.$rs['error_num'].'</td>';
				$str .='<td>'.($rs['status']==1 ? '已完成' :($rs['status']==2 ? '进行中...' : '待发送')).'</td>';
				$str .='<td id="td_'.$rs['id'].'"><a href="javascript:show_task('.$rs['id'].',\''.$rs['rand_key'].'\')">查看</a> | ';
				if ($rs['task_type']==1 || !$rs['run_time'])
				{
					if ($rs['pause']==0)
					{
						$str .='<a href="javascript:pause('.$rs['id'].',\''.$rs['rand_key'].'\',1);">暂停</a> | ';
					}else{
						$str .='<a href="javascript:pause('.$rs['id'].',\''.$rs['rand_key'].'\',0);"><SPAN>恢复</SPAN></a> | ';
					}
					
				}
				if (!$rs['run_time'] || $rs['task_type']==1)
				{
					$str .=thickbox_link('修改','a','id='.$rs['id'].'&key='.$rs['rand_key'],'修改'.$rs['task_name'],null,'edit_task',700,500).' | ';
				}
					$str .=($rs['userid'] ? '<a href="javascript:del('.$rs['id'].',\''.$rs['rand_key'].'\')">删除</a></td>' : '');
				$str.='</tr>';
			}
			$showpage = new includes_class_page ();
			$showpage->show_page ( array(
				
						'total'=>$this->num,
						'perpage'=>pagenum
			) );
			$showpage->_set_url ( 'num=' . $this->num . '&task_type=' . $task_type . '&level=' . $level . 'username=' . $username.'&start_date='.$start_date.'&end_date='.$end_date.'&keyword='.urlencode($keyword) );
			return $str . '<tr><td colspan="20">' . $showpage->show ( 6 ) . '</td></tr>';
			
		}
	}
	/**
	 * 发送错误邮件列表
	 */
	function model_erorr_email_list()
	{
		
	}
	/**
	 * 添加任务
	 * @param Array $data
	 */
	function model_save($data,$type='add')
	{
		$field = array();
		$field['userid'] = $_SESSION['USER_ID'];
		$field['task_name'] = $data['task_name'];
		$field['task_level'] = $data['task_level'];
		$field['task_type'] = $data['task_type'];
		$field['title'] = $data['title'];
		$field['content'] = $data['content'];
		
		if ($data['task_type']=='1')
		{
			$field['week_type'] = $data['week_type'];
			$field['week_send_time'] = $data['send_time'];
			if ($data['week_type']=='1')
			{
				$field['month_day'] = ($data['month_day'] ? implode(',',$data['month_day']) : '');
			}else{
				$field['week_date'] = ($data['week_date'] ? implode(',',$data['week_date']) : '');
			}
		}else{
			$field['send_time'] = $data['send_datetime'];
		}
		if ($data['target_type']==1)
		{
			$field['target_sql'] = str_replace('&nbsp;',' ',$data['target_sql']);
		}else{
			$field['target_sql'] = '';
			if ($data['dept']=='1')
			{
				$field['target_dept'] = ($data['dept_id'] ? implode(',',$data['dept_id']) : '');
			}else{
				$field['target_dept'] = '';
			}
			if ($data['area']=='1')
			{
				$field['target_area'] = ($data['areaid'] ? implode(',',$data['areaid']) : '');
			}else{
				$field['target_area'] = '';
			}
			if ($data['jobs']=='1')
			{
				$field['target_jobs'] = ($data['jobsid'] ? implode(',',$data['jobsid']) : '');
			}else{
				$field['target_jobs'] = '';
			}
			if ($data['user']=='1')
			{
				$field['target_user'] = ($data['user_id'] ? implode(',',$data['user_id']) : '');
			}else{
				$field['target_user'] = '';
			}
		}
		if ($type == 'add')
		{
			return $this->create($field);
		}elseif ($type == 'edit'){
			return $this->update(array('id'=>$data['task_id'],'rand_key'=>$data['key']),$field);
		}
	}
	/**
	 * 删除
	 * @param $id
	 * @param $key
	 */
	function model_del($id,$key)
	{
		if ($id && $key)
		{
			return $this->delete(array('id'=>$id,'rand_key'=>$key));
		}else{
			return false;
		}
	}
}

?>