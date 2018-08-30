<?php


/*
 * Created on 2010-8-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_contract_common_relcontract extends controller_base_action {

	function __construct() {
		$this->objName = "relcontract";
		$this->objPath = "contract_common";


		$this->docContArr = array (//不同类型出库申请策略类,根据需要在这里进行追加
			"oa_contract_contract" => "model_contract_contract_contract", //销售发货
			"oa_borrow_borrow" => "model_projectmanagent_borrow_borrow", //借用发货
			"oa_present_present" => "model_projectmanagent_present_present", //借用发货
		);

		$this->docEquArr = array (//不同类型出库申请策略类,根据需要在这里进行追加
			"oa_contract_contract" => "model_contract_contract_equ", //销售发货
			"oa_borrow_borrow" => "model_projectmanagent_borrow_borrowequ", //借用发货
			"oa_present_present" => "model_stock_outplan_strategy_presentplan", //借用发货
		);
//		//四种合同自定义清单 by LiuB
//		$this->docCusArr = array (
//		    "oa_sale_order" => "model_projectmanagent_order_customizelist", //销售发货
//			"oa_sale_lease" => "model_contract_rental_customizelist", //租赁出库
//			"oa_sale_service" => "model_engineering_serviceContract_customizelist", //服务合同出库
//			"oa_sale_rdproject" => "model_rdproject_yxrdproject_customizelist", //研发合同出库
//
//		);
		parent :: __construct();
	}



	/**
	 * 合同发货列表从表数据获取
	 */
	function c_pageJson() {
		if( $_POST['ifDeal'] ){
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo ();
			$lockDao = new model_stock_lock_lock ();
			$contType = $_POST ['docType'];
			echo $daoName = $this->docEquArr [$contType];
			$service = new $daoName ();
			$service->asc = false;
			$service->getParam ( $_POST );
			$service->searchArr ['isDel'] = 0;
			$service->searchArr ['isTemp'] = 0;
			$rows = $service->list_d ();
			foreach ( $rows as $key => $val ) {
				$rows [$key] ['lockNum'] = $lockDao->getEquStockLockNum ( $rows [$key] ['id'],null,$_POST ['docType'] );
				if( $rows [$key] ['productId'] ){
					$rows [$key] ['exeNum'] = $inventoryDao->getExeNums ( $rows [$key] ['productId'], '1' );
				}else{
					$rows [$key] ['exeNum']=0;
				}
			}
		}else{
			$rows = array();
		}
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 合同发货tab
	 */
	 function c_shipTab(){
 		$this->show->display( 'contract_contract_contract-contship-tab' );
	 }


	/**
	 * 单个合同导出excel
	 * 2012年3月15日 11:40:13
	 * @author zengzx
	 */
	function c_importCont() {
		$service = $this->service;
		$contId = $_GET['id'];
		$contObj = $service->contDao->getContractInfo($contId);
		//		echo "<pre>";
		//		print_R($contObj);
		if ($contObj['sign'] == 1) {
			$contObj['sign'] = '是';
		} else {
			$contObj['sign'] = '否';
		}
		switch ($contObj['shipCondition']) {
			case "" :
				$contObj['shipCondition'] = "";
				break;
			case "0" :
				$contObj['shipCondition'] = "立即发货";
				break;
			case "1" :
				$contObj['shipCondition'] = "通知发货";
				break;
		}
		//		echo "<pre>";
		//		print_R($contObj);
		return model_contract_common_contractExcelUtil :: exporTemplate($contObj, '', '');
	}


	//判断导入合同格式是否正确
	function infoSuc($row) {
		//判断合同号是否存在
        if(empty($row['orderCode'])){
        	$orderCode = '';
        }else{
        	$orderCode = $this->service->orderBe($row['orderTempCode'],$row['orderCode']);
        }
        //判断客户是否存在
		$customerDao = new model_customer_customer_customer ();
		$customerId = $customerDao->findCus ( $row ['customerName'] );
		foreach ( $customerId as $key => $val ) {
			$customerId [$key] = $val;
		}
		$customerId = implode ( ",", $customerId [$key] );
        //判断区域是否存在
        $areaName = $this->areaName($row['area']);
        $regionDao = new model_system_region_region ();
		$areaId = $regionDao->region ( $areaName );
		if (! empty ( $areaId )) {
			foreach ( $areaId as $key => $val ) {
				$areaId [$key] = $val;
			}
			$areaId = implode ( ",", $areaId [$key] );
		}
		//判断省市
		   $dao = new model_system_procity_city();
        $proCityIdArr = $dao->find(array("cityName" => $row['orderCity']),null,"provinceId");
         $proCityId = $proCityIdArr['provinceId'];
         $proId  = $this->province($row['orderProvince']);
		 $cityId = $this->city($row['orderCity']);
	   if(!empty($proId) && !empty($cityId) && $proCityId == $proId){
           $proCityTip = 1;
	   }
		if (empty ( $row ['orderType'] ) || !empty ($orderCode) || empty($areaId) || empty($customerId) || $proCityTip != 1) {
			return "1";
		} else if($row['orderType'] == '销售合同' || $row['orderType'] == '服务合同' || $row['orderType'] == '租赁合同' || $row['orderType'] == '研发合同'){
			return "0";
		}else {
			return "1";
		}
	}
	/**
	 * 上传EXCEL
	 */
	function c_upExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);

		$ExaDT = $_POST['import'];
		$objNameArr = array (
			0 => 'contractTypeName', //合同类型
			1 => 'contractNatureName', //合同属性
			2 => 'sign', //是否签收
			3 => 'signDate', //签约日期
			4 => 'beginDate', //开始日期
			5 => 'endDate', //结束日期
			6 => 'contractCode', //鼎利合同号
			7 => 'contractCountry', //所属国家
			8 => 'contractProvince', //所属省份
			9 => 'contractCity', //所属城市
			10 => 'areaName', //所属区域
			11 => 'areaPrincipal', //区域负责人
			12 => 'customerType', //客户性质
			13 => 'customerName', //客户名称
			14 => 'contractName', //合同名称
			15 => 'state', //合同状态
			16 => 'contractMoney', // 合同金额
			17 => 'incomeMoney', // 已收合计
			18 => 'invoiceMoney', // 开票金额
			19 => 'softMoney', // 软件开票金额
			20 => 'hardMoney', // 硬件开票金额
			21 => 'repareMoney', // 维修金额
			22 => 'serviceMoney', //  服务金额
			23 => 'invoiceTypeName', //  发票类型
			24 => 'prinvipalName', // 合同负责人
			25 => 'remark' // 备注
		);
		$this->c_addExecel($objNameArr, $ExaDT);
	}

	/**
	 * 上传EXCEl并导入其数据
	 */
	function c_addExecel($objNameArr, $ExaDT) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //改变加载类的方式
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}
				echo "<pre>";
				print_R($objectArr);
				$err = array (); //失败的数据
				$suc = array (); //成功的数据
				$sucArray = array ();
				$errArray = array ();

				foreach ($objectArr as $key => $val) {
					$flag = $this->infoSuc($objectArr[$key]);
					if ($flag == '1') {
						$errArray[$key] = $objectArr[$key];
					} else
						if ($flag == '0') {
							$sucArray[$key] = $objectArr[$key];
						}

				}
