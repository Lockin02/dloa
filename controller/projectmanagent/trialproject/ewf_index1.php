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
extract($_GET);
$actTo=isset($actTo)?$actTo:"";
$baseDir="../../../module/work_flow_examine/";//�������ģ��ĵ�ַ
//������
$ewf=new WorkFlow();
$ewf->setBaseDir($baseDir);
//ѡ������
if($actTo=="ewfSelect"){
    $billId=isset($billId)?$billId:"";//����������ID
    $examCode=isset($examCode)?$examCode:"oa_trialproject_trialproject";//�������ݱ�
    $passSqlCode=isset($passSqlCode)?$passSqlCode:" update oa_trialproject_trialproject set status= 2,ExaStatus = '���',turnStatus='δת��',ExaDT = '".date("Y-m-d")."' where id='$billId' ";//������ɺ�������
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:" update oa_trialproject_trialproject set ExaStatus = '���',status = 0 where id='$billId'";//������ظ������
    //����������ɫ�� ��Ŀ���� -- ������
    $proSid=isset($proSid)?$proSid:"";//��Ŀ������ID  --��Ŀ����
    $proId=isset($proId)?$proId:"";//��ĿID   --��Ŀ���� �� �����������������Ŀ����
    $billDept=isset($billDept)?$billDept:$DEPT_ID;//������������������ -- ������
    //
    $ewf->setBillId($billId);
    $ewf->setExamCode($examCode);
    $ewf->setPassSqlCode($passSqlCode);
    $ewf->setDisPassSqlCode($disPassSqlCode);
    $ewf->setProId($proId);
    $ewf->setProSid($proSid);
    $ewf->setBillDept($billDept);
    
    //�ͻ���Ϣ
    $sql="SELECT typeone  , arealeaderid , c.areaid FROM oa_trialproject_trialproject  b 
left join customer c on (c.id=b.customerid)
where b.id='$billId' ";
    $msql->query($sql);
    $msql->next_record();
    $cktype=$msql->f('typeone');
    $ewf->setCkType($cktype);
    $ewf->setBillUser($msql->f('arealeaderid'));//����������
    
    //��������
    $formName=isset($formName)?$formName:"������Ŀ����";//������������
    if(in_array($msql->f('areaid'),$spe_bx_flow)){
      $flowType=isset($flowType)?$flowType:"1";//����������-����
    }else{
      $flowType=isset($flowType)?$flowType:"2";//����������-����
    }
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//�������������
    $ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}
//���ɹ�����
if($actTo=="ewfBuild"){
    //$sendToURL=$baseDir."?actTo=swfList";
    $msql->query(" update oa_trialproject_trialproject SET status = 1,ExaStatus='��������' where id='$billId' ");
    $sendToURL="../../../view/reloadParent.php";//���ɹ���������ת��ҳ��
    $ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){
	$skey=isset($_GET['skey'])?$_GET['skey']:"";
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//����������ID
    $detailUrl=isset($detailUrl)?$detailUrl:"../../../index1.php?model=projectmanagent_trialproject_trialproject&action=init&perm=view&id=$billId&skey=$skey";//�����鿴����
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//�ύ����������
if($actTo=="ewfExamSub"){
    $sendToURL="../../../index1.php?model=projectmanagent_trialproject_trialproject&action=toAuditNo";//������������ת��ҳ�棬��ת��������ҳ��
    $ewf->examWorkFlowSub($sendToURL);
}
//��������
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
?>