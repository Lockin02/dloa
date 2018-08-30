<?php

/**
 * @author Show
 * @Date 2012��5��25�� ������ 14:55:28
 * @version 1.0
 * @description:���͹��� Model��
 */
class model_hr_incentive_incentive extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_incentive";
		$this->sql_map = "hr/incentive/incentiveSql.php";
		parent :: __construct();
	}

	//��Ҫ�����ֵ䴦����ֶ�
    public $datadictFieldArr = array(
		'incentiveType'
	);

	//��дadd_d
	function add_d($object){
		$object = $this->processDatadict($object);
		$id=parent::add_d($object,true);

		//���¸���������ϵ
		$this->updateObjWithFile ( $id );
//ע��by zengzx
//		$uploadFile = new model_file_uploadfile_management ();
//		$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
		return $id;
	}

	//��дedit
	function edit_d($object){
		$object = $this->processDatadict($object);

		return parent::edit_d($object,true);
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
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
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

						//��ʦԱ�����
						if(!empty($val[0])){
							if(!isset($userArr[$val[0]])){
								$rs = $otherDataDao->getUserInfoByUserNo(trim($val[0]));
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
							if( $rs['userName'] == trim($val[1]) ){
								$inArr['userName'] = trim($val[1]);
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!Ա�������Ա��������ƥ�䡣</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û��Ա������</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//����ԭ��
						if(!empty($val[2])){
							$inArr['reason'] = $val[2];
						}

						//��������
						if(!empty($val[3])){
							$val[3] = trim($val[3]);
							if(!isset($datadictArr[$val[3]])){
								$rs = $datadictDao->getCodeByName('HRJLSS',$val[3]);
								if(!empty($rs)){
									$incentiveType = $datadictArr[$val[3]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĽ�������</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$incentiveType = $datadictArr[$val[3]]['code'];
							}
							$inArr['incentiveType'] = $incentiveType;
							$inArr['incentiveTypeName'] = $val[3];
						}

						//��������
						if(!empty($val[4])&& $val[4] != '0000-00-00'){
							$val[4] = trim($val[4]);
								$incentiveDate1 = $val[4] . '-01';
								$date   =   explode( "-",   $incentiveDate1);
								if(checkdate($date[1],$date[2],$date[0])){
									$incentiveDate = $incentiveDate1;
								}else{
									$incentiveDate = date('Y-m-d',(mktime(0,0,0,1, $val[4] - 1 , 1900)));
								}
								$inArr['incentiveDate'] = $incentiveDate;
						}

						//���赥λ
						if(!empty($val[5])){
							$inArr['grantUnitName'] = $val[5];
						}

						//�����·�
						if(!empty($val[6])){
							$inArr['rewardPeriod'] = $val[6];
							$rewardDate = $inArr['rewardPeriod'] . '-01';
							$date   =   explode( "-",   $rewardDate);
							if(checkdate($date[1],$date[2],$date[0])){
								$inArr['rewardDate'] = $rewardDate;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ�ܣ�����Ĺ����·ݸ�ʽ���ԣ�Ӧ��ΪYYYY-MM </span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">û����д�����·�</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//���ͽ��
						if(!empty($val[7])){
							$inArr['incentiveMoney'] = $val[7];
						}

						//����˵��
						if(!empty($val[8])){
							$inArr['description'] = $val[8];
						}

						//��¼ʱ��
						if(!empty($val[9])&& $val[9] != '0000-00-00'){
							$val[9] = trim($val[9]);

								$recorderDate1 = $val[9] . '-01';
								$date   =   explode( "-",   $incentiveDate1);
								if(checkdate($date[1],$date[2],$date[0])){
									$recorderDate = $recorderDate1;
								}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[9] - 1 , 1900)));
								}
								$inArr['recordDate'] = $recorderDate;
						}

						//��¼��
						if(!empty($val[10])){
							if(!isset($userArr[$val[10]])){
								$rs = $otherDataDao->getUserInfo($val[10]);
								if(!empty($rs)){
									$userArr[$val[10]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵļ�¼������</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}

							$inArr['recorderId'] = $userArr[$val[10]]['USER_ID'];
							$inArr['recorderName'] = $val[10];
						}

						//��ע
						if(!empty($val[12])){
							$inArr['remark'] = $val[12];
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