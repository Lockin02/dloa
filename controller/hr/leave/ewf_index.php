<?php
/**
    *ʹ�÷���
*/
include("../../../includes/db.inc.php");
include("../../../includes/config.php");
include("../../../includes/msql.php");
include("../../../includes/fsql.php");
include("../../../includes/qsql.php");
include("../../../includes/util.php" );
include("../../../includes/getUSER_DEPT_ID.php" );
include("../../../module/work_flow_examine/examine.class.php");
/**
* ����������ֶ��б��뺬�� ����״̬��ExaStatus --varchar(15) ״̬Ϊ�� �༭���½�ʱ�� ��������  ��� ; �������� ��ExaDT --datetime
*/
$actTo=isset($actTo)?$actTo:"";
$baseDir="../../../module/work_flow_examine/";//�������ģ��ĵ�ַ
//������
$ewf=new WorkFlow();
$ewf->setBaseDir($baseDir);
//ѡ������
if($actTo=="ewfSelect"){
	//�����������
    $billArea=isset($billArea)?$billArea:"";//��������������
    $ewf->setBillArea($billArea);
    $billId=isset($billId)?$billId:"";//����������ID
    $examCode=isset($examCode)?$examCode:"oa_hr_leave";//�������ݱ�
    $passSqlCode=isset($passSqlCode)?$passSqlCode:" update oa_hr_leave set ExaStatus='���',ExaDT = now() where id='$billId'";//������ɺ�������
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:" update oa_hr_leave set ExaStatus = '���',ExaDT = now() where id='$billId'";//������ظ������
    //����������ɫ�� ��Ŀ���� -- ������
    $proSid=isset($proSid)?$proSid:"";//��Ŀ������ID  --��Ŀ����
    $proId=isset($proId)?$proId:"";//��ĿID   --��Ŀ���� �� �����������������Ŀ����
    $billDept=isset($billDept)?$billDept:$DEPT_ID;//������������������ -- ������
    $eUserId=isset($eUserId)?$eUserId:"";//������
    //
    $ewf->setBillId($billId);
    $ewf->setExamCode($examCode);
    $ewf->setPassSqlCode($passSqlCode);
    $ewf->setDisPassSqlCode($disPassSqlCode);
    $ewf->setProId($proId);
    $ewf->setProSid($proSid);
    $ewf->setBillDept($billDept);
    $ewf->seteUserId($eUserId);
    //��������
    $formName=isset($formName)?$formName:"��ְ��������";//������������
    $flowType=isset($flowType)?$flowType:"";//����������
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//�������������
    $ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}
//���ɹ�����
if($actTo=="ewfBuild"){
    //$sendToURL=$baseDir."?actTo=swfList";
    $msql->query(" update oa_hr_leave SET ExaStatus='��������' where id='$billId' ");//�ύ������ı�����״̬
    $sendToURL="../../../view/reloadParent.php";//���ɹ���������ת��ҳ��
    $ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){
	$skey=isset ($_GET['skey'])?$_GET['skey']:"";
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//����������ID
    $detailUrl=isset($detailUrl)?$detailUrl:"../../../index1.php?model=hr_leave_leave&action=init&id=$billId&perm=view&skey=$skey";//�����鿴����
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//�ύ����������
if($actTo=="ewfExamSub"){
	$leaveRows=isset($_POST['leave'])?$_POST['leave']:null;
	$spid = $_POST['spid'];
//	$billId=isset($billId)?$billId:"";//����������ID
	$handContentArr=array();
	$recipientNameArr=array();
	$recipientIdArr=array();
	if(is_array($leaveRows['handitem'])){
		foreach($leaveRows['handitem'] as $key=>$val){
			if($val['handContent']!=''&&$val['recipientName']!=''){
				array_push($handContentArr,$val['handContent']);
				array_push($recipientNameArr,$val['recipientName']);
				array_push($recipientIdArr,$val['recipientId']);
			}
		}
	}
	$handContent=implode(',',$handContentArr);
	$recipientName=implode(',',$recipientNameArr);
	$recipientId=implode(',',$recipientIdArr);
    $sendToURL="../../../index1.php?model=hr_leave_leave&action=leaveMail&spid=$spid&result=$result&rows[id]=$leaveRows[id]&rows[recipientName]=$recipientName&rows[recipientId]=$recipientId&rows[handContent]=$handContent&rows[requireDate]=$leaveRows[requireDate]";//������������ת��ҳ��
    $ewf->examWorkFlowSub($sendToURL);
}
//��������
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
?>