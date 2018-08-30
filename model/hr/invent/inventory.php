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
 * @author Administrator
 * @Date 2012��5��30�� 14:38:15
 * @version 1.0
 * @description:Ա���̵�� Model��
 */
 class model_hr_invent_inventory  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_inventory";
		$this->sql_map = "hr/invent/inventorySql.php";
		parent::__construct ();
	}

	/**
	 * Ա���̵���Ϣ����
	 */
	 function import_d($objKeyArr){
	 	try{
			set_time_limit(0);
	 		$this->start_d();
	 		$returnFlag = true;
			$service = $this->service;
			$filename = $_FILES ["inputExcel"] ["name"];
			$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
			$fileType = $_FILES ["inputExcel"] ["type"];
			$resultArr = array();
			$objectArr = array();
			$excelData = array ();
			//�жϵ��������Ƿ�Ϊexcel��
			if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
				$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
				spl_autoload_register("__autoload");
				//�ж��Ƿ��������Ч����
				unset($excelData[0]);
				$errorCodeArr = array();
				if ($excelData) {
					foreach ( $excelData as $rNum => $row ) {
						foreach ( $objKeyArr as $index => $fieldName ) {
							//��ֵ������Ӧ���ֶ�
							$objectArr [$rNum] [$fieldName] = $row [$index];
						}
					}
					foreach ($objectArr as $key=>$val){
						//��ʽ�����룬ɾ������Ŀո��������Ϊ�գ���������ݲ�����Ч��
						$objectArr[$key]['userNo'] = str_replace( ' ','',$val['userNo']);
						$objectArr[$key]['userName'] = str_replace( ' ','',$val['userName']);
						if( empty($val['userNo']) && empty($val['userName'])
							&& empty($val['inventoryDate']) && empty($val['entryDate'])) {
							unset($objectArr[$key]);
							continue;
						}else if( empty($val['userNo']) || empty($val['userName'])
							|| empty($val['inventoryDate']) || empty($val['entryDate'])) {
							$errorCodeArr[$key]['docCode']=$key+2;
							$errorCodeArr[$key]['result']='������Ϊ�գ�����ʧ��';
							unset($objectArr[$key]);
							continue;
						} else {
							$codeRuleDao = new model_common_codeRule();
							$userDao = new model_deptuser_user_user();
							$userId = $codeRuleDao->getUserIdByCard($val['userNo']);
							if( empty($userId) ){
								$errorCodeArr[$key]['docCode']=$key+2;
								$errorCodeArr[$key]['result']='Ա��������󣬵���ʧ��';
								unset($objectArr[$key]);
								continue;
							} else {
								$userInfo = $userDao->getUserById($userId);
								$userName = $userInfo['USER_NAME'];
								if( $userName!=$val['userName'] ){
									$errorCodeArr[$key]['docCode']=$key+2;
									$errorCodeArr[$key]['result']='Ա�����������Ʋ���Ӧ������ʧ��';
									unset($objectArr[$key]);
									continue;
								} else {
									$objectArr[$key]['userAccount']=$userId;
									$objectArr[$key]['companyName']=$userInfo['Company'];
								}
							}
							if( empty($val['deptNameS']) ){
								$deptIdSArr = $deptDao->getDeptId_d($val['deptNameS']);
								if($deptIdSArr['DEPT_ID']){
									$objectArr[$key]['deptIdS']=$deptIdSArr['DEPT_ID'];
								} else {
									$errorCodeArr[$key]['docCode']=$key+2;
									$errorCodeArr[$key]['result']='���Ų����ڣ�����ʧ��';
									unset($objectArr[$key]);
									continue;
								}
							}
							//�̵�����
							$inventoryDate = mktime(0, 0, 0, 1, $objectArr[$key]['inventoryDate'] - 1, 1900);
							$objectArr[$key]['inventoryDate'] = date("Y-m-d", $inventoryDate);
							//��ְ����
							$entryDate = mktime(0, 0, 0, 1, $objectArr[$key]['entryDate'] - 1, 1900);
							$objectArr[$key]['entryDate'] = date("Y-m-d", $entryDate);
						}
					}
					if( count($errorCodeArr)>0 ){
				 		$returnFlag = false;
						$title = 'Ա���̵���Ϣ������';
						$thead = array( '�к�','���' );
						echo "<script>alert('����ʧ��')</script>";
						echo util_excelUtil::showResult($errorCodeArr,$title,$thead);
					} else {
						$this->saveDelBatch($objectArr);
						echo "<script>alert('����ɹ�');self.parent.tb_remove();if(self.parent.show_page)self.parent.show_page(1);</script>";
					}
				} else {
					msg( "�ļ������ڿ�ʶ������!");
				}
			} else {
				msg( "�ϴ��ļ����Ͳ���EXCEL!");
			}
	 		$this->commit_d();
	 		return $returnFlag;
	 	}catch(Exception $e){
	 		$this->rollBack();
	 		return 0;
	 	}
	}

	function addExecelData_d(){
		ini_set('memory_limit', '-1');
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
		$deptArr = array();//��������
		$jobsArr = array();//ְλ����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {
				$newData = array(); //ת��excel����
				foreach ($excelData as $key => $val) {
					$newData[$key]['userNo'] = $val[0];
					$newData[$key]['userName'] = $val[1];
					$newData[$key]['deptNameS'] = $val[2];
					$newData[$key]['position'] = $val[3];
					$newData[$key]['inventoryDate'] = $val[4];
					$newData[$key]['entryDate'] = $val[5];
					$newData[$key]['alternative'] = $val[6];
					$newData[$key]['matching'] = $val[7];
					$newData[$key]['isCritical'] = $val[8];
					$newData[$key]['critical'] = $val[9];
					$newData[$key]['isCore'] = $val[10];
					$newData[$key]['recruitment'] = $val[11];
					$newData[$key]['performance'] = $val[12];
					$newData[$key]['examine'] = $val[13];
					$newData[$key]['preEliminated'] = $val[14];
					$newData[$key]['remark'] = $val[15];
					$newData[$key]['adjust'] = $val[16];
					$newData[$key]['workQuality'] = $val[17];
					$newData[$key]['workEfficiency'] = $val[18];
					$newData[$key]['workZeal'] = $val[19];
				}

				//������ѭ��
				foreach($newData as $key => $val){
					$actNum = $key + 2;
					if(empty($val['userNo']) && empty($val['deptNameS'])) {
						continue;
					} else {
						//��������
						$inArr = array();

						//Ա�����
						if(!empty($val['userNo'])) {
							if(!isset($userArr[$val['userNo']])) {
								$rs = $otherDataDao->getUserInfoByUserNo(trim($val['userNo']));
								if(!empty($rs)) {
									$inArr['userAccount'] = $rs['USER_ID'];
									$inArr['userNo'] = trim($val['userNo']);
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա�����</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!û��Ա�����</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//Ա������
						if(!empty($val['userName'])) {
							if(!isset($userArr[$val['userName']])) {
								$rs = $otherDataDao->getUserInfo(trim($val['userName']));
								if(!empty($rs)) {
									$inArr['userName'] = trim($val['userName']);
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա������</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!û��Ա������</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//����
						if(!empty($val['deptNameS']) && trim($val['deptNameS']) != '') {
							$val['deptNameS'] = trim($val['deptNameS']);
							if(!isset($deptArr[$val['deptNameS']])) {
								$deptIdS = $otherDataDao->getDeptId_d($val['deptNameS']);
								$inArr['deptNameS'] = $val['deptNameS'];
								$inArr['deptIdS'] = $deptIdS;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û�в���</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//Ա��ְλ
						if(!empty($val['position']) && trim($val['position']) != '') {
							$val['position'] = trim($val['position']);
							if(!isset($jobsArr[$val['position']])) {
								$jobId = $otherDataDao->getJobId_d($val['position']);
								if(empty($jobId)) {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�Ա��ְλ</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['position'] = $val['position'];
						}

						//�̵�����
						if(!empty($val['inventoryDate']) && trim($val['inventoryDate']) != '') {
							$val['inventoryDate'] = trim($val['inventoryDate']);
							if(!is_numeric($val['inventoryDate'])) {
								$inArr['inventoryDate'] = $val['inventoryDate'];
							} else {
								$beginDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['inventoryDate'] - 1 ,1900)));
								if($beginDate == '1970-01-01') {
									$inventoryDate1 = date('Y-m-d',strtotime($val['inventoryDate']));
									$inArr['inventoryDate'] = $inventoryDate1;
								} else {
									$inArr['inventoryDate'] = $beginDate;
								}
							}
						}

						//��ְ����
						if(!empty($val['entryDate']) && trim($val['entryDate']) != '') {
							$val['entryDate'] = trim($val['entryDate']);
							if(!is_numeric($val['entryDate'])) {
								$inArr['entryDate'] = $val['entryDate'];
							} else {
								$beginDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['entryDate'] - 1 ,1900)));
								if($beginDate == '1970-01-01') {
									$entryDate1 = date('Y-m-d' ,strtotime($val['entryDate']));
									$inArr['entryDate'] = $entryDate1;
								} else {
									$inArr['entryDate'] = $beginDate;
								}
							}
						}

						//��ְλ���г��������
						if(!empty($val['alternative'])) {
							$val['alternative'] = trim($val['alternative']);

							if($val['alternative'] == '��' || $val['alternative'] == '��' || $val['alternative'] == '��') {
								$inArr['alternative'] = $val['alternative'];
							} else {
								$inArr['alternative'] = '';
							}
						}

						//�ֹ�������������ְλ��ƥ���
						if(!empty($val['matching'])) {
							$val['matching'] = trim($val['matching']);

							if($val['matching'] == '��' || $val['matching'] == '��' || $val['matching'] == '��') {
								$inArr['matching'] = $val['matching'];
							} else {
								$inArr['matching'] = '';
							}
						}

						//�Ƿ�ؼ�Ա��
						if(!empty($val['isCritical'])) {
							$val['isCritical'] = trim($val['isCritical']);

							if($val['isCritical'] == '��' || $val['isCritical'] == '��') {
								$inArr['isCritical'] = $val['isCritical'];
							} else {
								$inArr['isCritical'] = '';
							}
						}

						//Ա���ؼ���
						if(!empty($val['critical'])) {
							$val['critical'] = trim($val['critical']);

							if($val['critical'] == '��' || $val['critical'] == '��' || $val['critical'] == '��') {
								$inArr['critical'] = $val['critical'];
							} else {
								$inArr['critical'] = '';
							}
						}

						//�Ƿ�Ϊ���ı����˲�
						if(!empty($val['isCore'])) {
							$val['isCore'] = trim($val['isCore']);

							if($val['isCore'] == '��' || $val['isCore'] == '��') {
								$inArr['isCore'] = $val['isCore'];
							} else {
								$inArr['isCore'] = '';
							}
						}

						//��ְλ���г���Ƹ�Ѷ�
						if(!empty($val['recruitment'])) {
							$val['recruitment'] = trim($val['recruitment']);

							if($val['recruitment'] == '��' || $val['recruitment'] == '��' || $val['recruitment'] == '��') {
								$inArr['recruitment'] = $val['recruitment'];
							} else {
								$inArr['recruitment'] = '';
							}
						}

						//�Լ�Ч������������
						if(!empty($val['performance'])) {
							$val['performance'] = trim($val['performance']);

							if($val['performance'] == '��' || $val['performance'] == '��' || $val['performance'] == '��') {
								$inArr['performance'] = $val['performance'];
							} else {
								$inArr['performance'] = '';
							}
						}

						//��һ���ȿ����Ƿ��ź�5%
						if(!empty($val['examine'])) {
							$val['examine'] = trim($val['examine']);

							if($val['examine'] == '��' || $val['examine'] == '��') {
								$inArr['examine'] = $val['examine'];
							} else {
								$inArr['examine'] = '';
							}
						}

						//�Ƿ�ΪԤ��̭��Ա
						if(!empty($val['preEliminated'])) {
							$val['preEliminated'] = trim($val['preEliminated']);

							if($val['preEliminated'] == '��' || $val['preEliminated'] == '��') {
								$inArr['preEliminated'] = $val['preEliminated'];
							} else {
								$inArr['preEliminated'] = '';
							}
						}

						//�Ƿ��п�����ʧ�Լ�ԭ��
						if(!empty($val['remark'])) {
							$inArr['remark'] = $val['remark'];
						}

						//�Դ�Ա���ĺ�����������
						if(!empty($val['adjust'])) {
							$inArr['adjust'] = $val['adjust'];
						}

						//��������
						if(!empty($val['workQuality'])) {
							$inArr['workQuality'] = $val['workQuality'];
						}

						//����Ч��
						if(!empty($val['workEfficiency'])) {
							$inArr['workEfficiency'] = $val['workEfficiency'];
						}

						//��������
						if(!empty($val['workZeal'])) {
							$inArr['workZeal'] = $val['workZeal'];
						}

						$newId = parent::add_d($inArr ,true);

						if($newId) {
							$tempArr['result'] = '����ɹ�';
						} else {
							$tempArr['result'] = '<font color=red>����ʧ��</font>';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push($resultArr ,$tempArr);
					}
				}
				return $resultArr;
			}
		}
	}

 }
?>