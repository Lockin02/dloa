<?php
/**
 * @author chengl
 * @description:仓库锁定记录表 Model层
 */
class model_stock_lock_lock extends model_base {

	static $defLockType = "otherLock"; //默认的锁定类型(默认为手工锁定，以前默认是仓库锁定：修改人zengzx)
	static $defObjType = "oa_sale_order"; //默认锁定关联的业务对象
	static $lockTypeArr = array (
		"stockLock" => "出库解锁",
		"otherLock" => "手工锁定",
		"purchaseLock" => "采购锁定",
		"productionLock" => "生产锁定",
		"instockLock" => "入库锁定",
		"outstockLock" => "出库锁定",
		"allocationLock" => "调拨锁定" );
	static $objTypeArr = array (
		"oa_contract_contract"=> "合同锁定",
		"oa_borrow_borrow" => "借用锁定",
		"oa_present_present" => "赠送锁定" );

	function __construct() {
		$this->tbl_name = "oa_stock_lock";
		$this->sql_map = "stock/lock/lockSql.php";
		parent::__construct ();
		$this->docArr = array (//不同类型出库申请策略类,根据需要在这里进行追加
			"oa_contract_contract" => "contract_contract_contract", //合同出库
			"oa_borrow_borrow" => "projectmanagent_borrow_borrow", //借用发货
			"oa_present_present" => "projectmanagent_present_present" ); //赠送发货
		$this->docModelArr = array ();
		foreach ( $this->docArr as $key => $val ) {
			$this->docModelArr [$key] = "model_" . $val;
		}
	}

	/**
	 * 获取锁定类型，如果没传入参数，获取默认的锁定类型
	 * @param  $lockType
	 */
	function getLockType($lockType) {
		if (empty ( $lockType )) {
			return self::$defLockType;
		}
		return $lockType;
	}

	/**
	 * 获取锁定关联的业务对象，如果没传入参数，获取默认关联的业务对象
	 * @param unknown_type $objType
	 */
	function getObjType($objType) {
		if (empty ( $objType )) {
			return self::$defObjType;
		}
		return $objType;
	}

	/**
	 * 显示锁定记录
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
			return '<tr><td colspan="6">--------暂无相关信息--------</td></tr>';
		}
		return $str;
	}

	/**
	 * 批量锁定库存统一接口
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
	 * 入库锁定
	 * $objArr:源单类型数组 如销售合同 第一个为objType源单类型,第二个为objId源单id,第三个为objCode源单编号,第四个为业务编码(新)，第四个为relDocId源单id(新)
	 * $proArr:锁定物料数组 第一个productId为物料id,第二个为物料编码productCode,第三个为物料名称productName,第四个锁定数量lockNum,第五个入库单id inStockDocId
	 * $stockArr:仓库数组 第一个为仓库编码stockId,第二个为仓库名称stockName
	 */
	function stockinLock_d($objArr, $proArr, $stockArr) {
		if (empty ( $objArr )) {
			throw new Exception ( "源单类型数组为空." );
		}
		if (empty ( $proArr )) {
			throw new Exception ( "锁定物料数组为空." );
		}
		if (empty ( $stockArr )) {
			throw new Exception ( "仓库数组为空." );
		}

		$inventoryinfoDao = new model_stock_inventoryinfo_inventoryinfo ();
		$inventoryinfo = $inventoryinfoDao->getInventoryInfoByStockAndProduct ( $stockArr ['stockId'], $proArr ['productId'] );
		$inventoryId = $inventoryinfo ['id'];

		//调用源单model获取源单信息
		$docModel = $this->docModelArr [$objArr ['applyType']];
		if (empty ( $docModel )) {
			//			throw new Exception("源单没有注册.");
			return;
		}
		$docDao = new $docModel ();
		$rows = $docDao->get_d ( $objArr ['applyId'] );
		if (empty ( $rows )) {
			//			throw new Exception("源单没有此对象.");
			return;
		}
		$souceDao = new model_common_contract_allsource ();
// 		$details = $souceDao->getProEqus ( $objArr ['applyId'], $objArr ['applyType'], $proArr ['productId'] );
		$details = $souceDao->getProEqusNew ( $objArr ['relDocId'], $objArr ['applyType'] );
		$lockNum = $proArr ['lockNum']; //计划锁定数量
		if(!empty($details)){
			foreach ( $details as $k => $equ ) {
				$equId = $equ ['id'];
				$lockedNum = $this->getEquStockLockNum ( $equId, $stockArr ['stockId'], $objArr ['applyType'] );
				//$lockedNum=$equ['lockNum'];
				//获取设备已锁定的数量,如果合同设备上的未执行数量大于已锁定数量，则进行锁定操作
				$noOutStockNum=$equ ['number']-$equ['executedNum'];
				if ($noOutStockNum > $lockedNum) {
					$n = $noOutStockNum - $lockedNum; //未锁定的数量
					if ($n > $lockNum) { //如果计划锁定的数量小于还未锁定数量，只要锁定一次
						$this->lockSingle ( $objArr, $proArr, $stockArr, $equId, $inventoryId );
						$inventoryinfoDao->lockExeNum ( $inventoryId, $lockNum,$equ['productName'] );
						break;
					} else { //如果计划锁定的数量大于未锁定数量，则全部锁，再循环清单看有没有的锁
						$proArr ['lockNum'] = $n;
						$this->lockSingle ( $objArr, $proArr, $stockArr, $equId, $inventoryId );
						$inventoryinfoDao->lockExeNum ( $inventoryId, $n,$equ['productName'] );
					}
				} else { //如果合同上设备数量小于或者等于锁定数量则什么也不做
			
				}
			}
		}
	}

