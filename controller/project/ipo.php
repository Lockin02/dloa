<?php
class controller_project_ipo extends model_project_ipo
{
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'project/';
	}
	/**
	 * 默认访问
	 */
	function c_index()
	{
		$this->c_list();
	}
	/**
	 * 管理列表
	 */
	function c_list()
	{
		$this->show->display('ipo');
	}
	/**
	 * 列表数据
	 */
	function c_list_data()
	{
		
		//传进去页码和多少行
		$data = $this->GetDataList(NULL,$_POST['page'],$_POST['rows']);
		//var_dump($data);die;
		//GetDataList这个函数返回一个数组，返回了需要的数据了
		$json = array('total'=>$this->num);
		if ($data)
		{
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		echo json_encode ( $json );
	}
	/**
	 * 保存数据
	 */
	function c_save()
	{
		$_POST = mb_iconv($_POST);
		$id = isset($_GET['id']) ? intval($_GET['id']) : $_POST['id'];
		$type = $_GET['type'] ? $_GET['type'] : $_POST['type'];
		if (!$id)
		{
			if ($this->Add($_POST))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			if ($this->Edit($_POST, $id))
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	/**
	 * 删除
	 */
	function c_del()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($this->Del($id))
		{
			echo 1;
		}else{
			echo -1;
		}
	}
	
	/**
	 * 用户列表
	 */
	function c_user_list(){
		
		$userList = $this->getUserList();
		exit(json_encode(un_iconv($userList)));
		//print_r($userList);exit;
	}
	/**
	 * 导出功能
	 */
	function c_export(){
		//var_dump($_POST);
		$condition = $this->conditions();
		$this->export($condition);
	}
	/**
	 * 导入功能
	 */
	function c_import(){
			
		if($_FILES['upfile'] and $_FILES['upfile']['tmp_name'] != ''){
			set_time_limit ( 0 );
			$flag = $this->import();
			
			if($flag){
				//echo "<script>alert(22)</script>";
				
				//echo "<script>$('#import_div').window('close');</script>";
				echo '<script>parent.importResult("' . 1 . '");</script>';
				//showmsg ( '操作成功！', 'index1.php?model=project_rd', 'link' );
			}else{
				echo '<script>parent.importResult("' . 0 . '");</script>';
				//showmsg ( '操作失败！', 'index1.php?model=project_rd', 'link' );
			}
		}else{
			showmsg ( '请选择Excel数据文件！' );
		}
	}
}