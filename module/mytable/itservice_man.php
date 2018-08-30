<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"left";
?>
<script language="javascript">
function detailit(readid)
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
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/menu/equip_tab.gif">IT维修管理</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_15'><A href="#" onclick="openTab('../general/equipment/service/manage.php?keytype=-1','IT维修申请表')"><IMG src="../images/menu/more5.png" onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';" onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"  border=0 title='查看更多IT维修申请信息'></A></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_15')"><IMG style='border:0px' id='img_<?php echo $pos;?>_15' title=折叠 src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id=table_<?php echo $pos;?>_15>
      <UL style="list-style-type:none;">
      <?php 
      $sql="select * from equi_service where 1 order by flag, appdate limit 0,$num ";
      $fsql->query($sql);
      if($fsql->num_rows()>0){
        $x=0;
        while($fsql->next_record()){
            $x++;
            ?>
        <LI style="color:#333333"><?php echo $x;?>&nbsp;&nbsp;<a href="javascript:detailit('<?php echo $fsql->f('id');?>');" ><span style="cursor:hand;"><?php echo $fsql->f('id');?>&nbsp;&nbsp;<?php echo getUserNameById($fsql->f('appid'));?>&nbsp;&nbsp;<?php echo substr($fsql->f( "appdate" ),0,10);?>&nbsp;&nbsp;</span><span style="cursor:hand;padding-right: 10PX;"><?php if($fsql->f('flag')==0) echo "<font color='red'>等待中</font>"; if($fsql->f('flag')==1) echo "<font color='green'>已完成</font>";?>&nbsp;&nbsp;<?php if(strtotime($fsql->f( "appdate" ))>=time()-3 * 24 * 3600){?><IMG style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 ><?php
        }?></span></A> 
            <?php
        }
      }
      ?>  
</UL></DIV></DIV></DIV>