//				//四种合同信息分类
//				foreach ($sucArray as $key => $val) {
//					try {
//						$this->service->start_d();
//						$order = array ();
//						$order[$key]['sign'] = $sucArray[$key]['sign']; //是否签约
//						$order[$key]['contractCode'] = $sucArray[$key]['contractCode']; //鼎利合同号
//						$order[$key]['orderTempCode'] = $objectArr[$key]['orderTempCode']; //临时合同号
//						$order[$key]['orderName'] = $objectArr[$key]['orderName']; //合同名称
//						$order[$key]['areaName'] = $this->areaName($sucArray[$key]['area']); //销售区域
//						$order[$key]['areaPrincipal'] = $this->areaMan($sucArray[$key]['area']); //区域负责人
//						$order[$key]['areaPrincipalId'] = $this->user($order[$key]['areaPrincipal']); //区域负责人Id
//						//自动产生业务编码
//						$contractCodeDao = new model_common_codeRule();
//						$deptDao = new model_deptuser_dept_dept();
//						$dept = $deptDao->getDeptByUserId($this->user($order[$key]['areaPrincipal']));
//						$order[$key]['objCode'] = $contractCodeDao->getObjCode("oa_sale_order_objCode", $dept['Code']);
//
//						$order[$key]['areaCode'] = $this->beArea($order[$key]['areaName'], $order[$key]['areaPrincipal']); //区域编码（id）
//						$order[$key]['state'] = $this->state($sucArray[$key]['state']); //合同状态
//						$order[$key]['orderMoney'] = $sucArray[$key]['orderMoney']; //合同金额
//						$order[$key]['invoiceType'] = $this->datadict("invoice", $sucArray[$key]['invoiceType']); //开票类型  invoice
//						$order[$key]['remark'] = $sucArray[$key]['remark']; //备注
//						$order[$key]['ExaDT'] = $ExaDT['ExaDT']; //建立时间
//						$order[$key]['createTime'] = date('Y-m-d'); //创建时间
//						$order[$key]['createName'] = $_SESSION['USERNAME'];
//						$order[$key]['createId'] = $_SESSION['USER_ID'];
//
//						$order[$key]['orderNature'] = $this->datadict("order", $sucArray[$key]['orderNature']); //合同属性 Code
//						$order[$key]['orderNatureName'] = $sucArray[$key]['orderNature']; //合同属性 Name
//						$order[$key]['orderProvince'] = $sucArray[$key]['orderProvince']; //所属省份
//						$order[$key]['orderProvinceId'] = $this->province($sucArray[$key]['orderProvince']); //所属省份Id
//						$order[$key]['orderCity'] = $sucArray[$key]['orderCity']; //所属城市
//						$order[$key]['orderCityId'] = $this->city($sucArray[$key]['orderCity']); //所属城市Id
//						$order[$key]['customerType'] = $this->datadict("cusType", $sucArray[$key]['customerType']); //客户类型
//						$order[$key]['customerName'] = $sucArray[$key]['customerName']; //客户名称
//						$order[$key]['customerId'] = $this->cusId($sucArray[$key]['customerName']); //客户名称cusId
//						$order[$key]['prinvipalName'] = $sucArray[$key]['prinvipalName']; //合同负责人 （四种合同不同）
//						$order[$key]['prinvipalId'] = $this->user($order[$key]['prinvipalName']); //合同负责人Id
//						$order[$key]['ExaStatus'] = "完成"; //合同审批状态
//
//						$orderId = $this->addOrder("order", $order); //保存合同信息，并获得合同Id
//						$invoiceM = $sucArray[$key]['invoiceMoney']; //开票金额
//						$incomeM = $sucArray[$key]['received']; //到款金额
//						if (!empty ($orderId)) {
//							//开票
//							$orderInvoice[$key]['objId'] = $orderId; //合同ID
//							$orderInvoice[$key]['objType'] = $this->isTemp("order", $sucArray[$key]['contractCode']); //开票的合同类型
//							$orderInvoice[$key]['objCode'] = $this->contractCode($orderInvoice[$key]['objType'], $sucArray[$key]['contractCode'], $sucArray[$key]['orderTempCode']); //鼎利合同号
//							$orderInvoice[$key]['objName'] = $sucArray[$key]['orderName']; //合同名称
//							$orderInvoice[$key]['invoiceUnitName'] = $order[$key]['customerName']; //客户名称
//							$orderInvoice[$key]['invoiceTime'] = date("Y-m-d"); //开票时间，暂时取导入的时间处理
//							$orderInvoice[$key]['invoiceType'] = $order[$key]['invoiceType']; //开票类型
//							$orderInvoice[$key]['invoiceMoney'] = $sucArray[$key]['invoiceMoney']; //开票金额
//							$orderInvoice[$key]['softMoney'] = $sucArray[$key]['softMoney']; //软件金额
//							$orderInvoice[$key]['hardMoney'] = $sucArray[$key]['hardMoney']; //硬件金额
//							$orderInvoice[$key]['repairMoney'] = $sucArray[$key]['repairMoney']; //维修金额
//							$orderInvoice[$key]['serviceMoney'] = $sucArray[$key]['serviceMoney']; //服务金额
//							$orderInvoice[$key]['salesman'] = $sucArray[$key]['prinvipalName']; //合同负责人
//							$orderInvoice[$key]['salesmanId'] = $this->user($sucArray[$key]['prinvipalName']); //合同负责人Id
//							//到款
//							//到款单
//							$income[$key]['formType'] = "YFLX-DKD"; //到款单类型
//							$income[$key]['incomeUnitName'] = $sucArray[$key]['customerName']; //客户名称
//							$income[$key]['incomeDate'] = date("Y-m-d"); //填写到款单日期，暂取导入日期
//							$income[$key]['incomeMoney'] = $sucArray[$key]['received']; //到款金额
//							$income[$key]['allotAble'] = "0"; //
//							$income[$key]['incomeType'] = "DKFS1"; //到款类型
//							$income[$key]['sectionType'] = "DKLX-HK"; //
//							$income[$key]['status'] = "DKZT-YFP"; //
//							//分配单
//							$allot[$key]['incomeId'] = $this->income($income); //到款单Id
//							$allot[$key]['objId'] = $orderInvoice[$key]['objId']; //合同Id
//							$allot[$key]['objType'] = $this->isTemp("order", $sucArray[$key]['contractCode']); //到款的合同类型
//							$allot[$key]['money'] = $sucArray[$key]['received']; //到款分配金额
//							$allot[$key]['allotDate'] = date("Y-m-d"); //到款分配金额
//							//保存到款分配单
//							if (!empty ($incomeM)) {
//								$allotDao = new model_finance_income_incomeAllot();
//								foreach ($allot as $key => $val) {
//									$allot = $val;
//								}
//								$allotId = $allotDao->create($allot);
//							}
//
//							//保存开票
//							if (!empty ($invoiceM)) {
//								$invoiceDao = new model_finance_invoice_invoice();
//								foreach ($orderInvoice as $key => $val) {
//									$orderInvoice = $val;
//								}
//								if (empty ($orderInvoice['objId'])) {
//									throw new Exception("合同信息有误");
//									$err[] = $sucArray[$key];
//								} else {
//									$orderId = $invoiceDao->create($orderInvoice);
//								}
//							}
//
//						}
//
//						$this->service->commit_d();
//					} catch (Exception $e) {
//
//						$sucArray[$key]['errMsg'] = $e->getMessage();
//						$err[] = $sucArray[$key];
//						$this->service->rollBack();
//						echo "$objectArr[$key]['orderName']-->合同导入错误";
//						return false;
//					}
//
//				}
//				$arrinfo = array ();
//				//判断合同名称是否为空
//				//				foreach ( $suc as $k => $v ) {
//				//					if (empty ( $suc [$k] ['orderName'] )) {
//				//						unset ( $suc [$k] );
//				//					}
//				//				}
//				foreach ($suc as $k => $v) {
//					array_push($arrinfo, array (
//						"contractCode" => $suc[$k]['contractCode'],
//						"cusName" => $suc[$k]['customerName'],
//						"result" => "导入成功"
//					));
//				}
//
//				foreach ($errArray as $k => $v) {
//					if (empty ($errArray[$k]['orderTempCode']) && empty ($errArray[$k]['contractCode'])) {
//						array_push($arrinfo, array (
//							"contractCode" => $errArray[$k]['contractCode'],
//							"cusName" => $errArray[$k]['customerName'],
//							"result" => "导入失败，合同号为空"
//						));
//					} else {
//						$contractCode = $this->service->orderBe($errArray[$k]['orderTempCode'], $errArray[$k]['contractCode']);
//						if (!empty ($contractCode)) {
//							array_push($arrinfo, array (
//								"contractCode" => $errArray[$k]['contractCode'],
//								"cusName" => $errArray[$k]['customerName'],
//								"result" => "导入失败，合同号已存在"
//							));
//						} else {
//							$areaName = $this->areaName($errArray[$k]['area']);
//							$regionDao = new model_system_region_region();
//							$areaId = $regionDao->region($areaName);
//							if (empty ($areaId)) {
//								array_push($arrinfo, array (
//									"contractCode" => $errArray[$k]['contractCode'],
//									"cusName" => $errArray[$k]['customerName'],
//									"result" => "导入失败，所属区域不存在!"
//								));
//							} else {
//								$customerDao = new model_customer_customer_customer();
//								$customerId = $customerDao->findCus($errArray[$k]['customerName']);
//								if (empty ($customerId)) {
//									array_push($arrinfo, array (
//										"contractCode" => $errArray[$k]['contractCode'],
//										"cusName" => $errArray[$k]['customerName'],
//										"result" => "导入失败，客户信息不存在!"
//									));
//								} else {
//									//判断省市
//									$dao = new model_system_procity_city();
//									$proCityIdArr = $dao->find(array (
//										"cityName" => $errArray[$k]['orderCity']
//									), null, "provinceId");
//									$proCityId = $proCityIdArr['provinceId'];
//									$proId = $this->province($errArray[$k]['orderProvince']);
//									$cityId = $this->city($row['orderCity']);
//									if (empty ($proId) || empty ($cityId) || $proCityId != $proId) {
//										array_push($arrinfo, array (
//											"contractCode" => $errArray[$k]['contractCode'],
//											"cusName" => $errArray[$k]['customerName'],
//											"result" => "导入失败，省市信息错误"
//										));
//									} else {
//										array_push($arrinfo, array (
//											"contractCode" => $errArray[$k]['contractCode'],
//											"cusName" => $errArray[$k]['customerName'],
//											"result" => "导入失败，合同类型错误"
//										));
//									}
//								}
//							}
//						}
//					}
//				}
//
//				if ($arrinfo) {
//					echo util_excelUtil :: showResultOrder($arrinfo, "导入结果", array (
//						"合同号",
//						"客户名称",
//						"结果"
//					));
//				}
			} else {
				echo "文件不存在可识别数据!";
			}
		} else {
			echo "上传文件类型不是EXCEL!";
		}

	}
	/**************************************新增需求菜单************************************************************************/

	/******************start合同信息列表导出**************************/
	/**
	 * 合同管理-合同信息-合同信息导出
	 */
	function c_exportExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(600);
