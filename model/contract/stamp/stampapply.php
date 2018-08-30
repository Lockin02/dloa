<?php

/**
 * @author Show
 * @Date 2012��10��20�� 15:27:58
 * @version 1.0
 * @description:���������(oa_sale_stampapply) Model��
 */
class model_contract_stamp_stampapply extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_sale_stampapply";
        $this->sql_map = "contract/stamp/stampapplySql.php";
        parent :: __construct();
    }

    /**
     * ����״̬
     */
    public function rtStampType_d($status)
    {
        if ($status == 1) {
            return '�Ѹ���';
        } elseif ($status == 0) {
            return 'δ����';
        } else {
            return '�ѹر�';
        }
    }

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
        'stampExecution'
    );

    /**
     * ��дadd_d
     */
    function add_d($object)
    {
        try {
            //�����ֵ��Լ����ݴ���
            $object = $this->processDatadict($object);

            if (!isset($object['ExaStatus'])) {
                $object['ExaStatus'] = WAITAUDIT;
            }
            $newId = parent::add_d($object, true);

            //���¸���������ϵ
            $this->updateObjWithFile($newId, $object['orderCode']);

            $this->commit_d();
            return $newId;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���������޸Ķ���
     */
    function edit_d($object)
    {
        //�����ֵ��Լ����ݴ���
        $object = $this->processDatadict($object);
        return parent::edit_d($object, true);
    }

    /**
     * ���������ת
     */
    function workflowCallBack($spid)
    {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getStepInfo($spid);
        $objId = $folowInfo ['objId'];

        $object = $this->get_d($objId);
        if ($object['ExaStatus'] == AUDITED) {
            //�����Ҫ����
            //��������
            $stampArr = array(
                "applyId" => $object['id'],
                "contractId" => $object['contractId'],
                "contractCode" => $object['contractCode'],
                "contractType" => $object['contractType'],
                "contractName" => $object['contractName'],
                "signCompanyName" => $object['signCompanyName'],
                "signCompanyId" => $object['signCompanyId'],
                "contractMoney" => $object['orderMoney'],
                "applyUserId" => $object['applyUserId'],
                "applyUserName" => $object['applyUserName'],
                "applyDate" => $object['applyDate'],
                "stampType" => $object['stampType'],
                "status" => 0,
                "attn" => $object['attn'],
                "attnId" => $object['attnId'],
                "attnDept" => $object['attnDept'],
                "attnDeptId" => $object['attnDeptId']
            );
            $stampDao = new model_contract_stamp_stamp();
            $stampDao->addStamps_d($stampArr, true);

            // �ʼ�֪ͨ���¸�����
            $stampNames = util_jsonUtil::strBuild($object['stampType']);
            $stampInfoSql = (!empty($object['stampIds']) && $object['stampIds'] != '')?
                "select stampName,principalId from oa_system_stamp_config where id in ({$object['stampIds']})" :
                "select stampName,principalId from oa_system_stamp_config where stampName in ({$stampNames})";
            $stampInfo = $this->_db->getArray($stampInfoSql);
            if($stampInfo && is_array($stampInfo)){
                foreach ($stampInfo as $key => $stamp){
                    $this->mailDeal_d('stampapply_passed', $stamp['principalId'], array('applyName' => $object['createName'],'stampName' => $stamp['stampName']));
                }
            }
        }
    }

    /**
     * �ύ����е��ڸ����б������Ϣ
     */
    function dealAfterSubmit_d($objId)
    {
        $object = $this->get_d($objId);
        $stampArr = array(
            "applyId" => $object['id'],
            "contractId" => $object['contractId'],
            "contractCode" => $object['contractCode'],
            "contractType" => $object['contractType'],
            "contractName" => $object['contractName'],
            "signCompanyName" => $object['signCompanyName'],
            "signCompanyId" => $object['signCompanyId'],
            "contractMoney" => $object['orderMoney'],
            "applyUserId" => $object['applyUserId'],
            "applyUserName" => $object['applyUserName'],
            "applyDate" => $object['applyDate'],
            "stampType" => $object['stampType'],
            "status" => 0,
            "attn" => $object['attn'],
            "attnId" => $object['attnId'],
            "attnDept" => $object['attnDept'],
            "attnDeptId" => $object['attnDeptId']
        );
        $stampDao = new model_contract_stamp_stamp();
        $stampDao->addStamps_d($stampArr, true);
    }

    //�ж��Ƿ���Ҫ����������������
    function checkNeedAudit($object)
    {
        $sql = "select id from oa_system_stamp_matter where id in (" . $object['useMattersId'] . ") and needAudit = 1";
        $useResult = $this->findSql($sql);
        if (empty($useResult)) {
            $object['isNeedAudit'] = 0;
        } else {
            $object['isNeedAudit'] = 1;
        }
        if (isset($_GET['act']) && empty($useResult)) {
            $object['ExaStatus'] = '���';
            $object['ExaDT'] = day_date;
        }
        return $object;
    }

    function  sendEmail($object, $newId)
    {
        //�ʼ���Ϣ��ȡ
        if (isset($object['email'])) {
            $emailArr = $object['email'];
            unset($object['email']);

            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $object['isSended'] = 1;
            }
        }

        if ($object['stampExecution'] == 'HTGZXZ-01') {
            $stampExecution = '��';
        } else {
            $stampExecution = '��˽';
        }

        //�����ʼ� ,������Ϊ�ύʱ�ŷ���
        if (isset($emailArr)) {
            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $this->mailDeal_d('stampApplyMail', $emailArr['TO_ID'], array('id' => $newId, 'stampType' => $object['stampType'],
                    'applyUserName' => $object['applyUserName'], 'fileName' => $object['fileName'],
                    'signCompanyName' => $object['signCompanyName'], 'useMatters' => $object['useMatters'], 'stampExecution' => $stampExecution,
                    'remark' => $object['remark']
                ));
            }
        }
    }

    /**
     * �жϺ�ͬ�Ƿ������
     * @param $contractId
     * @return int
     */
    function contractIsAudited_d($contractId) {
        $contractDao = new model_contract_contract_contract();
        $contractObj = $contractDao->get_d($contractId);

        // �жϺ�ͬ�Ƿ������
        return $contractObj['ExaStatus'] == AUDITED ? 1 : 0;
    }
}