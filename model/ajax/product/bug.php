<?php
class model_ajax_product_bug extends model_product_bug
{
	
	function __construct()
	{
		$_POST = $_POST ? mb_iconv($_POST) : null;
		$_GET = $_GET ? mb_iconv($_GET) : null;
		parent::__construct();
	}
	/**
	 * É¾³ý¸½¼þ
	 */
	function del_file()
	{
		$id =$_GET['id'] ? $_GET['id'] : $_POST['id'];
		$key =$_GET['key'] ? $_GET['key'] : $_POST['key'];
		$filename =$_GET['filename'] ? $_GET['filename'] : $_POST['filename'];
		$rs = $this->find(array('id'=>$id,'rand_key'=>$key));
		if ($rs['file_str'] && $rs['upfile_dir'])
		{
			$arr = explode(',',$rs['file_str']);
			$temp = array();
			foreach ($arr as $val)
			{
				if ($val==$filename)
				{
					unlink('upfile/product/bug/'.$rs['upfile_dir'].'/'.$val);
				}else{
					$temp[] = $val;
				}
			}
			if ($this->update(array('id'=>$id,'rand_key'=>$key),array('file_str'=>($temp ? implode(',',$temp) : ''))))
			{
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	function set_status()
	{
		$id =$_GET['id'] ? $_GET['id'] : $_POST['id'];
		$key =$_GET['key'] ? $_GET['key'] : $_POST['key'];
		if ($id && $key)
		{
			if ($this->update(array('id'=>$id,'rand_key'=>$key),array('status'=>2)))
			{
				return 1;
			}else{
				return 0;
			}
		}else{
			return -1;
		}
	}
	
	function feedback()
	{
		$id =$_GET['id'] ? $_GET['id'] : $_POST['id'];
		$key =$_GET['key'] ? $_GET['key'] : $_POST['key'];
		if ($id && $key)
		{
			if ($this->update(array('id'=>$id,'rand_key'=>$key),array('feedback'=>1)))
			{
				$this->send_email($id,$key,'upfd');
				return 1;
			}else{
				return 0;
			}
		}else{
			return -1;
		}
	}
}

?>