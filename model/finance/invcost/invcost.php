<?php
/**
 * 发票登记model层类
 */
class model_finance_invcost_invcost extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_invcost";
		$this->sql_map = "finance/invcost/invcostSql.php";
		parent :: __construct();
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------
	 *************************************************************************************************/


	/***************************************************************************************************
	 * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
	 *************************************************************************************************/

	/**
	 * 添加费用发票
	 */
	function add_d($invcost) {
		$invcostDetail = null;
		$invcost['objCode'] = 'IC-' . generatorSerial ();
		$invcost['status'] = 'CGFPZT-WSH';
		$invcost['payStatus'] = '未申请';
		if($invcost['invcostDetail']){
			$invcostDetail = $invcost['invcostDetail'] ;
			unset($invcost['invcostDetail']);
		}
		try {
			$this->start_d();
			$invCostId = parent :: add_d($invcost, true);
			if (is_array($invcostDetail)) {
				$detailDao = new model_finance_invcost_invcostDetail();
				$detailDao->createBatch($invcostDetail,array('invCostId' => $invCostId) , 'costName');
			}
			$this->commit_d();
			return $invCostId;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/*
	 * 编辑费用发票
	 */
	function edit_d($invcost) {
		$invcostDetail = null;
		if($invcost['invcostDetail']){
			$invcostDetail = $invcost['invcostDetail'] ;
			unset($invcost['invcostDetail']);
		}
		try {
			$this->start_d();
			$detailDao = new model_finance_invcost_invcostDetail();
			//删除费用发条关联的费用条目
			$detailDao->deleteDetailByInCostId($invcost['id']);
			//添加费用发票
//			echo $invcost['id'];
			if (is_array($invcostDetail)) {
				$detailDao->createBatch($invcostDetail,array('invCostId' => $invcost['id']) , 'costName');
			}
			$invcost = parent :: edit_d($invcost, true);
			$this->commit_d();
			return $invcost;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 原始编辑方法
	 */
	function editEasy_d($rows,$isTrue = false){
		return parent::edit_d($rows,$isTrue);
	}

	/*
	 * 获取发票条目
	 */
	function get_d($id) {
		$detailDao = new model_finance_invcost_invcostDetail();
		$details = $detailDao->getDetailByInvCostId($id);
		$invcost = parent :: get_d($id);
		$invcost['invcostDetail'] = $details;
		return $invcost;
	}

	/**
	 * 费用发票
	 */
	function pageJsonGrid_d(){
		$rows = $this->page_d('easy_list');
		foreach($rows as $key => $val){
			$rows[$key]['listStr'] = $val;
		}
		return $rows;
	}

	/**
	 * 审核
	 */
	function audit_d($id){
		return parent::edit_d(array( 'id' => $id , 'status' => 'CGFPZT-WGJ'),true);
	}

	/**
	 * 反审核
	 */
	function unaudit_d($id){
		return parent::edit_d(array( 'id' => $id , 'status' => 'CGFPZT-WSH'),true);
	}


    /**
     * @desription 根据采购合同Id获取相关信息方法
     */
    function getContractinfoById($purAppId)
    {
        $purchasecontract = new model_purchase_contract_purchasecontract();
        return $purchasecontract->find(array('id'=> $purAppId),null,'id,suppName,suppid,applyNumb,suppAddress');
    }

    function getInvcostByPurconId( $purconId ){
//		$service -> searchArr = array (
//			"purcontId" => $purconId
//			"payStatus" => "未申请"
//			);
		$this-> searchArr['purcontId'] = $purconId;
		$this-> searchArr['payStatus'] = '未申请';
		$rows = $this->page_d ();
		return $rows;
    }
	/**
	*author can
	*2010-12-18
	*/
	/**
	* 获取对象分页列表数组
	*/
	function page_d($sqlId = '') {
		$this->asc = true;
		//$this->echoSelect();
		if (!isset ($this->sql_arr)) {
			return $this->pageBySql("select * from " . $this->tbl_name . " c");
		} else {
			//var_dump($this->pageBySqlId ());
			return $this->pageBySqlId($sqlId);
		}

	}

	/*
	 * 关联付款申请中所关联到的费用发票付款状态
	 */
	function addApplyInvcost($apply) {
		if ($apply ['aboutInvcost']) {
			$aboutInvcostIdArr = explode ( ',', $apply [aboutInvcost] );
			foreach ( $aboutInvcostIdArr as $invcostId ) {
				if($apply['ExaStatus'] != AUDITED ){
					$sql = "update oa_finance_invcost c set c.payStatus = '已申请' where c.id = " . $invcostId;
				}
				else{
					$sql = "update oa_finance_invcost c set c.payStatus = '已付款' where c.id = " . $invcostId;
				}
//				echo $sql;
				$this->query ( $sql );
			}
		}
	}
	/*
	 * 关联付款申请中所关联到的费用发票付款状态
	 */
	function addPaymentInvcost($payment) {
		if ($payment ['aboutInvcost']) {
			$aboutInvcostIdArr = explode ( ',', $payment [aboutInvcost] );
			foreach ( $aboutInvcostIdArr as $invcostId ) {
					$sql = "update oa_finance_invcost c set c.payStatus = '已付款' where c.id = " . $invcostId;
//				echo $sql;
				$this->query ( $sql );
			}
		}
	}

    /**
	 * 费用发票状态变更 (已钩稽)
	 */
	function udInvcost_d(){
		$sql = "update ".$this->tbl_name ." c set c.status = 'CGFPZT-YHS' where c.Status = 'CGFPZT-YGJ'";
		return $this->query($sql);
	}
}
?>