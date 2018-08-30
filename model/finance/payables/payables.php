<?php

/**
 * @author Show
 * @Date 2011年5月6日 星期五 16:17:38
 * @version 1.0
 * @description:应付付款单/应付预付款/应付退款单 Model层
 */
class model_finance_payables_payables extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_payables";
        $this->sql_map = "finance/payables/payablesSql.php";
        parent::__construct();
    }

    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

    /********************新策略部分使用************************/
    private $relatedStrategyArr = array(//不同类型入库申请策略类,根据需要在这里进行追加
        'CWYF-01' => 'model_finance_payables_strategy_spayment', //付款单
        'CWYF-02' => 'model_finance_payables_strategy_advances', //预付款
        'CWYF-03' => 'model_finance_payables_strategy_refund' //退款单
    );

    private $relatedCode = array(
        'CWYF-01' => 'spayment',
        'CWYF-02' => 'advances',
        'CWYF-03' => 'refund'
    );

    /**
     * 据中文转义
     */
    private $relateName = array(
        'CWYF-01' => '付款单',
        'CWYF-02' => '预付单',
        'CWYF-03' => '退款单'
    );

    /**
     * 根据类型返回业务名称
     * @param $objType
     * @return mixed
     */
    public function getBusinessCode($objType) {
        return $this->relatedCode[$objType];
    }

    /**
     * 根据数据类型返回类
     * @param $objType
     * @return mixed
     */
    public function getClass($objType) {
        return $this->relatedStrategyArr[$objType];
    }

    /**
     * 根据业务id和类型获取相关信息
     * @param $objId
     * @param ipayables $strategy
     * @return mixed
     */
    public function getObjInfo_d($objId, ipayables $strategy) {
        return $strategy->getInfoAndDetail_d($objId);
    }

    /**
     * 返回类型名称
     * @param $objType
     * @return mixed
     */
    function rtFormName($objType) {
        return $this->relateName[$objType];
    }

    // 返回是否
    function rtYesOrNo_d($thisVal) {
        if ($thisVal == '1') {
            return '是';
        } else {
            return '否';
        }
    }

    /**********************外部调用接口**************************/
    /**
     * 重写add_d
     * @param $object
     * @param ipayables $strategy
     * @return bool
     */
    function add_d($object, ipayables $strategy) {
        $codeRuleDao = new model_common_codeRule();
        try {
            $this->start_d();

            //处理明细
            $detail = $object['detail'];
            unset($object['detail']);

            //处理邮件
            if (isset($object['email'])) {
                $emailArr = $object['email'];
                unset($object['email']);
            }

            //处理下推状态
            if (isset($object['pushDown'])) {
                $pushDown = $object['pushDown'];
                unset($object['pushDown']);
            }

            //自动产生到款号
            if ($object['formType'] == 'CWYF-01') {
                $object['formNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'FKJL');
            } else if ($object['formType'] == 'CWYF-02') {
                $object['formNo'] = $codeRuleDao->financeCode($this->tbl_name . '_k', 'DL', 'FK');
            } else {
                $object['formNo'] = $codeRuleDao->financeCode($this->tbl_name . '_t', 'DL', 'FT');
            }
			// 本位币金额默认等于单据金额
			if(empty($object['amountCur'])){
				$object['amountCur'] = $object['amount'];
			}
            $newId = parent:: add_d($object, true);

            if ($object['payApplyNo']) {
                $payablesapplyDao = new model_finance_payablesapply_payablesapply();
                $updateArr = array('status' => 'FKSQD-03', 'payedMoney' => $object['amount'], 'actPayDate' => $object['formDate']);
                $updateArr = $this->addUpdateInfo($updateArr);
                $payablesapplyDao->update(array('id' => $object['payApplyId']), $updateArr);

                if ($object['sourceType'] == 'YFRK-01') {
                    $payablesapplyDao->getMailDetail_d($object['payApplyId']);
                }
            }

            /**
             * 从表信息录入
             */
            if (!empty($detail)) {
                $payablesDetailDao = new model_finance_payables_detail();
                $payablesDetailDao->createBatch($detail, array('advancesId' => $newId));
            }

            /**
             * 源单内容反写
             */
            if (!empty($detail)) {
                $objArr = array();
                foreach ($detail as $val) {
                    if ($val['objType'] == 'YFRK-01' && !empty($val['objId']) && $val['objId'] != 0) {
                        if (empty($objArr[$val['objType']])) {
                            $objArr[$val['objType']] = new model_purchase_contract_purchasecontract();
                        }
                        $objArr[$val['objType']]->end_d($val['objId']);
                    }
                    //付款处理 反写其他合同内容
                    if ($val['objType'] == 'YFRK-02' && !empty($val['objId']) && $val['objId'] != 0) {
                        if (empty($objArr[$val['objType']])) {
                            $objArr[$val['objType']] = new model_contract_other_other();
                        }
                        $objArr[$val['objType']]->end_d($val['objId']);
                    }
                    //付款处理 反写租车合同
                    if ($val['objType'] == 'YFRK-06' && !empty($val['objId']) && $val['objId'] != 0) {
                        if (empty($objArr[$val['objType']])) {
                            $objArr[$val['objType']] = new model_outsourcing_vehicle_register();
                        }
                        $objArr[$val['objType']]->dealAfterPay_d(
                            array(
                                array(
                                    'id' => $val['expand1'],
                                    'money' => $val['money'],
                                    'payType' => 1
                                )
                            )
                        );
                    }
                }
            }

            //下推处理
            if (isset($pushDown)) {
                if ($this->checkIsRefundAll_d($object['belongId'])) {
                    //付款单据状态改变
                    $this->pushDown_d($object['belongId']);

                    //获取申请单id
                    $orgObj = $this->find(array('id' => $object['belongId']), null, 'payApplyId, payApplyNo');
                    if ($orgObj['payApplyId']) {
                        //付款申请单据状态改变
                        $payablesapplyDao = isset($payablesapplyDao) ? $payablesapplyDao : new model_finance_payablesapply_payablesapply();
                        $payablesapplyDao->applyRefund_d($orgObj['payApplyId']);
                    }
                }
            }

            if (isset($emailArr)) {
                if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                    if ($object['formType'] == 'CWYF-03') {
                        // 邮件内容
                        $mailInfo = Array('id' => $object['id'],
                            'mailUser' => $emailArr['TO_NAME'], 'formName' => $this->rtFormName($object['formType']),
                            'formNo' => $object['formNo'], 'supplierName' => $object['supplierName'],
                            'amount' => $object['amount'], 'payApplyNo' => '', 'payApplyId' => '',
                            'refundReason' => $emailArr['REFUNDREASON']);
                        // 下推退款时，获取源单的付款申请信息
                        if ($object['belongId'] && isset($orgObj)) {
                            $mailInfo['payApplyNo'] = $orgObj['payApplyNo'];
                            $mailInfo['payApplyId'] = $orgObj['payApplyId'];
                        }

                        $this->mailDeal_d('refundMail', $emailArr['TO_ID'], $mailInfo, $emailArr['ADDNAMES']);
                    } else {
                        $this->mailDeal_d('payMail', $emailArr['TO_ID'], Array('id' => $object['id'],
                            'mailUser' => $emailArr['TO_NAME'], 'formName' => $this->rtFormName($object['formType']),
                            'formNo' => $object['formNo'], 'supplierName' => $object['supplierName'],
                            'amount' => $object['amount'], 'payApplyNo' => $object['payApplyNo'],
                            'payApplyId' => $object['payApplyId']), $emailArr['ADDNAMES']);
                    }
                }
            }
            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 付款申请批量生成付款信息
     * @param $object
     * @param bool $isEntrust
     * @return bool
     */
    function addInGroup_d($object, $isEntrust = false) {
        $codeRuleDao = new model_common_codeRule();
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $payablesDetailDao = new model_finance_payables_detail();

        //邮件缓存
        $emailArr = array();
        //失败数组缓存
        $failureArr = array();

        foreach ($object as $val) {
            //邮件发送字符串
            $applyMailDetail = null;
            //邮件数组
            $mailDetail = null;

            try {
                $this->start_d();

                //转移从表信息
                $detail = $val['detail'];
                unset($val['detail']);

                //转移邮件信息
                if (isset($val['email'])) {
                    $mailDetail = $val['email'];
                    unset($val['email']);
                }

                //自动产生到款号
                if ($val['formType'] == 'CWYF-01') {
                    $val['formNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'FKJL');
                } else if ($val['formType'] == 'CWYF-02') {
                    $val['formNo'] = $codeRuleDao->financeCode($this->tbl_name . '_k', 'DL', 'FK');
                } else {
                    $val['formNo'] = $codeRuleDao->financeCode($this->tbl_name . '_t', 'DL', 'FT');
                }

                $newId = parent:: add_d($val, true);

                //更新单据付款状态
                if ($val['payApplyNo']) {
                    $updateArr = array('status' => 'FKSQD-03', 'payedMoney' => $val['amount'], 'actPayDate' => day_date);
                    $updateArr = $this->addUpdateInfo($updateArr);
                    $payablesapplyDao->update(array('id' => $val['payApplyId']), $updateArr);
                }

                //创建付款从表信息
                if (!empty($detail)) {
                    $payablesDetailDao->createBatch($detail, array('advancesId' => $newId));
                }

                /**
                 * 付款完成后关闭订单
                 */
                if (!empty($detail)) {
                    $objArr = array();
                    foreach ($detail as $innerVal) {
                        if ($innerVal['objType'] == 'YFRK-01' && !empty($innerVal['objId']) && $innerVal['objId'] != 0) {
                            if (empty($objArr[$val['objType']])) {
                                $objArr[$val['objType']] = new model_purchase_contract_purchasecontract();
                            }
                            $objArr[$val['objType']]->end_d($innerVal['objId']);
                        }
                        //付款处理 反写其他合同内容
                        if ($innerVal['objType'] == 'YFRK-02' && !empty($innerVal['objId']) && $innerVal['objId'] != 0) {
                            if (empty($objArr[$val['objType']])) {
                                $objArr[$val['objType']] = new model_contract_other_other();
                            }
                            $objArr[$val['objType']]->end_d($innerVal['objId']);
                        }
                        //付款处理 反写租车合同
                        if ($val['objType'] == 'YFRK-06' && !empty($val['objId']) && $val['objId'] != 0) {
                            if (empty($objArr[$val['objType']])) {
                                $objArr[$val['objType']] = new model_outsourcing_vehicle_register();
                            }
                            $objArr[$val['objType']]->dealAfterPay_d(
                                array(
                                    array(
                                        'id' => $val['expend1'],
                                        'money' => $val['money'],
                                        'payType' => 1
                                    )
                                )
                            );
                        }
                    }
                }

                $this->commit_d();

                // 如果是线下付款，则通知到特定人员
                if (!$isEntrust) {
                    //如果新增成功,构建邮件信息
                    if ($val['payApplyNo']) {
                        if ($val['sourceType'] == 'YFRK-01') {
                            $mailDetail['applyMailDetail'] = $payablesapplyDao->getMailDetail_d($val['payApplyId']);
                        }
                    }

                    //获取需要的主表信息
                    $mailDetail['formNo'] = $val['formNo'];
                    $mailDetail['supplierName'] = $val['supplierName'];
                    $mailDetail['amount'] = $val['amount'];
                    $mailDetail['payApplyNo'] = $val['payApplyNo'];
                    $mailDetail['payApplyId'] = $val['payApplyId'];
                    $mailDetail['formType'] = $val['formType'];
                    $mailDetail['detail'] = $detail;
                    //成功后缓存到邮件数组
                    if (isset($emailArr[$mailDetail['TO_ID']])) {
                        array_push($emailArr[$mailDetail['TO_ID']], $mailDetail);
                    } else {
                        $emailArr[$mailDetail['TO_ID']][0] = $mailDetail;
                    }
                } else {
                    $this->mailDeal_d("payApplyEntrust", "", array('id' => $val['payApplyId']));
                }
            } catch (Exception $e) {
                $this->rollBack();

                //如果存在导入失败,则缓存如失败数组中
                array_push($failureArr, $val);
            }
        }

        //发送邮件 ,当操作为提交时才发送
        if (!empty($emailArr) && !$isEntrust) {
            $this->thisMailBatch_d($emailArr);
        }
        return true;
    }

    /**
     * 邮件发送 - 以个人为单位发送邮件
     * @param $emailArr
     */
    function thisMailBatch_d($emailArr) {
        $emailDao = new model_common_mail();

        foreach ($emailArr as $val) {
            $str = '您好！<br/>';
            $str .= $_SESSION['USERNAME'] . "录入了以下记录:<br/><br/>";
            $TO_ID = null;
            $i = 0;
            $title = null;

            //循环,根据人名发送邮件
            foreach ($val as $v) {
                $i++;
                $formName = $this->rtFormName($v['formType']);
                $str .= "<font color='blue'>[ " . $formName . " ]</font>:" . $v['formNo'] . "<br/>";

                if ($v['formType'] == 'CWYF-03') {
                    $title = '退款信息';
                    $str .= '详细信息如下 ： <br/> 退款单位：' . $v['supplierName'] . ' , 退款金额： ' . $v['amount'] . ' , 申请单号为:' . $v['payApplyNo'] . ' , 申请单id为:' . $v['payApplyId'] . '<br/>';
                } else {
                    $title = '付款信息';
                    $str .= '详细信息如下 ： <br/> 付款单位：' . $v['supplierName'] . ' , 付款金额： ' . $v['amount'] . ' , 申请单号为:' . $v['payApplyNo'] . ' , 申请单id为:' . $v['payApplyId'] . '<br/>';
                }

                if (empty($TO_ID)) {
                    $TO_ID = $v['TO_ID'];
                }

                if (empty($v['applyMailDetail'])) {
                    $str .= $this->thisMailDetail_d($v['detail']);
                } else {
                    $str .= $v['applyMailDetail'];
                }

                $str .= "<br/>";
            }
            $emailDao->mailClear($title . '(' . $i . ')', $TO_ID, $str);
        }
    }

    /**
     * 重写get_d
     * @param $id
     * @param string $getType
     * @param bool $isInit
     * @return bool|mixed
     */
    function get_d($id, $getType = 'main', $isInit = false) {
        $rs = parent::get_d($id);

        //带从表
        if ($getType != 'main') {
            $payablesDetailDao = new model_finance_payables_detail();
            $rs['detail'] = $payablesDetailDao->getDetail($id);
            if ($isInit == 'view') {
                $rs['detail'] = $payablesDetailDao->initView($rs['detail']);
            } else if ($isInit == 'edit') {
                $rs['detail'] = $payablesDetailDao->initEdit($rs['detail']);
            }
        }
        return $rs;
    }

    /**
     * 获取剩余可下推金额
     * @param $id
     * @return bool|mixed
     */
    function getCanRefund_d($id) {
        $rs = parent::get_d($id);
        //获取从表信息
        $payablesDetailDao = new model_finance_payables_detail();
        $rs['detail'] = $payablesDetailDao->getCanRefundDetailGE($id);
        $rs['detail'] = $payablesDetailDao->initRefund($rs['detail']);
        return $rs;
    }

    /**
     * 重写edit_d
     * @param $object
     * @param ipayables $strategy
     * @return bool
     */
    function edit_d($object, ipayables $strategy) {
        try {
            $this->start_d();

            $detail = $object['detail'];
            unset($object['detail']);

            if (empty($object['payApplyId'])) {
                unset($object['payApplyId']);
            }
            // 本位币金额默认等于单据金额
            if(empty($object['amountCur'])){
            	$object['amountCur'] = $object['amount'];
            }
            parent:: edit_d($object, true);


            $payablesDetailDao = new model_finance_payables_detail();
            $payablesDetailDao->deleteDetail($object['id']);
            if (!empty($detail)) {
                $payablesDetailDao->createBatch($detail, array('advancesId' => $object['id']));
            }

            if (isset($object['payApplyId'])) {
                $payablesapply = new model_finance_payablesapply_payablesapply();
                $payablesapply->updateStatusByPayedMoney_d($object['payApplyId']);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 邮件发送
     * @param $emailArr
     * @param $object
     * @param $detail
     * @param null $applyMailDetail
     */
    function thisMail_d($emailArr, $object, $detail, $applyMailDetail = null) {
        $formName = $this->rtFormName($object['formType']);
        $str = '您好！<br/>';
        $str .= $_SESSION['USERNAME'] . " 录入了<font color='blue'>[ " . $formName . " ]</font>:" . $object['formNo'] . "<br/>";

        if ($object['formType'] == 'CWYF-03') {
            $title = '退款信息';
            $str .= '单据内容为 ： <br/> 退款单位：' . $object['supplierName'] . ' , 退款金额： ' . $object['amount'] . ' , 退款申请单号为:' . $object['payApplyNo'] . ' , 退款申请单id号为:' . $object['payApplyId'] . '<br/>';
        } else {
            $title = '付款信息';
            $str .= '单据内容为 ： <br/> 付款单位：' . $object['supplierName'] . ' , 付款金额： ' . $object['amount'] . ' , 付款申请单号为:' . $object['payApplyNo'] . ' , 付款申请单id号为:' . $object['payApplyId'] . '<br/>';
        }
        if (empty($applyMailDetail)) {
            $str .= $this->thisMailDetail_d($detail);
        } else {
            $str .= $applyMailDetail;
        }
        $str .= '退款原因： <br/>' . $emailArr['REFUNDREASON'];
        $emailDao = new model_common_mail();
        $emailDao->mailClear($title, $emailArr['TO_ID'], $str);
    }

    /**
     * 邮件从表表格设置
     * @param $object
     * @return string
     */
    function thisMailDetail_d($object) {
        $str = "无详细信息.<br/>";
        if (is_array($object)) {
            $i = 0;
            $datadictDao = new model_system_datadict_datadict();
            $str = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>源单类型</b></td><td><b>源单编号</b></td><td><b>申请金额</b></td></tr>";
            foreach ($object as $key => $val) {
                $i++;
                if (!empty($val['objCode'])) {
                    $objTypeCN = $datadictDao->getDataNameByCode($val['objType']);
                } else {
                    $objTypeCN = "";
                }
                $str .= <<<EOT
					<tr align="center" ><td>$i</td><td>$objTypeCN</td><td>$val[objCode]</td><td>$val[money]</td></tr>
EOT;
            }
            $str .= "</table>";
        }

        return $str;
    }

    /**
     * 正常
     * @param $id
     * @return mixed
     */
    function normal_d($id) {
        return parent::edit_d(array('id' => $id, 'status' => '0'), true);
    }

    /**
     * 退款
     * @param $id
     * @return mixed
     */
    function pushDown_d($id) {
        return parent::edit_d(array('id' => $id, 'status' => '1'), true);
    }

    /*************************付款申请部分*************************/
    /**
     * 针对付款申请的删除
     * @param $ids
     * @return int|string
     * @throws Exception
     */
    function delForApply_d($ids) {
        $idArr = explode(',', $ids);
        $formNoArr = array();
        try {
            $this->start_d();
            foreach ($idArr as $key => $val) {
                $rs = $this->isPayApply_d($val);
                if ($rs['status'] == 1) {
                    array_push($formNoArr, $rs['formNo']);
                    continue;
                }
                if ($applyId = $rs['payApplyId']) {
                    if (!isset($payablesapplyDao)) {
                        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
                    }
                    $this->deletes($val);
                    $payablesapplyDao->updateStatusByPayedMoney_d($applyId);
                } else {
                    $this->deletes($val);
                }
                if (!empty($rs['belongId'])) {
                    //将付款单状态还原
                    $this->normal_d($rs['belongId']);

                    $orgObj = $this->find(array('id' => $rs['belongId']), null, 'payApplyId');
                    if ($orgObj['payApplyId']) {
                        //更新付款申请状态
                        if (!isset($payablesapplyDao)) {
                            $payablesapplyDao = new model_finance_payablesapply_payablesapply();
                        }
                        $payablesapplyDao->applyDelRefund_d($orgObj['payApplyId']);
                    }
                }
            }
            $this->commit_d();
            if (!empty($formNoArr)) {
                return implode($formNoArr, ',');
            } else {
                return 1;
            }
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 判断当前付款单是否有付款申请
     * @param $id
     * @return bool|mixed
     */
    function isPayApply_d($id) {
        return $this->find(array('id' => $id), null, 'payApplyId,belongId,formNo,status');
    }

    /**
     * 根据采购订单id获取已付款金额
     * @param $objId
     * @param $objType
     * @return int
     */
    function getPayedMoneyByPur_d($objId, $objType) {
        $this->setCompany(0);//不启用公司过滤
        $this->searchArr['dObjId'] = $objId;
        $this->searchArr['dObjType'] = $objType;
        $this->groupBy = 'd.objId,d.objType';
        $this->sort = '';
        $rows = $this->list_d('sum_list');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * 根据采购订单id获取已付款录
     * @param $objId
     * @param $objType
     * @return mixed
     */
    function getPayedByPur_d($objId, $objType) {
        $this->setCompany(0);//不启用公司过滤
        $this->searchArr['dObjId'] = $objId;
        $this->searchArr['dObjType'] = $objType;
        $this->groupBy = 'c.id';
        return $this->list_d('select_historyNew');
    }

    /**
     * 根据付款申请号获取申请信息
     * @param null $ids
     * @return array
     */
    function getPayapply_d($ids = null) {
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $rows = $payablesapplyDao->getApplyAndDetail_d(array('ids' => $ids));
        $initRows = $payablesapplyDao->initAddIngroup_d($rows);
        return array('detail' => $initRows[0], 'rowCount' => $initRows[1]);
    }

    /**
     * 根据付款申请号获取申请信息 -- 确认付款时，只取未付款的单据
     * @param null $ids
     * @return array
     */
    function getPayapplyOneKey_d($ids = null) {
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $rows = $payablesapplyDao->getApplyAndDetailNew_d(array('ids' => $ids, 'status' => 'FKSQD-01'));
        return $payablesapplyDao->initAddIngroupNew_d($rows);
    }

    /**
     * 检测付款单是否已经完全下推
     * @param $id
     * @return bool
     */
    function checkIsRefundAll_d($id) {
        $rs = $this->get_d($id);

        $this->searchArr['belongId'] = $id;
        $belongArr = $this->list_d('sum_money');

        if (is_array($belongArr)) {
            if ($rs['amount'] <= $belongArr[0]['amount']) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * 查询付款申请的信息
     * @param $payApplyId
     * @return array|bool|mixed
     */
    function getPayapplyExaInfo_d($payApplyId) {
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $payapplyObj = $payablesapplyDao->find(array('id' => $payApplyId), null, 'exaId,exaCode');
        if ($payapplyObj['exaCode']) {
            return $payapplyObj;
        } else {
            return array(
                'exaId' => $payApplyId,
                'exaCode' => $payablesapplyDao->tbl_name
            );
        }
    }
}