//		if (! isset ( $this->service->this_limit ['合同信息导出'] )) {
//			showmsg ( '没有权限,需要开通权限请联系oa管理员' );
//		}
		$stateArr = array ('0' => '未提交', '1' => '审批中', '2' => '执行中', '3' => '已关闭', '4' => '已执行','5' => '已合并', '6' => '已拆分', '7' => '已拆分' );
		$signinArr = array ('0' => '未签收', '1' => '已签收');
//
		$colIdStr = $_GET ['colId'];
		$colNameStr = $_GET ['colName'];
		$type = $_GET ['type'];
		$state = $_GET ['state'];
		$ExaStatus = $_GET ['ExaStatus'];
//		$beginDate = $_GET ['beginDate'];//开始时间
//		$endDate = $_GET ['endDate'];//截止时间
//		$ExaDT = $_GET ['ExaDT'];//建立时间
//		$areaNameArr = $_GET ['areaNameArr'];//归属区域
//		$orderCodeOrTempSearch = $_GET ['orderCodeOrTempSearch'];//合同编号
//		$prinvipalName = $_GET ['prinvipalName'];//合同负责人
//		$customerName = $_GET ['customerName'];//客户名称
//		$orderProvince = $_GET ['orderProvince'];//所属省份
//		$customerType = $_GET ['customerType'];//客户类型
//		$orderNatureArr = $_GET ['orderNatureArr'];//合同属性
//		$searchConditionKey = $_GET['searchConditionKey'];//普通搜索的Key
//		$searchConditionVal = $_GET['searchConditionVal'];//普通搜索的Val
////		$searchCondition = "$searchConditionKey = $searchConditionVal";
		//表头Id数组
		$colIdArr = explode ( ',', $colIdStr );
		$colIdArr = array_filter ( $colIdArr );
		//表头Name数组
		$colNameArr = explode ( ',', $colNameStr );
		$colNameArr = array_filter ( $colNameArr );
		//表头数组
		$colArr = array_combine ( $colIdArr, $colNameArr );
		//搜索条件
		$searchArr ['contractType'] = $type;
		if($state == null || $state == "" || $state == "undefined"){
            $searchArr ['states'] = "1,2,3,4,5,6,7";
		}else{
			$searchArr ['state'] = $state;
		}
		$searchArr ['ExaStatus'] = $ExaStatus;
