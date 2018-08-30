<?php
class model_ajax_product_demand extends model_product_demand
{
	function __construct()
	{
		$_POST = $_POST ? mb_iconv($_POST) : null;
		$_GET = $_GET ? mb_iconv($_GET) : null;
		parent::__construct();
	}
	
	function get_project()
	{
		$typeid = $_GET['typeid'] ? $_GET['typeid'] : $_POST['typeid'];
		$data = $this->model_project($typeid);
		if ($data)
		{
			foreach ($data as $key=>$row)
			{
				if ($row)
				{
					$str .='<li>'.$row['name'].' -- '.$row['user_name'].'</li>';
				}
			}
			return $str;
		}
	}
}

?>