<?php
/*
 * Created on 2012-2-25
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "model/contract/common/simple_html_dom.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

class model_finance_common_financeExcelUtil
{

    //上传Excel并读取 excel数据
    public static function upReadExcelDataClear($file, $filetempname)
    {
        //自己设置的上传文件存放路径
        $filePath = UPLOADPATH . 'upfile/';
        if (!is_dir($filePath)) {
            mkdir($filePath);
        }
        $str = "";

        //下面的路径按照你PHPExcel的路径来修改
        $filename = explode(".", $file); //把上传的文件名以“.”好为准做一个数组。
        $time = date("y-m-d-H-i-s"); //去当前上传的时间
        $filename [0] = $time; //取文件名替换
        $name = implode(".", $filename); //上传后的文件名
        $uploadfile = $filePath . $name; //上传后的文件名地址
        $result = move_uploaded_file($filetempname, $uploadfile); //假如上传到当前目录下
        if ($result) { //如果上传文件成功，就执行导入excel操作
            $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
            $objPHPExcel = $objReader->load($uploadfile); //   $objPHPExcel = $objReader->load($uploadfile);
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumn = $sheet->getHighestColumn(); // 取得总列数
            $dataArr = array(); //读取结果
            //循环读取excel文件,读取一条,存取到数组一条
            for ($j = 2; $j <= $highestRow; $j++) {
                for ($k = 'A'; $k <= $highestColumn; $k++) {
                    $str .= iconv('utf-8', 'gbk', $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue()) . '|--|'; //读取单元格
                }
                //explode:函数把字符串分割为数组。
                $strs = explode("|--|", $str);
                array_push($dataArr, $strs);
                $str = "";
            }
        }
        return $dataArr;
    }

    /**导出产品入库核算信息
     * author show
     * 2012-02-25
     */
    public static function productInCalExcelOut($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/产品入库导出.xls"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel5 ($objPHPExcel);

        //		var_dump($rowdatas);

        foreach ($rowdatas as $key => $val) {
            $insertRow = 2 + $key;
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(0, $insertRow, iconv("GBK", "utf-8", $val['productCode']), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(1, $insertRow, iconv("GBK", "utf-8", $val['k3Code']), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $insertRow, iconv("GBK", "utf-8", $val['productName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $insertRow, iconv("GBK", "utf-8", $val['pattern']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $insertRow, iconv("GBK", "utf-8", $val['batchNum']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $insertRow, iconv("GBK", "utf-8", $val['actNum']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $insertRow, iconv("GBK", "utf-8", $val['unitName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $insertRow, iconv("GBK", "utf-8", $val['price']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $insertRow, iconv("GBK", "utf-8", $val['subPrice']));
        }
        //到浏览器
        ob_end_clean();//解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "产品入库导出.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**导出产品入库核算信息
     * author show
     * 2012-02-25
     */
    public static function productInCalExcelOutDept($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/产品入库按部门导出.xls"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel5 ($objPHPExcel);

        foreach ($rowdatas as $key => $val) {
            $insertRow = 2 + $key;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $insertRow, iconv("gb2312", "utf-8", $val['purchaserName']));
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(1, $insertRow, iconv("gb2312", "utf-8", $val['productCode']), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(2, $insertRow, iconv("gb2312", "utf-8", $val['k3Code']), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $insertRow, iconv("gb2312", "utf-8", $val['productName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $insertRow, iconv("gb2312", "utf-8", $val['pattern']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $insertRow, iconv("gb2312", "utf-8", $val['batchNum']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $insertRow, iconv("gb2312", "utf-8", $val['actNum']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $insertRow, iconv("gb2312", "utf-8", $val['unitName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $insertRow, iconv("gb2312", "utf-8", $val['price']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $insertRow, iconv("gb2312", "utf-8", $val['subPrice']));
        }
        //到浏览器
        ob_end_clean();//解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "产品入库按部门导出.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }


    /**导出开票查询
     *author show
     *2011-07-26
     */
    public static function exportInvoice($rowdatas)
    {
        PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/financeInvoice.xls"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel5 ($objPHPExcel);

        foreach ($rowdatas as $key => $val) {
            $insertRow = 3 + $key;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $insertRow, iconv("gb2312", "utf-8", $val['thisAreaName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $insertRow, iconv("gb2312", "utf-8", $val['prinvipalName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $insertRow, iconv("gb2312", "utf-8", $val['areaPrincipal']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $insertRow, iconv("gb2312", "utf-8", $val['invoiceTime']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $insertRow, iconv("gb2312", "utf-8", $val['invoiceUnitName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $insertRow, iconv("gb2312", "utf-8", $val['invoiceUnitProvince']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $insertRow, iconv("gb2312", "utf-8", $val['invoiceUnitTypeName']));
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(7, $insertRow, iconv("gb2312", "utf-8", $val['invoiceNo']), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $insertRow, iconv("gb2312", "utf-8", $val['invoiceTypeName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $insertRow, iconv("gb2312", "utf-8", $val['orderCode']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $insertRow, iconv("gb2312", "utf-8", $val['objTypeCN']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $insertRow, iconv("gb2312", "utf-8", $val['signSubjectName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $insertRow, iconv("gb2312", "utf-8", $val['sign']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $insertRow, iconv("gb2312", "utf-8", ''));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $insertRow, iconv("gb2312", "utf-8", $val['amount']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $insertRow, iconv("gb2312", "utf-8", $val['productName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $insertRow, iconv("gb2312", "utf-8", $val['psType']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $insertRow, iconv("gb2312", "utf-8", $val['softMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $insertRow, iconv("gb2312", "utf-8", $val['hardMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $insertRow, iconv("gb2312", "utf-8", $val['repairMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $insertRow, iconv("gb2312", "utf-8", $val['serviceMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $insertRow, iconv("gb2312", "utf-8", $val['equRentalMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $insertRow, iconv("gb2312", "utf-8", $val['spaceRentalMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $insertRow, iconv("gb2312", "utf-8", $val['otherMoney']));

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24, $insertRow, iconv("gb2312", "utf-8", $val['dsEnergyCharge']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, $insertRow, iconv("gb2312", "utf-8", $val['dsWaterRateMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26, $insertRow, iconv("gb2312", "utf-8", $val['houseRentalFee']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(27, $insertRow, iconv("gb2312", "utf-8", $val['installationCost']));

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(28, $insertRow, iconv("gb2312", "utf-8", $val['invoiceMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(29, $insertRow, iconv("gb2312", "utf-8", $val['invoiceTime']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(30, $insertRow, iconv("gb2312", "utf-8", $val['remark']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(31, $insertRow, iconv("gb2312", "utf-8", $val['createTime']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(32, $insertRow, iconv("gb2312", "utf-8", $val['areaName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(33, $insertRow, iconv("gb2312", "utf-8", $val['businessBelongName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(34, $insertRow, iconv("gb2312", "utf-8", $val['contractNatureName']));
        }
        //到浏览器
        ob_end_clean();//解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "开票汇总表.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * 发票导出
     */
    public static function exportInvoiceWithExcel07($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/financeInvoice.xlsx"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel2007 ($objPHPExcel);

        foreach ($rowdatas as $key => $val) {
            $insertRow = 3 + $key;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $insertRow, iconv("gb2312", "utf-8", $val['thisAreaName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $insertRow, iconv("gb2312", "utf-8", $val['prinvipalName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $insertRow, iconv("gb2312", "utf-8", $val['areaPrincipal']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $insertRow, iconv("gb2312", "utf-8", $val['invoiceTime']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $insertRow, iconv("gb2312", "utf-8", $val['invoiceUnitName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $insertRow, iconv("gb2312", "utf-8", $val['invoiceUnitProvince']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $insertRow, iconv("gb2312", "utf-8", $val['invoiceUnitTypeName']));
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(7, $insertRow, iconv("gb2312", "utf-8", $val['invoiceNo']), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $insertRow, iconv("gb2312", "utf-8", $val['invoiceTypeName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $insertRow, iconv("gb2312", "utf-8", $val['orderCode']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $insertRow, iconv("gb2312", "utf-8", $val['objTypeCN']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $insertRow, iconv("gb2312", "utf-8", $val['signSubjectName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $insertRow, iconv("gb2312", "utf-8", $val['sign']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $insertRow, iconv("gb2312", "utf-8", ''));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $insertRow, iconv("gb2312", "utf-8", $val['amount']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $insertRow, iconv("gb2312", "utf-8", $val['productName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $insertRow, iconv("gb2312", "utf-8", $val['psType']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $insertRow, iconv("gb2312", "utf-8", $val['softMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $insertRow, iconv("gb2312", "utf-8", $val['hardMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $insertRow, iconv("gb2312", "utf-8", $val['repairMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $insertRow, iconv("gb2312", "utf-8", $val['serviceMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $insertRow, iconv("gb2312", "utf-8", $val['equRentalMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $insertRow, iconv("gb2312", "utf-8", $val['spaceRentalMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $insertRow, iconv("gb2312", "utf-8", $val['otherMoney']));

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24, $insertRow, iconv("gb2312", "utf-8", $val['dsEnergyCharge']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, $insertRow, iconv("gb2312", "utf-8", $val['dsWaterRateMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26, $insertRow, iconv("gb2312", "utf-8", $val['houseRentalFee']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(27, $insertRow, iconv("gb2312", "utf-8", $val['installationCost']));

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(28, $insertRow, iconv("gb2312", "utf-8", $val['invoiceMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(29, $insertRow, iconv("gb2312", "utf-8", $val['invoiceTime']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(30, $insertRow, iconv("gb2312", "utf-8", $val['remark']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(31, $insertRow, iconv("gb2312", "utf-8", $val['createTime']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(32, $insertRow, iconv("gb2312", "utf-8", $val['areaName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(33, $insertRow, iconv("gb2312", "utf-8", $val['businessBelongName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(34, $insertRow, iconv("gb2312", "utf-8", $val['contractNatureName']));
        }
        //到浏览器
        ob_end_clean();//解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "开票汇总表.xlsx" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * 导出到款记录
     */
    public static function exportIncome($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/到款导出表.xls"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel5 ($objPHPExcel);

        foreach ($rowdatas as $key => $val) {
            $insertRow = 2 + $key;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $insertRow, iconv("gb2312", "utf-8", $val['incomeDate']));
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(1, $insertRow, iconv("gb2312", "utf-8", $val['incomeUnitName']), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $insertRow, iconv("gb2312", "utf-8", $val['objCode']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $insertRow, iconv("gb2312", "utf-8", $val['contractUnitName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $insertRow, iconv("gb2312", "utf-8", $val['areaName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $insertRow, iconv("gb2312", "utf-8", $val['province']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $insertRow, iconv("gb2312", "utf-8", $val['incomeMoney']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $insertRow, iconv("gb2312", "utf-8", $val['statusCN']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $insertRow, iconv("gb2312", "utf-8", $val['createName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $insertRow, iconv("gb2312", "utf-8", $val['businessBelongName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $insertRow, iconv("gb2312", "utf-8", $val['remark']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $insertRow, iconv("gb2312", "utf-8", $val['isAdjust'] ? '是' : '否'));
        }

        //到浏览器
        ob_end_clean();//解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "到款导出.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');

    }

    /**
     *  付款明细导出 07
     */
    public static function exportPayApplyDetail07($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/付款申请.xlsx"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel2007 ($objPHPExcel);

        //		echo "<pre>";
        //		print_r($rowdatas);
        foreach ($rowdatas as $key => $val) {
            $insertRow = 2 + $key;
            if (!empty($val['formNo'])) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $insertRow, $val['id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $insertRow, $val['formNo']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $insertRow, $val['payDate']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $insertRow, $val['actPayDate']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $insertRow, $val['formDate']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $insertRow, iconv("gb2312", "utf-8", $val['sourceTypeCN']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $insertRow, iconv("gb2312", "utf-8", $val['payForCN']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $insertRow, mb_convert_encoding($val['supplierName'], "utf-8", "gbk,big5"));
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(8, $insertRow, iconv("gb2312", "utf-8", $val['bank']), PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(9, $insertRow, iconv("gb2312", "utf-8", $val['account']), PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $insertRow, $val['payMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $insertRow, $val['payedMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $insertRow, $val['pchMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $insertRow, iconv("gb2312", "utf-8", $val['statusCN']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $insertRow, iconv("gb2312", "utf-8", $val['ExaStatus']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $insertRow, $val['ExaDT']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $insertRow, iconv("gb2312", "utf-8", $val['ExaUser']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $insertRow, iconv("gb2312", "utf-8", $val['ExaContent']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $insertRow, iconv("gb2312", "utf-8", $val['deptName']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $insertRow, iconv("gb2312", "utf-8", $val['salesman']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $insertRow, iconv("gb2312", "utf-8", $val['feeDeptName']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $insertRow, iconv("gb2312", "utf-8", $val['remark']));
            } else {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $insertRow, iconv("gb2312", "utf-8", '合计'));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $insertRow, $val['payMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $insertRow, $val['payedMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $insertRow, $val['pchMoney']);
            }
        }
        //到浏览器
        //        ob_end_clean();//解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "付款明细" . day_date . ".xlsx" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * 付款明细导出
     */
    public static function exportPayApplyDetail($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/付款明细.xls"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel5 ($objPHPExcel);

        //		echo "<pre>";
        //		print_r($rowdatas);
        foreach ($rowdatas as $key => $val) {
            $insertRow = 2 + $key;
            if (!empty($val['formNo'])) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $insertRow, $val['id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $insertRow, $val['formNo']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $insertRow, $val['payDate']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $insertRow, $val['actPayDate']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $insertRow, $val['formDate']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $insertRow, iconv("gb2312", "utf-8", $val['sourceTypeCN']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $insertRow, iconv("gb2312", "utf-8", $val['payForCN']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $insertRow, mb_convert_encoding($val['supplierName'], "utf-8", "gbk,big5"));
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(8, $insertRow, iconv("gb2312", "utf-8", $val['bank']), PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(9, $insertRow, iconv("gb2312", "utf-8", $val['account']), PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $insertRow, $val['payMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $insertRow, $val['payedMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $insertRow, $val['pchMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $insertRow, iconv("gb2312", "utf-8", $val['statusCN']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $insertRow, iconv("gb2312", "utf-8", $val['ExaStatus']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $insertRow, $val['ExaDT']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $insertRow, iconv("gb2312", "utf-8", $val['ExaUser']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $insertRow, iconv("gb2312", "utf-8", $val['ExaContent']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $insertRow, iconv("gb2312", "utf-8", $val['deptName']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $insertRow, iconv("gb2312", "utf-8", $val['salesman']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $insertRow, iconv("gb2312", "utf-8", $val['feeDeptName']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $insertRow, iconv("gb2312", "utf-8", $val['remark']));
            } else {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $insertRow, iconv("gb2312", "utf-8", '合计'));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $insertRow, $val['payMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $insertRow, $val['payedMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $insertRow, $val['pchMoney']);
            }
        }
        //到浏览器
        //        ob_end_clean();//解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "付款明细" . day_date . ".xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * 付款申请导出
     */
    public static function exportPayablesapply($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/付款申请.xls"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel5 ($objPHPExcel);

        foreach ($rowdatas as $key => $val) {
            $insertRow = 2 + $key;

            if ($val['id'] == '合计') {
                $val['remark'] = '合计:';
            } else {
                // payMoney用于显示外币金额,payMoneyCur用于显示人民币金额
                if (empty($val['payMoney'])) {
                    $val['payMoney'] = '0';
                } elseif ($val['currency'] == '人民币') {
                    $val['payMoney'] = '--';
                }
                if (empty($val['payMoneyCur'])) {
                    $val['payMoneyCur'] = '0';
                } elseif ($val['currency'] != '人民币') {
                    $val['payMoneyCur'] = '--';
                }
            }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $insertRow, iconv("gb2312", "utf-8", $val['deptName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $insertRow, iconv("gb2312", "utf-8", $val['salesman']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $insertRow, iconv("gb2312", "utf-8", $val['supplierName']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $insertRow, iconv("gb2312", "utf-8", $val['remark']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $insertRow, $val['pchMoney']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $insertRow, $val['payMoney']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $insertRow, $val['payMoneyCur']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $insertRow, iconv("gb2312", "utf-8", $val['currency']));
        }
        //到浏览器
        ob_end_clean();//解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "付款申请" . day_date . ".xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * 费用分摊明细导出
     * @param $thArr 标题
     * @param $data 数据
     * @param $countArr array 需要统计的字段
     * @throws Exception
     */
    public static function exportCostShare($thArr, $data, $countArr = array())
    {
        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;

        $objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //读取模板
        //Excel2003及以前的格式
        $objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

        //设置表头及样式 设置
        $headColumn = 0;
        foreach ($thArr as $v) {
            self::tableTrow($headColumn, 1, $v, $objPhpExcelFile);
            $headColumn++;
        }

        $i = 2;
        if (!empty($data)) {
            $row = $i;

            $doCount = !empty($countArr); // 进行统计
            $countRow = array(); // 合计列
            $countMap = array(); // 合计映射
            if ($doCount) {
                foreach ($countArr as $v) {
                    $countRow[$v] = 0;
                }
            }

            $key = 0;

            foreach ($data as $key => $val) {
                //归属期间格式化为（yyyy.m）
                $y = $m = $format_Date = '';
                $date = explode('-', $val['belongPeriod']);
                $date = explode('.', $date[0]);
                if (strlen($date[0]) > 4) {//可能为年和月连在一起
                    $y = substr($date[0], 0, 4);
                    if (substr($date[0], 4, 1) == '0') {
                        $m = substr($date[0], 5, 1);
                    } else {
                        $m = substr($date[0], 4, 2);
                    }
                    $format_Date = $y . '.' . $m;
                } else if (strlen($date[0]) == 4) {
                    $y = $date[0];
                    if (substr($date[1], 0, 1) == '0') {
                        $m = substr($date[1], 1, 1);
                    } else {
                        $m = $date[1];
                    }
                    $format_Date = $y . '.' . $m;
                } else {
                    $format_Date = $val['belongPeriod'];
                }
                $val['belongPeriod'] = $format_Date;//已验证以下情况: {20169 , 201609 , 2016.09 , 201609-201 , 2016.9-201 , 2016.09-20 , 201612-201 , 20160903 , 20161203}

                //审核
                switch ($val['auditStatus']) {
                    case '1':
                        $val['auditStatus'] = '已审核';
                        break;
                    case '2':
                        $val['auditStatus'] = '未审核';
                        break;
                    case '3':
                        $val['auditStatus'] = '撤回';
                        break;
                    default:
                }
                //源单类型
                switch ($val['objType']) {
                    case '1':
                        $val['objType'] = '赔偿单';
                        break;
                    case '2':
                        $val['objType'] = '其他合同';
                        break;
                    default:
                }
                //业务类型
                switch ($val['detailType']) {
                    case '1':
                        $val['detailType'] = '部门费用';
                        break;
                    case '2':
                        $val['detailType'] = '合同项目费用';
                        break;
                    case '3':
                        $val['detailType'] = '研发项目费用';
                        break;
                    case '4':
                        $val['detailType'] = '售前费用';
                        break;
                    case '5':
                        $val['detailType'] = '售后费用';
                        break;
                    default:
                }
                //勾稽状态
                switch ($val['hookStatus']) {
                    case '0':
                        $val['hookStatus'] = '未勾稽';
                        break;
                    case '1':
                        $val['hookStatus'] = '已勾稽';
                        break;
                    case '2':
                        $val['hookStatus'] = '部分勾稽';
                        break;
                    default:
                }
                //项目名称存在【-】，则取前面部分，否则取整个项目名称
                if (strstr($val['projectName'], '-')) {
                    $arr = explode('-', $val['projectName']);
                    $val['projectName'] = $arr[0];
                }

                $m = 0;
                foreach ($thArr as $field => $value) {
                    $tempValue = $val[$field];

                    if ($tempValue == 'undefined') {
                        $tempValue = '';
                    }

                    // 合计处理
                    if ($doCount && isset($countRow[$field])) {
                        $countRow[$field] = bcadd($countRow[$field], $tempValue, 2);

                        // 定位
                        if (!isset($countMap[$field])) {
                            $countMap[$field] = $m;
                        }
                    }

                    if (in_array($field, $countArr)) {
                        //解决显示为科学计数法的问题
                        $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key, $tempValue,
                            PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    } else {
                        //解决显示为科学计数法的问题
                        $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                            iconv("gb2312", "utf-8", $tempValue), PHPExcel_Cell_DataType::TYPE_STRING);
                    }
                    $m++;
                }
            }

            if ($doCount) {
                foreach ($countRow as $k => $v) {
                    //解决显示为科学计数法的问题
                    $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($countMap[$k], $row + $key + 1, $v,
                        PHPExcel_Cell_DataType::TYPE_NUMERIC);
                }
            }
        }
        //到浏览器
        ob_end_clean(); //解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "费用分摊明细.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /********************************  通用导出部分 **********************************/
    /**
     * 模块列样式
     * @param $column
     * @param $row
     * @param $content
     * @param $objPHPExcel
     */
    public static function tableTrow($column, $row, $content, $objPHPExcel)
    {
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
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
    }

    /**
     * excel生成部分
     * @param $thArr
     * @param $data
     * @param string $fileName
     * @param array $perArr 使用百分比的列
     * @param array $dateArr 使用日期的列
     * @throws Exception
     */
    public static function export2ExcelUtil($thArr, $data, $fileName = '列表信息', $perArr = array(), $dateArr = array())
    {
        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

        //设置表头及样式 设置
        $headColumn = 0;
        foreach ($thArr as $v) {
            self::tableTrow($headColumn, 1, $v, $objPhpExcelFile);
            $headColumn++;
        }

        $i = 2;
        if (!empty($data)) {
            $row = $i;
            foreach ($data as $key => $val) {
                $m = 0;
                foreach ($thArr as $field => $value) {
                    // 排除非法日期格式
                    if ($val[$field] != '0000-00-00') {
                        // 如果是属于需要做百分号处理的列，那么进入百分比处理
                        if (in_array($field, $perArr)) {
                            //解决显示为科学计数法的问题
                            $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                            $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                bcdiv($val[$field], 100, 9), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        } elseif(in_array($field, $dateArr)){// 如果是属于需要做日期处理的列，那么进入日期格式处理
                            $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $key,
                                $val[$field]);
                        }elseif (is_numeric($val[$field])) {
                            //解决显示为科学计数法的问题
                            $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                $val[$field], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        } else {
                            $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $key,
                                iconv("gb2312", "utf-8", $val[$field]));
                        }
                    }
                    $m++;
                }
            }
        }
        //到浏览器
        ob_end_clean(); //解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $fileName . ".xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * excel生成部分
     * @param $thArr
     * @param $data
     * @param string $fileName
     * @param array $formatArr
     * @throws Exception
     */
    public static function export2ExcelUtilNew($thArr, $data, $fileName = '列表信息', $formatArr = array())
    {
        $ThCode = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

        //设置表头及样式 设置
        $headColumn = 0;
        foreach ($thArr as $v) {
            self::tableTrow($headColumn, 1, $v, $objPhpExcelFile);
            $headColumn++;
        }

        $i = 2;
        if (!empty($data)) {
            $row = $i;
            foreach ($data as $key => $val) {
                $m = 0;
                foreach ($thArr as $field => $value) {
                    $objPhpExcelFile->getActiveSheet()->getColumnDimension($ThCode[$m])->setWidth(20);
                    // 排除非法日期格式
                    if ($val[$field] != '0000-00-00') {
                        // 如果是属于需要做百分号处理的列，那么进入百分比处理
                        if (isset($formatArr['perArr']) && in_array($field, $formatArr['perArr'])) {
                            //解决显示为科学计数法的问题
                            $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                            $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                bcdiv($val[$field], 100, 9), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        } elseif(isset($formatArr['dateArr']) && in_array($field, $formatArr['dateArr'])){// 如果是属于需要做日期处理的列，那么进入日期格式处理
                            $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $key,
                                $val[$field]);
                        } elseif(isset($formatArr['feeArr']) && in_array($field, $formatArr['feeArr'])){
                            $valueStr = (empty($val[$field]))? 0.00 : $val[$field];
                            $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                $valueStr, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        }elseif(isset($formatArr['txtArr']) && in_array($field, $formatArr['txtArr'])){
                            $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                            $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $key,
                                iconv("gb2312", "utf-8", $val[$field]));
                        }elseif (is_numeric($val[$field])) {
                            //解决显示为科学计数法的问题
                            $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                $val[$field], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        }else {
                            $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $key,
                                iconv("gb2312", "utf-8", $val[$field]));
                        }
                    }
                    $m++;
                }
            }
        }
        //到浏览器
        ob_end_clean(); //解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $fileName . ".xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * excel生成部分
     * @param $thArr
     * @param $data
     * @param string $fileName
     * @param array $perArr 使用百分比的列
     * @throws Exception
     */
    public static function export07ExcelUtil($thArr, $data, $fileName = '列表信息', $perArr = array())
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xlsx"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel2007 ($objPhpExcelFile);

        //设置表头及样式 设置
        $headColumn = 0;
        foreach ($thArr as $v) {
            self::tableTrow($headColumn, 1, $v, $objPhpExcelFile);
            $headColumn++;
        }

        $i = 2;
        if (!empty($data)) {
            $row = $i;
            foreach ($data as $key => $val) {
                $m = 0;
                foreach ($thArr as $field => $value) {
                    // 排除非法日期格式
                    if ($val[$field] != '0000-00-00') {
                        // 如果是属于需要做百分号处理的列，那么进入百分比处理
                        if (in_array($field, $perArr)) {
                            //解决显示为科学计数法的问题
                            $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                            $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                bcdiv($val[$field], 100, 9), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        } elseif (is_numeric($val[$field])) {
                            //解决显示为科学计数法的问题
                            $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                $val[$field], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        } else {
                            $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $key,
                                iconv("gb2312", "utf-8", $val[$field]));
                        }
                    }
                    $m++;
                }
            }
        }
        
        //到浏览器
        ob_end_clean();//解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "{$fileName}.xlsx" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * excel生成部分
     * 此方法用于多个sheet生成的情况
     * @param $sheets
     * @param $heads
     * @param $data
     * @param string $fileName
     * @param array $perArr 使用百分比的列
     * @throws Exception
     */
    public static function exportExcelMulUtil($sheets, $heads, $data, $fileName = '列表信息', $perArr = array())
    {
        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

        // 循环构建excel
        foreach ($sheets as $k => $sheet) {
            // 设置当前活动的sheet
            if ($k) {
                $objPhpExcelFile->createSheet();
                $objPhpExcelFile->setActiveSheetIndex($k);
            }
            $sheetObj = $objPhpExcelFile->getActiveSheet();
            $sheetObj->setTitle(iconv("gb2312", "utf-8", $sheet));

            //设置表头及样式 设置
            $headColumn = 0;
            foreach ($heads[$k] as $head) {
                self::tableTrow($headColumn, 1, $head, $objPhpExcelFile);
                $headColumn++;
            }

            if (!empty($data[$k])) {
                $row = 2;
                foreach ($data[$k] as $key => $val) {
                    $m = 0;
                    foreach ($heads[$k] as $field => $value) {
                        // 排除非法日期格式
                        if ($val[$field] != '0000-00-00') {
                            // 如果是属于需要做百分号处理的列，那么进入百分比处理
                            if (in_array($field, $perArr)) {
                                //解决显示为科学计数法的问题
                                $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                                $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                    bcdiv($val[$field], 100, 9), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            } elseif (is_numeric($val[$field])) {
                                //解决显示为科学计数法的问题
                                $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                    $val[$field], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            } else {
                                $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $key,
                                    iconv("gb2312", "utf-8", $val[$field]));
                            }
                        }
                        $m++;
                    }
                }
            }
        }
        //到浏览器
        ob_end_clean();//解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "{$fileName}.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * excel生成部分
     * 此方法用于多个sheet生成的情况
     * @param $sheets
     * @param $heads
     * @param $data
     * @param string $fileName
     * @param array $perArr 使用百分比的列
     * @throws Exception
     */
    public static function export07ExcelMulUtil($sheets, $heads, $data, $fileName = '列表信息', $perArr = array())
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xlsx"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel2007 ($objPhpExcelFile);

        // 循环构建excel
        foreach ($sheets as $k => $sheet) {
            // 设置当前活动的sheet
            if ($k) {
                $objPhpExcelFile->createSheet();
                $objPhpExcelFile->setActiveSheetIndex($k);
            }
            $sheetObj = $objPhpExcelFile->getActiveSheet();
            $sheetObj->setTitle(iconv("gb2312", "utf-8", $sheet));

            //设置表头及样式 设置
            $headColumn = 0;
            foreach ($heads[$k] as $head) {
                self::tableTrow($headColumn, 1, $head, $objPhpExcelFile);
                $headColumn++;
            }

            if (!empty($data[$k])) {
                $row = 2;
                foreach ($data[$k] as $key => $val) {
                    $m = 0;
                    foreach ($heads[$k] as $field => $value) {
                        // 排除非法日期格式
                        if ($val[$field] != '0000-00-00') {
                            // 如果是属于需要做百分号处理的列，那么进入百分比处理
                            if (in_array($field, $perArr)) {
                                //解决显示为科学计数法的问题
                                $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                                $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                    bcdiv($val[$field], 100, 9), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            } elseif (is_numeric($val[$field])) {
                                //解决显示为科学计数法的问题
                                $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                    $val[$field], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            } else {
                                $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $key,
                                    iconv("gb2312", "utf-8", $val[$field]));
                            }
                        }
                        $m++;
                    }
                }
            }
        }
        //到浏览器
        ob_end_clean();//解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "{$fileName}.xlsx" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * 销售费用统计数据分块导出
     * @param $thArr
     * @param $sheetData
     * @param $sheetName
     * @param string $fileName
     * @param array $perArr
     * @param array $feeArr
     */
    public static function export2ExcelUtilForSummary($thArr, $sheetData, $sheetName, $fileName = '销售费用统计', $perArr = array(), $feeArr = array())
    {
        $ThCode = array("A","B","C","D","E","F","G","H","I","J");
        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //读取模板
        $objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

        // 循环构建excel
        $sheetIndex = 0;
        foreach ($thArr as $k => $sheetTh) {
            // 设置当前活动的sheet
            if($sheetIndex > 0){
                $objPhpExcelFile->createSheet();
            }
            $objPhpExcelFile->setActiveSheetIndex($sheetIndex);

            $sheetObj = $objPhpExcelFile->getActiveSheet();
            $sheetObj->setTitle(iconv("gb2312", "utf-8", $sheetName[$k]));

            //设置表头及样式 设置
            $headColumn = 0;
            foreach ($sheetTh as $thk => $head) {
                self::tableTrow($headColumn, 1, $head, $objPhpExcelFile);
                $headColumn++;
            }

            $data = $sheetData[$k];
            $lastRowNum = count($data)+1;
            $catch = false;
            $mergeRowStr = "A".$lastRowNum.":";
            if (!empty($data)) {
                $row = 2;
                foreach ($data as $key => $val) {
                    $m = 0;
                    foreach ($sheetTh as $field => $value) {
                        if($m > 0){
                            $objPhpExcelFile->getActiveSheet()->getColumnDimension($ThCode[$m])->setWidth(20);
                        }else{
                            $objPhpExcelFile->getActiveSheet()->getColumnDimension($ThCode[$m])->setWidth(17);
                        }

                        // 排除非法日期格式
                        if ($val[$field] != '0000-00-00') {
                            // 如果是属于需要做百分号处理的列，那么进入百分比处理
                            if (in_array($field, $perArr)) {
                                //解决显示为科学计数法的问题
                                $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                                $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                    bcdiv($val[$field], 100, 9), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            }elseif(in_array($field, $feeArr)){
                                if(!$catch){
                                    $catch = true;
                                    $mergeRowStr .= $ThCode[$m-1].$lastRowNum;
                                }
                                $valueStr = (empty($val[$field]))? 0.00 : $val[$field];
                                $objPhpExcelFile->getActiveSheet()->getColumnDimension($ThCode[$m])->setWidth(17);
                                $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                                $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                    $valueStr, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            } elseif (is_numeric($val[$field])) {
                                //解决显示为科学计数法的问题
                                $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                    $val[$field], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            } else {
                                $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $key,
                                    iconv("gb2312", "utf-8", $val[$field]));
                            }
                        }
                        $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $lastRowNum)->getFont()->setBold(true);
                        $m++;
                    }
                }
            }

            // 合并合计项
            $objPhpExcelFile->getActiveSheet()->mergeCells($mergeRowStr);

            $sheetIndex += 1;
        }
        $objPhpExcelFile->setActiveSheetIndex(0);// 默认设回第一个表单
//        exit();

        //到浏览器
        ob_end_clean(); //解决输出到浏览器出现乱码的问题
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $fileName . ".xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
}