//		$searchArr ['beginDate'] = $beginDate;//开始时间
//		$searchArr ['endDate'] = $endDate;//截止时间
//		$searchArr ['createTime'] = $ExaDT;//建立时间
//		$searchArr ['areaNameArr'] = $areaNameArr;//归属区域
//		$searchArr ['orderCodeOrTempSearch'] = $orderCodeOrTempSearch;//合同编号
//		$searchArr ['prinvipalName'] = $prinvipalName;//合同负责人
//		$searchArr ['customerName'] = $customerName;//客户名称
//		$searchArr ['orderProvince'] = $orderProvince;//所属省份
//		$searchArr ['customerType'] = $customerType;//客户类型
//		$searchArr ['orderNatureArr'] = $orderNatureArr;//合同属性
//		$searchArr [$searchConditionKey] = $searchConditionVal;
//		$privlimit = isset ( $this->service->this_limit ['销售区域'] ) ? $this->service->this_limit ['销售区域'] : null;
//		$arealimit = $this->service->getArea_d ();
//		$thisAreaLimit = $this->regionMerge ( $privlimit, $arealimit );
//		if (! empty ( $thisAreaLimit )) {
//			$searchArr ['areaCode'] = $thisAreaLimit;
//		}
		foreach ( $searchArr as $key => $val ) {
			if ($searchArr [$key] === null || $searchArr [$key] === '' || $searchArr [$key] == 'undefined') {
				unset ( $searchArr [$key] );
			}
		}

		$this->service->searchArr = $searchArr;
		$rows = $this->service->contDao->listBySqlId ( 'select_default' );



		//过滤型权限设置
		$limit = $this->initLimit();

		if($limit == true){
			$rows = $service->page_d ();

			//合同完成权限
			$comLimit = isset($this->service->this_limit['改变合同状态']) ? $this->service->this_limit['改变合同状态'] : null;
			if($comLimit){
				$rows = util_arrayUtil::setItemMainId('com',1,$rows);
			}

			//安全码
			$rows = $this->sconfig->md5Rows ( $rows );
		}


