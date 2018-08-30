<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"left";
?>
<script language="javascript">
    <!--//
function news_detail(PLAN_ID)
{
 URL="news/show/show_news.php?NEWS_ID="+PLAN_ID;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_work_plan","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}      
    //-->        
</script> 
<DIV class='module'>
      <DIV class='moduleHeader' >
      <table width="100%" border="0"><tr>
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/menu/notify.gif">新闻</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_5'><A href="news/show/"><IMG src="../images/menu/more5.png" onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';" onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"  border=0 title='查看更多新闻信息'></A></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_5')"><IMG style='border:0px' id='img_<?php echo $pos;?>_5' title=折叠 src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id='table_<?php echo $pos;?>_5'>
      <UL style="list-style-type:none;">
      <?php 
         
      $sql="select * from news where  ExaStatus='完成' order by ID desc LIMIT 0, $num";      
      $fsql->query($sql);
      if($fsql->num_rows()>0){
        $x=0;
        while($fsql->next_record()){
            $x++;
            ?>
        <LI style="color:#333333"><?php echo $x;?>&nbsp;&nbsp;<a href="javascript:news_detail('<?php echo $fsql->f( "ID" );?>');"><span style="cursor:hand;"><?php echo $fsql->f( "SUBJECT" );?>&nbsp;&nbsp;</span><span style="cursor:hand;padding-right: 10PX;"><?php echo $fsql->f( "NEWS_TIME" );?>&nbsp;&nbsp;<?php if(strtotime($fsql->f( "NEWS_TIME" ))>=time()-3 * 24 * 3600){
            ?><IMG  style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 ><?php
        }?></span></A>
            <?php
        }
      }
      ?>  
</UL></DIV></DIV></DIV>