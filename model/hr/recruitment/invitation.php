<?php
/**
 * @author Administrator
 * @Date 2012年7月18日 星期三 15:18:37
 * @version 1.0
 * @description:面试通知 Model层
 */
class model_hr_recruitment_invitation  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitment_invitation";
		$this->sql_map = "hr/recruitment/invitationSql.php";
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
		1 => array (
				'statusEName' => 'interviewing',
				'statusCName' => '面试中',
				'key' => '1'
				),
				2 => array (
				'statusEName' => 'giveup',
				'statusCName' => '放弃',
				'key' => '2'
				),
				3 => array (
				'statusEName' => 'getout',
				'statusCName' => '淘汰',
				'key' => '3'
				),
				4 => array (
				'statusEName' => 'wait',
				'statusCName' => '待入职',
				'key' => '4'
				),
				5 => array (
				'statusEName' => 'passed',
				'statusCName' => '入职',
				'key' => '5'
				)
				);
				parent::__construct ();
	}
	/**
	 * 添加对象
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
							<td><b>应聘者</b></td>
							<td><b>应聘职位</b></td>
							<td><b>用人部门</b></td>
							<td><b>面试时间</b></td>
							<td><b>面试地点</b></td>
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
			$addmsg .="<br>用人面试官：<br><font color=blue>$interviewerName</font><br>人力面试官：<br><font color=blue>$hrInterviewer</font>";
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
	 *面试通知邮件通知面试官
	 */
	function Mailtothem($toid,$tomsg){
		$emailDao = new model_common_mail();
		$emailDao -> emailInquiry(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'interview_notice', '该邮件为面试通知面试官提醒', '', $toid, $tomsg, 1);
	}

	/**
	 * 面试通知发送邮件
	 */
	function thisMail_d($info){
		$emailDao = new model_common_mail();
		$emailDao->InterviewEmail($_SESSION['USERNAME'],$_SESSION['EMAIL'],$info['title'],$info['toMail'],$info['content'],$info['toccMailId'],$info['tobccMail'],$info['isSender'],$info['attachment']);

	}
	/**
	 * 通过resumeId检查简历是否以发送过面试通知
	 *
	 */
	function ajaxCheckExistsResume($resumeId){
		return $this->findCount(array('resumeId'=>$resumeId));
	}

}
?>