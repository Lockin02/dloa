<?php

class model_salary_entity extends model_base {

    public $page;
    public $num;
    public $start;
    public $db;
    private $salaryClass;
    public $userSta;
    public $nowy;
    public $nowm;
    public $zero;
    public $globalUtil;
    public $openMon;
    public $floatPer;
    public $emailClass;
    public $userLevel;
    public $userSpe;
    public $speType;
    public $flowName;
    public $flowSta;
    public $flowStepSta;
    public $expflag;
    public $fnStatU;
    public $flaotMon;
    public $yebyear;
    public $salarySql;
    public $salaryCom;
    public $accType;
    public $divDept;

    //*******************************构造函数***********************************
    function __construct() {
        set_time_limit(300);
        if ($_POST) {
            $_POST = mb_iconv($_POST);
        }
        if ($_GET) {
            $_GET = mb_iconv($_GET);
        }
        parent::__construct();
        $this->db = new mysql();
        $this->salaryClass = new model_salary_util();
        $this->login_ck();
        $this->zero = $this->salaryClass->encryptDeal(0);
        $this->globalUtil = new includes_class_global();
        $this->emailClass = new includes_class_sendmail();
        $this->userSta = array(0 => '新入职', 1 => '试用期', 2 => '已转正', 3 => '离职');
        $this->userLevel = array(0 => '总经理', 1 => '副总', 2 => '总监', 3 => '经理',4=>'非管理层');
        $this->userSpe = array(0 => '编辑', 1 => '提交', 2 => '打回', 3 => '完成');
        $this->speType = array(0 => '补发', 1 => '扣除');
        $this->accType = array(0 => '参与计税', 1 => '不参与计税');
        $this->flowName = array('spe' => '工资特殊','nym_4' => '非年度调薪-普通'
                                ,'nym_3' => '非年度调薪-经理','nym_2' => '非年度调薪-总监'
                                ,'nym_1' => '非年度调薪-副总','nym_0' => '非年度调薪-副总'
                                ,'pro' => '项目奖','bos'=>'工资奖金' , 'sdyhr'=>'人事补贴'
                                ,'fla'=>'季度奖','ymd'=>'年度调薪'
                            );
        $this->flowSta=array(0=>'待审',1=>'正在审批',2=>'完成',3=>'打回');
        $this->flowStepSta=array(''=>'待审','yes'=>'通过','no'=>'打回');
        $this->expflag=array(0=>'公司员工',1=>'外派员工');
        $this->page = intval($_GET['page']) ? intval($_GET['page']) : 1;
        $this->start = ($this->page == 1) ? 0 : ($this->page - 1) * pagenum;
        $this->num = intval($_GET['num']) ? intval($_GET['num']) : false;
        $this->openMon = array(1, 4, 7, 10);
        $this->floatPer = array(10 => 0.1, 20 => 0.2, 30 => 0.3);
        $this->fnStatU=array('yanping.li','rlchen','dafa.yu','admin','yunxia.zhu');
        $this->flaotMon=array("1"=>1 ,"2"=>2,"3"=>3
                                ,"4"=>1 ,"5"=>2,"6"=>3
                                ,"7"=>1 ,"8"=>2,"9"=>3
                                ,"10"=>1 ,"11"=>2,"12"=>3
                        );
        $this->yebyear='2012';
        $this->divDept=array(113,114,115);//专区 113,114,115,'66','71','40'
$this->salarySql=array('dl'=>'dloa','sy'=>'shiyuanoa','br'=>'beiruanoa');
$this->salaryCom=array('dl'=>'世纪鼎利','sy'=>'世源信通','br'=>'广州贝软');
        $this->model_get_data();
        $this->model_salary_ini();
        //$this->get_decrypt_deal();
        //$this->model_flow_auto_do();//自动审批
        //$this->update_salary_yeb();//年终奖计算
        //$this->update_salary();
        //$this->model_cesse_chg();//临时税率问题
        //$this->model_float_chg();//临时季度奖问题
//        $this->deal_pro();
        
    }

    //*********************************数据处理********************************
    function login_ck(){
        if($_SESSION['prikey']==""&&!$_POST['prikey']){
            include(WEB_TOR.'model/salary/login_salary.php');
            die();
        }elseif($_SESSION['prikey']==""&&$_POST['prikey']){
            $checkZero=$this->salaryClass->rsaClass->decrypt(
                    $this->salaryClass->salaryRsa['SalaryZero']
                    ,$_POST['prikey']
                    ,$this->salaryClass->salaryRsa['SalaryModulo']);
            if($checkZero!='0'){
                echo '4';
            }else {
                @session_start();
                $_SESSION['prikey']=$this->salaryClass->configCrypt(trim($_POST['prikey']));
            }
            die();
        }elseif(!empty($_SESSION['prikey'])){
            $checkZero=$this->salaryClass->rsaClass->decrypt(
                    $this->salaryClass->salaryRsa['SalaryZero']
                    ,$this->salaryClass->configCrypt($_SESSION['prikey'], 'decode')
                    ,$this->salaryClass->salaryRsa['SalaryModulo']);
            if($checkZero!='0'){
                echo 'Session 超时 或 私钥错误 。 请重新登录 ！ ';
                die();
            }
        }
    }
    //********************************显示数据**********************************

    function model_hr() {
        
    }
    /**
     *
     * @param <type> $flag true 人事 flase 部门
     * @param <type> $sqlflag
     * @return <type> 
     */
    function model_hr_join_list($flag=true,$sqlflag=''){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $seadept = $_REQUEST['seadept'];
        $seaname = $_REQUEST['seaname'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        if($flag==false){
            $sqlSch.=$sqlflag;
        }
        if($seadept){
            $sqlSch.=" and d.dept_name like '%".$seadept."%' ";
        }
        if($seaname){
            $sqlSch.=" and s.oldname like '%".$seaname."%' ";
        }
        $start = $limit * $page - $limit;
        //总数
        if($flag){
            $sql = "select count(*)
                from salary s
                    left join hrms h on (s.userid=h.user_id)
                    left join user u1 on (s.userid=u1.user_id)
                    left join department d on (u1.dept_id=d.dept_id)
                where
                    s.userid=h.user_id
                    and ( s.usersta='0' or
                            ( year(s.probationdt)='".$this->nowy."' and  month(s.probationdt)='".$this->nowm."' )
                        or s.freezeflag='1' or
                            ( year(s.recovercdt)='".$this->nowy."' and  month(s.recovercdt)='".$this->nowm."' )
                        )
                    $sqlSch ";
        }else{
            $sql = "select count(*)
                from salary s
                    left join hrms h on (s.userid=h.user_id)
                    left join user u1 on (s.userid=u1.user_id)
                    left join department d on (u1.dept_id=d.dept_id)
                where
                    s.userid=h.user_id
                    and ( s.usersta='0' or ( year(s.probationdt)='".$this->nowy."' and  month(s.probationdt)='".$this->nowm."' ) )
                    $sqlSch ";
        }
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        if($flag){
            $sql="select
                    s.rand_key , s.username , s.userid , s.olddept , h.userlevel
                    , s.comedt , s.probationam , s.probationnowam , s.probationdt
                    , u.user_name , s.usersta
                    , s.oldarea , s.idcard , s.acc , s.accbank , s.email , s.oldname
                    , h.expflag , s.freezeflag , s.recoverdt , s.recoveram , s.recovernowam , s.recovercdt
                    , u1.company , u1.salarycom 
                from salary s
                    left join hrms h on ( s.userid=h.user_id )
                    left join user u on (s.probationuser=u.user_id)
                    left join user u1 on (s.userid=u1.user_id)
                    left join department d on (u1.dept_id=d.dept_id)
                where
                    s.userid=h.user_id
                    and ( s.usersta='0' or
                            ( year(s.probationdt)='".$this->nowy."' and  month(s.probationdt)='".$this->nowm."' )
                        or s.freezeflag='1' or
                            ( year(s.recovercdt)='".$this->nowy."' and  month(s.recovercdt)='".$this->nowm."' )
                        )
                    $sqlSch
                order by s.usersta asc , h.userlevel desc , s.probationdt desc , $sidx $sord
                limit $start , $limit ";
        }else{
            $sql="select
                    s.rand_key , s.username , s.userid , s.olddept , h.userlevel
                    , s.comedt , s.probationam , s.probationnowam , s.probationdt
                    , u.user_name , s.usersta
                    , s.oldarea , s.idcard , s.acc , s.accbank , s.email , s.oldname
                    , h.expflag , u1.company , u1.salarycom 
                from salary s
                    left join hrms h on ( s.userid=h.user_id )
                    left join user u on (s.probationuser=u.user_id)
                    left join user u1 on (s.userid=u1.user_id)
                    left join department d on (u1.dept_id=d.dept_id)
                where
                    s.userid=h.user_id
                    and ( s.usersta='0' or ( year(s.probationdt)='".$this->nowy."' and  month(s.probationdt)='".$this->nowm."' ) )
                    $sqlSch
                order by s.usersta asc , h.userlevel desc , s.probationdt desc , $sidx $sord
                limit $start , $limit ";
        }
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $compt=$row['company'];
            if(!empty($row['salarycom'])){
                $compt=$row['salarycom'];
            }
            $ck='no';
            $pa='';
            $pna='';
            if($row['usersta']==0){
                $ck='yes';
            }elseif(substr($row['probationdt'], 0, 4)==$this->nowy&&substr($row['probationdt'], 5, 2)==$this->nowm){
                $ck='yes';
            }
            if($row['userlevel']!=4&&$flag){
                $pa='-';
                $pna='-';
            }else{
                $pa=$this->salaryClass->decryptDeal($row['probationam']);
                $pna=$this->salaryClass->decryptDeal($row['probationnowam']);
            }
            $responce->rows[$i]['id'] = $row['userid'];
            if($flag){
                if($row['freezeflag']=='1'||( substr($row['recovercdt'], 0, 4)==$this->nowy&&substr($row['recovercdt'], 5, 2)==$this->nowm )){
                    $pa=$this->salaryClass->decryptDeal($row['recoveram']);
                    $pna=$this->salaryClass->decryptDeal($row['recovernowam']);
                    $dt=$row['recoverdt'];
                    $cdt=$row['recovercdt'];
                    $ck='yes';
                    if($row['freezeflag']=='1'){
                        $st='冻结';
                    }else{
                        $st='已恢复';
                    }
                }else{
                    $dt=$row['comedt'];
                    $cdt=$row['probationdt'];
                    $st=$this->userSta[$row['usersta']];
                }
                $responce->rows[$i]['cell'] = un_iconv(
                            array("", $row['rand_key'], $row['oldname'],$this->salaryCom[$compt], $this->expflag[$row['expflag']],$row['olddept']
                                , $this->userLevel[$row['userlevel']]
                                , $dt, $pa
                                , $pna
                                , $row['oldarea'] , $row['idcard'] , $row['acc'] ,$row['accbank'] , $row['email']
                                , $cdt
                                , $row['user_name']
                                , $st
                                , $ck
                                , $compt
                            )
                        );
            }else{
                $responce->rows[$i]['cell'] = un_iconv(
                            array("", $row['rand_key'], $row['oldname'],$row['olddept']
                                , $this->userLevel[$row['userlevel']]
                                , $row['comedt'], $pa
                                , $pna
                                , $row['oldarea'] , $row['idcard'] , $row['acc'] ,$row['accbank'] , $row['email']
                                , $row['probationdt']
                                , $row['user_name']
                                , $this->userSta[$row['usersta']]
                                , $ck
                                , $compt
                            )
                        );
            }
            $i++;
        }
        return $responce;
    }
    /**
     * 入职处理
     */
    function model_hr_join_in($flag=true){
        $id = $_POST['id'];
        $val = round($_POST['prob'],2);
        $cdt = $_POST['cdt'];
        $compt = $_POST['compt'];
        $comtable = $this->get_com_sql($compt);
        try {
            $this->db->query("START TRANSACTION");
            $sql = "select
                    s.userid ,u.dept_id , s.oldarea as area
                    ,s.amount , s.floatam
                    ,s.gjjam , s.shbam , s.newflag , s.cogjjam , s.coshbam
                    ,s.prepaream , s.handicapam , s.manageam , s.cessebase
                    ,s.olddept
                    ,s.freezeflag , s.freezesta , p.id as pid
                    ,s.recovercdt
                from salary s
                    left join hrms h on (s.userid=h.user_id)
                    left join user u on (s.userid=u.user_id)
                    left join area a on (u.area=a.id)
                    left join ".$comtable."salary_pay p on (p.userid=s.userid and p.pyear='" . $this->nowy . "' and p.pmon='" . $this->nowm . "' )
                where s.rand_key='$id' ";
            $resck=$this->db->get_one($sql);
            if($resck['freezeflag']=='1'||( substr($resck['recovercdt'], 0, 4)==$this->nowy&&substr($resck['recovercdt'], 5, 2)==$this->nowm ) ){
               //恢复
                if(empty($cdt)){
                     throw new Exception('Data status incorrect');
                }
                if (strtotime($cdt) < strtotime(date('Y-m') . '-01')) {
                    $baseNow = 0;
                } else {
                    $baseNow = $this->salaryClass->salaryDeal($cdt, $val);
                }
                $this->model_salary_update($id,
                        array('amount' => $val
                            , 'recoveram'=>$val
                            , 'recovernowam'=>$baseNow
                            , 'usersta' => $resck['freezesta']
                            , 'recovercdt' => 'now()', 'recoveruser' => $_SESSION['USER_ID']
                            ,'oldname'=>$_POST['username']
                            ,'olddept'=>$_POST['dept'] , 'oldarea'=>$_POST['area']
                            ,'idcard'=>$_POST['idcard'],'acc'=>$_POST['acc']
                            ,'accbank'=>$_POST['accbank'],'email'=>$_POST['email']
                            ,'recoverdt' =>$cdt
                            ,'freezeflag'=>0
                        )
                        , array(3,4,5,6,7,8,9,10,11,12,13,14,15));
                if($baseNow=='0'||empty($baseNow)){
                    $baseAm = $val;
                }else{
                    $baseAm = $baseNow;
                }
                if(empty($resck['pid'])){
                    $totalAm = $this->salaryClass->cfv($baseAm);
                    $cesseAm = $this->salaryClass->cfv($totalAm);
                    $payCesse = $this->salaryClass->cesseDeal($cesseAm, $resck['cessebase']);
                    $payTotal = $this->salaryClass->cfv($cesseAm - $payCesse);
                    $sql = "insert into
                        salary_pay
                    set
                        userid='" . $resck['userid'] . "'
                        , deptid='" . $resck['dept_id'] . "'
                        , area='" . $resck['area'] . "'
                        , salarydept='" . $resck['olddept'] . "'
                        , pyear='" . $this->nowy . "'
                        , pmon='" . $this->nowm . "'
                        , baseam='" . $this->salaryClass->encryptDeal($val) . "'
                        , basenowam='" . $this->salaryClass->encryptDeal($baseAm) . "'
                        , floatam='" . $this->zero . "'
                        , gjjam='" . $this->zero . "'
                        , shbam='" . $this->zero . "'
                        , totalam='" . $this->salaryClass->encryptDeal($totalAm) . "'
                        , cesseam='" . $this->salaryClass->encryptDeal($cesseAm) . "'
                        , paycesse='" . $this->salaryClass->encryptDeal($payCesse) . "'
                        , paytotal='" . $this->salaryClass->encryptDeal($payTotal) . "'
                        , cogjjam='" . $resck['cogjjam'] . "'
                        , coshbam='" . $resck['coshbam'] . "'
                        , prepaream='" . $resck['prepaream'] . "'
                        , handicapam='" . $resck['handicapam'] . "'
                        , manageam='" . $resck['manageam'] . "'
                        , cessebase='".$resck['cessebase']."'
                        , createdt=now()
                        , nowamflag=5 ";
                    $this->db->query_exc($sql);
                }else{
                    $this->model_pay_update($resck['pid'],
                            array('baseam' => $val, 'basenowam' => $baseNow, 'nowamflag' => '5')
                            , array(2));
                    $this->model_pay_stat($resck['pid']);
                }
            }else{//入职
                $sql = "select
                        p.id , s.comedt , h.userlevel , s.userid , s.usersta
                    from ".$comtable."salary_pay p
                        left join salary s on (p.userid=s.userid)
                        left join hrms h on (s.userid=h.user_id)
                    where
                        p.pyear='" . $this->nowy . "' and p.pmon='" . $this->nowm . "' and p.userid=s.userid
                        and  s.rand_key='$id' ";
                $res = $this->db->get_one($sql);
                if(!$res['userid']){
                    throw new Exception('No data query');
                }
                if($res['usersta']>1){
                    throw new Exception('Data status incorrect');
                }
                if(!empty($cdt)){
                    $res['comedt']=$cdt;
                }
                if($flag){//人事
                    if($res['userlevel']==4){//非管理层员工
                        if (strtotime($res['comedt']) < strtotime(date('Y-m') . '-01')) {
                            $baseNow = 0;
                        } else {
                            $baseNow = $this->salaryClass->salaryDeal($res['comedt'], $val);
                        }
                        $this->model_salary_update($id,
                                array('amount' => $val, 'probationam' => $val, 'usersta' => '1'
                                    , 'probationdt' => 'now()', 'probationuser' => $_SESSION['USER_ID']
                                    ,'probationnowam'=>$this->salaryClass->salaryDeal($res['comedt'], $val)
                                    ,'oldname'=>$_POST['username']
                                    ,'olddept'=>$_POST['dept'] , 'oldarea'=>$_POST['area']
                                    ,'idcard'=>$_POST['idcard'],'acc'=>$_POST['acc']
                                    ,'accbank'=>$_POST['accbank'],'email'=>$_POST['email']
                                    ,'comedt' =>date('Ymd',strtotime($res['comedt']))
                                )
                                , array(2, 3, 4,6,7,8,9,10,11,12,13));
                        $this->model_pay_update($res['id'],
                                array('baseam' => $val, 'basenowam' => $baseNow, 'nowamflag' => '1', 'leaveflag'=>'0')
                                , array(2,3),$comtable);
                        $this->model_pay_stat($res['id'],$comtable);
                    }else{//管理层
                        $this->model_salary_update($id,
                                array(
                                    'oldname'=>$_POST['username']
                                    ,'olddept'=>$_POST['dept'] , 'oldarea'=>$_POST['area']
                                    ,'idcard'=>$_POST['idcard'],'acc'=>$_POST['acc']
                                    ,'accbank'=>$_POST['accbank'],'email'=>$_POST['email']
                                    ,'comedt' =>date('Ymd',strtotime($res['comedt']))
                                ),array(0,1,2,3,4,5,6,7)
                        );
                    }
                }else{
                    if (strtotime($res['comedt']) < strtotime(date('Y-m') . '-01')) {
                        $baseNow = 0;
                    } else {
                        $baseNow = $this->salaryClass->salaryDeal($res['comedt'], $val);
                    }
                    $this->model_salary_update($id,
                            array('amount' => $val, 'probationam' => $val, 'usersta' => '1'
                                , 'probationdt' => 'now()', 'probationuser' => $_SESSION['USER_ID']
                                ,'probationnowam'=>$this->salaryClass->salaryDeal($res['comedt'], $val)
                            )
                            , array(2, 3, 4));
                    $this->model_pay_update($res['id'],
                            array('baseam' => $val, 'basenowam' => $baseNow, 'nowamflag' => '1', 'leaveflag'=>'0')
                            , array(2,3),$comtable);
                    $this->model_pay_stat($res['id'],$comtable);
                }
            }
            $this->db->query("COMMIT");
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '员工入职', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '员工入职', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
    /**
     *转正
     */
    function model_hr_pass_list($flag=true,$sqlflag=''){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $seadept = $_REQUEST['seadept'];
        $seaname = $_REQUEST['seaname'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        if($flag==false){
            $sqlSch.=$sqlflag;
        }
        if($seadept){
            $sqlSch.=" and d.dept_name like '%".$seadept."%' ";
        }
        if($seaname){
            $sqlSch.=" and u1.user_name like '%".$seaname."%' ";
        }
        $start = $limit * $page - $limit;
        //总数 
        $sql = "select count(*)
            from salary s
                left join hrms h on (s.userid=h.user_id)
                left join user u1 on (s.userid=u1.user_id)
                left join department d on (u1.dept_id=d.dept_id)
            where
                s.userid=h.user_id
                and s.usersta>=1
                and ( s.usersta=1 or ( year(s.passuserdt)='".$this->nowy."' and month(s.passuserdt)='".$this->nowm."' ) )
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select
                s.rand_key , u1.user_name as username , s.userid , d.dept_name as olddept , h.userlevel , h.join_date
                , s.passdt , s.passam , s.passnowam , s.passuserdt
                , u.user_name , s.usersta
                , h.userlevel , h.expflag
                , s.passoldam
                , u1.company , u1.salarycom 
            from salary s
                left join hrms h on ( s.userid=h.user_id )
                left join user u on (s.passuser=u.user_id)
                left join user u1 on (s.userid=u1.user_id)
                left join department d on (u1.dept_id=d.dept_id)
            where
                s.userid=h.user_id
                and s.usersta>=1
                and ( s.usersta=1 or ( year(s.passuserdt)='".$this->nowy."' and month(s.passuserdt)='".$this->nowm."' ) )
                $sqlSch
            order by s.usersta asc , h.userlevel desc , s.passuserdt desc , $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $compt=$row['company'];
            if(!empty($row['salarycom'])){
                $compt=$row['salarycom'];
            }
            $ck='no';
            $pa='';
            $pna='';
            $tdt=empty($row['passdt'])?$row['join_date']:$row['passdt'];
            if($row['usersta']==1){
                $ck='yes';
            }elseif(substr($row['passuserdt'], 0, 4)==$this->nowy&&substr($row['passuserdt'], 5, 2)==$this->nowm){
                if($row['userlevel']!=4&&$row['passam']&&$flag){
                    $ck='no';
                }else{
                    $ck='yes';
                }
            }
            if($row['userlevel']!=4&&$flag){
                $pa='-';
                $pna='-';
                $poa='-';
            }else{
                $pa=$this->salaryClass->decryptDeal($row['passam']);
                $pna=$this->salaryClass->decryptDeal($row['passnowam']);
                $poa=$this->salaryClass->decryptDeal($row['passoldam']);
            }
            $responce->rows[$i]['id'] = $row['userid'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array("", $row['rand_key'], $row['username'],$this->salaryCom[$compt], $row['olddept'],$this->expflag[$row['expflag']]
                                , $this->userLevel[$row['userlevel']]
                                , $tdt
                                , $pa
                                , $pna
                                , $poa
                                , $row['passuserdt']
                                , $row['user_name']
                                , $this->userSta[$row['usersta']]
                                , $ck
                                , $compt
                            )
            );
            $i++;
        }
        return $responce;
    }
    /**
     * 转正处理
     */
    function model_hr_pass_in($flag=true){
        $id = $_POST['id'];
        $passam = $_POST['passam'];
        $passdt = $_POST['passdt'];
        $compt = $_POST['compt'];
        $comtable = $this->get_com_sql($compt);
        $sm=false;
        try {
            $this->db->query("START TRANSACTION");
            $sql="select
                    p.id , s.amount , h.userlevel , s.userid , s.usersta , s.passoldam , s.username , s.passdt , h.expflag 
                from ".$comtable."salary_pay p
                    left join salary s on (p.userid=s.userid)
                    left join hrms h on (s.userid=h.user_id)
                where
                    p.pyear='" . $this->nowy . "' and p.pmon='" . $this->nowm . "' and p.userid=s.userid
                    and  s.rand_key='$id'  ";
            $res = $this->db->get_one($sql);
            if(!$res['userid']){
                throw new Exception('No data query ');
            }
            if($res['usersta']>2||$res['usersta']==0){
                throw new Exception('Data status incorrect');
            }
            if($flag){
                if ($res['userlevel'] == 4) {
                    if($res['usersta']==2){
                        $passOldAm=$this->salaryClass->decryptDeal($res['passoldam']);
                    }else{
                        $passOldAm=$this->salaryClass->decryptDeal($res['amount']);
                    }
                    $passNowAm=$this->salaryClass->salaryPass($passOldAm, $passam, $passdt);
                    if(date('Y-m',  strtotime($passdt))==date('Y-m')){//当月转正
                        $baseNowAm = $passNowAm;
                    }else{
                        $baseNowAm = 0;
                    }
                    $this->model_salary_update($id,
                            array('amount' => $passam, 'passam' => $passam, 'usersta' => '2'
                                , 'passdt' => $passdt
                                , 'passuserdt' => 'now()', 'passuser' => $_SESSION['USER_ID']
                                , 'passnowam'=>$passNowAm
                                , 'passoldam'=>$passOldAm
                            )
                            , array(2, 3, 4,5));
                    $this->model_pay_update($res['id'],
                            array('baseam' => $passam, 'basenowam' => $baseNowAm, 'nowamflag' => '2')
                            , array(2),$comtable);
                    $this->model_pay_stat($res['id'],$comtable);
                } else {
                    $sm=true;
                    $this->model_salary_update($id, array('passdt' => $passdt, 'passuser' => $_SESSION['USER_ID']), array(0, 1));
                }
                $sql="update hrms set join_date='".$passdt."' where user_id='".$res['userid']."' ";
                $this->db->query_exc($sql);
            }else{
                if(!$res['passdt']){
                    throw new Exception('Data state error1');
                }
                $passdt=$res['passdt'];
                if($res['usersta']==2){
                    $passOldAm=$this->salaryClass->decryptDeal($res['passoldam']);
                }else{
                    $passOldAm=$this->salaryClass->decryptDeal($res['amount']);
                }
                $passNowAm=$this->salaryClass->salaryPass($passOldAm, $passam, $passdt);
                if(date('Y-m',  strtotime($passdt))==date('Y-m')){//当月转正
                    $baseNowAm = $passNowAm;
                }else{
                    $baseNowAm = 0;
                }
                $this->model_salary_update($id,
                        array('amount' => $passam, 'passam' => $passam, 'usersta' => '2'
                            , 'passdt' => $passdt
                            , 'passuserdt' => 'now()', 'passuser' => $_SESSION['USER_ID']
                            , 'passnowam'=>$passNowAm
                            , 'passoldam'=>$passOldAm
                        )
                        , array(2, 3, 4,5));
                $this->model_pay_update($res['id'],
                        array('baseam' => $passam, 'basenowam' => $baseNowAm, 'nowamflag' => '2')
                        , array(2),$comtable);
                $this->model_pay_stat($res['id'],$comtable);
            }
            $this->db->query("COMMIT");
            if($sm){
                $emaildb=$this->model_get_superiors($res['userid']);
                $body='您好！<br>
                    员工：'.$res['username'].',已转正，需要您录入转正后的工资。<br>
                    谢谢！';
                $this->model_send_email('提醒--转正工资录入', $body, $emaildb, false);
            }
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '员工转正', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '员工转正', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
     *离职
     */
    function model_hr_leave_list(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $seadept = $_REQUEST['seadept'];
        $seaname = $_REQUEST['seaname'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        if($seadept){
            $sqlSch.=" and d.dept_name like '%".$seadept."%' ";
        }
        if($seaname){
            $sqlSch.=" and u1.user_name like '%".$seaname."%' ";
        }
        $start = $limit * $page - $limit;
        //总数
        $sql = "select count(*)
            from salary s
                left join user u1 on (u1.user_id=s.userid)
                left join department d on (u1.dept_id=d.dept_id)
            where
                1
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select
                s.rand_key , u1.user_name as username , d.dept_name as olddept , s.leavedt
                , s.leavecreatedt , u.user_name , s.usersta
                , h.expflag , s.freezedt , u2.user_name as freezeuser , s.freezecdt , s.freezeflag
                , u1.company , u1.salarycom 
            from salary s
                left join user u on (s.leavecreator=u.user_id)
                left join user u1 on (u1.user_id=s.userid)
                left join department d on (u1.dept_id=d.dept_id)
                left join hrms h on (s.userid=h.user_id)
                left join user u2 on (u2.user_id=s.freezeuser)
            where
                1
                $sqlSch
            order by s.usersta , s.leavecreatedt desc , $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $compt=$row['company'];
            if(!empty($row['salarycom'])){
                $compt=$row['salarycom'];
            }
            if($row['freezeflag']=='1'){
                $dt=$row['freezedt'];
                $us=$row['freezeuser'];
                $cdt=$row['freezecdt'];
                $st='冻结';
            }else{
                $dt=$row['leavedt'];
                $us=$row['user_name'];
                $cdt=$row['leavecreatedt'];
                $st=$this->userSta[$row['usersta']];
            }
            if((date('Y', strtotime($dt))==$this->nowy&&date('n',strtotime($dt))==$this->nowm)
                    ||(date('Y', strtotime($cdt))==$this->nowy&&date('n',strtotime($cdt))==$this->nowm)){
                $ck='yes';
            }else{
                $ck='no';
            }
            $responce->rows[$i]['id'] = $row['userid'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array("", $row['rand_key']
                                , $row['username']
                                , $this->salaryCom[$compt]
                                , $row['olddept']
                                , $this->expflag[$row['expflag']]
                                , $dt
                                , $cdt
                                , $us
                                , $st
                                , $ck
                                , $compt
                            )
            );
            $i++;
        }
        return $responce;
    }
    /**
     * 离职处理
     */
    function model_hr_leave_in(){
        $id = $_POST['id'];
        $leavedt = $_POST['leavedt'];
        $actflag = $_POST['actflag'];
        $compt = $_POST['compt'];
        $comtable = $this->get_com_sql($compt);
        $sm=false;
        try {
            if(strtotime($leavedt)>time()&&false){//去掉时间控制
                throw new Exception('leave date can not more than today');
            }
            $this->db->query("START TRANSACTION");
            $sql="select
                    p.id , h.expflag , p.userid , p.baseam , s.comedt , s.usersta , s.amount
                from ".$comtable."salary_pay p
                    left join salary s on (p.userid=s.userid)
                    left join hrms h on (s.userid=h.user_id)
                where
                    p.pyear='" . $this->nowy . "' and p.pmon='" . $this->nowm . "' and p.userid=s.userid
                    and  s.rand_key='$id'  ";
            $res = $this->db->get_one($sql);
            if(!$res['id']){
                throw new Exception('No data query ');
            }
            if($actflag=='lz'){//离职
                if(empty($leavedt)){//不离职
                    $this->model_salary_update($id,
                        array('sta' => 1
                            , 'usersta' => 2
                            , 'leavedt' => null
                            , 'leavecreatedt' => 'now()'
                            , 'leavecreator'=>$_SESSION['USER_ID']
                        )
                        , array(0,1,2,3,4));
                    //统一
                    $pid=$res['id'];
                    //离职处理离职日期在上一个月的离职人员时，为了不影响上个月工资数据，统一只能勾选当月一号；结算时，手动调整出勤日期为0。
                    //本月数据恢复
                    $this->model_pay_update($pid,
                            array('nowamflag' => '0','leaveflag'=>'0')
                            , array(0,1),$comtable);
                    $this->model_pay_stat($pid,$comtable);
                    $sql="update hrms set userstatmp='1' , LEFT_DATE=null where user_id='".$res['userid']."'";
                    $this->db->query_exc($sql);
                    //新系统
                    $sql="update  oa_hr_personnel  p set p.quitDate=null 
                        where p.useraccount='".$res['userid']."'";
                    $this->db->query_exc($sql);
                }else{
                    $this->model_salary_update($id,
                        array('sta' => 1
                            , 'usersta' => 3
                            , 'leavedt' => $leavedt
                            , 'leavecreatedt' => 'now()'
                            , 'leavecreator'=>$_SESSION['USER_ID']
                        )
                        , array(0,1,2,3,4));
                    //统一
                    $pid=$res['id'];
                    //离职处理离职日期在上一个月的离职人员时，为了不影响上个月工资数据，统一只能勾选当月一号；结算时，手动调整出勤日期为0。
                    if(strtotime($leavedt) > strtotime(date('Y-m-t'))){//本月后离职
                        //本月数据恢复
                        $this->model_pay_update($pid,
                                array('nowamflag' => '0','leaveflag'=>'0')
                                , array(0,1),$comtable);
                        $this->model_pay_stat($pid,$comtable);

                    }else {//本月离职
                        $this->model_pay_update($pid,
                                array('nowamflag' => '3','leaveflag'=>'0')
                                , array(0,1),$comtable);
                    }
                    $sql="update hrms set userstatmp='1' , LEFT_DATE='".$leavedt."' where user_id='".$res['userid']."'";
                    $this->db->query_exc($sql);
                    //新系统
                    $sql="update  oa_hr_personnel  p set p.quitDate='".$leavedt."'  
                        where p.useraccount='".$res['userid']."'";
                    $this->db->query_exc($sql);
                }
                
            }elseif($actflag=='dj'){//冻结
                if(empty($leavedt)){//冻结日期为空，代表解冻
                    $this->model_salary_update($id,
                        array('sta' => 0
                            , 'usersta' => 2
                            , 'freezedt' => $leavedt
                            , 'freezeuser'=>$_SESSION['USER_ID']
                            , 'freezecdt'=>'now()'
                            , 'freezeflag'=>'0'
                            , 'freezesta'=>$res['usersta']  
                        ) 
                        , array(0,1,2,3,4,5,6)
                    );
                    $amount=$this->salaryClass->decryptDeal($res['amount']);
                    $pid=$res['id'];
                    $this->model_pay_update($pid,
                            array('basenowam' => '0', 'nowamflag' => '0','leaveflag'=>0,'baseam' => $amount)
                            , array(1,2),$comtable);
                    $this->model_pay_stat($pid,$comtable);
                }else{
                    $this->model_salary_update($id,
                        array('sta' => 1
                            , 'usersta' => 3
                            , 'freezedt' => $leavedt
                            , 'freezeuser'=>$_SESSION['USER_ID']
                            , 'freezecdt'=>'now()'
                            , 'freezeflag'=>'1'
                            , 'freezesta'=>$res['usersta']  
                        ) 
                        , array(0,1,2,3,4,5,6)
                    ); 
                    $amount=$this->salaryClass->decryptDeal($res['amount']);
                    $pid=$res['id'];
                    $baseNow = $this->salaryClass->getSalaryByDateToWorkDays($amount,$leavedt,false);
                    if($baseNow==0){//特别
                        $amount=$baseNow;
                    }
                    $this->model_pay_update($pid,
                            array('basenowam' => $baseNow, 'nowamflag' => '4','leaveflag'=>0,'baseam' => $amount)
                            , array(1,2),$comtable);
                    $this->model_pay_stat($pid,$comtable);
                }
            }
            $this->db->query("COMMIT");
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '员工离职', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '员工离职', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
     *部门查询离职 
     */
    function model_dp_leave_manager_list(){
        global $func_limit;
        $sqlflag='';
        $dppow=$this->model_dp_pow();
        if(!empty($func_limit['浏览部门'])){
            $sqlflag=" and ( s.deptid in ('". implode("','", $dppow['1'])."','" . implode("','", $dppow['2']) ."')
                or s.userid='".$_SESSION['USER_ID']."'
                or s.deptid in ( ".trim($func_limit['浏览部门'],',')." )
                ) ";
        }else{
            $sqlflag=" and ( s.deptid in ('". implode("','", $dppow['1'])."','" . implode("','", $dppow['2']) ."')
                or s.userid='".$_SESSION['USER_ID']."'
                ) ";
        }
        return $this->model_hr_leave_manager_list('list',$sqlflag);
    }
    /**
     *离职管理列表 
     * $outflag list 列表 xls 导出
     */
    function model_hr_leave_manager_list($outflag='list',$sqlflag=''){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $seadept = $_REQUEST['seadept'];
        $seaname = $_REQUEST['seaname'];
        $sealy = $_REQUEST['sealy']?$_REQUEST['sealy']:$this->nowy;
        $sealm = $_REQUEST['sealm']?$_REQUEST['sealm']:'';
        $seapy = $_REQUEST['seapy']?$_REQUEST['seapy']:'';
        $seapm = $_REQUEST['seapm']?$_REQUEST['seapm']:'';
        $seacom = $_REQUEST['seacom']?$_REQUEST['seacom']:'';
        $seaplf = isset($_REQUEST['seaplf'])?$_REQUEST['seaplf']:'-';
        $seapj = $_REQUEST['seapj']?$_REQUEST['seapj']:'';
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        if($seadept){
            $sqlSch.=" and d.dept_name like '%".$seadept."%' ";
        }
        if($seaname){
            $sqlSch.=" and u1.user_name like '%".$seaname."%' ";
        }
        if($sealy&&$sealy!='-'){
            $sqlSch.=" and year(s.leavedt) = '".$sealy."' ";
        }
        if($sealm&&$sealm!='-'){
            $sqlSch.=" and month(s.leavedt) = '".$sealm."' ";
        }
        if($seapy&&$seapy!='-'){
            $sqlSch.=" and s.lpy = '".$seapy."' ";
        }
        if($seapm&&$seapm!='-'){
            $sqlSch.=" and s.lpm = '".$seapm."' ";
        }
        if($seapj&&$seapj!='-'){
            $sqlSch.=" and s.lpj = '".$seapj."' ";
        }
        if($seaplf!='-'){
            $sqlSch.=" and s.payleaveflag = '".$seaplf."' ";
        }
        if($seacom){
            $sqlSch.=" and u1.company = '".$seacom."' ";
        }
        if($sqlflag){
            $sqlSch.=$sqlflag;
        }
        if($outflag=='list'){
            $start = $limit * $page - $limit;
            //总数
            $sql = "select count(*)
                from salary s
                    left join user u1 on (u1.user_id=s.userid)
                    left join department d on (u1.dept_id=d.dept_id)
                where
                    1
                    $sqlSch ";
            $rs = $this->db->get_one($sql);
            $count = $rs['count(*)'];
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages){
                $page = $total_pages;
            }
            $i = 0;
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;
            $listsql=" , $sidx $sord  limit $start , $limit ";
        }else{
            $responce=array();
        }
        $sql="select
                s.rand_key , u1.user_name as username , d.dept_name as olddept , s.leavedt
                , s.leavecreatedt , u.user_name , s.usersta
                , h.expflag 
                , u1.company , u1.salarycom 
                , year(s.leavedt) as py , month(s.leavedt) as pm , s.comedt 
                , s.accbank , s.acc , s.userid , j.name as jname , s.payleaveflag
                , if(lpy!='',concat(s.lpy,'-',s.lpm,'-',s.lpj), '') as lpd , h.usercard , d.pdeptname
                , s.idcard
            from salary s
                left join user u on (s.leavecreator=u.user_id)
                left join user u1 on (u1.user_id=s.userid)
                left join hrms h on (s.userid=h.user_id)
                left join department d on (u1.dept_id=d.dept_id)
                left join user_jobs j on (j.id=u1.jobs_id)
            where
                s.usersta=3
                $sqlSch
            order by s.payleaveflag , s.leavecreatedt desc ".$listsql;
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $res[$row['userid']]=array(
                'username'=>$row['username']
                ,'com'=>$this->salaryCom[$row['company']]
                ,'jname'=>$row['jname']
                ,'expflag'=>$row['expflag']
                ,'cdt'=>$row['comedt']
                ,'ldt'=>$row['leavedt']
                ,'lcdt'=>$row['leavecreatedt']
                ,'lcu'=>$row['user_name']
                ,'py'=>$row['py']
                ,'pm'=> $row['pm']
                ,'acc'=>$row['acc']
                ,'accbank'=> $row['accbank']
                ,'rk'=> $row['rand_key']
                ,'comcode'=> $row['company']
                ,'payleaveflag'=>$row['payleaveflag']
                ,'lpd'=>$row['lpd']
                ,'usercard'=>$row['usercard']
                ,'pdeptname'=>$row['pdeptname']
                ,'idcard'=>$row['idcard']
            );
            
        }
        if(!empty($res)){
            foreach($res as $key=>$val){
                $comtable = $this->get_com_sql($val['comcode']);
                if($val['payleaveflag']=='1'){
                    $sp=$this->model_get_pay(
                            array('userid'=>$key 
                                , 'pyear'=>$val['py']
                                , 'pmon'=>$val['pm']
                            ), 
                            array(
                                'BaseAm'
                                ,'BaseNowAm'
                                ,'PerHolsDays'
                                ,'SickHolsDays'
                                ,'HolsDelAm'
                                ,'SpeRewAm'
                                ,'AccDelAm'
                                ,'ShbAm'
                                ,'GjjAm'
                                ,'PayCesse'
                                ,'AccRewAm'
                                ,'PayTotal'
                                ,'SalaryDept'
                                ,'Remark'
                                ,'ID'
                                ,'hdar'
                                ,'bnar'
                                ,'srar'
                                ,'shbr'
                                ,'gjjr'
                                ,'sdar'
                                ,'pcr'
                                ,'arar'
                                ,'wdt'
                                ,'wdtr'
                                ,'OtherAccRewAm'
                                ,'AccRewAmCes'
                                ,'oarar'
                                ,'SdyAm'
                                ,'OtherAm'
                                ,'bonusam'
                                ,'proam'
                                ,'SpeDelAm'
                                ,'CoShbAm'
                                ,'CoGjjAm'
                                ,'ManageAm'
                            )
                            ,$comtable);
                }else{//未结算人员读取上个月基本工资数据
                    if($val['pm']==1){
                        $ckpy=$val['py']-1;
                        $ckpm=12;
                    }else{
                        $ckpy=$val['py'];
                        $ckpm=$val['pm']-1;
                    }
                    //上月基本工资
                    $sp1=$this->model_get_pay(
                            array('userid'=>$key 
                                , 'pyear'=>$ckpy
                                , 'pmon'=>$ckpm
                            ), 
                            array(
                                'BaseAm'
                            )
                            ,$comtable);
                    //本月基础数据
                    $sp2=$this->model_get_pay(
                            array('userid'=>$key 
                                , 'pyear'=>$val['py']
                                , 'pmon'=>$val['pm']
                            ), 
                            array(
                                'ID'
                                ,'PerHolsDays'
                                ,'SickHolsDays'
                                ,'HolsDelAm'
                                ,'SpeRewAm'
                                ,'AccDelAm'
                                ,'ShbAm'
                                ,'GjjAm'
                                ,'PayCesse'
                                ,'AccRewAm'
                                ,'PayTotal'
                                ,'SalaryDept'
                                ,'Remark'
                                ,'hdar'
                                ,'bnar'
                                ,'srar'
                                ,'shbr'
                                ,'gjjr'
                                ,'sdar'
                                ,'pcr'
                                ,'arar'
                                ,'wdt'
                                ,'wdtr'
                                ,'OtherAccRewAm'
                                ,'AccRewAmCes'
                                ,'oarar'
                                ,'SdyAm'
                                ,'OtherAm'
                                ,'bonusam'
                                ,'proam'
                                ,'SpeDelAm'
                                ,'CoShbAm'
                                ,'CoGjjAm'
                                ,'ManageAm'
                            )
                            ,$comtable);
                    if(empty($sp1)){
                      $sp=$sp2;
                    }elseif(empty($sp2)){
                      $sp=$sp1;
                    }else{
                      $sp=$sp1+$sp2;
                    }
                }
                
                    /*'操作','KEY','姓名','公司','部门','员工类型','入职日期','离职日期'
                            ,'基本工资','事假','病假','事病假扣除','实际工资小计','各项补贴'
                            ,'社保费','公积金','其他扣除','个人所得税','离职福利','实发离职工资'
                            ,'账号','开户行'
                    * 
                    */
                    if($outflag=='list'){
                        $responce->rows[$i]['id'] = $row['userid'];
                        $responce->rows[$i]['cell'] = un_iconv(
                                    array("", $val['rk']
                                        , $val['usercard']
                                        , $val['username']
                                        , $val['com']
                                        , $sp['SalaryDept']
                                        , $val['jname']
                                        , $this->expflag[$val['expflag']]
                                        , date('Y-m-d',strtotime($val['cdt']))
                                        , $val['ldt']
                                        , $val['lpd']
                                        , $this->salaryClass->decryptDeal($sp['BaseAm'])
                                        , $sp['PerHolsDays']
                                        , $sp['SickHolsDays']
                                        , ($sp['wdt']=='-1'?($this->salaryClass->getLeaveWorkDays($val['cdt'], $val['ldt'])):$sp['wdt'])
                                        , $this->salaryClass->decryptDeal($sp['HolsDelAm'])
                                        , round($this->salaryClass->decryptDeal($sp['BaseNowAm'])-$this->salaryClass->decryptDeal($sp['HolsDelAm']),2)
                                        , round($this->salaryClass->decryptDeal($sp['SpeRewAm'])+$this->salaryClass->decryptDeal($sp['SdyAm'])
                                                +$this->salaryClass->decryptDeal($sp['OtherAm'])+$this->salaryClass->decryptDeal($sp['bonusam'])
                                                +$this->salaryClass->decryptDeal($sp['proam']),2)
                                        , $this->salaryClass->decryptDeal($sp['ShbAm'])
                                        , $this->salaryClass->decryptDeal($sp['GjjAm'])

                                        , $this->salaryClass->decryptDeal($sp['PayCesse'])
                                        , round($this->salaryClass->decryptDeal($sp['SpeDelAm'])+$this->salaryClass->decryptDeal($sp['AccDelAm']),2)
                                        , $this->salaryClass->decryptDeal($sp['AccRewAm'])
                                        , $this->salaryClass->decryptDeal($sp['AccRewAmCes'])
                                        , $this->salaryClass->decryptDeal($sp['OtherAccRewAm'])
                                        , $this->salaryClass->decryptDeal($sp['PayTotal'])
                                        ,$val['acc']
                                        ,$val['accbank']
                                        ,$sp['Remark']
                                        ,$val['comcode']
                                        ,'yes'
                                        ,$sp['ID']
                                        ,$sp['hdar'],$sp['bnar'],$sp['srar'],$sp['shbr'],$sp['gjjr'],$sp['sdar'],$sp['pcr'],$sp['arar']
                                        ,($val['payleaveflag']=='1'?'已结算':'未结算')
                                        ,$val['pm'],$sp['wdtr'],$sp['oarar']
                                    )
                    );
                    $i++;
                }elseif($outflag=='xls'){
                    $responce[]=un_iconv(array(
                         $val['usercard']
                        , $val['username']
                        , $val['com']
                        , $val['pdeptname']
                        , $sp['SalaryDept']
                        , $val['jname']
                        , $this->expflag[$val['expflag']]
                        , date('Ymd',strtotime($val['cdt']))
                        , date('Ymd',strtotime($val['ldt']))
                        ,($val['payleaveflag']=='1'?'已结算':'未结算')
                        , date('Ymd',strtotime($val['lpd']))
                        , $this->salaryClass->decryptDeal($sp['BaseAm'])
                        , $sp['PerHolsDays']
                        , $sp['SickHolsDays']
                        , ($sp['wdt']=='-1'?($this->salaryClass->getLeaveWorkDays($val['cdt'], $val['ldt'])):$sp['wdt'])
                        , $this->salaryClass->decryptDeal($sp['HolsDelAm'])
                        , round($this->salaryClass->decryptDeal($sp['BaseNowAm'])-$this->salaryClass->decryptDeal($sp['HolsDelAm']),2)
                        , round($this->salaryClass->decryptDeal($sp['SpeRewAm'])+$this->salaryClass->decryptDeal($sp['SdyAm'])
                                +$this->salaryClass->decryptDeal($sp['OtherAm'])+$this->salaryClass->decryptDeal($sp['bonusam'])
                                +$this->salaryClass->decryptDeal($sp['proam']),2)
                        , $this->salaryClass->decryptDeal($sp['ShbAm'])
                        , $this->salaryClass->decryptDeal($sp['GjjAm'])
                        , $this->salaryClass->decryptDeal($sp['PayCesse'])
                        , $this->salaryClass->decryptDeal($sp['AccDelAm'])
                        , $this->salaryClass->decryptDeal($sp['AccRewAm'])
                        , $this->salaryClass->decryptDeal($sp['AccRewAmCes'])
                        , $this->salaryClass->decryptDeal($sp['OtherAccRewAm'])
                        , $this->salaryClass->decryptDeal($sp['PayTotal'])
                        , $this->salaryClass->decryptDeal($sp['CoShbAm'])
                        , $this->salaryClass->decryptDeal($sp['CoGjjAm'])
                        , $this->salaryClass->decryptDeal($sp['ManageAm'])
                        ,$val['acc']
                        ,$val['accbank']
                        ,$val['idcard']
                        ,($sp['wdtr']!=''? '离职当月出勤天数：'.$sp['wdtr'].'；':'')
                        .($sp['hdar']!=''? '事病假扣除：'.$sp['hdar'].'；':'')
                        .($sp['bnar']!=''? '离职工资小计：'.$sp['bnar'].'；':'')
                        .($sp['srar']!=''? '各项补贴：'.$sp['srar'].'；':'')
                        .($sp['shbr']!=''? '社会保险费：'.$sp['shbr'].'；':'')
                        .($sp['gjjr']!=''? '住房公积金：'.$sp['gjjr'].'；':'')
                        .($sp['pcr']!=''? '个人所得税：'.$sp['pcr'].'；':'')
                        .($sp['sdar']!=''? '其他扣除：'.$sp['sdar'].'；':'')
                        .($sp['arar']!=''? '离职福利：'.$sp['arar'].'；':'')
                        .($sp['oarar']!=''? '其余税后需发：'.$sp['oarar'].'；':'')
                        .($sp['Remark']!=''? '备注：'.$sp['Remark'].'；':'')
                    ));
                }
            }
        }
        return $responce;
    }
    /**
     *计算离职工资 
     */
    function model_cal_leavepay(){
        $key=$_POST['key'];
        $leavedt=$_POST['leavedt'];
        $comedt=$_POST['comedt'];
        $baseam=$_POST['baseam'];
        $ph=$_POST['ph'];
        $sh=$_POST['sh'];
        $sra=$_POST['sra'];
        $shb=$_POST['shb'];
        $gjj=$_POST['gjj'];
        $sda=$_POST['sda'];//税后扣除
        $ara=$_POST['ara'];
        $wdt=$_POST['wdt'];
        $oara=$_POST['oara'];//其他税后扣除
        $arac=$_POST['arac'];//福利税金
        $comedt=date('Y-m-d',strtotime($comedt));
        //离职工资
        $swd=round($wdt+$sh+$ph,2);
        $bna = $this->salaryClass->getSalaryByWorkDays($baseam , $swd , $leavedt);
        $hda = round($this->salaryClass->holsDeal($ph, $sh, $baseam,$leavedt),2);
        $bna=round($bna-$hda,2);//离职小计
        //计算个税
        $cesse=round($bna+$sra-$shb-$gjj,2);
        $pc = $this->salaryClass->cesseDeal($cesse);
        //总=税前-个税+福利-扣除-福利税-其他需发
        $ptol=round($cesse-$pc+$ara-$sda-$arac+$oara,2);
        
        $responce->hda=$hda;
        $responce->bna=$bna;
        $responce->pc=$pc;
        $responce->ptol=$ptol;
        
        return $responce;
        
    }
    /**
     *插入 
     */
    function model_cal_leave_in(){
        
        $key=$_POST['key'];
        $leavedt=$_POST['leavedt'];
        $comedt=$_POST['comedt'];
        $baseam=$_POST['baseam'];
        $ph=$_POST['ph'];
        $sh=$_POST['sh'];
        $sra=$_POST['sra'];
        $shb=$_POST['shb'];
        $gjj=$_POST['gjj'];
        $sda=$_POST['sda'];
        $ara=$_POST['ara'];
        $com=$_POST['comcode'];
        $pid=$_POST['pid'];
        $acc=$_POST['acc'];
        $hdar=$_POST['hdar'];
        $bnar=$_POST['bnar'];
        $srar=$_POST['srar'];
        $shbr=$_POST['shbr'];
        $gjjr=$_POST['gjjr'];
        $sdar=$_POST['sdar'];
        $arar=$_POST['arar'];
        $pcr=$_POST['pcr'];
        
        $accbank=$_POST['accbank'];
        $remark=$_POST['remark'];
        $comedt=date('Y-m-d',strtotime($comedt));
        //
        $wdt=$_POST['wdt'];
        $wdtr=$_POST['wdtr'];
        $oara=$_POST['oara'];
        $oarar=$_POST['oarar'];
        $arac=$_POST['arac'];
        $lpd=$_POST['lpd'];
        if($lpd){
            $lpy=date('Y',strtotime($lpd));
            $lpm=date('n',strtotime($lpd));
            $lpj=date('j',strtotime($lpd));
        }
        
        //公司
        $comtable = $this->get_com_sql($com);
        
        if($key&&$pid){
            $stemp=$this->model_get_salary($key,array('leavedt','userid'));
            //更新基本
            $this->model_salary_update($key,
                        array(
                            'leavedt' => $leavedt
                            ,'acc'=>$acc
                            ,'accbank'=>$accbank
                            ,'payleaveflag'=>1
                            ,'payleavedt'=>date('Y-m-d H:i:s')
                            ,'payleaveuser'=>$_SESSION['USER_ID']
                            ,'lpy'=>$lpy
                            ,'lpm'=>$lpm
                            ,'lpj'=>$lpj
                            ,'amount'=>$baseam
                            ,'shbam'=>$shb
                            ,'gjjam'=>$gjj
                        )
                        , array(0,1,2,3,4,5,6,7,8));
            //更新当月
            //$res = array('holsdelam' => $holsdelAm, 'totalam' => $totalAm, 'paycesse' => $payCesse, 'paytotal' => $payTotal);
            //离职工资
            $swd=round($wdt+$sh+$ph,2);
            $bna = $this->salaryClass->getSalaryByWorkDays($baseam , $swd , $leavedt);
            $hda = round($this->salaryClass->holsDeal($ph, $sh, $baseam,$leavedt),2);
            //计算个税
            $cesse=round($bna-$hda+$sra-$shb-$gjj,2);
            $pc = $this->salaryClass->cesseDeal($cesse);
            //总=税前-个税+福利-扣除-福利税-其他需发
            $ptol=round($cesse-$pc+$ara-$sda-$arac+$oara,2);
            $this->model_pay_update($pid,
                    array(
                            'Remark'=>$remark
                            , 'PerHolsDays' => $ph
                            ,'SickHolsDays'=> $sh
                        
                            ,'hdar' => $hdar
                            ,'bnar'=> $bnar
                            ,'srar' => $srar
                            ,'shbr'=> $shbr
                            ,'gjjr' => $gjjr
                            ,'sdar'=> $sdar
                            ,'pcr'=> $pcr
                            ,'arar'=> $arar
                            ,'wdt'=>$wdt
                            ,'wdtr'=>$wdtr
                            ,'oarar'=>$oarar
                        
                            ,'baseam' => $baseam
                            ,'basenowam' => $bna
                            ,'SpeRewAm'=>$sra
                            ,'AccDelAm'=>$sda
                            ,'AccRewAm'=>$ara
                            ,'shbam'=>$shb
                            ,'gjjam'=>$gjj
                            ,'SdyAm'=>0
                            ,'OtherAm'=>0
                            ,'bonusam'=>0
                            ,'proam'=>0
                            ,'SpeDelAm'=>0
                            ,'OtherAccRewAm'=>$oara
                            ,'AccRewAmCes'=>$arac
                            ,'holsdelam'=>$hda
                            ,'totalam'=>$cesse
                            ,'paycesse'=>$pc
                            ,'paytotal'=>$ptol
                        )
                    , array(0,1,2,3,4,5,6,7,8,9,10,11,12,13),$comtable);
            if($stemp['leavedt']!=$leavedt){
                //更改人事档案：
                $sql="update hrms set LEFT_DATE='".$leavedt."' where user_id='".$stemp['userid']."'";
                $this->db->query_exc($sql);
                //新系统
                $sql="update  oa_hr_personnel p set p.quitDate='".$leavedt."' where p.useraccount='".$stemp['userid']."'";
                $this->db->query_exc($sql);
            }
            
        }else{
            $responce->error='pid is null or key is null';
        }
        $responce->id=$key;
        return $responce;
    }
    /**
     *特殊
     */
    function model_hr_spe_list(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        $start = $limit * $page - $limit;
        //总数
        $sql = "select count(*)
            from salary_spe s
                left join salary sa on (s.payuserid=sa.userid)
                left join user u1 on (s.payuserid=u1.user_id)
            where
                s.payuserid=sa.userid
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select
                s.rand_key , u1.user_name as username , sa.userid , d.dept_name as olddept
                , concat(s.payyear,'-',s.paymon) as payym
                , s.paytype , s.amount , s.remark
                , s.spesta , s.paydt , u.user_name
                , s.createdt
                , f.id as fid
                , f.sta as fsta
                , fs.item as fitem
                , s.acctype
                , fs.rand_key as fskey
            from salary_spe s
                left join salary sa on ( s.payuserid=sa.userid )
                left join user u on (s.creator=u.user_id)
                left join salary_flow f on (f.salarykey=s.rand_key )
                left join salary_flow_step fs on (fs.salaryfid=f.id and ( fs.sta='0' or (fs.sta='1' and fs.res='no' ) ) )
                left join user u1 on (s.payuserid=u1.user_id)
                left join department d on (u1.dept_id=d.dept_id)
            where
                s.payuserid=sa.userid
                $sqlSch
            order by s.spesta asc , s.createdt desc, s.id , $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $fsta=$row['fitem'];
            if($row['fid']&&$row['fsta']!=''&&$row['fsta']!=3){
                $ck='yes';
                $fsta.='-未审';
            }else{
                $ck='no';
            }
            $responce->rows[$i]['id'] = $row['userid'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array("", $row['rand_key'], $row['username'], $row['olddept']
                                , $row['payym']
                                , $this->speType[$row['paytype']]
                                , $this->accType[$row['acctype']]
                                , $this->salaryClass->decryptDeal($row['amount'])
                                , $row['remark']
                                , $this->userSpe[$row['spesta']]
                                , $fsta
                                , $row['paydt']
                                , $row['user_name']
                                , $row['createdt']
                                , $ck
                                , $row['fskey']
                            )
            );
            $i++;
        }
        return $responce;
    }
    /**
     * 
     */
    function model_hr_spe_xls(){
        $seay=$_GET['sy'];
        $seam=$_GET['sm'];
        include( WEB_TOR . 'includes/classes/excel_out_class.php');
        $xls = new ExcelXML('gb2312', false, 'My Test Sheet');
        $data = array(1=> array ('序号', '员工号', '姓名',  '公司', '目前直属部门', '目前部门', '当月直属部门', '当月部门','月份'
                ,'类型', '金额', '备注','状态')
                );
        $xls->setStyle(array(4));
        if($seay!='-'){
            $sqlst.=" and payyear='".$seay."' ";
        }
        if($seam!='-'){
            $sqlst.=" and paymon='".$seam."' ";
        }
        $sql="select
                s.rand_key , u1.user_name as username , sa.userid , d.dept_name as olddept
                , concat(s.payyear,'-',s.paymon) as payym
                , s.paytype , s.amount , s.remark
                , s.spesta , s.paydt 
                , s.createdt
        		, dt.dept_name as dtname
        		, u1.company ,h.usercard
        		, td.dept_name as tdname
        		, tdt.dept_name as tdtname
            from salary_spe s
                left join salary sa on ( s.payuserid=sa.userid )
                left join user u1 on (s.payuserid=u1.user_id)
        		left join hrms h on (u1.user_id=h.user_id)
               	left join department d on (u1.dept_id=d.dept_id)
        		left join department dt on (dt.depart_x=left(d.depart_x,2))
        		left join salary_pay p on (p.userid=s.payuserid and p.pyear=s.payyear and p.pmon=s.paymon)
        		left join department td on (p.deptid=td.dept_id)
        		left join department tdt on (tdt.depart_x=left(td.depart_x,2))
            where
                s.payuserid=sa.userid
        		and p.userid=s.payuserid and p.pyear=s.payyear and p.pmon=s.paymon
                ".$sqlst."
            order by s.spesta asc , s.createdt desc, s.id ";
        $query=$this->db->query($sql);
        $i=1;
        while($row=$this->db->fetch_array($query)){
            $data[]=array(
                $i,$row['usercard'],$row['username'] , $this->salaryCom[$row['company']] , $row['dtname'], $row['olddept']
            	, $row['tdtname'], $row['tdname'] ,$row['payym']
                ,$this->speType[$row['paytype']],$this->salaryClass->decryptDeal($row['amount'])
                ,$row['remark'],$this->userSpe[$row['spesta']]
            );
            $i++;
        }
        //世源：
        $sql="select
                s.rand_key , u1.user_name as username , sa.userid , d.dept_name as olddept
                , concat(s.payyear,'-',s.paymon) as payym
                , s.paytype , s.amount , s.remark
                , s.spesta , s.paydt
                , s.createdt
        		, dt.dept_name as dtname
        		, u1.company ,h.usercard
        		, td.dept_name as tdname
        		, tdt.dept_name as tdtname
            from salary_spe s
                left join salary sa on ( s.payuserid=sa.userid )
                left join user u1 on (s.payuserid=u1.user_id)
        		left join hrms h on (u1.user_id=h.user_id)
               	left join department d on (u1.dept_id=d.dept_id)
        		left join department dt on (dt.depart_x=left(d.depart_x,2))
        		left join `shiyuanoa`.salary_pay p on (p.userid=s.payuserid and p.pyear=s.payyear and p.pmon=s.paymon)
        		left join department td on (p.deptid=td.dept_id)
        		left join department tdt on (tdt.depart_x=left(td.depart_x,2))
            where
                s.payuserid=sa.userid
        		and p.userid=s.payuserid and p.pyear=s.payyear and p.pmon=s.paymon
                ".$sqlst."
            order by s.spesta asc , s.createdt desc, s.id ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
        	$data[]=array(
        			$i,$row['usercard'],$row['username'] , $this->salaryCom[$row['company']] , $row['dtname'], $row['olddept']
        			, $row['tdtname'], $row['tdname'] ,$row['payym']
        			,$this->speType[$row['paytype']],$this->salaryClass->decryptDeal($row['amount'])
        			,$row['remark'],$this->userSpe[$row['spesta']]
        	);
        	$i++;
        }
        //北软：
        $sql="select
                s.rand_key , u1.user_name as username , sa.userid , d.dept_name as olddept
                , concat(s.payyear,'-',s.paymon) as payym
                , s.paytype , s.amount , s.remark
                , s.spesta , s.paydt
                , s.createdt
        		, dt.dept_name as dtname
        		, u1.company ,h.usercard
        		, td.dept_name as tdname
        		, tdt.dept_name as tdtname
            from salary_spe s
                left join salary sa on ( s.payuserid=sa.userid )
                left join user u1 on (s.payuserid=u1.user_id)
        		left join hrms h on (u1.user_id=h.user_id)
               	left join department d on (u1.dept_id=d.dept_id)
        		left join department dt on (dt.depart_x=left(d.depart_x,2))
        		left join `beiruanoa`.salary_pay p on (p.userid=s.payuserid and p.pyear=s.payyear and p.pmon=s.paymon)
        		left join department td on (p.deptid=td.dept_id)
        		left join department tdt on (tdt.depart_x=left(td.depart_x,2))
            where
                s.payuserid=sa.userid
        		and p.userid=s.payuserid and p.pyear=s.payyear and p.pmon=s.paymon
                ".$sqlst."
            order by s.spesta asc , s.createdt desc, s.id ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
        	$data[]=array(
        			$i,$row['usercard'],$row['username'] , $this->salaryCom[$row['company']] , $row['dtname'], $row['olddept']
        			, $row['tdtname'], $row['tdname'] ,$row['payym']
        			,$this->speType[$row['paytype']],$this->salaryClass->decryptDeal($row['amount'])
        			,$row['remark'],$this->userSpe[$row['spesta']]
        	);
        	$i++;
        }
        $xls->addArray($data);
        $xls->generateXML(time());
    }
    /**
     * 特殊处理
     */
    function model_hr_spe_in(){
        set_time_limit(600);
        $id = $_POST['id'];
        $type = $_POST['type'];
        $acctype = $_POST['acctype'];
        $amount = $_POST['amount'];
        $remark = $_POST['remark'];
        $sub = $_POST['sub'];
        $sm=false;
        try {
            $this->db->query("START TRANSACTION");
            if($sub=='submit'){
                $sql="select payuserid from salary_spe where rand_key='".$id."' ";
                $res=$this->db->get_one($sql);
                if(!$res['payuserid']){
                    throw new Exception('no user id');
                }
                $sql="update salary_spe
                    set
                       paytype='".$type."'
                       , amount='".$this->salaryClass->encryptDeal($amount)."'
                       , remark='".$remark."'
                       , createdt=now() , creator='".$_SESSION['USER_ID']."'
                       , spesta='1'
                       , acctype='".$acctype."'
                    where rand_key='".$id."' ";
                $this->db->query_exc($sql);
                $info=array('flowname'=>$this->flowName['spe']
                            ,'userid'=>$res['payuserid']
                            ,'salarykey'=>$id
                            ,'changeam'=>0
                            ,'remark'=>$remark
                            );
                $sm=$this->model_flow_new($info);
            }
            if($sub=='edit'){//检查是否允许修改
                $sql="select
                        count(*) , userid
                    from
                        salary_flow f
                    where
                        f.salarykey='".$id."' and f.sta!=3 
                        and f.flowname='".$this->flowName['spe']."' group by f.salarykey";
                $resck=$this->db->get_one($sql);
                if($resck['count(*)']&&$resck['count(*)']==1){
                    $sql="update salary_spe
                        set
                           paytype='".$type."'
                           , amount='".$this->salaryClass->encryptDeal($amount)."'
                           , remark='".$remark."'
                           , createdt=now() , creator='".$_SESSION['USER_ID']."'
                           , spesta='1'
                           , acctype='".$acctype."'
                        where rand_key='".$id."' ";
                    $this->db->query_exc($sql);
                    
                    $sql="delete from
                            salary_flow_step 
                        where
                            salaryfid in (
                                select f.id from salary_flow f where
                                    f.flowname='".$this->flowName['spe']."' and f.salarykey='".$id."'
                            ) ";
                    $this->db->query_exc($sql);
                    $sql="delete from
                            salary_flow 
                        where
                            flowname='".$this->flowName['spe']."' and salarykey='".$id."' ";
                    $this->db->query_exc($sql);
                    $info=array('flowname'=>$this->flowName['spe']
                                ,'userid'=>$resck['userid']
                                ,'salarykey'=>$id
                                ,'changeam'=>$this->salaryClass->encryptDeal($amount)
                                ,'remark'=>$remark
                                );
                    $sm=$this->model_flow_new($info);
                    
                }else{
                    throw new Exception('Can not modify the data has been approved');
                }
            }
            if($sub=='handup'){
                $sql="select payuserid , amount from salary_spe where rand_key='".$id."' ";
                $res=$this->db->get_one($sql);
                if(!$res['payuserid']){
                    throw new Exception('no user id');
                }
                $sql="update salary_spe
                    set
                       createdt=now() , creator='".$_SESSION['USER_ID']."'
                       , spesta='1'
                    where rand_key='".$id."' ";
                $this->db->query_exc($sql);
                $info=array('flowname'=>$this->flowName['spe']
                            ,'userid'=>$res['payuserid']
                            ,'salarykey'=>$id
                            ,'changeam'=>$res['amount']
                            ,'remark'=>$remark
                            );
                $sm=$this->model_flow_new($info);
            }
            if($sub=='back'){
                $sql="select payuserid from salary_spe where rand_key='".$id."' and spesta='2' ";
                $res=$this->db->get_one($sql);
                if(!$res['payuserid']){
                    throw new Exception('no user id');
                }
                $sql="update salary_spe
                    set
                       paytype='".$type."'
                       , amount='".$this->salaryClass->encryptDeal($amount)."'
                       , remark='".$remark."'
                       , createdt=now() , creator='".$_SESSION['USER_ID']."'
                       , spesta='1'
                       , acctype='".$acctype."'
                    where rand_key='".$id."' ";
                $this->db->query_exc($sql);
                $sql="delete from
                        salary_flow_step 
                    where
                        salaryfid in (
                            select f.id from salary_flow f where
                                f.flowname='".$this->flowName['spe']."' and f.salarykey='".$id."'
                        ) ";
                $this->db->query_exc($sql);
                $sql="delete from
                        salary_flow 
                    where
                        flowname='".$this->flowName['spe']."' and salarykey='".$id."' ";
                $this->db->query_exc($sql);
                $info=array('flowname'=>$this->flowName['spe']
                            ,'userid'=>$res['payuserid']
                            ,'salarykey'=>$id
                            ,'changeam'=>$this->salaryClass->encryptDeal($amount)
                            ,'remark'=>$remark
                            );
                $sm=$this->model_flow_new($info);
            }
            if($sub=='del'){
                $sql="delete from salary_spe where rand_key='".$id."' ";
                $this->db->query_exc($sql);
                $sql="delete from
                        salary_flow_step 
                    where
                        salaryfid in (
                            select f.id from salary_flow f where
                                f.flowname='".$this->flowName['spe']."' and f.salarykey='".$id."'
                        ) ";
                $this->db->query_exc($sql);
                $sql="delete from
                        salary_flow 
                    where
                        flowname='".$this->flowName['spe']."' and salarykey='".$id."' ";
                $this->db->query_exc($sql);
            }
            if($sub=='new'){
                if(!$id||!trim($id)){
                    throw new Exception('no user id');
                }
                if(!$_SESSION['USER_ID']){
                    throw new Exception('time out');
                }
                $id=str_replace('haiyang,yang', 'haiyang-yang', $id);
                $tmpua=explode(',', $id);
                if(!empty($tmpua)){
                    foreach($tmpua as $val){
                        $val=$val=='haiyang-yang'?'haiyang,yang':$val;
                        if(!$val||$val==''){
                            continue;
                        }
                        $spekey=get_rand_key();
                        $sql="insert into salary_spe
                            ( payyear , paymon , amount
                                , payuserid , payuserna ,remark
                                , createdt , creator , spesta ,rand_key , paytype , acctype
                            )
                            select
                                '".$this->nowy."' , '".$this->nowm."' , '".$this->salaryClass->encryptDeal($amount)."'
                                , user_id , user_name , '".$remark."'
                                , now() , '".$_SESSION['USER_ID']."' , 1 , '".$spekey."' ,'".$type."','".$acctype."'
                            from user where user_id='".$val."' ";
                        $this->db->query_exc($sql);
                        $info=array('flowname'=>$this->flowName['spe']
                            ,'userid'=>$val
                            ,'salarykey'=>$spekey
                            ,'changeam'=>$this->salaryClass->encryptDeal($amount)
                            ,'remark'=>$remark
                            );
                        $sm[$val]=$this->model_flow_new($info);
                    }
                }
            }
            $this->db->query("COMMIT");
            if(is_array($sm)){
                if(count($sm)){
                    foreach($sm as $val){
                        $body='您好！<br><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;系统内有工资数据：员工特殊奖励/扣除，需要您的审批。<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;此项数据由'.$_SESSION["USER_NAME"].'向您提交<br>
                            谢谢！';
                        $this->model_send_email('审批--员工特殊奖励/扣除', $body, $val, false,true);
                    }
                }
            }elseif($sm){
                $body='您好！<br><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;系统内有工资数据：员工特殊奖励/扣除，需要您的审批。<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;此项数据由'.$_SESSION["USER_NAME"].'向您提交<br>
                        谢谢！';
                $this->model_send_email('审批--员工特殊奖励/扣除', $body, $sm, false,true);
            }
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '特殊奖励', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '特殊奖励', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
     * 人事补贴
     */
    function model_hr_sdy_list(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        $start = $limit * $page - $limit;
        //总数
        $sql = "select count(*)
            from salary_sdy s
                left join user u on (s.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where
                s.flaflag in ( '1' , '2' )
                and s.creator='".$_SESSION['USER_ID']."'
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select
                s.rand_key , u.user_name as username , d.dept_name as olddept
                , concat(s.pyear,'-',s.pmon) as payym
                , s.sdymeal , s.sdyother , s.remark
                , s.flaflag 
                , s.createdt
                , f.id as fid
                , f.sta as fsta
                , fs.item as fitem
                , fs.rand_key as fskey
            from salary_sdy s
                left join salary_flow f on (f.salarykey=s.rand_key )
                left join salary_flow_step fs on (fs.salaryfid=f.id and ( fs.sta='0' or (fs.sta='1' and fs.res='no' ) ) )
                left join user u on (s.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where
                f.salarykey=s.rand_key and s.flaflag in ( '1' , '2' )
                and s.creator='".$_SESSION['USER_ID']."'
                $sqlSch
            group by s.id
            order by f.sta , $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $fsta=$this->flowSta[$row['fsta']];
            if($row['fid']&&$row['fsta']!=''&&$row['fsta']==0){
                $ck='yes';
                $fsta.='-未审';
            }else{
                $ck='no';
            }
            $responce->rows[$i]['id'] = $row['userid'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array("", $row['rand_key'], $row['username'], $row['olddept']
                                , $this->salaryClass->decryptDeal($row['sdymeal'])
                                , $this->salaryClass->decryptDeal($row['sdyother'])
                                , $row['payym']
                                , $row['remark']
                                , $row['createdt']
                                , $fsta
                                , $ck
                                , $row['fskey']
                            )
            );
            $i++;
        }
        return $responce;
    }
    /**
     * 人事补贴新建
     */
    function model_hr_sdy_new_in(){
        $id=$_POST['id'];
        $meal=$_POST['meal'];
        $other=$_POST['other'];
        $remark=$_POST['remark'];
        $sub=$_POST['sub'];
        $sm=array();
        try {
            $this->db->query("START TRANSACTION");
            if($sub=='new'){
                if(!$id||!trim($id)){
                    throw new Exception('no user id');
                }
                if(!$_SESSION['USER_ID']){
                    throw new Exception('time out');
                }
                $tmpua=explode(',', $id);
                $sql="select p.id , p.userid , h.userlevel
                        from
                            salary p
                            left join hrms h on (p.userid=h.user_id)
                        where
                            p.userid in ('".implode("','", $tmpua)."') ";
                $query=$this->db->query($sql);
                $tmpua=array();
                while($row=$this->db->fetch_array($query)){
                    $tmpua[$row['userid']]=$row['id'];
                }
                if(!empty($tmpua)){
                    foreach($tmpua as $key=>$val){
                        if(!$val||$val==''){
                            continue;
                        }
                        $sdykey=get_rand_key();
                        $sql="insert into salary_sdy
                            ( userid , sdymeal , sdyother , remark
                                , creator , createdt , pyear , pmon 
                                , flaflag , rand_key 
                            )
                            select
                                user_id , '".$this->salaryClass->encryptDeal($meal)."' , '".$this->salaryClass->encryptDeal($other)."'
                                , '".$remark."' , '".$_SESSION['USER_ID']."' , now() , '".$this->nowy."' , '".$this->nowm."'
                                , '1' , '".$sdykey."'
                            from user where user_id='".$key."' ";
                        $this->db->query_exc($sql);
                        $info=array('flowname'=>$this->flowName['sdyhr']
                            ,'userid'=>$key
                            ,'salarykey'=>$sdykey
                            ,'changeam'=>''
                            ,'remark'=>$remark
                            );
                        $sm[$val]=$this->model_flow_new($info,true,false);
                    }
                }
            }elseif($sub=='edit'){
                if(!$id||!trim($id)){
                    throw new Exception('no user id');
                }
                if(!$_SESSION['USER_ID']){
                    throw new Exception('time out');
                }
                $sql="select
                        id , rand_key
                    from salary_sdy
                    where rand_key='".$id."' ";
                $res=$this->db->get_one($sql);
                if(empty($res)){
                    throw new Exception('Data can not find');
                }
                $sql="update salary_sdy
                    set sdymeal='".$this->salaryClass->encryptDeal($meal)."'
                        , sdyother='".$this->salaryClass->encryptDeal($other)."'
                        , remark='".$remark."'
                    where rand_key='".$id."' ";
                $this->db->query_exc($sql);
            }elseif($sub=='del'){
                if(!$id||!trim($id)){
                    throw new Exception('no user id');
                }
                if(!$_SESSION['USER_ID']){
                    throw new Exception('time out');
                }
                $sql="select
                        id , rand_key , flaflag
                    from salary_sdy
                    where rand_key='".$id."' ";
                $res=$this->db->get_one($sql);
                if(empty($res)){
                    throw new Exception('Data can not find');
                }
                if($res['flaflag']=='2'){
                    throw new Exception('Information has been handled');
                }
                $sql="delete from salary_sdy where rand_key='".$id."'";
                $this->db->query_exc($sql);
                $sql="delete from salary_flow where salarykey='".$id."' and flowname='".$this->flowName['sdyhr']."' ";
                $this->db->query_exc($sql);
            }elseif($sub=='xls'){
                $temparr=array();
                $sql="select
                        s.userid , p.id , s.remark , s.rand_key
                    from salary_sdy s
                        left join salary_pay p on ( s.userid=p.userid and s.pyear=p.pyear and s.pmon=p.pmon )
                    where
                        s.creator='".$_SESSION['USER_ID']."'
                        and s.pyear='".$this->nowy."' and s.pmon='".$this->nowm."'
                        and s.staflag='1' and  flaflag='1' ";
                $query=$this->db->query($sql);
                while($row=$this->db->fetch_array($query)){
                   $temparr[$row['rand_key']]['userid']=$row['userid'];
                   $temparr[$row['rand_key']]['id']=$row['id'];
                   $temparr[$row['rand_key']]['remark']=$row['remark'];
                }
                if(!empty($temparr)){
                    foreach($temparr as $key=>$val){
                        $sql="update salary_sdy set staflag='0' where rand_key='".$key."' ";
                        $this->db->query_exc($sql);
                        $info=array('flowname'=>$this->flowName['sdyhr']
                            ,'userid'=>$val['userid']
                            ,'salarykey'=>$key
                            ,'changeam'=>''
                            ,'remark'=>$val['remark']
                            );
                        $sm[$key]=$this->model_flow_new($info,true,false);
                    }
                }
            }elseif($sub=='back'){
                if(!$id||!trim($id)){
                    throw new Exception('no user id');
                }
                if(!$_SESSION['USER_ID']){
                    throw new Exception('time out');
                }
                $sql="select
                        id , rand_key , userid , remark
                    from salary_sdy
                    where rand_key='".$id."' ";
                $res=$this->db->get_one($sql);
                if(empty($res)){
                    throw new Exception('Data can not find');
                }
                $sql="delete from salary_flow where salarykey='".$id."' and flowname='".$this->flowName['sdyhr']."' ";
                $this->db->query_exc($sql);
                $sql="update salary_sdy
                    set sdymeal='".$this->salaryClass->encryptDeal($meal)."'
                        , sdyother='".$this->salaryClass->encryptDeal($other)."'
                        , remark='".$remark."'
                    where rand_key='".$id."' ";
                $this->db->query_exc($sql);
                $info=array('flowname'=>$this->flowName['sdyhr']
                    ,'userid'=>$res['userid']
                    ,'salarykey'=>$id
                    ,'changeam'=>''
                    ,'remark'=>$res['remark']
                    );
                $sm[$val]=$this->model_flow_new($info,true,false);
            }
            $responce->id = $id;
            $this->db->query("COMMIT");
            $this->globalUtil->insertOperateLog('工资管理', $id, '人事补贴', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '人事补贴', '失败',$e->getMessage());
        }
        return $responce;
    }
    /**
     *公积金/社保费
     * @return <type> 
     */
    function model_hr_pay_list($outflag='list'){
        global $func_limit;
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        $seapy = $_GET['seapy']?$_GET['seapy']:$this->nowy;
        $seapm = $_GET['seapm']?$_GET['seapm']:$this->nowm;
        $seadept = $_REQUEST['seadept'];
        $seaname = $_REQUEST['seaname'];
        $seausercom = $_REQUEST['seausercom'];
        $seajfcom = $_REQUEST['seajfcom'];
        $seaexp = $_REQUEST['seaexp'];
        
        if($seapy&&$seapy!='-'){
            $sqlSch.=" and p.pyear='".$seapy."' ";
        }
        if($seapm&&$seapm!='-'){
            $sqlSch.=" and p.pmon='".$seapm."' ";
        }
        if($seadept){
            $sqlSch.=" and s.olddept like '%".$seadept."%' ";
        }
        if($seaname){
            $sqlSch.=" and ( s.username like '%".$seaname."%' or s.oldname like '%".$seaname."%' ) ";
        }
        if($seausercom){
            $sqlSch.=" and ( p.usercom='".$seausercom."' ) ";
        }if($seajfcom){
            $sqlSch.=" and ( p.jfcom='".$seajfcom."' ) ";
        }
        if(isset($seaexp)&&$seaexp!='-'){
            $sqlSch.=" and ( p.expflag='".$seaexp."' ) ";
        }
        
        $start = $limit * $page - $limit;
        //总数
        $totalarr=array(
            'gjj'=>0,'shb'=>0,'cogjj'=>0,'coshb'=>0,'pre'=>0,'had'=>0,'man'=>0
        );
        if($outflag=='list'){
            $sql="(
                        select s.rand_key ,p.gjjam , p.shbam
                        , p.cogjjam , p.coshbam
                        , p.prepaream , p.handicapam
                        , p.manageam
                        , p.expflag  
                        from salary s
                            left join salary_pay p on (p.userid=s.userid)
                            left join hrms h on (s.userid=h.user_id) 
                        where
                            s.userid=h.user_id and p.userid=s.userid $sqlSch
                    )union(
                        select s.rand_key ,p.gjjam , p.shbam
                        , p.cogjjam , p.coshbam
                        , p.prepaream , p.handicapam
                        , p.manageam
                        , p.expflag  
                        from salary s
                            left join `shiyuanoa`.salary_pay p on (p.userid=s.userid)
                            left join hrms h on (s.userid=h.user_id) 
                        where
                            s.userid=h.user_id and p.userid=s.userid $sqlSch
                    )union(
                        select s.rand_key ,p.gjjam , p.shbam
                        , p.cogjjam , p.coshbam
                        , p.prepaream , p.handicapam
                        , p.manageam
                        , p.expflag  
                        from salary s
                            left join `beiruanoa`.salary_pay p on (p.userid=s.userid)
                            left join hrms h on (s.userid=h.user_id) 
                        where
                            s.userid=h.user_id and p.userid=s.userid $sqlSch
                    ) ";
            $query=$this->db->query($sql);
            $count = $this->db->affected_rows();
            while($row=$this->db->fetch_array($query)){
                $totalarr['gjj']=$totalarr['gjj']+$this->salaryClass->decryptDeal($row['gjjam']);
                $totalarr['shb']=$totalarr['shb']+$this->salaryClass->decryptDeal($row['shbam']);
                $totalarr['cogjj']=$totalarr['cogjj']+$this->salaryClass->decryptDeal($row['cogjjam']);
                $totalarr['coshb']=$totalarr['coshb']+$this->salaryClass->decryptDeal($row['coshbam']);
                $totalarr['pre']=$totalarr['pre']+$this->salaryClass->decryptDeal($row['prepaream']);
                $totalarr['had']=$totalarr['had']+$this->salaryClass->decryptDeal($row['handicapam']);
                $totalarr['man']=$totalarr['man']+$this->salaryClass->decryptDeal($row['manageam']);
            }
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages){
                $page = $total_pages;
            }
            $i = 0;
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;
            $responce->userdata['amount'] = 'total:';
            $responce->userdata['gjjam'] = $this->salaryClass->cfv($totalarr['gjj']);
            $responce->userdata['shbam'] = $this->salaryClass->cfv($totalarr['shb']);
            $responce->userdata['cogjjam'] = $this->salaryClass->cfv($totalarr['cogjj']);
            $responce->userdata['coshbam'] = $this->salaryClass->cfv($totalarr['coshb']);
            $responce->userdata['prepaream'] = $this->salaryClass->cfv($totalarr['pre']);
            $responce->userdata['handicapam'] = $this->salaryClass->cfv($totalarr['had']);
            $responce->userdata['manageam'] = $this->salaryClass->cfv($totalarr['man']);
        }
        $mainsql="select p.* from ( 
                    (select
                    s.rand_key , u1.user_name as username , s.userid , d.dept_name as olddept , h.userlevel
                    , p.baseam , p.gjjam , p.shbam
                    , p.cogjjam , p.coshbam
                    , p.prepaream , p.handicapam
                    , p.manageam
                    , s.paycreatedt
                    , u.user_name
                    , h.expflag
                    , p.usercom as company  , s.id as sid , h.usercard , p.jfcom , p.id as pid , p.pyear , p.pmon
                from salary s
                    left join salary_pay p on (p.userid=s.userid)
                    left join hrms h on ( s.userid=h.user_id )
                    left join user u on (s.paycreator=u.user_id)
                    left join user u1 on (s.userid=u1.user_id)
                    left join department d on (p.deptid=d.dept_id)
                where
                    s.userid=h.user_id and p.userid=s.userid
                    $sqlSch
                    )union(select
                    s.rand_key , u1.user_name as username , s.userid , d.dept_name as olddept , h.userlevel
                    , p.baseam , p.gjjam , p.shbam
                    , p.cogjjam , p.coshbam
                    , p.prepaream , p.handicapam
                    , p.manageam
                    , s.paycreatedt
                    , u.user_name
                    , h.expflag
                    , p.usercom as company   , s.id as sid , h.usercard , p.jfcom , p.id as pid , p.pyear , p.pmon
                from salary s
                    left join `shiyuanoa`.salary_pay p on (p.userid=s.userid)
                    left join hrms h on ( s.userid=h.user_id )
                    left join user u on (s.paycreator=u.user_id)
                    left join user u1 on (s.userid=u1.user_id)
                    left join department d on (p.deptid=d.dept_id)
                where
                    s.userid=h.user_id and p.userid=s.userid
                    $sqlSch
                    )union(select
                    s.rand_key , u1.user_name as username , s.userid , d.dept_name as olddept , h.userlevel
                    , p.baseam , p.gjjam , p.shbam
                    , p.cogjjam , p.coshbam
                    , p.prepaream , p.handicapam
                    , p.manageam
                    , s.paycreatedt
                    , u.user_name
                    , h.expflag
                    , p.usercom as company  , s.id as sid , h.usercard , p.jfcom , p.id as pid , p.pyear , p.pmon
                from salary s
                    left join `beiruanoa`.salary_pay p on (p.userid=s.userid)
                    left join hrms h on ( s.userid=h.user_id )
                    left join user u on (s.paycreator=u.user_id)
                    left join user u1 on (s.userid=u1.user_id)
                    left join department d on (p.deptid=d.dept_id)
                where
                    s.userid=h.user_id and p.userid=s.userid
                    $sqlSch
                    )
                ) p";
        if($outflag=='list'){
            $sql=$mainsql."  order by $sidx $sord limit $start , $limit ";
        }elseif($outflag=='xls'){
            $sql=$mainsql."  order by p.sid ";
        }
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $compt=$row['company'];
            if($row['userlevel']=='4'||$func_limit['人事查看管理层']=='1'){
                $amount=$this->salaryClass->decryptDeal($row['baseam']);
            }else{
                $amount='-';
            }
            $gjjam=$this->salaryClass->decryptDeal($row['gjjam']);
            $shbam=$this->salaryClass->decryptDeal($row['shbam']);
            $coshb=$this->salaryClass->decryptDeal($row['coshbam']);
            $cogjj=$this->salaryClass->decryptDeal($row['cogjjam']);
            $pre=$this->salaryClass->decryptDeal($row['prepaream']);
            $had=$this->salaryClass->decryptDeal($row['handicapam']);
            $man=$this->salaryClass->decryptDeal($row['manageam']);
            
            if($outflag=='list'){
                $responce->rows[$i]['id'] = $row['userid'];
                $responce->rows[$i]['cell'] = un_iconv(
                                array("", $row['rand_key'], $row['usercard'], $row['username'],$this->salaryCom[$compt], $row['olddept']
                                    , $this->expflag[$row['expflag']]
                                    ,$this->salaryCom[$row['jfcom']]
                                    , $row['pyear'].'-'.$row['pmon']
                                    , $amount
                                    , $gjjam
                                    , $shbam
                                    , $cogjj
                                    , $coshb
                                    , $pre
                                    , $had
                                    , $man
                                    , $row['paycreatedt']
                                    , $row['user_name']
                                    , $row['pid']
                                    , $compt
                                )
                );
                $i++;
            }elseif($outflag=='xls'){
                $responce[] = un_iconv(
                    array( $row['usercard'], $row['pyear'].'-'.$row['pmon'], $row['username'],$this->salaryCom[$compt], $row['olddept']
                        , $this->expflag[$row['expflag']]
                        ,$this->salaryCom[$row['jfcom']]
                        , $amount
                        , $gjjam
                        , $shbam
                        , $cogjj
                        , $coshb
                        , $pre
                        , $had
                        , $man
                    )
                );
            }
        }
        return $responce;
    }
    /**
     * 公积金/社保费处理
     */
    function model_hr_pay_in(){
        $id = $_POST['id'];
        $gjjam = round($_POST['gjjam'],2);
        $shbam = round($_POST['shbam'],2);
        $cogjjam = round($_POST['cogjjam'],2);
        $coshbam = round($_POST['coshbam'],2);
        $prepaream = round($_POST['prepaream'],2);
        $handicapam = round($_POST['handicapam'],2);
        $manageam = round($_POST['manageam'],2);
        $usercom = $_POST['usercom'];
        $jfcom = $_POST['jfcom'];
        $pid = $_POST['pid'];
        //$this->nowm=6;
        try {
            $this->db->query("START TRANSACTION");
            $comtable=$this->get_com_sql($usercom);
            $sql="select
                    p.id , s.userid  , s.rand_key as skey  
                from salary s
                    left join ".$comtable."salary_pay p on ( s.userid=p.userid  )
                where s.rand_key='".$id."' and p.id='".$pid."' and s.userid=p.userid ";
            $res=$this->db->get_one($sql);
            if(!$res['userid']){
                throw new Exception('no data');
            }
            if(!$res['id']){
                throw new Exception('no pid ');
            }
            if($res['id']){
                $this->model_salary_update($id,
                    array('paycreatedt'=>'now()','paycreator'=>$_SESSION['USER_ID'],'jfcom'=>$jfcom
                        ,'gjjam' => $gjjam, 'shbam' => $shbam
                        ,'prepaream'=>$prepaream,'handicapam'=>$handicapam,'manageam'=>$manageam
                        ,'cogjjam'=>$cogjjam , 'coshbam'=>$coshbam ),array(0,1,2)
                    );
                $this->model_pay_update($res['id'],
                    array('jfcom'=>$jfcom,'gjjam' => $gjjam, 'shbam' => $shbam
                        ,'prepaream'=>$prepaream,'handicapam'=>$handicapam,'manageam'=>$manageam
                        ,'cogjjam'=>$cogjjam , 'coshbam'=>$coshbam ),array(0),$comtable
                    );
                $this->model_pay_stat($res['id'],$comtable);
            }
            $this->db->query("COMMIT");
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '缴费信息', '成功',json_encode($_POST));
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '缴费信息', '失败', json_encode($_POST));
        }
        return $responce;
    }
    function model_hr_jf_ini($ckt){
        set_time_limit(600);
        $infoE=array();
//        
//        $filename = $_FILES ["ctr_file"] ["name"];
//        $temp_name = $_FILES ["ctr_file"] ["tmp_name"];
//        $fileType = $_FILES ["ctr_file"] ["type"];
//        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
//            $excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
//        }
//        
//        print_r($excelData);
//        /*
        
        $excelfilename='attachment/xls_model/temp/'.$ckt.".xls";
        if(empty ($_FILES["ctr_file"]["tmp_name"])){
            $str='<tr><td colspan="16">请导入数据！</td></tr>';
        }elseif (!move_uploaded_file( $_FILES["ctr_file"]["tmp_name"], $excelfilename) ){
            $str='<tr><td colspan="16">上传失败！</td></tr>';
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
            foreach($excelArr['员工号'] as $key=>$val ){
                $infoE[$val]['name']=$excelArr['名称'][$key];
                $infoE[$val]['com']=trim($excelArr['公司'][$key]);
                $infoE[$val]['dept']=trim($excelArr['部门'][$key]);
                $infoE[$val]['pym']=trim($excelArr['月份'][$key]);
                $infoE[$val]['cogjjam']=trim($excelArr['公司公积金'][$key]);
                $infoE[$val]['coshbam']=trim($excelArr['公司社保费'][$key]);
            }
            if(count($infoE)){
                $i=1;
                foreach($infoE as $key=>$val){
                    $cl='green';
                    $str.='<tr style="color:'.$cl.'">
                        <td>'.$i.'</td>
                        <td>'.$key.'</td>
                        <td>'.$val['name'].'</td>
                        <td>'.$val['com'].'</td>
                        <td>'.$val['dept'].'</td>
                        <td>'.$val['pym'].'</td>
                        <td>'.$val['cogjjam'].'</td>
                        <td>'.$val['coshbam'].'</td>
                        </tr>';
                    $i++;
                }
            }
        }
        return $str;
    }
    function model_hr_jf_ini_in(){
        set_time_limit(600);
        $ckt=$_POST['ckt'];
        try{
            $excelfilename='attachment/xls_model/temp/'.$ckt.".xls";
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
                foreach($excelArr['员工号'] as $key=>$val ){
                    $infoE[$val]['name']=$excelArr['名称'][$key];
                    $infoE[$val]['com']=trim($excelArr['公司'][$key]);
                    $infoE[$val]['dept']=trim($excelArr['部门'][$key]);
                    $infoE[$val]['pym']=trim($excelArr['月份'][$key]);
                    $infoE[$val]['cogjjam']=trim($excelArr['公司公积金'][$key]);
                    $infoE[$val]['coshbam']=trim($excelArr['公司社保费'][$key]);
                }
                if(count($infoE)){
                    foreach($infoE as $key=>$val){
                        $pym=  explode('-', $val['pym']);
                        $sql="update  salary_pay p 
                            left join hrms h on (h.user_id=p.userid)
                            set p.cogjjam='".$this->salaryClass->encryptDeal($val['cogjjam'])."'
                            , p.coshbam='".$this->salaryClass->encryptDeal($val['coshbam'])."'
                            where h.usercard='".$key."' 
                                and p.pyear=".$pym[0]." and p.pmon=".$pym[1]." "; 
                        $query=$this->db->query_exc($sql);
                    }
                }
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
            
    }
    /**
     *对比数据
     * @return string 
     */
    function model_pay_ctr($ckt){
        set_time_limit(600);
        $type=$_POST['ctr_type'];
        $compt=$_POST['ctr_com'];
        $sqlCk=$type=='com'?'0':'1';
        $str='';
        $infoE=array();
        $infoR=array();
        $infoA=array();
        $pyear=$_POST['ctr_py'];
        $pmon=$_POST['ctr_pm'];
        $comtable=$this->get_com_sql($compt);
        $comarr=array_flip($this->salaryCom);
        try{
            $sql="delete from salary_temp where code in ('gjjam','shbam','prepaream' , 'handicapam' , 'manageam' , 'cogjjam' , 'coshbam' ,'jfcom') ";
            $this->db->query_exc($sql);
            $excelfilename='attachment/xls_model/hr_ctr/'.$ckt.".xls";
            if(empty ($_FILES["ctr_file"]["tmp_name"])){
                $str='<tr><td colspan="16">请导入对比数据！</td></tr>';
            }elseif (!move_uploaded_file( $_FILES["ctr_file"]["tmp_name"], $excelfilename) ){
                $str='<tr><td colspan="16">上传失败！</td></tr>';
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
                if(!in_array('员工', $excelFields)||!in_array('员工号', $excelFields)
                        ||!in_array('公积金', $excelFields)||!in_array('社保费', $excelFields)||!in_array('缴付公司', $excelFields)){
                    throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                }
                if(count($excelArr)){
                    foreach($excelArr['员工号'] as $key=>$val ){
                        if($type=='com'){
                            if(!is_numeric($excelArr['公积金'][$key])||!is_numeric($excelArr['社保费'][$key])){
                                throw new Exception('导入数据信息含有非法数据！'.$val);
                            }
                            $infoE[$val]['name']=$excelArr['员工'][$key];
                            $infoE[$val]['gjjam']=trim($excelArr['公积金'][$key]);
                            $infoE[$val]['shbam']=trim($excelArr['社保费'][$key]);
                            $infoE[$val]['cogjjam']=trim($excelArr['公司公积金'][$key]);
                            $infoE[$val]['coshbam']=trim($excelArr['公司社保费'][$key]);
                            $infoE[$val]['pre']=trim($excelArr['调配费'][$key]);
                            $infoE[$val]['had']=trim($excelArr['残障金'][$key]);
                            $infoE[$val]['man']=trim($excelArr['管理费'][$key]);
                            $infoE[$val]['jfcom']=$comarr[trim($excelArr['缴付公司'][$key])];
                            $infoE[$val]['jfcomname']=trim($excelArr['缴付公司'][$key]);
                        }else{
                            if(
                                !is_numeric($excelArr['公积金'][$key])||!is_numeric($excelArr['社保费'][$key])
                                ||!is_numeric($excelArr['公司公积金'][$key])||!is_numeric($excelArr['公司社保费'][$key])
                                ||!is_numeric($excelArr['调配费'][$key])||!is_numeric($excelArr['残障金'][$key])
                                ||!is_numeric($excelArr['管理费'][$key])
                             ){
                                throw new Exception('导入数据信息含有非法数据！'.$key);
                            }
                            $infoE[$val]['name']=$excelArr['员工'][$key];
                            $infoE[$val]['gjjam']=trim($excelArr['公积金'][$key]);
                            $infoE[$val]['shbam']=trim($excelArr['社保费'][$key]);
                            $infoE[$val]['cogjjam']=trim($excelArr['公司公积金'][$key]);
                            $infoE[$val]['coshbam']=trim($excelArr['公司社保费'][$key]);
                            $infoE[$val]['pre']=trim($excelArr['调配费'][$key]);
                            $infoE[$val]['had']=trim($excelArr['残障金'][$key]);
                            $infoE[$val]['man']=trim($excelArr['管理费'][$key]);
                            $infoE[$val]['jfcom']=$comarr[trim($excelArr['缴付公司'][$key])];
                            $infoE[$val]['jfcomname']=trim($excelArr['缴付公司'][$key]);
                        }
                    }
                }
                $sql="select
                        s.username , s.userid , p.gjjam , p.shbam
                        , p.prepaream , p.handicapam , p.manageam 
                        , h.expflag , h.usercard as idcard
                        , p.cogjjam , p.coshbam , p.jfcom
                    from
                        salary s
                        left join ".$comtable."salary_pay p on (s.userid=p.userid and p.pyear='".$pyear."'  and p.pmon='".$pmon."' )
                        left join hrms h on(s.userid=h.user_id) 
                    where h.expflag='".$sqlCk."' and p.usercom='".$compt."'";
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    $infoA[]=$row['idcard'];
                    $gjj=$this->salaryClass->decryptDeal($row['gjjam']);
                    $shb=$this->salaryClass->decryptDeal($row['shbam']);
                    $cogjj=$this->salaryClass->decryptDeal($row['cogjjam']);
                    $coshb=$this->salaryClass->decryptDeal($row['coshbam']);
                    if($sqlCk=='1'){
                        $pre=$this->salaryClass->decryptDeal($row['prepaream']);
                        $had=$this->salaryClass->decryptDeal($row['handicapam']);
                        $man=$this->salaryClass->decryptDeal($row['manageam']);
                    }else{
                        $pre=$this->salaryClass->decryptDeal($row['prepaream']);
                        $had=$this->salaryClass->decryptDeal($row['handicapam']);
                        $man=$this->salaryClass->decryptDeal($row['manageam']);
                    }
                    $jfcom=$row['jfcom'];
                    if(array_key_exists($row['idcard'],$infoE)){//在列表中
                        if(($gjj==''||$gjj==0)
                            &&($shb==''||$shb==0)
                            &&($pre==''||$pre==0)
                            &&($had==''||$had==0)
                            &&($man==''||$man==0)
                            &&($cogjj==''||$cogjj==0)
                            &&($coshb==''||$coshb==0)
                            &&($infoE[$row['idcard']]['cogjjam']==''||$infoE[$row['idcard']]['cogjjam']==0)
                            &&($infoE[$row['idcard']]['coshbam']==''||$infoE[$row['idcard']]['coshbam']==0)
                            &&($infoE[$row['idcard']]['gjjam']==''||$infoE[$row['idcard']]['gjjam']==0)
                            &&($infoE[$row['idcard']]['shbam']==''||$infoE[$row['idcard']]['shbam']==0)
                            &&($infoE[$row['idcard']]['pre']==''||$infoE[$row['idcard']]['pre']==0)
                            &&($infoE[$row['idcard']]['had']==''||$infoE[$row['idcard']]['had']==0)
                            &&($infoE[$row['idcard']]['man']==''||$infoE[$row['idcard']]['man']==0) ){
                            continue;
                        }
                        if(floatval($gjj)!=floatval($infoE[$row['idcard']]['gjjam'])
                            ||floatval($shb)!=floatval($infoE[$row['idcard']]['shbam'])
                            ||floatval($cogjj)!=floatval($infoE[$row['idcard']]['cogjjam'])
                            ||floatval($coshb)!=floatval($infoE[$row['idcard']]['coshbam'])
                            ||floatval($pre)!=floatval($infoE[$row['idcard']]['pre'])
                            ||floatval($had)!=floatval($infoE[$row['idcard']]['had'])
                            ||floatval($man)!=floatval($infoE[$row['idcard']]['man'])
                            ||($jfcom)!=($infoE[$row['idcard']]['jfcom'])){
                            $infoR[$row['idcard']]['name']=$row['username'];
                            $infoR[$row['idcard']]['gjj']=$gjj;
                            $infoR[$row['idcard']]['shb']=$shb;
                            $infoR[$row['idcard']]['pre']=$pre;
                            $infoR[$row['idcard']]['had']=$had;
                            $infoR[$row['idcard']]['man']=$man;
                            $infoR[$row['idcard']]['gjjex']=$infoE[$row['idcard']]['gjjam'];
                            $infoR[$row['idcard']]['shbex']=$infoE[$row['idcard']]['shbam'];
                            $infoR[$row['idcard']]['preex']=$infoE[$row['idcard']]['pre'];
                            $infoR[$row['idcard']]['hadex']=$infoE[$row['idcard']]['had'];
                            $infoR[$row['idcard']]['manex']=$infoE[$row['idcard']]['man'];
                            $infoR[$row['idcard']]['type']=0;
                            $infoR[$row['idcard']]['cogjj']=$cogjj;
                            $infoR[$row['idcard']]['coshb']=$coshb;
                            $infoR[$row['idcard']]['cogjjex']=$infoE[$row['idcard']]['cogjjam'];
                            $infoR[$row['idcard']]['coshbex']=$infoE[$row['idcard']]['coshbam'];
                            $infoR[$row['idcard']]['jfcom']=$jfcom;
                            $infoR[$row['idcard']]['jfcomx']=$infoE[$row['idcard']]['jfcom'];
                        }
                    }else{
                        if(($gjj==''||$gjj==0)&&($shb==''||$shb==0)&&($pre==''||$pre==0)
                            &&($had==''||$had==0)
                            &&($man==''||$man==0)&&($cogjj==''||$cogjj==0)&&($coshb==''||$coshb==0)){
                            continue;
                        }
                        $infoR[$row['idcard']]['name']=$row['username'];
                        $infoR[$row['idcard']]['gjj']=$gjj;
                        $infoR[$row['idcard']]['shb']=$shb;
                        $infoR[$row['idcard']]['pre']=$pre;
                        $infoR[$row['idcard']]['had']=$had;
                        $infoR[$row['idcard']]['man']=$man;
                        $infoR[$row['idcard']]['gjjex']='0';
                        $infoR[$row['idcard']]['shbex']='0';
                        $infoR[$row['idcard']]['preex']='0';
                        $infoR[$row['idcard']]['hadex']='0';
                        $infoR[$row['idcard']]['manex']='0';
                        $infoR[$row['idcard']]['type']=1;
                        $infoR[$row['idcard']]['cogjj']=$cogjj;
                        $infoR[$row['idcard']]['coshb']=$coshb;
                        $infoR[$row['idcard']]['cogjjex']='0';
                        $infoR[$row['idcard']]['coshbex']='0';
                        $infoR[$row['idcard']]['jfcom']=$jfcom;
                        $infoR[$row['idcard']]['jfcomx']=$jfcom;
                    }
                }
                if(count($infoE)){
                    foreach($infoE as $key=>$val){
                        if(!in_array($key,$infoA)){
                            $infoR[$key]['name']=$val['name'];
                            $infoR[$key]['gjj']=0;
                            $infoR[$key]['shb']=0;
                            $infoR[$key]['pre']=0;
                            $infoR[$key]['had']=0;
                            $infoR[$key]['man']=0;
                            $infoR[$key]['gjjex']=$val['gjjam'];
                            $infoR[$key]['shbex']=$val['shbam'];
                            $infoR[$key]['preex']=$val['pre'];
                            $infoR[$key]['hadex']=$val['had'];
                            $infoR[$key]['manex']=$val['man'];
                            $infoR[$key]['type']=2;
                            $infoR[$key]['cogjj']=0;
                            $infoR[$key]['coshb']=0;
                            $infoR[$key]['cogjjex']=$val['cogjjam'];
                            $infoR[$key]['coshbex']=$val['coshbam'];
                            $infoR[$key]['jfcom']=$val['jfcom'];
                            $infoR[$key]['jfcomx']=$val['jfcom'];
                        }
                    }
                }
                
                $this->db->query("START TRANSACTION");
                if(count($infoR)){
                    foreach($infoR as $key=>$val){
                        if($val['type']=='0'){
                            $cl='blue';
                        }elseif($val['type']=='1'){
                            $cl='green';
                        }elseif($val['type']=='2'){
                            $cl='#000000';
                        }
                        $str.='<tr style="color:'.$cl.'">
                            <td>'.$val['name'].'</td>
                            <td>'.$key.'</td>
                            <td style="background-color: #FFE573">'.$this->salaryCom[$val['jfcomx']].'</td>
                            <td>'.$this->salaryCom[$val['jfcom']].'</td>
                            <td style="background-color: #FFE573">'.$val['gjjex'].'</td>
                            <td>'.$val['gjj'].'</td>
                            <td style="background-color: #FFE573">'.$val['shbex'].'</td>
                            <td>'.$val['shb'].'</td>
                            <td style="background-color: #FFE573">'.$val['cogjjex'].'</td>
                            <td>'.$val['cogjj'].'</td>
                            <td style="background-color: #FFE573">'.$val['coshbex'].'</td>
                            <td>'.$val['coshb'].'</td>
                            <td style="background-color: #FFE573">'.$val['preex'].'</td>
                            <td>'.$val['pre'].'</td>
                            <td style="background-color: #FFE573">'.$val['hadex'].'</td>
                            <td>'.$val['had'].'</td>
                            <td style="background-color: #FFE573">'.$val['manex'].'</td>
                            <td>'.$val['man'].'</td>
                            </tr>';
                        if(($val['type']=='0'||$val['type']=='1')&&$key){
                            if($val['gjj']!=$val['gjjex']){
                                $sql="insert into salary_temp ( idcard , code , amount , type , creator , tmpexp   )
                      values ( '".$key."' , 'gjjam' , '".$val['gjjex']."' , 'salary' ,'".$_SESSION['USER_ID']."' , '".$sqlCk."'  
                                )";
                                $this->db->query_exc($sql);
                            }
                            if($val['shb']!=$val['shbex']){
                                $sql="insert into salary_temp ( idcard , code , amount , type , creator , tmpexp )
                      values ( '".$key."' , 'shbam' , '".$val['shbex']."' , 'salary' ,'".$_SESSION['USER_ID']."' , '".$sqlCk."'  
                          )";
                                $this->db->query_exc($sql);
                            }
                            if($val['pre']!=$val['preex']){
                                $sql="insert into salary_temp ( idcard , code , amount , type , creator , tmpexp )
                      values ( '".$key."' , 'prepaream' , '".$val['preex']."' , 'salary' ,'".$_SESSION['USER_ID']."', '".$sqlCk."'  
                          )";
                                $this->db->query_exc($sql);
                            }
                            if($val['had']!=$val['hadex']){
                                $sql="insert into salary_temp ( idcard , code , amount , type , creator , tmpexp )
                      values ( '".$key."' , 'handicapam' , '".$val['hadex']."' , 'salary' ,'".$_SESSION['USER_ID']."', '".$sqlCk."'  
                          )";
                                $this->db->query_exc($sql);
                            }
                            if($val['man']!=$val['manex']){
                                $sql="insert into salary_temp ( idcard , code , amount , type , creator , tmpexp )
                      values ( '".$key."' , 'manageam' , '".$val['manex']."' , 'salary' ,'".$_SESSION['USER_ID']."', '".$sqlCk."'  
                          )";
                                $this->db->query_exc($sql);
                            }
                            if($val['cogjj']!=$val['cogjjex']){
                                $sql="insert into salary_temp ( idcard , code , amount , type , creator , tmpexp )
                      values ( '".$key."' , 'cogjjam' , '".$val['cogjjex']."' , 'salary' ,'".$_SESSION['USER_ID']."' , '".$sqlCk."'  
                          )";
                                $this->db->query_exc($sql);
                            }
                            if($val['coshb']!=$val['coshbex']){
                                $sql="insert into salary_temp ( idcard , code , amount , type , creator , tmpexp )
                      values ( '".$key."' , 'coshbam' , '".$val['coshbex']."' , 'salary' ,'".$_SESSION['USER_ID']."' , '".$sqlCk."'  
                          )";
                                $this->db->query_exc($sql);
                            }
                            if($val['jfcom']!=$val['jfcomx']){
                                $sql="insert into salary_temp ( idcard , code , jfcom , type , creator , tmpexp )
                      values ( '".$key."' , 'jfcom' , '".$val['jfcomx']."' , 'salary' ,'".$_SESSION['USER_ID']."' , '".$sqlCk."'  
                          )";
                                $this->db->query_exc($sql);
                            }
                        }
                    }
                }
                $this->db->query("COMMIT");

            }
            if(empty($str)){
                $str='<tr><td colspan="16">导入数据和OA数据核对正确，无需修改！</td></tr>';
            }
        }catch(Exception $e){
            $this->db->query("ROLLBACK");
            $str='<tr><td colspan="16">导入数据错误！'.$e->getMessage().'</td></tr>';
        }
        return $str;
    }
    /**
     *缴费更新对比数据
     * @return <type> 
     */
    function model_hr_pay_ctr_in(){
        try {
            //print_r($_POST);
            $ckyear=$_POST['cky'];
            $ckmon=$_POST['ckm'];
            $ckcom=$_POST['ckc'];
            if(empty($ckyear)||empty($ckmon)||empty($ckcom)){
                throw new Exception('data error');
            }
            $info=array();
            $comtable=$this->get_com_sql($ckcom);
            $this->db->query("START TRANSACTION");
            $sql="select
                    p.id , s.rand_key , t.code , t.amount , s.userid  
                    , t.id as tmpid , t.jfcom
                from salary_temp t
                    left join hrms h on (t.idcard=h.usercard)
                    left join salary s on ( s.userid=h.user_id )
                    left join user u on (u.user_id=s.userid)
                    left join ".$comtable."salary_pay p on (s.userid=p.userid and p.pyear='".$ckyear."' and p.pmon='".$ckmon."' )
                where s.userid=p.userid and  t.code in ('gjjam','shbam','prepaream','handicapam','manageam','cogjjam','coshbam','jfcom') ";
            $query=$this->db->query_exc($sql);
            if(!$this->db->affected_rows()){
                throw new Exception('No updated data');
            }
            while($row=$this->db->fetch_array($query)){
                $info[$row['tmpid']][$row['code']]=($row['code']=='jfcom'?$row['jfcom']:$row['amount']);
                $info[$row['tmpid']]['pid']=$row['id'];
                $info[$row['tmpid']]['skey']=$row['rand_key'];
            }
            if(count($info)&&!empty($info)){
                foreach($info as $key=>$val){
                    
                    if(!empty($val['pid'])){
                        if(!empty($val['jfcom'])){
                            $arrtemp=array('paycreatedt'=>'now()','paycreator'=>$_SESSION['USER_ID'],'jfcom'=>$val['jfcom']);
                            $sp=array(0,1,2);
                            $arrtempp=array('jfcom'=>$val['jfcom']);
                            $spp=array(0);
                        }else{
                            $arrtemp=array('paycreatedt'=>'now()','paycreator'=>$_SESSION['USER_ID']);
                            $sp=array(0,1);
                            $arrtempp=array();
                            $spp=array();
                            foreach($val as $vkey=>$vval){
                                if($vkey!='pid'&&$vkey!='skey'){
                                    $arrtemp[$vkey]=$vval;
                                    $arrtempp[$vkey]=$vval;
                                }
                            }
                        }
                        
                        if($val['pid']){
                            $this->model_salary_update($val['skey'],
                                $arrtemp,$sp
                            );
                            $this->model_pay_update($val['pid'],
                                    $arrtempp,$spp,$comtable
                            );
                            $this->model_pay_stat($val['pid'],$comtable);
                        }
                    }
                }
            }
            $this->db->query("COMMIT");
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '缴费信息', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '缴费信息', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
     * 
     */
    function model_hr_exp_ini($ckt){
        set_time_limit(600);
        $type=$_POST['ctr_type'];
        $sqlCk=$type=='com'?'0':'1';
        $str='';
        $infoE=array();
        try{
            $sql="delete from salary_temp where code = 'amount' and creator='".$_SESSION['USER_ID']."' ";
            $this->db->query_exc($sql);
            $excelfilename='attachment/xls_model/exp_ini/'.$ckt.".xls";
            if(empty ($_FILES["ctr_file"]["tmp_name"])){
                $str='<tr><td colspan="10">请导入外派初始化数据表！</td></tr>';
            }elseif (!move_uploaded_file( $_FILES["ctr_file"]["tmp_name"], $excelfilename) ){
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
                if(!in_array('员工', $excelFields)||!in_array('员工号', $excelFields)
                        ||!in_array('基本工资', $excelFields)||!in_array('个人公积金', $excelFields) 
                        ||!in_array('个人社保费', $excelFields)||!in_array('公司公积金', $excelFields)
                        ||!in_array('公司社保费', $excelFields)||!in_array('调配费', $excelFields)
                        ||!in_array('残障金', $excelFields)||!in_array('管理费', $excelFields))
                {
                    throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                }
                if(count($excelArr)&&!empty($excelArr)){
                    foreach($excelArr['员工号'] as $key=>$val ){
                        $infoE[$val]['name']=$excelArr['员工'][$key];
                        $infoE[$val]['amount']=$excelArr['基本工资'][$key];
                        $infoE[$val]['gjjam']=$excelArr['个人公积金'][$key];
                        $infoE[$val]['shbam']=$excelArr['个人社保费'][$key];
                        $infoE[$val]['cogjjam']=$excelArr['公司公积金'][$key];
                        $infoE[$val]['coshbam']=$excelArr['公司社保费'][$key];
                        $infoE[$val]['prepaream']=$excelArr['调配费'][$key];
                        $infoE[$val]['handicapam']=$excelArr['残障金'][$key];
                        $infoE[$val]['manageam']=$excelArr['管理费'][$key];
                    }
                }
                $sql="select
                        s.username , s.userid , h.usercard as idcard
                        , year(s.leavedt) as ly , month(s.leavedt) as lm
                    from
                        salary s
                        left join hrms h on(s.userid=h.user_id)
                    where h.expflag='1'  ";
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    $infoA[]=$row['idcard'];
                    if(array_key_exists($row['idcard'],$infoE)){
                        $infoE[$row['idcard']]['type']=0;
                        if(!empty($row['ly'])&&!empty($row['lm'])){
                            if($row['ly'] < $this->nowy){
                                $infoE[$row['idcard']]['type']=1;
                            }
                            if($row['ly'] == $this->nowy && $row['lm'] < $this->nowm ){
                                $infoE[$row['idcard']]['type']=1;
                            }
                        }
                    }
                }
                if(count($infoE)){
                    foreach($infoE as $key=>$val){
                        if(!in_array($key,$infoA)){
                            $infoE[$row['idcard']]['type']=1;
                        }
                    }
                }
                if(count($infoE)){
                    $totalA=array('amount'=>0,'gjjam'=>0,'shbam'=>0,'cogjjam'=>0,'coshbam'=>0,'prepaream'=>0
                        ,'handicapam'=>0,'manageam'=>0);
                    foreach($infoE as $key=>$val){
                        if($val['type']=='0'){
                            $cl='green';
                        }elseif($val['type']=='1'){
                            $cl='red';
                        }
                        $totalA['amount']=$totalA['amount']+$val['amount'];
                        $totalA['gjjam']=$totalA['gjjam']+$val['gjjam'];
                        $totalA['shbam']=$totalA['shbam']+$val['shbam'];
                        $totalA['cogjjam']=$totalA['cogjjam']+$val['cogjjam'];
                        $totalA['coshbam']=$totalA['coshbam']+$val['coshbam'];
                        $totalA['prepaream']=$totalA['prepaream']+$val['prepaream'];
                        $totalA['handicapam']=$totalA['handicapam']+$val['handicapam'];
                        $totalA['manageam']=$totalA['manageam']+$val['manageam'];
                        $str.='<tr style="color:'.$cl.'">
                                <td>'.$key.'</td>
                                <td>'.$val['name'].'</td>
                                <td>'.$val['amount'].'</td>
                                <td>'.$val['gjjam'].'</td>
                                <td>'.$val['shbam'].'</td>
                                <td>'.$val['cogjjam'].'</td>
                                <td>'.$val['coshbam'].'</td>
                                <td>'.$val['prepaream'].'</td>
                                <td>'.$val['handicapam'].'</td>
                                <td>'.$val['manageam'].'</td>
                            </tr>';
                    }
                }
                $str.='<tr style="color:red">
                    <td></td>
                    <td>合计：</td>
                    <td>'.$totalA['amount'].'</td>
                    <td>'.$totalA['gjjam'].'</td>
                    <td>'.$totalA['shbam'].'</td>
                    <td>'.$totalA['cogjjam'].'</td>
                    <td>'.$totalA['coshbam'].'</td>
                    <td>'.$totalA['prepaream'].'</td>
                    <td>'.$totalA['handicapam'].'</td>
                    <td>'.$totalA['manageam'].'</td>
                </tr>';
            }
        }catch(Exception $e){
            $str='<tr><td colspan="10">导入数据错误！'.$e->getMessage().'</td></tr>';
        }
        return $str;
    }
    /**
     * 
     */
    function model_hr_sub_ini($ckt){
        set_time_limit(600);
        $type=$_POST['ctr_type'];
        $sqlCk=$type=='com'?'0':'1';
        $str='';
        $infoE=array();
        $infoA=array();
        try{
            $sql="delete from salary_temp where code = 'amount' and creator='".$_SESSION['USER_ID']."' ";
            $this->db->query_exc($sql);
            $excelfilename='attachment/xls_model/exp_ini/'.$ckt.".xls";
            if(empty ($_FILES["ctr_file"]["tmp_name"])){
                $str='<tr><td colspan="10">请导入数据表！</td></tr>';
            }elseif (!move_uploaded_file( $_FILES["ctr_file"]["tmp_name"], $excelfilename) ){
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
                if(!in_array('员工', $excelFields)||!in_array('员工号', $excelFields)
                        ||!in_array('基本工资', $excelFields))
                {
                    throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                }
                if(count($excelArr)&&!empty($excelArr)){
                    foreach($excelArr['员工号'] as $key=>$val ){
                        $infoE[$val]['name']=$excelArr['员工'][$key];
                        $infoE[$val]['amount']=$excelArr['基本工资'][$key];
                    }
                }
                $sql="select
                        s.username , s.userid , h.usercard as idcard
                        , year(s.leavedt) as ly , month(s.leavedt) as lm 
                        , u.company , u.salarycom
                    from
                        salary s
                        left join hrms h on (s.userid=h.user_id)
                        left join user u on (s.userid=u.user_id)
                    where ( u.company in ('sy','br') or u.salarycom in ('sy','br')) and s.userid=h.user_id ";
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    $infoA[]=$row['idcard'];
                    if(array_key_exists($row['idcard'],$infoE)){
                        
                        $infoE[$row['idcard']]['type']=0;
                        if(empty($row['salarycom'])){
                            $infoE[$row['idcard']]['com']=$this->salaryCom[$row['company']];
                        }else{
                            $infoE[$row['idcard']]['com']=$this->salaryCom[$row['salarycom']];
                        }
                        if(!empty($row['salarycom'])){
                            $row['company']=$row['salarycom'];
                        }
                        $gjj=$this->salaryClass->salaryGjj($infoE[$row['idcard']]['amount'], $row['company']);
                        $infoE[$row['idcard']]['gjjp']=$gjj['p'];
                        $infoE[$row['idcard']]['gjjc']=$gjj['c'];
                        $shb=$this->salaryClass->salaryShb($infoE[$row['idcard']]['amount'], $row['company'], $row['idcard']);
                        $infoE[$row['idcard']]['shbp']=$shb['p'];
                        $infoE[$row['idcard']]['shbc']=$shb['c'];
                    }
                }
                if(count($infoE)){
                    foreach($infoE as $key=>$val){
                        if(!in_array($key,$infoA)){
                            $infoE[$key]['type']=1;
                        }
                    }
                }
                if(count($infoE)){
                    $totalA=array('amount'=>0);
                    foreach($infoE as $key=>$val){
                        if($val['type']=='0'){
                            $cl='green';
                        }elseif($val['type']=='1'){
                            $cl='red';
                        }
                        $totalA['amount']=$totalA['amount']+$val['amount'];
                        $str.='<tr style="color:'.$cl.'">
                                <td>'.$key.'</td>
                                <td>'.$val['name'].'</td>
                                <td>'.$val['com'].'</td>
                                <td>'.$val['amount'].'</td>
                                <td>'.$val['shbp'].'</td>
                                <td>'.$val['shbc'].'</td>
                                <td>'.$val['gjjp'].'</td>
                                <td>'.$val['gjjc'].'</td>
                            </tr>';
                    }
                }
            }
        }catch(Exception $e){
            $str='<tr><td colspan="10">导入数据错误！'.$e->getMessage().'</td></tr>';
        }
        return $str;
    }
    /**
     * 导入初始化外派
     */
    function model_hr_exp_in(){
        set_time_limit(600);
        $ckt=$_POST['ckt'];
        $excelfilename=WEB_TOR.'attachment/xls_model/exp_ini/'.$ckt.".xls";
        try{
            if(!file_exists($excelfilename)){
                throw new Exception('File does not exist');
            }
            include('includes/classes/excel.php');
            $excel = Excel::getInstance();
            $excel->setFile($excelfilename);
            $excel->Open();
            $excel->setSheet();
            $excelFields = $excel->getFields();
            $excelArr=$excel->getAllData();
            $excel->Close();
            if(!in_array('员工', $excelFields)||!in_array('员工号', $excelFields)
                    ||!in_array('基本工资', $excelFields)||!in_array('个人公积金', $excelFields)
                    ||!in_array('个人社保费', $excelFields)||!in_array('公司公积金', $excelFields)
                    ||!in_array('公司社保费', $excelFields)||!in_array('调配费', $excelFields)
                    ||!in_array('残障金', $excelFields)||!in_array('管理费', $excelFields))
            {
                throw new Exception('Update failed');
            }
            if(count($excelArr)&&!empty($excelArr)){
                foreach($excelArr['员工号'] as $key=>$val ){
                    $infoE[$val]['name']=$excelArr['员工'][$key];
                    $infoE[$val]['amount']=$excelArr['基本工资'][$key];
                    $infoE[$val]['gjjam']=$excelArr['个人公积金'][$key];
                    $infoE[$val]['shbam']=$excelArr['个人社保费'][$key];
                    $infoE[$val]['cogjjam']=$excelArr['公司公积金'][$key];
                    $infoE[$val]['coshbam']=$excelArr['公司社保费'][$key];
                    $infoE[$val]['prepaream']=$excelArr['调配费'][$key];
                    $infoE[$val]['handicapam']=$excelArr['残障金'][$key];
                    $infoE[$val]['manageam']=$excelArr['管理费'][$key];
                }
            }
            if(count($infoE)){
                foreach($infoE as $key=>$val){
                    $sql="select s.rand_key , p.id as pid  from salary s
                        left join salary_pay p on (s.userid=p.userid and p.pyear='".$this->nowy."' and pmon='".$this->nowm."')
                        left join hrms h on (s.userid=h.user_id)
                        where  h.usercard='".$key."' and  h.expflag='1' ";
                    $res=$this->db->get_one($sql);
                    if(!empty($res)){
                        $tmps=array('amount'=>$val['amount'],'gjjam'=>$val['gjjam'],'shbam'=>$val['shbam']
                            ,'cogjjam'=>$val['cogjjam'],'coshbam'=>$val['coshbam'],'prepaream'=>$val['prepaream']
                            ,'handicapam'=>$val['handicapam'],'manageam'=>$val['manageam']);
                        $temp=array('baseam'=>$val['amount'],'gjjam'=>$val['gjjam'],'shbam'=>$val['shbam']
                            ,'cogjjam'=>$val['cogjjam'],'coshbam'=>$val['coshbam'],'prepaream'=>$val['prepaream']
                            ,'handicapam'=>$val['handicapam'],'manageam'=>$val['manageam']);
                        if($res['rand_key']){
                            $this->model_salary_update($res['rand_key'],$tmps);
                        }
                        if($res['pid']){
                            $this->model_pay_update($res['pid'],$temp);
                            $this->model_pay_stat($res['pid']);
                        }
                    }
                }
            }
        }catch(Exception $e){
            $responce->error = un_iconv($e->getMessage());
        }
        return $responce;
    }
    /**
     * 导入初始化子公司
     */
    function model_hr_sub_ini_in(){
        set_time_limit(600);
        $ckt=$_POST['ckt'];
        $excelfilename=WEB_TOR.'attachment/xls_model/exp_ini/'.$ckt.".xls";
        try{
            if(!file_exists($excelfilename)){
                throw new Exception('File does not exist');
            }
            include('includes/classes/excel.php');
            $excel = Excel::getInstance();
            $excel->setFile($excelfilename);
            $excel->Open();
            $excel->setSheet();
            $excelFields = $excel->getFields();
            $excelArr=$excel->getAllData();
            $excel->Close();
            if(!in_array('员工', $excelFields)||!in_array('员工号', $excelFields)
                    ||!in_array('基本工资', $excelFields))
            {
                throw new Exception('Update failed');
            }
            if(count($excelArr)&&!empty($excelArr)){
                foreach($excelArr['员工号'] as $key=>$val ){
                    $infoE[$val]['name']=$excelArr['员工'][$key];
                    $infoE[$val]['amount']=$excelArr['基本工资'][$key];
                    $infoE[$val]['gjjam']=0;
                    $infoE[$val]['shbam']=0;
                    $infoE[$val]['cogjjam']=0;
                    $infoE[$val]['coshbam']=0;
                    $infoE[$val]['type']=1;
                }
            }
            $sql="select
                    s.username , s.userid , h.usercard as idcard
                    , year(s.leavedt) as ly , month(s.leavedt) as lm 
                    , u.company , u.salarycom
                from
                    salary s
                    left join hrms h on (s.userid=h.user_id)
                    left join user u on (s.userid=u.user_id)
                where ( u.company in ('sy','br') or u.salarycom in ('sy','br')) and s.userid=h.user_id ";
            $query=$this->db->query_exc($sql);
            while($row=$this->db->fetch_array($query)){
                if(array_key_exists($row['idcard'],$infoE)){
                    $infoE[$row['idcard']]['type']=0;
                    $infoE[$row['idcard']]['com']=$row['company'];
                    $infoE[$row['idcard']]['scom']=$row['salarycom'];
                    if(!empty($row['salarycom'])){
                        $row['company']=$row['salarycom'];
                    }
                    $gjj=$this->salaryClass->salaryGjj($infoE[$row['idcard']]['amount'], $row['company']);
                    $infoE[$row['idcard']]['gjjam']=$gjj['p'];
                    $infoE[$row['idcard']]['cogjjam']=$gjj['c'];
                    $shb=$this->salaryClass->salaryShb($infoE[$row['idcard']]['amount'], $row['company'], $row['idcard']);
                    $infoE[$row['idcard']]['shbam']=$shb['p'];
                    $infoE[$row['idcard']]['coshbam']=$shb['c'];
                }
            }
            if(count($infoE)){
                foreach($infoE as $key=>$val){
                    if($val['type']=='0'){
                        //公司员工添加数据库名
                        $comtable=$this->get_com_sql($val['com'], $val['scom']);
                        //echo "\n\t";
                        $sql="select s.rand_key , p.id as pid  from salary s
                            left join ".$comtable."salary_pay p on (s.userid=p.userid and p.pyear='".$this->nowy."' and pmon='".$this->nowm."')
                            left join hrms h on (s.userid=h.user_id)
                            where  h.usercard='".$key."' ";
                        $res=$this->db->get_one($sql);
                        if(!empty($res)){
                            $tmps=array('amount'=>$val['amount'],'gjjam'=>$val['gjjam'],'shbam'=>$val['shbam']
                                ,'cogjjam'=>$val['cogjjam'],'coshbam'=>$val['coshbam'],'prepaream'=>$val['prepaream']
                                ,'handicapam'=>$val['handicapam'],'manageam'=>$val['manageam']);
                            $temp=array('baseam'=>$val['amount'],'gjjam'=>$val['gjjam'],'shbam'=>$val['shbam']
                                ,'cogjjam'=>$val['cogjjam'],'coshbam'=>$val['coshbam'],'prepaream'=>$val['prepaream']
                                ,'handicapam'=>$val['handicapam'],'manageam'=>$val['manageam']);
                            if($res['rand_key']){
                                $this->model_salary_update($res['rand_key'],$tmps);
                            }
                            if($res['pid']){
                                $this->model_pay_update($res['pid'],$temp,'',$comtable);
                                $this->model_pay_stat($res['pid'],$comtable);
                            }
                        }
                    }
                }
            }
        }catch(Exception $e){
            $responce->error = un_iconv($e->getMessage());
        }
        return $responce;
    }
    /**
     *获取公司表
     * @param type $com
     * @param type $scom
     * @return string 
     */
    function get_com_sql($com,$scom=''){
        $res='';
        if(!empty($scom)){
            $res=" `".$this->salarySql[$scom]."`.";
        }elseif(!empty($com)){
            $res=" `".$this->salarySql[$com]."`.";
        }
        return $res;
    }
    /**
     * 缴费导出
     */
    function model_hr_pay_xls() {
        $scom=$_GET['scom'];
        $comtable=$this->get_com_sql($scom);
        include( WEB_TOR . 'includes/classes/excel_out_class.php');
        $xls = new ExcelXML('gb2312', false, 'My Test Sheet');
        $data = array(1=> array ('年月','员工号', '姓名', '部门'
            ,'基本工资', '公积金', '社保费', '公司公积金', '公司社保费'
            , '调配费', '残障金', '管理费', '帐号', '邮箱'
            ,'开户行','身份证号','状态')
            );
        $xls->setStyle(array(4,5,6,7,8,9,10,11));
        $sqlstr='';
        if($_GET['type']=='com'){
            $sqlstr=" and h.expflag='0'";
        }elseif($_GET['type']=='exp'){
            $sqlstr=" and h.expflag='1'";
        }
        $sql="select
                s.rand_key , s.username , s.userid , s.olddept , h.userlevel
                , p.baseam as amount , p.gjjam , p.shbam
                , p.cogjjam , p.coshbam
                , p.prepaream , p.handicapam
                , p.manageam
                , s.amount , s.gjjam , s.shbam
                , s.cogjjam , s.coshbam
                , s.prepaream , s.handicapam
                , s.manageam
                , s.acc
                , s.email
                , s.accbank , s.idcard , s.usersta , h.userlevel , h.usercard
            from ".$comtable."salary_pay p 
                left join salary s on (s.userid=p.userid)
                left join hrms h on ( s.userid=h.user_id )
            where
                s.userid=h.user_id
                and s.userid=p.userid
                and p.pyear='".$_GET['sy']."'
                and p.pmon='".$_GET['sm']."'
                $sqlstr ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $tus=$row['usersta']=='3'?'离职':'在职';
            if($row['userlevel']=='4'){
                $amount=$this->salaryClass->decryptDeal($row['amount']);
            }else{
                $amount='-';
            }
            $data[]=array($_GET['sy'].'-'.$_GET['sm'],$row['usercard'],$row['username'],$row['olddept']
                ,$amount
                ,$this->salaryClass->decryptDeal($row['gjjam']),$this->salaryClass->decryptDeal($row['shbam'])
                ,$this->salaryClass->decryptDeal($row['cogjjam']),$this->salaryClass->decryptDeal($row['coshbam'])
                ,$this->salaryClass->decryptDeal($row['prepaream'])
                ,$this->salaryClass->decryptDeal($row['handicapam']),$this->salaryClass->decryptDeal($row['manageam'])
                ,$row['acc'],$row['email'],$row['accbank'],$row['idcard'],$tus
            );
        }
        $xls->addArray($data);
        $xls->generateXML(date('Y:m:d'));
	}
    /**
     * 邮件发送
     */
    function model_hr_email_send(){
        set_time_limit(600);
        $sqlStr="";
        if($_POST['usertype']=='2'){
            $sqlStr.=" and h.expflag='0' ";
        }elseif($_POST['usertype']=='3'){
            $sqlStr.=" and h.expflag='1' ";
        }
        $emailbody=$_POST['emailbd'];
        $sendArr=array();
        $sql="select
                s.email
            from salary s
                left join hrms h on(s.userid=h.user_id)
            where
                s.sta='0'
                $sqlStr  ";
        $query=$this->db->query($sql);
        while ($row=$this->db->fetch_array($query)) {
            $sendArr[]=$row['email'];
        }
        foreach($sendArr as $val){
            if($val){
                $this->model_send_email(($_POST['emailtl']), ($emailbody), $val);
            }
        }
        echo '1';
    }
    
    function model_hr_info_ck($bdt,$edt){
        $res='';
        $sql="select s.username ,  s.acc , h.account , s.idcard , h.card_no , s.accbank , h.bank
            from salary s
                left join hrms h on (s.userid=h.user_id)
            where s.userid=h.user_id and
                (   s.acc <> h.account or s.acc is null or s.acc=''
                    or s.idcard <> h.card_no or s.idcard is null or s.idcard=''
                    or s.accbank <> h.bank or s.accbank  is null or s.accbank=''
                )
                and to_days(s.comedt)>= to_days('".$bdt."')
                and to_days(s.comedt)<= to_days('".$edt."')
            order by s.id ";
        // year(s.comedt)='".date('Y')."' and month(s.comedt)='".date('m')."'
        $query=$this->db->query($sql);
        $i=1;
        while($row=$this->db->fetch_array($query)){
            $ac='';
            $cc='';
            $bc='';
            if($row['acc']!=$row['account']){
                $ac='color:red;';
            }
            if($row['idcard']!=$row['card_no']){
                $cc='color:red;';
            }
            if($row['accbank']!=$row['bank']){
                $bc='color:red;';
            }
            $res.='<tr>
                <td align="center" >'.$i.'</td>
                <td align="center" >'.$row['username'].'</td>
                <td align="center" style="'.$ac.'">'.$row['acc'].'</td>
                <td align="center" style="'.$ac.'">'.$row['account'].'</td>
                <td align="center" style="'.$cc.'">'.$row['idcard'].'</td>
                <td align="center" style="'.$cc.'">'.$row['card_no'].'</td>
                <td align="center" style="'.$bc.'">'.$row['accbank'].'</td>
                <td align="center" style="'.$bc.'">'.$row['bank'].'</td>
             </tr>';
            $i++;
        }
        if(empty($res)){
            $res='<tr><td colspan="8">信息核对完成，无错误信息！</td></tr>';
        }
        return $res;
    }
    /**
     *员工信息
     */
    function model_hr_info_list(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        $start = $limit * $page - $limit;
        //总数
        $sql = "select count(s.id)
            from salary s
                left join user u on (s.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where
                s.userid=u.user_id
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(s.id)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select
                s.rand_key , s.oldname , s.olddept , s.comedt
                ,s.oldarea , s.acc , s.accbank , s.idcard , s.email , s.cessebase
                ,s.usersta
            from salary s
                left join user u on (s.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where
                s.userid=u.user_id
                $sqlSch
            order by s.usersta , s.leavecreatedt desc , $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $responce->rows[$i]['id'] = $row['userid'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array("", $row['rand_key']
                                , $row['oldname']
                                , $row['olddept']
                                , $row['comedt']
                                , $row['oldarea']
                                , $row['cessebase']
                                , $row['acc']
                                , $row['accbank']
                                , $row['idcard']
                                , $row['email']
                                , $this->userSta[$row['usersta']]
                            )
            );
            $i++;
        }
        return $responce;
    }
    /**
     * 员工信息修改
     */
    function model_hr_info_in(){
        $id = $_POST['id'];
        try {
            $this->db->query("START TRANSACTION");
            $sql = "select
                    s.userid , s.usersta
                from salary s
                where
                    s.rand_key='$id' and s.usersta!='3'  ";
            $res = $this->db->get_one($sql);
            if(!$res['userid']){
                throw new Exception('No data query');
            }
            $this->model_salary_update($id,
                        array('oldname'=>$_POST['username']
                            ,'username'=>$_POST['username']
                            ,'olddept'=>$_POST['dept'] , 'oldarea'=>$_POST['area']
                            ,'idcard'=>$_POST['idcard'],'acc'=>$_POST['acc']
                            ,'accbank'=>$_POST['accbank'],'email'=>$_POST['email']
                            ,'username'=>$_POST['username']
                        )
                        , array(0,1,2,3,4,5,6,7));
            $this->db->query("COMMIT");
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '员工信息', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '员工信息', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
     *导出 -2013-04-25 
     */
    function model_xls_out($flag,$filename='模板'){
        $repClass= new model_module_report();
        include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
        include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
        //创建一个Excel工作流
        $objPhpExcelFile = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
        $objPhpExcelFile = $objReader->load ( "upfile/".$filename.".xls" ); //读取模板
        //Excel2003及以前的格式
        $objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );
        //设置当前工作表的名称
        $objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", $filename ) );
        
        $gl=new includes_class_global();
        $is_echo=true;//按通用输出数据
        $bi=0;//插入表数据行数
        $setString=array();//文本输出
        if($flag=='dp_tol'){//部门工资统计表
            $ty=$_REQUEST['type'];
            $is_echo=false;//关闭
            $bi=4;//插入表数据行数
            $sy=$_REQUEST['sy'];
            $sm=$_REQUEST['sm'];
            $filename.=$sy.'-'.$sm; //保存文件名
            $objPhpExcelFile->setActiveSheetIndex(0);
            $objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '在职员工工资统计' ) );
            $deptarr=array();//部门信息
            $deptarr['其他']['其他']='其他';
            $sql="(
                SELECT  d.pdeptname  , d.dept_id , d.dept_name  FROM department d
                left join department ds on (d.depart_x  = SUBSTR(ds.depart_x, 1, 2) )
                where ds.delflag=0 and d.delflag=0  and d.dept_id = d.pdeptid 
                group by d.dept_id  having count(d.dept_id)  =1
                order by d.dept_name 
                )union(
                SELECT   d.pdeptname  , d.dept_id , d.dept_name  FROM department d
                left join department ds on (d.depart_x  = SUBSTR(ds.depart_x, 1, 2) )
                where ds.delflag=0 and d.delflag=0  and d.dept_id = d.pdeptid 
                group by d.dept_id  having count(d.dept_id)  >1
                order by d.dept_name  desc  limit 100
                )union(
                SELECT   d.pdeptname  , d.dept_id , d.dept_name  FROM department d
                where  d.delflag=0  and d.dept_id <> d.pdeptid 
                order by d.dept_name desc  
                )";//获取部门固定排序
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                if($row['dept_name']!='生产部'){
                    $deptarr[$row['pdeptname']][$row['dept_name']]=$row['dept_name'];
                }
                if($row['dept_name']=='交付部'){//生产部独立开来
                    $deptarr['生产部']['生产部']='生产部';
                }
            }
            $data=array();
            global $func_limit;
            $dppow=$this->model_dp_pow();
            if(!empty($func_limit['浏览部门'])){
                $sqlpow=" and ( p.deptid in ('". implode("','", $dppow['1'])."','" . implode("','", $dppow['2']) ."')
                    or p.userid='".$_SESSION['USER_ID']."'
                    or p.deptid in ( ".trim($func_limit['浏览部门'],',')." )
                    ) ";
            }else{
                $sqlpow=" and ( p.deptid in ('". implode("','", $dppow['1'])."','" . implode("','", $dppow['2']) ."')
                    or p.userid='".$_SESSION['USER_ID']."'
                    ) ";
            }
            if(!empty($func_limit['部门财务统计'])||$ty=='hr'){
                $sqlpow="";
            }
            //在职工资统计
            $datacells=array(
                        'jb'=>0
                        ,'xm'=>0
                        ,'bt'=>0
                        ,'fl'=>0
                        ,'kc'=>0
                        ,'yf'=>0
                        ,'djgs'=>0
                        ,'djshb'=>0
                        ,'djgjj'=>0
                        ,'sf'=>0
                        ,'gsshb'=>0
                        ,'gsgjj'=>0
                        ,'gl'=>0
                        ,'rs'=>0
                        
                        ,'jbdl'=>0
                        ,'xmdl'=>0
                        ,'btdl'=>0
                        ,'fldl'=>0
                        ,'kcdl'=>0
                        ,'yfdl'=>0
                        ,'djgsdl'=>0
                        ,'djshbdl'=>0
                        ,'djgjjdl'=>0
                        ,'sfdl'=>0
                        ,'gsshbdl'=>0
                        ,'gsgjjdl'=>0
                        ,'gldl'=>0
                        ,'rsdl'=>0
                        
                        ,'jbbr'=>0
                        ,'xmbr'=>0
                        ,'btbr'=>0
                        ,'flbr'=>0
                        ,'kcbr'=>0
                        ,'yfbr'=>0
                        ,'djgsbr'=>0
                        ,'djshbbr'=>0
                        ,'djgjjbr'=>0
                        ,'sfbr'=>0
                        ,'gsshbbr'=>0
                        ,'gsgjjbr'=>0
                        ,'glbr'=>0
                        ,'rsbr'=>0
                        
                        ,'jbsy'=>0
                        ,'xmsy'=>0
                        ,'btsy'=>0
                        ,'flsy'=>0
                        ,'kcsy'=>0
                        ,'yfsy'=>0
                        ,'djgssy'=>0
                        ,'djshbsy'=>0
                        ,'djgjjsy'=>0
                        ,'sfsy'=>0
                        ,'gsshbsy'=>0
                        ,'gsgjjsy'=>0
                        ,'glsy'=>0
                        ,'rssy'=>0
                        
                        ,'jbep'=>0
                        ,'xmep'=>0
                        ,'btep'=>0
                        ,'flep'=>0
                        ,'kcep'=>0
                        ,'yfep'=>0
                        ,'djgsep'=>0
                        ,'djshbep'=>0
                        ,'djgjjep'=>0
                        ,'sfep'=>0
                        ,'gsshbep'=>0
                        ,'gsgjjep'=>0
                        ,'glep'=>0
                        ,'rsep'=>0
                    );
            $sqlSch=" and p.pyear='".$sy."' and p.pmon='".$sm."'";
            //过滤离职人员
            $sqlSch.="  and ( p.nowamflag!=3 or p.nowamflag is null )  and ( p.salarydept not in ('其他') 
            		or p.userid='bin.chang' or p.userid='2903' )  ";
            $sql = "select
                        s.rand_key , u.user_name as username , d.dept_name as deptname , s.olddept , s.comedt , s.oldarea
                        , s.amount ,  p.gjjam , p.shbam
                        , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                        , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                        , h.userlevel , p.pyear , p.pmon
                        , p.sdyam , p.holsdelam , p.company , p.expflag , d.pdeptname , p.basenowam  ,  p.cogjjam , p.coshbam
                        , p.accrewam , p.accdelam , p.nowamflag , p.jfcom , p.manageam 
                    from 
                        ((
                            select  
                                p.gjjam , p.shbam
                                , p.remark , p.baseam , p.floatam , p.proam , p.otheram
                                , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                                , p.pyear , p.pmon
                                , p.sdyam , p.holsdelam , 'dl' as company , p.expflag , p.basenowam  ,  p.cogjjam , p.coshbam
                                , p.accrewam , p.accdelam , p.nowamflag , p.userid , p.deptid  , p.leaveflag , p.jfcom , p.manageam ,p.salarydept
                            from salary_pay p 
                            where 
                                p.leaveflag='0'
                                $sqlpow
                                $sqlSch       
                            )union(
                            select  
                                p.gjjam , p.shbam
                                , p.remark , p.baseam , p.floatam , p.proam , p.otheram
                                , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                                , p.pyear , p.pmon
                                , p.sdyam , p.holsdelam , 'sy' as company , p.expflag , p.basenowam  ,  p.cogjjam , p.coshbam
                                , p.accrewam , p.accdelam , p.nowamflag , p.userid , p.deptid  , p.leaveflag , p.jfcom , p.manageam  ,p.salarydept
                            from `shiyuanoa`.salary_pay p 
                            where 
                                p.leaveflag='0'
                                $sqlpow
                                $sqlSch      
                            )union(
                            select  
                                p.gjjam , p.shbam
                                , p.remark , p.baseam , p.floatam , p.proam , p.otheram
                                , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                                , p.pyear , p.pmon
                                , p.sdyam , p.holsdelam , 'br' as company , p.expflag , p.basenowam  ,  p.cogjjam , p.coshbam
                                , p.accrewam , p.accdelam , p.nowamflag , p.userid , p.deptid  , p.leaveflag , p.jfcom , p.manageam  ,p.salarydept
                            from `beiruanoa`.salary_pay p 
                            where 
                                p.leaveflag='0'
                                $sqlpow
                                $sqlSch 
                            ) )
                        p
                        left join salary s on ( p.userid=s.userid )
                        left join hrms h on( h.user_id=s.userid )
                        left join user u on (u.user_id=p.userid)
                        left join department d on (p.deptid=d.dept_id)
                    where p.userid=s.userid 
                        and p.leaveflag='0'
                        $sqlpow
                        $sqlSch  
                    order by  d.pdeptname , d.dept_name , s.username  ";
            $query=$this->db->getArray($sql);//在职数据
            $i=1;
            $data=array();
            foreach($query as $row){
                if(empty($deptarr[$row['pdeptname']][$row['deptname']])){//
                    $tmpda=array( $row['pdeptname']=>array( $row['deptname']=>$row['deptname'] ) ) ;
                    array_unshift($deptarr,$tmpda);
                }
                if(empty($data[$row['deptname']])){
                    $data[$row['deptname']]=$datacells;
                }
                $jb=$this->salaryClass->decryptDeal($row['basenowam']);
                if(empty($jb)||round($jb)==0||$jb==0){
                    $jb=$this->salaryClass->decryptDeal($row['baseam']);
                }
                //基本工资=基本工资+税前补发工资+税后补发工资
                $jb=round($jb+$this->salaryClass->decryptDeal($row['sperewam'])+$this->salaryClass->decryptDeal($row['accrewam']),2);
                $xm=round($this->salaryClass->decryptDeal($row['proam'])
                        +$this->salaryClass->decryptDeal($row['bonusam'])+$this->salaryClass->decryptDeal($row['floatam']),2);
                //补贴：节假日补贴（旧餐补） 福利：福利补贴（旧其他补贴）
                $bt=round($this->salaryClass->decryptDeal($row['sdyam']),2);
                $fl=round($this->salaryClass->decryptDeal($row['otheram']),2);
                
                $kc=round($this->salaryClass->decryptDeal($row['holsdelam'])+$this->salaryClass->decryptDeal($row['othdelam'])
                        +$this->salaryClass->decryptDeal($row['spedelam'])+$this->salaryClass->decryptDeal($row['accdelam']),2);
                $yf=round($jb+$xm+$bt+$fl-$kc,2);
                $djgs=$this->salaryClass->decryptDeal($row['paycesse']);
                $djshb=$this->salaryClass->decryptDeal($row['shbam']);
                $djgjj=$this->salaryClass->decryptDeal($row['gjjam']);
                $dj=round($djgs+$djshb+$djgjj,2);
                $sf=round($this->salaryClass->decryptDeal($row['paytotal']),2);
                $gsshb=$this->salaryClass->decryptDeal($row['coshbam']);
                $gsgjj=$this->salaryClass->decryptDeal($row['cogjjam']);
                $glam=$this->salaryClass->decryptDeal($row['manageam']);
                
                if($ty=='hr'&&empty($func_limit['人事查看管理层'])){//人事权利控制
                    $jb=$yf=$djgs=$sf=$dj=0;
                }
                
                if($row['expflag']=='1'){
                    $data[$row['deptname']]['jbep']+=$jb;
                    $data[$row['deptname']]['xmep']+=$xm;
                    $data[$row['deptname']]['btep']+=$bt;
                    $data[$row['deptname']]['flep']+=$fl;
                    $data[$row['deptname']]['kcep']+=$kc;
                    $data[$row['deptname']]['yfep']+=$yf;
                    $data[$row['deptname']]['djgsep']+=$djgs;
                    $data[$row['deptname']]['djshbep']+=$djshb;
                    $data[$row['deptname']]['djgjjep']+=$djgjj;
                    $data[$row['deptname']]['sfep']+=$sf;
                    $data[$row['deptname']]['gsshbep']+=$gsshb;
                    $data[$row['deptname']]['gsgjjep']+=$gsgjj;
                    $data[$row['deptname']]['glep']+=$glam;
                    $data[$row['deptname']]['rsep']+=1;
                }else{
                    $data[$row['deptname']]['jb'.$row['company']]+=$jb;
                    $data[$row['deptname']]['xm'.$row['company']]+=$xm;
                    $data[$row['deptname']]['bt'.$row['company']]+=$bt;
                    $data[$row['deptname']]['fl'.$row['company']]+=$fl;
                    $data[$row['deptname']]['kc'.$row['company']]+=$kc;
                    $data[$row['deptname']]['yf'.$row['company']]+=$yf;
                    $data[$row['deptname']]['djgs'.$row['company']]+=$djgs;
                    $data[$row['deptname']]['djshb'.$row['jfcom']]+=$djshb;
                    $data[$row['deptname']]['djgjj'.$row['jfcom']]+=$djgjj;
                    $data[$row['deptname']]['sf'.$row['company']]+=$sf;
                    $data[$row['deptname']]['gsshb'.$row['jfcom']]+=$gsshb;
                    $data[$row['deptname']]['gsgjj'.$row['jfcom']]+=$gsgjj;
                    $data[$row['deptname']]['gl'.$row['company']]+=$glam;
                    $data[$row['deptname']]['rs'.$row['company']]+=1;
                }
                //总
                $data[$row['deptname']]['jb']+=$jb;
                $data[$row['deptname']]['xm']+=$xm;
                $data[$row['deptname']]['bt']+=$bt;
                $data[$row['deptname']]['fl']+=$fl;
                $data[$row['deptname']]['kc']+=$kc;
                $data[$row['deptname']]['yf']+=$yf;
                $data[$row['deptname']]['djgs']+=$djgs;
                $data[$row['deptname']]['djshb']+=$djshb;
                $data[$row['deptname']]['djgjj']+=$djgjj;
                $data[$row['deptname']]['sf']+=$sf;
                $data[$row['deptname']]['gsshb']+=$gsshb;
                $data[$row['deptname']]['gsgjj']+=$gsgjj;
                $data[$row['deptname']]['gl']+=$glam;
                $data[$row['deptname']]['rs']+=1;
            }
            //设置表头及样式 设置
            $i = 4;
            if(!empty($deptarr)){
                $row = $i;
                $n=0;
                $p=0;
                $tol=array();
                $tolnbx=array();
                foreach($deptarr as $key=>$val){
                    $p=($n+$row);
                    $showtol=false;
                    foreach($val as $vkey=>$vval ){
                        if(empty($data[$vval])){//过滤无工资信息部门
                            continue;
                        }
                        $showtol=true;
                        //读取数据
                        $tol[]=$row + $n;
                        if($key!='贝讯专区'){//统计不含贝讯专区
                            $tolnbx[]=$row + $n;
                        }
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row + $n, iconv ( "gb2312", "utf-8", $key ) );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row + $n, iconv ( "gb2312", "utf-8", $vval ) );
                        $m = 2;
                        
                        foreach ( $data[$vval] as $field => $value ) {
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $row + $n, $value  );
                            $m ++;
                        }
                        $n ++;
                    }
                    if(count($val)>1&&$showtol){
                        $objPhpExcelFile->getActiveSheet()->getStyle( 'B'.($row + $n).':BT'.($row + $n))->getFont()->setSize(10);
                        $objPhpExcelFile->getActiveSheet()->getStyle( 'B'.($row + $n).':BT'.($row + $n))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('8FBC8F');
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row + $n, iconv ( "gb2312", "utf-8", $key ) );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row + $n, iconv ( "gb2312", "utf-8", '小计' ) );
                        $m = 2;
                        foreach ( $datacells as $field => $value ) {
                            $str='';
                            for($si=$p;$si<$n+$row;$si++){
                                $str.=$gl->numToCell($m).$si.',';
                            }
                            $objPhpExcelFile->getActiveSheet ()
                                    ->setCellValueByColumnAndRow ( $m, $row + $n, '=SUM('.trim($str,',').')' );
                            $m ++;
                        }
                        $n ++;
                        $objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $p . ':' . 'A' . ($n+$row-1) );
                    }
                }
                //不含贝讯
                $objPhpExcelFile->getActiveSheet()->getStyle( 'A'.($row + $n).':BT'.($row + $n))->getFont()->setSize(10);
                $objPhpExcelFile->getActiveSheet()->getStyle( 'A'.($row + $n).':BT'.($row + $n))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row + $n, iconv ( "gb2312", "utf-8", '合计（不含贝讯专区）' ) );
                $objPhpExcelFile->getActiveSheet ()->mergeCells ('A' . ($n+$row).':'.'B' . ($n+$row) );
                $m = 2;
                foreach ( $datacells as $field => $value ) {
                    $str='';
                    foreach($tolnbx as $val){
                        $str.=$gl->numToCell($m).$val.',';
                    }
                    $objPhpExcelFile->getActiveSheet ()
                            ->setCellValueByColumnAndRow ( $m, $row + $n, '=SUM('.trim($str,',').')' );
                    $m ++;
                }
                $n ++;
                //含贝讯
                $objPhpExcelFile->getActiveSheet()->getStyle( 'A'.($row + $n).':BT'.($row + $n))->getFont()->setSize(10);
                $objPhpExcelFile->getActiveSheet()->getStyle( 'A'.($row + $n).':BT'.($row + $n))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('8FBC8F');
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row + $n, iconv ( "gb2312", "utf-8", '合计（含贝讯专区）' ) );
                $objPhpExcelFile->getActiveSheet ()->mergeCells ('A' . ($n+$row).':'.'B' . ($n+$row) );
                $m = 2;
                foreach ( $datacells as $field => $value ) {
                    $str='';
                    foreach($tol as $val){
                        $str.=$gl->numToCell($m).$val.',';
                    }
                    $objPhpExcelFile->getActiveSheet ()
                            ->setCellValueByColumnAndRow ( $m, $row + $n, '=SUM('.trim($str,',').')' );
                    $m ++;
                }
                
            }
            //第二个表离职统计表
            $objPhpExcelFile->createSheet();  
            $objPhpExcelFile->setActiveSheetIndex(1);
            $objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '离职员工工资统计' ) );
            $datacells=array(
                'jb'=>0
                ,'xm'=>0
                ,'bt'=>0
                ,'fl'=>0
                ,'kc'=>0
                ,'yf'=>0
                ,'djgs'=>0
                ,'djshb'=>0
                ,'djgjj'=>0
                ,'sf'=>0
                ,'gsshb'=>0
                ,'gsgjj'=>0
                ,'gl'=>0
                ,'rs'=>0
                ,'prs'=>0

                ,'jbdl'=>0
                ,'xmdl'=>0
                ,'btdl'=>0
                ,'fldl'=>0
                ,'kcdl'=>0
                ,'yfdl'=>0
                ,'djgsdl'=>0
                ,'djshbdl'=>0
                ,'djgjjdl'=>0
                ,'sfdl'=>0
                ,'gsshbdl'=>0
                ,'gsgjjdl'=>0
                ,'gldl'=>0
                ,'rsdl'=>0
                ,'prsdl'=>0

                ,'jbbr'=>0
                ,'xmbr'=>0
                ,'btbr'=>0
                ,'flbr'=>0
                ,'kcbr'=>0
                ,'yfbr'=>0
                ,'djgsbr'=>0
                ,'djshbbr'=>0
                ,'djgjjbr'=>0
                ,'sfbr'=>0
                ,'gsshbbr'=>0
                ,'gsgjjbr'=>0
                ,'glbr'=>0
                ,'rsbr'=>0
                ,'prsbr'=>0

                ,'jbsy'=>0
                ,'xmsy'=>0
                ,'btsy'=>0
                ,'flsy'=>0
                ,'kcsy'=>0
                ,'yfsy'=>0
                ,'djgssy'=>0
                ,'djshbsy'=>0
                ,'djgjjsy'=>0
                ,'sfsy'=>0
                ,'gsshbsy'=>0
                ,'gsgjjsy'=>0
                ,'glsy'=>0
                ,'rssy'=>0
                ,'prssy'=>0

                ,'jbep'=>0
                ,'xmep'=>0
                ,'btep'=>0
                ,'flep'=>0
                ,'kcep'=>0
                ,'yfep'=>0
                ,'djgsep'=>0
                ,'djshbep'=>0
                ,'djgjjep'=>0
                ,'sfep'=>0
                ,'gsshbep'=>0
                ,'gsgjjep'=>0
                ,'glep'=>0
                ,'rsep'=>0
                ,'prsep'=>0
            );
            //离职数据 本月离职和离职支付日期在本月数据
            $sqlSch=" and ( ( p.pyear='".$sy."' and p.pmon='".$sm."' and p.nowamflag=3 ) 
                or ( s.lpy='".$sy."' and s.lpm='".$sm."' and  year(s.leavedt)=p.pyear and month(s.leavedt)=p.pmon and p.nowamflag=3 ) ) 
                      and ( p.salarydept not in ('其他') or p.userid='bin.chang' or p.userid='2903'  )  ";
            $sql = "select
                        s.rand_key , u.user_name as username , d.dept_name as deptname , s.olddept , s.comedt , s.oldarea
                        , s.amount ,  p.gjjam , p.shbam
                        , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                        , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                        , h.userlevel , p.pyear , p.pmon
                        , p.sdyam , p.holsdelam  , p.expflag , d.pdeptname , p.basenowam  ,  p.cogjjam , p.coshbam
                        , p.accrewam , p.accdelam , p.nowamflag , s.lpy , s.lpm , year(s.leavedt) as ldy , month(s.leavedt) as ldm  
                        , p.company , p.jfcom , p.manageam 
                    from
                        ((
                            select  
                                p.gjjam , p.shbam
                                , p.remark , p.baseam , p.floatam , p.proam , p.otheram
                                , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                                , p.pyear , p.pmon
                                , p.sdyam , p.holsdelam , 'dl' as company , p.expflag , p.basenowam  ,  p.cogjjam , p.coshbam
                                , p.accrewam , p.accdelam , p.nowamflag , p.userid , p.deptid  , p.leaveflag , p.jfcom , p.manageam  ,p.salarydept
                            from salary_pay p 
                                left join salary s on ( p.userid=s.userid )
                            where 
                                p.leaveflag='0'
                                $sqlpow
                                $sqlSch    
                            )union(
                            select  
                                p.gjjam , p.shbam
                                , p.remark , p.baseam , p.floatam , p.proam , p.otheram
                                , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                                , p.pyear , p.pmon
                                , p.sdyam , p.holsdelam , 'sy' as company , p.expflag , p.basenowam  ,  p.cogjjam , p.coshbam
                                , p.accrewam , p.accdelam , p.nowamflag , p.userid , p.deptid  , p.leaveflag , p.jfcom , p.manageam  ,p.salarydept
                            from `shiyuanoa`.salary_pay p 
                                left join salary s on ( p.userid=s.userid )
                            where 
                                p.leaveflag='0'
                                $sqlpow
                                $sqlSch    
                            )union(
                            select  
                                p.gjjam , p.shbam
                                , p.remark , p.baseam , p.floatam , p.proam , p.otheram
                                , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                                , p.pyear , p.pmon
                                , p.sdyam , p.holsdelam , 'br' as company , p.expflag , p.basenowam  ,  p.cogjjam , p.coshbam
                                , p.accrewam , p.accdelam , p.nowamflag , p.userid , p.deptid  , p.leaveflag , p.jfcom , p.manageam  ,p.salarydept
                            from `beiruanoa`.salary_pay p 
                                left join salary s on ( p.userid=s.userid )
                            where 
                                p.leaveflag='0'
                                $sqlpow
                                $sqlSch
                            ) )
                        p
                        left join salary s on ( p.userid=s.userid )
                        left join hrms h on( h.user_id=s.userid )
                        left join user u on (u.user_id=p.userid)
                        left join department d on (p.deptid=d.dept_id)
                    where p.userid=s.userid 
                        and 
                        p.leaveflag='0'
                        $sqlpow
                        $sqlSch  
                    order by  d.pdeptname , d.dept_name , s.username ";
           // echo $sql;
                $querylz=$this->db->getArray($sql);//离职数据
                $data=array();
                foreach($querylz as $row){
                    if(empty($deptarr[$row['pdeptname']][$row['deptname']])){//
                        $tmpda=array( $row['pdeptname']=>array( $row['deptname']=>$row['deptname'] ) ) ;
                        array_unshift($deptarr,$tmpda);
                    }
                    if(empty($data[$row['deptname']])){
                        $data[$row['deptname']]=$datacells;
                    }
                    //人数统计
                    $jb=$xm=$fl=$kc=$yf=$djgs=$djshb=$djgjj=$sf=$gsshb=$gsgjj=$gsgjj=$rs=$prs=$dj=$bt=$glam=0;
                    if($row['lpy']==$sy && $row['lpm']==$sm ){//支付日期在选择月份计算成本
                        $jb=$this->salaryClass->decryptDeal($row['basenowam']);
                        if(empty($jb)||round($jb)==0||$jb==0){
                            $jb=$this->salaryClass->decryptDeal($row['baseam']);
                        }
                        //基本工资=基本工资+各项补贴+离职福利
                        $jb=round($jb+$this->salaryClass->decryptDeal($row['sperewam'])+$this->salaryClass->decryptDeal($row['accrewam']),2);
                        if($row['lpy']==2013 && $row['lpm']==12 && ( $row['username']=='薛飞' || $row['username']=='阮民'  ) ){
                        	$jb=round($this->salaryClass->decryptDeal($row['accrewam']),2);
                        }
                        $xm=round($this->salaryClass->decryptDeal($row['proam'])
                                +$this->salaryClass->decryptDeal($row['bonusam'])+$this->salaryClass->decryptDeal($row['floatam']),2);
                        //补贴：节假日补贴（旧餐补） 福利：福利补贴（旧其他补贴）
                        $bt=round($this->salaryClass->decryptDeal($row['sdyam']),2);
                        $fl=round($this->salaryClass->decryptDeal($row['otheram']),2);  

                        $kc=round($this->salaryClass->decryptDeal($row['holsdelam'])+$this->salaryClass->decryptDeal($row['othdelam'])
                                +$this->salaryClass->decryptDeal($row['spedelam'])+$this->salaryClass->decryptDeal($row['accdelam']),2);
                        $yf=round($jb+$xm+$bt+$fl-$kc,2);
                        $djgs=$this->salaryClass->decryptDeal($row['paycesse']); 
                        $sf=round($this->salaryClass->decryptDeal($row['paytotal']),2);
                        $prs=1;
                    }
                    if($row['ldy']==$sy && $row['ldm']==$sm ){//当月离职
                        $djshb=$this->salaryClass->decryptDeal($row['shbam']);
                        $djgjj=$this->salaryClass->decryptDeal($row['gjjam']);
                        
                        $gsshb=$this->salaryClass->decryptDeal($row['coshbam']);
                        $gsgjj=$this->salaryClass->decryptDeal($row['cogjjam']);
                        $glam=$this->salaryClass->decryptDeal($row['manageam']);
                        $rs=1;
                    }
                    $dj=round($djgs+$djshb+$djgjj,2);
                    if($ty=='hr'&&empty($func_limit['人事查看管理层'])){//人事权利
                        $jb=$xm=$yf=$djgs=$sf=$dj=$bt=0;
                    }
                    if($row['expflag']=='1'){
                        $data[$row['deptname']]['jbep']+=$jb;
                        $data[$row['deptname']]['xmep']+=$xm;
                        $data[$row['deptname']]['btep']+=$bt;
                        $data[$row['deptname']]['flep']+=$fl;
                        $data[$row['deptname']]['kcep']+=$kc;
                        $data[$row['deptname']]['yfep']+=$yf;
                        $data[$row['deptname']]['djgsep']+=$djgs;
                        $data[$row['deptname']]['djshbep']+=$djshb;
                        $data[$row['deptname']]['djgjjep']+=$djgjj;
                        $data[$row['deptname']]['sfep']+=$sf;
                        $data[$row['deptname']]['gsshbep']+=$gsshb;
                        $data[$row['deptname']]['gsgjjep']+=$gsgjj;
                        $data[$row['deptname']]['glep']+=$glam;
                        $data[$row['deptname']]['rsep']+=$rs;
                        $data[$row['deptname']]['prsep']+=$prs;
                    }else{
                        $data[$row['deptname']]['jb'.$row['company']]+=$jb;
                        $data[$row['deptname']]['xm'.$row['company']]+=$xm;
                        $data[$row['deptname']]['bt'.$row['company']]+=$bt;
                        $data[$row['deptname']]['fl'.$row['company']]+=$fl;
                        $data[$row['deptname']]['kc'.$row['company']]+=$kc;
                        $data[$row['deptname']]['yf'.$row['company']]+=$yf;
                        $data[$row['deptname']]['djgs'.$row['company']]+=$djgs;
                        $data[$row['deptname']]['djshb'.$row['jfcom']]+=$djshb;
                        $data[$row['deptname']]['djgjj'.$row['jfcom']]+=$djgjj;
                        $data[$row['deptname']]['sf'.$row['company']]+=$sf;
                        $data[$row['deptname']]['gsshb'.$row['jfcom']]+=$gsshb;
                        $data[$row['deptname']]['gsgjj'.$row['jfcom']]+=$gsgjj;
                        $data[$row['deptname']]['gl'.$row['jfcom']]+=$glam;
                        $data[$row['deptname']]['rs'.$row['company']]+=$rs;
                        $data[$row['deptname']]['prs'.$row['company']]+=$prs;
                    }
                    //总
                    $data[$row['deptname']]['jb']+=$jb;
                    $data[$row['deptname']]['xm']+=$xm;
                    $data[$row['deptname']]['bt']+=$bt;
                    $data[$row['deptname']]['fl']+=$fl;
                    $data[$row['deptname']]['kc']+=$kc;
                    $data[$row['deptname']]['yf']+=$yf;
                    $data[$row['deptname']]['djgs']+=$djgs;
                    $data[$row['deptname']]['djshb']+=$djshb;
                    $data[$row['deptname']]['djgjj']+=$djgjj;
                    $data[$row['deptname']]['sf']+=$sf;
                    $data[$row['deptname']]['gsshb']+=$gsshb;
                    $data[$row['deptname']]['gsgjj']+=$gsgjj;
                    $data[$row['deptname']]['gl']+=$glam;
                    $data[$row['deptname']]['rs']+=$rs;
                    $data[$row['deptname']]['prs']+=$prs;
                }
                //设置表头及样式 设置
                $i = 4;
                if(!empty($deptarr)){
                    $row = $i;
                    $n=0;
                    $p=0;
                    $tol=array();
                    $tolnbx=array();
                    foreach($deptarr as $key=>$val){
                        $p=($n+$row);
                        $showtol=false;
                        foreach($val as $vkey=>$vval ){
                            if(empty($data[$vval])){
                                continue;
                            }
                            $showtol=true;
                            //读取数据
                            $tol[]=$row + $n;
                            if($key!='贝讯专区'){//统计不含贝讯专区
                                $tolnbx[]=$row + $n;
                            }
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row + $n, iconv ( "gb2312", "utf-8", $key ) );
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row + $n, iconv ( "gb2312", "utf-8", $vval ) );
                            $m = 2;

                            foreach ( $data[$vval] as $field => $value ) {
                                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $row + $n, $value  );
                                $m ++;
                            }
                            $n ++;
                        }
                        if(count($val)>1&&$showtol){
                            $objPhpExcelFile->getActiveSheet()->getStyle( 'B'.($row + $n).':BT'.($row + $n))->getFont()->setSize(10);
                            $objPhpExcelFile->getActiveSheet()->getStyle( 'B'.($row + $n).':BT'.($row + $n))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('8FBC8F');
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row + $n, iconv ( "gb2312", "utf-8", $key ) );
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row + $n, iconv ( "gb2312", "utf-8", '小计' ) );
                            $m = 2;
                            foreach ( $datacells as $field => $value ) {
                                $str='';
                                for($si=$p;$si<$n+$row;$si++){
                                    $str.=$gl->numToCell($m).$si.',';
                                }
                                $objPhpExcelFile->getActiveSheet ()
                                        ->setCellValueByColumnAndRow ( $m, $row + $n, '=SUM('.trim($str,',').')' );
                                $m ++;
                            }
                            $n ++;
                            $objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $p . ':' . 'A' . ($n+$row-1) );
                        }
                    }
                    //不含贝讯
                    $objPhpExcelFile->getActiveSheet()->getStyle( 'A'.($row + $n).':BT'.($row + $n))->getFont()->setSize(10);
                    $objPhpExcelFile->getActiveSheet()->getStyle( 'A'.($row + $n).':BT'.($row + $n))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);
                    $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row + $n, iconv ( "gb2312", "utf-8", '合计（不含贝讯专区）' ) );
                    $objPhpExcelFile->getActiveSheet ()->mergeCells ('A' . ($n+$row).':'.'B' . ($n+$row) );
                    $m = 2;
                    foreach ( $datacells as $field => $value ) {
                        $str='';
                        foreach($tolnbx as $val){
                            if(!empty($val))
                                $str.=$gl->numToCell($m).$val.',';
                        }
                        $objPhpExcelFile->getActiveSheet ()
                                ->setCellValueByColumnAndRow ( $m, $row + $n, '=SUM('.trim($str,',').')' );
                        $m ++;
                    }
                    $n ++;
                    //含贝讯
                    $objPhpExcelFile->getActiveSheet()->getStyle( 'A'.($row + $n).':BT'.($row + $n))->getFont()->setSize(10);
                    $objPhpExcelFile->getActiveSheet()->getStyle( 'A'.($row + $n).':BT'.($row + $n))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('8FBC8F');
                    $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row + $n, iconv ( "gb2312", "utf-8", '合计（含贝讯专区）' ) );
                    $objPhpExcelFile->getActiveSheet ()->mergeCells ('A' . ($n+$row).':'.'B' . ($n+$row) );
                    $m = 2;
                    foreach ( $datacells as $field => $value ) {
                        $str='';
                        foreach($tol as $val){
                            if(!empty($val))
                                $str.=$gl->numToCell($m).$val.',';
                        }
                        $objPhpExcelFile->getActiveSheet ()
                                ->setCellValueByColumnAndRow ( $m, $row + $n, '=SUM('.trim($str,',').')' );
                        $m ++;
                    }

                }
                $objPhpExcelFile->setActiveSheetIndex(0);
//            print_r($deptarr);
        }elseif($flag=='lin'){//人事离职信息
            $bi=3;
            $dataarr=array();
            $dataarr=$this->model_hr_leave_manager_list('xls');
            $setString=array(0//员工号
                ,'AD'//账号
                ,'AF'
                );
            //print_r($dataarr);
        }elseif($flag=='ext'){//额外人信息
            $bi=2;
            $dataarr=array();
            $dataarr=$this->model_hr_user_ext('xls');
            $setString=array('U','S','T');
            //print_r($dataarr);
        }elseif($flag=='dp_lin'){//人事离职信息
            global $func_limit;
            $sqlflag='';
            $dppow=$this->model_dp_pow();
            if(!empty($func_limit['浏览部门'])){
                $sqlflag=" and ( s.deptid in ('". implode("','", $dppow['1'])."','" . implode("','", $dppow['2']) ."')
                    or s.userid='".$_SESSION['USER_ID']."'
                    or s.deptid in ( ".trim($func_limit['浏览部门'],',')." )
                    ) ";
            }else{
                $sqlflag=" and ( s.deptid in ('". implode("','", $dppow['1'])."','" . implode("','", $dppow['2']) ."')
                    or s.userid='".$_SESSION['USER_ID']."'
                    ) ";
            }
            $bi=3;
            $dataarr=array();
            $dataarr=$this->model_hr_leave_manager_list('xls',$sqlflag);
            $setString=array(0//员工号
                ,26//账号
                );
            //print_r($dataarr);
        }elseif($flag=='hr_sdy'){//人事补贴信息
            global $func_limit;
            $bi=2;
            $dataarr=array();
            $sy=$_REQUEST['sy'];
            $sm=$_REQUEST['sm'];
            if(!empty($sy)){
                $sqlstr.=" and s.pyear='".$sy."' ";
            }
            if($sm!='-'){
                $sqlstr.=" and s.pmon='".$sm."' ";
            }
            $tflag=array('0'=>'总监录入','1'=>'人事录入','2'=>'人事录入');
            $sql="select
                 s.pyear , s.pmon , h.usercard , u.user_name as username , u.company
                 , d.dept_name as dn , dt.dept_name as dtn
                 , s.sdymeal , s.sdyother 
            	 , s.remark 
                 , s.flaflag
            	 , f.sta as fsta , s.userid
            from salary_sdy s
                left join user u on (s.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
                left join department dt on (dt.depart_x=left(d.depart_x,2))
                left join hrms h on (u.user_id=h.user_id)
                left join salary_flow f on ( s.rand_key=f.salarykey )
            where
                s.userid=u.user_id 
                $sqlstr
            order by s.pyear , s.pmon
            ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $dataarr[]=un_iconv(array(
                    'sy'=>$row['pyear']
                    ,'sm'=>$row['pmon']
                    ,'uc'=>$row['usercard']
                    ,'un'=>$row['username']
                    ,'com'=>$this->salaryCom[$row['company']]
                    ,'dtn'=>$row['dtn']
                    ,'dn'=>$row['dn']
                    ,'odtn'=>$row['userid']//临时借用
                    ,'odn'=>$row['company']//临时借用
                    ,'sdymeal'=>$this->salaryClass->decryptDeal($row['sdymeal'])
                    ,'sdyother'=>$this->salaryClass->decryptDeal($row['sdyother'])
                    ,'remark'=>$row['remark']
                    ,'flag'=>$tflag[$row['flaflag']]
                    ,'sta'=>($row['flaflag']=='0'?'完成':$this->flowSta[$row['fsta']])
                ));
            }
            if(!empty($dataarr)){
                foreach($dataarr as $key=>$val){
                    $comtable=$this->get_com_sql($val['odn']);
                    $sql="select  d.dept_name as odn , dt.dept_name as odtn  
                            from ".$comtable."salary_pay p 
                            left join department d on (p.deptid=d.dept_id)
                            left join department dt on (dt.depart_x=left(d.depart_x,2))
                          where p.userid='".$val['odtn']."' 
                              and p.pyear='".$val['sy']."' and p.pmon='".$val['sm']."'
                        ";
                    $res=un_iconv($this->db->get_one($sql));
                    $dataarr[$key]['odtn']=$res['odtn'];
                    $dataarr[$key]['odn']=$res['odn'];
                }
            }
            
            $setString=array(2//员工号
                );
            //print_r($dataarr);
        }elseif($flag=='hr_div'){//人事专区数据导出
            global $func_limit;
            $bi=2;
            $dataarr=array(); 
            $dataarr=$this->model_hr_user_div('xls');
            $setString=array(0//员工号
                );
            //print_r($dataarr);
        }elseif($flag=='dp_detail'){
            $bi=2;
            $dataarr=array(); 
            $dataarr=$this->model_dp_user('xls');
            $setString=array(0//员工号
                );
        }elseif($flag=='hr_detail'){
            $bi=2;
            $dataarr=array(); 
            $dataarr=$this->model_hr_user(true,'',false,false,'xls',$flag);
            $setString=array(0//员工号
                ,'A','AI','AK'
                );
        }elseif($flag=='hr_jf'){
            $bi=2;
            $dataarr=array(); 
            $dataarr=$this->model_hr_pay_list('xls');
            $setString=array('A','B');
        }elseif($flag=='fn_pro'){
            $bi=2;
            $syear=$_REQUEST['sy'];
            $smon=$_REQUEST['sm'];
            $sdtb=date('Y-m-d',  strtotime($syear.'-'.$smon.'-1'));
            $sdte=date('Y-m-t',  strtotime($sdtb));
            if(false){//重新更新本月数据 true
                $sql="delete i  FROM salary_type_info  i 
                    left join salary_user_type t on (t.id=i.typeid )
                    where  pyear='".$syear."' and pmon='".$smon."'  and t.id=i.typeid  ";
                $this->db->query($sql);
                $sql="delete t  FROM  salary_user_type t where  pyear='".$syear."' and pmon='".$smon."'  ";
                $this->db->query($sql);
            }
            $sql="select count(*) as am
                from salary_user_type where pyear='".$syear."' and pmon='".$smon."'  ";
            $res = $this->db->get_one($sql);
            if( (empty($res['am'])&&( ($syear>2012) || ($syear==$this->nowy && $smon<$this->nowm) || $syear<$this->nowy ) )){
                //当月生效项目信息
                $sql="insert into  salary_user_type (name ,type , members , membersn, pyear , pmon , rand_key , pid )
                        select  name ,'pro' , developer , '' as membersn , '".$syear."' ,  '".$smon."'  , rand() , id
                        from project_rd where is_delete=0 and status in (0)  ";
                $this->db->query($sql);
                /**
                 *if(value== 0){
                        return '进行中';
                }else if(value == 1){
                        return '未开始';
                }else if(value == 2){
                        return '已完成';
                }else if(value == 3){
                        return '已取消';
                }else if(value == 4){
                        return '未审核';
                }else if(value == 5){
                        return '已审核';
                }else if(value == 6){
                        return '已关闭';
                } 
                 */
                $esm=array();
                $esmarr=array();
                $esmw=array();
                $sql="select
                            c.createId,c.createName,c.deptName,c.projectCode,c.projectId,c.projectName,round(sum(c.inWorkRate/100),2) as inWorkRate,sum(c.costMoney) as costMoney,
                            sum(c.thisProjectProcess) as thisProjectProcess,round(sum(c.inWorkRate/100),2) as inWorkRateOne,
                            sum(c.processCoefficient) as processCoefficient,sum(c.workCoefficient) as workCoefficient
                    from
                            oa_esm_worklog c
                            left join department d on (d.dept_id = c.deptid )
                    where 1 and c.executionDate >= '".$sdtb."' and c.executionDate <= '".$sdte."'
                            and c.confirmStatus = '1' and c.workStatus = 'GXRYZT-01'
                            and d.depart_x like '24%'
                    group by c.createId,c.projectId ";
                $query=$this->db->query($sql);
                while($row=$this->db->fetch_array($query)){
                    $esm[$row['projectCode']]['name']=$row['projectName'];
                    $esmarr[$row['projectCode']][$row['createId']]['work']=$row['inWorkRate'];
                }
                if(!empty($esm)){
                    foreach($esm as $key=>$val){
                        $sql="insert into  salary_user_type (name ,type , members , membersn, pyear , pmon , rand_key , pid )
                            values ( '".$val['name'].'-'.$key."' , 'esm' , '', '', '".$syear."' ,  '".$smon."'  , rand() , '')";
                        $this->db->query($sql);
                        $esm[$key]['id']=$this->db->insert_id();
                    }
                }
                if(!empty($esmarr)){
                    foreach($esmarr as $key=>$val){
                        foreach($val as $vkey=>$vval){
                            $tmp=round($vval['work']/date('t',  strtotime($syear.'-'.$smon.'-01'))*100,2);
                            $sql="insert into  salary_type_info (userid ,typeid , percent , addtime , leavetime )
                                values ( '".$vkey."' , '".$esm[$key]['id']."' , '".$tmp."' , null , null  ) ";
                            $this->db->query($sql);
                            $esmw[$vkey]['esm']=empty($esmw[$vkey]['esm'])?$tmp:$esmw[$vkey]['esm']+$tmp;
                        }
                    }
                }
//                print_r($esmw);
//                die();
                unset($esm);
                //当月生效研发部门信息
                $sql="insert into  salary_user_type (name ,type , members , membersn, pyear , pmon , rand_key , pid )
                        SELECT d.dept_name , 'dept' , '' as developer  , '' as membersn , '".$syear."' ,  '".$smon."'  , rand() , d.dept_id
                        FROM  department d  where d.depart_x like '24%' and d.delflag=0 order by d.depart_x ";
                $this->db->query($sql);
                $esmarr=array();
                //生成项目成员占比数据--过滤其他部门人员
               $sql="SELECT a.account , t.id , a.percent , a.addtime , a.leavetime  FROM project_rd_action a 
                        left join user u on (u.user_id=a.account)
                        left join department d on (u.dept_id=d.dept_id)
                        left join salary_user_type t on (a.project = t.pid and t.type='pro')
                        where t.pyear='".$syear."' and t.pmon='".$smon."' 
                        and 
( 
(a.addtime='0000-00-00 00:00:00' and  a.leavetime='0000-00-00 00:00:00' ) 
or ( to_days(a.addtime)<to_days('".$sdte."')  and ( a.leavetime='0000-00-00 00:00:00'  or  to_days(a.leavetime)>to_days('".$sdtb."')  )  ) 
or (  to_days(a.leavetime)>to_days('".$sdtb."') and ( a.addtime='0000-00-00 00:00:00'  or  to_days(a.addtime)<to_days('".$sdte."')  ) )
)
                        and a.percent>0 and d.depart_x like '24%'
                        order by a.id ";
                $query=$this->db->query($sql);
                while($row=$this->db->fetch_array($query)){
                    if(!empty($esmw[$row['account']]['esm'])){
                        $esmarr[$row['account']][$row['id']]['w']=round($row['percent']*(100-$esmw[$row['account']]['esm'])/100);
                    }else{
                        $esmarr[$row['account']][$row['id']]['w']=$row['percent'];
                    }
                    $esmarr[$row['account']][$row['id']]['b']=$row['addtime'];
                    $esmarr[$row['account']][$row['id']]['e']=$row['leavetime'];
                }
                if(!empty($esmarr)){
                    foreach($esmarr as $key=>$val){
                        foreach($val as $vkey=>$vval){
                            $sql="insert into  salary_type_info (userid ,typeid , percent , addtime , leavetime )
                                values ( '".$key."' , '".$vkey."' , '".$vval['w']."' , '".$vval['b']."' , '".$vval['e']."'  ) ";
                            $this->db->query($sql);
                        }
                    }
                }
//               $sql="insert into  salary_type_info (userid ,typeid , percent , addtime , leavetime )
//                        SELECT a.account , t.id , a.percent , a.addtime , a.leavetime  FROM project_rd_action a 
//                        left join user u on (u.user_id=a.account)
//                        left join department d on (u.dept_id=d.dept_id)
//                        left join salary_user_type t on (a.project = t.pid and t.type='pro')
//                        where t.pyear='".$syear."' and t.pmon='".$smon."' 
//                        and 
//( 
//(a.addtime='0000-00-00 00:00:00' and  a.leavetime='0000-00-00 00:00:00' ) 
//or ( to_days(a.addtime)<to_days('".$sdte."')  and ( a.leavetime='0000-00-00 00:00:00'  or  to_days(a.leavetime)>to_days('".$sdtb."')  )  ) 
//or (  to_days(a.leavetime)>to_days('".$sdtb."') and ( a.addtime='0000-00-00 00:00:00'  or  to_days(a.addtime)<to_days('".$sdte."')  ) )
//)
//                        and a.percent>0 and d.depart_x like '24%'
//                        order by a.id ";
//                $this->db->query($sql);
                //获取本月部门人员工资数据
                $sql="insert into  salary_type_info (userid ,typeid , percent , addtime , leavetime )
                    select p.userid , t.id , if(i.percent is null , 100 ,  100-i.percent ) as percent  , '".$sdtb."' , '".$sdte."'
                    from  (
                    ( SELECT p.userid , p.deptid   FROM salary_pay p 
                    left join department d on (p.deptid=d.dept_id )
                    where 
                    d.depart_x like '24%' and d.delflag=0
                    and p.leaveflag=0 and ( p.nowamflag!=3 or p.nowamflag is null ) 
                    and p.pyear='".$syear."' and p.pmon='".$smon."'
                    )union( 
                    SELECT  p.userid , p.deptid  FROM `shiyuanoa`.salary_pay p 
                    left join department d on (p.deptid=d.dept_id )
                    where 
                    d.depart_x like '24%' and d.delflag=0
                    and p.leaveflag=0 and ( p.nowamflag!=3 or p.nowamflag is null ) 
                    and p.pyear='".$syear."' and p.pmon='".$smon."'
                    )union( 
                    SELECT  p.userid , p.deptid  FROM `beiruanoa`.salary_pay p 
                    left join department d on (p.deptid=d.dept_id )
                    where 
                    d.depart_x like '24%' and d.delflag=0
                    and p.leaveflag=0 and ( p.nowamflag!=3 or p.nowamflag is null ) 
                    and p.pyear='".$syear."' and p.pmon='".$smon."' 
                    )  ) p
                    left join salary_user_type t on (p.deptid = t.pid and t.type='dept' )
                    left join ( 
                            select i.userid , sum(i.percent) as percent   from salary_type_info i 
                            left join salary_user_type t on ( t.id=i.typeid  )
                            where t.pyear='".$syear."' and t.pmon='".$smon."' group by i.userid 
                    ) i  on ( i.userid = p.userid  )
                    where t.pyear='".$syear."' and t.pmon='".$smon."' and p.deptid = t.pid and t.type='dept' ";
                $this->db->query($sql);
                //计算金额
                $proArr=array();
                $sql="(SELECT t.id ,  t.name , t.pyear , t.pmon , p.userid ,i.percent as percent  
                        , basenowam , baseam,sperewam,accrewam,proam,bonusam,floatam
                        ,sdyam,holsdelam,othdelam,spedelam,accdelam
                        ,coshbam,cogjjam
                        , p.totalam  , p.shbam , p.gjjam , p.paycesse , p.paytotal  ,'' as com  
                    FROM salary_user_type t 
                        left join salary_type_info i on (t.id=i.typeid)
                        left join salary_pay p on ( p.userid=i.userid and p.pyear=t.pyear and p.pmon=t.pmon )
                        where p.id is not null and t.pyear = '".$syear."' and t.pmon ='".$smon."' 
                            and p.leaveflag=0 and ( p.nowamflag!=3 or p.nowamflag is null )  )
                        union 
                        (SELECT t.id ,  t.name , t.pyear , t.pmon , p.userid ,i.percent as percent 
                        , basenowam , baseam,sperewam,accrewam,proam,bonusam,floatam
                        ,sdyam,holsdelam,othdelam,spedelam,accdelam
                        ,coshbam,cogjjam
                        , p.totalam  , p.shbam , p.gjjam , p.paycesse , p.paytotal ,'s' as com   
                    FROM salary_user_type t 
                        left join salary_type_info i on (t.id=i.typeid)
                        left join `shiyuanoa`.salary_pay p on ( p.userid=i.userid and p.pyear=t.pyear and p.pmon=t.pmon )
                        where p.id is not null and t.pyear = '".$syear."' and t.pmon ='".$smon."' 
                            and p.leaveflag=0 and ( p.nowamflag!=3 or p.nowamflag is null )  )
                        union 
                        (SELECT t.id ,  t.name , t.pyear , t.pmon , p.userid ,i.percent as percent  
                        , basenowam , baseam,sperewam,accrewam,proam,bonusam,floatam
                        ,sdyam,holsdelam,othdelam,spedelam,accdelam
                        ,coshbam,cogjjam
                        , p.totalam  , p.shbam , p.gjjam , p.paycesse , p.paytotal ,'b' as com  
                    FROM salary_user_type t 
                        left join salary_type_info i on (t.id=i.typeid)
                        left join `beiruanoa`.salary_pay p on ( p.userid=i.userid and p.pyear=t.pyear and p.pmon=t.pmon )
                        where p.id is not null and t.pyear = '".$syear."' and t.pmon ='".$smon."' 
                            and p.leaveflag=0 and ( p.nowamflag!=3 or p.nowamflag is null )  )";
                $query=$this->db->query($sql);
                while($row=$this->db->fetch_array($query)){
                    $jb=$this->salaryClass->decryptDeal($row['basenowam']);
                    if(empty($jb)||round($jb)==0||$jb==0){
                        $jb=$this->salaryClass->decryptDeal($row['baseam']);
                    }
                    //基本工资=基本工资+税前补发工资+税后补发工资
                    $jb=round($jb+$this->salaryClass->decryptDeal($row['sperewam'])+$this->salaryClass->decryptDeal($row['accrewam']),2);
                    $xm=round($this->salaryClass->decryptDeal($row['proam'])
                            +$this->salaryClass->decryptDeal($row['bonusam'])+$this->salaryClass->decryptDeal($row['floatam']),2);
                    //补贴：节假日补贴（旧餐补） 福利：福利补贴（旧其他补贴）
                    $bt=round($this->salaryClass->decryptDeal($row['sdyam']),2);
                    //扣除
                    $kc=round($this->salaryClass->decryptDeal($row['holsdelam'])+$this->salaryClass->decryptDeal($row['othdelam'])
                            +$this->salaryClass->decryptDeal($row['spedelam'])+$this->salaryClass->decryptDeal($row['accdelam']),2);
                    $yftotal=round($jb+$xm+$bt-$kc,2);//研发 = 基本工资+项目奖+节假日补贴-扣除 （其他福利不计算）
                    //公司社保公积金 20130708 显示公司社保公积金
                    $gsshb=$this->salaryClass->decryptDeal($row['coshbam']);
                    $gsgjj=$this->salaryClass->decryptDeal($row['cogjjam']);
                    
                    $proArr[$row['id']][$row['com'].'totalam']=isset($proArr[$row['id']][$row['com'].'totalam'])?round($proArr[$row['id']][$row['com'].'totalam']+($yftotal*$row['percent']/100),2):($yftotal*$row['percent']/100);
                    $proArr[$row['id']][$row['com'].'shbam']=isset($proArr[$row['id']][$row['com'].'shbam'])?round($proArr[$row['id']][$row['com'].'shbam']+($gsshb*$row['percent']/100),2):($gsshb*$row['percent']/100);
                    $proArr[$row['id']][$row['com'].'gjjam']=isset($proArr[$row['id']][$row['com'].'gjjam'])?round($proArr[$row['id']][$row['com'].'gjjam']+($gsgjj*$row['percent']/100),2):($gsgjj*$row['percent']/100);
                    $proArr[$row['id']][$row['com'].'paycesse']=isset($proArr[$row['id']][$row['com'].'paycesse'])?round($proArr[$row['id']][$row['com'].'paycesse']+($this->salaryClass->decryptDeal($row['paycesse'])*$row['percent']/100),2):($this->salaryClass->decryptDeal($row['paycesse'])*$row['percent']/100);
                    $proArr[$row['id']][$row['com'].'paytotal']=isset($proArr[$row['id']][$row['com'].'paytotal'])?round($proArr[$row['id']][$row['com'].'paytotal']+($this->salaryClass->decryptDeal($row['paytotal'])*$row['percent']/100),2):($this->salaryClass->decryptDeal($row['paytotal'])*$row['percent']/100);
                }
                if(!empty($proArr)){
                    foreach ($proArr as $key=>$val) {
                        $sql="update salary_user_type set 
                                totalam = '".$val['totalam']."'  , shbam = '".$val['shbam']."'
                                , gjjam = '".$val['gjjam']."', paycesse = '".$val['paycesse']."'
                                , paytotal = '".$val['paytotal']."'

                                ,stotalam = '".$val['stotalam']."'  , sshbam = '".$val['sshbam']."'
                                , sgjjam = '".$val['sgjjam']."', spaycesse = '".$val['spaycesse']."'
                                , spaytotal = '".$val['spaytotal']."'

                                ,btotalam = '".$val['btotalam']."'  , bshbam = '".$val['bshbam']."'
                                , bgjjam = '".$val['bgjjam']."', bpaycesse = '".$val['bpaycesse']."'
                                , bpaytotal = '".$val['bpaytotal']."'
                            where id='".$key."' ";
                        $this->db->query($sql);
                    }
                }
            }
//1.工程项目工资=工资/当月自然日*当月工程项目人工投入。（由于工程的人工投入是以当月自然日做基数，所以需要除以当月自然日。）
//2.剩余人工投入工资=工资-工程项目工资。
//3.研发项目工资=剩余人工投入工资*研发项目工资占比。
//4.工资部门管理费=剩余人工投入工资-研发项目工资。

            $sql="select t.name , t.totalam , t.shbam , t.gjjam , t.paycesse ,t.paytotal 
                    , t.stotalam , t.sshbam , t.sgjjam , t.spaycesse ,t.spaytotal
                    , t.btotalam , t.bshbam , t.bgjjam , t.bpaycesse ,t.bpaytotal 
                    , t.pmon , if(t.type='dept', '部门管理费', if(t.type='esm', '工程项目', i.project_name ) )  as iponame
                    , d.dept_name as deptname , zf.project_name as zfname
                    from salary_user_type t
                    left join project_rd r on (t.pid=r.id and t.type='pro')
                    left join project_ipo i on (r.ipo_id=i.id)
                    left join project_ipo zf on (r.zf_id=zf.id)
                    left join department d on (r.dept_id = d.dept_id)
                    where t.pyear='".$syear."' and t.pmon='".$smon."'  ";
            $query=$this->db->query($sql);
            $resData=array();
            while($row=$this->db->fetch_array($query)){
                $resData[$row['name']]=array(
                    'iponame'=>$row['iponame']
                    ,'deptname'=>$row['deptname']
                    ,'zfname'=>$row['zfname']
                    ,'totalam'=>$row['totalam'],'shbam'=>$row['shbam']
                    ,'gjjam'=>$row['gjjam'],'paycesse'=>$row['paycesse'],'paytotal'=>$row['paytotal']
                    ,'stotalam'=>$row['stotalam'],'sshbam'=>$row['sshbam']
                    ,'sgjjam'=>$row['sgjjam'],'spaycesse'=>$row['spaycesse'],'spaytotal'=>$row['spaytotal']
                    ,'btotalam'=>$row['btotalam'],'bshbam'=>$row['bshbam']
                    ,'bgjjam'=>$row['bgjjam'],'bpaycesse'=>$row['bpaycesse'],'bpaytotal'=>$row['bpaytotal']
                );
            }
            if($resData){
                foreach($resData as $key=>$row){
                    $dataarr[]=un_iconv(array(
                    $key,$row['iponame'],$row['deptname'],$row['zfname']
                    ,$row['totalam'],$row['shbam'],$row['gjjam'],$row['paycesse'],$row['paytotal']
//                  世源
                    ,$row['stotalam'],$row['sshbam'],$row['sgjjam'],$row['spaycesse'],$row['spaytotal']
//                  贝软
                    ,$row['btotalam'],$row['bshbam'],$row['bgjjam'],$row['bpaycesse'],$row['bpaytotal']
                    ));
                }
            }
           
        }elseif($flag=='gs_tol'){//工资部门分析表
            $is_echo=false;//关闭
            $bi=4;//插入表数据行数
            $sy=$_REQUEST['sy'];
            $sm=$_REQUEST['sm'];
            $filename.=$sy.'-'.$sm; //保存文件名
            $objPhpExcelFile->setActiveSheetIndex(0);
            $data=array();
            $deptarr=array();//部门信息
            $deptrep=array();//餐补和话费
            $deptarr['其他']['其他']='其他';
            $sql="(
                SELECT  d.pdeptname  , d.dept_id , d.dept_name  FROM department d
                left join department ds on (d.depart_x  = SUBSTR(ds.depart_x, 1, 2) )
                where ds.delflag=0 and d.delflag=0  and d.dept_id = d.pdeptid 
                group by d.dept_id  having count(d.dept_id)  =1
                order by d.dept_name 
                )union(
                SELECT   d.pdeptname  , d.dept_id , d.dept_name  FROM department d
                left join department ds on (d.depart_x  = SUBSTR(ds.depart_x, 1, 2) )
                where ds.delflag=0 and d.delflag=0  and d.dept_id = d.pdeptid 
                group by d.dept_id  having count(d.dept_id)  >1
                order by d.dept_name  desc  limit 100
                )union(
                SELECT   d.pdeptname  , d.dept_id , d.dept_name  FROM department d
                where  d.delflag=0  and d.dept_id <> d.pdeptid 
                order by d.dept_name desc  
                )";//获取部门固定排序
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                $deptarr[$row['pdeptname']][$row['dept_id']]=$row['dept_name'];
            }
            //餐车补
            $jfdb = $sy.'-'.$sm.'-01';
            $jfde = $sy.'-'.$sm.'-'.date('t',strtotime($jfdb));
            $wd=$this->salaryClass->getWorkDays($jfdb);
            $zham=330;//月补助金额
            $ydam=440;
            $zhwdam=$zham/$wd;//日补助金额
            $ydwdam=$ydam/$wd;
            $objPhpExcelFile->setActiveSheetIndex(4);
            $objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", $sm.'月餐车补' ) );
            $dataarr=array();
            $sqlTol=" and ( d.pdeptname not in ('贝讯专区','其他') or  u.user_id ='bin.chang' )  ";
            $sql="select d.dept_name,  sp.area as areaname ,u.company  , d.pdeptname as dtname , 1 as am , u.user_id
                from hrms h 
                left join user u on (u.user_id=h.user_id )
                left join  ( ( select sp.userid  ,sp.pyear, sp.pmon , sp.area , sp.deptid   from salary_pay sp where sp.pyear='".$sy."' and sp.pmon = '".$sm."' ) 
union  ( select  sp.userid  ,sp.pyear, sp.pmon , sp.area  , sp.deptid from `shiyuanoa`.salary_pay sp where sp.pyear='".$sy."' and sp.pmon = '".$sm."' ) 
union  ( select  sp.userid  ,sp.pyear, sp.pmon  , sp.area  , sp.deptid from `beiruanoa`.salary_pay sp where sp.pyear='".$sy."' and sp.pmon = '".$sm."' ) 			
)   sp on (sp.userid =u.user_id and sp.pyear='".$sy."' and sp.pmon = '".$sm."' ) 
                left join department d on (sp.deptid=d.dept_id ) 
                where u.del=0   and ( to_days(h.left_date)>to_days('".$jfde."') or h.left_date is null or h.left_date='0000-00-00'  ) 
                        and  to_days(h.come_date)<=to_days('".$jfde."') 
                and u.usertype='1' $sqlTol
                order by d.pdeptid , d.depart_x ";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                if($row['dtname']=='交付部'||$row['dtname']=='财务部'||$row['dtname']=='总经办'){
                    $row['dept_name']=$row['dtname'];
                }
                if($row['user_id']=='bin.chang'){
                    $row['dept_name']='其他';
                    $row['dtname']='其他';
                }
                $dataarr[$row['dept_name']]['pd']=$row['dtname'];
                if($row['areaname']=='珠海'){
                    $ckp=$row['company'].'_zh';
                }else{
                    $ckp=$row['company'].'_yd';
                }
                $dataarr[$row['dept_name']][$ckp]=empty($dataarr[$row['dept_name']][$ckp])?$row['am']:
                    round($dataarr[$row['dept_name']][$ckp]+$row['am']);
            }
            //离职人员
            $sql="select d.dept_name, sp.area as areaname ,u.company  , d.pdeptname as dtname , h.left_date as ldt , h.come_date as cdt
                from hrms h 
                left join user u on (u.user_id=h.user_id )
                left join  ( ( select sp.userid  ,sp.pyear, sp.pmon , sp.area , sp.deptid   from salary_pay sp where sp.pyear='".$sy."' and sp.pmon = '".$sm."' ) 
union  ( select  sp.userid  ,sp.pyear, sp.pmon , sp.area  , sp.deptid from `shiyuanoa`.salary_pay sp where sp.pyear='".$sy."' and sp.pmon = '".$sm."' ) 
union  ( select  sp.userid  ,sp.pyear, sp.pmon  , sp.area  , sp.deptid from `beiruanoa`.salary_pay sp where sp.pyear='".$sy."' and sp.pmon = '".$sm."' ) 			
)   sp on (sp.userid =u.user_id and sp.pyear='".$sy."' and sp.pmon = '".$sm."' ) 
                left join department d on (sp.deptid=d.dept_id ) 
                where u.del=0  and ( h.left_date is not null and h.left_date<>'0000-00-00')  and to_days(h.left_date)>=to_days('".$jfdb."') 
                        and to_days(h.left_date)<=to_days('".$jfde."')  
                        $sqlTol
                order by d.pdeptid , d.depart_x";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                if($row['dtname']=='交付部'||$row['dtname']=='财务部'||$row['dtname']=='总经办'){
                    $row['dept_name']=$row['dtname'];
                }
                $dataarr[$row['dept_name']]['pd']=$row['dtname'];
                if($row['areaname']=='珠海'){
                    $ckp=$row['company'].'_zh_l';
                }else{
                    $ckp=$row['company'].'_yd_l';
                }
                $dataarr[$row['dept_name']][$ckp]=empty($dataarr[$row['dept_name']][$ckp])?1:round($dataarr[$row['dept_name']][$ckp]+1);
            }
            //获取导入服务线信息
            $rep_cb=$repClass->getRepData('gzfuxcb', array('dimY'=>$sy,'dimM'=>$sm),'data');
            if(!empty($rep_cb)){
                for($ri=2;$ri<count($rep_cb);$ri++){
                    $dataarr[$rep_cb[$ri][1]['name']]['dl_cb']=$rep_cb[$ri][2]['name'];
                    $dataarr[$rep_cb[$ri][1]['name']]['sy_cb']=$rep_cb[$ri][3]['name'];
                    $dataarr[$rep_cb[$ri][1]['name']]['br_cb']=$rep_cb[$ri][4]['name'];
                    
                    $dataarr[$rep_cb[$ri][1]['name']]['dl_cb_l']=$rep_cb[$ri][5]['name'];
                    $dataarr[$rep_cb[$ri][1]['name']]['sy_cb_l']=$rep_cb[$ri][6]['name'];
                    $dataarr[$rep_cb[$ri][1]['name']]['br_cb_l']=$rep_cb[$ri][7]['name'];
                }
            }
//            print_r($dataarr);
//            die();
            //设置表头及样式 设置
            $i = 4;
            $sumi=array();
            if(!empty($dataarr)){
                foreach($deptarr as $key=>$val){
                    $n=0;
                    foreach($val as $vkey=>$vval){
                        if(empty($dataarr[$vval])){
                            continue;
                        }
                        $sumi[$i]=$i;
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (0, $i, iconv ( "gb2312", "utf-8", $key ) );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, $i, iconv ( "gb2312", "utf-8", $vval ) );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (2, $i,  '=SUM('.'D'.$i.','.'E'.$i.','.'F'.$i.','.'G'.$i.','.'H'.$i.','.'I'.$i.')');
                        //服务线
                        if($key=='服务线'){
                            //在职
                            $deptrep[$vval]['餐补']=$dataarr[$vval]['dl_cb']
                                                    +$dataarr[$vval]['sy_cb']
                                                    +$dataarr[$vval]['br_cb']
                                                    +$dataarr[$vval]['dl_cb_l']
                                                    +$dataarr[$vval]['sy_cb_l']
                                                    +$dataarr[$vval]['br_cb_l'];
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (3, $i, $dataarr[$vval]['dl_cb']);
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (4, $i, $dataarr[$vval]['sy_cb']);
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (5, $i, $dataarr[$vval]['br_cb']);
                        }else{
                            $deptrep[$vval]['餐补']=$dataarr[$vval]['dl_zh']*$zham+$dataarr[$vval]['dl_yd']*$ydam
                                                    +$dataarr[$vval]['sy_zh']*$zham+$dataarr[$vval]['sy_yd']*$ydam
                                                    +$dataarr[$vval]['br_zh']*$zham+$dataarr[$vval]['br_yd']*$ydam
                                                    +$dataarr[$vval]['dl_cb_l']
                                                    +$dataarr[$vval]['sy_cb_l']
                                                    +$dataarr[$vval]['br_cb_l'];
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (3, $i, $dataarr[$vval]['dl_zh']*$zham+$dataarr[$vval]['dl_yd']*$ydam );
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (4, $i, $dataarr[$vval]['sy_zh']*$zham+$dataarr[$vval]['sy_yd']*$ydam );
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (5, $i, $dataarr[$vval]['br_zh']*$zham+$dataarr[$vval]['br_yd']*$ydam );
                        }
                        //离职
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (6, $i, $dataarr[$vval]['dl_cb_l']);
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (7, $i, $dataarr[$vval]['sy_cb_l']);
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (8, $i, $dataarr[$vval]['br_cb_l']);

                        //在职人数
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (9, $i, $dataarr[$vval]['dl_zh'] );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (10, $i, $dataarr[$vval]['dl_yd'] );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (11, $i, $dataarr[$vval]['sy_zh'] );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (12, $i, $dataarr[$vval]['sy_yd'] );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (13, $i, $dataarr[$vval]['br_zh'] );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (14, $i, $dataarr[$vval]['br_yd'] );
                        //离职人数
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (15, $i, $dataarr[$vval]['dl_zh_l'] );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (16, $i, $dataarr[$vval]['dl_yd_l'] );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (17, $i, $dataarr[$vval]['sy_zh_l'] );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (18, $i, $dataarr[$vval]['sy_yd_l'] );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (19, $i, $dataarr[$vval]['br_zh_l'] );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (20, $i, $dataarr[$vval]['br_yd_l'] );

                        $i++;//行增加
                        $n++;
                    }
                    if($n>1){//多行
                        $objPhpExcelFile->getActiveSheet ()->mergeCells ('A' . ($i-$n).':'.'A' . ($i) );//合并
                        $objPhpExcelFile->getActiveSheet()->getStyle( 'A'.$i.':U'.$i)->getFont()->setSize(10);
                        $objPhpExcelFile->getActiveSheet()->getStyle( 'A'.$i.':U'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('8FBC8F');
                        
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (0, $i, iconv ( "gb2312", "utf-8", $key ) );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, $i, iconv ( "gb2312", "utf-8", '小计' ) );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (2, $i,  '=SUM(C'.($i-$n).':C'.($i-1).')');
                        //在职
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (3, $i,  '=SUM(D'.($i-$n).':D'.($i-1).')');
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (4, $i, '=SUM(E'.($i-$n).':E'.($i-1).')');
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (5, $i, '=SUM(F'.($i-$n).':F'.($i-1).')' );
                        //离职
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (6, $i,  '=SUM(G'.($i-$n).':G'.($i-1).')');
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (7, $i, '=SUM(H'.($i-$n).':H'.($i-1).')');
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (8, $i, '=SUM(I'.($i-$n).':I'.($i-1).')' );

                        //在职人数
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (9, $i, '=SUM(J'.($i-$n).':J'.($i-1).')' );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (10, $i, '=SUM(K'.($i-$n).':K'.($i-1).')' );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (11, $i, '=SUM(L'.($i-$n).':L'.($i-1).')' );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (12, $i, '=SUM(M'.($i-$n).':M'.($i-1).')' );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (13, $i, '=SUM(N'.($i-$n).':N'.($i-1).')' );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (14, $i, '=SUM(O'.($i-$n).':O'.($i-1).')' );
                        //离职人数
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (15, $i, '=SUM(P'.($i-$n).':P'.($i-1).')' );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (16, $i, '=SUM(Q'.($i-$n).':Q'.($i-1).')' );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (17, $i, '=SUM(R'.($i-$n).':R'.($i-1).')' );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (18, $i, '=SUM(S'.($i-$n).':S'.($i-1).')' );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (19, $i, '=SUM(T'.($i-$n).':T'.($i-1).')' );
                        $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (20, $i, '=SUM(U'.($i-$n).':U'.($i-1).')' );
                        $i++;//行增加
                    }
                }
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (0, $i, '' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, $i, 'Total' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (2, $i, '=SUM('.$this->get_cells_str('C',$sumi).')' );
                //在职
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (3, $i, '=SUM('.$this->get_cells_str('D',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (4, $i, '=SUM('.$this->get_cells_str('E',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (5, $i, '=SUM('.$this->get_cells_str('F',$sumi).')' );
                //离职
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (6, $i, '=SUM('.$this->get_cells_str('G',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (7, $i, '=SUM('.$this->get_cells_str('H',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (8, $i, '=SUM('.$this->get_cells_str('I',$sumi).')' );
                //在职人数
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (9, $i, '=SUM('.$this->get_cells_str('J',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (10, $i, '=SUM('.$this->get_cells_str('K',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (11, $i, '=SUM('.$this->get_cells_str('L',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (12, $i, '=SUM('.$this->get_cells_str('M',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (13, $i, '=SUM('.$this->get_cells_str('N',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (14, $i, '=SUM('.$this->get_cells_str('O',$sumi).')' );
                //离职人数
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (15, $i, '=SUM('.$this->get_cells_str('P',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (16, $i, '=SUM('.$this->get_cells_str('Q',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (17, $i, '=SUM('.$this->get_cells_str('R',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (18, $i, '=SUM('.$this->get_cells_str('S',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (19, $i, '=SUM('.$this->get_cells_str('T',$sumi).')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (20, $i, '=SUM('.$this->get_cells_str('U',$sumi).')' );
            }
            //话费
            $rep_hf=$repClass->getRepData('gzhf', array('dimY'=>$sy,'dimM'=>$sm),'data');
            $objPhpExcelFile->setActiveSheetIndex(5);
            $objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", $sm.'月话费' ) );
            if(!empty($rep_hf)){
                $rowset = 1;
                //$rep_hf=  un_iconv($rep_hf);
                foreach($rep_hf as $row=>$val){
                    foreach($val as $col=>$vval){
                        if(is_array($vval)){//内置格式
                            //合并
                            $colnow=$gl->numToCell($col).($row+$rowset);
                            $col_1=$col;
                            $col_2=$row+$rowset;
                            if(!empty($vval['to_cols'])){
                                $col_1=$col+$vval['to_cols']-1;
                            }
                            if(!empty($vval['to_rows'])){
                                $col_2=$row+$rowset+$vval['to_rows']-1;
                            }
                            $colto=$gl->numToCell($col_1).($col_2);
                            if ($colnow!=$colto) {
                                $objPhpExcelFile->getActiveSheet ()->mergeCells( $colnow. ':' . $colto );
                            }
                            
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($col, $row+$rowset, un_iconv($vval['name']) );
                        }else{
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($col, $row, $vval );
                        }
                    }
                    //加入话费
                    if(!empty($val['1']['name'])){
                        $deptrep[$val['1']['name']]['话费']=$val['2']['name'];
                    }
                }
            }
//            print_r($rep_hf);
//            print_r($deptrep);
//            die();
            //统计表
            $dataarr=array();
            
            $sqlTol=" and ( d.pdeptname not in ('贝讯专区','其他') or  p.userid ='bin.chang' or p.userid='2903'  )  ";
            $sqlSch=" and ( ( p.pyear='".$sy."' and p.pmon='".$sm."' and p.nowamflag!=3 or p.nowamflag is null ) 
                        or ( p.pyear='".$sy."' and p.pmon='".$sm."' and p.nowamflag=3 ) 
                        or ( s.lpy='".$sy."' and s.lpm='".$sm."' and  year(s.leavedt)=p.pyear and month(s.leavedt)=p.pmon and p.nowamflag=3 ) )
                    ".$sqlTol;
            $sql = "(
                    select  
                        p.gjjam , p.shbam
                        , p.remark , p.baseam , p.floatam , p.proam , p.otheram
                        , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                        , p.pyear , p.pmon
                        , p.sdyam , p.holsdelam , 'dl' as company , p.expflag , p.basenowam  ,  p.cogjjam , p.coshbam
                        , p.accrewam , p.accdelam , p.nowamflag , p.userid , p.deptid  , p.leaveflag , p.jfcom , p.manageam 
                        , d.pdeptname , d.dept_name as deptname , s.lpy , s.lpm , year(s.leavedt) as ldy , month(s.leavedt) as ldm  
                    from salary_pay p 
                        left join salary s on ( p.userid=s.userid )
                        left join department d on ( d.dept_id=p.deptid )
                    where 
                        p.leaveflag='0'
                        $sqlSch    
                    )union(
                    select  
                        p.gjjam , p.shbam
                        , p.remark , p.baseam , p.floatam , p.proam , p.otheram
                        , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                        , p.pyear , p.pmon
                        , p.sdyam , p.holsdelam , 'sy' as company , p.expflag , p.basenowam  ,  p.cogjjam , p.coshbam
                        , p.accrewam , p.accdelam , p.nowamflag , p.userid , p.deptid  , p.leaveflag , p.jfcom , p.manageam 
                        , d.pdeptname , d.dept_name as deptname , s.lpy , s.lpm , year(s.leavedt) as ldy , month(s.leavedt) as ldm  
                    from `shiyuanoa`.salary_pay p 
                        left join salary s on ( p.userid=s.userid )
                        left join department d on ( d.dept_id=p.deptid )
                    where 
                        p.leaveflag='0'
                        $sqlSch    
                    )union(
                    select  
                        p.gjjam , p.shbam
                        , p.remark , p.baseam , p.floatam , p.proam , p.otheram
                        , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                        , p.pyear , p.pmon
                        , p.sdyam , p.holsdelam , 'br' as company , p.expflag , p.basenowam  ,  p.cogjjam , p.coshbam
                        , p.accrewam , p.accdelam , p.nowamflag , p.userid , p.deptid  , p.leaveflag , p.jfcom , p.manageam 
                        , d.pdeptname , d.dept_name as deptname , s.lpy , s.lpm , year(s.leavedt) as ldy , month(s.leavedt) as ldm  
                    from `beiruanoa`.salary_pay p 
                        left join salary s on ( p.userid=s.userid )
                        left join department d on ( d.dept_id=p.deptid )
                    where 
                        p.leaveflag='0'
                        $sqlSch
                    ) ";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                if($row['pdeptname']=='交付部'||$row['pdeptname']=='财务部'||$row['pdeptname']=='总经办'){
                    $row['deptname']=$row['pdeptname'];
                }
                if($row['userid']=='bin.chang' || $row['userid']=='2903'){
                    $row['deptname']='其他';
                }
                $jbgj=$gsshbgjj=$btfl=$xmj=$yfl=$gjze=0;
                if($row['nowamflag']!=3){//在职
                    //公司社保公积金
                    $gsshbgjj=$this->salaryClass->decryptDeal($row['coshbam'])+$this->salaryClass->decryptDeal($row['cogjjam'])+$this->salaryClass->decryptDeal($row['manageam']);
                    $dataarr[$row['deptname']]['社保公积金']=empty($dataarr[$row['deptname']]['社保公积金'])?$gsshbgjj:($dataarr[$row['deptname']]['社保公积金']+$gsshbgjj);
                    //基本工资=基本工资+税前补发工资+税后补发工资
                    $jb=$this->salaryClass->decryptDeal($row['basenowam']);
                    if(empty($jb)||round($jb)==0||$jb==0){
                    $jb=$this->salaryClass->decryptDeal($row['baseam']);
                    }
                    //基本工资
                    $jbgj=round($jb+$this->salaryClass->decryptDeal($row['sperewam'])+$this->salaryClass->decryptDeal($row['accrewam']),2);
                    $dataarr[$row['deptname']]['基本工资']=empty($dataarr[$row['deptname']]['基本工资'])?$jbgj:($dataarr[$row['deptname']]['基本工资']+$jbgj);
                    //补贴+福利（ 话费+餐车补 手动 ）
                    $btfl=round($this->salaryClass->decryptDeal($row['sdyam']),2)+round($this->salaryClass->decryptDeal($row['otheram']),2);
                    $dataarr[$row['deptname']]['补贴']=empty($dataarr[$row['deptname']]['补贴'])?$btfl:($dataarr[$row['deptname']]['补贴']+$btfl);
                    //项目奖
                    $xmj=round($this->salaryClass->decryptDeal($row['proam'])
                        +$this->salaryClass->decryptDeal($row['bonusam'])+$this->salaryClass->decryptDeal($row['floatam']),2);
                    $dataarr[$row['deptname']]['项目奖']=empty($dataarr[$row['deptname']]['项目奖'])?$xmj:($dataarr[$row['deptname']]['项目奖']+$xmj);
                    //集团在职人数
                    $dataarr[$row['deptname']]['集团人数']=empty($dataarr[$row['deptname']]['集团人数'])?1:($dataarr[$row['deptname']]['集团人数']+1);
                    
                }else{//离职
                    if($row['ldy']==$sy && $row['ldm']==$sm ){//当月离职统计社保公积金
                        $gsshbgjj=$this->salaryClass->decryptDeal($row['coshbam'])+$this->salaryClass->decryptDeal($row['cogjjam'])+$this->salaryClass->decryptDeal($row['manageam']);
                        $dataarr[$row['deptname']]['社保公积金']=empty($dataarr[$row['deptname']]['社保公积金'])?$gsshbgjj:($dataarr[$row['deptname']]['社保公积金']+$gsshbgjj);
                    }
                    if($row['lpy']==$sy && $row['lpm']==$sm ){//支付日期在选择月份计算成本
                        $jb=$this->salaryClass->decryptDeal($row['basenowam']);
                        if(empty($jb)||round($jb)==0||$jb==0){
                            $jb=$this->salaryClass->decryptDeal($row['baseam']);
                        }
                        //基本工资=基本工资+各项补贴+离职福利
                        $jb=round($jb+$this->salaryClass->decryptDeal($row['sperewam'])+$this->salaryClass->decryptDeal($row['accrewam']),2);
                        if($row['lpy']==2013 && $row['lpm']==12 && ( $row['userid']=='fei.xue' || $row['userid']=='min.ruan'  ) ){
                        	$jb=round($this->salaryClass->decryptDeal($row['accrewam']),2);
                        }
                        $xm=round($this->salaryClass->decryptDeal($row['proam'])
                                +$this->salaryClass->decryptDeal($row['bonusam'])+$this->salaryClass->decryptDeal($row['floatam']),2);
                        //补贴：节假日补贴（旧餐补） 福利：福利补贴（旧其他补贴）
                        $bt=round($this->salaryClass->decryptDeal($row['sdyam']),2);
                        $fl=round($this->salaryClass->decryptDeal($row['otheram']),2);  
                        //离职补偿
                        $yfl=round($jb+$xm+$bt+$fl,2);
                        $dataarr[$row['deptname']]['离职补偿']=empty($dataarr[$row['deptname']]['离职补偿'])?$yfl:($dataarr[$row['deptname']]['离职补偿']+$yfl);
                    }
                }
                $gjze=($jbgj+$gsshbgjj+$btfl+$xmj);
                $dataarr[$row['deptname']]['工资总额']=empty($dataarr[$row['deptname']]['工资总额'])?($gjze):($dataarr[$row['deptname']]['工资总额']+$gjze);
                $dataarr[$row['deptname']]['人工总支出']=empty($dataarr[$row['deptname']]['人工总支出'])?($gjze+$yfl):($dataarr[$row['deptname']]['人工总支出']+$gjze+$yfl);
            }
            //加载话费餐补
            if($deptarr){
                foreach($deptarr as $key=>$val){
                    foreach($val as $vkey=>$vval){
                        $vvalc=$vval;
                        if($key=='交付部'||$key=='财务部'||$key=='总经办'){
                            $vvalc=$key;
                        }
                        if(!empty($deptrep[$vval])&&!empty($dataarr[$vvalc])){
                            $dataarr[$vvalc]['补贴']=$dataarr[$vvalc]['补贴']+$deptrep[$vval]['餐补']+$deptrep[$vval]['话费'];
                        }
                    }
                }
            }
            //第一表 月度总计
            $totalarr=array(
                '基本工资'=>0
                ,'社保公积金'=>0
                ,'补贴'=>0
                ,'项目奖'=>0
                ,'项目津贴'=>0
                ,'工资总额'=>0
                ,'离职补偿'=>0
                ,'人工总支出'=>0
                ,'集团人数'=>0
                ,'平均成本'=>0
            );
            foreach($dataarr as $key=>$val){
                $totalarr['基本工资']+=$val['基本工资'];
                $totalarr['社保公积金']+=$val['社保公积金'];
                $totalarr['补贴']+=$val['补贴'];
                $totalarr['项目奖']+=$val['项目奖'];
                $totalarr['项目津贴']+=$val['项目津贴'];
                $totalarr['工资总额']+=$val['工资总额'];
                $totalarr['离职补偿']+=$val['离职补偿'];
                $totalarr['人工总支出']+=$val['人工总支出'];
                $totalarr['集团人数']+=$val['集团人数'];
            }
            $objPhpExcelFile->setActiveSheetIndex(0);
            $objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", $sm.'月总计' ) );
            
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, 3, $totalarr['基本工资'] );
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, 4, $totalarr['社保公积金'] );
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, 5, $totalarr['补贴'] );
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, 6, $totalarr['项目奖'] );
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, 7, $totalarr['项目津贴'] );
            //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, 8, '=SUM(B3:B7)' );
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, 9, $totalarr['离职补偿'] );
            //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, 10, '=SUM(B8,B9)' );
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, 11, $totalarr['集团人数'] );
            //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1, 12, '=B10/B11' );
            
            //研发线月度成本
            $objPhpExcelFile->setActiveSheetIndex(1);
            $objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '研发'.$sm.'月成本' ) );
            $yfarray=array('仪器仪表部','网络应用部','基础研究部','移动业务部','测试部','综合部');
            $i=1;
            foreach($yfarray as $key=>$val){
                $tempcell=$gl->numToCell($i);
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,3, $dataarr[$val]['基本工资'] );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,4, $dataarr[$val]['社保公积金'] );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,5, $dataarr[$val]['补贴'] );
                //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,6, '=SUM('.$tempcell.'3:'.$tempcell.'5)' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,7, $dataarr[$val]['离职补偿'] );
                //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,8, '=SUM('.$tempcell.'6:'.$tempcell.'7)' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,9, $dataarr[$val]['集团人数'] );
                //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,10, '='.$tempcell.'8/'.$tempcell.'9' );
                $i++;
            }
            //合计
            $n=3;
            foreach($totalarr as $key=>$val){
                if($key!='项目奖'&&$key!='项目津贴'&&$key!='平均成本'){
                    $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i, $n, '=SUM('.'B'.$n.','.'C'.$n.','.'D'.$n.','.'E'.$n.','.'F'.$n.','.'G'.$n.')' );
                    $n++;
                }
            }
            //服务线月度成本
            $objPhpExcelFile->setActiveSheetIndex(2);
            $objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '服务线'.$sm.'月成本' ) );
            $yfarray=array('项目实施南区','项目实施西区','服务运营部','项目实施东区','项目实施北区','远程运维中心','江苏专区','新服务实施部','服务售前方案部','河南');
            $i=1;
            foreach($yfarray as $key=>$val){
                $tempcell=$gl->numToCell($i);
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,3, $dataarr[$val]['基本工资'] );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,4, $dataarr[$val]['项目奖'] );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,5, $dataarr[$val]['项目津贴'] );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,6, $dataarr[$val]['补贴'] );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,7, $dataarr[$val]['社保公积金'] );
                //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,8, '=SUM('.$tempcell.'3:'.$tempcell.'7)' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,9, $dataarr[$val]['离职补偿'] );
                //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,10, '=SUM('.$tempcell.'8:'.$tempcell.'9)' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,11, $dataarr[$val]['集团人数'] );
                //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i,12, '='.$tempcell.'10/'.$tempcell.'11' );
                $i++;
            }
            //合计
            $n=3;
            foreach($totalarr as $key=>$val){
                if($key!='平均成本'){
                    $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($i, $n
                        , '=SUM('.'B'.$n.','.'C'.$n.','.'D'.$n.','.'E'.$n.','.'F'.$n.','.'G'.$n.','.'H'.$n.','.'I'.$n.','.'J'.$n.','.'K'.$n.')' );
                    $n++;
                }
            }
            //其他部门
            $objPhpExcelFile->setActiveSheetIndex(3);
            $objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '其他部门'.$sm.'月成本' ) );
            $yfarray=array('营销线','经营团队','市场拓展部','硬件平台事业部','总经办'
                            ,'交付部','财务部','海外业务部','人力资源部','审计部','董事会办公室','其他');
            $n=3;
            foreach($yfarray as $key=>$val){
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1,$n, $dataarr[$val]['基本工资'] );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (2,$n, $dataarr[$val]['补贴'] );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (3,$n, $dataarr[$val]['社保公积金'] );
                //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (4,$n, '=SUM(B'.$n.':D'.$n.')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (5,$n, $dataarr[$val]['离职补偿'] );
                //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (6,$n, '=SUM(E'.$n.',F'.$n.')' );
                $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (7,$n, $dataarr[$val]['集团人数'] );
                //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (8,$n, '=G'.$n.'/H'.$n.'' );
                $n++;
            }
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (1,$n, '=SUM(B3:B'.($n-1).')' );
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (2,$n, '=SUM(C3:C'.($n-1).')' );
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (3,$n, '=SUM(D3:D'.($n-1).')' );
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (4,$n, '=SUM(E3:E'.($n-1).')' );
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (5,$n, '=SUM(F3:F'.($n-1).')' );
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (6,$n, '=SUM(G3:G'.($n-1).')' );
            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (7,$n, '=SUM(H3:H'.($n-1).')' );
            //$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow (8,$n, '=SUM(I3:I'.($n-1).')' );
            
            $objPhpExcelFile->setActiveSheetIndex(0);
        }elseif($flag=='gs_cp'){//工资统计对比
            $is_echo=true;//关闭
            $bi=4;//插入表数据行数
            $sy=$_REQUEST['sy'];
            $sm=$_REQUEST['sm'];
            $filename.=$sy.'-'.$sm; //保存文件名
            $setString=array(0//员工号
            		,'A'
            );
            $objPhpExcelFile->setActiveSheetIndex(0);
            $dataarr = un_iconv($this->model_user_total());
        }
        
        if($is_echo){
            //设置表头及样式 设置
            $i = $bi;
            if(!empty($dataarr)){
                foreach($dataarr as $key=>$val){
                    $n=0;//列定义
                    foreach($val as $vkey=>$vval ){
                        if(in_array($n , $setString,true)||in_array($gl->numToCell($n) , $setString,true)){
                            $objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ($n, $i, $vval );
                        }else{
                            $objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ($n, $i, $vval );
                        }
                        $n++;//列增加
                    }
                    $i++;//行增加
                }
            }
        }
        //到浏览器
        ob_end_clean (); //解决输出到浏览器出现乱码的问题
        header ( "Content-Type: application/force-download" );
        header ( "Content-Type: application/octet-stream" );
        header ( "Content-Type: application/download" );
        header ( 'Content-Disposition:inline;filename="' . $filename. ".xls" . '"' );
        header ( "Content-Transfer-Encoding: binary" );
        header ( "Expires: 0" );
        header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
        header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
        header ( "Pragma: no-cache" );
        $objWriter->save ( 'php://output' );
    }
    /**
     * 人员数据统计
     */
    function model_user_total(){
    	$res=array();
    	$sqlSch = " and p.pyear in (2012,2013) ";
    	echo $sql = "(
	    	select
	    	p.gjjam , p.shbam
	    	, p.remark , p.baseam , p.floatam , p.proam , p.otheram
	    	, p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
	    	, p.pyear , p.pmon
	    	, p.sdyam , p.holsdelam , 'dl' as company , p.expflag , p.basenowam  ,  p.cogjjam , p.coshbam
	    	, p.accrewam , p.accdelam , p.nowamflag , p.userid , p.deptid  , p.leaveflag , p.jfcom , p.manageam
	    	from salary_pay p
	    	where
	    	p.leaveflag='0'
	    	$sqlSch
	    	)union(
	    	select
	    	p.gjjam , p.shbam
	    	, p.remark , p.baseam , p.floatam , p.proam , p.otheram
	    	, p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
	    	, p.pyear , p.pmon
	    	, p.sdyam , p.holsdelam , 'sy' as company , p.expflag , p.basenowam  ,  p.cogjjam , p.coshbam
	    	, p.accrewam , p.accdelam , p.nowamflag , p.userid , p.deptid  , p.leaveflag , p.jfcom , p.manageam
	    	from `shiyuanoa`.salary_pay p
	    	where
	    	p.leaveflag='0'
	    	$sqlSch
	    	)union(
	    	select
	    	p.gjjam , p.shbam
	    	, p.remark , p.baseam , p.floatam , p.proam , p.otheram
	    	, p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
	    	, p.pyear , p.pmon
	    	, p.sdyam , p.holsdelam , 'br' as company , p.expflag , p.basenowam  ,  p.cogjjam , p.coshbam
	    	, p.accrewam , p.accdelam , p.nowamflag , p.userid , p.deptid  , p.leaveflag , p.jfcom , p.manageam
	    	from `beiruanoa`.salary_pay p
	    	where
	    	p.leaveflag='0'
	    	$sqlSch
    	) ";
    	$query=$this->db->query($sql);
    	while($row=$this->db->fetch_array($query)){
    		$res[$row['userid']]['baseam'][$row['pyear']][$row['pmon']]=$this->salaryClass->decryptDeal($row['baseam']);
    		$res[$row['userid']]['otheram'][$row['pyear']][$row['pmon']]=round(
    				$this->salaryClass->decryptDeal($row['floatam'])
    				+$this->salaryClass->decryptDeal($row['proam'])
    				+$this->salaryClass->decryptDeal($row['sdyam'])
    				+$this->salaryClass->decryptDeal($row['otheram'])
    				+$this->salaryClass->decryptDeal($row['bonusam'])
    				+$this->salaryClass->decryptDeal($row['sperewam'])
    				,2);
    	}
    	//年终
    	$sql="SELECT y.UserCard , h.USER_ID as userid , y.SYear , y.YearAm FROM `salary_yeb` y 
LEFT JOIN hrms h on ( y.UserCard = h.UserCard )
where y.SYear in ( 2012 , 2013 );";
    	$query=$this->db->query($sql);
    	while($row=$this->db->fetch_array($query)){
    		$res[$row['userid']]['yearam'][$row['SYear']]=$this->salaryClass->decryptDeal($row['YearAm']);
    		
    	}
    	//人员
    	$user=array();
    	$sql="SELECT u.USER_ID , u.USER_NAME , u.Company , d.pdeptname , d.DEPT_NAME , h.UserCard   FROM `user` u 
LEFT JOIN salary s on (u.USER_ID = s.UserId)
LEFT JOIN department d on (u.DEPT_ID = d.DEPT_ID)
LEFT JOIN hrms h on (u.USER_ID = h.USER_ID )
where 
	u.HAS_LEFT=0 and s.UserSta<>3 ;";
    	$query=$this->db->query($sql);
    	while($row=$this->db->fetch_array($query)){
    		$user[$row['USER_ID']]=array(
    				'uname'=>$row['USER_NAME']
    				,'com'=>$this->salaryCom[$row['Company']]
    				,'pdept'=>$row['pdeptname']
    				,'dept'=>$row['DEPT_NAME']
    				,'ucard'=>$row['UserCard']
    		);
    	
    	}
    	//整理
    	$data = array();
    	foreach($user as $key=>$val  ){
    		$b12 =round( array_sum($res[$key]['baseam']['2012']),2) ;
    		$ba12 =round($b12/count($res[$key]['baseam']['2012']),2);
    		$o12 =round( array_sum($res[$key]['otheram']['2012']) ,2);
    		$y12 =round( $res[$key]['yearam']['2012'],2);
    		$t12 = round($b12+$o12+$y12,2);
    		$ta12 =round( $t12/count($res[$key]['baseam']['2012']),2);
    		
    		$b13 = round(array_sum($res[$key]['baseam']['2013']),2) ;
    		$ba13 =round($b13/count($res[$key]['baseam']['2013']),2);
    		$o13 = round(array_sum($res[$key]['otheram']['2013']) ,2);
    		$y13 = round($res[$key]['yearam']['2013'],2);
    		$t13 =round( $b13+$o13+$y13,2);
    		$ta13 = round($t13/count($res[$key]['baseam']['2013']),2);
    		
    		$data[$key]=array(
    				'ucard'=>$val['ucard']
    				,'uname'=>$val['uname']
    				,'com'=>$val['com']
    				,'pdept'=>$val['pdept']
    				,'dept'=>$val['dept']
    				,$ta12,$ba12,$b12,$y12,$o12,$t12
    				,$ta13,$ba13,$b13,$y13,$o13,$t13
    		);
    	}
    	unset($res);
    	unset($user);
    	return $data;
    }
    //
    function get_cells_str($cell,$rowarr){
        $res='';
        if(!empty($rowarr)){
            foreach($rowarr as $val){
                $res.=$cell.$val.',';
            }
        }
        return trim($res,',');
    }
    function model_hr_xls_out(){
        global $func_limit;
        $flag=$_GET['flag'];
        $seay=$_GET['sy'];
        $seam=$_GET['sm'];
        $seacom=$_GET['seacom'];
        $comtable=$this->get_com_sql($seacom);
        $i=1;
        include( WEB_TOR . 'includes/classes/excel_out_class.php');
        $xls = new ExcelXML('gb2312', false, 'My Test Sheet');
        if($flag=='join'){
            $data = array(1=> array('序号','姓名','类型','部门','级别','入职日期','入职工资'
                ,'入职当月工资','地区','身份证','账号','开户行','邮箱','录入日期','状态'));
            $sql="select
                    s.rand_key , s.username , s.userid , s.olddept , h.userlevel
                    , s.comedt , s.probationam , s.probationnowam , s.probationdt
                    , s.usersta , h.expflag
                    , s.oldarea , s.idcard , s.acc , s.accbank , s.email , s.oldname
                    , s.recoverdt , s.recoveram , s.recovernowam , s.recovercdt
                from salary s
                    left join hrms h on ( s.userid=h.user_id )
                where
                    s.userid=h.user_id
                    and (
                            ( year(s.probationdt)='".$seay."' and  month(s.probationdt)='".$seam."' )
                            or
                            ( year(s.recovercdt)='".$seay."' and  month(s.recovercdt)='".$seam."' )
                        )
                order by s.usersta asc , h.userlevel desc , s.probationdt desc ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                if(substr($row['probationdt'], 0, 4)==$seay&&substr($row['probationdt'], 5, 2)==$seam){
                    $dt=$row['comedt'];
                    $cdt=$row['probationdt'];
                    $st=$this->userSta[$row['usersta']];
                    if($row['userlevel']=='4'){
                        $pa=$this->salaryClass->decryptDeal($row['probationam']);
                        $pan=$this->salaryClass->decryptDeal($row['probationnowam']);
                    }else{
                        $pa='-';
                        $pan='-';
                    }
                }else{
                    $dt=$row['recoverdt'];
                    $cdt=$row['recovercdt'];
                    $st='已恢复';
                    if($row['userlevel']=='4'){
                        $pa=$this->salaryClass->decryptDeal($row['recoveram']);
                        $pan=$this->salaryClass->decryptDeal($row['recovernowam']);
                    }else{
                        $pa='-';
                        $pan='-';
                    }
                }
                $data[]=array(
                    $i,$row['username'], $this->expflag[$row['expflag']],$row['olddept'],$this->userLevel[$row['userlevel']]
                    ,$dt,$pa,$pan
                    ,$row['oldarea'],$row['idcard'],$row['acc'],$row['accbank'],$row['email'],$cdt
                    ,$st
                );
                $i++;
            }
        }elseif($flag=='pass'){
            $data = array(1=> array('序号','姓名','类型','部门','级别','转正日期','转正后工资'
                ,'转正当月工资','转正前工资','录入日期','状态'));
            $sql="select
                s.rand_key , s.username , s.userid , s.olddept , h.userlevel , h.join_date
                , s.passdt , s.passam , s.passnowam , s.passuserdt
                , s.usersta
                , h.userlevel , h.expflag
                , s.passoldam
            from salary s
                left join hrms h on ( s.userid=h.user_id )
            where
                s.userid=h.user_id
                and year(s.passdt)='".$seay."' and month(s.passdt)='".$seam."'
            order by s.usersta asc , h.userlevel desc , s.passuserdt desc ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                if($row['userlevel']=='4'){
                    $pa=$this->salaryClass->decryptDeal($row['passam']);
                    $pan=$this->salaryClass->decryptDeal($row['passnowam']);
                    $pao=$this->salaryClass->decryptDeal($row['passoldam']);
                }else{
                    $pa='-';
                    $pan='-';
                    $pao='-';
                }
                $data[]=array(
                    $i,$row['username'], $this->expflag[$row['expflag']],$row['olddept'],$this->userLevel[$row['userlevel']]
                    ,$row['passdt'],$pa,$pan,$pao,$row['passuserdt']
                    ,$this->userSta[$row['usersta']]
                );
                $i++;
            }
        }elseif($flag=='leave'){
            $data = array(1=> array('序号','姓名','员工类型','部门','级别','离职日期','录入日期','处理类型'));
            $sql="select
                s.rand_key , s.username , s.userid , s.olddept , h.userlevel
                , s.leavedt , s.leavecreatedt
                , s.usersta
                , h.userlevel , h.expflag , s.freezeflag , s.freezedt , s.freezecdt
            from salary s
                left join hrms h on ( s.userid=h.user_id )
            where
                s.userid=h.user_id
                and ( (year(s.leavedt)='".$seay."' and month(s.leavedt)='".$seam."')
                        or (year(s.freezedt)='".$seay."' and month(s.freezedt)='".$seam."')
                     )
            order by s.usersta asc , h.userlevel desc , s.leavecreatedt desc ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                if($row['freezeflag']=='1'){
                    $dt=$row['freezedt'];
                    $cdt=$row['freezecdt'];
                    $st='冻结';
                }else{
                    $dt=$row['leavedt'];
                    $cdt=$row['leavecreatedt'];
                    $st='离职';
                }
                $data[]=array(
                    $i,$row['username'], $this->expflag[$row['expflag']],$row['olddept'],$this->userLevel[$row['userlevel']]
                    ,$dt,$cdt,$st
                );
                $i++;
            }
        }elseif($flag=='sdy'){
            $tflag=array('0'=>'总监录入','1'=>'人事录入','2'=>'人事录入');
            $data = array(1=> array('序号','月份','姓名','公司','目前直属部门','目前部门','当月直属部门','当月部门','餐补','其他补贴','录入类型','备注'));
            $sql="select
                 s.pyear , s.pmon , u.user_name as username , tdt.dept_name as tdtname
                 , td.dept_name as deptname , s.sdymeal , s.sdyother , s.flaflag 
            	 , s.remark 
            	 , u.company  , d.dept_name as dn , dt.dept_name as tdn
            from salary_sdy s
                left join user u on (s.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
        		    left join department dt on (dt.depart_x=left(d.depart_x,2))
                left join hrms h on (u.user_id=h.user_id)
            	left join salary_pay p on (p.userid=s.userid and p.pyear=s.pyear and p.pmon=s.pmon)
        		left join department td on (p.deptid=td.dept_id)
        		left join department tdt on (tdt.depart_x=left(td.depart_x,2))
                left join salary_flow f on ( s.rand_key=f.salarykey )
            where
                s.pyear='2012'
                and (s.flaflag='0' or f.sta='2')
            	and p.userid=s.userid and p.pyear=s.pyear and p.pmon=s.pmon
            order by s.flaflag , td.dept_id
            ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $data[]=array(
                    $i,$row['pyear'].'-'.$row['pmon'],$this->salaryCom[$row['company']],$row['username']
                	,$row['tdn'],$row['dn'],$row['tdtname'],$row['deptname']
                    ,$this->salaryClass->decryptDeal($row['sdymeal']),$this->salaryClass->decryptDeal($row['sdyother'])
                    ,$tflag[$row['flaflag']], $row['remark']
                );
                $i++;
            }
            //
            $sql="select
                 s.pyear , s.pmon , u.user_name as username , tdt.dept_name as tdtname
                 , td.dept_name as deptname , s.sdymeal , s.sdyother , s.flaflag , s.remark 
            		, u.company , d.dept_name as dn , dt.dept_name as tdn
            from salary_sdy s
                left join user u on (s.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
        		    left join department dt on (dt.depart_x=left(d.depart_x,2))
                left join hrms h on (u.user_id=h.user_id)
            	left join `shiyuanoa`.salary_pay p on (p.userid=s.userid and p.pyear=s.pyear and p.pmon=s.pmon)
        		left join department td on (p.deptid=td.dept_id)
        		left join department tdt on (tdt.depart_x=left(td.depart_x,2))
                left join salary_flow f on ( s.rand_key=f.salarykey )
            where
                s.pyear='2012'
                and (s.flaflag='0' or f.sta='2')
            	and p.userid=s.userid and p.pyear=s.pyear and p.pmon=s.pmon
            order by s.flaflag , td.dept_id
            ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
            	$data[]=array(
            			$i,$row['pyear'].'-'.$row['pmon'],$this->salaryCom[$row['company']],$row['username']
                	,$row['tdn'],$row['dn'],$row['tdtname'],$row['deptname']
                    ,$this->salaryClass->decryptDeal($row['sdymeal']),$this->salaryClass->decryptDeal($row['sdyother'])
                    ,$tflag[$row['flaflag']], $row['remark']
            	);
            	$i++;
            }
            //
            $sql="select
                 s.pyear , s.pmon , u.user_name as username , tdt.dept_name as tdtname
                 , td.dept_name as deptname , s.sdymeal , s.sdyother , s.flaflag , s.remark 
            		, u.company , d.dept_name as dn , dt.dept_name as tdn
            from salary_sdy s
                left join user u on (s.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
        		    left join department dt on (dt.depart_x=left(d.depart_x,2))
                left join hrms h on (u.user_id=h.user_id)
            	left join `beiruanoa`.salary_pay p on (p.userid=s.userid and p.pyear=s.pyear and p.pmon=s.pmon)
        		left join department td on (p.deptid=td.dept_id)
        		left join department tdt on (tdt.depart_x=left(td.depart_x,2))
                left join salary_flow f on ( s.rand_key=f.salarykey )
            where
                s.pyear='2012'
                and (s.flaflag='0' or f.sta='2')
            	and p.userid=s.userid and p.pyear=s.pyear and p.pmon=s.pmon
            order by s.flaflag , td.dept_id
            ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
            	$data[]=array(
            			$i,$row['pyear'].'-'.$row['pmon'],$this->salaryCom[$row['company']],$row['username']
                	,$row['tdn'],$row['dn'],$row['tdtname'],$row['deptname']
                    ,$this->salaryClass->decryptDeal($row['sdymeal']),$this->salaryClass->decryptDeal($row['sdyother'])
                    ,$tflag[$row['flaflag']], $row['remark']
            	);
            	$i++;
            }
        }elseif($flag=='dp_user'){
            $sead=faddslashes($_REQUEST['sdx']);
            $seau=faddslashes($_REQUEST['sux']);
            if($seay!=='-'){
                $sqlSch.=" and p.pyear='".$seay."' ";
            }
            if($seam!=='-'){
                $sqlSch.=" and p.pmon='".$seam."' ";
            }
            if($sead){
                $sqlSch.=" and d.dept_name like '%".$sead."%' ";
            }
            if($seau){
                $sqlSch.=" and u.user_name like '%".$seau."%' ";
            }
            //过滤离职人员
            $sqlSch.="  and ( p.nowamflag!=3 or p.nowamflag is null )  ";
            /*
            $sqlL=" select userlevel from hrms where user_id = '".$_SESSION['USER_ID']."' ";
            $resL=$this->db->get_one($sqlL);
            if(!empty($resL['userlevel'])){
                $sqlL=" and ( h.userlevel>".$resL['userlevel']." or u.user_id = '".$_SESSION['USER_ID']."')";
            }else{
                $sqlL="";
            }
             */
            global $func_limit;
            $data = array(1=> array('序号','姓名','公司','部门','发薪月份','基本工资','公积金','社保费'
                ,'季度奖','特别奖励','特别扣除','项目奖','餐补','其他补贴','奖金','其他扣除'
                ,'事假','病假','请假扣除额','税金','实发工资','账号','开户行','身份证','备注'));
            $xls->setStyle(array(4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20));
            $dppow=$this->model_dp_pow();
            if(!empty($func_limit['浏览部门'])){
                $sqlpow=" and ( p.deptid in ('". implode("','", $dppow['1'])."','" . implode("','", $dppow['2']) ."')
                    or p.userid='".$_SESSION['USER_ID']."'
                    or p.deptid in ( ".trim($func_limit['浏览部门'],',')." )
                    ) ";
            }else{
                $sqlpow=" and ( p.deptid in ('". implode("','", $dppow['1'])."','" . implode("','", $dppow['2']) ."')
                    or p.userid='".$_SESSION['USER_ID']."'
                    ) ";
            }
            $sql = "(select
                        s.rand_key , u.user_name as username , d.dept_name as deptname , s.olddept , s.comedt , s.oldarea
                        , s.amount ,  p.gjjam , p.shbam
                        , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                        , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                        , h.userlevel , p.pyear , p.pmon
                        , p.sdyam , p.holsdelam , u.company
                    from salary_pay p
                        left join salary s on ( p.userid=s.userid )
                        left join hrms h on( h.user_id=s.userid )
                        left join user u on (u.user_id=p.userid)
                        left join department d on (p.deptid=d.dept_id)
                    where p.leaveflag='0'
                        $sqlpow
                        $sqlSch  
                    order by p.id )
                    union
                    (
                    select
                        s.rand_key , u.user_name as username , d.dept_name as deptname , s.olddept , s.comedt , s.oldarea
                        , s.amount ,  p.gjjam , p.shbam
                        , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                        , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                        , h.userlevel , p.pyear , p.pmon
                        , p.sdyam , p.holsdelam , u.company
                    from `beiruanoa`.salary_pay p
                        left join salary s on ( p.userid=s.userid )
                        left join hrms h on( h.user_id=s.userid )
                        left join user u on (u.user_id=p.userid)
                        left join department d on (p.deptid=d.dept_id)
                    where p.leaveflag='0'
                        $sqlpow
                        $sqlSch  
                    order by p.id 
                    )
                    union
                    (
                    select
                        s.rand_key , u.user_name as username , d.dept_name as deptname , s.olddept , s.comedt , s.oldarea
                        , s.amount ,  p.gjjam , p.shbam
                        , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                        , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                        , h.userlevel , p.pyear , p.pmon
                        , p.sdyam , p.holsdelam , u.company
                    from `shiyuanoa`.salary_pay p
                        left join salary s on ( p.userid=s.userid )
                        left join hrms h on( h.user_id=s.userid )
                        left join user u on (u.user_id=p.userid)
                        left join department d on (p.deptid=d.dept_id)
                    where p.leaveflag='0'
                        $sqlpow
                        $sqlSch  
                    order by p.id 
                    )";
            $query=$this->db->query($sql);
            $i=1;
            while($row=$this->db->fetch_array($query)){
                $data[]=array(
                    $i,$row['username'],$this->salaryCom[$row['company']],$row['deptname'],$row['pyear'].'-'.$row['pmon'],$this->salaryClass->decryptDeal($row['baseam'])
                    ,$this->salaryClass->decryptDeal($row['gjjam']),$this->salaryClass->decryptDeal($row['shbam'])
                    ,$this->salaryClass->decryptDeal($row['floatam']),$this->salaryClass->decryptDeal($row['sperewam'])
                    ,$this->salaryClass->decryptDeal($row['spedelam']),$this->salaryClass->decryptDeal($row['proam'])
                    ,$this->salaryClass->decryptDeal($row['sdyam']),$this->salaryClass->decryptDeal($row['otheram'])
                    ,$this->salaryClass->decryptDeal($row['bonusam']),$this->salaryClass->decryptDeal($row['othdelam'])
                    ,($row['perholsdays']),($row['sickholsdays']), $this->salaryClass->decryptDeal($row['holsdelam'])
                    ,$this->salaryClass->decryptDeal($row['paycesse']),$this->salaryClass->decryptDeal($row['paytotal'])
                    ,$row['acc'],$row['accbank'],$row['idcard'],$row['remark']
                );
                $i++;
            }
        }elseif($flag=='hr_user'){
            $sead=faddslashes($_REQUEST['sdx']);
            $seau=faddslashes($_REQUEST['sux']);
            if($seay!=='-'){
                $sqlSch.=" and p.pyear='".$seay."' ";
            }
            if($seam!=='-'){
                $sqlSch.=" and p.pmon='".$seam."' ";
            }
            //过滤
            $sqlSch.="  and ( p.nowamflag!=3 or p.nowamflag is null )  ";
            
            $data = array(1=> array('序号','公司','员工号','姓名','直属部门','部门','发薪月份','基本工资','公积金','社保费'
                ,'季度奖','特别奖励','特别扣除','项目奖','餐补','其他补贴','奖金','其他扣除'
                ,'事假','病假','请假扣除','应发工资','税金','税后扣除','税后奖励','实发工资','公司公积金','公司社保费','账号','开户行','身份证','备注'));
            $xls->setStyle(array(4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27));
            $dppow=$this->model_dp_pow();
            if($comtable){
                $sql = "select
                        s.rand_key , u.user_name as username , d.dept_name as deptname , s.olddept , s.comedt , s.oldarea
                        , s.amount ,  p.gjjam , p.shbam
                        , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                        , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                        , h.userlevel , p.pyear , p.pmon , h.usercard
                        , p.sdyam , p.totalam , p.accdelam , p.accrewam  , dp.dept_name as dpname , u.company,p.cogjjam , p.coshbam
                    from ".$comtable."salary_pay p
                        left join salary s on ( p.userid=s.userid )
                        left join hrms h on( h.user_id=s.userid )
                        left join user u on (u.user_id=p.userid)
                        left join department d on (p.deptid=d.dept_id)
                        left join department dp on (left(d.depart_x,2)=dp.depart_x)
                    where p.leaveflag='0'
                        $sqlSch
                    order by p.id ";
            }else{
                $sql = "(select
                        s.rand_key , u.user_name as username , d.dept_name as deptname , s.olddept , s.comedt , s.oldarea
                        , s.amount ,  p.gjjam , p.shbam
                        , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                        , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                        , h.userlevel , p.pyear , p.pmon , h.usercard
                        , p.sdyam , p.totalam , p.accdelam , p.accrewam , dp.dept_name as dpname , u.company
                        , p.holsdelam , p.totalam,p.cogjjam , p.coshbam
                    from salary_pay p
                        left join salary s on ( p.userid=s.userid )
                        left join hrms h on( h.user_id=s.userid )
                        left join user u on (u.user_id=p.userid)
                        left join department d on (p.deptid=d.dept_id)
                        left join department dp on (left(d.depart_x,2)=dp.depart_x)
                    where p.leaveflag='0'
                        $sqlSch
                    order by p.id  
                        )union(
                        select
                        s.rand_key , u.user_name as username , d.dept_name as deptname , s.olddept , s.comedt , s.oldarea
                        , s.amount ,  p.gjjam , p.shbam
                        , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                        , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                        , h.userlevel , p.pyear , p.pmon , h.usercard
                        , p.sdyam , p.totalam , p.accdelam , p.accrewam , dp.dept_name as dpname , u.company
                        , p.holsdelam , p.totalam,p.cogjjam , p.coshbam
                    from `shiyuanoa`.salary_pay p
                        left join salary s on ( p.userid=s.userid )
                        left join hrms h on( h.user_id=s.userid )
                        left join user u on (u.user_id=p.userid)
                        left join department d on (p.deptid=d.dept_id)
                        left join department dp on (left(d.depart_x,2)=dp.depart_x)
                    where p.leaveflag='0'
                        $sqlSch
                    order by p.id  
                        )union(
                        select
                        s.rand_key , u.user_name as username , d.dept_name as deptname , s.olddept , s.comedt , s.oldarea
                        , s.amount ,  p.gjjam , p.shbam
                        , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                        , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                        , h.userlevel , p.pyear , p.pmon , h.usercard
                        , p.sdyam , p.totalam , p.accdelam , p.accrewam , dp.dept_name as dpname , u.company
                        , p.holsdelam , p.totalam,p.cogjjam , p.coshbam
                    from `beiruanoa`.salary_pay p
                        left join salary s on ( p.userid=s.userid )
                        left join hrms h on( h.user_id=s.userid )
                        left join user u on (u.user_id=p.userid)
                        left join department d on (p.deptid=d.dept_id)
                        left join department dp on (left(d.depart_x,2)=dp.depart_x)
                    where p.leaveflag='0'
                        $sqlSch
                    order by p.id  
                        )";
            }
            
            $query=$this->db->query($sql);
            $i=1;
            while($row=$this->db->fetch_array($query)){
                if($row['userlevel']=='4'||$func_limit['人事查看管理层']=='1'){
                    $data[]=array(
                        $i,$this->salaryCom[$row['company']],$row['usercard'],$row['username'],$row['dpname'],$row['deptname'],$row['pyear'].'-'.$row['pmon'],$this->salaryClass->decryptDeal($row['baseam'])
                        ,$this->salaryClass->decryptDeal($row['gjjam']),$this->salaryClass->decryptDeal($row['shbam'])
                        ,$this->salaryClass->decryptDeal($row['floatam']),$this->salaryClass->decryptDeal($row['sperewam'])
                        ,$this->salaryClass->decryptDeal($row['spedelam']),$this->salaryClass->decryptDeal($row['proam'])
                        ,$this->salaryClass->decryptDeal($row['sdyam']),$this->salaryClass->decryptDeal($row['otheram'])
                        ,$this->salaryClass->decryptDeal($row['bonusam']),$this->salaryClass->decryptDeal($row['othdelam'])
                        ,($row['perholsdays']),($row['sickholsdays'])
                    	,$this->salaryClass->decryptDeal($row['holsdelam'])
                    	,$this->salaryClass->decryptDeal($row['totalam'])
                        ,$this->salaryClass->decryptDeal($row['paycesse'])
                        ,$this->salaryClass->decryptDeal($row['accdelam']),$this->salaryClass->decryptDeal($row['accrewam'])
                        ,$this->salaryClass->decryptDeal($row['paytotal'])
                        ,$this->salaryClass->decryptDeal($row['cogjjam']),$this->salaryClass->decryptDeal($row['coshbam'])
                        ,$row['acc'],$row['accbank'],$row['idcard'],$row['remark']
                    );
                }else{
                    $data[]=array(
                        $i,$this->salaryCom[$row['company']],$row['usercard'],$row['username'],$row['dpname'],$row['deptname'],$row['pyear'].'-'.$row['pmon']
                        ,''
                        ,$this->salaryClass->decryptDeal($row['gjjam']),$this->salaryClass->decryptDeal($row['shbam'])
                        ,'',$this->salaryClass->decryptDeal($row['sperewam'])
                        ,$this->salaryClass->decryptDeal($row['spedelam']),$this->salaryClass->decryptDeal($row['proam'])
                        ,$this->salaryClass->decryptDeal($row['sdyam']),$this->salaryClass->decryptDeal($row['otheram'])
                        ,$this->salaryClass->decryptDeal($row['bonusam']),$this->salaryClass->decryptDeal($row['othdelam'])
                        ,($row['perholsdays']),($row['sickholsdays'])
                        ,'','','','','',''
                        ,$this->salaryClass->decryptDeal($row['cogjjam']),$this->salaryClass->decryptDeal($row['coshbam'])
                        ,$row['acc'],$row['accbank'],$row['idcard'],$row['remark']
                    );
                }
                $i++;
            }
        }elseif($flag=='manager'){
            $data = array(1=> array('序号','员工号','姓名','部门','基本工资'));
            $xls->setStyle(array(4));
            $sql="select
                    h.usercard , s.oldname , d.dept_name , s.amount
                  from salary s
                    left join hrms h on (s.userid=h.user_id)
                    left join department d on (s.deptid=d.dept_id)
                  where
                    s.usersta!=3
                    and h.userlevel<>4
                  order by d.dept_name , s.oldname  ";
            $query=$this->db->query($sql);
            $i=1;
            while($row=$this->db->fetch_array($query)){
                $data[]=array(
                    $i
                    ,$row['usercard']
                    ,$row['oldname']
                    ,$row['dept_name']
                    ,$this->salaryClass->decryptDeal($row['amount'])
                );
                $i++;
            }
        }elseif($flag=='ymd'){
            $data = array(1=> array('序号','员工号','姓名','部门','调整工资'));
            $xls->setStyle(array(4));
            $sql="select
                  s.username  , s.olddept , f.changeam 
                , h.usercard
            from salary_flow f
                left join salary s on (f.userid=s.userid)
		left join hrms h on (f.userid=h.user_id)
            where
                f.createuser='".$_SESSION['USER_ID']."' and f.flowname in ( '年度调薪'  )";
            $query=$this->db->query($sql);
            $i=1;
            while($row=$this->db->fetch_array($query)){
                $data[]=array(
                    $i
                    ,$row['usercard']
                    ,$row['username']
                    ,$row['olddept']
                    ,$this->salaryClass->decryptDeal($row['changeam'])
                );
                $i++;
            }
        }elseif($flag=='leavemanager'){
            $seapy = $_REQUEST['sy']?$_REQUEST['sy']:$this->nowy;
            $seapm = $_REQUEST['sm']?$_REQUEST['sm']:$this->nowm;
            if($seapy&&$seapy!='-'){
                $sqlSch.=" and year(s.leavedt) = '".$seapy."' ";
            }
            if($seapm&&$seapm!='-'){
                $sqlSch.=" and month(s.leavedt) = '".$seapm."' ";
            }
            $data = array(1=> array('公司','员工号','姓名','直属部门','部门','职务','类型' ,'入职日期','离职日期'
                ,'基本工资','事假'
                ,'病假','事病假扣除','实际工资小计','各项补贴','社保费','公积金','其他扣除','个人所得税'
                ,'离职福利','实发离职工资','账号','开户行','身份证','备注'));
            $xls->setStyle(array(7,8,9,10,11,12,13,14,15,16,17,18,19,20));
            $sql="select
                    s.rand_key , u1.user_name as username , d.dept_name as olddept , s.leavedt
                    , s.leavecreatedt , u.user_name , s.usersta
                    , h.expflag 
                    , u1.company , u1.salarycom 
                    , year(s.leavedt) as py , month(s.leavedt) as pm , s.comedt 
                    , s.accbank , s.acc , s.userid , j.name as jname , h.usercard ,d.pdeptname ,s.idcard
                from salary s
                    left join user u on (s.leavecreator=u.user_id)
                    left join user u1 on (u1.user_id=s.userid)
                    left join hrms h on (s.userid=h.user_id)
                    left join department d on (u1.dept_id=d.dept_id)
                    left join user_jobs j on (j.id=u1.jobs_id)
                where
                    1
                    $sqlSch
                order by s.usersta , s.leavecreatedt desc 
                ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $res[$row['userid']]=array(
                    'username'=>$row['username']
                    ,'com'=>$this->salaryCom[$row['company']]
                    ,'jname'=>$row['jname']
                    ,'expflag'=>$row['expflag']
                    ,'cdt'=>date('Ymd',strtotime($row['comedt']))
                    ,'ldt'=>date('Ymd',strtotime($row['leavedt']))
                    ,'lcdt'=>$row['leavecreatedt']
                    ,'lcu'=>$row['user_name']
                    ,'py'=>$row['py']
                    ,'pm'=> $row['pm']
                    ,'acc'=>$row['acc']
                    ,'accbank'=> $row['accbank']
                    ,'rk'=> $row['rand_key']
                    ,'comcode'=> $row['company']
                    ,'usercard'=> $row['usercard']
                    ,'pdept'=> $row['pdeptname']
                    ,'idcard'=> $row['idcard']
                    
                );

            }
            if(!empty($res)){
                foreach($res as $key=>$val){
                    $comtable = $this->get_com_sql($val['comcode']);
                    $sp=$this->model_get_pay(
                            array('userid'=>$key 
                                , 'pyear'=>$val['py']
                                , 'pmon'=>$val['pm']
                            ), 
                            array(
                                'BaseAm'
                                ,'BaseNowAm'
                                ,'PerHolsDays'
                                ,'SickHolsDays'
                                ,'HolsDelAm'
                                ,'SpeRewAm'
                                ,'SpeDelAm'
                                ,'ShbAm'
                                ,'GjjAm'
                                ,'PayCesse'
                                ,'AccRewAm'
                                ,'PayTotal'
                                ,'SalaryDept'
                                ,'Remark'
                                ,'ID'
                            )
                            ,$comtable);
                    $data[] =array(
        $val['com'] , $val['usercard'] , $val['username'], $val['pdept'], $sp['SalaryDept'] , $val['jname'] , $this->expflag[$val['expflag']]
        , $val['cdt'], $val['ldt'], $this->salaryClass->decryptDeal($sp['BaseAm']), $sp['PerHolsDays'] , $sp['SickHolsDays'], $this->salaryClass->decryptDeal($sp['HolsDelAm'])
        , round($this->salaryClass->decryptDeal($sp['BaseNowAm'])-$this->salaryClass->decryptDeal($sp['HolsDelAm']),2)
        , $this->salaryClass->decryptDeal($sp['SpeRewAm'])
        , $this->salaryClass->decryptDeal($sp['ShbAm'])
        , $this->salaryClass->decryptDeal($sp['GjjAm'])
        , $this->salaryClass->decryptDeal($sp['SpeDelAm'])
        , $this->salaryClass->decryptDeal($sp['PayCesse'])
        , $this->salaryClass->decryptDeal($sp['AccRewAm'])
        , $this->salaryClass->decryptDeal($sp['PayTotal'])
        ,$val['acc']
        ,$val['accbank']
        ,$val['idcard']
        ,$sp['Remark']
                             
                    );
                    $i++;
                }
            }
        }
        $xls->addArray($data);
        $xls->generateXML(time());
    }
    /**
     * 年终奖计算
     */
    function model_hr_exp_count($ckt){
        set_time_limit(600);
        $str='';
        $infoE=array();
        try{
            $excelfilename='attachment/xls_model/'.$ckt.".xls";
            if(empty ($_FILES["ctr_file"]["tmp_name"])){
                $str='<tr><td colspan="10">请导入年终奖数据信息！</td></tr>';
            }elseif (!move_uploaded_file( $_FILES["ctr_file"]["tmp_name"], $excelfilename) ){
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
                if(!in_array('名字', $excelFields)||!in_array('员工号', $excelFields)
                        ||!in_array('工资', $excelFields)||!in_array('年终奖', $excelFields))
                {
                    throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                }
                if(count($excelArr)&&!empty($excelArr)){
                    foreach($excelArr['员工号'] as $key=>$val ){
                        $infoE[$val]['name']=$excelArr['名字'][$key];
                        $infoE[$val]['bam']=$excelArr['工资'][$key];
                        $infoE[$val]['yam']=$excelArr['年终奖'][$key];
                    }
                }
                if(count($infoE)){
                    $totalA=array('pro'=>0);
                    $i=1;
                    foreach($infoE as $key=>$val){
                        if(empty($key)){
                            continue;
                        }
                        $bam=round($val['bam'],2);
                        $yam=round($val['yam'],2);
                        $kam=$this->salaryClass->cesseDealYeb($yam, $bam, '2000');
                        $pam=round($yam-$kam,2);
                        $str.='<tr>
                                <td>'.$i.'</td>
                                <td>'.$key.'</td>
                                <td>'.$val['name'].'</td>
                                <td>'.$bam.'</td>
                                <td>'.$yam.'</td>
                                <td>'.$kam.'</td>
                                <td>'.$pam.'</td>
                            </tr>';
                        $i++;
                    }
                }
            }
        }catch(Exception $e){
            $str='<tr><td colspan="10">导入数据错误！'.$e->getMessage().'</td></tr>';
        }
        return $str;
    }
    /**
     * 导出
     */
    function model_hr_exp_count_out($ckt){
        set_time_limit(600);
        $str='';
        $infoE=array();
        include( WEB_TOR . 'includes/classes/excel_out_class.php');
        $xls = new ExcelXML('gb2312', false, 'My Test Sheet');
        $data = array(1=> array ('序号', '员工号', '名字','工资'
                ,'年终奖', '扣税', '实发金额')
                );
        $xls->setStyle(array(3,4,5,6));
        try{
            $excelfilename='attachment/xls_model/'.$ckt.".xls";
            //读取excel信息
            include('includes/classes/excel.php');
            $excel = Excel::getInstance();
            $excel->setFile(WEB_TOR.$excelfilename);
            $excel->Open();
            $excel->setSheet();
            $excelFields = $excel->getFields();
            $excelArr=$excel->getAllData();
            $excel->Close();
            if(!in_array('名字', $excelFields)||!in_array('员工号', $excelFields)
                    ||!in_array('工资', $excelFields)||!in_array('年终奖', $excelFields))
            {
                throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
            }
            if(count($excelArr)&&!empty($excelArr)){
                foreach($excelArr['员工号'] as $key=>$val ){
                    $infoE[$val]['name']=$excelArr['名字'][$key];
                    $infoE[$val]['bam']=$excelArr['工资'][$key];
                    $infoE[$val]['yam']=$excelArr['年终奖'][$key];
                }
            }
            if(count($infoE)){
                $totalA=array('pro'=>0);
                $i=1;
                foreach($infoE as $key=>$val){
                    if(empty($key)){
                        continue;
                    }
                    $bam=round($val['bam'],2);
                    $yam=round($val['yam'],2);
                    $kam=$this->salaryClass->cesseDealYeb($yam, $bam, '2000');
                    $pam=round($yam-$kam,2);
                    $data[]=array(
                        $i
                        ,$key
                        ,$val['name']
                        ,$bam
                        ,$yam
                        ,$kam
                        ,$pam
                    );
                    $i++;
                }
            }
        }catch(Exception $e){
            $data[]=array('<tr><td colspan="10">导入数据错误！'.$e->getMessage().'</td></tr>');
        }
        $xls->addArray($data);
        $xls->generateXML(time());
    }
/**
 *工资数据源
 * @global type $func_limit
 * @param type $flag 不存在脚本限制 true 
 * @param type $sqlflag 脚本限制
 * @param type $fnsta 财务 false
 * @param type $dpsta 部门 false
 * @param type $outtype 输出格式 list ; xls 
 * @param type $outlist 输出内容  hr_div  人事专区  dp_detail 部门查看
 * @return type 
 */
    function model_hr_user($flag=true,$sqlflag='',$fnsta=false,$dpsta=false,$outtype='list',$outlist='') {
        global $func_limit;//权限
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $seapy = $_GET['seapy']?$_GET['seapy']:$this->nowy;
        $seapm = $_GET['seapm']?$_GET['seapm']:$this->nowm;
        $seadept = $_REQUEST['seadept'];
        $seaname = $_REQUEST['seaname'];
        $seacom = $_REQUEST['seacom'];
        $seaerrs = $_REQUEST['seaerrs'];
        
        $comtable = $this->get_com_sql($seacom);
        $sqlSch = '';
        $fnArr=array();
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        if($seapy&&$seapy!='-'){
            $comsql.=" and p.pyear='".$seapy."' ";
            $sqlSch.=" and p.pyear='".$seapy."' ";
        }
        if($seapm&&$seapm!='-'){
            $comsql.=" and p.pmon='".$seapm."' ";
            $sqlSch.=" and p.pmon='".$seapm."' ";
        }
        if($seadept&&$fnsta&&!$dpsta){
            $sqlSch.=" and s.olddept like '%".$seadept."%' ";
        }elseif($seadept){
            $sqlSch.=" and d.dept_name like '%".$seadept."%' ";
        }
        if($seaname){
            $sqlSch.=" and ( s.username like '%".$seaname."%' or s.oldname like '%".$seaname."%' ) ";
        }
        if($flag==false){
            $sqlSch.=$sqlflag;
        }
        //均过滤离职人员数据 + 过滤额外人员
        $comsql.="  and ( p.nowamflag!=3 or p.nowamflag is null ) ";
        $sqlSch.="  and ( p.nowamflag!=3 or p.nowamflag is null ) ";
        
        if($fnsta){
            $comsql.=" and ( p.comflag=1 or p.baseam not in ('mKyYBwAYs6OhZVIyCcao0A==','rayq0Lssv8erWaEbiLsxCg==') ) ";
            $sqlSch.=" and ( p.comflag=1 or p.baseam not in ('mKyYBwAYs6OhZVIyCcao0A==','rayq0Lssv8erWaEbiLsxCg==') ) ";
        }
        if($dpsta){
            $comsql.=" and p.comflag=1  ";
            $sqlSch.=" and p.comflag=1  ";
        }
        //分页
        $start = $limit * $page - $limit;
        //总数
        if(!empty($comtable)){
            $comsqlc=$comtable."salary_pay p";
            $comsqls=$comtable."salary_pay p";
            $comsqlt = " and p.usercom = '".$seacom."' ";
        }elseif($fnsta==true&&$dpsta==false){
            $comsqlc="salary_pay p";
            $comsqls="salary_pay p";
        }else{
            $comsqlc="( (select     
                        p.userid ,p.leaveflag , p.pyear , p.pmon , p.deptid , p.nowamflag , p.comflag , p.usercom , p.baseam
                     from salary_pay p 
                     where  1 ".$comsql."
                    )union(
                    select     
                                      p.userid ,p.leaveflag , p.pyear , p.pmon , p.deptid , p.nowamflag , p.comflag , p.usercom , p.baseam
                     from `beiruanoa`.salary_pay p 
                     where  1 ".$comsql."
                    )union(
                    select     
                                     p.userid ,p.leaveflag , p.pyear , p.pmon , p.deptid , p.nowamflag , p.comflag , p.usercom , p.baseam
                     from `shiyuanoa`.salary_pay p 
                     where  1 ".$comsql."
                    ) ) p";
            $comsqls="( (select     
                                 p.gjjam , p.shbam
                                , p.remark , p.baseam , p.floatam , p.proam , p.otheram
                                , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                                , p.pyear , p.pmon
                                , p.sdyam , p.cessebase , p.id 
                                , p.cogjjam , p.coshbam , p.prepaream , p.handicapam , p.manageam
                                , p.userid ,p.leaveflag , p.totalam , p.accrewam , p.accdelam , p.expflag , p.deptid , p.holsdelam
                                 , p.nowamflag  , p.comflag , p.usercom
                                 from salary_pay p 
                                 where  1 ".$comsql."
                )union(
                        select     
                                 p.gjjam , p.shbam
                                , p.remark , p.baseam , p.floatam , p.proam , p.otheram
                                , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                                , p.pyear , p.pmon
                                , p.sdyam , p.cessebase , p.id 
                                , p.cogjjam , p.coshbam , p.prepaream , p.handicapam , p.manageam
                                , p.userid ,p.leaveflag , p.totalam , p.accrewam , p.accdelam , p.expflag , p.deptid , p.holsdelam
                                 , p.nowamflag  , p.comflag , p.usercom
                         from `beiruanoa`.salary_pay p 
                         where  1 ".$comsql."
                )union(
                        select     
                                 p.gjjam , p.shbam
                                , p.remark , p.baseam , p.floatam , p.proam , p.otheram
                                , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                                , p.pyear , p.pmon
                                , p.sdyam , p.cessebase , p.id 
                                , p.cogjjam , p.coshbam , p.prepaream , p.handicapam , p.manageam
                                , p.userid ,p.leaveflag , p.totalam , p.accrewam , p.accdelam , p.expflag , p.deptid , p.holsdelam
                                 , p.nowamflag  , p.comflag , p.usercom
                         from `shiyuanoa`.salary_pay p 
                 where  1 ".$comsql." 
                ) order by userid , pyear , pmon , leaveflag ) p ";
        }
        //统一主数据读取源
        $mailSql=" select * from ( (select s.id ,
                    s.rand_key , s.oldname as username , d.dept_name as deptname , s.olddept , s.comedt , s.oldarea
                    , s.amount ,  p.gjjam , p.shbam
                    , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                    , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                    , h.userlevel , p.pyear , p.pmon
                    , p.sdyam , p.cessebase , p.id as pid , p.expflag
                    , p.cogjjam , p.coshbam , p.prepaream , p.handicapam , p.manageam
                    , p.usercom as company , u.salarycom , p.totalam , p.accrewam , p.accdelam , p.holsdelam
                    , h.usercard , d.pdeptname  , p.comflag
                from  salary_pay p 
                    left join salary s on ( p.userid=s.userid )
                    left join hrms h on( h.user_id=s.userid )
                    left join user u on (u.user_id=p.userid)
                    left join department d on (p.deptid=d.dept_id)
                where p.leaveflag='0' ".$comsql.$comsqlt."  $sqlSch    _limit_  )
                union(select s.id ,
                    s.rand_key , s.oldname as username , d.dept_name as deptname , s.olddept , s.comedt , s.oldarea
                    , s.amount ,  p.gjjam , p.shbam
                    , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                    , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                    , h.userlevel , p.pyear , p.pmon
                    , p.sdyam , p.cessebase , p.id as pid , p.expflag
                    , p.cogjjam , p.coshbam , p.prepaream , p.handicapam , p.manageam
                    , p.usercom as company , u.salarycom , p.totalam , p.accrewam , p.accdelam , p.holsdelam
                    , h.usercard , d.pdeptname  , p.comflag
                from  `shiyuanoa`.salary_pay p 
                    left join salary s on ( p.userid=s.userid )
                    left join hrms h on( h.user_id=s.userid )
                    left join user u on (u.user_id=p.userid)
                    left join department d on (p.deptid=d.dept_id)
                where p.leaveflag='0' ".$comsql.$comsqlt."  $sqlSch    _limit_  )
                union (select s.id ,
                    s.rand_key , s.oldname as username , d.dept_name as deptname , s.olddept , s.comedt , s.oldarea
                    , s.amount ,  p.gjjam , p.shbam
                    , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                    , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                    , h.userlevel , p.pyear , p.pmon
                    , p.sdyam , p.cessebase , p.id as pid , p.expflag
                    , p.cogjjam , p.coshbam , p.prepaream , p.handicapam , p.manageam
                    , p.usercom as company , u.salarycom , p.totalam , p.accrewam , p.accdelam , p.holsdelam
                    , h.usercard , d.pdeptname  , p.comflag
                from  `beiruanoa`.salary_pay p 
                    left join salary s on ( p.userid=s.userid )
                    left join hrms h on( h.user_id=s.userid )
                    left join user u on (u.user_id=p.userid)
                    left join department d on (p.deptid=d.dept_id)
                where p.leaveflag='0' ".$comsql.$comsqlt."  $sqlSch   _limit_  ) ) s ";
        if($outtype=='list'){
            $sql = "select count(*)
                from ".$comsqlc."
                    left join salary s on ( p.userid=s.userid  )
                    left join hrms h on( h.user_id=s.userid )
                    left join user u on (u.user_id=p.userid)
                    left join department d on (p.deptid=d.dept_id)
                where p.leaveflag='0' $sqlSch ";
            $rs = $this->db->get_one($sql);

            $count = $rs['count(*)'];
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages)
                $page = $total_pages;
            //标识
            if($dpsta){
                $sql="select f.flowname , p.id as pid
                    from salary_flow f
                        left join salary_pay p on (f.userid=p.userid and p.pyear=f.pyear and p.pmon=f.pmon )
                        left join hrms h on (h.user_id=f.userid)
                    where f.pyear='".$seapy."' and f.pmon='".$seapm."' and f.sta='2'
                        $sqlflag ";
                $query=$this->db->query($sql);
                while($row=$this->db->fetch_array($query)){
                    $fnArr[$row['pid']][]=$row['flowname'];
                }
            }
            //分页
            $mailSql = str_replace( '_limit_' ,  " limit $start , $limit  "  ,   $mailSql );
            $sql = $mailSql."
                order by $sidx $sord
                limit $start , $limit ";
            $query = $this->db->query($sql);
            $i = 0;
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;
            while ($row = $this->db->fetch_array($query)) {
                if(!empty($row['salarycom'])){
                    $comtab=$this->salaryCom[$row['salarycom']];
                }else{
                    $comtab=$this->salaryCom[$row['company']];
                }
                if($dpsta){//部门查询
                    if(!empty($fnArr[$row['pid']])){
                        if(in_array('非年度调薪-普通',$fnArr[$row['pid']])||in_array('非年度调薪-经理',$fnArr[$row['pid']])
                            ||in_array('非年度调薪-总监',$fnArr[$row['pid']])||in_array('非年度调薪-副总',$fnArr[$row['pid']]))
                        {
                            $baseam='<font color="red">'.$this->salaryClass->decryptDeal($row['baseam']).'</font>';
                        }else{
                            $baseam=$this->salaryClass->decryptDeal($row['baseam']);
                        }
                    }else{
                        $baseam=$this->salaryClass->decryptDeal($row['baseam']);
                    }
                    $sperewam=$this->salaryClass->decryptDeal($row['sperewam']);//特殊奖励
                    $sperewam=$sperewam?'<font color="red">'.$sperewam.'</font>':$sperewam;
                    $spedelam=$this->salaryClass->decryptDeal($row['spedelam']);//特殊扣除
                    $spedelam=$spedelam?'<font color="red">'.$spedelam.'</font>':$spedelam;
                    $proam=$this->salaryClass->decryptDeal($row['proam']);//项目奖
                    $proam=$proam?'<font color="red">'.$proam.'</font>':$proam;
                    $floatam=$this->salaryClass->decryptDeal($row['floatam']);//季度奖
                    $floatam=$floatam?'<font color="red">'.$floatam.'</font>':$floatam;
                    $sdyam=$this->salaryClass->decryptDeal($row['sdyam']);//餐补
                    $sdyam=$sdyam?'<font color="red">'.$sdyam.'</font>':$sdyam;
                    $otheram=$this->salaryClass->decryptDeal($row['otheram']);//其他补贴
                    $otheram=$otheram?'<font color="red">'.$otheram.'</font>':$otheram;
                    $bosam=$this->salaryClass->decryptDeal($row['bonusam']);//奖励
                    $bosam=$bosam?'<font color="red">'.$bosam.'</font>':$bosam;
                    $othdelam=$this->salaryClass->decryptDeal($row['othdelam']);//其他扣除
                    $othdelam=$othdelam?'<font color="red">'.$othdelam.'</font>':$othdelam;
                    $tmp=array($row['rand_key'], $row['username'],$comtab, $row['deptname'],$row['pyear'].'-'.$row['pmon']
                                    , $baseam
                                    , $this->salaryClass->decryptDeal($row['gjjam'])
                                    , $this->salaryClass->decryptDeal($row['shbam'])
                                    , $floatam
                                    , $sperewam , $spedelam
                                    , $proam , $sdyam
                                    , $otheram 
                                    , $bosam, $othdelam
                                    , $row['perholsdays'], $row['sickholsdays'], $this->salaryClass->decryptDeal($row['holsdelam'])
                                    , $this->salaryClass->decryptDeal($row['paycesse']), $this->salaryClass->decryptDeal($row['paytotal'])
                                    , $this->salaryClass->decryptDeal($row['cogjjam']), $this->salaryClass->decryptDeal($row['coshbam'])
                                    , $row['comedt'], $row['oldarea']
                                    , $row['userlevel']
                                    , $row['acc'], $row['accbank'], $row['idcard'], $row['remark']
                                    , $row['email'], $this->userSta[$row['usersta']]
                            );
                }elseif($fnsta){//财务
                    $tmp=array($row['pid'],'', $row['username'], $row['olddept'],$row['pyear'].'-'.$row['pmon']
                                    , $this->salaryClass->decryptDeal($row['baseam'])
                                    , $this->salaryClass->decryptDeal($row['gjjam'])
                                    , $this->salaryClass->decryptDeal($row['shbam']), $this->salaryClass->decryptDeal($row['floatam'])
                                    , $this->salaryClass->decryptDeal($row['sperewam']), $this->salaryClass->decryptDeal($row['spedelam'])
                                    , $this->salaryClass->decryptDeal($row['proam'])+$this->salaryClass->decryptDeal($row['sdyam'])
                                    , $this->salaryClass->decryptDeal($row['otheram'])
                                    , $this->salaryClass->decryptDeal($row['bonusam']), $this->salaryClass->decryptDeal($row['othdelam'])
                                    , $row['perholsdays'], $row['sickholsdays']
                                    , $this->salaryClass->decryptDeal($row['paycesse']), $this->salaryClass->decryptDeal($row['paytotal'])
                                    , $row['comedt'], $row['oldarea']
                                    , $row['userlevel']
                                    , $row['acc'], $row['accbank'], $row['idcard'], $row['remark']
                                    , $row['email'], $this->userSta[$row['usersta']],$row['cessebase']
                            );
                }elseif($row['userlevel']=='4'){//人事普通员工
                    if($seaerrs=='y'){
                        $totalerr=round($this->salaryClass->decryptDeal($row['totalam'])-$this->salaryClass->decryptDeal($row['gjjam'])-$this->salaryClass->decryptDeal($row['shbam']));

                        if(!empty($totalerr)&&$totalerr>0){
                            continue;
                        }
                    }

                    $totalarr['gjj']+=$this->salaryClass->decryptDeal($row['gjjam']);
                    $totalarr['shb']+=$this->salaryClass->decryptDeal($row['shbam']);
                    $tmp=array($row['pid'], '-',$row['usercard'],$row['username'],$comtab, $row['deptname'],$this->expflag[$row['expflag']],$row['pyear'].'-'.$row['pmon']
                                    , $this->salaryClass->decryptDeal($row['baseam'])
                                    , $this->salaryClass->decryptDeal($row['gjjam'])
                                    , $this->salaryClass->decryptDeal($row['shbam']), $this->salaryClass->decryptDeal($row['floatam'])
                                    , $this->salaryClass->decryptDeal($row['sperewam']), $this->salaryClass->decryptDeal($row['spedelam'])
                                    , $this->salaryClass->decryptDeal($row['proam']), $this->salaryClass->decryptDeal($row['sdyam'])
                                    , $this->salaryClass->decryptDeal($row['otheram'])
                                    , $this->salaryClass->decryptDeal($row['bonusam']), $this->salaryClass->decryptDeal($row['othdelam'])
                                    , $row['perholsdays'], $row['sickholsdays'], $this->salaryClass->decryptDeal($row['holsdelam'])
                                    , round($this->salaryClass->decryptDeal($row['totalam'])-$this->salaryClass->decryptDeal($row['gjjam'])-$this->salaryClass->decryptDeal($row['shbam']),2)
                                    , $this->salaryClass->decryptDeal($row['paycesse'])
                                    , $this->salaryClass->decryptDeal($row['accdelam']), $this->salaryClass->decryptDeal($row['accrewam'])
                                    , $this->salaryClass->decryptDeal($row['paytotal'])
                                    , $this->salaryClass->decryptDeal($row['cogjjam']), $this->salaryClass->decryptDeal($row['coshbam'])
                                    , $this->salaryClass->decryptDeal($row['prepaream']), $this->salaryClass->decryptDeal($row['handicapam'])
                                    , $this->salaryClass->decryptDeal($row['manageam'])
                                    , $row['comedt'], $row['oldarea']
                                    , $row['userlevel']
                                    , $row['acc'], $row['accbank'], $row['idcard'], $row['remark']
                                    , $row['email'], $this->userSta[$row['usersta']]
                            );
                }else{//人事管理层
                    if($seaerrs=='y'){
                        $totalerr=round($this->salaryClass->decryptDeal($row['totalam'])-$this->salaryClass->decryptDeal($row['gjjam'])-$this->salaryClass->decryptDeal($row['shbam']));

                        if(!empty($totalerr)&&$totalerr>0){
                            continue;
                        }

                    }
                    $totalarr['gjj']+=$this->salaryClass->decryptDeal($row['gjjam']);
                    $totalarr['shb']+=$this->salaryClass->decryptDeal($row['shbam']);
                    if($func_limit['人事查看管理层']=='1'){
                        $tmp=array($row['pid'], '-',$row['usercard'],$row['username'],$comtab, $row['deptname'],$this->expflag[$row['expflag']],$row['pyear'].'-'.$row['pmon']
                                    , $this->salaryClass->decryptDeal($row['baseam'])
                                    , $this->salaryClass->decryptDeal($row['gjjam'])
                                    , $this->salaryClass->decryptDeal($row['shbam']), $this->salaryClass->decryptDeal($row['floatam'])
                                    , $this->salaryClass->decryptDeal($row['sperewam']), $this->salaryClass->decryptDeal($row['spedelam'])
                                    , $this->salaryClass->decryptDeal($row['proam']), $this->salaryClass->decryptDeal($row['sdyam'])
                                    , $this->salaryClass->decryptDeal($row['otheram'])
                                    , $this->salaryClass->decryptDeal($row['bonusam']), $this->salaryClass->decryptDeal($row['othdelam'])
                                    , $row['perholsdays'], $row['sickholsdays'], $this->salaryClass->decryptDeal($row['holsdelam'])
                                    , round($this->salaryClass->decryptDeal($row['totalam'])-$this->salaryClass->decryptDeal($row['gjjam'])-$this->salaryClass->decryptDeal($row['shbam']),2)
                                    , $this->salaryClass->decryptDeal($row['paycesse'])
                                    , $this->salaryClass->decryptDeal($row['accdelam']), $this->salaryClass->decryptDeal($row['accrewam'])
                                    , $this->salaryClass->decryptDeal($row['paytotal'])
                                    , $this->salaryClass->decryptDeal($row['cogjjam']), $this->salaryClass->decryptDeal($row['coshbam'])
                                    , $this->salaryClass->decryptDeal($row['prepaream']), $this->salaryClass->decryptDeal($row['handicapam'])
                                    , $this->salaryClass->decryptDeal($row['manageam'])
                                    , $row['comedt'], $row['oldarea']
                                    , $row['userlevel']
                                    , $row['acc'], $row['accbank'], $row['idcard'], $row['remark']
                                    , $row['email'], $this->userSta[$row['usersta']]
                            );
                    }else{
                        $tmp=array($row['pid'],'-',$row['usercard'], $row['username'],$comtab, $row['deptname'],$this->expflag[$row['expflag']],$row['pyear'].'-'.$row['pmon']
                                    , '-'
                                    , $this->salaryClass->decryptDeal($row['gjjam'])
                                    , $this->salaryClass->decryptDeal($row['shbam']), $this->salaryClass->decryptDeal($row['floatam'])
                                    , $this->salaryClass->decryptDeal($row['sperewam']), $this->salaryClass->decryptDeal($row['spedelam'])
                                    , '-', '-', '-'
                                    , '-', '-'
                                    , '-', '-'
                                    , '-', '-'
                                    , '-', '-'
                                    , '-', '-'
                                    , '-', '-'
                                    , '-', '-'
                                    ,'-'
                                    , $row['comedt'], $row['oldarea']
                                    , $row['userlevel']
                                    , $row['acc'], $row['accbank'], $row['idcard'], $row['remark']
                                    , $row['email'], $this->userSta[$row['usersta']]
                            );
                    }

                }
                $responce->rows[$i]['id'] = $row['userid'];
                $responce->rows[$i]['cell'] = un_iconv($tmp);
                $i++;
            }
            if($fnsta==false&&$dpsta==false){
                $responce->userdata['amount'] = 'total:';
                $responce->userdata['gjjam'] = $this->salaryClass->cfv($totalarr['gjj']);
                $responce->userdata['shbam'] = $this->salaryClass->cfv($totalarr['shb']);
            }
            $this->globalUtil->insertOperateLog('工资人事管理', 'salary', '显示工资信息', '成功', $sql);
            return $responce;
        }elseif($outtype=='xls'){
            $res=array();
            $mailSql = str_replace( '_limit_' ,  ""  ,   $mailSql );
            $sql = $mailSql;
            $query = $this->db->query($sql);
            if($outlist=='hr_div'){
                while ($row = $this->db->fetch_array($query)) {
                    $total=$this->salaryClass->decryptDeal($row['totalam']);
                    $shb=$this->salaryClass->decryptDeal($row['shbam']);
                    $gjj=$this->salaryClass->decryptDeal($row['gjjam']);
                    $coshb=$this->salaryClass->decryptDeal($row['coshbam']);
                    $cogjj=$this->salaryClass->decryptDeal($row['cogjjam']);
                    $pre=$this->salaryClass->decryptDeal($row['prepaream']);
                    $han=$this->salaryClass->decryptDeal($row['handicapam']);
                    $man=$this->salaryClass->decryptDeal($row['manageam']);
                    $res[]=un_iconv(array(
                        $row['usercard'],$row['pyear'].'-'.$row['pmon'],$row['username'], $row['deptname']
                        , $this->salaryClass->decryptDeal($row['baseam'])
                        , $this->salaryClass->decryptDeal($row['proam'])
                        , $this->salaryClass->decryptDeal($row['sdyam'])
                        , $this->salaryClass->decryptDeal($row['otheram'])
                        , $this->salaryClass->decryptDeal($row['spedelam'])
                        , $this->salaryClass->decryptDeal($row['sperewam'])
                        , $row['perholsdays'], $row['sickholsdays']
                        , $this->salaryClass->decryptDeal($row['holsdelam'])
                        //, $this->salaryClass->decryptDeal($row['othdelam'])没用专区
                        , $total
                        , $shb
                        , $gjj
                        , $this->salaryClass->cfv($total-$shb-$gjj)
                        , $this->salaryClass->decryptDeal($row['paycesse'])
                        , $this->salaryClass->decryptDeal($row['paytotal'])
                        , $coshb
                        , $cogjj
                        , $pre, $han
                        , $man
                        , $this->salaryClass->cfv($total+$coshb+$cogjj+$pre+$han+$man)
                        , $row['remark']
                        , $row['comedt']
                    ));
                }
                //专区增加额外人员 $sqlflag=" and ( p.deptid in ('". implode("','", $this->divDept) ."') ) ";
                $extsql="select
                    s.rand_key as sid , s.oldname as username , p.salarydept as deptname , p.deptid
                    ,  p.gjjam , p.shbam
                    , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                    , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                    , p.pyear , p.pmon
                    , p.sdyam , p.cessebase , p.id as pid , p.expflag
                    , p.cogjjam , p.coshbam , p.prepaream , p.handicapam , p.manageam
                    , p.jfcom as company , p.totalam , p.accrewam , p.accdelam , p.holsdelam
                    , p.comflag , p.usercom
                from  salary s 
                    left join ( (select * from salary_pay where comflag=0 )
                    union (select * from `shiyuanoa`.salary_pay where comflag=0 )
                    union (select * from `beiruanoa`.salary_pay where comflag=0 )
                    ) p on ( s.userid=p.userid  and p.pyear='".$seapy."'  and p.pmon='".$seapm."'  )
                where s.comflag=0  and p.id is not null and ( p.deptid in ('". implode("','", $this->divDept) ."') )";
                $query=$this->db->query($extsql);
                while ($row = $this->db->fetch_array($query)) {
                    $total=$this->salaryClass->decryptDeal($row['totalam']);
                    $shb=$this->salaryClass->decryptDeal($row['shbam']);
                    $gjj=$this->salaryClass->decryptDeal($row['gjjam']);
                    $coshb=$this->salaryClass->decryptDeal($row['coshbam']);
                    $cogjj=$this->salaryClass->decryptDeal($row['cogjjam']);
                    $pre=$this->salaryClass->decryptDeal($row['prepaream']);
                    $han=$this->salaryClass->decryptDeal($row['handicapam']);
                    $man=$this->salaryClass->decryptDeal($row['manageam']);
                    $res[]=un_iconv(array(
                        '额外人员',$row['pyear'].'-'.$row['pmon'],$row['username'], $row['deptname']
                        , $this->salaryClass->decryptDeal($row['baseam'])
                        , ''
                        , ''
                        , ''
                        , ''
                        , ''
                        , '', ''
                        , ''
                        , $total
                        , $shb
                        , $gjj
                        , $this->salaryClass->cfv($total-$shb-$gjj)
                        , $this->salaryClass->decryptDeal($row['paycesse'])
                        , $this->salaryClass->decryptDeal($row['paytotal'])
                        , $coshb
                        , $cogjj
                        , $pre, $han
                        , $man
                        , $this->salaryClass->cfv($total+$coshb+$cogjj+$pre+$han+$man)
                        , $row['remark']
                        , ''
                    ));
                }
                
            }elseif($outlist=='dp_detail'){
                while ($row = $this->db->fetch_array($query)) {
                    $total=$this->salaryClass->decryptDeal($row['totalam']);
                    $shb=$this->salaryClass->decryptDeal($row['shbam']);
                    $gjj=$this->salaryClass->decryptDeal($row['gjjam']);
                    $coshb=$this->salaryClass->decryptDeal($row['coshbam']);
                    $cogjj=$this->salaryClass->decryptDeal($row['cogjjam']);
                    $pre=$this->salaryClass->decryptDeal($row['prepaream']);
                    $han=$this->salaryClass->decryptDeal($row['handicapam']);
                    $man=$this->salaryClass->decryptDeal($row['manageam']);
                    $res[]=un_iconv(array(
                        $row['usercard'],$row['pyear'],$row['pmon'],$row['username'], $this->salaryCom[$row['company']]
                        , $row['pdeptname'], $row['deptname']
                        , $this->salaryClass->decryptDeal($row['baseam'])
                        , $this->salaryClass->decryptDeal($row['proam'])
                        , $this->salaryClass->decryptDeal($row['floatam'])
                        , $this->salaryClass->decryptDeal($row['sperewam'])
                        , $this->salaryClass->decryptDeal($row['spedelam'])
                        , $this->salaryClass->decryptDeal($row['sdyam'])
                        , $this->salaryClass->decryptDeal($row['otheram'])
                        , $this->salaryClass->decryptDeal($row['bonusam'])
                        , $this->salaryClass->decryptDeal($row['othdelam'])
                        , $row['perholsdays'], $row['sickholsdays']
                        , $this->salaryClass->decryptDeal($row['holsdelam'])
                        , $total
                        , $shb
                        , $gjj
                        , $this->salaryClass->cfv($total-$shb-$gjj)
                        , $this->salaryClass->decryptDeal($row['paycesse'])
                        , $this->salaryClass->decryptDeal($row['paytotal'])
                        , $coshb
                        , $cogjj
                        , $pre, $han
                        , $man
                        , $row['remark']
                    ));
                }
            }elseif($outlist=='hr_detail'){
                while ($row = $this->db->fetch_array($query)) {
                    $total=$this->salaryClass->decryptDeal($row['totalam']);
                    $shb=$this->salaryClass->decryptDeal($row['shbam']);
                    $gjj=$this->salaryClass->decryptDeal($row['gjjam']);
                    $coshb=$this->salaryClass->decryptDeal($row['coshbam']);
                    $cogjj=$this->salaryClass->decryptDeal($row['cogjjam']);
                    $pre=$this->salaryClass->decryptDeal($row['prepaream']);
                    $han=$this->salaryClass->decryptDeal($row['handicapam']);
                    $man=$this->salaryClass->decryptDeal($row['manageam']);
                    $res[]=un_iconv(array(
                        $row['usercard'],$row['pyear'],$row['pmon'],$row['username'], $this->salaryCom[$row['company']]
                        , $row['pdeptname'], $row['deptname']
                        ,$this->expflag[$row['expflag']]
                        , $this->salaryClass->decryptDeal($row['baseam'])
                        , $this->salaryClass->decryptDeal($row['proam'])
                        , $this->salaryClass->decryptDeal($row['floatam'])
                        , $this->salaryClass->decryptDeal($row['sperewam'])
                        , $this->salaryClass->decryptDeal($row['spedelam'])
                        , $this->salaryClass->decryptDeal($row['sdyam'])
                        , $this->salaryClass->decryptDeal($row['otheram'])
                        , $this->salaryClass->decryptDeal($row['bonusam'])
                        , $this->salaryClass->decryptDeal($row['othdelam'])
                        , $row['perholsdays'], $row['sickholsdays']
                        , $this->salaryClass->decryptDeal($row['holsdelam'])
                        , $total
                        , $shb
                        , $gjj
                        , $this->salaryClass->cfv($total-$shb-$gjj)
                        , $this->salaryClass->decryptDeal($row['paycesse'])
                        , $this->salaryClass->decryptDeal($row['accrewam']), $this->salaryClass->decryptDeal($row['accdelam'])
                        , $this->salaryClass->decryptDeal($row['paytotal'])
                        , $coshb
                        , $cogjj
                        , $pre, $han
                        , $man
                        , $row['remark']
                        , $row['acc'], $row['accbank'], $row['idcard']
                    ));
                }
            }
               
        }
        return $res;
    }
    /**
     * 统计总额
     */
    function model_hr_yeb_stat($seapy){
        if(empty($seapy)) {
            $seapy=2011;
        }
        $sql="select paycesse , paytotal 
            from salary_pay p
            where p.pyear='$seapy' 
                and p.leaveflag='0' ";
        $query = $this->db->query($sql);
        $tolam=0;
        while ($row = $this->db->fetch_array($query)) {
            $tolam=round($tolam+$this->salaryClass->decryptDeal($row['paycesse'])+$this->salaryClass->decryptDeal($row['paytotal']) ,2);
        }
        return $tolam;
    }
    /**
     * 统计
     */
    function model_hr_user_stat(){
        global $func_limit;
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx']?$_GET['sidx']:'s.id';
        $sord = $_GET['sord']?$_GET['sord']:'asc';
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $seapy = $_GET['seapy']?$_GET['seapy']:$this->nowy;
        $seadept = $_REQUEST['seadept'];
        $seaname = $_REQUEST['seaname'];
        $sqlSch = '';
        $fnArr=array();
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        if($seadept){
            $sqlSch.=" and d.dept_name like '%".$seadept."%' ";
        }
        if($seaname){
            $sqlSch.=" and ( s.username like '%".$seaname."%' or s.oldname like '%".$seaname."%' ) ";
        }
        $start = $limit * $page - $limit;
        //总数
        $sql = "select count(*)
            from  salary s 
                left join hrms h on( h.user_id=s.userid )
                left join user u on (u.user_id=s.userid)
                left join department d on (u.dept_id=d.dept_id)
            where ( s.usersta<>3  or year(s.leavedt)>=".$seapy." ) $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;
        //
        $sql = "select
                s.rand_key , s.oldname as username , d.dept_name as deptname , s.olddept , s.comedt 
                , h.expflag
                , h.usercard
                , s.userid
                , s.passdt 
                , y.yearam 
            from salary s
                left join hrms h on( h.user_id=s.userid )
                left join user u on (u.user_id=s.userid)
                left join department d on (u.dept_id=d.dept_id)
                left join salary_yeb y on (y.usercard = h.usercard and y.syear='".$seapy."' )
            where ( s.usersta<>3  or year(s.leavedt)>=".$seapy." )
                   $sqlSch
            order by $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $userarr=array();
        while ($row = $this->db->fetch_array($query)) {
            $userarr[$row['userid']]=array(
                    'key'=>$row['rand_key'],'exp'=>$this->expflag[$row['expflag']]
                    ,'usercard'=>$row['usercard'], 'username'=>$row['username'], 'deptname'=>$row['deptname']
                    ,'comedt'=>$row['comedt'] , 'passdt'=>$row['passdt']
                    ,'yearam'=>$this->salaryClass->decryptDeal($row['yearam'])
            );
        }
        
        $payarr=array();//基本工资
        
        $sql="(
            select p.baseam , p.userid , h.userlevel , s.usersta , year(s.passdt) as sy , month(s.passdt) as sm
                , p.pyear as py , p.pmon as pm , p.paycesse , p.paytotal , p.floatam
            from salary_pay p 
                left join hrms h on (h.user_id=p.userid)
                left join salary s on (p.userid=s.userid)
            where p.userid in ('".implode("','",array_keys($userarr))."') and p.pyear='".$seapy."'
                and p.leaveflag<>1 
                )union(
           select p.baseam , p.userid , h.userlevel , s.usersta , year(s.passdt) as sy , month(s.passdt) as sm
                , p.pyear as py , p.pmon as pm , p.paycesse , p.paytotal , p.floatam
            from `shiyuanoa`.salary_pay p 
                left join hrms h on (h.user_id=p.userid)
                left join salary s on (p.userid=s.userid)
            where p.userid in ('".implode("','",array_keys($userarr))."') and p.pyear='".$seapy."'
                and p.leaveflag<>1 
                )union(
            select p.baseam , p.userid , h.userlevel , s.usersta , year(s.passdt) as sy , month(s.passdt) as sm
                , p.pyear as py , p.pmon as pm , p.paycesse , p.paytotal , p.floatam
            from `beiruanoa`.salary_pay p 
                left join hrms h on (h.user_id=p.userid)
                left join salary s on (p.userid=s.userid)
            where p.userid in ('".implode("','",array_keys($userarr))."') and p.pyear='".$seapy."'
                and p.leaveflag<>1 
                )";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            if($func_limit['人事统计']=='1'){
                $payarr[$row['userid']]['spay'][$row['pm']]=$this->salaryClass->decryptDeal($row['paycesse'])+$this->salaryClass->decryptDeal($row['paytotal']);//每月工资
                $payarr[$row['userid']]['bpay'][$row['pm']]=$this->salaryClass->decryptDeal($row['baseam']);//基本工资
                $payarr[$row['userid']]['fpay'][$row['pm']]=$this->salaryClass->decryptDeal($row['floatam']);//季度奖
                
            }else{
                if($row['userlevel']=='4'){
                    
                    $payarr[$row['userid']]['spay'][$row['pm']]=$this->salaryClass->decryptDeal($row['paycesse'])+$this->salaryClass->decryptDeal($row['paytotal']);//每月工资
                    $payarr[$row['userid']]['bpay'][$row['pm']]=$this->salaryClass->decryptDeal($row['baseam']);//基本工资
                    $payarr[$row['userid']]['fpay'][$row['pm']]=$this->salaryClass->decryptDeal($row['floatam']);//季度奖
                    
                }else{
                    $payarr[$row['userid']]['spay'][$row['pm']]=0;//每月工资
                    $payarr[$row['userid']]['bpay'][$row['pm']]=0;//基本工资
                    $payarr[$row['userid']]['fpay'][$row['pm']]=0;//季度奖
                    //清年终奖
                    $userarr[$row['userid']]['yearam']=0;
                }
            }
        }
        if(!empty($userarr)){
            
            foreach($userarr as $key=>$val){
                $tmpval=array();
                $tmpval[]=$userarr[$key]['key'];
                $tmpval[]=$userarr[$key]['exp'];
                $tmpval[]=$userarr[$key]['usercard'];
                $tmpval[]=$userarr[$key]['username'];
                $tmpval[]=$userarr[$key]['deptname'];
                $tmpval[]=$userarr[$key]['comedt'];
                $tmpval[]=$userarr[$key]['passdt'];
                if(!empty($payarr[$key])){
                    //年度总工资
                    $total=$this->salaryClass->finiView(array_sum($payarr[$key]['spay'])+$userarr[$key]['yearam']);
                    //年度平均收入
                    $tmpval[]=$this->salaryClass->finiView($total/count($payarr[$key]['spay']));
                    $tmpval[]=$total;
                    //基本工资
                    $tmpval[]=$this->salaryClass->finiView(array_sum($payarr[$key]['bpay'])/count($payarr[$key]['bpay']));//平均基本
                    $tmpval[]=$this->salaryClass->finiView(array_sum($payarr[$key]['bpay']));//总基本
                    $tmpval[]=$this->salaryClass->finiView(array_sum($payarr[$key]['fpay']));//季度奖
                    $tmpval[]=$userarr[$key]['yearam'];//年终奖
                    $tmpval[]=$this->salaryClass->finiView(array_sum($payarr[$key]['spay'])-array_sum($payarr[$key]['bpay'])-array_sum($payarr[$key]['fpay']));
                    //月份
                    $tmpval[]=$payarr[$key]['bpay'][1];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][2];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][3];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][4];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][5];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][6];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][7];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][8];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][9];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][10];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][11];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][12];//一月份
                    
                }
                $responce->rows[$i]['id'] = $key;
                $responce->rows[$i]['cell'] = un_iconv($tmpval);
                $i++;
            }
        }
        return $responce;
        $this->globalUtil->insertOperateLog('工资人事管理', 'salary', '显示工资信息', '成功', $sql);
        
    }
    /**
     * 导出
     */
    function model_hr_user_stat_xls(){
        global $func_limit;
        $seapy = $_GET['seapy']?$_GET['seapy']:$this->nowy;
        //分页
        $sql = "select
                s.rand_key , s.oldname as username , d.dept_name as deptname , s.olddept , s.comedt 
                , h.expflag
                , h.usercard
                , s.userid
                , s.passdt
                , y.yearam 
            from salary s
                left join hrms h on( h.user_id=s.userid )
                left join user u on (u.user_id=s.userid)
                left join department d on (u.dept_id=d.dept_id)
                left join salary_yeb y on (y.usercard = h.usercard and y.syear='".$seapy."' )
            where ( s.usersta<>3  or year(s.leavedt)>=".$seapy." ) 
            order by s.id asc ";
        $query = $this->db->query($sql);
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $userarr=array();
        while ($row = $this->db->fetch_array($query)) {
            $userarr[$row['userid']]=array(
                    'key'=>$row['rand_key'],'exp'=>$this->expflag[$row['expflag']]
                    ,'usercard'=>$row['usercard'], 'username'=>$row['username'], 'deptname'=>$row['deptname']
                    ,'comedt'=>$row['comedt'] , 'passdt'=>$row['passdt']
                    ,'yearam'=>$this->salaryClass->decryptDeal($row['yearam'])
            );
        }
        $payarr=array();
        $sql="(
            select p.baseam , p.userid , h.userlevel , s.usersta , year(s.passdt) as sy , month(s.passdt) as sm
                , p.pyear as py , p.pmon as pm , p.paycesse , p.paytotal , p.floatam
            from salary_pay p 
                left join salary s on (p.userid=s.userid)
                left join hrms h on (h.user_id=p.userid)
            where ( s.usersta<>3  or year(s.leavedt)>=".$seapy." ) and p.pyear='".$seapy."' 
                )union(
            select p.baseam , p.userid , h.userlevel , s.usersta , year(s.passdt) as sy , month(s.passdt) as sm
                , p.pyear as py , p.pmon as pm , p.paycesse , p.paytotal , p.floatam
            from `shiyuanoa`.salary_pay p 
                left join salary s on (p.userid=s.userid)
                left join hrms h on (h.user_id=p.userid)
            where ( s.usersta<>3  or year(s.leavedt)>=".$seapy." ) and p.pyear='".$seapy."' 
                )union(
            select p.baseam , p.userid , h.userlevel , s.usersta , year(s.passdt) as sy , month(s.passdt) as sm
                , p.pyear as py , p.pmon as pm , p.paycesse , p.paytotal , p.floatam
            from `beiruanoa`.salary_pay p 
                left join salary s on (p.userid=s.userid)
                left join hrms h on (h.user_id=p.userid)
            where ( s.usersta<>3  or year(s.leavedt)>=".$seapy." ) and p.pyear='".$seapy."' 
                )";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            if($func_limit['人事统计']=='1'){
                $payarr[$row['userid']]['spay'][$row['pm']]=$this->salaryClass->decryptDeal($row['paycesse'])+$this->salaryClass->decryptDeal($row['paytotal']);//每月工资
                $payarr[$row['userid']]['bpay'][$row['pm']]=$this->salaryClass->decryptDeal($row['baseam']);//基本工资
                $payarr[$row['userid']]['fpay'][$row['pm']]=$this->salaryClass->decryptDeal($row['floatam']);//季度奖
            }else{
                if($row['userlevel']=='4'){
                    $payarr[$row['userid']]['spay'][$row['pm']]=$this->salaryClass->decryptDeal($row['paycesse'])+$this->salaryClass->decryptDeal($row['paytotal']);//每月工资
                    $payarr[$row['userid']]['bpay'][$row['pm']]=$this->salaryClass->decryptDeal($row['baseam']);//基本工资
                    $payarr[$row['userid']]['fpay'][$row['pm']]=$this->salaryClass->decryptDeal($row['floatam']);//季度奖
                }else{
                    $payarr[$row['userid']]['spay'][$row['pm']]=0;//每月工资
                    $payarr[$row['userid']]['bpay'][$row['pm']]=0;//基本工资
                    $payarr[$row['userid']]['fpay'][$row['pm']]=0;//季度奖
                    //清年终奖
                    $userarr[$row['userid']]['yearam']=0;
                }
            }
        }
        $xls=new includes_class_excelout('gbk', true, '工资统计--'.$seapy);
        $data=array(1=>array('员工类型','员工号','姓名','部门','入职日期','转正日期'
            ,'年度平均收入','年度总收入','年度平均基本工资','年度总基本工资'
            ,'总季度奖','年终奖','其他'
            ,'一月(基本)','二月(基本)','三月(基本)','四月(基本)','五月(基本)'
            ,'六月(基本)','七月(基本)','八月(基本)','九月(基本)','十月(基本)','十一月(基本)','十二月(基本)'));
        $xls->setStyle(array(4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27));
        if(!empty($userarr)){
            foreach($userarr as $key=>$val){
                $tmpval=array();
                $tmpval[]=$userarr[$key]['exp'];
                $tmpval[]=$userarr[$key]['usercard'];
                $tmpval[]=$userarr[$key]['username'];
                $tmpval[]=$userarr[$key]['deptname'];
                $tmpval[]=$userarr[$key]['comedt'];
                $tmpval[]=$userarr[$key]['passdt'];
                if(!empty($payarr[$key])){
                    //年度总工资
                    $total=$this->salaryClass->finiView(array_sum($payarr[$key]['spay'])+$userarr[$key]['yearam']);
                    //年度平均收入
                    $tmpval[]=$this->salaryClass->finiView($total/count($payarr[$key]['spay']));
                    $tmpval[]=$total;
                    //基本工资
                    $tmpval[]=$this->salaryClass->finiView(array_sum($payarr[$key]['bpay'])/count($payarr[$key]['bpay']));//平均基本
                    $tmpval[]=$this->salaryClass->finiView(array_sum($payarr[$key]['bpay']));//总基本
                    $tmpval[]=$this->salaryClass->finiView(array_sum($payarr[$key]['fpay']));//季度奖
                    $tmpval[]=$userarr[$key]['yearam'];//年终奖
                    $tmpval[]=$this->salaryClass->finiView(array_sum($payarr[$key]['spay'])-array_sum($payarr[$key]['bpay'])-array_sum($payarr[$key]['fpay']));
                    //月份
                    $tmpval[]=$payarr[$key]['bpay'][1];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][2];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][3];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][4];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][5];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][6];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][7];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][8];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][9];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][10];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][11];//一月份
                    $tmpval[]=$payarr[$key]['bpay'][12];//一月份
                    
                }
                $data[]= $tmpval;
            }
        }
        $xls->addArray($data);
        $xls->generateXML($seapy);
    }
    /**
     * 外派导出-人事
     */
    function model_hr_user_exp_xls(){
        $sy=$_GET['sy'];
        $sm=$_GET['sm'];
        $data=array(1=>array('部门','员工号','姓名','入职日期','离职日期'
            ,'岗位工资','病假','事假'
            ,'季度奖','项目奖'
            ,'补贴'
            ,'特殊奖励','其他扣除'
            ,'奖金'
            ,'应发工资'
            ,'个人社保','个人公积金'
            ,'扣税金额'
            ,'扣税','实发工资'
            ,'企业社保'
            ,'企业公积金','调配费'
            ,'残障费'
            ,'管理费','合计费用'
            ,'备注'));
        $xls=new includes_class_excelout('gbk', true, '外派工资列表--'.$sy.'-'.$sm);
        if($sy>$this->nowy||($sy==$this->nowy&&$sm>=$this->nowm)){
            $data[]=array('本月数据暂未生成！');
        }else{
            $xls->setStyle(array(5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24));
            $baseam=array();
            $sql="select
                    s.id , p.id as pid , s.userid , u.user_name as username , s.oldname
                    , s.accbank as bank, s.comedt , d.dept_name , s.olddept
                    , s.oldarea , s.email , p.area , p.pyear ,p.pmon , p.baseam
                    , p.basenowam , p.floatam , p.proam , p.otheram , p.bonusam
                    , p.perholsdays , p.sickholsdays, p.holsdelam , p.totalam
                    , p.gjjam , p.shbam , p.cessebase , p.paycesse , p.paytotal
                    , p.othdelam , s.acc , h.address , s.sta ,s.idcard , h.card_no
                    , p.sperewam , p.cogjjam ,p.coshbam ,p.prepaream ,p.handicapam
                    , p.manageam , p.sdyam , p.spedelam
                    , h.usercard , s.leavedt , p.remark , p.nowamflag
                from
                    salary_pay p
                    left join salary s on ( s.userid=p.userid )
                    left join hrms h on (s.userid=h.user_id)
                    left join department d on (p.deptid=d.dept_id)
                    left join user u on (s.userid=u.user_id )
                where s.userid=p.userid  and p.leaveflag='0' and p.expflag='1'
                    and p.pmon='".$sm."' and p.pyear='".$sy."'
                    $sqlStr
                group by p.id
                order by s.id";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                $total=$this->salaryClass->decryptDeal($row["totalam"]);
                $shb=$this->salaryClass->decryptDeal($row["shbam"]);
                $gjj=$this->salaryClass->decryptDeal($row["gjjam"]);
                if(round($total,2)<round($shb+$gjj,2)){
                    $total=round($shb+$gjj,2);
                }
                $paytotal=$this->salaryClass->decryptDeal($row["paytotal"]);
                $coshb=$this->salaryClass->decryptDeal($row["coshbam"]);
                $cogjj=$this->salaryClass->decryptDeal($row["cogjjam"]);
                $pre=$this->salaryClass->decryptDeal($row["prepaream"]);
                $han=$this->salaryClass->decryptDeal($row["handicapam"]);
                $man=$this->salaryClass->decryptDeal($row["manageam"]);
                if($row['nowamflag']=='3'){
                    $res['Z'][]=array(
                        $row['dept_name'],$row['usercard'],$row['oldname'],$row['comedt'],$row['leavedt']
                        ,$shb,$gjj
                        ,$coshb,$cogjj
                        ,$pre,$han
                        ,$man,$this->salaryClass->cfv($shb+$gjj+$coshb+$cogjj+$pre+$han+$man)
                        ,$row["remark"]
                    );
                }else{
                    $res['L'][]=array(
                        $row['dept_name'],$row['usercard'],$row['oldname'],$row['comedt'],$row['leavedt']
                        ,$this->salaryClass->decryptDeal($row["baseam"]),$row['sickholsdays'],$row['perholsdays']
                        ,$this->salaryClass->decryptDeal($row["floatam"]),$this->salaryClass->decryptDeal($row["proam"])
                        ,$this->salaryClass->decryptDeal($row["sdyam"])+$this->salaryClass->decryptDeal($row["otheram"])
                        ,$this->salaryClass->decryptDeal($row["sperewam"])
                        ,$this->salaryClass->decryptDeal($row["othdelam"])+$this->salaryClass->decryptDeal($row["spedelam"])
                        ,$this->salaryClass->decryptDeal($row['bonusam'])
                        ,$total
                        ,$shb,$gjj
                        ,$this->salaryClass->cfv($total-$shb-$gjj)
                        ,$this->salaryClass->decryptDeal($row["paycesse"]),$paytotal
                        ,$coshb,$cogjj
                        ,$pre,$han
                        ,$man,$this->salaryClass->cfv($total+$coshb+$cogjj+$pre+$han+$man)
                        ,$row["remark"]
                    );
                }
            }
        }
        if(!empty($res['L'])){
            foreach($res['L'] as $val){
                $data[]=$val;
            }
        }
        if(!empty($res['Z'])){
            $data[]=array();
            $data[]=array('部门','员工号','姓名','入职日期','离职日期'
            ,'个人社保','个人公积金'
            ,'企业社保'
            ,'企业公积金','调配费'
            ,'残障费'
            ,'管理费','合计费用'
            ,'备注');
            foreach($res['Z'] as $val){
                $data[]=$val;
            }
        }
        $xls->addArray($data);
        $xls->generateXML($sy.'-'.$sm);
    }
    /**
     * 工程部导出-人事
     */
    function model_hr_user_wy_xls(){
        $sy=$_GET['sy'];
        $sm=$_GET['sm'];
        $data=array(1=>array('部门','员工号','姓名','入职日期','离职日期'
            ,'岗位工资','病假','事假'
            ,'季度奖','项目奖'
            ,'补贴'
            ,'特殊奖励','其他扣除'
            ,'奖金'
            ,'应发工资'
            ,'个人社保','个人公积金'
            ,'扣税金额'
            ,'扣税','实发工资'
            ,'企业社保'
            ,'企业公积金','调配费'
            ,'残障费'
            ,'管理费','合计费用'
            ,'备注'));
        $xls=new includes_class_excelout('gbk', true, '工程工资列表--'.$sy.'-'.$sm);
        $xls->setStyle(array(5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24));
        $baseam=array();
        $sql="select
                s.id , p.id as pid , s.userid , u.user_name as username , s.oldname
                , s.accbank as bank, s.comedt , d.dept_name , s.olddept
                , s.oldarea , s.email , p.area , p.pyear ,p.pmon , p.baseam
                , p.basenowam , p.floatam , p.proam , p.otheram , p.bonusam
                , p.perholsdays , p.sickholsdays, p.holsdelam , p.totalam
                , p.gjjam , p.shbam , p.cessebase , p.paycesse , p.paytotal
                , p.othdelam , s.acc , h.address , s.sta ,s.idcard , h.card_no
                , p.sperewam , p.cogjjam ,p.coshbam ,p.prepaream ,p.handicapam
                , p.manageam , p.sdyam , p.spedelam
                , h.usercard , s.leavedt , p.remark
            from
                salary_pay p
                left join salary s on ( s.userid=p.userid )
                left join hrms h on (s.userid=h.user_id)
                left join department d on (p.deptid=d.dept_id)
                left join user u on (s.userid=u.user_id )
            where s.userid=p.userid  and p.leaveflag='0' 
                and ( ( p.deptid in ( 90,91,94,35,34 ) and p.pmon> 5 ) or ( p.deptid in ( 35 ) and p.pmon<=5 ) )
                and p.pmon='".$sm."' and p.pyear='".$sy."'
                $sqlStr
            group by p.id
            order by p.deptid , s.id";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $total=$this->salaryClass->decryptDeal($row["totalam"]);
            $shb=$this->salaryClass->decryptDeal($row["shbam"]);
            $gjj=$this->salaryClass->decryptDeal($row["gjjam"]);
            if(round($total,2)<round($shb+$gjj,2)){
                $total=round($shb+$gjj,2);
            }
            $paytotal=$this->salaryClass->decryptDeal($row["paytotal"]);
            $coshb=$this->salaryClass->decryptDeal($row["coshbam"]);
            $cogjj=$this->salaryClass->decryptDeal($row["cogjjam"]);
            $pre=$this->salaryClass->decryptDeal($row["prepaream"]);
            $han=$this->salaryClass->decryptDeal($row["handicapam"]);
            $man=$this->salaryClass->decryptDeal($row["manageam"]);
            $data[]=array(
                $row['dept_name'],$row['usercard'],$row['oldname'],$row['comedt'],$row['leavedt']
                ,$this->salaryClass->decryptDeal($row["baseam"]),$row['sickholsdays'],$row['perholsdays']
                ,$this->salaryClass->decryptDeal($row["floatam"]),$this->salaryClass->decryptDeal($row["proam"])
                ,$this->salaryClass->decryptDeal($row["sdyam"])+$this->salaryClass->decryptDeal($row["otheram"])
                ,$this->salaryClass->decryptDeal($row["sperewam"])
                ,$this->salaryClass->decryptDeal($row["othdelam"])+$this->salaryClass->decryptDeal($row["spedelam"])
                ,$this->salaryClass->decryptDeal($row['bonusam'])
                ,$total
                ,$shb,$gjj
                ,$this->salaryClass->cfv($total-$shb-$gjj)
                ,$this->salaryClass->decryptDeal($row["paycesse"]),$paytotal
                ,$coshb,$cogjj
                ,$pre,$han
                ,$man,$this->salaryClass->cfv($total+$coshb+$cogjj+$pre+$han+$man)
                ,$row["remark"]
            );
        }
        
        //var_dump($data);
        $xls->addArray($data);
        $xls->generateXML($sy.'-'.$sm);
    }
    /**
     * 外派调动导出
     */
    function model_hr_user_exp_xls_f(){
        $sy=$_GET['sy'];
        $sm=$_GET['sm'];
        $data=array(1=>array('部门','员工','员工号','员工状态','调薪后工资','当月入职/转正工资','季度奖','项目奖','餐补','其他补贴','奖金','请假扣除','事假'
            ,'病假','其他扣除','特别奖励','特别扣除'));
        $xls=new includes_class_excelout('gb2312', true, 'My Test Sheet');
        $baseam=array();
        /*
        $sql="select
                f.userid , f.changeam
            from salary_flow f
                left join hrms h on (f.userid=h.user_id)
            where
                f.sta='2' and f.flowname like '%调薪%'
                and f.pyear='".$sy."' and f.pmon='".$sm."'
                and h.expflag='1' ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $baseam[$row['userid']]=$this->salaryClass->decryptDeal($row['changeam']);
        }
         * 
         */
        $sql="select
                u.user_name , d.dept_name , s.usersta , s.userid , h.usercard
                , p.baseam , p.basenowam 
                , p.floatam , p.proam , p.sdyam , p.otheram
                , p.bonusam , p.holsdelam , p.perholsdays , p.sickholsdays
                , p.othdelam , p.sperewam , p.spedelam 
            from 
                salary s
                left join salary_pay p on (s.userid=p.userid)
                left join user u on (s.userid=u.user_id)
                left join hrms h on (u.user_id=h.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where
                p.expflag='1' and p.pyear='".$sy."'
                and p.pmon='".$sm."'
                and (s.leavedt is null or ( year(s.leavedt)='".$sy."' and month(s.leavedt)='".$sm."' ) )
                ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $baseam=$this->salaryClass->decryptDeal($row['baseam']);
            $basenowam=$this->salaryClass->decryptDeal($row['basenowam']);
            $data[]=array(
                $row['dept_name'], $row['user_name'], $row['usercard'] ,$this->userSta[$row['usersta']]
                , $baseam ,$basenowam , $this->salaryClass->decryptDeal($row['floatam']),$this->salaryClass->decryptDeal($row['proam'])
                , $this->salaryClass->decryptDeal($row['sdyam']) , $this->salaryClass->decryptDeal($row['otheram'])
                , $this->salaryClass->decryptDeal($row['bonusam']) , $this->salaryClass->decryptDeal($row['holsdelam'])
                , $row['perholsdays'] , $row['sickholsdays']
                , $this->salaryClass->decryptDeal($row['othdelam']) , $this->salaryClass->decryptDeal($row['sperewam'])
                , $this->salaryClass->decryptDeal($row['spedelam'])
            );
        }
        //var_dump($data);
        $xls->addArray($data);
        $xls->generateXML(time());
    }
    /**
     * 人事请休假上交
     */
    function model_hr_hols_hd(){
        try {
            $info=array();
            $this->db->query("START TRANSACTION");
            $sql="select count(*) as mc from hols_sta s where s.syear='".$this->nowy."' and s.smon='".$this->nowm."'
                and checkflag<>'1' ";
            $res=$this->db->get_one($sql);
            if(empty($res['mc'])){
                throw new Exception('Data has been turned over to');
            }
            $sql="update
                    hols_sta s ,
                    (select userid , type as sty , sum(realdays)  as sds  from hols_sta where syear='".$this->nowy."'
                        and smon='".$this->nowm."' group by userid , type ) sta
                set s.saldays=sta.sds 
                where s.userid=sta.userid and s.type=sta.sty and s.syear='".$this->nowy."' and s.smon='".$this->nowm."'
                    and checkflag<>'1' ";
            $this->db->query_exc($sql);
            $sql="select
                    p.id , h.type , h.saldays , u.company , u.salarycom , h.userid
                from hols_sta h
                    left join salary_pay p on (h.userid=p.userid and p.pyear='".$this->nowy."' and p.pmon='".$this->nowm."')
                    left join user u on (h.userid=u.user_id)
                where h.syear='".$this->nowy."' and h.smon='".$this->nowm."' and checkflag<>'1' 
                group by h.userid , h.type ";
            $query=$this->db->query_exc($sql);
            while($row=$this->db->fetch_array($query)){
                $info[$row['userid']][$row['type']]=$row['saldays'];
                $info[$row['userid']]['pid']=$row['id'];
                $info[$row['userid']]['com']=$row['company'];
                if(!empty($row['salarycom'])&&$row['salarycom']!=''){
                    $info[$row['userid']]['com']=$row['salarycom'];
                }
            }
            if(count($info)){
                foreach($info as $key=>$val){
                    if($key){
                        $comtable=$this->get_com_sql($val['com']);
                        if(empty($val['pid'])){
                            $sql="select p.id as pid 
                                from ".$comtable."salary_pay p 
                                where  p.pyear='" . $this->nowy . "' and p.pmon='" . $this->nowm . "' and p.userid='".$key."' ";
                            $rescom=$this->db->get_one($sql);
                            $val['pid']=$rescom['pid'];
                        }
                        if(empty($val['pid'])){
                            continue;
                        }
                        $ph=empty ($val['事假'])?'0':$val['事假'];
                        $sh=empty ($val['病假'])?'0':$val['病假'];
                        $this->model_pay_update($val['pid'],
                                array('perholsdays'=>$ph ,'sickholsdays'=>$sh)
                                , array(0,1),$comtable);
                        $this->model_pay_stat($val['pid'],$comtable);
                    }
                }
            }
            $sql="update
                    hols_sta s 
                set s.checkflag='1'
                where  s.syear='".$this->nowy."' and s.smon='".$this->nowm."' and s.checkflag<>'1' ";
            $this->db->query_exc($sql);
            $this->db->query("COMMIT");
            $this->globalUtil->insertOperateLog('工资管理', 'salary', '请休假上交', '成功', $sql);
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->globalUtil->insertOperateLog('工资管理', 'salary', '请休假上交', '失败', $sql);
            echo $sql;
        }
    }
/**
 * 员工对应权限
 */
    function model_dp_pow(){
        $dppow=array('1'=>array(),'2'=>array());
        $sql="select majorid , vicemanager , dept_id
            from department d
            where
                ( find_in_set('".$_SESSION['USER_ID']."',d.majorid)>0
                    or find_in_set('".$_SESSION['USER_ID']."',d.vicemanager)
                ) ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            if(trim($row['majorid'])){
                if(strpos($row['majorid'], strtolower($_SESSION['USER_ID']))===false){
                    $dppow['2'][]=$row['dept_id'];
                }else{
                    $dppow['1'][]=$row['dept_id'];
                }
            }else{
                $dppow['1'][]=$row['dept_id'];
            }
        }
        if($_SESSION['USER_PRIV']==88){
            $sql="select majorid , vicemanager , dept_id from department d ";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                if(trim($row['majorid'])||trim($row['vicemanager'])){
                    $dppow['2'][]=$row['dept_id'];
                }else{
                    $dppow['1'][]=$row['dept_id'];
                }
            }
        }
        return $dppow;
    }
    /**
     *职位直接掌控对应部门
     * @return <type> 
     */
    function model_dp_ctr_pow(){
        $dppow=array('1'=>array(),'2'=>array(),'3'=>array());
        $sql="select majorid , vicemanager , dept_id
            from department d
            where
                ( find_in_set('".$_SESSION['USER_ID']."',d.majorid)>0
                    or find_in_set('".$_SESSION['USER_ID']."',d.vicemanager )
                    or d.vicemanager=''
                ) ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            if(strpos($row['majorid'], strtolower($_SESSION['USER_ID']))!==false){
                $dppow['3'][]=$row['dept_id'];
            }elseif(!$row['majorid']){
                if(strpos($row['vicemanager'], strtolower($_SESSION['USER_ID']))!==false){
                    $dppow['3'][]=$row['dept_id'];
                }elseif(!$row['vicemanager']){
                    if($_SESSION['USER_PRIV']==88){
                        $dppow['3'][]=$row['dept_id'];
                    }
                }
            }
            if(strpos($row['vicemanager'], strtolower($_SESSION['USER_ID']))!==false){
                $dppow['2'][]=$row['dept_id'];
            }elseif(!$row['vicemanager']){
                if($_SESSION['USER_PRIV']==88){
                    $dppow['2'][]=$row['dept_id'];
                }
            }
        }
        if($_SESSION['USER_PRIV']==88){
            $sql="select majorid , vicemanager , dept_id from department d ";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                $dppow['1'][]=$row['dept_id'];
            }
        }
        return $dppow;
    }
    function model_dp_sql_pow($dppow){
        $res='';
        if(is_array($dppow)){
            if(!empty($dppow['1'])){
                $res.=" ( h.userlevel in (0,1) and s.deptid in (".implode(',', $dppow['1']).") ) ";
            }
            if(!empty($dppow['2'])){
                if($res){
                    $res.=" or ( h.userlevel=2 and s.deptid in (".implode(',', $dppow['2']).") ) ";
                }else{
                    $res.=" ( h.userlevel=2 and s.deptid in (".implode(',', $dppow['2']).") ) ";
                }
            }
            if(!empty($dppow['3'])){
                if($res){
                    $res.=" or ( h.userlevel=3 and s.deptid in (".implode(',', $dppow['3']).") ) ";
                }else{
                    $res.=" ( h.userlevel=3 and s.deptid in (".implode(',', $dppow['3']).") ) ";
                }
            }
            if($res){
                $res=" and ( ".$res." )";
            }
        }
        if(empty($res)){
            $res=" and 1!=1 ";
        }
        return $res;
    }
    /**
     *工资办理
     * @return string 
     */
    function model_dp_exa(){
        global $func_limit;
        $str='<table align="left" style="text-align: left;width: 100%;" >
                <tr>
                    <td id="hr_user_pass_name" style="line-height: 20px;padding-left: 5px;" class="ui-widget-header ui-corner-all">
                        待审事项
                    </td>
                </tr>
                <tr>
                    <td><div class="divlist">';
        $dppow=$this->model_dp_ctr_pow();
        $sql="select count(*) , usersta
            from
                salary s
                left join user u on (s.userid=u.user_id)
                left join hrms h on (u.user_id=h.user_id)
            where
                (s.usersta='0' or (s.usersta='1' and passdt!='' ))
                ".$this->model_dp_sql_pow($dppow)."
                group by s.usersta ";
        $query=$this->db->query($sql);
        $res=array('0'=>0,'1'=>0);
        while($row=$this->db->fetch_array($query)){
            $res[$row['usersta']]=$row['count(*)'];
        }
        $str.='<div class="nbtn">
                    <input type="button" value="员工入职" class="btn" onclick="newParentTab(\'?model=salary&action=dp_join\', \'员工入职\',\'1\')" />
                    <div style="text-align: center"><font color="red">'.$res['0'].'</font></div>
                </div>';
        $str.='<div class="nbtn">
                    <input type="button" value="员工转正" class="btn" onclick="newParentTab(\'?model=salary&action=dp_pass\', \'员工转正\',\'2\')" />
                    <div style="text-align: center"><font color="red">'.$res['1'].'</font></div>
                </div>';
        $holspow=$this->model_dp_pow();
        if(!empty($holspow['1'])){
            $sql="select count(id) as mck from hols_sta where syear='".$this->nowy."' and smon='".$this->nowm."'
                and deptid in ('".implode("','", $holspow['1'])."') and checkflag in (1,2) ";
            $res=$this->db->get_one($sql);
            $str.='<div class="nbtn">
                    <input type="button" value="请休假" class="btn" onclick="newParentTab(\'?model=exet_attendance&action=deptsta\', \'请休假\',\'3\')" />
                    <div style="text-align: center"><font color="red">'.$res['mck'].'</font></div>
                </div>';
        }
        $sql="select count(fs.id) as ids , f.flowname from salary_flow_step fs
                left join salary_flow f on (fs.salaryfid=f.id)
            where find_in_set('".$_SESSION['USER_ID']."', fs.dealuser)>0 and  fs.sta='0' group by f.flowname ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $resck[$row['flowname']]=$row['ids'];
        }
        $resck['非年度调薪']=$resck['非年度调薪-普通']+$resck['非年度调薪-经理']+$resck['非年度调薪-总监']+$resck['非年度调薪-副总']+$resck['年度调薪'];
        $resck['工资特殊']=$resck['工资特殊']?$resck['工资特殊']:0;
        $resck['非年度调薪']=$resck['非年度调薪']?$resck['非年度调薪']:0;
        $resck['项目奖']=$resck['项目奖']?$resck['项目奖']:0;
        $resck['工资奖金']=$resck['工资奖金']?$resck['工资奖金']:0;
        $resck['季度奖']=$resck['季度奖']?$resck['季度奖']:0;
        $resck['人事补贴']=$resck['人事补贴']?$resck['人事补贴']:0;
        $str.='<div class="nbtn">
                    <input type="button" value="补发/扣除" class="btn" onclick="newParentTab(\'?model=salary&action=dp_sal_exa_spe\', \'补发/扣除审批\',\'41\')" />
                    <div style="text-align: center"><font color="red">'.$resck['工资特殊'].'</font></div>
                </div>';
        $str.='<div class="nbtn">
                    <input type="button" value="工资调薪" class="btn" onclick="newParentTab(\'?model=salary&action=dp_sal_exa_nym\', \'工资调薪\',\'42\')" />
                    <div style="text-align: center"><font color="red">'.$resck['非年度调薪'].'</font></div>
                </div>';
        $str.='<div class="nbtn">
                    <input type="button" value="季度奖" class="btn" onclick="newParentTab(\'?model=salary&action=dp_sal_exa_fla\', \'季度奖\',\'46\')" />
                    <div style="text-align: center"><font color="red">'.$resck['季度奖'].'</font></div>
                </div>';
        $str.='<div class="nbtn">
                    <input type="button" value="项目奖" class="btn" onclick="newParentTab(\'?model=salary&action=dp_sal_exa_pro\', \'项目奖\',\'43\')" />
                    <div style="text-align: center"><font color="red">'.$resck['项目奖'].'</font></div>
                </div>';
        $str.='<div class="nbtn">
                    <input type="button" value="奖金" class="btn" onclick="newParentTab(\'?model=salary&action=dp_sal_exa_bos\', \'奖金\',\'44\')" />
                    <div style="text-align: center"><font color="red">'.$resck['工资奖金'].'</font></div>
                </div>';
        $str.='<div class="nbtn">
                    <input type="button" value="人事补贴" class="btn" onclick="newParentTab(\'?model=salary&action=dp_sal_exa_sdy\', \'人事补贴\',\'45\')" />
                    <div style="text-align: center"><font color="red">'.$resck['人事补贴'].'</font></div>
                </div>';
        $str.='         </div>
                    </td>
                </tr>';
        $str.='<tr>
                    <td id="hr_user_pass_name" style="line-height: 20px;padding-left: 5px;" class="ui-widget-header ui-corner-all">
                        申请事项
                    </td>
                </tr>
                <tr>
                    <td><div class="divlist">';
        $str.='<div class="nbtn">
                    <input type="button" value="工资调薪" class="btn" onclick="newParentTab(\'?model=salary&action=dp_mdf\', \'工资调薪\',\'6\')" />
                </div>';
//        $str.='<div class="nbtn">
//                    <input type="button" value="季度奖" class="btn" onclick="newParentTab(\'?model=salary&action=dp_fla\', \'季度奖\',\'7\')" />
//                </div>';
        $str.='<div class="nbtn">
                    <input type="button" value="项目奖" class="btn" onclick="newParentTab(\'?model=salary&action=dp_pro\', \'项目奖\',\'8\')" />
                </div>';
//        $str.='<div class="nbtn">
//                    <input type="button" value="奖金" class="btn" onclick="newParentTab(\'?model=salary&action=dp_bos\', \'奖金\',\'9\')" />
//                </div>';
//        $str.='<div class="nbtn">
//                    <input type="button" value="补贴" class="btn" onclick="newParentTab(\'?model=salary&action=dp_sdy\', \'补贴\',\'10\')" />
//                </div>';
        if($func_limit['年终奖']=='1'){
            $str.='<div class="nbtn">
                        <input type="button" value="年终奖导入" class="btn" onclick="newParentTab(\'?model=salary&action=dp_yeb\', \'年终奖导入\',\'11\')" />
                    </div>';
        }
        if($func_limit['年度调薪']=='1'){
            $str.='<div class="nbtn">
                        <input type="button" value="年度调薪" class="btn" onclick="newParentTab(\'?model=salary&action=dp_ymd\', \'年度调薪\',\'12\')" />
                    </div>';
        }
        $str.='         </div>
                    </td>
                </tr>';
        if(in_array($_SESSION['USER_ID'], $this->fnStatU)){
            $str.='<tr>
                    <td id="hr_user_pass_name" style="line-height: 20px;padding-left: 5px;" class="ui-widget-header ui-corner-all">
                        财务管理
                    </td>
                </tr>
                <tr>
                    <td><div class="divlist">';
            $str.='<div class="nbtn">
                    <input type="button" value="财务对账" class="btn" onclick="newParentTab(\'?model=salary&action=fn_stat\', \'财务对账\',\'5\')" />
                </div>';
            $str.='<div class="nbtn">
                    <input type="button" value="员工信息管理" class="btn" onclick="newParentTab(\'?model=salary&action=fn_user_info\', \'员工信息管理\',\'51\')" />
                </div>';
            $str.='<div class="nbtn">
                    <input type="button" value="年终奖" class="btn" onclick="newParentTab(\'?model=salary&action=fn_yeb\', \'年终奖\',\'52\')" />
                </div>';
            if($func_limit['密钥管理']=='1'){
            	$str.='<div class="nbtn">
                    <input type="button" value="密码管理" class="btn"
            		onclick="newParentTab(\'./general/salary/index.php?lpw=pwi&scl=index\', \'密码管理\',\'53\')" />
                </div>';
            }
            $str.='         </div>
                    </td>
                </tr>';
        }
        if($func_limit['员工属性']=='1'||$func_limit['管理层工资']=='1'){
            $str.='<tr>
                    <td id="hr_user_pass_name" style="line-height: 20px;padding-left: 5px;" class="ui-widget-header ui-corner-all">
                        员工信息
                    </td>
                </tr>
                <tr>
                    <td><div class="divlist">';
            if($func_limit['员工属性']=='1'){
                $str.='<div class="nbtn">
                        <input type="button" value="项目属性" class="btn" onclick="newParentTab(\'?model=salary&action=dp_user_type&flag=pro\', \'项目属性\',\'11\')" />
                    </div>';
            }
            if($func_limit['管理层工资']=='1'){
                $str.='<div class="nbtn">
                        <input type="button" value="管理层工资" class="btn" onclick="location.href=\'?model=salary&action=hr_xls_out&flag=manager\'" />
                    </div>';
            }
            $str.='         </div>
                    </td>
                </tr>';
        }
        if($func_limit['季度奖统计']=='1'||$func_limit['外派工资']=='1'||$func_limit['工程工资']=='1'||$func_limit['部门工资']=='1'||$func_limit['部门分析表']=='1'){
            $str.='<tr>
                    <td id="hr_user_pass_name" style="line-height: 20px;padding-left: 5px;" class="ui-widget-header ui-corner-all">
                        工资统计
                    </td>
                </tr>
                <tr>
                    <td><div class="divlist">';
            if($func_limit['季度奖统计']=='1'){
                $str.='<div class="nbtn">
                    <input type="button" value="季度奖统计" class="btn" onclick="location.href=\'?model=salary&action=dp_stat_float\'" />
                </div>';
            }
            if($func_limit['外派工资']=='1'){
                $str.='<div class="nbtn">
                    <input type="button" value="外派工资" class="btn" onclick="expClick(\'?model=salary&action=hr_user_exp_xls\')" />
                </div>';
            }
            if($func_limit['工程工资']=='1'){
                $str.='<div class="nbtn">
                    <input type="button" value="工程工资" class="btn" onclick="expClick(\'?model=salary&action=hr_user_wy_xls\')" />
                </div>';
            }
            if($func_limit['部门工资']=='1'){
                $str.='<div class="nbtn">
                    <input type="button" value="部门工资" class="btn" onclick="expClick(\'?model=salary&action=fn_xls&ty=4\')" />
                </div>';
            }
            if($func_limit['部门工资']=='1'){
                $str.='<div class="nbtn">
                    <input type="button" value="项目工资" class="btn" onclick="expClick(\'?model=salary&action=xls_out&flag=fn_pro\')" />
                </div>';
            }
            if($func_limit['部门分析表']=='1'){
                $str.='<div class="nbtn">
                    <input type="button" value="部门分析表" class="btn" onclick="expClick(\'?model=salary&action=xls_out&flag=gs_tol\')" />
                </div>';
            }
            $str.='         </div>
                    </td>
                </tr>';
        }
        $str.='</table>';
        return $str;
    }
    /**
     * 工资统计季度奖
     */
    function model_dp_stat_float(){
        global $func_limit;
        if($func_limit['季度奖统计']=='1'){
            $xls=new includes_class_excelout('gb2312', true, 'My Test Sheet');
            $data = array(1=> array ('部门', '一月', '二月', '三月', '四月'
                , '五月', '六月', '七月', '八月', '九月', '十月'
                ,'十一月','十二月'));
            $xls->setStyle(array(1,2,3,4,5,6,7,8,9,10,11,12,13));
            $info=array();
            $sql="select
                s.id , p.id as pid , s.userid , u.user_name as username , s.oldname
                , s.accbank as bank, s.comedt , d.dept_name , s.olddept
                , s.oldarea , s.email , p.area , p.pyear ,p.pmon , p.baseam
                , p.basenowam , p.floatam , p.proam , p.otheram , p.bonusam
                , p.perholsdays , p.sickholsdays, p.holsdelam , p.totalam
                ,  p.gjjam , p.shbam , p.cessebase , p.paycesse , p.paytotal
                , p.othdelam , s.acc , h.address , s.sta ,s.idcard , h.card_no
                , p.sperewam , p.cogjjam ,p.coshbam ,p.prepaream ,p.handicapam
                , p.manageam , p.sdyam , p.spedelam
            from
                salary_pay p
                left join salary s on ( s.userid=p.userid )
                left join hrms h on (s.userid=h.user_id)
                left join department d on (s.deptid=d.dept_id)
                left join user u on (s.userid=u.user_id )
            where s.userid=p.userid  and p.leaveflag='0'
                and p.pyear='".date('Y')."' group by p.id order by s.id";
            $query=$this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                if($row['pmon'] < $this->nowm || date('Y')!=$this->nowy){
                    $info[$row['olddept']][$row['pmon']]=isset($info[$row['olddept']][$row['pmon']])?round($info[$row['olddept']][$row['pmon']]+$this->salaryClass->decryptDeal($row['floatam']),2):$this->salaryClass->decryptDeal($row['floatam']);
            
                }
            }
            foreach($info as $key=>$val){
                $data[]=array(
                    $key,$val['1'],$val['2'],$val['3'],$val['4'],$val['5']
                    ,$val['6'],$val['7'],$val['8'],$val['9'],$val['10']
                    ,$val['11'],$val['12']
                );
            }
            $xls->addArray($data);
            $xls->generateXML('salary');
        }
    }
    /**
     *部门入职
     * @return <type> 
     */
    function model_dp_join_list(){
        global $func_limit;
        $dppow=$this->model_dp_ctr_pow();
        $sqlflag=$this->model_dp_sql_pow($dppow);
        if($func_limit['入职管理']=='1'){
            return $this->model_hr_join_list(false,'');
        }else{
            return $this->model_hr_join_list(false,$sqlflag);
        }
    }
    /**
     *部门验证转正
     * @return <type> 
     */
    function model_dp_pass_list(){
        $dppow=$this->model_dp_ctr_pow();
        $sqlflag=$this->model_dp_sql_pow($dppow);
        //$sqlflag.=" and passdt!='' ";
        return $this->model_hr_pass_list(false,$sqlflag);
    }
        /**
     * 人事请休假上交
     */
    function model_dp_hols_hd(){
        $id=$_POST['id'];
        $uid=$_POST['uid'];
        $pud=$_POST['pub'];
        try {
            $this->db->query("START TRANSACTION");
            $sql="select
                    s.type , p.id , s.userid , u.company , u.salarycom
                from
                    hols_sta s
                    left join user u on (s.userid=u.user_id)
                    left join salary_pay p on (s.userid=p.userid and p.pyear=s.syear and p.pmon=s.smon )
                where 
                    s.userid='".  str_replace('_', '.', $uid)."' and s.id='".$id."'
                    and s.syear='".$this->nowy."' and s.smon='".$this->nowm."' ";
            $res=$this->db->get_one($sql);
            $res['com']=$res['company'];
            if(!empty($res['salarycom'])){
                $res['com']=$res['salarycom'];
            }
            if(empty($res['id'])){
                $comtable=$this->get_com_sql($res['com']);
                $sql="select p.id as pid 
                    from ".$comtable."salary_pay p 
                    where  p.pyear='" . $this->nowy . "' and p.pmon='" . $this->nowm . "' and p.userid='".$res['userid']."' ";
                $rescom=$this->db->get_one($sql);
                $res['id']=$rescom['pid'];
            }
            if(!$res['type']||!$res['id']){
                throw new Exception('Data Query failed');
            }
            
            $sql="update
                    hols_sta
                set saldays='".$pud."'
                where
                    type='".$res['type']."'
                    and syear='".$this->nowy."' and smon='".$this->nowm."'
                    and userid='".$res['userid']."' ";
            $this->db->query_exc($sql);
            if($res['type']=='事假'){
                $this->model_pay_update($res['id'],
                        array('perholsdays'=>$pud)
                        , array(0),$comtable);
                $this->model_pay_stat($res['id'],$comtable);
            }elseif($res['type']=='病假'){
                $this->model_pay_update($res['id'],
                        array('sickholsdays'=>$pud)
                        , array(0),$comtable);
                $this->model_pay_stat($res['id'],$comtable);
            }
            $this->db->query("COMMIT");
            $this->globalUtil->insertOperateLog('工资管理', 'salary', '请休假修改', '成功', $sql);
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->globalUtil->insertOperateLog('工资管理', 'salary', '请休假修改', '失败', $e->getMessage());
            echo $e->getMessage();
        }
    }
    /**
     *审批列表
     */
    function model_dp_sal_exa_list($flag=true,$sqlflag=''){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        if($flag==false){
            $sqlSch.=$sqlflag;
        }
        $start = $limit * $page - $limit;
        //总数
        $sql = "select count(*)
            from salary_flow_step fs
                left join salary_flow f on (fs.salaryfid=f.id)
                left join salary s on (f.userid=s.userid)
            where
                find_in_set('".$_SESSION['USER_ID']."',fs.dealuser)
                and fs.sta!=''
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select
                fs.rand_key , f.id , u1.user_name as username , d.dept_name as olddept , f.flowname , f.changeam , f.userid
                , f.pyear , f.pmon , fs.item , f.sta , u.user_name , f.createdt , fs.sta as fssta , sp.paytype as spetype
                , fs.res as fsres , fs.dealdt
                , f.remark , f.remark_rea , f.changedt
            from salary_flow_step fs
                left join salary_flow f on ( fs.salaryfid=f.id )
                left join salary s on (f.userid=s.userid)
                left join user u on (f.createuser=u.user_id)
                left join salary_spe sp on (f.flowname='".$this->flowName['spe']."' and f.salarykey=sp.rand_key)
                left join user u1 on (u1.user_id=f.userid)
                left join department d on (u1.dept_id=d.dept_id)
            where
                 find_in_set('".$_SESSION['USER_ID']."',fs.dealuser)
                 and fs.sta!=''
                 $sqlSch
            order by fs.sta asc , fs.dealdt desc , $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            if($this->flowName['spe']==$row['flowname']){
                if($row['spetype']=='0'){
                    $flownamet='特殊奖励';
                    $spetype='补发';
                }else{
                    $flownamet='特殊扣除';
                    $spetype='<font color="red">扣除</font>';
                }
            }else{
                $flownamet=$row['flowname'];
            }
            if($row['fssta']=='0'){
                $ck='yes';
            }else{
                $ck='no';
            }
            $responce->rows[$i]['id'] = $row['userid'];
            if($this->flowName['spe']==$row['flowname']){
                $responce->rows[$i]['cell'] = un_iconv(
                                array("", $row['rand_key'], $row['id'], $row['username']
                                    , $row['olddept']
                                    , $spetype
                                    , $this->salaryClass->decryptDeal($row['changeam'])
                                    , $row['remark']
                                    , $row['item']
                                    , $this->flowStepSta[$row['fsres']]
                                    , $row['user_name']
                                    , $row['dealdt']
                                    , $ck
                                    , $row['remark_rea']
                                )
                );
            }else{
                $responce->rows[$i]['cell'] = un_iconv(
                                array("", $row['rand_key'], $row['id'], $row['username']
                                    , $row['olddept']
                                    , $this->salaryClass->decryptDeal($row['changeam'])
                                    , $row['remark']
                                    , $row['item']
                                    , $this->flowStepSta[$row['fsres']]
                                    , $row['user_name']
                                    , $row['dealdt']
                                    , $ck
                                    , $row['remark_rea']
                                    ,(empty($row['changedt'])?'全月':$row['changedt'])
                                )
                );
            }
            $i++;
        }
        return $responce;
    }
    /**
     * 补贴审批列表
     */
    function model_dp_sal_exa_sdy_list(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        if($flag==false){
            $sqlSch.=$sqlflag;
        }
        $start = $limit * $page - $limit;
        //总数
        $sql = "select count(*)
            from salary_flow_step fs
                left join salary_flow f on (fs.salaryfid=f.id)
                left join salary_sdy sp on (f.salarykey=sp.rand_key)
                left join salary s on (f.userid=s.userid)
            where
                f.salarykey=sp.rand_key
                and find_in_set('".$_SESSION['USER_ID']."',fs.dealuser)
                and fs.sta!=''
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select
                fs.rand_key , f.id , u1.user_name as username , d.dept_name as olddept , f.flowname , f.changeam , f.userid
                , f.pyear , f.pmon , fs.item , f.sta , u.user_name , f.createdt , fs.sta as fssta 
                , fs.res as fsres , fs.dealdt
                , f.remark
                , sp.sdymeal , sp.sdyother 
            from salary_flow_step fs
                left join salary_flow f on ( fs.salaryfid=f.id )
                left join salary s on (f.userid=s.userid)
                left join user u on (f.createuser=u.user_id)
                left join salary_sdy sp on (f.salarykey=sp.rand_key)
                left join user u1 on (u1.user_id=f.userid)
                left join department d on (u1.dept_id=d.dept_id)
            where
                 f.salarykey=sp.rand_key
                 and find_in_set('".$_SESSION['USER_ID']."',fs.dealuser)
                 and fs.sta!=''
                 $sqlSch
            order by fs.sta asc , fs.dealdt desc , $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $flownamet=$row['flowname'];
            if($row['fssta']=='0'){
                $ck='yes';
            }else{
                $ck='no';
            }
            $responce->rows[$i]['id'] = $row['userid'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array("", $row['rand_key'], $row['id'], $row['username']
                                , $row['olddept']
                                , $this->salaryClass->decryptDeal($row['sdymeal'])
                                , $this->salaryClass->decryptDeal($row['sdyother'])
                                , $row['remark']
                                , $row['item']
                                , $this->flowStepSta[$row['fsres']]
                                , $row['user_name']
                                , $row['dealdt']
                                , $ck
                            )
            );
            $i++;
        }
        return $responce;
    }
    /**
     * 审批信息
     */
    function model_dp_sal_exa_info(){
        $msg='';
        $id=$_POST['id'];
        $sub=$_POST['sub'];
        if(!$id){
            return $msg='<font color="red">读取数据失败</font>';
        }
        $info=array();
        if($sub=='fid'){
            $sql="select fs.item , fs.step ,fs.dealuser , fs.res , fs.remark , fs.dealdt
                from
                    salary_flow_step fs
                    left join salary_flow f on (fs.salaryfid=f.id)
                where
                    f.rand_key='".$id."' ";
        }else{
            $sql="select fs.item , fs.step ,fs.dealuser , fs.res , fs.remark , fs.dealdt
                from
                    salary_flow_step fs
                    left join salary_flow f on (fs.salaryfid=f.id)
                    left join salary_flow_step sp on (f.id=sp.salaryfid)
                where
                    sp.rand_key='".$id."' ";
        }
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $info[$row['step']]['item']=$row['item'];
            $info[$row['step']]['dealuser']=$row['dealuser'];
            $info[$row['step']]['res']=$row['res'];
            $info[$row['step']]['dealdt']=$row['dealdt'];
            $info[$row['step']]['remark']=$row['remark'];
        }
        if(count($info)){
            $msg.='<table style="text-align: center;"><tr><td width="80">审批步骤</td><td width="80">审批人</td>
                <td width="80">审批结果</td><td width="120">审批日期</td><td width="138">意见</td></tr>';
            foreach($info as $key=>$val){
                $dn='';
                $rs='未审';
                if($val['dealuser']){
                    $sql="select user_name from user where user_id in ('".  str_replace(',', "','", $val['dealuser'])."') ";
                    $query=$this->db->query($sql);
                    while($row=$this->db->fetch_array($query)){
                        $dn.=$row['user_name'].';';
                    }
                    $dn=trim($dn,';');
                }
                if($val['res']=='yes'){
                    $rs='<font color="green">同意</font>';
                }elseif($val['res']=='no'){
                    $rs='<font color="red">不同意</font>';
                }
                $msg.='<tr>
                        <td>'.$val['item'].'</td>
                        <td>'.$dn.'</td>
                        <td>'.$rs.'</td>
                        <td>'.$val['dealdt'].'</td>
                        <td>'.$val['remark'].'</td>
                    </tr>';
            }
            $msg.='</table>';
        }
        return $msg;
    }
    /**
     * 审批处理检查
     */
    function model_dp_sal_exa_ck(){
        $id = $_POST['id'];
        $ck=0;
        $ckid='';
        if(!empty($id)){
            if(is_array($id)){
                $ckid=implode(',',$id);
            }else{
                $ckid=$id;
            }
            $sql="select
                    fs.rand_key
                from
                    salary_flow_step fs
                where
                    fs.rand_key in ('".str_replace(',', "','", $ckid)."')
                    and find_in_set('".$_SESSION['USER_ID']."',fs.dealuser)
                    and fs.sta='0' ";
            $query=$this->db->query_exc($sql);
            while($row=$this->db->fetch_array($query)){
                if($row['rand_key']){
                    $ck=1;
                }
            }
        }
        return $ck;
    }
    /**
     * 工资审批处理
     */
    function model_dp_sal_exa_in(){
        $id = $_POST['id'];
        $res = $_POST['res'];
        $remark = $_POST['remark'];
        $sub = $_POST['sub'];
        try {
            if($sub=='all'&&!empty($id)){
                $idArr=array();
                $sql="select
                        fs.rand_key
                    from
                        salary_flow_step fs
                    where
                        fs.rand_key in ('".str_replace(',', "','", $id)."')
                        and find_in_set('".$_SESSION['USER_ID']."',fs.dealuser)
                        and fs.sta='0' ";
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    $idArr[]=$row['rand_key'];
                }
                if(!empty($idArr)){
                    foreach($idArr as $val){
                        $responce->error=$this->model_flow_do($val, $res, $remark);
                    }
                }
                $responce->id = $id;
            }elseif(!empty($id)){
                $responce->error=$this->model_flow_do($id, $res, $remark);
                $responce->id = $id;
            }
        } catch (Exception $e) {
            $responce->error = $e->getMessage();
        }
        return $responce;
    }
    /**
     *撤销审批申请
     * @param type $key 
     */
    function model_dp_sal_exa_del($key){
        $sql="select sta , id  from salary_flow where rand_key = '".$key."' ";
        $ck= $this->db->get_one($sql);
        if($ck['sta']=='0'){
            $sql="delete from salary_flow where id= '".$ck['id']."' ";
            $this->db->query_exc($sql);
            $sql="delete from salary_flow_step where salaryfid= '".$ck['id']."' ";
            $this->db->query_exc($sql);
            $res['succ']=1;
        }else{
            $res['error']='审批申请单据已有领导审批，撤销失败';
        }
        return $res;
    }
    /**
     *更新列表
     */
    function model_dp_mdf_list($ty,$vty=''){
        if(!$ty||empty ($ty)){
            throw new Exception('Query type error');
        }
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        $start = $limit * $page - $limit;
        if(is_array($ty)){
            $sqlCk=" f.flowname in ( ";
            foreach($ty as $val){
                $sqlCk.="'".$this->flowName[$val]."' ,";
            }
            $sqlCk=trim($sqlCk,',');
            $sqlCk.=" ) ";
        }else{
            $sqlCk=" f.flowname ='".$this->flowName[$ty]."' ";
        }
        //总数
        $sql = "select count(*)
            from salary_flow f
                left join salary s on (f.salarykey=s.rand_key and f.userid=s.userid)
                left join salary_flow_step fs on (f.id=fs.salaryfid and fs.sta='0')
            where
                f.createuser='".$_SESSION['USER_ID']."' and 
                $sqlCk
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select
                f.rand_key , s.username  , s.olddept , f.changeam , f.pyear , f.pmon , f.sta , fs.item , f.createdt ,f.remark
                , f.proname , f.prono , f.changedt, f.remark_rea
            from salary_flow f
                left join salary s on (f.userid=s.userid)
                left join salary_flow_step fs on (f.id=fs.salaryfid and fs.sta='0')
            where
                f.createuser='".$_SESSION['USER_ID']."' and 
                $sqlCk
                $sqlSch
            order by $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            if($row['sta']=='1'){
                $sta='待审：'.$row['item'];
            }else{
                $sta=$this->flowSta[$row['sta']];
            }
            if(empty($row['changedt'])){
                $chdt='全月';
            }else{
                $chdt=$row['changedt'];
            }
            $responce->rows[$i]['id'] = $row['rand_key'];
            if(in_array('pro', $ty)){
                $responce->rows[$i]['cell'] = un_iconv(
                                array("", $row['rand_key']
                                    , $row['username']
                                    , $row['olddept']
                                    , $this->salaryClass->decryptDeal($row['changeam'])
                                    , $row['prono']
                                    , $row['proname']
                                    , $row['pyear'].'-'.$row['pmon']
                                    , $row['remark']
                                    , $sta
                                    , $row['createdt']
                                    , $row['remark_rea']
                                )
                );
            }else{
                if($vty=='nym'){
                    $responce->rows[$i]['cell'] = un_iconv(
                                array("", $row['rand_key']
                                    , $row['username']
                                    , $row['olddept']
                                    , $this->salaryClass->decryptDeal($row['changeam'])
                                    , $row['pyear'].'-'.$row['pmon']
                                    , $chdt
                                    , $row['remark_rea']
                                    , $row['remark']
                                    , $sta
                                    , $row['createdt']
                                    , $row['sta']
                                )
                    );
                }else{
                    $responce->rows[$i]['cell'] = un_iconv(
                                array("", $row['rand_key']
                                    , $row['username']
                                    , $row['olddept']
                                    , $this->salaryClass->decryptDeal($row['changeam'])
                                    , $row['pyear'].'-'.$row['pmon']
                                    , $chdt
                                    , $row['remark']
                                    , $sta
                                    , $row['createdt']
                                )
                    );
                }
                
            }
            $i++;
        }
        return $responce;
    }
    /**
     * 非年度调薪
     */
    function model_dp_nym_in(){
        //print_r($_POST);
        //die();
        $id = $_POST['id'];
        $sub= $_POST['sub'];
        $amount=$_POST['amount'];
        $remark=$_POST['remark'];
        $remark_rea=$_POST['remark_rea'];
        $uty = $_POST['uty'];
        $udt = $_POST['udt'];
        $chdt = '';
        $sm=false;
        try {
            $this->db->query("START TRANSACTION");
            if($sub=='new'){
                if(!$id||!trim($id)){
                    throw new Exception('no user id');
                }
                if(!$_SESSION['USER_ID']){
                    throw new Exception('time out');
                }
                $tmpua=explode(',', $id);
                if($uty=='part'){
                    $chdt=$udt;
                }
                $sql="select userlevel , user_id from hrms where user_id in ('".implode("','", $tmpua)."')";
                $query=$this->db->query($sql);
                $tmpua=array();
                while($row=$this->db->fetch_array($query)){
                    $tmpua[$row['user_id']]=$row['userlevel'];
                }
                if(count($tmpua)){
                    foreach($tmpua as $key=>$val){
                        if($val!='0'&&(!$val||$val=='')){
                            continue;
                        }
                        $info=array('flowname'=>$this->flowName['nym_'.$val]
                            ,'userid'=>$key
                            ,'salarykey'=>''
                            ,'changeam'=>$this->salaryClass->encryptDeal($amount)
                            ,'remark'=>$remark
                            ,'changedt'=>$chdt
                            ,'remark_rea'=>$remark_rea
                            );
                        $sm[$key]=$this->model_flow_new($info);
                    }
                }
            }
            $this->db->query("COMMIT");
            if(is_array($sm)){
                if(count($sm)){
                    foreach($sm as $val){
                        $body='您好！<br><br>
                        系统内有非年度调薪工资数据，需要您的审批。<br>
                        此项数据由'.$_SESSION['USER_NAME'].'向您提交<br>
                        谢谢！';
                        $this->model_send_email('审批--非年度调薪', $body, $val, false,true);
                    }
                }
            }elseif($sm){
                $body='您好！<br><br>
                        系统内有非年度调薪工资数据，需要您的审批。<br>
                        此项数据由'.$_SESSION['USER_NAME'].'向您提交<br>
                        谢谢！';
                $this->model_send_email('审批--非年度调薪', $body, $sm, false,true);
            }
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '非年度调薪', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '非年度调薪', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
     *调薪数据
     * @return string
     */
    function model_dp_nym_xls(){
        set_time_limit(600);
        global $func_limit;
        $sqlflag='';
        $dppow=$this->model_dp_pow();
        if(!empty($func_limit['浏览部门'])){
            $sqlflag=" and ( s.deptid in ('". implode("','", $dppow['1'])."','" . implode("','", $dppow['2']) ."')
                or s.userid='".$_SESSION['USER_ID']."'
                or s.deptid in ( ".trim($func_limit['浏览部门'],',')." )
                ) ";
        }else{
            $sqlflag=" and ( s.deptid in ('". implode("','", $dppow['1'])."','" . implode("','", $dppow['2']) ."')
                or s.userid='".$_SESSION['USER_ID']."'
                ) ";
        }
        $flag=$_REQUEST['flag'];
        $type=$_POST['ctr_type'];
        $sqlCk=$type=='com'?'0':'1';
        $str='';
        $ckt=time();
        $infoE=array();
        $code=$flag=='year'?'amount-year':'amount';
        try{
            $sql="delete from salary_temp where code = '".$code."' and creator='".$_SESSION['USER_ID']."' ";
            $this->db->query_exc($sql);
            $excelfilename='attachment/xls_model/dp_nym/'.$ckt.".xls";
            if(empty ($_FILES["ctr_file"]["tmp_name"])){
                $str='<tr><td colspan="5">请导入调薪数据表！</td></tr>';
            }elseif (!move_uploaded_file( $_FILES["ctr_file"]["tmp_name"], $excelfilename) ){
                $str='<tr><td colspan="5">上传失败！</td></tr>';
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
                if($flag=='year'){
                    if(!in_array('姓名', $excelFields)||!in_array('员工号', $excelFields)
                            ||!in_array('调整后工资', $excelFields)){
                        throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                    }
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['姓名'][$key];
                            $infoE[$val]['amount']=$excelArr['调整后工资'][$key];
                            $infoE[$val]['dept']=$excelArr['部门'][$key];
                            $infoE[$val]['remark']='年度调薪';
                        }
                    }
                }else{
                    if(!in_array('员工', $excelFields)||!in_array('员工号', $excelFields)
                            ||!in_array('调薪后金额', $excelFields)||!in_array('申请原因', $excelFields)
                            ||!in_array('工资条备注', $excelFields)){
                        throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                    }
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['员工'][$key];
                            $infoE[$val]['amount']=$excelArr['调薪后金额'][$key];
                            $infoE[$val]['remark_rea']=$excelArr['申请原因'][$key];
                            $infoE[$val]['remark']=$excelArr['工资条备注'][$key];
                        }
                    }
                }
                if($flag=='year'){
                    $sql="select
                            s.username , s.userid , h.usercard as idcard , d.dept_name
                        from
                            salary s
                            left join hrms h on(s.userid=h.user_id)
                            left join user u on (s.userid=u.user_id)
                            left join department d on (u.dept_id=d.dept_id)
                        where 1 
                            and s.usersta!=3
                            and h.userlevel=4 ";
                }else{
                    $sql="select
                            s.username , s.userid , h.usercard as idcard , d.dept_name
                        from
                            salary s
                            left join hrms h on (s.userid=h.user_id)
                            left join user u on (s.userid=u.user_id)
                            left join department d on (u.dept_id=d.dept_id)
                        where 1 ".$sqlflag;
                }
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    $infoA[]=$row['idcard'];
                    if(array_key_exists($row['idcard'],$infoE)){
                        $infoE[$row['idcard']]['type']=0;
                        $infoE[$row['idcard']]['name']=$row['username'];
                        $infoE[$row['idcard']]['dept']=$row['dept_name'];
                    }
                }
                if(count($infoE)){
                    foreach($infoE as $key=>$val){
                        if(!in_array($key,$infoA)){
                            $infoE[$key]['type']=1;
                        }
                    }
                }
                $this->db->query("START TRANSACTION");
                if(count($infoE)){
                    //print_r($infoE);
                    foreach($infoE as $key=>$val){
                        if($val['type']=='0'){
                            $cl='green';
                        }else{
                            $cl='#FF9900';
                        }
                        $str.='<tr style="color:'.$cl.'">
                            <td>'.$key.'</td>
                            <td>'.$val['name'].'</td> 
                            <td>'.$val['amount'].'</td>
                            <td>'.$val['remark_rea'].'</td>
                            <td>'.$val['remark'].'</td>
                            <td>'.($val['type']=='0'?'生效':'员工号匹配不成功或该员工非本部门员工').'</td>
                            </tr>';
                        if($val['type']=='0'&&$key){
                            $sql="insert into salary_temp ( idcard , code , amount , remark , type , creator , remark_rea)
                                  values ( '".$key."' , '".$code."' , '".round($val['amount'],2)."'
                                      , '".$val['remark']."' , 'salary' ,'".$_SESSION['USER_ID']."' , '".$val['remark_rea']."' )";
                            $this->db->query_exc($sql);
                        }
                    }
                }
                $this->db->query("COMMIT");
            }
        }catch(Exception $e){
            $this->db->query("ROLLBACK");
            $str='<tr><td colspan="5">导入数据错误！'.$e->getMessage().'</td></tr>';
        }
        return $str;
    }
    /**
     *调薪数据导入
     * @return <type>
     */
    function model_dp_nym_xls_in(){
        try {
            $info=array();
            $this->db->query("START TRANSACTION");
            $sql="select
                    t.amount , h.userlevel , s.userid , t.remark , t.code , t.remark_rea
                from salary_temp t
                    left join hrms h on (t.idcard=h.usercard)
                    left join salary s on ( s.userid=h.user_id)
                where t.code in ( 'amount' , 'amount-year' ) and t.creator='".$_SESSION['USER_ID']."' ";
            $query=$this->db->query_exc($sql);
            if(!$this->db->affected_rows()){
                throw new Exception('No updated data');
            }
            while($row=$this->db->fetch_array($query)){
                $info[$row['userid']]['amount']=$row['amount'];
                $info[$row['userid']]['ul']=$row['userlevel'];
                $info[$row['userid']]['remark']=$row['remark'];
                $info[$row['userid']]['flag']=$row['code'];
                $info[$row['userid']]['remark_rea']=$row['remark_rea'];
            }
            if(count($info)){
                foreach($info as $key=>$val){
                    $flowname=$val['flag']=='amount-year'?$this->flowName['ymd']:$this->flowName['nym_'.$val['ul']];
                    $info=array('flowname'=>$flowname
                        ,'userid'=>$key
                        ,'salarykey'=>''
                        ,'changeam'=>$this->salaryClass->encryptDeal($val['amount'])
                        ,'remark'=>$val['remark']
                        ,'remark_rea'=>$val['remark_rea']
                        );
                    $sm[$key]=$this->model_flow_new($info);
                }
            }
            if(empty($sm)){
                foreach($sm as $val){
                    $body='您好！<br><br>
                        系统内有非年度调薪工资数据，需要您的审批。<br>
                        此项数据由'.$_SESSION['USER_NAME'].'向您提交<br>
                        谢谢！';
                    $this->model_send_email('审批--非年度调薪', $body, $val, false,true);
                }
            }
            $this->db->query("COMMIT");
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '调薪', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '调薪', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
     * 奖金
     */
    function model_dp_bos_in(){
        $id = $_POST['id'];
        $sub= $_POST['sub'];
        $amount=$_POST['amount'];
        $remark=$_POST['remark'];
        $sm=false;
        try {
            $this->db->query("START TRANSACTION");
            if($sub=='new'){
                if(!$id||!trim($id)){
                    throw new Exception('no user id');
                }
                if(!$_SESSION['USER_ID']){
                    throw new Exception('time out');
                }
                $tmpua=explode(',', $id);
                $sql="select userlevel , user_id from hrms where user_id in ('".implode("','", $tmpua)."')";
                $query=$this->db->query($sql);
                $tmpua=array();
                while($row=$this->db->fetch_array($query)){
                    $tmpua[$row['user_id']]=$row['userlevel'];
                }
                if(count($tmpua)){
                    foreach($tmpua as $key=>$val){
                        if(!$val||$val==''){
                            continue;
                        }
                        $info=array('flowname'=>$this->flowName['bos']
                            ,'userid'=>$key
                            ,'salarykey'=>''
                            ,'changeam'=>$this->salaryClass->encryptDeal($amount)
                            ,'remark'=>$remark
                            );
                        $sm[$key]=$this->model_flow_new($info);
                    }
                }
            }
            $this->db->query("COMMIT");
            if(is_array($sm)){
                if(count($sm)){
                    foreach($sm as $val){
                        $body='您好！<br>
                            工资审批-项目奖，需要您的审批。<br>
                            谢谢！';
                        $this->model_send_email('审批--奖金', $body, $val, false);
                    }
                }
            }elseif($sm){
                $body='您好！<br>
                    工资审批-项目奖，需要您的审批。<br>
                    谢谢！';
                $this->model_send_email('审批--奖金', $body, $sm, false);
            }
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '奖金', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '奖金', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
     *
     */
    function model_dp_bos_xls($ckt){
        set_time_limit(600);
        $type=$_POST['ctr_type'];
        $sqlCk=$type=='com'?'0':'1';
        $str='';
        $infoE=array();
        try{
            $sql="delete from salary_temp where code = 'amount' and creator='".$_SESSION['USER_ID']."' ";
            $this->db->query_exc($sql);
            $excelfilename='attachment/xls_model/bos/'.$ckt.".xls";
            if(empty ($_FILES["ctr_file"]["tmp_name"])){
                $str='<tr><td colspan="10">请导入奖金数据表！</td></tr>';
            }elseif (!move_uploaded_file( $_FILES["ctr_file"]["tmp_name"], $excelfilename) ){
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
                if(!in_array('员工', $excelFields)||!in_array('员工号', $excelFields)
                        ||!in_array('奖金', $excelFields)||!in_array('备注', $excelFields))
                {
                    throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                }
                if(count($excelArr)&&!empty($excelArr)){
                    foreach($excelArr['员工号'] as $key=>$val ){
                        $infoE[$val]['name']=$excelArr['员工'][$key];
                        $infoE[$val]['pro']=$excelArr['奖金'][$key];
                        $infoE[$val]['remark']=$excelArr['备注'][$key];
                    }
                }
                $sql="select
                        s.username , s.userid , h.usercard as idcard
                    from
                        salary s
                        left join hrms h on(s.userid=h.user_id)
                    where s.usersta!=3  ";
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    $infoA[]=$row['idcard'];
                    if(array_key_exists($row['idcard'],$infoE)){
                        $infoE[$row['idcard']]['type']=0;
                        $infoE[$row['idcard']]['name']=$row['username'];
                    }
                }
                if(count($infoE)){
                    foreach($infoE as $key=>$val){
                        if(!in_array($key,$infoA)){
                            $infoE[$row['idcard']]['type']=1;
                        }
                    }
                }
                if(count($infoE)){
                    $totalA=array('pro'=>0);
                    foreach($infoE as $key=>$val){
                        if(empty($key)){
                            continue;
                        }
                        if($val['type']=='0'){
                            $cl='green';
                        }elseif($val['type']=='1'){
                            $cl='red';
                        }
                        $totalA['pro']=$totalA['pro']+$val['pro'];
                        $str.='<tr style="color:'.$cl.'">
                                <td>'.$key.'</td>
                                <td>'.$val['name'].'</td>
                                <td>'.$val['pro'].'</td>
                                <td>'.$val['remark'].'</td>
                            </tr>';
                    }
                }
                $str.='<tr style="color:red">
                    <td></td>
                    <td>合计：</td>
                    <td>'.$totalA['pro'].'</td>
                    <td></td>
                </tr>';
            }
        }catch(Exception $e){
            $str='<tr><td colspan="10">导入数据错误！'.$e->getMessage().'</td></tr>';
        }
        return $str;
    }
    /**
     * 导入初始化外派
     */
    function model_dp_bos_xls_in(){
        set_time_limit(600);
        $ckt=$_POST['ckt'];
        $excelfilename=WEB_TOR.'attachment/xls_model/bos/'.$ckt.".xls";
        try{
            if(!file_exists($excelfilename)){
                throw new Exception('File does not exist');
            }
            include('includes/classes/excel.php');
            $excel = Excel::getInstance();
            $excel->setFile($excelfilename);
            $excel->Open();
            $excel->setSheet();
            $excelFields = $excel->getFields();
            $excelArr=$excel->getAllData();
            $excel->Close();
            if(!in_array('员工', $excelFields)||!in_array('员工号', $excelFields)
                        ||!in_array('奖金', $excelFields)||!in_array('备注', $excelFields))
            {
                throw new Exception('Update failed');
            }
            if(count($excelArr)&&!empty($excelArr)){
                foreach($excelArr['员工号'] as $key=>$val ){
                    $infoE[$val]['name']=$excelArr['员工'][$key];
                    $infoE[$val]['pro']=$excelArr['奖金'][$key];
                    $infoE[$val]['remark']=$excelArr['备注'][$key];
                }
            }
            if(count($infoE)){
                foreach($infoE as $key=>$val){
                    $sql="select s.rand_key , p.id as pid , s.userid  from salary s
                        left join salary_pay p on (s.userid=p.userid and p.pyear='".$this->nowy."' and pmon='".$this->nowm."')
                        left join hrms h on (s.userid=h.user_id)
                        where  h.usercard='".$key."' ";
                    $res=$this->db->get_one($sql);
                    if(!empty($res)){
                        $info=array('flowname'=>$this->flowName['bos']
                            ,'userid'=>$res['userid']
                            ,'salarykey'=>''
                            ,'changeam'=>$this->salaryClass->encryptDeal($val['pro'])
                            ,'remark'=>$val['remark']
                            );
                        $sm[$key]=$this->model_flow_new($info);
                    }
                }
            }
            if(is_array($sm)){
                if(count($sm)){
                    foreach($sm as $val){
                        $body='您好！<br>
                            工资审批-奖金，需要您的审批。<br>
                            谢谢！';
                        $this->model_send_email('审批--奖金', $body, $val, false);
                    }
                }
            }
        }catch(Exception $e){
            $responce->error = un_iconv($e->getMessage());
        }
        return $responce;
    }
    
    /**
     *入职导入检验
     */
    function model_hr_join_xls($ckt,$hrflag='fn'){
        set_time_limit(600);
        $infoE=array();
        try{
            
            $excelfilename='attachment/xls_model/join/'.$ckt.".xls";
            if(empty ($_FILES["ctr_file"]["tmp_name"])){
                $str='<tr><td colspan="10">请导入信息！</td></tr>';
            }elseif (!move_uploaded_file( $_FILES["ctr_file"]["tmp_name"], $excelfilename) ){
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
                if($hrflag=='hr'){//入职
                    if(!in_array('姓名', $excelFields)||!in_array('员工号', $excelFields))
                    {
                        throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                    }
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['姓名'][$key];
                            $infoE[$val]['comedt']=$excelArr['日期'][$key];
                            $infoE[$val]['am']=$excelArr['工资金额'][$key];
                            $infoE[$val]['idcard']=$excelArr['身份证'][$key];
                            $infoE[$val]['acc']=$excelArr['账号'][$key];
                            $infoE[$val]['accbank']=$excelArr['开户行'][$key];
                            $infoE[$val]['type']=1;
                        }
                    }
                    $sql="select
                        h.usercard , s.username , h.userlevel 
                    from salary s
                        left join hrms h on ( s.userid=h.user_id )
                    where
                        s.userid=h.user_id
                        and ( s.usersta='0' or
                                ( year(s.probationdt)='".$this->nowy."' and  month(s.probationdt)='".$this->nowm."' )
                            )
                    order by s.id 
                    ";
                    $query=$this->db->query_exc($sql);
                    while($row=$this->db->fetch_array($query)){
                        if(array_key_exists($row['usercard'],$infoE)){
                            $infoE[$row['usercard']]['type']=0;
                            $infoE[$row['usercard']]['userlevel']=$row['userlevel'];
                            if($infoE[$row['usercard']]['name']!=$row['username']){
                                $infoE[$row['usercard']]['type']=3;//名字不对
                                $infoE[$row['usercard']]['ckname']=$row['username'];//名字不对
                            }
                        }
                    }
                    if(count($infoE)){
                        $i=1;
                        foreach($infoE as $key=>$val){
                            if(empty($key)){
                                continue;
                            }
                            if($val['type']=='0'){
                                $cl='green';
                                $rk='';
                            }elseif($val['type']=='1'){
                                $cl='#ff9900';
                                $rk='员工号错误或已入职';
                            }elseif($val['type']=='3'){
                                $cl='#ff9900';
                                $rk='名称不一致，请确认是否更新名字：'.$val['name'];
                            }
                            if($val['userlevel']!=4){
                                $rk.='该员工是管理岗位，导入信息不更新工资金额';
                            }
                            $str.='<tr style="color:'.$cl.'">
                                    <td>'.$i.'</td>
                                    <td>'.$key.'</td>
                                    <td>'.$val['name'].'</td>
                                    <td>'.$val['comedt'].'</td>
                                    <td>'.$val['am'].'</td>
                                    <td>'.$val['idcard'].'</td>
                                    <td>'.$val['acc'].'</td>
                                    <td>'.$val['accbank'].'</td>
                                    <td>'.$rk.'</td>
                                </tr>';
                            $i++;
                        }
                    }
                }elseif($hrflag=='pass'){
                    if(!in_array('姓名', $excelFields)||!in_array('员工号', $excelFields))
                    {
                        throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                    }
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['姓名'][$key];
                            $infoE[$val]['passdt']=$excelArr['转正日期'][$key];
                            $infoE[$val]['am']=$excelArr['转正工资'][$key];
                            $infoE[$val]['type']=1;
                        }
                    }
                    $sql="select
                        h.usercard , s.username , h.userlevel 
                    from salary s
                        left join hrms h on ( s.userid=h.user_id )
                    where
                        s.userid=h.user_id
                        and ( s.usersta='1' or
                                ( year(s.passdt)='".$this->nowy."' and  month(s.passdt)='".$this->nowm."' )
                            )
                    order by s.id 
                    ";
                    $query=$this->db->query_exc($sql);
                    while($row=$this->db->fetch_array($query)){
                        if(array_key_exists($row['usercard'],$infoE)){
                            $infoE[$row['usercard']]['type']=0;
                            $infoE[$row['usercard']]['userlevel']=$row['userlevel'];
                            if($infoE[$row['usercard']]['name']!=$row['username']){
                                $infoE[$row['usercard']]['type']=3;//名字不对
                                $infoE[$row['usercard']]['names']=$row['username'];//名字不对
                            }
                        }
                    }
                    if(count($infoE)){
                        $i=1;
                        foreach($infoE as $key=>$val){
                            if(empty($key)){
                                continue;
                            }
                            if($val['type']=='0'){
                                $cl='green';
                                $rk='';
                            }elseif($val['type']=='1'){
                                $cl='#ff9900';
                                $rk='员工号错误或已转正';
                            }elseif($val['type']=='3'){
                                $cl='#ff9900';
                                $rk='名称不一致，请确认是否更新名字：'.$val['names'];
                            }
                            if($val['userlevel']!=4){
                                $rk.='该员工是管理岗位，导入信息不更新工资金额';
                            }
                            $str.='<tr style="color:'.$cl.'">
                                    <td>'.$i.'</td>
                                    <td>'.$key.'</td>
                                    <td>'.$val['name'].'</td>
                                    <td>'.$val['passdt'].'</td>
                                    <td>'.$val['am'].'</td>
                                    <td>'.$rk.'</td>
                                </tr>';
                            $i++;
                        }
                    }
                }elseif($hrflag=='spe'){//补发
                    if(!in_array('姓名', $excelFields)||!in_array('员工号', $excelFields)
                    ||!in_array('类型', $excelFields)||!in_array('是否计税', $excelFields)||!in_array('金额', $excelFields)||!in_array('备注', $excelFields))
                    {
                        throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                    }
                    $ptyarr=array_flip($this->speType);
                    $ctyarr=array_flip($this->accType);
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['姓名'][$key];
                            $infoE[$val]['pty']=$ptyarr[$excelArr['类型'][$key]];
                            $infoE[$val]['aty']=$ctyarr[$excelArr['是否计税'][$key]];
                            $infoE[$val]['amount']=$excelArr['金额'][$key];
                            $infoE[$val]['remark']=$excelArr['备注'][$key];
                            $infoE[$val]['type']=1;
                        }
                    }
                    $sql="select
                        h.usercard , s.username , h.userlevel 
                    from salary s
                        left join hrms h on ( s.userid=h.user_id )
                    where
                        s.userid=h.user_id
                    order by s.id 
                    ";
                    $query=$this->db->query_exc($sql);
                    while($row=$this->db->fetch_array($query)){
                        if(array_key_exists($row['usercard'],$infoE)){
                            $infoE[$row['usercard']]['type']=0;
                            $infoE[$row['usercard']]['userlevel']=$row['userlevel'];
                            if($infoE[$row['usercard']]['name']!=$row['username']){
                                $infoE[$row['usercard']]['type']=3;//名字不对
                                $infoE[$row['usercard']]['names']=$row['username'];//名字不对
                            }
                        }
                    }
                    if(count($infoE)){
                        $i=1;
                        foreach($infoE as $key=>$val){
                            if(empty($key)){
                                continue;
                            }
                            if($val['type']=='0'){
                                $cl='green';
                                $rk='通过';
                            }elseif($val['type']=='1'){
                                $cl='#ff9900';
                                $rk='员工号错误或已转正';
                            }elseif($val['type']=='3'){
                                $cl='#ff9900';
                                $rk='名称不一致，请确认是否更新名字：'.$val['names'];
                            }
                            $str.='<tr style="color:'.$cl.'">
                                    <td>'.$i.'</td>
                                    <td>'.$key.'</td>
                                    <td>'.$val['name'].'</td>
                                    <td>'.$this->speType[$val['pty']].'</td>
                                    <td>'.$this->accType[$val['aty']].'</td>
                                    <td>'.$val['amount'].'</td>
                                    <td>'.$val['remark'].'</td>
                                    <td>'.$rk.'</td>
                                </tr>';
                            $i++;
                        }
                    }
                }
                
            }
        }catch(Exception $e){
            $str='<tr><td colspan="10">导入数据错误！'.$e->getMessage().'</td></tr>';
        }
        return $str;
    }
    /**
     * 入职导入初始化外派
     */
    function model_hr_join_xls_in($hrflag='fn'){
        set_time_limit(600);
        $ckt=$_POST['ckt'];
        $manflag=$_GET['manflag'];
        $excelfilename=WEB_TOR.'attachment/xls_model/join/'.$ckt.".xls";
        try{
            if(!file_exists($excelfilename)){
                throw new Exception('File does not exist');
            }
            include('includes/classes/excel.php');
            $excel = Excel::getInstance();
            $excel->setFile($excelfilename);
            $excel->Open();
            $excel->setSheet();
            $excelFields = $excel->getFields();
            $excelArr=$excel->getAllData();
            $excel->Close();
            if($hrflag=='hr'){//入职
                if(!in_array('姓名', $excelFields)||!in_array('员工号', $excelFields))
                {
                    throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                }
                if(count($excelArr)&&!empty($excelArr)){
                    foreach($excelArr['员工号'] as $key=>$val ){
                        $infoE[$val]['name']=$excelArr['姓名'][$key];
                        $infoE[$val]['comedt']=$excelArr['日期'][$key];
                        $infoE[$val]['am']=$excelArr['工资金额'][$key];
                        $infoE[$val]['idcard']=$excelArr['身份证'][$key];
                        $infoE[$val]['acc']=$excelArr['账号'][$key];
                        $infoE[$val]['accbank']=$excelArr['开户行'][$key];
                        $infoE[$val]['type']=1;
                    }
                }
                $sql="select
                    h.usercard , s.username , h.userlevel , p.id as pid , s.rand_key
                    , u.company , u.salarycom
                from salary s
                    left join hrms h on ( s.userid=h.user_id )
                    left join user u on ( u.user_id=s.userid)
                    left join salary_pay p on ( p.pyear='" . $this->nowy . "' and p.pmon='" . $this->nowm . "' and p.userid=s.userid )
                where
                    s.userid=h.user_id
                    and ( s.usersta='0' or
                            ( year(s.probationdt)='".$this->nowy."' and  month(s.probationdt)='".$this->nowm."' )
                        )
                order by s.id 
                ";
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    if(array_key_exists($row['usercard'],$infoE)){
                        $infoE[$row['usercard']]['type']='0';
                        $infoE[$row['usercard']]['userlevel']=$row['userlevel'];
                        //$infoE[$row['usercard']]['pid']=$row['pid'];
                        $infoE[$row['usercard']]['com']=$row['company'];
                        if(!empty($row['salarycom'])){
                            $infoE[$row['usercard']]['com']=$row['salarycom'];
                        }
                        $infoE[$row['usercard']]['sid']=$row['rand_key'];
                    }
                }
                if(count($infoE)){
                    try {
                        $this->db->query("START TRANSACTION");

                        foreach($infoE as $key=>$val){
                            if($val['type']=='0'){
                                if($val['userlevel']==4){//非管理层员工
                                    $comtable=$this->get_com_sql($val['com']);
                                    $sql="select
                                            p.id as pid 
                                        from salary s
                                            left join ".$comtable."salary_pay p 
                                                on ( p.pyear='" . $this->nowy . "' and p.pmon='" . $this->nowm . "' and p.userid=s.userid )
                                        where
                                            s.rand_key='".$val['sid']."'";
                                    $rescom=$this->db->get_one($sql);
                                    $val['pid']=$rescom['pid'];
                                    if (strtotime($val['comedt']) < strtotime(date('Y-m') . '-01')) {
                                        $baseNow = 0;
                                    } else {
                                        $baseNow = $this->salaryClass->salaryDeal($val['comedt'], $val['am']);
                                    }
                                    $this->model_salary_update($val['sid'],
                                            array('amount' => $val['am'], 'probationam' => $val['am'], 'usersta' => '1'
                                                , 'probationdt' => 'now()', 'probationuser' => $_SESSION['USER_ID']
                                                ,'probationnowam'=>$this->salaryClass->salaryDeal($val['comedt'], $val['am'])
                                                ,'oldname'=>$val['name']
                                                ,'idcard'=>$val['idcard'],'acc'=>$val['acc']
                                                ,'accbank'=>$val['accbank']
                                                ,'comedt' =>date('Ymd',strtotime($val['comedt']))
                                            )
                                            , array(2, 3, 4,6,7,8,9,10,11,12,13));
                                    $this->model_pay_update($val['pid'],
                                            array('baseam' => $val['am'], 'basenowam' => $baseNow, 'nowamflag' => '1', 'leaveflag'=>'0')
                                            , array(2,3),$comtable);
                                    $this->model_pay_stat($val['pid'],$comtable);
                                }else{//管理层
                                    $this->model_salary_update($val['sid'],
                                            array(
                                                'oldname'=>$val['name']
                                                ,'idcard'=>$val['idcard'],'acc'=>$val['acc']
                                                ,'accbank'=>$val['accbank']
                                                ,'comedt' =>date('Ymd',strtotime($val['comedt']))
                                            ),array(0,1,2,3,4,5,6,7)
                                    );
                                }
                            }
                        }

                        $this->db->query("COMMIT");
                        $this->globalUtil->insertOperateLog('入职导入', '入职导入', '入职导入', '成功');
                    } catch (Exception $e) {
                        $this->db->query("ROLLBACK");
                        $responce->error = un_iconv($e->getMessage());
                        $this->globalUtil->insertOperateLog('入职导入', '入职导入' , '入职导入', '失败', $e->getMessage());
                    }
                }
            }elseif($hrflag=='pass'){//转正
                if(!in_array('姓名', $excelFields)||!in_array('员工号', $excelFields))
                {
                    throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                }
                if(count($excelArr)&&!empty($excelArr)){
                    foreach($excelArr['员工号'] as $key=>$val ){
                        $infoE[$val]['name']=$excelArr['姓名'][$key];
                        $infoE[$val]['passdt']=$excelArr['转正日期'][$key];
                        $infoE[$val]['am']=$excelArr['转正工资'][$key];
                        $infoE[$val]['type']=1;
                    }
                }
            
                $sql="select
                    h.usercard , s.username , h.userlevel , p.id as pid , s.rand_key
                    , u.company , u.salarycom
                    , s.amount , s.usersta , s.passoldam , s.passdt , h.expflag 
                    , s.userid
                from salary s
                    left join hrms h on ( s.userid=h.user_id )
                    left join user u on ( u.user_id=s.userid)
                    left join salary_pay p on ( p.pyear='" . $this->nowy . "' and p.pmon='" . $this->nowm . "' and p.userid=s.userid )
                where
                    s.userid=h.user_id
                    and ( s.usersta='1' or
                            ( year(s.passdt)='".$this->nowy."' and  month(s.passdt)='".$this->nowm."' )
                        )
                order by s.id 
                ";
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    if(array_key_exists($row['usercard'],$infoE)){
                        $infoE[$row['usercard']]['type']='0';
                        $infoE[$row['usercard']]['userlevel']=$row['userlevel'];
                        //$infoE[$row['usercard']]['pid']=$row['pid'];
                        
                        $infoE[$row['usercard']]['passoldam']=$row['passoldam'];
                        $infoE[$row['usercard']]['amount']=$row['amount'];
                        $infoE[$row['usercard']]['usersta']=$row['usersta'];
                        $infoE[$row['usercard']]['userid']=$row['userid'];
                        
                        $infoE[$row['usercard']]['com']=$row['company'];
                        if(!empty($row['salarycom'])){
                            $infoE[$row['usercard']]['com']=$row['salarycom'];
                        }
                        $infoE[$row['usercard']]['sid']=$row['rand_key'];
                    }
                }
                
                if(count($infoE)){
                    try {
                        $this->db->query("START TRANSACTION");

                        foreach($infoE as $key=>$val){
                            if($val['type']=='0'){
                                $passam=$val['am'];
                                $passdt=$val['passdt'];
                                
                                if ($val['userlevel'] == 4) {
                                    
                                    $comtable=$this->get_com_sql($val['com']);
                                    $sql="select
                                            p.id as pid 
                                        from salary s
                                            left join ".$comtable."salary_pay p 
                                                on ( p.pyear='" . $this->nowy . "' and p.pmon='" . $this->nowm . "' and p.userid=s.userid )
                                        where
                                            s.rand_key='".$val['sid']."'";
                                    $rescom=$this->db->get_one($sql);
                                    $val['pid']=$rescom['pid'];
                                    
                                    if($val['usersta']==2){
                                        $passOldAm=$this->salaryClass->decryptDeal($val['passoldam']);
                                    }else{
                                        $passOldAm=$this->salaryClass->decryptDeal($val['amount']);
                                    }
                                    //throw new Exception($passOldAm.'-'.$passam.'-'.$passdt.$val['userid']);
                                    $passNowAm=$this->salaryClass->salaryPass($passOldAm, $passam, $passdt);
                                    
                                    if(date('Y-m',  strtotime($passdt))==date('Y-m')){//当月转正
                                        $baseNowAm = $passNowAm;
                                    }else{
                                        $baseNowAm = 0;
                                    }
                                    
                                    $this->model_salary_update($val['sid'],
                                            array('amount' => $passam, 'passam' => $passam, 'usersta' => '2'
                                                , 'passdt' => $passdt
                                                , 'passuserdt' => 'now()', 'passuser' => $_SESSION['USER_ID']
                                                , 'passnowam'=>$passNowAm
                                                , 'passoldam'=>$passOldAm
                                            )
                                            , array(2, 3, 4,5));
                                    $this->model_pay_update($val['pid'],
                                            array('baseam' => $passam, 'basenowam' => $baseNowAm, 'nowamflag' => '2')
                                            , array(2),$comtable);
                                    $this->model_pay_stat($val['pid'],$comtable);
                                } else {
                                    $sm=true;
                                    $this->model_salary_update($val['sid'], array('passdt' => $passdt, 'passuser' => $_SESSION['USER_ID']), array(0, 1));
                                }
                                
                                $sql="update hrms set join_date='".$passdt."' where user_id='".$val['userid']."' ";
                                $this->db->query_exc($sql);
                            }
                        }

                        $this->db->query("COMMIT");
                        $this->globalUtil->insertOperateLog('转正导入', '转正导入', '转正导入', '成功');
                    } catch (Exception $e) {
                        $this->db->query("ROLLBACK");
                        $responce->error = un_iconv($e->getMessage());
                        $this->globalUtil->insertOperateLog('转正导入', '转正导入' , '转正导入', '失败', $e->getMessage());
                    }
                }
            }elseif($hrflag=='spe'){//扣除补发
                if(!in_array('姓名', $excelFields)||!in_array('员工号', $excelFields)
                ||!in_array('类型', $excelFields)||!in_array('是否计税', $excelFields)||!in_array('金额', $excelFields)||!in_array('备注', $excelFields))
                {
                    throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                }
                if(count($excelArr)&&!empty($excelArr)){
                    $ptyarr=array_flip($this->speType);
                    $ctyarr=array_flip($this->accType);
                    foreach($excelArr['员工号'] as $key=>$val ){
                        $infoE[$val]['name']=$excelArr['姓名'][$key];
                        $infoE[$val]['pty']=$ptyarr[$excelArr['类型'][$key]];
                        $infoE[$val]['aty']=$ctyarr[$excelArr['是否计税'][$key]];
                        $infoE[$val]['amount']=$excelArr['金额'][$key];
                        $infoE[$val]['remark']=$excelArr['备注'][$key];
                        $infoE[$val]['type']=1;
                    }
                }
                
                $sql="select
                    h.usercard , s.username , h.userlevel , s.userid
                from salary s
                    left join hrms h on ( s.userid=h.user_id )
                where
                    s.userid=h.user_id
                order by s.id 
                ";
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    if(array_key_exists($row['usercard'],$infoE)){
                        $infoE[$row['usercard']]['type']=0;
                        $infoE[$row['usercard']]['userid']=$row['userid'];
                        if($infoE[$row['usercard']]['name']!=$row['username']){
                            $infoE[$row['usercard']]['type']=3;//名字不对
                        }
                    }
                }
                if(count($infoE)){
                    try {
                        $this->db->query("START TRANSACTION");

                        foreach($infoE as $key=>$val){
                            if($val['type']=='0'){
                                $spekey=get_rand_key();
                                $sql="insert into salary_spe
                                    ( payyear , paymon , amount
                                        , payuserid , payuserna ,remark
                                        , createdt , creator , spesta ,rand_key , paytype , acctype
                                    )
                                    select
                                        '".$this->nowy."' , '".$this->nowm."' , '".$this->salaryClass->encryptDeal($val['amount'])."'
                                        , user_id , user_name , '".$val['remark']."'
                                        , now() , '".$_SESSION['USER_ID']."' , 1 , '".$spekey."' ,'".$val['pty']."','".$val['aty']."'
                                    from user where user_id='".$val['userid']."' ";
                                $this->db->query_exc($sql);
                                $info=array('flowname'=>$this->flowName['spe']
                                    ,'userid'=>$val['userid']
                                    ,'salarykey'=>$spekey
                                    ,'changeam'=>$this->salaryClass->encryptDeal($val['amount'])
                                    ,'remark'=>$val['remark']
                                    );
                                $sm[$val]=$this->model_flow_new($info);
                            }
                        }

                        $this->db->query("COMMIT");
                        $this->globalUtil->insertOperateLog('补发导入', '补发导入', '补发导入', '成功');
                    } catch (Exception $e) {
                        $this->db->query("ROLLBACK");
                        $responce->error = un_iconv($e->getMessage());
                        $this->globalUtil->insertOperateLog('补发导入', '补发导入' , '补发导入', '失败', $e->getMessage());
                    }
                }
            }
            
        }catch(Exception $e){
            $responce->error = un_iconv($e->getMessage());
        }
        return $responce;
    }
    /**
     *年终奖
     */
    function model_dp_yeb_xls($ckt,$hrflag='fn'){
        set_time_limit(600);
        $type=$_POST['ctr_type'];
        $manflag=$_GET['manflag'];
        
        $sqlCk=$type=='com'?'0':'1';
        $str='';
        $infoE=array();
        $ckarr=array(
            1=>array('min'=>18001,'max'=>19283.33)
            ,2=>array('min'=>54001,'max'=>60187.5)
            ,3=>array('min'=>108001,'max'=>114600)
            ,4=>array('min'=>420001,'max'=>447500)
            ,5=>array('min'=>660001,'max'=>706538.46)
            ,6=>array('min'=>960001,'max'=>1120000)
        );
        $ckdata=array();
        try{
            $excelfilename='attachment/xls_model/yeb/'.$ckt.".xls";
            if(empty ($_FILES["ctr_file"]["tmp_name"])){
                $str='<tr><td colspan="10">请导入年终奖数据信息！</td></tr>';
            }elseif (!move_uploaded_file( $_FILES["ctr_file"]["tmp_name"], $excelfilename) ){
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
                if($manflag=='man'){
                    if(!in_array('姓名', $excelFields)||!in_array('员工号', $excelFields)
                            ||!in_array('年终奖金额', $excelFields)||!in_array('年度', $excelFields))
                    {
                        throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                    }
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['姓名'][$key];
                            $infoE[$val]['am']=$excelArr['年终奖金额'][$key];
                            $infoE[$val]['type']=1;
                            $infoE[$val]['nameoa']=$excelArr['姓名'][$key];
                            $infoE[$val]['syear']=$excelArr['年度'][$key];
                        }
                    }
                }else{
                    if(!in_array('姓名', $excelFields)||!in_array('员工号', $excelFields)
                            ||!in_array('年终奖金额', $excelFields))
                    {
                        throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                    }
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['姓名'][$key];
                            $infoE[$val]['yearam']=$excelArr['年终奖金额'][$key];
                            $infoE[$val]['type']=1;
                            $infoE[$val]['nameoa']=$excelArr['姓名'][$key];
                            $infoE[$val]['syear']=$this->yebyear;
                            foreach($ckarr as $ckkey=>$ckval){
                                if($excelArr['年终奖金额'][$key]>=$ckval['min']&&$excelArr['年终奖金额'][$key]<=$ckval['max']){
                                    $ckdata[$excelArr['姓名'][$key]]['am']=$excelArr['年终奖金额'][$key];
                                    $ckdata[$excelArr['姓名'][$key]]['min']=$ckval['min'];
                                    $ckdata[$excelArr['姓名'][$key]]['max']=$ckval['max'];
                                }
                            }
                        }
                    }
                }
                if($hrflag=='hr'){
                    $sql="select
                            s.oldname as username , s.userid , h.usercard as idcard
                        from
                            salary s
                            left join hrms h on(s.userid=h.user_id)
                        where h.userlevel='4' ";
                }else{
                    $sql="select
                        s.oldname as username , s.userid , h.usercard as idcard
                    from
                        salary s
                        left join hrms h on(s.userid=h.user_id)
                    where 1 ";
                }
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    if(array_key_exists($row['idcard'],$infoE)){
                        $infoE[$row['idcard']]['type']=0;
                        $infoE[$row['idcard']]['nameoa']=$row['username'];
                        if($infoE[$row['idcard']]['name']!=$row['username']){
                            $infoE[$row['idcard']]['type']=3;//名字不对
                        }
                    }
                }
                /*
                $sql="select
                        usercard , syear
                      from salary_yeb  ";
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    if(array_key_exists($row['usercard'],$infoE)&&$row['syear']==$infoE[$row['usercard']]['syear']){
                        $infoE[$row['usercard']]['type']=2;
                    }
                }
                 * 
                 */
                if(count($infoE)){
                    $totalA=array('pro'=>0);
                    $i=1;
                    foreach($infoE as $key=>$val){
                        if(empty($key)){
                            continue;
                        }
                        if($val['type']=='0'){
                            $cl='green';
                            $rk='';
                        }elseif($val['type']=='1'){
                            $cl='#ff9900';
                            $rk='员工号错误或管理层员工或已离职';
                        }elseif($val['type']=='2'){
                            $cl='#ff9900';
                            $rk='该员工年终奖已录入';
                        }elseif($val['type']=='3'){
                            $cl='#ff9900';
                            $rk='员工名字错误，正确：'.$val['nameoa'];
                        }
                        if($manflag=='man'){
                            $str.='<tr style="color:'.$cl.'">
                                <td>'.$i.'</td>
                                <td>'.$key.'</td>
                                <td>'.$val['name'].'</td>
                                <td>'.$val['nameoa'].'</td>
                                <td>'.$val['am'].'</td>
                                <td>'.$val['syear'].'</td>
                                <td>'.$rk.'</td>
                            </tr>';
                        }else{
                            $str.='<tr style="color:'.$cl.'">
                                <td>'.$i.'</td>
                                <td>'.$key.'</td>
                                <td>'.$val['name'].'</td>
                                <td>'.$val['nameoa'].'</td>
                                <td>'.$val['yearam'].'</td>
                                <td>'.$rk.'</td>
                            </tr>';
                        }
                        $i++;
                    }
                }
            }
        }catch(Exception $e){
            $str='<tr><td colspan="10">导入数据错误！'.$e->getMessage().'</td></tr>';
        }
        if(!empty($ckdata)){
            $str.='<tr><td colspan="10">盲区数据</td></tr>';
            $str.='<tr style="">
                                <td>姓名</td>
                                <td>年终奖金额</td>
                                <td>盲区起</td>
                                <td>盲区止</td>
                                <td> </td>
                                <td> </td>
                            </tr>';
            foreach($ckdata as $key=>$val){
                $str.='<tr style="">
                                <td>'.$key.'</td>
                                <td>'.$val['am'].'</td>
                                <td>'.$val['min'].'</td>
                                <td>'.$val['max'].'</td>
                                <td> </td>
                                <td> </td>
                            </tr>';
            }
        }else{
            $str.='<tr><td colspan="10">无盲区数据</td></tr>';
        }
        return $str;
    }
    /**
     * 导入初始化外派
     */
    function model_dp_yeb_xls_in($hrflag='fn'){
        set_time_limit(600);
        $ckt=$_POST['ckt'];
        $manflag=$_GET['manflag'];
        $excelfilename=WEB_TOR.'attachment/xls_model/yeb/'.$ckt.".xls";
        
        try{
            if(!file_exists($excelfilename)){
                throw new Exception('File does not exist');
            }
            include('includes/classes/excel.php');
            $excel = Excel::getInstance();
            $excel->setFile($excelfilename);
            $excel->Open();
            $excel->setSheet();
            $excelFields = $excel->getFields();
            $excelArr=$excel->getAllData();
            $excel->Close();
            if($manflag=='man'){
                if(!in_array('姓名', $excelFields)||!in_array('员工号', $excelFields)
                        ||!in_array('年终奖金额', $excelFields)||!in_array('年度', $excelFields))
                {
                    throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                }
                if(count($excelArr)&&!empty($excelArr)){
                    foreach($excelArr['员工号'] as $key=>$val ){
                        $infoE[$val]['name']=$excelArr['姓名'][$key];
                        $infoE[$val]['am']=$excelArr['年终奖金额'][$key];
                        $infoE[$val]['type']=1;
                        $infoE[$val]['syear']=$excelArr['年度'][$key];
                    }
                }
            }else{
                if(!in_array('姓名', $excelFields)||!in_array('员工号', $excelFields)
                        ||!in_array('年终奖金额', $excelFields))
                {
                    throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                }
                if(count($excelArr)&&!empty($excelArr)){
                    foreach($excelArr['员工号'] as $key=>$val ){
                        $infoE[$val]['name']=$excelArr['姓名'][$key];
                        $infoE[$val]['yearam']=$excelArr['年终奖金额'][$key];
                        $infoE[$val]['type']=1;
                        $infoE[$val]['syear']=$this->yebyear;
                    }
                }
            }
            if($hrflag=='hr'){
                //添加级别控制
                $sql="select
                        s.oldname as username , s.userid , h.usercard as idcard ,  p.totalam as amount  , s.cessebase
                        ,u.dept_id , h.expflag , p.gjjam as gjjam , p.shbam as shbam , u.company , u.salarycom , s.rand_key
                    from
                        salary s
                        left join salary_pay p on (s.userid=p.userid and p.pyear='2013' and p.pmon='1')
                        left join user u on(s.userid=u.user_id)
                        left join hrms h on(s.userid=h.user_id)
                    where h.userlevel='4' ";
            }else{
                $sql="select
                        s.oldname as username , s.userid , h.usercard as idcard ,  p.totalam as amount  , s.cessebase
                        ,u.dept_id , h.expflag , p.gjjam as gjjam , p.shbam as shbam , u.company , u.salarycom , s.rand_key
                    from
                        salary s
                        left join salary_pay p on (s.userid=p.userid and p.pyear='2013' and p.pmon='1')
                        left join user u on(s.userid=u.user_id)
                        left join hrms h on(s.userid=h.user_id)
                    where 1 ";
            } 
            $query=$this->db->query_exc($sql);
            while($row=$this->db->fetch_array($query)){
                if(array_key_exists($row['idcard'],$infoE)&&$infoE[$row['idcard']]['name']==$row['username']){
                    $sam=$this->salaryClass->decryptDeal($row['amount']);
                    $gjjam=$this->salaryClass->decryptDeal($row['gjjam']);
                    $shbam=$this->salaryClass->decryptDeal($row['shbam']);
                    $infoE[$row['idcard']]['type']=0;
                    $infoE[$row['idcard']]['lastmonam']=round($sam-$gjjam-$shbam);
                    $infoE[$row['idcard']]['cpb']=round($row['cessebase']);
                    $infoE[$row['idcard']]['expflag']=$row['expflag'];
                    $infoE[$row['idcard']]['com']=$row['company'];
                    if(!empty($row['salarycom'])){
                        $infoE[$row['idcard']]['com']=$row['salarycom'];
                    }
                    $infoE[$row['idcard']]['sid']=$row['rand_key'];
                }
            }
            $sql="select
                    usercard , syear , id
                  from salary_yeb  where syear='".$this->yebyear."'  ";
            $query=$this->db->query_exc($sql);
            while($row=$this->db->fetch_array($query)){
                if(array_key_exists($row['usercard'],$infoE)&&$row['syear']==$infoE[$row['usercard']]['syear']&&$infoE[$row['usercard']]['type']==0){
                    $infoE[$row['usercard']]['type']=2;
                    $infoE[$row['usercard']]['yebid']=$row['id'];
                }
            }
            
            if(count($infoE)){
                try {
                    $this->db->query("START TRANSACTION");
                    if($manflag=='man'){
                        foreach($infoE as $key=>$val){
                            if($val['type']=='0'&&!empty($val['lastmonam'])){
                                $yearAm=round($val['am'],2);
                                $payCesseAm=$this->salaryClass->cesseDealYeb($yearAm, $val['lastmonam'], $val['cpb']);
                                $payAm=round($yearAm-$payCesseAm,2);
                                $sql="insert into salary_yeb
                                    (usercard 
                                        , yearam , lastmonam , paycesseam
                                        , payam , sta , syear
                                        , inputuser , inputdt  )
                                    values
                                    ( '".$key."'
                                        , '".$this->salaryClass->encryptDeal($yearAm)."'
                                        , '".$this->salaryClass->encryptDeal($val['lastmonam'])."'
                                        , '".$this->salaryClass->encryptDeal($payCesseAm)."'
                                        , '".$this->salaryClass->encryptDeal($payAm)."'
                                        , '0', '".$val['syear']."'
                                        , '".$_SESSION['USER_ID']."' , now() )";
                                $this->db->query_exc($sql);
                            }
                        }
                    }else{
                        foreach($infoE as $key=>$val){
                            $comtable=$this->get_com_sql($val['com']);
                            $sql="select
                                    p.id as pid , p.totalam as amount , p.gjjam , p.shbam
                                from salary s
                                    left join ".$comtable."salary_pay p 
                                        on ( p.pyear='2013' and p.pmon='1' and p.userid=s.userid )
                                where
                                    s.rand_key='".$val['sid']."'";
                            $rescom=$this->db->get_one($sql);
                            $sam=$this->salaryClass->decryptDeal($rescom['amount']);
                            $gjjam=$this->salaryClass->decryptDeal($rescom['gjjam']);
                            $shbam=$this->salaryClass->decryptDeal($rescom['shbam']);
                            $val['lastmonam']=round($sam-$gjjam-$shbam);
                            
                            if($val['type']=='0'){
                                
                                if(false){
                                    $yearAm=round($val['yearam'],2);
                                    $sql="insert into salary_yeb
                                        (usercard 
                                            , yearam
                                            , sta , syear
                                            , inputuser , inputdt  )
                                        values
                                        ( '".$key."'
                                            , '".$this->salaryClass->encryptDeal($yearAm)."'
                                            , '0', '".$val['syear']."'
                                            , '".$_SESSION['USER_ID']."' , now() )";
                                }else{
                                    $yearAm=round($val['yearam'],2);
                                    $payCesseAm=$this->salaryClass->cesseDealYeb($yearAm, $val['lastmonam'], $val['cpb']);
                                    $payAm=round($yearAm-$payCesseAm,2);
                                    $sql="insert into salary_yeb
                                        (usercard 
                                            , yearam , paycesseam
                                            , payam , sta , syear
                                            , inputuser , inputdt , realam , com )
                                        values
                                        ( '".$key."'
                                            , '".$this->salaryClass->encryptDeal($yearAm)."'
                                            , '".$this->salaryClass->encryptDeal($payCesseAm)."'
                                            , '".$this->salaryClass->encryptDeal($payAm)."'
                                            , '0', '".$val['syear']."'
                                            , '".$_SESSION['USER_ID']."' , now()
                                            , '".$this->salaryClass->encryptDeal($val['lastmonam'])."' , '".$val['com']."' )";
                                }
                                $this->db->query_exc($sql);
                            }elseif($val['type']=='2'){
                                $yearAm=round($val['yearam'],2);
                                $payCesseAm=$this->salaryClass->cesseDealYeb($yearAm, $val['lastmonam'], $val['cpb']);
                                $payAm=round($yearAm-$payCesseAm,2);
                                $sql="update salary_yeb set 
                                        yearam='".$this->salaryClass->encryptDeal($yearAm)."'
                                        , paycesseam='".$this->salaryClass->encryptDeal($payCesseAm)."'  
                                        , payam='".$this->salaryClass->encryptDeal($payAm)."'
                                        , sta='0' , inputuser='".$_SESSION['USER_ID']."' , inputdt=now()
                                        , realam='".$this->salaryClass->encryptDeal($val['lastmonam'])."'
                                        where usercard='".$key."' and syear='".$val['syear']."' and id='".$val['yebid']."' ";
                                $this->db->query_exc($sql);
                            }
                        }
                    }
                    $this->db->query("COMMIT");
                    $this->globalUtil->insertOperateLog('年终奖', '年终奖', '年终奖', '成功');
                } catch (Exception $e) {
                    $this->db->query("ROLLBACK");
                    $responce->error = un_iconv($e->getMessage());
                    $this->globalUtil->insertOperateLog('年终奖', '年终奖' , '年终奖', '失败', $e->getMessage());
                }
            }
        }catch(Exception $e){
            $responce->error = un_iconv($e->getMessage());
        }
        return $responce;
    }
    
    
    /**
     * 年终奖
     */
     function model_dp_yeb_list($sqlflag=''){
        //$this->update_salary_yeb();
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $seay = empty($_GET['seay'])?$this->yebyear:$_GET['seay'];
        $seaname = $_REQUEST['seaname'];
        $seadept = $_REQUEST['seadept'];
        $seaexp = $_GET['seaexp'];
        $manflag = $_GET['manflag'];
        $sqlSch = '';
        if(!empty($seay)){
            $sqlSch.=" and y.syear = '".$seay."' ";
        }
        if(!empty($seaname)){
            $sqlSch.=" and u.user_name like '%".$seaname."%' ";
        }
        if(!empty($seadept)){
            $sqlSch.=" and d.dept_name like '%".$seadept."%' ";
        }
        if ($schOper) {
            $sqlSch .= jqgrid_sopt($schField, $schStr, $schOper);
        }
        $start = $limit * $page - $limit;
        if(empty($sqlflag)){
            //$sqlflag=" and y.inputuser='".$_SESSION['USER_ID']."'";
        }
        if(!empty($seaexp)){
            if($seaexp=='com'){
                $sqlSch.=" and h.expflag = '0' ";
            }elseif($seaexp=='exp'){
                $sqlSch.=" and h.expflag = '1' ";
            }
        }
        //$dppow=$this->model_dp_pow();
        //总数 and s.floatam!='0' and s.floatam!='".$this->zero."'
        $sql = "select count(*)
            from salary_yeb y
                left join hrms h on (y.usercard=h.usercard)
                left join user u on (u.user_id=h.user_id)
                left join department d on (u.dept_id=d.dept_id)
                left join salary s on (u.user_id = s.userid)
            where y.id is not null 
                $sqlflag
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select y.rand_key , y.usercard , u.user_name as username , d.dept_name as deptname
                , y.yearaveam , y.mons , y.yearam , y.sta , y.changeremark , y.syear
                , y.paycesseam , y.payam , h.expflag , y.com
            from salary_yeb y
                left join hrms h on (y.usercard=h.usercard)
                left join user u on (u.user_id=h.user_id)
                left join department d on (u.dept_id=d.dept_id)
                left join salary s on (u.user_id = s.userid)
            where y.id is not null 
                $sqlflag
                $sqlSch
            order by y.id , $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        if($manflag=='man'){
            while ($row = $this->db->fetch_array($query)) {
                $responce->rows[$i]['id'] = $row['rand_key'];
                $responce->rows[$i]['cell'] = un_iconv(
                                array("", $row['rand_key']
                                    , $row['usercard']
                                    , $row['username']
                                    , $row['deptname']
                                    , $this->salaryClass->decryptDeal($row['yearam'])
                                    , $row['syear']
                                    , $row['changeremark']
                                )
                );
                $i++;
            }
        }else{
            while ($row = $this->db->fetch_array($query)) {
                $responce->rows[$i]['id'] = $row['rand_key'];
                $responce->rows[$i]['cell'] = un_iconv(
                                array("", $row['rand_key']
                                    , $this->salaryCom[$row['com']]
                                    , $row['usercard']
                                    , $row['username']
                                    , $row['deptname']
                                    , $this->expflag[$row['expflag']]
                                    , $this->salaryClass->finiView($this->salaryClass->decryptDeal($row['yearaveam']))
                                    , $this->salaryClass->finiView($this->salaryClass->decryptDeal($row['mons']))
                                    , $this->salaryClass->finiView($this->salaryClass->decryptDeal($row['yearam']))
                                    , $this->salaryClass->finiView($this->salaryClass->decryptDeal($row['paycesseam']))
                                    , $this->salaryClass->finiView($this->salaryClass->decryptDeal($row['payam']))
                                    , $row['syear']
                                    , $row['changeremark']
                                )
                );
                $i++;
            }
        }
        return $responce;
     }
     /**
      * 年终奖修改
      */
     function model_fn_yeb_edit(){
        $key = $_POST['key'];
        $am  = $_POST['am'];
        $rmk = $_POST['rmk'];
        try{
            $sql="select
                    y.rand_key , s.amount as lastmonam , s.cessebase , y.realam 
                from salary_yeb y
                    left join hrms h on (y.usercard = h.usercard)
                    left join salary s on (h.user_id = s.userid)
                where y.rand_key ='".$key."' ";
            $res=$this->db->get_one($sql);
            if(empty($res)){
                throw new Exception('checked false');
            }
            $realam=$this->salaryClass->decryptDeal($res['realam']);
            $yearAm=round($am,2);
            $payCesseAm=$this->salaryClass->cesseDealYeb($yearAm, $realam, $res['cessebase']);
            $payAm=round($yearAm-$payCesseAm,2);
            $sql=" update salary_yeb
                   set yearam='".$this->salaryClass->encryptDeal($yearAm)."'
                       ,paycesseam='".$this->salaryClass->encryptDeal($payCesseAm)."'
                       ,payam='".$this->salaryClass->encryptDeal($payAm)."'
                       ,changeremark ='".$rmk."'
                   where rand_key = '".$key."' ";
            $this->db->query_exc($sql);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        echo '1';
     }
     function model_fn_yeb_list(){
        //$this->update_salary_yeb();
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $comflag=$_GET['comflag'];
        $seay = empty($_GET['seay'])?$this->yebyear:$_GET['seay'];
        $seaname = $_REQUEST['seaname'];
        $seadept = $_REQUEST['seadept'];
        $manflag=$_GET['manflag'];
        global $func_limit;
        $sqlSch = " and y.com in( '".str_replace(',',"','",$func_limit['子公司'])."' )" ;
        if(!empty($seay)){
            $sqlSch.=" and y.syear = '".$seay."' ";
        }
        if(!empty($seaname)){
            $sqlSch.=" and u.user_name like '%".$seaname."%' ";
        }
        if(!empty($seadept)){
            $sqlSch.=" and d.dept_name like '%".$seadept."%' ";
        }
        if(!empty($comflag)){
            if($comflag=='com'){
                $sqlSch.=" and h.expflag = '0' ";
            }elseif($comflag=='exp'){
                $sqlSch.=" and h.expflag = '1' ";
            }
        }
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        $start = $limit * $page - $limit;
        //$dppow=$this->model_dp_pow();
        //总数 and s.floatam!='0' and s.floatam!='".$this->zero."'
        $sql = "select count(*)
            from salary_yeb y
                left join hrms h on (y.usercard=h.usercard)
                left join user u on (u.user_id=h.user_id)
                left join department d on (u.dept_id=d.dept_id)
                left join salary s on (u.user_id=s.userid)
            where
                s.usersta!=3
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select y.rand_key , y.usercard , u.user_name as username , s.olddept as deptname
                , y.yearaveam , y.mons , y.yearam , y.sta , y.changeremark , y.syear , y.payam , y.paycesseam
                , s.acc , s.accbank , s.email , s.idcard 
            from salary_yeb y
                left join hrms h on (y.usercard=h.usercard)
                left join user u on (u.user_id=h.user_id)
                left join department d on (u.dept_id=d.dept_id)
                left join salary s on (u.user_id=s.userid)
            where
                s.usersta!=3
                $sqlSch
            order by  y.id , $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $responce->rows[$i]['id'] = $row['rand_key'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array("", $row['rand_key']
                                , $row['usercard']
                                , $row['username']
                                , $row['deptname']
                                , $row['syear']
                                , $this->salaryClass->finiView($this->salaryClass->decryptDeal($row['yearam']))
                                , $this->salaryClass->finiView($this->salaryClass->decryptDeal($row['paycesseam']))
                                , $this->salaryClass->finiView($this->salaryClass->decryptDeal($row['payam']))
                                , $row['idcard']
                                , $row['acc']
                                , $row['accbank']
                                , $row['email']
                            )
            );
            $i++;
        }
        return $responce;
     }
     /**
      * 更新
      */
     function model_yeb_update(){
         /*
         if( strtotime(date('Y-m-d')) < strtotime('2012-01-25') ){
            $dataArr=array();
            $sql=" select
                    y.rand_key , y.yearam
                    , y.realam , s.amount , s.gjjam , s.shbam , s.cessebase
                from salary_yeb y
                    left join hrms h on (y.usercard = h.usercard )
                    left join salary s on (h.user_id = s.userid )
                where s.usersta!='3' and h.expflag='0' ";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                $ram=$this->salaryClass->decryptDeal($row['realam']);
                $sam=$this->salaryClass->decryptDeal($row['amount']);
                $gjj=$this->salaryClass->decryptDeal($row['gjjam']);
                $shb=$this->salaryClass->decryptDeal($row['shbam']);
                if(round($ram,2)!=round($sam-$gjj-$shb,2)){
                    $dataArr[$row['rand_key']]['yearam']=$this->salaryClass->decryptDeal($row['yearam']);
                    $dataArr[$row['rand_key']]['amount']=$sam;
                    $dataArr[$row['rand_key']]['gjjam']=$gjj;
                    $dataArr[$row['rand_key']]['shbam']=$shb;
                    $dataArr[$row['rand_key']]['cessebase']=$row['cessebase'];
                }
            }
            if(!empty($dataArr)){
                foreach($dataArr as $key=>$val){
                    $nowam=round($val['amount']-$val['gjjam']-$val['shbam'],2);
                    $paycesseam=$this->salaryClass->cesseDealYeb($val['yearam'] , $nowam , $val['cessebase']);
                    $payam=round($val['yearam']-$paycesseam,2);
                    $sql="update salary_yeb set
                            paycesseam='".$this->salaryClass->encryptDeal($paycesseam)."' 
                            , payam ='".$this->salaryClass->encryptDeal($payam)."'
                            , realam='".$this->salaryClass->encryptDeal($nowam)."'
                          where rand_key='".$key."' ";
                    $this->db->query($sql);
                }
            }
         }
          * 
          */
     }
     /**
      * 导出
      */
     function model_fn_yeb_xls($flag='com'){
         set_time_limit(600);
         $xls=new includes_class_excelout('gb2312', true, 'My Test Sheet');
         $sqlstr="";
         $step=$_GET['step'];
         global $func_limit;
         if($flag=='com'){
             if($step=='1'){
                 $data = array(1=> array ('编号', '年份', '姓名', '部门'
                    ,'年终奖金额'
                    ,'交纳税金','实发金额'
                    ,'帐号','邮箱','身份证号','开户行'));
                $xls->setStyle(array(4,5,6));
             }elseif ($step=="2") {
                $data = array(1=>array('身份证明类别','身份证明号码','纳税人姓名','所得项目'
                    ,'所得项目子目','收入所属期间（起）','收入所属期间（止）','部门','本次申报收入额:金额'
                    ,'按规定扣除项目:社会保险费','按规定扣除项目:住房公积金','法定减除费用额','税款负担方式'));
                $xls->setStyle(array(8,9,10,11,12));
            }elseif ($step=="3") {
                $data = array(1=>array('序号','金额','收款行名称','收款帐号','收款人名称','收款人地址'
                    ,'收款人帐户类型','资金用途','附言','多方协议号'));
                $xls->setStyle(array(1));
            }elseif ($step=="4") {
                $data = array(1=>array('部门','总年终奖合计','缴纳税金','实发金额'));
                $xls->setStyle(array(1,2,3,4,5));
            }
            $sqlstr.=" and h.expflag='0' and y.com in( '".str_replace(',',"','",$func_limit['子公司'])."' )  and u.dept_id in (37)   ";
         }elseif($flag=='exp'){
            $data = array(1=> array ('编号', '年份', '姓名', '部门'
                ,'年终奖税前金额','缴纳税额','年终奖税后金额'
                ,'帐号','邮箱','身份证号','开户行'));
            $xls->setStyle(array(4,5,6));
            $sqlstr.=" and h.expflag='1' ";
         }elseif($flag=='hr'){
             $data = array(1=> array ('编号', '年份','公司','员工类型','员工号', '姓名', '部门'
                ,'年终奖金额','缴纳税额','年终奖税后金额'
                ,'帐号','邮箱','身份证号','开户行'));
            $xls->setStyle(array(6,7,8));
            $sqlstr.=" and h.userlevel='4' ";
         }elseif($flag=='dao'){
             $data = array(1=> array ('编号', '年份','公司','员工类型','员工号', '姓名', '部门'
                ,'年终奖金额','缴纳税额','年终奖税后金额'
                ,'帐号','邮箱','身份证号','开户行'));
            $xls->setStyle(array(6,7,8));
         }elseif($flag=='email'){
             $sqlstr.=" and h.expflag='0' and y.com in( '".str_replace(',',"','",$func_limit['子公司'])."' ) ";
         }else{
             $sqlstr.=" and h.expflag='0' and y.com in( '".str_replace(',',"','",$func_limit['子公司'])."' ) ";
         }
         if($flag!='email'&&$flag!='hr'){
             $sqlstr.="  ";
         }
         $sql="select
                y.syear , s.oldname , d.dept_name as olddept , y.yearam
                , s.amount , y.paycesseam , y.payam
                , s.acc , s.email , s.idcard , s.accbank
                , d.dept_name  , s.cessebase , h.usercard , y.com , h.expflag
             from salary_yeb y
                left join hrms h on (y.usercard = h.usercard)
                left join salary s on (h.user_id = s.userid ) 
                left join user u on (u.user_id=h.user_id)
                left join department d on (u.dept_id=d.dept_id)
             where y.syear='".$this->yebyear."'  
                $sqlstr
             order by y.id
             ";
         $query=$this->db->query($sql);
         $i=0;
         if($flag=='com'){
             if($step=='1'){
                 while($row=$this->db->fetch_array($query)){
                     $i++;
                     $data[]=array($i,
                         $row['syear'], $row['oldname'], $row['olddept']
                         , $this->salaryClass->decryptDeal($row['yearam'])
                         , $this->salaryClass->decryptDeal($row['paycesseam'])
                         , $this->salaryClass->decryptDeal($row['payam'])
                         , $row['acc'], $row['email'], $row['idcard'], $row['accbank']
                         );
                 }
             }elseif($step=="2"){
                while($row=$this->db->fetch_array($query)){
                    $data[]=array('', $row["idcard"],$row["oldname"],'',
                            '年终奖薪金',
                            '',
                            '',
                            $row["olddept"],
                            $this->salaryClass->decryptDeal($row["yearam"]),
                            '',
                            '',
                            $row["cessebase"],
                            '');
                }
            }elseif($step=="3"){
                $x=0;
                while($row=$this->db->fetch_array($query)){
                    $x++;
                    $data[]=array($x,
                    $this->salaryClass->decryptDeal($row["payam"]),
                    $row["accbank"],
                    $row["acc"],
                    $row["oldname"],
                    '',
                    '',
                    '',
                    '',
                    '');
                }
            }elseif($step=="4"){
                $deptArr=array();
                while($row=$this->db->fetch_array($query)){
                    $deptArr[$row['olddept']]['yearam']=isset($deptArr[$row['olddept']]['yearam'])?round($deptArr[$row['olddept']]['yearam']+$this->salaryClass->decryptDeal($row['yearam']),2):$this->salaryClass->decryptDeal($row['yearam']);
                    $deptArr[$row['olddept']]['paycesseam']=isset($deptArr[$row['olddept']]['paycesseam'])?round($deptArr[$row['olddept']]['paycesseam']+$this->salaryClass->decryptDeal($row['paycesseam']),2):$this->salaryClass->decryptDeal($row['paycesseam']);
                    $deptArr[$row['olddept']]['payam']=isset($deptArr[$row['olddept']]['payam'])?round($deptArr[$row['olddept']]['payam']+$this->salaryClass->decryptDeal($row['payam']),2):$this->salaryClass->decryptDeal($row['payam']);
                }
                foreach ($deptArr as $key=>$val) {
                    $data[]=array($key,
                    $val['yearam'],
                    $val['paycesseam'],
                    $val['payam']
                    );
                }
                $deptArr=array();
                $sql="select t.name
                        , y.yearam 
                        , y.paycesseam as pcam
                        , y.payam
                    from
                        salary_yeb y
                        left join hrms h on (y.usercard=h.usercard)
                        left join salary s on (h.user_id = s.userid )
                        left join salary_user_type t on ( find_in_set(s.userid ,t.members)>0 )
                    where t.type='pro'
                        and y.syear='".$this->yebyear."' and s.usersta!=3
                        $sqlStr ";
                $query=$this->db->query($sql);
                while($row=$this->db->fetch_array($query)){
                    $deptArr[$row['name']]['yearam']=isset($deptArr[$row['name']]['yearam'])?round($deptArr[$row['name']]['yearam']+$this->salaryClass->decryptDeal($row['yearam']),2):$this->salaryClass->decryptDeal($row['yearam']);
                    $deptArr[$row['name']]['pcam']=isset($deptArr[$row['name']]['pcam'])?round($deptArr[$row['name']]['pcam']+$this->salaryClass->decryptDeal($row['pcam']),2):$this->salaryClass->decryptDeal($row['pcam']);
                    $deptArr[$row['name']]['payam']=isset($deptArr[$row['name']]['payam'])?round($deptArr[$row['name']]['payam']+$this->salaryClass->decryptDeal($row['payam']),2):$this->salaryClass->decryptDeal($row['payam']);
                }
                if(!empty($deptArr)){
                    $data[] = array();
                    $data[] = array('项目名','总年终奖合计','缴纳税金','实发金额');
                    foreach ($deptArr as $key=>$val) {
                        $data[]=array($key,
                        $val['yearam'],
                        $val['pcam'],
                        $val['payam']
                        );
                    }
                }
            }
             $xls->addArray($data);
             $xls->generateXML('yeb'.'-'.time());
         }elseif($flag=='exp'){
             while($row=$this->db->fetch_array($query)){
                 $i++;
                 $data[]=array($i,
                     $row['syear'], $row['oldname'], $row['olddept']
                     , $this->salaryClass->decryptDeal($row['yearam'])
                     , $this->salaryClass->decryptDeal($row['paycesseam'])
                     , $this->salaryClass->decryptDeal($row['payam'])
                     , $row['acc'], $row['email'], $row['idcard'], $row['accbank']
                     );
             }
             $xls->addArray($data);
             $xls->generateXML('yeb'.'-'.time());
         }elseif($flag=='hr'||$flag=='dao'){
             while($row=$this->db->fetch_array($query)){
                 $i++;
                 $data[]=array($i,$row['syear'],$this->salaryCom[$row['com']],$this->expflag[$row['expflag']],$row['usercard']
                     , $row['oldname'], $row['olddept']
                     , $this->salaryClass->decryptDeal($row['yearam'])
                     , $this->salaryClass->decryptDeal($row['paycesseam'])
                     , $this->salaryClass->decryptDeal($row['payam'])
                     , $row['acc'], $row['email'], $row['idcard'], $row['accbank']
                     );
             }
             $xls->addArray($data);
             $xls->generateXML('yeb'.'-'.time());
         }elseif($flag=='email'){
             while($row=$this->db->fetch_array($query)){
                 $email[]=array(
                     '年度'=>$row['syear']
                     ,'姓名'=>$row['oldname']
                     ,'部门'=>$row['dept_name']
                     ,'年终奖金额'=>$this->salaryClass->decryptDeal($row['yearam'])
                     ,'交纳税金'=>$this->salaryClass->decryptDeal($row['paycesseam'])
                     ,'实发金额'=>$this->salaryClass->decryptDeal($row['payam'])
                     ,'邮箱'=>$row['email']
                 );
             }
             foreach($email as $val){
                $str="您好！您的年终奖已发放，请在三个工作日后查收！<br/>";
                $strTR="";
                foreach ($val as $vkey=>$vval){
                    if($vkey!='邮箱'){
                        if(is_numeric($vval)&&$vval<0){
                            $vval=0;
                        }
                        $strTR.="<tr><td>$vkey</td>";
                        $strTR.="<td>$vval</td></tr>";
                    }
                }
                $str.="<table border='1' cellspacing='1' cellpadding='1'>$strTR</table>";
                $this->model_send_email("年终奖信息",$str,$val["邮箱"],true,true);
            }
            return '1';
         }
     }
     /**
      * 年终奖报表
      */
    function model_dp_yeb_rep(){
        global $func_limit;
        if($func_limit['年终奖报表']=='1'){
            $seay=isset($_GET['seay'])?$_GET['seay']:$this->yebyear;
            $res='';
            $data=array();
            $total=array();
            $sql="select
                    y.yearam , d1.dept_name as deptname , h.userlevel
                from salary_yeb y
                    left join hrms h on (y.usercard = h.usercard)
                    left join user u on (h.user_id = u.user_id)
                    left join salary s on (u.user_id=s.userid)
                    left join department d on (d.dept_id=u.dept_id)
                    left join department d1 on (d1.depart_x= left(d.depart_x,2))
                where y.id is not null 
                    and d.dept_id=u.dept_id 
                    and y.syear='".$seay."' ";
            $query=$this->db->query($sql);
            $i=0;
            while($row=$this->db->fetch_array($query)){
                $tul=$row['userlevel']=='4'?'0':'1';
                if(empty($data[$row['deptname']][$tul])){
                    $data[$row['deptname']][$tul]=$this->salaryClass->decryptDeal($row['yearam']);
                }else{
                    $data[$row['deptname']][$tul]=round($data[$row['deptname']][$tul]+$this->salaryClass->decryptDeal($row['yearam']));
                }
                if(empty($total)){
                    $total[$tul]=$this->salaryClass->decryptDeal($row['yearam']);
                }else{
                    $total[$tul]=round($total[$tul]+$this->salaryClass->decryptDeal($row['yearam']));
                }
            }
            foreach($data as $key=>$val){
                $i++;
                if ($i % 2 == 0) {
                    $res.='<tr style="background: #F3F3F3;">';
                } else {
                    $res.='<tr style="background: #FFFFFF;">';
                }
                $res.='<td algin>'.$key.'</td>';
                $res.='<td>'.num_to_maney_format(array_sum($val)).'</td>';
                $res.='<td>'.num_to_maney_format($val['1']).'</td>';
                $res.='<td>'.num_to_maney_format($val['0']).'</td>';
                $res.='</tr>';
            }
            if ($i % 2 == 0) {
                $res.='<tr style="background: #F3F3F3;">';
            } else {
                $res.='<tr style="background: #FFFFFF;">';
            }
            $res.='<td style="color:red">合计</td>';
            $res.='<td>'.num_to_maney_format(array_sum($total)).'</td>';
            $res.='<td>'.num_to_maney_format($total['1']).'</td>';
            $res.='<td>'.num_to_maney_format($total['0']).'</td>';
            $res.='</tr>';
            return $res;
        }else{
            return '';
        }
    }
    /**
     * 季度奖
     */
    function model_dp_fla_list(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        $start = $limit * $page - $limit;
        $dppow=$this->model_dp_pow();
        //总数 and s.floatam!='0' and s.floatam!='".$this->zero."'
        $sql = "select count(*)
            from salary_flow f
                left join salary s on (f.userid=s.userid)
                left join user u on (s.userid=u.user_id)
            where
                f.flowname='".$this->flowName['fla']."'
                and u.dept_id in ('".  implode("','", $dppow['1'])."')
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select f.rand_key , u.user_name as username , d.dept_name as deptname , f.salarykey , f.changeam , f.remark
                , f.sta  , month(f.createdt) as fmon
            from salary_flow f
                left join salary s on (f.userid=s.userid)
                left join user u on (s.userid=u.user_id)
                left join department d on (d.dept_id=u.dept_id)
            where
                f.flowname='".$this->flowName['fla']."'
                and u.dept_id in ('".  implode("','", $dppow['1'])."')
                $sqlSch
            order by  $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $amount=$this->salaryClass->decryptDeal($row['salarykey']);
            $floatam=$this->salaryClass->decryptDeal($row['changeam']);
            $smon=(ceil($row['fmon']/3)-1);
            if($smon==0){
                $smon=4;
            }
            $pre=$row['remark'];
            $responce->rows[$i]['id'] = $row['rand_key'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array("", $row['rand_key']
                                , $row['username']
                                , $row['deptname']
                                , $amount
                                , $pre
                                , $floatam
                                , $smon
                                , $this->flowSta[$row['sta']]
                            )
            );
            $i++;
        }
        return $responce;
    }
    /**
     * 季度奖
     */
    function model_dp_fla_new_in(){
        $id = $_POST['id'];
        $sub= $_POST['sub'];
        $pes= $_POST['pes'];
        $sm=false;
        try {
            $this->db->query("START TRANSACTION");
            if($sub=='new'){
                if(!$id||!trim($id)){
                    throw new Exception('no user id');
                }
                if(!$_SESSION['USER_ID']){
                    throw new Exception('time out');
                }
                $tmpua=explode(',', trim($id,','));
                $tmpy=$this->nowy;
                $tmpm=(ceil($this->nowm/3)-1)*3;
                if($tmpm==0){
                    $tmpy=$tmpy-1;
                    $tmpm=12;
                }
                $sql="select s.userid , s.amount , p.baseam
                        from
                            salary s
                            left join salary_pay p
                                on (s.userid=p.userid and p.pyear='".$tmpy."' and p.pmon='".$tmpm."' )
                        where
                             s.userid in ('".implode("','", $tmpua)."')";
                $query=$this->db->query($sql);
                $tmpua=array();
                while($row=$this->db->fetch_array($query)){
                    $baseam=$this->salaryClass->decryptDeal($row['baseam']);
                    $amount=$this->salaryClass->decryptDeal($row['amount']);
                    if(empty($baseam)||empty($row['baseam'])){
                        $tmpua[$row['userid']]=$amount;
                    }else{
                        $tmpua[$row['userid']]=$baseam;
                    }
                }
                if(!empty($tmpua)){
                    foreach($tmpua as $key=>$val){
                        if(empty($val)||empty($key)){
                            continue;
                        }
                        $pesam=ceil($val/100*$pes);
                        $info=array('flowname'=>$this->flowName['fla']
                            ,'userid'=>$key
                            ,'salarykey'=>$this->salaryClass->encryptDeal($val)
                            ,'changeam'=>$this->salaryClass->encryptDeal($pesam)
                            ,'remark'=>$pes
                            );
                        $sm[$val]=$this->model_flow_new($info,true,true);
                    }
                }
            }elseif($sub=='edit'){
                $sql="select s.userid , p.id , s.sdymeal , s.sdyother , p.sdyam , p.otheram
                    from salary_sdy s
                        left join salary_pay p on ( s.userid=p.userid and s.pyear=p.pyear and s.pmon=p.pmon )
                    where s.rand_key='".$id."' ";
                $res=$this->db->get_one($sql);
                if(empty($res)||!$res['userid']||!$res['id']){
                    throw new Exception('Data error');
                }
                $oldMeal=$this->salaryClass->decryptDeal($res['sdymeal']);
                $oldOther=$this->salaryClass->decryptDeal($res['sdyother']);
                $paySdy=$this->salaryClass->decryptDeal($res['sdyam']);
                $payOther=$this->salaryClass->decryptDeal($res['otheram']);
                $sql="update salary_sdy
                        set sdymeal='".$this->salaryClass->encryptDeal($meal)."'
                            , sdyother='".$this->salaryClass->encryptDeal($other)."'
                            , createdt=now()
                            , remark ='".$remark."'
                        where rand_key='".$id."' ";
                $this->db->query_exc($sql);
                $this->model_pay_update($res['id'],
                    array('sdyam'=>ceil($paySdy-$oldMeal+$meal),'otheram'=>ceil($payOther-$oldOther+$other),'remark'=>$remark)
                    ,array(2)
                    );
                $this->model_pay_stat($res['id']);
            }elseif($sub=='del'){
                $sql="select s.userid , p.id , s.sdymeal , s.sdyother , p.sdyam , p.otheram
                    from salary_sdy s
                        left join salary_pay p on ( s.userid=p.userid and s.pyear=p.pyear and s.pmon=p.pmon )
                    where s.rand_key='".$id."' ";
                $res=$this->db->get_one($sql);
                if(empty($res)||!$res['userid']||!$res['id']){
                    throw new Exception('Data error');
                }
                $oldMeal=$this->salaryClass->decryptDeal($res['sdymeal']);
                $oldOther=$this->salaryClass->decryptDeal($res['sdyother']);
                $paySdy=$this->salaryClass->decryptDeal($res['sdyam']);
                $payOther=$this->salaryClass->decryptDeal($res['otheram']);

                $sql="delete from  salary_sdy where rand_key='".$id."' ";
                $this->db->query_exc($sql);
                $this->model_pay_update($res['id'],
                    array('sdyam'=>ceil($paySdy-$oldMeal),'otheram'=>ceil($payOther-$oldOther),'remark'=>$remark)
                    ,array(2)
                    );
                $this->model_pay_stat($res['id']);
            }elseif($sub=='xls'){
                $temparr=array();
                $sql="select
                        s.userid , p.id , s.sdymeal , s.sdyother , s.rand_key , p.sdyam , p.otheram
                    from salary_sdy s
                        left join salary_pay p on ( s.userid=p.userid and s.pyear=p.pyear and s.pmon=p.pmon )
                    where
                        s.creator='".$_SESSION['USER_ID']."'
                        and s.pyear='".$this->nowy."' and s.pmon='".$this->nowm."'
                        and s.staflag='1' ";
                $query=$this->db->query($sql);
                while($row=$this->db->fetch_array($query)){
                   $temparr[$row['rand_key']]['userid']=$row['userid'];
                   $temparr[$row['rand_key']]['id']=$row['id'];
                   $temparr[$row['rand_key']]['sdymeal']=$this->salaryClass->decryptDeal($row['sdymeal']);
                   $temparr[$row['rand_key']]['sdyother']=$this->salaryClass->decryptDeal($row['sdyother']);
                   $temparr[$row['rand_key']]['sdyam']=$this->salaryClass->decryptDeal($row['sdyam']);
                   $temparr[$row['rand_key']]['otheram']=$this->salaryClass->decryptDeal($row['otheram']);
                }
                if(!empty($temparr)){
                    foreach($temparr as $key=>$val){
                        if(empty($val)||empty($key)){
                            continue;
                        }
                        $sql="update salary_sdy set staflag='0' where rand_key='".$key."'";
                        $this->db->query_exc($sql);
                        $this->model_pay_update($val['id'],
                            array('sdyam'=>ceil($val['sdymeal']+$val['sdyam']),'otheram'=>ceil($val['sdyother']+$val['otheram']),'remark'=>$remark)
                            ,array(2)
                            );
                        $this->model_pay_stat($val['id']);
                    }
                }
            }
            $this->db->query("COMMIT");
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '补贴', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '补贴', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
     * 项目奖
     */
    function model_dp_pro_in(){
        $id = $_POST['id'];
        $sub= $_POST['sub'];
        $amount=$_POST['amount'];
        $remark=$_POST['remark'];
        $proname=$_POST['proname'];
        $prono=$_POST['prono'];
        $sm=false;
        try {
            $this->db->query("START TRANSACTION");
            if($sub=='new'){
                if(!$id||!trim($id)){
                    throw new Exception('no user id');
                }
                if(!$_SESSION['USER_ID']){
                    throw new Exception('time out');
                }
                $tmpua=explode(',', $id);
                $sql="select userlevel , user_id from hrms where user_id in ('".implode("','", $tmpua)."')";
                $query=$this->db->query($sql);
                $tmpua=array();
                while($row=$this->db->fetch_array($query)){
                    $tmpua[$row['user_id']]=$row['userlevel'];
                }
                if(count($tmpua)){
                    foreach($tmpua as $key=>$val){
                        if(!$val||$val==''){
                            continue;
                        }
                        $info=array('flowname'=>$this->flowName['pro']
                            ,'userid'=>$key
                            ,'salarykey'=>''
                            ,'changeam'=>$this->salaryClass->encryptDeal($amount)
                            ,'remark'=>$remark
                            ,'proname'=>$proname
                            ,'prono'=>$prono
                            );
                        $sm[$key]=$this->model_flow_new($info,true,false);
                    }
                }
            }
            $this->db->query("COMMIT");
            if(is_array($sm)){
                if(count($sm)){
                    foreach($sm as $val){
                        $body='您好！<br>
                            工资审批-项目奖，需要您的审批。<br>
                            谢谢！';
                        $this->model_send_email('审批--项目奖', $body, $val, false);
                    }
                }
            }elseif($sm){
                $body='您好！<br>
                    工资审批-项目奖，需要您的审批。<br>
                    谢谢！';
                $this->model_send_email('审批--项目奖', $body, $sm, false);
            }
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '项目奖', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '项目奖', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
     *导入通用
     * @param type $type 类型 
     * @param type $ckt 时间
     */
    function model_dao_xls($type,$ckt,$act='xls'){
        set_time_limit(600); 
        $sqlCk=$type=='com'?'0':'1';
        $str='';
        $infoE=array();
        $typediv=array(
            'hr_div'=>'temp/'
            ,'hr_ext'=>'temp/'
        ); 
        $ck=false;//是否执行
        try{
            $excelfilename='attachment/xls_model/'.$typediv[$type].$ckt.".xls";
            if($act=='xls'){
                if(empty ($_FILES["ctr_file"]["tmp_name"])){
                    $str='<tr><td colspan="30">请导入数据表！</td></tr>';
                }elseif (!move_uploaded_file( $_FILES["ctr_file"]["tmp_name"], $excelfilename) ){
                    $str='<tr><td colspan="30">上传失败！</td></tr>';
                }else{
                    $ck=true;
                    $excelfilename=WEB_TOR.$excelfilename;
                }
            }elseif($act=='in'){
                $excelfilename=WEB_TOR.$excelfilename;
                if(!file_exists($excelfilename)){
                    throw new Exception('File does not exist');
                }else{
                    $ck=true;
                }
            }
            if($ck){
                //读取excel信息
                include('includes/classes/excel.php');
                $excel = Excel::getInstance();
                $excel->setFile($excelfilename);
                $excel->Open();
                $excel->setSheet();
                $excelFields = $excel->getFields();
                $excelArr=$excel->getAllData();
                $excel->Close();
                if($type=='hr_div'){
                    $ckdt=true;
                    $tempt='';
                    //员工号	发薪日期	 姓名	部门	基本工资	项目奖金	节假日补贴	福利补贴	其他扣除	其他补发	事假	病假	备注
                    if(!in_array('员工号', $excelFields)||!in_array('姓名', $excelFields)
                            ||!in_array('发薪日期', $excelFields))
                    {
                        throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                    }
                    //获取数据
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['员工号'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['姓名'][$key];
                            $infoE[$val]['dept']=$excelArr['部门'][$key];
                            $infoE[$val]['pym']=$excelArr['发薪日期'][$key].'-01';
                            $infoE[$val]['bam']=$excelArr['基本工资'][$key];
                            $infoE[$val]['proam']=$excelArr['项目奖金'][$key];
                            $infoE[$val]['sdyam']=$excelArr['节假日补贴'][$key];
                            $infoE[$val]['otheram']=$excelArr['福利补贴'][$key];
                            $infoE[$val]['spedelam']=$excelArr['其他扣除'][$key];
                            $infoE[$val]['sperewam']=$excelArr['其他补发'][$key];
                            $infoE[$val]['pdt']=$excelArr['事假'][$key];
                            $infoE[$val]['sdt']=$excelArr['病假'][$key];
                            $infoE[$val]['remark']=$excelArr['备注'][$key];
                            $infoE[$val]['type']=1;//默认不生效
                            
                            if($tempt!=$infoE[$val]['pym']&&!empty($tempt)){
                               $ckdt=false;
                            }
                            $tempt = $infoE[$val]['pym'];
                        }
                    }
                    $tempt=  strtotime($tempt);//转时间戳
                    //验证数据
                    $sql="select
                            s.username , s.userid , h.usercard as idcard , s.rand_key
                        from
                            salary s 
                            left join hrms h on(s.userid=h.user_id)
                        where 1 and ( s.deptid in ('". implode("','", $this->divDept) ."') )"; 
                    $query=$this->db->query_exc($sql);
                    $upi=0;//可更新数据
                    while($row=$this->db->fetch_array($query)){ 
                        if(array_key_exists($row['idcard'],$infoE)){
                            $upi++;
                            $infoE[$row['idcard']]['type']=0;
                            $infoE[$row['idcard']]['userid']=$row['userid'];
                            $infoE[$row['idcard']]['sid']=$row['rand_key'];
                            if($infoE[$row['idcard']]['name']!=$row['username']){
                                $infoE[$row['idcard']]['daork']='；工资姓名：'.$row['username'];
                            }
                        }
                        
                    }
                    //输出数据
                    if($act=='xls'){
                        $str.='<tr align="left">
                            <td colspan="30">数据提醒：工资系统姓名和导入姓名不匹配；只要员工号正确，姓名不影响数据的导入。
                            '.($ckdt===false?'<br><font style="color:red;">日期不一致，请检查!</font>':'')
                             .'<br><font style="color:green;">可更新数据：'.$upi.' ；</font><font style="color:red;">不可更新数据：'.(count($infoE)-$upi).'</font>'.'
                            </td>
                        </tr>';
                        if(count($infoE)){
                            $totalA=array('pro'=>0);
                            foreach($infoE as $key=>$val){
                                if($val['type']=='0'){
                                    $cl='green';
                                }elseif($val['type']=='1'){
                                    $cl='red';
                                }
                                $totalA['pro']=$totalA['pro']+$val['pro'];
                                $tempt=  strtotime($val['pym']);
                                $str.='<tr style="color:'.$cl.'">
                                        <td>'.$key.'</td>
                                        <td>'.(date('Y-m', $tempt )).'</td>
                                        <td>'.$val['name'].'</td>
                                        <td>'.$val['dept'].'</td>
                                        <td>'.$val['bam'].'</td>
                                        <td>'.$val['proam'].'</td>
                                        <td>'.$val['sdyam'].'</td>
                                        <td>'.$val['otheram'].'</td>
                                        <td>'.$val['spedelam'].'</td>
                                        <td>'.$val['sperewam'].'</td>
                                        <td>'.$val['pdt'].'</td>
                                        <td>'.$val['sdt'].'</td>
                                        <td>'.$val['remark'].'</td>
                                        <td>'.($val['type']=='1'?'员工号不正确或员工不属于专区人员':'生效'.$val['daork']).'</td>
                                    </tr>';
                            }
                        }
                    }elseif($act=='in'){
                        if(count($infoE)){
                             foreach($infoE as $key=>$val){
                                 if($val['type']=='0'){
                                     $tempt=  strtotime($val['pym']);
                                     //目前只参与补贴调整
                                     $oldarr=array(
                                         'sdyam'=>round($val['sdyam'],2)
                                         ,'otheram'=>round($val['otheram'],2)
                                         ,'remark'=>''
                                     );
                                     //获取生效的补贴
                                     $sql="
                                         select
                                             s.sdymeal , s.sdyother , s.remark 
                                        from salary_sdy s
                                            left join salary_flow f on (f.salarykey=s.rand_key )
                                        where 
                                            s.pyear='".(date('Y',$tempt))."' and s.pmon='".(date('n',$tempt))."'
                                            and s.userid='".$val['userid']."' 
                                            and f.sta='2' ";
                                    $query=$this->db->query_exc($sql);
                                    while($row=$this->db->fetch_array($query)){ 
                                        $oldarr['sdyam']=round($oldarr['sdyam']+$this->salaryClass->decryptDeal($row['sdymeal']),2);
                                        $oldarr['otheram']=round($oldarr['otheram']+$this->salaryClass->decryptDeal($row['sdyother']),2);
                                        $val['remark'].=$row['remark'];
                                    }
                                    $pid=$this->model_get_payid($val['sid'], date('Y',$tempt), date('n',$tempt));
                                    //更新数据
                                    $this->model_salary_update($val['sid'],
                                            array('amount' => $val['bam']
                                            )
                                            , array());
                                    $this->model_pay_update($pid['id'],
                                            array(
                                                'PerHolsDays' => $val['pdt']
                                                ,'SickHolsDays' => $val['sdt']
                                                ,'remark' => $val['remark']
                                                ,'baseam' => $val['bam']
                                                ,'ProAm' => $val['proam']
                                                ,'SdyAm' => $oldarr['sdyam']
                                                ,'OtherAm' => $oldarr['otheram']
                                                ,'SpeRewAm' => $val['sperewam']
                                                ,'SpeDelAm' => $val['spedelam']
                                                )
                                            , array(0,1,2),'',false);
                                    $this->model_pay_stat($pid['id']); 
                                 }//type=0
                             }//foreach
                        }//infoE is not null 
                    }//$act is in 
                }elseif($type=='hr_ext'){//额外导入
                    $ckdt=true;
                    $tempt='';
                    //员工号	发薪日期	 姓名	部门	基本工资	项目奖金	节假日补贴	福利补贴	其他扣除	其他补发	事假	病假	备注
                    if(!in_array('姓名', $excelFields))
                    {
                        throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                    }
                    //获取数据
                    if(count($excelArr)&&!empty($excelArr)){
                        foreach($excelArr['姓名'] as $key=>$val ){
                            $infoE[$val]['name']=$excelArr['姓名'][$key];
                            $infoE[$val]['jfcom']=$excelArr['代购方'][$key];
                            $infoE[$val]['dept']=$excelArr['部门'][$key];
                            $infoE[$val]['usercom']=$excelArr['付费方'][$key];
                            $infoE[$val]['baseam']=$excelArr['基本工资'][$key];
                            $infoE[$val]['gjjam']=$excelArr['公积金'][$key];
                            $infoE[$val]['shbam']=$excelArr['社保费'][$key];
                            $infoE[$val]['cogjjam']=$excelArr['企业公积金'][$key];
                            $infoE[$val]['coshbam']=$excelArr['企业社保'][$key];
                            $infoE[$val]['PrepareAm']=$excelArr['调配费'][$key];
                            $infoE[$val]['HandicapAm']=$excelArr['残障费'][$key];
                            $infoE[$val]['ManageAm']=$excelArr['管理费'][$key];
                            $infoE[$val]['acc']=$excelArr['账号'][$key];
                            $infoE[$val]['accbank']=$excelArr['开户行'][$key];
                            $infoE[$val]['idcard']=$excelArr['身份证'][$key];
                            $infoE[$val]['remark']=$excelArr['备注'][$key];
                            $infoE[$val]['type']=1;//默认不生效
                        }
                    }
                    //验证数据
                    //输出数据
                    if($act=='xls'){
                        $str.='<tr align="left">
                            <td colspan="30">数据提醒：工资系统姓名和导入姓名不匹配；只要员工号正确，姓名不影响数据的导入。
                            '.($ckdt===false?'<br><font style="color:red;">日期不一致，请检查!</font>':'')
                             .'<br><font style="color:green;">可更新数据：'.$upi.' ；</font><font style="color:red;">不可更新数据：'.(count($infoE)-$upi).'</font>'.'
                            </td>
                        </tr>';
                        if(count($infoE)){
                            $totalA=array('pro'=>0);
                            foreach($infoE as $key=>$val){
                                if($val['type']=='0'){
                                    $cl='green';
                                }elseif($val['type']=='1'){
                                    $cl='red';
                                }
                                $totalA['pro']=$totalA['pro']+$val['pro'];
                                $tempt=  strtotime($val['pym']);
                                $str.='<tr style="color:'.$cl.'">
                                        <td>'.$key.'</td>
                                        <td>'.(date('Y-m', $tempt )).'</td>
                                        <td>'.$val['name'].'</td>
                                        <td>'.$val['dept'].'</td>
                                        <td>'.$val['bam'].'</td>
                                        <td>'.$val['proam'].'</td>
                                        <td>'.$val['sdyam'].'</td>
                                        <td>'.$val['otheram'].'</td>
                                        <td>'.$val['spedelam'].'</td>
                                        <td>'.$val['sperewam'].'</td>
                                        <td>'.$val['pdt'].'</td>
                                        <td>'.$val['sdt'].'</td>
                                        <td>'.$val['remark'].'</td>
                                        <td>'.($val['type']=='1'?'员工号不正确或员工不属于专区人员':'生效'.$val['daork']).'</td>
                                    </tr>';
                            }
                        }
                    }elseif($act=='in'){
                        if(count($infoE)){
                             foreach($infoE as $key=>$val){
                                 if($val['type']=='0'){
                                     $tempt=  strtotime($val['pym']);
                                     //目前只参与补贴调整
                                     $oldarr=array(
                                         'sdyam'=>round($val['sdyam'],2)
                                         ,'otheram'=>round($val['otheram'],2)
                                         ,'remark'=>''
                                     );
                                     //获取生效的补贴
                                     $sql="
                                         select
                                             s.sdymeal , s.sdyother , s.remark 
                                        from salary_sdy s
                                            left join salary_flow f on (f.salarykey=s.rand_key )
                                        where 
                                            s.pyear='".(date('Y',$tempt))."' and s.pmon='".(date('n',$tempt))."'
                                            and s.userid='".$val['userid']."' 
                                            and f.sta='2' ";
                                    $query=$this->db->query_exc($sql);
                                    while($row=$this->db->fetch_array($query)){ 
                                        $oldarr['sdyam']=round($oldarr['sdyam']+$this->salaryClass->decryptDeal($row['sdymeal']),2);
                                        $oldarr['otheram']=round($oldarr['otheram']+$this->salaryClass->decryptDeal($row['sdyother']),2);
                                        $val['remark'].=$row['remark'];
                                    }
                                    $pid=$this->model_get_payid($val['sid'], date('Y',$tempt), date('n',$tempt));
                                    //更新数据
                                    $this->model_salary_update($val['sid'],
                                            array('amount' => $val['bam']
                                            )
                                            , array());
                                    $this->model_pay_update($pid['id'],
                                            array(
                                                'PerHolsDays' => $val['pdt']
                                                ,'SickHolsDays' => $val['sdt']
                                                ,'remark' => $val['remark']
                                                ,'baseam' => $val['bam']
                                                ,'ProAm' => $val['proam']
                                                ,'SdyAm' => $oldarr['sdyam']
                                                ,'OtherAm' => $oldarr['otheram']
                                                ,'SpeRewAm' => $val['sperewam']
                                                ,'SpeDelAm' => $val['spedelam']
                                                )
                                            , array(0,1,2),'',false);
                                    $this->model_pay_stat($pid['id']); 
                                 }//type=0
                             }//foreach
                        }//infoE is not null 
                    }//$act is in 
                }
            }
        }catch(Exception $e){
            if($act=='xls'){
                $str='<tr><td colspan="30">导入数据错误！'.$e->getMessage().'</td></tr>';
            }else{
                $str['error']='数据查询失败！';
            }
        }
        return $str;
    }
    
    /**
     *
     */
    function model_dp_pro_xls($ckt){
        set_time_limit(600);
        $type=$_POST['ctr_type'];
        $sqlCk=$type=='com'?'0':'1';
        $str='';
        $infoE=array();
        try{
            $sql="delete from salary_temp where code = 'amount' and creator='".$_SESSION['USER_ID']."' ";
            $this->db->query_exc($sql);
            $excelfilename='attachment/xls_model/pro/'.$ckt.".xls";
            if(empty ($_FILES["ctr_file"]["tmp_name"])){
                $str='<tr><td colspan="10">请导入项目奖金数据表！</td></tr>';
            }elseif (!move_uploaded_file( $_FILES["ctr_file"]["tmp_name"], $excelfilename) ){
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
                if(!in_array('员工', $excelFields)||!in_array('员工号', $excelFields)
                        ||!in_array('项目奖金', $excelFields)||!in_array('备注', $excelFields)
                        ||!in_array('项目编号', $excelFields)||!in_array('项目名称', $excelFields))
                {
                    throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                }
                if(count($excelArr)&&!empty($excelArr)){
                    foreach($excelArr['员工号'] as $key=>$val ){
                        $infoE[$val]['name']=$excelArr['员工'][$key];
                        $infoE[$val]['pro']=$excelArr['项目奖金'][$key];
                        $infoE[$val]['prono']=$excelArr['项目编号'][$key];
                        $infoE[$val]['proname']=$excelArr['项目名称'][$key];
                        $infoE[$val]['remark']=$excelArr['备注'][$key];
                    }
                }
                $sql="select
                        s.username , s.userid , h.usercard as idcard
                    from
                        salary s
                        left join hrms h on(s.userid=h.user_id)
                    where 1 ";
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    $infoA[]=$row['idcard'];
                    if(array_key_exists($row['idcard'],$infoE)){
                        $infoE[$row['idcard']]['type']=0;
                    }
                }
                if(count($infoE)){
                    foreach($infoE as $key=>$val){
                        if(!in_array($key,$infoA)){
                            $infoE[$row['idcard']]['type']=1;
                        }
                    }
                }
                if(count($infoE)){
                    $totalA=array('pro'=>0);
                    foreach($infoE as $key=>$val){
                        if($val['type']=='0'){
                            $cl='green';
                        }elseif($val['type']=='1'){
                            $cl='red';
                        }
                        $totalA['pro']=$totalA['pro']+$val['pro'];
                        $str.='<tr style="color:'.$cl.'">
                                <td>'.$key.'</td>
                                <td>'.$val['name'].'</td>
                                <td>'.$val['pro'].'</td>
                                <td>'.$val['prono'].'</td>
                                <td>'.$val['proname'].'</td>
                                <td>'.$val['remark'].'</td>
                            </tr>';
                    }
                }
                $str.='<tr style="color:red">
                    <td></td>
                    <td>合计：</td>
                    <td>'.$totalA['pro'].'</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>';
            }
        }catch(Exception $e){
            $str='<tr><td colspan="10">导入数据错误！'.$e->getMessage().'</td></tr>';
        }
        return $str;
    }
    /**
     * 导入初始化外派
     */
    function model_dp_pro_xls_in(){
        set_time_limit(600);
        $ckt=$_POST['ckt'];
        $excelfilename=WEB_TOR.'attachment/xls_model/pro/'.$ckt.".xls";
        try{
            if(!file_exists($excelfilename)){
                throw new Exception('File does not exist');
            }
            include('includes/classes/excel.php');
            $excel = Excel::getInstance();
            $excel->setFile($excelfilename);
            $excel->Open();
            $excel->setSheet();
            $excelFields = $excel->getFields();
            $excelArr=$excel->getAllData();
            $excel->Close();
            if(!in_array('员工', $excelFields)||!in_array('员工号', $excelFields)
                        ||!in_array('项目奖金', $excelFields)||!in_array('备注', $excelFields)
                        ||!in_array('项目编号', $excelFields)||!in_array('项目名称', $excelFields))
            {
                throw new Exception('Update failed');
            }
            if(count($excelArr)&&!empty($excelArr)){
                foreach($excelArr['员工号'] as $key=>$val ){
                    $infoE[$val]['name']=$excelArr['员工'][$key];
                    $infoE[$val]['pro']=$excelArr['项目奖金'][$key];
                    $infoE[$val]['prono']=$excelArr['项目编号'][$key];
                    $infoE[$val]['proname']=$excelArr['项目名称'][$key];
                    $infoE[$val]['remark']=$excelArr['备注'][$key];
                }
            }
            if(count($infoE)){
                foreach($infoE as $key=>$val){
                     $sql="select s.rand_key , p.id as pid , s.userid  from salary s
                        left join salary_pay p on (s.userid=p.userid and p.pyear='".$this->nowy."' and pmon='".$this->nowm."')
                        left join hrms h on (s.userid=h.user_id)
                        where h.usercard='".$key."'   ";
                    $res=$this->db->get_one($sql);
                    if(!empty($res)){
                        $info=array('flowname'=>$this->flowName['pro']
                            ,'userid'=>$res['userid']
                            ,'salarykey'=>''
                            ,'changeam'=>$this->salaryClass->encryptDeal($val['pro'])
                            ,'remark'=>$val['remark']
                            ,'prono'=>$val['prono']
                            ,'proname'=>$val['proname']
                            );
                        $sm[$key]=$this->model_flow_new($info,true,false);
                    }
                }
            }
            if(is_array($sm)){
                if(count($sm)){
                    foreach($sm as $val){
                        $body='您好！<br>
                            工资审批-项目奖，需要您的审批。<br>
                            谢谢！';
                        $this->model_send_email('审批--项目奖', $body, $val, false);
                    }
                }
            }
        }catch(Exception $e){
            $responce->error = un_iconv($e->getMessage());
        }
        return $responce;
    }
    /**
     * 额外信息
     */
    function model_hr_user_ext($outtype='list'){ 
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $seapy = $_GET['seapy']?$_GET['seapy']:$this->nowy;
        $seapm = $_GET['seapm']?$_GET['seapm']:$this->nowm;
        $seadept = $_REQUEST['seadept'];
        $seaname = $_REQUEST['seaname'];
        $seausersta = $_REQUEST['seausersta'];
        
        $sqlSch = '';
        $fnArr=array();
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        if($seapy&&$seapy!='-'){
            $paysql.=" and p.pyear='".$seapy."' ";
        }
        if($seapm&&$seapm!='-'){
            $paysql.=" and p.pmon='".$seapm."' ";
        }
        if($seadept){
            $sqlSch.=" and p.salarydept like '%".$seadept."%' ";
        }
        if($seaname){
            $sqlSch.=" and ( s.username like '%".$seaname."%' ) ";
        }
        if($seausersta=='生效'){
            $sqlSch.=" and p.id is not null ";
        }elseif($seausersta=='关闭'){
            $sqlSch.=" and p.id is null ";
        }
        //判断当月，开放修改 暂时开通
        $outck=0;
        if(true){
            $outck=1;
        }
        //分页
        $start = $limit * $page - $limit;
        //统一主数据读取源
        $mailSql="select
                    s.rand_key as sid , s.oldname as username , p.salarydept as deptname , p.deptid
                    ,  p.gjjam , p.shbam
                    , s.acc , s.accbank , s.idcard , p.remark , s.email , s.usersta , p.baseam , p.floatam , p.proam , p.otheram
                    , p.bonusam , p.othdelam , p.perholsdays , p.sickholsdays , p.paycesse , p.paytotal , p.sperewam ,p.spedelam
                    , p.pyear , p.pmon
                    , p.sdyam , p.cessebase , p.id as pid , p.expflag
                    , p.cogjjam , p.coshbam , p.prepaream , p.handicapam , p.manageam
                    , p.jfcom as company , p.totalam , p.accrewam , p.accdelam , p.holsdelam
                    , p.comflag , p.usercom , p.accrewam
                from  salary s 
                    left join ( (select * from salary_pay where comflag=0 )
                    union (select * from `shiyuanoa`.salary_pay where comflag=0 )
                    union (select * from `beiruanoa`.salary_pay where comflag=0 )
                    ) p on ( s.userid=p.userid $paysql )
                where s.comflag=0  $sqlSch ";
        if($outtype=='list'){
            $sql = "select count(*)
                from  salary s 
                    left join ( (select * from salary_pay where comflag=0)
                    union (select * from `shiyuanoa`.salary_pay where comflag=0)
                    union (select * from `beiruanoa`.salary_pay where comflag=0)
                    ) p on ( s.userid=p.userid $paysql )
                where s.comflag=0 $sqlSch ";
            $rs = $this->db->get_one($sql);

            $count = $rs['count(*)'];
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages)
                $page = $total_pages;
            //分页
            $sql = $mailSql."
                order by p.id desc ,  $sidx $sord
                limit $start , $limit ";
            $query = $this->db->query($sql);
            $i = 0;
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;
            while ($row = $this->db->fetch_array($query)) {
                
                $totalarr['gjj']+=$this->salaryClass->decryptDeal($row['gjjam']);
                $totalarr['shb']+=$this->salaryClass->decryptDeal($row['shbam']);
                $tmp=array($row['sid'],'-',$row['username']
                                , (empty($row['pid'])?'关闭':'生效')
                                , $this->salaryCom[$row['company']]
                                , $row['deptname'], $row['usercom'],$row['pyear'].'-'.$row['pmon']
                                , $this->salaryClass->decryptDeal($row['baseam'])
                                , $this->salaryClass->decryptDeal($row['gjjam'])
                                , $this->salaryClass->decryptDeal($row['shbam'])
                                , $this->salaryClass->decryptDeal($row['cogjjam']), $this->salaryClass->decryptDeal($row['coshbam'])
                                , $this->salaryClass->decryptDeal($row['prepaream']), $this->salaryClass->decryptDeal($row['handicapam'])
                                , $this->salaryClass->decryptDeal($row['manageam'])
                                , $this->salaryClass->decryptDeal($row['sperewam']), $this->salaryClass->decryptDeal($row['spedelam'])
                                , $this->salaryClass->decryptDeal($row['accrewam'])
                                , $this->salaryClass->decryptDeal($row['paycesse'])
                                , $this->salaryClass->decryptDeal($row['paytotal'])
                                , $row['acc'], $row['accbank'], $row['idcard'], $row['remark']
                                , $row['pid'],$outck,$row['company'],$row['deptid'],$seapy,$seapm
                                
                        );
                $responce->rows[$i]['id'] = $row['userid'];
                $responce->rows[$i]['cell'] = un_iconv($tmp);
                $i++;
            }
            if($fnsta==false&&$dpsta==false){
                $responce->userdata['amount'] = 'total:';
                $responce->userdata['gjjam'] = $this->salaryClass->cfv($totalarr['gjj']);
                $responce->userdata['shbam'] = $this->salaryClass->cfv($totalarr['shb']);
            }
            $this->globalUtil->insertOperateLog('工资人事管理', 'salary', '显示额外工资信息', '成功', $sql);
            return $responce;
        }elseif($outtype=='xls'){
            $res=array();
            $sql = $mailSql;
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $total=$this->salaryClass->decryptDeal($row['totalam']);
                $shb=$this->salaryClass->decryptDeal($row['shbam']);
                $gjj=$this->salaryClass->decryptDeal($row['gjjam']);
                $coshb=$this->salaryClass->decryptDeal($row['coshbam']);
                $cogjj=$this->salaryClass->decryptDeal($row['cogjjam']);
                $pre=$this->salaryClass->decryptDeal($row['prepaream']);
                $han=$this->salaryClass->decryptDeal($row['handicapam']);
                $man=$this->salaryClass->decryptDeal($row['manageam']);
                $res[]=un_iconv(array(
                    $row['pyear'],$row['pmon']
                    ,$row['username']
                    , $this->salaryCom[$row['company']]
                    , $row['deptname'], $row['usercom']
                    , $this->salaryClass->decryptDeal($row['baseam'])
                    , $this->salaryClass->decryptDeal($row['gjjam'])
                    , $this->salaryClass->decryptDeal($row['shbam'])
                    , $this->salaryClass->decryptDeal($row['cogjjam']), $this->salaryClass->decryptDeal($row['coshbam'])
                    , $this->salaryClass->decryptDeal($row['prepaream']), $this->salaryClass->decryptDeal($row['handicapam'])
                    , $this->salaryClass->decryptDeal($row['manageam'])
                    , $this->salaryClass->decryptDeal($row['sperewam']), $this->salaryClass->decryptDeal($row['spedelam'])
                    , $this->salaryClass->decryptDeal($row['accrewam'])
                    , $this->salaryClass->decryptDeal($row['paycesse'])
                    , $this->salaryClass->decryptDeal($row['paytotal'])
                    , $row['acc'], $row['accbank'], $row['idcard'], $row['remark']
                ));
            }
               
        }
        return $res;
    }
    /**
     * 额外操作
     */
    function model_hr_user_ext_in(){
        try {
            $this->db->query("START TRANSACTION");
            
            $skey=$_POST['id'];
            $pid=$_POST['pid'];
            $subtype=$_POST['subtype'];
            $subpy=$_POST['subpy'];
            $subpm=$_POST['subpm'];
            
            $amount=round($_POST['amount'],2);
            $gjjam=round($_POST['gjjam'],2);
            $shbam=round($_POST['shbam'],2);
            $cogjjam=round($_POST['cogjjam'],2);
            $coshbam=round($_POST['coshbam'],2);
            $prepaream=round($_POST['prepaream'],2);
            $handicapam=round($_POST['handicapam'],2);
            $manageam=round($_POST['manageam'],2);
            $sperewam=round($_POST['sperewam'],2);
            $spedelam=round($_POST['spedelam'],2);
            $accrewam=round($_POST['accrewam'],2);
            
            $usercom=$_POST['usercom'];
            $ffcom=$_POST['ffcom'];
            $userdept=$_POST['userdept'];
            $username=$_POST['username'];
            $usersta=$_POST['usersta'];
            $acc=$_POST['acc'];
            $accbank=$_POST['accbank'];
            $idcard=$_POST['idcard'];
            $remark=$_POST['remark'];
            //获取部门名称
            $sql = "select d.dept_name
                from department d
                where d.dept_id='$userdept' ";
            $resdept = $this->db->get_one($sql);
            $userdeptname=$resdept['dept_name'];
            
            $update=false;
            $userid='';
            if($subtype=='edit'){
                $sql = "select
                            s.userid , s.usersta , s.jfcom as usercom
                        from salary s
                        where
                            s.rand_key='$skey' ";
                $res = $this->db->get_one($sql);
                if(empty($res['userid'])){
                    throw new Exception('No data query');
                }
                $userid=$res['userid'];
                if($usersta=='生效'){
                    if($pid){//修改
                        if($res['usercom']!=$usercom){//公司
                             $sql="insert into 
                                    ".$this->get_com_sql($usercom)."salary_pay 
                                    ( `UserId`,  `DeptId`,  `SalaryDept`,  `Area`,  `BaseAm`,  `BaseNowAm`,  `FloatAm`,  `ProAm`,
                                    `SdyAm`,  `OtherAm`,  `BonusAm`,  `HolsDelAm`,  `PerHolsDays`,  `SickHolsDays`,
                                    `OthDelAm`,  `TotalAm`,  `ShbAm`,  `GjjAm`,  `SpeRewAm`,  `SpeDelAm`,  `CesseAm`,
                                    `CesseBase`,  `PayCesse`,  `PayTotal`,  `PYear`,  `PMon`,  `Sta`,  `HrmsDT`,  `CreateDT`,
                                    `DeptExaUser`,  `DeptExaNot`,  `DeptExaDT`,  `RehearUser`,  `RehearNot`,  `RehearDT`,
                                    `StatUser`,  `StatDT`,  `EmailFlag`,  `Remark`,  `SpeRewRem`,  `NewFlag`,  `CoGjjAm`,
                                    `CoShbAm`,  `PrepareAm`,  `HandicapAm`,  `ManageAm`,  `LeaveFlag`,  `NowAmFlag`,
                                    `rand_key`,  `chgflag`,  `AccRewAm`,  `AccDelAm`,  `ExpFlag`,
                                    `hdar`,  `bnar`,  `srar`,  `shbr`,  `gjjr`,  `sdar`,  `arar`,  `pcr`,  `wdt`,  `wdtr`,
                                    `OtherAccRewAm`,  `AccRewAmCes`,  `oarar`,  `usercom` , `comflag` ,`jfcom` ,`accrewam`)
                                    select `UserId`,  `DeptId`,  `SalaryDept`,  `Area`,  `BaseAm`,  `BaseNowAm`,  `FloatAm`,  `ProAm`,
                                    `SdyAm`,  `OtherAm`,  `BonusAm`,  `HolsDelAm`,  `PerHolsDays`,  `SickHolsDays`,
                                    `OthDelAm`,  `TotalAm`,  `ShbAm`,  `GjjAm`,  `SpeRewAm`,  `SpeDelAm`,  `CesseAm`,
                                    `CesseBase`,  `PayCesse`,  `PayTotal`,  `PYear`,  `PMon`,  `Sta`,  `HrmsDT`,  `CreateDT`,
                                    `DeptExaUser`,  `DeptExaNot`,  `DeptExaDT`,  `RehearUser`,  `RehearNot`,  `RehearDT`,
                                    `StatUser`,  `StatDT`,  `EmailFlag`,  `Remark`,  `SpeRewRem`,  `NewFlag`,  `CoGjjAm`,
                                    `CoShbAm`,  `PrepareAm`,  `HandicapAm`,  `ManageAm`,  `LeaveFlag`,  `NowAmFlag`,
                                    `rand_key`,  `chgflag`,  `AccRewAm`,  `AccDelAm`,  `ExpFlag`,
                                    `hdar`,  `bnar`,  `srar`,  `shbr`,  `gjjr`,  `sdar`,  `arar`,  `pcr`,  `wdt`,  `wdtr`,
                                    `OtherAccRewAm`,  `AccRewAmCes`,  `oarar`,  `usercom` , `comflag` 
                                    ,'".$usercom."' as jfcom , `accrewam`
                                    from ".$this->get_com_sql($res['usercom'])."salary_pay 
                                        where id='".$pid."' and comflag=0 ";
                            $this->db->query_exc($sql);
                            $pid_temp=$this->db->insert_id();
                            $sql=" delete  from ".$this->get_com_sql($res['usercom'])."salary_pay 
                                        where id='".$pid."' and comflag=0  ";
                            $this->db->query_exc($sql);
                            //end 更新数据库
                            $pid=$pid_temp;
                        }
                    }else{//新增
                        $sql="insert into 
                                ".$this->get_com_sql($usercom)."salary_pay 
                                ( `UserId` ,  `PYear`,  `PMon` , comflag )
                                values ( '".$res['userid']."' ,'".$subpy."' ,'".$subpm."' , 0 ) ";
                        $this->db->query_exc($sql);
                        $pid=$this->db->insert_id();
                    }
                    $update=true;
                }elseif($usersta=='关闭'){
                    
                    $this->model_salary_update($skey,
                        array('usersta'=>3)
                        , array(0)
                    );
                    if($pid){
                        $sql=" delete  from ".$this->get_com_sql($res['usercom'])."salary_pay 
                               where id='".$pid."' and comflag=0 ";
                        $this->db->query_exc($sql);
                    }
                }
            }elseif($subtype=='new'){
                $skey=get_rand_key();
                $sql="insert into 
                        salary
                        ( rand_key , comflag )
                        values ( '".$skey."' , 0) ";
                $this->db->query_exc($sql);
                $userid=$this->db->insert_id();
                
                $sql="insert into 
                        ".$this->get_com_sql($usercom)."salary_pay 
                        ( `UserId` ,  `PYear`,  `PMon` , comflag )
                        values ( '".$userid."' ,'".$subpy."' ,'".$subpm."' , 0 ) ";
                $this->db->query_exc($sql);
                $pid=$this->db->insert_id();
                $update=true;
            }
            if($update){
                $sup=array(
                    'username'=>$username
                    ,'oldname'=>$username
                    ,'usercom'=>$ffcom
                    ,'jfcom'=>$usercom
                    ,'deptid'=>$userdept
                    ,'olddept'=>$userdeptname
                    ,'usersta'=>2
                    ,'acc'=>$acc
                    ,'accbank'=>$accbank
                    ,'idcard'=>$idcard
                    ,'userid'=>$userid
                    ,'lpy'=>empty($accrewam)?0:$subpy
                    ,'lpm'=>empty($accrewam)?0:$subpm

                    ,'amount'=>$amount
                    ,'gjjam'=>$gjjam
                    ,'shbam'=>$shbam
                    ,'cogjjam'=>$cogjjam
                    ,'coshbam'=>$coshbam
                    ,'prepaream'=>$prepaream
                    ,'handicapam'=>$handicapam
                    ,'manageam'=>$manageam
                );
                $pup=array(
                    'usercom'=>$ffcom
                    ,'jfcom'=>$usercom
                    ,'deptid'=>$userdept
                    ,'SalaryDept'=>$userdeptname
                    ,'remark'=>$remark
                    ,'nowamflag'=>empty($accrewam)?0:3

                    ,'baseam'=>$amount
                    ,'gjjam'=>$gjjam
                    ,'shbam'=>$shbam
                    ,'cogjjam'=>$cogjjam
                    ,'coshbam'=>$coshbam
                    ,'prepaream'=>$prepaream
                    ,'handicapam'=>$handicapam
                    ,'manageam'=>$manageam
                    ,'sperewam'=>$sperewam
                    ,'spedelam'=>$spedelam
                    ,'accrewam'=>$accrewam
                );
                $this->model_salary_update($skey,
                    $sup
                    , array(0,1,2,3,4,5,6,7,8,9,10,11,12)
                );
                $this->model_pay_update($pid,
                    $pup
                    , array(0,1,2,3,4,5),$this->get_com_sql($usercom),0
                );
                if(round($amount)!=0){
                    $this->model_pay_stat($pid,$this->get_com_sql($usercom));
                }
            }
            $this->db->query("COMMIT");
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '额外工资信息', '成功',$msg);
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '额外工资信息', '失败',$e->getMessage().$msg);
        }
        return $responce;
    }
    /**
     * 专区数据
     */
    function model_hr_user_div($outtype='list'){
        //专区部门
        $sqlflag=" and ( p.deptid in ('". implode("','", $this->divDept) ."') ) ";
        if($outtype=='list'){
            return $this->model_hr_user(false,$sqlflag);
        }elseif($outtype=='xls'){
            return $this->model_hr_user(false,$sqlflag,false,false,'xls','hr_div');
        }
    }
    /**
     *部门员工信息
     * @return <type> 
     */
    function model_dp_user($outtype='list'){
        global $func_limit;
        $dppow=$this->model_dp_pow();
        /*
        $sqlL=" select userlevel from hrms where user_id = '".$_SESSION['USER_ID']."' ";
        $resL=$this->db->get_one($sqlL);
        if(!empty($resL['userlevel'])){
            $sqlL=" and ( h.userlevel>".$resL['userlevel']." or s.userid='".$_SESSION['USER_ID']."' ) ";
        }else{
            $sqlL="";
        }
         * 
         */
        if(!empty($func_limit['浏览部门'])){
            $sqlflag=" and ( p.deptid in ('". implode("','", $dppow['1'])."','" . implode("','", $dppow['2']) ."')
                or p.userid='".$_SESSION['USER_ID']."'
                or p.deptid in ( ".trim($func_limit['浏览部门'],',')." )
                ) ";
        }else{
            $sqlflag=" and ( p.deptid in ('". implode("','", $dppow['1'])."','" . implode("','", $dppow['2']) ."')
                or p.userid='".$_SESSION['USER_ID']."'
                ) ";
        }
        return $this->model_hr_user(false,$sqlflag,true,true,$outtype,'dp_detail');
    }
    /**
     * 员工信息类型
     */
    function model_dp_user_typel(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        if($flag==false){
            $sqlSch.=$sqlflag;
        }
        $start = $limit * $page - $limit;
        //总数
        $sql = "select count(*)
            from salary_user_type t
            where
                1
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select
                t.rand_key , t.name , t.members , t.membersn , t.remark
            from salary_user_type t
            where
                1
                $sqlSch
            order by $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $responce->rows[$i]['id'] = $row['userid'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array("", $row['rand_key'], $row['name'],$row['members'],$row['membersn']
                                , $row['remark']
                            )
                        );
            $i++;
        }
        return $responce;
    }
    function model_dp_user_typen(){
        $namet=$_POST['namet'];
        $remarkt=$_POST['remarkt'];
        $memberst=$_POST['memberst'];
        $membersnt=$_POST['membersnt'];
        $sub=$_POST['sub'];
        $id=$_POST['id'];
        try{
            if($sub=='new'){
                $sql="insert into salary_user_type (name , type , members , membersn , remark )
                    values ('".$namet."','pro', '".$memberst."', '".$membersnt."' , '".$remarkt."')";
                $this->db->query($sql);
            }elseif($sub=='edit'){
                $sql="update salary_user_type set name='".$namet."' , members ='".$memberst."'
                        , membersn ='".$membersnt."' , remark ='".$remarkt."'
                    where rand_key='".$id."' ";
                $this->db->query($sql);
            }elseif($sub=='del'){
                $sql="delete from salary_user_type where rand_key='".$id."' ";
                $this->db->query($sql);
            }
            $responce->id = $namet;
        }catch(Exception $e){
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '员工项目属性', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
     * 财务对账
     */
    function model_fn_user(){
        $sqlflag='';
        $seacompt = $_GET['seacompt']?$_GET['seacompt']:'0';
        if($seacompt!='-'){
            $sqlflag=" and p.expflag='".$seacompt."'  ";
        }
        global $func_limit;
        if(empty($_REQUEST['seacom'])&&!empty($func_limit['财务发放公司'])){
            $_REQUEST['seacom'] = $func_limit['财务发放公司'];
        }
        return $this->model_hr_user(false,$sqlflag,true);
    }
    /**
     * 
     */
    function model_ck_idcard(){
        $id = $_POST['id'];
        $idcard=$_POST['idcard'];
        $flag = $_POST['flag'];
        $scom = $_POST['scom'];
        $comtable=$this->get_com_sql($scom);
        if($flag=='stat'){
            $sql="select count(*) as cm from salary where idcard='".$idcard."'
                  and userid not in ( select userid from ".$comtable."salary_pay where id='".$id."')
                  and usersta!=3 and comflag=1 ";
        }else{
            $sql="select count(*) as cm from salary where idcard='".$idcard."' and rand_key<>'$id' and usersta!=3 and comflag=1 ";
        }
        $res = $this->db->get_one($sql);
        if(empty($res['cm'])){
            return '1';
        }else{
            return '0';
        }
    }
    /**
     *用户信息修改
     * @return <type>
     */
    function model_fn_info_in(){
        $id = $_POST['id'];
        $cb = $_POST['cb'];
        $flag = $_POST['flag'];
        try {
            $this->db->query("START TRANSACTION");
            if(empty($flag)){
                $sql = "select
                        s.userid , s.usersta , p.id as pid
                    from salary s
                        left join salary_pay p on ( s.userid=p.userid and p.pyear='".$this->nowy."' and p.pmon='".$this->nowm."' )
                    where
                        s.rand_key='$id' and s.usersta!='3'  ";
                $res = $this->db->get_one($sql);
                if(!$res['userid']){
                    throw new Exception('No data query');
                }
                $this->model_salary_update($id,
                            array('cessebase'=>$cb
                            )
                            , array(0));
                if(!empty($res['pid'])){
                    $this->model_pay_update($res['pid'],
                            array('cessebase' => $cb)
                            , array(0));
                    $this->model_pay_stat($res['pid']);
                }
            }elseif($flag=='stat'){//财务
                $username=$_POST['username'];
                $amount=round($_POST['amount'],2);
                $gjjam=round($_POST['gjjam'],2);
                $shbam=round($_POST['shbam'],2);
                $floatam=round($_POST['floatam'],2);
                $sperewam=round($_POST['sperewam'],2);
                $spedelam=round($_POST['spedelam'],2);
                $proam=round($_POST['proam'],2);
                $otheram=round($_POST['otheram'],2);
                $bonusam=round($_POST['bonusam'],2);
                $othdelam=round($_POST['othdelam'],2);
                $cessebase=round($_POST['cessebase'],2);
                $comedt=$_POST['comedt'];
                $oldarea=$_POST['oldarea'];
                $acc=$_POST['acc'];
                $accbank=$_POST['accbank'];
                $idcard=$_POST['idcard'];
                $email=$_POST['email'];
                $scom=$_POST['scom'];
                $comtable=$this->get_com_sql($scom);
                $sql = "select
                        s.userid , s.usersta , s.rand_key , p.id as pid
                    from salary s
                        left join ".$comtable."salary_pay p on ( s.userid=p.userid )
                    where
                        p.id='$id' ";
                $res = $this->db->get_one($sql);
                if(!$res['userid']){
                    throw new Exception('No data query');
                }
                if(!empty($res['rand_key'])){
                    $salaryData=array('amount'=>$amount,'floatam'=>$floatam,'gjjam'=>$gjjam
                            ,'shbam'=>$shbam,'cessebase'=>$cessebase,'comedt'=>$comedt
                            ,'oldarea'=>$oldarea,'acc'=>$acc,'accbank'=>$accbank,'idcard'=>$idcard
                            ,'email'=>$email,'oldname'=>$username,'username'=>$username
                        );
                    $this->model_salary_update($res['rand_key'],
                        $salaryData
                        , array(4,5,6,7,8,9,10,11,12));
                }
                if(!empty($res['pid'])){
                    $payData=array('cessebase'=>$cessebase,'baseam'=>$amount,'floatam'=>$floatam,'gjjam'=>$gjjam,'shbam'=>$shbam
                                ,'sperewam'=>$sperewam,'spedelam'=>$spedelam,'proam'=>$proam,'otheram'=>$otheram
                                ,'bonusam'=>$bonusam,'othdelam'=>$othdelam
                            );
                    $this->model_pay_update($res['pid'],
                            $payData
                            , array(0),$comtable);
                    $this->model_pay_stat($res['pid'],$comtable);
                }
            }elseif($flag=='hr'){
                $amount=round($_POST['amount'],2);
                $gjjam=round($_POST['gjjam'],2);
                $shbam=round($_POST['shbam'],2);
                $cogjjam=round($_POST['cogjjam'],2);
                $coshbam=round($_POST['coshbam'],2);
                $prepaream=round($_POST['prepaream'],2);
                $handicapam=round($_POST['handicapam'],2);
                $manageam=round($_POST['manageam'],2);
                $id=$_POST['id'];
                $leaveflag=$_POST['leaveflag'];
                $leavedt=$_POST['leavedt'];
                $sql = "select
                        s.userid , s.usersta , s.rand_key , p.id as pid
                        , s.comedt , p.pyear , p.pmon
                    from salary s
                        left join salary_pay p on ( s.userid=p.userid )
                    where
                        p.id='$id' ";
                $res = $this->db->get_one($sql);
                if(!$res['userid']){
                    throw new Exception('No data query');
                }
                if(!empty($res['rand_key'])){
                    if($leaveflag=='1'){
                        $salaryData=array('amount'=>$amount,'usersta'=>'3'
                        );
                    }else{
                        $salaryData=array('amount'=>$amount
                        );
                    }
                    if(!empty($leavedt)){
                        $salaryData['leavedt']=$leavedt;
                    }
                    $this->model_salary_update($res['rand_key'],
                        $salaryData
                        , array(1));
                }
                if(!empty($res['pid'])){
                    if(!empty($leavedt)){
                        $baseNow = $this->salaryClass->salaryDealLeave($res['comedt'],$leavedt, $amount);
                        $payData=array('baseam'=>$amount,'leaveflag'=>$leaveflag,'basenowam'=>$baseNow
                            ,'gjjam'=>$gjjam,'shbam'=>$shbam, 'cogjjam'=>$cogjjam , 'coshbam'=>$coshbam
                            ,'prepaream'=>$prepaream, 'handicapam'=>$handicapam,'manageam'=>$manageam
                            );
                    }else{
                        $tempComeDT=strtotime($res['comedt']);
                        if(date('Y',$tempComeDT)==$res['pyear']&&date('n',$tempComeDT)==$res['pmon']){
                            $baseNow = $this->salaryClass->salaryDeal($res['comedt'], $amount);
                        }else{
                            $baseNow = 0;
                        }
                        $payData=array('baseam'=>$amount,'leaveflag'=>$leaveflag,'basenowam'=>$baseNow
                            ,'gjjam'=>$gjjam,'shbam'=>$shbam, 'cogjjam'=>$cogjjam , 'coshbam'=>$coshbam
                            ,'prepaream'=>$prepaream, 'handicapam'=>$handicapam,'manageam'=>$manageam
                            );
                    }
                    $this->model_pay_update($res['pid'],
                            $payData
                            , array(1));
                    $this->model_pay_stat($res['pid']);
                }
                //var_dump($payData);
            }
            $this->db->query("COMMIT");
            $responce->id = $id;
            $msg='基础信息：'.print_r($salaryData,true).'本月缴费：'.print_r($payData,true);
            $this->globalUtil->insertOperateLog('工资管理', $id, '修改工资信息', '成功',$msg);
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '修改工资信息', '失败',$e->getMessage().$msg);
        }
        return $responce;
    }
    function model_fn_xls(){
        set_time_limit(600);
        global $func_limit;
        $syear=$_REQUEST["sy"];
        $smon=$_REQUEST["sm"];
        $stype=$_REQUEST['ty'];
        $ck=$_REQUEST['ck'];
        $seacom = $_REQUEST['seacom'];
        if(empty($seacom)&&!empty($func_limit['财务发放公司'])){
            $seacom = $func_limit['财务发放公司'];
        }
        if(empty($seacom)){
        	$seacom = '_';
        }
        $comtable = $this->get_com_sql($seacom);
        $xls=new includes_class_excelout('gb2312', true, 'My Test Sheet');
        try {
            if(!$syear||!$smon||!$stype){
                throw new Exception('非法数据！');
            }
            if($stype=="1"){
                if($ck=='subcom'){
                    if($_SESSION['USER_COM']=='sy'||$func_limit['财务发放公司']=='sy'){
                        $data = array(1=> array ('编号', '入职时间', '发薪日期', '姓名','直属部门', '部门'
                    , '城市', '基本工资', '当月入职工资', '浮动工资', '项目奖金及餐补', '其他补贴'
                    ,'奖金','其他扣除','特别奖励','事假','病假','扣请假工资','总工资合计','社保费'
                    ,'公积金','小计','税率','应纳税所得额','应扣税金额','税后扣除','实发工资','公司社保费','公司公积金','帐号','邮箱','身份证号','开户行','备注'));
                    $xls->setStyle(array(7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28));
                    $xls->setID(array(7=>'s95',8=>'s95',9=>'s95',10=>'s95'
                                    ,11=>'s95',12=>'s95',13=>'s95'
                                    ,16=>'s95',17=>'s95',18=>'s95',19=>'s95',20=>'s95'
                                    ,23=>'s95',24=>'s95',25=>'s95',26=>'s95',27=>'s95',28=>'s95'));
                    }else{
                        $data = array(1=> array ('编号', '入职时间', '发薪日期', '姓名','直属部门', '部门'
                    , '城市', '基本工资', '当月入职工资', '浮动工资', '项目奖金及餐补', '其他补贴'
                    ,'奖金','其他扣除','特别奖励','事假','病假','扣请假工资','总工资合计','社保费'
                    ,'公积金','扣除数','交纳税金','税后扣除','实发工资','公司社保费','公司公积金','帐号','邮箱','身份证号','开户行','备注'));
                    $xls->setStyle(array(7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26));
                    $xls->setID(array(7=>'s95',8=>'s95',9=>'s95',10=>'s95'
                                    ,11=>'s95',12=>'s95',13=>'s95'
                                    ,16=>'s95',17=>'s95',18=>'s95',19=>'s95',20=>'s95'
                                    ,21=>'s95',22=>'s95',23=>'s95',24=>'s95',25=>'s95',26=>'s95'));
                    }
                }else{
                    $data = array(1=> array ('编号', '入职时间', '发薪日期', '姓名','直属部门', '部门'
                    , '城市', '基本工资', '当月入职工资', '浮动工资', '项目奖金及餐补', '其他补贴'
                    ,'奖金','其他扣除','特别奖励','事假','病假','扣请假工资','总工资合计','社保费'
                    ,'公积金','扣除数','交纳税金','税后扣除','实发工资','公司社保费','公司公积金','帐号','邮箱','身份证号','开户行'));
                    $xls->setStyle(array(7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26));
                    $xls->setID(array(7=>'s95',8=>'s95',9=>'s95',10=>'s95'
                                    ,11=>'s95',12=>'s95',13=>'s95'
                                    ,16=>'s95',17=>'s95',18=>'s95',19=>'s95',20=>'s95'
                                    ,21=>'s95',22=>'s95',23=>'s95',24=>'s95',25=>'s95',26=>'s95'));
                }
                
            }elseif ($stype=="2") {
                $data = array(1=>array('身份证明类别','身份证明号码','纳税人姓名','所得项目'
                    ,'所得项目子目','收入所属期间（起）','收入所属期间（止）','部门','本次申报收入额:金额'
                    ,'按规定扣除项目:社会保险费','按规定扣除项目:住房公积金','法定减除费用额','税款负担方式'));
                $xls->setStyle(array(8,9,10,11,12));
                $xls->setID(array(8=>'s95',9=>'s95',10=>'s95',11=>'s95',12=>'s95'));
            }elseif ($stype=="3") {
                $data = array(1=>array('序号','金额','收款行名称','收款帐号','收款人名称','收款人地址'
                    ,'收款人帐户类型','资金用途','附言','多方协议号'));
                $xls->setStyle(array(1));
                $xls->setID(array(1=>'s95'));
            }elseif ($stype=="4") {
                $data = array(1=>array('直属部门','部门','总工资合计','社保费','公积金','缴纳税金','不足扣除社保公积金金额','税后扣除','实发工资','部门人数'));
                $xls->setStyle(array(2,3,4,5,6,7,8,9,10));
            }
            elseif ($stype=="6") {
                $data = array(1=>array('部门','子部门','工资总计','社保费总计','公积金总计','缴纳税金总计','实发工资总计'
                                            ,'一月总工资合计','一月社保费','一月公积金','一月缴纳税金','一月实发工资'
                                            ,'二月总工资合计','二月社保费','二月公积金','二月缴纳税金','二月实发工资'
                                            ,'三月总工资合计','三月社保费','三月公积金','三月缴纳税金','三月实发工资'
                                            ,'四月总工资合计','四月社保费','四月公积金','四月缴纳税金','四月实发工资'
                                            ,'五月总工资合计','五月社保费','五月公积金','五月缴纳税金','五月实发工资'
                                            ,'六月总工资合计','六月社保费','六月公积金','六月缴纳税金','六月实发工资'
                                            ,'七月总工资合计','七月社保费','七月公积金','七月缴纳税金','七月实发工资'
                                            ,'八月总工资合计','八月社保费','八月公积金','八月缴纳税金','八月实发工资'
                                            ,'九月总工资合计','九月社保费','九月公积金','九月缴纳税金','九月实发工资'
                                            ,'十月总工资合计','十月社保费','十月公积金','十月缴纳税金','十月实发工资'
                                            ,'十一月总工资合计','十一月社保费','十一月公积金','十一月缴纳税金','十一月实发工资'
                                            ,'十二月总工资合计','十二月社保费','十二月公积金','十二月缴纳税金','十二月实发工资'));
                $xls->setStyle(array(1,2,3,4,5,6,7,8,9,10
                                    ,11,12,13,14,15 ,16,17,18,19,20
                                    ,21,22,23,24,25,26,27,28,29,30
                                    ,31,32,33,34,35,36,37,38,39,40
                                    ,41,42,43,44,45,46,47,48,49,50
                                    ,51,52,53,54,55,56,57,58,59,60
                                    ,61,61,62,63,64,65));
                $xls->setID(array(1=>'s95',2=>'s95',3=>'s95',4=>'s95',5=>'s95',6=>'s95',7=>'s95',8=>'s95',9=>'s95',10=>'s95'
                                    ,11=>'s95',12=>'s95',13=>'s95',14=>'s95',15=>'s95' ,16=>'s95',17=>'s95',18=>'s95',19=>'s95',20=>'s95'
                                    ,21=>'s95',22=>'s95',23=>'s95',24=>'s95',25=>'s95',26=>'s95',27=>'s95',28=>'s95',29=>'s95',30=>'s95'
                                    ,31=>'s95',32=>'s95',33=>'s95',34=>'s95',35=>'s95',36=>'s95',37=>'s95',38=>'s95',39=>'s95',40=>'s95'
                                    ,41=>'s95',42=>'s95',43=>'s95',44=>'s95',45=>'s95',46=>'s95',47=>'s95',48=>'s95',49=>'s95',50=>'s95'
                                    ,51=>'s95',52=>'s95',53=>'s95',54=>'s95',55=>'s95',56=>'s95',57=>'s95',58=>'s95',59=>'s95',60=>'s95'
                                    ,61=>'s95',61=>'s95',62=>'s95',63=>'s95',64=>'s95',65=>'s95'));
            }elseif($stype=="7"){
                $data = array(1=> array ('编号','员工类型', '入职时间', '发薪日期', '姓名', '上级部门', '部门'
                    , '城市', '基本工资', '当月入职工资', '浮动工资', '项目奖金及餐补', '其他补贴'
                    ,'奖金','其他扣除','特别奖励','事假','病假','扣请假工资','总工资合计','社保费'
                    ,'公积金','扣除数','交纳税金','实发工资','年终奖金','年终奖税金','年终实发工资'));
                $xls->setStyle(array(6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26));
                $xls->setID(array(6=>'s95',7=>'s95',8=>'s95',9=>'s95',10=>'s95'
                                ,11=>'s95',12=>'s95',13=>'s95'
                                ,16=>'s95',17=>'s95',18=>'s95',19=>'s95',20=>'s95'
                                ,21=>'s95',22=>'s95',23=>'s95',24=>'s95',25=>'s95',26=>'s95'));
            }elseif($stype=="hr"){
                $data = array(1=> array ('编号','员工类型','员工编号','工资级别', '入职时间', '发薪日期', '姓名', '部门'
                    , '城市', '基本工资', '当月入职工资', '浮动工资', '项目奖金及餐补', '其他补贴'
                    ,'奖金','其他扣除','特别奖励','事假','病假','扣请假工资','总工资合计','社保费'
                    ,'公积金','扣除数','交纳税金','实发工资','年终奖金','年终奖税金','年终实发工资'));
                $xls->setStyle(array(6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28));
                $xls->setID(array(6=>'s95',7=>'s95',8=>'s95',9=>'s95',10=>'s95'
                                ,11=>'s95',12=>'s95',13=>'s95'
                                ,16=>'s95',17=>'s95',18=>'s95',19=>'s95',20=>'s95'
                                ,21=>'s95',22=>'s95',23=>'s95',24=>'s95',25=>'s95',26=>'s95',27=>'s95',28=>'s95'));
            }elseif($stype=="pro"){
                
            }
            if(isset($syear)&&trim($syear)!="-1"&&trim($syear)!="-"){
                $sqlStr.=" and p.pyear = '".($syear)."' ";
            }
            if(isset($smon)&&trim($smon)!="-1"&&trim($smon)!="-"&&$stype!="6"&&$stype!="7"&&$stype!="hr"){
                $sqlStr.=" and p.pmon  = '".($smon)."' ";
            }
            if($stype=="7"&&$this->nowy==$syear){
                $sqlStr.=" and p.pmon < ".($this->nowm)."  ";
            }
            if($stype=='7'){//年度统计
                $sqlStr.="  ";
            }elseif($stype=='hr'){
                $sqlStr.=" and h.userlevel='4' ";
            }else{//加上离职不显示
                $sqlStr.=" and p.expflag='0' and ( p.nowamflag!=3 or p.nowamflag is null ) ";
            }
            if($stype!=5){
                $sqlStr.=" and p.userid<>'eric.ye' ";
            }
            if($stype!='pro'){
                 $sql="select 
                s.id , p.id as pid , s.userid , u.user_name as username , s.oldname
                , s.accbank as bank, if(p.comflag=1,s.comedt,'额外人员') as comedt , d.dept_name , if(p.salarydept is null , s.olddept , p.salarydept ) as olddept
                , s.oldarea , s.email , p.area , p.pyear ,p.pmon , p.baseam
                , p.basenowam , p.floatam , p.proam , p.otheram , p.bonusam
                , p.perholsdays , p.sickholsdays, p.holsdelam , p.totalam
                , p.gjjam , p.shbam , p.cessebase , p.paycesse , p.paytotal
                , p.othdelam , s.acc , h.address , s.sta ,s.idcard , h.card_no
                , p.sperewam , p.cogjjam ,p.coshbam ,p.prepaream ,p.handicapam
                , p.manageam , p.sdyam , p.spedelam
                , h.usercard , p.expflag , h.userlevel , p.remark
                , p.cogjjam , p.coshbam , u.company , p.accdelam , p.accrewam 
                , u.dept_id , d.pdeptname, p.comflag , p.nowamflag
            from
                ".$comtable."salary_pay p
                left join salary s on ( s.userid=p.userid )
                left join hrms h on (s.userid=h.user_id)
                left join department d on (p.deptid=d.dept_id)
                left join user u on (s.userid=u.user_id )
            where s.userid=p.userid  and p.leaveflag='0'  and ( p.comflag=1 or p.baseam not in ('mKyYBwAYs6OhZVIyCcao0A==','rayq0Lssv8erWaEbiLsxCg==') ) 
                $sqlStr group by p.id order by s.comflag desc , s.id ";
            $query=$this->db->query($sql);
            }
           
            /**
             * $row["cessebase"],
                $this->salaryClass->decryptDeal($row["paycesse"]),
                $this->salaryClass->decryptDeal($row["paytotal"]),
             * 
             *
             * $totalam=$this->salaryClass->decryptDeal($row["totalam"]);
                $shbam=$this->salaryClass->decryptDeal($row["shbam"]);
                $gjjam=$this->salaryClass->decryptDeal($row["gjjam"]);
                $cessebase=$row["cessebase"]==2000?3000:$row["cessebase"];
                $cesseam=round($totalam-$shbam-$gjjam,2);
                $paycesse=$this->salaryClass->cesseDealNew($cesseam , $cessebase);
                $payam=$this->salaryClass->getFinanceValue($cesseam - $paycesse);
             *  $cessebase,
                $paycesse,
                $payam,
             */
            if($stype=="1"){ 
                if($ck=='subcom'){
                    if($_SESSION['USER_COM']=='sy'||$func_limit['财务发放公司']=='sy'){
                        while($row=$this->db->fetch_array($query)){
                            $tmpt=$this->salaryClass->decryptDeal($row["totalam"]);
                            $tmpshb=$this->salaryClass->decryptDeal($row["shbam"]);
                            $tmpgjj=$this->salaryClass->decryptDeal($row["gjjam"]);
                            $tmpyks=$tmpt-$tmpshb-$tmpgjj-$row["cessebase"];
                            if($tmpyks<0){
                                $tmpyks=0;
                            }
                            $tmpsl=$this->salaryClass->getCesseDeal($tmpt-$tmpshb-$tmpgjj);
                            $data[]=array($row["id"],
                                $row["comedt"],
                                $row["pyear"]."-".$row["pmon"],
                                $row["oldname"],
                                $row["pdeptname"],
                                $row["olddept"],
                                $row["oldarea"],
                                $this->salaryClass->decryptDeal($row["baseam"]),
                                $this->salaryClass->decryptDeal($row["basenowam"]),
                                $this->salaryClass->decryptDeal($row["floatam"]),
                                $this->salaryClass->decryptDeal($row["proam"])+$this->salaryClass->decryptDeal($row["sdyam"]),
                                $this->salaryClass->decryptDeal($row["otheram"]),
                                $this->salaryClass->decryptDeal($row["bonusam"]),
                                $this->salaryClass->decryptDeal($row["othdelam"])+$this->salaryClass->decryptDeal($row["spedelam"]),
                                $this->salaryClass->decryptDeal($row["sperewam"]),
                                $row["perholsdays"],
                                $row["sickholsdays"],
                                $this->salaryClass->decryptDeal($row["holsdelam"]),
                                $tmpt,
                                $tmpshb,
                                $tmpgjj,
                                $tmpshb+$tmpgjj,
                                $tmpsl,
                                $tmpyks,
                                $this->salaryClass->decryptDeal($row["paycesse"]),
                                $this->salaryClass->decryptDeal($row["accdelam"]),
                                $this->salaryClass->decryptDeal($row["paytotal"]),
                                $this->salaryClass->decryptDeal($row["coshbam"]),
                                $this->salaryClass->decryptDeal($row["cogjjam"]),
                                $row["acc"],
                                $row["email"],
                                $row["idcard"],
                                $row["bank"],
                                $row["remark"]);
                            }
                    }else{
                        while($row=$this->db->fetch_array($query)){
                            $data[]=array($row["id"],
                                $row["comedt"],
                                $row["pyear"]."-".$row["pmon"],
                                $row["oldname"],
                                $row["pdeptname"],
                                $row["olddept"],
                                $row["oldarea"],
                                $this->salaryClass->decryptDeal($row["baseam"]),
                                $this->salaryClass->decryptDeal($row["basenowam"]),
                                $this->salaryClass->decryptDeal($row["floatam"]),
                                $this->salaryClass->decryptDeal($row["proam"])+$this->salaryClass->decryptDeal($row["sdyam"]),
                                $this->salaryClass->decryptDeal($row["otheram"]),
                                $this->salaryClass->decryptDeal($row["bonusam"]),
                                $this->salaryClass->decryptDeal($row["othdelam"])+$this->salaryClass->decryptDeal($row["spedelam"]),
                                $this->salaryClass->decryptDeal($row["sperewam"]),
                                $row["perholsdays"],
                                $row["sickholsdays"],
                                $this->salaryClass->decryptDeal($row["holsdelam"]),
                                $this->salaryClass->decryptDeal($row["totalam"]),
                                $this->salaryClass->decryptDeal($row["shbam"]),
                                $this->salaryClass->decryptDeal($row["gjjam"]),
                                $row["cessebase"],
                                $this->salaryClass->decryptDeal($row["paycesse"]),
                                $this->salaryClass->decryptDeal($row["accdelam"]),
                                $this->salaryClass->decryptDeal($row["paytotal"]),
                                $this->salaryClass->decryptDeal($row["coshbam"]),
                                $this->salaryClass->decryptDeal($row["cogjjam"]),
                                $row["acc"],
                                $row["email"],
                                $row["idcard"],
                                $row["bank"],
                                $row["remark"]);
                            }
                    }
                }else{
                    while($row=$this->db->fetch_array($query)){
                        $data[]=array($row["id"],
                            $row["comedt"],
                            $row["pyear"]."-".$row["pmon"],
                            $row["oldname"],
                            $row["pdeptname"],
                            $row["olddept"],
                            $row["oldarea"],
                            $this->salaryClass->decryptDeal($row["baseam"]),
                            $this->salaryClass->decryptDeal($row["basenowam"]),
                            $this->salaryClass->decryptDeal($row["floatam"]),
                            $this->salaryClass->decryptDeal($row["proam"])+$this->salaryClass->decryptDeal($row["sdyam"]),
                            $this->salaryClass->decryptDeal($row["otheram"]),
                            $this->salaryClass->decryptDeal($row["bonusam"]),
                            $this->salaryClass->decryptDeal($row["othdelam"])+$this->salaryClass->decryptDeal($row["spedelam"]),
                            $this->salaryClass->decryptDeal($row["sperewam"]),
                            $row["perholsdays"],
                            $row["sickholsdays"],
                            $this->salaryClass->decryptDeal($row["holsdelam"]),
                            $this->salaryClass->decryptDeal($row["totalam"]),
                            $this->salaryClass->decryptDeal($row["shbam"]),
                            $this->salaryClass->decryptDeal($row["gjjam"]),
                            $row["cessebase"],
                            $this->salaryClass->decryptDeal($row["paycesse"]),
                            $this->salaryClass->decryptDeal($row["accdelam"]),
                            $this->salaryClass->decryptDeal($row["paytotal"]),
                            $this->salaryClass->decryptDeal($row["coshbam"]),
                            $this->salaryClass->decryptDeal($row["cogjjam"]),
                            $row["acc"],
                            $row["email"],
                            $row["idcard"],
                            $row["bank"]);
                    }
                }
            }elseif($stype=="2"){
                while($row=$this->db->fetch_array($query)){
                    if($row["comflag"]=='0'&&round($this->salaryClass->decryptDeal($row["totalam"]))==0){//过滤额外人员不用交个税
                        continue;
                    }
                    $data[]=array('', $row["idcard"],$row["oldname"],'',
                            '月度工资薪金',
                            $row["pyear"]."-".$row["pmon"].'-01',
                            $row["pyear"]."-".$row["pmon"].'-'.date('t',strtotime($row["pyear"]."-".$row["pmon"].'-01')),
                            $row["olddept"],
                            $this->salaryClass->decryptDeal($row["totalam"]),
                            $this->salaryClass->decryptDeal($row["shbam"]),
                            $this->salaryClass->decryptDeal($row["gjjam"]),
                            $row["cessebase"],
                            '');
                }
            }elseif($stype=="3"){
                $x=0;
                while($row=$this->db->fetch_array($query)){
                    if($row["comflag"]=='0'&&round($this->salaryClass->decryptDeal($row["paytotal"]))==0){//过滤额外人员不用付款
                        continue;
                    }
                    $x++;
                    $data[]=array($x,
                    $this->salaryClass->decryptDeal($row["paytotal"]),
                    $row["bank"],
                    $row["acc"],
                    $row["oldname"],
                    '',
                    '',
                    '',
                    '',
                    '');
                }
            }elseif($stype=="4"){
                //'部门','总工资合计','社保费','公积金','缴纳税金','不足扣除社保公积金金额','税后扣除','实发工资','部门人数'
                $deptArr=array();
                while($row=$this->db->fetch_array($query)){
                    $tam=$this->salaryClass->decryptDeal($row['totalam']);
                    $sam=$this->salaryClass->decryptDeal($row['shbam']);
                    $gam=$this->salaryClass->decryptDeal($row['gjjam']);
                    $aam=$this->salaryClass->decryptDeal($row["accdelam"]);
                    $tempam=round($tam-$sam-$gam,2);
                    if($tempam<0){
                        $tempam=abs($tempam);
                    }else{
                        $tempam=0;
                    }
                    $deptArr[$row['olddept']]['totalam']=isset($deptArr[$row['olddept']]['totalam'])?round($deptArr[$row['olddept']]['totalam']+$tam,2):$tam;
                    $deptArr[$row['olddept']]['shbam']=isset($deptArr[$row['olddept']]['shbam'])?round($deptArr[$row['olddept']]['shbam']+$sam,2):$sam;
                    $deptArr[$row['olddept']]['gjjam']=isset($deptArr[$row['olddept']]['gjjam'])?round($deptArr[$row['olddept']]['gjjam']+$gam,2):$gam;
                    $deptArr[$row['olddept']]['paycesse']=isset($deptArr[$row['olddept']]['paycesse'])?round($deptArr[$row['olddept']]['paycesse']+$this->salaryClass->decryptDeal($row['paycesse']),2):$this->salaryClass->decryptDeal($row['paycesse']);
                    $deptArr[$row['olddept']]['paytotal']=isset($deptArr[$row['olddept']]['paytotal'])?round($deptArr[$row['olddept']]['paytotal']+$this->salaryClass->decryptDeal($row['paytotal']),2):$this->salaryClass->decryptDeal($row['paytotal']);
                    $deptArr[$row['olddept']]['count']=isset($deptArr[$row['olddept']]['count'])?$deptArr[$row['olddept']]['count']+1:1;
                    $deptArr[$row['olddept']]['accdelam']=isset($deptArr[$row['olddept']]['accdelam'])?$deptArr[$row['olddept']]['accdelam']+$aam:$aam;
                    $deptArr[$row['olddept']]['tempam']=isset($deptArr[$row['olddept']]['tempam'])?$deptArr[$row['olddept']]['tempam']+$tempam:$tempam;
                    $deptArr[$row['olddept']]['pdeptname']=$row['pdeptname'];
                }
                foreach ($deptArr as $key=>$val) {
                    $data[]=array($val['pdeptname'],$key,
                    $val['totalam'],
                    $val['shbam'],
                    $val['gjjam'],
                    $val['paycesse'],
                    $val['tempam'],
                    $val['accdelam'],
                    $val['paytotal'],
                    $val['count']
                    );
                }
                
            }elseif($stype=='pro'){
                /*弃用 2013-05
                $sql="select count(*) as am
                    from salary_user_type where pyear='".$syear."' and pmon='".$smon."'  ";
                $res = $this->db->get_one($sql);
                if(empty($res['am'])&&( ($syear>2012) || ($syear==$this->nowy && $smon<$this->nowm) || $syear<$this->nowy ) ){
                    $sql="insert into  salary_user_type (name ,type , members , membersn, pyear , pmon , rand_key , pid )
                          select  name ,'pro' , developer , '' as membersn , '".$syear."' ,  '".$smon."'  , rand() , id
                          from project_rd where is_delete=0 and status in (0 , 5)  ";
                    $this->db->get_one($sql);
                    $sql="insert into  salary_type_info (userid ,typeid , percent )
                          SELECT a.account , t.id , a.percent  FROM project_rd_action a 
                          left join salary_user_type t on (a.project = t.pid )
                          where t.pyear='".$syear."' and t.pmon='".$smon."' ";
                    $this->db->get_one($sql);
                    $proArr=array();
                    $sql="(SELECT t.id ,  t.name , t.pyear , t.pmon , p.userid ,if(i.percent=0,100,i.percent) as percent , p.totalam  , p.shbam , p.gjjam , p.paycesse , p.paytotal  ,'' as com  
                        FROM salary_user_type t 
			    left join salary_type_info i on (t.id=i.typeid)
                            left join salary_pay p on ( p.userid=i.userid and p.pyear=t.pyear and p.pmon=t.pmon )
                            where p.id is not null and t.pyear = '".$syear."' and t.pmon ='".$smon."' )
                            union 
                            (SELECT t.id ,  t.name , t.pyear , t.pmon , p.userid ,if(i.percent=0,100,i.percent) as percent , p.totalam  , p.shbam , p.gjjam , p.paycesse , p.paytotal ,'s' as com   
                        FROM salary_user_type t 
                            left join salary_type_info i on (t.id=i.typeid)
                            left join `shiyuanoa`.salary_pay p on ( p.userid=i.userid and p.pyear=t.pyear and p.pmon=t.pmon )
                            where p.id is not null and t.pyear = '".$syear."' and t.pmon ='".$smon."' )
                            union 
                            (SELECT t.id ,  t.name , t.pyear , t.pmon , p.userid ,if(i.percent=0,100,i.percent) as percent  , p.totalam  , p.shbam , p.gjjam , p.paycesse , p.paytotal ,'b' as com  
                        FROM salary_user_type t 
                            left join salary_type_info i on (t.id=i.typeid)
                            left join `beiruanoa`.salary_pay p on ( p.userid=i.userid and p.pyear=t.pyear and p.pmon=t.pmon )
                            where p.id is not null and t.pyear = '".$syear."' and t.pmon ='".$smon."' )";
                    $query=$this->db->query($sql);
                    while($row=$this->db->fetch_array($query)){

                        $proArr[$row['id']][$row['com'].'totalam']=isset($proArr[$row['id']][$row['com'].'totalam'])?round($proArr[$row['id']][$row['com'].'totalam']+($this->salaryClass->decryptDeal($row['totalam'])*$row['percent']/100),2):($this->salaryClass->decryptDeal($row['totalam'])*$row['percent']/100);
                        $proArr[$row['id']][$row['com'].'shbam']=isset($proArr[$row['id']][$row['com'].'shbam'])?round($proArr[$row['id']][$row['com'].'shbam']+($this->salaryClass->decryptDeal($row['shbam'])*$row['percent']/100),2):($this->salaryClass->decryptDeal($row['shbam'])*$row['percent']/100);
                        $proArr[$row['id']][$row['com'].'gjjam']=isset($proArr[$row['id']][$row['com'].'gjjam'])?round($proArr[$row['id']][$row['com'].'gjjam']+($this->salaryClass->decryptDeal($row['gjjam'])*$row['percent']/100),2):($this->salaryClass->decryptDeal($row['gjjam'])*$row['percent']/100);
                        $proArr[$row['id']][$row['com'].'paycesse']=isset($proArr[$row['id']][$row['com'].'paycesse'])?round($proArr[$row['id']][$row['com'].'paycesse']+($this->salaryClass->decryptDeal($row['paycesse'])*$row['percent']/100),2):($this->salaryClass->decryptDeal($row['paycesse'])*$row['percent']/100);
                        $proArr[$row['id']][$row['com'].'paytotal']=isset($proArr[$row['id']][$row['com'].'paytotal'])?round($proArr[$row['id']][$row['com'].'paytotal']+($this->salaryClass->decryptDeal($row['paytotal'])*$row['percent']/100),2):($this->salaryClass->decryptDeal($row['paytotal'])*$row['percent']/100);

                    }

                    if(!empty($proArr)){
                        foreach ($proArr as $key=>$val) {
                            $sql="update salary_user_type set 
                                    totalam = '".$val['totalam']."'  , shbam = '".$val['shbam']."'
                                    , gjjam = '".$val['gjjam']."', paycesse = '".$val['paycesse']."'
                                    , paytotal = '".$val['paytotal']."'

                                    ,stotalam = '".$val['stotalam']."'  , sshbam = '".$val['sshbam']."'
                                    , sgjjam = '".$val['sgjjam']."', spaycesse = '".$val['spaycesse']."'
                                    , spaytotal = '".$val['spaytotal']."'

                                    ,btotalam = '".$val['btotalam']."'  , bshbam = '".$val['bshbam']."'
                                    , bgjjam = '".$val['bgjjam']."', bpaycesse = '".$val['bpaycesse']."'
                                    , bpaytotal = '".$val['bpaytotal']."'
                                where id='".$key."' ";
                            $this->db->query($sql);
                        }
                    }
                }
                $data = array(1=> array('项目名','募投项目'
                    ,'鼎利总工资合计','鼎利社保费','鼎利公积金','鼎利缴纳税金','鼎利实发工资'
                    ,'世源总工资合计','世源社保费','世源公积金','世源缴纳税金','世源实发工资'
                    ,'贝软总工资合计','贝软社保费','贝软公积金','贝软缴纳税金','贝软实发工资'
                    )
                    );
                $xls->setStyle(array(1,2,3,4,5,6,7,8,9,10,
                    11,12,13,14,15,16,17,18,19,20
                    ,21,22,23,24,25,26,27,28,29,30));
                $xls->setID(array(
                    1=>'s95',2=>'s95',3=>'s95',4=>'s95',5=>'s95',6=>'s95',7=>'s95',8=>'s95',9=>'s95',10=>'s95',
                    11=>'s95',12=>'s95',13=>'s95',14=>'s95',15=>'s95',16=>'s95',17=>'s95',18=>'s95',19=>'s95',20=>'s95'
                    ,21=>'s95',22=>'s95',23=>'s95',24=>'s95',25=>'s95',26=>'s95',27=>'s95',28=>'s95',29=>'s95',30=>'s95'));
                $sql="select t.name , t.totalam , t.shbam , t.gjjam , t.paycesse ,t.paytotal 
                        , t.stotalam , t.sshbam , t.sgjjam , t.spaycesse ,t.spaytotal
                        , t.btotalam , t.bshbam , t.bgjjam , t.bpaycesse ,t.bpaytotal 
                        , t.pmon , i.project_name as iponame
                        from salary_user_type t
                        left join project_rd r on (t.pid=r.id)
                        left join project_ipo i on (r.ipo_id=i.id)
                        where t.pyear='".$syear."' and t.pmon='".$smon."'  ";
                $query=$this->db->query($sql);
                $resData=array();
                while($row=$this->db->fetch_array($query)){
                    $resData[$row['name']]=array(
                        'iponame'=>$row['iponame']
                        ,'totalam'=>$row['totalam'],'shbam'=>$row['shbam']
                        ,'gjjam'=>$row['gjjam'],'paycesse'=>$row['paycesse'],'paytotal'=>$row['paytotal']
                        ,'stotalam'=>$row['stotalam'],'sshbam'=>$row['sshbam']
                        ,'sgjjam'=>$row['sgjjam'],'spaycesse'=>$row['spaycesse'],'spaytotal'=>$row['spaytotal']
                        ,'btotalam'=>$row['btotalam'],'bshbam'=>$row['bshbam']
                        ,'bgjjam'=>$row['bgjjam'],'bpaycesse'=>$row['bpaycesse'],'bpaytotal'=>$row['bpaytotal']
                    );
                }
                if($resData){
                    foreach($resData as $key=>$row){
                        $data[]=array(
                        $key,$row['iponame']
                        ,$row['totalam'],$row['shbam'],$row['gjjam'],$row['paycesse'],$row['paytotal']
//                        世源
                        ,$row['stotalam'],$row['sshbam'],$row['sgjjam'],$row['spaycesse'],$row['spaytotal']
//                        贝软
                        ,$row['btotalam'],$row['bshbam'],$row['bgjjam'],$row['bpaycesse'],$row['bpaytotal']
                        );
                    }
                }
                /*
                 * 
                */
                
            }elseif($stype=='6'){
                $deptArr=array();
                $deptCh=array();
                while($row=$this->db->fetch_array($query)){
                    $deptCh[$row['dept_id']]=1;
                    $deptArr[$row['dept_id']][$row['pmon']]['totalam']=
                        isset($deptArr[$row['dept_id']][$row['pmon']]['totalam'])?
                        round($deptArr[$row['dept_id']][$row['pmon']]['totalam']+$this->salaryClass->decryptDeal($row['totalam']),2):
                        $this->salaryClass->decryptDeal($row['totalam']);
                    $deptArr[$row['dept_id']][$row['pmon']]['shbam']=
                        isset($deptArr[$row['dept_id']][$row['pmon']]['shbam'])?
                        round($deptArr[$row['dept_id']][$row['pmon']]['shbam']+$this->salaryClass->decryptDeal($row['shbam']),2):
                        $this->salaryClass->decryptDeal($row['shbam']);
                    $deptArr[$row['dept_id']][$row['pmon']]['gjjam']=
                        isset($deptArr[$row['dept_id']][$row['pmon']]['gjjam'])?
                        round($deptArr[$row['dept_id']][$row['pmon']]['gjjam']+$this->salaryClass->decryptDeal($row['gjjam']),2):
                        $this->salaryClass->decryptDeal($row['gjjam']);
                    $deptArr[$row['dept_id']][$row['pmon']]['paycesse']=
                        isset($deptArr[$row['dept_id']][$row['pmon']]['paycesse'])?
                        round($deptArr[$row['dept_id']][$row['pmon']]['paycesse']+$this->salaryClass->decryptDeal($row['paycesse']),2):
                        $this->salaryClass->decryptDeal($row['paycesse']);
                    $deptArr[$row['dept_id']][$row['pmon']]['paytotal']=
                        isset($deptArr[$row['dept_id']][$row['pmon']]['paytotal'])?
                        round($deptArr[$row['dept_id']][$row['pmon']]['paytotal']+$this->salaryClass->decryptDeal($row['paytotal']),2):
                        $this->salaryClass->decryptDeal($row['paytotal']);
                }
                if(!empty($deptCh)){
                    $sql="SELECT d.dept_name,d.dept_id , df.dept_name as df_dept_name  FROM department d 
left join department df on ( left(d.depart_x,2)=df.depart_x )
where d.dept_id in ('".implode("','", array_keys($deptCh))."')  group by d.dept_id order by df.dept_name ";
                    $query=$this->db->query($sql);
                    $deptCh=array();
                    while($row=$this->db->fetch_array($query)){
                        $deptCh[$row['dept_id']]['df']=$row['df_dept_name'];
                        $deptCh[$row['dept_id']]['dn']=$row['dept_name'];
                    }
                }
                foreach ($deptCh as $key=>$val) {
                    $tam=round( $deptArr[$key]['1']['totalam']+$deptArr[$key]['2']['totalam']+$deptArr[$key]['3']['totalam']
                                +$deptArr[$key]['4']['totalam']+$deptArr[$key]['5']['totalam']+$deptArr[$key]['6']['totalam']
                                +$deptArr[$key]['7']['totalam']+$deptArr[$key]['8']['totalam']+$deptArr[$key]['9']['totalam']
                                +$deptArr[$key]['10']['totalam']+$deptArr[$key]['11']['totalam']+$deptArr[$key]['12']['totalam'],2);
                    $shbam=round( $deptArr[$key]['1']['shbam']+$deptArr[$key]['2']['shbam']+$deptArr[$key]['3']['shbam']
                                +$deptArr[$key]['4']['shbam']+$deptArr[$key]['5']['shbam']+$deptArr[$key]['6']['shbam']
                                +$deptArr[$key]['7']['shbam']+$deptArr[$key]['8']['shbam']+$deptArr[$key]['9']['shbam']
                                +$deptArr[$key]['10']['shbam']+$deptArr[$key]['11']['shbam']+$deptArr[$key]['12']['shbam'],2);
                    $gjjam=round( $deptArr[$key]['1']['gjjam']+$deptArr[$key]['2']['gjjam']+$deptArr[$key]['3']['gjjam']
                                +$deptArr[$key]['4']['gjjam']+$deptArr[$key]['5']['gjjam']+$deptArr[$key]['6']['gjjam']
                                +$deptArr[$key]['7']['gjjam']+$deptArr[$key]['8']['gjjam']+$deptArr[$key]['9']['gjjam']
                                +$deptArr[$key]['10']['gjjam']+$deptArr[$key]['11']['gjjam']+$deptArr[$key]['12']['gjjam'],2);
                    $paycesse=round( $deptArr[$key]['1']['paycesse']+$deptArr[$key]['2']['paycesse']+$deptArr[$key]['3']['paycesse']
                                +$deptArr[$key]['4']['paycesse']+$deptArr[$key]['5']['paycesse']+$deptArr[$key]['6']['paycesse']
                                +$deptArr[$key]['7']['paycesse']+$deptArr[$key]['8']['paycesse']+$deptArr[$key]['9']['paycesse']
                                +$deptArr[$key]['10']['paycesse']+$deptArr[$key]['11']['paycesse']+$deptArr[$key]['12']['paycesse'],2);
                    $paytotal=round( $deptArr[$key]['1']['paytotal']+$deptArr[$key]['2']['paytotal']+$deptArr[$key]['3']['paytotal']
                                +$deptArr[$key]['4']['paytotal']+$deptArr[$key]['5']['paytotal']+$deptArr[$key]['6']['paytotal']
                                +$deptArr[$key]['7']['paytotal']+$deptArr[$key]['8']['paytotal']+$deptArr[$key]['9']['paytotal']
                                +$deptArr[$key]['10']['paytotal']+$deptArr[$key]['11']['paytotal']+$deptArr[$key]['12']['paytotal'],2);
                    $data[]=array($val['df'],$val['dn']
                        ,$tam,$shbam,$gjjam,$paycesse,$paytotal
                        ,$deptArr[$key]['1']['totalam'],$deptArr[$key]['1']['shbam'],$deptArr[$key]['1']['gjjam'],$deptArr[$key]['1']['paycesse'],$deptArr[$key]['1']['paytotal']
                        ,$deptArr[$key]['2']['totalam'],$deptArr[$key]['2']['shbam'],$deptArr[$key]['2']['gjjam'],$deptArr[$key]['2']['paycesse'],$deptArr[$key]['2']['paytotal']
                        ,$deptArr[$key]['3']['totalam'],$deptArr[$key]['3']['shbam'],$deptArr[$key]['3']['gjjam'],$deptArr[$key]['3']['paycesse'],$deptArr[$key]['3']['paytotal']
                        ,$deptArr[$key]['4']['totalam'],$deptArr[$key]['4']['shbam'],$deptArr[$key]['4']['gjjam'],$deptArr[$key]['4']['paycesse'],$deptArr[$key]['4']['paytotal']
                        ,$deptArr[$key]['5']['totalam'],$deptArr[$key]['5']['shbam'],$deptArr[$key]['5']['gjjam'],$deptArr[$key]['5']['paycesse'],$deptArr[$key]['5']['paytotal']
                        ,$deptArr[$key]['6']['totalam'],$deptArr[$key]['6']['shbam'],$deptArr[$key]['6']['gjjam'],$deptArr[$key]['6']['paycesse'],$deptArr[$key]['6']['paytotal']
                        ,$deptArr[$key]['7']['totalam'],$deptArr[$key]['7']['shbam'],$deptArr[$key]['7']['gjjam'],$deptArr[$key]['7']['paycesse'],$deptArr[$key]['7']['paytotal']
                        ,$deptArr[$key]['8']['totalam'],$deptArr[$key]['8']['shbam'],$deptArr[$key]['8']['gjjam'],$deptArr[$key]['8']['paycesse'],$deptArr[$key]['8']['paytotal']
                        ,$deptArr[$key]['9']['totalam'],$deptArr[$key]['9']['shbam'],$deptArr[$key]['9']['gjjam'],$deptArr[$key]['9']['paycesse'],$deptArr[$key]['9']['paytotal']
                        ,$deptArr[$key]['10']['totalam'],$deptArr[$key]['10']['shbam'],$deptArr[$key]['10']['gjjam'],$deptArr[$key]['10']['paycesse'],$deptArr[$key]['10']['paytotal']
                        ,$deptArr[$key]['11']['totalam'],$deptArr[$key]['11']['shbam'],$deptArr[$key]['11']['gjjam'],$deptArr[$key]['11']['paycesse'],$deptArr[$key]['11']['paytotal']
                        ,$deptArr[$key]['12']['totalam'],$deptArr[$key]['12']['shbam'],$deptArr[$key]['12']['gjjam'],$deptArr[$key]['12']['paycesse'],$deptArr[$key]['12']['paytotal']
                    );
                }
            }elseif($stype=="7"||$stype=="hr"){
                $userData=array();
                while($row=$this->db->fetch_array($query)){
                    $userData[$row['id']]=array(
                        'id'=>$row["id"],
                        'comedt'=>$row["comedt"],
                        'pyear'=>$row["pyear"],
                        'name'=>$row["oldname"],
                    	'pdept'=>$row["pdeptname"],
                        'dept'=>$row["olddept"],
                        'area'=>$row["oldarea"],
                        'baseam'=>round($this->salaryClass->decryptDeal($row["baseam"])+$userData[$row['id']]['baseam'],2),
                        'basenowam'=>round($this->salaryClass->decryptDeal($row["basenowam"])+$userData[$row['id']]['basenowam'],2),
                        'floatam'=>round($this->salaryClass->decryptDeal($row["floatam"])+$userData[$row['id']]['floatam'],2),
                        'ps'=>round($this->salaryClass->decryptDeal($row["proam"])+$this->salaryClass->decryptDeal($row["sdyam"])+$userData[$row['id']]['ps'],2),
                        'otheram'=>round($this->salaryClass->decryptDeal($row["otheram"])+$userData[$row['id']]['otheram'],2),
                        'bonusam'=>round($this->salaryClass->decryptDeal($row["bonusam"])+$userData[$row['id']]['bonusam'],2),
                        'os'=>round($this->salaryClass->decryptDeal($row["othdelam"])+$this->salaryClass->decryptDeal($row["spedelam"])+$userData[$row['id']]['os'],2),
                        'sperewam'=>round($this->salaryClass->decryptDeal($row["sperewam"])+$userData[$row['id']]['sperewam'],2),
                        'perholsdays'=>round($row["perholsdays"]+$userData[$row['id']]['perholsdays']),
                        'sickholsdays'=>round($row["sickholsdays"]+$userData[$row['id']]['sickholsdays']),
                        'holsdelam'=>round($this->salaryClass->decryptDeal($row["holsdelam"])+$userData[$row['id']]['holsdelam'],2),
                        'totalam'=>round($this->salaryClass->decryptDeal($row["totalam"])+$userData[$row['id']]['totalam'],2),
                        'shbam'=>round($this->salaryClass->decryptDeal($row["shbam"])+$userData[$row['id']]['shbam'],2),
                        'gjjam'=>round($this->salaryClass->decryptDeal($row["gjjam"])+$userData[$row['id']]['gjjam'],2),
                        'cessebase'=>$row["cessebase"],
                        'paycesse'=>round($this->salaryClass->decryptDeal($row["paycesse"])+$userData[$row['id']]['paycesse'],2),
                        'paytotal'=>round($this->salaryClass->decryptDeal($row["paytotal"])+$userData[$row['id']]['paytotal'],2),
                        'acc'=>$row["acc"],
                        'email'=>$row["email"],
                        'idcard'=>$row["idcard"],
                        'bank'=>$row["bank"],
                        'expflag'=>$this->expflag[$row["expflag"]],
                        'usercard'=>$row["usercard"],
                        'userlevel'=>$this->userLevel[$row["userlevel"]]
                    );
                }
                /**
                 * 年终奖数据
                 */
                $sql="select
                    y.yearam , y.payam , y.paycesseam , s.id
                from salary_yeb y
                    left join hrms h on (y.usercard=h.usercard)
                    left join salary s on (h.user_id=s.userid)
                where 
                    y.syear='".$syear."' and s.id is not null  ";
                $query=$this->db->query($sql);
                while($row=$this->db->fetch_array($query) ){
                    if(!empty($userData[$row['id']])){
                        $userData[$row['id']]['yam']=$this->salaryClass->decryptDeal($row["yearam"]);
                        $userData[$row['id']]['pceam']=$this->salaryClass->decryptDeal($row["paycesseam"]);
                        $userData[$row['id']]['payam']=$this->salaryClass->decryptDeal($row["payam"]);
                    }
                }
                if($stype=='7'){
                    foreach($userData as $key=>$val){
                        $data[]=array($val['id'],
                            $val["expflag"],
                            $val["comedt"],
                            $val["pyear"],
                            $val["name"],
                        	$val["pdept"],
                            $val["dept"],
                            $val["area"],
                            $val["baseam"],
                            $val["basenowam"],
                            $val["floatam"],
                            $val["ps"],
                            $val["otheram"],
                            $val["bonusam"],
                            $val["os"],
                            $val["sperewam"],
                            $val["perholsdays"],
                            $val["sickholsdays"],
                            $val["holsdelam"],
                            $val["totalam"],
                            $val["shbam"],
                            $val["gjjam"],
                            $val["cessebase"],
                            $val["paycesse"],
                            $val["paytotal"],
                            $val["yam"],
                            $val["pceam"],
                            $val["payam"]);
                    }
                }elseif($stype=="hr"){
                    foreach($userData as $key=>$val){
                        $data[]=array($val['id'],
                            $val['usercard'],
                            $val['userlevel'],
                            $val["expflag"],
                            $val["comedt"],
                            $val["pyear"],
                            $val["name"],
                            $val["dept"],
                            $val["area"],
                            $val["baseam"],
                            $val["basenowam"],
                            $val["floatam"],
                            $val["ps"],
                            $val["otheram"],
                            $val["bonusam"],
                            $val["os"],
                            $val["sperewam"],
                            $val["perholsdays"],
                            $val["sickholsdays"],
                            $val["holsdelam"],
                            $val["totalam"],
                            $val["shbam"],
                            $val["gjjam"],
                            $val["cessebase"],
                            $val["paycesse"],
                            $val["paytotal"],
                            $val["yam"],
                            $val["pceam"],
                            $val["payam"]);
                    }
                }
            }
            if($stype=='5'){
                $data=array();
                $mastoData=array('发薪时间','姓名','员工号','基本工资','总工资合计','社保费','公积金','交纳税金','实发工资');
                while($row=$this->db->fetch_array($query)){
                    if($row["nowamflag"]=='4'){//过滤冻结人员
                         continue;
                    }
                    $nowam=$this->salaryClass->decryptDeal($row["basenowam"]);
                    if(($nowam==0||$nowam=='')&&$row['company']!='dl' ){
                        $nowam=$this->salaryClass->decryptDeal($row["baseam"]);
                    }
                    $data[]=array(
                        '发薪时间'=>$row["pyear"]."-".$row["pmon"],
                        '姓名'=>$row["oldname"],
                        '员工号'=>$row["usercard"],
                        '基本工资'=>$this->salaryClass->decryptDeal($row["baseam"]),
                        '当月工资'=>$nowam,
                        '浮动工资'=>$this->salaryClass->decryptDeal($row["floatam"]),
                        '项目奖金'=>$this->salaryClass->decryptDeal($row["proam"]),
                        '节假日补贴'=>$this->salaryClass->decryptDeal($row["sdyam"]),
                        '福利补贴'=>$this->salaryClass->decryptDeal($row["otheram"]),
                        '奖金'=>$this->salaryClass->decryptDeal($row["bonusam"]),
                        '特别奖励'=>$this->salaryClass->decryptDeal($row["sperewam"]),
                        '特别扣除'=>$this->salaryClass->decryptDeal($row["spedelam"]),
                        '事假'=>$row["perholsdays"],'病假'=>$row["sickholsdays"],
                        '扣请假工资'=>$this->salaryClass->decryptDeal($row["holsdelam"]),
                        '总工资合计'=>$this->salaryClass->decryptDeal($row["totalam"]),
                        '社保费'=>$this->salaryClass->decryptDeal($row["shbam"]),
                        '公积金'=>$this->salaryClass->decryptDeal($row["gjjam"]),
                        '交纳税金'=>$this->salaryClass->decryptDeal($row["paycesse"]),
                        '税后扣款'=>$this->salaryClass->decryptDeal($row["accdelam"]),
                        '税后奖励'=>$this->salaryClass->decryptDeal($row["accrewam"]),
                        '实发工资'=>$this->salaryClass->decryptDeal($row["paytotal"]),
                        '备注'=>$row["remark"],
                        '身份证'=>$row["idcard"],
                        '邮箱'=>$row["email"]
                        );
                }
                $csd=false;
                foreach($data as $val){
                    /**
                     * if($val["邮箱"]=='guiling.li@dinglicom.com'){
                        continue;
                    }
                     * if($val["邮箱"]=='yan.sun@dinglicom.com'){
                        $csd=true;
                    }
                    if($csd==false){
                        continue;
                    }
                     */
                    if(empty($val["邮箱"])){//过滤空邮箱-额外人员
                        continue;
                    }
                    $str="您好！您的工资已提交并通过审核，请在三个工作日后查收工资，节假日及特殊情况顺延！<br/>";
                    $strTR="";
                    foreach ($val as $vkey=>$vval){
                        if($vkey!='邮箱'){
                            if(in_array($vkey,$mastoData)||($vval!='0'&&$vval!='')){
                                if(is_numeric($vval)&&$vval<0){
                                    $vval=0;
                                }
                                $strTR.="<tr><td>$vkey</td>";
                                $strTR.="<td>$vval</td></tr>";
                            }
                        }
                    }
                    $str.="<table border='1' cellspacing='1' cellpadding='1'>$strTR</table>";
                    $this->model_send_email("工资信息",$str,$val["邮箱"],true,true);
                }
                 echo '1';
            }else{
                $xls->addArray($data);
                $xls->generateXML('salary'.'-'.$syear.'-'.$smon);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /**
     * 发送外派工资条
     */
    function model_hr_email_exp(){
        set_time_limit(600);
        $seapy=$_POST['seapy'];
        $seapm=$_POST['seapm'];
        $seaty=$_POST['seaty'];
        if($seaty=='ymd'){
            $sql="select
                s.username  , s.olddept  , s.amount , s.email 
            from salary_flow f
                left join salary s on (f.userid=s.userid)
            where
                f.createuser='".$_SESSION['USER_ID']."'
                and f.flowname in ( '年度调薪' ) 
                and f.pyear='2012'
                and f.sta='2'  
            group by s.userid 
            order by s.username desc";
            $query=$this->db->query($sql);
            $data=array();
            while($row=$this->db->fetch_array($query)){
                $data[]=array(
                    'name'=>$row["username"],
                    'dept'=>$row["olddept"],
                    'amount'=>$this->salaryClass->decryptDeal($row["amount"]),
                    'email'=>$row["email"]
                    );
            }
            foreach($data as $val){
                $str='';
                if(!empty($val)){
                    $str=<<<EOD
<div style='font-size:10pt;'>
{$val['dept']}  {$val['name']}<br/>
<br/>
&nbsp;&nbsp;&nbsp;&nbsp;2012年非管理人员年度调薪工作已结束，本着市场化原则及与公司发展相适应的原则，公司根据不同员工的工作职责、任职条件、市场薪酬数据及上年度绩效考核结果，对部分岗位的基本工资做了不同程度的调整。<br/><br/>
&nbsp;&nbsp;&nbsp;&nbsp;从2012年4月起，您的基本工资调整为：{$val['amount']}<br/><br/>
&nbsp;&nbsp;&nbsp;&nbsp;请严格保密个人工资信息，并严禁打探或透露公司任何人的工资信息，违者以解除劳动合同处理。<br/><br/>
&nbsp;&nbsp;&nbsp;&nbsp;有疑问之处，仅限于向部门总监或人力资源部总监咨询。<br/>
<br/>
&nbsp;&nbsp;&nbsp;&nbsp;以上内容，敬请知悉。谢谢<br/><br/>
<br/>                                                                           
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;珠海世纪鼎利通信科技股份有限公司<br/><br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2012-4-28<br/>
</div>
EOD;
                    //$this->model_send_email("年度调薪通知",$str, $val['email'],true,true);
                }
            }
        }elseif(!empty($seapy)&&!empty($seapm)){
            $sql="select 
                    s.id , p.id as pid , s.userid , u.user_name as username , s.oldname
                    , s.accbank as bank, s.comedt , d.dept_name , if(p.salarydept is null , s.olddept , p.salarydept ) as olddept
                    , s.oldarea , s.email , p.area , p.pyear ,p.pmon , p.baseam
                    , p.basenowam , p.floatam , p.proam , p.otheram , p.bonusam
                    , p.perholsdays , p.sickholsdays, p.holsdelam , p.totalam
                    , p.gjjam , p.shbam , p.cessebase , p.paycesse , p.paytotal
                    , p.othdelam , s.acc , h.address , s.sta ,s.idcard , h.card_no
                    , p.sperewam , p.cogjjam ,p.coshbam ,p.prepaream ,p.handicapam
                    , p.manageam , p.sdyam , p.spedelam
                    , h.usercard , h.expflag , h.userlevel , p.remark
                from
                    salary_pay p
                    left join salary s on ( s.userid=p.userid )
                    left join hrms h on (s.userid=h.user_id)
                    left join department d on (s.deptid=d.dept_id)
                    left join user u on (s.userid=u.user_id )
                where s.userid=p.userid  and p.leaveflag='0' and h.expflag='1'
                    and p.pyear='".$seapy."' and p.pmon='".$seapm."' group by p.id order by s.id ";
            $query=$this->db->query($sql);
            $data=array();
            $mastoData=array('发薪时间','姓名','员工号','基本工资','总工资合计','社保费','公积金','交纳税金','实发工资');
            while($row=$this->db->fetch_array($query)){
                $data[]=array(
                    '发薪时间'=>$row["pyear"]."-".$row["pmon"],
                    '姓名'=>$row["oldname"],
                    '员工号'=>$row["usercard"],
                    '基本工资'=>$this->salaryClass->decryptDeal($row["baseam"]),
                    '当月入职工资'=>$this->salaryClass->decryptDeal($row["basenowam"]),
                    '浮动工资'=>$this->salaryClass->decryptDeal($row["floatam"]),
                    '项目奖金'=>$this->salaryClass->decryptDeal($row["proam"]),
                    '餐补'=>$this->salaryClass->decryptDeal($row["sdyam"]),
                    '其他补贴'=>$this->salaryClass->decryptDeal($row["otheram"]),
                    '奖金'=>$this->salaryClass->decryptDeal($row["bonusam"]),
                    '特别奖励'=>$this->salaryClass->decryptDeal($row["sperewam"]),
                    '特别扣除'=>$this->salaryClass->decryptDeal($row["spedelam"]),
                    '事假'=>$row["perholsdays"],'病假'=>$row["sickholsdays"],
                    '扣请假工资'=>$this->salaryClass->decryptDeal($row["holsdelam"]),
                    '总工资合计'=>$this->salaryClass->decryptDeal($row["totalam"]),
                    '社保费'=>$this->salaryClass->decryptDeal($row["shbam"]),
                    '公积金'=>$this->salaryClass->decryptDeal($row["gjjam"]),
                    '交纳税金'=>$this->salaryClass->decryptDeal($row["paycesse"]),
                    '实发工资'=>$this->salaryClass->decryptDeal($row["paytotal"]),
                    '备注'=>$row["remark"],
                    '邮箱'=>$row["email"]
                    );
            }
            $csd=false;
            foreach($data as $val){
                $str="您好！您的工资信息如下，请知晓。<br/>";
                $strTR="";
                foreach ($val as $vkey=>$vval){
                    if($vkey!='邮箱'){
                        if(in_array($vkey,$mastoData)||($vval!='0'&&$vval!='')){
                            if(is_numeric($vval)&&$vval<0){
                                $vval=0;
                            }
                            $strTR.="<tr><td>$vkey</td>";
                            $strTR.="<td>$vval</td></tr>";
                        }
                    }
                }
                $str.="<table border='1' cellspacing='1' cellpadding='1'>$strTR</table>";
                $this->model_send_email("工资信息",$str,$val["邮箱"],true,true);
            }
        }
         echo '1';
    }
    /**
     * 补贴
     */
    function model_dp_sdy_new_in(){
        $id = $_POST['id'];
        $sub= $_POST['sub'];
        $meal=$_POST['meal'];
        $other=$_POST['other'];
        $remark=$_POST['remark'];
        $sm=false;
        try {
            $this->db->query("START TRANSACTION");
            if($sub=='new'){
                if(!$id||!trim($id)){
                    throw new Exception('no user id');
                }
                if(!$_SESSION['USER_ID']){
                    throw new Exception('time out');
                }
                $tmpua=explode(',', $id);
                $sql="select p.id , p.userid , h.userlevel , p.sdyam , p.otheram 
                        from
                            salary_pay p 
                            left join hrms h on (p.userid=h.user_id)
                        where
                            p.pyear='".$this->nowy."' and p.pmon='".$this->nowm."'
                            and p.userid in ('".implode("','", $tmpua)."')";
                $query=$this->db->query($sql);
                $tmpua=array();
                while($row=$this->db->fetch_array($query)){
                    $tmpua[$row['userid']]['pid']=$row['id'];
                    $tmpua[$row['userid']]['sdyam']=$this->salaryClass->decryptDeal($row['sdyam']);
                    $tmpua[$row['userid']]['otheram']=$this->salaryClass->decryptDeal($row['otheram']);
                }
                if(!empty($tmpua)){
                    foreach($tmpua as $key=>$val){
                        if(empty($val)||empty($key)){
                            continue;
                        }
                        $sql="insert into salary_sdy
                                (userid , sdymeal , sdyother
                                    , creator , createdt
                                    , pyear , pmon , remark , rand_key )
                              values
                                ('".$key."','".$this->salaryClass->encryptDeal($meal) ."','".$this->salaryClass->encryptDeal($other) ."'
                                    , '".$_SESSION['USER_ID']."' , now()
                                    , '".$this->nowy."', '".$this->nowm."' , '".$remark."' ,'".get_rand_key()."')  ";
                        $this->db->query_exc($sql);
                        $this->model_pay_update($val['pid'],
                            array('sdyam'=>ceil($meal+$val['sdyam']),'otheram'=>ceil($other+$val['otheram']),'remark'=>$remark)
                            ,array(2)
                            );
                        $this->model_pay_stat($val['pid']);
                    }
                }
            }elseif($sub=='edit'){
                $sql="select s.userid , p.id , s.sdymeal , s.sdyother , p.sdyam , p.otheram
                    from salary_sdy s
                        left join salary_pay p on ( s.userid=p.userid and s.pyear=p.pyear and s.pmon=p.pmon )
                    where s.rand_key='".$id."' ";
                $res=$this->db->get_one($sql);
                if(empty($res)||!$res['userid']||!$res['id']){
                    throw new Exception('Data error');
                }
                $oldMeal=$this->salaryClass->decryptDeal($res['sdymeal']);
                $oldOther=$this->salaryClass->decryptDeal($res['sdyother']);
                $paySdy=$this->salaryClass->decryptDeal($res['sdyam']);
                $payOther=$this->salaryClass->decryptDeal($res['otheram']);
                $sql="update salary_sdy
                        set sdymeal='".$this->salaryClass->encryptDeal($meal)."'
                            , sdyother='".$this->salaryClass->encryptDeal($other)."'
                            , createdt=now()
                            , remark ='".$remark."'
                        where rand_key='".$id."' ";
                $this->db->query_exc($sql);
                $this->model_pay_update($res['id'],
                    array('sdyam'=>ceil($paySdy-$oldMeal+$meal),'otheram'=>ceil($payOther-$oldOther+$other),'remark'=>$remark)
                    ,array(2)
                    );
                $this->model_pay_stat($res['id']);
            }elseif($sub=='del'){
                $sql="select s.userid , p.id , s.sdymeal , s.sdyother , p.sdyam , p.otheram
                    from salary_sdy s
                        left join salary_pay p on ( s.userid=p.userid and s.pyear=p.pyear and s.pmon=p.pmon )
                    where s.rand_key='".$id."' ";
                $res=$this->db->get_one($sql);
                if(empty($res)||!$res['userid']||!$res['id']){
                    throw new Exception('Data error');
                }
                $oldMeal=$this->salaryClass->decryptDeal($res['sdymeal']);
                $oldOther=$this->salaryClass->decryptDeal($res['sdyother']);
                $paySdy=$this->salaryClass->decryptDeal($res['sdyam']);
                $payOther=$this->salaryClass->decryptDeal($res['otheram']);
                
                $sql="delete from  salary_sdy where rand_key='".$id."' ";
                $this->db->query_exc($sql);
                $this->model_pay_update($res['id'],
                    array('sdyam'=>ceil($paySdy-$oldMeal),'otheram'=>ceil($payOther-$oldOther),'remark'=>$remark)
                    ,array(2)
                    );
                $this->model_pay_stat($res['id']);
            }elseif($sub=='xls'){
                $temparr=array();
                $sql="select
                        s.userid , p.id , s.sdymeal , s.sdyother , s.rand_key , p.sdyam , p.otheram
                    from salary_sdy s
                        left join salary_pay p on ( s.userid=p.userid and s.pyear=p.pyear and s.pmon=p.pmon )
                    where
                        s.creator='".$_SESSION['USER_ID']."'
                        and s.pyear='".$this->nowy."' and s.pmon='".$this->nowm."'
                        and s.staflag='1' ";
                $query=$this->db->query($sql);
                while($row=$this->db->fetch_array($query)){
                   $temparr[$row['rand_key']]['userid']=$row['userid'];
                   $temparr[$row['rand_key']]['id']=$row['id'];
                   $temparr[$row['rand_key']]['sdymeal']=$this->salaryClass->decryptDeal($row['sdymeal']);
                   $temparr[$row['rand_key']]['sdyother']=$this->salaryClass->decryptDeal($row['sdyother']);
                   $temparr[$row['rand_key']]['sdyam']=$this->salaryClass->decryptDeal($row['sdyam']);
                   $temparr[$row['rand_key']]['otheram']=$this->salaryClass->decryptDeal($row['otheram']);
                }
                if(!empty($temparr)){
                    foreach($temparr as $key=>$val){
                        if(empty($val)||empty($key)){
                            continue;
                        }
                        $sql="update salary_sdy set staflag='0' where rand_key='".$key."'";
                        $this->db->query_exc($sql);
                        $this->model_pay_update($val['id'],
                            array('sdyam'=>ceil($val['sdymeal']+$val['sdyam']),'otheram'=>ceil($val['sdyother']+$val['otheram']),'remark'=>$remark)
                            ,array(2)
                            );
                        $this->model_pay_stat($val['id']);
                    }
                }
            }
            $this->db->query("COMMIT");
            $responce->id = $id;
            $this->globalUtil->insertOperateLog('工资管理', $id, '补贴', '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('工资管理', $id, '补贴', '失败', $e->getMessage());
        }
        return $responce;
    }
    /**
     * 补贴列表
     */
    function model_dp_sdy_list(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $schOper = $_GET['searchOper'];
        $schStr = $_GET['searchString'];
        $schField = $_GET['searchField'];
        $sqlSch = '';
        if ($schOper) {
            $sqlSch = jqgrid_sopt($schField, $schStr, $schOper);
        }
        $start = $limit * $page - $limit;
        //总数
        $sql = "select count(*)
            from salary_sdy y
                left join user u on (y.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where
                y.staflag='0' and y.flaflag='0' and  y.creator='".$_SESSION['USER_ID']."' ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(*)'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages){
            $page = $total_pages;
        }
        $i = 0;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sql="select
                y.rand_key , u.user_name  , d.dept_name , y.sdymeal , y.sdyother ,  y.pyear , y.pmon , y.createdt ,y.remark
            from salary_sdy y
                left join user u on (y.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where
                y.staflag='0' and y.flaflag='0' and y.creator='".$_SESSION['USER_ID']."'
            order by y.pyear desc , y.pmon desc , y.id desc , $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            if($row['pyear']==$this->nowy&&$row['pmon']==$this->nowm){
                $sta='0';
            }else{
                $sta='1';
            }
            $responce->rows[$i]['id'] = $row['rand_key'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array("", $row['rand_key']
                                , $row['user_name']
                                , $row['dept_name']
                                , $this->salaryClass->decryptDeal($row['sdymeal'])
                                , $this->salaryClass->decryptDeal($row['sdyother'])
                                , $row['pyear'].'-'.$row['pmon']
                                , $row['remark']
                                , $row['createdt']
                                , $sta
                            )
            );
            $i++;
        }
        return $responce;
    }
    /**
     *补贴数据
     * @return string $flaflag 1 人事申请； 0 部门申请
     */
    function model_dp_sdy_xls($flaflag='0'){
        set_time_limit(600);
        $type=$_POST['ctr_type'];
        $sqlCk=$type=='com'?'0':'1';
        $str='';
        $ckt=time();
        $infoE=array();
        try{
            $sql="delete from salary_sdy where staflag = '1' and creator='".$_SESSION['USER_ID']."' ";
            $this->db->query_exc($sql);
            $excelfilename='attachment/xls_model/dp_nym/'.$ckt.".xls";
            if(empty ($_FILES["ctr_file"]["tmp_name"])){
                $str='<tr><td colspan="5">请导入补贴数据表！</td></tr>';
            }elseif (!move_uploaded_file( $_FILES["ctr_file"]["tmp_name"], $excelfilename) ){
                $str='<tr><td colspan="5">上传失败！</td></tr>';
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
                if(!in_array('员工', $excelFields)||!in_array('员工号', $excelFields)
                        ||!in_array('节假日补贴', $excelFields)||!in_array('福利补贴', $excelFields)||!in_array('备注', $excelFields)){
                    throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
                }
                if(count($excelArr)){
                    foreach($excelArr['员工号'] as $key=>$val ){
                        $infoE[$val]['name']=$excelArr['员工'][$key];
                        $infoE[$val]['meal']=$excelArr['节假日补贴'][$key];
                        $infoE[$val]['other']=$excelArr['福利补贴'][$key];
                        $infoE[$val]['remark']=$excelArr['备注'][$key];
                    }
                }
                $sql="select
                        s.username , s.userid , h.usercard as idcard
                    from
                        salary s
                        left join hrms h on(s.userid=h.user_id)
                    where 1 ";
                $query=$this->db->query_exc($sql);
                while($row=$this->db->fetch_array($query)){
                    $infoA[]=$row['idcard'];
                    if(array_key_exists($row['idcard'],$infoE)){
                        $infoE[$row['idcard']]['type']=0;
                        $infoE[$row['idcard']]['userid']=$row['userid'];
                        $infoE[$row['idcard']]['name']=$row['username'];
                    }
                }
                if(count($infoE)){
                    foreach($infoE as $key=>$val){
                        if(!in_array($key,$infoA)){
                            $infoE[$row['idcard']]['type']=1;
                        }
                    }
                }
                $this->db->query("START TRANSACTION");
                if(count($infoE)){
                    foreach($infoE as $key=>$val){
                        if($val['type']=='0'){
                            $cl='green';
                        }elseif($val['type']=='1'){
                            $cl='#FF9900';
                        }
                        $str.='<tr style="color:'.$cl.'">
                            <td>'.$key.'</td>
                            <td>'.$val['name'].'</td>
                            <td>'.$val['meal'].'</td>
                            <td>'.$val['other'].'</td>
                            <td>'.$val['remark'].'</td>
                            </tr>';
                        if($val['type']=='0'&&$key){
                           if($flaflag=='1'){
                               $sql="insert into salary_sdy
                                        ( userid , sdymeal , sdyother
                                            , creator , createdt
                                            , pyear , pmon , remark , rand_key , staflag , flaflag )
                                      values
                                        ('".$val['userid']."','".$this->salaryClass->encryptDeal($val['meal']) ."','".$this->salaryClass->encryptDeal($val['other']) ."'
                                            , '".$_SESSION['USER_ID']."' , now()
                                            , '".$this->nowy."', '".$this->nowm."' , '".$val['remark']."' ,'".get_rand_key()."' ,'1' , '1' )  ";
                               $this->db->query_exc($sql);
                           }else{
                               $sql="insert into salary_sdy
                                        ( userid , sdymeal , sdyother
                                            , creator , createdt
                                            , pyear , pmon , remark , rand_key , staflag )
                                      values
                                        ('".$val['userid']."','".$this->salaryClass->encryptDeal($val['meal']) ."','".$this->salaryClass->encryptDeal($val['other']) ."'
                                            , '".$_SESSION['USER_ID']."' , now()
                                            , '".$this->nowy."', '".$this->nowm."' , '".$val['remark']."' ,'".get_rand_key()."' ,'1' )  ";
                               $this->db->query_exc($sql);
                           }
                        }
                    }
                }
                $this->db->query("COMMIT");
            }
        }catch(Exception $e){
            $this->db->query("ROLLBACK");
            $str='<tr><td colspan="5">导入数据错误！'.$e->getMessage().'</td></tr>';
        }
        return $str;
    }
//基础处理函数
    /**
     * 工资基础数据修改
     * @param <type> $k 修改对应的rand_key
     * @param <type> $v 数组结构 key=字段名，val=数值
     * @param <type> $e 不做转换的数据
     */
    function model_salary_update($k, $v, $e=array()) {
        if (!is_array($v) || !$k) {
            throw new Exception('Transfer data error '.$k);
        }
        if (!$this->salaryClass->numberCheck($v)) {
            throw new Exception('salary update db has non-num');
        }
        $v = $this->salaryClass->dataCript($v, 'encode', $e);
        $sqlt = '';
        foreach ($v as $key => $val) {
            if ($val == 'now()') {
                $sqlt.="$key=$val ,";
            } elseif(is_null($val)) {
                $sqlt.="$key = null ,";
            } else {
                $sqlt.="$key='$val' ,";
            }
        }
        $sqlt = trim($sqlt, ',');
        if (!$sqlt) {
            throw new Exception('salary update db error');
        }
        $sql = "update salary
            set $sqlt
            where rand_key='$k' ";
        $this->db->query_exc($sql);
    }

    /**
     ** 工资实时数据修改
     * @param <type> $k salary_pay的id
     * @param <type> $v 数组结构 key=字段名，val=数值
     * @param array $e 过滤加密
     * @param type $sqltabcom 公司数据库
     * @param type $mkflag 备注调整 true 叠加 false 直接代替
     * @throws Exception 
     */
    function model_pay_update($k, $v, $e=array(),$sqltabcom='',$mkflag=true) {
        //echo $sqltabcom;
        if(empty($e)){
            $e=array();
        }
        if (!is_array($v) || !$k) {
            throw new Exception('Transfer Data Error '.$k);
        }
        if (!$this->salaryClass->numberCheck($v)) {
            throw new Exception('salary update db has non-num');
        }
        $sqlt = '';
        $tmpv=$v;
        $v = $this->salaryClass->dataCript($v, 'encode', $e);
        foreach ($v as $key => $val) {
            if ($val == 'now()') {
                $sqlt.="$key=$val ,";
            }elseif($key=='remark'&&$mkflag){
                $sqlt.=" remark =CONCAT_WS(' | ',remark , '".$tmpv['remark']."' ) ,";
            }elseif(is_null($val)) {
                $sqlt.="$key = null ,";
            }else {
                $sqlt.="$key='$val' ,";
            }
        }
        $sqlt = trim($sqlt, ',');
        if (!$sqlt) {
            throw new Exception('salary update db error');
        }
        $sql = "update ".$sqltabcom."salary_pay
            set $sqlt
            where id='$k' ";
        $this->db->query_exc($sql);
    }

    /**
     * 计算工资数据，并更新信息。
     * @param <type> $k salary 对应ID
     */
    function model_pay_stat($k,$sqltab='') {
        $sql = "select
                baseam , basenowam ,floatam ,proam 
                ,otheram ,bonusam ,holsdelam
                ,othdelam , shbam ,gjjam ,sperewam ,spedelam
                ,sdyam
                ,cessebase
                ,perholsdays ,sickholsdays
                ,accrewam ,accdelam
                , pyear , pmon 
            from ".$sqltab."salary_pay
            where id='$k'";
        $query = $this->db->get_one($sql);
        $res = $this->salaryClass->dataCript($query, 'decode');
        //取整去掉 20130628 正规的处理方式 
        $ckpaydt=date('Y-m-d',strtotime($query['pyear'].'-'.$query['pmon'].'-01'));
        $holsdelAm = $this->salaryClass->holsDeal($query['perholsdays'], $query['sickholsdays'], $res['baseam'],$ckpaydt);
        $totalAm = 0;
        $payCesse = 0;
        $payTotal = 0;
        if ($res['basenowam'] == 0 || $res['basenowam'] == $this->zero || $res['basenowam'] == '') {
            $totalAm = round($res['baseam'] + $res['floatam'] + $res['proam'] + $res['sdyam'] + $res['otheram'] + $res['bonusam'] - $holsdelAm - $res['othdelam'] - $res['spedelam'] + $res['sperewam'], 2);
        } else {
            $totalAm = round($res['basenowam'] + $res['floatam'] + $res['proam'] + $res['sdyam'] + $res['otheram'] + $res['bonusam'] - $holsdelAm - $res['othdelam'] - $res['spedelam'] + $res['sperewam'], 2);
        }
        if (floatval($totalv) < 0) {
            throw new Exception('out limit');
        }
        $cesse = round($totalAm - $res['gjjam'] - $res['shbam'], 2);
        $payCesse = $this->salaryClass->cesseDeal($cesse, $query['cessebase']);
        $payTotal = $this->salaryClass->getFinanceValue($cesse - $payCesse + $res['accrewam'] - $res['accdelam']);
        $res = array('holsdelam' => $holsdelAm, 'totalam' => $totalAm, 'paycesse' => $payCesse, 'paytotal' => $payTotal);
        $this->model_pay_update($k, $res,'',$sqltab);
    }

    /**
     * 获取工资实时信息
     * @param <type> id
     * @param <type> 数组 val=字段名
     * @return <type>
     */
    function model_get_pay($k, $v,$com) {
        if(is_array($k)){
            foreach($k as $kkey=>$vval){
                $sql.=" and ".$kkey." = '".$vval."' ";
            }
            $sql = "select
                " . implode(' , ', $v) . "
                from ".$com."salary_pay where 1 ".$sql;
            
        }else{
            $sql = "select
                " . implode(' , ', $v) . "
                from ".$com."salary_pay where id='$k' ";
            
        }
        $res = $this->db->get_one($sql);
        return $res;
    }

    /**
     * 通过salary rand_key 获取pay id
     * @param <type> $k
     * @return <type>
     */
    function model_get_payid($k,$py='',$pm='') {
        if(empty($py)){
            $py=$this->nowy;
        }
        if(empty($pm)){
            $pm=$this->nowm;
        }
        $sql = "select p.id
                from
                    salary_pay p , salary s
                where
                    p.pyear='" . $py . "' and p.pmon='" . $pm . "' and p.userid=s.userid
                    and s.rand_key='$k' ";
        $res = $this->db->get_one($sql);
        return $res;
    }

    /**
     * 获取工资基础信息
     * @param <type> id
     * @param <type> 数组 val=字段名
     * @return <type>
     */
    function model_get_salary($k, $v) {
        $sql = "select
            " . implode(' , ', $v) . "
            from salary where rand_key='$k' ";
        $res = $this->db->get_one($sql);
        return $res;
    }
    function model_get_data(){
        $sql="select max(pyear) as mpy , max(pmon) as mpm from salary_pay where pyear='".date('Y')."' ";
//     	$sql="select max(pyear) as mpy , max(pmon) as mpm from salary_pay where pyear='2013' ";
        $res=$this->db->get_one($sql);
        if(!empty($res['mpy'])){
            $this->nowy=$res['mpy'];
        }else{
            $this->nowy=date('Y');
        }
        if(!empty($res['mpm'])){
            $this->nowm=$res['mpm'];
        }else{
            $this->nowm=date('m');
        }
        if($this->nowy<date('Y')){
            $this->nowy=date('Y');
        }
        if($this->nowm<date('m')){
            $this->nowm=date('m');
        }
    }
    /**
     * 初始化尚未建立本月工资信息的员工
     */
    function model_salary_ini($flag=false) {
        if($flag){
            $monthp = mktime(0, 0, 0, date("m"), date("d")+10,   date("Y"));
            $this->nowy=date('Y',$monthp);
            $this->nowm=date('m',$monthp);
        }
        //初始化本月新员工信息 增加 账号类型控制 userType  1为正式员工，2为临聘员工，3为测试员工
        $sql = "insert into
            salary
            ( userid , deptid , comedt , username , olddept 
            , acc , idcard , email , oldarea
            , amount , floatam , gjjam
            , shbam , cogjjam , coshbam
            , prepaream , handicapam
            , manageam , cessebase ,rand_key , oldname , usercom , jfcom )
        select
            h.user_id , u.dept_id , replace(h.come_date,'-','') as comedt  , u.user_name , d.dept_name
            , h.account , h.card_no , u.email , a.name as area
            , '" . $this->zero . "' , '" . $this->zero . "' , '" . $this->zero . "'
            , '" . $this->zero . "' , '" .$this->salaryClass->encryptDeal($this->salaryClass->coGjjBase) . "' 
            , '" . $this->salaryClass->encryptDeal($this->salaryClass->coShbBase) . "'
            , '" . $this->salaryClass->encryptDeal($this->salaryClass->prepareAm) . "'
            , '" . $this->salaryClass->encryptDeal($this->salaryClass->handicapAm) . "'
            , '" . $this->salaryClass->encryptDeal($this->salaryClass->manageAm) . "'
            , '" . $this->salaryClass->cesseProvideBase . "'
            , md5(concat('" . get_rand_key() . "',rand()))
            , u.user_name, u.company as usercom , u.company as jfcom
        from
            hrms h
            left join user u on (h.user_id=u.user_id)
            left join area a on (u.area=a.id)
            left join department d on (u.dept_id=d.dept_id)
            left join oa_hr_personnel oah on (h.user_id=oah.userAccount)
        where
            (  (u.del='0' and u.has_left='0'  )  or ( to_days(h.come_date) >=to_days('" . $this->nowy . "-" . $this->nowm . "-1')  ) )  
            and u.usertype='1' and u.company<>'bx'
            and h.user_id not in (select userid from salary )
            and oah.personnelType not in ( 'SXSDT' ) ";
        try {
            $this->db->query_exc($sql);
        } catch (Exception $e) {
            $this->globalUtil->insertOperateLog('工资信息', 'salary', '初始化本月新员工工资信息', '失败', $e->getMessage());
            echo '本月新员工数据初始失败，请联系管理员。';
            exit();
        }
        //季度项目奖 取消 5-29 xgq
//        if($this->nowm==1||$this->nowm==4||$this->nowm==7||$this->nowm==10){
//            $sql="update salary set floatam='".$this->zero."' , floatflag='1' where floatflag='0' ";
//            $this->db->query_exc($sql);
//        }else{
//            $sql="update salary set floatflag='0' where floatflag='1' ";
//            $this->db->query_exc($sql);
//        }
        //更新部门信息
        $sql="update
                salary s
                left join user u on (s.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id )
                left join area a on (u.area=a.id )
                left join salary_pay p on (s.userid=p.userid and pyear='" . $this->nowy . "'
                        and pmon='" . $this->nowm . "' )
            set  s.olddept = d.dept_name , s.deptid=u.dept_id
                , p.salarydept = d.dept_name , p.deptid=u.dept_id
                , s.oldarea= CONVERT(a.name USING 'gbk') , p.area= CONVERT(a.name USING 'gbk') 
            where
                s.userid=u.user_id and s.userid not in ('bin.chang')
                and u.dept_id=d.dept_id
                and ( s.deptid!=u.dept_id or s.oldarea != CONVERT(a.name USING 'gbk') )";
        $this->db->query_exc($sql);
        //更新其他公司部门
        $sql="update 
            `shiyuanoa`.salary_pay p 
            left join salary s on (p.userid=s.userid)
            set p.salarydept = s.olddept  , p.deptid = s.deptid , p.area=s.oldarea 
            where ( p.deptid != s.deptid  or s.oldarea != p.area )
            and pyear='" . $this->nowy . "'  and pmon='" . $this->nowm . "' ";
        $this->db->query_exc($sql);
        $sql="update 
            `beiruanoa`.salary_pay p 
            left join salary s on (p.userid=s.userid)
            set p.salarydept = s.olddept  , p.deptid = s.deptid , p.area=s.oldarea 
            where ( p.deptid != s.deptid  or s.oldarea != p.area )
            and pyear='" . $this->nowy . "'  and pmon='" . $this->nowm . "' ";
        $this->db->query_exc($sql);
        //外派状态调整：
        $sql="update salary_pay p 
            left join hrms h on (p.userid=h.user_id)
            set p.expflag=h.expflag
            where pyear='" . $this->nowy . "' and pmon='" . $this->nowm . "' and p.expflag<>h.expflag";
        $this->db->query_exc($sql);
        //公司属性 调整
        $comup=array();
        $sql="select s.userid , s.usercom , u.company  from salary s 
            left join user u on (s.userid=u.user_id)
            where s.userid=u.user_id and s.usercom <> u.company and s.usersta<>3 ";
        $comup=$this->db->getArray($sql);
        if(!empty($comup)){
            foreach($comup as $key=>$val){
                try {
                    $this->db->query("START TRANSACTION");
                    $sql="insert into 
                            ".$this->get_com_sql($val['company'])."salary_pay 
                            ( `UserId`,  `DeptId`,  `SalaryDept`,  `Area`,  `BaseAm`,  `BaseNowAm`,  `FloatAm`,  `ProAm`,
                            `SdyAm`,  `OtherAm`,  `BonusAm`,  `HolsDelAm`,  `PerHolsDays`,  `SickHolsDays`,
                            `OthDelAm`,  `TotalAm`,  `ShbAm`,  `GjjAm`,  `SpeRewAm`,  `SpeDelAm`,  `CesseAm`,
                            `CesseBase`,  `PayCesse`,  `PayTotal`,  `PYear`,  `PMon`,  `Sta`,  `HrmsDT`,  `CreateDT`,
                            `DeptExaUser`,  `DeptExaNot`,  `DeptExaDT`,  `RehearUser`,  `RehearNot`,  `RehearDT`,
                            `StatUser`,  `StatDT`,  `EmailFlag`,  `Remark`,  `SpeRewRem`,  `NewFlag`,  `CoGjjAm`,
                            `CoShbAm`,  `PrepareAm`,  `HandicapAm`,  `ManageAm`,  `LeaveFlag`,  `NowAmFlag`,
                            `rand_key`,  `chgflag`,  `AccRewAm`,  `AccDelAm`,  `ExpFlag`,
                            `hdar`,  `bnar`,  `srar`,  `shbr`,  `gjjr`,  `sdar`,  `arar`,  `pcr`,  `wdt`,  `wdtr`,
                            `OtherAccRewAm`,  `AccRewAmCes`,  `oarar`,  `usercom` )
                            select `UserId`,  `DeptId`,  `SalaryDept`,  `Area`,  `BaseAm`,  `BaseNowAm`,  `FloatAm`,  `ProAm`,
                            `SdyAm`,  `OtherAm`,  `BonusAm`,  `HolsDelAm`,  `PerHolsDays`,  `SickHolsDays`,
                            `OthDelAm`,  `TotalAm`,  `ShbAm`,  `GjjAm`,  `SpeRewAm`,  `SpeDelAm`,  `CesseAm`,
                            `CesseBase`,  `PayCesse`,  `PayTotal`,  `PYear`,  `PMon`,  `Sta`,  `HrmsDT`,  `CreateDT`,
                            `DeptExaUser`,  `DeptExaNot`,  `DeptExaDT`,  `RehearUser`,  `RehearNot`,  `RehearDT`,
                            `StatUser`,  `StatDT`,  `EmailFlag`,  `Remark`,  `SpeRewRem`,  `NewFlag`,  `CoGjjAm`,
                            `CoShbAm`,  `PrepareAm`,  `HandicapAm`,  `ManageAm`,  `LeaveFlag`,  `NowAmFlag`,
                            `rand_key`,  `chgflag`,  `AccRewAm`,  `AccDelAm`,  `ExpFlag`,
                            `hdar`,  `bnar`,  `srar`,  `shbr`,  `gjjr`,  `sdar`,  `arar`,  `pcr`,  `wdt`,  `wdtr`,
                            `OtherAccRewAm`,  `AccRewAmCes`,  `oarar`,   '".$val['company']."' as usercom
                            from ".$this->get_com_sql($val['usercom'])."salary_pay 
                                where userid='".$val['userid']."' and pyear='" . $this->nowy . "' and pmon='" . $this->nowm . "' ";
                    $this->db->query_exc($sql);
                    $sql=" delete  from ".$this->get_com_sql($val['usercom'])."salary_pay 
                                where userid='".$val['userid']."' and pyear='" . $this->nowy . "' and pmon='" . $this->nowm . "' ";
                    $this->db->query_exc($sql);
                    $sql="update salary s 
                            left join user u on (s.userid=u.user_id)
                            set s.usercom = u.company
                            where s.userid=u.user_id and s.usercom <> u.company and s.userid='".$val['userid']."' ";
                    $this->db->query_exc($sql);
                    $this->db->query("COMMIT");
                } catch (Exception $e) {
                    $this->db->query("ROLLBACK");
                }
            }
        }
        //初始化本月工资信息
        //添加公司初始语句
        $sqlcom = " and s.userid not in
                    (select userid 
                    from salary_pay
                    where pyear='" . $this->nowy . "'
                        and pmon='" . $this->nowm . "') ";
        if(!empty($this->salarySql)){
            foreach($this->salarySql as $val){
                $sqlcom .= " and s.userid not in
                    (select userid 
                    from `".$val."`.salary_pay
                    where pyear='" . $this->nowy . "'
                        and pmon='" . $this->nowm . "') ";
            }
        }
        
        $sql = "select
                s.userid ,u.dept_id , s.oldarea as area
                ,s.amount , s.floatam 
                ,s.gjjam , s.shbam , s.newflag , s.cogjjam , s.coshbam
                ,s.prepaream , s.handicapam , s.manageam , s.cessebase
                ,s.olddept , s.usersta , u.company , u.salarycom
                ,h.expflag , date_format(leavedt , '%Y%m') as ckldt
            from salary s
                left join hrms h on (s.userid=h.user_id)
                left join user u on (s.userid=u.user_id)
                left join area a on (u.area=a.id)
            where
                (s.usersta!='3' 
                    or 
                      ( date_format(leavedt , '%Y%m')>=".date('Ym',  strtotime($this->nowy.'-'.$this->nowm.'-1'))." 
                        and s.usersta=3 )
                ) and u.company <>'bx'
                and u.user_id is not null
                $sqlcom ";
        $query = $this->db->query($sql);
        try {
            $this->db->query("START TRANSACTION");
            $updataarr=array();
            while (($row = $this->db->fetch_array($query)) != false) {
                $updataarr[]=$row;
            }
            if(!empty($updataarr)){
                foreach ($updataarr as $row) {
                    //公司员工添加数据库名
                    $comtable=" ";
                    if(!empty($row['salarycom'])){
                        $comtable=" `".$this->salarySql[$row['salarycom']]."`.";
                    }elseif(!empty($row['company'])){
                        $comtable=" `".$this->salarySql[$row['company']]."`.";
                    }
                    //离职
                    if($row['usersta']==3&&$row['ckldt']==date('Ym',  strtotime($this->nowy.'-'.$this->nowm.'-1'))){
                        $nowamflag=3;//当月离职状态
                    }else{
                        $nowamflag=0;
                    }
                    //入职
                    $leaveflag=$row['usersta']=='0'?'1':'0';
                    $chk = true;
                    $baseAm = $this->salaryClass->decryptDeal($row['amount']);
                    $gjjAm = $this->salaryClass->decryptDeal($row['gjjam']);
                    $shbAm = $this->salaryClass->decryptDeal($row['shbam']);
                    $floatAm = $this->salaryClass->decryptDeal($row['floatam']);
                    $totalAm = $this->salaryClass->cfv($baseAm + $floatAm);
                    $cesseAm = $this->salaryClass->cfv($totalAm - $gjjAm - $shbAm);
                    $payCesse = $this->salaryClass->cesseDeal($cesseAm, $row['cessebase']);
                    $payTotal = $this->salaryClass->cfv($cesseAm - $payCesse);
                    $sql = "insert into
                            ".$comtable."salary_pay
                        set
                            userid='" . $row['userid'] . "'
                            , deptid='" . $row['dept_id'] . "'
                            , area='" . $row['area'] . "'
                            , salarydept='" . $row['olddept'] . "'
                            , pyear='" . $this->nowy . "'
                            , pmon='" . $this->nowm . "'
                            , baseam='" . $row['amount'] . "'
                            , floatam='" . $row['floatam'] . "'
                            , gjjam='" . $row['gjjam'] . "'
                            , shbam='" . $row['shbam'] . "'
                            , totalam='" . $this->salaryClass->encryptDeal($totalAm) . "'
                            , cesseam='" . $this->salaryClass->encryptDeal($cesseAm) . "'
                            , paycesse='" . $this->salaryClass->encryptDeal($payCesse) . "'
                            , paytotal='" . $this->salaryClass->encryptDeal($payTotal) . "'
                            , cogjjam='" . $row['cogjjam'] . "'
                            , coshbam='" . $row['coshbam'] . "'
                            , prepaream='" . $row['prepaream'] . "'
                            , handicapam='" . $row['handicapam'] . "'
                            , manageam='" . $row['manageam'] . "'
                            , cessebase='".$row['cessebase']."'
                            , createdt=now()
                            , leaveflag='".$leaveflag."'
                            , expflag='".$row['expflag']."' 
                            , nowamflag='".$nowamflag."' ";
                    $this->db->query_exc($sql);
                }
            }
            $this->db->query("COMMIT");
            if ($chk) {
                $this->globalUtil->insertOperateLog('工资信息', 'salary_pay', '初始化本月工资信息', '成功');
            }
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->globalUtil->insertOperateLog('工资信息', 'salary_pay', '初始化本月工资信息', '失败', $e->getMessage());
            echo '本月数据初始失败，请联系管理员。';
            exit();
        }
        //额外人员
        $sql="SELECT  s.usercom , s.jfcom , s.userid , s.deptid, s.olddept 
                , s.userid , s.oldarea as area
                ,s.amount , s.floatam 
                ,s.gjjam , s.shbam , s.newflag , s.cogjjam , s.coshbam
                ,s.prepaream , s.handicapam , s.manageam , s.cessebase
                ,s.olddept , s.usersta 
            FROM salary s where comflag='0' and s.usersta=2 ".$sqlcom;
        $query = $this->db->query($sql);
        try {
            $this->db->query("START TRANSACTION");
            $updataarr=array();
            while (($row = $this->db->fetch_array($query)) != false) {
                $updataarr[]=$row;
            }
            if(!empty($updataarr)){
                foreach ($updataarr as $row) {
                    //公司员工添加数据库名
                    $comtable=" `".$this->salarySql[$row['jfcom']]."`.";
                    $chk = true;
                    $baseAm = $this->salaryClass->decryptDeal($row['amount']);
                    $gjjAm = $this->salaryClass->decryptDeal($row['gjjam']);
                    $shbAm = $this->salaryClass->decryptDeal($row['shbam']);
                    $floatAm = $this->salaryClass->decryptDeal($row['floatam']);
                    $totalAm = $this->salaryClass->cfv($baseAm + $floatAm);
                    $cesseAm = $this->salaryClass->cfv($totalAm - $gjjAm - $shbAm);
                    $payCesse = $this->salaryClass->cesseDeal($cesseAm, $row['cessebase']);
                    $payTotal = $this->salaryClass->cfv($cesseAm - $payCesse);
                    $sql = "insert into
                            ".$comtable."salary_pay
                        set
                            userid='" . $row['userid'] . "'
                            , deptid='" . $row['deptid'] . "'
                            , area='" . $row['area'] . "'
                            , salarydept='" . $row['olddept'] . "'
                            , pyear='" . $this->nowy . "'
                            , pmon='" . $this->nowm . "'
                            , baseam='" . $row['amount'] . "'
                            , floatam='" . $row['floatam'] . "'
                            , gjjam='" . $row['gjjam'] . "'
                            , shbam='" . $row['shbam'] . "'
                            , totalam='" . $this->salaryClass->encryptDeal($totalAm) . "'
                            , cesseam='" . $this->salaryClass->encryptDeal($cesseAm) . "'
                            , paycesse='" . $this->salaryClass->encryptDeal($payCesse) . "'
                            , paytotal='" . $this->salaryClass->encryptDeal($payTotal) . "'
                            , cogjjam='" . $row['cogjjam'] . "'
                            , coshbam='" . $row['coshbam'] . "'
                            , prepaream='" . $row['prepaream'] . "'
                            , handicapam='" . $row['handicapam'] . "'
                            , manageam='" . $row['manageam'] . "'
                            , cessebase='".$row['cessebase']."'
                            , usercom='".$row['usercom']."'
                            , createdt=now() 
                            , comflag=0 ";
                    $this->db->query_exc($sql);
                }
            }
            $this->db->query("COMMIT");
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->globalUtil->insertOperateLog('工资信息', 'salary_pay', '初始化本额外人员工资信息', '失败', $e->getMessage());
            echo '初始化本额外人员工资信息失败，请联系管理员。';
            exit();
        }
    }
    /**
     * 临时更新税率问题2011-02-23
     */
    function model_cesse_chg(){
       return false;
       try{
           $this->db->query("START TRANSACTION");
           $chg=array();
           $sql="select id , totalam , gjjam , shbam , cessebase , paycesse , paytotal
               from salary_pay
               where pyear='".$this->nowy."'
                   and pmon='".$this->nowm."' ";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                $chg[$row['id']]['cessebase']=$row['cessebase'];
                $chg[$row['id']]['totalam']=$this->salaryClass->decryptDeal($row['totalam']);
                $chg[$row['id']]['gjjam']=$this->salaryClass->decryptDeal($row['gjjam']);
                $chg[$row['id']]['shbam']=$this->salaryClass->decryptDeal($row['shbam']);
                $chg[$row['id']]['paycesse']=$this->salaryClass->decryptDeal($row['paycesse']);
                $chg[$row['id']]['paytotal']=$this->salaryClass->decryptDeal($row['paytotal']);
            }
            $i='';
            if(!empty($chg)){
                foreach($chg as $key=>$val){
                    $cesse = round($val['totalam'] - $val['gjjam'] - $val['shbam'], 2);
                    $payCesse = $this->salaryClass->cesseDeal($cesse, $val['cessebase']);
                    $payTotal = $this->salaryClass->getFinanceValue($cesse - $payCesse);
                    if($payCesse!=$val['paycesse']||$payTotal!=$val['paytotal']){
                        $this->model_pay_update($key, array('paycesse' => $payCesse, 'paytotal' => $payTotal));
                        $i.=$key.'-'.$payCesse.'='.$val['paycesse'].','.$payTotal.'='.$val['paytotal'].'|';
                    }
                }
            }
           $this->db->query("COMMIT");
            $this->globalUtil->insertOperateLog('工资信息', '临时更新税率问题', '内容', '成功', $i.'good');
       }catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->globalUtil->insertOperateLog('工资信息', '临时更新税率问题', '内容', '失败', $i.$e->getMessage());
       }
    }
    /**
     * 临时更新季度奖，已不用
     */
    function model_float_chg(){
        //return false;
        try {
            $this->db->query("START TRANSACTION");
            $chg=array();
            $sql="SELECT p.userid  , p.floatam as pf , p1.floatam as pf1 , p.id as pid
                FROM salary_pay p
                    left join salary_pay p1 on (p.userid=p1.userid and p1.pmon='8' and p1.pyear='2011')
                where p.pmon='9'
                    and p.pyear='2011'
                    and p.floatam in ('0','mKyYBwAYs6OhZVIyCcao0A==')
                    and p1.floatam not in ('0','mKyYBwAYs6OhZVIyCcao0A==')
                    and p.chgflag='0' 
                    and p.userid not in ('xinping.gou','siyu.chen','honghui.liu','quanzhou.luo' )";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                $chg[$row['userid']]['pid']=$row['pid'];
                $chg[$row['userid']]['pf']=$this->salaryClass->decryptDeal($row['pf']);
                $chg[$row['userid']]['pf1']=$this->salaryClass->decryptDeal($row['pf1']);
            }
            if(!empty($chg)){
                foreach($chg as $key=>$val){
                    if($val['pf']=='0'&&$val['pf1']!='0'){
                        $payinfo=array('chgflag'=>'1','floatam'=>$val['pf1']);
                        $this->model_pay_update($val['pid'],$payinfo,array(0));
                        $this->model_pay_stat($val['pid']);
                    }
                }
            }
            $this->db->query("COMMIT");
            $this->globalUtil->insertOperateLog('工资信息', '浮动工资修改', '内容', '成功', 'good');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->globalUtil->insertOperateLog('工资信息', '浮动工资修改', '内容', '失败', $e->getMessage());
        }
    }
    /**
     * 工资发送邮件通知
     * @param <type> $title
     * @param <type> $body
     * @param <type> $emaildb add: 单个，数组 | user : xgq,xiaohuang,
     * @param <type> $flag 类型：true 地址名单；false 人员名单
     */
    function model_send_email($title, $body, $emaildb,$flag=true,$sflag=false) {
        if($sflag){
            try {
                if($flag){
                    $emailadd=$emaildb;
                }elseif($emaildb){
                    $emailadd=array();
                    $sql="select
                            u.email
                        from
                            user u
                        where
                            u.user_id in ('".str_replace(',', "','", trim($emaildb,','))."') ";
                    $query=$this->db->query($sql);
                    while($row=$this->db->fetch_array($query)){
                        $emailadd[]=$row['email'];
                    }
                }
                $this->emailClass ->send($title, $body, $emailadd);
            } catch (Exception $e) {
                $this->globalUtil->insertOperateLog('工资邮件', $emailadd, $body, '失败', $e->getMessage());
            }
        }
    }

    /**
     *
     * @param <array> $info 信息数组
     * array(
     *      flowname , userid , salarykey , changeam , remark 
     * );
     * @param <type> $flag
     * $rcov true 流程名称检查 false 工资key检查
     */
    function model_flow_new($info=array(), $flag=true , $rcov=true) {
        $resdb='';
        $info['proname']=$info['proname']?$info['proname']:'';
        $info['prono']=$info['prono']?$info['prono']:'';
        if ($flag) {
            //流程主体
            if($rcov){
                $sql = "select count(*) from salary_flow
                    where
                        flowname='".$info['flowname']."' and userid='".$info['userid']."'
                        and sta!=3 and sta!=2 ";
            }elseif($info['salarykey']){
                $sql = "select count(*) from salary_flow
                    where
                        salarykey='".$info['salarykey']."' ";
            }
            if($sql){
                $ckp=$this->db->get_one($sql);
                if($ckp['count(*)']>=1){
                    throw new Exception('Data has been generated');
                }
            }
            $sql = "insert into salary_flow
                ( flowid , flowname , userid , createdt , createuser
                , salarykey , changeam , remark , pyear , pmon
                , rand_key , proname , prono , changedt , remark_rea )
            select
                flow_id , flow_name  , '" . $info['userid'] . "' , now() , '" . $_SESSION['USER_ID'] . "'
                , '" . $info['salarykey'] . "' , '" . $info['changeam'] . "' , '" . $info['remark'] . "' , '" . $this->nowy . "', '" . $this->nowm . "'
                , md5(concat('" . get_rand_key() . "',rand())) , '" . $info['proname'] . "' , '" . $info['prono'] . "'
                , '" . $info['changedt'] . "' , '" . $info['remark_rea'] . "' 
            from
                flow_type
            where
                flow_name='" . $info['flowname'] . "' ";
            $this->db->query_exc($sql);
            $salaryfid = $this->db->insert_id();
            if(!$salaryfid){
                throw new Exception('no stat flow query');
            }
            //审批流
            $sql = "select
                p.id , p.prcs_name , p.prcs_id
                , p.prcs_user as pu , p.prcs_dept as pd
                , p.prcs_priv as pp , p.prcs_spec as ps
                , p.prcs_prop as ptype
            from
                flow_process p
                left join flow_type f on (p.flow_id=f.flow_id)
            where
                f.flow_name='" . $info['flowname'] . "'
                order by prcs_id ";
            if($info['remark']=='油卡扣除'){//油卡
                $sql = "select
                    p.id , p.prcs_name , p.prcs_id
                    , p.prcs_user as pu , p.prcs_dept as pd
                    , p.prcs_priv as pp , p.prcs_spec as ps
                    , p.prcs_prop as ptype
                from
                    flow_process p
                    left join flow_type f on (p.flow_id=f.flow_id)
                where
                    f.flow_name='" . $info['flowname'] . "'
                    order by prcs_id limit 0,1";
            }
            $query = $this->db->query_exc($sql);
            if(empty($query)){
                throw new Exception('no stat flow user');
            }
            $flow_step_id=1;
            $pass_step=false;
            while ($row = $this->db->fetch_array($query)) {
                $tmpUser = '';
                if ($row['pu']) {
                    $tmpUser.=$row['pu'];
                }
                $tmpUser = trim($tmpUser, ',');
                if ($row['pp']) {
                    $sql = "select user_id from user u where u.user_priv in ( " . trim($row['pp'], ',') . ") ";
                    $queryi = $this->db->query_exc($sql);
                    while ($rowi = $this->db->fetch_array($queryi)) {
                        $tmpUser.=',' . $rowi['user_id'];
                    }
                }
                $tmpUser = trim($tmpUser, ',');
                if ($row['ps']) {
                    $tmpPs = explode(',', $row['ps']);
                    if (!empty($tmpPs)) {
                        foreach ($tmpPs as $val) {
                            if ($val == '@bmzj') {
                                $sql = "select
                                    d.majorid , d.vicemanager
                                from user u
                                    left join department d on (u.dept_id=d.dept_id)
                                where
                                    u.user_id='" . $info['userid'] . "' ";
                                $resi = $this->db->get_one($sql);
                                if ($resi['majorid']) {
                                    $tmpUser = trim($tmpUser . $resi['majorid'], ',');
                                } else {
                                    if ($resi['vicemanager']) {
                                        $tmpUser = trim($tmpUser . $resi['vicemanager'], ',');
                                    } else {
                                        $sql = "select u.user_id
                                        from user u
                                            left join user_priv p on (u.user_priv=p.user_priv)
                                        where p.priv_name='总经理' ";
                                        $queryi = $this->db->query_exc($sql);
                                        while ($rowi = $this->db->fetch_array($queryi)) {
                                            $tmpUser.=',' . $rowi['user_id'];
                                        }
                                    }
                                }
                            }
                            if ($val == '@bmfz') {
                                $sql = "select
                                    d.vicemanager
                                from user u
                                    left join department d on (u.dept_id=d.dept_id)
                                where
                                    u.user_id='" . $info['userid'] . "' ";
                                $resi = $this->db->get_one($sql);
                                if ($resi['vicemanager']) {
                                    $tmpUser = trim($tmpUser . $resi['vicemanager'], ',');
                                } else {
                                    $sql = "select u.user_id
                                        from user u
                                            left join user_priv p on (u.user_priv=p.user_priv)
                                        where p.priv_name='总经理' ";
                                    $queryi = $this->db->query_exc($sql);
                                    while ($rowi = $this->db->fetch_array($queryi)) {
                                        $tmpUser.=',' . $rowi['user_id'];
                                    }
                                }
                            }
                        }
                    }
                }
                if (trim($tmpUser)) {//第一步
                    //读取授权人数据
                    $sql="SELECT group_concat( to_id)  as to_ids  , from_id   FROM power_set  
                        where to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and STATUS=1  
                        and find_in_set(FROM_ID,'".$tmpUser."')
                        group by FROM_ID";
                    $powserto=$this->db->get_one($sql);
                    $tmpUser.=empty($powserto['to_ids'])?'':','.$powserto['to_ids'];
                    $pos = stripos($tmpUser, $_SESSION['USER_ID']);
                    if($pos!==false){//过滤提交人审批
                        $pass_step=true;
                        continue;
                    }
                    if ($flow_step_id == 1) {
                        $resdb=$tmpUser;
                        $sql = "insert into salary_flow_step
                            (salaryfid , flowproid , item
                                , step , dealuser , rand_key , sta )
                            values
                            ('" . $salaryfid . "' , '" . $row['id'] . "' ,'" . $row['prcs_name'] . "'
                                , '" . $flow_step_id . "' , '" . $tmpUser . "' ,'" . get_rand_key() . "', 0 ) ";
                    } else {
                        $sql = "insert into salary_flow_step
                            (salaryfid , flowproid , item
                                , step , dealuser , rand_key )
                            values
                            ('" . $salaryfid . "' , '" . $row['id'] . "' ,'" . $row['prcs_name'] . "'
                                , '" . $flow_step_id . "' , '" . $tmpUser . "' ,'" . get_rand_key() . "' ) ";
                    }
                    $this->db->query_exc($sql);
                    $flow_step_id++;
                } else {
                    throw new Exception('no checker');
                }
            }
            if($flow_step_id==1&&$pass_step){
                $this->model_flow_pass($salaryfid);
            }
        } else {//没流程，只记录事件
            $sql = "insert into
                salary_flow
                ( flowname ,userid , createdt
                    , createuser , sta , salarykey
                    , changeam , finishdt , remark
                    , pyear , pmon  , rand_key
                )
            values
                ( '" . $info['flowname'] . "' ,'" . $info['userid'] . "' ,now()
                    , '" . $_SESSION['USER_ID'] . "' , 1 ,'" . $info['salarykey'] . "'
                    , '" . $info['changeam'] . "' , now() , '" . $info['remark'] . "'
                    , '" . $this->nowy . "', '" . $this->nowm . "' , md5(concat('" . get_rand_key() . "',rand()))
                )";
            $this->db->query_exc($sql);
        }
        return $resdb;
    }
    /**
     * 自动通过
     */
    
    function model_flow_auto_do(){
        set_time_limit(600);
        //'eric.ye',
        $sql="select 
                    s.rand_key as stepkey , s.dealuser
             FROM salary_flow_step s 
                    left join salary_flow f on (s.salaryfid=f.id)
             where s.sta<>1 and f.id is not null and f.sta<>3
                    and s.dealuser in ('jingkai.qu')
                    order by  f.id , s.id  ";
        $query = $this->db->query_exc($sql);
        while ($row = $this->db->fetch_array($query)) {
            if($row['stepkey']&&$row['dealuser']){
                //审批通过
                $this->model_flow_do($row['stepkey'],'yes','',$row['dealuser']);
                
            }
        }
    }
    /**
     *
     * @param <type> $stepkey
     * @param <type> $res
     * @param <type> $remark
     * @return <type> 
     */
    function model_flow_do($stepkey, $res, $remark,$douser='') {
        if(empty($douser)){
            $douser=$_SESSION['USER_ID'];
        }
        $emailArr = array();
        try {
            $this->db->query("START TRANSACTION");
            //更新当前步骤
            if(!$stepkey){
                throw new Exception('Data out time');
            }
            $sql="select
                    fs.step , fs.salaryfid
                from
                    salary_flow_step fs
                where
                    fs.rand_key='".$stepkey."'
                    and find_in_set('".$douser."',fs.dealuser)
                    and fs.sta='0' ";
            $ri=$this->db->get_one($sql);
            if(!$ri['salaryfid']){
                throw new Exception('Data Query failed');
            }
            $flowkey=$ri['salaryfid'];
            $nextstep=intval($ri['step']+1);
            $sql = "update salary_flow_step
                    set
                        sta='1' , res='" . $res . "' , remark='" . $remark . "'
                        , dealuser='" . $douser . "' , dealdt=now()
                    where rand_key='" . $stepkey . "' ";
            $this->db->query_exc($sql);
            $sql = "update salary_flow
                    set
                        sta='1' 
                    where id='" . $flowkey . "' ";
            $this->db->query_exc($sql);
            if ($res == 'no') {
                $sql = "update salary_flow
                    set sta='3'
                    where id='" . $flowkey . "' ";
                $this->db->query_exc($sql);
                $sql = "select
                        f.flowname , f.createuser as userid , f.salarykey
                    from salary_flow f
                    where id='" . $flowkey . "' ";
                $rs = $this->db->get_one($sql);
                $emailArr['add'] = $rs['userid'];
                $emailArr['res'] = 'back';
                $emailArr['type'] = $rs['flowname'];
                $emailArr['bill'] = $flowkey;
                if($rs['flowname']==$this->flowName['spe']){
                    $sql="update salary_spe set spesta='2' where rand_key='".$rs['salarykey']."' ";
                    $this->db->query_exc($sql);
                }
            } elseif ($res == 'yes') {
                $ckf=false;
                $sql = "select
                s.item , s.rand_key , s.dealuser as userid , s.step
                , f.flowname
            from salary_flow_step s
                left join salary_flow f on (s.salaryfid=f.id )
            where
                s.salaryfid='" . $flowkey . "'
                and s.step>='".$nextstep."'
            order by s.step asc ";
                $query=$this->db->query_exc($sql);
                $ars=$this->db->affected_rows();
                $i=1;
                if($ars&&$ars>=1){
                    while($row=$this->db->fetch_array($query)){
                        if(trim($row['userid'],',')==$douser){
                            $sql = "update salary_flow_step
                                set
                                    sta='1' , res='" . $res . "' , remark='" . $remark . "'
                                    , dealuser='" . $douser . "' , dealdt=now()
                                where rand_key='" . $row['rand_key'] . "' ";
                            $this->_db->query_exc($sql);
                        }else{
                            $emailArr['add'] = $row['userid'];
                            $emailArr['res'] = 'next';
                            $emailArr['type'] = $row['flowname'];
                            $emailArr['bill'] = $flowkey;
                            $sql = "update salary_flow_step set sta='0' where rand_key='" . $row['rand_key'] . "' ";
                            $this->_db->query_exc($sql);
                            break;
                        }
                        if($i==$ars){//审批完成
                            $ckf=true;
                        }
                        $i++;
                    }
                }else{//审批流完成
                    $ckf=true;
                }
                if($ckf){
                    $this->model_flow_pass($flowkey);
                }
            }
            $this->db->query("COMMIT");
            if(!empty($emailArr)&&$emailArr['add']){
                if($emailArr['res']=='next'){
                    $body='您好！<br><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;系统内有工资数据：'.$emailArr['type'].'，需要您的审批<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;此项数据由'.$_SESSION['USER_NAME'].'向您提交<br>
                            谢谢！';
                }elseif($emailArr['res']=='back'){
                    $body='您好！<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;您所申请的：'.$emailArr['type'].'，单号：'.$emailArr['bill'].'，已<font color="red">打回</font>。<br>
                            谢谢！';
                }elseif($emailArr['res']=='finish'){
                    $body='您好！<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;您所申请的：'.$emailArr['type'].'，单号：'.$emailArr['bill'].'，已<font color="green">通过</font>。<br>
                            谢谢！';
                }
                $this->model_send_email('工资审批', $body, $emailArr['add'],false,true);
            }
            $this->globalUtil->insertOperateLog('工资审批', $stepkey, '审批' . $res, '成功');
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->globalUtil->insertOperateLog('工资审批', $stepkey, '审批' . $res, '失败', $e->getMessage());
            return $e->getMessage();
        }
    }
    function model_flow_pass($flowkey){
        $salinfo=array();
        $payinfo=array();
        $emailArr=array();
        //$this->nowm=6;
        $salkey='';
        $payid='';
        $flowremark='';
        $sql="select f.rand_key , f.changeam , f.createuser as useride , f.flowname , f.userid , f.salarykey , f.remark as flowremark
                , f.pyear , f.pmon
                , u.user_name as saluser , d.dept_name as saldept , u.email as salemail
                , u.company , u.salarycom , f.changedt
            from salary_flow f 
                left join user u on (f.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where f.id='".$flowkey."' ";
        $resf=$this->db->get_one($sql);
        if(empty($resf['rand_key'])){
            throw new Exception('Salary flow data query failed');
            return false;
        }
        $flowremark=$resf['flowremark'];
        $emailArr['add'] = $resf['useride'];
        $emailArr['res'] = 'finish';
        $emailArr['type'] = $resf['flowname'];
        $emailArr['am'] = $this->salaryClass->decryptDeal($resf['changeam']);
        $emailArr['saluser'] = $resf['saluser'];
        $emailArr['saldept'] = $resf['saldept'];
        $emailArr['salemail'] = $resf['salemail'];
        $compt=$resf['company'];
        if(!empty($resf['salarycom'])){
            $compt=$resf['salarycom'];
        }
        $comtable=$this->get_com_sql($compt);
        $sql = "update salary_flow
            set sta='2' , pyear = '".$this->nowy."' , pmon='".$this->nowm."'
            where id='" . $flowkey . "' ";
        $this->db->query_exc($sql);
        if($resf['flowname']==$this->flowName['fla']){//季度奖
            $sql="select p.sdyam , p.otheram , p.remark , p.id as pid , s.rand_key , p.userid as puserid
                from ".$comtable."salary_pay p
                    left join salary s on (p.userid=s.userid)
                where p.userid='".$resf['userid']."'
                    and p.pyear='".$this->nowy."' and p.pmon='".$this->nowm."' ";
            $respay=$this->db->get_one($sql);
            if(empty($respay)){
                throw new Exception('Salary flow data query failed');
            }
            $salkey=$respay['rand_key'];
            $payid=$respay['pid'];
            $flaamtmp=$this->salaryClass->decryptDeal($resf['changeam']);
            $salinfo['floatam']=$this->salaryClass->cfv($flaamtmp);
            $payinfo['floatam']=$this->salaryClass->cfv($flaamtmp*$this->flaotMon[$this->nowm]);
            $payinfo['remark']=$flowremark;
        }elseif($resf['flowname']==$this->flowName['sdyhr']){//人事补贴
            $sql="select sdymeal , sdyother , userid   from salary_sdy
                where rand_key='".$resf['salarykey']."' ";
            $ressdy=$this->db->get_one($sql);
            if(empty($ressdy)){
                throw new Exception('Salary flow data query failed');
            }
            $sql="update salary_sdy set flaflag='2' , pyear ='".$this->nowy."'  , pmon ='".$this->nowm."'
                where rand_key='".$resf['salarykey']."' ";
            $this->db->query_exc($sql);
            $sql="select p.sdyam , p.otheram , p.remark , p.id as pid , s.rand_key
                from ".$comtable."salary_pay p
                    left join salary s on (p.userid=s.userid)
                where p.userid='".$ressdy['userid']."'
                    and p.pyear='".$this->nowy."' and p.pmon='".$this->nowm."' ";
            $respay=$this->db->get_one($sql);
            $salkey=$respay['rand_key'];
            $payid=$respay['pid'];
            $oldSdyAm=$this->salaryClass->decryptDeal($respay['sdyam']);
            $oldOthAm=$this->salaryClass->decryptDeal($respay['otheram']);
            $newSdyAm=$this->salaryClass->decryptDeal($ressdy['sdymeal']);
            $newOthAm=$this->salaryClass->decryptDeal($ressdy['sdyother']);
            //累计
            $payinfo['sdyam']=$this->salaryClass->cfv($oldSdyAm+$newSdyAm);
            $payinfo['otheram']=$this->salaryClass->cfv($oldOthAm+$newOthAm);
            $payinfo['remark']=$flowremark;
            //人事补贴
        }elseif($resf['flowname']==$this->flowName['spe']){//特殊奖励/扣除
            $sql="select payyear , paymon , amount , paytype , payuserid , acctype from salary_spe
                where rand_key='".$resf['salarykey']."' ";
            $resspe=$this->db->get_one($sql);
            if(empty($resspe)){
                throw new Exception('Salary flow data query failed');
            }
            $sql="select p.sperewam , p.spedelam , p.accrewam , p.accdelam  , p.remark , p.id as pid , s.rand_key
                from ".$comtable."salary_pay p
                    left join salary s on (p.userid=s.userid)
                where p.userid='".$resspe['payuserid']."'
                    and p.pyear='".$this->nowy."' and p.pmon='".$this->nowm."' ";
            $respay=$this->db->get_one($sql);
            $salkey=$respay['rand_key'];
            $payid=$respay['pid'];
            if($resspe['acctype']=='0'){//参与计税
                if($resspe['paytype']=='0'){
                    $oldam=$this->salaryClass->decryptDeal($respay['sperewam']);
                    $newam=$this->salaryClass->decryptDeal($resspe['amount']);
                    $oldrmk=$respay['remark'];
                    $payinfo['sperewam']=$this->salaryClass->cfv($oldam+$newam);
                    $payinfo['remark']=$flowremark;
                }elseif($resspe['paytype']=='1'){
                    $oldam=$this->salaryClass->decryptDeal($respay['spedelam']);
                    $newam=$this->salaryClass->decryptDeal($resspe['amount']);
                    $oldrmk=$respay['remark'];
                    $payinfo['spedelam']=$this->salaryClass->cfv($oldam+$newam);
                    $payinfo['remark']=$flowremark;
                }
            }else{
                if($resspe['paytype']=='0'){
                    $oldam=$this->salaryClass->decryptDeal($respay['accrewam']);
                    $newam=$this->salaryClass->decryptDeal($resspe['amount']);
                    $oldrmk=$respay['remark'];
                    $payinfo['accrewam']=$this->salaryClass->cfv($oldam+$newam);
                    $payinfo['remark']=$flowremark;
                }elseif($resspe['paytype']=='1'){
                    $oldam=$this->salaryClass->decryptDeal($respay['accdelam']);
                    $newam=$this->salaryClass->decryptDeal($resspe['amount']);
                    $oldrmk=$respay['remark'];
                    $payinfo['accdelam']=$this->salaryClass->cfv($oldam+$newam);
                    $payinfo['remark']=$flowremark;
                }
            }
            $sql="update salary_spe set spesta='3' , payyear='".$this->nowy."' , paymon='".$this->nowm."'  where rand_key='".$resf['salarykey']."' ";
            $this->db->query_exc($sql);
            //特殊奖励/扣除
        }elseif($resf['flowname']==$this->flowName['nym_0']||$resf['flowname']==$this->flowName['nym_1']
                ||$resf['flowname']==$this->flowName['nym_2']||$resf['flowname']==$this->flowName['nym_3']
                ||$resf['flowname']==$this->flowName['nym_4']||$resf['flowname']==$this->flowName['ymd'])
        {//调薪
            $sql="select p.sperewam , p.spedelam , p.remark , p.id as pid , s.rand_key , s.amount
                from ".$comtable."salary_pay p
                    left join salary s on (p.userid=s.userid)
                where p.userid='".$resf['userid']."'
                    and p.pyear='".$this->nowy."' and p.pmon='".$this->nowm."' ";
            $respay=$this->db->get_one($sql);
            $salkey=$respay['rand_key'];
            $payid=$respay['pid'];
            $oldam=$this->salaryClass->cfv($this->salaryClass->decryptDeal($respay['amount']));
            $changeam=$this->salaryClass->cfv($this->salaryClass->decryptDeal($resf['changeam']));
            $salinfo['amount']=$changeam;
            $payinfo['baseam']=$changeam;
            $payinfo['remark']=$flowremark;
            //调薪日期
            if(!empty($resf['changedt'])&&$resf['changedt']!='0000-00-00'&&!empty($oldam)){
                $passNowAm=$this->salaryClass->salaryPass($oldam, $changeam, $resf['changedt']);
                $payinfo['basenowam']=$passNowAm;
            }
            //
            if($compt=='sy'){
                /*
                $gjj=$this->salaryClass->salaryGjj($resf['changeam'], $compt);
                $salinfo['gjjam']=$gjj['p'];
                $salinfo['cogjjam']=$gjj['c'];
                $payinfo['gjjam']=$gjj['p'];
                $payinfo['cogjjam']=$gjj['c'];
                $shb=$this->salaryClass->salaryShb($resf['changeam'], $compt);
                $salinfo['shbam']=$shb['p'];
                $salinfo['coshbam']=$shb['c'];
                $payinfo['shbam']=$shb['p'];
                $payinfo['coshbam']=$shb['c'];*/
            }
            
        }elseif($resf['flowname']==$this->flowName['pro']){//项目奖
            $sql="select p.sperewam , p.spedelam , p.remark , p.id as pid , s.rand_key , p.proam
                from ".$comtable."salary_pay p
                    left join salary s on (p.userid=s.userid)
                where p.userid='".$resf['userid']."'
                    and p.pyear='".$this->nowy."' and p.pmon='".$this->nowm."' ";
            $respay=$this->db->get_one($sql);
            $salkey=$respay['rand_key'];
            $payid=$respay['pid'];
            $oldam=$this->salaryClass->decryptDeal($respay['proam']);
            $newam=$this->salaryClass->decryptDeal($resf['changeam']);
            $oldrmk=$respay['remark'];
            $payinfo['proam']=$this->salaryClass->cfv($oldam+$newam);
            $payinfo['remark']=$flowremark;
        }elseif($resf['flowname']==$this->flowName['bos']){//奖金
            $sql="select p.sperewam , p.spedelam , p.remark , p.id as pid , s.rand_key , p.bonusam
                from ".$comtable."salary_pay p
                    left join salary s on (p.userid=s.userid)
                where p.userid='".$resf['userid']."'
                    and p.pyear='".$this->nowy."' and p.pmon='".$this->nowm."' ";
            $respay=$this->db->get_one($sql);
            $salkey=$respay['rand_key'];
            $payid=$respay['pid'];
            $oldam=$this->salaryClass->decryptDeal($respay['bonusam']);
            $newam=$this->salaryClass->decryptDeal($resf['changeam']);
            $payinfo['bonusam']=$this->salaryClass->cfv($oldam+$newam);
            $payinfo['remark']=$flowremark;
        }
        if(!empty ($salinfo)&&$salkey){
            $this->model_salary_update($salkey,$salinfo);
        }
        if(!empty ($payinfo)&&$payid){
            $this->model_pay_update($payid,$payinfo,'',$comtable);
            $this->model_pay_stat($payid,$comtable);
        }
        if(!empty ($payinfo)&&$payid&&!empty ($salinfo)&&$salkey&&$resf['flowname']==$this->flowName['ymd']){
            try{
                $body=<<<EOD
<div style='font-size:10pt;'>
{$emailArr['saldept']}  {$emailArr['saluser']}<br/>
<br/>
&nbsp;&nbsp;&nbsp;&nbsp;2012年非管理人员年度调薪工作已结束，本着市场化原则及与公司发展相适应的原则，公司根据不同员工的工作职责、任职条件、市场薪酬数据及上年度绩效考核结果，对部分岗位的基本工资做了不同程度的调整。<br/><br/>
&nbsp;&nbsp;&nbsp;&nbsp;从2012年4月起，您的基本工资调整为：{$emailArr['am']}<br/><br/>
&nbsp;&nbsp;&nbsp;&nbsp;请严格保密个人工资信息，并严禁打探或透露公司任何人的工资信息，违者以解除劳动合同处理。<br/><br/>
&nbsp;&nbsp;&nbsp;&nbsp;有疑问之处，仅限于向部门总监或人力资源部总监咨询。<br/>
<br/>
&nbsp;&nbsp;&nbsp;&nbsp;以上内容，敬请知悉。谢谢<br/><br/>
<br/>                                                                           
                                                                                         珠海世纪鼎利通信科技股份有限公司<br/><br/>
                                                                                                              2012-4-28<br/>
</div>
EOD;
                $this->model_send_email('年度调薪通知', $body, $emailArr['salemail'],true,true);
            }catch(Exception $e){
                $this->globalUtil->insertOperateLog('工资审批', $emailArr['salemail'], '邮件发送失败' . $body, '失败', $e->getMessage());
            }
        }
    }

    function model_get_superiors($user){
        $resu='';
        $sql="select
                d.vicemanager , d.majorid  , h.userlevel
            from
                user u
                left join department d on (u.dept_id=d.dept_id)
                left join hrms h on (u.user_id=h.user_id)
            where
                u.dept_id=d.dept_id
                and u.user_id='".$user."' ";
        $res=$this->db->get_one($sql);
        $sql="select
                u.user_id
            from
                user u
                left join user_priv p on (u.user_priv =p.user_priv )
            where
                u.user_priv=p.user_priv
                and p.priv_name='总经理' ";
        $query=$this->db->query($sql);
        $gm='';
        while($row=$this->db->fetch_array($query)){
            $gm.=$row['user_id'].',';
        }
        $res['generalmanager']=$gm;
        if(!$res['vicemanager']){
            $res['vicemanager']=$res['generalmanager'];
        }
        if(!$res['majorid']){
            $res['majorid']=$res['vicemanager'];
        }
        switch($res['userlevel']){
            case "0":
                $resu=$res['generalmanager'];
                break;
            case "1":
                $resu=$res['generalmanager'];
                break;
            case "2":
                $resu=$res['vicemanager'];
                break;
            case "3":
                $resu=$res['majorid'];
                break;
            case "4":
                $resu=$res['majorid'];
                break;
            default :
                $resu=$res['majorid'];
                break;
        }
        return $resu;
    }
    /**
     * 获取用户信息
     */
    function model_salary_info(){
        $type=$_POST['type'];
        $id=$_POST['id'];
        $cdflag=$_POST['cdflag'];
        $sql="select $type from salary where userid ='".$id."' ";
        $res=$this->db->get_one($sql);
        if($cdflag=='1'){
            return $this->salaryClass->decryptDeal($res[$type]);
        }else{
            return $res[$type];
        }
    }
    /**
     * 临时错误
     */
    function change_error($comedt,$leavedt, $amount,$pid){
        $baseNow = $this->salaryClass->salaryDealLeave($comedt,$leavedt, $amount);
        $this->model_pay_update($pid,
                array('basenowam' => $baseNow)
                );
        $this->model_pay_stat($pid);
    }
    /*
     * 更新工资
     */
    function update_salary(){
        /*
        $data=array(344,356,394,410,345,238,252,294,312,239);
        foreach($data as $val){
            $this->model_pay_stat($val,'`shiyuanoa`.');
        }
         * for($i=115;$i<=222;$i++){
            $this->model_pay_stat($i,'`shiyuanoa`.');
        }
         
        $data=array(
        294,394
        );
        foreach($data as $val){
            $this->model_pay_stat($val,'`shiyuanoa`.');
        }
         $sql="SELECT  p.id  FROM salary_pay p
left join salary s on (s.userid=p.userid)
where p.pyear=2012 and p.pmon=6 and (p.gjjam<>s.gjjam  or p.shbam<>s.shbam)";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $this->model_pay_stat($row['id']);
        }
        $sql="SELECT  p.id  FROM `beiruanoa`.salary_pay p
left join salary s on (s.userid=p.userid)
where p.pyear=2012 and p.pmon=7 and s.userid=p.userid";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $this->model_pay_stat($row['id'],'`beiruanoa`.');
        }
         */
        //$this->model_pay_stat(27091);
        //$this->model_pay_stat(707,'`shiyuanoa`.');
        //$this->model_pay_stat(708,'`shiyuanoa`.');
        $data=array(36573);
        foreach($data as $val){
            $this->model_pay_stat($val);
        }
    }
    /**
     * 检查数据
     */
    
    function get_decrypt_deal(){
        $val=array('4T3znHIkj4Vy+j2IF/zErQ==');
        foreach($val as $vval){
            $res.='--'.$vval.' ： '.$this->salaryClass->decryptDeal($val);
        }
        file_put_contents('decrypt',$res);
    }
    /**
     * 年终奖更新
     */
    function update_salary_yeb(){
        if($_SESSION['USER_ID']=='yanping.li'){
            $sql="SELECT 
                    y.id , y.yearam 
                    , p.totalam , p.gjjam , p.shbam , p.cessebase
                    , y.realam , y.usercard
                FROM salary_yeb y 
                left join hrms h on (y.usercard=h.usercard)
                left join salary_pay p on ( p.userid=h.user_id and p.pyear='2012' and p.pmon='2' )
                where 
                    y.syear='2011'";
            $query=$this->db->query($sql);
            $updata=array();
            while($row=$this->db->fetch_array($query)){
                $updata[$row['id']]['yam']=$this->salaryClass->decryptDeal($row['yearam']);
                $updata[$row['id']]['bam']=$this->salaryClass->decryptDeal($row['totalam']);
                $updata[$row['id']]['gjjam']=$this->salaryClass->decryptDeal($row['gjjam']);
                $updata[$row['id']]['shbam']=$this->salaryClass->decryptDeal($row['shbam']);
                $updata[$row['id']]['realam']=$this->salaryClass->decryptDeal($row['realam']);
                $updata[$row['id']]['cb']=$row['cessebase'];
                $updata[$row['id']]['usercard']=$row['usercard'];
            }
            if(!empty($updata)){
                $tmuid='';
                foreach($updata as $key=>$val){
                    if(!empty($key)){
                        $yearAm=round($val['yam'],2);
                        $lastmonam=round($val['bam']-$val['gjjam']-$val['shbam']);
                        if($val['usercard']=='000977'){
                            $lastmonam=round(1204.55);
                        }
                        if($val['usercard']=='00001551'){
                            $lastmonam=round(2522.73-189.2);
                        }
                        if($val['realam']!=$lastmonam){
                            $tmuid.=','.$key;
                            $payCesseAm=$this->salaryClass->cesseDealYeb($yearAm, $lastmonam, $val['cb']);
                            $payAm=round($yearAm-$payCesseAm,2);
                            $sql="update salary_yeb set 
                                    yearam='".$this->salaryClass->encryptDeal($yearAm)."'
                                    , paycesseam='".$this->salaryClass->encryptDeal($payCesseAm)."'  
                                    , payam='".$this->salaryClass->encryptDeal($payAm)."'
                                    , sta='0' , inputuser='".$_SESSION['USER_ID']."' , inputdt=now()
                                    , realam='".$this->salaryClass->encryptDeal($lastmonam)."'
                                    where id='".$key."' ";
                            $this->db->query($sql);
                        }
                    }
                }
                $this->globalUtil->insertOperateLog('年终奖调整', '', '邮件发送失败' . $tmuid, '成功', $tmuid);
            }
        }
    }
    /**
     * 项目
     */
    function deal_pro(){
        $proArr=array();
                $sql="(SELECT t.id ,  t.name , t.pyear , t.pmon , p.userid , p.totalam  , p.shbam , p.gjjam , p.paycesse , p.paytotal  ,'' as com  FROM salary_user_type t 
                        left join salary_pay p on ( find_in_set(p.userid , t.members) and p.pyear=t.pyear and p.pmon=t.pmon )
                        where p.id is not null and t.pmon!=8)
                        union 
                        (SELECT t.id ,  t.name , t.pyear , t.pmon , p.userid , p.totalam  , p.shbam , p.gjjam , p.paycesse , p.paytotal ,'s' as com   FROM salary_user_type t 
                        left join `shiyuanoa`.salary_pay p on ( find_in_set(p.userid , t.members) and p.pyear=t.pyear and p.pmon=t.pmon )
                        where p.id is not null and t.pmon!=8)
                        union 
                        (SELECT t.id ,  t.name , t.pyear , t.pmon , p.userid , p.totalam  , p.shbam , p.gjjam , p.paycesse , p.paytotal ,'b' as com  FROM salary_user_type t 
                        left join `beiruanoa`.salary_pay p on ( find_in_set(p.userid , t.members) and p.pyear=t.pyear and p.pmon=t.pmon )
                        where p.id is not null and t.pmon!=8)";
                $query=$this->db->query($sql);
                while($row=$this->db->fetch_array($query)){
                    
                    $proArr[$row['id']][$row['com'].'totalam']=isset($proArr[$row['id']][$row['com'].'totalam'])?round($proArr[$row['id']][$row['com'].'totalam']+$this->salaryClass->decryptDeal($row['totalam']),2):$this->salaryClass->decryptDeal($row['totalam']);
                    $proArr[$row['id']][$row['com'].'shbam']=isset($proArr[$row['id']][$row['com'].'shbam'])?round($proArr[$row['id']][$row['com'].'shbam']+$this->salaryClass->decryptDeal($row['shbam']),2):$this->salaryClass->decryptDeal($row['shbam']);
                    $proArr[$row['id']][$row['com'].'gjjam']=isset($proArr[$row['id']][$row['com'].'gjjam'])?round($proArr[$row['id']][$row['com'].'gjjam']+$this->salaryClass->decryptDeal($row['gjjam']),2):$this->salaryClass->decryptDeal($row['gjjam']);
                    $proArr[$row['id']][$row['com'].'paycesse']=isset($proArr[$row['id']][$row['com'].'paycesse'])?round($proArr[$row['id']][$row['com'].'paycesse']+$this->salaryClass->decryptDeal($row['paycesse']),2):$this->salaryClass->decryptDeal($row['paycesse']);
                    $proArr[$row['id']][$row['com'].'paytotal']=isset($proArr[$row['id']][$row['com'].'paytotal'])?round($proArr[$row['id']][$row['com'].'paytotal']+$this->salaryClass->decryptDeal($row['paytotal']),2):$this->salaryClass->decryptDeal($row['paytotal']);

                }
                
                if(!empty($proArr)){
                    foreach ($proArr as $key=>$val) {
                        $sql="update salary_user_type set 
                                totalam = '".$val['totalam']."'  , shbam = '".$val['shbam']."'
                                , gjjam = '".$val['gjjam']."', paycesse = '".$val['paycesse']."'
                                , paytotal = '".$val['paytotal']."'
                                
                                ,stotalam = '".$val['stotalam']."'  , sshbam = '".$val['sshbam']."'
                                , sgjjam = '".$val['sgjjam']."', spaycesse = '".$val['spaycesse']."'
                                , spaytotal = '".$val['spaytotal']."'
                                    
                                ,btotalam = '".$val['btotalam']."'  , bshbam = '".$val['bshbam']."'
                                , bgjjam = '".$val['bgjjam']."', bpaycesse = '".$val['bpaycesse']."'
                                , bpaytotal = '".$val['bpaytotal']."'
                            where id='".$key."' ";
                        $this->db->query($sql);
                    }
                }
    }
    //*********************************析构函数************************************
    function __destruct() {

    }

}
?>