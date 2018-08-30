<?php


/**
 * 所有源单的接口
 * 包括销售合同，租赁合同，服务合同，研发合同，借试用，赠送
 */
class model_stock_withdraw_withcommon extends model_base {

	function __construct() {
		parent :: __construct();

			$this->sourceArr = array (//不同类型的源单在这里注册
		//销售
	"oa_contract_exchangeapply" => array (
				"baseModel" => "model_projectmanagent_exchange_exchange",
				"baseTable" => "oa_contract_exchangeapply",
				"equModel" => "model_projectmanagent_exchange_exchangeequ",
				"equTable" => "oa_contract_exchange_equ",
				"mainField" => "exchangeId",
			),
				//			//租赁
		//			"oa_sale_lease" => array(
		//				"baseModel"=>"model_contract_rental_rentalcontract",
		//				"equModel"=> "model_contract_rental_tentalcontractequ",
		//				"mainField"=>"orderId",
		//				"financeDatadict"=>"'KPRK-05','KPRK-06'"
		//			),
		//			//服务合同
		//			"oa_sale_service" => array(
		//				"baseModel"=>"model_engineering_serviceContract_serviceContract",
		//				"equModel"=> "model_engineering_serviceContract_serviceequ",
		//				"mainField"=>"orderId",
		//				"financeDatadict"=>"'KPRK-03','KPRK-04'"
		//			),
		//			//研发合同
		//			"oa_sale_rdproject" => array(
		//				"baseModel"=>"model_rdproject_yxrdproject_rdproject",
		//				"equModel"=> "model_rdproject_yxrdproject_rdprojectequ",
		//				"mainField"=>"orderId",
		//				"financeDatadict"=>"'KPRK-07','KPRK-08'"
		//			),
		//借试用
	"oa_borrow_borrow" => array (
				"baseModel" => "model_projectmanagent_borrow_borrow",
				"baseTable" => "oa_borrow_borrow",
				"equModel" => "model_projectmanagent_borrow_borrowequ",
				"equTable" => "oa_borrow_equ",
				"mainField" => "borrowId",
				"allocationDatadict" => "'DBDYDLXJY'",
				"productModel" => "model_projectmanagent_borrow_product"
			),
				//赠送
	"oa_present_present" => array (
				"baseModel" => "model_projectmanagent_present_present",
				"baseTable" => "oa_present_present",
				"equModel" => "model_projectmanagent_present_presentequ",
				"equTable" => "oa_present_equ",
				"mainField" => "presentId",
				"productModel" => "model_projectmanagent_present_product"
			),
				//退货
	"oa_contract_exchangeapply" => array (
				"baseModel" => "model_projectmanagent_exchange_exchange",
				"baseTable" => "oa_contract_exchangeapply",
				"equModel" => "model_projectmanagent_exchange_exchangeequ",
				"equTable" => "oa_contract_exchange_equ",
				"mainField" => "exchangeId",
				"productModel" => "model_projectmanagent_exchange_exchangeproduct"
			)
		);

		//使用表名作为源单类型的
		$this->relationTable = array (
			"oa_stock_ship_product" => array (
				"docType",
				"docId"
				), //发货单明细
	"oa_stock_ship" => array (
				"docType",
				"docId"
				), //发货单
	"oa_stock_outplan_product" => array (
				"docType",
				"docId"
				), //发货计划明细
	"oa_stock_outplan" => array (
				"docType",
				"docId"
				), //发货计划
	"oa_produce_protaskequ" => array (
				"relDocType",
				"relDocId"
				), //生产任务明细
	"oa_produce_protask" => array (
				"relDocType",
				"relDocId"
				), //生产任务
	"oa_purch_plan_basic" => array (
				"purchType",
				"sourceID"
				), //采购任务明细
	"oa_purch_objass" => array (
				"planAssType",
				"planAssId"
				), //采购总关联表
	"oa_purch_apply_equ" => array (
				"purchType",
				"sourceID"
				), //采购申请明细
	"oa_finance_carriedforward" => array (
				"saleType",
				"saleId"
				), //成本结转
	"oa_present_present" => array (
				"limits",
				"orderId"
				) //赠送


		);
		$this->relationTableFinance = array (
			"oa_finance_invoice" => array (
				"objType",
				"objId"
				), //发票
	"oa_finance_invoiceapply_relate" => array (
				"objType",
				"objId"
				), //发票申请
	"oa_finance_income_allot" => array (
				"objType",
				"objId"
				) //到款分配
		);

		//单独关联合同
		$this->singnRelation = array (
			"oa_stock_instock" => array (
				"contractType",
				"contractId",
				"contractObjCode"
				), //入库单
	"oa_stock_outstock" => array (
				"contractType",
				"contractId",
				"contractObjCode"
				), //出库单
	"oa_stock_allocation" => array (
				"contractType",
				"contractId",
				"contractObjCode"
				) //调拨单


		);

		//仓存调拨单
		$this->relationTableAllocation = array (
			"oa_stock_allocation" => array (
				"relDocType",
				"relDocId"
			)
		);

		parent :: __construct();

	}

	/**
	 * 获取源单主类dao
	 */
	function getSourceBaseDao($docType) {
		return $this->sourceArr[$docType]['baseModel'];
	}

	/**
	* 获取源单设备类dao
	*/
	function getSourceEquDao($docType) {
		return $this->sourceArr[$docType]['equModel'];
	}

	/**
	* 获取源单设备类dao
	*/
	function getSourceProDao($docType) {
		return $this->sourceArr[$docType]['productModel'];
	}

	/**
	 *获取源单设备清单
	 */
	function getEqus($id, $docType) {
		return $this->getProEqus($id, $docType);
	}

	/**
	 *获取源单某个产品设备清单
	 */
	function getProEqus($id, $docType, $productId = "") {
		$source = $this->sourceArr[$docType];
		$getEqus = empty ($source['getEqus']) ? "list_d" : $source['getEqus'];
		$equDaoName = $source['equModel'];
		if (empty ($equDaoName)) {
			throw new Exception("清单dao没有注册");
		}
		$equDao = new $equDaoName ();
		if (!method_exists($equDao, $getEqus)) {
			throw new Exception("源单清单没有注册该方法.");
		}
		$mainField = $source['mainField'];
		$equDao->searchArr = array (
			$mainField => $id,
			"isDel" => 0
		);
		if (!empty ($productId)) {
			$equDao->searchArr['productId'] = $productId;
		}
		$list = $equDao->list_d();
		return $list;
	}

	/**
	 * 批量产生业务编号
	 */
	function batchCreateObjCode($objType) {
		//如果是空先抛异常
		if (empty ($objType)) {
			throw new Exception("type is null");
		}
		try {
			$source = $this->sourceArr[$objType];
			$baseModelName = $source['baseModel'];
			$baseDao = new $baseModelName ();
			if (method_exists($baseDao, "batchCreateObjCode")) {
				$baseDao->batchCreateObjCode();
			} else {
				$this->defaultBatchCreateObjCode($baseDao);
			}
		} catch (Exception $e) {
			echo $e->getMessage();
			return 0;
		}
		return 1;
	}

