<?php 
/**
*工作流审批类 
*/
class WorkFlow{
//变量声明
    public $baseDir="";//文件路径
    public $compDir="";
//工作流选择
    public $examCode="";//工作流对应审批数据表
    public $billId="";//数据表对应的数据ID（审批时需要）
    public $passSqlCode="";//审批通过后数据更新
    public $disPassSqlCode="";//审批不通过的数据更新
    public $proId="";//项目信息
    public $proSid="";//项目任务书信息
    public $billDept="";//数据单位ID
    public $billArea='';
//审批工作流
    public $taskId="";//工作流ID
    public $cktype="";//工作流ID
    public $billUser='';//审批人员
 	public $AppArea='';//审批工作流
 	public $billCompany='';//公司属性
 	public $eUserId='';//使用人
//构造函数
    public function __construct() { 
        
    }
//判断进程
    function selectProcess(){
        
    }
    
    function getDbTable($objid,$type='wf'){
        global $msql;
        if($type=='wf'){
            $sql="select DBTable from wf_task where task='".$objid."' ";
            $msql->query($sql);
            $msql->next_record();
            define('DB_TABLE',$msql->f('DBTable'));
        }elseif($type=='sid'){
            $sql="select DBTable from wf_task t
                left join flow_step_partent p on (t.task=p.wf_task_id) where p.id='".$objid."' ";
            $msql->query($sql);
            $msql->next_record();
            define('DB_TABLE',$msql->f('DBTable'));
        }
        
    }
/**
* 选择工作流
*
* @param string $formName 工作流表单名
* @param string $flowType 工作流类型
* @param string $flowMoney 工作流金额
*/
    function selectWorkFlow($formName,$flowType,$flowMoney,$flowDept=""){
        global $msql;
        global $fsql;
        $this->setSqlStr();
        if(!empty($flowDept)){
        	$this->setBillDept($flowDept);
        }
        include("ewf.php");
    }
/**
* 选择工作流
*
* @param string $sendToURL 返回路径
*/
    function buildWorkFlow($sendToURL){
        global $msql;
        global $fsql;
        require("ewf_build.php");
    }
/**
* 审批列表
*
* @param string $formName 申请人
* @param string $flowType 审批单号
*/
    function workFlowList($formName,$flowType){
        global $msql;
        global $fsql;
        include("ewf_list.php");
    }
/**
* 审批工作流
*
* @param string $taskId 审批单号
* @param string $spid 当前步骤ID
* @param string $detailUrl 审批详情信息路径
*/
    function examWorkFlow($taskId,$spid,$detailUrl){
        $this->getDbTable($taskId);
        global $msql;
        global $fsql;
        global $qsql;
        $detailUrl=$detailUrl.'&gdbtable='.DB_TABLE;
        include("ewf_exam.php");
    }
/**
* 审批工作流
*
* @param string $sendToURL 返回路径
*/
    function examWorkFlowSub($sendToURL,$emailSql='',$emailBody=''){
        $this->getDbTable($_POST['spid'],'sid');
        $_POST['gdbtable']=DB_TABLE;
        global $msql;
        global $fsql;
        global $qsql;
        include("ewf_exam_sub.php");
    }
/**
* 审批详情
*
* @param string $taskId 审批单号
*/
    function examWorkFlowView($taskId){
        $this->getDbTable($taskId);
        global $msql;
        global $fsql;
        global $qsql;
        include("ewf_view.php");
    }
    /**
     *删除
     * @param type $bill 单号
     * @param type $code 表
     * $flag url | json
     */
    function delWorkFlow($billId,$examCode,$formName,$returnSta,$flag='url',$delSql=""){
        global $msql;
        global $fsql;
        global $qsql;
        include("ewf_del.php");
    }
    function setExamCode($val){
        $this->examCode=$val;
    }
    function setBillId($val){
        $this->billId=$val;
    }
    function setPassSqlCode($val){
        $this->passSqlCode=$val;
    }
    function setDisPassSqlCode($val){
        $this->disPassSqlCode=$val;
    }
    function setProId($val){
        $this->proId=$val;
    }
    function setProSid($val){
        $this->proSid=$val;
    }
    function setBillDept($val){
        $this->billDept=$val;
    }
    function setBillArea($val){
        $this->billArea=$val;
    }
    function setAppArea($val){
        $this->AppArea=$val;
    }
    function setTaskId($val){
        $this->taskId=$val;
    }
    function setCkType($val){
        $this->cktype=$val;
    }
    function setBillUser($val){
        $this->billUser=$val;
    }
    function setBaseDir($val){
        $this->baseDir=$val;
        $this->compDir = substr( $val, 0, strpos( $val, "module" ) ) ;
    }
    function setBillCompany($val){
        $this->billCompany=$val;
    }
    function seteUserId($val){
        $this->eUserId=$val;
    }
    
/**
*设置默认更新数据语句 
*/
    function setSqlStr(){
        if($this->passSqlCode=="")
            $this->passSqlCode="update $this->examCode set ExaStatus = '完成'  where ID='$this->billId'";//审批通过后数据更新
        if($this->disPassSqlCode=="")
            $this->disPassSqlCode="update $this->examCode set ExaStatus = '打回'  where ID='$this->billId'";//审批不通过的数据更新
    }
    
}
?>