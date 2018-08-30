<?php

/**
 * @author Show
 * @Date 2011年5月8日 星期日 13:55:05
 * @version 1.0
 * @description:付款申请(新) Model层
 */
class model_finance_payablesapply_payablesapply extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_payablesapply";
        $this->sql_map = "finance/payablesapply/payablesapplySql.php";
        parent::__construct();
    }

    /********************新策略部分使用************************/

    //公司权限处理 TODO
    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

    //不同类型入库申请策略类,根据需要在这里进行追加
    private $relatedStrategyArr = array(
        'YFRK-01' => 'model_finance_payablesapply_strategy_purcontract', //采购订单
        'YFRK-02' => 'model_finance_payablesapply_strategy_other', //销售订单
        'YFRK-03' => 'model_finance_payablesapply_strategy_outsourcing', //服务合同
        'YFRK-04' => 'model_finance_payablesapply_strategy_none', //无源单类
        'YFRK-05' => 'model_finance_payablesapply_strategy_outaccount',//外包结算单
        'YFRK-06' => 'model_finance_payablesapply_strategy_rentcar'//租车合同
    );

    /**
     * 根据数据类型返回类
     * @param $objType
     * @return null
     */
    public function getClass($objType) {
        return isset($this->relatedStrategyArr[$objType]) ? $this->relatedStrategyArr[$objType] : null;
    }

    //对应业务代码
    private $relatedCode = array(
        'YFRK-01' => 'purcontract',
        'YFRK-02' => 'other',
        'YFRK-03' => 'outsourcing',
        'YFRK-04' => 'none',
        'YFRK-05' => 'outaccount',
        'YFRK-06' => 'rentcar'
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
     * 获取数据信息 - 查看修改时使用
     * @param $obj
     * @param ipayablesapply $strategy
     * @return mixed
     */
    public function getObjInfo_d($obj, ipayablesapply $strategy) {
        return $strategy->getObjInfo_d($obj);
    }

    /**
     * 返回是否
     * @param $thisVal
     * @return string
     */
    function rtYesNo_d($thisVal) {
        if ($thisVal == 1) {
            return '是';
        } else {
            return '否';
        }
    }

    /**
     * 渲染新增页面
     * @param $object
     * @param ipayablesapply $strategy
     * @param $payFor
     * @return mixed
     */
    function initAdd_d($object, ipayablesapply $strategy, $payFor) {
        return $payFor == 'FKLX-03' ? $strategy->initAddRefund_d($object, $this) : $strategy->initAdd_d($object, $this);
    }

    /**
     * 渲染新增页面
     * @param $object
     * @param ipayablesapply $strategy
     * @param $payFor
     * @return mixed
     */
    function initAddOne_d($object, ipayablesapply $strategy, $payFor) {
        return $payFor == 'FKLX-03' ? $strategy->initAddOneRefund_d($object, $this) :
            $strategy->initAddOne_d($object, $this);
    }

    /**
     * @param $object
     * @param ipayablesapply $strategy
     * @param $payFor
     * @return mixed
     */
    function initEdit_d($object, ipayablesapply $strategy, $payFor) {
        return $payFor == 'FKLX-03' ? $strategy->initEditRefund_d($object['detail'], $this) :
            $strategy->initEdit_d($object['detail'], $this);
    }

    /**
     * 加载查看页面详细
     * @param $object
     * @param ipayablesapply $strategy
     * @param $payFor
     * @return mixed
     */
    function initView_d($object, ipayablesapply $strategy, $payFor) {
        //获取内容
        $object['detail'] = $payFor == 'FKLX-03' ? $strategy->initViewRefund_d($object['detail']) :
            $strategy->initView_d($object['detail']);

        return $object;
    }

    /**
     * 加载审核查看页面详细
     */
    function initAudit_d($object, ipayablesapply $strategy, $payFor) {
        //增加附属信息
        $object['addInfo'] = $strategy->initAddInfo_d($object['detail']);
        //获取内容
        $object['detail'] = $payFor == 'FKLX-03' ? $strategy->initAuditRefund_d($object['detail']) :
            $strategy->initAudit_d($object['detail']);

        return $object;
    }

    /**
     * 加载页面打印格式
     * @param $object
     * @param ipayablesapply $strategy
     * @return mixed
     */
    function initPrint_d($object, ipayablesapply $strategy) {
        //获取内容
        $printRs = $strategy->initPrint_d($object['detail']);
        if (isset($printRs['sourceCode'])) {
            $object['detail'] = $printRs['detail'];
            $object['sourceCode'] = $printRs['sourceCode'];
        } else {
            $object['detail'] = $strategy->initPrint_d($object['detail']);
        }
        return $object;
    }

    /**
     * 获取付款申请信息 用于下推
     * @param $id
     * @param bool $isInit
     * @return bool|mixed
     */
    function getForPushForDetail_d($id, $isInit = true) {
        $rs = parent::get_d($id);
        //		print_r($rs);

        //调用策略
        $newClass = $this->getClass($rs['sourceType']);
        $initObj = new $newClass();
        $initObjGruop = $initObj->groupByCodeForPush;

        $applyDetailDao = new model_finance_payablesapply_detail();
        $applyDetailDao->searchArr = array('payapplyId' => $id);

        if (empty($initObjGruop)) {
            $applyDetailDao->groupBy = 'c.payapplyId,c.objId,c.objType';
        } else {
            $applyDetailDao->groupBy = $initObjGruop;
        }
        $rs['detail'] = $applyDetailDao->listBySqlId('select_push');

        if ($isInit == true) {
            $detail = $initObj->initPayablesAdd_i($rs['detail']);
            $rs['detail'] = $detail[0];
            $rs['coutNumb'] = $detail[1];
        }

        return $rs;
    }

    /********************新策略部分使用************************/

    /******************** 付款单付款类型 **********************/
    // 付款类型
    public $payForArr = array(
        'FKLX-01' => '',
        'FKLX-02' => '',
        'FKLX-03' => 'back'
    );

    // 邮件类型配置
    public $mailTypeArr = array(
        'YFRK-01' => 'payablesapply_close',
        'YFRK-02' => 'payablesapply_outsourcing_close',
        'YFRK-03' => 'payablesapply_other_close',
        'YFRK-04' => 'payablesapply_none_close',
        'handUpPay' => 'payablesapply_handUpPay'
    );

    /**
     * @param $value
     * @return mixed
     */
    function getMailType_d($value) {
        if (isset($this->mailTypeArr[$value])) {
            return $this->mailTypeArr[$value];
        } else {
            return $value;
        }
    }

    /******************** 付款单付款类型 **********************/

    /**
     * 重写add_d
     * @param $object
     * @return bool
     */
    function add_d($object) {
        $codeRuleDao = new model_common_codeRule();
        try {
            $this->start_d();

            $detail = $object['detail'];
            unset($object['detail']);

            // 重新计算 payMoney 防止前端统计失效后传入0的情况
            $payMoneyNew = 0;
            if (!empty($detail)) {
                foreach ($detail as $val){
                    $payMoneyNew = round(bcadd($payMoneyNew,$val['money'],5),2);
                }
                if($payMoneyNew > 0 && $object['payMoney'] != $payMoneyNew){
                    $object['payMoney'] = $payMoneyNew;
                }
            }

            //自动产生到款号
            $object['formNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'FKSQ');

            //状态位 - 如果是付款，则不需要再进行财务支付操作
            if ($object['payFor'] == 'FKLX-03') {
                $object['status'] = 'FKSQD-01';
            } else {
                $object['status'] = 'FKSQD-00';
            }
            //审批状态
            $object['ExaStatus'] = WAITAUDIT;
            // 本位币金额 = 付款金额 * 汇率
            $object['payMoneyCur'] = $object['payMoney'] * $object['rate'];

            $newId = parent:: add_d($object, true);

            if (!empty($detail)) {
                $applyDetailDao = new model_finance_payablesapply_detail();
                $applyDetailDao->createBatch($detail, array('payapplyId' => $newId));
            } else {
                $applyDetailDao = new model_finance_payablesapply_detail();
                $detail = array(
                    array('money' => $object['payMoney'], 'objType' => $object['sourceType'])
                );
                $applyDetailDao->createBatch($detail, array('payapplyId' => $newId));
            }

            //更新附件关联关系
            $this->updateObjWithFile($newId, $object['formNo']);

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 新增付款申请 － 无其余设置 - 用于其他合同和外包合同单独申请付款
     * @param $object
     * @return bool
     */
    function addOnly_d($object) {
        $codeRuleDao = new model_common_codeRule();

        //判断是否已经生成了付款申请单，如果已存在，则直接返回
        $this->searchArr = array('exaCode' => $object['exaCode'], 'exaId' => $object['exaId']);
        $rs = $this->list_d();
        if ($rs) {
            return true;
        }

        try {
            $this->start_d();

            $detail = $object['detail'];
            unset($object['detail']);

            //自动产生到款号
            $object['formNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'FKSQ');
            //状态位
            $object['status'] = isset($object['status']) ? $object['status'] : 'FKSQD-00';

            //特别处理 -- 如果单据没有传入公司信息,那么将获取salesmanId的公司信息
            if (!$object['businessBelong']) {
                $userDao = new model_deptuser_user_user();
                $userInfo = $userDao->getUserById($object['salesmanId']);
                $object['businessBelong'] = $object['formBelong'] = $userInfo['Company'];
                $object['businessBelongName'] = $object['formBelongName'] = $userInfo['CompanyName'];
            }

            $newId = parent:: add_d($object);

            if (!empty($detail)) {
                $applyDetailDao = new model_finance_payablesapply_detail();
                $applyDetailDao->createBatch($detail, array('payapplyId' => $newId));
            }

            $this->commit_d();

            //如果是委托付款，则不发送邮件
            if (!$object['isEntrust']) {
                //邮件发送
                $content = "你好，付款申请审批 id: " . $newId . ",申请单号: " . $object['formNo'] . " 已经完成审批，<br><span style='color:red'>请将付款申请提交到财务进行支付。</span>";

                $emailDao = new model_common_mail();
                $emailDao->mailClear('付款申请审批 id:' . $newId, $object['salesmanId'], $content);
            }

            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * @param $id
     * @param string $getType
     * @param bool $isInit
     * @return bool|mixed
     */
    function get_d($id, $getType = 'main', $isInit = false) {
        $rs = parent::get_d($id);

        //带从表
        if ($getType != 'main') {
            $applyDetailDao = new model_finance_payablesapply_detail();
            $rs['detail'] = $applyDetailDao->getDetail($id);

            if ($isInit == 'view') {
                $sconfigDao = new model_common_securityUtil ('purchasecontract');
                $rs['detail'] = $sconfigDao->md5Rows($rs['detail'], 'objId');
                $rs['detail'] = $applyDetailDao->initView($rs['detail'], $rs);
            } else if ($isInit == 'edit') {
                $rs['detail'] = $applyDetailDao->initEdit($rs['detail']);
            }
        }

        return $rs;
    }

    /**
     * 重写getAuditing
     * @param $id
     * @return bool|mixed
     */
    function getAuditing_d($id) {
        $rs = parent::get_d($id);

        $applyDetailDao = new model_finance_payablesapply_detail();
        $rs['detail'] = $applyDetailDao->getDetail($id);

        $sconfigDao = new model_common_securityUtil ('purchasecontract');
        $rs['detail'] = $sconfigDao->md5Rows($rs['detail'], 'objId');
        $rs['detail'] = $applyDetailDao->initAuditing($rs['detail'], $rs);

        return $rs;
    }

    /**
     * 获取付款申请信息 用于下推
     * @param $id
     * @return bool|mixed
     */
    function getForPush_d($id) {
        $rs = parent::get_d($id);
        $applyDetailDao = new model_finance_payablesapply_detail();
        $applyDetailDao->searchArr = array('payapplyId' => $id);
        $applyDetailDao->groupBy = 'c.payapplyId,c.objId,c.objType';
        $rs['detail'] = $applyDetailDao->listBySqlId('select_push');

        return $rs;
    }


    /**
     * 根据付款申请id自动修改状态
     * @param null $id
     * @return null
     */
    function updateStatusByPayedMoney_d($id = null) {
        if (empty($id)) {
            return null;
        } else {
            $this->searchArr['id'] = $id;
            $this->sort = 'c.id';
            $this->groupBy = 'c.id';
            $rs = $this->listBySqlId('sum_payedmoney');
            $object = array();
            $object['id'] = $id;
            $object['actPayDate'] = '0000-00-00';
            $object['payedMoney'] = $rs[0]['thisPayedMoney'];
            if ($rs[0]['thisPayedMoney'] == 0 || $rs[0]['thisPayedMoney'] == null) {//为0时修改状态为未付款
                $object['status'] = 'FKSQD-01';
            } else if ($rs[0]['payMoney'] > $rs[0]['thisPayedMoney']) {//付款金额小于需付金额时改为部分付款
                $object['status'] = 'FKSQD-02';
            } else {//超出或者等于为已付款
                $object['status'] = 'FKSQD-03';
            }
            return parent::edit_d($object, true);
        }
    }

    /**
     * @param $object
     * @return bool
     */
    function edit_d($object) {
        try {
            $this->start_d();

            $detail = $object['detail'];
            unset($object['detail']);

            // 重新计算 payMoney 防止前端统计失效后传入0的情况
            $payMoneyNew = 0;
            if (!empty($detail)) {
                foreach ($detail as $val){
                    $payMoneyNew = round(bcadd($payMoneyNew,$val['money'],5),2);
                }
                if($payMoneyNew > 0 && $object['payMoney'] != $payMoneyNew){
                    $object['payMoney'] = $payMoneyNew;
                }
            }

            // 本位币金额 = 付款金额 * 汇率
            $object['payMoneyCur'] = $object['payMoney'] * $object['rate'];

            parent:: edit_d($object, true);

            $applyDetailDao = new model_finance_payablesapply_detail();
            $applyDetailDao->deleteDetail($object['id']);
            if (!empty($detail)) {
                $applyDetailDao->createBatch($detail, array('payapplyId' => $object['id']));
            }

            $this->commit_d();
            return $object['id'];
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 关闭付款申请单方法
     * @param $object
     * @return bool
     */
    function close_d($object) {
        $mail = $object['mail'];
        unset($object['mail']);

        try {
            $this->start_d();

            //关闭付款申请
            $rs = parent::edit_d($object);

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }

        //发送邮件 ,当操作为提交时才发送
        if (isset($mail) && $rs) {
            if ($mail['issend'] == 'y' && !empty($mail['TO_ID'])) {
                $this->thisMailClose_d($mail, $object);
            }
        }

        return $rs;
    }

    /**
     * 批量关闭付款申请单方法
     * @param $object
     * @return mixed
     */
    function batchClose_d($object) {
        $idStr = util_jsonUtil::strBuild($object['ids']);
        $sql = "
			UPDATE " . $this->tbl_name . "
			SET closeDate = '" . $object['closeDate'] . "', closeUser = '" . $object['closeUser'] .
            "', closeUserId = '" . $object['closeUserId'] .
            "',closeReason = '" . $object['closeReason'] .
            "',STATUS = '" . $object['status'] .
            "' WHERE id IN (" . $idStr . ") ";
        return $this->query($sql);
    }

    /**
     * 发送关闭邮件通知
     * @param $emailArr
     * @param $object
     */
    function thisMailClose_d($emailArr, $object) {
        $content = $object['closeUser'] . '已经关闭付款申请  ' . $object['formNo'] .
            ',申请详细信息为：<br/> id : ' . $object['id'] .
            ',<br/>供应商 ：' . $object['supplierName'] . ',<br/>申请金额：' . $object['payMoney'] .
            ',<br/>关闭人：' . $object['closeUser'] .
            ',<br/>关闭日期：' . $object['closeDate'] .
            ',<br/>关闭原因：' . $object['closeReason'];
        $emailDao = new model_common_mail();
        $emailDao->mailClear('关闭付款申请', $emailArr['TO_ID'], $content);
    }

    /**
     * 获取默认邮件发送人
     * @param string $key
     * @return array
     */
    function getSendMen_d($key = 'payablesapply_close') {
        include(WEB_TOR . "model/common/mailConfig.php");
        $mailArr = isset($mailUser[$key][0]) ? $mailUser[$key][0] : array('sendUserId' => '',
            'sendName' => '');
        return $mailArr;
    }

    /*******************外部信息获取********************/
    /**
     * 根据采购合同Id获取相关信息方法
     * @param $purAppId
     * @return bool|mixed
     */
    function getContractinfoById_d($purAppId) {
        //获取订单信息
        $purchasecontract = new model_purchase_contract_purchasecontract();
        $row = $purchasecontract->find(array('id' => $purAppId), null, 'id,suppName,suppId,objCode,suppAddress,createId,createName,hwapplyNumb,paymentCondition,payRatio,allMoney,suppBankName,suppAccount');

        //获取已申请付款金额
        //获取已付款金额
        $payablesapplyMoney = $this->getApplyMoneyByPur_d($purAppId, 'YFRK-01');
        $row['payablesapplyMoney'] = $payablesapplyMoney;

        //获取已付款金额
        $payablesDao = new model_finance_payables_payables();
        $payedMoney = $payablesDao->getPayedMoneyByPur_d($purAppId, 'YFRK-01');
        $row['payedMoney'] = $payedMoney;

        //获取已受发票金额
        $invpurchasaeDao = new model_finance_invpurchase_invpurchase();
        $handInvoiceMoney = $invpurchasaeDao->getInvMoneyByPur_d($purAppId);
        $row['handInvoiceMoney'] = $handInvoiceMoney;

        //获取当前登录人部门
        $otherDataDao = new model_common_otherdatas();
        $deptRows = $otherDataDao->getUserDatas($row['createId'], array('DEPT_NAME', 'DEPT_ID'));
        $row['deptName'] = $deptRows['DEPT_NAME'];
        $row['deptId'] = $deptRows['DEPT_ID'];

        return $row;
    }

    /**
     * 状态付款申请从表
     * @param $rs
     * @return array
     */
    function initPayApplyDetail_d($rs) {
        $str = ""; //返回的模板字符串
        $i = 0; //列表记录序号
        if ($rs) {
            $datadictArr = $this->getDatadicts('YFRK');
            foreach ($rs as $key => $val) {
                $i++;
                $objTypeArr = $this->getDatadictsStr($datadictArr ['YFRK'], $val ['objType']);
                $canApply = bcsub($val['allMoney'], $val['payablesapplyMoney'], 2);
                $str .= <<<EOT
                    <tr><td>$i</td>
                        <td>
                            <select class="selectmiddel" id="objTypeList$i" value="$val[objType]" name="payablesapply[detail][$i][objType]">
                                $objTypeArr
                            </select>
                        </td>
                        <td>
                            <input type="text" class="readOnlyTxtNormal" readonly="readonly" id="objCode$i" value="$val[hwapplyNumb]" name="payablesapply[detail][$i][objCode]"/>
                            <input type="hidden" id="objType$i" value="YFRK-01" name="payablesapply[detail][$i][objType]"/>
                            <input type="hidden" id="objId$i" value="$val[id]" name="payablesapply[detail][$i][objId]"/>
                        </td>
                        <td>
                            <input type="text" class="txtmiddle formatMoney" id="money$i" value="$canApply" name="payablesapply[detail][$i][money]" onblur="checkMax($i);countAll()"/>
                            <input type="hidden" id="oldMoney$i" value="$canApply"/>
                        </td>
                        <td>
                            <input type="text" class="readOnlyTxt formatMoney" readonly='readonly' value="$val[allMoney]" name="payablesapply[detail][$i][purchaseMoney]"/>
                        </td>
                        <td>
                            <input type="text" class="readOnlyTxt formatMoney" readonly='readonly' value="$val[payedMoney]" name="payablesapply[detail][$i][payedMoney]"/>
                        </td>
                        <td>
                            <input type="text" class="readOnlyTxt formatMoney" readonly='readonly' value="$val[handInvoiceMoney]" name="payablesapply[detail][$i][handInvoiceMoney]"/>
                        </td>
                    </tr>
EOT;
            }
        }
        return array(
            $str,
            $i
        );
    }

    /**
     * 根据采购订单id获取已申请付款金额
     * @param $objId
     * @param string $objType
     * @return int
     */
    function getApplyMoneyByPur_d($objId, $objType = 'YFRK-01') {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'noExaStatus' => BACK,
            'noStatusArr' => 'FKSQD-04,FKSQD-05'
        );
        $this->setCompany(0);//不启用公司过滤
        $this->groupBy = 'd.objId,d.objType';
        $rows = $this->list_d('sum_list');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * 根据采购订单id获取已退款金额总和
     * @param $objId
     * @param string $objType
     * @param string $payFor
     * @return int
     */
    function getApplyBackMoney_d($objId, $objType = 'YFRK-01', $payFor = 'FKLX-03') {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'noExaStatus' => BACK,
            'payFor' => $payFor,
            'noStatusArr' => 'FKSQD-04,FKSQD-05'
        );
        $this->setCompany(0);//不启用公司过滤
        $this->groupBy = 'd.objId,d.objType';
        $rows = $this->list_d('sum_list');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * 根据采购订单id获取已申请付款金额 - 包含退款计算
     * @param $objId
     * @param string $objType
     * @return int
     */
    function getApplyMoneyByPurAll_d($objId, $objType = 'YFRK-01') {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'noExaStatus' => BACK,
            'noStatusArr' => 'FKSQD-04,FKSQD-05'
        );
        $this->setCompany(0);//不启用公司过滤
        $this->groupBy = 'd.objId,d.objType';
        $rows = $this->list_d('sum_listAll');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * 根据采购订单id获取已申请付款金额
     * @param $objId
     * @param string $objType
     * @param $productId
     * @return int
     */
    function getApplyMoneyByPurProduct_d($objId, $objType = 'YFRK-01', $productId) {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'dProductId' => $productId,
            'noExaStatus' => BACK,
            'noStatusArr' => 'FKSQD-04,FKSQD-05'
        );
        $this->setCompany(0);//不启用公司过滤
        $this->groupBy = 'd.objId,d.objType,d.productId';
        $rows = $this->list_d('sum_list');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * 根据采购订单id和清单id获取已申请付款金额
     * @param $objId
     * @param string $objType
     * @param $detailId
     * @return int
     */
    function getApplyMoneyByPurExpand1_d($objId, $objType = 'YFRK-01', $detailId) {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'dExpand1' => $detailId,
            'noExaStatus' => BACK,
            'noStatusArr' => 'FKSQD-04,FKSQD-05'
        );
        $this->setCompany(0);//不启用公司过滤
        $this->groupBy = 'd.objId,d.objType,d.expand1';
        $rows = $this->list_d('sum_list');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * 根据采购订单id和清单id获取已申请付款金额 - 包含退款
     * @param $objId
     * @param string $objType
     * @param $detailId
     * @return int
     */
    function getApplyMoneyByPurExpand1All_d($objId, $objType = 'YFRK-01', $detailId) {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'dExpand1' => $detailId,
            'noExaStatus' => BACK,
            'noStatusArr' => 'FKSQD-04,FKSQD-05'
        );
        $this->setCompany(0);//不启用公司过滤
        $this->groupBy = 'd.objId,d.objType,d.expand1';
        $rows = $this->list_d('sum_listAll');
        if (is_array($rows)) {
            return $rows[0]['payed'];
        } else {
            return 0;
        }
    }

    /**
     * 查看付款申请和付款申请详细
     * @param $object
     * @return mixed
     */
    function getApplyAndDetail_d($object) {
        $this->searchArr = $object;
        $this->groupBy = 'c.id,d.objId,d.objType';
        return $this->list_d('select_history');
    }

    /**
     * 获取是否已存在对应未处理的申请单
     * @param $objId
     * @param string $objType
     * @param string $formType
     * @return int
     */
    function isExistence_d($objId, $objType = 'YFRK-01', $formType = 'pay') {
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType,
            'noExaStatus' => BACK,
            'noStatusArr' => 'FKSQD-03,FKSQD-04,FKSQD-05'
        );
        if ($formType != 'pay') {
            $this->searchArr['payFor'] = 'FKLX-03';
        } else {
            $this->searchArr['noPayFor'] = 'FKLX-03';
        }
        $this->setCompany(0);//不启用公司过滤
        $rows = $this->list_d('select_detail');
        if (is_array($rows)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 判断付款申请是否有效  - 用于费用分摊部分
     * @param $id
     * @return bool
     */
    function isEffective_d($id) {
        $obj = $this->find(array('id' => $id), null, 'ExaStatus,status');
        if ($obj['ExaStatus'] == '完成' && ($obj['status'] != 'FKSQD-04' or $obj['status'] != 'FKSQD-05')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 更新付款申请的费用分摊金额和费用分摊状态
     * @param $id
     * @param int $shareMoney
     * @param string $updateKey
     * @return mixed
     */
    function updateShareInfo_d($id, $shareMoney = 0, $updateKey = 'id') {
        if ($shareMoney == 0) {
            $shareStatus = 0;
        } else {
            $rs = $this->find(array($updateKey => $id), null, 'payMoney');
            if ($rs['payMoney'] * 1 == $shareMoney * 1) {
                $shareStatus = 1;
            } else {
                $shareStatus = 2;
            }
        }
        return $this->update(array($updateKey => $id), array('shareStatus' => $shareStatus, 'shareMoney' => $shareMoney));
    }

    /**
     * 渲染付款申请
     * @param $rs
     * @return array
     */
    function initAddIngroup_d($rs) {
        $str = ""; //返回的模板字符串
        $i = 0; //列表记录序号
        if ($rs) {
            $j = 0; //次行
            $mark = null; //列表记录序号

            $sconfigDao = new model_common_securityUtil ('purchasecontract');
            $rs = $sconfigDao->md5Rows($rs, 'id');
            $salesman = $salesmanId = null;
            foreach ($rs as $key => $val) {
                $objCode = empty($val['objCode']) ? '无' : $val['objCode'];
                if ($val['id'] != $mark) {
                    $thisDay = day_date;

                    if ($mark != $val['id'] && !empty($mark)) {//渲染申请情况部分
                        $str .= <<<EOT
							<tr class="tr_odd">
								<td></td>
								<td class="innerTd">
									邮件通知：
								</td>
								<td class="form_text_right " colspan="7" class="innerTd">
									<input type="text" class="txt" id="mailman$i" name="payables[$i][email][TO_NAME]" value="$salesman"/>
									<input type="hidden" id="mailmanId$i" name="payables[$i][email][TO_ID]" value="$salesmanId"/>
									<input type="hidden" name="payables[$i][email][issend]" value="y"/>
								</td>
							</tr>
							<tr class="tr_count">
								<td colspan="9"></td>
							</tr>
EOT;
                        $salesmanId = $val['salesmanId'];
                        $salesman = $val['salesman'];
                    } else {
                        $salesmanId = $val['salesmanId'];
                        $salesman = $val['salesman'];
                    }
                    switch ($val['payFor']) {
                        case 'FKLX-01' :
                            $payFor = 'CWYF-01';
                            break;
                        case 'FKLX-02' :
                            $payFor = 'CWYF-02';
                            break;
                        case 'FKLX-03' :
                            $payFor = 'CWYF-03';
                            break;
                        default :
                            $payFor = '';
                            break;
                    }
                    $i++;
                    $str .= <<<EOT
						<tr>
							<td>
								$i
							</td>
							<td>
								$val[formNo]
								<input type="hidden" name="payables[$i][payApplyNo]" value="$val[formNo]"/>
								<input type="hidden" name="payables[$i][payApplyId]" value="$val[id]"/>
								<input type="hidden" name="payables[$i][bank]" value="$val[bank]"/>
								<input type="hidden" name="payables[$i][account]" value="$val[account]"/>
								<input type="hidden" name="payables[$i][payType]" value="$val[payType]"/>
								<input type="hidden" name="payables[$i][remark]" value="$val[remark]"/>
								<input type="hidden" name="payables[$i][formType]" value="$payFor"/>
								<img src="images/icon/view.gif" title="单据信息" onclick="showModalWin('?model=finance_payablesapply_payablesapply&action=init&perm=view&id=$val[id]&skey=$val[skey_]',1);" />
								<img src="images/icon/search.gif" title="审批情况" onclick="showThickboxWin('controller/common/readview.php?itemtype=oa_finance_payablesapply&pid=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');" />
							</td>
							<td>
								$val[deptName]
								<input type="hidden" value="$val[deptName]" name="payables[$i][deptName]"/>
								<input type="hidden" value="$val[deptId]" name="payables[$i][deptId]"/>
							</td>
							<td>
								$val[salesman]
								<input type="hidden" value="$val[salesman]" name="payables[$i][salesman]"/>
								<input type="hidden" value="$val[salesmanId]" name="payables[$i][salesmanId]"/>
							</td>
							<td>
								<input type="text" class="txtshort" value="$thisDay" name="payables[$i][formDate]"/>
							</td>
							<td>
								$val[supplierName]
								<input type="hidden" value="$val[supplierName]" name="payables[$i][supplierName]"/>
								<input type="hidden" value="$val[supplierId]" name="payables[$i][supplierId]"/>
								<input type="hidden" value="$val[payMoney]" name="payables[$i][amount]"/>
							</td>
							<td class="formatMoney">
								$val[payMoney]
							</td>
							<td>
								$objCode
								<input type="hidden" value="$val[objId]" name="payables[$i][detail][$j][objId]"/>
								<input type="hidden" value="$val[objCode]" name="payables[$i][detail][$j][objCode]"/>
								<input type="hidden" value="$val[objType]" name="payables[$i][detail][$j][objType]"/>
								<input type="hidden" value="$val[money]" name="payables[$i][detail][$j][money]"/>
							</td>
							<td class="formatMoney">
								$val[money]
							</td>
						</tr>
EOT;
                    $mark = $val['id'];
                } else {
                    $j++;
                    $str .= <<<EOT
						<tr>
							<td colspan="7">
							</td>
							<td>
								$objCode
								<input type="hidden" value="$val[objId]" name="payables[$i][detail][$j][objId]"/>
								<input type="hidden" value="$val[objCode]" name="payables[$i][detail][$j][objCode]"/>
								<input type="hidden" value="$val[objType]" name="payables[$i][detail][$j][objType]"/>
								<input type="hidden" value="$val[money]" name="payables[$i][detail][$j][money]"/>
							</td>
							<td class="formatMoney">
								$val[money]
							</td>
						</tr>
EOT;

                }
            }
            $str .= <<<EOT
				<tr class="tr_odd">
					<td></td>
					<td class="innerTd">
						邮件通知：
					</td>
					<td class="form_text_right " colspan="7" class="innerTd">
						<input type="text" class="txt" id="mailman$i" name="payables[$i][email][TO_NAME]" value="$salesman"/>
						<input type="hidden" id="mailmanId$i" name="payables[$i][email][TO_ID]" value="$salesmanId"/>
						<input type="hidden" name="payables[$i][email][issend]" value="y"/>
					</td>
				</tr>
				<tr class="tr_count">
					<td colspan="9"></td>
				</tr>
EOT;
        }
        return array($str, $i);
    }

    /**
     * 查询付款申请信息
     * @param $object
     * @return mixed
     */
    function getApplyAndDetailO_d($object) {
        $this->searchArr = $object;
        $this->groupBy = 'c.id,d.objId,d.objType';
        return $this->list_d('select_detailcount');
    }

    /**
     * 查询付款申请信息 - 新付款记录
     * @param $object
     * @return mixed
     */
    function getApplyAndDetailNew_d($object) {
        $this->searchArr = $object;
        $this->setCompany(0);
        return $this->list_d('select_detailcountNew');
    }

    /**
     * 数组重组 - 暂时不用 2012-02-28
     * @param $object
     * @return array
     */
    function initAddIngroupO_d($object) {
        $newArr = array();
        if ($object) {
            $i = 0; //列表记录序号
            $j = 0; //次行
            $mark = null; //列表记录序号
            foreach ($object as $key => $val) {
                if ($mark != $val['id']) {//如果是主表行
                    $mark = $val['id'];//记录ID
                    switch ($val['formType']) {//处理单据类型
                        case 'FKLX-01' :
                            $val['formType'] = 'CWYF-01';
                            break;
                        case 'FKLX-02' :
                            $val['formType'] = 'CWYF-02';
                            break;
                        case 'FKLX-03' :
                            $val['formType'] = 'CWYF-03';
                            break;
                        default :
                            break;
                    }
                    $i++;
                    $j = 0;
                    unset($val['id']);
                    $newArr['payables'][$i] = $val;
                    $newArr['payables'][$i]['detail'][$j]['objId'] = $val['objId'];
                    $newArr['payables'][$i]['detail'][$j]['objCode'] = $val['objCode'];
                    $newArr['payables'][$i]['detail'][$j]['objType'] = $val['objType'];
                    $newArr['payables'][$i]['detail'][$j]['money'] = $val['money'];

                    $newArr['payables'][$i]['email']['issend'] = 'y';
                    $newArr['payables'][$i]['email']['TO_ID'] = $val['salesmanId'];
                    $newArr['payables'][$i]['email']['TO_NAME'] = $val['salesman'];
                } else {//如果是从表行，对从表信息进行加载
                    $j++;
                    $newArr['payables'][$i]['detail'][$j]['objId'] = $val['objId'];
                    $newArr['payables'][$i]['detail'][$j]['objCode'] = $val['objCode'];
                    $newArr['payables'][$i]['detail'][$j]['objType'] = $val['objType'];
                    $newArr['payables'][$i]['detail'][$j]['money'] = $val['money'];
                }
            }
        }
        return $newArr;
    }

    /**
     * 数组重组 - 新 2012-02-28
     * @param $object
     * @return array
     */
    function initAddIngroupNew_d($object) {
        $newArr = array();
        if ($object) {
            $i = 0; //列表记录序号
            $j = 0; //次行
            $mark = null; //列表记录序号
            $objArr = array();
            foreach ($object as $key => $val) {
                if ($mark != $val['id']) {//如果是主表行
                    $mark = $val['id'];//记录ID
                    switch ($val['formType']) {//处理单据类型
                        case 'FKLX-01' :
                            $val['formType'] = 'CWYF-01';
                            break;
                        case 'FKLX-02' :
                            $val['formType'] = 'CWYF-02';
                            break;
                        case 'FKLX-03' :
                            $val['formType'] = 'CWYF-03';
                            break;
                        default :
                            break;
                    }
                    $i++;
                    $j = 0;
                    unset($val['id']);
                    $newArr['payables'][$i] = $val;
                    $newArr['payables'][$i]['email']['issend'] = 'y';
                    $newArr['payables'][$i]['email']['TO_ID'] = $val['salesmanId'];
                    $newArr['payables'][$i]['email']['TO_NAME'] = $val['salesman'];

                    /**
                     * 付款明细处理
                     */
                    $newArr['payables'][$i]['detail'][$j]['objId'] = $val['objId'];
                    $newArr['payables'][$i]['detail'][$j]['objCode'] = $val['objCode'];
                    $newArr['payables'][$i]['detail'][$j]['objType'] = $val['objType'];
                    $newArr['payables'][$i]['detail'][$j]['money'] = $val['money'];

                    if (!empty($val['sourceType'])) {
                        //调用策略
                        $newClass = $this->getClass($val['sourceType']);
                        if (!isset($objArr[$newClass])) {
                            $objArr[$newClass] = new $newClass();
                        }
                        $expandArr = $objArr[$newClass]->rebuildExpandArr_i($val);
                        //合并扩展数据
                        $newArr['payables'][$i]['detail'][$j] = array_merge($newArr['payables'][$i]['detail'][$j], $expandArr);
                    }

                    //主表数据清除
                    unset($newArr['payables'][$i]['expand1']);
                    unset($newArr['payables'][$i]['expand2']);
                    unset($newArr['payables'][$i]['expand3']);
                    unset($newArr['payables'][$i]['productNo']);
                    unset($newArr['payables'][$i]['productName']);
                    unset($newArr['payables'][$i]['number']);
                    unset($newArr['payables'][$i]['objId']);
                    unset($newArr['payables'][$i]['objCode']);
                    unset($newArr['payables'][$i]['objType']);

                } else {//如果是从表行，对从表信息进行加载
                    $j++;
                    $newArr['payables'][$i]['detail'][$j]['objId'] = $val['objId'];
                    $newArr['payables'][$i]['detail'][$j]['objCode'] = $val['objCode'];
                    $newArr['payables'][$i]['detail'][$j]['objType'] = $val['objType'];
                    $newArr['payables'][$i]['detail'][$j]['money'] = $val['money'];

                    if (!empty($val['sourceType'])) {
                        //调用策略
                        $newClass = $this->getClass($val['sourceType']);

                        $expandArr = $objArr[$newClass]->rebuildExpandArr_i($val);
                        //合并扩展数据
                        $newArr['payables'][$i]['detail'][$j] = array_merge($newArr['payables'][$i]['detail'][$j], $expandArr);
                    }
                }
            }
        }
        return $newArr;
    }

    /**
     * 款项内容处理
     * @param $object
     * @return null|string
     */
    function detailDeald_d($object) {
        $equDao = new model_purchase_contract_equipment ();
        $mark = $str = null;
        foreach ($object as $key => $val) {
            if (!empty($val['objId'])) {
                if (empty($mark)) {
                    $str .= <<<EOT
						<tr>
							<td colspan="4" class="td_table">
								<table class="form_in_table">
									<thead>
										<tr>
											<td colspan="15" class="form_header">款项内容</td>
										</tr>
										<tr class="main_tr_header">
											<th width="25%">物料名称</th>
											<th>单位</th>
											<th >订单数量</th>
											<th >已入库数量</th>
											<th >单价</th>
											<th >金额</th>
											<th width="15%">采购用途</th>
											<th width="15%">申请部门</th>
										</tr>
									</thead>
									<tbody>
EOT;
                    $mark = 1;
                }
            }
            $equs = $equDao->getEqusByContractId($val ['objId']);
            $str .= $this->showEquList($equs);
        }
        if ($mark == 1) {
            $str .= <<<EOT
					</tbody>
				</table>
			</td>
		</tr>
EOT;
        }
        return $str;
    }

    /**
     * 显示内容列表
     * @param $rows
     * @return null|string
     */
    function showEquList($rows) {
        $str = null;
        if ($rows) {
            $i = 0;
            $interfObj = new model_common_interface_obj();
            foreach ($rows as $key => $val) {
                $i++;
                $purchTypeCn = $interfObj->typeKToC($val['purchType']); //类型名称
                $str .= <<<EOT
                    <tr>
                    <td>
                            $val[productName]
                        </td>
                        <td>
                            $val[units]
                        </td>
                        <td>
                            $val[amountAll]
                        </td>
                        <td>
                            $val[amountIssued]
                        </td>
                        <td class="formatMoney">
                            $val[applyPrice]
                        </td>
                        <td class="formatMoney">
                            $val[moneyAll]
                        </td>
                        <td>
                            $purchTypeCn
                        </td>
                        <td>
                            $val[applyDeptName]
                        </td>
                    </tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * 增加打印次数
     * @param $id
     * @param int $printTimes
     * @return mixed
     */
    function changePrintCount_d($id, $printTimes = 1) {
        $sql = 'update ' . $this->tbl_name . ' set printCount = printCount + ' . $printTimes . ' where id = ' . $id;

        $this->_db->query($sql);
        $selectSql = 'select printCount from ' . $this->tbl_name . ' where id = ' . $id;

        $rs = $this->_db->getArray($selectSql);

        return $rs[0]['printCount'];
    }

    /**
     * 增加打印次数 - 多id
     * @param $id
     * @param int $printTimes
     * @return int
     */
    function changePrintCountIds_d($id, $printTimes = 1) {
        $this->_db->query('update ' . $this->tbl_name . ' set printCount = printCount + ' . $printTimes .
            ',lastPrintTime = "' . date('Y-m-d H:i:s') . '" where id in(' . $id . ')');
        return 1;
    }

    /**
     * 根据采购订单id获取付款申请记录
     * @param $objId
     * @param $objType
     * @return mixed
     */
    function getApplyByPur_d($objId, $objType) {
        $this->searchArr['dObjId'] = $objId;
        $this->searchArr['dObjType'] = $objType;
        $this->searchArr['noExaStatus'] = BACK;
        $this->groupBy = 'c.id,d.objId,d.objType';
        return $this->list_d('select_history');
    }

    /**
     * 获取邮件发送从表信息 - 采购订单付款专用
     * @param $id
     * @return null|string
     */
    function getMailDetail_d($id) {
        $rs = $this->get_d($id, 'detail');
        $str = null;
        if (is_array($rs)) {
            $i = 0;
            $str = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>源单类型</b></td><td><b>源单编号</b></td><td><b>申请金额</b></td><td><b>源单金额</b></td>";
            $str .= "<td><b>物料编号</b></td><td><b>物料名称</b></td><td><b>物料型号</b></td><td><b>单位</b></td><td><b>数量</b></td><td><b>单价</b></td><td><b>价税合计</b></td></tr>";
            foreach ($rs['detail'] as $key => $val) {
                $i++;
                $str .= <<<EOT
					<tr><td>$i</td><td>采购订单</td><td>$val[objCode]</td><td>$val[money]</td><td>$val[purchaseMoney]</td><td>$val[productNo]</td><td>$val[productName]</td><td>$val[productModel]</td><td>$val[unitName]</td><td>$val[number]</td><td>$val[price]</td><td>$val[allAmount]</td></tr>
EOT;
            }
            $str .= "</table>";
        }
        return $str;
    }

    /**
     * 初始化审批信息
     * @param $rows
     * @return mixed
     */
    function initExaInfo_d($rows) {
        //id数组
        $idArr = array();

        foreach ($rows as $key => $val) {
            if (empty($val['exaCode'])) {
                $idArr[] = $val['id'];
            } else {
                $idArr[] = $val['exaId'];
            }
        }

        $idStr = implode($idArr, ',');

        $otherDataDao = new model_common_otherdatas();
        $exaInfo = $otherDataDao->getIdsLastExaInfo_d($idStr, 'oa_finance_payablesapply,oa_sale_other,oa_sale_outsourcing');

        $exaInfoArr = array();
        //构建审批信息数组
        foreach ($exaInfo as $key => $val) {
            $cusStr = $val['pid'] . '-' . $val['code'];
            $exaInfoArr[$cusStr]['ExaUser'] = $val['USER_NAME'];
            $exaInfoArr[$cusStr]['ExaContent'] = $val['content'];
        }

        //整合审批信息
        foreach ($rows as $key => $val) {

            if (empty($val['exaCode'])) {
                $cusStr = $val['id'] . '-' . $this->tbl_name;
            } else {
                $cusStr = $val['exaId'] . '-' . $val['exaCode'];
            }

            $rows[$key]['ExaUser'] = $exaInfoArr[$cusStr]['ExaUser'];
            $rows[$key]['ExaContent'] = $exaInfoArr[$cusStr]['ExaContent'];
        }

        return $rows;
    }

    /**
     * 查询可以打印付款的付款申请单
     * @return mixed
     */
    function getPayablesapplyCanPay_d() {
        $this->searchArr = array(
            'status' => 'FKSQD-01',
            'ExaStatus' => AUDITED,
            'payForArr' => 'FKLX-01,FKLX-02',
            'payDateEnd' => day_date,
            'isEntrust' => 0
        );
		$this->sort = 'c.actPayDate DESC,c.id';
		$this->asc = 'DESC';
        return $this->list_d();
    }

    /**
     * 审批完成后业务处理
     * @param $spid
     * @return int
     * @throws Exception
     */
    function workflowCallBack($spid) {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getStepInfo($spid);
        //需要更新的数组
        $updateArr = array();
        $updateArr['ExaUser'] = $folowInfo ['USER_NAME'];
        $updateArr['ExaUserId'] = $folowInfo['USER_ID'];
        $updateArr['ExaContent'] = $folowInfo['content'];

        //申请单Id
        $condition = array('id' => $folowInfo ['objId']);

        $obj = $this->get_d($folowInfo ['objId']);
        try {
            $this->start_d();

            //如果审批通过且是委托付款
            if ($obj['ExaStatus'] == AUDITED && $obj['isEntrust']) {
                //将付款更新为已付款
                $updateArr['status'] = 'FKSQD-03';
                $updateArr['actPayDate'] = $obj['payDate'];
                $updateArr['payedMoney'] = $obj['payMoney'];

                //生成付款单
                $payablesDao = new model_finance_payables_payables();

                //获取付款申请单数据
                $rows = $this->getApplyAndDetailNew_d(array('ids' => $folowInfo ['objId']));
                $addObj = $this->initAddIngroupNew_d($rows);

                $payablesDao->addInGroup_d($addObj['payables'], true);
            }

            //更新审批信息
            $this->update($condition, $updateArr);

            $this->commit_d();
            return 1;
        } catch (Exception $e) {
            $this->rollBack();
            return 1;
        }
    }

    /**
     * 提交财务支付 - 目前用于个人列表中的提交财务支付
     * @param $id
     * @return mixed
     */
    function handUpPay_d($id) {
        //更新状态
        $rs = parent::edit_d(array('id' => $id, 'status' => 'FKSQD-01'), true);

        //邮件发送
        include(WEB_TOR . "model/common/mailConfig.php");
        $mailStr = $this->getMailType_d('payablesapply_handUpPay');
        $mailArr = isset($mailUser[$mailStr][0]) ? $mailUser[$mailStr][0] : array('sendUserId' => '', 'sendName' => '');

        $content = "你好，用户 【" . $_SESSION['USERNAME'] . "】 已将付款申请单 id : " . $id . " 提交财务支付，请对单据进行付款。";

        $emailDao = new model_common_mail();
        $emailDao->mailClear('付款申请确认支付 id:' . $id, $mailArr['sendUserId'], $content);

        return $rs;
    }

    /**
     * 提交财务支付(新)
     * @param $obj
     * @return mixed
     */
    function handUpPay2_d($obj) {
        $emailArr = $obj['email'];
        if (!$emailArr['instruction']) {
            $emailArr['instruction'] = '无';
        }
        $rs = parent::edit_d(array('id' => $obj['id'], 'status' => 'FKSQD-01', 'isPay' => $obj['isPay'],
            'instruction' => $emailArr['instruction']), true);
        if (isset($emailArr)) {
            if ($emailArr['isEntrust'] == '1') {
                $payStr = '该单据已线下支付';
            } else {
                $payStr = '请对单据进行付款';
            }
            if (!empty($emailArr['TO_ID'])) {
                $this->mailDeal_d('handUpPayMail', $emailArr['TO_ID'], array(
                    'id' => $obj['id'], 'instruction' => $emailArr['instruction'],
                    'payStr' => $payStr
                ), null, true, $obj['businessBelong']);
            }
        }
        return $rs;
    }

    /**
     * 用于预警管理 - 系统自动将付款申请单提交财务支付
     * @param $obj
     * @return bool
     */
    function autoHandUp_d($obj) {
        $rs = parent::edit_d(array('id' => $obj['id'], 'status' => 'FKSQD-01'), true);

        if ($obj['isEntrust'] == '1') {
            $payStr = '该单据已线下支付';
        } else {
            $payStr = '请对单据进行付款';
        }
        $this->mailDeal_d('autoHandUpPayMail', null, array(
            'id' => $obj['id'], 'createName' => $obj['createName'],
            'payStr' => $payStr
        ), null, true, $obj['businessBelong']);

        return $rs;
    }

    /**
     * 增加退款处理
     * @param $id
     * @return bool
     * @throws Exception
     */
    function applyRefund_d($id) {
        try {
            //将申请单的状态改为已退款
            $this->update(array('id' => $id), array('status' => 'FKSQD-05'));

            //其余业务处理
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 删除退款处理
     * @param $id
     * @return bool
     * @throws Exception
     */
    function applyDelRefund_d($id) {
        try {
            //将申请单的状态改为已退款
            $this->update(array('id' => $id), array('status' => 'FKSQD-03'));

            //其余业务处理
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 计算出订单的最后已审核的入库日期
     * @param $id
     * @return mixed
     */
    function getEntryDate_d($id) {
        $data = $this->_db->get_one("select max(auditDate) as entryDate from oa_stock_instock where
            purOrderId in ( select objId  from oa_finance_payablesapply_detail where payapplyId = {$id})");

        return $data['entryDate'] ? $data['entryDate'] : "暂未有入库时间";
    }

    /**
     * 判断部门是否需要省份
     */
    function deptIsNeedProvince_d($deptId){
    	include (WEB_TOR."includes/config.php");
    	//部门费用需要省份的部门
    	$otherPayNeedProvinceDept = isset($otherPayNeedProvinceDept) ? $otherPayNeedProvinceDept : null;
    	//返回key数组
    	$keyArr = array_keys($otherPayNeedProvinceDept);
    	if(in_array($deptId,$keyArr)){
    		return 1;
    	}else{
    		return 0;
    	}
    }

	/**
     * 支付信息
     * @param $id
     * @return mixed
     */
    function getPayinfo_d($id) {
        return $this->_db->getArray("SELECT * FROM oa_sale_otherpayapply WHERE contractId={$id}");
    }
}