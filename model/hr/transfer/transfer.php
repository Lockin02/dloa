<?php
/**
 * @author Show
 * @Date 2012��5��28�� ����һ 13:38:56
 * @version 1.0
 * @description:��Ա���ü�¼ Model��
 */

include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";


 class model_hr_transfer_transfer  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_transfer";
		$this->sql_map = "hr/transfer/transferSql.php";
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
			0 => array (
				'statusEName' => 'notsub',
				'statusCName' => 'δ�ύ',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'notview',
				'statusCName' => 'δ���',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'notcommit',
				'statusCName' => 'Ա����ȷ��',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'commited',
				'statusCName' => 'Ա����ȷ��',
				'key' => '3'
			),
			4 => array (
				'statusEName' => 'finis',
				'statusCName' => '����������',
				'key' => '4'
			),
			5 => array (
				'statusEName' => 'wait',
				'statusCName' => '�����Ѹ���',
				'key' => '5'
			),
			6 => array (
				'statusEName' => 'done',
				'statusCName' => '���',
				'key' => '6'
			),
			7 => array (
				'statusEName' => 'yesview',
				'statusCName' => '�����',
				'key' => '7'
			)
		);
		parent::__construct ();
	}

	//��Ҫ�����ֵ䴦����ֶ�
    public $datadictFieldArr = array(
		'transferType'
	);

	/**
	 * ��дadd_d
	 */
	function add_d($object){
	 	try{
			$this->start_d();
			$object = $this->processDatadict($object);
			$object['formCode'] = date('YmdHis');
			$object['ExaStatus'] = "δ�ύ";
			$object['employeeOpinion'] = 2;

			$id = parent::add_d($object ,true);

			//���ΪԱ���ύ���룬���ʼ�֪ͨHR
			if ($object['status'] == '1') {
				$this->mailToHr_d($id);
			}
			$this->commit_d();
			return $id;
		 }catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * ���ʼ�֪ͨHR
	 */
	function mailToHr_d($id) {
		$object = $this->get_d($id);
		$emailDao = new model_common_mail ();
		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = $mailUser['oa_hr_transfer'];
		$mailId = $mailArr['TO_ID'];
		$mailContent = '<span style="color:blue">'.$object['preBelongDeptName'].'</span>���ŵ�<span style="color:blue">'.$object['userName'].'</span>�ύ�˵������룬���ݱ��Ϊ��<span style="color:blue">'.$object['formCode'].'</span>';
		$emailDao->mailClear("��������" ,$mailId ,$mailContent);
	}

	/**
	 * �༭��Ա������Ϣ
	 */
	 function edit_d($object){
	 	try{
			$this->start_d();
			$id = parent::edit_d($object ,true);
			$this->commit_d();
			return $id;
		 }catch(Exception $e){
			$this->rollBack();
			return $id;
		}
 	}

	/**
	 * Ա����д���
	 */
	 function opinionEdit_d($object){
	 	try{
			$this->start_d();
			$object['status']=3;
			$id=parent::edit_d($object,true);
			$this->commit_d();
			return $id;
		 }catch(Exception $e){
			$this->rollBack();
			return $id;
		}
 	}

	/**
	 *����Ա��������Ϣ
	 */
	function updatePersonInfo_d($ids){
		try{
			$this->start_d();
			$personDao = new model_hr_personnel_personnel();
			$branchDao = new  model_deptuser_branch_branch();
			if(is_array($ids)){
				$idArr = $ids;
			} else {
				$idArr = explode(',' ,$ids);
			}
			$flag = false;
			if(!empty($idArr)) {
				foreach($idArr as $key => $val) {
					$info = $this->get_d($val);
					$object['id'] = $val;
					$object['status'] = 6;
					$id = parent::edit_d($object ,true);
					$aboutinfo = $branchDao->find(array('ID'=>$info['afterUnitId']));
					if($aboutinfo['type'] == 0){
						$typeinfo = '�ӹ�˾';
					}else if($aboutinfo['type'] == 1){
						$typeinfo = '����';
					}
					$flag = $personDao->update(
						array(
							'userNo'=>$info['userNo']
							,'userAccount'=>$info['userAccount']
						),
						array(
							'companyType' => $typeinfo
							,'companyTypeCode' => $aboutinfo['type']
							,'companyName' => $info['afterUnitName']
							,'companyId' => $info['afterUnitId']
							,'deptNameS' => $info['afterDeptNameS']
							,'deptIdS' => $info['afterDeptIdS']
							,'deptCodeS' => $info['afterDeptCodeS']
							,'deptNameT' => $info['afterDeptNameT']
							,'deptIdT' => $info['afterDeptIdT']
							,'deptCodeT' => $info['afterDeptCodeT']
							,'deptNameF' => $info['afterDeptNameF']
							,'deptIdF' => $info['afterDeptIdF']
							,'deptCodeF' => $info['afterDeptCodeF']
							,'deptName' => $info['afterDeptName']
							,'deptId' => $info['afterDeptId']
							,'deptCode' => $info['afterDeptCode']
							,'jobId' => $info['afterJobId']
							,'regionName' => $info['afterUseAreaName']
							,'jobName' => $info['afterJobName']
							,'regionId' => $info['afterUseAreaId']
							,'belongDeptCode' => $info['afterBelongDeptCode']
							,'belongDeptName' => $info['afterBelongDeptName']
							,'belongDeptId' => $info['afterBelongDeptId']
							,'personnelClassName' => $info['afterPersonClass']
							,'personnelClass' => $info['afterPersonClassCode']
						)
					);

					$personnelID = $this->get_table_fields('oa_hr_personnel', "userNo='".$info['userNo']."'", 'id');
					$personDao->updateOldInfo_d($personnelID,'edit');

					//���¹̶��ʲ�������Ϣ
					$assetcardDao = new model_asset_assetcard_assetcard();
					$assetcardDao->updateDeptInfo($info['userAccount']);
				}
			}
			$this->commit_d();
			return $flag;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/******************* S ���뵼��ϵ�� ************************/
	function addExecelData_d(){
		set_time_limit(0);
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
		$deptDao = new model_deptuser_dept_dept();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");

			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
					if($key === 0){
						continue ;
					}
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])&& empty($val[5])){
						continue;
					} else {
						//��������
						$inArr = array();

						//Ա�����
						if(!empty($val[0])){
							if(!isset($userArr[$val[0]])) {
								$rs = $otherDataDao->getUserInfoByUserNo($val[0]);
								if(!empty($rs)) {
									$userConutArr[$val[0]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�Ա�����</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}

							$personDao = new model_hr_personnel_personnel();
							$personObj = $personDao->getPersonnelInfo_d($userConutArr[$val[0]]['USER_ID']);
							$inArr['entryDate'] = $personObj['entryDate'];

							$inArr['userAccount'] = $userConutArr[$val[0]]['USER_ID'];
							$inArr['deptId'] = $userConutArr[$val[0]]['DEPT_ID'];
							$inArr['deptName'] = $userConutArr[$val[0]]['DEPT_NAME'];
							$inArr['userNo'] = $val[0];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û��Ա�����</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//Ա������
						if(!empty($val[1])){
							$inArr['userName'] = $val[1];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û��Ա������</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//��������
						if(!empty($val[2])&& $val[2] != '0000-00-00'){
							$val[2] = trim($val[2]);

							if(!is_numeric($val[2])){
								$inArr['applyDate'] = $val[2];
							} else {
								$transferDate = date('Y-m-d',(mktime(0,0,0,1, $val[2] - 1 , 1900)));
								$inArr['applyDate'] = $transferDate;
							}
						}

						//�������������

						//��˾����
						if(!empty($val[4])){
							$inArr['preUnitTypeName'] = $val[4];
						}

						//��˾����
						if(!empty($val[5])){
							$inArr['preUnitName'] = $val[5];
						}

						//��������
						if(!empty($val[6])){
							if(!isset($deptArr[$val[6]])){
								$rs = $deptDao->getDeptId_d($val[6]);
								if($rs){
									$deptArr[$val[6]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ���ǰ��������</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['preDeptNameS'] = $val[6];
							$inArr['preDeptIdS'] = $deptArr[$val[6]];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û�е���ǰ��������</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//��������
						if(!empty($val[7])){
							if(!isset($deptArr[$val[7]])){
								$rs = $deptDao->getDeptId_d($val[7]);
								if($rs){
									$deptArr[$val[7]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ���ǰ��������</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['preDeptNameT'] = $val[7];
							$inArr['preDeptIdT'] = $deptArr[$val[7]];
							$inArr['preBelongDeptName'] = $val[7];
							$inArr['preBelongDeptId'] = $deptArr[$val[7]];
						} else {
							$inArr['preBelongDeptName'] = $val[6];
							$inArr['preBelongDeptId'] = $deptArr[$val[6]];
						}

						//�ļ�����
						if(!empty($val[8])){
							if(!isset($deptArr[$val[8]])){
								$rs = $deptDao->getDeptId_d($val[8]);
								if($rs){
									$deptArr[$val[8]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ���ǰ�ļ�����</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['preDeptNameF'] = $val[8];
							$inArr['preDeptIdF'] = $deptArr[$val[8]];
							$inArr['preBelongDeptName'] = $val[8];
							$inArr['preBelongDeptId'] = $deptArr[$val[8]];
						}

						//��˾����
						if(!empty($val[9])){
							$inArr['afterUnitTypeName'] = $val[9];
						}

						//��˾����
						if(!empty($val[10])){
							$inArr['afterUnitName'] = $val[10];
							if($val[10] == $val[5]) {
								$inArr['isCompanyChange'] = '0';
							} else {
								$inArr['isCompanyChange'] = '1';
							}
						}

						//��������
						if(!empty($val[11])){
							if(!isset($deptArr[$val[11]])){
								$rs = $deptDao->getDeptId_d($val[11]);
								if($rs){
									$deptArr[$val[11]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ������������</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['afterDeptNameS'] = $val[11];
							$inArr['afterDeptIdS'] = $deptArr[$val[11]];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!û�е������������</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//��������
						if(!empty($val[12])){
							if(!isset($deptArr[$val[12]])){
								$rs = $deptDao->getDeptId_d($val[12]);
								if($rs){
									$deptArr[$val[12]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ�������������</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['afterDeptNameT'] = $val[12];
							$inArr['afterDeptIdT'] = $deptArr[$val[12]];
							$inArr['afterBelongDeptName'] = $val[12];
							$inArr['afterBelongDeptId'] = $deptArr[$val[12]];
						} else {
							$inArr['afterBelongDeptName'] = $val[11];
							$inArr['afterBelongDeptId'] = $deptArr[$val[11]];
						}

						//�ļ�����
						if(!empty($val[13])){
							if(!isset($deptArr[$val[13]])){
								$rs = $deptDao->getDeptId_d($val[13]);
								if($rs){
									$deptArr[$val[13]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ������ļ�����</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['afterDeptNameF'] = $val[13];
							$inArr['afterDeptIdF'] = $deptArr[$val[13]];
							$inArr['afterBelongDeptName'] = $val[13];
							$inArr['afterBelongDeptId'] = $deptArr[$val[13]];
						}

						if($inArr['afterBelongDeptName'] == $inArr['preBelongDeptName']){
							$inArr['isDeptChange'] = '0';
						} else {
							$inArr['isDeptChange'] = '1';
						}

						//����ǰְλ
						if(!empty($val[14])){
							if(!isset($jobsArr[$val[14]])){
								$rs = $otherDataDao->getJobId_d($val[14]);
								if(1){
									$jobsArr[$val[14]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ���ǰԱ��ְλ</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['preJobName'] = $val[14];
							$inArr['preJobId'] = $jobsArr[$val[14]];
						}

						//������ְλ
						if(!empty($val[15])){
							if(!isset($jobsArr[$val[15]])){
								$rs = $otherDataDao->getJobId_d($val[15]);
								if(1){
									$jobsArr[$val[15]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ�����Ա��ְλ</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['afterJobName'] = $val[15];
							$inArr['afterJobId'] = $jobsArr[$val[15]];
							if($val[15] == $val[14]){
								$inArr['isJobChange']='0';
							} else {
								$inArr['isJobChange']='1';
							}
						}

						//����ǰ����
						if(!empty($val[16])){
							$branchDao = new model_deptuser_branch_branch();
							$rs = $otherDataDao->getAreaByName($val[16]);
							if(!empty($rs)){
								$branchId = $rs['ID'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ���ǰ����</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
							$inArr['preUseAreaName'] = $val[16];
							$inArr['preUseAreaId'] = $branchId;
						}

						//����������
						if(!empty($val[17])){
							$branchDao = new model_deptuser_branch_branch();
							$rs = $otherDataDao->getAreaByName($val[17]);
							if(!empty($rs)){
								$branchId=$rs['ID'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ���������</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
							$inArr['afterUseAreaName'] = $val[17];
							$inArr['afterUseAreaId'] = $branchId;
						}

						if($val[17] == $val[16]) {
							$inArr['isAreaChange'] = '0';
						} else {
							$inArr['isAreaChange'] = '1';
						}

						//����ǰ��Ա����
						if(!empty($val[18])) {
							$rs = $datadictDao->getCodeByName('HRRYFL' ,$val[18]);
							if(!empty($rs)) {
								$inArr['prePersonClassCode'] = $rs;
								$inArr['prePersonClass'] = $val[18];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ���ǰ��Ա����</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						//��������Ա����
						if(!empty($val[19])) {
							$rs = $datadictDao->getCodeByName('HRRYFL' ,$val[19]);
							if(!empty($rs)) {
								$inArr['afterPersonClassCode'] = $rs;
								$inArr['afterPersonClass'] = $val[19];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ�������Ա����</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						if($val[19] == $val[18]){
							$inArr['isClassChange'] = '0';
						} else {
							$inArr['isClassChange'] = '1';
						}

						//������
						if(!empty($val[20])){
							if(!isset($userArr[$val[20]])){
								$rs = $otherDataDao->getUserInfo($val[20]);
								if(!empty($rs)){
									$userArr[$val[20]] = $rs;
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵľ���������</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}

							$inArr['managerId'] = $userArr[$val[20]]['USER_ID'];
							$inArr['managerName'] = $val[20];
						}

						//����ԭ��
						if(!empty($val[22])){
							$inArr['reason'] = $val[22];
						}

						//��ע
						if(!empty($val[23])){
							$inArr['remark'] = $val[23];
						}

						$inArr['ExaStatus'] = AUDITED;
						$inArr['ExaDT'] = date('Y-m-d');
						$inArr['status'] = 6;
						$inArr['formCode'] = date('YmdHis').$inArr['userNo'];
						$inArr['applyDate'] = date('Y-m-d');
						$inArr['employeeOpinion'] = '1';

						if($inArr['isCompanyChange'] == 1)
							$inArr['transferTypeName'].="��˾�䶯  ";
						if($inArr['isAreaChange'] == 1)
							$inArr['transferTypeName'].="����䶯  ";
						if($inArr['isDeptChange'] == 1)
							$inArr['transferTypeName'].="���ű䶯 ";
						if($inArr['isJobChange'] == 1)
							$inArr['transferTypeName'].="ְλ�䶯 ";
						if($inArr['isClassChange'] == 1)
							$inArr['transferTypeName'].="��Ա����䶯 ";

						$newId = parent::add_d($inArr ,true);
						if($newId){
							$tempArr['result'] = '����ɹ�';
						} else {
							$tempArr['result'] = '<span class="red">����ʧ��</span>';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push($resultArr ,$tempArr);
					}
				}
				return $resultArr;
			}
		}
	}


	//����excel
	function export($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//����һ��Excel������
		//		$objPhpExcelFile = new PHPExcel();


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/����-��Ա��������ģ��.xls" ); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '��Ϣ�б�' ) );
		//���ñ�ͷ����ʽ ����
		$i = 3;
		if (! count ( array_filter ( $rowdatas ) ) == 0) {
			$row = $i;
			for($n = 0; $n < count ( $rowdatas ); $n ++) {
				$m = 0;
				foreach ( $rowdatas [$n] as $field => $value ) {
					$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m ++;
				}
				$i ++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'T' . $i );
			for($m = 0; $m < 10; $m ++) {
				$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
		}

		//�������
		ob_end_clean (); //��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "��Ա��������.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
	/******************* E ���뵼��ϵ�� ************************/
}
?>