<?php
class model_ajax_xm extends model_base
{
	function __construct()
	{
		$_POST = $_POST ? mb_iconv($_POST) : null;
		$_GET = $_GET ? mb_iconv($_GET) : null;
		parent::__construct();
	}
	function get_info()
	{
		$this->tbl_name = 'project_info';
		if ($_POST['name'])
		{
			$rs = $this->_db->get_one("
										select 
												a.*,b.user_name 
										from 
											project_info as a 
											left join user as b on b.user_id=a.manager 
										where 
											name='".$_POST['name']."'
									");
			return $rs['id'].'|'.$rs['manager'].'|'.$rs['number'].'|'.$rs['user_name'];
		}else{
			return false;
		}
	}
	
	function get_project()
	{
		$field = $_GET['field'] ? $_GET['field'] : $_POST['field'];
		$content = $_GET['content'] ? $_GET['content'] : $_POST['content'];
		if ($field && $content)
		{
			$rs = $this->get_one("
									select 
										a.id,a.name,a.dept_id,a.manager,b.user_name
									from 
										project_info as a
										left join user as b on b.user_id=a.manager
									where
										a.".$field." = '".$content."'
									
									");
			return $rs ? $rs['id'].'|'.$rs['name'].'|'.$rs['dept_id'].'|'.$rs['manager'].'|'.$rs['user_name'] : '';
		}
	}
	
	function get_projectid()
	{
		$project_name = $_GET['project_name'] ? $_GET['project_name'] : $_POST['project_name'];
		$rs = $this->get_one("select id from project_info where name='$project_name' ");
		return $rs['id'];
	}
}
?>