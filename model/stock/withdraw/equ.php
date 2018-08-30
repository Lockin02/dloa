<?php
/**
 * @author Administrator
 * @Date 2012年11月8日 11:04:46
 * @version 1.0
 * @description:收货通知物料清单 Model层
 */
class model_stock_withdraw_equ extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_withdraw_equ";
		$this->sql_map = "stock/withdraw/equSql.php";
		parent :: __construct();
	}

	/**
	 * 更新物料的质检数量.
	 * @param  $mainId 收料通知单ID
	 * @param  $productId 物料Id
	 * @param  $proNum   质检数量
	 */
	function editQualityInfo($mainId, $equId, $proNum) {
		$sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum+$proNum) where c.id=$equId";
		$this->query($sql);
	}

	/**
	 * 更新物料的收料数量 - 用于质检退回
	 * @param  $mainId   收料通知单ID
	 * @param  $productId   物料Id
	 * @param  $proNum   质检数量
	 */
	function editQualityBackInfo($mainId, $equId,$passNum,$receiveNum) {
		$completionTime = date('Y-m-d H:i:s'); //质检完成时间
		$sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum + $passNum),c.qBackNum=(c.qBackNum+$receiveNum)where  c.id=$equId";
		$this->query($sql);
	}

	/**
	 * 更新物料的质检数量. - 用于质检让步接收
	 * @param  $mainId   收料通知单ID
	 * @param  $productId   物料Id
	 * @param  $proNum   质检数量
	 */
	function editQualityReceiceInfo($mainId, $equId, $proNum, $backNum) {
		$sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum + $proNum),c.qBackNum=(c.qBackNum+$backNum) where  c.id=$equId";
		$this->query($sql);
	}

	/**
	 * 更新物料的质检数量. - 用于质检报告撤销
	 * @param  $mainId   收料通知单ID
	 * @param  $productId   物料Id
	 * @param  $proNum   质检数量
	 */
	function editQualityUnconfirmInfo($mainId, $equId, $proNum) {
		$sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum-$proNum) where c.id=$equId";
		$this->query($sql);
	}

	/**
	 * 更新下达数量
	 */
	function updateExecutedInfo($equId,$proNum){
		$sql = "update $this->tbl_name c set c.executedNum=(c.executedNum+$proNum) where c.id=$equId";
		return $this->query($sql);
	}

    /**
     * 更新下达数量
     */
    function editCompensateNumber($equId,$num){
        $sql = "update $this->tbl_name set compensateNum = compensateNum+$num where id = '$equId'";
        $this->query($sql);
    }
    
    /**
     * 更新物料的质检数量. - 用于质检申请打回
     * @param  $mainId	收料通知单ID
     * @param  $equId	明细Id
     * @param  $proNum	质检数量
     */
    function editQualityInfoAtBack($mainId, $equId, $proNum) {
    	$sql = "update $this->tbl_name c set c.qualityNum=(c.qualityNum-$proNum) where c.id=$equId";
    	$this->query($sql);
    }
}