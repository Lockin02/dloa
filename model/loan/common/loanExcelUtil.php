<?php
/**
 * @description: 实现数据导出到Excel的功能
 * @date 2017-06-22 下午19:18:35
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
    //模块名样式
    function tableThead($column, $row, $content, $objPHPExcel) {
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
    }

    //模块列样式
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
        //格式化数字为字符串，避免导致数字默认右对齐单元格
        //		$objPHPExcel->getActiveSheet ()->getStyle('C11')->getNumberFormat()->setFormatCode("@");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
    }

    //模块列样式
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

    //模块内容居中样式
    function toCecter($i, $objPHPExcel) {
        for ($temp = 0; $temp < 50; $temp++) {
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($temp, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        }
    }

    //模块列样式
    function tableColumn($column, $row, $content, $objPHPExcel) {
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment :: VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border :: BORDER_THIN);
        //格式化数字为字符串，避免导致数字默认右对齐单元格
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
        $objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode("@");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
    }

    /**
     * 导出列表
     */
    public static function export2ExcelUtil($thArr, $rowdatas,$fileName="列表信息"){
        if(empty($rowdatas)) exit('没有相关可导出数据');
        set_time_limit(0);
        ini_set('memory_limit', '1024M');	//设置内存
//        		$stime=microtime(true); //获取程序开始执行的时间
        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
        //		//创建一个Excel工作流
        //		$objPhpExcelFile = new PHPExcel();

        $objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xlsx"); //读取模板
        //Excel2007及以前的格式
        $objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

        //以下是对工作表基本参数的设置
        //设置当前工作表的名称
        $objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8",$fileName));
        //设置表头及样式 设置
        $theadColumn = 0;
        foreach ($thArr as $key => $val) {
            model_loan_common_loanExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
            $theadColumn++;
        }
        $i = 2;
        //获取th key用于数据布局
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
                $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow(0, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
            }
        }
        //到浏览器
        ob_end_clean(); //解决输出到浏览器出现乱码的问题
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
     * 导出csv格式的文件
     * @param $head
     * @param $data
     * @param $file
     */
    public static function exportCSV($head, $data, $file = '导出文件') {
        if (empty($data)) exit(util_jsonUtil::iconvGB2UTF('没有查询到相关数据'));
        // 头文件
        header("Content-type: text/csv; charset=gbk"); // 特别说明：excel需要支持中文的时候要将输出内容的编码转为gbk
        header('Content-Disposition: attachment;filename="' . $file . '.csv"');
        header('Cache-Control: max-age=0');

        $fp = fopen('php://output', 'w');

        // 写入列头
        fputcsv($fp, $head);

        // 获取字段数据
        $headKeys = array_keys($head);

        $count = count($data);

        // 这边只是简单的数据读取，这里还可以优化
        foreach ($data as $k => $v) {

            $outArr = array();
            foreach ($headKeys as $vi) {
                $outArr[] = is_numeric($v[$vi]) ? $v[$vi] . "\t" : $v[$vi];
            }
            fputcsv($fp, $outArr, ',', '"');
            unset($outArr);

            // 每10000条数据输出一次缓存
            if (($k != 0 && $k % 10000) || $k == $count) {
                ob_flush();
                flush();
            }
        }
    }
}