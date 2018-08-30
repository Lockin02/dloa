<?php
class model_ajax_pvurl extends model_pvurl 
{
	function __construct()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
		}
		parent::__construct();
	}
}
?>