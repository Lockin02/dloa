<?php
class model_cost_manager_costcom extends model_base
{

    public $page;
    public $num;
    public $start;
    public $db;
    public $comsta;
    public $entity;
    public $serialtype;

    //*******************************构造函数***********************************
    function __construct(){
        parent::__construct();
        $this->db = new mysql();
        $this->comsta = '完成';//财务录入
        $this->page = intval($_GET['page']) ? intval($_GET[page]) : 1;
        $this->start = ($this->page == 1) ? 0 : ($this->page - 1) * pagenum;
        $this->num = intval($_GET['num']) ? intval($_GET['num']) : false;
        $this->serialtype=array('1'=>'银','2'=>'现','3'=>'转');
    }
    function model_list(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $seabn = $_GET['seabn'];
        $seasn = $_GET['seasn'];
        $seaman = $_GET['seaman'];
        $seaam = $_GET['seaam'];
        $seact = $_GET['seact'];
        $seabt = $_GET['seabt'];
        $start = $limit * $page - $limit;
        $sqlSch='';
        if(!empty($seabn)){
            $sqlSch.=" and l.billno like '%".$seabn."%' ";
        }
        if(!empty($seasn)){
            $sqlSch.=" and l.serialno like '%".$seasn."%' ";
        }
        if(!empty($seaman)){
            $sqlSch.=" and u.user_name like '%".$seaman."%' ";
        }
        if(!empty($seaam)){
            $sqlSch.=" and l.amount = '".$seaam."' ";
        }
        if(!empty($seact)){
            $sqlSch.=" and d.costtypeid = '".$seact."' ";
        }
        if(!empty($seabt)){
            $sqlSch.=" and d.billtypeid = '".$seabt."' ";
        }
        //总数
        $sql = "select count(distinct l.billno) as cam
            from
                bill_list l
                left join user u on (l.inputman=u.user_id)
                left join bill_detail d on (l.billno=d.billno)
            where l.status='".$this->comsta."'
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['cam'];
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
                l.rand_key , l.billno , u.user_name as costman , l.amount
                , l.bank , l.bankacc , l.tallydt , l.serialtype , l.serialno
            from
                bill_list l
                left join user u on (l.inputman=u.user_id)
                left join bill_detail d on (l.billno=d.billno)
            where l.status='".$this->comsta."'
                $sqlSch
            group by l.billno 
            order by $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $responce->rows[$i]['id'] = $row['userid'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array('' ,$row['rand_key'], $row['billno']
                                ,$this->serialtype[$row['serialtype']]
                                ,$row['serialno']
                                ,$row['costman'] , $row['amount']
                                ,$row['bank'], $row['bankacc'] , $row['tallydt']
                            )
            );
            $i++;
            //$row['tallydt']
        }
        return json_encode($responce);
    }
    
    function model_user_list(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $seaval = $_GET['seaval'];
        $start = $limit * $page - $limit;
        $sqlSch='';
        if(!empty($seaval)){
            $sqlSch = " and u.user_name like '%".$seaval."%'";
        }
        //总数
        $sql = "select count(u.user_id)
            from
                user u
                left join department d on (u.dept_id=d.dept_id)
                left join user_jobs j on (u.jobs_id=j.id)
            where u.del='0' and u.has_left='0' and u.dept_id<>1
                $sqlSch ";
        $this->pf($sql.$_GET['seaval']);
        $rs = $this->db->get_one($sql);
        $count = $rs['count(u.user_id)'];
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
                u.user_name , u.user_id , d.dept_id , d.dept_name , j.id , j.name as job_name 
            from
                user u
                left join department d on (u.dept_id=d.dept_id)
                left join user_jobs j on (u.jobs_id=j.id)
            where u.del='0' and u.has_left='0' and u.dept_id<>1
                $sqlSch
            order by $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $responce->rows[$i]['id'] = $row['userid'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array($row['user_id'], $row['user_name']
                                ,$row['dept_name'] , $row['job_name']
                                ,$row['dept_id'], $row['job_id']
                            )
            );
            $i++;
        }
        return json_encode($responce);
    }
    function model_payee_list(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $seaval = $_GET['seaval'];
        $start = $limit * $page - $limit;
        $sqlSch='';
        if(!empty($seaval)){
            $sqlSch = " and p.name like '%".$seaval."%'";
        }
        //总数
        $sql = "select count(p.id)
            from
                cost_company_payee p
            where 1
                $sqlSch ";
        $this->pf($sql.$_GET['seaval']);
        $rs = $this->db->get_one($sql);
        $count = $rs['count(p.id)'];
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
                p.name , p.province , p.town , p.acc , p.id
            from
                cost_company_payee p
            where 1
                $sqlSch
            order by $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $responce->rows[$i]['id'] = $row['userid'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array($row['id'], $row['name']
                                ,$row['province'] , $row['town']
                                ,$row['acc']
                            )
            );
            $i++;
        }
        return json_encode($responce);
    }
    function model_bank_list(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $seaval = $_GET['seaval'];
        $start = $limit * $page - $limit;
        $sqlSch='';
        if(!empty($seaval)){
            $sqlSch = " and b.acc like '%".$seaval."%'";
        }
        //总数
        $sql = "select count(b.id)
            from
                cost_company_bank b
            where 1
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['count(b.id)'];
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
                b.name , b.acc , b.id
            from
                cost_company_bank b
            where 1
                $sqlSch
            order by $sidx $sord
            limit $start , $limit ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $responce->rows[$i]['id'] = $row['userid'];
            $responce->rows[$i]['cell'] = un_iconv(
                            array($row['id'], $row['name']
                                ,$row['acc']
                            )
            );
            $i++;
        }
        return json_encode($responce);
    }
    function model_cost_type($flag='cost',$view='menu'){
        $carr=array();
        if($flag=='cost'){
            $sql="select costtypeid as id , costtypename as name , parentcosttypeid as pid
                from cost_type
                where parentcosttypeid <>0  ";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                $carr[$row['pid']][$row['id']]=$row['name'];
            }
        }else{
            $sql="select id as id , name as name , parentid as pid
                from bill_type
                where parentid<>0 ";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                $carr[$row['pid']][$row['id']]=$row['name'];
            }
        }
        $res='';
        if($view=='menu'){
            foreach($carr['1'] as $key=>$val){
                if(empty($carr[$key])){//当前级
                    $res.='<tr><td>
                        <img src="images/menu/tree_blank.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                        <a href="#" name="'.$key.'" id="'.$key.'" onclick="clickFun(\''.$key.'\',\''.$val.'\')">'.$val.'</a>
                        </td></tr>';
                }else{//含子级
                    $res.='<tr><td>
                        <img src="images/menu/tree_minus.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                        '.$val.'
                        </td></tr>';
                    foreach($carr[$key] as $key1=>$val1){
                        if(empty($carr[$key1])){//当前级
                            $res.='<tr><td>
                                <img src="images/menu/tree_line.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                                <img src="images/menu/tree_blank.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                                <a href="#" name="'.$key1.'" id="'.$key1.'" onclick="clickFun(\''.$key1.'\',\''.$val1.'\')">'.$val1.'</a>
                                </td></tr>';
                        }else{//含子级
                            $res.='<tr><td>
                                <img src="images/menu/tree_line.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                                <img src="images/menu/tree_minus.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                                '.$val1.'
                                </td></tr>';
                            foreach($carr[$key1] as $key2=>$val2){
                                $res.='<tr><td>
                                        <img src="images/menu/tree_line.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                                        <img src="images/menu/tree_line.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                                        <img src="images/menu/tree_blank.gif" align="absbottom" class="outline" id="" style="cursor:hand" />
                                        <a href="#" name="'.$key2.'" id="'.$key2.'" onclick="clickFun(\''.$key2.'\',\''.$val2.'\')">'.$val2.'</a>
                                        </td></tr>';
                            }
                        }
                    }
                }
            }
            $res.='<tr><td>&nbsp;</td></tr>';
        }elseif($view=='select'){
            foreach($carr['1'] as $key=>$val){
                if(empty($carr[$key])){//当前级
                    $res.='<option value="'.$key.'">'.$val.'</option>';
                }else{//含子级
                    $res.='<option value="'.$key.'">+'.$val.'</option>';
                    foreach($carr[$key] as $key1=>$val1){
                        if(empty($carr[$key1])){//当前级
                            $res.='<option value="'.$key1.'">|--'.$val1.'</option>';
                        }else{//含子级
                            $res.='<option value="'.$key1.'">|--'.$val1.'</option>';
                            foreach($carr[$key1] as $key2=>$val2){
                                $res.='<option value="'.$key2.'">|--|--'.$val2.'</option>';
                            }
                        }
                    }
                }
            }
        }
        return $res;
    }
    function model_dept_list(){
        $res='';
        $sql="select dept_id , dept_name , depart_x
            from department
            where dept_id<>1
            order by depart_x ";
        $query=$this->db->query($sql);
        $i=0;
        while($row = $this->db->fetch_array($query) ){
            $i++;
            $res.='<tr class="tableline'.($i%2).'"><td>';
            
            $res.='</td></tr>';
            /*
            if(strlen($row['depart_x'])==4){
                $res.='<img src="images/menu/tree_blank.gif" align="absbottom" class="outline" />'.$row['dept_name'];
            }else{
                $res.=$row['dept_name'];
            }
            $res.='</td><td>
                <input id="" name="amount['.$row['dept_id'].']" class="billdept" value="" style="width:80px;">
                </td>
                <td>
                <select name="acctype['.$row['dept_id'].']" >
                    <option value="pro">项目信息</option>
                    <option value="pro">合同信息</option>
                </select>
                名称：
                <input name="accname['.$row['dept_id'].']" value="">
                编号：
                <input name="accno['.$row['dept_id'].']" value="">
                </td>
                <td >
                <img src="images/collapsed.gif" align="center" style="cursor:hand;padding-right:10px;padding-left:10px;"
                onclick="amtypeAddFun('.$row['dept_id'].')"/>
                </td>
                </tr>';
             * 
             */
        }
        //return $res;
    }
    function model_new_sub(){
        $subflag=$_POST['subflag'];
        $comdiff=array();
        try {
            $this->db->query("START TRANSACTION");
            if($subflag=='bill'){
                $sql="select max(billno) as maxno
                    from bill_list
                    where billno like '".date('Ymd')."%' ";
                $maxno=$this->db->get_one($sql);
                if(empty($maxno['maxno'])){
                    $newno = date("Ymd").str_pad("1", 4, "0", STR_PAD_LEFT);
                }else{
                    $newno = $maxno['maxno']+1;
                }
                $_POST['bill']['BillNo']=$newno;
                $_POST['bill']['Status']=$this->comsta;
                $keys = array_keys($_POST['bill']);
                $sql="insert into bill_list (`".  implode("`,`", $keys)."`) values ('".  implode("','", $_POST['bill'])."')  ";
                $this->db->query_exc($sql);
                if(empty($_POST['accdept'])){
                    throw new Exception('网络异常，传输数据错误！');
                }else{
                    foreach($_POST['accdept'] as $key=>$val){
                        if(!empty($_POST['accamount'][$key])){
                            $sql="insert into  bill_detail
                                (billtypeid,costtypeid,billdept
                                    ,proname,prono
                                    ,contname,contno
                                    ,unitprice,amount,billno)
                                values
                                ('".$_POST['billtypeid'][$key]."','".$_POST['costtypeid'][$key]."','".$_POST['accdept'][$key]."'
                                    ,'".$_POST['accnamep'][$key]."','".$_POST['accnop'][$key]."'
                                    ,'".$_POST['accnamec'][$key]."','".$_POST['accnoc'][$key]."'
                                    ,'".$_POST['accamount'][$key]."','".$_POST['accamount'][$key]."','".$newno."')";
                            $this->db->query_exc($sql);
                        }
                    }
                }
            }
            $this->db->query("COMMIT");
            $this->checkPayee($_POST['bill']['Payee'],$_POST['bill']['PayeePro'],$_POST['bill']['PayeeTown'],$_POST['bill']['PayeeAcc']);
            $this->checkBank($_POST['bill']['Bank'], $_POST['bill']['BankAcc']);
            $msg='新建成功！';
        } catch (Exception $e) {
            $msg='新建失败，请重新录入！'.$e->getMessage();
            $this->db->query("ROLLBACK");
        }
        showmsg($msg, "location.href='?model=cost_manager_costcom&action=new&placeValuesBefore';
            parent.$('#rowedgrid').trigger('reloadGrid');", 'button');
    }
    function model_edit_sub(){
        try {
            $this->db->query("START TRANSACTION");
            if(empty($_POST['key'])||empty($_POST['accdept'])||empty($_POST['bill'])){
                throw new Exception('网络异常，传输数据错误！');
            }
            foreach($_POST['bill'] as $key=>$val){
                $sql.=$key." ='".$val."' ," ;
            }
            $this->getEntity($_POST['key']);
            $sql=" update bill_list set ".trim($sql,',')."
                where  rand_key='".$_POST['key']."' ";
            $this->db->query_exc($sql);
            $sql=" delete d
                from bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                where l.rand_key='".$_POST['key']."' ";
            $this->db->query_exc($sql);
            foreach($_POST['accdept'] as $key=>$val){
                if(!empty($_POST['accamount'][$key])){
                    $sql="insert into  bill_detail
                                (billtypeid,costtypeid,billdept
                                    ,proname,prono
                                    ,contname,contno
                                    ,unitprice,amount,billno)
                                values
                                ('".$_POST['billtypeid'][$key]."','".$_POST['costtypeid'][$key]."','".$_POST['accdept'][$key]."'
                                    ,'".$_POST['accnamep'][$key]."','".$_POST['accnop'][$key]."'
                                    ,'".$_POST['accnamec'][$key]."','".$_POST['accnoc'][$key]."'
                                    ,'".$_POST['accamount'][$key]."','".$_POST['accamount'][$key]."','".$this->entity['billno']."')";

                    $this->db->query_exc($sql);
                }
            }
            $this->db->query("COMMIT");
            $this->checkPayee($_POST['bill']['Payee'],$_POST['bill']['PayeePro'],$_POST['bill']['PayeeTown'],$_POST['bill']['PayeeAcc']);
            $this->checkBank($_POST['bill']['Bank'], $_POST['bill']['BankAcc']);
            $msg='修改成功！';
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $msg='修改失败，请重新修改！'.$e->getMessage();
        }
        showmsg($msg , "location.href='?key=".$_POST['key']."&model=cost_manager_costcom&action=edit&placeValuesBefore&';
            parent.$('#rowedgrid').trigger('reloadGrid');", 'button');
    }
    function model_del(){
        $sql="delete l , d
            from bill_list l
                left join bill_detail d on (l.billno=d.billno)
            where l.rand_key='".$_POST['key']."' ";
        $res=$this->query($sql);
        if(!$res){
            return false;
        }
    }
    function checkPayee($name,$pro,$town,$acc){
        if(!empty($name)){
            $sql="select id from cost_company_payee
                where name='".$name."'
                    and province='".$pro."'
                    and town='".$town."'
                    and acc='".$acc."' ";
            $res=$this->db->get_one($sql);
            if(empty($res['id'])){
               $sql=" insert into cost_company_payee
                   (name , province , town , acc , creater) values
                   ('".$name."' , '".$pro."' , '".$town."' , '".$acc."' , '".$_SESSION['USER_ID']."' )";
               $this->db->query($sql);
            }
        }
    }
    function checkBank($name,$acc){
        if(!empty($name)&&!empty($acc)){
            $sql="select id from cost_company_bank
                where name='".$name."'
                    and acc='".$acc."' ";
            $res=$this->db->get_one($sql);
            if(empty($res['id'])){
               $sql=" insert into cost_company_bank
                   (name , acc , creater) values
                   ('".$name."' , '".$acc."' , '".$_SESSION['USER_ID']."' )";
               $this->db->query($sql);
            }
        }
    }
    function getEntity($key){
        $this->entity=array();
        $sql=" select
                l.billno , u.user_name as inputmanname , l.inputman
                , l.payee , l.payeepro , l.payeetown , l.payeeacc
                , l.content , l.amount , l.inputdt , l.paydt , l.tallydt
                , l.status , l.bank , l.bankacc
                , d.billtypeid , bt.name as billtypename
                , d.costtypeid , ct.costtypename
                , d.prono , d.proname
                , d.contno , d.contname
                , d.amount as dam , d.billdept , l.serialtype , l.serialno
            from
                bill_list l
                left join user u on (l.inputman=u.user_id)
                left join bill_detail d on (l.billno=d.billno)
                left join bill_type bt on (d.billtypeid=bt.id)
                left join cost_type ct on (d.costtypeid=ct.costtypeid)
            where
                l.rand_key='".$key."'";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $this->entity['billno']=$row['billno'];
            $this->entity['inputmanname']=$row['inputmanname'];
            $this->entity['inputman']=$row['inputman'];
            $this->entity['payee']=$row['payee'];
            $this->entity['payeepro']=$row['payeepro'];
            $this->entity['payeetown']=$row['payeetown'];
            $this->entity['payeeacc']=$row['payeeacc'];
            $this->entity['content']=$row['content'];
            $this->entity['amount']=$row['amount'];
            $this->entity['inputdt']=$row['inputdt'];
            $this->entity['paydt']=$row['paydt'];
            $this->entity['tallydt']=$row['tallydt'];
            $this->entity['status']=$row['status'];
            $this->entity['bank']=$row['bank'];
            $this->entity['bankacc']=$row['bankacc'];
            $this->entity['billtypeid'][]=$row['billtypeid'];
            $this->entity['billtypename'][]=$row['billtypename'];
            $this->entity['costtypeid'][]=$row['costtypeid'];
            $this->entity['costtypename'][]=$row['costtypename'];
            $this->entity['accnamep'][]=$row['proname'];
            $this->entity['accnop'][]=$row['prono'];
            $this->entity['accnamec'][]=$row['contname'];
            $this->entity['accnoc'][]=$row['contno'];
            $this->entity['accdept'][]=$row['billdept'];
            $this->entity['accamount'][]=$row['dam'];
            //20110130
            $this->entity['serialtype']=$row['serialtype'];
            $this->entity['serialno']=$row['serialno'];
        }

    }
    //*********************************数据处理********************************


    //*********************************析构函数************************************
    function __destruct(){

    }

}

?>