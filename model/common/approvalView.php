<?php
/**
 * ��������鿴
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
     * ��ȡ�������
     * @param $pid
     * @param $itemtype
     * @param null $isChange
     * @param null $isPrint
     * @return array
     */
    function getApproval_d($pid, $itemtype, $isChange = null, $isPrint = null)
    {
        $Csql = null;
        if ($isChange == "1") { //�����������
            $Idsql = "select max(id) as id from $itemtype where originalId = $pid AND isTemp = 1 AND ExaStatus IN('��������','���')";
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
     * ��ȡ�����Ľ��
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
                            $result = "ͬ��";
                        } else if ($iVal['Result'] == "no") {
                            $result = "��ͬ��";
                        } else {
                            $result = "δ����";
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
							<td nowrap="nowrap">δ����</td>
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
     * ��ȡ�����Ľ��,�ɱ༭�������
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
                            $result = "ͬ��";
                        } else if ($iVal['Result'] == "no") {
                            $result = "��ͬ��";
                        } else {
                            $result = "δ����";
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
							<td nowrap="nowrap">δ����</td>
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
     * ��ȡ���һ�α�������Ľ��
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
                            $result = "ͬ��";
                        } else if ($iVal[Result] == "no") {
                            $result = "��ͬ��";
                        } else {
                            $result = "δ����";
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
								<td>δ����</td>
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
     * ��ȡ�������
     */
    function getApprovalAll_d($pid, $itemtype)
    {
        $Csql = null;
        $Crows = array();
        //�����������
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

    //�����л�������
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
     * ������
     */
    function getResultAll_d($rows, $taskArr)
    {
        $otherDataDao = new model_common_otherdatas();
        $str = "";
        $rows = $this->handleRows($rows, $taskArr);
        if (is_array($rows)) {
            $i = "1";
            $changeNoAuditCount = 1;// �������ͳ��
            foreach ($rows as $k => $v) {
                $k = "0";
                if (!empty($v)) {
                    //��ȡ�л�������
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
                                    $result = "ͬ��";
                                } else if ($iVal[Result] == "no") {
                                    $result = "��ͬ��";
                                } else {
                                    $result = "δ����";
                                }
                                if ($k == 1) {
                                    if ($val['name'] == '��ͬ�������') {
                                        $itemStrA = '<td ' . $TRclass . '></b>��' . $changeNoAuditCount . '��' . $val['name'] . '</td>';
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
										<td>δ����</td>
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
                    //��ȡ�л�������
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
                                    $result = "ͬ��";
                                } else if ($iVal[Result] == "no") {
                                    $result = "��ͬ��";
                                } else {
                                    $result = "δ����";
                                }
                                if ($k == 1) {
                                    $itemStrA = '<td ' . $TRclass . '>�� <b>' . $i . '</b> �α������</td>';
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
										<td>δ����</td>
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

    //��ȡ�ж��ٸ�����
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

    //��������¼����
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

    /*����ӿڻ�ȡ����������¼*/
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

    /*������Ŀ��ȡ������¼*/
    function getTrialprojectApproval($pid, $code = "oa_trialproject_trialproject")
    {
        $sql = "select w.task,w.name,w.code,w.pid from wf_task w where w.pid IN ({$pid}) and w.code = '{$code}'GROUP BY w.task;";
        $arr = $this->_db->getArray($sql);
        return $arr;
    }

    /*��ȡ��Ŀ���������¼����Ŀid*/
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

    /*������Ŀ��ȡ������¼HTML��ʽ��*/
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
                $main_taskName = ($i > 1) ? "��{$i}��{$v['name']}" : "��1��" . $v['name'];
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
                            $result = 'ͬ��';
                            break;
                        case 'no':
                            $result = '��ͬ��';
                            break;
                        default:
                            $result = 'δ����';
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
            $str = "<tr><td colspan = '6'>��������¼</td></tr>";
        }
        return $str;
    }

    /**
     * ��ȡ����һ�ε�����ʵ��id
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
     * �ж�������ʵ���Ƿ�����Ѱ�����ڵ�
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