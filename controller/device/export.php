<?php
class controller_device_export extends model_device_export
{
	private $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'device/';
	}
	/**
	 * 库存总表
	 */
	function c_total()
	{
		$this->model_total();
	}
	
	/**
	 * 库存设备
	 */
	function c_type_list()
	{
		if ($_GET['typeid'])
		{
			$this->model_type_list($_GET['typeid'], $_GET['id']);
		}else{
			showmsg('非法参数！');
		}
	}
	/**
	 * 按类别借出的设备
	 */
	function c_borrow_type_info()
	{
		if ($_GET['typeid'])
		{
			$this->model_borrow_type_info($_GET['typeid']);
		}else{
			showmsg('非法参数！');
		}
	}
	/**
	 * 所有借出设备
	 */
	function c_borrow_info_list()
	{
		$this->model_borrow_info_list();
	}
	/**
	 * 项目借用的设备
	 */
	function c_project_info_list()
	{
		$this->model_project_info_list();
	}
	
	
	/**
	 * 导出报表
	 */
	function c_report()
	{
		if ($_POST)
		{
			$this->report($_POST['report_type'],$_GET['typeid'],$_GET['id'],$_POST['start_date'],$_POST['end_date'],$_POST['field'],$_POST['count_field']);
		}else{
			$field_arr = $this->fixed_format($_GET['typeid']);
			$field_list = '';
			$count_list = '';
			if ($field_arr)
			{
				foreach ($field_arr as $key=>$val)
				{
					$field_list .= '<input type="checkbox" name="field[]" value="'.$key.'" />'.$val.' ';
					$count_list .= '<input type="checkbox" name="count_field[]" value="'.$key.'" />'.$val.' ';
				}
			}
			$this->show->assign('field_list',$field_list);
			$this->show->assign('count_list',$count_list);
			$this->show->display('report-exprot');
		}
	}
	
	function c_exportInfoData(){
		$stock=new model_device_stock();
		$typeI=$stock->get_type();
		if($typeI&&is_array($typeI)){
		   $this->model_typeListInfo($typeI);	
		}
		

	}
	
}

?>