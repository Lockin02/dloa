<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"left";
?>
<script language="javascript">

function open_weekly(id)                
{                                                  
    URL="weekly/read_weekly.php?read_id="+id;                                                         
    myleft=(screen.availWidth-500)/2;       
     window.open(URL,"read_notify","height=550,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}         

</script> 
<DIV class='module'>
      <DIV class='moduleHeader' >
      <table width="100%" border="0"><tr>
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/menu/diary.gif">工作周报</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_13'><A href="weekly/"><IMG src="../images/menu/more5.png" onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';" onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"  border=0 title='查看更多工作周报信息'></A></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_13')"><IMG style='border:0px' id='img_<?php echo $pos;?>_13' title=折叠 src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id=table_<?php echo $pos;?>_13>
      <UL style="list-style-type:none;">
      <?php 
      $NOW_DATE = date( "Y-m-d" );
      $sqlStr="select  USER_PRIV,DEPT_ID  from user where  USER_ID='".$USER_ID."'";
      $fsql->query( $sqlStr );      
      while ( $fsql->next_record( ) ){
        $checkDept=$fsql->f("DEPT_ID");
        $checkPriv=$fsql->f("USER_PRIV");
      }
      $sql="select * from weekly  where USER_ID='$USER_ID' order by Flag , id desc LIMIT 0, $num ";
      $fsql->query($sql);
      if($fsql->num_rows()>0){
        $x=0;
        while($fsql->next_record()){
            $x++;
            ?>
        <LI style="color:#333333"><?php echo $x;?>&nbsp;&nbsp;<a href="javascript:open_weekly('<?php echo $fsql->f( 'id' );?>');" ><span style="cursor:hand;"><?php if($fsql->f('type')==0){echo "<font color=green>单周报</font>";}elseif($fsql->f('type')==1){echo "<font color=blue>双周报</font>";}else{echo "<font color=red>月报</font>";}?>&nbsp;&nbsp;<?php echo $fsql->f( "w_num" );?>&nbsp;&nbsp;</span><span style="cursor:hand;padding-right: 10PX;"><?php echo cut_str($fsql->f( "title" ), 20,0,'GBK');?>&nbsp;&nbsp;<?php if(strtotime($fsql->f( "CreateDT" ))>=time()-3 * 24 * 3600){
            ?><IMG style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 ><?php
        }?></span></A> 
            <?php
        }
      }
      ?>  
</UL></DIV></DIV></DIV>