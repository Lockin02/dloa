<?php

/**
 * @author huangzf
 * @Date 2011年5月14日 9:44:17
 * @version 1.0
 * @description:出库单基本信息 Model层
 */
class model_stock_outstock_stockout extends model_base
{
	public $stockoutStrategyArr = array();
	public $stockoutPreArr = array();

	function __construct() {
		$this->tbl_name = "oa_stock_outstock";
		$this->sql_map = "stock/outstock/stockoutSql.php";
		parent::__construct();
		$this->stockoutStrategyArr = array( //出库策略
			"CKSALES" => "model_stock_outstock_strategy_salesstockout", //销售出库
			"CKPICKING" => "model_stock_outstock_strategy_pickingstockout", //领料出库
			"CKOTHER" => "model_stock_outstock_strategy_otherstockout", //其他出库
            "CKDLBF" => "model_stock_outstock_strategy_idlestockout" //待报废呆料出库
		);

		$this->stockoutPreArr = array(//单据编号前缀
			"CKSALES" => "XOUT",
			"CKPICKING" => "SOUT",
			"CKOTHER" => "QOUT",
            "CKDLBF" => "DOUT"
		);
	}

	//公司权限处理
	protected $_isSetCompany = 1;

	/**
	 * 查看出库单物料清单显示模板
	 * @param
	 */
	function showProAtView($rows, istockout $istrategy) {
		return $istrategy->showProAtView($rows);
	}


	/**
	 * 打印出库单物料清单显示模板
	 * @param
	 */
	function showProAtPrint($rows, istockout $istrategy) {
		return $istrategy->showProAtPrint($rows);
	}
	
	/**
	 * 打印出库单物料清单显示模板 --红色单
	 * @param
	 */
	function showRedProAtPrint($rows, istockout $istrategy) {
		return $istrategy->showRedProAtPrint($rows);
	}

	/**
	 * 打印 - 成本结转
	 */
	function showPrintForCarry($rows, istockout $istrategy) {
		return $istrategy->showPrintForCarry($rows);
	}

	/**
	 * 新增红色销售出库单时 物料信息显示模板
	 * @param  $rows
	 */
	function showProAddRed($rows, istockout $istrategy) {
		return $istrategy->showProAddRed($rows);
	}

	/**
	 * 修改时物料清单显示模板
	 * @param unknown_type $rows
	 */
	function showProAtEdit($rows, istockout $istrategy) {
		return $istrategy->showProAtEdit($rows);
	}

	/**
	 * 修改时物料清单显示模板
	 * @param unknown_type $rows
	 */
	function showProAtEditCal($rows, istockout $istrategy) {
		return $istrategy->showProAtEditCal($rows);
	}

