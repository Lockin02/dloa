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
    $examCode=isset($examCode)?$examCode:"oa_borrow_borrow";//�������ݱ�
    $passSqlCode=isset($passSqlCode)?$passSqlCode:" update oa_borrow_borrow set ExaStatus='���',changeTips=0,isSubAppChange=0,dealStatus=1 where id='$billId'";//������ɺ�������
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:" update oa_borrow_borrow set ExaStatus = '���',changeTips=0,isSubAppChange=0,dealStatus=1 where id='$billId'";//������ظ������
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
    //
    if($_SESSION['DEPT_ID']==37){
      include("../../../includes/selltype.php" );
      if(!empty($sellEday)){
          foreach($sellEday as $eekey=>$eeval){
              if(in_array($_SESSION['USERNAME'],$eeval)){
                  $ewf->setBillUser($eekey);//
              }
          }
      }
    }
    //��������
    $formName=isset($formName)?$formName:"�����ñ������";//������������
    $flowType=isset($flowType)?$flowType:"";//����������
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//�������������
    $ewf->selectWorkFlow($formName,$flowType,$flowMoney);
    
}
//���ɹ�����
if($actTo=="ewfBuild"){
    //$sendToURL=$baseDir."?actTo=swfList";
    $msql->query(" update oa_borrow_borrow SET ExaStatus='��������' where id='$billId' ");//�ύ������ı�����״̬
    $msql->query(" update oa_borrow_borrow o1 left join oa_borrow_borrow o2 on o1.id=o2.originalId SET o1.ExaStatus='���������' where o2.id ='$billId'");//����ԭ������Ϊ���������״̬
    $msql->query("update oa_borrow_changlog set ExaStatus = '���������' where objType = 'borrow' and tempId='$billId';");
    $sendToURL="../../../index1.php?model=projectmanagent_borrow_borrow&action=toProBorrowList";//���ɹ���������ת��ҳ��
    $ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){
	$skey=isset ($_GET['skey'])?$_GET['skey']:"";
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//����������ID
    $detailUrl=isset($detailUrl)?$detailUrl:"../../../index1.php?model=projectmanagent_borrow_borrow&action=toViewTab&id=$billId&perm=view&skey=$skey";//�����鿴����
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//�ύ����������
if($actTo=="ewfExamSub"){
	$spid = $_POST ['spid'];
	$sendToURL = "../../../index1.php?model=projectmanagent_borrow_borrow&action=toMyBorrowList";
    $ewf->examWorkFlowSub($sendToURL);
}
//��������
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
?>