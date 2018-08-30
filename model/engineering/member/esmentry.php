<?php

/**
 * @author tse
 * @Date 2014��3��3�� 15:48:16
 * @version 1.0
 * @description:��Ա����� Model��
 */
class model_engineering_member_esmentry extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_entry";
        $this->sql_map = "engineering/member/esmentrySql.php";
        parent::__construct();
    }

    /**
     * ����Ƿ������־�ڱ��μ�����Ŀ����־(ǰ�������ж�������־)
     * @param $userId
     * @param $projectId
     * @return bool
     */
    function checkExistRangeLog_d($userId, $projectId)
    {
        $entryArr = $this->findAll(array('projectId' => $projectId, 'memberId' => $userId));
        if ($entryArr) {
            $sql = "select id from oa_esm_worklog where projectId = " . $projectId . " and createId = '" . $userId . "' ";
            foreach ($entryArr as $val) {
                $sql .= " and executionDate not between '" . $val['beginDate'] . "' and '" . $val['endDate'] . "'";
            }
            if ($this->findSql($sql)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * ������Ա������Ŀ����
     * �������������Ա�������ݣ�������Ա�����е�������ʾ��Ա������Ŀ
     * @param $data
     * @param $projectId
     * @param $userIds
     * @param $info
     * @return mixed
     */
    function dealEntry_d($data, $projectId = '', $userIds = '', $info = array())
    {
        // ����ӳ��
        $entryMapping = $projectId ? $this->getEntryMap_d($projectId, $userIds) : $info;

        foreach ($data as $k => $v) {
            if (!empty($entryMapping[$userIds]) && !in_array($v['executionTimes'], $entryMapping[$userIds])) {
                $data[$k]['notEntryProject'] = 1;
                $data[$k]['noNeed'] = 1;
            }
        }
        return $data;
    }

    /**
     * ��ȡ������
     * @param $projectId
     * @param $userIds
     * @return array [ʱ���, ʱ���, ʱ���]
     */
    function getEntryMap_d($projectId, $userIds)
    {
        // ��ѯ��Ա����ӳ��
        $data = $this->_db->getArray("SELECT * FROM " . $this->tbl_name . "
            WHERE projectId = " . $projectId . " AND memberId IN(" . util_jsonUtil::strBuild($userIds) . ")");

        // ���صĳ����
        $entryMapping = array();

        // ����ӳ���
        foreach ($data as $v) {
            $begin = strtotime($v['beginDate']);
            $end = strtotime($v['endDate']);

            for ($i = $begin; $i <= $end; $i += 86400) {
                $entryMapping[$v['memberId']][] = $i;
            }
        }
        return $entryMapping;
    }

    /**
     * ������Ա������Ϣ
     * @param $projectId
     * @return array|bool
     */
    function getProjectMemberEntryList_d($projectId) {
        $sql = "SELECT memberId,memberName,MIN(beginDate) AS beginDate, MIN(endDate) AS endDate
            FROM oa_esm_project_entry WHERE projectId = $projectId GROUP BY memberId";
        return $this->_db->getArray($sql);
    }
}