	/**
	 * 根据id升序批量产生合同业务编号
	 */
	function defaultBatchCreateObjCode($baseDao) {
		try {
			//$baseDao->start_d();
			$baseDao->asc = false;
			$baseDao->searchArr = array (
				"isTemp" => 0
			);
			$list = $baseDao->list_d();
			$orderCodeDao = new model_common_codeRule();
			$curCodeArr = array ();

			foreach ($list as $key => $val) {
				$userId = $val['prinvipalId'];
				if (empty ($userId) || $userId == 'NULL') {
					throw new Exception("业务单据" . $val['id'] . "用户id为空.");
				}
				$deptDao = new model_deptuser_dept_dept();
				$dept = $deptDao->getDeptByUserId($userId);
				$createTime = $val['createTime'];
				if (!empty ($val['contractType'])) {
					switch ($val['contractType']) { //编号前缀
						case "HTLX-XSHT" :
							$type = "oa_sale_order_objCode";
							break; //销售合同
						case "HTLX-FWHT" :
							$type = "oa_sale_service_objCode";
							break; //服务合同
						case "HTLX-ZLHT" :
							$type = "oa_sale_lease_objCode";
							break; //租赁合同
						case "HTLX-YFHT" :
							$type = "oa_sale_rdproject_objCode";
							;
							break;
					}
				} else {
					$type = $baseDao->tbl_name . "_objCode";
				}
				$val['objCode'] = $orderCodeDao->getBatchCode($type, $dept['Code'], $curCodeArr[$type], $createTime);
				$curCodeArr[$type] = $val['objCode'];
				$baseDao->updateById($val);
			}

			foreach ($curCodeArr as $key => $val) {
				$updateSql = "update oa_billCode set codeValue='$val' where codeType='" . $key . "'";
				//echo $updateSql;
				$orderCodeDao->query($updateSql);
			}
			//处理业务类型存表名的模块
			foreach ($this->relationTable as $tablename => $valArr) {
				$sourceType = $valArr[0];
				$sourceId = $valArr[1];
				foreach ($this->sourceArr as $key => $val) {
					if ($tablename != $key) {
						$sql = "update $tablename left join $key on $tablename.$sourceId=$key.id set $tablename.rObjCode=$key.objCode " .
						"where $tablename.$sourceType='$key'";
						//echo $sql;
						$orderCodeDao->query($sql);
					}

				}
			}

			//处理财务的模块
			foreach ($this->relationTableFinance as $tablename => $valArr) {
				$sourceType = $valArr[0];
				$sourceId = $valArr[1];
				foreach ($this->sourceArr as $key => $val) {
					$souceFinance = $val['financeDatadict'];
					if (!empty ($souceFinance)) {
						$sql = "update $tablename left join $key on $tablename.$sourceId=$key.id set $tablename.rObjCode=$key.objCode " .
						"where $tablename.$sourceType in ($souceFinance)";
						$orderCodeDao->query($sql);
					}
				}
			}

			//处理单独关联合同的模块
			foreach ($this->singnRelation as $tablename => $valArr) {
				$sourceType = $valArr[0];
				$sourceId = $valArr[1];
				$sourceObjCode = $valArr[2];
				foreach ($this->sourceArr as $key => $val) {
					$sql = "update $tablename left join $key on $tablename.$sourceId=$key.id set $tablename.$sourceObjCode=$key.objCode " .
					"where $tablename.$sourceType='$key'";
					//echo $sql;
					$orderCodeDao->query($sql);
				}
			}

			//处理调拨单
			foreach ($this->relationTableAllocation as $tablename => $valArr) {
				$sourceType = $valArr[0];
				$sourceId = $valArr[1];
				foreach ($this->sourceArr as $key => $val) {
					$souceFinance = $val['allocationDatadict'];
					if (!empty ($souceFinance)) {
						$sql = "update $tablename left join $key on $tablename.$sourceId=$key.id set $tablename.rObjCode=$key.objCode " .
						"where $tablename.$sourceType in ($souceFinance)";
						$orderCodeDao->query($sql);
					}
				}
			}

			//$baseDao->commit_d();
		} catch (Excetption $e) {
			//$baseDao->rollBack();
			throw $e;
		}

	}
	/**************************************************下达信息 start************************************************/
	/**
	 * 下达发货计划后，更新合同下达信息
	 */
	function updateIssuedInfo($docInfo) {
		$docType = $docInfo['docType'];
		$baseModel = $this->sourceArr[$docType]['baseModel'];
		if (empty ($baseModel)) {
			throw new Exception("该源单dao没有注册");
		}
		$baseDao = new $baseModel ();
		foreach ($docInfo['items'] as $key => $val) {
			//换货时，下达收货通知单应更新退货明细 
			if($docType == "oa_contract_exchangeapply"){
				$this->sourceArr[$docType]['equTable'] = 'oa_contract_exchange_backequ';
			}
			$sql = " update " . $this->sourceArr[$docType]['equTable'] . " set issuedBackNum = issuedBackNum+" . $docInfo['items'][$key]['number'] . " where id=" . $docInfo['items'][$key]['contEquId'] . " and " . $this->sourceArr[$docType]['mainField'] . "=" . $docInfo['docId'];
			$baseDao->_db->query($sql);
		}
	}

	/**
	 * 更新收货下达状态
	 */
	function updateIssuedStatus($docInfo) {
		$docType = $docInfo['docType'];
		$baseModel = $this->sourceArr[$docType]['baseModel'];
		if (empty ($baseModel)) {
			throw new Exception("该源单dao没有注册");
		}
		$baseDao = new $baseModel ();
		$baseDao->updateBackStatus_d($docInfo['docId']);
		return 1;
	}
	/**************************************************下达信息 end************************************************/

	/**************************************************出库信息 start************************************************/
	/**
	 * 更新合同发货信息 ---- 数量
	 */
	function updateOutStockInfo($docInfo, $rows) {
		$docType = $docInfo['docType'];
		$baseModel = $this->sourceArr[$docType]['baseModel'];
		if (empty ($baseModel)) {
			throw new Exception("该源单dao没有注册");
		}
		$baseDao = new $baseModel ();
		//更新合同已执行数量
		$contSql = " update " . $this->sourceArr[$docType]['equTable'] . " set executedNum = executedNum+" . $rows['outNum'] . " where " . $this->sourceArr[$docType]['mainField'] . "= " . $docInfo['docId'] . " and id= " . $rows['contEquId'];
		$baseDao->_db->query($contSql);
	}

	/**
	 * 更新合同发货状态
	 */
	function updateOutStockStatus($docInfo) {
		$docType = $docInfo['docType'];
		$baseModel = $this->sourceArr[$docType]['baseModel'];
		if (empty ($baseModel)) {
			throw new Exception("该源单dao没有注册");
		}
		$baseDao = new $baseModel ();
		$baseDao->updateOutStatus_d($docInfo['docId']);
		return 1;
	}

	/**
	 * 蓝字出库审核处理方法（独立）
	 */
	function updateAsOut($rows) {
		$type = $rows['contractType'];
		$baseDao = $this->getSourceBaseDao($type);
		if (empty ($baseDao)) {
			throw new Exception("该源单dao没有注册");
		}
		if( $type=='oa_contract_exchangeapply' ){
			$baseDao->updateAsOut();
		}else{
			$sql = "update " . $this->sourceArr[$type]['equTable'] . " set executedNum = executedNum + " . $rows['outNum'] .
			" where " . $this->sourceArr[$type]['mainField'] . " = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
			$dao = new $baseDao ();
			$dao->_db->query($sql);
			$dao->updateOutStatus_d($rows['relDocId']);
		}
	}

	/**
	 * 蓝字出库反审核处理方法（独立）
	 */
	function updateAsAutiAudit($rows) {
		$type = $rows['contractType'];
		$rows['outNum'] = $rows['outNum'] * (-1);
		$sql = "update " . $this->sourceArr[$type]['equTable'] . " set executedNum = executedNum + " . $rows['outNum'] .
		" where " . $this->sourceArr[$type]['mainField'] . " = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
		$baseDao = $this->getSourceBaseDao($type);
		$dao = new $baseDao ();
		if (empty ($baseDao)) {
			throw new Exception("该源单dao没有注册");
		}
		$dao->_db->query($sql);
		$dao->updateOutStatus_d($rows['relDocId']);
	}

	/**
	 * 红字出库审核处理方法
	 */
	function updateAsRedOut($rows) {
		$type = $rows['contractType'];
		$backNum = $rows['outNum'];
		$sql = "update " . $this->sourceArr[$type]['equTable'] . " set backNum = backNum + " . $backNum . " where " . $this->sourceArr[$type]['mainField'] . " = " .
		$rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
		$baseDao = $this->getSourceBaseDao($type);
		$dao = new $baseDao ();
		if (empty ($baseDao)) {
			throw new Exception("该源单dao没有注册");
		}
		$dao->_db->query($sql);
		$dao->updateOutStatus_d($rows['relDocId']);
	}
	/**
	 * 红字出库反审处理方法
	 */
	function updateAsRedAutiAudit($rows) {
		$type = $rows['contractType'];
		$backNum = $rows['outNum'] * (-1);
		$sql = "update " . $this->sourceArr[$type]['equTable'] . " set backNum = backNum + " . $backNum . " where " . $this->sourceArr[$type]['mainField'] . " = " .
		$rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
		$baseDao = $this->getSourceBaseDao($type);
		if (empty ($baseDao)) {
			throw new Exception("该源单dao没有注册");
		}
		$dao = new $baseDao ();
		$dao->_db->query($sql);
		$dao->updateOutStatus_d($rows['relDocId']);
	}

	/**
	 * 根据清单信息获取未发货信息
	 */
	function getNotExeNum_d($equInfo) {
		$docType = $equInfo['docType'];
		$equModel = $this->sourceArr[$docType]['equModel'];
		if (empty ($equModel)) {
			throw new Exception("该源单dao没有注册");
		}
		$equDao = new $equModel ();
		$equObj = $equDao->get_d($equInfo['id']);
		//合同未发货数量   合同数量-已执行数量+退库数量
		$remainNum = $equObj['number'] * 1 - $equObj['executedNum'] * 1 + $equObj['backNum'] * 1;
		return $remainNum;
	}