	/**
	 * 单条锁定
	 */
	function lockSingle($objArr, $proArr, $stockArr, $equId, $inventoryId) {
		$lockArr = array ("stockName" => $stockArr ['stockName'],"stockCode" => $stockArr ['stockCode'], "stockId" => $stockArr ['stockId'], "inventoryId" => $inventoryId, "lockNum" => $proArr ['lockNum'], "lockType" => 'instockLock', "productNo" => $proArr ['productCode'], "productId" => $proArr ['productId'], "productName" => $proArr ['productName'], "objCode" => $objArr ['applyCode'], "objId" => $objArr ['applyId'], "objType" => $objArr ['applyType'], "objEquId" => $equId, "inStockDocId" => $proArr ['inStockDocId'] );
		//print_r($lockArr);
		$this->add_d ( $lockArr, true );
	}

	/**
	 * 解锁（暂时用于反审核入库单）
	 */
//	function relateLock(){
//
//	}

	/**
	 * 手工锁定库存
	 */
	function stockLock_d($rows, $lockType = '') {
		try {
			$this->start_d ();
			$this->batchLock_d ( $rows, $lockType );
			$inventoryInfoDao = new model_stock_inventoryinfo_inventoryinfo ();
			foreach ( $rows as $key => $val ) {
				if (is_array ( $val ) && $val ['lockNum'] != 0) {
					//更新库存的可执行数量（减）
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
	 * 通过调拨单自动释放个人借试用锁定数量
	 */
	function releaseLockByPersonBorrow($rows) {
		$lockNum = $rows ['lockNum'];
		$borrowDao = new model_projectmanagent_borrow_borrow ();
		//根据传入的发货计划id查找源单号及源单类型
		$borrowObj = $borrowDao->get_d ( $rows ['borrowId'] );
		$borrowObj['docId']=$borrowObj['id'];
		$borrowObj['docCode']=$borrowObj['Code'];
		$borrowObj['docType']='oa_borrow_borrow';
		$borrowObj['rObjCode']=$borrowObj['objCode'];
		$rows['lockType']="allocationLock";
		$this->releaseLockByEqu($rows,$borrowObj,$lockNum);
	}

	/**
	 * 通过发货计划出库时，自动释放锁定数量及库存
	 */
	function releaseLockByOutPlan($rows) {
		//$lockNum = $rows['lockNum']*(-1);
		$lockNum = $rows ['lockNum'];
		$outplanDao = new model_stock_outplan_outplan ();
		//根据传入的发货计划id查找源单号及源单类型
		$planObj = $outplanDao->get_d ( $rows ['planId'] );
		$rows['lockType']="outstockLock";
		$this->releaseLockByEqu($rows,$planObj,$lockNum);
	}

	/**
	 * 反审核入库单 ，自动释放锁定数量及库存
	 */
	function releaseLockByCancelAudit($objArr, $proArr, $stockArr){
		//print_r($objArr);
		$rows=array("stockId"=>$stockArr['stockId'],"productId"=>$proArr['productId'],"lockType"=>"instockLock");
		$sourceObj=array("docId"=>$objArr['applyId'],"docType"=>$objArr['applyType'],"docCode"=>$objArr['applyCode'],"rObjCode"=>$objArr['rObjCode']);

		return $this->releaseLockByEqu($rows,$sourceObj,$proArr ['lockNum']);
	}

	/**
	 * 统一解锁方法
	 */
	function releaseLockByEqu($rows,$sourceObj,$lockNum){
		try {
			$this->start_d ();
			//print_r($sourceObj);
			//根据源单号、源单类型、仓库id、产品id定为一个记录，查找记录信息
			$this->searchArr = array (
				'objId' => $sourceObj ['docId'],
				'objType' => $sourceObj ['docType'],
				'stockId' => $rows ['stockId'],
				'productId' => $rows ['productId']
			 );
			//print_r($this->searchArr);
			//找到关联的所有的锁定记录
			$this->groupBy="c.objEquId,c.stockId,c.stockName,c.inventoryId,c.productId,c.productName,c.productNo";
			$sql = " select sum(c.lockNum) as lockNum,c.objEquId,c.stockId,c.stockName,c.inventoryId,c.productId,c.productName,c.productNo from oa_stock_lock c where 1=1 ";
			$lockObjs = $this->listBySql ( $sql );
			$lockArr = array ();
			//var_dump($lockObjs);
			if(!empty($lockObjs)){
				foreach($lockObjs as $key =>$lockObj){
					//$lockObj=$lockObjs[0];
					if($lockObj ['lockNum']>0){
						//如果锁定总数大于设备锁定数量,则当前解锁数量为设备锁定数量
						$isBreak=false;
						$relaseNum = $lockNum;
						if ($lockNum > $lockObj ['lockNum']) {
							$relaseNum = $lockObj ['lockNum'];
							$lockNum = $lockNum - $lockObj ['lockNum'];
						}else{
							$isBreak=true;
						}
						//拼装解锁数组。
						$lockArr = array (
							'objCode' => $sourceObj ['docCode'],
							'stockId' => $lockObj ['stockId'],
							 'stockName' => $lockObj ['stockName'],
							 'objType' => $sourceObj ['docType'] );
						$lockItemArr = array (
							'objId' => $sourceObj ['docId'],
							'rObjCode' => $sourceObj ['rObjCode'],//加上业务编号
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
	 * 根据编号和类型获取锁定记录
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
	 * 获取某个设备的锁定记录
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
	 * 获取某个业务对象的锁定记录
	 */
	function lockRecordsByobjCode($objCode, $objType = "") {
		$objType = $this->getObjType ( $objType );
		$searchArr = array ("objCode" => $objCode, 'objType' => $objType );
		$this->searchArr = $searchArr;
		return $this->page_d ();
	}

	/*
	 * 获取单个设备在某个仓库下的已经锁定数量数组(仓库id不传入则获取所有)
	 */
	function getEquStockLockNum($objEquId, $stockId = "", $docType) {
		//		$sql = "select sum(c.lockNum) as totalNum from " . $this->tbl_name . " c where c.objEquId='$objEquId' and c.productId=$productId group by c.productId";
		//		$lockNumArr = $this->findSql ( $sql );
		//		return $lockNumArr [0] ['totalNum'];
		$lockNumArr = $this->getEqusStockLockNumArr ( array ($objEquId ), $stockId, $docType );
		return $lockNumArr [0] ['totalNum'];
	}

	/*
	 * 获取设备在某个仓库下的已经锁定数量数组(仓库id不传入则获取所有)
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
	 * 获取锁定记录
	 * @param unknown_type $param
	 */
	function getPageLockLog() {
		$saleStock1 = '合同发货';
//		$saleStock2 = '服务发货';
//		$saleStock3 = '租赁发货';
//		$saleStock4 = '研发发货';
		$saleStock5 = '借用发货';
		$saleStock5 = '赠送发货';
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
		//构建获取记录数
		$sql .= " limit " . $this->start . "," . $this->pageSize;
		//echo $sql;
		return $this->_db->getArray ( $sql );
	}

	/**
	 *
	 * 根据锁定对象类型,锁定对象id,产品id,仓库id获取 已锁定数量
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