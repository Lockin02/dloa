<?php
class controller_competence extends model_competence
{
	public $show;
	function __construct()
	{
		$this->show = new show();
	}
	
	function index()
	{
		$this->showlist();
	}
	
	function showlist()
	{
		$this->show->assign('list',$this->model_showlist());
		$this->show->display('competence_list');
	}
}
?>