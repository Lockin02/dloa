<?php
/**
 * @author huangzf
 * @Date 2011��5��9�� 22:10:00
 * @version 1.0
 * @description:�ֿ��ڳ������Ϣ
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
						<a href="?model=stock_inventoryinfo_inventoryinfo&action=init&id=$rs[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�޸�< $rs[productName] >" class="thickbox">�޸�</a>|
						<a href="?model=stock_inventoryinfo_inventoryinfo&action=viewInfo&id=$rs[id]&typecode=$rs[typecode]&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�鿴< $rs[productName] >" class="thickbox">�鿴</a>
					</tr>
EOT;
			}
		
		} else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str . '<tr><td colspan="9" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';
	}
	
	/**
	 * ����Ӳ����������Ϣģ��
	 */
	function showConfigurationsView($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$str .= <<<EOT
					<tr id="configurations" class="TableHeader1" height="28" style="display:block">
					<td colspan="4">
					<table id="itemtable" border='0' cellspacing='1' width='100%'
						class='small' bgcolor='' align="center" rules="none">
						<tr class="TableHeader" height="20">
							<td colspan="4" align="center" width="90%">����</td>
						</tr>
						<tr align="center" class="TableHeader">
							<td width="25%">��������</td>
							<td width="20%">�ͺ�</td>
							<td width="20%">����</td>
							<td width="30%">˵��</td>
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
	
	/*--------------------------------------------------ҵ����------------------------------------------------------*/
	
	/**
	 * �����ڳ������Ϣ
	 */
	function add_d($inventoryinfo) {
		try {
			$this->start_d ();
			$id = parent::add_d ( $inventoryinfo, true );
			
			/*start:ͬʱ��ʼ���ڳ������Ϣ*/
			$stockbalanceDao = new model_finance_stockbalance_stockbalance ();
			$stockbalanceObj = array ("productId" => $inventoryinfo ['productId'], "productNo" => $inventoryinfo ['productCode'], "productName" => $inventoryinfo ['productName'], "productModel" => $inventoryinfo ['pattern'], "units" => $inventoryinfo ['unitName'], "clearingNum" => $inventoryinfo ['initialNum'], "balanceAmount" => $inventoryinfo ['sumAmount'], "price" => $inventoryinfo ['price'], "stockId" => $inventoryinfo ['stockId'], "stockCode" => $inventoryinfo ['stockCode'], "stockName" => $inventoryinfo ['stockName'], "inventoryId" => $id );
			$stockbalanceDao->addStockBalance_d ( $stockbalanceObj );
			/*end:ͬʱ��ʼ���ڳ������Ϣ*/
			
			$this->commit_d ();
			return $id;
		} catch ( exception $e ) {
			$this->rollBack ();
			return null;
		}
	
	}
	/**
	 * �޸��ڳ������Ϣ
	 */
	function edit_d($inventoryinfo) {
		try {
			$this->start_d ();
			parent::edit_d ( $inventoryinfo, true );
			
			/*start:ͬʱ�޸��ڳ������Ϣ*/
			$stockbalanceDao = new model_finance_stockbalance_stockbalance ();
			$stockbalanceObj = array ("productId" => $inventoryinfo ['productId'], "productNo" => $inventoryinfo ['productCode'], "productName" => $inventoryinfo ['productName'], "productModel" => $inventoryinfo ['pattern'], "units" => $inventoryinfo ['unitName'], "clearingNum" => $inventoryinfo ['initialNum'], "balanceAmount" => $inventoryinfo ['sumAmount'], "price" => $inventoryinfo ['price'], "stockId" => $inventoryinfo ['stockId'], "stockCode" => $inventoryinfo ['stockCode'], "stockName" => $inventoryinfo ['stockName'], "inventoryId" => $inventoryinfo ['id'] );
			$stockbalanceDao->editByInventoryId_d ( $stockbalanceObj );
			/*end:ͬʱ�޸��ڳ������Ϣ*/
			
			$this->commit_d ();
			return $inventoryinfo;
		} catch ( exception $e ) {
			$this->rollBack ();
			return null;
		}
	
	}
	
	/**
	 * ��ȡ�����Ϣ�������ƷΪӲ����ʱ��ͬʱ��ȡ��Ʒ��������Ϣ
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
	//	 * ���ݲֿ������¿����Ϣ
	//	 */
	//	function updateStockProNum($stockCode, $stockProArr, $actionType) {
	//		$sql = "";
	//		$actProCount = 0;
	//		foreach ( $stockProArr as $key => $stockPro ) {
	//
	//			if ("instock" == $actionType) { //������
	//				if (isset ( $stockPro ['exeNum'] )) {
	//					$sql = "update " . $this->tbl_name . " c set c.exeNum=c.exeNum+" . $stockPro ['exeNum'] . ", c.actNum=c.actNum+" . $stockPro ['changeNum'] . " where  c.stockId =(select t.id from oa_stock_baseinfo t where t.stockCode='" . $stockCode . "') and c.productId='" . $stockPro ['productId'] . "'";
	//				} else {
	//					$sql = "update " . $this->tbl_name . " c set c.actNum=c.actNum+" . $stockPro ['changeNum'] . " where  c.stockId =(select t.id from oa_stock_baseinfo t where t.stockCode='" . $stockCode . "') and c.productId='" . $stockPro ['productId'] . "'";
	//
	//				}
	//			}
	//			if ("outstock" == $actionType) { //�������
	//				$inventoryActNum = $this->get_table_fields ( $this->tbl_name, "productId='" . $stockPro ['productId'] . "' and stockCode='" . $stockCode . "'", "actNum" );
	//
	//				if (($inventoryActNum - $stockPro ['changeNum']) < 0) {
	//					throw new Exception ( "�������Ϣ����!" );
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
	//			throw new Exception ( "�������Ϣ����" );
	//		}
	//
	//	}
	

	/**
	 * ���¼�ʱ�����Ϣ
	 * @author huangzf
	 */
	function updateInTimeInfo($paramArr, $changeNum, $changeType) {
		$updateSql = "";
		$querySql = "";
		$queryResult = array ();
		if (empty ( $changeNum )) { //�仯����Ϊ�յ�,����Ĭ��Ϊ0
			$changeNum = 0;
		}
		
		if ("instock" == $changeType) { //������
			$countSql = "select count(c.id) as countNum from " . $this->tbl_name . " c where 1=1 ";
			$updateSql = "update " . $this->tbl_name . " c set c.exeNum=c.exeNum+" . $changeNum . ", c.actNum=c.actNum+" . $changeNum . " where 1=1 ";
			$querySql = $this->createQuery ( $countSql, $paramArr );
			$queryResult = $this->listBySql ( $querySql );
			if ($queryResult [0] ['countNum'] != 0) { //�ֿ���ڸ�����
				$querySql = $this->createQuery ( $updateSql, $paramArr );
				$this->query ( $querySql );
			} else {
				//throw new Exception ( "��ȷ�ϲֿ���Ϣ�Ƿ��ʼ����" );
				//�ֿ��в����ڵ�����ϵͳ�Զ����г�ʼ��
				if ($this->initialInventory ( $paramArr ['productId'], $paramArr ['stockId'] )) {
					$querySql = $this->createQuery ( $updateSql, $paramArr );
					$this->query ( $querySql );
				}
			
			}
		}
		
		if ("outstock" == $changeType) { //�������
			$productDao = new model_stock_productinfo_productinfo ();
			$productObj = $productDao->get_d ( $paramArr ['productId'] );
			$selectActSql = "select c.actNum from " . $this->tbl_name . " c where 1=1 ";
			$querySql = $this->createQuery ( $selectActSql, $paramArr );
			$queryResult = $this->listBySql ( $querySql );
			if ($queryResult) {
//				if (($queryResult [0] ['actNum'] - $changeNum) < 0) {
//					throw new Exception ( "����(".$productObj ['productCode'] . ")��治��!" );
//				} else {
					$querySql = "update " . $this->tbl_name . " c  set c.exeNum=c.exeNum-" . $changeNum . ",c.actNum=c.actNum-" . $changeNum . " where 1=1 ";
					$querySql = $this->createQuery ( $querySql, $paramArr );
					$this->query ( $querySql );
//				}
			
			} else {
				throw new Exception ( $productObj ['productCode'] . "�ֿ���û�д�����(".$productObj ['productCode'].")!" );
			}
		
		}
	
	}
	/**
	 *
	 * ϵͳ�Զ���ʼ����ʱ���
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
	
	//	/** �������淽�� ���ݲֿ������д���
	//	 * ���ݲֿ�Id�Ͳ�ƷID���¿���ִ������,$changeNum:�仯������/$actionType:instock(��ӿ��),outstock(���ٿ��)
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
	//	 * ���ݲֿ���롢��Ʒid���¿��Ŀ�ִ������
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
	 * ���ݿ����Ϣid ���������Ϣ
	 */
	function lockExeNum($id, $lockNum) {
		$inventoryinfo = $this->get_d ( $id );
		//������Ҫ�жϿ����������Ƿ����0�����С��0�������ǳ����ʱ����ʱ��ȥ�Ŀ�ִ������
		if ($inventoryinfo ['exeNum'] >= 0) {
			if ($lockNum > $inventoryinfo ['exeNum']) {
				throw new Exception ( "������������" );
			}
		}
		$sql = "update " . $this->tbl_name . " set  exeNum=exeNum-$lockNum ,lockedNum=lockedNum+$lockNum where id=$id";
		return $this->query ( $sql );
	
	}
	
	/**
	 * ���ݲֿ�ids ��ȡ��Ʒ�����Ϣ
	 */
	function getInfoFromIds_d($ids) {
		$this->searchArr ['stockIds'] = $ids;
		return $this->listBySqlId ( 'select_forCalculate' );
	}
	
	/**
	 * ���ݲֿ�id����Ʒid��ȡ��Ʒ��Ϣ
	 */
	function getInventoryInfos($stockId, $productIds) {
		$this->searchArr = array ('stockId' => $stockId, "productIds" => $productIds );
		return $this->listBySqlId ( "select_all" );
	}
	
	/**
	 * ���ݲ�Ʒid �Ͳֿ�id ���¿��۸�
	 */
	function updatePrice_d($object) {
		$sql = "update " . $this->tbl_name . " set price=" . $object ['price'] . " where productId='" . $object ['productId'] . "' and stockId='" . $object ['stockId'] . "'";
		return $this->query ( $sql );
	}
	
	//	/**����Excel
	//	 *author can
	//	 *2011-3-8
	//	 */
	//	function getworklogExcels_d() {
	//		return $arr = $this->listBySqlId ( 'inventoryinfo_excel' );
	//	}
	

	/**
	 * ���ݲ�ƷId�Ͳֿ����ƻ�ȡ�������
	 */
	function getExeNums($productId, $default) {
		//		$stock = util_jsonUtil::iconvGB2UTF('');
		//		echo $stockName = util_jsonUtil::iconvGB2UTF('���۲�');
		$systeminfoDao = new model_stock_stockinfo_systeminfo ();
		$rows = $systeminfoDao->get_d ( $default );
		$conditions = "productId = " . $productId . " and stockCode = '" . $rows ['salesStockCode'] . "'";
		return $this->get_table_fields ( $this->tbl_name, $conditions, 'exeNum' );
	}
	
	/**
	 * ���ݲ�ƷId�Ͳֿ����ͻ�ȡ�������
	 */
	function getExeNumsByStockType($productId, $stockType = 'salesStockCode') {
		$systeminfoDao = new model_stock_stockinfo_systeminfo ();
		$rows = $systeminfoDao->get_d ( 1 );
		$conditions = "productId = " . $productId . " and stockCode = '" . $rows [$stockType] . "'";
		return $this->get_table_fields ( $this->tbl_name, $conditions, 'exeNum' );
	}
	
	/**
	 * ���ݲ�Ʒidͳ�Ʋֿ⼴ʱ���
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
	 * �ж��Ƿ�����޸ġ�ɾ���ڳ����
	 * @param $id
	 */
	function checkEditInv($id) {
		$inventoryObj = $this->get_d ( $id );
		//�ж������Ƿ���ڵ��ڵ�ǰ��������
		$thisDate = substr ( $inventoryObj ['createTime'], 0, - 2 ) . '01';
		$financePeriodDao = new model_finance_period_period ();
		if ($financePeriodDao->isLaterPeriod_d ( $thisDate ))
			return false;
		
		//�Ƿ�����������ⵥ
		$inStockDao = new model_stock_instock_stockin ();
		$inStockDao->searchArr = array ("itemInStockId" => $inventoryObj ['stockId'], "productId" => $inventoryObj ['productId'], "docStatus" => "YSH" );
		$inStockDao->sort = "c.id";
		$result1 = $inStockDao->list_d ( "select_count" );
		if ($result1 [0] ['countNum'] > 0)
			return FALSE;
		
		//�Ƿ��������˳��ⵥ
		$outStockDao = new model_stock_outstock_stockout ();
		$outStockDao->searchArr = array ("itemStockId" => $inventoryObj ['stockId'], "productId" => $inventoryObj ['productId'], "docStatus" => "YSH" );
		$outStockDao->sort = "c.id";
		$result2 = $outStockDao->listBySqlId ( "select_count" );
		if ($result2 [0] ['countNum'] > 0)
			return FALSE;
		
		//�Ƿ��������˵�����
		$allocationDao = new model_stock_allocation_allocation ();
		$allocationDao->searchArr = array ("itemStockId" => $inventoryObj ['stockId'], "productId" => $inventoryObj ['productId'], "docStatus" => "YSH" );
		$allocationDao->sort = "c.id";
		$result3 = $allocationDao->listBySqlId ( "select_count" );
		if ($result3 [0] ['countNum'] > 0)
			return FALSE;
		return true;
	}
	/**
	 * ����ɾ������
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
	 * ���ݶ�ȡEXCEL�е���Ϣ���뵽ϵͳ��
	 * @param $stockArr
	 */
	function importInventory($inverntoryArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array (); //������
			$productDao = new model_stock_productinfo_productinfo ();
			$stockDao = new model_stock_stockinfo_stockinfo ();
			foreach ( $inverntoryArr as $key => $obj ) {
				if (! empty ( $obj [0] ) && ! empty ( $obj [2] )) {
					$proObj = $productDao->getProByExt2 ( $obj [2] );
					$stockObj = $stockDao->getStockByCode ( $obj [0] ); //��ȡ�ֿ���Ϣ
					if (is_array ( $stockObj )) {
						if ($proObj ['ext2']) {
							$tempObj = array ("stockId" => $stockObj ['id'], "stockCode" => $obj [0], "stockName" => $stockObj ['stockName'], "proTypeId" => $proObj ['proTypeId'], "proType" => $proObj ['proType'], "productId" => $proObj ['id'], "productCode" => $proObj ['productCode'], "productName" => $proObj ['productName'], "pattern" => $proObj ['pattern'], "unitName" => $proObj ['unitName'], "aidUnit" => $proObj ['aidUnit'], "converRate" => $proObj ['converRate'], "initialNum" => $obj [4], "safeNum" => $obj [5], "actNum" => $obj [4], "exeNum" => $obj [4], "maxNum" => $obj [6], "miniNum" => $obj [7], "price" => $obj [8], "sumAmount" => $obj [9] );
							$this->searchArr = array ("stockId" => $tempObj ['stockId'], "productId" => $tempObj ['productId'] );
							if (! is_array ( $this->list_d () )) {
								$nid = $this->add_d ( $tempObj, true );
								if ($nid) {
									//								/*start:ͬʱ��ʼ���ڳ������Ϣ*/
									//								$stockbalanceDao = new model_finance_stockbalance_stockbalance ();
									//								$stockbalanceObj = array ("productId" => $tempObj ['productId'], "productNo" => $tempObj ['productCode'], "productName" => $tempObj ['productName'], "productModel" => $tempObj ['pattern'], "units" => $tempObj ['unitName'], "clearingNum" => $tempObj ['initialNum'], "balanceAmount" => $tempObj ['sumAmount'], "price" => $tempObj ['price'], "stockId" => $tempObj ['stockId'], "stockCode" => $tempObj ['stockCode'], "stockName" => $tempObj ['stockName'], "inventoryId" => $nid );
									//								$stockbalanceDao->addStockBalance_d ( $stockbalanceObj );
									//								/*end:ͬʱ��ʼ���ڳ������Ϣ*/
									array_push ( $resultArr, array ("docCode" => $tempObj [productCode], "result" => "�ɹ�" ) );
								} else
									array_push ( $resultArr, array ("docCode" => $tempObj [productCode], "result" => "ʧ��" ) );
							} else {
								array_push ( $resultArr, array ("docCode" => $tempObj [productCode], "result" => "�������ڸòֿ����Ѿ���ʼ��!" ) );
							}
						} else {
							array_push ( $resultArr, array ("docCode" => $obj [2], "result" => "k3�����޷�ƥ��,��ȷ�������Ƿ��ʼ��!" ) );
						}
					} else {
						array_push ( $resultArr, array ("docCode" => $obj [2], "result" => "�ֿⲻ����,��ֿ��Ƿ��ʼ��!" ) );
					}
				} else {
					array_push ( $resultArr, array ("docCode" => $obj [2], "result" => "�ֿ������Ʒ���벻��Ϊ��!" ) );
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
	 * ��ȡ���״̬ͳ������
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
		//ƴװ��������
		//group by c.productName,c.productCode  having 1=1
		$sql = $this->createQuery ( $sql, $this->searchArr );
		//print($sql);
		$sql .= "group by c.productName,c.productCode ";
		//����������Ϣ
		$asc = $this->asc ? "DESC" : "ASC";
		//echo $this->asc;
		$sql .= "  order by " . $this->sort . " " . $asc;
		//������ȡ��¼��
		$sql .= " limit " . $this->start . "," . $this->pageSize;
		//		echo $sql;
		return $this->_db->getArray ( $sql );
	}
	
	/**
	 * ���ݲֿ�id����Ʒid��ȡ�����Ϣ
	 * add by chengl 2011-11-19
	 */
	function getInventoryInfoByStockAndProduct($stockId, $productId) {
		$this->searchArr = array ("stockId" => $stockId, "productId" => $productId );
		$list = $this->list_d ();
		if (count ( $list ) > 0) {
			return $list [0];
		} else {
			throw new Exception ( "û�д˲�Ʒ�����Ϣ." );
		}
	}
}
?>
