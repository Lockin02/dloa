<?php
/**
    *使用方法 
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
* 审批对象表单字段中必须含有 审批状态：ExaStatus --varchar(15) 状态为： 编辑（新建时） 部门审批  完成 ; 审批日期 ：ExaDT --datetime  
*/
$actTo=isset($actTo)?$actTo:"";
$baseDir="../../module/work_flow_examine/";//相对审批模块的地址
//审批类 ExaStatus ExaDT
$ewf=new WorkFlow();
$ewf->setBaseDir($baseDir);
//选择工作流
if($actTo=="ewfSelect"){
    $billId=isset($billId)?$billId:"";//审批表单数据ID
    $examCode=isset($examCode)?$examCode:"notice";//审批数据表
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
    $ewf->setBillArea($holsarea);
    //变量定义
    $formName=isset($formName)?$formName:"公告审批";//工作流表单名称
    $flowType=isset($flowType)?$flowType:"";//工作流类型 
    $flowMoney=isset($flowMoney)?$flowMoney:"";//工作流金额限制
    $flowDept=isset($flowDept)?$flowDept:$DEPT_ID;
    //部门副总
    $sql="select count(*) from user u, department d where FIND_IN_SET(u.USER_ID , d.ViceManager ) and u.USER_ID='$USER_ID'";
    $msql->query($sql);
    $msql->next_record();
    $isViceManager=$msql->f("count(*)")>0?"1":"0";
    //特殊
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
        if($msql->f("jobLevel")==2){//主管级以上
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
        }elseif($msql->f("jobLevel")==2){//主管级以上
            $flowType="1";
        }else{
            if($DEPT_ID=="35"){
                if($holsType=="事假"||$holsType=="病假"||$holsType=="年休假"){
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
                if($holsType=="事假"||($holsType=="病假"&&$holsDate<2)||$holsType=="年休假"){
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
                if($holsType=="事假"||$holsType=="病假"||$holsType=="年休假"){
                    $flowType="0";
                    if($holsType=="事假"&&$holsDate<=3){
                        $flowMoney="10";
                    }elseif($holsType=="病假"&&$holsDate<=5){
                        $flowMoney="10";
                    }else{
                        $flowMoney="20";
                    }
                    if($holsType=="年休假"){
                        $flowMoney="30";
                    }
                }else{
                    $flowType="1";
                }
            }
        }
    }
    ///册除审批
    $sql="select task from wf_task where name='$formName' and code='$examCode' and Pid='$billId'  and Status=0 ";
    $msql->query2($sql);
    $msql->next_record();
    $taskid=$msql->f('task');//审批ID
    if(!empty($taskid)){
	        $sql="SELECT * FROM flow_step where wf_task_id='".$taskid."' and  Flow_name='公告审批' ";
	        $msql->query2($sql);
		    if($msql->num_rows()>0){
		    }else{}
		      //删除工作流
		    $sql="delete from wf_task where task='".$taskid."'";
		    $msql->query2($sql);
		    $sql="delete from flow_step where wf_task_id='".$taskid."'";
		    $msql->query2($sql);
		    $sql="delete from flow_step_partent where wf_task_id='".$taskid."'";
		    $msql->query2($sql);

	 }
   $ewf->selectWorkFlow($formName,$flowType,$flowMoney,$flowDept);
}
//生成工作流
if($actTo=="ewfBuild"){
    //$sendToURL=$baseDir."?actTo=swfList";
    if(!empty($ctype)){
        $sendToURL="../../index1.php?model=info_notice&action=spreload";//生成工作流后跳转的页面
    }else{
        $sendToURL="../../index1.php?model=info_notice&action=spreload";//生成工作流后跳转的页面
    }
    $ewf->buildWorkFlow($sendToURL);
}
//审批工作流
if($actTo=="ewfExam"){
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//审批表单数据ID
    $detailUrl='../../index1.php?model=common_workflow_workflow&action=auditingList';//审批查看内容
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//提交审批工作流
if($actTo=="ewfExamSub"){
	$spid=isset($spid)?$spid:"";
	$result=isset($result)?$result:"";
    $sendToURL="../../index1.php?model=common_workflow_workflow&action=auditingList";//处理审批后跳转的页面
    $ewf->examWorkFlowSub($sendToURL);
}
//审批详情
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
//册除审批详情
if($actTo=="ewfDelView"){
   $taskId=isset($taskId)?$taskId:"";
    $formName=isset($formName)?$formName:"";
    $billId=isset($billId)?$billId:"";//审批表单数据ID
    $ewf->delWorkFlow($billId,$examCode,$formName,$returnSta);
}
?>