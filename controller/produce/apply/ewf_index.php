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
* ������������ֶ��б��뺬�� ����״̬��ExaStatus --varchar(15) ״̬Ϊ���༭���½�ʱ����������������ɡ����; �������� ��ExaDT --datetime
*/
$actTo = isset($actTo)?$actTo:"";
$baseDir = "../../../module/work_flow_examine/";//�������ģ��ĵ�ַ
//������
$ewf = new WorkFlow();
$ewf->setBaseDir($baseDir);

//ѡ������
if($actTo == "ewfSelect") {
	//�����������
	$billArea = isset($billArea)?$billArea:"";//����������������
	$ewf->setBillArea($billArea);

	$billId = isset($billId)?$billId:"";//������������ID
	$examCode = isset($examCode)?$examCode:"oa_produce_produceapply";//�������ݱ�
	$passSqlCode = isset($passSqlCode)?$passSqlCode:" update oa_produce_produceapply c set c.ExaStatus = '���',c.ExaDT = now() ,c.docStatus=0 where c.id='$billId' ";//������ɺ�������
	$disPassSqlCode = isset($disPassSqlCode)?$disPassSqlCode:" update oa_produce_produceapply c set c.ExaStatus = '���' ,c.docStatus=7 where c.id='$billId'";//������ظ������
	//����������ɫ�� ��Ŀ���� -- ������
	$proSid = isset($proSid)?$proSid:"";//��Ŀ������ID  --��Ŀ����
	$proId = isset($proId)?$proId:"";//��ĿID   --��Ŀ���� �� �����������������Ŀ������
	$billDept = isset($billDept)?$billDept:$DEPT_ID;//�������������������� -- ������
	//
	$ewf->setBillId($billId);
	$ewf->setExamCode($examCode);
	$ewf->setPassSqlCode($passSqlCode);
	$ewf->setDisPassSqlCode($disPassSqlCode);
	$ewf->setProId($proId);
	$ewf->setProSid($proSid);
	$ewf->setBillDept($billDept);
	//��������
	$formName = isset($formName)?$formName:"������������";//��������������
	$flowType = isset($flowType)?$flowType:"";//����������
	$flowMoney = isset($flowMoney)?$flowMoney:"0";//�������������
	$ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}

//���ɹ�����
if($actTo == "ewfBuild"){
	//$sendToURL=$baseDir."?actTo=swfList";
	$msql->query(" update oa_produce_produceapply c SET c.ExaStatus='��������',c.docStatus=6 where c.id='$billId' ");
	$sendToURL = "../../../view/reloadParent.php";//���ɹ���������ת��ҳ��
	$ewf->buildWorkFlow($sendToURL);
}

//����������
if($actTo=="ewfExam"){
	$skey = isset($_GET['skey'])? $_GET['skey'] : null ;
	$taskId = isset($taskId)?$taskId:"";
	$spid = isset($spid)?$spid:"";
	$billId = isset($billId)?$billId:"";//������������ID
	$detailUrl = isset($detailUrl)?$detailUrl:"../../index1.php?model=produce_apply_produceapply&action=toView&id=$billId&skey=$skey";//�����鿴����
	$ewf->examWorkFlow($taskId,$spid,$detailUrl);
}

//�ύ����������
if($actTo == "ewfExamSub"){
	$sendToURL = "../../../index1.php?model=produce_apply_produceapply&action=toAssetAuditTab";//������������ת��ҳ�棬��ת��������ҳ��
	$ewf->examWorkFlowSub($sendToURL);
}

//��������
if($actTo == "ewfView"){
	$taskId = isset($taskId)?$taskId:"";
	$ewf->examWorkFlowView($taskId);
}
?>