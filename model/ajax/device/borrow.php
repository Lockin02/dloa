<?php
class model_ajax_device_borrow extends model_device_borrow
{
	function __construct()
	{
		parent::__construct();
		
		$_POST = $_POST ? mb_iconv($_POST) : null;
		$_GET = $_GET ? mb_iconv($_GET) : null;
	}
	
	function update_table()
	{
		$table = $_POST['table'] ? $_POST['table'] : $_GET['table'];
		$field = $_POST['field'] ? $_POST['field'] : $_GET['field'];
		$where = $_POST['where'] ? $_POST['where'] : $_GET['where'];
		$content = $_POST['content'] ? $_POST['content'] : $_GET['content'];
		$content = $content ? $content : '';
		if ($table && $field && $where)
		{
			$this->tbl_name = $table;
			if ($this->update($where,array($field=>$content)))
			{
				return 1;
			}else{
				return -1;
			}
		}else{
			return -2;
		}
		
	}
	
}

?>