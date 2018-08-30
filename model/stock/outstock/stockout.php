<?php

/**
 * @author huangzf
 * @Date 2011��5��14�� 9:44:17
 * @version 1.0
 * @description:���ⵥ������Ϣ Model��
 */
class model_stock_outstock_stockout extends model_base
{
	public $stockoutStrategyArr = array();
	public $stockoutPreArr = array();

	function __construct() {
		$this->tbl_name = "oa_stock_outstock";
		$this->sql_map = "stock/outstock/stockoutSql.php";
		parent::__construct();
		$this->stockoutStrategyArr = array( //�������
			"CKSALES" => "model_stock_outstock_strategy_salesstockout", //���۳���
			"CKPICKING" => "model_stock_outstock_strategy_pickingstockout", //���ϳ���
			"CKOTHER" => "model_stock_outstock_strategy_otherstockout", //��������
            "CKDLBF" => "model_stock_outstock_strategy_idlestockout" //�����ϴ��ϳ���
		);

		$this->stockoutPreArr = array(//���ݱ��ǰ׺
			"CKSALES" => "XOUT",
			"CKPICKING" => "SOUT",
			"CKOTHER" => "QOUT",
            "CKDLBF" => "DOUT"
		);
	}

	//��˾Ȩ�޴���
	protected $_isSetCompany = 1;

	/**
	 * �鿴���ⵥ�����嵥��ʾģ��
	 * @param
	 */
	function showProAtView($rows, istockout $istrategy) {
		return $istrategy->showProAtView($rows);
	}


	/**
	 * ��ӡ���ⵥ�����嵥��ʾģ��
	 * @param
	 */
	function showProAtPrint($rows, istockout $istrategy) {
		return $istrategy->showProAtPrint($rows);
	}
	
	/**
	 * ��ӡ���ⵥ�����嵥��ʾģ�� --��ɫ��
	 * @param
	 */
	function showRedProAtPrint($rows, istockout $istrategy) {
		return $istrategy->showRedProAtPrint($rows);
	}

	/**
	 * ��ӡ - �ɱ���ת
	 */
	function showPrintForCarry($rows, istockout $istrategy) {
		return $istrategy->showPrintForCarry($rows);
	}

	/**
	 * ������ɫ���۳��ⵥʱ ������Ϣ��ʾģ��
	 * @param  $rows
	 */
	function showProAddRed($rows, istockout $istrategy) {
		return $istrategy->showProAddRed($rows);
	}

	/**
	 * �޸�ʱ�����嵥��ʾģ��
	 * @param unknown_type $rows
	 */
	function showProAtEdit($rows, istockout $istrategy) {
		return $istrategy->showProAtEdit($rows);
	}

	/**
	 * �޸�ʱ�����嵥��ʾģ��
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
	 * �޸ĳ��ⵥʱ����װ����ʾģ��
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
                        <img align="absmiddle" src="images/removeline.png" onclick="delPackItem(this);" title="ɾ����">
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
	 * �޸ĳ��ⵥʱ����װ����ʾģ��
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
	 * ���ƺ�ɫ����ʱ����װ����ʾģ��
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
                        <img align="absmiddle" src="images/removeline.png" onclick="delPackItem(this);" title="ɾ����">
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
	 * �鿴 ���ⵥʱ����װ����ʾģ��
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
	 * ������ɫ��ⵥ���ɺ�ɫ��ⵥ���嵥��ʾģ��
	 * @param  $rows
	 * @param  $istrategy
	 */
	function showRelItem($rows, istockout $istrategy) {
		return $istrategy->showRelItem($rows);
	}

	/**
	 * ���ⵥ������ⵥʱ���嵥��ʾģ��
	 * @param
	 */
	function showItemAtInStock($rows, istockout $istrategy) {
		return $istrategy->showItemAtInStock($rows);
	}

	/**
	 * ���к���ϸҳ��
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
			return '<tr align="center" ><td colspan="2">û�������Ϣ</td></tr>';
		}
	}

	/*===================================ҵ����======================================*/

