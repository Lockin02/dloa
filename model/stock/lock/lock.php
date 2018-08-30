<?php
/**
 * @author chengl
 * @description:�ֿ�������¼�� Model��
 */
class model_stock_lock_lock extends model_base {

	static $defLockType = "otherLock"; //Ĭ�ϵ���������(Ĭ��Ϊ�ֹ���������ǰĬ���ǲֿ��������޸���zengzx)
	static $defObjType = "oa_sale_order"; //Ĭ������������ҵ�����
	static $lockTypeArr = array (
		"stockLock" => "�������",
		"otherLock" => "�ֹ�����",
		"purchaseLock" => "�ɹ�����",
		"productionLock" => "��������",
		"instockLock" => "�������",
		"outstockLock" => "��������",
		"allocationLock" => "��������" );
	static $objTypeArr = array (
		"oa_contract_contract"=> "��ͬ����",
		"oa_borrow_borrow" => "��������",
		"oa_present_present" => "��������" );

	function __construct() {
		$this->tbl_name = "oa_stock_lock";
		$this->sql_map = "stock/lock/lockSql.php";
		parent::__construct ();
		$this->docArr = array (//��ͬ���ͳ������������,������Ҫ���������׷��
			"oa_contract_contract" => "contract_contract_contract", //��ͬ����
			"oa_borrow_borrow" => "projectmanagent_borrow_borrow", //���÷���
			"oa_present_present" => "projectmanagent_present_present" ); //���ͷ���
		$this->docModelArr = array ();
		foreach ( $this->docArr as $key => $val ) {
			$this->docModelArr [$key] = "model_" . $val;
		}
	}

	/**
	 * ��ȡ�������ͣ����û�����������ȡĬ�ϵ���������
	 * @param  $lockType
	 */
	function getLockType($lockType) {
		if (empty ( $lockType )) {
			return self::$defLockType;
		}
		return $lockType;
	}

	/**
	 * ��ȡ����������ҵ��������û�����������ȡĬ�Ϲ�����ҵ�����
	 * @param unknown_type $objType
	 */
	function getObjType($objType) {
		if (empty ( $objType )) {
			return self::$defObjType;
		}
		return $objType;
	}

	/**
	 * ��ʾ������¼
	 */
	function showLock($rows) {
		$str = null;
		if ($rows) {
			$i = 0;
			foreach ( $rows as $key => $val ) {
				$i ++;
				$str .= <<<EOT
					<tr>
						<td>
						$i
						</td>
						<td>
							$val[productNo]
						</td>
						<td>
							$val[productName]
						</td>
						<td>
							$val[stockName]
						</td>
						<td>
							$val[lockNum]
						</td>
						<td>
							$val[updateName]
						</td>
						<td>
						    $val[createTime]
						</td>
					</tr>
EOT;
			}
		} else {
			return '<tr><td colspan="6">--------���������Ϣ--------</td></tr>';
		}
		return $str;
	}

	/**
	 * �����������ͳһ�ӿ�
	 */
	function batchLock_d($rows, $lockType = '') {
		try {
			$this->start_d ();
			foreach ( $rows as $val ) {
				if (is_array ( $val ) && $val ['lockNum'] != 0) {
					$val ['lockType'] = $this->getLockType ( $lockType );
					$val ['stockId'] = $rows ['stockId'];
					$val ['stockName'] = $rows ['stockName'];
					$val ['objType'] = $this->getObjType ( $val ['objType'] );
					if (empty ( $val ['objCode'] )) {
						$val ['objCode'] = $rows ['objCode'];
					}
					$this->add_d ( $val, true );
				}
			}
			$this->commit_d ();
			return true;
		} catch ( exception $e ) {
			$this->rollBack ();
			throw $e;
			return false;
		}
	}

