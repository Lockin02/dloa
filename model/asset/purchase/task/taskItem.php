<?php

/**
 *
 * 资产采购任务明细model
 * @author fengxw
 *
 */
class model_asset_purchase_task_taskItem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_purchase_task_item";
		$this->sql_map = "asset/purchase/task/taskItemSql.php";
		parent::__construct ();
	}

	/**
	 * 根据采购任务Id，获取采购申请物料
	 *@param $parentId 采购任务ID
	 */
	 function getItemByParent_d($parentId){
		$searchArr = array (
			"parentId" => $parentId
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId();
		return $rows;
	 }

	/**
	 * @exclude 更新采购任务物料下达订单数量
	 */
	function updateIssuedAmount($equId,$issuedAmount,$lastCheckNum=false){
		if(isset($lastCheckNum)&&$issuedAmount==$lastCheckNum){
			return true;
		}else{
			if($lastCheckNum){
				$sql = " update ".$this->tbl_name." set issuedAmount=(ifnull(issuedAmount,0) + $issuedAmount - $lastCheckNum) where id=$equId ";
			}else{
				$sql = " update ".$this->tbl_name." set issuedAmount=(ifnull(issuedAmount,0) + $issuedAmount) where id=$equId ";
			}
			//echo $sql;
			return $this->query($sql);
		}
	}

}
?>