	/**************************************************出库信息 end************************************************/
	function getEquInfo($id, $type) {
		$equModel = $this->sourceArr[$type]['equModel'];
		$dao = new $equModel ();
		return $dao->get_d($id);
	}
	/*************************************************/
	/**
	 * 旧数据更新至新合同
	 */
	function updateOldcontract_d($objType) {
		switch ($objType) {
			case "order" :
				//销售合同
				$orderDao = new model_projectmanagent_order_order();
				$updateArr = $orderDao->getAllOrder_d();

				$contractDao = new model_contract_contract_contract();
				$msg = $contractDao->updateAdd($updateArr, "HTLX-XSHT");
				break;
			case "service" :
				//服务合同
				$serviceDao = new model_engineering_serviceContract_serviceContract();
				$updateArr = $serviceDao->getAllOrder_d();

				$contractDao = new model_contract_contract_contract();
				$msg = $contractDao->updateAdd($updateArr, "HTLX-FWHT");
				break;
			case "lease" :
				//租赁合同
				$leaseDao = new model_contract_rental_rentalcontract();
				$updateArr = $leaseDao->getAllOrder_d();

				$contractDao = new model_contract_contract_contract();
				$msg = $contractDao->updateAdd($updateArr, "HTLX-ZLHT");
				break;
			case "rdproject" :
				//研发合同
				$rdprojectDao = new model_rdproject_yxrdproject_rdproject();
				$updateArr = $rdprojectDao->getAllOrder_d();

				$contractDao = new model_contract_contract_contract();
				$msg = $contractDao->updateAdd($updateArr, "HTLX-YFHT");
				break;
			case "invoice" :
				//应收部分
				$msg = $this->updateInvoice_d();
				break;
			case "income" :
				//应收部分
				$msg = $this->updateIncome_d();
				break;
			case "esmproject" :
				//工程部分
				$msg = $this->updateESM_d();
				break;
			case "wftask" :
				//工作流
				$msg = $this->updateWftask_d();
				break;
			case "stock" :
				//仓存部分
				$msg = $this->updateStock_d();
				break;
			case "outplan" :
				//发货部分
				$msg = $this->updateOutplan_d();
				break;
			case "ship" :
				//发货单部分
				$msg = $this->updateOutProduct_d();
				break;
			case "purchase" :
				//发货部分
				$msg = $this->updatePurchase_d();
				break;
			case "borrow" :
				//借试用部分
				$borrowDao = new model_projectmanagent_borrow_borrow();
				$msg = $borrowDao->updateBorrow_d();
				break;
			case "present" :
				//借试用部分
				$borrowDao = new model_projectmanagent_present_present();
				$msg = $borrowDao->updatePresent_d();
				break;
		}

		if ($msg) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * 更新锁定信息
	 */
	function updateLock_d() {
		try {
			$this->start_d();
			$plus = ' where l.objType in("oa_sale_order","oa_sale_lease",' .
			'"oa_sale_service","oa_sale_rdproject")';
			//更新合同id
			$sql = "update oa_stock_lock l inner join oa_contract_initialize i  " .
			"on l.objId=i. oldContractId and l.objType=i.oldTableName set " .
			"l.objId=i.contractId" . $plus;
			$this->query($sql);
			//更新设备id
			$sql = 'update oa_stock_lock l inner join oa_contract_initialize_from  ' .
			'i  on l.objEquId=i.oldfromId and l.objType="oa_sale_order" and i.fromType="oa_sale_order_equ" ' .
			' set l.objEquId=i.fromId' . $plus;
			$this->query($sql);

			$sql = 'update oa_stock_lock l inner join oa_contract_initialize_from  ' .
			'i  on l.objEquId=i.oldfromId and l.objType="oa_sale_service" and i.fromType="oa_service_equ"' .
			' set l.objEquId=i.fromId' . $plus;
			$this->query($sql);

			$sql = 'update oa_stock_lock l inner join oa_contract_initialize_from  ' .
			'i  on l.objEquId=i.oldfromId and l.objType="oa_sale_lease" and  i.fromType="oa_lease_equ"' .
			' set l.objEquId=i.fromId' . $plus;
			$this->query($sql);

			$sql = 'update oa_stock_lock l inner join oa_contract_initialize_from  ' .
			'i  on l.objEquId=i.oldfromId and l.objType="oa_sale_rdproject" and  i.fromType="oa_rdproject_equ"' .
			' set l.objEquId=i.fromId' . $plus;
			$this->query($sql);

			//更新关联类型
			$sql = 'update oa_stock_lock l set objType="oa_contract_contract"' . $plus;
			$this->query($sql);
			//附件
			$sql = "update oa_uploadfile_manage l left join oa_contract_initialize i  " .
			"on l.serviceId=i.oldContractId and (l.serviceType=i.oldTableName" .
			" or l.serviceType=CONCAT(i.oldTableName,'2')) set " .
			"l.serviceId=i.contractId where l.serviceType in('oa_sale_order','oa_sale_order2'" .
			",'oa_sale_lease','oa_sale_lease2','oa_sale_service'," .
			"'oa_sale_service2','oa_sale_rdproject','oa_sale_rdproject2')";
			$this->query($sql);
			$sql = "UPDATE oa_uploadfile_manage SET serviceType='oa_contract_contract'" .
			" where serviceType IN ('oa_sale_order','oa_sale_lease'," .
			"'oa_sale_service','oa_sale_rdproject')";
			$this->query($sql);
			$sql = "UPDATE oa_uploadfile_manage SET serviceType='oa_contract_contract2'" .
			" where serviceType IN ('oa_sale_order2','oa_sale_lease2'," .
			"'oa_sale_service2','oa_sale_rdproject2')";
			$this->query($sql);
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return $e;
		}
	}
	/**
	 * 更新合同变更
	 */
	function updateChange_d($contractType) {
		try {
			$this->start_d();
			$arr = array (
				"oa_sale_order" => array (
					"oa_sale_order_changlog",
					"oa_sale_order_changedetail"
				),
				"oa_sale_rdproject" => array (
					"oa_sale_rdproject_changlog",
					"oa_sale_rdproject_changedetail"
				),
				"oa_sale_service" => array (
					"oa_sale_service_changlog",
					"oa_sale_service_changedetail"
				),
				"oa_sale_lease" => array (
					"oa_sale_lease_changlog",
					"oa_sale_lease_changedetail"
				)
			);
			//oa_sale_order oa_sale_service oa_sale_service oa_sale_lease
			if($contractType=="oa_sale_order"){
				$sql = "delete from oa_contract_changedetail";
				$this->query($sql);
				$sql = "delete from oa_contract_changlog";
				$this->query($sql);
			}
			//foreach ($arr as $key => $val) {
				$val=$arr[$contractType];
				$key=$contractType;
				$sql = "select * from " . $val[0];
				$arrM = $this->findSql($sql);
				$mainArr = array ();
				$this->tbl_name = "oa_contract_changlog";
				foreach ($arrM as $k => $v) {
					$arrM[$k]['objType'] = $key;
					unset ($arrM[$k]['id']);
					$arrM[$k]['remark']=$v['id'];//用remark存起来
				}
				$this->createBatch($arrM);

				$arrM = $this->findSql("select * from oa_contract_changlog");
				foreach ($arrM as $k => $v) {
					$mainArr[$v['remark']] = $v['id'];
				}

				$sql = "select * from " . $val[1];
				$arr1 = $this->findSql($sql);
				$this->tbl_name = "oa_contract_changedetail";
				$iArr=array();
				$i=0;
				foreach ($arr1 as $k => $v) {
					$i++;
					if($i==1000){
						$this->createBatch($iArr);
						$i=0;
						$iArr=array();
					}
					$arr1[$k]['parentType'] = $key;

					if (strpos($v['detailType'], "equ") > 0) {
						//$v['parentType']="contractequ";
						$arr1[$k]['parentType'] = $key ;
						$arr1[$k]['detailType'] = "equs";
					} else {
						$arr1[$k]['detailType'] = "contract";
					}
					$arr1[$k]['parentId'] = $mainArr[$v['parentId']];
					unset ($arr1[$k]['id']);
					$iArr[]=$arr1[$k];
				}
				//echo $val[1].count($arr1);
				if($i<1000){
					$this->createBatch($iArr);
				}
			//}
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return $e->getMessage();
		}
	}

	/**
	 * 更新变更关联
	 */
	function updateChangeRelate(){
		try {
			$this->start_d();
			$arr = array (
				"oa_sale_order" => array (
					"oa_sale_order_changlog",
					"oa_sale_order_changedetail"
				),
				"oa_sale_rdproject" => array (
					"oa_sale_rdproject_changlog",
					"oa_sale_rdproject_changedetail"
				),
				"oa_sale_service" => array (
					"oa_sale_service_changlog",
					"oa_sale_service_changedetail"
				),
				"oa_sale_lease" => array (
					"oa_sale_lease_changlog",
					"oa_sale_lease_changedetail"
				)
			);
			$sql = "update oa_contract_changlog l inner join oa_contract_initialize i  " .
			"on l.objId=i.oldContractId and l.objType=i.oldTableName set " .
			"l.objId=i.contractId";
			$this->query($sql);
			$sql = "update oa_contract_changlog l inner join oa_contract_initialize i  " .
			"on l.tempId=i.oldContractId and l.objType=i.oldTableName set " .
			"l.tempId=i.contractId";
			$this->query($sql);
			$sql = "update oa_contract_changlog set objType='contract'";
			$this->query($sql);
			foreach ($arr as $key => $val) {
				//更新设备id
				$sql = "update oa_contract_changedetail l inner join oa_contract_initialize  " .
				"i  on l.objId=i.oldContractId and   l.parentType in('$key','".$key."_equ')  " .
				" set l.objId=i.contractId";
				$this->query($sql);
				$sql = "update oa_contract_changedetail l inner join oa_contract_initialize_from  " .
				"i  on l.detailId=i.oldfromId and  l.parentType in('$key','".$key."_equ') " .
				" set l.detailId=i.fromId";
				$this->query($sql);
			}

			$sql = "update oa_contract_changedetail set parentType='contract' where" .
			" parentType in('oa_sale_order','oa_sale_rdproject','oa_sale_service','oa_sale_lease')";
			$this->query($sql);
//			$sql = "update oa_contract_changedetail set parentType='contractequ' where" .
//			" parentType in('oa_sale_order_equ','oa_sale_rdproject_equ','oa_sale_service_equ','oa_sale_lease_equ')";
//			$this->query($sql);

			//更新originalId
			$sql = "update oa_contract_contract c inner join oa_contract_initialize i on c.originalId = i.oldContractId " .
			" and c.contractType=i.contractType set c.originalId = i.contractId";
			$this->query($sql);
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return $e->getMessage();
		}
	}

	/**
	 * 更新签收信息
	 */
	function updateSignin_d() {
		set_time_limit(0);
		try {
			$this->start_d();
			$arr = array (
				"oa_sale_order" => array (
					"oa_sale_order_signin",
					"oa_sale_order_signininfo"
				),
				"oa_sale_rdproject" => array (
					"oa_sale_rdproject_signin",
					"oa_sale_rdproject_signininfo"
				),
				"oa_sale_service" => array (
					"oa_sale_service_signin",
					"oa_sale_service_signininfo"
				),
				"oa_sale_lease" => array (
					"oa_sale_lease_signin",
					"oa_sale_lease_signininfo"
				)
			);
			//oa_sale_order oa_sale_service oa_sale_service oa_sale_lease
			$sql = "delete from oa_contract_signininfo";
			$this->query($sql);
			$sql = "delete from oa_contract_signin";
			$this->query($sql);
			foreach ($arr as $key => $val) {
				$sql = "select * from " . $val[0];
				$arrM = $this->findSql($sql);
				$mainArr = array ();
				$this->tbl_name = "oa_contract_signin";
				foreach ($arrM as $k => $v) {
					$arrM[$k]['objType'] = $key;
					unset ($arrM[$k]['id']);
					$arrM[$k]['remark']=$v['id'];//用remark存起来
				}
				$this->createBatch($arrM);

				$arrM = $this->findSql("select * from oa_contract_signin");
				foreach ($arrM as $k => $v) {
					$mainArr[$v['remark']] = $v['id'];
				}

				$sql = "select * from " . $val[1];
				$arr1 = $this->findSql($sql);
				$this->tbl_name = "oa_contract_signininfo";
				$iArr=array();
				$i=0;
				foreach ($arr1 as $k => $v) {
					$i++;
					if($i==4000){
						$this->createBatch($iArr);
						$i=0;
						$iArr=array();
					}
//					$arr1[$k]['parentType'] = $key;
//
//					if (strpos($v['detailType'], "equ") > 0) {
//						//$v['parentType']="contractequ";
//						$arr1[$k]['parentType'] = $key . "_equ";
//						//$arr1[$k]['detailType'] = "equs";
//					} else {
//						//$arr1[$k]['detailType'] = "contract";
//					}
					$arr1[$k]['detailType'] =$key;
					$arr1[$k]['parentId'] = $mainArr[$v['parentId']];
					unset ($arr1[$k]['id']);
					$iArr[]=$arr1[$k];
				}
				//echo $val[1].count($arr1);
				if($i<4000){
					$this->createBatch($iArr);
				}
			}

			$sql = "update oa_contract_signin l inner join oa_contract_initialize i  " .
			"on l.objId=i.oldContractId and l.objType=i.oldTableName set " .
			"l.objId=i.contractId";
			$this->query($sql);
			$sql = "update oa_contract_signin l inner join oa_contract_initialize i  " .
			"on l.tempId=i.oldContractId and l.objType=i.oldTableName set " .
			"l.tempId=i.contractId";
			$this->query($sql);
			$sql = "update oa_contract_signin set objType='contract'";
			$this->query($sql);
			foreach ($arr as $key => $val) {
				//更新设备id
				$sql = "update oa_contract_signininfo l inner join oa_contract_initialize  " .
				"i  on l.objId=i.oldContractId  " .
				" set l.objId=i.contractId";
				$this->query($sql);
				$sql = "update oa_contract_signininfo l inner join oa_contract_initialize_from  " .
				"i  on l.detailId=i.oldfromId  " .
				" set l.detailId=i.fromId";
				$this->query($sql);
			}
			$sql="update oa_contract_signininfo set detailType='contractSi'";
			$this->query($sql);
//			$sql = "update oa_contract_signininfo set parentType='contract' where" .
//			" parentType in('oa_sale_order','oa_sale_rdproject','oa_sale_service','oa_sale_lease')";
//			$this->query($sql);
//			$sql = "update oa_contract_signininfo set parentType='contractequ' where" .
//			" parentType in('oa_sale_order_equ','oa_sale_rdproject_equ','oa_sale_service_equ','oa_sale_lease_equ')";
//			$this->query($sql);

			//更新originalId
//			$sql = "update oa_contract_contract c inner join oa_contract_initialize i on c.originalId = i.oldContractId " .
//			" and c.contractType=i.contractType set c.originalId = i.contractId";
//			$this->query($sql);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return $e->getMessage();
		}
	}

	/**
	 * 更新开票部分
	 */
	function updateInvoice_d() {
		try {
			$this->start_d();
			//开票记录
			/* 销售合同更新 */
			$sql = "update oa_finance_invoice c inner join oa_contract_initialize i on c.objId = i.oldContractId set c.objId = i.contractId,c.rObjCode = i.oldObjCode,c.objType = 'KPRK-12',c.objCode = i.contractCode where i.oldContractType = 'HTLX-XSHT' and c.objType in ('KPRK-01','KPRK-02')";
			$this->query($sql);
			$sql = "update oa_finance_invoiceapply c inner join oa_contract_initialize i on c.objId = i.oldContractId set c.objId = i.contractId,c.rObjCode = i.oldObjCode,c.objType = 'KPRK-12',c.objCode = i.contractCode where i.oldContractType = 'HTLX-XSHT' and c.objType in ('KPRK-01','KPRK-02')";
			$this->query($sql);

			/* 服务合同更新 */
			$sql = "update oa_finance_invoice c inner join oa_contract_initialize i on c.objId = i.oldContractId set c.objId = i.contractId,c.rObjCode = i.oldObjCode,c.objType = 'KPRK-12',c.objCode = i.contractCode where i.oldContractType = 'HTLX-FWHT' and c.objType in ('KPRK-03','KPRK-04')";
			$this->query($sql);
			$sql = "update oa_finance_invoiceapply c inner join oa_contract_initialize i on c.objId = i.oldContractId set c.objId = i.contractId,c.rObjCode = i.oldObjCode,c.objType = 'KPRK-12',c.objCode = i.contractCode where i.oldContractType = 'HTLX-FWHT' and c.objType in ('KPRK-03','KPRK-04')";
			$this->query($sql);

			/* 租赁合同更新 */
			$sql = "update oa_finance_invoice c inner join oa_contract_initialize i on c.objId = i.oldContractId set c.objId = i.contractId,c.rObjCode = i.oldObjCode,c.objType = 'KPRK-12',c.objCode = i.contractCode where i.oldContractType = 'HTLX-ZLHT' and c.objType in ('KPRK-05','KPRK-06')";
			$this->query($sql);
			$sql = "update oa_finance_invoiceapply c inner join oa_contract_initialize i on c.objId = i.oldContractId set c.objId = i.contractId,c.rObjCode = i.oldObjCode,c.objType = 'KPRK-12',c.objCode = i.contractCode where i.oldContractType = 'HTLX-ZLHT' and c.objType in ('KPRK-05','KPRK-06')";
			$this->query($sql);

			/* 研发合同更新 */
			$sql = "update oa_finance_invoice c inner join oa_contract_initialize i on c.objId = i.oldContractId set c.objId = i.contractId,c.rObjCode = i.oldObjCode,c.objType = 'KPRK-12',c.objCode = i.contractCode where i.oldContractType = 'HTLX-YFHT' and c.objType in ('KPRK-07','KPRK-08')";
			$this->query($sql);
			$sql = "update oa_finance_invoiceapply c inner join oa_contract_initialize i on c.objId = i.oldContractId set c.objId = i.contractId,c.rObjCode = i.oldObjCode,c.objType = 'KPRK-12',c.objCode = i.contractCode where i.oldContractType = 'HTLX-YFHT' and c.objType in ('KPRK-07','KPRK-08')";
			$this->query($sql);

			/* 更新合同开票金额*/
			$sql = "update oa_contract_contract c inner join
								(select objId,sum(if(isRed = 0,invoiceMoney,-invoiceMoney)) as invoiceMoney,sum(if(isRed = 0,softMoney,-softMoney)) as softMoney,sum(if(isRed = 0,hardMoney,-hardMoney)) as hardMoney,
									sum(if(isRed = 0,repairMoney,-repairMoney)) as repairMoney,sum(if(isRed = 0,serviceMoney,-serviceMoney)) as serviceMoney from oa_finance_invoice where objId <> 0 and objType = 'KPRK-12' group by objId
								) i
							on  c.id = i.objId
							set
								c.invoiceMoney = i.invoiceMoney,
								c.softMoney = i.softMoney,
								c.hardMoney = i.hardMoney,
								c.repairMoney = i.repairMoney,
								c.serviceMoney = i.serviceMoney
							";
			$this->query($sql);

			/* 更新合同缓存字段到开票记录 */
			$sql = "update oa_finance_invoice c inner join oa_contract_contract con on c.objId = con.id set c.areaId = con.areaCode ,c.areaName = con.areaName ,c.managerId  = con.areaPrincipalId,c.managerName = con.areaPrincipal,
									c.invoiceUnitProvince = con.contractProvince,c.invoiceUnitType = con.customerType
								where c.objId <> 0 and c.objType = 'KPRK-12' and c.areaName is null
							";
			$this->query($sql);

			/* 更新客户信息到开票缓存字段*/
			$sql = "update oa_finance_invoice c inner join customer con on c.invoiceUnitId = con.id set c.areaId = con.AreaId ,c.AreaName = con.areaName ,c.managerId  = con.AreaLeaderId,c.managerName = con.AreaLeader,
									c.invoiceUnitProvince = con.Prov,c.invoiceUnitType = con.TypeOne
								where c.invoiceUnitId <> 0 and (c.areaName is null or c.areaName = '' or c.managerId is null or c.managerId = '' or c.invoiceUnitType is null or c.invoiceUnitType = '')
							";
			$this->query($sql);

			/* 更新开票记录业务员区域 */
			$sql = "update oa_finance_invoice c inner join ( select u.USER_ID,a.Name from user u inner join area a on u.AREA = a.id ) a on c.salesmanId = a.USER_ID set c.salemanArea = a.Name
								where c.salemanArea is null
							";
			$this->query($sql);

			/* 更新开票申请金额*/
			$sql = "update oa_contract_contract c inner join
									(select objId,sum(invoiceMoney) invoiceMoney
										 from oa_finance_invoiceapply where objId <> 0 and objType = 'KPRK-12' group by objId
									) i
								on  c.id = i.objId
								set
									c.invoiceApplyMoney = i.invoiceMoney
							";
			$this->query($sql);

			/*更新合同缓存字段到开票申请 */
			$sql = "update oa_finance_invoiceapply c inner join oa_contract_contract con on c.objId = con.id set c.areaId = con.areaCode ,c.areaName = con.areaName ,c.managerId  = con.areaPrincipalId,c.managerName = con.areaPrincipal,
									c.customerProvince = con.contractProvince,c.customerType = con.customerType
								where c.objId <> 0 and c.objType = 'KPRK-12' and c.areaName is null
							";
			$this->query($sql);

			/*更新客户信息到开票缓存字段*/
			$sql = "update oa_finance_invoiceapply c inner join customer con on c.customerId = con.id set c.areaId = con.AreaId ,c.AreaName = con.areaName ,c.managerId  = con.AreaLeaderId,c.managerName = con.AreaLeader,
									c.customerProvince = con.Prov,c.customerType = con.TypeOne
								where c.customerId <> 0 and (c.areaName is null or c.areaName = '' or c.managerId is null or c.managerId = '' or c.customerType is null or c.customerType = '')
							";
			$this->query($sql);

			/*  更新开票申请业务员区域 */
			$sql = "update oa_finance_invoiceapply c inner join ( select u.USER_ID,a.Name from user u inner join area a on u.AREA = a.id ) a on c.createId = a.USER_ID set c.salemanArea = a.Name
								where c.salemanArea is null
							";
			$this->query($sql);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			echo $e;
			$this->rollBack();
			return $e;
		}
	}

	/**
	 * 更新到款部分
	 */
	function updateIncome_d() {
		try {
			$this->start_d();
			//开票记录
			/* 销售合同更新 */
			$sql = "update oa_finance_income_allot c inner join oa_contract_initialize i on c.objId = i.oldContractId set c.objId = i.contractId,c.rObjCode = i.oldObjCode,c.objType = 'KPRK-12',c.objCode = i.contractCode where i.oldContractType = 'HTLX-XSHT' and c.objType in ('KPRK-01','KPRK-02')";
			$this->query($sql);

			/* 服务合同更新 */
			$sql = "update oa_finance_income_allot c inner join oa_contract_initialize i on c.objId = i.oldContractId set c.objId = i.contractId,c.rObjCode = i.oldObjCode,c.objType = 'KPRK-12',c.objCode = i.contractCode where i.oldContractType = 'HTLX-FWHT' and c.objType in ('KPRK-03','KPRK-04')";
			$this->query($sql);

			/* 租赁合同更新 */
			$sql = "update oa_finance_income_allot c inner join oa_contract_initialize i on c.objId = i.oldContractId set c.objId = i.contractId,c.rObjCode = i.oldObjCode,c.objType = 'KPRK-12',c.objCode = i.contractCode where i.oldContractType = 'HTLX-ZLHT' and c.objType in ('KPRK-05','KPRK-06')";
			$this->query($sql);

			/* 研发合同更新 */
			$sql = "update oa_finance_income_allot c inner join oa_contract_initialize i on c.objId = i.oldContractId set c.objId = i.contractId,c.rObjCode = i.oldObjCode,c.objType = 'KPRK-12',c.objCode = i.contractCode where i.oldContractType = 'HTLX-YFHT' and c.objType in ('KPRK-07','KPRK-08')";
			$this->query($sql);

			/* 更新到款金额到合同 */
			$sql = "update oa_contract_contract c inner join
									(select a.objId,sum(if(c.formType ='YFLX-TKD' ,-a.money,a.money)) as incomeMoney
										 from oa_finance_income c inner join oa_finance_income_allot a on c.id = a.incomeId where a.objId <> 0 and a.objType = 'KPRK-12' group by a.objId
									) i
								on  c.id = i.objId
								set
									c.incomeMoney = i.incomeMoney
							";
			$this->query($sql);

			/* 更新到款缓存字段 */
			$sql = "update oa_finance_income c inner join customer cus on c.incomeUnitId = cus.id set c.incomeUnitType = cus.TypeOne ,c.province = cus.Prov,c.managerId = cus.AreaLeaderId,c.managerName = cus.AreaLeader,c.areaId = cus.AreaId , c.areaName = cus.AreaName
							";
			$this->query($sql);
			$sql = "update oa_finance_income c set c.contractUnitId = c.incomeUnitId , c.contractUnitName = c.incomeUnitName where c.contractUnitId is null
							";
			$this->query($sql);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return $e;
		}
	}

	/**
	 * 更新工程部分
	 */
	function updateESM_d() {
		try {
			$this->start_d();
			/* 工程服务部分更新 */
			//服务合同
			$sql = "update oa_esm_project c inner join oa_contract_initialize i on c.contractId = i.oldContractId set c.contractType = 'GCXMYD-01',c.contractId = i.contractId where
					i.oldTableName = 'oa_sale_service' and c.contractType = 'GCXMYD-02'";
			$this->query($sql);

			//销售合同
			$sql = "update oa_esm_project c inner join oa_contract_initialize i on c.contractId = i.oldContractId set c.contractType = 'GCXMYD-01',c.contractId = i.contractId where
					i.oldTableName = 'oa_sale_order' and c.contractType = 'GCXMYD-03'";
			$this->query($sql);

			//租赁合同
			$sql = "update oa_esm_project c inner join oa_contract_initialize i on c.contractId = i.oldContractId set c.contractType = 'GCXMYD-01',c.contractId = i.contractId where
					i.oldTableName = 'oa_sale_lease' and c.contractType = 'GCXMYD-05'";

			//研发合同
			$sql = "update oa_esm_project c inner join oa_contract_initialize i on c.contractId = i.oldContractId set c.contractType = 'GCXMYD-01',c.contractId = i.contractId where
					i.oldTableName = 'oa_sale_rdproject' and c.contractType = 'GCXMYD-06'";
			$this->query($sql);

            //合同类型更新
            $sql = "update oa_esm_project c inner join oa_system_datadict d on c.contractType = d.dataCode set c.contractTypeName = d.dataName where d.parentCode = 'GCXMYD'";
            $this->query($sql);

			//更新合同金额,开票金额
			$sql = "update oa_esm_project c inner join oa_contract_contract i on c.contractId = i.id set c.contractMoney = i.contractMoney,c.invoiceMoney = i.invoiceMoney where
					c.contractType = 'GCXMYD-01'";
			$this->query($sql);

			//更新项目章程关联的合同id
			$sql = "update oa_esm_charter c inner join oa_esm_project i on c.id = i.charterId set c.contractId = i.contractId ";
			$this->query($sql);

			/* 合同工作比例、项目进度更新 */
			$sql = "update
						oa_contract_contract c
						inner join
							(select
							contractId,
							contractType,
							sum(workRate) as workRate,
							sum(
								round(
									(
										workRate * projectProcess / 100
									),
									2
								)
							) as projectProcess
						from
							oa_esm_project
						where
							contractId <> 0 and contractType = 'GCXMYD-01'
						group by
							contractId,contractType
						) p on c.id = p.contractId
					set c.projectRate = p.workRate,c.projectProcess = p.projectProcess
							";
			$this->query($sql);

			/* 合同项目状态更新 */
			$sql = "update oa_contract_contract c inner join (
						select
							contractId,
							createTime,
							status
						from
							(
								select
									contractId,
									contractType,
									createTime,
									status
								from
									oa_esm_project

								where
									contractId <> 0 and contractType = 'GCXMYD-01'
								ORDER BY
									contractId,contractType,
									createTime DESC
							) c
						GROUP BY
							contractId
					) p on c.id = p.contractId set c.projectStatus = p.status";
			$this->query($sql);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return $e;
		}
	}

