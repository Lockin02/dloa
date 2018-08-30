<?php
class model_ajax_device_buy extends model_device_buy
{
	function __construct()
	{
		if ($_POST)
		{
			$_POST = mb_iconv ( $_POST );
		}
		parent::__construct ();
	}
	function get_field_name()
	{
		return $this->model_field_input($_POST['typeid']);
	}
}

?>