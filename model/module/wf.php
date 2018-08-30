<?php
class model_module_wf extends model_base {

    public $page;
    public $num;
    public $start;
    public $db;
    public $comsta;
    public $glo; //�������

    //*******************************���캯��***********************************
    function __construct(){
        parent::__construct();
        $this->db = new mysql();
        $this->glo = new includes_class_global();
    }
    /**
     *���Ӳ���
     * @return string 
     */
    function model_add_exa_step(){
        $ai=$_POST['ai'];
        $awf=$_POST['awf'];
        $awfspid=$_POST['awfspid'];
        $sql="";
        try {
            $this->db->query("START TRANSACTION");
            $ins = $item + 1;
            if ($taskra ['finish'] == "") {
                $tmp = 'null';
            } else {
                $tmp = "'$taskra[finish]'";
            }
            $query = "UPDATE
            flow_step s
            left join flow_step_partent p  on(s.wf_task_id=p.wf_task_id)
            set s.SmallID=s.SmallID+1,s.Step=s.Step+1,s.StepID=s.StepID+1
            where s.id>p.stepid and  p.id='".$awfspid."'";
            $this->db->query($query);
            $Stid = $StepID + 1;
            if ($rc ['Endtime'] == "") {
                $tmp = 'null';
            } else {
                $tmp = "'$rc[Endtime]'";
            }
            $query = "INSERT INTO flow_step  (`SmallID` ,`Electric` ,`Wf_task_ID` ,`Flow_id` ,`Step` ,`StepID` ,`Item` ,`User` 
                ,`PRCS_ITEM` ,`formid` ,`Flag` ,`status` ,`Flow_head` ,`Flow_name` ,`secrecy` ,`speed` ,`quickpipe` ,`quickreason` 
                ,`sendread` ,`Flow_prop` ,`PRCS_ALERT` ,`Flow_doc` ,`Flow_type` ,`ATTACHMENT_NAME` ,`ATTACHMENT_ID` ,`ATTACHMENT_MEMO` 
                ,`Start` ,`Endtime` ,`Pid` ,`Flock` ,`passlimit` ,`isReceive` ,`isEditPage` ) 
                SELECT s.`SmallID`+1 ,s.`Electric` ,s.`Wf_task_ID` ,s.`Flow_id` ,s.`Step`+1 ,s.`StepID`+1 ,'�Զ���������' ,'".$ai."' 
                    ,s.`PRCS_ITEM` ,s.`formid` ,s.`Flag`+1 ,s.`status` ,s.`Flow_head` ,s.`Flow_name` ,s.`secrecy` ,s.`speed` 
                    ,s.`quickpipe` ,s.`quickreason` ,s.`sendread` ,s.`Flow_prop` ,s.`PRCS_ALERT` ,s.`Flow_doc` ,s.`Flow_type` 
                    ,s.`ATTACHMENT_NAME` ,s.`ATTACHMENT_ID` ,s.`ATTACHMENT_MEMO` ,s.`Start` ,s.`Endtime` ,s.`Pid` ,s.`Flock` 
                    ,s.`passlimit` ,s.`isReceive` ,s.`isEditPage` FROM  flow_step s left join flow_step_partent p  on(s.id=p.stepid) 
                where p.id='".$awfspid."'";
            $this->db->query($query);
            $this->db->query("COMMIT");
            return '1';
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            return '0';
        }
    }
    /**
     *������������
     * @param type $task 
     $task=array(
        'pid'=>'Դ��ID',
        'code'=>'���ݱ���',
        'formname'=>'��������������',
        'infomation'=>'ժҪ',
        'objCode' => 'Դ����',
        'objName' => 'Դ������',
        'objCustomer' => 'Դ���ͻ�',
        'objAmount' => 'Դ�����',
        'objUser'=>'Դ��ҵ��ԱID',
        'objUserName'=>'Դ��ҵ��Ա����',
        'step'=>array(
                0=>array(
                    'userid'=>'guoquan.xie'
                    ,'item'=>'�����쵼'
                )
        )
     );
     */
    function build_wf($task,$restype='msg'){
        include_once(WEB_TOR."model/common/workflow/workflowInfoConfig.php");
        $res=null;
        try {
            $firstChecker=null;
            
            $this->db->query("START TRANSACTION");
            //���
            if(empty($task['pid'])||empty($task['code'])||empty($task['formname'])||empty($task['step'])){
                throw new Exception("pid��code��step��formnameΪ��");
            }
            $sql="SELECT form_id , pass_sql , dispass_sql  FROM flow_form_type where form_name ='".$task['formname']."' ";
            $ck=$this->db->get_one($sql);
            if(empty($ck['form_id'])){
                throw new Exception("������δ����");
            }else{
                $task['formid']=$ck['form_id'];
                $task['pass_sql']=str_replace('$pid', $task['pid'], $ck['pass_sql']);
                $task['dispass_sql']=str_replace('$pid', $task['pid'], $ck['dispass_sql']);
            }
            //��ʷ��������״̬
            $sql="select count(task) as am from wf_task where name='".$task['formname']."' 
                and code='".$task['code']."' and Pid='".$task['pid']."' and Status='ok' 
                and DBTable='".$_SESSION["COM_BRN_SQL"]."' ";
            $ck=$this->db->get_one($sql);
            if($ck['am']>0){
                throw new Exception("����ͬ����Ϊ��".$task['formname']." ���ţ�".$task['pid']." ����Ч��������");
            }
            //�������
            $wf_config=$workflowInfoConfig[$task['code']];
            $wf_config_arr=null;
            if (!empty($wf_config)) {
                $sql = "select ".$wf_config['thisCode']." from ".$task['code']." c where c.id='".$task['pid']."' ";
                $wf_config_arr = $this->db->get_one($sql);
            }
            if (is_array($wf_config_arr)&&!empty($wf_config_arr)) {
                $task['infomation'] = $wf_config['thisInfo'];
                foreach($wf_config_arr as $key=>$val){
                    $task['infomation'] = str_replace('$' . $key, $val, $task['infomation']);
                    $objkey=array_search($key, $wf_config['objArr']);
                    if($objkey!==false){
                        $task[$objkey]=$val;
                    }
                }
            } else {
                throw new Exception("�����������ݲ����ڣ�");
            }
            //ҵ����Ա��
            if(!empty($task['objUser'])){
                $wfu=$task['objUser'];
                $wfun=$task['objUserName'];
            }else{
                $wfu=$_SESSION['USER_ID'];
                $wfun=$_SESSION['USER_NAME'];
            }
            $sql = "update ".$task['code']." set ExaStatus = '��������' , ExaDT = now() where ID='".$task['pid']."' ";
            $this->db->query_exc($sql);
            //end �������
            $sql="insert into wf_task ( Creator , Enter_user , name , code , form 
                        , start , train , Status , Pid 
                        , PassSqlCode , DisPassSqlCode , DBTable 
                        , objUser , objUserName , infomation , objCode 
                        , objName , objCustomer , objAmount
                    ) values ( '".$wfu."' , '".$wfu."'  , '".$task['formname']."' , '".$task['code']."' , '".$task['formid']."' 
                        , '".date('Y-m-d H:i:s')."', '".$task['formid']."','ok','".$task['pid']."'
                        ,'".addslashes($task['pass_sql'])."','".addslashes($task['dispass_sql'])."','".$_SESSION["COM_BRN_SQL"]."' 
                        , '".$wfu."', '".$wfun."', '".$task['infomation']."', '".$task['objCode']."'
                        , '".$task['objName']."', '".$task['objCustomer']."', '".$task['objAmount']."'
                    )";
            $this->db->query_exc($sql);
            $task['taskid'] = $this->db->insert_id();
            $stepi=1;
            foreach($task['step'] as $key=>$val){
                $sql = "INSERT into flow_step set SmallID='".$stepi."',Wf_task_ID='".$task['taskid']."',Flow_id=''
                        ,Step='".$stepi."',StepID='".$stepi."',Item='".$val['item']."'
                        ,User='".$val['userid']."',PRCS_ITEM='',Flag='".($stepi-1)."',status='ok'
                        ,Flow_head='".$task['pid']."',Flow_name='".$task['formname']."',secrecy='1',speed='1'
                        ,quickpipe='0',quickreason='',sendread='',Flow_prop=''
                        ,PRCS_ALERT='',Flow_doc='1',Flow_type='1',ATTACHMENT_MEMO=''
                        ,Start='".date('Y-m-d H:i:s')."'";
                $this->db->query_exc($sql);
                $stepid= $this->db->insert_id();
                if($stepi===1){
                    $firstChecker = $val['userid'];
                    $tempsteparr=explode(",",$val['userid']);
                    $tempsteparr=array_unique($tempsteparr);
                    foreach( $tempsteparr as $vval){
                        if($vval!=""){
                            /**** ����޸�4 ��������ֶε�ֵ *****/
                            $sql="INSERT into flow_step_partent set StepID='".$stepid."',SmallID='".$stepi."'
                            ,Wf_task_ID='".$task['taskid']."',User='".$vval."',Flag='0',START='".date('Y-m-d H:i:s')."'
                            ,isReceive='',isEditPage=''" ;
                            $this->db->query_exc($sql);
                        }
                    }
                }
                $stepi++;
            }
            if(!empty($firstChecker)){
                $emailClass = new includes_class_sendmail();
                $firstChecker=  explode(',', $firstChecker);
                $emailadd= $this->glo->get_email($firstChecker);
                $title='�������ѣ�'.$task['formname'].'-'.$task['objCode'];
                $body='���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;�����µ���������Ҫ������<br />'
                        .'&nbsp;&nbsp;&nbsp;&nbsp;�������ţ�'.$task['objCode'];
                if(!empty($task['infomation'])){
                    $body.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$task['infomation'];
                }
                $emailClass ->send($title, $body, $emailadd);
            }
            $this->db->query("COMMIT");
            $res='���������ɳɹ���';
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $res=$e->getMessage();
            if($restype=='error'){
                throw new Exception($res);
            }
        }
        return $res;
    }
}
?>