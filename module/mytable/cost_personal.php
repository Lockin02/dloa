<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"left";
?>
<script language="javascript">
    <!--//
function openurl(VOTE_ID)
{
 URL="vote/vote_action.php?Pid="+VOTE_ID;
 myleft=(screen.availWidth-100)/2;
 window.open(URL,"投票","height=600,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function openradiourl(VOTE_ID)
{
 URL="vote/vote_radio.php?Pid="+VOTE_ID;
 myleft=(screen.availWidth-100)/2;
 window.open(URL,"投票","height=600,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}       
    //-->        
</script> 
<DIV class='module'>
      <DIV class='moduleHeader' >
      <table width="100%" border="0"><tr>
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/menu/notify.gif">个人费用</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_2'><A href="#" onclick="openTab('../general/costmanage/reim','个人费用')"><IMG src="../images/menu/more5.png" onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';" onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"  border=0 title='查看更多个人费用信息'></A></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_2')"><IMG style='border:0px' id='img_<?php echo $pos;?>_2' title=折叠 src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id=table_<?php echo $pos;?>_2>
      <UL style="list-style-type:none;">
      <?php 
      $NOW_DATE = date( "Y-m-d" );
      $sqlStr="select  USER_PRIV,DEPT_ID  from user where  USER_ID='".$USER_ID."'";
      $fsql->query( $sqlStr );      
      while ( $fsql->next_record( ) ){
        $checkDept=$fsql->f("DEPT_ID");
        $checkPriv=$fsql->f("USER_PRIV");
      }
      $sql="select *  from cost_summary_list where (InputMan ='".$USER_ID."' or CostMan='".$USER_ID."') order by UpdateDT desc limit 0, 5 ";
      $fsql->query($sql);
      if($fsql->num_rows()>0){
        $x=0;
        while($fsql->next_record()){
            $x++;
            ?>
        <LI style="color:#333333"><?php echo $x;?>&nbsp;&nbsp;<a href='#' onclick="openTab('../general/costmanage/reim','个人费用')"><span style="cursor:hand;"><?php if($fsql->f('isProject')==0) echo "部门报销"; if($fsql->f('isProject')==1) echo "项目报销"?>&nbsp;&nbsp;<?php echo cut_str($fsql->f('BillNo'), 20, 0, 'GBK') ;?>&nbsp;&nbsp;</span><span style="cursor:hand;padding-right: 10PX;">
        <?php 
        $msql->query("select Name from area where ID='".$fsql->f( "Area" )."'");
        if($msql->next_record())
        echo $msql->f("Name");?>&nbsp;&nbsp;<?php echo $fsql->f( "Status" );?>&nbsp;&nbsp;<?php if(strtotime($fsql->f( "UpdateDT" ))>=time()-3 * 24 * 3600){
            ?><IMG style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 ><?php
        }?></span></A> 
            <?php
        }
      }
      $loan_num=$num-$fsql->num_rows();
      $sql="select * from loan_list where Debtor ='".$USER_ID."' order by ID desc limit 0,$loan_num";
      $fsql->query($sql);
      while($fsql->next_record()){
        $x++;
        ?>
        <LI style="color:#333333"><?php echo $x;?>&nbsp;&nbsp;<a href='costmanage/loan/bill.php?&status=<?php echo $fsql->f( "Status" );?>&ID=<?php echo $fsql->f('ID')?>' ><span style="cursor:hand;"><?php echo "个人借款";?>&nbsp;&nbsp;<?php echo $fsql->f('ID');?>&nbsp;&nbsp;</span><span style="cursor:hand;padding-right: 10PX;">&nbsp;&nbsp;<?php echo $fsql->f( "Status" );?>&nbsp;&nbsp;<?php if(strtotime($fsql->f( "PayDT" ))>=time()-3 * 24 * 3600){
        ?><IMG style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 ><?php
        }?></span></A>
        <?php
      }
      ?>  
</UL></DIV></DIV></DIV>