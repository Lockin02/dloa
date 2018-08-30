<?php

/**
 * @author Show
 * @Date 2010年12月29日 星期三 20:07:33
 * @version 1.0
 * @description:发表勾稽记录表 Model层
 */
class model_finance_related_detail extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_finance_related_detail";
        $this->sql_map = "finance/related/detailSql.php";
        parent::__construct();
    }

    /********************************页面显示***************************/
    /**
     * 显示钩稽详细信息页面
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
     * 根据采购发票id获取钩稽主id
     */
    function getRelatedIds_d($invPurId, $objType = 'invpurchase')
    {
        $invpurchaseDao = new model_finance_invpurchase_invpurchase();
        $mainHookObj = $invpurchaseDao->get_d($invPurId);
        $hookObjCode = isset($mainHookObj['objCode']) ? $mainHookObj['objCode'] : '';
        $conditionArr = ($hookObjCode == '') ? array('hookMainId' => $invPurId, 'hookObj' => $objType) : array('hookMainId' => $invPurId, 'hookObjCode' => $hookObjCode, 'hookObj' => $objType);// hookObjCode)
        return $this->findAll($conditionArr, null, 'relatedId');
    }

    /********************************钩稽部分****************************/

    /**
     * 写入采购发票条目
     */
    function addInvpurDetail_d($object, $relatedId, $storage, $costAmount = 0, $shareType = 'forNumber', $checkCards = array())
    {
        /******************采购发票部分开始*******************/
        $invDetailDao = new model_finance_invpurchase_invpurdetail();
        $invPurDao = new model_finance_invpurchase_invpurchase();
        $markId = null;
        $invIds = array();
        $productArr = array();
        $date = day_date;
        foreach ($object as $key => $val) {
            //组合钩稽数组
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

            //生成产品id => 总数量 ， 产品id => 总金额 数组
            if (isset($productArr[$val['productId']])) {
                $productArr[$val['productId']] = bcadd($productArr[$val['productId']], $val['number'], 2);
                $productArr[$val['productId'] . '_amount'] = bcadd($productArr[$val['productId'] . '_amount'], $val['amount'], 2);
            } else {
                $productArr[$val['productId']] = $val['number'];
                $productArr[$val['productId'] . '_amount'] = $val['amount'];
                $productArr[$val['productId'] . '_price'] = $val['price'];
            }

            //修改采购发票条目
            $val['id'] = $val['hookId'];
            $invDetailDao->hookDeal_d($val);

            if (!in_array($val['hookMainId'], $invIds)) {
                $invIds[] = $val['hookMainId'];
            }
        }

        // 更新采购发票钩稽状态
        foreach ($invIds as $v) {
            $invPurDao->editEasy_d(array('id' => $v, 'status' => 1));
        }
        /******************采购发票部分结束*******************/

        if (!empty($checkCards)) {
            /******************资产采购卡片勾稽记录部分开始*******************/
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
            /******************资产采购卡片勾稽记录部分结束*******************/
        } else {
            /******************采购入库单部分开始*******************/
            //对应单价数组
            $productPrices = array();
            $stockInIds = array(); // 入库单id缓存

            $stockinDao = new model_stock_instock_stockin();//外购入库单
            $stockinitemDao = new model_stock_instock_stockinitem();//外购入库单明细

            //整理钩稽数据，插入
            foreach ($storage as $key => $val) {
                if ($val['number'] == 0) {
                    continue;
                }

                //记录字段
                $thisHookNumber = $val['hookNumber'];

                // 查询原始价格
                $orgPrice = $this->find(array('hookObj' => 'storage', 'hookId' => $val['hookId']), null, 'firstPrice, firstAmount');
                if ($orgPrice) {
                    $val['firstPrice'] = $orgPrice['firstPrice'];
                    $val['firstAmount'] = $orgPrice['firstAmount'];
                } else {
                    $val['firstPrice'] = $val['cost'];
                    $val['firstAmount'] = $val['subCost'];
                }

                //生成新数组
                $val['hookObj'] = 'storage';
                $val['hookDate'] = $date;
                $val['isAcount'] = 0;
                $val['relatedId'] = $relatedId;
                $val['hookNumber'] = $val['hookNumber'] + $val['number'];  //已钩稽数量
                $val['unHookNumber'] = $val['unHookNumber'] - $val['number'];  //未钩稽数量
                $val['unHookAmount'] = bcmul($val['firstPrice'], $val['unHookNumber'], 6); // 未钩稽金额 = 原总价 - 原单价 * 钩稽数量

                //设值钩稽金额
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

                //采购清单条目处理

                //判断出库单单价处理
                //如果未钩稽过的数据，则直接代入金额
                //如果是部分钩稽的数据，则取出金额做平均处理
                if ($thisHookNumber) {
                    $row = $stockinitemDao->find(array('id' => $val['hookId']), null, 'price,actNum,hookNumber');
                    $val['price'] = ($row['price'] * $row['hookNumber'] + $val['amount']) / ($row['hookNumber'] + $val['number']);
                }
                $stockinitemDao->hookDeal_d($val);

                if (!in_array($val['hookMainId'], $stockInIds)) {
                    $stockInIds[] = $val['hookMainId'];
                }
            }

            /// 更新钩稽状态
            foreach ($stockInIds as $v) {
                // 钩稽状态判定
                $catchStatus = $stockinitemDao->hookNumJudge($v) != 0 ? 'CGFPZT-BFGJ' : 'CGFPZT-YGJ';
                // 更新钩稽状态
                $stockinDao->parentEdit(array('id' => $v, 'catchStatus' => $catchStatus));
            }
            /******************采购入库单入库单结束*******************/
        }
    }

    /********************************反钩稽部分**********************************/

    /**
     * 反钩稽 - 采购发票处理
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
            //处理钩稽记录 - 还原采购发票条目
            $invDetailDao->unhookDeal_d($val);

            //删除该条钩稽记录
            $this->deletes_d($val['id']);

            // 如有相关的卡片记录也一起删除了
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
     * 反钩稽 － 外购入库处理
     */
    function unhookStorage_d($relatedId)
    {
        //获取钩稽记录
        $rows = $this->findAll(array('relatedId' => $relatedId, 'hookObj' => 'storage'), null,
            'id,price,number,amount,hookId,hookMainId');
        $stockinitemDao = new model_stock_instock_stockinitem();
        $stockinDao = new model_stock_instock_stockin();
        $storageMark = null;
        $cacheIds = array();

        foreach ($rows as $key => $val) {
            //处理反钩稽后的入库单数量
            $stockinitemDao->unhookDeal_d($val);
            //删除钩稽记录
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
     * 根据钩稽对象id和对象类型查找钩稽id
     */
    function getBaseInfo_d($objId, $objType = 'invpurchase')
    {
        $rows = $this->find(array('hookMainId' => $objId, 'hookObj' => $objType), null, 'relatedId');
        return $rows['relatedId'];
    }

    /***************************暂估冲回***********************/
    function addReleaseDetail_d($object, $relatedId, $costAmount = 0)
    {
        // 丢弃
    }
}