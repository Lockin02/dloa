<?php
/**
 *ʹ�÷���
 */
include("../../../includes/db.inc.php");
include("../../../includes/config.php");
include("../../../includes/msql.php");
include("../../../includes/fsql.php");
include("../../../includes/qsql.php");
include("../../../includes/util.php");
include("../../../includes/getUSER_DEPT_ID.php");
include("../../../module/work_flow_examine/examine.class.php");
/**
 * ����������ֶ��б��뺬�� ����״̬��ExaStatus --varchar(15) ״̬Ϊ�� �༭���½�ʱ�� ��������  ��� ; �������� ��ExaDT --datetime
 */
extract($_GET);
$actTo = isset($actTo) ? $actTo : "";
$baseDir = "../../../module/work_flow_examine/";//�������ģ��ĵ�ַ
//������
$ewf = new WorkFlow();
$ewf->setBaseDir($baseDir);
//ѡ������
if ($actTo == "ewfSelect") {
    $billId = isset($billId) ? $billId : "";//����������ID
    $examCode = isset($examCode) ? $examCode : "oa_borrow_equ_link";//�������ݱ�
    $passSqlCode = isset($passSqlCode) ? $passSqlCode : "update oa_borrow_equ_link set ExaStatus = '���',ExaDT = now() where id='$billId' ";//������ɺ�������
    $disPassSqlCode = isset($disPassSqlCode) ? $disPassSqlCode : "update oa_borrow_equ_link set ExaStatus = '���',ExaDT = now() where id='$billId'";//������ظ������
    //����������ɫ�� ��Ŀ���� -- ������
    $proSid = isset($proSid) ? $proSid : "";//��Ŀ������ID  --��Ŀ����
    $proId = isset($proId) ? $proId : "";//��ĿID   --��Ŀ���� �� �����������������Ŀ����
    $billDept = isset($billDept) ? $billDept : $DEPT_ID;//������������������ -- ������
    $billCompany = isset($billCompany) ? $billCompany : "";//���빫˾����
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
    $formName = isset($formName) ? $formName : "�����÷������ϱ��";//������������
    $flowType = isset($flowType) ? $flowType : "";//����������
    $flowMoney = isset($flowMoney) ? $flowMoney : "0";//�������������
    $flowDept = isset($flowDept) ? $flowDept : "0";//����������
    $ewf->selectWorkFlow($formName, $flowType, $flowMoney);
}
//���ɹ�����
if ($actTo == "ewfBuild") {
    //$sendToURL=$baseDir."?actTo=swfList";
    $msql->query("update
            oa_borrow_equ_link o1 left join oa_borrow_equ_link o2 on o1.id=o2.originalId
        SET o1.ExaStatus='���������',o2.ExaStatus='���������' where o2.id ='$billId'");//����ԭ������Ϊ���������״̬
    $msql->query(" update oa_borrow_borrow set needSalesConfirm=0,salesConfirmId=0 where needSalesConfirm = 3 and salesConfirmId = '{$billId}';");
    $sendToURL = "../../../view/reloadParent.php";//���ɹ���������ת��ҳ��
    $ewf->buildWorkFlow($sendToURL);
}
//����������
if ($actTo == "ewfExam") {
    $skey = isset($_GET['skey']) ? $_GET['skey'] : null;
    $taskId = isset($taskId) ? $taskId : "";
    $spid = isset($spid) ? $spid : "";
    $billId = isset($billId) ? $billId : "";//����������ID
    $detailUrl = isset($detailUrl) ? $detailUrl : "../../index1.php?model=contract_other_other&action=init&id=$billId&skey=$skey";//�����鿴����
    $ewf->examWorkFlow($taskId, $spid, $detailUrl);
}
//�ύ����������
if ($actTo == "ewfExamSub") {
    $sendToURL = "../../../index1.php?model=contract_other_other";//������������ת��ҳ�棬��ת��������ҳ��
    $ewf->examWorkFlowSub($sendToURL);
}
//��������
if ($actTo == "ewfView") {
    $taskId = isset($taskId) ? $taskId : "";
    $ewf->examWorkFlowView($taskId);
}
//ɾ��������
if ($actTo == "delWork") {
    $billId = isset($billId) ? $billId : "";//����������ID
    $sql = " update oa_borrow_equ_link c SET c.status='0' where c.id='$billId' ";
    $examCode = isset($examCode) ? $examCode : "oa_borrow_equ_link";//�������ݱ�
    $formName = isset($formName) ? $formName : "�����÷������ϱ��";//������������
    $returnSta = isset($returnSta) ? $returnSta : "���";//�ع���������״̬
    $flag = isset($flag) ? $flag : "json";//�ع���������״̬
    $ewf->delWorkFlow($billId, $examCode, $formName, $returnSta, $flag, $sql);
}