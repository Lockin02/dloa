<?php


/**
 * @author Liub
 * @Date 2012��3��8�� 10:30:28
 * @version 1.0
 * @description:��ͬ���� Model��
 */
class model_contract_contract_contract extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_contract";
		$this->sql_map = "contract/contract/contractSql.php";
		parent :: __construct();
	}

	/*************************************************************************/
	/**
	 * ���¾ͺ�ͬ�������º�ͬ
	 */
	function updateAdd($object, $conType) {
		try {
			$this->start_d();
			//����������Ϣ
			if (!empty ($object['info'])) {
				$linkArr = array ();
				foreach ($object['info'] as $key => $val) {
					$oldId = $val['oldId'];
					$contractCode = $val['contractCode'];
					$oldContractType = $val['oldContractType'];
					$contractType = $val['contractType'];
					$oldObjcode = $val['objCode'];
					$val['dealStatus'] = 1; //����״̬�������Ϊ1
					//�����ͬ
					$newId = parent :: add_d($val);
					//�����м������
					$sql = "INSERT INTO oa_contract_initialize(contractId,contractCode,contractType,oldContractId,oldContractType,oldObjCode) VALUES ('$newId','$contractCode','$contractType','$oldId','$oldContractType','$oldObjcode')";
					$this->query($sql);

					//����ȷ��������
					$linkdao = new model_contract_contract_contequlink();
					$link = array (
						"contractId" => $newId,
						"rObjCode" => $val['objCode'],
						"contractCode" => $val['contractCode'],
						"contractName" => $val['contractName'],
						"contractType" => $val['contractType'],
						"ExaStatus" => '���',
						"ExaDTOne" => $val['ExaDT'],
						"ExaDT" => $val['ExaDT'],
						"changeTips" => 0,
						"updateTime" => $val['updateTime'],
						"updateId" => $val['updateId'],
						"updateName" => $val['updateName'],
						"createTime" => $val['createTime'],
						"createName" => $val['createName'],
						"createId" => $val['createId']
					);
					$linkArr[$oldId] = $linkdao->create($link); //����linkId
				}
			}
			//����ӱ���Ϣ
			//�ͻ���ϵ��
			if (!empty ($object['linkman'])) {
				foreach ($object['linkman'] as $key => $val) {
					$oldId = $val['oldId'];
					$oldorderId = $val['oldorderId'];
					$tablename = $val['tablename'];
					$linkmanDao = new model_contract_contract_linkman();
					$newId = $linkmanDao->add_d($val);
					//�����м������
					$sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_linkman')";
					$this->query($sql);
				}
				//���´ӱ�������Ĺ�����ϵ
				$this->updateFromToList("oa_contract_linkman", $conType, $tablename);
			}
			//			//����
			if (!empty ($object['orderequ'])) {
				foreach ($object['orderequ'] as $key => $val) {
					$oldId = $val['oldId'];
					$oldorderId = $val['oldorderId'];
					$tablename = $val['tablename'];
					$val['linkId'] = $linkArr[$oldorderId];
					$equDao = new model_contract_contract_equ();
					$newId = $equDao->add_d($val);
					//�����м������
					$sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_equ')";
					$this->query($sql);
				}
				//���´ӱ�������Ĺ�����ϵ
				$this->updateFromToList("oa_contract_equ", $conType, $tablename);
			}
			//			//��Ʊ�ƻ�
			if (!empty ($object['invoice'])) {
				foreach ($object['invoice'] as $key => $val) {
					$oldId = $val['oldId'];
					$oldorderId = $val['oldorderId'];
					$tablename = $val['tablename'];
					$orderInvoiceDao = new model_contract_contract_invoice();
					$newId = $orderInvoiceDao->add_d($val);
					//�����м������
					$sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_invoice')";
					$this->query($sql);
				}
				//���´ӱ�������Ĺ�����ϵ
				$this->updateFromToList("oa_contract_invoice", $conType, $tablename);
			}
			//			//�տ�ƻ�
			if (!empty ($object['receiptplan'])) {
				foreach ($object['receiptplan'] as $key => $val) {
					$oldId = $val['oldId'];
					$oldorderId = $val['oldorderId'];
					$tablename = $val['tablename'];
					$orderReceiptplanDao = new model_contract_contract_receiptplan();
					$newId = $orderReceiptplanDao->add_d($val);
					//�����м������
					$sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_receiptplan')";
					$this->query($sql);
				}
				//���´ӱ�������Ĺ�����ϵ
				$this->updateFromToList("oa_contract_receiptplan", $conType, $tablename);
			}
			//			//��ѵ�ƻ�
			if (!empty ($object['trainingplan'])) {
				foreach ($object['trainingplan'] as $key => $val) {
					$oldId = $val['oldId'];
					$oldorderId = $val['oldorderId'];
					$tablename = $val['tablename'];
					$orderTrainingplanDao = new model_contract_contract_trainingplan();
					$newId = $orderTrainingplanDao->add_d($val);
					//�����м������
					$sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_trainingplan')";
					$this->query($sql);
				}
				//���´ӱ�������Ĺ�����ϵ
				$this->updateFromToList("oa_contract_trainingplan", $conType, $tablename);
			}

			//���� ��ͬ���������ڲ��� ��  ��Ʊ����
			$updateProDeptSql = "update oa_contract_contract  c left join  (
									     select  u.USER_ID,u.DEPT_ID,d.DEPT_NAME from user u left join department d on u.DEPT_ID=d.DEPT_ID
									)de   on c.prinvipalId=de.USER_ID set  c.prinvipalDept=de.DEPT_ID,c.prinvipalDeptId=de.DEPT_NAME";
			$this->query($updateProDeptSql);
			$updateinvoiceSql = "update oa_contract_contract c left join oa_system_datadict d on c.invoiceType=d.dataCode set c.invoiceTypeName=d.dataName";
			$this->query($updateinvoiceSql);
			$this->commit_d();
			//$this->rollBack();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return $e;
		}
	}

	/**
	 * ����������ӱ�Ĺ�����ϵ
	 */
	function updateFromToList($updateTable, $conType, $tablename) {
		$sql = "update " . $updateTable . " e left join (
						  select  f.fromId,f.oldcontractId,i.contractId,i.oldContractType,f.fromType
						  	from oa_contract_initialize_from f left join oa_contract_initialize i on f.oldcontractId=i.oldContractId
						  	   where i.contractType='" . $conType . "'  and fromType='" . $tablename . "'
						) ef on e.id=ef.fromId set e.contractId=ef.contractId  where e.contractId is null";
		$this->query($sql);
	}

}
?>