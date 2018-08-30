<?php
/**
 * 审批意见查看
 * Created on 2011-7-20
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
header("Content-type: text/html; charset=gb2312");

class model_common_approvalView extends model_base
{
    function __construct()
    {
        //$this->sql_map = "";
        parent::__construct();
    }

    /**
     * 获取审批意见
     * @param $pid
     * @param $itemtype
     * @param null $isChange
     * @param null $isPrint
     * @return array
     */
    function getApproval_d($pid, $itemtype, $isChange = null, $isPrint = null)
    {
        $Csql = null;
        if ($isChange == "1") { //带出审批意见
            $Idsql = "select max(id) as id from $itemtype where originalId = $pid AND isTemp = 1 AND ExaStatus IN('部门审批','完成')";
            $Id = $this->_db->getArray($Idsql);
            foreach ($Id as $k => $v) {
                $cId = $v['id'];
            }
            $sql = "select f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task,w.name from wf_task w left join flow_step_view f  on (w.task = f.wf_task_ID)  where w.Pid ='$pid' and w.code='$itemtype' ";
            $Csql = "select f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task,w.name from wf_task w left join flow_step_view f  on (w.task = f.wf_task_ID)  where w.Pid ='$cId' and w.code='$itemtype' ";
        } else {
            if ($itemtype == "loan") {
                $sql = "select f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task,w.name from wf_task w left join flow_step_view f  on (w.task = f.wf_task_ID)  where w.code ='$pid' ";
            } else {
                $sql = "select f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task,w.name from wf_task w left join flow_step_view f  on (w.task = f.wf_task_ID)  where w.Pid ='$pid' and w.code='$itemtype' ";
            }


            if (!empty($isPrint)) {
                $sql .= " and w.examines='ok' ";
            }
        }
        $this->sort = "f.SmallID";
        $this->asc = false;
        $rows = $this->listBySql($sql);
        if ($Csql) {
            $this->sort = "f.SmallID";
            $this->asc = false;
            $Crows = $this->listBySql($Csql);
            return array("app" => $rows, "change" => $Crows);
        } else {
            $Crows = array();
            return array("app" => $rows, "change" => $Crows);
        }
    }

    /**
     * 获取审批的结果
     * @param $rows
     * @return string
     */
    function getResult_d($rows)
    {
        $otherDataDao = new model_common_otherdatas();
        $str = "";
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                $sql = "select f.User, f.Content, f.Result, f.Endtime ,f.ID ,f.Flag from flow_step_partent f where f.Wf_task_ID='" . $val['task'] . "' and f.SmallID='" . $val['SmallID'] . "' ";
                $row = $this->listBySql($sql);
                $length = count($row);
                if ($row) {
                    $class = "rowspan='" . $length . "'";
                    $j = 0;
                    foreach ($row as $iKey => $iVal) {
                        $j++;
                        $exaUser = trim($otherDataDao->getUsernameList($iVal['User']), ",");
                        if ($iVal['Result'] == "ok") {
                            $result = "同意";
                        } else if ($iVal['Result'] == "no") {
                            $result = "不同意";
                        } else {
                            $result = "未审批";
                        }
                        if ($j == 1) {
                            $itemStr = '<td ' . $class . '>' . $val['Item'] . '</td>';
                        } else {
                            $itemStr = '';
                        }
                        $str .= <<<EOT
							<tr >
								$itemStr
								<td nowrap="nowrap">$exaUser</td>
								<td nowrap="nowrap">$iVal[Endtime]</td>
								<td nowrap="nowrap">$result</td>
								<td nowrap="nowrap">$iVal[Content]</td>
							</tr>
EOT;
                    }
                } else {
                    $exaUser = trim($otherDataDao->getUsernameList($val['User']), ",");
                    $str .= <<<EOT
						<tr >
							<td nowrap="nowrap">$val[Item]</td>
							<td nowrap="nowrap">$exaUser</td>
							<td nowrap="nowrap"></td>
							<td nowrap="nowrap">未审批</td>
							<td nowrap="nowrap"></td>
						</tr>
EOT;
                }
            }
        } else {
            $str .= "0";
        }
        return $str;
    }

    /**
     * 获取审批的结果,可编辑审批意见
     * @param $rows
     * @return string
     */
    function getResultNew_d($rows)
    {
        $otherDataDao = new model_common_otherdatas();
        $str = "";
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                $sql = "select f.User, f.Content, f.Result, f.Endtime ,f.ID ,f.Flag from flow_step_partent f where f.Wf_task_ID='" . $val['task'] . "' and f.SmallID='" . $val['SmallID'] . "' ";
                $row = $this->listBySql($sql);
                $length = count($row);
                if ($row) {
                    $class = "rowspan='" . $length . "'";
                    $j = 0;
                    foreach ($row as $iKey => $iVal) {
                        $j++;
                        $exaUser = trim($otherDataDao->getUsernameList($iVal['User']), ",");
                        if ($iVal['Result'] == "ok") {
                            $result = "同意";
                        } else if ($iVal['Result'] == "no") {
                            $result = "不同意";
                        } else {
                            $result = "未审批";
                        }
                        if ($j == 1) {
                            $itemStr = '<td ' . $class . '>' . $val['Item'] . '</td>';
                        } else {
                            $itemStr = '';
                        }
                        $str .= <<<EOT
							<tr >
								$itemStr
								<td nowrap="nowrap">$exaUser</td>
								<td nowrap="nowrap">$iVal[Endtime]</td>
								<td nowrap="nowrap">$result</td>
								<td nowrap="nowrap">
									<input type="text" class="txtlong" value="{$iVal[Content]}" style="border:none;" onblur="this.setAttribute('value', this.value);">
								</td>
							</tr>
EOT;
                    }
                } else {
                    $exaUser = trim($otherDataDao->getUsernameList($val['User']), ",");
                    $str .= <<<EOT
						<tr >
							<td nowrap="nowrap">$val[Item]</td>
							<td nowrap="nowrap">$exaUser</td>
							<td nowrap="nowrap"></td>
							<td nowrap="nowrap">未审批</td>
							<td nowrap="nowrap">
								<input type="text" class="txtlong" style="border:none;" onblur="this.setAttribute('value', this.value);">
							</td>
						</tr>
EOT;
                }
            }
        } else {
            $str .= "0";
        }
        return $str;
    }

    /**
     * 获取最近一次变更审批的结果
     * @param $rows
     * @return string
     */
    function changeGetResult_d($rows)
    {
        $otherDataDao = new model_common_otherdatas();
        $str = "";
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                $sql = "select f.User  , f.Content, f.Result, f.Endtime ,f.ID ,f.Flag  from flow_step_partent f where f.Wf_task_ID='" . $val[task] . "' and f.SmallID='" . $val[SmallID] . "' ";
                $row = $this->listBySql($sql);
                $length = count($row);
                if ($row) {
                    $class = "rowspan='" . $length . "'";
                    $j = 0;
                    foreach ($row as $iKey => $iVal) {
                        $j++;
                        $exaUser = trim($otherDataDao->getUsernameList($iVal['User']), ",");
                        if ($iVal['Result'] == "ok") {
                            $result = "同意";
                        } else if ($iVal[Result] == "no") {
                            $result = "不同意";
                        } else {
                            $result = "未审批";
                        }
                        if ($j == 1) {
                            $itemStr = '<td ' . $class . '>' . $val[Item] . '</td>';
                        } else {
                            $itemStr = '';
                        }
                        $str .= <<<EOT
							<tr >
								$itemStr
								<td>$exaUser</td>
								<td>$iVal[Endtime]</td>
								<td>$result</td>
								<td>$iVal[Content]</td>
							</tr>
EOT;
                    }
                } else {
                    $exaUser = trim($otherDataDao->getUsernameList($val['User']), ",");
                    $str .= <<<EOT
							<tr >
								<td >$val[Item]</td>
								<td>$exaUser</td>
								<td></td>
								<td>未审批</td>
								<td></td>
							</tr>
EOT;
                }
            }
        } else {
            $str .= "0";
        }
        return $str;
    }


    /**
     * 获取审批意见
     */
    function getApprovalAll_d($pid, $itemtype)
    {
        $Csql = null;
        $Crows = array();
        //带出审批意见
        $Idsql = "select id from $itemtype where originalId = $pid ";
        $IdsArr = $this->_db->getArray($Idsql);
        foreach ($IdsArr as $k => $v) {
            $cId = $v['id'];
            if ((!empty($_SESSION["COM_BRN_SQL"]) && $_SESSION["COM_BRN_SQL"] != 'dloa') || (!empty($_POST["gdbtable"]) && $_POST["gdbtable"] != 'dloa' && $_POST["gdbtable"] != 'null')) {
                if (!empty($_SESSION["COM_BRN_SQL"]) && $_SESSION["COM_BRN_SQL"] != 'dloa') {
                    $gdbtable = $_SESSION["COM_BRN_SQL"];
                } else {
                    $gdbtable = $_POST["gdbtable"];
                }
                if ($itemtype == "loan") {
                    $Csql = "select  f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task from wf_task w left join flow_step f  on (w.task = f.wf_task_ID)  where w.code ='$cId'  and DBTable='" . $gdbtable . "'  ";
                } else {
                    $Csql = "select  f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task from wf_task w left join flow_step f  on (w.task = f.wf_task_ID)  where w.Pid ='$cId' and w.code='$itemtype' and DBTable='" . $gdbtable . "'  ";
                }

            } else {
                if ($itemtype == "loan") {
                    $Csql = "select  f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task from wf_task w left join flow_step f  on (w.task = f.wf_task_ID)  where w.code ='$cId' and ( DBTable ='' or DBTable is null ) ";
                } else {
                    $Csql = "select  f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task from wf_task w left join flow_step f  on (w.task = f.wf_task_ID)  where w.Pid ='$cId' and w.code='$itemtype' and ( DBTable ='' or DBTable is null ) ";
                }

            }
            $this->sort = "f.SmallID";
            $this->asc = false;
            $Crows[] = $this->listBySql($Csql);
        }
        return $Crows;
    }

    //处理有机会数据
    function trlength($arr)
    {
        $num = 0;
        foreach ($arr as $key => $val) {
            $tempArr = explode(",", $val['User']);
            array_pop($tempArr);

            $sql = "select f.User  , f.Content, f.Result, f.Endtime ,f.ID ,f.Flag  from flow_step_partent f where f.Wf_task_ID='" . $val['task'] . "' and f.SmallID='" . $val['SmallID'] . "' ";
            $row = $this->listBySql($sql);
            if (empty($row)) {
                $tempNum = 1;
            } else {
                $n = '0';
                foreach ($row as $k => $v) {
                    $n++;
                }
                $tempNum = $n;
            }
            $num += $tempNum;
        }
        return $num;
    }

    /**
     * 处理结果
     */
    function getResultAll_d($rows, $taskArr)
    {
        $otherDataDao = new model_common_otherdatas();
        $str = "";
        $rows = $this->handleRows($rows, $taskArr);
        if (is_array($rows)) {
            $i = "1";
            $changeNoAuditCount = 1;// 变更免审统计
            foreach ($rows as $k => $v) {
                $k = "0";
                if (!empty($v)) {
                    //获取有机会数据
                    $TRlength = $this->trlength($v);
                    $TRclass = "rowspan='" . $TRlength . "'";
                    foreach ($v as $key => $val) {
                        $sql = "select f.User  , f.Content, f.Result, f.Endtime ,f.ID ,f.Flag  from flow_step_partent f where f.Wf_task_ID='" . $val['task'] . "' and f.SmallID='" . $val['SmallID'] . "' ";
                        $row = $this->listBySql($sql);
                        $length = count($row);
                        if ($row) {
                            $class = "rowspan='" . $length . "'";
                            $j = 0;
                            foreach ($row as $iKey => $iVal) {
                                $k++;
                                $j++;
                                $exaUser = trim($otherDataDao->getUsernameList($iVal['User']), ",");
                                if ($iVal['Result'] == "ok") {
                                    $result = "同意";
                                } else if ($iVal[Result] == "no") {
                                    $result = "不同意";
                                } else {
                                    $result = "未审批";
                                }
                                if ($k == 1) {
                                    if ($val['name'] == '合同变更免审') {
                                        $itemStrA = '<td ' . $TRclass . '></b>第' . $changeNoAuditCount . '次' . $val['name'] . '</td>';
                                        $changeNoAuditCount += 1;
                                    } else {
                                        $itemStrA = '<td ' . $TRclass . '></b>' . $val['name'] . '</td>';
                                    }
                                } else {
                                    $itemStrA = '';
                                }
                                if ($j == 1) {
                                    $itemStr = '<td ' . $class . '>' . $val['Item'] . '</td>';
                                } else {
                                    $itemStr = '';
                                }
                                $str .= <<<EOT
									<tr >
									    $itemStrA
										$itemStr
										<td>$exaUser</td>
										<td>$iVal[Endtime]</td>
										<td>$result</td>
										<td>$iVal[Content]</td>
									</tr>
EOT;
                            }
                        } else {
                            $exaUser = trim($otherDataDao->getUsernameList($val['User']), ",");
                            $str .= <<<EOT
									<tr >
										<td >$val[Item]</td>
										<td>$exaUser</td>
										<td></td>
										<td>未审批</td>
										<td></td>
									</tr>
EOT;
                        }
                    }
                    $i++;
                }
            }
        } else {
            $str .= "0";
        }
        return $str;
    }

    function changeGetResultAll_d($rows)
    {
        $otherDataDao = new model_common_otherdatas();
        $str = "";
        if (is_array($rows)) {
            $i = "1";
            foreach ($rows as $k => $v) {
                $k = "0";
                if (!empty($v)) {
                    //获取有机会数据
                    $TRlength = $this->trlength($v);
                    $TRclass = "rowspan='" . $TRlength . "'";
                    foreach ($v as $key => $val) {
                        $sql = "select f.User  , f.Content, f.Result, f.Endtime ,f.ID ,f.Flag  from flow_step_partent f where f.Wf_task_ID='" . $val['task'] . "' and f.SmallID='" . $val['SmallID'] . "' ";
                        $row = $this->listBySql($sql);
                        $length = count($row);
                        if ($row) {
                            $class = "rowspan='" . $length . "'";
                            $j = 0;
                            foreach ($row as $iKey => $iVal) {
                                $k++;
                                $j++;
                                $exaUser = trim($otherDataDao->getUsernameList($iVal['User']), ",");
                                if ($iVal['Result'] == "ok") {
                                    $result = "同意";
                                } else if ($iVal[Result] == "no") {
                                    $result = "不同意";
                                } else {
                                    $result = "未审批";
                                }
                                if ($k == 1) {
                                    $itemStrA = '<td ' . $TRclass . '>第 <b>' . $i . '</b> 次变更审批</td>';
                                } else {
                                    $itemStrA = '';
                                }
                                if ($j == 1) {

                                    $itemStr = '<td ' . $class . '>' . $val[Item] . '</td>';
                                } else {
                                    $itemStr = '';
                                }
                                $str .= <<<EOT
									<tr >
									    $itemStrA
										$itemStr
										<td>$exaUser</td>
										<td>$iVal[Endtime]</td>
										<td>$result</td>
										<td>$iVal[Content]</td>
									</tr>
EOT;
                            }
                        } else {
                            $exaUser = trim($otherDataDao->getUsernameList($val['User']), ",");
                            $str .= <<<EOT
									<tr >
										<td >$val[Item]</td>
										<td>$exaUser</td>
										<td></td>
										<td>未审批</td>
										<td></td>
									</tr>
EOT;
                        }
                    }
                    $i++;
                }
            }
        } else {
            $str .= "0";
        }
        return $str;
    }

    //获取有多少个审批
    function findTaskArr($pid, $itemtype)
    {
        if ($itemtype == "loan") {
            $sql = "select task from wf_task where code='" . $pid . "' ";
        } else {
            $sql = "select task from wf_task where Pid='" . $pid . "' and code = '" . $itemtype . "'";
        }

        $arr = $this->_db->getArray($sql);
        return $arr;
    }

    //处理变更记录数组
    function handleRows($rows, $taskArr)
    {
        $arr = array();
        foreach ($taskArr as $k => $v) {
            $tempFlag = $v['task'];
            foreach ($rows as $key => $val) {
                if ($val['task'] == $tempFlag) {
                    $tempArr[] = $val;
                }
            }
            array_push($arr, $tempArr);
            $tempArr = "";
        }

        return $arr;
    }

    /*请求接口获取需求审批记录*/
    function getXqApprovalJson($relDocId)
    {
        $result = util_curlUtil::getDataFromAWS('asset', 'getApplyProveRecords', array(
            "id" => $relDocId
        ));

        $back_data = util_jsonUtil::decode($result ['data'], true);

        $data = array();
        $data["relDocId"] = $relDocId;

        foreach ($back_data['data']['recordsList'] as $k => $v) {
            $data['reCord'][$k]["id"] = $v['ID'];
            $data['reCord'][$k]["step"] = $v['ACTIVITYNAME'];
            $data['reCord'][$k]["allowMan"] = $v['USERNAME'];
            $data['reCord'][$k]["allowTime"] = $v['CREATEDATE'];
            $data['reCord'][$k]["allowResult"] = $v['ACTIONNAME'];
            $data['reCord'][$k]["allowMsg"] = $v['MSG'];
        }

        return $data;
    }

    /*试用项目获取审批记录*/
    function getTrialprojectApproval($pid, $code = "oa_trialproject_trialproject")
    {
        $sql = "select w.task,w.name,w.code,w.pid from wf_task w where w.pid IN ({$pid}) and w.code = '{$code}'GROUP BY w.task;";
        $arr = $this->_db->getArray($sql);
        return $arr;
    }

    /*获取项目延期申请记录的项目id*/
    function getIdOfExtensionByTrialprojectId($trialprojectId)
    {
        $sql = "select id from oa_trialproject_extension where trialprojectId = '{$trialprojectId}'";
        $arr = $this->_db->getArray($sql);
        $ids = "";
        foreach ($arr as $k => $v) {
            $ids .= "'" . $v['id'] . "',";
        }
        return rtrim($ids, ',');
    }

    /*试用项目获取审批记录HTML格式化*/
    function getTpApprovalResult($pid)
    {
        $ids = $this->getIdOfExtensionByTrialprojectId($pid);
        $arr1 = $this->getTrialprojectApproval($pid);
        $arr2 = ($ids == '') ? array() : $this->getTrialprojectApproval($ids, 'oa_trialproject_extension');
        $rows = empty($arr2) ? $arr1 : array_merge($arr1, $arr2);
        $str = "";
        if (is_array($rows)) {
            $i = 1;
            $task_name = '';
            foreach ($rows as $k => $v) {
                if ($v['name'] == $task_name) {
                    $i += 1;
                } else {
                    $i = 1;
                    $task_name = $v['name'];
                }
                $main_taskName = ($i > 1) ? "第{$i}次{$v['name']}" : "第1次" . $v['name'];
                $sql = <<<EOT
                    SELECT
                        f.*, fs.Item,
                        u.USER_NAME
                    FROM
                    flow_step fs
                    LEFT JOIN flow_step_partent f ON (fs.Wf_task_ID = f.wf_task_ID AND f.StepID = fs.ID)
                    LEFT JOIN USER u ON (
                        FIND_IN_SET(u.USER_ID, f. USER)
                    )
                    WHERE fs.Wf_task_ID = $v[task]
                    ORDER BY fs.SmallID;
EOT;
                $arr = $this->_db->getArray($sql);
                $rowspan_num = count($arr);
//                echo "<pre>";print_r($arr);
                foreach ($arr as $ak => $av) {
                    switch ($av['Result']) {
                        case 'ok':
                            $result = '同意';
                            break;
                        case 'no':
                            $result = '不同意';
                            break;
                        default:
                            $result = '未审批';
                            break;
                    }
                    $mainStr = ($ak == 0) ? "<tr><td rowspan = '{$rowspan_num}'>$main_taskName</td>" : "<tr>";
                    $str .= <<<EOT
                        $mainStr
                            <td >$av[Item]</td>
                            <td>$av[USER_NAME]</td>
                            <td>$av[Endtime]</td>
                            <td>$result</td>
                            <td>$av[Content]</td>
                        </tr>
EOT;
                }
            }
        } else {
            $str = "<tr><td colspan = '6'>无审批记录</td></tr>";
        }
        return $str;
    }

    /**
     * 获取最新一次的任务实例id
     * @param $pid
     * @param $code
     * @return array
     */
    function getLastTask($pid, $code) {
        $sql = "SELECT task FROM wf_task WHERE pid = $pid AND code = '$code' AND Status = 'ok' LIMIT 1";
        $lastTask = $this->_db->get_one($sql);

        if (isset($lastTask['task'])) {
            return $lastTask['task'];
        } else {
            return 0;
        }
    }

    /**
     * 判定审批流实例是否存在已办任务节点
     * @param $task
     * @return bool
     */
    function hasDoneStep($task) {
        $sql = "SELECT ID FROM flow_step_partent WHERE Wf_task_ID = $task AND Flag = 1 LIMIT 1";
        $oneDoneStep = $this->_db->get_one($sql);

        if (isset($oneDoneStep['ID'])) {
            return true;
        } else {
            return false;
        }
    }
}