<?php
/**
 * @description: ʵ�����ݵ�����Excel�Ĺ���
 * @date 2017-06-22 ����19:18:35
 */
include_once "module/phpExcel/classes/PHPExcel.php";
include_once "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once ('model/contract/common/simple_html_dom.php');
include_once "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

class model_loan_common_loanExcelUtil {
    //ģ������ʽ
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
        //		$objPHPExcel->getActiveSheet ()->getStyle('C11')->getNumberFormat()->setFormatCode("@");
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
        for ($temp = 0; $temp < 50; $temp++) {
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($temp, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        }
    }

    //ģ������ʽ
    function tableColumn($column, $row, $content, $objPHPExcel) {
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
     * �����б�
     */
    public static function export2ExcelUtil($thArr, $rowdatas,$fileName="�б���Ϣ"){
        if(empty($rowdatas)) exit('û����ؿɵ�������');
        set_time_limit(0);
        ini_set('memory_limit', '1024M');	//�����ڴ�
//        		$stime=microtime(true); //��ȡ����ʼִ�е�ʱ��
        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
        //		//����һ��Excel������
        //		$objPhpExcelFile = new PHPExcel();

        $objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xlsx"); //��ȡģ��
        //Excel2007����ǰ�ĸ�ʽ
        $objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

        //�����ǶԹ������������������
        //���õ�ǰ�����������
        $objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8",$fileName));
        //���ñ�ͷ����ʽ ����
        $theadColumn = 0;
        foreach ($thArr as $key => $val) {
            model_loan_common_loanExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
            $theadColumn++;
        }
        $i = 2;
        //��ȡth key�������ݲ���
        $keysArr = array_keys($thArr);

        if (!count(array_filter($rowdatas)) == 0) {
            $row = $i;
            for ($n = 0; $n < count($rowdatas); $n++) {
                $m = 0;
                foreach($keysArr as $val){
                    $objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, mb_convert_encoding($rowdatas[$n][$val],"utf-8","gbk,big5") );
                    if($val == "id" || $val == "Amount" || $val == "reMoney" || $val == "isBackMoney"){
                        $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $n, iconv("GBK", "utf-8", $rowdatas[$n][$val]), PHPExcel_Cell_DataType :: TYPE_NUMERIC);
                    }
                    if($val == "Amount" || $val == "reMoney" || $val == "isBackMoney"){
                        $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_RIGHT);
                    }
                    $m++;
                }
                $i++;
            }
        } else {
            if(count($rowdatas)>0){
                $objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
            }
            for ($m = 0; $m < count($thArr); $m++) {
                $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow(0, $i, iconv("gb2312", "utf-8", '���������Ϣ'));
            }
        }
        //�������
        ob_end_clean(); //��������������������������
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "".$fileName.".xlsx" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
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