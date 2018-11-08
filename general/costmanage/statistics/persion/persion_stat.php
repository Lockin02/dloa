<?php
include( "../../../../includes/db.inc.php" );
include( "../../../../includes/config.php" );
include( "../../../../includes/msql.php" );
include( "../../../../includes/fsql.php" );
include( "../../../../includes/util.php" );
include( "../../../../includes/getUSER_DEPT_ID.php" );
include( "../../../../includes/common.inc.php" );
//print_r($_REQUEST);
$seaDateB=isset($seaDateB)?$seaDateB:date("Y-m")."-01";
$seaDateE=isset($seaDateE)?$seaDateE:"";
$row=0;
?>
<html>
<head>
<title>报销记录查询</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" type="text/css" href="../../../../inc/style.css">
<link rel="stylesheet" type="text/css" href="../project/ajax_search/sea.css">
<script src="../../../../DatePicker/WdatePicker.js" language="javascript"></script>
<link rel="stylesheet" type="text/css" href="../../../../css/nav_tab.css">
<script src="../../../../js/validate.js" language="javascript"></script> 
<script src="../project/ajax_search/sea.js" type="text/javascript"></script>
<script language="javascript">
function exToExcel()
{
    var elTable = document.getElementById("tbData"); //要导出的table id。
    var oRangeRef = document.body.createTextRange(); 
    oRangeRef.moveToElementText(elTable); 
    oRangeRef.execCommand("Copy");
    var appExcel = new ActiveXObject("Excel.Application");
    appExcel.Workbooks.Add().Worksheets.Item(1).Paste(); 
    appExcel.Visible = true; 
    appExcel = null;
}
function showDetail(pn)
{
  URL="./index_user.php?back=close&pn="+pn+"&db=<?php echo $seaDateB;?>&de=<?php echo $seaDateE;?>";    
  window.open(URL,"read_exam_stat","width=950,height=680,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=150,resizable=yes");  
}
function checkForm(){
    var sdb = document.getElementById("seaDateB");
    if(sdb.value!=""){
        if(!checkDT(sdb.value)){
            alert("请输入正确的开始日期和日期格式：2009-10-11 或 2009/10/11");
            return false;
        }
    }else{
        alert("查询的开始日期不能为空");
        return false;
    }
    var sde = document.getElementById("seaDateE");
    if(sde.value!=""){
        if(!checkDT(sde.value)){
            alert("请输入正确的结束日期和日期格式：2009-10-11 或 2009/10/11");
            return false; 
        }
    }
    return true;
}
function changeSelect(){
    var selB=document.getElementById("qrAreaB");
    var selE=document.getElementById("qrAreaE");
    var checkArr=new Array("项目经理汇总","部门检查","部门审批","财务审核","出纳");
    var seli=selB.selectedIndex;
    var newC=checkArr.slice(seli);
    selE.length=0;
    var str="";
    for(var i=0;i<newC.length;i++)
    {
        var opt = document.createElement("option");
        if(i==0)
            opt.setAttribute("selected","selected");
        opt.setAttribute("value",newC[i]);
        var txt = document.createTextNode(newC[i]);
        opt.appendChild(txt);
        selE.appendChild(opt);
    }
}
function show(obj,b){
    var s=obj.lable;
    var span=document.getElementById("title_pad");
    var ifTop=1;
    try{
        if(window.parent.frames("ifsummary")!=null)
            ifTop=window.parent.frames("ifsummary").document.body.scrollTop;
    }catch(e){
        
    }
    span.style.left=event.x+10+document.body.scrollLeft;
    span.style.top=event.y+ifTop+document.body.scrollTop;
    span.innerHTML=s;
    if (b)
        span.style.display="block";
    else
        span.style.display="none";
}
function showWin(id)
    {
        var myurl="../../query/cost/summary_detail.php?BillNo="+id;
        var myleft=(screen.availWidth-500)/2;
        window.open(myurl,"","height=700,width=800,status=yes,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");   
    }
</script>
</head>
<body class="bodycolor" topmargin="5">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" style="margin-bottom:5px;">
  <tr>
    <td class="Big" ><img src="../../../../images/menu/system.gif" WIDTH="22" HEIGHT="20"><b><font color="#000000"> 统计结果</font>
    </td>
  </tr>