    /***
     *
     */
    function  getMoneyLimit($limitKey){
        $otherdatasDao=new model_common_otherdatas();
        $limit =$otherdatasDao->getUserPriv('stock_outstock_stockout',$_SESSION ['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        return $limit[$limitKey];
    }

	/**
	 * 修改出库单时，包装物显示模板
	 */
	function showPackItemAtEdit($rows) {
		$str = "";
		if($rows) {
			$i = 0;
			foreach($rows as $key => $val) {
				$sNum = $i + 1;
				$str .= <<<EOT
				<tr align="center">
				    <td>
                        <img align="absmiddle" src="images/removeline.png" onclick="delPackItem(this);" title="删除行">
                    </td>
                    <td>
                        $sNum
                    </td>
                    <td>
                        <input type="hidden" name="stockout[packitem][$i][id]" id="id$i " value="$val[id]"  />
                        <input type="text" name="stockout[packitem][$i][productCode]" id="pproductCode$i" class="txt" value="$val[productCode]" />
					    <input type="hidden" name="stockout[packitem][$i][productId]" id="pproductId$i" value="$val[productId]" />
                    </td>
					<td>
					    <input type="text" name="stockout[packitem][$i][productName]" id="pproductName$i" class="txt" value="$val[productName]" />
					</td>
    				<td>
    				    <input type="text" name="stockout[packitem][$i][outstockNum]" id="poutstockNum$i" class="txt" value="$val[outstockNum]" />
					</td>
					<td>
					    <input type="text" name="stockout[packitem][$i][price]" id="pprice$i" class="txt" value="$val[price]" />
					</td>
				</tr>
EOT;
				$i++;
			}
		}
		return $str;
	}

	/**
	 * 修改出库单时，包装物显示模板
	 */
	function showPackItemAtEditCal($rows) {
		$str = "";
		if($rows) {
			$i = 0;
			foreach($rows as $key => $val) {
				$sNum = $i + 1;
				$str .= <<<EOT
				<tr align="center">
                    <td>
                        $sNum
                    </td>
                    <td>
                   		$val[productCode]
                        <input type="hidden" name="stockout[packitem][$i][id]" id="id$i " value="$val[id]"  />
                        <input type="hidden" name="stockout[packitem][$i][productCode]" id="pproductCode$i" value="$val[productCode]" />
					    <input type="hidden" name="stockout[packitem][$i][productId]" id="pproductId$i" value="$val[productId]" />
                    </td>
					<td>
						$val[productName]
					    <input type="hidden" name="stockout[packitem][$i][productName]" id="pproductName$i" class="txt" value="$val[productName]" />
					</td>
    				<td>
    					$val[outstockNum]
    				    <input type="hidden" name="stockout[packitem][$i][outstockNum]" id="poutstockNum$i" class="txt" value="$val[outstockNum]" />
					</td>
					<td>
					    <input type="text" name="stockout[packitem][$i][price]" id="pprice$i" class="txt" value="$val[price]" />
					</td>
				</tr>
EOT;
				$i++;
			}
		}
		return $str;
	}

	/**
	 * 下推红色单据时，包装物显示模板
	 */
	function showPackItemAddRed($rows) {
		$str = "";
		if($rows) {
			$i = 0;
			foreach($rows as $key => $val) {
				$sNum = $i + 1;
				$str .= <<<EOT
				<tr align="center">
				    <td>
                        <img align="absmiddle" src="images/removeline.png" onclick="delPackItem(this);" title="删除行">
                    </td>
                    <td>
                        $sNum
                    </td>
                    <td>
                        <input type="text" name="stockout[packitem][$i][productCode]" id="pproductCode$i" class="txt" value="$val[productCode]" />
					    <input type="hidden" name="stockout[packitem][$i][productId]" id="pproductId$i" value="$val[productId]" />
                    </td>
					<td>
					    <input type="text" name="stockout[packitem][$i][productName]" id="pproductName$i" class="txt" value="$val[productName]" />
					</td>
    				<td>
    				    <input type="text" name="stockout[packitem][$i][outstockNum]" id="poutstockNum$i" class="txt" value="$val[outstockNum]" />
					</td>
					<td>
					    <input type="text" name="stockout[packitem][$i][price]" id="pprice$i" class="txt" value="$val[price]" />
					</td>
				</tr>
EOT;
				$i++;
			}
		}
		return $str;
	}

	/**
	 * 查看 出库单时，包装物显示模板
	 */
	function showPackItemAtView($rows) {
		$str = "";
		if($rows) {
			$i = 0;
			foreach($rows as $key => $val) {
				$sNum = $i + 1;
				$str .= <<<EOT
				<tr align="center">
                    <td>
                        $sNum
                    </td>
                    <td>
                        $val[productCode]
                    </td>
					<td>
					    $val[productName]
					</td>
    				<td>
   						$val[outstockNum]
					</td>
					<td>
					   $val[price]
					</td>
				</tr>
EOT;
				$i++;
			}
		}
		return $str;
	}

	/**
	 * 根据蓝色入库单生成红色入库单，清单显示模板
	 * @param  $rows
	 * @param  $istrategy
	 */
	function showRelItem($rows, istockout $istrategy) {
		return $istrategy->showRelItem($rows);
	}

	/**
	 * 出库单下推入库单时，清单显示模板
	 * @param
	 */
	function showItemAtInStock($rows, istockout $istrategy) {
		return $istrategy->showItemAtInStock($rows);
	}

	/**
	 * 序列号详细页面
	 */
	function showSerialno($serialnoNameStr) {
		if($serialnoNameStr) {
			$i = 1;
			$str = "";
			$serialnoNameArr = explode(",", $serialnoNameStr);
			foreach($serialnoNameArr as $key => $val) {
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

	/*===================================业务处理======================================*/

	/**
	 * 新增出库单
	 */
	function add_d($object) {
		try {
			$this->start_d();
			if(is_array($object['items'])) {
				//处理数据字典字段
				$datadictDao = new model_system_datadict_datadict();
				$object['moduleName'] = $datadictDao->getDataNameByCode($object['module']);

				$codeDao = new model_common_codeRule();
				$object['docCode'] = $codeDao->stockCode("oa_stock_outstock", $this->stockoutPreArr[$object['docType']]);
				$id = parent::add_d($object, true);

                //插入操作记录
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->addObjLog($this->tbl_name, $id, $object,
                    $object['docStatus'] == "YSH" ? '审核出库' : '新增出库');

                if (isset($object['orgId']) && $object['orgId']) {
                    $logSettingDao->addObjLog($this->tbl_name, $object['orgId'],
                        array('docCode' => $object['docCode']), '下推红字单据');
                }

				// k3编码加载处理
				$productinfoDao = new model_stock_productinfo_productinfo();
				$object['items'] = $productinfoDao->k3CodeFormatter_d($object['items']);
                foreach($object['items'] as $key => $itemObj) {
                    //处理物料一级分类
                    if(!isset($itemObj['proType'])||$itemObj['proType']==""){
                        $typeRow=$productinfoDao->getParentType($itemObj['productId']);
                        if(!empty($typeRow)){
                            $object['items'] [$key]['proType']=$typeRow['proType'];
                        }
                    }
                }

				//保存出库单物料清单
				$outItemDao = new model_stock_outstock_stockoutitem();
				$itemsArr = $this->setItemMainId("mainId", $id, $object['items']);
				$itemsObj = $outItemDao->saveDelBatch($itemsArr);

				//出库单物料序列号
				if($object['docStatus'] == "YSH") {
					$serialnoDao = new model_stock_serialno_serialno();
					foreach($itemsObj as $key => $val) {
						if(!empty($val['serialnoId'])) {
							$sequenceId = $val['serialnoId'];
							//							$seqStatusVal = "1";
							$sequencObj = array("outDocCode" => $object['docCode'], "outDocId" => $id, "outDocItemId" => $val['id'], "seqStatus" => "1", "relDocType" => $object['contractType'], "relDocCode" => $object['contractCode']);
							if($object['isRed'] == "1") {
								$sequencObj['seqStatus'] = "0";
								$sequencObj['stockId'] = $val['stockId'];
								$sequencObj['stockName'] = $val['stockName'];
								$sequencObj['stockCode'] = $val['stockCode'];
							}
							$serialnoDao->update("id in($sequenceId)", $sequencObj);
						}
					}
				}

				//处理关联业务
				$stockoutStrategy = $this->stockoutStrategyArr[$object['docType']];
				if($stockoutStrategy) {
					$paramArr = array(//单据基本信息
						'docId' => $id, 'docCode' => $object['docCode'],
						'docType' => $object['docType'], 'docStatus' => $object['docStatus'],
						'relDocId' => $object['relDocId'], 'relDocCode' => $object['relDocCode'],
						'relDocType' => $object['relDocType'], 'isRed' => $object['isRed'],
						'contractId' => $object['contractId'], 'contractType' => $object['contractType']
					);
					$this->ctDealRelInfoAtAdd(new $stockoutStrategy(), $paramArr, $itemsObj);
				} else {
					throw new Exception("该类型入库暂未开放，请联系开发人员！");
				}
				//包装物处理
				if(isset($object['packitem'])) {
					$extraItemDao = new model_stock_outstock_extraitem();
					$stockSystemDao = new model_stock_stockinfo_systeminfo();
					$extraItemDao->delete(array("mainId" => $id));
					$packValArr = array(); //物料id不为空的包装物
					foreach($object['packitem'] as $key => $packVal) {
						if(!empty($packVal['productId'])) {
							array_push($packValArr, $packVal);
						}
					}
					if(count($packValArr) > 0) {
						$extraItemDao->addBatch_d($this->setItemMainId("mainId", $id, $packValArr));
						$stockSysObj = $stockSystemDao->get_d(1);
						if($object['docStatus'] == "YSH") {
							$packInOut = "outstock";
							if($object['isRed'] == "1") {
								$packInOut = "instock";
							}
							foreach($packValArr as $key => $packingVal) {
								//更新仓库包装物的库存数量
								if(!empty($packingVal['productId'])) {
									$inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //库存DAO
									$inventoryDao->updateInTimeInfo(array("stockId" => $stockSysObj['packingStockId'], "productId" => $packingVal['productId']), $packingVal['outstockNum'], $packInOut);
								}
							}
						}
					}
				}
				//资产出库-提交时邮件通知
				if(is_numeric($object['relDocId']) && strlen($object['relDocId']) < 32
					&& $object['relDocType'] == "QTCKZCCK" && $object['docStatus'] == "YSH") {
					$requireinDao = new model_asset_require_requirein();
					$rs = $requireinDao->find(array('id' => $object['relDocId']), null, 'applyId');
					$this->mailDeal_d('assetOutStock', $rs['applyId'], array('id' => $id));
				}

				$this->commit_d();
				return $id;
			} else {
				throw new Exception("单据信息不完整!");
			}

		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 调用父类新增
	 */
	function parentAdd_d($object) {
		try {
			$this->start_d();
			$id = parent::add_d($object, true);
			$this->commit_d();
			return $id;
		} catch(Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 修改出库单信息
	 */
	function edit_d($object) {
        // 获取旧的入库单信息
        $org = parent::get_d($object['id']);

		try {
			$this->start_d();
			if(is_array($object['items'])) { //物料清单
				//处理数据字典字段
				$datadictDao = new model_system_datadict_datadict();
				$object['moduleName'] = $datadictDao->getDataNameByCode($object['module']);

				$editResult = parent::edit_d($object, true);

                //插入操作记录
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->compareModelObj($this->tbl_name, $org, $object, $object['docStatus'] == "YSH" ? '审核出库' : '修改出库');

				// k3编码加载处理
				$productinfoDao = new model_stock_productinfo_productinfo();
				$object['items'] = $productinfoDao->k3CodeFormatter_d($object['items']);

				//保存出库单物料清单
				$outItemDao = new model_stock_outstock_stockoutitem();
				$itemsArr = $this->setItemMainId("mainId", $object['id'], $object['items']);
				$itemsObj = $outItemDao->saveDelBatch($itemsArr);

				//出库单物料序列号
				if($object['docStatus'] == "YSH") {
					$serialnoDao = new model_stock_serialno_serialno();
					foreach($itemsObj as $key => $val) {
						if(!empty($val['serialnoId'])) {
							$sequenceId = $val['serialnoId'];

							$sequencObj = array("outDocCode" => $object['docCode'], "outDocId" => $object['id'], "outDocItemId" => $val['id'], "seqStatus" => "1", "relDocType" => $object['contractType'], "relDocCode" => $object['contractCode']);
							if($object['isRed'] == "1") {
								$sequencObj['seqStatus'] = "0";
								$sequencObj['stockId'] = $val['stockId'];
								$sequencObj['stockName'] = $val['stockName'];
								$sequencObj['stockCode'] = $val['stockCode'];
							}
							$serialnoDao->update("id in($sequenceId)", $sequencObj);
						}
					}
				}
				//处理关联业务
				$stockoutStrategy = $this->stockoutStrategyArr[$object['docType']];
				if($stockoutStrategy) {
					$paramArr = array(//单据基本信息
						'docId' => $object['id'], 'docCode' => $object['docCode'],
						'docType' => $object['docType'], 'docStatus' => $object['docStatus'],
						'relDocId' => $object['relDocId'], 'relDocCode' => $object['relDocCode'],
						'relDocType' => $object['relDocType'], 'isRed' => $object['isRed'],
						'contractId' => $object['contractId'], 'contractType' => $object['contractType']);
					$this->ctDealRelInfoAtEdit(new $stockoutStrategy(), $paramArr, $itemsObj);
				} else {
					throw new Exception("该类型入库暂未开放，请联系开发人员！");
				}

				//包装物处理
				if(isset($object['packitem'])) {
					$extraItemDao = new model_stock_outstock_extraitem();
					$stockSystemDao = new model_stock_stockinfo_systeminfo();
					$extraItemDao->delete(array("mainId" => $object['id']));
					$packValArr = array(); //物料id不为空的包装物
					foreach($object['packitem'] as $key => $packVal) {
						if(!empty($packVal['productId'])) {
							array_push($packValArr, $packVal);
						}
					}

					if(count($packValArr) > 0) {
						$extraItemDao->addBatch_d($this->setItemMainId("mainId", $object['id'], $packValArr));
						$stockSysObj = $stockSystemDao->get_d(1);
						if($object['docStatus'] == "YSH") {
							$packInOut = "outstock";
							if($object['isRed'] == "1") {
								$packInOut = "instock";
							}
							foreach($packValArr as $key => $packingVal) {
								//更新仓库包装物的库存数量
								if(!empty($packingVal['productId'])) {
									$inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //库存DAO
									$inventoryDao->updateInTimeInfo(array("stockId" => $stockSysObj['packingStockId'], "productId" => $packingVal['productId']), $packingVal['outstockNum'], $packInOut);
								}
							}
						}
					}
				}
				//资产出库-提交时邮件通知
				if(is_numeric($object['relDocId']) && strlen($object['relDocId']) < 32
					&& $object['relDocType'] == "QTCKZCCK" && $object['docStatus'] == "YSH") {
					$requireinDao = new model_asset_require_requirein();
					$rs = $requireinDao->find(array('id' => $object['relDocId']), null, 'applyId');
					$this->mailDeal_d('assetOutStock', $rs['applyId'], array('id' => $object['id']));
				}

				$this->commit_d();
				return $editResult;
			} else {
				throw new Exception("单据信息不完整!");
			}
		} catch(Exception $e) {
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
            $logSettingDao = new model_syslog_setting_logsetting();
            $logSettingDao->deleteObjLog($this->tbl_name, $org);

            return true;
        } catch(Exception $e) {
            throw $e;
        }
    }

	/**
	 * 新增出库单时源单据业务处理
	 * @param $istrategy 策略接口
	 * @param  $paramArr ->主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
	 * @param  $relItemArr ->从表清单信息
	 */
	function ctDealRelInfoAtAdd(istockout $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtAdd($paramArr, $relItemArr);
	}

	/**
	 * 修改出库申请时源单据业务处理
	 * @param $istrategy 策略接口
	 * @param  $paramArr ->主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
	 * @param  $relItemArr ->从表清单信息
	 */
	function ctDealRelInfoAtEdit(istockout $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtEdit($paramArr, $relItemArr);
	}

	/**
	 * 反审核出库单
	 * @param $id
	 * @param $istrategy
	 */
	function ctCancelAudit($id, istockout $istrategy) {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');	//设置内存
		try {
			$this->start_d();
			$stockoutObj = $this->get_d($id, $istrategy);
			if("YSH" == $stockoutObj['docStatus']) {
				$obj = array("id" => $id, "docStatus" => "WSH");
				$this->updateById($obj);
				if(!$istrategy->cancelAudit($stockoutObj)) {
					throw new Exception("反审核失败!");
				}

                //插入操作记录
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->addObjLog($this->tbl_name, $id, array(), '取消审核');
			} else {
				throw new Exception("单据已是反审核状态!");
			}
			$this->commit_d();
			return true;
		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 设置关联从表的申请单id信息
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array();
		foreach($iteminfoArr as $key => $value) {
			$value[$mainIdName] = $mainIdValue;
			array_push($resultArr, $value);
		}
		return $resultArr;
	}

	/**
	 * 根据主键id获取出库单基本信息及物料清单信息
	 */
	function get_d($id, istockout $istrategy) {
		$stockoutObj = parent::get_d($id);
		$stockoutObj['items'] = $istrategy->getItem($id);
		$extraitemDao = new model_stock_outstock_extraitem();
		$extraitemDao->searchArr['mainId'] = $id;
		$stockoutObj['packitems'] = $extraitemDao->list_d();
		return $stockoutObj;
	}

	/**
	 * 数组过滤 - 同表单的表单数据不予显示
	 */
	function filterRows_d($object) {
		if($object) {
			$markId = null;
			foreach($object as $key => $val) {
				if($markId == $val['mainId']) {
					unset($object[$key]['docCode']);
					unset($object[$key]['docStatus']);
					unset($object[$key]['customerName']);
					unset($object[$key]['auditDate']);
					$object[$key]['isRed'] = 2;
				} else {
					$markId = $val['mainId'];
				}
			}
		}
		return $object;
	}

	/**
	 * 获取当前财务周期
	 */
	function rtThisPeriod_d() {
		$periodDao = new model_finance_period_period();
		return $periodDao->rtThisPeriod_d();
	}

	/**
	 * 修改出库单价格 金额信息
	 */
	function editCost_d($object) {
		try {
			$this->start_d();
			if(is_array($object['items'])) { //物料清单
				$editResult = parent::edit_d($object, true);

				//保存出库单物料清单
				$outItemDao = new model_stock_outstock_stockoutitem();
				$itemsArr = $this->setItemMainId("mainId", $object['id'], $object['items']);
				$outItemDao->saveDelBatch($itemsArr);

				//包装物处理
				if(isset($object['packitem'])) {
					$extraItemDao = new model_stock_outstock_extraitem();
					$stockSystemDao = new model_stock_stockinfo_systeminfo();
					$extraItemDao->delete(array("mainId" => $object['id']));
					$packValArr = array(); //物料id不为空的包装物
					foreach($object['packitem'] as $key => $packVal) {
						if(!empty($packVal['productId'])) {
							array_push($packValArr, $packVal);
						}
					}

					if(count($packValArr) > 0) {
						$extraItemDao->addBatch_d($this->setItemMainId("mainId", $object['id'], $packValArr));
						$stockSysObj = $stockSystemDao->get_d(1);
						if($object['docStatus'] == "YSH") {
							$packInOut = "outstock";
							if($object['isRed'] == "1") {
								$packInOut = "instock";
							}
							foreach($packValArr as $key => $packingVal) {
								//更新仓库包装物的库存数量
								if(!empty($packingVal['productId'])) {
									$inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //库存DAO
									$inventoryDao->updateInTimeInfo(array("stockId" => $stockSysObj['packingStockId'], "productId" => $packingVal['productId']), $packingVal['outstockNum'], $packInOut);
								}
							}
						}
					}
				}

				$this->commit_d();
				return $editResult;
			} else {
				throw new Exception("单据信息不完整!");
			}
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 根据合同类型 合同id 获取 某个日期前的所有已经审核的出库单
	 */
	function findByContract($docDate, $contactType, $contractId, $docType = "CKSALES") {
		$this->searchArr = array("contractType" => $contactType, "contractId" => $contractId, "docType" => $docType, "endDate" => $docDate, "docStatus" => 'YSH');
		$md5ConfigDao = new model_common_securityUtil("stockout");
		return $md5ConfigDao->md5Rows($this->listBySqlId("select_subcost"));
	}

	/**
	 *
	 *导入2011年8月前的出库单
	 */
	function importSalesStockout($excelData) {
		try {
			$this->start_d();
			$orderDao = new model_projectmanagent_order_order();
			$codeDao = new model_common_codeRule();
			$outStockItemDao = new model_stock_outstock_stockoutitem();
			$resultArr = array();
			foreach($excelData as $key => $value) {
				$stockObject = array();
				if(!empty($value[2])) {
					$orderObj = $orderDao->allOrderInfo($value[2]);
					if(is_array($orderObj)) {
						$date = explode("-", $value[0]);
						if(checkdate($date[1], $date[2], $date[0])) {
							$stockObject['auditDate'] = $value[0];
						} else {
							$stockObject['auditDate'] = date('Y-m-d',(mktime(0, 0, 0, 1, $value[0] - 1, 1900)));
						}
						$stockObject['docCode'] = $codeDao->stockCode("oa_stock_outstock", $this->stockoutPreArr["CKSALES"]);
						$stockObject['contractType'] = $orderObj['tablename'];
						$stockObject['contractId'] = $orderObj['orgid'];
						$stockObject['contractCode'] = $value[2];
						$stockObject['contractName'] = $orderObj['orderName'];
						$stockObject['customerName'] = $orderObj['customerName'];
						$stockObject['customerId'] = $orderObj['customerId'];
						$stockObject['docType'] = 'CKSALES';
						$stockObject['docStatus'] = 'YSH';
						$stockObject['auditerName'] = $_SESSION['USERNAME'];
						$stockObject['auditerCode'] = $_SESSION['USER_ID'];
						$id = parent::add_d($stockObject, true);
						$stockOutItemObj = array("mainId" => $id, "subCost" => $value['3'], "productName" => '虚拟导入物料', "productCode" => '虚拟导入物料');
						$outStockItemDao->add_d($stockOutItemObj);
						array_push($resultArr, array("docCode" => $value[2], "result" => "导入成功!"));
					} else {
						array_push($resultArr, array("docCode" => $value[2], "result" => "导入失败,合同号为" . $value[2] . "的合同不存在!"));
					}
				}
			}
			$this->commit_d();
			return $resultArr;
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 获取合同实际出库数量
	 * @param $contractCode 合同编号
	 * @param $thisType 类型，空为全部，1为红字，0为蓝字
	 */
	function getOutNumForCont_d($contractCode, $isRed = null) {
		$this->searchArr = array(
			'contractCodeEq' => $contractCode
		);
		//是否载入红蓝字
		if($isRed !== null) {
			$this->searchArr['isRed'] = $isRed;
		}
		$this->sort = 'c.id';
		$this->groupBy = 'i.productId';
		$rs = $this->list_d('count_num');
		return $rs;
	}

	/*********************************向海外系统推送数据***********************************************************************/
	/**
	 * 出库单审核后向海外系统推送订单
	 */
	function pushERPorder($row, $code) {
		$cId = $row['contractId'];
		$conDao = new model_contract_contract_contract();
		$conArr = $conDao->get_d($cId);
		if(empty($conArr['parentName'])) {
			return false;
		} else {
			$contractId = $conArr['parentName'];
			$equArr = array();
			foreach($row['items'] as $k => $v) {
				if($v['isDelTag'] != 1) {
					$tempArr = array(
						"contractId" => $contractId,
						"materialId" => $v['productId'],
						"materialCode" => $v['productCode'],
						"materialName" => $v['productName'],
						"pattern" => $v['pattern'],
						"unitName" => $v['unitName'],
						"number" => $v['actOutNum'],
						"remark" => $v['remark'],
						"serialnoName" => $v['serialnoName'],
						"serialnoId" => $v['serialnoId']
					);
					array_push($equArr, $tempArr);
				}
			}
			//处理插入数组
			$addArr = array(
				"applyCode" => $code,
				"buyer" => "",
				"buyerId" => "",
				"dateHope" => date("Y-m-d"),
				"dataType" => "DL",
				"ExaStatus" => "完成",
				"contractId" => $contractId,
				"state" => "0"
			);
			//插入主表
			$nid = $this->handleCreateSql($addArr, "overseas_purch_orders");
			//插入从表数据
			foreach($equArr as $k => $v) {
				$v['mainId'] = $nid;
				$this->handleCreateSql($v, "overseas_purch_orders_equ");
			}
		}
	}

	//处理并返回insertSQL并创建数据库连接并执行sql语句
	function handleCreateSql($row, $tableName) {
		if(!is_array($row))
			return FALSE;
		if(empty($row))
			return FALSE;
		$uuid = $this->uuid();
		$row['id'] = "$uuid";
		foreach($row as $key => $value) {
			$cols[] = $key;
			$vals[] = "'" . $this->__val_escape($value) . "'";
		}
		$col = join(',', $cols);
		$val = join(',', $vals);

		$sql = "INSERT INTO $tableName({$col}) VALUES({$val})";

		$con = mysql_connect(localhostOA, dbuserOA, dbpwOA);
		if(!$con) {
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db(dbnameOA, $con);
		mysql_query("SET NAMES 'GBK'");

		if(!mysql_query($sql, $con)) {
			echo $sql;
			die('Error: ' . mysql_error());
		}
		$id = mysql_insert_id();
		mysql_close($con);
		return $uuid;
	}

	//创建数据库连接并执行sql语句
	function  connectSql($sql) {
		$con = mysql_connect(localhostOA, dbuserOA, dbpwOA);
		if(!$con) {
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db(dbnameOA, $con);
		mysql_query("SET NAMES 'GBK'");

		if(!mysql_query($sql, $con)) {
			echo $sql;
			die('Error: ' . mysql_error());
		}
		$id = mysql_insert_id();
		mysql_close($con);
		return $id;
	}

	function uuid($prefix = '') {
		$chars = md5(uniqid(mt_rand(), true));
		$uuid = substr($chars, 0, 8) . '-';
		$uuid .= substr($chars, 8, 4) . '-';
		$uuid .= substr($chars, 12, 4) . '-';
		$uuid .= substr($chars, 16, 4) . '-';
		$uuid .= substr($chars, 20, 12);
		return $prefix . $uuid;
	}

	/*********************************向海外系统推送数据*******END****************************************************************/
	//出库后发送给海外仓管
	/**
	 * 邮件处理
	 * @p1 你调用的邮件的业务编码 str
	 * @p2 邮件接收人 str
	 * @p3 非查询脚本中的信息 array
	 * @p4 抄送人 str
	 * @p5 是否过滤公司人员 boolean
	 * @p6 公司编码 str
	 */
	function sendToHWStorer_d($infoArr) {
		$this->mailDeal_d("sendToHWStorer", $infoArr['createId'], $infoArr);
	}

	/**
	 * 资产出库时，检查出库数量是否存在填写错误
	 */
	function checkAudit_d($object) {
		$equItems = $object['items'];
		$requireinitemDao = new model_asset_require_requireinitem();
		foreach($equItems as $key => $val) {
			$rs = $requireinitemDao->find(array('id' => $val['relDocId']), null, 'number,executedNum');
			if($rs['executedNum'] + $val['actOutNum'] > $rs['number']) {
				return 2;//合计出库数量大于申请数量
			}
		}
		return 1;//正常
	}

	/**
	 * 最新出库价
	 */
	function updateCostByNewest_d($object) {
		$sql = "
			SELECT
				i.id,
				i.actOutNum,
				t.cost
			FROM
				oa_stock_outstock c
			LEFT JOIN oa_stock_outstock_item i ON c.id = i.mainId
			INNER JOIN(
				SELECT
					*
				FROM
					(
						SELECT
							i.productId,
							i.cost
						FROM
							oa_stock_outstock c
						LEFT JOIN oa_stock_outstock_item i ON c.id = i.mainId
						WHERE
							c.docStatus = 'YSH'
						AND i.cost <> 0
						AND i.cost IS NOT NULL
						ORDER BY
							c.auditDate DESC
					) c
				GROUP BY
					c.productId
			) t ON t.productId = i.productId
			WHERE
				c.docStatus = 'YSH'
			AND YEAR(c.auditDate) = '" . $object['thisYear'] . "'
			AND MONTH(c.auditDate) = '" . $object['thisMonth'] . "'
			AND i.cost = 0 ";
		if(!empty($object['id'])) {//勾选了相应的单据
			$sql .= " AND i.id IN(" . $object['id'] . ")";
		}
		$rows = $this->_db->getArray($sql);
		$num = 0;
		if(!empty($rows)) {
			$stockoutitemDao = new model_stock_outstock_stockoutitem();
			foreach($rows as $k => $v) {
				$stockoutitemDao->update(array('id' => $v['id']), array('cost' => $v['cost'], 'subCost' => $v['cost'] * $v['actOutNum']));
				$num++;
			}
		}
		return $num;
	}

	/**
	 * 期初余额加权平均价
	 */
	function updateCostByStockbalance_d($object) {
		$sql = "
			SELECT
				i.id,
				i.actOutNum,
				t.price
			FROM
				oa_stock_outstock c
			LEFT JOIN oa_stock_outstock_item i ON c.id = i.mainId
			INNER JOIN(
				SELECT
					*
				FROM
					(
						SELECT
							c.productId,
							c.price
						FROM
							oa_finance_stockbalance c
						WHERE
							c.price <> 0
						AND c.price IS NOT NULL
						ORDER BY
							c.thisDate DESC
					) c
				GROUP BY
					c.productId
			) t ON t.productId = i.productId
			WHERE
				c.docStatus = 'YSH'
			AND YEAR(c.auditDate) = '" . $object['thisYear'] . "'
			AND MONTH(c.auditDate) = '" . $object['thisMonth'] . "'
			AND i.cost = 0 ";
		if(!empty($object['id'])) {//勾选了相应的单据
			$sql .= " AND i.id IN(" . $object['id'] . ")";
		}
		$rows = $this->_db->getArray($sql);
		$num = 0;
		if(!empty($rows)) {
			$stockoutitemDao = new model_stock_outstock_stockoutitem();
			foreach($rows as $k => $v) {
				$stockoutitemDao->update(array('id' => $v['id']), array('cost' => $v['price'], 'subCost' => $v['price'] * $v['actOutNum']));
				$num++;
			}
		}
		return $num;
	}

	/**
	 * 根据更新类型更新红字出库单的单价
	 * @param $params
	 * @return int
	 */
	function updateProductPrice_d($params) {

		// 更新数量
		$num = 0;

		// 截取更新类型
		$updateType = $params['updateType'];
		unset($params['updateType']);

        if($updateType == 1){
            if(isset($params['thisYear'])){
                unset($params['thisYear']);
            }

            if(isset($params['thisMonth'])){
                unset($params['thisMonth']);
            }
        }

		// 列表数据获取
		$this->getParam($params);
		$items = $this->listBySqlId("select_callist");

		// 非空的时候
		if(!empty($items)) {

			// 等待处理的数据
			$waitItems = array();

			// 缓存需要查询的物料id
			$productIdArr = array();

			// 循环处理数据
			foreach($items as $k => $v) {
				// 价格为0时才去处理
				if($v['cost'] == 0) {

					// 物料id缓存
					if (!in_array($v['productId'], $productIdArr)) {
						$productIdArr[] = $v['productId'];
					}

					// 待处理数据缓存
					$waitItems[] = $v;
				}
			}

			// 如果有需要处理的数据，那么则进行单价获取
			if(!empty($productIdArr)) {

				// 根据更新类新获取相应的单据金额
				switch($updateType) {
					case 0: // 最初余额加权平均价
						$stockbalanceDao = new model_finance_stockbalance_stockbalance();
						$priceArr = $stockbalanceDao->getBeginPrice_d($params['thisYear'],
							$params['thisMonth'], $productIdArr);
						break;
					case 1: // 最新出库价
						$priceArr = $this->getLastOutPrice_d($params['thisYear'],
							$params['thisMonth'], $productIdArr);
						break;
					case 2: // 最新入库价
						$stockinDao = new model_stock_instock_stockin();
						$priceArr = $stockinDao->getLastInPrice_d($productIdArr);
						break;
					default:
				}

				// 价格处理
				if(!empty($priceArr)) {

					// 出库子表初始化
					$stockoutitemDao = new model_stock_outstock_stockoutitem();

					// 这里要更新
					foreach($waitItems as $v) {
						$price = $priceArr[$v['productId']];
						if($price && $price != 0) {
							$stockoutitemDao->update(
								array('id' => $v['id']),
								array(
									'cost' => $price,
									'subCost' => abs(round(bcmul($price,$v['actOutNum'],6), 2)) // 这里加入绝对值处理，因为取值SQL拿到数量是负数
								)
							);

							$num++;
						}
					}
				}
			}
		}

		return $num;
	}

	/**
	 * 获取上期最新出库价
	 * @param $thisYear
	 * @param $thisMonth
	 * @param $productIdArr
	 * @return int
	 */
	function getLastOutPrice_d($thisYear, $thisMonth, $productIdArr) {

		// 返回结果
		$results = array();

		// 往前一个月查找
//		if($thisMonth == 1) {
//			$thisYear = $thisYear - 1;
//			$thisMonth = 12;
//		} else {
//			$thisMonth = $thisMonth - 1;
//		}

		if(empty($productIdArr)) {
			return $results;
		}

		// 查询物料范围
		$productIds = implode(",", $productIdArr);

		// 查询物料的最新出库单价
		$sql = "SELECT c.auditDate, i.productId, i.productCode, i.productName, i.cost FROM
				oa_stock_outstock c INNER JOIN oa_stock_outstock_item i ON c.id = i.mainId
			WHERE
				c.isRed = 0 AND c.docStatus = 'YSH' AND i.productId IN($productIds)
				AND i.cost <> 0
			ORDER BY i.productCode, c.auditDate DESC";
		$rows = $this->_db->getArray($sql);

		if($rows) {
			foreach($rows as $v) {
				if(!isset($results[$v['productId']])) {
					$results[$v['productId']] = $v['cost'];
				}
			}
		}

		return $results;
	}

    /**
     * 呆料报废申请回调业务处理函数
     */
	function workflowCallBack_idleScrap($spid){
        try {
            $this->start_d();
            $otherdatas = new model_common_otherdatas();
            $folowInfo = $otherdatas->getWorkflowInfo($spid);
            $objId = $folowInfo['objId'];
            if (!empty ($objId)) {
                $stockoutStrategy = $this->stockoutStrategyArr['CKDLBF'];
                $mainObjOld = parent::get_d($objId);// 获取原来的入库单信息

                if ($mainObjOld['ExaStatus'] == "完成") {// 审批通过
                    $this->update( " id={$objId}",array("docStatus"=>"YSH"));// 出库单状态更新
                    $mainObj = $this->get_d($objId, new $stockoutStrategy());
                    $itemsObj = $mainObj['items'];

                    //插入操作记录
                    $logSettingDao = new model_syslog_setting_logsetting();
                    $logSettingDao->compareModelObj($this->tbl_name, $mainObjOld, $mainObj, '审核出库');

                    //出库单物料序列号
                    if($mainObj['docStatus'] == "YSH") {
                        $serialnoDao = new model_stock_serialno_serialno();
                        foreach($itemsObj as $key => $val) {
                            if(!empty($val['serialnoId'])) {
                                $sequenceId = $val['serialnoId'];

                                $sequencObj = array("outDocCode" => $mainObj['docCode'], "outDocId" => $mainObj['id'], "outDocItemId" => $val['id'], "seqStatus" => "1", "relDocType" => $mainObj['contractType'], "relDocCode" => $mainObj['contractCode']);
                                if($mainObj['isRed'] == "1") {
                                    $sequencObj['seqStatus'] = "0";
                                    $sequencObj['stockId'] = $val['stockId'];
                                    $sequencObj['stockName'] = $val['stockName'];
                                    $sequencObj['stockCode'] = $val['stockCode'];
                                }
                                $serialnoDao->update("id in($sequenceId)", $sequencObj);
                            }
                        }
                    }

                    //包装物处理
                    if(isset($mainObj['packitem'])) {
                        $extraItemDao = new model_stock_outstock_extraitem();
                        $stockSystemDao = new model_stock_stockinfo_systeminfo();
                        $extraItemDao->delete(array("mainId" => $mainObj['id']));
                        $packValArr = array(); //物料id不为空的包装物
                        foreach($mainObj['packitem'] as $key => $packVal) {
                            if(!empty($packVal['productId'])) {
                                array_push($packValArr, $packVal);
                            }
                        }

                        if(count($packValArr) > 0) {
                            $extraItemDao->addBatch_d($this->setItemMainId("mainId", $mainObj['id'], $packValArr));
                            $stockSysObj = $stockSystemDao->get_d(1);
                            if($mainObj['docStatus'] == "YSH") {
                                $packInOut = "outstock";
                                if($mainObj['isRed'] == "1") {
                                    $packInOut = "instock";
                                }
                                foreach($packValArr as $key => $packingVal) {
                                    //更新仓库包装物的库存数量
                                    if(!empty($packingVal['productId'])) {
                                        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //库存DAO
                                        $inventoryDao->updateInTimeInfo(array("stockId" => $stockSysObj['packingStockId'], "productId" => $packingVal['productId']), $packingVal['outstockNum'], $packInOut);
                                    }
                                }
                            }
                        }
                    }

                    //处理关联业务
                    $stockoutStrategy = $this->stockoutStrategyArr[$mainObj['docType']];
                    if($stockoutStrategy) {
                        $paramArr = array(//单据基本信息
                            'docId' => $mainObj['id'], 'docCode' => $mainObj['docCode'],
                            'docType' => $mainObj['docType'], 'docStatus' => $mainObj['docStatus'],
                            'relDocId' => $mainObj['relDocId'], 'relDocCode' => $mainObj['relDocCode'],
                            'relDocType' => $mainObj['relDocType'], 'isRed' => $mainObj['isRed'],
							'contractId' => $mainObj['contractId'], 'contractType' => $mainObj['contractType']);
                        $this->ctDealRelInfoAtEdit(new $stockoutStrategy(), $paramArr, $itemsObj);
                    } else {
                        throw new Exception("该类型入库暂未开放，请联系开发人员！");
                    }
                }else if($mainObjOld['ExaStatus'] == "打回"){// 审批不通过
                    $this->update( " id={$objId}",array("docStatus"=>"WSH"));// 出库单状态更新
                    $mainObj = $this->get_d($objId, new $stockoutStrategy());

                    //插入操作记录
                    $logSettingDao = new model_syslog_setting_logsetting();
                    $logSettingDao->compareModelObj($this->tbl_name, $mainObjOld, $mainObj, '审核打回');
                }
            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }
}