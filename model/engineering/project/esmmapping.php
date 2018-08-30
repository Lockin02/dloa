<?php

/**
 * @author show
 * @Date 2014年7月26日 14:59:37
 * @version 1.0
 * @description:试用项目映射表 Model层
 */
class model_engineering_project_esmmapping extends model_base
{
    function __construct() {
        $this->tbl_name = "oa_esm_project_mapping";
        $this->sql_map = "engineering/project/esmmappingSql.php";
        parent::__construct();
    }

    /**
     * 重新映射正式项目/PK项目关系
     */
    function remapping_d() {
        // 设置不超时
        set_time_limit(0);

        // 查询所有正式项目
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectArr = $esmprojectDao->findAll(array('contractType' => 'GCXMYD-01'), null, 'id,contractId,productLine');

        // 试用项目类
        $trialproject = new model_projectmanagent_trialproject_trialproject();

        // 插入的数据
        $inDatas = array();

        try {
            // 删除映射
            $this->delete(null);

            // 循环建立索引
            foreach ($esmprojectArr as $v) {
                $trialStr = $trialproject->getTrialIdByconId($v['contractId']);
                if ($trialStr != null && $trialStr != ',') {
                    if (substr($trialStr, -1) == ',') {
                        $trialStr = substr($trialStr, 0, -1);
                    }
                    $trialInfo = $this->_db->getArray("SELECT id FROM oa_esm_project WHERE contractId IN($trialStr)
                        AND contractType = 'GCXMYD-04' AND productLine = '" . $v['productLine'] . "'");
                    if ($trialInfo) {
                        foreach ($trialInfo as $val) {
                            // 建立映射放入数据
                            $inDatas[] = array('projectId' => $v['id'], 'pkProjectId' => $val['id']);
                        }
                    }
                }
            }
            if ($inDatas) {
                $this->createBatch($inDatas);
            }
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * 通过传入项目id，返回一个id 指向 PK id 的哈希数组
     * @param $projectIds
     * @return array|bool
     */
    function getProjectHash_d($projectIds) {
        $datas = $this->_db->getArray("SELECT projectId,pkProjectId FROM " . $this->table . " WHERE projectId IN(" . $projectIds . ")");
        if ($datas) {
            $hashDatas = array(); // 要返回的哈希数组
            foreach ($datas as $v) {
                $hashDatas[$v['projectId']][] = $v['pkProjectId'];
            }
            return $hashDatas;
        } else {
            return false;
        }
    }

    /**
     * 通过传入一个项目id，返回PK id 串
     * @param $projectId
     * @return bool|string
     */
    function getProjectString_d($projectId) {
        $datas = $this->_db->getArray("SELECT pkProjectId FROM " . $this->tbl_name . " WHERE projectId = " . $projectId);
        if ($datas) {
            $stringDatas = array(); // 要返回的哈希数组
            foreach ($datas as $v) {
                $stringDatas[] = $v['pkProjectId'];
            }
            return implode(',', $stringDatas);
        } else {
            return false;
        }
    }

    /**
     * 返回所有映射关系 ['projectId' => ['projectId1', 'projectId2']]
     */
    function getAllMapping_d() {
        $datas = $this->findAll(null, null, 'projectId,pkProjectId');
        if ($datas) {
            $hashDatas = array(); // 要返回的哈希数组
            foreach ($datas as $v) {
                $hashDatas[$v['projectId']][] = $v['pkProjectId'];
            }
            return $hashDatas;
        } else {
            return false;
        }
    }
}