	/**
	 * 更新工程部分
	 */
	function updateWftask_d() {
		try {
			$this->start_d();
			/* 工作流部分更新 */
			$sql = "update wf_task c inner join oa_contract_initialize i on c.pid = i.oldContractId set c.pid = i.contractId,c.code = 'oa_contract_contract' where c.code = 'oa_sale_order' and  i.oldContractType = 'HTLX-XSHT'";
			$this->query($sql);
			$sql = "update wf_task c inner join oa_contract_initialize i on c.pid = i.oldContractId set c.pid = i.contractId,c.code = 'oa_contract_contract' where c.code = 'oa_sale_service' and  i.oldContractType = 'HTLX-FWHT'";
			$this->query($sql);
			$sql = "update wf_task c inner join oa_contract_initialize i on c.pid = i.oldContractId set c.pid = i.contractId,c.code = 'oa_contract_contract' where c.code = 'oa_sale_lease' and  i.oldContractType = 'HTLX-ZLHT' ";
			$this->query($sql);
			$sql = "update wf_task c inner join oa_contract_initialize i on c.pid = i.oldContractId set c.pid = i.contractId,c.code = 'oa_contract_contract' where c.code = 'oa_sale_rdproject' and  i.oldContractType = 'HTLX-YFHT' ";
			$this->query($sql);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return $e;
		}
	}

