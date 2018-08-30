<?php
/**
 * @author Michael
 * @Date 2014年1月7日 星期二 10:22:36
 * @version 1.0
 * @description:车辆供应商-基本信息 Model层
 */
 class model_outsourcing_outsourcessupp_vehiclesupp  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcessupp_vehiclesupp";
		$this->sql_map = "outsourcing/outsourcessupp/vehiclesuppSql.php";
		parent::__construct ();
	}

	//公司权限处理 TODO
	// protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

	/*
	 * 重写add
	 */
	function add_d($object) {
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$codeDao = new model_common_codeRule();
			$object['suppCode'] = $codeDao->outsourcSupplierCode($this->tbl_name ,'WBCL'); //供应商编号
			$object['suppCategoryName'] = $datadictDao->getDataNameByCode($object['suppCategory']); //供应商类型
			$object['invoice'] = $datadictDao->getDataNameByCode($object['invoiceCode']); //发票属性

			//获取归属公司名称
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$id = parent :: add_d($object, true);  //新增主表信息

			$vehiclesuppequDao = new model_outsourcing_outsourcessupp_vehiclesuppequ();
			if(is_array($object['vehicle'])) {  //车辆资源信息
				foreach($object['vehicle'] as $key => $val) {
					$val['parentId'] = $id;
					$vehiclesuppequDao->add_d($val);
				}
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/*
	 * 重写edit
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['suppCategoryName'] = $datadictDao->getDataNameByCode($object['suppCategory']); //供应商类型
			$object['invoice'] = $datadictDao->getDataNameByCode($object['invoiceCode']); //发票属性

			//操作日志
			$logSettringDao = new model_syslog_setting_logsetting ();
			$oldObj = $this->get_d ( $object ['id'] );
			if ($oldObj['isEquipDriver'] == '1') { //能否配备司机
				$oldObj['isEquipDriver'] = '能';
			} else {
				$oldObj['isEquipDriver'] = '不能';
			}
			if ($oldObj['isDriveTest'] == '1') { //有无路测经验
				$oldObj['isDriveTest'] = '有';
			} else {
				$oldObj['isDriveTest'] = '没有';
			}
			$newObj = $object;
			if ($newObj['isEquipDriver'] == '1') { //能否配备司机
				$newObj['isEquipDriver'] = '能';
			} else {
				$newObj['isEquipDriver'] = '不能';
			}
			if ($newObj['isDriveTest'] == '1') { //有无路测经验
				$newObj['isDriveTest'] = '有';
			} else {
				$newObj['isDriveTest'] = '没有';
			}
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $newObj );

			$id = parent :: edit_d($object, true); //更新主表信息

			$vehiclesuppequDao = new model_outsourcing_outsourcessupp_vehiclesuppequ();
			$vehiclesuppequDao->delete(array ('parentId' =>$object['id']));
			if(is_array($object['vehicle'])) {  //车辆资源信息
				foreach($object['vehicle'] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						$val['parentId'] = $object['id'];
						$vehiclesuppequDao->add_d($val);
					}
				}
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/*
	 * 加入黑名单
	 */
	function addBlacklist_d($object) {
		try {
			$this->start_d();

			//操作日志
			$logSettringDao = new model_syslog_setting_logsetting ();
			$oldObj = $this->get_d ( $object ['id'] );
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object );

			$id = parent :: edit_d($object, true); //更新主表信息

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**
	 * excel导入
	 */
	function addExecelData_d() {
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
		$codeDao=new model_common_codeRule();
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {

				//行数组循环
				foreach($excelData as $key => $val) {
					$actNum = $key;
					if( $key == 0 || empty($val[1])) {
						continue;
					} else {
						//新增数组
						$inArr = array();

						//供应商类型
						if(!empty($val[0]) && trim($val[0]) != '') {
							$inArr['suppCategoryName'] = trim($val[0]);
							$inArr['suppCategory'] = $datadictDao->getCodeByName('WBGYSLX' ,$inArr['suppCategoryName']);
							if (!$inArr['suppCategory']) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!供应商类型不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!供应商类型为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//供应商名称
						if(!empty($val[1]) && trim($val[1]) != '') {
							$inArr['suppName'] = trim($val[1]);
							$tmp = $this->findCount(array('suppName' => $inArr['suppName'])); //查找供应商名字是否已经存在
							if ($tmp  > 0) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!供应商名称已被注册</font>';
								array_push($resultArr ,$tempArr);
								continue;
							} else {
								$inArr['suppCode'] = $codeDao->outsourcSupplierCode($this->tbl_name ,'WBCL');
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!供应商名称为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//省份
						if(!empty($val[2]) && trim($val[2]) != '') {
							$inArr['province'] = trim($val[2]);
							$provinceId = $this->get_table_fields('oa_system_province_info', "provinceName='".$inArr['province']."'", 'id');
							if($provinceId > 0) {
								$inArr['provinceId'] = $provinceId;
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!省份不正确</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!省份为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//城市
						if(!empty($val[3]) && trim($val[3]) != '') {
							$inArr['city'] = trim($val[3]);
							$cityId = $this->get_table_fields('oa_system_city_info', "cityName='".$inArr['city']."'", 'id');
							if($cityId > 0) {
								$inArr['cityId'] = $cityId;
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!城市不正确</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!城市为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//成立时间
						if(!empty($val[4]) &&  $val[4] != '0000-00-00' && trim($val[4]) != '') {
							$val[4] = trim($val[4]);
							if(!is_numeric($val[4])) {
								$inArr['registeredDate'] = $val[4];
							} else {
								$recorderDate = date('Y-m-d' ,(mktime(0,0,0,1, $val[4] - 1 , 1900)));
								if($recorderDate=='1970-01-01') {
									$entryDate = date('Y-m-d',strtotime ($val[4]));
									$inArr['registeredDate'] = $entryDate;
								} else {
									$inArr['registeredDate'] = $recorderDate;
								}
							}
						}

						//注册资金
						if(!empty($val[5]) && trim($val[5]) != '') {
							$inArr['registeredFunds'] = trim($val[5]);
						}

						//法人代表
						if(!empty($val[6]) && trim($val[6]) != '') {
							$inArr['legalRepre'] = trim($val[6]);
						}

						//车辆数量
						if(!empty($val[7]) && trim($val[7]) != '') {
							$inArr['carAmount'] = trim($val[7]);
						}

						//司机数量
						if(!empty($val[8]) && trim($val[8]) != '') {
							$inArr['driverAmount'] = trim($val[8]);
						}

						//发票属性
						if(!empty($val[9]) && trim($val[9]) != '') {
							$inArr['invoice'] = trim($val[9]);
							$inArr['invoiceCode'] = $datadictDao->getCodeByName('WBFPZL' ,$inArr['invoice']);
							if (!$inArr['invoiceCode']) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!发票属性不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						//发票税点
						if(!empty($val[10]) && trim($val[10]) != '') {
							$inArr['taxPoint'] = trim($val[10]);
						}

						//能否配备司机
						if(!empty($val[11]) && trim($val[11]) != '') {
							$isEquipDriver = trim($val[11]);
							if ($isEquipDriver == '能') {
								$inArr['isEquipDriver'] = 1;
							}else {
								$inArr['isEquipDriver'] = 0;
							}
						}else {
							$inArr['isEquipDriver'] = 0;
						}

						//有无路测经验
						if(!empty($val[12]) && trim($val[12]) != '') {
							$isDriveTest = trim($val[12]);
							if ($isDriveTest == '有') {
								$inArr['isDriveTest'] = 1;
							}else {
								$inArr['isDriveTest'] = 0;
							}
						}else {
							$inArr['isDriveTest'] = 0;
						}

						//业务分布
						if(!empty($val[13]) && trim($val[13]) != '') {
							$businessDistribute = str_replace('，' ,',' ,trim($val[13]));
							$businessDistributeList = explode(',' ,$businessDistribute);
							$tmp = '';
							foreach ($businessDistributeList as $k => $v) {
								$businessDistributeId = $this->get_table_fields('oa_system_province_info', "provinceName='".$v."'", 'id');
								if($businessDistributeId > 0) {
									continue;
								}
								$tmp = $tmp.$v.'，';
							}
							if ($tmp) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!区域中“'.$tmp.'”不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
							$inArr['businessDistribute'] = $businessDistribute;
						}

						//初步商谈意向
						if(!empty($val[14]) && trim($val[14]) != '') {
							$inArr['tentativeTalk'] = trim($val[14]);
						}

						//公司简介
						if(!empty($val[15]) && trim($val[15]) != '') {
							$inArr['companyProfile'] = trim($val[15]);
						}

						//姓名
						if(!empty($val[16]) && trim($val[16]) != '') {
							$inArr['linkmanName'] = trim($val[16]);
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!联系人姓名为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//职务
						if(!empty($val[17]) && trim($val[17]) != '') {
							$inArr['linkmanJob'] = trim($val[17]);
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!联系人职务为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//电话
						if(!empty($val[18]) && trim($val[18]) != '') {
							$inArr['linkmanPhone'] = trim($val[18]);
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!联系人电话为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//邮箱
						if(!empty($val[19]) && trim($val[19]) != '') {
							$inArr['linkmanMail'] = trim($val[19]);
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!联系人邮箱为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//邮编
						if(!empty($val[20]) && trim($val[20]) != '') {
							$inArr['postcode'] = trim($val[20]);
						}

						//地址
						if(!empty($val[21]) && trim($val[21]) != '') {
							$inArr['address'] = trim($val[21]);
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!联系人地址为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//开户行
						if(!empty($val[22]) && trim($val[22]) != '') {
							$inArr['bankName'] = trim($val[22]);
						}

						//开户账号
						if(!empty($val[23]) && trim($val[23]) != '') {
							$inArr['bankAccount'] = trim($val[23]);
						}

						$newId = parent::add_d($inArr ,true);
						if($newId) {
							$tempArr['result'] = '导入成功';
						} else {
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
	 * excel导入黑名单
	 */
	function blackExecelData_d() {
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
		$codeDao=new model_common_codeRule();
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {

				//行数组循环
				foreach($excelData as $key => $val) {
					$actNum = $key + 1;
					if( empty($val[1]) ) {
						continue;
					} else {
						//新增数组
						$inArr = array();

						//供应商编号
						if(!empty($val[0]) && trim($val[0]) != '') {
							$inArr['suppCode'] = trim($val[0]);
							$tmp = $this->find(array('suppCode' => $inArr['suppCode']));
							if (!$tmp) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!供应商编号不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
							$inArr['id'] = $tmp['id'];
							$inArr['suppName'] = $tmp['suppName'];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!供应商编号为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//供应商名称
						if(!empty($val[1]) && trim($val[1]) != '') {
							if ($inArr['suppName'] != trim($val[1])) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!供应商编号与名称不匹配</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!供应商名称为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//列入黑名单原因
						if(!empty($val[2]) && trim($val[2]) != '') {
							$inArr['blackReason'] = trim($val[2]);
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!列入黑名单原因为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						$inArr['suppLevel'] = 0;
						$newId = $this->addBlacklist_d($inArr);

						if($newId) {
							$tempArr['result'] = '导入成功';
						} else {
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
}
?>