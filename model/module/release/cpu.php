<?php
class model_module_release_cpu extends model_base
{ 
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'base_module_cpu';
	}
}