	/**
	 * 更新发货计划信息
	 * @author zengzx
	 * @since 1.0 - 2012年4月19日 07:41:14
	 */
	function updateOutplan_d() {
		try {
			$this->start_d();
			$plus = ' where l.docType in("oa_sale_order","oa_sale_lease",' .
			'"oa_sale_service","oa_sale_rdproject")';
			//更新发货计划关联合同id -- 销售合同
			$planSql = "update oa_stock_outplan l inner join oa_contract_initialize i  " .
			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
			"l.docId=i.contractId where l.docType='oa_sale_order'; ";
			$this->query($planSql);
			//更新发货计划关联合同id -- 服务合同
			$planSql = "update oa_stock_outplan l inner join oa_contract_initialize i  " .
			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
			"l.docId=i.contractId where l.docType='oa_sale_service'; ";
			$this->query($planSql);
			//更新发货计划关联合同id -- 租赁合同
			$planSql = "update oa_stock_outplan l inner join oa_contract_initialize i  " .
			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
			"l.docId=i.contractId where l.docType='oa_sale_lease'; ";
			$this->query($planSql);
			//更新发货计划关联合同id -- 研发合同
			$planSql = "update oa_stock_outplan l inner join oa_contract_initialize i  " .
			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
			"l.docId=i.contractId where l.docType='oa_sale_rdproject'; ";
			$this->query($planSql);

			//更新发货计划关联类型 -- 销售合同
			$outTypeSql = 'update oa_stock_outplan l set l.docType="oa_contract_contract"' .
					' where l.docType="oa_sale_order";';
			$this->query($outTypeSql);
			//更新发货计划关联类型 -- 服务合同
			$outTypeSql = 'update oa_stock_outplan l set l.docType="oa_contract_contract"' .
					' where l.docType="oa_sale_service";';
			$this->query($outTypeSql);
			//更新发货计划关联类型 -- 租赁合同
			$outTypeSql = 'update oa_stock_outplan l set l.docType="oa_contract_contract"' .
					' where l.docType="oa_sale_lease";';
			$this->query($outTypeSql);
			//更新发货计划关联类型 -- 研发合同
			$outTypeSql = 'update oa_stock_outplan l set l.docType="oa_contract_contract"' .
					' where l.docType="oa_sale_rdproject";';
			$this->query($outTypeSql);

			//更新发货计划从表设备id
			$sql = 'update oa_stock_outplan_Product l inner join oa_contract_initialize_from  ' .
			'i  on l.contEquId=i.oldfromId and l.docType="oa_sale_order" and  i.fromType="oa_sale_order_equ" ' .
			' set l.contEquId=i.fromId where l.docType="oa_sale_order";';
			$this->query($sql);
			$sql = 'update oa_stock_outplan_Product l inner join oa_contract_initialize_from  ' .
			'i  on l.contEquId=i.oldfromId and l.docType="oa_sale_service" and  i.fromType="oa_service_equ" ' .
			' set l.contEquId=i.fromId where l.docType="oa_sale_service";';
			$this->query($sql);
			$sql = 'update oa_stock_outplan_Product l inner join oa_contract_initialize_from  ' .
			'i  on l.contEquId=i.oldfromId and l.docType="oa_sale_lease" and  i.fromType="oa_lease_equ" ' .
			' set l.contEquId=i.fromId where l.docType="oa_sale_lease";';
			$this->query($sql);
			$sql = 'update oa_stock_outplan_Product l inner join oa_contract_initialize_from  ' .
			'i  on l.contEquId=i.oldfromId and l.docType="oa_sale_rdproject" and  i.fromType="oa_rdproject_equ" ' .
			' set l.contEquId=i.fromId where l.docType="oa_sale_rdproject";';
			$this->query($sql);

//			//更新从表设备docId -- 销售合同
//			$orderSql = "update oa_stock_outplan_Product l inner join oa_contract_initialize i  " .
//			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
//			"l.docId=i.contractId,l.docCode=i.contractCode;";
//			$this->query($orderSql);
//			//更新从表设备docId -- 服务合同
//			$serviceSql = "update oa_stock_outplan_Product l inner join oa_contract_initialize i  " .
//			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
//			"l.docId=i.contractId,l.docCode=i.contractCode where l.docType='oa_sale_service';";
//			$this->query($serviceSql);
//			//更新从表设备docId -- 租赁合同
//			$leaseSql = "update oa_stock_outplan_Product l inner join oa_contract_initialize i  " .
//			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
//			"l.docId=i.contractId,l.docCode=i.contractCode where l.docType='oa_sale_lease';";
//			$this->query($leaseSql);
//			//更新从表设备docId -- 研发合同
//			$rdprojectSql = "update oa_stock_outplan_Product l inner join oa_contract_initialize i  " .
//			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
//			"l.docId=i.contractId,l.docCode=i.contractCode where l.docType='oa_sale_rdproject';";
//			$this->query($rdprojectSql);
//
//			//更新发货计划从表关联类型
//			$outProTypeSql = 'update oa_stock_outplan_product l set l.docType="oa_contract_contract"' . $plus;
//			$this->query($outProTypeSql);
//
//			//更新发货单关联合同id
//			$shipSql = "update oa_stock_ship l inner join oa_contract_initialize i  " .
//			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
//			"l.docId=i.contractId" . $plus;
//			$this->query($shipSql);
//
//			//更新发货单从表旧类型  **********
//			$sql = 'update oa_stock_ship_product l inner join oa_stock_ship  ' .
//			'i  on i.id=l.mainId ' .
//			' set l.docType=i.docType where l.mainId is not null ';
//			$this->query($sql);
//
//			//更新发货单关联类型
//			$shipProSql = 'update oa_stock_ship l set l.docType="oa_contract_contract"' . $plus;
//			$this->query($shipProSql);
//
//			//更新设备id
//			$sql = 'update oa_stock_ship_product l inner join oa_contract_initialize_from  ' .
//			'i  on l.docId=i.oldfromId and  i.fromType like CONCAT(l.docType,"%") ' .
//			' set l.docId=i.fromId' . $plus;
//			$this->query($sql);
//
//			//更新发货单关联类型
//			$shipProTypeSql = 'update oa_stock_ship_product l set l.docType="oa_contract_contract"' . $plus;
//			$this->query($shipProTypeSql);
//
//			//更新合同发货进度合同id
//			$rateSql = "update oa_contract_rate l inner join oa_contract_initialize i  " .
//			"on l.relDocId=i.oldContractId and l.relDocType=i.oldTableName set " .
//			"l.relDocId=i.contractId where l.relDocType in('oa_sale_order','oa_sale_lease'," .
//			"'oa_sale_service','oa_sale_rdproject') ";
//			$this->query($rateSql);
//
//			//更新合同发货进度合同类型
//			$rateTypeSql = "update oa_contract_rate  l set l.relDocType='oa_contract_contract' where l.relDocType in('oa_sale_order','oa_sale_lease'," .
//			"'oa_sale_service','oa_sale_rdproject') ";
//			$this->query($rateTypeSql);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return $e;
		}
	}

