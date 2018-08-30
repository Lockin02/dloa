<?php

/**
 * @author show
 * @Date 2014��12��19�� 15:41:46
 * @version 1.0
 * @description:������Ŀ�����¼
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
     * �б�
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
     * ��ȡ����
     */
    function c_getDates()
    {
        // ��ȡ��һ�������һ������
        $all = $this->service->_db->getArray("SELECT versionNo FROM oa_esm_record_income GROUP BY versionNo ORDER BY versionNo DESC");

        // ���
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