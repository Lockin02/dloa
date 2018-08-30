<?php

/*
 * @author: zengq
 * Created on 2012-10-16
 *
 * @description: 招聘计划 Model层
 */
class model_hr_recruitment_plan extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitplan_plan";
		$this->sql_map = "hr/recruitment/planSql.php";

		$this->statusDao = new model_common_status();
		$this->statusDao->status = array (
		0 => array (
				'statusEName' => 'save',
				'statusCName' => '保存',
				'key' => '0'
				),
				1 => array (
				'statusEName' => 'nocheck',
				'statusCName' => '未下达',
				'key' => '1'
				),
				2 => array (
				'statusEName' => 'recruiting',
				'statusCName' => '招聘中',
				'key' => '2'
				),
				3 => array (
				'statusEName' => 'abord',
				'statusCName' => '暂停',
				'key' => '3'
				),
				4 => array (
				'statusEName' => 'finish',
				'statusCName' => '完成',
				'key' => '4'
				),
				5 => array (
				'statusEName' => 'closed',
				'statusCName' => '关闭',
				'key' => '5'
				)
				);
				//var_dump($this->statusDao->statusEtoK ( 'nocheck' ));
				parent :: __construct();
	}

	/******************* S 导入导出系列 ************************/
	function addExecelData_d() {
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType = $_FILES["inputExcel"]["type"];
		$resultArr = array (); //结果数组
		$excelData = array (); //excel数据数组
		$tempArr = array ();
		$inArr = array (); //插入数组
		$userArr = array (); //用户数组
		$deptArr = array (); //部门数组
		$jobsArr = array (); //职位数组
		$otherDataDao = new model_common_otherdatas(); //其他信息查询
		$datadictArr = array (); //数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		$deptDao = new model_deptuser_dept_dept();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {

			$excelData = util_excelUtil :: upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register("__autoload");

			if (is_array($excelData)) {

				//行数组循环
				foreach ($excelData as $key => $val) {
					//					if ($key === 0) {
					//						continue;
					//					}

					$actNum = $key +1;
					if (empty ($val[0]) && empty ($val[1])) {
						continue;
					} else {

						//新增数组
						$inArr = array ();

						//单据编号
						$inArr['formCode'] ='ZP'.$this->getRandNum();
						//审核状态
						$inArr['ExaStatus']='完成';
						//职位类型
						if (!empty ($val[0])) {
							$inArr['postTypeName'] = $val[0];
						}

						//部门
						if (!empty ($val[1])) {
							$rs = $otherDataDao->getDeptInfo_d($val[1]);
							if (!empty ($rs)) {
								$inArr['deptId'] = $rs['DEPT_ID'];
								$inArr['deptName'] = $val[1];
							} else {
								$tempArr['docCode'] = '第' . $actNum . '行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的部门</font>';
								array_push($resultArr, $tempArr);
								continue;
							}
						}

						//归属研发中心
						if (!empty ($val[2])) {
							$inArr['useAreaId']=$this->get_table_fields('area', "Name='".$val[2]."'", 'ID');
							$inArr['useAreaName']=$val[2];
						}

						//工作地点
						if (!empty ($val[3])) {
							$inArr['workPlace'] = $val[3];
						}

						//员工职位
						if (!empty ($val[4])) {
							$val[4]=trim($val[4]);
							$rs = $otherDataDao->getJobId_d($val[4]);
							if (!empty ($rs)) {
								$inArr['positionName'] = $val[4];
								$inArr['positionId'] = $rs;
							} else {
								$tempArr['docCode'] = '第' . $actNum . '行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的员工职位</font>';
								array_push($resultArr, $tempArr);
							}

						}

						//级别
						if (!empty ($val[5])) {
							$inArr['positionLevel'] = $val[5];
						}

						//是否紧急
						if (!empty ($val[6])) {
							$val[6] = trim($val[6]);
							if($val[6]=='是'||$val[6]='否')
							$inArr['isEmergency'] = $val[6];
						}

						//所在项目组
						if (!empty ($val[7])) {
							$inArr['projectGroup'] = $val[7];
							$inArr['projectCode'] = $this->get_table_fields('oa_rd_project', "projectName='".$val[7]."'", 'projectCode');
						}

						//招聘需求类型
						if (!empty ($val[8])) {
							$val[8] = trim($val[8]);
							$dictDao = new model_system_datadict_datadict();
							if ($val[8] == '计划外增员' || $val[8] == '计划内增员' || $val[8] == '离职补充'||$val[8]=='调岗') {
								$inArr['addTypeCode']=$dictDao->getCodeByName($val[8]);
								$inArr['addType'] = $val[8];
							}
						} else {
							$inArr['addType'] = "";
							$inArr['addTypeCode']= "";
						}

						//需求部门接口人
						if (!empty ($val[9])) {
							$val[9] = trim($val[9]);
							$resumeToId = $this->get_table_fields('user', "USER_NAME='".$val[9]."'", 'USER_ID');
							if($resumeToId!=null){
								$inArr['resumeToName'] = $val[9];
								$inArr['resumeToId'] = $resumeToId;
							}
						}
						//责任人
						if (!empty ($val[10])) {
							$recruitManId = $this->get_table_fields('user', "USER_NAME='".$val[10]."'", 'USER_ID');
							if($recruitManId!=null){
								$inArr['recruitManName'] = $val[10];
								$inArr['recruitManId'] = $recruitManId;
							}
						}
						//协助人
						if (!empty ($val[11])) {
							$assistManId = $this->get_table_fields('user', "USER_NAME='".$val[11]."'", 'USER_ID');
							if($assistManId!=null){
								$inArr['assistManName'] = $val[11];
								$inArr['assistManId'] = $recruitManId;
							}
						}
						//状态
						if (!empty ($val[12])) {
							$inArr['state'] = $val[12];
						}
						//提出需求日期
						if (!empty ($val[13])) {
							$inArr['formDate'] = $val[13];
						}
						//需求人数
						if (!empty ($val[14])) {
							$inArr['needNum'] = $val[14];
						}

						//已入职人数
						if (!empty ($val[15])) {
							$inArr['entryNum'] = $val[15];
						}

						//待入职人数
						if (!empty ($val[16])) {
							$inArr['beEntryNum'] = $val[16];
						}
						//招聘原因/理由
						if (!empty ($val[20])) {
							$inArr['applyReason'] = $val[20];
						}
						//岗位职责及任职要求
						if (!empty ($val[21])) {
							$inArr['workDuty'] = $val[21];
						}
						$newId = parent :: add_d($inArr, true);
						if ($newId) {
							$tempArr['result'] = '导入成功';
						} else {
							$tempArr['result'] = '导入失败';
						}
						$tempArr['docCode'] = '第' . $actNum . '行数据';
						array_push($resultArr, $tempArr);
					}
				}
				return $resultArr;
			}
		}
	}
	/**
	 * 生成十位的随机数
	 */
	function getRandNum(){
		$num = '';
		for($i=0;$i<10;$i++){
			$num.=rand(0,9);
		}
		return $num;
	}
}
?>
