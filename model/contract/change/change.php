<?php
/*
 * Created on 2010-7-8
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_contract_change_change extends model_base{

	function __construct() {
		$this->tbl_name = "oa_contract_change";
		$this->sql_map = "contract/change/changeSql.php";
		parent :: __construct();
	}

	/**
	 * ��ʼ������
	 */
	function initChange($id,$type = null){
		$rows = $this->find(array('id' => $id));

		$sales = new model_contract_sales_sales();
		if($type == 'new'){
			$rowsT = $sales->getContractInfo_d($rows['newContId']);
		}else if($type == 'old'){
			$rowsT = $sales->getContractInfo_d($rows['oldContId']);
		}
		$rowsTR = $sales->contractInfo($rowsT);
		$rowsTR['change'] =  $rows;
		$rowsTR['file'] = $sales->getFilesByObjNo($rows['contNumber'],false);
		return $rowsTR;
	}

	/**
	 * ��ʼ������-��������¼�޸ı��
	 */
	function initChangeEdit($id){
		$rows = $this->find(array('id' => $id));

		$sales = new model_contract_sales_sales();
		$rowsT = $sales->getContractInfo_d($rows['newContId']);
		$rows['file']=$sales->getFilesByObjNo($rows['contNumber']);

		$equipDao = new model_contract_equipment_equipment();
		$equipRow = $equipDao->findAll(array('contId' => $rows['oldContId']),null,'amount');

		$rowsTR = $sales->contractInfoForChange($rowsT,$equipRow);
		$rowsTR['change'] =  $rows;

		return $rowsTR;
	}

	/**
	 * ���ݲ���-�༭��������¼�ı�����뵥
	 */
	function editChange($rows){
		$rowsC = $rows['change'];
//		print_r($rows);
		unset($rows['change']);

		$contract = new model_contract_sales_sales();
		try{
			$this->start_d();
			$contract->edit_d($rows);
			$this->edit_d($rowsC);

//			$this->rollBack();
			$this->commit_d();
			return $rowsC['id'];
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���ݲ���-�༭��������¼�ı�����뵥
	 */
	function backEditChange($rows){
		$contract = new model_contract_sales_sales();
		try{
			$this->start_d();
			$newid = $contract->contractChange($rows);
			$temp = $rows['change']['formId'];
			unset($rows['change']['formId']);
			$this->setContCloseAfterBack($temp);
			$this->commit_d();
			return $newid;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���ݲ���-���������ͬ
	 */
	function beginThisChange($id){
		$rows = $this->find(array('id' => $id));
//		print_r($rows);
		try{
			$this->start_d();
			$this->closeAfterChange($id); //�����ı������뵥״̬
			$contract = new model_contract_sales_sales();
			$object = $contract->getExecutorAndPrincipal($rows['oldContId']);//��ȡ�ɺ�ͬ��ִ���˺͸�����
			$contract->closeAfterChange($rows['oldContId']); //�����ı�ɺ�ͬ��״̬
//			echo "<pre>";
//			print_r($object);
			$object['id'] = $rows['newContId'];
			$object['isUsing'] = 1;
			$contract->contractBeginInChange($object);//�����ı��º�ͬ��״̬
//			$this->rollBack();
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���ݲ���-�������ʱ�Ժ�ͬ������һЩ����
	 */
	function closeAfterChange($id)
	{
		$rows = $this->find(array( 'id' => $id));
		$initrows = $rows['newContId'];
		$object['id'] = $id;
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['ExaStatus'] = "������";
		try{
//			$this->start_d();
			$this->updateById($object);
//			$this->commit_d();
		}catch(exception $e){
//			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ���ݲ���-��֤��ǰ��ͬ�Ƿ��б���汾��������
	 */
	function checkExemining($contractId){
		$mark = $this->isChanging($contractId);
		if($mark){
			echo "<script>alert('��ǰ��ͬ����һ�ݱ�����뵥');history.back();</script>'";
		}else {
			return true;
		}
	}

	/**
	 * �������ĺ�ͬ�������
	 */
	function waitExaminingChange(){
		$this->searchArr['workFlowCode']= $this->tbl_name;
		return $this->pageBySqlId ('change_exemine');
	}

	/**
	 * ���ݲ���-�������ı������
	 */
	function examinedChange(){
		$this->searchArr['workFlowCode']= $this->tbl_name;
		return $this->pageBySqlId ('change_exemined');
	}

	/**
	 * ���ݲ���-����ID��عرձ�����뵥
	 * ��״̬�ڱ�����뵥����غ�ִ�����±༭�������µ����뵥�رվɵ����뵥
	 */
	function setContCloseAfterBack($id){
		$object['id'] = $id;
		$object['isUsing'] = 2;//״̬2�ݶ�Ϊ��عر�
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['ExaStatus'] = "��عر�";
		return $this->updateById($object);
	}

	/**
	 * ��ɾ������
	 */
	function notDel($id){
		$object['id'] = $id;
		$rows = $this->find(array('id' => $id),null,'newContId,oldContId');
		$object['isUsing'] = 3;//״̬3�ݶ�Ϊ��ɾ��ɾ��
		$object['updateTime'] = date("Y-m-d H:m:s");
		$object['updateId'] = $_SESSION['USER_ID'];
		$object['updateName'] = $_SESSION['USERNAME'];
		$object['ExaStatus'] = "����ɾ��";

		$contract = new model_contract_sales_sales();
		try{
			$this->start_d();
			//�޸ı�����뵥��Ϣ
			$this->updateById($object);

			//��ɾ���º�ͬͬʱ�޸ľɺ�ͬ�ı��״̬
			$contract->notDel($rows['newContId']);
			$contract->cancelChange($rows['oldContId']);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��ɾ������
	 */
	function deletesT($id){
		$rows = $this->find(array('id' => $id),null,'newContId,oldContId');
		$contract = new model_contract_sales_sales();
		try{
			$this->start_d();
			//ɾ��������뵥
			$this->deletes_d($id);
			//ɾ���º�ͬͬʱ�޸ľɺ�ͬ�ı��״̬
			$contract->deletes_d($rows['newContId']);
			$contract->cancelChange($rows['oldContId']);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ����ID��ȡ������뵥�����߰汾��
	 */
	function getMaxVersion($formNumber){
		$max = $this->find(array('formNumber' => $formNumber),'version desc','version');
		if($max)
			return $max['version'];
		else
			return 0;
	}

	/**
	 * ���ݺ�ͬID�жϺ�ͬ�Ƿ����������
	 */
	function isChange($value,$style=1){
		if($style==1){
			$rows = $this->find(array('newContId' => $value,'ExaStatus' => '������'));
			if($rows){
				return true;
			}else{
				return false;
			}
		}else{
			$rows = $this->find(array('contNumber' => $value,'ExaStatus' => '������'));
			if($rows){
				return true;
			}else{
				return false;
			}
		}
	}

	/**
	 * ���ݺ�ͬID�жϺ�ͬ�Ƿ��ڱ����״̬
	 */
	function isChanging($id){
		$rows = $this->findSql("select 0 from ".$this->tbl_name." where oldContId = '$id' and ExaStatus in ('���������','��������','���','���')");
		if($rows){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * ���ݺ�ͬID��ȡ���ǰ�İ汾��ID
	 */
	function getOldContId($value){
		$rows = $this->find(array('newContId' => $value),'','oldContId');
		return $rows['oldContId'];
	}
}
?>
