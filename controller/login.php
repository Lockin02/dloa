<?php
@session_start ();
class controller_login extends model_base {

    public $show; // ģ����ʾ
    public $file;
    public $login_log;
    public $db;
    public $gl;
    /**
     * ���캯��
     *
     */

    function __construct() {
        parent::__construct();
        $this->db = new mysql();
        $this->gl = new includes_class_global();
    }
    
    

    function c_setlog(){
        $type=$_POST['type'];
        $ip=  $this->gl->getIP();
        if($type=='out'){
            $sql="select log_id from  login_log where user_id='". $_SESSION['USER_ID'] ."' and on_line!='0' order by log_id desc limit 1 ";
            $rlog=$this->db->get_one($sql);
            //�������ر�
            $sql="update  login_log  set on_line='2' , bout='1' , out_time=now() where log_id = '".$rlog['log_id']."'";
            $this->db->query($sql);
        }
    }
    
    
    
    function ck_login(){
        $set=false;
        if(!empty($this->login_log)){
            $temp=array_keys($this->login_log);
            $temp=end($temp);
            //������һ�ε�¼�Ƿ��Ѿ��˳�
            if($this->login_log[$temp]['out']!=1){//δ�˳�
                if($this->login_log[$temp]['ip']!=$this->getIP()){
                    $set=false;
                }
            }else{//���˳�
                $set=true;
            }
        }else{
            $set=true;
        }
        if($set){
            $this->login_log[time()]=array(
                'user'=>$_SESSION['USER_ID']
                ,'ip'=>$this->getIP()
                ,'starttime'=>time()
                ,'startdt'=>date('Y-m-d H:i:s')
            );
            $str="<?php \n";
            $str.='$login_log='.var_export($this->login_log,true).';';
            $str.="\n ?>";
            @file_put_contents($this->file,$str);
        }
        return $set;
    }
    
    /**
     * ��������
     */
    function __destruct() {
        
    }

}
?>