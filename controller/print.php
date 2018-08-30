<?php
class controller_print
{
	public $show;
	function __construct()
	{
		$this->show = new show('print');
	}
	function c_index()
	{
		$this->show->display();
	}
}
?>