</table>
<table border="0"  width="100%"  class="small" cellpadding="3"  cellspacing="1" bgcolor="#EFF7FF" align="center" id="tabsMT">
  <TBODY>
  <TR class="TableControl">
    <TD height="23">
  <form name="form1" method="get" action="" onsubmit="return checkForm();">
    统计日期：
    <input type="text" name="seaDateB" size="12" maxlength="12"  value='<?php echo $seaDateB;?>' class="BigInput" >
    <img src="../../../../images/menu/calendar.gif" border="0" style="cursor:hand" onclick="WdatePicker({el:$dp.$('seaDateB')})">
    至：
    <input type="text" name="seaDateE" size="12" maxlength="12" value='<?php echo $seaDateE;?>'  class="BigInput" >
    <img  src="../../../../images/menu/calendar.gif" border="0" style="cursor:hand" onclick="WdatePicker({el:$dp.$('seaDateE')})">
    &nbsp;&nbsp;
    <input type="submit" name="subbtn" value=" 查  询 " class="BigInput" >
    &nbsp;
    <input type="button" id="exportbtn" value="Excel导出" onclick="exToExcel();"  class="BigInput">
  </form>
    </TD>
  </TR>
  </TBODY>
</table>
<table border="0"  width="100%"  class="small" cellpadding="3"  cellspacing="1" bgcolor="#EFF7FF" align="center" id="tbData">
  <TBODY>
  <TR>
    <TD>
<?php
    $checkUserId=$USER_ID;
    $loanSql="";
    $paySql="";
    $costSql="";
    $borrowSql="";
    if($seaDateB!=""){
        $loanSql.=" and TO_DAYS(PayDT) >= TO_DAYS('$seaDateB') ";
        $paySql.=" and TO_DAYS(r.CreateDT) >= TO_DAYS('$seaDateB') ";
        $costSql.=" and TO_DAYS(PayDT) >= TO_DAYS('$seaDateB') ";
        $borrowSql.=" and TO_DAYS(l.telledt) >= TO_DAYS('$seaDateB')";
    }
    if($seaDateE!=""){
        $loanSql.=" and TO_DAYS(PayDT) <= TO_DAYS('$seaDateE') ";
        $paySql.=" and TO_DAYS(r.CreateDT) <= TO_DAYS('$seaDateE') ";
        $costSql.=" and TO_DAYS(PayDT) <= TO_DAYS('$seaDateE') ";
        $borrowSql.=" and TO_DAYS(l.telledt) <= TO_DAYS('$seaDateE')";
    }
    //上月借款余额=已支付状态+还款中的金额
    $sql="select sum(Amount) as rm  from loan_list  where Debtor='$checkUserId' and  Status in ('已支付','还款中','已还款') and isTemp =0 and TO_DAYS(PayDT) < TO_DAYS('$seaDateB')  ";
    $msql->query($sql);
    $msql->next_record();
    $preLoanAm=$msql->f("rm");
    $sql="select sum(r.Money) as rm  from loan_list l , loan_repayment r   where  l.Debtor='$checkUserId' and l.ID=r.loan_ID  and l.Status in('还款中','已还款' ) and l.isTemp = 0 and TO_DAYS(r.CreateDT) < TO_DAYS('$seaDateB')  ";
    $msql->query($sql);
    $msql->next_record();
    $prePayLoanAm=$msql->f("rm");
    $preLeaveLoanAm=($preLoanAm*1000-$prePayLoanAm*1000)/1000; //上期借款余额
    //本期借款
    $sql="select sum(Amount) as rm from loan_list  where Debtor='$checkUserId' and Status in ('已支付','还款中','已还款') and isTemp = 0 $loanSql ";
    $msql->query($sql);
    $msql->next_record();
    $nowLoanAm=$msql->f("rm");
    //本月还款=已还款和还款中的冲销还款+现金还款
    //way=0 冲销
    $sql="select sum(r.Money) as rm  from  loan_list l, loan_repayment r  where l.Debtor='$checkUserId'
    and l.ID=r.loan_ID and  l.Status in ('还款中','已还款') and l.isTemp = 0 and way=0 $paySql ";
    $msql->query($sql);
    $msql->next_record();
    $nowPayLoanAmC=$msql->f("rm");
    $sql="select sum(r.Money) as rm  from  loan_list l, loan_repayment r  where l.Debtor='$checkUserId'
    and l.ID=r.loan_ID and  l.Status in ('还款中','已还款') and l.isTemp = 0 and way=1 $paySql ";
    $msql->query($sql);
    $msql->next_record();
    $nowPayLoanAmX=$msql->f("rm");
    $sql="select sum(r.Money) as rm  from  loan_list l, loan_repayment r  where l.Debtor='$checkUserId'
    and l.ID=r.loan_ID and  l.Status in ('还款中','已还款') and l.isTemp = 0 and way=2 $paySql ";
    $msql->query($sql);
    $msql->next_record();
    $nowPayLoanAmQ=$msql->f("rm");
    //本期报销收入
    $sql="select sum(PayMoney) as pm  from cost_pay  where UserId='$checkUserId' and PayType='银行支付' $costSql ";
    $msql->query($sql);
    $msql->next_record();
    $nowIncomeCostY=$msql->f("pm");
    $sql="select sum(PayMoney) as pm  from cost_pay  where UserId='$checkUserId' and PayType='现金支付' $costSql ";
    $msql->query($sql);
    $msql->next_record();
    $nowIncomeCostX=$msql->f("pm");
    //挂靠
    $sql="select sum(l.amount) as bm  from borrow_list l where l.userid='$checkUserId'  $borrowSql  ";
    $msql->query($sql);
    $msql->next_record();
    $nowIncomeBorrow=$msql->f("bm");
