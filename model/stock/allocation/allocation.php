<?php

/**
 * @author huangzf
 * @Date 2011年5月5日 9:35:03
 * @version 1.0
 * @description:调拨单基本信息 Model层
 */
class model_stock_allocation_allocation extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_stock_allocation";
		$this->sql_map = "stock/allocation/allocationSql.php";
		$this->relDocTypeArr = array(
			"DBDYDLXFH" => array(
				"name" => "发货计划",
				"mainModel" => "model_stock_outplan_outplan",
				"dealMainFun" => "updateAsOut",
				"unAuditFun" => "updateAsAutiAudit"
			),
			"DBDYDLXJY" => array(
				"name" => "个人借试用",
				"mainModel" => "model_projectmanagent_borrow_borrow",
				"dealMainFun" => "updateAsOut",
				"unAuditFun" => "updateAsAutiAudit"
			),
			"DBDYDLXDB" => array(
				"name" => "借出调拨单",
				"mainModel" => "model_stock_allocation_allocation",
				"dealMainFun" => "dealAtAudit",
				"unAuditFun" => "dealAtUnAudit"
			),
			"DBDYDLXGH" => array(
				"name" => "归还申请单",
				"mainModel" => "model_projectmanagent_borrowreturn_borrowreturnDis",
				"dealMainFun" => "updateAsOut",
				"unAuditFun" => "updateAsAutiAudit"
			)
		);
		parent:: __construct();
	}

	//公司权限处理
	protected $_isSetCompany = 1;

	/**
	 * 修改调拨单时清单显示模板
	 */
	function showItemAtEdit($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ($rows as $key => $val) {
				$productCodeClass = "txtshort";
				$productNameClass = "txt";
				if ($val['relDocId'] > 0) {
					$productCodeClass = "readOnlyTxtItem";
					$productNameClass = "readOnlyTxtNormal";
				}
				$seNum = $i + 1;
				$str .= <<<EOT
                    <tr align="center" >
                        <td>
                            <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行"/>
                        </td>
                        <td>
                            $seNum
                        </td>
                        <td>
                            <input type="text" name="allocation[items][$i][productCode]" id="productCode$i" class="$productCodeClass" value="{$val['productCode']}" />
                            <input type="hidden" name="allocation[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
                            <input type="hidden" name="allocation[items][$i][id]" id="id$i" value="{$val['id']}"  />
                        </td>
                        <td>
                            <input type="text" name="allocation[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtItem" value="{$val['k3Code']}" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="allocation[items][$i][productName]" id="productName$i" class="$productNameClass" value="{$val['productName']}" />
                        </td>
                        <td>
                            <input type="text" name="allocation[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="{$val['pattern']}" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="allocation[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtItem" value="{$val['unitName']}" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="allocation[items][$i][allocatNum]"  id="allocatNum$i" class="txtshort" onfocus="exploreProTipInfo($i)" ondblclick="chooseSerialNo($i)" onblur="FloatMul('allocatNum$i','cost$i','subCost$i')" value="{$val['allocatNum']}" />
                        </td>
                        <td>
                            <input type="text" name="allocation[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','allocatNum$i','subCost$i')" value="{$val['cost']}" />
                        </td>
                        <td>
                            <input type="text" name="allocation[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoneySix" value="{$val['subCost']}" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="allocation[items][$i][exportStockName]" id="exportStockName$i" class="txtshort" value="{$val['exportStockName']}" />
                            <input type="hidden" name="allocation[items][$i][exportStockId]" id="exportStockId$i" value="{$val['exportStockId']}" />
                            <input type="hidden" name="allocation[items][$i][exportStockCode]"id="exportStockCode$i" value="{$val['exportStockCode']}" />
                            <input type="hidden" name="allocation[items][$i][relDocId]" id="relDocId$i"   value="{$val['relDocId']}" />
                            <input type="hidden" name="allocation[items][$i][relDocName]" id="relDocName$i"  value="{$val['relDocName']}"  />
                            <input type="hidden" name="allocation[items][$i][relCodeCode]" id="relCodeCode$i"  value="{$val['relDocCode']}"  />
                            <input type="hidden" name="allocation[items][$i][borrowItemId]" id="borrowItemId$i"  value="{$val['borrowItemId']}"  />
                        </td>
                        <td>
                            <input type="text" name="allocation[items][$i][importStockName]" id="importStockName$i" class="txtshort" value="{$val['importStockName']}" />
                            <input type="hidden" name="allocation[items][$i][importStockId]" id="importStockId$i" value="{$val['importStockId']}" />
                            <input type="hidden" name="allocation[items][$i][importStockCode]"id="importStockCode$i" value="{$val['importStockCode']}" />
                        </td>
                        <td>
                            <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo($i);" title="选择序列号">
                            <input type="hidden" name="allocation[items][$i][serialnoId]" id="serialnoId$i" value="{$val['serialnoId']}" />
                            <input type="text" name="allocation[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i" value="{$val['serialnoName']}"   />
                        </td>
                        <td>
                            <input type="text" name="allocation[items][$i][remark]" id="remark$i" class="txtshort" value="{$val['remark']}"  />
                        </td>
                        <td>
                            <input type="text" name="allocation[items][$i][validDate]" id="subPrice$i" onfocus="WdatePicker()" class="txtshort" value="{$val['validDate']}" />
                        </td>
                    </tr>
EOT;
				$i++;
			}
			return $str;
		}
	}

	/**
	 * 复制调拨单时清单显示模板
	 */
	function showItemAtCopy($allocationObj) {
		if ($allocationObj['items']) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串

			if ($allocationObj['contractType'] == "oa_borrow_borrow") {
				// 实例化序列号
				$serialNoDao = new model_stock_serialno_serialno();

				// 要返回的序列号Id
				$serialIdsCache = array();

				// 借用系列号指引
				$borrowSerialIds = array();

				// 非当前单据的明细
				$this->searchArr = array(
					'idNot' => $allocationObj['id'],
					'contractId' => $allocationObj['contractId'],
					'contractType' => $allocationObj['contractType'],
					'docStatus' => 'WSH'
				);
				$list = $this->listBySqlId('select_item');

				if ($list) {
					foreach ($list as $v) {
						if ($v['serialnoId']) {
							// 纯序列号缓存
							$serialIdsCache = array_merge($serialIdsCache, explode(",", $v['serialnoId']));

							// 缓存借用明细序列号
							$borrowSerialIds[$v['borrowItemId']] = isset($borrowSerialIds[$v['borrowItemId']]) ?
								array_merge($borrowSerialIds[$v['borrowItemId']], explode(",", $v['serialnoId'])) :
								explode(",", $v['serialnoId']);
						}
					}
				}
			}

			foreach ($allocationObj['items'] as $key => $val) {
				if ($allocationObj['contractType'] == "oa_borrow_borrow" && $val['serialnoId'] && isset($serialNoDao)
					&& isset($serialIdsCache) && isset($borrowSerialIds)) {
					// 返回可复制的序列号
					$serialNoArr = $serialNoDao->findCopySerialNo($allocationObj['contractId'],
						$allocationObj['contractType'], $val['borrowItemId'], $val['importStockId'], $val['serialnoId'],
						implode(",", $serialIdsCache));

					$serialIds = $serialNoArr['id'];
					$serialNos = $serialNoArr['no'];
					$serialDiff = $borrowSerialIds[$val['borrowItemId']];
					$ignoreSerialnoId = implode(",", $serialDiff);
				} else {
					$serialIds = $val['serialnoId'];
					$serialNos = $val['serialnoName'];
					$ignoreSerialnoId = "";
				}

				$seNum = $i + 1;
				$str .= <<<EOT
					<tr align="center" >
						<td>
							<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
						</td>
						<td>
							$seNum
						</td>
						<td>
							<input type="text" name="allocation[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="{$val['productCode']}" readonly/>
							<input type="hidden" name="allocation[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
						</td>
						<td>
							<input type="text" name="allocation[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtItem" value="{$val['k3Code']}" readonly/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="{$val['productName']}" readonly/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="{$val['pattern']}" readonly/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtItem" value="{$val['unitName']}" readonly/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][allocatNum]"  id="allocatNum$i" class="txtshort" onfocus="exploreProTipInfo($i)" ondblclick="chooseSerialNo($i)" onblur="FloatMul('allocatNum$i','cost$i','subCost$i')" value="{$val['allocatNum']}" />
						</td>
						<td>
							<input type="text" name="allocation[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','allocatNum$i','subCost$i')" value="{$val['cost']}" />
						</td>
						<td>
							<input type="text" name="allocation[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="{$val['subCost']}" readonly/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][exportStockName]" id="exportStockName$i" class="txtshort" value="{$val['exportStockName']}"/>
							<input type="hidden" name="allocation[items][$i][exportStockId]" id="exportStockId$i" value="{$val['exportStockId']}"/>
							<input type="hidden" name="allocation[items][$i][exportStockCode]"id="exportStockCode$i" value="{$val['exportStockCode']}"/>
							<input type="hidden" name="allocation[items][$i][relDocId]" id="relDocId$i"  value="{$val['id']}" />
							<input type="hidden" name="allocation[items][$i][relDocName]" id="relDocName$i"/>
							<input type="hidden" name="allocation[items][$i][relCodeCode]" id="relCodeCode$i"/>
							<input type="hidden" name="allocation[items][$i][borrowItemId]" id="borrowItemId$i" value="{$val['borrowItemId']}"/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][importStockName]" id="importStockName$i" class="txtshort" value="{$val['importStockName']}"/>
							<input type="hidden" name="allocation[items][$i][importStockId]" id="importStockId$i" value="{$val['importStockId']}" />
							<input type="hidden" name="allocation[items][$i][importStockCode]"id="importStockCode$i" value="{$val['importStockCode']}"/>
						</td>
						<td>
							<img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo($i);" title="选择序列号">
							<input type="hidden" id="ignoreSerialnoId$i" value="$ignoreSerialnoId" />
							<input type="hidden" name="allocation[items][$i][serialnoId]" id="serialnoId$i" value="$serialIds" />
							<input type="text" name="allocation[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i" value="$serialNos"/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][remark]" id="remark$i" class="txtshort" value="{$val['remark']}"/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][validDate]" id="subPrice$i" onfocus="WdatePicker()" class="txtshort" value="{$val['validDate']}" />
						</td>
					</tr>
