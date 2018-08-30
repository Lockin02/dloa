<?php

/**
 * @description: ʵ�����ݵ�����Excel�Ĺ���
 * @date 2010-10-20 ����07:18:35
 */
include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Cell.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

class model_asset_assetcard_assetExcelUtil
{

    //ģ������ʽ
    public static function tableThead($column, $row, $content, $objPHPExcel) {
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
    }

    //ģ������ʽ
    public static function tableTrow($column, $row, $content, $objPHPExcel) {
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
        //		$objPHPExcel->getActiveSheet ()->getStyle('C11')->getNumberFormat()->setFormatCode("@");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
    }

    //ģ������ʽ
    public static function tableTrowLong($column, $row, $content, $objPHPExcel) {
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
    public static function toCecter($i, $objPHPExcel) {
        for ($temp = 0; $temp < 12; $temp++) {
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($temp, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        }
    }

    //ģ������ʽ
    public static function tableColumn($column, $row, $content, $objPHPExcel) {
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment :: VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
        //��ʽ������Ϊ�ַ��������⵼������Ĭ���Ҷ��뵥Ԫ��
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
        $objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode("@");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
    }

    /**
     * ������Ƭ��Ϣ
     */
    public static function export2ExcelUtil($thArr, $rowdatas) {
        if (empty($rowdatas)) exit(util_jsonUtil::iconvGB2UTF('û�п��Ե���������'));

        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //��ȡģ��

        //Excel2003����ǰ�ĸ�ʽ
        $objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

        //���ñ�ͷ����ʽ ����
        $theadColumn = 0;

        foreach ($thArr as $key => $val) {
            self:: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
            $theadColumn++;
        }
        unset($thArr);

        $i = 2;
        if (!empty($rowdatas)) {
            $row = $i;
            foreach ($rowdatas as $key => $val) {
                $m = 0;
                foreach ($rowdatas[$key] as $field => $value) {
                    //�����ʾΪ��ѧ������������
                    $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                        iconv("GBK", "utf-8", $value), PHPExcel_Cell_DataType::TYPE_STRING);
                    $m++;
                }
            }
        }
        unset($rowdatas);

        //�������
        ob_end_clean(); //��������������������������
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "��Ƭ��Ϣ.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
    
    /**
     * �����̵���Ϣ
     */
	public static function exportCheckExcel($rowdatas){
    	$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
    	$objPHPExcel = $objReader->load ( "upfile/�̶��ʲ��̵��.xls" ); //��ȡģ��
    	$excelActiveSheet=$objPHPExcel->getActiveSheet ();
    	$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
    
    	foreach( $rowdatas as $key => $val){
    		$insertRow = $key+4;
    
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$insertRow,iconv("GBK","utf-8",$val['assetCode']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$insertRow,iconv("GBK","utf-8",$val['assetName']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$insertRow,iconv("GBK","utf-8",$val['machineCode']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$insertRow,iconv("GBK","utf-8",$val['userName']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$insertRow,iconv("GBK","utf-8",$val['useOrgName']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$insertRow,iconv("GBK","utf-8",$val['brand']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$insertRow,iconv("GBK","utf-8",$val['spec']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$insertRow,iconv("GBK","utf-8",$val['deploy']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$insertRow,iconv("GBK","utf-8",$val['belongMan']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$insertRow,iconv("GBK","utf-8",$val['orgName']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$insertRow,iconv("GBK","utf-8",$val['agencyName']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$insertRow,iconv("GBK","utf-8",$val['wirteDate']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$insertRow,iconv("GBK","utf-8",$val['origina']));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$insertRow,iconv("GBK","utf-8",1));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$insertRow,iconv("GBK","utf-8",1));
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17,$insertRow,iconv("GBK","utf-8",$val['remark']));
    	}
    	//�������
    	ob_end_clean();//��������������������������
    	header ( "Content-Type: application/force-download" );
    	header ( "Content-Type: application/octet-stream" );
    	header ( "Content-Type: application/download" );
    	header ( 'Content-Disposition:inline;filename="' . "�̶��ʲ��̵��.xls" . '"' );
    	header ( "Content-Transfer-Encoding: binary" );
    	header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
    	header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
    	header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
    	header ( "Pragma: no-cache" );
    	$objWriter->save ( 'php://output' );
    }
    
    /**
     * ����csv��ʽ���ļ�
     * @param $head
     * @param $data
     * @param $file
     */
    public static function exportCSV($head, $data, $file = '�����ļ�') {
    	if (empty($data)) exit(util_jsonUtil::iconvGB2UTF('û�в�ѯ���������'));
    	// ͷ�ļ�
    	header("Content-type: text/csv; charset=gbk"); // �ر�˵����excel��Ҫ֧�����ĵ�ʱ��Ҫ��������ݵı���תΪgbk
    	header('Content-Disposition: attachment;filename="' . $file . '.csv"');
    	header('Cache-Control: max-age=0');
    
    	$fp = fopen('php://output', 'w');
    
    	// д����ͷ
    	fputcsv($fp, $head);
    
    	// ��ȡ�ֶ�����
    	$headKeys = array_keys($head);
    
    	$count = count($data);
    
    	// ���ֻ�Ǽ򵥵����ݶ�ȡ�����ﻹ�����Ż�
    	foreach ($data as $k => $v) {
    
    		$outArr = array();
    		foreach ($headKeys as $vi) {
    			$outArr[] = is_numeric($v[$vi]) ? $v[$vi] . "\t" : $v[$vi];
    		}
    		fputcsv($fp, $outArr, ',', '"');
    		unset($outArr);
    
    		// ÿ10000���������һ�λ���
    		if (($k != 0 && $k % 10000) || $k == $count) {
    			ob_flush();
    			flush();
    		}
    	}
    }
}