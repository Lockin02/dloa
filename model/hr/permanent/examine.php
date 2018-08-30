<?php
/**
 * @author Administrator
 * @Date 2012��8��6�� 21:39:45
 * @version 1.0
 * @description:Ա��ת������������ Model��
 * 1������
 * 2����ʦ���
 * 3���쵼���
 * 4���ܼ����
 * 5���������
 * 6������ȷ��
 * 7�����
 */
 class model_hr_permanent_examine  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_permanent_examine";
		$this->sql_map = "hr/permanent/examineSql.php";
		include (WEB_TOR . "model/common/mailConfig.php");
		$this->mailArr=$mailUser[$this->tbl_name];
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
			1 => array (
				'statusEName' => 'save',
				'statusCName' => '����',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'mastercheck',
				'statusCName' => '��ʦ���',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'leadercheck',
				'statusCName' => '�쵼���',
				'key' => '3'
			),
			4 => array (
				'statusEName' => 'directorcheck',
				'statusCName' => '�ܼ����',
				'key' => '4'
			),
			5 => array (
				'statusEName' => 'hrcheck',
				'statusCName' => '�������',
				'key' => '5'
			),
			6 => array (
				'statusEName' => 'lastcheck',
				'statusCName' => 'Ա��ȷ��',
				'key' => '6'
			),
			7 => array (
				'statusEName' => 'finish',
				'statusCName' => '���',
				'key' => '7'
			),
			8 => array (
				'statusEName' => 'close',
				'statusCName' => '�ر�',
				'key' => '8'
			),
			9 => array (
				'statusEName' => 'notfilled',
				'statusCName' => 'δ��д',
				'key' => '9'
			)
		);
		parent::__construct ();
	}
	/*
	 * ֱ���ܼ����
	 */
	function setApply($object){
//			$object['status'] = $this->statusDao->statusEtoK ( 'hrcheck' );
			$datadict = new model_system_datadict_datadict();
			$object['permanentType'] = $datadict->getDataNameByCode($object['permanentTypeCode']);
			$newid = parent::edit_d($object,true);
			return $newid;
	}

	/**
	 * ��Ӷ���
	 */
	function add_d($object, $isAddInfo = false) {
		try{
			$this->start_d();
			$object['formCode'] = "KH".date('YmdHis');
			$object['formDate'] = date("Y-m-d");
			$object['ExaStatus'] = "δ���";
			$object['permanentDate'] = $object['finishtime'];
			$newId = parent::add_d ( $object,true);
			$linkDao =new model_hr_permanent_linkcontent();
			$detailDao =new model_hr_permanent_detail();
			$standardDao =new model_hr_permanent_standard();
			if($object['summarystatus']==0){
				$summaryArr = $object['summaryTable'];
				foreach ($summaryArr as $key => $value) {
					$value['parentId'] = $newId;
					$value['parentCode'] = $object['formCode'];
					$linkDao->add_d($value,false);
				}
			}
			if($object['planstatus']==0){
				$planArr = $object['planTable'];
				foreach ($planArr as $key => $value) {
					$value['parentId'] = $newId;
					$value['parentCode'] = $object['formCode'];
					$linkDao->add_d($value,false);
				}
			}
			$detail = $object['schemeTable'];

			//���ֱ���ύ��ʦ����Ҳ���ʼ�
			if($object['status']==$this->statusDao->statusEtoK ( 'mastercheck' )){
				$addinfo = '�뵽����·������:������--->���˰칫--->����|��ѯ--->������--->����ת��';
				$this -> postInMail($object, $object['tutorId'], $addinfo);

			}

			//���ֱ���ύ�쵼����Ҳ���ʼ�
			if($object['status']==$this->statusDao->statusEtoK ( 'leadercheck' )){
				$addinfo = '�뵽����·������:������--->���˰칫--->����|��ѯ--->������--->����ת��';
				$this -> postInMail($object, $object['leaderId'], $addinfo);

			}

			foreach ($detail as $key => $value) {
				$value['parentId'] = $newId;
				$value['parentCode'] = $object['formCode'];
				$standardType = $standardDao->find(array("id"=>$value['standardId']),"","standardType");
				$value['standardType'] = $standardType['standardType'];
				$detailDao->add_d($value,false);
			}
			$this->commit_d();
		}catch(exception $e){
			$this->rollback();
			return null;
		}
		return $newId;
	}
	/*
	 * postmail�����������ʼ�
	 */
	function postInMail($object,$toid,$about){
		try {
			$this -> start_d();
			$emailArr = array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID'] = $toid;
			if ($emailArr['TO_ID']) {
				$addmsg = "";
				$username=$object['useName'];
				$position=$object ['positionName'];
				$deptname=$object ['deptName'];
				$begintime=$object ['begintime'];
				$finishtime=$object['finishtime'];
				$addmsg .=  <<<EOT
				<table width="500px">
					<thead>
						<tr align="center">
							<td><b>Ա������</b></td>
							<td><b>����ְλ</b></td>
							<td><b>��������</b></td>
							<td><b>���ÿ�ʼʱ��</b></td>
							<td><b>���ý���ʱ��</b></td>
						</tr>
					</thead>
					<tbody>
					<tr align="center" >
							<td>$username</td>
							<td>$position</td>
							<td>$deptname</td>
							<td>$begintime</td>
							<td>$finishtime</td>
						</tr>
					</tbody>
					</table><br>
EOT;
				$addmsg .= $about;
				//echo $addmsg;
				$emailDao = new model_common_mail();
				$flag=$emailDao -> emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'hr_permanent_examine', '���ʼ�Ϊ���ÿ�������֪ͨ', '', $emailArr['TO_ID'], $addmsg, 1);
			}
			$this -> commit_d();
			return $flag;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}
	}

	function iconvDecode($arr) {
		if (is_array ( $arr )) {
			foreach ( $arr as $key => $val ) {
				if (is_array ( $val )) {
					$arr [$key] = self::iconvDecode ( $val );
				} else {
					$arr [$key] = iconv('UTF-8','gb2312',urldecode($val));
				}
			}
		}
		return $arr;

	}


	function  autoEdit_d($object){
		$object = $this->iconvDecode($object);
		$info = $this->edit_d($object,true);
		return $info;
	}

	/**
	 * ���������޸Ķ���
	 */
	function edit_d($object, $isEditInfo = false) {
		try{
			$this->start_d();
			$datadict = new model_system_datadict_datadict();
//			print_r($object).die();
			//Ա��ȷ�Ϸ��ʼ�
			if($object['status']!=$this->statusDao->statusEtoK ( 'finish' )){
				$object['permanentType'] = $datadict->getDataNameByCode($object['permanentTypeCode']);
				$object['levelName'] = $datadict->getDataNameByCode($object['levelCode']);
			}
			$id= parent::edit_d ( $object, true );
			$linkDao=new model_hr_permanent_linkcontent();
			$detailDao =new model_hr_permanent_detail();
			$mainArr=array(
				"parentId"=>$object ['id'],
				"parentCode"=> $object['formCode']
			);
			if($object['planTable']!=null){
				$planArr=util_arrayUtil::setArrayFn($mainArr,$object ['planTable']);
				$linkDao->saveDelBatch ( $planArr );
			}
			if($object['summaryTable']!=null){
				$summaryArr=util_arrayUtil::setArrayFn($mainArr,$object ['summaryTable']);
				$linkDao->saveDelBatch ( $summaryArr );
			}
			if($object['schemeTable']!=null){
				$schemeArr=util_arrayUtil::setArrayFn($mainArr,$object ['schemeTable']);
				$detailDao->saveDelBatch ( $schemeArr );
			}
			//���ֱ���ύ�쵼����Ҳ���ʼ�
			if($object['status']==$this->statusDao->statusEtoK ( 'leadercheck' ) && $isEditInfo == false){
				$obj = $this -> get_d($object['id']);
				$username = $obj['tutor'];
				$comment = $obj['masterComment'];
				$addinfo = <<<EOT
				<br>��ʦ��<font color=blue>$username</font><br>
				��ʦ�����<font color=blue>$comment</font><br>
				�뵽����·������:������--->���˰칫--->����|��ѯ--->������--->����ת��
EOT;
				$this -> postInMail($obj, $obj['leaderId'], $addinfo);

			}
			//���ֱ���ύHR����Ҳ���ʼ�
			if($object['status']==$this->statusDao->statusEtoK ( 'hrcheck' ) && $isEditInfo == false){
				$obj = $this -> get_d($object['id']);
				$username_tutor = $obj['tutor'];
				$comment_tutor = $obj['masterComment'];
				$username_leader = $obj['leaderName'];
				$comment_leader = $obj['leaderComment'];
				$addinfo = <<<EOT
				<br>��ʦ��<font color=blue>$username_tutor</font><br>
				��ʦ�����<font color=blue>$comment_tutor</font><br>
				<br>�쵼��<font color=blue>$username_leader</font><br>
				�쵼�����<font color=blue>$comment_leader</font><br>
EOT;
				include (WEB_TOR . "model/common/mailConfig.php");
				$mailUser = $mailUser['oa_permanent_examie']['TO_ID'];
				$this -> postInMail($obj, $mailUser, $addinfo);

			}
			//Ա��ȷ�Ϸ��ʼ�
			if($object['status']==$this->statusDao->statusEtoK ( 'finish' )){
				$addmsg = "<br>Ա��״̬��";
				if($object['isAgree']==1){
					$addmsg .= "<font color=blue>ͬ��</font>";
					//�ı���˵�����Ϣ
					$personDao = new model_hr_personnel_personnel();
					$dictDao = new model_system_datadict_datadict ();
					$employeeState = $dictDao->getDataNameByCode ('YGZTZZ');
					$flag = $personDao->update(
							array('userNo'=>$_POST['userNo'],'userAccount'=>$_POST['userAccount']),
							array(
								  'employeesState'=>'YGZTZZ','employeesStateName'=>$employeeState,
								  'becomeDate'=>$object['permanentDate'],'becomeFraction'=>$object['totalScore'],
								  'jobName'=>$object['afterPositionName'],'jobId'=>$object['afterPositionId']
							)
						);
				}else{
					$addmsg .= "<font color=red>��ͬ��</font>";
				}
				//
				$obj = $this -> get_d($object['id']);
				$toid = $this->mailArr['sendUserId'];
				$this->postInMail($obj,$toid,$addmsg);
			}
			$this->commit_d();
		}catch(exception $e){
			$this->rollback();
			return null;
		}
		return $id;
	}

	//�ʼ�����
	function mailMsg_d($object){
		$mailDao = new model_common_mail();
		return $mailDao->mailClear($object['title'],$object['userAccount'],$object['content']);
	}

	//ȷ�ϲ鿴ҳ��
 	function affirmCheck_d($object, $isEditInfo = false) {
 		try {
 			$this->start_d();
 			$num= parent::edit_d ( $object, true );
 			$this->commit_d();
 		} catch (Exception $e) {
 			$this->rollback();
			return null;
 		}
 		return $num;

	}
 }
?>