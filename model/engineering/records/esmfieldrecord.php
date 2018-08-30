<?php

/**
 * @author show
 * @Date 2014��12��23�� 15:43:22
 * @version 1.0
 * @description:��Ŀ�ֳ����� Model��
 */
class model_engineering_records_esmfieldrecord extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_records_field";
        $this->sql_map = "engineering/records/esmfieldrecordSql.php";
        parent::__construct();
    }

    // ��ͬ���÷���
    private $_category = array(
        'rentCar' => 'model_engineering_records_strategy_rentCar', //�⳵����
        'flightsShare' => 'model_engineering_records_strategy_flightsShare', // ���÷�̯ - ��Ʊ
        'payables' => 'model_engineering_records_strategy_payables', // ���÷�̯
        'subsidy' => '', // ���� - ���� - ���ڼ���������⣬���Բ����ľ��㲢�뵽���������д���
        'subsidyProvision' => 'model_engineering_records_strategy_subsidyProvision', // ���Ჹ��
        'field' => 'model_engineering_records_strategy_field' // ���� - �������������
    );

    /**
     * ���ݳ�ʼ������
     * @param $category
     * @return string
     */
    function init_d($category)
    {
        if (!$category) {
            return 'undefined category';
        }

        // ƥ���Ӧ������
        $categoryClass = $this->_category[$category];

        try {
            // ʵ����
            $categoryDao = new $categoryClass();

            // �������ݸ���
            return "update success, update " . $categoryDao->init_d($this, $category) . " rows";
        } catch (Exception $e) {
            return 'system error��' . $e->getMessage();
        }
    }

    /**
     * ҵ�������Ϣ����
     * @param $category
     * @param $year
     * @param $month
     * @param $sourceParam
     * @throws Exception
     */
    function businessFeeUpdate_d($category, $year = '', $month = '', $sourceParam = array())
    {
        // ƥ���Ӧ������
        $categoryClass = $this->_category[$category];

        $year = $year ? $year : date('Y', day_date); // �궨��
        $month = $month ? $month : date('m', day_date); // �¶���

        try {
            // ʵ����
            $categoryDao = new $categoryClass();

            // �������ݸ���
            $categoryDao->feeUpdate_d($this, $category, $year, $month, $sourceParam);

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ҵ�������Ϣ����
     * @param $category
     * @param $year
     * @param $month
     * @param $sourceParam
     * @throws Exception
     */
    function businessFeeCancel_d($category, $year = '', $month = '', $sourceParam = array())
    {
        // ƥ���Ӧ������
        $categoryClass = $this->_category[$category];

        $year = $year ? $year : date('Y', day_date); // �궨��
        $month = $month ? $month : date('m', day_date); // �¶���

        try {
            // ʵ����
            $categoryDao = new $categoryClass();

            // �������ݸ���
            $categoryDao->feeCancel_d($this, $category, $year, $month, $sourceParam);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ҵ�����ݻ�ȡ
     * @param $category
     * @param string $year
     * @param string $month
     * @param array $sourceParam
     */
    function businessFeeList_d($category, $year = '', $month = '', $sourceParam = array())
    {
        // ƥ���Ӧ������
        $categoryClass = $this->_category[$category];

        // ʵ����
        $categoryDao = new $categoryClass();

        // �������ݸ���
        return $categoryDao->feeList_d($this, $category, $year, $month, $sourceParam);
    }

    /**
     * �����Զ���ķ���
     * @param $name
     * @param $arguments
     * @return bool
     */
    function __call($name, $arguments)
    {
        if (!$arguments[0]) {
            return false;
        }

        $categoryClass = $this->_category[$arguments[0]];
        if (!$categoryClass) {
            return false;
        }
        // ʵ����
        $categoryDao = new $categoryClass();

        // �������ݸ���
        return $categoryDao->$name($this, $arguments);
    }

    /**
     * @param $thisYear
     * @param $thisMonth
     * @param $feeFieldType
     * @param null $projectIds
     * @return bool
     * @throws Exception
     */
    function deleteInfo_d($thisYear, $thisMonth, $feeFieldType, $projectIds = null)
    {
        if (!$thisYear || !$thisMonth || !$feeFieldType) {
            return false;
        }
        $sql = "DELETE FROM " . $this->tbl_name . " WHERE thisYear = " . $thisYear .
            " AND thisMonth = " . $thisMonth . " AND feeFieldType = '" . $feeFieldType . "'";

        if ($projectIds) {
            $sql .= " AND projectId IN($projectIds)";
        }

        try {
            $this->_db->query($sql);
        } catch (Exception $e) {
            throw $e;
        }
        return true;
    }

    /**
     * ��ȡû�б仯������
     * @param $thisYear
     * @param $thisMonth
     * @param $feeFieldTypes
     * @param $projectIds
     * @return bool
     * @throws Exception
     */
    function getNoChangeData_d($thisYear, $thisMonth, $feeFieldTypes, $projectIds = null)
    {
        if (!$thisYear || !$thisMonth || !$feeFieldTypes) {
            return false;
        }
        $sql = "SELECT * FROM oa_esm_records_field WHERE thisYear = $thisYear AND thisMonth = $thisMonth
            AND feeFieldType IN(" . util_jsonUtil::strBuild($feeFieldTypes) . ")";

        if ($projectIds) {
            $sql .= " AND projectId IN($projectIds)";
        }

        try {
            return $this->_db->getArray($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ��ȡû�б仯������
     * @param $thisYear
     * @param $thisMonth
     * @param $feeFieldTypes
     * @param $projectIds
     * @param $createTime
     * @return bool
     * @throws Exception
     */
    function updateNoChangeData_d($thisYear, $thisMonth, $feeFieldTypes, $projectIds = null, $createTime)
    {
        if (!$thisYear || !$thisMonth || !$feeFieldTypes || !$createTime) {
            return false;
        }
        $sql = "UPDATE oa_esm_records_field SET createTime = '$createTime'
            WHERE thisYear = $thisYear AND thisMonth = $thisMonth
                AND feeFieldType IN(" . util_jsonUtil::strBuild($feeFieldTypes) . ")";

        if ($projectIds) {
            $sql .= " AND projectId IN($projectIds)";
        }

        try {
            $this->_db->query($sql);
        } catch (Exception $e) {
            throw $e;
        }
        return true;
    }
}