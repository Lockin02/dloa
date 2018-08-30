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
 * @Date 2012年5月25日 星期五 15:21:04
 * @version 1.0
 * @description:人事管理-基本信息 Model层
 */

class model_hr_personnel_personnel  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel";
		$this->sql_map = "hr/personnel/personnelSql.php";
		parent::__construct ();
	}

	//数据字典字段处理
	public $datadictFieldArr = array(
		'deptSuggest'
		);

	//通过登陆用户ID获取用户所在公司和部门的信息
	function getDeptInfo($userID){
		$sql = "select c.companyName,c.deptIdS,c.deptIdT from oa_hr_personnel c where c.userAccount ='".$userID."'";
		$obj = $this->_db->getArray($sql);
		return $obj[0];
	}


	//附件 获取照片地址
	function getFilePhoto_d($objId){
		$uploadFile = new model_file_uploadfile_management ();
		if (empty ( $serviceType )) {
			$serviceType = $this->tbl_name;
		}

		$files = $uploadFile->getFilesByObjId ( $objId, $serviceType );
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
		return $url = $str."/".$files[0]['serviceType']."/".$files[0]['newName'];
	}

	/**
	 * 员工编号添加规则
	 */
	function userNoAdd_d($companyName,$deptId=null){
		$muc="";
		if($deptId>0){
			$empFlag = $this->get_table_fields('department', "DEPT_ID='".$deptId."'", 'empFlag');
			if($empFlag == 1) {//正式员工
				$personnelRow = $this->listBySqlId ( "select_userNo" );
				$muc = $personnelRow['0']['muc']+1;
				$muc = sprintf("%06.0f",$muc);
				$sql = "SELECT comcard FROM branch_info i  where NameCN='".$companyName."' ";
				$rs = $this->_db->getArray($sql);
				$comc = $rs['0']['comcard'];
				$muc = $comc.$muc;
			} else {//外聘团队
				$personnelRow = $this->listBySqlId ( "select_extend_userNo" );
				if($personnelRow['0']['muc'] > 0) {
					$muc = $personnelRow['0']['muc'] + 1;
				} else {
					$muc = '0000001';
				}
				$muc = sprintf("%07.0f",$muc);
				$muc = "C".$muc;
			}
		}
		return $muc;
	}

	/**
	 * 更新旧系统档案信息
	 */
	function updateOldInfo_d($id,$type,$flag=null,$companyChange=null){
		$object=$this->get_d($id);
		//婚姻状况
		switch($object['maritalStatusName']){
			case "未婚":$MARRY = '0';break;
			case '已婚':$MARRY = '1';break;
			default:$MARRY = '';break;
		}
		//学历
		switch($object['highEducationName']){
			case "初中":$EDUCATION = '2';break;
			case '高中':$EDUCATION = '3';break;
			case "中专":$EDUCATION = '4';break;
			case '大专':$EDUCATION = '5';break;
			case "本科":$EDUCATION = '6';break;
			case '硕士':$EDUCATION = '7';break;
			case "博士":$EDUCATION = '9';break;
			case '未接受正规教育':$EDUCATION = '10';break;
			default:$EDUCATION = '';break;
		}
		//用工方式
		switch($object['personnelTypeName']){
			case "正式员工":$intern='1';break;
			case '派遣员工':$intern='2';break;
			case '实习生':$intern='3';break;
			case '长期实习生':$intern='1';break;
			case '短期实习生':$intern='5';break;
			case '本地化员工':$intern='4';break;
			default:$intern='';break;
		}
		if($intern == '2') {
			$ExpFlag = '1';
		} else {
			$ExpFlag = '0';
		}
		//工资级别
		switch($object['wageLevelName']){
			case "总经理":$UserLevel='0';$jobL=2;break;
			case '副总':$UserLevel='1';$jobL=2;break;
			case '总监':$UserLevel='2';$jobL=2;break;
			case '经理':$UserLevel='3';$jobL=2;break;
			case '非管理层':$UserLevel='4';$jobL=1;break;;
			case '副总监':$UserLevel='5';$jobL=2;break;
			case '主管':$UserLevel='6';$jobL=2;break;
			default:$UserLevel='';$jobL=2;break;
		}
		//人员等级
		switch(substr($object['personLevel'],0,1)){
			case "B":$localizers=2;break;
			default:$localizers=1;break;
		}
		//性别
		switch($object['sex']){
			case "男":$sex='0';break;
			case '女':$sex='1';break;
		}
		//离职日期
		if($object['quitDate']=='0000-00-00'||$object['quitDate']==''){
			$object['quitDate']='null';
		}else{
			$object['quitDate']="'".$object['quitDate']."'";
		}
		//转正日期
		if($object['realBecomeDate']=='0000-00-00'||$object['realBecomeDate']==''){
			if($object['becomeDate']!='0000-00-00'||$object['realBecomeDate']!=''){
				$object['becomeDate']="'".$object['becomeDate']."'";
			}else{
				$object['becomeDate']='null';
			}
		}else{
			$object['becomeDate']="'".$object['realBecomeDate']."'";
		}
		//入职日期
		if($object['entryDate']=='0000-00-00'||$object['entryDate']==''){
			$object['entryDate']='null';
		}else{
			$object['entryDate']="'".$object['entryDate']."'";
		}
		$POLITICS = "";
		$Prjoect_Place='';
		$PHOTO='';
		$bankAdd='';
		$contFlagB='';
		$contFlagE='';
		$Address=$object['homeAddressPro'].$object['homeAddressCity'].$object['homeAddress'];
		$Native=$object['residencePro'].$object['residenceCity'];
		 	//判断旧系统是否已有该员工档案信息
		$userNo=$this->get_table_fields('hrms', "UserCard='".$object['userNo']."'", 'UserCard');
		$sql='';
		if($userNo==""&&$type=='add'){
			$sql="insert into hrms(USER_ID,CARD_NO,MARRY,COME_DATE,JOIN_DATE,EDUCATION,POLITICS,CERTIFICATE,School,Major
				,Address,POST,Account,AccCard,Bank,Tele,Native,BIRTHDAY,Prjoect_Place,Email,PHOTO,Creator,CreateDT
				,JobLevel,expflag,intern, bankadd,contflagb,contflage,userlevel,usercard,jobfunc,technicalGrade)values('".$object["userAccount"]."','".$object["identityCard"]."'
				,'".$MARRY."',".$object["entryDate"].",".$object["becomeDate"].",'$EDUCATION','".$POLITICS."','".$object["jobName"]."'
				,'".$object["highSchool"]."','".$object["professionalName"]."','".$Address."','".$object["homePost"]."'
				,'".$object["oftenAccount"]."','".$object["oftenCardNum"]."','".$object["oftenBank"]."','".$object["mobile"]."','$Native'
				,'".$object["birthdate"]."','$Prjoect_Place','".$object["compEmail"]."','".$PHOTO."','".$object["createId"]."','".$object["createTime"]."'
				,'".$jobL."','".$ExpFlag."','".$intern."'
				,'$bankAdd','$contFlagB','$contFlagE','".$UserLevel."','".$object['userNo']."','".$object['functionName']."','".$object['personLevel']."')";
		}else if($userNo!=""&&$type='edit'){
			$sql="update  hrms set userlevel='".$UserLevel."' , CARD_NO='".$object["identityCard"]."',MARRY='".$MARRY."'
			,COME_DATE=".$object["entryDate"].",LEFT_DATE=".$object["quitDate"].",EDUCATION='".$EDUCATION."'
			,POLITICS='".$POLITICS."',CERTIFICATE='".$object["jobName"]."',School='".$object["highSchool"]."'
			,Major='".$object["professionalName"]."',Address='".$Address."',POST='".$object["homePost"]."'
			,Account='".$object["oftenAccount"]."',AccCard='".$object["oftenCardNum"]."',Tele='".$object["mobile"]."',Bank='".$object["oftenBank"]."'
			,Native='".$Native."',BIRTHDAY='".$object["birthdate"]."',Prjoect_Place='".$Prjoect_Place."',Email='".$object["compEmail"]."'
			,Updator='".$object["updateId"]."',UpdateDT='".$object["updateTime"]."' ,JobLevel='".$jobL."'
			,bankadd='$bankAdd', jobfunc='".$object['functionName']."',JOIN_DATE=".$object['becomeDate'].",expflag='".$ExpFlag."',intern='".$intern."',technicalGrade='".$object['personLevel']."'
			where UserCard='".$object["userNo"]."'";
		}
		if($sql != '') {
			$this->query($sql);
			$area=$this->get_table_fields('area', "Name='".$object['regionName']."'", 'ID');
			$compt=$this->get_table_fields('branch_info', "NameCN='".$object['companyName']."'", 'NamePT');
			if(!$flag){
				$sql1="update user set SEX='".$sex."',jobs_id='".$object["jobId"]."',DEPT_ID='".$object["belongDeptId"]."',area='".$area."' ,localizers='".$localizers."'  where USER_ID='".$object["userAccount"]."'";
			}else{
				$sql1="update user set SEX='".$sex."',jobs_id='".$object["jobId"]."',DEPT_ID='".$object["belongDeptId"]."',area='".$area."' , company='".$compt."', localizers='".$localizers."'  where USER_ID='".$object["userAccount"]."'";
			}
			$this->query($sql1);
			if($flag!=2){
				$this->query("update ecard set Ministration='".$object["jobName"]."' where User_id='".$object["userAccount"]."' ");
							    //更新工资模块的身份证信息
				$sql2="update salary set idcard='".trim($object['identityCard'])."' where userid='".$object['userAccount']."'";
				$this->query($sql2);
							  	//判断所属部门是否在用
				$deptFlag=$this->get_table_fields('department', "DEPT_ID='".$object['belongDeptId']."'", 'DelFlag');
				if(!$deptFlag){
								   	//更新大蚂蚁
					$logname=$this->get_table_fields('user', "user_id='".$object['userAccount']."'", 'logname');
					$obj_dept = new model_system_dept();
					$p_dept = $obj_dept->GetParents_ID($object['belongDeptId']);
					if ($p_dept) {
						$p_dept = array_reverse($p_dept);
						$dept_name = implode('/', array_keys($p_dept)) . '/' . $object["belongDeptName"];
					} else {
						$dept_name = $object["belongDeptName"];
					}
					$data = array(
						'COM_BRN_CN'=>'世纪鼎利',
						'DEPT_NAME'=>$dept_name,
						'JOBS_NAME'=>$object["jobName"],
						'USER_ID'=>$logname,
						);
					$im = new includes_class_ImInterface();
					if(!empty($object["belongDeptName"])){
						$im->edit_user('世纪鼎利',$logname,$data);
					}
				}
			}
		}
	}

	/**
	 * 重写add
	 */
	function add_d($object) {
		$identityCard = $this->get_table_fields('oa_hr_personnel', "identityCard='".$object['identityCard']."' AND employeesState='YGZTZZ'", 'identityCard');
		if($identityCard != '') {
			return false;
		}
		try{
			$this->start_d();

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object['politicsStatus'] = $datadictDao->getDataNameByCode ( $object['politicsStatusCode'] );
			$object['highEducationName'] = $datadictDao->getDataNameByCode ( $object['highEducation'] );
			$object['healthState'] = $datadictDao->getDataNameByCode ( $object['healthStateCode'] );
			$object['englishSkillName'] = $datadictDao->getDataNameByCode ( $object['englishSkill'] );
			$object['employeesStateName'] = $datadictDao->getDataNameByCode ( $object['employeesState'] );
			$object['staffStateName'] = $datadictDao->getDataNameByCode ( $object['staffState'] );
			$object['personnelTypeName'] = $datadictDao->getDataNameByCode ( $object['personnelType'] );
			$object['positionName'] = $datadictDao->getDataNameByCode ( $object['position'] );
			$object['personnelClassName'] = $datadictDao->getDataNameByCode ( $object['personnelClass'] );
			$object['wageLevelName'] = $datadictDao->getDataNameByCode ( $object['wageLevelCode'] );
			$object['functionName'] = $datadictDao->getDataNameByCode ( $object['functionCode'] );
			$object['technologyName'] = $datadictDao->getDataNameByCode ( $object['technologyCode'] );
			$object['networkName'] = $datadictDao->getDataNameByCode ( $object['networkCode'] );
			$object['deviceName'] = $datadictDao->getDataNameByCode ( $object['deviceCode'] );

			$object['mobilePhone'] = $object['mobile'];//电话
			if(isset($object['outsourcingCode'])) {
				$object ['outsourcingName'] = $datadictDao->getDataNameByCode ( $object['outsourcingCode'] );
			}
			if(isset($object['salaryAreaTypeCode'])) {
				$object ['salaryAreaCode'] = $datadictDao->getDataNameByCode ( $object['salaryAreaTypeCode'] );
			}
			if($object ['userNo'] == "") {
				$object ['userNo'] = $this->userNoAdd_d($object['companyName'] ,$object['belongDeptId']);
			}
			if(isset($object['companyTypeCode'])) {
				if($object['companyTypeCode'] == 0) {
					$object['companyType'] = '子公司';
				} else {
					$object['companyType'] = '集团';
				}
				$object['companyId'] = $this->get_table_fields('branch_info', "NameCN='".$object['companyName']."'", 'ID');
				if($object['regionId'] > 0) {
					$object['regionName'] = $this->get_table_fields('area', "ID='".$object['regionId']."'", 'Name');
				}
			}
			$id = parent::add_d($object ,true);
			//处理附件名称和Id
			$this->updateObjWithFile($id);
			if($object['entryId'] > 0) {
				$entryNoticeDao = new model_hr_recruitment_entryNotice();
				$entryNoticeDao->updateField("id=".$object['entryId'] ,'staffFileState' ,1);
				$entryNoticeDao->updateField("id=".$object['entryId'] ,'userAccount' ,$object['userAccount']);
				$entryNoticeDao->updateField("id=".$object['entryId'] ,'userNo' ,$object['userNo']);
				$entryNoticeDao->updateField("id=".$object['entryId'] ,'entryDate' ,$object['entryDate']);

				if($object['employmentId'] > 0) {
					//同步工作经历
					$workDao = new model_hr_recruitment_work();
					$workRows = $workDao->getInfoByParentId_d($object['employmentId']);
					if(is_array($workRows)){
						$personWorkDao = new model_hr_personnel_work();
						foreach($workRows as $key => $val) {
							$workArr['userNo'] = $object['userNo'];
							$workArr['userAccount'] = $object['userAccount'];
							$workArr['userName'] = $object['userName'];
							$workArr['company'] = $val['company'];
							$workArr['dept'] = $val['dept'];
							$workArr['position'] = $val['position'];
							$workArr['treatment'] = $val['treatment'];
							$workArr['beginDate'] = $val['beginDate'];
							$workArr['closeDate'] = $val['closeDate'];
							$workArr['seniority'] = $val['seniority'];
							$workArr['isSeniority'] = $val['isSeniority'];
							$workArr['responsibilities'] = $val['responsibilities'];
							$workArr['leaveReason'] = $val['leaveReason'];
							$workArr['prove'] = $val['prove'];
							$workArr['remark'] = $val['remark'];
							$personWorkDao->add_d($workArr,true);
						}
					}

					//教育经历
					$educationDao = new model_hr_recruitment_education();
					$educationRows = $educationDao->getInfoByParentId_d($object['employmentId']);
					if(is_array($educationRows)){
						$personEducationDao = new model_hr_personnel_education();
						foreach($educationRows as $key => $val) {
							$eductionArr['userNo'] = $object['userNo'];
							$eductionArr['userAccount'] = $object['userAccount'];
							$eductionArr['userName'] = $object['userName'];
							$eductionArr['organization'] = $val ['organization'];
							$eductionArr['content'] = $val ['content'];
							$eductionArr['education'] = $val ['education'];
							$eductionArr['educationName'] = $val ['educationName'];
							$eductionArr['certificate'] = $val ['certificate'];
							$eductionArr['beginDate'] = $val ['beginDate'];
							$eductionArr['closeDate'] = $val ['closeDate'];
							$eductionArr['remark'] = $val ['remark'];
							$personEducationDao->add_d($eductionArr,true);
						}
					}

					//家庭成员
					$familyDao = new model_hr_recruitment_family();
					$familyRows = $familyDao->getInfoByParentId_d($object['employmentId']);
					if(is_array($familyRows)){
						$personSocietyDao = new model_hr_personnel_society();
						foreach($familyRows as $key => $val){
							$familyArr['userNo'] = $object['userNo'];
							$familyArr['userAccount'] = $object['userAccount'];
							$familyArr['userName'] = $object['userName'];
							$familyArr['relationName'] = $val ['name'];
							$familyArr['age'] = $val ['age'];
							$familyArr['isRelation'] = $val ['relation'];
							$familyArr['information'] = $val ['information'];
							$familyArr['workUnit'] = $val ['work'];
							$familyArr['job'] = $val ['post'];
							$personSocietyDao->add_d($familyArr,true);
						}
					}
				}
			}

			//同步更新旧系统中的档案信息
			if(isset($object["flagstate"]) && $object["flagstate"] == 2) {
				$this->updateOldInfo_d($id ,'add' ,2);
			} else {
				$this->updateOldInfo_d($id ,'add');
			}

			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写edit
	 */
	function edit_d($object){
		try{
			$this->start_d();

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			if(isset($object['politicsStatusCode'])){
				$object ['politicsStatus'] =  $datadictDao->getDataNameByCode ( $object['politicsStatusCode'] );
			}
			if(isset($object['highEducation'])){
				$object ['highEducationName'] =  $datadictDao->getDataNameByCode ( $object['highEducation'] );
			}
			if(isset($object['healthStateCode'])){
				$object ['healthState'] =  $datadictDao->getDataNameByCode ( $object['healthStateCode'] );
			}
			$object ['healthState'] =  $datadictDao->getDataNameByCode ( $object['healthStateCode'] );
			if(isset($object['englishSkill'])){
				$object ['englishSkillName'] =  $datadictDao->getDataNameByCode ( $object['englishSkill'] );
			}
			if(isset($object['employeesState'])){
				$object ['employeesStateName'] =$datadictDao->getDataNameByCode ( $object['employeesState'] );
			}
			if(isset($object['staffState'])){
				$object ['staffStateName'] =$datadictDao->getDataNameByCode ( $object['staffState'] );
			}
			if(isset($object['personnelType'])){
				$object ['personnelTypeName'] =$datadictDao->getDataNameByCode ( $object['personnelType'] );
			}
			if(isset($object['position'])){
				$object ['positionName'] =$datadictDao->getDataNameByCode ( $object['position'] );
			}
			if(isset($object['personnelClass'])){
				$object ['personnelClassName'] =$datadictDao->getDataNameByCode ( $object['personnelClass'] );
			}
			if(isset($object['wageLevelCode'])){
				$object ['wageLevelName'] =$datadictDao->getDataNameByCode ( $object['wageLevelCode'] );
			}
			if(isset($object['functionCode'])){
				$object ['functionName'] =$datadictDao->getDataNameByCode ( $object['functionCode'] );
			}
			if(isset($object['technologyCode'])){
				$object ['technologyName'] =$datadictDao->getDataNameByCode ( $object['technologyCode'] );
			}
			if(isset($object['networkCode'])){
				$object ['networkName'] =$datadictDao->getDataNameByCode ( $object['networkCode'] );
			}
			if(isset($object['deviceCode'])){
				$object ['deviceName'] =$datadictDao->getDataNameByCode ( $object['deviceCode'] );
			}
			if(isset($object['outsourcingCode'])){
				$object ['outsourcingName'] =$datadictDao->getDataNameByCode ( $object['outsourcingCode'] );
			}
			if(isset($object['salaryAreaTypeCode'])){
				$object ['salaryAreaCode'] =$datadictDao->getDataNameByCode ( $object['salaryAreaTypeCode'] );
			}
			if(isset($object['companyTypeCode'])){
				if($object['companyTypeCode']==0){
					$object['companyType']='子公司';
				}else{
					$object['companyType']='集团';
				}
				$object ['companyId']=$this->get_table_fields('branch_info', "NameCN='".$object['companyName']."'", 'ID');
				if($object['regionId']>0){
					$object ['regionName']=$this->get_table_fields('area', "ID='".$object['regionId']."'", 'Name');
				}
			}
			$oldObj = $this->get_d ( $object ['id'] );
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object );
			//判断户籍类型是否改变，是则邮件通知指定人
			if($oldObj['householdType']!=$object['householdType']&&!isset($object['userNo'])){
				$this->mailNotice($oldObj,$object);		//我的档案户籍变动邮件通知
			}
			$id=parent::edit_d($object,true);

			//同步更新旧系统中的档案信息
			$this->updateOldInfo_d($object['id'],'edit',1,$object['companyChange']);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/*
	 * 我的档案户籍变动邮件通知
	 */
	function mailNotice($oldObj,$object){
		include (WEB_TOR . "model/common/mailConfig.php");
		$mailId = $mailUser['oa_hr_personnel']['TO_ID'];
		$addMsg = "您好 ：</br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;【".$oldObj['belongDeptName']."】员工编号为【".$oldObj['userNo']."】姓名【".$oldObj['userName']."】的户籍类型从【".$oldObj['householdType']."】改成【".$object['householdType']."】";
		$emailDao = new model_common_mail();
		$emailDao->mailClear('个人档案户籍变动通知',$mailId, $addMsg);
	}

	/**
	 * 外部调用edit方法，不涉及到同步更新
	 */
	function editExtra_d($object){
		try{
			$this->start_d();
			$oldObj = $this->get_d ( $object ['id'] );
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object );
			$id=parent::edit_d($object,true);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/*
	 * 离职管理，更新离职员工档案调用方法
	 */
	function updataLeave_d($object){
		try{
			$this->start_d();
	 		//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			if(isset($object['quitTypeCode'])){
				$object['quitTypeName'] = $datadictDao->getDataNameByCode($object['quitTypeCode']);
			}
			if(isset($object['employeesState'])){
				$object['employeesStateName'] = $datadictDao->getDataNameByCode($object['employeesState']);
			}
			if(isset($object['staffState'])){
				$object['staffStateName'] = $datadictDao->getDataNameByCode($object['staffState']);
			}

			$oldObj = $this->get_d ( $object ['id'] );
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object );
			$id = parent::edit_d($object,true);

			//同步更新旧系统中的档案信息
			$this->updateOldInfo_d($object['id'],'edit');

			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 修改入离职信息
	 */
	function editInLeave_d($object){
		try{
			$this->start_d();

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object['quitTypeName'] = $datadictDao->getDataNameByCode( $object['quitTypeCode'] );
			if($object['quitTypeCode'] !== "") {
				$object['staffState'] =  $object['quitTypeCode'];
				$object['staffStateName'] = $object['quitTypeName'];
				$object['employeesState'] = 'YGZTLZ';
				$object['employeesStateName'] = $datadictDao->getDataNameByCode( $object['employeesState'] );
			}
			$oldObj = $this->get_d ( $object ['id'] );
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj($this->tbl_name ,$oldObj ,$object);
			$id = parent::edit_d($object ,true);

			//同步更新旧系统中的档案信息
			$this->updateOldInfo_d($object['id'] ,'edit');
			$this->commit_d();
			return $id;
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * 修改联系信息
	 */
	function editContact_d($object){
		try{
			$this->start_d();
			$id= parent::edit_d($object,true);

			//同步更新旧系统中的档案信息
			$this->updateOldInfo_d($object['id'],'edit');
			//更新通讯录
			$im = new includes_class_ImInterface();
			$LOG_NAME=$this->get_table_fields('user', "USER_ID='".$object['userAccount']."'", 'LogName');
			$im->edit_userInfo($LOG_NAME, array (
				'Col_o_Phone' => $object['extensionNum']?$object['extensionNum'].'('.$object['unitPhone'].')':$object['unitPhone'],
				'Col_Mobile' => $object['shortNum']?$object['shortNum'].'('.$object['mobilePhone'].')':$object['mobilePhone']
				));
			$this->commit_d();
			return $id;
		}catch(exception $e){
			throw $e;
		}
	}

	/*
	 * 网络服务部信息修改
	 */
	function degreeEdit($object){
		try{
			$this->start_d();

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			if(isset($object['technologyCode'])){
				$object ['technologyName'] =$datadictDao->getDataNameByCode ( $object['technologyCode'] );
			}
			if(isset($object['networkCode'])){
				$object ['networkName'] =$datadictDao->getDataNameByCode ( $object['networkCode'] );
			}
			if(isset($object['deviceCode'])){
				$object ['deviceName'] =$datadictDao->getDataNameByCode ( $object['deviceCode'] );
			}
			if(isset($object['outsourcingCode'])){
				$object ['outsourcingName'] =$datadictDao->getDataNameByCode ( $object['outsourcingCode'] );
			}
			$oldObj = $this->get_d ( $object ['id'] );
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object );
			$id=parent::edit_d($object,true);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据用户账号获取信息
	 */
	function getPersonnelInfo_d($userAccount){
		$this->searchArr = array ('userAccount' => $userAccount );
		$personnelRow= $this->listBySqlId ( "select_default" );
		return $personnelRow['0'];
	}

	/**
	 * 根据用户账号获取信息
	 */
	function getPersonnelSimpleInfo_d($userAccount){
		$this->searchArr = array ('userAccount' => $userAccount );
		$personnelRow= $this->listBySqlId ( "select_simple" );
		return $personnelRow['0'];
	}

	/**
	 * 根据用户账号获取信息
	 */
	function getInfoByUserNo_d($userNo){
		$this->searchArr = array ('userNo' => $userNo );
		$this->__SET('sort', 'c.userNo');
		$personnelRow= $this->listBySqlId ( "select_simple" );
		return $personnelRow['0'];
	}

	/**
	 * 根据用户账号判断是否已添加人员档案
	 */
	function isAddPersonnel_d($userAccount){
		$this->searchArr = array ('userNo' => $userAccount );
		$personnelRow= $this->listBySqlId ( "select_simple" );
		if(is_array($personnelRow)){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 根据条件人员档案
	 */
	function selectPersonnel_d($condition){
		$this->searchArr = $condition;
		$personnelRow= $this->listBySqlId ( "select_simple" );
		return $personnelRow;
	}

	/**
	 * 录入部门建议
	 */
	function deptSuggest_d($object){
		//人员部分
		$mainObj = $object['personnel'];
		$mainObj = $this->processDatadict($mainObj);
		//建议部分
		$suggestObj = $object['trialdeptsuggest'];

		//邮件
		if(isset($mainObj['mail'])){
			$emailArr = $mainObj['mail'];
			unset($mainObj['mail']);
		}

		try{
			$this->start_d();
			//更新部门建议
			parent::edit_d($mainObj,true);

			//如果不是辞退，则提交审批单据
			if($mainObj['deptSuggest'] == 'HRBMJY-01' || $mainObj['deptSuggest'] == 'HRBMJY-02'){
				//建议信息加载
				$suggestObj['deptSuggest'] = $mainObj['deptSuggest'];
				$suggestObj['deptSuggestName'] = $mainObj['deptSuggestName'];
				$suggestObj['suggestion'] = $mainObj['suggestion'];
				//部门建议
				$trialdeptsuggestDao = new model_hr_trialplan_trialdeptsuggest();
				$suggestId = $trialdeptsuggestDao->addInPersonnel_d($suggestObj);
			}
			$this->commit_d();

			//邮件发送
			if($emailArr){
				if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
					$this->deptSuggestMail_d($emailArr,$mainObj);
				}
			}

			return $suggestId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 部门建议
	 */
	function deptSuggestMail_d($emailArr,$object){
		$addMsg = $_SESSION['USERNAME'].' 已对 试用员工 【'.$object['userName'].'】录入部门建议 【'.$object['deptSuggestName'] .'】,';
		if($object['deptSuggest'] != 'HRBMJY-03'){
			$addMsg.='请于部门建议信息列表中将相关单据提交审批,';
		}
		$addMsg .= '<br/>建议描述如下：'.$object['suggestion'];

		$emailDao = new model_common_mail();
		$emailDao->mailClear('OA-试用员工部门建议',$emailArr['TO_ID'],$addMsg);
	}

	/**
	 * 邮件配置获取
	 */
	function getMailInfo_d(){

		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = isset($mailUser['deptsuggest']) ? $mailUser['deptsuggest'] : array('sendUserId'=>'',
			'sendName'=>'');
		return $mailArr;
	}

	/**
	 * 更新人员信息
	 * @param1需要更新的人员帐号
	 * @param2需要更新的数组
	 */
	function updatePersonnel_d($userAccount,$object){
		//条件数组
		$conditionArr = array(
			'userAccount' => $userAccount
			);

		try{
			$object = $this->addUpdateInfo($object);
			$this->update($conditionArr,$object);
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * 根据区域发送邮件，并含有附件
	 */
	function sendEmail_d($object){
		try{
			$this->start_d();
			$uploadFile = new model_file_uploadfile_management ();
			$emailDao = new model_common_mail();
			//获取上传的附件
			$files = $uploadFile->getFilesByObjNo ( $object ['mailServiceNo'], 'oa_hr_personnel_email' );
			$fileArr=array();
			if(is_array($files)){
				foreach($files as $key=>$val){
					if(file_exists($val['uploadPath'].$val['newName'])){
                        $fileArr[$val['uploadPath'].$val['newName']] = $val['originalName'];
					}
				}
			}
			//根据条件获取人员信息
			$condition=array('regionName'=>$object['socialPlace'],'employeesState'=>'YGZTZZ');
			$personnelRows=$this->selectPersonnel_d($condition);
			if(is_array($personnelRows)){//发送邮件
				foreach($personnelRows as $k=>$v){
					if($k==0){
						$emailDao->mailWithFile($object ['title'], $v['userAccount'], $object ['content'], $object ['toccMailId'],$fileArr);
					}else{
						$emailDao->mailWithFile($object ['title'], $v['userAccount'], $object ['content'], null,$fileArr);
					}
				}
			}

			$this->commit_d();

			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/********************** 工程使用 **********************/
	/**
	 * 更新人员信息 - 项目信息
	 * create by kuangzw
	 * create on 2012-8-8
	 */
	function updatePersonnelInfo_d($user_id,$activityArr = null,$esmprojectArr = null){
		//条件数组
		$conditionArr = array(
			'userAccount' => $user_id
			);

		//更新数据
		$updateArr = array();
		//加载任务数据
		if($activityArr){
			$updateArr['taskName'] = $activityArr['activityName'];
			$updateArr['taskId'] = $activityArr['id'];
			$updateArr['taskPlanEnd'] = $activityArr['planEndDate'];
		}else{
			$updateArr['taskName'] = '';
			$updateArr['taskId'] = 0;
			$updateArr['taskPlanEnd'] = '0000-00-00';
		}
		//加载任务数据
		if($esmprojectArr){
			$updateArr['projectId'] = $esmprojectArr['id'];
			$updateArr['projectCode'] = $esmprojectArr['projectCode'];
			$updateArr['projectName'] = $esmprojectArr['projectName'];
			$updateArr['planEndDate'] = $esmprojectArr['planEndDate'];
		}else{
			$updateArr['projectId'] = 0;
			$updateArr['projectCode'] = '';
			$updateArr['projectName'] = '';
			$updateArr['planEndDate'] = '0000-00-00';
		}

		if(!empty($updateArr)){
			try{
				$updateArr = $this->addUpdateInfo($updateArr);
				$this->update($conditionArr,$updateArr);
			}catch(exception $e){
				throw $e;
			}
		}
	}

	/**
	 * 根据用户账号获取信息
	 */
	function getPersonnelAndLevel_d($userAccount){
		//获取用户以及等级信息
		$obj = $this->find(array('userAccount' => $userAccount),null,'userAccount,userName,personLevel,personLevelId');
		if($obj['personLevelId']){
			//查询人员等级中匹配的预算等级
			$levelDao = new model_hr_basicinfo_level();
			$levelObj = $levelDao->find('id' > $obj['personLevelId'],null,'esmLevelId');
			if($levelObj['esmLevelId']){
				$epersonDao = new model_engineering_baseinfo_eperson();
				$epersonObj = $epersonDao->find(array('id' => $levelObj['esmLevelId']),null,'price,coefficient');
				if($epersonObj){
					$obj['price'] = $epersonObj['price'];
					$obj['coefficient'] = $epersonObj['coefficient'];
				}
			}
		}
		if(!isset($obj['price'])){
			$obj['price'] = 0;
			$obj['coefficient'] = 0;
		}
		return $obj;
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
		$deptArr = array();//部门数组
		$jobsArr = array();//职位数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		$deptDao = new model_deptuser_dept_dept();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {
				$dataArr = array(); //存取数据数组
				$keyName = array(
					'userNo', //员工编号
					'staffName', //员工姓名
					'englishName', //曾用名（英）
					'sex', //性别
					'birthdate', //出生日期
					'age', //年龄
					'nativePlacePro', //籍贯省
					'nativePlaceCity', //籍贯市
					'nation', //民族
					'identityCard', //身份证号
					'identityCardDate0', //身份证有效日期（开始）
					'identityCardDate1', //身份证有效日期（结束）
					'identityCardAddress', //身份证上地址
					'politicsStatus', //政治面貌
					'highEducationName', //最高学历
					'highSchool', //毕业学校
					'professionalName', //专业
					'graduateDate', //毕业时间
					'companyType', //公司类型
					'companyName', //公司名称
					'belongDeptName', //所属部门
					'jobName', //职位
					'regionName', //区域
					'staffStateName', //员工状态
					'personnelTypeName', //员工类型
					'positionName', //岗位分类
					'personnelClassName', //人员分类
					'wageLevelName', //工资级别
					'jobLevel', //职级
					'functionName', //职能
					'healthState', //健康情况
					'isMedicalHistory', //是否有既往病史
					'medicalHistory', //既往病史
					'InfectDiseases', //传染疾病
					'height', //身高
					'weight', //体重
					'blood', //血型
					'maritalStatusName', //婚育状况
					'birthStatus', //生育状况
					'hobby', //爱好
					'speciality', //特长
					'professional', //专业技能
					'oftenCardNum', //常用卡号
					'oftenAccount', //常用账号
					'oftenBank', //常用开户行
					'bankCardNum', //卡号
					'accountNumb', //账号
					'openingBank', //开户行
					'salaryAreaCode', //工资账号地区代码
					'archivesCode', //档案编号
					'archivesLocation', //档案所在度
					'residencePro', //户籍省
					'residenceCity', //户籍市
					'householdType', //户籍类型
					'collectResidence', //集体户口
					'socialPlace', //社保购买地
					'isNeedTutor', //是否需要导师
					'socialBuyer', //社保购买方
					'fundPlace', //公积金购买地
					'fundBuyer', //公积金购买方
					'fundCardinality', //公积金缴费基数
					'fundProportion', //公积金缴费比例
					'outsourcingSupp', //外包公司
					'outsourcingName', //外包性质
					'personLevel', //技术等级
					'officeName', //归属区域
					'eprovince', //无线补助省份
					'ecity', //无线补助城市
					'technologyName', //技术领域
					'networkName', //网络
					'deviceName' //设备厂家及级别
				);
				foreach ($excelData as $key => $val) {
					if ($key > 1 && !empty($val[0]) && !empty($val[1])) { //非表头且前两个字段不为空
						$data = array();
						foreach ($keyName as $k => $v) {
							$data[$v] = trim($val[$k]);
						}
						array_push($dataArr ,$data);
					}
				}

				//行数组循环
				foreach($dataArr as $key => $val) {
					//新增数组
					$inArr = array();
					$actNum = $key + 1;

					//员工编号
					if(!empty($val['userNo'])){
						$userNo = $this->get_table_fields($this->tbl_name, "userNo='".$val['userNo']."'", 'userNo');
						if(!empty($userNo)) {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!该员工信息已录入</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
						$rs = $otherDataDao->getUserInfoByUserNo($val['userNo']);
						if(!empty($rs)) {
							$userArr[$val['staffName']] = $rs;
							$userName = $this->get_table_fields('user', "USER_ID='".$userArr[$val['staffName']]['USER_ID']."'", 'USER_NAME');//根据用户名获取员工编号
							if($userName != $val['staffName']) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!员工编号与员工姓名不匹配</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
							$inArr['userName'] = $userName;
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!不存在的员工编号</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
						$inArr['userAccount'] = $rs['USER_ID'];
						$inArr['userNo'] = $val['userNo'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<font color=red>导入失败!员工编号为空</font>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//员工姓名
					if(!empty($val['staffName'])) {
						if(!isset($userArr[$val['staffName']])) {
							$rs = $otherDataDao->getUserInfo($val['staffName']);
							if(!empty($rs)) {
								$userArr[$val['staffName']] = $rs;
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的员工名称</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['staffName'] = $val['staffName'];
					} else {
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<font color=red>导入失败!员工姓名为空</font>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//曾用名（英）
					if(!empty($val['englishName'])) {
						$inArr['englishName'] = $val['englishName'];
					}

					//性别
					if(!empty($val['sex']) && ($val['sex'] == '男' || $val['sex'] =='女')) {
						$inArr['sex'] = $val['sex'];
					}

					//出生日期
					if(!empty($val['birthdate']) && $val['birthdate'] != '0000-00-00') {
						if(!is_numeric($val['birthdate'])) {
							$inArr['birthdate'] = $val['birthdate'];
						} else {
							$birthdate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['birthdate'] - 1 ,1900)));
							if($birthdate == '1970-01-01') {
								$tmpDate = date('Y-m-d' ,strtotime($val['birthdate']));
								$inArr['birthdate'] = $tmpDate;
							} else {
								$inArr['birthdate'] = $birthdate;
							}
						}
					}

					//年龄
					if(!empty($val['age'])) {
						$inArr['age'] = $val['age'];
					}

					//籍贯（省）
					if(!empty($val['nativePlacePro'])) {
						$inArr['nativePlacePro'] = $val['nativePlacePro'];
					}

					//籍贯（市）
					if(!empty($val['nativePlaceCity'])) {
						$inArr['nativePlaceCity'] = $val['nativePlaceCity'];
					}

					//民族
					if(!empty($val['nation'])) {
						$inArr['nation'] = $val['nation'];
					}

					//身份证号
					if(!empty($val['identityCard'])) {
						$inArr['identityCard'] = $val['identityCard'];
					}

					//身份证有效日期开始
					if(!empty($val['identityCardDate0']) && $val['identityCardDate0'] != '0000-00-00') {
						$identityCardDate0 = '';
						if(!is_numeric($val['identityCardDate0'])) {
							$identityCardDate0 = str_replace('-' ,'.' ,$val['identityCardDate0']); //转换格式
						} else {
							$identityCardDate0 = date('Y.m.d' ,(mktime(0 ,0 ,0 ,1 ,$val['identityCardDate0'] - 1 ,1900)));
							if($identityCardDate0 == '1970.01.01') {
								$identityCardDate0 = date('Y.m.d' ,strtotime($val['identityCardDate0']));
							}
						}
					}

					//身份证有效日期结束
					if(!empty($val['identityCardDate1']) && $val['identityCardDate1'] != '0000-00-00') {
						$identityCardDate1 = '';
						if(!is_numeric($val['identityCardDate1'])) {
							$identityCardDate1 = str_replace('-' ,'.' ,$val['identityCardDate1']); //转换格式
						} else {
							$identityCardDate1 = date('Y.m.d' ,(mktime(0 ,0 ,0 ,1 ,$val['identityCardDate1'] - 1 ,1900)));
							if($identityCardDate1 == '1970.01.01') {
								$identityCardDate1 = date('Y.m.d' ,strtotime($val['identityCardDate1']));
							}
						}
					}

					//身份证有效日期拼接
					if (!empty($identityCardDate0) && !empty($identityCardDate1)) {
						$inArr['identityCardDate'] = $identityCardDate0.'-'.$identityCardDate1;
					}

					//政治面貌
					if(!empty($val['politicsStatus'])) {
						if(!isset($datadictArr[$val['politicsStatus']])){
							$rs = $datadictDao->getCodeByName('HRZZMM',$val['politicsStatus']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['politicsStatus']]['code'] = $rs;
							} else {
								$incentiveType="";
								$val['politicsStatus']="";
							}
						} else {
							$incentiveType = $datadictArr[$val['politicsStatus']]['code'];
						}
						$inArr['politicsStatusCode'] = $incentiveType;
						$inArr['politicsStatus'] = $val['politicsStatus'];
					}

					//最高学历
					if(!empty($val['highEducationName'])) {
						if(!isset($datadictArr[$val['highEducationName']])){
							$rs = $datadictDao->getCodeByName('HRJYXL',$val['highEducationName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['highEducationName']]['code'] = $rs;
							} else {
								$incentiveType="";
								$val['highEducationName']="";
							}
						} else {
							$incentiveType = $datadictArr[$val['highEducationName']]['code'];
						}
						$inArr['highEducation'] = $incentiveType;
						$inArr['highEducationName'] = $val['highEducationName'];
					}

					//毕业学校
					if(!empty($val['highSchool'])) {
						$inArr['highSchool'] = $val['highSchool'];
					}

					//专业
					if(!empty($val['professionalName'])) {
						$inArr['professionalName'] = $val['professionalName'];
					}

					//毕业时间
					if(!empty($val['graduateDate']) && $val['graduateDate'] != '0000-00-00') {
						if(!is_numeric($val['graduateDate'])) {
							$inArr['graduateDate'] = $val['graduateDate'];
						} else {
							$graduateDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['graduateDate'] - 1 ,1900)));
							if($graduateDate == '1970-01-01') {
								$tmpDate = date('Y-m-d' ,strtotime($val['graduateDate']));
								$inArr['graduateDate'] = $tmpDate;
							} else {
								$inArr['graduateDate'] = $graduateDate;
							}
						}
					}

					//公司类型
					if(!empty($val['companyType'])) {
						if($val['companyType'] == '子公司') {
							$inArr['companyTypeCode'] = 0;
						} else {
							$inArr['companyTypeCode'] = 1;
						}
						$inArr['companyType'] = $val['companyType'];
					}

					//公司名称
					if(!empty($val['companyName'])) {
						$inArr['companyId'] = $this->get_table_fields('branch_info', "NameCN='".$val['companyName']."'", 'ID');
						$inArr['companyName'] = $val['companyName'];
					}

					//所属部门
					if(!empty($val['belongDeptName'])) {
						if(!isset($deptArr[$val['belongDeptName']])) {
							$rs = $otherDataDao->getDeptInfo_d($val['belongDeptName']);
							if(!empty($rs)){
								$deptArr['DEPT_ID'] = $rs['DEPT_ID'];
								$deptArr['Depart_x'] = $rs['Depart_x'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的部门</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['belongDeptName'] = $val['belongDeptName'];
						$inArr['belongDeptId'] = $deptArr['DEPT_ID'];
						$inArr['belongDeptCode'] = $deptArr['Depart_x'];

						$deptRow = $deptDao->getSuperiorDeptById_d( $deptArr['DEPT_ID'],$rs['levelflag']);
						$inArr['deptCode'] = $deptRow['deptCode'];
						$inArr['deptName'] = $deptRow['deptName'];
						$inArr['deptId'] = $deptRow['deptId'];
						//二级部门
						$inArr['deptCodeS'] = $deptRow['deptCodeS'];
						$inArr['deptNameS'] = $deptRow['deptNameS'];
						$inArr['deptIdS'] = $deptRow['deptIdS'];
						//三级部门
						$inArr['deptNameT'] = $deptRow['deptNameT'];
						$inArr['deptCodeT'] = $deptRow['deptCodeT'];
						$inArr['deptIdT'] = $deptRow['deptIdT'];
                        //四级部门
                        $inArr['deptNameF'] = $deptRow['deptNameF'];
                        $inArr['deptCodeF'] = $deptRow['deptCodeF'];
                        $inArr['deptIdF'] = $deptRow['deptIdF'];
					}

					//员工职位
					if(!empty($val['jobName'])) {
						if(!isset($jobsArr[$val['jobName']])) {
							$rs = $otherDataDao->getJobId_d($val['jobName']);
							if(!empty($rs)){
								$jobsArr[$val['jobName']] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的员工职位</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['jobName'] = $val['jobName'];
						$inArr['jobId'] = $jobsArr[$val['jobName']];
					}

					//区域
					if(!empty($val['regionName'])) {
						$inArr['regionId'] = $this->get_table_fields('area', "Name='".$val['regionName']."'", 'ID');
						$inArr['regionName'] = $val['regionName'];
					}

					//员工状态
					if(!empty($val['staffStateName'])) {
						if(!isset($datadictArr[$val['staffStateName']])){
							if($val['staffStateName'] == '辞职' || $val['staffStateName'] == '辞退'
									|| $val['staffStateName'] == '退休' || $val['staffStateName'] == '协商解除'
									|| $val['staffStateName'] == '试用期辞退' || $val['staffStateName'] == '合同到期公司不续'
									|| $val['staffStateName'] == '退休公司不续' || $val['staffStateName'] == '退休员工不续'
									|| $val['staffStateName'] == '合同到期员工不续') {
								$rs = $datadictDao->getCodeByName('YGZTLZ' ,$val['staffStateName']);
								$employeesState = 'YGZTLZ';
								$employeesStateName = '离职';
							} else if ($val['staffStateName'] == '试用' || $val['staffStateName'] == '已转正'
									|| $val['staffStateName'] == '待岗' || $val['staffStateName'] == '试用期'
									|| $val['staffStateName'] == '无试用期' || $val['staffStateName'] == '实习期') {
								$rs = $datadictDao->getCodeByName('YGZTZZ' ,$val['staffStateName']);
								$employeesState = 'YGZTZZ';
								$employeesStateName = '在职';
							}
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['staffStateName']]['code'] = $rs;
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的员工状态</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$incentiveType = $datadictArr[$val['staffStateName']]['code'];
						}
						$inArr['employeesState'] = $employeesState;
						$inArr['employeesStateName'] = $employeesStateName;
						$inArr['staffState'] = $incentiveType;
						$inArr['staffStateName'] = $val['staffStateName'];
					}

					//员工类型
					if(!empty($val['personnelTypeName'])) {
						if(!isset($datadictArr[$val['personnelTypeName']])){
							$rs = $datadictDao->getCodeByName('HRYGLX' ,$val['personnelTypeName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['personnelTypeName']]['code'] = $rs;
							}else{
								$incentiveType="";
								$val['personnelTypeName']="";
							}
						} else {
							$incentiveType = $datadictArr[$val['personnelTypeName']]['code'];
						}
						$inArr['personnelType'] = $incentiveType;
						$inArr['personnelTypeName'] = $val['personnelTypeName'];
					}

					//岗位分类
					if(!empty($val['positionName'])) {
						if(!isset($datadictArr[$val['positionName']])) {
							$rs = $datadictDao->getCodeByName('HRGWFL' ,$val['positionName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['positionName']]['code'] = $rs;
							} else {
								$incentiveType = "";
								$val['positionName'] = "";
							}
						} else {
							$incentiveType = $datadictArr[$val['positionName']]['code'];
						}
						$inArr['position'] = $incentiveType;
						$inArr['positionName'] = $val['positionName'];
					}

					//人员分类
					if(!empty($val['personnelClassName'])) {
						if(!isset($datadictArr[$val['personnelClassName']])){
							$rs = $datadictDao->getCodeByName('HRRYFL' ,$val['personnelClassName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['personnelClassName']]['code'] = $rs;
							} else {
								$incentiveType = "";
								$val['personnelClassName'] = "";
							}
						} else {
							$incentiveType = $datadictArr[$val['personnelClassName']]['code'];
						}
						$inArr['personnelClass'] = $incentiveType;
						$inArr['personnelClassName'] = $val['personnelClassName'];
					}

					//工资级别
					if(!empty($val['wageLevelName'])) {
						if(!isset($datadictArr[$val['wageLevelName']])) {
							$rs = $datadictDao->getCodeByName('HRGZJB',$val['wageLevelName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['wageLevelName']]['code'] = $rs;
							} else {
								$incentiveType="";
								$val['wageLevelName']="";
							}
						} else {
							$incentiveType = $datadictArr[$val['wageLevelName']]['code'];
						}
						$inArr['wageLevelCode'] = $incentiveType;
						$inArr['wageLevelName'] = $val['wageLevelName'];
					}

					//职级
					if(!empty($val['jobLevel'])) {
						$inArr['jobLevel'] = $val['jobLevel'];
					}

					//职能
					if(!empty($val['functionName'])) {
						if(!isset($datadictArr[$val['functionName']])){
							$rs = $datadictDao->getCodeByName('HRYGZN',$val['functionName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['functionName']]['code'] = $rs;
							} else {
								$incentiveType = "";
								$val['functionName'] = "";
							}
						} else {
							$incentiveType = $datadictArr[$val['functionName']]['code'];
						}
						$inArr['functionCode'] = $incentiveType;
						$inArr['functionName'] = $val['functionName'];
					}

					//健康状况
					if(!empty($val['healthState'])) {
						if($val['healthState'] == '良好' || $val['healthState'] == '一般' || $val['healthState'] == '差') {
							$inArr['healthState'] = $val['healthState'];
						}
					}

					//是否有既往病史
					if(!empty($val['isMedicalHistory'])) {
						if($val['isMedicalHistory'] == '是' || $val['isMedicalHistory'] == '否') {
							$inArr['isMedicalHistory'] = $val['isMedicalHistory'];
						}
					}

					//既往病史
					if(!empty($val['medicalHistory'])) {
						$inArr['medicalHistory'] = $val['medicalHistory'];
					}

					//传染疾病
					if(!empty($val['InfectDiseases'])) {
						$inArr['InfectDiseases'] = $val['InfectDiseases'];
					}

					//身高
					if(!empty($val['height'])){
						$inArr['height'] = $val['height'];
					}

					//体重
					if(!empty($val['weight'])) {
						$inArr['weight'] = $val['weight'];
					}

					//血型
					if(!empty($val['blood'])) {
						$inArr['blood'] = $val['blood'];
					}

					//婚姻状况
					if(!empty($val['maritalStatusName'])){
						if($val['maritalStatusName'] == '已婚' || $val['maritalStatusName'] == '未婚') {
							$inArr['maritalStatusName'] = $val['maritalStatusName'];
						}
					}

					//生育状况
					if(!empty($val['birthStatus'])) {
						if($val['birthStatus'] == '已育' || $val['birthStatus'] == '未育') {
							$inArr['birthStatus'] = $val['birthStatus'];
						}
					}

					//爱好
					if(!empty($val['hobby'])) {
						$inArr['hobby'] = $val['hobby'];
					}

					//特长
					if(!empty($val['speciality'])) {
						$inArr['speciality'] = $val['speciality'];
					}

					//专业技能
					if(!empty($val['professional'])) {
						$inArr['professional'] = $val['professional'];
					}

					//常用卡号
					if(!empty($val['oftenCardNum'])) {
						$inArr['oftenCardNum'] = $val['oftenCardNum'];
					}

					//常用账号
					if(!empty($val['oftenAccount'])) {
						$inArr['oftenAccount'] = $val['oftenAccount'];
					}

					//常用开户行
					if(!empty($val['oftenBank'])) {
						$inArr['oftenBank'] = $val['oftenBank'];
					}

					//卡号
					if(!empty($val['bankCardNum'])) {
						$inArr['bankCardNum'] = $val['bankCardNum'];
					}

					//账号
					if(!empty($val['accountNumb'])) {
						$inArr['accountNumb'] = $val['accountNumb'];
					}

					//开户行
					if(!empty($val['openingBank'])) {
						$inArr['openingBank'] = $val['openingBank'];
					}

					//职能
					if(!empty($val['salaryAreaCode'])) {
						if(!isset($datadictArr[$val['salaryAreaCode']])){
							$rs = $datadictDao->getCodeByName('HRGZDQDM' ,$val['salaryAreaCode']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['salaryAreaCode']]['code'] = $rs;
							} else {
								$incentiveType = "";
								$val['salaryAreaCode'] = "";
							}
						} else {
							$incentiveType = $datadictArr[$val['salaryAreaCode']]['code'];
						}
						$inArr['salaryAreaTypeCode'] = $incentiveType;
						$inArr['salaryAreaCode'] = $val['salaryAreaCode'];
					}

					//档案编号
					if(!empty($val['archivesCode'])) {
						$inArr['archivesCode'] = $val['archivesCode'];
					}

					//档案所在地
					if(!empty($val['archivesLocation'])) {
						$inArr['archivesLocation'] = $val['archivesLocation'];
					}

					//户籍地(省)
					if(!empty($val['residencePro'])) {
						$inArr['residencePro'] = $val['residencePro'];
					}

					//户籍地(市)
					if(!empty($val['residenceCity'])) {
						$inArr['residenceCity'] = $val['residenceCity'];
					}

					//户籍类型
					if(!empty($val['householdType'])) {
						if($val['householdType'] == '城镇' || $val['householdType'] == '农业') {
							$inArr['householdType'] = $val['householdType'];
						}
					}

					//集体户口
					if(!empty($val['collectResidence'])) {
						if($val['collectResidence'] == '是' || $val['collectResidence'] == '否') {
							$inArr['collectResidence'] = $val['collectResidence'];
						}
					}

					//社保购买地
					if(!empty($val['socialPlace'])) {
						$inArr['socialPlace'] = $val['socialPlace'];
					}

					//是否需要导师
					if(!empty($val['isNeedTutor'])) {
						if($val['isNeedTutor'] == '是') {
							$inArr['isNeedTutor'] = '1';
						}
					}

					//技术等级
					if(!empty($val['personLevel'])) {
						$personLevelId = $this->get_table_fields('oa_hr_level', "personLevel='".$val['personLevel']."'", 'id');
						if($personLevelId) {
							$inArr['personLevelId'] = $personLevelId;
						} else {
							$inArr['personLevelId'] ='';
						}
						$inArr['personLevel'] = $val['personLevel'];
					}

					$newId = parent::add_d($inArr ,true);
					if($newId) {
						$tempArr['result'] = '导入成功';
					} else {
						$tempArr['result'] = '导入失败';
					}
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					array_push($resultArr ,$tempArr);
				}
				return $resultArr;
			}
		}
	}

	function editExecelData_d(){
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
		$deptArr = array();//部门数组
		$jobsArr = array();//职位数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		$logSettringDao = new model_syslog_setting_logsetting ();
		$deptDao=new model_deptuser_dept_dept();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");

			if(is_array($excelData)) {

				$dataArr = array(); //存取数据数组
				$keyName = array(
					'userNo', //员工编号
					'staffName', //员工姓名
					'englishName', //曾用名（英）
					'sex', //性别
					'birthdate', //出生日期
					'age', //年龄
					'nativePlacePro', //籍贯省
					'nativePlaceCity', //籍贯市
					'nation', //民族
					'identityCard', //身份证号
					'identityCardDate0', //身份证有效日期（开始）
					'identityCardDate1', //身份证有效日期（结束）
					'identityCardAddress', //身份证上地址
					'politicsStatus', //政治面貌
					'highEducationName', //最高学历
					'highSchool', //毕业学校
					'professionalName', //专业
					'graduateDate', //毕业时间
					'companyType', //公司类型
					'companyName', //公司名称
					'belongDeptName', //所属部门
					'jobName', //职位
					'regionName', //区域
					'staffStateName', //员工状态
					'personnelTypeName', //员工类型
					'positionName', //岗位分类
					'personnelClassName', //人员分类
					'wageLevelName', //工资级别
					'jobLevel', //职级
					'functionName', //职能
					'healthState', //健康情况
					'isMedicalHistory', //是否有既往病史
					'medicalHistory', //既往病史
					'InfectDiseases', //传染疾病
					'height', //身高
					'weight', //体重
					'blood', //血型
					'maritalStatusName', //婚育状况
					'birthStatus', //生育状况
					'hobby', //爱好
					'speciality', //特长
					'professional', //专业技能
					'oftenCardNum', //常用卡号
					'oftenAccount', //常用账号
					'oftenBank', //常用开户行
					'bankCardNum', //卡号
					'accountNumb', //账号
					'openingBank', //开户行
					'salaryAreaCode', //工资账号地区代码
					'archivesCode', //档案编号
					'archivesLocation', //档案所在度
					'residencePro', //户籍省
					'residenceCity', //户籍市
					'householdType', //户籍类型
					'collectResidence', //集体户口
					'socialPlace', //社保购买地
					'isNeedTutor', //是否需要导师
					'socialBuyer', //社保购买方
					'fundPlace', //公积金购买地
					'fundBuyer', //公积金购买方
					'fundCardinality', //公积金缴费基数
					'fundProportion', //公积金缴费比例
					'outsourcingSupp', //外包公司
					'outsourcingName', //外包性质
					'personLevel', //技术等级
					'officeName', //归属区域
					'eprovince', //无线补助省份
					'ecity', //无线补助城市
					'technologyName', //技术领域
					'networkName', //网络
					'deviceName' //设备厂家及级别
				);
				foreach ($excelData as $key => $val) {
					if ($key > 1 && !empty($val[0])) { //非表头且前两个字段不为空
						$data = array();
						foreach ($keyName as $k => $v) {
							$data[$v] = trim($val[$k]);
						}
						array_push($dataArr ,$data);
					}
				}

				//行数组循环
				foreach($dataArr as $key => $val){
					$inArr = array(); //新增数组
					$actNum = $key + 1;

					//员工编号
					if(!empty($val['userNo'])) {
						$id = $this->get_table_fields($this->tbl_name, "userNo='".$val['userNo']."'", 'id');
						if(!$id > 0) {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!该员工档案信息不存在</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
						$inArr['id'] = $id;
					} else {
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<font color=red>导入失败!员工编号为空</font>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//员工姓名
					if(!empty($val['staffName'])) {
						$staffName = $this->get_table_fields($this->tbl_name ,"userNo='".$val['userNo']."'" ,'staffName');
						$userName = $this->get_table_fields($this->tbl_name ,"userNo='".$val['userNo']."'" ,'userName');
						if($val['staffName'] != trim($staffName) && $val['staffName'] != trim($userName)) {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!员工姓名不匹配</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
						$inArr['staffName'] = $val['staffName'];
					}

					//曾用名（英）
					if(!empty($val['englishName'])) {
						$inArr['englishName'] = $val['englishName'];
					}

					//性别
					if(!empty($val['sex']) && ($val['sex'] == '男' || $val['sex'] == '女')) {
						$inArr['sex'] = $val['sex'];
					}

					//出生日期
					if(!empty($val['birthdate']) && $val['birthdate'] != '0000-00-00') {
						if(!is_numeric($val['birthdate'])) {
							$inArr['birthdate'] = $val['birthdate'];
						} else {
							$birthdate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['birthdate'] - 1 ,1900)));
							if($birthdate == '1970-01-01') {
								$tmpDate = date('Y-m-d' ,strtotime($val['birthdate']));
								$inArr['birthdate'] = $tmpDate;
							} else {
								$inArr['birthdate'] = $birthdate;
							}
						}
					}

					//年龄
					if(!empty($val['age'])) {
						$inArr['age'] = $val['age'];
					}

					//籍贯（省）
					if(!empty($val['nativePlacePro'])) {
						$inArr['nativePlacePro'] = $val['nativePlacePro'];
					}

					//籍贯（市）
					if(!empty($val['nativePlaceCity'])) {
						$inArr['nativePlaceCity'] = $val['nativePlaceCity'];
					}

					//民族
					if(!empty($val['nation'])) {
						$inArr['nation'] = $val['nation'];
					}

					//身份证号
					if(!empty($val['identityCard'])) {
						$inArr['identityCard'] = $val['identityCard'];
					}

					//身份证有效日期开始
					if(!empty($val['identityCardDate0']) && $val['identityCardDate0'] != '0000-00-00') {
						$identityCardDate0 = '';
						if(!is_numeric($val['identityCardDate0'])) {
							$identityCardDate0 = str_replace('-' ,'.' ,$val['identityCardDate0']); //转换格式
						} else {
							$identityCardDate0 = date('Y.m.d' ,(mktime(0 ,0 ,0 ,1 ,$val['identityCardDate0'] - 1 ,1900)));
							if($identityCardDate0 == '1970.01.01') {
								$identityCardDate0 = date('Y.m.d' ,strtotime($val['identityCardDate0']));
							}
						}
					}

					//身份证有效日期结束
					if(!empty($val['identityCardDate1']) && $val['identityCardDate1'] != '0000-00-00') {
						$identityCardDate1 = '';
						if(!is_numeric($val['identityCardDate1'])) {
							$identityCardDate1 = str_replace('-' ,'.' ,$val['identityCardDate1']); //转换格式
						} else {
							$identityCardDate1 = date('Y.m.d' ,(mktime(0 ,0 ,0 ,1 ,$val['identityCardDate1'] - 1 ,1900)));
							if($identityCardDate1 == '1970.01.01') {
								$identityCardDate1 = date('Y.m.d' ,strtotime($val['identityCardDate1']));
							}
						}
					}

					//身份证有效日期拼接
					if (!empty($identityCardDate0) && !empty($identityCardDate1)) {
						$inArr['identityCardDate'] = $identityCardDate0.'-'.$identityCardDate1;
					}

					//政治面貌
					if(!empty($val['politicsStatus'])) {
						if(!isset($datadictArr[$val['politicsStatus']])){
							$rs = $datadictDao->getCodeByName('HRZZMM',$val['politicsStatus']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['politicsStatus']]['code'] = $rs;
							} else {
								$incentiveType="";
								$val['politicsStatus']="";
							}
						} else {
							$incentiveType = $datadictArr[$val['politicsStatus']]['code'];
						}
						$inArr['politicsStatusCode'] = $incentiveType;
						$inArr['politicsStatus'] = $val['politicsStatus'];
					}

					//最高学历
					if(!empty($val['highEducationName'])) {
						if(!isset($datadictArr[$val['highEducationName']])){
							$rs = $datadictDao->getCodeByName('HRJYXL',$val['highEducationName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['highEducationName']]['code'] = $rs;
							} else {
								$incentiveType="";
								$val['highEducationName']="";
							}
						} else {
							$incentiveType = $datadictArr[$val['highEducationName']]['code'];
						}
						$inArr['highEducation'] = $incentiveType;
						$inArr['highEducationName'] = $val['highEducationName'];
					}

					//毕业学校
					if(!empty($val['highSchool'])) {
						$inArr['highSchool'] = $val['highSchool'];
					}

					//专业
					if(!empty($val['professionalName'])) {
						$inArr['professionalName'] = $val['professionalName'];
					}

					//毕业时间
					if(!empty($val['graduateDate']) && $val['graduateDate'] != '0000-00-00') {
						if(!is_numeric($val['graduateDate'])) {
							$inArr['graduateDate'] = $val['graduateDate'];
						} else {
							$graduateDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['graduateDate'] - 1 ,1900)));
							if($graduateDate == '1970-01-01') {
								$tmpDate = date('Y-m-d' ,strtotime($val['graduateDate']));
								$inArr['graduateDate'] = $tmpDate;
							} else {
								$inArr['graduateDate'] = $graduateDate;
							}
						}
					}

					//公司类型
					if(!empty($val['companyType'])) {
						if($val['companyType'] == '子公司') {
							$inArr['companyTypeCode'] = 0;
						} else {
							$inArr['companyTypeCode'] = 1;
						}
						$inArr['companyType'] = $val['companyType'];
					}

					//公司名称
					if(!empty($val['companyName'])) {
						$inArr['companyId'] = $this->get_table_fields('branch_info', "NameCN='".$val['companyName']."'", 'ID');
						$inArr['companyName'] = $val['companyName'];
					}

					//所属部门
					if(!empty($val['belongDeptName'])) {
						if(!isset($deptArr[$val['belongDeptName']])) {
							$rs = $otherDataDao->getDeptInfo_d($val['belongDeptName']);
							if(!empty($rs)){
								$deptArr['DEPT_ID'] = $rs['DEPT_ID'];
								$deptArr['Depart_x'] = $rs['Depart_x'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的部门</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['belongDeptName'] = $val['belongDeptName'];
						$inArr['belongDeptId'] = $deptArr['DEPT_ID'];
						$inArr['belongDeptCode'] = $deptArr['Depart_x'];

						$deptRow = $deptDao->getSuperiorDeptById_d( $deptArr['DEPT_ID'],$rs['levelflag']);
						$inArr['deptCode'] = $deptRow['deptCode'];
						$inArr['deptName'] = $deptRow['deptName'];
						$inArr['deptId'] = $deptRow['deptId'];
						//二级部门
						$inArr['deptCodeS'] = $deptRow['deptCodeS'];
						$inArr['deptNameS'] = $deptRow['deptNameS'];
						$inArr['deptIdS'] = $deptRow['deptIdS'];
						//三级部门
						$inArr['deptNameT'] = $deptRow['deptNameT'];
						$inArr['deptCodeT'] = $deptRow['deptCodeT'];
						$inArr['deptIdT'] = $deptRow['deptIdT'];
                        //四级部门
                        $inArr['deptNameF'] = $deptRow['deptNameF'];
                        $inArr['deptCodeF'] = $deptRow['deptCodeF'];
                        $inArr['deptIdF'] = $deptRow['deptIdF'];
                        if(empty($val['jobName'])){
                            $val['jobName'] = $this->get_table_fields($this->tbl_name, "userNo='".$val['userNo']."'", 'jobName');
                        }
					}

					//员工职位
					if(!empty($val['jobName'])) {
						if(!isset($jobsArr[$val['jobName']])) {
                            if(isset($inArr['belongDeptId'])&&!empty($inArr['belongDeptId'])){
                                $rs = $otherDataDao->getJobIdByJobName_d($val['jobName'],$inArr['belongDeptId']);
                            }else{
                                $deptId = $this->get_table_fields($this->tbl_name, "userNo='".$val['userNo']."'", 'belongDeptId');
                                $rs = $otherDataDao->getJobIdByJobName_d($val['jobName'],$deptId);
                            }
							if(!empty($rs)){
								$jobsArr[$val['jobName']] = $rs;
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!该部门不存在此职位</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['jobName'] = $val['jobName'];
						$inArr['jobId'] = $jobsArr[$val['jobName']];
					}

					//区域
					if(!empty($val['regionName'])) {
						$inArr['regionId'] = $this->get_table_fields('area', "Name='".$val['regionName']."'", 'ID');
						$inArr['regionName'] = $val['regionName'];
					}

					//员工状态
					if(!empty($val['staffStateName'])) {
						if(!isset($datadictArr[$val['staffStateName']])){
							if($val['staffStateName'] == '辞职' || $val['staffStateName'] == '辞退'
									|| $val['staffStateName'] == '退休' || $val['staffStateName'] == '协商解除'
									|| $val['staffStateName'] == '试用期辞退' || $val['staffStateName'] == '合同到期公司不续'
									|| $val['staffStateName'] == '退休公司不续' || $val['staffStateName'] == '退休员工不续'
									|| $val['staffStateName'] == '合同到期员工不续') {
								$rs = $datadictDao->getCodeByName('YGZTLZ' ,$val['staffStateName']);
								$employeesState = 'YGZTLZ';
								$employeesStateName = '离职';
							} else if ($val['staffStateName'] == '试用' || $val['staffStateName'] == '已转正'
									|| $val['staffStateName'] == '待岗' || $val['staffStateName'] == '试用期'
									|| $val['staffStateName'] == '无试用期' || $val['staffStateName'] == '实习期') {
								$rs = $datadictDao->getCodeByName('YGZTZZ' ,$val['staffStateName']);
								$employeesState = 'YGZTZZ';
								$employeesStateName = '在职';
							}
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['staffStateName']]['code'] = $rs;
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!不存在的员工状态</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$incentiveType = $datadictArr[$val['staffStateName']]['code'];
						}
						$inArr['employeesState'] = $employeesState;
						$inArr['employeesStateName'] = $employeesStateName;
						$inArr['staffState'] = $incentiveType;
						$inArr['staffStateName'] = $val['staffStateName'];
					}

					//员工类型
					if(!empty($val['personnelTypeName'])) {
						if(!isset($datadictArr[$val['personnelTypeName']])){
							$rs = $datadictDao->getCodeByName('HRYGLX' ,$val['personnelTypeName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['personnelTypeName']]['code'] = $rs;
							}else{
								$incentiveType="";
								$val['personnelTypeName']="";
							}
						} else {
							$incentiveType = $datadictArr[$val['personnelTypeName']]['code'];
						}
						$inArr['personnelType'] = $incentiveType;
						$inArr['personnelTypeName'] = $val['personnelTypeName'];
					}

					//岗位分类
					if(!empty($val['positionName'])) {
						if(!isset($datadictArr[$val['positionName']])) {
							$rs = $datadictDao->getCodeByName('HRGWFL' ,$val['positionName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['positionName']]['code'] = $rs;
							} else {
								$incentiveType = "";
								$val['positionName'] = "";
							}
						} else {
							$incentiveType = $datadictArr[$val['positionName']]['code'];
						}
						$inArr['position'] = $incentiveType;
						$inArr['positionName'] = $val['positionName'];
					}

					//人员分类
					if(!empty($val['personnelClassName'])) {
						if(!isset($datadictArr[$val['personnelClassName']])){
							$rs = $datadictDao->getCodeByName('HRRYFL' ,$val['personnelClassName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['personnelClassName']]['code'] = $rs;
							} else {
								$incentiveType = "";
								$val['personnelClassName'] = "";
							}
						} else {
							$incentiveType = $datadictArr[$val['personnelClassName']]['code'];
						}
						$inArr['personnelClass'] = $incentiveType;
						$inArr['personnelClassName'] = $val['personnelClassName'];
					}

					//工资级别
					if(!empty($val['wageLevelName'])) {
						if(!isset($datadictArr[$val['wageLevelName']])) {
							$rs = $datadictDao->getCodeByName('HRGZJB',$val['wageLevelName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['wageLevelName']]['code'] = $rs;
							} else {
								$incentiveType="";
								$val['wageLevelName']="";
							}
						} else {
							$incentiveType = $datadictArr[$val['wageLevelName']]['code'];
						}
						$inArr['wageLevelCode'] = $incentiveType;
						$inArr['wageLevelName'] = $val['wageLevelName'];
					}

					//职级
					if(!empty($val['jobLevel'])) {
						$inArr['jobLevel'] = $val['jobLevel'];
					}

					//职能
					if(!empty($val['functionName'])) {
						if(!isset($datadictArr[$val['functionName']])){
							$rs = $datadictDao->getCodeByName('HRYGZN',$val['functionName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['functionName']]['code'] = $rs;
							} else {
								$incentiveType = "";
								$val['functionName'] = "";
							}
						} else {
							$incentiveType = $datadictArr[$val['functionName']]['code'];
						}
						$inArr['functionCode'] = $incentiveType;
						$inArr['functionName'] = $val['functionName'];
					}

					//健康状况
					if(!empty($val['healthState'])) {
						if($val['healthState'] == '良好' || $val['healthState'] == '一般' || $val['healthState'] == '差') {
							$inArr['healthState'] = $val['healthState'];
						}
					}

					//是否有既往病史
					if(!empty($val['isMedicalHistory'])) {
						if($val['isMedicalHistory'] == '是' || $val['isMedicalHistory'] == '否') {
							$inArr['isMedicalHistory'] = $val['isMedicalHistory'];
						}
					}

					//既往病史
					if(!empty($val['medicalHistory'])) {
						$inArr['medicalHistory'] = $val['medicalHistory'];
					}

					//传染疾病
					if(!empty($val['InfectDiseases'])) {
						$inArr['InfectDiseases'] = $val['InfectDiseases'];
					}

					//身高
					if(!empty($val['height'])){
						$inArr['height'] = $val['height'];
					}

					//体重
					if(!empty($val['weight'])) {
						$inArr['weight'] = $val['weight'];
					}

					//血型
					if(!empty($val['blood'])) {
						$inArr['blood'] = $val['blood'];
					}

					//婚姻状况
					if(!empty($val['maritalStatusName'])){
						if($val['maritalStatusName'] == '已婚' || $val['maritalStatusName'] == '未婚') {
							$inArr['maritalStatusName'] = $val['maritalStatusName'];
						}
					}

					//生育状况
					if(!empty($val['birthStatus'])) {
						if($val['birthStatus'] == '已育' || $val['birthStatus'] == '未育') {
							$inArr['birthStatus'] = $val['birthStatus'];
						}
					}

					//爱好
					if(!empty($val['hobby'])) {
						$inArr['hobby'] = $val['hobby'];
					}

					//特长
					if(!empty($val['speciality'])) {
						$inArr['speciality'] = $val['speciality'];
					}

					//专业技能
					if(!empty($val['professional'])) {
						$inArr['professional'] = $val['professional'];
					}

					//常用卡号
					if(!empty($val['oftenCardNum'])) {
						$inArr['oftenCardNum'] = $val['oftenCardNum'];
					}

					//常用账号
					if(!empty($val['oftenAccount'])) {
						$inArr['oftenAccount'] = $val['oftenAccount'];
					}

					//常用开户行
					if(!empty($val['oftenBank'])) {
						$inArr['oftenBank'] = $val['oftenBank'];
					}

					//卡号
					if(!empty($val['bankCardNum'])) {
						$inArr['bankCardNum'] = $val['bankCardNum'];
					}

					//账号
					if(!empty($val['accountNumb'])) {
						$inArr['accountNumb'] = $val['accountNumb'];
					}

					//开户行
					if(!empty($val['openingBank'])) {
						$inArr['openingBank'] = $val['openingBank'];
					}

					//职能
					if(!empty($val['salaryAreaCode'])) {
						if(!isset($datadictArr[$val['salaryAreaCode']])){
							$rs = $datadictDao->getCodeByName('HRGZDQDM' ,$val['salaryAreaCode']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['salaryAreaCode']]['code'] = $rs;
							} else {
								$incentiveType = "";
								$val['salaryAreaCode'] = "";
							}
						} else {
							$incentiveType = $datadictArr[$val['salaryAreaCode']]['code'];
						}
						$inArr['salaryAreaTypeCode'] = $incentiveType;
						$inArr['salaryAreaCode'] = $val['salaryAreaCode'];
					}

					//档案编号
					if(!empty($val['archivesCode'])) {
						$inArr['archivesCode'] = $val['archivesCode'];
					}

					//档案所在地
					if(!empty($val['archivesLocation'])) {
						$inArr['archivesLocation'] = $val['archivesLocation'];
					}

					//户籍地(省)
					if(!empty($val['residencePro'])) {
						$inArr['residencePro'] = $val['residencePro'];
					}

					//户籍地(市)
					if(!empty($val['residenceCity'])) {
						$inArr['residenceCity'] = $val['residenceCity'];
					}

					//户籍类型
					if(!empty($val['householdType'])) {
						if($val['householdType'] == '城镇' || $val['householdType'] == '农业') {
							$inArr['householdType'] = $val['householdType'];
						}
					}

					//集体户口
					if(!empty($val['collectResidence'])) {
						if($val['collectResidence'] == '是' || $val['collectResidence'] == '否') {
							$inArr['collectResidence'] = $val['collectResidence'];
						}
					}

					//社保购买地
					if(!empty($val['socialPlace'])) {
						$inArr['socialPlace'] = $val['socialPlace'];
					}

					//是否需要导师
					if(!empty($val['isNeedTutor'])) {
						if($val['isNeedTutor'] == '是') {
							$inArr['isNeedTutor'] = '1';
						}
					}

					//社保购买方
					if(!empty($val['socialBuyer'])) {
						$inArr['socialBuyer'] = $val['socialBuyer'];
					}

					//公积金购买地
					if(!empty($val['fundPlace'])) {
						$inArr['fundPlace'] = $val['fundPlace'];
					}

					//公积金购买方
					if(!empty($val['fundBuyer'])) {
						$inArr['fundBuyer'] = $val['fundBuyer'];
					}

					//公积金缴费基数
					if(!empty($val['fundCardinality'])) {
						$inArr['fundCardinality'] = $val['fundCardinality'];
					}

					//公积金缴费比例
					if(!empty($val['fundProportion'])) {
						$inArr['fundProportion'] = $val['fundProportion'];
					}

					//外包公司
					if(!empty($val['fundProportion'])) {
						$inArr['outsourcingSupp'] = $val['fundProportion'];
					}

					//外包性质
					if(!empty($val['outsourcingName'])) {
						if(!isset($datadictArr[$val['outsourcingName']])) {
							$rs = $datadictDao->getCodeByName('HTWBFS',$val['outsourcingName']);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['outsourcingName']]['code'] = $rs;
							} else {
								$incentiveType = "";
							}
						} else {
							$incentiveType = $datadictArr[$val['outsourcingName']]['code'];
						}
						$inArr['outsourcingCode'] = $incentiveType;
						$inArr['outsourcingName'] = $val['outsourcingName'];
					}

					//技术等级
					if(!empty($val['personLevel'])) {
						$personLevelId = $this->get_table_fields('oa_hr_level', "personLevel='".$val['personLevel']."'", 'id');
						if($personLevelId) {
							$inArr['personLevelId'] = $personLevelId;
						} else {
							$inArr['personLevelId'] ='';
						}
						$inArr['personLevel'] = $val['personLevel'];
					}

					//归属区域
					if(!empty($val['officeName'])) {
						$personLevelId = $this->get_table_fields('oa_esm_office_baseinfo', "officeName='".$val['officeName']."'", 'id');
						if($personLevelId){
							$inArr['officeId'] = $personLevelId;
						} else {
							$inArr['officeId'] = '';
						}
						$inArr['officeName'] = $val['officeName'];
					}

					//无补助城市(省)
					if(!empty($val['eprovince'])) {
						$eprovinceId = $this->get_table_fields('oa_system_province_info', "provinceName='".$val['eprovince']."'", 'id');
						if($eprovinceId) {
							$inArr['eprovinceId'] = $eprovinceId;
						} else {
							$inArr['eprovinceId'] ='';
						}
						$inArr['eprovince'] = $val['eprovince'];
					}

					//无补助城市(市)
					if(!empty($val['ecity'])) {
						$ecityId = $this->get_table_fields('oa_system_city_info', "cityName='".$val['ecity']."'", 'id');
						if($ecityId){
							$inArr['ecityId'] = $ecityId;
						} else {
							$inArr['ecityId'] ='';
						}
						$inArr['ecity'] = $val['ecity'];
					}

					//技术领域
					if(!empty($val['technologyName'])) {
						if(!isset($datadictArr[$val['technologyName']])) {
							$rs = $datadictDao->getCodeByName('HRJSLY' ,$val['technologyName']);
							if(!empty($rs)) {
								$technologyCode = $datadictArr[$val['technologyName']]['code'] = $rs;
							} else {
								$technologyCode="";
							}
						} else {
							$technologyCode = $datadictArr[$val['technologyName']]['code'];
						}
						$inArr['technologyCode'] = $technologyCode;
						$inArr['technologyName'] = $val['technologyName'];
					}

					//网络
					if(!empty($val['networkName'])){
						if(!isset($datadictArr[$val['networkName']])){
							$rs = $datadictDao->getCodeByName('HRFWWL',$val['networkName']);
							if(!empty($rs)){
								$networkCode = $datadictArr[$val['networkName']]['code'] = $rs;
							} else {
								$networkCode = "";
							}
						} else {
							$networkCode = $datadictArr[$val['networkName']]['code'];
						}
						$inArr['networkCode'] = $networkCode;
						$inArr['networkName'] = $val['networkName'];
					}

					//设备厂家及级别
					if(!empty($val['deviceName'])) {
						if(!isset($datadictArr[$val['deviceName']])){
							$rs = $datadictDao->getCodeByName('HRSBDJ' ,$val['deviceName']);
							if(!empty($rs)) {
								$deviceCode = $datadictArr[$val['deviceName']]['code'] = $rs;
							} else {
								$deviceCode="";
							}
						} else {
							$deviceCode = $datadictArr[$val['deviceName']]['code'];
						}
						$inArr['deviceCode'] = $deviceCode;
						$inArr['deviceName'] = $val['deviceName'];
					}

					//技术领域
					if(!empty($val['technologyName'])) {
						if(!isset($datadictArr[$val['technologyName']])){
							$rs = $datadictDao->getCodeByName('HRJSLY' ,$val['technologyName']);
							if(!empty($rs)){
								$technologyCode = $datadictArr[$val['technologyName']]['code'] = $rs;
							} else {
								$technologyCode = "";
							}
						} else {
							$itechnologyCode = $datadictArr[$val['technologyName']]['code'];
						}
						$inArr['technologyCode'] = $technologyCode;
						$inArr['technologyName'] = $val['technologyName'];
					}

					$oldObj = $this->get_d ( $inArr ['id'] );
					$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $inArr );

					$newId = parent::edit_d($inArr ,true);
					//同步更新旧系统中的档案信息
					$this->updateOldInfo_d($inArr['id'],'edit');
					if($newId) {
						$tempArr['result'] = '导入成功';
					} else {
						$tempArr['result'] = '导入失败';
					}
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					array_push($resultArr ,$tempArr);
				}
				return $resultArr;
			}
		}
	}

	/**
	 * 导入联系信息
	 */
	function addContactExecelData_d(){
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
		$deptArr = array();//部门数组
		$jobsArr = array();//职位数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$im = new includes_class_ImInterface();
		$logSettringDao = new model_syslog_setting_logsetting ();
//		$datadictArr = array();//数据字典数组
//		$datadictDao = new model_system_datadict_datadict();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//行数组循环
				foreach($excelData as $key => $val){
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1])){
						continue;
					}else{
						//新增数组
						$inArr = array();

						//员工编号
						if(!empty($val[0])&&trim($val[0])!=''){
							$val[0]=trim($val[0]);
							if(!isset($userArr[$val[0]])){
								$rs=$this->get_table_fields($this->tbl_name, "userNo='".$val[0]."'", 'id');
								if(!empty($rs)&&$rs>0){
									$inArr['id'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!不存在的员工编号或该员工未进行基本信息的录入</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
//							$inArr['userNo'] = $val[0];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!员工编号为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//员工姓名
						if(!empty($val[1])&&trim($val[1])!=''){
							if(!isset($userArr[$val[1]])){
								$rs = $otherDataDao->getUserInfo($val[1]);
								if(!empty($rs)){
									$userArr[$val[1]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!不存在的员工名称</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}

//							$inArr['userAccount'] = $userArr[$val[1]]['USER_ID'];
//							$inArr['userName'] = $val[1];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!没有员工姓名</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//固定电话
						if(!empty($val[2])&&trim($val[2])!=''){
							$inArr['telephone'] = trim( $val[2]);
						}

						//移动电话
						if(!empty($val[3])&&trim($val[3])!=''){
							$inArr['mobile'] = trim( $val[3]);
						}

						//个人邮箱
						if(!empty($val[4])&&trim($val[4])!=''){
							$inArr['personEmail'] = trim( $val[4]);
						}

						//公司邮箱
						if(!empty($val[5])&&trim($val[5])!=''){
							$inArr['compEmail'] =trim( $val[5]);
						}


						//QQ
						if(!empty($val[6])&&trim($val[6])!=''){
							$inArr['QQ'] = trim( $val[6]);
						}

						//MSN
						if(!empty($val[7])&&trim($val[7])!=''){
							$inArr['MSN'] = trim( $val[7]);
						}

						//飞信
						if(!empty($val[8])&&trim($val[8])!=''){
							$inArr['fetion'] = trim( $val[8]);
						}

						//其他联系方式
						if(!empty($val[9])&&trim($val[9])!=''){
							$inArr['information'] =trim( $val[9]);
						}


						//家庭电话
						if(!empty($val[10])&&trim($val[10])!=''){
							$inArr['homePhone'] = trim( $val[10]);
						}

						//紧急联系人
						if(!empty($val[11])&&trim($val[11])!=''){
							$inArr['emergencyName'] = trim( $val[11]);
						}

						//紧急联系人电话
						if(!empty($val[12])&&trim($val[12])!=''){
							$inArr['emergencyTel'] = trim( $val[12]);
						}

						//关系
						if(!empty($val[13])&&trim($val[13])!=''){
							$inArr['emergencyRelation'] = trim( $val[13]);
						}

						//现住地址省
						if(!empty($val[14])&&trim($val[14])!=''){
							$inArr['nowPlacePro'] = trim( $val[14]);
						}


						//现住地址市
						if(!empty($val[15])&&trim($val[15])!=''){
							$inArr['nowPlaceCity'] = trim( $val[15]);
						}

						//现住地址详细地址
						if(!empty($val[16])&&trim($val[16])!=''){
							$inArr['nowAddress'] = trim( $val[16]);
						}

						//现住地址邮政编码
						if(!empty($val[17])&&trim($val[17])!=''){
							$inArr['nowPost'] = trim( $val[17]);
						}

						//家庭详细地址省
						if(!empty($val[18])&&trim($val[18])!=''){
							$inArr['homeAddressPro'] = trim( $val[18]);
						}


						//家庭详细地址市
						if(!empty($val[19])&&trim($val[19])!=''){
							$inArr['homeAddressCity'] = trim( $val[19]);
						}

						//家庭详细地址详细地址
						if(!empty($val[20])&&trim($val[20])!=''){
							$inArr['homeAddress'] = trim( $val[20]);
						}

						//家庭详细地址邮政编码
						if(!empty($val[21])&&trim($val[21])!=''){
							$inArr['homePost'] =trim( $val[21]);
						}

						//单位联系方式单位电话
						if(!empty($val[22])&&trim($val[22])!=''){
							$inArr['unitPhone'] =trim( $val[22]);
						}

						//单位联系方式分机号
						if(!empty($val[23])&&trim($val[23])!=''){
							$inArr['extensionNum'] =trim( $val[23]);
						}

						//单位联系方式单位传真
						if(!empty($val[24])&&trim($val[24])!=''){
							$inArr['unitFax'] =trim( $val[24]);
						}

						//单位联系方式手机
						if(!empty($val[25])&&trim($val[25])!=''){
							$inArr['mobilePhone'] =trim( $val[25]);
						}

						//单位联系方式短号
						if(!empty($val[26])&&trim($val[26])!=''){
							$inArr['shortNum'] =trim( $val[26]);
						}

						//单位联系方式其他手机
						if(!empty($val[27])&&trim($val[27])!=''){
							$inArr['otherPhone'] =trim( $val[27]);
						}

						//单位联系方式其他号码
						if(!empty($val[28])&&trim($val[28])!=''){
							$inArr['otherPhoneNum'] =trim( $val[28]);
						}

//						print_r($inArr);
						$oldObj = $this->get_d ( $inArr ['id'] );
						$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $inArr );
						$newId = parent::edit_d($inArr,true);
						if($newId){
							//更新通讯录
							$LOG_NAME=$this->get_table_fields('user', "USER_ID='".$oldObj['userAccount']."'", 'LogName');
							$im->edit_userInfo($LOG_NAME, array (
								'Col_o_Phone' => $inArr['extensionNum']?$inArr['extensionNum'].'('.$inArr['unitPhone'].')':$inArr['unitPhone'],
								'Col_Mobile' => $inArr['shortNum']?$inArr['shortNum'].'('.$inArr['mobilePhone'].')':$inArr['mobilePhone']
								));
							$tempArr['result'] = '导入成功';
						}else{
							$tempArr['result'] = '<font color=red>导入失败</font>';
						}
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						array_push($resultArr ,$tempArr);
					}

				}
				return $resultArr;
			}
		}
	}

	/**
	 * 导入入离职信息
	 *
	 */
	function addInleaveExecelData_d(){
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
		$deptArr = array();//部门数组
		$jobsArr = array();//职位数组
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
					if($key === 0){
						continue ;
					}
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1])){
						continue;
					}else{
						//新增数组
						$inArr = array();

						//员工编号
						if(!empty($val[0])){
							$val[0]=trim($val[0]);
							if(!isset($userArr[$val[0]])){
								$rs=$this->get_table_fields($this->tbl_name, "userNo='".$val[0]."'", 'id');
								if(!empty($rs)&&$rs>0){
									$inArr['id'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!不存在的员工编号或该员工未进行基本信息的录入</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
//							$inArr['userNo'] = $val[0];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!员工编号为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//员工姓名
						if(!empty($val[1])){
							if(!isset($userArr[$val[1]])){
								$rs = $otherDataDao->getUserInfo($val[1]);
								if(!empty($rs)){
									$userArr[$val[1]] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!不存在的员工名称</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}

//							$inArr['userAccount'] = $userArr[$val[1]]['USER_ID'];
//							$inArr['userName'] = $val[1];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!没有员工姓名</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//入职日期
						if(!empty($val[2])&& $val[2] != '0000-00-00'&&trim($val[2])!=''){
							$val[2] = trim($val[2]);

							if(!is_numeric($val[2])){
								$inArr['entryDate'] = $val[2];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[2] - 1 , 1900)));
								if($recorderDate=='1970-01-01'){
									$entryDate = date('Y-m-d',strtotime ($val[2]));
									$inArr['entryDate'] = $entryDate;
								}else{
									$inArr['entryDate'] = $recorderDate;
								}
							}
						}

						//入职地点
						if(!empty($val[3])&&trim($val[3])!=''){
							$inArr['entryPlace'] =trim($val[3]);
						}
						//预计转正
						if(!empty($val[4])&& $val[4] != '0000-00-00'&&trim($val[4])!=''){
							$val[4] = trim($val[4]);

							if(!is_numeric($val[4])){
								$inArr['becomeDate'] = $val[4];
							}else{
								$becomeDate = date('Y-m-d',(mktime(0,0,0,1, $val[4] - 1 , 1900)));
								if($becomeDate=='1970-01-01'){
									$quitDate = date('Y-m-d',strtotime ($val[4]));
									$inArr['becomeDate'] = $quitDate;
								}else{
									$inArr['becomeDate'] = $becomeDate;
								}
							}
						}

						//实际转正日期
						if(!empty($val[5])&&trim($val[5])!=''){
							$val[5] = trim($val[5]);
							$inArr['realBecomeDate'] = $val[5];
						}

						//转正分数
						if(!empty($val[6])&&trim($val[6])!=''){
							$inArr['becomeFraction'] = trim($val[6]);
						}


						//离职日期
						if(!empty($val[7])&& $val[7] != '0000-00-00'&&trim($val[7])!=''){
							$val[7] = trim($val[7]);

							if(!is_numeric($val[7])){
								$inArr['quitDate'] = $val[7];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[7] - 1 , 1900)));
								if($recorderDate=='1970-01-01'){
									$quitDate = date('Y-m-d',strtotime ($val[7]));
									$inArr['quitDate'] = $quitDate;
								}else{
									$inArr['quitDate'] = $recorderDate;
								}
							}
						}


						//离职类型
						if(!empty($val[8])&&trim($val[8])!=''){
							$val[8] = trim($val[8]);
							if(!isset($datadictArr[$val[8]])){
								$rs = $datadictDao->getCodeByName('HRLZLX',$val[8]);
								if(!empty($rs)){
									$incentiveType = $datadictArr[$val[8]]['code'] = $rs;
								}else{
									$incentiveType="";
									$val[8]="";
								}
							}else{
								$incentiveType = $datadictArr[$val[8]]['code'];
							}
							$inArr['quitTypeCode'] = $incentiveType;
							$inArr['quitTypeName'] = $val[8];
						}


						//离职原因
						if(!empty($val[9])&&trim($val[9])!=''){
							$inArr['quitReson'] =trim($val[9]);
						}

						//离职面谈记录
						if(!empty($val[10])&&trim($val[10])!=''){
							$inArr['quitInterview'] = trim($val[10]);
						}


//						print_r($inArr);
						$newId =parent::edit_d($inArr,true);
						if($newId){
							$tempArr['result'] = '导入成功';
						}else{
							$tempArr['result'] = '导入失败';
						}
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						array_push($resultArr ,$tempArr);
					}
				}
				return $resultArr;
			}
		}
	}

	/**
	 * 导入员工附件
	 */
	function importStaffFile(){
		$resultArr = array();//结果数组
		$managementDao=new model_file_uploadfile_management();
		//读取文件夹下的文件，循环插入附件表
		$dir = UPLOADPATH."hr/staff/";
		// Open a known directory, and proceed to read its contents
		$UPLOADPATH2=UPLOADPATH;
		$newPath=str_replace('\\','/',$UPLOADPATH2);
		$destDir=$newPath."personnel_staff/";
		if(!file_exists($destDir)){
			mkdir($destDir);
		}
		$list=$this->list_d();
		$map=array();
		foreach($list as $k=>$v){
			$map[$v['userNo']]=$v['id'];
		}
		$i=0;//用于循环中构造结果数组
		//初始化附件都导入成功，若有导入失败，将会覆盖初始化记录
		$resultArr[$i]['docCode']="所有附件";
		$resultArr[$i]['result']="导入成功";
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				$rootFile=scandir ( $dir );
				foreach($rootFile as  $key=>$val){
					if($val != "." && $val != ".."&&is_dir($dir.$val)){
						$temp = scandir ( $dir.$val ); // 列出指定路径中的文件和目录
						if(is_array($temp)){
							foreach($temp as $k=>$v){
								if($v != "." && $v != ".."&&$v!='Thumbs.db'){
			        				preg_match_all('/\d+/', $val, $userNo);//过滤出文件夹中的数字即人员编号
									//判断附件文件夹名称与人员编号是否匹配
			        				if($map[$userNo[0][0]]!=null){
			        					$source=$dir.$val."/".$v;
				            			$dest=$destDir.$val."-".$v;//保证名称唯一
				            			copy ($source,$dest);
				            			//插入附件表
				            			$fileArr['serviceType']="personnel_staff";
				            			$fileArr['serviceId']=$map[$userNo[0][0]];
				            			$fileArr['originalName']=$v;
				            			$fileArr['newName']=$val."-".$v;
				            			$fileArr['uploadPath']=$destDir;
				            			$fileArr['tFileSize']=filesize($dest);
				            			$test = $managementDao->add_d ( $fileArr, true );
				            		}else{
				            			$resultArr[$i]['docCode']=$val.'/'.$v;
				            			$resultArr[$i]['result']='<font color=red>导入失败，不存在的员工编号或该员工未进行基本信息的录入。</font>';
				            			$i++;
				            		}
				            	}
				            }
				        }

				    }
				}
				closedir($dh);
			}
		}
		return $resultArr;
	}
	/*****************************导出导入*****************************/


	/*
	 * 员工等级信息导出
	 *
	 */
	function degreeExcelOut($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//创建一个Excel工作流
		//		$objPhpExcelFile = new PHPExcel();


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/人资-员工等级信息导出模板.xls" ); //读取模板
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
					$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m ++;
				}
				$i ++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'O' . $i );
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
		header ( 'Content-Disposition:inline;filename="' . "员工等级信息导出报表.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}



	/*
	 * 导出excel
	 */
	function excelOutAll($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//创建一个Excel工作流
		$objPhpExcelFile = new PHPExcel();

		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/人员档案核对数据导出.xls" ); //读取模板
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
		header ( 'Content-Disposition:inline;filename="' . "人事档案导出报表.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}



	/*
	 * 联系信息导出
	 *
	 */
	function contactExcelOut($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//创建一个Excel工作流
		//		$objPhpExcelFile = new PHPExcel();


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/人资-联系信息导入模板.xls" ); //读取模板
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
					$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n,mb_convert_encoding($value,"utf-8","gbk,big5") );
					$m ++;
				}
				$i ++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'V' . $i );
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
		header ( 'Content-Disposition:inline;filename="' . "联系信息导出报表.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}



	/******************* E 导入导出系列 ************************/

	/**
	 * 获取某个二级或者三级部门下的人员
	 * add by chengl
	 */
	function getPersonnelsByDeptId($deptId){
		$this->searchArr['deptId']=$deptId;
		return $this->list_d();
	}

	/**
	 * 根据账号获取是否已制定导师
	 */
	function getTutorBystuId($stuId){
		$sql="select id from oa_hr_tutor_records where studentAccount = '".$stuId."'";
		return $this->_db->getArray($sql);
	}

    /**
     * 工程人员信息列表
     */
    function listEngineering_d($searchDate,$employeesState,$rtSql = false){
        // 权限获取
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
			$_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		$sysLimit = $sysLimit['部门权限'];
        // 获取权限省份
    	$managerDao = new model_engineering_officeinfo_manager();
    	$provinceStr = $managerDao->getProvinceByUser_d($_SESSION['USER_ID']);
		// 获取区域权限
		$officeDao = new model_engineering_officeinfo_officeinfo();
		$officeStr = $officeDao->getOfficeIds_d($_SESSION['USER_ID']);
		$personSql = "";

		//办事处 － 全部 处理
		if (strstr($sysLimit, ';;')) {
			$personSql = "";
		} elseif (!empty($sysLimit) && empty($provinceStr) && empty($officeStr)) {//如果没有选择全部，则进行权限查询并赋值
			$personSql = " and c.belongDeptId in ($sysLimit)";
		} elseif (empty($sysLimit) && !empty($provinceStr) && empty($officeStr)) {
			$provinceSqlStr = util_jsonUtil::strBuild($provinceStr);
			$personSql = " and c.userAccount in (select m.memberId
        		from
        		oa_esm_project p inner join oa_esm_project_member m
        		on p.id = m.projectId
        		where p.province in ($provinceSqlStr)
        			group by m.memberId)";
		} elseif (!empty($sysLimit) && !empty($provinceStr) && empty($officeStr)) {
			$provinceSqlStr = util_jsonUtil::strBuild($provinceStr);
			$personSql = " and (c.belongDeptId in ($sysLimit) or c.userAccount in (select m.memberId
				from
				oa_esm_project p inner join oa_esm_project_member m
				on p.id = m.projectId
				where p.province in ($provinceSqlStr)
				group by m.memberId))";
		} elseif (empty($sysLimit) && empty($provinceStr) && !empty($officeStr)) {
			$personSql = " and c.userAccount in (select m.memberId
        		from
        		oa_esm_project p inner join oa_esm_project_member m
        		on p.id = m.projectId
        		where p.officeId in($officeStr)
        			group by m.memberId)";
		} elseif (!empty($sysLimit) && empty($provinceStr) && !empty($officeStr)) {
			$personSql = " and (
					c.belongDeptId in($sysLimit)
					or
					c.userAccount in (select m.memberId from oa_esm_project p inner join oa_esm_project_member m
					on p.id = m.projectId where p.officeId in ($officeStr) group by m.memberId)
				)";
		} elseif (empty($sysLimit) && !empty($provinceStr) && !empty($officeStr)) {
			$provinceSqlStr = util_jsonUtil::strBuild($provinceStr);
			$personSql = " and (
					c.userAccount in(select m.memberId
					from oa_esm_project p inner join oa_esm_project_member m
					on p.id = m.projectId where p.province in ($provinceSqlStr) group by m.memberId)
					or
					c.userAccount in(select m.memberId from oa_esm_project p inner join oa_esm_project_member m
					on p.id = m.projectId where p.officeId in ($officeStr) group by m.memberId)
				)";
		} elseif (!empty($sysLimit) && !empty($provinceStr) && !empty($officeStr)) {
			$provinceSqlStr = util_jsonUtil::strBuild($provinceStr);
			$personSql = " and (
					c.belongDeptId in($sysLimit)
					or
					c.userAccount in(select m.memberId from oa_esm_project p inner join oa_esm_project_member m
					on p.id = m.projectId where p.province in ($provinceSqlStr) group by m.memberId)
					or
					c.userAccount in(select m.memberId from oa_esm_project p inner join oa_esm_project_member m
					on p.id = m.projectId where p.officeId in ($officeStr) group by m.memberId)
				)";
		}

		//存在人员状态时，加载
		if($employeesState!=""){
			$personSql .= " and c.employeesState = '$employeesState'";
		}

		//sql
		$sql = "select
			c.id,c.userNo,c.userName,c.entryDate,c.personLevel,c.belongDeptId,c.belongDeptName,
			c.officeName,concat(c.eprovince,c.ecity) as eprovinceCity,
			m.projectName as belongProject,
			l.projectName,l.workStatus,l.projectEndDate,l.activityName,l.activityEndDate
			from
			oa_hr_personnel c
			left join
			(
				select
				group_concat(p.projectCode separator '/') as projectCode,group_concat(p.projectName separator '/') as projectName,m.memberId
				from
				oa_esm_project p
				inner join
				oa_esm_project_member m on p.id = m.projectId
				where p.status = 'GCXMZT02' and m.status = '0'
				group by m.memberId
				) m on c.userAccount = m.memberId
			left join
			(
				select
				group_concat(w.projectCode separator '/') as projectCode,group_concat(w.projectName separator '/') as projectName,
				w.createId,group_concat(w.activityName separator '/') as activityName,group_concat(w.activityEndDate separator '/') as activityEndDate,
				group_concat(d.dataName separator '/') as workStatus,group_concat(w.projectEndDate separator '/') as projectEndDate
				from
				oa_esm_worklog w
				left join
				oa_system_datadict d on w.workStatus = d.dataCode
				where w.executionDate = '$searchDate' and w.activityId <> 0 and parentCode = 'GXRYZT'
				group by w.createId
				) l on c.userAccount = l.createId
			where
			1 $personSql ";
		if($rtSql == true){
			return $sql;
		}
		return $rows = $this->listBySql($sql);
	}

    /**
     * 获取默认部门
     */
	function getDefaultDept_d() {
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
			$_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		$deptLimit = $sysLimit['部门权限'];
		$deptLimit = str_replace(';', '', $deptLimit);

        // 避免开了全部权限的同事又点了其他部门的,上面把 “;;” 字符串过滤掉后,留下了一个 “,” 导致查询语句出错 PMS 2780
        $deptLimitArr = explode(",",$deptLimit);
        foreach ($deptLimitArr as $k => $v){
            if($v == ''){
                unset($deptLimitArr[$k]);
            }
        }
        $deptLimit = implode(",",$deptLimitArr);


		$deptNameArr = array();
		if (!empty($deptLimit)) {
			$deptDao = new model_deptuser_dept_dept();
			$deptArr = $deptDao->getDeptByIds_d($deptLimit);
			foreach ($deptArr as $key => $val) {
				$deptNameArr[$key] = $val['deptName'];
			}
		}
		return array(
			'deptId' => $deptLimit,
			'deptName' => implode(',', $deptNameArr)
		);
	}

	/**
	 * 附件权限管理
	 */
	function fileLimits_d( $obj ) {
		try {
			$this->start_d();

			$fileDao = new model_file_uploadfile_management();
			if (is_array($obj['fileLimits'])) {
				foreach ($obj['fileLimits'] as $key => $val) {
					if (isset($val['limits'])) {
						$val['limits'] = 1;
					} else {
						$val['limits'] = 0;
					}
					$fileDao->edit_d($val);
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 关联职位申请
	 */
	function associatePosition_d( $obj ) {
		try {
			$this->start_d();

			$id = $this->edit_d( $obj );
			if ($id) {
				$workDao = new model_hr_personnel_work();
				if (is_array($obj['work'])) {
					foreach ($obj['work'] as $key => $val) {
						if ($val['isDelTag'] != 1) {
							if ($val['id'] != '') {
								$workDao->edit_d($val);
							} else {
								unset($val['id']);
								$val['userNo'] = $obj['userNo'];
								$val['userName'] = $obj['userName'];
								$val['userAccount'] = $obj['userAccount'];
								$workDao->add_d($val);
							}
						} else if ($val['id']) {
							$workDao->deleteByPk($val['id']);
						}
					}
				}

				$educationDao = new model_hr_personnel_education();
				if (is_array($obj['education'])) {
					foreach ($obj['education'] as $key => $val) {
						if ($val['isDelTag'] != 1) {
							if ($val['id'] != '') {
								$educationDao->edit_d($val);
							} else {
								unset($val['id']);
								$val['userNo'] = $obj['userNo'];
								$val['userName'] = $obj['userName'];
								$val['userAccount'] = $obj['userAccount'];
								$educationDao->add_d($val);
							}
						} else if ($val['id']) {
							$educationDao->deleteByPk($val['id']);
						}
					}
				}

				$familyDao = new model_hr_personnel_society();
				if (is_array($obj['family'])) {
					foreach ($obj['family'] as $key => $val) {
						if ($val['isDelTag'] != 1) {
							if ($val['id'] != '') {
								$familyDao->edit_d($val);
							} else {
								unset($val['id']);
								$val['userNo'] = $obj['userNo'];
								$val['userName'] = $obj['userName'];
								$val['userAccount'] = $obj['userAccount'];
								$familyDao->add_d($val);
							}
						} else if ($val['id']) {
							$familyDao->deleteByPk($val['id']);
						}
					}
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}


	//工资接口
	function getPersonInfoByUserId($userId){
		$row=$this->getPersonnelInfo_d($userId);
		$returnArr=array();
		if(!empty($row)){
			$returnArr['entryDate']=$row['entryDate'];//入职时间
			$returnArr['becomeDate']=$row['becomeDate'];//预计转正时间
			$returnArr['realBecomeDate']=$row['realBecomeDate'];//实际转正日期
			$returnArr['oftenCardNum']=$row['oftenCardNum'];//常用卡号
			$returnArr['oftenAccount']=$row['oftenAccount'];//常用账号
			$returnArr['oftenBank']=$row['oftenBank'];//常用账号开户行
			//实际转正工资
			$returnArr['afterSalary']=$this->get_table_fields('oa_hr_permanent_examine', "userNo='".$row['userNo']."'", 'afterSalary');
			//试用期基本工资
			$entryNoticeDao=new model_hr_recruitment_entryNotice();
			$entryNoticeRow=$entryNoticeDao->getAllInfoByUserID_d($userId);
			if($entryNoticeRow['deptId']=='155'){//服务线实习生部门
				$returnArr['beforeSalary']=$entryNoticeRow['internshipSalary'];
			}else{
				$returnArr['beforeSalary']=$entryNoticeRow['useTrialWage'];
			}
		}
		return $returnArr;
	}

	/**
	 * 重写get_d
	 */
	function get_d($id) {
		$condition = array ("id" => $id );
		$obj = $this->find ( $condition );
		$obj['age'] = $this->getAge($obj['birthdate']);//计算年龄
		return $obj;
	}

	/**
	 * 计算年龄
	 * 以解决数据库年龄不准的问题
	 * @param $birthdate为生日
	 */
	function getAge($birthdate){
		$birthdate = strtotime($birthdate);
		$year = date('Y', $birthdate);
		if(($month = (date('m') - date('m', $birthdate))) < 0){
			$year++;
		}else if ($month == 0 && date('d') - date('d', $birthdate) < 0){
			$year++;
		}
		return date('Y') - $year;
	}
}
?>