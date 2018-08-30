<?php
class model_ajax_purchase extends model_purchase 
{
	function __construct()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
		}
		parent::__construct();
		$this->tbl_name='purchase';
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
}

?>