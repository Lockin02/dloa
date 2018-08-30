<?php
class model_test extends model_base 
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'user';
		$this->pk = 'USER_ID';
	}
	
	function userlist()
	{
		return $this->findAll('find_in_set(14,jobs_id)','USER_ID DESC');

	}
	function userinfo()
	{
		return $this->find("USER_ID='aaa'");
	}
	
	function insert()
	{
		return $this->create(array('USER_ID'=>'niuzi56' ,'USER_NAME'=>'ปฦณษณฏ'));
	}
}
?>