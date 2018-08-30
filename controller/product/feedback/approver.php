<?php
class controller_product_feedback_approver extends model_product_feedback_approver
{
	private $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'product/feedback/';
	}
	/*
	 * 默认访问页
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
		$obj = new model_product_management_type();
		$data = $obj->GetDataList();
		foreach ( $data as $row )
		{
			$str .= '<option value="' . $row['id'] . '">' . $row['typename'] . '</option>';
		}
		
		$needTwice = $this->getSettingByName('isset_twice');
		$this->show->assign ( 'type_option', $str );
		$this->show->assign('need_twice', $needTwice);
		$this->show->display('approver');
		
	}
	/**
	 * 列表数据
	 */
	function c_list_data()
	{
		$condition = 'is_delete=0 and status = 1';
		$typeid = $_GET['typeid'] ? $_GET['typeid'] : $_POST['typeid'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$condition .= $typeid ? " and a.typeid=$typeid" : '';
		$condition .= $keyword ? " and (a.product_name like '%$keyword%' or a.en_product_name like '%$keyword%')" : '';
		$product_obj = new model_product_list();
		$data = $product_obj->GetDataList($condition,$_POST['page'],$_POST['rows'],true);
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
		$product_obj = new model_product_list();
		$_POST['feedback_owner'] = $_POST['feedback_owner_name'] ? $product_obj->GetUserNmaeOrUserId(trim($_POST['feedback_owner_name'],','),'user_name') : '';
		$_POST['feedback_assistant'] = $_POST['feedback_assistant_name'] ? $product_obj->GetUserNmaeOrUserId(trim($_POST['feedback_assistant_name'],','),'user_name') : '';
		if ($_POST['id'])
		{
			if ($this->Edit($_POST, $_POST['id']))
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	
	function c_save_setting(){
		$isSet = 0;
		if($_POST){
			
			$value = $_POST['is_twice'];
			$count = $this->count_config('isset_twice');
			
			if($count == 0){
				$isSet = $this->save_config('isset_twice', $value);
			}else{
				$isSet = $this->update_config('isset_twice', $value);
			}
		}
		
		echo $isSet;
	}
	
}