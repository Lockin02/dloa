<?php

/**
 * @author show
 * @Date 2016��05��12�� 15:43:22
 * @version 1.0
 * @description:��Ŀ��Ʊ���� Model��
 */
class model_engineering_records_esmflights extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_esm_records_flights";
        $this->sql_map = "engineering/records/esmflightsSql.php";
        parent::__construct();
    }

    /**
     * @param $projectId
     * @return array|bool
     */
    function getDetailGroupMonth_d($projectId) {
        return $this->_db->getArray("SELECT CONCAT(thisYear,'.',thisMonth) AS yearMonth,fee AS actFee
            FROM oa_esm_records_flights WHERE projectId = $projectId");
    }
}