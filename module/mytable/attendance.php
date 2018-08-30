<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"left";
?>
<DIV class='module'>
      <DIV class='moduleHeader' >
      <table width="100%" border="0"><tr>
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/menu/attendance.gif">个人考勤</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_11'><A href="#"  onclick="openTab('../general/attendance/personal/','个人考勤')"><IMG src="../images/menu/more5.png" onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';" onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"  border=0 title='查看更多个人考勤信息'></A></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_11')"><IMG style='border:0px' id='img_<?php echo $pos;?>_11' title=折叠 src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id=table_<?php echo $pos;?>_11>
      <UL style="list-style-type:none;">
      <?php 
      $NOW_DATE = date( "Y-m-d" );
      $sqlStr="select  USER_PRIV,DEPT_ID  from user where  USER_ID='".$USER_ID."'";
      $fsql->query( $sqlStr );      
      while ( $fsql->next_record( ) ){
        $checkDept=$fsql->f("DEPT_ID");
        $checkPriv=$fsql->f("USER_PRIV");
      }
      $sql="select * from attend_leave  where USER_ID='$USER_ID' and STATUS='2' order by LEAVE_ID desc  limit 0,$num";
      $fsql->query($sql);
      if($fsql->num_rows()>0){
        $x=0;
        while($fsql->next_record()){
            $x++;
            ?>
        <LI style="color:#333333"><?php echo $x;?>&nbsp;&nbsp;<a href='attendance/personal/' ><span style="cursor:hand;"><?php echo $fsql->f( "LEAVE_TYPE" );?>&nbsp;&nbsp;<?php echo $fsql->f( "LEAVE_DATE1" );?>&nbsp;&nbsp;</span><span style="cursor:hand;padding-right: 10PX;"><?php echo $fsql->f( "LEAVE_DATE2" );?>&nbsp;&nbsp;<?php echo $fsql->f( "DAYS" )."天";?>&nbsp;&nbsp;<?php if(strtotime($fsql->f( "CreatDT" ))>=time()-3 * 24 * 3600){
            ?><IMG style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 ><?php
        }?></span></A> 
            <?php
        }
      }
      ?>  
</UL></DIV></DIV></DIV>