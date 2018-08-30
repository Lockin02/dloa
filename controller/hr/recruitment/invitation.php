<?php
/**
 * @author Administrator
 * @Date 2012年7月18日 星期三 15:18:37
 * @version 1.0
 * @description:面试通知控制层
 */
class controller_hr_recruitment_invitation extends controller_base_action {

	function __construct() {
		$this -> objName = "invitation";
		$this -> objPath = "hr_recruitment";
		parent::__construct();
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_page() {
		$this -> view('list');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_myPage() {
		$this -> assign("userid", $_SESSION['USER_ID']);
		$this -> view('mylist');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_noPage() {
		$this -> view('nopage');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_passedPage() {
		$this -> view('passpage');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_nowPage() {
		$this -> view('nowpage');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_tabPage() {
		$this -> view('tablist');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_mynopage() {
		$this -> assign("linkid", $_SESSION['USER_ID']);
		$this -> view('mynopage');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_myPassedPage() {
		$this -> assign("linkid", $_SESSION['USER_ID']);
		$this -> view('mypasspage');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_myNowPage() {
		$this -> assign("linkid", $_SESSION['USER_ID']);
		$this -> view('mynowpage');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_myTabPage() {
		$this -> assign("userid", $_SESSION['USER_ID']);
		$this -> view('mytablist');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_interTabPage() {
		$this -> assign("userid", $_SESSION['USER_ID']);
		$this -> view('intertablist');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_interNopage() {
		$this -> assign("linkid", $_SESSION['USER_ID']);
		$this -> view('internopage');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_interPassedPage() {
		$this -> assign("linkid", $_SESSION['USER_ID']);
		$this -> view('interpasspage');
	}

	/**
	 * 跳转到面试通知列表
	 */
	function c_interNowPage() {
		$this -> assign("linkid", $_SESSION['USER_ID']);
		$this -> view('internowpage');
	}
	/**
	 * 跳转到新增面试通知页面
	 */
	function c_toAdd() {
		$resume = new model_hr_recruitment_resume();
		$obj1 = $resume -> get_d($_GET['resumeid']);
		foreach ($obj1 as $key => $val) {
			$this -> assign($key, $val);
		}
		$this -> assign("toMail", $obj1["email"]);
		$this -> assign("resumeId", $obj1['id']);

		$this -> assign("user", $_SESSION["USERNAME"]);
		$this -> assign("userId", $_SESSION["USER_ID"]);

		$this -> showDatadicts(array('postType' => 'YPZW'));
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getByCode($_SESSION['Company']);
		$this->assign('sysCompanyId', $branchArr['ID']);
		$this->assign('sysCompanyName', $branchArr['NameCN']);
		$area = new includes_class_global();
		$this->show->assign('areaOpt',$area->area_select());  //将所有区域添加到select标签

		$this -> view('add' ,true);
	}

	/**
	 * 跳转到新增面试通知页面
	 */
	function c_toapplyAdd() {
		$this -> permCheck();
		//安全校验
		$resume = new model_hr_recruitment_resume();
		$obj1 = $resume -> get_d($_GET['resumeid']);
		$apply = new model_hr_recruitment_apply();
		$obj2 = $apply -> get_d($_GET['applyid']);
		$this -> assign("parentCode", $obj2['formCode']);
		$this -> assign("parentId", $obj2['id']);
		$this -> assign("interviewType", 1);
		$this -> assign("interviewTypeName", "增员申请");
		foreach ($obj2 as $key => $val) {
			$this -> assign($key, $val);
		}
		foreach ($obj1 as $key => $val) {
			$this -> assign($key, $val);
		}
		$this -> showDatadicts(array('postType' => 'YPZW'), $obj2['postType']);
		$this -> assign("toMail", $obj1["email"]);
		$this -> assign("resumeId", $obj1['id']);

		$this -> assign("user", $_SESSION["USERNAME"]);
		$this -> assign("userId", $_SESSION["USER_ID"]);

		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('sysCompanyId', $branchArr['ID']);
		$this->assign('sysCompanyName', $branchArr['NameCN']);
		$area = new includes_class_global();
		$this->show->assign('areaOpt',$area->area_select());  //将所有区域添加到select标签


		$this -> assign("applyid", $_GET["id"]);
		$this -> assign("applyResumeId", $_GET["id"]);
		$this -> view('applyadd' ,true);
	}

	function c_torecomAdd() {
		$this -> permCheck();
		//安全校验
		$resume = new model_hr_recruitment_resume();
		$obj1 = $resume -> get_d($_GET['resumeid']);
		$apply = new model_hr_recruitment_recommend();
		$obj2 = $apply -> get_d($_GET['applyid']);
		$this -> assign("parentCode", $obj2['formCode']);
		$this -> assign("parentId", $obj2['id']);
		$this -> assign("resumeToName", "");
		$this -> assign("interviewType", 2);
		$this -> assign("interviewTypeName", "内部推荐");
		foreach ($obj1 as $key => $val) {
			$this -> assign($key, $val);
		}
		$this -> assign("toMail", $obj1["email"]);
		$this -> assign("resumeId", $obj1['id']);

		$this -> showDatadicts(array('postType' => 'YPZW'),$obj1['post']);
		$this -> assign("user", $_SESSION["USERNAME"]);
		$this -> assign("userId", $_SESSION["USER_ID"]);

		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getByCode($_SESSION['Company']);
		$this->assign('sysCompanyId', $branchArr['ID']);
		$this->assign('sysCompanyName', $branchArr['NameCN']);
		$area = new includes_class_global();
		$this->show->assign('areaOpt',$area->area_select());  //将所有区域添加到select标签

		$this -> assign("recomid", $_GET["id"]);
		$this -> assign("applyResumeId", $_GET["id"]);
		$this -> view('recomadd' ,true);
	}

	/**
	 * 跳转到编辑面试通知页面
	 */
	function c_toEdit() {
		$this -> permCheck();
		//安全校验
		$obj = $this -> service -> get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this -> assign($key, $val);
		}
		$this -> showDatadicts(array('postType' => 'YPZW'), $obj['postType']);
		$this -> view('edit' ,true);
	}

	/**
	 * 跳转到面试通知页面
	 */
	function c_applyPage() {
		$this -> assign("interviewType", $_GET['interviewType']);
		$this -> assign("applyid", $_GET['applyid']);
		$this -> view('applylist' ,true);
	}


	/**
	 * 获取面试通知列表信息
	 */
	function c_pageJson() {
		$service = $this -> service;

		$service -> getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		$interviewer = new model_hr_recruitment_interviewer();
		$intercomment = new model_hr_recruitment_interviewComment();
		//$service->asc = false;
		$rows = $service -> page_d();
		$getrows = $rows;
		for ($i = 0; $i < count($rows); $i++) {
			//获取评论标志
				$comment = $intercomment -> find(array("invitationId" => $rows[$i]['id'], "interviewerId" => $_SESSION['USER_ID']));
				if ($comment) {
					//var_dump($comment);
					$rows[$i]['sign'] = 1;
					$rows[$i]['userWrite'] = $comment['useWriteEva'];
					$rows[$i]['interview'] = $comment['interviewEva'];
					$rows[$i]['commentid'] = $comment['id'];
				} else
					$rows[$i]['sign'] = 0;
			//获取面试官
//			$object = $interviewer -> findAll(array("parentId" => $rows[$i]['id']));
//			$namestr = '';
//			for ($j = 0; $j < count($object); $j++) {
//				$namestr .= ($object[$j]['interviewerName']);
//			}
//			$rows[$i]['interviewerName'] = $namestr;
		}
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				//转换成中文
				$rows[$key]['stateC'] = $service -> statusDao -> statusKtoC($rows[$key]['state']);
			}
		}

		//数据加入安全码
		$rows = $this -> sconfig -> md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service -> count ? $service -> count : ($getrows ? count($getrows) : 0);
		$arr['page'] = $service -> page;
		$arr['advSql'] = $service -> advSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 获取期内的面试通知列表信息
	 */
	function c_commentPageJson() {
		$service = $this -> service;
		$service -> getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		$interviewer = new model_hr_recruitment_interviewer();
		$intercomment = new model_hr_recruitment_interviewComment();
		//$service->asc = false;
		$rows = $service -> page_d();
		$getrows = $rows;
		for ($i = 0; $i < count($rows); $i++) {
			$comment1 = array();
			$comment2 = array();
			$interviewerNum = 0;
			$hrInterviewerNum = 0;
			$HrInterviewerCount = 1;
			$interviewerCount = 1;
			//获取评论标志
			$hrInterviewerIdArr = strpos($rows[$i]['hrInterviewerId'],",");
			$interviewerIdArr = strpos($rows[$i]['interviewerId'],",");
			//判断部门面试官ID 是否为数组
			if($hrInterviewerIdArr){
				$arrHrInterviewerId = explode(",",$rows[$i]['hrInterviewerId']);	//合成数组
				$HrInterviewerCount = count($arrHrInterviewerId);					//计算数组个数
				foreach($arrHrInterviewerId as $k => $v){
					$data1 = $intercomment -> find(array("invitationId" => $rows[$i]['id'], "interviewerId" => $v));
					$comment1 = array_merge($comment1,$data1);
					if(!empty($data1)){
					$hrInterviewerNum = $hrInterviewerNum+1;
					}
				}
			}
			else{
			$comment1 = $intercomment -> find(array("invitationId" => $rows[$i]['id'], "interviewerId" => $rows[$i]['hrInterviewerId']));
			}
			//判断人力资源面试官ID 是否为数组
			if($interviewerIdArr){
				$arrInterviewerId = explode(",",$rows[$i]['interviewerId']);
				$interviewerCount = count($arrInterviewerId);
				foreach($arrInterviewerId as $k=>$v){
					$data2 = $intercomment -> find(array("invitationId" => $rows[$i]['id'], "interviewerId" => $v));
					$comment2 = array_merge($comment2,$data2);
					if(!empty($data2)){
					$interviewerNum = $interviewerNum+1;
					}
				}
			}
			else{
			$comment2 = $intercomment -> find(array("invitationId" => $rows[$i]['id'], "interviewerId" => $rows[$i]['interviewerId']));
			}
			if($interviewerNum==0 && $hrInterviewerNum==0){
				if($comment1&&$comment2){
					$rows[$i]['addsign'] = 0;
				}else
					$rows[$i]['addsign'] = 1;
			}
			else if($interviewerNum==0 || $hrInterviewerNum==0){

				if(($comment1&&$interviewerNum == $interviewerCount)||($comment2&&$hrInterviewerNum == $HrInterviewerCount)){
					$rows[$i]['addsign'] = 0;
				}else
					$rows[$i]['addsign'] = 1;
			}
			else {
				if(($interviewerNum == $interviewerCount)&&($hrInterviewerNum == $HrInterviewerCount)){
					$rows[$i]['addsign'] = 0;
				}else
				$rows[$i]['addsign'] = 1;
			}
			if ($comment1||$comment2) {
				//var_dump($comment);
				$rows[$i]['editsign'] = 1;
//				$rows[$i]['userWrite'] = $comment1['useWriteEva'];
//				$rows[$i]['interview'] = $comment1['interviewEva'];
//				$rows[$i]['commentid'] = $comment1['id'];
			} else
				$rows[$i]['editsign'] = 0;
		}
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				//转换成中文
				$rows[$key]['stateC'] = $service -> statusDao -> statusKtoC($rows[$key]['state']);
			}
		}
		//var_dump($rows);
		//数据加入安全码
		$rows = $this -> sconfig -> md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service -> count ? $service -> count : ($getrows ? count($getrows) : 0);
		$arr['page'] = $service -> page;
		$arr['advSql'] = $service -> advSql;
		echo util_jsonUtil::encode($arr);
	}


	/**
	 * 新增对象操作
	 */
	function c_recomAdd($isAddInfo = true) {
		$this->checkSubmit(); //检查是否重复提交
		$obj = $_POST[$this -> objName];
		//禁止自动转义
		$obj['content'] = stripslashes(htmlspecialchars_decode($_POST["interMail"]['content']));
		//消除空行
		$obj['content'] = str_replace("<p>","",$obj['content']);
		$obj['content'] = str_replace("</p>","<br>",$obj['content']);
		$id = $this -> service -> add_d($obj, $isAddInfo);
		$msg = $_POST["msg"] ? $_POST["msg"] : '添加面试通知成功！';
		$mailinfo = $_POST["interMail"];
		//禁止自动转义
		$mailinfo['content'] = stripslashes(htmlspecialchars_decode($_POST["interMail"]['content']));
		//消除空行
		$mailinfo['content'] = str_replace("<p>","",$mailinfo['content']);
		$mailinfo['content'] = str_replace("</p>","<br>",$mailinfo['content']);
		if ($id) {
			$recom = new model_hr_recruitment_recomResume();
			//var_dump($_POST['recomid']);
			$recom -> update(array("id" => $_POST['recomid']), array("state" => $recom -> statusDao -> statusEtoK('interviewing')));
			$this -> service -> thisMail_d($mailinfo);
			msg($msg);
		}

		//$this->listDataDict();
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = true) {
		$this->checkSubmit(); //检查是否重复提交
		$obj = $_POST[$this -> objName];
		//禁止自动转义
		$obj['content'] = stripslashes(htmlspecialchars_decode($_POST["interMail"]['content']));
		//消除空行
		$obj['content'] = str_replace("<p>","",$obj['content']);
		$obj['content'] = str_replace("</p>","<br>",$obj['content']);
		$id = $this -> service -> add_d($obj, $isAddInfo);
		$msg = $_POST["msg"] ? $_POST["msg"] : '添加面试通知成功！';
		$mailinfo = $_POST["interMail"];
		//禁止自动转义
		$mailinfo['content'] = stripslashes(htmlspecialchars_decode($_POST["interMail"]['content']));
		//消除空行
		$mailinfo['content'] = str_replace("<p>","",$mailinfo['content']);
		$mailinfo['content'] = str_replace("</p>","<br>",$mailinfo['content']);
		$mailinfo['isSender'] = 0;

		$uploadFile = new model_file_uploadfile_management ();
		$file = $uploadFile->getFilesByObjId ( $mailinfo['id'], 'oa_hr_invatation_email' );
		$filePath = str_replace('\\','/',UPLOADPATH);
		$destDir = $filePath."oa_hr_invatation_email/";
		if ($file) {
			foreach ($file as $key => $val) {
                $mailinfo['attachment'][$val['uploadPath'].$val['newName']] = $val['originalName'];
			}
		}

		if ($id) {
			$this -> service -> thisMail_d($mailinfo);

			echo "<script>alert('{$msg}');window.close();</script>";
		}

		//$this->listDataDict();
	}

	/**
	 * 新增对象操作
	 */
	function c_applyAdd($isAddInfo = true) {
		$this->checkSubmit(); //检查是否重复提交
		$obj = $_POST[$this->objName];
		//禁止自动转义
		$obj['content'] = stripslashes(htmlspecialchars_decode($_POST["interMail"]['content']));
		//消除空行
		$obj['content'] = str_replace("<p>","",$obj['content']);
		$obj['content'] = str_replace("</p>","<br>",$obj['content']);
		$id = $this->service->add_d($obj, $isAddInfo);

		$msg = $_POST["msg"] ? $_POST["msg"] : '添加面试通知成功！';
		$mailinfo = $_POST["interMail"];
		//禁止自动转义
		$mailinfo['content'] = stripslashes(htmlspecialchars_decode($_POST["interMail"]['content']));
		//消除空行
		$mailinfo['content'] = str_replace("<p>","",$mailinfo['content']);
		$mailinfo['content'] = str_replace("</p>","<br>",$mailinfo['content']);
		$mailinfo['isSender'] = 0;

		$uploadFile = new model_file_uploadfile_management ();
		$file = $uploadFile->getFilesByObjId ( $mailinfo['id'], 'oa_hr_invatation_email' );
		$filePath = str_replace('\\','/',UPLOADPATH);
		$destDir = $filePath."oa_hr_invatation_email/";
		if ($file) {
			foreach ($file as $key => $val) {
				if (file_exists($destDir.$file[$key]['originalName'])) { //如果有重名文件就先删除
					unlink($destDir.$file[$key]['originalName']);
				}
				rename($destDir.$file[$key]['newName'] ,$destDir.$file[$key]['originalName']);
				$mailinfo['attachment'][$key] = $destDir.$file[$key]['originalName'];
			}
		}

		if ($id) {
			if($_POST[$this->objName]['applyResumeId'] > 0){
				$apply = new model_hr_recruitment_applyResume();
				$apply->update(array("id" => $_POST[$this->objName]['applyResumeId']) ,array("state" => $apply->statusDao->statusEtoK('interviewing')));
			}

			if ($obj['resumeId'] > 0) {
				$resumeDao = new model_hr_recruitment_resume();
				$resumeDao->updateById(array('id' => $obj['resumeId'] ,'isInform' => 1)); //更新简历状态为已发送面试通知
			}

			$this->service->thisMail_d($mailinfo);
			if ($mailinfo['attachment']) {
				foreach ($mailinfo['attachment'] as $key => $val) {
					unlink($mailinfo['attachment'][$key]); //发送完成后删除临时文件
				}
			}
			if ($file) {
				foreach ($file as $key => $val) {
					if ($file[$key]['id']) {
						$uploadFile->deletes($file[$key]['id']); //删除数据库的相关记录
					}
				}
			}

			msg($msg);
		}
	}

	/**
	 * 跳转到查看面试通知页面
	 */
	function c_toView() {
		$this -> permCheck();
		//安全校验
		$obj = $this -> service -> get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this -> assign($key, $val);
		}
//		$interviewer = new model_hr_recruitment_interviewer();
//		$object = $interviewer -> findAll(array("parentId" => $obj['id']));
//		$str = '';
//		for ($i = 0; $i < count($object); $i++) {
//			$str .= $object[$i]['interviewerName'];
//		}
		$this -> assign("resumeToName", $str);
		if ($obj ['positionLevel'] =="1") {
			$this->assign ( 'positionLevel', '初级' );
		} else if ($obj ['positionLevel'] == "2") {
			$this->assign ( 'positionLevel', '中级' );
		}else if ($obj ['positionLevel'] == "3") {
			$this->assign ( 'positionLevel', '高级' );
		}
		if ($obj['interviewType'] == 1)
			$this -> assign("interviewTypeName", "增员申请");
		else
			$this -> assign("interviewTypeName", "内部推荐");
		$this -> view('view');
	}

	//add chenrf 20130515
	/**
	 * 发送入职通知
	 */
	function c_sendNotify(){
		$this->permCheck(); //安全校验
		$entryNoticeDao=new model_hr_recruitment_entryNotice();
		$resumeModel=new model_hr_recruitment_resume();
		$resumeRow=$resumeModel->get_d($_GET['resumeId']);
		if($_GET['interviewType']==1){
			$applyModel=new model_hr_recruitment_apply();
			$applyRow=$applyModel->get_d($_GET['applyid']);  //增员申请
			$this->assign("sourceId",$applyRow['id']);
			$this->assign("sourceCode",$applyRow['formCode']);
			$this->assign("addType",$applyRow['addType']); //增员类型
			$this->assign("addTypeCode",$applyRow['addTypeCode']);

		}else{
			$recommendModel=new model_hr_recruitment_recommend();  //内部推荐
			$recommendRow=$recommendModel->get_d($_GET['applyid']);
			$this->assign("sourceId",$recommendRow['id']);
			$this->assign("sourceCode",$recommendRow['formCode']);
			$this->assign("addType",$recommendRow['source']);//增员类型
			$this->assign("addTypeCode",'');//
		}
		$this->assignFunc($resumeRow);
		$this->assign("SignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$resumeRow['post']);//职位类型
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' )); //工资级别
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->findAll();
		$select='';
		if(is_array($branchArr)){
			foreach ($branchArr as $branch) {
				$select .= "<option value=".$branch['ID'].">".$branch['NameCN']."</option>";
			}
		}
		$this->assign("interviewType",$_GET['interviewType']);
		$this->assign("selectName",$select);
		$area = new includes_class_global();
		$this->show->assign('area_select',$area->area_select());
		$this->assign("applyResumeId",$_GET['applyResumeId']);
		$this->showDatadicts ( array ('hrSourceType1Name' => 'HRBCFS' ));
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ));
		$this->assign("userId",$_SESSION['USER_ID']);
		$this->assign("user",$_SESSION['USERNAME']);
		$this->assign("useSignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ));
		$this->assign("managerId",$_SESSION['USER_ID']);
		$this->assign("manager",$_SESSION['USERNAME']);
		$this->assign("SignDate",date('Y-m-d'));
		$this->assign('toccMail',$entryNoticeDao->mailArr['sendName']);
		$this->assign('toccMailId',$entryNoticeDao->mailArr['sendUserId']);
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ));//增员类型

		$interviewDao = new model_hr_recruitment_interview();//关联指定导师
		$interviewObj = $interviewDao -> find(array("resumeId" => $_GET['resumeId']));
		$this->assign("tutor", $interviewObj['tutor']);
		$this->assign("tutorId", $interviewObj['tutorId']);

		$entryNoticeRow = $entryNoticeDao->find("applyResumeId='".$_GET['applyResumeId']."'");
		if($entryNoticeRow){
			 unset($entryNoticeRow['id']);
			 foreach($entryNoticeRow as $key=>$val){
			 	$this->assign($key,$val);
			}
		//获取邮件内容及附件
		$content = $this->service->get_table_fields('oa_hr_recruitment_entrynotice',"applyResumeId='".$_GET['applyResumeId']."'",'content');
			$this->show->assign("file",$this->service->getFilesByObjId($resumeRow['id'],true,'oa_hr_entryNotice_email')); //显示附件信息
			$this->assign('content',$content);
			$this->showDatadicts ( array ('postType' => 'YPZW' ),$entryNoticeRow['postType']);//职位类型
			$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ),$entryNoticeRow['useHireType']);//录用形式
			$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ),$entryNoticeRow['hrSourceType1']);	//简历来源大类
			$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ),$entryNoticeRow['wageLevelCode']); //工资级别
			$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$entryNoticeRow['addTypeCode']);//增员类型
			$this->view("sendNotify-edit");
		} else{
			$this->view("sendNotify-add");
		}
	}
	/**
	 *
	 *检查是否存在面试通知
	 */
	function c_ajaxCheckExistsResume(){
		$resumeId=$_POST['resumeId'];
		if($this->service->ajaxCheckExistsResume($resumeId))
			echo 1;
		else
			echo 0;
	}
}
?>