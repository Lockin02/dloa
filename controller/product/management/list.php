<?php
class controller_product_management_list extends model_product_management_list
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'product/management/';
	}
	/**
	 * 默认访问页
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
		$add_auth = 0;
		$Group = new model_system_usergroup_list();
		$GroupId = $Group->GetId('product_status_change_operator'); //通过唯一标识获取用户组
		
		if ($GroupId)
		{
			if ($Group->CheckUser($GroupId, $_SESSION['USER_ID']))
			{
				$add_auth = 1;
			}
		}
		$this->show->assign('add_auth', $add_auth);
		$this->show->assign('userid', $_SESSION['USER_ID']);
		$this->show->assign('product_option', $product_option_str);
		$this->show->display('list');
	}
	/**
	 * 列表数据
	 */
	function c_list_data()
	{
		$condition = '1=1';
		$product_id= $_GET['product_id'] ? $_GET['product_id'] : $_POST['product_id'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$condition .= $product_id ? " and a.product_id=".$product_id : '';
		$condition .= $keyword ? " and (a.cn_name like '%$keyword%' or a.en_name like '%$keyword%' or a.code like '%$keyword%' or a.desc like '%$keyword%' or a.status_desc like '%$keyword%')" : '';
		$data = $this->GetDataList($condition,$_POST['page'],$_POST['rows'],true);
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
	 * 变更记录
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
				//$str .='<li style="width:100px;'.($temp && $temp['typename']!=$rs['typename'] ? $bgcolor : '' ).'">'.$rs['typename'].'</li>';
				$str .='<li style="width:176px;'.($temp && $temp['product_name']!=$rs['product_name'] ? $bgcolor : '' ).'">'.$rs['product_name'].'</li>';
				$str .='<li style="width:176px;'.($temp && $temp['en_name']!=$rs['en_name'] ? $bgcolor : '' ).'">'.($rs['en_name'] ? $rs['en_name'] : '&nbsp;').'</li>';
				$str .='<li style="width:176px;'.($temp && $temp['cn_name']!=$rs['cn_name'] ? $bgcolor : '' ).'">'.($rs['cn_name'] ? $rs['cn_name'] : '&nbsp;').'</li>';
				$str .='<li style="width:176px;'.($temp && $temp['code']!=$rs['code'] ? $bgcolor : '' ).'">'.$rs['code'].'</li>';
				$str .='<li style="width:196px;'.($temp && $temp['desc']!=$rs['desc'] ? $bgcolor : '' ).'">'.$rs['desc'].'</li>';
				$str .='<li style="width:46px;'.($temp && $temp['status']!=$rs['status'] ? $bgcolor : '' ).'">'.($rs['status']==1 ? '成熟' : '不成熟').'</li>';
				$str .='<li style="width:36px;'.($temp && $temp['flag']!=$rs['flag'] ? $bgcolor : '' ).'">'.($rs['flag']==1 ? '是' : '否').'</li>';
				$str .='<li style="width:76px;'.($temp && $temp['time']!=$rs['time'] ? $bgcolor : '' ).'">'.date('Y-m-d',strtotime($rs['time'])).'</li>';
				$str .='<li style="width:46px;'.($temp && $temp['version']!=$rs['version'] ? $bgcolor : '' ).'">'.$rs['version'].'</li>';
				$str .='<li style="width:146px;'.(($temp && $temp['status_desc']!=$rs['status_desc']) ? $bgcolor : '' ).'">'.$rs['status_desc'].'</li>';
				//$str .='<li style="width:46px;'.($temp && $temp['unit']!=$rs['unit'] ? $bgcolor : '' ).'">'.$rs['unit'].'</li>';
				$str .='<li style="width:66px;'.($temp && $temp['owner_name']!=$rs['owner_name'] ? $bgcolor : '' ).'">'.$rs['owner_name'].'</li>';
				$str .='<li style="width:130px;"><a href="#" onclick="get_info('.$rs['id'].')">查看详细</a></li>';
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
		$_POST['time'] ? $_POST['time'] : null;
		$identification = $_GET['identification'] ? $_GET['identification'] : $_POST['identification'];
		if (!$id && !$identification)
		{
			$_POST['identification'] = md5(time().rand(0, 9999));
			$_POST['operator'] = $_SESSION['USER_ID'];
			$_POST['date'] = date('Y-m-d H:i:s');
			if ($this->Add($_POST))
			{
				$Group = new model_system_usergroup_list();
				$GroupId = $Group->GetId('product_status_change_notice'); //通过唯一标识获取用户组
				if ($GroupId)
				{
					$Emial_List = $Group->GetGroupUserEmail($GroupId);
					if ($Emial_List)
					{
						$this->EmialTask($_POST['en_name'].'正式发布了','详情请登录OA查看！'.oaurlinfo,$Emial_List);
					}
				}
				echo 1;
			}else{
				echo -1;
			}
		}elseif ($type == 'edit'){
			if ($id)
			{
				$rs = $this->GetOneInfo('id='.$id);
				if ($rs)
				{
					if ($this->Edit($_POST, $id))
					{
						echo 1;
					}else{
						echo -1;
					}
				}else{
					echo -1;
				}
			}
		}elseif ($type == 'change'){
			$_POST['operator'] = $_SESSION['USER_ID'];
			$_POST['date'] = date('Y-m-d H:i:s');
			if ($this->Add($_POST)){
				
				$Group = new model_system_usergroup_list();
				$GroupId = $Group->GetId('product_status_change_notice'); //通过唯一标识获取用户组
				if ($GroupId)
				{
					$Emial_List = $Group->GetGroupUserEmail($GroupId);
					if ($Emial_List)
					{
						$this->EmialTask($_POST['en_name'].' 的产品状态发生了变更','详情请登录OA查看！'.oaurlinfo,$Emial_List);
					}
				}
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	/**
	 * 删除
	 */
	function c_del()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($this->Del($id))
		{
			echo 1;
		}else{
			echo -1;
		}
	}
	
	function c_getinfo()
	{
		$id = $id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$data = $this->GetDataList('a.id='.$id);
		echo json_encode(un_iconv($data[0]));
	}
}

?>