<?php
/*
 * Created on 2011-6-16
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 所有合同公共类
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
		$this->docModelArr = array (//不同策略类,根据需要在这里进行追加
			"oa_sale_order" => "model_projectmanagent_order_orderequ", //销售
			"oa_sale_lease" => "model_contract_rental_tentalcontractequ", //租赁
			"oa_sale_service" => "model_engineering_serviceContract_serviceequ", //服务合同
			"oa_sale_rdproject" => "model_rdproject_yxrdproject_rdprojectequ", //研发合同
			"oa_borrow_borrow" => "projectmanagent_borrow_borrowequ" ,//借用发货
			"oa_present_present" => "projectmanagent_present_presentequ" //赠送发货
		);

		$this->docContArr = array (//不同类型出库申请策略类,根据需要在这里进行追加
			"oa_sale_order" => "model_projectmanagent_order_order", //销售发货
			"oa_sale_lease" => "model_contract_rental_rentalcontract", //租赁出库
			"oa_sale_service" => "model_engineering_serviceContract_serviceContract", //服务合同出库
			"oa_sale_rdproject" => "model_rdproject_yxrdproject_rdproject", //研发合同出库
			"oa_borrow_borrow" => "model_projectmanagent_borrow_borrow" ,//借用发货
			"oa_present_present" => "model_projectmanagent_present_present" //赠送发货
		);
        $this->docContItem = array(//四种合同从表数据库名称
            "oa_sale_order" => "oa_sale_order_equ",
            "oa_sale_service" => "oa_service_equ",
            "oa_sale_lease" => "oa_lease_equ",
            "oa_sale_rdproject" => "oa_rdproject_equ"
		);
	}
    /**
	 * 根据合同ID 、类型  获取借试用转销售 物料
	 * @parm : $orderId -- 合同ID  $orderType -- 合同类型（表名）
	 */
    function getBorrwoToOrderequ($orderId,$orderType){
        $dao = new $this->docModelArr[$orderType];
        $equArr = $dao->find(array("orderId" => $orderId,"isBorrowToorder" => '1'));
        return $equArr;
    }

      /**
     * 根据合同类型获取四种合同内容
     */
    function orderInfo($orderId,$orderType) {
         $DaoName = $this->docContArr[$orderType];
         $InfoDao = new $DaoName();
         $rows = $InfoDao->get_d($orderId);
         return $rows;
    }

	/**
	 *根据不同的类型实例化对象
	 *
	 * @param tags
	 * @return return_type
	 */
	function newTypeDao_d ($type) {
		$contModel=$this->docModelArr[$type];
		return new $contModel();
	}
	/**
	 * 根据不同类型的单据的ID，修改单据从表的在途数量
	 * @param $onWayNum   传入的在途数量
	 * @param $parentId   物料表所关联的主表的ID
	 * @return $interface
	 */
	function setOnWayNum_d ($type,$parentId,$onWayNum) {
		$contTypeDao=$this->newTypeDao_d($type);
		$sql="update " . $contTypeDao->tbl_name . " set onWayNum=onWayNum+" . $onWayNum." where id=" . $parentId;
		$this->query($sql);
		return 1;
	}


	/**
	 * 在数据表中新增一行数据
	 *
	 * @param row 数组形式，数组的键是数据表中的字段名，键对应的值是需要新增的数据。
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
		if (FALSE != $this->_db->query ( $sql )) { // 获取当前新增的ID
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

//上传Excel并读取 excel数据
	function upExcelData($file, $filetempname) {
		//自己设置的上传文件存放路径
		$filePath = UPLOADPATH.'upfile/';
		if(!is_dir($filePath)){
			mkdir($filePath);
		}
		$str = "";

		//下面的路径按照你PHPExcel的路径来修改
		//set_include_path ( '.' . PATH_SEPARATOR . 'D:\EXCELDEMO' . PATH_SEPARATOR . get_include_path () );


		$filename = explode ( ".", $file ); //把上传的文件名以“.”好为准做一个数组。
		$time = date ( "y-m-d-H-i-s" ); //去当前上传的时间
		$filename [0] = $time; //取文件名替换
		$name = implode ( ".", $filename ); //上传后的文件名
		$uploadfile = $filePath . $name; //上传后的文件名地址


		//move_uploaded_file() 函数将上传的文件移动到新位置。若成功，则返回 true，否则返回 false。

		$result = move_uploaded_file ( $filetempname, $uploadfile ); //假如上传到当前目录下

		if ($result) //如果上传文件成功，就执行导入excel操作
{
			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
			$objPHPExcel = $objReader->load ( $uploadfile ); //   $objPHPExcel = $objReader->load($uploadfile);
			$sheet = $objPHPExcel->getSheet ( 0 );
			$highestRow = $sheet->getHighestRow (); // 取得总行数
			$highestColumn = $sheet->getHighestColumn (); // 取得总列数


			$dataArr = array (); //读取结果


//			//循环读取excel文件,读取一条,存取到数组一条
//			for($j = 2; $j <= $highestRow; $j ++) {
//				for($k = 'A'; $k <= $highestColumn; $k ++) {
//					$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ) . '\\'; //读取单元格
//				}
//				//explode:函数把字符串分割为数组。
//				$strs = explode ( "\\", $str );
//				array_push ( $dataArr, $strs );
//				$str = "";
//			}
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

			//循环读取excel文件,读取一条,存取到数组一条
			for($j = 2; $j <= $highestRow; $j ++) {
				$str = "";
				for($k = 0; $k <= $highestColumnIndex; $k ++) {
					$str .= iconv ( 'UTF-8', 'GBK', $sheet->getCellByColumnAndRow ( $k, $j )->getValue () ); //读取单元格
					if ($k != $highestColumnIndex) {
						$str .= '\\';
					}
				}

				$rowData = explode ( "\\", $str );
				$rowData['23']=$j;
				array_push ( $dataArr, $rowData );
			}

			//			unlink ( $uploadfile ); //删除上传的excel文件
			$msg = "读取成功！";
		} else {
			$msg = "读取失败！";
		}

		return $dataArr;
	}
	/**
	 * 取得文件扩展
	 *
	 * @param $filename 文件名
	 * @return 扩展名
	 */
	function fileext($filename) {
	    return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
	}

	function upExcelData_07($file, $filetempname) {
		//自己设置的上传文件存放路径
		$filePath = UPLOADPATH.'upfile/';
		if(!is_dir($filePath)){
			mkdir($filePath);
		}
		$str = "";

		//下面的路径按照你PHPExcel的路径来修改
		//set_include_path ( '.' . PATH_SEPARATOR . 'D:\EXCELDEMO' . PATH_SEPARATOR . get_include_path () );


		$filename = explode ( ".", $file ); //把上传的文件名以“.”好为准做一个数组。
		$time = date ( "y-m-d-H-i-s" ); //去当前上传的时间
		$filename [0] = $time; //取文件名替换
		$name = implode ( ".", $filename ); //上传后的文件名
		$uploadfile = $filePath . $name; //上传后的文件名地址


		//move_uploaded_file() 函数将上传的文件移动到新位置。若成功，则返回 true，否则返回 false。

		$result = move_uploaded_file ( $filetempname, $uploadfile ); //假如上传到当前目录下
		if ($result) //如果上传文件成功，就执行导入excel操作
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
			$highestRow = $sheet->getHighestRow (); // 取得总行数
			$highestColumn = $sheet->getHighestColumn (); // 取得总列数


			$dataArr = array (); //读取结果


//			//循环读取excel文件,读取一条,存取到数组一条
//			for($j = 2; $j <= $highestRow; $j ++) {
//				for($k = 'A'; $k <= $highestColumn; $k ++) {
//					$str .= iconv ( 'utf-8', 'gbk', $objPHPExcel->getActiveSheet ()->getCell ( "$k$j" )->getValue () ) . '\\'; //读取单元格
//				}
//				//explode:函数把字符串分割为数组。
//				$strs = explode ( "\\", $str );
//				array_push ( $dataArr, $strs );
//				$str = "";
//			}
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

			//循环读取excel文件,读取一条,存取到数组一条
			for($j = 2; $j <= $highestRow; $j ++) {
				$str = "";
				for($k = 0; $k <= $highestColumnIndex; $k ++) {
					$str .= iconv ( 'UTF-8', 'GBK', $sheet->getCellByColumnAndRow ( $k, $j )->getValue () ); //读取单元格
					if ($k != $highestColumnIndex) {
						$str .= '\\';
					}
				}

				$rowData = explode ( "\\", $str );
				$rowData['23']=$j;
				array_push ( $dataArr, $rowData );
			}

			//			unlink ( $uploadfile ); //删除上传的excel文件
			$msg = "读取成功！";
		} else {
			$msg = "读取失败！";
		}

		return $dataArr;
	}


	function getEqusForLock($id,$docType){
		$docEquDao=$this->docContArr[$docType];
	}

    /**
     * 蓝字出库审核处理方法
     */
    function updateAsOut($rows){
    	//暂时先注释 chengl
    	   $type = $rows['contractType'];
           $sql = "update oa_contract_equ set executedNum = executedNum + ".$rows['outNum']." where contractId = ".$rows['relDocId']." and id= ".$rows['relDocItemId']." ";
		   $this->_db->query($sql);
		   $DaoName = $this->docContArr[$type];
		   $dao = new $DaoName();
		   $dao->updateOrderShipStatus_d($rows['relDocId']);
    }
    /**
     * 蓝字出库反审处理方法
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
     * 红字出库审核处理方法
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
     * 红字出库反审处理方法
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