	/**
	 * �������ⵥ
	 */
	function add_d($object) {
		try {
			$this->start_d();
			if(is_array($object['items'])) {
				//���������ֵ��ֶ�
				$datadictDao = new model_system_datadict_datadict();
				$object['moduleName'] = $datadictDao->getDataNameByCode($object['module']);

				$codeDao = new model_common_codeRule();
				$object['docCode'] = $codeDao->stockCode("oa_stock_outstock", $this->stockoutPreArr[$object['docType']]);
				$id = parent::add_d($object, true);

                //���������¼
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->addObjLog($this->tbl_name, $id, $object,
                    $object['docStatus'] == "YSH" ? '��˳���' : '��������');

                if (isset($object['orgId']) && $object['orgId']) {
                    $logSettingDao->addObjLog($this->tbl_name, $object['orgId'],
                        array('docCode' => $object['docCode']), '���ƺ��ֵ���');
                }

				// k3������ش���
				$productinfoDao = new model_stock_productinfo_productinfo();
				$object['items'] = $productinfoDao->k3CodeFormatter_d($object['items']);
                foreach($object['items'] as $key => $itemObj) {
                    //��������һ������
                    if(!isset($itemObj['proType'])||$itemObj['proType']==""){
                        $typeRow=$productinfoDao->getParentType($itemObj['productId']);
                        if(!empty($typeRow)){
                            $object['items'] [$key]['proType']=$typeRow['proType'];
                        }
                    }
                }

				//������ⵥ�����嵥
				$outItemDao = new model_stock_outstock_stockoutitem();
				$itemsArr = $this->setItemMainId("mainId", $id, $object['items']);
				$itemsObj = $outItemDao->saveDelBatch($itemsArr);

				//���ⵥ�������к�
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

				//�������ҵ��
				$stockoutStrategy = $this->stockoutStrategyArr[$object['docType']];
				if($stockoutStrategy) {
					$paramArr = array(//���ݻ�����Ϣ
						'docId' => $id, 'docCode' => $object['docCode'],
						'docType' => $object['docType'], 'docStatus' => $object['docStatus'],
						'relDocId' => $object['relDocId'], 'relDocCode' => $object['relDocCode'],
						'relDocType' => $object['relDocType'], 'isRed' => $object['isRed'],
						'contractId' => $object['contractId'], 'contractType' => $object['contractType']
					);
					$this->ctDealRelInfoAtAdd(new $stockoutStrategy(), $paramArr, $itemsObj);
				} else {
					throw new Exception("�����������δ���ţ�����ϵ������Ա��");
				}
				//��װ�ﴦ��
				if(isset($object['packitem'])) {
					$extraItemDao = new model_stock_outstock_extraitem();
					$stockSystemDao = new model_stock_stockinfo_systeminfo();
					$extraItemDao->delete(array("mainId" => $id));
					$packValArr = array(); //����id��Ϊ�յİ�װ��
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
								//���²ֿ��װ��Ŀ������
								if(!empty($packingVal['productId'])) {
									$inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //���DAO
									$inventoryDao->updateInTimeInfo(array("stockId" => $stockSysObj['packingStockId'], "productId" => $packingVal['productId']), $packingVal['outstockNum'], $packInOut);
								}
							}
						}
					}
				}
				//�ʲ�����-�ύʱ�ʼ�֪ͨ
				if(is_numeric($object['relDocId']) && strlen($object['relDocId']) < 32
					&& $object['relDocType'] == "QTCKZCCK" && $object['docStatus'] == "YSH") {
					$requireinDao = new model_asset_require_requirein();
					$rs = $requireinDao->find(array('id' => $object['relDocId']), null, 'applyId');
					$this->mailDeal_d('assetOutStock', $rs['applyId'], array('id' => $id));
				}

