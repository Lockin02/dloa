<?php
class model_ajax_device_stock extends model_device_stock
{
	function __construct()
	{
		if ($_POST)
		{
			$_POST = mb_iconv ( $_POST );
		}
		parent::__construct ();
	}
	/**
	 * 更新设备信息
	 *
	 */
	function update_info()
	{
		if ($this->model_update_info ())
		{
			echo 1;
		} else
		{
			echo 2;
		}
	}
	
	function del_device(){
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$key = $_GET['key'] ? $_GET['key'] : $_POST['key'];
		$this->tbl_name = 'device_list';
		$rs = $this->get_one("select id,total from device_list where id=".$id." and rand_key='".$key."'");
		if ($rs)
		{
			if ($rs['total'] > 0)
			{
				return -1;
			}else{
				if ($this->delete(array('id'=>$id,'rand_key'=>$key)))
				{
					return 1;
				}else{
					return 0;
				}
			}
		}else{
			return -2;
		}
	}
	function get_device_option()
	{
		$data = $this->model_get_device ( $_POST['typeid'] );
		if ($data)
		{
			foreach ( $data as $key => $row )
			{
				$str .= '<option value="' . $row['id'] . '">' . $row['device_name'] . '</option>';
			}
			return $str;
		}
	}
	
	function select_type()
	{
		return parent::select_type($_POST['typeid'],$_POST['dept_id'],false);
	}
	/**
	 * 字段下拉
	 */
	function get_field_name()
	{
		return $this->model_ajax_get_field_name($_POST['typeid'] ? $_POST['typeid'] : $_GET['typeid']);
	}
	
	function get_device_in()
	{
		return $this->model_device_info($_POST['info']);
	}
}
?>