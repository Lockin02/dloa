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
 * @Date 2012年5月30日 14:38:15
 * @version 1.0
 * @description:员工盘点表 Model层
 */
 class model_hr_invent_inventory  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_inventory";
		$this->sql_map = "hr/invent/inventorySql.php";
		parent::__construct ();
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
					foreach ( $excelData as $rNum => $row ) {
						foreach ( $objKeyArr as $index => $fieldName ) {
							//将值赋给对应的字段
							$objectArr [$rNum] [$fieldName] = $row [$index];
						}
					}
					foreach ($objectArr as $key=>$val){
						//格式化编码，删除多余的空格。如果编码为空，则该条数据插入无效。
						$objectArr[$key]['userNo'] = str_replace( ' ','',$val['userNo']);
						$objectArr[$key]['userName'] = str_replace( ' ','',$val['userName']);
						if( empty($val['userNo']) && empty($val['userName'])
							&& empty($val['inventoryDate']) && empty($val['entryDate'])) {
							unset($objectArr[$key]);
							continue;
						}else if( empty($val['userNo']) || empty($val['userName'])
							|| empty($val['inventoryDate']) || empty($val['entryDate'])) {
							$errorCodeArr[$key]['docCode']=$key+2;
							$errorCodeArr[$key]['result']='必填项为空，导入失败';
							unset($objectArr[$key]);
							continue;
						} else {
							$codeRuleDao = new model_common_codeRule();
							$userDao = new model_deptuser_user_user();
							$userId = $codeRuleDao->getUserIdByCard($val['userNo']);
							if( empty($userId) ){
								$errorCodeArr[$key]['docCode']=$key+2;
								$errorCodeArr[$key]['result']='员工编码错误，导入失败';
								unset($objectArr[$key]);
								continue;
							} else {
								$userInfo = $userDao->getUserById($userId);
								$userName = $userInfo['USER_NAME'];
								if( $userName!=$val['userName'] ){
									$errorCodeArr[$key]['docCode']=$key+2;
									$errorCodeArr[$key]['result']='员工编码与名称不对应，导入失败';
									unset($objectArr[$key]);
									continue;
								} else {
									$objectArr[$key]['userAccount']=$userId;
									$objectArr[$key]['companyName']=$userInfo['Company'];
								}
							}
							if( empty($val['deptNameS']) ){
								$deptIdSArr = $deptDao->getDeptId_d($val['deptNameS']);
								if($deptIdSArr['DEPT_ID']){
									$objectArr[$key]['deptIdS']=$deptIdSArr['DEPT_ID'];
								} else {
									$errorCodeArr[$key]['docCode']=$key+2;
									$errorCodeArr[$key]['result']='部门不存在，导入失败';
									unset($objectArr[$key]);
									continue;
								}
							}
							//盘点日期
							$inventoryDate = mktime(0, 0, 0, 1, $objectArr[$key]['inventoryDate'] - 1, 1900);
							$objectArr[$key]['inventoryDate'] = date("Y-m-d", $inventoryDate);
							//入职日期
							$entryDate = mktime(0, 0, 0, 1, $objectArr[$key]['entryDate'] - 1, 1900);
							$objectArr[$key]['entryDate'] = date("Y-m-d", $entryDate);
						}
					}
					if( count($errorCodeArr)>0 ){
				 		$returnFlag = false;
						$title = '员工盘点信息导入结果';
						$thead = array( '行号','结果' );
						echo "<script>alert('导入失败')</script>";
						echo util_excelUtil::showResult($errorCodeArr,$title,$thead);
					} else {
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

	function addExecelData_d(){
		ini_set('memory_limit', '-1');
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
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {
				$newData = array(); //转存excel数据
				foreach ($excelData as $key => $val) {
					$newData[$key]['userNo'] = $val[0];
					$newData[$key]['userName'] = $val[1];
					$newData[$key]['deptNameS'] = $val[2];
					$newData[$key]['position'] = $val[3];
					$newData[$key]['inventoryDate'] = $val[4];
					$newData[$key]['entryDate'] = $val[5];
					$newData[$key]['alternative'] = $val[6];
					$newData[$key]['matching'] = $val[7];
					$newData[$key]['isCritical'] = $val[8];
					$newData[$key]['critical'] = $val[9];
					$newData[$key]['isCore'] = $val[10];
					$newData[$key]['recruitment'] = $val[11];
					$newData[$key]['performance'] = $val[12];
					$newData[$key]['examine'] = $val[13];
					$newData[$key]['preEliminated'] = $val[14];
					$newData[$key]['remark'] = $val[15];
					$newData[$key]['adjust'] = $val[16];
					$newData[$key]['workQuality'] = $val[17];
					$newData[$key]['workEfficiency'] = $val[18];
					$newData[$key]['workZeal'] = $val[19];
				}

				//行数组循环
				foreach($newData as $key => $val){
					$actNum = $key + 2;
					if(empty($val['userNo']) && empty($val['deptNameS'])) {
						continue;
					} else {
						//新增数组
						$inArr = array();

						//员工编号
						if(!empty($val['userNo'])) {
							if(!isset($userArr[$val['userNo']])) {
								$rs = $otherDataDao->getUserInfoByUserNo(trim($val['userNo']));
								if(!empty($rs)) {
									$inArr['userAccount'] = $rs['USER_ID'];
									$inArr['userNo'] = trim($val['userNo']);
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!不存在的员工编号</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!没有员工编号</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//员工姓名
						if(!empty($val['userName'])) {
							if(!isset($userArr[$val['userName']])) {
								$rs = $otherDataDao->getUserInfo(trim($val['userName']));
								if(!empty($rs)) {
									$inArr['userName'] = trim($val['userName']);
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!不存在的员工姓名</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!没有员工姓名</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//部门
						if(!empty($val['deptNameS']) && trim($val['deptNameS']) != '') {
							$val['deptNameS'] = trim($val['deptNameS']);
							if(!isset($deptArr[$val['deptNameS']])) {
								$deptIdS = $otherDataDao->getDeptId_d($val['deptNameS']);
								$inArr['deptNameS'] = $val['deptNameS'];
								$inArr['deptIdS'] = $deptIdS;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有部门</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//员工职位
						if(!empty($val['position']) && trim($val['position']) != '') {
							$val['position'] = trim($val['position']);
							if(!isset($jobsArr[$val['position']])) {
								$jobId = $otherDataDao->getJobId_d($val['position']);
								if(empty($jobId)) {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的员工职位</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['position'] = $val['position'];
						}

						//盘点日期
						if(!empty($val['inventoryDate']) && trim($val['inventoryDate']) != '') {
							$val['inventoryDate'] = trim($val['inventoryDate']);
							if(!is_numeric($val['inventoryDate'])) {
								$inArr['inventoryDate'] = $val['inventoryDate'];
							} else {
								$beginDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['inventoryDate'] - 1 ,1900)));
								if($beginDate == '1970-01-01') {
									$inventoryDate1 = date('Y-m-d',strtotime($val['inventoryDate']));
									$inArr['inventoryDate'] = $inventoryDate1;
								} else {
									$inArr['inventoryDate'] = $beginDate;
								}
							}
						}

						//入职日期
						if(!empty($val['entryDate']) && trim($val['entryDate']) != '') {
							$val['entryDate'] = trim($val['entryDate']);
							if(!is_numeric($val['entryDate'])) {
								$inArr['entryDate'] = $val['entryDate'];
							} else {
								$beginDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['entryDate'] - 1 ,1900)));
								if($beginDate == '1970-01-01') {
									$entryDate1 = date('Y-m-d' ,strtotime($val['entryDate']));
									$inArr['entryDate'] = $entryDate1;
								} else {
									$inArr['entryDate'] = $beginDate;
								}
							}
						}

						//此职位的市场可替代性
						if(!empty($val['alternative'])) {
							$val['alternative'] = trim($val['alternative']);

							if($val['alternative'] == '高' || $val['alternative'] == '中' || $val['alternative'] == '低') {
								$inArr['alternative'] = $val['alternative'];
							} else {
								$inArr['alternative'] = '';
							}
						}

						//现工作能力与现在职位的匹配度
						if(!empty($val['matching'])) {
							$val['matching'] = trim($val['matching']);

							if($val['matching'] == '高' || $val['matching'] == '中' || $val['matching'] == '低') {
								$inArr['matching'] = $val['matching'];
							} else {
								$inArr['matching'] = '';
							}
						}

						//是否关键员工
						if(!empty($val['isCritical'])) {
							$val['isCritical'] = trim($val['isCritical']);

							if($val['isCritical'] == '是' || $val['isCritical'] == '否') {
								$inArr['isCritical'] = $val['isCritical'];
							} else {
								$inArr['isCritical'] = '';
							}
						}

						//员工关键性
						if(!empty($val['critical'])) {
							$val['critical'] = trim($val['critical']);

							if($val['critical'] == '高' || $val['critical'] == '中' || $val['critical'] == '低') {
								$inArr['critical'] = $val['critical'];
							} else {
								$inArr['critical'] = '';
							}
						}

						//是否为核心保留人才
						if(!empty($val['isCore'])) {
							$val['isCore'] = trim($val['isCore']);

							if($val['isCore'] == '是' || $val['isCore'] == '否') {
								$inArr['isCore'] = $val['isCore'];
							} else {
								$inArr['isCore'] = '';
							}
						}

						//此职位的市场招聘难度
						if(!empty($val['recruitment'])) {
							$val['recruitment'] = trim($val['recruitment']);

							if($val['recruitment'] == '高' || $val['recruitment'] == '中' || $val['recruitment'] == '低') {
								$inArr['recruitment'] = $val['recruitment'];
							} else {
								$inArr['recruitment'] = '';
							}
						}

						//对绩效达成情况的评价
						if(!empty($val['performance'])) {
							$val['performance'] = trim($val['performance']);

							if($val['performance'] == '高' || $val['performance'] == '中' || $val['performance'] == '低') {
								$inArr['performance'] = $val['performance'];
							} else {
								$inArr['performance'] = '';
							}
						}

						//上一季度考核是否排后5%
						if(!empty($val['examine'])) {
							$val['examine'] = trim($val['examine']);

							if($val['examine'] == '是' || $val['examine'] == '否') {
								$inArr['examine'] = $val['examine'];
							} else {
								$inArr['examine'] = '';
							}
						}

						//是否为预淘汰人员
						if(!empty($val['preEliminated'])) {
							$val['preEliminated'] = trim($val['preEliminated']);

							if($val['preEliminated'] == '是' || $val['preEliminated'] == '否') {
								$inArr['preEliminated'] = $val['preEliminated'];
							} else {
								$inArr['preEliminated'] = '';
							}
						}

						//是否有可能流失以及原因
						if(!empty($val['remark'])) {
							$inArr['remark'] = $val['remark'];
						}

						//对此员工的后续调整方向
						if(!empty($val['adjust'])) {
							$inArr['adjust'] = $val['adjust'];
						}

						//工作质量
						if(!empty($val['workQuality'])) {
							$inArr['workQuality'] = $val['workQuality'];
						}

						//工作效率
						if(!empty($val['workEfficiency'])) {
							$inArr['workEfficiency'] = $val['workEfficiency'];
						}

						//工作热情
						if(!empty($val['workZeal'])) {
							$inArr['workZeal'] = $val['workZeal'];
						}

						$newId = parent::add_d($inArr ,true);

						if($newId) {
							$tempArr['result'] = '导入成功';
						} else {
							$tempArr['result'] = '<font color=red>导入失败</font>';
						}
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						array_push($resultArr ,$tempArr);
					}
				}
				return $resultArr;
			}
		}
	}

 }
?>