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
 * @author Show
 * @Date 2012年5月26日 星期六 11:40:48
 * @version 1.0
 * @description:导师经历信息表 Model层
 */
class model_hr_tutor_tutorrecords extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_records";
		$this->sql_map = "hr/tutor/tutorrecordsSql.php";

		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
			0 => array (
				'statusCName' => '辅导期',
				'key' => '1'
			),
			1 => array (

				'statusCName' => '员工考核',
				'key' => '2'
			),
			2 => array (

				'statusCName' => '导师考核',
				'key' => '3'
			),
			3 => array (

				'statusCName' => '完成',
				'key' => '4'
			),
			4 => array (

				'statusCName' => '已关闭',
				'key' => '5'
			)
		);
		parent :: __construct();
	}
	/******************** 华丽丽的分割线 ******************/
		/*
	 * 通过value查找状态
	 */
	function stateToVal($stateVal) {
		$returnVal = false;
		try {
			foreach ( $this->state as $key => $val ) {
				if ($val ['stateVal'] == $stateVal) {
					$returnVal = $val ['stateCName'];
				}
			}
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
		return $returnVal;
	}

	/*
	 * 通过状态查找value
	 */
	function stateToSta($stateSta) {
		$returnVal = false;
		foreach ( $this->state as $key => $val ) {
			if ($val ['stateEName'] == $stateSta) {
				$returnVal = $val ['stateVal'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}
	/******************** 外部信息获取 ******************/
	/**
	 * 获取人事信息
	 */
	function getPersonnelInfo_d($userAccount) {
		$personnelDao = new model_hr_personnel_personnel();
		$rs = $personnelDao->find(array (
			'userAccount' => $userAccount
		));
		if ($rs['deptNameT']) {
			$rs['deptName'] = $rs['deptNameT'];
			$rs['deptId'] = $rs['deptIdT'];
		} else {
			$rs['deptName'] = $rs['deptNameS'];
			$rs['deptId'] = $rs['deptIdS'];
		}
		return $rs;
	}

	/**
	 * 获取入职人员信息
	 */
	function getEntryInfo_d($entryId) {
		$entryDao = new model_hr_recruitment_entryNotice();
		$rs = $entryDao->get_d($entryId);
		return $rs;
	}/**
	 * 根据用户账号获取信息
	 *
	 */
	 function getInfoByUserNo_d($userNoArr){
		$this->searchArr = array ('userNoArr' => $userNoArr );
		$this->__SET('groupBy', 'c.userNo');
		$rows= $this->listBySqlId ( "select_default" );
		return $rows;
	 }/**
	 * 根据学员用户账号获取信息
	 *
	 */
	 function getInfoByStuUserNo_d($studentNo){
		$this->searchArr = array ('studentNo' => $studentNo );
		$this->__SET('groupBy', 'c.studentNo');
		$rows= $this->listBySqlId ( "select_default" );
		return $rows;
	 }

	/********************** 增删改查 **********************/
	/**
	 * 重写add_d方法
	 */
	function add_d($object) {

		//获取邮件内容
		if (isset ($object['email'])) {
			$emailArr = $object['email'];
			unset ($object['email']);
		}

		try {
			$this->start_d();
			//插入主表信息
			if(!isset($object['status'])){
				$object['status']=1;//指定导师后，就为辅导期
			}
			$newId = parent :: add_d($object, true);
			if ($object['studentNo'] != null) {
				//反写导师信息到基本信息内
				$studentNo = $object['studentNo']; //学员编号
				$tutorName = $object['userName']; //导师名称
				$tutorId = $object['userAccount']; //导师ID
				$sql = "update oa_hr_personnel set tutor='" . $tutorName . "',tutorId='" . $tutorId . "' where userNo='" . $studentNo . "'";
				$this->query($sql);
			}
			$this->commit_d();
			//获取学员的邮箱getPersonnelInfo_d
			$student = $this->getPersonnelInfo_d($object['studentAccount']);
			//发送给导师的邮箱也要发送给学员的邮箱
			$toTutor = $object['userAccount'].','.$object['studentAccount'].','.$object['studentSuperiorId'];

			//发送邮件 ,当操作为提交时才发送 ------ 为了不让邮件影响业务所以加到外面
			if (isset ($emailArr)) {
				if ($emailArr['issend'] == 'y' && !empty ($emailArr['TO_ID'])) {

					$this->thisMail_d($emailArr, $object,$student);
					$this->MailtoTutor($emailArr,$toTutor, $object,$student); //发邮件给导师
				}
			}
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 不指定导师的新增方法
	 */
	function newadd_d($object) {
		$Dao = new model_hr_personnel_personnel();
		$id = $Dao->update(array (
			"id" => $object['perid']
		), array (
			"isNeedTutor" => "1"
		));
		$mailArr = $this->getHRMailInfo_d();
		$this->notutorMail_d($mailArr, $object);
		return $id;
	}

	/**
	 * 重写编辑方法
	 */
	function edit_d($object) {

		//获取邮件内容
		if (isset ($object['email'])) {
			$mailInfo = $object['email'];
			unset ($object['email']);
		}

		try {
			$this->start_d();

			//修改主表信息
			parent :: edit_d($object, true);
			$empId = $object['id'];
			$studentNo = $object['studentNo']; //学员编号
			$tutorName = $object['userName']; //导师名称
			$tutorId = $object['userAccount']; //导师ID
			$sql = "update oa_hr_personnel set tutor='" . $tutorName . "',tutorId='" . $tutorId . "' where userNo='" . $studentNo . "'";
			$this->query($sql);

			$this->commit_d();
			//获取学员的邮箱
			$student = $this->getPersonnelInfo_d($object['studentAccount']);
			//发送给导师的邮箱也要发送给学员的邮箱
			$toTutor = $object['userAccount'].','.$object['studentAccount'];

			//发送邮件 ,当操作为提交时才发送 ------ 为了不让邮件影响业务所以加到外面
			if (isset ($mailInfo)) {
				if ($mailInfo['issend'] == 'y' && !empty ($mailInfo['TO_ID'])) {

					$this->thisMail_d($mailInfo, $object,$student);
					$this->MailtoTutor($mailInfo,$toTutor, $object,$student); //发邮件给导师
				}
			}

			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/******************* 业务逻辑 ************************/
	/**
	 * 关闭导师记录，并填写关闭备注
	 */
	function close_d($object) {
		//将导师记录的状态改为关闭
		$object['status']=5;
		//修改主表信息
		if (parent :: edit_d($object, true)){
			return true;
		}
		else {
			return false;
		}
	}
	/**
	 * 编辑考核分数
	 */
	 function editScore_d($object){
	 	try{
	 		$this->start_d();
			//编辑主表考核分数
			parent::edit_d($object);

			//更新考核表中的分数
			$schemeDao = new model_hr_tutor_scheme();
			$sql = "update oa_hr_tutor_scheme set assessmentScore='".$object['assessmentScore']."'where tutorId=".$object['id']."";
			$this->query($sql);

	 		$this->commit_d();
			return true;
	 	}catch(exception $e){
			$this->rollBack();
			return false;
	 	}
	 }
	 /**
	 * 编辑 是否需要制定辅导计划 是否需要按照HR的模式提交周报
	 */
	 function editModel_d($obj){
	 	//如果为空，则说明未选中，则为 否
		$obj['isCoachplan'] = isset($obj['isCoachplan']) ? "是":"否";
		$obj['isWeekly'] = isset($obj['isWeekly']) ? "是":"否";

		if($this->update(array("id"=>$obj['id']),$obj)){
			return true;
		}else{
			return false;
		}
	 }
	/**
	 * 将一条记录的当前状态改为完成
	 */
	 function complete_d($id){
		$obj = $this->get_d($id);
		$obj['status']=4;
		return parent::edit_d($obj);
	 }
	/**
	 * 邮件配置获取
	 */
	function getMailInfo_d() {

		include (WEB_TOR . "model/common/mailConfig.php");
		$mailArr = isset ($mailUser['oa_hr_tutor_records']) ? $mailUser['oa_hr_tutor_records'] : array (
			'sendUserId' => '',
			'sendName' => ''
		);
		return $mailArr;
	}
	/**
	 * 当学员为研发和产品线时，指定导师的抄送人获取
	 */
	function getMailInfoSpecial_d() {

		include (WEB_TOR . "model/common/mailConfig.php");
		$mailArr = isset ($mailUser['oa_hr_tutor_records2']) ? $mailUser['oa_hr_tutor_records2'] : array (
			'sendUserId' => '',
			'sendName' => ''
		);
		return $mailArr;
	}
	/**
	 * 邮件配置获取HR
	 */
	function getHRMailInfo_d() {

		include (WEB_TOR . "model/common/mailConfig.php");
		$mailArr = isset ($mailUser['tutorReward']) ? $mailUser['tutorReward'] : array (
			'sendUserId' => '',
			'sendName' => ''
		);
		return $mailArr;
	}

	/**
	 * 发送邮件给导师
	 * @param  导师ID,指定导师页面传过来的对象
	 */
	function MailtoTutor($emailArr,$tutorId, $object,$student) {

		$addMsg = $_SESSION['USERNAME'] .
		'已经指定['. $object['deptName'].'\\'.$object['userName'] . ']担任学员[' . $object['studentName'] . '\部门:' . $object['studentDeptName'] . '\职位:' . $student['jobName'] . '\入职日期:' . $student['entryDate'] . ']的导师,<br>' .
		'如有异议请在收到此信息后3个工作日内与部门领导或指定人沟通。<br>' .
		'请导师多费心，给予新员工相应的指引和交流。<br>' .
		'请导师在学员入职后5个工作日内在OA系统制定“辅导计划表”并提交学员上级审核。<br>' .
		'请每周二前在OA系统对学员每周一提交的周报回复指导意见或建议。<br>' .
		'“辅导计划表”或周报提交及回复的OA路径：导航栏--->个人办公--->工作任务--->人事类--->导师工作（选中记录右击可见）<br>' .
		'相关信息敬请查阅附件。';
		$emailDao = new model_common_mail();
		$attachment=array("upfile/download/新进员工导师制度之Q&A.pdf");

		$emailDao->mailWithFile('导师和新同事需要做的工作', $tutorId, $addMsg, $emailArr['ADDIDS'],'upfile/download/新进员工导师制度之Q&A.pdf');

	}
	/**
	 * 邮件发送
	 */
	function thisMail_d($emailArr, $object,$student, $thisAct = '新增') {
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = $_SESSION['USERNAME'] . '已将 [ '. $object['deptName'].'\\'.$object['userName'] . '] 指定为学员[' . $object['studentName'] . '\部门:' . $object['studentDeptName'] . '\职位:' . $student['jobName'] . '\入职日期:' . $student['entryDate'] . '] 的导师。<br><br>';
		$addMsg.=' 欢迎新同事加入我们这个大家庭！<br><br> 请导师做好准备，迎接即将到来的充满着责任、辛苦、成就和幸福的老师生涯！<br><br>公司感谢每一位导师，您那乐于分享经验的精神、对别人的无私付出，使公司的新成员快速适应新岗位的要求，使公司的知识文化得以不断积累和传承！
';
		$emailDao = new model_common_mail();
		$emailDao->mailClear('关于新同事导师的任命', $emailArr['TO_ID'], $addMsg, $emailArr['ADDIDS']);
	}

	/**
	 * 不指定导师时的邮件发送
	 */
	function notutorMail_d($emailArr, $object, $thisAct = '新增') {
		$addMsg = '[' . $object['studentDeptName'] . ']部门[' . $object['studentName'] . ']员工不需要指定导师' . '<br>备注说明:  ' . $object['remark'];

		$emailDao = new model_common_mail();
		$emailDao->mailClear('不指定导师', $emailArr['sendUserId'], $addMsg, null);
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
		$userConutArr = array (); //用户数组
		$userArr = array (); //用户数组
		$deptArr = array (); //部门数组
		$jobsArr = array (); //职位数组
		$otherDataDao = new model_common_otherdatas(); //其他信息查询
		$datadictArr = array (); //数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		$personnelDao = new model_hr_personnel_personnel();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil :: upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register("__autoload");
			//			echo "<pre>";
			if (is_array($excelData)) {

				//行数组循环
				foreach ($excelData as $key => $val) {
					//					echo "<pre>";
					//					print_r($val);
					$actNum = $key +1;
					if (empty ($val[0]) && empty ($val[1]) && empty ($val[2]) && empty ($val[3]) && empty ($val[4]) && empty ($val[5]) && empty ($val[13])) {
						continue;
					} else {
						//新增数组
						$inArr = array ();

						//导师员工编号
						if (!empty ($val[0])) {
							$val[0]=trim($val[0]);
							if (!isset ($userArr[$val[0]])) {
								$rs = $personnelDao->getInfoByUserNo_d($val[0]);
								if (!empty ($rs)) {
									$userConutArr[$val[0]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum . '行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的员工编号</span>';
									array_push($resultArr, $tempArr);
									continue;
								}
							}

							$inArr['userAccount'] = $userConutArr[$val[0]]['userAccount'];
							$inArr['deptId'] = $userConutArr[$val[0]]['belongDeptId'];
							$inArr['deptName'] = $userConutArr[$val[0]]['belongDeptName'];
							$inArr['userNo'] = $val[0];
						} else {
							$tempArr['docCode'] = '第' . $actNum . '条数据';
							$tempArr['result'] = '<span class="red">导入失败!没有员工编号</span>';
							array_push($resultArr, $tempArr);
							continue;
						}

						//导师姓名
						if (!empty ($val[1])) {
							$inArr['userName'] = $val[1];
						} else {
							$tempArr['docCode'] = '第' . $actNum . '条数据';
							$tempArr['result'] = '<span class="red">导入失败!没有员工姓名</span>';
							array_push($resultArr, $tempArr);
							continue;
						}

						//导师职务
						if (!empty ($val[2])&&trim($val[2])!='') {
							$val[2] = trim($val[2]);
							if (!isset ($jobsArr[$val[2]])) {
								$rs = $otherDataDao->getJobId_d($val[2]);
								if (1) {
									$jobsArr[$val[2]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum . '行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的导师职位</span>';
									array_push($resultArr, $tempArr);
									continue;
								}
							}
							$inArr['jobName'] = $val[2];
							$inArr['jobId'] = $jobsArr[$val[2]];
						}

						//学员编号
						if (!empty ($val[3])&&trim($val[3])!='') {
							$val[3] = trim($val[3]);
							if (!isset ($userArr[$val[3]])) {
								$rs = $personnelDao->getInfoByUserNo_d($val[3]);
									$userArr[$val[3]] = $rs;
							}
							$inArr['studentAccount'] = $userArr[$val[3]]['userAccount'];
							$inArr['studentNo'] = $userArr[$val[3]]['userNo'];
						} else {
							$tempArr['docCode'] = '第' . $actNum . '条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的员工编号</span>';
							array_push($resultArr, $tempArr);
							continue;
						}

						//学员姓名
						if (!empty ($val[4])&&trim($val[4])!='') {
							$val[4] = trim($val[4]);
							$inArr['studentName'] = $val[4];
						} else {
							$tempArr['docCode'] = '第' . $actNum . '条数据';
							$tempArr['result'] = '<span class="red">导入失败!没有学员姓名</span>';
							array_push($resultArr, $tempArr);
							continue;
						}

						//学员部门
						if (!empty ($val[5])&&trim($val[5])!='') {
							$val[5] = trim($val[5]);
							if (!isset ($deptArr[$val[5]])) {
								$rs = $otherDataDao->getDeptId_d($val[5]);
								if (1) {
									$deptArr[$val[5]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum . '行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的学员部门</span>';
									array_push($resultArr, $tempArr);
									continue;
								}
							}
							$inArr['studentDeptName'] = $val[5];
							$inArr['studentDeptId'] = $deptArr[$val[5]];
						} else {
							$tempArr['docCode'] = '第' . $actNum . '条数据';
							$tempArr['result'] = '<span class="red">导入失败!没有学员部门</span>';
							array_push($resultArr, $tempArr);
							continue;
						}

						//教学开始日期
						if (!empty ($val[6]) && $val[6] != '0000-00-00'&&trim($val[6])!='') {
							$val[6] = trim($val[6]);

							if (!is_numeric($val[6])) {
								$inArr['beginDate'] = $val[6];
							} else {
								$beginDate = date('Y-m-d', (mktime(0, 0, 0, 1, $val[6] - 1, 1900)));
								if($beginDate=='1970-01-01'){
									$quitDate = date('Y-m-d',strtotime ($val[6]));
									$inArr['beginDate'] = $quitDate;
								}else{
									$inArr['beginDate'] = $beginDate;
								}
							}
						}
						//转正时间
						if (!empty ($val[7])&&trim($val[7])!='') {
							$inArr['becomeDate'] =trim($val[7]);
						}
						//当前状态
						if (!empty ($val[8])&&trim($val[8])!='') {
							$inArr['status'] = $this->statusDao->statusCtoK(trim($val[8])) ;
						}
						//考核分数
						if (!empty ($val[9])&&trim($val[9])!='') {
							$inArr['assessmentScore'] = $val[9];
						}
						//奖励
						if (!empty ($val[10])&&trim($val[10])!='') {
							$inArr['rewardPrice'] = $val[10];
						}
						//关闭理由
						if (!empty ($val[11])&&trim($val[11])!='') {
							$inArr['closeReason'] = $val[11];
						}
						//备注
						if (!empty ($val[12])&&trim($val[12])!='') {
							$inArr['remark'] = $val[12];
						}
						//学员直接上级
						if (!empty ($val[13])&&trim($val[13])!='') {
							$val[13] = trim($val[13]);
							$inArr['studentSuperior'] = $val[13];
						} else {
							$tempArr['docCode'] = '第' . $actNum . '条数据';
							$tempArr['result'] = '<span class="red">导入失败!没有学员直接上级</span>';
							array_push($resultArr, $tempArr);
							continue;
						}

						//						print_r($inArr);
						$newId = parent::add_d($inArr, true);
						if ($newId) {
							$tempArr['result'] = '导入成功';
						} else {
							$tempArr['result'] = '<span class="red">导入失败</span>';
						}
						$tempArr['docCode'] = '第' . $actNum . '条数据';
						array_push($resultArr, $tempArr);
					}
				}
				return $resultArr;
			}
		}
	}

	//导出
	function export($rowdatas) {
		PHPExcel_CachedObjectStorageFactory :: cache_in_memory_serialized;
		//		//创建一个Excel工作流
		//		$objPhpExcelFile = new PHPExcel();

		$objReader = PHPExcel_IOFactory :: createReader('Excel5'); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load("upfile/人资-导师经历导入模版.xls"); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5($objPhpExcelFile);

		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date('H_i_s') . rand(0, 10) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet()->setTitle(iconv("gb2312", "utf-8", '信息列表'));
		//设置表头及样式 设置
		$i = 2;
		if (!count(array_filter($rowdatas)) == 0) {
			$row = $i;
			for ($n = 0; $n < count($rowdatas); $n++) {
				$m = 0;
				foreach ($rowdatas[$n] as $field => $value) {
//					$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $row + $n, iconv("gb2312", "utf-8", $value));
					$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m++;
				}
				$i++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet()->mergeCells('A' . $i . ':' . 'I' . $i);
			for ($m = 0; $m < 10; $m++) {
				$objPhpExcelFile->getActiveSheet()->getStyleByColumnAndRow($m, $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
				$objPhpExcelFile->getActiveSheet()->setCellValueByColumnAndRow($m, $i, iconv("gb2312", "utf-8", '暂无相关信息'));
			}
		}

		//到浏览器
		ob_end_clean(); //解决输出到浏览器出现乱码的问题
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . "导师列表导出.xls" . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$objWriter->save('php://output');
	}
	/******************* E 导入导出系列 ************************/
}
?>