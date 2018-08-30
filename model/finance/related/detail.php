<?php

/**
 * @author Show
 * @Date 2010��12��29�� ������ 20:07:33
 * @version 1.0
 * @description:��������¼�� Model��
 */
class model_finance_related_detail extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_finance_related_detail";
        $this->sql_map = "finance/related/detailSql.php";
        parent::__construct();
    }

    /********************************ҳ����ʾ***************************/
    /**
     * ��ʾ������ϸ��Ϣҳ��
     */
    function showDetailInInit($rows)
    {
        $str = null;
        $i = 0;
        if ($rows) {
            foreach ($rows as $val) {
                $i++;
                $str .= <<<EOT
					<tr>
						<td width="5%">$i
						</td>
						<td width="12%">$val[hookObjCode]
						</td>
						<td width="12%">$val[productNo]
						</td>
						<td>$val[productName]
						</td>
						<td width="8%">$val[number]
						</td>
						<td width="12%" class="formatMoney">$val[firstPrice]
						</td>
						<td width="12%" class="formatMoney">$val[price]
						</td>
						<td width="13%" class="formatMoney">$val[firstAmount]
						</td>
						<td width="13%" class="formatMoney">$val[amount]
						</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * ���ݲɹ���Ʊid��ȡ������id
     */
    function getRelatedIds_d($invPurId, $objType = 'invpurchase')
    {
        $invpurchaseDao = new model_finance_invpurchase_invpurchase();
        $mainHookObj = $invpurchaseDao->get_d($invPurId);
        $hookObjCode = isset($mainHookObj['objCode']) ? $mainHookObj['objCode'] : '';
        $conditionArr = ($hookObjCode == '') ? array('hookMainId' => $invPurId, 'hookObj' => $objType) : array('hookMainId' => $invPurId, 'hookObjCode' => $hookObjCode, 'hookObj' => $objType);// hookObjCode)
        return $this->findAll($conditionArr, null, 'relatedId');
    }

    /********************************��������****************************/

    /**
     * д��ɹ���Ʊ��Ŀ
     */
    function addInvpurDetail_d($object, $relatedId, $storage, $costAmount = 0, $shareType = 'forNumber', $checkCards = array())
    {
        /******************�ɹ���Ʊ���ֿ�ʼ*******************/
        $invDetailDao = new model_finance_invpurchase_invpurdetail();
        $invPurDao = new model_finance_invpurchase_invpurchase();
        $markId = null;
        $invIds = array();
        $productArr = array();
        $date = day_date;
        foreach ($object as $key => $val) {
            //��Ϲ�������
            $insertDetail = $val;
            $insertDetail['hookObj'] = 'invpurchase';
            $insertDetail['hookDate'] = $date;
            $insertDetail['isAcount'] = 0;
            $insertDetail['relatedId'] = $relatedId;
            $insertDetail['hookNumber'] = $val['hookNumber'] + $val['number'];
            $insertDetail['hookAmount'] = bcadd($val['hookAmount'], $val['amount'], 2);
            $insertDetail['unHookNumber'] = $val['unHookNumber'] - $val['number'];
            $insertDetail['unHookAmount'] = bcsub($val['unHookAmount'], $val['amount'], 2);
            $this->add_d($insertDetail);

            //���ɲ�Ʒid => ������ �� ��Ʒid => �ܽ�� ����
            if (isset($productArr[$val['productId']])) {
                $productArr[$val['productId']] = bcadd($productArr[$val['productId']], $val['number'], 2);
                $productArr[$val['productId'] . '_amount'] = bcadd($productArr[$val['productId'] . '_amount'], $val['amount'], 2);
            } else {
                $productArr[$val['productId']] = $val['number'];
                $productArr[$val['productId'] . '_amount'] = $val['amount'];
                $productArr[$val['productId'] . '_price'] = $val['price'];
            }

            //�޸Ĳɹ���Ʊ��Ŀ
            $val['id'] = $val['hookId'];
            $invDetailDao->hookDeal_d($val);

            if (!in_array($val['hookMainId'], $invIds)) {
                $invIds[] = $val['hookMainId'];
            }
        }

        // ���²ɹ���Ʊ����״̬
        foreach ($invIds as $v) {
            $invPurDao->editEasy_d(array('id' => $v, 'status' => 1));
        }
        /******************�ɹ���Ʊ���ֽ���*******************/

        if (!empty($checkCards)) {
            /******************�ʲ��ɹ���Ƭ������¼���ֿ�ʼ*******************/
            $cardHookRecord = array();
            foreach ($checkCards as $cardv) {
                $hookRecord = array();
                $cardArr = explode(",", $cardv);
                $hookRecord['cardNo'] = $cardArr[3];
                $hookRecord['cardBindId'] = $cardArr[4];
                $hookRecord['productId'] = $cardArr[0];
                $hookRecord['productNo'] = $cardArr[1];
                $hookRecord['objCode'] = $cardArr[2];
                $hookRecord['remarks'] = '';
                foreach ($object as $objk => $objv) {
                    if ($objv['contractCode'] == $cardArr[2] && $objv['productNo'] == $cardArr[1]) {
                        $hookRecord['objId'] = $objv['contractId'];
                        $hookRecord['invoiceId'] = $objv['hookMainId'];
                        $hookRecord['invoiceCode'] = $objv['hookObjCode'];
                        $hookRecord['createTime'] = time();
                        $hookRecord['createId'] = $_SESSION['USER_ID'];
                    }
                }
                $cardHookRecord[] = $hookRecord;
            }
            $this->tbl_name = 'oa_finance_assetscard_hookrecord';
            $this->createBatch($cardHookRecord);
            /******************�ʲ��ɹ���Ƭ������¼���ֽ���*******************/
        } else {
            /******************�ɹ���ⵥ���ֿ�ʼ*******************/
            //��Ӧ��������
            $productPrices = array();
            $stockInIds = array(); // ��ⵥid����

            $stockinDao = new model_stock_instock_stockin();//�⹺��ⵥ
            $stockinitemDao = new model_stock_instock_stockinitem();//�⹺��ⵥ��ϸ

            //���������ݣ�����
            foreach ($storage as $key => $val) {
                if ($val['number'] == 0) {
                    continue;
                }

                //��¼�ֶ�
                $thisHookNumber = $val['hookNumber'];

                // ��ѯԭʼ�۸�
                $orgPrice = $this->find(array('hookObj' => 'storage', 'hookId' => $val['hookId']), null, 'firstPrice, firstAmount');
                if ($orgPrice) {
                    $val['firstPrice'] = $orgPrice['firstPrice'];
                    $val['firstAmount'] = $orgPrice['firstAmount'];
                } else {
                    $val['firstPrice'] = $val['cost'];
                    $val['firstAmount'] = $val['subCost'];
                }

                //����������
                $val['hookObj'] = 'storage';
                $val['hookDate'] = $date;
                $val['isAcount'] = 0;
                $val['relatedId'] = $relatedId;
                $val['hookNumber'] = $val['hookNumber'] + $val['number'];  //�ѹ�������
                $val['unHookNumber'] = $val['unHookNumber'] - $val['number'];  //δ��������
                $val['unHookAmount'] = bcmul($val['firstPrice'], $val['unHookNumber'], 6); // δ������� = ԭ�ܼ� - ԭ���� * ��������

                //��ֵ�������
                if (isset($productPrices[$val['productId']])) {
                    $val['hookAmount'] = bcmul($productPrices[$val['productId']], $val['hookNumber'], 2);
                } else {
                    $productPrices[$val['productId']] = bcdiv($productArr[$val['productId'] . '_amount'], $productArr[$val['productId']], 6);
                    $val['hookAmount'] = $productArr[$val['productId'] . '_amount'];
                }

                $val['price'] = $productPrices[$val['productId']];
                $val['amount'] = round(bcmul($val['price'], $val['number'], 6), 2);

//				print_r($val);
                $this->add_d($val);

                //�ɹ��嵥��Ŀ����

                //�жϳ��ⵥ���۴���
                //���δ�����������ݣ���ֱ�Ӵ�����
                //����ǲ��ֹ��������ݣ���ȡ�������ƽ������
                if ($thisHookNumber) {
                    $row = $stockinitemDao->find(array('id' => $val['hookId']), null, 'price,actNum,hookNumber');
                    $val['price'] = ($row['price'] * $row['hookNumber'] + $val['amount']) / ($row['hookNumber'] + $val['number']);
                }
                $stockinitemDao->hookDeal_d($val);

                if (!in_array($val['hookMainId'], $stockInIds)) {
                    $stockInIds[] = $val['hookMainId'];
                }
            }

            /// ���¹���״̬
            foreach ($stockInIds as $v) {
                // ����״̬�ж�
                $catchStatus = $stockinitemDao->hookNumJudge($v) != 0 ? 'CGFPZT-BFGJ' : 'CGFPZT-YGJ';
                // ���¹���״̬
                $stockinDao->parentEdit(array('id' => $v, 'catchStatus' => $catchStatus));
            }
            /******************�ɹ���ⵥ��ⵥ����*******************/
        }
    }

    /********************************����������**********************************/

    /**
     * ������ - �ɹ���Ʊ����
     */
    function unhookInvPur_d($relatedId)
    {
        $invDetailDao = new model_finance_invpurchase_invpurdetail();
        $invPurDao = new model_finance_invpurchase_invpurchase();
        $rows = $this->findAll(array('relatedId' => $relatedId, 'hookObj' => 'invpurchase'), null,
            'id,price,number,amount,hookId,hookMainId,productId,productNo,hookObjCode');
//		print_r($rows);
        $cacheIds = array();
        foreach ($rows as $key => $val) {
            //��������¼ - ��ԭ�ɹ���Ʊ��Ŀ
            $invDetailDao->unhookDeal_d($val);

            //ɾ������������¼
            $this->deletes_d($val['id']);

            // ������صĿ�Ƭ��¼Ҳһ��ɾ����
            $cardHookRecord = $this->_db->getArray("select * from oa_finance_assetscard_hookrecord where invoiceId = '{$val['hookMainId']}' AND invoiceCode = '{$val['hookObjCode']}' AND productNo = '{$val['productNo']}';");
            if (!empty($cardHookRecord)) {
                foreach ($cardHookRecord as $cartK => $cartV) {
                    $this->_db->query("delete from oa_finance_assetscard_hookrecord where id = '{$cartV['id']}'");
                }
            }

            if (!in_array($val['hookMainId'], $cacheIds)) {
                $cacheIds[] = $val['hookMainId'];
            }
        }

        foreach ($cacheIds as $v) {
            if ($this->getRelatedIds_d($v)) {
                $invPurDao->editEasy_d(array('id' => $v, 'status' => '1'));
            } else {
                $invPurDao->editEasy_d(array('id' => $v, 'status' => '0'));
            }
        }
    }

    /**
     * ������ �� �⹺��⴦��
     */
    function unhookStorage_d($relatedId)
    {
        //��ȡ������¼
        $rows = $this->findAll(array('relatedId' => $relatedId, 'hookObj' => 'storage'), null,
            'id,price,number,amount,hookId,hookMainId');
        $stockinitemDao = new model_stock_instock_stockinitem();
        $stockinDao = new model_stock_instock_stockin();
        $storageMark = null;
        $cacheIds = array();

        foreach ($rows as $key => $val) {
            //�������������ⵥ����
            $stockinitemDao->unhookDeal_d($val);
            //ɾ��������¼
            $this->deletes_d($val['id']);

            if (!in_array($val['hookMainId'], $cacheIds)) {
                $cacheIds[] = $val['hookMainId'];
            }
        }

        foreach ($cacheIds as $v) {
            if ($this->getRelatedIds_d($v, 'storage')) {
                $stockinDao->parentEdit(array('id' => $v, 'catchStatus' => 'CGFPZT-BFGJ'));
            } else {
                $stockinDao->parentEdit(array('id' => $v, 'catchStatus' => 'CGFPZT-WGJ'));
            }
        }
    }

    /**
     * ���ݹ�������id�Ͷ������Ͳ��ҹ���id
     */
    function getBaseInfo_d($objId, $objType = 'invpurchase')
    {
        $rows = $this->find(array('hookMainId' => $objId, 'hookObj' => $objType), null, 'relatedId');
        return $rows['relatedId'];
    }

    /***************************�ݹ����***********************/
    function addReleaseDetail_d($object, $relatedId, $costAmount = 0)
    {
        // ����
    }
}