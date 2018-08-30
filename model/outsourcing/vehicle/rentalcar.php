<?php
/**
 * @author Michael
 * @Date 2014年1月20日 星期一 15:32:49
 * @version 1.0
 * @description:租车的申请与受理 Model层
 */
class model_outsourcing_vehicle_rentalcar  extends model_base {

    public $_rentCarFeeName = array();
	function __construct() {
		$this->tbl_name = "oa_outsourcing_rentalcar";
		$this->sql_map = "outsourcing/vehicle/rentalcarSql.php";
		parent::__construct ();

        // 租车费用名目
        $this->_rentCarFeeName = array(
            "gasolinePrice" => "油费",
            "reimbursedFuel" => "实报实销油费",
            "gasolineKMCost" => "按公里计价油费",
            "parkingCost" => "停车费",
            "tollCost" => "路桥费",
            "rentalCarCost" => "租车费",
            "mealsCost" => "餐饮费",
            "accommodationCost" => "住宿费",
            "overtimePay" => "加班费",
            "specialGas" => "特殊油费"
        );
	}

	//公司权限处理 TODO
	protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

	/**
	 * 重写add
	 */
	function add_d($object){
		try {
			$this->start_d();
			$object['formCode'] = "ZCSQ".date("Ymd").time(); //生成申请单编号
			$datadictDao = new model_system_datadict_datadict();
			$object['testTime'] = $datadictDao->getDataNameByCode($object['testTimeCode']); //测试时长
			$object['testPeriod'] = $datadictDao->getDataNameByCode($object['testPeriodCode']); //测试时间段
			$object['expectUseDay'] = $datadictDao->getDataNameByCode($object['expectUseDayCode']); //预计每月用车天数
			$object['rentalProperty'] = $datadictDao->getDataNameByCode($object['rentalPropertyCode']); //租车性质
			$object['payGasoline'] = $datadictDao->getDataNameByCode($object['payGasolineCode']); //油费
			$object['payParking'] = $datadictDao->getDataNameByCode($object['payParkingCode']); //路桥停车费
			$object['isPayDriver'] = $datadictDao->getDataNameByCode($object['isPayDriverCode']); //是否支司机食宿

			//获取归属公司名称
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$id = parent :: add_d($object, true);  //新增主表信息

			$rentalcarequDao = new model_outsourcing_vehicle_rentalcarequ();
			if(is_array($object['supp'])){  //推荐供应商列表
                $uploadFile = new model_file_uploadfile_management ();
				foreach($object['supp'] as $key => $val) {
					if ($val['isProvideInvoice'] == 0) { //如果选择不提供发票，则发票种类和税点设置为空
						$val['invoiceCode'] = '';
						$val['taxPoint'] = '';
					}
					$val['vehicleModel'] = $datadictDao->getDataNameByCode($val['vehicleModelCode']); //租车性质
					$val['paymentCycle'] = $datadictDao->getDataNameByCode($val['paymentCycleCode']); //付款周期
					$val['invoice'] = $datadictDao->getDataNameByCode($val['invoiceCode']); //发票属性
					$val['taxationBears'] = $datadictDao->getDataNameByCode($val['taxationBearsCode']); //税费承担方
					$val['parentId'] = $id;
					$equId=$rentalcarequDao->add_d($val, true);
                    if(!empty($val['file'])){
                        //处理附件
                        $uploadFile->updateFileAndObj ( $val['file'], $equId ,'rentalcar_supp' );
                    }
				}
			}
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
			$object['testTime'] = $datadictDao->getDataNameByCode($object['testTimeCode']); //测试时长
			$object['testPeriod'] = $datadictDao->getDataNameByCode($object['testPeriodCode']); //测试时间段
			$object['expectUseDay'] = $datadictDao->getDataNameByCode($object['expectUseDayCode']); //预计每月用车天数
			$object['rentalProperty'] = $datadictDao->getDataNameByCode($object['rentalPropertyCode']); //租车性质
			$object['payGasoline'] = $datadictDao->getDataNameByCode($object['payGasolineCode']); //油费
			$object['payParking'] = $datadictDao->getDataNameByCode($object['payParkingCode']); //路桥停车费
			$object['isPayDriver'] = $datadictDao->getDataNameByCode($object['isPayDriverCode']); //是否支司机食宿

			$id = parent::edit_d($object, true); //更新主表信息

			if(is_array($object['supp'])) { //推荐供应商列表
				$rentalcarequDao = new model_outsourcing_vehicle_rentalcarequ();
                $uploadFile = new model_file_uploadfile_management ();
				$rentalcarequDao->delete(array('parentId' => $object['id']));
				foreach($object['supp'] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						if ($val['isProvideInvoice'] == 0) { //如果选择不提供发票，则发票种类和税点设置为空
							$val['invoiceCode'] = '';
							$val['taxPoint'] = '';
						}
						$val['vehicleModel'] = $datadictDao->getDataNameByCode($val['vehicleModelCode']); //租车性质
						$val['paymentCycle'] = $datadictDao->getDataNameByCode($val['paymentCycleCode']); //付款周期
						$val['invoice'] = $datadictDao->getDataNameByCode($val['invoiceCode']); //发票属性
						$val['taxationBears'] = $datadictDao->getDataNameByCode($val['taxationBearsCode']); //税费承担方
						$val['parentId'] = $object['id'];
                        unset($val['id'] );
                        $equId=$rentalcarequDao->add_d($val, true);
                        if(!empty($val['file'])){
                            //处理附件
                            $uploadFile->updateFileAndObj ( $val['file'], $equId ,'rentalcar_supp' );
                        }
					}
				}
			}

			$this->commit_d();
			return $object['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 租车接口人编辑推荐供应商列表
	 */
	function dealEdit_d($id ,$obj) {
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$rentalcarequDao = new model_outsourcing_vehicle_rentalcarequ();
			$rentalcarequDao->delete(array ('parentId' =>$id ,'createId' =>$_SESSION['USER_ID']));
			if(is_array($obj)){  //推荐供应商列表
				foreach($obj as $key => $val){
					if ($val['isDelTag'] != 1) {
						if ($val['isProvideInvoice'] == 0) { //如果选择不提供发票，则发票种类和税点设置为空
							$val['invoiceCode'] = '';
							$val['taxPoint'] = '';
						}
						$val['paymentCycle'] = $datadictDao->getDataNameByCode($val['paymentCycleCode']); //付款周期
						$val['invoice'] = $datadictDao->getDataNameByCode($val['invoiceCode']); //发票属性
						$val['taxationBears'] = $datadictDao->getDataNameByCode($val['taxationBearsCode']); //税费承担方
						$val['parentId'] = $id;
						$rentalcarequDao->add_d($val, true);
					}
				}
			}
			//更新附件关联关系
			$this->updateObjWithFile ( $id );

			//附件处理
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 租车负责人确认推荐供应商列表
	 */
	function affirmEdit_d($id ,$obj) {
		try {
			$this->start_d();
			$this->update(array('id'=>$id) ,array('state'=>'6'));
			$rentalcarequDao = new model_outsourcing_vehicle_rentalcarequ();
			if(is_array($obj)){  //推荐供应商列表
				foreach($obj as $key => $val){
					if (!$val['suppAffirm']) {
						$val['suppAffirm'] = 'no';
					}
					$rentalcarequDao->edit_d($val);
				}
			}
			$this->sendFinishMail_d( $id ); //发送邮件通知
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 租车负责人打回受理
	 */
	function back_d($obj) {
		try {
			$this->start_d();
			$obj['state'] = '7';
			parent :: edit_d($obj);
			$this->sendBackMail_d( $obj['id'] ); //发送邮件通知
			$this->commit_d();
			return $obj['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 审批完成发送邮件通知租车申请流相关人员(不包含服务总监)
	 */
	 function sendRentalcarMail_d( $id ) {
	 	$obj = $this->get_d( $id );
	 	$receiverId = $this->getMailReceiver_d( $id );
	 	$emailDao = new model_common_mail();
	 	$mailContent = '您好！此邮件为租车申请审批通过通知，详细信息如下：<br>'.
		'单据编号：<span style="color:blue">'.$obj['formCode'].
		'</span><br>项目编号：<span style="color:blue">'.$obj['projectCode'].
		'</span><br>项目名称：<span style="color:blue">'. $obj['projectName'].
		'</span><br>租车性质：<span style="color:blue">'.$obj['rentalProperty'].
		'</span><br>用车地点：<span style="color:blue">'.$obj['province'].'-'.$obj['city'].
		'</span><br>用车数量：<span style="color:blue">'.$obj['useCarAmount'].
		'</span><br>申请人：<span style="color:blue">'.$obj['createName'].
		'</span><br>申请人电话：<span style="color:blue">'.$obj['applicantPhone'].
		'</span><br>申请时间：<span style="color:blue">'.$obj['createTime'].
		'</span><br>';

	 	$emailDao->mailGeneral("租车申请受理结果" ,$receiverId ,$mailContent);
	 }

	/**
	 * 审批完成发送邮件通知租车申请流相关人员
	 */
	 function sendFinishMail_d( $id ) {
	 	$obj = $this->get_d( $id );
	 	$receiverId = $this->getMailReceiver2_d( $id );
	 	$emailDao = new model_common_mail();
	 	$mailContent = '您好！此邮件为租车申请审批通过通知，详细信息如下：<br>'.
		'单据编号：<span style="color:blue">'.$obj['formCode'].
		'</span><br>项目编号：<span style="color:blue">'.$obj['projectCode'].
		'</span><br>项目名称：<span style="color:blue">'. $obj['projectName'].
		'</span><br>租车性质：<span style="color:blue">'.$obj['rentalProperty'].
		'</span><br>用车地点：<span style="color:blue">'.$obj['province'].'-'.$obj['city'].
		'</span><br>用车数量：<span style="color:blue">'.$obj['useCarAmount'].
		'</span><br>申请人：<span style="color:blue">'.$obj['createName'].
		'</span><br>申请人电话：<span style="color:blue">'.$obj['applicantPhone'].
		'</span><br>申请时间：<span style="color:blue">'.$obj['createTime'].
		'</span><br>';

		$rentalcarequDao = new model_outsourcing_vehicle_rentalcarequ();
		$objEqu = $rentalcarequDao->findAll(array('parentId'=>$id));

		if (is_array($objEqu)) {
			$mailContent .= '推荐供应商信息:<br><table border="1px" cellspacing="0px" style="text-align:center"><tr style="background-color:#fff999"><td width="100px">供应商编号</td><td width="100px">供应商名称</td><td width="60px">联系人姓名</td><td>联系人电话</td><td width="60px">租车车型</td><td width="110px">车辆供电情况</td><td width="90px">现场沟通价格</td><td width="150px">现场沟通价格说明</td><td width="90px">付款周期</td><td width="60px">车辆里程数</td><td width="50px">是否提供发票</td><td width="90px">发票种类</td><td width="45px">发票税点</td><td width="80px">税费承担方</td><td width="300px">备注</td></tr>';
			foreach($objEqu as $k => $v) {
				if ($v['powerSupply'] == '1') {
					$v['powerSupply'] = "满足项目需求";
				}else{
					$v['powerSupply'] = "不满足项目需求";
				}

				if ($v['isProvideInvoice'] == '1') {
				   $v['isProvideInvoice'] = "是";
				}else{
				   $v['isProvideInvoice'] = "否";
				}

				$mailContent .= '<tr><td>'.$v['suppCode'].'</td><td>'.$v['suppName'].'</td><td>'.$v['linkManName'].'</td><td>'.$v['linkManPhone'].'</td><td>'.$v['vehicleModel'].'</td><td>'.$v['powerSupply'].'</td><td>'.number_format($v['spotPrice'],2).'</td><td style="word-break: break-all;">'.$v['spotPriceExplain'].'</td><td>'.$v['paymentCycle'].'</td><td>'.number_format($v['vehicleMileage'],2).'</td><td>'.$v['isProvideInvoice'].'</td><td>'.$v['invoice'].'</td><td>'.($v['taxPoint']?$v['taxPoint'].'%':'').'</td><td>'.$v['taxationBears'].'</td><td style="text-align:left;word-break: break-all;">'.$v['remark'].'</td></tr>';
			}
			$mailContent .= '</table>';
		}
	 	$emailDao->mailGeneral("租车申请受理结果" ,$receiverId ,$mailContent);
	 }

	/**
	 * 租车负责人打回受理发送邮件通知租车申请流相关人员
	 */
	 function sendBackMail_d( $id ) {
	 	$obj = $this->get_d( $id );
	 	$receiverId = $this->getMailReceiver2_d( $id );
	 	$emailDao = new model_common_mail();
	 	$mailContent = '您好！此邮件为租车申请打回受理通知，详细信息如下：<br>'.
		'单据编号：<span style="color:blue">'.$obj['formCode'].
		'</span><br>项目编号：<span style="color:blue">'.$obj['projectCode'].
		'</span><br>项目名称：<span style="color:blue">'. $obj['projectName'].
		'</span><br>租车性质：<span style="color:blue">'.$obj['rentalProperty'].
		'</span><br>用车地点：<span style="color:blue">'.$obj['province'].'-'.$obj['city'].
		'</span><br>用车数量：<span style="color:blue">'.$obj['useCarAmount'].
		'</span><br>申请人：<span style="color:blue">'.$obj['createName'].
		'</span><br>申请人电话：<span style="color:blue">'.$obj['applicantPhone'].
		'</span><br>申请时间：<span style="color:blue">'.$obj['createTime'].
		'</span><br>';

		$rentalcarequDao = new model_outsourcing_vehicle_rentalcarequ();
		$objEqu = $rentalcarequDao->findAll(array('parentId'=>$id));

		if (is_array($objEqu)) {
			$mailContent .= '推荐供应商信息:<br><table border="1px" cellspacing="0px" style="text-align:center"><tr style="background-color:#fff999"><td width="60px">供应商确认</td><td width="80px">部门</td><td width="100px">供应商名称</td><td width="60px">联系人姓名</td><td>联系人电话</td><td width="60px">用车数量</td><td width="100px">证件情况</td><td width="110px">车辆供电情况</td><td width="90px">付款周期</td><td width="90px">发票属性</td><td width="45px">发票税点</td><td width="200px">租车费（包含司机工资）</td><td>油费</td><td>餐饮费</td><td>住宿费</td><td width="300px">备注</td></tr>';
			foreach($objEqu as $k => $v) {
				if ($v['suppAffirm'] == 'on') {
				   $v['suppAffirm'] = "<span style='color:blue'>√</span>";
				}else{
				   $v['suppAffirm'] = "<span style='color:red'>-</span>";
				}

				if ($v['powerSupply'] == '1') {
					$v['powerSupply'] = "满足项目需求";
				}else{
					$v['powerSupply'] = "不满足项目需求";
				}

				$mailContent .= '<tr><td>'.$v['suppAffirm'].'</td><td>'.$v['deptName'].'</td><td>'.$v['suppName'].'</td><td>'.$v['linkManName'].'</td><td>'.$v['linkManPhone'].'</td><td>'.$v['useCarAmount'].'</td><td>'.$v['certificate'].'</td><td>'.$v['powerSupply'].'</td><td>'.$v['paymentCycle'].'</td><td>'.$v['invoice'].'</td><td>'.$v['taxPoint'].'%</td><td>'.$v['rentalFee'].'</td><td>'.$v['gasolineFee'].'</td><td>'.$v['catering'].'</td><td>'.$v['accommodationFee'].'</td><td style="text-align:left">'.$v['remark'].'</td></tr>';
			}
		}

		$mailContent .= '</table><br>打回原因：<span style="color:blue">'.$obj['backReason'].'</span>';
	 	$emailDao->mailGeneral("租车申请打回受理" ,$receiverId ,$mailContent);
	 }

	/**
	 * 根据租车申请id 获取租车申请流相关人员(不包含服务总监)
	 */
	function getMailReceiver_d( $id ) {
		$obj = $this->get_d( $id );
		$receiverId = '';
		$receiverId.=$obj['createId'];//申请人
		include (WEB_TOR."model/common/mailConfig.php");
		$mailNotify = $mailUser['oa_outsourcing_rentalcar'];
		$receiverId.= ','.$mailNotify['TO_ID']; //租车申请-审批通过通知人

		$esmprojectDao = new model_engineering_project_esmproject();
		$esmprojectObj = $esmprojectDao->get_d($obj['projectId']);
		$receiverId.=','.$esmprojectObj['areaManagerId'];//服务经理
		return $receiverId;
	}

	/**
	 * 根据租车申请id 获取租车申请流相关人员
	 */
	function getMailReceiver2_d( $id ) {
		$obj = $this->get_d( $id );
		$receiverId = '';
		$receiverId.=$obj['createId'];//申请人
		include (WEB_TOR."model/common/mailConfig.php");
		$mailNotify = $mailUser['oa_outsourcing_rentalcar'];
		$receiverId.= ','.$mailNotify['TO_ID']; //租车申请-审批通过通知人

		$esmprojectDao = new model_engineering_project_esmproject();
		$esmprojectObj = $esmprojectDao->get_d($obj['projectId']);
		$receiverId.=','.$esmprojectObj['areaManagerId'];//服务经理
		$receiverId.=','.$this->get_table_fields('oa_esm_office_baseinfo', "id=".$esmprojectObj['officeId'], 'mainManagerId');//服务总监
		return $receiverId;
	}

	/**
	 * 审批通过后的处理
	 */
	function dealAfterAuditPass_d( $id ) {
		$this->sendFinishMail_d( $id ); //发邮件通知受理人

		//判断推荐供应商是否存在供应商库，如果没有则添加
		$equDao = new model_outsourcing_vehicle_rentalcarequ();
		$suppDao = new model_outsourcing_outsourcessupp_vehiclesupp();
		$equObj = $equDao->findAll(array('parentId' => $id));
		if (is_array($equObj)) {
			foreach ($equObj as $v) {
				$suppObj = $suppDao->find(array('suppName' => $v['suppName']));
				if ($suppObj) {
					$equDao->edit_d(array('id' => $v['id'] ,'suppCode' => $suppObj['suppCode'] ,'suppId' => $suppObj['id']));
				} else {
					$newSupp = array(
						'suppName' => $v['suppName']
						,'linkmanName' => $v['linkManName']
						,'linkmanPhone' => $v['linkManPhone']
						,'suppCategory' => 'GYSLX-02' //手动添加默认为自由人
					);
					$suppId = $suppDao->add_d($newSupp);
					$newSuppObj = $suppDao->get_d($suppId);
					$equDao->edit_d(array('id' => $v['id'] ,'suppCode' => $newSuppObj['suppCode'] ,'suppId' => $suppId));
				}
			}
		}
	}

	/**
	 * 根据项目id获取租车预算
	 */
	function getBudgetByProId_d($projectId) {
		$esmbudgetDao = new model_engineering_budget_esmbudget();
		$budgetArr = $esmbudgetDao->getBudgetForCar_d($projectId);
		$budget = 0;
		if (!empty($budgetArr)) {
			foreach ($budget as $key => $val) {
				$budget += $val;
			}
		}
		return $budget;
	}
	/**
	 * 审批流回调方法
	 */
	function workflowCallBack($spid){
        $otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $spid );
		if($folowInfo['examines'] == "ok") {  //审批通过
			$this->dealAfterAuditPass_d( $folowInfo['objId'] );
		}
	}

    /**
     * 通过ID获取支付信息
     *
     * @param $id
     * @return bool|mixed
     */
	function getPayInfoById($id,$idType = ''){
        $rentCarPayInfoDao =  new model_outsourcing_contract_payInfo();
        if($idType == "mainId"){
            $payInfo = $rentCarPayInfoDao->find(array("mainId" => $id));
        }else{
            $payInfo = $rentCarPayInfoDao->get_d($id);
        }

        if(isset($payInfo['payTypeCode']) && $payInfo['payTypeCode'] == 'BXFQR'){// 报销付发起人
            $bankInfoSql = "select staffName as realName,oftenBank,oftenCardNum,oftenAccount from oa_hr_personnel where userAccount = '{$_SESSION['USER_ID']}';";
            $bankInfo = $this->_db->getArray($bankInfoSql);

            $payInfo['bankName'] = ($bankInfo && isset($bankInfo[0]['oftenBank']))? $bankInfo[0]['oftenBank'] : '';
            $payInfo['bankAccount'] = ($bankInfo && isset($bankInfo[0]['oftenAccount']))? $bankInfo[0]['oftenAccount'] : '';
            $payInfo['bankReceiver'] = ($bankInfo && isset($bankInfo[0]['realName']))? $bankInfo[0]['realName'] : '';
        }

        return $payInfo;
    }

    /**
     * 根据车牌号以及租车登记汇总ID读取相关的数据
     * @param $allregisterId
     * @param $carNum
     * @param $rentcarContractId
     * @return array
     */
    function getRentalCarBaseInfo($allregisterId,$carNum,$rentcarContractId){
        $backData = array();
        $carNumStr = "'".str_replace(",","','",rtrim($carNum,","))."'";
        $registerBaseInfoSql = "
        SELECT (TO_DAYS(t.endDate) - TO_DAYS(t.startDate) + 1) AS useCarDays,t.* FROM (
            SELECT min(useCarDate) AS startDate,max(useCarDate) AS endDate,driverName,rentalProperty,rentalPropertyCode,carNum FROM oa_outsourcing_register WHERE allregisterId = {$allregisterId} AND carNum in ({$carNumStr}) GROUP BY
	        YEAR (useCarDate),MONTH (useCarDate)
        ) t";
        $registerBaseInfo = $this->_db->getArray($registerBaseInfoSql);
        if($registerBaseInfo){$registerBaseInfo = $registerBaseInfo[0];}
        $backData['startDate'] = isset($registerBaseInfo['startDate'])? $registerBaseInfo['startDate'] : '';
        $backData['endDate'] = isset($registerBaseInfo['endDate'])? $registerBaseInfo['endDate'] : '';
        $backData['useCarDays'] = isset($registerBaseInfo['useCarDays'])? $registerBaseInfo['useCarDays'] : '';
        $backData['carNum'] = isset($registerBaseInfo['carNum'])? $registerBaseInfo['carNum'] : '';
        $backData['driverName'] = isset($registerBaseInfo['driverName'])? $registerBaseInfo['driverName'] : '';
        $backData['rentalProperty'] = isset($registerBaseInfo['rentalProperty'])? $registerBaseInfo['rentalProperty'] : '';
        $backData['rentalPropertyCode'] = isset($registerBaseInfo['rentalPropertyCode'])? $registerBaseInfo['rentalPropertyCode'] : '';

        $rentalContract = array();
        if($rentcarContractId != ''){
            $rentalContractSql = "select c.contractNature,c.contractNatureCode,c.contractType,c.contractTypeCode,c.contractStartDate,c.contractEndDate from oa_contract_rentcar c where c.id = {$rentcarContractId};";
            $rentalContractData = $this->_db->getArray($rentalContractSql);
            if($rentalContractData){$rentalContract = $rentalContractData[0];}
        }

        $backData['contractNature'] = isset($rentalContract['contractNature'])? $rentalContract['contractNature'] : '';
        $backData['contractNatureCode'] = isset($rentalContract['contractNatureCode'])? $rentalContract['contractNatureCode'] : '';
        $backData['contractType'] = isset($rentalContract['contractType'])? $rentalContract['contractType'] : '';
        $backData['contractTypeCode'] = isset($rentalContract['contractTypeCode'])? $rentalContract['contractTypeCode'] : '';
        $backData['contractStartDate'] = isset($rentalContract['contractStartDate'])? $rentalContract['contractStartDate'] : '';
        $backData['contractEndDate'] = isset($rentalContract['contractEndDate'])? $rentalContract['contractEndDate'] : '';

        return $backData;
    }

    /**
     * 通过项目ID查询关联的租车总费用
     *
     * @param $projectId
     * @param string $state // 需要过滤的租车登记的状态,默认为“未审批”,既已提交部门审批,但是在项目租车登记列表看到的状态为未审批的
     * @return array
     */
    function getProjectAuditingCarFee($projectId,$state = "WSP"){
        $backArr = array();
        $chkSql = "";
        switch ($state){
            case 'WSP': // 未审批
                $chkSql = <<<EOT
            SELECT
                c.id,c.projectId,c.projectCode,(if(c.rentalCarCost is null,0,c.rentalCarCost)+c.reimbursedFuel+if(c.gasolineKMCost is null,0,c.gasolineKMCost)+c.parkingCost+c.tollCost+c.mealsCost+c.accommodationCost+c.overtimePay+c.specialGas) as allCost
            FROM
                oa_outsourcing_allregister c 
            WHERE ((c.ExaStatus IN ( '部门审批', '完成', '打回' ))) AND ( ( c.projectId = '$projectId' ) ) AND c.state = 1 ORDER BY id DESC
EOT;
                break;
        }

        $totalCost = 0;
        $result = empty($chkSql)? array() : $this->_db->getArray($chkSql);
        foreach ($result as $row){
            $totalCost += isset($row['allCost'])? $row['allCost'] : 0;
        }

        $backArr['searchSql'] = $chkSql;
        $backArr['resultRow'] = $result;
        $backArr['totalCost'] = $totalCost;
        return $backArr;
    }

    /**
     * 更新项目总成本之后再加上相应的审批中的租车登记预提金额
     * @param null $projectIds
     */
    function updateProjectFieldFeeWithCarFee($projectIds = null){
        $projectIdsArr = explode(",",$projectIds);
        if(!empty($projectIdsArr)){
            foreach ($projectIdsArr as $id){
                $rentalcarCostArr = $this->getProjectAuditingCarFee($id);
                $auditingCarFee = ($rentalcarCostArr)? $rentalcarCostArr['totalCost'] : 0;
                if($auditingCarFee > 0){
                    $updateSql = "update oa_esm_project set feeAll = feeAll + {$auditingCarFee} where id = '{$id}';";
                    $this->_db->query($updateSql);
                }
            }
        }
    }
}