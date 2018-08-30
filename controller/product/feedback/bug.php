<?php
class controller_product_feedback_bug extends model_product_feedback_bug
{
	private $show;
	
	function __construct()
	{
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'product/feedback/';
	}
	
	//Ĭ�Ϸ���
	function c_index()
	{
		$this->c_list();
	}
	
	//�б�
	function c_list()
	{
		$product_option_str = '';
		$product = new model_product_demand ();
		$product_data = $product->get_typelist ();

		if ($product_data)
		{
			foreach ( $product_data as $rs )
			{
				$product_option_str .= '<option value="' . $rs ['id'] . '">' . $rs ['product_name'] . '</option>';
			}
		}
		
		$pConfig = new model_product_feedback_approver();
		$needTwice = $pConfig->getSettingByName('isset_twice');
		
		$this->show->assign('userid', $_SESSION['USER_ID']);
		$this->show->assign('product_option', $product_option_str);
		$this->show->assign('need_twice', $needTwice);
		$this->show->display ('bug');
	}
	
	//�б�����
	function c_list_data()
	{	
		$condition = '';
		$typeid = $_GET['typeid'] ? $_GET['typeid'] : $_POST['typeid'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		
		$status = '';
		if(isset($_GET['status']) || isset($_POST['status'])){
			$status = $_REQUEST['status'];
		}
		
		$endDate = $_GET['edate'] ? $_GET['edate'] : $_POST['edate'];
		$startDate = $_GET['sdate'] ? $_GET['sdate'] : $_POST['sdate'];
		
		$bugId = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$pmsBugId = $_GET['pms_bug_id'] ? $_GET['pms_bug_id'] : $_POST['pms_bug_id'];
		$deadline = $_GET['deadline'] ? $_GET['deadline'] : $_POST['deadline'];
		$resolved_date = $_GET['resolved_date'] ? $_GET['resolved_date'] : $_POST['resolved_date'];
		
		$condition .= $typeid ? $condition == "" ? " a.product_id = $typeid" : " and a.product_id = $typeid" : '';
		$condition .= $keyword ? $condition == "" ? " a.title like '%$keyword%' " : " and a.title like '%$keyword%' " : '';
		$condition .= $status != "" ? $condition == "" ? " a.status = '$status'" : " and a.status = '$status'" : '';
		
		
		$condition .= $startDate ? $condition == "" ? " a.date>='".strtotime($startDate.' 00:00:00')."'" : " and a.date>='".strtotime($startDate.' 00:00:00')."'" : '';
		$condition .= $endDate ? $condition == "" ? " a.date<='".strtotime($endDate.' 23:59:59')."' " : " and a.date<='".strtotime($endDate.' 23:59:59')."'" : '';
		
		$condition .= $bugId ? $condition == "" ? " a.id = $bugId" : " and a.id = $bugId" : '';
		$condition .= $pmsBugId ? $condition == "" ? " a.pms_bug_id = $pmsBugId" : " and a.pms_bug_id = $pmsBugId" : '';
		$condition .= $deadline ? $condition == "" ? " a.deadline = '$deadline'" : " and a.deadline = '$deadline'" : '';
		$condition .= $resolved_date ? $condition == "" ? " a.resolved_date like '$resolved_date%'" : " and a.resolved_date like '$resolved_date%'" : '';
		
		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows'], true );
		
		$json = array (
					'total' => $this->num 
		);
		if ($data){
			$json ['rows'] = un_iconv ( $data );
		} else{
			$json ['rows'] = array ();
		}
		echo json_encode ( $json );
	}
	
	//��������
	function c_save()
	{
		
		$this->saveUploadFile();
		$msg = '';
		$_POST['update_time'] = time();
		
		if($_POST ['id']){
			//�޸�
			if($this->Edit ( $_POST, $_POST ['id'] )){
				$msg = 1;
			}else{
				$msg = - 1;
			}
		}else{
			//����
			$_POST['userid'] = $_SESSION['USER_ID'];
			$_POST ['date'] = time ();
			if ($this->Add ( $_POST )){
				
				$productInfo = $this->getProductInfo($_POST['product_id']);
				$user_id_list = $productInfo['feedback_owner'];
				$user_id_list .= $productInfo['feedback_assistant'] ? ','.$productInfo['feedback_assistant']: '';

				if ($user_id_list){
					
					$address = $this->getAddressList($user_id_list);
					if ($address){
						$email = new includes_class_sendmail();
						
						$body .='������Ʒ��'.$productInfo['product_name'].'<br />';
						$body .='��Ʒ�汾�ţ�'.$_POST['version'].'<br />';
						$body .='�Ƿ��и���:'.($_POST ['file_str'] ? '��' : '��').'<br />';
						$body .='<hr />��ƷBug������<br />';
						$body .= preg_replace('/(<img.+src=\")(.+?attachment\/)(.+\.(jpg|gif|bmp|bnp|png)\"?.+>)/i',"\${1}".WEB_URL."/attachment/\${3}",stripslashes($_POST['description']));
						$body .=oaurlinfo;
						$email->send ( $_SESSION['USERNAME'] . '�ύ�˲�ƷBug', $body, $address );
					}
				}
				$msg = 1;
			} else{
				$msg = - 1;
			}
		}
		echo '<script>parent.save_status("' . $msg . '");</script>';
	}
	
	/**
	 * ɾ������
	 */
	function c_delfile()
	{
		$id = isset($_POST['id']) ? intval($_POST['id']) : intval($_GET['id']);
		$filename = isset($_POST['filename']) ? trim($_POST['filename']) : $_GET['filename'];
		$rs = $this->GetOneInfo('id='.$id);
		if ($rs['file_str'])
		{
			$file_arr = explode(',',$rs['file_str']);
			$tmp = array();
			$delfiname = '';
			foreach ($file_arr as $val)
			{
				if (un_iconv($val)==$filename)
				{
					$delfiname = un_iconv($val);
				}else{
					$tmp[] = un_iconv($val);
				}
			}
			if ($delfiname!='')
			{
				$file = 'upfile/product/bug/'.$rs['file_dir'].'/'.$delfiname;
				if (file_exists($file))
				{
					@unlink($file);
				}
			}
			$tmp = mb_iconv($tmp) ? implode(',',mb_iconv($tmp)) : '';
			
			if ( $this->Edit(array('file_str'=>$tmp),$id))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			echo -2;
		}
	}
	
	/**
	 * ���������
	 */
	function c_first_trial(){
		$isSucceed = -1;
		if($_POST){
			if($_POST['status'] == -1){
				unset($_POST['severity']);
				unset($_POST['pri']);
				unset($_POST['error_type']);
			}
			$data = mb_iconv($_POST);
			
			if($this->Edit($data, $data['id'])){
				$rs = $this->getProductBugAndProductInfo($data['id']);
				
				$address = array();
				if($rs['status'] == -1){
					$address = $this->failBackAddressList($rs['userid'], $rs['email']);
				}else{
					$user_id_list = $rs['manager'];
					$user_id_list .= $rs['assistant'] ? ','.$rs['assistant'] : '';
					$address = $this->getAddressList($user_id_list);
				}
				
				$mailList = $this->mailNoteForStatusChange($rs);
				$Email = new includes_class_sendmail();
				$Email->send ($mailList['title'], $mailList['body'], $address);
				$isSucceed = 1;
			}
		}
		
		echo $isSucceed;
	}
	
	/**
	 * ������˽��
	 */
	function c_audit(){
		$isSucceed = -1;
		if($_POST){
			$data = mb_iconv($_POST);
			$id = $data['id'];
			unset($data['id']);
			if($this->Edit($data, $id)){
				
				$rs = $this->getProductBugAndProductInfo($id);
				
				$flag = true;
				
				//��˺�ͬ��PMS,����Ϊstatus������-1
				if($rs['status'] != -1){
					$flag = $this->synchronization_data($rs);
				}
				
				if($flag){
					
					if($rs['status'] != -1){
						$user_id_list = $rs['manager'];
						$user_id_list .= $rs['assistant'] ? ','.$rs['assistant'] : '';
						$address = $this->getAddressList($user_id_list);
					}else{
						$address = $this->failBackAddressList($rs['userid'], $rs['email']);
					}
					
					$mailList = $this->mailNoteForStatusChange($rs);
					$Email = new includes_class_sendmail();
					$Email->send ($mailList['title'], $mailList['body'], $address);
					$isSucceed = 1;
				}
			}
		}
		echo $isSucceed;
	}
	
	/**
	 * �����ѷ�����Ϣ
	 */
	function c_set_feedback(){
		if($this->Edit($_POST, $_POST['id'])){
			echo 1;
		}else{
			echo -1;
		}
	}
	
	/**
	 * �����ѽ����Ϣ
	 */
	function c_save_resolved(){
		$_POST = mb_iconv($_POST);
		if($this->Edit($_POST, $_POST['id'])){
			echo 1;
		}else{
			echo -1;
		}
	}
	
}
?>