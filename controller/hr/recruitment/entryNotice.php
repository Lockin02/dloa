<?php
/**
 * @author Administrator
 * @Date 2012年7月27日 星期五 13:22:05
 * @version 1.0
 * @description:入职通知控制层
 */
class controller_hr_recruitment_entryNotice extends controller_base_action {

	function __construct() {
		$this->objName = "entryNotice";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到入职通知列表
	 */
	function c_myPage() {
		$this->assign("userid" ,$_SESSION['USER_ID']);
		$this->view('mytablist');
	}

	/**
	 * 跳转到入职通知列表
	 */
	function c_myWaitPage() {
		$this->assign("userid",$_SESSION['USER_ID']);
		$this->view('mywaitlist');
	}

	/**
	 * 入职通知表--简历查看tab页
	 */
	function c_viewList() {
		$this->assign('resumeId',$_GET['resumeId']);
		$this->view('viewList');
	}

	/**
	 * 跳转到入职通知列表
	 */
	function c_myPassedPage() {
		$this->assign("userid",$_SESSION['USER_ID']);
		$this->view('mypassedlist');
	}

	/**
	 * 跳转到入职通知列表
	 */
	function c_deptPage() {
		$this->view('depttablist');
	}

	/**
	 * 跳转到入职通知列表
	 */
	function c_deptWaitPage() {
		$this->assign("deptId" ,$_SESSION['DEPT_ID']);
		$this->view('deptwaitlist');
	}

	/**
	 * 跳转到入职通知列表
	 */
	function c_deptPassedPage() {
		$this->assign("deptId",$_SESSION['DEPT_ID']);
		$this->view('deptpassedlist');
	}

	/**
	 * 跳转到入职通知列表
	 */
	function c_hrPage() {
		$this->view('hrtablist');
	}

	/**
	 * 跳转到入职通知列表
	 */
	function c_hrWaitPage() {
		$this->view('hrwaitlist');
	}

	/**
	 * 跳转到入职通知列表
	 */
	function c_hrPassedPage() {
		$this->view('hrpassedlist');
	}

	/**
	 * 跳转到入职通知列表
	 */
	function c_newdeptPage() {
		$this->view('newdepttablist');
	}

	/**
	 * 跳转到入职通知列表
	 */
	function c_newdeptWaitPage() {
		$this->assign("deptId",$_SESSION['DEPT_ID']);
		$this->view('newdeptwaitlist');
	}

	/**
	 * 跳转到入职通知列表
	 */
	function c_newdeptPassedPage() {
		$this->assign("deptId",$_SESSION['DEPT_ID']);
		$this->view('newdeptpassedlist');
	}

    /**
     * 列表权限
     */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/**
	 * 跳转到新增入职通知页面
	 */
	function c_toAdd() {
		$this->permCheck (); //安全校验
		$interviewDao = new model_hr_recruitment_interview();
		$obj = $interviewDao->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		if($obj['interviewType'] == 1 || $obj['interviewType'] == 3) { //3是简历发送过来的入职通知
			$this->assign ( 'interviewTypeName', '增员申请' );
		} else {
			$this->assign ( 'interviewTypeName', '内部推荐' );
		}

		//级别
		if($obj['postType'] == 'YPZW-WY') {
			$level = new model_hr_basicinfo_level();
			$WYlevel = $level->get_d($obj['positionLevel']);
			$this->assign('positionLevelName', $WYlevel['personLevel']);
		} else {
			switch ($obj['positionLevel']) {
				case '1' :
					$this->assign('positionLevelName' ,'初级');
					break;
				case '2' :
					$this->assign('positionLevelName' ,'中级');
					break;
				case '3' :
					$this->assign('positionLevelName' ,'高级');
					break;
				default : $this->assign('positionLevelName' ,'');
					break;
			}
		}

		//二级部门、三级部门和四级部门
		$deptDao = new model_deptuser_dept_dept();
		$deptObj = $deptDao->getSuperiorDeptById_d($obj['deptId']);
		$this->assign('deptNameS' ,$deptObj['deptNameS']);
		$this->assign('deptNameT' ,$deptObj['deptNameT']);
		$this->assign('deptNameF' ,$deptObj['deptNameF']);

		//电脑协助人（取需求电脑设备信息反馈的配置收件人）
		$xqdnMailUser = $this->service->getMailUser_d('interviewAddOffer_xqdn');
		$this->assign('xqdnUser' ,$xqdnMailUser['defaultUserName']);

		//获取邮件内容及附件
		$object = $this->service->find(array('parentId' => $obj['id']));
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,true ,'oa_hr_entryNotice_email')); //显示附件信息
		$this->assign('content' ,$object['content']);

