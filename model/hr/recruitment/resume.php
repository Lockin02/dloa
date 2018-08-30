<?php
/**
 * @author Administrator
 * @Date 2012-07-06 15:20:28
 * @version 1.0
 * @description:�������� Model��
 */
class model_hr_recruitment_resume  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitment_resume";
		$this->sql_map = "hr/recruitment/resumeSql.php";

		$this->statusDao = new model_common_status ();
		$this->statusDao->resumeType = array (
			0 => array (
				'statusEName' => 'company',
				'statusCName' => '��˾����',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'staff',
				'statusCName' => '��ְ����',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'blacklist',
				'statusCName' => '������',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'fpsg',
				'statusCName' => '�����˲�',
				'key' => '3'
			),
			4 => array (
				'statusEName' => 'obsolete',
				'statusCName' => '��̭����',
				'key' => '4'
			),
			5 => array (
				'statusEName' => 'inservice',
				'statusCName' => '��ְ����',
				'key' => '5'
			),
			6 => array (
				'statusEName' => 'leave',
				'statusCName' => '��ְ����',
				'key' => '6'
			)
		);
		parent::__construct ();
	}


	//���� ��ȡ��Ƭ��ַ
	function getFilePhoto_d($objId){
		$uploadFile = new model_file_uploadfile_management ();
		if (empty ( $serviceType )) {
			$serviceType = $this->tbl_name;
		}
		$files = $uploadFile->getFilesByObjId ( $objId, $serviceType );
		if(empty($files)){
			return "";
		}else{
			$str=str_replace(WEB_TOR,'',UPLOADPATH);
			if(substr($str,0,1)=="/"){
				$str=substr($str,1);
			}
			return  $url = $str."/".$files[0]['serviceType']."/".$files[0]['newName'];
		}
	}

	//��������Զ����� ����ʱ��
	function resumeCode(){
		$billCode = "JL".date("Ym");
		$sql="select max(RIGHT(c.resumeCode,4)) as maxCode,left(c.resumeCode,8) as _maxbillCode " .
				"from oa_hr_recruitment_resume c group by _maxbillCode having _maxbillCode='".$billCode."'";

		$resArr=$this->findSql($sql);
		$res=$resArr[0];
		if(is_array($res)){
			$maxCode=$res['maxCode'];
			$maxBillCode=$res['maxbillCode'];
			$newNum=$maxCode+1;
			switch(strlen($newNum)){
				case 1:$codeNum="000".$newNum;break;
				case 2:$codeNum="00".$newNum;break;
				case 3:$codeNum="0".$newNum;break;
				case 4:$codeNum=$newNum;break;
			}
			$billCode.=$codeNum;
		}else{
			$billCode.="0001";
		}

		return $billCode;
	}

	/**
	 * ��д��������
	 */
	function add_d($object){
		try{
			$this->start_d();

			$object['resumeCode']=$this->resumeCode();
			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object ['postName'] = $datadictDao->getDataNameByCode ( $object['post'] );
			$object ['sourceAName'] = $datadictDao->getDataNameByCode ( $object['sourceA'] );
			$object ['languageGradeName'] = $datadictDao->getDataNameByCode ( $object['languageGrade'] );
			$object ['languageName'] = $datadictDao->getDataNameByCode ( $object['language'] );
			$object ['computerGradeName'] = $datadictDao->getDataNameByCode ( $object['computerGrade'] );

			//�޸�������Ϣ
			$newId = parent::add_d($object,true);
			//���������ƺ�Id
			$this->updateObjWithFile($newId);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��д��������
	 */
	function addByEmployment_d($object){
		try{
			$this->start_d();

			$object['resumeCode'] = $this->resumeCode();
			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object ['postName'] = $datadictDao->getDataNameByCode ( $object['post'] );
			$object ['sourceAName'] = $datadictDao->getDataNameByCode ( $object['sourceA'] );
			$object ['languageGradeName'] = $datadictDao->getDataNameByCode ( $object['languageGrade'] );
			$object ['languageName'] = $datadictDao->getDataNameByCode ( $object['language'] );
			$object ['computerGradeName'] = $datadictDao->getDataNameByCode ( $object['computerGrade'] );

			//�޸�������Ϣ
			$newId = parent::add_d($object,true);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��дedit_d
	 */
	function edit_d($object){
		try{
			$this->start_d();
			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object ['postName'] = $datadictDao->getDataNameByCode ( $object['post'] );
			$object ['sourceAName'] = $datadictDao->getDataNameByCode ( $object['sourceA'] );
			$object ['languageGradeName'] = $datadictDao->getDataNameByCode ( $object['languageGrade'] );
			$object ['languageName'] = $datadictDao->getDataNameByCode ( $object['language'] );
			$object ['computerGradeName'] = $datadictDao->getDataNameByCode ( $object['computerGrade'] );

			$oldObj = $this->get_d($object["id"]);
			if ($oldObj["applicantName"] != $object["applicantName"]
					|| $oldObj["sex"] != $object["sex"]
					|| $oldObj["email"] != $object["email"]
					|| $oldObj["phone"] != $object["phone"]) {
				$newData["userName"] = ($oldObj["applicantName"] != $object["applicantName"]) ? $object["applicantName"] : $oldObj["applicantName"];
				$newData["sexy"] = ($oldObj["sex"] != $object["sex"]) ? $object["sex"] : $oldObj["sex"];
				$newData["email"] = ($oldObj["email"] != $object["email"]) ? $object["email"] : $oldObj["email"];
				$newData["phone"] = ($oldObj["phone"] != $object["phone"]) ? $object["phone"] : $oldObj["phone"];

				//���ݼ���ID�������Ӧ��������������ְ֪ͨ
				$intDao = new model_hr_recruitment_interview();
				$intDao->update(array("resumeId" => $object["id"]) ,$newData);
				$entDao = new model_hr_recruitment_entryNotice();
				$entDao->update(array("resumeId" => $object["id"]) ,$newData);
			}

			//�޸�������Ϣ
			parent::edit_d($object ,true);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * תΪԱ������
	 */
	function turnType_d($id){
		try {
			$sql="update oa_hr_recruitment_resume set resumeType=1 where id=".$id."";
			$this->query($sql);
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 * ���������
	 */
	function ajaxBlacklist_d($id){
		try {
			$sql="update oa_hr_recruitment_resume set resumeType=2 where id=".$id."";
			$this->query($sql);
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 * �����˲�
	 */
	function ajaxReservelist_d($id){
		try {
			$sql="update oa_hr_recruitment_resume set resumeType=3 where id=".$id."";
			$this->query($sql);
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 * ��˾����
	 */
	function ajaxCompanyResume_d($id){
		try {
			$sql="update oa_hr_recruitment_resume set resumeType=0 where id=".$id."";
			$this->query($sql);
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 * ת������
	 */
	function ajaxChangeResume_d($id,$type){
		$sql="update oa_hr_recruitment_resume set resumeType=".$type." where id=".$id."";
		$flag=$this->query($sql);
		if($flag){
			return true;
		}else{
			return false;
		}
	}

	/******************* S ���뵼��ϵ�� ************************/
	function addExecelData_d($objNameArr){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			//			echo "<pre>";
			if(is_array($excelData)){
				$objectArr = array ();
				$resultArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					foreach ( $objNameArr as $index => $fieldName ) {
						$objectArr [$rNum] [$fieldName] = $row [$index];
					}
				}
				//ѭ�����������
				foreach($objectArr as $key => $val){
					if(empty($val['applicantName']) && empty($val['sex'])&& empty($val['birthdate'])){
						unset($objectArr[$key]);
					}
				}
				$actNum = 1;
				//ѭ������
				foreach($objectArr as $key => $val){
					//������������
					$tempArr = $this->disposeData($val,$actNum);
					array_push( $resultArr,$tempArr );
					$actNum += 1;
				}

				return $resultArr;
			}
		}
	}

	//������������
	function disposeData($row,$actNum){
		$addArr=array();
		//���������͵绰�����ѯ��Ϣ�Ƿ��ظ�
		$namecount = $this->get_table_fields($this->tbl_name,'applicantName="'.$row['applicantName'].'"',"resumeCode");
		$phonecount = $this->get_table_fields($this->tbl_name,'phone='.$row['phone'].'',"resumeCode");
		if(empty($row['applicantName'])||empty($row['phone'])||empty($row['email'])||empty($row['educationName'])){
			$tempArr['docCode'] = '<font color=red>��' . $actNum .'������</font>';
			$tempArr['result'] = '<font color=red>����ʧ��!ӦƸ����Ϣ������</font>';
			return $tempArr;
		}
		else if(empty($row['workSeniority'])||empty($row['sourceAName'])||empty($row['sourceB'])||empty($row['selfAssessment'])||empty($row['postName'])||empty($row['reserveA'])||empty($row['graduateDate']))
		{
			$tempArr['docCode'] = '<font color=red>��' . $actNum .'������</font>';
			$tempArr['result'] = '<font color=red>����ʧ��!ӦƸ����Ϣ������</font>';
			return $tempArr;
		}else if(!empty($namecount)&&!empty($phonecount)){
			$tempArr['docCode'] = '<font color=red>��' . $actNum .'������</font>';
			$tempArr['result'] = '<font color=red>����ʧ��!��ӦƸ���Ѵ���</font>';
			return $tempArr;
		}else{
			//�����ֵ䴦��
			$datadictDao = new model_system_datadict_datadict();
			$post = $datadictDao->getCodeByName('YPZW',$row['postName']);
			$row['post'] = $post;
			$education = $datadictDao->getCodeByName('HRJYXL',$row['educationName']);
			$row['education'] = $education;
			$computerGrade = $datadictDao->getCodeByName('JSJSP',$row['computerGradeName']);
			$row['computerGrade'] = $computerGrade;
			$language= $datadictDao->getCodeByName('HRYZ',$row['languageName']);
			$row['language'] = $language;
			$languageGrade = $datadictDao->getCodeByName('WYSP',$row['languageGradeName']);
			$row['languageGrade'] = $languageGrade;
			$sourceA = $datadictDao->getCodeByName('JLLY',$row['sourceAName']);
			$row['sourceA'] = $sourceA;
			//����ʱ��
			$birthdate = trim($row["birthdate"]);
			$graduateDate = trim($row["graduateDate"]);
			$hillockDate = trim($row["hillockDate"]);
			$row["birthdate"] = date('Y-m-d',(mktime(0,0,0,1, $birthdate - 1 , 1900)));//����ʱ��
			$row["graduateDate"] = date('Y-m-d',(mktime(0,0,0,1, $graduateDate - 1 , 1900)));//��ҵʱ��
			$row["hillockDate"] = date('Y-m-d',(mktime(0,0,0,1, $hillockDate - 1 , 1900)));//����ʱ��
			$newId = $this->add_d($row,true);
			if($newId){
				$tempArr['result'] = '�����ɹ�';
			}else{
				$tempArr['result'] = '����ʧ��';
			}
			$tempArr['docCode'] = '����' . $actNum .'������';
			return $tempArr;
		}

	}
	/******************* E ���뵼��ϵ�� ************************/

	/**
	 * �������һ�������
	 */
	function updateViewer($id){
		$viewName = $_SESSION['USERNAME'];
		$viewId = $_SESSION['USER_ID'];
		$viewDate = date("Y-m-d-");
		$updateSql = "update oa_hr_recruitment_resume set viewer='".$viewName."',viewerId='".$viewId."',viewDate=now() where id='".$id."';";
		$this->query($updateSql);
	}

	/**
	 * ɾ������
	 */
	function deletes_d($ids) {
		try {
			//�ж��Ƿ��ѹ��������
			$findA = "select id from oa_hr_recommend_resume where resumeId = ".$ids."";  //�ڲ��Ƽ��Ƿ���ڼ���
			$recomeend = $this->_db->getArray($findA);
			if(!empty($recomeend)){
				return 2;
			}

			$findB = "select id from oa_hr_apply_resume where resumeId = ".$ids."";  //��Ա�����Ƿ���ڼ���
			$apply = $this->_db->getArray($findB);
			if(!empty($apply)>0){
				return 2;
			}

			$findC = "select id from oa_hr_recruitment_invitation where resumeId = ".$ids."";  //����֪ͨ
			$invitation = $this->_db->getArray($findC);
			if(!empty($invitation)>0){
				return 2;
			}

			$findD = "select id from oa_hr_recruitment_interview where resumeId = ".$ids."";  //��������
			$interview = $this->_db->getArray($findD);
			if(!empty($interview)>0){
				return 2;
			}

			$findE = "select id from oa_hr_recruitment_entrynotice where resumeId = ".$ids."";  //��Ա�����Ƿ���ڼ���
			$entrynotice = $this->_db->getArray($findE);
			if(!empty($entrynotice)>0){
				return 2;
			}

			$this->deletes ( $ids );
			return 1;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/***************add chenrf 20130515*********************/
	/**
	 *
	 * ����id��ѯ����֪ͨ������
	 * @param id int
	 * @param type boolean
	 * @return Array,boolean
	 */
	function getInvaitation($id){
		$invitationModel=new model_hr_recruitment_invitation();
		$row=$invitationModel->findAll(array('resumeId'=> $id));
		return !empty($row)?$row:false;
	}
}
?>