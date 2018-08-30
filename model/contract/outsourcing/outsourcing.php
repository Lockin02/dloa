<?php
/**
 * @author Show
 * @Date 2011年12月3日 星期六 10:29:00
 * @version 1.0
 * @description:外包合同 Model层
 */
class model_contract_outsourcing_outsourcing extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_outsourcing";
		$this->sql_map = "contract/outsourcing/outsourcingSql.php";
		parent::__construct ();
    }

    public $datadictFieldArr = array('outsourcing','payType','projectType','outsourceType');

    //公司权限处理 TODO
    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

    //是否
    function rtYesOrNo_d($value){
		if($value == 1){
			return '是';
		}else{
			return '否';
		}
    }

    //签收状态
    function rtIsSign_d($value){
		if($value == 1){
			return '已签收';
		}else{
			return '未签收';
		}
    }

    //合同状态
    function rtStatusKey_d($value){
    	$rt = null;
		switch($value){
			case '未提交' : $rt = 0;break;
			case '审批中' : $rt = 1;break;
			case '执行中' : $rt = 2;break;
			case '已关闭' : $rt = 3;break;
			case '变更中' : $rt = 4;break;
			case '关闭审批中' : $rt = 5;break;
			default : $rt = false;
		}
		return $rt;
    }

    /**
     * SQL配置
     */
    function initSetting_c($thisVal){
		switch($thisVal){
			case '1' : return 'larger';break;
			case '2' : return 'largerEqu';break;
			case '3' : return 'equ';break;
			case '4' : return 'lessEqu';break;
			case '5' : return 'less';break;
			default : return 'noThisSetting';
		}
    }

	/************************* 增删改查 ***********************/
	/**
	 * 重写新增方法
	 */
	function add_d($object){
//		echo "<pre>";
//		print_r($object);
//		$projectrentalDao = new model_contract_outsourcing_projectrental();
//		$projectRental = $projectrentalDao->dataFormat_d($object['projectRental']);//格式化数据,转成正常数据
//		print_r($projectRental);
//		die();

// 		//业务前签约单位信息处理
// 		$signCompanyDao = new model_contract_signcompany_signcompany();
// 		$signCompanyArr = array(
// 			'signCompanyName' => $object['signCompanyName'],
// 			'proName' => $object['proName'],
// 			'proCode' => $object['proCode'],
// 			'phone' => $object['phone'],
// 			'address' => $object['address'],
// 			'linkman' => $object['linkman']
// 		);
// 		$signCompanyDao->saveCompanyInfo_d($signCompanyArr);

		//获取付款申请信息
		$payapplyInfo = $object['payapply'];
		unset($object['payapply']);
		//人员租赁取数
		$items = $object['items'];
		unset($object['items']);
		//项目整包取数
		$projectRental = $object['projectRental'];
		unset($object['projectRental']);

		try{
			$this->start_d();//开启事务

			//业务编号生成部分
			$deptDao = new model_deptuser_dept_dept();
			$dept = $deptDao->getDeptByUserId($object['principalId']);
			$orderCodeDao = new model_common_codeRule ();
			$object['objCode']=$orderCodeDao->getObjCode($this->tbl_name."_objCode",$dept['Code']);

			if(ORDERCODE_INPUT == 1) $object['orderCode'] = $object['objCode'];//如果系统生成编号,则合同号等于业务编号

			//数据字典处理
			$object = $this->processDatadict($object);

			$object['ExaStatus'] = WAITAUDIT;
			$object['status'] = 0;

			//调用父类
			$newId = parent :: add_d($object,true);

			if($object['isNeedPayapply']){//插入付款申请信息
				$payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
				$payapplyInfo['contractId'] = $newId;
				$payapplyInfo['contractType'] = $this->tbl_name;
				$payapplyInfoDao->dealInfo_d($payapplyInfo);
			}
//			if ($items && $object['outsourcing'] == 'HTWBFS-02') {//人员租赁
//				$personrentalDao = new model_contract_personrental_personrental();
//				$items = util_arrayUtil :: setArrayFn(array ('mainId' => $newId), $items ,array('personLevelName'));
//				$personrentalDao->saveDelBatch($items);
//			}else{//整包/分包
//				$projectrentalDao = new model_contract_outsourcing_projectrental();
//				$projectRental = $projectrentalDao->dataFormat_d($projectRental);//格式化数据,转成正常数据
//				$projectRental = util_arrayUtil :: setArrayFn(array('mainId' => $newId), $projectRental);
//				$projectrentalDao->saveDelBatch($projectRental);
//			}

			//更新附件关联关系
			$this->updateObjWithFile($newId,$object['orderCode']);

			$this->commit_d();
			return $newId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写修改方法
	 */
	function edit_d($object){
		//外包状态字段数据字典处理
		if(isset($object['outsourceType']) && !empty($object['outsourceType'])){
			//数据字典处理
			$object = $this->processDatadict($object);
		}
		return parent::edit_d($object,true);
	}

	/**
	 * 编辑方法
	 */
	function editInfo_d($object){
//		echo "<pre>";
//		print_r($object);
//		$projectrentalDao = new model_contract_outsourcing_projectrental();
//		$projectRental = $projectrentalDao->dataFormat_d($object['projectRental']);//格式化数据,转成正常数据
//		print_r($projectRental);
//		die();

		//获取付款申请信息
		$payapplyInfo = $object['payapply'];
		unset($object['payapply']);
		//人员租赁取数
		$items = $object['items'];
		unset($object['items']);
		//项目整包取数
		$projectRental = $object['projectRental'];
		unset($object['projectRental']);

		try{
			$this->start_d();

			//外包状态字段数据字典处理
			if(isset($object['outsourceType']) && !empty($object['outsourceType'])){
				//数据字典处理
				$object = $this->processDatadict($object);
			}

			$payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
			//付款信息处理
			if($object['isNeedPayapply'] == 1){
				//更新付款申请信息
				$payapplyInfo['contractId'] = $object['id'];
				$payapplyInfo['contractType'] = $this->tbl_name;
				$payapplyInfoDao->dealInfo_d($payapplyInfo);
			}else{
				$object['isNeedPayapply'] = 0;
				//删除付款申请信息
				$payapplyInfoDao->delete(array('contractId' =>$object['id'],'contractType'=> $this->tbl_name));
			}

			parent::edit_d($object,true);

//			$personrentalDao = new model_contract_personrental_personrental();//人员租赁实例
//			$projectrentalDao = new model_contract_outsourcing_projectrental();//整包实例
//			if($object['outsourcing'] == "HTWBFS-02"){//人员租赁
//				$items = util_arrayUtil :: setArrayFn(array ('mainId' => $object['id']), $items ,array('personLevelName'));
//				$personrentalDao->saveDelBatch($items);
//
//				$projectrentalDao->delItemInfo_d($object['id']);//删除整包信息
//			}else{//整包/分包
//				$projectRental = $projectrentalDao->dataFormat_d($projectRental);//格式化数据,转成正常数据
//				$projectRental = util_arrayUtil :: setArrayFn(array('mainId' => $object['id']), $projectRental);
//				$projectrentalDao->saveDelBatch($projectRental);
//
//				$personrentalDao->delItemInfo_d($object['id']);//删除人员租赁信息
//			}
			$this->commit_d();
			return $object['id'];
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写删除方法
	 */
	function delete_d($id){
		try{
			$this->start_d();

			//修改外包立项状态
			$arr = $this->find(array('id' => $id ));
			if(!empty($arr['approvalId'])){
				if($arr['outsourcing'] == 'HTWBFS-01'){
					$this->changeIsAddContract_d(0, $arr['approvalId'], 0);
				}else{
					$this->changeIsAddContract_d(1, $arr['personrentalId'], 0);
				}
			}

			//删除结算
			$this->delete(array('id' => $id));

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 获取合同信息及付款信息
	 */
	function getInfo_d($id){
		$obj = parent::get_d($id);

		$payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
		$payapplyArr = $payapplyInfoDao->getPayapplyInfo_d($id,$this->tbl_name);

		$obj['outPayType'] = $obj['payType'];
		$obj['outRemark'] = $obj['remark'];

		$obj = array_merge($obj,$payapplyArr);
		return $obj;
	}

	/**
	 *获取外包合同所信息
	 */
	function getInfoProject_d ($projectId){
		$projectDao = new model_engineering_project_esmproject();
		$obj = $projectDao->get_d($projectId);
		return $obj;
	}

	/**
	 * 新增申请盖章信息
	 */
	function stamp_d($obj){
		$stampDao = new model_contract_stamp_stamp();
		try{
			$this->start_d();

			//获取对应对象的最大批次号
			$maxBatchNo = $stampDao->get_table_fields($stampDao->tbl_name," contractType = 'HTGZYD-01' and contractId=". $obj['contractId'],"max(batchNo)");
			$obj['batchNo'] = $maxBatchNo + 1;

			//新增盖章信息
			$obj['contractType'] = 'HTGZYD-01';
			$stampDao->addStamps_d($obj,true);

			//更新合同字段信息
			$this->edit_d(array('id' => $obj['contractId'],'isNeedStamp' => 1,'stampType' => $obj['stampType'],'isStamp' => 0));

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 *审批成功后在盖章列表添加信息
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];

		$object = $this->get_d($objId);
	 	if($object['isNeedStamp'] == "1" &&$object['ExaStatus'] == AUDITED){
	 		if($userId == $object['createId']){
	 			$userName = $object['createName'];
	 		}else{
	 			$userName = $object['principalName'];
	 		}
			//初始化盖章类
			$stampDao = new model_contract_stamp_stamp();
			$stampArr = $stampDao->find(array('contractId' => $object['id'],'contractType' => 'HTGZYD-01' ),null,'id');
			if(empty($stampArr)){
		 		//创建数组 - 模板插入数组
				$object = array ("contractId" => $object['id'],
					"contractCode" => $object['orderCode'],
					"contractType" =>  'HTGZYD-01',
					"contractName" => $object['orderName'],
					"signCompanyName" => $object['signCompanyName'],
					"signCompanyId" => $object['signCompanyId'],
					"objCode" => $object['objCode'],
					"contractMoney" => $object['orderMoney'],
					"applyUserId" => $userId,
					"applyUserName" => $userName,
					"applyDate" => day_date,
					"stampType" => $object['stampType'],
					"status" => 0
				);
				$stampDao->addStamps_d($object);
			}
			return 1;
	 	}
	 	return 1;
	}

	/**
	 *外包合同立项付款申请审批成功后处理
	 */
	function dealAfterAuditPayapply_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];

		$object = $this->getInfo_d($objId);
	 	if($object['ExaStatus'] == AUDITED){

			try{
				$this->start_d();

				//主要责任人设定
		 		if($userId == $object['createId']){
		 			$userName = $object['createName'];
		 		}else{
		 			$userName = $object['principalName'];
		 		}

				//如果需要盖章
		 		if($object['isNeedStamp'] == "1"){
					$stampDao = new model_contract_stamp_stamp();
					$stampArr = $stampDao->find(array('contractId' => $object['id'],'contractType' => 'HTGZYD-01' ),null,'id');
					if(empty($stampArr)){
				 		//创建数组
						$stampArr = array ("contractId" => $object['id'],
							"contractCode" => $object['orderCode'] ,
							"contractType" =>  'HTGZYD-01',
							"contractName" => $object['orderName'],
							"signCompanyName" => $object['signCompanyName'],
							"signCompanyId" => $object['signCompanyId'],
							"objCode" => $object['objCode'],
							"contractMoney" => $object['orderMoney'],
							"applyUserId" => $userId,
							"applyUserName" => $userName,
							"applyDate" => day_date,
							"stampType" => $object['stampType'] ,
							"status" => 0
						);
						$stampDao->addStamps_d($stampArr);
					}
		 		}

				//付款申请处理
				$payablesapplyDao = new model_finance_payablesapply_payablesapply();
				//构建付款申请数组
				$payablesapplyArr = array(
					'deptName' => $object['deptName'],
					'deptId' => $object['deptId'],
					'salesman' => $userName,
					'salesmanId' => $userId,
					'supplierName' => $object['signCompanyName'],
					'payMoney' => $object['applyMoney'],
					'payMoneyCur' => $object['applyMoney'] * $object['rate'],
					'payDate' => $object['formDate'],
					'formDate' => day_date,
					'feeDeptName' => $object['feeDeptName'],
					'feeDeptId' => $object['feeDeptId'],
					'sourceCode' => $object['orderCode'],
					'bank' => $object['bank'],
					'account' => $object['account'],
					'payFor' => $object['payFor'],
					'payType' => $object['payType'],
					'remark' => $object['remark'],
					'payCondition' => $object['payCondition'],
					'sourceType' => 'YFRK-03',
					'ExaStatus' => '完成',
					'ExaDT' => day_date,
					'exaId' => $object['id'],
					'exaCode' => $this->tbl_name,
					'ExaUser' => $folowInfo['USER_NAME'],
					'ExaUserId' => $folowInfo['USER_ID'],
					'ExaContent' => $folowInfo['content'],
					'payDesc' => $object['payDesc'],
					'isEntrust' => $object['isEntrust'],
					'currency' => $object['currency'],
					'currencyCode' => $object['currencyCode'],
					'place' => $object['place'],
					'rate' => $object['rate'],
					'businessBelong' => $object['businessBelong'],
					'businessBelongName' => $object['businessBelongName'],
					'formBelong' => $object['formBelong'],
					'formBelongName' => $object['formBelongName'],
					'detail' => array(
						0 => array(
							'money' => $object['applyMoney'],
							'objId' => $object['id'],
							'objCode' => $object['orderCode'],
							'objType' => 'YFRK-03',
							'purchaseMoney' => $object['orderMoney'],
							'payDesc' => '外包项目立项付款审批单据',
							'expand1' =>  $object['outsourceTypeName'],
							'expand2' =>  $object['projectCode'],
							'expand3' =>  $object['projectId'],
							'orgFormType' =>  $object['projectName']
						)
					)
				);
				$payablesapplyArr ['createId'] = $payablesapplyArr ['updateId'] = $userId;
				$payablesapplyArr ['createName'] = $payablesapplyArr ['updateName'] = $userName;
				$payablesapplyArr ['createTime'] = $payablesapplyArr ['updateTime'] =date ( "Y-m-d H:i:s" );

				$payablesapplyDao->addOnly_d($payablesapplyArr);

				$this->commit_d();
			}catch(Exception $e){
				$this->rollBack();
			}

			return 1;
	 	}
	 	return 1;
	}

	/**
	 * 单页小计处理
	 */
	function pageCount_d($object){
		if(is_array($object)){
			//小计初始金额
			$newArr = array(
				'allCount' => 0,'applyedMoney'=>0,'payedMoney'=>0 ,'orderMoney' => 0,
				'initPayMoney' => 0,'initInvoiceMoney' => 0,'orgApplyedMoney' => 0,'orgPayedMoney' => 0,'orgAllCount' => 0
			);

			foreach($object as $key => $val){
				$newArr['allCount'] = bcadd($newArr['allCount'],$val['allCount'],2);
				$newArr['applyedMoney'] = bcadd($newArr['applyedMoney'],$val['applyedMoney'],2);
				$newArr['payedMoney'] = bcadd($newArr['payedMoney'],$val['payedMoney'],2);
				$newArr['orderMoney'] = bcadd($newArr['orderMoney'],$val['orderMoney'],2);
				$newArr['initPayMoney'] = bcadd($newArr['initPayMoney'],$val['initPayMoney'],2);
				$newArr['initInvoiceMoney'] = bcadd($newArr['initInvoiceMoney'],$val['initInvoiceMoney'],2);
				$newArr['orgApplyedMoney'] = bcadd($newArr['orgApplyedMoney'],$val['orgApplyedMoney'],2);
				$newArr['orgPayedMoney'] = bcadd($newArr['orgPayedMoney'],$val['orgPayedMoney'],2);
				$newArr['orgAllCount'] = bcadd($newArr['orgAllCount'],$val['orgAllCount'],2);
			}
			$newArr['createDate'] = '本页小计';
			$newArr['id'] = 'noId';
			$object[] = $newArr;
			return $object;
		}
	}

	/**************** S 导入导出些列 ************************/

	/**
	 * 导入更新项目数据
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
		$contractArr = array();//合同缓存数组
		$otherDataDao = new model_common_otherdatas();
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		$provinceArr = array();//省份数组
		$provinceDao = new model_system_procity_province();
		$esmprojectArr = array();//工程项目数组
		$esmprojectDao = new model_engineering_project_esmproject();
		$rdprojectArr = array();//研发项目数组
		$rdprojectDao = new model_rdproject_project_rdproject();
		$deptDao = new model_deptuser_dept_dept();
		$orderCodeDao = new model_common_codeRule ();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
//			print_r($excelData);
			if(is_array($excelData)){
				//行数组循环
				foreach($excelData as $key => $val){
					$val[0] = str_replace( ' ','',$val[0]);
					$actNum = $key + 2;
					$updateArr = array();

					//新增或者是更新数据
					//1为新增
					//0为更新
					$addOrUpdate = 1;

					//外包性质
					if(!empty($val[0])){
						$val[0] = trim($val[0]);
						if(!isset($datadictArr[$val[0]])){
							$rs = $datadictDao->getCodeByName('HTWB',$val[0]);
							if(!empty($rs)){
								$outsourceType = $datadictArr[$val[0]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '导入失败!不存在的外包性质';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$outsourceType = $datadictArr[$val[0]]['code'];
						}
						$updateArr['outsourceType'] = $outsourceType;
						$updateArr['outsourceTypeName'] = $val[0];
					}

					//合同签约日期
					if(!empty($val[1])&& $val[1] != '0000-00-00'){
						$val[1] = trim($val[1]);

						if(!is_numeric($val[1])){
							$updateArr['signDate'] = $val[1];
						}else{
							$actEndDate = date('Y-m-d',(mktime(0,0,0,1, $val[1] - 1 , 1900)));
							$updateArr['signDate'] = $actEndDate;
						}
					}else{
						$updateArr['signDate'] = day_date;
					}

					//项目名称
					if(!empty($val[2])){
						$updateArr['projectName'] = $val[2];
					}

					//项目编号
					if($outsourceType != 'HTWB01'){
						if(!empty($val[3])){
							$updateArr['projectCode'] = $projectCode = $val[3];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '更新失败!没有填写项目编号';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//研发
						if($outsourceType == 'HTWB02'){
							if(!isset($rdprojectArr[$val[3]])){
								$rs = $rdprojectDao->getProjectInfo_d($val[3]);
								if(empty($rs)){
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '更新失败!不存在的研发项目['.$val[3].']，请先新增项目信息';
									array_push( $resultArr,$tempArr );
									continue;
								}else{
									$rdprojectArr[$val[3]] = $rs;
								}
							}
							$updateArr['projectId'] = $rdprojectArr[$val[3]]['id'];
						}else if($outsourceType == 'HTWB03'){//工程项目
							if(!isset($esmprojectArr[$val[3]])){
								$rs = $esmprojectDao->getProjectInfo_d($val[3]);
								if(empty($rs)){
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '更新失败!不存在的工程项目['.$val[3].']，请先新增项目信息';
									array_push( $resultArr,$tempArr );
									continue;
								}else{
									$esmprojectArr[$val[3]] = $rs;
								}
							}
							$updateArr['projectId'] = $esmprojectArr[$val[3]]['id'];
							$updateArr['projectType'] = $esmprojectArr[$val[3]]['category'];

							//工程项目类型
							if(!empty($updateArr['projectType'])){
								$updateArr['projectType'] = trim($updateArr['projectType']);
								if(!isset($datadictArr[$updateArr['projectType']])){
									$rs = $datadictDao->getCodeByName('XMLB',$updateArr['projectType']);
									if(!empty($rs)){
										$outsourcing = $datadictArr[$updateArr['projectType']]['code'] = $rs;
									}else{
										$tempArr['docCode'] = '第' . $actNum .'条数据';
										$tempArr['result'] = '导入失败!不存在的项目类别';
										array_push( $resultArr,$tempArr );
										continue;
									}
								}else{
									$outsourcing = $datadictArr[$updateArr['projectType']]['code'];
								}
								$updateArr['projectTypeName'] = $updateArr['projectType'];
							}
						}
					}

					//鼎利合同号
					if(!empty($val[4])){
						$updateArr['orderCode'] = $val[4];
						if(!isset($contractArr[$updateArr['orderCode']])){
							$rs = $this->find(array('orderCode' => $updateArr['orderCode']),null,'id');
							if(is_array($rs)){
								$addOrUpdate = 0;
							}
						}else{
							$addOrUpdate = 0;
						}
					}

					//合同名称
					if(!empty($val[5])){
						$updateArr['orderName'] = $val[5];
					}

					//外包公司名称
					if(!empty($val[6])){
						$updateArr['signCompanyName'] = $val[6];
						$basicinfoDao = new model_outsourcing_supplier_basicinfo();
						$basicinfoArr = $basicinfoDao->find(array('suppName' => $val[6]),null,'id');
						if(!empty($basicinfoArr['id'])){
							$updateArr['signCompanyId'] = $basicinfoArr['id'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!没有对应的供应商';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}

					//省份
					if(!empty($val[7])){
						if(!isset($provinceArr[$val[7]])){
							$provinceCode = $provinceDao->getCodeByName($val[7]);
							if(!empty($provinceCode)){
								$provinceArr[$val[7]]['provinceCode'] = $provinceCode;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '导入失败!没有对应的省份';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						$updateArr['proCode'] = $provinceArr[$val[7]]['provinceCode'];
						$updateArr['proName'] = $val[7];
					}

					//外包合同号
					if(!empty($val[8])){
						$updateArr['outContractCode'] = $val[8];
					}

					//外包方式
					if(!empty($val[9])){
						$val[9] = trim($val[9]);
						if(!isset($datadictArr[$val[9]])){
							$rs = $datadictDao->getCodeByName('HTWBFS',$val[9]);
							if(!empty($rs)){
								$outsourcing = $datadictArr[$val[9]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '导入失败!不存在的外包性质';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$outsourcing = $datadictArr[$val[9]]['code'];
						}
						$updateArr['outsourcing'] = $outsourcing;
						$updateArr['outsourcingName'] = $val[9];
					}

					//合同负责人
					if(!empty($val[10])){
						$val[10] = trim($val[10]);
						if(!isset($userArr[$val[10]])){
							$rs = $otherDataDao->getUserInfo($val[10]);
							if(!empty($rs)){
								$userArr[$val[10]] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '更新失败!不存在的合同负责人';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						$updateArr['principalId'] = $userArr[$val[10]]['USER_ID'];
						$updateArr['deptId'] = $userArr[$val[10]]['DEPT_ID'];
						$updateArr['deptName'] = $userArr[$val[10]]['DEPT_NAME'];
						$updateArr['principalName'] = $val[10];

						$deptCode = $userArr[$val[10]]['DEPT_CODE'];
					}else{
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '更新失败!没有录入合同负责人';
						array_push( $resultArr,$tempArr );
						continue;
					}

					//付款方式
					if(!empty($val[11])){
						$val[11] = trim($val[11]);
						if(!isset($datadictArr[$val[11]])){
							$rs = $datadictDao->getCodeByName('HTFKFS',$val[11]);
							if(!empty($rs)){
								$payType = $datadictArr[$val[11]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '导入失败!不存在的付款方式';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$payType = $datadictArr[$val[11]]['code'];
						}
						$updateArr['payType'] = $payType;
						$updateArr['payTypeName'] = $val[11];
					}

					//开始日期
					if(!empty($val[13])&& $val[13] != '0000-00-00'){
						$val[13] = trim($val[13]);

						if(!is_numeric($val[13])){
							$updateArr['beginDate'] = $val[13];
						}else{
							$actEndDate = date('Y-m-d',(mktime(0,0,0,1, $val[13] - 1 , 1900)));
							$updateArr['beginDate'] = $actEndDate;
						}
					}

					//结束日期
					if(!empty($val[14])&& $val[14] != '0000-00-00'){
						$val[14] = trim($val[14]);

						if(!is_numeric($val[14])){
							$updateArr['endDate'] = $val[14];
						}else{
							$actEndDate = date('Y-m-d',(mktime(0,0,0,1, $val[14] - 1 , 1900)));
							$updateArr['endDate'] = $actEndDate;
						}
					}

					//初始付款合计和开票金额
					$updateArr['orderMoney'] = empty($val[12]) ? 0 : sprintf("%f",abs(trim($val[12])));
					$updateArr['initPayMoney'] = empty($val[15]) ? 0 : sprintf("%f",abs(trim($val[15])));
					$updateArr['initInvoiceMoney'] = empty($val[16]) ? 0 : sprintf("%f",abs(trim($val[16])));

					//项目状态
					if($val[17]){
						$rt = $this->rtStatusKey_d($val[17]);
						if($rt){
							$updateArr['status'] = $rt;
							if($val[17] == '未提交'){
								$updateArr['ExaStatus'] = '待提交';
							}else{
								$updateArr['ExaStatus'] = '完成';
							}
							$updateArr['ExaDT'] = day_date;
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!不存在的合同状态';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$updateArr['status'] = 0;
					}

					//联系人
					if(!empty($val[18])){
						$updateArr['linkman'] = $val[18];
					}

					//联系电话
					if(!empty($val[19])){
						$updateArr['phone'] = $val[19];
					}

					//联系地址
					if(!empty($val[20])){
						$updateArr['address'] = $val[20];
					}

					//归属公司
					if(!empty($val[21])){
						$branchDao = new model_deptuser_branch_branch();
						$branchObj = $branchDao->find(array('NameCN' => $val[21]));
						if(!empty($branchObj)){
							$updateArr['businessBelongName'] = $val[21];
							$updateArr['businessBelong'] = $branchObj['NamePT'];
							$updateArr['formBelong'] = $branchObj['NamePT'];
							$updateArr['formBelongName'] = $branchObj['NameCN'];
						}
						else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!不存在的归属公司';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '更新失败!没有录入归属公司';
						array_push( $resultArr,$tempArr );
						continue;
					}

					//数据处理部分
					if($addOrUpdate == 1){
						//业务编号处理
						$updateArr['objCode']=$orderCodeDao->getObjCode($this->tbl_name."_objCode",$deptCode);
						//新增项目数据
						$newId = parent::add_d($updateArr,true);
						if($newId){
							$tempArr['result'] = '导入成功';
							$tempArr['docCode'] = '第' . $actNum .'行数据';
						}else{
							$tempArr['result'] = '导入失败';
							$tempArr['docCode'] = '第' . $actNum .'行数据';
						}
					}else{
						//更新项目数据
						$this->update(array('orderCode' => $updateArr['orderCode']),$updateArr);
						$tempArr['result'] = '更新数据成功';
						$tempArr['docCode'] = '第' . $actNum .'行数据';
					}

//					echo "<pre>";
//					print_r($updateArr);

					array_push( $resultArr,$tempArr );
				}
				return $resultArr;
			} else {
				msg( "文件不存在可识别数据!");
			}
		} else {
			msg( "上传文件类型不是EXCEL!");
		}

	}

	/**************** E 导入导出系列 ************************/

	/***************** S 变更系列 *********************/
	/**
	 * 变更操作
	 */
	function change_d($object){
		try{
			$this->start_d();

			//数据字典处理
			$object = $this->processDatadict($object);

			//实例化变更类
			$changeLogDao = new model_common_changeLog ( 'outsourcing' );

//			echo "<pre>";
//			print_r($object);

			//附件处理
			$object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

			//建立变更信息
			$tempObjId = $changeLogDao->addLog ( $object );

			$this->commit_d();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 变更审批完成后更新合同状态
	 */
	function dealAfterAuditChange_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];

		$obj = $this->get_d($objId);
 		try{
 			$this->start_d();

			//变更信息处理
	 		$changeLogDao = new model_common_changeLog ( 'outsourcing' );
			$changeLogDao->confirmChange_d ( $obj );

 			if($obj['ExaStatus'] == AUDITED){
				//源单状态处理
				if($obj['isNeedRestamp'] == 1 && $obj['isStamp'] == 1){//需要重新盖章
					//直接重置盖章状态位，将现有盖章记录关闭
					$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'isStamp' => 0,'isNeedRestamp' => 0,'isNeedStamp' =>0,'stampType' =>''));

				}elseif($obj['isNeedStamp'] == 1 && $obj['isStamp'] == 0){//正在盖章的处理

					$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'isStamp' => 0,'isNeedRestamp' => 0,'isNeedStamp' =>0,'stampType' =>''));

					$stampDao = new model_contract_stamp_stamp();
					$newId = $stampDao->closeWaiting_d($obj['originalId'],'HTGZYD-01');
				}else{//非盖章处理
					$this->update(array('id'=>$obj['originalId']),array('status' => 2,'isNeedRestamp' => 0));
				}
 			}else{
            	$this->update(array('id'=>$obj['originalId']),array('status' => 2));
 			}

 			$this->commit_d();
 		}catch(Exception $e){
 			$this->rollBack();
 		}
	 	return 1;
	}
	/***************** E 变更系列 *********************/

	/***************** S 签收系列 *********************/
	/**
	 * 合同签收 - 签收功能
	 */
	function sign_d($object){
		//实例化变更类
		$changeLogDao = new model_common_changeLog ( 'outsourcingSign' );
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
 			parent::edit_d($signInfo,true);

			//数据处理
			$object = $this->processDatadict($object);

			//附件处理
			$object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

			//建立变更信息
			$tempObjId = $changeLogDao->addLog ( $object );

			$changeObj = $object;
			$changeObj['id'] = $tempObjId;
			$changeObj['originalId'] = $changeObj['oldId'];

			//变更确认
			$changeLogDao->confirmChange_d ( $changeObj );

 			$this->commit_d();
 			return $tempObjId;
 		}catch(Exception $e){
 			$this->rollBack();
 			return false;
 		}
	}

	/***************** E 签收系列 *********************/

	/**
	 * 修改外包立项的生成合同的状态
	 * @param unknown $type
	 * @param unknown $changeId
	 * @param unknown $status
	 */
	function changeIsAddContract_d($type,$changeId,$status){
		$approvalDao = new model_outsourcing_approval_basic();
		$personrentalDao = new model_outsourcing_approval_persronRental();
		if($type == 0){
			$approvalDao->update(array('id'=> $changeId),array('isAllAddContract' => $status));
		}else{
			$changeId = explode(',', $changeId);
			foreach ($changeId as $key =>$val){
				$personrentalDao->update(array('id'=> $val),array('isAddContract' => $status));
			}
			$arr=$personrentalDao->find(array('id' => $changeId[0]),null,mainId);
			$count=$personrentalDao->findCount(array('mainId' => $arr['mainId'],'isAddContract' => 0));
			if($count == 0){
				$approvalDao->update(array('id'=> $arr['mainId']),array('isAllAddContract' => 1));
			}else{
				$approvalDao->update(array('id'=> $arr['mainId']),array('isAllAddContract' => 0));
			}
		}
	}

	/**
	 * 获取合同信息 - 不区分公司
	 */
	function getList_d($ids){
		$this->setCompany(0);
		$this->searchArr = array('ids' => $ids);
		return $this -> list_d();
	}
}