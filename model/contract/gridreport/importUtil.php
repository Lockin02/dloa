<?php
/**
 * @description: excel����util
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

class model_contract_gridreport_importUtil extends model_base {

	//��ͬ��Ŀ��ģ�鵼��
	function exportContract($thArr ,$rowdatas ,$modelName) {
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		// //����һ��Excel������
		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/gridreport.xlsx"); //��ȡģ��

		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);
		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8","$modelName"));
		//���ñ�ͷ����ʽ ����
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($theadColumn ,1 ,iconv("gbk" ,"utf-8" ,$val['name']));
			$theadColABC = model_contract_gridreport_importUtil::Decimal2ABC($theadColumn);
			$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
			$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
			$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
			$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
			if (is_array($val['children'])) {
				$strtCol = model_contract_gridreport_importUtil::Decimal2ABC($theadColumn);
				$endCol = model_contract_gridreport_importUtil::Decimal2ABC($theadColumn + count($val['children']) - 1);
				$objPhpExcelFile->getActiveSheet()->mergeCells($strtCol.'1:'.$endCol.'1'); //�ϲ���Ԫ��
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($theadColumn ,1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //�ϲ���Ԫ����о���
				$objPhpExcelFile->getActiveSheet()->getStyle($strtCol.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPhpExcelFile->getActiveSheet()->getStyle($strtCol.'1')->getFill()->getStartColor()->setARGB('7AD3F7'); //���ñ���ɫ
				foreach ($val['children'] as $k => $v) {
					$theadColABC = model_contract_gridreport_importUtil::Decimal2ABC($theadColumn);
					$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($theadColumn ,2 ,iconv("gbk" ,"utf-8" ,$v));
					$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'2')->getFill()->getStartColor()->setARGB('7AD3F7'); //���ñ���ɫ
					$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($theadColumn ,2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //����
					$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
					$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
					$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
					$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
					$theadColumn++;
				}
				$theadColumn--;
			} else {
				$theadColABC = model_contract_gridreport_importUtil::Decimal2ABC($theadColumn);
				$objPhpExcelFile->getActiveSheet()->mergeCells($theadColABC.'1:'.$theadColABC.'2'); //�ϲ���Ԫ��
				$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'1')->getFill()->getStartColor()->setARGB('7AD3F7'); //���ñ���ɫ
				$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
				$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
				$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
				$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
				$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
				$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
				$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
				$objPhpExcelFile->getActiveSheet()->getStyle($theadColABC.'2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN );
			}
			$theadColumn++;
		}
		$i = 3;

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
			$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow(0, $i, mb_convert_encoding('���������Ϣ',"utf-8","gbk,big5"));
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
