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
	//�����������
    $billArea=isset($billArea)?$billArea:"";//��������������
    $ewf->setBillArea($billArea);

    $billId=isset($billId)?$billId:"";//����������ID
    $examCode=isset($examCode)?$examCode:"oa_borrow_return";//�������ݱ�
    $passSqlCode=isset($passSqlCode)?$passSqlCode:"update oa_borrow_return set ExaStatus = '���',ExaDT = now() where id='$billId' ";//������ɺ�������
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:"update oa_borrow_return set ExaStatus = '���',ExaDT = now() where id='$billId'";//������ظ������
    //����������ɫ�� ��Ŀ���� -- ������
    $proSid=isset($proSid)?$proSid:"";//��Ŀ������ID  --��Ŀ����
    $proId=isset($proId)?$proId:"";//��ĿID   --��Ŀ���� �� �����������������Ŀ����
    $billDept=isset($billDept)?$billDept:$DEPT_ID;//������������������ -- ������
    $billCompany=isset($billCompany)?$billCompany:'';//������������˾
    //
    $ewf->setBillId($billId);
    $ewf->setExamCode($examCode);
    $ewf->setPassSqlCode($passSqlCode);
    $ewf->setDisPassSqlCode($disPassSqlCode);
    $ewf->setProId($proId);
    $ewf->setProSid($proSid);
    $ewf->setBillDept($billDept);
    $ewf->setBillCompany($billCompany);
    //��������
    $formName=isset($formName)?$formName:"�����ù黹����";//������������
    $flowType=isset($flowType)?$flowType:"";//����������
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//�������������
    $ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}
//���ɹ�����
if($actTo=="ewfBuild"){
	extract($_POST);
    //$sendToURL=$baseDir."?actTo=swfList";
    $sendToURL="../../../view/reloadParent.php";//���ɹ���������ת��ҳ��
    $ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){
	$skey = isset($_GET['skey'])? $_GET['skey'] : null ;
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//����������ID
    $detailUrl=isset($detailUrl)?$detailUrl:"../../../index1.php?model=finance_invoiceapply_invoiceapply&action=initAuditing&id=$billId&skey=$skey";//�����鿴����
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//�ύ����������
if($actTo=="ewfExamSub"){
    $sendToURL="../../../index1.php?model=finance_invoiceapply_invoiceapply&action=approvalNo";//������������ת��ҳ��
    $ewf->examWorkFlowSub($sendToURL);
}
//��������
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
//ɾ��������
if($actTo=="delWork"){
    $billId=isset($billId)?$billId:"";//����������ID
    $examCode=isset($examCode)?$examCode:"cost_summary_list";//�������ݱ�
    $formName=isset($formName)?$formName:"��������";//������������
    $returnSta=isset($returnSta)?$returnSta:"�༭";//�ع���������״̬
    $flag=isset($flag)?$flag:"json";//�ع���������״̬
    //����SQL
	$delSql = " update cost_summary_list c SET c.Status='�༭' where c.id='$billId' ";
    $ewf->delWorkFlow($billId,$examCode,$formName,$returnSta,$flag,$delSql);
}
?>