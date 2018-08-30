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
	 * Ĭ�Ϸ���
	 */
	function c_index()
	{
		$this->c_list();
	}
	/**
	 * �����б�
	 */
	function c_list()
	{
		$this->show->display('ipo');
	}
	/**
	 * �б�����
	 */
	function c_list_data()
	{
		
		//����ȥҳ��Ͷ�����
		$data = $this->GetDataList(NULL,$_POST['page'],$_POST['rows']);
		//var_dump($data);die;
		//GetDataList�����������һ�����飬��������Ҫ��������
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
	 * ��������
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
	 * ɾ��
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
	 * �û��б�
	 */
	function c_user_list(){
		
		$userList = $this->getUserList();
		exit(json_encode(un_iconv($userList)));
		//print_r($userList);exit;
	}
	/**
	 * ��������
	 */
	function c_export(){
		//var_dump($_POST);
		$condition = $this->conditions();
		$this->export($condition);
	}
	/**
	 * ���빦��
	 */
	function c_import(){
			
		if($_FILES['upfile'] and $_FILES['upfile']['tmp_name'] != ''){
			set_time_limit ( 0 );
			$flag = $this->import();
			
			if($flag){
				//echo "<script>alert(22)</script>";
				
				//echo "<script>$('#import_div').window('close');</script>";
				echo '<script>parent.importResult("' . 1 . '");</script>';
				//showmsg ( '�����ɹ���', 'index1.php?model=project_rd', 'link' );
			}else{
				echo '<script>parent.importResult("' . 0 . '");</script>';
				//showmsg ( '����ʧ�ܣ�', 'index1.php?model=project_rd', 'link' );
			}
		}else{
			showmsg ( '��ѡ��Excel�����ļ���' );
		}
	}
}