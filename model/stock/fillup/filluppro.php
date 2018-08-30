<?php
/**
 * @author Administrator
 * @Date 2011年1月17日 13:05:29
 * @version 1.0
 * @description:补库计划产品信息 Model层
 */
class model_stock_fillup_filluppro extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_fillup_detail";
		$this->sql_map = "stock/fillup/fillupproSql.php";
		parent::__construct ();
	}

	/*
	 * 更新补库计划的到货数量
	 */
	function updateProArrNum($id, $arrivalNum) {
		$sql = "update " . $this->tbl_name . " set arrivalNum=arrivalNum+$arrivalNum ,unArrivalNum=unArrivalNum-$arrivalNum where id=$id";
		return $this->query ( $sql );
	}

	/**根据补库单ID，获取其相关的物料信息
	 *author can
	 *2011-3-10
	 */
	function getItemByFillUpId($fillUpId) {
		return $this->findAll ( array ('fillUpId' => $fillUpId ) );
	}

	/**
	 * 更新补库计划的已下达采购数量
	 * @author huangzf
	 */
	function updateIssuedPurNum($id, $issuedPurNum) {
		$sql = "update " . $this->tbl_name . " set issuedPurNum=issuedPurNum+$issuedPurNum  where id=$id";
		return $this->query ( $sql );
	}

		/**
	 * 根据ID获取设备清单
	 */
	function getAllEqu_d($planId){
		$this->searchArr=array("fillUpId"=>$planId);
//		$this->groupBy="c.id";
		$equs = $this->listBySqlId('select_all');
		return $equs;
	}

	/**
	 * 更新已下达采购数量  (变更用)
	 *@author huangzf
	 *
	 */
	function updateAmountIssued($id, $issuedNum, $lastIssueNum = false) {
		if (isset ( $lastIssueNum ) && $issuedNum == $lastIssueNum) {
			return true;
		} else {
			if ($lastIssueNum) {
				$sql = " update " . $this->tbl_name . " set issuedPurNum=(ifnull(issuedPurNum,0)  + $issuedNum - $lastIssueNum) where id='$id'  ";
			} else {
				$sql = " update " . $this->tbl_name . " set issuedPurNum=ifnull(issuedPurNum,0) + $issuedNum where id='$id' ";
			}
			return $this->query ( $sql );
		}
	}
}
?>