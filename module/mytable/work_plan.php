<?php 
//ģ��id ��鿴 config.php
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
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/w_vActionSignIn.gif">�����ƻ�</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_3'><A href="work_plan/show"><IMG src="../images/menu/more5.png" onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';" onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"  border=0 title='�鿴���๤���ƻ�'></A></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_3')"><IMG style='border:0px' id='img_<?php echo $pos;?>_3' title=�۵� src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id='table_<?php echo $pos;?>_3'>
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
        $sql="select * from work_plan  where 1 and STATUS_TAG ='1' and END_DATE>='".date('Y-m-d')."' order by PLAN_ID desc LIMIT 0, $num";
      }else{
        $sql="select * from work_plan  where ( find_in_set('".$checkDept."',TO_ID)>0 or TO_ID='ALL_DEPT' ) and STATUS_TAG ='1' and END_DATE>='".date('Y-m-d')."' order by PLAN_ID desc LIMIT 0, $num"; 
      }
      $fsql->query($sql);
      if($fsql->num_rows()>0){
        $x=0;
        while($fsql->next_record()){
            $x++;
            ?>
        <LI style="color:#333333"><?php echo $x;?>&nbsp;&nbsp;<a href="javascript:plan_detail('<?php echo $fsql->f( "PLAN_ID" );?>');"><span style="cursor:hand;"><?php echo $fsql->f( "NAME" );?>&nbsp;&nbsp;</span><span style="cursor:hand;padding-right: 10PX;"><?php echo $fsql->f( "BEGIN_DATE" );?>&nbsp;--&nbsp;<?php echo $fsql->f( "END_DATE" );?>&nbsp;&nbsp;<?php if(strtotime($fsql->f( "BEGIN_DATE" ))>=time()-3 * 24 * 3600){
            ?><IMG style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 ><?php
        }?></span></a> 
            <?php
        }
      }
      ?>  
</UL></DIV></DIV></DIV>