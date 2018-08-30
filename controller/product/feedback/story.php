<?php

class controller_product_feedback_story extends model_product_feedback_story{
	
	private $show;
	function __construct(){
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'product/feedback/';
	}
	
	/**
	 * 入口
	 */
	function c_index(){
		$this->c_list();
	}

	/**
	 * 获取相关数据显示至template
	 */
	function c_list(){
		$product_option_str = '';
		$product = new model_product_demand ();
		$product_data = $product->get_typelist ();

		if ($product_data){
			foreach ( $product_data as $rs ){
				$product_option_str .= '<option value="' . $rs ['id'] . '">' . $rs ['product_name'] . '</option>';
			}
		}
		
		global $func_limit;
		$isAdmin = 'false';
		if($func_limit[ '管理员' ]){
			$isAdmin = 'true';
		}
		
		$pConfig = new model_product_feedback_approver();
		$needTwice = $pConfig->getSettingByName('isset_twice');
		
		$this->show->assign('isAdmin', $isAdmin);
		$this->show->assign('need_twice', $needTwice);
		$this->show->assign('userid', $_SESSION['USER_ID']);
		$this->show->assign('username', $_SESSION['USERNAME']);
		$this->show->assign('user_email', $_SESSION['EMAIL']);
		$this->show->assign( 'product_option', $product_option_str );
		$this->show->display( 'story' );
	}
	
