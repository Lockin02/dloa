<?php
class controller_jobs extends model_system_organizer_jobs 
{
	public $func_id='712';
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
	}
	
	function c_index()
	{
		$dept = new includes_class_depart();
		$this->show->assign('dept_select',$dept->depart_select());
		$this->show->assign('list',$this->showlist());
		$this->show->display('jobs_list');
	}
	
	function c_edit_func_new()
	{
		$this->show->assign('id',$_GET['id']);
		$this->show->assign('list',$this->edit_purview());
		$this->show->display('jobs_editpurivew');
	}
        function c_edit_func()
	{
		$this->show->assign ( 'jid', $_GET ['id'] );
		$this->show->display ( 'general_system_job_show-priv' );
	}
	function c_add()
	{
		if ($this->insert())
		{
			showmsg('添加成功！','?model=system_organizer_jobs','link');
		}
	}
	function c_save_func()
	{
            if ($_POST['jid'])
            {
                echo un_iconv($this->model_save_func());
            } else
            {
                echo un_iconv( '非法提交！' );
            }
	}
}
?>