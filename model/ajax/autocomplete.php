<?php
class model_ajax_autocomplete extends model_base
{
	function __construct()
	{
		parent::__construct();
		$_POST = $_POST ? mb_iconv($_POST) : null;
		$_GET = $_GET ? mb_iconv($_GET) : null;
	}
	/**
	 * 模糊查询，返回单字段
	 */
	function GetField()
	{
		$table = $_GET['table'] ? $_GET['table'] : $_POST['table'];
		$field = $_GET['field'] ? $_GET['field'] : $_POST['field'];
		$word = $_GET['q'] ? $_GET['q'] : $_POST['q'];
		$where = $table=='user' ? "where 1 and del=0 and $field like '%".$word."%'" : "where $field like '%".$word."%'";
		if ($table && $field)
		{
			$query = $this->query("select $field from $table $where");
			$data = array();
			while (($rs = $this->fetch_array($query))!=false)
			{
				$data[] = $rs[$field];
			}
			return implode(',',$data);
		}else{
			return false;
		}
	}
	/**
	 * 模糊查询返回多字段
	 */
	function GetTable()
	{
		$table = $_GET['table'] ? $_GET['table'] : $_POST['table'];
		$field = $_GET['field'] ? $_GET['field'] : $_POST['field'];
		$return_field = $_GET['return_field'] ? $_GET['return_field'] : $_POST['return_field'];
		$word = $_GET['q'] ? $_GET['q'] : $_POST['q'];
		$where = $table=='user' ? "where 1 and del=0 and $field like '%".$word."%'" : "where $field like '%".$word."%'";
		$where = $table=='project_info' ? "where  ($field like '%".$word."%' or number like '%".$word."%') AND flag=1 " : "where $field like '%".$word."%'";
		if ($table && $field && $return_field)
		{
			$field_arr = explode(',',$return_field);
			$query = $this->query("select $return_field from $table $where");
			$data = array();
			while (($rs = $this->fetch_array($query))!=false)
			{
				$temp = array();
				foreach ($field_arr as $val)
				{
					$temp[$val] = un_iconv($rs[$val]);
				}
				$data[] = $temp;
			}
			return json_encode($data);
		}else{
			return false;
		}
	}
}

?>