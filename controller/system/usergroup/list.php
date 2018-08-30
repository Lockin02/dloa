<?php
class controller_system_usergroup_list extends model_system_usergroup_list
{
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'system/usergroup/';
	}
	/**
	 * Ĭ�Ϸ���
	 */
	function c_index()
	{
		$this->c_list();
	}
	/**
	 * �б����
	 */
	function c_list()
	{
		global $func_limit;
		if (!$func_limit['������'])
		{
			showmsg('�����޹����κβ����û���Ȩ�ޣ�');
		}
		$dept_obj = new model_system_dept();
		$dept_option = $dept_obj->options();
		$this->show->assign('dept_option',$dept_option);
		$this->show->display('list');
	}
	/**
	 * �б�����
	 */
	function c_list_data()
	{
		$data = array();
		$condition = null;
		global $func_limit;
		if ($func_limit['������'])
		{
			$condition = "a.dept_id in(".$func_limit['������'].")";
			$data = $this->GetDataList($condition,$_POST['page'],$_POST['rows']);
			$json = array('total'=>$this->num);
		}else{
			$json = array('total'=>0);
		}
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
	 * ���
	 */
	function c_add()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
			$_POST['user_id'] = $_SESSION['USER_ID'];
			$_POST['date'] = date('Y-m-d H:i:s');
			if ($this->Add($_POST))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			echo -2;
		}
	}
	/**
	 * �޸�
	 */
	function c_edit()
	{
		if ($_POST)
		{
			$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
			if ($id)
			{
				$_POST = mb_iconv($_POST);
				if ($this->Edit($_POST,$id))
				{
					echo 1;
				}else{
					echo -1;
				}
			}else{
				echo -2;
			}
		}else{
			echo -3;
		}
		
	}
	/**
	 * ɾ��
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
			echo -2;
		}
	}
	/**
	 * ���Ψһ��ʶ
	 */
	function c_check_ide()
	{
		$identification = $_GET['identification'] ? $_GET['identification'] : $_POST['identification'];
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$condition = " identification='$identification'";
		$condition .= $id ? " id!=$id" : '';
		$info = $this->GetOneInfo($condition);
		if ($info)
		{
			echo 1;
		}else{
			echo -1;
		}
	}
	
	/**
	 * �û�
	 */
	function c_get_username_list()
	{
		$user_id_str = $_GET['user_id_str'] ? $_GET['user_id_str'] : $_POST['user_id_str'];
		if ($user_id_str)
		{
			$gl = new includes_class_global();
			$data = $gl->GetUserName(explode(',',trim($user_id_str,',')));
			$username_str = implode('��',$data);
		}
		echo un_iconv($username_str);
	}
	/**
	 * ����
	 */
	function c_get_deptname_list()
	{
		$dept_id_str = $_GET['dept_id_str'] ? $_GET['dept_id_str'] : $_POST['dept_id_str'];
		if ($dept_id_str)
		{
			$gl = new includes_class_global();
			$data = $gl->GetDept(explode(',',trim($dept_id_str,',')),true);
			$deptname_str = implode('��',$data);
		}
		echo un_iconv($deptname_str);
	}
	/**
	 * ����
	 */
	function c_get_areaname_list()
	{
		$area_id_str = $_GET['area_id_str'] ? $_GET['area_id_str'] : $_POST['area_id_str'];
		if ($area_id_str)
		{
			$gl = new includes_class_global();
			$data = $gl->get_area(explode(',',trim($area_id_str,',')),true);
			$areaname_str = implode('��',$data);
		}
		echo un_iconv($areaname_str);
	}
	/**
	 * ְλ
	 */
	function c_get_jobsname_list()
	{
		$jobs_id_str = $_GET['jobs_id_str'] ? $_GET['jobs_id_str'] : $_POST['jobs_id_str'];
		if ($jobs_id_str)
		{
			$gl = new includes_class_global();
			$data = $gl->GetJobs(explode(',',trim($jobs_id_str,',')),true);
			$jobsname_str = implode('��',$data);
		}
		echo un_iconv($jobsname_str);
	}
}

?>