<?php
require_once($this->compDir."phpmailer/class.phpmailer.php");  
require_once($this->compDir."includes/config_mail.php");  
include($this->compDir."model/common/workflow/workflowInfoConfig.php");//�������÷����ļ� - ����޸� 
$requireType = $_REQUEST['requireType'];
$isMbFlag=1;
if($requireType=='mobile'){
	$isMbFlag=2;
}
$TO_ID=isset($_POST["TO_ID"])?$_POST["TO_ID"]:"";
$spid=isset($_POST["spid"])?$_POST["spid"]:"";
$result=isset($_POST["result"])?$_POST["result"]:"";
$content=isset($_POST["content"])?$_POST["content"]:"";
$USER_ID=isset($_SESSION["USER_ID"])?$_SESSION["USER_ID"]:"";
$copyUserId=isset($_SESSION["copyUserId"])?$_SESSION["copyUserId"]:"";
$sendEmailUserArr=explode(",",$TO_ID);
$next_sid="";
$nextChecker="";
$nextFlow=false;
$wfTaskId="";
$wffinish=false;
$wffinishHols=false;
$wfCreator="";
$wfCreatorDept="";
$sysMsgI=array();
//print_r($_POST);
//die();
$msql->query("START TRANSACTION");
try{   
//�ж�����ͨ��                       
    if($USER_ID==""||$spid==""||$result=="")
        throw new Exception("���ݴ���ʧ��");
    $sql="select p.SmallID, p.Wf_task_ID , s.Flow_prop , t.name , t.Creator , t.Enter_user,t.DBTable , t.code , t.pid,u.USER_NAME  from flow_step_partent p , flow_step s , wf_task t,`user` u where t.task=p.Wf_task_ID and p.ID='$spid' and p.Wf_task_ID=s.Wf_task_ID and p.SmallID=s.SmallID and p.Flag!='1' AND u.USER_ID=t.Creator";
    $msql->query2( $sql);
    if($msql->affected_rows()<1){
        throw new Exception("��������Ѿ�������������鿴��");
    }
    $msql->next_record();
    $fckp=1;
    $nowSmallId=$msql->f("SmallID");
    $wfTaskId=$msql->f("Wf_task_ID");
    $flowProp=$msql->f("Flow_prop");
    $flowName=$msql->f("name");
    $wfCreator=$msql->f("Enter_user");
	$wftCreator=$msql->f("Creator");
    $gdbtable=$msql->f("DBTable");
    $gdbtablestr='';
    if(!empty($gdbtable)&&$gdbtable!=''){
      $gdbtablestr='&gdbtable='.$gdbtable;
    }
    $examCode=$msql->f("code");
    $examPid=$msql->f("pid");
    
	if($isMbFlag==2&& $TO_ID ==""){
		$sqlToId="select USER_ID from user u, wf_task l where u.USER_ID=l.Enter_user  and l.task='$wfTaskId' group by USER_ID";
		$msql->query($sqlToId);
		while($msql->next_record())
		{
			if($msql->f("USER_ID")){
				$TO_ID .= $msql->f("USER_ID").",";
			}
		}
		
	}
	
    //����������ã��滻��ѯ���
    if(!empty($workflowInfoConfig[$examCode])){
        $thisCode = $workflowInfoConfig[$examCode]['thisCode'];
        $sqltmp = "select $thisCode from $examCode c where c.id='$examPid' ";
        $informationArr=$msql->getrow($sqltmp);
        $information = $workflowInfoConfig[$examCode]['thisInfo'];
        $objArr = isset($workflowInfoConfig[$examCode]['objArr']) ? $workflowInfoConfig[$examCode]['objArr'] : null;
        $robjArr = isset($workflowInfoConfig[$examCode]['objArr']) ? array_flip($workflowInfoConfig[$examCode]['objArr']) : null;
        foreach($informationArr as $key=>$val){
            $information=str_replace('$'.$key, $val, $information);
            //��������������ֶ���������
            if(!empty($objArr) && in_array($key,$objArr)){
                $tempkey = $robjArr[$key];
                $$tempkey = $val;
            }
        }
    }
    $sql="select user_name , dept_id  from user where user_id='$wfCreator'";
    $msql->query2($sql);
    $msql->next_record();
    $wfCreator=$msql->f("user_name");
    $wfCreatorDept=$msql->f("dept_id");
    $sql="update wf_task set UpdateDT = now() where Status='ok' and task='$wfTaskId' ";
    $msql->query2($sql);
    if($msql->affected_rows()<1){
        throw new Exception("���������������Ѹ����أ�");
    }
//ִ�д���    
    $sql="update flow_step_partent set Flag='1', User='".$USER_ID."',Result='".$result."',Content='".$content."',copyUserId='$copyUserId',Endtime='".getlongdatetime()."',isMbFlag='".$isMbFlag."' where ID=".$spid;
    $msql->query2( $sql);
//pass
    //if($result=="ok"||$flowProp=='1')
	if($result=="ok")
    {
        insertOperateLog("����������",$wfTaskId,"����ͨ��","�ɹ�");
        //ɾ���Ժ���Ҫ�����Ĳ���
        $sql="select ID , User from flow_step where SmallID>'$nowSmallId' and Wf_task_ID='$wfTaskId' and find_in_set('$USER_ID',User)>0 order by SmallID";
        $fsql->query2($sql);
        while($fsql->next_record()){
            //ɾ��������������
            $fckp++;
            $msql->query2("delete from flow_step where ID='".$fsql->f("ID")."' ");         
        }
//
        if($flowProp==1){
            $sql="select ID from flow_step_partent where Wf_task_ID='$wfTaskId' and SmallID='$nowSmallId' and Flag!='1' and ID!='$spid' ";
            $msql->query2($sql);
            if($msql->num_rows()==0){
                $nextFlow=true;
            }
        }
        else{
            $sql="delete from flow_step_partent where Wf_task_ID='$wfTaskId' and SmallID='$nowSmallId' and ID!='$spid' ";
            $msql->query2($sql);
            $nextFlow=true;
        }
        if($nextFlow===true){
            $sql="update flow_step set Flag='',Endtime=now() where Wf_task_ID='$wfTaskId' and SmallID='$nowSmallId'";
            $msql->query2( $sql);
            //$nextSmallId=$nowSmallId+$fckp;
            $sql="select ID,User,Flag ,SmallID,isReceive,isEditPage from flow_step where SmallID>'$nowSmallId' and Wf_task_ID='$wfTaskId' order by SmallID";
            $msql->query2($sql);
            if($msql->next_record())
            {
                $stepid=$msql->f("ID");
                $nextChecker=$msql->f("User");
                $nextSmallId=$msql->f("SmallID");
                $isReceive = $msql->f("isReceive");
                $isEditPage = $msql->f("isEditPage");
//�ܾ����������쵽��Ȩ

//								$posstr = strpos($nextChecker,'joe.wang');
//								$posstrto = strpos($nextChecker,'dafa.yu');
//								if( $posstr!==false && $posstrto ===false ){
//									$nextChecker.=',dafa.yu';
//								}
                $tempstep=explode(",",trim($nextChecker,','));
                $tempstep=array_unique($tempstep);
                foreach( $tempstep as $val){
                    if($val!=""){
                        $sql="INSERT into flow_step_partent set StepID='$stepid',SmallID='$nextSmallId',Wf_task_ID='$wfTaskId',User='$val',Flag='0',START=now(),isReceive='$isReceive',isEditPage='$isEditPage'   " ;
                        $msql->query2( $sql);
                    }
                }
                insertOperateLog("����������",$wfTaskId,"����������һ������ flow_step��".$stepid,"�ɹ�");
            }else{
                
                $sql="update wf_task set examines='ok' , finish=now() , Status='0' where task='$wfTaskId' and Status='ok' ";
                $msql->query2($sql);
                $sql="select PassSqlCode , name from wf_task where task='$wfTaskId'";
                $msql->query2($sql);
                $msql->next_record();
                $passSqlCode=$msql->f('PassSqlCode');
                $wfTaskName=$msql->f("name");
                if($passSqlCode!=""){
                    $msql->query2(stripslashes($passSqlCode));
                }
                if($wfTaskName=="�������ڵ���")
                    $wffinish=true;
                if($wfTaskName=="���ݼ�"&&$wfCreatorDept=="35")
                    $wffinishHols=true;
                insertOperateLog("����������",$wfTaskId,"�������������","�ɹ�");
            }
        }
    }
    elseif($result=="no")
    {
//back
        $sql="update flow_step set Flag='ok',Endtime=now() where Wf_task_ID='$wfTaskId' and SmallID='$nowSmallId'";
        $msql->query2( $sql);
        $sql="update wf_task set examines='no' , Status='0' , finish=now() where task='$wfTaskId' and Status='ok' ";
        $msql->query2($sql);
        $sql="select DisPassSqlCode , name from wf_task where task='$wfTaskId'";
        $msql->query2($sql);
        $msql->next_record();
        $disPassSqlCode=$msql->f('DisPassSqlCode');
        $wfTaskName=$msql->f("name");
        if($disPassSqlCode!=""){
            $msql->query2(stripslashes($disPassSqlCode));
        }
        if($wfTaskName=="���ݼ�"&&$wfCreatorDept=="35")
            $wffinishHols=true;
        insertOperateLog("����������",$wfTaskId,"������ͨ��","�ɹ�");
    }
    //�ʼ�����
    if(!empty($emailSql)){
        $emailSql.=" and b.task='".$wfTaskId."' ";
        $emailData=$msql->getrow($emailSql);
        if(is_array($emailData)){
            foreach($emailData as $key=>$val){
                $emailBody=str_replace('$'.$key, $val, $emailBody);
            }
        }
    }
    //�����ʼ�����
    if($flowName=='���ݼ�'){//���ݼٻ�ȡ��Ϣ
        $sql="select
                u.user_name , h.type , h.begindt
                , h.enddt , h.beginhalf , h.endhalf
                , h.reason
            from wf_task t
                left join hols h on ( t.pid=h.id )
                left join hrms hr on (h.userid=hr.user_id)
                left join user u on (hr.user_id=u.user_id)
            where
                t.code ='hols'
                and task='".$wfTaskId."' ";
        $msql->query($sql);
        $msql->next_record();
        $mainData=array('�����ˣ�'=>$msql->f('user_name')
                ,'������ͣ�'=>$msql->f('type')
                ,'��ʼ���ڣ�'=>$msql->f('begindt')
                ,'��ֹ���ڣ�'=>$msql->f('enddt')
                ,'����ԭ��'=>$msql->f('reason')
                ,'��һ�����ˣ�'=>$_SESSION["USERNAME"]
            );
    }
		
	$sqlcoms="select ID,User,Flag ,SmallID,isReceive,isEditPage from flow_step where SmallID>'$nowSmallId' and Wf_task_ID='$wfTaskId' order by SmallID";
	$msql->query2($sqlcoms);
	if(!$msql->next_record())
	{
		 $stepid=$msql->f("ID");
		$sqltmp="SELECT GROUP_CONCAT(copyUserId) AS appUser FROM flow_step_partent WHERE Wf_task_ID='$wfTaskId'  ";
			$msql->query($sqltmp);
			$SendToIDI=array();
			while($msql->next_record()){
				$copyToIDI=$msql->f('appUser');
			}
			$TO_ID=$TO_ID?$TO_ID.','.($copyToIDI?implode(',',$copyToIDI):''):($copyToIDI?implode(',',$copyToIDI):'');
	}
	
	
	writeToLog("�ʼ���".$title.'-T-'.$_POST["issend"] .'s'.$TO_ID.'-I-'.$result,"task_email-all23.txt");
    if(($_POST["issend"] == "y" ||$isMbFlag==2)&& $TO_ID !="")
    {
        if($wffinish){
            //$TO_ID.="ruiping.zuo,";
        }
        if($wffinishHols)
            $TO_ID.="haiqiang.chen,peiwen.huang,yongan.sun,dongsheng.wang,";
        $Subject = "OA-������".$flowName;
        $extraMsg = $_POST["extraMessage"]==""?null: "������Ϣ��".$_POST["extraMessage"];
        $ebody = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION["USERNAME"]."�Ѿ�����������Ϊ��".$wfTaskId." , �����ˣ�".$wfCreator."       �����뵥����������<br />&nbsp;&nbsp;&nbsp;&nbsp;���������";
        if($result=="no")
            $ebody.="<font color='red'>��ͨ��</font>";
        else
            $ebody.="<font color='blue'>ͨ��</font>";
        if(!empty($content)){
            $ebody.='<br />&nbsp;&nbsp;&nbsp;&nbsp;���������'.$content;
        }
        if($flowName=='���ݼ�'){
            $ebody .= "<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������£�";
            foreach($mainData as $key=>$val){
                $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$key.' '.$val;
            }
        }
        if($result=="no"||!empty($content)){
            $sqlcke="SELECT GROUP_CONCAT(user) as toeu   FROM flow_step where wf_task_id='".$wfTaskId."'
             and SmallID<'$nowSmallId' group by wf_task_id ";
             $msql->query($sqlcke);
             $msql->next_record();
             $TO_ID.=$msql->f('toeu');
        }
        if(!empty($objUser)){//����ҵ����
        		$TO_ID.=$objUser;
        }
        if(!empty($emailBody)){
            $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$emailBody;
        }elseif(!empty($information)){
            $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$information;
        }

        //�������Ҫ�յ��ģ����Ͳ����֪ͨ
		if(isset($isReceive) && $isReceive == "1"){
			$nextCheckerStr = "";
			foreach( explode(",",trim($nextChecker,',')) as $val){
				if(empty($nextCheckerStr)){
					$nextCheckerStr = "'". $val . "'";
				}else{
					$nextCheckerStr .= ",'". $val . "'";
				}
			}
	        $sql="select group_concat(USER_NAME) as USER_NAME from user where USER_ID in(".trim($nextCheckerStr,',').")";
	        $msql->query2( $sql);
	        $msql->next_record();
       		$nextCheckerNames = $msql->f('USER_NAME');

	        $sql="select objCode,infomation from wf_task where task = '$wfTaskId'";
	        $msql->query2( $sql);
	        $msql->next_record();
       		$infomation = $msql->f('infomation');
       		$objCode = $msql->f('objCode');

			$Subject = "OA-��������֪ͨ:".$objCode;
			$ebody = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;���ı������Ѿ�ͨ������������������������׶Ρ�<br />&nbsp;&nbsp;&nbsp;&nbsp;���ӡ��������С��һ�뽻�����񲿻�ƣ�".$nextCheckerNames." ������ˡ�лл��";

		}
        require($this->compDir."includes/send_html_mail.php");
		//require($this->compDir . "config.php");
        require($this->compDir . "util/curlUtil.php");
        require($this->compDir . "util/jsonUtil.php");
		
		// ΢��֪ͨ������
        $msg = "���ã�" . $_SESSION['USERNAME'] . "�Ѿ�����������Ϊ��" . $wfTaskId .
            "��" . $flowName . "���������ˣ�" . $wfCreator . " �����뵥�������������������";
        $msg .= $result == "no" ? "��ͨ��" : "ͨ��";
        if (!empty($content)) {
            $msg .= '�����������' . $content;
        }
        $userArr = explode(',', $TO_ID);
        foreach ($userArr as $v) {
            if ($v) {
                // ����΢��֪ͨ
                util_curlUtil::getDataFromAWS('mobliemiro', 'WechatSendMsgAslp', array(
                    "userid" => $v, 'msg' => $msg
                ), array(), true, 'com.youngheart.apps.');
            }
        }
		
    }
    if(isset($_POST["isSendNext"])&&$_POST["isSendNext"]=="y" && $nextChecker!="")
    {
        $TO_ID = $nextChecker;
        $Subject = "OA-������".$flowName;
        $ebody = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;���µ���������Ҫ��������
                <br />&nbsp;&nbsp;&nbsp;&nbsp;�������ţ�".$wfTaskId."
                <br />&nbsp;&nbsp;&nbsp;&nbsp;����ʼ���".$_SESSION["USERNAME"]."ѡ��������ͣ�";
        if(!empty($content)){
            $ebody.='<br />&nbsp;&nbsp;&nbsp;&nbsp;���������'.$content;
        }
        if($flowName=='���ݼ�'){
            $ebody .= "<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������£�";
            foreach($mainData as $key=>$val){
                $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$key.' '.$val;
            }
        }
        if(!empty($emailBody)){
            $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$emailBody;
        }elseif(!empty($information)){
            $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$information;
        }
		
        require($this->compDir."includes/send_html_mail.php");
		
		// ΢��֪ͨ��һ������
        $msg = "���ã����µ���������Ҫ���������������ţ�" . $wfTaskId .
            "��" . $flowName . "��������Ϣ��" . $_SESSION["USERNAME"] . "ѡ��������ͣ�";
        if (!empty($content)) {
            $msg .= '���������' . $content;
        }
        $userArr = explode(',', $TO_ID);
        foreach ($userArr as $v) {
            if ($v) {
                // ����΢��֪ͨ
                util_curlUtil::getDataFromAWS('mobliemiro', 'WechatSendMsgAslp', array(
                    "userid" => $v, 'msg' => $msg
                ), array(), true, 'com.youngheart.apps.');
            }
        }
    }
	
	
	
	if(($_POST["isSendSys"]==1||$_POST["isSendApp"]==1)&&$content&&$result){
		$TO_ID ='';
		$Subject = "OA-���������".$flowName;
        $ebody = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION["USERNAME"]."�Ѿ�����������Ϊ��".$wfTaskId." , �����ˣ�".$wfCreator."       �����뵥����������<br />&nbsp;&nbsp;&nbsp;&nbsp;���������";
        if($result=="no")
           $ebody.="<font color='red'>��ͨ��</font>";
        else
            $ebody.="<font color='blue'>ͨ��</font>";
        if(!empty($content)){
            $ebody.='<br />&nbsp;&nbsp;&nbsp;&nbsp;���������'.$content;
        }
        if($flowName=='���ݼ�'){
            $ebody .= "<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������£�";
            foreach($mainData as $key=>$val){
                $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$key.' '.$val;
            }
        }
		if(!empty($emailBody)){
            $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$emailBody;
        }elseif(!empty($information)){
            $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$information;
        }
		if($_POST["isSendApp"]==1){
			$sqltmp="SELECT User AS appUser FROM flow_step_partent WHERE Wf_task_ID='$wfTaskId' and SmallID<'$nowSmallId' ";
			$msql->query($sqltmp);
			$SendToIDI=array();
			while($msql->next_record()){
				$SendToIDI[trim($msql->f('appUser'),',')]=trim($msql->f('appUser'),',');
			}
			$TO_ID=$SendToIDI?implode(',',$SendToIDI):'';	 
			 
		}
		
		if($_POST["isSendSys"]==1){
			$TO_ID =$TO_ID?$TO_ID.','.$sysSenderMail:$sysSenderMail;	
			 $sql="INSERT into flow_comments  set StepID='$stepid',SmallID='$nowSmallId',wf_id='$wfTaskId',creator='".$_SESSION['USER_ID']."',flowName='$flowName',createrTime=now(),code='$examCode',pid='$examPid',user='$wftCreator',content='$content'" ;
              $msql->query2( $sql);
		}		
		require($this->compDir."includes/send_html_mail.php");
		
			
	}	
	
    $msql->query("COMMIT");
}catch(Exception $e)
{
    $msql->query("ROLLBACK");
    insertOperateLog("����������",$wfTaskId,"����������","ʧ��",$e->getMessage().'-'.$USER_ID.'-'.$spid.'-'.$result);
    if($requireType=='mobile'){
		$res['msgCode'] = '2';
		$res['url'] = '';
		$res['msg'] = '����ʧ�ܣ�';
		$res['responseCode'] =1;
		$res['errorMsg'] =$e->getMessage().'-'.$USER_ID.'-'.$spid.'-'.$result;
		echo json_encode( tmp_iconv( $res ) );
		exit();
	}else{
		sendback("����ʧ��!".$sql.$e->getMessage());
    	exit();	
	}
    
}
if($requireType=='mobile'){
		$res['msgCode'] = '1';
		$res['msg'] = '�����ɹ���';
		$res['responseCode'] =1;
		$res['url'] =$sendToURL.$gdbtablestr;
		
		//header("Location: ".$sendToURL.$gdbtablestr.'&sessionId='.session_id()); 
		//succ_show($sendToURL.$gdbtablestr);
		echo json_encode( tmp_iconv( $res ) );
		exit();
}else{
	succ("�����ɹ�!",$sendToURL.$gdbtablestr);	
}

?>