<?php

/**
 * @author Administrator
 * @Date 2012-08-07 09:38:05
 * @version 1.0
 * @description:离职管理 Model层
 */
class model_hr_leave_leave extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave";
		$this->sql_map = "hr/leave/leaveSql.php";
		parent :: __construct();
	}

	/**
	 * 根据员工账号获取人事信息和合同起止日期
	 */
	function getPersonnelInfo_d($userAccount) {
		$contractArr=array('beginDate'=>'','closeDate'=>'');
		//人事信息
		$sql = "select userNo,staffName,jobName,jobId,entryDate,becomeDate,wageLevelCode,wageLevelName,companyName,companyId,belongDeptName,belongDeptId,mobile,personEmail,deptId,personLevel from oa_hr_personnel where userAccount = '" . $userAccount . "'";
		$arr = $this->_db->getArray($sql);
		//合同起止日期
		$contractSql = "select beginDate,closeDate,trialBeginDate,trialEndDate from oa_hr_personnel_contract where userAccount = '" . $userAccount . "' and conType='HRHTLX-05' and beginDate=(SELECT MAX(beginDate)  from oa_hr_personnel_contract where userAccount = '" . $userAccount . "' and conType='HRHTLX-05')";
		$Carr = $this->_db->getArray($contractSql);
		if(is_array($Carr)){
			$contractArr= $Carr['0'];
		}
		$row = array_merge($arr[0],$contractArr);
		return $row;
	}

	/**
	 * 根据员工账号获取是否存在离职单据
	 */
	function getLeaveInfo_d($userAccount) {
		$sql = "select count(*) as num from oa_hr_leave where state<>4 and userAccount = '" . $userAccount . "'";
		$arr = $this->_db->getArray($sql);
		if ($arr[0]['num'] == '0') {
			return "0";
		} else {
			return "1";
		}
	}

	//编号自动生成 （临时）
	function leaveCode() {
		$billCode = "LZ" . date("Ym");
		//        $billCode = "JL201208";
		$sql = "select max(RIGHT(c.leaveCode,4)) as maxCode,left(c.leaveCode,8) as _maxbillCode " .
		"from oa_hr_leave c group by _maxbillCode having _maxbillCode='" . $billCode . "'";

		$resArr = $this->findSql($sql);
		$res = $resArr[0];
		if (is_array($res)) {
			$maxCode = $res['maxCode'];
			$maxBillCode = $res['maxbillCode'];
			$newNum = $maxCode +1;
			switch (strlen($newNum)) {
				case 1 :
					$codeNum = "000" . $newNum;
					break;
				case 2 :
					$codeNum = "00" . $newNum;
					break;
				case 3 :
					$codeNum = "0" . $newNum;
					break;
				case 4 :
					$codeNum = $newNum;
					break;
			}
			$billCode .= $codeNum;
		} else {
			$billCode .= "0001";
		}

		return $billCode;
	}

	/**
	 * 重写新增方法
	 */
	function add_d($object) {
		try {
			$this->start_d();

			$object['leaveCode'] = $this->leaveCode();
			if($object['quitTypeCode']!="YGZTCZ"){//非辞职类型则不需要走审批流程
				$object['ExaStatus'] = '完成';
			}else{
				$object['ExaStatus'] = '未提交';
			}
			//处理数据字典字段
			if(isset($object['quitTypeCode'])){
				$datadictDao = new model_system_datadict_datadict();
				$object['quitTypeName'] = $datadictDao->getDataNameByCode($object['quitTypeCode']);
			}

			//修改主表信息
			$newId = parent :: add_d($object, true);

			//判断是否为非辞职类型
			if(is_array($object['handitem'])) {
				$itemDao=new model_hr_leave_handitem();
				$itemDao->createBatch($object['handitem'] ,array('mainId' => $newId) ,'handContent');
				if($object['quitTypeCode'] != "YGZTCZ") {
					/**邮件通知员工*/
					$emailmsg .='<table border=1 cellspacing=0  width=100% bordercolorlight="#333333" bordercolordark="#efefef" style="font-size:14"> <tr align="center" style=";BACKGROUND-COLOR:#efefef"><td>工作及设备交接事项</td><td>交接人</td></tr>';
					foreach($object['handitem'] as $key=>$val){
							$handContent=$val['handContent'];
							$recipientName=$val['recipientName'];
							$emailmsg .=<<<EOT
								<tr >
								        <td>$handContent</td>
										<td>$recipientName</td>
								</tr>
EOT;
					}
					$addmsg.="</table>";
					$emailDao = new model_common_mail();
					$emailDao->emailInquiry(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'sendLeaveStaff', '您的离职日期为：<font color=blue>'.$object['comfirmQuitDate'].'</font>，并已指定具体工作任务及资料交接事项,请于<font color=blue>'.$object['comfirmQuitDate'].'</font>前据下表跟工作接收人完成工作交接', '', $object['userAccount'], $emailmsg, 1);

					//发送邮件通知账务
					$this->sendMailToFinan($object);

					//发送离职指引
					// $companyName=$this->get_table_fields('oa_hr_personnel', "userNo='".$object['userNo']."'", 'companyName');
					// $filePath = str_replace('\\','/',UPLOADPATH);
					// $destDir = $filePath."oa_hr_leave_email/";			//附件地址
					// $title=$object['deptName'].'/'.$object['userName'].'离职指引';
					// switch($companyName){//不同公司发送给不同的人员
					// 	case '世源信通' : $destDir.='世源信通-离职指引.docx';
					// 					break;
					// 	case '广州贝讯' : $destDir.='广州贝讯-离职指引.docx';
					// 					break;
					// 	case '广州贝软' : $destDir.='广州贝软-离职指引.docx';
					// 					break;
					// 	case '鼎元丰和' : $destDir.='鼎元丰和-离职指引.docx';
					// 					break;
					// 	default : $destDir.='世纪鼎立-离职指引.docx';
					// 			  break;
					// }
					// $content = "您好，请下载相关附件查看离职指引！";
					// $emailDao->mailWithFile($title,$object['userAccount'],$content,null,$destDir);
				}
			}

			$this->commit_d();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 确认类型
	 */
	function editType_d($object) {
		try {
			$this->start_d();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$object['quitTypeName'] = $datadictDao->getDataNameByCode($object['quitTypeCode']);
			if($object['quitTypeCode']!="YGZTCZ"){//非辞职类型则不需要走审批流程
				$object['ExaStatus'] = '完成';
			}else{
				$object['ExaStatus'] = '未提交';
			}

			if($_GET['actType'] != 'staff') {
				$object['state'] = '2';
			}

			$newId = parent :: edit_d($object ,true); //修改主表信息

			$row = $this->get_d($object['id']);

			$itemDao=new model_hr_leave_handitem();
			//删除原交接内容
			$itemDao->delete("mainId=".$row['id']);

			//判断是否为非辞职类型
			if(is_array($object['handitem'])){
				$itemDao->createBatch($object['handitem'],array('mainId'=>$row['id']),'handContent');
				if($object['quitTypeCode'] != "YGZTCZ"){
					/**邮件通知员工*/
					$emailmsg .='<table border=1 cellspacing=0  width=100% bordercolorlight="#333333" bordercolordark="#efefef" style="font-size:14"> <tr align="center" style=";BACKGROUND-COLOR:#efefef"><td>工作及设备交接事项</td><td>交接人</td></tr>';
					foreach($object['handitem'] as $key=>$val){
							$handContent=$val['handContent'];
							$recipientName=$val['recipientName'];
							$emailmsg .=<<<EOT
								<tr >
								        <td>$handContent</td>
										<td>$recipientName</td>
								</tr>
EOT;
					}
					$addmsg.="</table>";
					$emailDao = new model_common_mail();
					$emailDao -> emailInquiry(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'sendLeaveStaff', '您的离职日期为：<font color=blue>'.$object['comfirmQuitDate'].'</font>，并已指定具体工作任务及资料交接事项,请于<font color=blue>'.$object['comfirmQuitDate'].'</font>前据下表跟工作接收人完成工作交接', '', $row['userAccount'], $emailmsg, 1);

					//发送邮件通知账务
					$row=$this->get_d($object['id']);
					$this->sendMailToFinan($row);
					//发送离职指引
					// $companyName=$this->get_table_fields('oa_hr_personnel', "userNo='".$row['userNo']."'", 'companyName');
					// $filePath = str_replace('\\','/',UPLOADPATH);
					// $destDir = $filePath."oa_hr_leave_email/";			//附件地址
					// $title=$row['deptName'].'/'.$row['userName'].'离职指引';
					// switch($companyName) {//不同公司发送给不同的人员
					// 	case '世源信通' : $destDir.='世源信通-离职指引.docx';
					// 					break;
					// 	case '广州贝讯' : $destDir.='广州贝讯-离职指引.docx';
					// 					break;
					// 	case '广州贝软' : $destDir.='广州贝软-离职指引.docx';
					// 					break;
					// 	case '鼎元丰和' : $destDir.='鼎元丰和-离职指引.docx';
					// 					break;
					// 	default : $destDir.='世纪鼎立-离职指引.docx';
					// 			  break;
					// }
					// $content = "您好，请下载相关附件查看离职指引！";
					// $emailDao->mailWithFile($title,$row['userAccount'],$content,null,$destDir);
				}
			}

			$this->commit_d();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}


	/**
	 * 根据主键修改对象
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			parent :: edit_d($object, true);
			if(is_array($object['handitem'])){
				$itemDao=new model_hr_leave_handitem();
				//删除从表的内容，并添加新内容
				$deleteCondition=array('mainId'=>$object['id']);
				$itemDao->delete($deleteCondition);
				$itemDao->addBatch_d($object['handitem']);
			}
			$this->commit_d();
			return true;
		}catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据主键修改对象
	 */
	function staffEdit_d($object) {
		try {
			$this->start_d();
			parent :: edit_d($object, true);
			$this->commit_d();
			return true;
		}catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 发送邮件通知账务
	 */
	function sendMailToFinan($obj) {
		$leaveCode = $obj['leaveCode'];
		$userName = $obj['userName'];
		$deptName = $obj['deptName'];
		$jobName = $obj['jobName'];
		$comfirmQuitDate = $obj['comfirmQuitDate'];
		$salaryEndDate = $obj['salaryEndDate'];
		$companyName=$this->get_table_fields('oa_hr_personnel', "userNo='".$obj['userNo']."'", 'companyName');
		$deptId=$this->get_table_fields('oa_hr_personnel', "userNo='".$obj['userNo']."'", 'deptId');
		$addmsg .=<<<EOT
				<table border=1 cellspacing=0  width=100% bordercolorlight='#333333' bordercolordark='#efefef' style="font-size:14">
				    <tr align="center" style=";BACKGROUND-COLOR:#efefef">
				       <td>离职单编号</td><td>离职员工</td><td>归属公司</td><td>所属部门</td><td>职位</td><td>离职日期</td><td>工资结算截止日期</td>
				    </tr>
					<tr align="center" >
					        <td>$leaveCode</td>
							<td>$userName</td>
							<td>$companyName</td>
							<td>$deptName</td>
							<td>$jobName</td>
							<td>$comfirmQuitDate</td>
							<td>$salaryEndDate</td>
					</tr>
					</table>
EOT;
		$addmsg .= "<font color='blue'>说明：</font>";
		$addmsg .= "<br>财务请优先处理离职员工的报销与借款";
		//获取默认发送人
		include (WEB_TOR . "model/common/mailConfig.php");
		$mailstr='';
		$mailstr.=$mailUser['leave']['sendUserId'];
		switch($companyName){//不同编制发送给不同的人员
			case '世源信通' : $mailstr.=','.$mailUser['leave_shiyuan']['sendUserId'];
							break;
			case '广州贝讯' : $mailstr.=','.$mailUser['contract_bx']['TO_ID'];
							break;
			case '广州贝软' : $mailstr.=','.$mailUser['leave_beiruan']['sendUserId'];
							break;
			case '鼎元丰和' : $mailstr.=','.$mailUser['leave_dingyuan']['sendUserId'];
							break;
			default : $mailstr.=','.$mailUser['leave_dingli']['sendUserId'];			//公司判断，默认世纪鼎利
					  break;
		}
		//判断是否为服务线或营销线
		if($deptId == '35') {//服务线
			$mailstr.=','.$mailUser['leave_fuwu']['sendUserId'];
		}else if($deptId == '37'){//营销线
			$mailstr.=','.$mailUser['leave_yingxiao']['sendUserId'];
		}
		$emailDao = new model_common_mail();
		$emailDao -> emailInquiry(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'sendLeave', '员工离职通知', '', $mailstr, $addmsg, 1);
		return true;
	}

	/**
	 * 批量审批处理
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		if($folowInfo['examines']=="ok"){  //审批通过
			$objId = $folowInfo ['objId'];
			$obj = $this->get_d ( $objId );
			$handitemDao = new model_hr_leave_handitem();
			$rows['handitem'] = $handitemDao->findAll(array('mainId'=>$objId),null,'handContent,recipientName,recipientId');
			$this->sendLeaveMail($obj,$rows);
		}
	}
	
	/**
	 * 审批后发送邮件
	 */
	function sendLeaveMail($obj,$object) {
		$filePath = str_replace('\\','/',UPLOADPATH);
		$destDir = $filePath."oa_hr_leave_email/";			//附件地址
		$leaveCode = $obj['leaveCode'];
		$userName = $obj['userName'];
		$deptName = $obj['deptName'];
		$jobName = $obj['jobName'];
		$comfirmQuitDate = $obj['comfirmQuitDate'];
		$salaryEndDate = $obj['salaryEndDate'];
		$companyName=$this->get_table_fields('oa_hr_personnel', "userNo='".$obj['userNo']."'", 'companyName');
		$deptId=$this->get_table_fields('oa_hr_personnel', "userNo='".$obj['userNo']."'", 'deptId');
		$addmsg .=<<<EOT
				<table border=1 cellspacing=0  width=100% bordercolorlight='#333333' bordercolordark='#efefef' style="font-size:14">
				    <tr align="center" style=";BACKGROUND-COLOR:#efefef">
				       <td>离职单编号</td><td>离职员工</td><td>归属公司</td><td>所属部门</td><td>职位</td><td>离职日期</td><td>工资结算截止日期</td>
				    </tr>
					<tr align="center" >
					        <td>$leaveCode</td>
							<td>$userName</td>
							<td>$companyName</td>
							<td>$deptName</td>
							<td>$jobName</td>
							<td>$comfirmQuitDate</td>
							<td>$salaryEndDate</td>
					</tr>
					</table>
EOT;
		$addmsg .= "<font color='blue'>说明：</font>";
		$addmsg .= "<br>财务请优先处理离职员工的报销与借款";
		//获取默认发送人
		include (WEB_TOR . "model/common/mailConfig.php");
		$mailstr='';
		$mailstr.=$mailUser['leave']['sendUserId'];
		switch($companyName){//不同编制发送给不同的人员
			case '世源信通' : $mailstr.=','.$mailUser['leave_shiyuan']['sendUserId'];
							 $destDir.='世源信通-离职指引.docx';
							break;
			case '广州贝讯' : $mailstr.=','.$mailUser['contract_bx']['TO_ID'];
							 $destDir.='广州贝讯-离职指引.docx';
							break;
			case '广州贝软' : $mailstr.=','.$mailUser['leave_beiruan']['sendUserId'];
							 $destDir.='广州贝软-离职指引.docx';
							break;
			case '鼎元丰和' : $mailstr.=','.$mailUser['leave_dingyuan']['sendUserId'];
							 $destDir.='鼎元丰和-离职指引.docx';
							break;
			default : $mailstr.=','.$mailUser['leave_dingli']['sendUserId'];			//公司判断，默认世纪鼎利
					  $destDir.='世纪鼎立-离职指引.docx';
					  break;
		}
		//判断是否为服务线或营销线
		if($deptId=='35'){//服务线
			$mailstr.=','.$mailUser['leave_fuwu']['sendUserId'];
		}else if($deptId=='37'){//营销线
			$mailstr.=','.$mailUser['leave_yingxiao']['sendUserId'];
		}
		$emailDao = new model_common_mail();
		$emailDao -> emailInquiry(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'sendLeave', '员工离职通知', '', $mailstr, $addmsg, 1);

		/**邮件通知员工*/
		$emailmsg .='<table border=1 cellspacing=0  width=100% bordercolorlight="#333333" bordercolordark="#efefef" style="font-size:14"> <tr align="center" style=";BACKGROUND-COLOR:#efefef"><td>工作及设备交接事项</td><td>交接人</td></tr>';
		foreach($object['handitem'] as $key=>$val){
				$handContent=$val['handContent'];
				$recipientName=$val['recipientName'];
				$emailmsg .=<<<EOT
					<tr >
					        <td>$handContent</td>
							<td>$recipientName</td>
					</tr>
EOT;
		}
		$addmsg.="</table>";
		$emailDao -> emailInquiry(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'sendLeaveStaff', '您的离职申请已审批通过。离职日期为：'.$obj['comfirmQuitDate'].'，并已指定具体工作任务及资料交接事项,请于'.$obj['comfirmQuitDate'].'前据下表跟工作接收人完成工作交接', '', $obj['userAccount'], $emailmsg, 1);

		/**发送离职指引*/
		// $title=$obj['deptName'].'/'.$obj['userName'].'离职指引';
		// $content = "您好，请下载相关附件查看离职指引";
		// $emailDao->mailWithFile($title,$obj['userAccount'],$content,null,$destDir);
		return true;
	}

	//发送邮件
	function sendEmail($obj){
		try{
			$object['id']=$obj['id'];
			$object['emailSate']=1;
			$id=$this->updateById($object);
			$mailDao = new model_common_mail();
			$title=$obj['deptName'].'/'.$obj['userName'].'已完成离职';
			$mailDao->mailClear($title,$obj['TO_ID'],$obj['mailContent'],null);
			return true;
		}catch(exception $e){
			return false;
		}
	}

	//发送邮件-离职指引
	function sendEmailguide($obj){
		try{
			$object['id']=$obj['id'];
			$object['emailSate']=1;
			$id=$this->updateById($object);
			$mailDao = new model_common_mail();
			$title=$obj['deptName'].'/'.$obj['userName'].'离职指引';
			//抄送人邮件发送
			$mailDao->mailWithFile($title,$obj['TO_CCID'],$obj['mailContent'],null,$obj['attachment']);
			//收件人人邮件发送
			$email = new includes_class_sendmail();
			$obj['mailContent'] = nl2br($obj['mailContent']);
			$email->send($title,$obj['mailContent'],$obj['receiver'],null,null,'GBK','default',NULL,$obj['attachment']);
			return true;
		}catch(exception $e){
			return false;
		}
	}

	/**
	 * 改变状态不带邮件通知
	 */
	function getState($id ,$state = 1) {
		$object['id'] = $id;
		$object['state'] = $state;
		$flag = $this->updateById($object);
		return $flag;
	}

	/**
	 * 改变状态带邮件通知
	 */
	function changeState($id ,$state = 1) {
		$object['id'] = $id;
		$object['state'] = $state;
		$object['leaveApplyDate'] = date('Y-m-d',time());
		$flag = $this->updateById($object);
		if($state == 1) {
			$this->mailForSubmit($id);
		}
		return $flag;
	}

	/**提交离职申请发送邮件*/
	function mailForSubmit($id){
		//获取离职申请信息
		$row = $this->get_d($id);
		$companyName = $this->get_table_fields('oa_hr_personnel', "userNo='".$row['userNo']."'", 'companyName');
		//发送邮件通知HR
		include (WEB_TOR . "model/common/mailConfig.php");
		$emailDao = new model_common_mail();
		$mailstr = $mailUser['leave_dingli']['sendUserId'];		//根据公司，选择通知人，默认世纪鼎利
		$addMsg = "你好,".$row['deptName']."的".$row['userName']."提交了离职申请,请查看。";
		switch ($companyName){
			case '世源信通' : $mailstr = $mailUser['leave_shiyuan']['sendUserId'];
							break;
			case '广州贝讯' : $mailstr = $mailUser['contract_bx']['TO_ID'];
							break;
			case '广州贝软' : $mailstr = $mailUser['leave_beiruan']['sendUserId'];
							break;
			case '鼎元丰和' : $mailstr = $mailUser['leave_dingyuan']['sendUserId'];
							break;
		}
		$emailDao->mailClear($row['deptName'].'-'.$row['userName'].'提交了离职申请', $mailstr, $addMsg, null);
	}

	/**离职打印样情*/
	function getLeaveUserInfo($LId){
		if($LId){
			$contractSql = "SELECT (date_format(a.comfirmQuitDate,'%Y')-date_format(b.entryDate,'%Y')) as leaveYears,
							(date_format(a.comfirmQuitDate,'%m')-date_format(b.entryDate,'%m')) as leaveMonth,
							(date_format(a.comfirmQuitDate,'%d')-date_format(b.entryDate,'%d')) as leaveDay,
							c.NamePT,c.NameCN,b.userName,b.identityCard,b.entryDate,a.comfirmQuitDate,a.quitTypeCode,a.jobName,a.userAccount
							FROM oa_hr_leave a LEFT JOIN oa_hr_personnel b ON a.userNo=b.userNo
							LEFT JOIN branch_info c ON b.companyId=c.ID
							WHERE  a.id='$LId'";
			$row= $this->_db->getArray($contractSql);
		}
		return $row[0];
	}

	/**
	 * 更新离职员工档案
	 */
	function updatePersonInfo($data) {
		try {
			$this->start_d();

			if(is_array($data)) {
				$object = $data;
			} else {
				$object = array($data);
			}

			foreach($object as $key => $val) {
				$obj = array();
				$dataArr = $this->get_d($val);
				$obj['employeesState'] = "YGZTLZ"; //给当前员工附上离职状态
				//提取相关信息更新到人事档案
				$obj['quitTypeCode'] = $dataArr['quitTypeCode'];
				$obj['quitReson'] = $dataArr['quitReson'];
				$obj['quitDate']= $dataArr['comfirmQuitDate'];
				$obj['staffState'] = $dataArr['quitTypeCode'];
				//获取ID
				$personelId = $this->get_table_fields('oa_hr_personnel',"userNo='".$dataArr['userNo']."'",'id');
				$obj['id'] = $personelId;
				$personel = new model_hr_personnel_personnel();
				$result = $personel->updataLeave_d($obj); //更新档案记录
				if($result) {
					$this->updateById(array('id' => $dataArr['id'] ,'state' => 3));
				}
			}

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 离职申请，撤销功能
	 */
	 function updateExaStatus_d($object){
	 	$flag = $this->updateById($object);
	 	return $flag;
	 }

	 /**
	  * 批量打印，获取选中的人物
	  */
	 function getChecked_d($idStr){
		if($idStr){
			$idArr = explode(',',$idStr);
			$num = 0;
			foreach($idArr as $key => $val){
				$num++;
				$data = $this->get_d($val);
				$str .=<<<EOT
						<input type="checkbox" name="leave[idchecked][]" value="$data[id]" checked/>$data[userName]&nbsp;&nbsp;&nbsp;&nbsp;
EOT;
				if($num%5==0){
					$str .=<<<EOT
					<br>
EOT;
				}
			}
		}
		return $str;
	 }

	/**
	 * 离职申请打回
	 */
	function back_d( $obj ) {
		try {
			$this->start_d();

			$this->updateById(array("id"=>$obj['id'] ,"state"=>0));

			//发邮件通知员工
			$object = $this->get_d( $obj['id'] );
			$mailDao = new model_common_mail();
			$mailContent = '你好，你的离职申请已被打回，原因如下：<br>'.$obj['backReason'];
			$mailDao->mailClear('打回离职申请' ,$object['userAccount'] ,$mailContent ,null);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 编辑真实离职原因
	 */
	function editReal_d( $obj ) {
		try {
			$this->start_d();

			$rs = parent::edit_d($obj ,true);

			//同步更新人事档案真实离职原因和黑名单
			if ($rs) {
				$object = $this->get_d($obj['id']);
				$personnelDao = new model_hr_personnel_personnel();
				$personnelObj = $personnelDao->find(array('userNo' => $object['userNo']));
				if ($personnelObj) {
					$personnelDao->updateById(
						array(
							'id' => $personnelObj['id'],
							'realReason' => $obj['realReason'],
							'isBack' => $obj['isBack']
						)
					);
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * excel导入修改离职信息
	 */
	function editLeaveInfoExcel_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType = $_FILES["inputExcel"]["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
		$inArr = array();//插入数组
		$linkmanArr = array();//插入数组

		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear($filename ,$temp_name);
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//行数组循环
				foreach($excelData as $key => $val){
					$actNum = $key + 1;
					if( empty($val[0]) ){
						continue;
					}else{
						//新增数组
						$inArr = array();

						//员工编号
						if(!empty($val[0]) && trim($val[0]) != '') {
							$inArr['userNo'] = trim($val[0]);
							$tmp = $this->find(array('userNo'=>$inArr['userNo']));
							if (!$tmp) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!员工不存在离职申请</font>';
								array_push($resultArr ,$tempArr);
								continue;
							} else {
								if($tmp['ExaStatus'] != '完成') {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!员工离职申请审批未完成</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
								if($tmp['state'] == '4') {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!员工离职申请已关闭</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['id'] = $tmp['id'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!员工编号为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//$val[1]员工姓名可不管

						//离职日期
						if(!empty($val[2]) && trim($val[2]) != '' && $val[2] != '0000-00-00'){
							$val[2] = trim($val[2]);
							if(!is_numeric($val[2])) {
								$inArr['comfirmQuitDate'] = $val[2];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[2] - 1 , 1900)));
								if($recorderDate == '1970-01-01') {
									$entryDate = date('Y-m-d' ,strtotime($val[2]));
									$inArr['comfirmQuitDate'] = $entryDate;
								} else {
									$inArr['comfirmQuitDate'] = $recorderDate;
								}
							}
						}

						//工资结算截止日期
						if(!empty($val[3]) && trim($val[3]) != '' && $val[3] != '0000-00-00'){
							$val[3] = trim($val[3]);
							if(!is_numeric($val[3])) {
								$inArr['salaryEndDate'] = $val[3];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[3] - 1 , 1900)));
								if($recorderDate == '1970-01-01') {
									$entryDate = date('Y-m-d' ,strtotime($val[3]));
									$inArr['salaryEndDate'] = $entryDate;
								} else {
									$inArr['salaryEndDate'] = $recorderDate;
								}
							}
						}

						//工资支付日期
						if(!empty($val[4]) && trim($val[4]) != '' && $val[4] != '0000-00-00'){
							$val[4] = trim($val[4]);
							if(!is_numeric($val[4])) {
								$inArr['salaryPayDate'] = $val[4];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[4] - 1 , 1900)));
								if($recorderDate == '1970-01-01') {
									$entryDate = date('Y-m-d' ,strtotime($val[4]));
									$inArr['salaryPayDate'] = $entryDate;
								} else {
									$inArr['salaryPayDate'] = $recorderDate;
								}
							}
						}

						//办公软件状态
						if(!empty($val[5]) && trim($val[5]) != ''){
							$val[5] = trim($val[5]);
							if($val[5] == '未关闭') {
								$inArr['softSate'] = 0;
							} else if($val[5] == '已关闭') {
								$inArr['softSate'] = 1;
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!办公软件状态不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						//社保减员
						if(!empty($val[6]) && trim($val[6]) != ''){
							$inArr['pensionReduction'] = trim($val[6]);
						}

						//公积金减员
						if(!empty($val[7]) && trim($val[7]) != ''){
							$inArr['fundReduction'] = trim($val[7]);
						}

						//用工终止
						if(!empty($val[8]) && trim($val[8]) != ''){
							$val[8] = trim($val[8]);
							if($val[8] == '是' || $val[8] == '否') {
								$inArr['employmentEnd'] = $val[8];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!用工终止不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						$rs = parent::edit_d($inArr ,true);

						if($rs){
							$tempArr['result'] = '导入成功';
						}else{
							$tempArr['result'] = '<font color=red>导入失败</font>';
						}
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}
}
?>