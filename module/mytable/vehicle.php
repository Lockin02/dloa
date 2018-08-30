<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"left";
include_once( "../includes/getUSER_DEPT_ID.php" );
?>
<script language="javascript">
    <!--//
function plan_detail(PLAN_ID)
{
 URL="work_plan/show/plan_detail.php?PLAN_ID="+PLAN_ID;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_work_plan","height=400,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}      
    //-->        
</script> 
<DIV class='module'>
      <DIV class='moduleHeader' >
      <table width="100%" border="0"><tr>
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/menu/vehicle.gif" width="20" height="17">车辆信息</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_6'><A href="vehicle/query/"><IMG src="../images/menu/more5.png" onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';" onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"  border=0 title='查看更多用车信息'></A></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_6')"><IMG style='border:0px' id='img_<?php echo $pos;?>_6' title=折叠 src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id='table_<?php echo $pos;?>_6'>
      <UL style="list-style-type:none;">
      <?php    
      $sql="select * from  vehicle_usage  where VU_STATUS!='0' order by VU_ID desc LIMIT 0, $num"; 
      $fsql->query($sql);
      if($fsql->num_rows()>0){
        $x=0;
        while($fsql->next_record()){
            $x++;
            $VU_ID = $fsql->f( "VU_ID" );
            $V_ID = $fsql->f( "V_ID" );
            $VU_STATUS = $fsql->f( "VU_STATUS" );
            $msql->query( "select V_NUM from vehicle  where V_ID='".$V_ID."'" );
            if ( $msql->next_record( ) )
            {
                $V_NUM = $msql->f( "V_NUM" );
            }
            $VU_USER = $fsql->f( "VU_USER" );
            $VU_USER_NAME=getuser_name($VU_USER);
            $status = $meetingstatus[0][$VU_STATUS];
            ?>
        <LI style="color:#333333"><?php echo $x;?>&nbsp;&nbsp;<a href="#" onClick="window.open('vehicle/usage_detail.php?VU_ID=<?php echo $VU_ID;?>','','height=330,width=400,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,resizable=yes');"><span style="cursor:hand;"><?php echo $V_NUM;?>&nbsp;&nbsp;</span><span style="cursor:hand;padding-right: 10PX;"><?php echo $VU_USER_NAME;?>&nbsp;&nbsp;<?php echo $fsql->f( "VU_START" );?>&nbsp;--&nbsp;<?php echo $fsql->f( "VU_END" );?>&nbsp;&nbsp;<?php echo $status;?>&nbsp;&nbsp;<?php if(strtotime($fsql->f( "VU_START" ))>=time()){
            ?><IMG style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 ><?php
        }?></span></A> 
            <?php
        }
      }
      ?>  
</UL></DIV></DIV></DIV>