EOT;
				$i++;
			}
			return $str;
		}
	}

	/**
	 * 查看调拨单时清单显示模板
	 */
	function showItemAtView($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ($rows as $key => $val) {
				$productNameArr = model_common_util:: subWordInArray($val['productName'], 20);
				$productNameStr = implode("<br />", $productNameArr);
				$seNum = $i + 1;
				$str .= <<<EOT
					<tr align="center" >
						<td>
							$seNum
						</td>
						<td>
							$val[productCode]
						</td>
						<td>
							$val[k3Code]
						</td>
						<td>
							$productNameStr
						</td>
						<td>
							$val[pattern]
						</td>
						<td>
							$val[unitName]
						</td>
						<td>
							$val[allocatNum]
						</td>
						<td class="formatMoneySix">
							$val[cost]
						</td>
						<td class="formatMoney">
							$val[subCost]
						</td>
						<td>
							$val[remark]
						</td>
						<td>
							$val[validDate]
						</td>
						<td>
							$val[exportStockName]
						</td>
						<td>
							$val[importStockName]
						</td>
						<td >
							<a href="?model=stock_allocation_allocation&action=toViewSerialno&serialnoName=$val[serialnoName]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=400" title="查看序列号信息" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="查看序列号" /></a>
						</td>
					</tr>
EOT;
				$i++;
			}
			return $str;
		}
	}

	/**
	 * 查看调拨单时清单显示模板
	 */
	function showItemAtPrint($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ($rows as $key => $val) {
				$productNameArr = model_common_util:: subWordInArray($val['productName'], 20);
				$productNameStr = implode("<br />", $productNameArr);
				$seNum = $i + 1;
				$str .= <<<EOT
					<tr align="center" >
<!--						<td>
							$seNum
						</td>-->
						<td>
							$val[productCode]
						    <input type="hidden" id="productCode$i" value="$val[productCode]">
                            <input type="hidden" id="productName$i" value="$val[productName]">
                            <input type="hidden" id="unitName$i" value="$val[unitName]">
                            <input type="hidden" id="pattern$i" value="$val[pattern]">
                            <input type="hidden" id="actOutNum$i" value="$val[allocatNum]">
						</td>
<!--						<td>
							$val[k3Code]
						</td>-->
						<td>
							$productNameStr
						</td>
<!--						<td>
							$val[pattern]
						</td>
						<td>
							$val[unitName]
						</td>-->
						<td>
							$val[allocatNum]
						</td>
						<td class="formatMoneySix">
							$val[cost]
						</td>
						<td class="formatMoney">
							$val[subCost]
						</td>
<!--						<td>
							$val[remark]
						</td>
						<td>
							$val[validDate]
						</td>
						<td>
							$val[exportStockName]
						</td>
						<td>
							$val[importStockName]
						</td>
						<td >
							<a href="?model=stock_allocation_allocation&action=toViewSerialno&serialnoName=$val[serialnoName]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=400" title="查看序列号信息" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="查看序列号" /></a>
						</td>-->
					</tr>
EOT;
				$i++;
			}
			return $str;
		}
	}


	/*
	 * 序列号详细页面
	 */
	function showSerialno($serialnoNameStr) {
		if ($serialnoNameStr) {
			$i = 1;
			$str = "";
			$serialnoNameArr = explode(",", $serialnoNameStr);

			foreach ($serialnoNameArr as $key => $val) {
				$str .= <<<EOT
					<tr align="center" >
						<td>
							$i
						</td>
						<td>
							$val
						</td>
					</tr>
EOT;
				$i++;
			}
			return $str;
		} else {
			return '<tr align="center" ><td colspan="2">没有相关信息</td></tr>';
		}
	}

	/**
	 * 新增调拨单
	 * @param $object
	 * @return bool
	 * @throws Exception
	 */
	function add_d($object) {
		try {
			$this->start_d();
			if (is_array($object['items'])) {
				$codeDao = new model_common_codeRule();
				$object['docCode'] = $codeDao->stockCode("oa_stock_allocation", "CHG");
				$id = parent::add_d($object, true);

                //插入操作记录
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->addObjLog($this->tbl_name, $id, $object,
                    $object['docStatus'] == "YSH" ? '审核调拨' : '新增调拨');

				//保存调拨单物料清单
				$allocationitemDao = new model_stock_allocation_allocationitem();
				$itemsArr = $this->setItemMainId("mainId", $id, $object['items']);
				$itemsObj = $allocationitemDao->saveDelBatch($itemsArr);

				if ($object['docStatus'] == "YSH") {
					$inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //库存DAO
					$serialnoDao = new model_stock_serialno_serialno(); //序列号DAO
					$lockDao = new model_stock_lock_lock(); //锁DAO
					$borrowInitialDao = new model_projectmanagent_borrow_borrow(); //借试用归还

					$append = '';
					foreach ($itemsObj as $key => $itemObj) {
						//更新库存
						$exportParamArr = array(
							"stockId" => $itemObj['exportStockId'],
							"productId" => $itemObj['productId']
						);
						$importParamArr = array(
							"stockId" => $itemObj['importStockId'],
							"productId" => $itemObj['productId']
						);
						$inventoryDao->updateInTimeInfo($exportParamArr, $itemObj['allocatNum'], "outstock"); //调出
						$inventoryDao->updateInTimeInfo($importParamArr, $itemObj['allocatNum'], "instock"); //调入

						//出库单物料序列号从已出库变为库存中
						if (!empty ($itemObj['serialnoId'])) {
							$sequenceId = $itemObj['serialnoId'];

							$contractType = $object['contractType'];
							$contractId = $object['contractId'];
							$contractCode = $object['contractCode'];
							if ($object['relDocType'] == "DBDYDLXGH") { //源单据为归还的需要清空序列号源单信息
								$contractType = '';
								$contractId = '';
								$contractCode = '';
							}
							$sequencObj = array(
								"stockId" => $itemObj['importStockId'],
								"stockName" => $itemObj['importStockName'],
								"stockCode" => $itemObj['importStockCode'],
								"relDocType" => $contractType,
								"relDocItemId" => $itemObj['borrowItemId'],
								"relDocId" => $contractId,
								"relDocCode" => $contractCode
							);
							$serialnoDao->update("id in($sequenceId)", $sequencObj);
						}
						/*start:处理关联单据,处理锁定解锁*/
						$relDocModelInfo = $this->relDocTypeArr[$object['relDocType']];
						if ($relDocModelInfo && $itemObj['relDocId']) {
							$relDocDao = new $relDocModelInfo['mainModel'] ();
							$relDocDaoFun = $relDocModelInfo['dealMainFun'];
							$relDocItemArr = array(
								"relDocId" => $object['relDocId'],
								"relDocItemId" => $itemObj['relDocId'],
								"productId" => $itemObj['productId'],
								"outNum" => $itemObj['allocatNum']
							);
							$relDocDao->$relDocDaoFun($relDocItemArr);

							//判断是否为海外借用归还，是则需要反写数据到海外ERP
                            /**$object['relDocType'] == "DBDYDLXGH" && */
							if ($object['contractType'] == 'oa_borrow_borrow' && $object['customerId'] == '1058') {
								$sql = "update overseas_borrow_material set backNum = backNum + '" . $itemObj['allocatNum'] . "' where borrowCode = '" . $object['contractCode'] . "' and materialId = '" . $itemObj['productId'] . "' and purType=2";
								$this->connectSql($sql);
								$this->updateBackStatus($object['contractCode']); //更新海外借用归还状态
							}

							if ($object['relDocType'] == "DBDYDLXFH") { //源单据为发货计划的需要处理锁定信息
								$lockNum = $relDocDao->findLockNum($object['relDocId'], $itemObj['exportStockId'], $itemObj['productId']);

								if (!empty ($lockNum) && $lockNum > 0) {
									if ($itemObj['allocatNum'] >= $lockNum) { //出库数量大于锁数量
										$releaseObj = array(
											"outDocId" => $id,
											"planId" => $object['relDocId'],
											"relDocItemId" => $itemObj['relDocId'],
											"stockId" => $itemObj['exportStockId'],
											"productId" => $itemObj['productId'],
											"lockNum" => $lockNum
										);
									} else { //出库数量小于锁数量
										$releaseObj = array(
											"outDocId" => $id,
											"planId" => $object['relDocId'],
											"relDocItemId" => $itemObj['relDocId'],
											"stockId" => $itemObj['exportStockId'],
											"productId" => $itemObj['productId'],
											"lockNum" => $itemObj['allocatNum']
										);
									}
									$lockDao->releaseLockByOutPlan($releaseObj);
								}
								//判断是否为海外借用，是则需要反写数据到海外ERP
								if ($object['contractType'] == 'oa_borrow_borrow' && $object['customerId'] == '1058') {
									$sql = "update overseas_borrow_material set executedNum = executedNum + '" . $itemObj['allocatNum'] . "' where borrowCode = '" . $object['contractCode'] . "' and materialId = '" . $itemObj['productId'] . "' and purType=2";
									$this->connectSql($sql);
									$this->updateDeliveryStatus($object['contractCode']); //更新海外借用发货状态
								}
							}
							if ($object['relDocType'] == "DBDYDLXJY") { //源单据个人借用的需要处理锁定信息
								$lockNum = $relDocDao->findLockNum($object['relDocId'], $itemObj['exportStockId'], $itemObj['productId']);

								if (!empty ($lockNum) && $lockNum > 0) {
									if ($itemObj['allocatNum'] >= $lockNum) { //出库数量大于锁数量
										$releaseObj = array(
											"outDocId" => $id,
											"borrowId" => $object['relDocId'],
											"relDocItemId" => $itemObj['relDocId'],
											"stockId" => $itemObj['exportStockId'],
											"productId" => $itemObj['productId'],
											"lockNum" => $lockNum
										);
									} else { //出库数量小于锁数量
										$releaseObj = array(
											"outDocId" => $id,
											"borrowId" => $object['relDocId'],
											"relDocItemId" => $itemObj['relDocId'],
											"stockId" => $itemObj['exportStockId'],
											"productId" => $itemObj['productId'],
											"lockNum" => $itemObj['allocatNum']
										);
									}
									$lockDao->releaseLockByPersonBorrow($releaseObj);
								}
							}
						}
						/*end:处理关联单据,处理锁定解锁*/

						/*s:----------------处理初始化归还----------------*/
						if ($object['toUse'] == "CHUKUGUIH" && empty ($object['relDocId'])) {
							$borrowInitialDao->updateInitialzeRelation($id, $object['docCode'], $object['pickCode'], $itemObj['productId'], $itemObj['allocatNum'], $object['customerId']);
						}
						/*e:----------------处理初始化归还----------------*/
					}
					/*start:发送邮件 ,当操作为提交时才发送*/
					$emailArr = $object['email'];
					if ($emailArr['issend'] == 'y' && !empty ($emailArr['TO_ID'])) {
						$tipMsg = "已借用了如下物料,请提醒在规定时间内归还，谢谢";
						if ($object['toUse'] == "CHUKUGUIH")
							$tipMsg = "已归还了如下物料,<b>请收到邮件时,请于2天内回复,2天不回当默认.</b>";

						//add chenrf 20130605邮件增加源单类型、源单编号、借试用申请编号

						$append .= "<td>源单类型: </td><td>{$object['relDocType']}</td><td>源单编号: </td><td>{$object['relDocCode']}</td><td>借试用申请编号: </td><td>{$object['contractCode']}</td>";

						$sendContent = "各位好:<br/>" . $object['deptName'] . "部门的" . $object['pickName'] . $tipMsg . ".<br/>";
						$sendContent .= "<table><tr><td>单据编号:</td><td>" . $object['docCode'] . "</td><td>客户单位名称:</td><td>" . $object['customerName'] . "</td></tr>
																				   <tr><td>借出开始日期:</td><td>" . $object['outStartDate'] . "</td><td>借出结束日期:</td><td>" . $object['outEndDate'] . "</td>{$append}</tr></table>";

						$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料编号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>数量</b></td><td><b>序列号</b></td></tr>";
						$seNum = 1;
						foreach ($itemsObj as $key => $kValue) {
							$sendContent .= <<<EOT
								<tr align="center" ><td>$seNum</td><td>$kValue[productCode]</td><td>$kValue[productName]</td><td>$kValue[pattern]</td><td>$kValue[allocatNum]</td><td>$kValue[serialnoName]</td></tr>
EOT;
							$seNum++;
						}
						$sendContent .= "</table>";
						$emailDao = new model_common_mail();
						$emailDao->mailClear($object['pickName'] . "物料借用归还", $emailArr['TO_ID'], $sendContent);

					}
					/*end:发送邮件 ,当操作为提交时才发送*/
				}
				$this->commit_d();
				return $id;
			} else {
				throw new Exception("单据信息不完整!");
			}
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 *修改调拨单
	 * @see model_base::add_d()
	 */
	function edit_d($object) {
        // 获取旧的入库单信息
        $org = parent::get_d($object['id']);

		try {
			$this->start_d();
			if (is_array($object['items'])) {
				$editResult = parent:: edit_d($object, true);

                //插入操作记录
                $logSettringDao = new model_syslog_setting_logsetting();
                $logSettringDao->compareModelObj($this->tbl_name, $org, $object,
                    $object['docStatus'] == "YSH" ? '审核调拨' : '修改调拨');

				//保存调拨单物料清单
				$allocationitemDao = new model_stock_allocation_allocationitem();
				$itemsArr = $this->setItemMainId("mainId", $object['id'], $object['items']);
				$itemsObj = $allocationitemDao->saveDelBatch($itemsArr);

				if ($object['docStatus'] == "YSH") {
					$inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //库存DAO
					$serialnoDao = new model_stock_serialno_serialno(); //序列号DAO
					$lockDao = new model_stock_lock_lock(); //锁DAO
					$borrowInitialDao = new model_projectmanagent_borrow_borrow(); //借试用归还

					foreach ($itemsObj as $key => $itemObj) {
						$exportParamArr = array(
							"stockId" => $itemObj['exportStockId'],
							"productId" => $itemObj['productId']
						);
						$importParamArr = array(
							"stockId" => $itemObj['importStockId'],
							"productId" => $itemObj['productId']
						);
						$inventoryDao->updateInTimeInfo($exportParamArr, $itemObj['allocatNum'], "outstock"); //调出
						$inventoryDao->updateInTimeInfo($importParamArr, $itemObj['allocatNum'], "instock"); //调入

						//出库单物料序列号从已出库变为库存中
						if (!empty ($itemObj['serialnoId'])) {
							$sequenceId = $itemObj['serialnoId'];
							$sequencObj = array(
								"stockId" => $itemObj['importStockId'],
								"stockName" => $itemObj['importStockName'],
								"stockCode" => $itemObj['importStockCode'],
								"relDocType" => $object['contractType'],
								"relDocItemId" => $itemObj['borrowItemId'],
								"relDocId" => $object['contractId'],
								"relDocCode" => $object['contractCode']
							);
							$serialnoDao->update("id in($sequenceId)", $sequencObj);
						}

						/*start:处理关联单据,处理锁定解锁*/
						$relDocModelInfo = $this->relDocTypeArr[$object['relDocType']];
						if ($relDocModelInfo && $itemObj['relDocId']) {
							$relDocDao = new $relDocModelInfo['mainModel'] ();
							$relDocDaoFun = $relDocModelInfo['dealMainFun'];
							$relDocItemArr = array(
								"relDocId" => $object['relDocId'],
								"relDocItemId" => $itemObj['relDocId'],
								"productId" => $itemObj['productId'],
								"outNum" => $itemObj['allocatNum']
							);
							$relDocDao->$relDocDaoFun($relDocItemArr);

							if ($object['relDocType'] == "DBDYDLXFH") { //源单据为发货计划的需要处理锁定信息
								$lockNum = $relDocDao->findLockNum($object['relDocId'], $itemObj['exportStockId'], $itemObj['productId']);
								//echo "=======".$lockNum;
								if (!empty ($lockNum) && $lockNum > 0) {
									if ($itemObj['allocatNum'] >= $lockNum) { //出库数量大于锁数量
										$releaseObj = array(
											"outDocId" => $object['id'],
											"planId" => $object['relDocId'],
											"relDocItemId" => $itemObj['relDocId'],
											"stockId" => $itemObj['exportStockId'],
											"productId" => $itemObj['productId'],
											"lockNum" => $lockNum
										);
									} else { //出库数量小于锁数量
										$releaseObj = array(
											"outDocId" => $object['id'],
											"planId" => $object['relDocId'],
											"relDocItemId" => $itemObj['relDocId'],
											"stockId" => $itemObj['exportStockId'],
											"productId" => $itemObj['productId'],
											"lockNum" => $itemObj['allocatNum']
										);
									}
									$lockDao->releaseLockByOutPlan($releaseObj);
								}
							}
							if ($object['relDocType'] == "DBDYDLXJY") { //源单据个人借用的需要处理锁定信息
								$lockNum = $relDocDao->findLockNum($object['relDocId'], $itemObj['exportStockId'], $itemObj['productId']);

								if (!empty ($lockNum) && $lockNum > 0) {
									if ($itemObj['allocatNum'] >= $lockNum) { //出库数量大于锁数量
										$releaseObj = array(
											"outDocId" => $object['id'],
											"borrowId" => $object['relDocId'],
											"relDocItemId" => $itemObj['relDocId'],
											"stockId" => $itemObj['exportStockId'],
											"productId" => $itemObj['productId'],
											"lockNum" => $lockNum
										);
									} else { //出库数量小于锁数量
										$releaseObj = array(
											"outDocId" => $object['id'],
											"borrowId" => $object['relDocId'],
											"relDocItemId" => $itemObj['relDocId'],
											"stockId" => $itemObj['exportStockId'],
											"productId" => $itemObj['productId'],
											"lockNum" => $itemObj['allocatNum']
										);
									}
									$lockDao->releaseLockByPersonBorrow($releaseObj);
								}
							}
						}
						/*end:处理关联单据,处理锁定解锁*/
						/*s:----------------处理初始化归还----------------*/
						if ($object['toUse'] == "CHUKUGUIH" && empty($object['relDocId'])) {
							$borrowInitialDao->updateInitialzeRelation($object['id'], $object['docCode'], $object['pickCode'], $itemObj['productId'], $itemObj['allocatNum'], $object['customerId']);
						}
						/*e:----------------处理初始化归还----------------*/
					}
					/*start:发送邮件 ,当操作为提交时才发送*/
					$emailArr = $object['email'];
					if ($emailArr['issend'] == 'y' && !empty ($emailArr['TO_ID'])) {
						$tipMsg = "已借用了如下物料,请提醒在规定时间内归还,谢谢";
						if ($object['toUse'] == "CHUKUGUIH")
							$tipMsg = "已归还了如下物料,<b>请收到邮件时,请于2天内回复,2天不回当默认.</b>";

						//add chenrf 20130606邮件增加源单类型、源单编号、借试用申请编号

						$append = "<td>源单类型: </td><td>{$object['relDocType']}</td><td>源单编号: </td><td>{$object['relDocCode']}</td><td>借试用申请编号: </td><td>{$object['contractCode']}</td>";

						$sendContent = "各位好:<br/>" . $object['deptName'] . "部门的" . $object['pickName'] . $tipMsg . ".<br/>";
						$sendContent .= "<table><tr><td>单据编号:</td><td>" . $object['docCode'] . "</td><td>客户单位名称:</td><td>" . $object['customerName'] . "</td></tr>
																				   <tr><td>借出开始日期:</td><td>" . $object['outStartDate'] . "</td><td>借出结束日期:</td><td>" . $object['outEndDate'] . "</td>{$append}</tr></table>";

						$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料编号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>数量</b></td><td><b>序列号</b></td></tr>";
						$seNum = 1;
						foreach ($itemsObj as $key => $kValue) {
							$sendContent .= <<<EOT
						<tr align="center" >
							<td>$seNum</td>
							<td>$kValue[productCode]</td>
							<td>$kValue[productName]</td>
							<td>$kValue[pattern]</td>
							<td>$kValue[allocatNum]</td>
							<td>$kValue[serialnoName]</td>
						</tr>
EOT;
							$seNum++;
						}
						$sendContent .= "</table>";
						$emailDao = new model_common_mail();
						$emailDao->mailClear($object['pickName'] . "物料借用归还", $emailArr['TO_ID'], $sendContent);

					}
					/*end:发送邮件 ,当操作为提交时才发送*/
				}
				$this->commit_d();
				return $editResult;
			} else {
				throw new Exception("单据信息不完整!");
			}

		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

    /**
     * 批量删除对象
     */
    function deletes_d($id)
    {
        // 获取旧的入库单信息
        $org = parent::get_d($id);

        try {
            $this->deletes($id);

            //插入操作记录
            $logSettringDao = new model_syslog_setting_logsetting();
            $logSettringDao->deleteObjLog($this->tbl_name, $org);

            return true;
        } catch(Exception $e) {
            throw $e;
        }
    }

	/**
	 * 调拨单反审核进行业务处理
	 * @param $id
	 */
	function cancelAudit($id) {
		try {
			$this->start_d();
			$allocationObj = $this->get_d($id);
			if ($allocationObj['docStatus'] == "YSH") {
				$allocationObj['docStatus'] = "WSH";
				$this->updateById($allocationObj);
				if (is_array($allocationObj['items'])) {
					$inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //库存DAO
					$serialnoDao = new model_stock_serialno_serialno(); //序列号DAO
					$borrowInitialDao = new model_projectmanagent_borrow_borrow(); //借试用归还

					foreach ($allocationObj['items'] as $itemObj) {
						//还原库存
						$exportParamArr = array(
							"stockId" => $itemObj['exportStockId'],
							"productId" => $itemObj['productId']
						);
						$importParamArr = array(
							"stockId" => $itemObj['importStockId'],
							"productId" => $itemObj['productId']
						);
						$inventoryDao->updateInTimeInfo($exportParamArr, $itemObj['allocatNum'], "instock"); //调出
						$inventoryDao->updateInTimeInfo($importParamArr, $itemObj['allocatNum'], "outstock"); //调入

						//出库单物料序列号从已出库变为库存中
						if (!empty ($itemObj['serialnoId'])) {
							$sequenceId = $itemObj['serialnoId'];

							$sequencObj = array(
								"stockId" => $itemObj['exportStockId'],
								"stockName" => $itemObj['exportStockName'],
								"stockCode" => $itemObj['exportStockCode'],
								"relDocType" => "",
								"relDocCode" => "",
								"relDocId" => "",
								"relDocItemId" => "",
							);
							$serialnoDao->update("id in($sequenceId)", $sequencObj);
						}
						//还原关联业务
						$relDocModelInfo = $this->relDocTypeArr[$allocationObj['relDocType']];
						if ($relDocModelInfo && $itemObj['relDocId']) {
							$relDocDao = new $relDocModelInfo['mainModel'] ();
							$relDocDaoFun = $relDocModelInfo['unAuditFun'];

							$relDocItemArr = array(
								"relDocId" => $allocationObj['relDocId'],
								"relDocItemId" => $itemObj['relDocId'],
								"productId" => $itemObj['productId'],
								"outNum" => $itemObj['allocatNum']
							);
							$relDocDao->$relDocDaoFun ($relDocItemArr);
						}
						/*s:----------------处理初始化归还----------------*/
						if ($allocationObj['toUse'] == "CHUKUGUIH" && empty ($allocationObj['relDocId'])) {
							$borrowInitialDao->autiAuditDelInitialzeRelation($allocationObj['id']);
						}
						/*e:----------------处理初始化归还----------------*/
					}

				}

				if ($allocationObj['relDocType'] == "DBDYDLXFH" || $allocationObj['relDocType'] == "DBDYDLXJY") { //源单据为发货计划或者个人借试用的需要处理锁定信息
					/*start:删除对应出库释放锁定记录及增加即时库存的锁定数量*/
					$lockDao = new model_stock_lock_lock();
					if ($allocationObj['relDocType'] == "DBDYDLXFH") {
						$salePlanDao = new model_stock_outplan_outplan();
						$salePlanObj = $salePlanDao->get_d($allocationObj['relDocId']);
					} else { //个人借试用
						$salePlanObj['docType'] = "oa_borrow_borrow";
					}
					//根据出库单id及发货计划关联的源单类型查找出库的负数量锁定记录
					$lockSearchArr = array(
						"outStockDocId" => $allocationObj['id'],
						"objType" => $salePlanObj['docType']
					);
					$lockDao->searchArr = $lockSearchArr;
					$relLockArr = $lockDao->list_d();
					if (is_array($relLockArr)) {
						foreach ($relLockArr as $key => $relLockObj) {
							$invSearchArr = array(
								"stockId" => $relLockObj['stockId'],
								"productId" => $relLockObj['productId']
							);
							$inventoryDao->searchArr = $invSearchArr;
							$inventoryArr = $inventoryDao->list_d();
							if (is_array($inventoryArr)) {
								$inventoryObj = $inventoryArr[0];
								$inventoryObj['lockedNum'] = $inventoryObj['lockedNum'] - $relLockObj['lockNum'];
								$inventoryObj['exeNum'] = $inventoryObj['exeNum'] + $relLockObj['lockNum'];
								$inventoryDao->updateById($inventoryObj);
							}

						}
					}
					$lockDao->delete($lockSearchArr);
					/*end:删除对应出库释放锁定记录及增加即时库存的锁定数量*/
				}

                //插入操作记录
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->addObjLog($this->tbl_name, $id, array(), '取消审核');

				$this->commit_d();
				return true;
			} else {
				throw new Exception("单据信息有误，请确认！");
			}
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 获取未归还的物料信息
	 */
	function getNotBackItem() {
		$resultArr = array();
		//有序列号未归还的物料信息
		$serialnoDao = new model_stock_serialno_serialno();
		$serialnoDao->searchArr = array(
			"stockId" => "-1"
		);
		$serialnoArr = $serialnoDao->listBySqlId(); //借出去的序列号
		foreach ($serialnoArr as $key => $serialnoObj) {
			$this->searchArr = array(
				"productId" => $serialnoObj['productId'],
				"serialnoName" => $serialnoObj['sequence'],
				"toUseArr" => array(
					'CHUKUJY',
					'CHUKUWX',
					'CHUKUSY'
				)
			);
			$this->sort = "c.id";
			$this->asc = true;
			$notBackArr = $this->listBySqlId("select_item");

			if (is_array($notBackArr)) {
				$notBackArr[0]['proNum'] = "1";
				$notBackArr[0]['serialnoName'] = $serialnoObj['sequence'];
				array_push($resultArr, $notBackArr[0]);
			}
		}

		//没有序列号的借出物料信息
		$lendSql = "select a.pickName,ai.productCode,ai.productName,sum(ai.allocatNum) as outNum  from  oa_stock_allocation a  left join  oa_stock_allocation_item  ai on(ai.mainId=a.id)    where ai.serialnoName=''  and a.toUse in('CHUKUJY','CHUKUWX','CHUKUSY')  group by ai.productCode,ai.productName,a.pickName";
		$this->searchArr = null;
		$this->sort = "a.id";
		$lendArr = $this->listBySql($lendSql);

		$backSql = "select a.pickName,ai.productCode,ai.productName,sum(ai.allocatNum) as inNum  from  oa_stock_allocation a  left join  oa_stock_allocation_item  ai on(ai.mainId=a.id)    where ai.serialnoName=''  and a.toUse in('CHUKUGUIH')  group by ai.productCode,ai.productName,a.pickName";
		$backArr = $this->query($backSql);

		$bakNameArr = array();
		foreach ($backArr as $key => $backObj) { //已归还的没有序列号的信息
			$nameKey = $backObj['pickName'] . "-" . $backObj['productCode'];
			$bakNameArr[$nameKey] = $backObj;
		}

		foreach ($lendArr as $key => $lendObj) { //借出去的东西跟减去未归还的物料就是未归还的
			$nameKey = $lendObj['pickName'] . "-" . $lendObj['productCode'];
			if (isset ($bakNameArr[$nameKey])) {
				$lendObj['proNum'] = $lendObj['outNum'] - $bakNameArr[$nameKey]['inNum'];
			} else {
				$lendObj['proNum'] = $lendObj['outNum'];
			}
			array_push($resultArr, $lendObj);
		}

		return $resultArr;
	}

	/**
	 *
	 * 通过源单去匹配查找未归还信息
	 */
	function getNotBackWithRelDoc() {
		set_time_limit(0);
		//借出
		$this->searchArr = array(
			"toUseArr" => array(
				'CHUKUJY',
				'CHUKUWX',
				'CHUKUSY'
			),
			"importStockIdArr" => "-1,3",
			"docStatus" => 'YSH'
		);
		$this->sort = "c.pickName,c.id";
		$this->asc = false;
		$lentItemArr = $this->listBySqlId("select_item"); //借出物料清单
		$lentItemDocIdArr = array(); //借出物料单据id数组
		$lentItemIdProIdKeyArr = array(); //借出物料单据id-productId key数组
		foreach ($lentItemArr as $key => $lentItem) {
			array_push($lentItemDocIdArr, $lentItem['id']);

			$idProIdKey = $lentItem['id'] . "-" . $lentItem['productId'];
			$lentItemIdProIdKeyArr[$idProIdKey]['docId'] = $lentItem['id']; //主表单据id
			$lentItemIdProIdKeyArr[$idProIdKey]['productCode'] = $lentItem['productCode'];
			$lentItemIdProIdKeyArr[$idProIdKey]['productName'] = $lentItem['productName'];
			$lentItemIdProIdKeyArr[$idProIdKey]['pattern'] = $lentItem['pattern'];
			$lentItemIdProIdKeyArr[$idProIdKey]['docCode'] = $lentItem['docCode'];
			$lentItemIdProIdKeyArr[$idProIdKey]['customerName'] = $lentItem['customerName'];
			$lentItemIdProIdKeyArr[$idProIdKey]['deptName'] = $lentItem['deptName'];
			$lentItemIdProIdKeyArr[$idProIdKey]['pickName'] = $lentItem['pickName'];
			$lentItemIdProIdKeyArr[$idProIdKey]['outStartDate'] = $lentItem['outStartDate'];
			$lentItemIdProIdKeyArr[$idProIdKey]['outEndDate'] = $lentItem['outEndDate'];

			if (isset ($lentItemIdProIdKeyArr[$idProIdKey])) {
				$lentItemIdProIdKeyArr[$idProIdKey]['pNum'] += $lentItem['allocatNum'];
				if (!empty ($lentItem['serialnoName'])) {
					if (!empty ($lentItemIdProIdKeyArr[$idProIdKey]['lserialnoName'])) {
						$lentItemIdProIdKeyArr[$idProIdKey]['lserialnoName'] .= "," . $lentItem['serialnoName'];
					} else {
						$lentItemIdProIdKeyArr[$idProIdKey]['lserialnoName'] .= $lentItem['serialnoName'];
					}
				}

			} else {
				$lentItemIdProIdKeyArr[$idProIdKey]['pNum'] = $lentItem['allocatNum'];
				$lentItemIdProIdKeyArr[$idProIdKey]['lserialnoName'] = $lentItem['serialnoName'];
			}
		}

		//归还
		$allocationItemDao = new model_stock_allocation_allocationitem();
		$allocationItemDao->searchArr = array(
			'relDocIdArr' => $lentItemDocIdArr,
			'relDocType' => "DBDYDLXDB"
		);
		$allocationItemDao->sort = "c.id";
		$relDocBackItemArr = $allocationItemDao->listBySqlId(); //归还物料

		foreach ($relDocBackItemArr as $key => $relDocBackItem) {
			$backDocProIdKey = $relDocBackItem['relDocId'] . "-" . $relDocBackItem['productId'];
			if (isset ($lentItemIdProIdKeyArr[$backDocProIdKey])) {
				$lentItemIdProIdKeyArr[$backDocProIdKey]['pNum'] -= $relDocBackItem['allocatNum'];
				if (!empty ($relDocBackItem['serialnoName'])) {
					if (!empty ($lentItemIdProIdKeyArr[$backDocProIdKey]['bserialnoName'])) {
						$lentItemIdProIdKeyArr[$backDocProIdKey]['bserialnoName'] .= "," . $relDocBackItem['serialnoName'];
					} else {
						$lentItemIdProIdKeyArr[$backDocProIdKey]['bserialnoName'] .= $relDocBackItem['serialnoName'];
					}
				}

			}
		}

		//借用_归还结果
		$resultArr = array();
		foreach ($lentItemIdProIdKeyArr as $key => $result) {
			if ($result['pNum'] > 0) {
				array_push($resultArr, $result);
			}
		}

		return $resultArr;
	}

	/**
	 * 根据主表id获取调拨单详细信息
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$allocationObj = parent:: get_d($id);
		$allocationitemDao = new model_stock_allocation_allocationitem();
		$allocationitemDao->searchArr = array(
			"mainId" => $id
		);
		$allocationitemDao->asc = true;
		$allocationObj['items'] = $allocationitemDao->listBySqlId();
		return $allocationObj;
	}

	/**
	 * 设置关联从表的主表ID值
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array();
		foreach ($iteminfoArr as $key => $value) {
			$value[$mainIdName] = $mainIdValue;
			array_push($resultArr, $value);
		}
		return $resultArr;
	}

	/**
	 *
	 * 获取借用申请清单的未归还数量
	 * @param 申请单id $applyDocId
	 * @param 产品id $productId
	 * @param 申请单类型 $applyType
	 */
	function getApplyDocNotBackNum($applyDocId, $productId, $applyType) {
		$lendApplyDocCon = "";
		$backApplyDocCon = "";

		if ($applyType == "DBDYDLXJY") { //个人借试用
			$lendApplyDocCon = " a.relDocId=$applyDocId";
		} else
			if ($applyType == "DBDYDLXFH") { //客户借试用
				$lendApplyDocCon = " a.contractId=$applyDocId";

			}
		$this->sort = " a.id";
		$lendSql = "select ai.mainId,ai.allocatNum from oa_stock_allocation a inner join oa_stock_allocation_item ai on(ai.mainId=a.id) where a.docStatus='YSH' and $lendApplyDocCon and a.relDocType='$applyType' and ai.productId=$productId and a.toUse!='CHUKUGUIH'";
		$lendArr = $this->listBySql($lendSql);
		$lendNum = 0;
		$idArr = array();
		if (is_array($lendArr)) {
			foreach ($lendArr as $key => $value) {
				$lendNum += $value['allocatNum'];
				array_push($idArr, $value['mainId']);
			}
		}

		if (count($idArr) >= 1) {
			$idStr = implode($idArr, ",");
			if ($applyType == "DBDYDLXJY") { //个人借试用
				$backApplyDocCon = " a.relDocId in($idStr)";
			} else
				if ($applyType == "DBDYDLXFH") { //客户借试用
					$backApplyDocCon = " a.contractId in($idStr)";
				}
			$backSql = "select ai.mainId,ai.allocatNum  from oa_stock_allocation a inner join oa_stock_allocation_item ai on(ai.mainId=a.id) where a.docStatus='YSH' and $backApplyDocCon and ai.productId=$productId and a.toUse='CHUKUGUIH'";

			$backArr = $this->listBySql($backSql);
			$backNum = 0;
			foreach ($backArr as $key => $value) {
				$backNum += $value['allocatNum'];
			}
			return $lendNum - backNum;
		} else {
			return $lendNum;
		}
	}

	/**
	 *
	 * 通过源单类型查找借出调拨单
	 * @param 源单类型 $relDocType
	 * @param 源单id $relDocId
	 */
	function findLendDoc($relDocType, $relDocId) {
		$resultArr = array();
		if ("DBDYDLXJY" == $relDocType) {
			$this->searchArr = array(
				"relDocType" => $relDocType,
				"relDocId" => $relDocId,
				"notToUse" => "CHUKUGUIH"
			);
			$lendArr = $this->listBySqlId();
		} else
			if ("DBDYDLXFH" == $relDocType) {
				$this->searchArr = array(
					"relDocType" => $relDocType,
					"contractId" => $relDocId,
					"notToUse" => "CHUKUGUIH"
				);
				$lendArr = $this->listBySqlId();
			}
		if (is_array($lendArr)) {
			$allocationItemDao = new model_stock_allocation_allocationitem();
			foreach ($lendArr as $key => $value) {
				$allocationItemDao->searchArr['mainId'] = $value['id'];
				$allocationItemArr = $allocationItemDao->listBySqlId();
				$value['items'] = $allocationItemArr;
				array_push($resultArr, $value);
			}
		}

		return $resultArr;
	}

	/**
	 *
	 * 根据借出调拨单自动生成归还调拨单
	 * @param  借出单id $docId
	 * @param 归还物料信息 $productArr [0] =array("productId","productNum","serialnoId","serialnoName");
	 */
	function addAllocationAuto($docId, $productArr) {
		$lendObj = $this->get_d($docId);
		$backObj = $lendObj; //需要生成的归还调拨单

		//拼装主表信息
		unset ($backObj['items']);
		unset ($backObj['id']);
		$backObj["auditDate"] = day_date;
		$backObj["toUse"] = "CHUKUGUIH";
		$backObj["outEndDate"] = day_date;
		$backObj["exportStockName"] = $lendObj['importStockName'];
		$backObj["exportStockCode"] = $lendObj['importStockCode'];
		$backObj["exportStockId"] = $lendObj['importStockId'];
		$backObj["importStockName"] = $lendObj['exportStockName'];
		$backObj["importStockCode"] = $lendObj['exportStockCode'];
		$backObj["importStockId"] = $lendObj['exportStockId'];
		$backObj["relDocType"] = 'DBDYDLXDB';

		//拼装从表信息
		foreach ($productArr as $key => $productObj) {
			$backObjItemTemp = array();
			$backObjItem = array();
			foreach ($lendObj['items'] as $key => $lendItem) {
				if ($lendItem['productId'] == $productObj['productId']) {
					if (!empty ($productObj['serialnoId'])) { //如果存在序列号用序列号进行匹配借出产品
						if (strpos($lendItem['serialnoId'], $productObj['serialnoId']))
							$backObjItemTemp = $lendItem;
						break;
					} else {
						$backObjItemTemp = $lendItem;
						break;
					}
				}
			}

			if (count($backObjItemTemp) > 0) {
				unset ($backObjItem['id']);
				$backObjItem = $backObjItemTemp;
				$backObjItem['exportStockName'] = $backObjItemTemp['importStockName'];
				$backObjItem['exportStockId'] = $backObjItemTemp['importStockId'];
				$backObjItem['exportStockCode'] = $backObjItemTemp['importStockCode'];
				$backObjItem['importStockName'] = $backObjItemTemp['exportStockName'];
				$backObjItem['importStockId'] = $backObjItemTemp['exportStockId'];
				$backObjItem['importStockCode'] = $backObjItemTemp['exportStockCode'];
				$backObjItem['serialnoId'] = $productObj['serialnoId'];
				$backObjItem['serialnoName'] = $productObj['serialnoName'];
				$backObjItem['allocatNum'] = $productObj['productNum'];
				$backObj['items'][$key] = $backObjItem;
			}
		}
		return $this->add_d($backObj);
	}

	/**
	 *
	 * 调拨单作为源单时审核的业务操作
	 * @param 源单id $relDocId
	 * @param 源单清单id $relDocItemId
	 * @param 产品id $productId
	 * @param  数量 $outNum
	 */
	function dealAtAudit($relDocItemArr) {
		$allocationObj = parent:: get_d($relDocItemArr['relDocId']);
		$allocationItemDao = new model_stock_allocation_allocationitem();
		$allocationItemObj = $allocationItemDao->get_d($relDocItemArr['relDocItemId']);
		$backItemArr = "";
		if (!empty ($allocationObj['relDocId']) && !empty ($allocationItemObj['relDocId'])) {
			if ($allocationObj['relDocType'] == "DBDYDLXFH") { //发货计划
				$outPlanItemDao = new model_stock_outplan_outplanProduct();
				$outPlanItemObj = $outPlanItemDao->get_d($allocationItemObj['relDocId']);

				$relDocItemArr['relDocId'] = $outPlanItemObj['docId'];
				$backItemArr['relDocItemId'] = $outPlanItemObj['contEquId'];
			} else
				if ($allocationObj['relDocType'] == "DBDYDLXJY") { //借试用申请
					$backItemArr['relDocId'] = $allocationObj['relDocId'];
					$backItemArr['relDocItemId'] = $allocationItemObj['relDocId'];
				}
		}
		if (is_array($relDocItemArr)) {
			$backItemArr['outNum'] = $relDocItemArr['outNum'];
			$backItemArr['productId'] = $relDocItemArr['productId'];
			$borrowDao = new model_projectmanagent_borrow_borrow();
			return $borrowDao->updateBorrowEquBackNum($backItemArr);
		}
	}

	/**
	 *
	 * 调拨单作为源单时反审核的业务操作
	 * @param 源单id $relDocId
	 * @param 源单清单id $relDocItemId
	 * @param 产品id $productId
	 * @param  数量 $outNum
	 */
	function dealAtUnAudit($relDocItemArr) {
		$allocationObj = parent:: get_d($relDocItemArr['relDocId']);
		$allocationItemDao = new model_stock_allocation_allocationitem();
		$allocationItemObj = $allocationItemDao->get_d($relDocItemArr['relDocItemId']);
		$backItemArr = "";
		if (!empty ($allocationObj['relDocId']) && !empty ($allocationItemObj['relDocId'])) {
			if ($allocationObj['relDocType'] == "DBDYDLXFH") { //发货计划
				$outPlanItemDao = new model_stock_outplan_outplanProduct();
				$outPlanItemObj = $outPlanItemDao->get_d($allocationItemObj['relDocId']);

				$relDocItemArr['relDocId'] = $outPlanItemObj['docId'];
				$backItemArr['relDocItemId'] = $outPlanItemObj['contEquId'];
			} else
				if ($allocationObj['relDocType'] == "DBDYDLXJY") { //借试用申请
					$backItemArr['relDocId'] = $allocationObj['relDocId'];
					$backItemArr['relDocItemId'] = $allocationItemObj['relDocId'];
				}
		}
		if (is_array($relDocItemArr)) {
			$backItemArr['outNum'] = $relDocItemArr['outNum'];
			$backItemArr['productId'] = $relDocItemArr['productId'];
			$borrowDao = new model_projectmanagent_borrow_borrow();
			return $borrowDao->reUpdateBorrowEquBackNum($backItemArr);
		}
	}

	/**
	 *
	 * 初始化借出调拨单
	 */
	function initialLendAllocation() {
		$object = array(
			"auditDate" => day_date,
			"outStartDate" => day_date,
			"outEndDate" => day_date,
			"toUse" => "CHUKUJY",
			"docStatus" => "YSH",
			"remark" => "系统自动调出",
			"pickCode" => $_SESSION['USER_ID'],
			"pickName" => $_SESSION['USERNAME'],
			"auditerCode" => $_SESSION['USER_ID'],
			"auditerName" => $_SESSION['USERNAME'],
			"relDocType" => "",
			"relDocId" => ""
		);
		$sql = "select sub.productId,
				ifnull( (sub.lendNum - ifnull(i.actNum,0)) ,0)as  initialNum,
				ifnull( k.actNum,0) as actNum
			from (
				select productName,
				productId,
				productNo,
				sum(executedNum) as executedNum,
				sum(backNum) as backNum,
				(
					sum(executedNum) - sum(backNum)) as lendNum
					from oa_borrow_equ e inner join  oa_borrow_borrow b on(b.id=e.borrowId)
				 	where e.isDel = 0 and e.isTemp = 0 and e.productId >0 and e.executedNum > 0
				 	and b.createName <> '刘红辉'
					group by productId,
						productNo,productName
				) sub
				left join oa_stock_inventory_info i on (i.productId = sub.productId and  i.stockId= - 1 )
				left join
				(
					select productId,productCode,productName,sum(actNum) as actNum
					from oa_stock_inventory_info   where  stockId in(1,2,5) group by productId
				) k on (k.productId=sub.productId)
			where (sub.lendNum - ifnull( i.actNum,0)) > 0;";
		$queryArr = $this->findSql($sql);
		$itemsArr = array();
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		foreach ($queryArr as $value) { //1 库存商品 2 旧设备 5 手机零售仓
			$needInitialNum = $value['initialNum'];
			//查出所有三个仓库库存
			$inventoryDao->asc = "false";
			$inventoryDao->searchArr = array(
				"productId" => $value['productId'],
				"stockIds" => "1,2,5"
			);
			$inventotyArr = $inventoryDao->listBySqlId();
			if (is_array($inventotyArr)) {
				foreach ($inventotyArr as $inventoryObj) {
					if ($inventoryObj['actNum'] > 0 && $needInitialNum > 0) {
						$itemObj = array(
							"productId" => $inventoryObj['productId'],
							"productCode" => $inventoryObj['productCode'],
							"productName" => $inventoryObj['productName'],
							"pattern" => $inventoryObj['pattern'],
							"unitName" => $inventoryObj['unitName'],
							"importStockId" => "-1",
							"importStockCode" => "OUTSTOCK",
							"importStockName" => "借出仓",
							"exportStockId" => $inventoryObj['stockId'],
							"exportStockCode" => $inventoryObj['stockCode'],
							"exportStockName" => $inventoryObj['stockName']
						);
						if ($needInitialNum <= $inventoryObj['actNum']) {
							$itemObj['allocatNum'] = $needInitialNum;
							array_push($itemsArr, $itemObj);
							break;
						} else {
							$itemObj['allocatNum'] = $inventoryObj['actNum'];
							array_push($itemsArr, $itemObj);
							$needInitialNum -= $inventoryObj['actNum'];
						}
					}
				}
			}
			unset ($inventoryDao->searchArr);
		}
		$object['items'] = $itemsArr;
		return $object;
	}

	/**
	 *
	 * 因不进行初始化而导致之前进行独立归还的使借出仓库出现负库存的数据进行抹平
	 */
	function allocatLendStock() {
		$object = array(
			"auditDate" => day_date,
			"outStartDate" => day_date,
			"outEndDate" => day_date,
			"toUse" => "CHUKUJY",
			"docStatus" => "YSH",
			"remark" => "系统自动调出抹平借出仓负库存",
			"pickCode" => $_SESSION['USER_ID'],
			"pickName" => $_SESSION['USERNAME'],
			"auditerCode" => $_SESSION['USER_ID'],
			"auditerName" => $_SESSION['USERNAME'],
			"relDocType" => "",
			"relDocId" => ""
		);
		$sql = "select i.productId as productId,-i.actNum as initialNum from oa_stock_inventory_info i
 			where stockId='-1'  and actNum<0;";
		$queryArr = $this->findSql($sql);
		$itemsArr = array();
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		foreach ($queryArr as $key => $value) { //1 库存商品 2 旧设备 5 手机零售仓
			$needInitialNum = $value['initialNum'];
			//查出所有三个仓库库存
			$inventoryDao->asc = "false";
			$inventoryDao->searchArr = array(
				"productId" => $value['productId'],
				"stockIds" => "1,2,5"
			);
			$inventotyArr = $inventoryDao->listBySqlId();
			if (is_array($inventotyArr)) {
				foreach ($inventotyArr as $inventoryObj) {
					if ($inventoryObj['actNum'] > 0 && $needInitialNum > 0) {
						$itemObj = array(
							"productId" => $inventoryObj['productId'],
							"productCode" => $inventoryObj['productCode'],
							"productName" => $inventoryObj['productName'],
							"pattern" => $inventoryObj['pattern'],
							"unitName" => $inventoryObj['unitName'],
							"importStockId" => "-1",
							"importStockCode" => "OUTSTOCK",
							"importStockName" => "借出仓",
							"exportStockId" => $inventoryObj['stockId'],
							"exportStockCode" => $inventoryObj['stockCode'],
							"exportStockName" => $inventoryObj['stockName']
						);
						if ($needInitialNum <= $inventoryObj['actNum']) {
							$itemObj['allocatNum'] = $needInitialNum;
							array_push($itemsArr, $itemObj);
							break;
						} else {
							$itemObj['allocatNum'] = $inventoryObj['actNum'];
							array_push($itemsArr, $itemObj);
							$needInitialNum -= $inventoryObj['actNum'];
						}
					}
				}
			}
			unset ($inventoryDao->searchArr);
		}
		$object['items'] = $itemsArr;
		return $object;
	}


	/**
	 * 更新借用发货状态
	 * @param string 海外借用编号
	 * @return int
	 */
	function updateDeliveryStatus($borrowNo) {
		$sql = "SELECT
				count(0) AS countNum,
				(
					SELECT
						sum(o.executedNum)
					FROM
						overseas_borrow_material o
					WHERE o.borrowCode='" . $borrowNo . "' and
					 o.isTemp = 0
					AND o.isDel = 0
				) AS executeNum
			FROM
				(
					SELECT
						e.borrowId,
						(e.number - e.executedNum) AS remainNum
					FROM
						overseas_borrow_material e
					WHERE e.borrowCode='" . $borrowNo . "' and
					 e.isTemp = 0
					AND e.isDel = 0
				) c WHERE c.remainNum > 0";
		$remainNum = $this->connectSql($sql);
		if ($remainNum[0]['countNum'] <= 0) { //已发货
			$statusInfo = array(
				'borrowNo' => $borrowNo,
				'deliveryStatus' => '2'
			);
		} else if ($remainNum[0]['countNum'] > 0 && $remainNum[0]['executeNum'] == 0) { //未发货
			$statusInfo = array(
				'borrowNo' => $borrowNo,
				'deliveryStatus' => '0',
			);
		} else { //部分发货
			$statusInfo = array(
				'borrowNo' => $borrowNo,
				'deliveryStatus' => '1',
			);
		}
		$updateSql = " update overseas_borrow_baseinfo set deliveryStatus='" . $statusInfo['deliveryStatus'] . "' where borrowNo='" . $statusInfo['borrowNo'] . "'";
		$this->connectSql($updateSql);
		return 0;
	}

	/**
	 * 创建数据库连接并执行sql语句
	 * @param string sql语句
	 * @return string id
	 */
	function  connectSql($sql) {
		$con = mysql_connect(localhostOA, dbuserOA, dbpwOA);
		if (!$con) {
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db(dbnameOA, $con);
		mysql_query("SET NAMES 'GBK'");

		if (!$result = mysql_query($sql, $con)) {
			echo $sql;
			die('Error: ' . mysql_error());
		}
		$id = mysql_insert_id();
		$rows = array();
		while ($rows[] = mysql_fetch_array($result, MYSQL_ASSOC)) {
		}
		mysql_free_result($result);
		array_pop($rows);
		mysql_close($con);
		return $rows;
	}

	/**
	 * 更新借用归还状态
	 * @param $borrowNo
	 * @return int
	 */
	function updateBackStatus($borrowNo) {
		$sql = "SELECT
				count(0) AS countNum,
				(
					SELECT
						sum(o.backNum)
					FROM
						overseas_borrow_material o
					WHERE o.borrowCode='" . $borrowNo . "' and
					 o.isTemp = 0
					AND o.isDel = 0
				) AS backNum
			FROM
				(
					SELECT
						e.borrowId,
						(e.number - e.backNum) AS remainNum
					FROM
						overseas_borrow_material e
					WHERE e.borrowCode='" . $borrowNo . "' and
					 e.isTemp = 0
					AND e.isDel = 0
				) c WHERE c.remainNum > 0";
		$remainNum = $this->connectSql($sql);
		if ($remainNum[0]['countNum'] <= 0) { //已归还
			$backStatus = '3';
		} else if ($remainNum[0]['countNum'] > 0 && $remainNum[0]['backNum'] == 0) { //未归还
			$backStatus = '1';
		} else { //部分归还
			$backStatus = '2';
		}
		$updateSql = " update overseas_borrow_baseinfo set backStatus='" . $backStatus . "' where borrowNo='" . $borrowNo . "'";
		$this->connectSql($updateSql);
		return 0;
	}

	/**
	 * 获取已经保存了的调拨
	 * @param $relDocId
	 * @param $relDocType
	 * @return array
	 */
	function getSaveAllocation_d($relDocId, $relDocType) {
		$this->searchArr = array(
			'relDocId' => $relDocId,
			'relDocType' => $relDocType,
            'docStatus' => 'WSH'
		);
		$this->setCompany(0);
		$data = $this->list_d('select_default');

		$rst = array();
		// 转为map
		foreach ($data as $v) {
			$rst[] = $v['docCode'];
		}
		return implode(',', $rst);
	}
}