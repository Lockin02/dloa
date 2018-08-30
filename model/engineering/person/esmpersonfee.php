<?php

/**
 * @author show
 * @Date 2013年10月29日 17:16:28
 * @version 1.0
 * @description:项目人力决算 Model层
 */
class model_engineering_person_esmpersonfee extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_personfee";
        $this->sql_map = "engineering/person/esmpersonfeeSql.php";
        parent:: __construct();
    }

    /**
     * 同步人员工作量
     * 业务逻辑描述：
     * 1、获取当前已有的数据
     * 2、对比数据差异
     * 3、处理差异数据（变动的删除后新增，缺少的新增，删除的删除）
     * @param $thisYear
     * @param $thisMonth
     * @param $salary
     * @param $logData
     * @return array
     * @throws Exception
     */
    function synLogInfo_d($thisYear, $thisMonth, $salary, $logData)
    {
        $insertData = $realInsertData = $projectIdArr = array(); // 插入的数据

        // 如果有数据要处理，则进去处理逻辑
        if ($logData) {
            $userId = $_SESSION['USER_ID'];
            $userName = $_SESSION['USER_ID'];
            $now = date("Y-m-d H:i:s");

            // 需要删除的数据
            $delIdArr = array();

            // 获取当期已有数据
            $thisPeriodData = $this->getThisPeriodData_d($thisYear, $thisMonth);
            try {

                //数据同步
                foreach ($logData as $k => $v) {
                    $v['userId'] = $v['createId'];
                    $v['userName'] = $v['createName'];
                    $v['feePerson'] = isset($salary[$v['userId']]) ?
                        bcmul($v['inWorkRate'], $salary[$v['userId']]['price'], 2) : 0;
                    $v['updateId'] = $v['createId'] = $userId;
                    $v['updateName'] = $v['createName'] = $userName;
                    $v['updateTime'] = $v['createTime'] = $now;

                    // 如果当前
                    $index = $v['projectId'] . "_" . $v['userId'];
                    if (!isset($thisPeriodData[$index]) ||
                        (
                            isset($thisPeriodData[$index]) && ($thisPeriodData[$index]['feePerson'] != $v['feePerson']
                            || $thisPeriodData[$index]['inWorkRate'] != $v['inWorkRate'])
                        )
                    ) {
                        // 放入新增数据
                        $realInsertData[] = $v;

                        // 插入需要处理的项目id
                        if (!in_array($v['projectId'], $projectIdArr)) {
                            $projectIdArr[] = $v['projectId'];
                        }

                        // 判定是否需要删除
                        if (isset($thisPeriodData[$index])) {
                            $delIdArr[] = $thisPeriodData[$index]['id'];
                        }
                        // 删除数据
                        unset($thisPeriodData[$index]);

                    } else if(isset($thisPeriodData[$index])) { // 如果已存在，并且无变化，说明这部分决算不需要变动
                        // 删除数据
                        unset($thisPeriodData[$index]);
                    }
                }

                // 如果非空，说明这些数据已经被删除，需要处理
                if (!empty($thisPeriodData)) {
                    foreach ($thisPeriodData as $v) {
                        $delIdArr[] = $v['id'];

                        // 插入需要处理的项目id
                        if (!in_array($v['projectId'], $projectIdArr)) {
                            $projectIdArr[] = $v['projectId'];
                        }
                    }
                }

                if (!empty($realInsertData)) {
                    if (!empty($delIdArr)) {
                        // 先删除原来的数据
                        $this->_db->query("DELETE FROM $this->tbl_name WHERE id IN (" . implode(',', $delIdArr) . ")");
                    }

                    // 插入新的数据
                    $dataKeyLength = count($realInsertData) - 1; // key 长度

                    foreach ($realInsertData as $k => $v) {
                        $insertData[] = $v;
                        // 100条获取满足数组长度时，插入数据
                        if (($k % 100 == 0 && $k != 0) || $dataKeyLength == $k) {
                            // 批量保存
                            $this->createBatch($insertData);
                            $insertData = array();
                        }
                    }
                }
            } catch (Exception $e) {
                throw $e;
            }
        }

        // 日志写入
        $logDao = new model_engineering_baseinfo_esmlog();
        $logDao->addLog_d(-1, '更新人力决算', count($realInsertData) . '|' . $thisMonth);

        return $projectIdArr;
    }

    /**
     * 获取当前已有的人力数据
     * @param $thisYear
     * @param $thisMonth
     * @return array
     */
    function getThisPeriodData_d($thisYear, $thisMonth)
    {
        // 查询本月的决算数据
        $data = $this->findAll(array('thisYear' => $thisYear, 'thisMonth' => $thisMonth));
        $rs = array();

        foreach ($data as $v) {
            $rs[$v['projectId'] . "_" . $v['userId']] = $v;
        }
        return $rs;
    }
}