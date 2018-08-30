<?php
/**
 * @author Show
 * @Date 2012年5月31日 星期四 10:13:30
 * @version 1.0
 * @description:培训管理-授课记录 Model层
 */
class model_hr_training_teachrecords  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_training_teachrecords";
		$this->sql_map = "hr/training/teachrecordsSql.php";
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
		$teacherArr = array();//教师数组
		$teacherDao = new model_hr_training_teacher();//讲师类
		$courseArr = array();//课程数组
		$courseIdArr = array();//课程id数组
		$courseDao = new model_hr_training_course();//课程类
		$trainingrecordsDao = new model_hr_training_trainingrecords();
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		$otherDataDao = new model_common_otherdatas();//其他信息查询

		//数据字段表头配置
		$objNameArr = array (
			0 => 'courseCode', //课程编号
			1 => 'courseName', //课程名称
			2 => 'duration', //时长
			3 => 'teacherName', //讲师
			4 => 'userNo', //员工编号
			5 => 'trainsTypeName', //培训类型
			6 => 'trainsMethod', //培训方式
			7 => 'orgDeptName', //组织部门
			8 => 'trainsMonth', //培训月份
			9 => 'teachDate', //开始时间
			10 => 'teachEndDate', //结束时间
			11 => 'trainsNum', //培训次数
			12 => 'agency', //培训机构
			13 => 'address', //地点
			14 => 'joinNum', //参与人数
			15 => 'fee', //费用
			16 => 'assessmentName', //考核类型
			17 => 'courseEvaluateScore', //课程评估分数
			18 => 'trainsOrgEvaluateScore', //培训组织评估分数
			19 => 'followTime', //效果跟绩效跟进时间
		);
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){
				$objectArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					if(empty($row[1]) && empty($row[2]) && empty($row[9])) {
						continue;
					} else {
						foreach ( $objNameArr as $index => $fieldName ) {
							$objectArr [$rNum] [$fieldName] = $row [$index];
						}
					}
				}

				//行数组循环
				foreach($objectArr as $key => $val){

					$actNum = $key + 2;
					$inArr = array();

					//课程编号
					if($val['courseCode']) {
						$inArr['courseCode'] = $val['courseCode'];
					}

					//课程名称
					if($val['courseName']){
						if(!isset($courseArr[$val['courseName']])){
							$rs = $courseDao->find(array('courseName' => $val['courseName']));
							if(empty($rs)){
								//如果是空，构造课程数组并新增
								$courseArr[$val['courseName']]['courseName'] = $val['courseName'];
								$courseArr[$val['courseName']]['address'] = $inArr['address'];
								$courseArr[$val['courseName']]['teachDate'] = $inArr['teachDate'];
								$courseArr[$val['courseName']]['status'] = 'HRKCZT-02';
							}else{
								$courseArr[$val['courseName']] = $rs;
							}
						}
						$inArr['courseName'] = $val['courseName'];
					}else{
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!没有填写课程名称</span>';
						array_push( $resultArr,$tempArr );
						continue;
					}

					//时长
					if($val['duration']) {
						$inArr['duration'] = $val['duration'];
					}

					//讲师名称
					if($val['teacherName']){
						$inArr['teacherName'] = $val['teacherName'];
						if(!isset($teacherArr[$val['teacherName']])){
							$rs = $teacherDao->find(array('teacherName' => $val['teacherName']));
							if(empty($rs)){
								//如果是空，构造课程数组并新增
								$teacherArr[$val['teacherName']]['teacherName'] = $val['teacherName'];
							}else{
								$teacherArr[$val['teacherName']] = $rs;
							}
						}

						$inArr['teacherName'] = $val['teacherName'];
					}else{
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!没有填写讲师姓名</span>';
						array_push( $resultArr,$tempArr );
						continue;
					}

					//员工编号
					if($val['userNo']) {
						$inArr['userNo'] = $val['userNo'];
					}

					//培训类型
					if(!empty($val['trainsTypeName']) && trim($val['trainsTypeName']) != '') {
						$val['trainsTypeName'] = trim($val['trainsTypeName']);
						if(!isset($datadictArr[$val['trainsTypeName']])) {
							$rs = $datadictDao->getCodeByName('HRPXLX',$val['trainsTypeName']);
							if(!empty($rs)) {
								$trainsType = $datadictArr[$val['trainsTypeName']]['code'] = $rs;
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的培训类型</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						} else {
							$trainsType = $datadictArr[$val['trainsTypeName']]['code'];
						}
						$inArr['trainsType'] = $trainsType;
						$inArr['trainsTypeName'] = $val['trainsTypeName'];
					}

					//培训方式
					if(!empty($val['trainsMethod']) && trim($val['trainsMethod']) != '') {
						$val['trainsMethod'] = trim($val['trainsMethod']);
						if(!isset($datadictArr[$val['trainsMethod']])) {
							$rs = $datadictDao->getCodeByName('HRPXFS',$val['trainsMethod']);
							if(!empty($rs)) {
								$trainsMethodCode = $datadictArr[$val['trainsMethod']]['code'] = $rs;
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的培训方式</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$trainsMethodCode = $datadictArr[$val['trainsMethod']]['code'];
						}
						$inArr['trainsMethodCode'] = $trainsMethodCode;
						$inArr['trainsMethod'] = $val['trainsMethod'];
					}

					//组织部门
					if(!empty($val['orgDeptName'])) {
						$val['orgDeptName'] = trim($val['orgDeptName']);
						if(!isset($deptArr[$val['orgDeptName']])) {
							$rs = $otherDataDao->getDeptId_d($val['orgDeptName']);
							if(!empty($rs)) {
								$deptArr[$val['orgDeptName']] = $rs;
							}
						}
						$inArr['orgDeptName'] = $val['orgDeptName'];
						$inArr['orgDeptId'] = $deptArr[$val['orgDeptName']];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!没有组织部门</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//培训月份
					if(!empty($val['trainsMonth']) && trim($val['trainsMonth']) != '') {
						if(!is_numeric($val['trainsMonth'])) {
							$inArr['trainsMonth'] = $val['trainsMonth'];
						} else {
							$year = date('Y',(mktime(0 ,0 ,0 ,1 ,$val['trainsMonth'] - 1 ,1900)));
							$month = date('n',(mktime(0 ,0 ,0 ,1 ,$val['trainsMonth'] - 1 ,1900)));
							if($year == '1970' && $month == '1') {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!培训月份格式不正确</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
							$inArr['trainsMonth'] = $year.'年'.$month.'月';
						}
					}

					//授课日期
					if(!empty($val['teachDate']) && $val['teachDate'] != '0000-00-00') {
						$val['teachDate'] = trim($val['teachDate']);

						if(!is_numeric($val['teachDate'])){
							$inArr['teachDate'] = $val['teachDate'];
						}else{
							$teachDate = date('Y-m-d',(mktime(0,0,0,1, $val['teachDate'] - 1 , 1900)));
							$inArr['teachDate'] = $teachDate;
						}
					}

					//授课结束日期
					if(!empty($val['teachEndDate']) && $val['teachEndDate'] != '0000-00-00') {
						$val['teachEndDate'] = trim($val['teachEndDate']);

						if(!is_numeric($val['teachEndDate'])){
							$inArr['teachEndDate'] = $val['teachEndDate'];
						}else{
							$teachDate = date('Y-m-d',(mktime(0,0,0,1, $val['teachEndDate'] - 1 , 1900)));
							$inArr['teachEndDate'] = $teachDate;
						}
					}

					//培训次数
					if($val['trainsNum']) {
						$inArr['trainsNum'] = $val['trainsNum'];
					}

					//培训机构
					if($val['agency']) {
						$inArr['agency'] = $val['agency'];
					}

					//地址
					if($val['address']){
						$inArr['address'] = $val['address'];
					}

					//参训人数
					if($val['joinNum']){
						$inArr['joinNum'] = $val['joinNum'];
					}

					//费用
					if($val['fee']){
						$inArr['fee'] = $val['fee'];
					}

					//考核类型
					if(!empty($val['assessmentName']) && trim($val['assessmentName']) != '') {
						$val['assessmentName'] = trim($val['assessmentName']);
						if(!isset($datadictArr[$val['assessmentName']])) {
							$rs = $datadictDao->getCodeByName('HRPXKH',$val['assessmentName']);
							if(!empty($rs)) {
								$assessment = $datadictArr[$val['assessmentName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的考核类型</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$assessment = $datadictArr[$val['assessmentName']]['code'];
						}
						$inArr['assessment'] = $assessment;
						$inArr['assessmentName'] = $val['assessmentName'];
					}

					//课程评估分数
					if(!empty($val['courseEvaluateScore']) && trim($val['courseEvaluateScore']) != '') {
						$inArr['courseEvaluateScore'] = $val['courseEvaluateScore'];
					}

					//培训组织评估分数
					if(!empty($val['trainsOrgEvaluateScore']) && trim($val['trainsOrgEvaluateScore']) != '') {
						$inArr['trainsOrgEvaluateScore'] = $val['trainsOrgEvaluateScore'];
					}

					//效果及绩效跟进时间
					if(!empty($val['followTime']) && trim($val['followTime']) != '') {
						$val['followTime'] = trim($val['followTime']);
						if(!is_numeric($val['followTime'])) {
							$inArr['followTime'] = $val['followTime'];
						} else {
							$followTime = date('Y-m-d',(mktime(0,0,0,1, $val['followTime'] - 1 , 1900)));
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

					if($newId){
						$tempArr['result'] = '导入成功';
					}else{
						$tempArr['result'] = '<span class="red">导入失败</span>';
					}
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					array_push( $resultArr,$tempArr );
				}

				//更新课程的培训讲师
//				$ids = implode($courseIdArr,',');
//				$courseDao->updateTeacher_d($ids);
				return $resultArr;
			}
		}
	}
	/******************* E 导入导出系列 ************************/
}
?>