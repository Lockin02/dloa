<?php
/*
 * Created on 2011-6-16
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * ���к�ͬ������
 */

include_once "module/phpExcel/classes/PHPExcel.php";
include_once "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";

include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";

class model_contract_common_allcontract extends model_base{


	function __construct() {
		parent :: __construct();
		$this->docModelArr = array (//��ͬ������,������Ҫ���������׷��
			"oa_sale_order" => "model_projectmanagent_order_orderequ", //����
			"oa_sale_lease" => "model_contract_rental_tentalcontractequ", //����
			"oa_sale_service" => "model_engineering_serviceContract_serviceequ", //�����ͬ
			"oa_sale_rdproject" => "model_rdproject_yxrdproject_rdprojectequ", //�з���ͬ
			"oa_borrow_borrow" => "projectmanagent_borrow_borrowequ" ,//���÷���
			"oa_present_present" => "projectmanagent_present_presentequ" //���ͷ���
		);

		$this->docContArr = array (//��ͬ���ͳ������������,������Ҫ���������׷��
			"oa_sale_order" => "model_projectmanagent_order_order", //���۷���
			"oa_sale_lease" => "model_contract_rental_rentalcontract", //���޳���
			"oa_sale_service" => "model_engineering_serviceContract_serviceContract", //�����ͬ����
			"oa_sale_rdproject" => "model_rdproject_yxrdproject_rdproject", //�з���ͬ����
			"oa_borrow_borrow" => "model_projectmanagent_borrow_borrow" ,//���÷���
			"oa_present_present" => "model_projectmanagent_present_present" //���ͷ���
		);
        $this->docContItem = array(//���ֺ�ͬ�ӱ����ݿ�����
            "oa_sale_order" => "oa_sale_order_equ",
            "oa_sale_service" => "oa_service_equ",
            "oa_sale_lease" => "oa_lease_equ",
            "oa_sale_rdproject" => "oa_rdproject_equ"
		);
	}
    /**
	 * ���ݺ�ͬID ������  ��ȡ������ת���� ����
	 * @parm : $orderId -- ��ͬID  $orderType -- ��ͬ���ͣ�������
	 */
    function getBorrwoToOrderequ($orderId,$orderType){
        $dao = new $this->docModelArr[$orderType];
        $equArr = $dao->find(array("orderId" => $orderId,"isBorrowToorder" => '1'));
        return $equArr;
    }

      /**
     * ���ݺ�ͬ���ͻ�ȡ���ֺ�ͬ����
     */
    function orderInfo($orderId,$orderType) {
         $DaoName = $this->docContArr[$orderType];
         $InfoDao = new $DaoName();
         $rows = $InfoDao->get_d($orderId);
         return $rows;
    }

	/**
	 *���ݲ�ͬ������ʵ��������
	 *
	 * @param tags
	 * @return return_type
	 */
	function newTypeDao_d ($type) {
		$contModel=$this->docModelArr[$type];
		return new $contModel();
	}
	/**
	 * ���ݲ�ͬ���͵ĵ��ݵ�ID���޸ĵ��ݴӱ����;����
	 * @param $onWayNum   �������;����
	 * @param $parentId   ���ϱ��������������ID
	 * @return $interface
	 */
	function setOnWayNum_d ($type,$parentId,$onWayNum) {
		$contTypeDao=$this->newTypeDao_d($type);
		$sql="update " . $contTypeDao->tbl_name . " set onWayNum=onWayNum+" . $onWayNum." where id=" . $parentId;
		$this->query($sql);
		return 1;
	}


