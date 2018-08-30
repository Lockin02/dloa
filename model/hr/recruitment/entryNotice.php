<?php
/**
 * @author Administrator
 * @Date 2012��7��27�� ������ 13:22:05
 * @version 1.0
 * @description:��ְ֪ͨ Model��
 */
 class model_hr_recruitment_entryNotice  extends model_base {

	function __construct() {
		include (WEB_TOR."model/common/mailConfig.php");
		$this->tbl_name = "oa_hr_recruitment_entrynotice";
		$this->sql_map = "hr/recruitment/entryNoticeSql.php";
		$this->mailArr = $mailUser[$this->tbl_name];
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
			0 => array (
				'statusEName' => 'save',
				'statusCName' => '����',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'waitting',
				'statusCName' => '����ְ',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'onwork',
				'statusCName' => '����ְ',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'giveup',
				'statusCName' => '������ְ',
				'key' => '3'
			)
		);
		parent::__construct ();
	}

	/**
	 * ���Ӷ���
	 */
	function add_d($object ,$isAddInfo = false) {
		try{
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			if($_GET['isSave'] != 1) { //�Ǳ���
				$interviewDao = new model_hr_recruitment_interview();
				$interviewDao->updateById(array("id" => $object['parentId'] ,"state" => $interviewDao->statusDao->statusEtoK("hassend")));
				$interviewDao->mailToXZ_d($object['parentId'] ,$object); //�ʼ�����������

				$object['state'] = $this->statusDao->statusEtoK ( 'waitting' ); //��Ҫ�����ʼ�������Ϊ����ְ
				if($object['interviewType'] == 1) {
					if($object['applyResumeId']) {//�����ⷢ����ְ����û��applyResumeId add chenrf
						$applyResumeDao = new model_hr_recruitment_applyResume();
						$applyResumeDao->updateField("id=".$object['applyResumeId'],'state','4');
					}
				} else if ($object['interviewType'] == 2) {
					$recomResumeDao = new model_hr_recruitment_recomResume();
					$recomResumeDao->updateField("id=".$object['applyResumeId'],'state','4');
				}
				//��������֪ͨ״̬
				if($object['invitationId'] > 0) {
					$invitationDao = new model_hr_recruitment_invitation();
					$invitationDao->updateField("id=".$object['invitationId'],'state','4');
				}
				//ͬʱ������Ա������Ĵ���ְ��������Ƹ����
				if ($object['sourceId'] != '') {
					$apply = new model_hr_recruitment_apply();
					$apply->updateBeEntryNum($object['sourceId'],1);
				}
				//�����ڲ��Ƽ�����״̬
				if ($object['recommendId'] > 0) {
					$recommendDao = new model_hr_recruitment_recommend();
					$recommendDao->updateById(array('id' => $object['recommendId'] ,'state' => 8));
				}
			}
			$object['formCode'] = 'TZ'.date("YmdHis");
			$object['ExaStatus'] = "δ���";
			$object['staffFileState'] = 0;
			$object['contractState'] = 0;
			$object['accountState'] = 0;
			$object['formDate'] = date("Y-m-d");
			$object['hrSourceType1Name'] = $datadictDao->getDataNameByCode($object['hrSourceType1']);
			$object['useHireTypeName'] = $datadictDao->getDataNameByCode($object['useHireType']);
			$object['postTypeName'] = $datadictDao->getDataNameByCode($object['postType']);
			$object['wageLevelName'] = $datadictDao->getDataNameByCode($object['wageLevelCode']);

			$id = parent::add_d($object ,true);

			$this->commit_d();
			return $id;
		} catch(exception $e){
			$this->rollBack();
			return null;
		}
	}

	/**
	 * �༭����
	 */
	function edit_d($object ,$isAddInfo = false) {
		try{
			$this->start_d();

			if($_GET['isSave'] != 1) { //�Ǳ���
				$object['state'] = $this->statusDao->statusEtoK ( 'waitting' ); //��Ҫ�����ʼ�������Ϊ����ְ

				$interviewDao = new model_hr_recruitment_interview();
				$interviewDao->updateById(array("id" => $object['parentId'] ,"state" => $interviewDao->statusDao->statusEtoK("hassend")));
				$interviewDao->mailToXZ_d($object['parentId'] ,$object); //�ʼ�����������

				if($object['interviewType'] == 1) {
					if($object['applyResumeId']) { //�����ⷢ����ְ����û��applyResumeId add chenrf
						$applyResumeDao = new model_hr_recruitment_applyResume();
						$applyResumeDao->updateField("id=".$object['applyResumeId'],'state','4');
					}
				} else if($object['interviewType'] == 2) {
					$recomResumeDao = new model_hr_recruitment_recomResume();
					$recomResumeDao->updateField("id=".$object['applyResumeId'],'state','4');
				}

				//��������֪ͨ״̬
				if($object['invitationId'] > 0) {
					$invitationDao = new model_hr_recruitment_invitation();
					$invitationDao->updateField("id=".$object['invitationId'],'state','4');
				}

				//ͬʱ������Ա������Ĵ���ְ��������Ƹ����
				if ($object['sourceId'] != '') {
					$apply = new model_hr_recruitment_apply();
					$apply->updateBeEntryNum($object['sourceId'] ,1);
				}

				//�����ڲ��Ƽ�����״̬
				if ($object['recommendId'] > 0) {
					$recommendDao = new model_hr_recruitment_recommend();
					$recommendDao->updateById(array('id' => $object['recommendId'] ,'state' => 8));
				}
			}

			parent::edit_d($object ,true);

			$this->commit_d();
			return $object['id'];
		} catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/*
	 * ����֪ͨ��ְ�ʼ�
	 * ���͸�   ���� Ĭ�Ϸ��Ͷ��� ��ӦƸ��
	 */
	function postEmail_d($object ,$info) {
		$notice = $object;
		$mailRow = $this->mailArr;
		$emailArr = array();
		$emailArr['issend'] = 'y';
		$emailArr['TO_ID'] = $mailRow['sendUserId'];
		$emailArr['TO_NAME'] = $mailRow['sendName'];
		if ($info['toMail']) {
			$addmsg = $info['content'];
			$somemsg = "";
			$userName = $notice['userName'];
			$positionName = $notice ['hrJobName'];
			$deptName = $notice ['deptName'];
			$entryDate = $notice['entryDate'];
			$useAreaName = $notice ['useAreaName'];
			$somemsg .= <<<EOT
			<table border=1 cellspacing=0 width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center >
				<thead>
					<tr align="center">
						<td><b>����</b></td>
						<td><b>ְλ</b></td>
						<td><b>���˲���</b></td>
						<td><b>��ְʱ��</b></td>
						<td><b>��������</b></td>
					</tr>
				</thead>
				<tbody>
				<tr align="center" >
						<td>$userName</td>
						<td>$positionName</td>
						<td>$deptName</td>
						<td>$entryDate</td>
						<td>$useAreaName</td>
					</tr>
				</tbody>
			</table>
EOT;
			$addmsg = $addmsg.$somemsg;

			$emailDao = new model_common_mail();
			$emailDao->offerEmail($_SESSION['USERNAME'] ,$_SESSION['EMAIL'] ,$info['title'] ,$info['toMail'] ,$info['content'] ,$info['toccMailId'] ,'' ,$info['isSender'] ,$info['attachment']);
		}
	}


	/*
	 * ����֪ͨ��ְ�ʼ�
	 * ���͸�   ���� Ĭ�Ϸ��Ͷ��� ��ӦƸ��
	 */
	function postDateEmail_d($object) {
		$notice = $this->get_d($object['id']);
		$notice['entryDate'] = $object['entryDate'];
		$notice['email'] = $object['email'];
		$mailRow = $this->mailArr;
		$emailArr = array();
		$emailArr['issend'] = 'y';
		$emailArr['TO_ID'] = $mailRow['sendUserId'];
		$emailArr['TO_NAME'] = $mailRow['sendName'];
		if ($emailArr['TO_ID']) {
			$somemsg = "������Ա�ѿ�ͷ����offer���������£���Ԥ��ְǰ5���������ڻ������������Ա׼��Э�������ְ���ˣ���֪Ϥ��<br>��ְ�����޸����£�<br>";
			$userName = $notice['userName'];
			$positionName = $notice ['hrJobName'];
			$deptName = $notice ['deptName'];
			$entryDate = $notice['entryDate'];
			$useAreaName = $notice ['useAreaName'];
			$phone = $notice ['phone'];
			$useDemandEqu = $notice ['useDemandEqu'];
			$somemsg .=  <<<EOT
			<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center >
				<thead>
					<tr align="center">
						<td><b>����</b></td>
						<td><b>ְλ</b></td>
						<td><b>��������</b></td>
						<td><b>���˲���</b></td>
						<td><b>��ְʱ��</b></td>
						<td><b>��������</b></td>
						<td><b>��ϵ��ʽ</b></td>
					</tr>
				</thead>
				<tbody>
				<tr align="center" >
						<td>$userName</td>
						<td>$positionName</td>
						<td>$useDemandEqu</td>
						<td>$deptName</td>
						<td><font color=blue>$entryDate</font></td>
						<td>$useAreaName</td>
						<td>$phone</td>
					</tr>
				</tbody>
				</table>
EOT;

			$emailDao = new model_common_mail();
			$emailDao->emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'hr_recruitment_entryNotice_Date', '���ʼ�Ϊ��ְʱ���޸���Ϣ', '', $notice['email']['TO_ID'], $somemsg, 1);
		}
	}

	/**
	 * �����ְ
	 */
	function doneEntry_d($id){
		try{
			$this->start_d();
			$obj = $this->get_d($id);
			$applyresume = new model_hr_recruitment_applyResume();
			$apply = new model_hr_recruitment_apply();
			$recomresume = new model_hr_recruitment_recomResume();
			$recommend = new model_hr_recruitment_recommend();
			$resume = new model_hr_recruitment_resume();
			if($obj['interviewType'] == 1) {
				$sourceresume = $applyresume->update(array("id"=>$obj['applyResumeId']),array("state"=>5));
			} else {
				$sourceresume = $recomresume->update(array("id"=>$obj['applyResumeId']),array("state"=>5));
			}
			if ($obj['invitationId'] > 0) {
				$invitationDao = new model_hr_recruitment_invitation();
				$invitationDao->updateField("id=".$obj['invitationId'],'state','5');
			}
			$resume->update(array("id"=>$obj['resumeId']),array("resumeType"=>1));     // add chenrf 20150523
			$obj['state'] = 2;
			$this-> updateById ( $obj );
			$this->commit_d();
			return 1;
		} catch (exception $e) {
			$this->rollBack();
			return 0;
		}
	}

	/**
	 * ������ְ
	 */
	function cancelEntry_d($object){
		try {
			$this->start_d();

			$emailArr['issend'] = 'y';
			$emailArr['TO_ID'] = $mailRow['sendUserId'];
			$emailArr['TO_NAME'] = $mailRow['sendName'];
			$applyresume = new model_hr_recruitment_applyResume();
			$apply = new model_hr_recruitment_apply();
			$recomresume = new model_hr_recruitment_recomResume();
			$recommend = new model_hr_recruitment_recommend();
			$resume = new model_hr_recruitment_resume();

			$obj = $this->get_d($object['id']);

			if($obj['sourceId'] > 0) {
				$sourceresume = $applyresume->update(array("id" => $obj['applyResumeId']) ,array("state" => 6));
				if ($obj['state'] != 3) { //Ԥ���ظ���������������Ա�������ְ��������
					$apply->updateBeEntryNum($obj['sourceId'] ,-1);
				}
			} else {
				$sourceresume = $recomresume->update(array("id" => $obj['applyResumeId']) ,array("state" => 6));
			}

			//��������֪ͨ״̬
			if($obj['invitationId'] > 0) {
				$invitationDao = new model_hr_recruitment_invitation();
				$invitationDao->updateField("id=".$obj['invitationId'] ,'state' ,'2');
			}

			//�����ڲ��Ƽ�����״̬
			if ($obj['recommendId'] > 0) {
				$recommendDao = new model_hr_recruitment_recommend();
				$recommendDao->updateById(array('id' => $obj['recommendId'] ,'state' => 9));
			}

			$somemsg = '��ְ֪ͨ��ţ�<font color=blue>'.$obj['formCode'].'</font><br>';
			$somemsg .= 'Ա�����:<font color=blue>'.$obj['userNo'].'</font><br>';
			$somemsg .= 'Ա������:<font color=blue>'.$obj['userName'].'</font><br>';
			$somemsg .= '��ְ�����<font color=blue>���������ְ</font><br>';
			$somemsg .= '������ְԭ��<font color=blue>'.$object['cancelReason'].'</font>';
			$emailDao = new model_common_mail();
			$emailDao->emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'hr_recruitment_entryNotice', '���ʼ�ΪԱ��������ְ֪ͨ', '', $emailArr['TO_ID'], $somemsg, 1);

			parent::edit_d($object ,true);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �༭������ְԭ��
	 */
	function editCancel_d($obj) {
		try {
			$this->start_d();

			parent::edit_d($obj ,true);

			if ($obj['mailInfo'] == 'yes') { //����Ƹ�޸ķ�����ְԭ����Ҫ���ʼ�֪ͨ������
				$this->editCancelMail_d($obj['id']);
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �༭������ְԭ���ʼ�֪ͨ
	 */
	function editCancelMail_d($id) {
		try {
			$obj = $this->get_d($id);
			$content = <<<EOT
				<br>
				��    ����<font color="blue">$obj[userName]</font><br>
				��    �ţ�$obj[deptName]<br>
				ְλ���ͣ�$obj[postTypeName]<br>
				ְλ���ƣ�$obj[hrJobName]<br>
				������ְԭ��$obj[cancelReason]<br><br>
EOT;

			$this->mailDeal_d('entrynotice_giveUp' ,null ,array('userName' => $obj['userName'] ,'content' => $content));
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * ��д��ְԭ��
	 */
	function addDepart_d($obj) {
		try {
			$this->start_d();

			parent::edit_d($obj ,true);

			if ($obj['mailInfo'] == 'yes') { //����Ƹ��д��ְԭ����Ҫ���ʼ�֪ͨ������
				$this->addDepartMail_d($obj['id']);
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��д��ְԭ���ʼ�֪ͨ
	 */
	function addDepartMail_d($id) {
		try {
			$obj = $this->get_d($id);
			$content = <<<EOT
				<br>
				��    ����<font color="blue">$obj[userName]</font><br>
				��    �ţ�$obj[deptName]<br>
				ְλ���ͣ�$obj[postTypeName]<br>
				ְλ���ƣ�$obj[hrJobName]<br>
				��ְʱ�䣺$obj[entryDate]<br>
				��ְԭ��$obj[departReason]<br><br>
EOT;

			$this->mailDeal_d('entrynotice_depart' ,null ,array('userName' => $obj['userName'] ,'content' => $content));
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * �����û���Ż�ȡ��Ϣ
	 */
	function getInfoByUserNo_d($userNo){
		$this->searchArr = array ('userNo' => $userNo );
		$personnelRow= $this->listBySqlId ( "select_default" );
		return $personnelRow['0'];
	}

	/**
	 * �����û���Ż�ȡ��Ϣ
	 */
	function getInfoByUserID_d($userId){
		$this->searchArr = array ('userAccount' => $userId );
		$personnelRow= $this->listBySqlId ( "select_default" );
		return $personnelRow['0'];
	}

	/**
	 * �����û���Ż�ȡ��Ϣ(����������������Ϣ)
	 */
	function getAllInfoByUserID_d($userId){
		$this->searchArr = array ('userAccount' => $userId );
		$personnelRow= $this->listBySqlId ( "select_list" );
		return $personnelRow['0'];
	}

	/**
	 * ����֪ͨ�ʼ�
	 */
	function mailForCompany($object){
		$flag = true;
		if($object['email']['TO_ID']){
			$emailDao = new model_common_mail();
			$flag=$emailDao->mailGeneral($object['title'], $object['email']['TO_ID'], $object['content'], null);
		}
		return $flag;
	}

	/**
	 * �����ְ����ã���ͨ�ʺ���Ϊ�����ְ��
	 * 1��������ְID���´���ְ����
	 * 2�������ڲ��Ƽ�״̬
	 */
	function updateBeEntryNumById_d( $id ) {
		try{
			$this->start_d();
			$obj = $this->get_d( $id );

			if($obj['sourceId'] > 0) {
				$applyDao = new model_hr_recruitment_apply();
				$rs = $applyDao->updateEntryNum($obj['sourceId'] ,1); //������Ա�������ְ����+1
				if ($rs) {
					$applyObj = $applyDao->get_d($obj['sourceId']);
					//����ְ����������Ƹ����Ϊ0����Ա����״̬��Ϊ���
					if ($applyObj['beEntryNum'] == 0
							&& ($applyObj['needNum'] - $applyObj['entryNum'] - $applyObj['beEntryNum'] - $applyObj['stopCancelNum'] == 0)) {
						$applyDao->updateById(array('id' => $obj['sourceId'] ,'state' => 4));
					}
				}
			}

			//�����ڲ��Ƽ�����״̬
			if ($obj['recommendId'] > 0) {
				$recommendDao = new model_hr_recruitment_recommend();
				$recommendDao->updateById(array('id' => $obj['recommendId'] ,'state' => 5));
			}

			$this->commit_d();
			return true;
		} catch(exception $e) {
			$this->rollBack();
			return false;
		}
	}

 }
?>