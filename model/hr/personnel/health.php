<?php

 include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";
/**
 * @author Administrator
 * @Date 2012年6月22日 星期五 11:09:55
 * @version 1.0
 * @description:人事管理-基础信息-健康信息 Model层
 */
 class model_hr_personnel_health  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_health";
		$this->sql_map = "hr/personnel/healthSql.php";
		parent::__construct ();
	}

		/******************* S 导入导出系列 ************************/
	function addExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
		$inArr = array();//插入数组
		$userArr = array();//用户数组
		$deptArr = array();//部门数组
		$jobsArr = array();//职位数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
//		$datadictArr = array();//数据字典数组
//		$datadictDao = new model_system_datadict_datadict();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
			if(is_array($excelData)){

				//行数组循环
				foreach($excelData as $key => $val){
//					echo "<pre>";
//					print_r($val);
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])){
						continue;
					}else{
						//新增数组
						$inArr = array();

						//员工编号
						if(!empty($val[0])){
							if(!isset($userArr[$val[0]])){
								$rs = $otherDataDao->getUserInfoByUserNo(trim($val[0]));
								if(!empty($rs)){
									$userConutArr[$val[0]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!不存在的员工编号</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}

							$inArr['userAccount'] = $userConutArr[$val[0]]['USER_ID'];
							$inArr['userNo'] = trim($val[0]);
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<font color=red>导入失败!没有员工编号</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//员工姓名
						if(!empty($val[1])){
							if(!isset($userArr[$val[1]])){
								$rs = $otherDataDao->getUserInfo(trim($val[1]));
								if(!empty($rs)){
									$userArr[$val[1]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!不存在的员工姓名</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['userName'] = trim($val[1]);
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<font color=red>导入失败!没有员工姓名</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//检查医院
						if(!empty($val[2])){
							$inArr['hospital'] = $val[2];
						}

						//检查时间
						if(!empty($val[3])){
							$inArr['checkDate'] = $val[3];
						}

						//检查结果
						if(!empty($val[4])){
							$inArr['checkResult'] = $val[4];
						}

						//内容
						if(!empty($val[5])){
							$inArr['remark'] = $val[5];
						}

						//医院意见
						if(!empty($val[6])){
							$inArr['hospitalOpinion'] = $val[6];
						}




//						print_r($inArr);
						$newId = $this->add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '导入成功';
						}else{
							$tempArr['result'] = '<font color=red>导入失败</font>';
						}
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}


	/*
	 * 导出excel
	 */
	 function excelOut($rowdatas){
			PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//创建一个Excel工作流
		//		$objPhpExcelFile = new PHPExcel();


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/人资-健康信息导入模板.xls" ); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '信息列表' ) );
		//设置表头及样式 设置
		$i = 2;
		if (! count ( array_filter ( $rowdatas ) ) == 0) {
			$row = $i;
			for($n = 0; $n < count ( $rowdatas ); $n ++) {
				$m = 0;
				foreach ( $rowdatas [$n] as $field => $value ) {
					$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m ++;
				}
				$i ++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'O' . $i );
			for($m = 0; $m < 10; $m ++) {
				$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
		}

		//到浏览器
		ob_end_clean (); //解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "健康信息导出报表.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
		}

	/******************* E 导入导出系列 ************************/
 }
?>