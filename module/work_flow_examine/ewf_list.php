<?php
echo getlongdate();

$USER_ID=$_SESSION["USER_ID"];
$sql="select USER_PRIV from user where USER_ID='".$USER_ID."' ";
$msql->query($sql);
while($msql->next_record()){
    $checkPriv=$msql->f("USER_PRIV");
}
$sqlStr="";
$applyUser=isset($applyUser)?$applyUser:"";
$proNo=isset($proNo)?$proNo:"";
if(trim($proNo)!=""){
    $sqlStr.=" and (l.ProjectNo like '%".addslashes($proNo)."%' or l.ProjectNoTmp like '%".addslashes($proNo)."%')";
}
if(trim($applyUser)!=""){
    $tempWhere="";
    $sql="select USER_ID from user where USER_NAME like '%".trim($applyUser)."%' ";
    $msql->query($sql);
    while($msql->next_record()){    
        $tempWhere.="'".$msql->f("USER_ID")."',";
    }
    if($tempWhere)
    $sqlStr.=" and l.User_main in (".trim($tempWhere,",").")";
}
//������Ȩ����
$powerSql=" and ( find_in_set('".$USER_ID."',s.User)>0  ";
$sql="select FROM_ID from power_set where TO_ID='".$USER_ID."' and STATUS='1' and BEGIN_DATE <= '".getlongdate()."' and END_DATE>='".getlongdate()."'";
$msql->query($sql);
while($msql->next_record()){
    $powerSql.=" or find_in_set('".$msql->f("FROM_ID")."',s.User)>0 ";
}
$powerSql.=" ) ";
$sql="select count(distinct t.task) from wf_task t,flow_step_partent s where  t.task=s.Wf_task_ID and s.Flag='0' and t.examines='' and t.Status='ok' $powerSql  $sqlStr ";
echo $sql;
$crow = $msql->getrow($sql);
$pages = $crow["count(distinct t.task)" ];
$totalpage = ceil( $pages / $offset );
$totalgroup = ceil( $totalpage / $offset );
if (!isset($page) || $page == "" )
{
    $page = 1;
}
include( "../../inc/page.php" );
?>
<html>
<head>
    <title>��Ŀ�����б�</title>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <link rel="stylesheet" type="text/css" href="../../inc/style.css">
    <script language="JavaScript">
    function selall(all)
    {
        var ids = document.getElementsByName("ck_ids[]");
        var idslength = ids.length;
        for(var i=0;i<idslength;i++)
        {
            ids[i].checked=all.checked;
        }
    }
    function sub(id)
    {
        var checkCoust=0;
        if(id==0){
            var inputs = document.getElementsByName("ck_ids[]");
            if(inputs.length==0){
                return false;
            }
            var cp=true;
            var fr=false;
            for(var i = 0; i < inputs.length; i++)
            {
                if(inputs[i].checked){
                    checkCoust=(checkCoust*1+(document.getElementById("c_"+inputs[i].value).value)*1);
                    cp=false;
                    if(document.getElementById("fr_"+inputs[i].value).value==0){
                        fr=true;
                    }
                }
            } 
            if(cp){
                alert("����ѡ��һ��ѡ��");
                return false;
                
            }
            if(fr){
                alert("ѡ���к���δ�յ��ı����������Ƚ����յ�����");
                return false;
                
            }
        }  
        suggeclick(checkCoust);
    }
    function checkPayType(){
        var inputs = document.getElementById("content");
        if(inputs.value==""){
            return confirm("ȷ������д���������");
        }
    }
    function suggeclick(okid){
        document.getElementById("divCoust").innerHTML=okid;
        width = 380;
        height = 280;
        document.getElementById("infoDiv").style.left=(((document.body.clientWidth-width)>0?(document.body.clientWidth-width):0)/2-100)+"px"; 
        document.getElementById("infoDiv").style.top=100+"px"; 
        document.getElementById("infoDiv").style.zIndex=10001;
        document.getElementById("infoDiv").style.width=width; 
        document.getElementById("infoDiv").style.height=height; 
        document.getElementById("infoDiv").style.border="1px solid #9FBFE3";
        document.getElementById("tranDiv").style.height=document.body.clientHeight+ "px"; 
        document.getElementById("tranDiv").style.width=document.body.clientWidth+ "px"; 
        document.getElementById("tranDiv").style.display=""; 
        document.getElementById("tranDivBack").style.display=""; 
        document.getElementById("tranDivBack").style.zIndex=10000;
        document.getElementById("infoDiv").style.display=""; 
    }
    function closeWindow(){ 
        document.getElementById("tranDiv").style.display="none"; 
    }
    function chackRec(cv){
        if(cv=='0'){
            alert("���յ���������������");
            return false;
        }else{
            return true;
        }
    }
    function setForm(){
        document.form1.action="#";
        document.form1.method="get";
        document.form1.submit();
    }
    function showBill(id){
        var url="summary_detail.php?BillNo="+id+"&status=�������&back=close&doAct=����";
        window.open(url,"showBill","status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
    }
    </script>
</head>
<body class="bodycolor" topmargin="5">
<div>

<table border="0" cellspacing="0" cellpadding="3" class="small" width="98%">  
    <tr>    
        <td class="Big">
            <img src="../../images/notify_open.gif">
            <b><font color="#000000">��Ŀ�����б�</font></b>
            <br>    
        </td>
    </tr>
</table>
<table border="0" cellspacing="0"  cellpadding="3" class="small" align="center" width="98%" style="margin-left:5px;margin-bottom:5px;margin-top:5px;">  
    <tr >
    <!--  <td height="26" width="10%">
      <input type="button" name="btn" id="back" value="��������" onclick="sub('0')" class="BigButton" >
    </td>-->
    <td>
       <input type="button" name="back" value="�鿴��˼�¼" align="center" size="30" class="BigButton" onclick="location.href='has_examine_list.php?fin=1';">
    </td>
    </tr>
</table>
<form action="examine_do_finance_submit_b.php" id="form1" name="form1" method="post">
    <table border='0' cellspacing='1' width='98%' class='small'  cellpadding='3' bgcolor='#d0d0c8'  align='center' style="margin-left:5px;">  
        <tbody>  
        <tr align="left" class="TableControl">
            <td colspan="8">
                    <table border="0" class="small" width="100%">
                        <tr >
                            <td height="30">
                                �����ˣ�
                                <input type="text" name="applyUser" value="<?php echo $applyUser;?>" class="BigInput">&nbsp;&nbsp;
                                ��Ŀ��ţ�
                                <input type="text" name="proNo" value="<?php echo $proNo;?>" class="BigInput">&nbsp;&nbsp;
                                <input type="submit" value="�� ѯ" onclick="setForm()" class="BigButton" title="���в�ѯ" name="Q_submit">
                            </td>
                        </tr>
                    </table>
            </td>
        </tr>
            <tr>    
                <td>          
                    <table border='0' cellspacing='1' width='100%' class='small' bgcolor='#d0d0c8' cellpadding='3'>                             
                        <tr class="<?php echo $TableHeader;?>">
                            <!--<td nowrap align="center" width="6%">
                                <input type="checkbox" name="slt_all" id="slt_all" value="all" title="ȫѡ" onclick="selall(this)">ȫѡ
                            </td> 
                            -->
                            <td width="6%" align="center" style="color:blue">
                                    ���
                            </td>
                            <td width="20%" align="center" height="28">
                                <a href="?arrow=<?php echo $arrow_next;?>&applyUser=<?php echo $applyUser;?>&proNo=<?php echo $proNo;?>">
                                    ��������
                                    <img border=0 src='../../images/farrow<?php echo $arrowgif;?>.gif' alt='����'>
                                </a>
                            </td>      
                            <td width="18%"  align="center">
                                <a href="?arrow1=<?php echo $arrow1_next;?>&applyUser=<?php echo $applyUser;?>&proNo=<?php echo $proNo;?>">
                                    ������
                                    <img border=0 src='../../images/farrow<?php echo $arrow1gif;?>.gif' alt='����'>
                                </a>
                            </td> 
                            <td width="18%" align="center">
                                <a href="?arrow2=<?php echo $arrow2_next;?>&applyUser=<?php echo $applyUser;?>&proNo=<?php echo $proNo;?>">
                                    �ύ����
                                    <img border=0 src='../../images/farrow<?php echo $arrow2gif;?>.gif' alt='����'>
                                </a>
                            </td>  
                            <td width="12%" align="center">
                                <a href="?arrow3=<?php echo $arrow3_next;?>&applyUser=<?php echo $applyUser;?>&proNo=<?php echo $proNo;?>">
                                    ������
                                    <img border=0 src='../../images/farrow<?php echo $arrow3gif;?>.gif' alt='����'>
                                </a>
                            </td>   
                            <td width="12%" align="center" style="color:blue">
                                    ��������
                            </td>   
                            <td  align="center">
                                    ����
                            </td>    
                    </tr>
<?php
$x = 0;
$sql=" select t.* , fs.Flow_prop as FlowProp  from wf_task t, flow_step_partent s , flow_step fs where fs.Wf_task_Id=s.Wf_task_Id and fs.SmallID=s.SmallID and   t.task=s.Wf_task_Id and s.Flag='0' and t.examines='' and t.Status='ok' $powerSql  $sqlStr group by t.task order by $Order_by ";
$fsql->query($sql);
while ( $fsql->next_record( ) )
{
    $x ++;
    
?>
    <tr class="TableLine<?php echo $x%2+1;?>"  onmouseover="this.className='TableHightLine';" onmouseout="this.className='TableLine<?php echo $x%2+1;?>';">
        <td align='center'>
            <?php echo $x;?>
        </td> 
        <td align='center' height="23">
            <?php echo $fsql->f("task");?>
        </td>     
        <td align='center'>
            <?php echo getUserNameById($fsql->f("Enter_user"));?>
        </td>  
        <td align='center'>
            <?php echo substr($fsql->f("start"),0,10);?>
        </td>          
        <td align='center'>
            <?php echo $fsql->f("name");?>
        </td>
        <td align='center'>
            <?php echo $fsql->f("FlowProp")==1?"��ǩ":"���";?>
        </td>
        <td align="center">
            <input type="button" name="examBtn" value="����" class="BigButton" onclick="location.href='examine.php?billId=<?php echo $fsql->f('billId');?>&spid=<?php echo $spartemtid;?>&wfTask=<?php echo $fsql->f("wfTask");?>';">          
        </td>    
    </tr>
    <?php
}
?>
            </table>
        </td>   
    </tr>   
</tbody>   
</table>
<!--�����ֲ���-->
<div style="position:absolute;display:none; left:0px; top:0px;" id="tranDiv">
    <div style="position:absolute;left:0px; top:0px; width:100%; height:100%;background-color:#333333;filter:alpha(Opacity=5)" id="tranDivBack"> </div>
    <div align='center'style='position:absolute;left:0px; top:0px; width:100%; height:100%;background-color:#ffffff' id='infoDiv'>
        <input type="hidden" name="extraMessage" id="issend" value="">
        <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="1" align="center" bgcolor="#d0d0c8" bordercolor="#d0d0c8" >
            <tr class="<?php echo $TableHeader;?>" align="center" >
                <td height="30" colspan="2" ><b>����������Ϣ</b></td>
            </tr>
            <tr class="TableControl" valign="bottom" align="left" >
                <td height="28">
                    �����ܽ�
                </td>
                <td>
                <font id="divCoust" color="red"></font>
                </td>
            </tr> 
            <tr class="TableControl" valign="bottom" align="left" >
                <td height="28">
                    ������
                </td>
                <td  >
                <input type="radio" name="result" value="ok" checked="checked">ͬ��
                <input type="radio" name="result" value="no">��ͬ��
                </td>
            </tr>            
            <tr class="TableControl" align="left" >
                <td nowrap height="28">
                    �Ƿ�֪ͨ��������������
                </td>
                <td >
                <input type="radio" name="issend" value="y" checked="checked">��
                <input type="radio" name="issend" value="n" >��   
                </td>
            </tr>
            <tr class="TableControl" align="left" >
                <td nowrap height="28">
                    �Ƿ�֪ͨ��һ��������������
                </td>
                <td >
                <input type="radio" name="isSendNext" value="y" checked="checked">��
                <input type="radio" name="isSendNext" value="n" >��   
                </td>
            </tr>
            <tr class="TableControl" align="left" >
                <td nowrap>
                    ���������
                </td>
                <td >
                <textarea name="content" id="content" rows="8" cols="50" align="right" title="�������"></textarea>   
                </td>
            </tr>
            <tr class="<?php echo $TableHeader;?>" align="center">
                <td height="28" colspan="2"><input type="submit" class="BigInput" name="Submit" onclick="return checkPayType();" class="bntccanniu" value="�� ��"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="Submit1"  class="BigInput" value="ȡ ��" onclick="closeWindow();"/></td>
            </tr>
        </table>
    </div>
</div> 
<!--�����ֲ��ֽ���-->
</form>
<?php
require("../../module/PageController/pager.php");
?>
</body>
</html>