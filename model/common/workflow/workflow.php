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

        //��������Ӧ���鼰�������Ϣ
        $this->urlArr = isset($urlArr) ? $urlArr : null;
        //������������
        $this->batchAuditArr = isset($batchAuditArr) ? $batchAuditArr : null;

        //������ʷ��������
        $this->ExaInfoUserArr = isset($ExaInfoUserArr) ? $ExaInfoUserArr : null;

        //��������Ӧ�������
        $this->changeFunArr = isset($changeFunArr) ? $changeFunArr : null;

        //�յ�������Ӧ����
        $this->receiveActionArr = isset($receiveActionArr) ? $receiveActionArr : null;
        //�˵�������Ӧ����
        $this->backActionArr = isset($backActionArr) ? $backActionArr : null;

        $this->tbl_name = "wf_task";
        $this->sql_map = "common/workflow/workflowSql.php";

        // �������������߼������Ӧ����
        $this->_logicOpts = array(
            array("code"=>"@bmfz","name"=>"���Ÿ���/�ܾ���"),
            array("code"=>"@bmfzj","name"=>"���Ÿ��ܼ�"),
            array("code"=>"@qyjl","name"=>"������"),
            array("code"=>"@xmjl","name"=>"��Ŀ����"),
            array("code"=>"@wgcqy","name"=>"���̰��´�����")
        );

        parent:: __construct();
    }

    //��������
    public $urlArr;

    //������������
    public $batchAuditArr;

    //�������
    public $changeFunArr;

    //�յ�������Ӧ����
    public $receiveActionArr;
    //�˵�������Ӧ����
    public $backActionArr;

    /**
     * ����ע�Ṥ������
     */
    function rtWorkflowStr_d()
    {
        return implode(array_keys($this->urlArr), ',');
    }

    /**
     * ����ע�Ṥ��������
     */
    function getBatchAudit_d()
    {
        return array_keys($this->batchAuditArr);
    }

    /**
     * ���ؿ���������������
     */
    function rtWorkflowArr_d()
    {
        return array_keys($this->urlArr);
    }

    /**
     * ����������ʷ�鿴����������
     */
    function rtWorkflowExInfoArr_d()
    {
        return array_keys($this->ExaInfoUserArr);
    }

    /**
     * ��������������
     * @param $object
     * @return mixed
     */
    function rowsDeal_d($object)
    {
        if (!empty($object) && !empty($this->changeFunArr)) {
            //���ڱ���ı�����
            $changeKeyArray = array_keys($this->changeFunArr);

            foreach ($object as $key => $val) {
                //�жϵ�ǰ���Ƿ��ڱ��������
                if (in_array($val['name'], $changeKeyArray)) {

                    //��֤�Ƿ��Ǻ�ͬ���
                    $objClassStr = $this->changeFunArr[$val['name']]['className'];
                    $objFunStr = $this->changeFunArr[$val['name']]['funName'];
                    //���黺��ʵ��������
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
     * ������ʱid��ȡ��ʽid�����ڴ���������ɺ�ı���鿴
     */
    function getObjIdByTempId_d($tempId, $code)
    {
        $changeDao = new model_common_changeLog($code);
        $rows = $changeDao->getObjByTempId($tempId);
        return $rows[0]['objId'];
    }

    /**
     * ���ݹ�����spid��ȡ��������Ϣ
     */
    function getWfInfo_d($spid)
    {
        $otherDao = new model_common_otherdatas();
        return $otherDao->getWorkflowInfo($spid);
    }

    /**
     * ��ȡ����Ĭ������
     */
    function getPersonSelectedSetting_d($gridId = 'auditing')
    {
        $selectsettingDao = new model_common_workflow_selectedsetting();
        return $selectsettingDao->rtUserSelected_d($gridId);
    }

    /**
     * �ж��Ƿ��������·��
     */
    function getSpeUrl_d($formName)
    {
        //����·������
        include(WEB_TOR . "model/common/workflow/workflowSpeConfig.php");
        //��������Ӧ�������
        $speSetArr = isset($speSetArr) ? $speSetArr : null;
        if ($speSetArr) {
            //��������
            $keyArr = array_keys($speSetArr[$formName]);
            if (in_array($_SESSION['USER_ID'], $keyArr)) {
                return $speSetArr[$formName][$_SESSION['USER_ID']];
            }
        }
        return '';
    }

    /**
     * �ж��������Ƿ����������Ϣ
     */
    function isAudited_d($billId, $examCode)
    {
        //��֤ҵ���Ƿ񻹴�������״̬
        $sql = "select ExaStatus from $examCode where id = '$billId'";
        $objArr = $this->_db->getArray($sql);
        if ($objArr[0]['ExaStatus'] != AUDITING) {
            return 1;
        }

        //��ȡ��������Ϣ
        $sql = "select task from wf_task where code='$examCode' and Pid='$billId' and Status='ok' ";
        $taskArr = $this->_db->getArray($sql);
        $taskObj = array_pop($taskArr);

        //��ȡ��������Ϣ
        $sql = "SELECT ID FROM flow_step where wf_task_id='" . $taskObj['task'] . "' and ( flag='ok' or status<>'ok' or flag='') ";
        $auditArr = $this->_db->getArray($sql);
        if ($auditArr[0]['ID']) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * �ж��������Ƿ����������Ϣ(��ͬ��)
     */
    function isAuditedContract_d($billId, $examCode)
    {
        //��֤ҵ���Ƿ񻹴�������״̬
        $sql = "select ExaStatus from $examCode where id = '$billId'";
        $objArr = $this->_db->getArray($sql);
        if ($objArr[0]['ExaStatus'] != AUDITING) {
            return 1;
        }

        //��ȡ��������Ϣ
        $sql = "select task from wf_task where code='$examCode' and Pid='$billId' and Status='ok' ";
        $taskArr = $this->_db->getArray($sql);
        $taskObj = array_pop($taskArr);

        //��ȡ��������Ϣ
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

    /********************* �յ��˵�ϵ�� ********************/
    /**
     * �յ�����
     */
    function receiveForm_d($taskId, $formName, $objId)
    {
        if (isset($this->receiveActionArr[$formName])) {
            $modelInfo = $this->receiveActionArr[$formName];
            //			print_r($modelInfo);

            $rs = 1;
            try {
                $this->start_d();

                //���µ�ǰ���յ�״̬
                $this->update(
                    array('task' => $taskId),
                    array('receiveStatus' => 1, 'recevierName' => $_SESSION['USERNAME'], 'recevierId' => $_SESSION['USER_ID'], 'recevieTime' => date('Y-m-d H:i:s'))
                );

                //����ҵ���յ�״̬
                //��ѯҵ����Ϣ
                $newClass = $modelInfo['className'];
                $newAction = $modelInfo['funName'];
                //�˵�����
                $newClassDao = new $newClass();
                $newClassDao->$newAction($objId);

                $this->commit_d();
            } catch (Exception $e) {
                $this->rollBack();
                $rs = 0;
            }

            //����յ��ɹ��������ʼ�
            if ($rs == 1) {
                //��ѯ������Ϣ
                $obj = $newClassDao->find(array('id' => $objId));
                $this->receiveMail_d($obj);
            }
            return $rs;
        } else {
            return -1;
        }
    }

    /**
     * �˵��ʼ�
     */
    function receiveMail_d($obj)
    {
        //����
        $content = "���ã�<br/>" . $_SESSION['USERNAME'] . " �Ѿ�������ı������ţ�$obj[BillNo] �ĵ��ݣ�<br/>��������oaϵͳ�鿴��лл��";
        //����
        $title = "OA-�����յ�֪ͨ:" . $obj['BillNo'];

        $mailDao = new model_common_mail();
        $mailDao->mailClear($title, $obj['InputMan'], $content);
    }

    /**
     * �˵�����
     */
    function backForm_d($taskId, $formName, $objId)
    {
        if (isset($this->backActionArr[$formName])) {
            $modelInfo = $this->backActionArr[$formName];
            //			print_r($modelInfo);

            $rs = 1;
            try {
                $this->start_d();

                //���µ�ǰ���յ�״̬
                $this->update(
                    array('task' => $taskId),
                    array('receiveStatus' => 0, 'recevierName' => '', 'recevierId' => '', 'recevieTime' => date('Y-m-d H:i:s'))
                );

                //����ҵ���յ�״̬
                //��ѯҵ����Ϣ
                $newClass = $modelInfo['className'];
                $newAction = $modelInfo['funName'];
                //�˵�����
                $newClassDao = new $newClass();
                $newClassDao->$newAction($objId);

                $this->commit_d();
            } catch (Exception $e) {
                $this->rollBack();
                $rs = 0;
            }

            //����յ��ɹ��������ʼ�
            if ($rs == 1) {
                //��ѯ������Ϣ
                $obj = $newClassDao->find(array('id' => $objId));
                $this->backMail_d($obj);
            }
            return $rs;
        } else {
            return -1;
        }
    }

    /**
     * �˵��ʼ�
     */
    function backMail_d($obj)
    {
        //����
        $content = "���ã�<br/>" . $_SESSION['USERNAME'] . " �Ѿ��˻���ı������ţ�$obj[BillNo] �ĵ��ݣ�<br/>��������oaϵͳ�鿴��лл��";
        //����
        $title = "OA-�����˵�֪ͨ:" . $obj['BillNo'];

        $mailDao = new model_common_mail();
        $mailDao->mailClear($title, $obj['InputMan'], $content);
    }

    /************************** ������ʷ���� **************************/
    /**
     * ���������ʼ��
     */
    function auditInfo_d($object)
    {
        $rs = $this->rtWorkflowExInfoArr_d();
        if (in_array($_SESSION['USER_ID'], $rs)) {
            foreach ($object as $key => $val) {
                $object[$key]['auditHistory'] = $this->initViewOnlyBack($this->initAuditInfo($val['Pid'], $val['code']), $val['task']);
            }
        } else {
            $object[0]['auditHistory'] = '���ر������ݿ��ܵ����б��ȡ���������迪��������ϵOA����Ա';
        }
        return $object;
    }

    /**
     * �ж��Ƿ��д�صļ�¼
     */
    function hasBackInfo($pid, $code)
    {
        $sql = "select task from wf_task where pid = $pid and code = '$code'";
        $rs = $this->_db->getArray($sql);
        return $rs;
    }

    /**
     * �����������
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
     * ҳ��ƴװ
     */
    function initView($rows, $thisTask)
    {
        //		print_r($rows);
        $str = "";
        if (is_array($rows)) {
            $headStr = "<table  class='form_main_table' style='width:500px'>";
            $headStr .= <<<EOT
                <tr style="color:blue;">
                	<td width="5%">���</td>
                    <td width="15%">������</td>
                    <td width="10%">������</td>
                    <td width="15%">��������</td>
                    <td width="9%">�������</td>
                    <td width="27%">�������</td>
                </tr>
EOT;
            $mark = "";
            $i = 0; //�����κ�
            $x = 0; //�����
            foreach ($rows as $key => $val) {
                if (empty($val['Result'])) continue;

                if ($val['Result'] == "ok")
                    $rsStr = "<font color='green'>ͬ��</font>";
                elseif ($val['Result'] == "no")
                    $rsStr = "<font color='red'>��ͬ��</font>";
                else $rsStr = "δ����";

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
                        $thisTaskStr = '(��������)';
                    } else {
                        $thisTaskStr = '';
                    }
                    $str .= <<<EOT
						<tr class="form_header"><td colspan="6" style="text-align:left">�� $i ������$thisTaskStr</td></tr>
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
     * ҳ��ƴװ-ֻ��ʾ�����Ϣ
     */
    function initViewOnlyBack($rows, $thisTask)
    {
        //		print_r($rows);
        $str = "";
        if (is_array($rows)) {
            $headStr = "<table  class='form_main_table' style='width:500px'>";
            $headStr .= <<<EOT
                <tr style="color:blue;">
                	<td width="5%">���</td>
                    <td width="15%">������</td>
                    <td width="10%">������</td>
                    <td width="15%">��������</td>
                    <td width="9%">�������</td>
                    <td width="27%">�������</td>
                </tr>
EOT;
            $mark = "";
            $i = 0; //�����κ�
            $x = 0; //�����
            foreach ($rows as $key => $val) {
                if (empty($val['Result'])) continue;

                if ($val['Result'] == "ok")
                    $rsStr = "<font color='green'>ͬ��</font>";
                elseif ($val['Result'] == "no")
                    $rsStr = "<font color='red'>��ͬ��</font>";
                else $rsStr = "δ����";

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
                        $thisTaskStr = '(��������)';
                    } else {
                        $thisTaskStr = '';
                    }
                    $str .= <<<EOT
						<tr class="form_header"><td colspan="6" style="text-align:left">�� $i ������$thisTaskStr</td></tr>
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
     * �ж������Ƿ��Ǳ��
     */
    function inChange_d($spid)
    {
        $otherDao = new model_common_otherdatas();
        $rows = $otherDao->getWorkflowInfo($spid);

        //���ڱ���ı�����
        $changeKeyArray = array_keys($this->changeFunArr);

        if (in_array($rows['formName'], $changeKeyArray)) {
            //��֤�Ƿ��Ǻ�ͬ���
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

    /******************* ������������ **********************/
    /**
     * ��ȡ������Ϣ
     */
    function getAuditInfo_d($spids)
    {
        $sql = "select p.ID , p.Wf_task_ID , t.Pid , t.code, t.name , t.Creator , t.objCode ,t.infomation from flow_step_partent p , flow_step s , wf_task t where t.task=p.Wf_task_ID and p.ID in ($spids) and p.Wf_task_ID=s.Wf_task_ID and p.SmallID=s.SmallID and p.Flag!='1'";
        return $this->_db->getArray($sql);
    }

    /**
     * ��ʼ��������Ϣ
     * @param $workflowArr
     * @return null|string
     */
    function initAuditInfo_d($workflowArr)
    {
        $str = null;
        //��Ⱦ������Ϣ
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
							<input type="radio" id="resultYes$id" name="result$id" value="ok" checked="checked" onclick="changeResult($id,'ok');"/><span id="resultYesInfo$id" class="blue">ͨ��</span>
							<input type="radio" id="resultNo$id" name="result$id" value="no" onclick="changeResult($id,'no');"/><span id="resultNoInfo$id">��ͨ��</span>
						</td>
						<td id="contentShow$id">
							<textarea id="content$id"></textarea>
						</td>
						<td id="mailShow$id">
							<input type="checkbox" id="isSend$id" checked="checked" value="y" title="֪ͨ�ύ�˵���������"/>֪ͨ������
							<input type="checkbox" id="isSendNext$id" checked="checked" value="y" title="֪ͨ��һ�������߽�������"/>֪ͨ��һ��������
						</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * ajax ��˵���
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
        //����spid�ж�
        if (!$spid) {
            return '���ݴ���ʧ��';
        }

        //�������Ƿ��Ѿ����
        $isOver = false;
        //��һ��������
        $nextChecker = "";

        //���Ȼ�ȡ������Ϣ
        $sql = "select p.SmallID,p.Wf_task_ID,s.Flow_prop,t.Pid,t.code,t.name,t.Creator,t.DBTable,t.Enter_user
		    from flow_step_partent p , flow_step s , wf_task t
		    where t.task=p.Wf_task_ID and p.ID='$spid' and p.Wf_task_ID=s.Wf_task_ID and p.SmallID=s.SmallID and p.Flag!='1'";
        $workflowArr = $this->_db->getArray($sql);
        $workflowInfo = $workflowArr[0];

        // ���û��ע�ᣬ����ʾ�쳣
        if (!isset($this->batchAuditArr[$workflowInfo['name']])) {
            return "�����쳣��û��ע��������������͡�";
        }
        if ($workflowInfo) {
            //��ʽ������������
            try {
                $this->start_d();

                //��ʱ������;SQL
                $sql = "update wf_task set UpdateDT = now() where Status='ok' and task='" . $workflowInfo['Wf_task_ID'] . "' ";
                $this->_db->query($sql);

                //����������
                $sql = "update flow_step_partent set Flag='1', isMbFlag='3', User='" . $_SESSION['USER_ID'] . "',Result='" . $result . "',Content='" . $content . "',Endtime='" . date('Y-m-d H:i:s') . "' where ID=" . $spid;
                $this->_db->query($sql);

                //�������ͨ�� ���� ������
                if ($result == "ok" || $workflowInfo['Flow_prop'] == "1") {

                    //��ѯ����������������Ƿ����Լ�������ɾ��
                    $sql = "select ID , User from flow_step where SmallID>'" . $workflowInfo['SmallID'] .
                        "' and Wf_task_ID='" . $workflowInfo['Wf_task_ID'] .
                        "' and find_in_set('" . $_SESSION['USER_ID'] . "',User)>0 ORDER BY SmallID";
                    $nextFlowArr = $this->_db->getArray($sql);
                    if(!empty($nextFlowArr)){
                        foreach ($nextFlowArr as $val) {
                            //�������������Լ���������,ɾ��
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

                    //δ֪����
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

                    //��һ������
                    if ($nextFlow === true) {
                        $sql = "update flow_step set Flag='',Endtime=now() where Wf_task_ID='" . $workflowInfo['Wf_task_ID'] . "' and SmallID='" . $workflowInfo['SmallID'] . "'";
                        $this->_db->query($sql);

                        /************* ����޸� 1 ��ʼ select�����Ӳ�ѯ isReceive �� isEditPage�ֶ� *****************/
                        $sql = "select ID,User,Flag ,SmallID,isReceive,isEditPage from flow_step where SmallID>'" . $workflowInfo['SmallID'] .
                            "' and Wf_task_ID='" . $workflowInfo['Wf_task_ID'] . "' ORDER BY SmallID";
                        /************* ����޸� 1 ���� *****************/
                        $nextFlowArr = $this->_db->getArray($sql);

                        //�����������һ������Ϣ������д���
                        if ($nextFlowArr[0]['ID']) {
                            $nextFlowInfo = $nextFlowArr[0];
                            $stepid = $nextFlowInfo["ID"];
                            $nextChecker = $nextFlowInfo["User"];
                            $nextSmallId = $nextFlowInfo["SmallID"];

                            /*************** ����޸� 2 ��ʼ ��� isReceive �� isEditPage�ֶ� ********************/
                            $isReceive = $nextFlowInfo["isReceive"];
                            $isEditPage = $nextFlowInfo["isEditPage"];
                            /************* ����޸� 2 ���� *****************/

                            foreach (explode(",", trim($nextChecker, ',')) as $val) {
                                if ($val != "") {
                                    /*************** ����޸� 3 ��ʼ ������� isReceive �� isEditPage�ֶ� ********************/
                                    $sql = "INSERT into flow_step_partent set StepID='$stepid',SmallID='$nextSmallId',Wf_task_ID='" . $workflowInfo['Wf_task_ID'] . "',User='$val',Flag='0',START=now(),isReceive='$isReceive',isEditPage='$isEditPage' ";
                                    /*************** ����޸� 3 ���� ******************/
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

                            //���������������
                            $isOver = true;
                        }
                    }

                } elseif ($result == "no") {
                    //������������Ϊ����
                    $sql = "update flow_step set Flag='ok',Endtime=now() where Wf_task_ID='" . $workflowInfo['Wf_task_ID'] . "' and SmallID='" . $workflowInfo['SmallID'] . "'";
                    $this->_db->query($sql);

                    //����������Ϊ���
                    $sql = "update wf_task set examines='no' , Status='0' , finish=now() where task='" . $workflowInfo['Wf_task_ID'] . "' and Status='ok' ";
                    $this->_db->query($sql);

                    $sql = "select DisPassSqlCode , name from wf_task where task='" . $workflowInfo['Wf_task_ID'] . "'";
                    $taskArrs = $this->_db->getArray($sql);
                    $taskInfo = $taskArrs[0];
                    if ($taskInfo['DisPassSqlCode'] != "") {
                        $this->_db->query(stripslashes($taskInfo['DisPassSqlCode']));
                    }
                    //���������������
                    $isOver = true;
                }

                //�������ҵ����
                $allStep = isset($this->urlArr[$workflowInfo['name']]['allStep']) ? $this->urlArr[$workflowInfo['name']]['allStep'] : 0;
                if (($allStep || $isOver) && (isset($this->batchAuditArr[$workflowInfo['name']]) && !empty($this->batchAuditArr[$workflowInfo['name']]))) {
                    $newClass = $this->batchAuditArr[$workflowInfo['name']]['className'];
                    $newAction = $this->batchAuditArr[$workflowInfo['name']]['funName'];
                    $newClassDao = new $newClass();
                    if (method_exists($newClassDao, $newAction)) {
                        $newClassDao->$newAction($spid);
                    } else {
                        throw new Exception($newAction . '����������' . $newClass . '��');
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

            //�ʼ�����
            $this->mailSend_d($result, $isOver, $workflowInfo, $isSend, $workflowInfo['Enter_user'], $isSendNext, $nextChecker, $content);

            // ΢����Ϣ����
            $this->WXMsgSend_d($result, $isOver, $workflowInfo, $isSend, $workflowInfo['Enter_user'], $isSendNext, $nextChecker, $content);

            return true;
        } else {
            return '�����Ѿ���ɣ�������';
        }
    }

    /**
     * �ʼ�����
     */
    function mailSend_d($result, $isOver, $workflowInfo, $isSend = 'y', $enterUser, $isSendNext = 'y', $nextChecker, $content = null)
    {
        /************************* �����ʼ��������� ***************************/
        include(WEB_TOR . "model/common/workflow/workflowMailConfig.php"); //���������ļ�
        include(WEB_TOR . "model/common/workflow/workflowMailInit.php"); //�������÷����ļ�
        include(WEB_TOR . "cache/DATADICTARR.cache.php"); //���������ֵ仺��
        $DATADICTARR = isset($DATADICTARR) ? $DATADICTARR : "";
        $workflowMailConfig = isset($workflowMailConfig) ? $workflowMailConfig : "";

        //ʵ�����ʼ���
        $mailDao = new model_common_mail();

        $emailBody = null; //�ʼ�����
        $emailSql = null; //�ʼ���ѯsql
        //��ȡ�ʼ�����
        $taskName = $workflowInfo['code'];
        $pid = $workflowInfo['Pid'];
        $flowName = $workflowInfo['name'];
        $wfCreator = $workflowInfo['Creator'];

        //�����ʼ���Ϣ
        if (!empty($workflowMailConfig[$taskName])) {
            //���������ϸ����
            if ($workflowMailConfig[$taskName]['detailSet']) {

                //���ݶ�ȡ����
                $sql = $workflowMailConfig[$taskName]['selectSql'] . " and c.id = $pid";
                $rows = $this->_db->getArray($sql);

                //ģ��ƴװ
                $thisFun = $workflowMailConfig[$taskName]['actFunc'];
                $emailBody = $thisFun($rows, $DATADICTARR);
            } else { //�����������ϸ����
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

        //�ж��Ƿ��͵�֪ͨ��
        if ($isSend == 'y') {
            $Subject = "OA-������" . $flowName;
            $ebody = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;" . $_SESSION['USERNAME'] . "�Ѿ�����������Ϊ��" . $workflowInfo['Wf_task_ID'] . " , �����ˣ�" . $wfCreator . " �����뵥����������<br />&nbsp;&nbsp;&nbsp;&nbsp;���������";
            if ($result == "no")
                $ebody .= "<span color='red'>��ͨ��</span>";
            else
                $ebody .= "<span color='blue'>ͨ��</span>";
            if (!empty($content)) {
                $ebody .= '<br />&nbsp;&nbsp;&nbsp;&nbsp;���������' . $content;
            }
            if (!empty($emailBody)) {
                $ebody .= '<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;' . $emailBody;
            }
            $mailUser = $wfCreator != $enterUser ? $wfCreator . ',' . $enterUser : $enterUser;
            $mailDao->mailClear($Subject, $mailUser, $ebody);
        }

        //֪ͨ��һ��������
        if ($isSendNext == "y" && $result == "ok" && $isOver == false && $nextChecker) {
            $Subject = "OA-������" . $flowName;
            $ebody = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;���µ���������Ҫ��������
	                <br />&nbsp;&nbsp;&nbsp;&nbsp;�������ţ�" . $workflowInfo['Wf_task_ID'] . "
	                <br />&nbsp;&nbsp;&nbsp;&nbsp;����ʼ���" . $_SESSION["USERNAME"] . "ѡ��������ͣ�";
            if (!empty($content)) {
                $ebody .= '<br />&nbsp;&nbsp;&nbsp;&nbsp;���������' . $content;
            }
            if (!empty($emailBody)) {
                $ebody .= '<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;' . $emailBody;
            }
            $mailDao->mailClear($Subject, $nextChecker, $ebody);
        }
    }

    /**
     * ΢�ŷ��ͷ���
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
        //��ȡ�ʼ�����
        $flowName = $workflowInfo['name'];
        $wfCreator = $workflowInfo['Creator'];

        // ��ȡ������ʾ�㼶
        $otherDatasDao = new model_common_otherdatas();
        $sendWXMsg = $otherDatasDao->getConfig('workflow_send_wx_msg');
        if (!$sendWXMsg) {
            return;
        }

        //�ж��Ƿ��͵�֪ͨ��
        if ($isSend == 'y') {
            // ΢��֪ͨ������
            $resultCN = $result == "no" ? "��ͨ��" : "ͨ��";
            $msg = "���ã�[" . $_SESSION['USERNAME'] . "]������[" . $flowName . "]�����ţ�[" . $workflowInfo['Wf_task_ID'] . "]�������������[" . $resultCN . "]";
            if (!empty($content)) {
                $msg .= '�����������[' . $content . "]��";
            } else {
                $msg .= '��';
            }
            $mailUser = $wfCreator != $enterUser ? $wfCreator . ',' . $enterUser : $enterUser;
            $userArr = array_unique(explode(',', $mailUser));
            foreach ($userArr as $v) {
                if ($v) {
                    // ��aws��ȡ��Ʊ��������
                    util_curlUtil::getDataFromAWS('mobliemiro', 'WechatSendMsgAslp', array(
                        "userid" => $v, 'msg' => $msg
                    ), array(), true, 'com.youngheart.apps.');
                }
            }
        }

        //֪ͨ��һ��������
        if ($isSendNext == "y" && $result == "ok" && $isOver == false && $nextChecker) {
            // ΢��֪ͨ��һ������
            $msg = "���ã�����[" . $flowName . "]�����ţ�[" . $workflowInfo['Wf_task_ID'] .
                "]�������������Ĵ�������������¼OAϵͳ��΢���ֻ��˽���������";
            if (!empty($content)) {
                $msg .= '��һ�����������������' . $content;
            }

            $userArr = array_unique(explode(',', $nextChecker));
            foreach ($userArr as $v) {
                if ($v) {
                    // ��aws��ȡ��Ʊ��������
                    util_curlUtil::getDataFromAWS('mobliemiro', 'WechatSendMsgAslp', array(
                        "userid" => $v, 'msg' => $msg
                    ), array(), true, 'com.youngheart.apps.');
                }
            }
        }
    }

    /**
     * ��ȡ���ݵ����������б�
     * @param $pid
     * @param $code
     * @param int $isChange
     * @param int $isPrint
     * @return array
     */
    function getBoAuditList_d($pid, $code, $isChange = 0, $isPrint = 0)
    {
        // ������������
        $rows = $this->getBoAuditInfo_d($pid, $code, $isPrint);

        // ������ݻ�ȡ
        $changeRows = "";

        if ($isChange) {
            $lastChangeInfo = $this->get_one("select max(id) as id from " . $code . " where originalId = " . $pid .
                " AND isTemp = 1 AND ExaStatus IN('��������','���')");
            if ($lastChangeInfo) {
                $rows = $this->getBoAuditInfo_d($lastChangeInfo['id'], $code, $isPrint);
            }
        }

        return array("app" => $rows, "change" => $changeRows);
    }

    /**
     * ��ȡĳҵ�����������
     * @param $pid
     * @param $code
     * @param int $isPrint
     * @return array|bool
     */
    function getBoAuditInfo_d($pid, $code, $isPrint = 0)
    {
        // �����������ݻ�ȡ
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
     * ��ȡͳ������
     * @param $formName
     * @return array|bool
     */
    function getCategoryList_d($formName)
    {
        // �����������ݻ�ȡ
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
     * ��ȡͳ������
     * @param $formName
     * @return array|bool
     */
    function getAuditedCategoryList_d($formName)
    {
        // ��һ���ʱ��ڵ�
        $prev1YearTS = strtotime("-1 year");

        // �����������ݻ�ȡ
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

        // ��������
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

    /* ======================================== ������ҳ�棨��ʼ�� ======================================== */
    // ���Է���
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
     * ��ȡ����������
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
     * ��������Ҫ�����յĸ���ʼֵ
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
                    $backArr[$ok] = ($ov != '') ? $ov : ""; //�������ݱ�
                    break;
                case 'proId':
                    $backArr[$ok] = ($ov != '') ? $ov : "";
                    break;
                case 'billId':
                    $backArr[$ok] = ($ov != '') ? $ov : ''; //����������ID
                    break;
                case 'billArea':
                    $backArr[$ok] = ($ov != '') ? $ov : '';
                    break;
                case 'billDept':
                    $backArr[$ok] = ($ov != '') ? $ov : ''; //������������������ -- ������
                    break;
                case 'cktype':
                    $backArr[$ok] = ($ov != '') ? $ov : "";
                    break;
                case 'examTb':
                    $backArr[$ok] = ($ov != '') ? $ov : "";
                    break;
                case 'FLOW_ID':
                    $backArr[$ok] = ($ov != '') ? $ov : ""; //������ID
                    break;
                case 'formName':
                    $backArr[$ok] = ($ov != '') ? $ov : ""; //������������
                    break;
                case 'flowType':
                    $backArr[$ok] = ($ov != '') ? $ov : ""; //����������
                    break;
                case 'flowMoney':
                    $backArr[$ok] = ($ov != '') ? $ov : "0"; //�������������
                    break;
                default:
                    $backArr[$ok] = $ov;
                    break;
            }
        }
        return $backArr;
    }

    /* ======= ������������ʱ��ҳ����Ϣ�����߼�(��ʼ) ======= */
    /**
     * ��ȡ��������̻�ȡ
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
            // �ȼ�����,�жϴ������Ƿ������ڴ˴��������Ŀ
            if ($wfv['filtingSql'] && $wfv['filtingSql'] != '') {
                $sql = $wfv['filtingSql'];
                foreach ($obj as $k => $v) {
                    $sql = str_replace('$' . $k, $v, $sql);
                }
                $chkResult = $this->_db->getArray($sql);
                $need = $chkResult ? 0 : 1; // �����ѯ�����,����˵���������
            } else if ($wfv['filtingClass'] && $wfv['filtingClass'] != '' && $wfv['filtingFun'] && $wfv['filtingFun'] != '') {
                try {
                    if (class_exists($wfv['filtingClass'])) {
                        if (in_array($wfv['filtingFun'], get_class_methods($wfv['filtingClass']))) {
                            // ��ʼִ�з�������ȡ��Ӧ��������
                            $exeFun = $wfv['filtingFun'];
                            $exeDao = new $wfv['filtingClass'];
                            $chkResult = $exeDao->$exeFun($obj); // �Զ���ĺ�������boolean����ʾ���������˽ڵ���
                            $need = $chkResult ? 0 : 1; // �����ѯ�����,����˵���������
                        }
                    }
                } catch (exception $e) {
                    echo "���������˺�������ʧ��: " . $e->getMessage();
                }
            }

            // ��������������֤,���������������,�򷵻ص�ǰ��
            if ($need) {
                $arr['flowName'] = $wfv['FLOW_NAME'];
                $arr['flowId'] = $wfv['FLOW_ID'];
                $backArr[] = $arr;
            }
        }
        return $backArr;
    }

    /**
     * ��ȡ���̲���
     * @param $obj
     * @return string
     **/
    function getProcess_d($obj)
    {
        $processObj = array(); // ����ʵ������
        $flow_id = isset($obj['flowId']) ? $obj['flowId'] : '';
        $sql = <<<EOT
        SELECT
            p.ID AS stepId,
            p.PRCS_NAME AS stepName,
            t.FLOW_NAME AS flowName,
            CASE p.PRCS_PROP
                WHEN 0 THEN '���'
                WHEN 1 THEN '��ǩ'
                WHEN 2 THEN '��ǩ'
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
        // ������ȡ�����˵Ĵ���
        $stepsArr = $this->selectStepUsers($obj, $stepsArr);
        // �ڵ����
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
     * (����������ҳ����Ҫ����)
     * �����˴���,�����˶�ȡ���ȼ��ɸߵ��ͣ�������->���������->�ű�->���룩
     *
     * @param $obj
     * @param $processArr
     * @return mixed
     */
    function selectStepUsers($obj, $processArr)
    {
        // ǰ̨����,��ȡ���������
        foreach ($obj as $ok => $ov) {
            if (is_array($ov)) {
                unset($obj[$ok]);
            }
        }

        $stepUserIds = "";
        // �ض���ÿ�����̵�������
        foreach ($processArr as $k => $v) {
            $stepUserStr = $v['stepUser'];
            // ���Զ�����������ˡ�û�л�ȡ��Ĭ��������, ������������������, ��ȡ���������˶�Ӧ������
            if ($stepUserStr == '' && $v['PRCS_SPEC'] != '') {
                $specialUsers = $this->getSpecialAuditor($v['PRCS_SPEC'],$v['SPEC_TYPE'],$obj);
                $specialUsers = rtrim($specialUsers, ',');
                $sql1 = "select GROUP_CONCAT(USER_NAME) as stepUsers from user where USER_ID IN ({$specialUsers})";
                $usersArr = $this->_db->getArray($sql1);
                $stepUserStr = (!empty($usersArr)) ? $usersArr[0]['stepUsers'] : '';
                $stepUserIds = (!empty($usersArr)) ? str_replace("'","",$specialUsers) : '';
            }

            // ���Զ���SQL��䡿û��ȡ����������ˣ������õ�ִ�нű����ڵĻ�����ִ�нű���ȡ������
            if ($stepUserStr == '' && $v['executorSearchSql'] != '') { //ͨ���ض��ű���ȡ������
                $exeSql = $v['executorSearchSql'];
                $hasVal = 0;
                foreach ($obj as $ok => $ov) {
                    // ���������ֶ����д������������ģ��滻��Ӧ��ֵ
                    $exeSql = str_replace('$' . $ok, $ov, $exeSql);
                }
                $usersArr = $this->_db->getArray($exeSql);
//                $processArr[$k]['executorSearchSql'] = $exeSql;
                $stepUserStr = (!empty($usersArr)) ? $usersArr[0]['stepUsers'] : '';
            }

            // ���Զ��庯��������Ķ�û�鵽��Ӧ�İ����ˣ������õ�ִ����ͷ����ִ��ڵĻ�����ִ�ж�Ӧ������ȡ������
            if ($stepUserStr == '' && $v['executorSearchClass'] != '' && $v['executorSearchFun'] != '') {
                if (class_exists($v['executorSearchClass'])) {
                    if (in_array($v['executorSearchFun'], get_class_methods($v['executorSearchClass']))) {
                        // ��ʼִ�з�������ȡ��Ӧ��������
                        $exeFun = $v['executorSearchFun'];
                        $exeDao = new $v['executorSearchClass']();
                        $backArr = $exeDao->$exeFun($obj); // �Զ���ĺ�������һ��Ҫ����һ���ֶ���ΪstepUsers���������ַ������Ĳ���
                        if (isset($backArr['stepUsers']) && $backArr['stepUsers'] != '') {
                            $stepUserStr = $backArr['stepUsers'];
                        }
                    }
                }
            }
            $processArr[$k]['stepUser'] = $stepUserStr;
            $processArr[$k]['stepUserIds'] = $stepUserIds;

            // ȥ��������ѡ�����ز���
            unset($processArr[$k]['executorSearchSql']);
            unset($processArr[$k]['executorSearchClass']);
            unset($processArr[$k]['executorSearchFun']);

        }
        return $processArr;
    }

    /**
     * ������ѡ��������˹��򷵻ض�Ӧ�İ�����ID
     *
     * @param $code
     * @param $type
     * @param $extInfo (ǰ��URL��GET���Ĳ���)
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
                case in_array('@bmfz',$codeObj):// ���Ÿ���/�ܾ���
                    if(isset($extInfo['billDept']) && $extInfo['billDept'] != ''){
                        $sql = "select a.ViceManager from department a where a.DEPT_ID='{$extInfo['billDept']}';";
                        $vManagerArr = $this->_db->getArray($sql);
                        if($vManagerArr){
                            foreach ($vManagerArr as $v){
                                $userIds .= ($v['ViceManager'] == "")? "" : rtrim($v['ViceManager'], ',').",";
                            }
                        }else{
                            $sql = "select u.USER_ID from user u, user_priv p where u.USER_PRIV=p.USER_PRIV and p.PRIV_NAME='�ܾ���'";
                            $vManagerArr = $this->_db->getArray($sql);
                            foreach ($vManagerArr as $v){
                                $userIds = rtrim($userIds, ',');
                                $userIds .= ($v['USER_ID'] == "")? "" : rtrim($v['USER_ID'], ',').",";
                            }
                        }
                    }
                    break;
                case in_array('@bmfzj',$codeObj):// ���Ÿ��ܼ�
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
                case in_array('@qyjl',$codeObj):// ������
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
                case in_array('@xmjl',$codeObj):// ��Ŀ����
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
                case in_array('@wgcqy',$codeObj):// ���̰��´�����
                    //����������´�����
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
     * �������õĽű���������Ӧ�ڵ�����̽��й���
     * @param $obj
     * @param $processArr
     * @return mixed
     */
    function decisionFilter($obj, $processArr)
    {
        $backArr = array();
        foreach ($processArr as $k => $v) {
            $result = '';
            // ��ִ�нű�
            if ($v['decisionSql']) {
                $exeSql = $v['decisionSql'];
                foreach ($obj as $ok => $ov) {
                    $exeSql = str_replace('$' . $ok, $ov, $exeSql);
                }
                $processArr[$k]['decisionSql'] = $exeSql;
                $resultArr = $this->_db->getArray($exeSql);
                $result = empty($resultArr) ? '' : '1';
            }
            // �ű�ִ�н��Ϊ�գ�����������ͺ����ģ�ִ�к���
            if ($result == '' && $v['decisionClass'] && $v['decisionFun']) { // ��ִ�нű�
                if (class_exists($v['decisionClass'])) {
                    if (in_array($v['decisionFun'], get_class_methods($v['decisionClass']))) {
                        // ��ʼִ�з�������ȡ��Ӧ��������
                        $exeFun = $v['decisionFun'];
                        $exeDao = new $v['decisionClass']();
                        $chkResult = $exeDao->$exeFun($obj); // �Զ���ĺ�������boolean����ʾ���������˽ڵ���
                        $result = $chkResult ? '1' : '';
                    }
                }
            }

            // ������϶�û�鵽��Ӧ�Ĺ��������򷵻ظ�����
            if ($result == '') {
                // ȥ���ڵ���˵���ز���
                unset($processArr[$k]['decisionSql']);
                unset($processArr[$k]['decisionClass']);
                unset($processArr[$k]['decisionFun']);
                array_push($backArr, $processArr[$k]);
            }
        }
        return $backArr;
    }

    /**
     * ������Ӧ������������
     * @param $obj
     * @return mixed
     */
    function joinFormData($obj)
    {
        if (isset($obj['formName']) && !empty($obj['formName'])) {
            $sql = "select * from flow_form_type where FORM_NAME = '{$obj['formName']}';";
            $formData = $this->_db->get_one($sql);
            if ($formData) {
                // ��ǰ�˴�����������ˢһ�ζ�Ӧ�����������ַ���,���ж�Ӧ���ַ�,���滻����Ӧ��ֵ
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
                $formData['PASS_SQL'] = ($formData['PASS_SQL'] != '') ? $formData['PASS_SQL'] : "update {$exeTb} set ExaStatus = '���',ExaDT = now() where id='{$billId}' "; //������ɺ�������
                $formData['DISPASS_SQL'] = ($formData['DISPASS_SQL'] != '') ? $formData['DISPASS_SQL'] : "update {$exeTb} set ExaStatus = '���',ExaDT = now() where id='{$billId}'"; //������ظ������
                $obj['formData'] = $formData;
            }
        }
        return $obj;
    }

    /* ======= ������������ʱ��ҳ����Ϣ�����߼�(����) ======= */

    /* ======= ���ܲ������ύ����������(��ʼ) ======= */
    function saveAuditApply_d($obj, $sendToURL)
    {
//        echo"<pre>";print_r($obj);exit();
        $resultArr = array();
        $resultArr['msg'] = '';
        $resultArr['data'] = array();
        $formData = (isset($obj['formData']) && is_array($obj['formData'])) ? $obj['formData'] : array(); // ��������ر���Ϣ
        $processSteps = (isset($obj['steps']) && is_array($obj['steps'])) ? $obj['steps'] : array(); // �����������Ĵ���������Ϣ

        // �ȼ������������Ϣ�Ƿ�������start��
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
            $resultArr['data']['result'] = '�������̲�����Ϣ����!';
            return $resultArr;
            exit();
        }
        // �ȼ������������Ϣ�Ƿ�������end��

        try {
            $this->start_d();
            $information = $objCode = $objName = $objCustomer = $objAmount = $sql = "";
            /*********** -��ʼ- ��ȡinfomation��չ�ֶ�ֵ**************/
            if (isset($formData['infomation']) && $formData['infomation'] != '') { // ����Ҫ���ֵ�ҵ����Ϣ
                $information = $formData['infomation'];
                // ִ�нű�����ѯ��ص�ժҪ��Ϣ
                if (isset($formData['infomationSql']) && $formData['infomationSql'] != '') {
                    $searchInfoSql = stripslashes(stripslashes($formData['infomationSql']));
                    $informationArr = $this->_db->getArray($searchInfoSql);
                    $informationArr = ($informationArr && !empty($informationArr)) ? $informationArr[0] : '';

                    //������ݴ��ڣ����������ݣ����򱨴�
                    if (is_array($informationArr)) {
                        foreach ($informationArr as $key => $val) {
                            $information = str_replace('$' . $key, $val, $information);
                        }
                    } else {
                        throw new Exception("�����������ݲ����ڣ�");
                    }
                }

                // ִ��������ͺ�����ȡ��Ӧ��ժҪ��Ϣ
                if (isset($formData['infomationClass']) && $formData['infomationClass'] != '' && isset($formData['infomationFun']) && $formData['infomationFun'] != '') {
                    $model = $formData['infomationClass'];
                    $action = $formData['infomationFun'];
                    if (class_exists($model)) {
                        if (in_array($action, get_class_methods($model))) {
                            // ��ʼִ�з�������ȡ��Ӧ�ĸ�����Ϣ
                            $exeDao = new $model();
                            $infoArr = $exeDao->$action($obj);
                            //������ݴ��ڣ����������ݣ����򱨴�
                            if (is_array($infoArr)) {
                                foreach ($infoArr as $key => $val) {
                                    $information = str_replace('$' . $key, $val, $information);
                                }
                            } else {
                                throw new Exception("�����������ݲ����ڣ�");
                            }
                        } else {
                            throw new Exception("���࡮{$model}���в����ڷ�����{$action}����");
                        }
                    } else {
                        throw new Exception("������{$model}�����ڣ�");
                    }
                }

            }
            /************ -����- ��ȡinfomation��չ�ֶ�ֵ**************/

            // ����Ƿ��Ѿ����ڴ����������Լ�BillId�µ�������
            $exitTaskChkSql = "select task from wf_task where name='{$obj['formName']}' and code='{$obj['examCode']}' and Pid='{$obj['billId']}' and Status='ok';"; // and DBTable='".$_SESSION["COM_BRN_SQL"]."' //���ֶ���ʱ�������;
            $exitTaskChk = $this->_db->getArray($exitTaskChkSql);
            if (is_array($exitTaskChk) && !empty($exitTaskChk)) {
                throw new Exception("���������Ѿ�����ͬ����Ϊ��{$obj['formName']} ����������");
            }

            //������Ϣ ������ �������
            $updateExCodeSql = "update {$obj['examCode']} set ExaStatus = '��������' , ExaDT = now() where ID='{$obj['billId']}' ";
            $this->query($updateExCodeSql);
            $selectFlTypeSql = "select ClassID,FORM_ID, FLOW_NAME from flow_type where FLOW_ID='{$obj['flowId']}'";
            $flowTypeArr = $this->_db->getArray($selectFlTypeSql);
            $form = 0;
            if (is_array($flowTypeArr[0]) && !empty($flowTypeArr[0])) {
                $sql = "update wf_class set Ccount=Ccount+1 where class_id=" . $flowTypeArr[0]["ClassID"];
                //echo $sql;br();//������
                $form = $flowTypeArr[0]["FORM_ID"];
                $this->query($sql);
            }

            // ҵ����Ա��
            if (isset($obj['objUser']) && !empty($obj['objUser'])) {
                $wfu = $obj['objUser'];
                $wfun = $this->getUserByUid($obj['objUser']);
            } else {
                $wfu = $_SESSION['USER_ID'];
                $wfun = $_SESSION['USER_NAME'];
            }

            // ��������������Ϣ
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
//            $resultArr['data']['addMainArr'] = $addMainArr;$taskid = '123';//������
            $taskid = $this->add_d($addMainArr);

            // ÿ������������Ӧ�����������¼
            $firstChecker = "";
            if (!empty($processSteps)) {
                $Flag = 0;
                $Smallval = 1;
                $stepTotals = count($processSteps);
                $stepNum = 0;
//                $resultArr['data']['flowStepArr'] = array();//������
                $tbName = $this->tbl_name;
                $this->tbl_name = 'flow_step'; //����flow_step��������������
                foreach ($processSteps as $stepK => $stepV) {
                    // ��ȡ������������Ϣ
                    $getSQL = "select * from flow_process where ID = '{$stepV[ID]}'";
                    $stepData = $this->get_one($getSQL);

                    // �������̸��������ֻ�ȡ��ӦID
                    $userIdArr = $this->_db->getArray("select GROUP_CONCAT(USER_ID) as userIds from user where FIND_IN_SET(USER_NAME,'{$stepV['stepUser']}') > 0;");
                    $AC_Users = ($userIdArr && !empty($userIdArr)) ? $userIdArr[0]['userIds'] : '';
                    // ��ϴ������̾�������
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
//                    $resultArr['data']['flowStepArr'][] = $flowStepArr;//������

                    $stepid = $this->add_d($flowStepArr);
                    if (!$stepid)
                        throw new Exception("dberror");
                    if ($Smallval == 1) {
                        //�����̴��������һ����ذ����˵ļ�¼
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
                $this->tbl_name = $tbName; //�������ݺ󻻻�ԭ������
            }

            // ���ݼ������������ݴ���
            if ($obj['formName'] == '���ݼ�') {
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
                    $mainData['�����ˣ�'] = $data[0]['user_name'];
                    $mainData['������ͣ�'] = $data[0]['type'];
                    $mainData['��ʼ���ڣ�'] = $data[0]['begindt'];
                    $mainData['��ֹ���ڣ�'] = $data[0]['enddt'];
                    $mainData['����ԭ��'] = $data[0]['reason'];
                }
            }

            // �����ʼ�
            if ($obj["isSendNotify"] == "y" && $firstChecker != "") {
                $TO_ID = $firstChecker;
                $Subject = "OA-������{$obj['formName']}";
                $ebody = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;�����µ���������Ҫ������<br />&nbsp;&nbsp;&nbsp;&nbsp;�������ţ�" . $taskid . "<br />&nbsp;&nbsp;&nbsp;&nbsp;����ʼ���" . $_SESSION["USERNAME"] . "ѡ��������ͣ�";
                if ($obj['formName'] == '���ݼ�') {
                    $ebody .= "<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������£�";
                    foreach ($mainData as $key => $val) {
                        $ebody .= '<br /> &nbsp;&nbsp;&nbsp;&nbsp;' . $key . ' ' . $val;
                    }
                }
                if (!empty($information)) {
                    $ebody .= '<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;' . $information;
                }
                $emailDao = new model_common_mail();
                $emailDao->mailClear($Subject, $TO_ID, $ebody);
            }
            $this->commit_d();
        } catch (exception $e) {
            $this->rollBack();
            $reason = "�ύʧ�ܣ�" . $e->getMessage();
            $resultArr['data']['error'] = 1;
            $resultArr['data']['result'] = $reason;
        }

        // ��������,����������
        if (isset($resultArr['data']['error']) && $resultArr['data']['error'] > 0) {
            return $resultArr;
        } else {
            //�ύ�ɹ��󷵻�ָ��·��
            $resultArr['msg'] = 'ok';
            $resultArr['data']['error'] = 0;
            $resultArr['data']['result'] = '�ύ�ɹ�!';
            $resultArr['data']['sendToURL'] = ($sendToURL == '') ? '' : str_replace(" ", "", $sendToURL);
            return $resultArr;
        }
        return $resultArr;
    }
    /* ======= ���ܲ������ύ����������(����) ======= */

    /* ======================================== ������ҳ�棨������ ======================================== */

    /* ======================================== ������������ҳ�棨��ʼ�� ======================================== */
    /**
     * �����û�ID��ȡ�û���
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
     * ����FormId��ȡ��������
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
                WHEN 1 THEN '�̶�����'
                WHEN 2 THEN '��������'
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
     * ����FormIdͳ��������������
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
     * ��ȡ�������
     * @return array|bool
     */
    function getWfClass()
    {
        $sql = "select *  from wf_class order by class_id;";
        $data = $this->_db->getArray($sql);
        return $data;
    }
    /* ======================================== ������������ҳ�棨������ ======================================== */
    /**
     * �����������
     */
    function viewMobile_d($pid, $code)
    {
        // ��ѯ������¼
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

        // ��������
        $data = array();

        // ������������
        if (!empty($rs)) {
            // ʵ������ѯ��
            $otherDataDao = new model_common_otherdatas();

            foreach ($rs as $v) {
                // �����û��������Ȼ�����������Ϣ
                if (!isset($data[$v['task']])) {
                    $data[$v['task']] = array(
                        'name' => $v['name'],
                        'start' => $v['start'],
                        'steps' => array(
                        )
                    );
                }
                // ������������
                $data[$v['task']]['steps'][] = array(
                    'stepName' => $v['Item'],
                    'result' => $v['Result'],
                    'content' => $v['Content'] ? $v['Content'] : '��',
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
     * ��ȡ���������м�¼��������������
     * @return array
     */
    function getAuditingIncludesFormName(){
        $inNames = $this->rtWorkflowStr_d();
        $inNames = str_replace(",","','",$inNames);
        $findInName = $_SESSION['USER_ID'];
        $chkSql = <<<EOT
        select t.name from(
select p.ID as id,c.task,c.infomation,c.name,c.name as orgName,c.code,c.start,c.Creator,c.Pid ,
		if(u.has_left='1', concat(u.USER_NAME,'����ְ��') , u.USER_NAME ) as creatorName,p.isReceive,p.isEditPage,c.receiveStatus,c.recevierId,c.recevierName,c.recevieTime,
		'' as thisAction,c.receiveStatus as receiveStatusAction,c.objCode,c.objName,c.objCustomer,c.objAmount,c.objUser,c.objUserName,c.projectCode,c.isImptSubsidy,
		if(c.isImptSubsidy = '��', '����', if(l.id is null,'-',if((l.allregisterId is null or l.allregisterId <= 0),'���˴���','�⳵'))) as costSourceType
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
     * ��ȡ�Ѱ������м�¼��������������
     * @return array
     */
    function getAuditedIncludesFormName(){
        $inNames = $this->rtWorkflowStr_d();
        $inNames = str_replace(",","','",$inNames);
        $findInName = $_SESSION['USER_ID'];
        $chkSql = <<<EOT
        select t.name from(
select p.ID as id,c.task,c.infomation,c.name,c.name as orgName,c.code,c.start,c.Creator,c.Pid ,
		if(u.has_left='1', concat(u.USER_NAME,'����ְ��') , u.USER_NAME ) as creatorName,p.isReceive,p.isEditPage,c.receiveStatus,c.recevierId,c.recevierName,c.recevieTime,
		'' as thisAction,c.receiveStatus as receiveStatusAction,c.objCode,c.objName,c.objCustomer,c.objAmount,c.objUser,c.objUserName,c.projectCode,c.isImptSubsidy,
		if(c.isImptSubsidy = '��', '����', if(l.id is null,'-',if((l.allregisterId is null or l.allregisterId <= 0),'���˴���','�⳵'))) as costSourceType
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