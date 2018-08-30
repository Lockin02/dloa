<?php

/*
 * @author: zengq
 * Created on 2012-10-16
 *
 * @description: ��Ƹ�ƻ� Model��
 */
class model_hr_recruitment_plan extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitplan_plan";
		$this->sql_map = "hr/recruitment/planSql.php";

		$this->statusDao = new model_common_status();
		$this->statusDao->status = array (
		0 => array (
				'statusEName' => 'save',
				'statusCName' => '����',
				'key' => '0'
				),
				1 => array (
				'statusEName' => 'nocheck',
				'statusCName' => 'δ�´�',
				'key' => '1'
				),
				2 => array (
				'statusEName' => 'recruiting',
				'statusCName' => '��Ƹ��',
				'key' => '2'
				),
				3 => array (
				'statusEName' => 'abord',
				'statusCName' => '��ͣ',
				'key' => '3'
				),
				4 => array (
				'statusEName' => 'finish',
				'statusCName' => '���',
				'key' => '4'
				),
				5 => array (
				'statusEName' => 'closed',
				'statusCName' => '�ر�',
				'key' => '5'
				)
				);
				//var_dump($this->statusDao->statusEtoK ( 'nocheck' ));
				parent :: __construct();
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
		$userArr = array (); //�û�����
		$deptArr = array (); //��������
		$jobsArr = array (); //ְλ����
		$otherDataDao = new model_common_otherdatas(); //������Ϣ��ѯ
		$datadictArr = array (); //�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		$deptDao = new model_deptuser_dept_dept();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {

			$excelData = util_excelUtil :: upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register("__autoload");

			if (is_array($excelData)) {

				//������ѭ��
				foreach ($excelData as $key => $val) {
					//					if ($key === 0) {
					//						continue;
					//					}

					$actNum = $key +1;
					if (empty ($val[0]) && empty ($val[1])) {
						continue;
					} else {

						//��������
						$inArr = array ();

						//���ݱ��
						$inArr['formCode'] ='ZP'.$this->getRandNum();
						//���״̬
						$inArr['ExaStatus']='���';
						//ְλ����
						if (!empty ($val[0])) {
							$inArr['postTypeName'] = $val[0];
						}

						//����
						if (!empty ($val[1])) {
							$rs = $otherDataDao->getDeptInfo_d($val[1]);
							if (!empty ($rs)) {
								$inArr['deptId'] = $rs['DEPT_ID'];
								$inArr['deptName'] = $val[1];
							} else {
								$tempArr['docCode'] = '��' . $actNum . '������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĲ���</font>';
								array_push($resultArr, $tempArr);
								continue;
							}
						}

						//�����з�����
						if (!empty ($val[2])) {
							$inArr['useAreaId']=$this->get_table_fields('area', "Name='".$val[2]."'", 'ID');
							$inArr['useAreaName']=$val[2];
						}

						//�����ص�
						if (!empty ($val[3])) {
							$inArr['workPlace'] = $val[3];
						}

						//Ա��ְλ
						if (!empty ($val[4])) {
							$val[4]=trim($val[4]);
							$rs = $otherDataDao->getJobId_d($val[4]);
							if (!empty ($rs)) {
								$inArr['positionName'] = $val[4];
								$inArr['positionId'] = $rs;
							} else {
								$tempArr['docCode'] = '��' . $actNum . '������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա��ְλ</font>';
								array_push($resultArr, $tempArr);
							}

						}

						//����
						if (!empty ($val[5])) {
							$inArr['positionLevel'] = $val[5];
						}

						//�Ƿ����
						if (!empty ($val[6])) {
							$val[6] = trim($val[6]);
							if($val[6]=='��'||$val[6]='��')
							$inArr['isEmergency'] = $val[6];
						}

						//������Ŀ��
						if (!empty ($val[7])) {
							$inArr['projectGroup'] = $val[7];
							$inArr['projectCode'] = $this->get_table_fields('oa_rd_project', "projectName='".$val[7]."'", 'projectCode');
						}

						//��Ƹ��������
						if (!empty ($val[8])) {
							$val[8] = trim($val[8]);
							$dictDao = new model_system_datadict_datadict();
							if ($val[8] == '�ƻ�����Ա' || $val[8] == '�ƻ�����Ա' || $val[8] == '��ְ����'||$val[8]=='����') {
								$inArr['addTypeCode']=$dictDao->getCodeByName($val[8]);
								$inArr['addType'] = $val[8];
							}
						} else {
							$inArr['addType'] = "";
							$inArr['addTypeCode']= "";
						}

						//�����Žӿ���
						if (!empty ($val[9])) {
							$val[9] = trim($val[9]);
							$resumeToId = $this->get_table_fields('user', "USER_NAME='".$val[9]."'", 'USER_ID');
							if($resumeToId!=null){
								$inArr['resumeToName'] = $val[9];
								$inArr['resumeToId'] = $resumeToId;
							}
						}
						//������
						if (!empty ($val[10])) {
							$recruitManId = $this->get_table_fields('user', "USER_NAME='".$val[10]."'", 'USER_ID');
							if($recruitManId!=null){
								$inArr['recruitManName'] = $val[10];
								$inArr['recruitManId'] = $recruitManId;
							}
						}
						//Э����
						if (!empty ($val[11])) {
							$assistManId = $this->get_table_fields('user', "USER_NAME='".$val[11]."'", 'USER_ID');
							if($assistManId!=null){
								$inArr['assistManName'] = $val[11];
								$inArr['assistManId'] = $recruitManId;
							}
						}
						//״̬
						if (!empty ($val[12])) {
							$inArr['state'] = $val[12];
						}
						//�����������
						if (!empty ($val[13])) {
							$inArr['formDate'] = $val[13];
						}
						//��������
						if (!empty ($val[14])) {
							$inArr['needNum'] = $val[14];
						}

						//����ְ����
						if (!empty ($val[15])) {
							$inArr['entryNum'] = $val[15];
						}

						//����ְ����
						if (!empty ($val[16])) {
							$inArr['beEntryNum'] = $val[16];
						}
						//��Ƹԭ��/����
						if (!empty ($val[20])) {
							$inArr['applyReason'] = $val[20];
						}
						//��λְ����ְҪ��
						if (!empty ($val[21])) {
							$inArr['workDuty'] = $val[21];
						}
						$newId = parent :: add_d($inArr, true);
						if ($newId) {
							$tempArr['result'] = '����ɹ�';
						} else {
							$tempArr['result'] = '����ʧ��';
						}
						$tempArr['docCode'] = '��' . $actNum . '������';
						array_push($resultArr, $tempArr);
					}
				}
				return $resultArr;
			}
		}
	}
	/**
	 * ����ʮλ�������
	 */
	function getRandNum(){
		$num = '';
		for($i=0;$i<10;$i++){
			$num.=rand(0,9);
		}
		return $num;
	}
}
?>
