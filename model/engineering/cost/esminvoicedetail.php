<?php
/**
 * @author Show
 * @Date 2012年7月31日 20:24:45
 * @version 1.0
 * @description:费用发票明细 Model层
 */
class model_engineering_cost_esminvoicedetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_costdetail_invoicedetail";
		$this->sql_map = "engineering/cost/esminvoicedetailSql.php";
		parent :: __construct();
	}

	/***************** 增删改查 ********************/
	/**
	 * 批量新增
	 */
	function batchAdd_d($object, $costdetailId , $invoiceStatus = null) {
		$object = util_arrayUtil :: setItemMainId('costDetailId', $costdetailId, $object);

		//如果需要修改发票状态，则进行调整
		if($invoiceStatus){
			$object = util_arrayUtil :: setItemMainId('status', $invoiceStatus, $object);
		}

		try {
			$this->start_d();
			//新增
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
	 * 根据费用明细的id更新发票状态
	 */
	function updateCostInvoice_d($detailId,$status){
		$sql = "update ".$this->tbl_name." set status = '$status' where costDetailId in ($detailId)";
		$this->query($sql);
	}

	/**
	 * 获取发票金额
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
	 * 判断发票
	 * @param1 发票记录id
	 * @param2 发票类型id
	 * @param3 所有费用类型id
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