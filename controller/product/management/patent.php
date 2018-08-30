<?php
class controller_product_management_patent extends model_product_management_patent
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'product/management/';
	}
	/**
	 * 默认访问
	 */
	function c_index()
	{
		$this->c_list();
	}
	/**
	 * 列表页
	 */
	function c_list()
	{
		$product_option_str = '';
		$product = new model_product_demand();
		$product_data = $product->get_typelist();
		if ($product_data)
		{
			foreach ($product_data as $rs)
			{
				$product_option_str .= '<option value="'.$rs['id'].'">'.$rs['product_name'].'</option>';
			}
		}
		$group = new model_system_usergroup_list();
		$group_id = $group->GetId('product_patent_manager'); // 获取产品专利管理员
		$add_auth = 0;
		if ($group_id && $group->CheckUser($group_id, $_SESSION['USER_ID']))
		{
			$add_auth = 1;
		}
		
		$ipo_option='';
		$ipo = new model_project_ipo();
		$ipo_data = $ipo->GetDataList();
		if ($ipo_data)
		{
			foreach ($ipo_data as $rs)
			{
				$ipo_option .='<option value="'.$rs['project_name'].'">'.$rs['project_name'].'</option>';
			}
		}
		$this->show->assign('add_auth', $add_auth);
		$this->show->assign('product_option', $product_option_str);
		$this->show->assign('ipo_option', $ipo_option);
		$this->show->display('patent');
	}
	/**
	 * 列表数据
	 */
	function c_list_data()
	{
		file_put_contents('mmmk.txt', json_encode($_POST));
		$sort = $_POST['sort'] ? 'a.'.$_POST['sort'].' '.$_POST['order'] : '';
		$data = $this->GetDataList(NULL,$_POST['page'],$_POST['rows'],true,$sort);
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
	 * 历史记录
	 */
	function c_history_list()
	{
		$identification = $_GET['identification'] ? $_GET['identification'] : $_POST['identification'];
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$data = $this->GetDataList("a.id<$id and a.identification='$identification'");
		$str = '';
		if ($data)
		{
			krsort($data);
			$temp = array();
			$bgcolor = 'background:#FBEC88;';
			$html_str_arr = array();
			foreach ($data as $key=>$rs)
			{
				$str = '';
				$str .= '<div class="list_info">';
				$str .='<li style="width:80px;'.($temp && $temp['typename']!=$rs['typename'] ? $bgcolor : '' ).'">'.$rs['typename'].'</li>';
				$str .='<li style="width:80px;'.($temp && $temp['product_name']!=$rs['product_name'] ? $bgcolor : '' ).'">'.$rs['product_name'].'</li>';
				$str .='<li style="width:55px;'.($temp && $temp['patent_type']!=$rs['patent_type'] ? $bgcolor : '' ).'">'.$rs['patent_type'].'</li>';
				$str .='<li style="width:150px;'.($temp && $temp['patent_name']!=$rs['patent_name'] ? $bgcolor : '' ).'">'.$rs['patent_name'].'</li>';
				$str .='<li style="width:80px;'.($temp && $temp['patent_status']!=$rs['patent_status'] ? $bgcolor : '' ).'">'.$rs['patent_status'].'</li>';
				$str .='<li style="width:120px;'.($temp && $temp['certificate_number']!=$rs['certificate_number'] ? $bgcolor : '' ).'">'.$rs['certificate_number'].'</li>';
				$str .='<li style="width:100px;'.($temp && $temp['project']!=$rs['project'] ? $bgcolor : '' ).'">'.$rs['project'].'</li>';
				$str .='<li style="width:100px;'.($temp && $temp['finance_project_name']!=$rs['finance_project_name'] ? $bgcolor : '' ).'">'.$rs['finance_project_name'].'</li>';
				$str .='<li style="width:200px;'.($temp && $temp['dept_name_str']!=$rs['dept_name_str'] ? $bgcolor : '' ).'">'.$rs['dept_name_str'].'</li>';
				$str .='<li style="width:100px;'.($temp && $temp['user_name_str']!=$rs['user_name_str'] ? $bgcolor : '' ).'">'.$rs['user_name_str'].'</li>';
				$str .='<li style="width:80px;'.($temp && $temp['submitted']!=$rs['submitted'] ? $bgcolor : '' ).'">'.date('Y-m-d',strtotime($rs['submitted'])).'</li>';
				$str .='<li style="width:120px;'.($temp && $temp['remark']!=$rs['remark'] ? $bgcolor : '' ).'">'.$rs['remark'].'</li>';
				$str .='<li style="width:77px;"><a href="#" onclick="get_info('.$rs['id'].')">查看详细</a></li>';
				$str .='</div>';
				$temp = $rs;
				$html_str_arr[] = $str;
			}
			krsort($html_str_arr);
			$str = implode('', $html_str_arr);
		}else{
			$str .= '暂没有变更记录！';
		}
		echo un_iconv($str);
	}
	/**
	 * 保存数据
	 */
	function c_save()
	{
		$_POST = mb_iconv($_POST);
		$type = $_GET['type'] ? $_GET['type'] : $_POST['type'];
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$identification = $_GET['identification'] ? $_GET['identification'] : $_POST['identification'];
		if (!$id && !$identification)
		{
			$_POST['identification'] = md5(time().rand(0, 9999));
			$_POST['operator'] = $_SESSION['USER_ID'];
			$_POST['date'] = date('Y-m-d H:i:s');
			if ($this->Add($_POST))
			{
				/*$Group = new model_system_usergroup_list();
				$GroupId = $Group->GetId('product_status_change'); //通过唯一标识获取用户组
				if ($GroupId)
				{
					$Emial_List = $Group->GetGroupUserEmail($GroupId);
					if ($Emial_List)
					{
						$this->EmailTask($_POST['en_name'].'正式发布了','详情请登录OA查看！'.oaurlinfo,$Emial_List);
					}
				}*/
				echo 1;
			}else{
				echo -1;
			}
		}elseif ($type == 'edit'){
			if ($this->Edit($_POST, $id))
			{
				echo 1;
			}else{
				echo -1;
			}
		}elseif ($type == 'change'){
			if ($this->Add($_POST)){
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	/**
	 * 删除记录
	 */
	function c_del()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($id && $this->Del($id))
		{
			echo 1;
		}else{
			echo -1;
		}
	}
	/**
	 * 部门
	 */
	function c_get_dept_data()
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
		
		echo json_encode($trre_data);
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