	/**
	 * �����ݱ�������һ������
	 *
	 * @param row ������ʽ������ļ������ݱ��е��ֶ���������Ӧ��ֵ����Ҫ���������ݡ�
	 */
   function createOrder($row) {
   	  try{
   	  	 		if (! is_array ( $row ))
			return FALSE;
		$row = $this->__prepera_format ( $row );
		if (empty ( $row ))
			return FALSE;
		foreach ( $row as $key => $value ) {
			$cols [] = $key;
			$vals [] = "'" . $this->__val_escape ( $value ) . "'";
		}
		$col = join ( ',', $cols );
		$val = join ( ',', $vals );

		$sql = "INSERT INTO {$this->tbl_name} ({$col}) VALUES ({$val})";
		//echo $sql;
		if (FALSE != $this->_db->query ( $sql )) { // ��ȡ��ǰ������ID
			if ($newinserid = $this->_db->insert_id ()) {
				return $newinserid;
			} else {
//				return array_pop ( $this->find ( $row, "{$this->pk} DESC", $this->pk ) );
			}
		}
		return FALSE;
   	  }catch (exception $e){
   	  	   $this->service->rollBack();
   	  	   return false;
   	  }

	}

//�ϴ�Excel����ȡ excel����
	function upExcelData($file, $filetempname) {
		//�Լ����õ��ϴ��ļ����·��
		$filePath = UPLOADPATH.'upfile/';
		if(!is_dir($filePath)){
			mkdir($filePath);
		}
		$str = "";

		//�����·��������PHPExcel��·�����޸�
		//set_include_path ( '.' . PATH_SEPARATOR . 'D:\EXCELDEMO' . PATH_SEPARATOR . get_include_path () );


		$filename = explode ( ".", $file ); //���ϴ����ļ����ԡ�.����Ϊ׼��һ�����顣
		$time = date ( "y-m-d-H-i-s" ); //ȥ��ǰ�ϴ���ʱ��
		$filename [0] = $time; //ȡ�ļ����滻
		$name = implode ( ".", $filename ); //�ϴ�����ļ���
		$uploadfile = $filePath . $name; //�ϴ�����ļ�����ַ


		//move_uploaded_file() �������ϴ����ļ��ƶ�����λ�á����ɹ����򷵻� true�����򷵻� false��

		$result = move_uploaded_file ( $filetempname, $uploadfile ); //�����ϴ�����ǰĿ¼��

		if ($result) //����ϴ��ļ��ɹ�����ִ�е���excel����
{
			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
			$objPHPExcel = $objReader->load ( $uploadfile ); //   $objPHPExcel = $objReader->load($uploadfile);
			$sheet = $objPHPExcel->getSheet ( 0 );
			$highestRow = $sheet->getHighestRow (); // ȡ��������
			$highestColumn = $sheet->getHighestColumn (); // ȡ��������


			$dataArr = array (); //��ȡ���


//			//ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
//			for($j = 2; $j <= $highestRow; $j ++) {
//				for($k = 'A'; $k <= $highestColumn; $k ++) {
//					$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ) . '\\'; //��ȡ��Ԫ��
//				}
//				//explode:�������ַ����ָ�Ϊ���顣
//				$strs = explode ( "\\", $str );
//				array_push ( $dataArr, $strs );
//				$str = "";
//			}
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

			//ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
			for($j = 2; $j <= $highestRow; $j ++) {
				$str = "";
				for($k = 0; $k <= $highestColumnIndex; $k ++) {
					$str .= iconv ( 'UTF-8', 'GBK', $sheet->getCellByColumnAndRow ( $k, $j )->getValue () ); //��ȡ��Ԫ��
					if ($k != $highestColumnIndex) {
						$str .= '\\';
					}
				}

				$rowData = explode ( "\\", $str );
				$rowData['23']=$j;
				array_push ( $dataArr, $rowData );
			}

			//			unlink ( $uploadfile ); //ɾ���ϴ���excel�ļ�
			$msg = "��ȡ�ɹ���";
		} else {
			$msg = "��ȡʧ�ܣ�";
		}

		return $dataArr;
	}
	/**
	 * ȡ���ļ���չ
	 *
	 * @param $filename �ļ���
	 * @return ��չ��
	 */
	function fileext($filename) {
	    return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
	}

