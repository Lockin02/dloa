<?php

/**
 *
 * 资产采购申请明细model
 * @author fengxw
 *
 */
class model_asset_purchase_apply_applyItem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_purchase_apply_item";
		$this->sql_map = "asset/purchase/apply/applyItemSql.php";
		parent::__construct ();
	}
//
	/**
	 * 过滤下达数量不为null和0的数据
	 */
    function findIssuedAmount(){
        return $this->listBySql ( "select * from oa_asset_purchase_apply_item c where (c.issuedAmount is null or c.issuedAmount=0) " );
    }

	/**
	 * 过滤假删除标志为1的数据
	 */
    function findIsDel($applyID){
        return $this->listBySql ( "select * from oa_asset_purchase_apply_item c where c.applyId=".$applyID." and c.isDel=0" );
    }


	/**
	 * 根据Id，获取采购申请物料
	 *@param $idArr id数组
	 */
	 function getItemByIdArr($idArr){
		$searchArr = array (
			"idArr" => $idArr
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("select_applyItem");
		return $rows;
	 }

	/**
	 * 根据采购申请Id，获取采购申请物料
	 *@param $applyID 采购申请ID
	 *@param $purchDept 采购部门
	 *@param $isDel 假删除标志
	 */
	 function getItemByApplyId($applyID,$purchDept=null,$isDel=null){
		$searchArr = array (
			"applyId" => $applyID,
			"purchDept"=>$purchDept,
			"isDel"=>$isDel
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("select_applyItem");
		return $rows;
	 }

	/**
	 * 根据采购申请Id，获取没有假删除采购申请物料
	 *@param $applyID 采购申请ID
	 *@param $isDel 采购部门
	 */
	 function getDelItemByApplyId($applyID,$isDel=null){
		$searchArr = array (
			"applyId" => $applyID,
			"isDel"=>$isDel
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("select_applyItem");
		return $rows;
	 }


	/**
	 * 根据采购申请Id，获取采购申请物料
	 *@param $applyID 采购申请ID
	 */
	 function getItemByApplyIds($applyID){
		$searchArr = array (
			"applyId" => $applyID
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("select_applyItem");
		return $rows;
	 }

	/**
	 * 根据采购申请Id，获取采购申请物料
	 *@param $applyID 采购申请ID
	 */
	 function getItem_d($applyID){
		$searchArr = array (
			"applyId" => $applyID
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("select_applyItem");
		return $rows;
	 }

	/**
	 * 根据采购申请Id，获取采购申请物料（交付采购）
	 *@param $applyID 采购申请ID
	 */
	 function getPurchItem_d($applyID){
		$searchArr = array (
			"applyId" => $applyID,
			"purchDept"=>'1'
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("select_applyItem");
		return $rows;
	 }

	/**
	 * @exclude 更新采购需求物料下达任务数量
	 */
	function updateAmountIssued($equId,$issuedNum,$lastIssueNum=false){
		if(isset($lastIssueNum)&&$issuedNum==$lastIssueNum){
			return true;
		}else{
			if($lastIssueNum){
				$sql = " update ".$this->tbl_name." set issuedAmount=(ifnull(issuedAmount,0) + $issuedNum - $lastIssueNum) where id=$equId ";
			}else{
				$sql = " update ".$this->tbl_name." set issuedAmount=(ifnull(issuedAmount,0) + $issuedNum) where id=$equId ";
			}
			//echo $sql;
			return $this->query($sql);
		}
	}

	/**
	 * @exclude 更新采购需求物料下达任务数量
	 */
	function updateAmountCheck($equId,$checkNum,$lastCheckNum=false){
		if(isset($lastCheckNum)&&$checkNum==$lastCheckNum){
			return true;
		}else{
			if($lastCheckNum){
				$sql = " update ".$this->tbl_name." set checkAmount=(ifnull(checkAmount,0) + $checkNum - $lastCheckNum) where id=$equId ";
			}else{
				$sql = " update ".$this->tbl_name." set checkAmount=(ifnull(checkAmount,0) + $checkNum) where id=$equId ";
			}
			//echo $sql;
			return $this->query($sql);
		}
	}

	/**
	 * 获取已申请的明细数量
	 */
	function getApplyedNum_d($requireItemId){
		$sql = "select sum(applyAmount) as applyAmount from ".$this->tbl_name." where requireItemId = '$requireItemId' group by requireItemId";
		$rs = $this->_db->getArray($sql);
		if($rs[0]['applyAmount']){
			return $rs[0]['applyAmount'];
		}else{
			return 0;
		}
	}
}
?>