	/**
	 * 更新发货计划从表及发货单数据
	 */
	function updateOutProduct_d(){
		try {
			$this->start_d();
			$plus = ' where l.docType in("oa_sale_order","oa_sale_lease",' .
			'"oa_sale_service","oa_sale_rdproject")';

			//更新从表设备docId -- 销售合同
			$orderSql = "update oa_stock_outplan_Product l inner join oa_contract_initialize i  " .
			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
			"l.docId=i.contractId,l.docCode=i.contractCode;";
			$this->query($orderSql);
//			//更新从表设备docId -- 服务合同
//			$serviceSql = "update oa_stock_outplan_Product l inner join oa_contract_initialize i  " .
//			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
//			"l.docId=i.contractId,l.docCode=i.contractCode where l.docType='oa_sale_service';";
//			$this->query($serviceSql);
//			//更新从表设备docId -- 租赁合同
//			$leaseSql = "update oa_stock_outplan_Product l inner join oa_contract_initialize i  " .
//			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
//			"l.docId=i.contractId,l.docCode=i.contractCode where l.docType='oa_sale_lease';";
//			$this->query($leaseSql);
//			//更新从表设备docId -- 研发合同
//			$rdprojectSql = "update oa_stock_outplan_Product l inner join oa_contract_initialize i  " .
//			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
//			"l.docId=i.contractId,l.docCode=i.contractCode where l.docType='oa_sale_rdproject';";
//			$this->query($rdprojectSql);

			//更新发货计划从表关联类型
			$outProTypeSql = 'update oa_stock_outplan_product l set l.docType="oa_contract_contract"' . $plus;
			$this->query($outProTypeSql);

			//更新发货单关联合同id
			$shipSql = "update oa_stock_ship l inner join oa_contract_initialize i  " .
			"on l.docId=i.oldContractId and l.docType=i.oldTableName set " .
			"l.docId=i.contractId" . $plus;
			$this->query($shipSql);

			//更新发货单从表旧类型  **********
			$sql = 'update oa_stock_ship_product l inner join oa_stock_ship  ' .
			'i  on i.id=l.mainId ' .
			' set l.docType=i.docType where l.mainId is not null ';
			$this->query($sql);

			//更新发货单关联类型
			$shipProSql = 'update oa_stock_ship l set l.docType="oa_contract_contract"' . $plus;
			$this->query($shipProSql);

			//更新设备id
			$sql = 'update oa_stock_ship_product l inner join oa_contract_initialize_from  ' .
			'i  on l.docId=i.oldfromId and  i.fromType like CONCAT(l.docType,"%") ' .
			' set l.docId=i.fromId' . $plus;
			$this->query($sql);

			//更新发货单关联类型
			$shipProTypeSql = 'update oa_stock_ship_product l set l.docType="oa_contract_contract"' . $plus;
			$this->query($shipProTypeSql);

			//更新合同发货进度合同id
			$rateSql = "update oa_contract_rate l inner join oa_contract_initialize i  " .
			"on l.relDocId=i.oldContractId and l.relDocType=i.oldTableName set " .
			"l.relDocId=i.contractId where l.relDocType in('oa_sale_order','oa_sale_lease'," .
			"'oa_sale_service','oa_sale_rdproject') ";
			$this->query($rateSql);

			//更新合同发货进度合同类型
			$rateTypeSql = "update oa_contract_rate  l set l.relDocType='oa_contract_contract' where l.relDocType in('oa_sale_order','oa_sale_lease'," .
			"'oa_sale_service','oa_sale_rdproject') ";
			$this->query($rateTypeSql);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return $e;
		}

	}




