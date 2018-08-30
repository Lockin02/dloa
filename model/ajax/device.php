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
	 * 检查字段内容是否存在
	 */
	function check_field_content()
	{
		$typeid = $_POST['typeid'] ? $_POST['typeid'] : $_GET['typeid'];
		$list_id = $_POST['list_id'] ? $_POST['list_id']:$_GET['list_id'];
		$fname = trim($_POST['fname'] ? $_POST['fname'] : $_GET['fname']);
		$content = trim($_POST['content'] ? $_POST['content'] : $_GET['content']);
		$field ='';
		//return $fname.'==部门编码';
		switch ($fname)
		{
			case '机身码':
				$field = 'coding';
				break;
			case '部门编码':
				$field = 'dpcoding';
				break;
			case '配件':
				$field = 'fitting';
				break;
			case '单价':
				$field = 'price';
				break;
			case '备注':
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