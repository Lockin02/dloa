<?php

/**
 * @author Show
 * @Date 2011年12月27日 星期二 20:39:05
 * @version 1.0
 * @description:应付其他发票 Model层 审核状态 ExaStatus
 * 0.未审核
 * 1.已审核
 */
class model_finance_invother_invother extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_invother";
        $this->sql_map = "finance/invother/invotherSql.php";
        parent::__construct();
    }

    /********************新策略部分使用************************/

    // 不同类型入库申请策略类,根据需要在这里进行追加
    private $relatedStrategyArr = array(
        'YFQTYD02' => 'model_finance_invother_strategy_other', // 其他合同
        'YFQTYD01' => 'model_finance_invother_strategy_outsourcing', // 外包合同
        'YFQTYD03' => 'model_finance_invother_strategy_rentcar'// 租车合同
    );

    /**
     * 获取提交状态对应的提示
     * @param $v
     * @return string
     */
    function getMsg_d($v) {
        switch ($v) {
            case 'audit' :
                $msg = '审核成功';
                break;
            case 'sub' :
                $msg = '提交成功';
                break;
            case 'back' :
                $msg = '打回成功';
                break;
            default :
                $msg = '保存成功';
        }
        return $msg;
    }

    /**
     * 根据数据类型返回类
     * @param $objType
     * @return mixed
     */
    public function getClass($objType) {
        return $this->relatedStrategyArr[$objType];
    }

    // 单据是否要区分公司,1为区分,0为不区分
    protected $_isSetCompany = 1;

    // 对应业务代码
    private $relatedCode = array(
        'YFQTYD02' => 'other',
        'YFQTYD01' => 'outsourcing',
        'YFQTYD03' => 'rentcar'
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
     * @param iinvother $strategy
     * @param formCode 不为null时，则通过源单编号获取数据
     * @return mixed
     */
    public function getObjInfo_d($obj, iinvother $strategy, $formCode = null) {
        return $strategy->getObjInfo_d($obj, $formCode);
    }

    /**
     * 获取邮件扩展内容
     * @param $objCode
     * @param iinvother $strategy
     */
    public function getMailExpend_d($objCode, iinvother $strategy) {
        return $strategy->getMailExpend_d($objCode);
    }

    /**
     * 邮件配置获取
     */
    function getMailInfo_d() {
        include(WEB_TOR . "model/common/mailConfig.php");
        $mailArr = isset($mailUser['invother']) ? $mailUser['invother'] : array('sendUserId' => '',
            'sendName' => '');
        return $mailArr;
    }

    /********************新策略部分使用************************/

    /**
     * 新增保存
     * @param $object
     * @param null $actType
     * @return bool|null
     */
    function add_d($object, $actType = null) {
        $codeRuleDao = new model_common_codeRule();

        if (isset($object['mail'])) {
            $emailArr = $object['mail'];
            unset($object['mail']);
        }

        // created by huanghaojin 16-10-27 pms2138
        if (isset($object['isShare'])) {
            // 为了让后面的步骤能判断出这个单据是无分摊录入的发票
            $object['isShareCost'] = ($object['isShare'] == 0)? "no" : "yes";
        }

        try {
            $this->start_d();

            if (is_array($object['items'])) {

                // 获取从表数组
                $itemsArr = $object['items'];
                unset($object['items']);

                // 取费用分摊信息
                if (isset($object['costshare'])) {
                    $shareObj['costshare'] = $object['costshare'];
                    unset($object['costshare']);
                }

                // 新增单据
                $object['invoiceCode'] = $codeRuleDao->invotherCode($this->tbl_name, $object['salesmanId']);

                // 如果是提交或者是审核状态，则将提交状态设为1
                if ($actType == 'sub' || $actType == 'audit') {
                    $object['status'] = 1;
                }

                $id = parent::add_d($object, true);

                // 增加从表信息
                $invotherdetailDao = new model_finance_invother_invotherdetail();
                $itemsArr = util_arrayUtil::setItemMainId("mainId", $id, $itemsArr);
                $invotherdetailDao->saveDelBatch($itemsArr);

                // 分摊明细处理
                if (isset($shareObj)) {
                    $costHookDao = new model_finance_cost_costHook();
                    $shareObj['costshare'] = util_arrayUtil::setItemMainId("invotherId", $id, $shareObj['costshare']);
                    $shareObj['hookObj'] = array(
                        'hookId' => $id, 'hookCode' => $object['invoiceCode'], 'hookDate' => $object['formDate']
                    );
                    $hookObj = $costHookDao->dealHook_d($shareObj, $actType == 'audit' ? true : false);

                    // 更新分摊金额
                    $this->update(array('id' => $id), array('hookMoney' => $hookObj['hookMoney']));
                }

                // 更新附件关联关系
                $this->updateObjWithFile($id, $object['invoiceNo']);

                $this->commit_d();

                // 邮件发送
                if (isset($emailArr) && $actType == 'sub' && $emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                    $this->mailDeal_d('invotherAdd', $emailArr['TO_ID'], $object);
                }

                return $id;
            } else {
                throw new Exception ("单据信息不完整，请确认！");
            }
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 编辑其他邮件邮件发送
     * @param $object
     */
    function thisMailSend_d($object) {
        if (isset($object['mail'])) {
            $emailArr = $object['mail'];
            if (!empty($emailArr['TO_ID'])) {
                $addMsg = '邮件内容：' . $emailArr['mailContent'];

                $emailDao = new model_common_mail();
                $emailDao->mailClear('OA-通知 :其他发票', $emailArr['TO_ID'], $addMsg);
            }
        }
    }

    /**
     * 修改保存
     * @param $object
     * @param null $actType
     * @return bool|null
     */
    function edit_d($object, $actType = null) {

        if (isset($object['mail'])) {
            $emailArr = $object['mail'];
            unset($object['mail']);
        }

        try {
            $this->start_d();
            if (is_array($object ['items'])) {
                //获取从表数组
                $itemsArr = $object ['items'];
                unset($object ['items']);

                //取费用分摊信息
                if (isset($object['costshare'])) {
                    $shareObj['costshare'] = $object['costshare'];
                    unset($object['costshare']);
                }

                // 如果是提交，则修改提交状态
                if ($actType == 'sub') {
                    $object['status'] = 1;
                } elseif ($actType == 'back') {
                    $object['status'] = 2;
                } elseif ($actType == 'audit') {
                    $object = $this->setAuditInfo_d($object);
                }

                parent::edit_d($object, true);

                $invotherdetailDao = new model_finance_invother_invotherdetail();
                $itemsArr = util_arrayUtil::setItemMainId("mainId", $object ['id'], $itemsArr);
                $invotherdetailDao->saveDelBatch($itemsArr);

                //分摊明细处理
                if (isset($shareObj)) {
                    // 勾稽日期选择
                    $hookDate = isset($object['ExaDT']) && !empty($object['ExaDT']) && $object['ExaDT'] != '0000-00-00' ?
                        $object['ExaDT'] : $object['formDate'];

                    $costHookDao = new model_finance_cost_costHook();
                    $shareObj['costshare'] = util_arrayUtil::setItemMainId("invotherId", $object['id'], $shareObj['costshare']);
                    $shareObj['hookObj'] = array(
                        'hookId' => $object['id'], 'hookCode' => $object['invoiceCode'], 'hookDate' => $hookDate
                    );
                    $hookObj = $costHookDao->dealHook_d($shareObj, $actType == 'audit' ? true : false);

                    // 更新分摊金额
                    $this->update(array('id' => $object['id']), array('hookMoney' => $hookObj['hookMoney']));
                }

                // 审核处理
                if ($actType == 'audit') {
                    // 工程决算处理 - 只要租车合同的才进行处理
                    if ($object['sourceType'] == 'YFQTYD03') {
                        $periodArr = explode('.', $object['period']);
                        $esmfieldrecordDao = new model_engineering_records_esmfieldrecord();
                        $esmfieldrecordDao->businessFeeUpdate_d('rentCar', $periodArr[0],
                            $periodArr[1], array(
                                'id' => $object['id'], 'menuNo' => $object['menuNo']
                            ));
                    }

                    // 审核的时候，往邮件内容中传入欠票信息。
                    $newClass = $this->getClass($object['sourceType']);
                    if ($newClass) {
                        $initObj = new $newClass();
                        //获取对应业务信息
                        $object['mailExpend'] = $this->getMailExpend_d($object['menuNo'], $initObj);
                    }
                }

                $this->commit_d();

                // 修改邮件 -- 如果当前用户不是创建人，则通知创建人
                if (isset($emailArr) && $emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                    if ($actType == 'save') {
                        $this->mailDeal_d('invotherEdit', $emailArr['TO_ID'], $object);
                    } elseif ($actType == 'audit') {
                        $this->mailDeal_d('invotherAudit', $emailArr['TO_ID'], $object);
                    } elseif ($actType == 'back') {
                        $this->mailDeal_d('invotherBackMail', $emailArr['TO_ID'], $object);
                    }
                }

                return true;
            } else {
                throw new Exception ("单据信息不完整，请确认！");
            }
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 要删除的id
     * @param $ids
     * @return true
     * @throws $e
     */
    function deletes_d($ids) {
        $costHookDao = new model_finance_cost_costHook();
        try {
            $this->start_d();

            // delete deal
            $this->deletes($ids);

            // delete hook info
            $costHookDao->deleteHook_d($ids, 2);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取已开票金额
     * @param $objId
     * @param string $objType
     * @return int
     */
    function getInvotherMoney_d($objId, $objType = 'YFQTYD02') {
        $this->setCompany(0);
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType
        );
        $this->sort = '';
        $rs = $this->list_d("select_sum");
        $this->groupBy = 'd.objId';
        if ($rs[0]['formCount']) {
            return $rs[0]['formCount'];
        } else {
            return 0;
        }
    }

    /**
     * 添加审核信息
     * @param $object
     * @return array
     */
    function setAuditInfo_d($object) {
        return array_merge($object, array('ExaStatus' => 1, 'exaMan' => $_SESSION['USERNAME'],
            'exaManId' => $_SESSION['USER_ID'], 'ExaDT' => day_date
        ));
    }

    /**
     * 反审核
     * @param $id
     * @return mixed
     */
    function unaudit_d($id) {
        // 查询发票数据
        $object = $this->find(array('id' => $id), null, 'formDate,sourceType,menuNo,period');

        try {
            $this->start_d();

            // 更新审核状态
            parent::edit_d(array('id' => $id, 'ExaStatus' => 0, 'exaMan' => null, 'exaManId' => null,
                'ExaDT' => null, 'hookMoney' => 0
            ), true);

            // 工程决算处理 - 只要租车合同的才进行处理
            if ($object['sourceType'] == 'YFQTYD03') {
                $periodArr = explode('.', $object['period']);
                $esmfieldrecordDao = new model_engineering_records_esmfieldrecord();
                $esmfieldrecordDao->businessFeeUpdate_d('rentCar', $periodArr[0],
                    $periodArr[1], array(
                        'id' => $object['id'], 'menuNo' => $object['menuNo']
                    ));
            }

            // 取消勾稽处理
            $costHookDao = new model_finance_cost_costHook();
            $costHookDao->unAudit_d($id, 2);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }
}