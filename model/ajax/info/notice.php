<?php
class model_ajax_info_notice extends model_info_notice
{
	function __construct()
	{

		$_POST = $_POST ? mb_iconv($_POST) : false;
		$_GET = $_GET ? mb_iconv($_GET) : false;
		parent::__construct();
	}
}
?>