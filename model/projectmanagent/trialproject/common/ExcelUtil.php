<?php


/**
 * @description: ʵ�����ݵ�����Excel�Ĺ���
 * @date 2010-10-20 ����07:18:35
 */
include_once "module/phpExcel/classes/PHPExcel.php";
include_once "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include ('model/contract/common/simple_html_dom.php');
include_once "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

class model_projectmanagent_trialproject_common_ExcelUtil {

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
		for ($temp = 0; $temp < 12; $temp++) {
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
	/*
		 * $cellValueΪ����Ķ�ά����
		 */
	public static function export2ExcelUtil($thArr, $rowdatas) {
		//		$stime=microtime(true); //��ȡ����ʼִ�е�ʱ��
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		//		//����һ��Excel������
		//		$objPhpExcelFile = new PHPExcel();

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/trialproject.xls"); //��ȡģ��
		$highestColumn = $objPhpExcelFile->getActiveSheet()->getHighestColumn(); // ȡ��������
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '��Ϣ�б�'));
		//���ñ�ͷ����ʽ ����
		//		$highestColumn = chr(ord('A') + count($thArr) - 1);
		//		$objPhpExcelFile->getActiveSheet()->mergeCells('A1:G1');
		//		for ($m = 0; $m < count($thArr); $m++) {
		//			model_projectmanagent_trialproject_common_ExcelUtil :: tableThead(0, 1, '�б���Ϣ', $objPhpExcelFile);
		//			model_projectmanagent_trialproject_common_ExcelUtil :: toCecter(1, $objPhpExcelFile);
		//		}
		//���ñ�ͷ����ʽ ����
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
			model_projectmanagent_trialproject_common_ExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
			$theadColumn++;
		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
					$m++;
					//model_projectmanagent_trialproject_common_ExcelUtil :: toCecter($i, $objPhpExcelFile);
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
		ob_end_clean(); //��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "�б���Ϣ.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}
	/**
	 * �����ͬ����
	 */
	public static function exportServiceExcelUtil($rowdatas) {
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		//		//����һ��Excel������
		//		$objPhpExcelFile = new PHPExcel();

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/exprotServiceExcel.xls"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		$i = 2;
		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
					$m++;
				}
				$i++;
			}
		}

		//�������
		ob_end_clean(); //��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "�б���Ϣ.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	/*
		 * $cellValueΪ����Ķ�ά����
		 */
	public static function exportDisCus($rowdatas) {
		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPHPExcel = $objReader->load("upfile/customer_export_template.xls"); //��ȡģ��
		$highestColumn = $objPHPExcel->getActiveSheet()->getHighestColumn(); // ȡ��������
		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date('H_i_s') . rand(0, 10) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		$i = 2;
		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 2;
				foreach ($rowdatas[$n] as $field => $value) {
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
					$m++;
					//					model_projectmanagent_trialproject_common_ExcelUtil :: toCecter($i, $objPHPExcel);
				}
				$i++;
			}
		} else {
			$objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':' . $highestColumn . $i);
			for ($m = 0; $m < count($rowdatas); $m++) {
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '���������Ϣ'));
			}
		}

		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		//�������
		ob_end_clean(); //��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "�ͻ���Ϣ�����б�.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');

		//		$objWriter = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
		//
		//		$objWriter = PHPExcel_IOFactory::CreateWriter ( $objPHPExcel );
		//		$objWriter->save ( $fileSave );
	}

	/**
	 * ������ ���ַ������ݵ���
	 */
	public static function exportExeBorrowInfoExcelUtil($rowdatas) {
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		//		//����һ��Excel������
		//		$objPhpExcelFile = new PHPExcel();

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/exprotExeBorrowinfoExcel.xls"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		$i = 2;
		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
					$m++;
				}
				$i++;
			}
		}

		//�������
		ob_end_clean(); //��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "�����ò��ַ���������Ϣ.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	/*
			 * $cellValueΪ����Ķ�ά����
			 */
	public static function exportContShipInfo() {
		$orderDao = new model_projectmanagent_order_order();
		$columSql = "select o.tablename,o.orderCode,o.orderTempCode,o.id,ov.productId,ov.productName,ov.productNo,
						IFNULL(ii.exeNum,0) as exeNum,sum(ov.number-ov.executedNum) as remainNum
						from shipments_oa_order o inner join oa_shipment_equ_view ov on o.id=ov.orderId
		 				left join oa_stock_inventory_info ii on (ov.productId=ii.productId  and ii.stockId=(select s.salesStockId   from oa_stock_syteminfo `s` where id=1))
						where o.ExaStatus in ('���','���������') and state in('2','4') and o.DeliveryStatus in (7,10) and ov.number-ov.executedNum>0 group by productId";
		$orderDataSql = "select o.tablename,o.createTime,o.deliveryDate,o.customerName,o.customerType,o.orderCode,o.orderTempCode,o.id,ov.productId,ov.productName
			,o.objCode,ov.productNo,ov.number-ov.executedNum as remainNum,(select c.remark from oa_contract_rate c where c.relDocType=o.tablename
			and c.relDocId=o.orgid group by o.orgid) as rateRemark from shipments_oa_order o inner join oa_shipment_equ_view ov
			on o.id=ov.orderId where o.ExaStatus in ('���','���������') and state in('2','4') and o.DeliveryStatus in (7,10) and ov.number-ov.executedNum>0  group by id";
		$equSql = "select o.tablename,o.objCode,o.orderCode,o.orderTempCode,o.id,ov.productId,ov.productName,ov.productNo,
					sum(ov.number-ov.executedNum) as remainNum from shipments_oa_order o inner join oa_shipment_equ_view ov
					on o.id=ov.orderId where o.ExaStatus in ('���','���������') and state in('2','4') and o.DeliveryStatus in (7,10) and ov.number-ov.executedNum>0 group by productId,objCode";
		$equArr = $orderDao->listBySql($equSql);
		$thArr = $orderDao->listBySql($columSql);
		$orderDataArr = $orderDao->listBySql($orderDataSql);
		$dataArr = array ();
		$objCodeArr = array ();
		foreach ($orderDataArr as $key => $val) {
			foreach( $val as $k=>$v ){
				if($v=='NULL'){
					$orderDataArr[$key][$k]='';
				}
			}
			$objCodeArr[] = "'" . $val['objCode'] . "'";
			$dataArr[$key]['customer'] = $val['customerName'];
			if ($val['orderCode']) {
				$dataArr[$key]['orderCode'] = $val['orderCode'];
			} else {
				$dataArr[$key]['orderCode'] = $val['orderTempCode'];
			}
			$dataArr[$key]['customerType'] = $val['customerType'];
			$dataArr[$key]['createTime'] = $val['createTime'];
			$dataArr[$key]['deliveryDate'] = $val['deliveryDate'];
			$dataArr[$key]['rateRemark'] = $val['rateRemark'];
		}
		$objCodeStr = implode(',', $objCodeArr);
		if ($objCodeStr) {
			$planDateSql = "select rObjCode,shipPlanDate from oa_stock_outplan where rObjCode in(" . $objCodeStr . ")";
			$planDateArr = $orderDao->listBySql($planDateSql);
		} else {
			$planDateArr = array ();
		}
		$datadictDao = new model_system_datadict_datadict();
		foreach ($orderDataArr as $index => $value) {
			$dataArr[$index]['shipPlanDate'] = '';
			foreach ($planDateArr as $key => $val) {
				if ($val['rObjCode'] == $value['objCode']) {
					$dataArr[$index]['shipPlanDate'] .= $val['shipPlanDate'] . ",";
				}
			}
			$dataArr[$index]['customerType'] = $datadictDao->getDataNameByCode($value['customerType']);
			$dataArr[$index]['rate'] = $dataArr[$index]['rateRemark'];
			unset ($dataArr[$index]['rateRemark']);
		}

		$productNameArr = array ();
		$productColumn = array ();
		$remainNumArr = array ();
		$exeNumArr = array ();
		foreach ($thArr as $key => $val) {
			$productNameArr[] = $val['productId'];
			$remainNumArr[] = $val['remainNum'];
			$productColumn[] = " (" . $val['productNo'] . ") " . $val['productName'];
			$exeNumArr[] = $val['exeNum'];
		}
		$productArr = array_flip($productNameArr);
		foreach ($productArr as $key => $val) {
			$productArr[$key] = '';
		}
		$dataArr = util_arrayUtil :: setArrayFn($productArr, $dataArr);

		foreach ($orderDataArr as $key => $val) {
			foreach ($equArr as $k => $v) {
				$productNo = $v['productId'];
				if ($val['objCode'] == $v['objCode']) {
					$dataArr[$key][$productNo] = $v['remainNum'];
				}
			}
		}
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/ordershipExcel.xlsx"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '��Ϣ�б�'));
		//���ñ�ͷ����ʽ ����
		$theadColumn = 7;
		foreach ($productColumn as $key => $val) {
			model_projectmanagent_trialproject_common_ExcelUtil :: tableColumn($theadColumn, 1, $val, $objPhpExcelFile);
			model_projectmanagent_trialproject_common_ExcelUtil :: tableColumn($theadColumn, 2, $exeNumArr[$key], $objPhpExcelFile);
			model_projectmanagent_trialproject_common_ExcelUtil :: tableColumn($theadColumn, 3, $remainNumArr[$key], $objPhpExcelFile);
			$theadColumn++;
		}
		$i = 4;
		if (!count(array_filter($dataArr)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($dataArr); $n++) {
				$m = 0;
				foreach ($dataArr[$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
					$m++;
				}
				$i++;
			}
		}
		//�������
		//        ob_end_clean();//��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "��ͬ������Ϣ.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	/*
			 * $cellValueΪ����Ķ�ά����
			 */
	public static function exportBorrowShipInfo($limits) {
		$condition = '';
		if( $limits=='Ա��' ){
			$condition = " o.subTip=0 and o.limits='Ա��' and o.ExaStatus in ('���','����') and (o.isship =1 or o.isproShipcondition = 1) ";
		}else{
			$condition = " o.subTip=0 and o.limits='�ͻ�' and o.ExaStatus='���' ";
		}

		$orderDao = new model_projectmanagent_borrow_borrow();
		$columSql = "select o.Code,o.id,ov.productId,ov.productName,ov.productNo,IFNULL(ii.exeNum,0) as exeNum,
							sum(ov.number-ov.executedNum) as remainNum
							from oa_borrow_borrow o
								 inner join oa_borrow_equ ov on o.id=ov.borrowId
							left join oa_stock_inventory_info ii on (ov.productId=ii.productId  and ii.stockId=(select s.outStockId from oa_stock_syteminfo `s` where id=1))
							where $condition and o.DeliveryStatus in (0,2)
							and ov.isDel=0 and ov.isTemp=0 and ov.number-ov.executedNum>0 and o.isTemp=0
							group by productId";
		$orderDataSql = "select o.ExaDT as createTime,o.deliveryDate,o.customerName,o.Code,o.id,ov.productId,ov.productName
			,o.objCode,ov.productNo,ov.number-ov.executedNum as remainNum,(select c.remark from oa_contract_rate c where c.relDocType='oa_borrow_borrow'
			and c.relDocId=o.id group by o.id) as rateRemark from oa_borrow_borrow o inner join oa_borrow_equ ov
			on o.id=ov.borrowId where $condition and o.DeliveryStatus in (0,2) and o.isTemp=0 and ov.isDel=0 and ov.isTemp=0 and ov.number-ov.executedNum>0  group by id";
		$equSql = "select o.objCode,o.Code,o.id,ov.productId,ov.productName,ov.productNo,
					sum(ov.number-ov.executedNum) as remainNum from oa_borrow_borrow o inner join oa_borrow_equ ov
					on o.id=ov.borrowId where $condition and o.DeliveryStatus in (0,2) and o.isTemp=0 and ov.isDel=0 and ov.isTemp=0 and ov.number-ov.executedNum>0 group by productId,id";

		$equArr = $orderDao->listBySql($equSql);
		$thArr = $orderDao->listBySql($columSql);
		$orderDataArr = $orderDao->listBySql($orderDataSql);
		$dataArr = array ();
		$objCodeArr = array ();
		foreach ($orderDataArr as $key => $val) {
			foreach( $val as $k=>$v ){
				if($v=='NULL'){
					$orderDataArr[$key][$k]='';
				}
			}
			$objCodeArr[] = "'" . $val['objCode'] . "'";
			$dataArr[$key]['customer'] = $val['customerName'];
			$dataArr[$key]['orderCode'] = $val['Code'];
			$dataArr[$key]['customerType'] = '';
			$dataArr[$key]['createTime'] = $val['createTime'];
			$dataArr[$key]['deliveryDate'] = $val['deliveryDate'];
			$dataArr[$key]['rateRemark'] = $val['rateRemark'];
		}
		$objCodeStr = implode(',', $objCodeArr);
		if ($objCodeStr) {
			$planDateSql = "select rObjCode,shipPlanDate from oa_stock_outplan where rObjCode in(" . $objCodeStr . ")";
			$planDateArr = $orderDao->listBySql($planDateSql);
		} else {
			$planDateArr = array ();
		}
		$datadictDao = new model_system_datadict_datadict();
		foreach ($orderDataArr as $index => $value) {
			$dataArr[$index]['shipPlanDate'] = '';
			foreach ($planDateArr as $key => $val) {
				if ($val['rObjCode'] == $value['objCode']) {
					$dataArr[$index]['shipPlanDate'] .= $val['shipPlanDate'] . ",";
				}
			}
			$dataArr[$index]['rate'] = $dataArr[$index]['rateRemark'];
			unset ($dataArr[$index]['rateRemark']);
		}

		$productNameArr = array ();
		$productColumn = array ();
		$remainNumArr = array ();
		$exeNumArr = array ();
		foreach ($thArr as $key => $val) {
			$productNameArr[] = $val['productId'];
			$remainNumArr[] = $val['remainNum'];
			$productColumn[] = " (" . $val['productNo'] . ") " . $val['productName'];
			$exeNumArr[] = $val['exeNum'];
		}
		$productArr = array_flip($productNameArr);
		foreach ($productArr as $key => $val) {
			$productArr[$key] = '';
		}
		$dataArr = util_arrayUtil :: setArrayFn($productArr, $dataArr);

		foreach ($orderDataArr as $key => $val) {
			foreach ($equArr as $k => $v) {
				$productNo = $v['productId'];
				if ($val['objCode'] == $v['objCode']) {
					$dataArr[$key][$productNo] = $v['remainNum'];
				}
			}
		}
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/ordershipExcel.xlsx"); //��ȡģ��
		//Excel2007��ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);
		//���ñ�ͷ����ʽ ����
		$theadColumn = 7;
		foreach ($productColumn as $key => $val) {
			model_projectmanagent_trialproject_common_ExcelUtil :: tableColumn($theadColumn, 1, $val, $objPhpExcelFile);
			model_projectmanagent_trialproject_common_ExcelUtil :: tableColumn($theadColumn, 2, $exeNumArr[$key], $objPhpExcelFile);
			model_projectmanagent_trialproject_common_ExcelUtil :: tableColumn($theadColumn, 3, $remainNumArr[$key], $objPhpExcelFile);
			$theadColumn++;
		}
		$i = 4;
		if (!count(array_filter($dataArr)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($dataArr); $n++) {
				$m = 0;
				foreach ($dataArr[$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
					$m++;
				}
				$i++;
			}
		}
		//�������
		//        ob_end_clean();//��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "������������Ϣ.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}

	/*
			 * $cellValueΪ����Ķ�ά����
			 */
	public static function exportPreShipInfo() {
		$orderDao = new model_projectmanagent_present_present();
		$columSql = "select o.Code,o.id,ov.productId,ov.productName,ov.productNo,IFNULL(ii.exeNum,0) as exeNum,
					sum(ov.number-ov.executedNum) as remainNum
					 from oa_present_present o inner join oa_present_equ ov on o.id=ov.presentId
		 			left join oa_stock_inventory_info ii on (ov.productId=ii.productId  and ii.stockId=(select s.salesStockId   from oa_stock_syteminfo `s` where id=1))
					 where o.ExaStatus='���' and o.DeliveryStatus in (7,10) and ov.isDel=0 and ov.number-ov.executedNum>0 and o.isTemp=0 group by productId";
		$orderDataSql = "select o.ExaDT as createTime,o.deliveryDate,o.customerName,o.Code,o.id,ov.productId,ov.productName
			,o.objCode,ov.productNo,ov.number-ov.executedNum as remainNum,(select c.remark from oa_contract_rate c where c.relDocType='oa_present_present'
			 and c.relDocId=o.id group by o.id) as rateRemark from oa_present_present o inner join oa_present_equ ov
			on o.id=ov.presentId where o.ExaStatus='���' and o.DeliveryStatus in (7,10) and o.isTemp=0 and ov.isDel=0 and ov.number-ov.executedNum>0  group by id";
		$equSql = "select o.objCode,o.Code,o.id,ov.productId,ov.productName,ov.productNo,
					sum(ov.number-ov.executedNum) as remainNum from oa_present_present o inner join oa_present_equ ov
					on o.id=ov.presentId where o.ExaStatus='���' and o.DeliveryStatus in (7,10) and o.isTemp=0 and ov.isDel=0 and ov.number-ov.executedNum>0 group by productId,id";
		$equArr = $orderDao->listBySql($equSql);
		$thArr = $orderDao->listBySql($columSql);
		$orderDataArr = $orderDao->listBySql($orderDataSql);
		$dataArr = array ();
		$objCodeArr = array ();
		foreach ($orderDataArr as $key => $val) {
			foreach( $val as $k=>$v ){
				if($v=='NULL'){
					$orderDataArr[$key][$k]='';
				}
			}
			$objCodeArr[] = "'" . $val['objCode'] . "'";
			$dataArr[$key]['customer'] = $val['customerName'];
			$dataArr[$key]['orderCode'] = $val['Code'];
			$dataArr[$key]['customerType'] = '';
			$dataArr[$key]['createTime'] = $val['createTime'];
			$dataArr[$key]['deliveryDate'] = $val['deliveryDate'];
			$dataArr[$key]['rateRemark'] = $val['rateRemark'];
		}
		$objCodeStr = implode(',', $objCodeArr);
		if ($objCodeStr) {
			$planDateSql = "select rObjCode,shipPlanDate from oa_stock_outplan where rObjCode in(" . $objCodeStr . ")";
			$planDateArr = $orderDao->listBySql($planDateSql);
		} else {
			$planDateArr = array ();
		}
		$datadictDao = new model_system_datadict_datadict();
		foreach ($orderDataArr as $index => $value) {
			$dataArr[$index]['shipPlanDate'] = '';
			foreach ($planDateArr as $key => $val) {
				if ($val['rObjCode'] == $value['objCode']) {
					$dataArr[$index]['shipPlanDate'] .= $val['shipPlanDate'] . ",";
				}
			}
			$dataArr[$index]['rate'] = $dataArr[$index]['rateRemark'];
			unset ($dataArr[$index]['rateRemark']);
		}

		$productNameArr = array ();
		$productColumn = array ();
		$remainNumArr = array ();
		$exeNumArr = array ();
		foreach ($thArr as $key => $val) {
			$productNameArr[] = $val['productId'];
			$remainNumArr[] = $val['remainNum'];
			$productColumn[] = " (" . $val['productNo'] . ") " . $val['productName'];
			$exeNumArr[] = $val['exeNum'];
		}
		$productArr = array_flip($productNameArr);
		foreach ($productArr as $key => $val) {
			$productArr[$key] = '';
		}
		$dataArr = util_arrayUtil :: setArrayFn($productArr, $dataArr);

		foreach ($orderDataArr as $key => $val) {
			foreach ($equArr as $k => $v) {
				$productNo = $v['productId'];
				if ($val['objCode'] == $v['objCode']) {
					$dataArr[$key][$productNo] = $v['remainNum'];
				}
			}
		}
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/ordershipExcel.xlsx"); //��ȡģ��
		//Excel2007�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);
		//���ñ�ͷ����ʽ ����
		$theadColumn = 7;
		foreach ($productColumn as $key => $val) {
			model_projectmanagent_trialproject_common_ExcelUtil :: tableColumn($theadColumn, 1, $val, $objPhpExcelFile);
			model_projectmanagent_trialproject_common_ExcelUtil :: tableColumn($theadColumn, 2, $exeNumArr[$key], $objPhpExcelFile);
			model_projectmanagent_trialproject_common_ExcelUtil :: tableColumn($theadColumn, 3, $remainNumArr[$key], $objPhpExcelFile);
			$theadColumn++;
		}
		$i = 4;
		if (!count(array_filter($dataArr)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($dataArr); $n++) {
				$m = 0;
				foreach ($dataArr[$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
					$m++;
				}
				$i++;
			}
		}
		//�������
		//        ob_end_clean();//��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "����������Ϣ.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}
    /**
     * �̻�����
     */
    public static function exportChanceExcelUtil($thArr, $rowdatas) {
		//		$stime=microtime(true); //��ȡ����ʼִ�е�ʱ��
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		//		//����һ��Excel������
		//		$objPhpExcelFile = new PHPExcel();

		$objReader = PHPExcel_IOFactory :: createReader('Excel2007'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/orderReportList07.xlsx"); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel2007($objPhpExcelFile);

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '��Ϣ�б�'));
		//���ñ�ͷ����ʽ ����
		//		$highestColumn = chr(ord('A') + count($thArr) - 1);
		//		$objPhpExcelFile->getActiveSheet()->mergeCells('A1:G1');
		//		for ($m = 0; $m < count($thArr); $m++) {
		//			model_projectmanagent_trialproject_common_ExcelUtil :: tableThead(0, 1, '�б���Ϣ', $objPhpExcelFile);
		//			model_projectmanagent_trialproject_common_ExcelUtil :: toCecter(1, $objPhpExcelFile);
		//		}
		//���ñ�ͷ����ʽ ����
		$theadColumn = 0;
		foreach ($thArr as $key => $val) {
			if ($key == 'orderCode' || $key == 'orderTempCode' || $key == 'orderName') {
				model_projectmanagent_trialproject_common_ExcelUtil :: tableTrowLong($theadColumn, 1, $val, $objPhpExcelFile);
			} else
				model_projectmanagent_trialproject_common_ExcelUtil :: tableTrow($theadColumn, 1, $val, $objPhpExcelFile);
			$theadColumn++;
		}
		$i = 2;

		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
					$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, mb_convert_encoding($value,"utf-8","gbk,big5"));
					if ($field == "surincomeMoney" || $field == "surOrderMoney" || $field == "surOrderMoney") {
						$objPhpExcelFile->getActiveSheet()->setCellValueExplicitByColumnAndRow($m, $row + $n, iconv("GBK", "utf-8", $value), PHPExcel_Cell_DataType :: TYPE_NUMERIC);
					}

					$m++;
					//model_projectmanagent_trialproject_common_ExcelUtil :: toCecter($i, $objPhpExcelFile);
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
		ob_end_clean(); //��������������������������
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "�б���Ϣ.xlsx" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
		//$etime=microtime(true);//��ȡ����ִ�н�����ʱ��
		//$total=$etime-$stime;   //�����ֵ
		//echo "<br />{$total} times";
	}
}
?>