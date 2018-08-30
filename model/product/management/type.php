<?php
class model_product_management_type extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'product_type';
	}
}