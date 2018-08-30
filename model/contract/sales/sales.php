<?php
class model_contract_sales_sales extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_sales";
		$this->pk = "id";
		$this->sql_map = "contract/sales/salesSql.php";
		parent :: __construct();
	}

	/**
	 * 重写edit
	 */
	function editBySelf($object){
		return parent::edit_d($object,true);
	}

	/**
	 * 数据操作-插入合同信息-新建
	 */
	function add_d($object) {
		$object['ExaStatus'] = WAITAUDIT;
		$object['isUsing'] = 1;
		$object['changeStatus'] = $object['contStatus'] = 0;

		//获取对象中的自定义清单，获取后清空
		$linkman = new model_contract_linkman_linkman();
		$linkmanrows = $object['linkman'];
		unset($object['linkman']);
		//获取对象中的自定义清单，获取后清空
		$equipment = new model_contract_equipment_equipment();
		$equipmentrows = $object['equipment'];
		unset($object['equipment']);
		//var_dump($object['licenselist']);
		//获取对象中的加密列表，获取后清空
		$licenselist = new model_contract_licenselist_licenselist();
		$licenselistrows = $object['licenselist'];
		unset($object['licenselist']);

		//获取对象中的自定义清单，获取后清空
		$customizelist = new model_contract_customizelist_customizelist();
		$customizelistrows = $object['customizelist'];
		unset($object['customizelist']);
		//获取对象中的开票计划，获取后清空
		$invoice = new model_contract_invoice_invoice();
		$invrows = $object['invoice'];
		unset($object['invoice']);
		//获取对象中的收款计划，获取后清空
		$receiptplan = new model_contract_receiptplan_receiptplan();
		$recrows = $object['receiptplan'];
		unset($object['receiptplan']);
		//获取对象中的培训计划，获取后清空
		$trainingplan = new model_contract_trainingplan_trainingplan();
		$trainrows = $object['trainingplan'];
		unset($object['trainingplan']);

		try {
			$this->start_d();
			$in_id = parent::add_d($object,true);
			//更新附件关联关系
			$this->updateObjWithFile($in_id,$object['contNumber']);

			$linkman->batchInsert($in_id, $object['contNumber'], $object['contName'], $linkmanrows);
			$equipment->insertRows($in_id, $object['contNumber'], $object['contName'], $object['version'], $equipmentrows);
			$licenselist->batchInsert($in_id, $object['contNumber'], $object['contName'], $licenselistrows);
			$customizelist->batchInsert($in_id, $object['contNumber'], $object['contName'], $customizelistrows);
			$invoice->batchInsert($in_id, $object['contNumber'], $object['contName'], $invrows);
			$receiptplan->batchInsert($in_id, $object['contNumber'], $object['contName'], $recrows);
			$trainingplan->batchInsert($in_id, $object['contNumber'], $object['contName'], $trainrows);


//			$this->rollBack();
			$this->commit_d();
			return $in_id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 数据操作-重写编辑对象-暂定只用于未提交审批的合同
	 */
	function edit_d($object){
		try {
			$this->start_d();
			parent::edit_d($object,true);

			//开票计划
			$linkman = new model_contract_linkman_linkman();
			$linkman->delectByIdAndNumber($object['id'], $object['contNumber']);
			if(!empty($object['linkman'])){
				$linkrows = $object['linkman'];
				unset($object['linkman']);
				$linkman->batchInsert($object['id'], $object['contNumber'], $object['contName'], $linkrows);
			}

			//产品清单
			$equipmentDao = new model_contract_equipment_equipment();
			$equipmentDao->delectByIdAndNumber($object['id'], $object['contNumber']);
			if(!empty($object['equipment'])){
				$equipmentrows = $object['equipment'];
				unset($object['equipment']);
				$equipmentDao->insertRows($object['id'], $object['contNumber'], $object['contName'],$object['version'], $equipmentrows);
			}
			//加密信息
			$licenselistDao = new model_contract_licenselist_licenselist();
			$licenselistDao->delectByIdAndNumber($object['id'], $object['contNumber']);
			if(!empty($object['licenselist'])){
				$licenselistrows = $object['licenselist'];
				unset($object['licenselist']);
				$licenselistDao->batchInsert($object['id'], $object['contNumber'], $object['contName'], $licenselistrows);
			}
			//自定义清单
			$customizelistDao = new model_contract_customizelist_customizelist();
			$customizelistDao->delectByIdAndNumber($object['id'], $object['contNumber']);
			if(!empty($object['customizelist'])){
				$customizelistrows = $object['customizelist'];
				unset($object['customizelist']);
				$customizelistDao->batchInsert($object['id'], $object['contNumber'], $object['contName'], $customizelistrows);
			}
			//开票计划
			$invoice = new model_contract_invoice_invoice();
			$invoice->delectByIdAndNumber($object['id'], $object['contNumber']);
			if(!empty($object['invoice'])){
				$invrows = $object['invoice'];
				unset($object['invoice']);
				$invoice->batchInsert($object['id'], $object['contNumber'], $object['contName'], $invrows);
			}
			//收款计划
			$receiptplan = new model_contract_receiptplan_receiptplan();
			$receiptplan->delectByIdAndNumber($object['id'], $object['contNumber']);
			if(!empty($object['receiptplan'])){
				$recrows = $object['receiptplan'];
				unset($object['receiptplan']);
				$receiptplan->batchInsert($object['id'], $object['contNumber'], $object['contName'], $recrows);
			}
			//培训计划
			$trainingplan = new model_contract_trainingplan_trainingplan();
			$trainingplan->delectByIdAndNumber($object['id'],$object['contNumber']);
			if(!empty($object['trainingplan'])){
				$trainrows = $object['trainingplan'];
				unset($object['trainingplan']);
				$trainingplan->batchInsert($object['id'], $object['contNumber'], $object['contName'], $trainrows);
			}

//			$this->rollBack();
			$this->commit_d();
			return true;
		}catch(exception $e){
//			echo $e;
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 数据操作-打回后重新编辑
	 */
	function editAgainAfterBack($object){
		$object['ExaStatus'] = WAITAUDIT;
		$object['version'] = $this->getMaxContractVersion($object['oldContId']) + 1;
		$object['isUsing'] = 1;
		$object['changeStatus'] = $object['contStatus'] = 0;


		//获取对象中的客户联系人，获取后清空
		$linkman = new model_contract_linkman_linkman();
		$linkmanrows = $object['linkman'];
		unset($object['linkman']);

		//获取对象中的设备清单，获取后清空
		$equipment = new model_contract_equipment_equipment();
		$equipmentrows = $object['equipment'];
		unset($object['equipment']);

		//获取对象中的加密列表，获取后清空
		$licenselist = new model_contract_licenselist_licenselist();
		$licenselistrows = $object['licenselist'];
		unset($object['licenselist']);

		//获取对象中的自定义清单，获取后清空
		$customizelist = new model_contract_customizelist_customizelist();
		$customizelistrows = $object['customizelist'];
		unset($object['customizelist']);
		//获取对象中的开票计划，获取后清空
		$invoice = new model_contract_invoice_invoice();
		$invrows = $object['invoice'];
		unset($object['invoice']);
		//获取对象中的收款计划，获取后清空
		$receiptplan = new model_contract_receiptplan_receiptplan();
		$recrows = $object['receiptplan'];
		unset($object['receiptplan']);
		//获取对象中的培训计划，获取后清空
		$trainingplan = new model_contract_trainingplan_trainingplan();
		$trainrows = $object['trainingplan'];
		unset($object['trainingplan']);
		try {
			$this->start_d();
			$this->setContCloseAfterBack($object['oldContId']);
			$in_id = parent::add_d($object,true);
			$linkman->batchInsert($in_id, $object['contNumber'], $object['contName'], $linkmanrows);
			$equipment->insertRows($in_id, $object['contNumber'], $object['contName'], $object['version'], $equipmentrows);
			$licenselist->batchInsert($in_id, $object['contNumber'], $object['contName'], $licenselistrows);
			$customizelist->batchInsert($in_id, $object['contNumber'], $object['contName'], $customizelistrows);
			$invoice->batchInsert($in_id, $object['contNumber'], $object['contName'], $invrows);
			$receiptplan->batchInsert($in_id, $object['contNumber'], $object['contName'], $recrows);
			$trainingplan->batchInsert($in_id, $object['contNumber'], $object['contName'], $trainrows);

//			$this->rollBack();
			$this->commit_d();
			return $in_id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 数据操作-合同变更
	 */
	function contractChange($object){
		$object['version'] = $this->getMaxContractVersion($object['change']['oldContId']) + 1;
		$object['isUsing'] = 0;
		$object['changeStatus'] = 0;

		//获取对象中的变更申请单，获取后清空
		$change = new model_contract_change_change();
		$changerows = $object['change'];
		$changerows['ExaStatus'] = "变更待审批";
//		$changerows['formalNo'] = $object['formalNo'] ;
		$changerows['contNumber'] = $object['contNumber'] ;
		$changerows['version'] = $change->getMaxVersion($changerows['formNumber']) + 1;
		$changerows['isUsing'] = 1;
		unset($object['change']);

		//获取对象中的客户联系人，获取后清空
		$linkman = new model_contract_linkman_linkman();
		$linkmanrows = $object['linkman'];
		unset($object['linkman']);

		//获取对象中的设备清单，获取后清空
		$equipment = new model_contract_equipment_equipment();
		$equipmentrows = $object['equipment'];
		unset($object['equipment']);

		//获取对象中的加密列表，获取后清空
		$licenselist = new model_contract_licenselist_licenselist();
		$licenselistrows = $object['licenselist'];
		unset($object['licenselist']);

		//获取对象中的自定义清单，获取后清空
		$customizelist = new model_contract_customizelist_customizelist();
		$customizelistrows = $object['customizelist'];
		unset($object['customizelist']);
		//获取对象中的开票计划，获取后清空
		$invoice = new model_contract_invoice_invoice();
		$invrows = $object['invoice'];
		unset($object['invoice']);
		//获取对象中的收款计划，获取后清空
		$receiptplan = new model_contract_receiptplan_receiptplan();
		$recrows = $object['receiptplan'];
		unset($object['receiptplan']);
		//获取对象中的培训计划，获取后清空
		$trainingplan = new model_contract_trainingplan_trainingplan();
		$trainrows = $object['trainingplan'];
		unset($object['trainingplan']);
		try {
			$this->start_d();
			$in_id = parent::add_d($object,true);//新起草的合同
			$changerows['newContId'] = $in_id;
			$this->markChange($changerows['oldContId']);//变更时标记该合同经过变更
			$return_id =  $change->add_d($changerows,'true');//添加申请单的ID
			$linkman->batchInsert($in_id, $object['contNumber'], $object['contName'], $linkmanrows);
			$equipment->insertRows($in_id, $object['contNumber'], $object['contName'],$object['version'], $equipmentrows);
			$licenselist->batchInsert($in_id, $object['contNumber'], $object['contName'], $licenselistrows);
			$customizelist->batchInsert($in_id, $object['contNumber'], $object['contName'], $customizelistrows);
			$invoice->batchInsert($in_id, $object['contNumber'], $object['contName'], $invrows);
			$receiptplan->batchInsert($in_id, $object['contNumber'], $object['contName'], $recrows);
			$trainingplan->batchInsert($in_id, $object['contNumber'], $object['contName'], $trainrows);

			$this->commit_d();
			return $return_id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 页面显示-获取合同对象-合同信息
	 */
	function getContractInfo_d($id,$undertable=null) {
		$rows = parent::get_d($id);
		if(empty($undertable)){
			$linkman = new model_contract_linkman_linkman();
			$rows['linkman'] = $linkman->showLinkmanList($rows['id'], $rows['contNumber']);

			$equipment = new model_contract_equipment_equipment();
			$rows['equipment'] = $equipment->showEquipmentList($rows['id'], $rows['contNumber']);

			$licenselist = new model_contract_licenselist_licenselist();
			$rows['licenselist'] = $licenselist->showLicenseList($rows['id'], $rows['contNumber']);

			$customizelist = new model_contract_customizelist_customizelist();
			$rows['customizelist'] = $customizelist->showCustomizeList($rows['id'], $rows['contNumber']);

			$invoice = new model_contract_invoice_invoice();
			$rows['invlist'] = $invoice->showInvList($rows['id'], $rows['contNumber']);

			$receiptplan = new model_contract_receiptplan_receiptplan();
			$rows['receiptplan'] = $receiptplan->showReceiptList($rows['id'], $rows['contNumber']);

			$trainingplan = new model_contract_trainingplan_trainingplan();
			$rows['trainingplan'] = $trainingplan->showTrainList($rows['id'], $rows['contNumber']);
		}else if(is_array($undertable)){
			if(in_array('linkman',$undertable)){
				$linkman = new model_contract_linkman_linkman();
				$rows['linkman'] = $linkman->showLinkmanList($id);
			}
			if(in_array('equipment',$undertable)){
				$equipment = new model_contract_equipment_equipment();
				$rows['equipment'] = $equipment->showEquipmentList($id);
			}
			if(in_array('licenselist',$undertable)){
				$licenselist = new model_contract_licenselist_licenselist();
				$rows['licenselist'] = $licenselist->showLicenseList($id);
			}
			if(in_array('customizelist',$undertable)){
				$customizelist = new model_contract_customizelist_customizelist();
				$rows['customizelist'] = $customizelist->showCustomizeList($id);
			}
			if(in_array('invlist',$undertable)){
				$invoice = new model_contract_invoice_invoice();
				$rows['invlist'] = $invoice->showInvList($id);
			}
			if(in_array('receiptplan',$undertable)){
				$receiptplan = new model_contract_receiptplan_receiptplan();
				$rows['receiptplan'] = $receiptplan->showReceiptList($id);
			}
			if(in_array('trainingplan',$undertable)){
				$trainingplan = new model_contract_trainingplan_trainingplan();
				$rows['trainingplan'] = $trainingplan->showTrainList($id);
			}
		}else if($undertable == "none"){
			return $rows;
		}
//		echo "<pre>";
//		print_R( $rows );
		return $rows;
	}

	/**
	 * 页面显示-合同信息装载从表
	 */
	function contractInfo($rows){
		$rows['signStatus'] = $this->showSignStatusInfo($rows['signStatus']);
		$rows['startType'] = $this->showStartTypeInfo($rows['startType']);
		$rows['shipCondition'] = $this->showShipConditionInfo($rows['shipCondition']);

		$linkman = new model_contract_linkman_linkman();
		$rows['linkman'] = $linkman->showlist($rows['linkman']);

		$equipment = new model_contract_equipment_equipment();
		$rows['equipment'] = $equipment->showlistInfo($rows['equipment']);

		$licenselist = new model_contract_licenselist_licenselist();
		$rows['licenselist'] = $licenselist->showlist($rows['licenselist']);

		$customizelist = new model_contract_customizelist_customizelist();
		$rows['customizelist'] = $customizelist->showlist($rows['customizelist']);

		$invoice = new model_contract_invoice_invoice();
		$rows['invlist'] = $invoice->showlist($rows['invlist']);

		$receiptplan = new model_contract_receiptplan_receiptplan();
		$rows['receiptplan'] = $receiptplan->showlist($rows['receiptplan']);

		$trainingplan = new model_contract_trainingplan_trainingplan();
		$rows['trainingplan'] = $trainingplan->showlist($rows['trainingplan']);

		return $rows;
	}

	/**
	 * 编辑合同时装载从表
	 */
	function contractInfoForEdit($rows){
		$rows['signStatus'] = $this->showSignStatus($rows['signStatus']);
		$rows['startType'] = $this->showStartType($rows['startType']);
		$rows['shipCondition'] = $this->showShipCondition($rows['shipCondition']);

		$linkman = new model_contract_linkman_linkman();
		$notdeal = $linkman->showlistInEdit($rows['linkman']);
		$rows['linkNum'] = $notdeal[0];
		$rows['linkman'] = $notdeal[1];

		$equipment = new model_contract_equipment_equipment();
		$notdeal = $equipment->showlistInEdit($rows['equipment']);
		$rows['equNum'] = $notdeal[0];
		$rows['equipment'] = $notdeal[1];

		$licenselist = new model_contract_licenselist_licenselist();
		$notdeal = $licenselist->showlistInEdit($rows['licenselist']);
		$rows['licenseNum'] = $notdeal[0];
		$rows['licenselist'] = $notdeal[1];

		$customizelist = new model_contract_customizelist_customizelist();
		$notdeal = $customizelist->showlistInEdit($rows['customizelist']);
		$rows['cusNum'] = $notdeal[0];
		$rows['customizelist'] = $notdeal[1];

		$invoice = new model_contract_invoice_invoice();
		$notdeal = $invoice->showlistInEdit($rows['invlist']);
		$rows['invNum'] = $notdeal[0];
		$rows['invlist'] = $notdeal[1];

		$receiptplan = new model_contract_receiptplan_receiptplan();
		$notdeal = $receiptplan->showlistInEdit($rows['receiptplan']);
		$rows['payNum'] = $notdeal[0];
		$rows['receiptplan'] = $notdeal[1];

		$trainingplan = new model_contract_trainingplan_trainingplan();
		$notdeal = $trainingplan->showlistInEdit($rows['trainingplan']);
		$rows['traNumber'] = $notdeal[0];
		$rows['trainingplan'] = $notdeal[1];

		return $rows;
	}

	/**
	 * 变更合同时装载从表
	 */
	function contractInfoForChange($rows,$equipRow = null){
		$rows['signStatus'] = $this->showSignStatus($rows['signStatus']);
		$rows['startType'] = $this->showStartType($rows['startType']);
		$rows['shipCondition'] = $this->showShipCondition($rows['shipCondition']);

		$change = new model_contract_change_change();
		$change->checkExemining($rows['id']);

		$linkman = new model_contract_linkman_linkman();
		$notdeal = $linkman->showlistInEdit($rows['linkman']);
		$rows['linkNum'] = $notdeal[0];
		$rows['linkman'] = $notdeal[1];

		$equipment = new model_contract_equipment_equipment();
		$notdeal = $equipment->showlistInChange($rows['equipment'],$equipRow);
		$rows['equNum'] = $notdeal[0];
		$rows['equipment'] = $notdeal[1];

		$licenselist = new model_contract_licenselist_licenselist();
		$notdeal = $licenselist->showlistInEdit($rows['licenselist']);
		$rows['licenseNum'] = $notdeal[0];
		$rows['licenselist'] = $notdeal[1];

		$customizelist = new model_contract_customizelist_customizelist();
		$notdeal = $customizelist->showlistInEdit($rows['customizelist']);
		$rows['cusNum'] = $notdeal[0];
		$rows['customizelist'] = $notdeal[1];

		$invoice = new model_contract_invoice_invoice();
		$notdeal = $invoice->showlistInEdit($rows['invlist']);
		$rows['invNum'] = $notdeal[0];
		$rows['invlist'] = $notdeal[1];

		$receiptplan = new model_contract_receiptplan_receiptplan();
		$notdeal = $receiptplan->showlistInEdit($rows['receiptplan']);
		$rows['payNum'] = $notdeal[0];
		$rows['receiptplan'] = $notdeal[1];

		$trainingplan = new model_contract_trainingplan_trainingplan();
		$notdeal = $trainingplan->showlistInEdit($rows['trainingplan']);
		$rows['traNumber'] = $notdeal[0];
		$rows['trainingplan'] = $notdeal[1];

		return $rows;
	}

	/**
	 * 处理合同时转载设备信息
	 */
	function showDetaiInEC($rows){
		$equipment = new model_contract_equipment_equipment();
		$rows['equipment'] = $equipment->showDetailByEC($equipment->showEquListInByEC($rows['id']));
		return $rows;
	}

	/**
	 * 锁定设备时显示设备信息
	 */
	function showDetailInLock($rows){
		$equipment = new model_contract_equipment_equipment();
		$rows['list'] = $equipment->showDetailByLock($equipment->showEquListInByEC($rows['id']));

		return $rows;
	}

	/**
	 * 数据操作-根据合同编号获取合同基本信息
	 */
	function getContractVersion($contNumber){
		$condition = array (
			"contNumber" => $contNumber
		);
		return $this->find($condition);
	}

	/**
	 * 页面显示-签约状态-非编辑类页面
	 */
	function showSignStatusInfo($value){
		if ($value == 0) {
			$value = "未签约";
		}else if ($value == 1) {
			$value = "已签约";
		} else if($value== 2){
			$value = "已拿到纸质合同";
		} else if($value== 3){
			$value = "已提交纸质合同";
		}else{
			$value = "财务已签收";
		}
		return $value;
	}


	/**
	 * 页面显示-提交方式-非编辑类页面
	 */
	function showStartTypeInfo($value){
		if ($value == 0) {
			$value = "通知启动";
		}else if ($value == 1) {
			$value = "审批后直接启动";
		}else{
			$value = "合同已启动";
		}
		return $value;
	}



	/**
	 * 页面显示-发货方式-非编辑类页面
	 */
	function showshipConditionInfo($value){
		if ($value == 0) {
			$value = "款到发货";
		}else{
			$value = "货到付款";
		}
		return $value;
	}



	/**
	 * 页面显示-签收状态-编辑类合同页面中
	 */
	function showSignStatus($value){
		if($value == "0"){
			$val1 = "checked";
			$val2 = "";
			$val3 = "";
			$val4 = "";
		}elseif($value == "1"){
			$val1 = "";
			$val2 = "checked";
			$val3 = "";
			$val4 = "";
		}elseif($value == "2"){
			$val1 = "";
			$val2 = "";
			$val3 = "checked";
			$val4 = "";
		}elseif($value == "3") {
			$val1 = "";
			$val2 = "";
			$val3 = "";
			$val4 = "checked";
		}else{
			return "财务已签收";
		}
		$str=<<<EOT
			<input type="radio" name="sales[signStatus]" value="0" $val1>未签约
	 		<input type="radio" name="sales[signStatus]" value="1" $val2>已签约
	 		<input type="radio" name="sales[signStatus]" value="2" $val3>已拿到纸质合同
	 		<input type="radio" name="sales[signStatus]" value="3" $val4>已提交纸质合同
EOT;
		return $str;
	}


	/**
	 * 页面显示-提交方式-编辑类合同页面中
	 */
	function showStartType($value){
		if($value == "1"){
			$val1 = "checked";
			$val2 = "";
		}else{
			$val1 = "";
			$val2 = "checked";
		}
		$str=<<<EOT
			<input type="radio" name="sales[startType]" value="1" $val1>通知启动
	 		<input type="radio" name="sales[startType]" value="2" $val2>审批后直接启动
EOT;
		return $str;
	}



	/**
	 * 页面显示-提交方式-编辑类合同页面中
	 */
	function showShipCondition($value){
		if($value == "1"){
			$val1 = "checked";
			$val2 = "";
		}else{
			$val1 = "";
			$val2 = "checked";
		}
		$str=<<<EOT
			<input type="radio" name="sales[shipCondition]" value="1" $val1>款到发货
	 		<input type="radio" name="sales[shipCondition]" value="2" $val2>货到付款
EOT;
		return $str;
	}



	/**
	 * 返回合同状态列表
	 */
	function returnContStatus($value){
		switch ($value) {
			case '': return '未启动';break;
			case '0': return '未启动';break;
			case '1': return '正执行';break;
			case '2': return '变更待审批';break;
			case '3': return '变更中';break;
			case '4': return '打回关闭';break;
			case '5': return '保留删除';break;
			case '6': return '变更后关闭';break;
			case '9': return '已关闭';break;
			default : return '未启动';break;
		}
	}

	/**
	 * 在执行的合同(旧：含有设备数量)
	 */
	function showExecuting_s($rows) {
		if ($rows) {
			$i = 0;
			$equipment = new model_contract_equipment_equipment();
			$change = new model_contract_change_change();
			$rowsT = $equipment->showEquListInCont($rows);
			$str = "";
			foreach ($rows as $key => $val) {
				$i++;
				$iClass = (($i%2)==0)?'tr_even':'tr_odd';
				if($val['changeStatus'] == 1){
					if($change->isChanging($val['id'])) $val['ExaStatus'] = "变更中";
				}
				$str .=<<<EOT
				<tr align="center" class="$iClass">
					<td width="5%">
						<img src="images/collapsed.gif" id="changeTab$i" title="$i" />$i
					</td>
					<td> $val[contNumber] </td>
					<td> $val[contName] </td>
					<td width="60%" class="td_table">
						<table id="table$i" class="main_table_nested"  width="100%">
EOT;

				if ($rowsT) {
					$str .= $equipment->showlist($val['contNumber'], $rowsT);
				} else
					$str .= '<tr align="center"><td colspan="8" align="center">暂无相关内容</td></tr>';
				$str .=<<<EOT
						</table><div id="inputDiv$i" title="$i"><单击展开本设备具体信息></div>
					</td>
					<td>
						<p>
							<select name="selecter" id="selecter" class="myPrincipal">
				    			<option value="">请选择操作</option>
				    			<option value="info">合同信息</option>
EOT;
			    			if($val['ExaStatus'] != "变更中") $str.=<<<EOT
				        		<option value="respon">指定负责人</option>
				    			<option value="executor">指定执行人</option>
EOT;
							$str.=<<<EOT
				    		</select>
				    		<input type="hidden" value="$val[id]" />
				    		<input type="hidden" value="$val[contNumber]" />
			    		</p>
			    	</td>
				</tr>
EOT;
			}
//<option value="newpurchaseplan">下达采购计划</option>
		} else {
			$str = '<tr align="center" height="28"><td colspan="8">暂无相关信息</td></tr>';
		}
		return $str;
	}

	/**
	 * 根据ID获得签约状态
	 */
	function returnSignStatus($id){
		$condition = array ("id" => $id );
		return $this->find ( $condition ,'','signStatus,contNumber');
	}

	/**
	 * 根据ID获得合同号和合同名称
	 */
	function returnMainMsg($id){
		return $this->find(array('id'=>$id),null,'contNumber,contName');
	}

	/**
	 * 根据合同ID获得合同相关关闭信息
	 */
	function returnCloseInfo($id){
		$bcinfo = new model_contract_common_bcinfo();
		$rows = $bcinfo->getInfo($id,'1');
		if($rows){
			$datadict = new model_system_datadict_datadict();
			$rows['closeType'] = $datadict->getDataNameByCode($rows['doType']);
	//		print_r($rows);
			return $rows;
		}
	}

	/**
	 * 启动合同执行
	 */
	function contractBegin($object){
		$equipment = new model_contract_equipment_equipment();
		$object['contStatus'] = "1";
		$object['beginTime'] = $object['updateTime'] = date("Y-m-d H:i:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		try{
			$this->start_d();
			$this->updateById ( $object );
			$equipment->initialize($object['id']);
//			$this->rollBack();
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 启动同-变更时使用-抛出异常
	 */
	function contractBeginInChange($object){
		$equipment = new model_contract_equipment_equipment();
		$object['beginTime'] = $object['updateTime'] = date("Y-m-d H:i:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['changeStatus'] = 0;
		if(empty($object['executorId'])){
			unset($object['executorId']);
			unset($object['executorName']);
		}
		try{
			$this->updateById ( $object );
			$equipment->initialize($object['id']);
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * 数据操作-关闭合同
	 */
	function contractClose($object){
		$object['ExaStatus'] = "已关闭";
		$object = $this->addUpdateInfo($object);
		$this->updateById ( $object );
		return true;
	}

	/**
	 * 数据操作-关闭合同（新）不在合同表内存放关闭信息
	 */
	function contractClose2($id){
		$object['id'] = $id;
		$object['contStatus'] = "9";//contStatus为 9 时 表示合同处于关闭状态
		$object = $this->addUpdateInfo($object);
		try{
			return $this->updateById ( $object );
		}catch(exception $e){
			$this->rollBack();
			throw $e;
		}
	}

//	/**
//	 * 数据操作-根据合同编号获取合同内容
//	 */
//	function getContractByContNumber(){
//		return $this->pageBySqlId('version_list');
//	}

	/**
	 * 数据操作-根据合同编号获取合同最高版本
	 */
	function getMaxContractVersion($id){
		$contNumber = $this->find(array('id' => $id),null,'contNumber');
		$max = $this->find(array('contNumber' => $contNumber['contNumber']),'version desc','version');
		return $max['version'];
	}

	/**
	 * 数据操作-根据合同ID获取合同当前负责人和执行人
	 */
	function getExecutorAndPrincipal($id){
		$rows = $this->find(array( 'id' => $id),null,'principalId,principalName,executorId,executorName,ExaStatus,contStatus');
		return $rows;
	}

	/**
	 * 数据操作-根据合同ID打回关闭合同
	 * 改状态在合同被打回后，执行重新编辑，生成新的合同关闭旧的合同
	 */
	function setContCloseAfterBack($id){
		$object['id'] = $id;
		$object['isUsing'] = 2;//状态2暂定为打回关闭
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['contStatus'] = 4;
//		$object['ExaStatus'] = "打回关闭";
		return $this->updateById($object);
	}

	/**
	 * 假删除操作
	 */
	function notDel($id){
		$object['id'] = $id;
		$object['isUsing'] = 3;//状态3暂定为不删档删除
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['contStatus'] = 5;
//		$object['ExaStatus'] = "保留删除";
		return $this->updateById($object);
	}

	/**
	 * 数据操作-变更之后关闭原来版本的合同
	 */
	function closeAfterChange($id)
	{
		$object['id'] = $id;
		$object['isUsing'] = 4;//状态4暂定为变更后关闭
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['contStatus'] = 6;
//		$object['ExaStatus'] = "变更后关闭";
		return $this->updateById($object);
	}

	/**
	 * 数据操作-标识合同有变更操作
	 */
	function markChange($id){
		$object['id'] = $id;
//		$object['contStatus'] = 3;
		$object['changeStatus'] = 1;
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		return $this->updateById($object);
	}

	/**
	 * 数据操作-取消合同变更的标识
	 */
	function cancelChange($id){
		$object['id'] = $id;
		$object['changeStatus'] = 0;
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		return $this->updateById($object);
	}

	/**
	 * 根据合同编号确定合同状态为使用中
	 */
	function isExecuting($contNumber){
		$object = $this->findAll(array( 'contNumber' => $contNumber,'isUsing' => '1' , 'contStatus' => '1' ),null,'id');
		if(empty($object)){
			return false;
		}else{
			return true;
		}
	}

	/*
	 * 重写edit_d
	 */
	 function infoedit_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		return $this->updateById ( $object );
	}

	/**
	 * 签收合同
	 */
	function sign_d($id){
		return $this->editBySelf(array('id' => $id ,'signStatus' => '4'));
	}
}
?>
