<?php

/**
 * ���ŷ��ñ�
 * Class model_bi_deptFee_deptFee
 */
class model_bi_deptFee_deptFee extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_bi_dept_fee";
        $this->sql_map = "bi/deptFee/deptFeeSql.php";
        parent::__construct();
    }

    public $feeType = array(
        'person' => '����',
        'expense' => '����',
        'pay' => '֧��',
        'pk' => 'PK'
    );


    /**
     * ������ʷ��������
     * @param $orgParam
     * @param $newParam
     * @throws Exception
     */
    function updateOldData_d($orgParam, $newParam)
    {
        $sql = "UPDATE $this->tbl_name SET ";

        $i = 0;
        foreach ($newParam as $k => $v) {
            $sql .= $i ? "," . $k . " = '" . $v . "'" : $k . " = '" . $v . "'";
            $i = 1;
        }

        $sql .= " WHERE ";

        $j = 0;
        foreach ($orgParam as $k => $v) {
            $sql .= $j ? " AND " . $k . " = '" . $v . "'" : $k . " = '" . $v . "'";
            $j = 1;
        }
        $this->_db->query($sql);
    }

    /**
     * �������
     * @param $year
     * @param $month
     * @param $feeType
     * @return string
     */
    function updateFee_d($year, $month, $feeType)
    {
        // ȡ��������
        $data = array();
        // ������������
        $feeTypeName = $this->feeType[$feeType];

        switch ($feeType) {
            case 'person':
                $data = $this->getPerson_d($year, $month);
                break;
            case 'expense':
                $data = $this->getExpense_d($year, $month);
                break;
            case 'pay':
                $data = $this->getPay_d($year, $month);
                break;
            case 'pk':
                $data = $this->getPK_d($year, $month);
                break;
            default:
        }

        // ���Ϊ��ʱ��������ʾ
        if (empty($data)) {
            return array(
                'feeType' => $feeType,
                'feeTypeName' => $feeTypeName,
                'rst' => 'none',
                'feeAll' => 0,
                'ignore' => 0,
                'ignoreInfo' => ''
            );
        } else {
            $time = strtotime($year . '-' . $month . '-1'); // ʱ��

            // ɾ��������
            $this->delete(array(
                'thisTime' => $time, 'costType' => $feeTypeName, 'isImport' => 0
            ));

            // ����ӳ���ȡ
            $deptMappingDao = new model_bi_deptFee_deptMapping();
            $deptMapping = $deptMappingDao->getMapping_d($feeTypeName);

            // ��������
            $feeAll = 0; // ���µĽ��
            $ignore = 0; // ���ԵĽ��
            $ignoreInfo = array(); // ��������

            // ѭ����������
            foreach ($data as $k => $v) {
                // ��������
                $inArr = array(
                    'thisYear' => $year, 'thisMonth' => $month, 'thisTime' => $time,
                    'costType' => $feeTypeName, 'isImport' => 0, 'fee' => $v['fee']
                );

                // �������Դ�а�飬�򽫲��źͰ��ƴ����һ����
                $dept = isset($v['module']) && $v['module'] ?
                    $v['deptName'] . '_' . $v['module'] : $v['deptName'];

                // ƥ��ӳ�䣬ƥ�����ˣ����ȴ���ƥ�䲻�ȷ���
                if (isset($deptMapping[$dept])) {
                    $inArr = array_merge($inArr, $deptMapping[$dept]);
                    $inArr['orgDept'] = $dept;
                    $this->add_d($inArr);
                } elseif (isset($deptMapping[$v['deptName']])) {
                    $inArr = array_merge($inArr, $deptMapping[$v['deptName']]);
                    $inArr['orgDept'] = $v['deptName'];
                    $this->add_d($inArr);
                } else {
                    $ignore = bcadd($ignore, $v['fee'], 2);
                    $ignoreInfo[] = $dept . "|" . $v['fee'];
                    continue;
                }
                $feeAll = bcadd($feeAll, $v['fee'], 2);
            }

            return array(
                'feeType' => $feeType,
                'feeTypeName' => $feeTypeName,
                'rst' => 'ok',
                'feeAll' => $feeAll,
                'ignore' => $ignore,
                'ignoreInfo' => implode(',', $ignoreInfo)
            );
        }
    }

    /**
     * ��ȡ��ϸi����
     * @param $year
     * @param $month
     * @param $feeTypes
     * @return array
     */
    function getDetail_d($year, $month, $feeTypes)
    {
        $data = array();
        $feeTypeArr = explode(',', $feeTypes);
        foreach ($feeTypeArr as $feeType) {
            switch ($feeType) {
                case 'person':
                    $data[] = $this->getPerson_d($year, $month);
                    break;
                case 'expense':
                    $data[] = $this->getExpenseDetail_d($year, $month);
                    break;
                case 'pay':
                    $data[] = $this->getPayDetail_d($year, $month);
                    break;
                case 'pk':
                    $data[] = $this->getPKDetail_d($year, $month);
                    break;
                default:
                    $data[] = array();
            }
        }
        return $data;
    }

    /**
     * ��ȡ��������
     * @param $year
     * @param $month
     * @return array
     */
    function getPerson_d($year, $month)
    {
        // ��н��ģ����ȡ����
        $gl = new includes_class_global();
        return $gl->get_salaryDept_info($year, $month);
    }

    /**
     * �������û�ȡ
     * @param $year
     * @param $month
     * @return array|bool
     */
    function getExpense_d($year, $month)
    {
        // ��ȡ���˵��ķ�����
        $otherDatasDao = new model_common_otherdatas();
        $filterType = $otherDatasDao->getConfig('deptFee_filter_costType');

        // ���˲����õķ�����
        $filterSql = $filterType ? " AND d.CostType NOT IN(" . util_jsonUtil::strBuild($filterType) . ")" : "";

        // ��ѯ�ű�
        $sql = "SELECT
                l.CostBelongDeptName AS deptName,SUM(d.CostMoney) AS fee,d.moduleName AS module
            FROM
            (
                SELECT CostBelongDeptName,BillNo FROM cost_summary_list l
                WHERE l.isNew = 1 AND l.Status = '���'
                    AND l.DetailType IN(1,3,5)
                    AND YEAR(l.PayDT) = " . $year . " AND MONTH(l.PayDT) = " . $month . "
                UNION ALL
                SELECT CostBelongDeptName,BillNo FROM cost_summary_list l
                WHERE l.isNew = 1 AND l.Status = '���'
                    AND l.DetailType = 4 AND l.projectId = 0
                    AND YEAR(l.PayDT) = " . $year . " AND MONTH(l.PayDT) = " . $month . "
            ) l
            INNER JOIN oa_finance_costshare d ON d.BillNo = l.BillNo
            WHERE 1 $filterSql
            GROUP BY l.CostBelongDeptName,d.moduleName";
        return $this->_db->getArray($sql);
    }

    /**
     * ������ϸ��ȡ
     * @param $year
     * @param $month
     * @return array|bool
     */
    function getExpenseDetail_d($year, $month) {
        // ��ȡ���˵��ķ�����
        $otherDatasDao = new model_common_otherdatas();
        $filterType = $otherDatasDao->getConfig('deptFee_filter_costType');

        // ���˲����õķ�����
        $filterSql = $filterType ? " AND d.CostType NOT IN(" . util_jsonUtil::strBuild($filterType) . ")" : "";

        $sql = "SELECT
                l.CostBelongDeptName AS deptName,l.BillNo,SUM(d.CostMoney) AS fee,d.moduleName AS module
            FROM
            (
                SELECT CostBelongDeptName,BillNo FROM cost_summary_list l
                WHERE l.isNew = 1 AND l.Status = '���'
                    AND l.DetailType IN(1,3,5)
                    AND YEAR(l.PayDT) = " . $year . " AND MONTH(l.PayDT) = " . $month . "
                UNION ALL
                SELECT CostBelongDeptName,BillNo FROM cost_summary_list l
                WHERE l.isNew = 1 AND l.Status = '���'
                    AND l.DetailType = 4 AND l.projectId = 0
                    AND YEAR(l.PayDT) = " . $year . " AND MONTH(l.PayDT) = " . $month . "
            ) l
            INNER JOIN oa_finance_costshare d ON d.BillNo = l.BillNo
            WHERE 1 $filterSql
            GROUP BY l.CostBelongDeptName,l.BillNo,d.moduleName";
        return $this->_db->getArray($sql);
    }

    /**
     * ��ȡ֧������
     * @param $year
     * @param $month
     * @return array|bool
     */
    function getPay_d($year, $month)
    {
        // ��������
        $period = $year . "." . $month;

        // ��ȡ���˵��ķ�����
        $otherDatasDao = new model_common_otherdatas();
        $filterType = $otherDatasDao->getConfig('deptFee_filter_costType');

        // ���˲����õķ�����
        $filterSql = $filterType ? " AND costTypeName NOT IN(" . util_jsonUtil::strBuild($filterType) . ")" : "";

        // ��ѯ�ű�
        $sql = "SELECT
		    belongDeptName AS deptName, SUM(costMoney) AS fee, moduleName AS module
        FROM (
            SELECT
                    belongDeptName, costMoney, moduleName
                FROM oa_finance_cost
                WHERE isTemp = 0 AND isDel = 0 AND auditStatus = 1
                    AND DetailType IN(1,3,5) AND inPeriod = '" . $period . "' $filterSql
            UNION ALL
            SELECT
                    belongDeptName, costMoney, moduleName
                FROM oa_finance_cost
                WHERE isTemp = 0 AND isDel = 0 AND auditStatus = 1
                    AND DetailType = 4 AND projectId = 0 AND inPeriod = '" . $period . "' $filterSql
            ) c
        GROUP BY belongDeptName, moduleName";
        return $this->_db->getArray($sql);
    }

    /**
     * ��ȡ֧������
     * @param $year
     * @param $month
     * @return array|bool
     */
    function getPayDetail_d($year, $month)
    {
        // ��������
        $period = $year . "." . $month;

        // ��ȡ���˵��ķ�����
        $otherDatasDao = new model_common_otherdatas();
        $filterType = $otherDatasDao->getConfig('deptFee_filter_costType');

        // ���˲����õķ�����
        $filterSql = $filterType ? " AND costTypeName NOT IN(" . util_jsonUtil::strBuild($filterType) . ")" : "";

        // ��ѯ�ű�
        $sql = "SELECT
            belongDeptName AS deptName, SUM(costMoney) AS fee, moduleName AS module, objCode
        FROM (
            SELECT
                    belongDeptName, costMoney, moduleName, objCode
                FROM oa_finance_cost
                WHERE isTemp = 0 AND isDel = 0 AND auditStatus = 1
                    AND DetailType IN(1,3,5) AND inPeriod = '" . $period . "' $filterSql
            UNION ALL
            SELECT
                    belongDeptName, costMoney, moduleName, objCode
                FROM oa_finance_cost
                WHERE isTemp = 0 AND isDel = 0 AND auditStatus = 1
                    AND DetailType = 4 AND projectId = 0 AND inPeriod = '" . $period . "' $filterSql
            ) c
        GROUP BY belongDeptName, moduleName, objCode";
        return $this->_db->getArray($sql);
    }

    /**
     * ��ȡPK��Ŀ���ŷ���
     * @param $year
     * @param $month
     * @return array
     */
    function getPK_d($year, $month)
    {
        // ��ȡǰһ����
        $prevYear = $month == 1 ? $year - 1 : $year;
        $prevMonth = $month == 1 ? 12 : $month - 1;

        // ���ص�����
        $rst = array();

        // ��ȡ��Ŀ��Ӧ��ӳ�䲿��
        $deptMappingDao = new model_bi_deptFee_deptMapping();
        $deptMapping = $deptMappingDao->getProjectMapping_d();
        $deptMapping = $deptMappingDao->dealPKMapping_d($deptMapping);

        // ����ת����ĿID
        $turnPKProjectIdList = $this->getTurnPKProjectIds_d($year, $month);

        // ������ڱ���ת����ĿID�����ȡ��Щ��Ŀ�ĵ��¾��������о���
        if (!empty($turnPKProjectIdList)) {
            $turnPKProjectIds = implode(',', $turnPKProjectIdList);
            // ��ȡ����ת����Ŀ���¾���
            $sql = "SELECT
                    projectId,
                    SUM(IF(thisYear = $year AND thisMonth = $month, fee, 0)) -
                        SUM(IF(thisYear = $prevYear AND thisMonth = $prevMonth, fee, 0)) AS fee
                FROM oa_esm_records_fielddetail
                WHERE projectId IN(" . $turnPKProjectIds . ")
                    AND ((thisYear = $prevYear AND thisMonth = $prevMonth)
                        OR (thisYear = $year AND thisMonth = $month))
                GROUP BY projectId HAVING fee <> 0";
            $rs = $this->_db->getArray($sql);
            // �����ѯ�����ݣ���ʼת��
            if ($rs) {
                foreach ($rs as $v) {
                    $dept = isset($deptMapping[$v['projectId']]) ? $deptMapping[$v['projectId']] : "";
                    if ($dept) {
                        // ����Ѿ����ڣ������
                        if (isset($rst[$dept])) {
                            $rst[$dept] = bcadd($v['fee'], $rst[$dept], 2);
                        } else {
                            $rst[$dept] = $v['fee'];
                        }
                    }
                }
            }

            // ��ȡ����ת����Ŀȫ������
            $sql = "SELECT
                    projectId, SUM(fee) AS fee
                FROM oa_esm_records_fielddetail
                WHERE projectId IN(" . $turnPKProjectIds . ") AND (thisYear = $year AND thisMonth = $month)
                GROUP BY projectId";
            $rs = $this->_db->getArray($sql);
            // �����ѯ�����ݣ���ʼת��
            if ($rs) {
                foreach ($rs as $v) {
                    $dept = isset($deptMapping[$v['projectId']]) ? $deptMapping[$v['projectId']] : "";
                    if ($dept) {
                        // ����Ѿ����ڣ������
                        if (isset($rst[$dept])) {
                            $rst[$dept] = bcsub($rst[$dept], $v['fee'], 2);
                        } else {
                            $rst[$dept] = -$v['fee'];
                        }
                    }
                }
            }
        }
        // δת����ĿID
        $PKProjectIdList = $this->getPKProjectIds_d($year, $month);

        // �������δת����ĿID�����ȡ��Щ��Ŀ�ĵ��¾���
        if (!empty($PKProjectIdList)) {
            $sql = "SELECT
                    projectId,
                    SUM(IF(thisYear = $year AND thisMonth = $month, fee, 0)) -
                        SUM(IF(thisYear = $prevYear AND thisMonth = $prevMonth, fee, 0)) AS fee
                FROM oa_esm_records_fielddetail
                WHERE projectId IN(" . implode(',', $PKProjectIdList) . ")
                    AND ((thisYear = $prevYear AND thisMonth = $prevMonth)
                        OR (thisYear = $year AND thisMonth = $month))
                GROUP BY projectId HAVING fee <> 0";
            $rs = $this->_db->getArray($sql);

            // �����ѯ�����ݣ���ʼת��
            if ($rs) {
                foreach ($rs as $v) {
                    $dept = isset($deptMapping[$v['projectId']]) ? $deptMapping[$v['projectId']] : "";
                    if ($dept) {
                        // ����Ѿ����ڣ������
                        if (isset($rst[$dept])) {
                            $rst[$dept] = bcadd($v['fee'], $rst[$dept], 2);
                        } else {
                            $rst[$dept] = $v['fee'];
                        }
                    }
                }
            }
        }

        $rst2 = array();
        foreach ($rst as $k => $v) {
            $rst2[] = array(
                'deptName' => $k,
                'fee' => $v
            );
        }
        return $rst2;
    }

    /**
     * ��ȡPK��Ŀ���ŷ��� - ��ϸ
     * @param $year
     * @param $month
     * @return array
     */
    function getPKDetail_d($year, $month)
    {
        // ��ȡǰһ����
        $prevYear = $month == 1 ? $year - 1 : $year;
        $prevMonth = $month == 1 ? 12 : $month - 1;

        // ���ص�����
        $rst = array();

        // ��ȡ��Ŀ��Ӧ��ӳ�䲿��
        $deptMappingDao = new model_bi_deptFee_deptMapping();
        $deptMapping = $deptMappingDao->getProjectMapping_d();
        $projectCodeMapping = $deptMappingDao->getProjectCodeMapping_d();

        // ����ת����ĿID
        $turnPKProjectIdList = $this->getTurnPKProjectIds_d($year, $month);

        // ������ڱ���ת����ĿID�����ȡ��Щ��Ŀ�ĵ��¾��������о���
        if (!empty($turnPKProjectIdList)) {
            $turnPKProjectIds = implode(',', $turnPKProjectIdList);
            // ��ȡ����ת����Ŀ���¾���
            $sql = "SELECT
                    projectId,
                    SUM(IF(thisYear = $year AND thisMonth = $month, fee, 0)) -
                        SUM(IF(thisYear = $prevYear AND thisMonth = $prevMonth, fee, 0)) AS fee
                FROM oa_esm_records_fielddetail
                WHERE projectId IN(" . $turnPKProjectIds . ")
                    AND ((thisYear = $prevYear AND thisMonth = $prevMonth)
                        OR (thisYear = $year AND thisMonth = $month))
                GROUP BY projectId HAVING fee <> 0";
            $rs = $this->_db->getArray($sql);
            // �����ѯ�����ݣ���ʼת��
            if ($rs) {
                foreach ($rs as $v) {
                    $rst[$v['projectId']] = array(
                        'projectCode' => $projectCodeMapping[$v['projectId']],
                        'deptName' => isset($deptMapping[$v['projectId']]) ? $deptMapping[$v['projectId']] : "",
                        'feeMonth' => $v['fee'],
                        'feeAll' => 0,
                        'feeNotTurn' => 0
                    );
                }
            }

            // ��ȡ����ת����Ŀȫ������
            $sql = "SELECT
                    projectId, SUM(fee) AS fee
                FROM oa_esm_records_fielddetail
                WHERE projectId IN(" . $turnPKProjectIds . ") AND (thisYear = $year AND thisMonth = $month)
                GROUP BY projectId";
            $rs = $this->_db->getArray($sql);
            // �����ѯ�����ݣ���ʼת��
            if ($rs) {
                foreach ($rs as $v) {
                    if (isset($rst[$v['projectId']])) {
                        $rst[$v['projectId']]['feeAll'] = $v['fee'];
                    } else {
                        $rst[$v['projectId']] = array(
                            'projectCode' => $projectCodeMapping[$v['projectId']],
                            'deptName' => isset($deptMapping[$v['projectId']]) ? $deptMapping[$v['projectId']] : "",
                            'feeMonth' => 0,
                            'feeAll' => $v['fee'],
                            'feeNotTurn' => 0
                        );
                    }
                }
            }
        }
        // δת����ĿID
        $PKProjectIdList = $this->getPKProjectIds_d($year, $month);

        // �������δת����ĿID�����ȡ��Щ��Ŀ�ĵ��¾���
        if (!empty($PKProjectIdList)) {
            $sql = "SELECT
                    projectId,
                    SUM(IF(thisYear = $year AND thisMonth = $month, fee, 0)) -
                        SUM(IF(thisYear = $prevYear AND thisMonth = $prevMonth, fee, 0)) AS fee
                FROM oa_esm_records_fielddetail
                WHERE projectId IN(" . implode(',', $PKProjectIdList) . ")
                    AND ((thisYear = $prevYear AND thisMonth = $prevMonth)
                        OR (thisYear = $year AND thisMonth = $month))
                GROUP BY projectId HAVING fee <> 0";
            $rs = $this->_db->getArray($sql);

            // �����ѯ�����ݣ���ʼת��
            if ($rs) {
                foreach ($rs as $v) {
                    if (isset($rst[$v['projectId']])) {
                        $rst[$v['projectId']]['feeNotTurn'] = $v['fee'];
                    } else {
                        $rst[$v['projectId']] = array(
                            'projectCode' => $projectCodeMapping[$v['projectId']],
                            'deptName' => isset($deptMapping[$v['projectId']]) ? $deptMapping[$v['projectId']] : "",
                            'feeMonth' => 0,
                            'feeAll' => 0,
                            'feeNotTurn' => $v['fee']
                        );
                    }
                }
            }
        }
        $rst2 = array();
        foreach ($rst as $v) {
            $rst2[] = $v;
        }
        return $rst2;
    }

    /**
     * ��ѯ����ת����PK��Ŀ
     * @param $year
     * @param $month
     * @return array
     */
    function getTurnPKProjectIds_d($year, $month)
    {
        // ���£�����ƥ����Ŀת��ʱ��
        $yearMonth = $year . str_pad($month, 2, '0', STR_PAD_LEFT);

        $sql = "SELECT id FROM oa_esm_project
            WHERE
            contractType = 'GCXMYD-04'
            AND contractId IN(
				SELECT id FROM oa_trialproject_trialproject
				WHERE turnStatus = '��ת��' AND DATE_FORMAT(turnDate, '%Y%m') = $yearMonth
		    )";
        $turnPKProject = $this->_db->getArray($sql);

        // ����ת����ĿID
        $turnPKProjectIdList = array();

        // ������ڱ���ת����Ŀ����ʼƴװ����
        if (!empty($turnPKProject)) {
            foreach ($turnPKProject as $v) {
                $turnPKProjectIdList[] = $v['id'];
            }
        }
        return $turnPKProjectIdList;
    }

    /**
     * ��ȡδת��PK��Ŀ
     * @param $year
     * @param $month
     * @return array
     */
    function getPKProjectIds_d($year, $month)
    {
        // ���£�����ƥ����Ŀת��ʱ��
        $yearMonth = $year . str_pad($month, 2, '0', STR_PAD_LEFT);

        // ��ȡδת��PK��Ŀ
        $sql = "SELECT id FROM oa_esm_project
            WHERE
                contractType = 'GCXMYD-04'
                AND contractId IN(
                    SELECT id FROM oa_trialproject_trialproject
                    WHERE ExaStatus = '���'
                    AND (turnStatus <> '��ת��' OR (turnStatus = '��ת��' AND DATE_FORMAT(turnDate, '%Y%m') > $yearMonth))
                )";
        $PKProject = $this->_db->getArray($sql);

        // ����ת����ĿID
        $PKProjectIdList = array();

        // ������ڱ���ת����Ŀ����ʼƴװ����
        if (!empty($PKProject)) {
            foreach ($PKProject as $v) {
                $PKProjectIdList[] = $v['id'];
            }
        }
        return $PKProjectIdList;
    }

    /**
     * ��ȡ���������б�
     * @param $beginYear
     * @param $beginMonth
     * @param $endYear
     * @param $endMonth
     * @return array|bool
     */
    function getCostTypeList_d($beginYear, $beginMonth, $endYear, $endMonth)
    {
//        $beginTime = strtotime($beginYear . '-' . $beginMonth . '-1');
//        $endTime = strtotime($endYear . '-' . $endMonth . '-1');
//
//        $sql = "SELECT costType FROM oa_bi_dept_fee WHERE thisTime BETWEEN $beginTime AND $endTime GROUP BY costType";
//        $data = $this->_db->getArray($sql);
//
//        // ƴ��һ�������ٷ���
//        foreach ($data as $k => $v) {
//            $data[$k]['key'] = 'c' . $k;
//        }
        // ���°�Ҫ��ĳɹ̶���ͷ
        $data = array(
            array('costType' => '����', 'key' => 'c0'),
            array('costType' => '����', 'key' => 'c1'),
            array('costType' => '֧��', 'key' => 'c2'),
            array('costType' => '�۾ɼ���̯', 'key' => 'c3'),
            array('costType' => 'PK', 'key' => 'c4'),
            array('costType' => '˰��', 'key' => 'c5'),
            array('costType' => '���ս�', 'key' => 'c6'),
            array('costType' => '����', 'key' => 'c7'),
            array('costType' => '����ֵ', 'key' => 'c8')
        );

        return $data;
    }

    /**
     * ��ȡ�����б�����
     * @param $beginYear
     * @param $beginMonth
     * @param $endYear
     * @param $endMonth
     * @return array|bool
     */
    function summaryList_d($beginYear, $beginMonth, $endYear, $endMonth)
    {
        // ��ȡ������ʾ�㼶
        $otherDatasDao = new model_common_otherdatas();
        $deptLevel = $otherDatasDao->getConfig('deptFee_filter_deptLevel');

        // ���û�����ò�����ʾ�㼶��ֱ�ӷ��ؿ�
        if ($deptLevel) {
            // �����ֶ�����
            $keyArr = array('c.business', 'c.secondDept', 'c.thirdDept', 'c.fourthDept');
            $keyStr = implode(',', array_slice($keyArr, 0, $deptLevel));
            $conditionArr = array("c.business = f.business", "c.secondDept = f.secondDept",
                "c.thirdDept = f.thirdDept", "c.fourthDept = f.fourthDept");
            $conditionStr = implode(' AND ', array_slice($conditionArr, 0, $deptLevel));

            // ʱ�䴦��
            $beginTime = strtotime($beginYear . '-' . $beginMonth . '-1');
            $endTime = strtotime($endYear . '-' . $endMonth . '-1');
            $sql = "SELECT c.costCategory,c.productLine,$keyStr,f.budget,f.fee FROM
                (
                    SELECT c.sortOrder,c.costCategory,c.productLine,c.filterStartDate,c.filterEndDate,$keyStr
                    FROM oa_bi_dept_mapping c GROUP BY $keyStr,c.costCategory,c.productLine
                ) c LEFT JOIN
                (
                    SELECT
                        $keyStr,SUM(c.budget) AS budget,SUM(c.fee) AS fee
                     FROM
                    (
                        SELECT
                            $keyStr,(budget) AS budget,(fee) AS fee
                        FROM
                            oa_bi_dept_fee c
                        WHERE
                            thisTime BETWEEN $beginTime AND $endTime
                        UNION ALL
                        SELECT
                            $keyStr,0 AS budget,(c.feeIn) AS fee
                        FROM
                            oa_bi_asset_depreciation s
                            LEFT JOIN oa_bi_asset_share c ON s.id = c.deprId
                        WHERE
                            thisTime BETWEEN $beginTime AND $endTime
                    ) c
                        GROUP BY $keyStr
                ) f ON $conditionStr 
                where
                 ((c.filterStartDate is null or c.filterStartDate = '') and (c.filterEndDate is null or c.filterEndDate = ''))
								or
                IF(c.filterStartDate <> '' and c.filterEndDate <> '',
                      ((c.filterStartDate BETWEEN $beginTime AND $endTime) and c.filterEndDate BETWEEN $beginTime AND $endTime),
						IF(c.filterStartDate <> '',
						  ((c.filterStartDate BETWEEN $beginTime AND $endTime)),
						  (c.filterEndDate BETWEEN $beginTime AND $endTime)
						)
				  )
                ORDER BY c.sortOrder,$keyStr";
            $data = $this->_db->getArray($sql);

            // ƴ��һ�������ٷ���
            foreach ($data as $k => $v) {
                $data[$k]['budget'] = $v['budget'] ? $v['budget'] : 0.00;
                $data[$k]['fee'] = $v['fee'] ? $v['fee'] : 0.00;
                $data[$k]['feeProcess'] = $v['budget'] ?
                    round(bcmul(bcdiv($v['fee'], $v['budget'], 6), 100, 4), 2) : 0.00;
            }
            return $data;
        } else {
            return array();
        }
    }

    /**
     * @param $rowNum
     * @param $beginYear
     * @param $beginMonth
     * @param $endYear
     * @param $endMonth
     * @param $business
     * @param $secondDept
     * @param $thirdDept
     * @param $fourthDept
     * @return array|bool
     */
    function summaryDetail_d($rowNum, $beginYear, $beginMonth, $endYear, $endMonth, $business, $secondDept,
                             $thirdDept, $fourthDept)
    {
        // ��ȡ������ʾ�㼶
        $otherDatasDao = new model_common_otherdatas();
        $deptLevel = $otherDatasDao->getConfig('deptFee_filter_deptLevel');

        // ���û�����ò�����ʾ�㼶��ֱ�ӷ��ؿ�
        if ($deptLevel) {
            $beginTime = strtotime($beginYear . '-' . $beginMonth . '-1');
            $endTime = strtotime($endYear . '-' . $endMonth . '-1');

            // �����ֶ�����
            $keyArr = array(
                "AND c.business = '$business'", "AND c.secondDept = '$secondDept'",
                "AND c.thirdDept = '$thirdDept'", "AND c.fourthDept = '$fourthDept'"
            );
            $keyStr = implode(' ', array_slice($keyArr, 0, $deptLevel));

            $sql = "SELECT
                costType,SUM(fee) AS fee
            FROM
                oa_bi_dept_fee c
            WHERE thisTime BETWEEN $beginTime AND $endTime
                $keyStr
            GROUP BY costType";
            $data = $this->_db->getArray($sql);

            $rst = array();

            // ƴ��һ�������ٷ���
            foreach ($data as $v) {
                $rst[$v['costType']] = $v['fee'];
            }

            // ��ȡ�豸����
            $sql = "SELECT SUM(c.feeIn) AS fee
                FROM
                    oa_bi_asset_depreciation s
                    LEFT JOIN oa_bi_asset_share c ON s.id = c.deprId
                    $keyStr
                WHERE thisTime BETWEEN $beginTime AND $endTime $keyStr";
            $deviceFeeRow = $this->get_one($sql);

            if ($deviceFeeRow) {
                $rst['�۾ɼ���̯'] = isset($rst['�۾ɼ���̯']) ? bcadd($rst['�۾ɼ���̯'], $deviceFeeRow['fee'], 2) : $deviceFeeRow['fee'];
            }

            return array('rowNum' => $rowNum, 'rows' => $rst);
        } else {
            return array('rowNum' => $rowNum, 'rows' => array());
        }
    }

    /**
     * �������û���
     * @param $beginYear
     * @param $beginMonth
     * @param $endYear
     * @param $endMonth
     * @param $isImport
     * @return array|bool
     */
    function otherFeeSummary_d($beginYear, $beginMonth, $endYear, $endMonth, $isImport)
    {
        $beginStamp = strtotime($beginYear . "-" . $beginMonth . "-1");
        $endStamp = strtotime($endYear . "-" . $endMonth . "-1");

        // ��ѯ�ű� - �ϼƲ���
        $sql = "SELECT business,secondDept,thirdDept,fourthDept,costType,SUM(budget) AS budget, SUM(fee) AS fee
                FROM oa_bi_dept_fee WHERE thisTime BETWEEN $beginStamp AND $endStamp AND isImport = $isImport
                GROUP BY business,secondDept,thirdDept,fourthDept,costType";
        $data = $this->_db->getArray($sql);

        if (empty($data)) {
            return array();
        }

        // ��ѯ�ű� - �����
        $sql = "SELECT business,secondDept,thirdDept,fourthDept,costType,thisYear,thisMonth,fee
                FROM oa_bi_dept_fee WHERE thisTime BETWEEN $beginStamp AND $endStamp AND isImport = $isImport";
        $itemData = $this->_db->getArray($sql);

        // ����ת��
        $itemDataMap = array();
        foreach ($itemData as $k => $v) {
            $key = $v['business'] . '_' . $v['secondDept'] . '_' . $v['thirdDept'] . '_' .
                $v['fourthDept'] . '_' . $v['costType'] . $v['thisYear'] . str_pad($v['thisMonth'], 2, 0, STR_PAD_LEFT);
            $itemDataMap[$key] = $v;
        }

        // �����´洢�ֶ�
        foreach ($data as $k => $v) {
            for ($i = $beginYear; $i <= $endYear; $i++) {
                $begin = 1;
                $end = 12;
                if ($i == $beginYear) {
                    $begin = $beginMonth;
                }
                if ($i == $endYear) {
                    $end = $endMonth;
                }
                // �·�����Ⱦ
                for ($j = $begin; $j <= $end; $j++) {
                    // �����·�
                    $yearMonth = $j >= 10 ? $i . $j : $i . str_pad($j, 2, 0, STR_PAD_LEFT);

                    // ����key
                    $key = $v['business'] . '_' . $v['secondDept'] . '_' . $v['thirdDept'] . '_' .
                        $v['fourthDept'] . '_' . $v['costType'] . $yearMonth;

                    // �����µ�����
                    $data[$k]['d' . $yearMonth] = isset($itemDataMap[$key]) ? $itemDataMap[$key]['fee'] : 0;
                }
            }
        }
        return $data;
    }

    function otherFeeDetail_d($beginYear, $beginMonth, $endYear, $endMonth, $isImport)
    {
        $beginStamp = strtotime($beginYear . "-" . $beginMonth . "-1");
        $endStamp = strtotime($endYear . "-" . $endMonth . "-1");

        // ��ѯ�ű� - �����
        $sql = "SELECT business,secondDept,thirdDept,fourthDept,costType,thisYear,thisMonth,fee,budget
                FROM oa_bi_dept_fee WHERE thisTime BETWEEN $beginStamp AND $endStamp AND isImport = $isImport
                ORDER BY thisYear DESC,thisMonth DESC";
        $data = $this->_db->getArray($sql);

        if (empty($data)) {
            return array();
        }
        return $data;
    }

    // �������
    private $importTitle = array(
        '��ҵ��' => 'business', '��������' => 'secondDept', '��������' => 'thirdDept', '�ļ�����' => 'fourthDept',
        '��' => 'thisYear', '��' => 'thisMonth', '��������' => 'costType', 'Ԥ��' => 'budget',
        '����' => 'fee'
    );

    // �������
    private $needTitle = array(
        '��ҵ��' => 'business', '��������' => 'secondDept', '��' => 'thisYear', '��' => 'thisMonth',
        '��������' => 'costType', 'Ԥ��' => 'budget', '����' => 'fee'
    );

    /**
     * ���뷽��
     * @return array
     */
    function import_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array(); //�������
        $tempArr = array();
        // �жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name, 1);
            spl_autoload_register("__autoload");

            $titleRow = $excelData[0];
            unset($excelData[0]);

            if (is_array($excelData)) {
                foreach ($excelData as $key => $val) {
                    // ���д���
                    if ($val[0] == "" && $val[1] == "") {
                        continue;
                    }

                    $actNum = $key + 2;
                    // ��ʽ������
                    $val = $this->formatArray_d($val, $titleRow);

                    $allIn = true;

                    // ����У��
                    foreach ($this->needTitle as $k => $v) {
                        if (!isset($val[$v])) {
                            $resultArr[] = array(
                                'docCode' => '��' . $actNum . '������', 'result' => 'û����д' . $k
                            );
                            $allIn = false;
                            break;
                        }
                    }

                    // ������ڷǱ��������
                    if (!$allIn) {
                        continue;
                    }

                    // ���
                    if ($val['thisYear'] > date('Y') || $val['thisYear'] < 2000) {
                        $resultArr[] = array(
                            'docCode' => '��' . $actNum . '������', 'result' => '�����д����'
                        );
                        continue;
                    }

                    // �·�
                    if ($val['thisMonth'] > 12 || $val['thisMonth'] < 1) {
                        $resultArr[] = array(
                            'docCode' => '��' . $actNum . '������', 'result' => '�·���д����'
                        );
                        continue;
                    }

                    // Ԥ��
                    if (!is_numeric($val['budget'])) {
                        $resultArr[] = array(
                            'docCode' => '��' . $actNum . '������', 'result' => 'Ԥ����д����'
                        );
                        continue;
                    }

                    // ����
                    if (!is_numeric($val['fee'])) {
                        $resultArr[] = array(
                            'docCode' => '��' . $actNum . '������', 'result' => '������д����'
                        );
                        continue;
                    }

                    // �����־
                    $val['isImport'] = 1;

                    try {
                        // ����ƴװ
                        $conditionArr = array(
                            'business' => $val['business'], 'secondDept' => $val['secondDept'],
                            'thirdDept' => $val['thirdDept'], 'fourthDept' => $val['fourthDept'],
                            'costType' => $val['costType'], 'thisYear' => $val['thisYear'],
                            'thisMonth' => $val['thisMonth'], 'isImport' => 1
                        );
                        // ���Ԥ��;���Ϊ0��ɾ��
                        if ($val['budget'] == 0 && $val['fee'] == 0) {
                            $this->delete($conditionArr);
                        } else {
                            // ƥ���Ƿ��Ѵ���
                            $deptFee = $this->find($conditionArr, null, 'id');
                            if ($deptFee) {
                                //���·���
                                $this->update($deptFee, array(
                                        'budget' => $val['budget'], 'fee' => $val['fee']
                                    )
                                );
                                $tempArr['result'] = '���³ɹ�';
                            } else {
                                $val['thisTime'] = strtotime($val['thisYear'] . '-' . $val['thisMonth'] . '-1');
                                $val['thirdDept'] = isset($val['thirdDept']) ? $val['thirdDept'] : "";
                                $val['fourthDept'] = isset($val['fourthDept']) ? $val['fourthDept'] : "";
                                $val['orgDept'] = isset($val['orgDept']) ? $val['orgDept'] : "";
                                $this->add_d($val);
                                $tempArr['result'] = '����ɹ�';
                            }
                        }
                    } catch (Exception $e) {
                        $tempArr['result'] = '����ʧ��' . $e->getMessage();
                    }

                    $tempArr['docCode'] = '��' . $actNum . '������';
                    array_push($resultArr, $tempArr);
                }
                return $resultArr;
            } else {
                msg("�ļ������ڿ�ʶ������!");
            }
        } else {
            msg("�ϴ��ļ����Ͳ���EXCEL!");
        }
    }

    /**
     * ƥ��excel�ֶ�
     * @param $data
     * @param $titleRow
     * @return mixed
     */
    function formatArray_d($data, $titleRow)
    {
        // �����µ�����
        foreach ($titleRow as $k => $v) {
            // �������Ϊ�գ���ɾ��
            if (trim($data[$k]) === '') {
                unset($data[$k]);
                continue;
            }
            // ��������Ѷ������ݣ�����м�ֵ�滻
            if (isset($this->importTitle[$v])) {
                // ��ʽ����������
                $data[$this->importTitle[$v]] = trim($data[$k]);
            }
            // ������ɺ�ɾ������
            unset($data[$k]);
        }
        return $data;
    }
}