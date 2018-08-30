<?php
/**
 * @description: ʵ�����ݵ�����Excel�Ĺ���
 * @date 2010-10-20 ����07:18:35
 */

include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_rdproject_task_rdExcelUtil {

	//�ϴ�Excel����ȡ excel����
	function upReadExcelData($file, $filetempname) {
		//�Լ����õ��ϴ��ļ����·��

		$filePath = UPLOADPATH.'upfile/';
		if(!is_dir($filePath)){
			mkdir($filePath);
		}
		$str = "";

		//�����·��������PHPExcel��·�����޸�
		//set_include_path ( '.' . PATH_SEPARATOR . 'D:\EXCELDEMO' . PATH_SEPARATOR . get_include_path () );


		$filename = explode ( ".", $file ); //���ϴ����ļ����ԡ�.����Ϊ׼��һ�����顣
		$time = date ( "y-m-d-H-i-s" ); //ȥ��ǰ�ϴ���ʱ��
		$filename [0] = $time; //ȡ�ļ����滻
		$name = implode ( ".", $filename ); //�ϴ�����ļ���
		$uploadfile = $filePath . $name; //�ϴ�����ļ�����ַ


		//move_uploaded_file() �������ϴ����ļ��ƶ�����λ�á����ɹ����򷵻� true�����򷵻� false��
		$result = move_uploaded_file ( $filetempname, $uploadfile ); //�����ϴ�����ǰĿ¼��
		if ($result){ //����ϴ��ļ��ɹ�����ִ�е���excel����
			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
			$objPHPExcel = $objReader->load ( $uploadfile ); //   $objPHPExcel = $objReader->load($uploadfile);
			$sheet = $objPHPExcel->getSheet ( 0 );
			$highestRow = $sheet->getHighestRow (); // ȡ��������
			$highestColumn = $sheet->getHighestColumn (); // ȡ��������


			$dataArr = array (); //��ȡ���


			//ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
			for($j = 2; $j <= $highestRow; $j ++) {
				for($k = 'A'; $k <= $highestColumn; $k ++) {
					$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ) . '\\'; //��ȡ��Ԫ��
				}
				//explode:�������ַ����ָ�Ϊ���顣
				$strs = explode ( "\\", $str );
				array_push ( $dataArr, $strs );
				$str = "";
			}

			//			unlink ( $uploadfile ); //ɾ���ϴ���excel�ļ�
			$msg = "��ȡ�ɹ���";
		} else {
			$msg = "��ȡʧ�ܣ�";
		}

		return $dataArr;
	}


	//��ȡģ�岢����
	public static function exporTemplate($thArr, $rowdatas){
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/protemplate.xls" ); //��ȡģ��
		$excelActiveSheet=$objPHPExcel->getActiveSheet ();
		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";

		$fileSave = $path . "/" . $fileName;

		$dataArr=array(
			"projectName"=>"����������",
			"status"=>"������",
			"chargeName"=>"���Ƿ�",
			"peopleNum"=>"10(��)",
			"finRate"=>"50%",
			"waptRate"=>"1%",
			"planHours"=>"50(Сʱ)",
			"inputHours"=>"20(Сʱ)",
			"nowStone"=>"Ԥ�н׶�",
			"stoneDate"=>"2010-11-30"
		);
		$tkGsNum=array(3,5,7,9,10,4,3,2);
		$planTimeArr=array(
			array(
				"sequence"=>"1",
				"planName"=>"�ƻ�1",
				"planUseTime"=>"25(Сʱ)",
				"useTime"=>"15(Сʱ)",
				"finRate"=>"70%"

			),
			array(
				"sequence"=>"2",
				"planName"=>"�ƻ�2",
				"planUseTime"=>"35(Сʱ)",
				"useTime"=>"30(Сʱ)",
				"finRate"=>"90%"
			)
		);
//		echo "------".$objPHPExcel->getCellXfByHashCode('$projectName');

			for($j = 2; $j <= 6; $j ++) {//�滻ģ���ֶ�
				for($k = 'A'; $k <= 'G'; $k ++) {
					$ename= iconv ( 'utf-8', 'gb2312', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue ()); //��ȡ��Ԫ��
					if(isset($dataArr[$ename])){
						$pvalue=$dataArr[$ename];
						$objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->setValue(iconv ( "gb2312", "utf-8", $pvalue));
					}

				}
			}
			$i=10;
			foreach($tkGsNum as $key=>$cvalue){

				$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(11,$i,iconv ( "GBK", "utf-8", $cvalue));
				$i++;
			}

			//list I32
			for($n=0;$n<count($planTimeArr);$n++){
				$m=0;
				foreach($planTimeArr[$n] as $field =>$value){
					$objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow(8+$m,32+$n,iconv("GBK","utf-8",$value));
					$m+=2;
				}

			}


		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );

		$objWriter = PHPExcel_IOFactory::CreateWriter ( $objPHPExcel );
		$objWriter->save ( $fileSave );
	}

	/*
		 * $cellValueΪ����Ķ�ά����
		 */
	public static function export2ExcelUtil($thArr, $rowdatas) {
		//����һ��Excel������
		$objPhpExcelFile = new PHPExcel ();
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '��Ŀ�����б�') );

		$temp = count ( $rowdatas ) + 1;
		if( isset($rowdatas[0]['projectCode']) && isset($rowdatas[0]['projectName']) ){
			$projectCode = 'A2:A' . $temp;
			$projectName = 'B2:B' . $temp;
			$objPhpExcelFile->getActiveSheet()->mergeCells($projectCode);
			$objPhpExcelFile->getActiveSheet()->mergeCells($projectName);
		}else if( !isset($rowdatas[0]['projectCode']) && !isset($rowdatas[0]['projectName']) ){
			;
		}else{
			$projectCode = 'A2:A' . $temp;
			$objPhpExcelFile->getActiveSheet()->mergeCells($projectCode);
		}
		//���ñ�ͷ����ʽ ����
		for($m = 0; $m < count ( $thArr ); $m ++) {
			$objPhpExcelFile->getActiveSheet()->getDefaultColumnDimension()->setWidth(16);
			$objPhpExcelFile->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, 1 )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
			$objPhpExcelFile->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, 1 )->getFill ()->getStartColor ()->setARGB ( "0099CCFF" );
			$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, 1, iconv ( "gb2312", "utf-8", $thArr [$m] ) );
