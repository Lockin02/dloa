<?php

/**
 * @author tse
 * @Date 2014年3月3日 15:48:16
 * @version 1.0
 * @description:人员出入表 Model层
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
     * 检查是否存在日志在本次加入项目的日志(前提是已判断了有日志)
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
     * 加载人员出入项目数据
     * 规则：如果存在人员出入数据，则不在人员数据中的日期显示人员不在项目
     * @param $data
     * @param $projectId
     * @param $userIds
     * @param $info
     * @return mixed
     */
    function dealEntry_d($data, $projectId = '', $userIds = '', $info = array())
    {
        // 出入映射
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
     * 获取出入天
     * @param $projectId
     * @param $userIds
     * @return array [时间戳, 时间戳, 时间戳]
     */
    function getEntryMap_d($projectId, $userIds)
    {
        // 查询人员出入映射
        $data = $this->_db->getArray("SELECT * FROM " . $this->tbl_name . "
            WHERE projectId = " . $projectId . " AND memberId IN(" . util_jsonUtil::strBuild($userIds) . ")");

        // 返回的出入表
        $entryMapping = array();

        // 构建映射表
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
     * 更新人员出入信息
     * @param $projectId
     * @return array|bool
     */
    function getProjectMemberEntryList_d($projectId) {
        $sql = "SELECT memberId,memberName,MIN(beginDate) AS beginDate, MIN(endDate) AS endDate
            FROM oa_esm_project_entry WHERE projectId = $projectId GROUP BY memberId";
        return $this->_db->getArray($sql);
    }
}