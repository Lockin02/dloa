<?php

class controller_develop_monthly extends model_develop_monthly {
	public $show;
	
	function __construct() {
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'develop/';
	}
	/**
	 * �±��б�
	 */
	function c_mylist() {
			$this->show->assign ( 'list', $this->model_mylist () );
			$this->show->display ( 'monthly-list' );
	}
	/**
	 * ����±��б�
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
							case '��':
								$lang = 'checked';
								break;
							case '��':
								$zhong = 'checked';
								break;
							case '��':
								$cha = 'checked';
							case '��':
								$you = 'checked';
								break;
						}
					}
					
					if ($key == 'status' && $val==-1)
					{
						$this->show->assign('improve_text','<b>����������ص����ɣ�</b><br />'.$row['notse']);
					}elseif ($key=='status' && $val !=-1 && ($row['audit_userid']==$_SESSION['USER_ID'] || $func_limit['����Ȩ��'])){
						$this->show->assign('improve_text','<b>�ϼ��������ۼ����ƽ��飺</b><br /><textarea style="width:99%" cols="40" id="improve" name="improve" rows="5">'.$row['improve'].'</textarea>');
						$this->show->assign('score_explain','<textarea style="width:99%" rows="5" cols="40" id="score_explain" name="score_explain">'.$row['score_explain'].'</textarea>');
					}elseif ($key=='status' && $row['audit_userid']!=$_SESSION['USER_ID']){
						$this->show->assign('improve_text','<b>�ϼ��������ۼ����ƽ��飺</b><br />'.$row['improve']);
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
				$this->show->assign('project',($rs['name'] ? $rs['name'] : '��'));
				
				$submit_text = ($row['status']==0 ? 'ͨ�����' : '�޸����');
				$submit = ($row['status'] >= 0 && ($row['audit_userid']==$_SESSION['USER_ID'] || $func_limit['����Ȩ��'])? '<input type="submit" value=" '.$submit_text.' "/>' : '');
				$return_link = thickbox_link('��ͨ�������','b','id='.$_GET['id'].'&key='.$_GET['key'],'���'.$row['user_name'].'��'.$row['month'].'�����±�',null,'return',400,220);
				$return_button = ($row['status'] ==0 && $row['audit_userid']==$_SESSION['USER_ID'] ? $return_link : '');
				$this->show->assign('submit',$submit);
				$this->show->assign('return_button',$return_button);
				$this->show->display ( ($row['audit_userid']==$_SESSION['USER_ID'] || $func_limit['����Ȩ��'] ? 'monthly-audit-info' : 'monthly-info') );
			} else {
				showmsg ( '�����ʵ����ݲ����ڣ�' );
			}
		} else {
			showmsg ( '�Ƿ����ʣ�' );
		}
	
	}
	/**
	 * �ύ�±�
	 */
	function c_add() {
		if ($_POST) {
			if ($this->model_save_add ( $_POST )) {
				showmsg ( ($_POST['save']==1 ? '����ɹ���' : '�ύ�±��ɹ���'), 'self.parent.location.reload();', 'button' );
			} else {
				showmsg ( '����±�ʧ�ܣ��������Ա��ϵ��' );
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
					showmsg('����'.$row['month'].'�·ݹ��������Ѿ��ύ���������Ѿ�ͨ����ˣ�','self.parent.location.reload();', 'button');
				}else{
					showmsg ( '���Ѿ��ύ�����¹����±��������Զ����¹����±������޸ģ�', '?model=develop_monthly&action=edit&id=' . $row['id'] . '&key=' . $row['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=680' );
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
	 * �޸��±�
	 */
	function c_edit() {
		if ($_POST) {
			if ($this->model_save_edit ( $_GET['id'], $_GET['key'], $_POST )) {
				showmsg ( ($_POST['save']==1 ? '����ɹ���' :'�޸ĳɹ���'), 'self.parent.location.reload();', 'button' );
			} else {
				showmsg ( '�޸�ʧ�ܣ��������Ա��ϵ��' );
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
	 * ����±�
	 */
	function c_audit() {
		if ($_POST)
		{
			if ($this->model_save_audit($_GET['id'],$_GET['key'],$_POST))
			{
				showmsg('�����ɹ���','self.parent.location.reload();', 'button');
			}else{
				showmsg('����ʧ�ܣ��������Ա��ϵ��');
			}
		}else{
			$this->c_show_inof();
		}
	}
	/**
	 * ���
	 */
	function c_return()
	{
		if ($_POST)
		{
			if ($this->model_return($_GET['id'],$_GET['key'],$_POST['notse']))
			{
				showmsg('�����ɹ���','self.parent.location.reload();', 'button');
			}else{
				showmsg('����ʧ�ܣ��������Ա��ϵ��');
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
				showmsg('�Ƿ����ʣ�');
			}
		}else{
			showmsg('�Ƿ����ʲ�����');
		}
	}
	/**
	 * ��������
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
	 * ��ȡ������
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