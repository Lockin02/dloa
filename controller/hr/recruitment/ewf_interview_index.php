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
	$examCode=isset($examCode)?$examCode:"";//�������ݱ�
	$passSqlCode=isset($passSqlCode)?$passSqlCode:"update oa_hr_recruitment_interview set ExaStatus ='���',ExaDT = now(),state = 1 where ID='$billId'";//������ɺ�������
	$disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:"update oa_hr_recruitment_interview set ExaStatus ='���',ExaDT = now() where ID='$billId'";//������ظ������
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
	$formName=isset($formName)?$formName:"������������";//������������
	$flowType=isset($flowType)?$flowType:"";//����������
	$flowMoney=isset($flowMoney)?$flowMoney:"0";//�������������
	$ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}
//���ɹ�����
if($actTo=="ewfBuild"){
	//	$msql->query(" update oa_purch_plan_basic c SET  c.ExaStatus='��������' where c.id='$billId' ");
	$sendToURL="../../../index1.php?model=hr_recruitment_interview&action=tolastpage";//���ɹ���������ת��ҳ��
	$ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){

	$billId=isset($billId)?$billId:"";//����������ID
	$detailUrl=isset($detailUrl)?$detailUrl:"../../../index1.php?model=hr_recruitment_interview&action=toRead&id=$billId";//�����鿴����

	$ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//�ύ����������
if($actTo=="ewfExamSub"){
	$sendToURL="../../../index1.php?model=hr_recruitment_interview&action=toAssetAuditTab";//������������ת��ҳ��
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
	$examCode=isset($examCode)?$examCode:"oa_hr_recruitment_interview";//�������ݱ�
	$formName=isset($formName)?$formName:"����������";//������������
	$returnSta=isset($returnSta)?$returnSta:"δ�ύ";//�ع���������״̬
	$ewf->delWorkFlow($billId,$examCode,$formName,$returnSta);
}
?>