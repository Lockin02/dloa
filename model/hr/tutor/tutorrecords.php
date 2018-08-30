<?php
include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

/**
 * @author Show
 * @Date 2012��5��26�� ������ 11:40:48
 * @version 1.0
 * @description:��ʦ������Ϣ�� Model��
 */
class model_hr_tutor_tutorrecords extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_records";
		$this->sql_map = "hr/tutor/tutorrecordsSql.php";

		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
			0 => array (
				'statusCName' => '������',
				'key' => '1'
			),
			1 => array (

				'statusCName' => 'Ա������',
				'key' => '2'
			),
			2 => array (

				'statusCName' => '��ʦ����',
				'key' => '3'
			),
			3 => array (

				'statusCName' => '���',
				'key' => '4'
			),
			4 => array (

				'statusCName' => '�ѹر�',
				'key' => '5'
			)
		);
		parent :: __construct();
	}
	/******************** �������ķָ��� ******************/
		/*
	 * ͨ��value����״̬
	 */
	function stateToVal($stateVal) {
		$returnVal = false;
		try {
			foreach ( $this->state as $key => $val ) {
				if ($val ['stateVal'] == $stateVal) {
					$returnVal = $val ['stateCName'];
				}
			}
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
		return $returnVal;
	}

	/*
	 * ͨ��״̬����value
	 */
	function stateToSta($stateSta) {
		$returnVal = false;
		foreach ( $this->state as $key => $val ) {
			if ($val ['stateEName'] == $stateSta) {
				$returnVal = $val ['stateVal'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}
	/******************** �ⲿ��Ϣ��ȡ ******************/
	/**
	 * ��ȡ������Ϣ
	 */
	function getPersonnelInfo_d($userAccount) {
		$personnelDao = new model_hr_personnel_personnel();
		$rs = $personnelDao->find(array (
			'userAccount' => $userAccount
		));
		if ($rs['deptNameT']) {
			$rs['deptName'] = $rs['deptNameT'];
			$rs['deptId'] = $rs['deptIdT'];
		} else {
			$rs['deptName'] = $rs['deptNameS'];
			$rs['deptId'] = $rs['deptIdS'];
		}
		return $rs;
	}

	/**
	 * ��ȡ��ְ��Ա��Ϣ
	 */
	function getEntryInfo_d($entryId) {
		$entryDao = new model_hr_recruitment_entryNotice();
		$rs = $entryDao->get_d($entryId);
		return $rs;
	}/**
	 * �����û��˺Ż�ȡ��Ϣ
	 *
	 */
	 function getInfoByUserNo_d($userNoArr){
		$this->searchArr = array ('userNoArr' => $userNoArr );
		$this->__SET('groupBy', 'c.userNo');
		$rows= $this->listBySqlId ( "select_default" );
		return $rows;
	 }/**
	 * ����ѧԱ�û��˺Ż�ȡ��Ϣ
	 *
	 */
	 function getInfoByStuUserNo_d($studentNo){
		$this->searchArr = array ('studentNo' => $studentNo );
		$this->__SET('groupBy', 'c.studentNo');
		$rows= $this->listBySqlId ( "select_default" );
		return $rows;
	 }

	/********************** ��ɾ�Ĳ� **********************/
	/**
	 * ��дadd_d����
	 */
	function add_d($object) {

		//��ȡ�ʼ�����
		if (isset ($object['email'])) {
			$emailArr = $object['email'];
			unset ($object['email']);
		}

		try {
			$this->start_d();
			//����������Ϣ
			if(!isset($object['status'])){
				$object['status']=1;//ָ����ʦ�󣬾�Ϊ������
			}
			$newId = parent :: add_d($object, true);
			if ($object['studentNo'] != null) {
				//��д��ʦ��Ϣ��������Ϣ��
				$studentNo = $object['studentNo']; //ѧԱ���
				$tutorName = $object['userName']; //��ʦ����
				$tutorId = $object['userAccount']; //��ʦID
				$sql = "update oa_hr_personnel set tutor='" . $tutorName . "',tutorId='" . $tutorId . "' where userNo='" . $studentNo . "'";
				$this->query($sql);
			}
			$this->commit_d();
			//��ȡѧԱ������getPersonnelInfo_d
			$student = $this->getPersonnelInfo_d($object['studentAccount']);
			//���͸���ʦ������ҲҪ���͸�ѧԱ������
			$toTutor = $object['userAccount'].','.$object['studentAccount'].','.$object['studentSuperiorId'];

			//�����ʼ� ,������Ϊ�ύʱ�ŷ��� ------ Ϊ�˲����ʼ�Ӱ��ҵ�����Լӵ�����
			if (isset ($emailArr)) {
				if ($emailArr['issend'] == 'y' && !empty ($emailArr['TO_ID'])) {

					$this->thisMail_d($emailArr, $object,$student);
					$this->MailtoTutor($emailArr,$toTutor, $object,$student); //���ʼ�����ʦ
				}
			}
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��ָ����ʦ����������
	 */
	function newadd_d($object) {
		$Dao = new model_hr_personnel_personnel();
		$id = $Dao->update(array (
			"id" => $object['perid']
		), array (
			"isNeedTutor" => "1"
		));
		$mailArr = $this->getHRMailInfo_d();
		$this->notutorMail_d($mailArr, $object);
		return $id;
	}

	/**
	 * ��д�༭����
	 */
	function edit_d($object) {

		//��ȡ�ʼ�����
		if (isset ($object['email'])) {
			$mailInfo = $object['email'];
			unset ($object['email']);
		}

		try {
			$this->start_d();

			//�޸�������Ϣ
			parent :: edit_d($object, true);
			$empId = $object['id'];
			$studentNo = $object['studentNo']; //ѧԱ���
			$tutorName = $object['userName']; //��ʦ����
			$tutorId = $object['userAccount']; //��ʦID
			$sql = "update oa_hr_personnel set tutor='" . $tutorName . "',tutorId='" . $tutorId . "' where userNo='" . $studentNo . "'";
			$this->query($sql);

			$this->commit_d();
			//��ȡѧԱ������
			$student = $this->getPersonnelInfo_d($object['studentAccount']);
			//���͸���ʦ������ҲҪ���͸�ѧԱ������
			$toTutor = $object['userAccount'].','.$object['studentAccount'];

			//�����ʼ� ,������Ϊ�ύʱ�ŷ��� ------ Ϊ�˲����ʼ�Ӱ��ҵ�����Լӵ�����
			if (isset ($mailInfo)) {
				if ($mailInfo['issend'] == 'y' && !empty ($mailInfo['TO_ID'])) {

					$this->thisMail_d($mailInfo, $object,$student);
					$this->MailtoTutor($mailInfo,$toTutor, $object,$student); //���ʼ�����ʦ
				}
			}

			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/******************* ҵ���߼� ************************/
	/**
	 * �رյ�ʦ��¼������д�رձ�ע
	 */
	function close_d($object) {
		//����ʦ��¼��״̬��Ϊ�ر�
		$object['status']=5;
		//�޸�������Ϣ
		if (parent :: edit_d($object, true)){
			return true;
		}
		else {
			return false;
		}
	}
	/**
	 * �༭���˷���
	 */
	 function editScore_d($object){
	 	try{
	 		$this->start_d();
			//�༭�����˷���
			parent::edit_d($object);

			//���¿��˱��еķ���
			$schemeDao = new model_hr_tutor_scheme();
			$sql = "update oa_hr_tutor_scheme set assessmentScore='".$object['assessmentScore']."'where tutorId=".$object['id']."";
			$this->query($sql);

	 		$this->commit_d();
			return true;
	 	}catch(exception $e){
			$this->rollBack();
			return false;
	 	}
	 }
	 /**
	 * �༭ �Ƿ���Ҫ�ƶ������ƻ� �Ƿ���Ҫ����HR��ģʽ�ύ�ܱ�
	 */
	 function editModel_d($obj){
	 	//���Ϊ�գ���˵��δѡ�У���Ϊ ��
		$obj['isCoachplan'] = isset($obj['isCoachplan']) ? "��":"��";
		$obj['isWeekly'] = isset($obj['isWeekly']) ? "��":"��";

		if($this->update(array("id"=>$obj['id']),$obj)){
			return true;
		}else{
			return false;
		}
	 }
	/**
	 * ��һ����¼�ĵ�ǰ״̬��Ϊ���
	 */
	 function complete_d($id){
		$obj = $this->get_d($id);
		$obj['status']=4;
		return parent::edit_d($obj);
	 }
	/**
	 * �ʼ����û�ȡ
	 */
	function getMailInfo_d() {

		include (WEB_TOR . "model/common/mailConfig.php");
		$mailArr = isset ($mailUser['oa_hr_tutor_records']) ? $mailUser['oa_hr_tutor_records'] : array (
			'sendUserId' => '',
			'sendName' => ''
		);
		return $mailArr;
	}
	/**
	 * ��ѧԱΪ�з��Ͳ�Ʒ��ʱ��ָ����ʦ�ĳ����˻�ȡ
	 */
	function getMailInfoSpecial_d() {

		include (WEB_TOR . "model/common/mailConfig.php");
		$mailArr = isset ($mailUser['oa_hr_tutor_records2']) ? $mailUser['oa_hr_tutor_records2'] : array (
			'sendUserId' => '',
			'sendName' => ''
		);
		return $mailArr;
	}
	/**
	 * �ʼ����û�ȡHR
	 */
	function getHRMailInfo_d() {

		include (WEB_TOR . "model/common/mailConfig.php");
		$mailArr = isset ($mailUser['tutorReward']) ? $mailUser['tutorReward'] : array (
			'sendUserId' => '',
			'sendName' => ''
		);
		return $mailArr;
	}

	/**
	 * �����ʼ�����ʦ
	 * @param  ��ʦID,ָ����ʦҳ�洫�����Ķ���
	 */
	function MailtoTutor($emailArr,$tutorId, $object,$student) {

		$addMsg = $_SESSION['USERNAME'] .
		'�Ѿ�ָ��['. $object['deptName'].'\\'.$object['userName'] . ']����ѧԱ[' . $object['studentName'] . '\����:' . $object['studentDeptName'] . '\ְλ:' . $student['jobName'] . '\��ְ����:' . $student['entryDate'] . ']�ĵ�ʦ,<br>' .
		'�������������յ�����Ϣ��3�����������벿���쵼��ָ���˹�ͨ��<br>' .
		'�뵼ʦ����ģ�������Ա����Ӧ��ָ���ͽ�����<br>' .
		'�뵼ʦ��ѧԱ��ְ��5������������OAϵͳ�ƶ��������ƻ������ύѧԱ�ϼ���ˡ�<br>' .
		'��ÿ�ܶ�ǰ��OAϵͳ��ѧԱÿ��һ�ύ���ܱ��ظ�ָ��������顣<br>' .
		'�������ƻ������ܱ��ύ���ظ���OA·����������--->���˰칫--->��������--->������--->��ʦ������ѡ�м�¼�һ��ɼ���<br>' .
		'�����Ϣ������ĸ�����';
		$emailDao = new model_common_mail();
		$attachment=array("upfile/download/�½�Ա����ʦ�ƶ�֮Q&A.pdf");

		$emailDao->mailWithFile('��ʦ����ͬ����Ҫ���Ĺ���', $tutorId, $addMsg, $emailArr['ADDIDS'],'upfile/download/�½�Ա����ʦ�ƶ�֮Q&A.pdf');

	}
	/**
	 * �ʼ�����
	 */
	function thisMail_d($emailArr, $object,$student, $thisAct = '����') {
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = $_SESSION['USERNAME'] . '�ѽ� [ '. $object['deptName'].'\\'.$object['userName'] . '] ָ��ΪѧԱ[' . $object['studentName'] . '\����:' . $object['studentDeptName'] . '\ְλ:' . $student['jobName'] . '\��ְ����:' . $student['entryDate'] . '] �ĵ�ʦ��<br><br>';
		$addMsg.=' ��ӭ��ͬ�¼�������������ͥ��<br><br> �뵼ʦ����׼����ӭ�Ӽ��������ĳ��������Ρ����ࡢ�ɾͺ��Ҹ�����ʦ���ģ�<br><br>��˾��лÿһλ��ʦ���������ڷ�����ľ��񡢶Ա��˵���˽������ʹ��˾���³�Ա������Ӧ�¸�λ��Ҫ��ʹ��˾��֪ʶ�Ļ����Բ��ϻ��ۺʹ��У�
';
		$emailDao = new model_common_mail();
		$emailDao->mailClear('������ͬ�µ�ʦ������', $emailArr['TO_ID'], $addMsg, $emailArr['ADDIDS']);
	}

	/**
	 * ��ָ����ʦʱ���ʼ�����
	 */
	function notutorMail_d($emailArr, $object, $thisAct = '����') {
		$addMsg = '[' . $object['studentDeptName'] . ']����[' . $object['studentName'] . ']Ա������Ҫָ����ʦ' . '<br>��ע˵��:  ' . $object['remark'];

		$emailDao = new model_common_mail();
		$emailDao->mailClear('��ָ����ʦ', $emailArr['sendUserId'], $addMsg, null);
	}

	/******************* S ���뵼��ϵ�� ************************/
	function addExecelData_d() {
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType = $_FILES["inputExcel"]["type"];
		$resultArr = array (); //�������
		$excelData = array (); //excel��������
		$tempArr = array ();
		$inArr = array (); //��������
		$userConutArr = array (); //�û�����
		$userArr = array (); //�û�����
		$deptArr = array (); //��������
		$jobsArr = array (); //ְλ����
		$otherDataDao = new model_common_otherdatas(); //������Ϣ��ѯ
		$datadictArr = array (); //�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		$personnelDao = new model_hr_personnel_personnel();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil :: upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register("__autoload");
			//			echo "<pre>";
			if (is_array($excelData)) {

				//������ѭ��
				foreach ($excelData as $key => $val) {
					//					echo "<pre>";
					//					print_r($val);
					$actNum = $key +1;
					if (empty ($val[0]) && empty ($val[1]) && empty ($val[2]) && empty ($val[3]) && empty ($val[4]) && empty ($val[5]) && empty ($val[13])) {
						continue;
					} else {
						//��������
						$inArr = array ();

						//��ʦԱ�����
						if (!empty ($val[0])) {
							$val[0]=trim($val[0]);
							if (!isset ($userArr[$val[0]])) {
								$rs = $personnelDao->getInfoByUserNo_d($val[0]);
								if (!empty ($rs)) {
									$userConutArr[$val[0]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum . '������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�Ա�����</span>';
									array_push($resultArr, $tempArr);
									continue;
								}
							}

							$inArr['userAccount'] = $userConutArr[$val[0]]['userAccount'];
							$inArr['deptId'] = $userConutArr[$val[0]]['belongDeptId'];
							$inArr['deptName'] = $userConutArr[$val[0]]['belongDeptName'];
							$inArr['userNo'] = $val[0];
						} else {
							$tempArr['docCode'] = '��' . $actNum . '������';
							$tempArr['result'] = '<span class="red">����ʧ��!û��Ա�����</span>';
							array_push($resultArr, $tempArr);
							continue;
						}

						//��ʦ����
						if (!empty ($val[1])) {
							$inArr['userName'] = $val[1];
						} else {
							$tempArr['docCode'] = '��' . $actNum . '������';
							$tempArr['result'] = '<span class="red">����ʧ��!û��Ա������</span>';
							array_push($resultArr, $tempArr);
							continue;
						}

						//��ʦְ��
						if (!empty ($val[2])&&trim($val[2])!='') {
							$val[2] = trim($val[2]);
							if (!isset ($jobsArr[$val[2]])) {
								$rs = $otherDataDao->getJobId_d($val[2]);
								if (1) {
									$jobsArr[$val[2]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum . '������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ�ʦְλ</span>';
									array_push($resultArr, $tempArr);
									continue;
								}
							}
							$inArr['jobName'] = $val[2];
							$inArr['jobId'] = $jobsArr[$val[2]];
						}

						//ѧԱ���
						if (!empty ($val[3])&&trim($val[3])!='') {
							$val[3] = trim($val[3]);
							if (!isset ($userArr[$val[3]])) {
								$rs = $personnelDao->getInfoByUserNo_d($val[3]);
									$userArr[$val[3]] = $rs;
							}
							$inArr['studentAccount'] = $userArr[$val[3]]['userAccount'];
							$inArr['studentNo'] = $userArr[$val[3]]['userNo'];
						} else {
							$tempArr['docCode'] = '��' . $actNum . '������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�Ա�����</span>';
							array_push($resultArr, $tempArr);
							continue;
						}

						//ѧԱ����
						if (!empty ($val[4])&&trim($val[4])!='') {
							$val[4] = trim($val[4]);
							$inArr['studentName'] = $val[4];
						} else {
							$tempArr['docCode'] = '��' . $actNum . '������';
							$tempArr['result'] = '<span class="red">����ʧ��!û��ѧԱ����</span>';
							array_push($resultArr, $tempArr);
							continue;
						}

						//ѧԱ����
						if (!empty ($val[5])&&trim($val[5])!='') {
							$val[5] = trim($val[5]);
							if (!isset ($deptArr[$val[5]])) {
								$rs = $otherDataDao->getDeptId_d($val[5]);
								if (1) {
									$deptArr[$val[5]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum . '������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�ѧԱ����</span>';
									array_push($resultArr, $tempArr);
									continue;
								}
							}
							$inArr['studentDeptName'] = $val[5];
							$inArr['studentDeptId'] = $deptArr[$val[5]];
						} else {
							$tempArr['docCode'] = '��' . $actNum . '������';
							$tempArr['result'] = '<span class="red">����ʧ��!û��ѧԱ����</span>';
							array_push($resultArr, $tempArr);
							continue;
						}

						//��ѧ��ʼ����
						if (!empty ($val[6]) && $val[6] != '0000-00-00'&&trim($val[6])!='') {
							$val[6] = trim($val[6]);

							if (!is_numeric($val[6])) {
								$inArr['beginDate'] = $val[6];
							} else {
								$beginDate = date('Y-m-d', (mktime(0, 0, 0, 1, $val[6] - 1, 1900)));
								if($beginDate=='1970-01-01'){
									$quitDate = date('Y-m-d',strtotime ($val[6]));
									$inArr['beginDate'] = $quitDate;
								}else{
									$inArr['beginDate'] = $beginDate;
								}
							}
						}
						//ת��ʱ��
						if (!empty ($val[7])&&trim($val[7])!='') {
							$inArr['becomeDate'] =trim($val[7]);
						}
						//��ǰ״̬
						if (!empty ($val[8])&&trim($val[8])!='') {
							$inArr['status'] = $this->statusDao->statusCtoK(trim($val[8])) ;
						}
						//���˷���
						if (!empty ($val[9])&&trim($val[9])!='') {
							$inArr['assessmentScore'] = $val[9];
						}
						//����
						if (!empty ($val[10])&&trim($val[10])!='') {
							$inArr['rewardPrice'] = $val[10];
						}
						//�ر�����
						if (!empty ($val[11])&&trim($val[11])!='') {
							$inArr['closeReason'] = $val[11];
						}
						//��ע
						if (!empty ($val[12])&&trim($val[12])!='') {
							$inArr['remark'] = $val[12];
						}
						//ѧԱֱ���ϼ�
						if (!empty ($val[13])&&trim($val[13])!='') {
							$val[13] = trim($val[13]);
							$inArr['studentSuperior'] = $val[13];
						} else {
							$tempArr['docCode'] = '��' . $actNum . '������';
							$tempArr['result'] = '<span class="red">����ʧ��!û��ѧԱֱ���ϼ�</span>';
							array_push($resultArr, $tempArr);
							continue;
						}

						//						print_r($inArr);
						$newId = parent::add_d($inArr, true);
						if ($newId) {
							$tempArr['result'] = '����ɹ�';
						} else {
							$tempArr['result'] = '<span class="red">����ʧ��</span>';
						}
						$tempArr['docCode'] = '��' . $actNum . '������';
						array_push($resultArr, $tempArr);
					}
				}
				return $resultArr;
			}
		}
	}

	//����
	function export($rowdatas) {
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		//		//����һ��Excel������
		//		$objPhpExcelFile = new PHPExcel();

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/����-��ʦ��������ģ��.xls"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date('H_i_s') . rand(0, 10) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '��Ϣ�б�'));
		//���ñ�ͷ����ʽ ����
		$i = 2;
		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
//					$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
					$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m++;
				}
				$i++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . 'I' . $i);
			for ($m = 0; $m < 10; $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '���������Ϣ'));
			}
		}

		//�������
		ob_end_clean(); //��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "��ʦ�б���.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}
	/******************* E ���뵼��ϵ�� ************************/
}
?>