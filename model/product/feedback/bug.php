<?php
class model_product_feedback_bug extends model_base{
	
	private $ftp;
	private $ftp_host;
	private $ftp_port;
	private $ftp_pwd;
	private $ftp_user;
	
	function __construct(){
		parent::__construct();
		$this->tbl_name = 'product_bug';
		
		//FTP连接配置
		$this->ftp_host = '172.16.1.102';
		$this->ftp_port = '21';
		$this->ftp_user = 'zentao';
		$this->ftp_pwd  = 'zentao';
		
	}
	
	
	/**
	 * 列表数据
	 * @see model_base::GetDataList()
	 */
	function GetDataList($condition=null,$page,$rows)
	{
		if ($page && $rows && !$this->num)
		{
			$rs = $this->get_one("select count(0) as num from $this->tbl_name ".($condition ? "where ".str_replace('a.', '', $condition) : ''));
			$this->num = $rs['num'];
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page-1)*$pagenum) : $this->start;
			$limit = $page &&  $rows ? $start . ',' . $pagenum : '';
		}
		$query = $this->query("
								select
									a.*,b.product_name,c.user_name,b.feedback_owner,b.feedback_assistant,b.manager,b.assistant
								from
									$this->tbl_name as a
									left join product as b on b.id=a.product_id
									left join user as c on c.user_id=a.userid
									".($condition ? " where ".$condition : "")."
									order by a.id desc
									" . ($limit ? "limit " . $limit : '') . "
		");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$rs['date'] = date('Y-m-d',$rs['date']);
			$data[] = $rs;
		}
		return $data;
	}
	
	//获取产品BUG与产品详细信息
	function getProductBugAndProductInfo($id){
		$SQL = "
			SELECT 
				a.product_id as product_id, 
				a.id as id, 
				a.version as version, 
				a.title as title, 
				a.description as description, 
				a.userid as userid, 
				a.email as contact_email, 
				a.contact as contact_name,
				a.error_type as error_type,
				a.pri as pri, 
				a.severity as severity,
				a.file_str as file_str,
				a.upfile_dir as upfile_dir,
				a.deadline as deadline,
				a.resolved_version as resolved_version,
				a.resolved_note as resolved_note,
				a.resolved_by as resolved_by,
				a.resolution as resolution,
				a.resolved_date as resolved_date,
				a.activated_count as activated_count,
				a.status as status,
				b.manager as manager, 
				b.assistant as assistant, 
				b.feedback_owner as feedback_owner, 
				b.feedback_assistant as feedback_assistant,
				b.product_name as product_name
			FROM 
				product_bug AS a, 
				product AS b 
			WHERE 
				a.product_id = b.id 
			AND 
				a.id = ".$id."
		";
		return array_pop($this->_db->getArray($SQL));
	}
	
	//获取人员详细信息
	function getCurrentUserInfo($userid){
		$SQL = "
			SELECT  
				* 
			FROM 
				user
			WHERE 
				`USER_ID` = '".$userid."'
		";
		return array_pop($this->_db->getArray($SQL));
	}
	
	//获取产品详细信息
	function getProductInfo($id){
		$product_obj = new model_product_list();
		return $product_obj->GetOneInfo('id='.$id);
	}

	//设置路径名称
	function setPathName($fileID, $extension){
        $sessionID  = session_id();
        $randString = substr($sessionID, mt_rand(0, strlen($sessionID) - 5), 3);
        return date('dHis') . $fileID . mt_rand(0, 10000) . $randString . '.' . $extension;
    }
    
	/**
	 * 获取 upfile 字段
	 * return array
	 */
	function getUploadFile($id){
		$result = $this->getProductBugAndProductInfo($id);
		return spliti(',', $result['file_str']);
	}
	
	/**
	 * 获取 uploadPath 字段
	 */
	function getUploadPath($id){
		$result = $this->getProductBugAndProductInfo($id);
		return $result['upfile_dir'];
	}
	
	/**
	 * 保存上传文件
	 */
	function saveUploadFile(){
		$fileArr = array();
		$path = "";
		if ($_FILES ['upfile']['name']){
			if($_POST['id'] != ""){
				$path = $this->getUploadPath($_POST['id']);
			}
			
			if($path != ''){
				$FilePathName = $path;
			}else{
				$FilePathName = md5 ( time () . rand ( 0, 99999 ) );
			}
			if (! is_dir ( WEB_TOR . 'upfile/product/bug/' . $FilePathName )){
					if (! mkdir ( WEB_TOR . 'upfile/product/bug/' . $FilePathName )){
						showmsg ( '上传附件失败，请与管理员联系！！' );
					}
			}
			$_POST ['upfile_dir'] = $FilePathName;
			
			//========保存上传文件开始============
			
			if($_POST['id'] != ''){
				$fileArr = $this->getUploadFile($_POST['id']);
			}
			
			$temp_file_arr = array ();
			$count = 0;
			foreach($_FILES ['upfile'] ['name'] as $key => $value){
				$count++;
				if(!in_array($value, $fileArr)){
					if ($value != ""){
						$name = date('YmdHis');
						$num = strripos($value, '.');
						if($num !== false){
							$suffix = substr($value, $num, 4);
							$name = $name.'-'.$count.$suffix;
							if (move_uploaded_file ( str_replace ( '\\\\', '\\', $_FILES ['upfile'] ['tmp_name'] [$key] ), WEB_TOR . 'upfile/product/bug/' . $FilePathName . '/' . $name )){
								$temp_file_arr [] = $name;
							} else{
								showmsg ( '上传附件失败，请与管理员联系！' );
							}
						}else{
							showmsg ( '上传附件失败，请与管理员联系！' );
						}
					}
				}
			}
			$temp_file_arr = array_merge_recursive($temp_file_arr, $fileArr);
			foreach($temp_file_arr as $key => $value){
				if($value == ''){
					unset($temp_file_arr[$key])	;
				}
			}
			$_POST ['file_str'] = $temp_file_arr ? implode ( ',', $temp_file_arr ) : '';
		}
	}
	
	function getStatus($statusId){
		$status = "被打回";
		if($statusId == 0){
			$status = '待确认';
		}elseif($statusId == 1){
			$status = '已确认';
		}elseif($statusId == 2){
			$status = '已解决';
		}elseif($statusId == 3){
			$status = '已初审';
		}else{
			$status = '被打回';
		}
		return $status;
	}
	
	
	//创建邮件对象
	function sendMail($mailTitle, $mailNote, $address){
		$email = new includes_class_sendmail();
		return $email->send($mailTitle, $mailNote.'详情请登录OA查看！'.oaurlinfo, $address);
	}
	
	
	//获取人员邮件地址
	function getAddress($address){
		$gl = new includes_class_global();
		return $gl->get_email($address);
	}
	
	function getAddressList($data){
		$emailList = array();
		$dataList = explode(",", $data);
		
		foreach ($dataList as $value){
			$result = $this->getCurrentUserInfo($value);
			
			$emailList[$result['EMAIL']] = $result['USER_NAME'];
		}
		return $emailList;
	}
	
	function failBackAddressList($name, $email){
		return $emailList[$email] = $name;
	}
	
	/**
	 * 获取本程序已上传的文件数据
	 * Enter description here ...
	 * @param unknown_type $fileName 文件名
	 * @param unknown_type $filePath 文件路径
	 */
	function getFileInfo($fileName, $filePath){
		
		$fileArr = array();
		$postFileInfo = '';
		$url = "/upfile/product/bug/";
		
		//设置文件夹
		$pms_folder_name = date('Ym');
		//获取FTP连接
		$this->getFtpConnect();
		
		if($filePath != "" || $fileName != ""){
			$fileArr = spliti(',', $fileName);
			$info = array();
			if(count($fileArr) != 0){
				foreach($fileArr as $key => $value){
					if($value != ""){
						$fullUrl = $url.$filePath."/".$value;
						//获取OA文件信息
						$info = pathinfo($fullUrl);
						//生成PMS对应的文件路径名称
						$pathName = $this->setPathName(0, $info['extension']);
						$postFileInfo .= 'title=='.$info['filename'].
										'|||extension=='.$info['extension'].
										'|||size==0'.
										//'|||pathname=='.$pms_folder_name.'/'.$info['basename'].
										'|||pathname=='.$pms_folder_name.'/'.$pathName.
										'%%%';
						
						//上传文件至PMS FTP
						$this->uploadFile($filePath.'\\'.$info['basename'], $pathName, $pms_folder_name);
					}
				}
			}
		}
		
		//关闭FTP连接
		$this->ftp->close();
		return substr($postFileInfo, 0, strlen($postFileInfo)-3);
	}
	
	//获取FTP连接
	private function getFtpConnect(){
		$this->ftp = new includes_class_ftp($this->ftp_host,$this->ftp_port,$this->ftp_user,$this->ftp_pwd);
	}
	
	//上传文件至PMS
	private function uploadFile($filePath, $ftp_file_name, $ftp_file_path){
		$ftpPath = '/www/data/upload/1/'.$ftp_file_path.'/'.$ftp_file_name;
		$path = WEB_TOR.'upfile/product/bug/'.$filePath;
		if(!$this->ftp->up_file($path,$ftpPath)){
			$this->ftp->close();
			showmsg('上传附件失败,请与OA管理员联系！');
		}else{
			$this->ftp->close();
		}
	}
	
	//邮件内容，状态改变
	public function mailNoteForStatusChange($data){
		
		$status = $this->getStatus($data['status']);
		$note = '';			
		$note .='所属产品：'.$data['product_name'].'<br />';
		$note .='产品版本号：'.$data['version'].'<br />';
		$note .='是否有附件:'.($data ['file_str'] ? '有' : '无').'<br />';
		$note .='<hr />产品Bug审批：'.$status.'<br />';
		$note .= preg_replace('/(<img.+src=\")(.+?attachment\/)(.+\.(jpg|gif|bmp|bnp|png)\"?.+>)/i',"\${1}".WEB_URL."/attachment/\${3}",stripslashes($_POST['description']));
		
		return array(
			'title' => 'bug反馈 - #序号: '.$data['id'].' 《'.$data['title'].'》 状态已更新',
			'body' => $note
		);
		
	}
	
	public function mailNoteForSubmit($data){
		
		$note = '';
		$note .='所属产品：'.$data['product_name'].'<br />';
		$note .='产品版本号：'.$data['version'].'<br />';
		$note .='是否有附件:'.($data ['file_str'] ? '有' : '无').'<br />';
		$note .='<hr />产品Bug描述：<br />';
		$note .= preg_replace('/(<img.+src=\")(.+?attachment\/)(.+\.(jpg|gif|bmp|bnp|png)\"?.+>)/i',"\${1}".WEB_URL."/attachment/\${3}",stripslashes($data['description']));
		$note .=oaurlinfo;
		return array(
			'title' => $_SESSION['USERNAME'] . '提交了产品Bug',
			'body' => $note
		);
	}
	
	/**
	 * 保存审核结果
	 */
	function synchronization_data($rs){
		
		$isSucceed = true;
		//组合上传文件信息
		$fileInfo = $this->getFileInfo($rs['file_str'], $rs['upfile_dir']);

		$bugInfo = array(
			'company' => 1,
			'product' => $rs['product_id'],
			'title' => $rs['title'],
			'steps' => $rs['description'],
			'openedBuild' => $rs['version'],
			'confirmed' => 1,
			'pri' => $rs['pri'],
			'severity' => $rs['severity'],
			'type' => $rs['error_type'],
			'deadline' => $rs['deadline'],
			'files' => $fileInfo
		);
		
		//同步至PMS，当数据同步失败后返回-1
		$user = $this->getCurrentUserInfo($_SESSION['USER_ID']);
		$pms = new api_pms($_SESSION['USER_ID'], $user['PASSWORD']);
		$result = $pms->GetModule('bug', 'processBugForOaData', un_iconv($bugInfo), 'post');
		
		if($result->status == 'success'){
			$pms_bug_id = str_replace('"', '', $result->data);
			$feebackData =array(
						'pms_bug_id' => (int)$pms_bug_id
			);
			
			//保存PMS返回的ID
			if(!$this->Edit($feebackData, $rs['id'])){
				$isSucceed = false;
			}
		}else{
			$isSucceed = false;
		}
		
		return $isSucceed;
	}
}

?>