	/**
	 *
	 * 更新仓存部分数据
	 */
	function updateStock_d() {
		try {
			$this->start_d();
			/*start:-------------出库单-----------------*/
			/*1: relDocId is null  有一条合同业务编号为空*/
			$sql = "UPDATE oa_stock_outstock o
								INNER JOIN oa_contract_initialize t
								SET o.contractId = t.contractId,
								 o.contractCode = t.contractCode,
								 o.contractObjCode = t.oldObjCode,
								 o.contractType = 'oa_contract_contract'
								WHERE
									o.relDocId IS NULL
								AND o.contractType = t.oldTableName
								AND o.contractId = t.oldContractId;";
			$this->query($sql);

			/*2: relDocId=0 独立新增的出库单并且选择了合同 ，清单不用更新*/
			$sql = "UPDATE oa_stock_outstock o
									INNER JOIN oa_contract_initialize t
									SET o.contractId = t.contractId,
									 o.contractCode = t.contractCode,
									 o.contractObjCode = t.oldObjCode,
									 o.contractType = 'oa_contract_contract'
									WHERE
										o.relDocId = 0
									AND o.contractType = t.oldTableName
									AND o.contractId = t.oldContractId;";
			$this->query($sql);

			/*3: relDocId <>0 更新源单类型为合同的 出库单中对应清单*/
			$sql = "UPDATE oa_stock_outstock_item oi
								INNER JOIN oa_stock_outstock o
								INNER JOIN oa_contract_initialize_from f
								SET oi.relDocId = f.fromId
								WHERE
									oi.relDocId <> 0
								AND o.relDocType = 'XSCKDLHT'
								AND o.contractType = 'oa_sale_order'
								AND o.relDocId = f.oldcontractId
								AND oi.mainId = o.id
								AND oi.relDocId = f.oldfromId
								AND f.fromType ='oa_sale_order_equ';";
			$this->query($sql);

			$sql = "UPDATE oa_stock_outstock_item oi
								INNER JOIN oa_stock_outstock o
								INNER JOIN oa_contract_initialize_from f
								SET oi.relDocId = f.fromId
								WHERE
									oi.relDocId <> 0
								AND o.relDocType = 'XSCKDLHT'
								AND o.contractType = 'oa_sale_service'
								AND o.relDocId = f.oldcontractId
								AND oi.mainId = o.id
								AND oi.relDocId = f.oldfromId
								AND f.fromType ='oa_service_equ';";
			$this->query($sql);

			$sql = "UPDATE oa_stock_outstock_item oi
								INNER JOIN oa_stock_outstock o
								INNER JOIN oa_contract_initialize_from f
								SET oi.relDocId = f.fromId
								WHERE
									oi.relDocId <> 0
								AND o.relDocType = 'XSCKDLHT'
								AND o.contractType = 'oa_sale_lease'
								AND o.relDocId = f.oldcontractId
								AND oi.mainId = o.id
								AND oi.relDocId = f.oldfromId
								AND f.fromType ='oa_lease_equ';";
			$this->query($sql);

			$sql = "UPDATE oa_stock_outstock_item oi
								INNER JOIN oa_stock_outstock o
								INNER JOIN oa_contract_initialize_from f
								SET oi.relDocId = f.fromId
								WHERE
									oi.relDocId <> 0
								AND o.relDocType = 'XSCKDLHT'
								AND o.contractType = 'oa_rdproject_equ'
								AND o.relDocId = f.oldcontractId
								AND oi.mainId = o.id
								AND oi.relDocId = f.oldfromId
								AND f.fromType ='oa_rdproject_equ';";
			$this->query($sql);

			/*4: relDocId <>0 更新源单类型为合同的 出库单中的源单id、源单编号、源单对象编号 */
			$sql="UPDATE oa_stock_outstock o
								INNER JOIN oa_contract_initialize t
								SET o.relDocId = t.contractId,
								    o.relDocCode = t.contractCode,
								    o.rObjCode = t.oldObjCode
								WHERE
									o.relDocId <> 0
              				    AND o.relDocType = 'XSCKDLHT'
								AND o.contractType = t.oldTableName
								AND o.contractId = t.oldContractId;";
			$this->query($sql);


			/*5:更新出库单合同相关字段合同id、合同编号、合同业务编号、合同类型*/
			$sql = "UPDATE oa_stock_outstock o
								INNER JOIN oa_contract_initialize t
								SET o.contractId = t.contractId,
								 o.contractCode = t.contractCode,
								 o.contractObjCode = t.oldObjCode,
								 o.contractType = 'oa_contract_contract'
								WHERE
									o.relDocId <> 0
								AND o.contractType = t.oldTableName
								AND o.contractId = t.oldContractId;";
			$this->query($sql);
			/*end:------------出库单-----------------*/
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return $e;
		}
	}

	/**
	 *更新采购部分数据r
	 *
	 */
	function updatePurchase_d() {
		try {
			$this->start_d();
			/*start:-------------采购单-----------------*/
			/*采购申请*/
			$sql = "UPDATE oa_purch_plan_basic c
								INNER JOIN oa_contract_initialize t
								SET c.sourceID = t.contractId,
								 c.sourceNumb = t.contractCode,
								 c.rObjCode = t.oldObjCode,
								 c.purchType = t.contractType
								WHERE
								c.purchType = t.oldTableName
								AND c.sourceID = t.oldContractId;";
			$this->query($sql);

			/*更新采购申请物料表*/
			/*销售合同*/
			$sql = "UPDATE  oa_purch_plan_equ p
								INNER JOIN oa_contract_initialize_from f
								SET p.applyEquId = f.fromId,
										p.purchType='HTLX-XSHT'
								WHERE
									p.applyEquId <> 0
								AND 	p.purchType = 'oa_sale_order'
								AND p.applyEquId = f.oldfromId
								AND f.fromType ='oa_sale_order_equ';";
			$this->query($sql);

			/*服务合同*/
			$sql = "UPDATE  oa_purch_plan_equ p
								INNER JOIN oa_contract_initialize_from f
								SET p.applyEquId = f.fromId,
										p.purchType='HTLX-FWHT'
								WHERE
								 p.purchType = 'oa_sale_service'
								AND p.applyEquId = f.oldfromId
								AND f.fromType ='oa_service_equ';";
			$this->query($sql);

			/*租赁合同*/
			$sql = "UPDATE  oa_purch_plan_equ p
								INNER JOIN oa_contract_initialize_from f
								SET p.applyEquId = f.fromId,
										p.purchType='HTLX-ZLHT'
								WHERE
								 p.purchType = 'oa_sale_lease'
								AND p.applyEquId = f.oldfromId
								AND f.fromType ='oa_lease_equ';";
			$this->query($sql);

			/*研发合同*/
			$sql = "UPDATE  oa_purch_plan_equ p
								INNER JOIN oa_contract_initialize_from f
								SET p.applyEquId = f.fromId,
										p.purchType='HTLX-YFHT'
								WHERE
								 p.purchType = 'oa_sale_rdproject'
								AND p.applyEquId = f.oldfromId
								AND f.fromType ='oa_rdproject_equ';";
			$this->query($sql);
			/*更新源物料ID为0的数据*/
			$sql = "UPDATE oa_purch_plan_equ set purchType='HTLX-XSHT' where purchType='oa_sale_order';";
			$this->query($sql);
			$sql = "UPDATE oa_purch_plan_equ set purchType='HTLX-FWHT' where purchType='oa_sale_service';";
			$this->query($sql);
			$sql = "UPDATE oa_purch_plan_equ set purchType='HTLX-ZLHT' where purchType='oa_sale_lease';";
			$this->query($sql);
			$sql = "UPDATE oa_purch_plan_equ set purchType='HTLX-YFHT' where purchType='oa_sale_rdproject';";
			$this->query($sql);

			/*更新总关联表*/
			$sql = "UPDATE oa_purch_objass o INNER JOIN oa_purch_plan_equ p ON p.id=o.planEquId
								SET o.planAssEquId=p.applyEquId
								WHERE p.purchType='HTLX-XSHT' OR p.purchType='HTLX-FWHT'
											OR p.purchType='HTLX-ZLHT' OR p.purchType='HTLX-YFHT';";
			$this->query($sql);

			$sql = "UPDATE oa_purch_objass o
								INNER JOIN oa_contract_initialize t
									SET o.planAssId= t.contractId,
									 o.planAssCode = t.contractCode,
									 o.rObjCode = t.oldObjCode,
									 o.planAssType = t.contractType
									WHERE
										o.planAssType = t.oldTableName
									AND o.planAssId = t.oldContractId;";
			$this->query($sql);

			/*采购任务物料表更新*/
			$sql = "UPDATE oa_purch_task_equ set purchType='HTLX-XSHT' where purchType='oa_sale_order';";
			$this->query($sql);
			$sql = "UPDATE oa_purch_task_equ set purchType='HTLX-FWHT' where purchType='oa_sale_service';";
			$this->query($sql);
			$sql = "UPDATE oa_purch_task_equ set purchType='HTLX-ZLHT' where purchType='oa_sale_lease';";
			$this->query($sql);
			$sql = "UPDATE oa_purch_task_equ set purchType='HTLX-YFHT' where purchType='oa_sale_rdproject';";
			$this->query($sql);

			/*采购询价物料表更新*/
			$sql = "UPDATE oa_purch_inquiry_equ set purchType='HTLX-XSHT' where purchType='oa_sale_order';";
			$this->query($sql);
			$sql = "UPDATE oa_purch_inquiry_equ set purchType='HTLX-FWHT' where purchType='oa_sale_service';";
			$this->query($sql);
			$sql = "UPDATE oa_purch_inquiry_equ set purchType='HTLX-ZLHT' where purchType='oa_sale_lease';";
			$this->query($sql);
			$sql = "UPDATE oa_purch_inquiry_equ set purchType='HTLX-YFHT' where purchType='oa_sale_rdproject';";
			$this->query($sql);

			/*采购订单物料表更新*/
			$sql = "UPDATE oa_purch_apply_equ p
								INNER JOIN oa_contract_initialize t
									SET p.sourceID= t.contractId,
									 p.sourceNumb = t.contractCode,
									 p.rObjCode = t.oldObjCode,
									 p.purchType = t.contractType
									WHERE
										p.purchType = t.oldTableName
									AND p.sourceID = t.oldContractId;";
			$this->query($sql);

			$sql = "UPDATE oa_purch_apply_equ set purchType='HTLX-XSHT' where purchType='oa_sale_order';";
			$this->query($sql);
			$sql = "UPDATE oa_purch_apply_equ set purchType='HTLX-FWHT' where purchType='oa_sale_service';";
			$this->query($sql);
			$sql = "UPDATE oa_purch_apply_equ set purchType='HTLX-ZLHT' where purchType='oa_sale_lease';";
			$this->query($sql);
			$sql = "UPDATE oa_purch_apply_equ set purchType='HTLX-YFHT' where purchType='oa_sale_rdproject';";
			$this->query($sql);
			/*end:------------采购单-----------------*/
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return $e;
		}
	}