	function upExcelData_07($file, $filetempname) {
		//�Լ����õ��ϴ��ļ����·��
		$filePath = UPLOADPATH.'upfile/';
		if(!is_dir($filePath)){
			mkdir($filePath);
		}
		$str = "";

		//�����·��������PHPExcel��·�����޸�
		//set_include_path ( '.' . PATH_SEPARATOR . 'D:\EXCELDEMO' . PATH_SEPARATOR . get_include_path () );


		$filename = explode ( ".", $file ); //���ϴ����ļ����ԡ�.����Ϊ׼��һ�����顣
		$time = date ( "y-m-d-H-i-s" ); //ȥ��ǰ�ϴ���ʱ��
		$filename [0] = $time; //ȡ�ļ����滻
		$name = implode ( ".", $filename ); //�ϴ�����ļ���
		$uploadfile = $filePath . $name; //�ϴ�����ļ�����ַ


		//move_uploaded_file() �������ϴ����ļ��ƶ�����λ�á����ɹ����򷵻� true�����򷵻� false��

		$result = move_uploaded_file ( $filetempname, $uploadfile ); //�����ϴ�����ǰĿ¼��
		if ($result) //����ϴ��ļ��ɹ�����ִ�е���excel����
{
	        $excel_ext = $this->fileext($uploadfile);
	        if($excel_ext=="xlsx"){
	            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
	        }else{
	            $objReader = PHPExcel_IOFactory::createReader('Excel5');
	        }
//			$objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' ); //use excel2007 for 2007 format
			$objPHPExcel = $objReader->load ( $uploadfile ); //   $objPHPExcel = $objReader->load($uploadfile);
			$sheet = $objPHPExcel->getSheet ( 0 );
			$highestRow = $sheet->getHighestRow (); // ȡ��������
			$highestColumn = $sheet->getHighestColumn (); // ȡ��������


			$dataArr = array (); //��ȡ���


//			//ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
//			for($j = 2; $j <= $highestRow; $j ++) {
//				for($k = 'A'; $k <= $highestColumn; $k ++) {
//					$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ) . '\\'; //��ȡ��Ԫ��
//				}
//				//explode:�������ַ����ָ�Ϊ���顣
//				$strs = explode ( "\\", $str );
//				array_push ( $dataArr, $strs );
//				$str = "";
//			}
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

			//ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
			for($j = 2; $j <= $highestRow; $j ++) {
				$str = "";
				for($k = 0; $k <= $highestColumnIndex; $k ++) {
					$str .= iconv ( 'UTF-8', 'GBK', $sheet->getCellByColumnAndRow ( $k, $j )->getValue () ); //��ȡ��Ԫ��
					if ($k != $highestColumnIndex) {
						$str .= '\\';
					}
				}

				$rowData = explode ( "\\", $str );
				$rowData['23']=$j;
				array_push ( $dataArr, $rowData );
			}

			//			unlink ( $uploadfile ); //ɾ���ϴ���excel�ļ�
			$msg = "��ȡ�ɹ���";
		} else {
			$msg = "��ȡʧ�ܣ�";
		}

		return $dataArr;
	}


	function getEqusForLock($id,$docType){
		$docEquDao=$this->docContArr[$docType];
	}

    /**
     * ���ֳ�����˴�����
     */
    function updateAsOut($rows){
    	//��ʱ��ע�� chengl
    	   $type = $rows['contractType'];
           $sql = "update oa_contract_equ set executedNum = executedNum + ".$rows['outNum']." where contractId = ".$rows['relDocId']." and id= ".$rows['relDocItemId']." ";
		   $this->_db->query($sql);
		   $DaoName = $this->docContArr[$type];
		   $dao = new $DaoName();
		   $dao->updateOrderShipStatus_d($rows['relDocId']);
    }
    /**
     * ���ֳ��ⷴ������
     */
    function updateAsAutiAudit($rows){
    	  $type = $rows['contractType'];
          $rows['outNum'] = $rows['outNum']*(-1);
		  $sql = "update ".$this->docContItem[$type]." set executedNum = executedNum + ".$rows['outNum']." where orderId = ".$rows['relDocId']." and id= ".$rows['relDocItemId']." ";
		  $this->_db->query($sql);
		  $DaoName = $this->docContArr[$type];
		  $dao = new $DaoName();
		  $dao->updateOrderShipStatus_d($rows['relDocId']);
    }


    /**
     * ���ֳ�����˴�����
     */
    function updateAsRedOut($rows){
    	   $type = $rows['contractType'];
    	   $exeNum = $rows['outNum']*(-1);
    	   $backNum = $rows['outNum'];
           $sql = "update ".$this->docContItem[$type]." set executedNum = executedNum + ".$exeNum.",backNum = backNum + ".$backNum." where orderId = ".$rows['relDocId']." and id= ".$rows['relDocItemId']." ";
		   $this->_db->query($sql);
		   $DaoName = $this->docContArr[$type];
		   $dao = new $DaoName();
		   $dao->updateOrderShipStatus_d($rows['relDocId']);
    }
    /**
     * ���ֳ��ⷴ������
     */
    function updateAsRedAutiAudit($rows){
    	  $type = $rows['contractType'];
    	  $exeNum = $rows['outNum'];
    	  $backNum = $rows['outNum']*(-1);
		  $sql = "update ".$this->docContItem[$type]." set executedNum = executedNum + ".$exeNum.",backNum = backNum + ".$backNum." where orderId = ".$rows['relDocId']." and id= ".$rows['relDocItemId']." ";
		  $this->_db->query($sql);
		  $DaoName = $this->docContArr[$type];
		  $dao = new $DaoName();
		  $dao->updateOrderShipStatus_d($rows['relDocId']);
    }
}
?>
