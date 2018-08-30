<?php
require_once($this->compDir."phpmailer/class.phpmailer.php");
require_once($this->compDir."includes/config_mail.php");
try{
    $msql->query("START TRANSACTION");
    if(empty($billId)||empty($examCode)||empty($formName))
        throw new Exception("数据传送失败");
    $sql="select task,name,objCode from wf_task where name='$formName' and code='$examCode' and Pid='$billId' and Status='ok' ";
    $msql->query2($sql);
    $msql->next_record();
    $taskid=$msql->f('task');//审批ID
	$formName=$msql->f('name');//审批ID
    $objCode=$msql->f('objCode');//审批业务单号
    $objInfoStr = (empty($objCode))? "单据ID：[{$billId}]" : "业务编号：[{$objCode}]";
    if(empty($taskid)){
        throw new Exception("表单不存在或已审批！");
    }
    //判断是否已经审批过 $msql->num_rows();
    $sql="SELECT * FROM flow_step where wf_task_id='".$taskid."' and ( flag='ok' or status<>'ok' or flag='') ";
    $msql->query2($sql);
    if($msql->num_rows()>0){
        throw new Exception("单据已经审批，无法撤销单据！");
    }
	
	 //判断是否已经审批过 $msql->num_rows();
    $sqlSelct="SELECT user FROM flow_step_partent where wf_task_id='".$taskid."' and flag='0'";
    $msql->query2($sqlSelct);
    $msql->next_record();
    $sendUserId=$msql->f('user');//审批ID
	if($sendUserId){
		$TO_ID =$sendUserId;     
        $Subject = "OA-撤销审批：$formName";
        $ebody = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;$formName 审批单已撤回,此单不需要审批！<br />&nbsp;&nbsp;&nbsp;&nbsp;审批单号：".$billId."<br />". $objInfoStr ."<br />&nbsp;&nbsp;&nbsp;&nbsp;这封邮件由".$_SESSION["USERNAME"]."选择给您发送！";
	}

    include($this->compDir."includes/send_html_message.php");
	include($this->compDir."includes/send_phone_message.php"); // 发送信息到下一位审批者
    require($this->compDir."includes/send_html_mail.php");
	include($this->compDir."util/jsonUtil.php");
	include($this->compDir."util/curlUtil.php");

    // 添加撤销审批通知 457 微信通知优化
    $mailUsers = "";
    $msql->query2("select * from flow_step where wf_task_id = '{$taskid}' and status = 'ok' order by SmallID limit 1");
    if($msql->num_rows()>0){
        $msql->next_record();
        $TO_ID=$msql->f('User');//审批人
        $currentUserid = $_SESSION['USER_ID'];
        $msg = "【通知】[{$_SESSION['USERNAME']}]已经撤销了一单[{$formName}]申请，{$objInfoStr}，请知悉。";

        $userArr = array_unique(explode(',', $TO_ID));
        foreach ($userArr as $v) {
            // 调用aws方法发送微信通知
            $result = util_curlUtil::getDataFromAWS('mobliemiro', 'WechatSendMsgAslp', array(
                "userid" => $v, 'msg' => $msg
            ), array(), true, 'com.youngheart.apps.');
        }
    }
	
    //删除工作流
    $sql="delete from wf_task where task='".$taskid."'";
    $msql->query2($sql);
    $sql="delete from flow_step where wf_task_id='".$taskid."'";
    $msql->query2($sql);
    $sql="delete from flow_step_partent where wf_task_id='".$taskid."'";
    $msql->query2($sql);
    //更新单据状态
    $sql = "update $examCode set ExaStatus = '".$returnSta."' where ID='$billId' ";
    $msql->query2($sql);
    //删除 更新业务信息
    if(!empty($delSql)){
      $msql->query2($delSql);
    }

    //清理工作流
    $msql->query("COMMIT");
    $resmsg='撤销成功';
}catch(Exception $e)
{
    $msql->query("ROLLBACK");
    writeToLog($e->getMessage().$e->getTraceAsString().$e->getFile().$sql,"error_project.txt");
    $resmsg='撤销失败';

}
if($flag=='url'){
    sendback($resmsg);
}elseif($flag=='json'){
    echo iconv ( 'GBK', 'UTF-8',$resmsg );
}
exit();
?>