////		$rows = $this->service->getInvoiceAndIncome_d ( $rows );
////        $rows = $this->service->getMoneyControl_d ( $rows );//导出金额控制
//		foreach ( $rows as $index => $row ) {
//			foreach ( $row as $key => $val ) {
//				$rows [$index] ['surOrderMoney'] = $rows [$index] ['orderMoney'] - $rows [$index] ['incomeMoney'];
//				$rows [$index] ['surincomeMoney'] = $rows [$index] ['invoiceMoney'] - $rows [$index] ['incomeMoney'];
//				if ($key == 'tablename') {
////					$rows [$index] [$key] = $tablenameArr [$val];
//				} else if ($key == 'state') {
//					$rows [$index] [$key] = $stateArr [$val];
//				} else if ($key == 'signIn'){
//					$rows [$index] [$key] = $signinArr [$val];
//				}
//			}
//		}
//		//金额合计
//		$this->service->searchArr = $searchArr;
//		$rowMoney = $this->service->listBySqlId ( 'select_orderinfo_sumMoney' );
//		$rowMoney[0]['tablename'] = "金额合计";
//		$rows[] = $rowMoney[0];
//		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip ( $colIdArr );
//		unset($colIdArr['softMoney'] );
//		unset($colIdArr['repairMoney'] );
//		unset($colIdArr['serviceMoney'] );
//		unset($colIdArr['hardMoney'] );
		foreach ( $rows as $key => $row ) {
			foreach ( $colIdArr as $index => $val ) {
				$colIdArr [$index] = $row [$index];
			}
			array_push ( $dataArr, $colIdArr );
		}
//		foreach($dataArr as $key=>$val){
//			$dataArr[$key]['customerType']=$this->getDataNameByCode($val['customerType']);
//			if($val['orderMoney']*1 != 0 ){
//				$dataArr[$key]['orderTempMoney'] = 0;
//			}
//		}
//		echo "<pre>";
//		print_R($dataArr);
		return model_contract_common_contractExcelUtil::export2ExcelUtil ( $colArr, $dataArr );

	}



}
?>