?>
    <table border='1'bordercolor="#000000" bordercolordark="#FFFFFF" bgcolor="#FFFFFF" class='small' cellspacing='0' width='100%'  cellpadding='0' >  
    <tr class="TableHeader2" >
        <td  align="center" rowspan="3" width="12.5%">
            总计：
        </td>
        <td  align="center" rowspan="2">
            上期借款余额
        </td>
        <td  align="center" rowspan="2">
            本期借款
        </td>
        <td  align="center" colspan="3">
            本期还款
        </td>
        <td  align="center" rowspan="2">
            本期借款余额
        </td>
        <td  align="center" colspan="2">
            报销收入
        </td>
    </tr>
    <tr class="TableHeader2" >
        <td  align="center">
            现金还款
        </td>
        <td  align="center">
            冲销还款
        </td>
        <td  align="center">
            其他还款
        </td>
        <td  align="center">
            现金收入
        </td>
        <td  align="center">
            银行收入
        </td>
    </tr>
    <tr class="TableHeader2" style="color:red">
        <td  align="center" height="25" width="12.5%">
            <?php echo num_to_maney_format($preLeaveLoanAm);?>
        </td>
        <td  align="center" width="12.5%"> 
            <?php echo num_to_maney_format($nowLoanAm);?>
        </td>
        <td  align="center" width="10%">
            <?php echo num_to_maney_format($nowPayLoanAmX);?>
        </td>
        <td  align="center" width="10%">
            <?php echo num_to_maney_format($nowPayLoanAmC);?>
        </td>
        <td  align="center" width="10%">
            <?php echo num_to_maney_format($nowPayLoanAmQ);?>
        </td>
        <td  align="center" width="12.5%">
            <!-- 上期借款余额 + 本期借款 -现今还款 -冲销还款 -其他还款  -->
            <?php echo num_to_maney_format(round($preLeaveLoanAm+$nowLoanAm-$nowPayLoanAmX-$nowPayLoanAmC-$nowPayLoanAmQ,2));?>
        </td>
        <td  align="center" width="12.5%">
            <?php echo num_to_maney_format($nowIncomeCostX);?>
        </td>
        <td  align="center" >
            <?php echo num_to_maney_format(round($nowIncomeCostY+$nowIncomeBorrow,2));?>
        </td>
    </tr>
    </table>
    </TD>
  </TR>
<?php 
    $sql="( select '部门借款' as ctype , amount  as am , reason as cinfo  ,  paydt as cdt  ,  xmflag as way from loan_list l where l.Debtor='$checkUserId' and l.Status in ('已支付','还款中','已还款')  and xmflag='0' $loanSql ) 
