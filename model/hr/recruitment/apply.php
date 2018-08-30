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
 * @author Administrator
 * @Date 2012年7月11日 星期三 13:20:21
 * @version 1.0
 * @description:增员申请表 Model层
 */
class model_hr_recruitment_apply  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitment_apply";
		$this->sql_map = "hr/recruitment/applySql.php";

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
			// 5 => array (
			// 	'statusEName' => 'closed',
			// 	'statusCName' => '关闭',
			// 	'key' => '5'
			// ),
			// 6 => array (
			// 	'statusEName' => 'suspend',
			// 	'statusCName' => '挂起',
			// 	'key' => '6'
			// ),
			7 => array (
				'statusEName' => 'cancel',
				'statusCName' => '取消',
				'key' => '7'
			),
			8 => array (
				'statusEName' => 'submit',
				'statusCName' => '提交',
				'key' => '8'
			)
		);

		//服务线部门id
		$this->serviceLine = array('120','210','211','212','213','214','215','217','218','219','228');

		parent::__construct ();
	}
	/*****************************************************显示分割线**********************************************/

	/*
	 * 通过value查找状态
	 */
	function stateToVal($stateVal) {
		$returnVal = false;
		try {
			foreach ( $this->state as $key => $val ) {
				if ($val['stateVal'] == $stateVal) {
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

	/*****************************************************显示分割线**********************************************/


	/**
	 * 添加增员申请信息
	 */
	function add_d($object){
		try{
			$this->start_d();
			$object['formCode'] = 'ZY'.date ( "YmdHis" );//单据编号
			$dictDao = new model_system_datadict_datadict();
			//update chenrf 20130508
			if(!empty($object['actType']) && 'state' == $object['actType']) {      //状态为提交
				$object['state'] = $this->statusDao->statusEtoK ( 'submit' );
			} else {
				$object['state'] = $this->statusDao->statusEtoK ( 'save' );
			}
			$object['addType'] = $dictDao->getDataNameByCode($object['addTypeCode']);
			$object['employmentType'] = $dictDao->getDataNameByCode($object['employmentTypeCode']);
			$object['maritalStatusName'] = $dictDao->getDataNameByCode($object['maritalStatus']);
			if(isset($object['education'])){
				$object['educationName'] = $dictDao->getDataNameByCode($object['education']);
			}
			$object['postTypeName'] = $dictDao->getDataNameByCode($object['postType']);
			$object['ExaStatus'] = '未提交';
			$object['entryNum'] = 0;//入职人数
			$object['beEntryNum'] = 0;//待入职人数
			$object['ingtryNum'] = $object['needNum'] - $object['entryNum'] - $object['beEntryNum'];//在招聘人数
			if($object['useAreaId'] > 0) {
				$object ['useAreaName'] = $this->get_table_fields('area', "ID='".$object['useAreaId']."'", 'Name');
			}
			$id = parent::add_d($object,true);

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
	 * 编辑增员申请信息
	 */
	function edit_d($object){
		try{
			$this->start_d();

			$needNum = $object['needNum'];//需求人数
			$entryNum = $object['entryNum'];//已入职人数
			$beEntryNum = $object['beEntryNum'];  //待入职人数
			$object['ingtryNum'] = $needNum - $entryNum - $beEntryNum;//在招聘人数

			$dictDao = new model_system_datadict_datadict();
			$object['addType'] = $dictDao->getDataNameByCode($object['addTypeCode']);
			$object['employmentType'] = $dictDao->getDataNameByCode($object['employmentTypeCode']);
			$object['maritalStatusName'] = $dictDao->getDataNameByCode($object['maritalStatus']);
			$object['educationName'] = $dictDao->getDataNameByCode($object['education']);
			$object['postTypeName'] = $dictDao->getDataNameByCode($object['postType']);
			$id = parent::edit_d($object ,true);
			//更新附件关联关系
			$this->updateObjWithFile($object['id']);
			$this->commit_d();
			return $object['id'];
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 编辑关键要点
	 */
	function editKeyPoints($object) {
		try{
			$this->start_d();
			$id = parent::edit_d($object ,true);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * 编辑增员申请信息
	 */
	function editState_d($object) {
		try{
			$this->start_d();

			$oldObj = $this->get_d($object['id']);
			$stateName = $this->statusDao->statusKtoC($object['state']);
			if ($object['state'] == 2) {
				$stateName = '启用';
			}
			$reason = $_SESSION['USERNAME'].'&nbsp;&nbsp;'.date('Y-m-d H:i:s').'<br>'.$stateName.'原因：'.$object['reasonRemark'].'<breakpoint>';

			if ($object['state'] == 3 || $object['state'] == 2) { //暂停和启用
				$object['stopStart'] = $oldObj['stopStart'].$reason;
			} else if ($object['state'] == 7) { //取消
				$object['cancelContent'] = $reason;
			}

			$rs = parent::edit_d($object ,true);

			$this->commit_d();
			return $rs;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 分配负责人
	 */
	function assignHead_d($object){
		try{
			$this->start_d();
			$id = parent::edit_d($object ,true);
			if(!empty($object['assistManId'])) {//添加协助人
				$assistManName = explode(",",$object['assistManName']);
				$assistManId = explode(",",$object['assistManId']);
				$memberDao = new model_hr_recruitment_applymember();
				foreach($assistManId as $key=>$val){
					$member['parentId'] = $object['id'];
					$member['formCode'] = $object['formCode'];
					$member['assesManName'] = $assistManName[$key];
					$member['assesManId'] = $val;
					$memberDao->add_d($member);
				}
			}
			$this->passedEmail_d($object);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * 修改负责人
	 */
	function editHead_d($object){
		try{
			$this->start_d();
			$apply = $this -> get_d($object['id']);
			$id = parent::edit_d($object ,true);

			//协助人处理
			$memberDao = new model_hr_recruitment_applymember();
			if ($apply['assistManName'] != $object['assistManName']) {
				$memberDao->delete(array('parentId'=>$object['id']));
				if(!empty($object['assistManId'])) {
					$assistManName = explode(",",$object['assistManName']);
					$assistManId = explode(",",$object['assistManId']);
					foreach($assistManId as $key=>$val){
						$member['parentId'] = $object['id'];
						$member['formCode'] = $object['formCode'];
						$member['assesManName'] = $assistManName[$key];
						$member['assesManId'] = $val;
						$memberDao->add_d($member);
					}
				}
			}

			if($apply['recruitManName'] != $object['recruitManName'] || $apply['assistManName'] != $object['assistManName']){
				$this->passedEmail_d($object);
			}
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * 处理列表数据
	 */
	function dealRows_d($rows,$sumrows){
		$sumNeedNum=0;
		$sumEntryNum=0;
		$sumBeEntryNum=0;
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				//计算数量总数量
				$sumNeedNum=bcadd($sumNeedNum,$val ['needNum']);
				$sumEntryNum=bcadd($sumEntryNum,$val ['entryNum']);
				$sumBeEntryNum=bcadd($sumBeEntryNum,$val ['beEntryNum']);
				$rows[$key]['stateC'] = $this->statusDao->statusKtoC( $val ['state'] );
				$rows[$key]['viewType']=1;//用来区分是数据还是总计
			}

			//总计统计
			$sumNeedNumAll=0;
			$sumEntryNumAll=0;
			$sumBeEntryNumAll=0;

			foreach($sumrows as $sumKey=>$sumVal){
				//计算数量总数量
				$sumNeedNumAll=bcadd($sumNeedNumAll,$sumVal ['needNum']);
				$sumEntryNumAll=bcadd($sumEntryNumAll,$sumVal ['entryNum']);
				$sumBeEntryNumAll=bcadd($sumBeEntryNumAll,$sumVal['beEntryNum']);

			}
		}
		return $rows;
	}

	/**
	 * 改变状态
	 */
	function changeState_d($object){
		try{
			$this->start_d();
			parent::edit_d($object ,true);
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 经理审批后反馈，发送邮件
	 *@param $id 内部推荐ID
	 */
	function passedEmail_d($object) {
		try {
			$this->start_d();
			$apply = $this->get_d($object['id']);
			$emailArr = array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID'] = $apply['recruitManId'].",".$apply['assistManId'].",".$apply['formManId'];
			$emailArr['TO_NAME'] = $apply['recruitManName'].",".$apply['assistManName'].",".$apply['formManName'];
			if ($emailArr['TO_ID']) {
				$addmsg = "";
				$deptName = $apply['deptName'];
				$postTypeName = $apply ['postTypeName'];
				$positionName = $apply ['positionName'];
				$needNum = $apply['needNum'];
				$hopeDate = $apply['hopeDate'];
				$recruitManName = $object['recruitManName'];
				$assistManName = $object['assistManName'];
				$addmsg .=  <<<EOT
				<table width="500px">
					<thead>
						<tr align="center">
							<td><b>需求部门</b></td>
							<td><b>职位类型</b></td>
							<td><b>需求职位</b></td>
							<td><b>需求人数</b></td>
							<td><b>希望到岗时间</b></td>
						</tr>
					</thead>
					<tbody>
					<tr align="center" >
							<td>$deptName</td>
							<td>$postTypeName</td>
							<td>$positionName</td>
							<td>$needNum</td>
							<td>$hopeDate</td>
						</tr>
					</tbody>
					</table>
EOT;
				$addmsg .= "<br>审核结果：";
				$addmsg .= "<font color='blue'>通过</font>";
				$addmsg .= "<br>负责人：";
				$addmsg .= "<font color='blue'>$recruitManName</font>";
				$addmsg .= "<br>协助人：";
				$addmsg .= "<font color='blue'>$assistManName</font>";

				$emailDao = new model_common_mail();
				$emailDao -> emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'apply_passed', '该邮件为增员申请通过通知', '', $emailArr['TO_ID'], $addmsg, 1);
			}

			$this -> commit_d();
			return true;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}

	}

	/**
	 *更新在招聘人数人数
	 */
	//update chenrf 20130604 完成入职同时更新在招聘人数
	function updateEntryNum($id,$entryNum){
		$sql = " update ".$this->tbl_name." set entryNum=(ifnull(entryNum,0) + $entryNum), beEntryNum=(ifnull(beEntryNum,1) - 1) where id=$id ";
		return $this->query($sql);
	}

	/**
	 * 发送录用通知和放弃入职同时更新在招聘人数
	 */
	function updateBeEntryNum($id,$beEntryNum){
		$sql = " update ".$this->tbl_name." set beEntryNum=(ifnull(beEntryNum,0) + $beEntryNum) where id=$id ";
		return $this->query($sql);
	}


	/*****************************导出导入*****************************/
	function addExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
		$userArr = array();//用户数组
		$deptArr = array();//部门数组
		$jobsArr = array();//职位数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");

			if(is_array($excelData)) {
				//数据下标
				$keyArr = array(
					'formCode', //单据编号
					'state', //单据状态
					'ExaStatus', //审批状态
					'formManName', //填表人
					'resumeToName', //接口人
					'deptName', //直属部门
					'deptNameS', //二级部门
					'deptNameT', //三级部门
					'deptNameF', //四级部门
					'workPlace', //工作地点
					'postTypeName', //职位类型
					'positionName', //需求职位
					'developPositionName', //研发职位
					'network', //网络
					'device', //设备
					'positionLevel', //级别
					'projectGroup', //所在项目组
					'isEmergency', //是否紧急
					'tutor', //导师
					'computerConfiguration', //电脑配置
					'formDate', //填表日期
					'ExaDT', //审批通过时间
					'assignedDate', //下达日期
					'createTime', //录用日期
					'entryDate', //到岗时间
					'firstOfferTime', //第一个offer时间
					'lastOfferTime', //最后一个offer时间
					'addType', //增员类型
					'needNum', //需求人数
					'entryNum', //已入职人数
					'beEntryNum', //待入职人数
					'stopCancelNum', //暂停取消人数
					'ingtryNum', //在招聘人数
					'recruitManName', //招聘负责人
					'assistManName', //招聘协助人
					'userName', //录用名单
					'applyReason', //需求原因
					'workDuty', //工作职责
					'jobRequire', //任职要求
					'keyPoint', //关键要点
					'attentionMatter', //注意事项
					'leaderLove', //部门领导喜好
					'applyRemark' //进度备注
				);
				//数据格式转换
				$newData = array();
				foreach ($excelData as $key => $val) {
					if(!empty($val[3]) && !empty($val[5])){
						$tmpData = array();
						foreach ($keyArr as $k => $v) {
							$tmpData[$v] = trim($val[$k]);
						}
						array_push($newData ,$tmpData);
					}
				}
			}

			if (!empty($newData)) {
				//行数组循环
				foreach($newData as $key => $val){
					$actNum = $key + 2; //数据行号
					$inArr = array(); //新增数组

					//状态
					if(!empty($val['state'])) {
						$inArr['state'] = $this->statusDao->statusCtoK($val['state']) ;
					} else {
						$inArr['state'] = $this->statusDao->statusEtoK('nocheck');
					}

					//审批状态
					if(!empty($val['ExaStatus'])) {
						if($val['ExaStatus'] == '完成' || $val['ExaStatus'] == '未提交' || $val['ExaStatus'] == '部门审批') {
							$inArr['ExaStatus'] = $val['ExaStatus'];//审批状态
						} else {
							$inArr['ExaStatus'] = '完成'; //审批状态
						}
					}

					//填表人
					if(!empty($val['formManName'])) {
						if(!isset($userArr[$val['formManName']])){
							$rs = $otherDataDao->getUserInfo($val['formManName']);
							if(!empty($rs)){
								$userArr[$val['formManName']] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的填表人</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['formManName'] = $val['formManName'];
						$inArr['formManId'] = $userArr[$val['formManName']]['USER_ID'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!填表人为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//部门接口人
					if(!empty($val['resumeToName'])) {
						if(!isset($userArr[$val['resumeToName']])) {
							$rs = $otherDataDao->getUserInfo($val['resumeToName']);
							if(!empty($rs)){
								$userArr[$val['resumeToName']] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的接口人</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['resumeToName'] = $val['resumeToName'];
						$inArr['resumeToId'] = $userArr[$val['resumeToName']]['USER_ID'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!接口人为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//直属部门
					if(!empty($val['deptName'])) {
						if(!isset($deptArr[$val['deptName']])) {
							$rs = $otherDataDao->getDeptId_d($val['deptName']);
							if(!empty($rs)){
								$deptArr[$val['deptName']] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的直属部门</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['deptName'] = $val['deptName'];
						$inArr['deptId'] = $deptArr[$val['deptName']];
					}else{
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!直属部门为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//二级部门
					if(!empty($val['deptNameS'])) {
						if(!isset($deptArr[$val['deptNameS']])) {
							$rs = $otherDataDao->getDeptId_d($val['deptNameS']);
							if(!empty($rs)){
								$deptArr[$val['deptNameS']] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的二级部门</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['deptName'] = $val['deptNameS'];
						$inArr['deptId'] = $deptArr[$val['deptNameS']];
					}

					//三级部门
					if(!empty($val['deptNameT'])) {
						if(!isset($deptArr[$val['deptNameT']])) {
							$rs = $otherDataDao->getDeptId_d($val['deptNameT']);
							if(!empty($rs)){
								$deptArr[$val['deptNameT']] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的三级部门</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['deptName'] = $val['deptNameT'];
						$inArr['deptId'] = $deptArr[$val['deptNameT']];
					}

					//四级部门
					if(!empty($val['deptNameF'])) {
						if(!isset($deptArr[$val['deptNameF']])) {
							$rs = $otherDataDao->getDeptId_d($val['deptNameF']);
							if(!empty($rs)){
								$deptArr[$val['deptNameF']] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的四级部门</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['deptName'] = $val['deptNameF'];
						$inArr['deptId'] = $deptArr[$val['deptNameF']];
					}

					//工作地点
					if(!empty($val['workPlace'])) {
						$inArr['workPlace'] = $val['workPlace'];
					}else{
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!工作地点为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//职位类型
					if(!empty($val['postTypeName'])) {
						if(!isset($datadictArr[$val['postTypeName']])){
							$rs = $datadictDao->getCodeByName('YPZW' ,$val['postTypeName']);
							if(!empty($rs)){
								$trainsType = $datadictArr[$val['postTypeName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的职位类型</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$trainsType = $datadictArr[$val['postTypeName']]['code'];
						}
						$inArr['postType'] = $trainsType;
						$inArr['postTypeName'] = $val['postTypeName'];
					}else{
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!职位类型为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//需求职位
					if(!empty($val['positionName'])) {
						if(!isset($jobsArr[$val['positionName']])){
							$rs = $otherDataDao->getJobId_d($val['positionName']);
							if(!empty($rs)){
								$jobsArr[$val['positionName']] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的职位</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['positionName'] = $val['positionName'];
						$inArr['positionId'] = $jobsArr[$val['positionName']];
					}else{
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!职位为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//研发职位
					if(!empty($val['developPositionName'])) {
						$inArr['developPositionName'] = $val['developPositionName'];
					}

					//网络
					if(!empty($val['network'])) {
						$inArr['network'] = $val['network'];
					}

					//设备
					if(!empty($val['device'])) {
						$inArr['device'] = $val['device'];
					}

					//级别
					if(!empty($val['positionLevel'])) {
						$inArr['positionLevel'] = $val['positionLevel'];
					}else{
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!级别为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//所在项目组
					if(!empty($val['projectGroup'])) {
						$inArr['projectGroup'] = $val['projectGroup'];
					}

					//是否紧急
					if(!empty($val['isEmergency'])) {
						if($val['isEmergency'] == '是') {
							$inArr['isEmergency'] = 1;
						}else if($val['isEmergency'] == '否') {
							$inArr['isEmergency'] = 0;
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!是否紧急请填是或否</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!是否紧急为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//导师
					if(!empty($val['tutor'])) {
						if(!isset($userArr[$val['tutor']])) {
							$rs = $otherDataDao->getUserInfo($val['tutor']);
							if(!empty($rs)){
								$userArr[$val['tutor']] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的导师</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['tutor'] = $val['tutor'];
						$inArr['tutorId'] = $userArr[$val['tutor']]['USER_ID'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!导师为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//电脑配置
					if(!empty($val['computerConfiguration'])) {
						if ($val['computerConfiguration'] == '公司提供笔记本电脑'
								|| $val['computerConfiguration'] == '公司提供台式电脑'
								|| $val['computerConfiguration'] == '自备笔记本电脑') {
							$inArr['computerConfiguration'] = $val['computerConfiguration'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的电脑配置</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!电脑配置为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//填表日期
					if(!empty($val['formDate'])) {
						if (!is_numeric($val['formDate'])) {
							$inArr['formDate'] = $val['formDate'];
						} else {
							$beginDate = date('Y-m-d' ,(mktime(0, 0, 0, 1, $val['formDate'] - 1, 1900)));
							if($beginDate == '1970-01-01') {
								$quitDate = date('Y-m-d' ,strtotime($val['formDate']));
								$inArr['formDate'] = $quitDate;
							}else{
								$inArr['formDate'] = $beginDate;
							}
						}
					}
					
					//下达日期
					if(!empty($val['assignedDate'])) {
						if (!is_numeric($val['assignedDate'])) {
							$inArr['assignedDate'] = $val['assignedDate'];
						} else {
							$beginDate = date('Y-m-d' ,(mktime(0, 0, 0, 1, $val['assignedDate'] - 1, 1900)));
							if($beginDate == '1970-01-01') {
								$quitDate = date('Y-m-d' ,strtotime($val['assignedDate']));
								$inArr['assignedDate'] = $quitDate;
							}else{
								$inArr['assignedDate'] = $beginDate;
							}
						}
					}
					
					//审批通过时间
					if(!empty($val['ExaDT'])) {
						if (!is_numeric($val['ExaDT'])) {
							$inArr['ExaDT'] = $val['ExaDT'];
						} else {
							$beginDate = date('Y-m-d', (mktime(0, 0, 0, 1, $val['ExaDT'] - 1, 1900)));
							if($beginDate=='1970-01-01'){
								$quitDate = date('Y-m-d',strtotime ($val['ExaDT']));
								$inArr['ExaDT'] = $quitDate;
							}else{
								$inArr['ExaDT'] = $beginDate;
							}
						}
					}

					//增员类型
					if(!empty($val['addType'])) {
						if(!isset($datadictArr[$val['addType']])){
							$rs = $datadictDao->getCodeByName('HRZYLX' ,$val['addType']);
							if(!empty($rs)){
								$trainsType = $datadictArr[$val['addType']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的增员类型</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}else{
							$trainsType = $datadictArr[$val['addType']]['code'];
						}
						$inArr['addTypeCode'] = $trainsType;
						$inArr['addType'] = $val['addType'];
					}

					//需求人数
					if(!empty($val['needNum'])) {
						if (preg_match("/^[1-9]\d*$/",$val['needNum'])) {
							$inArr['needNum'] = $val['needNum'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!需求人数必须为正整数</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<span class="red">导入失败!需求人数为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//已入职的人数
					if(!empty($val['entryNum'])) {
						if (preg_match("/^[1-9]\d*$/",$val['entryNum'])) {
							$inArr['entryNum'] = $val['entryNum'];
						}else{
							$inArr['entryNum'] = 0;
						}
					}else {
						$inArr['entryNum'] = 0;
					}

					//待入职的人数
					if(!empty($val['beEntryNum'])) {
						if (preg_match("/^[1-9]\d*$/",$val['beEntryNum'])) {
							$inArr['beEntryNum'] = $val['beEntryNum'];
						}else{
							$inArr['beEntryNum'] = 0;
						}
					}else {
						$inArr['beEntryNum'] = 0;
					}

					//责任人
					if(!empty($val['recruitManName'])) {
						if(!isset($userArr[$val['recruitManName']])){
							$rs = $otherDataDao->getUserInfo($val['recruitManName']);
							if(!empty($rs)){
								$userArr[$val['recruitManName']] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的姓名</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['recruitManName'] = $val['recruitManName'];
						$inArr['recruitManId'] = $userArr[$val['recruitManName']]['USER_ID'];
					}

					//协助人
					if(!empty($val['assistManName'])) {
						if(!isset($userArr[$val['assistManName']])){
							$rs = $otherDataDao->getUserInfo($val['assistManName']);
							if(!empty($rs)){
								$userArr[$val['assistManName']] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的姓名</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['assistManName'] = $val['assistManName'];
						$inArr['assistManId'] = $userArr[$val['assistManName']]['USER_ID'];
					}

					//需求原因
					if(!empty($val['applyReason'])) {
						$inArr['applyReason'] = $val['applyReason'];
					}

					//工作职责
					if(!empty($val['workDuty'])) {
						$inArr['workDuty'] = $val['workDuty'];
					}

					//任职要求
					if(!empty($val['jobRequire'])) {
						$inArr['jobRequire'] = $val['jobRequire'];
					}

					//关键要点
					if(!empty($val['keyPoint'])) {
						$inArr['keyPoint'] = $val['keyPoint'];
					}

					//注意事项
					if(!empty($val['attentionMatter'])) {
						$inArr['attentionMatter'] = $val['attentionMatter'];
					}

					//部门领导喜好
					if(!empty($val['leaderLove'])) {
						$inArr['leaderLove'] = $val['leaderLove'];
					}

					//进度备注
					if(!empty($val['applyRemark'])) {
						$inArr['applyRemark'] = $val['applyRemark'];
					}

					$suffix = sprintf("%03d" ,rand(0 ,999)); //3位随机数后缀
					$inArr['formCode'] = 'ZY'.date( "YmdHis" ).$suffix;//单据编号

					$newId = parent::add_d($inArr,true);

					if($newId){
						$tempArr['result'] = '导入成功';
					}else{
						$tempArr['result'] = '<span class="red">导入失败</span>';
					}
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					array_push($resultArr ,$tempArr);
				}
				return $resultArr;
			}
		}
	}

	/*
	 * 导出excel
	 */
	function excelOut($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/人资-增员申请导入.xls" ); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '增员申请信息列表' ) );
		//设置表头及样式 设置
		$i = 2;
		if (! count ( array_filter ( $rowdatas ) ) == 0) {
			$row = $i;
			for($n = 0; $n < count ( $rowdatas ); $n ++) {
				$m = 0;
				foreach ( $rowdatas [$n] as $field => $value ) {
					$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $row + $n, iconv ( "GBK", "utf-8", $value ) );
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
		header ( 'Content-Disposition:inline;filename="' . "增员申请导出报表.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/*****************************导出导入*****************************/

	/************add chenrf 20130508***************/
	/**
	 * 改变状态
	 * @param $id
	 */
	function changeState($id ,$state = 8) {
		$object['id'] = $id;
		$object['state'] = $state;
		return $this->updateById($object);
	}

	/**
	 * 选择审批流
	 * @param $object
	 */
	function ewfSelect($object){
		$id = $object['id'];
		$deptId = $object['deptId'];
		$addTypeCode = $object['addTypeCode'];
		$rowArr = $this->findSql('select DEPT_ID,PARENT_ID from department');
		$parentId = $this->getParentID($rowArr ,$deptId);
		$selPage = '';
		if(in_array($deptId ,$this->serviceLine)) { //服务线
			if ($object['employmentTypeCode'] == 'PYLXSX') { //用工类型为实习生
				$selPage = "ewf_serviceLineAddIntern_index.php";
			} else {
				switch ($addTypeCode) {
					case 'ZYLXJHN':
						$selPage = 'ewf_serviceLineAddPlan_index.php'; //计划内增员
						break;
					case 'ZYLXJHW':
						$selPage = 'ewf_serviceLineAdd_index.php'; //计划外增员
						break;
					case 'ZYLXLZ':
						$selPage = 'ewf_serviceLine_index.php'; //离职换岗
						break;
				}
			}
		} else { //非服务线
			if ($object['employmentTypeCode'] == 'PYLXSX') { //用工类型为实习生
				$selPage = "ewf_enServiceLineAddIntern_index.php";
			} else {
				switch ($addTypeCode) {
					case 'ZYLXJHN':
						$selPage = 'ewf_enServiceLineAddPlan_index.php';  //计划内增员
						break;
					case 'ZYLXJHW':
						$selPage = 'ewf_enServiceLineAdd_index.php'; //计划外增员
						break;
					case 'ZYLXLZ':
						$selPage = 'ewf_enServiceLine_index.php'; //离职换岗
						break;
				}
			}
		}
		if($selPage == '') {
			return '';
		}
		return 'controller/hr/recruitment/'.$selPage.'?actTo=ewfSelect&billId='.$id.'&billDept='.$deptId;
	}

	/**
	 * 选择撤销审批流
	 * @param $object
	 */
	function ewfDelWork($object){
		$id = $object['id'];
		$deptId = $object['deptId'];
		$addTypeCode = $object['addTypeCode'];
		$rowArr = $this->findSql('select DEPT_ID,PARENT_ID from department');
		$parentId = $this->getParentID($rowArr ,$deptId);
		$selPage = '';
		if(in_array($deptId ,$this->serviceLine)) { //服务线
			if ($object['employmentTypeCode'] == 'PYLXSX') { //用工类型为实习生
				$selPage = "ewf_serviceLineAddIntern_index.php";
			} else {
				switch ($addTypeCode) {
					case 'ZYLXJHN':
						$selPage = 'ewf_serviceLineAddPlan_index.php'; //计划内增员
						break;
					case 'ZYLXJHW':
						$selPage = 'ewf_serviceLineAdd_index.php'; //计划外增员
						break;
					case 'ZYLXLZ':
						$selPage = 'ewf_serviceLine_index.php'; //离职换岗
						break;
				}
			}
		} else { //非服务线
			if ($object['employmentTypeCode'] == 'PYLXSX') { //用工类型为实习生
				$selPage = "ewf_enServiceLineAddIntern_index.php";
			} else {
				switch ($addTypeCode) {
					case 'ZYLXJHN':
						$selPage = 'ewf_enServiceLineAddPlan_index.php'; //计划内增员
						break;
					case 'ZYLXJHW':
						$selPage = 'ewf_enServiceLineAdd_index.php'; //计划外增员
						break;
					case 'ZYLXLZ':
						$selPage = 'ewf_enServiceLine_index.php'; //离职换岗
						break;
				}
			}
		}
		if($selPage == '') {
			return '';
		}
		return 'controller/hr/recruitment/'.$selPage.'?actTo=delWork&billId=';
	}

	/**
	 * 根据ID获取部门顶级父类ID(数组)
	 * @param $arr  数组 必须包含DEPT_ID和PARENT_ID字段
	 * @param $id   要查找的ID
	 */
	function getParentID($arr ,$id) {
		$parentArr = array();
		foreach ($arr as $row){
			if($id == $row['DEPT_ID']) {
				if($row['PARENT_ID'] == 0) {
					array_push($parentArr ,$id);
				}
				$parentArr = array_merge($parentArr ,$this->getParentID($arr,$row['PARENT_ID']));
			}
		}
		return $parentArr;
	}

	/**
	 * 邮件发送
	 * update chenrf 增加自定义发送信息参数
	 */
	function thisMail_d($object ,$state=8 ,$msg=null) {
		$this->searchArr['id'] = $object['id'];
		$this->groupBy = 'c.id';
		$row = $this->list_d('select_list');
		include (WEB_TOR . "model/common/mailConfig.php");

		$emailArr = isset ($mailUser['oa_hr_recruitment_apply']) ? $mailUser['oa_hr_recruitment_apply'] : array (
			'TO_ID'=>'',
			'TO_NAME'=>''
			);

		$nameStr = $emailArr['TO_NAME'];
		if(in_array($row[0]['deptSId'] ,$this->serviceLine) || $row[0]['postType'] == 'YPZW-WY') {
			$persons = $this->getAuditPersons_d($object);
			if($persons){
				foreach($persons as $key=>$val){
					$receivers .= $val['User'].',';
				}
			}
			$receivers .= $mailUser['oa_hr_recruitment_apply_duo']['TO_ID'].','.$emailArr['TO_ID'].','.$row[0]['createId'];
		} else {
			$receivers = $emailArr['TO_ID'].','.$row[0]['createId'];
		}

		if(empty($msg)) {
			$stateC = $this->statusDao->statusKtoC($state);
		} else {
			$stateC = iconv("UTF-8","GB2312//IGNORE",$msg);
		}
		$addMsg = $_SESSION['USERNAME'] .$stateC.'了增员申请，请查看。<br>
				单据编号 :【<font color="red">'.$object['formCode'].'</font>】<br>
				需求部门 :【<font color="red">'.$object['deptName'].'</font>】<br>
				直属部门 :【<font color="red">'.$row[0]['deptNameO'].'</font>】<br>
				二级部门 :【<font color="red">'.$row[0]['deptNameS'].'</font>】<br>
				三级部门 :【<font color="red">'.$row[0]['deptNameT'].'</font>】<br>
				工作地点 :【<font color="red">'.$row[0]['workPlace'].'</font>】<br>
				网络 :【<font color="red">'.$row[0]['network'].'</font>】<br>
				设备 :【<font color="red">'.$row[0]['device'].'</font>】<br>
				级别 :【<font color="red">'.$row[0]['positionLevel'].'</font>】<br>
				需求人数 :【<font color="red">'.$object['needNum'].'</font> 】<br>';
		$emailDao = new model_common_mail();
		$emailDao->mailClear('增员申请提交', $receivers, $addMsg);
	}

	/**
	 * 比较两数组，填充要修改的数据
	 * @param $oldObj
	 * @param $newObj
	 */
	function fillEdit($oldObj ,$newObj) {
		$data = array(
			// 'needNum',
			'positionLevel' // 级别
			,'deptName' //部门
			,'deptId'
			,'postType' //职位类型
			,'postTypeName' //
			,'positionName' //需求职位
			,'positionId'
			,'developPositionName' //研发职位
			,'workPlace' //工作地点
			,'wageRange' //工资范围
			,'addTypeCode' //增员类型
			,'addType'
			,'leaveManName' //换岗人
			,'employmentType' //用工类型
			,'employmentTypeCode'
			,'required'
			,'projectType' //项目类型
			,'projectGroup' //所在项目组
			,'projectGroupId'
			,'applyReason' //需求原因
			,'useAreaId' //归属区域
			,'useAreaName'
		);
		$subData = array();
		if($oldObj != $newObj) {
			foreach($oldObj as $key => $val) {
				if($val != $newObj[$key] && in_array($key ,$data)) {
					if($val == '') {
						$val = "空";
					}
					if($newObj[$key] == '') {
						$newObj[$key]=' ';
					}
					$subData[] = $key;
					$newObj[$key.'Edit'] = $newObj[$key];
					$newObj[$key] = $val;
				}
			}
			$re = array_intersect($data ,$subData);
			if(!empty($re)) { //如果指定字段被修改，返回新数组
				return $newObj;
			}
		}
		return '';
	}

	/**
	 * 审批后的增员申请修改邮件通知
	 */
	function auditEditMail_d( $oldObj ,$newObj) {
		$data = array(
			'needNum' => '需求人数'
			,'positionLevel'=>'级别'
			,'deptName'=>'需求部门'
			,'postTypeName'=>'职位类型'
			,'positionName'=>'需求职位'
			,'developPositionName'=>'研发职位'
			,'workPlace'=>'工作地点'
			,'wageRange'=>'工资范围'
			,'addType'=>'增员类型'
			,'leaveManName'=>'离职/换岗人姓名'
			,'employmentType'=>'用工类型'
			,'projectType'=>'项目类型'
			,'projectGroup'=>'所在项目组'
			,'applyReason'=>'需求原因'
			,'useAreaName'=>'归属区域'
		);
		$dictDao = new model_system_datadict_datadict();
		$changeData = array();
		foreach ($data as $key => $val) {
			if (array_key_exists($key.'Edit' ,$newObj)) {
				$changeData[$key]['data'] = $newObj[$key.'Edit'];
				$changeData[$key]['name'] = $val;
			}
		}
		$emailDao = new model_common_mail();

		$mailContent = "您好，【".$_SESSION['USERNAME']."】对单据【<span style='color:blue'>".$oldObj['formCode']."</span>】进行了以下修改：<br>"
					.'<table border="1px" cellspacing="0px" style="text-align:center"><tr style="background-color:#fff999"><td>&nbsp;</td><td width="40%">旧数据</td><td width="40%">新数据</td></tr>';

		foreach ($changeData as $key => $val) {
			$mailContent .= '<tr><td>'.$val['name'].'</td><td>'.$oldObj[$key].'</td><td>'.$val['data'].'</td></tr>';
		}

		$mailContent .= '</table>';

		include_once(WEB_TOR."model/common/mailConfig.php");
		$receiverId = $newObj['resumeToId'].','.$oldObj['recruitManId'].','.$mailUser['oa_hr_recruitment_apply']['TO_ID'].','.$oldObj['createId'];
	 	$emailDao->mailGeneral("增员申请修改通知" ,$receiverId ,$mailContent);
	}

	/**
	 * 审批修改后回调
	 */
	function dealEditApply($id){
		$data = array('needNum','positionLevel','deptName','deptId','postType','postTypeName','positionName','positionId','developPositionName','workPlace',
  	'wageRange','addTypeCode','addType','leaveManName','employmentType','employmentTypeCode','required','projectType','projectGroup','projectGroupId','applyReason','useAreaId','useAreaName');
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $id );
		$objId = $folowInfo ['objId'];
		if (! empty ( $objId )) {
			$newRow=array();
			$rows= $this->get_d ( $objId );
			if($folowInfo['examines']!="no"){   // 审批通过
				//填充变更数据
				foreach ($data as $val){
					if(!empty($rows[$val.'Edit']))
						$newRow[$val]=$rows[$val.'Edit'];
				}
				//如果变更包含需求人数，更新在招聘人数
				if(isset($newRow['needNum'])){
					$needNum=$newRow['needNum'];//需求人数
					$entryNum=$rows['entryNum'];//已入职人数
					$beEntryNum = $rows['beEntryNum'];  //待入职人数
					$newRow['ingtryNum'] = $needNum - $entryNum - $beEntryNum;//在招聘人数
				}
				$this->applyEditAuditMail_d($objId ,'通过'); //发邮件通知填表人
			}else {
				$this->applyEditAuditMail_d($objId ,'不通过'); //发邮件通知填表人
			}
			//清空变更的临时数据
			foreach ($data as $val){
				$newRow[$val.'Edit']='';
			}
			$this->update(array('id'=>$objId),$newRow);
		}
	}

	/**
	 * 增员申请，判断显示页面，是否出现“关键要点”等三项
	 */
	function showHtm($val,$obj){
		if($val){
			$htm =<<<EOT
			<tr>
				<td class="form_text_left_three">关键要点</td>
				<td class="form_text_right_three" colspan="5">
					<textarea class="txt_txtarea_biglong" id="keyPoint" name="apply[keyPoint]" >{$obj['keyPoint']}</textarea>
				</td>
			</tr>
			<tr>
				<td class="form_text_left_three">注意事项</td>
				<td class="form_text_right_three" colspan="5">
					<textarea class="txt_txtarea_biglong" id="attentionMatter" name="apply[attentionMatter]">{$obj['attentionMatter']}</textarea>
				</td>
			</tr>
			<tr>
				<td class="form_text_left_three">部门领导喜好</td>
				<td class="form_text_right_three" colspan="5">
					<textarea class="txt_txtarea_biglong" id="leaderLove" name="apply[leaderLove]">{$obj['leaderLove']}</textarea>
				</td>
			</tr>
EOT;
		} else {
			$htm="";
		}
		return $htm;
	}

	/**
	 * 审批结果发送邮件通知增员申请填表人
	 */
	function applyAuditMail_d( $id , $result) {
		$obj = $this->get_d( $id );
		$receiverId = $obj['createId'];
		$emailDao = new model_common_mail();
		$mailContent = '您好！您提交的单据编号为<span style="color:blue">'.$obj['formCode'].
		'</span>的增员申请审批结果为：<span style="color:blue">'.$result.
		'</span><br>';
		$emailDao->mailGeneral("增员申请审批结果" ,$receiverId ,$mailContent);
	}

	/**
	 * 审批结果发送邮件通知增员申请填表人
	 */
	function applyEditAuditMail_d( $id , $result) {
		$obj = $this->get_d( $id );
		$receiverId = $obj['formManId'];
		$emailDao = new model_common_mail();
		$mailContent = '您好！您提交的单据编号为<span style="color:blue">'.$obj['formCode'].
		'</span>的增员申请修改审批结果为：<span style="color:blue">'.$result.
		'</span><br>';

		$emailDao->mailGeneral("增员申请修改审批结果" ,$receiverId ,$mailContent);
	}

	/**
	 * 增员申请审批页面带出部门统计人数信息
	 */
	function getDeptPer_d($id){
		$this->groupBy='c.id';
		$this->searchArr['ExaStatus'] = "完成";
		$rows = $this->list_d('select_list');
		$this->searchArr['id'] = $id;
		$this->searchArr['ExaStatus'] = "部门审批";
		$row = $this->list_d('select_list');
		$beEntryNumS = 0; //二级部门待入职人数
		$beEntryNumT = 0; //三级部门待入职人数
		$beEntryNumF = 0; //四级部门待入职人数
		//获取二三四级部门待入职人数
		foreach($rows as $key => $val){
			if($row[0]['deptNameS'] && $val['deptNameS'] == $row[0]['deptNameS']) {
				$beEntryNumS = $beEntryNumS + $val['beEntryNum'];
			}
			if($row[0]['deptNameT'] && $val['deptNameT'] == $row[0]['deptNameT']) {
				$beEntryNumT = $beEntryNumT + $val['beEntryNum'];
			}
			if($row[0]['deptNameF'] && $val['deptNameF'] == $row[0]['deptNameF']) {
				$beEntryNumF = $beEntryNumF + $val['beEntryNum'];
			}
		}
		//获取二三四级部门在职人数
		$personnel = new model_hr_personnel_personnel();
		if($row[0]['deptNameS']) {
			$conditionS['deptNameS'] = $row[0]['deptNameS'];
			$conditionS['employeesStateName'] = '在职';
			$employeesStateNumS = $personnel->findCount($conditionS); //二级部门在职人数
		}
		if($row[0]['deptNameT']) {
			$conditionT['deptNameT'] = $row[0]['deptNameT'];
			$conditionT['employeesStateName'] = '在职';
			$employeesStateNumT = $personnel->findCount($conditionT); //三级部门在职人数
		}
		if($row[0]['deptNameF']) {
			$conditionF['deptNameF'] = $row[0]['deptNameF'];
			$conditionF['employeesStateName'] = '在职';
			$employeesStateNumF = $personnel->findCount($conditionF); //四级部门在职人数
		}
		//获取二三四级人数小计
		$numS = $beEntryNumS + $employeesStateNumS;	//二级部门人数小计
		$numT = $beEntryNumT + $employeesStateNumT;	//三级部门人数小计
		$numF = $beEntryNumF + $employeesStateNumF;	//四级部门人数小计
		$html = <<<EOT
			<tr>
				<td colspan="6">
					<fieldset><legend><b>部门人员信息</b></legend>
						<table cellpadding="2" width="100%">
							<tr>
								<td class="form_text_left_three">二级部门</td>
								<td class="form_text_right">
									{$row[0][deptNameS]}
								</td>
								<td class="form_text_left_three">在职人数</td>
								<td class="form_text_right">
									{$employeesStateNumS}
								</td>
								<td class="form_text_left_three">待入职人数</td>
								<td class="form_text_right">
									{$beEntryNumS}
								</td>
								<td class="form_text_left_three">人数小计</td>
								<td class="form_text_right">
									{$numS}
								</td>
							</tr>
							<tr>
								<td class="form_text_left_three">三级部门</td>
								<td class="form_text_right">
									{$row[0][deptNameT]}
								</td>
								<td class="form_text_left_three">在职人数</td>
								<td class="form_text_right">
									{$employeesStateNumT}
								</td>
								<td class="form_text_left_three">待入职人数</td>
								<td class="form_text_right">
									{$beEntryNumT}
								</td>
								<td class="form_text_left_three">人数小计</td>
								<td class="form_text_right">
									{$numT}
								</td>
							</tr>
							<tr>
								<td class="form_text_left_three">四级部门</td>
								<td class="form_text_right">
									{$row[0][deptNameF]}
								</td>
								<td class="form_text_left_three">在职人数</td>
								<td class="form_text_right">
									{$employeesStateNumF}
								</td>
								<td class="form_text_left_three">待入职人数</td>
								<td class="form_text_right">
									{$beEntryNumF}
								</td>
								<td class="form_text_left_three">人数小计</td>
								<td class="form_text_right">
									{$numF}
								</td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
EOT;
		return $html;
	}

	/**
	 * 获取审批人
	 */
	function getAuditPersons_d($row){
		if($row){
			$task = $this->get_table_fields('wf_task',"Pid='".$row['id']."' and code='oa_hr_recruitment_apply'",'task');
			$sql = "select User from flow_step_partent where wf_task_id = '".$task."'";
			$persons = $this->findSql($sql);
		}
		return $persons;
	}

	/**
	 * 增员申请修改，邮件通知
	 */
	function sendEmail_d($diff,$oldRow,$persons){
		$dictDao = new model_system_datadict_datadict();
		$title = "增员申请修改通知";
		//邮件接收人
		if($persons){
			foreach($persons as $key=>$val){
				$receivers .=$val['User'].',';
			}
		}

		include (WEB_TOR . "model/common/mailConfig.php");
		$receivers .= $mailUser['oa_hr_recruitment_apply_duo']['TO_ID'].','.$oldRow['formManId'].','.$oldObj['createId'];
		//字段对应中文名
		$field=array('deptName'=>'需求部门','postTypeName'=>'职位类型','positionName'=>'需求职位','developPositionName'=>'研发职位','positionLevel'=>'级别','isEmergency'=>'是否紧急','needNum'=>'需求人数',
			'hopeDate'=>'希望到岗时间','workPlace'=>'工作地点','wageRange'=>'工资范围','addMode'=>'建议补充方式','addType'=>'增员类型','employmentType'=>'用工类型',
			'useAreaName'=>'归属区域','projectType'=>'项目类型','projectGroup'=>'所在项目组','sex'=>'性别','age'=>'年龄','maritalStatus'=>'婚姻','education'=>'学历',
			'professionalRequire'=>'专业要求','workExperiernce'=>'工作经验要求','network'=>'网络','device'=>'设备','resumeToName'=>'简历发送至','applyReason'=>'需求原因','workDuty'=>'工作职责',
			'jobRequire'=>'任职要求','workArrange'=>'试用期内的工作安排','assessmentIndex'=>'试用期结束后的主要考核指标','changeReason'=>'变更原因','leaveManName'=>'离职/换岗人姓名');
		if(array_key_exists('isEmergency',$diff)){
			if($diff['isEmergency']==1)
				$diff['isEmergency']='是';
			else
				$diff['isEmergency']='否';
		}
		if(array_key_exists('projectType',$diff)){
			if($diff['projectType']=='YFXM')
				$diff['projectType']='研发项目';
			else if($diff['projectType']=='GCXM')
				$diff['projectType']='工程项目';
		}
		if(array_key_exists('maritalStatus',$diff)){
			$diff['maritalStatus'] = $dictDao->getDataNameByCode($diff['maritalStatus']);
		}
		if(array_key_exists('education',$diff)){
			$diff['education'] = $dictDao->getDataNameByCode($diff['education']);
		}
		foreach($diff as $key => $val){
			if($field[$key])
				$diffName[$field[$key]] = $val;
		}
		//拼装邮件内容
		$msg ="您好，【".$_SESSION['USERNAME']."】对单据【".$oldRow['formCode']."】进行了以下修改：<br>";
		foreach($diffName as $key => $val){
			$msg .= $key."=>".$val."<br>";
		}
		$emailDao = new model_common_mail();
		$emailDao ->mailClear($title,$receivers,$msg);
		return  true;
	}

	/**
	 * 增员申请，挂起，关闭，取消动作时邮件通知
	 */
	function emailNotice_d($object,$state){
		$this->searchArr['id']=$object['id'];
		$this->groupBy = 'c.id';
		$row = $this->list_d('select_list');
		include (WEB_TOR . "model/common/mailConfig.php");
		$emailArr = isset ($mailUser['oa_hr_recruitment_apply']) ? $mailUser['oa_hr_recruitment_apply'] : array (
			'TO_ID'=>'',
			'TO_NAME'=>''
			);
		$nameStr = $emailArr['TO_NAME'];
		if(in_array($row[0]['deptSId'] ,$this->serviceLine) || $row[0]['postType'] == 'YPZW-WY') {
			$persons = $this->getAuditPersons_d($object);
			if($persons){
				foreach($persons as $key => $val) {
					$receivers .= $val['User'].',';
				}
			}
			$receivers .= $mailUser['oa_hr_recruitment_apply_duo']['TO_ID'].$emailArr['TO_ID'].','.$object['formManId'].','.$row[0]['createId'];
		} else {
			$receivers = $emailArr['TO_ID'].','.$row[0]['createId'];
		}
		if(empty($msg)) {
			$stateC = $this->statusDao->statusKtoC($state);
		} else {
			$stateC = iconv("UTF-8","GB2312//IGNORE",$msg);
		}
		$addMsg = $_SESSION['USERNAME'] .$stateC.'了增员申请，请查看。<br>
				单据编号 :【<font color="red">'.$object['formCode'].'</font>】<br>
				需求部门 :【<font color="red">'.$object['deptName'].'</font>】<br>
				直属部门 :【<font color="red">'.$row[0]['deptNameO'].'</font>】<br>
				二级部门 :【<font color="red">'.$row[0]['deptNameS'].'</font>】<br>
				三级部门 :【<font color="red">'.$row[0]['deptNameT'].'</font>】<br>
				工作地点 :【<font color="red">'.$row[0]['workPlace'].'</font>】<br>
				网络 :【<font color="red">'.$row[0]['network'].'</font>】<br>
				设备 :【<font color="red">'.$row[0]['device'].'</font>】<br>
				级别 :【<font color="red">'.$row[0]['positionLevel'].'</font>】<br>
				需求人数 :【<font color="red">'.$object['needNum'].'</font> 】<br>';
		$emailDao = new model_common_mail();
		$emailDao->mailClear('增员申请提交' , $receivers ,$addMsg);
	}
}
?>