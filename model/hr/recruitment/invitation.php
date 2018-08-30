<?php
/**
 * @author Administrator
 * @Date 2012��7��18�� ������ 15:18:37
 * @version 1.0
 * @description:����֪ͨ Model��
 */
class model_hr_recruitment_invitation  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitment_invitation";
		$this->sql_map = "hr/recruitment/invitationSql.php";
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
		1 => array (
				'statusEName' => 'interviewing',
				'statusCName' => '������',
				'key' => '1'
				),
				2 => array (
				'statusEName' => 'giveup',
				'statusCName' => '����',
				'key' => '2'
				),
				3 => array (
				'statusEName' => 'getout',
				'statusCName' => '��̭',
				'key' => '3'
				),
				4 => array (
				'statusEName' => 'wait',
				'statusCName' => '����ְ',
				'key' => '4'
				),
				5 => array (
				'statusEName' => 'passed',
				'statusCName' => '��ְ',
				'key' => '5'
				)
				);
				parent::__construct ();
	}
	/**
	 * ��Ӷ���
	 */
	function add_d($object, $isAddInfo = false) {
		try{
			$this->start_d();
			$mailinfo = $_POST["interMail"];
			$object['formCode'] = date ( "YmdHis" );
			$object['formDate'] = date ( "Y-m-d" );
			$object['isAddInterview'] = 0;
			$datadict = new model_system_datadict_datadict();
			$object['postTypeName'] = $datadict->getDataNameByCode($object['postType']);
			$object['state'] = $this->statusDao->statusEtoK ( 'interviewing' );
			$applicantName = $object['applicantName'];
			$positionsName = $object['positionsName'];
			$deptName = $object['deptName'];
			$interviewDate = $object['interviewDate'];
			$interviewPlace = $object['interviewPlace'];
			$interviewer = $object['$interviewer'];
			$interviewerName = $object['interviewerName'];
			$hrInterviewer = $object['hrInterviewer'];
			$addmsg =  <<<EOT
				<table width="500px">
					<thead>
						<tr align="center">
							<td><b>ӦƸ��</b></td>
							<td><b>ӦƸְλ</b></td>
							<td><b>���˲���</b></td>
							<td><b>����ʱ��</b></td>
							<td><b>���Եص�</b></td>
						</tr>
					</thead>
					<tbody>
					<tr align="center" >
							<td>$applicantName</td>
							<td>$positionsName</td>
							<td>$deptName</td>
							<td>$interviewDate</td>
							<td>$interviewPlace</td>
						</tr>
					</tbody>
					</table>
EOT;
			$mailinfo['content'] .= $addmsg;
			$addmsg .="<br>�������Թ٣�<br><font color=blue>$interviewerName</font><br>�������Թ٣�<br><font color=blue>$hrInterviewer</font>";
			//echo $addmsg;
			//			$this -> thisMail_d($mailinfo);
			$newId = parent::add_d($object,true);
			if($newId){
				$invitation = $this->get_d($newId);
				$interviewer = new model_hr_recruitment_interviewer();
				$interviewerId = explode(",", $object['interviewerId']);
				$interviewerName = explode(",", $object['interviewerName']);
				$hrInterviewerId = explode(",", $object['hrInterviewerId']);
				$hrInterviewer = explode(",", $object['hrInterviewer']);
				for ($i=0; $i < count($interviewerId); $i++) {
					$interviewer->add_d(array('parentId'=>$invitation['id'],
										  'parentCode'=>$invitation['formCode'],
										  'interviewerType'=>1,
										  'interviewerId'=>$interviewerId[$i],
										  'interviewerName'=>$interviewerName[$i]
					));
					$this->Mailtothem($interviewerId[$i], $addmsg);
				}
				for ($i=0; $i < count($hrInterviewerId); $i++) {
					$interviewer->add_d(array('parentId'=>$invitation['id'],
										  'parentCode'=>$invitation['formCode'],
										  'interviewerType'=>2,
										  'interviewerId'=>$hrInterviewerId[$i],
										  'interviewerName'=>$hrInterviewer[$i]
					));
					$this->Mailtothem($hrInterviewerId[$i], $addmsg);
				}
			}
			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return null;
		}
	}
	/**
	 *����֪ͨ�ʼ�֪ͨ���Թ�
	 */
	function Mailtothem($toid,$tomsg){
		$emailDao = new model_common_mail();
		$emailDao -> emailInquiry(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'interview_notice', '���ʼ�Ϊ����֪ͨ���Թ�����', '', $toid, $tomsg, 1);
	}

	/**
	 * ����֪ͨ�����ʼ�
	 */
	function thisMail_d($info){
		$emailDao = new model_common_mail();
		$emailDao->InterviewEmail($_SESSION['USERNAME'],$_SESSION['EMAIL'],$info['title'],$info['toMail'],$info['content'],$info['toccMailId'],$info['tobccMail'],$info['isSender'],$info['attachment']);

	}
	/**
	 * ͨ��resumeId�������Ƿ��Է��͹�����֪ͨ
	 *
	 */
	function ajaxCheckExistsResume($resumeId){
		return $this->findCount(array('resumeId'=>$resumeId));
	}

}
?>