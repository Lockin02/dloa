<?php
class controller_rd_hrms_files extends model_rd_hrms_files
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'rd/hrms/files/';
	}
	/**
	 * 栏目首页
	 */
	function c_index()
	{
		$this->show->display('list');
	}
	/**
	 * 列表
	 */
	function c_list()
	{
		global $func_limit;
		$josn= array();
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$jobs_id = $_GET['jobs_id'] ? $_GET['jobs_id'] : $_POST['jobs_id'];
		$field_id = $_GET['field_id'] ? $_GET['field_id'] : $_POST['field_id'];
		if ($dept_id && in_array($dept_id,explode(',',$func_limit['管理部门'])))
		{
			$condition = 'a.dept_id='.$dept_id;
		}else{
			$condition = $func_limit['管理部门'] ? "a.dept_id in(".$func_limit['管理部门'].")" : "a.dept_id=".$_SESSION['DEPT_ID'];
		}
		if ($jobs_id)
		{
			$condition .=" and a.jobs_id='$jobs_id' ";
		}
		
		if ($keyword)
		{
			if ($keyword && $field_id && is_numeric($field_id) )
			{
				$field_id_arr = $this->Get_Field_Name_Id($field_id);
				$user_arr = $this->Search_Field_Conten($keyword,$field_id_arr);
				if ($user_arr)
				{
					$condition.= " and a.user_id in (".implode(',',$user_arr).")";
				}else{
					$josn = array('total'=>0);
					$josn['rows'] = array();
					echo json_encode($josn);
					exit();
				}
			}else if ($keyword && $field_id){
				if ($field_id == 'user_name')
				{
					$condition .=" and a.user_name like '%$keyword%'";
				}else{
					$condition .=" and b.".$field_id." like '%$keyword%'";
				}
				
			}
		}
		$data = $this->GetDataList($condition,$_POST['page'],$_POST['rows']);
		if ($data)
		{
			$josn = array('total'=>$this->num);
			$josn['rows'] = un_iconv($data);
			$josn['field_arr'] = un_iconv($this->list_all_field_arr);
		}
		echo json_encode($josn);
	}
	function c_list2()
	{
		file_put_contents('jff.txt',json_encode($_GET));
	}
	function c_dept_data()
	{
		global $func_limit;
		$dept_id = $func_limit['管理部门'] ? $func_limit['管理部门']: $_SESSION['DEPT_ID'];
		$dept = new model_system_dept();
		$data = $dept->DeptList($dept_id);
		$josn[] = array('dept_id'=>'','dept_name'=>'所有部门');
		if ($data)
		{
			foreach ($data as $key=>$rs)
			{
				$josn[] = array('dept_id'=>$rs['DEPT_ID'],'dept_name'=>$rs['DEPT_NAME']);
			}
		}
		echo json_encode(un_iconv($josn));
	}
	/**
	 * 添加
	 */
	function c_add()
	{
		if ($_POST)
		{
			
		}else{
			$this->show->display('add');
		}
	}
	/**
	 * 修改
	 */
	function c_edit()
	{
		file_put_contents('jj.txt',json_encode($_POST));
		if ($_POST)
		{
			if ($this->Edit(mb_iconv($_POST),$_GET['user_id']))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			
		}
	}
	/**
	 * 删除
	 */
	function c_del()
	{
		
	}
}

?>