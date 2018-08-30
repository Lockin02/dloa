<?php
/**
 * @author Administrator
 * @Date 2012-07-18 19:15:30
 * @version 1.0
 * @description:职位申请表 Model层
 */
class model_hr_recruitment_employment  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitment_employment";
		$this->sql_map = "hr/recruitment/employmentSql.php";
		parent::__construct ();
	}


	//附件 获取照片地址
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
	//简历编号自动生成 （临时）
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
	 * 邮件配置获取
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
	 * 重写add_d方法
	 */
	function add_d($object) {
		try {
			$this->start_d();
			$object['employmentCode']=$this->employmentCode();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$object ['politicsStatus'] =  $datadictDao->getDataNameByCode ( $object['politicsStatusCode'] );
			$object ['highEducationName'] =  $datadictDao->getDataNameByCode ( $object['highEducation'] );
			$object ['healthState'] =  $datadictDao->getDataNameByCode ( $object['healthStateCode'] );
			$object ['englishSkillName'] =  $datadictDao->getDataNameByCode ( $object['englishSkill'] );
			$object ['postName'] =  $datadictDao->getDataNameByCode ( $object['post'] );
			//插入主表信息
			$newId = parent :: add_d($object, true);
			//插入从表信息
			//工作经历
			if (!empty ($object['work'])) {
				$workDao = new model_hr_recruitment_work();
				$workDao->createBatch($object['work'], array (
					'employmentId' => $newId
				),"beginDate");
			}
			//教育经历
			if (!empty ($object['education'])) {
				$educationDao = new model_hr_recruitment_education();
				$educationDao->createBatch($object['education'], array (
					'employmentId' => $newId
				),"organization");
			}
			//家庭成员
			if (!empty ($object['family'])) {
				$familyDao = new model_hr_recruitment_family();
				$familyDao->createBatch($object['family'], array (
					'employmentId' => $newId
				),"name");
			}
			//项目经历
			if (!empty ($object['project'])) {
				$familyDao = new model_hr_recruitment_project();
				$familyDao->createBatch($object['project'], array (
					'employmentId' => $newId
				),"beginDate");
			}
			//处理附件名称和Id
			$this->updateObjWithFile($newId,'oa_hr_recruitment_employment2' );
			$resumeDao=new model_hr_recruitment_resume();
			$uploadFile = new model_file_uploadfile_management ();
			$files = $uploadFile->getFilesByObjId ( $newId, 'oa_hr_recruitment_employment2' );

			//添加简历
			//根据姓名和电话号码查询信息是否重复
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
				//添加附件信息
				if(is_array($files)){
					foreach($files as $fKey=>$fVal){
						$i=$fKey+1;
						//插入附件表
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

			//发送邮件
			$content = $object['name']."提交了职位申请，请注意查看。";
			$emailDao = new model_common_mail();
			$emailDao->mailClear($title = '职位申请通知',$object['TO_ID'],$content);
			//$this->rollBack();
			$this->commit_d();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写编辑方法
	 */
	function edit_d($object) {
		try {
			$this->start_d();

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$object ['politicsStatus'] =  $datadictDao->getDataNameByCode ( $object['politicsStatusCode'] );
			$object ['highEducationName'] =  $datadictDao->getDataNameByCode ( $object['highEducation'] );
			$object ['healthState'] =  $datadictDao->getDataNameByCode ( $object['healthStateCode'] );
			$object ['englishSkillName'] =  $datadictDao->getDataNameByCode ( $object['englishSkill'] );
			$object ['postName'] =  $datadictDao->getDataNameByCode ( $object['post'] );
			//修改主表信息
			parent :: edit_d($object, true);
			$empId = $object['id'];
			//插入从表信息
			//工作经历
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
			//教育经历
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


			//家庭成员
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

			//项目经历
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
	 * ajax重写删除方法
	 */
	function deletes_d($ids) {
		try {
			$idArr=explode(',',$ids);
			$delId=array();
				
			$interviewDao=new model_hr_recruitment_interview(); //面试评估
			$entryNoticeDao=new model_hr_recruitment_entryNotice(); //入职通知
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