<?php
$requireType = $_REQUEST['requireType'];
//file_put_contents('../../../temp/wf/s'.time().'.txt',implode(',',$_REQUEST));
file_put_contents('../../../temp/wf/s'.time().'.txt',json_encode($_REQUEST));
if($requireType=='mobile'){
	$sessionId=$_REQUEST["sessionId"];
	session_id($sessionId);
	@session_start();
	$mbError='';
	$mbType=$_REQUEST["mbType"];
	$taskId=$_REQUEST["taskId"];
	$commentMsg=$_REQUEST["content"];
	$sids=$_REQUEST["sid"];
	$relusts=$_REQUEST["result"];
	if($relusts=="ok"){
		$actionName="ͬ��";
	}else{
		$actionName="��ͬ��";
	}
	if(!$taskId&&!$sids&&!$sessionId){
		$res['msgCode'] =1;
		$res['msg'] ='����ʧ�ܣ�';
		$res['responseCode']=2;
		echo json_encode( tmp_iconv( $res ) );
		exit();
	}
	if($mbType=='2'&&$taskId&&$sids){
		$postData=array (
				"cmd"  => "com.actionsoft.apps.sys.Audit_audit",
				"sid"   => $sids,
				"taskInstId"  =>$taskId,
				"actionName"   =>  tmp_iconv( $actionName ),
				"commentMsg"   =>$commentMsg
			) ;
		$url='http://172.16.1.101/r/w?';
		$postFields = empty($postData) ? "" : http_build_query($postData);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$result = curl_exec($ch);
		curl_close($ch);
		$AWSI =json_decode($result, true);
		if($AWSI&&is_array($AWSI)&&$AWSI['responseCode']=="1")
		{
			$res['msgCode'] ="1";
			$res['msg'] = '�����ɹ���';
			$res['responseCode']="1";
			$res['url']="";
		}else{
			$res['msgCode'] ="1";
			$res['msg'] = $AWSI['errorMsg'];
			$res['responseCode'] ="2";
			$res['url']="";
		}
		file_put_contents('../../../temp/wf/'.$_SESSION['USER_ID'].'_'.time().'.txt', json_encode($res));
		echo json_encode( ( $res ) );
		die();
		
	}
	
	
	if(!$_SESSION['USER_ID']){
		$res['msgCode'] =1;
		$res['msg'] ='����ʧ�ܣ�';
		$res['responseCode']=2;
		echo json_encode( tmp_iconv( $res ) );
		exit();
	}
	if(!$_REQUEST["spid"]||!$_REQUEST["result"]){
		$res['msgCode'] = '1';
		$res['msg'] = '����ʧ�ܣ�';
		$res['responseCode'] =3;
		echo json_encode( tmp_iconv( $res ) );
		exit();
	}
}
$_POST['content']=iconv('UTF-8', 'GB2312', $_POST['content']);

/**
 * ����ת�� GBK ת UTF-8
 * @param unknown $str
 * @param string $charset
 * @param string $tocharset
 */
function tmp_iconv($str, $charset = 'GBK', $tocharset = 'UTF-8') {
	return is_array ( $str ) ? array_map ( 'tmp_iconv', $str ) : iconv ( $charset, $tocharset, $str );
}
function mb_iconv($str, $charset = 'UTF-8', $tocharset = 'GBK') {
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
    $detailUrl=isset($detailUrl)?$detailUrl:"index_mb.php?model=common_workflow_workflow&action=toObjInfo&taskId=$taskId&spid=$spid&billId=$billId&formName=$formName&code=$code&isTemp=$isTemp&sessionId=".session_id(); //�����鿴����
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

    $sendToURL="index_mb.php?model=common_workflow_workflow&action=toLoca&spid=$spid" . $addStr."&sessionId=".session_id();//������������ת��ҳ��

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