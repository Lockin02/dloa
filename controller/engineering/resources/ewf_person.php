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
* ������������ֶ��б��뺬�� ����״̬��ExaStatus --varchar(15) ״̬Ϊ�� �༭���½�ʱ�� ��������  ��� ; �������� ��ExaDT --datetime
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
    $billArea=isset($billArea)?$billArea:"";//����������������
    $ewf->setBillArea($billArea);

    $billId=isset($billId)?$billId:"";//������������ID
    $examCode=isset($examCode)?$examCode:"oa_esm_resource_apply";//�������ݱ�
    $passSqlCode=isset($passSqlCode)?$passSqlCode:" update oa_esm_resource_apply set ExaStatus = '���',confirmStatus = 3,ExaDT = now() where id='$billId' ";//������ɺ�������
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:" update oa_esm_resource_apply set ExaStatus = '���',ExaDT = now() where id='$billId'";//������ظ������
    //����������ɫ�� ��Ŀ���� -- ������
    $proSid=isset($proSid)?$proSid:"";//��Ŀ������ID  --��Ŀ����
    $proId=isset($proId)?$proId:"";//��ĿID   --��Ŀ���� �� �����������������Ŀ������
    $billDept=isset($billDept)?$billDept:$DEPT_ID;//�������������������� -- ������
	$flowDept=isset($flowDept)?$flowDept:'';//$_SESSION['DEPT_ID'];
    //
    $ewf->setBillId($billId);
    $ewf->setExamCode($examCode);
    $ewf->setPassSqlCode($passSqlCode);
    $ewf->setDisPassSqlCode($disPassSqlCode);
    $ewf->setProId($proId);
    $ewf->setProSid($proSid);
    $ewf->setBillDept($billDept);
	
	if(in_array($_SESSION['DEPT_ID'],$NetAppLimitDeptI)||in_array($_SESSION['DEPT_ID'],array(132,133))){
		$flowType=1;//����������
		$flowDept=$_SESSION['DEPT_ID'];
    }else{
		$flowType=2;//����������
		$flowDept='';
	}
    //��������
    $formName=isset($formName)?$formName:"�豸����(����)";//��������������
	$flowType=isset($flowType)?$flowType:'';//����������
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//�������������
    $ewf->selectWorkFlow($formName,$flowType,$flowMoney,$flowDept);
}
//���ɹ�����
if($actTo=="ewfBuild"){
    //$sendToURL=$baseDir."?actTo=swfList";
     $msql->query(" update oa_esm_resource_apply SET ExaStatus='��������' where id='$billId' ");
    $sendToURL="../../../view/reloadParent.php";//���ɹ���������ת��ҳ��
    $ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){
	$skey = isset($_GET['skey'])? $_GET['skey'] : null ;
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//������������ID
    $detailUrl=isset($detailUrl)?$detailUrl:"../../index1.php?model=asset_disposal_scrap&action=init&id=$billId&skey=$skey";//�����鿴����
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//�ύ����������
if($actTo=="ewfExamSub"){
    $sendToURL="../../../index1.php?model=asset_disposal_scrap";//������������ת��ҳ�棬��ת��������ҳ��
    $ewf->examWorkFlowSub($sendToURL);
}
//��������
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
?>