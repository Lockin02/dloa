<?php
/**
 * @author Show
 * @Date 2013年7月5日 星期五 14:59:59
 * @version 1.0
 * @description:其他发票费用分摊 Model层
 */
class model_finance_invother_invcostdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_invother_cost";
		$this->sql_map = "finance/invother/invcostdetailSql.php";
		parent :: __construct();
	}

	/**
	 * 获取费用
	 */
	function getPayCost_d($sourceCode,$sourceType){
		$sql = "select
			c.id ,c.id as payCostId,c.payapplyId ,c.payapplyCode ,c.shareTypeName ,c.shareType ,c.shareObjName,c.shareObjCode,
			c.shareObjId,c.deptName ,c.deptId ,c.userName ,c.userId ,c.projectName ,c.projectCode ,
			c.projectId ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,
			c.updateTime ,c.sysCompanyName ,c.sysCompanyId,c.shareMoney,c.feeType,c.feeTypeId
		from oa_finance_payablesapply_cost c
		where
			c.payapplyId in (select id from oa_finance_payablesapply where sourceType = 'YFRK-02' and sourceCode = '$sourceCode')";
		$rs = $this->_db->getArray($sql);

		//如果查询到数据
		$newArr = array();
		if($rs){
			foreach($rs as $key => $val){
				$sharedMoney = $this->getPayCostCanUse_d($val['id']);
				$rs[$key]['shareMoney'] = bcsub($val['shareMoney'],$sharedMoney,2);
				if((float)$rs[$key]['shareMoney']){
					array_push($newArr,$rs[$key]);
				}
			}
		}
		return $newArr;
	}

	//查询相关分摊明细
	function getPayCostCanUse_d($payCostId){
		$this->searchArr = array(
			'payCostId' => $payCostId
		);
		$rs = $this->list_d('count_list');
		if($rs[0]['shareMoney']){
			return $rs[0]['shareMoney'];
		}else{
			return 0;
		}
	}

	/**
	 * 根据其他合同id获取分摊费用
	 */
	function getListViewCost_d($otherId){
		//先获取对应其他合同下的费用分摊
		$invotherdetailDao = new model_finance_invother_invotherdetail();
		$invotherdetailArr = $invotherdetailDao->findAll(array('objId' => $otherId,'objType' => 'YFQTYD02'),null,'mainId');
		$datas = array();
		if($invotherdetailArr){
			$mainIdArr = array();
			foreach($invotherdetailArr as $val){
				if(!in_array($val['mainId'],$mainIdArr)){
					array_push($mainIdArr,$val['mainId']);
				}
			}
			$this->searchArr = array('invotherIdArr' => implode(',',$mainIdArr));
			$this->groupBy = 'c.shareType,c.shareObjName,c.feeType';
			$datas = $this->list_d('count_group');
		}
		return $datas;
	}
}
?>