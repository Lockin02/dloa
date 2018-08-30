<?php

/**
 *
 * 折旧方式model
 * @author fengxw
 *
 */
class model_asset_basic_deprMethod extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_deprMethod";
		$this->sql_map = "asset/basic/deprMethodSql.php";
		parent::__construct ();
	}

	/**
	 * 折旧方式导入
	 */
	 function import_d($objKeyArr){
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();
		$objectArr = array();
		$excelData = array ();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			//判断是否传入的是有效数据
			echo "<pre>";
			print_R($excelData);
			if ($excelData) {
				foreach ($excelData as $key=>$val){
					//将里程碑计划名称和任务名称格式化，删除多余的空格。如果任务名为空，则该条数据插入无效。
					$excelData[$key][0] = str_replace( ' ','',$val[0]);
					if( $excelData[$key][0] == '' ){
						unset( $excelData[$key] );
					}
				}
				foreach ( $excelData as $rNum => $row ) {
					foreach ( $objKeyArr as $index => $fieldName ) {
						//将值赋给对应的字段
						$objectArr [$rNum] [$fieldName] = $row [$index];
					}
				}
				echo "<pre>";
				print_R($objectArr);
//				foreach( $objectArr as $key=>$val ){
//					$condition = array(
//						'mailNo' => $objectArr[$key]['mailNo']
//					);
//					$rows = array(
//						'number' => $objectArr[$key]['number'],
//						'weight' => $objectArr[$key]['weight'],
//						'serviceType' => $objectArr[$key]['serviceType'],
//						'fare' => $objectArr[$key]['fare'],
//						'anotherfare' => $objectArr[$key]['anotherfare'],
//						'mailMoney' => $objectArr[$key]['mailMoney']
//					);
//					$this->update( $condition,$rows );
//					$tempArr['docCode']=$val['mailNo'];
//					if ($this->_db->affected_rows () == 0) {
//						$tempArr['result']='导入失败！（单号不存在或数据无效）';
//					}else{
//						$tempArr['result']='导入成功！';
//					}
//					array_push( $resultArr,$tempArr );
//				}
//				return $resultArr;
			} else {
				msg( "文件不存在可识别数据!");
			}
		} else {
			msg( "上传文件类型不是EXCEL!");
		}

	}

}
?>
