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
$actTo=isset($actTo)?$actTo:"ewfSelect"; 
$baseDir="../../../module/work_flow_examine/";//�������ģ��ĵ�ַ
//������
$ewf=new WorkFlow();
$ewf->setBaseDir($baseDir);
//ѡ������
if($actTo=="ewfSelect"){
    $billId=isset($billId)?$billId:"";//����������ID
    $examCode=isset($examCode)?$examCode:"xm";//�������ݱ�
    $passSqlCode=isset($passSqlCode)?$passSqlCode:"";//������ɺ�������
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:"";//������ظ������
    //����������ɫ�� ��Ŀ���� -- ������
    $proSid=isset($proSid)?$proSid:"";//��Ŀ������ID  --��Ŀ����
    $proId=isset($proId)?$proId:"";//��ĿID   --��Ŀ���� �� �����������������Ŀ����
    $billDept=isset($billDept)?$billDept:"";//������������������ -- ������
    //
    $ewf->setBillId($billId);
    $ewf->setExamCode($examCode);
    $ewf->setPassSqlCode($passSqlCode);
    $ewf->setDisPassSqlCode($disPassSqlCode);
    $ewf->setProId($proId);
    $ewf->setProSid($proSid);
    $ewf->setBillDept($billDept);//ָ������1,2
    $ewf->setBillUser($billUser);//ָ����Ա1,2
    //��������
    $formName=isset($formName)?$formName:"��Ŀ����";//������������
    $flowType=isset($flowType)?$flowType:"2";//���������� 
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//�������������
    $ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}
//���ɹ�����
if($actTo=="ewfBuild"){
    //$sendToURL=$baseDir."?actTo=swfList";
    $sendToURL="examine_list.php";//���ɹ���������ת��ҳ��
    $ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//����������ID
    $detailUrl=isset($detailUrl)?$detailUrl:"read_pro.php?billId=$billId";//�����鿴����
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//�ύ����������
if($actTo=="ewfExamSub"){
    $sendToURL="examine_list.php";//������������ת��ҳ��
    /*
     * $emailSql �ʼ����ݶ�ȡ��䣬������Ҫ���ʼ���������ʾ��������Ϣ��
     *  ���뺬�У�left join wf_task b on (a.id=b.pid)
     * $emailBody ��ʽ��$emailSql��ȡ�������ֶ����Ƽ��ϡ�$������ɱ������ơ�
     *  ϵͳ���Զ�ƥ���Ӧ$emailSql�����ݣ��������ʼ����ݡ�
     * ������examWorkFlowSub()����� $emailSql ��$emailBody ������
     */
    $emailSql="select u.user_name as username , a.jobname as jobname from hrms_recruit a
        left join user u on (u.user_id=a.userid)
        left join wf_task b on (a.id=b.pid) 
        where a.id is not null  ";
    $emailBody='���ڵ���: $username ְλ���� $jobname';
    $ewf->examWorkFlowSub($sendToURL,$emailSql,$emailBody);
    //$ewf->examWorkFlowSub($sendToURL);
}
//��������
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
//ɾ��������
if($actTo=="delWork"){
    $billId=isset($billId)?$billId:"";//����������ID
    $examCode=isset($examCode)?$examCode:"hrms_recruit";//�������ݱ�
    $formName=isset($formName)?$formName:"��Ƹ����";//������������
    $returnSta=isset($returnSta)?$returnSta:"";//�ع���������״̬
    $delSql=isset($delSql)?$delSql:"";//�ع���������״̬
    $ewf->delWorkFlow($billId,$examCode,$formName,$returnSta,$delSql);
}
?>