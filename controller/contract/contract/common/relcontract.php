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


		$this->docContArr = array (//��ͬ���ͳ������������,������Ҫ���������׷��
			"oa_contract_contract" => "model_contract_contract_contract", //���۷���
			"oa_borrow_borrow" => "model_projectmanagent_borrow_borrow", //���÷���
			"oa_present_present" => "model_projectmanagent_present_present", //���÷���
		);

		$this->docEquArr = array (//��ͬ���ͳ������������,������Ҫ���������׷��
			"oa_contract_contract" => "model_contract_contract_equ", //���۷���
			"oa_borrow_borrow" => "model_projectmanagent_borrow_borrowequ", //���÷���
			"oa_present_present" => "model_stock_outplan_strategy_presentplan", //���÷���
		);
//		//���ֺ�ͬ�Զ����嵥 by LiuB
//		$this->docCusArr = array (
//		    "oa_sale_order" => "model_projectmanagent_order_customizelist", //���۷���
//			"oa_sale_lease" => "model_contract_rental_customizelist", //���޳���
//			"oa_sale_service" => "model_engineering_serviceContract_customizelist", //�����ͬ����
//			"oa_sale_rdproject" => "model_rdproject_yxrdproject_customizelist", //�з���ͬ����
//
//		);
		parent :: __construct();
	}



	/**
	 * ��ͬ�����б�ӱ����ݻ�ȡ
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
	 * ��ͬ����tab
	 */
	 function c_shipTab(){
 		$this->show->display( 'contract_contract_contract-contship-tab' );
	 }


	/**
	 * ������ͬ����excel
	 * 2012��3��15�� 11:40:13
	 * @author zengzx
	 */
	function c_importCont() {
		$service = $this->service;
		$contId = $_GET['id'];
		$contObj = $service->contDao->getContractInfo($contId);
		//		echo "<pre>";
		//		print_R($contObj);
		if ($contObj['sign'] == 1) {
			$contObj['sign'] = '��';
		} else {
			$contObj['sign'] = '��';
		}
		switch ($contObj['shipCondition']) {
			case "" :
				$contObj['shipCondition'] = "";
				break;
			case "0" :
				$contObj['shipCondition'] = "��������";
				break;
			case "1" :
				$contObj['shipCondition'] = "֪ͨ����";
				break;
		}
		//		echo "<pre>";
		//		print_R($contObj);
		return model_contract_common_contractExcelUtil :: exporTemplate($contObj, '', '');
	}


	//�жϵ����ͬ��ʽ�Ƿ���ȷ
	function infoSuc($row) {
		//�жϺ�ͬ���Ƿ����
        if(empty($row['orderCode'])){
        	$orderCode = '';
        }else{
        	$orderCode = $this->service->orderBe($row['orderTempCode'],$row['orderCode']);
        }
        //�жϿͻ��Ƿ����
		$customerDao = new model_customer_customer_customer ();
		$customerId = $customerDao->findCus ( $row ['customerName'] );
		foreach ( $customerId as $key => $val ) {
			$customerId [$key] = $val;
		}
		$customerId = implode ( ",", $customerId [$key] );
        //�ж������Ƿ����
        $areaName = $this->areaName($row['area']);
        $regionDao = new model_system_region_region ();
		$areaId = $regionDao->region ( $areaName );
		if (! empty ( $areaId )) {
			foreach ( $areaId as $key => $val ) {
				$areaId [$key] = $val;
			}
			$areaId = implode ( ",", $areaId [$key] );
		}
		//�ж�ʡ��
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
		} else if($row['orderType'] == '���ۺ�ͬ' || $row['orderType'] == '�����ͬ' || $row['orderType'] == '���޺�ͬ' || $row['orderType'] == '�з���ͬ'){
			return "0";
		}else {
			return "1";
		}
	}
	/**
	 * �ϴ�EXCEL
	 */
	function c_upExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);

		$ExaDT = $_POST['import'];
		$objNameArr = array (
			0 => 'contractTypeName', //��ͬ����
			1 => 'contractNatureName', //��ͬ����
			2 => 'sign', //�Ƿ�ǩ��
			3 => 'signDate', //ǩԼ����
			4 => 'beginDate', //��ʼ����
			5 => 'endDate', //��������
			6 => 'contractCode', //������ͬ��
			7 => 'contractCountry', //��������
			8 => 'contractProvince', //����ʡ��
			9 => 'contractCity', //��������
			10 => 'areaName', //��������
			11 => 'areaPrincipal', //��������
			12 => 'customerType', //�ͻ�����
			13 => 'customerName', //�ͻ�����
			14 => 'contractName', //��ͬ����
			15 => 'state', //��ͬ״̬
			16 => 'contractMoney', // ��ͬ���
			17 => 'incomeMoney', // ���պϼ�
			18 => 'invoiceMoney', // ��Ʊ���
			19 => 'softMoney', // �����Ʊ���
			20 => 'hardMoney', // Ӳ����Ʊ���
			21 => 'repareMoney', // ά�޽��
			22 => 'serviceMoney', //  ������
			23 => 'invoiceTypeName', //  ��Ʊ����
			24 => 'prinvipalName', // ��ͬ������
			25 => 'remark' // ��ע
		);
		$this->c_addExecel($objNameArr, $ExaDT);
	}

	/**
	 * �ϴ�EXCEl������������
	 */
	function c_addExecel($objNameArr, $ExaDT) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //�ı������ķ�ʽ
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}
				echo "<pre>";
				print_R($objectArr);
				$err = array (); //ʧ�ܵ�����
				$suc = array (); //�ɹ�������
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
//				//���ֺ�ͬ��Ϣ����
//				foreach ($sucArray as $key => $val) {
//					try {
//						$this->service->start_d();
//						$order = array ();
//						$order[$key]['sign'] = $sucArray[$key]['sign']; //�Ƿ�ǩԼ
//						$order[$key]['contractCode'] = $sucArray[$key]['contractCode']; //������ͬ��
//						$order[$key]['orderTempCode'] = $objectArr[$key]['orderTempCode']; //��ʱ��ͬ��
//						$order[$key]['orderName'] = $objectArr[$key]['orderName']; //��ͬ����
//						$order[$key]['areaName'] = $this->areaName($sucArray[$key]['area']); //��������
//						$order[$key]['areaPrincipal'] = $this->areaMan($sucArray[$key]['area']); //��������
//						$order[$key]['areaPrincipalId'] = $this->user($order[$key]['areaPrincipal']); //��������Id
//						//�Զ�����ҵ�����
//						$contractCodeDao = new model_common_codeRule();
//						$deptDao = new model_deptuser_dept_dept();
//						$dept = $deptDao->getDeptByUserId($this->user($order[$key]['areaPrincipal']));
//						$order[$key]['objCode'] = $contractCodeDao->getObjCode("oa_sale_order_objCode", $dept['Code']);
//
//						$order[$key]['areaCode'] = $this->beArea($order[$key]['areaName'], $order[$key]['areaPrincipal']); //������루id��
//						$order[$key]['state'] = $this->state($sucArray[$key]['state']); //��ͬ״̬
//						$order[$key]['orderMoney'] = $sucArray[$key]['orderMoney']; //��ͬ���
//						$order[$key]['invoiceType'] = $this->datadict("invoice", $sucArray[$key]['invoiceType']); //��Ʊ����  invoice
//						$order[$key]['remark'] = $sucArray[$key]['remark']; //��ע
//						$order[$key]['ExaDT'] = $ExaDT['ExaDT']; //����ʱ��
//						$order[$key]['createTime'] = date('Y-m-d'); //����ʱ��
//						$order[$key]['createName'] = $_SESSION['USERNAME'];
//						$order[$key]['createId'] = $_SESSION['USER_ID'];
//
//						$order[$key]['orderNature'] = $this->datadict("order", $sucArray[$key]['orderNature']); //��ͬ���� Code
//						$order[$key]['orderNatureName'] = $sucArray[$key]['orderNature']; //��ͬ���� Name
//						$order[$key]['orderProvince'] = $sucArray[$key]['orderProvince']; //����ʡ��
//						$order[$key]['orderProvinceId'] = $this->province($sucArray[$key]['orderProvince']); //����ʡ��Id
//						$order[$key]['orderCity'] = $sucArray[$key]['orderCity']; //��������
//						$order[$key]['orderCityId'] = $this->city($sucArray[$key]['orderCity']); //��������Id
//						$order[$key]['customerType'] = $this->datadict("cusType", $sucArray[$key]['customerType']); //�ͻ�����
//						$order[$key]['customerName'] = $sucArray[$key]['customerName']; //�ͻ�����
//						$order[$key]['customerId'] = $this->cusId($sucArray[$key]['customerName']); //�ͻ�����cusId
//						$order[$key]['prinvipalName'] = $sucArray[$key]['prinvipalName']; //��ͬ������ �����ֺ�ͬ��ͬ��
//						$order[$key]['prinvipalId'] = $this->user($order[$key]['prinvipalName']); //��ͬ������Id
//						$order[$key]['ExaStatus'] = "���"; //��ͬ����״̬
//
//						$orderId = $this->addOrder("order", $order); //�����ͬ��Ϣ������ú�ͬId
//						$invoiceM = $sucArray[$key]['invoiceMoney']; //��Ʊ���
//						$incomeM = $sucArray[$key]['received']; //������
//						if (!empty ($orderId)) {
//							//��Ʊ
//							$orderInvoice[$key]['objId'] = $orderId; //��ͬID
//							$orderInvoice[$key]['objType'] = $this->isTemp("order", $sucArray[$key]['contractCode']); //��Ʊ�ĺ�ͬ����
//							$orderInvoice[$key]['objCode'] = $this->contractCode($orderInvoice[$key]['objType'], $sucArray[$key]['contractCode'], $sucArray[$key]['orderTempCode']); //������ͬ��
//							$orderInvoice[$key]['objName'] = $sucArray[$key]['orderName']; //��ͬ����
//							$orderInvoice[$key]['invoiceUnitName'] = $order[$key]['customerName']; //�ͻ�����
//							$orderInvoice[$key]['invoiceTime'] = date("Y-m-d"); //��Ʊʱ�䣬��ʱȡ�����ʱ�䴦��
//							$orderInvoice[$key]['invoiceType'] = $order[$key]['invoiceType']; //��Ʊ����
//							$orderInvoice[$key]['invoiceMoney'] = $sucArray[$key]['invoiceMoney']; //��Ʊ���
//							$orderInvoice[$key]['softMoney'] = $sucArray[$key]['softMoney']; //������
//							$orderInvoice[$key]['hardMoney'] = $sucArray[$key]['hardMoney']; //Ӳ�����
//							$orderInvoice[$key]['repairMoney'] = $sucArray[$key]['repairMoney']; //ά�޽��
//							$orderInvoice[$key]['serviceMoney'] = $sucArray[$key]['serviceMoney']; //������
//							$orderInvoice[$key]['salesman'] = $sucArray[$key]['prinvipalName']; //��ͬ������
//							$orderInvoice[$key]['salesmanId'] = $this->user($sucArray[$key]['prinvipalName']); //��ͬ������Id
//							//����
//							//���
//							$income[$key]['formType'] = "YFLX-DKD"; //�������
//							$income[$key]['incomeUnitName'] = $sucArray[$key]['customerName']; //�ͻ�����
//							$income[$key]['incomeDate'] = date("Y-m-d"); //��д������ڣ���ȡ��������
//							$income[$key]['incomeMoney'] = $sucArray[$key]['received']; //������
//							$income[$key]['allotAble'] = "0"; //
//							$income[$key]['incomeType'] = "DKFS1"; //��������
//							$income[$key]['sectionType'] = "DKLX-HK"; //
//							$income[$key]['status'] = "DKZT-YFP"; //
//							//���䵥
//							$allot[$key]['incomeId'] = $this->income($income); //���Id
//							$allot[$key]['objId'] = $orderInvoice[$key]['objId']; //��ͬId
//							$allot[$key]['objType'] = $this->isTemp("order", $sucArray[$key]['contractCode']); //����ĺ�ͬ����
//							$allot[$key]['money'] = $sucArray[$key]['received']; //���������
//							$allot[$key]['allotDate'] = date("Y-m-d"); //���������
//							//���浽����䵥
//							if (!empty ($incomeM)) {
//								$allotDao = new model_finance_income_incomeAllot();
//								foreach ($allot as $key => $val) {
//									$allot = $val;
//								}
//								$allotId = $allotDao->create($allot);
//							}
//
//							//���濪Ʊ
//							if (!empty ($invoiceM)) {
//								$invoiceDao = new model_finance_invoice_invoice();
//								foreach ($orderInvoice as $key => $val) {
//									$orderInvoice = $val;
//								}
//								if (empty ($orderInvoice['objId'])) {
//									throw new Exception("��ͬ��Ϣ����");
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
//						echo "$objectArr[$key]['orderName']-->��ͬ�������";
//						return false;
//					}
//
//				}
//				$arrinfo = array ();
//				//�жϺ�ͬ�����Ƿ�Ϊ��
//				//				foreach ( $suc as $k => $v ) {
//				//					if (empty ( $suc [$k] ['orderName'] )) {
//				//						unset ( $suc [$k] );
//				//					}
//				//				}
//				foreach ($suc as $k => $v) {
//					array_push($arrinfo, array (
//						"contractCode" => $suc[$k]['contractCode'],
//						"cusName" => $suc[$k]['customerName'],
//						"result" => "����ɹ�"
//					));
//				}
//
//				foreach ($errArray as $k => $v) {
//					if (empty ($errArray[$k]['orderTempCode']) && empty ($errArray[$k]['contractCode'])) {
//						array_push($arrinfo, array (
//							"contractCode" => $errArray[$k]['contractCode'],
//							"cusName" => $errArray[$k]['customerName'],
//							"result" => "����ʧ�ܣ���ͬ��Ϊ��"
//						));
//					} else {
//						$contractCode = $this->service->orderBe($errArray[$k]['orderTempCode'], $errArray[$k]['contractCode']);
//						if (!empty ($contractCode)) {
//							array_push($arrinfo, array (
//								"contractCode" => $errArray[$k]['contractCode'],
//								"cusName" => $errArray[$k]['customerName'],
//								"result" => "����ʧ�ܣ���ͬ���Ѵ���"
//							));
//						} else {
//							$areaName = $this->areaName($errArray[$k]['area']);
//							$regionDao = new model_system_region_region();
//							$areaId = $regionDao->region($areaName);
//							if (empty ($areaId)) {
//								array_push($arrinfo, array (
//									"contractCode" => $errArray[$k]['contractCode'],
//									"cusName" => $errArray[$k]['customerName'],
//									"result" => "����ʧ�ܣ��������򲻴���!"
//								));
//							} else {
//								$customerDao = new model_customer_customer_customer();
//								$customerId = $customerDao->findCus($errArray[$k]['customerName']);
//								if (empty ($customerId)) {
//									array_push($arrinfo, array (
//										"contractCode" => $errArray[$k]['contractCode'],
//										"cusName" => $errArray[$k]['customerName'],
//										"result" => "����ʧ�ܣ��ͻ���Ϣ������!"
//									));
//								} else {
//									//�ж�ʡ��
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
//											"result" => "����ʧ�ܣ�ʡ����Ϣ����"
//										));
//									} else {
//										array_push($arrinfo, array (
//											"contractCode" => $errArray[$k]['contractCode'],
//											"cusName" => $errArray[$k]['customerName'],
//											"result" => "����ʧ�ܣ���ͬ���ʹ���"
//										));
//									}
//								}
//							}
//						}
//					}
//				}
//
//				if ($arrinfo) {
//					echo util_excelUtil :: showResultOrder($arrinfo, "������", array (
//						"��ͬ��",
//						"�ͻ�����",
//						"���"
//					));
//				}
			} else {
				echo "�ļ������ڿ�ʶ������!";
			}
		} else {
			echo "�ϴ��ļ����Ͳ���EXCEL!";
		}

	}
	/**************************************��������˵�************************************************************************/

	/******************start��ͬ��Ϣ�б���**************************/
	/**
	 * ��ͬ����-��ͬ��Ϣ-��ͬ��Ϣ����
	 */
	function c_exportExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
