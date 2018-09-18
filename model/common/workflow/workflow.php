<?php

/**
 * Created on 2011-8-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_common_workflow_workflow extends model_base
{
    private $_logicOpts = array();

    function __construct()
    {
        include(WEB_TOR . "model/common/workflow/workflowRegister.php");
        include(WEB_TOR . "model/common/workflow/workflowExaInfoConfig.php");

        //工作流对应数组及其加密信息
        $this->urlArr = isset($urlArr) ? $urlArr : null;
        //批量审批数组
        $this->batchAuditArr = isset($batchAuditArr) ? $batchAuditArr : null;

        //审批历史加载数组
        $this->ExaInfoUserArr = isset($ExaInfoUserArr) ? $ExaInfoUserArr : null;

        //工作流对应变更数组
        $this->changeFunArr = isset($changeFunArr) ? $changeFunArr : null;

        //收单操作对应数组
        $this->receiveActionArr = isset($receiveActionArr) ? $receiveActionArr : null;
        //退单操作对应数组
        $this->backActionArr = isset($backActionArr) ? $backActionArr : null;

        $this->tbl_name = "wf_task";
        $this->sql_map = "common/workflow/workflowSql.php";

        // 审批流办理人逻辑编码对应数组
        $this->_logicOpts = array(
            array("code"=>"@bmfz","name"=>"部门副总/总经理"),
            array("code"=>"@bmfzj","name"=>"部门副总监"),
            array("code"=>"@qyjl","name"=>"区域经理"),
            array("code"=>"@xmjl","name"=>"项目经理"),
            array("code"=>"@wgcqy","name"=>"工程办事处经理")
        );

        parent:: __construct();
    }

    //正常数组
    public $urlArr;

    //批量审批数组
    public $batchAuditArr;

    //变更数组
    public $changeFunArr;

    //收单操作对应数组
    public $receiveActionArr;
    //退单操作对应数组
    public $backActionArr;

    /**
     * 返回注册工作流串
     */
    function rtWorkflowStr_d()
    {
        return implode(array_keys($this->urlArr), ',');
    }

    /**
     * 返回注册工作流数组
     */
    function getBatchAudit_d()
    {
        return array_keys($this->batchAuditArr);
    }

    /**
     * 返回刻批量审批流数组
     */
    function rtWorkflowArr_d()
    {
        return array_keys($this->urlArr);
    }

    /**
     * 返回审批历史查看人配置数组
     */
    function rtWorkflowExInfoArr_d()
    {
        return array_keys($this->ExaInfoUserArr);
    }

    /**
     * 处理变更部分数据
     * @param $object
     * @return mixed
     */
    function rowsDeal_d($object)
    {
        if (!empty($object) && !empty($this->changeFunArr)) {
            //存在变更的表数组
            $changeKeyArray = array_keys($this->changeFunArr);

            foreach ($object as $key => $val) {
                //判断当前列是否在变更数组中
                if (in_array($val['name'], $changeKeyArray)) {

                    //验证是否是合同变更
                    $objClassStr = $this->changeFunArr[$val['name']]['className'];
                    $objFunStr = $this->changeFunArr[$val['name']]['funName'];
                    //数组缓存实例化对象
                    $obj[$val['name']] = isset($obj[$val['name']]) ? $obj[$val['name']] : new $objClassStr();
                    if ($obj[$val['name']]->$objFunStr($val['Pid'])) {
                        $object[$key]['name'] = $this->changeFunArr[$val['name']]['taskName'];
                        $object[$key]['isTemp'] = 1;
                    } else if ($obj[$val['name']]->$objFunStr($val['Pid'])) {
                        $object[$key]['isTemp'] = 0;
                    }
                } else {
                    $object[$key]['isTemp'] = 0;
                }
            }
        }
        return $object;
    }

    /**
     * 根据临时id获取正式id，用于处理审批完成后的变更查看
     */
    function getObjIdByTempId_d($tempId, $code)
    {
        $changeDao = new model_common_changeLog($code);
        $rows = $changeDao->getObjByTempId($tempId);
        return $rows[0]['objId'];
    }

    /**
     * 根据工作流spid获取工作流信息
     */
    function getWfInfo_d($spid)
    {
        $otherDao = new model_common_otherdatas();
        return $otherDao->getWorkflowInfo($spid);
    }

    /**
     * 获取个人默认设置
     */
    function getPersonSelectedSetting_d($gridId = 'auditing')
    {
        $selectsettingDao = new model_common_workflow_selectedsetting();
        return $selectsettingDao->rtUserSelected_d($gridId);
    }

    /**
     * 判断是否存在特殊路径
     */
    function getSpeUrl_d($formName)
    {
        //特殊路径配置
        include(WEB_TOR . "model/common/workflow/workflowSpeConfig.php");
        //工作流对应变更数组
        $speSetArr = isset($speSetArr) ? $speSetArr : null;
        if ($speSetArr) {
            //对象数组
            $keyArr = array_keys($speSetArr[$formName]);
            if (in_array($_SESSION['USER_ID'], $keyArr)) {
                return $speSetArr[$formName][$_SESSION['USER_ID']];
            }
        }
        return '';
    }

    /**
     * 判断审批流是否存在审批信息
     */
    function isAudited_d($billId, $examCode)
    {
        //验证业务是否还处于审批状态
        $sql = "select ExaStatus from $examCode where id = '$billId'";
        $objArr = $this->_db->getArray($sql);
        if ($objArr[0]['ExaStatus'] != AUDITING) {
            return 1;
        }

        //获取主审批信息
        $sql = "select task from wf_task where code='$examCode' and Pid='$billId' and Status='ok' ";
        $taskArr = $this->_db->getArray($sql);
        $taskObj = array_pop($taskArr);

        //获取已审批信息
        $sql = "SELECT ID FROM flow_step where wf_task_id='" . $taskObj['task'] . "' and ( flag='ok' or status<>'ok' or flag='') ";
        $auditArr = $this->_db->getArray($sql);
        if ($auditArr[0]['ID']) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 判断审批流是否存在审批信息(合同用)
     */
    function isAuditedContract_d($billId, $examCode)
    {
        //验证业务是否还处于审批状态
        $sql = "select ExaStatus from $examCode where id = '$billId'";
        $objArr = $this->_db->getArray($sql);
        if ($objArr[0]['ExaStatus'] != AUDITING) {
            return 1;
        }

        //获取主审批信息
        $sql = "select task from wf_task where code='$examCode' and Pid='$billId' and Status='ok' ";
        $taskArr = $this->_db->getArray($sql);
        $taskObj = array_pop($taskArr);

        //获取已审批信息
        $sql = "SELECT ID FROM flow_step where wf_task_id='" . $taskObj['task'] . "' and ( flag='ok' or status<>'ok' or flag='') ";
        $auditArr = $this->_db->getArray($sql);

        $sql = "select name from wf_task where code='$examCode' and Pid='$billId' and Status='ok' ";
        $taskNameArr = $this->_db->getArray($sql);
        if ($auditArr[0]['ID']) {
            return 1;
        } else {
            return $taskNameArr[0]['name'];
        }
    }

    /********************* 收单退单系列 ********************/
    /**
     * 收单操作
     */
    function receiveForm_d($taskId, $formName, $objId)
    {
        if (isset($this->receiveActionArr[$formName])) {
            $modelInfo = $this->receiveActionArr[$formName];
            //			print_r($modelInfo);

            $rs = 1;
            try {
                $this->start_d();

                //更新当前的收单状态
                $this->update(
                    array('task' => $taskId),
                    array('receiveStatus' => 1, 'recevierName' => $_SESSION['USERNAME'], 'recevierId' => $_SESSION['USER_ID'], 'recevieTime' => date('Y-m-d H:i:s'))
                );

                //更新业务收单状态
                //查询业务信息
                $newClass = $modelInfo['className'];
                $newAction = $modelInfo['funName'];
                //退单操作
                $newClassDao = new $newClass();
                $newClassDao->$newAction($objId);

                $this->commit_d();
            } catch (Exception $e) {
                $this->rollBack();
                $rs = 0;
            }

            //如果收单成功，发送邮件
            if ($rs == 1) {
                //查询对象信息
                $obj = $newClassDao->find(array('id' => $objId));
                $this->receiveMail_d($obj);
            }
            return $rs;
        } else {
            return -1;
        }
    }

    /**
     * 退单邮件
     */
    function receiveMail_d($obj)
    {
        //内容
        $content = "您好：<br/>" . $_SESSION['USERNAME'] . " 已经接收你的报销单号：$obj[BillNo] 的单据！<br/>详情请上oa系统查看，谢谢！";
        //标题
        $title = "OA-财务收单通知:" . $obj['BillNo'];

        $mailDao = new model_common_mail();
        $mailDao->mailClear($title, $obj['InputMan'], $content);
    }

    /**
     * 退单操作
     */
    function backForm_d($taskId, $formName, $objId)
    {
        if (isset($this->backActionArr[$formName])) {
            $modelInfo = $this->backActionArr[$formName];
            //			print_r($modelInfo);

            $rs = 1;
            try {
                $this->start_d();

                //更新当前的收单状态
                $this->update(
                    array('task' => $taskId),
                    array('receiveStatus' => 0, 'recevierName' => '', 'recevierId' => '', 'recevieTime' => date('Y-m-d H:i:s'))
                );

                //更新业务收单状态
                //查询业务信息
                $newClass = $modelInfo['className'];
                $newAction = $modelInfo['funName'];
                //退单操作
                $newClassDao = new $newClass();
                $newClassDao->$newAction($objId);

                $this->commit_d();
            } catch (Exception $e) {
                $this->rollBack();
                $rs = 0;
            }

            //如果收单成功，发送邮件
            if ($rs == 1) {
                //查询对象信息
                $obj = $newClassDao->find(array('id' => $objId));
                $this->backMail_d($obj);
            }
            return $rs;
        } else {
            return -1;
        }
    }

    /**
     * 退单邮件
     */
    function backMail_d($obj)
    {
        //内容
        $content = "您好：<br/>" . $_SESSION['USERNAME'] . " 已经退还你的报销单号：$obj[BillNo] 的单据！<br/>详情请上oa系统查看，谢谢！";
        //标题
        $title = "OA-财务退单通知:" . $obj['BillNo'];

        $mailDao = new model_common_mail();
        $mailDao->mailClear($title, $obj['InputMan'], $content);
    }

    /************************** 审批历史处理 **************************/
    /**
     * 审批情况初始化
     */
    function auditInfo_d($object)
    {
        $rs = $this->rtWorkflowExInfoArr_d();
        if (in_array($_SESSION['USER_ID'], $rs)) {
            foreach ($object as $key => $val) {
                $object[$key]['auditHistory'] = $this->initViewOnlyBack($this->initAuditInfo($val['Pid'], $val['code']), $val['task']);
            }
        } else {
            $object[0]['auditHistory'] = '加载本列数据可能导致列表读取变慢，如需开启，请联系OA管理员';
        }
        return $object;
    }

    /**
     * 判断是否含有打回的记录
     */
    function hasBackInfo($pid, $code)
    {
        $sql = "select task from wf_task where pid = $pid and code = '$code'";
        $rs = $this->_db->getArray($sql);
        return $rs;
    }

    /**
     * 构建审批情况
     */
    function initAuditInfo($pid, $code)
    {
        $sql = "select
					p.task,p.User  , p.Content, p.Result, p.Endtime ,p.ID ,p.Flag,p.Item,p.examines,u.USER_NAME
				from
				(
				select w.task,p.User  , p.Content, p.Result, p.Endtime ,p.ID ,p.Flag,f.Item,w.examines from flow_step f
						RIGHT JOIN wf_task w ON w.task = f.Wf_task_ID
						LEFT JOIN flow_step_partent p on p.StepID = f.ID where
					w.pid = $pid
				AND w. CODE = '$code'
				) p left JOIN user u on p.User = u.USER_ID ";
        $rs = $this->_db->getArray($sql);
        return $rs;
    }

    /**
     * 页面拼装
     */
    function initView($rows, $thisTask)
    {
        //		print_r($rows);
        $str = "";
        if (is_array($rows)) {
            $headStr = "<table  class='form_main_table' style='width:500px'>";
            $headStr .= <<<EOT
                <tr style="color:blue;">
                	<td width="5%">序号</td>
                    <td width="15%">步骤名</td>
                    <td width="10%">审批人</td>
                    <td width="15%">审批日期</td>
                    <td width="9%">审批结果</td>
                    <td width="27%">审批意见</td>
                </tr>
EOT;
            $mark = "";
            $i = 0; //审批次号
            $x = 0; //行序号
            foreach ($rows as $key => $val) {
                if (empty($val['Result'])) continue;

                if ($val['Result'] == "ok")
                    $rsStr = "<font color='green'>同意</font>";
                elseif ($val['Result'] == "no")
                    $rsStr = "<font color='red'>不同意</font>";
                else $rsStr = "未审批";

                if (!empty($val['Endtime'])) {
                    $endDate = date('Y-m-d', strtotime($val['Endtime']));
                } else {
                    $endDate = "";
                }

                if (empty($mark) || $mark != $val['task']) {
                    $mark = $val['task'];
                    $i++;
                    $x = 0;


                    if ($thisTask == $mark) {
                        $thisTaskStr = '(本次审批)';
                    } else {
                        $thisTaskStr = '';
                    }
                    $str .= <<<EOT
						<tr class="form_header"><td colspan="6" style="text-align:left">第 $i 次审批$thisTaskStr</td></tr>
EOT;
                }
                $x++;
                $contentStr = model_common_util::mb_str_split($val ['Content'], 20);
                //				$contentStr = implode ( "<br />", $contentArr );
                $str .= <<<EOT
		                <tr class='extr'>
		                	<td width="5%">$x</td>
		                    <td width="15%">$val[Item]</td>
		                    <td width="10%">$val[USER_NAME]</td>
		                    <td width="15%">$endDate</td>
		                    <td width="9%">$rsStr</td>
		                    <td width="27%">$contentStr</td>
		                </tr>
EOT;
            }
            if (empty($str)) {
                return '';
            } else {
                return $headStr . $str . "</table>";
            }
        }
        return $str;
    }

    /**
     * 页面拼装-只显示打回信息
     */
    function initViewOnlyBack($rows, $thisTask)
    {
        //		print_r($rows);
        $str = "";
        if (is_array($rows)) {
            $headStr = "<table  class='form_main_table' style='width:500px'>";
            $headStr .= <<<EOT
                <tr style="color:blue;">
                	<td width="5%">序号</td>
                    <td width="15%">步骤名</td>
                    <td width="10%">审批人</td>
                    <td width="15%">审批日期</td>
                    <td width="9%">审批结果</td>
                    <td width="27%">审批意见</td>
                </tr>
EOT;
            $mark = "";
            $i = 0; //审批次号
            $x = 0; //行序号
            foreach ($rows as $key => $val) {
                if (empty($val['Result'])) continue;

                if ($val['Result'] == "ok")
                    $rsStr = "<font color='green'>同意</font>";
                elseif ($val['Result'] == "no")
                    $rsStr = "<font color='red'>不同意</font>";
                else $rsStr = "未审批";

                if (!empty($val['Endtime'])) {
                    $endDate = date('Y-m-d', strtotime($val['Endtime']));
                } else {
                    $endDate = "";
                }
                if ($val['Result'] != 'no') continue;

                if (empty($mark) || $mark != $val['task']) {
                    $mark = $val['task'];
                    $i++;
                    $x = 0;

                    if ($thisTask == $mark) {
                        $thisTaskStr = '(本次审批)';
                    } else {
                        $thisTaskStr = '';
                    }
                    $str .= <<<EOT
						<tr class="form_header"><td colspan="6" style="text-align:left">第 $i 次审批$thisTaskStr</td></tr>
EOT;
                }
                $x++;
                $contentStr = model_common_util::mb_str_split($val ['Content'], 20);
                //				$contentStr = implode ( "<br />", $contentArr );
                $str .= <<<EOT
		                <tr class='extr'>
		                	<td width="5%">$x</td>
		                    <td width="15%">$val[Item]</td>
		                    <td width="10%">$val[USER_NAME]</td>
		                    <td width="15%">$endDate</td>
		                    <td width="9%">$rsStr</td>
		                    <td width="27%">$contentStr</td>
		                </tr>
EOT;
            }
            if (empty($str)) {
                return '';
            } else {
                return $headStr . $str . "</table>";
            }
        }
        return $str;
    }

    /**
     * 判断数据是否是变更
     */
    function inChange_d($spid)
    {
        $otherDao = new model_common_otherdatas();
        $rows = $otherDao->getWorkflowInfo($spid);

        //存在变更的表数组
        $changeKeyArray = array_keys($this->changeFunArr);

        if (in_array($rows['formName'], $changeKeyArray)) {
            //验证是否是合同变更
            $objClassStr = $this->changeFunArr[$rows['formName']]['className'];
            $objFunStr = $this->changeFunArr[$rows['formName']]['funName'];
            $obj = new $objClassStr();
            if ($obj->$objFunStr($rows['objId'])) {
                return $this->changeFunArr[$rows['formName']]['rtUrl'] . $spid;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    /******************* 批量审批部分 **********************/
    /**
     * 获取审批信息
     */
    function getAuditInfo_d($spids)
    {
        $sql = "select p.ID , p.Wf_task_ID , t.Pid , t.code, t.name , t.Creator , t.objCode ,t.infomation from flow_step_partent p , flow_step s , wf_task t where t.task=p.Wf_task_ID and p.ID in ($spids) and p.Wf_task_ID=s.Wf_task_ID and p.SmallID=s.SmallID and p.Flag!='1'";
        return $this->_db->getArray($sql);
    }

    /**
     * 初始化审批信息
     * @param $workflowArr
     * @return null|string
     */
    function initAuditInfo_d($workflowArr)
    {
        $str = null;
        //渲染审批信息
        if ($workflowArr) {
            foreach ($workflowArr as $key => $val) {
                $trClass = $key % 2 == 0 ? "tr_odd" : "tr_even";
                $id = $val['ID'];
                $str .= <<<EOT
					<tr class="$trClass">
						<td>
							<a href="javascript:void(0);" onclick="showModalWin('?model=common_workflow_workflow&action=toViweObjInfo&taskId={$val['Wf_task_ID']}&spid={$val['ID']}&examCode={$val['code']}&billId={$val['Pid']}&formName={$val['name']}&code={$val['code']}&isTemp={$val['objCode']}');">$val[Wf_task_ID]</a>
							<input type="hidden" id="task$id" value="$val[Wf_task_ID]"/>
							<input type="hidden" id="spid$id" value="$val[ID]"/>
						</td>
						<td title="$val[infomation]">$val[objCode]</td>
						<td id="resultShow$id">
							<input type="radio" id="resultYes$id" name="result$id" value="ok" checked="checked" onclick="changeResult($id,'ok');"/><span id="resultYesInfo$id" class="blue">通过</span>
							<input type="radio" id="resultNo$id" name="result$id" value="no" onclick="changeResult($id,'no');"/><span id="resultNoInfo$id">不通过</span>
						</td>
						<td id="contentShow$id">
							<textarea id="content$id"></textarea>
						</td>
						<td id="mailShow$id">
							<input type="checkbox" id="isSend$id" checked="checked" value="y" title="通知提交人单据已审批"/>通知已审批
							<input type="checkbox" id="isSendNext$id" checked="checked" value="y" title="通知下一步审批者进行审批"/>通知下一步审批者
						</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * ajax 审核单据
     * @param $spid
     * @param string $result
     * @param string $content
     * @param string $isSend
     * @param string $isSendNext
     * @return bool|string
     * @throws Exception
     */
    function ajaxAudit_d($spid, $result = 'ok', $content = '', $isSend = 'y', $isSendNext = 'y')
    {
        //传入spid判断
        if (!$spid) {
            return '数据传入失败';
        }

        //审批流是否已经完成
        $isOver = false;
        //下一步审批人
        $nextChecker = "";

        //首先获取审批信息
        $sql = "select p.SmallID,p.Wf_task_ID,s.Flow_prop,t.Pid,t.code,t.name,t.Creator,t.DBTable,t.Enter_user
		    from flow_step_partent p , flow_step s , wf_task t
		    where t.task=p.Wf_task_ID and p.ID='$spid' and p.Wf_task_ID=s.Wf_task_ID and p.SmallID=s.SmallID and p.Flag!='1'";
        $workflowArr = $this->_db->getArray($sql);
        $workflowInfo = $workflowArr[0];

        // 如果没有注册，测提示异常
        if (!isset($this->batchAuditArr[$workflowInfo['name']])) {
            return "操作异常，没有注册的批量审批类型。";
        }
        if ($workflowInfo) {
            //正式进入审批流程
            try {
                $this->start_d();

                //暂时不明用途SQL
                $sql = "update wf_task set UpdateDT = now() where Status='ok' and task='" . $workflowInfo['Wf_task_ID'] . "' ";
                $this->_db->query($sql);

                //本步骤审批
                $sql = "update flow_step_partent set Flag='1', isMbFlag='3', User='" . $_SESSION['USER_ID'] . "',Result='" . $result . "',Content='" . $content . "',Endtime='" . date('Y-m-d H:i:s') . "' where ID=" . $spid;
                $this->_db->query($sql);

                //如果审批通过 或者 。。。
                if ($result == "ok" || $workflowInfo['Flow_prop'] == "1") {

                    //查询后面的审批步骤中是否有自己，有则删除
                    $sql = "select ID , User from flow_step where SmallID>'" . $workflowInfo['SmallID'] .
                        "' and Wf_task_ID='" . $workflowInfo['Wf_task_ID'] .
                        "' and find_in_set('" . $_SESSION['USER_ID'] . "',User)>0 ORDER BY SmallID";
                    $nextFlowArr = $this->_db->getArray($sql);
                    if(!empty($nextFlowArr)){
                        foreach ($nextFlowArr as $val) {
                            //后续审批含有自己审批步骤,删除
                            if (strstr($val['User'], $_SESSION['USER_ID']) !== false && !empty($val['ID'])){
                                $this->_db->query("delete from flow_step where ID='" . $val["ID"] . "'");
                            }
                            /*
                            if (trim($val["User"], ",") == $_SESSION['USER_ID']) {
                                $this->_db->query("delete from flow_step where ID='" . $val["ID"] . "'");
                            } else {
                                $this->_db->query("update flow_step set User='" . str_replace($_SESSION['USER_ID'] . ",", "", $val["User"]) . "' where ID='" . $val["ID"] . "' ");
                            }
                            */
                        }
                    }

                    //未知条件
                    $nextFlow = false;
                    if ($workflowInfo['Flow_prop'] == 1) {
                        $sql = "select ID from flow_step_partent where Wf_task_ID='" . $workflowInfo['Wf_task_ID'] . "' and SmallID='" . $workflowInfo['SmallID'] . "' and Flag!='1' and ID!='$spid' ";
                        $flowPartentArr = $this->_db->getArray($sql);
                        if (!$flowPartentArr || empty($flowPartentArr)) {
                            $nextFlow = true;
                        }
                    } else {
                        $sql = "delete from flow_step_partent where Wf_task_ID='" . $workflowInfo['Wf_task_ID'] . "' and SmallID='" . $workflowInfo['SmallID'] . "' and ID!='$spid' ";
                        $this->_db->query($sql);
                        $nextFlow = true;
                    }

                    //下一步处理
                    if ($nextFlow === true) {
                        $sql = "update flow_step set Flag='',Endtime=now() where Wf_task_ID='" . $workflowInfo['Wf_task_ID'] . "' and SmallID='" . $workflowInfo['SmallID'] . "'";
                        $this->_db->query($sql);

                        /************* 外包修改 1 开始 select语句添加查询 isReceive 和 isEditPage字段 *****************/
                        $sql = "select ID,User,Flag ,SmallID,isReceive,isEditPage from flow_step where SmallID>'" . $workflowInfo['SmallID'] .
                            "' and Wf_task_ID='" . $workflowInfo['Wf_task_ID'] . "' ORDER BY SmallID";
                        /************* 外包修改 1 结束 *****************/
                        $nextFlowArr = $this->_db->getArray($sql);

                        //如果还存在下一步的信息，则进行处理
                        if ($nextFlowArr[0]['ID']) {
                            $nextFlowInfo = $nextFlowArr[0];
                            $stepid = $nextFlowInfo["ID"];
                            $nextChecker = $nextFlowInfo["User"];
                            $nextSmallId = $nextFlowInfo["SmallID"];

                            /*************** 外包修改 2 开始 添加 isReceive 和 isEditPage字段 ********************/
                            $isReceive = $nextFlowInfo["isReceive"];
                            $isEditPage = $nextFlowInfo["isEditPage"];
                            /************* 外包修改 2 结束 *****************/

                            foreach (explode(",", trim($nextChecker, ',')) as $val) {
                                if ($val != "") {
                                    /*************** 外包修改 3 开始 插入添加 isReceive 和 isEditPage字段 ********************/
                                    $sql = "INSERT into flow_step_partent set StepID='$stepid',SmallID='$nextSmallId',Wf_task_ID='" . $workflowInfo['Wf_task_ID'] . "',User='$val',Flag='0',START=now(),isReceive='$isReceive',isEditPage='$isEditPage' ";
                                    /*************** 外包修改 3 结束 ******************/
                                    $this->_db->query($sql);
                                }
                            }
                        } else {
                            $sql = "update wf_task set examines='".$result."' , finish=now() , Status='0' where task='" . $workflowInfo['Wf_task_ID'] . "' and Status='ok' ";
                            $this->_db->query($sql);

                            $sql = "select PassSqlCode ,DisPassSqlCode, name from wf_task where task='" . $workflowInfo['Wf_task_ID'] . "'";
                            $taskArrs = $this->_db->getArray($sql);
                            $taskInfo = $taskArrs[0];
                            if($result == 'ok'){
                                if ($taskInfo['PassSqlCode'] != "") {
                                    $this->_db->query(stripslashes($taskInfo['PassSqlCode']));
                                }
                            }else if($result == 'no'){
                                if ($taskInfo['DisPassSqlCode'] != "") {
                                    $this->_db->query(stripslashes($taskInfo['DisPassSqlCode']));
                                }
                            }

                            //设置审批流已完成
                            $isOver = true;
                        }
                    }

                } elseif ($result == "no") {
                    //更新审批步骤为已审
                    $sql = "update flow_step set Flag='ok',Endtime=now() where Wf_task_ID='" . $workflowInfo['Wf_task_ID'] . "' and SmallID='" . $workflowInfo['SmallID'] . "'";
                    $this->_db->query($sql);

                    //更新审批表为打回
                    $sql = "update wf_task set examines='no' , Status='0' , finish=now() where task='" . $workflowInfo['Wf_task_ID'] . "' and Status='ok' ";
                    $this->_db->query($sql);

                    $sql = "select DisPassSqlCode , name from wf_task where task='" . $workflowInfo['Wf_task_ID'] . "'";
                    $taskArrs = $this->_db->getArray($sql);
                    $taskInfo = $taskArrs[0];
                    if ($taskInfo['DisPassSqlCode'] != "") {
                        $this->_db->query(stripslashes($taskInfo['DisPassSqlCode']));
                    }
                    //设置审批流已完成
                    $isOver = true;
                }

                //审批完成业务处理
                $allStep = isset($this->urlArr[$workflowInfo['name']]['allStep']) ? $this->urlArr[$workflowInfo['name']]['allStep'] : 0;
                if (($allStep || $isOver) && (isset($this->batchAuditArr[$workflowInfo['name']]) && !empty($this->batchAuditArr[$workflowInfo['name']]))) {
                    $newClass = $this->batchAuditArr[$workflowInfo['name']]['className'];
                    $newAction = $this->batchAuditArr[$workflowInfo['name']]['funName'];
                    $newClassDao = new $newClass();
                    if (method_exists($newClassDao, $newAction)) {
                        $newClassDao->$newAction($spid);
                    } else {
                        throw new Exception($newAction . '不存在于类' . $newClass . '中');
                    }
                }else if (($allStep || $isOver) && isset ($this->urlArr[$workflowInfo['name']]['rtUrl'])){
                    $addStr = null;
                    $url = $this->urlArr[$workflowInfo['name']]['rtUrl'] . $spid . $addStr;
                    $this->go($url);
                }

                $this->commit_d();
            } catch (Exception $e) {
                $this->rollBack();
                return $e->getMessage();
            }

            //邮件处理
            $this->mailSend_d($result, $isOver, $workflowInfo, $isSend, $workflowInfo['Enter_user'], $isSendNext, $nextChecker, $content);

            // 微信消息发送
            $this->WXMsgSend_d($result, $isOver, $workflowInfo, $isSend, $workflowInfo['Enter_user'], $isSendNext, $nextChecker, $content);

            return true;
        } else {
            return '审批已经完成，请重试';
        }
    }

    /**
     * 邮件处理
     */
    function mailSend_d($result, $isOver, $workflowInfo, $isSend = 'y', $enterUser, $isSendNext = 'y', $nextChecker, $content = null)
    {
        /************************* 审批邮件发送设置 ***************************/
        include(WEB_TOR . "model/common/workflow/workflowMailConfig.php"); //引入配置文件
        include(WEB_TOR . "model/common/workflow/workflowMailInit.php"); //引入配置方法文件
        include(WEB_TOR . "cache/DATADICTARR.cache.php"); //引入数据字典缓存
        $DATADICTARR = isset($DATADICTARR) ? $DATADICTARR : "";
        $workflowMailConfig = isset($workflowMailConfig) ? $workflowMailConfig : "";

        //实例化邮件类
        $mailDao = new model_common_mail();

        $emailBody = null; //邮件内容
        $emailSql = null; //邮件查询sql
        //获取邮件配置
        $taskName = $workflowInfo['code'];
        $pid = $workflowInfo['Pid'];
        $flowName = $workflowInfo['name'];
        $wfCreator = $workflowInfo['Creator'];

        //配置邮件信息
        if (!empty($workflowMailConfig[$taskName])) {
            //如果存在详细设置
            if ($workflowMailConfig[$taskName]['detailSet']) {

                //数据读取部分
                $sql = $workflowMailConfig[$taskName]['selectSql'] . " and c.id = $pid";
                $rows = $this->_db->getArray($sql);

                //模板拼装
                $thisFun = $workflowMailConfig[$taskName]['actFunc'];
                $emailBody = $thisFun($rows, $DATADICTARR);
            } else { //如果不存在详细设置
                $emailSql = "select " . $workflowMailConfig[$taskName]['thisCode'] . "  from " . $taskName . " c
			        left join wf_task b on (c.id=b.pid)
			        where c.id is not null  ";
                $emailBody = $workflowMailConfig[$taskName]['thisInfo'];

                $emailSql .= " and b.task='" . $workflowInfo['Wf_task_ID'] . "' ";
                $emailArrs = $this->_db->getArray($emailSql);
                $emailData = $emailArrs[0];

                if (is_array($emailData)) {
                    foreach ($emailData as $key => $val) {
                        $emailBody = str_replace('$' . $key, $val, $emailBody);
                    }
                }
            }
        }

        //判断是否发送到通知人
        if ($isSend == 'y') {
            $Subject = "OA-审批：" . $flowName;
            $ebody = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;" . $_SESSION['USERNAME'] . "已经对审批单号为：" . $workflowInfo['Wf_task_ID'] . " , 申请人：" . $wfCreator . " 的申请单进行审批！<br />&nbsp;&nbsp;&nbsp;&nbsp;审批结果：";
            if ($result == "no")
                $ebody .= "<span color='red'>不通过</span>";
            else
                $ebody .= "<span color='blue'>通过</span>";
            if (!empty($content)) {
                $ebody .= '<br />&nbsp;&nbsp;&nbsp;&nbsp;审批意见：' . $content;
            }
            if (!empty($emailBody)) {
                $ebody .= '<br /> &nbsp;&nbsp;&nbsp;&nbsp;单据详情：<br /> &nbsp;&nbsp;&nbsp;&nbsp;' . $emailBody;
            }
            $mailUser = $wfCreator != $enterUser ? $wfCreator . ',' . $enterUser : $enterUser;
            $mailDao->mailClear($Subject, $mailUser, $ebody);
        }

        //通知下一个审批人
        if ($isSendNext == "y" && $result == "ok" && $isOver == false && $nextChecker) {
            $Subject = "OA-审批：" . $flowName;
            $ebody = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;有新的审批单需要您审批！
	                <br />&nbsp;&nbsp;&nbsp;&nbsp;审批单号：" . $workflowInfo['Wf_task_ID'] . "
	                <br />&nbsp;&nbsp;&nbsp;&nbsp;这封邮件由" . $_SESSION["USERNAME"] . "选择给您发送！";
            if (!empty($content)) {
                $ebody .= '<br />&nbsp;&nbsp;&nbsp;&nbsp;审批意见：' . $content;
            }
            if (!empty($emailBody)) {
                $ebody .= '<br /> &nbsp;&nbsp;&nbsp;&nbsp;单据详情：<br /> &nbsp;&nbsp;&nbsp;&nbsp;' . $emailBody;
            }
            $mailDao->mailClear($Subject, $nextChecker, $ebody);
        }
    }

    /**
     * 微信发送方法
     * @param $result
     * @param $isOver
     * @param $workflowInfo
     * @param string $isSend
     * @param $enterUser
     * @param string $isSendNext
     * @param $nextChecker
     * @param null $content
     */
    function WXMsgSend_d($result, $isOver, $workflowInfo, $isSend = 'y', $enterUser, $isSendNext = 'y', $nextChecker, $content = null) {
        //获取邮件配置
        $flowName = $workflowInfo['name'];
        $wfCreator = $workflowInfo['Creator'];

        // 获取部门显示层级
        $otherDatasDao = new model_common_otherdatas();
        $sendWXMsg = $otherDatasDao->getConfig('workflow_send_wx_msg');
        if (!$sendWXMsg) {
            return;
        }

        //判断是否发送到通知人
        if ($isSend == 'y') {
            // 微信通知办理人
            $resultCN = $result == "no" ? "不通过" : "通过";
            $msg = "您好！[" . $_SESSION['USERNAME'] . "]已审批[" . $flowName . "]（单号：[" . $workflowInfo['Wf_task_ID'] . "]）。审批结果：[" . $resultCN . "]";
            if (!empty($content)) {
                $msg .= '，审批意见：[' . $content . "]。";
            } else {
                $msg .= '。';
            }
            $mailUser = $wfCreator != $enterUser ? $wfCreator . ',' . $enterUser : $enterUser;
            $userArr = array_unique(explode(',', $mailUser));
            foreach ($userArr as $v) {
                if ($v) {
                    // 从aws获取机票费用数据
                    util_curlUtil::getDataFromAWS('mobliemiro', 'WechatSendMsgAslp', array(
                        "userid" => $v, 'msg' => $msg
                    ), array(), true, 'com.youngheart.apps.');
                }
            }
        }

        //通知下一个审批人
        if ($isSendNext == "y" && $result == "ok" && $isOver == false && $nextChecker) {
            // 微信通知下一审批人
            $msg = "您好！流程[" . $flowName . "]（单号：[" . $workflowInfo['Wf_task_ID'] .
                "]）已推送至您的待办任务，请您登录OA系统或微信手机端进行审批。";
            if (!empty($content)) {
                $msg .= '上一个审批人审批意见：' . $content;
            }

            $userArr = array_unique(explode(',', $nextChecker));
            foreach ($userArr as $v) {
                if ($v) {
                    // 从aws获取机票费用数据
                    util_curlUtil::getDataFromAWS('mobliemiro', 'WechatSendMsgAslp', array(
                        "userid" => $v, 'msg' => $msg
                    ), array(), true, 'com.youngheart.apps.');
                }
            }
        }
    }

    /**
     * 获取单据的审批数据列表
     * @param $pid
     * @param $code
     * @param int $isChange
     * @param int $isPrint
     * @return array
     */
    function getBoAuditList_d($pid, $code, $isChange = 0, $isPrint = 0)
    {
        // 常规审批数据
        $rows = $this->getBoAuditInfo_d($pid, $code, $isPrint);

        // 变更数据获取
        $changeRows = "";

        if ($isChange) {
            $lastChangeInfo = $this->get_one("select max(id) as id from " . $code . " where originalId = " . $pid .
                " AND isTemp = 1 AND ExaStatus IN('部门审批','完成')");
            if ($lastChangeInfo) {
                $rows = $this->getBoAuditInfo_d($lastChangeInfo['id'], $code, $isPrint);
            }
        }

        return array("app" => $rows, "change" => $changeRows);
    }

    /**
     * 获取某业务的审批数据
     * @param $pid
     * @param $code
     * @param int $isPrint
     * @return array|bool
     */
    function getBoAuditInfo_d($pid, $code, $isPrint = 0)
    {
        // 常规审批数据获取
        $sql = "select
                f.SmallID AS stepNo,f.Item AS stepName,f.User AS auditor,p.User AS actAuditor,
                f.Endtime AS endTime,w.task,w.name,p.Result AS result, p.Content AS content
            from
                wf_task w
                LEFT JOIN flow_step f ON w.task = f.wf_task_ID
                LEFT JOIN flow_step_partent p ON w.task = f.wf_task_ID AND f.ID = p.StepID
            where w.Pid ='" . $pid . "' and w.code='" . $code . "'";
        if (!empty($isPrint)) {
            $sql .= " and w.examines='ok' ";
        }
        $this->sort = "f.SmallID";
        $this->asc = false;
        $rows = $this->listBySql($sql);

        return $rows;
    }

    /**
     * 获取统计数据
     * @param $formName
     * @return array|bool
     */
    function getCategoryList_d($formName)
    {
        // 常规审批数据获取
        $sql = "select name AS formName,COUNT(1) AS number
                from wf_task c right join
                flow_step_partent p on c.task = p.wf_task_id
                left join (SELECT group_concat( to_id)  as to_ids  , from_id   FROM power_set
                    where to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and STATUS=1
                    group by FROM_ID)  powi on (  find_in_set(   powi.from_id , p.User ) > 0 )
            where
                p.Flag = 0 AND c.examines = ''
                AND c.name IN(" . util_jsonUtil::strBuild($formName) . ") ";
        $this->searchArr = array(
            'findInName' => $_SESSION['USER_ID']
        );
        $this->groupBy = 'c.name';
        $this->sort = 'c.name';
        $rows = $this->listBySql($sql);
        return $rows;
    }

    /**
     * 获取统计数据
     * @param $formName
     * @return array|bool
     */
    function getAuditedCategoryList_d($formName)
    {
        // 上一年的时间节点
        $prev1YearTS = strtotime("-1 year");

        // 常规审批数据获取
        $sql = "select name AS formName,COUNT(1) AS number,MAX(c.finish) AS last
                from wf_task c right join
                flow_step_partent p on c.task = p.wf_task_id
                left join (SELECT group_concat( to_id)  as to_ids  , from_id   FROM power_set
                    where to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and STATUS=1
                    group by FROM_ID)  powi on (  find_in_set(   powi.from_id , p.User ) > 0 )
            where
                p.Flag = 1 AND UNIX_TIMESTAMP(c.START) >= $prev1YearTS
                AND c.name IN(" . util_jsonUtil::strBuild($formName) . ")";
        $this->searchArr = array(
            'findInName' => $_SESSION['USER_ID']
        );
        $this->groupBy = 'c.name';
        $rows = $this->listBySql($sql);

        // 做个排序
        $sortArr = array();
        foreach ($rows as $k => $v) {
            $sortArr[$k] = strtotime($v['last']);
        }
        arsort($sortArr);

        $rst = array();
        foreach ($sortArr as $k => $v) {
            $rst[] = $rows[$k];
        }

        return array_reverse($rows);
    }

    /* ======================================== 新审批页面（开始） ======================================== */
    // 测试方法
    function chkFunction($obj)
    {
        if (isset($obj['testFun'])) {
            return $obj['testFun'];
        } else {
            return false;
        }
    }


    function getLogicOpts(){
        return $this->_logicOpts;
    }

    /**
     * 获取表单类型数据
     * @param string $formName
     * @param string $formId
     * @return array|bool
     */
    function getFlowFormType($formName = '', $formId = '')
    {
        $sql = "select * from flow_form_type where 1=1";
        if ($formName != '') {
            $sql .= " AND FORM_NAME = '{$formName}'";
        }
        if ($formId != '') {
            $sql .= " AND FORM_ID = '{$formId}'";
        }
        $data = $this->_db->getArray($sql);
        return $data;
    }

    /**
     * 审批流必要参数空的赋初始值
     * @param $obj
     * @return array
     */
    function formatParamProcess($obj)
    {
        unset($obj['model']);
        unset($obj['action']);
        $backArr = array();
        foreach ($obj as $ok => $ov) {
            switch ($ok) {
                case 'examCode':
                    $backArr[$ok] = ($ov != '') ? $ov : ""; //审批数据表
                    break;
                case 'proId':
                    $backArr[$ok] = ($ov != '') ? $ov : "";
                    break;
                case 'billId':
                    $backArr[$ok] = ($ov != '') ? $ov : ''; //审批表单数据ID
                    break;
                case 'billArea':
                    $backArr[$ok] = ($ov != '') ? $ov : '';
                    break;
                case 'billDept':
                    $backArr[$ok] = ($ov != '') ? $ov : ''; //审批表单数据所属部门 -- 区域经理
                    break;
                case 'cktype':
                    $backArr[$ok] = ($ov != '') ? $ov : "";
                    break;
                case 'examTb':
                    $backArr[$ok] = ($ov != '') ? $ov : "";
                    break;
                case 'FLOW_ID':
                    $backArr[$ok] = ($ov != '') ? $ov : ""; //审批流ID
                    break;
                case 'formName':
                    $backArr[$ok] = ($ov != '') ? $ov : ""; //工作流表单名称
                    break;
                case 'flowType':
                    $backArr[$ok] = ($ov != '') ? $ov : ""; //工作流类型
                    break;
                case 'flowMoney':
                    $backArr[$ok] = ($ov != '') ? $ov : "0"; //工作流金额限制
                    break;
                default:
                    $backArr[$ok] = $ov;
                    break;
            }
        }
        return $backArr;
    }

    /* ======= 发起审批申请时的页面信息处理逻辑(开始) ======= */
    /**
     * 获取可审核流程获取
     * @param $obj
     * @return array
     */
    function getFlow_d($obj)
    {
        $sqlStr = "";
        $backArr = array();
        if (!empty($obj)) {
            if ($obj['flowMoney'] != 0) {
                $sqlStr .= " and  t.MinMoney<=" . $obj['flowMoney'] . " and ( t.MaxMoney>=" . $obj['flowMoney'] . " or t.MaxMoney=0  )";
            }
            if ($obj['flowType'] != "") {
                $sqlStr .= " and t.COST_FLOW_TYPE='{$obj['flowType']}' ";
            }
            if ($obj['formName'] != "") {
                $sqlStr .= " and ft.FORM_NAME='{$obj['formName']}' ";
            }
            if (isset($obj['flowDept']) && $obj['flowDept'] != "") {
                $sqlStr .= " and ( find_in_set('{$obj['flowDept']}',t.FLOW_DEPTS)>0 or t.FLOW_DEPTS='ALL_DEPT' )";
            }
        }

        $sql = "select t.FLOW_ID,t.FLOW_NAME,t.filtingSql,t.filtingClass,t.filtingFun from flow_type t,flow_form_type ft where ft.FORM_ID=t.FORM_ID " . $sqlStr;
        $workFlow = $this->_db->getArray($sql);
        $i = 0;
        $selectOptions = '';
        $flow_id = '';
        foreach ($workFlow as $wfv) {
            $need = 1;
            // 先检查过滤,判断此流程是否适用于此次申请的项目
            if ($wfv['filtingSql'] && $wfv['filtingSql'] != '') {
                $sql = $wfv['filtingSql'];
                foreach ($obj as $k => $v) {
                    $sql = str_replace('$' . $k, $v, $sql);
                }
                $chkResult = $this->_db->getArray($sql);
                $need = $chkResult ? 0 : 1; // 如果查询到结果,则过滤掉该审批流
            } else if ($wfv['filtingClass'] && $wfv['filtingClass'] != '' && $wfv['filtingFun'] && $wfv['filtingFun'] != '') {
                try {
                    if (class_exists($wfv['filtingClass'])) {
                        if (in_array($wfv['filtingFun'], get_class_methods($wfv['filtingClass']))) {
                            // 开始执行方法并获取对应的审批人
                            $exeFun = $wfv['filtingFun'];
                            $exeDao = new $wfv['filtingClass'];
                            $chkResult = $exeDao->$exeFun($obj); // 自定义的函数返回boolean，表示满足跳过此节点或否
                            $need = $chkResult ? 0 : 1; // 如果查询到结果,则过滤掉该审批流
                        }
                    }
                } catch (exception $e) {
                    echo "审批流过滤函数调用失败: " . $e->getMessage();
                }
            }

            // 如果进过上面的验证,该审批流无需过滤,则返回到前端
            if ($need) {
                $arr['flowName'] = $wfv['FLOW_NAME'];
                $arr['flowId'] = $wfv['FLOW_ID'];
                $backArr[] = $arr;
            }
        }
        return $backArr;
    }

    /**
     * 获取流程步骤
     * @param $obj
     * @return string
     **/
    function getProcess_d($obj)
    {
        $processObj = array(); // 流程实例数组
        $flow_id = isset($obj['flowId']) ? $obj['flowId'] : '';
        $sql = <<<EOT
        SELECT
            p.ID AS stepId,
            p.PRCS_NAME AS stepName,
            t.FLOW_NAME AS flowName,
            CASE p.PRCS_PROP
                WHEN 0 THEN '混合'
                WHEN 1 THEN '会签'
                WHEN 2 THEN '主签'
                END AS stepAttr,
            p.*,
            GROUP_CONCAT(u.USER_NAME) AS stepUser
        FROM
            flow_process p
            LEFT JOIN user u ON FIND_IN_SET(u.USER_ID,p.PRCS_USER) > 0
            LEFT JOIN flow_type t ON t.FLOW_ID = p.FLOW_ID
        WHERE
            p.FLOW_ID = $flow_id GROUP BY p.ID;
EOT;
        $stepsArr = $this->_db->getArray($sql);
        // 其他获取审批人的处理
        $stepsArr = $this->selectStepUsers($obj, $stepsArr);
        // 节点过滤
        $stepsArr = $this->decisionFilter($obj, $stepsArr);
        $backStepsArr = array();
        $stepId = 1;
        foreach ($stepsArr as $sk => $sv) {
            $arr['ID'] = $sv['ID'];
            $arr['stepName'] = $sv['stepName'];
            $arr['stepType'] = $sv['stepAttr'];
            $arr['stepUserId'] = $sv['PRCS_USER'].(($sv['PRCS_USER'] == '')? "" : ",").$sv['stepUserIds'];
            $arr['stepUser'] = $sv['stepUser'];
            $arr['stepId'] = $stepId;
            $backStepsArr[] = $arr;
            $stepId += 1;
        }

        $processObj['steps'] = $backStepsArr;
        return $processObj;
    }

    /**
     * (生成审批流页面主要函数)
     * 审批人处理,办理人读取优先级由高到低（办理人->特殊办理人->脚本->代码）
     *
     * @param $obj
     * @param $processArr
     * @return mixed
     */
    function selectStepUsers($obj, $processArr)
    {
        // 前台参数,自取最外层数据
        foreach ($obj as $ok => $ov) {
            if (is_array($ov)) {
                unset($obj[$ok]);
            }
        }

        $stepUserIds = "";
        // 重定义每个流程的审批人
        foreach ($processArr as $k => $v) {
            $stepUserStr = $v['stepUser'];
            // 【自定义特殊办理人】没有获取到默认审批人, 而设置了特殊审批人, 获取特殊审批人对应的名字
            if ($stepUserStr == '' && $v['PRCS_SPEC'] != '') {
                $specialUsers = $this->getSpecialAuditor($v['PRCS_SPEC'],$v['SPEC_TYPE'],$obj);
                $specialUsers = rtrim($specialUsers, ',');
                $sql1 = "select GROUP_CONCAT(USER_NAME) as stepUsers from user where USER_ID IN ({$specialUsers})";
                $usersArr = $this->_db->getArray($sql1);
                $stepUserStr = (!empty($usersArr)) ? $usersArr[0]['stepUsers'] : '';
                $stepUserIds = (!empty($usersArr)) ? str_replace("'","",$specialUsers) : '';
            }

            // 【自定义SQL语句】没获取到特殊办理人，而配置的执行脚本存在的话，就执行脚本获取审批人
            if ($stepUserStr == '' && $v['executorSearchSql'] != '') { //通过特定脚本获取审批人
                $exeSql = $v['executorSearchSql'];
                $hasVal = 0;
                foreach ($obj as $ok => $ov) {
                    // 如果传入的字段中有存在配置语句里的，替换对应的值
                    $exeSql = str_replace('$' . $ok, $ov, $exeSql);
                }
                $usersArr = $this->_db->getArray($exeSql);
//                $processArr[$k]['executorSearchSql'] = $exeSql;
                $stepUserStr = (!empty($usersArr)) ? $usersArr[0]['stepUsers'] : '';
            }

            // 【自定义函数】上面的都没查到对应的办理人，而配置的执行类和方法又存在的话，就执行对应函数获取审批人
            if ($stepUserStr == '' && $v['executorSearchClass'] != '' && $v['executorSearchFun'] != '') {
                if (class_exists($v['executorSearchClass'])) {
                    if (in_array($v['executorSearchFun'], get_class_methods($v['executorSearchClass']))) {
                        // 开始执行方法并获取对应的审批人
                        $exeFun = $v['executorSearchFun'];
                        $exeDao = new $v['executorSearchClass']();
                        $backArr = $exeDao->$exeFun($obj); // 自定义的函数里面一定要返回一个字段名为stepUsers（办理人字符串）的参数
                        if (isset($backArr['stepUsers']) && $backArr['stepUsers'] != '') {
                            $stepUserStr = $backArr['stepUsers'];
                        }
                    }
                }
            }
            $processArr[$k]['stepUser'] = $stepUserStr;
            $processArr[$k]['stepUserIds'] = $stepUserIds;

            // 去掉审批人选择的相关参数
            unset($processArr[$k]['executorSearchSql']);
            unset($processArr[$k]['executorSearchClass']);
            unset($processArr[$k]['executorSearchFun']);

        }
        return $processArr;
    }

    /**
     * 根据所选特殊办理人规则返回对应的办理人ID
     *
     * @param $code
     * @param $type
     * @param $extInfo (前端URL内GET到的参数)
     * @return string
     */
    function getSpecialAuditor($code,$type,$extInfo){
        $codeObj = explode(",",$code);
        if($type == 1){
            return $code;
        }else{
            $USER_ID = $_SESSION['USER_ID'];
            $userIds = '';
            switch ($code){
                case in_array('@bmfz',$codeObj):// 部门副总/总经理
                    if(isset($extInfo['billDept']) && $extInfo['billDept'] != ''){
                        $sql = "select a.ViceManager from department a where a.DEPT_ID='{$extInfo['billDept']}';";
                        $vManagerArr = $this->_db->getArray($sql);
                        if($vManagerArr){
                            foreach ($vManagerArr as $v){
                                $userIds .= ($v['ViceManager'] == "")? "" : rtrim($v['ViceManager'], ',').",";
                            }
                        }else{
                            $sql = "select u.USER_ID from user u, user_priv p where u.USER_PRIV=p.USER_PRIV and p.PRIV_NAME='总经理'";
                            $vManagerArr = $this->_db->getArray($sql);
                            foreach ($vManagerArr as $v){
                                $userIds = rtrim($userIds, ',');
                                $userIds .= ($v['USER_ID'] == "")? "" : rtrim($v['USER_ID'], ',').",";
                            }
                        }
                    }
                    break;
                case in_array('@bmfzj',$codeObj):// 部门副总监
                    if(isset($extInfo['billDept']) && $extInfo['billDept'] != ''){
                        $sql = "select d.MajorId , d.vicemagor , d.ViceManager from department d where d.DEPT_ID='{$extInfo['billDept']}'; ";
                        $objArr = $this->_db->getArray($sql);
                        if($objArr){
                            foreach ($objArr as $v){
                                $bmfzj = $v['vicemagor'];
                                if(empty($bmfzj)||trim($bmfzj,',')==$USER_ID){
                                    $bmfzj = $v['MajorId'];
                                }
                                if(empty($bmfzj)||trim($bmfzj,',')==$USER_ID){
                                    $bmfzj = $v['ViceManager'];
                                }
                                $userIds = rtrim($userIds, ',');
                                $userIds .= ($bmfzj == "")? "" : $bmfzj.",";
                            }
                        }
                    }
                    break;
                case in_array('@qyjl',$codeObj):// 区域经理
                    if(isset($extInfo['billDept']) && $extInfo['billDept'] != ''){
                        if(isset($extInfo['billArea']) && $extInfo['billArea'] != ''){
                            $sql = "select a.LeaderId from area_leader a where a.DEPT_ID='{$extInfo['billDept']}' and a.AreaId='{$extInfo['billArea']}'";
                        }else{
                            $sql = "select a.LeaderId from area_leader a,user u where u.AREA=a.AreaId and a.DEPT_ID='{$extInfo['billDept']}' and u.USER_ID='{$USER_ID}'";
                        }
                        $objArr = $this->_db->getArray($sql);
                        if($objArr){
                            foreach ($objArr as $v){
                                $userIds .= ($v['LeaderId'] != '')? rtrim($v['LeaderId'], ',')."," : "";
                            }
                        }
                    }
                    break;
                case in_array('@xmjl',$codeObj):// 项目经理
                    $sql = "";
                    if(isset($extInfo['proSid']) && $extInfo['proSid']!=""){
                        $sql = "select x.Manager from xm_lx x where x.ID='".$extInfo['proSid']."'";
                    }elseif(isset($extInfo['proId']) && $extInfo['proId']!=""){
                        $sql = "select x.Manager from xm_lx x where x.ProId='".$extInfo['proId']."' order by Flag , BeginDate desc limit 0 , 1  ";
                    }
                    $objArr = ($sql != "")? $this->_db->getArray($sql) : false;
                    if($objArr){
                        foreach ($objArr as $v){
                            $userIds .= ($v['Manager'] != '')? rtrim($v['Manager'], ',')."," : "";
                        }
                    }

                    break;
                case in_array('@wgcqy',$codeObj):// 工程办事处经理
                    //工程区域办事处经理
                    $billArea = isset($extInfo['billArea'])? trim($extInfo['billArea']) : false;
                    if($billArea){
                        $sql = "SELECT mainManagerId,managerId FROM oa_esm_office_range where id='$billArea'";
                        $objArr = $this->_db->getArray($sql);
                        $ckarray = array();
                        foreach ($objArr as $v){
                            if($v["managerId"]){
                                $ckarray[]=trim($v["managerId"],',');
                            }
                            if($v["mainManagerId"]){
                                $ckarray[]=trim($v["mainManagerId"],',');
                            }
                        }

                        $ckarray = array_diff($ckarray, array(null,''));
                        $ckarray = array_unique($ckarray);
                        $ckarray = array_values($ckarray);

                        $userIds .= rtrim($ckarray[0], ',').",";
                    }
                    break;
            }

            $userIds = "'".str_replace(",","','",rtrim($userIds,","))."'";
//            echo "<pre>";print_r($userIds);exit();
            return $userIds;
        }
    }

    /**
     * 根据设置的脚本或函数对相应节点的流程进行过滤
     * @param $obj
     * @param $processArr
     * @return mixed
     */
    function decisionFilter($obj, $processArr)
    {
        $backArr = array();
        foreach ($processArr as $k => $v) {
            $result = '';
            // 先执行脚本
            if ($v['decisionSql']) {
                $exeSql = $v['decisionSql'];
                foreach ($obj as $ok => $ov) {
                    $exeSql = str_replace('$' . $ok, $ov, $exeSql);
                }
                $processArr[$k]['decisionSql'] = $exeSql;
                $resultArr = $this->_db->getArray($exeSql);
                $result = empty($resultArr) ? '' : '1';
            }
            // 脚本执行结果为空，但有配置类和函数的，执行函数
            if ($result == '' && $v['decisionClass'] && $v['decisionFun']) { // 先执行脚本
                if (class_exists($v['decisionClass'])) {
                    if (in_array($v['decisionFun'], get_class_methods($v['decisionClass']))) {
                        // 开始执行方法并获取对应的审批人
                        $exeFun = $v['decisionFun'];
                        $exeDao = new $v['decisionClass']();
                        $chkResult = $exeDao->$exeFun($obj); // 自定义的函数返回boolean，表示满足跳过此节点或否
                        $result = $chkResult ? '1' : '';
                    }
                }
            }

            // 如果以上都没查到对应的过滤数据则返回该流程
            if ($result == '') {
                // 去掉节点过滤的相关参数
                unset($processArr[$k]['decisionSql']);
                unset($processArr[$k]['decisionClass']);
                unset($processArr[$k]['decisionFun']);
                array_push($backArr, $processArr[$k]);
            }
        }
        return $backArr;
    }

    /**
     * 加上相应审批表单的数据
     * @param $obj
     * @return mixed
     */
    function joinFormData($obj)
    {
        if (isset($obj['formName']) && !empty($obj['formName'])) {
            $sql = "select * from flow_form_type where FORM_NAME = '{$obj['formName']}';";
            $formData = $this->_db->get_one($sql);
            if ($formData) {
                // 把前端传入最外层参数刷一次对应的语句或链接字符串,如有对应的字符,则替换掉对应的值
                foreach ($obj as $ok => $ov) {
                    if (!is_array($ov)) {
                        $formData['PASS_SQL'] = str_replace('$' . $ok, $ov, $formData['PASS_SQL']);
                        $formData['DISPASS_SQL'] = str_replace('$' . $ok, $ov, $formData['DISPASS_SQL']);
                        $formData['viewUrl'] = str_replace('$' . $ok, $ov, $formData['viewUrl']);
                        $formData['infomationSql'] = str_replace('$' . $ok, $ov, $formData['infomationSql']);
                    }
                }
                $exeTb = isset($obj['examCode']) ? $obj['examCode'] : "";
                $billId = isset($obj['billId']) ? $obj['billId'] : "";
                $formData['PASS_SQL'] = ($formData['PASS_SQL'] != '') ? $formData['PASS_SQL'] : "update {$exeTb} set ExaStatus = '完成',ExaDT = now() where id='{$billId}' "; //审批完成后更新语句
                $formData['DISPASS_SQL'] = ($formData['DISPASS_SQL'] != '') ? $formData['DISPASS_SQL'] : "update {$exeTb} set ExaStatus = '打回',ExaDT = now() where id='{$billId}'"; //审批打回更新语句
                $obj['formData'] = $formData;
            }
        }
        return $obj;
    }

    /* ======= 发起审批申请时的页面信息处理逻辑(结束) ======= */

    /* ======= 接受并处理提交的审批申请(开始) ======= */
    function saveAuditApply_d($obj, $sendToURL)
    {
//        echo"<pre>";print_r($obj);exit();
        $resultArr = array();
        $resultArr['msg'] = '';
        $resultArr['data'] = array();
        $formData = (isset($obj['formData']) && is_array($obj['formData'])) ? $obj['formData'] : array(); // 审批流相关表单信息
        $processSteps = (isset($obj['steps']) && is_array($obj['steps'])) ? $obj['steps'] : array(); // 所申请审批的处理流程信息

        // 先检查审批步骤信息是否正常（start）
        $errorNum = 0;
        if(!empty($processSteps)){
            foreach ($processSteps as $psV){
                if($psV['stepUserId'] == '' || $psV['stepUser'] == ''){
                    $errorNum += 1;
                }else if(!$psV['ID'] || $psV['ID'] == ''){
                    $errorNum += 1;
                }
            }
        }else{
            $errorNum += 1;
        }

        if($errorNum > 0){
            $resultArr['msg'] = 'fail';
            $resultArr['data']['error'] = $errorNum;
            $resultArr['data']['result'] = '审批流程步骤信息有误!';
            return $resultArr;
            exit();
        }
        // 先检查审批步骤信息是否正常（end）

        try {
            $this->start_d();
            $information = $objCode = $objName = $objCustomer = $objAmount = $sql = "";
            /*********** -开始- 获取infomation扩展字段值**************/
            if (isset($formData['infomation']) && $formData['infomation'] != '') { // 有需要呈现的业务信息
                $information = $formData['infomation'];
                // 执行脚本语句查询相关的摘要信息
                if (isset($formData['infomationSql']) && $formData['infomationSql'] != '') {
                    $searchInfoSql = stripslashes(stripslashes($formData['infomationSql']));
                    $informationArr = $this->_db->getArray($searchInfoSql);
                    $informationArr = ($informationArr && !empty($informationArr)) ? $informationArr[0] : '';

                    //如果数据存在，构建该数据，否则报错
                    if (is_array($informationArr)) {
                        foreach ($informationArr as $key => $val) {
                            $information = str_replace('$' . $key, $val, $information);
                        }
                    } else {
                        throw new Exception("审批对象数据不存在！");
                    }
                }

                // 执行配置类和函数获取对应的摘要信息
                if (isset($formData['infomationClass']) && $formData['infomationClass'] != '' && isset($formData['infomationFun']) && $formData['infomationFun'] != '') {
                    $model = $formData['infomationClass'];
                    $action = $formData['infomationFun'];
                    if (class_exists($model)) {
                        if (in_array($action, get_class_methods($model))) {
                            // 开始执行方法并获取对应的附加信息
                            $exeDao = new $model();
                            $infoArr = $exeDao->$action($obj);
                            //如果数据存在，构建该数据，否则报错
                            if (is_array($infoArr)) {
                                foreach ($infoArr as $key => $val) {
                                    $information = str_replace('$' . $key, $val, $information);
                                }
                            } else {
                                throw new Exception("审批对象数据不存在！");
                            }
                        } else {
                            throw new Exception("此类‘{$model}’中不存在方法‘{$action}’！");
                        }
                    } else {
                        throw new Exception("调用类{$model}不存在！");
                    }
                }

            }
            /************ -结束- 获取infomation扩展字段值**************/

            // 检查是否已经存在此审批类型以及BillId下的审批单
            $exitTaskChkSql = "select task from wf_task where name='{$obj['formName']}' and code='{$obj['examCode']}' and Pid='{$obj['billId']}' and Status='ok';"; // and DBTable='".$_SESSION["COM_BRN_SQL"]."' //此字段暂时不清楚用途
            $exitTaskChk = $this->_db->getArray($exitTaskChkSql);
            if (is_array($exitTaskChk) && !empty($exitTaskChk)) {
                throw new Exception("审批对象已经存在同类型为：{$obj['formName']} 的审批单！");
            }

            //更新信息 给予打回 事务控制
            $updateExCodeSql = "update {$obj['examCode']} set ExaStatus = '部门审批' , ExaDT = now() where ID='{$obj['billId']}' ";
            $this->query($updateExCodeSql);
            $selectFlTypeSql = "select ClassID,FORM_ID, FLOW_NAME from flow_type where FLOW_ID='{$obj['flowId']}'";
            $flowTypeArr = $this->_db->getArray($selectFlTypeSql);
            $form = 0;
            if (is_array($flowTypeArr[0]) && !empty($flowTypeArr[0])) {
                $sql = "update wf_class set Ccount=Ccount+1 where class_id=" . $flowTypeArr[0]["ClassID"];
                //echo $sql;br();//调试用
                $form = $flowTypeArr[0]["FORM_ID"];
                $this->query($sql);
            }

            // 业务人员：
            if (isset($obj['objUser']) && !empty($obj['objUser'])) {
                $wfu = $obj['objUser'];
                $wfun = $this->getUserByUid($obj['objUser']);
            } else {
                $wfu = $_SESSION['USER_ID'];
                $wfun = $_SESSION['USER_NAME'];
            }

            // 插入审批主表信息
            $addMainArr['infomation'] = $information;
            $addMainArr['objCode'] = $objCode;
            $addMainArr['objName'] = $objName;
            $addMainArr['objCustomer'] = $objCustomer;
            $addMainArr['objAmount'] = $objAmount;
            $addMainArr['Creator'] = $wfu;
            $addMainArr['Enter_user'] = $wfu;
            $addMainArr['name'] = $obj['formName'];
            $addMainArr['code'] = $obj['examCode'];
            $addMainArr['form'] = $form;
            $addMainArr['start'] = date("Y-m-d H:i:s");
            $addMainArr['train'] = $obj['flowId'];
            $addMainArr['Status'] = 'ok';
            $addMainArr['Pid'] = $obj['billId'];
            $addMainArr['PassSqlCode'] = addslashes($formData['PASS_SQL']);
            $addMainArr['DisPassSqlCode'] = addslashes($formData['DISPASS_SQL']);
            $addMainArr['UpdateDT'] = date("Y-m-d H:i:s");
            $addMainArr['DBTable'] = ''; //$_SESSION["COM_BRN_SQL"];
            $addMainArr['objUser'] = $wfu;
            $addMainArr['objUserName'] = $wfun;
//            $resultArr['data']['addMainArr'] = $addMainArr;$taskid = '123';//调试用
            $taskid = $this->add_d($addMainArr);

            // 每个步骤生成相应的审批处理记录
            $firstChecker = "";
            if (!empty($processSteps)) {
                $Flag = 0;
                $Smallval = 1;
                $stepTotals = count($processSteps);
                $stepNum = 0;
//                $resultArr['data']['flowStepArr'] = array();//调试用
                $tbName = $this->tbl_name;
                $this->tbl_name = 'flow_step'; //先用flow_step表名来插入数据
                foreach ($processSteps as $stepK => $stepV) {
                    // 获取处理步骤的相关信息
                    $getSQL = "select * from flow_process where ID = '{$stepV[ID]}'";
                    $stepData = $this->get_one($getSQL);

                    // 根据流程负责人名字获取对应ID
                    $userIdArr = $this->_db->getArray("select GROUP_CONCAT(USER_ID) as userIds from user where FIND_IN_SET(USER_NAME,'{$stepV['stepUser']}') > 0;");
                    $AC_Users = ($userIdArr && !empty($userIdArr)) ? $userIdArr[0]['userIds'] : '';
                    // 组合处理流程具体数据
                    $flowStepArr['SmallID'] = $Smallval;
                    $flowStepArr['Wf_task_ID'] = $taskid;
                    $flowStepArr['Flow_id'] = $stepData['FLOW_ID'];
                    $flowStepArr['Step'] = $stepData['PRCS_ID'];
                    $flowStepArr['StepID'] = $stepData['ID'];
                    $flowStepArr['Item'] = $stepData['PRCS_NAME'];
                    $flowStepArr['User'] = $AC_Users;
                    $flowStepArr['PRCS_ITEM'] = ''; //$stepData['PRCS_ITEM'];
                    $flowStepArr['Flag'] = $Flag;
                    $flowStepArr['status'] = 'ok';
                    $flowStepArr['Flow_name'] = $obj['formName'];
                    $flowStepArr['secrecy'] = '1';
                    $flowStepArr['speed'] = '1';
                    $flowStepArr['quickpipe'] = '0';
                    $flowStepArr['quickreason'] = '';
                    $flowStepArr['sendread'] = '';
                    $flowStepArr['Flow_prop'] = $stepData['PRCS_PROP'];
                    $flowStepArr['PRCS_ALERT'] = $stepData['PRCS_ALERT'];
                    $flowStepArr['Flow_doc'] = '1';
                    $flowStepArr['Flow_type'] = '1';
                    $flowStepArr['ATTACHMENT_MEMO'] = '';
                    $flowStepArr['Start'] = date("Y-m-d H:i:s");
                    $flowStepArr['passlimit'] = $stepData['passlimit'];
                    $flowStepArr['isReceive'] = $stepData['isReceive'];
                    $flowStepArr['isEditPage'] = $stepData['isEditPage'];
//                    $resultArr['data']['flowStepArr'][] = $flowStepArr;//调试用

                    $stepid = $this->add_d($flowStepArr);
                    if (!$stepid)
                        throw new Exception("dberror");
                    if ($Smallval == 1) {
                        //在流程处理表插入第一步相关办理人的记录
                        $firstChecker = $AC_Users;
                        foreach (explode(",", $AC_Users) as $val) {
                            if ($val != "") {
                                $sql = "INSERT into flow_step_partent set StepID='{$stepid}',SmallID='{$Smallval}',Wf_task_ID='$taskid',User='$val',Flag='$Flag',START='" . date("Y-m-d H:i:s") . "',isReceive='$stepV[isReceive]',isEditPage='$stepV[isEditPage]'";
                                $this->_db->query($sql);
                            }
                        }
                    }

                    $Flag++;
                    ++$Smallval;
                }
                $this->tbl_name = $tbName; //插入数据后换回原来表名
            }

            // 请休假审批类型数据处理
            if ($obj['formName'] == '请休假') {
                $sql = "select
                u.user_name , h.type , h.begindt
                , h.enddt , h.beginhalf , h.endhalf
                , h.reason
            from wf_task t
                left join hols h on ( t.pid=h.id )
                left join hrms hr on (h.userid=hr.user_id)
                left join user u on (hr.user_id=u.user_id)
            where
                t.code ='hols'
                and task='" . $taskid . "' ";
                $data = $this->_db->getArray($sql);
                $mainData = array();
                if ($data && !empty($data)) {
                    $mainData['申请人：'] = $data[0]['user_name'];
                    $mainData['请假类型：'] = $data[0]['type'];
                    $mainData['开始日期：'] = $data[0]['begindt'];
                    $mainData['截止日期：'] = $data[0]['enddt'];
                    $mainData['申请原因：'] = $data[0]['reason'];
                }
            }

            // 发送邮件
            if ($obj["isSendNotify"] == "y" && $firstChecker != "") {
                $TO_ID = $firstChecker;
                $Subject = "OA-审批：{$obj['formName']}";
                $ebody = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;您有新的审批单需要审批！<br />&nbsp;&nbsp;&nbsp;&nbsp;审批单号：" . $taskid . "<br />&nbsp;&nbsp;&nbsp;&nbsp;这封邮件由" . $_SESSION["USERNAME"] . "选择给您发送！";
                if ($obj['formName'] == '请休假') {
                    $ebody .= "<br /> &nbsp;&nbsp;&nbsp;&nbsp;详情如下：";
                    foreach ($mainData as $key => $val) {
                        $ebody .= '<br /> &nbsp;&nbsp;&nbsp;&nbsp;' . $key . ' ' . $val;
                    }
                }
                if (!empty($information)) {
                    $ebody .= '<br /> &nbsp;&nbsp;&nbsp;&nbsp;单据详情：<br /> &nbsp;&nbsp;&nbsp;&nbsp;' . $information;
                }
                $emailDao = new model_common_mail();
                $emailDao->mailClear($Subject, $TO_ID, $ebody);
            }
            $this->commit_d();
        } catch (exception $e) {
            $this->rollBack();
            $reason = "提交失败，" . $e->getMessage();
            $resultArr['data']['error'] = 1;
            $resultArr['data']['result'] = $reason;
        }

        // 处理处理结果,并返回数据
        if (isset($resultArr['data']['error']) && $resultArr['data']['error'] > 0) {
            return $resultArr;
        } else {
            //提交成功后返回指定路径
            $resultArr['msg'] = 'ok';
            $resultArr['data']['error'] = 0;
            $resultArr['data']['result'] = '提交成功!';
            $resultArr['data']['sendToURL'] = ($sendToURL == '') ? '' : str_replace(" ", "", $sendToURL);
            return $resultArr;
        }
        return $resultArr;
    }
    /* ======= 接受并处理提交的审批申请(结束) ======= */

    /* ======================================== 新审批页面（结束） ======================================== */

    /* ======================================== 新审批流配置页面（开始） ======================================== */
    /**
     * 根据用户ID获取用户名
     * @param $uid
     * @return string
     */
    function getUserByUid($uid)
    {
        $uid = trim($uid, ",");
        $uidArr = explode(",", $uid);
        $backUserName = '';
        if (count($uidArr) > 0) {
            foreach ($uidArr as $v) {
                $sql = "SELECT USER_NAME FROM `user` WHERE USER_ID = '{$v}';";
                $userName = $this->_db->getArray($sql);
                $backUserName .= ($userName) ? $userName[0]['USER_NAME'] . ',' : '';
            }
        } else {
            $sql = "SELECT USER_NAME FROM `user` WHERE USER_ID = '{$uid}';";
            $userName = $this->_db->getArray($sql);
            $backUserName = ($userName) ? $userName[0]['USER_NAME'] : '';
        }
        $backUserName = trim($backUserName, ",");
        return $backUserName;
    }

    /**
     * 根据FormId读取流程类型
     * @param $formId
     * @param string $advCondition
     * @param string $group
     * @param string $order
     * @param string $limit
     * @return array|bool
     */
    function getWfByFormId_d($formId, $advCondition = '', $group = '', $order = '', $limit = '')
    {
        $formIdStr = ($formId == '') ? '' : "ft.FORM_ID = {$formId}";
        $condition = $formIdStr . $advCondition;
        $sql = "
            SELECT
                ft.*, wfc. NAME AS className,
                CASE ft.FLOW_TYPE
                WHEN 1 THEN '固定流程'
                WHEN 2 THEN '自由流程'
                END AS flowType
            FROM
                flow_type ft
            LEFT JOIN wf_class wfc ON wfc.class_id = ft.ClassID
            WHERE {$condition} {$group} {$order} {$limit};";
        $rows = $this->_db->getArray($sql);
        $arr = ($rows) ? $rows : array();
        return $arr;
    }

    /**
     * 根据FormId统计流程类型数量
     * @param $formId
     * @param string $advCondition
     * @param string $group
     * @param string $order
     * @param string $limit
     * @return int
     */
    function countWfByFormId_d($formId, $advCondition = '', $group = '', $order = '', $limit = '')
    {
        $countSql = "
            SELECT
                COUNT(ft.FLOW_ID) as Num
            FROM
                flow_type ft
            LEFT JOIN wf_class wfc ON wfc.class_id = ft.ClassID
            WHERE
	          ft.FORM_ID = {$formId}
	          {$advCondition} {$group} {$order} {$limit};";
        $arr = $this->_db->getArray($countSql);
        $totalSize = ($arr) ? $arr[0]['Num'] : 0;
        return $totalSize;
    }

    /**
     * 获取公文类别
     * @return array|bool
     */
    function getWfClass()
    {
        $sql = "select *  from wf_class order by class_id;";
        $data = $this->_db->getArray($sql);
        return $data;
    }
    /* ======================================== 新审批流配置页面（结束） ======================================== */
    /**
     * 构建审批情况
     */
    function viewMobile_d($pid, $code)
    {
        // 查询审批记录
        $sql = "select
                p.task,p.start,p.User,p.name,p.Content,p.Result,p.Endtime,
                p.ID,p.Flag,p.Item,p.examines,p.stepUser,u.USER_NAME
            from
            (
                select
                    w.task,w.start,w.name,p.User,p.Content,p.Result,p.Endtime,
                    p.ID,p.Flag,f.Item,w.examines,f.ID AS sid,f.User AS stepUser
                from flow_step f
                    RIGHT JOIN wf_task w ON w.task = f.Wf_task_ID
                    LEFT JOIN flow_step_partent p on p.StepID = f.ID WHERE
                    w.pid = $pid
                AND w.CODE = '$code'
            ) p left JOIN user u on p.User = u.USER_ID
            ORDER BY p.task, p.sid";
        $rs = $this->_db->getArray($sql);

        // 返回数据
        $data = array();

        // 构建返回数据
        if (!empty($rs)) {
            // 实例化查询类
            $otherDataDao = new model_common_otherdatas();

            foreach ($rs as $v) {
                // 如果还没缓存过，先缓存主审批信息
                if (!isset($data[$v['task']])) {
                    $data[$v['task']] = array(
                        'name' => $v['name'],
                        'start' => $v['start'],
                        'steps' => array(
                        )
                    );
                }
                // 加入审批详情
                $data[$v['task']]['steps'][] = array(
                    'stepName' => $v['Item'],
                    'result' => $v['Result'],
                    'content' => $v['Content'] ? $v['Content'] : '无',
                    'stepUser' => $v['USER_NAME'] ? $v['USER_NAME'] : trim($otherDataDao->getUsernameList($v['stepUser']), ","),
                    'backUser' => $v['Result'] == 'no' ? $v['USER_NAME'] : "",
                    'endTime' => $v['Endtime']
                );
            }
        }

        return array_values($data);
    }

    /**
     * header go
     * @param $url string
     */
    function go($url)
    {
        $baseUrl = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        $baseUrl .= '://' . $_SERVER['HTTP_HOST'];
        if (substr($url, 0, 1) != "?") {
            $projectUrl = str_replace('index1.php', '', $_SERVER['PHP_SELF']);
        } else {
            $projectUrl = $_SERVER['PHP_SELF'];
        }
        header("Location: " . $baseUrl . $projectUrl . $url, TRUE, 302);
    }

    /**
     * 获取待办中所有记录包含的审批类型
     * @return array
     */
    function getAuditingIncludesFormName(){
        $inNames = $this->rtWorkflowStr_d();
        $inNames = str_replace(",","','",$inNames);
        $findInName = $_SESSION['USER_ID'];
        $chkSql = <<<EOT
        select t.name from(
select p.ID as id,c.task,c.infomation,c.name,c.name as orgName,c.code,c.start,c.Creator,c.Pid ,
		if(u.has_left='1', concat(u.USER_NAME,'（离职）') , u.USER_NAME ) as creatorName,p.isReceive,p.isEditPage,c.receiveStatus,c.recevierId,c.recevierName,c.recevieTime,
		'' as thisAction,c.receiveStatus as receiveStatusAction,c.objCode,c.objName,c.objCustomer,c.objAmount,c.objUser,c.objUserName,c.projectCode,c.isImptSubsidy,
		if(c.isImptSubsidy = '是', '补贴', if(l.id is null,'-',if((l.allregisterId is null or l.allregisterId <= 0),'个人创建','租车'))) as costSourceType
		from wf_task c right join
		flow_step_partent p on c.task = p.wf_task_id
		left join cost_summary_list l on (c.code = 'cost_summary_list' and c.Pid = l.id)
		left join (SELECT group_concat( to_id)  as to_ids  , from_id   FROM power_set
           where to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and STATUS=1
           group by FROM_ID)  powi on (  find_in_set(   powi.from_id , p.User ) > 0 )
       left join user u on c.Enter_user = u.USER_ID where
		p.Flag = 0 AND c.examines = '' and((  ( ( find_in_set( '$findInName' , p.User ) > 0 ) or ( find_in_set( '$findInName' , powi.to_ids  ) > 0 )))) 
		and(( c.name  in ('$inNames'))) 
		order by id DESC)t group by t.name order by t.name
EOT;
        $resultArr = $this->_db->getArray($chkSql);
        $backArr = array();
        if($resultArr){
            foreach ($resultArr as $val){
                $backArr[] = $val['name'];
            }
        }
        return $backArr;
    }

    /**
     * 获取已办中所有记录包含的审批类型
     * @return array
     */
    function getAuditedIncludesFormName(){
        $inNames = $this->rtWorkflowStr_d();
        $inNames = str_replace(",","','",$inNames);
        $findInName = $_SESSION['USER_ID'];
        $chkSql = <<<EOT
        select t.name from(
select p.ID as id,c.task,c.infomation,c.name,c.name as orgName,c.code,c.start,c.Creator,c.Pid ,
		if(u.has_left='1', concat(u.USER_NAME,'（离职）') , u.USER_NAME ) as creatorName,p.isReceive,p.isEditPage,c.receiveStatus,c.recevierId,c.recevierName,c.recevieTime,
		'' as thisAction,c.receiveStatus as receiveStatusAction,c.objCode,c.objName,c.objCustomer,c.objAmount,c.objUser,c.objUserName,c.projectCode,c.isImptSubsidy,
		if(c.isImptSubsidy = '是', '补贴', if(l.id is null,'-',if((l.allregisterId is null or l.allregisterId <= 0),'个人创建','租车'))) as costSourceType
		from wf_task c right join
		flow_step_partent p on c.task = p.wf_task_id
		left join cost_summary_list l on (c.code = 'cost_summary_list' and c.Pid = l.id)
		left join (SELECT group_concat( to_id)  as to_ids  , from_id   FROM power_set
           where to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and STATUS=1
           group by FROM_ID)  powi on (  find_in_set(   powi.from_id , p.User ) > 0 )
       left join user u on c.Enter_user = u.USER_ID where
		p.Flag = 1 and((  ( ( find_in_set( '$findInName' , p.User ) > 0 ) or ( find_in_set( '$findInName' , powi.to_ids  ) > 0 )))) 
		and(( c.name  in ('$inNames'))) 
		order by id DESC)t group by t.name order by t.name
EOT;
        $resultArr = $this->_db->getArray($chkSql);
        $backArr = array();
        if($resultArr){
            foreach ($resultArr as $val){
                $backArr[] = $val['name'];
            }
        }
        return $backArr;
    }
}