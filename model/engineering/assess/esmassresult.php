<?php

/**
 * @author show
 * @Date 2013��8��23�� 17:19:19
 * @version 1.0
 * @description:��־���˽������ Model��
 */
class model_engineering_assess_esmassresult extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_ass_result";
        $this->sql_map = "engineering/assess/esmassresultSql.php";
        parent :: __construct();
    }

    /**
     * ��ȡ����
     */
    function getValScore_d($resultVal)
    {
        $obj = $this->find(array('resultVal' => $resultVal), 'resultScore');
        if ($obj) {
            return $obj['resultScore'];
        } else {
            return 0;
        }
    }

    /**
     * ��ȡ���˽��ӳ��
     * @return array
     */
    function getResultMap_d() {
        $data = $this->findAll();
        $resultMap = array();

        if (!empty($data)) {
            // ����ӳ��
            foreach ($data as $v) {
                $resultMap[$v['id']] = $v;
            }
        }
        return $resultMap;
    }
}