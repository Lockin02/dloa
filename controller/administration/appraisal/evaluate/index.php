<?php
class controller_administration_appraisal_evaluate_index extends model_administration_appraisal_evaluate_index 
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'administration/appraisal/evaluate/';
	}
	/**
	 * 默认首页
	 */
	function c_index()
	{
		$this->c_mylist();
	}
	/**
	 * 我的评价
	 */
	function c_mylist()
	{
		$data = $this->model_my_evaluators($_SESSION['USER_ID']);
		$evaluators_list = array();
		$tpl_user = array();
		$tpl = new model_administration_appraisal_performance_item();
		if ($data)
		{
			foreach ($data as $rs)
			{
				$temp = $tpl->GetTemplate($rs['user_id'], $this->last_quarter_year, $this->this_quarter);
				/*
				if ($this->this_quarter < 4)
				{
					$temp = $tpl->GetTemplate($rs['user_id']); //默认读取上一个季度
				}else{
					$temp = $tpl->GetTemplate($rs['user_id'],date('Y'),$this->this_quarter);
				}
				*/
				if ($temp)
				{
					$tmp = array();
					foreach ($temp as $row)
					{
						$info_list = $this->GetEvaluateList("a.evaluators_userid='".$_SESSION['USER_ID']."' and a.user_id='".$rs['user_id']."' and a.tpl_id=".$row['id']);
						if (!$info_list)
						{
							$tmp[] = array('id'=>$row['id'],'name'=>$row['name']);
						}
					}
					if ($tmp)
					{
						$evaluators_list[] = array('user_id'=>$rs['user_id'],'user_name'=>$rs['user_name']);
						$tpl_user[] = $tmp;
					}
				}
			}
		}
		$this->show->assign('year',$this->last_quarter_year);
		$this->show->assign('quarter',$this->this_quarter);
		//$this->show->assign('quarter',$this->this_quarter < 4 ? $this->last_quarter : $this->this_quarter);
		$this->show->assign('evaluators_list',json_encode(un_iconv($evaluators_list)));
		$this->show->assign('template',json_encode(un_iconv($tpl_user)));
		$this->show->display('list');
	}
	/**
	 * 我的评价记录
	 */
	function c_mylist_data()
	{
		$condition = " a.evaluators_userid='".$_SESSION['USER_ID']."' ";
		$years = $_GET ['years'] ? $_GET ['years'] : $_POST ['years'];
		$quarter = $_GET ['quarter'] ? $_GET ['quarter'] : $_POST ['quarter'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$years = $years ? $years : $this->last_quarter_year;
		$quarter = $quarter ? $quarter : ($this->this_quarter < 4 ? $this->last_quarter : $this->this_quarter);
		$condition .= $years ? " and a.years=$years " : '';
		$condition .= $quarter ? " and a.quarter=$quarter" : '';
		$condition .= $keyword ? " and d.name like '%$keyword%'" : '';
		$data = $this->GetEvaluateList( $condition, $_POST['page'], $_POST['rows'] );
		$json = array (
						'total' => $this->num 
		);
		if ($data)
		{
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		
		echo json_encode ( $json );
	}
	/**
	 *考核记录
	 */
	function c_list()
	{
		$this->show->assign('quarte',$this->last_quarter);
		$this->show->assign('year',$this->last_quarter_year);
		$this->show->display('all-list');
	}
	/**
	 * 考核评价记录
	 */
	function c_list_data()
	{
		global $func_limit;
		$dept_id_str = $func_limit['管理部门'] ? $func_limit['管理部门'] : $_SESSION['DEPT_ID'];
		$condition = " d.dept_id in($dept_id_str)";
		
		$dept_id = $_GET ['dept_id'] ? $_GET ['dept_id'] : $_POST ['dept_id'];
		$years = $_GET ['years'] ? $_GET ['years'] : $_POST ['years'];
		$quarter = $_GET ['quarter'] ? $_GET ['quarter'] : $_POST ['quarter'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		
		$condition .=$dept_id ? " and d.dept_id=".$dept_id : '';
		$condition .= $keyword ? " and (b.user_name like '%$keyword%')" : '';
		$data = $this->GetEvaluateCountList( $condition,$years,$quarter, $_POST['page'], $_POST['rows'] );
		$json = array (
						'total' => $this->num 
		);
		if ($data)
		{
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		
		echo json_encode ( $json );
	}
	/**
	 * 用户评价
	 */
	function c_get_user_evaluate_list()
	{
		$years = $_GET['years'] ? $_GET['years'] : $_POST['years'];
		$years = $years ? $years : $this->last_quarter_year;
		$quarter = $_GET['quarter'] ? $_GET['quarter'] : $_POST['quarter'];
		$quarter = $quarter ? $quarter : ($this->this_quarter < 4 ? $this->last_quarter : $this->this_quarter);
		$user_id = $_GET['user_id'] ? $_GET['user_id'] : $_POST['user_id'];
		$data = $this->model_user_evaluate_list($years,$quarter,$user_id);
		$str = '';
		if ($data)
		{
			foreach($data as $rs)
			{
				$str .='<div class="list_info">';
				$str .='<li>评价人：'.$rs['user_name'].'<li>';
				$str .='<li>总评分数：'.$rs['count_fraction'].'<li>';
				$str .='<li>评价时间：'.$rs['date'].'<li>';
				$str .='</div>';
			}
		}
		echo $str ? un_iconv($str) : un_iconv('<div>暂时无记录</div>');
	}
	/**
	 * 评价人管理
	 */
	function c_manage_list()
	{
		global $func_limit;
		//部门
		$dept_id = $func_limit['管理部门'] ? $func_limit['管理部门'] : $_SESSION['DEPT_ID'];
		$obj = new model_system_dept();
		$dept = $obj->DeptList();
		$dept_arr = array();
		if ($dept)
		{
			foreach ($dept as $rs)
			{
				$dept_arr[] = array('DEPT_ID'=>$rs['DEPT_ID'],'DEPT_NAME'=>$rs['DEPT_NAME']);
			}
		}
		//职位
		$jobs_obj = new model_system_jobs();
		$jobs = $jobs_obj->JobsList();
		$jobs_arr = array();
		if ($jobs)
		{
			foreach ($jobs as $rs)
			{
				$jobs_arr[] = array('jobs_id'=>$rs['id'],'jobs_name'=>$rs['name']);
			}
		}
		//项目
		$project = $this->GetProject($dept_id);
		$project_list[] = array('project_id'=>'','project_name'=>'所有项目组','selected'=>true);
		if ($project)
		{
			foreach ($project as $rs)
			{
				$project_list[] = array('project_id'=>$rs['id'],'project_name'=>$rs['name']);
			}
		}
		$this->show->assign('dept',json_encode(un_iconv($dept_arr)));
		$this->show->assign('jobs',json_encode(un_iconv($jobs_arr)));
		$this->show->assign('project',json_encode(un_iconv($project_list)));
		$this->show->display('manage');
	}
	/**
	 * 管理被评价人列表
	 */
	function c_manage_list_data()
	{
		global $func_limit;
		if ($func_limit['管理部门'])
		{
			$condition = " (b.dept_id in(".$func_limit['管理部门'].") or a.create_userid = '".$_SESSION['USER_ID']."')";
		}else{
			$condition = " a.create_userid = '".$_SESSION['USER_ID']."'";
		}
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
		$jobs_id = $_GET['jobs_id'] ? $_GET['jobs_id'] : $_POST['jobs_id'];
		$project_id = $_GET['project_id'] ? $_GET['project_id'] : $_POST['project_id'];
		if ($dept_id)
		{
			$condition .= " and b.dept_id=$dept_id";
		}
		if ($jobs_id)
		{
			$condition .= " and b.jobs_id=$jobs_id";
		}
		if ($project_id)
		{
			$condition .= " and d.id=".$project_id;
		}
		$data = $this->GetDataList($condition,$_POST['page'],$_POST['rows']);
		$json = array('total'=>$this->num);
		if ($data)
		{
			$json['rows'] = un_iconv($data);
		}else{
			$json['rows'] = array();
		}
		
		echo json_encode($json);
	}
	/**
	 * 添加评价人
	 */
	function c_save_evaluators()
	{
		if ($_POST)
		{
			if ($this->model_save_evaluators($_POST))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			showmsg('非法提交！');
		}
	}
	/**
	 * 删除
	 */
	function c_del()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($id)
		{
			if ($this->Del($id))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			showmsg('非法请求！');
		}
	}
	/**
	 * 检查用户是否已经存在
	 */
	function c_check_user()
	{
		$username = $_GET['username'] ? $_GET['username'] : $_POST['username'];
		if ($username)
		{
			$user_id = $this->GetUserID(mb_iconv($username));
			if ($user_id)
			{
				$info = $this->GetOneInfo(" user_id='".$user_id."'");
				if ($info)
				{
					echo 1;
				}else{
					echo -1;
				}
			}else{
				echo -1;
			}
		}else{
			showmsg('非法请求！');
		}
	}
	
	/**
	 * 部门联动职位 
	 */
	function c_get_jobs()
	{
		$dept_id = isset($_GET['dept_id']) ? $_GET['dept_id'] : $_POST['dept_id'];
		$jobs_obj = new model_system_jobs();
		$jobs = $jobs_obj->JobsList($dept_id);
		$jobs_arr = array();
		if ($_GET['type']=='list')
		{
			$jobs_arr[] = array('jobs_id'=>'','jobs_name'=>'所有职位','selected'=>true);
		}
		if ($jobs)
		{
			foreach ($jobs as $rs)
			{
				$jobs_arr[] = array('jobs_id'=>$rs['id'],'jobs_name'=>$rs['name']);
			}
		}
		
		echo json_encode(un_iconv($jobs_arr));
	}
	/**
	 * 显示评价界面
	 */
	function c_show_tpl()
	{
		$tpl_id = $_GET ['tpl_id'] ? $_GET ['tpl_id'] : $_POST ['tpl_id'];
		$user_id = $_GET['user_id'] ? $_GET['user_id'] : $_POST['user_id'];
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($id)
		{
			$this->tbl_name = 'appraisal_evaluate_list';
			$info = $this->GetOneInfo(array('id'=>$id));
		}
		if ($tpl_id)
		{
			$gl = new includes_class_global();
			$userinfo = $gl->GetUserinfo($user_id,array('user_name','jobs_name'));
			$tpl_obj = new model_administration_appraisal_performance_item ();
			$rs = $tpl_obj->GetOneData ( 'a.id=' . $tpl_id );
			$content = '<html><title>' . $rs ['name'] . '</title><head>';
			$content .= '<meta http-equiv="Content-Type" content="text/html; charset=GBK">';
			$content .= '<script type="text/javascript" src="js/jqeasyui/jquery.min.js"></script>';
			$content .= '<script type="text/javascript" src="js/preformance.js"></script>';
			$content .= '<style type="text/css">';
			$content .= ' input[type=text] { background-color:#e1e1e1; }';
			$content .= 'td {border-collapse:collapse;border: 1px solid #000000;}';
			$content .= 'table {border-collapse:collapse;border: 1px solid #000000;font-size:12px;}';
			$content .= 'textarea {background-color:#e1e1e1;}';
			$content .= '</style></head>';
			$content .= '<form method="post" action="?model=' . $_GET ['model'] . '&action=save_evaluate&id='.$id.'&tpl_id=' . $tpl_id . '&user_id='.$user_id.'" enctype="multipart/form-data" onsubmit="return submit_check(\'evaluate\')">';
			$content .= $rs ['content'];
			$content = str_replace ( '{user_name}', $userinfo ['user_name'], $content );
			$content = str_replace ( '{jobs_name}', $userinfo ['jobs_name'], $content );
			$content = str_replace ( '{cycle}', $rs ['years'] . '年第' . $rs ['quarter'] . '季度', $content );
			$content = str_replace ( '{assess_user_name}', $rs ['assess_user_name'], $content );
			$content = str_replace ( '{audit_user_name}', $rs ['audit_user_name'], $content );
			$content = str_replace ( '{average_assess_fraction}', '&nbsp;', $content );
			$content = str_replace ( '{average_audit_fraction}', '&nbsp;', $content );
			if ($id)
			{
				$content = preg_replace_callback('/<input class="evaluate_fraction" name="evaluate_fraction[[][]]"/is',array($this,'set_Evaluate_fraction'),$content);
				$content = preg_replace_callback('/<textarea class="evaluate_remark"(.+?)evaluate_remark(.+?)>/is',array($this,'set_Evaluate_remark'),$content);
			}
			preg_match_all ( '/<span class="percentage"(.+?)>(.+?)<\/span>/is', $content, $arr );
			$count_percentage = 0;
			if ($arr [2])
			{
				foreach ( $arr [2] as $val )
				{
					$count_percentage = $count_percentage + trim ( str_replace ( '%', '', $val ) );
				}
			}
			$content = str_replace ( '{count_percentage}', $count_percentage . '%', $content );
			$content = str_replace('<input id="count_evaluate_fraction" name="count_evaluate_fraction" type="hidden" />','<input id="count_evaluate_fraction" name="count_evaluate_fraction" type="hidden" value="' . $info['count_fraction'] . '" />',$content);
			$content = str_replace ( '<span id="show_count_evaluate_fraction" style="color: red">0</span>', '<span id="show_count_evaluate_fraction" style="color: red">' . $info['count_fraction'] . '</span>', $content );
			$content = preg_replace ( '/<input class="my_fraction" name="my_fraction(.+?)>/', '&nbsp;', $content ); //关闭考核人评分框
			$content = preg_replace ( '/<textarea(.+?)my_remark(.+?)><\/textarea>/', '&nbsp;', $content ); //关闭考核人评分框
			$content = preg_replace ( '/<input class="assess_fraction" name="assess_fraction(.+?)>/', '&nbsp;', $content ); //关闭考核人评分框
			$content = preg_replace ( '/<input class="audit_fraction" name="audit_fraction(.+?)>/', '&nbsp;', $content ); //关闭审核人评分框
			$content = preg_replace ( '/<textarea(.+?)opinion(.+?)>/', '&nbsp;', $content ); //关闭意见填写框
			$content .= '<div style="text-align:center;"><input type="submit" value=" 确定提交 " /> &nbsp;&nbsp; <input type="button" onclick="window.close();" value=" 关闭返回 " /></div>';
			$content .= '</form></html>';
			echo $content;
		} else
		{
			showmsg ( '非法访问！' );
		}
	}
	/**
	 * 部门
	 */
	function c_dept_data()
	{
		global $func_limit;
		$dept_id = $func_limit['管理部门'] ? $func_limit['管理部门']: $_SESSION['DEPT_ID'];
		$dept = new model_system_dept();
		if ($dept_id)
		{
			$tmp_deptid = array();
			$dept_arr = explode(',',$dept_id);
			foreach ($dept_arr as $val)
			{
				$tmp = $dept->GetParent_ID($val,null,false);
				if ($tmp)
				{
					$tmp_deptid[] = implode(',',$tmp);
				}
			}
			if ($tmp_deptid)
			{
				$tmp_deptid = array_merge($tmp_deptid,$dept_arr);
				$dept_id = implode(',',$tmp_deptid);
			}
		}
		$data = $dept->DeptTree($dept_id);
		$josn[] = array('dept_id'=>'','dept_name'=>'所有部门');
		if ($data)
		{
			foreach ($data as $key=>$rs)
			{
				$josn[] = array('dept_id'=>$rs['DEPT_ID'],'dept_name'=>($rs['level'] ? str_repeat('|--',$rs['level']) : '').$rs['DEPT_NAME']);
			}
		}
		echo json_encode(un_iconv($josn));
	}
	/**
	 * 替换评分
	 * @param $matches
	 */
	function set_Evaluate_fraction($matches)
	{
		$list_id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$list_obj = new model_administration_appraisal_evaluate_index();
		
		static $data = null;
		static $fra = null;
		if ($data == null && $fra == null)
		{
			$data = $list_obj->GetEvaluateInfo($list_id);
		}
		static $i = 0;
		if ($list_id)
		{
			$str = '<input class="evaluate_fraction" name="evaluate_fraction['.$data[$i]['id'].']" value="'.$data[$i]['fraction'].'" ';
		}else{
			$str = $data[$i]['fraction'];
		}
		$i ++;
		return $str;
	}
	/**
	 * 替换评分说明
	 * @param $matches
	 */
	function set_Evaluate_remark($matches)
	{
		$list_id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$list_obj = new model_administration_appraisal_evaluate_index();
		
		static $data = null;
		static $fra = null;
		if ($data == null && $fra == null)
		{
			$data = $list_obj->GetEvaluateInfo($list_id);
		}
		static $i = 0;
		if ($list_id)
		{
			$str = str_replace ( 'evaluate_remark[]', 'evaluate_remark['.$data[$i]['id'].']', $matches [0] ) .$data[$i]['remark'];
		}else{
			$str = $data[$i]['remark'];
		}
		$i ++;
		return $str;
	}
	/**
	 * 保存评介
	 */
	function c_save_evaluate()
	{
		$tpl_id = $_GET ['tpl_id'] ? $_GET ['tpl_id'] : $_POST ['tpl_id'];
		$user_id = $_GET['user_id'] ? $_GET['user_id'] : $_POST['user_id'];
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($_POST)
		{
			if ($this->model_save_evaluate($_POST,$user_id,$tpl_id,$id))
			{
				showmsg(($id ? '修改' : '添加').'成功！','opener.location.reload();window.close()', 'button' );
			}else{
				showmsg(($id ? '修改' : '添加').'失败！','opener.location.reload();window.close()', 'button' );
			}
		}else{
			showmsg('非法访问！');	
		}
	}
	/**
	 * 导出未评价人
	 */
	function c_export_noteevaluate_data()
	{
		global $func_limit;
		$dept_id_str = $func_limit['管理部门'] ? $func_limit['管理部门'] : $_SESSION['DEPT_ID'];
		$condition = " b.dept_id in($dept_id_str)";
		
		$dept_id = $_GET ['dept_id'] ? $_GET ['dept_id'] : $_POST ['dept_id'];
		$years = $_GET['years'] ? $_GET['years'] : $_POST['years'];
		$years = $years ? $years : $this->last_quarter_year;
		$quarter = $_GET['quarter'] ? $_GET['quarter'] : $_POST['quarter'];
		$quarter = $quarter ? $quarter : ($this->this_quarter < 4 ? $this->last_quarter : $this->this_quarter);
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		
		$condition .=$dept_id ? " and b.dept_id=".$dept_id : '';
		$condition .= $keyword ? " and (b.user_name like '%$keyword%')" : '';
		$data = $this->GetNotEevaluate_list($condition,$years,$quarter);
		
		echo count($data);
		
		
	}
	function c_evalList()
	{
		$this->show->display ( 'evalList' );
	}
	function c_evalListData()
	{
		echo $this->model_administration_appraisal_evaluate_index_evalListData();
	}
	function c_evalManager()
	{
		$this->show->display ( 'evalManager' );
	}
	function c_evalManagerData()
	{
		echo $this->model_administration_appraisal_evaluate_index_evalManagerData();
	}
	
	 /* 导出员工数据
	 * Enter description here ...
	 */
	function c_exportExcel ( )
	{
	   $this->model_administration_appraisal_evaluate_index_exportExcel();
    }
}

?>