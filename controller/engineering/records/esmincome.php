<?php

/**
 * @author show
 * @Date 2014年12月19日 15:41:46
 * @version 1.0
 * @description:工程项目收入记录
 */
class controller_engineering_records_esmincome extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmincome";
        $this->objPath = "engineering_records";
        parent::__construct();
    }

    /**
     * 列表
     */
    function c_page()
    {
        $this->assignFunc($_GET);
        $this->view('list');
    }

    function c_updateIncome()
    {
        set_time_limit(0);
        echo $this->service->updateIncome_d($_POST['projectCode']);
    }

    /**
     * 获取日期
     */
    function c_getDates()
    {
        // 获取第一条和最后一条数据
        $all = $this->service->_db->getArray("SELECT versionNo FROM oa_esm_record_income GROUP BY versionNo ORDER BY versionNo DESC");

        // 结果
        $result = array();

        if ($all) {
            foreach ($all as $v) {
                $result[] = array(
                    'dataName' => $v['versionNo'],
                    'dataCode' => $v['versionNo']
                );
            }
        }
        echo util_jsonUtil::encode($result);
    }
}