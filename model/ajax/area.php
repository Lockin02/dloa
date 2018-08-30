<?php
class model_ajax_area extends model_base 
{
	function __construct()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
		}
		parent::__construct();
	}
	
	function select_area()
	{
		$area = new includes_class_global();
		return $area->area_select($_POST['area_id']);
	}
}
?>