<?php
/**
    *ʹ�÷��� 
*/
include("../../includes/db.inc.php");
include("../../includes/config.php");
include("../../includes/msql.php");
include("../../includes/fsql.php");
include("../../includes/qsql.php");
include("../../includes/util.php" );
include("../../includes/getUSER_DEPT_ID.php" );
include("../../module/work_flow_examine/examine.class.php");
/**
* ����������ֶ��б��뺬�� ����״̬��ExaStatus --varchar(15) ״̬Ϊ�� �༭���½�ʱ�� ��������  ��� ; �������� ��ExaDT --datetime  
*/
$actTo=isset($actTo)?$actTo:"";
$baseDir="../../module/work_flow_examine/";//�������ģ��ĵ�ַ
//������ ExaStatus ExaDT
$ewf=new WorkFlow();
$ewf->setBaseDir($baseDir);
//ѡ������
if($actTo=="ewfSelect"){
    $billId=isset($billId)?$billId:"";//����������ID
    $examCode=isset($examCode)?$examCode:"notice";//�������ݱ�
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
    $ewf->setBillArea($holsarea);
    //��������
    $formName=isset($formName)?$formName:"��������";//������������
    $flowType=isset($flowType)?$flowType:"";//���������� 
    $flowMoney=isset($flowMoney)?$flowMoney:"";//�������������
    $flowDept=isset($flowDept)?$flowDept:$DEPT_ID;
    //���Ÿ���
    $sql="select count(*) from user u, department d where FIND_IN_SET(u.USER_ID , d.ViceManager ) and u.USER_ID='$USER_ID'";
    $msql->query($sql);
    $msql->next_record();
    $isViceManager=$msql->f("count(*)")>0?"1":"0";
    //����
    $sql="select h.jobLevel , d.MajorId , d.ViceManager , d.Leader_id , d.dept_id as adp
        , u.assistantdept 
    from  hrms h,  department d , user u where u.USER_ID = h.USER_ID and u.DEPT_ID=d.DEPT_ID and u.USER_ID='$USER_ID'";
    $msql->query($sql);
    $msql->next_record();
    if($msql->f('assistantdept')){
        $flowDept=$msql->f('assistantdept');
    }else{
        $flowDept=$msql->f('adp');
    }
    if($_SESSION['COM_BRN_PT']=='br'||$_SESSION['COM_BRN_PT']=='sy'){
        if($msql->f("jobLevel")==2){//���ܼ�����
            $flowType="2";
        }else{
            $flowType="1";
            $flowMoney=$holsDate*10;
        }
    }else{
        $proflag=($_SESSION['DEPT_ID']=='35'&&$_SESSION['COM_BRN_PT']=='dl' )?'yes':'no';
        if($proflag=='yes'){
            $flowType='0';
            $flowMoney=$holsDate;
        }elseif($msql->f("jobLevel")==2){//���ܼ�����
            $flowType="1";
        }else{
            if($DEPT_ID=="35"){
                if($holsType=="�¼�"||$holsType=="����"||$holsType=="���ݼ�"){
                    $flowType="0";
                    if($holsDate<=7){
                        $flowMoney="10";
                    }else{
                        $flowMoney="20";
                    }
                }else{
                    $flowType="1";
                }
            }elseif($DEPT_ID=="34"||$DEPT_ID=="91"){
                if($holsType=="�¼�"||($holsType=="����"&&$holsDate<2)||$holsType=="���ݼ�"){
                    $flowType="0";
                    if($holsDate<=5){
                        $flowMoney="10";
                    }else{
                        $flowMoney="20";
                    }
                }else{
                    $flowType="1";
                }
            }else{
                if($holsType=="�¼�"||$holsType=="����"||$holsType=="���ݼ�"){
                    $flowType="0";
                    if($holsType=="�¼�"&&$holsDate<=3){
                        $flowMoney="10";
                    }elseif($holsType=="����"&&$holsDate<=5){
                        $flowMoney="10";
                    }else{
                        $flowMoney="20";
                    }
                    if($holsType=="���ݼ�"){
                        $flowMoney="30";
                    }
                }else{
                    $flowType="1";
                }
            }
        }
    }
    ///�������
    $sql="select task from wf_task where name='$formName' and code='$examCode' and Pid='$billId'  and Status=0 ";
    $msql->query2($sql);
    $msql->next_record();
    $taskid=$msql->f('task');//����ID
    if(!empty($taskid)){
	        $sql="SELECT * FROM flow_step where wf_task_id='".$taskid."' and  Flow_name='��������' ";
	        $msql->query2($sql);
		    if($msql->num_rows()>0){
		    }else{}
		      //ɾ��������
		    $sql="delete from wf_task where task='".$taskid."'";
		    $msql->query2($sql);
		    $sql="delete from flow_step where wf_task_id='".$taskid."'";
		    $msql->query2($sql);
		    $sql="delete from flow_step_partent where wf_task_id='".$taskid."'";
		    $msql->query2($sql);

	 }
   $ewf->selectWorkFlow($formName,$flowType,$flowMoney,$flowDept);
}
//���ɹ�����
if($actTo=="ewfBuild"){
    //$sendToURL=$baseDir."?actTo=swfList";
    if(!empty($ctype)){
        $sendToURL="../../index1.php?model=info_notice&action=spreload";//���ɹ���������ת��ҳ��
    }else{
        $sendToURL="../../index1.php?model=info_notice&action=spreload";//���ɹ���������ת��ҳ��
    }
    $ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//����������ID
    $detailUrl='../../index1.php?model=common_workflow_workflow&action=auditingList';//�����鿴����
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//�ύ����������
if($actTo=="ewfExamSub"){
	$spid=isset($spid)?$spid:"";
	$result=isset($result)?$result:"";
    $sendToURL="../../index1.php?model=common_workflow_workflow&action=auditingList";//������������ת��ҳ��
    $ewf->examWorkFlowSub($sendToURL);
}
//��������
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
//�����������
if($actTo=="ewfDelView"){
   $taskId=isset($taskId)?$taskId:"";
    $formName=isset($formName)?$formName:"";
    $billId=isset($billId)?$billId:"";//����������ID
    $ewf->delWorkFlow($billId,$examCode,$formName,$returnSta);
}
?>