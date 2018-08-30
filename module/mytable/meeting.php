<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"left";
?>
<script language="javascript">
function detail(readid)
{
 var URL;
 URL="equipment/service/detail.php?readid="+readid;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_notify","height=400,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,resizable=yes");
}        
</script> 
<DIV class='module'>
      <DIV class='moduleHeader' >
      <table width="100%" border="0"><tr>
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/menu/meeting_tab.gif">会议安排</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_7'><A href="meeting/"><IMG src="../images/menu/more5.png" onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';" onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"  border=0 title='查看更多会议安排信息'></A></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_7')"><IMG style='border:0px' id='img_<?php echo $pos;?>_7' title=折叠 src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id=table_<?php echo $pos;?>_7>
      <UL style="list-style-type:none;">
      <?php 
      $sql="select * from meeting  where M_PROPOSER='".$USER_ID."' order by M_ID desc limit 0,$num";
      $fsql->query($sql);
      if($fsql->num_rows()>0){
        $x=0;
        while($fsql->next_record()){
            $x++;
            $msql->query( "select * from meeting_room  where MR_ID='".$fsql->f( "M_ROOM" )."'" );
            $MR_NAME = "";
            if ( $msql->next_record( ) )
            {
                $MR_NAME = $msql->f( "MR_NAME" );
            }
            $status="";
            if($fsql->f("M_STATUS")==0)
                $status="待批";
            elseif($fsql->f("M_STATUS")==1)
                $status="已准";
            elseif($fsql->f("M_STATUS")==2)
                $status="未批准";
            elseif($fsql->f("M_STATUS")==3)
                $status="进行中";
            ?>
        <LI style="color:#333333"><?php echo $x;?>&nbsp;&nbsp;<a href="#" onclick="javascript:window.open('meeting/meeting_detail.php?M_ID=<?php echo $fsql->f('M_ID');?>','','height=350,width=450,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,resizable=yes');" ><span style="cursor:hand;"><?php echo $MR_NAME ;?>&nbsp;&nbsp;<?php echo cut_str($fsql->f('M_NAME'),20,0,"GBK");?>&nbsp;&nbsp;<?php echo substr($fsql->f( "M_START" ),0,10);?>&nbsp;&nbsp;</span><span style="cursor:hand;padding-right: 10PX;"><?php echo $status;?>&nbsp;&nbsp;<?php if(strtotime($fsql->f( "M_START" ))>=time()-3 * 24 * 3600){?><IMG style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 ><?php
        }?></span></A> 
            <?php
        }
      }
      ?>  
</UL></DIV></DIV></DIV>