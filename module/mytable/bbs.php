<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"left";
$bbspt=empty($_SESSION["COM_BRN_PT"])||$_SESSION["COM_BRN_PT"]=='dl'?'':$_SESSION["COM_BRN_PT"];
?>
<DIV class='module'>
      <DIV class='moduleHeader' >
      <table width="100%" border="0"><tr>
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/menu/meeting_tab.gif">论坛</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_7'><A href="#" onclick="openTab('../../<?php echo $bbspt;?>bbs/','论坛')" ><IMG src="../images/menu/more5.png" onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';" onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"  border=0 title='查看更多讨论区信息'></A></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_7')"><IMG style='border:0px' id='img_<?php echo $pos;?>_7' title=折叠 src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id=table_<?php echo $pos;?>_7>
      <UL style="list-style-type:none;">
      <?php
      $url = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
      if(strpos($url,'oa.dinglicom')===false){
          $bbsurl = 'https://'.substr($url,0,-20).'/'.$bbspt.'bbs/home.php';
      }else{
          $bbsurl = 'https://172.16.1.103/'.$bbspt.'bbs/home.php';
      }
//      $bbsnew = file_get_contents($bbsurl);
//       echo iconv("UTF-8","GB2312//IGNORE",$bbsnew);
      ?> 
      <script type="text/javascript" src="../app/<?php echo $bbspt;?>bbs_login.php"></script>
</UL></DIV></DIV></DIV>