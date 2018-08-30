<?php
class model_ajax_info_new extends model_info_new
{
	function __construct()
	{

		$_POST = $_POST ? mb_iconv($_POST) : false;
		$_GET = $_GET ? mb_iconv($_GET) : false;
		parent::__construct();
	}
}
?>