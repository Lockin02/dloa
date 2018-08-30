<?php
/**
 * @author huangzf
 * @Date 2011年5月9日 22:10:00
 * @version 1.0
 * @description:仓库期初库存信息
 */

class model_stock_inventoryinfo_inventoryinfo extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_stock_inventory_info";
		$this->sql_map = "stock/inventoryinfo/inventoryinfoSql.php";
		parent::__construct ();
	}
	
	function showlist($rows, $showpage) {
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ( $rows as $key => $rs ) {
				$i ++;
				$str .= <<<EOT
					<tr>
					<td><input type="checkbox" name="datacb"  value="$rs[id]" onClick="checkOne();"></td>
					<td height="25" align="center"> $i </td>
					<td align="center" >$rs[stockName] </td>
					<td align="center" >$rs[proType] </td>
					<td align="center" >$rs[sequence] </td>
					<td align="center" >$rs[productName] </td>
					<td align="center" >$rs[actNum] </td>
					<td align="center" >$rs[price] </td>
					<td align="center" >
						<a href="?model=stock_inventoryinfo_inventoryinfo&action=init&id=$rs[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="修改< $rs[productName] >" class="thickbox">修改</a>|
						<a href="?model=stock_inventoryinfo_inventoryinfo&action=viewInfo&id=$rs[id]&typecode=$rs[typecode]&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="查看< $rs[productName] >" class="thickbox">查看</a>
					</tr>
EOT;
			}
		
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str . '<tr><td colspan="9" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';
	}
	
	/**
	 * 返回硬件的配置信息模板
	 */
	function showConfigurationsView($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$str .= <<<EOT
					<tr id="configurations" class="TableHeader1" height="28" style="display:block">
					<td colspan="4">
					<table id="itemtable" border='0' cellspacing='1' width='100%'
						class='small' bgcolor='' align="center" rules="none">
						<tr class="TableHeader" height="20">
							<td colspan="4" align="center" width="90%">配置</td>
						</tr>
						<tr align="center" class="TableHeader">
							<td width="25%">配置名称</td>
							<td width="20%">型号</td>
							<td width="20%">数量</td>
							<td width="30%">说明</td>
						</tr>
					<tr align="center" class="TableData" height="28">
							<td>
							$val[configName]
						</td>
						<td>
							$val[configPattern]
						</td>
						<td>
							$val[configNum]
						</td>
						<td>
							$val[explains]
						</td>
				 		</tr>
				 		</table>
						</td>
					</tr>

EOT;
				$i ++;
			}
		
		}
		return $str;
	}
	
	/*--------------------------------------------------业务处理------------------------------------------------------*/
	
	/**
	 * 新增期初库存信息
	 */
	function add_d($inventoryinfo) {
		try {
			$this->start_d ();
			$id = parent::add_d ( $inventoryinfo, true );
			
			/*start:同时初始化期初余额信息*/
			$stockbalanceDao = new model_finance_stockbalance_stockbalance ();
			$stockbalanceObj = array ("productId" => $inventoryinfo ['productId'], "productNo" => $inventoryinfo ['productCode'], "productName" => $inventoryinfo ['productName'], "productModel" => $inventoryinfo ['pattern'], "units" => $inventoryinfo ['unitName'], "clearingNum" => $inventoryinfo ['initialNum'], "balanceAmount" => $inventoryinfo ['sumAmount'], "price" => $inventoryinfo ['price'], "stockId" => $inventoryinfo ['stockId'], "stockCode" => $inventoryinfo ['stockCode'], "stockName" => $inventoryinfo ['stockName'], "inventoryId" => $id );
			$stockbalanceDao->addStockBalance_d ( $stockbalanceObj );
			/*end:同时初始化期初余额信息*/
			
			$this->commit_d ();
			return $id;
		} catch ( exception $e ) {
			$this->rollBack ();
			return null;
		}
	
	}
	/**
	 * 修改期初库存信息
	 */
	function edit_d($inventoryinfo) {
		try {
			$this->start_d ();
			parent::edit_d ( $inventoryinfo, true );
			
			/*start:同时修改期初余额信息*/
			$stockbalanceDao = new model_finance_stockbalance_stockbalance ();
			$stockbalanceObj = array ("productId" => $inventoryinfo ['productId'], "productNo" => $inventoryinfo ['productCode'], "productName" => $inventoryinfo ['productName'], "productModel" => $inventoryinfo ['pattern'], "units" => $inventoryinfo ['unitName'], "clearingNum" => $inventoryinfo ['initialNum'], "balanceAmount" => $inventoryinfo ['sumAmount'], "price" => $inventoryinfo ['price'], "stockId" => $inventoryinfo ['stockId'], "stockCode" => $inventoryinfo ['stockCode'], "stockName" => $inventoryinfo ['stockName'], "inventoryId" => $inventoryinfo ['id'] );
			$stockbalanceDao->editByInventoryId_d ( $stockbalanceObj );
			/*end:同时修改期初余额信息*/
			
			$this->commit_d ();
			return $inventoryinfo;
		} catch ( exception $e ) {
			$this->rollBack ();
			return null;
		}
	
	}
	
	/**
	 * 获取库存信息是如果产品为硬件类时，同时获取产品的配置信息
	 */
	function getIventoryByIdandCode($productId, $typecode) {
		$inventoryinfo = parent::listBySqlId ( "select_comp_inventoryinfo" );
		if ("hardware" == $typecode) {
			$configurationDao = new model_stock_productinfo_configuration ();
			$configurations = $configurationDao->getConfigByHardWareId ( $productId );
			$inventoryinfo [0] ['configurations'] = $configurations;
		}
		return $inventoryinfo [0];
	}
	
	//	/**
	//	 * 根据仓库代码更新库存信息
	//	 */
	//	function updateStockProNum($stockCode, $stockProArr, $actionType) {
	//		$sql = "";
	//		$actProCount = 0;
	//		foreach ( $stockProArr as $key => $stockPro ) {
	//
	//			if ("instock" == $actionType) { //入库操作
	//				if (isset ( $stockPro ['exeNum'] )) {
	//					$sql = "update " . $this->tbl_name . " c set c.exeNum=c.exeNum+" . $stockPro ['exeNum'] . ", c.actNum=c.actNum+" . $stockPro ['changeNum'] . " where  c.stockId =(select t.id from oa_stock_baseinfo t where t.stockCode='" . $stockCode . "') and c.productId='" . $stockPro ['productId'] . "'";
	//				} else {
	//					$sql = "update " . $this->tbl_name . " c set c.actNum=c.actNum+" . $stockPro ['changeNum'] . " where  c.stockId =(select t.id from oa_stock_baseinfo t where t.stockCode='" . $stockCode . "') and c.productId='" . $stockPro ['productId'] . "'";
	//
	//				}
	//			}
	//			if ("outstock" == $actionType) { //出库操作
	//				$inventoryActNum = $this->get_table_fields ( $this->tbl_name, "productId='" . $stockPro ['productId'] . "' and stockCode='" . $stockCode . "'", "actNum" );
	//
	//				if (($inventoryActNum - $stockPro ['changeNum']) < 0) {
	//					throw new Exception ( "出入库信息有误!" );
	//				} else {
	//					if (isset ( $stockPro ['exeNum'] )) {
	//						$sql = "update " . $this->tbl_name . " c set c.exeNum=c.exeNum-" . $stockPro ['exeNum'] . ", c.actNum=c.actNum-" . $stockPro ['changeNum'] . " where  c.stockCode = '$stockCode' and c.productId='" . $stockPro ['productId'] . "'";
	//					} else {
	//						$sql = "update " . $this->tbl_name . " c set c.actNum=c.actNum-" . $stockPro ['changeNum'] . " where  c.stockCode ='$stockCode' and c.productId='" . $stockPro ['productId'] . "'";
	//					}
	//				}
	//
	//			}
	//			$this->query ( $sql );
	//			if ($this->_db->affected_rows () == 1) {
	//				$actProCount ++;
	//			}
	//
	//		}
	//
	//		if ($actProCount != count ( $stockProArr )) {
	//			throw new Exception ( "出入库信息有误" );
	//		}
	//
	//	}
	

	/**
	 * 更新即时库存信息
	 * @author huangzf
	 */
	function updateInTimeInfo($paramArr, $changeNum, $changeType) {
		$updateSql = "";
		$querySql = "";
		$queryResult = array ();
		if (empty ( $changeNum )) { //变化数量为空的,设置默认为0
			$changeNum = 0;
		}
		
		if ("instock" == $changeType) { //入库操作
			$countSql = "select count(c.id) as countNum from " . $this->tbl_name . " c where 1=1 ";
			$updateSql = "update " . $this->tbl_name . " c set c.exeNum=c.exeNum+" . $changeNum . ", c.actNum=c.actNum+" . $changeNum . " where 1=1 ";
			$querySql = $this->createQuery ( $countSql, $paramArr );
			$queryResult = $this->listBySql ( $querySql );
			if ($queryResult [0] ['countNum'] != 0) { //仓库存在该物料
				$querySql = $this->createQuery ( $updateSql, $paramArr );
				$this->query ( $querySql );
			} else {
				//throw new Exception ( "请确认仓库信息是否初始化！" );
				//仓库中不存在的物料系统自动进行初始化
				if ($this->initialInventory ( $paramArr ['productId'], $paramArr ['stockId'] )) {
					$querySql = $this->createQuery ( $updateSql, $paramArr );
					$this->query ( $querySql );
				}
			
			}
		}
		
		if ("outstock" == $changeType) { //出库操作
			$productDao = new model_stock_productinfo_productinfo ();
			$productObj = $productDao->get_d ( $paramArr ['productId'] );
			$selectActSql = "select c.actNum from " . $this->tbl_name . " c where 1=1 ";
			$querySql = $this->createQuery ( $selectActSql, $paramArr );
			$queryResult = $this->listBySql ( $querySql );
			if ($queryResult) {
//				if (($queryResult [0] ['actNum'] - $changeNum) < 0) {
//					throw new Exception ( "物料(".$productObj ['productCode'] . ")库存不够!" );
//				} else {
					$querySql = "update " . $this->tbl_name . " c  set c.exeNum=c.exeNum-" . $changeNum . ",c.actNum=c.actNum-" . $changeNum . " where 1=1 ";
					$querySql = $this->createQuery ( $querySql, $paramArr );
					$this->query ( $querySql );
//				}
			
			} else {
				throw new Exception ( $productObj ['productCode'] . "仓库中没有此物料(".$productObj ['productCode'].")!" );
			}
		
		}
	
	}
	/**
	 *
	 * 系统自动初始化即时库存
	 * @param $productId
	 * @param $stockId
	 * @author huangzf
	 */
	function initialInventory($productId, $stockId) {
		$productDao = new model_stock_productinfo_productinfo ();
		$stockDao = new model_stock_stockinfo_stockinfo ();
		$productObj = $productDao->get_d ( $productId );
		$stockObj = $stockDao->get_d ( $stockId );
		$inventoryObj = array ("stockId" => $stockObj ['id'], "stockName" => $stockObj ['stockName'], "stockCode" => $stockObj ['stockCode'], "proTypeId" => $productObj ['proTypeId'], "proType" => $productObj ['proType'], "productId" => $productObj ['id'], "productCode" => $productObj ['productCode'], "productName" => $productObj ['productName'], "pattern" => $productObj ['pattern'], "unitName" => $productObj ['unitName'], "aidUnit" => $productObj ['aidUnit'], "converRate" => $productObj ['converRate'], "initialNum" => "0", "actNum" => "0", "exeNum" => "0" );
		return parent::add_d ( $inventoryObj, true );
	}
	
	//	/** 采用下面方法 根据仓库代码进行处理
	//	 * 根据仓库Id和产品ID更新库存可执行数量,$changeNum:变化的数量/$actionType:instock(添加库存),outstock(减少库存)
	//	 */
	//	function changeExeNum2($stockId, $productId, $changeNum, $actionType) {
	//		$condition = array ("stockId" => $stockId, "productId" => $productId );
	//		$sql = "";
	//
	//		if ("instock" == $actionType) {
	//			$sql = "update " . $this->tbl_name . " set exeNum=exeNum+" . $changeNum . " where stockId='" . $stockId . "' and productId='" . $productId . "'";
	//		}
	//		if ("outstock" == $actionType) {
	//			$sql = "update " . $this->tbl_name . " set exeNum=exeNum-" . $changeNum . " where stockId='" . $stockId . "' and productId='" . $productId . "'";
	//		}
	//		//		echo $sql."<br />";
	//		return $this->query ( $sql );
	//	}
	//
	//	/**
	//	 * 根据仓库代码、产品id更新库存的可执行数量
	//	 */
	//	function changeExeNum($stockCode, $productId, $changeNum, $actionType) {
	//		$condition = array ("$stockCode" => $stockCode, "productId" => $productId );
	//		$sql = "";
	//
	//		if ("instock" == $actionType) {
	//			$sql = "update " . $this->tbl_name . " set exeNum=exeNum+" . $changeNum . " where stockCode='" . $stockCode . "' and productId='" . $productId . "'";
	//		}
	//		if ("outstock" == $actionType) {
	//			$sql = "update " . $this->tbl_name . " set exeNum=exeNum-" . $changeNum . " where stockCode='" . $stockCode . "' and productId='" . $productId . "'";
	//		}
	//		//		echo $sql."<br />";
	//		return $this->query ( $sql );
	//	}
	

	/**
	 * 根据库存信息id 锁定库存信息
	 */
	function lockExeNum($id, $lockNum) {
		$inventoryinfo = $this->get_d ( $id );
		//这里需要判断可行性数量是否大于0，如果小于0，可能是出库的时候临时减去的可执行数量
		if ($inventoryinfo ['exeNum'] >= 0) {
			if ($lockNum > $inventoryinfo ['exeNum']) {
				throw new Exception ( "锁定数量有误！" );
			}
		}
		$sql = "update " . $this->tbl_name . " set  exeNum=exeNum-$lockNum ,lockedNum=lockedNum+$lockNum where id=$id";
		return $this->query ( $sql );
	
	}
	
	/**
	 * 根据仓库ids 获取产品库存信息
	 */
	function getInfoFromIds_d($ids) {
		$this->searchArr ['stockIds'] = $ids;
		return $this->listBySqlId ( 'select_forCalculate' );
	}
	
	/**
	 * 根据仓库id、产品id获取产品信息
	 */
	function getInventoryInfos($stockId, $productIds) {
		$this->searchArr = array ('stockId' => $stockId, "productIds" => $productIds );
		return $this->listBySqlId ( "select_all" );
	}
	
	/**
	 * 根据产品id 和仓库id 更新库存价格
	 */
	function updatePrice_d($object) {
		$sql = "update " . $this->tbl_name . " set price=" . $object ['price'] . " where productId='" . $object ['productId'] . "' and stockId='" . $object ['stockId'] . "'";
		return $this->query ( $sql );
	}
	
	//	/**导出Excel
	//	 *author can
	//	 *2011-3-8
	//	 */
	//	function getworklogExcels_d() {
	//		return $arr = $this->listBySqlId ( 'inventoryinfo_excel' );
	//	}
	

	/**
	 * 根据产品Id和仓库名称获取库存数量
	 */
	function getExeNums($productId, $default) {
		//		$stock = util_jsonUtil::iconvGB2UTF('');
		//		echo $stockName = util_jsonUtil::iconvGB2UTF('销售仓');
		$systeminfoDao = new model_stock_stockinfo_systeminfo ();
		$rows = $systeminfoDao->get_d ( $default );
		$conditions = "productId = " . $productId . " and stockCode = '" . $rows ['salesStockCode'] . "'";
		return $this->get_table_fields ( $this->tbl_name, $conditions, 'exeNum' );
	}
	
	/**
	 * 根据产品Id和仓库类型获取库存数量
	 */
	function getExeNumsByStockType($productId, $stockType = 'salesStockCode') {
		$systeminfoDao = new model_stock_stockinfo_systeminfo ();
		$rows = $systeminfoDao->get_d ( 1 );
		$conditions = "productId = " . $productId . " and stockCode = '" . $rows [$stockType] . "'";
		return $this->get_table_fields ( $this->tbl_name, $conditions, 'exeNum' );
	}
	
	/**
	 * 根据产品id统计仓库即时库存
	 */
	function getActNumByProId($productId) {
		$this->searchArr = array ("productId" => $productId );
		$rows = $this->list_d ( "subactnum_proid" );
		if (is_array ( $rows )) {
			return $rows [0] ['actNum'];
		} else {
			return 0;
		}
	}
	
	/**
	 * 判断是否可以修改、删除期初库存
	 * @param $id
	 */
	function checkEditInv($id) {
		$inventoryObj = $this->get_d ( $id );
		//判断日期是否大于等于当前财务周期
		$thisDate = substr ( $inventoryObj ['createTime'], 0, - 2 ) . '01';
		$financePeriodDao = new model_finance_period_period ();
		if ($financePeriodDao->isLaterPeriod_d ( $thisDate ))
			return false;
		
		//是否存在已审核入库单
		$inStockDao = new model_stock_instock_stockin ();
		$inStockDao->searchArr = array ("itemInStockId" => $inventoryObj ['stockId'], "productId" => $inventoryObj ['productId'], "docStatus" => "YSH" );
		$inStockDao->sort = "c.id";
		$result1 = $inStockDao->list_d ( "select_count" );
		if ($result1 [0] ['countNum'] > 0)
			return FALSE;
		
		//是否存在已审核出库单
		$outStockDao = new model_stock_outstock_stockout ();
		$outStockDao->searchArr = array ("itemStockId" => $inventoryObj ['stockId'], "productId" => $inventoryObj ['productId'], "docStatus" => "YSH" );
		$outStockDao->sort = "c.id";
		$result2 = $outStockDao->listBySqlId ( "select_count" );
		if ($result2 [0] ['countNum'] > 0)
			return FALSE;
		
		//是否存在已审核调拨单
		$allocationDao = new model_stock_allocation_allocation ();
		$allocationDao->searchArr = array ("itemStockId" => $inventoryObj ['stockId'], "productId" => $inventoryObj ['productId'], "docStatus" => "YSH" );
		$allocationDao->sort = "c.id";
		$result3 = $allocationDao->listBySqlId ( "select_count" );
		if ($result3 [0] ['countNum'] > 0)
			return FALSE;
		return true;
	}
	/**
	 * 批量删除对象
	 */
	function deletes_d($ids) {
		try {
			$this->deletes ( $ids );
			$stockbalanceDao = new model_finance_stockbalance_stockbalance ();
			$stockbalanceDao->deleteByInventoryId_d ( $ids );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
	
	/**
	 * 根据读取EXCEL中的信息导入到系统中
	 * @param $stockArr
	 */
	function importInventory($inverntoryArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array (); //导入结果
			$productDao = new model_stock_productinfo_productinfo ();
			$stockDao = new model_stock_stockinfo_stockinfo ();
			foreach ( $inverntoryArr as $key => $obj ) {
				if (! empty ( $obj [0] ) && ! empty ( $obj [2] )) {
					$proObj = $productDao->getProByExt2 ( $obj [2] );
					$stockObj = $stockDao->getStockByCode ( $obj [0] ); //获取仓库信息
					if (is_array ( $stockObj )) {
						if ($proObj ['ext2']) {
							$tempObj = array ("stockId" => $stockObj ['id'], "stockCode" => $obj [0], "stockName" => $stockObj ['stockName'], "proTypeId" => $proObj ['proTypeId'], "proType" => $proObj ['proType'], "productId" => $proObj ['id'], "productCode" => $proObj ['productCode'], "productName" => $proObj ['productName'], "pattern" => $proObj ['pattern'], "unitName" => $proObj ['unitName'], "aidUnit" => $proObj ['aidUnit'], "converRate" => $proObj ['converRate'], "initialNum" => $obj [4], "safeNum" => $obj [5], "actNum" => $obj [4], "exeNum" => $obj [4], "maxNum" => $obj [6], "miniNum" => $obj [7], "price" => $obj [8], "sumAmount" => $obj [9] );
							$this->searchArr = array ("stockId" => $tempObj ['stockId'], "productId" => $tempObj ['productId'] );
							if (! is_array ( $this->list_d () )) {
								$nid = $this->add_d ( $tempObj, true );
								if ($nid) {
									//								/*start:同时初始化期初余额信息*/
									//								$stockbalanceDao = new model_finance_stockbalance_stockbalance ();
									//								$stockbalanceObj = array ("productId" => $tempObj ['productId'], "productNo" => $tempObj ['productCode'], "productName" => $tempObj ['productName'], "productModel" => $tempObj ['pattern'], "units" => $tempObj ['unitName'], "clearingNum" => $tempObj ['initialNum'], "balanceAmount" => $tempObj ['sumAmount'], "price" => $tempObj ['price'], "stockId" => $tempObj ['stockId'], "stockCode" => $tempObj ['stockCode'], "stockName" => $tempObj ['stockName'], "inventoryId" => $nid );
									//								$stockbalanceDao->addStockBalance_d ( $stockbalanceObj );
									//								/*end:同时初始化期初余额信息*/
									array_push ( $resultArr, array ("docCode" => $tempObj [productCode], "result" => "成功" ) );
								} else
									array_push ( $resultArr, array ("docCode" => $tempObj [productCode], "result" => "失败" ) );
							} else {
								array_push ( $resultArr, array ("docCode" => $tempObj [productCode], "result" => "该物料在该仓库中已经初始化!" ) );
							}
						} else {
							array_push ( $resultArr, array ("docCode" => $obj [2], "result" => "k3编码无法匹配,请确认物料是否初始化!" ) );
						}
					} else {
						array_push ( $resultArr, array ("docCode" => $obj [2], "result" => "仓库不存在,请仓库是否初始化!" ) );
					}
				} else {
					array_push ( $resultArr, array ("docCode" => $obj [2], "result" => "仓库代码或产品代码不能为空!" ) );
				}
			
			}
			
			$this->commit_d ();
			return $resultArr;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 *
	 * 获取库存状态统计数据
	 */
	function getPageSubItem() {
		$sql = $this->sql_arr ['select_subitem'];
		$countsql = "select count(0) as num from ("; //. substr ( $sql, strrpos ( $sql, "from" ) );
		$selectSql = substr ( $sql, strrpos ( $sql, "from" ) );
		//	echo $countsql;
		$countsql .= $this->createQuery ( $sql, $this->searchArr );
		$countsql .= "group by c.productName,c.productCode  having 1=1) sub";
		//		echo $countsql;
		$this->count = $this->queryCount ( $countsql );
		//拼装搜索条件
		//group by c.productName,c.productCode  having 1=1
		$sql = $this->createQuery ( $sql, $this->searchArr );
		//print($sql);
		$sql .= "group by c.productName,c.productCode ";
		//构建排序信息
		$asc = $this->asc ? "DESC" : "ASC";
		//echo $this->asc;
		$sql .= "  order by " . $this->sort . " " . $asc;
		//构建获取记录数
		$sql .= " limit " . $this->start . "," . $this->pageSize;
		//		echo $sql;
		return $this->_db->getArray ( $sql );
	}
	
	/**
	 * 根据仓库id跟产品id获取库存信息
	 * add by chengl 2011-11-19
	 */
	function getInventoryInfoByStockAndProduct($stockId, $productId) {
		$this->searchArr = array ("stockId" => $stockId, "productId" => $productId );
		$list = $this->list_d ();
		if (count ( $list ) > 0) {
			return $list [0];
		} else {
			throw new Exception ( "没有此产品库存信息." );
		}
	}
}
?>
