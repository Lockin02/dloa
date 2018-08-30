<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"right";
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
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;">
                    <img style="border:0px" src="../images/menu/notify.gif">OA操作指引</span></td>
            <td width="90%" class='more_info' align="right"></td>
            <td nowrap class="icon" align="left" valign="top"><span >
                    <A href="#" onClick="resize('<?php echo $pos;?>_5')">
                        <IMG style='border:0px' id='img_<?php echo $pos;?>_5' title=折叠 src="../images/verpic_close.gif" ></A></SPAN>
            </td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id='table_<?php echo $pos;?>_5'>
      <UL style="list-style-type:none;">
	  <LI  style="color:#333333;">
        1&nbsp;&nbsp;
        <A href="../attachment/help/用户手册——合同建立V1.0.2.docx" title="" target="_blank">
        <span style="cursor:hand;">
                用户手册——合同建立V1.0.2&nbsp;&nbsp;
                2015-03-11 &nbsp;&nbsp;
                <IMG  style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 >
        </span>
        </A> 
    </LI>
	        <LI  style="color:#333333;">
        2&nbsp;&nbsp;
        <A href="../attachment/help/OA操作手册--其他类合同费用分摊.pdf" title="" target="_blank">
        <span style="cursor:hand;">
                OA操作手册-其他类合同费用分摊&nbsp;&nbsp;
                2015-02-10 &nbsp;&nbsp;
                <IMG  style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 >
        </span>
        </A> 
    </LI>
      <LI  style="color:#333333;">
        3&nbsp;&nbsp;
        <A href="../attachment/help/固定资产申请流程图.pdf" title="" target="_blank">
        <span style="cursor:hand;">
                OA操作手册-固定资产申请流程&nbsp;&nbsp;
                2013-03-11 &nbsp;&nbsp;
        </span>
        </A> 
    </LI>
      <LI  style="color:#333333;">
        4&nbsp;&nbsp;
        <A href="../attachment/help/OA操作手册-费用报销.pdf" title="" target="_blank">
        <span style="cursor:hand;">
                OA操作手册-费用报销&nbsp;&nbsp;
                2013-03-11 &nbsp;&nbsp;
        </span>
        </A> 
    </LI>
      <LI  style="color:#333333;">
        5&nbsp;&nbsp;
        <A href="../attachment/help/OA操作手册-员工借试用.pdf" title="" target="_blank">
        <span style="cursor:hand;">
                OA操作手册-员工借试用&nbsp;&nbsp;
                2012-09-10 &nbsp;&nbsp;
        </span>
        </A> 
    </LI>
      <LI  style="color:#333333;">
        6&nbsp;&nbsp;
        <A href="../attachment/help/OA操作手册-请休假流程.pdf" title="" target="_blank">
        <span style="cursor:hand;">
                OA操作手册-请休假流程&nbsp;&nbsp;
                2012-03-31 &nbsp;&nbsp;
        </span>
        </A> 
    </LI>
    <LI  style="color:#333333;">
        7&nbsp;&nbsp;
        <A href="../attachment/help/OA操作手册-非销售类合同审批及付款申请流程.pdf" title="" target="_blank">
        <span style="cursor:hand;">
                OA操作手册-非销售类合同审批及付款申请流程&nbsp;&nbsp;
                2012-03-31
        </span>
        </A> 
    </LI>
      <LI  style="color:#333333;">
        8&nbsp;&nbsp;
        <A href="../attachment/help/OA操作手册-借款流程.pdf" title="" target="_blank">
        <span style="cursor:hand;">
                OA操作手册-借款流程&nbsp;&nbsp;
                2012-03-31 &nbsp;&nbsp;
        </span>
        </A> 
    </LI>
    <LI  style="color:#333333;">
        9&nbsp;&nbsp;
        <A href="../attachment/help/OA操作手册-档案户口调动.pdf" title="" target="_blank">
        <span style="cursor:hand;">
                OA操作手册-档案户口调动&nbsp;&nbsp;
                2012-03-31 &nbsp;&nbsp;
                
        </span>
        </A> 
    </LI>
      
          
</UL></DIV></DIV></DIV>