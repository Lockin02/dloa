<?php
/**
 * @author Show
 * @Date 2012��5��30�� ������ 9:56:29
 * @version 1.0
 * @description:��ѵ����-��ʦ���� Model��
 */
class model_hr_training_teacher extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_training_teacher";
		$this->sql_map = "hr/training/teacherSql.php";
		parent :: __construct();
	}

	public $datadictFieldArr = array (
		'levelId'
	);

	//�����Ƿ���ѵʦ
	function rtYN_d($val){
		if($val == 1){
			return '��';
		}else{
			return '��';
		}
	}

	//��дadd
	function add_d($object){
		$object = $this->processDatadict($object);

		return parent::add_d($object,true);
	}

	//��д�༭
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
		$userArr = array();//�û�����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$perpDao = new model_hr_personnel_personnel();//������Ϣ��ѯ
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
					if(empty($val[1]) && empty($val[2]) && empty($val[5])){
						continue;
					}else{
						//��������
						$inArr = array();

						//-----��ʦ���
						if (!empty($val[0])){
							$val[0] = trim($val[0]);
							$inArr['teacherNum'] = $val[0];
						}
						//��ʦ����
						if(!empty($val[1])){
							$val[1] = trim($val[1]);
							if(!isset($userArr[$val[1]])){
								$rs = $otherDataDao->getUserInfo($val[1]);
								if(!empty($rs)){
									$userArr[$val[1]] = $rs;
								}else{
									$userArr[$val[1]] = array('USER_ID' => '');
								}
							}
							$inArr['teacherName'] = $val[1];
							$inArr['teacherAccount'] = $userArr[$val[1]]['USER_ID'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û����д��ʦ����</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//---��ѵ����
						if(!empty($val[2])){
							$val[2] = trim($val[2]);
							$inArr['trainingAgency'] = $val[2];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û����д��ѵ����</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//----��ʦ����
						if(!empty($val[3])){
							$val[3] = trim($val[3]);
//							if(!isset($userArr[$val[3]])){
//								//��ѯ��ʦ��ź͵�ʦ�����Ƿ�ƥ��
//								if(!empty($val[0])&&!empty($val[1])){
//							$rs1=$perpDao->find(array("userNo"=>$val[0]));
//								if ($rs1['userNo']==$val[0]&&$rs1['userName']==$val[1]){
//									if ($val[3]!=$rs1['belongDeptName']){
//												$tempArr['docCode'] = '��' . $actNum .'������';
//												$tempArr['result'] = '<span class="red">����ʧ��!��д��ʦ��λ����</span>';
//												array_push( $resultArr,$tempArr );
//												continue;
//									}else{
//										$userArr[$val[3]]=$rs1;
//									}
//								}else{
//									$userArr[$val[3]]=array('belongDeptId' => '');
//								}
//								}else{
//									$userArr[$val[3]]=array('belongDeptId' => '');
//								}
//									if (!empty($rs1)){
//										$userArr[$val[3]]=$rs1;
////										if ($val[3]!=$rs1['belongDeptName']){
////											$tempArr['docCode'] = '��' . $actNum .'������';
////											$tempArr['result'] = '<span class="red">����ʧ��!��д��ʦ��λ����</span>';
////											array_push( $resultArr,$tempArr );
////											continue;
////										}
//									}else{
//										$userArr[$val[3]]=array('belongDeptId' => '');
//									}
//								}
//
									$inArr['belongDeptName'] = $val[3];
//									$inArr['belongDeptId'] = $userArr[$val[3]]['belongDeptId'];
//						}else{
//							$tempArr['docCode'] = '��' . $actNum .'������';
//							$tempArr['result'] = '<span class="red">����ʧ��!û����д��ʦ����</span>';
//							array_push( $resultArr,$tempArr );
//							continue;
						}


						//----��ʦ��λ
						if(!empty($val[4])){
//							echo "<pre>";
//							print_r($val[4]);
							$val[4] = trim($val[4]);
//							if(!isset($userArr[$val[4]])){
//							$rs1=$perpDao->find(array("userNo"=>$val[0]));
//								if (!empty($rs1)){
//										$userArr[$val[4]]=$rs1;
//									if ($val[4]!=$rs1['jobName']){
//										$tempArr['docCode'] = '��' . $actNum .'������';
//										$tempArr['result'] = '<span class="red">����ʧ��!��д��ʦ��λ����</span>';
//										array_push( $resultArr,$tempArr );
//										continue;
//									}
//								}else{
//									$userArr[$val[4]]=array('lecturerPostId' => '');
//								}
//							}
								$inArr['lecturerPost'] = $val[4];
//								$inArr['lecturerPostId'] = $userArr[$val[4]]['jobId'];
//						}else{
//							$tempArr['docCode'] = '��' . $actNum .'������';
//							$tempArr['result'] = '<span class="red">����ʧ��!û����д��ʦ����</span>';
//							array_push( $resultArr,$tempArr );
//							continue;
						}

						//----��ʦ���
						if (!empty($val[5])){
							if($val[5]=="��ѵʦ" || $val[5]=="��ʱ��ʦ"|| $val[5]=="�ⲿ��ʦ"){
							$val[5] = trim($val[5]);
							$inArr['lecturerCategory'] = $val[5];
							}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!���뽲ʦ�������</span>';
							array_push( $resultArr,$tempArr );
							continue;
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û����д��ʦ���</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}
//
//						//�Ƿ���ѵʦ
//						if(!empty($val[1])){
//							if($val[1] == '��'){
//								$inArr['isInner'] = 1;
//							}else{
//								$inArr['isInner'] = 0;
//							}
//						}else{
//							$tempArr['docCode'] = '��' . $actNum .'������';
//							$tempArr['result'] = '<span class="red">����ʧ��!û����д�Ƿ���ѵʦ</span>';
//							array_push( $resultArr,$tempArr );
//							continue;
//						}
//
//						//��ѵʱ��
//						if(!empty($val[2])&& $val[2] != '0000-00-00'){
//							$val[2] = trim($val[2]);
//
//							if(!is_numeric($val[2])){
//								$inArr['certifyDate'] = $val[2];
//							}else{
//								$certifyDate = date('Y-m-d',(mktime(0,0,0,1, $val[2] - 1 , 1900)));
//								$inArr['certifyDate'] = $certifyDate;
//							}
//						}
//
						//��ѵʦ����
						if(!empty($val[6])){
							$val[6] = trim($val[6]);
							if(!isset($datadictArr[$val[6]])){
								$rs = $datadictDao->getCodeByName('HRNSSJB',$val[6]);
								if(!empty($rs)){
									$levelId = $datadictArr[$val[6]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���ѵʦ����</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$levelId = $datadictArr[$val[6]]['code'];
							}
							$inArr['levelId'] = $levelId;
							$inArr['levelName'] = $val[6];
						}

						//��֤ʱ��
						if(!empty($val[7])){
							$inArr['certifyDate'] = trim($val[7]);
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!��֤ʱ��δ��д</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}
					if(!empty($val[7])&& $val[7] != '0000-00-00'){
						$val['certifyDate'] = trim($val[7]);
						if(!is_numeric($val[7])){
							$inArr['certifyDate'] = $val[7];
//							$tempArr['docCode'] = '��' . $actNum .'������';
//							$tempArr['result'] = '<span class="red">����ʧ��!����ʱ������</span>';
//							array_push( $resultArr,$tempArr );
//							continue;
						}else{
							$teachDate = date('Y-m-d',(mktime(0,0,0,1, $val[7] - 1 , 1900)));
							$inArr['certifyDate'] = $teachDate;
						}
					}


						//��֤����
						if(!empty($val[8])){
							$inArr['scores'] = $val[8];
						}
//
						//���ڿγ�
						if(!empty($val[9])){
							$inArr['courses'] = $val[9];
						}

						$inArr['remark'] = 'ϵͳ������Ϣ';
//						echo "<pre>";
//						print_r($inArr);
						$newId = $this->add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '����ɹ�';
						}else{
							$tempArr['result'] = '<span class="red">����ʧ��<span class="red">';
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