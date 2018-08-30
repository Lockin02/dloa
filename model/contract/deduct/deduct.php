<?php

/**
 * @author Administrator
 * @Date 2012-04-11 20:09:54
 * @version 1.0
 * @description:扣款申请 Model层
 */
class model_contract_deduct_deduct extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_contract_deduct";
        $this->sql_map = "contract/deduct/deductSql.php";
        parent:: __construct();
    }

    /**
     *审批成功后处理方法
     */
    function dealAfterAudit_d($objId, $userId)
    {
        $object = $this->get_d($objId);
        $Dao = new model_contract_contract_contract();
        $contractArr = $Dao->get_d($object['contractId']);

        if ($object['ExaStatus'] == AUDITED) {
            include(WEB_TOR . "model/common/mailConfig.php");
            if (isset($mailUser['oa_contract_deductEnd']['TO_ID'])) {
                $tomail = $mailUser['oa_contract_deduct']['TO_ID'];
                $addmsg = "  您好：<br/>" .
                    "   针对合同 :　<span style='color:blue'>" . $contractArr['contractCode'] . "</span>   提交的扣款申请以通过审批";
                $emailDao = new model_common_mail();
                $emailDao->deductMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_contract_deduct", "审批", "通过", $tomail, $addmsg);
            }
        }
        return 1;
    }

    /**
     * 扣款处理
     */
    function dedispose_d($obj)
    {
        $moneyType = $obj['deductDispose'];
        $moneyNum = $obj['deductMoney'];
        $handleType = array(
            "deductMoney" => "扣款",
            "badMoney" => "坏账"
        );

        try {
            $this->start_d();

            $upsql = "update oa_contract_deduct set state=1,dispose='" . $obj['deductDispose'] . "' where id=" . $obj['id'] . "";
            $this->query($upsql);

            //根据合同id重新计算合同扣款，坏账金额
            $deductSql = "select sum(if(dispose = 'deductMoney',deductMoney,0)) as deductMoney,sum(if(dispose = 'badMoney',deductMoney,0)) as badMoney
                      from oa_contract_deduct where state='1' and ExaStatus='完成'  and contractId = '" . $obj['contractId'] . "'";
            $deductInfoArr = $this->_db->getArray($deductSql);

            $sql = "update oa_contract_contract set deductMoney=" . $deductInfoArr[0]['deductMoney'] . ",badMoney=" . $deductInfoArr[0]['badMoney'] . " where id=" . $obj['contractId'] . "";
            $this->query($sql);

            $contractDao = new model_contract_contract_contract();
            $contractInfo = $contractDao->get_d($obj['contractId']);

            //插入合同确认数据
            $conFirmArr['type'] = '合同扣款';
            $conFirmArr['money'] = $obj['deductMoney'];
            $conFirmArr['state'] = '未确认';
            $conFirmArr['contractId'] = $contractInfo['id'];
            $conFirmArr['contractCode'] = $contractInfo['contractCode'];
            $confirmDao = new model_contract_contract_confirm();
            $confirmDao->add_d($conFirmArr);

            //回款核销部分 create on 2013-09-02 by kuangzw
            $incomeCheck = $obj['incomeCheck'];
            $deductDispose = $obj['deductDispose'];// 根据处理的方法的区别后面的可扣款金额范围 change by huanghaojin 2016-09-28
            unset($obj['incomeCheck']);
            if (empty($incomeCheck)) {
                //实例化付款条件类
                $receiptPlanDao = new model_contract_contract_receiptplan();
                $incomeCheck = $receiptPlanDao->autoInitCheck_d(array($obj['contractId'] => $obj['deductMoney']), 1);
            }
            if ($incomeCheck) {
                // 去掉未到款金额
                foreach ($incomeCheck as $k => $v){
                    unset($incomeCheck[$k]['unIncomMoney']);
                }

                //核销处理
                $incomeCheckDao = new model_finance_income_incomecheck();
                $incomeCheck = util_arrayUtil::setArrayFn(
                    array('incomeId' => $obj['id'], 'incomeNo' => $contractInfo['contractCode'], 'incomeType' => 1),
                    $incomeCheck
                );
                $incomeCheckDao->batchDeal_d($incomeCheck, $deductDispose);
            }

            include(WEB_TOR . "model/common/mailConfig.php");
            if (isset($mailUser['oa_contract_deductEnd']['TO_ID'])) {
                $tomail = $mailUser['oa_contract_deductEnd']['TO_ID'];
                $addmsg = "  您好：<br/>" .
                    "   针对合同 :　<span style='color:blue'>" . $contractInfo['contractCode'] . "</span> 的扣款信息已处理完成<br/>" .
                    " 处理结果 ： " . $handleType[$moneyType] . " <br/> 处理金额 ：$moneyNum";
                $emailDao = new model_common_mail();
                $emailDao->deductMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_contract_deductEnd", "审批", "通过", $tomail, $addmsg);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }

    }

    /**
     * 根据合同id 获取已申请扣款金额
     */
    function getApplyMoney($cid)
    {
        $sql = "select sum(deductMoney) as applyMoney from oa_contract_deduct where contractId= '" . $cid . "' and (ExaStatus='完成' or ExaStatus='部门审批')";
        $appArr = $this->_db->getArray($sql);
        if (empty($appArr[0]['applyMoney'])) {
            return "0";
        }
        return $appArr[0]['applyMoney'];
    }

    /**
     * 审批流回调方法
     */
    function workflowCallBack($spid)
    {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $objId = $folowInfo ['objId'];
        $userId = $folowInfo['Enter_user'];
        $this->dealAfterAudit_d($objId, $userId);
    }
}