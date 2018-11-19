<?php
/**
 * @description: 工程管理
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

	//模块名样式
	public static function tableThead($column, $row, $content, $objPHPExcel) {
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}

	//模块列样式
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
		//格式化数字为字符串，避免导致数字默认右对齐单元格
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setARGB("00ECF8FB");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, iconv("gb2312", "utf-8", $content));
	}

	//模块列样式
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

	//模块内容居中样式
	public static function toCecter($i, $objPHPExcel) {
		for ($temp = 0; $temp < 12; $temp++) {
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($temp, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
		}
	}

	//模块列样式
	public static function tableColumn($column, $row, $content, $objPHPExcel) {
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
	 * 项目导出信息
	 * @param $dataArr
	 * @throws Exception
	 */
	public static function exportProject($dataArr) {
		set_time_limit(0);
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		$objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/工程项目导出模板.xls"); //读取模板
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
		//到浏览器
		ob_end_clean();
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "工程项目.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

    /**
     * 项目导出信息
     * @param $dataArr
     * @throws Exception
     */
    public static function exportProject07($dataArr) {
        set_time_limit(0);
        PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/工程项目导出模板.xlsx"); //读取模板
        $excelActiveSheet = $objPHPExcel->getActiveSheet();
        foreach ($dataArr as $k => $v) {
            $n = $k + 2;
			$excelActiveSheet->setCellValueByColumnAndRow(0, $n, iconv("GBK", "utf-8", $v['attributeName']));//项目属性
			$excelActiveSheet->setCellValueByColumnAndRow(1, $n, iconv("GBK", "utf-8", $v['productLineName']));//执行区域
            $excelActiveSheet->setCellValueByColumnAndRow(2, $n, iconv("GBK", "utf-8", $v['newProLineName']));//产品线
            $excelActiveSheet->setCellValueByColumnAndRow(3, $n, iconv("GBK", "utf-8", $v['projectName']));//项目名称
            $excelActiveSheet->setCellValueByColumnAndRow(4, $n, $v['projectCode']);//项目编号
			$excelActiveSheet->setCellValueByColumnAndRow(5, $n, $v['contractCode']);//鼎利合同号
			if ($v['planBeginDate'] != '0000-00-00' && !empty($v['planBeginDate'])) {
				$excelActiveSheet->setCellValueByColumnAndRow(6, $n, $v['planBeginDate']);//预计启动日期
			}
			if ($v['planEndDate'] != '0000-00-00' && !empty($v['planEndDate'])) {
				$excelActiveSheet->setCellValueByColumnAndRow(7, $n, $v['planEndDate']);//预计结束日期
			}

            $excelActiveSheet->setCellValueByColumnAndRow(8, $n, iconv("GBK", "utf-8", $v['statusName']));//状态
			$excelActiveSheet->setCellValueByColumnAndRow(9, $n, iconv("GBK", "utf-8", $v['categoryName']));//项目类别
            $projectProcess = bcdiv($v['feeProcess'], 100, 4);
			$excelActiveSheet->setCellValueByColumnAndRow(10, $n, bcdiv($v['projectProcess'], 100, 6));//项目进度
			// $excelActiveSheet->setCellValueByColumnAndRow(11, $n, sprintf("%.4f",$projectProcess));//费用进度
			$excelActiveSheet->setCellValueByColumnAndRow(11, $n, bcdiv($v['DeliverySchedule'], 100, 6));//发货进度
			$excelActiveSheet->setCellValueByColumnAndRow(12, $n, $v['contractMoney']);//合同金额
			// $excelActiveSheet->setCellValueByColumnAndRow(14, $n, $v['projectRate']/100);//项目占比
			$excelActiveSheet->setCellValueByColumnAndRow(13, $n, $v['projectMoneyWithTax']);//项目金额（税前）
			$excelActiveSheet->setCellValueByColumnAndRow(14, $n, $v['estimates']);//项目概算
            $excelActiveSheet->setCellValueByColumnAndRow(15, $n, $v['budgetAll']);//总预算
			$excelActiveSheet->setCellValueByColumnAndRow(16, $n, $v['feeAll']);//总成本
			$excelActiveSheet->setCellValueByColumnAndRow(17, $n, $v['contractType'] == 'GCXMYD-04' ? '0' : $v['curIncome']);//当前收入
			// $excelActiveSheet->setCellValueByColumnAndRow(20, $n, $v['reserveEarnings']);//预留营收
			$excelActiveSheet->setCellValueByColumnAndRow(18, $n, $v['grossProfit']);//项目毛利
			$excelActiveSheet->setCellValueByColumnAndRow(19, $n, $v['contractType'] == 'GCXMYD-04' ? '--' : bcdiv($v['exgross'], 100, 4));//毛利率
            $excelActiveSheet->setCellValueByColumnAndRow(20, $n, $v['budgetField']);//报销支付预算
            // $feeFieldCount = $v['feeField'];
			$feeFieldCount = bcadd($v['feeField'], $v['feeFieldImport'], 2);
			$feeFieldCount = bcadd($feeFieldCount, $v['feeFlights'], 2);
			$feeFieldCount = bcadd($feeFieldCount, $v['feePayables'], 2);
			$feeFieldCount = bcadd($feeFieldCount, $v['feeCar'], 2);
			$feeFieldCount = bcadd($feeFieldCount, $v['feeCostbx'], 2);

			$excelActiveSheet->setCellValueByColumnAndRow(21, $n, $feeFieldCount);//报销支付成本合计

            $excelActiveSheet->setCellValueByColumnAndRow(22, $n, $v['budgetPerson']);//人力预算
			$feePersonCount = bcadd($v['feePerson'], $v['feeSubsidy'], 2);
			$feePersonCount = bcadd($feePersonCount, $v['feeSubsidyImport'], 2);
			$excelActiveSheet->setCellValueByColumnAndRow(23, $n, $feePersonCount);//人力成本合计

            $excelActiveSheet->setCellValueByColumnAndRow(24, $n, $v['budgetEqu']);//设备预算
			$excelActiveSheet->setCellValueByColumnAndRow(25, $n, bcadd($v['feeEqu'], $v['feeEquImport'], 2));//设备成本
            $excelActiveSheet->setCellValueByColumnAndRow(26, $n, $v['budgetOutsourcing']);//外包预算
			$excelActiveSheet->setCellValueByColumnAndRow(27, $n, $v['feeOutsourcing']);//外包成本
            $excelActiveSheet->setCellValueByColumnAndRow(28, $n, bcadd($v['budgetOther'], empty($v['budgetPK']) ? 0 : $v['budgetPK'], 2));//其他预算
            $excelActiveSheet->setCellValueByColumnAndRow(29, $n, bcadd($v['feeOther'], empty($v['feePK']) ? 0 : $v['feePK'], 2));//其他成本（合计）

			$excelActiveSheet->setCellValueByColumnAndRow(30, $n, $v['shipCostT']);//发货成本
			$excelActiveSheet->setCellValueByColumnAndRow(31, $n, $v['shipCost']);//计提发货成本
			// $excelActiveSheet->setCellValueByColumnAndRow(35, $n, $v['equCost']);//存货核算成本

			$excelActiveSheet->setCellValueByColumnAndRow(32, $n, iconv("GBK", "utf-8", $v['outsourcingName']));//外包类型

            $excelActiveSheet->setCellValueByColumnAndRow(33, $n, iconv("GBK", "utf-8", $v['managerName']));//项目经理
			$excelActiveSheet->setCellValueByColumnAndRow(34, $n, iconv("GBK", "utf-8", $v['earningsType'] ? $v['earningsType'] : $v['incomeTypeName']));//收入确认方式


        }

        $objWriter = new PHPExcel_Writer_Excel2007 ($objPHPExcel);
        //到浏览器
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "工程项目.xlsx" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

	/**
	 * 导出设备信息
	 * @param $dataArr
	 * @throws Exception
	 */
	public static function exportDeviceProject($dataArr) {
		set_time_limit(0);
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		$objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/工程-设备信息导出模板.xls"); //读取模板
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
		//到浏览器
		ob_end_clean();
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "设备信息.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	/**
	 * 导出工作量统计
	 * @param $thArr
	 * @param $rowDatas
	 * @param $fileName
	 * @throws Exception
	 */
	public static function exportSearchDept($thArr, $rowDatas, $fileName) {
		if (empty($rowDatas)) exit('没有相关可导出数据');
		set_time_limit(0);
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		$objReader = PHPExcel_IOFactory:: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/空表头通用模板.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", $fileName));
		//设置表头及样式 设置
		$headColumn = 0;
		foreach ($thArr as $val) {
			model_engineering_util_esmexcelutil:: tableTrow($headColumn, 1, $val, $objPhpExcelFile);
			$headColumn++;
		}
		//获取th key用于数据布局
		$keysArr = array_keys($thArr);
		$rowNum = 2;
		foreach ($rowDatas as $k => $v) {
			foreach ($keysArr as $key => $val) {
				$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($key, $rowNum, mb_convert_encoding($rowDatas[$k][$val], "utf-8", "gbk,big5"));
			}
			$rowNum++;
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
	 * 导出excel
	 * $rowdatas传入数组
	 * @param $rowdatas
	 * @throws Exception
	 */
	public static function exportWeeklogInfo($rowdatas) {
		set_time_limit(0);
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/部门周报导出模版.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5 ($objPhpExcelFile);

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '部门周报'));
		//设置表头及样式 设置
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
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}
		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "部门周报导出模版.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	/**
	 * 项目成员列表导出excel
	 * @param $dataArr
	 * @throws Exception
	 */
	public static function exportMemberList($dataArr) {
		set_time_limit(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/工程-项目成员列表导出模板.xls"); //读取模板
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
		//到浏览器
		ob_end_clean();
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "项目成员列表信息.xls" . '"');
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
	
	/**
	 * 项目预决算导出excel
	 * @param $dataArr
	 * @throws Exception
	 */
	public static function exportBudget($dataArr) {
		set_time_limit(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/工程-项目预决算导出模板.xls"); //读取模板
		$excelActiveSheet = $objPHPExcel->getActiveSheet();
		foreach ($dataArr as $key => $val) {
			$col = $key + 2;
            switch ($val['parentName']) {
                case '外包预算' :
                    $val['parentName'] = '外包成本';
                    break;
                case '其他预算' :
                    $val['parentName'] = '其他成本';
                    break;
                case '设备预算' :
                    $val['parentName'] = '设备成本';
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
			// 备注处理
			$remark = $val['remark'];
			if($val['isImport'] == 1){
				if($val['status'] == 0){
					$remark = '后台导入数据，未审核';
				}else{
					$remark = '后台导入数据，已审核';
				}
			}
			$excelActiveSheet->setCellValueByColumnAndRow(8, $col, iconv("GBK", "utf-8", $remark));
		}
	
		$objWriter = new PHPExcel_Writer_Excel5 ($objPHPExcel);
		//到浏览器
		ob_end_clean();
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "项目预决算导出.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}
	
	/**
	 * 项目预决算批量导出excel
	 * @param $dataArr
	 * @throws Exception
	 */
	public static function exportBudgetAll($dataArr) {
		set_time_limit(0);
		$objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/工程-项目预决算导出模板（批量）.xlsx"); //读取模板
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
		//到浏览器
		ob_end_clean();
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "项目预决算导出（批量）.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}
}