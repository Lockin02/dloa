<?php
class model_ajax_system_menu extends model_system_menu_index
{

	function __construct()
	{
		$_POST = $_POST ? mb_iconv($_POST) : false;
		$_GET = $_GET ? mb_iconv($_GET) : false;
		parent::__construct();
	}
}

?>