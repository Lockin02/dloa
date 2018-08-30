<?php
include("../../../includes/db.inc.php");
include("../../../includes/config.php");
include("../../../includes/msql.php");
include("../../../includes/fsql.php");
include("../../../includes/qsql.php");
//
$QR_BillNo = isset($_GET['QR_BillNo']) ? $_GET['QR_BillNo']:"";
$sql= "select * from cost_summary_list where 1 and isProject='1' and Status in('部门检查','部门审批','财务审核','出纳付款','完成') and BillNo = '$QR_BillNo'";
$fsql->query($sql);
//
$BillNo = isset($QR_BillNo)?$QR_BillNo:"";
$xm_name="";
$costman_name="";
$inputman_name="";
$costdepart_name="";
$costdates="";
$costbelongtodeptids="";
$servicequantity="";
$flag="";
$area="";
$disabled="";

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <link rel="stylesheet" type="text/css" href="../../../inc/style.css">
    <style>
.tabp{
    border-bottom: 1px;
    border-collapse: separate;
    empty-cells: show;
    vertical-align: top;
}
.tabtr td{
    height: 30px;
}
.cuttable{
    height: 700px;

}
.cutline{
    border-bottom: 1px ;
    border-bottom-style: dashed;
}
.buttoninfo{
    margin-top: 20px;
    width: 23%;
    float: left;
    font-size: 12;
    white-space: nowrap;
    margin-left: 5px;
    text-align: left;
}
.extab{
    border-top: 1px;
    border-collapse: separate;
    empty-cells: show;
    vertical-align: top;
}
.extr td{
    height: 28px;
}
    </style>
<style   media=print type="text/css">
.Noprint{display:none;}
.PageNext{page-break-after:   always;}
.WriteBg{
    BACKGROUND:white;
}
</style>
<script>
    function printClick(){
        try{
            document.all.WebBrowser.ExecWB(6,6);
        }catch(e){
            alert("安全提示，请把站点设置成安全站点，详情请点击：“帮助” !");
        }
    }
    function helpClick(){
        window.open("../../help/help.html",null);
    }
</script>
</head>
<body class="bodycolor" topmargin="5">
<table border="0" cellspacing="0" cellpadding="3" class="small Noprint" width="100%">
        <tr>
            <td class="Big">
                &nbsp;&nbsp;&nbsp;&nbsp;<img src="../../../images/w_fActionEdit.gif">
                <b><font color="#000000">打印工程借款单</font></b>
            </td>
        </tr>
        <tr align="left">
            <td class="Big" height="30">
<OBJECT id=WebBrowser classid=CLSID:8856F961-340A-11D0-A96B-00C04FD705A2 height=0 width=0>
</OBJECT>
<input     type='button'  class="BigInput"   value='直接打印'     onclick="printClick()">
<input     type='button'  class="BigInput"   value='页面设置'     onclick=document.all.WebBrowser.ExecWB(8,1)>
<input     type='button'  class="BigInput"   value='打印预览'     onclick=document.all.WebBrowser.ExecWB(7,1)>
<input     type='button'  class="BigInput"   value='帮助'     onclick="helpClick()">
            </td>
        </tr>
