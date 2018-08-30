<?php

/**
 * @author Show
 * @Date 2012年10月20日 15:27:58
 * @version 1.0
 * @description:盖章申请表(oa_sale_stampapply) Model层
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
     * 盖章状态
     */
    public function rtStampType_d($status)
    {
        if ($status == 1) {
            return '已盖章';
        } elseif ($status == 0) {
            return '未盖章';
        } else {
            return '已关闭';
        }
    }

    //数据字典字段处理
    public $datadictFieldArr = array(
        'stampExecution'
    );

    /**
     * 重写add_d
     */
    function add_d($object)
    {
        try {
            //数据字典以及数据处理
            $object = $this->processDatadict($object);

            if (!isset($object['ExaStatus'])) {
                $object['ExaStatus'] = WAITAUDIT;
            }
            $newId = parent::add_d($object, true);

            //更新附件关联关系
            $this->updateObjWithFile($newId, $object['orderCode']);

            $this->commit_d();
            return $newId;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 根据主键修改对象
     */
    function edit_d($object)
    {
        //数据字典以及数据处理
        $object = $this->processDatadict($object);
        return parent::edit_d($object, true);
    }

    /**
     * 审批完成跳转
     */
    function workflowCallBack($spid)
    {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getStepInfo($spid);
        $objId = $folowInfo ['objId'];

        $object = $this->get_d($objId);
        if ($object['ExaStatus'] == AUDITED) {
            //如果需要盖章
            //创建数组
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

            // 邮件通知盖章负责人
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
     * 提交后进行的在盖章列表添加信息
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

    //判断是否需要审批，并设置数组
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
            $object['ExaStatus'] = '完成';
            $object['ExaDT'] = day_date;
        }
        return $object;
    }

    function  sendEmail($object, $newId)
    {
        //邮件信息获取
        if (isset($object['email'])) {
            $emailArr = $object['email'];
            unset($object['email']);

            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $object['isSended'] = 1;
            }
        }

        if ($object['stampExecution'] == 'HTGZXZ-01') {
            $stampExecution = '因公';
        } else {
            $stampExecution = '因私';
        }

        //发送邮件 ,当操作为提交时才发送
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
     * 判断合同是否已审核
     * @param $contractId
     * @return int
     */
    function contractIsAudited_d($contractId) {
        $contractDao = new model_contract_contract_contract();
        $contractObj = $contractDao->get_d($contractId);

        // 判断合同是否已审核
        return $contractObj['ExaStatus'] == AUDITED ? 1 : 0;
    }
}