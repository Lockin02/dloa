<?php
class controller_product_management_copyright extends model_product_management_copyright
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
	 * 列表
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
		$group_id = $group->GetId('product_copyright_manager'); // 获取产品著作权管理员
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
		$this->show->display('copyright');
	}
	/**
	 * 列表数据
	 */
	function c_list_data()
	{
		$data = $this->GetDataList(NULL,$_POST['page'],$_POST['rows'],true);
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
	 * 保存数据
	 */
	function c_save()
	{
	$_POST = mb_iconv($_POST);
		$type = $_GET['type'] ? $_GET['type'] : $_POST['type'];
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if (!$id)
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
	 * 删除
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
}