<?php

/*审批意见查看
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

    /*获取已审批个数*/
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

    /*获取审批意见*/
    function c_getApproval()
    {
        $pid = isset ($_POST['pid']) ? addslashes($_POST['pid']) : "";
        $itemtype = isset ($_POST['itemtype']) ? addslashes($_POST['itemtype']) : "";
        $isChange = isset ($_POST['isChange']) ? addslashes($_POST['isChange']) : "";
        $isPrint = isset ($_POST['isPrint']) ? addslashes($_POST['isPrint']) : "";
        $changeContent = isset ($_POST['changeContent']) ? addslashes($_POST['changeContent']) : "";// 是否可编辑审批意见标识
        $rows = $this->service->getApproval_d($pid, $itemtype, $isChange, $isPrint);

        if ($isPrint) {
            if ($changeContent) {
                $string = $this->service->getResultNew_d($rows['app']); //新增可编辑审批意见
            } else {
                $string = $this->service->getResult_d($rows['app']); //新增审批意见
            }
        } else {
            $taskArr = $this->service->findTaskArr($pid, $itemtype);
            $string = $this->service->getResultAll_d($rows['app'], $taskArr); //新增审批意见
        }

        echo $string;
    }

    /*获取需求审批记录HTML字符串*/
    function c_getXqApproval()
    {
        $relDocId = isset ($_POST['relDocId']) ? addslashes($_POST['relDocId']) : "";

        //请求接口获取数据
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

    //最近一次变更审批意见
    function c_changeGetApproval()
    {
        $pid = isset ($_POST['pid']) ? addslashes($_POST['pid']) : "";
        $itemtype = isset ($_POST['itemtype']) ? addslashes($_POST['itemtype']) : "";
        $isChange = isset ($_POST['isChange']) ? addslashes($_POST['isChange']) : "";
        $rows = $this->service->getApproval_d($pid, $itemtype, $isChange);
        //			echo "<pre>";
        //			print_R($rows['app']);

        $changeString = $this->service->changeGetResult_d($rows['change']); //最近一次变更审批意见

        echo $changeString;
    }

    //所有变更审批
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
        if ($flagArr[0]['name'] == '合同变更审批A' || $flagArr[0]['name'] == '合同变更审批B' || $flagArr[0]['name'] == '合同变更审批') {
            $findSql = "select originalId from oa_contract_contract where id = '$pid'";
            $oldArr = $this->service->_db->getArray($findSql);
            $cId = $oldArr[0]['originalId'];
        } else {
            $cId = $pid;
        }
        $rows = $this->service->getApproval_d($cId, $itemtype, $isChange, $isPrint);
        $taskArr = $this->service->findTaskArr($cId, $itemtype);
        $string = $this->service->getResultAll_d($rows['app'], $taskArr); //新增审批意见

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
        if ($flagArr[0]['name'] == '合同变更审批A' || $flagArr[0]['name'] == '合同变更审批B' || $flagArr[0]['name'] == '合同变更审批') {
            $findSql = "select originalId from oa_contract_contract where id = '$pid'";
            $oldArr = $this->service->_db->getArray($findSql);
            $cId = $oldArr[0]['originalId'] ? $oldArr[0]['originalId'] : $pid;
        } else {
            $cId = $pid;
        }

        $rows = $this->service->getApprovalAll_d($cId, $itemtype);
        if($itemtype == "oa_borrow_borrow"){// 如果是借试用的,另外加上交付变更的数据
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

        $changeString = $this->service->changeGetResultAll_d($rows); //最近一次变更审批意见

        echo $changeString;
    }
}