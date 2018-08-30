<?php
class controller_product_management_collection extends model_product_management_collection
{
	private $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'product/management/';
	}
	/**
	 * 默认访问页
	 */
	function c_index()
	{
		$this->show->display('collection');
	}
	/**
	 * 搜索结果列表数据
	 */
	function c_list_data()
	{
		set_time_limit(0);
		$_POST = mb_iconv($_POST);
		$type = $_GET['type'] ? $_GET['type'] : $_POST['type'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$json = $this->GetListData($type, $keyword,$_POST['page']);
		echo json_encode ( $json );
		
	}
	
	function c_data_list_data()
	{
		$condition = '1=1 ';
		$leixin = $_GET['leixin'] ? $_GET['leixin'] : $_POST['leixin'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$condition .= $leixin ? " and leixin='$leixin'" : '';
		$condition .= $keyword ? " and title like '%$keyword%'" : '';
		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows']);
		$json = array (
						'total' => $this->num 
		);
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
	 * 单条记录详细
	 */
	function c_get_info()
	{
		$recid = $_GET['recid'] ? $_GET['recid'] : $_POST['recid'];
		$data = $this->GetInfo($recid);
		echo json_encode(un_iconv($data));
	}
	/**
	 * 采集数据到本地
	 */
	function c_GetData()
	{
		set_time_limit(0);
		$recid = $_GET['recid'] ? $_GET['recid'] : $_POST['recid'];
		$leixin = $_GET['leixin'] ? $_GET['leixin'] : $_POST['leixin'];
		if ($recid)
		{
			if ($this->SaveUrlData($recid,$leixin))
			{
				echo 'ok';
			}else{
				echo 'no';
			}
		}else{
			echo 'not_recid';
		}
	}
	/**
	 * 导入
	 */
	function c_export_data()
	{
		$condition ='';
		$recid = $_GET['recid'] ? $_GET['recid'] : $_POST['recid'];
		if ($recid)
		{
			if ($recid == 'all')
			{
				$condition = '';
			}else{
				$arr = explode(',', trim($recid,','));
			}
			if ($this->CopyOneData($arr))
			{
				echo 'ok';
			}
		}
	}
}