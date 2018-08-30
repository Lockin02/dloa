<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 16:32:24
 * @version 1.0
 * @description:外包供应商库 Model层
 */
 class model_outsourcing_supplier_basicinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_supplib";
		$this->sql_map = "outsourcing/supplier/basicinfoSql.php";
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

	/*****************************************************显示分割线**********************************************/
	/**新增供应商*/
	function add_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$codeDao=new model_common_codeRule();
			$object['suppCode']=$codeDao->outsourcSupplierCode($this->tbl_name);//供应商编号
			$object['suppTypeName'] = $datadictDao->getDataNameByCode($object['suppTypeCode']);

			//新增主表信息
			$id = parent :: add_d($object, true);

			$linkmanDao=new model_outsourcing_supplier_linkman();
			if(is_array($object['linkman'])){//联系人
				foreach($object['linkman'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$id;
					$linkmanDao->add_d($val);
				}
			}
			$bankinfoDao=new model_outsourcing_supplier_bankinfo();
			if(is_array($object['bankinfo'])){//银行账号
				foreach($object['bankinfo'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$id;
					$val['suppName']=$object['suppName'];
					$bankinfoDao->add_d($val);
				}
			}
			$hrInfoDao=new model_outsourcing_supplier_hrInfo();
			if(is_array($object['hrinfo'])){//人力资源信息
				foreach($object['hrinfo'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$id;
					$val['suppName']=$object['suppName'];
					$hrInfoDao->add_d($val);
				}
			}
			$workInfoDao=new model_outsourcing_supplier_workInfo();
			if(is_array($object['workinfo'])){//人力资源工作经验信息
				foreach($object['workinfo'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$id;
					$val['suppName']=$object['suppName'];
					$workInfoDao->add_d($val);
				}
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

		/**编辑供应商*/
	function edit_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['suppTypeName'] = $datadictDao->getDataNameByCode($object['suppTypeCode']);

			//新增主表信息
			$oldObj = $this->get_d ( $object ['id'] );
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object );
			$id = parent :: edit_d($object, true);

			$linkmanDao=new model_outsourcing_supplier_linkman();
			$linkmanDao->delete(array ('suppId' =>$object['id']));
			if(is_array($object['linkman'])){//联系人
				foreach($object['linkman'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$object['id'];
					$linkmanDao->add_d($val);
				}
			}
			$bankinfoDao=new model_outsourcing_supplier_bankinfo();
			$bankinfoDao->delete(array ('suppId' =>$object['id']));
			if(is_array($object['bankinfo'])){//银行账号
				foreach($object['bankinfo'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$object['id'];
					$val['suppName']=$object['suppName'];
					$bankinfoDao->add_d($val);
				}
			}
			$hrInfoDao=new model_outsourcing_supplier_hrInfo();
			$hrInfoDao->delete(array ('suppId' =>$object['id']));
			if(is_array($object['hrinfo'])){//人力资源信息
				foreach($object['hrinfo'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$object['id'];
					$val['suppName']=$object['suppName'];
					$hrInfoDao->add_d($val);
				}
			}

			$workInfoDao=new model_outsourcing_supplier_workInfo();
			$workInfoDao->delete(array ('suppId' =>$object['id']));
			if(is_array($object['workinfo'])){//人力资源工作经验信息
				foreach($object['workinfo'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$object['id'];
					$val['suppName']=$object['suppName'];
					$workInfoDao->add_d($val);
				}
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**认证等级变更*/
	function changeGrade_d($object){
		try {
			$this->start_d();
			if($object['gradeChange']==4){//黑名单
				$object['blackListReason']=$object['changeReason'];
			}
			$object['blackReason']=$object['changeReason'];
			//新增主表信息
			$id = parent :: edit_d($object, true);

			$changeInfoDao=new model_outsourcing_supplier_changeInfo();
			$val['suppCode']=$object['suppCode'];
			$val['suppId']=$object['id'];
			$val['suppGradeOld']=$object['suppGrade'];
			$val['suppGrade']=$object['gradeChange'];
			$val['remark']=$object['changeReason'];
			$changeInfoDao->add_d($val,true);
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

		/**认证等级变更审批*/
	function dealChange_d($id){
		try {
			$this->start_d();
			$object=$this->get_d($id);
			$obj['id']=$id;
			$obj['suppGrade']=$object['gradeChange'];
			//新增主表信息
			$id = parent :: edit_d($obj, true);
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

			/**认证等级变更审批*/
	function ajaxChange_d($id){
		try {
			$this->start_d();
			$obj['id']=$id;
			$obj['ExaStatus']='完成';
			//新增主表信息
			$id = parent :: edit_d($obj, true);
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

/**假删除供应商*/
	function deleteSupp_d($id,$isDel){
		try {
			$this->start_d();
			$obj['id']=$id;
			$obj['isDel']=$isDel;
			//新增主表信息
			$id = parent :: edit_d($obj, true);
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
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
		$linkmanArr = array();//插入数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$linkmanDao=new model_outsourcing_supplier_linkman();
		$codeDao=new model_common_codeRule();
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//行数组循环
				foreach($excelData as $key => $val){
					$actNum = $key + 2;
					if( empty($val[1])){
						continue;
					}else{
						//新增数组
						$inArr = array();

						//供应商名称
						if(!empty($val[0])&&trim($val[0])!=''){
							$id=$this->get_table_fields('oa_outsourcesupp_supplib', "suppName='".$val[0]."'", 'id');
							if($id>0){
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!该外包供应商信息已存在</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}else{
								$inArr['suppName'] = trim($val[0]);
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!供应商名称为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//区域
						if(!empty($val[1])&&trim($val[1])!=''){
							$officeId=$this->get_table_fields('oa_esm_office_baseinfo', "officeName='".$val[1]."'", 'id');
							if($officeId>0){
								$inArr['officeId'] = $officeId;
								$inArr['officeName'] = trim($val[1]);
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!区域不正确</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//省份
						if(!empty($val[2])&&trim($val[2])!=''){
							$provinceId=$this->get_table_fields('oa_system_province_info', "provinceName='".$val[2]."'", 'id');
							if($provinceId>0){
								$inArr['provinceId'] = $provinceId;
								$inArr['province'] = trim($val[2]);
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!省份不正确</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//成立时间
						if(!empty($val[3])&& $val[3] != '0000-00-00'&&trim($val[3])!=''){
							$val[3] = trim($val[3]);

							if(!is_numeric($val[3])){
								$inArr['registeredDate'] = $val[3];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[3] - 1 , 1900)));
								if($recorderDate=='1970-01-01'){
									$entryDate = date('Y-m-d',strtotime ($val[3]));
									$inArr['registeredDate'] = $entryDate;
								}else{
									$inArr['registeredDate'] = $recorderDate;
								}
							}
						}

						//注册资金（万元）
						if(!empty($val[4])&&trim($val[4])!=''){
							$inArr['registeredFunds'] = trim($val[4]);
						}

						//法人代表
						if(!empty($val[5])&&trim($val[5])!=''){
							$inArr['legalRepre'] = trim($val[5]);
						}

						//股权结构
						if(!empty($val[6])&&trim($val[6])!=''){
							$inArr['equityStructure'] = trim($val[6]);
						}

						//主营业务
						if(!empty($val[7])&&trim($val[7])!=''){
							$inArr['mainBusiness'] = trim($val[7]);
						}

						//擅长网络类型
						if(!empty($val[8])&&trim($val[8])!=''){
							$inArr['adeptNetType'] = trim($val[8]);
						}

						//擅长厂家设备
						if(!empty($val[9])&&trim($val[9])!=''){
							$inArr['adeptDevice'] = trim($val[9]);
						}

						//业务分布
						if(!empty($val[10])&&trim($val[10])!=''){
							$inArr['businessDistribute'] = trim($val[10]);
						}

						//税点
						if(!empty($val[11])&&trim($val[11])!=''){
							$val[11] = trim($val[11]);
							if(!isset($datadictArr[$val[11]])){
								$rs = $datadictDao->getCodeByName('WBZZSD',$val[11]);
								if(!empty($rs)){
									$incentiveType = $datadictArr[$val[11]]['code'] = $rs;
									$inArr['taxPoint'] = $incentiveType;
								}
							}else{
								$incentiveType = $datadictArr[$val[11]]['code'];
							}
						}

						//级别
						if(!empty($val[12])&&trim($val[12])!=''){
							switch(trim($val[12])){
								case '金':$inArr['suppGrade'] = 1;break;
								case '银':$inArr['suppGrade'] = 2;break;
								case '铜':$inArr['suppGrade'] = 3;break;
								case '黑名单':$inArr['suppGrade'] = 4;break;
								default:$inArr['suppGrade'] = '0';break;
							}
						}else{
							$inArr['suppGrade'] = '0';
						}

						//认证人数
						if(!empty($val[13])&&trim($val[13])!=''){
							$inArr['certifyNumber'] = trim($val[13]);
						}

						//邮编
						if(!empty($val[14])&&trim($val[14])!=''){
							$inArr['zipCode'] = trim($val[14]);
						}

						//地址
						if(!empty($val[15])&&trim($val[15])!=''){
							$inArr['address'] = trim($val[15]);
						}

						//公司简介
						if(!empty($val[16])&&trim($val[16])!=''){
							$inArr['suppIntro'] = trim($val[16]);
						}

						$inArr['suppCode']=$codeDao->outsourcSupplierCode($this->tbl_name);//供应商编号
						$inArr['suppTypeCode'] ='GYSLX-01';
						$inArr['suppTypeName'] ='外包公司';
//						print_r($inArr);
						$newId = parent::add_d($inArr,true);
						if($newId){
							//姓名
							if(!empty($val[17])&&trim($val[17])!=''){
								$linkmanArr['name'] = trim($val[17]);
							}
							//职务
							if(!empty($val[18])&&trim($val[18])!=''){
								$linkmanArr['jobName'] = trim($val[18]);
							}
							//电话
							if(!empty($val[19])&&trim($val[19])!=''){
								$linkmanArr['mobile'] = trim($val[19]);
							}
							//邮箱
							if(!empty($val[20])&&trim($val[20])!=''){
								$linkmanArr['email'] = trim($val[20]);
							}
							if(trim($val[17])!=''){
								$linkmanArr['suppCode']=$inArr['suppCode'];
								$linkmanArr['suppId']=$newId;
								$linkmanArr['defaultContact']='on';
								$linkmanDao->add_d($linkmanArr,true);
							}
							$tempArr['result'] = '导入成功';
						}else{
							$tempArr['result'] = '<font color=red>导入失败</font>';
						}
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}

	function updateExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
		$inArr = array();//插入数组
		$linkmanArr = array();//插入数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$linkmanDao=new model_outsourcing_supplier_linkman();
		$logSettringDao = new model_syslog_setting_logsetting ();
		$codeDao=new model_common_codeRule();
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//行数组循环
				foreach($excelData as $key => $val){
					$actNum = $key + 2;
					if( empty($val[1])){
						continue;
					}else{
						//新增数组
						$inArr = array();

						//供应商名称
						if(!empty($val[0])&&trim($val[0])!=''){
							$id=$this->get_table_fields('oa_outsourcesupp_supplib', "suppName='".$val[0]."'", 'id');
							if($id>0){
								$inArr['id'] =$id;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>更新失败!该外包供应商信息不存在</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>更新失败!供应商名称为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//区域
						if(!empty($val[1])&&trim($val[1])!=''){
							$officeId=$this->get_table_fields('oa_esm_office_baseinfo', "officeName='".$val[1]."'", 'id');
							if($officeId>0){
								$inArr['officeId'] = $officeId;
								$inArr['officeName'] = trim($val[1]);
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!区域不正确</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//省份
						if(!empty($val[2])&&trim($val[2])!=''){
							$provinceId=$this->get_table_fields('oa_system_province_info', "provinceName='".$val[2]."'", 'id');
							if($provinceId>0){
								$inArr['provinceId'] = $provinceId;
								$inArr['province'] = trim($val[2]);
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!省份不正确</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//成立时间
						if(!empty($val[3])&& $val[3] != '0000-00-00'&&trim($val[3])!=''){
							$val[3] = trim($val[3]);

							if(!is_numeric($val[3])){
								$inArr['registeredDate'] = $val[3];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[3] - 1 , 1900)));
								if($recorderDate=='1970-01-01'){
									$entryDate = date('Y-m-d',strtotime ($val[3]));
									$inArr['registeredDate'] = $entryDate;
								}else{
									$inArr['registeredDate'] = $recorderDate;
								}
							}
						}

						//注册资金（万元）
						if(!empty($val[4])&&trim($val[4])!=''){
							$inArr['registeredFunds'] = trim($val[4]);
						}

						//法人代表
						if(!empty($val[5])&&trim($val[5])!=''){
							$inArr['legalRepre'] = trim($val[5]);
						}

						//股权结构
						if(!empty($val[6])&&trim($val[6])!=''){
							$inArr['equityStructure'] = trim($val[6]);
						}

						//主营业务
						if(!empty($val[7])&&trim($val[7])!=''){
							$inArr['mainBusiness'] = trim($val[7]);
						}

						//擅长网络类型
						if(!empty($val[8])&&trim($val[8])!=''){
							$inArr['adeptNetType'] = trim($val[8]);
						}

						//擅长厂家设备
						if(!empty($val[9])&&trim($val[9])!=''){
							$inArr['adeptDevice'] = trim($val[9]);
						}

						//业务分布
						if(!empty($val[10])&&trim($val[10])!=''){
							$inArr['businessDistribute'] = trim($val[10]);
						}

						//税点
						if(!empty($val[11])&&trim($val[11])!=''){
							$val[11] = trim($val[11]);
							if(!isset($datadictArr[$val[11]])){
								$rs = $datadictDao->getCodeByName('WBZZSD',$val[11]);
								if(!empty($rs)){
									$incentiveType = $datadictArr[$val[11]]['code'] = $rs;
									$inArr['taxPoint'] = $incentiveType;
								}
							}else{
								$incentiveType = $datadictArr[$val[11]]['code'];
							}
						}

						//级别
						if(!empty($val[12])&&trim($val[12])!=''){
							switch(trim($val[12])){
								case '金':$inArr['suppGrade'] = 1;break;
								case '银':$inArr['suppGrade'] = 2;break;
								case '铜':$inArr['suppGrade'] = 3;break;
								case '黑名单':$inArr['suppGrade'] = 4;break;
								default:$inArr['suppGrade'] = '0';break;
							}
						}

						//认证人数
						if(!empty($val[13])&&trim($val[13])!=''){
							$inArr['certifyNumber'] = trim($val[13]);
						}

						//邮编
						if(!empty($val[14])&&trim($val[14])!=''){
							$inArr['zipCode'] = trim($val[14]);
						}

						//地址
						if(!empty($val[15])&&trim($val[15])!=''){
							$inArr['address'] = trim($val[15]);
						}

						//公司简介
						if(!empty($val[16])&&trim($val[16])!=''){
							$inArr['suppIntro'] = trim($val[16]);
						}
//						print_r($inArr);
						$oldObj = $this->get_d ( $inArr ['id'] );
						$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $inArr );
						$newId = parent::edit_d($inArr,true);
						if($newId){
							$tempArr['result'] = '更新成功';
						}else{
							$tempArr['result'] = '<font color=red>更新失败</font>';
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
	 * 获取省份编码，联系人，电话,银行账户和银行
	 * @param unknown $object
	 */
	function getInfo_d($object){
		$provinceDao = new model_system_procity_province();
		$provinceArr = $provinceDao->find(array('id' => $object['provinceId']));
		$linkmanDao = new model_outsourcing_supplier_linkman();
		$linkmanArr = $linkmanDao->find(array('suppId' => $object['id']));
		$bankinfoDao = new model_outsourcing_supplier_bankinfo();
		$bankinfoArr = $bankinfoDao->find(array('suppId' => $object['id']));
		$result = array('0'=>array('provinceCode' => $provinceArr['provinceCode'],'linkman' => $linkmanArr['name'],
				'phone' => $linkmanArr['mobile'],'bank'=>$bankinfoArr['bankName'],'account'=>$bankinfoArr['accountNum']));
		return $result;
	}
 }