	/**
	 * �������
	 * $objArr:Դ���������� �����ۺ�ͬ ��һ��ΪobjTypeԴ������,�ڶ���ΪobjIdԴ��id,������ΪobjCodeԴ�����,���ĸ�Ϊҵ�����(��)�����ĸ�ΪrelDocIdԴ��id(��)
	 * $proArr:������������ ��һ��productIdΪ����id,�ڶ���Ϊ���ϱ���productCode,������Ϊ��������productName,���ĸ���������lockNum,�������ⵥid inStockDocId
	 * $stockArr:�ֿ����� ��һ��Ϊ�ֿ����stockId,�ڶ���Ϊ�ֿ�����stockName
	 */
	function stockinLock_d($objArr, $proArr, $stockArr) {
		if (empty ( $objArr )) {
			throw new Exception ( "Դ����������Ϊ��." );
		}
		if (empty ( $proArr )) {
			throw new Exception ( "������������Ϊ��." );
		}
		if (empty ( $stockArr )) {
			throw new Exception ( "�ֿ�����Ϊ��." );
		}

		$inventoryinfoDao = new model_stock_inventoryinfo_inventoryinfo ();
		$inventoryinfo = $inventoryinfoDao->getInventoryInfoByStockAndProduct ( $stockArr ['stockId'], $proArr ['productId'] );
		$inventoryId = $inventoryinfo ['id'];

		//����Դ��model��ȡԴ����Ϣ
		$docModel = $this->docModelArr [$objArr ['applyType']];
		if (empty ( $docModel )) {
			//			throw new Exception("Դ��û��ע��.");
			return;
		}
		$docDao = new $docModel ();
		$rows = $docDao->get_d ( $objArr ['applyId'] );
		if (empty ( $rows )) {
			//			throw new Exception("Դ��û�д˶���.");
			return;
		}
		$souceDao = new model_common_contract_allsource ();
// 		$details = $souceDao->getProEqus ( $objArr ['applyId'], $objArr ['applyType'], $proArr ['productId'] );
		$details = $souceDao->getProEqusNew ( $objArr ['relDocId'], $objArr ['applyType'] );
		$lockNum = $proArr ['lockNum']; //�ƻ���������
		if(!empty($details)){
			foreach ( $details as $k => $equ ) {
				$equId = $equ ['id'];
				$lockedNum = $this->getEquStockLockNum ( $equId, $stockArr ['stockId'], $objArr ['applyType'] );
				//$lockedNum=$equ['lockNum'];
				//��ȡ�豸������������,�����ͬ�豸�ϵ�δִ�����������������������������������
				$noOutStockNum=$equ ['number']-$equ['executedNum'];
				if ($noOutStockNum > $lockedNum) {
					$n = $noOutStockNum - $lockedNum; //δ����������
					if ($n > $lockNum) { //����ƻ�����������С�ڻ�δ����������ֻҪ����һ��
						$this->lockSingle ( $objArr, $proArr, $stockArr, $equId, $inventoryId );
						$inventoryinfoDao->lockExeNum ( $inventoryId, $lockNum,$equ['productName'] );
						break;
					} else { //����ƻ���������������δ������������ȫ��������ѭ���嵥����û�е���
						$proArr ['lockNum'] = $n;
						$this->lockSingle ( $objArr, $proArr, $stockArr, $equId, $inventoryId );
						$inventoryinfoDao->lockExeNum ( $inventoryId, $n,$equ['productName'] );
					}
				} else { //�����ͬ���豸����С�ڻ��ߵ�������������ʲôҲ����
			
				}
			}
		}
	}

	/**
	 * ��������
	 */
	function lockSingle($objArr, $proArr, $stockArr, $equId, $inventoryId) {
		$lockArr = array ("stockName" => $stockArr ['stockName'],"stockCode" => $stockArr ['stockCode'], "stockId" => $stockArr ['stockId'], "inventoryId" => $inventoryId, "lockNum" => $proArr ['lockNum'], "lockType" => 'instockLock', "productNo" => $proArr ['productCode'], "productId" => $proArr ['productId'], "productName" => $proArr ['productName'], "objCode" => $objArr ['applyCode'], "objId" => $objArr ['applyId'], "objType" => $objArr ['applyType'], "objEquId" => $equId, "inStockDocId" => $proArr ['inStockDocId'] );
		//print_r($lockArr);
		$this->add_d ( $lockArr, true );
	}

	/**
	 * ��������ʱ���ڷ������ⵥ��
	 */
//	function relateLock(){
//
//	}

