<?php
class model_ajax_system_emailtask extends model_info_notice
{
	function __construct()
	{

		$_POST = $_POST ? mb_iconv($_POST) : false;
		$_GET = $_GET ? mb_iconv($_GET) : false;
		parent::__construct();
	}
	
	function update_pause()
	{
		$this->tbl_name = 'email_task';
		if ($this->update(array('id'=>$_POST['id'],'rand_key'=>$_POST['key']),array('pause'=>$_POST['send'])))
		{
			echo 1;
		}else{
			echo -1;
		}
	}
}

?>