				$this->commit_d();
				return $id;
			} else {
				throw new Exception("������Ϣ������!");
			}

		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ���ø�������
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
	 * �޸ĳ��ⵥ��Ϣ
	 */
	function edit_d($object) {
        // ��ȡ�ɵ���ⵥ��Ϣ
        $org = parent::get_d($object['id']);

		try {
			$this->start_d();
			if(is_array($object['items'])) { //�����嵥
				//���������ֵ��ֶ�
				$datadictDao = new model_system_datadict_datadict();
				$object['moduleName'] = $datadictDao->getDataNameByCode($object['module']);

				$editResult = parent::edit_d($object, true);

                //���������¼
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->compareModelObj($this->tbl_name, $org, $object, $object['docStatus'] == "YSH" ? '��˳���' : '�޸ĳ���');

				// k3������ش���
				$productinfoDao = new model_stock_productinfo_productinfo();
				$object['items'] = $productinfoDao->k3CodeFormatter_d($object['items']);

				//������ⵥ�����嵥
				$outItemDao = new model_stock_outstock_stockoutitem();
				$itemsArr = $this->setItemMainId("mainId", $object['id'], $object['items']);
				$itemsObj = $outItemDao->saveDelBatch($itemsArr);

				//���ⵥ�������к�
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
				//�������ҵ��
				$stockoutStrategy = $this->stockoutStrategyArr[$object['docType']];
				if($stockoutStrategy) {
					$paramArr = array(//���ݻ�����Ϣ
						'docId' => $object['id'], 'docCode' => $object['docCode'],
						'docType' => $object['docType'], 'docStatus' => $object['docStatus'],
						'relDocId' => $object['relDocId'], 'relDocCode' => $object['relDocCode'],
						'relDocType' => $object['relDocType'], 'isRed' => $object['isRed'],
						'contractId' => $object['contractId'], 'contractType' => $object['contractType']);
					$this->ctDealRelInfoAtEdit(new $stockoutStrategy(), $paramArr, $itemsObj);
				} else {
					throw new Exception("�����������δ���ţ�����ϵ������Ա��");
				}

				//��װ�ﴦ��
				if(isset($object['packitem'])) {
					$extraItemDao = new model_stock_outstock_extraitem();
					$stockSystemDao = new model_stock_stockinfo_systeminfo();
					$extraItemDao->delete(array("mainId" => $object['id']));
					$packValArr = array(); //����id��Ϊ�յİ�װ��
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
								//���²ֿ��װ��Ŀ������
								if(!empty($packingVal['productId'])) {
									$inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //���DAO
									$inventoryDao->updateInTimeInfo(array("stockId" => $stockSysObj['packingStockId'], "productId" => $packingVal['productId']), $packingVal['outstockNum'], $packInOut);
								}
							}
						}
					}
				}
				//�ʲ�����-�ύʱ�ʼ�֪ͨ
				if(is_numeric($object['relDocId']) && strlen($object['relDocId']) < 32
					&& $object['relDocType'] == "QTCKZCCK" && $object['docStatus'] == "YSH") {
					$requireinDao = new model_asset_require_requirein();
					$rs = $requireinDao->find(array('id' => $object['relDocId']), null, 'applyId');
					$this->mailDeal_d('assetOutStock', $rs['applyId'], array('id' => $object['id']));
				}

				$this->commit_d();
				return $editResult;
			} else {
				throw new Exception("������Ϣ������!");
			}
		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

    /**
     * ����ɾ������
     */
    function deletes_d($id)
    {
        // ��ȡ�ɵ���ⵥ��Ϣ
        $org = parent::get_d($id);

        try {
            $this->deletes($id);

            //���������¼
            $logSettingDao = new model_syslog_setting_logsetting();
            $logSettingDao->deleteObjLog($this->tbl_name, $org);

            return true;
        } catch(Exception $e) {
            throw $e;
        }
    }

	/**
	 * �������ⵥʱԴ����ҵ����
	 * @param $istrategy ���Խӿ�
	 * @param  $paramArr ->����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
	 * @param  $relItemArr ->�ӱ��嵥��Ϣ
	 */
	function ctDealRelInfoAtAdd(istockout $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtAdd($paramArr, $relItemArr);
	}

	/**
	 * �޸ĳ�������ʱԴ����ҵ����
	 * @param $istrategy ���Խӿ�
	 * @param  $paramArr ->����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
	 * @param  $relItemArr ->�ӱ��嵥��Ϣ
	 */
	function ctDealRelInfoAtEdit(istockout $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtEdit($paramArr, $relItemArr);
	}

	/**
	 * ����˳��ⵥ
	 * @param $id
	 * @param $istrategy
	 */
	function ctCancelAudit($id, istockout $istrategy) {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');	//�����ڴ�
		try {
			$this->start_d();
			$stockoutObj = $this->get_d($id, $istrategy);
			if("YSH" == $stockoutObj['docStatus']) {
				$obj = array("id" => $id, "docStatus" => "WSH");
				$this->updateById($obj);
				if(!$istrategy->cancelAudit($stockoutObj)) {
					throw new Exception("�����ʧ��!");
				}

                //���������¼
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->addObjLog($this->tbl_name, $id, array(), 'ȡ�����');
			} else {
				throw new Exception("�������Ƿ����״̬!");
			}
			$this->commit_d();
			return true;
		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ���ù����ӱ�����뵥id��Ϣ
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
	 * ��������id��ȡ���ⵥ������Ϣ�������嵥��Ϣ
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
	 * ������� - ͬ���ı����ݲ�����ʾ
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
	 * ��ȡ��ǰ��������
	 */
	function rtThisPeriod_d() {
		$periodDao = new model_finance_period_period();
		return $periodDao->rtThisPeriod_d();
	}

	/**
	 * �޸ĳ��ⵥ�۸� �����Ϣ
	 */
	function editCost_d($object) {
		try {
			$this->start_d();
			if(is_array($object['items'])) { //�����嵥
				$editResult = parent::edit_d($object, true);

				//������ⵥ�����嵥
				$outItemDao = new model_stock_outstock_stockoutitem();
				$itemsArr = $this->setItemMainId("mainId", $object['id'], $object['items']);
				$outItemDao->saveDelBatch($itemsArr);

				//��װ�ﴦ��
				if(isset($object['packitem'])) {
					$extraItemDao = new model_stock_outstock_extraitem();
					$stockSystemDao = new model_stock_stockinfo_systeminfo();
					$extraItemDao->delete(array("mainId" => $object['id']));
					$packValArr = array(); //����id��Ϊ�յİ�װ��
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
								//���²ֿ��װ��Ŀ������
								if(!empty($packingVal['productId'])) {
									$inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //���DAO
									$inventoryDao->updateInTimeInfo(array("stockId" => $stockSysObj['packingStockId'], "productId" => $packingVal['productId']), $packingVal['outstockNum'], $packInOut);
								}
							}
						}
					}
				}

				$this->commit_d();
				return $editResult;
			} else {
				throw new Exception("������Ϣ������!");
			}
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ���ݺ�ͬ���� ��ͬid ��ȡ ĳ������ǰ�������Ѿ���˵ĳ��ⵥ
	 */
	function findByContract($docDate, $contactType, $contractId, $docType = "CKSALES") {
		$this->searchArr = array("contractType" => $contactType, "contractId" => $contractId, "docType" => $docType, "endDate" => $docDate, "docStatus" => 'YSH');
		$md5ConfigDao = new model_common_securityUtil("stockout");
		return $md5ConfigDao->md5Rows($this->listBySqlId("select_subcost"));
	}

	/**
	 *
	 *����2011��8��ǰ�ĳ��ⵥ
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
						$stockOutItemObj = array("mainId" => $id, "subCost" => $value['3'], "productName" => '���⵼������', "productCode" => '���⵼������');
						$outStockItemDao->add_d($stockOutItemObj);
						array_push($resultArr, array("docCode" => $value[2], "result" => "����ɹ�!"));
					} else {
						array_push($resultArr, array("docCode" => $value[2], "result" => "����ʧ��,��ͬ��Ϊ" . $value[2] . "�ĺ�ͬ������!"));
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
	 * ��ȡ��ͬʵ�ʳ�������
	 * @param $contractCode ��ͬ���
	 * @param $thisType ���ͣ���Ϊȫ����1Ϊ���֣�0Ϊ����
	 */
	function getOutNumForCont_d($contractCode, $isRed = null) {
		$this->searchArr = array(
			'contractCodeEq' => $contractCode
		);
		//�Ƿ����������
		if($isRed !== null) {
			$this->searchArr['isRed'] = $isRed;
		}
		$this->sort = 'c.id';
		$this->groupBy = 'i.productId';
		$rs = $this->list_d('count_num');
		return $rs;
	}

	/*********************************����ϵͳ��������***********************************************************************/
	/**
	 * ���ⵥ��˺�����ϵͳ���Ͷ���
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
			//�����������
			$addArr = array(
				"applyCode" => $code,
				"buyer" => "",
				"buyerId" => "",
				"dateHope" => date("Y-m-d"),
				"dataType" => "DL",
				"ExaStatus" => "���",
				"contractId" => $contractId,
				"state" => "0"
			);
			//��������
			$nid = $this->handleCreateSql($addArr, "overseas_purch_orders");
			//����ӱ�����
			foreach($equArr as $k => $v) {
				$v['mainId'] = $nid;
				$this->handleCreateSql($v, "overseas_purch_orders_equ");
			}
		}
	}

	//��������insertSQL���������ݿ����Ӳ�ִ��sql���
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

	//�������ݿ����Ӳ�ִ��sql���
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

	/*********************************����ϵͳ��������*******END****************************************************************/
	//������͸�����ֹ�
	/**
	 * �ʼ�����
	 * @p1 ����õ��ʼ���ҵ����� str
	 * @p2 �ʼ������� str
	 * @p3 �ǲ�ѯ�ű��е���Ϣ array
	 * @p4 ������ str
	 * @p5 �Ƿ���˹�˾��Ա boolean
	 * @p6 ��˾���� str
	 */
	function sendToHWStorer_d($infoArr) {
		$this->mailDeal_d("sendToHWStorer", $infoArr['createId'], $infoArr);
	}

	/**
	 * �ʲ�����ʱ�������������Ƿ������д����
	 */
	function checkAudit_d($object) {
		$equItems = $object['items'];
		$requireinitemDao = new model_asset_require_requireinitem();
		foreach($equItems as $key => $val) {
			$rs = $requireinitemDao->find(array('id' => $val['relDocId']), null, 'number,executedNum');
			if($rs['executedNum'] + $val['actOutNum'] > $rs['number']) {
				return 2;//�ϼƳ�������������������
			}
		}
		return 1;//����
	}

	/**
	 * ���³����
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
		if(!empty($object['id'])) {//��ѡ����Ӧ�ĵ���
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
	 * �ڳ�����Ȩƽ����
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
		if(!empty($object['id'])) {//��ѡ����Ӧ�ĵ���
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
	 * ���ݸ������͸��º��ֳ��ⵥ�ĵ���
	 * @param $params
	 * @return int
	 */
	function updateProductPrice_d($params) {

		// ��������
		$num = 0;

		// ��ȡ��������
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

		// �б����ݻ�ȡ
		$this->getParam($params);
		$items = $this->listBySqlId("select_callist");

		// �ǿյ�ʱ��
		if(!empty($items)) {

			// �ȴ����������
			$waitItems = array();

			// ������Ҫ��ѯ������id
			$productIdArr = array();

			// ѭ����������
			foreach($items as $k => $v) {
				// �۸�Ϊ0ʱ��ȥ����
				if($v['cost'] == 0) {

					// ����id����
					if (!in_array($v['productId'], $productIdArr)) {
						$productIdArr[] = $v['productId'];
					}

					// ���������ݻ���
					$waitItems[] = $v;
				}
			}

			// �������Ҫ��������ݣ���ô����е��ۻ�ȡ
			if(!empty($productIdArr)) {

				// ���ݸ������»�ȡ��Ӧ�ĵ��ݽ��
				switch($updateType) {
					case 0: // �������Ȩƽ����
						$stockbalanceDao = new model_finance_stockbalance_stockbalance();
						$priceArr = $stockbalanceDao->getBeginPrice_d($params['thisYear'],
							$params['thisMonth'], $productIdArr);
						break;
					case 1: // ���³����
						$priceArr = $this->getLastOutPrice_d($params['thisYear'],
							$params['thisMonth'], $productIdArr);
						break;
					case 2: // ��������
						$stockinDao = new model_stock_instock_stockin();
						$priceArr = $stockinDao->getLastInPrice_d($productIdArr);
						break;
					default:
				}

				// �۸���
				if(!empty($priceArr)) {

					// �����ӱ��ʼ��
					$stockoutitemDao = new model_stock_outstock_stockoutitem();

					// ����Ҫ����
					foreach($waitItems as $v) {
						$price = $priceArr[$v['productId']];
						if($price && $price != 0) {
							$stockoutitemDao->update(
								array('id' => $v['id']),
								array(
									'cost' => $price,
									'subCost' => abs(round(bcmul($price,$v['actOutNum'],6), 2)) // ����������ֵ������ΪȡֵSQL�õ������Ǹ���
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
	 * ��ȡ�������³����
	 * @param $thisYear
	 * @param $thisMonth
	 * @param $productIdArr
	 * @return int
	 */
	function getLastOutPrice_d($thisYear, $thisMonth, $productIdArr) {

		// ���ؽ��
		$results = array();

		// ��ǰһ���²���
//		if($thisMonth == 1) {
//			$thisYear = $thisYear - 1;
//			$thisMonth = 12;
//		} else {
//			$thisMonth = $thisMonth - 1;
//		}

		if(empty($productIdArr)) {
			return $results;
		}

		// ��ѯ���Ϸ�Χ
		$productIds = implode(",", $productIdArr);

		// ��ѯ���ϵ����³��ⵥ��
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
     * ���ϱ�������ص�ҵ������
     */
	function workflowCallBack_idleScrap($spid){
        try {
            $this->start_d();
            $otherdatas = new model_common_otherdatas();
            $folowInfo = $otherdatas->getWorkflowInfo($spid);
            $objId = $folowInfo['objId'];
            if (!empty ($objId)) {
                $stockoutStrategy = $this->stockoutStrategyArr['CKDLBF'];
                $mainObjOld = parent::get_d($objId);// ��ȡԭ������ⵥ��Ϣ

                if ($mainObjOld['ExaStatus'] == "���") {// ����ͨ��
                    $this->update( " id={$objId}",array("docStatus"=>"YSH"));// ���ⵥ״̬����
                    $mainObj = $this->get_d($objId, new $stockoutStrategy());
                    $itemsObj = $mainObj['items'];

                    //���������¼
                    $logSettingDao = new model_syslog_setting_logsetting();
                    $logSettingDao->compareModelObj($this->tbl_name, $mainObjOld, $mainObj, '��˳���');

                    //���ⵥ�������к�
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

                    //��װ�ﴦ��
                    if(isset($mainObj['packitem'])) {
                        $extraItemDao = new model_stock_outstock_extraitem();
                        $stockSystemDao = new model_stock_stockinfo_systeminfo();
                        $extraItemDao->delete(array("mainId" => $mainObj['id']));
                        $packValArr = array(); //����id��Ϊ�յİ�װ��
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
                                    //���²ֿ��װ��Ŀ������
                                    if(!empty($packingVal['productId'])) {
                                        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //���DAO
                                        $inventoryDao->updateInTimeInfo(array("stockId" => $stockSysObj['packingStockId'], "productId" => $packingVal['productId']), $packingVal['outstockNum'], $packInOut);
                                    }
                                }
                            }
                        }
                    }

                    //�������ҵ��
                    $stockoutStrategy = $this->stockoutStrategyArr[$mainObj['docType']];
                    if($stockoutStrategy) {
                        $paramArr = array(//���ݻ�����Ϣ
                            'docId' => $mainObj['id'], 'docCode' => $mainObj['docCode'],
                            'docType' => $mainObj['docType'], 'docStatus' => $mainObj['docStatus'],
                            'relDocId' => $mainObj['relDocId'], 'relDocCode' => $mainObj['relDocCode'],
                            'relDocType' => $mainObj['relDocType'], 'isRed' => $mainObj['isRed'],
							'contractId' => $mainObj['contractId'], 'contractType' => $mainObj['contractType']);
                        $this->ctDealRelInfoAtEdit(new $stockoutStrategy(), $paramArr, $itemsObj);
                    } else {
                        throw new Exception("�����������δ���ţ�����ϵ������Ա��");
                    }
                }else if($mainObjOld['ExaStatus'] == "���"){// ������ͨ��
                    $this->update( " id={$objId}",array("docStatus"=>"WSH"));// ���ⵥ״̬����
                    $mainObj = $this->get_d($objId, new $stockoutStrategy());

                    //���������¼
                    $logSettingDao = new model_syslog_setting_logsetting();
                    $logSettingDao->compareModelObj($this->tbl_name, $mainObjOld, $mainObj, '��˴��');
                }
            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }
}