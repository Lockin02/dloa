<?php
class model_ajax_device extends model_device 
{
	function __construct()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
		}
		if ($_GET)
		{
			$_GET = mb_iconv($_GET);
		}
		parent::__construct();
	}
	
	function update_type()
	{
		$this->tbl_name = 'purchase_type';
		if ($this->model_update_type())
		{
			echo 1;
		}else{
			echo 2;
		}
	}
	function del_type()
	{
		$this->tbl_name = 'purchase_type';
		if ($this->model_delete_type())
		{
			echo 1;
		}else{
			echo 2;
		}
	}
	/**
	 * ����ֶ������Ƿ����
	 */
	function check_field_content()
	{
		$typeid = $_POST['typeid'] ? $_POST['typeid'] : $_GET['typeid'];
		$list_id = $_POST['list_id'] ? $_POST['list_id']:$_GET['list_id'];
		$fname = trim($_POST['fname'] ? $_POST['fname'] : $_GET['fname']);
		$content = trim($_POST['content'] ? $_POST['content'] : $_GET['content']);
		$field ='';
		//return $fname.'==���ű���';
		switch ($fname)
		{
			case '������':
				$field = 'coding';
				break;
			case '���ű���':
				$field = 'dpcoding';
				break;
			case '���':
				$field = 'fitting';
				break;
			case '����':
				$field = 'price';
				break;
			case '��ע':
				$field = 'notes';
				break;	
		}
		if ($typeid && $fname && $content)
		{
			if ($field)
			{
				$rs = $this->get_one("
										select 
											a.id 
										from 
											device_info as a
											left join device_list as b on b.id=a.list_id
										where 
											$field = '$content' and b.typeid=$typeid and b.id=$list_id
										");
				if ($rs)
				{
					return 1;
				}else{
					return -1;
				}
			}else{
				$rs = $this->get_one("select id from device_type_field where typeid=$typeid and fname='$fname'");
				if ($rs)
				{
					$row = $this->get_one("select 
												a.id 
											from 
												device_type_field_content as a 
												left join  device_info as b on b.id=a.info_id
											where 
												a.field_id=".$rs['id']."
												and a.content='$content'
												and b.list_id=$list_id
												");
					if ($row)
					{
						return 1;
					}else{
						return -1;
					}
				}else{
					return -2;
				}
			}
		}else{
			return -2;
		}
	}
}
?>