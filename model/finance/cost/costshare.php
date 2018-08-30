<?php

/**
 * @author show
 * @Date 2014��5��6�� 16:12:52
 * @version 1.0
 * @description:���÷��÷�̯ Model��
 */
class model_finance_cost_costshare extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_finance_cost";
        $this->sql_map = "finance/cost/costshareSql.php";
        parent::__construct();
    }

    //���ñ�������
    private $detailTypeArr = array(
        '1' => '���ŷ���',
        '2' => '��ͬ��Ŀ����',
        '3' => '�з�����',
        '4' => '��ǰ����',
        '5' => '�ۺ����'
    );

    /**
     * ���ط�������
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
     * �ж��Ƿ��з�������
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
                return '�⳥��';
            case 2 :
                return '������ͬ';
            case 3 :
                return '�����ͬ';
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
                return '����';
            case 1 :
                return '�����';
            case 2 :
                return 'δ���';
            case 3 :
                return '����';
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
                return 'δ����';
            case 1 :
                return '�ѹ���';
            case 2 :
                return '���ֹ���';
            default;
                return $v;
        }
    }

    /**
     * ��Ӷ���
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
     * ��Ӷ���
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

            // ������Ŀ����
            $shareProject = array();

            // ������������飬���һ������˵�״̬
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
                // ���ҳ��������һ����ѡ�����ѡ�У��Ŷ���������������
                if ($v['check']) {
                    $detailIds[] = $v['id'];
                }
            }

            if (!empty($detailIds))
                $this->setDataAudited_d($object['objId'], $object['objType'], implode(',', $detailIds));

            // ���ƥ�䵽��Ŀ���㣬����о������
            if (!empty($shareProject)) {
                // ��Ʊ�������
                $esmfieldRecordDao = new model_engineering_records_esmfieldrecord();

                // ѭ������
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
        // ���뷢��˵Ĵ���
        $this->searchArr = array(
            'ids' => $ids
        );
        $data = $this->list_d();

        try {
            $this->start_d();

            // ������Ŀ����
            $shareProject = array();

            // ������������飬���һ������˵�״̬
            foreach ($data as $v) {
                if ($v['projectId'] && !isset($shareProject[$v['projectId']][$v['inPeriod']])) {
                    $shareProject[$v['projectId']][$v['inPeriod']] = 1;
                }
            }

            $this->_db->query("UPDATE " . $this->tbl_name . " SET unHookMoney = costMoney, auditStatus = 1, auditDate = '"
                . day_date . "',auditor = '" . $_SESSION['USERNAME'] . "',auditorId = '" . $_SESSION['USER_ID']
                . "' WHERE id IN(" . $ids . ") AND auditStatus = 2");

            // ���ƥ�䵽��Ŀ���㣬����о������
            if (!empty($shareProject)) {
                // ��Ʊ�������
                $esmfieldRecordDao = new model_engineering_records_esmfieldrecord();

                // ѭ������
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
        // ���뷢��˵Ĵ���
        $this->searchArr = array(
            'ids' => $ids
        );
        $data = $this->list_d();

        try {
            $this->start_d();

            // ������Ŀ����
            $shareProject = array();

            // ������������飬���һ������˵�״̬
            foreach ($data as $v) {
                if ($v['projectId'] && !isset($shareProject[$v['projectId']][$v['inPeriod']])) {
                    $shareProject[$v['projectId']][$v['inPeriod']] = 1;
                }
            }

            $this->_db->query("UPDATE " . $this->tbl_name . " SET unHookMoney = 0, auditStatus = 2, auditDate = NULL"
                . ", auditor = '', auditorId = '' WHERE id IN(" . $ids . ") AND auditStatus = 1");

            // ���ƥ�䵽��Ŀ���㣬����о������
            if (!empty($shareProject)) {
                // ��Ʊ�������
                $esmfieldRecordDao = new model_engineering_records_esmfieldrecord();

                // ѭ������
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

        // ƥ���û�ж������ŵ�����
        $data = $this->_db->getArray("SELECT id,belongDeptId FROM oa_finance_cost
            WHERE headDeptName='NULL' OR headDeptName='��������' OR headDeptName = '' OR headDeptName IS NULL");

        if (!empty($data)) {
            // ��������ӳ���ϵ
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
//        WHERE c.headDeptId IS NULL OR c.headDeptName = '��������' OR c.headDeptName = ''");
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
     * ��ȡ��Ŀ�ķ�̯���
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
            // ���ݹ����������� - ���������Ҫע����Ƿ���ֵ������id��
            $filterArr = array(
                2 => array(
                    'class' => 'model_contract_other_other',
                    'func' => 'getEffectiveObjIds_d'
                )
            );

            // ��ʼ��һ����������
            $condition = array();
            // ѭ�����ع�������
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
                // ���ȷʵ����������ƴװ�������
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
     * ��ȡԴ�����
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

        // ���û�з��ֶ�Ӧ�����ͣ���ô����0
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
            // ������¼
            $costHookDetailDao = new model_finance_cost_costHookDetail();
            // �Ȳ�������к�
            $costHookDetailInfo = $costHookDetailDao->find(array('hookId' => $hookId, 'hookType' => $hookType), null,
                'mainId');
            if (!$costHookDetailInfo) $costHookDetailInfo['mainId'] = -1;
            // �ڲ鹴����id
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
     * @param bool $companyLimit ��˾Ȩ�޴���Ĭ��Ϊtrue
     * @param bool $isCountSql �Ƿ��ȡͳ�ƽű���Ĭ��Ϊfalse
     * @return string
     */
    function getPageSql_d($condition, $companyLimit = true, $isCountSql = false)
    {
        if ($condition['periodNo']) {
            $periodCondition = " AND h.hookPeriod = '" . $condition['periodNo'] . "'";
        }
        //��˾Ȩ�޴���
        if ($companyLimit) {
            //����oa_finance_costû��businessBelong,formBelong�ֶ�,��ߵ���������
            $limitStr = isset($this->this_limit['��˾Ȩ��']) && !empty($this->this_limit['��˾Ȩ��']) ? $this->this_limit['��˾Ȩ��'] : $_SESSION['Company'];
            if (strpos($limitStr, ';;') === false) {# �����������Ȩ��,�����˴���
                $companyCondition = " AND c.company IN(" . util_jsonUtil::strBuild($limitStr) . ")";
            }
        }
        if ($isCountSql) {
            return "SELECT '�ܼ�' AS companyName, SUM(c.costMoney) AS costMoney,
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
     * ��ȡ��̯��Ϣ - �����ѷ�̯���ܶ�costMoney��δ�����ܶ�unHookMoney���������hookMoney
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
     * ��ȡ��̯���ݵĽӿ�
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
     * ��δ��˵ķ�̯��¼ʱ��֪ͨ�����Ա
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
     * �Զ����÷�̯����
     * @param $data
     * @param $moneyNoTax
     * @param $taxPoint
     * @return string
     */
    function setShareObjType_d($data, $moneyNoTax = 0, $taxPoint = 0)
    {
        // ��ȡ�������
        $maxIndex = count($data) - 1;

        foreach ($data as $k => $v) {
            // ���˰�ʲ�Ϊ0������Ҫ��˰�Ѵ���
            if ($taxPoint != 0) {
                $data[$k]['costMoney'] = $k == $maxIndex ? $moneyNoTax : round(bcdiv($v['costMoney'], bcdiv(100 + $taxPoint, 100, 6), 6), 2);
                $moneyNoTax = bcsub($moneyNoTax, $v['costMoney'], 2);
            }

            switch ($v['detailType']) {
                case '���ŷ���' :
                    $data[$k]['detailType'] = '1';
                case '1' :
                    $data[$k]['shareObjType'] = 'FTDXLX-01';
                    break;
                case '��ͬ��Ŀ����' :
                    $data[$k]['detailType'] = '2';
                case '2' :
                    $data[$k]['shareObjType'] = 'FTDXLX-02';
                    break;
                case '�з�����' :
                    $data[$k]['detailType'] = '3';
                case '3' :
                    $data[$k]['shareObjType'] = 'FTDXLX-10';
                    break;
                case '��ǰ����' :
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
                case '�ۺ����' :
                    $data[$k]['detailType'] = '5';
                case '5' :
                    $data[$k]['shareObjType'] = 'FTDXLX-09';
                    break;
            }
        }
        return $data;
    }

    /**
     * ���ݵ���
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
        // �������
        $moduleArr = $datadictDao->getDataDictList_d('HTBK', array('isUse' => 0), true);
        // ��˾����
        $branchDao = new model_deptuser_branch_branch();
        $branchArr = $branchDao->getCompany_d();
        $branchArrReverse = array_flip($branchArr);
        // ��ǰ��������
        $periodDao = new model_finance_period_period();
        $periodArr = $periodDao->rtThisPeriod_d(1, 'cost');
        // ��������
        $costTypeDao = new model_finance_expense_costtype();
        $costTypeArr = $costTypeDao->getCostTypeLeafList_d('costTypeName');
        // ����
        $deptDao = new model_deptuser_dept_dept();
        $deptArr = $deptDao->getDeptList_d();
        // ������Ŀ
        $esmProjectDao = new model_engineering_project_esmproject();
        $esmProjectArr = array();
        // ��Ч����Ŀ״̬
        $projectStatus = array('GCXMZT02', 'GCXMZT04', 'GCXMZT00');
        $projectStatus1 = array('GCXMZT02', 'GCXMZT04');// pms2600 ֻ�����ڽ�,�깤״̬��
        // ��ͬ��Ŀ
        $conProjectDao = new model_contract_conproject_conproject();
        // ������Ŀ
        $trialProjectDao = new model_projectmanagent_trialproject_trialproject();
        $trialProjectArr = array();
        // �̻�
        $chanceDao = new model_projectmanagent_chance_chance();
        $chanceArr = array();
        // �ͻ�
        $customerDao = new model_customer_customer_customer();
        $customerArr = array();
        // ��ͬ
        $contractDao = new model_contract_contract_contract();
        $contractArr = array();
        // �������۲��Ŵ���
        $saleDeptArr = explode(',', expenseSaleDeptId);
        // ��������
        $regionDao = new model_system_region_region();
        // �û�
        $userDao = new model_deptuser_user_user();

        // �жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name, 1);
            spl_autoload_register("__autoload");
            $titleRow = $excelData[0];
            unset($excelData[0]);

            if (!empty($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    if (empty($val[0]) || empty($val[3])) {
                        unset($excelData[$key]);
                    } else {
                        // ��ʽ������
                        $excelData[$key] = $this->formatArray_d($val, $titleRow);
                        // ��ʼ������ֵ�ֶΣ���ֹǰ̨js����undefined�������By weijb 2015.11.25
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

                        // �������
                        if ($excelData[$key]['moduleName'] && $moduleArr[$excelData[$key]['moduleName']]) {
                            $excelData[$key]['module'] = $moduleArr[$excelData[$key]['moduleName']];
                        } else {
                            $excelData[$key]['result'] = '����������������';
                            continue;
                        }

                        // ������˾
                        if ($excelData[$key]['belongCompanyName'] && $branchArrReverse[$excelData[$key]['belongCompanyName']]) {
                            $excelData[$key]['belongCompany'] = $branchArrReverse[$excelData[$key]['belongCompanyName']];
                        } else {
                            $excelData[$key]['result'] = '����Ĺ�����˾����';
                            continue;
                        }

                        // ���������ڼ�
                        $excelData[$key]['inPeriod'] = str_replace(".0",".",$excelData[$key]['inPeriod']);
                        if (!$excelData[$key]['inPeriod'] ||
                            !$periodDao->checkPeriodAllow_d($excelData[$key]['inPeriod'], $periodArr['thisPeriod'])
                        ) {

                            $excelData[$key]['result'] = '����ķ��������ڼ�';
                            continue;
                        }

                        // ��������
                        if ($excelData[$key]['detailType'] && $this->hasDetailType($excelData[$key]['detailType'], true)) {
                            $excelData[$key]['detailType'] = $this->getDetailType($excelData[$key]['detailType'], true);
                        } else {
                            $excelData[$key]['result'] = '����ķ�������';
                            continue;
                        }

                        // ��̯����
                        if ($excelData[$key]['shareObjType'] && $datadictArr[$excelData[$key]['shareObjType']]) {
                            $excelData[$key]['shareObjType'] = $datadictArr[$excelData[$key]['shareObjType']];
                            // ��̯����
                            switch ($excelData[$key]['shareObjType']) {
                                case 'FTDXLX-01' : // ���ŷ���

                                    // �������
                                    $costShareObjExtends = explode('/', $excelData[$key]['costShareObjExtends']);

                                    // ������Ϣ����
                                    if ($deptArr[$costShareObjExtends[0]]) {
                                        $deptId = $deptArr[$costShareObjExtends[0]]['DEPT_ID'];
                                        if ($unSltDeptFilter != '') {
                                            $unSltDeptFilterArr = explode(",", $unSltDeptFilter);
                                            if (in_array($deptId, $unSltDeptFilterArr)) {
                                                $excelData[$key]['result'] = '�˲��� ��' . $costShareObjExtends[0] . "����ֹѡ��";
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
                                        $excelData[$key]['result'] = '����Ĳ���';
                                        continue;
                                    }

                                    // ������ƥ��
                                    if (isset($costShareObjExtends[1]) && !empty($costShareObjExtends[1])) {
                                        $excelData[$key]['projectCode'] = $costShareObjExtends[1];
                                        $excelData[$key]['projectType'] = 'esm';

                                        // ��Ŀ��Ϣƥ��
                                        if (!isset($esmProjectArr[$excelData[$key]['projectCode']])) {
                                            $esmProjectInfo = $esmProjectDao->find(
                                                array('projectCode' => $excelData[$key]['projectCode']),
                                                null,
                                                'id,projectName,deptId,deptName,status'
                                            );

                                            if ($esmProjectInfo) {
                                                $esmProjectArr[$excelData[$key]['projectCode']] = $esmProjectInfo;
                                            } else {
                                                $excelData[$key]['result'] = '�������Ŀ���';
                                                continue;
                                            }
                                        }
                                        // ��Ŀ״̬��֤��ֻ�����ڽ����깤����Ŀ
                                        if (!in_array($esmProjectArr[$excelData[$key]['projectCode']]['status'], $projectStatus1)) {
//                                            $excelData[$key]['result'] = '��Ŀ״̬����ֻ����ѡ���ڽ����깤������δ�رյ���Ŀ';
                                            $excelData[$key]['result'] = '��Ŀ״̬����ֻ����ѡ���ڽ����깤����Ŀ';// PMS2600
                                            continue;
                                        }
                                        $excelData[$key]['projectId'] = $esmProjectArr[$excelData[$key]['projectCode']]['id'];
                                        $excelData[$key]['projectName'] = $esmProjectArr[$excelData[$key]['projectCode']]['projectName'];
                                    }

                                    break;
                                case 'FTDXLX-02' : // ��ͬ������Ŀ����
                                case 'FTDXLX-05' : // ��ǰ���� - ������Ŀ
                                    // �������
                                    $costShareObjExtends = explode('/', $excelData[$key]['costShareObjExtends']);
                                    $excelData[$key]['projectCode'] = $costShareObjExtends[0];
                                    $excelData[$key]['projectType'] = 'esm';
                                    // ��Ŀ��Ϣƥ��
                                    if (!isset($esmProjectArr[$excelData[$key]['projectCode']])) {
                                        $esmProjectInfo = $esmProjectDao->find(
                                            array('projectCode' => $excelData[$key]['projectCode']),
                                            null,
                                            'id,projectName,deptId,deptName,contractId,status'
                                        );
                                        if ($esmProjectInfo) {
                                            // ��Ŀ״̬��֤��ֻ�����ڽ����깤����Ŀ
                                            if (!in_array($esmProjectInfo['status'], $projectStatus1)) {
//                                                $excelData[$key]['result'] = '��Ŀ״̬����ֻ����ѡ���ڽ����깤������δ�رյ���Ŀ';
                                                $excelData[$key]['result'] = '��Ŀ״̬����ֻ����ѡ���ڽ����깤����Ŀ';// PMS2600
                                                continue;
                                            }
                                            $esmProjectArr[$excelData[$key]['projectCode']] = $esmProjectInfo;
                                        } else {
                                            // ����Ǻ�ͬ��Ŀ��ƥ��ƥ���Ʒ��Ŀ�Ĳ���
                                            if ('FTDXLX-02' == $excelData[$key]['shareObjType']) {
                                                $conProjectInfo = $conProjectDao->getProjectInfoByCode_d($excelData[$key]['projectCode']);

                                                // �����Ŀ����
                                                if ($conProjectInfo) {
                                                    $esmProjectArr[$excelData[$key]['projectCode']] = $conProjectInfo;
                                                } else {
                                                    $excelData[$key]['result'] = '�������Ŀ���';
                                                    continue;
                                                }
                                            } else {
                                                $excelData[$key]['result'] = '�������Ŀ���';
                                                continue;
                                            }
                                        }
                                    }
                                    $excelData[$key]['projectId'] = $esmProjectArr[$excelData[$key]['projectCode']]['id'];
                                    $excelData[$key]['projectName'] = $esmProjectArr[$excelData[$key]['projectCode']]['projectName'];
                                    $excelData[$key]['belongDeptId'] = $esmProjectArr[$excelData[$key]['projectCode']]['deptId'];
                                    $excelData[$key]['belongDeptName'] = $esmProjectArr[$excelData[$key]['projectCode']]['deptName'];

                                    // �����������Ŀ����Ҫ����������Ŀ������
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
                                                $excelData[$key]['result'] = '����ķ��÷�̯����';
                                                continue;
                                            }
                                        }
                                        //���ñ����������������۲�������óе��˱���
                                        if (in_array($excelData[$key]['belongDeptId'], $saleDeptArr)) {
                                            if (empty($costShareObjExtends[1])) {
                                                $excelData[$key]['result'] = '���ù������������۲�������д���óе���';
                                                continue;
                                            } else {
                                                $rs = $userDao->find(array('USER_NAME' => $costShareObjExtends[1], 'DEPT_ID' => $excelData[$key]['belongDeptId']), null, 'USER_ID');
                                                if (empty($rs)) {
                                                    $excelData[$key]['result'] = '���óе��˲����ڸ÷��ù�������';
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
                                case 'FTDXLX-04' : // ��ͬ�з���Ŀ����
                                case 'FTDXLX-10' : // �з�����
                                    $excelData[$key]['projectCode'] = $excelData[$key]['costShareObjExtends'];
                                    $excelData[$key]['projectType'] = 'rd';
                                    // ��Ŀ��Ϣƥ��
                                    if (!isset($esmProjectArr[$excelData[$key]['projectCode']])) {
                                        $esmProjectInfo = $esmProjectDao->find(
                                            array('projectCode' => $excelData[$key]['projectCode'], 'attribute' => 'GCXMSS-05'),
                                            null,
                                            'id,projectName,deptId,deptName,contractId,status'
                                        );
                                        if ($esmProjectInfo) {
                                            $esmProjectArr[$excelData[$key]['projectCode']] = $esmProjectInfo;
                                        } else {
                                            $excelData[$key]['result'] = '�������Ŀ���';
                                            continue;
                                        }
                                    }
                                    // ��Ŀ״̬��֤��ֻ�����ڽ����깤����Ŀ
                                    if (!in_array($esmProjectArr[$excelData[$key]['projectCode']]['status'], $projectStatus1)) {
//                                        $excelData[$key]['result'] = '��Ŀ״̬����ֻ����ѡ���ڽ����깤������δ�رյ���Ŀ';
                                        $excelData[$key]['result'] = '��Ŀ״̬����ֻ����ѡ���ڽ����깤����Ŀ';// PMS2600
                                        continue;
                                    }
                                    $excelData[$key]['projectId'] = $esmProjectArr[$excelData[$key]['projectCode']]['id'];
                                    $excelData[$key]['projectName'] = $esmProjectArr[$excelData[$key]['projectCode']]['projectName'];
                                    $excelData[$key]['belongDeptId'] = $esmProjectArr[$excelData[$key]['projectCode']]['deptId'];
                                    $excelData[$key]['belongDeptName'] = $esmProjectArr[$excelData[$key]['projectCode']]['deptName'];
                                    break;
                                case 'FTDXLX-03' : // ��ͬ������Ŀ����
                                    $excelData[$key]['projectCode'] = $excelData[$key]['costShareObjExtends'];
                                    break;
                                case 'FTDXLX-06' : // ��ǰ���� - �̻�
                                    // �������
                                    $costShareObjExtends = explode('/', $excelData[$key]['costShareObjExtends']);
                                    $excelData[$key]['chanceCode'] = $costShareObjExtends[0];

                                    // �̻���Ϣ
                                    if (!isset($chanceArr[$excelData[$key]['chanceCode']])) {
                                        $chanceInfo = $chanceDao->find(
                                            array('chanceCode' => $excelData[$key]['chanceCode']),
                                            null,
                                            'id,customerId,customerName,customerTypeName,Province,prinvipalName,prinvipalId,prinvipalDeptId,prinvipalDept,areaName,areaCode'
                                        );
                                        if ($chanceInfo) {
                                            $chanceArr[$excelData[$key]['chanceCode']] = $chanceInfo;
                                        } else {
                                            $excelData[$key]['result'] = '������̻����';
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
                                    //���ñ����������������۲�������óе��˱���
                                    if (in_array($excelData[$key]['belongDeptId'], $saleDeptArr)) {
                                        if (empty($costShareObjExtends[1])) {
                                            $excelData[$key]['result'] = '���ù������������۲�������д���óе���';
                                            continue;
                                        } else {
                                            $rs = $userDao->find(array('USER_NAME' => $costShareObjExtends[1], 'DEPT_ID' => $excelData[$key]['belongDeptId']), null, 'USER_ID');
                                            if (empty($rs)) {
                                                $excelData[$key]['result'] = '���óе��˲����ڸ÷��ù�������';
                                                continue;
                                            } else {
                                                $excelData[$key]['feeMan'] = $costShareObjExtends[1];
                                                $excelData[$key]['feeManId'] = $rs['USER_ID'];
                                            }
                                        }
                                    }
                                    break;
                                case 'FTDXLX-07' : // ��ǰ���� - �ͻ�
                                    $costShareObjExtends = explode('/', $excelData[$key]['costShareObjExtends']);
                                    $excelData[$key]['customerName'] = $costShareObjExtends[0];
                                    if ($deptArr[$costShareObjExtends[1]]) {
                                        $excelData[$key]['belongDeptName'] = $costShareObjExtends[1];
                                        $excelData[$key]['belongDeptId'] = $deptArr[$excelData[$key]['belongDeptName']];
                                    } else {
                                        $excelData[$key]['result'] = '����Ĳ���';
                                        continue;
                                    }

                                    // �ͻ���Ϣ
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
                                            $excelData[$key]['result'] = '����Ŀͻ�����';
                                            continue;
                                        }
                                    }
                                    //���ñ����������������۲�������óе��˱���
                                    if (in_array($excelData[$key]['belongDeptId'], $saleDeptArr)) {
                                        if (empty($costShareObjExtends[2])) {
                                            $excelData[$key]['result'] = '���ù������������۲�������д���óе���';
                                            continue;
                                        } else {
                                            $rs = $userDao->find(array('USER_NAME' => $costShareObjExtends[2], 'DEPT_ID' => $excelData[$key]['belongDeptId']), null, 'USER_ID');
                                            if (empty($rs)) {
                                                $excelData[$key]['result'] = '���óе��˲����ڸ÷��ù�������';
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
                                    //��ȡ��������
                                    $rs = $regionDao->conRegionByName_d($excelData[$key]['customerType'], $excelData[$key]['province'],
                                        $excelData[$key]['moduleName'], $excelData[$key]['belongCompanyName']);
                                    if (!empty($rs)) {
                                        $excelData[$key]['salesArea'] = $rs[0]['areaName'];
                                        $excelData[$key]['salesAreaId'] = $rs[0]['id'];
                                    }
                                    break;
                                case 'FTDXLX-08' : // ��ǰ���� - ʡ��/�ͻ�����/��������
                                    $costShareObjExtends = explode('/', $excelData[$key]['costShareObjExtends']);
                                    $excelData[$key]['province'] = $costShareObjExtends[0];
                                    $excelData[$key]['customerType'] = $costShareObjExtends[1];
                                    if ($deptArr[$costShareObjExtends[2]]) {
                                        $excelData[$key]['belongDeptName'] = $costShareObjExtends[2];
                                        $excelData[$key]['belongDeptId'] = $deptArr[$excelData[$key]['belongDeptName']];
                                    } else {
                                        $excelData[$key]['result'] = '����Ĳ���';
                                        continue;
                                    }
                                    //���ñ����������������۲�������óе��˱���
                                    if (in_array($excelData[$key]['belongDeptId'], $saleDeptArr)) {
                                        if (empty($costShareObjExtends[3])) {
                                            $excelData[$key]['result'] = '���ù������������۲�������д���óе���';
                                            continue;
                                        } else {
                                            $rs = $userDao->find(array('USER_NAME' => $costShareObjExtends[3], 'DEPT_ID' => $excelData[$key]['belongDeptId']), null, 'USER_ID');
                                            if (empty($rs)) {
                                                $excelData[$key]['result'] = '���óе��˲����ڸ÷��ù�������';
                                                continue;
                                            } else {
                                                $excelData[$key]['feeMan'] = $costShareObjExtends[3];
                                                $excelData[$key]['feeManId'] = $rs['USER_ID'];
                                            }
                                        }
                                    }
                                    //��ȡ��������
                                    $rs = $regionDao->conRegionByName_d($excelData[$key]['customerType'], $excelData[$key]['province'],
                                        $excelData[$key]['moduleName'], $excelData[$key]['belongCompanyName']);
                                    if (!empty($rs)) {
                                        $excelData[$key]['salesArea'] = $rs[0]['areaName'];
                                        $excelData[$key]['salesAreaId'] = $rs[0]['id'];
                                    }
                                    break;
                                case 'FTDXLX-09' : // �ۺ����
                                    $costShareObjExtends = explode('/', $excelData[$key]['costShareObjExtends']);
                                    $excelData[$key]['contractCode'] = $costShareObjExtends[0];
                                    if ($deptArr[$costShareObjExtends[1]]) {
                                        $excelData[$key]['belongDeptName'] = $costShareObjExtends[1];
                                        $excelData[$key]['belongDeptId'] = $deptArr[$excelData[$key]['belongDeptName']];
                                    } else {
                                        $excelData[$key]['result'] = '����Ĳ���';
                                        continue;
                                    }
                                    // �ͻ���Ϣ
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
                                            $excelData[$key]['result'] = '����ĺ�ͬ���';
                                            continue;
                                        }
                                    }
                                    //���ñ����������������۲�������óе��˱���
                                    if (in_array($excelData[$key]['belongDeptId'], $saleDeptArr)) {
                                        if (empty($costShareObjExtends[2])) {
                                            $excelData[$key]['result'] = '���ù������������۲�������д���óе���';
                                            continue;
                                        } else {
                                            $rs = $userDao->find(array('USER_NAME' => $costShareObjExtends[2], 'DEPT_ID' => $excelData[$key]['belongDeptId']), null, 'USER_ID');
                                            if (empty($rs)) {
                                                $excelData[$key]['result'] = '���óе��˲����ڸ÷��ù�������';
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
                                case 'FTDXLX-11' : // ��ͬ��Ŀ����- ��ͬ
                                    $excelData[$key]['contractCode'] = $excelData[$key]['costShareObjExtends'];
                                    if (!isset($contractArr[$excelData[$key]['contractCode']])) {
                                        $contractInfo = $contractDao->find(
                                            array('contractCode' => $excelData[$key]['contractCode'], 'ExaStatus' => '���'),
                                            null,
                                            'id,contractName,customerId,customerName,customerTypeName,contractProvince,prinvipalName,
                                    			prinvipalId,prinvipalDept,prinvipalDeptId,areaName,areaCode'
                                        );
                                        if ($contractInfo) {
                                            $contractArr[$excelData[$key]['contractCode']] = $contractInfo;
                                        } else {
                                            $excelData[$key]['result'] = '����ĺ�ͬ���';
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
                            $excelData[$key]['result'] = '����ķ��÷�̯����';
                            continue;
                        }

                        // ������ϸ
                        if ($excelData[$key]['costTypeName'] && $costTypeArr[$excelData[$key]['costTypeName']]) {
                            if (isset($extInfo['unSelectableIds']) && !empty($extInfo['unSelectableIds']) && in_array($costTypeArr[$excelData[$key]['costTypeName']]['costTypeId'], $extInfo['unSelectableIds'])) {
                                $excelData[$key]['result'] = '�˷�����ϸ��' . $excelData[$key]['costTypeName'] . '����ֹѡ��';
                                continue;
                            } else {
                                $excelData[$key]['costTypeId'] = $costTypeArr[$excelData[$key]['costTypeName']]['costTypeId'];
                                $excelData[$key]['parentTypeId'] = $costTypeArr[$excelData[$key]['costTypeName']]['parentId'];
                                $excelData[$key]['parentTypeName'] = $costTypeArr[$excelData[$key]['costTypeName']]['parentName'];
                            }
                        } else {
                            $excelData[$key]['result'] = '����ķ�����ϸ';
                            continue;
                        }

                        // ��̯���
                        if (!$excelData[$key]['costMoney']) {
                            $excelData[$key]['result'] = '��̯����Ϊ0';
                            continue;
                        }
                    }
                }
                return $excelData;
            } else {
                return 'EXCEL��û�п�������';
            }
        } else {
            return '�ļ���ʽ����ȷ���������ϴ�';
        }
    }

    /**
     * ƥ��excel�ֶ�
     * @param $datas
     * @param $titleRow
     * @return mixed
     */
    function formatArray_d($datas, $titleRow)
    {
        // �Ѷ������
        $definedTitle = array(
            '�������' => 'moduleName', '������˾' => 'belongCompanyName', '���������ڼ�' => 'inPeriod', '���ù����ڼ�' => 'belongPeriod',
            '��������' => 'detailType', '��̯����' => 'shareObjType', '������Ϣ' => 'costShareObjExtends',
            '������ϸ' => 'costTypeName', '��̯���' => 'costMoney'
        );

        // ���ӵ��ֶ�
        $appendRow = array(
            'deptId' => '', 'deptName' => '', 'projectId' => '', 'projectCode' => '', 'projectName' => '',
            'chanceId' => '', 'chanceCode' => '', 'province' => '', 'customerType' => '', 'contractId' => '',
            'contractCode' => '', 'belongId' => '', 'belongName' => '', 'belongDeptId' => '', 'belongDeptName' => '',
            'projectType' => '', 'customerName' => '', 'customerId' => ''
        );

        // �����µ�����
        foreach ($titleRow as $k => $v) {
            // �������Ϊ�գ���ɾ��
            if (trim($datas[$k]) === '') {
                unset($datas[$k]);
                continue;
            }
            // ��������Ѷ������ݣ�����м�ֵ�滻
            if (isset($definedTitle[$v])) {
                // ��ʽ����������
                $datas[$definedTitle[$v]] = trim($datas[$k]);
            }
            // ������ɺ�ɾ������
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

        // ����
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

            //������Ч����
            if ($filterField) {
                if (!empty($value[$filterField])) {
                    unset($objArr[$key]);
                }
            }
        }
        return $objArr;
    }

    /**
     * ��ȡһ������˵�����
     * @return bool|mixed
     */
    function getOneUnAudit_d()
    {
        //����oa_finance_costû��businessBelong,formBelong�ֶ�,��ߵ���������
        $limitStr = isset($this->this_limit['��˾Ȩ��']) && !empty($this->this_limit['��˾Ȩ��']) ?
            $this->this_limit['��˾Ȩ��'] : $_SESSION['Company'];

        // ��ȡ��ǰ��������
        $periodDao = new model_finance_period_period();
        $periodArr = $periodDao->rtThisPeriod_d(1, 'cost');

        // ƥ��Ȩ�ޣ����ֻ�в��ֹ�˾Ȩ�ޣ���ֻ���Ӧ��˾������
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