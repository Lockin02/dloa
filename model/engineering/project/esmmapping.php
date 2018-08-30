<?php

/**
 * @author show
 * @Date 2014��7��26�� 14:59:37
 * @version 1.0
 * @description:������Ŀӳ��� Model��
 */
class model_engineering_project_esmmapping extends model_base
{
    function __construct() {
        $this->tbl_name = "oa_esm_project_mapping";
        $this->sql_map = "engineering/project/esmmappingSql.php";
        parent::__construct();
    }

    /**
     * ����ӳ����ʽ��Ŀ/PK��Ŀ��ϵ
     */
    function remapping_d() {
        // ���ò���ʱ
        set_time_limit(0);

        // ��ѯ������ʽ��Ŀ
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectArr = $esmprojectDao->findAll(array('contractType' => 'GCXMYD-01'), null, 'id,contractId,productLine');

        // ������Ŀ��
        $trialproject = new model_projectmanagent_trialproject_trialproject();

        // ���������
        $inDatas = array();

        try {
            // ɾ��ӳ��
            $this->delete(null);

            // ѭ����������
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
                            // ����ӳ���������
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
     * ͨ��������Ŀid������һ��id ָ�� PK id �Ĺ�ϣ����
     * @param $projectIds
     * @return array|bool
     */
    function getProjectHash_d($projectIds) {
        $datas = $this->_db->getArray("SELECT projectId,pkProjectId FROM " . $this->table . " WHERE projectId IN(" . $projectIds . ")");
        if ($datas) {
            $hashDatas = array(); // Ҫ���صĹ�ϣ����
            foreach ($datas as $v) {
                $hashDatas[$v['projectId']][] = $v['pkProjectId'];
            }
            return $hashDatas;
        } else {
            return false;
        }
    }

    /**
     * ͨ������һ����Ŀid������PK id ��
     * @param $projectId
     * @return bool|string
     */
    function getProjectString_d($projectId) {
        $datas = $this->_db->getArray("SELECT pkProjectId FROM " . $this->tbl_name . " WHERE projectId = " . $projectId);
        if ($datas) {
            $stringDatas = array(); // Ҫ���صĹ�ϣ����
            foreach ($datas as $v) {
                $stringDatas[] = $v['pkProjectId'];
            }
            return implode(',', $stringDatas);
        } else {
            return false;
        }
    }

    /**
     * ��������ӳ���ϵ ['projectId' => ['projectId1', 'projectId2']]
     */
    function getAllMapping_d() {
        $datas = $this->findAll(null, null, 'projectId,pkProjectId');
        if ($datas) {
            $hashDatas = array(); // Ҫ���صĹ�ϣ����
            foreach ($datas as $v) {
                $hashDatas[$v['projectId']][] = $v['pkProjectId'];
            }
            return $hashDatas;
        } else {
            return false;
        }
    }
}