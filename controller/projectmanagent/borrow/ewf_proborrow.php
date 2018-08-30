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
extract($_GET);
$actTo=isset($actTo)?$actTo:"";
$baseDir="../../../module/work_flow_examine/";//相对审批模块的地址
//审批类
$ewf=new WorkFlow();
$ewf->setBaseDir($baseDir);
//选择工作流
if($actTo=="ewfSelect"){
    $billId=isset($billId)?$billId:"";//审批表单数据ID
    $examCode=isset($examCode)?$examCode:"oa_borrow_borrow";//审批数据表
    $passSqlCode=isset($passSqlCode)?$passSqlCode:" update oa_borrow_borrow set ExaStatus = '完成',ExaDT = '".date("Y-m-d")."' where id='$billId' ";//审批完成后更新语句
    $disPassSqlCode=isset($disPassSqlCode)?$disPassSqlCode:" update oa_borrow_borrow set ExaStatus = '未审批',ExaDT = null where id='$billId'";//审批打回更新语句
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
    //客户信息
    $sql="SELECT typeone , u.dept_id  , c.areaid  FROM oa_borrow_borrow b
left join customer c on (c.name=b.customername)
left join user u on (b.createid=u.user_id)
where b.id='$billId' ";
    $msql->query($sql);
    $msql->next_record();
    $cktype=$msql->f('typeone');
    $billDept=$msql->f('dept_id');
    $ewf->setCkType($cktype);
    $ewf->setBillDept($billDept);
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
    //变量定义
    $formName=isset($formName)?$formName:"员工借试用";//工作流表单名称
    $flowType=isset($flowType)?$flowType:"";//工作流类型
    $flowMoney=isset($flowMoney)?$flowMoney:"0";//工作流金额限制
    if($_SESSION['DEPT_ID']!='37'){
      $ewf->selectWorkFlow($formName,'1',$flowMoney);
    }else{
      $ewf->selectWorkFlow($formName,'2',$flowMoney);
    }

}
//生成工作流
if($actTo=="ewfBuild"){
    //$sendToURL=$baseDir."?actTo=swfList";
    $msql->query(" update oa_borrow_borrow SET ExaStatus='部门审批',dealStatus = 1,ExaDT = null where id='$billId' ");
    $sendToURL="../../../view/reloadParent.php";//生成工作流后跳转的页面
    $ewf->buildWorkFlow($sendToURL);
}
//审批工作流
if($actTo=="ewfExam"){
	$skey=isset($_GET['skey'])?$_GET['skey']:"";
    $taskId=isset($taskId)?$taskId:"";
    $spid=isset($spid)?$spid:"";
    $billId=isset($billId)?$billId:"";//审批表单数据ID
    $detailUrl=isset($detailUrl)?$detailUrl:"../../../index1.php?model=projectmanagent_borrow_borrow&action=proView&perm=view&id=$billId&skey=$skey";//审批查看内容
    $ewf->examWorkFlow($taskId,$spid,$detailUrl);
}
//提交审批工作流
if($actTo=="ewfExamSub"){
    $sendToURL="../../../index1.php?model=projectmanagent_borrow_borrow&action=toProBorrowAll";//处理审批后跳转的页面，跳转到待审批页面
    $ewf->examWorkFlowSub($sendToURL);
}
//审批详情
if($actTo=="ewfView"){
    $taskId=isset($taskId)?$taskId:"";
    $ewf->examWorkFlowView($taskId);
}
?>