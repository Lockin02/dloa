<?php

/*��������鿴
 * Created on 2011-7-20
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_common_approvalView extends controller_base_action
{

    function __construct()
    {
        $this->objName = "approvalView";
        $this->objPath = "common";
        parent:: __construct();
    }

    /*��ȡ����������*/
    function c_checkHasAppAction()
    {
        $pid = isset ($_POST['pid']) ? addslashes($_POST['pid']) : "";
        $itemtype = isset ($_POST['itemtype']) ? addslashes($_POST['itemtype']) : "";
        $task = $this->service->getLastTask($pid, $itemtype);

        if ($task) {
            echo $this->service->hasDoneStep($task) ? 1 : 0;
        } else {
            echo 0;
        }
    }

    /*��ȡ�������*/
    function c_getApproval()
    {
        $pid = isset ($_POST['pid']) ? addslashes($_POST['pid']) : "";
        $itemtype = isset ($_POST['itemtype']) ? addslashes($_POST['itemtype']) : "";
        $isChange = isset ($_POST['isChange']) ? addslashes($_POST['isChange']) : "";
        $isPrint = isset ($_POST['isPrint']) ? addslashes($_POST['isPrint']) : "";
        $changeContent = isset ($_POST['changeContent']) ? addslashes($_POST['changeContent']) : "";// �Ƿ�ɱ༭���������ʶ
        $rows = $this->service->getApproval_d($pid, $itemtype, $isChange, $isPrint);

        if ($isPrint) {
            if ($changeContent) {
                $string = $this->service->getResultNew_d($rows['app']); //�����ɱ༭�������
            } else {
                $string = $this->service->getResult_d($rows['app']); //�����������
            }
        } else {
            $taskArr = $this->service->findTaskArr($pid, $itemtype);
            $string = $this->service->getResultAll_d($rows['app'], $taskArr); //�����������
        }

        echo $string;
    }

    /*��ȡ����������¼HTML�ַ���*/
    function c_getXqApproval()
    {
        $relDocId = isset ($_POST['relDocId']) ? addslashes($_POST['relDocId']) : "";

        //����ӿڻ�ȡ����
        $xqApproval = $this->service->getXqApprovalJson($relDocId);
        $xqApproval['relDocId'] = $relDocId;
        $xqApprovalRecord = $xqApproval['reCord'];

        $string = '';
        for ($i = 0; $i < count($xqApprovalRecord); $i++) {
            $num = $i + 1;
            $string .= <<<EOT
				<tr >
					<td>{$num}</td>
					<td>{$xqApprovalRecord[$i]['step']}</td>
					<td>{$xqApprovalRecord[$i]['allowMan']}</td>
					<td>{$xqApprovalRecord[$i]['allowTime']}</td>
					<td>{$xqApprovalRecord[$i]['allowResult']}</td>
					<td>{$xqApprovalRecord[$i]['allowMsg']}</td>
				</tr>
EOT;
        }

        echo $string;
    }

    //���һ�α���������
    function c_changeGetApproval()
    {
        $pid = isset ($_POST['pid']) ? addslashes($_POST['pid']) : "";
        $itemtype = isset ($_POST['itemtype']) ? addslashes($_POST['itemtype']) : "";
        $isChange = isset ($_POST['isChange']) ? addslashes($_POST['isChange']) : "";
        $rows = $this->service->getApproval_d($pid, $itemtype, $isChange);
        //			echo "<pre>";
        //			print_R($rows['app']);

        $changeString = $this->service->changeGetResult_d($rows['change']); //���һ�α���������

        echo $changeString;
    }

    //���б������
    function c_getApprovalAll()
    {
        $pid = isset ($_POST['pid']) ? addslashes($_POST['pid']) : "";
        $itemtype = isset ($_POST['itemtype']) ? addslashes($_POST['itemtype']) : "";
        $isChange = isset ($_POST['isChange']) ? addslashes($_POST['isChange']) : "";
        $isPrint = isset ($_POST['isPrint']) ? addslashes($_POST['isPrint']) : "";

        if ($itemtype == "loan") {
            $sql = "select  w.name from wf_task w left join flow_step f  on (w.task = f.wf_task_ID)  where w.code ='$pid' ";
        } else {
            $sql = "select  w.name from wf_task w left join flow_step f  on (w.task = f.wf_task_ID)  where w.Pid ='$pid' and w.code='$itemtype' ";
        }

        $flagArr = $this->service->_db->getArray($sql);
        if ($flagArr[0]['name'] == '��ͬ�������A' || $flagArr[0]['name'] == '��ͬ�������B' || $flagArr[0]['name'] == '��ͬ�������') {
            $findSql = "select originalId from oa_contract_contract where id = '$pid'";
            $oldArr = $this->service->_db->getArray($findSql);
            $cId = $oldArr[0]['originalId'];
        } else {
            $cId = $pid;
        }
        $rows = $this->service->getApproval_d($cId, $itemtype, $isChange, $isPrint);
        $taskArr = $this->service->findTaskArr($cId, $itemtype);
        $string = $this->service->getResultAll_d($rows['app'], $taskArr); //�����������

        echo $string;
    }

    function c_changeGetApprovalAll()
    {
        $pid = isset ($_POST['pid']) ? addslashes($_POST['pid']) : "";
        $itemtype = isset ($_POST['itemtype']) ? addslashes($_POST['itemtype']) : "";
        if ($itemtype == "loan") {
            $sql = "select  w.name from wf_task w left join flow_step f  on (w.task = f.wf_task_ID)  where w.code ='$pid' ";
        } else {
            $sql = "select  w.name from wf_task w left join flow_step f  on (w.task = f.wf_task_ID)  where w.Pid ='$pid' and w.code='$itemtype' ";
        }

        $flagArr = $this->service->_db->getArray($sql);
        if ($flagArr[0]['name'] == '��ͬ�������A' || $flagArr[0]['name'] == '��ͬ�������B' || $flagArr[0]['name'] == '��ͬ�������') {
            $findSql = "select originalId from oa_contract_contract where id = '$pid'";
            $oldArr = $this->service->_db->getArray($findSql);
            $cId = $oldArr[0]['originalId'] ? $oldArr[0]['originalId'] : $pid;
        } else {
            $cId = $pid;
        }

        $rows = $this->service->getApprovalAll_d($cId, $itemtype);
        if($itemtype == "oa_borrow_borrow"){// ����ǽ����õ�,������Ͻ������������
            $sql = "select id from oa_borrow_equ_link where isTemp = 0 and borrowId = '{$cId}';";
            $linkArr = $this->service->_db->getArray($sql);
            if($linkArr){
                $linkId = $linkArr[0]['id'];
                $linkRows = $this->service->getApprovalAll_d($linkId, "oa_borrow_equ_link");
                $catchArr = array();
                if($linkRows && count($linkRows) > 0){
                    $rows = array_merge($rows,$linkRows);
                    foreach ($rows as $key => $val){
                        if(!empty($val[0])){
                            $taskNum = $val[0]['task'];
                            $catchArr[$taskNum] = $val;
                        }
                    }
                    ksort($catchArr);
                    $rows = $catchArr;
                }
            }
        }

        $changeString = $this->service->changeGetResultAll_d($rows); //���һ�α���������

        echo $changeString;
    }
}