//		if (! isset ( $this->service->this_limit ['��ͬ��Ϣ����'] )) {
//			showmsg ( 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա' );
//		}
		$stateArr = array ('0' => 'δ�ύ', '1' => '������', '2' => 'ִ����', '3' => '�ѹر�', '4' => '��ִ��','5' => '�Ѻϲ�', '6' => '�Ѳ��', '7' => '�Ѳ��' );
		$signinArr = array ('0' => 'δǩ��', '1' => '��ǩ��');
//
		$colIdStr = $_GET ['colId'];
		$colNameStr = $_GET ['colName'];
		$type = $_GET ['type'];
		$state = $_GET ['state'];
		$ExaStatus = $_GET ['ExaStatus'];
//		$beginDate = $_GET ['beginDate'];//��ʼʱ��
//		$endDate = $_GET ['endDate'];//��ֹʱ��
//		$ExaDT = $_GET ['ExaDT'];//����ʱ��
//		$areaNameArr = $_GET ['areaNameArr'];//��������
//		$orderCodeOrTempSearch = $_GET ['orderCodeOrTempSearch'];//��ͬ���
//		$prinvipalName = $_GET ['prinvipalName'];//��ͬ������
//		$customerName = $_GET ['customerName'];//�ͻ�����
//		$orderProvince = $_GET ['orderProvince'];//����ʡ��
//		$customerType = $_GET ['customerType'];//�ͻ�����
//		$orderNatureArr = $_GET ['orderNatureArr'];//��ͬ����
//		$searchConditionKey = $_GET['searchConditionKey'];//��ͨ������Key
//		$searchConditionVal = $_GET['searchConditionVal'];//��ͨ������Val
////		$searchCondition = "$searchConditionKey = $searchConditionVal";
		//��ͷId����
		$colIdArr = explode ( ',', $colIdStr );
		$colIdArr = array_filter ( $colIdArr );
		//��ͷName����
		$colNameArr = explode ( ',', $colNameStr );
		$colNameArr = array_filter ( $colNameArr );
		//��ͷ����
		$colArr = array_combine ( $colIdArr, $colNameArr );
		//��������
		$searchArr ['contractType'] = $type;
		if($state == null || $state == "" || $state == "undefined"){
            $searchArr ['states'] = "1,2,3,4,5,6,7";
		}else{
			$searchArr ['state'] = $state;
		}
		$searchArr ['ExaStatus'] = $ExaStatus;
