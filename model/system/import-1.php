<?php
class model_system_import extends model_base {

    public $page;
    public $num;
    public $start;
    public $db;
    public $emailClass;
    public $im;
    public $flag;

    //*******************************构造函数***********************************
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
                //读取excel信息
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
                        if(!in_array('员工号', $excelFields)||!in_array('姓名', $excelFields)
                            ||!in_array('归属办事处', $excelFields)||!in_array('职位', $excelFields))
                        {
                            throw new Exception('Update failed');
                        }
                        if(count($excelArr)&&!empty($excelArr)){
                            foreach($excelArr['员工号'] as $key=>$val ){
                                $infoE[$val]['name']=$excelArr['姓名'][$key];
                                $infoE[$val]['blg']=$excelArr['归属办事处'][$key];
                                $infoE[$val]['pos']=$excelArr['职位'][$key];
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
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $sql="update ecard set 
                                    tel_no_dept='".$excelArr['公司电话'][$key]."'
                                    ,mobile1='".$excelArr['手机号码'][$key]."'
                                  where 
                                    User_id='".$excelArr['员工号'][$key]."'
                                ";
                            $this->db->query_exc($sql);
                        }
                    }elseif($flag=='hr_up'){
                         if(count($excelArr)&&!empty($excelArr)){
                            $sexInfo=array('男'=>'0','女'=>'1');
                            $eduInfo=array("专科"=>'1',"初中"=>'2',"高中"=>'3',"中专"=>'4',"大专"=>'5'
                                ,"本科"=>'6',"硕士"=>'7',"研究生"=>'8',"博士"=>'9',"未接受正规教育"=>'10');
                             foreach($excelArr['员工号'] as $key=>$val ){
                                 try{
                                    $this->db->query("START TRANSACTION");
                                    $sql="update hrms h
                                            left join user u on (h.user_id=u.user_id)
                                        set 
                                            u.sex='".$sexInfo[$excelArr['性别'][$key]]."'
                                            ,h.education='".$eduInfo[$excelArr['学历'][$key]]."'
                                            ,h.school='".$excelArr['毕业学校'][$key]."'
                                            ,h.major='".$excelArr['专业'][$key]."'
                                        where h.usercard='".$excelArr['员工号'][$key]."' ";
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
                            //获取部门/区域/职位数据
                            $deptInfo=array();
                            $areaInfo=array(''=>'1');
                            $jobInfo=array();
                            $sexInfo=array('男'=>'0','女'=>'1');
                            $eduInfo=array("专科"=>'1',"初中"=>'2',"高中"=>'3',"中专"=>'4',"大专"=>'5'
                                ,"本科"=>'6',"硕士"=>'7',"研究生"=>'8',"博士"=>'9',"未接受正规教育"=>'10');
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
                            foreach($excelArr['员工号'] as $key=>$val ){
                                try{
                                    $this->db->query("START TRANSACTION");
                                    $tempdept=array();
                                    $tempdeptname='';
                                    $tempdept=$excelArr['部门'][$key];
                                    $tempdeptname=$tempdept;
                                    $excelArr['员工号'][$key]='0'.$excelArr['员工号'][$key];
                                    $excelArr['邮箱地址'][$key]=$excelArr['邮箱地址'][$key].'@bettercomm.net';
                                    //默认普通员工
                                    if(empty($excelArr['职位'][$key])){
                                        $excelArr['职位'][$key]='普通员工';
                                    }
                                    //职位新增
                                    if(empty($jobInfo[$excelArr['职位'][$key]])){
                                        $this->db->query_exc("insert into user_jobs(name,dept_id,level,func_id_str) 
                                            values
                                            ('".$excelArr['职位'][$key]."','".$deptInfo[$tempdeptname]."','0','')");
                                        $jobinsertid = $this->db->insert_id();
                                        $this->db->query_exc("insert into user_priv(priv_name,user_priv)values('".$excelArr['职位'][$key]."','$jobinsertid')");
                                        $jobInfo[$excelArr['职位'][$key]]=$jobinsertid;
                                    }
                                    //用户表
                                     $sql="insert into user(
                                            user_id,user_name,password,logname,user_priv
                                            ,jobs_id,dept_id,sex,email
                                            ,area,company,creatdt,creator
                                        )values(
                                            '".$excelArr['员工号'][$key]."','".$excelArr['姓名'][$key]."','".$pw."'
                                            ,'".$excelArr['员工号'][$key]."','".$jobInfo[$excelArr['职位'][$key]]."','".$jobInfo[$excelArr['职位'][$key]]."',
                                            '".$deptInfo[$tempdeptname]."','".$sexInfo[$excelArr['性别'][$key]]."','".$excelArr['邮箱地址'][$key]."',
                                            '".$areaInfo[$excelArr['区域'][$key]]."','贝讯',now(),'".$_SESSION['USER_ID']."'
                                        )";
                                    $this->db->query_exc($sql);
                                    //人事表
                                     $sql="insert into hrms
                                        (USER_ID,CARD_NO,COME_DATE,JOIN_DATE,EDUCATION
                                        ,CERTIFICATE,School,Major,Address
                                        ,Account,AccCard,Bank,Tele,Native
                                        ,Email,Creator,CreateDT,Remark
                                        ,contflagb,contflage,usercard
                                        )
                                    values
    ('".$excelArr['员工号'][$key]."','".$excelArr['身份证'][$key]."','".$excelArr['入职日期'][$key]."','".$excelArr['转正日期'][$key]."'
    ,'".$eduInfo[$excelArr['学历'][$key]]."','".$excelArr['职位'][$key]."','".$excelArr['毕业学校'][$key]."','".$excelArr['专业'][$key]."'
    ,'".$excelArr['家庭地址'][$key]."','".$excelArr['银行帐号'][$key]."','".$excelArr['卡号'][$key]."','".$excelArr['开户行'][$key]."'
    ,'".$excelArr['手机号码'][$key]."','".$excelArr['户籍地'][$key]."','".$excelArr['邮箱地址'][$key]."','".$_SESSION['USER_ID']."'
    ,now(),'".$excelArr['备注'][$key]."','".$excelArr['合同期始'][$key]."','".$excelArr['合同期止'][$key]."','".$excelArr['员工号'][$key]."'
        )";
                                    $this->db->query_exc($sql);
                                     $sql="insert into ecard
                                    (
                                        User_id,Name,Sex,Depart,Http,Email,Company,CreateDT
                                        ,tel_no_dept,mobile1
                                    )values(
                                        '".$excelArr['员工号'][$key]."',
                                        '".$excelArr['姓名'][$key]."',
                                        '".$sexInfo[$excelArr['性别'][$key]]."',
                                        '".$deptInfo[$tempdeptname]."',
                                        'http://www.bettercomm.net',
                                        '".$excelArr['邮箱地址'][$key]."',
                                        '',
                                        '".date('Y-m-d H:i:s')."',
                                        '".$excelArr['公司电话'][$key]."',
                                        '".$excelArr['手机号码'][$key]."'
                                        )";
                                    $this->db->query_exc($sql);
                                    $this->db->query("COMMIT");
                                    
                                    
                                    //=========创建IM用户==============
                                    
                                    $data = array(
                                                    'COM_BRN_CN'=>'广州贝讯',
                                                    'DEPT_NAME'=>$excelArr['部门'][$key],
                                                    'USER_ID'=>$excelArr['员工号'][$key],
                                                    'USER_NAME'=>$excelArr['姓名'][$key],
                                                    'PASSWORD'=>$pw,
                                                    'SEX'=>$sexInfo[$excelArr['性别'][$key]],
                                                    'EMAIL'=>$excelArr['邮箱地址'][$key],
                                                    'JOBS_NAME'=>$excelArr['职位'][$key],
                                                    'Mobile'=>'',
                                                    'Phone'=>''
                                                );
                                    $msg=$this->im->add_user($data);
                                    if ($msg)
                                    {
                                        $creat_im = '创建IM帐号成功';
                                    }else{
                                        $creat_im = '创建IM帐号失败';
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
                            //获取部门/区域/职位数据
                            $deptInfo=array();
                            $areaInfo=array(''=>'1');
                            $jobInfo=array();
                            $sexInfo=array('男'=>'0','女'=>'1');
                            $eduInfo=array("专科"=>'1',"初中"=>'2',"高中"=>'3',"中专"=>'4',"大专"=>'5'
                                ,"本科"=>'6',"硕士"=>'7',"研究生"=>'8',"博士"=>'9',"未接受正规教育"=>'10');
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
                            foreach($excelArr['员工号'] as $key=>$val ){
                                try{
                                    $this->db->query("START TRANSACTION");
                                    $tempdept=array();
                                    $tempdeptname='';
                                    $tempdept=$excelArr['部门'][$key];
                                    $tempdeptname=$tempdept;
                                    $excelArr['员工号'][$key]='0'.$excelArr['员工号'][$key];
                                    $excelArr['邮箱地址'][$key]=$excelArr['OA用户名'][$key].'@dinglicom.com';
                                    //默认普通员工
                                    if(empty($excelArr['职位'][$key])){
                                        $excelArr['职位'][$key]='普通员工';
                                    }
                                    //职位新增
                                    if(empty($jobInfo[$excelArr['职位'][$key]])){
                                        $this->db->query_exc("insert into user_jobs(name,dept_id,level,func_id_str) 
                                            values
                                            ('".$excelArr['职位'][$key]."','".$deptInfo[$tempdeptname]."','0','')");
                                        $jobinsertid = $this->db->insert_id();
                                        $this->db->query_exc("insert into user_priv(priv_name,user_priv)values('".$excelArr['职位'][$key]."','$jobinsertid')");
                                        $jobInfo[$excelArr['职位'][$key]]=$jobinsertid;
                                    }
                                    //用户表
                                     $sql="insert into user(
                                            user_id,user_name,password,logname,user_priv
                                            ,jobs_id,dept_id,sex,email
                                            ,area,company,creatdt,creator
                                        )values(
                                            '".$excelArr['员工号'][$key]."','".$excelArr['姓名'][$key]."','".$pw."'
                                            ,'".$excelArr['OA用户名'][$key]."','".$jobInfo[$excelArr['职位'][$key]]."','".$jobInfo[$excelArr['职位'][$key]]."',
                                            '".$deptInfo[$tempdeptname]."','".$sexInfo[$excelArr['性别'][$key]]."','".$excelArr['邮箱地址'][$key]."',
                                            '".$areaInfo[$excelArr['区域'][$key]]."','".$brInfo[$excelArr['公司'][$key]]."',now(),'".$_SESSION['USER_ID']."'
                                        )";
                                    $this->db->query_exc($sql);
                                    //人事表
                                     $sql="insert into hrms
                                        (USER_ID,CARD_NO,COME_DATE,JOIN_DATE,EDUCATION
                                        ,CERTIFICATE,School,Major,Address
                                        ,Account,AccCard,Bank,Tele,Native
                                        ,Email,Creator,CreateDT,Remark
                                        ,contflagb,contflage,usercard
                                        )
                                    values
    ('".$excelArr['员工号'][$key]."','".$excelArr['身份证'][$key]."','".$excelArr['入职日期'][$key]."','".$excelArr['转正日期'][$key]."'
    ,'".$eduInfo[$excelArr['学历'][$key]]."','".$excelArr['职位'][$key]."','".$excelArr['毕业学校'][$key]."','".$excelArr['专业'][$key]."'
    ,'".$excelArr['家庭地址'][$key]."','".$excelArr['银行帐号'][$key]."','".$excelArr['卡号'][$key]."','".$excelArr['开户行'][$key]."'
    ,'".$excelArr['手机号码'][$key]."','".$excelArr['户籍地'][$key]."','".$excelArr['邮箱地址'][$key]."','".$_SESSION['USER_ID']."'
    ,now(),'".$excelArr['备注'][$key]."','".$excelArr['合同期始'][$key]."','".$excelArr['合同期止'][$key]."','".$excelArr['员工号'][$key]."'
        )";
                                    $this->db->query_exc($sql);
                                     $sql="insert into ecard
                                    (
                                        User_id,Name,Sex,Depart,Http,Email,Company,CreateDT
                                        ,tel_no_dept,mobile1
                                    )values(
                                        '".$excelArr['员工号'][$key]."',
                                        '".$excelArr['姓名'][$key]."',
                                        '".$sexInfo[$excelArr['性别'][$key]]."',
                                        '".$deptInfo[$tempdeptname]."',
                                        'http://www.dinglicom.com',
                                        '".$excelArr['邮箱地址'][$key]."',
                                        '',
                                        '".date('Y-m-d H:i:s')."',
                                        '".$excelArr['公司电话'][$key]."',
                                        '".$excelArr['手机号码'][$key]."'
                                        )";
                                    $this->db->query_exc($sql);
                                    
                                    
                                    
                                    //=========创建IM用户==============
                                    
                                    $data = array(
                                                    'COM_BRN_CN'=>'世纪鼎利',
                                                    'DEPT_NAME'=>$excelArr['部门'][$key],
                                                    'USER_ID'=>$excelArr['OA用户名'][$key],
                                                    'USER_NAME'=>$excelArr['姓名'][$key],
                                                    'PASSWORD'=>$pw,
                                                    'SEX'=>$sexInfo[$excelArr['性别'][$key]],
                                                    'EMAIL'=>$excelArr['邮箱地址'][$key],
                                                    'JOBS_NAME'=>$excelArr['职位'][$key],
                                                    'Mobile'=>'',
                                                    'Phone'=>$excelArr['公司'][$key]
                                                );
                                    $msg=$this->im->add_user($data);
                                    if ($msg)
                                    {
                                        $creat_im = '创建IM帐号成功';
                                    }else{
                                        $creat_im = '创建IM帐号失败';
                                        throw new ErrorException('shibai-im');
                                    }
                                    
                                     //=========创建邮箱==============
                                    $AddEmail='';
                                    $AddEmail_URL = Email_Server_Api_Url.'?action=AddUser&key='.urlencode(authcode(oa_auth_key.' '.time(),'ENCODE'));
                                    $AddEmail_URL .='&userid='.$excelArr['OA用户名'][$key].'&username='.$excelArr['姓名'][$key].'&password=dinglicom';
                                    $AddEmail_URL .='&domain=dinglicom.com&deptname='.$excelArr['部门'][$key];
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
                                    $filestr.=$excelArr['员工号'][$key].'--'.$e->getMessage()."\n";
                                    $msg='error';
                                }
                            }
                        }
                        $filestr.='x';
                        file_put_contents('zgs.log', $filestr);
                        file_put_contents('zgs-e.log', $emailerr);
                        return un_iconv($filestr);
                    }elseif($flag=='salary'){
                        if(!in_array('员工号', $excelFields)||!in_array('姓名', $excelFields)
                            ||!in_array('技术岗位', $excelFields)||!in_array('技术岗位工资', $excelFields))
                        {
                            throw new Exception('Update failed');
                        }
                        if(count($excelArr)&&!empty($excelArr)){
                            foreach($excelArr['员工号'] as $key=>$val ){
                                $infoE[$val]['name']=$excelArr['姓名'][$key];
                                $infoE[$val]['lev']=$excelArr['技术岗位'][$key];
                                $infoE[$val]['sal']=$excelArr['技术岗位工资'][$key];
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
                                    $tmpstr='<DIV><FONT size=2 face=Verdana>&nbsp;<FONT size=2 face=Verdana>您好！</FONT><FONT
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
        <DIV align=center>员工号</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>姓名</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>技术岗位</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>技术岗位工资</DIV></FONT></TD></TR>
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
face=Verdana>从12月1日起，您的工资将按照如上标准发放，敬请知悉。</FONT></FONT></FONT></DIV></DIV>';
                                    $this->emailClass ->send('工资调整', $tmpstr, $val['email'], '世纪鼎利', '世纪鼎利');
                                }
                            }
                        }
                    }elseif($flag=='userupdate'){
                        foreach($excelArr['工号'] as $key=>$val){
                            /*$sql="update hrms h left join user u on (h.user_id = u.user_id )
                                set h.usercard='".$val."'
                                where u.user_name='".$excelArr['姓名'][$key]."' ";
                            $this->_db->query($sql);
                             * 
                             */
                        }
                    }elseif($flag=='yeb'){
                        if(!in_array('员工号', $excelFields)||!in_array('名字', $excelFields)
                            ||!in_array('年终奖', $excelFields)
                            ||!in_array('扣税', $excelFields)||!in_array('实发金额', $excelFields))
                        {
                            throw new Exception('Update failed');
                        }
                        if(count($excelArr)&&!empty($excelArr)){
                            foreach($excelArr['员工号'] as $key=>$val ){
                                $infoE[$val]['name']=$excelArr['名字'][$key];
                                $infoE[$val]['yam']=$excelArr['年终奖'][$key];
                                $infoE[$val]['kam']=$excelArr['扣税'][$key];
                                $infoE[$val]['pam']=$excelArr['实发金额'][$key];
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
                                    $str='您好！您的年终奖已通过审核，具体信息如下，敬请知悉：<br/>
                                            <table border="1" cellspacing="1" cellpadding="1">
                                                <tr><td>员工：</td><td>'.$val['name'].'</td></tr>
                                                <tr><td>年终奖：</td><td>'.$val['yam'].'</td></tr>
                                                <tr><td>扣税金额：</td><td>'.$val['kam'].'</td></tr>
                                                <tr><td>实发金额：</td><td>'.$val['pam'].'</td></tr>
                                            </table><br/>
                                            年终奖将在年前发放！';
                                    $this->emailClass ->send('年终奖信息', $str, $val['email']);
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
                $str='<tr><td colspan="10">请上传数据！</td></tr>';
            }elseif (!move_uploaded_file( $_FILES["imfile"]["tmp_name"], $excelfilename) ){
                $str='<tr><td colspan="10">上传失败！</td></tr>';
            }else{
                //读取excel信息
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
                        $str.='<tr><td>序号</td>';
                        foreach($excelFields as $val){
                            $str.='<td>'.$val.'</td>';
                        }
                        $str.='</tr>';
                        $i=0;
                        foreach($excelArr['员工号'] as $key=>$val ){
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
                //子公司合并
                if($flag=='zgs_ini'){
                    if(count($excelArr)&&!empty($excelArr)){
                        $deptInfo=array();
                        $areaInfo=array(''=>'1');
                        $jobInfo=array();
                        $sexInfo=array('男'=>'0','女'=>'1');
                        $eduInfo=array("专科"=>'1',"初中"=>'2',"高中"=>'3',"中专"=>'4',"大专"=>'5'
                            ,"本科"=>'6',"硕士"=>'7',"研究生"=>'8',"博士"=>'9',"未接受正规教育"=>'10');
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
                        $str.='<tr><td>序号</td>';
                        foreach($excelFields as $val){
                            $str.='<td>'.$val.'</td>';
                        }
                        $str.='</tr>';
                        $i=0;
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $i++;
                            $str.='<tr>';
                            $str.='<td>'.$i.'</td>';
                            foreach($excelFields as $fval){
                                $col='';
                                if(empty($deptInfo[$excelArr[$fval][$key]])&&$fval=='部门'){
                                    $col=' color="red" ';
                                }
                                if(!empty($userInfo[$excelArr[$fval][$key]])&&$fval=='OA用户名'){
                                    $col=' color="red" ';
                                    $ckr.=$excelArr[$fval][$key].'<br>';
                                }
                                if(empty($eduInfo[$excelArr[$fval][$key]])&&$fval=='学历'){
                                    $col=' color="red" ';
                                }
                                if(empty($brInfo[$excelArr[$fval][$key]])&&$fval=='公司'){
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
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $str.='<tr>';
                            foreach($excelFields as $fval){
                                $str.='<td>'.$excelArr[$fval][$key].'</td>';
                            }
                            $str.='</tr>';
                        }
                    }
                }
                if($flag=='hrupdate'){
                    if(!in_array('员工号', $excelFields)||!in_array('姓名', $excelFields)
                        ||!in_array('归属办事处', $excelFields)||!in_array('职位', $excelFields))
                    {
                        throw new Exception('Update failed');
                    }
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['姓名'][$key];
                            $infoE[$val]['blg']=$excelArr['归属办事处'][$key];
                            $infoE[$val]['pos']=$excelArr['职位'][$key];
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
                                    <td>序号</td>
                                    <td>员工号</td>
                                    <td>姓名</td>
                                    <td>归属办事处</td>
                                    <td>职位</td>
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
                    if(!in_array('员工号', $excelFields)||!in_array('姓名', $excelFields)
                        ||!in_array('技术岗位', $excelFields)||!in_array('技术岗位工资', $excelFields))
                    {
                        throw new Exception('Update failed');
                    }
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['姓名'][$key];
                            $infoE[$val]['lev']=$excelArr['技术岗位'][$key];
                            $infoE[$val]['sal']=$excelArr['技术岗位工资'][$key];
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
                                    <td>序号</td>
                                    <td>员工号</td>
                                    <td>姓名</td>
                                    <td>技术岗位</td>
                                    <td>技术岗位工资</td>
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
                                    <td style="color:red;">请检查员工是否网优部或已离职！</td>
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
                            $infoA[$row['user_id']]['leave']='<font color="red">离职</font>';
                        }else{
                            $infoA[$row['user_id']]['leave']='在职';
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
                            $sk=array_search($val['name'], $excelArr['姓名']);
                            if($sk!==FALSE&&$excelArr['工号'][$sk]){
                                $ckB[]=$excelArr['工号'][$sk];
                                /*
                                if(array_key_exists($excelArr['工号'][$sk], $infoB)){
                                    $infoB[$excelArr['工号'][$sk]]['name']=$infoB[$excelArr['工号'][$sk]]['name'].$val['name'];
                                }else{
                                    $infoB[$excelArr['工号'][$sk]]['name']=$val['name'];
                                }
                                */
                                $infoB[$excelArr['工号'][$sk]]['name']=$val['name'];
                                $infoB[$excelArr['工号'][$sk]]['leave']=$val['leave'];
                                $infoB[$excelArr['工号'][$sk]]['sex']=$excelArr['性别'][$sk];
                                $infoB[$excelArr['工号'][$sk]]['dept']=$excelArr['部门'][$sk];
                            }elseif($val['leave']=='在职'){
                                $infoC[$key]=$val;
                            }else{
                                $infoD[$key]=$val;
                            }
                        }
                        foreach($excelArr['姓名'] as $key=>$val){
                            if(array_key_exists($excelArr['工号'][$key], $infoF)){
                                $infoF[$excelArr['工号'][$key]]+=1;
                            }else{
                                $infoF[$excelArr['工号'][$key]]=1;
                            }
                            if(array_search($val, $ckA)===false){
                                $infoE[$key]=$val;
                            }
                            if(array_search($excelArr['工号'][$key], $ckB)===false){
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
                    if(!in_array('员工号', $excelFields)||!in_array('名字', $excelFields)
                        ||!in_array('年终奖', $excelFields)
                        ||!in_array('扣税', $excelFields)||!in_array('实发金额', $excelFields))
                    {
                        throw new Exception('Update failed');
                    }
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['名字'][$key];
                            $infoE[$val]['yam']=$excelArr['年终奖'][$key];
                            $infoE[$val]['kam']=$excelArr['扣税'][$key];
                            $infoE[$val]['pam']=$excelArr['实发金额'][$key];
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
                                    <td>序号</td>
                                    <td>员工号</td>
                                    <td>名字</td>
                                    <td>年终奖</td>
                                    <td>扣税</td>
                                    <td>实发金额</td>
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
                                    <td style="color:red;">请检查员工号是否正确！</td>
                                </tr>';
                            }
                            $i++;
                        }
                    }
                }
            }
        }catch(Exception $e){
            $str='<tr><td colspan="10">导入数据错误，请检查格式！'.$e->getMessage().'</td></tr>';
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
    
    //*********************************析构函数************************************
    function __destruct() {
    }

}

?>