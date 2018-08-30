<?php
/**
 * @author Administrator
 * @Date 2012年5月31日 17:03:17
 * @version 1.0
 * @description:考勤信息 Model层
 */
 class model_hr_personnel_attendance  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_attendance";
		$this->sql_map = "hr/personnel/attendanceSql.php";
		parent::__construct ();
		$this->status=array(
			'0'=>'草稿',
			'1'=>'开始（未处理）',
			'2'=>'处理中',
			'3'=>'已完成',
			'4'=>'退回',
			'5'=>'撤销'
		);
	}


	/**
	 * 员工盘点信息导入
	 */
	 function import_d($objKeyArr){
	 	try{
			set_time_limit(0);
	 		$this->start_d();
	 		$returnFlag = true;
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
				unset($excelData[0]);
				$errorCodeArr = array();
				if ($excelData) {
					$codeRuleDao = new model_common_codeRule();
					$userDao = new model_deptuser_user_user();
					$deptDao = new model_deptuser_dept_dept();
					foreach ( $excelData as $rNum => $row ) {
						foreach ( $objKeyArr as $index => $fieldName ) {
							//将值赋给对应的字段
							$objectArr [$rNum] [$fieldName] = $row [$index];
						}
					}
					$HRdatadict = $this->getDatadicts('HRQJLX');
					foreach ($objectArr as $key=>$val){
						//格式化编码，删除多余的空格。如果编码为空，则该条数据插入无效。
						$objectArr[$key]['userNo'] = str_replace( ' ','',$val['userNo']);
						$objectArr[$key]['userName'] = str_replace( ' ','',$val['userName']);
						if( empty($val['userNo']) && empty($val['userName']) && empty($val['days'])
							&& empty($val['beginDate']) && empty($val['endDate']) && empty($val['docStatusName'])){
							unset($objectArr[$key]);
							continue;
						}else if( empty($val['userNo']) || empty($val['userName']) || empty($val['days'])
							|| empty($val['beginDate']) || empty($val['endDate'])|| empty($val['docStatusName'])){
							$errorCodeArr[$key]['docCode']=$key+2;
							$errorCodeArr[$key]['result']='必填项为空，导入失败';
							unset($objectArr[$key]);
							continue;
						}else{
							$userId = $codeRuleDao->getUserIdByCard($val['userNo']);
							$inputId = $codeRuleDao->getUserIdByCard($val['inputNo']);
							if( empty($userId) ){
								$errorCodeArr[$key]['docCode']=$key+2;
								$errorCodeArr[$key]['result']='员工编码错误，导入失败';
								unset($objectArr[$key]);
								continue;
							}else{
								$userInfo = $userDao->getUserById($userId);
								$userName = $userInfo['USER_NAME'];
								if( $userName!=$val['userName'] ){
									$errorCodeArr[$key]['docCode']=$key+2;
									$errorCodeArr[$key]['result']='员工编码与名称不对应，导入失败';
									unset($objectArr[$key]);
									continue;
								}else{
									$objectArr[$key]['userAccount']=$userId;
									$objectArr[$key]['companyName']=$userInfo['Company'];
								}
							}
							if( !empty($val['deptNameS']) ){
								$deptIdSArr = $deptDao->getDeptId_d($val['deptNameS']);
								if($deptIdSArr['DEPT_ID']){
									$objectArr[$key]['deptIdS']=$deptIdSArr['DEPT_ID'];
								}else{
									$errorCodeArr[$key]['docCode']=$key+2;
									$errorCodeArr[$key]['result']='二级部门不存在，导入失败';
									unset($objectArr[$key]);
									continue;
								}
							}
							if( !empty($val['deptNameT']) ){
								$deptIdTArr = $deptDao->getDeptId_d($val['deptNameT']);
								if($deptIdTArr['DEPT_ID']){
									$objectArr[$key]['deptIdT']=$deptIdTArr['DEPT_ID'];
								}else{
									$errorCodeArr[$key]['docCode']=$key+2;
									$errorCodeArr[$key]['result']='三级部门不存在，导入失败';
									unset($objectArr[$key]);
									continue;
								}
							}
							if( empty($inputId) ){
								$errorCodeArr[$key]['docCode']=$key+2;
								$errorCodeArr[$key]['result']='制单人编码错误，导入失败';
								unset($objectArr[$key]);
								continue;
							}else{
								$inputInfo = $userDao->getUserById($inputId);
								$inputName = $inputInfo['USER_NAME'];
								if( $inputName!=$val['inputName'] ){
									$errorCodeArr[$key]['docCode']=$key+2;
									$errorCodeArr[$key]['result']='制单人编码与名称不对应，导入失败';
									unset($objectArr[$key]);
									continue;
								}else{
									$objectArr[$key]['inputId']=$inputId;
								}
							}
							foreach ( $this->status as $k=>$v ){
								if($val['docStatusName']==$v){
									$objectArr[$key]['docStatus']=$key;
								}
							}
							foreach ( $HRdatadict as $index=>$row ){
								if($row['dataName']==$val['typeName']){
									$objectArr[$key]['typeCode']=$row['dataCode'];
								}
							}
							//盘点日期
							$beginDate = mktime(0, 0, 0, 1, $objectArr[$key]['beginDate'] - 1, 1900);
							$objectArr[$key]['beginDate'] = date("Y-m-d", $beginDate);
							//入职日期
							$endDate = mktime(0, 0, 0, 1, $objectArr[$key]['endDate'] - 1, 1900);
							$objectArr[$key]['endDate'] = date("Y-m-d", $endDate);
						}
					}
					if( count($errorCodeArr)>0 ){
				 		$returnFlag = false;
						$title = '考勤信息导入结果';
						$thead = array( '行号','结果' );
						echo "<script>alert('导入失败')</script>";
						echo util_excelUtil::showResult($errorCodeArr,$title,$thead);
					}else{
						$this->saveDelBatch($objectArr);
						echo "<script>alert('导入成功');self.parent.tb_remove();if(self.parent.show_page)self.parent.show_page(1);</script>";
					}
				} else {
					msg( "文件不存在可识别数据!");
				}
			} else {
				msg( "上传文件类型不是EXCEL!");
			}
	 		$this->commit_d();
	 		return $returnFlag;
	 	}catch(Exception $e){
	 		$this->rollBack();
	 		return 0;
	 	}
	}



 }
?>