<?php

/**
 * @author Show
 * @Date 2012��5��24�� ������ 10:00:14
 * @version 1.0
 * @description:н����Ϣ Model��
 */
class model_hr_reward_rewardrecords extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_reward_records";
		$this->sql_map = "hr/reward/rewardrecordsSql.php";
		parent :: __construct();
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
		$userConutArr = array();//�û�����
		$userArr = array();//�û�����
		$deptArr = array();//��������
		$jobsArr = array();//ְλ����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
//			print_r($excelData);
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
					if($key === 0){
						continue ;
					}
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])&& empty($val[5])){
						continue;
					}else{
						//��������
						$inArr = array();

						//��ʦԱ�����
						if(!empty($val[0])){
							if(!isset($userArr[$val[0]])){
								$rs = $otherDataDao->getUserInfoByUserNo($val[0]);
								if(!empty($rs)){
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

						//��ʦ����
						if(!empty($val[1])){
							$inArr['userName'] = $val[1];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û��Ա������</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��������
						if(!empty($val[2])){
							if(!isset($deptArr[$val[2]])){
								$rs = $otherDataDao->getDeptId_d($val[2]);
								if(!empty($rs)){
									$deptArr[$val[2]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĶ�������</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['deptNameS'] = $val[2];
							$inArr['deptIdS'] = $deptArr[$val[2]];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û�ж�������</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��������
						if(!empty($val[3])){
							if(!isset($deptArr[$val[3]])){
								$rs = $otherDataDao->getDeptId_d($val[3]);
								if(!empty($rs)){
									$deptArr[$val[3]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���������</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['deptNameT'] = $val[3];
							$inArr['deptIdT'] = $deptArr[$val[3]];
						}

						//Ա��ְλ
						if(!empty($val[4])){
							if(!isset($jobsArr[$val[4]])){
								$rs = $otherDataDao->getJobId_d($val[4]);
								if(!empty($rs)){
									$jobsArr[$val[4]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�Ա��ְλ</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['jobName'] = $val[4];
							$inArr['jobId'] = $jobsArr[$val[4]];
						}

						//��н�·�
						if(!empty($val[5])){
							$inArr['rewardPeriod'] = $val[5];
							$rewardDate = $inArr['rewardPeriod'] . '-01';
							$date   =   explode( "-",   $rewardDate);
							if(checkdate($date[1],$date[2],$date[0])){
								$inArr['rewardDate'] = $rewardDate;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ�ܣ�����ķ�н�·ݸ�ʽ���ԣ�Ӧ��ΪYYYY-MM </span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">û����д��н�·�</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//���¹�����
						if(!empty($val[6])){
							$inArr['workDays'] = $val[6];
						}

						//��������
						if(!empty($val[7])){
							$inArr['basicWage'] = $val[7];
						}else{
							$inArr['basicWage'] = 0;
						}

						//���˹�����
						if(!empty($val[8])){
							$inArr['provident'] = $val[8];
						}else{
							$inArr['provident'] = 0;
						}

						//�����籣
						if(!empty($val[9])){
							$inArr['socialSecurity'] = $val[9];
						}else{
							$inArr['socialSecurity'] = 0;
						}

						//�ر���
						if(!empty($val[10])){
							$inArr['specialBonus'] = $val[10];
						}else{
							$inArr['specialBonus'] = 0;
						}

						//�ر�ۿ�
						if(!empty($val[11])){
							$inArr['specialDeduction'] = $val[11];
						}else{
							$inArr['specialDeduction'] = 0;
						}

						//��Ŀ����
						if(!empty($val[12])){
							$inArr['projectBonus'] = $val[12];
						}else{
							$inArr['projectBonus'] = 0;
						}

						//�ͷѲ���
						if(!empty($val[13])){
							$inArr['mealSubsidies'] = $val[13];
						}else{
							$inArr['mealSubsidies'] = 0;
						}

						//��������
						if(!empty($val[14])){
							$inArr['otherSubsidies'] = $val[14];
						}else{
							$inArr['otherSubsidies'] = 0;
						}

						//����
						if(!empty($val[15])){
							$inArr['otherBonus'] = $val[15];
						}else{
							$inArr['otherBonus'] = 0;
						}

						//�¼�
						if(!empty($val[16])){
							$inArr['leaveDays'] = $val[16];
						}else{
							$inArr['leaveDays'] = 0;
						}

						//����
						if(!empty($val[17])){
							$inArr['sickDays'] = $val[17];
						}else{
							$inArr['sickDays'] = 0;
						}

						//˰ǰ����
						if(!empty($val[18])){
							$inArr['preTaxWage'] = $val[18];
						}else{
							$inArr['preTaxWage'] = 0;
						}

						//˰��
						if(!empty($val[19])){
							$inArr['taxes'] = $val[19];
						}else{
							$inArr['taxes'] = 0;
						}

						//ʵ������
						if(!empty($val[20])){
							$inArr['afterTaxWage'] = $val[20];
						}else{
							$inArr['afterTaxWage'] = 0;
						}

						//��ע
						if(!empty($val[21])){
							$inArr['remark'] = $val[21];
						}

//						print_r($inArr);
						$newId = $this->add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '����ɹ�';
						}else{
							$tempArr['result'] = '<span class="red">����ʧ��</span>';
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