		$this->assign('interviewId' ,$obj ['id']);
		$this->assign('interviewCode' ,$obj ['formCode']);
		$this->assign('user' ,$_SESSION['USERNAME']);
		$this->assign('userId' ,$_SESSION['USER_ID']);

		//邮件部分
		$this->assign('toMail' ,$obj['email']);
		switch ($obj['sysCompanyName']) {
			case '广州贝讯' :
				include(WEB_TOR."model/common/mailConfig.php");
				$toccMailId = $mailUser['oa_hr_recruitment_entrynotice_beixun']['sendUserId'];
				$toccMail = $mailUser['oa_hr_recruitment_entrynotice_beixun']['sendName'];
				break;
			default :
				$toccMailId = $this->service->mailArr['sendUserId'];
				$toccMail = $this->service->mailArr['sendName'];
				break;
		}
		//服务线的抄送给服务经理
		if ((($obj['deptId'] >= 161 && $obj['deptId'] <= 168) || $obj['deptId'] == 130)
				&& $obj['postType'] == 'YPZW-WY'
				&& $obj['workProvinceId'] > 0) { //服务线和试点专区、网优类型、省份
			$managerId = $this->service->get_table_fields('oa_esm_office_managerinfo' ,"provinceId='".$obj['workProvinceId']."'" ,'managerId');

			if (!empty($managerId)) {
				$managerName = $this->service->get_table_fields('oa_esm_office_managerinfo' ,"provinceId='".$obj['workProvinceId']."'" ,'managerName');

				$toccMailId .= ','.$managerId;
				$toccMail .= ','.$managerName;
			}
		}
		$title = "$obj[deptName]新员工($obj[userName])入职通知"; //邮件标题
		$this->assign('toccMail' ,$toccMail);
		$this->assign('toccMailId' ,$toccMailId);
		$this->assign('title' ,$title);

