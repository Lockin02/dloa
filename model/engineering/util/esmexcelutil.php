<?php
/**
 * @description: ���̹���
 * @date 2012-9-10
 */
include WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";
include WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";

class model_engineering_util_esmexcelutil extends model_base
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
	 * ��Ŀ������Ϣ
	 * @param $dataArr
	 * @throws Exception
	 */
	public static function exportProject($dataArr) {
		set_time_limit(0);
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		$objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/������Ŀ����ģ��.xls"); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet();
		foreach ($dataArr as $k => $v) {
			$n = $k + 2;
			$excelActiveSheet->setCellValueByColumnAndRow(0, $n, iconv("GBK", "utf-8", $v['newProLineName']));
			$excelActiveSheet->setCellValueByColumnAndRow(1, $n, iconv("GBK", "utf-8", $v['officeName']));
			$excelActiveSheet->setCellValueByColumnAndRow(2, $n, iconv("GBK", "utf-8", $v['projectName']));
			$excelActiveSheet->setCellValueByColumnAndRow(3, $n, $v['projectCode']);
			$excelActiveSheet->setCellValueByColumnAndRow(4, $n, iconv("GBK", "utf-8", $v['statusName']));
			$excelActiveSheet->setCellValueByColumnAndRow(5, $n, $v['budgetAll']);
			$excelActiveSheet->setCellValueByColumnAndRow(6, $n, $v['budgetField']);
			$excelActiveSheet->setCellValueByColumnAndRow(7, $n, $v['budgetPerson']);
			$excelActiveSheet->setCellValueByColumnAndRow(8, $n, $v['budgetEqu']);
			$excelActiveSheet->setCellValueByColumnAndRow(9, $n, $v['budgetOutsourcing']);
			$excelActiveSheet->setCellValueByColumnAndRow(10, $n, bcadd($v['budgetOther'], empty($v['budgetPK']) ? 0 : $v['budgetPK'], 2));
			$excelActiveSheet->setCellValueByColumnAndRow(11, $n, $v['feeAll']);

            $feeFieldCount = bcadd($v['feeField'], $v['feeFieldImport'], 2);
            $feeFieldCount = bcadd($feeFieldCount, $v['feeFlights'], 2);
            $feeFieldCount = bcadd($feeFieldCount, $v['feePayables'], 2);
            $feeFieldCount = bcadd($feeFieldCount, $v['feeCar'], 2);
            $feeFieldCount = bcadd($feeFieldCount, $v['feeCostbx'], 2);

			$excelActiveSheet->setCellValueByColumnAndRow(12, $n, $feeFieldCount);
			$excelActiveSheet->setCellValueByColumnAndRow(13, $n, bcadd($v['feeField'], $v['feeFlights'], 2));
			$excelActiveSheet->setCellValueByColumnAndRow(14, $n, bcadd(bcadd($v['feePayables'], $v['feeCar'], 2), $v['feeCostbx'], 2));
            $excelActiveSheet->setCellValueByColumnAndRow(15, $n, $v['feeFieldImport']);

            $feePersonCount = bcadd($v['feePerson'], $v['feeSubsidy'], 2);
            $feePersonCount = bcadd($feePersonCount, $v['feeSubsidyImport'], 2);
            $excelActiveSheet->setCellValueByColumnAndRow(16, $n, $feePersonCount);
			$excelActiveSheet->setCellValueByColumnAndRow(17, $n, $v['feePerson']);
			$excelActiveSheet->setCellValueByColumnAndRow(18, $n, $v['feeSubsidy']);
			$excelActiveSheet->setCellValueByColumnAndRow(19, $n, $v['feeSubsidyImport']);
			$excelActiveSheet->setCellValueByColumnAndRow(20, $n, bcadd($v['feeEqu'], $v['feeEquImport'], 2));
			$excelActiveSheet->setCellValueByColumnAndRow(21, $n, $v['feeOutsourcing']);
			$excelActiveSheet->setCellValueByColumnAndRow(22, $n, bcadd($v['feeOther'], empty($v['feePK']) ? 0 : $v['feePK'], 2));
            $excelActiveSheet->setCellValueByColumnAndRow(23, $n, $v['feeOther']);
			$excelActiveSheet->setCellValueByColumnAndRow(24, $n, empty($v['feePK']) ? 0 : $v['feePK']);

			$excelActiveSheet->setCellValueByColumnAndRow(25, $n, $v['contractType'] == 'GCXMYD-04' ? '--' : bcdiv($v['exgross'], 100, 4));
			// $excelActiveSheet->setCellValueByColumnAndRow(26, $n, bcdiv($v['feeAllProcessCount'], 100, 4));
			$excelActiveSheet->setCellValueByColumnAndRow(26, $n, bcdiv($v['projectProcess'], 100, 4));
			$excelActiveSheet->setCellValueByColumnAndRow(27, $n, iconv("GBK", "utf-8", $v['province']));
			if ($v['planBeginDate'] != '0000-00-00' && !empty($v['planBeginDate'])) {
				$excelActiveSheet->setCellValueByColumnAndRow(28, $n, $v['planBeginDate']);
			}
			if ($v['planEndDate'] != '0000-00-00' && !empty($v['planEndDate'])) {
				$excelActiveSheet->setCellValueByColumnAndRow(29, $n, $v['planEndDate']);
			}
			if ($v['actBeginDate'] != '0000-00-00' && !empty($v['actBeginDate'])) {
				$excelActiveSheet->setCellValueByColumnAndRow(30, $n, $v['actBeginDate']);
			}
			if ($v['actEndDate'] != '0000-00-00' && !empty($v['actEndDate'])) {
				$excelActiveSheet->setCellValueByColumnAndRow(31, $n, $v['actEndDate']);
			}
			$excelActiveSheet->setCellValueByColumnAndRow(32, $n, iconv("GBK", "utf-8", $v['managerName']));
			$excelActiveSheet->setCellValueByColumnAndRow(33, $n, iconv("GBK", "utf-8", $v['ExaStatus']));
			$excelActiveSheet->setCellValueByColumnAndRow(34, $n, $v['peopleNumber']);
			$excelActiveSheet->setCellValueByColumnAndRow(35, $n, iconv("GBK", "utf-8", $v['outsourcingName']));
			$excelActiveSheet->setCellValueByColumnAndRow(36, $n, iconv("GBK", "utf-8", $v['categoryName']));
			$excelActiveSheet->setCellValueByColumnAndRow(37, $n, iconv("GBK", "utf-8", $v['netName']));
			$excelActiveSheet->setCellValueByColumnAndRow(38, $n, iconv("GBK", "utf-8", $v['attributeName']));
			$excelActiveSheet->setCellValueByColumnAndRow(39, $n, $v['contractCode']);
			$excelActiveSheet->setCellValueByColumnAndRow(40, $n, $v['rObjCode']);
			$excelActiveSheet->setCellValueByColumnAndRow(41, $n, $v['contractType'] == 'GCXMYD-04' ? '--' : $v['contractMoney']);
            $excelActiveSheet->setCellValueByColumnAndRow(42, $n, $v['contractType'] == 'GCXMYD-04' ? '0' : $v['curIncome']);
			$excelActiveSheet->setCellValueByColumnAndRow(43, $n, bcdiv($v['workRate'], 100, 4));
			$excelActiveSheet->setCellValueByColumnAndRow(44, $n, $v['updateTime']);
            $excelActiveSheet->setCellValueByColumnAndRow(45, $n, $v['estimates']);
		}

		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		//�������
		ob_end_clean();
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "������Ŀ.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

    /**
     * ��Ŀ������Ϣ
     * @param $dataArr
     * @throws Exception
     */
    public static function exportProject07($dataArr) {
        set_time_limit(0);
        PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/������Ŀ����ģ��.xlsx"); //��ȡģ��
        $excelActiveSheet = $objPHPExcel->getActiveSheet();
        foreach ($dataArr as $k => $v) {
            $n = $k + 2;
			$excelActiveSheet->setCellValueByColumnAndRow(0, $n, iconv("GBK", "utf-8", $v['attributeName']));//��Ŀ����
			$excelActiveSheet->setCellValueByColumnAndRow(1, $n, iconv("GBK", "utf-8", $v['productLineName']));//ִ������
            $excelActiveSheet->setCellValueByColumnAndRow(2, $n, iconv("GBK", "utf-8", $v['newProLineName']));//��Ʒ��
            $excelActiveSheet->setCellValueByColumnAndRow(3, $n, iconv("GBK", "utf-8", $v['projectName']));//��Ŀ����
            $excelActiveSheet->setCellValueByColumnAndRow(4, $n, $v['projectCode']);//��Ŀ���
			$excelActiveSheet->setCellValueByColumnAndRow(5, $n, $v['contractCode']);//������ͬ��
			if ($v['planBeginDate'] != '0000-00-00' && !empty($v['planBeginDate'])) {
				$excelActiveSheet->setCellValueByColumnAndRow(6, $n, $v['planBeginDate']);//Ԥ����������
			}
			if ($v['planEndDate'] != '0000-00-00' && !empty($v['planEndDate'])) {
				$excelActiveSheet->setCellValueByColumnAndRow(7, $n, $v['planEndDate']);//Ԥ�ƽ�������
			}

            $excelActiveSheet->setCellValueByColumnAndRow(8, $n, iconv("GBK", "utf-8", $v['statusName']));//״̬
			$excelActiveSheet->setCellValueByColumnAndRow(9, $n, iconv("GBK", "utf-8", $v['categoryName']));//��Ŀ���
            $projectProcess = bcdiv($v['feeProcess'], 100, 4);
			$excelActiveSheet->setCellValueByColumnAndRow(10, $n, bcdiv($v['projectProcess'], 100, 6));//��Ŀ����
			// $excelActiveSheet->setCellValueByColumnAndRow(11, $n, sprintf("%.4f",$projectProcess));//���ý���
			$excelActiveSheet->setCellValueByColumnAndRow(11, $n, bcdiv($v['DeliverySchedule'], 100, 6));//��������
			$excelActiveSheet->setCellValueByColumnAndRow(12, $n, $v['contractMoney']);//��ͬ���
			// $excelActiveSheet->setCellValueByColumnAndRow(14, $n, $v['projectRate']/100);//��Ŀռ��
			$excelActiveSheet->setCellValueByColumnAndRow(13, $n, $v['projectMoneyWithTax']);//��Ŀ��˰ǰ��
			$excelActiveSheet->setCellValueByColumnAndRow(14, $n, $v['estimates']);//��Ŀ����
            $excelActiveSheet->setCellValueByColumnAndRow(15, $n, $v['budgetAll']);//��Ԥ��
			$excelActiveSheet->setCellValueByColumnAndRow(16, $n, $v['feeAll']);//�ܳɱ�
			$excelActiveSheet->setCellValueByColumnAndRow(17, $n, $v['contractType'] == 'GCXMYD-04' ? '0' : $v['curIncome']);//��ǰ����
			// $excelActiveSheet->setCellValueByColumnAndRow(20, $n, $v['reserveEarnings']);//Ԥ��Ӫ��
			$excelActiveSheet->setCellValueByColumnAndRow(18, $n, $v['grossProfit']);//��Ŀë��
			$excelActiveSheet->setCellValueByColumnAndRow(19, $n, $v['contractType'] == 'GCXMYD-04' ? '--' : bcdiv($v['exgross'], 100, 4));//ë����
            $excelActiveSheet->setCellValueByColumnAndRow(20, $n, $v['budgetField']);//����֧��Ԥ��
            // $feeFieldCount = $v['feeField'];
			$feeFieldCount = bcadd($v['feeField'], $v['feeFieldImport'], 2);
			$feeFieldCount = bcadd($feeFieldCount, $v['feeFlights'], 2);
			$feeFieldCount = bcadd($feeFieldCount, $v['feePayables'], 2);
			$feeFieldCount = bcadd($feeFieldCount, $v['feeCar'], 2);
			$feeFieldCount = bcadd($feeFieldCount, $v['feeCostbx'], 2);

			$excelActiveSheet->setCellValueByColumnAndRow(21, $n, $feeFieldCount);//����֧���ɱ��ϼ�

            $excelActiveSheet->setCellValueByColumnAndRow(22, $n, $v['budgetPerson']);//����Ԥ��
			$feePersonCount = bcadd($v['feePerson'], $v['feeSubsidy'], 2);
			$feePersonCount = bcadd($feePersonCount, $v['feeSubsidyImport'], 2);
			$excelActiveSheet->setCellValueByColumnAndRow(23, $n, $feePersonCount);//�����ɱ��ϼ�

            $excelActiveSheet->setCellValueByColumnAndRow(24, $n, $v['budgetEqu']);//�豸Ԥ��
			$excelActiveSheet->setCellValueByColumnAndRow(25, $n, bcadd($v['feeEqu'], $v['feeEquImport'], 2));//�豸�ɱ�
            $excelActiveSheet->setCellValueByColumnAndRow(26, $n, $v['budgetOutsourcing']);//���Ԥ��
			$excelActiveSheet->setCellValueByColumnAndRow(27, $n, $v['feeOutsourcing']);//����ɱ�
            $excelActiveSheet->setCellValueByColumnAndRow(28, $n, bcadd($v['budgetOther'], empty($v['budgetPK']) ? 0 : $v['budgetPK'], 2));//����Ԥ��
            $excelActiveSheet->setCellValueByColumnAndRow(29, $n, bcadd($v['feeOther'], empty($v['feePK']) ? 0 : $v['feePK'], 2));//�����ɱ����ϼƣ�

			$excelActiveSheet->setCellValueByColumnAndRow(30, $n, $v['shipCostT']);//�����ɱ�
			$excelActiveSheet->setCellValueByColumnAndRow(31, $n, $v['shipCost']);//���ᷢ���ɱ�
			// $excelActiveSheet->setCellValueByColumnAndRow(35, $n, $v['equCost']);//�������ɱ�

			$excelActiveSheet->setCellValueByColumnAndRow(32, $n, iconv("GBK", "utf-8", $v['outsourcingName']));//�������

            $excelActiveSheet->setCellValueByColumnAndRow(33, $n, iconv("GBK", "utf-8", $v['managerName']));//��Ŀ����
			$excelActiveSheet->setCellValueByColumnAndRow(34, $n, iconv("GBK", "utf-8", $v['earningsType'] ? $v['earningsType'] : $v['incomeTypeName']));//����ȷ�Ϸ�ʽ


        }

        $objWriter = new PHPExcel_Writer_Excel2007 ($objPHPExcel);
        //�������
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "������Ŀ.xlsx" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

	/**
	 * �����豸��Ϣ
	 * @param $dataArr
	 * @throws Exception
	 */
	public static function exportDeviceProject($dataArr) {
		set_time_limit(0);
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		$objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/����-�豸��Ϣ����ģ��.xls"); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet();
		foreach ($dataArr as $k => $v) {
			$rowNum = $k + 2;
			$excelActiveSheet->setCellValueByColumnAndRow(0, $rowNum, iconv("GBK", "utf-8", $v['deviceType']));
			$excelActiveSheet->setCellValueByColumnAndRow(1, $rowNum, iconv("GBK", "utf-8", $v['device_name']));
			$excelActiveSheet->setCellValueExplicitByColumnAndRow(2, $rowNum, iconv("gb2312", "utf-8", $v['coding']), PHPExcel_Cell_DataType::TYPE_STRING);
			$excelActiveSheet->setCellValueExplicitByColumnAndRow(3, $rowNum, iconv("gb2312", "utf-8", $v['dpcoding']), PHPExcel_Cell_DataType::TYPE_STRING);
			$excelActiveSheet->setCellValueByColumnAndRow(4, $rowNum, iconv("GBK", "utf-8", $v['borrowNum']));
			$excelActiveSheet->setCellValueByColumnAndRow(5, $rowNum, iconv("GBK", "utf-8", $v['unit']));
			$excelActiveSheet->setCellValueByColumnAndRow(6, $rowNum, iconv("GBK", "utf-8", $v['borrowUserName']));
			$excelActiveSheet->setCellValueByColumnAndRow(7, $rowNum, iconv("GBK", "utf-8", $v['borrowDate']));
			$excelActiveSheet->setCellValueByColumnAndRow(8, $rowNum, iconv("GBK", "utf-8", $v['returnDate']));
			$excelActiveSheet->setCellValueByColumnAndRow(9, $rowNum, iconv("GBK", "utf-8", $v['useDays']));
			$excelActiveSheet->setCellValueByColumnAndRow(10, $rowNum, iconv("GBK", "utf-8", $v['amount']));
			$excelActiveSheet->setCellValueByColumnAndRow(11, $rowNum, iconv("GBK", "utf-8", $v['fitting']));
			$excelActiveSheet->setCellValueByColumnAndRow(12, $rowNum, iconv("GBK", "utf-8", $v['projectCode']));
			$excelActiveSheet->setCellValueByColumnAndRow(13, $rowNum, iconv("GBK", "utf-8", $v['projectName']));
			$excelActiveSheet->setCellValueByColumnAndRow(14, $rowNum, iconv("GBK", "utf-8", $v['notes']));
			$excelActiveSheet->setCellValueByColumnAndRow(15, $rowNum, iconv("GBK", "utf-8", $v['budgetPrice']));
		}

		$objWriter = new PHPExcel_Writer_Excel5 ($objPHPExcel);
		//�������
		ob_end_clean();
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "�豸��Ϣ.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	/**
	 * ����������ͳ��
	 * @param $thArr
	 * @param $rowDatas
	 * @param $fileName
	 * @throws Exception
	 */
	public static function exportSearchDept($thArr, $rowDatas, $fileName) {
		if (empty($rowDatas)) exit('û����ؿɵ�������');
		set_time_limit(0);
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		$objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/�ձ�ͷͨ��ģ��.xls"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", $fileName));
		//���ñ�ͷ����ʽ ����
		$headColumn = 0;
		foreach ($thArr as $val) {
			model_engineering_util_esmexcelutil:: tableTrow($headColumn, 1, $val, $objPhpExcelFile);
			$headColumn++;
		}
		//��ȡth key�������ݲ���
		$keysArr = array_keys($thArr);
		$rowNum = 2;
		foreach ($rowDatas as $k => $v) {
			foreach ($keysArr as $key => $val) {
				$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($key, $rowNum, mb_convert_encoding($rowDatas[$k][$val], "utf-8", "gbk,big5"));
			}
			$rowNum++;
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
	 * ����excel
	 * $rowdatas��������
	 * @param $rowdatas
	 * @throws Exception
	 */
	public static function exportWeeklogInfo($rowdatas) {
		set_time_limit(0);
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/�����ܱ�����ģ��.xls"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5 ($objPhpExcelFile);

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '�����ܱ�'));
		//���ñ�ͷ����ʽ ����
		$i = 2;
		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas [$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
					$m++;
				}
				$i++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . 'AB' . $i);
			for ($m = 0; $m < 10; $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '���������Ϣ'));
			}
		}
		//�������
		ob_end_clean(); //��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "�����ܱ�����ģ��.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	/**
	 * ��Ŀ��Ա�б���excel
	 * @param $dataArr
	 * @throws Exception
	 */
	public static function exportMemberList($dataArr) {
		set_time_limit(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/����-��Ŀ��Ա�б���ģ��.xls"); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet();
		foreach ($dataArr as $key => $val) {
			$col = $key + 2;
			$excelActiveSheet->setCellValueByColumnAndRow(0, $col, iconv("GBK", "utf-8", $val['userNo']));
			$excelActiveSheet->setCellValueByColumnAndRow(1, $col, iconv("GBK", "utf-8", $val['memberName']));
			$excelActiveSheet->setCellValueByColumnAndRow(2, $col, iconv("GBK", "utf-8", $val['belongDeptName']));
			$excelActiveSheet->setCellValueByColumnAndRow(3, $col, iconv("GBK", "utf-8", $val['personLevel']));
			$excelActiveSheet->setCellValueByColumnAndRow(4, $col, iconv("GBK", "utf-8", $val['roleName']));
			$excelActiveSheet->setCellValueByColumnAndRow(5, $col, iconv("GBK", "utf-8", $val['beginDate']));
			$excelActiveSheet->setCellValueByColumnAndRow(6, $col, iconv("GBK", "utf-8", $val['endDate']));
			$excelActiveSheet->setCellValueByColumnAndRow(7, $col, iconv("GBK", "utf-8", $val['inWorkRate']));
			$excelActiveSheet->setCellValueByColumnAndRow(8, $col, iconv("GBK", "utf-8", $val['logMissNum']));
			$excelActiveSheet->setCellValueByColumnAndRow(9, $col, iconv("GBK", "utf-8", $val['workCoefficient']));
			$excelActiveSheet->setCellValueByColumnAndRow(10, $col, iconv("GBK", "utf-8", $val['processCoefficient']));
			$excelActiveSheet->setCellValueByColumnAndRow(11, $col, iconv("GBK", "utf-8", $val['thisProjectProcess']));
			$excelActiveSheet->setCellValueByColumnAndRow(12, $col, iconv("GBK", "utf-8", $val['loan']));
			$excelActiveSheet->setCellValueByColumnAndRow(13, $col, iconv("GBK", "utf-8", $val['feeFieldCount']));
		}

		$objWriter = new PHPExcel_Writer_Excel5 ($objPHPExcel);
		//�������
		ob_end_clean();
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "��Ŀ��Ա�б���Ϣ.xls" . '"');
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
	
	/**
	 * ��ĿԤ���㵼��excel
	 * @param $dataArr
	 * @throws Exception
	 */
	public static function exportBudget($dataArr) {
		set_time_limit(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/����-��ĿԤ���㵼��ģ��.xls"); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet();
		foreach ($dataArr as $key => $val) {
			$col = $key + 2;
            switch ($val['parentName']) {
                case '���Ԥ��' :
                    $val['parentName'] = '����ɱ�';
                    break;
                case '����Ԥ��' :
                    $val['parentName'] = '�����ɱ�';
                    break;
                case '�豸Ԥ��' :
                    $val['parentName'] = '�豸�ɱ�';
                    break;
                default:
            }
			$excelActiveSheet->setCellValueByColumnAndRow(0, $col, iconv("GBK", "utf-8", $key + 1));
			$excelActiveSheet->setCellValueByColumnAndRow(1, $col, iconv("GBK", "utf-8", $val['parentName']));
			$excelActiveSheet->setCellValueByColumnAndRow(2, $col, iconv("GBK", "utf-8", $val['budgetName']));
			$excelActiveSheet->setCellValueByColumnAndRow(3, $col, iconv("GBK", "utf-8", number_format($val['amount'],2)));
			$excelActiveSheet->setCellValueByColumnAndRow(4, $col, iconv("GBK", "utf-8", number_format($val['actFee'],2)));
			$excelActiveSheet->setCellValueByColumnAndRow(5, $col, iconv("GBK", "utf-8", number_format($val['amountWait'],2)));
			$excelActiveSheet->setCellValueByColumnAndRow(6, $col, iconv("GBK", "utf-8", number_format($val['actFeeWait'],2)));
			$excelActiveSheet->setCellValueByColumnAndRow(7, $col, iconv("GBK", "utf-8", $val['feeProcess'].'%'));
			// ��ע����
			$remark = $val['remark'];
			if($val['isImport'] == 1){
				if($val['status'] == 0){
					$remark = '��̨�������ݣ�δ���';
				}else{
					$remark = '��̨�������ݣ������';
				}
			}
			$excelActiveSheet->setCellValueByColumnAndRow(8, $col, iconv("GBK", "utf-8", $remark));
		}
	
		$objWriter = new PHPExcel_Writer_Excel5 ($objPHPExcel);
		//�������
		ob_end_clean();
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "��ĿԤ���㵼��.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}
	
	/**
	 * ��ĿԤ������������excel
	 * @param $dataArr
	 * @throws Exception
	 */
	public static function exportBudgetAll($dataArr) {
		set_time_limit(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/����-��ĿԤ���㵼��ģ�壨������.xlsx"); //��ȡģ��
		$excelActiveSheet = $objPHPExcel->getActiveSheet();

		foreach ($dataArr as $key => $val) {
			$col = $key + 2;
			$excelActiveSheet->setCellValueByColumnAndRow(0, $col, iconv("GBK", "utf-8", $val['projectCode']));
			$excelActiveSheet->setCellValueByColumnAndRow(1, $col, iconv("GBK", "utf-8", $val['statusName']));
			$excelActiveSheet->setCellValueByColumnAndRow(2, $col, iconv("GBK", "utf-8", $val['parentName']));
			$excelActiveSheet->setCellValueByColumnAndRow(3, $col, iconv("GBK", "utf-8", $val['budgetName']));
			$excelActiveSheet->setCellValueByColumnAndRow(4, $col, iconv("GBK", "utf-8", empty($val['amount']) && $val['amount'] != '0' ? '' : number_format($val['amount'],2)));
			$excelActiveSheet->setCellValueByColumnAndRow(5, $col, iconv("GBK", "utf-8", empty($val['actFee']) && $val['actFee'] != '0' ? '' : number_format($val['actFee'],2)));
			$excelActiveSheet->setCellValueByColumnAndRow(6, $col, iconv("GBK", "utf-8", empty($val['amountWait']) && $val['amountWait'] != '0' ? '' : number_format($val['amountWait'],2)));
			$excelActiveSheet->setCellValueByColumnAndRow(7, $col, iconv("GBK", "utf-8", empty($val['actFeeWait']) && $val['actFeeWait'] != '0' ? '' : number_format($val['actFeeWait'],2)));
			$excelActiveSheet->setCellValueByColumnAndRow(8, $col, iconv("GBK", "utf-8", empty($val['feeProcess']) && $val['feeProcess'] != '0' ? '' : $val['feeProcess'].'%'));
			$excelActiveSheet->setCellValueByColumnAndRow(9, $col, iconv("GBK", "utf-8", $val['remark']));
		}
	
		$objWriter = new PHPExcel_Writer_Excel2007 ($objPHPExcel);
		//�������
		ob_end_clean();
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "��ĿԤ���㵼����������.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}
}