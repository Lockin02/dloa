<?php
/**
 * @author Administrator
 * @Date 2012年10月16日 星期二 9:21:33
 * @version 1.0
 * @description:招聘计划 Model层
 */
class model_hr_recruitplan_plan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitplan_plan";
		$this->sql_map = "hr/recruitplan/planSql.php";
		$this->statusDao = new model_common_status ();
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
				),
				6 => array (
				'statusEName' => 'suspend',
				'statusCName' => '挂起',
				'key' => '6'
				),
				7 => array (
				'statusEName' => 'cancel',
				'statusCName' => '取消',
				'key' => '7'
				),
				9 => array (
				'statusEName' => 'inactive ',
				'statusCName' => '未启用',
				'key' => '9'
				)
				);
				parent::__construct ();
	}

	/**
	 * 添加招聘计划信息
	 */
	function add_d($object){
		try{
			$this->start_d();
			$object['formCode']='ZPJH'.date ( "YmdHis" );//单据编号
			$dictDao = new model_system_datadict_datadict();
			$object['state'] = 0;
			$object['addType'] = $dictDao->getDataNameByCode($object['addTypeCode']);
			$object['employmentType'] = $dictDao->getDataNameByCode($object['employmentTypeCode']);
			$object['maritalStatusName'] = $dictDao->getDataNameByCode($object['maritalStatus']);
			//$object['educationName'] = $dictDao->getDataNameByCode($object['education']);
			$object['postTypeName'] = $dictDao->getDataNameByCode($object['postType']);
			$object['ExaStatus']='未提交';
			$object['entryNum']=0;//入职人数
			$object['beEntryNum']=$object['needNum'];//待入职人数
			if($object['useAreaId']>0){
				$object ['useAreaName']=$this->get_table_fields('area', "ID='".$object['useAreaId']."'", 'Name');
			}
			$id=parent::add_d($object,true);

			//更新附件关联关系
			$this->updateObjWithFile ( $id);

			//附件处理
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			}
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * 编辑招聘计划
	 */
	function edit_d($object){
		try{
			$this->start_d();
			//add chenrf 20130510
			$needNum=$object['needNum'];//需求人数
			$entryNum=$object['entryNum'];//已入职人数
			$beEntryNum=$needNum-$entryNum;
			$object['beEntryNum']=$beEntryNum;  //待入职人数

			$dictDao = new model_system_datadict_datadict();
			$object['addType'] = $dictDao->getDataNameByCode($object['addTypeCode']);
			$object['employmentType'] = $dictDao->getDataNameByCode($object['employmentTypeCode']);
			$object['maritalStatusName'] = $dictDao->getDataNameByCode($object['maritalStatus']);
			$object['educationName'] = $dictDao->getDataNameByCode($object['education']);
			$object['postTypeName'] = $dictDao->getDataNameByCode($object['postType']);
			$id=parent::edit_d($object,true);
			//更新附件关联关系
			$this->updateObjWithFile($object['id']);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * 招聘计划excel导入
	 */
	function addExecelData_d($actionType){
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
		$userArr = array();//用户数组
		$deptArr = array();//部门数组
		$jobsArr = array();//职位数组
		$areArr	= array(); //归属部门（区域）数组
		$positionLevel=array(); //网优级别数组
		$levelArr=array('初级','中级','高级');//除网优的级别数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		$eperson=new model_engineering_baseinfo_eperson();  //网优类型职位的级别
		$epersonData=$eperson->findAll('orderNum!=0',null,'personLevel');
		foreach ($epersonData as $per){
			array_push($positionLevel, $per['personLevel']);
		}
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			//			echo "<pre>";
			if(is_array($excelData)){
			try{
				$this->start_d();
				//行数组循环
				foreach($excelData as $key => $val){
					$val=array_map(array(__CLASS__,'addslashes'),$val);  //过滤特殊符号
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[14]) ){
						continue;
					}else{
						//新增数组
						$inArr = array();

						//职位类型
						if(!empty($val[0])&&trim($val[0])!=''){
							$val[0] = trim($val[0]);
							if(!isset($datadictArr[$val[0]])){
								$rs = $datadictDao->getCodeByName('YPZW',trim($val[0]));
								if(!empty($rs)){
									$trainsType = $datadictArr[$val[0]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的职位类型</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$trainsType = $datadictArr[$val[0]]['code'];
							}
							$inArr['postType'] = $trainsType;
							$inArr['postTypeName'] = $val[0];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!职位类型为空</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//部门
						if(!empty($val[1])&&trim($val[1])!=''){
							if(!isset($deptArr[$val[1]])){
								$rs = $otherDataDao->getDeptId_d(trim($val[1]));
								if(!empty($rs)){
									$deptArr[$val[1]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的部门</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['deptName'] = $val[1];
							$inArr['deptId'] = $deptArr[$val[1]];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!需求部门为空</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//归属部门
						if(!empty($val[2])&&trim($val[2])!=''){
							$are=trim($val[2]);
							if(!in_array($are, $areArr)){
								$re=$this->get_table_fields('area', "Name='".$are."'", 'ID');
								if(!empty($re))
									$areArr[$are]=$re;
								else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的归属部门</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['useAreaName']=$are;
							$inArr['useAreaId']=$areArr[$are];
						}

						//工作地点
						if(!empty($val[3])&&trim($val[3])!=''){
							$inArr['workPlace'] = trim($val[3]);
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!需工作地点为空</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//职位
						if(!empty($val[4])&&trim($val[4])!=''){
							$val[4] = trim($val[4]);
							if(!isset($jobsArr[$val[4]])){
								$rs = $otherDataDao->getJobId_d($val[4]);
								if(!empty($rs)){
									$jobsArr[$val[4]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的职位</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['positionName'] = $val[4];
							$inArr['positionId'] = $jobsArr[$val[4]];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!职位不能为空</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//级别
						if(!empty($val[5])&&trim($val[5])!=''){
							$levelStr=trim($val[5]);
							$level=explode(',',$levelStr);
							$level=array_filter($level);
							$errorLe='';
							if('网优'==$actionType){   //如果职位类型为网优
								foreach ($level as $le)
									if(!in_array($le,$positionLevel)){
										$errorLe=$le;
										break;
									}
							}else{
								foreach ($level as $le)
									if(!in_array($le, $levelArr)){
										$errorLe=$le;
									}

							}

							if($errorLe!=''){
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的级别('.$errorLe.')</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
							else{
								$inArr['positionLevel'] = $levelStr;
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!级别不能为空</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}



						//是否紧急
						if(!empty($val[6])&&trim($val[6])!=''){
							$val[6] = trim($val[6]);
							if($val[6]=='是'){
								$inArr['isEmergency'] = 1;
							}else if($val[6]=='否'){
								$inArr['isEmergency'] = 0;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!是否紧急请填是或否</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}

						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!是否紧急为空</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//所在项目组
						if(!empty($val[7])&&trim($val[7])!=''){
							$inArr['projectGroup'] = $val[7];
						}



						//招聘需求类型
						if(!empty($val[8])&&trim($val[8])!=''){
							$val[8] = trim($val[8]);
							if(!isset($datadictArr[$val[8]])){
								$rs = $datadictDao->getCodeByName('HRZYLX',trim($val[8]));
								if(!empty($rs)){
									$trainsType = $datadictArr[$val[8]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的需求类型</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$trainsType = $datadictArr[$val[8]]['code'];
							}
							$inArr['addTypeCode'] = $trainsType;
							$inArr['addType'] = $val[8];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!招聘需求类型不能为空</span>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//部门接口人
						if(!empty($val[9])&&trim($val[9])!=''){
							$val[9] = trim($val[9]);
							if(!isset($userArr[$val[9]])){
								$rs = $otherDataDao->getUserInfo(trim($val[9]));
								if(!empty($rs)){
									$userArr[$val[9]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!不存在的姓名(需求部门接口人)</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['resumeToName'] = trim($val[9]);
							$inArr['resumeToId'] = $userArr[$val[9]]['USER_ID'];
							$inArr['formManName'] = trim($val[9]);
							$inArr['formManId'] = $userArr[$val[9]]['USER_ID'];
						}

						//责任人
						if(!empty($val[10])&&trim($val[10])!=''){
							$val[10] = trim($val[10]);
							if(!isset($userArr[$val[10]])){
								$rs = $otherDataDao->getUserInfo(trim($val[10]));
								if(!empty($rs)){
									$userArr[$val[10]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!不存在的姓名</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['recruitManName'] = trim($val[10]);
							$inArr['recruitManId'] = $userArr[$val[10]]['USER_ID'];
						}

						//协助人
						if(!empty($val[11])&&trim($val[11])!=''){
							$val[11] = trim($val[11]);
							if(!isset($userArr[$val[11]])){
								$rs = $otherDataDao->getUserInfo(trim($val[11]));
								if(!empty($rs)){
									$userArr[$val[11]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!不存在的协助人</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['assistManName'] = trim($val[11]);
							$inArr['assistManId'] = $userArr[$val[11]]['USER_ID'];
						}

						//状态
						if(!empty($val[12])&&trim($val[12])!=''){
							$inArr['state'] = $this->statusDao->statusCtoK(trim($val[12])) ;
						}

						//提出需求日期
						if(!empty($val[13])&&trim($val[13])!=''){
							$val[13] = trim($val[13]);
							if (!is_numeric($val[13])) {
								$inArr['formDate'] = $val[13];
							} else {
								$beginDate = date('Y-m-d', (mktime(0, 0, 0, 1, $val[13] - 1, 1900)));
								if($beginDate=='1970-01-01'){
									$quitDate = date('Y-m-d',strtotime ($val[13]));
									$inArr['formDate'] = $quitDate;
								}else{
									$inArr['formDate'] = $beginDate;
								}
							}
						}

						//网优模板 导入
						if('网优'==$actionType){
							if($actionType!=($val[0])){
								throw new Exception;
							}
							//希望到岗时间
							if(!empty($val[14])&&trim($val[14])!=''){
								$hopeDate=$var[14];
								if(is_numeric($val[14])){
									$beginDate = date('Y-m-d', (mktime(0, 0, 0, 1, $val[14] - 1, 1900)));
									if($beginDate=='1970-01-01'){
										$quitDate = date('Y-m-d',strtotime ($val[14]));
										$inArr['hopeDate'] = $quitDate;
									}else{
										$inArr['hopeDate'] = $beginDate;
									}
								}else {
										$tempArr['docCode'] = '第' . $actNum .'行数据';
										$tempArr['result'] = '<span class="red">导入失败!希望到岗时间类型错误</span>';
										array_push( $resultArr,$tempArr );
										continue;
								}


							}
						//需求人数
							if(!empty($val[15])&&trim($val[15])!=''){
								//已入职的人数
								if(!empty($val[16])&&trim($val[16])!=''){
									if(!is_numeric($val[15])){
										$tempArr['docCode'] = '第' . $actNum .'行数据';
										$tempArr['result'] = '<span class="red">导入失败!已入职的人数类型错误</span>';
										array_push( $resultArr,$tempArr );
										continue;
									}
									$inArr['entryNum'] = $val[16];
								}
								if(!is_numeric($val[15])){
										$tempArr['docCode'] = '第' . $actNum .'行数据';
										$tempArr['result'] = '<span class="red">导入失败!需求人数类型错误</span>';
										array_push( $resultArr,$tempArr );
										continue;
									}
								if(trim($val[16])>trim($val[15])){
										$tempArr['docCode'] = '第' . $actNum .'行数据';
										$tempArr['result'] = '<span class="red">导入失败!需求人数必须大于已入职人数</span>';
										array_push( $resultArr,$tempArr );
										continue;
								}
									$inArr['needNum'] = $val[15];
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!需求人数为空</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}



							//待入职的人数
							if(!empty($val[17])&&trim($val[17])!=''){
								$inArr['beEntryNum'] = $val[17];
							}

							//在招聘的人数
							if(!empty($val[18])&&trim($val[18])!=''){
							}

							//本周offer姓名
							if(!empty($val[20])&&trim($val[20])!=''){
							}

							//招聘原因/理由
							if(!empty($val[21])&&trim($val[21])!=''){
								$inArr['applyReason'] = $val[21];
							}

							//岗位职责及任职要求
							if(!empty($val[22])&&trim($val[22])!=''){
								$inArr['workDuty'] = $val[22];
							}
							//网络
							if(!empty($val[23])&&trim($val[23])!=''){
								$inArr['network'] = $val[23];
							}
							//设备
							if(!empty($val[24])&&trim($val[24])!=''){
								$inArr['device'] = $val[24];
							}
						}else{                        //非网优模板
							if('网优'==($val[0])){
								throw new Exception;
							}
							//需求人数
							if(!empty($val[14])&&trim($val[14])!=''){
								$inArr['needNum'] = $val[14];
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!需求人数为空</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}

							//已入职的人数
							if(!empty($val[15])&&trim($val[15])!=''){
								$inArr['entryNum'] = $val[15];
							}

							//待入职的人数
							if(!empty($val[16])&&trim($val[16])!=''){
								$inArr['beEntryNum'] = $val[16];
							}

							//在招聘的人数
							if(!empty($val[17])&&trim($val[17])!=''){
							}

							//本周offer姓名
							if(!empty($val[19])&&trim($val[19])!=''){
							}

							//招聘原因/理由
							if(!empty($val[20])&&trim($val[20])!=''){
								$inArr['applyReason'] = $val[20];
							}

							//岗位职责及任职要求
							if(!empty($val[21])&&trim($val[21])!=''){
								$inArr['workDuty'] = $val[21];
							}
							//网络
							if(!empty($val[22])&&trim($val[22])!=''){
								$inArr['network'] = $val[22];
							}
							//设备
							if(!empty($val[23])&&trim($val[23])!=''){
								$inArr['device'] = $val[23];
							}
						}





						//$inArr['formCode']='ZP'.str_replace('-', '', $inArr['formDate']).uniqid();//单据编号
						$inArr['formCode']=uniqid('ZPJH-');//单据编号
						$inArr['ExaStatus']='完成';//审批状态
						if($inArr['state']==''){
							$inArr['state'] = $this->statusDao->statusEtoK ( 'nocheck' );
						}
						$newId = parent::add_d($inArr,true);

						if($newId){
							$tempArr['result'] = '导入成功';
						}else{
							$tempArr['result'] = '<span class="red">导入失败</span>';
						}
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						array_push( $resultArr,$tempArr );
					}
				}
				$this->commit_d();
			}catch (Exception $e){
				$this->rollBack();
				$tempArr['docCode'] = 'error';
				$tempArr['result'] = '<span class="red">模板错误，导入失败，请检查文件中是否存网优、非网优数据</span>';
				$resultArr=array();
				array_push( $resultArr,$tempArr );
			}
				return $resultArr;
			}
		}
	}
	/**
	 *
	 * 改变状态
	 * @param $id
	 * @param $state
	 */
	function changeState($id,$state=2){
		$object['id']=$id;
		$object['state']=$state;
		return $this->updateById($object);
	}
	/**
	 * 对导入excel表字段做过滤
	 * @param unknown_type $val
	 */
	function addslashes($val){

	    if(!get_magic_quotes_gpc()) {
	        $val = addslashes($val);
	    }
	    $val=trim($val);
	    $val = str_replace("_", "\_", $val);
	    $val = str_replace("%", "\%", $val);
	    $val = nl2br($val);
	    $val = htmlspecialchars($val);

	    return $val;

	}


}
?>