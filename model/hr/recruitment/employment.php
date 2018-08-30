<?php
/**
 * @author Administrator
 * @Date 2012-07-18 19:15:30
 * @version 1.0
 * @description:ְλ����� Model��
 */
class model_hr_recruitment_employment  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitment_employment";
		$this->sql_map = "hr/recruitment/employmentSql.php";
		parent::__construct ();
	}


	//���� ��ȡ��Ƭ��ַ
	function getFilePhoto_d($objId,$serviceType=null){
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
	//��������Զ����� ����ʱ��
	function employmentCode(){
		$billCode = "ZWSQ".date("Ym");
		//        $billCode = "JL201208";
		$sql="select max(RIGHT(c.employmentCode,4)) as maxCode,left(c.employmentCode,10) as _maxbillCode " .
				"from oa_hr_recruitment_employment c group by _maxbillCode having _maxbillCode='".$billCode."'";

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
	 * �ʼ����û�ȡ
	 */
	function getMailInfo_d() {

		include (WEB_TOR . "model/common/mailConfig.php");
		$mailArr = isset ($mailUser['oa_hr_recruitment_employment']) ? $mailUser['oa_hr_recruitment_employment'] : array (
			'sendUserId' => '',
			'sendName' => ''
			);
			return $mailArr;
	}
	/**
	 * ��дadd_d����
	 */
	function add_d($object) {
		try {
			$this->start_d();
			$object['employmentCode']=$this->employmentCode();
			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict();
			$object ['politicsStatus'] =  $datadictDao->getDataNameByCode ( $object['politicsStatusCode'] );
			$object ['highEducationName'] =  $datadictDao->getDataNameByCode ( $object['highEducation'] );
			$object ['healthState'] =  $datadictDao->getDataNameByCode ( $object['healthStateCode'] );
			$object ['englishSkillName'] =  $datadictDao->getDataNameByCode ( $object['englishSkill'] );
			$object ['postName'] =  $datadictDao->getDataNameByCode ( $object['post'] );
			//����������Ϣ
			$newId = parent :: add_d($object, true);
			//����ӱ���Ϣ
			//��������
			if (!empty ($object['work'])) {
				$workDao = new model_hr_recruitment_work();
				$workDao->createBatch($object['work'], array (
					'employmentId' => $newId
				),"beginDate");
			}
			//��������
			if (!empty ($object['education'])) {
				$educationDao = new model_hr_recruitment_education();
				$educationDao->createBatch($object['education'], array (
					'employmentId' => $newId
				),"organization");
			}
			//��ͥ��Ա
			if (!empty ($object['family'])) {
				$familyDao = new model_hr_recruitment_family();
				$familyDao->createBatch($object['family'], array (
					'employmentId' => $newId
				),"name");
			}
			//��Ŀ����
			if (!empty ($object['project'])) {
				$familyDao = new model_hr_recruitment_project();
				$familyDao->createBatch($object['project'], array (
					'employmentId' => $newId
				),"beginDate");
			}
			//���������ƺ�Id
			$this->updateObjWithFile($newId,'oa_hr_recruitment_employment2' );
			$resumeDao=new model_hr_recruitment_resume();
			$uploadFile = new model_file_uploadfile_management ();
			$files = $uploadFile->getFilesByObjId ( $newId, 'oa_hr_recruitment_employment2' );

			//��Ӽ���
			//���������͵绰�����ѯ��Ϣ�Ƿ��ظ�
			$namecount = $resumeDao->get_table_fields($resumeDao->tbl_name,'applicantName="'.$object['name'].'"',"applicantName");
			$phonecount = $resumeDao->get_table_fields($resumeDao->tbl_name,'phone="'.$object['mobile'].'"',"phone");
			if(($object['name']!=$namecount&&$object['mobile']!=$phonecount)||($object['name']==$namecount&&$object['mobile']!=$phonecount)){

				$resume['applicantName']=$object['name'];
				$resume['sex']=$object['sex'];
				$resume['birthdate']=$object['birthdate'];
				$resume['phone']=$object['mobile'];
				$resume['email']=$object['personEmail'];
				$resume['marital']=$object['maritalStatusName'];
				$resume['education']=$object['highEducation'];
				$resume['post']=$object['post'];
				$resume['reserveA']=$object['reserveA'];
				$resume['graduateDate']=$object['graduateDate'];
				$resume['workSeniority']=$object['workSeniority'];
				$resume['college']=$object['highSchool'];
				$resume['major']=$object['professionalName'];
				$resume['wishSalary']=$object['wishSalary'];
				$resume['prevCompany']=$object['prevCompany'];
				$resume['hillockDate']=$object['hillockDate'];
				$resumeId=$resumeDao->addByEmployment_d($resume);
				//��Ӹ�����Ϣ
				if(is_array($files)){
					foreach($files as $fKey=>$fVal){
						$i=$fKey+1;
						//���븽����
						$fileArr['serviceType']="oa_hr_recruitment_resume2";
						$fileArr['originalName']=$fVal['originalName'];
						$fileArr['serviceId']=$resumeId;
						$fileArr['newName']="oa_hr_recruitment_resume2"."-".$fVal['newName'];
						$UPLOADPATH2=UPLOADPATH;
						$newPath=str_replace('\\','/',$UPLOADPATH2);
						$destDir=$newPath."oa_hr_recruitment_resume2/";
						$fileArr['uploadPath']=$destDir;
						$fileArr['tFileSize']=$fVal['tFileSize'];
						$test = $uploadFile->add_d ( $fileArr, true );
					}
				}
			}

			//�����ʼ�
			$content = $object['name']."�ύ��ְλ���룬��ע��鿴��";
			$emailDao = new model_common_mail();
			$emailDao->mailClear($title = 'ְλ����֪ͨ',$object['TO_ID'],$content);
			//$this->rollBack();
			$this->commit_d();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��д�༭����
	 */
	function edit_d($object) {
		try {
			$this->start_d();

			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict();
			$object ['politicsStatus'] =  $datadictDao->getDataNameByCode ( $object['politicsStatusCode'] );
			$object ['highEducationName'] =  $datadictDao->getDataNameByCode ( $object['highEducation'] );
			$object ['healthState'] =  $datadictDao->getDataNameByCode ( $object['healthStateCode'] );
			$object ['englishSkillName'] =  $datadictDao->getDataNameByCode ( $object['englishSkill'] );
			$object ['postName'] =  $datadictDao->getDataNameByCode ( $object['post'] );
			//�޸�������Ϣ
			parent :: edit_d($object, true);
			$empId = $object['id'];
			//����ӱ���Ϣ
			//��������
			$workDao = new model_hr_recruitment_work();
			$workDao->delete(array ( 'employmentId' => $empId ));
			foreach ($object['work'] as $k => $v) {
				if ($v['isDelTag'] == '1') {
					unset ($object['work'][$k]);
				}
			}
			$workDao->createBatch($object['work'], array (
					'employmentId' => $empId
			),"beginDate");
			//��������
			$educationDao = new model_hr_recruitment_education();
			$educationDao->delete(array ( 'employmentId' => $empId ));
			foreach ($object['education'] as $k => $v) {
				if ($v['isDelTag'] == '1') {
					unset ($object['education'][$k]);
				}
			}
			$educationDao->createBatch($object['education'], array (
					'employmentId' => $empId
			),"organization");


			//��ͥ��Ա
			$familyDao = new model_hr_recruitment_family();
			$familyDao->delete(array (
				'employmentId' => $empId
			));
			foreach ($object['family'] as $k => $v) {
				if ($v['isDelTag'] == '1') {
					unset ($object['family'][$k]);
				}
			}
			$familyDao->createBatch($object['family'], array (
					'employmentId' => $empId
			),"name");

			//��Ŀ����
			$projectDao = new model_hr_recruitment_project();
			$projectDao->delete(array (
				'employmentId' => $empId
			));
			foreach ($object['project'] as $k => $v) {
				if ($v['isDelTag'] == '1') {
					unset ($object['project'][$k]);
				}
			}
			$projectDao->createBatch($object['project'], array (
					'employmentId' => $empId
			),"beginDate");
			$this->commit_d();
			//				$this->rollBack();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
	/**
	 * ajax��дɾ������
	 */
	function deletes_d($ids) {
		try {
			$idArr=explode(',',$ids);
			$delId=array();
				
			$interviewDao=new model_hr_recruitment_interview(); //��������
			$entryNoticeDao=new model_hr_recruitment_entryNotice(); //��ְ֪ͨ
			foreach ($idArr as $id){
				$condition=array('applyId'=>$id);
				$count=$interviewDao->findCount($condition);
				if($count>0){
					continue;
				}
				$count=$entryNoticeDao->findCount($condition);
				if($count>0){
					continue;
				}
				array_push($delId,$id);
			}
			$idStr=implode(',',$delId);
			if(!empty($idStr)){
				$this->deletes ( $idStr );
				return true;
			}else{
				throw new Exception();
			}
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
}
?>