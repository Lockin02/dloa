<?php
class model_project_rdmember extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'project_rd_action';
	}
}