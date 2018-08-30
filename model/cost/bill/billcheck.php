<?php
class model_cost_bill_billcheck extends model_base
{

    public $page;
    public $num;
    public $start;
    public $db;
    public $comsta;
    public $glo; //公用类库

    //*******************************构造函数***********************************
    function __construct(){
        parent::__construct();
        $this->db = new mysql();
        $this->glo = new includes_class_global();
        $this->comsta = '完成';//财务录入
        $this->page = intval($_GET['page']) ? intval($_GET[page]) : 1;
        $this->start = ($this->page == 1) ? 0 : ($this->page - 1) * pagenum;
        $this->num = intval($_GET['num']) ? intval($_GET['num']) : false;
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
        $sqlSch = '';
        $formatSta = " '部门审批' "; //审批状态
        $powInfo = $this->glo->getPowerAccredit($_SESSION['USER_ID']);
        $powSql = '';
        if(!empty($powInfo)){
            foreach($powInfo as $val){
                $powSql .= " or find_in_set('".$val."' , s.user )>0 ";
            }
        }
        $sqlSch = " and ( find_in_set('".$_SESSION['USER_ID']."',s.user )>0 ".$powSql." )  ";
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

        $sql="select count(distinct l.BillNo) as ct
            from cost_summary_list l
                left join bill_list bl on (l.billno=bl.conbillno)
                left join wf_task t on (l.BillNo=t.name)
                left join flow_step_partent s on (t.task=s.Wf_task_ID)
            where l.billno=bl.conbillno
                and s.Flag='0' and s.Result=''
                and l.Status in ( $formatSta )
                $sqlSch ";
        $rs = $this->db->get_one($sql);
        $count = $rs['ct'];
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
                l.billno , bl.billno as costno
                , u.user_name , d.dept_name
                , x.name as proname , l.amount , l.inputdate as dt
                , l.status
            from cost_summary_list l
                left join user u on (l.costman=u.user_id)
                left join department d on (u.dept_id = d.dept_id)
                left join xm x on (l.projectno=x.projectno)
                left join bill_list bl on (l.billno=bl.conbillno)
                left join wf_task t on (l.BillNo=t.name)
                left join flow_step_partent s on (t.task=s.Wf_task_ID)
            where l.billno=bl.conbillno
                and s.Result=''  and s.Flag='0'
                and l.Status in ( $formatSta )
                $sqlSch
            group by l.billno
            order by $sidx $sord
            limit $start , $limit ";
        $this->pf($sql);
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $responce->rows[$i]['id'] = $row['billno'];
            $responce->rows[$i]['cell'] = un_iconv(
                array('' ,$row['billno']
                ,$row['billno']
                ,$row['costno']
                ,$row['user_name']
                ,$row['dept_name']
                ,$row['proname']
                ,$row['amount']
                ,$row['status']
                ,$row['dt']
                )
            );
            $i++;
            //$row['tallydt']
        }
        return json_encode($responce);
    }

    function model_bill_detail($flag='cost'){
        $key=$_GET['key'];
        $data=array();
        $datatype=array();
        $typeam=array();
        $billdata=array();
        $billtype=array();
        $billtypeam=array();
        $sql="(select
                a.place , a.costdatebegin , a.costdateend
                , d.costmoney , d.days
                , a.id as aid , d.id
                , t.costtypeid , t.costtypename , d.remark
            from
                cost_detail d
                left join cost_type t on (d.costtypeid=t.costtypeid)
                left join cost_detail_assistant a on (d.assid=a.id)
                left join cost_summary_list l on (a.billno=l.billno)
            where
                l.billno = '".$key."')
            union(
                select
                a.place , a.costdatebegin , a.costdateend
                , d.costmoney , d.days
                , a.id as aid , d.id
                , t.costtypeid , t.costtypename , d.remark
            from
                cost_detail_project d
                left join cost_type t on (d.costtypeid=t.costtypeid)
                left join cost_detail_assistant a on (d.assid=a.id)
                left join cost_summary_list l on (a.billno=l.billno)
            where
                l.billno = '".$key."'
                )";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $data[$row['aid']]['palce']=$row['place'];
            $data[$row['aid']]['dtb']=$row['costdatebegin'];
            $data[$row['aid']]['dte']=$row['costdateend'];
            $data[$row['aid']]['sam'][$row['id']]=$row['costmoney']*$row['days'];
            $data[$row['aid']]['am'][$row['id']]=$row['costmoney'];
            $data[$row['aid']]['days'][$row['id']]=$row['days'];
            $data[$row['aid']]['rmk'][$row['id']]=$row['remark'];
            $data[$row['aid']]['type'][$row['costtypeid']]=$row['id'];
            $datatype[$row['costtypeid']]=$row['costtypename'];
        }
        $sql="select
                d.amount as am , d.days
                , d.id , d.billdetailid as did
                , t.id as billtypeid , t.name as billtypename
            from
                bill_detail d
                left join bill_type t on (d.billtypeid=t.id)
                left join bill_list l on (d.billno=l.billno)
                left join cost_summary_list sl on (l.conbillno=sl.billno)
            where
                sl.billno = '".$key."' ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $billdata[$row['did']][$row['id']]['am']=$row['am'];
            $billdata[$row['did']][$row['id']]['days']=$row['days'];
            $billdata[$row['did']][$row['id']]['billtypeid']=$row['billtypeid'];
            $billdata[$row['did']][$row['id']]['billtypename']=$row['billtypename'];
            $billtype[$row['billtypeid']]=$row['billtypename'];
        }
        $res.='<tr style="background: #D3E5FA;text-align: center;height:21px;">
                    <td>序号</td>
                    <td>日期</td>
                    <td>地点</td>';
        if($datatype){
            foreach($datatype as $val){
                $res.='<td colspan="2">'.$val.'</td>';
            }
        }
        $res.='<td>小计</td></tr>';
        if($data){
            $i=0;
            foreach($data as $key=>$val){
                $dt=$val['dtb'];
                if($val['dtb']!=$val['dte']){
                    $dt.='~'.$val['dte'];
                }
                $i++;
                if ($i % 2 == 0) {
                    $res.='<tr style="background: #F3F3F3;height:18px;">';
                } else {
                    $res.='<tr style="background: #FFFFFF;height:18px;">';
                }
                $res.=' <td width="60" style="text-align: center;">'.$i.'</td>
                        <td width="100" style="text-align: center;">'.$dt.'</td>
                        <td width="100" style="text-align: center;">'.$val['place'].'</td>';
                foreach($datatype as $tkey=>$tval){
                    if(!empty($val['type'][$tkey])){
                        $did=$val['type'][$tkey];
                        $trid=$key.'-'.$did;
                        $typeam[$tkey]=isset($typeam[$tkey])?$val['sam'][$val['type'][$tkey]]+$typeam[$tkey]:$val['sam'][$val['type'][$tkey]];
                        $res.='<td width="80" id="cost-'.$trid.'"
                                title="'.$val['rmk'][$val['type'][$tkey]].'">'.$val['sam'][$val['type'][$tkey]].'</td>';
                        if(!empty($billdata[$did])){
                            $res.='<td width="120" >
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                        class="billTab" id="'.$trid.'" onclick="detailClick(\''.$trid.'\')"
                                        lable="'.$val['rmk'][$val['type'][$tkey]].'">';
                            $bi=0;
                            foreach($billdata[$did] as $bkey=>$bval){
                                $bi++;
                                if($bi==count($billdata[$did])){
                                    $res.='<tr >';
                                }else{
                                    $res.='<tr class="bill-tr" >';
                                }
                                $res.='
                                        <td style="text-align: left;color: blue;">'.$bval['billtypename'].'</td>
                                        <td style="text-align: right;color: green;">'.$bval['am'].'</td>
                                        </tr>';
                            }
                            $res.='</table></td>';
                        }
                    }else{
                        $res.='<td>-</td>';
                    }
                }
                $res.='<td >'.array_sum($val['sam']).'</td>';
                $res.='</tr>';
            }
            $res.='<tr style="background: #FFFFFF;height:18px;">
                    <td>合计：</td>
                    <td></td>
                    <td></td>';
            foreach($datatype as $key=>$val){
                $res.='<td colspan="2">'.$typeam[$key].'</td>';
            }
            $res.='<td>'.array_sum($typeam).'</td>
                   </tr> ';
        }
        $res='<table style="text-align: right;font-size: 12px; background: #666666;"
                width="'.(count($datatype)*200+340).'" cellpadding="0" cellspacing="1" border="0" >'.$res.'</table>';
        return $res;
    }

    function model_detail_info(){
        $id=$_POST['id'];
        $id=  explode('-', $id);
        $did=$id[1];
        $aid=$id[0];
        $billType=$this->model_bill_type();
        try{
            if(empty($did)){
                throw new Exception('数据读取失败！');
            }else{
                $sql="select
                        t.id as typeid , d.amount , d.id
                    from
                        bill_detail d
                        left join bill_type t on (d.billtypeid=t.id)
                    where
                        d.billdetailid='".$did."' and d.billassid='".$aid."' ";
                $query=$this->db->query($sql);
                $i=0;
                while($row=$this->db->fetch_array($query)){
                    $i++;
                    $msg.='<tr style="text-align: center;">
                            <td>
                                <select name="detail_type['.$row['id'].']" id="detail_type" class="detail_type" >
                                    '.$this->set_html($billType, 'select', $row['typeid']).'
                                </select>
                            </td>
                            <td>
                                <input type="text" name="detail_am['.$row['id'].']"
                                    id="detail_am" value="'.$row['amount'].'" class="detail_am"
                                    style="width:70px;" />
                            </td>
                            </tr>';
                }
            }
        }catch(Exception $e){
            $msg=$e->getMessage();
        }
        return un_iconv($msg);
    }
    function model_detail_change(){
        //var_dump($_POST);
        $_POST=mb_iconv($_POST);
        $isproflag=$_POST['cIsPro'];
        $addassid=$_POST['addassid'];
        $newtype=$_POST['newTypeID'];
        $changedate=$_POST['changeDate'];
        $changebillno=$_POST['cBillno'];
        $chageid=$_POST['changeId'];
        $changermk=$_POST['cremark'];
        $changeam=round($_POST['changeAmount'],2);
        $detailAm=$_POST['detail_am'];
        $detailType=$_POST['detail_type'];
        $detailTypeAdd=$_POST['detail_type_add'];
        $detailAmAdd=$_POST['detail_am_add'];
        $costid=$_POST['aiddid'];
        $costid=explode('-', $costid);
        $did=$costid[1];
        $aid=$costid[0];
        $msg='';
        $billdb='cost_detail';
        if($isproflag=='1'){
            $billdb='cost_detail_project';
        }
        if(empty($did)){
            $msg='更新失败！';
        }
        try {
            $this->db->query("START TRANSACTION");
            if(empty($addassid)){//修改
                $upkey='';
                if($detailAm){
                    foreach($detailAm as $key=>$val){
                        $upkey=$key;
                        if($val=='0'){
                            $sql="delete from bill_detail where id='".$key."' ";
                        }else{
                            $sql="update bill_detail set billtypeid='".$detailType[$key]."' , amount ='".$val."' , days='".$changedate."' where id='".$key."' ";
                        }
                        $this->db->query($sql);
                    }
                }
                if($changeam==0){
                    $sql="delete from $billdb where id='".$did."' ";
                }else{
                    $sql="update $billdb
                    set remark='".$changermk."'
                       , costmoney='".$changeam."'
                    where id='".$did."' ";
                }
                $this->db->query($sql);
                if(!empty($detailAmAdd)){
                    foreach($detailAmAdd as $key=>$val){
                        if(!empty($val)){
                            $sql=" insert into bill_detail
                                    ( billtypeid , amount , billno
                                        , billdetailid , billassid , days )
                                  values
                                    ( '".$detailTypeAdd[$key]."' , '".$val."', '".$changebillno."'
                                        , '".$did."' ,'".$aid."' ,'".$changedate."' )
                                    ";
                            $this->db->query($sql);
                        }
                    }
                }
            }else{
                if(!empty($changeam)){
                    $sql=" insert into $billdb
                            (  headid , rno , costtypeid , costmoney , days , remark , billno , assid
                                )
                       select  headid , rno , '".$newtype."' , '".$changeam."' , '".$changedate."' , '".$changermk."' , billno , assid

                           from $billdb where assid ='".$addassid."' limit 0,1  ";
                    $this->db->query($sql);
                    $idid=$this->db->insert_id();
                    if(!empty($detailAmAdd)){
                        foreach($detailAmAdd as $key=>$val){
                            if(!empty($val)){
                                $sql=" insert into bill_detail
                                        ( billtypeid , amount , billno
                                                , billdetailid , billassid , days)
                                       values
                                        ( '".$detailTypeAdd[$key]."' , '".$val."' , '".$changebillno."'
                                                , '".$idid."' , '".$addassid."' ,'".$changedate."' )
                                        ";
                                $this->db->query($sql);
                            }
                        }
                    }
                }
            }
            //更新cost_summary_list
            $sql="select sum(costmoney*d.days) as newam
                from $billdb d
                    left join  cost_detail_assistant a on ( d.assid= a.id )
                where
                    a.billno='".$changebillno."' ";
            $res=$this->db->get_one($sql);
            $newam=$res['newam'];
            $sql="update cost_summary_list set amount='".$newam."' where billno='".$changebillno."' ";
            $this->db->query($sql);
            $sql="select checkamount , amount from cost_summary_list where billno='".$changebillno."' ";
            $res=$this->db->get_one($sql);
            /*
            if($res['checkamount']!=$res['amount']){
                $sql="update cost_detail_assistant set Status='部门修改' where BillNo='".$changebillno."' and Status='部门' ";
            }else{
                $sql="update cost_detail_assistant set Status='部门' where BillNo='".$changebillno."' and Status='部门修改' ";
            }*/
            $sql="update cost_detail_assistant set Status='部门' where BillNo='".$changebillno."' and Status='部门修改' ";
            $this->db->query($sql);

            $this->db->query("COMMIT");
            $this->glo->insertOperateLog('类型', '对象', '内容', '成功', '错误信息');
            $msg='1';
        } catch (Exception $e) {
            $msg=$e->getMessage();
            $this->db->query("ROLLBACK");
            $this->glo->insertOperateLog('类型', '对象', '内容', '失败', '错误信息');
        }
        return un_iconv($msg);
    }
    /**
     *删除
     * @return <type>
     */
    function model_detail_del(){
        $costid=$_POST['aiddid'];
        $costid=explode('-', $costid);
        $did=$costid[1];
        $aid=$costid[0];
        try {
            $this->db->query("START TRANSACTION");

            $sql="delete from cost_detail_project where id='".$did."' ";
            $this->db->query($sql);
            $sql="delete from bill_detail where billdetailid='".$did."' and billassid='".$aid."' ";
            $this->db->query($sql);
            //更新cost_summary_list
            $sql="select billno from cost_detail_assistant where id='".$aid."' ";
            $res_bill=$this->db->get_one($sql);
            $rbill=$res_bill['billno'];
            $sql="select sum(costmoney*d.days) as newam
                from cost_detail_project d
                    left join  cost_detail_assistant a on ( d.assid= a.id )
                where
                    a.billno='".$rbill."' ";
            $res=$this->db->get_one($sql);
            $newam=$res['newam'];
            $sql="update cost_summary_list set amount='".$newam."' where billno='".$rbill."' ";
            $this->db->query($sql);
            $this->db->query("COMMIT");
            $this->glo->insertOperateLog('类型', '对象', '内容', '成功', $sql);
            $msg='1';
        } catch (Exception $e) {
            $msg=$e->getMessage();
            $this->db->query("ROLLBACK");
            $this->glo->insertOperateLog('类型', '对象', '内容', '失败', '错误信息');
        }
        return $msg='1';
    }
    /**
     *
     */
    function model_payee_ch(){
        $_POST=mb_iconv($_POST);
        $payee=$_POST['payee'];
        $payee = strstr($payee, '(', true);
        $billno=$_POST['billno'];
        $msg;
        if(!empty($payee)){
            try {
                $sql="update cost_summary_list set payee='".$payee."' where billno='".$billno."' ";
                $this->db->query($sql);
                $msg=1;
            } catch (Exception $exc) {
                $msg=$exc->getMessage();
            }
        }
        return $msg;
    }

    /**
     * 更新打单信息修改记录
     * @param $billNo
     * @param $changeField
     * @param $newVal
     * @param $oldVal
     * @return bool
     */
    function updateChangeBillInfoRecord($billNo,$changeField,$newVal,$oldVal){
        $chkSql = "select * from oa_billcheck_change_records where billNo = '{$billNo}';";
        $changeRecord = $this->db->get_one($chkSql);
        $updateDataSql = "";
        switch ($changeField){
            case "mainAccount":
                $updateDataSql = " oldAccountVal = '{$oldVal}',newAccountVal = '{$newVal}',";
                break;
            case "mainAcccard":
                $updateDataSql = " oldAccountCardVal = '{$oldVal}',newAccountCardVal = '{$newVal}',";
                break;
            case "payee":
                $updateDataSql = " oldPayeeVal = '{$oldVal}',newPayeeVal = '{$newVal}',";
                break;
        }

        $todayTime = date("Y-m-d H:i:s");
        if($changeRecord){// 已存在记录, 更新相关信息
            $flag = $this->db->query("update oa_billcheck_change_records set {$updateDataSql} lastChangeUserId = '{$_SESSION['USER_ID']}',lastChangeTime = '{$todayTime}' where id = '{$changeRecord['id']}'");
        }else{// 无记录,添加记录
            $flag = $this->db->query("insert into oa_billcheck_change_records set billNo = '{$billNo}', {$updateDataSql} createUserId='{$_SESSION['USER_ID']}',createTime='{$todayTime}',lastChangeUserId = '{$_SESSION['USER_ID']}',lastChangeTime = '{$todayTime}';");
        }
        return $flag;
    }

    /**
     * 打印数据
     */
    function model_print_info(){
        $detailTypeArr = array(
            '0' => '差费',
            '1' => '部门费用',
            '2' => '合同项目费用',
            '3' => '研发费用',
            '4' => '售前费用',
            '5' => '售后费用'
        );
        $digit2chinese=new includes_class_digit2chinese();
        $billno=$_GET['billno'];
        $billinfo=array();
        $sql="select
                u.user_name , d.dept_name , l.costdates,d.dept_id,d.parent_id,d.MajorId
                , x.name as proname , x.projectno
                , x.city , min(a.costdatebegin) as bdt
                , max(a.costdateend) as edt , l.payee , l.isNew
                , h.account , h.usercard , h.acccard
                , isproject , l.amount  , if( l.purpose is null ,  l.CostClientType ,l.purpose ) as CostClientType , l.projectno as lpro , l.projectname
                , if( l.province is null ,  l.costclientarea ,l.province ) as cca
                , l.CostBelongtoDeptIds as code,l.chanceCode,l.chanceName
                , l.detailType , l.isproject,l.DetailType,l.contractCode
            from cost_summary_list l
                left join user u on (l.costman=u.user_id)
                left join hrms h on (h.user_id=u.user_id)
                left join department d on (d.dept_id = u.dept_id )
                left join xm x on (x.projectno=l.projectno)
                left join cost_detail_assistant a on (l.billno=a.billno)
            where l.billno='".$billno."'
                group by l.billno ";
        $billrow=$this->db->get_one($sql);

        $ispro=$billrow['isproject'];
        if($ispro==1){
            $billdb='cost_detail_project';
        }else{
            $billdb='cost_detail';
            if($billrow['cca']!='其他')
                $billrow['city']=$billrow['cca'];
        }
        $type=$this->model_bill_type();
        $typer=array_flip($type);
        $typebill=array();
        $lvtype=array();
        $billinfoam=0;
        $sql="select
                t.id as typeid , sum(d.amount*d.days) as sm , d.id
                , t.name as tn
            from
                bill_detail d
                left join bill_type t on (d.billtypeid=t.id)
                left join cost_detail_assistant a on (d.billassid=a.id)
                LEFT JOIN cost_summary_list cl on cl.BillNo = a.BillNo
            where
                a.billno='".$billno."' and t.id is not null 
                AND if(cl.isNew = 1,(d.BillNo IS NOT NULL AND d.BillNo <> '' AND d.BillNo = a.BillNo),1)
                group by t.id";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $billinfo[$row['typeid']]=round($row['sm'],2);
            $billinfoam=round($billinfoam+$row['sm'],2);
            $typebill[$row['tn']]=$row['typeid'];
        }
        $type=array_merge($typebill,$typer);
        $payee=$billrow['payee'];
        if(empty($payee)){
            $payee=$billrow['user_name'].'('.$billrow['usercard'].')';
        }

        // 租车登记汇总生成的报销单（报销司机类型）打单时候需要特殊处理 2017-11-09 PMS 388
        $rentalCarPayInfo = $this->db->get_one("SELECT
                pt.payType,pt.payTypeCode,pt.bankName,pt.bankAccount,pt.bankReceiver
            FROM
                cost_summary_list l
            LEFT JOIN oa_contract_rentcar_expensetmp et ON l.id = et.expenseId
            LEFT JOIN oa_contract_rentcar_payinfos pt ON et.payInfoId = pt.id
            WHERE
	          l.billNo = '{$billno}';");
        if($rentalCarPayInfo && $rentalCarPayInfo['payTypeCode'] == "BXFSJ"){
            $billrow['account'] = $rentalCarPayInfo['bankAccount'];
            $billrow['acccard'] = $rentalCarPayInfo['bankName'];
            $billrow['city'] = $payee;
            $payee = $rentalCarPayInfo['bankReceiver'];
        }

        // 读取修改记录, 如果有替换最新一次修改的值
        $chkSql = "select * from oa_billcheck_change_records where billNo = '{$billno}';";
        $billrowChangeRcd=$this->db->get_one($chkSql);
        if($billrowChangeRcd){
            $payeeOrg = $payee;
            $billrow['accountOrg'] = $billrow['account'];
            $billrow['acccardOrg'] = $billrow['acccard'];
            $billrow['payeeHasChangeTip'] = (!empty($billrowChangeRcd['newPayeeVal']) && $billrowChangeRcd['newPayeeVal'] !== $payee)? "1" : "";
            $billrow['accountHasChangeTip'] = (!empty($billrowChangeRcd['newAccountVal']) && $billrowChangeRcd['newAccountVal'] !== $billrow['account'])? "1" : "";
            $billrow['acccardHasChangeTip'] = (!empty($billrowChangeRcd['newAccountCardVal']) && $billrowChangeRcd['newAccountCardVal'] !== $billrow['acccard'])? "1" : "";
            $billrow['payee'] = empty($billrowChangeRcd['newPayeeVal'])? $payee : $billrowChangeRcd['newPayeeVal'];// 账户姓名
            $payee = $billrow['payee'];
            $billrow['account'] = empty($billrowChangeRcd['newAccountVal'])? $billrow['account'] : $billrowChangeRcd['newAccountVal'];// 账户
            $billrow['acccard'] = empty($billrowChangeRcd['newAccountCardVal'])? $billrow['acccard'] : $billrowChangeRcd['newAccountCardVal'];// 账户卡号(银行)
        }

        $sql="";
        if(round($billinfoam,2)==round($billrow['amount'],2)){
            if($billdb=='cost_detail'){
                if($billrow['DetailType']=='4'){
                    //if($billrow['chanceCode']){
                    //$PKI=$this->model_get_saleChance($billrow['chanceCode']);
                    //}
                    if($billrow['projectno']){
                        $projectName='项目名称';
                        $projectValue=$billrow['projectno'].'<br/>'.$billrow['projectname'];
                    }else{
                        $projectName='商机号';
                        $projectValue=$billrow['chanceCode'].'<br/>'.$billrow['chanceName'];
                    }
                }else if($billrow['DetailType']=='5'){
                    $projectName='合同号';
                    $projectValue=$billrow['contractCode'];
                }else{
                    $projectName='研发项目 ';
                    $arr=explode('-',$billrow['projectname']) ;
                    $projectname=$arr[0];
                    $projectValue=$billrow['lpro'].'<br/>'.$projectname;
                }
                $res='<tr class="trall trtop">
                <td width="13%" style="border: 1px solid #000000; ">姓名</td><td class="indt" width="37%" style="border: 1px solid #000000; ">
                    <input type="text" class="pInput changeLimitItem" style="width:99%;border: 0px;" name="payee" id="payee" changeTip="'.$billrow['payeeHasChangeTip'].'" data-orgVal="'.$payeeOrg.'" data-lastChangeVal="'.$payee.'" value="'.$payee.'" />
                    <input type="hidden" name="username" id="username" value="'.$payee.'" />
                </td>
                <td width="15%" style="border: 1px solid #000000; ">部门</td><td class="indt" style="border: 1px solid #000000;font-size:12px ">
                '.$billrow['code'].'('.$billno.')</td>
            </tr>
            <tr class="trall trtop">
                <td style="border: 1px solid #000000; ">
                事由
                </td>
                <td class="indt" style="border: 1px solid #000000; ">
                <input type="text" class="pInput" style="width:99%;border: 0px;" name="payee" id="payee"
                 value="'.$billrow['CostClientType'].'" /></td>
                <td style="border: 1px solid #000000; ">
                '.$projectName.'
                </td>
                <td class="indt" style="border: 1px solid #000000;font-size:9px; word-wrap:break-word;word-break:break-all;">
                '.$projectValue.'
                </td>
            </tr>
            <tr class="trall trtop">
                <td style="border: 1px solid #000000; ">日期</td><td class="indt" style="border: 1px solid #000000; ">
                    '.(($billrow['bdt']==$billrow['edt'])?$billrow['bdt']:($billrow['bdt'].'~'.$billrow['edt'])).'
                </td>
                <td style="border: 1px solid #000000; ">地点</td><td class="indt" style="border: 1px solid #000000; ">
                  '.$billrow['city'].' </td>
            </tr>
            <tr >
                <td colspan="4" class="intable" style="padding:0px;border: 1px solid #000000; ">
                    <table align="center"  class="form_main_table"  style="padding:0px;width:100%">
                        ';
                $i=0;
                $trtop='';
                $tr='';
                $rows=10;
                $costam=0;
                if($type){
                    $tii=1;
                    foreach($type as $val=>$key){
                        if($tii>31){
                            break;
                        }
                        if(!empty($billinfo[$key])){
                            $costam=round($costam+$billinfo[$key],2);
                        }
                        if($i%$rows==0){
                            if($i!=0){
                                $trtop.='</tr>';
                                $tr.='</tr>';
                                $res.=$trtop.$tr;
                            }
                            $trtop='<tr>';
                            $tr='<tr>';
                        }
                        $trtop.='<td style="border: 1px solid #000000; padding:0px;height:25px;">'.$val.'</td>';
                        $tr.='<td style="border: 1px solid #000000; padding:0px;height:25px;">
                            '.(empty($billinfo[$key])?'&nbsp;': '<span class="formatMoney">'.$billinfo[$key]).'</span>'
                            .'</td>';
                        $i++;
                        if($i==count($type)){
                            $trtop.='</tr>';
                            $tr.='</tr>';
                            $res.=$trtop.$tr;
                        }
                        $tii++;
                    }
                }
                $ci=7-ceil(count($type)/$rows)*2;
                for($y=0;$y<$ci;$y++){
                    $res.='<tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>';
                }
                $gl = new includes_class_global();
                $digit2chinese->num = round($costam,2);
                $digit2chinese->chuli();
                $res.='
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="intable" style="padding:0px;border: 1px solid #000000; " class="form_main_table">
                    <table align="center"  class="form_main_table" style="padding:0px;width:100%">
                        <tr>
                            <td width="15%" style="border: 1px solid #000000; ">合计</td>
                            <td align="left" width="50%" style="padding-right:25px;letter-spacing:10px;border: 1px solid #000000;">'.$gl->num2Upper($costam).'</td>
                            <td width="15%" style="border: 1px solid #000000; ">小写</td>
                            <td align="right" style="padding-right:5px;">
                            <span class="formatMoney">'.($costam).'</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>';
            }else{
                $res='<tr class="trall trtop">
                <td width="13%" style="border: 1px solid #000000; ">姓名</td><td class="indt" width="37%" style="border: 1px solid #000000; ">
                    <input type="text" class="pInput" class="changeLimitItem" style="width:99%;border: 0px;" name="payee" id="payee" changeTip="'.$billrow['payeeHasChangeTip'].'" data-orgVal="'.$payeeOrg.'" data-lastChangeVal="'.$payee.'" value="'.$payee.'" />
                    <input type="hidden" name="username" id="username" value="'.$payee.'" />
                </td>
                <td width="15%" style="border: 1px solid #000000; ">部门</td><td class="indt" style="border: 1px solid #000000; ">
                <input type="text" class="indt pInput" style="width:99%;border: 0px;" value="工程项目'.'（'.$billno.'）" /></td>
            </tr>
            <tr class="trall trtop">
                <td style="border: 1px solid #000000; ">
                <input type="text" class="pInput" style="width:99%;border: 0px;" value="项目名称" />
                </td>
                <td class="indt" style="border: 1px solid #000000; ">
                <input type="text" class="indt pInput" style="width:99%;border: 0px;" value="'.$billrow['proname'].'" /></td>
                <td style="border: 1px solid #000000; ">
                <input type="text"  class="pInput" style="width:99%;border: 0px;" value="项目编号" />
                </td>
                <td class="indt" style="border: 1px solid #000000; ">
                <input type="text" class="indt pInput" style="width:99%;border: 0px;" value="'.$billrow['projectno'].'" /></td>
            </tr>
            <tr class="trall trtop">
                <td style="border: 1px solid #000000; ">日期</td><td class="indt" style="border: 1px solid #000000; ">
                    <input type="text" class="indt pInput" style="width:99%;border: 0px;" value="'.(($billrow['bdt']==$billrow['edt'])?$billrow['bdt']:($billrow['bdt'].'~'.$billrow['edt'])).'" />
                </td>
                <td style="border: 1px solid #000000; ">地点</td><td class="indt" style="border: 1px solid #000000; ">
                <input type="text" class="indt pInput" style="width:99%;border: 0px;" value="'.$billrow['city'].'" /></td>
            </tr>
            <tr style="padding:0px;">
                <td colspan="4" style="padding:0px;border: 1px solid #000000; " >
                    <table align="center"  class="form_main_table"  style="padding:0px;width:100%">
                        ';
                $i=0;
                $trtop='';
                $tr='';
                $rows=10;
                $costam=0;
                if($type){
                    $tii=1;
                    foreach($type as $val=>$key){
                        if($tii>31){
                            break;
                        }
                        if(!empty($billinfo[$key])){
                            $costam=round($costam+$billinfo[$key],2);
                        }
                        if($i%$rows==0){
                            if($i!=0){
                                $trtop.='</tr>';
                                $tr.='</tr>';
                                $res.=$trtop.$tr;
                            }
                            $trtop='<tr>';
                            $tr='<tr>';
                        }
                        $trtop.='<td style="border: 1px solid #000000; padding:0px;height:25px;">'.$val.'</td>';
                        $tr.='<td style="border: 1px solid #000000; padding:0px;height:25px;">'.(empty($billinfo[$key])?'&nbsp;':'<span class="formatMoney">'.$billinfo[$key]).'</span>'.'</td>';
                        $i++;
                        if($i==count($type)){
                            $trtop.='</tr>';
                            $tr.='</tr>';
                            $res.=$trtop.$tr;
                        }
                        $tii++;
                    }
                }
                $ci=7-ceil(count($type)/$rows)*2;
                for($y=0;$y<$ci;$y++){
                    $res.='<tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>';
                }
                $digit2chinese->num = $costam;
                $digit2chinese->chuli();
                $res.='
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="intable" style="padding:0px;border: 1px solid #000000; " class="form_main_table">
                    <table align="center"  class="form_main_table" style="padding:0px;width:100%">
                        <tr>
                            <td width="15%" style="border: 1px solid #000000; ">合计</td>
                            <td align="left" width="50%" style="padding:5px;letter-spacing:10px;border: 1px solid #000000; padding-right:25px;">'.$digit2chinese->huey_return().'</td>
                            <td width="15%" style="border: 1px solid #000000; ">小写</td>
                            <td width="20%" align="right" style="padding:5px;border: 1px solid #000000; "><span class="formatMoney">'.($costam).'</span></td>
                        </tr>
                    </table>
                </td>
            </tr>';
            }
        }else{
            $res='err';
        }
        if($billrow['isproject']=='1'){
            $re['ty']=$detailTypeArr[2];
        }else{
            $re['ty']=$detailTypeArr[$billrow['detailType']];
        }

        $re['tb']=$res;
        $re['ac']='<input type="text" class="pInput changeLimitItem" style="width:100px;border: 0px solid #000000; " id="mainAccount" changeTip="'.$billrow['accountHasChangeTip'].'" data-orgVal="'.$billrow['accountOrg'].'" data-lastChangeVal="'.$billrow['account'].'" value="'.$billrow['account'].'" /> 
        - <input type="text" class="pInput changeLimitItem" style="width:150px ;border: 0px solid #000000; " id="mainAcccard" changeTip="'.$billrow['acccardHasChangeTip'].'" data-orgVal="'.$billrow['acccardOrg'].'" data-lastChangeVal="'.$billrow['acccard'].'" value="'.$billrow['acccard'].'" />';

        if($billno){
            $sqlStr="SELECT f.USER_NAME FROM flow_step_partent d,`user` f
					WHERE  f.USER_ID=REPLACE(d.User,',','')
					AND d.StepID=(SELECT  MAX(b.ID) FROM flow_step a, flow_step b
												WHERE (a.Item='会计审核' OR  a.Item='财务会计'  ) AND a.StepID-1=b.StepID
												AND a.Wf_task_ID=b.Wf_task_ID AND a.Wf_task_ID =(
															SELECT MAX(w.task)
															FROM wf_task w ,cost_summary_list s
															WHERE s.BillNo='$billno' AND (s.ID=w.Pid OR w.name='$billno')
						)
					) ";
            $appI=$this->db->get_one($sqlStr);
            if(!$appI['USER_NAME']&&$billrow['MajorId']){
                $appI=$this->db->getArray("SELECT USER_NAME from user where FIND_IN_SET(user_id,'".trim($billrow['MajorId'],',')."')");
            }
            $re['appName']=implode(',',(array)($appI['USER_NAME']?$appI['USER_NAME']:$appI[0]));
        }



        return $re;
    }
    /**
     *
     */
    function model_bill_type(){
        $res=array();
        $sql="select id , name from bill_type where 1 and id<>'1' and typeflag='1' ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $res[$row['id']]=$row['name'];
        }
        return $res;
    }

    function set_html($data , $type ,$sd=null ){
        $res='';
        if($type=='select'){
            if($data){
                foreach($data as $key=>$val){
                    if($sd==$key){
                        $res.='<option value="'.$key.'" selected>'.$val.'</option>';
                    }else{
                        $res.='<option value="'.$key.'">'.$val.'</option>';
                    }
                }
            }
        }
        return $res;
    }


    function model_get_exeDept($prodectNo){
        $res='';
        if($prodectNo){
            $sql="SELECT b.dataName FROM `oa_esm_project` a
				  LEFT JOIN oa_system_datadict b  ON a.productLine=b.dataCode
				  WHERE a.projectCode='$prodectNo' ";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                $res=$row['dataName'];
            }
        }
        return $res;
    }

    function model_get_saleChance($chanceCode){
        $res='';
        if($chanceCode){
            $sql="SELECT b.projectCode,b.projectName FROM oa_sale_chance  a
				  LEFT JOIN oa_trialproject_trialproject b ON a.chanceCode=b.chanceCode
                  WHERE  a.chanceCode='$chanceCode' ";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                $res['projectCode']=$row['projectCode'];
                $res['projectName']=$row['projectName'];
            }
        }
        return $res;
    }

    //*********************************数据处理********************************


    //*********************************析构函数************************************
    function __destruct(){

    }

}

?>