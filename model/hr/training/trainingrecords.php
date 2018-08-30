<?php

/**
 * @author Show
 * @Date 2012��5��30�� ������ 14:02:31
 * @version 1.0
 * @description:��ѵ����-�γ���ϸ��¼ Model��
 */
class model_hr_training_trainingrecords extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_training_course_records";
		$this->sql_map = "hr/training/trainingrecordsSql.php";
		parent :: __construct();
	}

	//�����Ƿ���ѵʦ
	function rtHandStatus_d($val) {
		if($val == 1) {
			return '���ύ';
		}else if($val == 2) {
			return '�����ύ';
		}else{
			return 'δ�ύ';
		}
	}

	//������ѵ������ѵ
	function rtIsInner_d($val) {
		if($val == 1) {
			return '��ѵ';
		}else if($val == 2) {
			return '��ѵ-��Ƹ';
		}else{
			return '��ѵ';
		}
	}

	/**
	 * �༭��Ա������Ϣ
	 */
	 function edit_d($object) {
	 	try{
			$this->start_d();
			$dictDao = new model_system_datadict_datadict();
			$object['addMode'] = $dictDao->getDataNameByCode($object['status']);
			$object['assessmentName'] = $dictDao->getDataNameByCode($object['assessment']);
			$object['trainsTypeName'] = $dictDao->getDataNameByCode($object['trainsType']);
			$object['trainsMethod'] = $dictDao->getDataNameByCode($object['trainsMethodCode']);
			$id = parent::edit_d($object ,true);
			$this->commit_d();
			return $id;
		 }catch(Exception $e) {
			$this->rollBack();
			return $id;
		}
 	}

 	/**���ݿγ����ƣ���ѵ��ʦ���ڿ����ڣ��ڿν������ڣ���ѯ��ѵ��
	 * @author Administrator
	 *
	 */
	 function findMemberByRecords($courseName,$teacherName,$teachDate,$teachEndDate) {
		$this->searchArr = array ('courseName' => $courseName,'teacherName' => $teacherName,'beginDate' => $teachDate,'endDate' => $teachEndDate);
		$rows= $this->listBySqlId ( "select_default" );
		$userAccountArr=array();
		$userNameArr=array();
		$returnArr=array();
		if(is_array($rows)) {
			foreach($rows as $key=>$val) {
				$userAccountArr[$key]=$val['userAccount'];
				$userNameArr[$key]=$val['userName'];
			}
			$returnArr['userAccount']=implode(',',$userAccountArr);
			$returnArr['userName']=implode(',',$userNameArr);
			return $returnArr;
		}
	 }

	/******************* S ���뵼��ϵ�� ************************/
	function addExecelData_d() {
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$inArr = array();//��������
		$userArr = array();//�û�����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		$courseArr = array();//�γ�����
		$courseDao = new model_hr_training_course();
		$courseIdArr = array();
		$teacherArr = array();//��ʦ����
		$teacherDao = new model_hr_training_teacher();//��ʦ��
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");

			if(is_array($excelData)) {

				//������ѭ��
				foreach($excelData as $key => $val) {

					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])&& empty($val[5])) {
						continue;
					}else{
						//��������
						$inArr = array();

						//��ʦԱ�����
						if(!empty($val[0])) {
							if(!isset($userArr[$val[0]])) {
								$rs = $otherDataDao->getUserInfoByUserNo($val[0]);
								if(!empty($rs)) {
									$userConutArr[$val[0]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�Ա�����</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}

							$inArr['userAccount'] = $userConutArr[$val[0]]['USER_ID'];
							$inArr['deptId'] = $userConutArr[$val[0]]['DEPT_ID'];
							$inArr['deptName'] = $userConutArr[$val[0]]['DEPT_NAME'];
							$inArr['userNo'] = $val[0];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û��Ա�����</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//Ա������
						if(!empty($val[1])) {
							$inArr['userName'] = $val[1];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û��Ա������</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//����
						if(!empty($val[2])) {
							$val[2] = trim($val[2]);
							if(!isset($deptArr[$val[2]])) {
								$rs = $otherDataDao->getDeptId_d($val[2]);
								if(!empty($rs)) {
									$inArr['deptId'] = $rs;
								}
							}
							$inArr['deptName'] = $val[2];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û�в���</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//Ա��ְλ
						if(!empty($val[3]) && trim($val[3]) != '') {
							$val[3] = trim($val[3]);
							if(!isset($jobsArr[$val[3]])) {
								$rs = $otherDataDao->getJobId_d($val[3]);
								if(!empty($rs)) {
									$jobsArr[$val[3]] = $rs;
								}else{
									$jobsArr[$val[3]] = array('belongDeptId' => '');
								}
							}
							$inArr['jobName'] = $val[3];
							$inArr['jobId'] = $jobsArr[$val[3]];
						}

						//�γ̱��
						if(!empty($val[4]) && trim($val[4]) != '') {
							$inArr['courseCode'] = $val[4];
						}

						//�γ�����
						if(!empty($val[5]) && trim($val[5]) != '') {
							$inArr['courseName'] = $val[5];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û����д�γ�����</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//ʱ��
						if(!empty($val[6])) {
							$inArr['duration'] = $val[6];
						}

						//��ѵ����
						if(!empty($val[7]) && trim($val[7]) != '') {
							$val[7] = trim($val[7]);
							if(!isset($datadictArr[$val[7]])) {
								$rs = $datadictDao->getCodeByName('HRPXLX',$val[7]);
								if(!empty($rs)) {
									$trainsType = $datadictArr[$val[7]]['code'] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���ѵ����</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							} else {
								$trainsType = $datadictArr[$val[7]]['code'];
							}
							$inArr['trainsType'] = $trainsType;
							$inArr['trainsTypeName'] = $val[7];
						}

						//��ѵ��ʽ
						if(!empty($val[8]) && trim($val[8]) != '') {
							$val[8] = trim($val[8]);
							if(!isset($datadictArr[$val[8]])) {
								$rs = $datadictDao->getCodeByName('HRPXFS',$val[8]);
								if(!empty($rs)) {
									$trainsMethodCode = $datadictArr[$val[8]]['code'] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���ѵ��ʽ</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$trainsMethodCode = $datadictArr[$val[8]]['code'];
							}
							$inArr['trainsMethodCode'] = $trainsMethodCode;
							$inArr['trainsMethod'] = $val[8];
						}

						//��֯����
						if(!empty($val[9])) {
							$val[9] = trim($val[9]);
							if(!isset($deptArr[$val[9]])) {
								$rs = $otherDataDao->getDeptId_d($val[9]);
								if(!empty($rs)) {
									$deptArr[$val[9]] = $rs;
								}
							}
							$inArr['orgDeptName'] = $val[9];
							$inArr['orgDeptId'] = $deptArr[$val[9]];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û����֯����</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//��ѵ�·�
						if(!empty($val[10]) && trim($val[10])!='') {
							if(!is_numeric($val[10])) {
								$inArr['trainsMonth'] = $val[10];
							} else {
								$year = date('Y',(mktime(0 ,0 ,0 ,1 ,$val[10] - 1 ,1900)));
								$month = date('n',(mktime(0 ,0 ,0 ,1 ,$val[10] - 1 ,1900)));
								if($year == '1970' && $month == '1') {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!��ѵ�·ݸ�ʽ����ȷ</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
								$inArr['trainsMonth'] = $year.'��'.$month.'��';
							}
						}

						//��ѵ��ʼʱ��
						if(!empty($val[11]) &&  $val[11] != '0000-00-00' && trim($val[11]) != '') {
							$val[11] = trim($val[11]);
							if(!is_numeric($val[11])) {
								$inArr['beginDate'] = $val[11];
							} else {
								$beginDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val[11] - 1 ,1900)));
								if($beginDate == '1970-01-01') {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!��ʼʱ���ʽ����ȷ</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
								$inArr['beginDate'] = $beginDate;
							}
						}

						//��ѵ����ʱ��
						if(!empty($val[12]) &&  $val[12] != '0000-00-00' && trim($val[12]) != '') {
							$val[12] = trim($val[12]);
							if(!is_numeric($val[12])) {
								$inArr['endDate'] = $val[12];
							}else{
								$endDate = date('Y-m-d',(mktime(0,0,0,1, $val[12] - 1 , 1900)));
								if($endDate=='1970-01-01') {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!����ʱ���ʽ����ȷ</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
								$inArr['endDate'] = $endDate;
							}
						}

						//��ѵ����
						if(!empty($val[13]) && trim($val[13])!='') {
							$inArr['trainsNum'] = $val[13];
						}

						//��ѵ����
						if(!empty($val[14]) && trim($val[14])!='') {
							$inArr['agency'] = $val[14];
						}

						//��ַ
						if(!empty($val[15]) && trim($val[15])!='') {
							$inArr['address'] = $val[15];
						}

						//��ѵ��ʦ
						if(!empty($val[16]) && trim($val[16])!='') {
							$val[16] = trim($val[16]);
							if(!isset($teacherArr[$val[16]])) {
								$rs = $teacherDao->find(array('teacherName' => $val[16]));
								if(empty($rs)) {
									//����ǿգ�����γ����鲢����
									$teacherArr[$val[16]]['teacherName'] = $val[11];

									if(!isset($userArr[$val[16]])) {
										$rs = $otherDataDao->getUserInfo($val[16]);
										if(!empty($rs)) {
											$userArr[$val[16]] = $rs;
										}else{
											$userArr[$val[16]] = array('USER_ID' => '');
										}
									}
									$inArr['teacherName'] = $val[16];
									$inArr['teacherAccount'] = $userArr[$val[16]]['USER_ID'];
								}else{
									$teacherArr[$val[16]] = $rs;
								}
							}

							$inArr['teacherName'] = $val[16];
							$inArr['teacherId'] = $teacherArr[$val[16]]['id'];
						}

						//��ѵ����
						if(!empty($val[17]) && trim($val[17]) != '') {
							$inArr['fee'] = $val[17];
						}

						//״̬
						if(!empty($val[18]) && trim($val[18]) != '') {
							$val[18] = trim($val[18]);
							if(!isset($datadictArr[$val[18]])) {
								$rs = $datadictDao->getCodeByName('HRPXZT',$val[18]);
								if(!empty($rs)) {
									$status = $datadictArr[$val[18]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���ѵ״̬</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$status = $datadictArr[$val[18]]['code'];
							}
							$inArr['status'] = $status;
						}

						//��������
						if(!empty($val[19]) && trim($val[19]) != '') {
							$val[19] = trim($val[19]);
							if(!isset($datadictArr[$val[19]])) {
								$rs = $datadictDao->getCodeByName('HRPXKH',$val[19]);
								if(!empty($rs)) {
									$assessment = $datadictArr[$val[19]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĿ�������</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$assessment = $datadictArr[$val[19]]['code'];
							}
							$inArr['assessment'] = $assessment;
							$inArr['assessmentName'] = $val[19];
						}

						//���˳ɼ�
						if(!empty($val[20]) && trim($val[20]) != '') {
							$inArr['assessmentScore'] = $val[20];
						}

						//�γ���������
						if(!empty($val[21]) && trim($val[21]) != '') {
							$inArr['courseEvaluateScore'] = $val[21];
						}

						//��ѵ��֯��������
						if(!empty($val[22]) && trim($val[22]) != '') {
							$inArr['trainsOrgEvaluateScore'] = $val[22];
						}

						//Ч������Ч����ʱ��
						if(!empty($val[23]) && trim($val[23]) != '') {
							$val[23] = trim($val[23]);
							if(!is_numeric($val[23])) {
								$inArr['followTime'] = $val[23];
							} else {
								$followTime = date('Y-m-d',(mktime(0,0,0,1, $val[23] - 1 , 1900)));
								if($followTime == '1970-01-01') {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!Ч������Ч����ʱ���ʽ����ȷ</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
								$inArr['followTime'] = $followTime;
							}
						}

						$newId = $this->add_d($inArr,true);

						if($newId) {
							$tempArr['result'] = '����ɹ�';
						}else{
							$tempArr['result'] = '<span class="red">����ʧ��</span>';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push( $resultArr,$tempArr );
					}
				}

				//���¿γ̵���ز�����
//				print_r($courseIdArr);
//				$ids = implode($courseIdArr,',');
//				$courseDao->updateCoursepersons_d($ids);

				return $resultArr;
			}
		}
	}
	/******************* E ���뵼��ϵ�� ************************/
}
?>