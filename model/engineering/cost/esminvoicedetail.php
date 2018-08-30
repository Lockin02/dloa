<?php
/**
 * @author Show
 * @Date 2012��7��31�� 20:24:45
 * @version 1.0
 * @description:���÷�Ʊ��ϸ Model��
 */
class model_engineering_cost_esminvoicedetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_costdetail_invoicedetail";
		$this->sql_map = "engineering/cost/esminvoicedetailSql.php";
		parent :: __construct();
	}

	/***************** ��ɾ�Ĳ� ********************/
	/**
	 * ��������
	 */
	function batchAdd_d($object, $costdetailId , $invoiceStatus = null) {
		$object = util_arrayUtil :: setItemMainId('costDetailId', $costdetailId, $object);

		//�����Ҫ�޸ķ�Ʊ״̬������е���
		if($invoiceStatus){
			$object = util_arrayUtil :: setItemMainId('status', $invoiceStatus, $object);
		}

		try {
			$this->start_d();
			//����
			$this->saveDelBatch($object);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			throw $e;
			return false;
		}
	}

	/**
	 * ���ݷ�����ϸ��id���·�Ʊ״̬
	 */
	function updateCostInvoice_d($detailId,$status){
		$sql = "update ".$this->tbl_name." set status = '$status' where costDetailId in ($detailId)";
		$this->query($sql);
	}

	/**
	 * ��ȡ��Ʊ���
	 */
	function getInvoice_d($detailId,$status = 1){
		$this->searchArr = array(
			'costDetailIds' => $detailId,
			'status' => $status
		);
		$this->groupBy = 'c.invoiceTypeId';
		$rs = $this->list_d('count_costinvoice');
		if($rs){
			return $rs;
		}else{
			return false;
		}
	}

	/**
	 * �жϷ�Ʊ
	 * @param1 ��Ʊ��¼id
	 * @param2 ��Ʊ����id
	 * @param3 ���з�������id
	 */
	function checkInvoiceExist_d($id,$invoiceTypeId,$allcostdetailId){
		$this->searchArr = array(
			'costDetailIds' => $allcostdetailId,
			'invoiceTypeId' => $invoiceTypeId
		);
		$rs = $this->list_d();
		if($rs){
			return true;
		}else{
			return false;
		}
	}
}
?>