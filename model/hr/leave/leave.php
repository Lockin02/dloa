<?php

/**
 * @author Administrator
 * @Date 2012-08-07 09:38:05
 * @version 1.0
 * @description:��ְ���� Model��
 */
class model_hr_leave_leave extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave";
		$this->sql_map = "hr/leave/leaveSql.php";
		parent :: __construct();
	}

	/**
	 * ����Ա���˺Ż�ȡ������Ϣ�ͺ�ͬ��ֹ����
	 */
	function getPersonnelInfo_d($userAccount) {
		$contractArr=array('beginDate'=>'','closeDate'=>'');
		//������Ϣ
		$sql = "select userNo,staffName,jobName,jobId,entryDate,becomeDate,wageLevelCode,wageLevelName,companyName,companyId,belongDeptName,belongDeptId,mobile,personEmail,deptId,personLevel from oa_hr_personnel where userAccount = '" . $userAccount . "'";
		$arr = $this->_db->getArray($sql);
		//��ͬ��ֹ����
		$contractSql = "select beginDate,closeDate,trialBeginDate,trialEndDate from oa_hr_personnel_contract where userAccount = '" . $userAccount . "' and conType='HRHTLX-05' and beginDate=(SELECT MAX(beginDate)  from oa_hr_personnel_contract where userAccount = '" . $userAccount . "' and conType='HRHTLX-05')";
		$Carr = $this->_db->getArray($contractSql);
		if(is_array($Carr)){
			$contractArr= $Carr['0'];
		}
		$row = array_merge($arr[0],$contractArr);
		return $row;
	}

	/**
	 * ����Ա���˺Ż�ȡ�Ƿ������ְ����
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

	//����Զ����� ����ʱ��
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
	 * ��д��������
	 */
	function add_d($object) {
		try {
			$this->start_d();

			$object['leaveCode'] = $this->leaveCode();
			if($object['quitTypeCode']!="YGZTCZ"){//�Ǵ�ְ��������Ҫ����������
				$object['ExaStatus'] = '���';
			}else{
				$object['ExaStatus'] = 'δ�ύ';
			}
			//���������ֵ��ֶ�
			if(isset($object['quitTypeCode'])){
				$datadictDao = new model_system_datadict_datadict();
				$object['quitTypeName'] = $datadictDao->getDataNameByCode($object['quitTypeCode']);
			}

			//�޸�������Ϣ
			$newId = parent :: add_d($object, true);

			//�ж��Ƿ�Ϊ�Ǵ�ְ����
			if(is_array($object['handitem'])) {
				$itemDao=new model_hr_leave_handitem();
				$itemDao->createBatch($object['handitem'] ,array('mainId' => $newId) ,'handContent');
				if($object['quitTypeCode'] != "YGZTCZ") {
					/**�ʼ�֪ͨԱ��*/
					$emailmsg .='<table border=1 cellspacing=0  width=100% bordercolorlight="#333333" bordercolordark="#efefef" style="font-size:14"> <tr align="center" style=";BACKGROUND-COLOR:#efefef"><td>�������豸��������</td><td>������</td></tr>';
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
					$emailDao->emailInquiry(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'sendLeaveStaff', '������ְ����Ϊ��<font color=blue>'.$object['comfirmQuitDate'].'</font>������ָ�����幤���������Ͻ�������,����<font color=blue>'.$object['comfirmQuitDate'].'</font>ǰ���±��������������ɹ�������', '', $object['userAccount'], $emailmsg, 1);

					//�����ʼ�֪ͨ����
					$this->sendMailToFinan($object);

					//������ְָ��
					// $companyName=$this->get_table_fields('oa_hr_personnel', "userNo='".$object['userNo']."'", 'companyName');
					// $filePath = str_replace('\\','/',UPLOADPATH);
					// $destDir = $filePath."oa_hr_leave_email/";			//������ַ
					// $title=$object['deptName'].'/'.$object['userName'].'��ְָ��';
					// switch($companyName){//��ͬ��˾���͸���ͬ����Ա
					// 	case '��Դ��ͨ' : $destDir.='��Դ��ͨ-��ְָ��.docx';
					// 					break;
					// 	case '���ݱ�Ѷ' : $destDir.='���ݱ�Ѷ-��ְָ��.docx';
					// 					break;
					// 	case '���ݱ���' : $destDir.='���ݱ���-��ְָ��.docx';
					// 					break;
					// 	case '��Ԫ���' : $destDir.='��Ԫ���-��ְָ��.docx';
					// 					break;
					// 	default : $destDir.='���Ͷ���-��ְָ��.docx';
					// 			  break;
					// }
					// $content = "���ã���������ظ����鿴��ְָ����";
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
	 * ȷ������
	 */
	function editType_d($object) {
		try {
			$this->start_d();
			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict();
			$object['quitTypeName'] = $datadictDao->getDataNameByCode($object['quitTypeCode']);
			if($object['quitTypeCode']!="YGZTCZ"){//�Ǵ�ְ��������Ҫ����������
				$object['ExaStatus'] = '���';
			}else{
				$object['ExaStatus'] = 'δ�ύ';
			}

			if($_GET['actType'] != 'staff') {
				$object['state'] = '2';
			}

			$newId = parent :: edit_d($object ,true); //�޸�������Ϣ

			$row = $this->get_d($object['id']);

			$itemDao=new model_hr_leave_handitem();
			//ɾ��ԭ��������
			$itemDao->delete("mainId=".$row['id']);

			//�ж��Ƿ�Ϊ�Ǵ�ְ����
			if(is_array($object['handitem'])){
				$itemDao->createBatch($object['handitem'],array('mainId'=>$row['id']),'handContent');
				if($object['quitTypeCode'] != "YGZTCZ"){
					/**�ʼ�֪ͨԱ��*/
					$emailmsg .='<table border=1 cellspacing=0  width=100% bordercolorlight="#333333" bordercolordark="#efefef" style="font-size:14"> <tr align="center" style=";BACKGROUND-COLOR:#efefef"><td>�������豸��������</td><td>������</td></tr>';
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
					$emailDao -> emailInquiry(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'sendLeaveStaff', '������ְ����Ϊ��<font color=blue>'.$object['comfirmQuitDate'].'</font>������ָ�����幤���������Ͻ�������,����<font color=blue>'.$object['comfirmQuitDate'].'</font>ǰ���±��������������ɹ�������', '', $row['userAccount'], $emailmsg, 1);

					//�����ʼ�֪ͨ����
					$row=$this->get_d($object['id']);
					$this->sendMailToFinan($row);
					//������ְָ��
					// $companyName=$this->get_table_fields('oa_hr_personnel', "userNo='".$row['userNo']."'", 'companyName');
					// $filePath = str_replace('\\','/',UPLOADPATH);
					// $destDir = $filePath."oa_hr_leave_email/";			//������ַ
					// $title=$row['deptName'].'/'.$row['userName'].'��ְָ��';
					// switch($companyName) {//��ͬ��˾���͸���ͬ����Ա
					// 	case '��Դ��ͨ' : $destDir.='��Դ��ͨ-��ְָ��.docx';
					// 					break;
					// 	case '���ݱ�Ѷ' : $destDir.='���ݱ�Ѷ-��ְָ��.docx';
					// 					break;
					// 	case '���ݱ���' : $destDir.='���ݱ���-��ְָ��.docx';
					// 					break;
					// 	case '��Ԫ���' : $destDir.='��Ԫ���-��ְָ��.docx';
					// 					break;
					// 	default : $destDir.='���Ͷ���-��ְָ��.docx';
					// 			  break;
					// }
					// $content = "���ã���������ظ����鿴��ְָ����";
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
	 * ���������޸Ķ���
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			parent :: edit_d($object, true);
			if(is_array($object['handitem'])){
				$itemDao=new model_hr_leave_handitem();
				//ɾ���ӱ�����ݣ������������
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
	 * ���������޸Ķ���
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
	 * �����ʼ�֪ͨ����
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
				       <td>��ְ�����</td><td>��ְԱ��</td><td>������˾</td><td>��������</td><td>ְλ</td><td>��ְ����</td><td>���ʽ����ֹ����</td>
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
		$addmsg .= "<font color='blue'>˵����</font>";
		$addmsg .= "<br>���������ȴ�����ְԱ���ı�������";
		//��ȡĬ�Ϸ�����
		include (WEB_TOR . "model/common/mailConfig.php");
		$mailstr='';
		$mailstr.=$mailUser['leave']['sendUserId'];
		switch($companyName){//��ͬ���Ʒ��͸���ͬ����Ա
			case '��Դ��ͨ' : $mailstr.=','.$mailUser['leave_shiyuan']['sendUserId'];
							break;
			case '���ݱ�Ѷ' : $mailstr.=','.$mailUser['contract_bx']['TO_ID'];
							break;
			case '���ݱ���' : $mailstr.=','.$mailUser['leave_beiruan']['sendUserId'];
							break;
			case '��Ԫ���' : $mailstr.=','.$mailUser['leave_dingyuan']['sendUserId'];
							break;
			default : $mailstr.=','.$mailUser['leave_dingli']['sendUserId'];			//��˾�жϣ�Ĭ�����Ͷ���
					  break;
		}
		//�ж��Ƿ�Ϊ�����߻�Ӫ����
		if($deptId == '35') {//������
			$mailstr.=','.$mailUser['leave_fuwu']['sendUserId'];
		}else if($deptId == '37'){//Ӫ����
			$mailstr.=','.$mailUser['leave_yingxiao']['sendUserId'];
		}
		$emailDao = new model_common_mail();
		$emailDao -> emailInquiry(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'sendLeave', 'Ա����ְ֪ͨ', '', $mailstr, $addmsg, 1);
		return true;
	}

	/**
	 * ������������
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		if($folowInfo['examines']=="ok"){  //����ͨ��
			$objId = $folowInfo ['objId'];
			$obj = $this->get_d ( $objId );
			$handitemDao = new model_hr_leave_handitem();
			$rows['handitem'] = $handitemDao->findAll(array('mainId'=>$objId),null,'handContent,recipientName,recipientId');
			$this->sendLeaveMail($obj,$rows);
		}
	}
	
	/**
	 * ���������ʼ�
	 */
	function sendLeaveMail($obj,$object) {
		$filePath = str_replace('\\','/',UPLOADPATH);
		$destDir = $filePath."oa_hr_leave_email/";			//������ַ
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
				       <td>��ְ�����</td><td>��ְԱ��</td><td>������˾</td><td>��������</td><td>ְλ</td><td>��ְ����</td><td>���ʽ����ֹ����</td>
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
		$addmsg .= "<font color='blue'>˵����</font>";
		$addmsg .= "<br>���������ȴ�����ְԱ���ı�������";
		//��ȡĬ�Ϸ�����
		include (WEB_TOR . "model/common/mailConfig.php");
		$mailstr='';
		$mailstr.=$mailUser['leave']['sendUserId'];
		switch($companyName){//��ͬ���Ʒ��͸���ͬ����Ա
			case '��Դ��ͨ' : $mailstr.=','.$mailUser['leave_shiyuan']['sendUserId'];
							 $destDir.='��Դ��ͨ-��ְָ��.docx';
							break;
			case '���ݱ�Ѷ' : $mailstr.=','.$mailUser['contract_bx']['TO_ID'];
							 $destDir.='���ݱ�Ѷ-��ְָ��.docx';
							break;
			case '���ݱ���' : $mailstr.=','.$mailUser['leave_beiruan']['sendUserId'];
							 $destDir.='���ݱ���-��ְָ��.docx';
							break;
			case '��Ԫ���' : $mailstr.=','.$mailUser['leave_dingyuan']['sendUserId'];
							 $destDir.='��Ԫ���-��ְָ��.docx';
							break;
			default : $mailstr.=','.$mailUser['leave_dingli']['sendUserId'];			//��˾�жϣ�Ĭ�����Ͷ���
					  $destDir.='���Ͷ���-��ְָ��.docx';
					  break;
		}
		//�ж��Ƿ�Ϊ�����߻�Ӫ����
		if($deptId=='35'){//������
			$mailstr.=','.$mailUser['leave_fuwu']['sendUserId'];
		}else if($deptId=='37'){//Ӫ����
			$mailstr.=','.$mailUser['leave_yingxiao']['sendUserId'];
		}
		$emailDao = new model_common_mail();
		$emailDao -> emailInquiry(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'sendLeave', 'Ա����ְ֪ͨ', '', $mailstr, $addmsg, 1);

		/**�ʼ�֪ͨԱ��*/
		$emailmsg .='<table border=1 cellspacing=0  width=100% bordercolorlight="#333333" bordercolordark="#efefef" style="font-size:14"> <tr align="center" style=";BACKGROUND-COLOR:#efefef"><td>�������豸��������</td><td>������</td></tr>';
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
		$emailDao -> emailInquiry(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'sendLeaveStaff', '������ְ����������ͨ������ְ����Ϊ��'.$obj['comfirmQuitDate'].'������ָ�����幤���������Ͻ�������,����'.$obj['comfirmQuitDate'].'ǰ���±��������������ɹ�������', '', $obj['userAccount'], $emailmsg, 1);

		/**������ְָ��*/
		// $title=$obj['deptName'].'/'.$obj['userName'].'��ְָ��';
		// $content = "���ã���������ظ����鿴��ְָ��";
		// $emailDao->mailWithFile($title,$obj['userAccount'],$content,null,$destDir);
		return true;
	}

	//�����ʼ�
	function sendEmail($obj){
		try{
			$object['id']=$obj['id'];
			$object['emailSate']=1;
			$id=$this->updateById($object);
			$mailDao = new model_common_mail();
			$title=$obj['deptName'].'/'.$obj['userName'].'�������ְ';
			$mailDao->mailClear($title,$obj['TO_ID'],$obj['mailContent'],null);
			return true;
		}catch(exception $e){
			return false;
		}
	}

	//�����ʼ�-��ְָ��
	function sendEmailguide($obj){
		try{
			$object['id']=$obj['id'];
			$object['emailSate']=1;
			$id=$this->updateById($object);
			$mailDao = new model_common_mail();
			$title=$obj['deptName'].'/'.$obj['userName'].'��ְָ��';
			//�������ʼ�����
			$mailDao->mailWithFile($title,$obj['TO_CCID'],$obj['mailContent'],null,$obj['attachment']);
			//�ռ������ʼ�����
			$email = new includes_class_sendmail();
			$obj['mailContent'] = nl2br($obj['mailContent']);
			$email->send($title,$obj['mailContent'],$obj['receiver'],null,null,'GBK','default',NULL,$obj['attachment']);
			return true;
		}catch(exception $e){
			return false;
		}
	}

	/**
	 * �ı�״̬�����ʼ�֪ͨ
	 */
	function getState($id ,$state = 1) {
		$object['id'] = $id;
		$object['state'] = $state;
		$flag = $this->updateById($object);
		return $flag;
	}

	/**
	 * �ı�״̬���ʼ�֪ͨ
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

	/**�ύ��ְ���뷢���ʼ�*/
	function mailForSubmit($id){
		//��ȡ��ְ������Ϣ
		$row = $this->get_d($id);
		$companyName = $this->get_table_fields('oa_hr_personnel', "userNo='".$row['userNo']."'", 'companyName');
		//�����ʼ�֪ͨHR
		include (WEB_TOR . "model/common/mailConfig.php");
		$emailDao = new model_common_mail();
		$mailstr = $mailUser['leave_dingli']['sendUserId'];		//���ݹ�˾��ѡ��֪ͨ�ˣ�Ĭ�����Ͷ���
		$addMsg = "���,".$row['deptName']."��".$row['userName']."�ύ����ְ����,��鿴��";
		switch ($companyName){
			case '��Դ��ͨ' : $mailstr = $mailUser['leave_shiyuan']['sendUserId'];
							break;
			case '���ݱ�Ѷ' : $mailstr = $mailUser['contract_bx']['TO_ID'];
							break;
			case '���ݱ���' : $mailstr = $mailUser['leave_beiruan']['sendUserId'];
							break;
			case '��Ԫ���' : $mailstr = $mailUser['leave_dingyuan']['sendUserId'];
							break;
		}
		$emailDao->mailClear($row['deptName'].'-'.$row['userName'].'�ύ����ְ����', $mailstr, $addMsg, null);
	}

	/**��ְ��ӡ����*/
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
	 * ������ְԱ������
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
				$obj['employeesState'] = "YGZTLZ"; //����ǰԱ��������ְ״̬
				//��ȡ�����Ϣ���µ����µ���
				$obj['quitTypeCode'] = $dataArr['quitTypeCode'];
				$obj['quitReson'] = $dataArr['quitReson'];
				$obj['quitDate']= $dataArr['comfirmQuitDate'];
				$obj['staffState'] = $dataArr['quitTypeCode'];
				//��ȡID
				$personelId = $this->get_table_fields('oa_hr_personnel',"userNo='".$dataArr['userNo']."'",'id');
				$obj['id'] = $personelId;
				$personel = new model_hr_personnel_personnel();
				$result = $personel->updataLeave_d($obj); //���µ�����¼
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
	 * ��ְ���룬��������
	 */
	 function updateExaStatus_d($object){
	 	$flag = $this->updateById($object);
	 	return $flag;
	 }

	 /**
	  * ������ӡ����ȡѡ�е�����
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
	 * ��ְ������
	 */
	function back_d( $obj ) {
		try {
			$this->start_d();

			$this->updateById(array("id"=>$obj['id'] ,"state"=>0));

			//���ʼ�֪ͨԱ��
			$object = $this->get_d( $obj['id'] );
			$mailDao = new model_common_mail();
			$mailContent = '��ã������ְ�����ѱ���أ�ԭ�����£�<br>'.$obj['backReason'];
			$mailDao->mailClear('�����ְ����' ,$object['userAccount'] ,$mailContent ,null);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �༭��ʵ��ְԭ��
	 */
	function editReal_d( $obj ) {
		try {
			$this->start_d();

			$rs = parent::edit_d($obj ,true);

			//ͬ���������µ�����ʵ��ְԭ��ͺ�����
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
	 * excel�����޸���ְ��Ϣ
	 */
	function editLeaveInfoExcel_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType = $_FILES["inputExcel"]["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$inArr = array();//��������
		$linkmanArr = array();//��������

		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear($filename ,$temp_name);
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
					$actNum = $key + 1;
					if( empty($val[0]) ){
						continue;
					}else{
						//��������
						$inArr = array();

						//Ա�����
						if(!empty($val[0]) && trim($val[0]) != '') {
							$inArr['userNo'] = trim($val[0]);
							$tmp = $this->find(array('userNo'=>$inArr['userNo']));
							if (!$tmp) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!Ա����������ְ����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							} else {
								if($tmp['ExaStatus'] != '���') {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!Ա����ְ��������δ���</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
								if($tmp['state'] == '4') {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!Ա����ְ�����ѹر�</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['id'] = $tmp['id'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!Ա�����Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//$val[1]Ա�������ɲ���

						//��ְ����
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

						//���ʽ����ֹ����
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

						//����֧������
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

						//�칫���״̬
						if(!empty($val[5]) && trim($val[5]) != ''){
							$val[5] = trim($val[5]);
							if($val[5] == 'δ�ر�') {
								$inArr['softSate'] = 0;
							} else if($val[5] == '�ѹر�') {
								$inArr['softSate'] = 1;
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�칫���״̬������</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						//�籣��Ա
						if(!empty($val[6]) && trim($val[6]) != ''){
							$inArr['pensionReduction'] = trim($val[6]);
						}

						//�������Ա
						if(!empty($val[7]) && trim($val[7]) != ''){
							$inArr['fundReduction'] = trim($val[7]);
						}

						//�ù���ֹ
						if(!empty($val[8]) && trim($val[8]) != ''){
							$val[8] = trim($val[8]);
							if($val[8] == '��' || $val[8] == '��') {
								$inArr['employmentEnd'] = $val[8];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�ù���ֹ������</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						$rs = parent::edit_d($inArr ,true);

						if($rs){
							$tempArr['result'] = '����ɹ�';
						}else{
							$tempArr['result'] = '<font color=red>����ʧ��</font>';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}
}
?>