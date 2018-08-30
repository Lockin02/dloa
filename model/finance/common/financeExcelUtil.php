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

    //�ϴ�Excel����ȡ excel����
    public static function upReadExcelDataClear($file, $filetempname)
    {
        //�Լ����õ��ϴ��ļ����·��
        $filePath = UPLOADPATH . 'upfile/';
        if (!is_dir($filePath)) {
            mkdir($filePath);
        }
        $str = "";

        //�����·��������PHPExcel��·�����޸�
        $filename = explode(".", $file); //���ϴ����ļ����ԡ�.����Ϊ׼��һ�����顣
        $time = date("y-m-d-H-i-s"); //ȥ��ǰ�ϴ���ʱ��
        $filename [0] = $time; //ȡ�ļ����滻
        $name = implode(".", $filename); //�ϴ�����ļ���
        $uploadfile = $filePath . $name; //�ϴ�����ļ�����ַ
        $result = move_uploaded_file($filetempname, $uploadfile); //�����ϴ�����ǰĿ¼��
        if ($result) { //����ϴ��ļ��ɹ�����ִ�е���excel����
            $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
            $objPHPExcel = $objReader->load($uploadfile); //   $objPHPExcel = $objReader->load($uploadfile);
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // ȡ��������
            $highestColumn = $sheet->getHighestColumn(); // ȡ��������
            $dataArr = array(); //��ȡ���
            //ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
            for ($j = 2; $j <= $highestRow; $j++) {
                for ($k = 'A'; $k <= $highestColumn; $k++) {
                    $str .= iconv('utf-8', 'gbk', $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue()) . '|--|'; //��ȡ��Ԫ��
                }
                //explode:�������ַ����ָ�Ϊ���顣
                $strs = explode("|--|", $str);
                array_push($dataArr, $strs);
                $str = "";
            }
        }
        return $dataArr;
    }

    /**������Ʒ��������Ϣ
     * author show
     * 2012-02-25
     */
    public static function productInCalExcelOut($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/��Ʒ��⵼��.xls"); //��ȡģ��
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
        //�������
        ob_end_clean();//��������������������������
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "��Ʒ��⵼��.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**������Ʒ��������Ϣ
     * author show
     * 2012-02-25
     */
    public static function productInCalExcelOutDept($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/��Ʒ��ⰴ���ŵ���.xls"); //��ȡģ��
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
        //�������
        ob_end_clean();//��������������������������
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "��Ʒ��ⰴ���ŵ���.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }


    /**������Ʊ��ѯ
     *author show
     *2011-07-26
     */
    public static function exportInvoice($rowdatas)
    {
        PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/financeInvoice.xls"); //��ȡģ��
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
        //�������
        ob_end_clean();//��������������������������
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "��Ʊ���ܱ�.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * ��Ʊ����
     */
    public static function exportInvoiceWithExcel07($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/financeInvoice.xlsx"); //��ȡģ��
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
        //�������
        ob_end_clean();//��������������������������
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "��Ʊ���ܱ�.xlsx" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * ���������¼
     */
    public static function exportIncome($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/�������.xls"); //��ȡģ��
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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $insertRow, iconv("gb2312", "utf-8", $val['isAdjust'] ? '��' : '��'));
        }

        //�������
        ob_end_clean();//��������������������������
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "�����.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');

    }

    /**
     *  ������ϸ���� 07
     */
    public static function exportPayApplyDetail07($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/��������.xlsx"); //��ȡģ��
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
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $insertRow, iconv("gb2312", "utf-8", '�ϼ�'));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $insertRow, $val['payMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $insertRow, $val['payedMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $insertRow, $val['pchMoney']);
            }
        }
        //�������
        //        ob_end_clean();//��������������������������
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "������ϸ" . day_date . ".xlsx" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * ������ϸ����
     */
    public static function exportPayApplyDetail($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/������ϸ.xls"); //��ȡģ��
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
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $insertRow, iconv("gb2312", "utf-8", '�ϼ�'));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $insertRow, $val['payMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $insertRow, $val['payedMoney']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $insertRow, $val['pchMoney']);
            }
        }
        //�������
        //        ob_end_clean();//��������������������������
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "������ϸ" . day_date . ".xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * �������뵼��
     */
    public static function exportPayablesapply($rowdatas)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/��������.xls"); //��ȡģ��
        $objWriter = new PHPExcel_Writer_Excel5 ($objPHPExcel);

        foreach ($rowdatas as $key => $val) {
            $insertRow = 2 + $key;

            if ($val['id'] == '�ϼ�') {
                $val['remark'] = '�ϼ�:';
            } else {
                // payMoney������ʾ��ҽ��,payMoneyCur������ʾ����ҽ��
                if (empty($val['payMoney'])) {
                    $val['payMoney'] = '0';
                } elseif ($val['currency'] == '�����') {
                    $val['payMoney'] = '--';
                }
                if (empty($val['payMoneyCur'])) {
                    $val['payMoneyCur'] = '0';
                } elseif ($val['currency'] != '�����') {
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
        //�������
        ob_end_clean();//��������������������������
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "��������" . day_date . ".xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * ���÷�̯��ϸ����
     * @param $thArr ����
     * @param $data ����
     * @param $countArr array ��Ҫͳ�Ƶ��ֶ�
     * @throws Exception
     */
    public static function exportCostShare($thArr, $data, $countArr = array())
    {
        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;

        $objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //��ȡģ��
        //Excel2003����ǰ�ĸ�ʽ
        $objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

        //���ñ�ͷ����ʽ ����
        $headColumn = 0;
        foreach ($thArr as $v) {
            self::tableTrow($headColumn, 1, $v, $objPhpExcelFile);
            $headColumn++;
        }

        $i = 2;
        if (!empty($data)) {
            $row = $i;

            $doCount = !empty($countArr); // ����ͳ��
            $countRow = array(); // �ϼ���
            $countMap = array(); // �ϼ�ӳ��
            if ($doCount) {
                foreach ($countArr as $v) {
                    $countRow[$v] = 0;
                }
            }

            $key = 0;

            foreach ($data as $key => $val) {
                //�����ڼ��ʽ��Ϊ��yyyy.m��
                $y = $m = $format_Date = '';
                $date = explode('-', $val['belongPeriod']);
                $date = explode('.', $date[0]);
                if (strlen($date[0]) > 4) {//����Ϊ���������һ��
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
                $val['belongPeriod'] = $format_Date;//����֤�������: {20169 , 201609 , 2016.09 , 201609-201 , 2016.9-201 , 2016.09-20 , 201612-201 , 20160903 , 20161203}

                //���
                switch ($val['auditStatus']) {
                    case '1':
                        $val['auditStatus'] = '�����';
                        break;
                    case '2':
                        $val['auditStatus'] = 'δ���';
                        break;
                    case '3':
                        $val['auditStatus'] = '����';
                        break;
                    default:
                }
                //Դ������
                switch ($val['objType']) {
                    case '1':
                        $val['objType'] = '�⳥��';
                        break;
                    case '2':
                        $val['objType'] = '������ͬ';
                        break;
                    default:
                }
                //ҵ������
                switch ($val['detailType']) {
                    case '1':
                        $val['detailType'] = '���ŷ���';
                        break;
                    case '2':
                        $val['detailType'] = '��ͬ��Ŀ����';
                        break;
                    case '3':
                        $val['detailType'] = '�з���Ŀ����';
                        break;
                    case '4':
                        $val['detailType'] = '��ǰ����';
                        break;
                    case '5':
                        $val['detailType'] = '�ۺ����';
                        break;
                    default:
                }
                //����״̬
                switch ($val['hookStatus']) {
                    case '0':
                        $val['hookStatus'] = 'δ����';
                        break;
                    case '1':
                        $val['hookStatus'] = '�ѹ���';
                        break;
                    case '2':
                        $val['hookStatus'] = '���ֹ���';
                        break;
                    default:
                }
                //��Ŀ���ƴ��ڡ�-������ȡǰ�沿�֣�����ȡ������Ŀ����
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

                    // �ϼƴ���
                    if ($doCount && isset($countRow[$field])) {
                        $countRow[$field] = bcadd($countRow[$field], $tempValue, 2);

                        // ��λ
                        if (!isset($countMap[$field])) {
                            $countMap[$field] = $m;
                        }
                    }

                    if (in_array($field, $countArr)) {
                        //�����ʾΪ��ѧ������������
                        $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key, $tempValue,
                            PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    } else {
                        //�����ʾΪ��ѧ������������
                        $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                            iconv("gb2312", "utf-8", $tempValue), PHPExcel_Cell_DataType::TYPE_STRING);
                    }
                    $m++;
                }
            }

            if ($doCount) {
                foreach ($countRow as $k => $v) {
                    //�����ʾΪ��ѧ������������
                    $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($countMap[$k], $row + $key + 1, $v,
                        PHPExcel_Cell_DataType::TYPE_NUMERIC);
                }
            }
        }
        //�������
        ob_end_clean(); //��������������������������
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "���÷�̯��ϸ.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /********************************  ͨ�õ������� **********************************/
    /**
     * ģ������ʽ
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
        //��ʽ������Ϊ�ַ��������⵼������Ĭ���Ҷ��뵥Ԫ��
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
    }

    /**
     * excel���ɲ���
     * @param $thArr
     * @param $data
     * @param string $fileName
     * @param array $perArr ʹ�ðٷֱȵ���
     * @param array $dateArr ʹ�����ڵ���
     * @throws Exception
     */
    public static function export2ExcelUtil($thArr, $data, $fileName = '�б���Ϣ', $perArr = array(), $dateArr = array())
    {
        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //��ȡģ��
        $objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

        //���ñ�ͷ����ʽ ����
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
                    // �ų��Ƿ����ڸ�ʽ
                    if ($val[$field] != '0000-00-00') {
                        // �����������Ҫ���ٷֺŴ�����У���ô����ٷֱȴ���
                        if (in_array($field, $perArr)) {
                            //�����ʾΪ��ѧ������������
                            $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                            $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                bcdiv($val[$field], 100, 9), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        } elseif(in_array($field, $dateArr)){// �����������Ҫ�����ڴ�����У���ô�������ڸ�ʽ����
                            $objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $key,
                                $val[$field]);
                        }elseif (is_numeric($val[$field])) {
                            //�����ʾΪ��ѧ������������
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
        //�������
        ob_end_clean(); //��������������������������
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
     * excel���ɲ���
     * @param $thArr
     * @param $data
     * @param string $fileName
     * @param array $formatArr
     * @throws Exception
     */
    public static function export2ExcelUtilNew($thArr, $data, $fileName = '�б���Ϣ', $formatArr = array())
    {
        $ThCode = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //��ȡģ��
        $objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

        //���ñ�ͷ����ʽ ����
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
                    // �ų��Ƿ����ڸ�ʽ
                    if ($val[$field] != '0000-00-00') {
                        // �����������Ҫ���ٷֺŴ�����У���ô����ٷֱȴ���
                        if (isset($formatArr['perArr']) && in_array($field, $formatArr['perArr'])) {
                            //�����ʾΪ��ѧ������������
                            $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                            $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                bcdiv($val[$field], 100, 9), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        } elseif(isset($formatArr['dateArr']) && in_array($field, $formatArr['dateArr'])){// �����������Ҫ�����ڴ�����У���ô�������ڸ�ʽ����
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
                            //�����ʾΪ��ѧ������������
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
        //�������
        ob_end_clean(); //��������������������������
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
     * excel���ɲ���
     * @param $thArr
     * @param $data
     * @param string $fileName
     * @param array $perArr ʹ�ðٷֱȵ���
     * @throws Exception
     */
    public static function export07ExcelUtil($thArr, $data, $fileName = '�б���Ϣ', $perArr = array())
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xlsx"); //��ȡģ��
        $objWriter = new PHPExcel_Writer_Excel2007 ($objPhpExcelFile);

        //���ñ�ͷ����ʽ ����
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
                    // �ų��Ƿ����ڸ�ʽ
                    if ($val[$field] != '0000-00-00') {
                        // �����������Ҫ���ٷֺŴ�����У���ô����ٷֱȴ���
                        if (in_array($field, $perArr)) {
                            //�����ʾΪ��ѧ������������
                            $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                            $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                bcdiv($val[$field], 100, 9), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        } elseif (is_numeric($val[$field])) {
                            //�����ʾΪ��ѧ������������
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
        
        //�������
        ob_end_clean();//��������������������������
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
     * excel���ɲ���
     * �˷������ڶ��sheet���ɵ����
     * @param $sheets
     * @param $heads
     * @param $data
     * @param string $fileName
     * @param array $perArr ʹ�ðٷֱȵ���
     * @throws Exception
     */
    public static function exportExcelMulUtil($sheets, $heads, $data, $fileName = '�б���Ϣ', $perArr = array())
    {
        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //��ȡģ��
        $objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

        // ѭ������excel
        foreach ($sheets as $k => $sheet) {
            // ���õ�ǰ���sheet
            if ($k) {
                $objPhpExcelFile->createSheet();
                $objPhpExcelFile->setActiveSheetIndex($k);
            }
            $sheetObj = $objPhpExcelFile->getActiveSheet();
            $sheetObj->setTitle(iconv("gb2312", "utf-8", $sheet));

            //���ñ�ͷ����ʽ ����
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
                        // �ų��Ƿ����ڸ�ʽ
                        if ($val[$field] != '0000-00-00') {
                            // �����������Ҫ���ٷֺŴ�����У���ô����ٷֱȴ���
                            if (in_array($field, $perArr)) {
                                //�����ʾΪ��ѧ������������
                                $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                                $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                    bcdiv($val[$field], 100, 9), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            } elseif (is_numeric($val[$field])) {
                                //�����ʾΪ��ѧ������������
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
        //�������
        ob_end_clean();//��������������������������
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
     * excel���ɲ���
     * �˷������ڶ��sheet���ɵ����
     * @param $sheets
     * @param $heads
     * @param $data
     * @param string $fileName
     * @param array $perArr ʹ�ðٷֱȵ���
     * @throws Exception
     */
    public static function export07ExcelMulUtil($sheets, $heads, $data, $fileName = '�б���Ϣ', $perArr = array())
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xlsx"); //��ȡģ��
        $objWriter = new PHPExcel_Writer_Excel2007 ($objPhpExcelFile);

        // ѭ������excel
        foreach ($sheets as $k => $sheet) {
            // ���õ�ǰ���sheet
            if ($k) {
                $objPhpExcelFile->createSheet();
                $objPhpExcelFile->setActiveSheetIndex($k);
            }
            $sheetObj = $objPhpExcelFile->getActiveSheet();
            $sheetObj->setTitle(iconv("gb2312", "utf-8", $sheet));

            //���ñ�ͷ����ʽ ����
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
                        // �ų��Ƿ����ڸ�ʽ
                        if ($val[$field] != '0000-00-00') {
                            // �����������Ҫ���ٷֺŴ�����У���ô����ٷֱȴ���
                            if (in_array($field, $perArr)) {
                                //�����ʾΪ��ѧ������������
                                $objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $row + $key)->getNumberFormat()
                                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                                $objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $key,
                                    bcdiv($val[$field], 100, 9), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            } elseif (is_numeric($val[$field])) {
                                //�����ʾΪ��ѧ������������
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
        //�������
        ob_end_clean();//��������������������������
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
     * ���۷���ͳ�����ݷֿ鵼��
     * @param $thArr
     * @param $sheetData
     * @param $sheetName
     * @param string $fileName
     * @param array $perArr
     * @param array $feeArr
     */
    public static function export2ExcelUtilForSummary($thArr, $sheetData, $sheetName, $fileName = '���۷���ͳ��', $perArr = array(), $feeArr = array())
    {
        $ThCode = array("A","B","C","D","E","F","G","H","I","J");
        PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load("upfile/orderReportList07.xls"); //��ȡģ��
        $objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

        // ѭ������excel
        $sheetIndex = 0;
        foreach ($thArr as $k => $sheetTh) {
            // ���õ�ǰ���sheet
            if($sheetIndex > 0){
                $objPhpExcelFile->createSheet();
            }
            $objPhpExcelFile->setActiveSheetIndex($sheetIndex);

            $sheetObj = $objPhpExcelFile->getActiveSheet();
            $sheetObj->setTitle(iconv("gb2312", "utf-8", $sheetName[$k]));

            //���ñ�ͷ����ʽ ����
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

                        // �ų��Ƿ����ڸ�ʽ
                        if ($val[$field] != '0000-00-00') {
                            // �����������Ҫ���ٷֺŴ�����У���ô����ٷֱȴ���
                            if (in_array($field, $perArr)) {
                                //�����ʾΪ��ѧ������������
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
                                //�����ʾΪ��ѧ������������
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

            // �ϲ��ϼ���
            $objPhpExcelFile->getActiveSheet()->mergeCells($mergeRowStr);

            $sheetIndex += 1;
        }
        $objPhpExcelFile->setActiveSheetIndex(0);// Ĭ����ص�һ����
//        exit();

        //�������
        ob_end_clean(); //��������������������������
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