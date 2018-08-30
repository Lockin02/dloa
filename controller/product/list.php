<?php
class controller_product_list extends model_product_list
{
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'product/';
	}
	/**
	 * Ĭ�Ϸ���
	 */
	function c_index()
	{
		$this->c_list();
	}
	/**
	 * ��Ʒ�б�
	 */
	function c_list()
	{
		$product_manager = '';
		$product_audit = '';
		$group = new model_system_usergroup_list();
		$group_id = $group->GetId('product_manager'); // ��ȡ��Ʒ����Ա
		if ($group_id && $group->CheckUser($group_id, $_SESSION['USER_ID']))
		{
			$product_manager = 1;
		}
		$group_id = $group->GetId('product_audit'); // ��ȡ��Ʒ������Ա
		if ($group_id && $group->CheckUser($group_id, $_SESSION['USER_ID']))
		{
			$product_audit = 1;
		}
		
		$obj = new model_product_management_type();
		$data = $obj->GetDataList();
		foreach ( $data as $row )
		{
			$str .= '<option value="' . $row['id'] . '">' . $row['typename'] . '</option>';
		}
		$this->show->assign('product_manager', $product_manager);
		$this->show->assign('product_audit', $product_audit);
		$this->show->assign ( 'type_option', $str );
		$this->show->display('product-list');
	}
	/**
	 * �б�����
	 */
	function c_list_data()
	{
		$condition = 'is_delete=0';
		$typeid = $_GET['typeid'] ? $_GET['typeid'] : $_POST['typeid'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$condition .= $typeid ? " and a.typeid=$typeid" : '';
		$condition .= $keyword ? " and (a.product_name like '%$keyword%' or a.en_product_name like '%$keyword%')" : '';
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
	
	function c_get_project()
	{
		$obj = new model_product_demand();
		$data = $obj->model_project($_GET['id']);
		$str = '<div><ul>';
		if ($data)
		{
			foreach ($data as $rs)
			{
				$str .='<li style="height:25px;lin-height:25px;">'.$rs['name'].'��'.$rs['user_name'].'��</li>';
			}
		}
		$str .='</ul></div>';
		echo un_iconv($str);
	}
	/**
	 * ����
	 */
	function c_save()
	{
		$_POST = mb_iconv($_POST);
		$_POST['assistant'] = $_POST['assistant_name'] ? $this->GetUserNmaeOrUserId(trim($_POST['assistant_name'],','),'user_name') : '';
		if ($_POST['id'])
		{
			$rs = $this->GetOneInfo('id='.$_POST['id']);
			if ($rs['status'] == -1)
			{
				$_POST['status'] = 0 ;
				$group = new model_system_usergroup_list();
				$group_id = $group->GetId('product_audit'); // ��ȡ��Ʒ������Ա
				$address = $group->GetGroupUserEmail($group_id);
				if ($address)
				{
					$email = new includes_class_sendmail();
					$email->send($_SESSION['USERNAME'].' �����ύ�˲�Ʒ�������,��������¼OA����', '<br />'.$_SESSION['USERNAME'].' �����ύ�˲�Ʒ������룬��Ʒ���ƣ�'.$_POST['product_name'].',�������¼OA�鿴��'.oaurlinfo, $address);
				}
			}
			if ($this->Edit($_POST, $_POST['id']))
			{
				if ($rs['status'] == 1)
				{
					$product = array();
					$product['company'] = 1;
					$product['tid'] = $_POST['id'];
					$product['name'] = $_POST['product_name'];
					$product['code'] = $_POST['id'];
					$product['PO'] = $_POST['manager'];
					$product['desc'] = $_POST['description'];
					$product['status'] = 'normal';
					$pms = new api_pms();
					$pms->GetModule('product', 'edit&params=tid='.$_POST['id'],un_iconv($product),'post'); 
				}
				echo 1;
			}else{
				echo -1;
			}
		}else{
			$_POST['creator'] = $_SESSION['USER_ID'];
			$_POST['date'] = time ();
			if ($this->Add($_POST))
			{
					$group = new model_system_usergroup_list();
					$group_id = $group->GetId('product_audit'); // ��ȡ��Ʒ������Ա
					$address = $group->GetGroupUserEmail($group_id);
					if ($address)
					{
						$email = new includes_class_sendmail();
						$email->send($_SESSION['USERNAME'].' �ύ�˲�Ʒ�������,��������¼OA����', '<br />'.$_SESSION['USERNAME'].' �ύ�˲�Ʒ������룬��Ʒ���ƣ�'.$_POST['product_name'].',�������¼OA�鿴��'.oaurlinfo, $address);
					}
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	/**
	 * ��������
	 */
	function c_save_audit()
	{
		$_POST = mb_iconv($_POST);
		$rs = $this->GetOneInfo('id='.$_POST['id']);
		
		if ($rs && $this->Edit($_POST, $_POST['id']))
		{
			if ($_POST['status'] == 1)
			{
				$product = array();
				$product['company'] = 1;
				$product['tid'] = $rs['id'];
				$product['name'] = $rs['product_name'];
				$product['code'] = $rs['id'];
				$product['PO'] = $rs['manager'];
				$product['desc'] = $rs['description'];
				$product['status'] = 'normal';
				$pms = new api_pms();
				$pms->GetModule('product', 'add',un_iconv($product),'post');
			}
			if ($rs['creator'])
			{
				$gl = new includes_class_global();
				$creator_email = $gl->get_email($rs['creator']);
			}
			//�����ʼ�
			$group = new model_system_usergroup_list();
			$group_id = $group->GetId('product_manager'); // ��ȡ��Ʒ������Ա
			$address = $group->GetGroupUserEmail($group_id);
			if ($address || $creator_email)
			{
				$address = array_merge($creator_email,$address);
				$email = new includes_class_sendmail();
				$email->send($rs['product_name'].' ��Ʒ��������������', $rs['product_name'].' ��Ʒ�������'.($_POST['status']==1 ? '����ͨ��' : '�����').',�������¼OA�鿴��'.oaurlinfo, $address);
			}
			echo 1;
		}else{
			echo -1;
		}
	}
	/**
	 * ɾ��
	 */
	function c_del()
	{
		if ($this->Edit(array('is_delete'=>1), $_POST['id']))
		{
			echo 1;
		}else{
			echo -1;
		}
	}
}