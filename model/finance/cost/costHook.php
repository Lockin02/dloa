<?php

/**
 * @author show
 * @Date 2014年10月15日 14:15:01
 * @version 1.0
 * @description:费用分摊核销记录 Model层
 */
class model_finance_cost_costHook extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_cost_hook";
        $this->sql_map = "finance/cost/costHookSql.php";
        parent::__construct();
    }

    /**
     * hook type
     * @param $k
     * @return string
     */
    function getHookTypeName_d($k) {
        switch ($k) {
            case 1 :
                return '分摊记录';
            case 2 :
                return '其他合同';
            case 3 :
                return '外包合同';
            default :
                return $k;
        }
    }

    /**
     * auto hook
     * @param $object
     * @param bool $isAudit
     * @return boolean
     * @throws $e
     */
    function dealHook_d($object, $isAudit = false) {
        if (!isset($object['costshare'])) throw new Exception('传入的数据中没有分摊信息');
        if (!isset($object['hookObj'])) throw new Exception('传入的数据中没有勾稽对象');

        // init hook detail object
        $hookDetailDao = new model_finance_cost_costHookDetail();

        // init cost share object
        $costShareDao = new model_finance_cost_costshare();

        // init period info
        $periodDao = new model_finance_period_period();

        try {
            $isAdd = true;
            // add hook info
            $periodInfo = $periodDao->periodChange($object['hookObj']['hookDate']);
            if ($object['costshare'][0]['mainId']) {
                $id = $object['costshare'][0]['mainId'];
                $isAdd = false;
                if ($isAudit) {
                    $this->update(array('id' => $id), array(
                        'auditStatus' => '1', 'auditDate' => day_date, 'auditor' => $_SESSION['USERNAME'],
                        'auditorId' => $_SESSION['USER_ID'], 'hookPeriod' => $periodInfo['thisPeriod'],
                        'hookYear' => $periodInfo['thisYear'], 'hookMonth' => $periodInfo['thisMonth']
                    ));
                }
            } else {
                $periodInfo = $periodDao->periodChange($object['hookObj']['hookDate']);
                $inArr = array(
                    'hookPeriod' => $periodInfo['thisPeriod'], 'hookYear' => $periodInfo['thisYear'],
                    'hookMonth' => $periodInfo['thisMonth'], 'createId' => $_SESSION['USER_ID'],
                    'createName' => $_SESSION['USERNAME'], 'createTime' => date('Y-m-d H:i:s')
                );
                if ($isAudit) {
                    $inArr = array_merge($inArr, array(
                        'auditStatus' => '1', 'auditDate' => day_date, 'auditor' => $_SESSION['USERNAME'],
                        'auditorId' => $_SESSION['USER_ID']
                    ));
                }
                $id = $this->add_d($inArr);
            }

            $hookDetail = array();

            $hookType = -1; // it would by the share info's objType
            $costShareIds = array(); // waiting recount share info
            $hookMoney = 0; // act hook money for share form
            foreach ($object['costshare'] as $k => $v) {
                $hookType = $hookType == -1 ? $v['objType'] : $hookType;
                array_push($costShareIds, $v['id']);

                // set costshare list
                $hookArr = array(
                    'hookId' => $v['id'], 'hookCode' => $v['id'], 'hookType' => 1,
                    'hookMoney' => $v['costMoney'], 'mainId' => $id
                );

                if ($v['hookDetailId']) {
                    $hookArr['id'] = $v['hookDetailId'];
                }

                if (isset($v['isDelTag'])) {
                    $hookArr['isDelTag'] = 1;
                } else {
                    $hookMoney = bcadd($hookMoney, $v['costMoney'], 2);
                }

                array_push($hookDetail, $hookArr);
            }

            $hookObj = array(
                'hookId' => $object['hookObj']['hookId'], 'hookCode' => $object['hookObj']['hookCode'],
                'hookType' => $hookType, 'hookMoney' => $hookMoney, 'mainId' => $id
            );

            if (!$isAdd) {
                $obj = $hookDetailDao->find(
                    array('hookId' => $object['hookObj']['hookId'], 'hookType' => $hookType),
                    null,
                    'id'
                );
                $hookObj['id'] = $obj['id'];
            }
            array_push($hookDetail, $hookObj);

            $hookDetailDao->saveDelBatch($hookDetail);

            // recount cost share info
            $costShareDao->recount_d($costShareIds);

            return $hookObj;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * delete hook info
     * @param $hookIds
     * @param $hookType
     * @return true
     * @throws $e
     */
    function deleteHook_d($hookIds, $hookType) {
        // init hook detail object
        $hookDetailDao = new model_finance_cost_costHookDetail();

        // init cost share object
        $costShareDao = new model_finance_cost_costshare();

        try {
            $hookIdArr = explode(',', $hookIds);
            foreach ($hookIdArr as $hookId) {
                // get hook info
                $costShareInfo = $costShareDao->getHookList_d($hookType, null, $hookId);

                // get mainId
                $id = $costShareInfo[0]['mainId'];

                // delete action
                $this->delete(array('id' => $id));
                $hookDetailDao->delete(array('mainId' => $id));
            }

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * un audit hook info
     * @param $hookIds
     * @param $hookType
     * @throws Exception
     * @return bool
     */
    function  unAudit_d($hookIds, $hookType) {
        // init hook detail object
        $hookDetailDao = new model_finance_cost_costHookDetail();

        // init cost share object
        $costShareDao = new model_finance_cost_costshare();

        try {
            $hookIdArr = explode(',', $hookIds);
            foreach ($hookIdArr as $hookId) {
                // get hook info
                $costShareInfo = $costShareDao->getHookList_d($hookType, null, $hookId);

				if (!$costShareInfo) continue;

                // get mainId
                $id = $costShareInfo[0]['mainId'];

                // unAudit update
                $this->update(array('id' => $id), array(
                    'auditStatus' => '0', 'auditDate' => '', 'auditor' => '', 'auditorId' => ''
                ));

                // get hooked share info
                $hookedShareInfo = $hookDetailDao->findAll(array('hookType' => 1, 'mainId' => $id), null, 'hookId');

                $costShareIds = array();
                foreach ($hookedShareInfo as $v) {
                    array_push($costShareIds, $v['hookId']);
                }

                $costShareDao->recount_d($costShareIds);
            }

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取勾稽记录
     * @param $hookId
     * @return string
     */
    function getIds_d($hookId = -1) {
        $rs = $this->_db->getArray("SELECT mainId FROM oa_finance_cost_hook_detail WHERE hookId = " . $hookId);
        if ($rs[0]['mainId']) {
            $mainIdArr = array();
            foreach ($rs as $v) {
                array_push($mainIdArr, $v['mainId']);
            }
            return implode(',', $mainIdArr);
        } else {
            return -1;
        }
    }
}