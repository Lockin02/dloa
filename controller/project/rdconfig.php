<?php
class controller_project_rdconfig extends model_project_rdconfig{
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
		return $this->c_list();
	}
	
	/**
	 * 列表
	 */
	function c_list()
	{
		$this->show->display('rdconfig');
		
	}
	
	function c_list_data(){
		$data = $this->getConfigManager($_POST['page'],$_POST['rows']);
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
	
	function c_save(){
		
		$flag = 0;
		$_POST = mb_iconv($_POST);
		if(isset($_POST['username']) and !empty($_POST['username'])){
			
			$user_data = $this->get_username('user_name');
			
			
			$data['value'] = $user_data[$_POST['username']];
			$data['updatetime'] = date('Y-m-d H:i:s');
			
			if(isset($_POST['id']) and !empty($_POST['id'])){
				if($this->updateData($data, $_POST['id'])){
					$flag = 1;
				}
			}else{
				if($this->isAdminExist($data['value']) == 0){
					$data['type'] = 'admin';
					$data['createtime'] = date('Y-m-d H:i:s');
					if($this->createData($data)){
						$flag = 1;
					}
				}else{
					$flag = 2;
				}
			}
		}
		echo $flag;
		
	}
	
	function c_del(){
		
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($id)
		{
			if ($this->removeData($id, 'admin'))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			echo -1;
		}
	}
}