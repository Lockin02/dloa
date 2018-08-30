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
 * @Date 2012-05-30 19:26:14
 * @version 1.0
 * @description:��ͬ��Ϣ Model��
 */
class model_hr_contract_contract extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_contract";
		$this->sql_map = "hr/contract/contractSql.php";
		parent :: __construct();
	}

	/**
	 * ��д�༭����
	 */
	function edit_d($object){
		try{
			$this->start_d();

			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object['conTypeName'] = $datadictDao->getDataNameByCode ( $object['conType'] );
			$object['conStateName'] = $datadictDao->getDataNameByCode ( $object['conState'] );
			$object['conNumName'] = $datadictDao->getDataNameByCode ( $object['conNum'] );

			//�޸�������Ϣ
			parent::edit_d($object ,true);
			//���¸���������ϵ
			$this->updateObjWithFile($object['id'] ,$object['conNo']);

			//��������
			if(isset($_POST['fileuploadIds']) && is_array($_POST['fileuploadIds'])){
				$uploadFile = new model_file_uploadfile_management();
				$uploadFile->updateFileAndObj($_POST['fileuploadIds'],$object['id'],$object['conNo']);
			}
			if($object['entryId'] > 0) {
				$entryNoticeDao = new model_hr_recruitment_entryNotice();
				$entryNoticeDao->updateField("id=".$object['entryId'],'contractState',1);
			}

			//���¾�ϵͳ�ĵ�����ͬ��Ϣ
			$userNo = $this->get_table_fields('hrms', "UserCard='".$object['userNo']."'", 'UserCard');
			$sql = '';
			if($userNo&&$object['conStateName']=='��Ч'&&($object['conTypeName']=='�Ͷ���ͬ'||$object['conTypeName']=='ʵϰЭ��'||$object['conTypeName']=='Ƹ��Э��')){
				switch($object['conNumName']){
					case '��һ���빫˾ǩ':$ContractState='1';break;
					case '�����ڶ����빫˾ǩ':$ContractState='3';break;
					case '�����������빫˾ǩ':$ContractState='10';break;
					case '��һ�������ɹ�˾ǩ':$ContractState='2';break;
					case '�����ڶ��������ɹ�˾ǩ':$ContractState='4';break;
					case '�빫˾ǩ�޹̶�����':$ContractState='5';break;
					case '��㶫���ɹ�˾ǩ�޹̶�����':$ContractState='6';break;
					case 'Ƹ��Э��':$ContractState='7';break;
					case 'ʵϰЭ��':$ContractState='8';break;
					case '����':$ContractState='9';break;
					default :$ContractState='';break;
				}
				$sql = "update  hrms set ContractState='".$ContractState."' , ContFlagB='".$object["beginDate"]."',ContFlagE='".$object["closeDate"]."'
					where usercard='".$object["userNo"]."'";
				$this->query($sql);
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��д��������
	 */
	function add_d($object){
		try{
			$this->start_d();

			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object['conTypeName'] = $datadictDao->getDataNameByCode ( $object['conType'] );
			$object['conStateName'] = $datadictDao->getDataNameByCode ( $object['conState'] );
			$object['conNumName'] = $datadictDao->getDataNameByCode ( $object['conNum'] );

			//�޸�������Ϣ
			$id = parent::add_d($object ,true);

			//���¸���������ϵ
			$this->updateObjWithFile($id,$object['conNo']);

			//��������
			if(isset($_POST['fileuploadIds']) && is_array($_POST['fileuploadIds'])){
				$uploadFile = new model_file_uploadfile_management();
				$uploadFile->updateFileAndObj($_POST['fileuploadIds'],$id,$object['conNo']);
			}
			if($object['entryId'] > 0){
				$entryNoticeDao=new model_hr_recruitment_entryNotice();
				$entryNoticeDao->updateField("id=".$object['entryId'],'contractState',1);
			}

			//���¾�ϵͳ�ĵ�����ͬ��Ϣ
			$userNo=$this->get_table_fields('hrms', "UserCard='".$object['userNo']."'", 'UserCard');
			$sql = '';
			if($userNo && $object['conStateName'] == '��Ч'
					&& ($object['conTypeName'] == '�Ͷ���ͬ' || $object['conTypeName'] == 'ʵϰЭ��' || $object['conTypeName'] == 'Ƹ��Э��')) {
				switch($object['conNumName']){
					case '��һ���빫˾ǩ':$ContractState='1';break;
					case '�����ڶ����빫˾ǩ':$ContractState='3';break;
					case '�����������빫˾ǩ':$ContractState='10';break;
					case '��һ�������ɹ�˾ǩ':$ContractState='2';break;
					case '�����ڶ��������ɹ�˾ǩ':$ContractState='4';break;
					case '�빫˾ǩ�޹̶�����':$ContractState='5';break;
					case '��㶫���ɹ�˾ǩ�޹̶�����':$ContractState='6';break;
					case 'Ƹ��Э��':$ContractState='7';break;
					case 'ʵϰЭ��':$ContractState='8';break;
					case '����':$ContractState='9';break;
					default :$ContractState='';break;
				}
				$sql = "update  hrms set ContractState='".$ContractState."' , ContFlagB='".$object["beginDate"]."',ContFlagE='".$object["closeDate"]."'
					where usercard='".$object["userNo"]."'";
				$this->query($sql);
			}

			$this->commit_d();
			return $id;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �����û��˺Ż�ȡ��Ϣ
	 */
	function getInfoByUserNo_d($userNoArr){
		$this->searchArr = array ('userNoArr' => $userNoArr );
		$this->__SET('sort', 'c.userNo');
		$rows= $this->listBySqlId ( "select_default" );
		return $rows;
	}

	/******************* S ���뵼��ϵ�� ************************/
	function addExecelData_d($objNameArr){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){
				$objectArr = array ();
				$resultArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					foreach ( $objNameArr as $index => $fieldName ) {
						$objectArr [$rNum] [$fieldName] = $row [$index];
					}
				}
				//ѭ�����������
				foreach($objectArr as $key => $val){
					if(empty($val['userNo']) && empty($val['userName'])){
						unset($objectArr[$key]);
					}
				}
				$actNum = 3;
				//ѭ������
				foreach($objectArr as $key => $val){
					//������������
					$tempArr = $this->disposeData($val,$actNum);
					array_push( $resultArr,$tempArr );
					$actNum += 1;
				}
				return $resultArr;
			}
		}
	}

	//������������
	function disposeData($row,$actNum){
		$addArr=array();
		//��ȡ�˺���Ϣ
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$rs = $otherDataDao->getUserInfoByUserNo($row['userNo']);
		if(empty($rs)){
			$tempArr['docCode'] = '��'. $actNum .'������';
			$tempArr['result'] = '����ʧ��!�����ڵ�Ա�����';
			return $tempArr;
		} else {
			$row['userAccount'] = $rs['USER_ID'];
			$row['jobName'] = $rs['jobName'];
			$row['jobId'] = $rs['jobId'];
			//�����ֵ䴦��
			$datadictDao = new model_system_datadict_datadict();
			$conType = $datadictDao->getCodeByName('HRHTLX',$row['conTypeName']);
			$conState = $datadictDao->getCodeByName('HRHTZT',$row['conStateName']);
			$conNum = $datadictDao->getCodeByName('HRHTCS',$row['conNumName']);
			if(empty($conType)){
				$tempArr['docCode'] = '��'. $actNum .'������';
				$tempArr['result'] = '����ʧ��!�����ڵĺ�ͬ���ͣ�';
				return $tempArr;
			}else{
				if(empty($conState)){
					$tempArr['docCode'] = '��'. $actNum .'������';
					$tempArr['result'] = '����ʧ��!�����ڵĺ�ͬ״̬��';
					return $tempArr;
				}else{
					if(empty($conNum)){
						$tempArr['docCode'] = '��'. $actNum .'������';
						$tempArr['result'] = '����ʧ��!�����ڵĺ�ͬ������';
						return $tempArr;
					}else{
						$row['conType'] = $conType;
						$row['conState'] = $conState;
						$row['conNum'] = $conNum;
						$row['recorderName'] = $_SESSION['USERNAME'];
						$row['recorderId'] = $_SESSION['USER_ID'];
						$row['recordDate'] = date("Y-m-d");
						//����ʱ��
						$row["beginDate"] = date('Y-m-d',strtotime (trim($row["beginDate"])));
						$row["closeDate"] = date('Y-m-d',strtotime (trim($row["closeDate"])));
						$row["trialBeginDate"] = date('Y-m-d',strtotime (trim($row["trialBeginDate"])));
						$row["trialEndDate"] = date('Y-m-d',strtotime (trim($row["trialEndDate"])));;
						$newId = $this->add_d($row ,true);
						if($newId){
							$tempArr['result'] = '�����ɹ�';
						}else{
							$tempArr['result'] = '����ʧ��';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						return $tempArr;
					}
				}
			}
		}
	}

	/**
	 * �������
	 */
	function upDateExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear($filename ,$temp_name ,3);
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {
				//�ȶ��������ת��
				$transData = array();
				foreach ($excelData as $key => $val) {
					$transData[$key]['userNo'] = $val[0];
					$transData[$key]['userName'] = $val[1];
					$transData[$key]['conNo'] = $val[2];
					$transData[$key]['conName'] = $val[3];
					$transData[$key]['conTypeName'] = $val[4];
					$transData[$key]['conStateName'] = $val[5];
					$transData[$key]['beginDate'] = $val[6];
					$transData[$key]['closeDate'] = $val[7];
					$transData[$key]['trialBeginDate'] = $val[8];
					$transData[$key]['trialEndDate'] = $val[9];
					$transData[$key]['conNumName'] = $val[10];
					$transData[$key]['conContent'] = $val[11];
				}

				//������ѭ��
				foreach($transData as $key => $val){
					$actNum = $key + 1;
					if(empty($val['userNo'])) {
						continue;
					} else {
						//��������
						$inArr = array();

						//Ա�����
						if(!empty($val['userNo']) && trim($val['userNo']) != '') {
							$inArr['userNo'] = trim($val['userNo']);
							$rs = $otherDataDao->getUserInfoByUserNo($inArr['userNo']);
							if (empty($rs)) {
								$tempArr['docCode'] = '��'. $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!Ա����Ų�����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��'. $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!Ա�����Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//Ա������
						if(!empty($val['userName']) && trim($val['userName']) != '') {
							//�ݲ�����
						}

						//��ͬ���
						if(!empty($val['conNo']) && trim($val['conNo']) != '') {
							$inArr['conNo'] = trim($val['conNo']);
						}

						//��ͬ����
						if(!empty($val['conName']) && trim($val['conName']) != '') {
							$inArr['conName'] = trim($val['conName']);
						}

						//��ͬ����
						if(!empty($val['conTypeName']) && trim($val['conTypeName']) != '') {
							$inArr['conTypeName'] = trim($val['conTypeName']);
							$conType = $datadictDao->getCodeByName('HRHTLX' ,$inArr['conTypeName']);
							if(empty($conType)) {
								$tempArr['docCode'] = '��'. $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĺ�ͬ����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							} else {
								$inArr['conType'] = $conType;
							}
						} else {
							$tempArr['docCode'] = '��'. $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��ͬ����Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//��ͬ״̬
						if(!empty($val['conStateName']) && trim($val['conStateName']) != '') {
							$inArr['conStateName'] = trim($val['conStateName']);
							$conState = $datadictDao->getCodeByName('HRHTZT' ,$inArr['conStateName']);
							if(empty($conState)) {
								$tempArr['docCode'] = '��'. $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĺ�ͬ״̬</font>';
								array_push($resultArr ,$tempArr);
								continue;
							} else {
								$inArr['conState'] = $conState;
							}
						} else {
							$tempArr['docCode'] = '��'. $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��ͬ״̬Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//��ͬ��ʼʱ��
						if(!empty($val['beginDate']) && trim($val['beginDate']) != '') {
							$val['beginDate'] = trim($val['beginDate']);
							if(!is_numeric($val['beginDate'])) {
								$inArr['beginDate'] = $val['beginDate'];
							} else {
								$beginDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['beginDate'] - 1 ,1900)));
								if($beginDate == '1970-01-01') {
									$tmpDate = date('Y-m-d' ,strtotime($val['beginDate']));
									$inArr['beginDate'] = $tmpDate;
								} else {
									$inArr['beginDate'] = $beginDate;
								}
							}
						}

						//��ͬ����ʱ��
						if(!empty($val['closeDate']) && trim($val['closeDate']) != '') {
							$val['closeDate'] = trim($val['closeDate']);
							if(!is_numeric($val['closeDate'])) {
								$inArr['closeDate'] = $val['closeDate'];
							} else {
								$closeDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['closeDate'] - 1 ,1900)));
								if($closeDate == '1970-01-01') {
									$tmpDate = date('Y-m-d' ,strtotime($val['closeDate']));
									$inArr['closeDate'] = $tmpDate;
								} else {
									$inArr['closeDate'] = $closeDate;
								}
							}
						}

						//��ͬ��ʼʱ��ͽ���ʱ�䲻��ͬʱΪ��
						if (empty($inArr['beginDate']) && empty($inArr['closeDate'])) {
							$tempArr['docCode'] = '��'. $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��ͬ��ʼʱ��ͽ���ʱ�䲻��ͬʱΪ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//���ÿ�ʼʱ��
						if(!empty($val['trialBeginDate']) && $val['trialBeginDate'] != '0000-00-00' && trim($val['trialBeginDate']) != '') {
							$val['trialBeginDate'] = trim($val['trialBeginDate']);
							if(!is_numeric($val['trialBeginDate'])) {
								$inArr['trialBeginDate'] = $val['trialBeginDate'];
							} else {
								$trialBeginDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['trialBeginDate'] - 1 ,1900)));
								if($trialBeginDate == '1970-01-01') {
									$tmpDate = date('Y-m-d' ,strtotime($val['trialBeginDate']));
									$inArr['trialBeginDate'] = $tmpDate;
								} else {
									$inArr['trialBeginDate'] = $trialBeginDate;
								}
							}
						}

						//���ý���ʱ��
						if(!empty($val['trialEndDate']) && $val['trialEndDate'] != '0000-00-00' && trim($val['trialEndDate']) != '') {
							$val['trialEndDate'] = trim($val['trialEndDate']);
							if(!is_numeric($val['trialEndDate'])) {
								$inArr['trialEndDate'] = $val['trialEndDate'];
							} else {
								$trialEndDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['trialEndDate'] - 1 ,1900)));
								if($trialEndDate == '1970-01-01') {
									$tmpDate = date('Y-m-d' ,strtotime($val['trialEndDate']));
									$inArr['trialEndDate'] = $tmpDate;
								} else {
									$inArr['trialEndDate'] = $trialEndDate;
								}
							}
						}

						//��ͬ����
						if(!empty($val['conNumName']) && trim($val['conNumName']) != '') {
							$inArr['conNumName'] = trim($val['conNumName']);
							$conNum = $datadictDao->getCodeByName('HRHTCS' ,$inArr['conNumName']);
							if(empty($conNum)) {
								$tempArr['docCode'] = '��'. $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĺ�ͬ����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							} else {
								$inArr['conNum'] = $conNum;
							}
						}

						//��ͬ����
						if(!empty($val['conContent']) && trim($val['conContent']) != '') {
							$inArr['conContent'] = trim($val['conContent']);
						}

						//���Һ�ͬ
						$condition = array(
							'userNo' => $inArr['userNo']
							,'conType' => $inArr['conType']
						);
						//��ͬ����
						if (!empty($inArr['beginDate'])) {
							$condition['beginDate'] = $inArr['beginDate'];
						}
						if (!empty($inArr['closeDate'])) {
							$condition['closeDate'] = $inArr['closeDate'];
						}

						$obj = $this->find($condition);
						if (empty($obj)) {
							$tempArr['docCode'] = '��'. $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĺ�ͬ</font>';
							array_push($resultArr ,$tempArr);
							continue;
						} else {
							$inArr['id'] = $obj['id'];
						}

						$id = parent::edit_d($inArr ,true);
						if($id) {
							$tempArr['result'] = '������³ɹ�';
						} else {
							$tempArr['result'] = '<font color=red>�������ʧ��</font>';
						}
						$tempArr['docCode'] = '��'. $actNum .'������';
						array_push($resultArr ,$tempArr);
					}
				}
				return $resultArr;
			}
		}
	}

	/**
	 * ����excel
	 */
	 function excelOut($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/����-��ͬ��Ϣ����ģ��.xls" ); //��ȡģ��
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
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'J' . $i );
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
		header ( 'Content-Disposition:inline;filename="' . "��ͬ��Ϣ��������.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
	/******************* E ���뵼��ϵ�� ************************/

	/**
	 * ����Ա���ʺż���ͬ���ͻ�ȡ��ͬ��Ϣ
	 * zengzx 2012.12.01
	 */
	function getConInfoByUserId($userId,$contType){
		$this->searchArr = array (
			'userAccount' => $userId ,
			'conTypeArr' => $contType,
			'conState' => 'HRHTZT-YX'
		);
		$this->__SET('sort', 'c.id');
		$this->__SET('asc', true);
		$rows= $this->listBySqlId ();
		return $rows;
	}
}
?>