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
 * 审批对象表单字段中必须含有 审批状态：ExaStatus --varchar(15) 状态为：编辑（新建时）、部门审批、完成、打回; 审批日期 ：ExaDT --datetime
 */
$actTo   = isset($_REQUEST['actTo']) ? $_REQUEST['actTo'] : $actTo;
$baseDir = "../../../module/work_flow_examine/";//相对审批模块的地址
//审批类
$ewf = new WorkFlow();
$ewf->setBaseDir($baseDir);

//选择工作流
if($actTo == "ewfSelect") {
	//传入区域参数
	$billArea = isset($_REQUEST['billArea'])?$_REQUEST['billArea'] : $billArea;//审批表单所属区域
	$ewf->setBillArea($billArea);

	$billId         = isset($_REQUEST['billId']) ? $_REQUEST['billId'] : $billId; //审批表单数据ID
	$examCode       = isset($_REQUEST['examCode']) ? $_REQUEST['examCode'] : 'oa_produce_picking';//审批数据表
	$passSqlCode    = " update oa_produce_picking c set c.ExaStatus = '完成',c.ExaDT = now() ,c.docStatus=2 where c.id='$billId' ";//审批完成后更新语句
	$disPassSqlCode = " update oa_produce_picking c set c.ExaStatus = '打回' ,c.docStatus=3 where c.id='$billId'";//审批打回更新语句
	//特殊审批角色： 项目经理 -- 区域经理
	$proSid   = isset($_REQUEST['proSid']) ? $_REQUEST['proSid'] : $proSid; //项目任务书ID  --项目经理
	$proId    = isset($_REQUEST['proId']) ? $_REQUEST['proId'] : $proId; //项目ID   --项目经理 （ 查找最新任务书的项目经理）
	$billDept = isset($_REQUEST['billDept']) ? $_REQUEST['billDept'] : $billDept; //审批表单数据所属部门 -- 区域经理

	$ewf->setBillId($billId);
	$ewf->setExamCode($examCode);
	$ewf->setPassSqlCode($passSqlCode);
	$ewf->setDisPassSqlCode($disPassSqlCode);
	$ewf->setProId($proId);
	$ewf->setProSid($proSid);
	$ewf->setBillDept($billDept);
	//变量定义
	$formName  ="生产领料申请审批";//工作流表单名称
	$flowType  = isset($_REQUEST['flowType']) ? $_REQUEST['flowType'] : $flowType;//工作流类型
	$flowMoney = isset($_REQUEST['flowMoney']) ? $_REQUEST['flowMoney'] : (isset($flowMoney) ? $flowMoney : 0);//工作流金额限制
	$ewf->selectWorkFlow($formName,$flowType,$flowMoney);
}

//生成工作流
if($actTo == "ewfBuild") {
	$billId = isset($_REQUEST['billId']) ? $_REQUEST['billId'] : $billId; //审批表单数据ID
	$msql->query(" update oa_produce_picking c SET c.ExaStatus='部门审批',c.docStatus=1 where c.id='$billId' ");
	$sendToURL = "../../../view/reloadParent.php";//生成工作流后跳转的页面
	$ewf->buildWorkFlow($sendToURL);
}

//审批工作流
if($actTo == "ewfExam") {
	$skey      = isset($_REQUEST['skey']) ? $_REQUEST['skey'] : null;
	$taskId    = isset($_REQUEST['taskId']) ? $_REQUEST['taskId'] : $taskId;
	$spid      = isset($_REQUEST['spid']) ? $_REQUEST['spid'] : $spid;
	$billId    = isset($_REQUEST['billId']) ? $_REQUEST['billId'] : $billId;
	$detailUrl = "../../index1.php?model=produce_plan_picking&action=toView&id=$billId&skey=$skey";//审批查看内容
	$ewf->examWorkFlow($taskId,$spid,$detailUrl);
}

//提交审批工作流
if($actTo == "ewfExamSub") {
	$sendToURL = "../../../index1.php?model=produce_plan_picking&action=toAssetAuditTab";//处理审批后跳转的页面，跳转到待审批页面
	$ewf->examWorkFlowSub($sendToURL);
}

//审批详情
if($actTo == "ewfView"){
	$taskId = isset($_REQUEST['taskId'])? $_REQUEST['taskId'] : $taskId;
	$ewf->examWorkFlowView($taskId);
}

//删除审批流
if($actTo == "delWork"){
	$billId    = isset($_REQUEST['billId'])? $_REQUEST['billId'] : $billId;//审批表单数据ID
	$sql       = " update oa_produce_picking c SET c.docStatus=0 where c.id='$billId' ";
	$examCode  = "oa_produce_picking";//审批数据表
	$formName  = "生产领料申请审批";//工作流表单名称
	$returnSta = "待提交";//回滚单据审批状态
	$flag      = isset($_REQUEST['flag'])? $_REQUEST['flag'] : "json";//回滚单据审批状态
	$ewf->delWorkFlow($billId,$examCode,$formName,$returnSta,$flag,$sql);
}
?>