<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"left";
include_once( "../includes/getUSER_DEPT_ID.php" );
$MONTH=isset($MONTH)?$MONTH:date("m");
$YEAR=isset($YEAR)?$YEAR:date("Y");
$checkDate=$YEAR."-".$MONTH;
$sql="select SUBSTRING(CAL_TIME,9,2) as checkday from calendar where 1 and left(CAL_TIME,7)='$checkDate' and USER_ID='$USER_ID' ";
$msql->query($sql);
$checkArr=array();
while($msql->next_record()){
    $checkArr[]=intval($msql->f('checkday'));
}
?>
<script language="javascript">
    <!--//
my_top=50;
my_left=50;
function my_note(CAL_ID)
{
  my_top+=25;
  my_left+=15;
  window.open("note.php?CAL_ID="+CAL_ID,"note_win"+CAL_ID,"height=170,width=180,status=0,toolbar=no,menubar=no,location=no,scrollbars=auto,top="+ my_top+",left="+ my_left +",resizable=no");
}

function My_Submit()
{
  document.form1.submit();
}

function set_year(op)
{
  if(op==-1 && document.form1.YEAR.selectedIndex==0)
     return;
  if(op==1 && document.form1.YEAR.selectedIndex==(document.form1.YEAR.options.length-1))
     return;
  document.form1.YEAR.selectedIndex=document.form1.YEAR.selectedIndex+op;
  My_Submit();
}

function set_mon(op)
{
  if(op==-1 && document.form1.MONTH.selectedIndex==0)
     return;
  if(op==1 && document.form1.MONTH.selectedIndex==(document.form1.MONTH.options.length-1))
     return;
  document.form1.MONTH.selectedIndex=document.form1.MONTH.selectedIndex+op;
  My_Submit();
}    
    //-->        
</script> 
<DIV class='module'>
      <DIV class='moduleHeader' >
      <table width="100%" border="0"><tr>
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/menu/calendar.gif" >日程安排</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_8'></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_8')"><IMG style='border:0px' id='img_<?php echo $pos;?>_8' title=折叠 src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id='table_<?php echo $pos;?>_8'>
<table border="0" cellspacing="1" width="95%" class="small"  cellpadding="3" bgcolor="#EFF7FF" align="center">
  <TBODY>
    <TR>
      <TD>
    <table border="0" cellspacing="1" width="100%" class="small" bgcolor="#d0d0c8" cellpadding="3">
    <tr align="center" class="D3E5FA">
      <td bgcolor="#FFCCFF" width="10%" height='23'><b>日</b></td>
      <td width="10%"><b>一</b></td>
      <td width="10%"><b>二</b></td>
      <td width="10%"><b>三</b></td>
      <td width="10%"><b>四</b></td>
      <td width="10%"><b>五</b></td>
      <td bgcolor="#CCFFCC" width="10%"><b>六</b></td>
    </tr>
   <tr >
<?php
    $firstday=date("w",mktime(0,0,0,$MONTH,1,$YEAR));
    for($i=0;$i<$firstday; $i++){
    ?>
    <td class=TableLine2 valign='top' >
    <div align=center style='font-family:sans-serif'><b>&nbsp;</b></div></td>
    <?php
    }
    $countDays=date("t",mktime(0,0,0,$MONTH,1,$YEAR));
    for($i=1; $i<=$countDays; $i++){
        $tempstr="YEAR=$YEAR&MONTH=$MONTH&DAY=$i";
    ?>
    <td class=TableLine2 valign='top'>
    <div id='nd' align='center' style='font-family:sans-serif; <?php if($i==date("d")) echo "background-color :#FF9999";?> '><b><a href='calendar/manage?<?php echo $tempstr;?>' style="color:<?php if(in_array($i,$checkArr)) echo "red";else echo "#0066cc";?>"><?php echo $i;?></a></b></div></td>
    <?php
        if(date("w",mktime(0,0,0,$MONTH,$i,$YEAR))==6){
            if($i!=$countDays){
                ?>
                </tr><tr>
                <?php
            }
        }
    }
    $lastday=date("w",mktime(0,0,0,$MONTH,$countDays,$YEAR));
    for($j=0; $j<6-$lastday; $j++){
        ?>
        <td class=TableLine2 valign='top'>
        <div align=center style='font-family:sans-serif'><b>&nbsp;</b></div></td>
        <?php
    }
    ?>
    </tr>
    <?php
?> 
</table>      </td>
    </tr>
  </tbody>
</table></DIV></DIV></DIV>