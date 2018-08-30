<?php
class controller_rd_hrms_field extends model_rd_hrms_field
{
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show  = new show();
		$this->show->path = 'rd/hrms/field/';
	}
	/**
	 * �Զ����ֶ��б�
	 */
	function c_index()
	{
		global $func_limit;
		//����
		$dept_id = $func_limit['������'] ? $func_limit['������'] : $_SESSION['DEPT_ID'];
		$obj = new model_system_dept();
		$dept = $obj->DeptList($dept_id);
		$dept_arr = array();
		if ($dept)
		{
			foreach ($dept as $rs)
			{
				$dept_arr[] = array('DEPT_ID'=>$rs['DEPT_ID'],'DEPT_NAME'=>$rs['DEPT_NAME']);
			}
		}
		//ְλ
		$jobs_obj = new model_system_jobs();
		$jobs = $jobs_obj->JobsList($dept_id);
		$jobs_arr = array();
		if ($jobs)
		{
			foreach ($jobs as $rs)
			{
				$jobs_arr[] = array('jobs_id'=>$rs['id'],'jobs_name'=>$rs['name']);
			}
		}
		$this->show->assign('dept',json_encode(un_iconv($dept_arr)));
		$this->show->assign('jobs',json_encode(un_iconv($jobs_arr)));
		$this->show->display('field-list');
	}
	/**
	 * ��ȡ�ֶ�
	 */
	function c_get_field()
	{
		global $func_limit;
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
		if (!$dept_id)
		{
			$dept_id = $func_limit['������'] ? $func_limit['������'] : $_SESSION['DEPT_ID'];
		}
		$jobs_id = $_GET['jobs_id'] ? $_GET['jobs_id'] : $_POST['jobs_id'];
		$data = $this->GetDataList("dept_id in($dept_id)".($jobs_id ? " and jobs_id=$jobs_id" : ''));
		$field_arr = array(
		array('field_id'=>'user_name','field_name'=>'����','selected'=>true),
		array('field_id'=>'skill','field_name'=>'���˼���'),
		array('field_id'=>'honor','field_name'=>'����֤��'),
		array('field_id'=>'interest','field_name'=>'������Ȥ'),
		);
		if ($data)
		{
			foreach ($data as $rs)
			{
				$field_arr[] = array('field_id'=>$rs['id'],'field_name'=>$rs['field_name']);
			}
		}
		
		echo json_encode(un_iconv($field_arr));
		
	}
	/**
	 * ��������ְλ 
	 */
	function c_get_jobs()
	{
		$dept_id = isset($_GET['dept_id']) ? $_GET['dept_id'] : $_POST['dept_id'];
		$jobs_obj = new model_system_jobs();
		$jobs = $jobs_obj->JobsList($dept_id);
		$jobs_arr = array();
		if ($_GET['type']=='list')
		{
			$jobs_arr[] = array('jobs_id'=>'','jobs_name'=>'����ְλ','selected'=>true);
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
	 * �б�����
	 */
	function c_list_data()
	{
		global $func_limit;
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
		$jobs_id = $_GET['jobs_id'] ? $_GET['jobs_id'] : $_POST['jobs_id'];
		if (!$dept_id)
		{
			$dept_id = $func_limit['������'] ? $func_limit['������'] : $_SESSION['DEPT_ID'];
		}
		$condition = " dept_id in($dept_id)";
		$condition.= $jobs_id ? " and jobs_id=$jobs_id" : "";
		//var_dump($dept_id);
		$data = $this->GetDataList($condition,$_POST['page'],$_POST['rows']);
		$josn = array('total'=>$this->num);
		$josn['rows'] = $data ? un_iconv($data) : array();

		echo json_encode($josn);
		
	}
	/**
	 * ���
	 */
	function c_add()
	{
		if ($_POST)
		{
			if ($this->Add(mb_iconv($_POST)))
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	/**
	 * �޸�
	 */
	function c_edit()
	{
		if ($_POST)
		{
			if ($this->Edit(mb_iconv($_POST),$_POST['id']))
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	/**
	 * ɾ��
	 */
	function c_del()
	{
		if ($_POST)
		{
			if ($this->Del($_POST['id']))
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
}
?>