<?php
include( "../../../includes/db.inc.php" );
include( "../../../includes/config.php" );
include( "../../../includes/msql.php" );
include( "../../../includes/fsql.php" );
include( "../../../includes/qsql.php" );
include( "../../../includes/getUSER_DEPT_ID.php" );
include( "../../../includes/util.php" );

$flowProp="";
$itemName="";
$pid = isset($_GET['pid'])?addslashes($_GET['pid']):"";
$itemtype = isset($_GET['itemtype'])?addslashes($_GET['itemtype']):"";
$sql="select  f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task from flow_step f , wf_task w  where  w.code='$itemtype' and w.Pid='$pid' and f.Wf_task_ID=w.task ";
$fsql->query($sql);
//if($fsql->num_rows()){
?>
<link rel="stylesheet" type="text/css" href="../../../inc/style.css">
<tr align="center" >
 <td colspan="6" style="padding-left:0px;padding-right:0px;" >
   <table bgcolor="#d0d0c8" border="0"  cellspacing="1" cellpadding="0" width="100%"  align="center" class="small tdExa">
           <tr align="center" class="TableContent" >
               <td width="100%" align="center" colspan="6" style="font-size:14px;" height="35"><B>审批情况</B></td>
           </tr>
               <tr align="center" class="TableLine2" style="color:blue;">
                   <td width="20%">步骤名</td>
                   <td width="10%">审批人</td>
                   <td width="20%">审批日期</td>
                   <td width="9%">审批结果</td>
                   <td width="27%">审批意见</td>
               </tr>
               <?php
   $taskMy ="";
   $n = 0;
   while($fsql->next_record()){
       $taskId=$fsql->f("task");
       $exaItem=$fsql->f("Item");
       $exaUser=$fsql->f("User");
       $exaDate=$fsql->f("Endtime");
       $exaResult="";
       $exaContent="";
       $sql="select p.User  , p.Content, p.Result, p.Endtime ,p.ID ,p.Flag  from flow_step_partent p where p.Wf_task_ID='$taskId' and p.SmallID='".$fsql->f("SmallID")."'  ";
       $qsql->query($sql);
       if($qsql->num_rows()>0){
           $x=0;
           while($qsql->next_record()){
               $x++;
               $exaUser=$qsql->f("User");
               $exaResult=$qsql->f("Result");
               $exaContent=$qsql->f("Content");
               $exaDate=$qsql->f("Endtime");
               $exaFlag=$qsql->f("Flag");
           ?>
       <tr class="extr TableLine2" >
           <?php
           if($x==1){
               ?>
           <td rowspan="<?php echo $qsql->num_rows();?>" <?php if($exaFlag==0) echo "style='color:red;'";?>>&nbsp;<?php echo $exaItem;?></td>
               <?php
           }
           ?>
           <td align="center">&nbsp;<?php echo trim(get_username_list($exaUser),",");?></td>
           <td align="center" style='color:green;'>&nbsp;<?php echo $exaDate;?></td>
           <td align="center">&nbsp;<?php if($exaResult=="ok") echo "<font color='green'>同意</font>"; elseif($exaResult=="no") echo "<font color='red'>不同意</font>";else echo "未审批"?></td>
           <td>&nbsp;<?php echo $exaContent;?></td>
       </tr>
           <?php
           }
       }else{
           ?>
       <tr class="extr TableLine2" >
           <td >&nbsp;<?php echo $exaItem;?></td>
           <td align="center">&nbsp;<?php echo trim(get_username_list($exaUser),",");?></td>
           <td align="center">&nbsp;<?php echo $exaDate;?></td>
           <td align="center">&nbsp;<?php if($exaResult=="ok") echo "同意"; elseif($exaResult=="no") echo "<font color='red'>不同意</font>";else echo "未审批"?></td>
           <td>&nbsp;<?php echo $exaContent;?></td>
       </tr>
       <?php
       }
   }?>
</table>
  </td>
</tr>
<?php
//}
?>