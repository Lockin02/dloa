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
 * @Date 2012��5��25�� ������ 15:12:03
 * @version 1.0
 * @description:���¹���-������Ϣ-����ϵ Model��
 */
 class model_hr_personnel_society  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_society";
		$this->sql_map = "hr/personnel/societySql.php";
		parent::__construct ();
	}

			/**
	 * ��дadd
	 */
	function add_d($object){
		return parent::add_d($object,true);
	}

/**
	 * �����û��˺Ż�ȡ��Ϣ
	 *
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
//			echo "<pre>";
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
//					echo "<pre>";
//					print_r($val);
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) ){
						continue;
					}else{
						//��������
						$inArr = array();

						//Ա�����
						if(!empty($val[0])){
							if(!isset($userArr[$val[0]])){
								$rs = $otherDataDao->getUserInfoByUserNo(trim($val[0]));
								if(!empty($rs)){
									$userConutArr[$val[0]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա�����</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}

							$inArr['userAccount'] = $userConutArr[$val[0]]['USER_ID'];
							$inArr['userNo'] = trim($val[0]);
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

						//����
						if(!empty($val[2])){
							$inArr['relationName'] = $val[2];
						}

						//���䣨�꣩
						if(!empty($val[3])){
							$inArr['age'] = $val[3];
						}

						//�뱾�˹�ϵ
						if(!empty($val[4])){
							$inArr['isRelation'] = $val[4];
						}

						//��ϵ��ʽ
						if(!empty($val[5])){
							$inArr['information'] = $val[5];
						}

						//������λ
						if(!empty($val[6])){
							$inArr['workUnit'] = $val[6];
						}

						//ְλ
						if(!empty($val[7])){
							$inArr['job'] = $val[7];
						}

						//��ע
						if(!empty($val[8])){
							$inArr['remark'] = $val[8];
						}

//						print_r($inArr);
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


	/*
	 * ����excel
	 */
	 function excelOut($rowdatas){
			PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//����һ��Excel������
		//		$objPhpExcelFile = new PHPExcel();


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/����-����ϵ����ģ��.xls" ); //��ȡģ��
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
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'I' . $i );
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
		header ( 'Content-Disposition:inline;filename="' . "����ϵ��������.xls" . '"' );
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