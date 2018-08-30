<?php


/**
 * 到款model层类
 */
class model_finance_income_income extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_income";
		$this->sql_map = "finance/income/incomeSql.php";
		parent :: __construct();
	}

	/********************新策略部分使用************************/

	private $relatedStrategyArr = array (//不同类型入库申请策略类,根据需要在这里进行追加
		'YFLX-DKD' => 'model_finance_income_strategy_income', //到款单
		'YFLX-YFK' => 'model_finance_income_strategy_prepayment', //预收款
		'YFLX-TKD' => 'model_finance_income_strategy_refund' //退款单
	);

	private $relatedCode = array (
		'YFLX-DKD' => 'income',
		'YFLX-YFK' => 'prepayment',
		'YFLX-TKD' => 'refund'
	);

	/**
	 * 根据类型返回业务名称
	 */
	public function getBusinessCode($objType){
		return $this->relatedCode[$objType];
	}

	/**
	 * 根据数据类型返回类
	 */
	public function getClass($objType){
		return $this->relatedStrategyArr[$objType];
	}

	/***************************************************************************************************
	 * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
	 *************************************************************************************************/

	/**
	 * 添加到款及到款分配
	 */
	function add_d($income ,iincome $strategy) {
		$codeRuleDao = new model_common_codeRule();
		$emailArr = null;
		try {
			$this->start_d();

			$incomeAllot = $income['incomeAllots'];
		 	unset($income['incomeAllots']);

			//自动产生到款号
			if($income['formType'] == 'YFLX-TKD'){
				$income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name . '_t','DL','ST');
			}else{
				$income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name,'DL','SK');
			}

			if( isset($income['sectionType']) && $income['sectionType'] == 'DKLX-FHK' ){
				$income['status'] = 'DKZT-FHK';
			}else{
				$income['status'] = $this->changeStatus( $income , 'val');
			}

			if(isset($income['email'])){
				$emailArr = $income['email'];
				unset($income['email']);
			}

			if($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'  ])){
				$income['isSended'] = 1;
			}

			$incomeId = parent :: add_d($income, true);

			if(is_array($incomeAllot)){
				$incomeAllotDao = new model_finance_income_incomeAllot();
				$incomeAllotDao->createBatch($incomeAllot ,array('incomeId' => $incomeId));
			}

			$income['id'] = $incomeId;

			$this->commit_d();

			if($income['formType'] == 'YFLX-TKD'){
				//插入操作记录
				$logSettringDao = new model_syslog_setting_logsetting ();
				$logSettringDao->addObjLog ( $this->tbl_name, $incomeId, $income,'录入退款' );
			}else{
				//插入操作记录
				$logSettringDao = new model_syslog_setting_logsetting ();
				$logSettringDao->addObjLog ( $this->tbl_name, $incomeId, $income,'录入到款' );
			}

			//发送邮件 ,当操作为提交时才发送
			if(isset($emailArr)){
				if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
					$this->thisMail_d($emailArr,$income);
				}
			}

			return $incomeId;
		} catch (exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 新增其它到款
	 */
	function addOther_d($income){
		$codeRuleDao = new model_common_codeRule();
		try {
			$this->start_d();

			$incomeAllot = $income['incomeAllots'];
		 	unset($income['incomeAllots']);

			$income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name,'DL','SK');
			$income['status'] = 'DKZT-YFP';
			$income['formType'] = 'YFLX-DKD';
			$income['sectionType'] = 'DKLX-HK';

			$income['allotAble'] = 0;
			$incomeAllot[1]['money'] =  $income['incomeMoney'];
			$incomeAllot[1]['allotDate'] =  day_date;

			$incomeId = parent :: add_d($income, true);

			if(is_array($incomeAllot)){
				$incomeAllotDao = new model_finance_income_incomeAllot();
				$incomeAllotDao->createBatch($incomeAllot ,array('incomeId' => $incomeId));
			}

			$this->commit_d();
			return $incomeId;
		} catch (exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 根据主键修改对象
	 */
	function edit_d($object) {
		if( $object['sectionType'] == 'DKLX-FHK' ){
			$object['status'] = 'DKZT-FHK';
		}else{
			$object['status'] = 'DKZT-WFP';
		}

		$emailArr = $object['email'];
		unset($object['email']);

		if($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'  ])){
			$object['isSended'] = 1;
		}

		try{
			$this->start_d();

			$oldObj = parent::get_d($object['id']);

			parent::edit_d($object,true);

			$this->commit_d();

			//更新操作日志
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object,'编辑到款' );

			//发送邮件 ,当操作为提交时才发送
			if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
				$this->thisMail_d($emailArr,$object,'修改');
			}

			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		};
	}

	/**
	 * 按分配金额更改到款状态
	 */
	 function changeStatus( $income , $rsType = 'act'){
		if($rsType == 'act'){
			if( $income['allotAble'] == 0 ){
				$income['status'] = 'DKZT-YFP' ;
				//关闭到款邮件
				$mailRecordDao = new model_finance_income_mailrecord();
				$mailRecordDao->closeMailrecordByIncomeId_d($income['id']);
		 	}else if( $income['allotAble'] == $income['incomeMoney']){
				$income['status'] = 'DKZT-WFP' ;
				//开启到款邮件
				$mailRecordDao = new model_finance_income_mailrecord();
				$mailRecordDao->closeMailrecordByIncomeId_d($income['id'],0);
		 	}else{
				$income['status'] = 'DKZT-BFFP' ;
				//关闭到款邮件
				$mailRecordDao = new model_finance_income_mailrecord();
				$mailRecordDao->closeMailrecordByIncomeId_d($income['id']);
		 	}
			parent::edit_d($income,true);
		}else{
			if( $income['allotAble'] == 0 ){
				return 'DKZT-YFP' ;
		 	}else if( $income['allotAble'] == $income['incomeMoney']){
				return 'DKZT-WFP' ;
		 	}else{
				return 'DKZT-BFFP' ;
		 	}
		}
	 }

	 /**
	  * 获取表单信息和从表信息
	  */
	 function getInfoAndDetail_d($id){
		$rs = $this->get_d($id);
		$incomeAllotDao = new model_finance_income_incomeAllot();
		$rs['incomeAllot'] = $incomeAllotDao->getAllotsByIncomeId($id);

		return $rs;
	 }

	 /**
	  * 调用分配详细
	  */
	 function initAllot_d($object,$perm = null ){
		if( empty($object) && $perm == 'view'){
			//没有则返回字符串
			return '<tr align="center"><td colspan="6">没有详细信息</td></tr>';
		}else if( $perm == 'view' ){
			//查看情况下返回从表页面
			$incomeAllotDao = new model_finance_income_incomeAllot();
			return $incomeAllotDao->showIncomeAllotDetailView($object);
		}else if($perm == 'push'){
            //编辑状况下返回从表页面
            $incomeAllotDao = new model_finance_income_incomeAllot();
            return $incomeAllotDao->showIncomeAllotDetailPush($object);
        }else{
			//编辑状况下返回从表页面
			$incomeAllotDao = new model_finance_income_incomeAllot();
			return $incomeAllotDao->showIncomeAllotDetail($object);
		}
	 }

	 /**
	  * 到款分配
	  */
	 function allot_d($object){
		try{
			$this->start_d();

			$oldObj = parent::get_d($object['id']);

		 	$incomeAllot = $object['incomeAllots'];
		 	unset($object['incomeAllots']);

			//插入从表
			$incomeAllotDao = new model_finance_income_incomeAllot();
			$incomeAllotDao->deleteAllotsByIncomeId($object['id']);
			if(!empty($incomeAllot)){
				$incomeAllotDao->createBatch($incomeAllot ,array('incomeId' => $object['id'],'allotDate' => day_date));
			};
			//改变源单状态
			$this->changeStatus($object);

			$this->commit_d();

			//更新操作日志
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object,'到款分配' );

			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	 }

	/**
	 * 邮件发送
	 */
	function thisMail_d($emailArr,$object,$thisAct = '新增'){
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = '到款单位：' . $object['incomeUnitName'] . ',到款金额： '.$object['incomeMoney'] . ' 。';
		$addMsg .= "\n请[收件人] ".$nameStr.' 提供该笔到款的对应合同号及客户名称给财务，并邮件回复至 '.$_SESSION['USERNAME'].'['.$_SESSION['EMAIL'].']进行分配' ;

		$emailDao = new model_common_mail();
		$emailDao->batchEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,$thisAct,$object['incomeNo'],$emailArr['TO_ID'],$addMsg,'1',$emailArr['ADDIDS']);

		$mailRecordDao = new model_finance_income_mailrecord();
		$mailInfo = array(
			'incomeId' => $object['id'],
			'incomeCode' => $object['incomeNo'],
			'sendIds' => $emailArr['TO_ID'],
			'sendNames' => $emailArr['TO_NAME'],
			'copyIds' => $emailArr['ADDIDS'],
			'copyNames' => $emailArr['ADDNAMES'],
			'title' => '到款单',
			'content' => $addMsg,
			'times' => 1,
			'createOn' => date('Y-m-d h:i:s'),
			'lastMailTime' => date('Y-m-d h:i:s'),
		);
		$mailRecordDao->add_d($mailInfo);
	}

	/**
	 *  获取默认邮件发送人
	 */
	function getSendMen_d(){
		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = isset($mailUser[$this->tbl_name][0]) ? $mailUser[$this->tbl_name][0] : array('sendUserId'=>'',
				'sendName'=>'');
		return $mailArr;
	}

	/**
	 * 单页小计加载
	 * create by kuangzw
	 * create on 2012-5-22
	 */
	function pageCount_d($object){
		if(is_array($object)){
			$newArr = array(
				'incomeMoney'=>0
			);
			foreach($object as $key => $val){
				$newArr['incomeMoney'] = bcadd($newArr['incomeMoney'],$val['incomeMoney'],2);
			}
			$newArr['incomeNo'] = '本页小计';
			$newArr['id'] = 'noId';
			$object[] = $newArr;
			return $object;
		}
	}

	/**
	 *删除方法
	 */
	function deletes_d($ids) {
		try {
			$this->start_d ();
			$idArr = explode ( ",", $ids );
			$customerTable = $this->tbl_name;
			$logSettringDao = new model_syslog_setting_logsetting ();
			foreach ( $idArr as $id ) {
				//删除对象操作日志
				$logSettringDao->deleteObjLog ( $this->tbl_name, parent::get_d ( $id ) );

				$this->deleteByPk ( $id );
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			//echo $e->message;
			throw $e;
		}
	}

	/**************************************到款导入部分*********************************/

	/**
	 * 到款导入功能
	 */
	function addExecelData_d($isCheck = 1){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$contArr = array();//合同信息数组
		$contDao = new model_projectmanagent_order_order();
		$customerArr = array();//客户信息数组
		$customerDao = new model_customer_customer_customer();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){
				if($isCheck == 1) $status = 'DKZT-YFP';
				else $status = 'DKZT-WFP';
				//行数组循环
				foreach($excelData as $key => $val){
					$val[0] = str_replace( ' ','',$val[0]);
					$val[1] = trim($val[1]);
					$val[2] = str_replace( ' ','',$val[2]);
					$val[3] = str_replace( ' ','',$val[3]);
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3])){
						continue;
					}else{
						if(!empty($val[0])){
							//判断单据日期
							$incomeDate = date('Y-m-d',(mktime(0,0,0,1, $val[0] - 1 , 1900)));
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '插入失败!没有收款日期';
							array_push( $resultArr,$tempArr );
							continue;
						}

						if($isCheck == 1){
							if(!empty($val[2])){
								//产生合同缓存数组
								$contArr[$val[2]] = isset($contArr[$val[2]]) ? $contArr[$val[2]] : $contDao->allOrderInfo($val[2]);
								if(is_array($contArr[$val[2]])){
									$orderCode = $val[2];
									$orderId = $contArr[$val[2]]['orgid'];
									if($val[2] == $contArr[$val[2]]['orderCode']){
										$orderType = $this->changeContType_d($contArr[$val[2]]['tablename'],1);
									}else{
										$orderType = $this->changeContType_d($contArr[$val[2]]['tablename'],2);
									}
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '插入失败!不存在的合同号';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '插入失败!没有合同号';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$orderCode = $val[2];
							$orderId = $orderType = "";
						}

						if(!empty($val[1])){
							//客户名称
							if(!isset($customerArr[$val[1]])){
								$rs = $customerDao->findCus($val[1]);
								if(is_array($rs)){
									$customerId = $customerArr[$val[1]]['id'] = $rs[0]['id'];
									$prov = $customerArr[$val[1]]['prov'] = $rs[0]['Prov'];
									$customerName = $val[1];
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '插入失败!客户系统中不存在此客户';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$customerId = $customerArr[$val[1]]['id'];
								$prov = $customerArr[$val[1]]['prov'];
								$customerName = $val[1];
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '插入失败!没有客户名称';
							array_push( $resultArr,$tempArr );
							continue;
						}

						if(!empty($val[3])&& $val[3]*1 == $val[3]&&$val[3]!=0){
							//判断到款金额
							$incomeMoney = $val[3];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '插入失败!没有到款金额或者到款金额为0';
							array_push( $resultArr,$tempArr );
							continue;
						}

						if($isCheck == 1){
							$inArr = array(
								'incomeDate' => $incomeDate,
								'incomeUnitName' => $customerName,
								'incomeMoney' => $incomeMoney,
								'allotAble' => 0,
								'incomeUnitId' => $customerId,
								'contractName' => $customerName,
								'contractId' => $customerId,
								'province' => $prov,
								'remark' => '系统导入数据',
								'formType' => 'YFLX-DKD',
								'status' => $status,
								'sectionType' => 'DKLX-HK',
								'incomeAllots' => array(
									array(
										'objId' => $orderId,
										'objCode' => $orderCode,
										'objType' => $orderType,
										'money' => $incomeMoney,
										'allotDate' => day_date
									)
								)
							);
						}else{
							if(empty($orderCode)){
								$inArr = array(
									'incomeDate' => $incomeDate,
									'incomeUnitName' => $customerName,
									'incomeMoney' => $incomeMoney,
									'allotAble' => 0,
									'incomeUnitId' => $customerId,
									'contractName' => $customerName,
									'contractId' => $customerId,
									'province' => $prov,
									'remark' => '系统导入数据',
									'formType' => 'YFLX-DKD',
									'status' => $status,
									'sectionType' => 'DKLX-HK'
								);

							}else{
								$inArr = array(
									'incomeDate' => $incomeDate,
									'incomeUnitName' => $customerName,
									'incomeMoney' => $incomeMoney,
									'allotAble' => 0,
									'incomeUnitId' => $customerId,
									'contractName' => $customerName,
									'contractId' => $customerId,
									'province' => $prov,
									'remark' => '系统导入数据',
									'formType' => 'YFLX-DKD',
									'status' => 'DKZT-YFP',
									'sectionType' => 'DKLX-HK',
									'incomeAllots' => array(
										array(
											'objId' => $orderId,
											'objCode' => $orderCode,
											'objType' => $orderType,
											'money' => $incomeMoney,
											'allotDate' => day_date
										)
									)
								);
							}
						}
						if($this->adForExcel_d($inArr)){
							$tempArr['result'] = '插入成功';
							$tempArr['docCode'] = '第' . $actNum .'条数据';
						}else{
							$tempArr['result'] = '插入失败';
							$tempArr['docCode'] = '第' . $actNum .'条数据';
						}
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			} else {
				msg( "文件不存在可识别数据!");
			}
		} else {
			msg( "上传文件类型不是EXCEL!");
		}
	}

	/**
	 * 合同类型转换
	 */
	function changeContType_d($val,$thisType){
		if($thisType == 1){
			switch($val){
				case 'oa_sale_order': return 'KPRK-01';break;
				case 'oa_sale_service': return 'KPRK-03';break;
				case 'oa_sale_lease': return 'KPRK-05';break;
				case 'oa_sale_rdproject': return 'KPRK-07';break;
			}
		}else{
			switch($val){
				case 'oa_sale_order': return 'KPRK-02';break;
				case 'oa_sale_service': return 'KPRK-04';break;
				case 'oa_sale_lease': return 'KPRK-06';break;
				case 'oa_sale_rdproject': return 'KPRK-08';break;
			}
		}
	}

	/**
	 * 添加到款及到款分配
	 */
	function adForExcel_d($income) {
		$codeRuleDao = new model_common_codeRule();
		$emailArr = null;
		try {
			$this->start_d();

			$incomeAllot = $income['incomeAllots'];
		 	unset($income['incomeAllots']);

			//自动产生到款号
			if($income['formType'] == 'YFLX-TKD'){
				$income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name . '_t','DL','ST');
			}else{
				$income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name,'DL','SK');
			}

			$incomeId = parent :: add_d($income, true);

			if(is_array($incomeAllot)){
				$incomeAllotDao = new model_finance_income_incomeAllot();
				$incomeAllotDao->createBatch($incomeAllot ,array('incomeId' => $incomeId));
			}

			$this->commit_d();
			return $incomeId;
		} catch (exception $e) {
			$this->rollBack();
			return null;
		}
	}
}
?>