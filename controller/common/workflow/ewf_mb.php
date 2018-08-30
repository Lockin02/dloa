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
		$actionName="同意";
	}else{
		$actionName="不同意";
	}
	if(!$taskId&&!$sids&&!$sessionId){
		$res['msgCode'] =1;
		$res['msg'] ='审批失败！';
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
			$res['msg'] = '审批成功！';
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
		$res['msg'] ='审批失败！';
		$res['responseCode']=2;
		echo json_encode( tmp_iconv( $res ) );
		exit();
	}
	if(!$_REQUEST["spid"]||!$_REQUEST["result"]){
		$res['msgCode'] = '1';
		$res['msg'] = '审批失败！';
		$res['responseCode'] =3;
		echo json_encode( tmp_iconv( $res ) );
		exit();
	}
}
$_POST['content']=iconv('UTF-8', 'GB2312', $_POST['content']);

/**
 * 编码转换 GBK 转 UTF-8
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
    *使用方法
*/
include("../../../includes/db.inc.php");
include("../../../includes/config.php");
include("../../../includes/msql.php");
include("../../../includes/fsql.php");
include("../../../includes/qsql.php");
include("../../../includes/util.php" );
include("../../../includes/getUSER_DEPT_ID.php" );
include("../../../module/work_flow_examine/examine.class.php");
include("../../../model/common/workflow/workflowMailConfig.php");//引入配置文件
include("../../../model/common/workflow/workflowMailInit.php");//引入配置方法文件
include("../../../cache/DATADICTARR.cache.php");//引入数据字典缓存


/**
* 审批对象表单字段中必须含有 审批状态：ExaStatus --varchar(15) 状态为： 编辑（新建时） 部门审批  完成 ; 审批日期 ：ExaDT --datetime
*/
$actTo=isset($actTo)?$actTo:"";
$baseDir="../../../module/work_flow_examine/";//相对审批模块的地址
//审批类
$ewf=new WorkFlow();
$ewf->setBaseDir($baseDir);
//选择工作流
if($actTo=="ewfSelect"){
    $billId=isset($billId)?$billId:"";//审批表单数据ID
    $examCode=isset($examCode)?$examCode:"";//审批数据表
    $passSqlCode=isset($passSqlCode)?$passSqlCode:"";//审批完成后更新语句
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:"";//审批打回更新语句
    //特殊审批角色： 项目经理 -- 区域经理
    $proSid=isset($proSid)?$proSid:"";//项目任务书ID  --项目经理
    $proId=isset($proId)?$proId:"";//项目ID   --项目经理 （ 查找最新任务书的项目经理）
    $billDept=isset($billDept)?$billDept:$DEPT_ID;//审批表单数据所属部门 -- 区域经理
    //
    $ewf->setBillId($billId);
    $ewf->setExamCode($examCode);
    $ewf->setPassSqlCode($passSqlCode);
    $ewf->setDisPassSqlCode($disPassSqlCode);
    $ewf->setProId($proId);
    $ewf->setProSid($proSid);
    $ewf->setBillDept($billDept);
    //变量定义
    $formName=isset($formName)?$formName:"";//工作流表单名称
    $flowType=isset($flowType)?$flowType:"";//工作流类型
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//工作流金额限制
    $ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}
//生成工作流
if($actTo=="ewfBuild"){
    $sendToURL="";//生成工作流后跳转的页面
    $ewf->buildWorkFlow($sendToURL);
}
//审批工作流
if($actTo=="ewfExam"){
    $formName=isset($formName)?$formName:"";
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $code=isset($code)?$code:"";
    $isTemp=isset($isTemp)?$isTemp:0;
    $billId=isset($billId)?$billId:"";//审批表单数据ID
    $detailUrl=isset($detailUrl)?$detailUrl:"index_mb.php?model=common_workflow_workflow&action=toObjInfo&taskId=$taskId&spid=$spid&billId=$billId&formName=$formName&code=$code&isTemp=$isTemp&sessionId=".session_id(); //审批查看内容
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//提交审批工作流
if($actTo=="ewfExamSub"){

	/************************* 审批跳转路径设置 ***************************/
	//额外需要处理数据添加
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

	if(isset($_POST['basic'])){//生产采购申请审批
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

    $sendToURL="index_mb.php?model=common_workflow_workflow&action=toLoca&spid=$spid" . $addStr."&sessionId=".session_id();//处理审批后跳转的页面

	/************************* 审批邮件发送设置 ***************************/

	$emailBody = null;//邮件内容
	$emailSql = null;//邮件查询sql
	//获取邮件配置
    $sql = "select w.Pid,w.code,w.DBTable from flow_step_partent p inner join wf_task w on p.Wf_task_ID = w.task where p.ID = $spid ";
    $msql->query($sql);
    $msql->next_record();
    $taskName = $msql->f("code");
    $pid = $msql->f("Pid");
	$_GET['gdbtable'] = $msql->f("DBTable");


	//配置邮件信息
    if(!empty($workflowMailConfig[$taskName])){
    	//如果存在详细设置
    	if($workflowMailConfig[$taskName]['detailSet']){

			//数据读取部分
			$sql = $workflowMailConfig[$taskName]['selectSql'] . " and c.id = $pid";
		    $result = $msql->query($sql);
			$rows = array();
			while($rows[] = mysql_fetch_array($result,MYSQL_ASSOC)){}
			array_pop($rows);
			mysql_free_result($result);

			//模板拼装
			$thisFun = $workflowMailConfig[$taskName]['actFunc'];
			$emailBody = $thisFun($rows,$DATADICTARR);

			//设置默认查询业务
			$emailSql="select c.id  from ". $taskName . " c
		        left join wf_task b on (c.id=b.pid)
		        where c.id is not null  ";
    	}else{//如果不存在详细设置
		    $emailSql="select " . $workflowMailConfig[$taskName]['thisCode'] . "  from ". $taskName . " c
		        left join wf_task b on (c.id=b.pid)
		        where c.id is not null  ";
		    $emailBody= $workflowMailConfig[$taskName]['thisInfo'];
    	}
    }

	if($isEdit == 1){
		return false;
	}
    //进入提交方法
    $ewf->examWorkFlowSub($sendToURL,$emailSql,$emailBody);
}
//审批详情
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
?>