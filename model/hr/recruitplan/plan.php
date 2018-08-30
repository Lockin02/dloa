<?php
/**
 * @author Administrator
 * @Date 2012��10��16�� ���ڶ� 9:21:33
 * @version 1.0
 * @description:��Ƹ�ƻ� Model��
 */
class model_hr_recruitplan_plan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitplan_plan";
		$this->sql_map = "hr/recruitplan/planSql.php";
		$this->statusDao = new model_common_status ();
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
				),
				6 => array (
				'statusEName' => 'suspend',
				'statusCName' => '����',
				'key' => '6'
				),
				7 => array (
				'statusEName' => 'cancel',
				'statusCName' => 'ȡ��',
				'key' => '7'
				),
				9 => array (
				'statusEName' => 'inactive ',
				'statusCName' => 'δ����',
				'key' => '9'
				)
				);
				parent::__construct ();
	}

	/**
	 * �����Ƹ�ƻ���Ϣ
	 */
	function add_d($object){
		try{
			$this->start_d();
			$object['formCode']='ZPJH'.date ( "YmdHis" );//���ݱ��
			$dictDao = new model_system_datadict_datadict();
			$object['state'] = 0;
			$object['addType'] = $dictDao->getDataNameByCode($object['addTypeCode']);
			$object['employmentType'] = $dictDao->getDataNameByCode($object['employmentTypeCode']);
			$object['maritalStatusName'] = $dictDao->getDataNameByCode($object['maritalStatus']);
			//$object['educationName'] = $dictDao->getDataNameByCode($object['education']);
			$object['postTypeName'] = $dictDao->getDataNameByCode($object['postType']);
			$object['ExaStatus']='δ�ύ';
			$object['entryNum']=0;//��ְ����
			$object['beEntryNum']=$object['needNum'];//����ְ����
			if($object['useAreaId']>0){
				$object ['useAreaName']=$this->get_table_fields('area', "ID='".$object['useAreaId']."'", 'Name');
			}
			$id=parent::add_d($object,true);

			//���¸���������ϵ
			$this->updateObjWithFile ( $id);

			//��������
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			}
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * �༭��Ƹ�ƻ�
	 */
	function edit_d($object){
		try{
			$this->start_d();
			//add chenrf 20130510
			$needNum=$object['needNum'];//��������
			$entryNum=$object['entryNum'];//����ְ����
			$beEntryNum=$needNum-$entryNum;
			$object['beEntryNum']=$beEntryNum;  //����ְ����

			$dictDao = new model_system_datadict_datadict();
			$object['addType'] = $dictDao->getDataNameByCode($object['addTypeCode']);
			$object['employmentType'] = $dictDao->getDataNameByCode($object['employmentTypeCode']);
			$object['maritalStatusName'] = $dictDao->getDataNameByCode($object['maritalStatus']);
			$object['educationName'] = $dictDao->getDataNameByCode($object['education']);
			$object['postTypeName'] = $dictDao->getDataNameByCode($object['postType']);
			$id=parent::edit_d($object,true);
			//���¸���������ϵ
			$this->updateObjWithFile($object['id']);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * ��Ƹ�ƻ�excel����
	 */
	function addExecelData_d($actionType){
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
		$userArr = array();//�û�����
		$deptArr = array();//��������
		$jobsArr = array();//ְλ����
		$areArr	= array(); //�������ţ���������
		$positionLevel=array(); //���ż�������
		$levelArr=array('����','�м�','�߼�');//�����ŵļ�������
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		$eperson=new model_engineering_baseinfo_eperson();  //��������ְλ�ļ���
		$epersonData=$eperson->findAll('orderNum!=0',null,'personLevel');
		foreach ($epersonData as $per){
			array_push($positionLevel, $per['personLevel']);
		}
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			//			echo "<pre>";
			if(is_array($excelData)){
			try{
				$this->start_d();
				//������ѭ��
				foreach($excelData as $key => $val){
					$val=array_map(array(__CLASS__,'addslashes'),$val);  //�����������
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[14]) ){
						continue;
					}else{
						//��������
						$inArr = array();

						//ְλ����
						if(!empty($val[0])&&trim($val[0])!=''){
							$val[0] = trim($val[0]);
							if(!isset($datadictArr[$val[0]])){
								$rs = $datadictDao->getCodeByName('YPZW',trim($val[0]));
								if(!empty($rs)){
									$trainsType = $datadictArr[$val[0]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�ְλ����</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$trainsType = $datadictArr[$val[0]]['code'];
							}
							$inArr['postType'] = $trainsType;
							$inArr['postTypeName'] = $val[0];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!ְλ����Ϊ��</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//����
						if(!empty($val[1])&&trim($val[1])!=''){
							if(!isset($deptArr[$val[1]])){
								$rs = $otherDataDao->getDeptId_d(trim($val[1]));
								if(!empty($rs)){
									$deptArr[$val[1]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĲ���</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['deptName'] = $val[1];
							$inArr['deptId'] = $deptArr[$val[1]];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!������Ϊ��</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��������
						if(!empty($val[2])&&trim($val[2])!=''){
							$are=trim($val[2]);
							if(!in_array($are, $areArr)){
								$re=$this->get_table_fields('area', "Name='".$are."'", 'ID');
								if(!empty($re))
									$areArr[$are]=$re;
								else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĹ�������</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['useAreaName']=$are;
							$inArr['useAreaId']=$areArr[$are];
						}

						//�����ص�
						if(!empty($val[3])&&trim($val[3])!=''){
							$inArr['workPlace'] = trim($val[3]);
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�蹤���ص�Ϊ��</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//ְλ
						if(!empty($val[4])&&trim($val[4])!=''){
							$val[4] = trim($val[4]);
							if(!isset($jobsArr[$val[4]])){
								$rs = $otherDataDao->getJobId_d($val[4]);
								if(!empty($rs)){
									$jobsArr[$val[4]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�ְλ</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['positionName'] = $val[4];
							$inArr['positionId'] = $jobsArr[$val[4]];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!ְλ����Ϊ��</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//����
						if(!empty($val[5])&&trim($val[5])!=''){
							$levelStr=trim($val[5]);
							$level=explode(',',$levelStr);
							$level=array_filter($level);
							$errorLe='';
							if('����'==$actionType){   //���ְλ����Ϊ����
								foreach ($level as $le)
									if(!in_array($le,$positionLevel)){
										$errorLe=$le;
										break;
									}
							}else{
								foreach ($level as $le)
									if(!in_array($le, $levelArr)){
										$errorLe=$le;
									}

							}

							if($errorLe!=''){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵļ���('.$errorLe.')</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
							else{
								$inArr['positionLevel'] = $levelStr;
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!������Ϊ��</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}



						//�Ƿ����
						if(!empty($val[6])&&trim($val[6])!=''){
							$val[6] = trim($val[6]);
							if($val[6]=='��'){
								$inArr['isEmergency'] = 1;
							}else if($val[6]=='��'){
								$inArr['isEmergency'] = 0;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�Ƿ���������ǻ��</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}

						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�Ƿ����Ϊ��</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//������Ŀ��
						if(!empty($val[7])&&trim($val[7])!=''){
							$inArr['projectGroup'] = $val[7];
						}



						//��Ƹ��������
						if(!empty($val[8])&&trim($val[8])!=''){
							$val[8] = trim($val[8]);
							if(!isset($datadictArr[$val[8]])){
								$rs = $datadictDao->getCodeByName('HRZYLX',trim($val[8]));
								if(!empty($rs)){
									$trainsType = $datadictArr[$val[8]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���������</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$trainsType = $datadictArr[$val[8]]['code'];
							}
							$inArr['addTypeCode'] = $trainsType;
							$inArr['addType'] = $val[8];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!��Ƹ�������Ͳ���Ϊ��</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//���Žӿ���
						if(!empty($val[9])&&trim($val[9])!=''){
							$val[9] = trim($val[9]);
							if(!isset($userArr[$val[9]])){
								$rs = $otherDataDao->getUserInfo(trim($val[9]));
								if(!empty($rs)){
									$userArr[$val[9]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�����(�����Žӿ���)</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['resumeToName'] = trim($val[9]);
							$inArr['resumeToId'] = $userArr[$val[9]]['USER_ID'];
							$inArr['formManName'] = trim($val[9]);
							$inArr['formManId'] = $userArr[$val[9]]['USER_ID'];
						}

						//������
						if(!empty($val[10])&&trim($val[10])!=''){
							$val[10] = trim($val[10]);
							if(!isset($userArr[$val[10]])){
								$rs = $otherDataDao->getUserInfo(trim($val[10]));
								if(!empty($rs)){
									$userArr[$val[10]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�����</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['recruitManName'] = trim($val[10]);
							$inArr['recruitManId'] = $userArr[$val[10]]['USER_ID'];
						}

						//Э����
						if(!empty($val[11])&&trim($val[11])!=''){
							$val[11] = trim($val[11]);
							if(!isset($userArr[$val[11]])){
								$rs = $otherDataDao->getUserInfo(trim($val[11]));
								if(!empty($rs)){
									$userArr[$val[11]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Э����</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['assistManName'] = trim($val[11]);
							$inArr['assistManId'] = $userArr[$val[11]]['USER_ID'];
						}

						//״̬
						if(!empty($val[12])&&trim($val[12])!=''){
							$inArr['state'] = $this->statusDao->statusCtoK(trim($val[12])) ;
						}

						//�����������
						if(!empty($val[13])&&trim($val[13])!=''){
							$val[13] = trim($val[13]);
							if (!is_numeric($val[13])) {
								$inArr['formDate'] = $val[13];
							} else {
								$beginDate = date('Y-m-d', (mktime(0, 0, 0, 1, $val[13] - 1, 1900)));
								if($beginDate=='1970-01-01'){
									$quitDate = date('Y-m-d',strtotime ($val[13]));
									$inArr['formDate'] = $quitDate;
								}else{
									$inArr['formDate'] = $beginDate;
								}
							}
						}

						//����ģ�� ����
						if('����'==$actionType){
							if($actionType!=($val[0])){
								throw new Exception;
							}
							//ϣ������ʱ��
							if(!empty($val[14])&&trim($val[14])!=''){
								$hopeDate=$var[14];
								if(is_numeric($val[14])){
									$beginDate = date('Y-m-d', (mktime(0, 0, 0, 1, $val[14] - 1, 1900)));
									if($beginDate=='1970-01-01'){
										$quitDate = date('Y-m-d',strtotime ($val[14]));
										$inArr['hopeDate'] = $quitDate;
									}else{
										$inArr['hopeDate'] = $beginDate;
									}
								}else {
										$tempArr['docCode'] = '��' . $actNum .'������';
										$tempArr['result'] = '<span class="red">����ʧ��!ϣ������ʱ�����ʹ���</span>';
										array_push( $resultArr,$tempArr );
										continue;
								}


							}
						//��������
							if(!empty($val[15])&&trim($val[15])!=''){
								//����ְ������
								if(!empty($val[16])&&trim($val[16])!=''){
									if(!is_numeric($val[15])){
										$tempArr['docCode'] = '��' . $actNum .'������';
										$tempArr['result'] = '<span class="red">����ʧ��!����ְ���������ʹ���</span>';
										array_push( $resultArr,$tempArr );
										continue;
									}
									$inArr['entryNum'] = $val[16];
								}
								if(!is_numeric($val[15])){
										$tempArr['docCode'] = '��' . $actNum .'������';
										$tempArr['result'] = '<span class="red">����ʧ��!�����������ʹ���</span>';
										array_push( $resultArr,$tempArr );
										continue;
									}
								if(trim($val[16])>trim($val[15])){
										$tempArr['docCode'] = '��' . $actNum .'������';
										$tempArr['result'] = '<span class="red">����ʧ��!�������������������ְ����</span>';
										array_push( $resultArr,$tempArr );
										continue;
								}
									$inArr['needNum'] = $val[15];
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!��������Ϊ��</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}



							//����ְ������
							if(!empty($val[17])&&trim($val[17])!=''){
								$inArr['beEntryNum'] = $val[17];
							}

							//����Ƹ������
							if(!empty($val[18])&&trim($val[18])!=''){
							}

							//����offer����
							if(!empty($val[20])&&trim($val[20])!=''){
							}

							//��Ƹԭ��/����
							if(!empty($val[21])&&trim($val[21])!=''){
								$inArr['applyReason'] = $val[21];
							}

							//��λְ����ְҪ��
							if(!empty($val[22])&&trim($val[22])!=''){
								$inArr['workDuty'] = $val[22];
							}
							//����
							if(!empty($val[23])&&trim($val[23])!=''){
								$inArr['network'] = $val[23];
							}
							//�豸
							if(!empty($val[24])&&trim($val[24])!=''){
								$inArr['device'] = $val[24];
							}
						}else{                        //������ģ��
							if('����'==($val[0])){
								throw new Exception;
							}
							//��������
							if(!empty($val[14])&&trim($val[14])!=''){
								$inArr['needNum'] = $val[14];
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!��������Ϊ��</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}

							//����ְ������
							if(!empty($val[15])&&trim($val[15])!=''){
								$inArr['entryNum'] = $val[15];
							}

							//����ְ������
							if(!empty($val[16])&&trim($val[16])!=''){
								$inArr['beEntryNum'] = $val[16];
							}

							//����Ƹ������
							if(!empty($val[17])&&trim($val[17])!=''){
							}

							//����offer����
							if(!empty($val[19])&&trim($val[19])!=''){
							}

							//��Ƹԭ��/����
							if(!empty($val[20])&&trim($val[20])!=''){
								$inArr['applyReason'] = $val[20];
							}

							//��λְ����ְҪ��
							if(!empty($val[21])&&trim($val[21])!=''){
								$inArr['workDuty'] = $val[21];
							}
							//����
							if(!empty($val[22])&&trim($val[22])!=''){
								$inArr['network'] = $val[22];
							}
							//�豸
							if(!empty($val[23])&&trim($val[23])!=''){
								$inArr['device'] = $val[23];
							}
						}





						//$inArr['formCode']='ZP'.str_replace('-', '', $inArr['formDate']).uniqid();//���ݱ��
						$inArr['formCode']=uniqid('ZPJH-');//���ݱ��
						$inArr['ExaStatus']='���';//����״̬
						if($inArr['state']==''){
							$inArr['state'] = $this->statusDao->statusEtoK ( 'nocheck' );
						}
						$newId = parent::add_d($inArr,true);

						if($newId){
							$tempArr['result'] = '����ɹ�';
						}else{
							$tempArr['result'] = '<span class="red">����ʧ��</span>';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push( $resultArr,$tempArr );
					}
				}
				$this->commit_d();
			}catch (Exception $e){
				$this->rollBack();
				$tempArr['docCode'] = 'error';
				$tempArr['result'] = '<span class="red">ģ����󣬵���ʧ�ܣ������ļ����Ƿ�����š�����������</span>';
				$resultArr=array();
				array_push( $resultArr,$tempArr );
			}
				return $resultArr;
			}
		}
	}
	/**
	 *
	 * �ı�״̬
	 * @param $id
	 * @param $state
	 */
	function changeState($id,$state=2){
		$object['id']=$id;
		$object['state']=$state;
		return $this->updateById($object);
	}
	/**
	 * �Ե���excel���ֶ�������
	 * @param unknown_type $val
	 */
	function addslashes($val){

	    if(!get_magic_quotes_gpc()) {
	        $val = addslashes($val);
	    }
	    $val=trim($val);
	    $val = str_replace("_", "\_", $val);
	    $val = str_replace("%", "\%", $val);
	    $val = nl2br($val);
	    $val = htmlspecialchars($val);

	    return $val;

	}


}
?>