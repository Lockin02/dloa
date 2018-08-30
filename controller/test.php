<?php
class controller_test extends model_test 
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
	}
	function index()
	{
		$user = $this->userlist();
		foreach ($user as $rs)
		{
			echo $rs['USER_ID'].'<br />';
		}
	}
	function showinfo()
	{
		$user = $this->userinfo();
		var_dump($user);
	}
	function add()
	{
		if ($this->insert())
		{
			echo 'Ìí¼Ó³É¹¦£¡';
		}
	}
}
?>