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
	$examCode=isset($examCode)?$examCode:"oa_hr_recommend_bonus";//�������ݱ�
	$passSqlCode=isset($passSqlCode)?$passSqlCode:"update oa_hr_recommend_bonus set ExaStatus ='���',ExaDT = '".date("Y-m-d")."',state = 2  where ID='$billId'";//������ɺ�������
	$disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:"update oa_hr_recommend_bonus set ExaStatus ='���',ExaDT = '".date("Y-m-d")."',state = 1  where ID='$billId'";//������ظ������
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
	$formName=isset($formName)?$formName:"�ڲ��Ƽ�����(�Ƿ�����)";//������������
	$flowType=isset($flowType)?$flowType:"";//����������
	$flowMoney=isset($flowMoney)?$flowMoney:"0";//�������������
	$ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}
//���ɹ�����
if($actTo=="ewfBuild"){
	//	$msql->query(" update oa_purch_plan_basic c SET  c.ExaStatus='��������' where c.id='$billId' ");
	$sendToURL="../../../index1.php?model=hr_recruitment_recomBonus&action=page";//���ɹ���������ת��ҳ��
	$ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){

	$billId=isset($billId)?$billId:"";//����������ID
	$detailUrl=isset($detailUrl)?$detailUrl:"../../../index1.php?model=hr_recruitment_recomBonus&action=toRead&id=$billId";//�����鿴����

	$ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//�ύ����������
if($actTo=="ewfExamSub"){
	$sendToURL="../../../index1.php?model=hr_recruitment_recomBonus&action=toAssetAuditTab";//������������ת��ҳ��
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
	$examCode=isset($examCode)?$examCode:"oa_hr_position_description";//�������ݱ�
	$formName=isset($formName)?$formName:"�ڲ��Ƽ�����(�Ƿ�����)";//������������
	$returnSta=isset($returnSta)?$returnSta:"δ�ύ";//�ع���������״̬
	$ewf->delWorkFlow($billId,$examCode,$formName,$returnSta);
}
?>