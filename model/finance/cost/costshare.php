<?php

/**
 * @author show
 * @Date 2014年5月6日 16:12:52
 * @version 1.0
 * @description:公用费用分摊 Model层
 */
class model_finance_cost_costshare extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_finance_cost";
        $this->sql_map = "finance/cost/costshareSql.php";
        parent::__construct();
    }

    //配置报销类型
    private $detailTypeArr = array(
        '1' => '部门费用',
        '2' => '合同项目费用',
        '3' => '研发费用',
        '4' => '售前费用',
        '5' => '售后费用'
    );

    /**
     * 返回费用类型
     * @param $thisVal
     * @param bool $reverse
     * @return mixed
     */
    function getDetailType($thisVal, $reverse = false)
    {
        $tmp = $reverse ? array_flip($this->detailTypeArr) : $this->detailTypeArr;
        if (isset($tmp[$thisVal])) {
            return $tmp[$thisVal];
        } else {
            return $thisVal;
        }
    }

    /**
     * 判断是否含有费用类型
     * @param $thisVal
     * @param bool $reverse
     * @return mixed
     */
    function hasDetailType($thisVal, $reverse = false)
    {
        $tmp = $reverse ? array_flip($this->detailTypeArr) : $this->detailTypeArr;
        return isset($tmp[$thisVal]);
    }

    /**
     * return object type cn
     * @param string $v object type
     * @return string
     */
    function getObjType($v)
    {
        switch ($v) {
            case 1 :
                return '赔偿单';
            case 2 :
                return '其他合同';
            case 3 :
                return '外包合同';
            default;
                return $v;
        }
    }

    /**
     * return audit status
     * @param string $v object type
     * @return string
     */
    function getAuditStatus($v)
    {
        switch ($v) {
            case 0 :
                return '保存';
            case 1 :
                return '已审核';
            case 2 :
                return '未审核';
            case 3 :
                return '撤回';
            default;
                return $v;
        }
    }

    /**
     * return hook status
     * @param string $v object type
     * @return string
     */
    function getHookStatus($v)
    {
        switch ($v) {
            case 0 :
                return '未勾稽';
            case 1 :
                return '已勾稽';
            case 2 :
                return '部分勾稽';
            default;
                return $v;
        }
    }

    /**
     * 添加对象
     */
    function add_d($object)
    {
        $object['company'] = $object['company'] ? $object['company'] : $_SESSION['USER_COM'];
        $object['companyName'] = $object['companyName'] ? $object['companyName'] : $_SESSION['USER_COM_NAME'];
        $object['belongCompany'] = $object['belongCompany'] ? $object['belongCompany'] : $_SESSION['USER_COM'];
        $object['belongCompanyName'] = $object['belongCompanyName'] ? $object['belongCompanyName'] : $_SESSION['USER_COM_NAME'];
        return parent::add_d($object);
    }

    /**
     * 添加对象
     */
    function edit_d($object)
    {
        $object['company'] = $object['company'] ? $object['company'] : $_SESSION['USER_COM'];
        $object['companyName'] = $object['companyName'] ? $object['companyName'] : $_SESSION['USER_COM_NAME'];
        $object['belongCompany'] = $object['belongCompany'] ? $object['belongCompany'] : $_SESSION['USER_COM'];
        $object['belongCompanyName'] = $object['belongCompanyName'] ? $object['belongCompanyName'] : $_SESSION['USER_COM_NAME'];
        return parent::edit_d($object);
    }

    /**
     * update share info effective
     * @param $objId
     * @param $objType
     * @return mixed
     * @throws Exception
     */
    function setDataEffective_d($objId, $objType)
    {
        try {
            return $this->_db->query("UPDATE " . $this->tbl_name . " SET unHookMoney = costMoney, auditStatus = 2"
                . " WHERE objId = " . $objId . " AND objType ='" . $objType . "' AND auditStatus IN(0,3)");
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * update share info invalid
     * @param $objId
     * @param $objType
     * @return mixed
     * @throws Exception
     */
    function setDataInvalid($objId, $objType)
    {
        try {
            return $this->_db->query("UPDATE " . $this->tbl_name . " SET unHookMoney = costMoney, auditStatus = 0"
                . " WHERE objId = " . $objId . " AND objType ='" . $objType . "' AND auditStatus = 2");
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * update share info audited
     * @param $objId
     * @param $objType
     * @param $detailIds
     * @return mixed
     * @throws Exception
     */
    function setDataAudited_d($objId, $objType, $detailIds = null)
    {
        $sql = "UPDATE " . $this->tbl_name . " SET unHookMoney = costMoney, auditStatus = 1, auditDate = '"
            . day_date . "',auditor = '" . $_SESSION['USERNAME'] . "',auditorId = '" . $_SESSION['USER_ID']
            . "' WHERE objId = " . $objId . " AND objType ='" . $objType . "' AND auditStatus = 2";
        if ($detailIds) {
            $sql .= " AND id IN($detailIds)";
        }
        try {
            return $this->_db->query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * update share info un audit
     * @param $objId
     * @param $objType
     * @return mixed
     * @throws Exception
     */
    function setDataUnAudited_d($objId, $objType)
    {
        try {
            return $this->_db->query("UPDATE " . $this->tbl_name . " SET unHookMoney = 0, auditStatus = 2, auditDate = NULL"
                . ",auditor = '',auditorId = '' WHERE objId = " . $objId . " AND objType ='" . $objType
                . "' AND auditStatus = 1");
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * audit cost share info
     * @param $object
     * @return mixed
     */
    function audit_d($object)
    {
        try {
            $this->start_d();

            // update data
            $object['detail'] = util_arrayUtil::setArrayFn(
                array('objId' => $object['objId'], 'objCode' => $object['objCode'], 'objType' => $object['objType'],
                    'company' => $object['company'],
                    'companyName' => util_jsonUtil::is_utf8($object['companyName']) ?
                        util_jsonUtil::iconvUTF2GB($object['companyName']) : $object['companyName'],
                    'supplierName' => util_jsonUtil::is_utf8($object['supplierName']) ?
                        util_jsonUtil::iconvUTF2GB($object['supplierName']) : $object['supplierName'],
                    'currency' => util_jsonUtil::is_utf8($object['currency']) ?
                        util_jsonUtil::iconvUTF2GB($object['currency']) : $object['currency']
                ),
                $object['detail']
            );

            // 工程项目数据
            $shareProject = array();

            // 针对新增的数组，添加一个已审核的状态
            foreach ($object['detail'] as $k => $v) {
                if (!$v['id'])
                    $object['detail'][$k]['auditStatus'] = 2;

                if ($v['projectId'] && !isset($shareProject[$v['projectId']][$v['inPeriod']])) {
                    $shareProject[$v['projectId']][$v['inPeriod']] = 1;
                }
            }

            $saveData = $this->saveDelBatch($object['detail']);

            $detailIds = array();
            foreach ($saveData as $v) {
                // 审核页面增加了一个勾选，如果选中，才对这条数据做更新
                if ($v['check']) {
                    $detailIds[] = $v['id'];
                }
            }

            if (!empty($detailIds))
                $this->setDataAudited_d($object['objId'], $object['objType'], implode(',', $detailIds));

            // 如果匹配到项目决算，则进行决算更新
            if (!empty($shareProject)) {
                // 机票决算更新
                $esmfieldRecordDao = new model_engineering_records_esmfieldrecord();

                // 循环更新
                foreach ($shareProject as $k => $v) {
                    foreach ($v as $ki => $vi) {
                        $period = explode('.', $ki);
                        $esmfieldRecordDao->businessFeeUpdate_d('payables', $period[0], $period[1], array(
                            'projectId' => $k
                        ));
                    }
                }
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * quick audit
     * @param $ids
     * @return mixed
     */
    function quickAudit_d($ids)
    {
        // 加入发审核的处理
        $this->searchArr = array(
            'ids' => $ids
        );
        $data = $this->list_d();

        try {
            $this->start_d();

            // 工程项目数据
            $shareProject = array();

            // 针对新增的数组，添加一个已审核的状态
            foreach ($data as $v) {
                if ($v['projectId'] && !isset($shareProject[$v['projectId']][$v['inPeriod']])) {
                    $shareProject[$v['projectId']][$v['inPeriod']] = 1;
                }
            }

            $this->_db->query("UPDATE " . $this->tbl_name . " SET unHookMoney = costMoney, auditStatus = 1, auditDate = '"
                . day_date . "',auditor = '" . $_SESSION['USERNAME'] . "',auditorId = '" . $_SESSION['USER_ID']
                . "' WHERE id IN(" . $ids . ") AND auditStatus = 2");

            // 如果匹配到项目决算，则进行决算更新
            if (!empty($shareProject)) {
                // 机票决算更新
                $esmfieldRecordDao = new model_engineering_records_esmfieldrecord();

                // 循环更新
                foreach ($shareProject as $k => $v) {
                    foreach ($v as $ki => $vi) {
                        $period = explode('.', $ki);
                        $esmfieldRecordDao->businessFeeUpdate_d('payables', $period[0], $period[1], array(
                            'projectId' => $k
                        ));
                    }
                }
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * cancel cost share info audit
     * @param $ids
     * @return mixed
     */
    function unAudit_d($ids)
    {
        // 加入发审核的处理
        $this->searchArr = array(
            'ids' => $ids
        );
        $data = $this->list_d();

        try {
            $this->start_d();

            // 工程项目数据
            $shareProject = array();

            // 针对新增的数组，添加一个已审核的状态
            foreach ($data as $v) {
                if ($v['projectId'] && !isset($shareProject[$v['projectId']][$v['inPeriod']])) {
                    $shareProject[$v['projectId']][$v['inPeriod']] = 1;
                }
            }

            $this->_db->query("UPDATE " . $this->tbl_name . " SET unHookMoney = 0, auditStatus = 2, auditDate = NULL"
                . ", auditor = '', auditorId = '' WHERE id IN(" . $ids . ") AND auditStatus = 1");

            // 如果匹配到项目决算，则进行决算更新
            if (!empty($shareProject)) {
                // 机票决算更新
                $esmfieldRecordDao = new model_engineering_records_esmfieldrecord();

                // 循环更新
                foreach ($shareProject as $k => $v) {
                    foreach ($v as $ki => $vi) {
                        $period = explode('.', $ki);
                        $esmfieldRecordDao->businessFeeUpdate_d('payables', $period[0], $period[1], array(
                            'projectId' => $k
                        ));
                    }
                }
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * back cost share info
     * @param $objId
     * @param $objType
     * @return mixed
     */
    function back_d($objId, $objType)
    {
        return $this->_db->query("UPDATE " . $this->tbl_name . " SET unHookMoney = 0, auditStatus = 3
            WHERE objId = " . $objId . " AND objType = " . $objType . " AND auditStatus = 2");
    }

    /**
     * quickly back cost share info
     * @param $ids
     * @return mixed
     */
    function quickBack_d($ids)
    {
        return $this->_db->query("UPDATE " . $this->tbl_name . " SET unHookMoney = 0, auditStatus = 3
            WHERE id IN(" . $ids . ") AND auditStatus = 2");
    }

    /**
     * delete cost share info
     * @param $objId
     * @param $objType
     * @throws Exception
     */
    function deleteByObjInfo_d($objId, $objType)
    {
        try {
            return $this->delete(array('objId' => $objId, 'objType' => $objType));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * update head department
     */
    function updateDept_d()
    {

        // 匹配出没有二级部门的数据
        $data = $this->_db->getArray("SELECT id,belongDeptId FROM oa_finance_cost
            WHERE headDeptName='NULL' OR headDeptName='【新增】' OR headDeptName = '' OR headDeptName IS NULL");

        if (!empty($data)) {
            // 二级部门映射关系
            $otherDatas = new model_common_otherdatas();
            $deptsMap = $otherDatas->getDeptSMap_d();

            foreach ($data as $v) {
                if (isset($deptsMap[$v['belongDeptId']])) {
                    $this->_db->query("UPDATE oa_finance_cost
                        SET
                            headDeptId = '{$deptsMap[$v['belongDeptId']]['DEPT_ID']}',
                            headDeptName = '{$deptsMap[$v['belongDeptId']]['DEPT_NAME']}'
                        WHERE id = {$v['id']}
                    ");
                }
            }
        }


//        return $this->_db->query("UPDATE
//            oa_finance_cost c
//            INNER JOIN (
//                SELECT
//                    deptIdS,deptNameS,belongDeptId
//                FROM oa_hr_personnel WHERE belongDeptId <> 0 AND deptIdS <> 0 GROUP BY belongDeptId
//            ) d ON c.belongDeptId = d.belongDeptId
//            SET c.headDeptId = d.deptIdS,c.headDeptName = d.deptNameS
//        WHERE c.headDeptId IS NULL OR c.headDeptName = '【新增】' OR c.headDeptName = ''");
    }

    /**
     * recount share info
     * @param $idArr
     * @return true
     * @throws $e
     */
    function recount_d($idArr)
    {
        $ids = implode(',', $idArr);
        $hookTime = date('Y-m-d H:i:s');

        try {
            // update hook money
            $this->_db->query("UPDATE
                oa_finance_cost	c
                LEFT JOIN
                (
                    SELECT d.hookId,SUM(d.hookMoney) AS hookMoney
                    FROM oa_finance_cost_hook_detail d INNER JOIN oa_finance_cost_hook h ON d.mainId = h.id AND h.auditStatus = 1
                    WHERE d.hookType = 1 AND d.hookId IN(" . $ids . ") GROUP BY d.hookId
                ) m
                    ON c.id = m.hookId
                SET
                    c.hookMoney = IF(m.hookMoney IS NULL,0,m.hookMoney),
                    c.unHookMoney = c.costMoney - IF(m.hookMoney IS NULL,0,m.hookMoney)
            WHERE c.id IN(" . $ids . ");");

            // update hook status
            $this->_db->query("UPDATE
                oa_finance_cost	c
                SET
                    c.hookStatus = IF(c.hookMoney IS NULL OR c.hookMoney = 0,0,IF(c.unHookMoney = 0,1,2)),
                    c.hookTime = '" . $hookTime . "'
            WHERE c.id IN(" . $ids . ");");

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取项目的分摊金额
     * @param $projectId
     * @return mixed
     */
    function getProjectCostMoney_d($projectId)
    {
        $objArr = $this->_db->getArray("SELECT
				GROUP_CONCAT(CAST(objId AS CHAR)) AS objId, objType
			FROM oa_finance_cost
			WHERE
				auditStatus = 1 AND isTemp = 0 AND isDel = 0
				AND projectType = 'esm' AND projectId = " . $projectId . " AND detailType IN(2,4)
			GROUP BY objType");
        if ($objArr) {
            // 数据过滤条件配置 - 这个方法需要注意的是返回值必须是id串
            $filterArr = array(
                2 => array(
                    'class' => 'model_contract_other_other',
                    'func' => 'getEffectiveObjIds_d'
                )
            );

            // 初始化一个条件数组
            $condition = array();
            // 循环加载过滤条件
            foreach ($objArr as $v) {
                if (isset($filterArr[$v['objType']])) {
                    $objDao = new $filterArr[$v['objType']]['class']();
                    $objIds = $objDao->$filterArr[$v['objType']]['func']($v['objId']);

                    if ($objIds) {
                        $condition[] = "(objId IN(" . $objIds . ") AND objType = " . $v['objType'] . ")";
                    }
                }
            }

            if (empty($condition)) {
                return null;
            } else {
                // 如果确实有条件，则拼装这个条件
                $expandCondition = "AND ( " . implode(' OR ', $condition) . " ) ";
                return $this->_db->getArray("SELECT
					projectId,projectCode,parentTypeId,parentTypeName,costTypeId,costTypeName,SUM(costMoney) AS costMoney
				FROM oa_finance_cost
				WHERE
					auditStatus = 1 AND isTemp = 0  AND isDel = 0
					AND projectType = 'esm' AND projectId = " . $projectId . " AND detailType IN(2,4)
					$expandCondition
				GROUP BY projectId,costTypeId");
            }
        } else {
            return null;
        }
    }

    /**
     * get share info money
     * @param $objId
     * @param $objType
     * @return int
     */
    function getCostMoney_d($objId, $objType)
    {
        $this->searchArr = array(
            'objId' => $objId,
            'objType' => $objType
        );
        $data = $this->list_d('select_default_sum');
        return $data[0]['costMoney'] ? $data[0]['costMoney'] : 0;
    }

    /**
     * 获取源单金额
     * @param $objId
     * @param $objType
     * @return int
     */
    function getObjMoney_d($objId, $objType)
    {
        $func_array = array(
            2 => array(
                'class' => 'model_contract_other_other',
                'func' => 'getObjMoney_d'
            )
        );

        // 如果没有发现对应的类型，那么返回0
        if (!isset($func_array[$objType])) {
            return 0;
        } else {
            $objClass = new $func_array[$objType]['class']();
            return $objClass->$func_array[$objType]['func']($objId);
        }
    }

    /**
     * @param $objType
     * @param null $objId
     * @param null $hookId
     * @return mixed
     */
    function getHookList_d($objType, $objId = null, $hookId = null)
    {
        if (empty($hookId)) {
            $sql = "SELECT c.id ,c.objId ,c.objType ,c.companyName ,c.company ,c.shareObjType ,c.inPeriod,
                c.belongPeriod ,c.detailType ,c.belongDeptName ,c.belongDeptId ,c.belongId ,c.belongName,
                c.chanceCode ,c.chanceId ,c.province ,c.customerType ,c.contractCode ,c.contractId ,c.projectId,
                c.projectCode ,c.projectName ,c.projectType ,c.parentTypeId ,c.shareObjType AS defaultShareObjType,
                c.parentTypeName ,c.costTypeId ,c.costTypeName ,c.hookStatus,
                c.hookTime ,c.auditStatus ,c.auditDate, c.customerId, c.customerName, c.objCode, c.belongCompanyName,
                c.isTemp, c.originalId, c.id AS oldId, c.headDeptId, c.headDeptName,
                c.costMoney - if(h.hookMoney IS NULL,0,h.hookMoney) AS costMoney,
                c.costMoney - IF(h.hookMoney IS NULL,0,h.hookMoney) AS actCostMoney,
                c.hookMoney, c.unHookMoney, c.module, c.moduleName, c.feeManId, c.feeMan, c.salesAreaId, c.salesArea,
                c.currency
            FROM oa_finance_cost c
            LEFT JOIN
            (
                SELECT d.hookId,SUM(d.hookMoney) AS hookMoney FROM oa_finance_cost_hook_detail d
                WHERE d.hookType = 1 GROUP BY d.hookId
            ) h ON c.id = h.hookId
            WHERE c.isTemp = 0 AND c.isDel = 0 AND c.objType = " . $objType . " AND c.objId = " . $objId
                . " HAVING costMoney > 0 ";
        } elseif (empty($objId)) {
            $sql = "SELECT c.id ,c.objId ,c.objType ,c.companyName ,c.company ,c.shareObjType ,c.inPeriod,
                c.belongPeriod ,c.detailType ,c.belongDeptName ,c.belongDeptId ,c.belongId ,c.belongName,
                c.chanceCode ,c.chanceId ,c.province ,c.customerType ,c.contractCode ,c.contractId ,c.projectId,
                c.projectCode ,c.projectName ,c.projectType ,c.parentTypeId ,c.shareObjType AS defaultShareObjType,
                c.parentTypeName ,c.costTypeId ,c.costTypeName ,h.hookMoney AS costMoney,c.hookStatus,
                c.hookTime ,c.auditStatus ,c.auditDate, c.customerId, c.customerName, c.objCode, c.belongCompanyName,
                c.isTemp, c.originalId, c.id AS oldId, c.headDeptId, c.headDeptName,h.mainId, h.hookId,
                h.hookDetailId, h.auditStatus, c.module, c.moduleName, c.feeManId, c.feeMan, c.salesAreaId, c.salesArea,
                c.currency
            FROM oa_finance_cost c
            LEFT JOIN
            (
                SELECT d.hookId,d.mainId,d.id AS hookDetailId,d.hookMoney,h.auditStatus FROM oa_finance_cost_hook_detail d
                    LEFT JOIN oa_finance_cost_hook h ON d.mainId = h.id
                WHERE d.hookType = 1
            ) h ON c.id = h.hookId
            WHERE c.isTemp = 0 AND c.isDel = 0
                AND h.mainId IN( SELECT mainId FROM oa_finance_cost_hook_detail WHERE hookId = " . $hookId
                . " AND hookType = " . $objType . ")"
                . " AND c.objType = " . $objType;
        }
        return isset($sql) ? $this->_db->getArray($sql) : false;
    }

    /**
     * get can hook info
     * @param $hookType
     * @param null $objId
     * @param null $hookId
     * @return mixed
     */
    function getHookSelectList_d($hookType, $objId = null, $hookId = null)
    {
        if (empty($hookId)) {
            $sql = "SELECT c.id ,c.objId ,c.objType ,c.companyName ,c.company ,c.shareObjType ,c.inPeriod,
                c.belongPeriod ,c.detailType ,c.belongDeptName ,c.belongDeptId ,c.belongId ,c.belongName,
                c.chanceCode ,c.chanceId ,c.province ,c.customerType ,c.contractCode ,c.contractId ,c.projectId,
                c.projectCode ,c.projectName ,c.projectType ,c.parentTypeId ,c.shareObjType AS defaultShareObjType,
                c.parentTypeName ,c.costTypeId ,c.costTypeName ,c.hookStatus,
                c.hookTime ,c.auditStatus ,c.auditDate, c.customerId, c.customerName, c.objCode, c.belongCompanyName,
                c.isTemp, c.originalId, c.id AS oldId, c.headDeptId, c.headDeptName,
                c.costMoney - if(h.hookMoney IS NULL,0,h.hookMoney) AS costMoney,
                c.costMoney - IF(h.hookMoney IS NULL,0,h.hookMoney) AS actCostMoney,
                c.hookMoney, c.unHookMoney ,c.module, c.moduleName, c.feeManId, c.feeMan, c.salesAreaId, c.salesArea,
                c.currency
            FROM oa_finance_cost c
            LEFT JOIN
            (
                SELECT d.hookId,SUM(d.hookMoney) AS hookMoney FROM oa_finance_cost_hook_detail d
                WHERE d.hookType = 1 GROUP BY d.hookId
            ) h ON c.id = h.hookId
            WHERE c.isTemp = 0 AND c.isDel = 0 AND c.auditStatus = 1 AND c.objType = " . $hookType .
                " AND c.objId = " . $objId . " HAVING costMoney <> 0 ";
        } elseif (empty($objId)) {
            // 核销记录
            $costHookDetailDao = new model_finance_cost_costHookDetail();
            // 先查核销序列号
            $costHookDetailInfo = $costHookDetailDao->find(array('hookId' => $hookId, 'hookType' => $hookType), null,
                'mainId');
            if (!$costHookDetailInfo) $costHookDetailInfo['mainId'] = -1;
            // 在查勾稽的id
            $costHookDetailCostList =
                $costHookDetailDao->findAll(array('mainId' => $costHookDetailInfo['mainId'], 'hookType' => 1), null, 'hookId');
            $hookIdArr = array();
            if ($costHookDetailCostList) {
                foreach ($costHookDetailCostList as $v) {
                    array_push($hookIdArr, $v['hookId']);
                }
            } else {
                array_push($hookIdArr, -1);
            }
            $sql = "SELECT c.id ,c.objId ,c.objType ,c.companyName ,c.company ,c.shareObjType ,c.inPeriod,
                c.belongPeriod ,c.detailType ,c.belongDeptName ,c.belongDeptId ,c.belongId ,c.belongName,
                c.chanceCode ,c.chanceId ,c.province ,c.customerType ,c.contractCode ,c.contractId ,c.projectId,
                c.projectCode ,c.projectName ,c.projectType ,c.parentTypeId ,c.shareObjType AS defaultShareObjType,
                c.parentTypeName ,c.costTypeId ,c.costTypeName ,c.hookStatus,
                c.hookTime ,c.auditStatus ,c.auditDate, c.customerId, c.customerName, c.objCode, c.belongCompanyName,
                c.isTemp, c.originalId, c.id AS oldId, c.headDeptId, c.headDeptName,
                c.costMoney - IF(h.hookMoney IS NULL,0,h.hookMoney) AS actCostMoney,
                c.hookMoney, c.unHookMoney, c.module, c.moduleName, c.feeManId, c.feeMan, c.salesAreaId, c.salesArea,
                c.currency
            FROM oa_finance_cost c
            LEFT JOIN
            (
                SELECT d.hookId,SUM(d.hookMoney) AS hookMoney FROM oa_finance_cost_hook_detail d
                WHERE d.hookType = 1 AND d.mainId <> " . $costHookDetailInfo['mainId'] . " GROUP BY d.hookId
            ) h ON c.id = h.hookId
            WHERE c.isTemp = 0 AND c.isDel = 0 AND c.auditStatus = 1 AND c.objType = " . $hookType
                . " AND c.objId IN(SELECT objId FROM oa_finance_cost WHERE id IN(" . implode(',', $hookIdArr) . "))"
                . " AND c.hookStatus != 1";
        }
        return isset($sql) ? $this->_db->getArray($sql) : false;
    }

    /**
     * get page sql
     * @param $condition
     * @param bool $companyLimit 公司权限处理，默认为true
     * @param bool $isCountSql 是否获取统计脚本，默认为false
     * @return string
     */
    function getPageSql_d($condition, $companyLimit = true, $isCountSql = false)
    {
        if ($condition['periodNo']) {
            $periodCondition = " AND h.hookPeriod = '" . $condition['periodNo'] . "'";
        }
        //公司权限处理
        if ($companyLimit) {
            //由于oa_finance_cost没有businessBelong,formBelong字段,这边单独做处理
            $limitStr = isset($this->this_limit['公司权限']) && !empty($this->this_limit['公司权限']) ? $this->this_limit['公司权限'] : $_SESSION['Company'];
            if (strpos($limitStr, ';;') === false) {# 如果含有所有权限,跳过此处理
                $companyCondition = " AND c.company IN(" . util_jsonUtil::strBuild($limitStr) . ")";
            }
        }
        if ($isCountSql) {
            return "SELECT '总计' AS companyName, SUM(c.costMoney) AS costMoney,
                    SUM(c.hookMoney) AS hookMoney,
                    SUM(c.unHookMoney) AS unHookMoney,
                    SUM(IFNULL(h.hookMoney, 0)) AS thisMonthHookMoney
                from oa_finance_cost c
                    LEFT JOIN
                    (
                        SELECT d.hookId,SUM(d.hookMoney) AS hookMoney FROM oa_finance_cost_hook_detail d
                            LEFT JOIN oa_finance_cost_hook h ON d.mainId = h.id
                        WHERE h.auditStatus = 1 AND d.hookType = 1 $periodCondition
                        GROUP BY d.hookId
                    ) h ON c.id = h.hookId
                WHERE c.isTemp = 0 AND c.isDel = 0 $companyCondition";
        } else {
            return "SELECT c.id ,c.objId ,c.objType ,c.companyName ,c.company ,c.shareObjType ,c.inPeriod,
                    c.belongPeriod ,c.detailType ,c.belongDeptName ,c.belongDeptId ,c.belongId ,c.belongName,c.supplierName,
                    c.chanceCode ,c.chanceId ,c.province ,c.customerType ,c.contractCode ,c.contractId ,c.projectId,
                    c.projectCode ,c.projectName ,c.projectType ,c.parentTypeId ,c.shareObjType AS defaultShareObjType,
                    c.parentTypeName ,c.costTypeId ,c.costTypeName ,c.costMoney ,c.hookMoney ,c.unHookMoney ,c.hookStatus,
                    c.hookTime ,c.auditStatus ,c.auditDate, c.customerId, c.customerName, c.objCode, c.belongCompanyName,
                    c.isTemp, c.originalId, c.id AS oldId, c.headDeptId, c.headDeptName, c.auditor, c.auditorId,
                    if(h.hookMoney IS NULL,0,h.hookMoney) AS thisMonthHookMoney,c.module,c.moduleName,c.feeManId,
                    c.feeMan,c.salesAreaId,c.salesArea,c.contractName,c.currency
                from oa_finance_cost c
                    LEFT JOIN
                    (
                        SELECT d.hookId,SUM(d.hookMoney) AS hookMoney FROM oa_finance_cost_hook_detail d
                            LEFT JOIN oa_finance_cost_hook h ON d.mainId = h.id
                        WHERE h.auditStatus = 1 AND d.hookType = 1 $periodCondition
                        GROUP BY d.hookId
                    ) h ON c.id = h.hookId
                WHERE c.isTemp = 0 AND c.isDel = 0 $companyCondition";
        }
    }

    /**
     * 获取分摊信息 - 包括已分摊的总额costMoney、未勾稽总额unHookMoney、勾稽金额hookMoney
     * @param $objId
     * @param $objType
     * @return array
     */
    function getShareInfo_d($objId, $objType)
    {
        $this->searchArr = array(
            'objId' => $objId,
            'objType' => $objType
        );
        $data = $this->list_d('select_default_sum');
        if (empty($data)) {
            return false;
        } else {
            return $data[0];
        }
    }

    /**
     * 获取分摊数据的接口
     * @param $objId
     * @param $objType
     * @return array|bool
     */
    function getShareList_d($objId, $objType)
    {
        $this->searchArr = array(
            'objId' => $objId,
            'objType' => $objType
        );
        $data = $this->list_d('select_default_sum');
        if (empty($data)) {
            return false;
        } else {
            return $data;
        }
    }

    /**
     * 有未审核的分摊记录时，通知相关人员
     * @param $objId
     * @param $objCode
     * @param $objType
     */
    function sendWaitingAuditMail_d($objId, $objCode, $objType)
    {
        if ($this->find(array('objId' => $objId, 'objType' => $objType, 'auditStatus' => 2), null, 'id'))
            $this->mailDeal_d('costShareWaitingAudit', null, array('objCode' => $objCode));
    }

    /**
     * 自动设置分摊对象
     * @param $data
     * @param $moneyNoTax
     * @param $taxPoint
     * @return string
     */
    function setShareObjType_d($data, $moneyNoTax = 0, $taxPoint = 0)
    {
        // 获取最大索引
        $maxIndex = count($data) - 1;

        foreach ($data as $k => $v) {
            // 如果税率不为0，则需要做税费处理
            if ($taxPoint != 0) {
                $data[$k]['costMoney'] = $k == $maxIndex ? $moneyNoTax : round(bcdiv($v['costMoney'], bcdiv(100 + $taxPoint, 100, 6), 6), 2);
                $moneyNoTax = bcsub($moneyNoTax, $v['costMoney'], 2);
            }

            switch ($v['detailType']) {
                case '部门费用' :
                    $data[$k]['detailType'] = '1';
                case '1' :
                    $data[$k]['shareObjType'] = 'FTDXLX-01';
                    break;
                case '合同项目费用' :
                    $data[$k]['detailType'] = '2';
                case '2' :
                    $data[$k]['shareObjType'] = 'FTDXLX-02';
                    break;
                case '研发费用' :
                    $data[$k]['detailType'] = '3';
                case '3' :
                    $data[$k]['shareObjType'] = 'FTDXLX-10';
                    break;
                case '售前费用' :
                    $data[$k]['detailType'] = '4';
                case '4' :
                    if ($v['projectCode']) {
                        $data[$k]['shareObjType'] = 'FTDXLX-05';
                    } else if ($v['chanceCode']) {
                        $data[$k]['shareObjType'] = 'FTDXLX-06';
                    } else if ($v['customerName']) {
                        $data[$k]['shareObjType'] = 'FTDXLX-07';
                    } else {
                        $data[$k]['shareObjType'] = 'FTDXLX-08';
                    }
                    break;
                case '售后费用' :
                    $data[$k]['detailType'] = '5';
                case '5' :
                    $data[$k]['shareObjType'] = 'FTDXLX-09';
                    break;
            }
        }
        return $data;
    }

    /**
     * 数据导入
     */
    function importExcel_d($unSltDeptFilter = '', $extInfo = array())
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $datadictDao = new model_system_datadict_datadict();
        $datadictArrCustomerType = $datadictDao->getDataDictList_d('KHLX', array('isUse' => 0));
        $datadictArr = $datadictDao->getDataDictList_d('FTDXLX', array('isUse' => 0), true);
        // 所属板块
        $moduleArr = $datadictDao->getDataDictList_d('HTBK', array('isUse' => 0), true);
        // 公司数据
        $branchDao = new model_deptuser_branch_branch();
        $branchArr = $branchDao->getCompany_d();
        $branchArrReverse = array_flip($branchArr);
        // 当前财务周期
        $periodDao = new model_finance_period_period();
        $periodArr = $periodDao->rtThisPeriod_d(1, 'cost');
        // 费用类型
        $costTypeDao = new model_finance_expense_costtype();
        $costTypeArr = $costTypeDao->getCostTypeLeafList_d('costTypeName');
        // 部门
        $deptDao = new model_deptuser_dept_dept();
        $deptArr = $deptDao->getDeptList_d();
        // 工程项目
        $esmProjectDao = new model_engineering_project_esmproject();
        $esmProjectArr = array();
        // 有效的项目状态
        $projectStatus = array('GCXMZT02', 'GCXMZT04', 'GCXMZT00');
        $projectStatus1 = array('GCXMZT02', 'GCXMZT04');// pms2600 只允许在建,完工状态的
        // 合同项目
        $conProjectDao = new model_contract_conproject_conproject();
        // 试用项目
        $trialProjectDao = new model_projectmanagent_trialproject_trialproject();
        $trialProjectArr = array();
        // 商机
        $chanceDao = new model_projectmanagent_chance_chance();
        $chanceArr = array();
        // 客户
        $customerDao = new model_customer_customer_customer();
        $customerArr = array();
        // 合同
        $contractDao = new model_contract_contract_contract();
        $contractArr = array();
        // 报销销售部门处理
        $saleDeptArr = explode(',', expenseSaleDeptId);
        // 区域负责人
        $regionDao = new model_system_region_region();
        // 用户
        $userDao = new model_deptuser_user_user();

        // 判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name, 1);
            spl_autoload_register("__autoload");
            $titleRow = $excelData[0];
            unset($excelData[0]);

            if (!empty($excelData)) {
                //行数组循环
                foreach ($excelData as $key => $val) {
                    if (empty($val[0]) || empty($val[3])) {
                        unset($excelData[$key]);
                    } else {
                        // 格式化数组
                        $excelData[$key] = $this->formatArray_d($val, $titleRow);
                        // 初始化待赋值字段，防止前台js出现undefined的情况，By weijb 2015.11.25
                        $excelData[$key]['module'] = '';
                        $excelData[$key]['belongCompany'] = '';
                        $excelData[$key]['belongDeptId'] = '';
                        $excelData[$key]['belongDeptName'] = '';
                        $excelData[$key]['belongId'] = '';
                        $excelData[$key]['belongName'] = '';
                        $excelData[$key]['headDeptId'] = '';
                        $excelData[$key]['headDeptName'] = '';
                        $excelData[$key]['projectId'] = '';
                        $excelData[$key]['projectCode'] = '';
                        $excelData[$key]['projectName'] = '';
                        $excelData[$key]['projectType'] = '';
                        $excelData[$key]['chanceId'] = '';
                        $excelData[$key]['chanceCode'] = '';
                        $excelData[$key]['customerId'] = '';
                        $excelData[$key]['customerName'] = '';
                        $excelData[$key]['customerType'] = '';
                        $excelData[$key]['province'] = '';
                        $excelData[$key]['contractId'] = '';
                        $excelData[$key]['contractCode'] = '';
                        $excelData[$key]['contractName'] = '';
                        $excelData[$key]['costTypeId'] = '';
                        $excelData[$key]['parentTypeId'] = '';
                        $excelData[$key]['parentTypeName'] = '';
                        $excelData[$key]['salesArea'] = '';
                        $excelData[$key]['salesAreaId'] = '';
                        $excelData[$key]['feeMan'] = $_SESSION['USERNAME'];
                        $excelData[$key]['feeManId'] = $_SESSION['USER_ID'];

                        // 所属板块
                        if ($excelData[$key]['moduleName'] && $moduleArr[$excelData[$key]['moduleName']]) {
                            $excelData[$key]['module'] = $moduleArr[$excelData[$key]['moduleName']];
                        } else {
                            $excelData[$key]['result'] = '错误的所属板块名称';
                            continue;
                        }

                        // 归属公司
                        if ($excelData[$key]['belongCompanyName'] && $branchArrReverse[$excelData[$key]['belongCompanyName']]) {
                            $excelData[$key]['belongCompany'] = $branchArrReverse[$excelData[$key]['belongCompanyName']];
                        } else {
                            $excelData[$key]['result'] = '错误的归属公司名称';
                            continue;
                        }

                        // 费用入账期间
                        $excelData[$key]['inPeriod'] = str_replace(".0",".",$excelData[$key]['inPeriod']);
                        if (!$excelData[$key]['inPeriod'] ||
                            !$periodDao->checkPeriodAllow_d($excelData[$key]['inPeriod'], $periodArr['thisPeriod'])
                        ) {

                            $excelData[$key]['result'] = '错误的费用入账期间';
                            continue;
                        }

                        // 费用类型
                        if ($excelData[$key]['detailType'] && $this->hasDetailType($excelData[$key]['detailType'], true)) {
                            $excelData[$key]['detailType'] = $this->getDetailType($excelData[$key]['detailType'], true);
                        } else {
                            $excelData[$key]['result'] = '错误的费用类型';
                            continue;
                        }

                        // 分摊对象
                        if ($excelData[$key]['shareObjType'] && $datadictArr[$excelData[$key]['shareObjType']]) {
                            $excelData[$key]['shareObjType'] = $datadictArr[$excelData[$key]['shareObjType']];
                            // 分摊对象
                            switch ($excelData[$key]['shareObjType']) {
                                case 'FTDXLX-01' : // 部门费用

                                    // 拆解内容
                                    $costShareObjExtends = explode('/', $excelData[$key]['costShareObjExtends']);

                                    // 部门信息处理
                                    if ($deptArr[$costShareObjExtends[0]]) {
                                        $deptId = $deptArr[$costShareObjExtends[0]]['DEPT_ID'];
                                        if ($unSltDeptFilter != '') {
                                            $unSltDeptFilterArr = explode(",", $unSltDeptFilter);
                                            if (in_array($deptId, $unSltDeptFilterArr)) {
                                                $excelData[$key]['result'] = '此部门 【' . $costShareObjExtends[0] . "】禁止选择";
                                                continue;
                                            } else {
                                                $excelData[$key]['belongDeptName'] = $costShareObjExtends[0];
                                                $excelData[$key]['belongDeptId'] = $deptId;
                                            }
                                        } else {
                                            $excelData[$key]['belongDeptName'] = $costShareObjExtends[0];
                                            $excelData[$key]['belongDeptId'] = $deptId;
                                        }
                                    } else {
                                        $excelData[$key]['result'] = '错误的部门';
                                        continue;
                                    }

                                    // 工作组匹配
                                    if (isset($costShareObjExtends[1]) && !empty($costShareObjExtends[1])) {
                                        $excelData[$key]['projectCode'] = $costShareObjExtends[1];
                                        $excelData[$key]['projectType'] = 'esm';

                                        // 项目信息匹配
                                        if (!isset($esmProjectArr[$excelData[$key]['projectCode']])) {
                                            $esmProjectInfo = $esmProjectDao->find(
                                                array('projectCode' => $excelData[$key]['projectCode']),
                                                null,
                                                'id,projectName,deptId,deptName,status'
                                            );

                                            if ($esmProjectInfo) {
                                                $esmProjectArr[$excelData[$key]['projectCode']] = $esmProjectInfo;
                                            } else {
                                                $excelData[$key]['result'] = '错误的项目编号';
                                                continue;
                                            }
                                        }
                                        // 项目状态验证，只允许在建、完工的项目
                                        if (!in_array($esmProjectArr[$excelData[$key]['projectCode']]['status'], $projectStatus1)) {
//                                            $excelData[$key]['result'] = '项目状态错误，只允许选择在建、完工、逾期未关闭的项目';
                                            $excelData[$key]['result'] = '项目状态错误，只允许选择在建、完工的项目';// PMS2600
                                            continue;
                                        }
                                        $excelData[$key]['projectId'] = $esmProjectArr[$excelData[$key]['projectCode']]['id'];
                                        $excelData[$key]['projectName'] = $esmProjectArr[$excelData[$key]['projectCode']]['projectName'];
                                    }

                                    break;
                                case 'FTDXLX-02' : // 合同工程项目费用
                                case 'FTDXLX-05' : // 售前费用 - 试用项目
                                    // 拆解内容
                                    $costShareObjExtends = explode('/', $excelData[$key]['costShareObjExtends']);
                                    $excelData[$key]['projectCode'] = $costShareObjExtends[0];
                                    $excelData[$key]['projectType'] = 'esm';
                                    // 项目信息匹配
                                    if (!isset($esmProjectArr[$excelData[$key]['projectCode']])) {
                                        $esmProjectInfo = $esmProjectDao->find(
                                            array('projectCode' => $excelData[$key]['projectCode']),
                                            null,
                                            'id,projectName,deptId,deptName,contractId,status'
                                        );
                                        if ($esmProjectInfo) {
                                            // 项目状态验证，只允许在建、完工的项目
                                            if (!in_array($esmProjectInfo['status'], $projectStatus1)) {
//                                                $excelData[$key]['result'] = '项目状态错误，只允许选择在建、完工、逾期未关闭的项目';
                                                $excelData[$key]['result'] = '项目状态错误，只允许选择在建、完工的项目';// PMS2600
                                                continue;
                                            }
                                            $esmProjectArr[$excelData[$key]['projectCode']] = $esmProjectInfo;
                                        } else {
                                            // 如果是合同项目，匹配匹配产品项目的部分
                                            if ('FTDXLX-02' == $excelData[$key]['shareObjType']) {
                                                $conProjectInfo = $conProjectDao->getProjectInfoByCode_d($excelData[$key]['projectCode']);

                                                // 如果项目存在
                                                if ($conProjectInfo) {
                                                    $esmProjectArr[$excelData[$key]['projectCode']] = $conProjectInfo;
                                                } else {
                                                    $excelData[$key]['result'] = '错误的项目编号';
                                                    continue;
                                                }
                                            } else {
                                                $excelData[$key]['result'] = '错误的项目编号';
                                                continue;
                                            }
                                        }
                                    }
                                    $excelData[$key]['projectId'] = $esmProjectArr[$excelData[$key]['projectCode']]['id'];
                                    $excelData[$key]['projectName'] = $esmProjectArr[$excelData[$key]['projectCode']]['projectName'];
                                    $excelData[$key]['belongDeptId'] = $esmProjectArr[$excelData[$key]['projectCode']]['deptId'];
                                    $excelData[$key]['belongDeptName'] = $esmProjectArr[$excelData[$key]['projectCode']]['deptName'];

                                    // 如果是试用项目，还要查找试用项目的内容
                                    if ('FTDXLX-05' == $excelData[$key]['shareObjType']) {
                                        if (!isset($trialProjectArr[$esmProjectArr[$excelData[$key]['projectCode']]['contractId']])) {
                                            $trialProjectInfo = $trialProjectDao->find(
                                                array('id' => $esmProjectArr[$excelData[$key]['projectCode']]['contractId']),
                                                null,
                                                'chanceId,chanceCode,customerName,customerId,province,customerTypeName,areaName,areaCode'
                                            );
                                            if ($trialProjectInfo) {
                                                $trialProjectArr[$esmProjectArr[$excelData[$key]['projectCode']]['contractId']] = $trialProjectInfo;
                                            } else {
                                                $excelData[$key]['result'] = '错误的费用分摊对象';
                                                continue;
                                            }
                                        }
                                        //费用报销归属部门是销售部，则费用承担人必填
                                        if (in_array($excelData[$key]['belongDeptId'], $saleDeptArr)) {
                                            if (empty($costShareObjExtends[1])) {
                                                $excelData[$key]['result'] = '费用归属部门是销售部，请填写费用承担人';
                                                continue;
                                            } else {
                                                $rs = $userDao->find(array('USER_NAME' => $costShareObjExtends[1], 'DEPT_ID' => $excelData[$key]['belongDeptId']), null, 'USER_ID');
                                                if (empty($rs)) {
                                                    $excelData[$key]['result'] = '费用承担人不属于该费用归属部门';
                                                    continue;
                                                } else {
                                                    $excelData[$key]['feeMan'] = $costShareObjExtends[1];
                                                    $excelData[$key]['feeManId'] = $rs['USER_ID'];
                                                }
                                            }
                                        }
                                        $excelData[$key]['chanceId'] = $trialProjectArr[$esmProjectArr[$excelData[$key]['projectCode']]['contractId']]['chanceId'];
                                        $excelData[$key]['chanceCode'] = $trialProjectArr[$esmProjectArr[$excelData[$key]['projectCode']]['contractId']]['chanceCode'];
                                        $excelData[$key]['customerName'] = $trialProjectArr[$esmProjectArr[$excelData[$key]['projectCode']]['contractId']]['customerName'];
                                        $excelData[$key]['customerId'] = $trialProjectArr[$esmProjectArr[$excelData[$key]['projectCode']]['contractId']]['customerId'];
                                        $excelData[$key]['province'] = $trialProjectArr[$esmProjectArr[$excelData[$key]['projectCode']]['contractId']]['province'];
                                        $excelData[$key]['customerType'] = $trialProjectArr[$esmProjectArr[$excelData[$key]['projectCode']]['contractId']]['customerTypeName'];
                                        $excelData[$key]['salesArea'] = $trialProjectArr[$esmProjectArr[$excelData[$key]['projectCode']]['contractId']]['areaName'];
                                        $excelData[$key]['salesAreaId'] = $trialProjectArr[$esmProjectArr[$excelData[$key]['projectCode']]['contractId']]['areaCode'];
                                    }

                                    break;
                                case 'FTDXLX-04' : // 合同研发项目费用
                                case 'FTDXLX-10' : // 研发费用
                                    $excelData[$key]['projectCode'] = $excelData[$key]['costShareObjExtends'];
                                    $excelData[$key]['projectType'] = 'rd';
                                    // 项目信息匹配
                                    if (!isset($esmProjectArr[$excelData[$key]['projectCode']])) {
                                        $esmProjectInfo = $esmProjectDao->find(
                                            array('projectCode' => $excelData[$key]['projectCode'], 'attribute' => 'GCXMSS-05'),
                                            null,
                                            'id,projectName,deptId,deptName,contractId,status'
                                        );
                                        if ($esmProjectInfo) {
                                            $esmProjectArr[$excelData[$key]['projectCode']] = $esmProjectInfo;
                                        } else {
                                            $excelData[$key]['result'] = '错误的项目编号';
                                            continue;
                                        }
                                    }
                                    // 项目状态验证，只允许在建、完工的项目
                                    if (!in_array($esmProjectArr[$excelData[$key]['projectCode']]['status'], $projectStatus1)) {
//                                        $excelData[$key]['result'] = '项目状态错误，只允许选择在建、完工、逾期未关闭的项目';
                                        $excelData[$key]['result'] = '项目状态错误，只允许选择在建、完工的项目';// PMS2600
                                        continue;
                                    }
                                    $excelData[$key]['projectId'] = $esmProjectArr[$excelData[$key]['projectCode']]['id'];
                                    $excelData[$key]['projectName'] = $esmProjectArr[$excelData[$key]['projectCode']]['projectName'];
                                    $excelData[$key]['belongDeptId'] = $esmProjectArr[$excelData[$key]['projectCode']]['deptId'];
                                    $excelData[$key]['belongDeptName'] = $esmProjectArr[$excelData[$key]['projectCode']]['deptName'];
                                    break;
                                case 'FTDXLX-03' : // 合同销售项目费用
                                    $excelData[$key]['projectCode'] = $excelData[$key]['costShareObjExtends'];
                                    break;
                                case 'FTDXLX-06' : // 售前费用 - 商机
                                    // 拆解内容
                                    $costShareObjExtends = explode('/', $excelData[$key]['costShareObjExtends']);
                                    $excelData[$key]['chanceCode'] = $costShareObjExtends[0];

                                    // 商机信息
                                    if (!isset($chanceArr[$excelData[$key]['chanceCode']])) {
                                        $chanceInfo = $chanceDao->find(
                                            array('chanceCode' => $excelData[$key]['chanceCode']),
                                            null,
                                            'id,customerId,customerName,customerTypeName,Province,prinvipalName,prinvipalId,prinvipalDeptId,prinvipalDept,areaName,areaCode'
                                        );
                                        if ($chanceInfo) {
                                            $chanceArr[$excelData[$key]['chanceCode']] = $chanceInfo;
                                        } else {
                                            $excelData[$key]['result'] = '错误的商机编号';
                                            continue;
                                        }
                                    }
                                    $excelData[$key]['chanceId'] = $chanceArr[$excelData[$key]['chanceCode']]['id'];
                                    $excelData[$key]['customerId'] = $chanceArr[$excelData[$key]['chanceCode']]['customerId'];
                                    $excelData[$key]['customerName'] = $chanceArr[$excelData[$key]['chanceCode']]['customerName'];
                                    $excelData[$key]['customerType'] = $chanceArr[$excelData[$key]['chanceCode']]['customerTypeName'];
                                    $excelData[$key]['province'] = $chanceArr[$excelData[$key]['chanceCode']]['Province'];
                                    $excelData[$key]['belongName'] = $chanceArr[$excelData[$key]['chanceCode']]['prinvipalName'];
                                    $excelData[$key]['belongId'] = $chanceArr[$excelData[$key]['chanceCode']]['prinvipalId'];
                                    $excelData[$key]['belongDeptId'] = $chanceArr[$excelData[$key]['chanceCode']]['prinvipalDeptId'];
                                    $excelData[$key]['belongDeptName'] = $chanceArr[$excelData[$key]['chanceCode']]['prinvipalDept'];
                                    $excelData[$key]['salesArea'] = $chanceArr[$excelData[$key]['chanceCode']]['areaName'];
                                    $excelData[$key]['salesAreaId'] = $chanceArr[$excelData[$key]['chanceCode']]['areaCode'];
                                    //费用报销归属部门是销售部，则费用承担人必填
                                    if (in_array($excelData[$key]['belongDeptId'], $saleDeptArr)) {
                                        if (empty($costShareObjExtends[1])) {
                                            $excelData[$key]['result'] = '费用归属部门是销售部，请填写费用承担人';
                                            continue;
                                        } else {
                                            $rs = $userDao->find(array('USER_NAME' => $costShareObjExtends[1], 'DEPT_ID' => $excelData[$key]['belongDeptId']), null, 'USER_ID');
                                            if (empty($rs)) {
                                                $excelData[$key]['result'] = '费用承担人不属于该费用归属部门';
                                                continue;
                                            } else {
                                                $excelData[$key]['feeMan'] = $costShareObjExtends[1];
                                                $excelData[$key]['feeManId'] = $rs['USER_ID'];
                                            }
                                        }
                                    }
                                    break;
                                case 'FTDXLX-07' : // 售前费用 - 客户
                                    $costShareObjExtends = explode('/', $excelData[$key]['costShareObjExtends']);
                                    $excelData[$key]['customerName'] = $costShareObjExtends[0];
                                    if ($deptArr[$costShareObjExtends[1]]) {
                                        $excelData[$key]['belongDeptName'] = $costShareObjExtends[1];
                                        $excelData[$key]['belongDeptId'] = $deptArr[$excelData[$key]['belongDeptName']];
                                    } else {
                                        $excelData[$key]['result'] = '错误的部门';
                                        continue;
                                    }

                                    // 客户信息
                                    if (!isset($customerArr[$excelData[$key]['customerName']])) {
                                        $customerInfo = $customerDao->find(
                                            array('Name' => $excelData[$key]['customerName']),
                                            null,
                                            'id,Prov,TypeOne'
                                        );
                                        if ($customerInfo) {
                                            $customerInfo['TypeOne'] = $datadictArrCustomerType[$customerInfo['TypeOne']];
                                            $customerArr[$excelData[$key]['customerName']] = $customerInfo;
                                        } else {
                                            $excelData[$key]['result'] = '错误的客户名称';
                                            continue;
                                        }
                                    }
                                    //费用报销归属部门是销售部，则费用承担人必填
                                    if (in_array($excelData[$key]['belongDeptId'], $saleDeptArr)) {
                                        if (empty($costShareObjExtends[2])) {
                                            $excelData[$key]['result'] = '费用归属部门是销售部，请填写费用承担人';
                                            continue;
                                        } else {
                                            $rs = $userDao->find(array('USER_NAME' => $costShareObjExtends[2], 'DEPT_ID' => $excelData[$key]['belongDeptId']), null, 'USER_ID');
                                            if (empty($rs)) {
                                                $excelData[$key]['result'] = '费用承担人不属于该费用归属部门';
                                                continue;
                                            } else {
                                                $excelData[$key]['feeMan'] = $costShareObjExtends[2];
                                                $excelData[$key]['feeManId'] = $rs['USER_ID'];
                                            }
                                        }
                                    }
                                    $excelData[$key]['customerId'] = $customerArr[$excelData[$key]['customerName']]['id'];
                                    $excelData[$key]['customerType'] = $customerArr[$excelData[$key]['customerName']]['TypeOne'];
                                    $excelData[$key]['province'] = $customerArr[$excelData[$key]['customerName']]['Prov'];
                                    //获取销售区域
                                    $rs = $regionDao->conRegionByName_d($excelData[$key]['customerType'], $excelData[$key]['province'],
                                        $excelData[$key]['moduleName'], $excelData[$key]['belongCompanyName']);
                                    if (!empty($rs)) {
                                        $excelData[$key]['salesArea'] = $rs[0]['areaName'];
                                        $excelData[$key]['salesAreaId'] = $rs[0]['id'];
                                    }
                                    break;
                                case 'FTDXLX-08' : // 售前费用 - 省份/客户类型/归属部门
                                    $costShareObjExtends = explode('/', $excelData[$key]['costShareObjExtends']);
                                    $excelData[$key]['province'] = $costShareObjExtends[0];
                                    $excelData[$key]['customerType'] = $costShareObjExtends[1];
                                    if ($deptArr[$costShareObjExtends[2]]) {
                                        $excelData[$key]['belongDeptName'] = $costShareObjExtends[2];
                                        $excelData[$key]['belongDeptId'] = $deptArr[$excelData[$key]['belongDeptName']];
                                    } else {
                                        $excelData[$key]['result'] = '错误的部门';
                                        continue;
                                    }
                                    //费用报销归属部门是销售部，则费用承担人必填
                                    if (in_array($excelData[$key]['belongDeptId'], $saleDeptArr)) {
                                        if (empty($costShareObjExtends[3])) {
                                            $excelData[$key]['result'] = '费用归属部门是销售部，请填写费用承担人';
                                            continue;
                                        } else {
                                            $rs = $userDao->find(array('USER_NAME' => $costShareObjExtends[3], 'DEPT_ID' => $excelData[$key]['belongDeptId']), null, 'USER_ID');
                                            if (empty($rs)) {
                                                $excelData[$key]['result'] = '费用承担人不属于该费用归属部门';
                                                continue;
                                            } else {
                                                $excelData[$key]['feeMan'] = $costShareObjExtends[3];
                                                $excelData[$key]['feeManId'] = $rs['USER_ID'];
                                            }
                                        }
                                    }
                                    //获取销售区域
                                    $rs = $regionDao->conRegionByName_d($excelData[$key]['customerType'], $excelData[$key]['province'],
                                        $excelData[$key]['moduleName'], $excelData[$key]['belongCompanyName']);
                                    if (!empty($rs)) {
                                        $excelData[$key]['salesArea'] = $rs[0]['areaName'];
                                        $excelData[$key]['salesAreaId'] = $rs[0]['id'];
                                    }
                                    break;
                                case 'FTDXLX-09' : // 售后费用
                                    $costShareObjExtends = explode('/', $excelData[$key]['costShareObjExtends']);
                                    $excelData[$key]['contractCode'] = $costShareObjExtends[0];
                                    if ($deptArr[$costShareObjExtends[1]]) {
                                        $excelData[$key]['belongDeptName'] = $costShareObjExtends[1];
                                        $excelData[$key]['belongDeptId'] = $deptArr[$excelData[$key]['belongDeptName']];
                                    } else {
                                        $excelData[$key]['result'] = '错误的部门';
                                        continue;
                                    }
                                    // 客户信息
                                    if (!isset($contractArr[$excelData[$key]['contractCode']])) {
                                        $contractInfo = $contractDao->find(
                                            array('contractCode' => $excelData[$key]['contractCode']),
                                            null,
                                            'id,contractName,customerId,customerName,customerTypeName,contractProvince,
                                            prinvipalName,prinvipalId,areaName,areaCode'
                                        );
                                        if ($contractInfo) {
                                            $contractArr[$excelData[$key]['contractCode']] = $contractInfo;
                                        } else {
                                            $excelData[$key]['result'] = '错误的合同编号';
                                            continue;
                                        }
                                    }
                                    //费用报销归属部门是销售部，则费用承担人必填
                                    if (in_array($excelData[$key]['belongDeptId'], $saleDeptArr)) {
                                        if (empty($costShareObjExtends[2])) {
                                            $excelData[$key]['result'] = '费用归属部门是销售部，请填写费用承担人';
                                            continue;
                                        } else {
                                            $rs = $userDao->find(array('USER_NAME' => $costShareObjExtends[2], 'DEPT_ID' => $excelData[$key]['belongDeptId']), null, 'USER_ID');
                                            if (empty($rs)) {
                                                $excelData[$key]['result'] = '费用承担人不属于该费用归属部门';
                                                continue;
                                            } else {
                                                $excelData[$key]['feeMan'] = $costShareObjExtends[2];
                                                $excelData[$key]['feeManId'] = $rs['USER_ID'];
                                            }
                                        }
                                    }
                                    $excelData[$key]['contractId'] = $contractArr[$excelData[$key]['contractCode']]['id'];
                                    $excelData[$key]['contractName'] = $contractArr[$excelData[$key]['contractCode']]['contractName'];
                                    $excelData[$key]['customerId'] = $contractArr[$excelData[$key]['contractCode']]['customerId'];
                                    $excelData[$key]['customerName'] = $contractArr[$excelData[$key]['contractCode']]['customerName'];
                                    $excelData[$key]['customerType'] = $contractArr[$excelData[$key]['contractCode']]['customerTypeName'];
                                    $excelData[$key]['province'] = $contractArr[$excelData[$key]['contractCode']]['contractProvince'];
                                    $excelData[$key]['belongName'] = $contractArr[$excelData[$key]['contractCode']]['prinvipalName'];
                                    $excelData[$key]['belongId'] = $contractArr[$excelData[$key]['contractCode']]['prinvipalId'];
                                    $excelData[$key]['salesArea'] = $contractArr[$excelData[$key]['contractCode']]['areaName'];
                                    $excelData[$key]['salesAreaId'] = $contractArr[$excelData[$key]['contractCode']]['areaCode'];
                                    break;
                                case 'FTDXLX-11' : // 合同项目费用- 合同
                                    $excelData[$key]['contractCode'] = $excelData[$key]['costShareObjExtends'];
                                    if (!isset($contractArr[$excelData[$key]['contractCode']])) {
                                        $contractInfo = $contractDao->find(
                                            array('contractCode' => $excelData[$key]['contractCode'], 'ExaStatus' => '完成'),
                                            null,
                                            'id,contractName,customerId,customerName,customerTypeName,contractProvince,prinvipalName,
                                    			prinvipalId,prinvipalDept,prinvipalDeptId,areaName,areaCode'
                                        );
                                        if ($contractInfo) {
                                            $contractArr[$excelData[$key]['contractCode']] = $contractInfo;
                                        } else {
                                            $excelData[$key]['result'] = '错误的合同编号';
                                            continue;
                                        }
                                    }
                                    $excelData[$key]['contractId'] = $contractArr[$excelData[$key]['contractCode']]['id'];
                                    $excelData[$key]['contractName'] = $contractArr[$excelData[$key]['contractCode']]['contractName'];
                                    $excelData[$key]['customerId'] = $contractArr[$excelData[$key]['contractCode']]['customerId'];
                                    $excelData[$key]['customerName'] = $contractArr[$excelData[$key]['contractCode']]['customerName'];
                                    $excelData[$key]['customerType'] = $contractArr[$excelData[$key]['contractCode']]['customerTypeName'];
                                    $excelData[$key]['province'] = $contractArr[$excelData[$key]['contractCode']]['contractProvince'];
                                    $excelData[$key]['belongName'] = $contractArr[$excelData[$key]['contractCode']]['prinvipalName'];
                                    $excelData[$key]['belongId'] = $contractArr[$excelData[$key]['contractCode']]['prinvipalId'];
                                    $excelData[$key]['belongDeptName'] = $contractArr[$excelData[$key]['contractCode']]['prinvipalDept'];
                                    $excelData[$key]['belongDeptId'] = $contractArr[$excelData[$key]['contractCode']]['prinvipalDeptId'];
                                    $excelData[$key]['salesArea'] = $contractArr[$excelData[$key]['contractCode']]['areaName'];
                                    $excelData[$key]['salesAreaId'] = $contractArr[$excelData[$key]['contractCode']]['areaCode'];
                                    break;
                                default :
                            }
                        } else {
                            $excelData[$key]['result'] = '错误的费用分摊对象';
                            continue;
                        }

                        // 费用明细
                        if ($excelData[$key]['costTypeName'] && $costTypeArr[$excelData[$key]['costTypeName']]) {
                            if (isset($extInfo['unSelectableIds']) && !empty($extInfo['unSelectableIds']) && in_array($costTypeArr[$excelData[$key]['costTypeName']]['costTypeId'], $extInfo['unSelectableIds'])) {
                                $excelData[$key]['result'] = '此费用明细【' . $excelData[$key]['costTypeName'] . '】禁止选择。';
                                continue;
                            } else {
                                $excelData[$key]['costTypeId'] = $costTypeArr[$excelData[$key]['costTypeName']]['costTypeId'];
                                $excelData[$key]['parentTypeId'] = $costTypeArr[$excelData[$key]['costTypeName']]['parentId'];
                                $excelData[$key]['parentTypeName'] = $costTypeArr[$excelData[$key]['costTypeName']]['parentName'];
                            }
                        } else {
                            $excelData[$key]['result'] = '错误的费用明细';
                            continue;
                        }

                        // 分摊金额
                        if (!$excelData[$key]['costMoney']) {
                            $excelData[$key]['result'] = '分摊金额不能为0';
                            continue;
                        }
                    }
                }
                return $excelData;
            } else {
                return 'EXCEL中没有可用数据';
            }
        } else {
            return '文件格式不正确，请重新上传';
        }
    }

    /**
     * 匹配excel字段
     * @param $datas
     * @param $titleRow
     * @return mixed
     */
    function formatArray_d($datas, $titleRow)
    {
        // 已定义标题
        $definedTitle = array(
            '所属板块' => 'moduleName', '归属公司' => 'belongCompanyName', '费用入账期间' => 'inPeriod', '费用归属期间' => 'belongPeriod',
            '费用类型' => 'detailType', '分摊对象' => 'shareObjType', '关联信息' => 'costShareObjExtends',
            '费用明细' => 'costTypeName', '分摊金额' => 'costMoney'
        );

        // 附加的字段
        $appendRow = array(
            'deptId' => '', 'deptName' => '', 'projectId' => '', 'projectCode' => '', 'projectName' => '',
            'chanceId' => '', 'chanceCode' => '', 'province' => '', 'customerType' => '', 'contractId' => '',
            'contractCode' => '', 'belongId' => '', 'belongName' => '', 'belongDeptId' => '', 'belongDeptName' => '',
            'projectType' => '', 'customerName' => '', 'customerId' => ''
        );

        // 构建新的数组
        foreach ($titleRow as $k => $v) {
            // 如果数据为空，则删除
            if (trim($datas[$k]) === '') {
                unset($datas[$k]);
                continue;
            }
            // 如果存在已定义内容，则进行键值替换
            if (isset($definedTitle[$v])) {
                // 格式化更新数组
                $datas[$definedTitle[$v]] = trim($datas[$k]);
            }
            // 处理完成后，删除该项
            unset($datas[$k]);
        }
        return array_merge($datas, $appendRow);
    }

    /**
     * ajax save datas
     * @param $data
     * @return int
     */
    function ajaxSave_d($data)
    {
        $objId = $data['objId'];
        unset($data['objId']);
        $objType = $data['objType'];
        unset($data['objType']);
        $orderCode = $data['other']['orderCode'];
        unset($data['other']['orderCode']);
        $signCompanyName = $data['other']['signCompanyName'];
        unset($data['other']['signCompanyName']);

        // 保存
        $costShare = $this->mergeArray(
            array('objId' => $objId, 'objType' => $objType, 'objCode' => $orderCode, 'supplierName' => $signCompanyName),
            $data['other']['costshare']
        );
        $costShare = util_jsonUtil::iconvUTF2GBArr($costShare);

        return $this->saveDelBatch($costShare) ? 1 : 0;
    }

    /**
     * custom array merge
     * @param $appendArr
     * @param $objArr
     * @param $filterField
     * @return mixed
     */
    function mergeArray($appendArr, $objArr, $filterField = '')
    {
        foreach ($objArr as $key => $value) {
            foreach ($appendArr as $k => $v) {
                $value[$k] = $v;
                $objArr[$key] = $value;
            }

            if (!isset($value['company']) || empty($value['company'])) {
                $objArr[$key]['company'] = $_SESSION['USER_COM'];
                $objArr[$key]['companyName'] = $_SESSION['USER_COM_NAME'];
            }

            //过滤无效数据
            if ($filterField) {
                if (!empty($value[$filterField])) {
                    unset($objArr[$key]);
                }
            }
        }
        return $objArr;
    }

    /**
     * 获取一个待审核的数据
     * @return bool|mixed
     */
    function getOneUnAudit_d()
    {
        //由于oa_finance_cost没有businessBelong,formBelong字段,这边单独做处理
        $limitStr = isset($this->this_limit['公司权限']) && !empty($this->this_limit['公司权限']) ?
            $this->this_limit['公司权限'] : $_SESSION['Company'];

        // 获取当前财务周期
        $periodDao = new model_finance_period_period();
        $periodArr = $periodDao->rtThisPeriod_d(1, 'cost');

        // 匹配权限，如果只有部分公司权限，则只查对应公司的数据
        if (strpos($limitStr, ';;') === false) {
            $obj = $this->_db->get_one("SELECT objId, objType, objCode, supplierName, company, companyName FROM " .
                $this->tbl_name . " WHERE auditStatus = 2 AND isTemp = 0 AND isDel = 0 AND company IN(" .
                util_jsonUtil::strBuild($limitStr) . ") AND inPeriod = '" . $periodArr['thisPeriod'] . "'");
        } else {
            $obj = $this->find(array('auditStatus' => 2, 'isTemp' => 0, 'isDel' => 0, 'inPeriod' => $periodArr['thisPeriod']),
                null, 'objId, objType, objCode, supplierName, company, companyName');
        }
        return $obj;
    }
}