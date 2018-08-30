<?php


/**
 * @author Show
 * @Date 2012��5��29�� ���ڶ� 9:24:35
 * @version 1.0
 * @description:��ѵ�γ̱� Model��
 */
class model_hr_training_course extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_training_course";
		$this->sql_map = "hr/training/courseSql.php";
		parent :: __construct();
	}

	public $datadictFieldArr = array (
		'courseType'
	);

	//��дadd_d
	function add_d($object) {
		//�����ֵ䴦��
		$object = $this->processDatadict($object);

		return parent :: add_d($object, true);
	}

	//��д�༭����
	function edit_d($object) {
		//�����ֵ䴦��
		$object = $this->processDatadict($object);

		return parent :: edit_d($object, true);
	}

	/**
	 * ���ݿγ�id���²�����
	 */
	function updateCoursepersons_d($ids){
		if(empty($ids)){
			return false;
		}
		$sql = "update oa_hr_training_course c
					left join (
						select
							c.courseId,
							GROUP_CONCAT(c.userName) as userNames,
							GROUP_CONCAT(c.userAccount) as userAccounts,
							GROUP_CONCAT(CONVERT(c.userNo,CHAR(50))) as userNos
						from
							(
								select
									courseId,
									userName,
									userAccount,
									userNo
								from
									oa_hr_training_course_records
								where
									courseId in (".$ids.")
								group by
									courseId,
									userAccount
							) c
						group by
							c.courseId
					) p on c.id = p.courseId
					set
						c.personsListName = p.userNames,
						c.personsListAccount = p.userAccounts,
						c.personsListNo = p.userNos
					where c.id in (".$ids.")";
		return $this->_db->query($sql);
	}

	/**
	 * ���ݿγ�id���²�����
	 */
	function updateTeacher_d($ids){
		if(empty($ids)){
			return false;
		}
		$sql = "update oa_hr_training_course c
					left join (
						select
							c.courseId,
							GROUP_CONCAT(c.teacherName) as teacherNames,
							GROUP_CONCAT(CONVERT(c.teacherId,CHAR(50))) as teacherIds
						from
							(
								select
									courseId,
									teacherName,
									teacherId
								from
									oa_hr_training_teachrecords
								where
									courseId in (".$ids.")
								group by
									courseId,
									teacherId
							) c
						GROUP BY
							c.courseId
					) p on c.id = p.courseId
					set
						c.teacherName = p.teacherNames,
						c.teacherId = p.teacherIds
					where c.id in (".$ids.")";
		return $this->_db->query($sql);
	}

	/******************* S ���뵼��ϵ�� ************************/
	function addExecelData_d(){
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
		$teacherArr = array();//��ʦ����
		$teacherDao = new model_hr_training_teacher();//��ʦ��
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
//					echo "<pre>";
//					print_r($val);
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])&& empty($val[5])){
						continue;
					}else{
						//��������
						$inArr = array();

						//�γ�����
						if(!empty($val[0])){
							$inArr['courseName'] = $val[0];
						}

						//��������
						if(!empty($val[1])){
							$val[1] = trim($val[1]);
							if(!isset($datadictArr[$val[1]])){
								$rs = $datadictDao->getCodeByName('HRPXLB',$val[1]);
								if(!empty($rs)){
									$courseType = $datadictArr[$val[1]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���ѵ�γ����</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$courseType = $datadictArr[$val[1]]['code'];
							}
							$inArr['courseType'] = $courseType;
							$inArr['courseTypeName'] = $val[1];
						}

						//��ѵ����
						if(!empty($val[2])){
							$inArr['agency'] = $val[2];
						}

						//��ʦ����
						if($val[3]){
							$val[3] = trim($val[3]);
							if(!isset($teacherArr[$val[3]])){
								$rs = $teacherDao->find(array('teacherName' => $val[3]));
								if(empty($rs)){
									//����ǿգ�����γ����鲢����
									$teacherArr[$val[3]]['teacherName'] = $val[3];

									if(!isset($userArr[$val[3]])){
										$rs = $otherDataDao->getUserInfo($val[3]);
										if(!empty($rs)){
											$userArr[$val[3]] = $rs;
										}else{
											$userArr[$val[3]] = array('USER_ID' => '');
										}
									}
									$inArr['teacherName'] = $val[3];
									$inArr['teacherAccount'] = $userArr[$val[3]]['USER_ID'];

									$teacherArr[$val[3]]['id'] = $teacherDao->add_d($teacherArr[$val[3]]);
								}else{
									$teacherArr[$val[3]] = $rs;
								}
							}

							$inArr['teacherName'] = $val[3];
							$inArr['teacherId'] = $teacherArr[$val[3]]['id'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û����д��ʦ����</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��ѵʱ��
						if(!empty($val[4])&& $val[4] != '0000-00-00'){
							$val[4] = trim($val[4]);

							if(!is_numeric($val[4])){
								$inArr['courseDate'] = $val[4];
							}else{
								$courseDate = date('Y-m-d',(mktime(0,0,0,1, $val[4] - 1 , 1900)));
								$inArr['courseDate'] = $courseDate;
							}
						}

						//��ѵ�ص�
						if(!empty($val[5])){
							$inArr['address'] = $val[5];
						}

						//��ѵ��ʱ
						if(!empty($val[6])){
							$inArr['lessons'] = $val[6];
						}

						//��ѵ����
						if(!empty($val[7])){
							$inArr['fee'] = $val[7];
						}

						//�γ̴��
						if(!empty($val[8])){
							$inArr['outline'] = $val[8];
						}

						//�ʺ϶���
						if(!empty($val[9])){
							$inArr['forWho'] = $val[9];
						}

						//�ʺ϶���
						if(!empty($val[10])){
							$val[10] = trim($val[10]);
							if(!isset($datadictArr[$val[10]])){
								$rs = $datadictDao->getCodeByName('HRKCZT',$val[10]);
								if(!empty($rs)){
									$status = $datadictArr[$val[10]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���ѵ�γ�״̬</spam>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$status = $datadictArr[$val[10]]['code'];
							}
							$inArr['status'] = $status;
						}

						//��ע
						if(!empty($val[11])){
							$inArr['remark'] = $val[11];
						}

						//��ע
						if(!empty($val[12])){
							$inArr['personsListName'] = $val[12];
						}

//						print_r($inArr);
						$newId = $this->add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '����ɹ�';
						}else{
							$tempArr['result'] = '<span class="red">����ʧ��</spam>';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}
	/******************* E ���뵼��ϵ�� ************************/
}
?>