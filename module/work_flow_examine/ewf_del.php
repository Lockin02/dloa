<?php
require_once($this->compDir."phpmailer/class.phpmailer.php");
require_once($this->compDir."includes/config_mail.php");
try{
    $msql->query("START TRANSACTION");
    if(empty($billId)||empty($examCode)||empty($formName))
        throw new Exception("���ݴ���ʧ��");
    $sql="select task,name,objCode from wf_task where name='$formName' and code='$examCode' and Pid='$billId' and Status='ok' ";
    $msql->query2($sql);
    $msql->next_record();
    $taskid=$msql->f('task');//����ID
	$formName=$msql->f('name');//����ID
    $objCode=$msql->f('objCode');//����ҵ�񵥺�
    $objInfoStr = (empty($objCode))? "����ID��[{$billId}]" : "ҵ���ţ�[{$objCode}]";
    if(empty($taskid)){
        throw new Exception("�������ڻ���������");
    }
    //�ж��Ƿ��Ѿ������� $msql->num_rows();
    $sql="SELECT * FROM flow_step where wf_task_id='".$taskid."' and ( flag='ok' or status<>'ok' or flag='') ";
    $msql->query2($sql);
    if($msql->num_rows()>0){
        throw new Exception("�����Ѿ��������޷��������ݣ�");
    }
	
	 //�ж��Ƿ��Ѿ������� $msql->num_rows();
    $sqlSelct="SELECT user FROM flow_step_partent where wf_task_id='".$taskid."' and flag='0'";
    $msql->query2($sqlSelct);
    $msql->next_record();
    $sendUserId=$msql->f('user');//����ID
	if($sendUserId){
		$TO_ID =$sendUserId;     
        $Subject = "OA-����������$formName";
        $ebody = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;$formName �������ѳ���,�˵�����Ҫ������<br />&nbsp;&nbsp;&nbsp;&nbsp;�������ţ�".$billId."<br />". $objInfoStr ."<br />&nbsp;&nbsp;&nbsp;&nbsp;����ʼ���".$_SESSION["USERNAME"]."ѡ��������ͣ�";
	}

    include($this->compDir."includes/send_html_message.php");
	include($this->compDir."includes/send_phone_message.php"); // ������Ϣ����һλ������
    require($this->compDir."includes/send_html_mail.php");
	include($this->compDir."util/jsonUtil.php");
	include($this->compDir."util/curlUtil.php");

    // ��ӳ�������֪ͨ 457 ΢��֪ͨ�Ż�
    $mailUsers = "";
    $msql->query2("select * from flow_step where wf_task_id = '{$taskid}' and status = 'ok' order by SmallID limit 1");
    if($msql->num_rows()>0){
        $msql->next_record();
        $TO_ID=$msql->f('User');//������
        $currentUserid = $_SESSION['USER_ID'];
        $msg = "��֪ͨ��[{$_SESSION['USERNAME']}]�Ѿ�������һ��[{$formName}]���룬{$objInfoStr}����֪Ϥ��";

        $userArr = array_unique(explode(',', $TO_ID));
        foreach ($userArr as $v) {
            // ����aws��������΢��֪ͨ
            $result = util_curlUtil::getDataFromAWS('mobliemiro', 'WechatSendMsgAslp', array(
                "userid" => $v, 'msg' => $msg
            ), array(), true, 'com.youngheart.apps.');
        }
    }
	
    //ɾ��������
    $sql="delete from wf_task where task='".$taskid."'";
    $msql->query2($sql);
    $sql="delete from flow_step where wf_task_id='".$taskid."'";
    $msql->query2($sql);
    $sql="delete from flow_step_partent where wf_task_id='".$taskid."'";
    $msql->query2($sql);
    //���µ���״̬
    $sql = "update $examCode set ExaStatus = '".$returnSta."' where ID='$billId' ";
    $msql->query2($sql);
    //ɾ�� ����ҵ����Ϣ
    if(!empty($delSql)){
      $msql->query2($delSql);
    }

    //��������
    $msql->query("COMMIT");
    $resmsg='�����ɹ�';
}catch(Exception $e)
{
    $msql->query("ROLLBACK");
    writeToLog($e->getMessage().$e->getTraceAsString().$e->getFile().$sql,"error_project.txt");
    $resmsg='����ʧ��';

}
if($flag=='url'){
    sendback($resmsg);
}elseif($flag=='json'){
    echo iconv ( 'GBK', 'UTF-8',$resmsg );
}
exit();
?>
