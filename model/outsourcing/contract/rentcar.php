<?php
/**
 * @author Michael
 * @Date 2014年3月6日 星期四 10:10:23
 * @version 1.0
 * @description:租车合同 Model层
 */
 class model_outsourcing_contract_rentcar  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_rentcar";
		$this->sql_map = "outsourcing/contract/rentcarSql.php";
		parent::__construct ();
	}

	//公司权限处理 TODO
	// protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

	/**
	 * 重写add
	 */
	function add_d($object){
		//业务前签约单位信息处理
		$signCompanyDao = new model_contract_signcompany_signcompany();
		$signCompanyArr = array(
			'signCompanyName' => $object['signCompany'],
			'proName' => $object['companyProvince'],
			'proCode' => $object['companyProvinceCode'],
			'phone' => $object['phone'],
			'address' => $object['address'],
			'linkman' => $object['linkman']
		);
		$signCompanyDao->saveCompanyInfo_d($signCompanyArr);

		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['orderCode'] = $this->createContractCode_d($object['companyCityCode']); //鼎利合同编号
			$object['contractNature'] = $datadictDao->getDataNameByCode($object['contractNatureCode']); //合同性质
			$object['contractType'] = $datadictDao->getDataNameByCode($object['contractTypeCode']); //合同类型
			$object['payType'] = $datadictDao->getDataNameByCode($object['payTypeCode']); //合同付款方式
			$object['ExaStatus'] = WAITAUDIT; //初始化审批状态

			//获取归属公司名称
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

            // 付款信息
            $payInfos = array();
            if(isset($object['payInfo'])){
                $payInfos = $object['payInfo'];
                unset($object['payInfo']);
            }

			$id = parent :: add_d($object, true); //新增主表信息

            // 付款信息数据处理
            if(!empty($payInfos)){
                $this->tbl_name = 'oa_contract_rentcar_payinfos';
                foreach ($payInfos as $k => $v){
                    $payInfos[$k]['mainId'] = $id;
                    $payInfos[$k]['projectId'] = $object['projectId'];
                    $payInfo = $payInfos[$k];
                    if(!isset($v['isDel'])){
                        parent :: add_d($payInfo,true);// 新增租车付款信息表数据
                    }
                }
                $this->tbl_name = "oa_contract_rentcar";
            }

			if(is_array($object['vehicle'])) { //合同租赁车辆信息
				$vehicleDao = new model_outsourcing_contract_vehicle();
				foreach($object['vehicle'] as $key => $val) {
					$val['contractId'] = $id;
					$val['orderCode'] = $object['orderCode'];
					$val['carNumber'] = trim($val['carNumber']); //去除车牌号两边的空格
					$val['carModel'] = $datadictDao->getDataNameByCode($val['carModelCode']); //租车性质
					$vehicleDao->add_d($val ,true);
				}
			}

			if(is_array($object['fee'])) { //合同附加费用
				$feeDao = new model_outsourcing_contract_rentcarfee();
				foreach($object['fee'] as $key => $val) {
					$val['contractId'] = $id;
					$val['orderCode'] = $object['orderCode'];
					$feeDao->add_d($val ,true);
				}
			}

			$this->updateObjWithFile($id ,$object['orderCode']); //更新附件关联关系
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写edit
	 */
	function edit_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['contractNature'] = $datadictDao->getDataNameByCode($object['contractNatureCode']); //合同性质
			$object['contractType'] = $datadictDao->getDataNameByCode($object['contractTypeCode']); //合同类型
			$object['payType'] = $datadictDao->getDataNameByCode($object['payTypeCode']); //合同付款方式

            // 付款信息
            $payInfos = array();
            if(isset($object['payInfo'])){
                $payInfos = $object['payInfo'];
                unset($object['payInfo']);
            }

            // 付款信息数据处理
            if(!empty($payInfos)){
                $this->tbl_name = 'oa_contract_rentcar_payinfos';
                foreach ($payInfos as $k => $v){
                    $payInfos[$k]['mainId'] = $object['id'];
                    $payInfos[$k]['projectId'] = $object['projectId'];
                    $payInfo = $payInfos[$k];
                    if(!isset($payInfo['id'])){
                        if(!isset($payInfo['isDel'])){
                            parent :: add_d($payInfo,true);
                        }
                    }else if(isset($payInfo['id'])){
                        if(isset($payInfo['isDel']) && $payInfo['isDel'] == 1){
                            parent :: delete(array("id" => $payInfo['id']));
                        }else{
                            parent :: edit_d($payInfo,true);
                        }
                    }
                }
                $this->tbl_name = "oa_contract_rentcar";
            }

			$id = parent :: edit_d($object, true); //编辑主表信息

			$vehicleDao = new model_outsourcing_contract_vehicle();
			$vehicleDao->delete(array ('contractId' => $object['id']));
			if(is_array($object['vehicle'])) {  //合同租赁车辆信息
				foreach($object['vehicle'] as $key => $val){
					if ($val['isDelTag'] != 1) {
						$val['contractId'] = $object['id'];
						$val['orderCode'] = $object['orderCode'];
						$val['carModel'] = $datadictDao->getDataNameByCode($val['carModelCode']); //租车性质
						$vehicleDao->add_d($val ,true);
					}
				}
			}

			if(is_array($object['fee'])) { //合同附加费用
				$feeDao = new model_outsourcing_contract_rentcarfee();
				foreach ($object['fee'] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						$val['carNumber'] = trim($val['carNumber']); //去除车牌号两边的空格
						if ($val['id'] > 0) {
							$feeDao->edit_d($val);
						} else {
							$val['contractId'] = $object['id'];
							$val['orderCode'] = $object['orderCode'];
							$feeDao->add_d($val);
						}
					} else {
						$feeDao->deleteByPk($val['id']);
					}
				}
			}

			$this->updateObjWithFile($id ,$object['orderCode']); //更新附件关联关系
			$this->commit_d();
			return $object['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 生成鼎利租车合同编号 城市编码+年份 确定唯一性
	 * @param $preStr 城市编码
	 */
	function createContractCode_d($cityCode) {
		$preStr = "DLZC".$cityCode.date("Y");
		//查找最近一条符合记录的合同编号
		$sqlStr = "SELECT orderCode FROM ".$this->tbl_name." WHERE orderCode LIKE '$preStr%' AND isTemp = 0 ORDER BY id DESC LIMIT 0,1";
		$obj = $this->findSql($sqlStr);
		if($obj) {
			$codeNum = intval(substr($obj[0]['orderCode'] ,-3));
			$newNum = $codeNum + 1;
			switch(strlen($newNum)) {
				case 1 : $codeNum = "00".$newNum;break;
				case 2 : $codeNum = "0".$newNum;break;
				case 3 : $codeNum = $newNum;break;
				default : $codeNum = "001"; //流水号超过999
			}
			$billCode = $preStr.$codeNum;
		} else {
			$billCode = $preStr."001";
		}
		return $billCode;
	}

	/**
	 * 新增申请盖章信息
	 */
	function stamp_d($obj){
		try{
			$this->start_d();

			$stampDao = new model_contract_stamp_stamp();
			//获取对应对象的最大批次号
			$maxBatchNo = $stampDao->get_table_fields($stampDao->tbl_name," contractType = 'HTGZYD-07' and contractId=". $obj['contractId'] ,"max(batchNo)");
			$obj['batchNo'] = $maxBatchNo + 1;

			//新增盖章信息
			$obj['contractType'] = 'HTGZYD-07';
			$stampDao->addStamps_d($obj ,true);

			//更新合同字段信息
			$this->updateById(array('id'=>$obj['contractId'] ,'isNeedStamp' => 1 ,'stampType'=>$obj['stampType'] ,'isStamp'=>0));

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 *合同审批成功后在盖章列表添加信息
	 */
	function workflowCallBack($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid);
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];

		$object = $this->get_d($objId);

		$this->dealSuppAndVehicle_d( $objId ); //车辆供应和车辆资源库处理

		if($object['isNeedStamp'] == "1" && $object['ExaStatus'] == AUDITED){
			if($userId == $object['createId']){
				$userName = $object['createName'];
			} else{
				$userName = $object['principalName'];
			}
	 		//创建数组
			$stampObj = array (
				"contractId" => $object['id'],
				"contractCode" => ($object['orderCode'] ? $object['orderCode'] : $object['orderTempCode']),
				"contractType" => 'HTGZYD-07',
				"objCode" => $object['objCode'],
				"contractName" => $object['orderName'],
				"signCompanyName" => $object['signCompany'],
				"signCompanyId" => $object['signCompanyId'],
				"contractMoney" => $object['orderMoney'],
				"applyUserId" => $userId,
				"applyUserName" => $userName,
				"applyDate" => day_date,
				"stampType" => $object['stampType'],
				"status" => 0
			);
			$stampDao = new model_contract_stamp_stamp();
			$stampDao->addStamps_d($stampObj ,true);
			return 1;
		}
	 	return 1;
	}

	/**
	 * 变更操作
	 */
	function change_d($object){
		try{
			$this->start_d();

			$changeLogDao = new model_common_changeLog('rentcar'); //实例化变更类

			$object['uploadFiles'] = $changeLogDao->processUploadFile($object ,$this->tbl_name); //附件处理

			$datadictDao = new model_system_datadict_datadict();
			$object['payType'] = $datadictDao->getDataNameByCode($object['payTypeCode']); //合同付款方式

			//车辆租赁情况的数据处理
			foreach ($object['vehicle'] as $key => $val) {
				$object['vehicle'][$key]['carNumber'] = trim($val['carNumber']); //去除车牌号两边的空格
				if ($val['id']) {
					$object['vehicle'][$key]['oldId'] = $val['id'];
				} else {
					$object['vehicle'][$key]['orderCode'] = $object['orderCode'];
				}
				$object['vehicle'][$key]['carModel'] = $datadictDao->getDataNameByCode($val['carModelCode']); //租车性质
			}

			//附加费用的数据处理
			foreach ($object['fee'] as $key => $val) {
				if ($val['id']) {
					$object['fee'][$key]['oldId'] = $val['id'];
				} else {
					$object['fee'][$key]['orderCode'] = $object['orderCode'];
				}
			}

            //付款信息的数据处理
            foreach ($object['payInfo'] as $key => $val) {
                $object = $this->addUpdateInfo($object);
                if ($val['id']) {
                    $object['payInfo'][$key]['oldId'] = $val['id'];
                } else {
                    $object['payInfo'][$key]['orderCode'] = $object['orderCode'];
                }

                if($val['isDel'] == 1){
                    if(!isset($val['id'])){
                        unset($object['payInfo'][$key]);
                    }else{
                        $object['payInfo'][$key]['isDelTag'] = $val['isDel'];
                    }
                }
            }

			$tempObjId = $changeLogDao->addLog($object); //建立变更信息

			$this->commit_d();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}
	
	/**
	 * 审批后回调方法
	 */
	function workflowCallBack_change($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];
		$this->dealAfterAuditChange_d($objId ,$userId);
	}

	/**
	 * 变更审批完成后更新合同状态
	 */
	function dealAfterAuditChange_d($objId ,$userId){
		$obj = $this->get_d($objId);
		if($obj['ExaStatus'] == AUDITED){
			try{
				$this->start_d();

				$changeLogDao = new model_common_changeLog ( 'rentcar' );
				$changeLogDao->confirmChange_d ( $obj );

				//源单状态处理
				if($obj['isNeedRestamp'] == 1 && $obj['isStamp'] == 1){//需要重新盖章
					//直接重置盖章状态位，将现有盖章记录关闭
					$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'isStamp' => 0,'isNeedRestamp' => 0,'isNeedStamp' =>0,'stampType' =>''));

				}elseif($obj['isNeedStamp'] == 1 && $obj['isStamp'] == 0){//正在盖章的处理

					$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'isStamp' => 0,'isNeedRestamp' => 0,'isNeedStamp' =>0,'stampType' =>''));

					$stampDao = new model_contract_stamp_stamp();
					$newId = $stampDao->closeWaiting_d($obj['originalId'],'HTGZYD-07');
				}else{//非盖章处理
					$this->update(array('id'=>$obj['originalId']),array('status' => 2,'isNeedRestamp' => 0));
				}

				$this->dealSuppAndVehicle_d($objId); //车辆供应商和车辆资源库信息处理

                // 付款信息更新
                $updateSql = "update oa_contract_rentcar_payinfos o1 left join oa_contract_rentcar_payinfos o2 on o1.id=o2.originalId SET o1.includeFeeTypeCode=o2.includeFeeTypeCode,o1.updateId = '{$_SESSION['USER_ID']}',o1.updateName = '{$_SESSION['USERNAME']}',o1.updateTime = Now() where o2.mainId = '{$objId}';";
                $this->_db->query($updateSql);

				$this->commit_d();
				return 1;
			}catch(Exception $e){
				$this->rollBack();
				return 1;
			}
		}else{
			try{
				$this->start_d();

				$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'ExaStatus' => '完成'));

                // 变更记录审批状态更新为打回
                $this->_db->query("update oa_contract_rentcar_changelog set ExaStatus = '打回',ExaDT = now() where tempId = '{$objId}';");

				$this->dealSuppAndVehicle_d($objId); //车辆供应商和车辆资源库信息处理

				$this->commit_d();
				return 1;
			}catch(Exception $e){
				$this->rollBack();
				return 1;
			}
		}
		return 1;
	}

	/**
	 * 付款申请验证方法
	 */
	function canPayapply_d($id){
		$payablesapplyDao = new model_finance_payablesapply_payablesapply();
		$rs = $payablesapplyDao->isExistence_d($id ,'YFRK-06' ,'back');
		if($rs){
			return 'hasBack';
		}
	}

	/**
	 * 退款申请验证
	 */
	function canPayapplyBack_d($id){
		$obj = $this->find(array('id' => $id) ,null ,'initPayMoney');
		//获取已付款金额(包含退款)
		$payablesDao = new model_finance_payables_payables();
		$payedMoney = bcadd($payablesDao->getPayedMoneyByPur_d($id ,'YFRK-02') ,$obj['initPayMoney'] ,2);
		if($payedMoney*1 != 0){
			$payablesapplyDao = new model_finance_payablesapply_payablesapply();
			$rs = $payablesapplyDao->isExistence_d($id ,'YFRK-02');
			if($rs){
				return 'hasBack';
			}
			$payedApplyMoney = bcadd($payablesapplyDao->getApplyMoneyByPurAll_d($id ,'YFRK-02') ,$obj['initPayMoney'] ,2);
			if($payedApplyMoney*1 != 0){
				return $payedApplyMoney;
			}else{
				return -1;
			}
		}else{
			return 0;
		}
	}

	/**
	 * 合同签收 - 签收功能
	 */
	function sign_d($object){
		//实例化变更类
		$changeLogDao = new model_common_changeLog ( 'rentcarSign' );
		try{
			$this->start_d();

 			//原来签收状态处理
			$signInfo = array(
				'signedDate' => day_date,
				'signedStatus' => 1,
				'signedMan' => $_SESSION['USERNAME'],
				'signedManId' => $_SESSION['USER_ID'],
				'id' => $object['oldId']
				);
			parent::edit_d($signInfo ,true);

			//数据处理
			$object = $this->processDatadict($object);

			//附件处理
			$object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

			//建立签收信息
			$tempObjId = $changeLogDao->addLog ( $object );

			$changeObj = $object;
			$changeObj['id'] = $tempObjId;
			$changeObj['originalId'] = $changeObj['oldId'];

			//签收确认
			$changeLogDao->confirmChange_d ( $changeObj );

			$this->commit_d();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据合同ID处理车辆供应商和车辆资源库信息
	 */
	function dealSuppAndVehicle_d( $id ) {
		$object = $this->get_d($id);
		//车辆供应商信息处理
		$vehicleSuppDao = new model_outsourcing_outsourcessupp_vehiclesupp();
		$vehicleSuppObj['id'] = $object['signCompanyId'];
		$vehicleSuppObj['province'] = $object['companyProvince'];
		$vehicleSuppObj['provinceId'] = $this->get_table_fields('oa_system_province_info' ,"provinceName='".$vehicleSuppObj['province']."'" ,'id');
		$vehicleSuppObj['city'] = $object['companyCity'];
		$vehicleSuppObj['cityId'] = $this->get_table_fields('oa_system_city_info' ,"cityName='".$vehicleSuppObj['province']."' AND provinceId='".$vehicleSuppObj['provinceId']."'" ,'id');
		$vehicleSuppObj['address'] = $object['address'];
		$vehicleSuppDao->updateById($vehicleSuppObj);

		//获取车辆供应商信息
		$suppObj = $vehicleSuppDao->get_d($object['signCompanyId']);
		//租赁车辆从表录入车辆资源库
		$equDao = new model_outsourcing_contract_vehicle();
		$contractId = $object['originalId'] ? $object['originalId'] : $object['id']; //判断是否第一次添加
		$equRow = $equDao->findAll(array('contractId'=>$contractId ,'isTemp'=>0));
		$vehicleDao = new model_outsourcing_outsourcessupp_vehicle();
		foreach ($equRow as $key => $val) {
			$vehicleObj = $vehicleDao->find(array('carNumber'=>$val['carNumber']));
			$vehicleObj['suppId']       = $suppObj['id'];
			$vehicleObj['suppCode']     = $suppObj['suppCode'];
			$vehicleObj['suppName']     = $suppObj['suppName'];

			$vehicleObj['carModel']     = $val['carModel'];
			$vehicleObj['driver']       = $val['driver'];
			$vehicleObj['idNumber']     = $val['idNumber'];
			$vehicleObj['displacement'] = $val['displacement'];

			if ($vehicleObj['id']) {
				$vehicleDao->edit_d($vehicleObj ,true);
			} else {
				$vehicleObj['carNumber'] = $val['carNumber'];
				$vehicleDao->add_d($vehicleObj ,true);
			}
		}
	}

	/**
	 * 根据车牌和日期查找合同信息
	 */
	function getByCarAndDate_d($carNum ,$date) {
		$sql = "SELECT * FROM $this->tbl_name c LEFT JOIN oa_contract_vehicle v ON c.id=v.contractId "
				." WHERE c.isTemp=0 AND v.isTemp=0 AND c.status=2 "
				." AND contractStartDate<='$date' AND contractEndDate>='$date' "
				." AND v.carNumber='$carNum' ";
		$obj = $this->findSql($sql);
		if ($obj) {
			return array_pop($obj);
		} else {
			return false;
		}
	}

	/**
	 * 项目经理提交租车合同后发邮件通知相关负责人
	 */
	function mailByProjectSubmit_d( $id ) {
		$obj = $this->get_d( $id );
		$content = <<<EOT
			租车申请单号：<span style="color:blue">$obj[rentalcarCode]</span><br />
			项目名称：<span style="color:blue">$obj[projectName]</span><br />
			项目编号：<span style="color:blue">$obj[projectCode]</span><br />
			签约公司：<span style="color:blue">$obj[signCompany]</span><br />
			合同名称：<span style="color:blue">$obj[orderName]</span>
EOT;
		$this->mailDeal_d('rentcarProjectSubmit' ,null ,array('id' => $id ,'content' => $content));
	}

	/**
	 * 打回租车合同
	 */
	function back_d($obj){
		try{
			$this->start_d();
			$this->updateById(array('id' => $obj['id'] ,'status' => $obj['status']));
			$object = $this->get_d($obj['id']);
			$content = <<<EOT
				租车申请单号：<span style="color:blue">$object[rentalcarCode]</span><br />
				项目名称：<span style="color:blue">$object[projectName]</span><br />
				项目编号：<span style="color:blue">$object[projectCode]</span><br />
				签约公司：<span style="color:blue">$object[signCompany]</span><br />
				合同名称：<span style="color:blue">$object[orderName]</span><br />
				打回原因：$obj[backReason]
EOT;
			$this->mailDeal_d('rentcarBack' ,$object['projectManagerId'] ,array('id' => $obj['id'] ,'content' => $content));

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * excel导入
	 */
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
		$codeDao = new model_common_codeRule();
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {
				//数组下标
				$keyArr = array(
					'contractNature', //合同性质
					'contractType', //合同类型
					'rentalcarCode', //租车申请单号
					'projectCode', //项目编号
					'orderName', //合同名称
					'principalName', //合同负责人
					'deptName', //负责人部门
					'signCompanyCode', //签约公司编号
					'companyProvince', //公司省份
					'companyCity', //公司城市
					'orderMoney', //合同金额
					'linkman', //联系人
					'phone', //联系电话
					'isNeedStamp', //是否需要盖章
					'stampType', //盖章类型
					'ownCompany', //归属公司
					'rentUnitPrice', //租赁费用(元/月/辆)
					'oilPrice', //油价(元/升)
					'fuelCharge', //燃油费(元/公里)
					'signDate', //签约日期
					'contractStartDate', //合同开始日期
					'contractEndDate', //合同结束日期
					'isUseOilcard', //是否使用油卡
					'oilcardMoney', //油卡金额(元)
//					'payBankName', //付款银行
//					'payBankNum', //付款账号
//					'payMan', //付款人
//					'payConditions', //付款条件
//					'payType', //付款方式
//					'payApplyMan', //付款申请人
					'carModel', //车型
					'carNumber', //车牌号
					'driver', //驾驶员
					'idNumber', //驾驶员身份证号
					'displacement', //排量、使用何种汽油
					'oilCarUse', //油卡抵充
					'oilCarAmount', //油卡金额
					'address', //联系地址
					'fundCondition', //款项条件
					'contractContent' //合同内容
				);
				//对数组进行转换
				$transData = array();
				foreach ($excelData as $key => $val) {
					if (!empty($val[0]) || !empty($val[1])) {
						foreach ($keyArr as $k => $v) {
							$data[$v] = trim($val[$k]);
						}
                        $data['numKey']=$key;
						array_push($transData ,$data);
					}
				}
			}
             $newTransData=array();
            foreach($transData as $key=>$val){
                $newTransData[$val['signCompanyCode']][] = $val;
            }
			if (!empty($newTransData)) {
				$rentalcarDao = new model_outsourcing_vehicle_rentalcar(); //租车申请
				$projectDao = new model_engineering_project_esmproject(); //工程项目
				$userDao = new model_deptuser_user_user(); //用户
				$deptDao = new model_deptuser_dept_dept(); //部门
				$suppDao = new model_outsourcing_outsourcessupp_vehiclesupp(); //车辆供应商
				$provinceDao = new model_system_procity_province(); //城市
				$cityDao = new model_system_procity_city(); //省份
				$branchDao = new model_deptuser_branch_branch(); //公司
				$equDao = new model_outsourcing_contract_vehicle(); //从表
				$vehicleDao = new model_outsourcing_outsourcessupp_vehicle(); //车辆资源库
				$stampDao = new model_contract_stamp_stamp(); // 盖章

                foreach($newTransData as $nKey=>$nVal){
                    $newId=0;
                    $orderCode='';
                    foreach($newTransData[$nKey] as $key => $val ){
                        $actNum = $val['numKey'] + 2;
                        //新增数组
                        $inArr = array();
                        //车辆数数组
                        $carArr=array();
                        if($key==0){

                            //合同性质
                            if(!empty($val['contractNature'])) {
                                $inArr['contractNature'] = $val['contractNature'];
                                $contractNatureCode = $datadictDao->getCodeByName('ZCHTXZ' ,$inArr['contractNature']);
                                if ($contractNatureCode) {
                                    $inArr['contractNatureCode'] = $contractNatureCode;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!合同性质不存在</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!合同性质为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //合同类型
                            if(!empty($val['contractType'])) {
                                $inArr['contractType'] = $val['contractType'];
                                $contractTypeCode = $datadictDao->getCodeByName('ZCHTLX' ,$inArr['contractType']);
                                if ($contractTypeCode) {
                                    $inArr['contractTypeCode'] = $contractTypeCode;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!合同类型不存在</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!合同类型为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //租车申请单号
                            if(!empty($val['rentalcarCode'])) {
                                $inArr['rentalcarCode'] = $val['rentalcarCode'];
                                $rentalcarObj = $rentalcarDao->find(array('formCode' => $inArr['rentalcarCode']) ,'id');
                                if ($rentalcarObj) {
                                    $inArr['rentalcarId'] = $rentalcarObj['id'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!租车申请单号不存在</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            }

                            //项目编号
                            if(!empty($val['projectCode'])) {
                                $inArr['projectCode'] = $val['projectCode'];
                                $projectObj = $projectDao->find(array('projectCode' => $inArr['projectCode']));
                                if ($projectObj) {
                                    $inArr['projectId'] = $projectObj['id'];
                                    $inArr['projectName'] = $projectObj['projectName'];
                                    $inArr['officeId'] = $projectObj['officeId'];
                                    $inArr['officeName'] = $projectObj['officeName'];
                                    $inArr['projectType'] = $projectObj['natureName'];
                                    $inArr['projectTypeCode'] = $projectObj['nature'];
                                    $inArr['projectManager'] = $projectObj['managerName'];
                                    $inArr['projectManagerId'] = $projectObj['managerId'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!项目编号不存在</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!项目编号为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //合同名称
                            if(!empty($val['orderName'])) {
                                $inArr['orderName'] = $val['orderName'];
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!合同名称为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //合同负责人
                            if(!empty($val['principalName'])) {
                                $inArr['principalName'] = $val['principalName'];
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!合同负责人为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //负责人部门
                            if(!empty($val['deptName'])) {
                                $inArr['deptName'] = $val['deptName'];
                                $deptObj = $deptDao->findAll(array('DEPT_NAME' => $inArr['deptName']) ,null ,'DEPT_ID');
                                if ($deptObj) {
                                    $deptIds = '';
                                    foreach ($deptObj as $dKey => $dval){// 防止输入的子部门名有多条记录的时候会出错的问题
                                        if($dval['DEPT_ID'] != ''){
                                            $deptIds .= ($deptIds == '')? $dval['DEPT_ID'] : ",".$dval['DEPT_ID'];
                                        }
                                    }
                                    $userObj = ($deptIds == "")? false : $userDao->find(" USER_NAME = '{$inArr['principalName']}' AND DEPT_ID IN ({$deptIds})" ,null ,'USER_ID');
                                    if ($userObj) {
                                        $inArr['deptId'] = $userObj['DEPT_ID'];
                                        $inArr['principalId'] = $userObj['USER_ID'];
                                    } else {
                                        $tempArr['docCode'] = '第' . $actNum .'行数据';
                                        $tempArr['result'] = '<font color=red>导入失败!合同负责人不存在</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!负责人部门不存在</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!负责人部门为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //签约公司编号
                            if(!empty($val['signCompanyCode'])) {
                                $signCompanyCode = $val['signCompanyCode'];
                                $suppObj = $suppDao->find(array('suppCode'=>$signCompanyCode));
                                if ($suppObj) {
                                    $inArr['signCompany'] = $suppObj['suppName'];
                                    $inArr['signCompanyId'] = $suppObj['id'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!签约公司编号不存在</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!签约公司编号为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //公司省份
                            if(!empty($val['companyProvince'])) {
                                $inArr['companyProvince'] = $val['companyProvince'];
                                $companyProvinceObj = $provinceDao->find(array('provinceName' => $inArr['companyProvince']) ,null ,'provinceCode');
                                if ($companyProvinceObj) {
                                    $inArr['companyProvinceCode'] = $companyProvinceObj['provinceCode'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!公司省份不存在</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!公司省份为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //公司城市
                            if(!empty($val['companyCity'])) {
                                $inArr['companyCity'] = $val['companyCity'];
                                $companyCityObj = $cityDao->find(array('cityName' => $inArr['companyCity'] ,'provinceCode' => $inArr['companyProvinceCode']) ,null ,'cityCode');
                                if ($companyCityObj) {
                                    $inArr['companyCityCode'] = $companyCityObj['cityCode'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!公司城市不存在</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!公司城市为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //合同金额
                            if($val['orderMoney'] != '') {
                                $inArr['orderMoney'] = $val['orderMoney'];
                                if (!is_numeric($inArr['orderMoney'])) {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!合同金额必须为整数或小数</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!合同金额为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //联系人
                            if(!empty($val['linkman'])) {
                                $inArr['linkman'] = $val['linkman'];
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!联系人为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //联系电话
                            if(!empty($val['phone'])) {
                                $inArr['phone'] = $val['phone'];
                            }

                            //是否需要盖章
                            if(!empty($val['isNeedStamp'])) {
                                $isNeedStamp = $val['isNeedStamp'];
                                if ($isNeedStamp == '是') {
                                    $inArr['isNeedStamp'] = 1;
                                } else {
                                    $inArr['isNeedStamp'] = 0;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!是否需要盖章为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //盖章类型
                            if ($inArr['isNeedStamp'] == 1) {
                                if(!empty($val['stampType'])) {
                                    $inArr['stampType'] = $val['stampType'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!盖章类型为空</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            }

                            //归属公司
                            if(!empty($val['ownCompany'])) {
                                $inArr['ownCompany'] = $val['ownCompany'];
                                $branchObj = $branchDao->find(array('NameCN' => $inArr['ownCompany']));
                                if ($branchObj) {
                                    $inArr['ownCompanyId'] = $branchObj['ID'];
                                    $inArr['ownCompanyCode'] = $branchObj['NamePT'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!归属公司不存在</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!归属公司为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //租赁费用
                            if($val['rentUnitPrice'] != '') {
                                $inArr['rentUnitPrice'] = $val['rentUnitPrice'];
                                if (!is_numeric($inArr['rentUnitPrice'])) {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!租赁费用必须为整数或小数</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!租赁费用为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //油价
                            if ($inArr['contractTypeCode'] == 'ZCHTLX-01') {
                                if(trim($val['oilPrice']) != '') {
                                    $inArr['oilPrice'] = $val['oilPrice'];
                                    if (!is_numeric($inArr['oilPrice'])) {
                                        $tempArr['docCode'] = '第' . $actNum .'行数据';
                                        $tempArr['result'] = '<font color=red>导入失败!油价必须为整数或小数</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!油价为空</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $inArr['oilPrice'] = 0;
                            }

                            //燃油费
                            if ($inArr['contractTypeCode'] == 'ZCHTLX-02') {
                                if(trim($val['fuelCharge']) != '') {
                                    $inArr['fuelCharge'] = $val['fuelCharge'];
                                    if (!is_numeric($inArr['fuelCharge'])) {
                                        $tempArr['docCode'] = '第' . $actNum .'行数据';
                                        $tempArr['result'] = '<font color=red>导入失败!燃油费必须为整数或小数</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!燃油费为空</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $inArr['fuelCharge'] = 0;
                            }

                            //签约日期
                            if(!empty($val['signDate']) && $val['signDate'] != '0000-00-00') {
                                $val['signDate'] = $val['signDate'];
                                if(!is_numeric($val['signDate'])){
                                    $inArr['signDate'] = $val['signDate'];
                                } else {
                                    $signDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['signDate'] - 1 ,1900)));
                                    if($signDate == '1970-01-01') {
                                        $tmpDate = date('Y-m-d' ,strtotime($val['signDate']));
                                        $inArr['signDate'] = $tmpDate;
                                    } else {
                                        $inArr['signDate'] = $signDate;
                                    }
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!签约日期为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //合同开始日期
                            if(!empty($val['contractStartDate']) && $val['contractStartDate'] != '0000-00-00') {
                                $val['contractStartDate'] = $val['contractStartDate'];
                                if(!is_numeric($val['contractStartDate'])){
                                    $inArr['contractStartDate'] = $val['contractStartDate'];
                                } else {
                                    $contractStartDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['contractStartDate'] - 1 ,1900)));
                                    if($contractStartDate == '1970-01-01') {
                                        $tmpDate = date('Y-m-d' ,strtotime($val['contractStartDate']));
                                        $inArr['contractStartDate'] = $tmpDate;
                                    } else {
                                        $inArr['contractStartDate'] = $contractStartDate;
                                    }
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!合同开始日期为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //合同结束日期
                            if(!empty($val['contractEndDate']) && $val['contractEndDate'] != '0000-00-00') {
                                $val['contractEndDate'] = $val['contractEndDate'];
                                if(!is_numeric($val['contractEndDate'])){
                                    $inArr['contractEndDate'] = $val['contractEndDate'];
                                } else {
                                    $contractEndDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['contractEndDate'] - 1 ,1900)));
                                    if($contractEndDate == '1970-01-01') {
                                        $tmpDate = date('Y-m-d' ,strtotime($val['contractEndDate']));
                                        $inArr['contractEndDate'] = $tmpDate;
                                    } else {
                                        $inArr['contractEndDate'] = $contractEndDate;
                                    }
                                }

                                //计算合同天数
                                $days = (strtotime($inArr['contractEndDate']) - strtotime($inArr['contractStartDate'])) / (24 * 60 * 60);
                                if ($days > 0) {
                                    $inArr['contractUseDay'] = $days;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!合同结束日期不能小于合同开始日期</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!合同结束日期为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //是否使用油卡
                            if(!empty($val['isUseOilcard'])) {
                                $isUseOilcard = $val['isUseOilcard'];
                                if ($isUseOilcard == '是') {
                                    $inArr['isUseOilcard'] = 1;
                                } else {
                                    $inArr['isUseOilcard'] = 0;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!是否使用油卡为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //油卡金额
                            if ($inArr['isUseOilcard'] == 1) {
                                if($val['oilcardMoney'] != '') {
                                    $inArr['oilcardMoney'] = $val['oilcardMoney'];
                                    if (!is_numeric($inArr['oilcardMoney'])) {
                                        $tempArr['docCode'] = '第' . $actNum .'行数据';
                                        $tempArr['result'] = '<font color=red>导入失败!油卡金额必须为整数或小数</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!油卡金额为空</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $inArr['oilcardMoney'] = 0;
                            }

//                            //付款银行
//                            if(!empty($val['payBankName'])) {
//                                $inArr['payBankName'] = $val['payBankName'];
//                            } else {
//                                $tempArr['docCode'] = '第' . $actNum .'行数据';
//                                $tempArr['result'] = '<font color=red>导入失败!付款银行为空</font>';
//                                array_push($resultArr ,$tempArr);
//                                continue;
//                            }
//
//                            //付款帐号
//                            if(!empty($val['payBankNum'])) {
//                                $inArr['payBankNum'] = $val['payBankNum'];
//                            } else {
//                                $tempArr['docCode'] = '第' . $actNum .'行数据';
//                                $tempArr['result'] = '<font color=red>导入失败!付款帐号为空</font>';
//                                array_push($resultArr ,$tempArr);
//                                continue;
//                            }
//
//                            //付款人
//                            if(!empty($val['payMan'])) {
//                                $inArr['payMan'] = $val['payMan'];
//                            } else {
//                                $tempArr['docCode'] = '第' . $actNum .'行数据';
//                                $tempArr['result'] = '<font color=red>导入失败!付款人为空</font>';
//                                array_push($resultArr ,$tempArr);
//                                continue;
//                            }
//
//                            //付款条件
//                            if(!empty($val['payConditions'])) {
//                                $inArr['payConditions'] = $val['payConditions'];
//                            } else {
//                                $tempArr['docCode'] = '第' . $actNum .'行数据';
//                                $tempArr['result'] = '<font color=red>导入失败!付款条件为空</font>';
//                                array_push($resultArr ,$tempArr);
//                                continue;
//                            }
//
//                            //付款方式
//                            if(!empty($val['payType'])) {
//                                $inArr['payType'] = $val['payType'];
//                                $payTypeCode = $datadictDao->getCodeByName('ZCHTFK' ,$inArr['payType']);
//                                if ($payTypeCode) {
//                                    $inArr['payTypeCode'] = $payTypeCode;
//                                } else {
//                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
//                                    $tempArr['result'] = '<font color=red>导入失败!付款方式不存在</font>';
//                                    array_push($resultArr ,$tempArr);
//                                    continue;
//                                }
//                            } else {
//                                $tempArr['docCode'] = '第' . $actNum .'行数据';
//                                $tempArr['result'] = '<font color=red>导入失败!付款方式为空</font>';
//                                array_push($resultArr ,$tempArr);
//                                continue;
//                            }
//
//                            //付款申请人
//                            if(!empty($val['payApplyMan'])) {
//                                if ($val['payApplyMan'] == '项目经理' || $val['payApplyMan'] == '项目成员' || $val['payApplyMan'] == '不限范围') {
//                                    $inArr['payApplyMan'] = $val['payApplyMan'];
//                                } else {
//                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
//                                    $tempArr['result'] = '<font color=red>导入失败!合同性质不存在</font>';
//                                    array_push($resultArr ,$tempArr);
//                                    continue;
//                                }
//                            } else {
//                                $tempArr['docCode'] = '第' . $actNum .'行数据';
//                                $tempArr['result'] = '<font color=red>导入失败!付款申请人为空</font>';
//                                array_push($resultArr ,$tempArr);
//                                continue;
//                            }

                            //车型
                            if(!empty($val['carModel'])) {
                                $inArr['equ']['carModel'] = $val['carModel'];
                                $carModelCode = $datadictDao->getCodeByName('WBZCCX' ,$inArr['equ']['carModel']);
                                if ($carModelCode) {
                                    $inArr['equ']['carModelCode'] = $carModelCode;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!车型不存在</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!车型为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //车牌号
                            if(!empty($val['carNumber'])) {
                                $inArr['equ']['carNumber'] = $val['carNumber'];
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!车牌号为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //驾驶员
                            if(!empty($val['driver'])) {
                                $inArr['equ']['driver'] = $val['driver'];
                            }else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!驾驶员名称为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //驾驶员身份证号
                            if(!empty($val['idNumber'])) {
                                $inArr['equ']['idNumber'] = $val['idNumber'];
                            } else {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!驾驶员身份证号为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //排量、使用何种汽油
                            if(!empty($val['displacement'])) {
                                $inArr['equ']['displacement'] = $val['displacement'];
                            }

                            //油卡抵充
                            if(!empty($val['oilCarUse'])) {
                                switch ($val['oilCarUse']) {
                                    case '是':
                                        $oilCarUse = '是';
                                        break;
                                    case '否':
                                        $oilCarUse = '否';
                                        break;
                                    default:
                                        $oilCarUse = '否';
                                        break;
                                }
                                $inArr['equ']['oilCarUse'] = $oilCarUse;
                            } else {
                                $inArr['equ']['oilCarUse'] = '否';
                            }

                            if(!empty($val['oilCarAmount'])) {
                                if (!is_numeric($val['oilCarAmount'])) {
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!车辆油卡金额必须为整数或小数</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                                $inArr['equ']['oilCarAmount'] = $val['oilCarAmount'];
                            } else {
                                $inArr['equ']['oilCarAmount'] = 0;
                            }

                            //联系地址
                            if(!empty($val['address'])) {
                                $inArr['address'] = $val['address'];
                            }

                            //款项条件
                            if(!empty($val['fundCondition'])) {
                                $inArr['fundCondition'] = $val['fundCondition'];
                            } else if ($inArr['contractNatureCode'] == 'ZCHTXZ-01') {
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!款项条件为空</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //合同内容
                            if(!empty($val['contractContent'])) {
                                $inArr['contractContent'] = $val['contractContent'];
                            }

                            $inArr['orderCode'] = $this->createContractCode_d($inArr['companyCityCode']); //鼎利合同编号
                            $inArr['status'] = 0;
                            $inArr['ExaStatus'] = '待提交';
                            //获取归属公司名称
                            $inArr['formBelong'] = $_SESSION['USER_COM'];
                            $inArr['formBelongName'] = $_SESSION['USER_COM_NAME'];
                            $inArr['businessBelong'] = $_SESSION['USER_COM'];
                            $inArr['businessBelongName'] = $_SESSION['USER_COM_NAME'];

					        $newId = parent::add_d($inArr ,true);

                            if($newId) {
                                $inArr['equ']['contractId'] = $newId;
                                $orderCode=$inArr['equ']['orderCode'] = $inArr['orderCode'];
                                $rs = $equDao->add_d( $inArr['equ'] );
                                if ($rs) {
                                    //录入车辆资源库
                                    $this->dealSuppAndVehicle_d( $newId );

                                    // 盖章处理
                                    if($inArr['isNeedStamp'] == "1") {
                                        $obj = $this->get_d($newId);
                                        //创建数组
                                        $stampObj = array (
                                            "contractId"      => $obj['id'],
                                            "contractCode"    => $obj['orderCode'],
                                            "contractType"    => 'HTGZYD-07',
                                            "objCode"         => $obj['objCode'],
                                            "contractName"    => $obj['orderName'],
                                            "signCompanyName" => $obj['signCompany'],
                                            "signCompanyId"   => $obj['signCompanyId'],
                                            "contractMoney"   => $obj['orderMoney'],
                                            "applyUserId"     => $obj['principalId'],
                                            "applyUserName"   => $obj['principalName'],
                                            "applyDate"       => day_date,
                                            "stampType"       => $obj['stampType'],
                                            "status"          => 0
                                        );
                                        $stampDao->addStamps_d($stampObj ,true);
                                    }

                                    $tempArr['result'] = '导入成功';
                                } else {
                                    $tempArr['result'] = '<font color=red>导入失败</font>';
                                }
                            } else {
                                $tempArr['result'] = '<font color=red>导入失败</font>';
                            }
                            $tempArr['docCode'] = '第' . $actNum .'行数据';
                            array_push($resultArr ,$tempArr);

                        }else{
                                if($newId>0){
                                    //车型
                                    if(!empty($val['carModel'])) {
                                        $carArr['carModel'] = $val['carModel'];
                                        $carModelCode = $datadictDao->getCodeByName('WBZCCX' ,$carArr['carModel']);
                                        if ($carModelCode) {
                                            $carArr['carModelCode'] = $carModelCode;
                                        } else {
                                            $tempArr['docCode'] = '第' . $actNum .'行数据';
                                            $tempArr['result'] = '<font color=red>导入失败!车型不存在</font>';
                                            array_push($resultArr ,$tempArr);
                                            continue;
                                        }
                                    } else {
                                        $tempArr['docCode'] = '第' . $actNum .'行数据';
                                        $tempArr['result'] = '<font color=red>导入失败!车型为空</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }

                                    //车牌号
                                    if(!empty($val['carNumber'])) {
                                        $carArr['carNumber'] = $val['carNumber'];
                                    } else {
                                        $tempArr['docCode'] = '第' . $actNum .'行数据';
                                        $tempArr['result'] = '<font color=red>导入失败!车牌号为空</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }

                                    //驾驶员
                                    if(!empty($val['driver'])) {
                                        $carArr['driver'] = $val['driver'];
                                    }

                                    //驾驶员身份证号
                                    if(!empty($val['idNumber'])) {
                                        $carArr['idNumber'] = $val['idNumber'];
                                    } else {
                                        $tempArr['docCode'] = '第' . $actNum .'行数据';
                                        $tempArr['result'] = '<font color=red>导入失败!驾驶员身份证号为空</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }

                                    //排量、使用何种汽油
                                    if(!empty($val['displacement'])) {
                                        $carArr['displacement'] = $val['displacement'];
                                    }

                                    //油卡抵充
                                    if(!empty($val['oilCarUse'])) {
                                        switch ($val['oilCarUse']) {
                                            case '是':
                                                $oilCarUse = '是';
                                                break;
                                            case '否':
                                                $oilCarUse = '否';
                                                break;
                                            default:
                                                $oilCarUse = '否';
                                                break;
                                        }
                                        $carArr['oilCarUse'] = $oilCarUse;
                                    } else {
                                        $carArr['oilCarUse'] = '否';
                                    }

                                    if(!empty($val['oilCarAmount'])) {
                                        if (!is_numeric($val['oilCarAmount'])) {
                                            $tempArr['docCode'] = '第' . $actNum .'行数据';
                                            $tempArr['result'] = '<font color=red>导入失败!车辆油卡金额必须为整数或小数</font>';
                                            array_push($resultArr ,$tempArr);
                                            continue;
                                        }
                                        $carArr['oilCarAmount'] = $val['oilCarAmount'];
                                    } else {
                                        $carArr['oilCarAmount'] = 0;
                                    }
                                    $carArr['contractId'] = $newId;
                                   $carArr['orderCode'] =  $orderCode;
                                    $rs = $equDao->add_d( $carArr);
                                    if($rs){
                                        //录入车辆资源库
                                        $this->dealSuppAndVehicle_d( $newId );
                                        $tempArr['result'] = '导入成功';
                                    } else {
                                        $tempArr['result'] = '<font color=red>导入失败</font>';
                                    }
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    array_push($resultArr ,$tempArr);
                                }else{
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入失败!未生成合同信息</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                        }
                    }
                }
			}
			return $resultArr;
		}
	}

	/**
	 * 合同关联工程项目
	 */
	function excelPro_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
		$inArr = array();//插入数组
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {
				$projectDao = new model_engineering_project_esmproject(); //工程项目

				//行数组循环
				foreach($excelData as $key => $val){
					$actNum = $key + 1;
					if(empty($val[0]) && empty($val[1])) {
						continue;
					} else {
						//新增数组
						$inArr = array();

						//鼎利合同编号
						if(!empty($val[0]) && trim($val[0]) != '') {
							$inArr['orderCode'] = trim($val[0]);
							$rs = $this->find(array('orderCode' => $inArr['orderCode'] ,'isTemp' => 0 ,'status' => 2));
							if ($rs) {
								$conditions = array('orderCode' => $inArr['orderCode'] ,'isTemp' => 0 ,'status' => 2);
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!鼎利合同编号不存在或不在执行中</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!鼎利合同编号为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//项目编号
						if(!empty($val[1]) && trim($val[1]) != '') {
							$inArr['projectCode'] = trim($val[1]);
							$projectObj = $projectDao->find(array('projectCode' => $inArr['projectCode']));
							if ($projectObj) {
								$inArr['projectId'] = $projectObj['id'];
								$inArr['projectName'] = $projectObj['projectName'];
								$inArr['officeId'] = $projectObj['officeId'];
								$inArr['officeName'] = $projectObj['officeName'];
								$inArr['projectType'] = $projectObj['natureName'];
								$inArr['projectTypeCode'] = $projectObj['nature'];
								$inArr['projectManager'] = $projectObj['managerName'];
								$inArr['projectManagerId'] = $projectObj['managerId'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!项目编号不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!项目编号为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						$rs = $this->update($conditions ,$inArr);

						if($rs) {
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