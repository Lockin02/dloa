<?php
class controller_product_management_competitors extends model_product_management_competitors
{
	private $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'product/management/';
	}
	/**
	 * Ĭ�Ϸ���ҳ
	 */
	function c_index()
	{
		$this->c_list();
	}
	/**
	 * �б�
	 */
	function c_list()
	{
		$group = new model_system_usergroup_list();
		$group_id = $group->GetId('product_competitors_patent_manager'); // ��ȡ��������ר������Ա
		$administrator = '';
		if ($group_id && $group->CheckUser($group_id, $_SESSION['USER_ID']))
		{
			$administrator = 'true';
		}
		$this->show->assign('administrator', $administrator);
		$this->show->display('competitors');
	}
	/**
	 * �б�����
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
	 * ���
	 */
	function c_add()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
			if ($this->Add($_POST))
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	/**
	 * �༭
	 */
	function c_edit()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
			if ($this->Edit($_POST, $_GET['id']))
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
		if ($_GET['id'])
		{
			if ($this->Del($_GET['id']))
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
}