		if($object['assistManName']) { //入职协助人
			$this->assign('assistManName' ,$object['assistManName']);
			$this->assign('assistManId' ,$object['assistManId']);
		} else {
			$this->assign('assistManName' ,'');
			$this->assign('assistManId' ,'');
		}
		$this->view ('add' ,true);
	}

	/**
	 * 根据部门id获取部门总监和副总监的信息
	 */
	function c_getDirector($deptId) {
		$otherDao = new model_common_otherdatas();
		$otherObj = $otherDao->getDeptById_d($deptId);

		$userDao = new model_deptuser_user_user();
		$MajorId = $otherObj['MajorId'];
		//判断最后一个字符是否逗号,是的话则出去逗号
		if(substr($MajorId ,-1) == ',') {
			$MajorId = substr($MajorId ,0 ,-1);
		}
		$MajorInfo = $userDao->getUserName_d($MajorId);

		$ViceManager = $otherObj['ViceManager'];
		//判断最后一个字符是否逗号,是的话则出去逗号
		if(substr($ViceManager ,-1) == ',') {
			$ViceManager = substr($ViceManager ,0 ,-1);
		}
		$ViceManagerInfo = $userDao->getUserName_d($ViceManager);

		$info = array('directorId' => $otherObj['MajorId'].$otherObj['ViceManager'],
					'directorName' => $MajorInfo['USER_NAME'].','.$ViceManagerInfo['USER_NAME']);
		return $info;
	}

	/**
	 * 跳转到编辑入职通知页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view ('edit' ,true);
	}

	/**
	 * 跳转到入职进度备注页面
	 */
	function c_toEntryRemark() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view ('entryremark' ,true);
	}

	/**
	 * 跳转到入职关联职位申请表页面
	 */
	function c_toLinkApply() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view ('linkapply' ,true);
	}

	/**
	 * @author Administrator
	 *
	 */
	function c_changeEntryDate(){
		$this->checkSubmit(); //检查是否重复提交
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view ('change-edit');
	}

	/**
	 * 邮件通知
	 */
	function c_toCompanyEmail(){
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}

		$this->assign('probation' ,$obj['probation'] ? $obj['probation'] : 0); //试用期
		$this->assign('socialPlace' ,$obj['socialPlace'] ? $obj['socialPlace'] : '不购买'); //社保购买地

		//级别
		if($obj['postType'] == 'YPZW-WY') {
			$level = new model_hr_basicinfo_level();
			$WYlevel = $level->get_d($obj['positionLevel']);
			$this->assign('positionLevelName', $WYlevel['personLevel']);
		} else {
			switch ($obj['positionLevel']) {
				case '1' :
					$this->assign('positionLevelName' ,'初级');
					break;
				case '2' :
					$this->assign('positionLevelName' ,'中级');
					break;
				case '3' :
					$this->assign('positionLevelName' ,'高级');
					break;
				default : $this->assign('positionLevelName' ,'');
					break;
			}
		}

		//二级部门、三级部门和四级部门
		$deptDao = new model_deptuser_dept_dept();
		$deptObj = $deptDao->getSuperiorDeptById_d($obj['deptId']);
		$this->assign('deptNameS' ,$deptObj['deptNameS']);
		$this->assign('deptNameT' ,$deptObj['deptNameT']);
		$this->assign('deptNameF' ,$deptObj['deptNameF']);

		//电脑协助人（取需求电脑设备信息反馈的配置收件人）
		$xqdnMailUser = $this->service->getMailUser_d('interviewAddOffer_xqdn');
		$this->assign('xqdnUser' ,$xqdnMailUser['defaultUserName']);

		//邮件部分
		$toccMailId = $this->service->mailArr['sendUserId'];
		$toccMail = $this->service->mailArr['sendName'];
		$this->assign('toccMail' ,$toccMail);
		$this->assign('toccMailId' ,$toccMailId);
		$this->assign('title' ,"$obj[deptName]新员工($obj[userName])入职通知");
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,true ,'oa_hr_entryNotice_email')); //显示附件信息
		$this->view ('compmail' ,true);
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		$obj = $_POST [$this->objName];
		$obj['state'] = $this->service->statusDao->statusEtoK ( 'save' ); //初始化为保存状态
		$datadict = new model_system_datadict_datadict();
		$obj['addType'] = $datadict->getDataNameByCode($obj['addTypeCode']);
		$obj['wageLevelName'] = $datadict->getDataNameByCode($obj['wageLevelCode']);
		//禁止自动转义
		$obj['content'] = stripslashes(htmlspecialchars_decode($_POST["interMail"]['content']));
		//消除空行
		$obj['content'] = str_replace("<p>","",$obj['content']);
		$obj['content'] = str_replace("</p>","",$obj['content']);
		if($obj['parentId'] > 0) { //判断是否是从面试评估或录用通知处发送录用通知
			$entryNoticeId = $this->service->get_table_fields('oa_hr_recruitment_entrynotice',"parentId='".$obj['parentId']."'",'id');
			if($entryNoticeId){
				$obj['id'] = $entryNoticeId;
			}
		} else if ($obj['applyResumeId'] > 0) { //判断是否是从增员申请发送录用通知
			$entryNoticeId = $this->service->get_table_fields('oa_hr_recruitment_entrynotice',"applyResumeId='".$obj['applyResumeId']."'",'id');
			if($entryNoticeId){
				$obj['id'] = $entryNoticeId;
			}
		} else if ($obj['resumeId'] > 0) { //判断是否是从简历管理发送录用通知
			$entryNoticeId = $this->service->get_table_fields('oa_hr_recruitment_entrynotice',"resumeId='".$obj['resumeId']."'",'id');
			if($entryNoticeId){
				$obj['id'] = $entryNoticeId;
			}
		}

		if($obj['id']) { //进入编辑
			$obj['isSave'] = '1'; //设置为保存状态
			$editId = $this->service->edit_d($obj ,$isAddInfo);
		} else { //进行添加操作
			$obj['isSave'] = '1'; //设置为保存状态
			$id = $this->service->add_d ($obj, $isAddInfo);
		}

		if($_GET['isSave'] != 1) { //进行发送邮件操作
			$mailinfo = $_POST["interMail"];
			//禁止自动转义
			$mailinfo['content'] = stripslashes(htmlspecialchars_decode($_POST["interMail"]['content']));
			//消除空行
			$mailinfo['content'] = str_replace("<p>","",$mailinfo['content']);
			$mailinfo['content'] = str_replace("</p>","<br>",$mailinfo['content']);
			$mailinfo['isSender'] = 1;

			$uploadFile = new model_file_uploadfile_management ();
			$file = $uploadFile->getFilesByObjId ( $mailinfo['id'], 'oa_hr_entryNotice_email' );
			if ($file) {
				foreach ($file as $key => $val) {
                    $mailinfo['attachment'][$val['uploadPath'].$val['newName']] = $val['originalName'];
				}
			}

			$this->service->postEmail_d($_POST[$this->objName] ,$mailinfo);


			if(!$obj['id']) {
				$obj['id'] = $id;
			}
			$this->service->updateById(array('id' => $obj['id'] ,'isSave' => 0)); //邮件发送后，取消保存状态
		}

		if ($id) {
			if ($_GET['isSave'] != 1) {
				msg ( '发送成功！' );
			} else {
				msg ( '保存成功' );
			}
		} else if($editId) {
			if ($_GET['isSave'] != 1) {
				msg ( '发送成功！' );
			} else {
				msg ( '保存成功' );
			}
		} else {
			msg('操作失败');
		}
	}

	/**
	 * 修改对象操作
	 */
	function c_editDate($isAddInfo = false) {
		$this->checkSubmit(); //检查是否重复提交

		$object = $_POST [$this->objName];
		if(!empty($object['email']['TO_ID'])) {
			$this->service->postDateEmail_d($object);
		}
		unset($object['email']);

		if($this->service->updateById($object)){
			msg("修改时间成功");
		}
	}

	/**
	 * 修改对象操作
	 */
	function c_compEmail() {
		$this->checkSubmit(); //检查是否重复提交
		$object = $_POST[$this->objName];

		$rs = true;
		if ($object['oldEntryDate'] != $object['entryDate']) { //修改了入职时间
			$rs = $this->service->updateById($object);
		}

		$mailinfo = $_POST["interMail"];
		//禁止自动转义
		$mailinfo['content'] = stripslashes(htmlspecialchars_decode($_POST["interMail"]['content']));
		//消除空行
		$mailinfo['content'] = str_replace("<p>","",$mailinfo['content']);
		$mailinfo['content'] = str_replace("</p>","<br>",$mailinfo['content']);
		$mailinfo['isSender'] = 1;

		$uploadFile = new model_file_uploadfile_management ();
		$file = $uploadFile->getFilesByObjId ( $object['id'], 'oa_hr_entryNotice_email' );
		if ($file) {
			foreach ($file as $key => $val) {
                $mailinfo['attachment'][$val['uploadPath'].$val['newName']] = $val['originalName'];
			}
		}

		$emailDao = new model_common_mail();
		$emailDao->mailWithFileGeneral($mailinfo['title'] ,$mailinfo['email']['TO_ID'] ,$mailinfo['content'] ,null ,$mailinfo['attachment']);


		if ($rs) {
			msg('发送成功！');
		} else {
			msg('发送失败！');
		}
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		if(isset($_POST ['stateSearch'])) {
			switch($_POST ['stateSearch']) {
				case '1':
					$service->searchArr ['state'] = '1';
					$service->searchArr ['accountState'] = 0;
					$service->searchArr ['staffFileState'] = 0;
					$service->searchArr ['contractState'] = 0;
					$service->searchArr ['isSaveN'] = 1;
					break;
				case '2':
					$service->searchArr ['accountState'] = 1;
					$service->searchArr ['staffFileState'] = 0;
					$service->searchArr ['contractState'] = 0;
					$service->searchArr ['isSaveN'] = 1;
					break;
				case '3':
					$service->searchArr ['accountState'] = 1;
					$service->searchArr ['staffFileState'] = 1;
					$service->searchArr ['contractState'] = 0;
					$service->searchArr ['isSaveN'] = 1;
					break;
				case '4':
					$service->searchArr ['accountState'] = 1;
					$service->searchArr ['staffFileState'] = 1;
					$service->searchArr ['contractState'] = 1;
					$service->searchArr ['isSaveN'] = 1;
					break;
				case '5':
					$service->searchArr ['isSave'] = 1;
					$service->searchArr ['state'] = 1;
					break;
			}
		}
		$rows = $service->page_d ('select_list');
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)) {
			$leaveDao = new model_hr_leave_leave();
			foreach( $rows as $key => $val ){
				//转换成中文
				if($rows[$key]['isSave'] == '1' && $rows[$key]['state'] != 2) {
					$rows[$key]['stateC'] = '待发送邮件';
				}else if($rows[$key]['accountState'] == 1 && $rows[$key]['staffFileState'] == 0 && $rows[$key]['contractState'] == 0 && $rows[$key]['state'] != 2) {
					$rows[$key]['stateC'] = '已建账号';
				}else if($rows[$key]['accountState'] == 1 && $rows[$key]['staffFileState'] == 1 && $rows[$key]['contractState'] == 0 && $rows[$key]['state'] != 2) {
					$rows[$key]['stateC'] = '已建档案';
				}else if($rows[$key]['accountState'] == 1 && $rows[$key]['staffFileState'] == 1 && $rows[$key]['contractState'] == 1 && $rows[$key]['state'] != 2) {
					$rows[$key]['stateC'] = '已签合同';
				}else{
					$rows[$key]['stateC'] = $service->statusDao->statusKtoC($rows[$key]['state'] );
				}

				//获取离职信息
				if ($val["state"] == 2 && !empty($val["userAccount"])) {
					$leaveObj = array();
					$leaveObj = $leaveDao->find(" userAccount='$val[userAccount]' AND ExaStatus='完成' AND state<>4 ");
					//提取离职原因，重新组合
					$str = substr($leaveObj['quitReson'] ,-5);
					if ($str == "^nbsp") { //没有包含其他原因
						$leaveObj['quitReson'] = str_replace('^nbsp' ,"； " ,$leaveObj['quitReson']);
					} else {
						$quitReson = '';
						$arr = explode("^nbsp" ,$leaveObj['quitReson']);
						for ($i = 0 ;$i < count($arr) - 2 ;$i++) { //不处理其他原因
							$quitReson .= $arr[$i]."；";
						}
						$leaveObj['quitReson'] = $quitReson.$arr[$i]."：".$arr[$i + 1];
					}
					$rows[$key]["leaveReason"] = $leaveObj["quitReson"];
				} else {
					$rows[$key]["leaveReason"] = '';
				}
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到查看入职通知页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		if($obj['interviewType'] == 1 || $obj['interviewType'] == 3 ){   //3是简历发送过来的入职通知

			$this->assign ( 'interviewTypeName', '增员申请' );
		} else {
			$this->assign ( 'interviewTypeName', '内部推荐' );
		}
		$this->view('view' );
	}

	/**
	 * 完成入职
	 */
	function c_doneEntry() {
		if($this->service->doneEntry_d( $_POST ['id'])) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 跳转到放弃入职页面
	 */
	function c_toCancelEntry() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view('cancelEntry' ,true);
	}

	/**
	 * 放弃入职
	 */
	function c_cancelEntry() {
		$this->checkSubmit(); //检查是否重复提交
		if($this->service->cancelEntry_d($_POST[$this->objName])) {
			msg("保存成功");
		} else {
			msg("保存失败");
		}
	}

	/**
	 * 跳转到查看放弃入职页面
	 */
	function c_toViewCancel() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view('view-cancel');
	}

	/**
	 * 跳转到编辑放弃入职原因页面
	 */
	function c_toEditCancel() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}
		//从招聘修改放弃入职原因需要发邮件通知人事组
		$this->assign('mailInfo' ,isset($_GET['mailInfo']) ? 'yes' : 'no');
		$this->view('edit-cancel' ,true);
	}

	/**
	 * 编辑放弃入职原因
	 */
	function c_editCancel() {
		$this->checkSubmit();
		if($this->service->editCancel_d($_POST[$this->objName])) {
			msg("保存成功");
		} else {
			msg("保存失败");
		}
	}

	function c_toExport(){
		$this->view('exportview');
	}

	function c_EntryExport(){
		$object = $_POST[$this->objName];

		if(!empty($object['beginDate'])) {
			$this->service->searchArr['preDateHope'] = $object['beginDate'];
		}
		if(!empty($object['endDate'])) {
			$this->service->searchArr['afterDateHope'] = $object['endDate'];
		}

		if(!empty($object['formCode'])) {
			$this->service->searchArr['formCode'] = $object['formCode'];
		}

		if(!empty($object['userName'])) {
			$this->service->searchArr['userNameSame'] = $object['userName'];
		}

		if(!empty($object['deptName'])) {
			$this->service->searchArr['deptName'] = $object['deptName'];
		}

		if(!empty($object['entryDateBegin'])) {
			$this->service->searchArr['entryDatefrom'] = $object['entryDateBegin'];
		}

		if(!empty($object['entryDateEnd'])) {
			$this->service->searchArr['entryDateto'] = $object['entryDateEnd'];
		}

		if(!empty($object['state'])) { //单据状态
			$state = implode(',' ,$object['state']);
			$this->service->searchArr['stateArr'] = $state;
		}

		set_time_limit(0); // 执行时间为无限制
		$rows = $this->service->listBySqlId('select_list');

		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$exportData = array();
		$applyObjs = array();
		$applyDao = new model_hr_recruitment_apply();
		foreach ( $rows as $key => $val ) {
			//面试类型
			switch ($val['interviewType']) {
				case '1':
					$val['interviewType'] = '增员申请';
					break;
				case '2':
					$val['interviewType'] = '内部推荐';
					break;
				// case '3':
				// 	$val['interviewType'] = '增员申请'; //3是简历发送过来的入职通知
				// 	break;
				default:
					$val['interviewType'] = '';
					break;
			}

			//增员申请信息
			if ($val['sourceId'] > 0) {
				if (empty($applyObjs[$val['sourceId']])) {
					$applyObj = $applyDao->get_d($val['sourceId']);
					$applyObjs[$val['sourceId']] = $applyObj;
					$val['workArrange'] = $applyObj['workArrange']; //试用期内的工作安排
					$val['assessmentIndex'] = $applyObj['assessmentIndex']; //试用期结束后的主要考核指标
				} else {
					$val['workArrange'] = $applyObjs[$val['sourceId']]['workArrange']; //试用期内的工作安排
					$val['assessmentIndex'] = $applyObjs[$val['sourceId']]['assessmentIndex']; //试用期结束后的主要考核指标
				}
			} else {
				$val['workArrange'] = ''; //试用期内的工作安排
				$val['assessmentIndex'] = ''; //试用期结束后的主要考核指标
			}

			$exportData[$key]['formCode'] = $val['formCode'];
			$exportData[$key]['formDate'] = $val['formDate'];
			$exportData[$key]['userAccount'] = $val['userAccount'];
			$exportData[$key]['userName'] = $val['userName'];
			$exportData[$key]['sex'] = $val['sex'];
			$exportData[$key]['phone'] = $val['phone'];
			$exportData[$key]['email'] = $val['email'];
			$exportData[$key]['interviewType'] = $val['interviewType'];
			$exportData[$key]['resumeCode'] = $val['resumeCode'];
			$exportData[$key]['hrSourceType2Name'] = $val['hrSourceType2Name'];
			$exportData[$key]['entryDate'] = $val['entryDate'];
			$exportData[$key]['state'] = $this->service->statusDao->statusKtoC($val['state']);
			$exportData[$key]['applyCode'] = $val['applyCode'];
			$exportData[$key]['positionsName'] = $val['positionsName'];
			$exportData[$key]['developPositionName'] = $val['developPositionName'];
			$exportData[$key]['deptName'] = $val['deptName'];
			$exportData[$key]['workPlace'] = $val['workProvince'].'-'.$val['workCity'];
			$exportData[$key]['useHireTypeName'] = $val['useHireTypeName'];
			$exportData[$key]['useAreaName'] = $val['useAreaName'];
			$exportData[$key]['sysCompanyName'] = $val['sysCompanyName'];
			$exportData[$key]['assistManName'] = $val['assistManName'];
			$exportData[$key]['assistManPhone'] = $this->service->get_table_fields('oa_hr_personnel' ,"userAccount='".$val['assistManId']."'" ,'mobile');
			$exportData[$key]['useDemandEqu'] = $val['useDemandEqu'];
			$exportData[$key]['useSign'] = $val['useSign'];
			$exportData[$key]['probation'] = $val['probation'];
			$exportData[$key]['contractYear'] = $val['contractYear'];
			$exportData[$key]['hrSourceType1Name'] = $val['hrSourceType1Name'];
			$exportData[$key]['hrJobName'] = $val['hrJobName'];
			$exportData[$key]['hrIsManageJob'] = $val['hrIsManageJob'];
			$exportData[$key]['workArrange'] = $val['workArrange'];
			$exportData[$key]['assessmentIndex'] = $val['assessmentIndex'];
		}
		return model_hr_recruitment_entryNoticeExportUtil::export2ExcelUtil ( $exportData );
	}


	/**
	 * 导出数据
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['entryNotice']['listSql']))));
		$this->view('excelout-select');
	}

	/**
	 * 导出数据
	 */
	function c_selectExcelOut(){
		$rows = array();//数据集
		$listSql = str_replace("&nbsp;"," ",stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if(!empty($listSql)){
			$rows = $this->service->_db->getArray($listSql);
		}
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//转换成中文
				if($rows[$key]['accountState']==1&&$rows[$key]['staffFileState']==0&&$rows[$key]['contractState']==0&&$rows[$key]['state']!=2){
					$rows[$key]['state']='已建账号';
				}else if($rows[$key]['accountState']==1&&$rows[$key]['staffFileState']==1&&$rows[$key]['contractState']==0&&$rows[$key]['state']!=2){
					$rows[$key]['state']='已建档案';
				}else if($rows[$key]['accountState']==1&&$rows[$key]['staffFileState']==1&&$rows[$key]['contractState']==1&&$rows[$key]['state']!=2){
					$rows[$key]['state']='已签合同';
				}else{
					$rows[$key]['state']=$this->service->statusDao->statusKtoC($rows[$key]['state'] );
				}
			}
		}
		$colNameArr = array();//列名数组
		include_once ("model/hr/recruitment/entryNoticeFieldArr.php");
		if(is_array($_POST['entryNotice'])){
			foreach($_POST['entryNotice'] as $key=>$val){
					foreach($entryNoticeFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}
		$newColArr = array_combine($_POST['entryNotice'],$colNameArr);//合并数组
		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip($_POST['entryNotice']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		};

		return model_hr_personnel_personnelExcelUtil::excelOutEntryNotice($newColArr,$dataArr);
	}

	/**
	 * 根据入职ID更新待入职人数
	 */
	function c_updateBeEntryNumById( $id ) {
		return $this->service->updateBeEntryNumById_d( $id );
	}

	/**
	 * 跳转到填写离职原因页面
	 */
	function c_toAddDepart() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		//从招聘填写离职原因需要发邮件通知人事组
		$this->assign('mailInfo' ,isset($_GET['mailInfo']) ? 'yes' : 'no');
		$this->view('add-depart' ,true);
	}

	/**
	 * 填写离职原因
	 */
	function c_addDepart() {
		$this->checkSubmit();
		if($this->service->addDepart_d($_POST[$this->objName])) {
			msg("保存成功");
		} else {
			msg("保存失败");
		}
	}

	/**
	 * 跳转到查看放弃入职页面
	 */
	function c_toViewDepart() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign($key ,$val);
		}
		$this->view('view-depart');
	}
 }
?>