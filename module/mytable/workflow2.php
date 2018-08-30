<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"left";
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
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/w_vActionSignIn.gif">待办公文</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_4'><A href="vote/person.php"><IMG src="../images/menu/more5.png" onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';" onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"  border=0 title='查看更多待办公文信息'></A></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_4')"><IMG style='border:0px' id='img_<?php echo $pos;?>_4' title=折叠 src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id='table_<?php echo $pos;?>_4'>
      <UL style="list-style-type:none;">
      <?php 
      $NOW_DATE = date( "Y-m-d" );
      $sqlStr="select  USER_PRIV,DEPT_ID  from user where  USER_ID='".$USER_ID."'";
      $fsql->query( $sqlStr );      
      while ( $fsql->next_record( ) ){
        $checkDept=$fsql->f("DEPT_ID");
        $checkPriv=$fsql->f("USER_PRIV");
      }
      if($USER_ID=='admin'){
        $sql="select * from work_plan  where1 and STATUS_TAG ='1' order by PLAN_ID desc LIMIT 0, $num";
      }else{
        $sql="select * from work_plan  where find_in_set('".$DEPT_ID."',TO_ID)>0 and STATUS_TAG ='1' order by PLAN_ID desc LIMIT 0, $num"; 
      }
      $fsql->query($sql);
      if($fsql->num_rows()>0){
        $x=0;
        while($fsql->next_record()){
            $x++;
            ?>
        <LI style="color:#333333"><?php echo $x;?>&nbsp;&nbsp;<a href="javascript:plan_detail('<?php echo $fsql->f( "PLAN_ID" );?>');"><span style="cursor:hand;"><?php echo $fsql->f( "NAME" );?>&nbsp;&nbsp;</span><span style="cursor:hand;padding-right: 10PX;"><?php echo $fsql->f( "BEGIN_DATE" );?>&nbsp;--&nbsp;<?php echo $fsql->f( "END_DATE" );?></span></A> 
            <?php
        }
      }
      ?>  
</UL></DIV></DIV></DIV>