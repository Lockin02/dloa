<?php
/**
 * @author Show
 * @Date 2012年6月1日 星期五 14:51:13
 * @version 1.0
 * @description:招聘管理-面试评估表 Model层
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

class model_hr_recruitment_interview  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitment_interview";
		$this->sql_map = "hr/recruitment/interviewSql.php";
		$this->db = new mysql();
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
			0 => array (
				'statusEName' => 'notview',
				'statusCName' => '未审核',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'notsend',
				'statusCName' => '未发送录用通知',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'hassend',
				'statusCName' => '已发送录用通知',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'finis',
				'statusCName' => '完成',
				'key' => '3'
			)
		);
		parent::__construct ();
	}

	//需要数据字典处理的字段
	public $datadictFieldArr = array(
		'useHireType','hrHireType','wageLevelCode','postType'
		);

	//返回是否
	function rtYN_d($val){
		if ($val == 1){
			return '是';
		}else{
			return '否';
		}
	}

	//重写add_d
	function add_d($object){
		$datadictDao = new model_system_datadict_datadict();
		$object['formCode']='PG'.date ( "YmdHis" );//单据编号
		$object['formDate']=date( "Y-m-d" );//单据编号
		$object['deptState']=0;
		$object['hrState']=0;
		$object['ExaStatus']='未提交';
		$object = $this->processDatadict($object);
		if (isset($object['useHireType'])) {
			$object ['useHireTypeName'] = $datadictDao->getDataNameByCode ( $object['useHireType'] );
		}

		if ($object['invitationId']>0){
			$invitationDao=new model_hr_recruitment_invitation();
			$invitationDao->update(array("id"=>$object['invitationId']),array("isAddInterview"=>1));
		}
		return parent::add_d($object,true);
	}

	/*
	 * 重新add_duanlh2013-04-01
	 */
	function addInterview_d ($object) {
		try{
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['formCode']  = 'PG'.date ( "YmdHis" );//单据编号
			$object['formDate']  = date( "Y-m-d" );//单据日期
			$object['deptState'] = 0;
			$object['hrState']   = 0;
			$object['ExaStatus'] = '未提交';
			$object = $this->processDatadict($object);
			if (isset($object['useHireType'])) {
				$object ['useHireTypeName'] = $datadictDao->getDataNameByCode ( $object['useHireType'] );
			}
			$branchDao = new model_deptuser_branch_branch();
			$branchCN = $branchDao->get_d($object['sysCompanyId']);
			$object['sysCompanyName'] = $branchCN['NameCN'];
			$object['deptState'] = 1;
			if ($object['useAreaId'] != '') {
				$query = $this->db->query("select Name from area where ID = ".$object['useAreaId']);
				$get = $this->db->fetch_array($query);
				$object['useAreaName'] = $get['Name'];
			}
			$object['hrState'] = 1;
			$object['state'] = $this->statusDao->statusEtoK("noview");
			$datadict = new model_system_datadict_datadict();
			$object['hrHireTypeName']    = $datadict->getDataNameByCode($object['hrHireType']);
			$object['hrSourceType1Name'] = $datadict->getDataNameByCode($object['hrSourceType1']);
			$object['useHireTypeName']   = $datadict->getDataNameByCode($object['useHireType']);
			$object['addType']           = $datadict->getDataNameByCode($object['addTypeCode']);
			$object['wageLevelName']     = $datadict->getDataNameByCode($object['wageLevelCode']);
			$object['controlPost']       = $datadict->getDataNameByCode($object['controlPostCode']);

			$id = parent :: add_d($object,true);
			if (is_array($object['items'])) {
				$interviewcomDao = new model_hr_recruitment_interviewComment();
				foreach($object['items'] as $key => $value){
					$value["interviewId"]     = $id;
					$value["interviewerType"] = '1';
					$value["resumeId"]        = $object['resumeId'];
					$value["resumeCode"]      = $object['resumeCode'];
					$interviewcomDao->add_d($value ,true);
				}
			}
			if (is_array($object['humanResources'])) {
				$interviewcomDao = new model_hr_recruitment_interviewComment();
				foreach($object['humanResources'] as $key => $value){
					$value["interviewId"]     = $id;
					$value["interviewerType"] = '2';
					$value["resumeId"]        = $object['resumeId'];
					$value["resumeCode"]      = $object['resumeCode'];
					$interviewcomDao->add_d($value ,true);
				}
			}

			$this->commit_d();
			return $id;
		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	function change_d(){
		try{
			$this->start_d();
			$obj = $this->get_d($_POST['id']);
			$applyresume = new model_hr_recruitment_applyResume();
			$apply = new model_hr_recruitment_apply();
			$recomresume = new model_hr_recruitment_recomResume();
			$recommend = new model_hr_recruitment_recommend();
			$resume = new model_hr_recruitment_resume();
			$invitationDao=new model_hr_recruitment_invitation();
			if ($_POST['type']==1){
				$sourceresume = $applyresume -> update(array("id"=>$obj['applyResumeId']),array("state"=>3));
			}else{
				$sourceresume = $recomresume -> update(array("id"=>$obj['applyResumeId']),array("state"=>3));
				$source = $recommend -> update(array("id"=>$obj['parentId']),array("state"=>3));
			}
			$getresume = $resume -> update(array("id"=>$obj['resumeId']),array("resumeType"=>3));
			//更新面试通知状态
			if ($obj['invitationId']>0){
				$invitationDao -> update(array("id"=>$obj['invitationId']),array("state"=>3));
			}
			$obj['useInterviewResult'] = 0;
			$this-> updateById ( $obj );
			$this->commit_d();
			return 1;
		}catch(exception $e){
			$this->rollBack();
			return 0;
		}
	}

	//获取面试评价
	function getInterComment($type,$id){
		$comment = new model_hr_recruitment_interviewComment();
		$hrcomment = $comment->findAll(array("interviewerType"=>$type,"invitationId"=>$id));
		if (is_array($hrcomment)) {
			$str1 = "";
			foreach($hrcomment as $commentone){
				$str1 .= $commentone['interviewer']."(".$commentone['interviewDate']."):";
				$str1 .= $commentone['interviewEva']."<br>";
			}
		}
		return $str1;
	}

	//获取面试评价(面试评估)
	function getInterviewComment($intertype,$id){
		$comment = new model_hr_recruitment_interviewComment();
		$hrcomment = $comment->findAll(array("interviewerType"=>$intertype,"interviewId"=>$id));
		if (is_array($hrcomment)) {
			$str1 = "";
			foreach($hrcomment as $commentone){
				$str1 .= $commentone['interviewer']."(".$commentone['interviewDate']."):";
				$str1 .= $commentone['interviewEva']."<br>";
			}
		}
		return $str1;
	}

	//获取笔试评价
	function getUseComment($type,$id){
		$comment = new model_hr_recruitment_interviewComment();
		$hrcomment = $comment->findAll(array("interviewerType"=>$type,"invitationId"=>$id));
		if (is_array($hrcomment)) {
			$str1 = "";
			foreach($hrcomment as $commentone){
				if ($commentone['useWriteEva']=="")continue;
				$str1 .= $commentone['interviewer']."(".$commentone['interviewDate']."):";
				$str1 .= $commentone['useWriteEva']."<br>";
			}
		}
		return $str1;
	}

	//获取笔试评价(面试评估)
	function getUseInterviewComment($type,$id){
		$comment = new model_hr_recruitment_interviewComment();
		$hrcomment = $comment->findAll(array("interviewerType"=>$type,"interviewId"=>$id));
		if (is_array($hrcomment)) {
			$str1 = "";
			foreach($hrcomment as $commentone){
				if ($commentone['useWriteEva']=="")continue;
				$str1 .= $commentone['interviewer']."(".$commentone['interviewDate']."):";
				$str1 .= $commentone['useWriteEva']."<br>";
			}
		}
		return $str1;
	}

	//重写edit_d
	function edit_d($object){

		//处理数据字典字段
		$datadictDao = new model_system_datadict_datadict ();
		$object ['hrSourceType1Name'] = $datadictDao->getDataNameByCode ( $object['hrSourceType1'] );
		if (!empty($object['useHireType'])) {
			$object ['useHireTypeName'] = $datadictDao->getDataNameByCode ( $object['useHireType'] );
		}

		$object = $this->processDatadict($object);

		return parent::edit_d($object,true);
	}

	//重写edit_d
	function managerEdit_d($object) {
		try{
			$this->start_d();

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object['hrSourceType1Name'] = $datadictDao->getDataNameByCode($object['hrSourceType1']);
			$object['useHireTypeName']   = $datadictDao->getDataNameByCode($object['useHireType']);
			$object['addType']           = $datadictDao->getDataNameByCode($object['addTypeCode']);
			$object['wageLevelName']     = $datadictDao->getDataNameByCode($object['wageLevelCode']);
			$object['controlPost']       = $datadictDao->getDataNameByCode($object['controlPostCode']);

			$object = $this->processDatadict($object);
			
			$id = parent::edit_d($object,true);

			//处理从表数据
			if (is_array($object['items'])) {
				$interviewcomDao = new model_hr_recruitment_interviewComment();
				$mainArr = array("interviewId"=>$object['id'],"interviewerType"=>'1');
				$itemsArr = util_arrayUtil::setArrayFn($mainArr,$object['items']);

				//判断用人部门意见从表，原有的就编辑，新加的往数据库中添加
				foreach($object['items'] as $key => $value){
					if ($value['id']) {
						$value["interviewId"]     = $object['id'];
						$value["interviewerType"] = '1';
						if (!isset($value['isDelTag'])) {
							$interviewcomDao->edit_d($value ,true);
						}
					} else {
						$value["interviewId"]     = $object['id'];
						$value["interviewerType"] = '1';
						if (!isset($value['isDelTag'])) {
							$interviewcomDao->add_d($value ,true);
						}
					}
				}
			}

			if (is_array($object['humanResources'])) {
				$interviewcomDao = new model_hr_recruitment_interviewComment();
				$mainArr  = array("interviewId"=>$object['id'],"interviewerType"=>'2');
				$itemsArr = util_arrayUtil::setArrayFn($mainArr,$object['humanResources']);
				//判断人力资源部门意见从表，原有的就编辑，新加的往数据库中添加
				foreach($object['humanResources'] as $key => $value){
					if ($value['id']){
						$value["interviewId"] = $object['id'];
						$value["interviewerType"] = '2';
						if (!isset($value['isDelTag'])) {
							$interviewcomDao->edit_d($value ,true);
						}
					} else {
						$value["interviewId"] = $object['id'];
						$value["interviewerType"] = '2';
						if (!isset($value['isDelTag'])) {
							$interviewcomDao->add_d($value ,true);
						}
					}
				}
			}
			
			$this->commit_d();
			return $id;
		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 面试评估审批页面带出部门统计人数信息
	 */
	function getDeptPer_d($id){
		$this->searchArr['id'] = $id;
		$interview = $this->list_d('select_list');
		$apply = new model_hr_recruitment_apply();
		$apply->groupBy = 'c.id';
		$apply->searchArr['ExaStatus'] = "完成";
		$applyRows = $apply->list_d('select_list');
		$personnel = new model_hr_personnel_personnel();
		$beEntryNumS = 0; //二级部门待入职人数
		$beEntryNumT = 0; //三级部门待入职人数
		$beEntryNumF = 0; //四级部门待入职人数
		//获取二三四级部门待入职人数
		foreach($applyRows as $key => $val) {
			if ($interview[0]['deptNameS'] && $val['deptNameS'] == $interview[0]['deptNameS']) {
				$beEntryNumS = $beEntryNumS + $val['beEntryNum'];
			}
			if ($interview[0]['deptNameT'] && $val['deptNameT'] == $interview[0]['deptNameT']) {
				$beEntryNumT = $beEntryNumT + $val['beEntryNum'];
			}
			if ($interview[0]['deptNameF'] && $val['deptNameF'] == $interview[0]['deptNameF']) {
				$beEntryNumF = $beEntryNumF + $val['beEntryNum'];
			}
		}
		//获取二三四级部门在职人数
		$employeesStateNumS = 0; //二级部门在职人数
		$employeesStateNumT = 0; //三级部门在职人数
		$employeesStateNumF = 0; //四级部门在职人数
		if ($interview[0]['deptNameS']) {
			$conditionS['deptNameS'] = $interview[0]['deptNameS'];
			$conditionS['employeesStateName'] = '在职';
			$employeesStateNumS = $personnel->findCount($conditionS); //二级部门在职人数
		}
		if ($interview[0]['deptNameT']) {
			$conditionT['deptNameT'] = $interview[0]['deptNameT'];
			$conditionT['employeesStateName'] = '在职';
			$employeesStateNumT = $personnel->findCount($conditionT); //三级部门在职人数
		}
		if ($interview[0]['deptNameF']) {
			$conditionF['deptNameF'] = $interview[0]['deptNameF'];
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
									{$interview[0]['deptNameS']}
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
									{$interview[0]['deptNameT']}
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
									{$interview[0]['deptNameF']}
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
	 * 发送offer后反馈信息给行政部
	 * @param entryNotice  入职信息
	 */
	function mailToXZ_d($id ,$entryNotice) {
		$obj = $this->get_d($id);
		if (!$obj) {
			return false; //2015-01-09印丹说正式系统上说会发空白邮件，可惜我不知道怎么回事，就先这样吧
		}

		try{
			$this->start_d();

			$deptDao = new model_deptuser_dept_dept();
			$deptObj = $deptDao->getSuperiorDeptById_d($obj['deptId']);
			$content = <<<EOT
					<br>
					姓名：<font color=blue>$obj[userName]</font><br>
					电话：$obj[phone]<br>
					邮箱：$obj[email]<br>
					二级部门：$deptObj[deptNameS]<br>
					三级部门：$deptObj[deptNameT]<br>
					岗位：$obj[hrJobName]<br>
					技术级别：$obj[personLevel]<br>
					入职时间：$entryNotice[entryDate]<br>
					入职协助人：$entryNotice[assistManName]<br><br>
EOT;
			//非服务线实习生的发送邮件通知行政部“电话费补助”
			if ($obj['deptId'] != 155) {
				$txbzContent = $content."电话费补助：<font color='blue'>$obj[phoneSubsidy]</font>（试用期）;  <font color='blue'>$obj[phoneSubsidyFormal]</font>（转正）";
				$this->mailDeal_d('interviewAddOffer_txbz' ,null ,array('id' => $obj['id'] ,'content' => $txbzContent));
			}

			//需求电脑设备信息需要反馈到行政部
			$xqdnContent = $content."办公电脑需求设备类型：<font color='blue'>$obj[useDemandEqu]</font>";
			$this->mailDeal_d('interviewAddOffer_xqdn' ,null ,array('id' => $obj['id'] ,'content' => $xqdnContent));

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/******************* S 导入导出系列 ************************/
	function addExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename  = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType  = $_FILES["inputExcel"]["type"];

		$resultArr    = array(); //结果数组
		$excelData    = array(); //excel数据数组
		$tempArr      = array();
		$inArr        = array(); //插入数组
		$userConutArr = array(); //用户数组
		$userArr      = array(); //用户数组
		$deptArr      = array(); //部门数组
		$datadictArr  = array(); //数据字典数组
		$jobsArr      = array(); //职位数组
		$applyArr	  = array(); //增员申请数组

		$otherDataDao    = new model_common_otherdatas();//其他信息查询
		$datadictDao     = new model_system_datadict_datadict();
		$provinceDao     = new model_system_procity_province(); //城市
		$cityDao         = new model_system_procity_city(); //省份
		$branchDao       = new model_deptuser_branch_branch(); //归属公司
		$area            = new includes_class_global(); //归属区域或支撑中心
		$applyDao        = new model_hr_recruitment_apply(); // 增员申请
		$resumeDao       = new model_hr_recruitment_resume(); // 简历
		$employmentDao   = new model_hr_recruitment_employment(); // 职位申请
		$recommendDao    = new model_hr_recruitment_recommend(); // 内部推荐
		$interviewcomDao = new model_hr_recruitment_interviewComment(); // 面试评价

		//数据字段表头配置
		$objNameArr = array (
			//基本信息
			'userName', //姓名
			'sexy', //性别
			'phone', //联系电话
			'email', //电子邮箱
			'positionsName', //应聘职位
			'deptName', //用人部门
			'postTypeName', //职位类型
			'developPositionName',//研发职位
			'positionLevel', //级别
			'workProvince', //工作地点省
			'workCity', //工作地点市
			'resumeCode', //简历编号
			'applyCode', //关联职位申请
			'parentCode', //关联增员申请
			'recommendCode', //关联内部推荐
			'ExaStatus', //审批状态

			//用人部门意见
			'interviewer', //用人部门面试官
			'interviewDate', //用人部门面试日期
			'useWriteEva', //用人部门笔试评价
			'interviewEva', //用人部门面试评价
			'useInterviewResult', //面试结果
			'useHireTypeName', //录用形式
			'projectType', //项目类型
			'projectGroup', //所在项目组
			'sysCompanyName', //归属公司
			'useAreaName', //归属区域或支撑中心
			'personLevel', //技术等级
			'isLocal', //是否本地化
			'useTrialWage', //试用期基本工资
			'useFormalWage', //转正基本工资
			'internshipSalaryType', //实习工资类型
			'internshipSalary', //实习工资
			'eatCarSubsidy', //餐车补（实习生）
			'computerIntern', //电脑补贴（实习生）
			'mealCarSubsidy', //餐车补（试用）
			'mealCarSubsidyFormal', //餐车补（转正）
			'phoneSubsidy', //电话费补助（试用）
			'phoneSubsidyFormal', //电话费补助（转正）
			'tripSubsidy', //出差补助上限值（试用）
			'tripSubsidyFormal', //出差补助上限值（转正）
			'computerSubsidy', //电脑补助（试用）
			'computerSubsidyFormal', //电脑补助（转正）
			'bonusLimit', //奖金上限值（试用）
			'bonusLimitFormal', //奖金上限值（转正）
			'manageSubsidy', //管理津贴（试用）
			'manageSubsidyFormal', //管理津贴（转正）
			'accommodSubsidy', //临时住宿补助（试用）
			'accommodSubsidyFormal', //临时住宿补助（转正）
			'otherSubsidy', //其他补贴（试用）
			'otherSubsidyFormal', //其他补贴（转正）
			'workBonus', //工作奖金（试用）
			'workBonusFormal', //工作奖金（转正）
			'levelSubsidy', //技术级别补助（试用）
			'levelSubsidyFormal', //技术级别补助（转正）
			'areaSubsidy', //区域补助（试用）
			'areaSubsidyFormal', //区域补助（转正）
			'controlPost', //管理岗位
			'isCompanyStandard', //配置是否按公司标准
			'useSign', //签订《竞业限制协议》
			'useDemandEqu', //办公电脑需求设备类型
			'useManager', //确认人
			'useSignDate', //确认日期
			'tutor', //指定导师

			//人力资源部门意见
			'interviewer2', //HR面试官
			'interviewDate2', //HR面试日期
			'interviewEva2', //HR面试评价
			'hrInterviewResult', //总体评价
			'hrSourceType1Name', //简历来源大类
			'hrSourceType2Name', //简历来源小类
			'hrJobName', //录用职位
			'probation', //试用期（月）
			'contractYear', //合同年限
			'socialPlace', //社保购买地
			'hrIsMatch', //基本工资与薪点及薪资是否对应
			'wageLevelName', //工资级别
			'entryDate', //预计入职时间
			'addType', //增员类型
			'eprovince', //无线补助省份
			'ecity', //无线补助城市
			'manager', //HR确认人
			'SignDate' //确认日期
		);

		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear($filename ,$temp_name);
			spl_autoload_register("__autoload");

			if (is_array($excelData)) {
				$objectArr = array();
				foreach ($excelData as $key => $val) {
					if (empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4]) && empty($val[5])) {
						continue;
					} else {
						foreach ($objNameArr as $k => $v) {
							$objectArr[$key][$v] = trim($val[$k]); //格式化数据
						}
					}
				}

				//行数组循环
				foreach($objectArr as $key => $val) {
					if ($key === 0) {
						continue ;
					}
					$inArr = array();
					$actNum = $key + 2;

					//姓名
					if (!empty($val['userName'])) {
						$inArr['userName'] = $val['userName'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!姓名为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//性别
					if (!empty($val['sexy'])) {
						$inArr['sexy'] = $val['sexy'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!性别为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//联系电话
					if (!empty($val['phone'])) {
						$inArr['phone'] = $val['phone'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!联系电话为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//电子邮箱
					if (!empty($val['email'])) {
						$inArr['email'] = $val['email'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!电子邮箱为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//用人部门
					if (!empty($val['deptName'])) {
						$rs = $otherDataDao->getDeptId_d($val['deptName']);
						if (!empty($rs)) {
							$inArr['deptName'] = $val['deptName'];
							$inArr['deptId'] = $rs;
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的用人部门</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!用人部门为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//应聘职位
					if (!empty($val['positionsName'])) {
						$inArr['positionsName'] = $val['positionsName'];
						$jobsDao = new model_deptuser_jobs_jobs();
						$jobsObj = $jobsDao->find(array('dept_id' => $inArr['deptId'] ,'name' => $inArr['positionsName']));
						if (!empty($jobsObj)) {
							$inArr['positionsId'] = $jobsObj['id'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!用人部门不存在该应聘职位</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!应聘职位为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//职位类型
					if (!empty($val['postTypeName'])) {
						$rs = $datadictDao->getCodeByName('YPZW' ,$val['postTypeName']);
						if (!empty($rs)) {
							$inArr['postTypeName'] = $val['postTypeName'];
							$inArr['postType'] = $rs;
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的职位类型</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!职位类型为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//研发职位
					if (!empty($val['developPositionName'])) {
						$inArr['developPositionName'] = $val['developPositionName'];
					}

					//级别
					if (!empty($val['positionLevel'])) {
						if ($inArr['postType'] == 'YPZW-WY') {
							$levelDao = new model_hr_basicinfo_level();
							$levelObj = $levelDao->find(array('personLevel' => $val['positionLevel']));
							if (!empty($levelObj)) {
								$inArr['positionLevel'] = $levelObj['id'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的级别</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							switch ($val['positionLevel']) {
								case '初级' :
									$inArr['positionLevel'] = 1;
									break;
								case '中级' :
									$inArr['positionLevel'] = 2;
									break;
								case '高级' :
									$inArr['positionLevel'] = 3;
									break;
								default :
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的级别</span>';
									array_push($resultArr ,$tempArr);
									continue;
							}
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!级别为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//工作地点省份
					if(!empty($val['workProvince'])) {
						$provinceObj = $provinceDao->find(array('provinceName' => $val['workProvince']));
						if (!empty($provinceObj)) {
							$inArr['workProvince'] = $val['workProvince'];
							$inArr['workProvinceId'] = $provinceObj['id'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!不存在的工作地点省份</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!工作地点省份为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//工作地点城市
					if(!empty($val['workCity'])) {
						$cityObj = $cityDao->find(array('cityName' => $val['workCity']));
						if (!empty($cityObj)) {
							$inArr['workCity'] = $val['workCity'];
							$inArr['workCityId'] = $cityObj['id'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!不存在的工作地点城市</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!工作地点城市为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//简历编号
					if(!empty($val['resumeCode'])) {
						$resumeObj = $resumeDao->find(array('resumeCode' => $val['resumeCode']));
						if (!empty($resumeObj)) {
							$inArr['resumeCode'] = $val['resumeCode'];
							$inArr['resumeId']   = $resumeObj['id'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!不存在的简历编号</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					//职位申请编号
					if(!empty($val['applyCode'])) {
						$employmentObj = $employmentDao->find(array('employmentCode' => $val['applyCode']));
						if (!empty($employmentObj)) {
							$inArr['applyCode'] = $val['applyCode'];
							$inArr['applyId']   = $employmentObj['id'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!不存在的职位申请编号</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					//增员申请编号
					if(!empty($val['parentCode'])) {
						if ($applyArr[$val['parentCode']]) {
							$inArr['parentCode'] = $applyArr[$val['parentCode']]['parentCode'];
							$inArr['parentId']   = $applyArr[$val['parentCode']]['id'];
						} else {
							$applyObj = $applyDao->find(array('formCode' => $val['parentCode']));
							if (!empty($applyObj)) {
								if ($applyObj['needNum'] - (int)$applyObj['entryNum'] - (int)$applyObj['beEntryNum'] - (int)$applyObj['stopCancelNum'] > 0) {
									$applyArr[$val['parentCode']] = array(
										'parentCode' => $applyObj['formCode'],
										'id' => $applyObj['id']
									);
									$inArr['parentCode'] = $val['parentCode'];
									$inArr['parentId']   = $applyObj['id'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!增员申请没有在招聘人数</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的增员申请编号</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!增员申请编号为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//内部推荐编号
					if(!empty($val['recommendCode'])) {
						$recommendObj = $recommendDao->find(array('formCode' => $val['recommendCode']));
						if (!empty($recommendObj)) {
							$inArr['recommendCode'] = $val['recommendCode'];
							$inArr['recommendId']   = $recommendObj['id'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!不存在的内部推荐编号</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					//审批状态
					if(!empty($val['ExaStatus'])) {
						if ($val['ExaStatus'] == '完成') {
							$inArr['state']     = 1;
							$inArr['ExaStatus'] = '完成';
						} else if ($val['ExaStatus'] == '未提交') {
							$inArr['state']     = 0;
							$inArr['ExaStatus'] = '未提交';
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!审批状态无效</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$inArr['state']     = 0;
						$inArr['ExaStatus'] = '未提交';
					}

					/**************** S 用人部门部分 *******************/
					//用人部门面试官
					if (!empty($val['interviewer'])) {
						$rs = $otherDataDao->getUserInfo($val['interviewer']);
						if (!empty($rs)) {
							$inArr['use'][0]['interviewer'] = $val['interviewer'];
							$inArr['use'][0]['interviewerId'] = $rs['USER_ID'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的用人部门面试官</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!用人部门面试官为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//用人部门面试日期
					if (!empty($val['interviewDate']) && $val['interviewDate'] != '0000-00-00') {
						if (!is_numeric($val['interviewDate'])) {
							$inArr['use'][0]['interviewDate'] = $val['interviewDate'];
						} else {
							$interviewDate = date('Y-m-d',(mktime(0 ,0 ,0 ,1 ,$val['interviewDate'] - 1 ,1900)));
							$inArr['use'][0]['interviewDate'] = $interviewDate;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!用人部门面试日期为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//用人部门笔试评价
					if (!empty($val['useWriteEva'])) {
						$inArr['use'][0]['useWriteEva'] = $val['useWriteEva'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!用人部门笔试评价为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//用人部门面试评价
					if (!empty($val['interviewEva'])) {
						$inArr['use'][0]['interviewEva'] = $val['interviewEva'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!用人部门面试评价为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//面试结果
					if (!empty($val['useInterviewResult'])) {
						switch ($val['useInterviewResult']) {
							case '立即录用' :
								$inArr['useInterviewResult'] = 1;
								break;
							case '储备人才' :
								$inArr['useInterviewResult'] = 2;
								break;
							default:
								$inArr['useInterviewResult'] = 1; //默认
								break;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!面试结果为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//录用形式
					if (!empty($val['useHireTypeName'])) {
						$rs = $datadictDao->getCodeByName('HRLYXX' ,$val['useHireTypeName']);
						if (!empty($rs)) {
							$inArr['useHireType'] = $rs;
							$inArr['useHireTypeName'] = $val['useHireTypeName'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的录用形式</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!录用形式为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//项目类型
					if (!empty($val['projectType'])) {
						switch ($val['projectType']) {
							case '研发项目' :
								$inArr['projectType'] = 'YFXM';
								break;
							case '工程项目' :
								$inArr['projectType'] = 'GCXM';
								break;
							default:
								$inArr['projectType'] = ''; //默认
								break;
						}
					}

					//所在项目组
					if (!empty($val['projectGroup'])) {
						$inArr['projectGroup'] = $val['projectGroup'];
					}

					//归属公司
					if (!empty($val['sysCompanyName'])) {
						$branchObj = $branchDao->find(array('NameCN' => $val['sysCompanyName']) ,null ,'ID');
						if (!empty($branchObj)) {
							$inArr['sysCompanyName'] = $val['sysCompanyName'];
							$inArr['sysCompanyId'] = $branchObj['ID'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的归属公司</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!归属公司为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//归属区域或支撑中心
					if (!empty($val['useAreaName'])) {
						$sqlStr = 'SELECT ID FROM area WHERE Name="'.$val['useAreaName'].'"';
						$areaObj = $this->findSql($sqlStr);
						if (!empty($areaObj[0])) {
							$inArr['useAreaName'] = $val['useAreaName'];
							$inArr['useAreaId'] = $areaObj[0]['ID'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的归属区域或支撑中心</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!归属区域或支撑中心为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//技术等级
					if (!empty($val['personLevel'])) {
						$levelDao = new model_hr_basicinfo_level();
						$levelObj = $levelDao->find(array('personLevel' => $val['personLevel']) ,null ,'id');
						if (!empty($levelObj)) {
							$inArr['personLevel'] = $val['personLevel'];
							$inArr['personLevelId'] = $levelObj['id'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的技术等级</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!技术等级为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//是否本地化
					if (!empty($val['isLocal'])) {
						switch ($val['isLocal']) {
							case '是' :
								$inArr['isLocal'] = '是';
								break;
							case '否' :
								$inArr['isLocal'] = '否';
								break;
							default:
								$inArr['isLocal'] = '否'; //默认
								break;
						}
					}

					//实习生薪资
					if ($inArr['useHireType'] == 'HRLYXX-03') {
						/**************实习生没有的字段**************/
						$inArr['probation']    = 0;
						$inArr['contractYear'] = 0;
						$inArr['socialPlace']  = '不购买';
						$inArr['contractYear'] = 0;
						/**************实习生没有的字段**************/

						//实习生工资类型
						if (!empty($val['internshipSalaryType'])) {
							if ($val['internshipSalaryType'] == '日工资' || $val['internshipSalaryType'] == '月工资') {
								$inArr['internshipSalaryType'] = $val['internshipSalaryType'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的实习生工资类型</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!实习生工资类型为空</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//实习生工资
						if (!empty($val['internshipSalary'])) {
							if (is_numeric($val['internshipSalary'])) {
								$inArr['internshipSalary'] = $val['internshipSalary'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!实习生工资必须填写整数或小数</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!实习生工资为空</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//餐车补（实习生）
						if (!empty($val['eatCarSubsidy'])) {
							if (is_numeric($val['eatCarSubsidy'])) {
								$inArr['eatCarSubsidy'] = $val['eatCarSubsidy'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!餐车补（实习生）必须填写整数或小数</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['eatCarSubsidy'] = 0;
						}

						//电脑补贴（实习生）
						if (!empty($val['computerIntern'])) {
							if (is_numeric($val['computerIntern'])) {
								$inArr['computerIntern'] = $val['computerIntern'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!电脑补贴（实习生）必须填写整数或小数</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['computerIntern'] = 0;
						}

						$dayNum = ($inArr['internshipSalaryType'] == '日工资') ? 30 : 1;
						//实习工资总额预计
						$inArr['allInternship'] = (double)$inArr['internshipSalary'] * $dayNum
												+ (double)$inArr['eatCarSubsidy']
												+ (double)$inArr['computerIntern'];
					} else { // 非实习生薪资
						//试用期工资
						if (!empty($val['useTrialWage'])) {
							$inArr['useTrialWage'] = $val['useTrialWage'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!试用期基本工资为空</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//转正工资
						if (!empty($val['useFormalWage'])) {
							$inArr['useFormalWage'] = $val['useFormalWage'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!转正基本工资为空</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//电话费补助（试用）
						if (!empty($val['phoneSubsidy'])) {
							if (is_numeric($val['phoneSubsidy'])) {
								$inArr['phoneSubsidy'] = $val['phoneSubsidy'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!电话费补助（试用）必须填写整数或小数</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['phoneSubsidy'] = 0;
						}

						//电话费补助（转正）
						if (!empty($val['phoneSubsidyFormal'])) {
							if (is_numeric($val['phoneSubsidyFormal'])) {
								$inArr['phoneSubsidyFormal'] = $val['phoneSubsidyFormal'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!电话费补助（转正）必须填写整数或小数</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['phoneSubsidyFormal'] = 0;
						}

						//餐车补（试用）
						if (!empty($val['mealCarSubsidy'])) {
							if (in_array($val['mealCarSubsidy'], array(0 ,330 ,440))) {
								$inArr['mealCarSubsidy'] = $val['mealCarSubsidy'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!餐车补（试用）必须填写0、330、440</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['mealCarSubsidy'] = 0;
						}

						//餐车补（转正）
						if (!empty($val['mealCarSubsidyFormal'])) {
							if (in_array($val['mealCarSubsidyFormal'], array(0 ,330 ,440))) {
								$inArr['mealCarSubsidyFormal'] = $val['mealCarSubsidyFormal'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!餐车补（转正）必须填写0、330、440</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['mealCarSubsidyFormal'] = 0;
						}

						//试用期（月）
						if (!empty($val['probation'])) {
							if (strcmp($val['probation'] ,(int)$val['probation']) === 0) {
								$inArr['probation'] = $val['probation'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!试用期（月）必须填写整数</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['probation'] = 0;
						}

						//合同年限
						if (!empty($val['contractYear'])) {
							if (strcmp($val['contractYear'] ,(int)$val['contractYear']) === 0) {
								$inArr['contractYear'] = $val['contractYear'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!合同年限必须填写整数</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['contractYear'] = 0;
						}

						//社保购买地
						if (!empty($val['socialPlace'])) {
							$socialplaceDao = new model_hr_basicinfo_socialplace();
							$socialplaceObj = $socialplaceDao->find(array('socialCity' => $val['socialPlace']) ,null ,'id');
							if (!empty($levelObj)) {
								$inArr['socialPlace'] = $val['socialPlace'];
								$inArr['socialPlaceId'] = $socialplaceObj['id'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的社保购买地</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!社保购买地为空</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					if ($inArr['postType'] == 'YPZW-WY') {
						/********* 网优没有的补助 *********/
						$inArr['mealCarSubsidy'] = 0;
						$inArr['mealCarSubsidyFormal'] = 0;
						/********* 网优没有的补助 *********/

						/********* 网优公用的 *********/
						// 管理岗位
						if (!empty($val['controlPost'])) {
							$rs = $datadictDao->getCodeByName('HRGLGW' ,$val['controlPost']);
							if (!empty($rs)) {
								$inArr['controlPostCode'] = $rs;
								$inArr['controlPost']     = $val['controlPost'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的管理岗位</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!管理岗位为空</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
						/********* 网优公用的 *********/

						$leveltype = $this->getLevelType_d($inArr['positionLevel']);
						if ($leveltype == 1) { //A类
							//出差补助上限值（试用）
							if (!empty($val['tripSubsidy'])) {
								if (is_numeric($val['tripSubsidy'])) {
									$inArr['tripSubsidy'] = $val['tripSubsidy'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!出差补助上限值（试用）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['tripSubsidy'] = 0;
							}

							//出差补助上限值（转正）
							if (!empty($val['tripSubsidyFormal'])) {
								if (is_numeric($val['tripSubsidyFormal'])) {
									$inArr['tripSubsidyFormal'] = $val['tripSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!出差补助上限值（转正）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['tripSubsidyFormal'] = 0;
							}

							//电脑补助（试用）
							if (!empty($val['computerSubsidy'])) {
								if (is_numeric($val['computerSubsidy'])) {
									$inArr['computerSubsidy'] = $val['computerSubsidy'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!电脑补助（试用）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['computerSubsidy'] = 0;
							}

							//电脑补助（转正）
							if (!empty($val['computerSubsidyFormal'])) {
								if (is_numeric($val['computerSubsidyFormal'])) {
									$inArr['computerSubsidyFormal'] = $val['computerSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!电脑补助（转正）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['computerSubsidyFormal'] = 0;
							}

							//奖金上限值（试用）
							if (!empty($val['bonusLimit'])) {
								if (is_numeric($val['bonusLimit'])) {
									$inArr['bonusLimit'] = $val['bonusLimit'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!奖金上限值（试用）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['bonusLimit'] = 0;
							}

							//奖金上限值（转正）
							if (!empty($val['bonusLimitFormal'])) {
								if (is_numeric($val['bonusLimitFormal'])) {
									$inArr['bonusLimitFormal'] = $val['bonusLimitFormal'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!奖金上限值（转正）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['bonusLimitFormal'] = 0;
							}

							//管理津贴（试用）
							if (!empty($val['manageSubsidy'])) {
								if (is_numeric($val['manageSubsidy'])) {
									$inArr['manageSubsidy'] = $val['manageSubsidy'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!管理津贴（试用）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['manageSubsidy'] = 0;
							}

							//管理津贴（转正）
							if (!empty($val['manageSubsidyFormal'])) {
								if (is_numeric($val['manageSubsidyFormal'])) {
									$inArr['manageSubsidyFormal'] = $val['manageSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!管理津贴（转正）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['manageSubsidyFormal'] = 0;
							}

							//临时住宿补助（试用）
							if (!empty($val['accommodSubsidy'])) {
								if (is_numeric($val['accommodSubsidy'])) {
									$inArr['accommodSubsidy'] = $val['accommodSubsidy'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!临时住宿补助（试用）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['accommodSubsidy'] = 0;
							}

							//临时住宿补助（转正）
							if (!empty($val['accommodSubsidyFormal'])) {
								if (is_numeric($val['accommodSubsidyFormal'])) {
									$inArr['accommodSubsidyFormal'] = $val['accommodSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!临时住宿补助（转正）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['accommodSubsidyFormal'] = 0;
							}

							//其他补贴（试用）
							if (!empty($val['otherSubsidy'])) {
								if (is_numeric($val['otherSubsidy'])) {
									$inArr['otherSubsidy'] = $val['otherSubsidy'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!其他补贴（试用）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['otherSubsidy'] = 0;
							}

							//其他补贴（转正）
							if (!empty($val['otherSubsidyFormal'])) {
								if (is_numeric($val['otherSubsidyFormal'])) {
									$inArr['otherSubsidyFormal'] = $val['otherSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!其他补贴（转正）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['otherSubsidyFormal'] = 0;
							}

							//总额预计
							$inArr['allTrialWage'] = (double)$inArr['useTrialWage']
													+ (double)$inArr['tripSubsidy']
													+ (double)$inArr['phoneSubsidy']
													+ (double)$inArr['computerSubsidy']
													+ (double)$inArr['manageSubsidy']
													+ (double)$inArr['accommodSubsidy']
													+ (double)$inArr['bonusLimit']
													+ (double)$inArr['otherSubsidy'];
							$inArr['allFormalWage'] = (double)$inArr['useFormalWage']
													+ (double)$inArr['tripSubsidyFormal']
													+ (double)$inArr['phoneSubsidyFormal']
													+ (double)$inArr['computerSubsidyFormal']
													+ (double)$inArr['manageSubsidyFormal']
													+ (double)$inArr['accommodSubsidyFormal']
													+ (double)$inArr['bonusLimitFormal']
													+ (double)$inArr['otherSubsidyFormal'];
						} else if ($leveltype == 2) {
							//电脑补助（试用）
							if (!empty($val['computerSubsidy'])) {
								if (is_numeric($val['computerSubsidy'])) {
									$inArr['computerSubsidy'] = $val['computerSubsidy'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!电脑补助（试用）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['computerSubsidy'] = 0;
							}

							//电脑补助（转正）
							if (!empty($val['computerSubsidyFormal'])) {
								if (is_numeric($val['computerSubsidyFormal'])) {
									$inArr['computerSubsidyFormal'] = $val['computerSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!电脑补助（转正）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['computerSubsidyFormal'] = 0;
							}

							//工作奖金（试用）
							if (!empty($val['workBonus'])) {
								if (is_numeric($val['workBonus'])) {
									$inArr['workBonus'] = $val['workBonus'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!工作奖金（试用）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['workBonus'] = 0;
							}

							//工作奖金（转正）
							if (!empty($val['workBonusFormal'])) {
								if (is_numeric($val['workBonusFormal'])) {
									$inArr['workBonusFormal'] = $val['workBonusFormal'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!工作奖金（转正）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['workBonusFormal'] = 0;
							}

							//技术级别补助（试用）
							if (!empty($val['levelSubsidy'])) {
								if (is_numeric($val['levelSubsidy'])) {
									$inArr['levelSubsidy'] = $val['levelSubsidy'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!技术级别补助（试用）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['levelSubsidy'] = 0;
							}

							//技术级别补助（转正）
							if (!empty($val['levelSubsidyFormal'])) {
								if (is_numeric($val['levelSubsidyFormal'])) {
									$inArr['levelSubsidyFormal'] = $val['levelSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!技术级别补助（转正）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['levelSubsidyFormal'] = 0;
							}

							//区域补助（试用）
							if (!empty($val['areaSubsidy'])) {
								if (is_numeric($val['areaSubsidy'])) {
									$inArr['areaSubsidy'] = $val['areaSubsidy'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!区域补助（试用）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['areaSubsidy'] = 0;
							}

							//区域补助（转正）
							if (!empty($val['areaSubsidyFormal'])) {
								if (is_numeric($val['areaSubsidyFormal'])) {
									$inArr['areaSubsidyFormal'] = $val['areaSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!区域补助（转正）必须填写整数或小数</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['areaSubsidyFormal'] = 0;
							}

							//总额预计
							$inArr['allTrialWage'] = (double)$inArr['useTrialWage']
													+ (double)$inArr['workBonus']
													+ (double)$inArr['phoneSubsidy']
													+ (double)$inArr['computerSubsidy']
													+ (double)$inArr['levelSubsidy']
													+ (double)$inArr['areaSubsidy'];
							$inArr['allFormalWage'] = (double)$inArr['useFormalWage']
													+ (double)$inArr['workBonusFormal']
													+ (double)$inArr['phoneSubsidyFormal']
													+ (double)$inArr['computerSubsidyFormal']
													+ (double)$inArr['levelSubsidyFormal']
													+ (double)$inArr['areaSubsidyFormal'];
						}
					}

					//配置是否按公司标准
					if (!empty($val['isCompanyStandard'])) {
						switch ($val['isCompanyStandard']) {
							case '是' :
								$inArr['isCompanyStandard'] = 1;
								break;
							case '否' :
								$inArr['isCompanyStandard'] = 0;
								break;
							default:
								$inArr['isCompanyStandard'] = 1; //默认
								break;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!配置是否按公司标准为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//签订《竞业限制协议》
					if (!empty($val['useSign'])) {
						switch ($val['useSign']) {
							case '是' :
								$inArr['useSign'] = '是';
								break;
							case '否' :
								$inArr['useSign'] = '否';
								break;
							default:
								$inArr['useSign'] = '否'; //默认
								break;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!签订《竞业限制协议》为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//办公电脑需求设备类型
					if (!empty($val['useDemandEqu'])) {
						switch ($val['useDemandEqu']) {
							case '公司提供笔记本电脑' :
								$inArr['useDemandEqu'] = '公司提供笔记本电脑';
								break;
							case '公司提供台式电脑' :
								$inArr['useDemandEqu'] = '公司提供台式电脑';
								break;
							case '自备笔记本电脑' :
								$inArr['useDemandEqu'] = '自备笔记本电脑';
								break;
							default:
								$inArr['useDemandEqu'] = ''; //默认
								break;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!办公电脑需求设备类型为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//确认人
					if (!empty($val['useManager'])) {
						$rs = $otherDataDao->getUserInfo($val['useManager']);
						if (!empty($rs)) {
							$inArr['useManager'] = $val['useManager'];
							$inArr['useManagerId'] = $rs['USER_ID'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的确认人</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!确认人为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//确认日期
					if (!empty($val['useSignDate']) && $val['useSignDate'] != '0000-00-00') {
						if (!is_numeric($val['useSignDate'])) {
							$inArr['useSignDate'] = $val['useSignDate'];
						} else {
							$useSignDate = date('Y-m-d',(mktime(0 ,0 ,0 ,1 ,$val['useSignDate'] - 1 ,1900)));
							$inArr['useSignDate'] = $useSignDate;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!确认日期为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//指定导师
					if (!empty($val['tutor'])) {
						$rs = $otherDataDao->getUserInfo($val['tutor']);
						if (!empty($rs)) {
							$inArr['tutor'] = $val['tutor'];
							$inArr['tutorId'] = $rs['USER_ID'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的指定导师</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					/**************** 人力资源部门部分 *******************/
					//HR面试官
					if (!empty($val['interviewer2'])) {
						$rs = $otherDataDao->getUserInfo($val['interviewer2']);
						if (!empty($rs)) {
							$inArr['hr'][0]['interviewer'] = $val['interviewer2'];
							$inArr['hr'][0]['interviewerId'] = $rs['USER_ID'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的HR试官</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!HR试官为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//HR面试日期
					if (!empty($val['interviewDate2']) && $val['interviewDate2'] != '0000-00-00') {
						if (!is_numeric($val['interviewDate2'])) {
							$inArr['hr'][0]['interviewDate'] = $val['interviewDate2'];
						} else {
							$interviewDate = date('Y-m-d',(mktime(0 ,0 ,0 ,1 ,$val['interviewDate2'] - 1 ,1900)));
							$inArr['hr'][0]['interviewDate'] = $interviewDate;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!HR面试日期为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//HR面试评价
					if (!empty($val['interviewEva2'])) {
						$inArr['hr'][0]['interviewEva'] = $val['interviewEva2'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!用人部门笔试评价为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//总体评价
					if (!empty($val['hrInterviewResult'])) {
						$inArr['hrInterviewResult'] = $val['hrInterviewResult'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!总体评价为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//简历来源大类
					if (!empty($val['hrSourceType1Name'])) {
						$rs = $datadictDao->getCodeByName('JLLY' ,$val['hrSourceType1Name']);
						if (!empty($rs)) {
							$inArr['hrSourceType1'] = $rs;
							$inArr['hrSourceType1Name'] = $val['hrSourceType1Name'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的简历来源大类</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!简历来源大类为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//简历来源小类
					if (!empty($val['hrSourceType2Name'])) {
						$inArr['hrSourceType2Name'] = $val['hrSourceType2Name'];
					}

					//录用职位
					if (!empty($val['hrJobName'])) {
						$inArr['hrJobName'] = $val['hrJobName'];
						$jobsDao = new model_deptuser_jobs_jobs();
						$jobsObj = $jobsDao->find(array('dept_id' => $inArr['deptId'] ,'name' => $inArr['hrJobName']));
						if (!empty($jobsObj)) {
							$inArr['hrJobId'] = $jobsObj['id'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!用人部门不存在该录用职位</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!录用职位为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//基本工资与薪点及薪资是否对应
					if (!empty($val['hrIsMatch'])) {
						switch ($val['hrIsMatch']) {
							case '对应' :
								$inArr['hrIsMatch'] = '对应';
								break;
							case '不对应' :
								$inArr['hrIsMatch'] = '不对应';
								break;
							default :
								$inArr['hrIsMatch'] = '对应'; //默认
								break;
						}
					}

					//工资级别
					if (!empty($val['wageLevelName'])) {
						$rs = $datadictDao->getCodeByName('HRGZJB' ,$val['wageLevelName']);
						if (!empty($rs)) {
							$inArr['wageLevelCode'] = $rs;
							$inArr['wageLevelName'] = $val['wageLevelName'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的工资级别</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!工资级别为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//预计入职时间
					if (!empty($val['entryDate']) && $val['entryDate'] != '0000-00-00') {
						if (!is_numeric($val['entryDate'])) {
							$inArr['entryDate'] = $val['entryDate'];
						} else {
							$entryDate = date('Y-m-d',(mktime(0 ,0 ,0 ,1 ,$val['entryDate'] - 1 ,1900)));
							$inArr['entryDate'] = $entryDate;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!预计入职时间为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//增员类型
					if (!empty($val['addType'])) {
						$rs = $datadictDao->getCodeByName('HRZYLX' ,$val['addType']);
						if (!empty($rs)) {
							$inArr['addTypeCode'] = $rs;
							$inArr['addType'] = $val['addType'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的增员类型</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!增员类型为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//无线补助省份
					if(!empty($val['eprovince'])) {
						$provinceObj = $provinceDao->find(array('provinceName' => $val['eprovince']));
						if (!empty($provinceObj)) {
							$inArr['eprovince'] = $val['eprovince'];
							$inArr['eprovinceId'] = $provinceObj['id'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!不存在的无线补助省份</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					//无线补助城市
					if(!empty($val['ecity'])) {
						$cityObj = $cityDao->find(array('cityName' => $val['ecity']));
						if (!empty($cityObj)) {
							$inArr['ecity'] = $val['ecity'];
							$inArr['ecityId'] = $cityObj['id'];
							$inArr['ecountry'] = '中国';
							$inArr['ecountryId'] = 1;
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!不存在的无线补助城市</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					//HR确认人
					if (!empty($val['manager'])) {
						$rs = $otherDataDao->getUserInfo($val['manager']);
						if (!empty($rs)) {
							$inArr['manager'] = $val['manager'];
							$inArr['managerId'] = $rs['USER_ID'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '<span class="red">导入失败!不存在的HR确认人</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!HR确认人为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//HR确认日期
					if (!empty($val['SignDate']) && $val['SignDate'] != '0000-00-00') {
						if (!is_numeric($val['SignDate'])) {
							$inArr['SignDate'] = $val['SignDate'];
						} else {
							$SignDate = date('Y-m-d',(mktime(0 ,0 ,0 ,1 ,$val['SignDate'] - 1 ,1900)));
							$inArr['SignDate'] = $SignDate;
						}
					} else {
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '<span class="red">导入失败!HR确认日期为空</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}
					/**************** E HR部分 *******************/


					$inArr['formCode']  = $this->setFormCode_d($key - 1);//单据编号
					$inArr['formDate']  = date("Y-m-d"); //单据日期
					$inArr['deptState'] = 1;
					$inArr['hrState']   = 1;
					$newId = parent::add_d($inArr ,true);
					if ($newId) {
						foreach($inArr['use'] as $k => $v){
							$v["interviewId"] = $newId;
							$v["interviewerType"] = '1';
							$v["resumeId"] = $inArr['resumeId'];
							$v["resumeCode"] = $inArr['resumeCode'];
							$interviewcomDao->add_d($v ,true);
						}
						foreach($inArr['hr'] as $k => $v){
							$v["interviewId"] = $newId;
							$v["interviewerType"] = '2';
							$v["resumeId"] = $inArr['resumeId'];
							$v["resumeCode"] = $inArr['resumeCode'];
							$interviewcomDao->add_d($v ,true);
						}
						$tempArr['result'] = '导入成功';
					} else {
						$tempArr['result'] = '<span class="red">导入失败</span>';
					}
					$tempArr['docCode'] = '第'.$actNum.'条数据';
					array_push($resultArr ,$tempArr);
				}
				return $resultArr;
			}
		}
	}

	/**
	 * 产生单据编号
	 */
	function setFormCode_d($num = 0) {
		$formCode = 'PG'.date("YmdHis" ,strtotime('+'. $num .' second'));
		$count = $this->findCount(array('formCode' => $formCode));
		if ($count > 0) {
			return $this->setFormCode_d($num + 1);
		} else {
			return $formCode;
		}
	}
	/******************* E 导入导出系列 ************************/

	/**
	 * 根据级别ID判断是什么类型（A返回1、B返回2、其他返回3）
	 */
	function getLevelType_d($levelId) {
		$levelDao = new model_hr_basicinfo_level();
		$levelObj = $levelDao->get_d($levelId);
		$firstStr = substr($levelObj['personLevel'] ,0 ,1);
		if (is_numeric($firstStr) || $firstStr == 'A') {
			return 1;
		} else if ($firstStr == 'B') {
			return 2;
		} else {
			return 3;
		}
	}

	/**
	 * 面试通知发送邮件
	 */
	function thisMail_d($info){
		//改变简历管理里的面试通知 状态
		$resumeDao=new model_hr_recruitment_resume();
		$conditions = array("id"=>$info['resumeId']);
		$resumeDao->updateField($conditions,"isInform","1");
		$emailDao = new model_common_mail();
		$emailDao->InterviewEmail($_SESSION['USERNAME'],$_SESSION['EMAIL'],$info['title'],$info['toMail'],$info['content'],$info['toccMailId'],$info['tobccMail']);

	}

	/**
	 * 导出excel
	 */
	function excelOut($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//创建一个Excel工作流
		//		$objPhpExcelFile = new PHPExcel();

		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/人资-面试评估导出模版.xls" ); //读取模板
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
		$i = 3;
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
		header ( 'Content-Disposition:inline;filename="' . "面试评估导出模版.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * 审批过程的处理
	 */
	function dealAfterAuditIng_d($id ,$taskId) {
		$obj = $this->get_d($id);
		$serviceDeptIdArr = array('120','210','211','212','213','214','215','217','218','219'); //服务线部门id
		if (in_array($obj['deptId'] ,$serviceDeptIdArr)
				&& $obj['postType'] == 'YPZW-WY'
				&& $obj['workProvinceId'] > 0) { //服务线和试点专区、网优类型、省份
			$managerId = $this->get_table_fields('oa_esm_office_managerinfo' ,"provinceId='".$obj['workProvinceId']."'" ,'managerId');

			if (empty($managerId)) return false; //没有项目经理则退出函数

			$sql = "SELECT s.Item ,p.User ,u.USER_NAME ,p.Endtime ,p.Result ,p.Content
					FROM
						flow_step_partent p
					LEFT JOIN wf_task w ON p.Wf_task_ID = w.task
					LEFT JOIN flow_step s ON p.StepID = s.ID
					LEFT JOIN user u ON p.User = u.USER_ID
					WHERE
						w.code = 'oa_hr_recruitment_interview'
					AND w.task = '$taskId'
					AND w.Pid = '$id'
					ORDER BY p.ID ASC";
			$rs = $this->findSql($sql);
			$content = <<<EOT
				<table border="1px" cellspacing="0px" style="text-align:center">
					<tr style="color:blue;">
						<td width="5%">序号</td>
						<td width="15%">步骤名</td>
						<td width="10%">审批人</td>
						<td width="20%">审批日期</td>
						<td width="9%">审批结果</td>
						<td width="27%">审批意见</td>
					</tr>
EOT;
			foreach ($rs as $key => $val) {
				switch ($val['Result']) {
					case 'ok':
						$result = '同意';
						break;
					case 'no':
						$result = '不同意';
						break;
					default:
						$result = '';
						break;
				}
				$rowNum = $key + 1;
				$content .= <<<EOT
					<tr>
						<td>$rowNum</td>
						<td>$val[Item]</td>
						<td>$val[USER_NAME]</td>
						<td>$val[Endtime]</td>
						<td>$result</td>
						<td style="text-align:left">$val[Content]</td>
					</tr>
EOT;
			}
			$content .= "</table>";

			$this->mailDeal_d('interviewAudit' ,$managerId ,array('userName' => $obj['userName'] ,'content' => $content));
		}
	}

	/**
	 * 审批通过的处理
	 */
	function dealAfterAuditPass_d($id){
		try{
			$this->start_d();

			$obj = $this->get_d($id);

			//变更审批的处理
			if ($obj['changeTip'] == 1) {
				$state = 1;
				//查找是否已经发送了录用通知
				$entryId = $this->get_table_fields('oa_hr_recruitment_entrynotice' ," parentId='$obj[id]' AND state<>0 " ,'id');
				if ($entryId) {
					$state = 2;
				}
				$this->updateById(array('id' => $id ,'changeTip' => 0 ,'state' => $state)); //修改变更标识
			}

			$this->commit_d();
			return $id;
		}catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 审批打回的处理
	 */
	function dealAfterAuditFail_d($id){
		try{
			$this->start_d();

			$obj = $this->get_d($id);

			//变更审批的处理
			if ($obj['changeTip'] == 1) {
				$state = 1;
				//查找是否已经发送了录用通知
				$entryId = $this->get_table_fields('oa_hr_recruitment_entrynotice' ," parentId='$obj[id]' AND state<>0 " ,'id');
				if ($entryId) {
					$state = 2;
				}
				$this->updateById(array('id' => $id ,'changeTip' => 0 ,'ExaStatus' => '完成' ,'state' => $state)); //修改变更标识
				$this->recoveryLastTime_d($id); //还原最近一次修改的记录
			}

			$this->commit_d();
			return $id;
		}catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 编辑审批完成的单据
	 */
	function editAuditFinish_d($object){
		try{
			$this->start_d();

			//原始记录
			$oldObj = $this->get_d($object['id']);

			//归属公司
			$branchDao = new model_deptuser_branch_branch();
			$branchCN = $branchDao->get_d($object['sysCompanyId']);
			$object['sysCompanyName'] = $branchCN['NameCN'];

			//归属区域
			$object['useAreaName'] = $this->get_table_fields('area' ," ID='$object[useAreaId]' " ,'Name');

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object['hrSourceType1Name'] = $datadictDao->getDataNameByCode($object['hrSourceType1']);
			$object['useHireTypeName'] = $datadictDao->getDataNameByCode($object['useHireType']);
			$object['addType'] = $datadictDao->getDataNameByCode($object['addTypeCode']);
			$object['wageLevelName'] = $datadictDao->getDataNameByCode($object['wageLevelCode']);
            $object['controlPost']       = $datadictDao->getDataNameByCode($object['controlPostCode']);
			$object = $this->processDatadict($object);

			$id = parent::edit_d($object);
			//用来判断是否有做实际上的更新
			$affectedRows = $this->_db->affected_rows();
			
			if ($id) {
				//处理从表数据
				$interviewcomDao = new model_hr_recruitment_interviewComment();
				if (is_array($object['items'])) {
					//判断用人部门意见从表，原有的就编辑，新加的往数据库中添加
					foreach($object['items'] as $key => $value) {
						if ($value['id']){
							$value["interviewId"] = $object['id'];
							$value["interviewerType"] = '1';
							if (!isset($value['isDelTag'])) {
								$interviewcomDao->edit_d($value ,true);
							}
						} else {
							$value["interviewId"] = $object['id'];
							$value["interviewerType"] = '1';
							if (!isset($value['isDelTag'])) {
								$interviewcomDao->add_d($value ,true);
							}
						}
					}
				}

				if (is_array($object['humanResources'])) {
					//判断人力资源部门意见从表，原有的就编辑，新加的往数据库中添加
					foreach($object['humanResources'] as $key => $value) {
						if ($value['id']) {
							$value["interviewId"] = $object['id'];
							$value["interviewerType"] = '2';
							if (!isset($value['isDelTag'])) {
								$interviewcomDao->edit_d($value ,true);
							}
						} else {
							$value["interviewId"] = $object['id'];
							$value["interviewerType"] = '2';
							if (!isset($value['isDelTag'])) {
								$interviewcomDao->add_d($value ,true);
							}
						}
					}
				}
			}
			
			$this->operationLog_d($oldObj ,$object); //操作日志记录
			
			$this->commit_d();
			return $affectedRows;
		}catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 操作日志记录
	 */
	function operationLog_d($oldObj ,$newObj) {
		$logSettringDao = new model_syslog_setting_logsetting ();
		/******需要转中文处理******/
		//级别
		$oldObj['positionLevelName'] = $this->getPositionLevelName_d($oldObj['postType'] ,$oldObj['positionLevel']);
		$newObj['positionLevelName'] = $this->getPositionLevelName_d($newObj['postType'] ,$newObj['positionLevel']);

		//面试结果
		if ($oldObj['useInterviewResult'] == '0') {
			$oldObj['useInterviewResultName'] = '储备人才';
		} else {
			$oldObj['useInterviewResultName'] = '立即录用';
		}
		if ($newObj['useInterviewResult'] == '0') {
			$newObj['useInterviewResultName'] = '储备人才';
		} else {
			$newObj['useInterviewResultName'] = '立即录用';
		}

		//项目类型
		if ($oldObj['projectType'] == 'GCXM') {
			$oldObj['projectTypeName'] = '工程项目';
		} else if ($oldObj['projectType'] == 'YFXM') {
			$oldObj['projectTypeName'] = '研发项目';
		} else {
			$oldObj['projectTypeName'] = '';
		}
		if ($newObj['projectType'] == 'GCXM') {
			$newObj['projectTypeName'] = '工程项目';
		} else if ($newObj['projectType'] == 'YFXM') {
			$newObj['projectTypeName'] = '研发项目';
		} else {
			$newObj['projectTypeName'] = '';
		}

		//配置是否按公司标准
		if ($oldObj['isCompanyStandard'] == '1') {
			$oldObj['isCompanyStandardName'] = '是';
		} else {
			$oldObj['isCompanyStandardName'] = '否';
		}
		if ($newObj['isCompanyStandard'] == '1') {
			$newObj['isCompanyStandardName'] = '是';
		} else {
			$newObj['isCompanyStandardName'] = '否';
		}

		/******部分金额做特殊处理********/
		//试用总额预计
		if ($newObj['allTrialWage'] == '') {
			$newObj['allTrialWage'] = 0.00;
		}

		//转正总额预计
		if ($newObj['allFormalWage'] == '') {
			$newObj['allFormalWage'] = 0.00;
		}

		//实习工资
		if ($newObj['internshipSalary'] == '') {
			$newObj['internshipSalary'] = 0.00;
		}

		$logSettringDao->compareModelObj($this->tbl_name ,$oldObj ,$newObj);
	}

	/**
	 * 根据职位类型和级别id，获取级别的中文名称
	 */
	function getPositionLevelName_d($postType ,$positionLevel) {
		$levelName = '';
		if($postType == 'YPZW-WY' || $postType == '网优') {
			$level = new model_hr_basicinfo_level();
			$WYlevel = $level->get_d($positionLevel);
			$levelName = $WYlevel['personLevel'];
		} else {
			switch ($positionLevel){
				case '1' : $levelName = '初级'; break;
				case '2' : $levelName = '中级'; break;
				case '3' : $levelName = '高级'; break;
			}
		}
		return $levelName;
	}

	/**
	 * 根据单据id还原最后一次修改记录
	 */
	function recoveryLastTime_d($id) {
		try {
			$this->start_d();

			$logsettingDao = new model_syslog_setting_logsetting();
			$logObj = $logsettingDao->find(array('tableName' => $this->tbl_name));
			if ($logObj) {
				$operationItemDao = new model_syslog_operation_logoperationItem();
				$editObj = $lastTimeObj = $operationItemDao->findByLogAndPk($logObj['id'] ,$id);
				if (is_array($editObj)) {
					foreach ($editObj as $key => $val) {
						/****有进行中文转换处理的需要进行转回处理****/
						//级别
						if ($val['columnCcode'] == 'positionLevelName') {
							switch ($val['oldValue']) {
								case '初级' : $levelId = '1'; break;
								case '中级' : $levelId = '2'; break;
								case '高级' : $levelId = '3'; break;
								default : $levelId = $this->get_table_fields('oa_hr_level' ," personLevel='$val[positionLevelName]' " ,'id');
							}
							$lastObj['positionLevel'] = $levelId;
							continue;
						}

						//面试结果
						if ($val['columnCcode'] == 'useInterviewResultName') {
							if ($val['oldValue'] == '储备人才') {
								$lastObj['useInterviewResult'] = 0;
							} else {
								$lastObj['useInterviewResult'] = 1;
							}
							continue;
						}

						//项目类型
						if ($val['columnCcode'] == 'projectTypeName') {
							if ($val['oldValue'] == '工程项目') {
								$lastObj['projectType'] = 'GCXM';
							} else if ($val['oldValue'] == '研发项目') {
								$lastObj['projectType'] = 'YFXM';
							} else {
								$lastObj['projectType'] = '';
							}
							continue;
						}

						//配置是否按公司标准
						if ($val['columnCcode'] == 'isCompanyStandardName') {
							if ($val['oldValue'] == '是') {
								$lastObj['isCompanyStandard'] = 1;
							} else {
								$lastObj['isCompanyStandard'] = 0;
							}
							continue;
						}

						$lastObj[$val['columnCcode']] = $val['oldValue'];
					}
				}
				$lastObj['id'] = $id;
				$this->updateById($lastObj); //修改为原来的记录
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
	
	/**
	 * 发送邮件通知人事组相关人员
	 * @param $id 单据id
	 * @param $fieldArr 需要验证的字段数组,默认为空
	 */
	function sendMailByEdit_d($id,$fieldArr = null) {
		//根据id获取操作日志内容
		$logoperationDao = new model_syslog_operation_logoperation();
		$logoperationDao->searchArr = array ("tableName" => $this->tbl_name, "pkValue" => $id );
		$rows = $logoperationDao->listBySqlId ( "select_detail" );
		if($rows){
			//获取单据编号
			$rs = $this->find(array('id' => $id),null,'formCode');
			//只显示最新的操作日志
			$logContent =  $logoperationDao->showAtBusinessView(array($rows[0]));
			//去掉详细内容隐藏
			$logContent = str_replace('style="display:none"', "", $logContent);
			$logContent = str_replace('<a href="#" onclick="showTrContent(this,0)">＞＞＞</a>', "", $logContent);
			$logContent = str_replace('业务主键字段值:'.$id, "单据编号:".$rs['formCode'], $logContent);
			if(!empty($fieldArr)){
				$fieldCount = 0;//计算验证字段实际存在的个数
				if(in_array("socialPlace", $fieldArr) && strpos($logContent,"socialPlace")){//如果更新了社保公积金购买地，则通知人事组相关人员
					$fieldCount ++;
					$this->mailDeal_d('interviewEditSocialPlace',null,array('mailContent' => $logContent));
					//计算验证外的字段数量,需要减去验证字段的个数以及操作日志中多出的3个<tr>
					$trNum = substr_count($logContent,"<tr") - $fieldCount - 3;
					if($trNum > 0){//除了需要验证的字段外，还更新了其它字段，需要通知其他人员
						$this->mailDeal_d('interviewEdit',null,array('mailContent' => $logContent));
					}
				}else{
					$this->mailDeal_d('interviewEdit',null,array('mailContent' => $logContent));
				}
			}else{
				//默认通知邮件
				$this->mailDeal_d('interviewEdit',null,array('mailContent' => $logContent));
			}
		}
	}
	
	/**
	 * 获取当前单据的审批次数
	 * @param $id 单据id
	 */
	function countWorkFlow($id){
		$sql = "SELECT COUNT(*) as count FROM wf_task WHERE code = '".$this->tbl_name."' AND Pid = '".$id."'";
		$rs = $this->findSql($sql);
		return $rs[0]['count'];
	}
}