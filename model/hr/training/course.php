<?php


/**
 * @author Show
 * @Date 2012年5月29日 星期二 9:24:35
 * @version 1.0
 * @description:培训课程表 Model层
 */
class model_hr_training_course extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_training_course";
		$this->sql_map = "hr/training/courseSql.php";
		parent :: __construct();
	}

	public $datadictFieldArr = array (
		'courseType'
	);

	//重写add_d
	function add_d($object) {
		//数据字典处理
		$object = $this->processDatadict($object);

		return parent :: add_d($object, true);
	}

	//重写编辑方法
	function edit_d($object) {
		//数据字典处理
		$object = $this->processDatadict($object);

		return parent :: edit_d($object, true);
	}

	/**
	 * 根据课程id更新参与人
	 */
	function updateCoursepersons_d($ids){
		if(empty($ids)){
			return false;
		}
		$sql = "update oa_hr_training_course c
					left join (
						select
							c.courseId,
							GROUP_CONCAT(c.userName) as userNames,
							GROUP_CONCAT(c.userAccount) as userAccounts,
							GROUP_CONCAT(CONVERT(c.userNo,CHAR(50))) as userNos
						from
							(
								select
									courseId,
									userName,
									userAccount,
									userNo
								from
									oa_hr_training_course_records
								where
									courseId in (".$ids.")
								group by
									courseId,
									userAccount
							) c
						group by
							c.courseId
					) p on c.id = p.courseId
					set
						c.personsListName = p.userNames,
						c.personsListAccount = p.userAccounts,
						c.personsListNo = p.userNos
					where c.id in (".$ids.")";
		return $this->_db->query($sql);
	}

	/**
	 * 根据课程id更新参与人
	 */
	function updateTeacher_d($ids){
		if(empty($ids)){
			return false;
		}
		$sql = "update oa_hr_training_course c
					left join (
						select
							c.courseId,
							GROUP_CONCAT(c.teacherName) as teacherNames,
							GROUP_CONCAT(CONVERT(c.teacherId,CHAR(50))) as teacherIds
						from
							(
								select
									courseId,
									teacherName,
									teacherId
								from
									oa_hr_training_teachrecords
								where
									courseId in (".$ids.")
								group by
									courseId,
									teacherId
							) c
						GROUP BY
							c.courseId
					) p on c.id = p.courseId
					set
						c.teacherName = p.teacherNames,
						c.teacherId = p.teacherIds
					where c.id in (".$ids.")";
		return $this->_db->query($sql);
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
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		$teacherArr = array();//教师数组
		$teacherDao = new model_hr_training_teacher();//讲师类
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

						//课程名称
						if(!empty($val[0])){
							$inArr['courseName'] = $val[0];
						}

						//奖惩属性
						if(!empty($val[1])){
							$val[1] = trim($val[1]);
							if(!isset($datadictArr[$val[1]])){
								$rs = $datadictDao->getCodeByName('HRPXLB',$val[1]);
								if(!empty($rs)){
									$courseType = $datadictArr[$val[1]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的培训课程类别</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$courseType = $datadictArr[$val[1]]['code'];
							}
							$inArr['courseType'] = $courseType;
							$inArr['courseTypeName'] = $val[1];
						}

						//培训机构
						if(!empty($val[2])){
							$inArr['agency'] = $val[2];
						}

						//讲师名称
						if($val[3]){
							$val[3] = trim($val[3]);
							if(!isset($teacherArr[$val[3]])){
								$rs = $teacherDao->find(array('teacherName' => $val[3]));
								if(empty($rs)){
									//如果是空，构造课程数组并新增
									$teacherArr[$val[3]]['teacherName'] = $val[3];

									if(!isset($userArr[$val[3]])){
										$rs = $otherDataDao->getUserInfo($val[3]);
										if(!empty($rs)){
											$userArr[$val[3]] = $rs;
										}else{
											$userArr[$val[3]] = array('USER_ID' => '');
										}
									}
									$inArr['teacherName'] = $val[3];
									$inArr['teacherAccount'] = $userArr[$val[3]]['USER_ID'];

									$teacherArr[$val[3]]['id'] = $teacherDao->add_d($teacherArr[$val[3]]);
								}else{
									$teacherArr[$val[3]] = $rs;
								}
							}

							$inArr['teacherName'] = $val[3];
							$inArr['teacherId'] = $teacherArr[$val[3]]['id'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!没有填写讲师姓名</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//培训时间
						if(!empty($val[4])&& $val[4] != '0000-00-00'){
							$val[4] = trim($val[4]);

							if(!is_numeric($val[4])){
								$inArr['courseDate'] = $val[4];
							}else{
								$courseDate = date('Y-m-d',(mktime(0,0,0,1, $val[4] - 1 , 1900)));
								$inArr['courseDate'] = $courseDate;
							}
						}

						//培训地点
						if(!empty($val[5])){
							$inArr['address'] = $val[5];
						}

						//培训课时
						if(!empty($val[6])){
							$inArr['lessons'] = $val[6];
						}

						//培训费用
						if(!empty($val[7])){
							$inArr['fee'] = $val[7];
						}

						//课程大纲
						if(!empty($val[8])){
							$inArr['outline'] = $val[8];
						}

						//适合对象
						if(!empty($val[9])){
							$inArr['forWho'] = $val[9];
						}

						//适合对象
						if(!empty($val[10])){
							$val[10] = trim($val[10]);
							if(!isset($datadictArr[$val[10]])){
								$rs = $datadictDao->getCodeByName('HRKCZT',$val[10]);
								if(!empty($rs)){
									$status = $datadictArr[$val[10]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的培训课程状态</spam>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$status = $datadictArr[$val[10]]['code'];
							}
							$inArr['status'] = $status;
						}

						//备注
						if(!empty($val[11])){
							$inArr['remark'] = $val[11];
						}

						//备注
						if(!empty($val[12])){
							$inArr['personsListName'] = $val[12];
						}

//						print_r($inArr);
						$newId = $this->add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '导入成功';
						}else{
							$tempArr['result'] = '<span class="red">导入失败</spam>';
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