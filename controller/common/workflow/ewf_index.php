<?php
$requireType = $_REQUEST['requireType'];
if($requireType=='mobile'){
	$res['msgCode'] = '1';
	$res['msg'] = '�����ɹ�';
	$res['responseCode'] = '';
	echo json_encode( tmp_iconv( $res ) );
	die();
}
/**
 * ����ת�� GBK ת UTF-8
 * @param unknown $str
 * @param string $charset
 * @param string $tocharset
 */
function tmp_iconv($str, $charset = 'GBK', $tocharset = 'UTF-8') {
	return is_array ( $str ) ? array_map ( 'tmp_iconv', $str ) : iconv ( $charset, $tocharset, $str );
}
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
include("../../../model/common/workflow/workflowMailConfig.php");//���������ļ�
include("../../../model/common/workflow/workflowMailInit.php");//�������÷����ļ�
include("../../../cache/DATADICTARR.cache.php");//���������ֵ仺��
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
    $examCode=isset($examCode)?$examCode:"";//�������ݱ�
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
    //��������
    $formName=isset($formName)?$formName:"";//������������
    $flowType=isset($flowType)?$flowType:"";//����������
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//�������������
    $ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}
//���ɹ�����
if($actTo=="ewfBuild"){
    $sendToURL="";//���ɹ���������ת��ҳ��
    $ewf->buildWorkFlow($sendToURL);
}
//����������
if($actTo=="ewfExam"){
    $formName=isset($formName)?$formName:"";
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $code=isset($code)?$code:"";
    $isTemp=isset($isTemp)?$isTemp:0;
    $billId=isset($billId)?$billId:"";//����������ID
    $detailUrl=isset($detailUrl)?$detailUrl:"../../../index1.php?model=common_workflow_workflow&action=toObjInfo&taskId=$taskId&spid=$spid&billId=$billId&formName=$formName&code=$code&isTemp=$isTemp";//�����鿴����
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//�ύ����������
if($actTo=="ewfExamSub"){

	/************************* ������ת·������ ***************************/
	//������Ҫ�����������
	//var_dump($_REQUEST);

	$addStr = null;
	if(isset($_POST['inquirysheet'])){
		$inquirysheetRows = $_POST['inquirysheet'];
		if(is_array($inquirysheetRows)){
			foreach($inquirysheetRows as $key => $val){
				$addStr .= '&row['.$key.']=' . $val;
			}
		}
	}
	if(isset($_POST['lose'])){
		$loseRow = $_POST['lose'];
		if(is_array($loseRow)){
			foreach($loseRow as $key => $val){
				$addStr .= '&row['.$key.']=' . $val;
			}
		}
	}
	if(isset($_POST['examine'])){
		$examineRow = $_POST['examine'];
		if(is_array($examineRow)){
			foreach($examineRow as $key => $val){
				$addStr .= '&row['.$key.']=' . $val;
			}
		}
	}

	if(isset($_POST['leave'])){
		$leaveRow = $_POST['leave'];
		if(is_array($leaveRow)){
			$handContentArr=array();
			$recipientNameArr=array();
			$recipientIdArr=array();
			if(is_array($leaveRow['handitem'])){
				foreach($leaveRow['handitem'] as $iKey=>$iVal){
					if($iVal['handContent']!=''&&$iVal['recipientName']!=''){
						array_push($handContentArr,$iVal['handContent']);
						array_push($recipientNameArr,$iVal['recipientName']);
						array_push($recipientIdArr,$iVal['recipientId']);
					}
				}
			}
			$leaveRow['handContent']=implode('|',$handContentArr);
			$leaveRow['recipientName']=implode(',',$recipientNameArr);
			$leaveRow['recipientId']=implode(',',$recipientIdArr);
			unset($leaveRow['handitem']);
			foreach($leaveRow as $key => $val){
				$addStr .= '&row['.$key.']=' . $val;
			}
		}
	}

	if(isset($_POST['basic'])){//�����ɹ���������
		$basicRow = $_POST['basic'];
		if(is_array($basicRow)){
			$amountAllArr=array();
			$idArr=array();
			$isPurchArr=array();
			if(is_array($basicRow['equipment'])){
				foreach($basicRow['equipment'] as $iKey=>$iVal){
					if($iVal['amountAll']!=''&&$iVal['id']!=''){
						array_push($amountAllArr,$iVal['amountAll']);
						array_push($idArr,$iVal['id']);
						array_push($isPurchArr,$iVal['isPurch']);
					}
				}
			}
			$basicRow['amountAll']=implode(',',$amountAllArr);
			$basicRow['id']=implode(',',$idArr);
			$basicRow['isPurch']=implode(',',$isPurchArr);
			unset($basicRow['equipment']);
			foreach($basicRow as $key => $val){
				$addStr .= '&row['.$key.']=' . $val;
			}
		}
	}

	if(isset($_POST['objInfo'])){
		$oabjInfo = $_POST['objInfo'];
		if(is_array($oabjInfo)){
			foreach($oabjInfo as $key => $val){
				$addStr .= '&row['.$key.']=' . $val;
			}
		}
	}

    $sendToURL="../../../index1.php?model=common_workflow_workflow&action=toLoca&spid=$spid" . $addStr ;//������������ת��ҳ��

	/************************* �����ʼ��������� ***************************/

	$emailBody = null;//�ʼ�����
	$emailSql = null;//�ʼ���ѯsql
	//��ȡ�ʼ�����
    $sql = "select w.Pid,w.code,w.DBTable from flow_step_partent p inner join wf_task w on p.Wf_task_ID = w.task where p.ID = $spid ";
    $msql->query($sql);
    $msql->next_record();
    $taskName = $msql->f("code");
    $pid = $msql->f("Pid");
	$_GET['gdbtable'] = $msql->f("DBTable");


	//�����ʼ���Ϣ
    if(!empty($workflowMailConfig[$taskName])){
    	//���������ϸ����
    	if($workflowMailConfig[$taskName]['detailSet']){

			//���ݶ�ȡ����
			$sql = $workflowMailConfig[$taskName]['selectSql'] . " and c.id = $pid";
		    $result = $msql->query($sql);
			$rows = array();
			while($rows[] = mysql_fetch_array($result,MYSQL_ASSOC)){}
			array_pop($rows);
			mysql_free_result($result);

			//ģ��ƴװ
			$thisFun = $workflowMailConfig[$taskName]['actFunc'];
			$emailBody = $thisFun($rows,$DATADICTARR);

			//����Ĭ�ϲ�ѯҵ��
			$emailSql="select c.id  from ". $taskName . " c
		        left join wf_task b on (c.id=b.pid)
		        where c.id is not null  ";
    	}else{//�����������ϸ����
		    $emailSql="select " . $workflowMailConfig[$taskName]['thisCode'] . "  from ". $taskName . " c
		        left join wf_task b on (c.id=b.pid)
		        where c.id is not null  ";
		    $emailBody= $workflowMailConfig[$taskName]['thisInfo'];
    	}
    }

	if($isEdit == 1){
		return false;
	}
    //�����ύ����
    $ewf->examWorkFlowSub($sendToURL,$emailSql,$emailBody);
}
//��������
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
?>