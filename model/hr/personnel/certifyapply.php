<?php

/**
 * @author Show
 * @Date 2012年5月31日 星期四 17:39:42
 * @version 1.0
 * @description:任职资格信息 Model层
 */
include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

class model_hr_personnel_certifyapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_certifyapply";
		$this->sql_map = "hr/personnel/certifyapplySql.php";
		parent :: __construct();
	}

	//需要数据字典处理的字段
    public $datadictFieldArr = array(
		'careerDirection','baseLevel',
		'baseGrade','finalCareer',
		'finalLevel','finalTitle',
		'finalGrade',
        'nowLevel','nowGrade',
        'nowDirection',
        'certifyDirection'
	);

	//状态
	function rtStatus_c($status){
		switch($status){
			case 0 : return '未提交';break;
			case 1 : return '审批中';break;
			case 2 : return '认证表待生成';break;
			case 3 : return '认证准备中';break;
			case 4 : return '认证待审批';break;
			case 5 : return '认证审批中';break;
			case 6 : return '认证待答辩';break;
			case 7 : return '认证结果审核中';break;
			case 8 : return '认证失败';break;
			case 10 : return '认证已审核';break;
			case 11 : return '完成';break;
			case 12 : return '打回';break;
			default : return $status;
		}
	}

	//返回是否通过
	function rtIsPass_d($val){
		if($val == 1){
			return '通过';
		}else if($val === '0'){
			return '不通过';
		}else{
			return '';
		}
	}

	//获取人资信息
	function getPersonnelInfo_d($userId){
		$personnelDao = new model_hr_personnel_personnel();
		$personnelInfo = $personnelDao->find(array('userAccount' => $userId));
		if(!$personnelInfo){
			return false;
		}
		if($personnelInfo['entryDate'] != '0000-00-00' && !empty($personnelInfo['entryDate'])){
			$personnelInfo['workExperience'] = (substr(day_date,0,4) - substr($personnelInfo['entryDate'],0,4));
		}else{
			$personnelInfo['workExperience'] = '';
		}
		return $personnelInfo;
	}

	//获取认证评价表
	function getAssess_d($id){
		$cassessDao = new model_hr_certifyapply_cassess();
		return $cassessDao->find(array('applyId' => $id),null,'id');
	}

	//获取认证评价表
	function getScore_d($cassessId){
		$scoreDao = new model_hr_certifyapply_score();
		return $scoreDao->find(array('cassessId' => $cassessId),null,'id');
	}

	/***************** 增删改查 ******************************/

	//重写add_d
	function add_d($object){
		$object = $this->processDatadict($object);
		$object['status']=11;
		$object['ExaStatus'] = "完成";

		return parent::add_d($object,true);
	}

	//重写edit
	function edit_d($object){
		$object = $this->processDatadict($object);

		return parent::edit_d($object,true);
	}

    //审核编辑，不调用数据字典处理
    function editConfirm_d($object){
        try{
            return parent::edit_d($object,true);

        }catch(exception $e){
            throw $e;
            return false;
        }
    }

	//重写addApply_d
	function addApply_d($object){
        //获取行为要项
        $certifyapplyexp = $object['certifyapplyexp'];
        unset($object['certifyapplyexp']);

		try{
			$this->start_d();

			//数据字典处理
			$object = $this->processDatadict($object);
			if($object['status']==1){
				$object['ExaStatus'] = AUDITING;
			}else{
				$object['ExaStatus'] = WAITAUDIT;

			}

			$newId =  parent::add_d($object,true);

            //处理任务成员
            $certifyapplyexpDao = new model_hr_personnel_certifyapplyexp();
            $certifyapplyexpDao->createBatch($certifyapplyexp,array('applyId' => $newId));

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	//editapply
	function editApply_d($object){
//		echo "<pre>";print_r($object);die();
        //获取行为要项
        $certifyapplyexp = $object['certifyapplyexp'];
        unset($object['certifyapplyexp']);

		try{
			$this->start_d();

			//数据字典处理
			$object = $this->processDatadict($object);

			parent::edit_d($object,true);

            //处理任务成员
            $certifyapplyexpDao = new model_hr_personnel_certifyapplyexp();
            if(is_array($certifyapplyexp)){
            	$certifyapplyexpDao->saveDelBatch($certifyapplyexp);
            }

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}
		//editapply
	function backApply_d($object){
		try{
			$this->start_d();
			$object['status']='12';
			$object['ExaStatus'] = AUDITING;
			parent::edit_d($object,true);

			$row=$this->get_d($object['id']);
			//发送邮件
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$row['userAccount'];
			$emailArr['TO_NAME']=$row['userName'];
			if(is_array($row )){
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>员工姓名</b></td><td><b>申请日期</b></td><td><b>申请通道</b></td><td><b>申请级别</b></td></tr>";

				$userName=$row['userName'];
				$applyDate=$row['applyDate'];
				$careerDirectionName=$row['careerDirectionName'];
				$baseLevelName=$row['baseLevelName'];
				$baseGradeName=$row['baseGradeName'];
					$addmsg .=<<<EOT
					<tr align="center" >
								<td>$userName</td>
								<td>$applyDate</td>
								<td>$careerDirectionName</td>
								<td>$baseLevelName</td>
						</tr>
EOT;
					$addmsg.="</table><br/>";
					$addmsg.="</table><br><font color=red>打回原因：</font><br>".$object['backReason'];
		}
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->emailInquiry($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'certifyapplyBack','您的任职资格申请单已打回','',$emailArr['TO_ID'],$addmsg,1);


			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 更新申请单状态
	 */
	function updateStatus_d($id,$status){
		try{
			$updateArr = array('status' => $status);
			$updateArr = $this->addUpdateInfo($updateArr);

			$this->update(array('id' => $id),$updateArr);
			return true;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}

	/**
	 *提交认证申请
	 */
	function submitApply_d($id){
			$ExaStatus= '部门审批';
			$sql="update oa_hr_personnel_certifyapply set status=1,ExaStatus='".$ExaStatus."' where id=".$id."";
			$flag=$this->query($sql);
			if($flag){
				return true;
			}else{
				return false;
			}
	}

	 		/**
	 *更新员工档案信息
	 *
	 */
	 function aduitPass_d($ids){
	 	try{
			$this->start_d();
			if(is_array($ids)){
				$idArr=$ids;
			}else{
				$idArr=explode(',',$ids);
			}
			$flag=true;
			if(!empty($idArr)){
				foreach($idArr as $key=>$val){
					$info = $this->get_d($val);
					$object['id']=$val;
					$object['status']=2;
					$object['ExaStatus']='完成';
					$id=parent::edit_d($object,true);

					$row=$this->get_d($object['id']);
					//发送邮件
					$emailArr=array();
					$emailArr['issend'] = 'y';
					$emailArr['TO_ID']=$row['userAccount'];
					$emailArr['TO_NAME']=$row['userName'];
					if(is_array($row )){
						$addmsg="";
						$j=0;
						$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>员工姓名</b></td><td><b>申请日期</b></td><td><b>申请通道</b></td><td><b>申请级别</b></td></tr>";

						$userName=$row['userName'];
						$applyDate=$row['applyDate'];
						$careerDirectionName=$row['careerDirectionName'];
						$baseLevelName=$row['baseLevelName'];
						$baseGradeName=$row['baseGradeName'];
							$addmsg .=<<<EOT
							<tr align="center" >
										<td>$userName</td>
										<td>$applyDate</td>
										<td>$careerDirectionName</td>
										<td>$baseLevelName</td>
								</tr>
EOT;
					$addmsg.="</table><br/>";
					$emailDao = new model_common_mail();
					$emailInfo = $emailDao->certifyapply($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'certifyapplyBack','您的任职资格申请单已审核，请准备后续认证工作。','',$emailArr['TO_ID'],$addmsg,1);
				}
			}
			}
			$this->commit_d();
			return $flag;
		 }catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	 }

	/****************** 业务逻辑 ******************************/
	/**
	 *审批成功后在盖章列表添加信息
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid);
		$objId = $folowInfo ['objId'];

	 	$object = $this->get_d($objId);
	 	if($object['ExaStatus'] == AUDITED){
			return 1;
	 	}
	 	return 1;
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

		//数据字段表头配置
		$objNameArr = array (
			0 => 'userNo', //用户编号
			1 => 'userName', //用户名称
			2 => 'applyDate', //申请日期
			3 => 'careerDirectionName', //职业发展通道
		    4 => 'baseLevelName', //级别
			5 => 'baseGradeName', //申请级等
		    7 => 'ExaStatus', //审批结果
		    8 => 'baseScore',//考试得分
		    9 => 'baseResult',//考试结果
		    10 => 'finalResult', //最终认证结果
		    11 => 'finalScore', //认证评价得分
		    12 => 'finalCareerName',//职业发展通道
		    13 => 'finalLevelName',//级别
		    14 => 'finalTitleName',//级别对应的称谓
		    15 => 'finalGradeName',//级等
		    16 => 'finalDate'//认证结果生效时间
		);

		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
			if(is_array($excelData)){
				$objectArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					if(empty($row[0]) && empty($row[1]) && empty($row[2]) && empty($row[3]) && empty($row[4])&& empty($row[5]))
						continue;
					else{
						foreach ( $objNameArr as $index => $fieldName ) {
							$objectArr [$rNum] [$fieldName] = $row [$index];
						}
					}
				}

//				print_r($objectArr);

				//行数组循环
				foreach($objectArr as $key => $val){
//					echo "<pre>";
//					print_r($val);
					if($key === 0){
						continue ;
					}
					$inArr = array();
					$actNum = $key + 2;

					//导师员工编号
					if(!empty($val['userNo'])){
						if(!isset($userArr[$val['userNo']])){
							$rs = $otherDataDao->getUserInfoByUserNo($val['userNo']);
							if(!empty($rs)){
								$userConutArr[$val['userNo']] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的员工编号:'.$val['userNo'] .'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						$inArr['userAccount'] = $userConutArr[$val['userNo']]['USER_ID'];
						$inArr['deptId'] = $userConutArr[$val['userNo']]['DEPT_ID'];
						$inArr['deptName'] = $userConutArr[$val['userNo']]['DEPT_NAME'];
						$inArr['userNo'] = $val['userNo'];
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!没有员工编号</span>';
						array_push( $resultArr,$tempArr );
						continue;
					}

					//员工姓名
					if(!empty($val['userName'])){
						$inArr['userName'] = $val['userName'];
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!没有员工姓名</span>';
						array_push( $resultArr,$tempArr );
						continue;
					}

					//申请日期
					if(!empty($val['applyDate'])&& $val['applyDate'] != '0000-00-00'){
						$val['applyDate'] = trim($val['applyDate']);

						if(!is_numeric($val['applyDate'])){
							$inArr['applyDate'] = $val['applyDate'];
						}else{
							$applyDate = date('Y-m-d',(mktime(0,0,0,1, $val['applyDate'] - 1 , 1900)));
							$inArr['applyDate'] = $applyDate;
						}
					}

					//职业发展通道
					if(!empty($val['careerDirectionName'])){
						$val['careerDirectionName'] = trim($val['careerDirectionName']);
						if(!isset($datadictArr[$val['careerDirectionName']])){
							$rs = $datadictDao->getCodeByName('HRZYFZ',$val['careerDirectionName']);
							if(!empty($rs)){
								$careerDirection = $datadictArr[$val['careerDirectionName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的职业发展通道:'.$val['careerDirectionName'] .'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$careerDirection = $datadictArr[$val['careerDirectionName']]['code'];
						}
						$inArr['careerDirection'] = $careerDirection;
						$inArr['careerDirectionName'] = $val['careerDirectionName'];
					}

					//申请级别
					if(!empty($val['baseLevelName'])){
						$val['baseLevelName'] = trim($val['baseLevelName']);
						if(!isset($datadictArr[$val['baseLevelName']])){
							$rs = $datadictDao->getCodeByName('HRRZJB',$val['baseLevelName']);
							if(!empty($rs)){
								$baseLevel = $datadictArr[$val['baseLevelName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的申请级别:' . $val['baseLevelName'].'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$baseLevel = $datadictArr[$val['baseLevelName']]['code'];
						}
						$inArr['baseLevel'] = $baseLevel;
						$inArr['baseLevelName'] = $val['baseLevelName'];
					}

					//申请级等
					if(!empty($val['baseGradeName'])){
						$val['baseGradeName'] = trim($val['baseGradeName']);
						if(!isset($datadictArr[$val['baseGradeName']])){
							$rs = $datadictDao->getCodeByName('HRRZZD',$val['baseGradeName']);
							if(!empty($rs)){
								$baseGrade = $datadictArr[$val['baseGradeName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的申请级等:' . $val['baseGradeName'].'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$baseGrade = $datadictArr[$val['baseGradeName']]['code'];
						}
						$inArr['baseGrade'] = $baseGrade;
						$inArr['baseGradeName'] = $val['baseGradeName'];
					}

					//审批结果
					if(!empty($val['ExaStatus'])){
						$inArr['ExaStatus'] = $val['ExaStatus'];
					}

					//考试得分
					if(!empty($val['baseScore'])){
						$inArr['baseScore'] = $val['baseScore'];
					}

					//考试结果
					if(!empty($val['baseResult'])){
						if($val['baseResult'] == '通过'){
							$inArr['baseResult'] = 1;
						}else if($val['baseResult'] == '不通过'){
							$inArr['baseResult'] = 0;
						}
					}

					//最终认证结果
					if(!empty($val['finalResult'])){
						if($val['finalResult'] == '通过'){
							$inArr['finalResult'] = 1;
						}else if($val['finalResult'] == '不通过'){
							$inArr['finalResult'] = 0;
						}
					}

					//认证得分
					if(!empty($val['finalScore'])){
						$inArr['finalScore'] = $val['finalScore'];
					}

					//职业发展通道
					if(!empty($val['finalCareerName'])){
						$val['finalCareerName'] = trim($val['finalCareerName']);
						if(!isset($datadictArr[$val['finalCareerName']])){
							$rs = $datadictDao->getCodeByName('HRZYFZ',$val['finalCareerName']);
							if(!empty($rs)){
								$finalCareer = $datadictArr[$val['finalCareerName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的认证职业发展通道:' . $val['finalCareerName'].'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$finalCareer = $datadictArr[$val['finalCareerName']]['code'];
						}
						$inArr['finalCareer'] = $finalCareer;
						$inArr['finalCareerName'] = $val['finalCareerName'];
					}

					//级别
					if(!empty($val['finalLevelName'])){
						$val['finalLevelName'] = trim($val['finalLevelName']);
						if(!isset($datadictArr[$val['finalLevelName']])){
							$rs = $datadictDao->getCodeByName('HRRZJB',$val['finalLevelName']);
							if(!empty($rs)){
								$finalLevel = $datadictArr[$val['finalLevelName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的认证级别:' . $val['finalLevelName'].'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$finalLevel = $datadictArr[$val['finalLevelName']]['code'];
						}
						$inArr['finalLevel'] = $finalLevel;
						$inArr['finalLevelName'] = $val['finalLevelName'];
					}

					//级别对应的称谓
					if(!empty($val['finalTitleName'])){
						$val['finalTitleName'] = trim($val['finalTitleName']);
						if(!isset($datadictArr[$val['finalTitleName']])){
							$rs = $datadictDao->getCodeByName('HRRZCW',$val['finalTitleName']);
							if(!empty($rs)){
								$finalTitle = $datadictArr[$val['finalTitleName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的认证称谓:' . $val['finalTitleName'].'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$finalTitle = $datadictArr[$val['finalTitleName']]['code'];
						}
						$inArr['finalTitle'] = $finalTitle;
						$inArr['finalTitleName'] = $val['finalTitleName'];
					}

					//级等
					if(!empty($val['finalGradeName'])){
						$val['finalGradeName'] = trim($val['finalGradeName']);
						if(!isset($datadictArr[$val['finalGradeName']])){
							$rs = $datadictDao->getCodeByName('HRRZZD',$val['finalGradeName']);
							if(!empty($rs)){
								$finalGrade = $datadictArr[$val['finalGradeName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的认证级等:' . $val['finalGradeName'].'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$finalGrade = $datadictArr[$val['finalGradeName']]['code'];
						}
						$inArr['finalGrade'] = $finalGrade;
						$inArr['finalGradeName'] = $val['finalGradeName'];
					}

					//申请
					if(!empty($val['finalDate'])&& $val['finalDate'] != '0000-00-00'){
						$val['finalDate'] = trim($val['finalDate']);

						if(!is_numeric($val['finalDate'])){
							$inArr['finalDate'] = $val['finalDate'];
						}else{
							$finalDate = date('Y-m-d',(mktime(0,0,0,1, $val['finalDate'] - 1 , 1900)));
							$inArr['finalDate'] = $finalDate;
						}
					}
					$inArr['status'] = '11';
//					print_r($inArr);
					$newId = $this->add_d($inArr,true);
					if($newId){
						$tempArr['result'] = '导入成功';
					}else{
						$tempArr['result'] = '<span class="red">导入失败</span>';
					}
					$tempArr['docCode'] = '第' . $actNum .'条数据';
					array_push( $resultArr,$tempArr );
				}

				return $resultArr;
			}
		}
	}

	function updataExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
		$updataArr = array();//更新数组

		$userConutArr = array();//用户数组
		$userArr = array();//用户数组
		$deptArr = array();//部门数组
		$jobsArr = array();//职位数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();

		//数据字段表头配置
		$objNameArr = array (
			0 => 'userNo', //用户编号
			1 => 'userName', //用户名称
			2 => 'applyDate', //申请日期
			3 => 'careerDirectionName', //职业发展通道
		    4 => 'baseLevelName', //级别
			5 => 'baseGradeName', //申请级等
		    7 => 'ExaStatus', //审批结果
		    8 => 'baseScore',//考试得分
		    9 => 'baseResult',//考试结果
		    10 => 'finalResult', //最终认证结果
		    11 => 'finalScore', //认证评价得分
		    12 => 'finalCareerName',//职业发展通道
		    13 => 'finalLevelName',//级别
		    14 => 'finalTitleName',//级别对应的称谓
		    15 => 'finalGradeName',//级等
		    16 => 'finalDate'//认证结果生效时间
		);

		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
			if(is_array($excelData)){
				$objectArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					if(empty($row[0]) && empty($row[1]) && empty($row[2]) && empty($row[3]) && empty($row[4])&& empty($row[5]))
						continue;
					else{
						foreach ( $objNameArr as $index => $fieldName ) {
							$objectArr [$rNum] [$fieldName] = $row [$index];
						}
					}
				}
			}
			//print_r($objectArr);
			foreach($objectArr as $key => $val){
				$actNum = $key + 2;
				if($key === 0){
						continue ;
					}
					//导师员工编号
					if(!empty($val['userNo'])){
						$updataArr['userNo'] = $val['userNo'];
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!没有员工编号</span>';
						array_push( $resultArr,$tempArr );
						continue;
					}

				//考试得分
				if(!empty($val['baseScore'])){
					$updataArr['baseScore'] = $val['baseScore'];
				}

				//考试结果
				if(!empty($val['baseResult'])){
					if($val['baseResult']=="通过"){
						$updataArr['baseResult'] = 1;
					}else{
						$updataArr['baseResult'] = 0;
					}
				}
				$id=$this->update(array("userNo"=>$updataArr['userNo']),array("baseScore"=>$updataArr['baseScore'],"baseResult"=>$updataArr['baseResult']));
				if($id){
					$tempArr['result'] = '导入成功';
				}else{
					$tempArr['result'] = '<span class="red">导入失败</span>';
				}
				$tempArr['docCode'] = '第' . $actNum .'条数据';
				array_push( $resultArr,$tempArr );
			}
			return $resultArr;
		}
	}

		/**
	 * 导入更新信息
	 *
	 */
	 function updataCertifyapplyData_d(){
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
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//行数组循环
				foreach($excelData as $key => $val){
//					if($key === 0){
//						continue ;
//					}
					$actNum = $key + 2;
					if(0){
						continue;
					}else{
						//新增数组
						$inArr = array();

						//员工编号
						if(!empty($val[0])&&trim($val[0])!=''){
							$val[0]=trim($val[0]);
							$inArr['userNo'] = $val[0];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!员工编号为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//现属级别
						if(!empty($val[4])&&trim($val[4])!=''){
							$val[4]=trim($val[4]);
							$rs = $datadictDao->getCodeByName('HRRZJB',$val[4]);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val[4]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!现属级别不正确</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
							$inArr['nowLevel'] = $incentiveType;
							$inArr['nowLevelName'] = $val[4];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!现属级别为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}


						//现属级等
						if(!empty($val[5])&&trim($val[5])!=''){
							$val[5]=trim($val[5]);
							$rs = $datadictDao->getCodeByName('HRRZZD',$val[5]);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val[5]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!现属级等不正确</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
							$inArr['nowGrade'] = $incentiveType;
							$inArr['nowGradeName'] = $val[5];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!现属级等为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}
						//申请日期
						if(!empty($val[8])&& $val[8] != '0000-00-00'&&trim($val[8])!=''){
							$val[8] = trim($val[8]);
							if(!is_numeric($val[8])){
								$inArr['applyDate'] = $val[8];
							}else{
								$applyDate = date('Y-m-d',(mktime(0,0,0,1, $val[8] - 1 , 1900)));
								if($applyDate=='1970-01-01'){
									$quitDate = date('Y-m-d',strtotime ($val[8]));
									$inArr['applyDate'] = $quitDate;
								}else{
									$inArr['applyDate'] = $applyDate;
								}
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!申请日期为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}




						//申请通道
						if(!empty($val[9])&&trim($val[9])!=''){
							$val[9]=trim($val[9]);
							$rs = $datadictDao->getCodeByName('HRZYFZ',$val[9]);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val[9]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!申请通道不正确</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
							$inArr['careerDirection'] = $incentiveType;
							$inArr['careerDirectionName'] = $val[9];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!申请通道为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//申请级别
						if(!empty($val[10])&&trim($val[10])!=''){
							$val[10]=trim($val[10]);
							$rs = $datadictDao->getCodeByName('HRRZJB',$val[10]);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val[10]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!申请级别不正确</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
							$inArr['baseLevel'] = $incentiveType;
							$inArr['baseLevelName'] = $val[10];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!申请级别为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//考试得分
						if(!empty($val[13])&&trim($val[13])!=''){
							$val[13] = trim($val[13]);
							if ($val[13] >= 0 && $val[13] <= 100) {
								$inArr['baseScore'] = $val[13];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!考试得分不在0~100之间</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//考试结果
						if(!empty($val[14])&&trim($val[14])!=''){
							$val[14] = trim($val[14]);
							if($val[14] == '通过'){
								$inArr['baseResult'] = 1;
							}else if($val[14] == '不通过'){
								$inArr['baseResult'] = 0;
							}else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!考试结果不正确</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//认证结果
						if(!empty($val[15])&&trim($val[15])!=''){
							$val[15] = trim($val[15]);
							if($val[15] == '通过'){
								$inArr['finalResult'] = 1;
							}else if($val[15] == '不通过'){
								$inArr['finalResult'] = 0;
							}else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!认证结果不正确</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//认证得分
						if(!empty($val[16])&&trim($val[16])!=''){
							$val[16] = trim($val[16]);
							if ($val[16] >= 0 && $val[16] <= 100) {
								$inArr['finalScore'] = $val[16];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!认证得分不在0~100之间</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//认证通道
						if(!empty($val[17])&&trim($val[17])!=''){
							$val[17] = trim($val[17]);
							if(!isset($datadictArr[$val[17]])){
								$rs = $datadictDao->getCodeByName('HRZYFZ',$val[17]);
								if(!empty($rs)){
									$finalCareer = $datadictArr[$val[17]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的认证通道:' . $val[17].'</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$finalCareer = $datadictArr[$val[17]]['code'];
							}
							$inArr['finalCareer'] = $finalCareer;
							$inArr['finalCareerName'] = $val[17];
						}

						//认证级别
						if(!empty($val[18])&&trim($val[18])!=''){
							$val[18] = trim($val[18]);
							if(!isset($datadictArr[$val[18]])){
								$rs = $datadictDao->getCodeByName('HRRZJB',$val[18]);
								if(!empty($rs)){
									$finalLevel = $datadictArr[$val[18]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的认证级别:' . $val[18].'</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$finalLevel = $datadictArr[$val[18]]['code'];
							}
							$inArr['finalLevel'] = $finalLevel;
							$inArr['finalLevelName'] = $val[18];
						}

						//认证级等
						if(!empty($val[19])&&trim($val[19])!=''){
							$val[19] = trim($val[19]);
							if(!isset($datadictArr[$val[19]])){
								$rs = $datadictDao->getCodeByName('HRRZZD',$val[19]);
								if(!empty($rs)){
									$finalGrade = $datadictArr[$val[19]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的认证级等:' . $val[19].'</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$finalGrade = $datadictArr[$val[19]]['code'];
							}
							$inArr['finalGrade'] = $finalGrade;
							$inArr['finalGradeName'] = $val[19];
						}

						//认证称谓
						if(!empty($val[20])&&trim($val[20])!=''){
							$val[20] = trim($val[20]);
							if(!isset($datadictArr[$val[20]])){
								$rs = $datadictDao->getCodeByName('HRRZCW',$val[20]);
								if(!empty($rs)){
									$finalTitle = $datadictArr[$val[20]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的认证称谓:' . $val[20].'</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$finalTitle = $datadictArr[$val[20]]['code'];
							}
							$inArr['finalTitle'] = $finalTitle;
							$inArr['finalTitleName'] = $val[20];
						}

						//认证结果生效日期
						if(!empty($val[21])&& $val[21] != '0000-00-00'){
							$val[21] = trim($val[21]);
							if(!is_numeric($val[21])){
								$inArr['finalDate'] = $val[21];
							}else{
								$finalDate = date('Y-m-d',(mktime(0,0,0,1, $val[21] - 1 , 1900)));
								$inArr['finalDate'] = $finalDate;
							}
						}

						$id=$this->get_table_fields('oa_hr_personnel_certifyapply', "userNo='".$inArr['userNo']."' and applyDate='".$inArr['applyDate']."' and careerDirection='".$inArr['careerDirection']."' and baseLevel='".$inArr['baseLevel']."'", 'id');
						if($id){
							/*
							$newId = $this->update(
							array("id" => $id) ,
							array("nowLevel"=>$inArr['nowLevel'] ,"nowLevelName"=>$inArr['nowLevelName'] ,"nowGrade"=>$inArr['nowGrade'] ,"nowGradeName"=>$inArr['nowGradeName'] ,"baseScore"=>$inArr['baseScore'] ,"baseResult"=>$inArr['baseResult'] ,"finalResult"=>$inArr['finalResult'] ,"finalScore"=>$inArr['finalScore'] ,"finalCareerName"=>$inArr['finalCareerName'] ,"finalLevelName"=>$inArr['finalLevelName'] ,"finalGradeName"=>$inArr['finalGradeName'] ,"finalTitleName"=>$inArr['finalTitleName'] ,"finalDate"=>$inArr['finalDate'] ,"finalCareer"=>$inArr['finalCareer'] ,"finalLevel"=>$inArr['finalLevel'] ,"finalGrade"=>$inArr['finalGrade'] ,"finalTitle"=>$inArr['finalTitle'])
							);
							*/
							$inArr['id'] = $id;
							$newId = parent::edit_d($inArr,true);
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!该任职资格申请记录不存在</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						if($newId){
							$tempArr['result'] = '更新成功';
						}else{
							$tempArr['result'] = '更新失败';
						}
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}

		/**
		 * 导出excel
		 */
		function excelOut($rowdatas){
			PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
			//		//创建一个Excel工作流
			//		$objPhpExcelFile = new PHPExcel();


			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
			$objPhpExcelFile = $objReader->load ( "upfile/人资-任职资格导出模版.xls" ); //读取模板
			//Excel2003及以前的格式
			$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

			//设置保存的文件名称及路径
			$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
			$path = "D:/EXCELDEMO";
			$fileSave = $path . "/" . $fileName;
			//以下是对工作表基本参数的设置
			//设置当前工作表的名称
			$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '信息列表' ) );
			//设置表头及样式 设置
			$i = 2;
			if (! count ( array_filter ( $rowdatas ) ) == 0) {
				$row = $i;
				for($n = 0; $n < count ( $rowdatas ); $n ++) {
					$m = 0;
					foreach ( $rowdatas [$n] as $field => $value ) {
						$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
						$m ++;
					}
					$i ++;
				}
			} else {
				$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'AB' . $i );
				for($m = 0; $m < 10; $m ++) {
					$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
					$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
				}
			}
			//到浏览器
			ob_end_clean (); //解决输出到浏览器出现乱码的问题
			header ( "Content-Type: application/force-download" );
			header ( "Content-Type: application/octet-stream" );
			header ( "Content-Type: application/download" );
			header ( 'Content-Disposition:inline;filename="' . "人资-任职资格导出.xls" . '"' );
			header ( "Content-Transfer-Encoding: binary" );
			header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
			header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
			header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
			header ( "Pragma: no-cache" );
			$objWriter->save ( 'php://output' );
		}
	/******************* E 导入导出系列 ************************/
}
?>