<?php
class controller_project_index extends model_project_index
{
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'project/';
	}
	
	function c_index()
	{
		$this->c_list();	
	}
	
	function c_list()
	{
		$this->show->assign('list',$this->model_list());
		$this->show->display('list');
	}
	function c_upflag(){
		
		$this->model_upflag();
	}
	function c_add()
	{
		global $func_limit;
		if ($_POST)
		{
			if ($this->model_add($_POST))
			{
				showmsg('操作成功！', 'self.parent.location.reload();', 'button');
			}else{
				showmsg('操作失败，请与管理员联系！');
			}
		}else{
			$options = '';
			$dept_name = '';
			$gl = new includes_class_global();
			$data = $gl->GetDept($func_limit['管理部门']);
			if ($data)
			{
				foreach ($data as $row)
				{
					if ($func_limit['管理部门'])
					{
						$options.='<option value="'.$row['DEPT_ID'].'" >'.$row['DEPT_NAME'].'</option>';
					}elseif ($row['DEPT_ID']==$_SESSION['DEPT_ID']){
						$dept_name = $row['DEPT_NAME'];	
					}
				}
			}
			$options = $options ? $options : '<option value="'.$_SESSION['DEPT_ID'].'">'.$dept_name.'</option>';
			$this->show->assign('select_dept',$options);
			
			$select_ipo = '';
			$ipo = new model_project_ipo();
			$ipo_data = $ipo->GetDataList();
			if ($ipo_data)
			{
				foreach ($ipo_data as $row)
				{
					$select_ipo .='<option '.($row['id']==$info['ipo_id'] ? 'selected' : '').' value="'.$row['id'].'">'.$row['project_name'].'</option>';
				}
			}
			
			$select_product = '';
			$product = new model_product_demand();
			$product_data = $product->get_typelist();
			if ($product_data)
			{
				foreach ($product_data as $row)
				{
					$select_product .='<option value="'.$row['id'].'">'.$row['product_name'].'</option>';
				}
			}
			$this->show->assign('select_product', $select_product);
			$this->show->assign('select_ipo', $select_ipo);
				
			$this->show->display('add');
		}
	}
	
	function c_edit()
	{
		global $func_limit;
		if ($_POST)
		{
			if ($this->model_edit($_GET['id'],$_GET['key'],$_POST))
			{
				showmsg('操作成功！', 'self.parent.location.reload();', 'button');
			}else{
				showmsg('操作失败，请与管理员联系！');
			}
			
		}else{
			if ($_GET['id'] && $_GET['key'])
			{
				$info = $this->find(array('id'=>$_GET['id'],'rand_key'=>$_GET['key']));
				$user = $this->get_username();
				$options = '';
				$dept_name = '';
				$count = 0;
				$gl = new includes_class_global();
				$data = $gl->GetDept($func_limit['管理部门']);
				if ($data)
				{
					foreach ($data as $row)
					{
						if ($func_limit['管理部门'])
						{
							$options.='<option '.($row['DEPT_ID']==$info['dept_id'] ? 'selected' : '').' value="'.$row['DEPT_ID'].'" >'.$row['DEPT_NAME'].'</option>';
						}elseif ($row['DEPT_ID']==$_SESSION['DEPT_ID']){
							$dept_name = $row['DEPT_NAME'];	
						}
					}
				}
				$options = $options ? $options : '<option value="'.$_SESSION['DEPT_ID'].'">'.$dept_name.'</option>';
				$producmanager_str = '';
				$producmanager_input = '';
				if ($info['producmanager'])
				{
					$arr = $this->get_username_list($user,$info['producmanager']);
					foreach ($arr as $key=>$val)
					{
						$producmanager_str .='<li id="user_'.$count.'">'.$val.'<a href="javascript:del('.$count.');">删除</a></li>';
						$producmanager_input .='<input type="hidden" id="hi_'.$count.'" name="producmanager[]" value="'.$key.'" />';
						$count++;
					}
				}
				$productassistant_str = '';
				$productassistant_input = '';
				if ($info['productassistant'])
				{
					$arr = $this->get_username_list($user,$info['productassistant']);
					foreach ($arr as $key=>$val)
					{
						$productassistant_str .='<li id="user_'.$count.'">'.$val.'<a href="javascript:del('.$count.');">删除</a></li>';
						$productassistant_input .='<input type="hidden" id="hi_'.$count.'" name="productassistant[]" value="'.$key.'" />';
						$count++;
					}
				}
				$manager_str = '';
				$manager_input = '';
				if ($info['manager'])
				{
					$arr = $this->get_username_list($user,$info['manager']);
					foreach ($arr as $key=>$val)
					{
						$manager_str .='<li id="user_'.$count.'">'.$val.'<a href="javascript:del('.$count.');">删除</a></li>';
						$manager_input .='<input type="hidden" id="hi_'.$count.'" name="manager[]" value="'.$key.'" />';
						$count++;
					}
				}
				
				$teamleader_str = '';
				$teamleader_input = '';
				if ($info['teamleader'])
				{
					$arr = $this->get_username_list($user,$info['teamleader']);
					foreach ($arr as $key=>$val)
					{
						$teamleader_str .='<li id="user_'.$count.'">'.$val.'<a href="javascript:del('.$count.');">删除</a></li>';
						$teamleader_input .='<input type="hidden" id="hi_'.$count.'" name="teamleader[]" value="'.$key.'" />';
						$count++;
					}
				}
				$developer_str = '';
				$developer_input = '';
				if ($info['developer'])
				{
					$arr = $this->get_username_list($user,$info['developer']);
					foreach ($arr as $key=>$val)
					{
						$developer_str .='<li id="user_'.$count.'">'.$val.'<a href="javascript:del('.$count.');">删除</a></li>';
						$developer_input .='<input type="hidden" id="hi_'.$count.'" name="developer[]" value="'.$key.'" />';
						$count++;
					}
				}
				$testleader_str = '';
				$testleader_input = '';
				if ($info['testleader'])
				{
					$arr = $this->get_username_list($user,$info['testleader']);
					foreach ($arr as $key=>$val)
					{
						$testleader_str .='<li id="user_'.$count.'">'.$val.'<a href="javascript:del('.$count.');">删除</a></li>';
						$testleader_input .='<input type="hidden" id="hi_'.$count.'" name="testleader[]" value="'.$key.'" />';
						$count++;
					}
				}
				
				$tester_str = '';
				$tester_input = '';
				if ($info['tester'])
				{
					$arr = $this->get_username_list($user,$info['tester']);
					foreach ($arr as $key=>$val)
					{
						$tester_str .='<li id="user_'.$count.'">'.$val.'<a href="javascript:del('.$count.');">删除</a></li>';
						$tester_input .='<input type="hidden" id="hi_'.$count.'" name="tester[]" value="'.$key.'" />';
						$count++;
					}
				}
				$qc_str = '';
				$qc_input = '';
				if ($info['qc'])
				{
					$arr = $this->get_username_list($user,$info['qc']);
					foreach ($arr as $key=>$val)
					{
						$qc_str .='<li id="user_'.$count.'">'.$val.'<a href="javascript:del('.$count.');">删除</a></li>';
						$qc_input .='<input type="hidden" id="hi_'.$count.'" name="qc[]" value="'.$key.'" />';
						$count++;
					}
				}
				$select_ipo = '';
				$ipo = new model_project_ipo();
				$ipo_data = $ipo->GetDataList();
				if ($ipo_data)
				{
					foreach ($ipo_data as $row)
					{
						$select_ipo .='<option '.($row['id']==$info['ipo_id'] ? 'selected' : '').' value="'.$row['id'].'">'.$row['project_name'].'</option>';
					}
				}
				$select_product = '';
				$product = new model_product_demand();
				$product_data = $product->get_typelist();
				$product_id_arr = $info['product_id_str'] ? explode(',', $info['product_id_str']) : array();
				if ($product_data)
				{
					foreach ($product_data as $row)
					{
						$select_product .='<option '.(in_array($row['id'], $product_id_arr) ? 'selected' : '').' value="'.$row['id'].'">'.$row['product_name'].'</option>';
					}
				}
				$this->show->assign('select_product', $select_product);
				$this->show->assign('select_ipo', $select_ipo);
				$this->show->assign('name',$info['name']);
				$this->show->assign('number',$info['number']);
				$this->show->assign('description',$info['description']);
				
				$this->show->assign('producmanager_str',$producmanager_str);
				$this->show->assign('producmanager_input',$producmanager_input);
				
				$this->show->assign('productassistant_str',$productassistant_str);
				$this->show->assign('productassistant_input',$productassistant_input);
				
				$this->show->assign('manager_str',$manager_str);
				$this->show->assign('manager_input',$manager_input);
				
				$this->show->assign('teamleader_str',$teamleader_str);
				$this->show->assign('teamleader_input',$teamleader_input);
				
				$this->show->assign('developer_str',$developer_str);
				$this->show->assign('developer_input',$developer_input);
				
				$this->show->assign('testleader_str',$testleader_str);
				$this->show->assign('testleader_input',$testleader_input);
				
				$this->show->assign('tester_str',$tester_str);
				$this->show->assign('tester_input',$tester_input);
				
				$this->show->assign('qc_str',$qc_str);
				$this->show->assign('qc_input',$qc_input);
				
				$this->show->assign('tid',$count);
				$this->show->assign('select_dept',$options);
				$this->show->display('edit');
			}else{
				showmsg('非法参数访问！');
			}
		}
	}
	
	function c_del()
	{
		if ($_GET['id'] && $_GET['key'])
		{
			if ($this->model_del($_GET['id'] , $_GET['key']))
			{
				showmsg('操作成！', 'self.parent.location.reload();', 'button');
			}else{
				showmsg('操作失败，请与管理员联系！');
			}
		}else{
			showmsg('非法参数！');
		}
	}
}x

?>