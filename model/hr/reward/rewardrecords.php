<?php

/**
 * @author Show
 * @Date 2012年5月24日 星期四 10:00:14
 * @version 1.0
 * @description:薪资信息 Model层
 */
class model_hr_reward_rewardrecords extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_reward_records";
		$this->sql_map = "hr/reward/rewardrecordsSql.php";
		parent :: __construct();
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
		$userConutArr = array();//用户数组
		$userArr = array();//用户数组
		$deptArr = array();//部门数组
		$jobsArr = array();//职位数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
//			print_r($excelData);
			if(is_array($excelData)){

				//行数组循环
				foreach($excelData as $key => $val){
					if($key === 0){
						continue ;
					}
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])&& empty($val[5])){
						continue;
					}else{
						//新增数组
						$inArr = array();

						//导师员工编号
						if(!empty($val[0])){
							if(!isset($userArr[$val[0]])){
								$rs = $otherDataDao->getUserInfoByUserNo($val[0]);
								if(!empty($rs)){
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
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!没有员工编号</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//导师姓名
						if(!empty($val[1])){
							$inArr['userName'] = $val[1];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!没有员工姓名</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//二级部门
						if(!empty($val[2])){
							if(!isset($deptArr[$val[2]])){
								$rs = $otherDataDao->getDeptId_d($val[2]);
								if(!empty($rs)){
									$deptArr[$val[2]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的二级部门</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['deptNameS'] = $val[2];
							$inArr['deptIdS'] = $deptArr[$val[2]];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!没有二级部门</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//三级部门
						if(!empty($val[3])){
							if(!isset($deptArr[$val[3]])){
								$rs = $otherDataDao->getDeptId_d($val[3]);
								if(!empty($rs)){
									$deptArr[$val[3]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的三级部门</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['deptNameT'] = $val[3];
							$inArr['deptIdT'] = $deptArr[$val[3]];
						}

						//员工职位
						if(!empty($val[4])){
							if(!isset($jobsArr[$val[4]])){
								$rs = $otherDataDao->getJobId_d($val[4]);
								if(!empty($rs)){
									$jobsArr[$val[4]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的员工职位</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['jobName'] = $val[4];
							$inArr['jobId'] = $jobsArr[$val[4]];
						}

						//发薪月份
						if(!empty($val[5])){
							$inArr['rewardPeriod'] = $val[5];
							$rewardDate = $inArr['rewardPeriod'] . '-01';
							$date   =   explode( "-",   $rewardDate);
							if(checkdate($date[1],$date[2],$date[0])){
								$inArr['rewardDate'] = $rewardDate;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败，输入的发薪月份格式不对，应该为YYYY-MM </span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">没有填写发薪月份</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//本月工作日
						if(!empty($val[6])){
							$inArr['workDays'] = $val[6];
						}

						//基本工资
						if(!empty($val[7])){
							$inArr['basicWage'] = $val[7];
						}else{
							$inArr['basicWage'] = 0;
						}

						//个人公积金
						if(!empty($val[8])){
							$inArr['provident'] = $val[8];
						}else{
							$inArr['provident'] = 0;
						}

						//个人社保
						if(!empty($val[9])){
							$inArr['socialSecurity'] = $val[9];
						}else{
							$inArr['socialSecurity'] = 0;
						}

						//特别奖励
						if(!empty($val[10])){
							$inArr['specialBonus'] = $val[10];
						}else{
							$inArr['specialBonus'] = 0;
						}

						//特别扣款
						if(!empty($val[11])){
							$inArr['specialDeduction'] = $val[11];
						}else{
							$inArr['specialDeduction'] = 0;
						}

						//项目奖金
						if(!empty($val[12])){
							$inArr['projectBonus'] = $val[12];
						}else{
							$inArr['projectBonus'] = 0;
						}

						//餐费补贴
						if(!empty($val[13])){
							$inArr['mealSubsidies'] = $val[13];
						}else{
							$inArr['mealSubsidies'] = 0;
						}

						//其他补贴
						if(!empty($val[14])){
							$inArr['otherSubsidies'] = $val[14];
						}else{
							$inArr['otherSubsidies'] = 0;
						}

						//奖金
						if(!empty($val[15])){
							$inArr['otherBonus'] = $val[15];
						}else{
							$inArr['otherBonus'] = 0;
						}

						//事假
						if(!empty($val[16])){
							$inArr['leaveDays'] = $val[16];
						}else{
							$inArr['leaveDays'] = 0;
						}

						//病假
						if(!empty($val[17])){
							$inArr['sickDays'] = $val[17];
						}else{
							$inArr['sickDays'] = 0;
						}

						//税前工资
						if(!empty($val[18])){
							$inArr['preTaxWage'] = $val[18];
						}else{
							$inArr['preTaxWage'] = 0;
						}

						//税金
						if(!empty($val[19])){
							$inArr['taxes'] = $val[19];
						}else{
							$inArr['taxes'] = 0;
						}

						//实发工资
						if(!empty($val[20])){
							$inArr['afterTaxWage'] = $val[20];
						}else{
							$inArr['afterTaxWage'] = 0;
						}

						//备注
						if(!empty($val[21])){
							$inArr['remark'] = $val[21];
						}

//						print_r($inArr);
						$newId = $this->add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '导入成功';
						}else{
							$tempArr['result'] = '<span class="red">导入失败</span>';
						}
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}
	/******************* E 导入导出系列 ************************/
}
?>