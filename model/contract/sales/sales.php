<?php
class model_contract_sales_sales extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_sales";
		$this->pk = "id";
		$this->sql_map = "contract/sales/salesSql.php";
		parent :: __construct();
	}

	/**
	 * ��дedit
	 */
	function editBySelf($object){
		return parent::edit_d($object,true);
	}

	/**
	 * ���ݲ���-�����ͬ��Ϣ-�½�
	 */
	function add_d($object) {
		$object['ExaStatus'] = WAITAUDIT;
		$object['isUsing'] = 1;
		$object['changeStatus'] = $object['contStatus'] = 0;

		//��ȡ�����е��Զ����嵥����ȡ�����
		$linkman = new model_contract_linkman_linkman();
		$linkmanrows = $object['linkman'];
		unset($object['linkman']);
		//��ȡ�����е��Զ����嵥����ȡ�����
		$equipment = new model_contract_equipment_equipment();
		$equipmentrows = $object['equipment'];
		unset($object['equipment']);
		//var_dump($object['licenselist']);
		//��ȡ�����еļ����б���ȡ�����
		$licenselist = new model_contract_licenselist_licenselist();
		$licenselistrows = $object['licenselist'];
		unset($object['licenselist']);

		//��ȡ�����е��Զ����嵥����ȡ�����
		$customizelist = new model_contract_customizelist_customizelist();
		$customizelistrows = $object['customizelist'];
		unset($object['customizelist']);
		//��ȡ�����еĿ�Ʊ�ƻ�����ȡ�����
		$invoice = new model_contract_invoice_invoice();
		$invrows = $object['invoice'];
		unset($object['invoice']);
		//��ȡ�����е��տ�ƻ�����ȡ�����
		$receiptplan = new model_contract_receiptplan_receiptplan();
		$recrows = $object['receiptplan'];
		unset($object['receiptplan']);
		//��ȡ�����е���ѵ�ƻ�����ȡ�����
		$trainingplan = new model_contract_trainingplan_trainingplan();
		$trainrows = $object['trainingplan'];
		unset($object['trainingplan']);

		try {
			$this->start_d();
			$in_id = parent::add_d($object,true);
			//���¸���������ϵ
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
	 * ���ݲ���-��д�༭����-�ݶ�ֻ����δ�ύ�����ĺ�ͬ
	 */
	function edit_d($object){
		try {
			$this->start_d();
			parent::edit_d($object,true);

			//��Ʊ�ƻ�
			$linkman = new model_contract_linkman_linkman();
			$linkman->delectByIdAndNumber($object['id'], $object['contNumber']);
			if(!empty($object['linkman'])){
				$linkrows = $object['linkman'];
				unset($object['linkman']);
				$linkman->batchInsert($object['id'], $object['contNumber'], $object['contName'], $linkrows);
			}

			//��Ʒ�嵥
			$equipmentDao = new model_contract_equipment_equipment();
			$equipmentDao->delectByIdAndNumber($object['id'], $object['contNumber']);
			if(!empty($object['equipment'])){
				$equipmentrows = $object['equipment'];
				unset($object['equipment']);
				$equipmentDao->insertRows($object['id'], $object['contNumber'], $object['contName'],$object['version'], $equipmentrows);
			}
			//������Ϣ
			$licenselistDao = new model_contract_licenselist_licenselist();
			$licenselistDao->delectByIdAndNumber($object['id'], $object['contNumber']);
			if(!empty($object['licenselist'])){
				$licenselistrows = $object['licenselist'];
				unset($object['licenselist']);
				$licenselistDao->batchInsert($object['id'], $object['contNumber'], $object['contName'], $licenselistrows);
			}
			//�Զ����嵥
			$customizelistDao = new model_contract_customizelist_customizelist();
			$customizelistDao->delectByIdAndNumber($object['id'], $object['contNumber']);
			if(!empty($object['customizelist'])){
				$customizelistrows = $object['customizelist'];
				unset($object['customizelist']);
				$customizelistDao->batchInsert($object['id'], $object['contNumber'], $object['contName'], $customizelistrows);
			}
			//��Ʊ�ƻ�
			$invoice = new model_contract_invoice_invoice();
			$invoice->delectByIdAndNumber($object['id'], $object['contNumber']);
			if(!empty($object['invoice'])){
				$invrows = $object['invoice'];
				unset($object['invoice']);
				$invoice->batchInsert($object['id'], $object['contNumber'], $object['contName'], $invrows);
			}
			//�տ�ƻ�
			$receiptplan = new model_contract_receiptplan_receiptplan();
			$receiptplan->delectByIdAndNumber($object['id'], $object['contNumber']);
			if(!empty($object['receiptplan'])){
				$recrows = $object['receiptplan'];
				unset($object['receiptplan']);
				$receiptplan->batchInsert($object['id'], $object['contNumber'], $object['contName'], $recrows);
			}
			//��ѵ�ƻ�
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
	 * ���ݲ���-��غ����±༭
	 */
	function editAgainAfterBack($object){
		$object['ExaStatus'] = WAITAUDIT;
		$object['version'] = $this->getMaxContractVersion($object['oldContId']) + 1;
		$object['isUsing'] = 1;
		$object['changeStatus'] = $object['contStatus'] = 0;


		//��ȡ�����еĿͻ���ϵ�ˣ���ȡ�����
		$linkman = new model_contract_linkman_linkman();
		$linkmanrows = $object['linkman'];
		unset($object['linkman']);

		//��ȡ�����е��豸�嵥����ȡ�����
		$equipment = new model_contract_equipment_equipment();
		$equipmentrows = $object['equipment'];
		unset($object['equipment']);

		//��ȡ�����еļ����б���ȡ�����
		$licenselist = new model_contract_licenselist_licenselist();
		$licenselistrows = $object['licenselist'];
		unset($object['licenselist']);

		//��ȡ�����е��Զ����嵥����ȡ�����
		$customizelist = new model_contract_customizelist_customizelist();
		$customizelistrows = $object['customizelist'];
		unset($object['customizelist']);
		//��ȡ�����еĿ�Ʊ�ƻ�����ȡ�����
		$invoice = new model_contract_invoice_invoice();
		$invrows = $object['invoice'];
		unset($object['invoice']);
		//��ȡ�����е��տ�ƻ�����ȡ�����
		$receiptplan = new model_contract_receiptplan_receiptplan();
		$recrows = $object['receiptplan'];
		unset($object['receiptplan']);
		//��ȡ�����е���ѵ�ƻ�����ȡ�����
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
	 * ���ݲ���-��ͬ���
	 */
	function contractChange($object){
		$object['version'] = $this->getMaxContractVersion($object['change']['oldContId']) + 1;
		$object['isUsing'] = 0;
		$object['changeStatus'] = 0;

		//��ȡ�����еı�����뵥����ȡ�����
		$change = new model_contract_change_change();
		$changerows = $object['change'];
		$changerows['ExaStatus'] = "���������";
//		$changerows['formalNo'] = $object['formalNo'] ;
		$changerows['contNumber'] = $object['contNumber'] ;
		$changerows['version'] = $change->getMaxVersion($changerows['formNumber']) + 1;
		$changerows['isUsing'] = 1;
		unset($object['change']);

		//��ȡ�����еĿͻ���ϵ�ˣ���ȡ�����
		$linkman = new model_contract_linkman_linkman();
		$linkmanrows = $object['linkman'];
		unset($object['linkman']);

		//��ȡ�����е��豸�嵥����ȡ�����
		$equipment = new model_contract_equipment_equipment();
		$equipmentrows = $object['equipment'];
		unset($object['equipment']);

		//��ȡ�����еļ����б���ȡ�����
		$licenselist = new model_contract_licenselist_licenselist();
		$licenselistrows = $object['licenselist'];
		unset($object['licenselist']);

		//��ȡ�����е��Զ����嵥����ȡ�����
		$customizelist = new model_contract_customizelist_customizelist();
		$customizelistrows = $object['customizelist'];
		unset($object['customizelist']);
		//��ȡ�����еĿ�Ʊ�ƻ�����ȡ�����
		$invoice = new model_contract_invoice_invoice();
		$invrows = $object['invoice'];
		unset($object['invoice']);
		//��ȡ�����е��տ�ƻ�����ȡ�����
		$receiptplan = new model_contract_receiptplan_receiptplan();
		$recrows = $object['receiptplan'];
		unset($object['receiptplan']);
		//��ȡ�����е���ѵ�ƻ�����ȡ�����
		$trainingplan = new model_contract_trainingplan_trainingplan();
		$trainrows = $object['trainingplan'];
		unset($object['trainingplan']);
		try {
			$this->start_d();
			$in_id = parent::add_d($object,true);//����ݵĺ�ͬ
			$changerows['newContId'] = $in_id;
			$this->markChange($changerows['oldContId']);//���ʱ��Ǹú�ͬ�������
			$return_id =  $change->add_d($changerows,'true');//������뵥��ID
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
	 * ҳ����ʾ-��ȡ��ͬ����-��ͬ��Ϣ
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
	 * ҳ����ʾ-��ͬ��Ϣװ�شӱ�
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
	 * �༭��ͬʱװ�شӱ�
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
	 * �����ͬʱװ�شӱ�
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
	 * �����ͬʱת���豸��Ϣ
	 */
	function showDetaiInEC($rows){
		$equipment = new model_contract_equipment_equipment();
		$rows['equipment'] = $equipment->showDetailByEC($equipment->showEquListInByEC($rows['id']));
		return $rows;
	}

	/**
	 * �����豸ʱ��ʾ�豸��Ϣ
	 */
	function showDetailInLock($rows){
		$equipment = new model_contract_equipment_equipment();
		$rows['list'] = $equipment->showDetailByLock($equipment->showEquListInByEC($rows['id']));

		return $rows;
	}

	/**
	 * ���ݲ���-���ݺ�ͬ��Ż�ȡ��ͬ������Ϣ
	 */
	function getContractVersion($contNumber){
		$condition = array (
			"contNumber" => $contNumber
		);
		return $this->find($condition);
	}

	/**
	 * ҳ����ʾ-ǩԼ״̬-�Ǳ༭��ҳ��
	 */
	function showSignStatusInfo($value){
		if ($value == 0) {
			$value = "δǩԼ";
		}else if ($value == 1) {
			$value = "��ǩԼ";
		} else if($value== 2){
			$value = "���õ�ֽ�ʺ�ͬ";
		} else if($value== 3){
			$value = "���ύֽ�ʺ�ͬ";
		}else{
			$value = "������ǩ��";
		}
		return $value;
	}


	/**
	 * ҳ����ʾ-�ύ��ʽ-�Ǳ༭��ҳ��
	 */
	function showStartTypeInfo($value){
		if ($value == 0) {
			$value = "֪ͨ����";
		}else if ($value == 1) {
			$value = "������ֱ������";
		}else{
			$value = "��ͬ������";
		}
		return $value;
	}



	/**
	 * ҳ����ʾ-������ʽ-�Ǳ༭��ҳ��
	 */
	function showshipConditionInfo($value){
		if ($value == 0) {
			$value = "�����";
		}else{
			$value = "��������";
		}
		return $value;
	}



	/**
	 * ҳ����ʾ-ǩ��״̬-�༭���ͬҳ����
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
			return "������ǩ��";
		}
		$str=<<<EOT
			<input type="radio" name="sales[signStatus]" value="0" $val1>δǩԼ
	 		<input type="radio" name="sales[signStatus]" value="1" $val2>��ǩԼ
	 		<input type="radio" name="sales[signStatus]" value="2" $val3>���õ�ֽ�ʺ�ͬ
	 		<input type="radio" name="sales[signStatus]" value="3" $val4>���ύֽ�ʺ�ͬ
EOT;
		return $str;
	}


	/**
	 * ҳ����ʾ-�ύ��ʽ-�༭���ͬҳ����
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
			<input type="radio" name="sales[startType]" value="1" $val1>֪ͨ����
	 		<input type="radio" name="sales[startType]" value="2" $val2>������ֱ������
EOT;
		return $str;
	}



	/**
	 * ҳ����ʾ-�ύ��ʽ-�༭���ͬҳ����
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
			<input type="radio" name="sales[shipCondition]" value="1" $val1>�����
	 		<input type="radio" name="sales[shipCondition]" value="2" $val2>��������
EOT;
		return $str;
	}



	/**
	 * ���غ�ͬ״̬�б�
	 */
	function returnContStatus($value){
		switch ($value) {
			case '': return 'δ����';break;
			case '0': return 'δ����';break;
			case '1': return '��ִ��';break;
			case '2': return '���������';break;
			case '3': return '�����';break;
			case '4': return '��عر�';break;
			case '5': return '����ɾ��';break;
			case '6': return '�����ر�';break;
			case '9': return '�ѹر�';break;
			default : return 'δ����';break;
		}
	}

	/**
	 * ��ִ�еĺ�ͬ(�ɣ������豸����)
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
					if($change->isChanging($val['id'])) $val['ExaStatus'] = "�����";
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
					$str .= '<tr align="center"><td colspan="8" align="center">�����������</td></tr>';
				$str .=<<<EOT
						</table><div id="inputDiv$i" title="$i"><����չ�����豸������Ϣ></div>
					</td>
					<td>
						<p>
							<select name="selecter" id="selecter" class="myPrincipal">
				    			<option value="">��ѡ�����</option>
				    			<option value="info">��ͬ��Ϣ</option>
EOT;
			    			if($val['ExaStatus'] != "�����") $str.=<<<EOT
				        		<option value="respon">ָ��������</option>
				    			<option value="executor">ָ��ִ����</option>
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
//<option value="newpurchaseplan">�´�ɹ��ƻ�</option>
		} else {
			$str = '<tr align="center" height="28"><td colspan="8">���������Ϣ</td></tr>';
		}
		return $str;
	}

	/**
	 * ����ID���ǩԼ״̬
	 */
	function returnSignStatus($id){
		$condition = array ("id" => $id );
		return $this->find ( $condition ,'','signStatus,contNumber');
	}

	/**
	 * ����ID��ú�ͬ�źͺ�ͬ����
	 */
	function returnMainMsg($id){
		return $this->find(array('id'=>$id),null,'contNumber,contName');
	}

	/**
	 * ���ݺ�ͬID��ú�ͬ��عر���Ϣ
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
	 * ������ִͬ��
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
	 * ����ͬ-���ʱʹ��-�׳��쳣
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
	 * ���ݲ���-�رպ�ͬ
	 */
	function contractClose($object){
		$object['ExaStatus'] = "�ѹر�";
		$object = $this->addUpdateInfo($object);
		$this->updateById ( $object );
		return true;
	}

	/**
	 * ���ݲ���-�رպ�ͬ���£����ں�ͬ���ڴ�Źر���Ϣ
	 */
	function contractClose2($id){
		$object['id'] = $id;
		$object['contStatus'] = "9";//contStatusΪ 9 ʱ ��ʾ��ͬ���ڹر�״̬
		$object = $this->addUpdateInfo($object);
		try{
			return $this->updateById ( $object );
		}catch(exception $e){
			$this->rollBack();
			throw $e;
		}
	}

//	/**
//	 * ���ݲ���-���ݺ�ͬ��Ż�ȡ��ͬ����
//	 */
//	function getContractByContNumber(){
//		return $this->pageBySqlId('version_list');
//	}

	/**
	 * ���ݲ���-���ݺ�ͬ��Ż�ȡ��ͬ��߰汾
	 */
	function getMaxContractVersion($id){
		$contNumber = $this->find(array('id' => $id),null,'contNumber');
		$max = $this->find(array('contNumber' => $contNumber['contNumber']),'version desc','version');
		return $max['version'];
	}

	/**
	 * ���ݲ���-���ݺ�ͬID��ȡ��ͬ��ǰ�����˺�ִ����
	 */
	function getExecutorAndPrincipal($id){
		$rows = $this->find(array( 'id' => $id),null,'principalId,principalName,executorId,executorName,ExaStatus,contStatus');
		return $rows;
	}

	/**
	 * ���ݲ���-���ݺ�ͬID��عرպ�ͬ
	 * ��״̬�ں�ͬ����غ�ִ�����±༭�������µĺ�ͬ�رվɵĺ�ͬ
	 */
	function setContCloseAfterBack($id){
		$object['id'] = $id;
		$object['isUsing'] = 2;//״̬2�ݶ�Ϊ��عر�
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['contStatus'] = 4;
//		$object['ExaStatus'] = "��عر�";
		return $this->updateById($object);
	}

	/**
	 * ��ɾ������
	 */
	function notDel($id){
		$object['id'] = $id;
		$object['isUsing'] = 3;//״̬3�ݶ�Ϊ��ɾ��ɾ��
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['contStatus'] = 5;
//		$object['ExaStatus'] = "����ɾ��";
		return $this->updateById($object);
	}

	/**
	 * ���ݲ���-���֮��ر�ԭ���汾�ĺ�ͬ
	 */
	function closeAfterChange($id)
	{
		$object['id'] = $id;
		$object['isUsing'] = 4;//״̬4�ݶ�Ϊ�����ر�
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['contStatus'] = 6;
//		$object['ExaStatus'] = "�����ر�";
		return $this->updateById($object);
	}

	/**
	 * ���ݲ���-��ʶ��ͬ�б������
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
	 * ���ݲ���-ȡ����ͬ����ı�ʶ
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
	 * ���ݺ�ͬ���ȷ����ͬ״̬Ϊʹ����
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
	 * ��дedit_d
	 */
	 function infoedit_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		return $this->updateById ( $object );
	}

	/**
	 * ǩ�պ�ͬ
	 */
	function sign_d($id){
		return $this->editBySelf(array('id' => $id ,'signStatus' => '4'));
	}
}
?>
