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
    $examCode=isset($examCode)?$examCode:"oa_contract_contract";//�������ݱ�
    $passSqlCode=isset($passSqlCode)?$passSqlCode:"update oa_contract_contract set ExaStatus='���',state = '2',isSubAppChange='0' where id='$billId'";//������ɺ�������
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:"update oa_contract_contract set ExaStatus = '���',isSubAppChange='0' where id='$billId'";//������ظ������
    //����������ɫ�� ��Ŀ���� -- ������
    $proSid=isset($proSid)?$proSid:"";//��Ŀ������ID  --��Ŀ����
    $proId=isset($proId)?$proId:"";//��ĿID   --��Ŀ���� �� �����������������Ŀ����
//    $billDept=isset($billDept)?$billDept:$DEPT_ID;//������������������ -- ������
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
    $sql="select customertype , areaprincipalid   , areacode from oa_contract_contract  where id='$billId' ";
    $msql->query($sql);
    $msql->next_record();
    $cktype=$msql->f('customertype');
    $ewf->setCkType($cktype);
    $ewf->setBillUser($msql->f('areaprincipalid'));//����������
    //��������
    $formName=isset($formName)?$formName:"�������ͬ�������";//������������

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

    $msql->query(" update oa_contract_contract SET ExaStatus='��������' where id='$billId' ");//�ύ������ı�����״̬
    $msql->query(" update oa_contract_contract o1 left join oa_contract_contract o2 on o1.id=o2.originalId SET o1.ExaStatus='���������',o1.isBecome='1' where o2.id ='$billId'"); //����ԭ������Ϊ���������״̬
    $sendToURL="../../../index1.php?model=contract_contract_contract&action=dealAfterSubAudit&id=".$billId;//���ɹ���������ת��ҳ��

    $ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){
    $skey=isset($_GET['skey'])?$_GET['skey']:"";
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//����������ID
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
?>