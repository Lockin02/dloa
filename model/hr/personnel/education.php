<?php
/**
 * @author Administrator
 * @Date 2012年5月25日 星期五 15:11:48
 * @version 1.0
 * @description:人事管理-基础信息-教育经历 Model层
 */

 include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

 class model_hr_personnel_education  extends model_base {



	function __construct() {
		$this->tbl_name = "oa_hr_personnel_education";
		$this->sql_map = "hr/personnel/educationSql.php";
		parent::__construct ();
	}

	/**
	 * 重写add
	 */
	function add_d($object){
        //处理数据字典字段
		$datadictDao = new model_system_datadict_datadict ();
		$object ['educationName'] =  $datadictDao->getDataNameByCode ( $object['education'] );
		$id=parent::add_d($object,true);

		//更新附件关联关系
		$this->updateObjWithFile ( $id );

		//附件处理
		if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
			$uploadFile = new model_file_uploadfile_management ();
			$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
		}
		return $id;
	}

	/**
	 * 重写add
	 */
	function edit_d($object){

        //处理数据字典字段
		$datadictDao = new model_system_datadict_datadict ();
		$object ['educationName'] =  $datadictDao->getDataNameByCode ( $object['education'] );

		return parent::edit_d($object,true);
	}

/**
	 * 根据用户账号获取信息
	 *
	 */
	 function getInfoByUserNo_d($userNoArr){
		$this->searchArr = array ('userNoArr' => $userNoArr );
		$this->__SET('sort', 'c.userNo');
		$rows= $this->listBySqlId ( "select_default" );
		return $rows;
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
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//行数组循环
				foreach($excelData as $key => $val){
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
//							$inArr['deptId'] = $userConutArr[$val[0]]['DEPT_ID'];
//							$inArr['deptName'] = $userConutArr[$val[0]]['DEPT_NAME'];
							$inArr['userNo'] = $val[0];
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
							$inArr['userName'] = $val[1];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<font color=red>导入失败!没有员工姓名</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//学校
						if(!empty($val[2])){
							$inArr['organization'] = $val[2];
						}

						//专业
						if(!empty($val[3])){
							$inArr['content'] = $val[3];
						}

						//学历
						if(!empty($val[4])){
							$val[4]= trim($val[4]);
								$rs = $datadictDao->getCodeByName('HRJYXL',$val[4]);
								if(!empty($rs)){
									$educationCode = $rs;
								}else{
									$educationCode="";
									$val[4]="1111";
								}
								$inArr['educationName'] = trim($val[4]);
								$inArr['education'] = $educationCode;
						}

						//证书
						if(!empty($val[5])){
							$inArr['certificate'] = $val[5];
						}

						//开始时间
						if(!empty($val[6])&& $val[6] != '0000-00-00'){
							$val[6] = trim($val[6]);

							if(!is_numeric($val[6])){
								$inArr['beginDate'] = $val[6];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[6] - 1 , 1900)));
								$inArr['beginDate'] = $recorderDate;
							}
						}

						//结束时间
						if(!empty($val[7])&& $val[7] != '0000-00-00'){
							$val[7] = trim($val[7]);

							if(!is_numeric($val[7])){
								$inArr['closeDate'] = $val[7];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[7] - 1 , 1900)));
								$inArr['closeDate'] = $recorderDate;
							}
						}

						//备注
						if(!empty($val[9])){
							$inArr['remark'] = $val[9];
						}

//						print_r($inArr);
						$newId = $this->add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '新增成功';
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

		/*
		 * 导出excel
		 */

	}

		function excelOut($rowdatas){
			PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//创建一个Excel工作流
		//		$objPhpExcelFile = new PHPExcel();


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/人资-教育经历导入模版.xls" ); //读取模板
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
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'J' . $i );
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
		header ( 'Content-Disposition:inline;filename="' . "教育经历导出报表.xls" . '"' );
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