	/**
	 * �ֹ��������
	 */
	function stockLock_d($rows, $lockType = '') {
		try {
			$this->start_d ();
			$this->batchLock_d ( $rows, $lockType );
			$inventoryInfoDao = new model_stock_inventoryinfo_inventoryinfo ();
			foreach ( $rows as $key => $val ) {
				if (is_array ( $val ) && $val ['lockNum'] != 0) {
					//���¿��Ŀ�ִ������������
					$inventoryInfoDao->lockExeNum ( $val ['inventoryId'], $val ['lockNum'] );
				}
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
			return false;
		}
	}

	/**
	 * ͨ���������Զ��ͷŸ��˽�������������
	 */
	function releaseLockByPersonBorrow($rows) {
		$lockNum = $rows ['lockNum'];
		$borrowDao = new model_projectmanagent_borrow_borrow ();
		//���ݴ���ķ����ƻ�id����Դ���ż�Դ������
		$borrowObj = $borrowDao->get_d ( $rows ['borrowId'] );
		$borrowObj['docId']=$borrowObj['id'];
		$borrowObj['docCode']=$borrowObj['Code'];
		$borrowObj['docType']='oa_borrow_borrow';
		$borrowObj['rObjCode']=$borrowObj['objCode'];
		$rows['lockType']="allocationLock";
		$this->releaseLockByEqu($rows,$borrowObj,$lockNum);
	}

	/**
	 * ͨ�������ƻ�����ʱ���Զ��ͷ��������������
	 */
	function releaseLockByOutPlan($rows) {
		//$lockNum = $rows['lockNum']*(-1);
		$lockNum = $rows ['lockNum'];
		$outplanDao = new model_stock_outplan_outplan ();
		//���ݴ���ķ����ƻ�id����Դ���ż�Դ������
		$planObj = $outplanDao->get_d ( $rows ['planId'] );
		$rows['lockType']="outstockLock";
		$this->releaseLockByEqu($rows,$planObj,$lockNum);
	}

	/**
	 * �������ⵥ ���Զ��ͷ��������������
	 */
	function releaseLockByCancelAudit($objArr, $proArr, $stockArr){
		//print_r($objArr);
		$rows=array("stockId"=>$stockArr['stockId'],"productId"=>$proArr['productId'],"lockType"=>"instockLock");
		$sourceObj=array("docId"=>$objArr['applyId'],"docType"=>$objArr['applyType'],"docCode"=>$objArr['applyCode'],"rObjCode"=>$objArr['rObjCode']);

		return $this->releaseLockByEqu($rows,$sourceObj,$proArr ['lockNum']);
	}

	/**
	 * ͳһ��������
	 */
	function releaseLockByEqu($rows,$sourceObj,$lockNum){
		try {
			$this->start_d ();
			//print_r($sourceObj);
			//����Դ���š�Դ�����͡��ֿ�id����Ʒid��Ϊһ����¼�����Ҽ�¼��Ϣ
			$this->searchArr = array (
				'objId' => $sourceObj ['docId'],
				'objType' => $sourceObj ['docType'],
				'stockId' => $rows ['stockId'],
				'productId' => $rows ['productId']
			 );
			//print_r($this->searchArr);
			//�ҵ����������е�������¼
			$this->groupBy="c.objEquId,c.stockId,c.stockName,c.inventoryId,c.productId,c.productName,c.productNo";
			$sql = " select sum(c.lockNum) as lockNum,c.objEquId,c.stockId,c.stockName,c.inventoryId,c.productId,c.productName,c.productNo from oa_stock_lock c where 1=1 ";
			$lockObjs = $this->listBySql ( $sql );
			$lockArr = array ();
			//var_dump($lockObjs);
			if(!empty($lockObjs)){
				foreach($lockObjs as $key =>$lockObj){
					//$lockObj=$lockObjs[0];
					if($lockObj ['lockNum']>0){
						//����������������豸��������,��ǰ��������Ϊ�豸��������
						$isBreak=false;
						$relaseNum = $lockNum;
						if ($lockNum > $lockObj ['lockNum']) {
							$relaseNum = $lockObj ['lockNum'];
							$lockNum = $lockNum - $lockObj ['lockNum'];
						}else{
							$isBreak=true;
						}
						//ƴװ�������顣
						$lockArr = array (
							'objCode' => $sourceObj ['docCode'],
							'stockId' => $lockObj ['stockId'],
							 'stockName' => $lockObj ['stockName'],
							 'objType' => $sourceObj ['docType'] );
						$lockItemArr = array (
							'objId' => $sourceObj ['docId'],
							'rObjCode' => $sourceObj ['rObjCode'],//����ҵ����
							'inventoryId' => $lockObj ['inventoryId'],
							'objType' => $sourceObj ['docType'],
							'productId' => $rows ['productId'],
							'productName' => $lockObj ['productName'],
							'productNo' => $lockObj ['productNo'],
							'objEquId' => $lockObj ['objEquId'],
							'lockNum' => $relaseNum * - 1,
							'outStockDocId' => $rows ['outDocId'],
							'inStockDocId' => $rows ['inStockDocId'] );
						array_push ( $lockArr, $lockItemArr );
						if(empty($rows['lockType'])){
							$rows['lockType']='stockLock';
						}
						//print_r($lockArr);
						$this->stockLock_d ( $lockArr, $rows['lockType'] );
						if($isBreak){
							break;
						}
					}
				}
			}
			$this->commit_d ();
		}catch(Exception $e){
			$this->rollBack ();
			throw $e;
		}
	}


	/**
	 * ���ݱ�ź����ͻ�ȡ������¼
	 */
	function getDataByObjCode_d($objCode, $objType, $productId = null) {
		$objType = $this->getObjType ( $objType );
		if ($productId) {
			return $this->findAll ( array ('objCode' => $objCode, 'objType' => $objType, 'productId' => $productId ) );
		} else {
			return $this->findAll ( array ('objCode' => $objCode, 'objType' => $objType ) );
		}
	}

	/**
	 * ��ȡĳ���豸��������¼
	 */
	function lockRecordsByEquId($equId, $stockId = "") {
		//$lockType = $this->getLockType ( $lockType );
		$searchArr = array ();
		$searchArr = array ("objEquId" => $equId );

		if (! empty ( $stockId )) {
			$searchArr ['stockId'] = $stockId;
		}
		$this->searchArr = $searchArr;
		return $this->page_d ();
	}
	/**
	 * ��ȡĳ��ҵ������������¼
	 */
	function lockRecordsByobjCode($objCode, $objType = "") {
		$objType = $this->getObjType ( $objType );
		$searchArr = array ("objCode" => $objCode, 'objType' => $objType );
		$this->searchArr = $searchArr;
		return $this->page_d ();
	}

	/*
	 * ��ȡ�����豸��ĳ���ֿ��µ��Ѿ�������������(�ֿ�id���������ȡ����)
	 */
	function getEquStockLockNum($objEquId, $stockId = "", $docType) {
		//		$sql = "select sum(c.lockNum) as totalNum from " . $this->tbl_name . " c where c.objEquId='$objEquId' and c.productId=$productId group by c.productId";
		//		$lockNumArr = $this->findSql ( $sql );
		//		return $lockNumArr [0] ['totalNum'];
		$lockNumArr = $this->getEqusStockLockNumArr ( array ($objEquId ), $stockId, $docType );
		return $lockNumArr [0] ['totalNum'];
	}

	/*
	 * ��ȡ�豸��ĳ���ֿ��µ��Ѿ�������������(�ֿ�id���������ȡ����)
	 */
	function getEqusStockLockNumArr($objEquIdArr, $stockId = "", $docType) {
		$plusSql = "";
		if (is_array ( $objEquIdArr ) && count ( $objEquIdArr ) > 0) {
			foreach ( $objEquIdArr as $v ) {

				$val .= "'" . $v . "',";
			}
			$val = substr ( $val, 0, - 1 );
			$plusSql .= " and c.objEquId in (" . $val . ")";
		}
		if (! empty ( $docType )) {
			$typePlusSql .= " and c.objType='" . $docType . "'";
		}
		if (! empty ( $stockId )) {
			$plusSql .= " and stockId=" . $stockId;
		}

		$sql = "select c.objEquId,sum(c.lockNum) as totalNum from " . $this->tbl_name . " c where 1=1  $plusSql " . $typePlusSql . " group by c.objEquId";

		$lockNumArr = $this->findSql ( $sql );
		return $lockNumArr;
	}

	/**
	 * ��ȡ������¼
	 * @param unknown_type $param
	 */
	function getPageLockLog() {
		$saleStock1 = '��ͬ����';
//		$saleStock2 = '���񷢻�';
//		$saleStock3 = '���޷���';
//		$saleStock4 = '�з�����';
		$saleStock5 = '���÷���';
		$saleStock5 = '���ͷ���';
		//		$sql = "select id,  case   objType
		//			when 'oa_sale_order' then (select orderTempCode from oa_sale_order where id=objId)
		//			when 'oa_sale_service' then (select orderTempCode from oa_sale_service where id=objId)
		//			when 'oa_sale_lease' then (select orderTempCode from oa_sale_lease where id=objId)
		//			when 'oa_sale_rdproject' then (select orderTempCode from oa_sale_rdproject where id=objId)
		//		else objCode end as objCode,
		$sql = "select id,
 		case objType
				when 'oa_contract_contract' then '$saleStock1'
				when 'oa_borrow_borrow' then '$saleStock5'
				when 'oa_present_present' then '$saleStock6'
		else objType end as objTypeTest,
		objCode,
		objId,
		objType,
		stockId,
		stockName,
		productId,
		productNo,
		productName,
		objEquId,
		inventoryId,
		sum(lockNum) as lockNum
			from oa_stock_lock c where 1=1 ";

		$sql = $this->createQuery ( $sql, $this->searchArr );
		$sql .= " group by objType,objId, stockId,productId ";
		if (! isset ( $this->searchArr ['showAll'] )) {
			$sql .= "having lockNum!=0";
		}
		//������ȡ��¼��
		$sql .= " limit " . $this->start . "," . $this->pageSize;
		//echo $sql;
		return $this->_db->getArray ( $sql );
	}

	/**
	 *
	 * ����������������,��������id,��Ʒid,�ֿ�id��ȡ ����������
	 * @param  $objType
	 * @param  $objId
	 * @param  $productId
	 * @param  $stockId
	 */
	function subLockNumAtOut($objType, $objId, $productId, $stockId) {
		$this->searchArr = array ("objType" => $objType, "objId" => $objId, "productId" => $productId, "stockId" => $stockId );
		$this->groupBy = "  c.objType,c.objId,c.productId,c.stockId ";
		$resultArr = $this->listBySqlId ( "sub_locknum" );
		return $resultArr [0];
	}

}
?>