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
    $billId=isset($billId)?$billId:"";//����������ID
    $examCode=isset($examCode)?$examCode:"";//�������ݱ�
    $passSqlCode=isset($passSqlCode)?$passSqlCode:"";//������ɺ�������
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:"";//������ظ������
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
    //��������
    $formName=isset($formName)?$formName:"";//������������
    $flowType=isset($flowType)?$flowType:"";//����������
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//�������������
    $ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}
//���ɹ�����
if($actTo=="ewfBuild"){
	$msql->query(" update oa_purch_inquiry c SET c.state=1, c.ExaStatus='��������' where c.id='$billId' ");
   $sendToURL="../../../index1.php?model=purchase_inquiry_inquirysheet&action=isMyInquiryTab";//���ɹ���������ת��ҳ��
   $ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//����������ID
	$detailUrl=isset($detailUrl)?$detailUrl:"../../../index1.php?model=purchase_inquiry_inquirysheet&action=toAssignSupp&actType=audit&id=$billId";//�����鿴����
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//�ύ����������
if($actTo=="ewfExamSub"){
	$inquirysheetRows=isset($_POST['inquirysheet'])?$_POST['inquirysheet']:null;
	$spid = $_POST ['spid'];
//	echo "<pre>";
//	print_r($inquirysheetRows);
    $sendToURL="../../../index1.php?model=purchase_inquiry_inquirysheet&action=assignSuppByApproval&spid=$spid&rows[id]=$inquirysheetRows[id]&rows[suppId]=$inquirysheetRows[suppId]&rows[suppName]=$inquirysheetRows[suppName]&rows[amaldarName]=$inquirysheetRows[amaldarName]&rows[amaldarId]=$inquirysheetRows[amaldarId]&rows[amaldarDate]=$inquirysheetRows[amaldarDate]&rows[amaldarRemark]=$inquirysheetRows[amaldarRemark]";//������������ת��ҳ��
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
	$msql->query(" update oa_purch_inquiry c SET c.state=0 where c.id='$billId' ");
    $examCode=isset($examCode)?$examCode:"oa_purch_inquiry";//�������ݱ�
    $formName=isset($formName)?$formName:"�ɹ�ѯ�۵�����";//������������
    $returnSta=isset($returnSta)?$returnSta:"δ�ύ";//�ع���������״̬
    $ewf->delWorkFlow($billId,$examCode,$formName,$returnSta);
}
//��������
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
?>