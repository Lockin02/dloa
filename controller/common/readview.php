<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
include( "../../includes/fsql.php" );
include( "../../includes/qsql.php" );
include( "../../includes/getUSER_DEPT_ID.php" );
include( "../../includes/util.php" );

$flowProp="";
$itemName="";
$pid = isset($_GET['pid'])?addslashes($_GET['pid']):"";
$thisTaskId = isset($_GET['taskId'])?addslashes($_GET['taskId']):"";
$itemtype = isset($_GET['itemtype'])?addslashes($_GET['itemtype']):"";
$sql="select  f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task from flow_step f , wf_task w  where  w.code='$itemtype' and w.Pid='$pid' and f.Wf_task_ID=w.task order by f.Wf_task_ID asc,f.SmallID asc";
$fsql->query($sql);
//if($fsql->num_rows()){
?>
<link rel="stylesheet" type="text/css" href="../../css/yxstyle2.css">
<tr >
 <td>
   <table width="100%"  class="form_main_table" id="auditInfo<?php echo $thisTaskId;?>">
           <tr class="TableContent" >
               <td width="100%" colspan="6"><B>审批情况</B></td>
           </tr>
<?php
	$mark = "";
	$i = 0;//审批次号
	while($fsql->next_record()){
		$taskId=$fsql->f("task");
		if(empty($mark) || $mark != $taskId){
			$i++;
        	$x = 0;
			if(!empty($mark)){
			?>
            <tr>
            	<td colspan="6"></td>
            </tr>
			<?php }
			$mark = $taskId;
			?>
            	<tr class="form_header"><td colspan="6" style="text-align:left">第<?php echo $i; ?>次审批<?php if( !empty($thisTaskId)&& $taskId == $thisTaskId){ ?> (本次审批) <?php }?></td></tr>
                <tr style="color:blue;">
                	<td width="5%">序号</td>
                    <td width="15%">步骤名</td>
                    <td width="10%">审批人</td>
                    <td width="20%">审批日期</td>
                    <td width="9%">审批结果</td>
                    <td width="27%">审批意见</td>
                </tr>
			<?php
		}
       	$exaItem=$fsql->f("Item");
      	$exaUser=$fsql->f("User");
      	$exaDate=$fsql->f("Endtime");
		$exaResult="";
		$exaContent="";
      	$sql="select p.User  , p.Content, p.Result, p.Endtime ,p.ID ,p.Flag  from flow_step_partent p where p.Wf_task_ID='$taskId' and p.SmallID='".$fsql->f("SmallID")."'  ";
      	$qsql->query($sql);
       	if($qsql->num_rows()>0){
            while($qsql->next_record()){
    			$x++;
                $exaUser=$qsql->f("User");
                $exaResult=$qsql->f("Result");
                $exaContent=$qsql->f("Content");
                $exaDate=$qsql->f("Endtime");
                $exaFlag=$qsql->f("Flag");
           ?>
        <tr class="extr TableLine2" >
            <td><?php echo $x;?></td>
            <td rowspan="1" <?php if($exaFlag==0) echo "style='color:red;'";?>><?php echo $exaItem;?></td>
            <td><?php echo trim(get_username_list($exaUser),",");?></td>
            <td style='color:green;'><?php echo $exaDate;?></td>
            <td><?php if($exaResult=="ok") echo "<font color='green'>同意</font>"; elseif($exaResult=="no") echo "<font color='red'>不同意</font>";else echo "未审批"?></td>
            <td><?php echo $exaContent;?></td>
        </tr>
           <?php
           }
		}else{
			$x++;
           ?>
        <tr class="extr TableLine2" >
            <td><?php echo $x;?></td>
            <td><?php echo $exaItem;?></td>
            <td><?php echo trim(get_username_list($exaUser),",");?></td>
            <td><?php echo $exaDate;?></td>
            <td><?php if($exaResult=="ok") echo "同意"; elseif($exaResult=="no") echo "<font color='red'>不同意</font>";else echo "未审批"?></td>
            <td><?php echo $exaContent;?></td>
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