	/**
	 * 获取列表数据
	 */
	function c_list_data(){
		$condition = "";
		$typeid = "";
		$keyword = "";
		$status = "";
		if(isset($_REQUEST['status'])){
			$status = $_REQUEST['status'];
		}
		
		if(isset($_REQUEST['keyword'])){
			$keyword = $_REQUEST['keyword'];
		}
		
		if(isset($_REQUEST['typeid'])){
			$typeid = $_REQUEST['typeid'];
		}
		
		if(isset($_REQUEST['s_start_date'])){
			$startDate = $_REQUEST['s_start_date'];
		}
		
		if(isset($_REQUEST['s_end_date'])){
			$endDate = $_REQUEST['s_end_date'];
		}
		
		if(isset($_REQUEST['s_deadline'])){
			$deadline = $_REQUEST['s_deadline'];
		}
		
		if(isset($_REQUEST['s_degree'])){
			$degree = $_REQUEST['s_degree'];
		}
		
		if(isset($_REQUEST['s_title'])){
			$title = $_REQUEST['s_title'];
		}
		
		$condition .= $typeid != "" ? $condition == "" ? " a.product_id = $typeid" : " and a.product_id = $typeid" : '';
		$condition .= $keyword != "" ? $condition == "" ? " c.user_name like '%$keyword%' " : " and c.user_name like '%$keyword%' " : '';
		$condition .= $status != "" ? $condition == "" ? " a.status = $status" : " and a.status = $status" : '';
		$condition .= $degree != "" ? $condition == "" ? " a.degree = {$degree}" : " and a.degree = {$degree}" : '';
		$condition .= $deadline != "" ? $condition == "" ? " a.deadline = '{$deadline}'" : " and a.deadline = '{$deadline}'" : '';
		$condition .= $startDate != "" ? $condition == "" ? " a.date>='".strtotime($startDate.' 00:00:00')."'" : " and a.date>='".strtotime($startDate.' 00:00:00')."'" : '';
		$condition .= $endDate != "" ? $condition == "" ? " a.date<='".strtotime($endDate.' 23:59:59')."' " : " and a.date<='".strtotime($endDate.' 23:59:59')."'" : '';
		$condition .= $title != "" ? $condition == "" ? " a.title like '%{$title}%'" : " and a.title like '%{$title}%'" : '';
		//echo $condition;exit;
		
		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows'], true );
		$json = array (
					'total' => $this->num 
		);
		if ($data)
		{
//			echo "<pre>";
//			print_r($data);
//			echo "</pre>";exit;
			
			
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		echo json_encode ( $json );
	}
	
	/**
	 * 保存或更新数据 
	 */
	function c_save(){
		$isSave = 0;
		$this->saveUploadFile();
		$result = $this->saveData();
		
		
		
		if(is_array($result) and count($result) > 0){
			$product_obj = new model_product_list();
			$product_info = $product_obj->GetOneInfo('id='.$_POST['product_id']);
			
			$user_id_list = $product_info['feedback_owner'];
			$user_id_list .= $product_info['feedback_assistant'] ? ','.$product_info['feedback_assistant']: '';
			
			if ($user_id_list)
			{
				$model_product_bug = new model_product_feedback_bug();
				$address = $model_product_bug->getAddressList($user_id_list);
				
				$product_info['title'] = $_POST['title'];
				$product_info['description'] = $_POST['description']; 
				$mailNote = $this->mailNoteForNew($result);
				
				if ($address)
				{
					$Email = new includes_class_sendmail();
					$Email->send ( $_SESSION['USERNAME'] . '提交了产品需求', $mailNote['body'], $address );
				}
				
				$isSave = 1;
			}
		}
		echo '<script>parent.save_status("' . $isSave . '");</script>';
	}
	
	/**
	 * 删除附件操作
	 */
	function c_delfile(){
		$makeStr = '';
		$_POST = mb_iconv($_POST);
		$postData = $_POST;
		unset($_POST['upfile']);
		$fileArr = $this->getUploadFile($_POST['id']);
		foreach($fileArr as $key => $value){
			
			if($value == $postData['upfile']){
				unset($fileArr[$key]);
			}elseif('' == $postData['upfile']){
				unset($fileArr[$key]);
			}else{
				$makeStr .= $value.',';
			}
		}
		$makeStr = substr($makeStr, 0, strlen($makeStr) - 1);
		$_POST['upfile'] = $makeStr;
		$_POST['function'] = 'delfile';
		$result = $this->saveData();
		if(count($result) > 0){
			echo 1;
		}else{
			echo -1;
		}
	}
	
	function c_set_feedback(){
		$rs = $this->getDataInfoById($_POST['id']);
		if($rs && $this->Edit($_POST, $_POST['id'])){
			echo 1;
		}else{
			echo -1;
		}
	}
	
	/**
	 * 导出EXCEL表
	 */
	function c_export(){
		$this->model_list_export();
	}
	
	/**
	 * 保存审核
	 * 如通过则同步PMS
	 * 如打回则只更新状态
	 */
	function c_audit(){
		$isSucceed = -1;
		if($_POST){
			$data = mb_iconv($_POST);
			$id = $data['id'];
			unset($data['id']);
			if($this->Edit($data, $id)){
				
				$rs = $this->getDataInfoById($id);
			
				$flag = true;
				
				//审核后同步PMS,必须为status不等于-1
				if($rs['status'] != -1){
				//if(0 != 0){
					$flag = $this->synchronization_data($rs);
				}
				
				if($flag){
					
					$model_product_bug = new model_product_feedback_bug();
					if($rs['status'] != -1){
						$user_id_list = $rs['manager'];
						$user_id_list .= $rs['assistant'] ? ','.$rs['assistant'] : '';
						$address = $model_product_bug->getAddressList($user_id_list);
						
					}else{
						$address = $model_product_bug->failBackAddressList($rs['userid'], $rs['email']);
					}
					
					$mailList = $this->mailNoteForStatusChange($rs, $data['status']);
					
//					$this->sendMail($mailList['title'], $mailList['body'], $address);
					$Email = new includes_class_sendmail();
					$Email->send ($mailList['title'], $mailList['body'], $address);
					$isSucceed = 1;
				}
			}
		}
		
		echo $isSucceed;
	}
	
	/**
	 * 保存初审结果
	 * 通过 将发送邮件至产品经理，
	 * 打回 将发送邮件至提出人.
	 * ------------------------
	 * 提交成功返回1，失败则返回-1
	 */
	function c_first_trial(){
		$isSucceed = -1;
		if($_POST){
			if($_POST['status'] == -1){
				unset($_POST['source']);
				unset($_POST['type']);
			}
			$data = mb_iconv($_POST);
			
			if($this->Edit($data, $data['id'])){
				$rs = $this->getDataInfoById($data['id']);
				
				$address = array();
				$model_product_bug = new model_product_feedback_bug();
				if($rs['status'] == -1){
					$address = $model_product_bug->failBackAddressList($rs['userid'], $rs['email']);
				}else{
					$user_id_list = $rs['manager'];
					$user_id_list .= $rs['assistant'] ? ','.$rs['assistant'] : '';
					$address = $model_product_bug->getAddressList($user_id_list);
					
				}
				$mailList = $this->mailNoteForStatusChange($rs, $data['status']);
//				$this->sendMail($mailList['title'], $mailList['body'], $address);
				$Email = new includes_class_sendmail();
				$Email->send ($mailList['title'], $mailList['body'], $address);
				$isSucceed = 1;
			}
		}
		echo $isSucceed;
	}
	
	
	/**
	 * 设置为实现
	 */
	function c_realize()
	{
		$flag = -1;
		if ($_POST['status'] == 1)
		{
			unset($_POST['status']);
			$_POST['realize_date'] = date('Y-m-d');
			$_POST['status'] = 2;
			if($this->Edit($_POST, $_POST['id'])){
				$flag = 1;
			}
		}
		echo $flag;
	}
	
	/**
	 * 根据ID获取单条记录
	 */
	function c_info(){
		return $this->getDataInfoById($_POST['id']);
	}
	
	
	/**
	 * 导入
	 */
	function c_import_file(){
		
		if(isset($_POST['import_product_id']) && $_POST['import_product_id'] != ''){
			if($_FILES['upfile']){
				$newRecordList = array();
				set_time_limit ( 0 );
				$newRecordList = $this->model_save_import ($_POST['import_product_id']);
				$mailList = $this->import_sendMail($newRecordList);
				
				if (count($mailList) > 0){
					include_once WEB_TOR.'includes/class/sendmail.php';
					$Email = new includes_class_sendmail();
					$Email->send($mailList['title'], $mailList['body'], $mailList['address']);
					echo '<script>parent.importResult("' . 1 . '");</script>';
				} else{
					echo '<script>parent.importResult("' . 0 . '");</script>';
				}
			}else{
				showmsg ( '请选择Excel数据文件！' );
			}
		}else{
			showmsg ( '请选择产品！' );
		}
	}
	
	function c_del(){
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$key = $_GET['rand'] ? $_GET['rand'] : $_POST['rand'];
		
		if ($this->model_delete($id))
		{
			echo 1;
		}else{
			echo 0;
		}
	}
	
}