union 
( select '工程借款' as ctype , amount  as am , x.name  as cinfo  , paydt as cdt ,  xmflag as way from loan_list l , xm x  where l.projectno=x.projectno and  l.Debtor='$checkUserId' and l.Status in ('已支付','还款中','已还款')  and xmflag='1' $loanSql  )
union 
( select '冲销还款' as ctype , sum(money)  as am , r.billnos  as cinfo  , r.CreateDT as cdt ,  l.id as way from loan_list l , loan_repayment r where l.ID=r.loan_ID and l.Debtor='$checkUserId'  and  l.Status in ('还款中','已还款') and way='0' $paySql group by r.billnos  )
union 
( select '现金还款' as ctype , money  as am , r.billnos  as cinfo  , r.CreateDT as cdt ,  l.id as way from loan_list l , loan_repayment r where l.ID=r.loan_ID and l.Debtor='$checkUserId'  and  l.Status in ('还款中','已还款') and way='1' $paySql )
union
( select '其他还款' as ctype , money  as am , r.billnos  as cinfo  , r.CreateDT as cdt ,  l.id as way from loan_list l , loan_repayment r where l.ID=r.loan_ID and l.Debtor='$checkUserId'  and  l.Status in ('还款中','已还款') and way='2' $paySql )
order by cdt asc";
    $msql->query($sql);
?>
  <TR class="TableControl">
    <TD height="20">
    借款信息：
    </TD>
  </TR>
  <tr>
    <td>
    <table border='1'bordercolor="#000000" bordercolordark="#FFFFFF" bgcolor="#FFFFFF" class='small' cellspacing='0' width='100%'  cellpadding='0' >  
        <tr class="TableHeader2" >
            <td  align="center" width="10%" >
                日期
            </td>
            <td  align="center" width="15%" height="25">
                类型
            </td>
            <td  align="center" width="40%" >
                信息
            </td>
            <td  align="center" width="18%" >
                金额
            </td>
            <td  align="center" >
                借款余额
            </td>
        </tr>
        <?php
    $row=0; 
    while($msql->next_record()){
        $row++;
        $colorTr="";
        if($msql->f("ctype")=="部门借款"||$msql->f("ctype")=="工程借款"){
            $preLeaveLoanAm=($preLeaveLoanAm*1000+$msql->f("am")*1000)/1000;
            $cinfo=$msql->f("cinfo");
        }
        elseif($msql->f("ctype")=="冲销还款"||$msql->f("ctype")=="现金还款"||$msql->f("ctype")=="其他还款"){
            $preLeaveLoanAm=($preLeaveLoanAm*1000-$msql->f("am")*1000)/1000;
            $colorTr="style='color:green;'";
            $cinfo=str_replace("','",", ",trim($msql->f("cinfo"),"'"));
        }
        ?>
        <tr class="TableLine<?php echo $row%2+1;?> "  onmouseover="this.className='TableHightLine';" onmouseout="this.className='TableLine<?php echo $row%2+1;?>';" <?php echo $colorTr;?>>
            
            <td  align="center"  >
                <?php echo substr($msql->f("cdt"),0,10);?>
            </td>
            <td  align="center" height="20" >
                <?php echo $msql->f("ctype");?>
            </td>
            <td  align="left" >&nbsp;
                <?php echo $cinfo;?>
            </td>
            <td  align="right" style="padding-right:5px;" >
                <?php echo num_to_maney_format($msql->f("am"));?>
            </td>
            <td  align="right" style="padding-right:5px;" >
                <?php echo num_to_maney_format($preLeaveLoanAm);?>
            </td>
        </tr>
        <?php
    }
        ?>
    </table>
    </td>
  </tr>
  <TR class="TableControl">
    <TD height="20">
    报销信息：
    </TD>
  </TR>
  <?php 
    $billTellArr=array();
    $payBillDateArr=array();
    $billStr="";
    $billAmArr=array();
    $sql="(select  billnos ,  paytype ,  paymoney  as am , left(paydt,10)  as dt , '1' as ct  from cost_pay  where userid='$checkUserId' $costSql )
union 
(select l.billnos , '银行支付' as paytype , l.amount as am  , left(l.telledt,10)  as dt , '2' as ct  from borrow_list l where l.userid='$checkUserId' $borrowSql )
union
(select r.billnos ,'冲销还款' as paytype ,  sum(money)  as am   , left(r.CreateDT,10) as dt  , '3' as ct  from loan_list l , loan_repayment r where l.ID=r.loan_ID and l.Debtor='$checkUserId'  and  l.Status in ('还款中','已还款') and way='0' $paySql group by r.billnos  )
order by dt ";
    $msql->query($sql);
    while($msql->next_record()){
        $billTellArr[$msql->f("billnos")][$msql->f("paytype")]=$msql->f("am");
        $payBillDateArr[$msql->f("billnos")]=$msql->f("dt");
        if(strpos($billStr,$msql->f("billnos"))===false)
            $billStr.=$msql->f("billnos").",";
    }
    $sql="select amount , billno from cost_summary_list  where billno in (".trim($billStr,",").")";
    $msql->query($sql);
    while($msql->next_record()){
        $billAmArr[$msql->f("billno")]=$msql->f("amount");
    }
