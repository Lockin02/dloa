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
    $examCode=isset($examCode)?$examCode:"oa_finance_payablesapply";//审批数据表
    $passSqlCode=isset($passSqlCode)?$passSqlCode:"";//审批完成后更新语句
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:"";//审批打回更新语句
    //特殊审批角色： 项目经理 -- 区域经理
    $proSid=isset($proSid)?$proSid:"";//项目任务书ID  --项目经理
    $proId=isset($proId)?$proId:"";//项目ID   --项目经理 （ 查找最新任务书的项目经理）
    $billDept=isset($billDept)?$billDept:$DEPT_ID;//审批表单数据所属部门 -- 区域经理
    $billCompany=isset($billCompany)?$billCompany:"";//传入公司参数
    //
    $ewf->setBillId($billId);
    $ewf->setExamCode($examCode);
    $ewf->setPassSqlCode($passSqlCode);
    $ewf->setDisPassSqlCode($disPassSqlCode);
    $ewf->setProId($proId);
    $ewf->setProSid($proSid);
    $ewf->setBillDept($billDept);
    $ewf->setBillCompany($billCompany);
    //变量定义
    $formName=isset($formName)?$formName:"采购退款申请";//工作流表单名称
    $flowType=isset($flowType)?$flowType:"";//工作流类型
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//工作流金额限制
    $ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}
//生成工作流
if($actTo=="ewfBuild"){
    //$sendToURL=$baseDir."?actTo=swfList";
    $sendToURL="../../../view/reloadParent.php";//生成工作流后跳转的页面
    $ewf->buildWorkFlow($sendToURL);
}
//审批工作流
if($actTo=="ewfExam"){
	$skey = isset($_GET['skey'])? $_GET['skey'] : null ;
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//审批表单数据ID
    $detailUrl=isset($detailUrl)?$detailUrl:"../../../index1.php?model=finance_payablesapply_payablesapply&action=initAuditing&id=$billId&skey=$skey";//审批查看内容
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//提交审批工作流
if($actTo=="ewfExamSub"){
    $sendToURL="../../../index1.php?model=finance_payablesapply_payablesapply&action=auditundo";//处理审批后跳转的页面
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
	$examCode=isset($examCode)?$examCode:"oa_finance_payablesapply";//审批数据表
	$formName=isset($formName)?$formName:"采购退款申请";//工作流表单名称
	$returnSta=isset($returnSta)?$returnSta:"待提交";//回滚单据审批状态
	$flag=isset($flag)?$flag:"json";//回滚单据审批状态

	$ewf->delWorkFlow($billId,$examCode,$formName,$returnSta,$flag);
}
?>