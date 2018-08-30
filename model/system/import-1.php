<?php
class model_system_import extends model_base {

    public $page;
    public $num;
    public $start;
    public $db;
    public $emailClass;
    public $im;
    public $flag;

    //*******************************���캯��***********************************
    function __construct() {
        parent::__construct();
        $this->db = new mysql();
        //$this->im = new includes_class_ImInterface();
        //$this->model_ini();
        //$this->emailClass = new includes_class_sendmail();
        $this->flag='hr_up';
    }
    function model_sub(){
        set_time_limit(600);
        $ckt=$_POST['ckt'];
        $flag=$_POST['flag'];
        try{
            $excelfilename='attachment/sys_import/'.$ckt.".xls";
            if(!file_exists($excelfilename)){
                throw new Exception('File does not exist');
            }else{
                //��ȡexcel��Ϣ
                include('includes/classes/excel.php');
                $excel = Excel::getInstance();
                $excel->setFile(WEB_TOR.$excelfilename);
                $excel->Open();
                $excel->setSheet();
                $excelFields = $excel->getFields();
                $excelArr=$excel->getAllData();
                $excel->Close();
                if(!empty($excelArr)){
                    if($flag=='hrupdate'){
                        if(!in_array('Ա����', $excelFields)||!in_array('����', $excelFields)
                            ||!in_array('�������´�', $excelFields)||!in_array('ְλ', $excelFields))
                        {
                            throw new Exception('Update failed');
                        }
                        if(count($excelArr)&&!empty($excelArr)){
                            foreach($excelArr['Ա����'] as $key=>$val ){
                                $infoE[$val]['name']=$excelArr['����'][$key];
                                $infoE[$val]['blg']=$excelArr['�������´�'][$key];
                                $infoE[$val]['pos']=$excelArr['ְλ'][$key];
                                $infoE[$val]['flag']='0';
                            }
                        }
                        $areaArr=array();
                        $sql="select name , id  from area where del='0'";
                        $query=$this->db->query_exc($sql);
                        while($row=$this->db->fetch_array($query)){
                            $areaArr[$row['name']]=$row['id'];
                        }
                        $jobArr=array();
                        $sql="select name , id  from user_jobs where dept_id='35' ";
                        $query=$this->db->query_exc($sql);
                        while($row=$this->db->fetch_array($query)){
                            $jobArr[$row['name']]=$row['id'];
                        }
                        $sql="select u.email , h.usercard
                            from user u
                                left join hrms h on (u.user_id=h.user_id)
                            where u.user_id=h.user_id and u.dept_id='35'";
                        $query=$this->db->query_exc($sql);
                        while($row=$this->db->fetch_array($query)){
                            if(array_key_exists($row['usercard'],$infoE)){
                                $infoE[$row['usercard']]['flag']='1';
                            }
                        }
                        if(!empty($infoE)){
                            foreach($infoE as $key=>$val){
                                $sql="update
                                    user u
                                        left join hrms h on (u.user_id=h.user_id)
                                        left join ecard e on (u.user_id=e.user_id)
                                    set
                                        u.user_priv='".$jobArr[$val['pos']]."'
                                        ,u.jobs_id='".$jobArr[$val['pos']]."'
                                        ,h.certificate='".$val['pos']."'
                                        ,e.ministration='".$val['pos']."'
                                        ,u.area='".$areaArr[$val['blg']]."'
                                    
                                    where
                                        h.usercard='".$key."'";
                                $this->db->query_exc($sql);
                            }
                        }
                    }elseif($flag=='ecard-up'){
                        foreach($excelArr['Ա����'] as $key=>$val ){
                            $sql="update ecard set 
                                    tel_no_dept='".$excelArr['��˾�绰'][$key]."'
                                    ,mobile1='".$excelArr['�ֻ�����'][$key]."'
                                  where 
                                    User_id='".$excelArr['Ա����'][$key]."'
                                ";
                            $this->db->query_exc($sql);
                        }
                    }elseif($flag=='hr_up'){
                         if(count($excelArr)&&!empty($excelArr)){
                            $sexInfo=array('��'=>'0','Ů'=>'1');
                            $eduInfo=array("ר��"=>'1',"����"=>'2',"����"=>'3',"��ר"=>'4',"��ר"=>'5'
                                ,"����"=>'6',"˶ʿ"=>'7',"�о���"=>'8',"��ʿ"=>'9',"δ�����������"=>'10');
                             foreach($excelArr['Ա����'] as $key=>$val ){
                                 try{
                                    $this->db->query("START TRANSACTION");
                                    $sql="update hrms h
                                            left join user u on (h.user_id=u.user_id)
                                        set 
                                            u.sex='".$sexInfo[$excelArr['�Ա�'][$key]]."'
                                            ,h.education='".$eduInfo[$excelArr['ѧ��'][$key]]."'
                                            ,h.school='".$excelArr['��ҵѧУ'][$key]."'
                                            ,h.major='".$excelArr['רҵ'][$key]."'
                                        where h.usercard='".$excelArr['Ա����'][$key]."' ";
                                    $this->db->query_exc($sql);
                                    $this->db->query("COMMIT");
                                    }catch(Exception $e){
                                    $this->db->query("ROLLBACK");
                                    return $e->getMessage();
                                }
                             }
                         }
                    }elseif($flag=='brn_ini'){
                        if(count($excelArr)&&!empty($excelArr)){
                            //��ȡ����/����/ְλ����
                            $deptInfo=array();
                            $areaInfo=array(''=>'1');
                            $jobInfo=array();
                            $sexInfo=array('��'=>'0','Ů'=>'1');
                            $eduInfo=array("ר��"=>'1',"����"=>'2',"����"=>'3',"��ר"=>'4',"��ר"=>'5'
                                ,"����"=>'6',"˶ʿ"=>'7',"�о���"=>'8',"��ʿ"=>'9',"δ�����������"=>'10');
                            $sql="select dept_id , dept_name from department ";
                            $query=$this->db->query($sql);
                            while($row=$this->db->fetch_array($query)){
                                $deptInfo[$row['dept_name']]=$row['dept_id'];
                            }
                            $sql="select name , id from area where del='0' ";
                            $query=$this->db->query($sql);
                            while($row=$this->db->fetch_array($query)){
                                $areaInfo[$row['name']]=$row['id'];
                            }
                            $sql="select name , id from user_jobs  ";
                            $query=$this->db->query($sql);
                            while($row=$this->db->fetch_array($query)){
                                $jobInfo[$row['name']]=$row['id'];
                            }
                            $pw=md5('bettercomm.net');
                            foreach($excelArr['Ա����'] as $key=>$val ){
                                try{
                                    $this->db->query("START TRANSACTION");
                                    $tempdept=array();
                                    $tempdeptname='';
                                    $tempdept=$excelArr['����'][$key];
                                    $tempdeptname=$tempdept;
                                    $excelArr['Ա����'][$key]='0'.$excelArr['Ա����'][$key];
                                    $excelArr['�����ַ'][$key]=$excelArr['�����ַ'][$key].'@bettercomm.net';
                                    //Ĭ����ͨԱ��
                                    if(empty($excelArr['ְλ'][$key])){
                                        $excelArr['ְλ'][$key]='��ͨԱ��';
                                    }
                                    //ְλ����
                                    if(empty($jobInfo[$excelArr['ְλ'][$key]])){
                                        $this->db->query_exc("insert into user_jobs(name,dept_id,level,func_id_str) 
                                            values
                                            ('".$excelArr['ְλ'][$key]."','".$deptInfo[$tempdeptname]."','0','')");
                                        $jobinsertid = $this->db->insert_id();
                                        $this->db->query_exc("insert into user_priv(priv_name,user_priv)values('".$excelArr['ְλ'][$key]."','$jobinsertid')");
                                        $jobInfo[$excelArr['ְλ'][$key]]=$jobinsertid;
                                    }
                                    //�û���
                                     $sql="insert into user(
                                            user_id,user_name,password,logname,user_priv
                                            ,jobs_id,dept_id,sex,email
                                            ,area,company,creatdt,creator
                                        )values(
                                            '".$excelArr['Ա����'][$key]."','".$excelArr['����'][$key]."','".$pw."'
                                            ,'".$excelArr['Ա����'][$key]."','".$jobInfo[$excelArr['ְλ'][$key]]."','".$jobInfo[$excelArr['ְλ'][$key]]."',
                                            '".$deptInfo[$tempdeptname]."','".$sexInfo[$excelArr['�Ա�'][$key]]."','".$excelArr['�����ַ'][$key]."',
                                            '".$areaInfo[$excelArr['����'][$key]]."','��Ѷ',now(),'".$_SESSION['USER_ID']."'
                                        )";
                                    $this->db->query_exc($sql);
                                    //���±�
                                     $sql="insert into hrms
                                        (USER_ID,CARD_NO,COME_DATE,JOIN_DATE,EDUCATION
                                        ,CERTIFICATE,School,Major,Address
                                        ,Account,AccCard,Bank,Tele,Native
                                        ,Email,Creator,CreateDT,Remark
                                        ,contflagb,contflage,usercard
                                        )
                                    values
    ('".$excelArr['Ա����'][$key]."','".$excelArr['���֤'][$key]."','".$excelArr['��ְ����'][$key]."','".$excelArr['ת������'][$key]."'
    ,'".$eduInfo[$excelArr['ѧ��'][$key]]."','".$excelArr['ְλ'][$key]."','".$excelArr['��ҵѧУ'][$key]."','".$excelArr['רҵ'][$key]."'
    ,'".$excelArr['��ͥ��ַ'][$key]."','".$excelArr['�����ʺ�'][$key]."','".$excelArr['����'][$key]."','".$excelArr['������'][$key]."'
    ,'".$excelArr['�ֻ�����'][$key]."','".$excelArr['������'][$key]."','".$excelArr['�����ַ'][$key]."','".$_SESSION['USER_ID']."'
    ,now(),'".$excelArr['��ע'][$key]."','".$excelArr['��ͬ��ʼ'][$key]."','".$excelArr['��ͬ��ֹ'][$key]."','".$excelArr['Ա����'][$key]."'
        )";
                                    $this->db->query_exc($sql);
                                     $sql="insert into ecard
                                    (
                                        User_id,Name,Sex,Depart,Http,Email,Company,CreateDT
                                        ,tel_no_dept,mobile1
                                    )values(
                                        '".$excelArr['Ա����'][$key]."',
                                        '".$excelArr['����'][$key]."',
                                        '".$sexInfo[$excelArr['�Ա�'][$key]]."',
                                        '".$deptInfo[$tempdeptname]."',
                                        'http://www.bettercomm.net',
                                        '".$excelArr['�����ַ'][$key]."',
                                        '',
                                        '".date('Y-m-d H:i:s')."',
                                        '".$excelArr['��˾�绰'][$key]."',
                                        '".$excelArr['�ֻ�����'][$key]."'
                                        )";
                                    $this->db->query_exc($sql);
                                    $this->db->query("COMMIT");
                                    
                                    
                                    //=========����IM�û�==============
                                    
                                    $data = array(
                                                    'COM_BRN_CN'=>'���ݱ�Ѷ',
                                                    'DEPT_NAME'=>$excelArr['����'][$key],
                                                    'USER_ID'=>$excelArr['Ա����'][$key],
                                                    'USER_NAME'=>$excelArr['����'][$key],
                                                    'PASSWORD'=>$pw,
                                                    'SEX'=>$sexInfo[$excelArr['�Ա�'][$key]],
                                                    'EMAIL'=>$excelArr['�����ַ'][$key],
                                                    'JOBS_NAME'=>$excelArr['ְλ'][$key],
                                                    'Mobile'=>'',
                                                    'Phone'=>''
                                                );
                                    $msg=$this->im->add_user($data);
                                    if ($msg)
                                    {
                                        $creat_im = '����IM�ʺųɹ�';
                                    }else{
                                        $creat_im = '����IM�ʺ�ʧ��';
                                    }
                                    $msg='good!';
                                }catch(Exception $e){
                                    $this->db->query("ROLLBACK");
                                    $this->pf($sql.$e->getMessage());
                                    return $e->getMessage();
                                }
                            }
                        }
                        return un_iconv($msg);
                    }elseif($flag=='zgs_ini'){
                        $filestr='';
                        if(count($excelArr)&&!empty($excelArr)){
                            //��ȡ����/����/ְλ����
                            $deptInfo=array();
                            $areaInfo=array(''=>'1');
                            $jobInfo=array();
                            $sexInfo=array('��'=>'0','Ů'=>'1');
                            $eduInfo=array("ר��"=>'1',"����"=>'2',"����"=>'3',"��ר"=>'4',"��ר"=>'5'
                                ,"����"=>'6',"˶ʿ"=>'7',"�о���"=>'8',"��ʿ"=>'9',"δ�����������"=>'10');
                            $sql="select dept_id , dept_name from department ";
                            $query=$this->db->query($sql);
                            while($row=$this->db->fetch_array($query)){
                                $deptInfo[$row['dept_name']]=$row['dept_id'];
                            }
                            $sql="select name , id from area where del='0' ";
                            $query=$this->db->query($sql);
                            while($row=$this->db->fetch_array($query)){
                                $areaInfo[$row['name']]=$row['id'];
                            }
                            $sql="select name , id from user_jobs  ";
                            $query=$this->db->query($sql);
                            while($row=$this->db->fetch_array($query)){
                                $jobInfo[$row['name']]=$row['id'];
                            }
                            $sql="SELECT namecn , namept FROM branch_info where type='1' ";
                            $query=$this->db->query($sql);
                            while($row=$this->db->fetch_array($query)){
                                $brInfo[$row['namecn']]=$row['namept'];
                            }
                            $pw=md5('dinglicom');
                            foreach($excelArr['Ա����'] as $key=>$val ){
                                try{
                                    $this->db->query("START TRANSACTION");
                                    $tempdept=array();
                                    $tempdeptname='';
                                    $tempdept=$excelArr['����'][$key];
                                    $tempdeptname=$tempdept;
                                    $excelArr['Ա����'][$key]='0'.$excelArr['Ա����'][$key];
                                    $excelArr['�����ַ'][$key]=$excelArr['OA�û���'][$key].'@dinglicom.com';
                                    //Ĭ����ͨԱ��
                                    if(empty($excelArr['ְλ'][$key])){
                                        $excelArr['ְλ'][$key]='��ͨԱ��';
                                    }
                                    //ְλ����
                                    if(empty($jobInfo[$excelArr['ְλ'][$key]])){
                                        $this->db->query_exc("insert into user_jobs(name,dept_id,level,func_id_str) 
                                            values
                                            ('".$excelArr['ְλ'][$key]."','".$deptInfo[$tempdeptname]."','0','')");
                                        $jobinsertid = $this->db->insert_id();
                                        $this->db->query_exc("insert into user_priv(priv_name,user_priv)values('".$excelArr['ְλ'][$key]."','$jobinsertid')");
                                        $jobInfo[$excelArr['ְλ'][$key]]=$jobinsertid;
                                    }
                                    //�û���
                                     $sql="insert into user(
                                            user_id,user_name,password,logname,user_priv
                                            ,jobs_id,dept_id,sex,email
                                            ,area,company,creatdt,creator
                                        )values(
                                            '".$excelArr['Ա����'][$key]."','".$excelArr['����'][$key]."','".$pw."'
                                            ,'".$excelArr['OA�û���'][$key]."','".$jobInfo[$excelArr['ְλ'][$key]]."','".$jobInfo[$excelArr['ְλ'][$key]]."',
                                            '".$deptInfo[$tempdeptname]."','".$sexInfo[$excelArr['�Ա�'][$key]]."','".$excelArr['�����ַ'][$key]."',
                                            '".$areaInfo[$excelArr['����'][$key]]."','".$brInfo[$excelArr['��˾'][$key]]."',now(),'".$_SESSION['USER_ID']."'
                                        )";
                                    $this->db->query_exc($sql);
                                    //���±�
                                     $sql="insert into hrms
                                        (USER_ID,CARD_NO,COME_DATE,JOIN_DATE,EDUCATION
                                        ,CERTIFICATE,School,Major,Address
                                        ,Account,AccCard,Bank,Tele,Native
                                        ,Email,Creator,CreateDT,Remark
                                        ,contflagb,contflage,usercard
                                        )
                                    values
    ('".$excelArr['Ա����'][$key]."','".$excelArr['���֤'][$key]."','".$excelArr['��ְ����'][$key]."','".$excelArr['ת������'][$key]."'
    ,'".$eduInfo[$excelArr['ѧ��'][$key]]."','".$excelArr['ְλ'][$key]."','".$excelArr['��ҵѧУ'][$key]."','".$excelArr['רҵ'][$key]."'
    ,'".$excelArr['��ͥ��ַ'][$key]."','".$excelArr['�����ʺ�'][$key]."','".$excelArr['����'][$key]."','".$excelArr['������'][$key]."'
    ,'".$excelArr['�ֻ�����'][$key]."','".$excelArr['������'][$key]."','".$excelArr['�����ַ'][$key]."','".$_SESSION['USER_ID']."'
    ,now(),'".$excelArr['��ע'][$key]."','".$excelArr['��ͬ��ʼ'][$key]."','".$excelArr['��ͬ��ֹ'][$key]."','".$excelArr['Ա����'][$key]."'
        )";
                                    $this->db->query_exc($sql);
                                     $sql="insert into ecard
                                    (
                                        User_id,Name,Sex,Depart,Http,Email,Company,CreateDT
                                        ,tel_no_dept,mobile1
                                    )values(
                                        '".$excelArr['Ա����'][$key]."',
                                        '".$excelArr['����'][$key]."',
                                        '".$sexInfo[$excelArr['�Ա�'][$key]]."',
                                        '".$deptInfo[$tempdeptname]."',
                                        'http://www.dinglicom.com',
                                        '".$excelArr['�����ַ'][$key]."',
                                        '',
                                        '".date('Y-m-d H:i:s')."',
                                        '".$excelArr['��˾�绰'][$key]."',
                                        '".$excelArr['�ֻ�����'][$key]."'
                                        )";
                                    $this->db->query_exc($sql);
                                    
                                    
                                    
                                    //=========����IM�û�==============
                                    
                                    $data = array(
                                                    'COM_BRN_CN'=>'���Ͷ���',
                                                    'DEPT_NAME'=>$excelArr['����'][$key],
                                                    'USER_ID'=>$excelArr['OA�û���'][$key],
                                                    'USER_NAME'=>$excelArr['����'][$key],
                                                    'PASSWORD'=>$pw,
                                                    'SEX'=>$sexInfo[$excelArr['�Ա�'][$key]],
                                                    'EMAIL'=>$excelArr['�����ַ'][$key],
                                                    'JOBS_NAME'=>$excelArr['ְλ'][$key],
                                                    'Mobile'=>'',
                                                    'Phone'=>$excelArr['��˾'][$key]
                                                );
                                    $msg=$this->im->add_user($data);
                                    if ($msg)
                                    {
                                        $creat_im = '����IM�ʺųɹ�';
                                    }else{
                                        $creat_im = '����IM�ʺ�ʧ��';
                                        throw new ErrorException('shibai-im');
                                    }
                                    
                                     //=========��������==============
                                    $AddEmail='';
                                    $AddEmail_URL = Email_Server_Api_Url.'?action=AddUser&key='.urlencode(authcode(oa_auth_key.' '.time(),'ENCODE'));
                                    $AddEmail_URL .='&userid='.$excelArr['OA�û���'][$key].'&username='.$excelArr['����'][$key].'&password=dinglicom';
                                    $AddEmail_URL .='&domain=dinglicom.com&deptname='.$excelArr['����'][$key];
                                    $AddEmail = file_get_contents(trim($AddEmail_URL));
                                    if (trim($AddEmail)==1)
                                    {
                                        
                                    }else{
                                        //throw new ErrorException('shibai-yx');
                                        $emailerr.=$AddEmail."\n";
                                    }
                                    
                                    $this->db->query("COMMIT");
                                }catch(Exception $e){
                                    $this->db->query("ROLLBACK");
                                    $filestr.=$excelArr['Ա����'][$key].'--'.$e->getMessage()."\n";
                                    $msg='error';
                                }
                            }
                        }
                        $filestr.='x';
                        file_put_contents('zgs.log', $filestr);
                        file_put_contents('zgs-e.log', $emailerr);
                        return un_iconv($filestr);
                    }elseif($flag=='salary'){
                        if(!in_array('Ա����', $excelFields)||!in_array('����', $excelFields)
                            ||!in_array('������λ', $excelFields)||!in_array('������λ����', $excelFields))
                        {
                            throw new Exception('Update failed');
                        }
                        if(count($excelArr)&&!empty($excelArr)){
                            foreach($excelArr['Ա����'] as $key=>$val ){
                                $infoE[$val]['name']=$excelArr['����'][$key];
                                $infoE[$val]['lev']=$excelArr['������λ'][$key];
                                $infoE[$val]['sal']=$excelArr['������λ����'][$key];
                                $infoE[$val]['flag']='0';
                            }
                        }
                        $sql="select u.email , h.usercard
                            from user u
                                left join hrms h on (u.user_id=h.user_id)
                            where u.user_id=h.user_id and u.dept_id='35' ";
                        $query=$this->db->query_exc($sql);
                        while($row=$this->db->fetch_array($query)){
                            if(array_key_exists($row['usercard'],$infoE)){
                                $infoE[$row['usercard']]['flag']='1';
                                $infoE[$row['usercard']]['email']=$row['email'];
                            }
                        }
                        if(!empty($infoE)){
                            foreach($infoE as $key=>$val){
                                $tmpstr='';
                                if($val['flag']=='1'){
                                    $tmpstr='<DIV><FONT size=2 face=Verdana>&nbsp;<FONT size=2 face=Verdana>���ã�</FONT><FONT
size=2 face=Verdana>
<BLOCKQUOTE style="MARGIN-RIGHT: 0px" dir=ltr>
  <DIV>
  <TABLE
  style="BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; BORDER-COLLAPSE: collapse; FONT-SIZE: 10pt; BORDER-TOP: medium none; BORDER-RIGHT: medium none"
  border=1 cellSpacing=0 borderColor=#000000 cellPadding=2 width="50%">
    <TBODY>
    <TR>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>Ա����</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>����</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>������λ</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>������λ����</DIV></FONT></TD></TR>
    <TR>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>'.$key.'</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>'.$val['name'].'</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>'.$val['lev'].'</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>'.$val['sal'].'</DIV></FONT></TD></TR></TBODY></TABLE></FONT><FONT
  size=2 face=Verdana><FONT size=2 face=Verdana></FONT></DIV></BLOCKQUOTE>
<DIV style="TEXT-INDENT: 2em"><FONT size=2
face=Verdana>��12��1�������Ĺ��ʽ��������ϱ�׼���ţ�����֪Ϥ��</FONT></FONT></FONT></DIV></DIV>';
                                    $this->emailClass ->send('���ʵ���', $tmpstr, $val['email'], '���Ͷ���', '���Ͷ���');
                                }
                            }
                        }
                    }elseif($flag=='userupdate'){
                        foreach($excelArr['����'] as $key=>$val){
                            /*$sql="update hrms h left join user u on (h.user_id = u.user_id )
                                set h.usercard='".$val."'
                                where u.user_name='".$excelArr['����'][$key]."' ";
                            $this->_db->query($sql);
                             * 
                             */
                        }
                    }elseif($flag=='yeb'){
                        if(!in_array('Ա����', $excelFields)||!in_array('����', $excelFields)
                            ||!in_array('���ս�', $excelFields)
                            ||!in_array('��˰', $excelFields)||!in_array('ʵ�����', $excelFields))
                        {
                            throw new Exception('Update failed');
                        }
                        if(count($excelArr)&&!empty($excelArr)){
                            foreach($excelArr['Ա����'] as $key=>$val ){
                                $infoE[$val]['name']=$excelArr['����'][$key];
                                $infoE[$val]['yam']=$excelArr['���ս�'][$key];
                                $infoE[$val]['kam']=$excelArr['��˰'][$key];
                                $infoE[$val]['pam']=$excelArr['ʵ�����'][$key];
                                $infoE[$val]['flag']='0';
                            }
                        }
                        $sql="select u.email , h.usercard
                            from user u
                                left join hrms h on (u.user_id=h.user_id)
                            where u.user_id=h.user_id  ";
                        $query=$this->db->query_exc($sql);
                        while($row=$this->db->fetch_array($query)){
                            if(array_key_exists($row['usercard'],$infoE)){
                                $infoE[$row['usercard']]['flag']='1';
                                $infoE[$row['usercard']]['email']=$row['email'];
                            }
                        }
                        if(!empty($infoE)){
                            $i=1;
                            foreach($infoE as $key=>$val){
                                if($val['flag']=='1'){
                                    $str='���ã��������ս���ͨ����ˣ�������Ϣ���£�����֪Ϥ��<br/>
                                            <table border="1" cellspacing="1" cellpadding="1">
                                                <tr><td>Ա����</td><td>'.$val['name'].'</td></tr>
                                                <tr><td>���ս���</td><td>'.$val['yam'].'</td></tr>
                                                <tr><td>��˰��</td><td>'.$val['kam'].'</td></tr>
                                                <tr><td>ʵ����</td><td>'.$val['pam'].'</td></tr>
                                            </table><br/>
                                            ���ս�������ǰ���ţ�';
                                    $this->emailClass ->send('���ս���Ϣ', $str, $val['email']);
                                }
                                $i++;
                            }
                        }
                    }
                }
            }
            return '1';
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    function model_data($ckt){
        set_time_limit(600);
        $type=$_POST['imfile'];
        $flag=$_POST['flag'];
        $str='';
        $infoA=array();
        try{
            $excelfilename='attachment/sys_import/'.$ckt.".xls";
            if(empty ($_FILES["imfile"]["tmp_name"])){
                $str='<tr><td colspan="10">���ϴ����ݣ�</td></tr>';
            }elseif (!move_uploaded_file( $_FILES["imfile"]["tmp_name"], $excelfilename) ){
                $str='<tr><td colspan="10">�ϴ�ʧ�ܣ�</td></tr>';
            }else{
                //��ȡexcel��Ϣ
                include('includes/classes/excel.php');
                $excel = Excel::getInstance();
                $excel->setFile(WEB_TOR.$excelfilename);
                $excel->Open();
                $excel->setSheet();
                $excelFields = $excel->getFields();
                $excelArr=$excel->getAllData();
                $excel->Close();
                $ckA=array();
                $ckB=array();
                if($flag=='hr_up'){
                    if(count($excelArr)&&!empty($excelArr)){
                        $str.='<tr><td>���</td>';
                        foreach($excelFields as $val){
                            $str.='<td>'.$val.'</td>';
                        }
                        $str.='</tr>';
                        $i=0;
                        foreach($excelArr['Ա����'] as $key=>$val ){
                            $i++;
                            $str.='<tr>';
                            $str.='<td>'.$i.'</td>';
                            foreach($excelFields as $fval){
                                $str.='<td>'.$excelArr[$fval][$key].'</td>';
                            }
                            $str.='</tr>';
                        }
                    }
                }
                //�ӹ�˾�ϲ�
                if($flag=='zgs_ini'){
                    if(count($excelArr)&&!empty($excelArr)){
                        $deptInfo=array();
                        $areaInfo=array(''=>'1');
                        $jobInfo=array();
                        $sexInfo=array('��'=>'0','Ů'=>'1');
                        $eduInfo=array("ר��"=>'1',"����"=>'2',"����"=>'3',"��ר"=>'4',"��ר"=>'5'
                            ,"����"=>'6',"˶ʿ"=>'7',"�о���"=>'8',"��ʿ"=>'9',"δ�����������"=>'10');
                        $sql="select dept_id , dept_name from department ";
                        $query=$this->db->query($sql);
                        while($row=$this->db->fetch_array($query)){
                            $deptInfo[$row['dept_name']]=$row['dept_id'];
                        }
                        $sql="select name , id from area where del='0' ";
                        $query=$this->db->query($sql);
                        while($row=$this->db->fetch_array($query)){
                            $areaInfo[$row['name']]=$row['id'];
                        }
                        $sql="select name , id from user_jobs  ";
                        $query=$this->db->query($sql);
                        while($row=$this->db->fetch_array($query)){
                            $jobInfo[$row['name']]=$row['id'];
                        }
                        $sql="select user_name , user_id , has_left from user where del='0' and has_left='0' ";
                        $query=$this->db->query($sql);
                        while($row=$this->db->fetch_array($query)){
                            $userInfo[$row['user_id']]['na']=$row['user_name'];
                            $userInfo[$row['user_id']]['le']=$row['has_left'];
                        }
                        $sql="SELECT namecn , namept FROM branch_info where type='1' ";
                        $query=$this->db->query($sql);
                        while($row=$this->db->fetch_array($query)){
                            $brInfo[$row['namecn']]=$row['namept'];
                        }
                        //
                        $str.='<tr><td>���</td>';
                        foreach($excelFields as $val){
                            $str.='<td>'.$val.'</td>';
                        }
                        $str.='</tr>';
                        $i=0;
                        foreach($excelArr['Ա����'] as $key=>$val ){
                            $i++;
                            $str.='<tr>';
                            $str.='<td>'.$i.'</td>';
                            foreach($excelFields as $fval){
                                $col='';
                                if(empty($deptInfo[$excelArr[$fval][$key]])&&$fval=='����'){
                                    $col=' color="red" ';
                                }
                                if(!empty($userInfo[$excelArr[$fval][$key]])&&$fval=='OA�û���'){
                                    $col=' color="red" ';
                                    $ckr.=$excelArr[$fval][$key].'<br>';
                                }
                                if(empty($eduInfo[$excelArr[$fval][$key]])&&$fval=='ѧ��'){
                                    $col=' color="red" ';
                                }
                                if(empty($brInfo[$excelArr[$fval][$key]])&&$fval=='��˾'){
                                    $col=' color="red" ';
                                }
                                $str.='<td ><font '.$col.'>'.$excelArr[$fval][$key].'<font></td>';
                            }
                            $str.='</tr>';
                        }
                        echo $ckr;
                        
                    }
                }
                if($flag=='ecard-up'){
                    if(count($excelArr)&&!empty($excelArr)){
                        $str.='<tr>';
                        foreach($excelFields as $val){
                            $str.='<td>'.$val.'</td>';
                        }
                        $str.='</tr>';
                        foreach($excelArr['Ա����'] as $key=>$val ){
                            $str.='<tr>';
                            foreach($excelFields as $fval){
                                $str.='<td>'.$excelArr[$fval][$key].'</td>';
                            }
                            $str.='</tr>';
                        }
                    }
                }
                if($flag=='hrupdate'){
                    if(!in_array('Ա����', $excelFields)||!in_array('����', $excelFields)
                        ||!in_array('�������´�', $excelFields)||!in_array('ְλ', $excelFields))
                    {
                        throw new Exception('Update failed');
                    }
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['Ա����'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['����'][$key];
                            $infoE[$val]['blg']=$excelArr['�������´�'][$key];
                            $infoE[$val]['pos']=$excelArr['ְλ'][$key];
                            $infoE[$val]['flag']='0';
                        }
                    }
                    $sql="select u.email , h.usercard
                        from user u
                            left join hrms h on (u.user_id=h.user_id)
                        where u.user_id=h.user_id and u.dept_id='35'";
                    $query=$this->db->query_exc($sql);
                    while($row=$this->db->fetch_array($query)){
                        if(array_key_exists($row['usercard'],$infoE)){
                            $infoE[$row['usercard']]['flag']='1';
                        }
                    }
                    if(!empty($infoE)){
                        $i=1;
                        $str.='<tr>
                                    <td>���</td>
                                    <td>Ա����</td>
                                    <td>����</td>
                                    <td>�������´�</td>
                                    <td>ְλ</td>
                                </tr>';
                        foreach($infoE as $key=>$val){
                            if($val['flag']=='1'){
                                $str.='<tr style="color:green;">
                                    <td>'.$i.'</td>
                                    <td>'.$key.'</td>
                                    <td>'.$val['name'].'</td>
                                    <td>'.$val['blg'].'</td>
                                    <td>'.$val['pos'].'</td>
                                </tr>';
                            }else{
                                $str.='<tr style="color:#FF9900;">
                                    <td>'.$i.'</td>
                                    <td>'.$key.'</td>
                                    <td>'.$val['name'].'</td>
                                    <td>'.$val['blg'].'</td>
                                    <td>'.$val['pos'].'</td>
                                </tr>';
                            }
                            $i++;
                        }
                    }
                }elseif($flag=='salary'){
                    if(!in_array('Ա����', $excelFields)||!in_array('����', $excelFields)
                        ||!in_array('������λ', $excelFields)||!in_array('������λ����', $excelFields))
                    {
                        throw new Exception('Update failed');
                    }
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['Ա����'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['����'][$key];
                            $infoE[$val]['lev']=$excelArr['������λ'][$key];
                            $infoE[$val]['sal']=$excelArr['������λ����'][$key];
                            $infoE[$val]['flag']='0';
                        }
                    }
                    $sql="select u.email , h.usercard
                        from user u
                            left join hrms h on (u.user_id=h.user_id)
                        where u.user_id=h.user_id and u.dept_id='35'";
                    $query=$this->db->query_exc($sql);
                    while($row=$this->db->fetch_array($query)){
                        if(array_key_exists($row['usercard'],$infoE)){
                            $infoE[$row['usercard']]['flag']='1';
                            $infoE[$row['usercard']]['email']=$row['email'];
                        }
                    }
                    if(!empty($infoE)){
                        $i=1;
                        $str.='<tr>
                                    <td>���</td>
                                    <td>Ա����</td>
                                    <td>����</td>
                                    <td>������λ</td>
                                    <td>������λ����</td>
                                    <td>Email</td>
                                </tr>';
                        foreach($infoE as $key=>$val){
                            if($val['flag']=='1'){
                                $str.='<tr style="color:green;">
                                    <td>'.$i.'</td>
                                    <td>'.$key.'</td>
                                    <td>'.$val['name'].'</td>
                                    <td>'.$val['lev'].'</td>
                                    <td>'.$val['sal'].'</td>
                                    <td>'.$val['email'].'</td>
                                </tr>';
                            }else{
                                $str.='<tr style="color:#FF9900;">
                                    <td>'.$i.'</td>
                                    <td>'.$key.'</td>
                                    <td>'.$val['name'].'</td>
                                    <td>'.$val['lev'].'</td>
                                    <td>'.$val['sal'].'</td>
                                    <td style="color:red;">����Ա���Ƿ����Ų�������ְ��</td>
                                </tr>';
                            }
                            $i++;
                        }
                    }
                }elseif($flag=='userupdate'){
                    $sql="select
                            u.user_id , u.user_name , u.del , u.has_left
                        from
                            user u
                            left join hrms h on(u.user_id=h.user_id)
                        where u.user_id=h.user_id  and u.dept_id<>1 order by h.come_date  ";
                    $query=$this->db->query_exc($sql);
                    while($row=$this->db->fetch_array($query)){
                        $ckA[]=$row['user_name'];
                        $infoA[$row['user_id']]['name']=$row['user_name'];
                        if($row['del']=='1'||$row['has_left']=='1'){
                            $infoA[$row['user_id']]['leave']='<font color="red">��ְ</font>';
                        }else{
                            $infoA[$row['user_id']]['leave']='��ְ';
                        }
                    }
                    $infoB=array();
                    $infoC=array();
                    $infoD=array();
                    $infoE=array();
                    $infoF=array();
                    $infoJ=array();
                    if(!empty($infoA)){
                        foreach($infoA as $key=>$val){
                            $sk=array_search($val['name'], $excelArr['����']);
                            if($sk!==FALSE&&$excelArr['����'][$sk]){
                                $ckB[]=$excelArr['����'][$sk];
                                /*
                                if(array_key_exists($excelArr['����'][$sk], $infoB)){
                                    $infoB[$excelArr['����'][$sk]]['name']=$infoB[$excelArr['����'][$sk]]['name'].$val['name'];
                                }else{
                                    $infoB[$excelArr['����'][$sk]]['name']=$val['name'];
                                }
                                */
                                $infoB[$excelArr['����'][$sk]]['name']=$val['name'];
                                $infoB[$excelArr['����'][$sk]]['leave']=$val['leave'];
                                $infoB[$excelArr['����'][$sk]]['sex']=$excelArr['�Ա�'][$sk];
                                $infoB[$excelArr['����'][$sk]]['dept']=$excelArr['����'][$sk];
                            }elseif($val['leave']=='��ְ'){
                                $infoC[$key]=$val;
                            }else{
                                $infoD[$key]=$val;
                            }
                        }
                        foreach($excelArr['����'] as $key=>$val){
                            if(array_key_exists($excelArr['����'][$key], $infoF)){
                                $infoF[$excelArr['����'][$key]]+=1;
                            }else{
                                $infoF[$excelArr['����'][$key]]=1;
                            }
                            if(array_search($val, $ckA)===false){
                                $infoE[$key]=$val;
                            }
                            if(array_search($excelArr['����'][$key], $ckB)===false){
                                $infoJ[$key]=$val;
                            }
                        }
                    }
                    $i=1;
                    if(!empty($infoB)){
                        ksort($infoB);
                        foreach($infoB as $key=>$val){
                            $str.='<tr style="color:green;">
                                    <td>'.$i.'</td>
                                    <td>'.$val['name'].'</td>
                                    <td>'.$val['sex'].'</td>
                                    <td>'.$val['dept'].'</td>
                                    <td>'.$key.'</td>
                                    <td>'.$val['leave'].'</td>
                                </tr>';
                            $i++;
                        }
                    }
                    if(!empty($infoC)){
                        foreach($infoC as $key=>$val){
                            $str.='<tr style="color:blue;">
                                <td>'.$val['name'].'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>'.$val['leave'].'</td>
                            </tr>';
                        }
                    }
                    if(!empty($infoD)){
                        foreach($infoD as $key=>$val){
                            $str.='<tr style="color:blue;">
                                <td>'.$val['name'].'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>'.$val['leave'].'</td>
                            </tr>';
                        }
                    }
                    if(!empty($infoE)){
                        foreach($infoE as $key=>$val){
                            $str.='<tr >
                                <td>'.$val.'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>';
                        }
                    }
                    if(!empty($infoF)){
                        foreach($infoF as $key=>$val){
                            if($val=='1'){
                                continue;
                            }
                            $str.='<tr style="color:red;">
                                <td>'.$key.'</td>
                                <td>'.$val.'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>';
                        }
                    }
                    if(!empty($infoJ)){
                        foreach($infoJ as $key=>$val){
                            $str.='<tr >
                                <td>'.$val.'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>';
                        }
                    }
                }elseif($flag=='yeb'){
                    if(!in_array('Ա����', $excelFields)||!in_array('����', $excelFields)
                        ||!in_array('���ս�', $excelFields)
                        ||!in_array('��˰', $excelFields)||!in_array('ʵ�����', $excelFields))
                    {
                        throw new Exception('Update failed');
                    }
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['Ա����'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['����'][$key];
                            $infoE[$val]['yam']=$excelArr['���ս�'][$key];
                            $infoE[$val]['kam']=$excelArr['��˰'][$key];
                            $infoE[$val]['pam']=$excelArr['ʵ�����'][$key];
                            $infoE[$val]['flag']='0';
                        }
                    }
                    $sql="select u.email , h.usercard
                        from user u
                            left join hrms h on (u.user_id=h.user_id)
                        where u.user_id=h.user_id ";
                    $query=$this->db->query_exc($sql);
                    while($row=$this->db->fetch_array($query)){
                        if(array_key_exists($row['usercard'],$infoE)){
                            $infoE[$row['usercard']]['flag']='1';
                            $infoE[$row['usercard']]['email']=$row['email'];
                        }
                    }
                    if(!empty($infoE)){
                        $i=1;
                        $str.='<tr>
                                    <td>���</td>
                                    <td>Ա����</td>
                                    <td>����</td>
                                    <td>���ս�</td>
                                    <td>��˰</td>
                                    <td>ʵ�����</td>
                                    <td>Email</td>
                                </tr>';
                        foreach($infoE as $key=>$val){
                            if($val['flag']=='1'){
                                $str.='<tr style="color:green;">
                                    <td>'.$i.'</td>
                                    <td>'.$key.'</td>
                                    <td>'.$val['name'].'</td>
                                    <td>'.$val['yam'].'</td>
                                    <td>'.$val['kam'].'</td>
                                    <td>'.$val['pam'].'</td>
                                    <td>'.$val['email'].'</td>
                                </tr>';
                            }else{
                                $str.='<tr style="color:#FF9900;">
                                    <td>'.$i.'</td>
                                    <td>'.$key.'</td>
                                    <td>'.$val['name'].'</td>
                                    <td>'.$val['yam'].'</td>
                                    <td>'.$val['kam'].'</td>
                                    <td>'.$val['pam'].'</td>
                                    <td style="color:red;">����Ա�����Ƿ���ȷ��</td>
                                </tr>';
                            }
                            $i++;
                        }
                    }
                }
            }
        }catch(Exception $e){
            $str='<tr><td colspan="10">�������ݴ��������ʽ��'.$e->getMessage().'</td></tr>';
        }
        return $str;
    }
    function model_ini(){
        $card=1;
        $userArr=array();
        $sql="select u.user_id
            from hrms h
                left join user u on (h.user_id=u.user_id )
            where h.usercard='' and ( u.del='1' or u.has_left='1' ) ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $muc=sprintf("%05.0f",$card);
            $sql="update hrms set usercard='L".$muc."' where user_id='".$row['user_id']."' ";
            $this->_db->query($sql);
            $card++;
        }
    }
    
    //*********************************��������************************************
    function __destruct() {
    }

}

?>