	/**
	 * setType_d
	 */
	 function setType_d( $id ,$docType , $objInfo ){
	 	$baseDao = $this->getSourceBaseDao($docType);
	 	if (empty ($baseDao)) {
			throw new Exception("该源单dao没有注册");
		}
		$dao = new $baseDao ();
		$condition = array(
			'id'=>$id
		);
		unset($objInfo['id']);
		unset($objInfo['docType']);
		return $dao->update($condition,$objInfo);
	 }

	 /**
	  * 清空自定义类型 ids
	  */
	 function clearCusTypeByIds($ids){
	 	foreach( $this->sourceArr as $tableName => $array ){
			$sql = "update ".$array['baseTable']." set customTypeId=0,customTypeName='',warnDate='' where customTypeId in (".$ids.");";
	 		if($this->query($sql)){
	 			return true;
	 		}else{
				throw new Exception("自定义分类清除失败！");
				return false;
	 		}
	 	}
	 }

	 /**
	 * 获取产品下的物料信息(用于物料确认开始带出)
	 * $sourceType 源单类型：合同，借试用，赠送，换货
	 */
	function getProductEqu_d($id,$sourceType) {
		switch($sourceType){
			case 'contract'://合同
				$dao= new model_contract_contract_product();
				break;
			case 'borrow'://借试用
				$dao= new model_projectmanagent_borrow_product();
				break;
			case 'present'://赠送
				$dao= new model_projectmanagent_present_product();
				break;
			case 'chance'://换货
				$dao= new model_projectmanagent_exchange_exchangeproduct();
				break;
		    case 'chanceEqu'://商机
				$dao= new model_projectmanagent_chance_product();
				break;
		}
		//$productDao = new model_contract_contract_product();
		$goodsArr = $dao->resolve_d($id);
		$productEqu=$dao->get_d($id);
//		print_r($goodsArr);
		$licenseIdArr=array();

		//添加一个缓存配置数量的数组
		$cacheNumArr = array();

		foreach($goodsArr as $k=>$v){
			//如果配置中的数量为0，忽略
			if($v[1] === 0 || $v[1] === '0') continue;
			$a=$licenseIdArr[$v[0]];
			if(!is_array($a)){
				$a=array();
			}
			array_push($a,$v[2]);
			$licenseIdArr[$v[0]]=$a;

			$cacheNumArr[$v[0]] = $v[1];

		}
//		print_r($cacheNumArr);
		$propertiesitemArr = array ();
		if ($goodsArr != 0) {
			$propertiesitemDao = new model_goods_goods_propertiesitem();
			$productInfoDao = new model_stock_productinfo_productinfo();
			$goodsInfoIdArr = array ();
			foreach ($goodsArr as $key => $val) {
				//如果配置中的数量为0，忽略
				if($val[1] === 0 || $val[1] === '0') continue;
				$goodsInfoIdArr[] = $val[0];
			}
//			print_r($goodsInfoIdArr);
			$goodsInfoIdStr = implode(',', $goodsInfoIdArr);
			$propertiesitemDao->searchArr['ids'] = $goodsInfoIdStr;
			$propertiesitemArr = $propertiesitemDao->list_d();
			$productIdArr = array ();
			$propertiesitemArrNew=array();
//			print_r($propertiesitemArr);
			foreach ($propertiesitemArr as $key => $val) {
				$productIdArr[] = $val['productId'];
				$propertiesitemArrNew[$val['id']]=$val;
			}
			$productIdStr = implode(',', $productIdArr);
			$productInfoDao->searchArr['idArr'] = $productIdStr;
			$productArr = $productInfoDao->list_d();
			$productArrNew=array();
			foreach ($productArr as $key => $val) {
				$productArrNew[$val['id']]=$val;
			}
			$equArrReturn=array();
			foreach($licenseIdArr as $key=>$val){
				foreach($val as $k=>$v){
					$item=$propertiesitemArrNew[$key];

					if(!empty($item['productId'])){

						$product=$productArrNew[$item['productId']];
						$item['productId'] = $product['id'];
						$item['productNo'] = $product['productCode'];
						$item['warranty'] = $product['warranty'];
						$item['arrivalPeriod'] = $product['arrivalPeriod'];
						$item['productModel'] = $product['pattern'];
						$item['price'] = $product['priCost'] ? $product['priCost'] : 0;

						if(isset($cacheNumArr[$item['id']])){
							$item['number'] = $cacheNumArr[$item['id']];
						}else{
							$item['number'] = $item['defaultNum'];
						}

						if(empty($v)){
							$v = $productEqu['license'];
						}
						$item['license'] = $v;
						$item['parentLicenseId'] = $productEqu['license'];
						unset ($item['id']);
						$equArrReturn[]=$item;

					}
				}
			}
		}
		//print_r($equArrReturn);
		return $equArrReturn;
	}

/*************************************合同导入***************************************************************************/

/**
 * 根据合同号判断合同是否存在
 */
function getContractbyCode($code){
	if(!empty($code)){
		$sql = "select id from oa_contract_contract where contractCode='".$code."'";
		$arr = $this->_db->getArray($sql);
		return $arr[0]['id'];
	}else{
		return "";
	}
}
/**
 * 根据名字查找对应Id
 */
function user($userName) {
	if(!empty($userName)){
		$userDao = new model_deptuser_user_user();
		$user = $userDao->getUserByName($userName);
		$userId = $user['USER_ID'];
		return $userId;
	}else{
		return "";
	}

}
function userArr($userName) {
	if(!empty($userName)){
		$userDao = new model_deptuser_user_user();
		$user = $userDao->getUserByName($userName);
		return $user;
	}else{
		return "";
	}

}
/**
 * 判断客户是否存在
 */
function isCus($cusName){
	if(!empty($cusName)){
		$customerDao = new model_customer_customer_customer();
		$customerId = $customerDao->findCus($cusName);
		return $customerId;
	}else{
		return "";
	}
}
function isCusCode($cusCode){
	if(!empty($cusCode)){
		$customerDao = new model_customer_customer_customer();
		$customerId = $customerDao->findCusCode($cusCode);
		return $customerId;
	}else{
		return "";
	}
}
/**
 * 处理客户相关信息
 */
function isCusInfo($cusId){
	if(!empty($cusId)){
	   $customerDao = new model_customer_customer_customer();
	   $cusInfoArr = $customerDao->get_d($cusId);
	//   //客户性质
	//    $datadictDao = new model_system_datadict_datadict();
	//    $customerType = $datadictDao->getCodeByName("KHLX", $cusInfoArr['TypeOne']);
	   $cusInfo = array(
	      "customerName"=>$cusInfoArr['Name'],
	      "customerType"=>$cusInfoArr['TypeOne'],
		  "address"=>$cusInfoArr['Address'],
		  "contractCountry"=>$cusInfoArr['Country'],
		  "contractCountryId"=>$cusInfoArr['CountryId'],
		  "contractProvince"=>$cusInfoArr['Prov'],
		  "contractProvinceId"=>$cusInfoArr['ProvId'],
		  "contractCity"=>$cusInfoArr['City'],
		  "contractCityId"=>$cusInfoArr['CityId'],
		  "areaPrincipal"=>$cusInfoArr['AreaLeader'],
		  "areaPrincipalId"=>$cusInfoArr['AreaLeaderId'],
		  "areaName"=>$cusInfoArr['AreaName'],
	      "areaCode"=>$cusInfoArr['AreaId'],
	   );
	    return $cusInfo;
	}else{
		return "";
	}
}


	/*
	 * 转换时间戳
	 */
	function transitionTime($timestamp){
		$time = "";
		if(!empty($timestamp)){
			$wirteDate = mktime(0, 0, 0, 1, $timestamp - 1, 1900);
		    $time = date("Y-m-d", $wirteDate);
		}
		 return $time;
	}

	/*
	 * 根据商机编号判断编号是否已存在
	 */
	function findChanceCode($chanceCode){
	  if(!empty($chanceCode)){
	  	 $sql="select * from oa_sale_chance where chanceCode='".$chanceCode."'";
	    return $this->_db->getArray($sql);
	  }else{
	  	return "1";
	  }
	}
/*************************************合同导入************end***************************************************************/

}
?>
