<?php
class model_ajax_purview extends model_purview 
{
	public $func_id = '';
	function __construct()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
		}
		@extract($_POST);
		@extract($_GET);
		if ($action=='insert')
		{
			$this->func_id = 30;
		}
		parent::__construct();
	}
}
?>