//		$searchArr ['beginDate'] = $beginDate;//��ʼʱ��
//		$searchArr ['endDate'] = $endDate;//��ֹʱ��
//		$searchArr ['createTime'] = $ExaDT;//����ʱ��
//		$searchArr ['areaNameArr'] = $areaNameArr;//��������
//		$searchArr ['orderCodeOrTempSearch'] = $orderCodeOrTempSearch;//��ͬ���
//		$searchArr ['prinvipalName'] = $prinvipalName;//��ͬ������
//		$searchArr ['customerName'] = $customerName;//�ͻ�����
//		$searchArr ['orderProvince'] = $orderProvince;//����ʡ��
//		$searchArr ['customerType'] = $customerType;//�ͻ�����
//		$searchArr ['orderNatureArr'] = $orderNatureArr;//��ͬ����
//		$searchArr [$searchConditionKey] = $searchConditionVal;
//		$privlimit = isset ( $this->service->this_limit ['��������'] ) ? $this->service->this_limit ['��������'] : null;
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



		//������Ȩ������
		$limit = $this->initLimit();

		if($limit == true){
			$rows = $service->page_d ();

			//��ͬ���Ȩ��
			$comLimit = isset($this->service->this_limit['�ı��ͬ״̬']) ? $this->service->this_limit['�ı��ͬ״̬'] : null;
			if($comLimit){
				$rows = util_arrayUtil::setItemMainId('com',1,$rows);
			}

			//��ȫ��
			$rows = $this->sconfig->md5Rows ( $rows );
		}


////		$rows = $this->service->getInvoiceAndIncome_d ( $rows );
////        $rows = $this->service->getMoneyControl_d ( $rows );//����������
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
//		//���ϼ�
//		$this->service->searchArr = $searchArr;
//		$rowMoney = $this->service->listBySqlId ( 'select_orderinfo_sumMoney' );
//		$rowMoney[0]['tablename'] = "���ϼ�";
//		$rows[] = $rowMoney[0];
//		//ƥ�䵼����
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
