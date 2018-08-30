<?php

/**
 * @author Show
 * @Date 2012年5月30日 星期三 14:02:31
 * @version 1.0
 * @description:培训管理-课程详细记录 Model层
 */
class model_hr_training_trainingrecords extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_training_course_records";
		$this->sql_map = "hr/training/trainingrecordsSql.php";
		parent :: __construct();
	}

	//返回是否内训师
	function rtHandStatus_d($val) {
		if($val == 1) {
			return '已提交';
		}else if($val == 2) {
			return '不需提交';
		}else{
			return '未提交';
		}
	}

	//返回内训还是外训
	function rtIsInner_d($val) {
		if($val == 1) {
			return '内训';
		}else if($val == 2) {
			return '内训-外聘';
		}else{
			return '外训';
		}
	}

	/**
	 * 编辑增员申请信息
	 */
	 function edit_d($object) {
	 	try{
			$this->start_d();
			$dictDao = new model_system_datadict_datadict();
			$object['addMode'] = $dictDao->getDataNameByCode($object['status']);
			$object['assessmentName'] = $dictDao->getDataNameByCode($object['assessment']);
			$object['trainsTypeName'] = $dictDao->getDataNameByCode($object['trainsType']);
			$object['trainsMethod'] = $dictDao->getDataNameByCode($object['trainsMethodCode']);
			$id = parent::edit_d($object ,true);
			$this->commit_d();
			return $id;
		 }catch(Exception $e) {
			$this->rollBack();
			return $id;
		}
 	}

 	/**根据课程名称，培训讲师，授课日期，授课结束日期，查询参训人
	 * @author Administrator
	 *
	 */
	 function findMemberByRecords($courseName,$teacherName,$teachDate,$teachEndDate) {
		$this->searchArr = array ('courseName' => $courseName,'teacherName' => $teacherName,'beginDate' => $teachDate,'endDate' => $teachEndDate);
		$rows= $this->listBySqlId ( "select_default" );
		$userAccountArr=array();
		$userNameArr=array();
		$returnArr=array();
		if(is_array($rows)) {
			foreach($rows as $key=>$val) {
				$userAccountArr[$key]=$val['userAccount'];
				$userNameArr[$key]=$val['userName'];
			}
			$returnArr['userAccount']=implode(',',$userAccountArr);
			$returnArr['userName']=implode(',',$userNameArr);
			return $returnArr;
		}
	 }

	/******************* S 导入导出系列 ************************/
	function addExecelData_d() {
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
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		$courseArr = array();//课程数组
		$courseDao = new model_hr_training_course();
		$courseIdArr = array();
		$teacherArr = array();//教师数组
		$teacherDao = new model_hr_training_teacher();//讲师类
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");

			if(is_array($excelData)) {

				//行数组循环
				foreach($excelData as $key => $val) {

					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])&& empty($val[5])) {
						continue;
					}else{
						//新增数组
						$inArr = array();

						//导师员工编号
						if(!empty($val[0])) {
							if(!isset($userArr[$val[0]])) {
								$rs = $otherDataDao->getUserInfoByUserNo($val[0]);
								if(!empty($rs)) {
									$userConutArr[$val[0]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的员工编号</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}

							$inArr['userAccount'] = $userConutArr[$val[0]]['USER_ID'];
							$inArr['deptId'] = $userConutArr[$val[0]]['DEPT_ID'];
							$inArr['deptName'] = $userConutArr[$val[0]]['DEPT_NAME'];
							$inArr['userNo'] = $val[0];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有员工编号</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//员工姓名
						if(!empty($val[1])) {
							$inArr['userName'] = $val[1];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有员工姓名</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//部门
						if(!empty($val[2])) {
							$val[2] = trim($val[2]);
							if(!isset($deptArr[$val[2]])) {
								$rs = $otherDataDao->getDeptId_d($val[2]);
								if(!empty($rs)) {
									$inArr['deptId'] = $rs;
								}
							}
							$inArr['deptName'] = $val[2];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有部门</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//员工职位
						if(!empty($val[3]) && trim($val[3]) != '') {
							$val[3] = trim($val[3]);
							if(!isset($jobsArr[$val[3]])) {
								$rs = $otherDataDao->getJobId_d($val[3]);
								if(!empty($rs)) {
									$jobsArr[$val[3]] = $rs;
								}else{
									$jobsArr[$val[3]] = array('belongDeptId' => '');
								}
							}
							$inArr['jobName'] = $val[3];
							$inArr['jobId'] = $jobsArr[$val[3]];
						}

						//课程编号
						if(!empty($val[4]) && trim($val[4]) != '') {
							$inArr['courseCode'] = $val[4];
						}

						//课程名称
						if(!empty($val[5]) && trim($val[5]) != '') {
							$inArr['courseName'] = $val[5];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有填写课程名称</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//时长
						if(!empty($val[6])) {
							$inArr['duration'] = $val[6];
						}

						//培训类型
						if(!empty($val[7]) && trim($val[7]) != '') {
							$val[7] = trim($val[7]);
							if(!isset($datadictArr[$val[7]])) {
								$rs = $datadictDao->getCodeByName('HRPXLX',$val[7]);
								if(!empty($rs)) {
									$trainsType = $datadictArr[$val[7]]['code'] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的培训类型</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							} else {
								$trainsType = $datadictArr[$val[7]]['code'];
							}
							$inArr['trainsType'] = $trainsType;
							$inArr['trainsTypeName'] = $val[7];
						}

						//培训方式
						if(!empty($val[8]) && trim($val[8]) != '') {
							$val[8] = trim($val[8]);
							if(!isset($datadictArr[$val[8]])) {
								$rs = $datadictDao->getCodeByName('HRPXFS',$val[8]);
								if(!empty($rs)) {
									$trainsMethodCode = $datadictArr[$val[8]]['code'] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的培训方式</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$trainsMethodCode = $datadictArr[$val[8]]['code'];
							}
							$inArr['trainsMethodCode'] = $trainsMethodCode;
							$inArr['trainsMethod'] = $val[8];
						}

						//组织部门
						if(!empty($val[9])) {
							$val[9] = trim($val[9]);
							if(!isset($deptArr[$val[9]])) {
								$rs = $otherDataDao->getDeptId_d($val[9]);
								if(!empty($rs)) {
									$deptArr[$val[9]] = $rs;
								}
							}
							$inArr['orgDeptName'] = $val[9];
							$inArr['orgDeptId'] = $deptArr[$val[9]];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有组织部门</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//培训月份
						if(!empty($val[10]) && trim($val[10])!='') {
							if(!is_numeric($val[10])) {
								$inArr['trainsMonth'] = $val[10];
							} else {
								$year = date('Y',(mktime(0 ,0 ,0 ,1 ,$val[10] - 1 ,1900)));
								$month = date('n',(mktime(0 ,0 ,0 ,1 ,$val[10] - 1 ,1900)));
								if($year == '1970' && $month == '1') {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!培训月份格式不正确</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
								$inArr['trainsMonth'] = $year.'年'.$month.'月';
							}
						}

						//培训开始时间
						if(!empty($val[11]) &&  $val[11] != '0000-00-00' && trim($val[11]) != '') {
							$val[11] = trim($val[11]);
							if(!is_numeric($val[11])) {
								$inArr['beginDate'] = $val[11];
							} else {
								$beginDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val[11] - 1 ,1900)));
								if($beginDate == '1970-01-01') {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!开始时间格式不正确</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
								$inArr['beginDate'] = $beginDate;
							}
						}

						//培训结束时间
						if(!empty($val[12]) &&  $val[12] != '0000-00-00' && trim($val[12]) != '') {
							$val[12] = trim($val[12]);
							if(!is_numeric($val[12])) {
								$inArr['endDate'] = $val[12];
							}else{
								$endDate = date('Y-m-d',(mktime(0,0,0,1, $val[12] - 1 , 1900)));
								if($endDate=='1970-01-01') {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!结束时间格式不正确</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
								$inArr['endDate'] = $endDate;
							}
						}

						//培训次数
						if(!empty($val[13]) && trim($val[13])!='') {
							$inArr['trainsNum'] = $val[13];
						}

						//培训机构
						if(!empty($val[14]) && trim($val[14])!='') {
							$inArr['agency'] = $val[14];
						}

						//地址
						if(!empty($val[15]) && trim($val[15])!='') {
							$inArr['address'] = $val[15];
						}

						//培训讲师
						if(!empty($val[16]) && trim($val[16])!='') {
							$val[16] = trim($val[16]);
							if(!isset($teacherArr[$val[16]])) {
								$rs = $teacherDao->find(array('teacherName' => $val[16]));
								if(empty($rs)) {
									//如果是空，构造课程数组并导入
									$teacherArr[$val[16]]['teacherName'] = $val[11];

									if(!isset($userArr[$val[16]])) {
										$rs = $otherDataDao->getUserInfo($val[16]);
										if(!empty($rs)) {
											$userArr[$val[16]] = $rs;
										}else{
											$userArr[$val[16]] = array('USER_ID' => '');
										}
									}
									$inArr['teacherName'] = $val[16];
									$inArr['teacherAccount'] = $userArr[$val[16]]['USER_ID'];
								}else{
									$teacherArr[$val[16]] = $rs;
								}
							}

							$inArr['teacherName'] = $val[16];
							$inArr['teacherId'] = $teacherArr[$val[16]]['id'];
						}

						//培训费用
						if(!empty($val[17]) && trim($val[17]) != '') {
							$inArr['fee'] = $val[17];
						}

						//状态
						if(!empty($val[18]) && trim($val[18]) != '') {
							$val[18] = trim($val[18]);
							if(!isset($datadictArr[$val[18]])) {
								$rs = $datadictDao->getCodeByName('HRPXZT',$val[18]);
								if(!empty($rs)) {
									$status = $datadictArr[$val[18]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的培训状态</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$status = $datadictArr[$val[18]]['code'];
							}
							$inArr['status'] = $status;
						}

						//考核类型
						if(!empty($val[19]) && trim($val[19]) != '') {
							$val[19] = trim($val[19]);
							if(!isset($datadictArr[$val[19]])) {
								$rs = $datadictDao->getCodeByName('HRPXKH',$val[19]);
								if(!empty($rs)) {
									$assessment = $datadictArr[$val[19]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的考核类型</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$assessment = $datadictArr[$val[19]]['code'];
							}
							$inArr['assessment'] = $assessment;
							$inArr['assessmentName'] = $val[19];
						}

						//考核成绩
						if(!empty($val[20]) && trim($val[20]) != '') {
							$inArr['assessmentScore'] = $val[20];
						}

						//课程评估分数
						if(!empty($val[21]) && trim($val[21]) != '') {
							$inArr['courseEvaluateScore'] = $val[21];
						}

						//培训组织评估分数
						if(!empty($val[22]) && trim($val[22]) != '') {
							$inArr['trainsOrgEvaluateScore'] = $val[22];
						}

						//效果及绩效跟进时间
						if(!empty($val[23]) && trim($val[23]) != '') {
							$val[23] = trim($val[23]);
							if(!is_numeric($val[23])) {
								$inArr['followTime'] = $val[23];
							} else {
								$followTime = date('Y-m-d',(mktime(0,0,0,1, $val[23] - 1 , 1900)));
								if($followTime == '1970-01-01') {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!效果及绩效跟进时间格式不正确</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
								$inArr['followTime'] = $followTime;
							}
						}

						$newId = $this->add_d($inArr,true);

						if($newId) {
							$tempArr['result'] = '导入成功';
						}else{
							$tempArr['result'] = '<span class="red">导入失败</span>';
						}
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						array_push( $resultArr,$tempArr );
					}
				}

				//更新课程的相关参与人
//				print_r($courseIdArr);
//				$ids = implode($courseIdArr,',');
//				$courseDao->updateCoursepersons_d($ids);

				return $resultArr;
			}
		}
	}
	/******************* E 导入导出系列 ************************/
}
?>