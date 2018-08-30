<?php
/**
 * @author Administrator
 * @Date 2012��5��31�� 17:03:17
 * @version 1.0
 * @description:������Ϣ Model��
 */
 class model_hr_personnel_attendance  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_attendance";
		$this->sql_map = "hr/personnel/attendanceSql.php";
		parent::__construct ();
		$this->status=array(
			'0'=>'�ݸ�',
			'1'=>'��ʼ��δ����',
			'2'=>'������',
			'3'=>'�����',
			'4'=>'�˻�',
			'5'=>'����'
		);
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
					$codeRuleDao = new model_common_codeRule();
					$userDao = new model_deptuser_user_user();
					$deptDao = new model_deptuser_dept_dept();
					foreach ( $excelData as $rNum => $row ) {
						foreach ( $objKeyArr as $index => $fieldName ) {
							//��ֵ������Ӧ���ֶ�
							$objectArr [$rNum] [$fieldName] = $row [$index];
						}
					}
					$HRdatadict = $this->getDatadicts('HRQJLX');
					foreach ($objectArr as $key=>$val){
						//��ʽ�����룬ɾ������Ŀո��������Ϊ�գ���������ݲ�����Ч��
						$objectArr[$key]['userNo'] = str_replace( ' ','',$val['userNo']);
						$objectArr[$key]['userName'] = str_replace( ' ','',$val['userName']);
						if( empty($val['userNo']) && empty($val['userName']) && empty($val['days'])
							&& empty($val['beginDate']) && empty($val['endDate']) && empty($val['docStatusName'])){
							unset($objectArr[$key]);
							continue;
						}else if( empty($val['userNo']) || empty($val['userName']) || empty($val['days'])
							|| empty($val['beginDate']) || empty($val['endDate'])|| empty($val['docStatusName'])){
							$errorCodeArr[$key]['docCode']=$key+2;
							$errorCodeArr[$key]['result']='������Ϊ�գ�����ʧ��';
							unset($objectArr[$key]);
							continue;
						}else{
							$userId = $codeRuleDao->getUserIdByCard($val['userNo']);
							$inputId = $codeRuleDao->getUserIdByCard($val['inputNo']);
							if( empty($userId) ){
								$errorCodeArr[$key]['docCode']=$key+2;
								$errorCodeArr[$key]['result']='Ա��������󣬵���ʧ��';
								unset($objectArr[$key]);
								continue;
							}else{
								$userInfo = $userDao->getUserById($userId);
								$userName = $userInfo['USER_NAME'];
								if( $userName!=$val['userName'] ){
									$errorCodeArr[$key]['docCode']=$key+2;
									$errorCodeArr[$key]['result']='Ա�����������Ʋ���Ӧ������ʧ��';
									unset($objectArr[$key]);
									continue;
								}else{
									$objectArr[$key]['userAccount']=$userId;
									$objectArr[$key]['companyName']=$userInfo['Company'];
								}
							}
							if( !empty($val['deptNameS']) ){
								$deptIdSArr = $deptDao->getDeptId_d($val['deptNameS']);
								if($deptIdSArr['DEPT_ID']){
									$objectArr[$key]['deptIdS']=$deptIdSArr['DEPT_ID'];
								}else{
									$errorCodeArr[$key]['docCode']=$key+2;
									$errorCodeArr[$key]['result']='�������Ų����ڣ�����ʧ��';
									unset($objectArr[$key]);
									continue;
								}
							}
							if( !empty($val['deptNameT']) ){
								$deptIdTArr = $deptDao->getDeptId_d($val['deptNameT']);
								if($deptIdTArr['DEPT_ID']){
									$objectArr[$key]['deptIdT']=$deptIdTArr['DEPT_ID'];
								}else{
									$errorCodeArr[$key]['docCode']=$key+2;
									$errorCodeArr[$key]['result']='�������Ų����ڣ�����ʧ��';
									unset($objectArr[$key]);
									continue;
								}
							}
							if( empty($inputId) ){
								$errorCodeArr[$key]['docCode']=$key+2;
								$errorCodeArr[$key]['result']='�Ƶ��˱�����󣬵���ʧ��';
								unset($objectArr[$key]);
								continue;
							}else{
								$inputInfo = $userDao->getUserById($inputId);
								$inputName = $inputInfo['USER_NAME'];
								if( $inputName!=$val['inputName'] ){
									$errorCodeArr[$key]['docCode']=$key+2;
									$errorCodeArr[$key]['result']='�Ƶ��˱��������Ʋ���Ӧ������ʧ��';
									unset($objectArr[$key]);
									continue;
								}else{
									$objectArr[$key]['inputId']=$inputId;
								}
							}
							foreach ( $this->status as $k=>$v ){
								if($val['docStatusName']==$v){
									$objectArr[$key]['docStatus']=$key;
								}
							}
							foreach ( $HRdatadict as $index=>$row ){
								if($row['dataName']==$val['typeName']){
									$objectArr[$key]['typeCode']=$row['dataCode'];
								}
							}
							//�̵�����
							$beginDate = mktime(0, 0, 0, 1, $objectArr[$key]['beginDate'] - 1, 1900);
							$objectArr[$key]['beginDate'] = date("Y-m-d", $beginDate);
							//��ְ����
							$endDate = mktime(0, 0, 0, 1, $objectArr[$key]['endDate'] - 1, 1900);
							$objectArr[$key]['endDate'] = date("Y-m-d", $endDate);
						}
					}
					if( count($errorCodeArr)>0 ){
				 		$returnFlag = false;
						$title = '������Ϣ������';
						$thead = array( '�к�','���' );
						echo "<script>alert('����ʧ��')</script>";
						echo util_excelUtil::showResult($errorCodeArr,$title,$thead);
					}else{
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



 }
?>