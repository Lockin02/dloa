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
$actTo=isset($actTo)?$actTo:"";
$baseDir="../../../module/work_flow_examine/";//�������ģ��ĵ�ַ
//������
$ewf=new WorkFlow();
$ewf->setBaseDir($baseDir);
//ѡ������
if($actTo=="ewfSelect"){
    $billId=isset($billId)?$billId:"";//������������ID
    $examCode=isset($examCode)?$examCode:"oa_contract_contract";//�������ݱ�
    $passSqlCode=isset($passSqlCode)?$passSqlCode:"update oa_contract_contract set ExaStatus='���',state = '2',ExaDT = now(),ExaDTOne = now() where id='$billId'";//������ɺ�������
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:"update oa_contract_contract set ExaStatus = '���',ExaDT = now(),state = '0',isSubApp = '0',engConfirm = '0',engConfirm = '0',saleConfirm='0',rdproConfirm='0' where id='$billId'";//������ظ������
    //����������ɫ�� ��Ŀ���� -- ������
    $proSid=isset($proSid)?$proSid:"";//��Ŀ������ID  --��Ŀ����
    $proId=isset($proId)?$proId:"";//��ĿID   --��Ŀ���� �� �����������������Ŀ������
//    $billDept=isset($billDept)?$billDept:$DEPT_ID;//�������������������� -- ������
    //�ಿ��ID��ֵ='1,2,3',��ֵΪ��Ӧ�Ĳ���ID��ֻҪ�Ѷ�Ӧ��Ҫ�����Ĳ���ID�������ʽ����$billDept���ɡ�
    $billDept=isset($billDept)?$billDept:"";
    //
    $ewf->setBillId($billId);
    $ewf->setExamCode($examCode);
    $ewf->setPassSqlCode($passSqlCode);
    $ewf->setDisPassSqlCode($disPassSqlCode);
    $ewf->setProId($proId);
    $ewf->setProSid($proSid);
    $ewf->setBillDept($billDept);
    //�ͻ���Ϣ
    $sql="select customertype , areaprincipalid , areacode from oa_contract_contract  where id='$billId' ";
    $msql->query($sql);
    $msql->next_record();
    $cktype=$msql->f('customertype');
    $ewf->setCkType($cktype);
    $ewf->setBillUser($msql->f('areaprincipalid'));//����������
    //��������
    $formName=isset($formName)?$formName:"��ͬ����C";//��������������
    
    if(in_array($msql->f('areacode'),$spe_bx_flow)){
      $flowType=isset($flowType)?$flowType:"1";//����������-����
    }else{
      $flowType=isset($flowType)?$flowType:"2";//����������-����
    }
    
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//�������������
    $ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}
//���ɹ�����
if($actTo=="ewfBuild"){
	//���������������ҵ��״̬
	
	$msql->query(" update oa_contract_contract SET ExaStatus='��������',state='1' where id='$billId' ");//�ύ������ı�����״̬
    $sendToURL="../../../index1.php?model=contract_contract_contract&action=dealAfterSubAudit&id=".$billId;//���ɹ���������ת��ҳ��

    //���������������ҵ��״̬	--����ȥ���ɱ���ˣ���ʱȥ���ⲽ���� By weijb 2015.10.17
//     $userId = $_SESSION ['USER_ID'];
//     $userName = $_SESSION ['USERNAME'];
//     $costTime = date ( "Y-m-d H:i:s" );

//     $proIdStr=isset($proId)?$proId:"";//����ҵ�񷽷��Ĳ��� ($isdeff.",".$productLine.",".$costId;)
//     $proIdArr = explode(',',$proIdStr);

//     $isdeff = $proIdArr[0];
//     $line = $proIdArr[1];
//     $costId = $proIdArr[2];

//     if($isdeff == '2'){
//         $updateSql = "update oa_contract_cost" .
//             " set ExaState = '1',costAppName='".$userName."',costAppId='".$userId."',costAppDate='".$costTime."' " .
//             "where contractId='".$billId."' and productLine='".$line."' ";
//     }else{
//         $updateSql = "update oa_contract_cost" .
//             " set ExaState = '1',costAppName='".$userName."',costAppId='".$userId."',costAppDate='".$costTime."' " .
//             "where id = $costId ";
//     }
//     $msql->query($updateSql);


    $ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){
	$skey=isset($_GET['skey'])?$_GET['skey']:"";
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//������������ID
    $detailUrl=isset($detailUrl)?$detailUrl:"../../../index1.php?model=contract_contract_contract&action=init&perm=view&id=$billId&skey=$skey";//�����鿴����
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//�ύ����������
if($actTo=="ewfExamSub"){
    $sendToURL="../../../index1.php?model=contract_contract_contract";//������������ת��ҳ��
    $ewf->examWorkFlowSub($sendToURL);
}
//��������
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
//ɾ��������
if($actTo=="delWork"){
    $billId=isset($billId)?$billId:"";//������������ID
	$msql->query(" update oa_contract_contract c SET c.state='0' where c.id='$billId' ");
    $examCode=isset($examCode)?$examCode:"oa_contract_contract";//�������ݱ�
    $formName=isset($formName)?$formName:"��ͬ����C";//��������������
    $returnSta=isset($returnSta)?$returnSta:"δ����";//�ع���������״̬
    $flag=isset($flag)?$flag:"json";//�ع���������״̬
    $ewf->delWorkFlow($billId,$examCode,$formName,$returnSta,$flag);
}
?>