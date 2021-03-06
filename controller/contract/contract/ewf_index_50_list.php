<?php
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
    $examCode=isset($examCode)?$examCode:"oa_contract_contract";//审批数据表
    $passSqlCode=isset($passSqlCode)?$passSqlCode:"update oa_contract_contract set ExaStatus='完成',state = '2',ExaDT = now(),ExaDTOne = now() where id='$billId'";//审批完成后更新语句
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:"update oa_contract_contract set ExaStatus = '打回',ExaDT = now(),state = '0',isSubApp = '0',engConfirm = '0',saleConfirm='0',rdproConfirm='0' where id='$billId'";//审批打回更新语句
    //特殊审批角色： 项目经理 -- 区域经理
    $proSid=isset($proSid)?$proSid:"";//项目任务书ID  --项目经理
    $proId=isset($proId)?$proId:"";//项目ID   --项目经理 （ 查找最新任务书的项目经理）
//    $billDept=isset($billDept)?$billDept:$DEPT_ID;//审批表单数据所属部门 -- 区域经理
    //多部门ID，值='1,2,3',数值为对应的部门ID。只要把对应需要审批的部门ID已这个方式传到$billDept即可。
    $billDept=isset($billDept)?$billDept:"";
    //
    $ewf->setBillId($billId);
    $ewf->setExamCode($examCode);
    $ewf->setPassSqlCode($passSqlCode);
    $ewf->setDisPassSqlCode($disPassSqlCode);
    $ewf->setProId($proId);
    $ewf->setProSid($proSid);
    $ewf->setBillDept($billDept);
    //客户信息
    $sql="select customertype , areaprincipalid  , areacode from oa_contract_contract  where id='$billId' ";
    $msql->query($sql);
    $msql->next_record();
    $cktype=$msql->f('customertype');
    $ewf->setCkType($cktype);
    $ewf->setBillUser($msql->f('areaprincipalid'));//区域审批人
    //变量定义
    $formName=isset($formName)?$formName:"合同审批C";//工作流表单名称
    
    if(in_array($msql->f('areacode'),$spe_bx_flow)){
      $flowType=isset($flowType)?$flowType:"1";//工作流类型-部门
    }else{
      $flowType=isset($flowType)?$flowType:"2";//工作流类型-工程
    }
    
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//工作流金额限制
    $ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}
//生成工作流
if($actTo=="ewfBuild"){
	
	$msql->query(" update oa_contract_contract SET ExaStatus='部门审批',state='1' where id='$billId' ");//提交审批后改变审批状态
    $sendToURL="../../../index1.php?model=contract_contract_contract&action=dealAfterSubAudit&id=".$billId;//生成工作流后跳转的页面
    
    //生成审批流后更新业务状态	--由于去掉成本审核，暂时去掉这步操作 By weijb 2015.10.17
//     $userId = $_SESSION ['USER_ID'];
//     $userName = $_SESSION ['USERNAME'];
//     $costTime = date ( "Y-m-d H:i:s" );

//     $proIdStr=isset($proId)?$proId:"";//更新业务方法的参数 ($isdeff.",".$productLine.",".$costId;)
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
//审批工作流
if($actTo=="ewfExam"){
	$skey=isset($_GET['skey'])?$_GET['skey']:"";
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//审批表单数据ID
    $detailUrl=isset($detailUrl)?$detailUrl:"../../../index1.php?model=contract_contract_contract&action=init&perm=view&id=$billId&skey=$skey";//审批查看内容
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//提交审批工作流
if($actTo=="ewfExamSub"){
    $sendToURL="../../../index1.php?model=contract_contract_contract";//处理审批后跳转的页面
    $ewf->examWorkFlowSub($sendToURL);
}
//审批详情
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
//删除审批流
if($actTo=="delWork"){
    $billId=isset($billId)?$billId:"";//审批表单数据ID
	$msql->query(" update oa_contract_contract c SET c.state='0' where c.id='$billId' ");
    $examCode=isset($examCode)?$examCode:"oa_contract_contract";//审批数据表
    $formName=isset($formName)?$formName:"合同审批C";//工作流表单名称
    $returnSta=isset($returnSta)?$returnSta:"未审批";//回滚单据审批状态
    $flag=isset($flag)?$flag:"json";//回滚单据审批状态
    $ewf->delWorkFlow($billId,$examCode,$formName,$returnSta,$flag);
}
?>