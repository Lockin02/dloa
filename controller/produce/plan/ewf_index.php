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
 * ����������ֶ��б��뺬�� ����״̬��ExaStatus --varchar(15) ״̬Ϊ���༭���½�ʱ����������������ɡ����; �������� ��ExaDT --datetime
 */
$actTo   = isset($_REQUEST['actTo']) ? $_REQUEST['actTo'] : $actTo;
$baseDir = "../../../module/work_flow_examine/";//�������ģ��ĵ�ַ
//������
$ewf = new WorkFlow();
$ewf->setBaseDir($baseDir);

//ѡ������
if($actTo == "ewfSelect") {
	//�����������
	$billArea = isset($_REQUEST['billArea'])?$_REQUEST['billArea'] : $billArea;//��������������
	$ewf->setBillArea($billArea);

	$billId         = isset($_REQUEST['billId']) ? $_REQUEST['billId'] : $billId; //����������ID
	$examCode       = isset($_REQUEST['examCode']) ? $_REQUEST['examCode'] : 'oa_produce_picking';//�������ݱ�
	$passSqlCode    = " update oa_produce_picking c set c.ExaStatus = '���',c.ExaDT = now() ,c.docStatus=2 where c.id='$billId' ";//������ɺ�������
	$disPassSqlCode = " update oa_produce_picking c set c.ExaStatus = '���' ,c.docStatus=3 where c.id='$billId'";//������ظ������
	//����������ɫ�� ��Ŀ���� -- ������
	$proSid   = isset($_REQUEST['proSid']) ? $_REQUEST['proSid'] : $proSid; //��Ŀ������ID  --��Ŀ����
	$proId    = isset($_REQUEST['proId']) ? $_REQUEST['proId'] : $proId; //��ĿID   --��Ŀ���� �� �����������������Ŀ����
	$billDept = isset($_REQUEST['billDept']) ? $_REQUEST['billDept'] : $billDept; //������������������ -- ������

	$ewf->setBillId($billId);
	$ewf->setExamCode($examCode);
	$ewf->setPassSqlCode($passSqlCode);
	$ewf->setDisPassSqlCode($disPassSqlCode);
	$ewf->setProId($proId);
	$ewf->setProSid($proSid);
	$ewf->setBillDept($billDept);
	//��������
	$formName  ="����������������";//������������
	$flowType  = isset($_REQUEST['flowType']) ? $_REQUEST['flowType'] : $flowType;//����������
	$flowMoney = isset($_REQUEST['flowMoney']) ? $_REQUEST['flowMoney'] : (isset($flowMoney) ? $flowMoney : 0);//�������������
	$ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}

//���ɹ�����
if($actTo == "ewfBuild") {
	$billId = isset($_REQUEST['billId']) ? $_REQUEST['billId'] : $billId; //����������ID
	$msql->query(" update oa_produce_picking c SET c.ExaStatus='��������',c.docStatus=1 where c.id='$billId' ");
	$sendToURL = "../../../view/reloadParent.php";//���ɹ���������ת��ҳ��
	$ewf->buildWorkFlow($sendToURL);
}

//����������
if($actTo == "ewfExam") {
	$skey      = isset($_REQUEST['skey']) ? $_REQUEST['skey'] : null;
	$taskId    = isset($_REQUEST['taskId']) ? $_REQUEST['taskId'] : $taskId;
	$spid      = isset($_REQUEST['spid']) ? $_REQUEST['spid'] : $spid;
	$billId    = isset($_REQUEST['billId']) ? $_REQUEST['billId'] : $billId;
	$detailUrl = "../../index1.php?model=produce_plan_picking&action=toView&id=$billId&skey=$skey";//�����鿴����
	$ewf->examWorkFlow($taskId,$spid,$detailUrl);
}

//�ύ����������
if($actTo == "ewfExamSub") {
	$sendToURL = "../../../index1.php?model=produce_plan_picking&action=toAssetAuditTab";//������������ת��ҳ�棬��ת��������ҳ��
	$ewf->examWorkFlowSub($sendToURL);
}

//��������
if($actTo == "ewfView"){
	$taskId = isset($_REQUEST['taskId'])? $_REQUEST['taskId'] : $taskId;
	$ewf->examWorkFlowView($taskId);
}

//ɾ��������
if($actTo == "delWork"){
	$billId    = isset($_REQUEST['billId'])? $_REQUEST['billId'] : $billId;//����������ID
	$sql       = " update oa_produce_picking c SET c.docStatus=0 where c.id='$billId' ";
	$examCode  = "oa_produce_picking";//�������ݱ�
	$formName  = "����������������";//������������
	$returnSta = "���ύ";//�ع���������״̬
	$flag      = isset($_REQUEST['flag'])? $_REQUEST['flag'] : "json";//�ع���������״̬
	$ewf->delWorkFlow($billId,$examCode,$formName,$returnSta,$flag,$sql);
}
?>