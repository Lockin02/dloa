<?php
/**
 * @author Show
 * @Date 2012��5��31�� ������ 10:13:30
 * @version 1.0
 * @description:��ѵ����-�ڿμ�¼ Model��
 */
class model_hr_training_teachrecords  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_training_teachrecords";
		$this->sql_map = "hr/training/teachrecordsSql.php";
		parent::__construct ();
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
		$teacherArr = array();//��ʦ����
		$teacherDao = new model_hr_training_teacher();//��ʦ��
		$courseArr = array();//�γ�����
		$courseIdArr = array();//�γ�id����
		$courseDao = new model_hr_training_course();//�γ���
		$trainingrecordsDao = new model_hr_training_trainingrecords();
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ

		//�����ֶα�ͷ����
		$objNameArr = array (
			0 => 'courseCode', //�γ̱��
			1 => 'courseName', //�γ�����
			2 => 'duration', //ʱ��
			3 => 'teacherName', //��ʦ
			4 => 'userNo', //Ա�����
			5 => 'trainsTypeName', //��ѵ����
			6 => 'trainsMethod', //��ѵ��ʽ
			7 => 'orgDeptName', //��֯����
			8 => 'trainsMonth', //��ѵ�·�
			9 => 'teachDate', //��ʼʱ��
			10 => 'teachEndDate', //����ʱ��
			11 => 'trainsNum', //��ѵ����
			12 => 'agency', //��ѵ����
			13 => 'address', //�ص�
			14 => 'joinNum', //��������
			15 => 'fee', //����
			16 => 'assessmentName', //��������
			17 => 'courseEvaluateScore', //�γ���������
			18 => 'trainsOrgEvaluateScore', //��ѵ��֯��������
			19 => 'followTime', //Ч������Ч����ʱ��
		);
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){
				$objectArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					if(empty($row[1]) && empty($row[2]) && empty($row[9])) {
						continue;
					} else {
						foreach ( $objNameArr as $index => $fieldName ) {
							$objectArr [$rNum] [$fieldName] = $row [$index];
						}
					}
				}

				//������ѭ��
				foreach($objectArr as $key => $val){

					$actNum = $key + 2;
					$inArr = array();

					//�γ̱��
					if($val['courseCode']) {
						$inArr['courseCode'] = $val['courseCode'];
					}

					//�γ�����
					if($val['courseName']){
						if(!isset($courseArr[$val['courseName']])){
							$rs = $courseDao->find(array('courseName' => $val['courseName']));
							if(empty($rs)){
								//����ǿգ�����γ����鲢����
								$courseArr[$val['courseName']]['courseName'] = $val['courseName'];
								$courseArr[$val['courseName']]['address'] = $inArr['address'];
								$courseArr[$val['courseName']]['teachDate'] = $inArr['teachDate'];
								$courseArr[$val['courseName']]['status'] = 'HRKCZT-02';
							}else{
								$courseArr[$val['courseName']] = $rs;
							}
						}
						$inArr['courseName'] = $val['courseName'];
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!û����д�γ�����</span>';
						array_push( $resultArr,$tempArr );
						continue;
					}

					//ʱ��
					if($val['duration']) {
						$inArr['duration'] = $val['duration'];
					}

					//��ʦ����
					if($val['teacherName']){
						$inArr['teacherName'] = $val['teacherName'];
						if(!isset($teacherArr[$val['teacherName']])){
							$rs = $teacherDao->find(array('teacherName' => $val['teacherName']));
							if(empty($rs)){
								//����ǿգ�����γ����鲢����
								$teacherArr[$val['teacherName']]['teacherName'] = $val['teacherName'];
							}else{
								$teacherArr[$val['teacherName']] = $rs;
							}
						}

						$inArr['teacherName'] = $val['teacherName'];
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!û����д��ʦ����</span>';
						array_push( $resultArr,$tempArr );
						continue;
					}

					//Ա�����
					if($val['userNo']) {
						$inArr['userNo'] = $val['userNo'];
					}

					//��ѵ����
					if(!empty($val['trainsTypeName']) && trim($val['trainsTypeName']) != '') {
						$val['trainsTypeName'] = trim($val['trainsTypeName']);
						if(!isset($datadictArr[$val['trainsTypeName']])) {
							$rs = $datadictDao->getCodeByName('HRPXLX',$val['trainsTypeName']);
							if(!empty($rs)) {
								$trainsType = $datadictArr[$val['trainsTypeName']]['code'] = $rs;
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���ѵ����</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						} else {
							$trainsType = $datadictArr[$val['trainsTypeName']]['code'];
						}
						$inArr['trainsType'] = $trainsType;
						$inArr['trainsTypeName'] = $val['trainsTypeName'];
					}

					//��ѵ��ʽ
					if(!empty($val['trainsMethod']) && trim($val['trainsMethod']) != '') {
						$val['trainsMethod'] = trim($val['trainsMethod']);
						if(!isset($datadictArr[$val['trainsMethod']])) {
							$rs = $datadictDao->getCodeByName('HRPXFS',$val['trainsMethod']);
							if(!empty($rs)) {
								$trainsMethodCode = $datadictArr[$val['trainsMethod']]['code'] = $rs;
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���ѵ��ʽ</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$trainsMethodCode = $datadictArr[$val['trainsMethod']]['code'];
						}
						$inArr['trainsMethodCode'] = $trainsMethodCode;
						$inArr['trainsMethod'] = $val['trainsMethod'];
					}

					//��֯����
					if(!empty($val['orgDeptName'])) {
						$val['orgDeptName'] = trim($val['orgDeptName']);
						if(!isset($deptArr[$val['orgDeptName']])) {
							$rs = $otherDataDao->getDeptId_d($val['orgDeptName']);
							if(!empty($rs)) {
								$deptArr[$val['orgDeptName']] = $rs;
							}
						}
						$inArr['orgDeptName'] = $val['orgDeptName'];
						$inArr['orgDeptId'] = $deptArr[$val['orgDeptName']];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!û����֯����</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//��ѵ�·�
					if(!empty($val['trainsMonth']) && trim($val['trainsMonth']) != '') {
						if(!is_numeric($val['trainsMonth'])) {
							$inArr['trainsMonth'] = $val['trainsMonth'];
						} else {
							$year = date('Y',(mktime(0 ,0 ,0 ,1 ,$val['trainsMonth'] - 1 ,1900)));
							$month = date('n',(mktime(0 ,0 ,0 ,1 ,$val['trainsMonth'] - 1 ,1900)));
							if($year == '1970' && $month == '1') {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!��ѵ�·ݸ�ʽ����ȷ</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
							$inArr['trainsMonth'] = $year.'��'.$month.'��';
						}
					}

					//�ڿ�����
					if(!empty($val['teachDate']) && $val['teachDate'] != '0000-00-00') {
						$val['teachDate'] = trim($val['teachDate']);

						if(!is_numeric($val['teachDate'])){
							$inArr['teachDate'] = $val['teachDate'];
						}else{
							$teachDate = date('Y-m-d',(mktime(0,0,0,1, $val['teachDate'] - 1 , 1900)));
							$inArr['teachDate'] = $teachDate;
						}
					}

					//�ڿν�������
					if(!empty($val['teachEndDate']) && $val['teachEndDate'] != '0000-00-00') {
						$val['teachEndDate'] = trim($val['teachEndDate']);

						if(!is_numeric($val['teachEndDate'])){
							$inArr['teachEndDate'] = $val['teachEndDate'];
						}else{
							$teachDate = date('Y-m-d',(mktime(0,0,0,1, $val['teachEndDate'] - 1 , 1900)));
							$inArr['teachEndDate'] = $teachDate;
						}
					}

					//��ѵ����
					if($val['trainsNum']) {
						$inArr['trainsNum'] = $val['trainsNum'];
					}

					//��ѵ����
					if($val['agency']) {
						$inArr['agency'] = $val['agency'];
					}

					//��ַ
					if($val['address']){
						$inArr['address'] = $val['address'];
					}

					//��ѵ����
					if($val['joinNum']){
						$inArr['joinNum'] = $val['joinNum'];
					}

					//����
					if($val['fee']){
						$inArr['fee'] = $val['fee'];
					}

					//��������
					if(!empty($val['assessmentName']) && trim($val['assessmentName']) != '') {
						$val['assessmentName'] = trim($val['assessmentName']);
						if(!isset($datadictArr[$val['assessmentName']])) {
							$rs = $datadictDao->getCodeByName('HRPXKH',$val['assessmentName']);
							if(!empty($rs)) {
								$assessment = $datadictArr[$val['assessmentName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĿ�������</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$assessment = $datadictArr[$val['assessmentName']]['code'];
						}
						$inArr['assessment'] = $assessment;
						$inArr['assessmentName'] = $val['assessmentName'];
					}

					//�γ���������
					if(!empty($val['courseEvaluateScore']) && trim($val['courseEvaluateScore']) != '') {
						$inArr['courseEvaluateScore'] = $val['courseEvaluateScore'];
					}

					//��ѵ��֯��������
					if(!empty($val['trainsOrgEvaluateScore']) && trim($val['trainsOrgEvaluateScore']) != '') {
						$inArr['trainsOrgEvaluateScore'] = $val['trainsOrgEvaluateScore'];
					}

					//Ч������Ч����ʱ��
					if(!empty($val['followTime']) && trim($val['followTime']) != '') {
						$val['followTime'] = trim($val['followTime']);
						if(!is_numeric($val['followTime'])) {
							$inArr['followTime'] = $val['followTime'];
						} else {
							$followTime = date('Y-m-d',(mktime(0,0,0,1, $val['followTime'] - 1 , 1900)));
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

					if($newId){
						$tempArr['result'] = '����ɹ�';
					}else{
						$tempArr['result'] = '<span class="red">����ʧ��</span>';
					}
					$tempArr['docCode'] = '��' . $actNum .'������';
					array_push( $resultArr,$tempArr );
				}

				//���¿γ̵���ѵ��ʦ
//				$ids = implode($courseIdArr,',');
//				$courseDao->updateTeacher_d($ids);
				return $resultArr;
			}
		}
	}
	/******************* E ���뵼��ϵ�� ************************/
}
?>