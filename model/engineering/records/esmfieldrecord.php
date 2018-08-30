<?php

/**
 * @author show
 * @Date 2014年12月23日 15:43:22
 * @version 1.0
 * @description:项目现场决算 Model层
 */
class model_engineering_records_esmfieldrecord extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_records_field";
        $this->sql_map = "engineering/records/esmfieldrecordSql.php";
        parent::__construct();
    }

    // 不同费用分类
    private $_category = array(
        'rentCar' => 'model_engineering_records_strategy_rentCar', //租车费用
        'flightsShare' => 'model_engineering_records_strategy_flightsShare', // 费用分摊 - 机票
        'payables' => 'model_engineering_records_strategy_payables', // 费用分摊
        'subsidy' => '', // 报销 - 补贴 - 由于计算规则问题，所以补贴的决算并入到报销决算中处理
        'subsidyProvision' => 'model_engineering_records_strategy_subsidyProvision', // 计提补贴
        'field' => 'model_engineering_records_strategy_field' // 报销 - 除补贴外的所有
    );

    /**
     * 数据初始化功能
     * @param $category
     * @return string
     */
    function init_d($category)
    {
        if (!$category) {
            return 'undefined category';
        }

        // 匹配对应的类型
        $categoryClass = $this->_category[$category];

        try {
            // 实例化
            $categoryDao = new $categoryClass();

            // 调用数据更新
            return "update success, update " . $categoryDao->init_d($this, $category) . " rows";
        } catch (Exception $e) {
            return 'system error：' . $e->getMessage();
        }
    }

    /**
     * 业务决算信息更新
     * @param $category
     * @param $year
     * @param $month
     * @param $sourceParam
     * @throws Exception
     */
    function businessFeeUpdate_d($category, $year = '', $month = '', $sourceParam = array())
    {
        // 匹配对应的类型
        $categoryClass = $this->_category[$category];

        $year = $year ? $year : date('Y', day_date); // 年定义
        $month = $month ? $month : date('m', day_date); // 月定义

        try {
            // 实例化
            $categoryDao = new $categoryClass();

            // 调用数据更新
            $categoryDao->feeUpdate_d($this, $category, $year, $month, $sourceParam);

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 业务决算信息撤销
     * @param $category
     * @param $year
     * @param $month
     * @param $sourceParam
     * @throws Exception
     */
    function businessFeeCancel_d($category, $year = '', $month = '', $sourceParam = array())
    {
        // 匹配对应的类型
        $categoryClass = $this->_category[$category];

        $year = $year ? $year : date('Y', day_date); // 年定义
        $month = $month ? $month : date('m', day_date); // 月定义

        try {
            // 实例化
            $categoryDao = new $categoryClass();

            // 调用数据更新
            $categoryDao->feeCancel_d($this, $category, $year, $month, $sourceParam);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 业务数据获取
     * @param $category
     * @param string $year
     * @param string $month
     * @param array $sourceParam
     */
    function businessFeeList_d($category, $year = '', $month = '', $sourceParam = array())
    {
        // 匹配对应的类型
        $categoryClass = $this->_category[$category];

        // 实例化
        $categoryDao = new $categoryClass();

        // 调用数据更新
        return $categoryDao->feeList_d($this, $category, $year, $month, $sourceParam);
    }

    /**
     * 调用自定义的方法
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
        // 实例化
        $categoryDao = new $categoryClass();

        // 调用数据更新
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
     * 获取没有变化的数据
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
     * 获取没有变化的数据
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