?>
  <tr>
    <td>
    <table border='1'bordercolor="#000000" bordercolordark="#FFFFFF" bgcolor="#FFFFFF" class='small' cellspacing='0' width='100%'  cellpadding='0' >  
        <tr class="TableHeader2" >
            <td  align="center" width="10%"  rowspan="2">
                出纳日期
            </td>
            <td  align="center" width="28%" height="20" rowspan="2" >
                报销单
            </td>
            <td  align="center" width="16%" rowspan="2">
                总金额
            </td>
            <td  align="center" colspan="3">
                出纳信息
            </td>
        </tr>
        <tr class="TableHeader2" >
            <td  align="center" height="20">
                冲销还款
            </td>
            <td  align="center" >
                现金收入
            </td>
            <td  align="center" >
                银行收入
            </td>
        </tr>
        <?php
    $row=0;
    $totalBill=array(); 
    $totalBill['p']=0;
    $totalBill['x']=0;
    $totalBill['y']=0;
    foreach($billTellArr as $key=>$val){
        $row++;
        $payLoan=isset($val['冲销还款'])?$val['冲销还款']:0;
        $haseLoanX=isset($val['现金支付'])?$val['现金支付']:0;
        $haseLoanY=isset($val['银行支付'])?$val['银行支付']:0;
        $totalBill['p']=isset($totalBill['p'])?($totalBill['p']*1000+$payLoan*1000)/1000:$payLoan;
        $totalBill['x']=isset($totalBill['x'])?($totalBill['x']*1000+$haseLoanX*1000)/1000:$haseLoanX;
        $totalBill['y']=isset($totalBill['y'])?($totalBill['y']*1000+$haseLoanY*1000)/1000:$haseLoanY;
        $cinfo="";
        $tmplable="";
        foreach( explode("','",trim($key,"'")) as $bval ){
            $tmplable.="&nbsp;".$bval."：".num_to_maney_format($billAmArr[$bval])."&nbsp;<br>";
            $cinfo.="<a href='#' onClick='showWin(".$bval.")' >$bval</a>".", ";
        }
        $cinfo=trim($cinfo,", ");
        ?>
        <tr class="TableLine<?php echo $row%2+1;?> "  onmouseover="this.className='TableHightLine';" onmouseout="this.className='TableLine<?php echo $row%2+1;?>';" >
            <td  align="center" height="20" >
                <?php echo $payBillDateArr[$key];?>
            </td>
            <td  align="left" >&nbsp;
                <?php echo $cinfo;?>
            </td>
            <td  align="right" style="padding-right:5px;" onmouseover="show(this,true)" onmouseout="show(this,false)"  lable="<?php echo $tmplable;?>">
                <?php echo num_to_maney_format(array_sum($val));?>
            </td>
            <td  align="right" style="padding-right:5px;" >
                <?php echo num_to_maney_format($payLoan);?>
            </td>
            <td  align="right" style="padding-right:5px;" >
                <?php echo num_to_maney_format($haseLoanX);?>
            </td>
            <td  align="right" style="padding-right:5px;" >
                <?php echo num_to_maney_format($haseLoanY);?>
            </td>
        </tr>
        <?php
    }
        ?>
        <tr class="TableHeader2" >
            <td  align="center" height="20">
                合计：
            </td>
            <td  align="center" >
                &nbsp;
            </td>
            <td  align="center" >
                <?php echo num_to_maney_format(array_sum($totalBill));?>
            </td>
            <td  align="center" >
                <?php echo num_to_maney_format($totalBill['p']);?>
            </td>
            <td  align="center" >
                <?php echo num_to_maney_format($totalBill['x']);?>
            </td>
            <td  align="center" >
                <?php echo num_to_maney_format($totalBill['y']);?>
            </td>
        </tr>
    </table>
    </td>
  </tr>
  <tr><td height="20">&nbsp;</td></tr>
  </TBODY>
</table>
<span id="title_pad" style="position:absolute;left:0px;top:0px;border:1px solid #000000;background-color:#FFFFE1;font-size:12px;z-index:2;display:none;padding:1px;"></span>
</body>
</html>