</table>
<?php
$x=0;
$ncount=$fsql->num_rows();
while($fsql->next_record()){
    $x++;
    $BillNo=$fsql->f("BillNo");
    $msql->query("select l.* , u.account , u.usercard
        from cost_summary_list l
        left join hrms u on (u.user_id = l.costman )
        where BillNo='".$fsql->f("BillNo")."'");
    while($msql->next_record())
    {
    	if($msql->f("CostBelongTo")==4)
    		$checkPrintName=true;
    	else
    		$checkPrintName=false;
        $inputman_id=$msql->f("InputMan");
        $costman_id=$msql->f("CostMan");
        $costdepart_id=$msql->f("CostDepartID");
        $projectno=$msql->f("ProjectNo");
        $sid=$msql->f("xm_sid");
        $CostDates=$msql->f("CostDates");
        $area=$msql->f("Area");
        $costbelongtodeptids=$msql->f("CostBelongtoDeptIds");
        $servicequantity=$msql->f("ServiceQuantity");
        $Status=$msql->f("Status");
        $costbelongtodeptids= explode(",",$costbelongtodeptids);
        $servicequantity= explode(",",$servicequantity);
        $acc=$msql->f('account');
        $ucard=$msql->f('usercard');
        $disabled="disabled";
        $flag="update";

        $xm_name = $msql->f("projectName");
        $costman_name  = $msql->f("CostManName");
        $inputman_name  = $msql->f("InputManName");
        $costdepart_name=$msql->f("CostDepartName");
        $id = $msql->f("ID");

        if($CostDates){
            $costDateArr=array();
            $costDateArrTmp=explode(" ",$CostDates);
            foreach($costDateArrTmp as $val){
                if($val){
                    $valArr=explode("~",$val);
                    if(count($valArr)==2){
                        $costDateArr[]=array($valArr[0],$valArr[1]);
                    }elseif(count($valArr)==1){
                        $costDateArr[]=array($valArr[0],$valArr[0]);
                    }
                }
            }
            if(count($costDateArr)!=1){
                $CostDates="";
                for($i=0; $i<count($costDateArr); $i++){
                    $tempOne=$costDateArr[$i][0];
                    $tempTwo=$costDateArr[$i][1];
                    if($i==0){
                        $firstDate=$tempOne;
                    }else{
                        if(strtotime($tempOne)!=strtotime($costDateArr[$i-1][1])+24*60*60){
                            if($firstDate==$costDateArr[$i-1][1])
			            		$CostDates.=$firstDate." ";
			            	else
			                	$CostDates.=$firstDate."~".$costDateArr[$i-1][1]." ";
                            $firstDate=$tempOne;
                        }
                        if($i==count($costDateArr)-1){
                            if($firstDate==$tempTwo)
			            		$CostDates.=$firstDate;
			            	else
			                	$CostDates.=$firstDate."~".$tempTwo;
                        }
                    }
                }
            }elseif(count($costDateArr)==1){
				if($costDateArr[0][0]==$costDateArr[0][1])
					$CostDates=$costDateArr[0][0];
				else
					$CostDates=$costDateArr[0][0]."~".$costDateArr[0][1];
			}
        }
    }

    $sql="( select d.ID, t.CostTypeName,t.ParentCostTypeID,d.CostTypeID,sum(d.CostMoney*d.days),d.Remark from cost_detail_project d , cost_type t , cost_detail_assistant a where a.HeadID=d.HeadID and a.RNo=d.RNo and a.BillNo='$BillNo' and  d.CostTypeID=t.CostTypeID  group by d.CostTypeID  )
    	union
    	(select d.ID, t.CostTypeName,t.ParentCostTypeID,d.CostTypeID,sum(d.CostMoney*d.days),d.Remark from cost_detail d , cost_type t , cost_detail_assistant a where a.HeadID=d.HeadID and a.RNo=d.RNo and a.BillNo='$BillNo' and  d.CostTypeID=t.CostTypeID  group by d.CostTypeID) order by ParentCostTypeID";
    $msql->query($sql);
    $trowset = $msql->getarray();
    $count=count($trowset);
    $parentcount=0;
    $parentarr=false;
    $j=1;
    for($i=0;$i<$count;$i++)
    {
        if($i==0)
            $parentarr[0] = Array($trowset[0]["ParentCostTypeID"],$j);
        if($i>0)
         {
            if($trowset[$i]["ParentCostTypeID"]!=$trowset[$i-1]["ParentCostTypeID"])
            {
                $j=1;
                $parentcount++;
                $parentarr[$parentcount] = Array($trowset[$i]["ParentCostTypeID"],$j);
            }
            else
            {
                $j++;
                $parentarr[$parentcount][1]=$j;
            }
         }
    }
    ?>
<table width="640" align="center" class="cuttable" border="0" cellpadding="0" cellspacing="0">
<tr><td height="60" class="cutline">&nbsp;</td></tr>
<tr>
<td valign="top" height="30%">
<div style="text-align:center;margin-top:10;vertical-align: top;"><span style="font-size:large;font-weight:bold;color:red;">报  销  清  单</span></div>
<div style="text-align:right;vertical-align: top;">
    <table cellspacing="0" cellpadding="0" width="100%" align="center" boder="0">
        <tr>
            <td style="text-align:left;font-size: 11px;"><?php echo $ucard.'-'.$acc;?></td>
            <td style="text-align:right;vertical-align: top;">No:<?php echo $fsql->f("BillNo");?></td>
        </tr>
    </table>
</div>
    <table class="small tabp" border="1" bordercolor="black" bgcolor="black" cellspacing="0" cellpadding="0" width="100%" align="center" >
        <thead>
            <tr class="TableLine1  WriteBg tabtr">
                <td nowrap="" colspan="2" width="15%">员工：<?php echo $costman_name;?></td>
                <td width="15%">部门：<?php echo $costdepart_name;?></td>
                <td width="40%">时间：<font size="1"><?php echo $CostDates;?></font></td>
                <td width="30%">区域：<?php
                $msql->query("select Name from area where del='0' and ID='$area' ");
                $msql->next_record();
                echo $msql->f('Name');
?>
                </td>
            </tr>
        </thead>
            <tr class="TableLine1  WriteBg tabtr">
                <td  colspan="5" nowrap>
                    项目名称：<?php echo $xm_name;?>&nbsp;&nbsp;
                    项目编号：<?php echo $projectno;?>
                </td>
            </tr>
            <tr class="TableLine1  WriteBg tabtr">
                <td  colspan="5" nowrap>服务性质:
                <input  type="checkbox" name="ServiceQuantity[]"  value="产品" <?php if(is_array($servicequantity)&&in_array("产品",$servicequantity))echo "checked"; ?> <?php echo $disabled; ?>>
                产品
                <input  type="checkbox" name="ServiceQuantity[]"  value="服务" <?php if(is_array($servicequantity)&&in_array("服务",$servicequantity))echo "checked"; ?> <?php echo $disabled; ?>>
                服务
                <input  type="checkbox" name="otherServiceQuantity[]"  value="其他" <?php if(is_array($servicequantity)&&in_array("其他",$servicequantity))echo "checked"; ?> <?php echo $disabled; ?>>
                其他
                </td>
            </tr>
            <tr class="TableLine1  WriteBg tabtr">
                <td>&nbsp;</td>
                <td>费用明细</td>
                <td align="center">金额</td>
                <td align="center" colspan="3">备注</td>
            </tr>
            <!-- 所有报销类型明细 -->
    <?php
        $moneycountall=0;
        foreach($parentarr as $parentid)
        {
            $msql->query("select CostTypeName from cost_type where CostTypeID='".$parentid[0]."'");
            if($msql->next_record())
            {
                $moneycount=0;
    ?>
            <tr class="TableLine1  WriteBg tabtr">
                <td style="padding-left:5px;" rowspan="<?php echo $parentid[1]+1;?>"  id="td<?php echo $parentid[0];?>" width="4%" valign="middle">
                    <?php echo $msql->f("CostTypeName");?>
                </td>
    <?php
                    $colspan = 0;
                    foreach($trowset as $row)
                    {
                        if($row["ParentCostTypeID"]==$parentid[0])
                        {
                            $colspan++;
                            $moneycount += $row["sum(d.CostMoney*d.days)"];
                            if($colspan>1)
                                echo "<tr class=\"TableLine1  WriteBg tabtr \">";
    ?>
                <td>
                    <?php echo $row["CostTypeName"]; ?>
                </td>
                <td align="right">
                    <?php echo $row["sum(d.CostMoney*d.days)"];?>
                </td>
                <td colspan="9">&nbsp;<?php echo $row["Remark"];?>
                </td>
            </tr>
    <?php
                        }
                    }
                    $moneycountall+=$moneycount;
    ?>
              <tr class="TableLine1  WriteBg tabtr">
                <td><b>合计:</b></td>
                <td align="right">
                    <?php echo $moneycount;?>
                </td>
                <td colspan="9" >&nbsp;</td>
            </tr>
    <?php
            }
        }
    ?>
              <tr class="TableLine1  WriteBg tabtr">
                <td rowspan="2">合计</td>
                <td><b>小写:</b></td>
                <td align="right"><?php echo $moneycountall;?></td>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr class="TableLine1  WriteBg tabtr">
                <td><b>大写:</td>
                <td colspan="3" align="center">
                    <span style="font: normal small-caps bold 120%/120% fantasy ">
                        <?php echo num2Upper($moneycountall);?>
                    </span>
                </td>
            </tr>
    </table>
</td></tr>
<tr ><td valign="top" >
<?php

	//根据业务id获取最近的一次审批
	$sql = "select max(task) as task from wf_task where pid = '$id' and code = 'cost_summary_list'";
    $wfArr=$msql->getrow($sql);
    $wfId = $wfArr['task'];

	//然后再查询审批流信息
    $sql="select  f.Item,p.User,u.USER_NAME ,p.Content,p.Endtime ,p.Result from flow_step  f  left join ( flow_step_partent  p , user u ) on f.Wf_task_ID=p.Wf_task_ID and f.SmallID=p.SmallID  and u.USER_ID=p.User   where f.Wf_task_ID='".$wfId."' ";
    $msql->query($sql);
    if($msql->num_rows()>0){
        ?>
<table class="small extab" border="1" bordercolor="black" bgcolor="black" cellspacing="0" cellpadding="0" width="100%" align="center">
<tr align="center" class="extr TableLine1  WriteBg" >
                <td width="100%" align="center" colspan="5" style="font-size:14px;"><B>审批情况</B></td>
            </tr>
            <tr align="center" class="extr TableLine1  WriteBg" >
                <td width="30%">步骤名</td>
                <td width="10%">审批人</td>
                <td width="25%">审批日期</td>
                <td width="9%">审批结果</td>
                <td width="30%">审批意见</td>
            </tr>
     <?php
        while($msql->next_record()){
            ?>
            <tr class="extr TableLine1  WriteBg" >
                <td>&nbsp;<?php echo $msql->f("Item");?></td>
                <td align="center">&nbsp;<?php echo $msql->f("USER_NAME");?></td>
                <td align="center">&nbsp;<?php echo $msql->f("Endtime");?></td>
                <td align="center">&nbsp;<?php if($msql->f("Result")=="ok") echo "同意"; elseif($msql->f("Result")=="no") echo "<font color='red'>不同意</font>";?></td>
                <td>&nbsp;<?php echo $msql->f("Content");?></td>
            </tr>
            <?php
        }
     ?>
</table>
        <?php
    }
?>
<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;" class="small">
    <tr><td nowrap>&nbsp;制单人：<?php echo $costman_name;?></td>
        <td width="60%"></td>
        <td nowrap>&nbsp;报销收款人：<?php echo $checkPrintName?$costman_name:""?></td>
    </tr>
</table>
</td></tr>
</table>
<?php
    if($x!=$ncount){
        ?>
        <div   class="PageNext">&nbsp;</div>
        <?php
    }
}
?>
</body>
</html>