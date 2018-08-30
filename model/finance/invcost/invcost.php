<?php
/**
 * ��Ʊ�Ǽ�model����
 */
class model_finance_invcost_invcost extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_invcost";
		$this->sql_map = "finance/invcost/invcostSql.php";
		parent :: __construct();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------
	 *************************************************************************************************/


	/***************************************************************************************************
	 * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
	 *************************************************************************************************/

	/**
	 * ��ӷ��÷�Ʊ
	 */
	function add_d($invcost) {
		$invcostDetail = null;
		$invcost['objCode'] = 'IC-' . generatorSerial ();
		$invcost['status'] = 'CGFPZT-WSH';
		$invcost['payStatus'] = 'δ����';
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
	 * �༭���÷�Ʊ
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
			//ɾ�����÷��������ķ�����Ŀ
			$detailDao->deleteDetailByInCostId($invcost['id']);
			//��ӷ��÷�Ʊ
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
	 * ԭʼ�༭����
	 */
	function editEasy_d($rows,$isTrue = false){
		return parent::edit_d($rows,$isTrue);
	}

	/*
	 * ��ȡ��Ʊ��Ŀ
	 */
	function get_d($id) {
		$detailDao = new model_finance_invcost_invcostDetail();
		$details = $detailDao->getDetailByInvCostId($id);
		$invcost = parent :: get_d($id);
		$invcost['invcostDetail'] = $details;
		return $invcost;
	}

	/**
	 * ���÷�Ʊ
	 */
	function pageJsonGrid_d(){
		$rows = $this->page_d('easy_list');
		foreach($rows as $key => $val){
			$rows[$key]['listStr'] = $val;
		}
		return $rows;
	}

	/**
	 * ���
	 */
	function audit_d($id){
		return parent::edit_d(array( 'id' => $id , 'status' => 'CGFPZT-WGJ'),true);
	}

	/**
	 * �����
	 */
	function unaudit_d($id){
		return parent::edit_d(array( 'id' => $id , 'status' => 'CGFPZT-WSH'),true);
	}


    /**
     * @desription ���ݲɹ���ͬId��ȡ�����Ϣ����
     */
    function getContractinfoById($purAppId)
    {
        $purchasecontract = new model_purchase_contract_purchasecontract();
        return $purchasecontract->find(array('id'=> $purAppId),null,'id,suppName,suppid,applyNumb,suppAddress');
    }

    function getInvcostByPurconId( $purconId ){
//		$service -> searchArr = array (
//			"purcontId" => $purconId
//			"payStatus" => "δ����"
//			);
		$this-> searchArr['purcontId'] = $purconId;
		$this-> searchArr['payStatus'] = 'δ����';
		$rows = $this->page_d ();
		return $rows;
    }
	/**
	*author can
	*2010-12-18
	*/
	/**
	* ��ȡ�����ҳ�б�����
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
	 * �����������������������ķ��÷�Ʊ����״̬
	 */
	function addApplyInvcost($apply) {
		if ($apply ['aboutInvcost']) {
			$aboutInvcostIdArr = explode ( ',', $apply [aboutInvcost] );
			foreach ( $aboutInvcostIdArr as $invcostId ) {
				if($apply['ExaStatus'] != AUDITED ){
					$sql = "update oa_finance_invcost c set c.payStatus = '������' where c.id = " . $invcostId;
				}
				else{
					$sql = "update oa_finance_invcost c set c.payStatus = '�Ѹ���' where c.id = " . $invcostId;
				}
//				echo $sql;
				$this->query ( $sql );
			}
		}
	}
	/*
	 * �����������������������ķ��÷�Ʊ����״̬
	 */
	function addPaymentInvcost($payment) {
		if ($payment ['aboutInvcost']) {
			$aboutInvcostIdArr = explode ( ',', $payment [aboutInvcost] );
			foreach ( $aboutInvcostIdArr as $invcostId ) {
					$sql = "update oa_finance_invcost c set c.payStatus = '�Ѹ���' where c.id = " . $invcostId;
//				echo $sql;
				$this->query ( $sql );
			}
		}
	}

    /**
	 * ���÷�Ʊ״̬��� (�ѹ���)
	 */
	function udInvcost_d(){
		$sql = "update ".$this->tbl_name ." c set c.status = 'CGFPZT-YHS' where c.Status = 'CGFPZT-YGJ'";
		return $this->query($sql);
	}
}
?>