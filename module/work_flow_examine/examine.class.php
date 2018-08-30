<?php 
/**
*������������ 
*/
class WorkFlow{
//��������
    public $baseDir="";//�ļ�·��
    public $compDir="";
//������ѡ��
    public $examCode="";//��������Ӧ�������ݱ�
    public $billId="";//���ݱ��Ӧ������ID������ʱ��Ҫ��
    public $passSqlCode="";//����ͨ�������ݸ���
    public $disPassSqlCode="";//������ͨ�������ݸ���
    public $proId="";//��Ŀ��Ϣ
    public $proSid="";//��Ŀ��������Ϣ
    public $billDept="";//���ݵ�λID
    public $billArea='';
//����������
    public $taskId="";//������ID
    public $cktype="";//������ID
    public $billUser='';//������Ա
 	public $AppArea='';//����������
 	public $billCompany='';//��˾����
 	public $eUserId='';//ʹ����
//���캯��
    public function __construct() { 
        
    }
//�жϽ���
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
* ѡ������
*
* @param string $formName ����������
* @param string $flowType ����������
* @param string $flowMoney ���������
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
* ѡ������
*
* @param string $sendToURL ����·��
*/
    function buildWorkFlow($sendToURL){
        global $msql;
        global $fsql;
        require("ewf_build.php");
    }
/**
* �����б�
*
* @param string $formName ������
* @param string $flowType ��������
*/
    function workFlowList($formName,$flowType){
        global $msql;
        global $fsql;
        include("ewf_list.php");
    }
/**
* ����������
*
* @param string $taskId ��������
* @param string $spid ��ǰ����ID
* @param string $detailUrl ����������Ϣ·��
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
* ����������
*
* @param string $sendToURL ����·��
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
* ��������
*
* @param string $taskId ��������
*/
    function examWorkFlowView($taskId){
        $this->getDbTable($taskId);
        global $msql;
        global $fsql;
        global $qsql;
        include("ewf_view.php");
    }
    /**
     *ɾ��
     * @param type $bill ����
     * @param type $code ��
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
*����Ĭ�ϸ���������� 
*/
    function setSqlStr(){
        if($this->passSqlCode=="")
            $this->passSqlCode="update $this->examCode set ExaStatus = '���'  where ID='$this->billId'";//����ͨ�������ݸ���
        if($this->disPassSqlCode=="")
            $this->disPassSqlCode="update $this->examCode set ExaStatus = '���'  where ID='$this->billId'";//������ͨ�������ݸ���
    }
    
}
?>