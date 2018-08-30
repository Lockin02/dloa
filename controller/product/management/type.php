<?php
class controller_product_management_type extends model_product_management_type
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'product/management/';
	}
	
	function c_index()
	{
		$this->c_list();
	}
	
	function c_list()
	{
		$dept = new model_system_dept();
		$dept_data = $dept->GetDataList();
		$dept_arr = array();
		if ($dept_data)
		{
			foreach ($dept_data as $row)
			{
				$dept_arr[$row['DEPT_ID']]=un_iconv($row['DEPT_NAME']);
			}
		}
		$group = new model_system_usergroup_list();
		$group_id = $group->GetId('product_type_manager'); // 获取产品专利管理员
		$manage = '';
		if ($group_id && $group->CheckUser($group_id, $_SESSION['USER_ID']))
		{
			$manage = 'true';
		}
		$this->show->assign('manage', $manage);
		$this->show->assign('dept_data', json_encode($dept_arr));
		$this->show->assign('dept_json', $this->c_get_dept_data(true));
		$this->show->display('type');
	}
	/**
	 * 列表JSON数据
	 */
	function c_list_data()
	{
		$data = $this->GetDataList(null,$_POST['page'],$_POST['rows']);
		$json = array('total'=>$this->num);
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
	 * 添加
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
		}else{
			echo -2;
		}
	}
	/**
	 * 修改
	 */
	function c_edit()
	{
		if ($_POST)
		{
			$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
			if ($id)
			{
				if ($this->Edit(mb_iconv($_POST),$id))
				{
					echo 1;
				}else{
					echo -1;
				}
			}else{
				ecjo -1;
			}
		}else{
			echo -1;
		}
	}
	/**
	 * 删除
	 */
	function c_del()
	{
		$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
		if ($id)
		{
			if ($this->Del($id))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			echo -1;
		}
	}
	
	/**
	 * 部门
	 */
	function c_get_dept_data($get = false)
	{
		$dept = new model_system_dept();
		$data = $dept->DeptList();
		$trre_data = array();
		if ($data)
		{
			$temp = array();
			foreach ( $data as $key => $rs )
			{
				$temp[$rs['PARENT_ID']][$rs['DEPT_ID']] = $rs;
			}
			$trre_data = $this->trre_dept($temp);
		}
		if ($get)
		{
			return json_encode($trre_data);
		}else{
			echo json_encode($trre_data);
		}
	}
	/**
	 * 部门树型
	 * 
	 * @param unknown_type $data
	 * @param unknown_type $parent_id
	 */
	function trre_dept($data,$parent_id=0)
	{
		$tmp = array();
		if ($data)
		{
			if ($data[$parent_id])
			{
				foreach ($data[$parent_id] as $key=>$rs)
				{
					$tmp[]=array(
									'id'=>$rs['DEPT_ID'],
									'text'=>un_iconv($rs['DEPT_NAME']),
									'children'=>$this->trre_dept($data,$rs['DEPT_ID'])
								);
				}
			}
		}
		
		return $tmp;
	}
	
}
?>