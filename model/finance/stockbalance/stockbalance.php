<?php

/**
 * @author Show
 * @Date 2011��5��31�� ���ڶ� 19:31:17
 * @version 1.0
 * @description:�ڳ����� Model��
 */
class model_finance_stockbalance_stockbalance extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_finance_stockbalance";
        $this->sql_map = "finance/stockbalance/stockbalanceSql.php";
        parent::__construct();
    }
    /***************************ҳ����ʾ��******************************/

    /**
     * ��ʾ�����б�
     * @param $rows
     * @return null|string
     */
    function showStockBalance($rows)
    {
        $str = null;
        $i = 0;
        if ($rows) {
            foreach ($rows as $val) {
                $i++;
                $classCss = (($i % 2) == 0) ? 'tr_even' : 'tr_odd';
                $str .= <<<EOT
					<tr class="$classCss">
						<td>
							$val[thisDate]
							<input type="hidden" name="costajust[$i][stockbalId]" value="$val[id]"/>
							<input type="hidden" name="costajust[$i][formDate]" value="$val[thisDate]"/>
							<input type="hidden" name="costajust[$i][stockName]" value="$val[stockName]"/>
							<input type="hidden" name="costajust[$i][stockId]" value="$val[stockId]"/>
							<input type="hidden" name="costajust[$i][stockCode]" value="$val[stockCode]"/>
						</td>
						<td>
							$val[stockName]
						</td>
						<td>
							$val[productNo]
						</td>
						<td>
							$val[productName]
						</td>
						<td>
							$val[productModel]
						</td>
						<td>
							$val[clearingNum]
						</td>
						<td class='formatMoney'>
							$val[balanceAmount]
						</td>
						<td>
							<input type="text" class="txtmiddle formatMoney" id="ajustAmount$i" name="costajust[$i][detail][1][ajustAmount]" value="0"/>
							<input type="hidden" name="costajust[$i][detail][1][productNo]" value="$val[productNo]"/>
							<input type="hidden" name="costajust[$i][detail][1][productName]" value="$val[productName]"/>
							<input type="hidden" name="costajust[$i][detail][1][productModel]" value="$val[productModel]"/>
							<input type="hidden" name="costajust[$i][detail][1][productId]" value="$val[productId]"/>
							<input type="hidden" name="costajust[$i][detail][1][balanceAmount]" value="$val[balanceAmount]"/>
							<input type="hidden" name="costajust[$i][detail][1][remark]" value="�ڳ��ִ�����������"/>
						</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * ��Ʒ���ҳ��
     * @param $rows
     * @return array
     */
    function showProductInList($rows)
    {
        $i = 0;
        $str = null;
        $num = $amount = 0;
        foreach ($rows as $val) {
            $i++;
            $classCss = (($i % 2) == 0) ? 'tr_even' : 'tr_odd';
            $num = bcadd($val['actNum'], $num);
            $amount = bcadd($val['subPrice'], $amount, 2);
            $str .= <<<EOT
                <tr class="$classCss">
                    <td>
                        $i
                    </td>
                    <td>
                        $val[productCode]
                    </td>
                    <td>
                        $val[k3Code]
                    </td>
                    <td>
                        $val[productName]
                        <input type="hidden" name="invpurchase[invpurdetail][$i][productId]" id="productId$i" value="$val[productId]"/>
                    </td>
                    <td>
                        $val[pattern]
                    </td>
                    <td>
                        $val[batchNum]
                    </td>
                    <td style="text-align:right; padding-right:10px;">
                        $val[actNum]
                    </td>
                    <td>
                        $val[unitName]
                    </td>
                    <td class="formatMoney" style="text-align:right; padding-right:10px;">
                        $val[price]
                    </td>
                    <td class="formatMoney" style="text-align:right; padding-right:10px;">
                        $val[subPrice]
                    </td>
                </tr>
EOT;
        }
        if ($i) {
            $str .=<<<EOT
                <tr class="tr_count">
                    <td>
                    </td>
                    <td>
                        �ϼ�
                    </td>
                    <td colspan="4">
                    </td>
                    <td style="text-align:right; padding-right:10px;">
                        $num
                    </td>
                    <td colspan="2">
                    </td>
                    <td class="formatMoney" style="text-align:right; padding-right:10px;">
                        $amount
                    </td>
                </tr>
EOT;
        }
        return array($str, $i);
    }

    /**
     * ��Ʒ���ҳ��
     * @param $rows
     * @return array
     */
    function showProductInListDept($rows)
    {
        $i = 0;
        $str = null;
        $num = $amount = 0;
        foreach ($rows as $val) {
            $i++;
            $classCss = (($i % 2) == 0) ? 'tr_even' : 'tr_odd';
            $num = bcadd($val['actNum'], $num);
            $amount = bcadd($val['subPrice'], $amount, 2);
            $str .= <<<EOT
                <tr class="$classCss">
                    <td>
                        $i
                    </td>
                    <td>
                        $val[purchaserName]
                    </td>
                    <td>
                        $val[productCode]
                    </td>
                    <td>
                        $val[k3Code]
                    </td>
                    <td>
                        $val[productName]
                        <input type="hidden" name="invpurchase[invpurdetail][$i][productId]" id="productId$i" value="$val[productId]"/>
                        <input type="hidden" id="purchaserCode$i" name="invpurchase[invpurdetail][$i][purchaserCode]" value="$val[purchaserCode]"/>
                    </td>
                    <td>
                        $val[pattern]
                    </td>
                    <td>
                        $val[batchNum]
                    </td>
                    <td style="text-align:right; padding-right:10px;">
                        $val[actNum]
                    </td>
                    <td>
                        $val[unitName]
                    </td>
                    <td class="formatMoney" style="text-align:right; padding-right:10px;">
                        $val[price]
                    </td>
                    <td class="formatMoney" style="text-align:right; padding-right:10px;">
                        $val[subPrice]
                    </td>
                </tr>
EOT;
        }
        if ($i) {
            $str .= <<<EOT
                <tr class="tr_count">
                    <td>
                    </td>
                    <td>
                        �ϼ�
                    </td>
                    <td colspan="5">
                    </td>
                    <td style="text-align:right; padding-right:10px;">
                        $num
                    </td>
                    <td colspan="2">
                    </td>
                    <td class="formatMoney" style="text-align:right; padding-right:10px;">
                        $amount
                    </td>
                </tr>
EOT;
        }
        return array($str, $i);
    }

    /**
     * ��ӯ������
     * @param $rows
     * @return array
     */
    function showOverageCalList($rows)
    {
        $i = 0;
        $str = null;
        if ($rows) {
            foreach ($rows as $val) {
                $i++;
                $classCss = (($i % 2) == 0) ? 'tr_even' : 'tr_odd';
                $str .= <<<EOT
                    <tr class="$classCss">
                        <td>
                            $i
                        </td>
                        <td>
                            $val[productCode]
                        </td>
                        <td>
                            $val[productName]
                            <input type="hidden" name="invpurchase[invpurdetail][$i][productId]" id="productId$i" value="$val[productId]"/>
                        </td>
                        <td>
                            $val[pattern]
                        </td>
                        <td>
                            $val[batchNum]
                        </td>
                        <td>
                            $val[unitName]
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$i][number]" id="number$i" value="$val[actNum]" class="readOnlyTxtShort formatMoney"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$i][price]" id="price$i" value="$val[price]" onblur="FloatMul('price$i','number$i','subPrice$i');" class="txtmiddle formatMoneySix"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$i][subPrice]" id="subPrice$i" value="$val[subPrice]" onblur="FloatDivSix('subPrice$i','number$i','price$i',6);" class="txtmiddle formatMoney"/>
                            <input type="hidden" name="invpurchase[invpurdetail][$i][subPrice]" id="oldSubPrice$i" value="$val[subPrice]"/>
                        </td>
                    </tr>
EOT;
            }
        }
        return array($str, $i);
    }

    /**
     * ��������
     * @param $rows
     * @return null|string
     */
    function showAllDetail($rows)
    {
        $str = null;
        $i = 0;
        if ($rows) {
            $markStockId = null;
            $markProductId = null;
            $formType = null;
            $formNo = null;
            $balAmount = $balNumber = $outPrice = 0;
            foreach ($rows as $key => $val) {
                //��������
                switch ($val['formType']) {
                    case 'balance' :
                        $formType = '�ڳ����';
                        break;
                    case 'costAdjust' :
                        $formType = '�ɱ�������';
                        break;
                    case 'adjustment' :
                        $formType = '���';
                        break;
                    case 'RKPURCHASE' :
                        $formType = '�⹺���';
                        break;
                    case 'RKPRODUCT' :
                        $formType = '��Ʒ���';
                        break;
                    case 'RKOTHER' :
                        $formType = '�������';
                        break;
                    case 'allo-in' :
                        $formType = '������-��';
                        break;
                    case 'CKSALES' :
                        $formType = '���۳���';
                        break;
                    case 'CKPICKING' :
                        $formType = '���ϳ���';
                        break;
                    case 'CKOTHER' :
                        $formType = '��������';
                        break;
                    case 'allo-out' :
                        $formType = '������-��';
                        break;
                    default :
                        $formType = $val['formType'];
                }
                //��������
                if ($val['isRed'] == 1) {
                    $formNo = '<span class="red">' . $val['formNo'] . '</span>';
                } else {
                    $formNo = $val['formNo'];
                }

                //����
                if ($val['inAmount'] != 0 || $val['inNumber'] != 0) {
                    if ($val['sortNo'] != 4) {
                        $calStr = $val['inAmount'];
                    } else {
                        if ($val['inPrice'] != 0)
                            $calStr = $val['inNumber'] . ' X <span class="formatMoney">' . $val['inPrice'] . '</span> = <span class="formatMoney">' . $val['inAmount'] . '</span>';
                        else
                            $calStr = $val['inNumber'] . ' X <span class="red">' . $val['inPrice'] . '</span> = <span class="red">' . $val['inAmount'] . '</span>';
                    }

                } else {
                    if ($outPrice == 0) {
                        $outPrice = round($balAmount / $balNumber, 6);
                        $i++;
                        $classCss = (($i % 2) == 0) ? 'tr_even' : 'tr_odd';

                        $str .= <<<EOT
							<tr class="$classCss">
								<td colspan="6">
								</td>
								<td>
									<span style="color:blue">���ⲿ��
								</td>
								<td colspan="2">
								</td>
							</tr>
EOT;
                    }
                    $calStr = '<span class="red">' . -$val['outNumber'] . ' X <span class="formatMoney">' . $val['outPrice'] . '</span> = -<span class="formatMoney">' . $val['outAmount'] . '</span></span>';
                }

                //��洦��
                if ($val['sortNo'] != 5) {
                    if ($val['sortNo'] == 1 || $val['sortNo'] == 4) {
                        $balNumber = bcadd($balNumber, $val['inNumber'], 2);
                    }
                    $balAmount = bcadd($balAmount, $val['inAmount'], 2);
                } else {
                    $balNumber = bcsub($balNumber, $val['outNumber'], 2);
                    $balAmount = bcsub($balAmount, $val['outAmount'], 2);
                    $val['balNumber'] = $balNumber;
                    $val['balAmount'] = $balAmount;
                }

                $i++;
                $classCss = (($i % 2) == 0) ? 'tr_even' : 'tr_odd';
                if ($markStockId == $val['stockId'] && $markProductId == $val['productId']) {
                    $str .= <<<EOT
                        <tr class="$classCss">
                            <td>
                            </td>
                            <td>
                            </td>
                            <td>
                            </td>
                            <td>
                                $val[formDate]
                            </td>
                            <td>
                                $formType
                            </td>
                            <td>
                                $formNo
                            </td>
                            <td>
                                $calStr
                            </td>
                            <td class='formatMoney'>
                                $balNumber
                            </td>
                            <td class='formatMoney'>
                                $balAmount
                            </td>
                        </tr>
EOT;
                } else {
                    if ($key != 0) {
                        $str .= <<<EOT
                            <tr class="$classCss">
                                <td colspan="9"></td>
                            </tr>
EOT;
                    }
                    $balAmount = $balNumber = $outPrice = 0;
                    $balNumber = bcadd($balNumber, $val['inNumber'], 2);
                    $balAmount = bcadd($balAmount, $val['inAmount'], 2);
                    $i++;
                    $classCss = (($i % 2) == 0) ? 'tr_even' : 'tr_odd';
                    $str .= <<<EOT
                        <tr class="$classCss">
                            <td>
                                $val[stockName]
                            </td>
                            <td>
                                $val[productNo]
                            </td>
                            <td>
                                $val[productName]
                            </td>
                            <td>
                                $val[formDate]
                            </td>
                            <td>
                                $formType
                            </td>
                            <td>
                                $formNo
                            </td>
                            <td>
                                $calStr
                            </td>
                            <td class='formatMoney'>
                                $balNumber
                            </td>
                            <td class='formatMoney'>
                                $balAmount
                            </td>
                        </tr>
EOT;
                    $markStockId = $val['stockId'];
                    $markProductId = $val['productId'];
                }
            }
        }
        return $str;
    }

    /**
     * ������ϸ�б�
     * @param $rows
     * @param $object
     * @return null|string
     */
    function showResultProduct($rows, $object)
    {
        $str = null;
        $i = 0;
        $thisYear = $object['thisYear'];
        $thisMonth = $object['thisMonth'];
        $checkType = 2;
        $properties = $object['properties'];
        if ($rows) {
            foreach ($rows as $val) {
                $i++;
                $classCss = (($i % 2) == 0) ? 'tr_even' : 'tr_odd';
                $thisResult = $val['isSuccess'] ? "����ɹ�" : "<span class='red'>����ʧ��</span>";
                $productId = $val['productId'];
                $str .= <<<EOT
                    <tr class="$classCss">
                        <td>
                            $val[productNo]
                        </td>
                        <td>
                            $val[productName]
                        </td>
                        <td>
                            $val[productModel]
                        </td>
                        <td>
                            $thisResult
                        </td>
                        <td>
                            <a href="#" onclick="showModalWin('?model=finance_stockbalance_stockbalance&action=allCalDetail&thisYear=$thisYear&thisMonth=$thisMonth&properties=$properties&checkType=$checkType&productId=$productId',1)">������ϸ</a>
                        </td>
                    </tr>
EOT;
            }
        }
        return $str;
    }

    /***************************ҳ����ʾ��******************************/
    /**
     * �����ֿ��ڳ���Ϣʱͬ�������ڳ�����¼
     * �ṩ��������ֶ�����
     * productId(����id),productNo(���ϱ��),productName(��������),productModel(�����ͺ�),
     * units(��λ),clearingNum(�������),balanceAmount(���),price(����),stockId(�ֿ�id),
     * stockName(�ֿ�����),stockCode(�ֿ���),inventoryId(���id)
     * @param $object
     * @return bool
     */
    function addStockBalance_d($object)
    {
        if ($object) {
            $periodRs = $this->rtThisPeriod_d();
            $object['thisYear'] = $periodRs['thisYear'];
            $object['thisMonth'] = $periodRs['thisMonth'];
            $object['thisDate'] = $periodRs['thisDate'];
            $object['periodNo'] = $periodRs['thisYear'] . '.' . $periodRs['thisMonth'];
            $object['isDeal'] = 0;
            $object['actInNum'] = 0;
            $object['actInAmount'] = 0;
            $object['actOutNum'] = 0;
            $object['actOutAmount'] = 0;
            return $this->add_d($object);
        } else
            return false;
    }

    /**
     * ������������¼
     * �ṩ��������ֶ�����
     * productId(����id),productNo(���ϱ��),productName(��������),productModel(�����ͺ�),
     * units(��λ),clearingNum(�������),balanceAmount(���),price(����),stockId(�ֿ�id),
     * stockName(�ֿ�����),stockCode(�ֿ���),inventoryId(���id)
     * @param $object
     */
    function addStockBalanceBatch_d($object)
    {
        if ($object) {
            $periodRs = $this->rtThisPeriod_d();
            $thisYear = $periodRs['thisYear'];
            $thisMonth = $periodRs['thisMonth'];
            $formDate = $periodRs['thisDate'];
            $periodNo = $thisYear . '.' . $thisMonth;
            $sql = 'INSERT INFO ' . $this->tbl_name . '(thisYear,thisMonth,productId,productNo,productName,productModel,
                units,clearingNum,balanceAmount,price,stockId,stockName,stockCode,thisDate,actInNum,actInAmount,
                actOutNum,actOutAmount,inventoryId,periodNo) VALUES ';
            foreach ($object as $key => $val) {
                if ($key) {
                    $sql .= ",('" . $thisYear . "','" . $thisMonth . "','" . $val['productId'] . "','" . $val['productNo'] . "','" . $val['productName'] . "','" . $val['productModel'] . "','" . $val['units'] . "','" . $val['clearingNum'] . "','" . $val['balanceAmount'] . "','" . $val['price'] . "','" . $val['stockId'] . "','" . $val['stockName'] . "','" . $val['stockCode'] . "','" . $formDate . "',0,0,0,0," . $val['inventoryId'] . ",'$periodNo')";
                } else {
                    $sql .= "('" . $thisYear . "','" . $thisMonth . "','" . $val['productId'] . "','" . $val['productNo'] . "','" . $val['productName'] . "','" . $val['productModel'] . "','" . $val['units'] . "','" . $val['clearingNum'] . "','" . $val['balanceAmount'] . "','" . $val['price'] . "','" . $val['stockId'] . "','" . $val['stockName'] . "','" . $val['stockCode'] . "','" . $formDate . "',0,0,0,0," . $val['inventoryId'] . ",'$periodNo')";
                }
            }
            $this->_db->query($sql);
        }
    }

    /**
     * ����inventoryId �޸Ĵ����Ϣ
     * @param $object
     * @return bool
     */
    function editByInventoryId_d($object)
    {
        return $this->update(array('inventoryId' => $object['inventoryId']), $object);
    }

    /**
     * ����inventoryIdɾ�������Ϣ
     * @param $inventoryId
     */
    function deleteByInventoryId_d($inventoryId)
    {
        return $this->delete(array('inventoryId' => $inventoryId));
    }

    /**
     * ����id��ȡ����
     * @param $ids
     * @return mixed
     */
    function getStockBalance_d($ids)
    {
        $this->searchArr['ids'] = $ids;
        return $this->list_d();
    }

    /**
     * ����id�޸�״̬
     * @param $ids
     * @param int $isDeal
     * @return mixed
     */
    function updateIsDeal_d($ids, $isDeal = 1)
    {
        $sql = 'update ' . $this->tbl_name . ' set isDeal = ' . $isDeal . ' where id in (' . $ids . ')';
        return $this->_db->query($sql);
    }

    /**
     * ������������¼
     * @param $object
     * @param $thisYear
     * @param $thisMonth
     */
    function batchAdd_d($object, $thisYear, $thisMonth)
    {
        if ($object) {
            if ($thisMonth == 12) {
                $thisYear++;
                $thisMonth = 1;
            } else {
                $thisMonth++;
            }
            $formDate = $thisYear . '-' . $thisMonth . '-01';
            $sql = 'INSERT INFO ' . $this->tbl_name . '(thisYear,thisMonth,productId,productNo,productName,productModel,
                units,clearingNum,balanceAmount,price,stockId,stockName,stockCode,thisDate,actInNum,actInAmount,
                actOutNum,actOutAmount) VALUES ';
            foreach ($object as $key => $val) {
                if ($key) {
                    $sql .= ",('" . $thisYear . "','" . $thisMonth . "','" . $val['productId'] . "','" . $val['productNo'] . "','" . $val['productName'] . "','" . $val['productModel'] . "','" . $val['units'] . "','" . $val['clearNum'] . "','" . $val['balance'] . "','" . $val['thisProPrice'] . "','" . $val['stockId'] . "','" . $val['stockName'] . "','" . $val['stockCode'] . "','" . $formDate . "','" . $val['inNumber'] . "','" . $val['inAmount'] . "','" . $val['outNumber'] . "','" . $val['outAmount'] . "')";
                } else {
                    $sql .= "('" . $thisYear . "','" . $thisMonth . "','" . $val['productId'] . "','" . $val['productNo'] . "','" . $val['productName'] . "','" . $val['productModel'] . "','" . $val['units'] . "','" . $val['clearNum'] . "','" . $val['balance'] . "','" . $val['thisProPrice'] . "','" . $val['stockId'] . "','" . $val['stockName'] . "','" . $val['stockCode'] . "','" . $formDate . "','" . $val['inNumber'] . "','" . $val['inAmount'] . "','" . $val['outNumber'] . "','" . $val['outAmount'] . "')";
                }
            }
            $this->_db->query($sql);
        }
    }

    /**
     * ��������ɾ�����
     * @param $thisYear
     * @param $thisMonth
     * @param string $productType
     * @param null $object
     * @return mixed
     */
    function deleteByDate_d($thisYear, $thisMonth, $productType = 'WLSXZZ', $object = null)
    {
        if ($thisMonth == 12) {
            $thisYear++;
            $thisMonth = 1;
        } else {
            $thisMonth++;
        }
        $productId = "";
        if (!empty($object['productId'])) {
            $productId = " and id = '" . $object['productId'] . "'";
        }
        if (!empty($object['productNoBegin'])) {
            $productId = " and (productCode between '" . $object['productNoBegin'] . "' and '" . $object['productNoEnd'] . "')";
        }
        $thisDate = $thisYear . '-' . $thisMonth . '-01';

        $sql = "DELETE
            FROM
                " . $this->tbl_name . "
            WHERE
                TO_DAYS(thisDate) >= TO_DAYS('$thisDate')
                AND
                productId IN(
                    SELECT id FROM oa_stock_product_info WHERE properties = '$productType' $productId
                )";
        return $this->_db->query($sql);
    }

    /**
     * ��ȡ��ǰ������Ϣ
     * @param null $thisType
     * @return bool|mixed
     */
    function rtThisPeriod_d($thisType = null)
    {
        $periodDao = new model_finance_period_period();
        return $periodDao->rtThisPeriod_d($thisType);
    }

    /**************************************�⹺�����㲿��********************************/

    /**
     * �������
     * sql��:
     * 1.�⹺����(������ⵥ���뵽��������м���)
     * 2.����
     * 3.��������
     * @param $thisYear
     * @param $thisMonth
     * @return bool
     */
    function calculate_d($thisYear, $thisMonth)
    {
        try {
            $this->start_d();

            // ���ڹ������ݼ۸���
            $this->updatePurchaseInStock($thisYear, $thisMonth);

            //��ȡ�����Ѿ����в������Ϣ
            $adjustDao = new model_finance_adjust_adjust();
            $relatedIds = $adjustDao->getAdjustRelatedIds_d($thisYear, $thisMonth);

            //��ȡ���
            $adjustArr = $this->getAdjustRows_d($thisYear, $thisMonth, $relatedIds);
            if ($adjustArr) {
                //�����¼Id
                $markId = null;
                //ͬ����������
                $inArr = array();
                //���ɲ��
                foreach ($adjustArr as $val) {
                    $val['cost'] = $val['price'];
                    $val['price'] = $val['rPrice'];
                    $val['productNo'] = $val['productCode'];
                    $val['number'] = $val['rHookNumber'];
                    $val['stockId'] = $val['instockId'];
                    $val['stockName'] = $val['instockName'];
                    $inArr[$val['relatedId']][] = $val;
                }
            }

            if (!empty($inArr)) {
                $formDate = $thisYear . '-' . $thisMonth . '-01';
                foreach ($inArr as $k => $v) {
                    $adjustDao->addForCal_d($v, $k, $formDate);
                }
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���±��ڵ��⹺���ļ۸�
     * @param $thisYear
     * @param $thisMonth
     * @return mixed
     * @throws Exception
     */
    function updatePurchaseInStock($thisYear, $thisMonth)
    {
        try {
            // ȫ�����������ݴ���
            $sql = "SELECT
                    i.id,(SUM(d.AMOUNT) + MIN(d.unHookAmount)) / (SUM(d.number) + MIN(d.unHookNumber)) AS price,
                    (SUM(d.AMOUNT) + MIN(d.unHookAmount)) AS amount,
                    GROUP_CONCAT(d.id)
                FROM
                    oa_stock_instock c
                    INNER JOIN oa_stock_instock_item i ON c.id = i.mainId
                    INNER JOIN oa_finance_related_detail d ON d.hookId = i.id AND d.hookObj = 'storage'
                WHERE
                    c.docStatus = 'YSH' AND c.docType = 'RKPURCHASE' AND c.catchStatus = 'CGFPZT-YGJ'
                    AND YEAR(auditDate) = $thisYear AND MONTH(c.auditDate) = $thisMonth
                GROUP BY i.id";
            $data = $this->_db->getArray($sql);

            // ���³��ⵥ
            if (!empty($data)) {
                foreach ($data as $v) {
                    $sql = "UPDATE oa_stock_instock_item
                        SET price = {$v["price"]}, subPrice = {$v['amount']}, isUnhookCal = 0
                        WHERE id = {$v["id"]}";
                    $this->_db->query($sql);
                }
            }

            // ���ڱ���ֻ���˲��ֹ����ģ�����ƥ�����
            $sql = "SELECT
                    i.id,(SUM(d.AMOUNT) + MIN(d.unHookAmount)) / (SUM(d.number) + MIN(d.unHookNumber)) AS price,
                    (SUM(d.AMOUNT) + MIN(d.unHookAmount)) AS amount,
                    GROUP_CONCAT(d.id)
                FROM
                    oa_stock_instock c
                    INNER JOIN oa_stock_instock_item i ON c.id = i.mainId
                    INNER JOIN oa_finance_related_detail d ON d.hookId = i.id AND d.hookObj = 'storage'
                WHERE
                    c.docStatus = 'YSH' AND c.docType = 'RKPURCHASE' AND c.catchStatus = 'CGFPZT-BFGJ'
                    AND YEAR(auditDate) = $thisYear AND MONTH(c.auditDate) = $thisMonth
                GROUP BY i.id";
            $data = $this->_db->getArray($sql);

            // ���³��ⵥ
            if (!empty($data)) {
                foreach ($data as $v) {
                    $sql = "UPDATE oa_stock_instock_item
                        SET price = {$v["price"]}, subPrice = {$v['amount']}, isUnhookCal = 0
                        WHERE id = {$v["id"]}";
                    $this->_db->query($sql);
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * �޸��ѹ����Ľ��
     * @param $thisYear
     * @param $thisMonth
     * @return mixed
     */
    function changeHookedItem_d($thisYear, $thisMonth)
    {
        return $this->_db->query("UPDATE oa_stock_instock i LEFT JOIN oa_stock_instock_item t ON i.id = t.mainId
                SET t.subPrice = t.hookAmount,t.price = round(hookAmount/hookNumber,6)
            where i.docStatus = 'YSH' AND YEAR(i.auditDate)= $thisYear AND MONTH(i.auditDate) = $thisMonth
                AND t.hookNumber = t.actNum ");
    }

    /**
     * �޸Ĺ��ۺ�����
     * @param $thisYear
     * @param $thisMonth
     * @return mixed
     */
    function changeValuation_d($thisYear, $thisMonth)
    {
        return $this->_db->query("UPDATE oa_stock_instock i LEFT JOIN oa_stock_instock_item t ON i.id = t.mainId
                SET t.subPrice = t.actNum* t.price
            where i.docStatus = 'YSH' AND t.price <>0
                AND YEAR(i.auditDate)= $thisYear AND MONTH(i.auditDate) = $thisMonth
                AND t.hookNumber <> t.actNum");
    }

    /**
     * ��ȡ���ڲ���ĵ���
     * @param $thisYear
     * @param $thisMonth
     * @param $relatedIds
     * @return array
     */
    function getAdjustRows_d($thisYear, $thisMonth, $relatedIds)
    {
        if (!empty($relatedIds)) {
            $relatedIds = " and relatedId not in ($relatedIds)";
        }

        // ʣ����Ҫ����Ĺ�����¼ID
        $lastRelatedIds = array();
        // ��ƥ�䱾�ڹ����Ĳɹ���Ʊ
        $sql = "SELECT d.relatedId FROM oa_finance_related_detail d
            WHERE YEAR(d.formDate) = $thisYear AND MONTH(d.formDate) = $thisMonth AND d.hookObj = 'invpurchase'
            $relatedIds
            GROUP BY d.relatedId";
        $data = $this->_db->getArray($sql);
        // ���ڹ�����¼Id
        if (!empty($data)) {
            foreach ($data as $v) {
                if (!in_array($v['relatedId'], $lastRelatedIds)) $lastRelatedIds[] = $v['relatedId'];
            }

            // ����
            $yearMonth = $thisYear . str_pad($thisMonth, 2, 0, STR_PAD_LEFT);
            // ��ȡƥ��������ϸ�嵥
            $sql = "SELECT hookId FROM oa_finance_related_detail d
                WHERE d.relatedId IN(" . implode(',', $lastRelatedIds) . ") AND d.hookObj = 'storage'
				    AND DATE_FORMAT(d.formDate, '%Y%m') < $yearMonth
                GROUP BY d.hookId";
            $data = $this->_db->getArray($sql);

            $inStockItemIds = array();
            foreach ($data as $v) {
                if (!in_array($v['hookId'], $inStockItemIds)) $inStockItemIds[] = $v['hookId'];
            }

            if (!empty($inStockItemIds)) {
                // ��ȡƥ��Ǳ����⹺��ⵥ
                $sql = "SELECT
                    c.docCode,c.supplierId,c.supplierName,
                    i.productId,i.productName,i.productCode,i.pattern as productModel,i.instockId,i.instockName,
                    i.actNum,i.price,i.subPrice,
                    (SUM(d.hookAmount) + MIN(d.unHookAmount)) / (SUM(d.number) + MIN(d.unHookNumber)) AS rPrice,
                    i.actNum AS rHookNumber,
                    (SUM(d.hookAmount) + MIN(d.unHookAmount)) / (SUM(d.number) + MIN(d.unHookNumber)) - i.price AS differ,
                    round((SUM(d.hookAmount) + MIN(d.unHookAmount)), 2) - round(i.subPrice, 2) AS allDiffer,
                    MAX(relatedId) AS relatedId
                FROM
                    oa_stock_instock c
                    INNER JOIN oa_stock_instock_item i ON c.id = i.mainId
                    INNER JOIN oa_finance_related_detail d ON d.hookId = i.id AND d.hookObj = 'storage'
                WHERE
                    c.docStatus = 'YSH' AND c.docType = 'RKPURCHASE' AND c.catchStatus IN('CGFPZT-YGJ','CGFPZT-BFGJ')
                    AND i.id IN(" . implode(',', $inStockItemIds) . ")
                GROUP BY i.id";
                return $this->_db->getArray($sql);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**************************************�⹺�����㲿��********************************/

    /*************************************�������***********************************/
    /**
     * �������
     * ���β� $productType Ϊ WLSXWG ʱ�����ڲ��ϳ������
     * ���β� $productType Ϊ WLSXZZ ʱ�����ڲ�Ʒ�������
     * @param $object
     * @param string $productType
     * @return bool
     */
    function materialsCal_d($object, $productType = 'WLSXWG')
    {
        if (!$object) {
            return false;
        }
        set_time_limit(0);
        $productIdStr = null;
        $productNoRate = null;
        if ($object['checkType'] == 1) {// ���㷶ΧΪȫ������

        } else if ($object['checkType'] == 2) {
            $productIdStr = " AND i.productId = '" . $object['productId'] . "'";
        } else {// ���㷶ΧΪָ�����ϱ���
            $productNoBegin = $object['productNoBegin'];
            $productNoEnd = $object['productNoEnd'];
            $productNoRate = " AND (db.productNo between '$productNoBegin' and '$productNoEnd')";
        }

        $thisYear = $object['thisYear'];
        $thisMonth = $object['thisMonth'];
        if ($thisMonth == 12) {
            $newYear = $thisYear + 1;
            $newMonth = 1;
        } else {
            $newYear = $thisYear;
            $newMonth = $thisMonth + 1;
        }
        $thisDate = $newYear . '-' . $newMonth . '-01';
        $periodNo = $newYear . '.' . $newMonth * 1;

        // ��ȡ���ڷ����˱䶯������id
        $changeProductIds = $this->getChangeProductId_d($thisYear, $thisMonth, $productIdStr);

        // �����ڹ��ۺ�������Ϲ̶�����۸�
        $this->updateUnhookCalInfo_d($thisYear, $thisMonth, $object['productId'], $productNoRate);

        try {
            $this->start_d();

            // ɾ��ԭ���
            $this->deleteByDate_d($thisYear, $thisMonth, $productType, $object);

            // ��ȡ����δ�䶯���
            $noChangeData = $this->getNoChangeBalance_d($newYear, $newMonth, $thisDate, $periodNo, $thisYear,
                $thisMonth, $productIdStr, $productNoRate, $productType, $changeProductIds);

            // �����ݴ���
            $keyLength = count($noChangeData) - 1;
            $insertDta = array();
            foreach ($noChangeData as $k => $v) {
                $insertDta[] = $v;

                if (($k != 0 && $k % 200 == 0) || $k == $keyLength) {
                    $this->createBatch($insertDta);
                    $insertDta = array();
                }
            }

            // ���ڱ䶯����idʱ��ȥ����䶯���������
            if ($changeProductIds) {
                // ��ȡ�¼�������
                $changeData = $this->getChangeBalance_d($newYear, $newMonth, $thisDate, $periodNo, $thisYear, $thisMonth,
                    $productIdStr, $productNoRate, $productType, $changeProductIds);

                // �����ݴ���
                $keyLength = count($changeData) - 1;
                $insertDta = array();
                foreach ($changeData as $k => $v) {
                    $insertDta[] = $v;

                    if (($k != 0 && $k % 200 == 0) || $k == $keyLength) {
                        $this->createBatch($insertDta);
                        $insertDta = array();
                    }
                }
            }

            // ���³��ⵥ�۸�
            $this->changeOutStock_d($thisYear, $thisMonth, $productType, $object);

            // ���µ������۸�
            $this->changeAllocation_d($thisYear, $thisMonth, $productType, $object);

            // ���ڱ䶯����idʱ��ȥ����䶯���������
            if ($changeProductIds) {
                // ���У׼
                $this->adjustBalance_d($newYear, $newMonth, $thisYear, $thisMonth, $productIdStr,
                    $productNoRate, $productType, $changeProductIds);

                // �������
                $this->updateStockBalance_d($newYear, $newMonth, $thisYear, $thisMonth, $productIdStr,
                    $productNoRate, $productType, $changeProductIds);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��ȡ����ȫ��ȫ��
     * @param $newYear
     * @param $newMonth
     * @param $thisYear
     * @param $thisMonth
     * @return string
     */
    function getZeroProductIds_d($newYear, $newMonth, $thisYear, $thisMonth) {
        $sql = "SELECT SUM(clearingNum) AS clearingNum, productId
            FROM oa_finance_stockbalance
            WHERE (thisYear = $newYear AND thisMonth = $newMonth) OR (thisYear = $thisYear AND thisMonth = $thisMonth)
            GROUP BY productId HAVING clearingNum = 0";
        $data = $this->_db->getArray($sql);

        if (!empty($data)) {
            $productIdArr = array();
            foreach ($data as $v) {
                $productIdArr[] = $v['productId'];
            }
            return implode(',', $productIdArr);
        } else {
            return "";
        }
    }

    /**
     * ���У׼
     * @param $newYear
     * @param $newMonth
     * @param $thisYear
     * @param $thisMonth
     * @param $productIdStr
     * @param $productNoRate
     * @param $productType
     * @param $changeProductIds
     * @throws Exception
     */
    function adjustBalance_d($newYear, $newMonth, $thisYear, $thisMonth, $productIdStr,
                             $productNoRate, $productType, $changeProductIds) {

        // ��ѯ������Ϊ0������
        $zeroProductIds = $this->getZeroProductIds_d($newYear, $newMonth, $thisYear, $thisMonth);
        if ($zeroProductIds == "") {
            return;
        }

        $changeProductSql = $changeProductIds ? " AND db.productId IN($changeProductIds)" : " AND 0";

        // ƥ������в�������
        $sql = "SELECT productId, SUM(inNum) AS inNum, SUM(inAmount) AS inAmount,
                SUM(outNum) AS outNum, SUM(outAmount) AS outAmount
            FROM
                (
                    SELECT
                        i.productId, i.productCode AS productNo, i.productName,
                        SUM(IF (c.isRed = 0, i.actNum, - i.actNum )) AS inNum,
                        SUM(IF(c.isRed = 0, i.subPrice, - i.subPrice)) AS inAmount,
                        0 AS outNum, 0 AS outAmount
                    FROM
                        oa_stock_instock c
                        LEFT JOIN oa_stock_instock_item i ON c.id = i.mainId
                    WHERE
                        c.docStatus = 'YSH' AND YEAR (c.auditDate) = $thisYear AND MONTH (c.auditDate) = $thisMonth
                        $productIdStr
                    GROUP BY
                        i.productId
		            UNION ALL
                    SELECT
                        i.productId, i.productCode AS productNo, i.productName,
                        0 AS inNum, 0 AS inAmount,
                        SUM(IF (o.isRed = 1, - i.actOutNum, i.actOutNum)) AS outNum,
                        SUM(IF (o.isRed = 1, - i.subCost, i.subCost)) AS outAmount
                    FROM
                        oa_stock_outstock_item i
                        RIGHT JOIN oa_stock_outstock o ON o.id = i.mainId
                    WHERE
                        o.docStatus = 'YSH' AND YEAR (o.auditDate) = $thisYear AND MONTH (o.auditDate) = $thisMonth
                        $productIdStr
                    GROUP BY
                        i.productId
			        UNION ALL
                    SELECT
                        i.productId, i.productNo, i.productName, 0 AS inNum, i.money AS inAmount,
                        0 AS outNum, 0 AS outAmount
                    FROM
                        oa_finance_costajust c
                        LEFT JOIN oa_finance_costajust_detail i ON c.id = i.costajustId
                    WHERE
                        c.formType = 'CBTZ-01' AND YEAR (c.formDate) = $thisYear AND MONTH (c.formDate) = $thisMonth
                        $productIdStr
				    UNION ALL
					SELECT
						i.productId, i.productNo, i.productName,
						i.number AS inNum, i.allDiffer AS inAmount, 0 AS outNum, 0 AS outAmount
					FROM
						oa_finance_adjustment c
					    LEFT JOIN oa_finance_adjustment_detail i ON c.id = i.adjustId
					WHERE
						YEAR (c.formDate) = $thisYear AND MONTH (c.formDate) = $thisMonth $productIdStr
					UNION ALL
                    SELECT
                        i.productId, i.productCode AS productNo, i.productName, 0 AS inNum, 0 AS inAmount,
                        SUM(IF (c.isRed = 1,- i.outstockNum,i.outstockNum)) AS outNum,
                        SUM(IF (c.isRed = 1,- i.outstockNum * i.price,i.outstockNum * i.price)) AS outAmount
                    FROM
                        oa_stock_outstock c
                        INNER JOIN oa_stock_stockout_extraitem i ON c.id = i.mainId
                    WHERE
                        c.docStatus = 'YSH' AND YEAR (c.auditDate) = $thisYear AND MONTH (c.auditDate) = $thisMonth
                        $productIdStr
                    GROUP BY
                        i.productId
                ) db
                LEFT JOIN oa_stock_product_info i ON db.productId = i.id
                WHERE db.productId IN($zeroProductIds) AND i.properties = '$productType' $productNoRate $changeProductSql
            GROUP BY db.productId HAVING inNum = outNum AND inAmount <> outAmount";
        $data = $this->_db->getArray($sql);

        // ������ִ������ݣ���ʼ���д���
        if (!empty($data)) {
            foreach ($data as $v) {
                $sql = "SELECT i.id,i.actOutNum,i.cost,i.subCost
                    FROM oa_stock_outstock c LEFT JOIN oa_stock_outstock_item i ON c.id = i.mainId
                    WHERE
                        c.docStatus = 'YSH' AND i.productId = {$v['productId']}
                        AND YEAR (c.auditDate) = $thisYear AND MONTH (c.auditDate) = $thisMonth
                    ORDER BY i.id DESC LIMIT 1";
                $outstock = $this->_db->get_one($sql);

                if ($outstock) {
                    $actSubCost = bcadd($outstock['subCost'], bcsub($v['inAmount'], $v['outAmount'], 2), 2);
                    $actCost = bcdiv($actSubCost, $outstock['actOutNum'], 6);
                    $sql = "UPDATE oa_stock_outstock_item SET cost={$actCost},subCost={$actSubCost} WHERE id={$outstock['id']};";
                    $this->_db->query($sql);
                }
            }
        }
    }

    /**
     * �̶������ݹ�������۸�ͽ��
     * @param $thisYear
     * @param $thisMonth
     * @param $productIdStr
     * @param $productNoRate
     */
    function updateUnhookCalInfo_d($thisYear, $thisMonth, $productIdStr, $productNoRate)
    {
        $changeProductSql = $productIdStr ? " AND i.productId NOT IN($productIdStr)" : "";
        $productNoRate = $productNoRate ? str_replace("db.productNo", "i.productCode", $productNoRate) : "";

        $sql = "UPDATE oa_stock_instock c LEFT JOIN oa_stock_instock_item i ON c.id = i.mainId
			SET i.unhookCalPrice = i.price, i.unhookCalAmount = i.subPrice, i.isUnhookCal = 1
			WHERE
				docStatus = 'YSH' AND docType = 'RKPURCHASE' AND catchStatus = 'CGFPZT-WGJ'
				AND YEAR(auditDate) = $thisYear AND MONTH(auditDate) = $thisMonth " .
            $changeProductSql . $productNoRate;
        $this->_db->query($sql);
    }

    /**
     * ������
     * @param $newYear
     * @param $newMonth
     * @param $thisYear
     * @param $thisMonth
     * @param $productIdStr
     * @param $productNoRate
     * @param $productType
     * @param $changeProductIds
     */
    function updateStockBalance_d($newYear, $newMonth, $thisYear, $thisMonth, $productIdStr,
                                  $productNoRate, $productType, $changeProductIds)
    {
        // ��ȡϵͳ�趨�Ĳֿ�
        $stockSystemDao = new model_stock_stockinfo_systeminfo();
        $stockSysObj = $stockSystemDao->get_d(1);

        $changeProductSql = $changeProductIds ? " AND db.productId IN($changeProductIds)" : " AND 0";

        // ���±��������
        $sql = "UPDATE oa_finance_stockbalance db LEFT JOIN (
                SELECT
                    db.stockId, db.productId, if(ba.stockType='CKLX-SC',SUM(db.outAmount),0) AS actOutAmount
                FROM
                (
                    SELECT
                        i.stockId, i.productId, if(o.isRed = 1, -i.subCost, i.subCost) AS outAmount
                    FROM
                        oa_stock_outstock_item i RIGHT JOIN oa_stock_outstock o ON o.id = i.mainId
                    WHERE o.docStatus = 'YSH' AND YEAR(o.auditDate) = $thisYear
                        AND MONTH(o.auditDate) = $thisMonth $productIdStr
                    UNION ALL
                    SELECT
                        i.exportStockId AS stockId, i.productId, i.subCost AS outAmount
                    FROM
                        oa_stock_allocation c LEFT JOIN oa_stock_allocation_item i ON c.id = i.mainId
                    WHERE c.docStatus = 'YSH' AND YEAR(c.auditDate) = $thisYear
                        AND MONTH(c.auditDate) = $thisMonth $productIdStr
                    UNION ALL
                    SELECT
                        " . $stockSysObj['packingStockId'] . " AS stockId, i.productId,
                        ROUND(IF(c.isRed = 1 , -i.outstockNum*i.price, i.outstockNum*i.price),2) AS outAmount
                    FROM oa_stock_outstock c INNER JOIN oa_stock_stockout_extraitem i ON c.id = i.mainId
                    WHERE c.docStatus = 'YSH' AND YEAR(c.auditDate) = $thisYear
                        AND MONTH(c.auditDate) = $thisMonth $productIdStr
                    UNION ALL
                    SELECT
                        c.stockId, i.productId, i.money AS outAmount
                    FROM
                        oa_finance_costajust c LEFT JOIN oa_finance_costajust_detail i ON c.id = i.costajustId
                    WHERE c.formType = 'CBTZ-02' AND YEAR(c.formDate) = $thisYear
                        AND MONTH(c.formDate) = $thisMonth $productIdStr
                ) db LEFT JOIN oa_stock_product_info i ON db.productId = i.id
                LEFT JOIN oa_stock_baseinfo ba ON db.stockId = ba.id
                WHERE i.properties = '$productType' GROUP BY db.stockId,db.productId
            ) s ON db.productId = s.productId AND db.stockId = s.stockId
            SET db.actOutAmount = IF(s.actOutAmount IS NULL, 0, s.actOutAmount)
            WHERE db.thisYear = $newYear AND db.thisMonth = $newMonth $productNoRate $changeProductSql";
        $this->_db->query($sql);

        // �����ڳ����
        $sql = "UPDATE oa_finance_stockbalance db
            LEFT JOIN
                (SELECT * FROM oa_finance_stockbalance WHERE thisYear = $thisYear AND thisMonth = $thisMonth) db1
                ON db.productId = db1.productId AND db.stockId = db1.stockId
            LEFT JOIN
                oa_stock_product_info i ON db.productId = i.id
            SET db.balanceAmount = IF(db1.balanceAmount IS NULL, 0, db1.balanceAmount) + db.actInAmount - db.actOutAmount,
            db.clearingNum = IF(db1.clearingNum IS NULL, 0, db1.clearingNum) + db.actInNum - db.actOutNum
            WHERE i.properties = '$productType' AND db.thisYear = $newYear AND db.thisMonth = $newMonth $productNoRate $changeProductSql";

        $this->_db->query($sql);
    }

    /**
     * ��ȡ���ڱ䶯�������
     * @param $newYear
     * @param $newMonth
     * @param $thisDate
     * @param $periodNo
     * @param $thisYear
     * @param $thisMonth
     * @param $productIdStr
     * @param $productNoRate
     * @param $productType
     * @param $changeProductIds
     * @return mixed
     */
    function getChangeBalance_d($newYear, $newMonth, $thisDate, $periodNo, $thisYear, $thisMonth,
                                $productIdStr, $productNoRate, $productType, $changeProductIds)
    {

        // ��ȡϵͳ�趨�Ĳֿ�
        $stockSystemDao = new model_stock_stockinfo_systeminfo();
        $stockSysObj = $stockSystemDao->get_d(1);

        $changeProductSql = $changeProductIds ? " AND db.productId IN($changeProductIds)" : " AND 0";

        $sql = "
            SELECT
                $newYear AS thisYear, $newMonth AS thisMonth, '$thisDate' AS thisDate, '$periodNo' AS periodNo,
                db.stockId, db.stockName, db.productId, db.productNo, i.ext2 AS k3Code, db.productName,
                i.pattern AS productModel,i.unitName AS units, SUM(db.inNumber) AS actInNum,
                if(
                    ba.stockType='CKLX-SC',
                    SUM(IF(db.inAmount != 0, ROUND(db.inAmount,2),
                    ROUND((db.inNumber * p.price),2))), 0
                ) AS actInAmount,
                SUM(db.outNumber) AS actOutNum , 0 AS clearingNum,
                if(ba.stockType='CKLX-SC',p.price,0) AS  price,
                0 AS actOutAmount,
                0 AS balanceAmount
            FROM
            (
                SELECT
                    i.inStockId AS stockId, i.inStockName AS stockName, i.productId,
                    i.productCode AS productNo ,i.productName ,
                    SUM(if(c.isRed = 0,i.actNum, -i.actNum)) AS inNumber,
                    SUM(if(c.isRed = 0 ,i.subPrice, -i.subPrice)) AS inAmount,
                    0 AS outNumber
                FROM
                    oa_stock_instock c LEFT JOIN oa_stock_instock_item i ON c.id = i.mainId
                WHERE c.docStatus = 'YSH' AND YEAR(c.auditDate) = " . $thisYear . "
                    AND MONTH(c.auditDate) = " . $thisMonth . " $productIdStr
                GROUP BY i.inStockId,i.productId
                UNION ALL
                SELECT
                    i.stockId,i.stockName,i.productId,i.productCode AS productNo,i.productName,
                    SUM(if(o.isRed = 1 , 0, 0)) AS inNumber,
                    SUM(if(o.isRed = 1 , 0, 0)) AS inAmount,
                    SUM(if(o.isRed = 1 , -i.actOutNum, i.actOutNum )) AS outNumber
                FROM
                    oa_stock_outstock_item i RIGHT JOIN oa_stock_outstock o ON o.id = i.mainId
                WHERE o.docStatus = 'YSH' AND YEAR(o.auditDate) = " . $thisYear . "
                    AND MONTH(o.auditDate) = " . $thisMonth . " $productIdStr
                GROUP BY i.stockId,i.productId
                UNION ALL
                SELECT
                    i.stockId,i.stockName,i.productId,i.productNo,i.productName,
                    0 AS inNumber,0 AS inAmount , 0 AS outNumber
                FROM
                    oa_finance_stockbalance i
                WHERE i.thisYear = " . $thisYear . " AND i.thisMonth = " . $thisMonth . " $productIdStr
                UNION ALL
                SELECT
                    c.stockId,c.stockName,i.productId,i.productNo,i.productName,
                    0 AS inNumber ,i.money AS inAmount, 0 AS outNumber
                FROM
                    oa_finance_costajust c LEFT JOIN oa_finance_costajust_detail i ON c.id = i.costajustId
                WHERE c.formType = 'CBTZ-01' AND YEAR(c.formDate) = " . $thisYear . "
                    AND MONTH(c.formDate) = " . $thisMonth . " $productIdStr
                UNION ALL
                SELECT
                    i.stockId,i.stockName,i.productId,i.productNo,i.productName,
                    0 AS inNumber ,i.allDiffer AS inAmount, 0 AS outNumber
                FROM
                    oa_finance_adjustment c LEFT JOIN oa_finance_adjustment_detail i ON c.id = i.adjustId
                WHERE YEAR(c.formDate) = " . $thisYear . " AND MONTH(c.formDate) = " . $thisMonth . " $productIdStr
                UNION ALL
                SELECT
                    i.exportStockId AS stockId,i.exportStockName AS stockName,
                    i.productId,i.productCode AS productNo,i.productName,
                    0 AS inNumber ,0 AS inAmount, i.allocatNum AS outNumber
                FROM
                    oa_stock_allocation c LEFT JOIN oa_stock_allocation_item i ON c.id = i.mainId
                WHERE c.docStatus = 'YSH' AND YEAR(c.auditDate) = " . $thisYear . "
                    AND MONTH(c.auditDate) = " . $thisMonth . " $productIdStr
                UNION ALL
                SELECT
                    i.importStockId AS stockId,i.importStockName AS stockName,
                    i.productId,i.productCode AS productNo,i.productName,
                    i.allocatNum AS inNumber ,0 AS inAmount, 0 AS outNumber
                FROM
                    oa_stock_allocation c LEFT JOIN oa_stock_allocation_item i ON c.id = i.mainId
                WHERE c.docStatus = 'YSH' AND YEAR(c.auditDate) = " . $thisYear . "
                    AND MONTH(c.auditDate) = " . $thisMonth . " $productIdStr
                UNION ALL
                SELECT
                    " . $stockSysObj['packingStockId'] . " AS stockId,'" . $stockSysObj['salesStockName'] . "' AS stockName,
                    i.productId,i.productCode AS productNo,i.productName,
                    0 AS inNumber,
                    0 AS inAmount,
                    SUM(if(c.isRed = 1 , -i.outstockNum, i.outstockNum)) AS outNumber
                FROM oa_stock_outstock c INNER JOIN oa_stock_stockout_extraitem i ON c.id = i.mainId
                WHERE c.docStatus = 'YSH' AND YEAR(c.auditDate) = " . $thisYear . "
                    AND MONTH(c.auditDate) = " . $thisMonth . " $productIdStr
                GROUP BY i.productId
            ) db LEFT join oa_stock_product_info i ON db.productId = i.id LEFT join
                (
                    SELECT a.productId,SUM(a.inAmount)/SUM(a.inNumber) AS price FROM
                    (
                        SELECT
                            t.productId,
                            SUM(IF(i.isRed = 0, t.actNum, -t.actNum)) AS inNumber,
                            SUM(if(i.isRed = 0 ,t.subPrice, -t.subPrice)) AS inAmount,
                            t.inStockId  AS stockId
                        FROM
                            oa_stock_instock i LEFT JOIN oa_stock_instock_item t ON i.id = t.mainId
                        WHERE i.docStatus = 'YSH' AND YEAR(i.auditDate) = " . $thisYear . "
                            AND MONTH(i.auditDate) = " . $thisMonth . "
                        GROUP BY t.inStockId,t.productId
                        UNION ALL
                        SELECT
                            t.productId,
                            SUM(t.actOutNum) AS inNumber,
                            SUM(ABS(t.subCost)) AS inAmount,
                            t.stockId  AS stockId
                        FROM
                            oa_stock_outstock i LEFT JOIN oa_stock_outstock_item t ON i.id = t.mainId
                        WHERE i.docStatus = 'YSH' AND i.isRed = 1 AND YEAR(i.auditDate) = " . $thisYear . "
                            AND MONTH(i.auditDate) = " . $thisMonth . "
                        GROUP BY t.stockId,t.productId
                        UNION ALL
                        SELECT
                            s.productId,s.clearingNum AS inNumber,s.balanceAmount AS inAmount,s.stockId
                        FROM
                            oa_finance_stockbalance s
                        WHERE s.thisYear = " . $thisYear . " AND thisMonth = " . $thisMonth . "
                        UNION ALL
                        SELECT
                            cd.productId, 0 AS inNumber, cd.money AS inAmount, c.stockId
                        FROM
                            oa_finance_costajust c LEFT JOIN oa_finance_costajust_detail cd ON c.id = cd.costajustId
                        WHERE YEAR(c.formDate) = " . $thisYear . " AND MONTH(c.formDate) = " . $thisMonth . "
                            AND c.formType = 'CBTZ-01'
                        UNION ALL
                        SELECT
                            cd.productId,0 AS inNumber ,cd.allDiffer AS inAmount,cd.stockId
                        FROM
                            oa_finance_adjustment c LEFT JOIN oa_finance_adjustment_detail cd ON c.id = cd.adjustId
                        WHERE YEAR(c.formDate) = " . $thisYear . " AND MONTH(c.formDate) = " . $thisMonth . "
                    ) a
                    INNER JOIN oa_stock_baseinfo ba ON a.stockId = ba.id AND ba.stockType = 'CKLX-SC'
                    GROUP BY a.productId
                ) p ON db.productId = p.productId
            LEFT JOIN oa_stock_baseinfo ba ON db.stockId = ba.id
            WHERE i.properties = '" . $productType . "' $productNoRate $changeProductSql
            GROUP BY db.stockId,db.productId";

        return $this->_db->getArray($sql);
    }

    /**
     * ��ȡ����û�б䶯�Ĵ������
     * @param $newYear
     * @param $newMonth
     * @param $thisDate
     * @param $periodNo
     * @param $thisYear
     * @param $thisMonth
     * @param $productIdStr
     * @param $productNoRate
     * @param $productType
     * @param $changeProductIds
     * @return mixed
     */
    function getNoChangeBalance_d($newYear, $newMonth, $thisDate, $periodNo, $thisYear, $thisMonth,
                                  $productIdStr, $productNoRate, $productType, $changeProductIds)
    {

        $changeProductSql = $changeProductIds ? " AND i.productId NOT IN($changeProductIds)" : "";
        $productNoRate = $productNoRate ? str_replace("db.productNo", 'i.productNo', $productNoRate) : "";

        $sql = "SELECT
                $newYear AS thisYear, $newMonth AS thisMonth, '$thisDate' AS thisDate, '$periodNo' AS periodNo,
				i.stockId, i.stockName, i.productId, i.productNo, o.ext2 AS k3Code, i.productName,
				o.pattern AS productModel,o.unitName AS units, i.actInNum,
				i.actInAmount,
				i.actOutNum, i.clearingNum,
				i.price,
				i.actOutAmount,
				i.balanceAmount
			FROM
				oa_finance_stockbalance i
			  	LEFT join oa_stock_product_info o ON i.productId = o.id
			WHERE o.properties = '" . $productType . "' AND i.thisYear = $thisYear AND i.thisMonth = $thisMonth"
            . $productIdStr . $productNoRate . $changeProductSql;

        return $this->_db->getArray($sql);
    }

    /**
     * ���³��ⵥ���
     * @param $thisYear
     * @param $thisMonth
     * @param string $productType
     * @param null $object
     * @return bool
     * @throws Exception
     */
    function changeOutStock_d($thisYear, $thisMonth, $productType = 'WLSXZZ', $object = null)
    {
        $productIdStr = '';
        if ($thisMonth == 12) {
            $newYear = $thisYear + 1;
            $newMonth = 1;
        } else {
            $newYear = $thisYear;
            $newMonth = $thisMonth + 1;
        }
        if (!empty($object['productId'])) {
            $productIdStr = " t.productId = " . $object['productId'] . " AND ";
        }

        try {
            //�������ֳ��ⲿ��
            $sql = "UPDATE
                    oa_stock_outstock_item t RIGHT JOIN oa_stock_outstock i ON i.id = t.mainId INNER JOIN (
                        SELECT a.productId,a.price,a.stockId,b.properties
                        FROM
                            oa_finance_stockbalance a
                            INNER JOIN oa_stock_product_info b ON a.productId = b.id
                        WHERE a.thisYear = " . $newYear . " AND a.thisMonth = " . $newMonth .
                " AND b.properties = '" . $productType . "'
                    ) db ON t.productId = db.productId AND t.stockId = db.stockId
                    SET t.cost = db.price , t.subCost = ROUND(db.price * t.actOutNum, 2)
                WHERE $productIdStr
                    i.isRed = 0 AND i.docStatus = 'YSH'
                AND year(i.auditDate)= " . $thisYear . " AND month(i.auditDate) = " . $thisMonth;
            $this->_db->query($sql);

            //���¹�����ɫ���ⵥ
            $sql = "SELECT
						t.id
					FROM
                    oa_stock_outstock_item t RIGHT JOIN oa_stock_outstock i ON i.id = t.mainId INNER JOIN (
                        SELECT a.productId,a.price,a.stockId,b.properties
                        FROM
                            oa_finance_stockbalance a
                        INNER JOIN oa_stock_product_info b ON a.productId = b.id
                        WHERE a.thisYear = " . $newYear . " AND a.thisMonth = " . $newMonth .
                " AND b.properties = '" . $productType . "'
			        	) db on t.productId = db.productId AND t.stockId = db.stockId
			        WHERE $productIdStr
			        	i.isRed = 0 AND i.docStatus = 'YSH'
			        AND year(i.auditDate)= " . $thisYear . " AND month(i.auditDate) = " . $thisMonth;
            $rs = $this->_db->getArray($sql);
            if (!empty($rs)) {
                $idstr = '';
                foreach ($rs as $k => $v) {
                    if ($k == 0) {
                        $idstr = $v['id'];
                    } else {
                        $idstr .= ',' . $v['id'];
                    }
                }
                $sql = "UPDATE oa_stock_outstock_item t
                    RIGHT JOIN oa_stock_outstock i ON i.id = t.mainId
                    INNER JOIN (
                        SELECT
                            t.id,
                            t.cost
                        FROM
                            oa_stock_outstock_item t
                        RIGHT JOIN oa_stock_outstock i ON i.id = t.mainId
                        WHERE
                            t.id IN (" . $idstr . ")
                    ) c ON t.orgId = c.id
                    SET t.cost = c.cost,
                     t.subCost = ROUND(c.cost * t.actOutNum, 2)
                    WHERE t.orgId IN (" . $idstr . ") AND i.isRed = 1";
                $this->_db->query($sql);
            }

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ���µ������۸�
     * @param $thisYear
     * @param $thisMonth
     * @param string $productType
     * @param null $object
     * @return mixed
     */
    function changeAllocation_d($thisYear, $thisMonth, $productType = 'WLSXZZ', $object = null)
    {
        if ($thisMonth == 12) {
            $newYear = $thisYear + 1;
            $newMonth = 1;
        } else {
            $newYear = $thisYear;
            $newMonth = $thisMonth + 1;
        }

        // ����id��ѯ��
        $productIdStr = !empty($object['productId']) ? " t.productId = " . $object['productId'] . " AND " : "";

        $sql = "UPDATE
                oa_stock_allocation_item t right join oa_stock_allocation i on i.id = t.mainId INNER join (
                    select a.productId,a.price,a.stockId,b.properties
                    from
                        oa_finance_stockbalance a
                        INNER join oa_stock_product_info b on a.productId = b.id
                    where a.thisYear = " . $newYear . " and a.thisMonth = " . $newMonth .
            " and b.properties = '" . $productType . "'
                ) db on t.productId = db.productId and t.exportStockId = db.stockId
                set t.cost = db.price , t.subCost = ROUND(db.price * t.allocatNum, 2)
            where $productIdStr
                i.docStatus = 'YSH' AND year(i.auditDate)= " . $thisYear . " and month(i.auditDate) = " . $thisMonth;
        return $this->_db->query($sql);
    }

    /**
     * ���¹�����ͬ/���Ͳ�Ʒ����ͬ��Ŀ����ʵ�ʳɱ�
     * ���β� $productType Ϊ WLSXWG ʱ�����ڲ��ϳ������
     * ���β� $productType Ϊ WLSXZZ ʱ�����ڲ�Ʒ�������
     * @param $object
     * @param string $productType
     * @return bool
     * @throws Exception
     */
    function materialsCostAct_d($object, $productType = 'WLSXWG')
    {
        if (!$object) {
            return false;
        }
        set_time_limit(0);
        $productIdStr = null;
        $productNoRate = null;
        if ($object['checkType'] == 1) {// ���㷶ΧΪȫ������

        } else if ($object['checkType'] == 2) {
            $productIdStr = " AND c.productId = '" . $object['productId'] . "'";
        } else {// ���㷶ΧΪָ�����ϱ���
            $productNoBegin = $object['productNoBegin'];
            $productNoEnd = $object['productNoEnd'];
            $productNoRate = " AND (c.productCode between '$productNoBegin' and '$productNoEnd')";
        }

        try {
            //���¹�����ͬ/���Ͳ�Ʒ����ͬ��Ŀ����ʵ�ʳɱ������ⵥ���ϳɱ�֮�ͣ�
            $sql = "SELECT
					i.contractType,
					p.docId,
					p.contEquId,
					SUM(IF(i.isRed = 0,c.subCost,-c.subCost)) AS subCost
				FROM
					oa_stock_outstock_item c
				RIGHT JOIN oa_stock_outstock i ON i.id = c.mainId
				LEFT JOIN oa_stock_outplan_product p ON p.id = c.relDocId
				INNER join oa_stock_product_info b ON c.productId = b.id AND b.properties = '" . $productType . "'
				WHERE
					i.docStatus = 'YSH'
				AND i.contractType IN (
					'oa_contract_contract',
					'oa_present_present'
				)" . $productIdStr . $productNoRate .
                " GROUP BY
					i.contractType,
					p.contEquId";
            $rs = $this->_db->getArray($sql);
            if ($rs) {
                $conEquDao = new model_contract_contract_equ();// ��ͬ����
                $presentEquDao = new model_projectmanagent_present_presentequ();// ��������
                $contractIdArr = array();// �����ͬid
                foreach ($rs as $v) {
                    if (!empty($v['contEquId'])) {
                        if ($v['contractType'] == 'oa_contract_contract') {
                            $conEquDao->update(array('id' => $v['contEquId']), array('costAct' => $v['subCost']));
                            if (!empty($v['docId']) && !in_array($v['docId'], $contractIdArr)) {
                                array_push($contractIdArr, $v['docId']);
                            }
                        } elseif ($v['contractType'] == 'oa_present_present') {
                            $presentEquDao->update(array('id' => $v['contEquId']), array('costAct' => $v['subCost']));
                        }
                    }
                }
                // ���º�ͬ��Ŀ���ɱ�
                if (!empty($contractIdArr)) {
                    foreach ($contractIdArr as $id) {
                        $sql = "UPDATE oa_contract_project t
							LEFT JOIN (
								SELECT
									p.contractId AS contractId,
									p.newProLineCode,
									SUM(e.costAct) AS costAct
								FROM
									oa_contract_product p
								LEFT JOIN oa_contract_equ e ON p.id = e.conProductId
								WHERE
									p.contractId = " . $id . "
								AND p.proTypeId = '11'
								AND e.isDel = '0'
								GROUP BY
									p.newProLineCode
							) c ON t.contractId = c.contractId
							AND t.proLineCode = c.newProLineCode
							SET t.costAct = c.costAct
							WHERE
								t.contractId = " . $id;
                        $this->_db->query($sql);
                    }
                }
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $object
     * @param string $productType
     * @return bool
     */
    function updateProductAssetPrice_d($object, $productType = 'WLSXWG')
    {
        // ����
        $thisYear = $object['thisYear'];
        $thisMonth = $object['thisMonth'];

        // ��ȡ���ڵ������ϸID������ID
        $sql = "SELECT i.id,productId
            FROM oa_stock_outstock c
                    INNER JOIN oa_stock_outstock_item i ON c.id = i.mainId
                    LEFT JOIN oa_stock_product_info inf ON i.productId = inf.id
            WHERE c.relDocType = 'QTCKZCCK' AND YEAR(c.auditDate) = " . $thisYear . "
		AND MONTH(c.auditDate) = " . $thisMonth . " AND LENGTH(c.relDocId) > 20 AND inf.properties = '$productType'";
        $boData = $this->_db->getArray($sql);

        // ѭ���������Ϻ���ϸ
        if ($boData) {
            $itemIdArr = array(); // ��ⵥ��ϸID����
            $productIdArr = array(); // ������ϸID����
            foreach ($boData as $v) {
                $itemIdArr[] = $v['id'];
                $productIdArr[] = $v['productId'];
            }

            // ��ȡ�����ڳ��ĵ���
            if ($thisMonth == 12) {
                $nextYear = $thisYear + 1;
                $nextMonth = 1;
            } else {
                $nextYear = $thisYear;
                $nextMonth = $thisMonth + 1;
            }

            // �۸��ѯ�ű�
            $sql = "SELECT
                productId, price
            FROM
                oa_finance_stockbalance
            WHERE thisYear = $nextYear AND thisMonth = $nextMonth AND productId IN(" . implode(',', $productIdArr) . ")
            GROUP BY
                thisYear, thisMonth, productId";
            $priceDataOrg = $this->_db->getArray($sql);

            // ȷ�����˺���Ž��и���
            if ($priceDataOrg) {
                $priceData = array();

                foreach ($priceDataOrg as $v) {
                    $priceData[$v['productId']] = $v['price'];
                }

                // ����ƽ̨�ĸ���
                $result = util_curlUtil::getDataFromAWS('asset', 'updateProductAssetPrice', array(
                    "itemIds" => implode(',', $itemIdArr), "priceData" => $priceData
                ));
            }
        }

        return true;
    }

    /**
     * �������ϳɱ�
     * ���β� $productType Ϊ WLSXZZ ʱ�����ڲ�Ʒ�������
     * @param $object
     * @param string $productType
     * @return bool
     * @throws Exception
     */
    function productInfoCost_d($object, $productType = 'WLSXZZ')
    {
        if (!$object) {
            return false;
        }
        set_time_limit(0);
        $productIdStr = null;
        $productNoRate = null;
        if ($object['checkType'] == 1) {// ���㷶ΧΪȫ������

        } else if ($object['checkType'] == 2) {
            $productIdStr = " AND c.id = '" . $object['productId'] . "'";
        } else {// ���㷶ΧΪָ�����ϱ���
            $productNoBegin = $object['productNoBegin'];
            $productNoEnd = $object['productNoEnd'];
            $productNoRate = " AND (c.productCode between '$productNoBegin' and '$productNoEnd')";
        }

        try {
            //��ȡ�¸���������
            $thisYear = $object['thisYear'];
            $thisMonth = $object['thisMonth'];
            if ($thisMonth == 12) {
                $thisYear = $thisYear + 1;
                $thisMonth = 1;
            } else {
                $thisMonth = $thisMonth + 1;
            }
            //�����ϳɱ�����Ϊ�¸��������ڵ��ڳ�
            $sql = "UPDATE oa_stock_product_info c
				LEFT JOIN (
					SELECT
						thisYear,
						thisMonth,
						productId,
						price
					FROM
						oa_finance_stockbalance
					GROUP BY
						thisYear,
						thisMonth,
						productId
				) s ON c.id = s.productId
				SET c.priCost = s.price
				WHERE
					s.thisYear = '" . $thisYear . "'
				AND s.thisMonth = '" . $thisMonth . "'
				AND c.properties = '" . $productType . "'" . $productIdStr . $productNoRate;
            $this->_db->query($sql);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ���ֳ���������ȡ
     * @param $productCode
     * @param $thisVal
     * @return int
     */
    function getPrice_d($productCode, $thisVal)
    {
        switch ($thisVal) {
            case 'stockBalancePrice' :
                $period = $this->rtThisPeriod_d();
                $thisDate = $period['thisDate'];

                $sql = "select price from oa_finance_stockbalance where productNo  ='$productCode' and thisDate = '$thisDate' ORDER BY thisDate desc limit 0,1";
                break;
            case 'stockInPrice' :
                $sql = "select price from oa_stock_instock_item where productCode  ='$productCode' ORDER BY id desc limit 0,1";
                break;
            case 'stockOutPrice' :
                $sql = "select cost as price from oa_stock_outstock_item where productCode  ='$productCode' ORDER BY id desc limit 0,1";
                break;
            case 'purchasePrice' :
                $sql = "select price from oa_purch_apply_equ where productNumb  ='$productCode' ORDER BY id desc limit 0,1";
                break;
            default :
                return 0;
        }
        $rs = $this->_db->getArray($sql);
        if ($rs) {
            return $rs[0]['price'];
        }
        return 0;
    }

    /**
     * ��ȡ���ڱ䶯������ID
     * @param $thisYear
     * @param $thisMonth
     * @param $productIdStr
     * @return mixed
     */
    function getChangeProductId_d($thisYear, $thisMonth, $productIdStr)
    {
        $sql = "SELECT
				i.productId
			FROM
				oa_stock_instock c LEFT JOIN oa_stock_instock_item i ON c.id = i.mainId
			WHERE c.docStatus = 'YSH' AND YEAR(c.auditDate) = " . $thisYear . "
				AND MONTH(c.auditDate) = " . $thisMonth . " $productIdStr
			GROUP BY i.productId
			UNION ALL
			SELECT
				i.productId
			FROM
				oa_stock_outstock_item i RIGHT JOIN oa_stock_outstock o ON o.id = i.mainId
			WHERE o.docStatus = 'YSH' AND YEAR(o.auditDate) = " . $thisYear . "
				AND MONTH(o.auditDate) = " . $thisMonth . " $productIdStr
			GROUP BY i.productId
			UNION ALL
			SELECT
				i.productId
			FROM
				oa_stock_allocation c LEFT JOIN oa_stock_allocation_item i ON c.id = i.mainId
			WHERE c.docStatus = 'YSH' AND YEAR(c.auditDate) = " . $thisYear . "
				AND MONTH(c.auditDate) = " . $thisMonth . " $productIdStr
			UNION ALL
			SELECT
				i.productId
			FROM
				oa_finance_costajust c LEFT JOIN oa_finance_costajust_detail i ON c.id = i.costajustId
			WHERE YEAR(c.formDate) = " . $thisYear . " AND MONTH(c.formDate) = " . $thisMonth . " $productIdStr
			UNION ALL
			SELECT
				i.productId
			FROM
				oa_finance_adjustment c LEFT JOIN oa_finance_adjustment_detail i ON c.id = i.adjustId
			WHERE YEAR(c.formDate) = " . $thisYear . " AND MONTH(c.formDate) = " . $thisMonth . $productIdStr;
        $changeProductIds = $this->_db->getArray($sql);

        $result = array();

        if ($changeProductIds) {
            foreach ($changeProductIds as $v) {
                if ($v['productId'] && !in_array($v['productId'], $result)) {
                    $result[] = $v['productId'];
                }
            }
        }

        return implode(',', $result);
    }

    /*************************************�����ϸ�б�***********************************/

    /**
     * �����ϸ�б�
     * @param $object
     * @return array|bool
     */
    function listDetail_d($object)
    {
        if (!$object) {
            return false;
        }

        $thisYear = $object['thisYear'];
        $thisMonth = $object['thisMonth'];
        if ($thisMonth == 1) {
            $newYear = $thisYear - 1;
            $newMonth = 12;
        } else {
            $newYear = $thisYear;
            $newMonth = $thisMonth - 1;
        }
        $stockId = $object['stockId'];
        $productId = $object['productId'];

        // ��ȡϵͳ�趨�Ĳֿ�
        $stockSystemDao = new model_stock_stockinfo_systeminfo();
        $stockSysObj = $stockSystemDao->get_d(1);
        $packingStockId = $stockSysObj['packingStockId'];

        $sql = "select
                id,formNo,formType,formDate,sortNo,isRed,stockId,productId,inNumber,inAmount,inPrice,outNumber,outAmount,outPrice
            from
            (
                select
                    s.id,null as formNo,'balance' as formType,s.thisDate as formDate,1 as sortNo,0 as isRed,s.stockId,
                    s.productId,s.clearingNum as inNumber,s.balanceAmount as inAmount ,s.price as inPrice,
                    0 as outNumber , 0 as outAmount, 0 as outPrice, '' as createTime
                from
                    oa_finance_stockbalance s
                    where s.productId = $productId AND s.stockId = $stockId
                        AND s.thisYear = $newYear AND thisMonth = $newMonth
                union all
                select
                    c.id,c.formNo,'costAdjust' as formType,c.formDate ,2 as sortNo,0 as isRed,c.stockId,cd.productId,
                    0 as inNumber ,IF(formType = 'CBTZ-01', cd.money, 0) as inAmount,
                    0 as inPrice, 0 as outNumber ,
                    IF(formType = 'CBTZ-01', 0, cd.money) as outAmount, 0 as outPrice,c.createTime
                from
                    oa_finance_costajust c left join oa_finance_costajust_detail cd on c.id = cd.costajustId
                    where cd.productId = $productId AND c.stockId = $stockId
                        AND year(c.formDate) = $newYear AND month(c.formDate) = $newMonth
                union all
                select
                    c.id,c.adjustCode as formNo,'adjustment' as formType,c.formDate,3 as sortNo,0 as isRed,cd.stockId,
                    cd.productId,0 as inNumber ,cd.allDiffer as inAmount,0 as inPrice, 0 as outNumber , 0 as outAmount,
                    0 as outPrice, c.createTime
                from
                    oa_finance_adjustment c left join oa_finance_adjustment_detail cd on c.id = cd.adjustId
                    where  cd.productId = $productId AND cd.stockId = $stockId
                        AND year(c.formDate) = $newYear AND month(c.formDate) = $newMonth
                union all
                select
                    t.id,i.docCode as formNo,i.docType as formType,i.auditDate as formDate,4 as sortNo,
                    i.isRed, t.inStockId as stockId ,t.productId,
                    if(i.isRed = 0, t.actNum, -t.actNum) as inNumber,
                    if(i.isRed = 0, t.subPrice, -t.subPrice) as inAmount,
                    if(i.isRed = 0, t.price, -t.price) as inPrice,
                    0 as outNumber, 0 as outAmount,0 as outPrice, i.createTime
                from
                    oa_stock_instock i left join oa_stock_instock_item t on i.id = t.mainId
                    where t.productId = $productId AND t.inStockId = $stockId
                        AND i.docStatus = 'YSH' AND year(i.auditDate) = $newYear AND month(i.auditDate) = $newMonth
                union all
                select
                    cd.id,c.docCode as formNo,'allo-in' as formType,c.auditDate as formDate, 4 as sortNo,0 as isRed,
                    cd.importStockId as stockId,cd.productId,cd.allocatNum as inNumber ,cd.subCost as inAmount,
                    cd.cost as inPrice, 0 as outNumber , 0 as outAmount , 0 as outPrice, c.createTime
                from
                    oa_stock_allocation c left join oa_stock_allocation_item cd on c.id = cd.mainId
                    where cd.productId = $productId AND cd.importStockId = $stockId
                        AND c.docStatus = 'YSH' AND year(c.auditDate) = $newYear AND month(c.auditDate) = $newMonth
                union all
                select
                    i.id,o.docCode as formNo,o.docType as formType,o.auditDate as formDate,5 as sortNo,
                    o.isRed,i.stockId,i.productId, 0 as inNumber, 0 as inAmount,0 as inPrice,
                    if(o.isRed = 1 , -i.actOutNum, i.actOutNum) as outNumber,
                    if(o.isRed = 1 , -i.subCost, i.subCost) as outAmount,
                    if(o.isRed = 1 , -i.cost, i.cost ) as outPrice, o.createTime
                from
                    oa_stock_outstock_item i right join oa_stock_outstock o on o.id = i.mainId
                    where i.productId = $productId AND i.stockId = $stockId
                        AND o.docStatus = 'YSH' AND year(o.auditDate) = $newYear AND month(o.auditDate) = $newMonth
                UNION ALL
                select
                    i.id,o.docCode as formNo,o.docType as formType,o.auditDate as formDate,5 as sortNo,
                    o.isRed,$packingStockId AS stockId,i.productId,
                     0 as inNumber, 0 as inAmount,0 as inPrice,if(o.isRed = 1 , -i.outstockNum,i.outstockNum ) as outNumber,
					if(o.isRed = 1 ,i.outstockNum * i.price,i.outstockNum * i.price ) as outAmount,
					if(o.isRed = 1 ,- i.price, i.price ) as outPrice,
                    o.createTime
                from
                    oa_stock_stockout_extraitem i INNER JOIN oa_stock_outstock o on o.id = i.mainId
                    where i.productId = $productId AND $packingStockId = $stockId
                        AND o.docStatus = 'YSH' and year(o.auditDate) = $newYear and month(o.auditDate) = $newMonth
                union all
                select
                    cd.id,c.docCode as formNo,'allo-out' as formType,c.auditDate as formDate, 5 as sortNo,0 as isRed,
                    cd.exportStockId as stockId,cd.productId,0 as inNumber ,0 as inAmount, 0 as inPrice,
                    cd.allocatNum as outNumber , cd.subCost as outAmount , cd.cost as outPrice, c.createTime
                from
                    oa_stock_allocation c left join oa_stock_allocation_item cd on c.id = cd.mainId
                    where cd.productId = $productId AND cd.exportStockId = $stockId
                        AND c.docStatus = 'YSH' AND year(c.auditDate) = $newYear AND month(c.auditDate) = $newMonth
            ) db where 1 ";
        $this->sort = 'formDate ASC,createTime';
        $this->asc = false;
        $rs = $this->listBySql($sql);
        return $this->rowsFilter_d($rs);
    }

    /**
     * @param $object
     * @return bool
     */
    function listAllDetail_d($object)
    {
        if (!$object) {
            return false;
        }

        $newYear = $object['thisYear'];
        $newMonth = $object['thisMonth'];
        $productType = $object['properties'];
        if ($object['checkType'] == 1) {//���㷶ΧΪȫ������
            $productIdStr = null;
        } else if ($object['checkType'] == 2) {
            $productIdStr = " productId = '" . $object['productId'] . "' and ";
        } else {//���㷶ΧΪָ�����ϱ���
            $productNoRate = " (db.productNo between '" . $object['productNoBegin'] . "' and '" . $object['productNoEnd'] . "') and ";
        }
        $sql = "select
                db.id,db.formNo,db.formType,db.formDate,db.sortNo,db.isRed,db.stockId,db.stockName,db.productId,
                db.productNo,db.productName,db.inNumber,db.inAmount,db.inPrice,db.outNumber,db.outAmount,db.outPrice
            from
            (
                select
                    s.id,null as formNo,'balance' as formType,s.thisDate as formDate,1 as sortNo,0 as isRed,s.stockId,s.stockName,s.productId,s.productNo,s.productName,s.clearingNum as inNumber,s.balanceAmount as inAmount ,s.price as inPrice, 0 as outNumber , 0 as outAmount, 0 as outPrice
                from
                    oa_finance_stockbalance s where $productIdStr s.thisYear = " . $newYear . " and thisMonth = " . $newMonth . "
                union all
                select
                    c.id,c.formNo,'costAdjust' as formType,c.formDate ,2 as sortNo,0 as isRed,c.stockId,c.stockName,cd.productId,cd.productNo,cd.productName,0 as inNumber ,cd.money as inAmount,0 as inPrice, 0 as outNumber , 0 as outAmount, 0 as outPrice
                from
                    oa_finance_costajust c left join oa_finance_costajust_detail cd on c.id = cd.costajustId
                    where $productIdStr year(c.formDate) = " . $newYear . " and month(c.formDate) = " . $newMonth . "
                union all
                select
                    c.id,c.adjustCode as formNo,'adjustment' as formType,c.formDate,3 as sortNo,0 as isRed,cd.stockId,cd.stockName,cd.productId,cd.productNo,cd.productName,0 as inNumber ,0 as inPrice,cd.allDiffer as inAmount, 0 as outNumber , 0 as outAmount, 0 as outPrice
                from
                    oa_finance_adjustment c left join oa_finance_adjustment_detail cd on c.id = cd.adjustId
                    where $productIdStr year(c.formDate) = " . $newYear . " and month(c.formDate) = " . $newMonth . "
                union all
                select
                    t.id,i.docCode as formNo,i.docType as formType,i.auditDate as formDate,if(i.isRed = 0 ,4 ,5) as sortNo,i.isRed, t.inStockId as stockId ,t.inStockName as stockName ,t.productId,t.productCode as productNo,t.productName,
                    if(i.isRed = 0 ,t.actNum,0) as inNumber , if(i.isRed = 0 ,t.actNum*t.price,0) as inAmount ,if(i.isRed = 0 ,t.price,0) as inPrice, if(i.isRed = 0 ,0,t.actNum) as outNumber, if(i.isRed = 0 ,0,t.actNum*t.price) as outAmount, if(i.isRed = 0 ,0,t.price) as outPrice
                from
                    oa_stock_instock i left join oa_stock_instock_item t on i.id = t.mainId
                    where $productIdStr i.docStatus = 'YSH' and year(i.auditDate) = " . $newYear . " and month(i.auditDate) = " . $newMonth . "
                union all
                select
                    cd.id,c.docCode as formNo,'allo-in' as formType,c.auditDate as formDate, 4 as sortNo,0 as isRed,cd.importStockId as stockId,cd.importStockName as stockName,cd.productId,cd.productCode as productNo,cd.productName,cd.allocatNum as inNumber ,cd.subCost as inAmount, cd.cost as inPrice, 0 as outNumber , 0 as outAmount , 0 as outPrice
                from
                    oa_stock_allocation c left join oa_stock_allocation_item cd on c.id = cd.mainId
                    where $productIdStr c.docStatus = 'YSH' and year(c.auditDate) = " . $newYear . " and month(c.auditDate) = " . $newMonth . "
                union all
                select
                    i.id,o.docCode as formNo,o.docType as formType,o.auditDate as formDate,if(o.isRed = 0 ,5 ,4) as sortNo,o.isRed,i.stockId,i.stockName,i.productId,i.productCode as productNo,i.productName,
                    if(o.isRed = 1 ,i.actOutNum,0) as inNumber, if(o.isRed = 1 ,i.actOutNum*i.cost , 0) as inAmount,if(o.isRed = 1 ,i.cost , 0) as inPrice, if(o.isRed = 1 ,0,i.actOutNum ) as outNumber, if(o.isRed = 1 ,0,i.actOutNum * i.cost ) as outAmount, if(o.isRed = 1 ,0, i.cost ) as outPrice
                from
                    oa_stock_outstock_item i right join oa_stock_outstock o on o.id = i.mainId
                    where $productIdStr o.docStatus = 'YSH' and year(o.auditDate) = " . $newYear . " and month(o.auditDate) = " . $newMonth . "
                union all
                select
                    cd.id,c.docCode as formNo,'allo-out' as formType,c.auditDate as formDate, 5 as sortNo,0 as isRed,cd.exportStockId as stockId,cd.exportStockName as stockName,cd.productId,cd.productCode as productNo,cd.productName,0 as inNumber ,0 as inAmount, 0 as inPrice, cd.allocatNum as outNumber , cd.subCost as outAmount , cd.cost as outPrice
                from
                    oa_stock_allocation c left join oa_stock_allocation_item cd on c.id = cd.mainId
                    where $productIdStr c.docStatus = 'YSH' and year(c.auditDate) = " . $newYear . " and month(c.auditDate) = " . $newMonth . "
                    ) db  left join oa_stock_product_info i on db.productId = i.id left join oa_stock_baseinfo ba on db.stockId = ba.id
            where $productNoRate i.properties = '" . $productType . "' and ba.stockType='CKLX-SC' order by productId,stockId,sortNo,formDate ";
        return $this->_db->getArray($sql);
    }

    /**
     * �б������
     * @param $object
     * @return array
     */
    function rowsFilter_d($object)
    {
        $rs = array();
        $balAmount = $balNumber = $allIn = $allOut = 0;
        if ($object) {
            $start = number_format($object[0]['inAmount'], 2);
            foreach ($object as $val) {
                $balNumber = bcadd($balNumber, $val['inNumber']);
                $balNumber = bcsub($balNumber, $val['outNumber']);
                $balAmount = bcadd($balAmount, $val['inAmount'], 2);
                $balAmount = bcsub($balAmount, $val['outAmount'], 2);
                $allIn = bcadd($allIn, $val['inAmount'], 2);
                $allOut = bcadd($allOut, $val['outAmount'], 2);

                $val['balNumber'] = $balNumber;
                $val['balAmount'] = $balAmount;
                array_push($rs, $val);
            }
            array_push($rs, array('formDate' => '�ϼ�', 'formType' => $start, 'balAmount' => $balAmount,
                'inAmount' => $allIn, 'outAmount' => $allOut));
        }
        return $rs;
    }

    /**
     * �������б�
     * @param $object
     * @return bool
     */
    function listResultProduct_d($object)
    {
        if (!$object) {
            return false;
        }

        $thisYear = $object['thisYear'];
        $thisMonth = $object['thisMonth'];

        if ($thisMonth == 12) {
            $newYear = $thisYear + 1;
            $newMonth = 1;
        } else {
            $newYear = $thisYear;
            $newMonth = $thisMonth + 1;
        }

        $productType = $object['properties'];
        if ($object['checkType'] == 1) {//���㷶ΧΪȫ������
            $productIdStr = null;
        } else if ($object['checkType'] == 2) {
            $productIdStr = " s.productId = '" . $object['productId'] . "' and ";
        } else {//���㷶ΧΪָ�����ϱ���
            $productNoRate = " (s.productNo between '" . $object['productNoBegin'] . "' and '" . $object['productNoEnd'] . "') and ";
        }
        $sql = "
			select
				s.productId,s.productNo,s.productModel,s.productName,if(ins.productId is null and ons.productId is null,1,0) as isSuccess
			from
					oa_finance_stockbalance s
				left join
					(
                        select i.productId from oa_stock_instock_item i left join oa_stock_instock c on i.mainId = c.id where i.price  = 0 and year(c.auditDate) = $thisYear and month(c.auditDate) = $thisMonth group by i.productId
					) ins
					on s.productId = ins.productId
				left join
					(
                        select i.productId from oa_stock_outstock_item i left join oa_stock_outstock c on i.mainId = c.id where i.cost  = 0 and year(c.auditDate) = $thisYear and month(c.auditDate) = $thisMonth group by i.productId
					) ons
					on s.productId = ons.productId
				left join oa_stock_product_info info on s.productId = info.id
			where $productIdStr $productNoRate s.thisYear = $newYear and s.thisMonth = $newMonth and info.properties = '" . $productType . "' group by s.productId";
        return $this->_db->getArray($sql);
    }

    /**************************��Ʒ�����㲿��*****************************/

    /**
     * ��Ʒ������
     * @param $object
     * @return mixed
     */
    function productInCalList_d($object)
    {
        $isGroupByDept = $object['isGroupByDept'];
        $period = $this->rtThisPeriod_d();
        $this->searchArr['cThisYear'] = $period['thisYear'];
        $this->searchArr['cThisMonth'] = $period['thisMonth'];
        $this->searchArr['docType'] = 'RKPRODUCT';
        $this->searchArr['cdocStatus'] = 'YSH';
        $this->searchArr['isRed'] = 0;
        //�豸ѡ��
        if (!empty($object['productId'])) {
            $this->searchArr['iproductId'] = $object['productId'];
        }
        //�豸ѡ��
        if (!empty($object['productNoBegin'])) {
            $this->searchArr['productNoBegin'] = $object['productNoBegin'];
            $this->searchArr['productNoEnd'] = $object['productNoEnd'];
        }
        $this->sort = "i.productCode";
        $this->asc = false;
        if ($isGroupByDept) {
            //����ǰ����Ż��ܣ�ѡ����
            if (!empty($object['deptId'])) {
                $this->searchArr['purchaserCodes'] = $object['deptId'];
            }
            $this->groupBy = 'c.purchaserCode,i.productId';
        } else {
            $this->groupBy = 'i.productId';
        }
        return $this->list_d('count_proin');
    }

    /**
     * ��Ʒ������
     * @param $object
     * @param string $docType
     * @return bool
     */
    function productInCal_d($object, $docType = 'RKPRODUCT')
    {
        $period = $this->rtThisPeriod_d();
        $thisYear = $period['thisYear'];
        $thisMonth = $period['thisMonth'];

        $sql = null;

        $sqlHead = 'update oa_stock_instock i left join oa_stock_instock_item t on i.id = t.mainId set ';
        $sqlBottom = " where year(i.auditDate) = $thisYear and month(i.auditDate) = $thisMonth and i.docType = '$docType'";
        // ������Ϣ
        $productinfoDao = new model_stock_productinfo_productinfo();
        try {
            $this->start_d();

            foreach ($object['data'] as $key => $val) {
                if (isset($val['purchaserCode'])) {
                    $sql = $sqlHead . ' t.price = ' . $val['price'] . ' , t.subPrice = ' . $val['price'] . ' * t.actNum ' . $sqlBottom . ' and i.purchaserCode = "' . $val['purchaserCode'] . '" and t.productId = ' . $val['productId'];
                } else {
                    $sql = $sqlHead . ' t.price = ' . $val['price'] . ' , t.subPrice = ' . $val['price'] . ' * t.actNum ' . $sqlBottom . ' and t.productId = ' . $val['productId'];
                }
                $this->_db->query($sql);
                // ͬ������������Ϣ����ĵ���
                $productinfoDao->update(array('id' => $val['productId']), array('priCost' => $val['price']));
            }
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��Ʒ�����㵼��
     */
    function productInCalExcelIn_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//�������

        //�������ڲ���
        $period = $this->rtThisPeriod_d();
        $thisYear = $period['thisYear'];
        $thisMonth = $period['thisMonth'];

        $sql = null;

        $sqlHead = 'update oa_stock_instock i left join oa_stock_instock_item t on i.id = t.mainId set ';
        $sqlBottom = " where year(i.auditDate) = $thisYear and month(i.auditDate) = $thisMonth and i.docType = 'RKPRODUCT'";
        // ������Ϣ
        $productinfoDao = new model_stock_productinfo_productinfo();

        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = model_finance_common_financeExcelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            //			echo "<pre>";
            //			print_r($excelData);
            if (is_array($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[7]) && empty($val[8])) {
                        continue;
                    } else {
                        if (!empty($val[0])) {
                            $proCode = $val[0];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д���ϱ���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (!empty($val[7])) {
                            $price = $val[7];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        $sql = $sqlHead . ' t.price = ' . $price . ' , t.subPrice = t.actNum * ' . $price . $sqlBottom .
                            ' and t.productCode = "' . $proCode . '"';

                        $this->query($sql);
                        // ͬ������������Ϣ����ĵ���
                        $productinfoDao->update(array('productCode' => $proCode), array('priCost' => $price));
                        if ($this->_db->affected_rows() == 0) {
                            $tempArr['result'] = '���³ɹ�';
                        } else {
                            $tempArr['result'] = '���³ɹ�';
                        }
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("�ļ������ڿ�ʶ������!");
            }
        } else {
            msg("�ϴ��ļ����Ͳ���EXCEL!");
        }
    }

    /**
     * ��Ʒ�����㵼��
     */
    function productInCalExcelInDept_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//�������

        //�������ڲ���
        $period = $this->rtThisPeriod_d();
        $thisYear = $period['thisYear'];
        $thisMonth = $period['thisMonth'];

        $sql = null;

        $sqlHead = 'update oa_stock_instock i left join oa_stock_instock_item t on i.id = t.mainId set ';
        $sqlBottom = " where year(i.auditDate) = $thisYear and month(i.auditDate) = $thisMonth and i.docType = 'RKPRODUCT'";

        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = model_finance_common_financeExcelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            //			echo "<pre>";
            //			print_r($excelData);
            if (is_array($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1]) && empty($val[7]) && empty($val[8])) {
                        continue;
                    } else {

                        if (!empty($val[0])) {
                            $purchaserName = $val[0];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д���ϱ���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (!empty($val[1])) {
                            $proCode = $val[1];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д���ϱ���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (!empty($val[8])) {
                            $price = $val[8];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        $sql = $sqlHead . ' t.price = ' . $price . ' , t.subPrice = t.actNum * ' . $price . $sqlBottom .
                            ' and t.productCode = "' . $proCode . '" and i.purchaserName = "' . $purchaserName . '"';

                        $this->query($sql);
                        if ($this->_db->affected_rows() == 0) {
                            $tempArr['result'] = '���³ɹ�';
                        } else {
                            $tempArr['result'] = '���³ɹ�';
                        }
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("�ļ������ڿ�ʶ������!");
            }
        } else {
            msg("�ϴ��ļ����Ͳ���EXCEL!");
        }
    }

    /**************************��ӯ�����㲿��******************************/
    /**
     * ��ӯ������
     */
    function overageCalList_d()
    {
        $period = $this->rtThisPeriod_d();
        $this->searchArr['cDocDateYear'] = $period['thisYear'];
        $this->searchArr['cDocDateMonth'] = $period['thisMonth'];
        $this->searchArr['checkType'] = 'OVERAGE';
        $this->searchArr['cauditStatus'] = 'YPD';
        $this->asc = false;
        $this->groupBy = 'i.productId';
        return $this->list_d('count_overage');
    }

    /**
     * ��ӯ���������
     * @param $object
     * @param string $checkType
     * @return bool
     */
    function overageCal_d($object, $checkType = 'OVERAGE')
    {
        $period = $this->rtThisPeriod_d();
        $thisYear = $period['thisYear'];
        $thisMonth = $period['thisMonth'];
        $sql = null;

        $sqlHead = 'update oa_stock_check_info i left join oa_stock_check_item t on i.id = t.checkId set ';
        $sqlBottom = " where year(i.docDate) = $thisYear and month(i.docDate) = $thisMonth and i.checkType = '$checkType' and i.auditStatus = 'YPD'";
        try {
            $this->start_d();

            foreach ($object['data'] as $val) {
                $sql = $sqlHead . ' t.price = ' . $val['price'] . ' , t.subPrice = ' . $val['price'] . ' * t.actNum ' . $sqlBottom . ' and t.productId = ' . $val['productId'];
                $this->_db->query($sql);
            }
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /********************** ���뵼������ ***************************/

    /****************** �ڳ����� *************************/
    /**
     * �����Ϣ����
     * @param $objKeyArr
     * @param $dateArr
     * @return array
     */
    function addBalance_d($objKeyArr, $dateArr)
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();
        $codeType = $dateArr['updateCode'];
        unset($dateArr['updateCode']);//�жϵ��������Ƿ�Ϊexcel��

        //ʱ�䴦��
        $thisDate = date('Y-m-d', strtotime($dateArr['thisYear'] . "-" . $dateArr['thisMonth'] . "-01"));
        $periodNo = $dateArr['thisYear'] . "." . $dateArr['thisMonth'];

        //��������
        $stockbalanceArr = array();

        //�ִ滺������
        $stockArr = array();
        $stockDao = new model_stock_stockinfo_stockinfo();

        //����������Ϣ
        $productArr = array();
        $productDao = new model_stock_productinfo_productinfo();

        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            //			echo "<pre>";
            //			print_r($excelData);
            //			die();
            if (is_array($excelData)) {
                //������ѭ��
                if ($codeType == 'ext2') {//�����K3���룬ʹ�������Ӹ��±�
                    foreach ($excelData as $key => $val) {
                        $val[0] = trim($val[0]);
                        $val[1] = trim($val[1]);
                        $val[2] = trim($val[2]);
                        $val[3] = trim($val[3]);
                        $val[4] = trim($val[4]);
                        $actNum = $key + 2;

                        if (empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])) {
                            continue;
                        } else {
                            if (!empty($val[0]) && !empty($val[1])) {
                                //1Ϊ������0Ϊ����
                                $addOrUpdate = 1;

                                //�������
                                if (isset($stockbalanceArr[$val[0]][$val[1]])) {
                                    $addOrUpdate = 0;
                                } else {
                                    //��������ƴװ
                                    $conditionArr = array(
                                        $objKeyArr[0] => $val[0],
                                        'k3Code' => $val[1]
                                    );
                                    $conditionArr = array_merge($conditionArr, $dateArr);
                                    $rs = $this->find($conditionArr);
                                    if (is_array($rs)) {
                                        $addOrUpdate = 0;
                                        $stockbalanceArr[$val[0]][$val[1]] = $rs;
                                    }
                                }

                                //��������
                                if (isset($productArr[$val[1]])) {
                                    //�����ڶ�Ӧ���棬ֱ�ӵ���
                                    $productId = $productArr[$val[1]]['id'];
                                    $productNo = $productArr[$val[1]]['productCode'];
                                    $productName = $productArr[$val[1]]['productName'];
                                    $productModel = $productArr[$val[1]]['pattern'];
                                    $units = $productArr[$val[1]]['unitName'];
                                    $k3Code = $productArr[$val[1]]['ext2'];
                                } else {
                                    //�������ڣ������֮�����
                                    $rs = $productDao->find(array('ext2' => $val[1]), null, 'id,productCode,productName,pattern,unitName,ext2');

                                    if (is_array($rs)) {
                                        $productArr[$val[1]] = $rs;
                                        $productId = $productArr[$val[1]]['id'];
                                        $productNo = $productArr[$val[1]]['productCode'];
                                        $productName = $productArr[$val[1]]['productName'];
                                        $productModel = $productArr[$val[1]]['pattern'];
                                        $units = $productArr[$val[1]]['unitName'];
                                        $k3Code = $productArr[$val[1]]['ext2'];
                                    } else {
                                        $tempArr['docCode'] = '��' . $actNum . '������';
                                        $tempArr['result'] = '����ʧ��!<font color="blue">' . $val[0] . '</font> ���Ϊ <font color="green">' . $val[1] . '</font> ������,��ӦK3���벻������ϵͳ��';
                                        array_push($resultArr, $tempArr);
                                        continue;
                                    }
                                }

                                //����ֿ�//��������
                                if (isset($stockArr[$val[0]])) {
                                    //�����ڶ�Ӧ���棬ֱ�ӵ���
                                    $stockId = $stockArr[$val[0]]['id'];
                                    $stockName = $stockArr[$val[0]]['stockName'];
                                    $stockCode = $stockArr[$val[0]]['stockCode'];
                                } else {
                                    //�������ڣ������֮�����
                                    $rs = $stockDao->find(array('stockName' => $val[0]), null, 'id,stockName,stockCode');

                                    if (is_array($rs)) {
                                        $stockArr[$val[0]] = $rs;
                                        $stockId = $stockArr[$val[0]]['id'];
                                        $stockName = $stockArr[$val[0]]['stockName'];
                                        $stockCode = $stockArr[$val[0]]['stockCode'];
                                    } else {
                                        $tempArr['docCode'] = '��' . $actNum . '������';
                                        $tempArr['result'] = '����ʧ��!<font color="blue">' . $val[0] . '</font> ���Ϊ <font color="green">' . $val[1] . '</font> ������,�����ڵĲֿ�';
                                        array_push($resultArr, $tempArr);
                                        continue;
                                    }
                                }

                                //�ж�����
                                if (is_numeric($val[2])) {
                                    $num = $val[2];
                                } else {
                                    $num = 0;
                                }
                                //�жϵ���
                                if (is_numeric($val[3])) {
                                    $price = $val[3];
                                } else {
                                    $price = 0;
                                }
                                //�жϽ����
                                if (is_numeric($val[4])) {
                                    $allMoney = $val[4];
                                } else {
                                    $allMoney = 0;
                                }

                                if ($addOrUpdate == 1) {
                                    $sql = "insert into " . $this->tbl_name . " (thisYear,thisMonth,thisDate,periodNo,stockName,stockId,stockCode,productId,productNo,productName,productModel,units,k3Code,clearingNum,price,balanceAmount) value ";
                                    $sql .= "(" . $dateArr['thisYear'] . "," . $dateArr['thisMonth'] . ",'$thisDate','$periodNo','$stockName',$stockId,'$stockCode',$productId,'$productNo','$productName','$productModel','$units','$k3Code',$num,$price,$allMoney)";
                                    $this->_db->query($sql);
                                    if ($this->_db->insert_id()) {
                                        $tempArr['result'] = '����ɹ�';
                                    } else {
                                        $tempArr['result'] = '����ʧ��';
                                    }
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    array_push($resultArr, $tempArr);
                                } else {
                                    $sql = "update oa_finance_stockbalance s right join oa_stock_product_info i on s.productId = i.id set s.clearingNum = $num ,s.price = $price,balanceAmount = $allMoney where i.ext2 = '$val[1]' and s.stockName = '$val[0]' and s.thisYear =  '" . $dateArr['thisYear'] . "' and s.thisMonth = '" . $dateArr['thisMonth'] . "'";
                                    $this->_db->query($sql);
                                    if ($this->_db->affected_rows() == 1) {
                                        $tempArr['result'] = '���³ɹ�';
                                    } else {
                                        $tempArr['result'] = '���³ɹ�,��Ӱ����������Ϊ0';
                                    }
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    array_push($resultArr, $tempArr);
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!ȱ�ٲֿ����ƻ������ϱ���';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }
                    }
                } else {//OA�������
                    foreach ($excelData as $key => $val) {
                        $val[0] = trim($val[0]);
                        $val[1] = trim($val[1]);
                        $val[2] = trim($val[2]);
                        $val[3] = trim($val[3]);
                        $val[4] = trim($val[4]);
                        $actNum = $key + 2;

                        if (empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])) {
                            continue;
                        } else {
                            if (!empty($val[0]) && !empty($val[1])) {
                                //1Ϊ������0Ϊ����
                                $addOrUpdate = 1;

                                //�������
                                if (isset($stockbalanceArr[$val[0]][$val[1]])) {
                                    $addOrUpdate = 0;
                                } else {
                                    //��������ƴװ
                                    $conditionArr = array(
                                        $objKeyArr[0] => $val[0],
                                        $objKeyArr[1] => $val[1]
                                    );
                                    $conditionArr = array_merge($conditionArr, $dateArr);
                                    $rs = $this->find($conditionArr);
                                    if (is_array($rs)) {
                                        $addOrUpdate = 0;
                                        $stockbalanceArr[$val[0]][$val[1]] = $rs;
                                    }
                                }

                                //��������
                                if (isset($productArr[$val[1]])) {
                                    //�����ڶ�Ӧ���棬ֱ�ӵ���
                                    $productId = $productArr[$val[1]]['id'];
                                    $productNo = $productArr[$val[1]]['productCode'];
                                    $productName = $productArr[$val[1]]['productName'];
                                    $productModel = $productArr[$val[1]]['pattern'];
                                    $units = $productArr[$val[1]]['unitName'];
                                    $k3Code = $productArr[$val[1]]['ext2'];
                                } else {
                                    //�������ڣ������֮�����
                                    $rs = $productDao->find(array('productCode' => $val[1]), null, 'id,productCode,productName,pattern,unitName,ext2');

                                    if (is_array($rs)) {
                                        $productArr[$val[1]] = $rs;
                                        $productId = $productArr[$val[1]]['id'];
                                        $productNo = $productArr[$val[1]]['productCode'];
                                        $productName = $productArr[$val[1]]['productName'];
                                        $productModel = $productArr[$val[1]]['pattern'];
                                        $units = $productArr[$val[1]]['unitName'];
                                        $k3Code = $productArr[$val[1]]['ext2'];
                                    } else {
                                        $tempArr['docCode'] = '��' . $actNum . '������';
                                        $tempArr['result'] = '����ʧ��!<font color="blue">' . $val[0] . '</font> ���Ϊ <font color="green">' . $val[1] . '</font> ������,��Ӧ���ϲ�������ϵͳ��';
                                        array_push($resultArr, $tempArr);
                                        continue;
                                    }
                                }

                                //����ֿ�//��������
                                if (isset($stockArr[$val[0]])) {
                                    //�����ڶ�Ӧ���棬ֱ�ӵ���
                                    $stockId = $stockArr[$val[0]]['id'];
                                    $stockName = $stockArr[$val[0]]['stockName'];
                                    $stockCode = $stockArr[$val[0]]['stockCode'];
                                } else {
                                    //�������ڣ������֮�����
                                    $rs = $stockDao->find(array('stockName' => $val[0]), null, 'id,stockName,stockCode');

                                    if (is_array($rs)) {
                                        $stockArr[$val[0]] = $rs;
                                        $stockId = $stockArr[$val[0]]['id'];
                                        $stockName = $stockArr[$val[0]]['stockName'];
                                        $stockCode = $stockArr[$val[0]]['stockCode'];
                                    } else {
                                        $tempArr['docCode'] = '��' . $actNum . '������';
                                        $tempArr['result'] = '����ʧ��!<font color="blue">' . $val[0] . '</font> ���Ϊ <font color="green">' . $val[1] . '</font> ������,�����ڵĲֿ�';
                                        array_push($resultArr, $tempArr);
                                        continue;
                                    }
                                }

                                //�ж�����
                                if (is_numeric($val[2])) {
                                    $num = $val[2];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!<font color="blue">' . $val[0] . '</font> ���Ϊ <font color="green">' . $val[1] . '</font> �����Ͻ������Ϊ�ջ�Ϊ��������';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                                //�жϵ���
                                if (is_numeric($val[3])) {
                                    $price = $val[3];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!<font color="blue">' . $val[0] . '</font> ���Ϊ <font color="green">' . $val[1] . '</font> �����ϵ���Ϊ�ջ�Ϊ��������';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                                //�жϽ����
                                if (is_numeric($val[4])) {
                                    $allMoney = $val[4];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!<font color="blue">' . $val[0] . '</font> ���Ϊ <font color="green">' . $val[1] . '</font> �����Ͻ����Ϊ�ջ�Ϊ��������';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }

                                if ($addOrUpdate == 1) {
                                    $sql = "insert into " . $this->tbl_name . " (thisYear,thisMonth,thisDate,periodNo,stockName,stockId,stockCode,productId,productNo,productName,productModel,units,k3Code,clearingNum,price,balanceAmount) value ";
                                    $sql .= "(" . $dateArr['thisYear'] . "," . $dateArr['thisMonth'] . ",'$thisDate','$periodNo','$stockName',$stockId,'$stockCode',$productId,'$productNo','$productName','$productModel','$units','$k3Code',$num,$price,$allMoney)";
                                    $this->_db->query($sql);
                                    if ($this->_db->insert_id()) {
                                        $tempArr['result'] = '����ɹ�';
                                    } else {
                                        $tempArr['result'] = '����ʧ��';
                                    }
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    array_push($resultArr, $tempArr);
                                } else {
                                    $sql = "update oa_finance_stockbalance s right join oa_stock_product_info i on s.productId = i.id set s.clearingNum = $num ,s.price = $price,balanceAmount = $allMoney where i.productCode = '$val[1]' and s.stockName = '$val[0]' and s.thisYear =  '" . $dateArr['thisYear'] . "' and s.thisMonth = '" . $dateArr['thisMonth'] . "'";
                                    $this->_db->query($sql);
                                    if ($this->_db->affected_rows() == 1) {
                                        $tempArr['result'] = '���³ɹ�';
                                    } else {
                                        $tempArr['result'] = '���³ɹ�,��Ӱ����������Ϊ0';
                                    }
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    array_push($resultArr, $tempArr);
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!ȱ�ٲֿ����ƻ������ϱ���';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }
                    }
                }
                return $resultArr;
            } else {
                msg("�ļ������ڿ�ʶ������!");
            }
        } else {
            msg("�ϴ��ļ����Ͳ���EXCEL!");
        }
    }

    /****************** ���´����Ϣ *************************/
    /**
     * excel������������Ϣ
     * @param $objKeyArr
     * @param $dateArr
     * @return array
     */
    function addExecelDatabyPro_d($objKeyArr, $dateArr)
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();
        $codeType = $dateArr['updateCode'];
        unset($dateArr['updateCode']);
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //������ѭ��
                if ($codeType == 'ext2') {//�����K3���룬ʹ�������Ӹ��±�
                    foreach ($excelData as $key => $val) {
                        $val[1] = trim($val[1]);
                        $val[3] = trim($val[3]);
                        $actNum = $key + 2;

                        if (empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])) {
                            continue;
                        } else {
                            if (!empty($val[1])) {
                                //�жϵ���
                                if (!empty($val[3]) && ($val[3] * 1) == $val[3]) {
                                    $price = $val[3];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!<font color="blue">' . $val[0] . '</font> ���Ϊ <font color="green">' . $val[1] . '</font> �����ϵ���Ϊ�ջ�Ϊ��������';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                                // �����豸����
                                $sql = "update oa_finance_stockbalance s inner join oa_stock_product_info i on s.productId = i.id " .
                                    "set s.price = $price,s.balanceAmount = s.clearingNum * $price where i.ext2 = '$val[1]' " .
                                    "and s.thisYear =  '" . $dateArr['thisYear'] . "' and s.thisMonth = '" . $dateArr['thisMonth'] . "'";
                                $this->_db->query($sql);

                                if ($this->_db->affected_rows() == 0) {
                                    $tempArr['result'] = '���³ɹ��������µ���������Ϊ0';
                                } else {
                                    $tempArr['result'] = '���³ɹ�';
                                }
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                array_push($resultArr, $tempArr);
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!ȱ�����ϱ���';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }
                    }
                } else {//OA�������
                    foreach ($excelData as $key => $val) {
                        $val[1] = trim($val[1]);
                        $val[3] = trim($val[3]);
                        $actNum = $key + 2;

                        if (empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])) {
                            continue;
                        } else {
                            if (!empty($val[1])) {
                                //�жϵ���
                                if (!empty($val[3]) && ($val[3] * 1) == $val[3]) {
                                    $price = $val[3];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!<font color="blue">' . $val[0] . '</font> ���Ϊ <font color="green">' . $val[1] . '</font> �����ϵ���Ϊ�ջ�Ϊ��������';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                                // �����豸����
                                $sql = "update oa_finance_stockbalance s " .
                                    "set s.price = $price,s.balanceAmount = s.clearingNum * $price where s.productNo = '$val[1]' " .
                                    "and s.thisYear =  '" . $dateArr['thisYear'] . "' and s.thisMonth = '" . $dateArr['thisMonth'] . "'";
                                $this->_db->query($sql);

                                if ($this->_db->affected_rows() == 0) {
                                    $tempArr['result'] = '���³ɹ��������µ���������Ϊ0';
                                } else {
                                    $tempArr['result'] = '���³ɹ�';
                                }
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                array_push($resultArr, $tempArr);
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!ȱ�����ϱ���';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }
                    }
                }
                return $resultArr;
            } else {
                msg("�ļ������ڿ�ʶ������!");
            }
        } else {
            msg("�ϴ��ļ����Ͳ���EXCEL!");
        }
    }

    /**
     * ��ȡ�ڳ����ļ۸�
     * @param $thisYear
     * @param $thisMonth
     * @param $productIdArr
     * @return array
     */
    function getBeginPrice_d($thisYear, $thisMonth, $productIdArr)
    {
        // ���ؽ��
        $results = array();

        if (empty($productIdArr)) {
            return $results;
        }

        // ��ѯ���Ϸ�Χ
        $productIds = implode(",", $productIdArr);

        // ��ѯ���ϵ����³��ⵥ��
        $sql = "SELECT productId,price FROM oa_finance_stockbalance
				WHERE thisYear = $thisYear AND thisMonth = $thisMonth
					AND productId IN($productIds)
				GROUP BY productId";
        $rows = $this->_db->getArray($sql);

        if ($rows) {
            foreach ($rows as $v) {
                if (!isset($results[$v['productId']])) {
                    $results[$v['productId']] = $v['price'];
                }
            }
        }

        return $results;
    }
}