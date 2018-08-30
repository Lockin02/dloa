<?php
/**
 * @author Administrator
 * @Date 2012年5月11日 11:41:42
 * @version 1.0
 * @description:资产需求申请明细 Model层
 */
 class model_asset_require_requireitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_requireitem";
		$this->sql_map = "asset/require/requireitemSql.php";
		parent::__construct ();
	}

	/**
	 * 更新物料发货数量
	 * 1条数据对应1个发货数量
	 */
	 function setExeNum($itemIdArr){
	 	if(is_array($itemIdArr) && count($itemIdArr)>0){
		 	foreach( $itemIdArr as $key => $itemId ){
		 		$sql = "
					UPDATE ".$this->tbl_name."
					SET executedNum = executedNum + 1
					WHERE
						id = '".$itemId."'";
				$this->_db->query ( $sql );
		 	}
	 		return true;
	 	}else{
			throw new Exception("单据信息不完整.");
	 		return false;
	 	}
	 }

	/**
	 * 下达采购申请时获取可申请列表
	 */
	function requireJsonApply_d($mainId = null){
		if($mainId){
			$this->searchArr['mainId'] = $mainId;
		}
		$rs = $this->list_d("list_apply");
		if($rs){
			//采购申请明细实例化
			$applyItemDao = new model_asset_purchase_apply_applyItem();
			//可申请数量验证
			$resultArr = array();
			foreach($rs as $key => $val){
				$applyedNum = $applyItemDao->getApplyedNum_d($val['id']);
				if($applyedNum >= $val['applyAmount']){
					unset($rs[$key]);
				}else{
					$rs[$key]['maxAmount'] = $rs[$key]['applyAmount'] = $val['applyAmount'] - $applyedNum;
					array_push($resultArr,$rs[$key]);
				}
			}
		}
		return $resultArr;
	}
	
	/**
	 * 下达物料转资产申请时获取可申请列表
	 */
	function requireinJsonApply_d($mainId = null){
		if($mainId){
			$this->searchArr['mainId'] = $mainId;
		}
		$rs = $this->list_d("list_apply");
		if($rs){
			//物料转资产申请明细实例化
			$requireinitemDao = new model_asset_require_requireinitem();
			//可申请数量验证
			$resultArr = array();
			foreach($rs as $key => $val){
				$applyedNum = $requireinitemDao->getApplyedNum_d($val['id']);
				if($applyedNum >= $val['applyAmount']){
					unset($rs[$key]);
				}else{
					$rs[$key]['maxNum'] = $rs[$key]['number'] = $val['applyAmount'] - $applyedNum;
					array_push($resultArr,$rs[$key]);
				}
			}
		}
		return $resultArr;
	}
	
	/**
	 * 更新资产申请从表采购部门，采购数量信息
	 */
	function updatePurchInfo($obj){
		$sql = 
			"UPDATE ".$this->tbl_name."
			SET purchDept = '".$obj['purchDept']."',
				purchAmount = IFNULL(purchAmount,0)+".$obj['purchAmount']."
			WHERE
				mainId = ".$obj['mainId'].
			" AND productId = ".$obj['productId'];
		$this->_db->query($sql);
	}
 }