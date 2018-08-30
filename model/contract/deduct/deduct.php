<?php

/**
 * @author Administrator
 * @Date 2012-04-11 20:09:54
 * @version 1.0
 * @description:�ۿ����� Model��
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
     *�����ɹ�������
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
                $addmsg = "  ���ã�<br/>" .
                    "   ��Ժ�ͬ :��<span style='color:blue'>" . $contractArr['contractCode'] . "</span>   �ύ�Ŀۿ�������ͨ������";
                $emailDao = new model_common_mail();
                $emailDao->deductMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_contract_deduct", "����", "ͨ��", $tomail, $addmsg);
            }
        }
        return 1;
    }

    /**
     * �ۿ��
     */
    function dedispose_d($obj)
    {
        $moneyType = $obj['deductDispose'];
        $moneyNum = $obj['deductMoney'];
        $handleType = array(
            "deductMoney" => "�ۿ�",
            "badMoney" => "����"
        );

        try {
            $this->start_d();

            $upsql = "update oa_contract_deduct set state=1,dispose='" . $obj['deductDispose'] . "' where id=" . $obj['id'] . "";
            $this->query($upsql);

            //���ݺ�ͬid���¼����ͬ�ۿ���˽��
            $deductSql = "select sum(if(dispose = 'deductMoney',deductMoney,0)) as deductMoney,sum(if(dispose = 'badMoney',deductMoney,0)) as badMoney
                      from oa_contract_deduct where state='1' and ExaStatus='���'  and contractId = '" . $obj['contractId'] . "'";
            $deductInfoArr = $this->_db->getArray($deductSql);

            $sql = "update oa_contract_contract set deductMoney=" . $deductInfoArr[0]['deductMoney'] . ",badMoney=" . $deductInfoArr[0]['badMoney'] . " where id=" . $obj['contractId'] . "";
            $this->query($sql);

            $contractDao = new model_contract_contract_contract();
            $contractInfo = $contractDao->get_d($obj['contractId']);

            //�����ͬȷ������
            $conFirmArr['type'] = '��ͬ�ۿ�';
            $conFirmArr['money'] = $obj['deductMoney'];
            $conFirmArr['state'] = 'δȷ��';
            $conFirmArr['contractId'] = $contractInfo['id'];
            $conFirmArr['contractCode'] = $contractInfo['contractCode'];
            $confirmDao = new model_contract_contract_confirm();
            $confirmDao->add_d($conFirmArr);

            //�ؿ�������� create on 2013-09-02 by kuangzw
            $incomeCheck = $obj['incomeCheck'];
            $deductDispose = $obj['deductDispose'];// ���ݴ���ķ������������Ŀɿۿ��Χ change by huanghaojin 2016-09-28
            unset($obj['incomeCheck']);
            if (empty($incomeCheck)) {
                //ʵ��������������
                $receiptPlanDao = new model_contract_contract_receiptplan();
                $incomeCheck = $receiptPlanDao->autoInitCheck_d(array($obj['contractId'] => $obj['deductMoney']), 1);
            }
            if ($incomeCheck) {
                // ȥ��δ������
                foreach ($incomeCheck as $k => $v){
                    unset($incomeCheck[$k]['unIncomMoney']);
                }

                //��������
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
                $addmsg = "  ���ã�<br/>" .
                    "   ��Ժ�ͬ :��<span style='color:blue'>" . $contractInfo['contractCode'] . "</span> �Ŀۿ���Ϣ�Ѵ������<br/>" .
                    " ������ �� " . $handleType[$moneyType] . " <br/> ������ ��$moneyNum";
                $emailDao = new model_common_mail();
                $emailDao->deductMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_contract_deductEnd", "����", "ͨ��", $tomail, $addmsg);
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
     * ���ݺ�ͬid ��ȡ������ۿ���
     */
    function getApplyMoney($cid)
    {
        $sql = "select sum(deductMoney) as applyMoney from oa_contract_deduct where contractId= '" . $cid . "' and (ExaStatus='���' or ExaStatus='��������')";
        $appArr = $this->_db->getArray($sql);
        if (empty($appArr[0]['applyMoney'])) {
            return "0";
        }
        return $appArr[0]['applyMoney'];
    }

    /**
     * �������ص�����
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