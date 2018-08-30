<?php

/**
 * @author Show
 * @Date 2012年5月25日 星期五 14:55:28
 * @version 1.0
 * @description:奖惩管理 Model层
 */
class model_hr_incentive_incentive extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_incentive";
		$this->sql_map = "hr/incentive/incentiveSql.php";
		parent :: __construct();
	}

	//需要数据字典处理的字段
    public $datadictFieldArr = array(
		'incentiveType'
	);

	//重写add_d
	function add_d($object){
		$object = $this->processDatadict($object);
		$id=parent::add_d($object,true);

		//更新附件关联关系
		$this->updateObjWithFile ( $id );
//注释by zengzx
//		$uploadFile = new model_file_uploadfile_management ();
//		$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
		return $id;
	}

	//重写edit
	function edit_d($object){
		$object = $this->processDatadict($object);

		return parent::edit_d($object,true);
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
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
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
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])&& empty($val[5])){
						continue;
					}else{
						//新增数组
						$inArr = array();

						//导师员工编号
						if(!empty($val[0])){
							if(!isset($userArr[$val[0]])){
								$rs = $otherDataDao->getUserInfoByUserNo(trim($val[0]));
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
							if( $rs['userName'] == trim($val[1]) ){
								$inArr['userName'] = trim($val[1]);
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!员工编号与员工姓名不匹配。</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!没有员工姓名</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//奖惩原因
						if(!empty($val[2])){
							$inArr['reason'] = $val[2];
						}

						//奖惩属性
						if(!empty($val[3])){
							$val[3] = trim($val[3]);
							if(!isset($datadictArr[$val[3]])){
								$rs = $datadictDao->getCodeByName('HRJLSS',$val[3]);
								if(!empty($rs)){
									$incentiveType = $datadictArr[$val[3]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的奖惩属性</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$incentiveType = $datadictArr[$val[3]]['code'];
							}
							$inArr['incentiveType'] = $incentiveType;
							$inArr['incentiveTypeName'] = $val[3];
						}

						//奖惩日期
						if(!empty($val[4])&& $val[4] != '0000-00-00'){
							$val[4] = trim($val[4]);
								$incentiveDate1 = $val[4] . '-01';
								$date   =   explode( "-",   $incentiveDate1);
								if(checkdate($date[1],$date[2],$date[0])){
									$incentiveDate = $incentiveDate1;
								}else{
									$incentiveDate = date('Y-m-d',(mktime(0,0,0,1, $val[4] - 1 , 1900)));
								}
								$inArr['incentiveDate'] = $incentiveDate;
						}

						//授予单位
						if(!empty($val[5])){
							$inArr['grantUnitName'] = $val[5];
						}

						//工资月份
						if(!empty($val[6])){
							$inArr['rewardPeriod'] = $val[6];
							$rewardDate = $inArr['rewardPeriod'] . '-01';
							$date   =   explode( "-",   $rewardDate);
							if(checkdate($date[1],$date[2],$date[0])){
								$inArr['rewardDate'] = $rewardDate;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败，输入的工资月份格式不对，应该为YYYY-MM </span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">没有填写工资月份</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//奖惩金额
						if(!empty($val[7])){
							$inArr['incentiveMoney'] = $val[7];
						}

						//奖惩说明
						if(!empty($val[8])){
							$inArr['description'] = $val[8];
						}

						//记录时间
						if(!empty($val[9])&& $val[9] != '0000-00-00'){
							$val[9] = trim($val[9]);

								$recorderDate1 = $val[9] . '-01';
								$date   =   explode( "-",   $incentiveDate1);
								if(checkdate($date[1],$date[2],$date[0])){
									$recorderDate = $recorderDate1;
								}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[9] - 1 , 1900)));
								}
								$inArr['recordDate'] = $recorderDate;
						}

						//记录人
						if(!empty($val[10])){
							if(!isset($userArr[$val[10]])){
								$rs = $otherDataDao->getUserInfo($val[10]);
								if(!empty($rs)){
									$userArr[$val[10]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">更新失败!不存在的记录人名称</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}

							$inArr['recorderId'] = $userArr[$val[10]]['USER_ID'];
							$inArr['recorderName'] = $val[10];
						}

						//备注
						if(!empty($val[12])){
							$inArr['remark'] = $val[12];
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