<?php
/**
 * @author Administrator
 * @Date 2012-07-06 15:20:28
 * @version 1.0
 * @description:简历管理 Model层
 */
class model_hr_recruitment_resume  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitment_resume";
		$this->sql_map = "hr/recruitment/resumeSql.php";

		$this->statusDao = new model_common_status ();
		$this->statusDao->resumeType = array (
			0 => array (
				'statusEName' => 'company',
				'statusCName' => '公司简历',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'staff',
				'statusCName' => '在职简历',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'blacklist',
				'statusCName' => '黑名单',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'fpsg',
				'statusCName' => '储备人才',
				'key' => '3'
			),
			4 => array (
				'statusEName' => 'obsolete',
				'statusCName' => '淘汰简历',
				'key' => '4'
			),
			5 => array (
				'statusEName' => 'inservice',
				'statusCName' => '在职简历',
				'key' => '5'
			),
			6 => array (
				'statusEName' => 'leave',
				'statusCName' => '离职简历',
				'key' => '6'
			)
		);
		parent::__construct ();
	}


	//附件 获取照片地址
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

	//简历编号自动生成 （临时）
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
	 * 重写新增方法
	 */
	function add_d($object){
		try{
			$this->start_d();

			$object['resumeCode']=$this->resumeCode();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object ['postName'] = $datadictDao->getDataNameByCode ( $object['post'] );
			$object ['sourceAName'] = $datadictDao->getDataNameByCode ( $object['sourceA'] );
			$object ['languageGradeName'] = $datadictDao->getDataNameByCode ( $object['languageGrade'] );
			$object ['languageName'] = $datadictDao->getDataNameByCode ( $object['language'] );
			$object ['computerGradeName'] = $datadictDao->getDataNameByCode ( $object['computerGrade'] );

			//修改主表信息
			$newId = parent::add_d($object,true);
			//处理附件名称和Id
			$this->updateObjWithFile($newId);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写新增方法
	 */
	function addByEmployment_d($object){
		try{
			$this->start_d();

			$object['resumeCode'] = $this->resumeCode();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object ['postName'] = $datadictDao->getDataNameByCode ( $object['post'] );
			$object ['sourceAName'] = $datadictDao->getDataNameByCode ( $object['sourceA'] );
			$object ['languageGradeName'] = $datadictDao->getDataNameByCode ( $object['languageGrade'] );
			$object ['languageName'] = $datadictDao->getDataNameByCode ( $object['language'] );
			$object ['computerGradeName'] = $datadictDao->getDataNameByCode ( $object['computerGrade'] );

			//修改主表信息
			$newId = parent::add_d($object,true);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写edit_d
	 */
	function edit_d($object){
		try{
			$this->start_d();
			//处理数据字典字段
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

				//根据简历ID更新相对应的面试评估和入职通知
				$intDao = new model_hr_recruitment_interview();
				$intDao->update(array("resumeId" => $object["id"]) ,$newData);
				$entDao = new model_hr_recruitment_entryNotice();
				$entDao->update(array("resumeId" => $object["id"]) ,$newData);
			}

			//修改主表信息
			parent::edit_d($object ,true);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 转为员工简历
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
	 * 加入黑名单
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
	 * 储备人才
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
	 * 公司简历
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
	 * 转换简历
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

	/******************* S 导入导出系列 ************************/
	function addExecelData_d($objNameArr){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		//判断导入类型是否为excel表
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
				//循环清掉空数组
				foreach($objectArr as $key => $val){
					if(empty($val['applicantName']) && empty($val['sex'])&& empty($val['birthdate'])){
						unset($objectArr[$key]);
					}
				}
				$actNum = 1;
				//循环数据
				foreach($objectArr as $key => $val){
					//处理并插入数据
					$tempArr = $this->disposeData($val,$actNum);
					array_push( $resultArr,$tempArr );
					$actNum += 1;
				}

				return $resultArr;
			}
		}
	}

	//处理并插入数据
	function disposeData($row,$actNum){
		$addArr=array();
		//根据姓名和电话号码查询信息是否重复
		$namecount = $this->get_table_fields($this->tbl_name,'applicantName="'.$row['applicantName'].'"',"resumeCode");
		$phonecount = $this->get_table_fields($this->tbl_name,'phone='.$row['phone'].'',"resumeCode");
		if(empty($row['applicantName'])||empty($row['phone'])||empty($row['email'])||empty($row['educationName'])){
			$tempArr['docCode'] = '<font color=red>第' . $actNum .'行数据</font>';
			$tempArr['result'] = '<font color=red>更新失败!应聘者信息不完整</font>';
			return $tempArr;
		}
		else if(empty($row['workSeniority'])||empty($row['sourceAName'])||empty($row['sourceB'])||empty($row['selfAssessment'])||empty($row['postName'])||empty($row['reserveA'])||empty($row['graduateDate']))
		{
			$tempArr['docCode'] = '<font color=red>第' . $actNum .'行数据</font>';
			$tempArr['result'] = '<font color=red>更新失败!应聘者信息不完整</font>';
			return $tempArr;
		}else if(!empty($namecount)&&!empty($phonecount)){
			$tempArr['docCode'] = '<font color=red>第' . $actNum .'行数据</font>';
			$tempArr['result'] = '<font color=red>更新失败!此应聘者已存在</font>';
			return $tempArr;
		}else{
			//数据字典处理
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
			//处理时间
			$birthdate = trim($row["birthdate"]);
			$graduateDate = trim($row["graduateDate"]);
			$hillockDate = trim($row["hillockDate"]);
			$row["birthdate"] = date('Y-m-d',(mktime(0,0,0,1, $birthdate - 1 , 1900)));//出生时间
			$row["graduateDate"] = date('Y-m-d',(mktime(0,0,0,1, $graduateDate - 1 , 1900)));//毕业时间
			$row["hillockDate"] = date('Y-m-d',(mktime(0,0,0,1, $hillockDate - 1 , 1900)));//到岗时间
			$newId = $this->add_d($row,true);
			if($newId){
				$tempArr['result'] = '新增成功';
			}else{
				$tempArr['result'] = '新增失败';
			}
			$tempArr['docCode'] = '新增' . $actNum .'条数据';
			return $tempArr;
		}

	}
	/******************* E 导入导出系列 ************************/

	/**
	 * 更新最近一次浏览人
	 */
	function updateViewer($id){
		$viewName = $_SESSION['USERNAME'];
		$viewId = $_SESSION['USER_ID'];
		$viewDate = date("Y-m-d-");
		$updateSql = "update oa_hr_recruitment_resume set viewer='".$viewName."',viewerId='".$viewId."',viewDate=now() where id='".$id."';";
		$this->query($updateSql);
	}

	/**
	 * 删除对象
	 */
	function deletes_d($ids) {
		try {
			//判断是否已管理简历库
			$findA = "select id from oa_hr_recommend_resume where resumeId = ".$ids."";  //内部推荐是否存在简历
			$recomeend = $this->_db->getArray($findA);
			if(!empty($recomeend)){
				return 2;
			}

			$findB = "select id from oa_hr_apply_resume where resumeId = ".$ids."";  //增员申请是否存在简历
			$apply = $this->_db->getArray($findB);
			if(!empty($apply)>0){
				return 2;
			}

			$findC = "select id from oa_hr_recruitment_invitation where resumeId = ".$ids."";  //面试通知
			$invitation = $this->_db->getArray($findC);
			if(!empty($invitation)>0){
				return 2;
			}

			$findD = "select id from oa_hr_recruitment_interview where resumeId = ".$ids."";  //面试评估
			$interview = $this->_db->getArray($findD);
			if(!empty($interview)>0){
				return 2;
			}

			$findE = "select id from oa_hr_recruitment_entrynotice where resumeId = ".$ids."";  //增员申请是否存在简历
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
	 * 根据id查询面试通知表内容
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