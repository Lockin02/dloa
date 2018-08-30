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
 * @Date 2012��5��25�� ������ 15:21:04
 * @version 1.0
 * @description:���¹���-������Ϣ Model��
 */

class model_hr_personnel_personnel  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel";
		$this->sql_map = "hr/personnel/personnelSql.php";
		parent::__construct ();
	}

	//�����ֵ��ֶδ���
	public $datadictFieldArr = array(
		'deptSuggest'
		);

	//ͨ����½�û�ID��ȡ�û����ڹ�˾�Ͳ��ŵ���Ϣ
	function getDeptInfo($userID){
		$sql = "select c.companyName,c.deptIdS,c.deptIdT from oa_hr_personnel c where c.userAccount ='".$userID."'";
		$obj = $this->_db->getArray($sql);
		return $obj[0];
	}


	//���� ��ȡ��Ƭ��ַ
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
	 * Ա�������ӹ���
	 */
	function userNoAdd_d($companyName,$deptId=null){
		$muc="";
		if($deptId>0){
			$empFlag = $this->get_table_fields('department', "DEPT_ID='".$deptId."'", 'empFlag');
			if($empFlag == 1) {//��ʽԱ��
				$personnelRow = $this->listBySqlId ( "select_userNo" );
				$muc = $personnelRow['0']['muc']+1;
				$muc = sprintf("%06.0f",$muc);
				$sql = "SELECT comcard FROM branch_info i  where NameCN='".$companyName."' ";
				$rs = $this->_db->getArray($sql);
				$comc = $rs['0']['comcard'];
				$muc = $comc.$muc;
			} else {//��Ƹ�Ŷ�
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
	 * ���¾�ϵͳ������Ϣ
	 */
	function updateOldInfo_d($id,$type,$flag=null,$companyChange=null){
		$object=$this->get_d($id);
		//����״��
		switch($object['maritalStatusName']){
			case "δ��":$MARRY = '0';break;
			case '�ѻ�':$MARRY = '1';break;
			default:$MARRY = '';break;
		}
		//ѧ��
		switch($object['highEducationName']){
			case "����":$EDUCATION = '2';break;
			case '����':$EDUCATION = '3';break;
			case "��ר":$EDUCATION = '4';break;
			case '��ר':$EDUCATION = '5';break;
			case "����":$EDUCATION = '6';break;
			case '˶ʿ':$EDUCATION = '7';break;
			case "��ʿ":$EDUCATION = '9';break;
			case 'δ�����������':$EDUCATION = '10';break;
			default:$EDUCATION = '';break;
		}
		//�ù���ʽ
		switch($object['personnelTypeName']){
			case "��ʽԱ��":$intern='1';break;
			case '��ǲԱ��':$intern='2';break;
			case 'ʵϰ��':$intern='3';break;
			case '����ʵϰ��':$intern='1';break;
			case '����ʵϰ��':$intern='5';break;
			case '���ػ�Ա��':$intern='4';break;
			default:$intern='';break;
		}
		if($intern == '2') {
			$ExpFlag = '1';
		} else {
			$ExpFlag = '0';
		}
		//���ʼ���
		switch($object['wageLevelName']){
			case "�ܾ���":$UserLevel='0';$jobL=2;break;
			case '����':$UserLevel='1';$jobL=2;break;
			case '�ܼ�':$UserLevel='2';$jobL=2;break;
			case '����':$UserLevel='3';$jobL=2;break;
			case '�ǹ����':$UserLevel='4';$jobL=1;break;;
			case '���ܼ�':$UserLevel='5';$jobL=2;break;
			case '����':$UserLevel='6';$jobL=2;break;
			default:$UserLevel='';$jobL=2;break;
		}
		//��Ա�ȼ�
		switch(substr($object['personLevel'],0,1)){
			case "B":$localizers=2;break;
			default:$localizers=1;break;
		}
		//�Ա�
		switch($object['sex']){
			case "��":$sex='0';break;
			case 'Ů':$sex='1';break;
		}
		//��ְ����
		if($object['quitDate']=='0000-00-00'||$object['quitDate']==''){
			$object['quitDate']='null';
		}else{
			$object['quitDate']="'".$object['quitDate']."'";
		}
		//ת������
		if($object['realBecomeDate']=='0000-00-00'||$object['realBecomeDate']==''){
			if($object['becomeDate']!='0000-00-00'||$object['realBecomeDate']!=''){
				$object['becomeDate']="'".$object['becomeDate']."'";
			}else{
				$object['becomeDate']='null';
			}
		}else{
			$object['becomeDate']="'".$object['realBecomeDate']."'";
		}
		//��ְ����
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
		 	//�жϾ�ϵͳ�Ƿ����и�Ա��������Ϣ
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
							    //���¹���ģ������֤��Ϣ
				$sql2="update salary set idcard='".trim($object['identityCard'])."' where userid='".$object['userAccount']."'";
				$this->query($sql2);
							  	//�ж����������Ƿ�����
				$deptFlag=$this->get_table_fields('department', "DEPT_ID='".$object['belongDeptId']."'", 'DelFlag');
				if(!$deptFlag){
								   	//���´�����
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
						'COM_BRN_CN'=>'���Ͷ���',
						'DEPT_NAME'=>$dept_name,
						'JOBS_NAME'=>$object["jobName"],
						'USER_ID'=>$logname,
						);
					$im = new includes_class_ImInterface();
					if(!empty($object["belongDeptName"])){
						$im->edit_user('���Ͷ���',$logname,$data);
					}
				}
			}
		}
	}

	/**
	 * ��дadd
	 */
	function add_d($object) {
		$identityCard = $this->get_table_fields('oa_hr_personnel', "identityCard='".$object['identityCard']."' AND employeesState='YGZTZZ'", 'identityCard');
		if($identityCard != '') {
			return false;
		}
		try{
			$this->start_d();

			//���������ֵ��ֶ�
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

			$object['mobilePhone'] = $object['mobile'];//�绰
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
					$object['companyType'] = '�ӹ�˾';
				} else {
					$object['companyType'] = '����';
				}
				$object['companyId'] = $this->get_table_fields('branch_info', "NameCN='".$object['companyName']."'", 'ID');
				if($object['regionId'] > 0) {
					$object['regionName'] = $this->get_table_fields('area', "ID='".$object['regionId']."'", 'Name');
				}
			}
			$id = parent::add_d($object ,true);
			//���������ƺ�Id
			$this->updateObjWithFile($id);
			if($object['entryId'] > 0) {
				$entryNoticeDao = new model_hr_recruitment_entryNotice();
				$entryNoticeDao->updateField("id=".$object['entryId'] ,'staffFileState' ,1);
				$entryNoticeDao->updateField("id=".$object['entryId'] ,'userAccount' ,$object['userAccount']);
				$entryNoticeDao->updateField("id=".$object['entryId'] ,'userNo' ,$object['userNo']);
				$entryNoticeDao->updateField("id=".$object['entryId'] ,'entryDate' ,$object['entryDate']);

				if($object['employmentId'] > 0) {
					//ͬ����������
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

					//��������
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

					//��ͥ��Ա
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

			//ͬ�����¾�ϵͳ�еĵ�����Ϣ
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
	 * ��дedit
	 */
	function edit_d($object){
		try{
			$this->start_d();

			//���������ֵ��ֶ�
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
					$object['companyType']='�ӹ�˾';
				}else{
					$object['companyType']='����';
				}
				$object ['companyId']=$this->get_table_fields('branch_info', "NameCN='".$object['companyName']."'", 'ID');
				if($object['regionId']>0){
					$object ['regionName']=$this->get_table_fields('area', "ID='".$object['regionId']."'", 'Name');
				}
			}
			$oldObj = $this->get_d ( $object ['id'] );
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object );
			//�жϻ��������Ƿ�ı䣬�����ʼ�ָ֪ͨ����
			if($oldObj['householdType']!=$object['householdType']&&!isset($object['userNo'])){
				$this->mailNotice($oldObj,$object);		//�ҵĵ��������䶯�ʼ�֪ͨ
			}
			$id=parent::edit_d($object,true);

			//ͬ�����¾�ϵͳ�еĵ�����Ϣ
			$this->updateOldInfo_d($object['id'],'edit',1,$object['companyChange']);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/*
	 * �ҵĵ��������䶯�ʼ�֪ͨ
	 */
	function mailNotice($oldObj,$object){
		include (WEB_TOR . "model/common/mailConfig.php");
		$mailId = $mailUser['oa_hr_personnel']['TO_ID'];
		$addMsg = "���� ��</br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��".$oldObj['belongDeptName']."��Ա�����Ϊ��".$oldObj['userNo']."��������".$oldObj['userName']."���Ļ������ʹӡ�".$oldObj['householdType']."���ĳɡ�".$object['householdType']."��";
		$emailDao = new model_common_mail();
		$emailDao->mailClear('���˵��������䶯֪ͨ',$mailId, $addMsg);
	}

	/**
	 * �ⲿ����edit���������漰��ͬ������
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
	 * ��ְ����������ְԱ���������÷���
	 */
	function updataLeave_d($object){
		try{
			$this->start_d();
	 		//���������ֵ��ֶ�
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

			//ͬ�����¾�ϵͳ�еĵ�����Ϣ
			$this->updateOldInfo_d($object['id'],'edit');

			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �޸�����ְ��Ϣ
	 */
	function editInLeave_d($object){
		try{
			$this->start_d();

			//���������ֵ��ֶ�
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

			//ͬ�����¾�ϵͳ�еĵ�����Ϣ
			$this->updateOldInfo_d($object['id'] ,'edit');
			$this->commit_d();
			return $id;
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * �޸���ϵ��Ϣ
	 */
	function editContact_d($object){
		try{
			$this->start_d();
			$id= parent::edit_d($object,true);

			//ͬ�����¾�ϵͳ�еĵ�����Ϣ
			$this->updateOldInfo_d($object['id'],'edit');
			//����ͨѶ¼
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
	 * ���������Ϣ�޸�
	 */
	function degreeEdit($object){
		try{
			$this->start_d();

			//���������ֵ��ֶ�
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
	 * �����û��˺Ż�ȡ��Ϣ
	 */
	function getPersonnelInfo_d($userAccount){
		$this->searchArr = array ('userAccount' => $userAccount );
		$personnelRow= $this->listBySqlId ( "select_default" );
		return $personnelRow['0'];
	}

	/**
	 * �����û��˺Ż�ȡ��Ϣ
	 */
	function getPersonnelSimpleInfo_d($userAccount){
		$this->searchArr = array ('userAccount' => $userAccount );
		$personnelRow= $this->listBySqlId ( "select_simple" );
		return $personnelRow['0'];
	}

	/**
	 * �����û��˺Ż�ȡ��Ϣ
	 */
	function getInfoByUserNo_d($userNo){
		$this->searchArr = array ('userNo' => $userNo );
		$this->__SET('sort', 'c.userNo');
		$personnelRow= $this->listBySqlId ( "select_simple" );
		return $personnelRow['0'];
	}

	/**
	 * �����û��˺��ж��Ƿ��������Ա����
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
	 * ����������Ա����
	 */
	function selectPersonnel_d($condition){
		$this->searchArr = $condition;
		$personnelRow= $this->listBySqlId ( "select_simple" );
		return $personnelRow;
	}

	/**
	 * ¼�벿�Ž���
	 */
	function deptSuggest_d($object){
		//��Ա����
		$mainObj = $object['personnel'];
		$mainObj = $this->processDatadict($mainObj);
		//���鲿��
		$suggestObj = $object['trialdeptsuggest'];

		//�ʼ�
		if(isset($mainObj['mail'])){
			$emailArr = $mainObj['mail'];
			unset($mainObj['mail']);
		}

		try{
			$this->start_d();
			//���²��Ž���
			parent::edit_d($mainObj,true);

			//������Ǵ��ˣ����ύ��������
			if($mainObj['deptSuggest'] == 'HRBMJY-01' || $mainObj['deptSuggest'] == 'HRBMJY-02'){
				//������Ϣ����
				$suggestObj['deptSuggest'] = $mainObj['deptSuggest'];
				$suggestObj['deptSuggestName'] = $mainObj['deptSuggestName'];
				$suggestObj['suggestion'] = $mainObj['suggestion'];
				//���Ž���
				$trialdeptsuggestDao = new model_hr_trialplan_trialdeptsuggest();
				$suggestId = $trialdeptsuggestDao->addInPersonnel_d($suggestObj);
			}
			$this->commit_d();

			//�ʼ�����
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
	 * ���Ž���
	 */
	function deptSuggestMail_d($emailArr,$object){
		$addMsg = $_SESSION['USERNAME'].' �Ѷ� ����Ա�� ��'.$object['userName'].'��¼�벿�Ž��� ��'.$object['deptSuggestName'] .'��,';
		if($object['deptSuggest'] != 'HRBMJY-03'){
			$addMsg.='���ڲ��Ž�����Ϣ�б��н���ص����ύ����,';
		}
		$addMsg .= '<br/>�����������£�'.$object['suggestion'];

		$emailDao = new model_common_mail();
		$emailDao->mailClear('OA-����Ա�����Ž���',$emailArr['TO_ID'],$addMsg);
	}

	/**
	 * �ʼ����û�ȡ
	 */
	function getMailInfo_d(){

		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = isset($mailUser['deptsuggest']) ? $mailUser['deptsuggest'] : array('sendUserId'=>'',
			'sendName'=>'');
		return $mailArr;
	}

	/**
	 * ������Ա��Ϣ
	 * @param1��Ҫ���µ���Ա�ʺ�
	 * @param2��Ҫ���µ�����
	 */
	function updatePersonnel_d($userAccount,$object){
		//��������
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
	 * �����������ʼ��������и���
	 */
	function sendEmail_d($object){
		try{
			$this->start_d();
			$uploadFile = new model_file_uploadfile_management ();
			$emailDao = new model_common_mail();
			//��ȡ�ϴ��ĸ���
			$files = $uploadFile->getFilesByObjNo ( $object ['mailServiceNo'], 'oa_hr_personnel_email' );
			$fileArr=array();
			if(is_array($files)){
				foreach($files as $key=>$val){
					if(file_exists($val['uploadPath'].$val['newName'])){
                        $fileArr[$val['uploadPath'].$val['newName']] = $val['originalName'];
					}
				}
			}
			//����������ȡ��Ա��Ϣ
			$condition=array('regionName'=>$object['socialPlace'],'employeesState'=>'YGZTZZ');
			$personnelRows=$this->selectPersonnel_d($condition);
			if(is_array($personnelRows)){//�����ʼ�
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

	/********************** ����ʹ�� **********************/
	/**
	 * ������Ա��Ϣ - ��Ŀ��Ϣ
	 * create by kuangzw
	 * create on 2012-8-8
	 */
	function updatePersonnelInfo_d($user_id,$activityArr = null,$esmprojectArr = null){
		//��������
		$conditionArr = array(
			'userAccount' => $user_id
			);

		//��������
		$updateArr = array();
		//������������
		if($activityArr){
			$updateArr['taskName'] = $activityArr['activityName'];
			$updateArr['taskId'] = $activityArr['id'];
			$updateArr['taskPlanEnd'] = $activityArr['planEndDate'];
		}else{
			$updateArr['taskName'] = '';
			$updateArr['taskId'] = 0;
			$updateArr['taskPlanEnd'] = '0000-00-00';
		}
		//������������
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
	 * �����û��˺Ż�ȡ��Ϣ
	 */
	function getPersonnelAndLevel_d($userAccount){
		//��ȡ�û��Լ��ȼ���Ϣ
		$obj = $this->find(array('userAccount' => $userAccount),null,'userAccount,userName,personLevel,personLevelId');
		if($obj['personLevelId']){
			//��ѯ��Ա�ȼ���ƥ���Ԥ��ȼ�
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

	/******************* S ���뵼��ϵ�� ************************/
	function addExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$inArr = array();//��������
		$userArr = array();//�û�����
		$deptArr = array();//��������
		$jobsArr = array();//ְλ����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		$deptDao = new model_deptuser_dept_dept();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {
				$dataArr = array(); //��ȡ��������
				$keyName = array(
					'userNo', //Ա�����
					'staffName', //Ա������
					'englishName', //��������Ӣ��
					'sex', //�Ա�
					'birthdate', //��������
					'age', //����
					'nativePlacePro', //����ʡ
					'nativePlaceCity', //������
					'nation', //����
					'identityCard', //���֤��
					'identityCardDate0', //���֤��Ч���ڣ���ʼ��
					'identityCardDate1', //���֤��Ч���ڣ�������
					'identityCardAddress', //���֤�ϵ�ַ
					'politicsStatus', //������ò
					'highEducationName', //���ѧ��
					'highSchool', //��ҵѧУ
					'professionalName', //רҵ
					'graduateDate', //��ҵʱ��
					'companyType', //��˾����
					'companyName', //��˾����
					'belongDeptName', //��������
					'jobName', //ְλ
					'regionName', //����
					'staffStateName', //Ա��״̬
					'personnelTypeName', //Ա������
					'positionName', //��λ����
					'personnelClassName', //��Ա����
					'wageLevelName', //���ʼ���
					'jobLevel', //ְ��
					'functionName', //ְ��
					'healthState', //�������
					'isMedicalHistory', //�Ƿ��м�����ʷ
					'medicalHistory', //������ʷ
					'InfectDiseases', //��Ⱦ����
					'height', //���
					'weight', //����
					'blood', //Ѫ��
					'maritalStatusName', //����״��
					'birthStatus', //����״��
					'hobby', //����
					'speciality', //�س�
					'professional', //רҵ����
					'oftenCardNum', //���ÿ���
					'oftenAccount', //�����˺�
					'oftenBank', //���ÿ�����
					'bankCardNum', //����
					'accountNumb', //�˺�
					'openingBank', //������
					'salaryAreaCode', //�����˺ŵ�������
					'archivesCode', //�������
					'archivesLocation', //�������ڶ�
					'residencePro', //����ʡ
					'residenceCity', //������
					'householdType', //��������
					'collectResidence', //���廧��
					'socialPlace', //�籣�����
					'isNeedTutor', //�Ƿ���Ҫ��ʦ
					'socialBuyer', //�籣����
					'fundPlace', //���������
					'fundBuyer', //��������
					'fundCardinality', //������ɷѻ���
					'fundProportion', //������ɷѱ���
					'outsourcingSupp', //�����˾
					'outsourcingName', //�������
					'personLevel', //�����ȼ�
					'officeName', //��������
					'eprovince', //���߲���ʡ��
					'ecity', //���߲�������
					'technologyName', //��������
					'networkName', //����
					'deviceName' //�豸���Ҽ�����
				);
				foreach ($excelData as $key => $val) {
					if ($key > 1 && !empty($val[0]) && !empty($val[1])) { //�Ǳ�ͷ��ǰ�����ֶβ�Ϊ��
						$data = array();
						foreach ($keyName as $k => $v) {
							$data[$v] = trim($val[$k]);
						}
						array_push($dataArr ,$data);
					}
				}

				//������ѭ��
				foreach($dataArr as $key => $val) {
					//��������
					$inArr = array();
					$actNum = $key + 1;

					//Ա�����
					if(!empty($val['userNo'])){
						$userNo = $this->get_table_fields($this->tbl_name, "userNo='".$val['userNo']."'", 'userNo');
						if(!empty($userNo)) {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��Ա����Ϣ��¼��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
						$rs = $otherDataDao->getUserInfoByUserNo($val['userNo']);
						if(!empty($rs)) {
							$userArr[$val['staffName']] = $rs;
							$userName = $this->get_table_fields('user', "USER_ID='".$userArr[$val['staffName']]['USER_ID']."'", 'USER_NAME');//�����û�����ȡԱ�����
							if($userName != $val['staffName']) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!Ա�������Ա��������ƥ��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
							$inArr['userName'] = $userName;
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա�����</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
						$inArr['userAccount'] = $rs['USER_ID'];
						$inArr['userNo'] = $val['userNo'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<font color=red>����ʧ��!Ա�����Ϊ��</font>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//Ա������
					if(!empty($val['staffName'])) {
						if(!isset($userArr[$val['staffName']])) {
							$rs = $otherDataDao->getUserInfo($val['staffName']);
							if(!empty($rs)) {
								$userArr[$val['staffName']] = $rs;
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա������</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['staffName'] = $val['staffName'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<font color=red>����ʧ��!Ա������Ϊ��</font>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//��������Ӣ��
					if(!empty($val['englishName'])) {
						$inArr['englishName'] = $val['englishName'];
					}

					//�Ա�
					if(!empty($val['sex']) && ($val['sex'] == '��' || $val['sex'] =='Ů')) {
						$inArr['sex'] = $val['sex'];
					}

					//��������
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

					//����
					if(!empty($val['age'])) {
						$inArr['age'] = $val['age'];
					}

					//���ᣨʡ��
					if(!empty($val['nativePlacePro'])) {
						$inArr['nativePlacePro'] = $val['nativePlacePro'];
					}

					//���ᣨ�У�
					if(!empty($val['nativePlaceCity'])) {
						$inArr['nativePlaceCity'] = $val['nativePlaceCity'];
					}

					//����
					if(!empty($val['nation'])) {
						$inArr['nation'] = $val['nation'];
					}

					//���֤��
					if(!empty($val['identityCard'])) {
						$inArr['identityCard'] = $val['identityCard'];
					}

					//���֤��Ч���ڿ�ʼ
					if(!empty($val['identityCardDate0']) && $val['identityCardDate0'] != '0000-00-00') {
						$identityCardDate0 = '';
						if(!is_numeric($val['identityCardDate0'])) {
							$identityCardDate0 = str_replace('-' ,'.' ,$val['identityCardDate0']); //ת����ʽ
						} else {
							$identityCardDate0 = date('Y.m.d' ,(mktime(0 ,0 ,0 ,1 ,$val['identityCardDate0'] - 1 ,1900)));
							if($identityCardDate0 == '1970.01.01') {
								$identityCardDate0 = date('Y.m.d' ,strtotime($val['identityCardDate0']));
							}
						}
					}

					//���֤��Ч���ڽ���
					if(!empty($val['identityCardDate1']) && $val['identityCardDate1'] != '0000-00-00') {
						$identityCardDate1 = '';
						if(!is_numeric($val['identityCardDate1'])) {
							$identityCardDate1 = str_replace('-' ,'.' ,$val['identityCardDate1']); //ת����ʽ
						} else {
							$identityCardDate1 = date('Y.m.d' ,(mktime(0 ,0 ,0 ,1 ,$val['identityCardDate1'] - 1 ,1900)));
							if($identityCardDate1 == '1970.01.01') {
								$identityCardDate1 = date('Y.m.d' ,strtotime($val['identityCardDate1']));
							}
						}
					}

					//���֤��Ч����ƴ��
					if (!empty($identityCardDate0) && !empty($identityCardDate1)) {
						$inArr['identityCardDate'] = $identityCardDate0.'-'.$identityCardDate1;
					}

					//������ò
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

					//���ѧ��
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

					//��ҵѧУ
					if(!empty($val['highSchool'])) {
						$inArr['highSchool'] = $val['highSchool'];
					}

					//רҵ
					if(!empty($val['professionalName'])) {
						$inArr['professionalName'] = $val['professionalName'];
					}

					//��ҵʱ��
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

					//��˾����
					if(!empty($val['companyType'])) {
						if($val['companyType'] == '�ӹ�˾') {
							$inArr['companyTypeCode'] = 0;
						} else {
							$inArr['companyTypeCode'] = 1;
						}
						$inArr['companyType'] = $val['companyType'];
					}

					//��˾����
					if(!empty($val['companyName'])) {
						$inArr['companyId'] = $this->get_table_fields('branch_info', "NameCN='".$val['companyName']."'", 'ID');
						$inArr['companyName'] = $val['companyName'];
					}

					//��������
					if(!empty($val['belongDeptName'])) {
						if(!isset($deptArr[$val['belongDeptName']])) {
							$rs = $otherDataDao->getDeptInfo_d($val['belongDeptName']);
							if(!empty($rs)){
								$deptArr['DEPT_ID'] = $rs['DEPT_ID'];
								$deptArr['Depart_x'] = $rs['Depart_x'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĲ���</font>';
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
						//��������
						$inArr['deptCodeS'] = $deptRow['deptCodeS'];
						$inArr['deptNameS'] = $deptRow['deptNameS'];
						$inArr['deptIdS'] = $deptRow['deptIdS'];
						//��������
						$inArr['deptNameT'] = $deptRow['deptNameT'];
						$inArr['deptCodeT'] = $deptRow['deptCodeT'];
						$inArr['deptIdT'] = $deptRow['deptIdT'];
                        //�ļ�����
                        $inArr['deptNameF'] = $deptRow['deptNameF'];
                        $inArr['deptCodeF'] = $deptRow['deptCodeF'];
                        $inArr['deptIdF'] = $deptRow['deptIdF'];
					}

					//Ա��ְλ
					if(!empty($val['jobName'])) {
						if(!isset($jobsArr[$val['jobName']])) {
							$rs = $otherDataDao->getJobId_d($val['jobName']);
							if(!empty($rs)){
								$jobsArr[$val['jobName']] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա��ְλ</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['jobName'] = $val['jobName'];
						$inArr['jobId'] = $jobsArr[$val['jobName']];
					}

					//����
					if(!empty($val['regionName'])) {
						$inArr['regionId'] = $this->get_table_fields('area', "Name='".$val['regionName']."'", 'ID');
						$inArr['regionName'] = $val['regionName'];
					}

					//Ա��״̬
					if(!empty($val['staffStateName'])) {
						if(!isset($datadictArr[$val['staffStateName']])){
							if($val['staffStateName'] == '��ְ' || $val['staffStateName'] == '����'
									|| $val['staffStateName'] == '����' || $val['staffStateName'] == 'Э�̽��'
									|| $val['staffStateName'] == '�����ڴ���' || $val['staffStateName'] == '��ͬ���ڹ�˾����'
									|| $val['staffStateName'] == '���ݹ�˾����' || $val['staffStateName'] == '����Ա������'
									|| $val['staffStateName'] == '��ͬ����Ա������') {
								$rs = $datadictDao->getCodeByName('YGZTLZ' ,$val['staffStateName']);
								$employeesState = 'YGZTLZ';
								$employeesStateName = '��ְ';
							} else if ($val['staffStateName'] == '����' || $val['staffStateName'] == '��ת��'
									|| $val['staffStateName'] == '����' || $val['staffStateName'] == '������'
									|| $val['staffStateName'] == '��������' || $val['staffStateName'] == 'ʵϰ��') {
								$rs = $datadictDao->getCodeByName('YGZTZZ' ,$val['staffStateName']);
								$employeesState = 'YGZTZZ';
								$employeesStateName = '��ְ';
							}
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['staffStateName']]['code'] = $rs;
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա��״̬</font>';
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

					//Ա������
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

					//��λ����
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

					//��Ա����
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

					//���ʼ���
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

					//ְ��
					if(!empty($val['jobLevel'])) {
						$inArr['jobLevel'] = $val['jobLevel'];
					}

					//ְ��
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

					//����״��
					if(!empty($val['healthState'])) {
						if($val['healthState'] == '����' || $val['healthState'] == 'һ��' || $val['healthState'] == '��') {
							$inArr['healthState'] = $val['healthState'];
						}
					}

					//�Ƿ��м�����ʷ
					if(!empty($val['isMedicalHistory'])) {
						if($val['isMedicalHistory'] == '��' || $val['isMedicalHistory'] == '��') {
							$inArr['isMedicalHistory'] = $val['isMedicalHistory'];
						}
					}

					//������ʷ
					if(!empty($val['medicalHistory'])) {
						$inArr['medicalHistory'] = $val['medicalHistory'];
					}

					//��Ⱦ����
					if(!empty($val['InfectDiseases'])) {
						$inArr['InfectDiseases'] = $val['InfectDiseases'];
					}

					//���
					if(!empty($val['height'])){
						$inArr['height'] = $val['height'];
					}

					//����
					if(!empty($val['weight'])) {
						$inArr['weight'] = $val['weight'];
					}

					//Ѫ��
					if(!empty($val['blood'])) {
						$inArr['blood'] = $val['blood'];
					}

					//����״��
					if(!empty($val['maritalStatusName'])){
						if($val['maritalStatusName'] == '�ѻ�' || $val['maritalStatusName'] == 'δ��') {
							$inArr['maritalStatusName'] = $val['maritalStatusName'];
						}
					}

					//����״��
					if(!empty($val['birthStatus'])) {
						if($val['birthStatus'] == '����' || $val['birthStatus'] == 'δ��') {
							$inArr['birthStatus'] = $val['birthStatus'];
						}
					}

					//����
					if(!empty($val['hobby'])) {
						$inArr['hobby'] = $val['hobby'];
					}

					//�س�
					if(!empty($val['speciality'])) {
						$inArr['speciality'] = $val['speciality'];
					}

					//רҵ����
					if(!empty($val['professional'])) {
						$inArr['professional'] = $val['professional'];
					}

					//���ÿ���
					if(!empty($val['oftenCardNum'])) {
						$inArr['oftenCardNum'] = $val['oftenCardNum'];
					}

					//�����˺�
					if(!empty($val['oftenAccount'])) {
						$inArr['oftenAccount'] = $val['oftenAccount'];
					}

					//���ÿ�����
					if(!empty($val['oftenBank'])) {
						$inArr['oftenBank'] = $val['oftenBank'];
					}

					//����
					if(!empty($val['bankCardNum'])) {
						$inArr['bankCardNum'] = $val['bankCardNum'];
					}

					//�˺�
					if(!empty($val['accountNumb'])) {
						$inArr['accountNumb'] = $val['accountNumb'];
					}

					//������
					if(!empty($val['openingBank'])) {
						$inArr['openingBank'] = $val['openingBank'];
					}

					//ְ��
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

					//�������
					if(!empty($val['archivesCode'])) {
						$inArr['archivesCode'] = $val['archivesCode'];
					}

					//�������ڵ�
					if(!empty($val['archivesLocation'])) {
						$inArr['archivesLocation'] = $val['archivesLocation'];
					}

					//������(ʡ)
					if(!empty($val['residencePro'])) {
						$inArr['residencePro'] = $val['residencePro'];
					}

					//������(��)
					if(!empty($val['residenceCity'])) {
						$inArr['residenceCity'] = $val['residenceCity'];
					}

					//��������
					if(!empty($val['householdType'])) {
						if($val['householdType'] == '����' || $val['householdType'] == 'ũҵ') {
							$inArr['householdType'] = $val['householdType'];
						}
					}

					//���廧��
					if(!empty($val['collectResidence'])) {
						if($val['collectResidence'] == '��' || $val['collectResidence'] == '��') {
							$inArr['collectResidence'] = $val['collectResidence'];
						}
					}

					//�籣�����
					if(!empty($val['socialPlace'])) {
						$inArr['socialPlace'] = $val['socialPlace'];
					}

					//�Ƿ���Ҫ��ʦ
					if(!empty($val['isNeedTutor'])) {
						if($val['isNeedTutor'] == '��') {
							$inArr['isNeedTutor'] = '1';
						}
					}

					//�����ȼ�
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
						$tempArr['result'] = '����ɹ�';
					} else {
						$tempArr['result'] = '����ʧ��';
					}
					$tempArr['docCode'] = '��' . $actNum .'������';
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
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$inArr = array();//��������
		$userArr = array();//�û�����
		$deptArr = array();//��������
		$jobsArr = array();//ְλ����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		$logSettringDao = new model_syslog_setting_logsetting ();
		$deptDao=new model_deptuser_dept_dept();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");

			if(is_array($excelData)) {

				$dataArr = array(); //��ȡ��������
				$keyName = array(
					'userNo', //Ա�����
					'staffName', //Ա������
					'englishName', //��������Ӣ��
					'sex', //�Ա�
					'birthdate', //��������
					'age', //����
					'nativePlacePro', //����ʡ
					'nativePlaceCity', //������
					'nation', //����
					'identityCard', //���֤��
					'identityCardDate0', //���֤��Ч���ڣ���ʼ��
					'identityCardDate1', //���֤��Ч���ڣ�������
					'identityCardAddress', //���֤�ϵ�ַ
					'politicsStatus', //������ò
					'highEducationName', //���ѧ��
					'highSchool', //��ҵѧУ
					'professionalName', //רҵ
					'graduateDate', //��ҵʱ��
					'companyType', //��˾����
					'companyName', //��˾����
					'belongDeptName', //��������
					'jobName', //ְλ
					'regionName', //����
					'staffStateName', //Ա��״̬
					'personnelTypeName', //Ա������
					'positionName', //��λ����
					'personnelClassName', //��Ա����
					'wageLevelName', //���ʼ���
					'jobLevel', //ְ��
					'functionName', //ְ��
					'healthState', //�������
					'isMedicalHistory', //�Ƿ��м�����ʷ
					'medicalHistory', //������ʷ
					'InfectDiseases', //��Ⱦ����
					'height', //���
					'weight', //����
					'blood', //Ѫ��
					'maritalStatusName', //����״��
					'birthStatus', //����״��
					'hobby', //����
					'speciality', //�س�
					'professional', //רҵ����
					'oftenCardNum', //���ÿ���
					'oftenAccount', //�����˺�
					'oftenBank', //���ÿ�����
					'bankCardNum', //����
					'accountNumb', //�˺�
					'openingBank', //������
					'salaryAreaCode', //�����˺ŵ�������
					'archivesCode', //�������
					'archivesLocation', //�������ڶ�
					'residencePro', //����ʡ
					'residenceCity', //������
					'householdType', //��������
					'collectResidence', //���廧��
					'socialPlace', //�籣�����
					'isNeedTutor', //�Ƿ���Ҫ��ʦ
					'socialBuyer', //�籣����
					'fundPlace', //���������
					'fundBuyer', //��������
					'fundCardinality', //������ɷѻ���
					'fundProportion', //������ɷѱ���
					'outsourcingSupp', //�����˾
					'outsourcingName', //�������
					'personLevel', //�����ȼ�
					'officeName', //��������
					'eprovince', //���߲���ʡ��
					'ecity', //���߲�������
					'technologyName', //��������
					'networkName', //����
					'deviceName' //�豸���Ҽ�����
				);
				foreach ($excelData as $key => $val) {
					if ($key > 1 && !empty($val[0])) { //�Ǳ�ͷ��ǰ�����ֶβ�Ϊ��
						$data = array();
						foreach ($keyName as $k => $v) {
							$data[$v] = trim($val[$k]);
						}
						array_push($dataArr ,$data);
					}
				}

				//������ѭ��
				foreach($dataArr as $key => $val){
					$inArr = array(); //��������
					$actNum = $key + 1;

					//Ա�����
					if(!empty($val['userNo'])) {
						$id = $this->get_table_fields($this->tbl_name, "userNo='".$val['userNo']."'", 'id');
						if(!$id > 0) {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��Ա��������Ϣ������</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
						$inArr['id'] = $id;
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<font color=red>����ʧ��!Ա�����Ϊ��</font>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//Ա������
					if(!empty($val['staffName'])) {
						$staffName = $this->get_table_fields($this->tbl_name ,"userNo='".$val['userNo']."'" ,'staffName');
						$userName = $this->get_table_fields($this->tbl_name ,"userNo='".$val['userNo']."'" ,'userName');
						if($val['staffName'] != trim($staffName) && $val['staffName'] != trim($userName)) {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!Ա��������ƥ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
						$inArr['staffName'] = $val['staffName'];
					}

					//��������Ӣ��
					if(!empty($val['englishName'])) {
						$inArr['englishName'] = $val['englishName'];
					}

					//�Ա�
					if(!empty($val['sex']) && ($val['sex'] == '��' || $val['sex'] == 'Ů')) {
						$inArr['sex'] = $val['sex'];
					}

					//��������
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

					//����
					if(!empty($val['age'])) {
						$inArr['age'] = $val['age'];
					}

					//���ᣨʡ��
					if(!empty($val['nativePlacePro'])) {
						$inArr['nativePlacePro'] = $val['nativePlacePro'];
					}

					//���ᣨ�У�
					if(!empty($val['nativePlaceCity'])) {
						$inArr['nativePlaceCity'] = $val['nativePlaceCity'];
					}

					//����
					if(!empty($val['nation'])) {
						$inArr['nation'] = $val['nation'];
					}

					//���֤��
					if(!empty($val['identityCard'])) {
						$inArr['identityCard'] = $val['identityCard'];
					}

					//���֤��Ч���ڿ�ʼ
					if(!empty($val['identityCardDate0']) && $val['identityCardDate0'] != '0000-00-00') {
						$identityCardDate0 = '';
						if(!is_numeric($val['identityCardDate0'])) {
							$identityCardDate0 = str_replace('-' ,'.' ,$val['identityCardDate0']); //ת����ʽ
						} else {
							$identityCardDate0 = date('Y.m.d' ,(mktime(0 ,0 ,0 ,1 ,$val['identityCardDate0'] - 1 ,1900)));
							if($identityCardDate0 == '1970.01.01') {
								$identityCardDate0 = date('Y.m.d' ,strtotime($val['identityCardDate0']));
							}
						}
					}

					//���֤��Ч���ڽ���
					if(!empty($val['identityCardDate1']) && $val['identityCardDate1'] != '0000-00-00') {
						$identityCardDate1 = '';
						if(!is_numeric($val['identityCardDate1'])) {
							$identityCardDate1 = str_replace('-' ,'.' ,$val['identityCardDate1']); //ת����ʽ
						} else {
							$identityCardDate1 = date('Y.m.d' ,(mktime(0 ,0 ,0 ,1 ,$val['identityCardDate1'] - 1 ,1900)));
							if($identityCardDate1 == '1970.01.01') {
								$identityCardDate1 = date('Y.m.d' ,strtotime($val['identityCardDate1']));
							}
						}
					}

					//���֤��Ч����ƴ��
					if (!empty($identityCardDate0) && !empty($identityCardDate1)) {
						$inArr['identityCardDate'] = $identityCardDate0.'-'.$identityCardDate1;
					}

					//������ò
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

					//���ѧ��
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

					//��ҵѧУ
					if(!empty($val['highSchool'])) {
						$inArr['highSchool'] = $val['highSchool'];
					}

					//רҵ
					if(!empty($val['professionalName'])) {
						$inArr['professionalName'] = $val['professionalName'];
					}

					//��ҵʱ��
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

					//��˾����
					if(!empty($val['companyType'])) {
						if($val['companyType'] == '�ӹ�˾') {
							$inArr['companyTypeCode'] = 0;
						} else {
							$inArr['companyTypeCode'] = 1;
						}
						$inArr['companyType'] = $val['companyType'];
					}

					//��˾����
					if(!empty($val['companyName'])) {
						$inArr['companyId'] = $this->get_table_fields('branch_info', "NameCN='".$val['companyName']."'", 'ID');
						$inArr['companyName'] = $val['companyName'];
					}

					//��������
					if(!empty($val['belongDeptName'])) {
						if(!isset($deptArr[$val['belongDeptName']])) {
							$rs = $otherDataDao->getDeptInfo_d($val['belongDeptName']);
							if(!empty($rs)){
								$deptArr['DEPT_ID'] = $rs['DEPT_ID'];
								$deptArr['Depart_x'] = $rs['Depart_x'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĲ���</font>';
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
						//��������
						$inArr['deptCodeS'] = $deptRow['deptCodeS'];
						$inArr['deptNameS'] = $deptRow['deptNameS'];
						$inArr['deptIdS'] = $deptRow['deptIdS'];
						//��������
						$inArr['deptNameT'] = $deptRow['deptNameT'];
						$inArr['deptCodeT'] = $deptRow['deptCodeT'];
						$inArr['deptIdT'] = $deptRow['deptIdT'];
                        //�ļ�����
                        $inArr['deptNameF'] = $deptRow['deptNameF'];
                        $inArr['deptCodeF'] = $deptRow['deptCodeF'];
                        $inArr['deptIdF'] = $deptRow['deptIdF'];
                        if(empty($val['jobName'])){
                            $val['jobName'] = $this->get_table_fields($this->tbl_name, "userNo='".$val['userNo']."'", 'jobName');
                        }
					}

					//Ա��ְλ
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
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�ò��Ų����ڴ�ְλ</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['jobName'] = $val['jobName'];
						$inArr['jobId'] = $jobsArr[$val['jobName']];
					}

					//����
					if(!empty($val['regionName'])) {
						$inArr['regionId'] = $this->get_table_fields('area', "Name='".$val['regionName']."'", 'ID');
						$inArr['regionName'] = $val['regionName'];
					}

					//Ա��״̬
					if(!empty($val['staffStateName'])) {
						if(!isset($datadictArr[$val['staffStateName']])){
							if($val['staffStateName'] == '��ְ' || $val['staffStateName'] == '����'
									|| $val['staffStateName'] == '����' || $val['staffStateName'] == 'Э�̽��'
									|| $val['staffStateName'] == '�����ڴ���' || $val['staffStateName'] == '��ͬ���ڹ�˾����'
									|| $val['staffStateName'] == '���ݹ�˾����' || $val['staffStateName'] == '����Ա������'
									|| $val['staffStateName'] == '��ͬ����Ա������') {
								$rs = $datadictDao->getCodeByName('YGZTLZ' ,$val['staffStateName']);
								$employeesState = 'YGZTLZ';
								$employeesStateName = '��ְ';
							} else if ($val['staffStateName'] == '����' || $val['staffStateName'] == '��ת��'
									|| $val['staffStateName'] == '����' || $val['staffStateName'] == '������'
									|| $val['staffStateName'] == '��������' || $val['staffStateName'] == 'ʵϰ��') {
								$rs = $datadictDao->getCodeByName('YGZTZZ' ,$val['staffStateName']);
								$employeesState = 'YGZTZZ';
								$employeesStateName = '��ְ';
							}
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val['staffStateName']]['code'] = $rs;
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա��״̬</font>';
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

					//Ա������
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

					//��λ����
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

					//��Ա����
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

					//���ʼ���
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

					//ְ��
					if(!empty($val['jobLevel'])) {
						$inArr['jobLevel'] = $val['jobLevel'];
					}

					//ְ��
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

					//����״��
					if(!empty($val['healthState'])) {
						if($val['healthState'] == '����' || $val['healthState'] == 'һ��' || $val['healthState'] == '��') {
							$inArr['healthState'] = $val['healthState'];
						}
					}

					//�Ƿ��м�����ʷ
					if(!empty($val['isMedicalHistory'])) {
						if($val['isMedicalHistory'] == '��' || $val['isMedicalHistory'] == '��') {
							$inArr['isMedicalHistory'] = $val['isMedicalHistory'];
						}
					}

					//������ʷ
					if(!empty($val['medicalHistory'])) {
						$inArr['medicalHistory'] = $val['medicalHistory'];
					}

					//��Ⱦ����
					if(!empty($val['InfectDiseases'])) {
						$inArr['InfectDiseases'] = $val['InfectDiseases'];
					}

					//���
					if(!empty($val['height'])){
						$inArr['height'] = $val['height'];
					}

					//����
					if(!empty($val['weight'])) {
						$inArr['weight'] = $val['weight'];
					}

					//Ѫ��
					if(!empty($val['blood'])) {
						$inArr['blood'] = $val['blood'];
					}

					//����״��
					if(!empty($val['maritalStatusName'])){
						if($val['maritalStatusName'] == '�ѻ�' || $val['maritalStatusName'] == 'δ��') {
							$inArr['maritalStatusName'] = $val['maritalStatusName'];
						}
					}

					//����״��
					if(!empty($val['birthStatus'])) {
						if($val['birthStatus'] == '����' || $val['birthStatus'] == 'δ��') {
							$inArr['birthStatus'] = $val['birthStatus'];
						}
					}

					//����
					if(!empty($val['hobby'])) {
						$inArr['hobby'] = $val['hobby'];
					}

					//�س�
					if(!empty($val['speciality'])) {
						$inArr['speciality'] = $val['speciality'];
					}

					//רҵ����
					if(!empty($val['professional'])) {
						$inArr['professional'] = $val['professional'];
					}

					//���ÿ���
					if(!empty($val['oftenCardNum'])) {
						$inArr['oftenCardNum'] = $val['oftenCardNum'];
					}

					//�����˺�
					if(!empty($val['oftenAccount'])) {
						$inArr['oftenAccount'] = $val['oftenAccount'];
					}

					//���ÿ�����
					if(!empty($val['oftenBank'])) {
						$inArr['oftenBank'] = $val['oftenBank'];
					}

					//����
					if(!empty($val['bankCardNum'])) {
						$inArr['bankCardNum'] = $val['bankCardNum'];
					}

					//�˺�
					if(!empty($val['accountNumb'])) {
						$inArr['accountNumb'] = $val['accountNumb'];
					}

					//������
					if(!empty($val['openingBank'])) {
						$inArr['openingBank'] = $val['openingBank'];
					}

					//ְ��
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

					//�������
					if(!empty($val['archivesCode'])) {
						$inArr['archivesCode'] = $val['archivesCode'];
					}

					//�������ڵ�
					if(!empty($val['archivesLocation'])) {
						$inArr['archivesLocation'] = $val['archivesLocation'];
					}

					//������(ʡ)
					if(!empty($val['residencePro'])) {
						$inArr['residencePro'] = $val['residencePro'];
					}

					//������(��)
					if(!empty($val['residenceCity'])) {
						$inArr['residenceCity'] = $val['residenceCity'];
					}

					//��������
					if(!empty($val['householdType'])) {
						if($val['householdType'] == '����' || $val['householdType'] == 'ũҵ') {
							$inArr['householdType'] = $val['householdType'];
						}
					}

					//���廧��
					if(!empty($val['collectResidence'])) {
						if($val['collectResidence'] == '��' || $val['collectResidence'] == '��') {
							$inArr['collectResidence'] = $val['collectResidence'];
						}
					}

					//�籣�����
					if(!empty($val['socialPlace'])) {
						$inArr['socialPlace'] = $val['socialPlace'];
					}

					//�Ƿ���Ҫ��ʦ
					if(!empty($val['isNeedTutor'])) {
						if($val['isNeedTutor'] == '��') {
							$inArr['isNeedTutor'] = '1';
						}
					}

					//�籣����
					if(!empty($val['socialBuyer'])) {
						$inArr['socialBuyer'] = $val['socialBuyer'];
					}

					//���������
					if(!empty($val['fundPlace'])) {
						$inArr['fundPlace'] = $val['fundPlace'];
					}

					//��������
					if(!empty($val['fundBuyer'])) {
						$inArr['fundBuyer'] = $val['fundBuyer'];
					}

					//������ɷѻ���
					if(!empty($val['fundCardinality'])) {
						$inArr['fundCardinality'] = $val['fundCardinality'];
					}

					//������ɷѱ���
					if(!empty($val['fundProportion'])) {
						$inArr['fundProportion'] = $val['fundProportion'];
					}

					//�����˾
					if(!empty($val['fundProportion'])) {
						$inArr['outsourcingSupp'] = $val['fundProportion'];
					}

					//�������
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

					//�����ȼ�
					if(!empty($val['personLevel'])) {
						$personLevelId = $this->get_table_fields('oa_hr_level', "personLevel='".$val['personLevel']."'", 'id');
						if($personLevelId) {
							$inArr['personLevelId'] = $personLevelId;
						} else {
							$inArr['personLevelId'] ='';
						}
						$inArr['personLevel'] = $val['personLevel'];
					}

					//��������
					if(!empty($val['officeName'])) {
						$personLevelId = $this->get_table_fields('oa_esm_office_baseinfo', "officeName='".$val['officeName']."'", 'id');
						if($personLevelId){
							$inArr['officeId'] = $personLevelId;
						} else {
							$inArr['officeId'] = '';
						}
						$inArr['officeName'] = $val['officeName'];
					}

					//�޲�������(ʡ)
					if(!empty($val['eprovince'])) {
						$eprovinceId = $this->get_table_fields('oa_system_province_info', "provinceName='".$val['eprovince']."'", 'id');
						if($eprovinceId) {
							$inArr['eprovinceId'] = $eprovinceId;
						} else {
							$inArr['eprovinceId'] ='';
						}
						$inArr['eprovince'] = $val['eprovince'];
					}

					//�޲�������(��)
					if(!empty($val['ecity'])) {
						$ecityId = $this->get_table_fields('oa_system_city_info', "cityName='".$val['ecity']."'", 'id');
						if($ecityId){
							$inArr['ecityId'] = $ecityId;
						} else {
							$inArr['ecityId'] ='';
						}
						$inArr['ecity'] = $val['ecity'];
					}

					//��������
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

					//����
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

					//�豸���Ҽ�����
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

					//��������
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
					//ͬ�����¾�ϵͳ�еĵ�����Ϣ
					$this->updateOldInfo_d($inArr['id'],'edit');
					if($newId) {
						$tempArr['result'] = '����ɹ�';
					} else {
						$tempArr['result'] = '����ʧ��';
					}
					$tempArr['docCode'] = '��' . $actNum .'������';
					array_push($resultArr ,$tempArr);
				}
				return $resultArr;
			}
		}
	}

	/**
	 * ������ϵ��Ϣ
	 */
	function addContactExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$inArr = array();//��������
		$userArr = array();//�û�����
		$deptArr = array();//��������
		$jobsArr = array();//ְλ����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$im = new includes_class_ImInterface();
		$logSettringDao = new model_syslog_setting_logsetting ();
//		$datadictArr = array();//�����ֵ�����
//		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1])){
						continue;
					}else{
						//��������
						$inArr = array();

						//Ա�����
						if(!empty($val[0])&&trim($val[0])!=''){
							$val[0]=trim($val[0]);
							if(!isset($userArr[$val[0]])){
								$rs=$this->get_table_fields($this->tbl_name, "userNo='".$val[0]."'", 'id');
								if(!empty($rs)&&$rs>0){
									$inArr['id'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա����Ż��Ա��δ���л�����Ϣ��¼��</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
//							$inArr['userNo'] = $val[0];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!Ա�����Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//Ա������
						if(!empty($val[1])&&trim($val[1])!=''){
							if(!isset($userArr[$val[1]])){
								$rs = $otherDataDao->getUserInfo($val[1]);
								if(!empty($rs)){
									$userArr[$val[1]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա������</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}

//							$inArr['userAccount'] = $userArr[$val[1]]['USER_ID'];
//							$inArr['userName'] = $val[1];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!û��Ա������</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//�̶��绰
						if(!empty($val[2])&&trim($val[2])!=''){
							$inArr['telephone'] = trim( $val[2]);
						}

						//�ƶ��绰
						if(!empty($val[3])&&trim($val[3])!=''){
							$inArr['mobile'] = trim( $val[3]);
						}

						//��������
						if(!empty($val[4])&&trim($val[4])!=''){
							$inArr['personEmail'] = trim( $val[4]);
						}

						//��˾����
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

						//����
						if(!empty($val[8])&&trim($val[8])!=''){
							$inArr['fetion'] = trim( $val[8]);
						}

						//������ϵ��ʽ
						if(!empty($val[9])&&trim($val[9])!=''){
							$inArr['information'] =trim( $val[9]);
						}


						//��ͥ�绰
						if(!empty($val[10])&&trim($val[10])!=''){
							$inArr['homePhone'] = trim( $val[10]);
						}

						//������ϵ��
						if(!empty($val[11])&&trim($val[11])!=''){
							$inArr['emergencyName'] = trim( $val[11]);
						}

						//������ϵ�˵绰
						if(!empty($val[12])&&trim($val[12])!=''){
							$inArr['emergencyTel'] = trim( $val[12]);
						}

						//��ϵ
						if(!empty($val[13])&&trim($val[13])!=''){
							$inArr['emergencyRelation'] = trim( $val[13]);
						}

						//��ס��ַʡ
						if(!empty($val[14])&&trim($val[14])!=''){
							$inArr['nowPlacePro'] = trim( $val[14]);
						}


						//��ס��ַ��
						if(!empty($val[15])&&trim($val[15])!=''){
							$inArr['nowPlaceCity'] = trim( $val[15]);
						}

						//��ס��ַ��ϸ��ַ
						if(!empty($val[16])&&trim($val[16])!=''){
							$inArr['nowAddress'] = trim( $val[16]);
						}

						//��ס��ַ��������
						if(!empty($val[17])&&trim($val[17])!=''){
							$inArr['nowPost'] = trim( $val[17]);
						}

						//��ͥ��ϸ��ַʡ
						if(!empty($val[18])&&trim($val[18])!=''){
							$inArr['homeAddressPro'] = trim( $val[18]);
						}


						//��ͥ��ϸ��ַ��
						if(!empty($val[19])&&trim($val[19])!=''){
							$inArr['homeAddressCity'] = trim( $val[19]);
						}

						//��ͥ��ϸ��ַ��ϸ��ַ
						if(!empty($val[20])&&trim($val[20])!=''){
							$inArr['homeAddress'] = trim( $val[20]);
						}

						//��ͥ��ϸ��ַ��������
						if(!empty($val[21])&&trim($val[21])!=''){
							$inArr['homePost'] =trim( $val[21]);
						}

						//��λ��ϵ��ʽ��λ�绰
						if(!empty($val[22])&&trim($val[22])!=''){
							$inArr['unitPhone'] =trim( $val[22]);
						}

						//��λ��ϵ��ʽ�ֻ���
						if(!empty($val[23])&&trim($val[23])!=''){
							$inArr['extensionNum'] =trim( $val[23]);
						}

						//��λ��ϵ��ʽ��λ����
						if(!empty($val[24])&&trim($val[24])!=''){
							$inArr['unitFax'] =trim( $val[24]);
						}

						//��λ��ϵ��ʽ�ֻ�
						if(!empty($val[25])&&trim($val[25])!=''){
							$inArr['mobilePhone'] =trim( $val[25]);
						}

						//��λ��ϵ��ʽ�̺�
						if(!empty($val[26])&&trim($val[26])!=''){
							$inArr['shortNum'] =trim( $val[26]);
						}

						//��λ��ϵ��ʽ�����ֻ�
						if(!empty($val[27])&&trim($val[27])!=''){
							$inArr['otherPhone'] =trim( $val[27]);
						}

						//��λ��ϵ��ʽ��������
						if(!empty($val[28])&&trim($val[28])!=''){
							$inArr['otherPhoneNum'] =trim( $val[28]);
						}

//						print_r($inArr);
						$oldObj = $this->get_d ( $inArr ['id'] );
						$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $inArr );
						$newId = parent::edit_d($inArr,true);
						if($newId){
							//����ͨѶ¼
							$LOG_NAME=$this->get_table_fields('user', "USER_ID='".$oldObj['userAccount']."'", 'LogName');
							$im->edit_userInfo($LOG_NAME, array (
								'Col_o_Phone' => $inArr['extensionNum']?$inArr['extensionNum'].'('.$inArr['unitPhone'].')':$inArr['unitPhone'],
								'Col_Mobile' => $inArr['shortNum']?$inArr['shortNum'].'('.$inArr['mobilePhone'].')':$inArr['mobilePhone']
								));
							$tempArr['result'] = '����ɹ�';
						}else{
							$tempArr['result'] = '<font color=red>����ʧ��</font>';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push($resultArr ,$tempArr);
					}

				}
				return $resultArr;
			}
		}
	}

	/**
	 * ��������ְ��Ϣ
	 *
	 */
	function addInleaveExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$inArr = array();//��������
		$userArr = array();//�û�����
		$deptArr = array();//��������
		$jobsArr = array();//ְλ����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
					if($key === 0){
						continue ;
					}
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1])){
						continue;
					}else{
						//��������
						$inArr = array();

						//Ա�����
						if(!empty($val[0])){
							$val[0]=trim($val[0]);
							if(!isset($userArr[$val[0]])){
								$rs=$this->get_table_fields($this->tbl_name, "userNo='".$val[0]."'", 'id');
								if(!empty($rs)&&$rs>0){
									$inArr['id'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա����Ż��Ա��δ���л�����Ϣ��¼��</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
//							$inArr['userNo'] = $val[0];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!Ա�����Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//Ա������
						if(!empty($val[1])){
							if(!isset($userArr[$val[1]])){
								$rs = $otherDataDao->getUserInfo($val[1]);
								if(!empty($rs)){
									$userArr[$val[1]] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�Ա������</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}

//							$inArr['userAccount'] = $userArr[$val[1]]['USER_ID'];
//							$inArr['userName'] = $val[1];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!û��Ա������</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//��ְ����
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

						//��ְ�ص�
						if(!empty($val[3])&&trim($val[3])!=''){
							$inArr['entryPlace'] =trim($val[3]);
						}
						//Ԥ��ת��
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

						//ʵ��ת������
						if(!empty($val[5])&&trim($val[5])!=''){
							$val[5] = trim($val[5]);
							$inArr['realBecomeDate'] = $val[5];
						}

						//ת������
						if(!empty($val[6])&&trim($val[6])!=''){
							$inArr['becomeFraction'] = trim($val[6]);
						}


						//��ְ����
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


						//��ְ����
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


						//��ְԭ��
						if(!empty($val[9])&&trim($val[9])!=''){
							$inArr['quitReson'] =trim($val[9]);
						}

						//��ְ��̸��¼
						if(!empty($val[10])&&trim($val[10])!=''){
							$inArr['quitInterview'] = trim($val[10]);
						}


//						print_r($inArr);
						$newId =parent::edit_d($inArr,true);
						if($newId){
							$tempArr['result'] = '����ɹ�';
						}else{
							$tempArr['result'] = '����ʧ��';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push($resultArr ,$tempArr);
					}
				}
				return $resultArr;
			}
		}
	}

	/**
	 * ����Ա������
	 */
	function importStaffFile(){
		$resultArr = array();//�������
		$managementDao=new model_file_uploadfile_management();
		//��ȡ�ļ����µ��ļ���ѭ�����븽����
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
		$i=0;//����ѭ���й���������
		//��ʼ������������ɹ������е���ʧ�ܣ����Ḳ�ǳ�ʼ����¼
		$resultArr[$i]['docCode']="���и���";
		$resultArr[$i]['result']="����ɹ�";
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				$rootFile=scandir ( $dir );
				foreach($rootFile as  $key=>$val){
					if($val != "." && $val != ".."&&is_dir($dir.$val)){
						$temp = scandir ( $dir.$val ); // �г�ָ��·���е��ļ���Ŀ¼
						if(is_array($temp)){
							foreach($temp as $k=>$v){
								if($v != "." && $v != ".."&&$v!='Thumbs.db'){
			        				preg_match_all('/\d+/', $val, $userNo);//���˳��ļ����е����ּ���Ա���
									//�жϸ����ļ�����������Ա����Ƿ�ƥ��
			        				if($map[$userNo[0][0]]!=null){
			        					$source=$dir.$val."/".$v;
				            			$dest=$destDir.$val."-".$v;//��֤����Ψһ
				            			copy ($source,$dest);
				            			//���븽����
				            			$fileArr['serviceType']="personnel_staff";
				            			$fileArr['serviceId']=$map[$userNo[0][0]];
				            			$fileArr['originalName']=$v;
				            			$fileArr['newName']=$val."-".$v;
				            			$fileArr['uploadPath']=$destDir;
				            			$fileArr['tFileSize']=filesize($dest);
				            			$test = $managementDao->add_d ( $fileArr, true );
				            		}else{
				            			$resultArr[$i]['docCode']=$val.'/'.$v;
				            			$resultArr[$i]['result']='<font color=red>����ʧ�ܣ������ڵ�Ա����Ż��Ա��δ���л�����Ϣ��¼�롣</font>';
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
	/*****************************��������*****************************/


	/*
	 * Ա���ȼ���Ϣ����
	 *
	 */
	function degreeExcelOut($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//����һ��Excel������
		//		$objPhpExcelFile = new PHPExcel();


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/����-Ա���ȼ���Ϣ����ģ��.xls" ); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '��Ϣ�б�' ) );
		//���ñ�ͷ����ʽ ����
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
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
		}

		//�������
		ob_end_clean (); //��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "Ա���ȼ���Ϣ��������.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}



	/*
	 * ����excel
	 */
	function excelOutAll($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//����һ��Excel������
		$objPhpExcelFile = new PHPExcel();

		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/��Ա�����˶����ݵ���.xls" ); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '��Ϣ�б�' ) );
		//���ñ�ͷ����ʽ ����
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
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
		}

		//�������
		ob_end_clean (); //��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "���µ�����������.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}



	/*
	 * ��ϵ��Ϣ����
	 *
	 */
	function contactExcelOut($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//����һ��Excel������
		//		$objPhpExcelFile = new PHPExcel();


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/����-��ϵ��Ϣ����ģ��.xls" ); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '��Ϣ�б�' ) );
		//���ñ�ͷ����ʽ ����
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
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
		}

		//�������
		ob_end_clean (); //��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "��ϵ��Ϣ��������.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}



	/******************* E ���뵼��ϵ�� ************************/

	/**
	 * ��ȡĳ�������������������µ���Ա
	 * add by chengl
	 */
	function getPersonnelsByDeptId($deptId){
		$this->searchArr['deptId']=$deptId;
		return $this->list_d();
	}

	/**
	 * �����˺Ż�ȡ�Ƿ����ƶ���ʦ
	 */
	function getTutorBystuId($stuId){
		$sql="select id from oa_hr_tutor_records where studentAccount = '".$stuId."'";
		return $this->_db->getArray($sql);
	}

    /**
     * ������Ա��Ϣ�б�
     */
    function listEngineering_d($searchDate,$employeesState,$rtSql = false){
        // Ȩ�޻�ȡ
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
			$_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		$sysLimit = $sysLimit['����Ȩ��'];
        // ��ȡȨ��ʡ��
    	$managerDao = new model_engineering_officeinfo_manager();
    	$provinceStr = $managerDao->getProvinceByUser_d($_SESSION['USER_ID']);
		// ��ȡ����Ȩ��
		$officeDao = new model_engineering_officeinfo_officeinfo();
		$officeStr = $officeDao->getOfficeIds_d($_SESSION['USER_ID']);
		$personSql = "";

		//���´� �� ȫ�� ����
		if (strstr($sysLimit, ';;')) {
			$personSql = "";
		} elseif (!empty($sysLimit) && empty($provinceStr) && empty($officeStr)) {//���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
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

		//������Ա״̬ʱ������
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
     * ��ȡĬ�ϲ���
     */
	function getDefaultDept_d() {
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
			$_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		$deptLimit = $sysLimit['����Ȩ��'];
		$deptLimit = str_replace(';', '', $deptLimit);

        // ���⿪��ȫ��Ȩ�޵�ͬ���ֵ����������ŵ�,����� ��;;�� �ַ������˵���,������һ�� ��,�� ���²�ѯ������ PMS 2780
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
	 * ����Ȩ�޹���
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
	 * ����ְλ����
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


	//���ʽӿ�
	function getPersonInfoByUserId($userId){
		$row=$this->getPersonnelInfo_d($userId);
		$returnArr=array();
		if(!empty($row)){
			$returnArr['entryDate']=$row['entryDate'];//��ְʱ��
			$returnArr['becomeDate']=$row['becomeDate'];//Ԥ��ת��ʱ��
			$returnArr['realBecomeDate']=$row['realBecomeDate'];//ʵ��ת������
			$returnArr['oftenCardNum']=$row['oftenCardNum'];//���ÿ���
			$returnArr['oftenAccount']=$row['oftenAccount'];//�����˺�
			$returnArr['oftenBank']=$row['oftenBank'];//�����˺ſ�����
			//ʵ��ת������
			$returnArr['afterSalary']=$this->get_table_fields('oa_hr_permanent_examine', "userNo='".$row['userNo']."'", 'afterSalary');
			//�����ڻ�������
			$entryNoticeDao=new model_hr_recruitment_entryNotice();
			$entryNoticeRow=$entryNoticeDao->getAllInfoByUserID_d($userId);
			if($entryNoticeRow['deptId']=='155'){//������ʵϰ������
				$returnArr['beforeSalary']=$entryNoticeRow['internshipSalary'];
			}else{
				$returnArr['beforeSalary']=$entryNoticeRow['useTrialWage'];
			}
		}
		return $returnArr;
	}

	/**
	 * ��дget_d
	 */
	function get_d($id) {
		$condition = array ("id" => $id );
		$obj = $this->find ( $condition );
		$obj['age'] = $this->getAge($obj['birthdate']);//��������
		return $obj;
	}

	/**
	 * ��������
	 * �Խ�����ݿ����䲻׼������
	 * @param $birthdateΪ����
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