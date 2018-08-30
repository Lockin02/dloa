<?php

class controller_develop_monthly extends model_develop_monthly {
	public $show;
	
	function __construct() {
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'develop/';
	}
	/**
	 * 月报列表
	 */
	function c_mylist() {
			$this->show->assign ( 'list', $this->model_mylist () );
			$this->show->display ( 'monthly-list' );
	}
	/**
	 * 审核月报列表
	 */
	function c_audit_list() {
		$username = $this->get_table_fields('user',"user_id='".$_GET['userid']."'",'user_name');
		$audit_name = $this->get_table_fields('user',"user_id='".$_GET['audit_userid']."'",'user_name');
		$this->show->assign ( 'select_dept', $this->dept_select($_GET['dept_name']) );
		$this->show->assign('username',$username);
		$this->show->assign('audit_name',$audit_name);
		$this->show->assign ( 'list', $this->model_audit_list () );
		$this->show->display ( 'monthly-audit-list' );
	}
	
	function c_show_info() {
		global $func_limit;
		if ($_GET['id'] && $_GET['key']) 

		{
			$row = $this->get_info ( $_GET['id'], $_GET['key'] );
			if ($row) {
				foreach ( $row as $key => $val ) {
					if ($key=='score_explain')continue;
					if ($key == 'date') {
						$this->show->assign ( 'last_month', date ( 'n', $val ) );
						$val = date ( 'Y-m-d', $val );
					}
					if ($key=='score')
					{
						switch ($val)
						{
							case '良':
								$lang = 'checked';
								break;
							case '中':
								$zhong = 'checked';
								break;
							case '差':
								$cha = 'checked';
							case '优':
								$you = 'checked';
								break;
						}
					}
					
					if ($key == 'status' && $val==-1)
					{
						$this->show->assign('improve_text','<b>不合理，被打回的理由：</b><br />'.$row['notse']);
					}elseif ($key=='status' && $val !=-1 && ($row['audit_userid']==$_SESSION['USER_ID'] || $func_limit['所有权限'])){
						$this->show->assign('improve_text','<b>上级总体评价及改善建议：</b><br /><textarea style="width:99%" cols="40" id="improve" name="improve" rows="5">'.$row['improve'].'</textarea>');
						$this->show->assign('score_explain','<textarea style="width:99%" rows="5" cols="40" id="score_explain" name="score_explain">'.$row['score_explain'].'</textarea>');
					}elseif ($key=='status' && $row['audit_userid']!=$_SESSION['USER_ID']){
						$this->show->assign('improve_text','<b>上级总体评价及改善建议：</b><br />'.$row['improve']);
						$this->show->assign('score_explain',$row['score_explain']);
					}
					$this->show->assign ( $key, $val );
				}
				if ($row['project_id'])
				{
					$rs = $this->model_one_porject($row['project_id']);
				}
				$this->show->assign('you_checked',$you);
				$this->show->assign('zhong_checked',$zhong);
				$this->show->assign('cha_checked',$cha);
				$this->show->assign('lang_checked',$lang);
				$this->show->assign ( 'LAST_MONTH_TASK', $this->model_get_last ( $_GET['id'], 'show' ) );
				$this->show->assign ( 'NEXT_MONTH_TASK', $this->model_get_next ( $_GET['id'],'show' ));
				$this->show->assign ( 'OTHER_TASK', $this->model_get_other ( $_GET['id'],'show' ) );
				$this->show->assign ( 'rowspan', ($this->task_num + 2) );
				$this->show->assign('other_num',$this->last_other_num+2);
				$this->show->assign('project',($rs['name'] ? $rs['name'] : '无'));
				
				$submit_text = ($row['status']==0 ? '通过审核' : '修改审核');
				$submit = ($row['status'] >= 0 && ($row['audit_userid']==$_SESSION['USER_ID'] || $func_limit['所有权限'])? '<input type="submit" value=" '.$submit_text.' "/>' : '');
				$return_link = thickbox_link('不通过，打回','b','id='.$_GET['id'].'&key='.$_GET['key'],'打回'.$row['user_name'].'的'.$row['month'].'工作月报',null,'return',400,220);
				$return_button = ($row['status'] ==0 && $row['audit_userid']==$_SESSION['USER_ID'] ? $return_link : '');
				$this->show->assign('submit',$submit);
				$this->show->assign('return_button',$return_button);
				$this->show->display ( ($row['audit_userid']==$_SESSION['USER_ID'] || $func_limit['所有权限'] ? 'monthly-audit-info' : 'monthly-info') );
			} else {
				showmsg ( '您访问的数据不存在！' );
			}
		} else {
			showmsg ( '非法访问！' );
		}
	
	}
	/**
	 * 提交月报
	 */
	function c_add() {
		if ($_POST) {
			if ($this->model_save_add ( $_POST )) {
				showmsg ( ($_POST['save']==1 ? '保存成功！' : '提交月报成功！'), 'self.parent.location.reload();', 'button' );
			} else {
				showmsg ( '添加月报失败，请与管理员联系！' );
			}
		} else {
			$last_month = date ( 'n', mktime ( 23, 59, 59, date ( "m" ), 0, date ( "Y" ) ) );
			$row = $this->get_one ( "
									select 
										id,status,month,rand_key 
									from 
										develop_monthly 
									where 
										userid='" . $_SESSION['USER_ID'] . "' 
										and date > '" . strtotime ( date ( 'Y' ) . '-01-01' ) . "' 
										and month='" . $last_month . "'
								" );
			if ($row) {
				$_GET['id'] = $row['id'];
				$_GET['key'] = $row['rand_key'];
				if ($row['status']==1)
				{
					showmsg('您的'.$row['month'].'月份工作报告已经提交过，并且已经通过审核！','self.parent.location.reload();', 'button');
				}else{
					showmsg ( '您已经提交过上月工作月报，您可以对上月工作月报进行修改！', '?model=develop_monthly&action=edit&id=' . $row['id'] . '&key=' . $row['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=680' );
				}
			} else {
				$pmsTaskArr = $this->getPmsTaskData($_SESSION['USER_ID'], $last_month);

				$gl = new includes_class_global ();
				$this->show->assign ( 'USER_NAME', $_SESSION['USERNAME'] );
				$this->show->assign ( 'select_dept', $this->dept_select() );
				$this->show->assign ( 'DATE', date ( 'Y-m-d' ) );
				$this->show->assign ( 'LAST_MONTH', $last_month );
				$this->show->assign ( 'MONTH', date ( 'n', time () ) );
				$this->show->assign ( 'LAST_MONTH_TASK', $this->model_Last_month_task ( $_SESSION['USER_ID'] ) );
				$this->show->assign ( 'NEXT_MONTH_TASK', $this->model_next_month_task ( $_SESSION['USER_ID'] ) );
				$this->show->assign ( 'LAST_MONTH_OTHER_TASK', $this->model_last_month_other_task( $_SESSION['USER_ID'] ) );

				$this->show->assign ( 'PMS_LAST_MONTH_TASK_LIST',$pmsTaskArr['last']);
				$this->show->assign ( 'PMS_NEXT_MONTH_TASK_LIST',$pmsTaskArr['next']);

				$this->show->assign ( 'rowspan', ($this->task_num + 3) );
				$this->show->assign('other_rowspan',($this->last_other_num+3));
				$this->show->assign ( 'new_task_num', ($this->task_num + 1) );
				$this->show->assign ( 'next_task_num', ($this->next_task_num + 1) );
				$this->show->assign ( 'level', $this->model_level_select ( 'level[]' ) );
				$this->show->assign ( 'next_level', $this->model_level_select ( 'next_level[]' ) );
				$this->show->assign ( 'score_select', $this->model_score_select ( 'score[]' ) );
				$this->show->assign ( 'other_score_select', $this->model_score_select ( 'other_score[]' ) );
				$this->show->assign('porject_option',$this->project_option($_SESSION['DEPT_ID']));
				$this->show->display ( 'monthly-add' );
			}
		}
	}
	/**
	 * 修改月报
	 */
	function c_edit() {
		if ($_POST) {
			if ($this->model_save_edit ( $_GET['id'], $_GET['key'], $_POST )) {
				showmsg ( ($_POST['save']==1 ? '保存成功！' :'修改成功！'), 'self.parent.location.reload();', 'button' );
			} else {
				showmsg ( '修改失败，请与管理员联系！' );
			}
		} else {
			$data = $this->model_getinfo ( $_GET['id'], $_GET['key'] );
			if ($data) {
				foreach ( $data as $key => $val ) {
					$this->show->assign ( $key, $val );
				}
			}
			$this->show->assign ( 'select_dept', $this->dept_select($data['dept_name']) );
			$this->show->assign ( 'level', $this->model_level_select ( 'level[]' ) );
			$this->show->assign ( 'next_level', $this->model_level_select ( 'next_level[]' ) );
			$this->show->assign ( 'last_score_select', $this->model_score_select ( 'score[]',null,'last_score' ) );
			$this->show->assign ( 'other_score_select', $this->model_score_select ( 'other_score[]',null,'other_score' ) );
			$this->show->assign ( 'DATE', date ( 'Y-m-d', $data['date'] ) );
			$this->show->assign ( 'LAST_MONTH_TASK', $this->model_get_last ( $_GET['id'] ) );
			$this->show->assign ( 'NEXT_MONTH_TASK', $this->model_get_next ( $_GET['id'] ) );
			$this->show->assign ( 'OTHER_TASK', $this->model_get_other ( $_GET['id'] ) );
			$this->show->assign ( 'rowspan', ($this->task_num + 3) );
			$this->show->assign ( 'other_rowspan', ($this->last_other_num + 3) );
			$this->show->assign ( 'next_task_num', ($this->next_task_num + 1) );
			$this->show->assign ( 'new_task_num', ($this->task_num + 1) );
			$this->show->assign ( 'other_task_num', ($this->last_other_num + 1) );
			$this->show->assign ( 'NEXT_MONTH', (($data['month'] + 1) > 12 ? 1 : ($data['month'] + 1)) );
			$this->show->assign('porject_option',$this->project_option($_SESSION['DEPT_ID'],$data['project_id']));
			$this->show->display ( 'monthly-edit' );
		}
	}
	/**
	 * 审核月报
	 */
	function c_audit() {
		if ($_POST)
		{
			if ($this->model_save_audit($_GET['id'],$_GET['key'],$_POST))
			{
				showmsg('操作成功！','self.parent.location.reload();', 'button');
			}else{
				showmsg('操作失败，请与管理员联系！');
			}
		}else{
			$this->c_show_inof();
		}
	}
	/**
	 * 打回
	 */
	function c_return()
	{
		if ($_POST)
		{
			if ($this->model_return($_GET['id'],$_GET['key'],$_POST['notse']))
			{
				showmsg('操作成功！','self.parent.location.reload();', 'button');
			}else{
				showmsg('操作失败，请与管理员联系！');
			}
		}else{
			$this->show->display('monthly-audit-return');
		}
	}
	
	function c_show_return_msg()
	{
		if (intval($_GET['id']) && $_GET['key'])
		{
			$rs = $this->get_info($_GET['id'],$_GET['key']);
			if ($rs)
			{
				$this->show->assign('msg',$rs['notse']);
				$this->show->display('monthly-return-msg');
			}else{
				showmsg('非法访问！');
			}
		}else{
			showmsg('非法访问参数！');
		}
	}
	/**
	 * 部门下拉
	 * @param $DEPT_NAME
	 */
	function dept_select($DEPT_NAME=null)
	{
		$dept = new model_system_dept();
		$dept_id = $dept->GetSon_ID(32);
		$dept_data = $dept->DeptTree(array_merge($dept_id,array('32')));
		$dept_data = $dept->rest($dept_data);
		$dept_list = '';
		if ($dept_data)
		{
			foreach ($dept_data as $row)
			{
				if ($row)
				{
					foreach ($row as $key=>$rs)
					{
						$dept_list .='<option '.($rs['DEPT_NAME']==$DEPT_NAME ? 'selected' : '').' value="'.$rs['DEPT_NAME'].'">'.(str_repeat('&nbsp;',($rs['level']*2))).$rs['DEPT_NAME'].'</option>';
					}
				}
				
			}
		}
		return $dept_list;
	}
	/**
	 * 获取审批人
	 */
	function c_get_audit()
	{
		$project_id = isset($_GET['project_id']) ? $_GET['project_id'] : $_POST['project_id'];
		if ($project_id)
		{
			$str = $this->model_project_audit_user($project_id);
			echo un_iconv($str);
		}else{
			echo -1;
		}
	}
}

?>