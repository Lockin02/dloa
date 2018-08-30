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
 * @Date 2012��5��25�� ������ 15:11:59
 * @version 1.0
 * @description:���¹���-������Ϣ-�������� Model��
 */
 class model_hr_personnel_work  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_work";
		$this->sql_map = "hr/personnel/workSql.php";
		parent::__construct ();
	}

	/**
	 * ��дadd
	 */
	function add_d($object){
		$id = parent::add_d($object ,true);

		//���¸���������ϵ
		$this->updateObjWithFile ( $id );

		//��������
		if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
			$uploadFile = new model_file_uploadfile_management ();
			$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
		}
		return $id;
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
		$deptArr = array();//��������
		$jobsArr = array();//ְλ����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");

			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])&& empty($val[5])){
						continue;
					}else{
						//��������
						$inArr = array();

						//Ա�����
						$val[0] = trim($val[0]);
						if(!empty($val[0])){
							if(!isset($userArr[$val[0]])){
								$rs = $otherDataDao->getUserInfoByUserNo($val[0]);
								if(!empty($rs)){
									$userConutArr[$val[0]] = $rs;
								}else{
									//�����µ������Ҳ����Ļ��ʹ��µ����µ���������
									$rs = $this->get_table_fields('oa_hr_personnel' ," userNo='$val[0]' " ,'userAccount');
									if (!empty($rs)) {
										$userConutArr[$val[0]]['USER_ID'] = $rs;
									} else {
										$tempArr['docCode'] = '��' . $actNum .'������';
										$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա�����</font>';
										array_push( $resultArr,$tempArr );
										continue;
									}
								}
							}

							$inArr['userAccount'] = $userConutArr[$val[0]]['USER_ID'];
							$inArr['userNo'] = $val[0];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!û��Ա�����</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//Ա������
						if(!empty($val[1])){
							if(!isset($userArr[$val[1]])){
								$rs = $otherDataDao->getUserInfo(trim($val[1]));
								if(!empty($rs)){
									$userArr[$val[1]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա������</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['userName'] = trim($val[1]);
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!û��Ա������</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��˾����
						if(!empty($val[2])){
							$inArr['company'] = $val[2];
						}

						//����
						if(!empty($val[3])){
							$inArr['dept'] = $val[3];
						}

						//ְλ
						if(!empty($val[4])){
							$inArr['position'] = $val[4];
						}

						//����
						if(!empty($val[5])){
							$inArr['treatment'] = $val[5];
						}

						//��ʼʱ��
						if(!empty($val[6])&& $val[6] != '0000-00-00'){
							$val[6] = trim($val[6]);

							if(!is_numeric($val[6])){
								$inArr['beginDate'] = $val[6];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[6] - 1 , 1900)));
								$inArr['beginDate'] = $recorderDate;
							}
						}

						//����ʱ��
						if(!empty($val[7])&& $val[7] != '0000-00-00'){
							$val[7] = trim($val[7]);

							if(!is_numeric($val[7])){
								$inArr['closeDate'] = $val[7];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[7] - 1 , 1900)));
								$inArr['closeDate'] = $recorderDate;
							}
						}

						//�ڸù�˾��������
						if(!empty($val[8])){
							$inArr['isSeniority'] = $val[8];
						}

						//��������
						if(!empty($val[9])){
							$inArr['seniority'] = $val[9];
						}

						//����ְ��
						if(!empty($val[10])){
							$inArr['responsibilities'] = $val[10];
						}

						//֤����/ְ��/�绰
						if(!empty($val[11])){
							$inArr['prove'] = $val[11];
						}

						//��ְԭ��
						if(!empty($val[12])){
							$inArr['leaveReason'] = $val[12];
						}

						//��ע
						if(!empty($val[13])){
							$inArr['remark'] = $val[13];
						}

						$newId = $this->add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '����ɹ�';
						}else{
							$tempArr['result'] = '<font color=red>����ʧ��</font>';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push( $resultArr,$tempArr );
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
		$objPhpExcelFile = $objReader->load ( "upfile/����-������������ģ��.xls" ); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//�����ǶԹ�������������������
		//���õ�ǰ������������
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '��Ϣ�б�' ) );
		//���ñ�ͷ����ʽ ����
		$i = 2;
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
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'O' . $i );
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
		header ( 'Content-Disposition:inline;filename="' . "����������������.xls" . '"' );
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