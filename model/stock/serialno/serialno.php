<?php
/**
 * @author Administrator
 * @Date 2011��5��17�� 14:10:08
 * @version 1.0
 * @description:�������к�̨�� Model��
 */
class model_stock_serialno_serialno extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_stock_product_serialno";
        $this->sql_map = "stock/serialno/serialnoSql.php";
        parent::__construct();
    }

    /**
     * ���к�ѡ���б�ģ��
     */
    function showChooseList($rows) {
        $str = "";
        if (is_array($rows)) {
            $i = 0;
            foreach ($rows as $key => $val) {
                $seNum = $i + 1;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $lbgColor = (($i % 2) == 0) ? "#fff" : "#F9FAFC";
                $str .= <<<EOT
					<tr id="tr_$val[id]" lbgColor="$lbgColor" class="$classCss" onclick="chooseItem('$val[id]','$val[sequence]')" >
							<td>
							$seNum
							</td>
							<td>

							$val[sequence]
						</td>
					</tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /**
     * ���к�ѡ���б�ģ��
     */
    function showHistory($rows) {
        $str = "";
        if (is_array($rows)) {
            $i = 0;
            foreach ($rows as $key => $val) {
                if ($val ['isRed'] == "0") {
                    $actType = "<b><font color='blue'>����</font></b>";
                } else {
                    $actType = "<b><font color='red'>�˿�</font></b>";
                }

                switch ($val ['contractType']) {
                    case 'oa_sale_order' :
                        $contractType = "���ۺ�ͬ";
                        break;
                    case 'oa_sale_service' :
                        $contractType = "�����ͬ";
                        break;
                    case 'oa_present_present' :
                        $contractType = "����";
                        break;
                    case 'oa_sale_lease' :
                        $contractType = "���޺�ͬ";
                        break;
                    case 'oa_sale_rdproject' :
                        $contractType = "�з���ͬ";
                        break;
                    case 'oa_service_accessorder' :
                        $contractType = "�������";
                        break;
                    default :
                        $contractType = "";
                }

                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $str .= <<<EOT
					<tr  class="$classCss" bgcolor="#ffffff"  >
							<td>
								$actType
							</td>
							<td>
								$val[productCode]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[pattern]
							</td>
							<td>
								$val[auditDate]
							</td>
							<td>
								<a href="#" onclick="viewDocDetail($val[id])" >$val[docCode]<img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>
							</td>
							<td>
								$val[auditerName]
							</td>
							<td>
								$contractType
							</td>
							<td>
								<a href="#" onclick="viewRelDocDetail($val[contractId],'{$val ['contractType']}')" > $val[contractCode]<img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>
							</td>

					</tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

	/**
	 * ����������ʱ���
	 * @param $object
	 * @return bool|null
	 */
    function addTemp_d($object) {
        try {
            $this->start_d();
            $serialnoArr = array();
            $productDao = new model_stock_productinfo_productinfo ();
            $productObj = $productDao->get_d($object ['productId']);

            if ($object ['stockId']) {
                $stockDao = new model_stock_stockinfo_stockinfo ();
                $stockObj = $stockDao->get_d($object ['stockId']);
            } else {
                //��ȡĬ�Ͻ����
                $sysDao = new model_stock_stockinfo_systeminfo();
                $defaultStockObj = $sysDao->find(null, null, 'borrowStockId,borrowStockCode,borrowStockName');
                $stockObj = array(
                    'stockName' => $defaultStockObj['borrowStockName'],
                    'stockCode' => $defaultStockObj['borrowStockCode'],
                    'id' => $defaultStockObj['borrowStockId']
                );
            }

            if ($object ['items']) {
                foreach ($object ['items'] as $value) {
                    if (!empty ($value ['sequence'])) {
                        if ($object ['stockId']) {
                            $serialnoObj = array("sequence" => $value ['sequence'], "seqStatus" => $object ['seqStatus'], "productId" => $productObj ['id'],
                                "productCode" => $productObj ['productCode'], "productName" => $productObj ['productName'], "pattern" => $productObj ['pattern'],
                                "stockId" => $stockObj ['id'], "stockName" => $stockObj ['stockName'], "stockCode" => $stockObj ['stockCode']);
                        } else {//�����ù黹ʱ�����ʱ���
                            $serialnoObj = array("sequence" => $value ['sequence'], "seqStatus" => $object ['seqStatus'],
                                "productId" => $productObj ['id'], "productCode" => $productObj ['productCode'], "productName" => $productObj ['productName'],
                                "pattern" => $productObj ['pattern'],
                                "relDocCode" => $object['relDocCode'], "relDocType" => $object['relDocType'], "relDocId" => $object['relDocId'],
                                "stockId" => $stockObj ['id'], "stockName" => $stockObj ['stockName'], "stockCode" => $stockObj ['stockCode'],
                                "remark" => "�����ù黹�������к�", 'isTemp' => 1);
                        }
                        array_push($serialnoArr, $serialnoObj);
                    }
                }
                $result = $this->addBatch_d($serialnoArr);
            }
            $this->commit_d();
            return $result;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * ��������������ʱ���
     * @see model_base::addBatch_d()
     */
    function addItem_d($object) {
        try {
            $this->start_d();
            $serialnoArr = array();
            $productDao = new model_stock_productinfo_productinfo ();
            $productObj = $productDao->get_d($object ['productId']);
            if ($object ['items']) {
                foreach ($object ['items'] as $key => $value) {
                    if (!empty ($value ['sequence'])) {
                        $serialnoObj = array("sequence" => $value ['sequence'], "remark" => $value ['remark'], "seqStatus" => $object ['seqStatus'], "productId" => $productObj ['id'], "productCode" => $productObj ['productCode'], "productName" => $productObj ['productName'], "pattern" => $productObj ['pattern'], "stockId" => $object ['stockId'], "stockName" => $object ['stockName'], "stockCode" => $object ['stockCode']);
                        array_push($serialnoArr, $serialnoObj);
                    }

                }
                $result = $this->addBatch_d($serialnoArr);
            }
            $this->commit_d();
            return $result;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * ���������޸Ķ���,ͬʱ�ѹ����ĳ���ⵥ�����к��޸Ĺ���
     */
    function edit_d($object) {
        try {
            $this->start_d();
            $oldObj = $this->get_d($object ['id']);
            $instockItemDao = new model_stock_instock_stockinitem ();
            $outstockItemDao = new model_stock_outstock_stockoutitem ();

            //��ⵥ���кŸ���
            if (!empty ($oldObj ['inDocId'])) {
                $instockItemDao->searchArr ['mainId'] = $oldObj ['inDocId'];
                $instockItemDao->searchArr ['productId'] = $oldObj ['productId'];
                $instockItemArr = $instockItemDao->list_d();
                if (is_array($instockItemArr)) {
                    foreach ($instockItemArr as $key => $value) {
                        $serialnoNameArr = explode(",", $value ['serialnoName']);

                        $vKey = array_search($oldObj ['sequence'], $serialnoNameArr); //�����Ƿ�����޸ĵ����к�
                        if ($vKey >= 0) {
                            $serialnoNameArr [$vKey] = $object ['sequence'];
                            $serialnoNameStr = implode(",", $serialnoNameArr);
                            $value ['serialnoName'] = $serialnoNameStr;
                            $instockItemDao->updateById($value); //�������к�
                        }
                    }
                }
            }
            //���ⵥ����
            if (!empty ($oldObj ['outDocId'])) {
                $outstockItemDao->searchArr ['mainId'] = $oldObj ['outDocId'];
                $outstockItemDao->searchArr ['productId'] = $oldObj ['productId'];
                $outstockItemArr = $outstockItemDao->list_d();
                if (is_array($outstockItemArr)) {
                    foreach ($outstockItemArr as $key => $value) {
                        $serialnoNameArr = explode(",", $value ['serialnoName']);

                        $vKey = array_search($oldObj ['sequence'], $serialnoNameArr); //�����Ƿ�����޸ĵ����к�
                        if ($vKey >= 0) {
                            $serialnoNameArr [$vKey] = $object ['sequence'];
                            $serialnoNameStr = implode(",", $serialnoNameArr);
                            $value ['serialnoName'] = $serialnoNameStr;
                            $outstockItemDao->updateById($value); //�������к�
                        }
                    }
                }
            }

            $result = $this->updateById($object);
            $this->commit_d();
            return $result;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     *
     * ����idɾ�����к�
     */
    function deleteSerialno_d($id) {
        try {
            $this->start_d();
            $serialnoObj = $this->get_d($id);

            $instockItemDao = new model_stock_instock_stockinitem ();
            $outstockItemDao = new model_stock_outstock_stockoutitem ();

            //��ⵥ���к�ɾ��
            if (!empty ($serialnoObj ['inDocId'])) {
                $instockItemDao->searchArr ['mainId'] = $serialnoObj ['inDocId'];
                $instockItemDao->searchArr ['productId'] = $serialnoObj ['productId'];
                $instockItemArr = $instockItemDao->list_d();
                if (is_array($instockItemArr)) {
                    foreach ($instockItemArr as $key => $value) {
                        $serialnoNameArr = explode(",", $value ['serialnoName']);
                        $vKey = array_search($serialnoObj ['sequence'], $serialnoNameArr); //�����Ƿ�����޸ĵ����к�
                        if ($vKey >= 0) {
                            unset ($serialnoNameArr [$vKey]); //�ÿ�
                            $serialnoNameStr = implode(",", $serialnoNameArr);
                            $value ['serialnoName'] = $serialnoNameStr;
                            $instockItemDao->updateById($value); //�������к�
                        }
                    }
                }
            }
            //���ⵥ���к�ɾ��
            if (!empty ($serialnoObj ['outDocId'])) {
                $outstockItemDao->searchArr ['mainId'] = $serialnoObj ['outDocId'];
                $outstockItemDao->searchArr ['productId'] = $serialnoObj ['productId'];
                $outstockItemArr = $outstockItemDao->list_d();
                if (is_array($outstockItemArr)) {
                    foreach ($outstockItemArr as $key => $value) {
                        $serialnoNameArr = explode(",", $value ['serialnoName']);
                        $serialnoIdArr = explode(",", $value ['serialnoId']);

                        $vKey = array_search($serialnoObj ['sequence'], $serialnoNameArr); //�����Ƿ�����޸ĵ����к�
                        if ($vKey >= 0) {
                            unset ($serialnoNameArr [$vKey]); //�ÿ�����
                            $serialnoNameStr = implode(",", $serialnoNameArr);
                            $value ['serialnoName'] = $serialnoNameStr;
                            $idKey = array_search($serialnoObj ['id'], $serialnoIdArr);
                            if ($idKey >= 0) {
                                unset ($serialnoIdArr [$vKey]); //�ÿ�id
                                $serialnoIdStr = implode(",", $serialnoIdArr);
                                $value ['serialnoId'] = $serialnoIdStr;
                            }
                            $outstockItemDao->updateById($value); //�������к�
                        }
                    }
                }
            }
            $this->deletes_d($id);
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * ��������嵥id�������к�
     * @param  $inDocItemId
     */
    function findByInItemId($inDocItemId) {
        $this->searchArr ['inDocItemId'] = $inDocItemId;
        return $this->listBySqlId();
    }

	/**
	 * ���ҿ��Ը��Ƶ����к�
	 * @param $relDocId
	 * @param $relDocType
	 * @param $relDocItemId
	 * @param $stockId
	 * @param $ids
	 * @param $idsNot
	 * @return array
	 */
	function findCopySerialNo($relDocId, $relDocType, $relDocItemId, $stockId, $ids, $idsNot) {
		$serialNoArr = array(
			'id' => '',
			'no' => ''
		);
		$this->searchArr = array(
			'relDocId' => $relDocId,
			'relDocType' => $relDocType,
			'relDocItemId' => $relDocItemId,
			'stockId' => $stockId,
			'ids' => $ids,
			'seqStatus' => 0
		);
		if ($idsNot) {
			$this->searchArr['idsNot'] = $idsNot;
		}
		$list = $this->listBySqlId();

		if (count($list) > 0) {
			$idArr = array();
			$noArr = array();
			foreach ($list as $v) {
				$idArr[] = $v['id'];
				$noArr[] = $v['sequence'];
			}
			$serialNoArr['id'] = implode(",", $idArr);
			$serialNoArr['no'] = implode(",", $noArr);
		}

		return $serialNoArr;
	}

    function getSerialRecord($id, $type) {
        $sql = "select * from oa_serialnoRecord_view where id='" . $id . "' and type='" . $type . "' ";
        $row = $this->findSql($sql);
        return $row;
    }

    function showOutStock($row) {
        if ($row[0]['contractType'] != '' && $row[0]['contractId'] != '' && $row[0]['contractId'] != 0) {
            $linkDao = new model_contract_contract_contequlink();
            $linkObj = $linkDao->find(array('contractId' => $row[0]['contractId']));
            $row[0]['linkId'] = $linkObj['id'];
        } else {
            $row[0]['beginTime'] = '';
            $row[0]['closeDate'] = '';
            $row[0]['applyName'] = '';
        }
        return $row;
    }

    function showAllocation($row) {
        if ($row[0]['contractType'] != '' && $row[0]['contractId'] != '' && $row[0]['contractId'] != 0) {
            $borrowDao = new model_projectmanagent_borrow_borrow();
            $borrowObj = $borrowDao->get_d($row[0]['contractId']);
            $row[0]['beginTime'] = $borrowObj['beginTime'];
            $row[0]['closeTime'] = $borrowObj['closeTime'];
            $row[0]['applyName'] = $borrowObj['createName'];
        } else {
            $row[0]['beginTime'] = '';
            $row[0]['closeDate'] = '';
            $row[0]['applyName'] = '';
        }
        return $row;
    }

    /**
     * ������к��Ƿ�Ϊ��ʱ���к�
     */
    function checkTemp_d($ids) {
        $this->searchArr = array('ids' => $ids, 'isTemp' => 1);
        return $this->list_d();
    }

    /**
     * ����ʱ���к�תΪ��ʽ���к�
     */
    function updateTempToFormal_d($ids) {
        try {
            return $this->_db->query("update " . $this->tbl_name . " set isTemp = 0 where isTemp = 1 and id in($ids)");
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ajax ��ȡ��Դ��id
     */
    function ajaxRelDocIdByCode_d($relDocType, $relDocCode) {
        switch ($relDocType) {
            case 'oa_contract_contract' :
                $sql = "select id from oa_contract_contract where contractCode='" . $relDocCode . "' and isTemp=0";
                break;
            case 'oa_borrow_borrow' :
                $sql = "select id from oa_borrow_borrow where Code = '" . $relDocCode . "' and isTemp=0";
                break;
            case 'oa_present_present' :
                $sql = "select id from oa_present_present where Code = '" . $relDocCode . "' and isTemp=0";
                break;
        }
        $idArr = $this->_db->getArray($sql);
        return $idArr[0]['id'];
    }

    /**
     * ajax ��ȡ��Դ��id
     */
    function ajaxGetLimitsById_d($relDocId) {
        $sql = "select limits from oa_borrow_borrow where id = '" . $relDocId . "' and isTemp=0";
        $limitsArr = $this->_db->getArray($sql);
        return $limitsArr[0]['limits'];
    }

    /**
     * ����Ȩ���ж�
     */
    function ajaxlimit_d($relDocType, $relDocId) {
        switch ($relDocType) {
            case 'oa_contract_contract' :
                $sql = "select createId from oa_contract_contract where id='" . $relDocId . "'";
                break;
            case 'oa_borrow_borrow' :
                $sql = "select createId from oa_borrow_borrow where id = '" . $relDocId . "'";
                break;
            case 'oa_present_present' :
                $sql = "select createId from oa_present_present where id = '" . $relDocId . "'";
                break;
        }
        $createIdArr = $this->_db->getArray($sql);
        $createId = $createIdArr[0]['createId'];
        $deptuserDao = new model_deptuser_user_user();
        $userInfo = $deptuserDao->getUserById($createId);
        $deptId = $userInfo['DEPT_ID'];
        $deptName = $userInfo['DEPT_NAME'];
        //��ȡȨ�޲���
        $limitStr = $this->this_limit['����Ȩ��'];
        if (strstr($limitStr, ';;') || strstr($limitStr, $deptId)) {
            return "1";
        } else {
            return $deptName;
        }
    }

    /**
     * ��ʼ�����к�Դ��
     */
    function initSerialNo_d(){
        set_time_limit(0);
        // search serial no
        $sql = "SELECT * FROM oa_stock_product_serialno WHERE stockId = 3 AND seqStatus = 0 AND (relDocType <> 'oa_borrow_borrow' OR relDocType IS NULL)";
        $serialNoArray = $this->_db->getArray($sql);

        $conditionArray = array(); // set the conditon
        foreach($serialNoArray as $v){
            $conditionArray[] = ' FIND_IN_SET("'.$v['sequence'].'",i.serialnoName)';
        }
        unset($serialNoArray);

        // find serialno in allocation item
        $sql = "SELECT a.id,a.contractId,a.contractCode,i.serialnoName FROM
	        oa_stock_allocation_item i LEFT JOIN oa_stock_allocation a ON i.mainId = a.id WHERE a.contractType = 'oa_borrow_borrow' AND (" .
            implode(' OR ',$conditionArray).")";
        $allocationArray = $this->_db->getArray($sql);

        // find did not return form
        $sql = "SELECT c.id,c.Code,SUM(e.executedNum) > SUM(e.backNum) AS isNotBack FROM oa_borrow_borrow c LEFT JOIN oa_borrow_equ e ON c.id = e.borrowId AND c.isTemp = 0 AND e.isTemp = 0 AND e.isDel = 0
            WHERE e.id IS NOT NULL AND c.DeliveryStatus <> 'WFH'
            GROUP BY c.id
            HAVING isNotBack = 1";
        $borrowArray = $this->_db->getArray($sql);
        $borrowArrayFormatted = array();
        foreach($borrowArray as $v){
            $borrowArrayFormatted[$v['id']] = $v['Code'];
        }
        unset($borrowArray);

        // filter no use allocation info
        $sameSerialNoArr = array();
        $allocationArrayFormatted = array();
        foreach($allocationArray as $k => $v){
            if(isset($borrowArrayFormatted[$v['contractId']])){
                $singleSerialNoArray = explode(',',$v['serialnoName']);
                foreach($singleSerialNoArray as $vi){
                    $vi = util_jsonUtil::iconvUTF2GB($vi);
                    $allocationArrayFormatted[] = array(
                        'id' => $v['id'],
                        'contractId' => $v['contractId'],
                        'contractCode' => $v['contractCode'],
                        'serialnoName' => $vi
                    );

                    if(isset($sameSerialNoArr[$vi]))
                        $sameSerialNoArr[$vi]++;
                    else
                        $sameSerialNoArr[$vi] = 1;
                }
            }
        }

        // final format correct data
        foreach($allocationArrayFormatted as $k => $v){
            if($sameSerialNoArr[$v['serialnoName']]%2 == 1){
                echo "UPDATE oa_stock_product_serialno SET relDocId = ".$v['contractId'].",relDocCode = '".$v['contractCode'].
                    "',relDocType='oa_borrow_borrow' WHERE sequence = '".util_jsonUtil::iconvUTF2GB($v['serialnoName']).
                    "' AND stockId = 3 AND seqStatus = 0;";
            }
        }
    }

	/**
	 * �Զ��������к�
	 * @param $objInfo
	 * @param $serialSequence
	 * @param $serialRemark
	 * @param string $dealType 0 �� / 1 ��
	 * @return bool
	 * @throws Exception
	 */
	function autoDeal_d($objInfo, $serialSequence, $serialRemark, $dealType = '0') {

		// ��ȡ�ɵ����к���Ϣ
		$oldSerialNo = $dealType ?
			$this->findAll(array('inDocItemId' => $objInfo['inDocItemId'], 'seqStatus' => 0), null, 'id,sequence') :
			$this->findAll(array('outDocItemId' => $objInfo['outDocItemId'], 'seqStatus' => 0), null, 'id,sequence');

		// �����к�
		$serialArr = explode(',', $serialSequence);
		$serialRemarkArr = explode(',', $serialRemark);

		$newSerialArr = array();
		foreach ($serialArr as $k => $v) {
			$newSerialArr[$v] = array(
				'sequence' => $v,
				'remark' => $serialRemarkArr[$k]
			);
		}

		// ���õ����к�
		$uselessIdArr = array();

		// ������ھɵ����кţ������ϲ�������������
		if ($oldSerialNo) {
			foreach ($oldSerialNo as $v) {
				// ������ھɵ����ݣ����ͬid�����к���
				if (isset($newSerialArr[$v['sequence']])) {
					$newSerialArr[$v['sequence']]['id'] = $v['id'];
				} else {
					$uselessIdArr[] = $v['id'];
				}
			}
		}

		try {

			// �������޸ĵĴ���
			foreach ($newSerialArr as $v) {
				$objInfo['sequence'] = $v['sequence'];
				$objInfo['remark'] = $v['remark'];

				// �޸ĵĲ���
				if (isset($v['id'])) {
					$this->update(array('id' => $v['id']), $objInfo);
				} else {
					$this->add_d($objInfo);
				}
			}

			// ɾ�������Ĳ���
			if (!empty($uselessIdArr)) {
				$this->deletes(implode(',', $uselessIdArr));
			}

			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}
}