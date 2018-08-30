<?php

/**
 * @author show
 * @Date 2013年8月23日 17:19:19
 * @version 1.0
 * @description:日志考核结果设置 Model层
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
     * 获取分数
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
     * 获取考核结果映射
     * @return array
     */
    function getResultMap_d() {
        $data = $this->findAll();
        $resultMap = array();

        if (!empty($data)) {
            // 建立映射
            foreach ($data as $v) {
                $resultMap[$v['id']] = $v;
            }
        }
        return $resultMap;
    }
}