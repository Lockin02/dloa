<?php
/**
 * @description: ����ģ��excel����util
 * @date 2014-01-09
 */
include_once "module/phpExcel/classes/PHPExcel.php";
include_once "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once "model/contract/common/simple_html_dom.php";
include_once "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

class model_produce_basic_produceExcelUtil extends model_base {

	function tableThead($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}

	//ģ������ʽ
	function tableTrow($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension(chr(ord('A') + $column))->setWidth(12);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment :: VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
		//��ʽ������Ϊ�ַ��������⵼������Ĭ���Ҷ��뵥Ԫ��
		//$objPHPExcel->getActiveSheet ()->getStyle('C11')->getNumberFormat()->setFormatCode("@");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}

	//ģ������ʽ
	function tableTrowLong($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension(chr(ord('A') + $column))->setWidth(26);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment :: VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}
	//ģ�����ݾ�����ʽ
	function toCecter($i, $objPHPExcel) {
		for($temp = 0; $temp < 12; $temp ++) {
			$objPHPExcel->getActiveSheet ()->getStyleByColumnAndRow ( $temp, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		}
	}

	function exportExcelUtil($thArr, $rowdatas, $modelName) {

		// $stime=microtime(true); //��ȡ����ʼִ�е�ʱ��
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		// ����һ��Excel������
		// $objPhpExcelFile = new PHPExcel();
		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/$modelName.xlsx"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", $modelName));
		//���ñ�ͷ����ʽ ����
		$theadColumn = 0;
		if(is_array($thArr)){
			foreach ($thArr as $key => $val) {
				model_produce_basic_produceExcelUtil::tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
				$theadColumn++;
			}

		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5"));
					$m++;
				}
				$i++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '���������Ϣ'));
			}
		}

		//�������
		ob_end_clean();    //��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "$modelName.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	//��ͷ�ж���
	function export2ExcelUtil($thArr, $rowdatas, $modelName, $startRowNum) {

		// $stime=microtime(true); //��ȡ����ʼִ�е�ʱ��
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		// ����һ��Excel������
		// $objPhpExcelFile = new PHPExcel();
		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/$modelName.xlsx"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", $modelName));
		//���ñ�ͷ����ʽ ����
		$theadColumn = 0;
		if(is_array($thArr)){
			foreach ($thArr as $key => $val) {
				model_produce_basic_produceExcelUtil::tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
				$theadColumn++;
			}

		}
		$i = $startRowNum;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5"));
					$m++;
				}
				$i++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '���������Ϣ'));
			}
		}

		//�������
		ob_end_clean();    //��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "$modelName.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	//������������
	function exportProduce($datas) {
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		// ����һ��Excel������
		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/����-��������.xlsx"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", "��������"));

		if (!count(array_filter($datas)) == 0) {
			//��������
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,2 ,mb_convert_encoding($datas['proType'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('B2')->getFill()->getStartColor()->setARGB('FFFFFFFF');
			//���ϱ��
//			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(3 ,2 ,mb_convert_encoding($datas['productCode'] ,"utf-8" ,"gbk,big5"));
//			$objPhpExcelFile->getActiveSheet()->getStyle('D2')->getFill()->getStartColor()->setARGB('FFFFFFFF');
			//��������
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(5 ,2 ,mb_convert_encoding($datas['docDate'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('F2')->getFill()->getStartColor()->setARGB('FFFFFFFF');

			//��;
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,3 ,mb_convert_encoding($datas['purpose'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('B3')->getFill()->getStartColor()->setARGB('FFFFFFFF');
			//����
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(3 ,3 ,mb_convert_encoding($datas['technology'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('D3')->getFill()->getStartColor()->setARGB('FFFFFFFF');
			//�ļ����
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(5 ,3 ,mb_convert_encoding($datas['fileNo'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('F3')->getFill()->getStartColor()->setARGB('FFFFFFFF');

			//�ͻ�����
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,4 ,mb_convert_encoding($datas['customerName'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('B4')->getFill()->getStartColor()->setARGB('FFFFFFFF');
			//��ͬ���
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(3 ,4 ,mb_convert_encoding($datas['relDocCode'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('D4')->getFill()->getStartColor()->setARGB('FFFFFFFF');
			//��������
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(5 ,4 ,mb_convert_encoding($datas['productionBatch'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('F4')->getFill()->getStartColor()->setARGB('FFFFFFFF');

			//����˵��
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,5 ,mb_convert_encoding($datas['salesExplain'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('B5')->getFill()->getStartColor()->setARGB('FFFFFFFF');
			//���۴���
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(5 ,5 ,mb_convert_encoding($datas['saleUserName'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('F5')->getFill()->getStartColor()->setARGB('FFFFFFFF');

			//��������
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,6 ,mb_convert_encoding($datas['config'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('B6')->getFill()->getStartColor()->setARGB('FFFFFFFF');
			//����
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(3 ,2 ,mb_convert_encoding($datas['planNum'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('F6')->getFill()->getStartColor()->setARGB('FFFFFFFF');

			$rowNum = 6;
			//��������
			foreach ($datas['productInfo'] as $key => $val) {
				$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(0 ,$rowNum ,mb_convert_encoding($val['tableHead'][0]['configCode'].'����' ,"utf-8" ,"gbk,big5"));
				$objPhpExcelFile->getActiveSheet()->mergeCells('A'.$rowNum.':'.model_produce_basic_produceExcelUtil::Decimal2ABC(count($val['tableHead']) - 1).$rowNum); //�ϲ���Ԫ��
				$objPhpExcelFile->getActiveSheet()->getStyle('A'.$rowNum)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPhpExcelFile->getActiveSheet()->getStyle('A'.$rowNum)->getFill()->getStartColor()->setARGB('FF808080'); //���ñ���ɫ
				$rowNum++;
				foreach ($val['tableHead'] as $ke => $va) {
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($ke ,$rowNum ,mb_convert_encoding($va['colName'] ,"utf-8" ,"gbk,big5"));
					$objPhpExcelFile->getActiveSheet()->getStyle(model_produce_basic_produceExcelUtil::Decimal2ABC($ke).$rowNum)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPhpExcelFile->getActiveSheet()->getStyle(model_produce_basic_produceExcelUtil::Decimal2ABC($ke).$rowNum)->getFill()->getStartColor()->setARGB('FFFFFF00'); //���ñ���ɫ
					foreach ($val['tableBody'] as $k => $v) {
						$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($ke ,$rowNum + $k + 1,mb_convert_encoding($v[$ke]['colContent'] ,"utf-8" ,"gbk,big5"));
						$objPhpExcelFile->getActiveSheet()->getStyle(model_produce_basic_produceExcelUtil::Decimal2ABC($ke).($rowNum + $k + 1))->getFill()->getStartColor()->setARGB('FFFFFFFF');
					}
				}
				$rowNum += count($val['tableBody']) + 2;
			}

			//��ע
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(0 ,$rowNum ,mb_convert_encoding('��ע' ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('A'.$rowNum)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPhpExcelFile->getActiveSheet()->getStyle('A'.$rowNum)->getFill()->getStartColor()->setARGB('FFFFFF00'); //���ñ���ɫ
			$objPhpExcelFile->getActiveSheet()->getStyle('A'.$rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //����ˮƽ����
			$objPhpExcelFile->getActiveSheet()->getStyle('A'.$rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //���ô�ֱ����
			$objPhpExcelFile->getActiveSheet()->getRowDimension($rowNum)->setRowHeight(50); // �����и�
			$objPhpExcelFile->getActiveSheet()->mergeCells('B'.$rowNum.':F'.$rowNum); //�ϲ���Ԫ��
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,$rowNum ,mb_convert_encoding($datas['remark'] ,"utf-8" ,"gbk,big5"));
			$objPhpExcelFile->getActiveSheet()->getStyle('B'.$rowNum)->getFill()->getStartColor()->setARGB('FFFFFFFF');
			$objPhpExcelFile->getActiveSheet()->getStyle('B'.$rowNum)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //���ô�ֱ����
			$objPhpExcelFile->getActiveSheet()->getStyle('B'.$rowNum)->getAlignment()->setWrapText(true); //��Ԫ���ڻ���
			$rowNum += 2;

			//����
			if (is_array($datas['process'])) {
				//��ͷ
				$processHead = array('����' ,'��Ŀ����' ,'����ʱ�䣨�룩' ,'������' ,'��ע');
				foreach ($processHead as $key => $val) {
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($key ,$rowNum ,mb_convert_encoding($val ,"utf-8" ,"gbk,big5"));
					$objPhpExcelFile->getActiveSheet()->getStyle(model_produce_basic_produceExcelUtil::Decimal2ABC($key).$rowNum)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPhpExcelFile->getActiveSheet()->getStyle(model_produce_basic_produceExcelUtil::Decimal2ABC($key).$rowNum)->getFill()->getStartColor()->setARGB('FFFFFF00'); //���ñ���ɫ
					$objPhpExcelFile->getActiveSheet()->getStyle(model_produce_basic_produceExcelUtil::Decimal2ABC($key).$rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //����ˮƽ����
				}
				$rowNum++;

				//����
				foreach ($datas['process'] as $key => $val) {
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(0 ,$rowNum ,mb_convert_encoding($val['process'] ,"utf-8" ,"gbk,big5"));
					$objPhpExcelFile->getActiveSheet()->getStyle('A'.$rowNum)->getFill()->getStartColor()->setARGB('FFFFFFFF');
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,$rowNum ,mb_convert_encoding($val['processName'] ,"utf-8" ,"gbk,big5"));
					$objPhpExcelFile->getActiveSheet()->getStyle('B'.$rowNum)->getFill()->getStartColor()->setARGB('FFFFFFFF');
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(2 ,$rowNum ,mb_convert_encoding($val['processTime'] ,"utf-8" ,"gbk,big5"));
					$objPhpExcelFile->getActiveSheet()->getStyle('C'.$rowNum)->getFill()->getStartColor()->setARGB('FFFFFFFF');
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(3 ,$rowNum ,mb_convert_encoding($val['recipient'] ,"utf-8" ,"gbk,big5"));
					$objPhpExcelFile->getActiveSheet()->getStyle('D'.$rowNum)->getFill()->getStartColor()->setARGB('FFFFFFFF');
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(4 ,$rowNum ,mb_convert_encoding($val['remark'] ,"utf-8" ,"gbk,big5"));
					$objPhpExcelFile->getActiveSheet()->getStyle('E'.$rowNum)->getFill()->getStartColor()->setARGB('FFFFFFFF');
					$rowNum++;
				}
			}
		} else {
			$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '���������Ϣ'));
			}
		}

		//�������
		ob_end_clean();    //��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "��������.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	//�����������ϵ�
	function exportPicking($datas) {
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		// ����һ��Excel������
		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/����-�������ϵ���Ϣ.xlsx"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

		if (!count(array_filter($datas)) == 0) {
			//���ݱ��
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,2 ,mb_convert_encoding($datas['docCode'] ,"utf-8" ,"gbk,big5"));
			//Դ������
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(5 ,2 ,mb_convert_encoding($datas['relDocName'] ,"utf-8" ,"gbk,big5"));

			//Դ�����
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,3 ,mb_convert_encoding($datas['relDocCode'] ,"utf-8" ,"gbk,big5"));
			//Դ������
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(5 ,3 ,mb_convert_encoding($datas['relDocType'] ,"utf-8" ,"gbk,big5"));

			//�������񵥺�
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,4 ,mb_convert_encoding($datas['taskCode'] ,"utf-8" ,"gbk,big5"));
			//�����ƻ�����
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(5 ,4 ,mb_convert_encoding($datas['planCode'] ,"utf-8" ,"gbk,big5"));

			//������
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,5 ,mb_convert_encoding($datas['createName'] ,"utf-8" ,"gbk,big5"));
			//��������
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(5 ,5 ,mb_convert_encoding($datas['docDate'] ,"utf-8" ,"gbk,big5"));

			//��ע
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,6 ,mb_convert_encoding($datas['remark'] ,"utf-8" ,"gbk,big5"));

			foreach ($datas['item'] as $key => $val) {
				$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(0 ,$key + 8 ,mb_convert_encoding(($key + 1) ,"utf-8" ,"gbk,big5"));
				$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(1 ,$key + 8 ,mb_convert_encoding($val['productCode'] ,"utf-8" ,"gbk,big5"));
				$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(2 ,$key + 8 ,mb_convert_encoding($val['productName'] ,"utf-8" ,"gbk,big5"));
				$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(3 ,$key + 8 ,mb_convert_encoding($val['pattern'] ,"utf-8" ,"gbk,big5"));
				$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(4 ,$key + 8 ,mb_convert_encoding($val['unitName'] ,"utf-8" ,"gbk,big5"));
				$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(5 ,$key + 8 ,mb_convert_encoding($val['applyNum'] ,"utf-8" ,"gbk,big5"));
				$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(6 ,$key + 8 ,mb_convert_encoding($val['planDate'] ,"utf-8" ,"gbk,big5"));
				$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(7 ,$key + 8 ,mb_convert_encoding($val['remark'] ,"utf-8" ,"gbk,big5"));
			}
		} else {
			$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			for ($m = 0; $m < count($thArr); $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '���������Ϣ'));
			}
		}

		//�������
		ob_end_clean();    //��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "�������ϵ�.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	/**
	 * 10����ת��ĸ��26������
	 * 0=>A,1=>B....26=>AA
	 * �˴�ֻ�ܽ���0(A)-701(ZZ)����ת��
	 */
	function Decimal2ABC($num) {
		if ($num < 26) {
			return chr(65 + $num);
		}

		$ABCstr = "";
		$ten = $num;
		$isLast = true;
		$first = true;

		while ( $isLast ) {
			$tmp = intval($ten / 26);
			if ($first) {
				$tmp--;
				$first = false;
			}
			$ABCstr .= chr(65 + $tmp);
			if ($tmp / 26 > 1) {
				$ten = $ten - 26 * $tmp;
			} else {
				$ABCstr .= chr(65 + ($ten % 26));
				$isLast = false;
			}
		}
		return $ABCstr;
	}
}
?>