//			$temp ++;
		}
		//��������
		for($i = 0; $i < count ( $rowdatas ); $i ++) {
			$j = 0;
			foreach ( $rowdatas [$i] as $key => $value ) {
				if( $value == '' ){
					$value="-";
				}
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $j, $i + 2, iconv ( "GBK", "utf-8", $value ) );
				$j ++;
			}
		}

		//�������
		ob_end_clean();//��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "YX_EXCEL.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/*
		 * $cellValueΪ����Ķ�ά����
		 */
	public static function dowcImportTemplate() {
		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load ( "upfile/importTemplate.xls" ); //��ȡģ��
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );

		//�������
//		ob_end_clean();//��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "�з�������ģ��.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
		return 1;
	}


	/*
		 * $cellValueΪ����Ķ�ά����
		 */
	public static function myTaskExcelUtil($thArr, $rowdatas) {
		//����һ��Excel������
		$objPhpExcelFile = new PHPExcel ();
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '��Ŀ�����б�') );

		//���ñ�ͷ����ʽ ����
		for($m = 0; $m < count ( $thArr ); $m ++) {
			$objPhpExcelFile->getActiveSheet()->getDefaultColumnDimension()->setWidth(16);
			$objPhpExcelFile->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, 1 )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
			$objPhpExcelFile->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, 1 )->getFill ()->getStartColor ()->setARGB ( "0099CCFF" );
			$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, 1, iconv ( "gb2312", "utf-8", $thArr [$m] ) );
//			$temp ++;
		}
		//��������
		for($i = 0; $i < count ( $rowdatas ); $i ++) {
			$j = 0;
			foreach ( $rowdatas [$i] as $key => $value ) {
				if( $value == '' ){
					$value="-";
				}
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $j, $i + 2, iconv ( "GBK", "utf-8", $value ) );
				$j ++;
			}
		}

		//�������
		ob_end_clean();//��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "YX_EXCEL.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}


}
?>