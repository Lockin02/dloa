<?php
/**
 * @description: �ִ����excel����util
 * @date 2011-07-23
 */

include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Cell.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

class model_supplierManage_formal_importSupplierUtil extends model_base {

	/**
	 * ����EXCEL���ݵ�ϵͳ��
	 * @param  $file
	 * @param  $filetempname
	 */
	function readExcelData($file, $filetempname) {
		//�Լ����õ��ϴ��ļ����·��
		$filePath = 'upfile/';
		$filename = explode ( ".", $file ); //���ϴ����ļ����ԡ�.����Ϊ׼��һ�����顣
		$time = date ( "y-m-d-H-i-s" ); //ȥ��ǰ�ϴ���ʱ��
		$filename [0] = $time; //ȡ�ļ����滻
		$name = implode ( ".", $filename ); //�ϴ�����ļ���
		$uploadfile = $filePath . $name; //�ϴ�����ļ�����ַ


		//move_uploaded_file() �������ϴ����ļ��ƶ�����λ�á����ɹ����򷵻� true�����򷵻� false��
		$result = move_uploaded_file ( $filetempname, $uploadfile ); //�����ϴ�����ǰĿ¼��
		if ($result) { //����ϴ��ļ��ɹ�����ִ�е���excel����
			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
			$objPHPExcel = $objReader->load ( $uploadfile ); //   $objPHPExcel = $objReader->load($uploadfile);
			$sheet = $objPHPExcel->getSheet ( 0 );
			$highestRow = $sheet->getHighestRow (); // ȡ��������
			$highestColumn = $sheet->getHighestColumn (); // ȡ��������
			$dataArr = array (); //��ȡ���


			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

			//��ȡ�̶�������
			$dataArr['suppName']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B3" )->getValue () );
			$dataArr['products']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B4" )->getValue () );
			$dataArr['address']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B5" )->getValue () );
			$dataArr['legalRepre']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B6" )->getValue () );
			$dataArr['registeredFunds']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D6" )->getValue () );
			$dataArr['bankName']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B7" )->getValue () );
			$dataArr['accountNum']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D7" )->getValue () );
			$dataArr['businRegistCode']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B8" )->getValue () );
			$dataArr['businessCode']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D8" )->getValue () );
			$dataArr['employeesNum']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "B9" )->getValue () );
			$dataArr['companySize']=iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "D9" )->getValue () );
			$dataArr['linkman']=array();
			//ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
			for($j = 12; $j <= $highestRow; $j++) {
				$str = "";
					for($k = A; $k <= D; $k++) {
						$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ); //��ȡ��Ԫ��
						if ($k != $highestColumn) {
							$str .= '\\';
						}
					}

					$rowData = explode ( "\\", $str );
					if(trim($rowData['0'])!=""){
						array_push ( $dataArr['linkman'], $rowData );
					}
			}
			$dataArr['linkmanNumb']=count($dataArr['linkman']);
			unlink ( $uploadfile ); //ɾ���ϴ���excel�ļ�
			$msg = "��ȡ�ɹ���";
		} else {
			$msg = "��ȡʧ�ܣ�";
		}

		return $dataArr;
	}
}
?>
