<?php
/**
 *ʹ�÷���
 */
include ("../../../includes/db.inc.php");
include ("../../../includes/config.php");
include ("../../../includes/msql.php");
include ("../../../includes/fsql.php");
include ("../../../includes/qsql.php");
include ("../../../includes/util.php");
include ("../../../includes/getUSER_DEPT_ID.php");
include ("../../../module/work_flow_examine/examine.class.php");
/**
 * ������������ֶ��б��뺬�� ����״̬��ExaStatus --varchar(15) ״̬Ϊ�� �༭���½�ʱ�� ��������  ��� ; �������� ��ExaDT --datetime
 */
$actTo = isset ( $actTo ) ? $actTo : "";
$baseDir = "../../../module/work_flow_examine/"; //�������ģ��ĵ�ַ
//������
$ewf = new WorkFlow ();
$ewf->setBaseDir ( $baseDir );
//ѡ������
if ($actTo == "ewfSelect") {
	$billId = isset ( $billId ) ? $billId : ""; //������������ID
	$examCode = isset ( $examCode ) ? $examCode : "oa_purch_apply_basic"; //�������ݱ�
	$passSqlCode = isset ( $passSqlCode ) ? $passSqlCode : " update oa_purch_apply_basic SET signState=0, ExaStatus='���',ExaDT='".date("Y-m-d")."' where id='$billId' "; //������ɺ�������
	$disPassSqlCode = isset ( $disPassSqlCode ) ? $disPassSqlCode : " update oa_purch_apply_basic SET state=3,ExaStatus='���' where id='$billId' "; //������ظ������
	//����������ɫ�� ��Ŀ���� -- ������
	$proSid = isset ( $proSid ) ? $proSid : ""; //��Ŀ������ID  --��Ŀ����
	$proId = isset ( $proId ) ? $proId : ""; //��ĿID   --��Ŀ���� �� �����������������Ŀ������
	$billDept = isset ( $billDept ) ? $billDept : $DEPT_ID; //�������������������� -- ������
	//
	$ewf->setBillId ( $billId );
	$ewf->setExamCode ( $examCode );
	$ewf->setPassSqlCode ( $passSqlCode );
	$ewf->setDisPassSqlCode ( $disPassSqlCode );
	$ewf->setProId ( $proId );
	$ewf->setProSid ( $proSid );
	$ewf->setBillDept ( $billDept );
	//��������
	$formName = isset ( $formName ) ? $formName : "�ɹ���ͬ����"; //��������������
	$flowType = isset ( $flowType ) ? $flowType : ""; //����������
	$flowMoney = isset ( $flowMoney ) ? $flowMoney : "0"; //�������������
	$ewf->selectWorkFlow ( $formName, $flowType, $flowMoney );
}
//���ɹ�����
if ($actTo == "ewfBuild") {
	$msql->query ( " update oa_purch_apply_basic c SET c.state=1, c.ExaStatus='��������' where c.id='$billId' " );
	$sendToURL = "../../../index1.php?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab1"; //���ɹ���������ת��ҳ��
	$ewf->buildWorkFlow ( $sendToURL );
}
//����������
if ($actTo == "ewfExam") {
    $skey=isset($_GET['skey'])?$_GET['skey']:"";
	$taskId = isset ( $taskId ) ? $taskId : "";
	$spid = isset ( $spid ) ? $spid : "";
	$billId = isset ( $billId ) ? $billId : ""; //������������ID
	$detailUrl = isset ( $detailUrl ) ? $detailUrl : "../../../index1.php?model=purchase_contract_purchasecontract&action=toTabView&id=$billId&readType=exam&skey=$skey"; //�����鿴����
	$ewf->examWorkFlow ( $taskId, $spid, $detailUrl );
}
//�ύ����������
if ($actTo == "ewfExamSub") {
	$spid = $_POST ['spid'];
	$sendToURL = "../../../index1.php?model=purchase_change_contractchange&action=confirmChangeToApprovalNo&spid=$spid";
	$ewf->examWorkFlowSub ( $sendToURL );
}
//��������
if ($actTo == "ewfView") {
	$taskId = isset ( $taskId ) ? $taskId : "";
	$ewf->examWorkFlowView ( $taskId );
}
?>