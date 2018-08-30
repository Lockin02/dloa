<?php
/**
 * @author Show
 * @Date 2012年5月30日 星期三 9:56:29
 * @version 1.0
 * @description:培训管理-讲师管理 Model层
 */
class model_hr_training_teacher extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_training_teacher";
		$this->sql_map = "hr/training/teacherSql.php";
		parent :: __construct();
	}

	public $datadictFieldArr = array (
		'levelId'
	);

	//返回是否内训师
	function rtYN_d($val){
		if($val == 1){
			return '是';
		}else{
			return '否';
		}
	}

	//重写add
	function add_d($object){
		$object = $this->processDatadict($object);

		return parent::add_d($object,true);
	}

	//重写编辑
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
		$userArr = array();//用户数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$perpDao = new model_hr_personnel_personnel();//其他信息查询
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
					if(empty($val[1]) && empty($val[2]) && empty($val[5])){
						continue;
					}else{
						//新增数组
						$inArr = array();

						//-----讲师编号
						if (!empty($val[0])){
							$val[0] = trim($val[0]);
							$inArr['teacherNum'] = $val[0];
						}
						//讲师姓名
						if(!empty($val[1])){
							$val[1] = trim($val[1]);
							if(!isset($userArr[$val[1]])){
								$rs = $otherDataDao->getUserInfo($val[1]);
								if(!empty($rs)){
									$userArr[$val[1]] = $rs;
								}else{
									$userArr[$val[1]] = array('USER_ID' => '');
								}
							}
							$inArr['teacherName'] = $val[1];
							$inArr['teacherAccount'] = $userArr[$val[1]]['USER_ID'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有填写讲师姓名</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//---培训机构
						if(!empty($val[2])){
							$val[2] = trim($val[2]);
							$inArr['trainingAgency'] = $val[2];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有填写培训机构</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//----讲师部门
						if(!empty($val[3])){
							$val[3] = trim($val[3]);
//							if(!isset($userArr[$val[3]])){
//								//查询导师编号和导师名字是否匹配
//								if(!empty($val[0])&&!empty($val[1])){
//							$rs1=$perpDao->find(array("userNo"=>$val[0]));
//								if ($rs1['userNo']==$val[0]&&$rs1['userName']==$val[1]){
//									if ($val[3]!=$rs1['belongDeptName']){
//												$tempArr['docCode'] = '第' . $actNum .'行数据';
//												$tempArr['result'] = '<span class="red">导入失败!填写讲师岗位有误</span>';
//												array_push( $resultArr,$tempArr );
//												continue;
//									}else{
//										$userArr[$val[3]]=$rs1;
//									}
//								}else{
//									$userArr[$val[3]]=array('belongDeptId' => '');
//								}
//								}else{
//									$userArr[$val[3]]=array('belongDeptId' => '');
//								}
//									if (!empty($rs1)){
//										$userArr[$val[3]]=$rs1;
////										if ($val[3]!=$rs1['belongDeptName']){
////											$tempArr['docCode'] = '第' . $actNum .'条数据';
////											$tempArr['result'] = '<span class="red">导入失败!填写讲师岗位有误</span>';
////											array_push( $resultArr,$tempArr );
////											continue;
////										}
//									}else{
//										$userArr[$val[3]]=array('belongDeptId' => '');
//									}
//								}
//
									$inArr['belongDeptName'] = $val[3];
//									$inArr['belongDeptId'] = $userArr[$val[3]]['belongDeptId'];
//						}else{
//							$tempArr['docCode'] = '第' . $actNum .'行数据';
//							$tempArr['result'] = '<span class="red">导入失败!没有填写讲师部门</span>';
//							array_push( $resultArr,$tempArr );
//							continue;
						}


						//----讲师岗位
						if(!empty($val[4])){
//							echo "<pre>";
//							print_r($val[4]);
							$val[4] = trim($val[4]);
//							if(!isset($userArr[$val[4]])){
//							$rs1=$perpDao->find(array("userNo"=>$val[0]));
//								if (!empty($rs1)){
//										$userArr[$val[4]]=$rs1;
//									if ($val[4]!=$rs1['jobName']){
//										$tempArr['docCode'] = '第' . $actNum .'行数据';
//										$tempArr['result'] = '<span class="red">导入失败!填写讲师岗位有误</span>';
//										array_push( $resultArr,$tempArr );
//										continue;
//									}
//								}else{
//									$userArr[$val[4]]=array('lecturerPostId' => '');
//								}
//							}
								$inArr['lecturerPost'] = $val[4];
//								$inArr['lecturerPostId'] = $userArr[$val[4]]['jobId'];
//						}else{
//							$tempArr['docCode'] = '第' . $actNum .'行数据';
//							$tempArr['result'] = '<span class="red">导入失败!没有填写讲师部门</span>';
//							array_push( $resultArr,$tempArr );
//							continue;
						}

						//----讲师类别
						if (!empty($val[5])){
							if($val[5]=="内训师" || $val[5]=="临时讲师"|| $val[5]=="外部讲师"){
							$val[5] = trim($val[5]);
							$inArr['lecturerCategory'] = $val[5];
							}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!导入讲师类别有误</span>';
							array_push( $resultArr,$tempArr );
							continue;
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有填写讲师类别</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}
//
//						//是否内训师
//						if(!empty($val[1])){
//							if($val[1] == '是'){
//								$inArr['isInner'] = 1;
//							}else{
//								$inArr['isInner'] = 0;
//							}
//						}else{
//							$tempArr['docCode'] = '第' . $actNum .'行数据';
//							$tempArr['result'] = '<span class="red">导入失败!没有填写是否内训师</span>';
//							array_push( $resultArr,$tempArr );
//							continue;
//						}
//
//						//培训时间
//						if(!empty($val[2])&& $val[2] != '0000-00-00'){
//							$val[2] = trim($val[2]);
//
//							if(!is_numeric($val[2])){
//								$inArr['certifyDate'] = $val[2];
//							}else{
//								$certifyDate = date('Y-m-d',(mktime(0,0,0,1, $val[2] - 1 , 1900)));
//								$inArr['certifyDate'] = $certifyDate;
//							}
//						}
//
						//内训师级别
						if(!empty($val[6])){
							$val[6] = trim($val[6]);
							if(!isset($datadictArr[$val[6]])){
								$rs = $datadictDao->getCodeByName('HRNSSJB',$val[6]);
								if(!empty($rs)){
									$levelId = $datadictArr[$val[6]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的内训师级别</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$levelId = $datadictArr[$val[6]]['code'];
							}
							$inArr['levelId'] = $levelId;
							$inArr['levelName'] = $val[6];
						}

						//认证时间
						if(!empty($val[7])){
							$inArr['certifyDate'] = trim($val[7]);
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!认证时间未填写</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}
					if(!empty($val[7])&& $val[7] != '0000-00-00'){
						$val['certifyDate'] = trim($val[7]);
						if(!is_numeric($val[7])){
							$inArr['certifyDate'] = $val[7];
//							$tempArr['docCode'] = '第' . $actNum .'行数据';
//							$tempArr['result'] = '<span class="red">导入失败!导入时间有误</span>';
//							array_push( $resultArr,$tempArr );
//							continue;
						}else{
							$teachDate = date('Y-m-d',(mktime(0,0,0,1, $val[7] - 1 , 1900)));
							$inArr['certifyDate'] = $teachDate;
						}
					}


						//认证分数
						if(!empty($val[8])){
							$inArr['scores'] = $val[8];
						}
//
						//可授课程
						if(!empty($val[9])){
							$inArr['courses'] = $val[9];
						}

						$inArr['remark'] = '系统导入信息';
//						echo "<pre>";
//						print_r($inArr);
						$newId = $this->add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '导入成功';
						}else{
							$tempArr['result'] = '<span class="red">导入失败<span class="red">';
						}
						$tempArr['docCode'] = '第' . $actNum .'行数据';
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