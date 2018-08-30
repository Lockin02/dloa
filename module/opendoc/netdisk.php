<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
?>
<HTML>
<HEAD>
<TITLE><?php echo $ATTACHMENT_NAME;?>文档在线阅读</TITLE>
<meta http-equiv="content-type" content="text/html;charset=gb2312">
<SCRIPT LANGUAGE="JavaScript" src="tangerocx.js"></SCRIPT>
<script>
function myload()
{

  TANGER_OCX_SetInfo();
  window.title=" Office 文档在线阅读";
}

function MY_SetMarkModify(flag)
{
  if(flag)
  {
     mflag1.className="TableHeader2";
     mflag2.className="TableHeader1";
  }
  else
  {
     mflag1.className="TableHeader1";
     mflag2.className="TableHeader2";
  }
  TANGER_OCX_SetMarkModify(flag);
}

function MY_ShowRevisions(flag)
{
  if(flag)
  {
     sflag1.className="TableHeader2";
     sflag2.className="TableHeader1";
  }
  else
  {
     sflag1.className="TableHeader1";
     sflag2.className="TableHeader2";
  }
  TANGER_OCX_ShowRevisions(flag);
}

function lock_ref()
{
  return;
}
</script>
</HEAD>

<BODY class="bodycolor" leftmargin="0" topmargin="0" onLoad="javascript:myload()" onunload="javascript:close_doc()">
<FORM NAME="form1" METHOD=post ACTION="" ENCTYPE="multipart/form-data">
<table width=100% height=100% class="small" cellspacing="1" cellpadding="3" align="center">
<tr width=100%>
<td valign=top width=80>
  <table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="0" align="center">

  </table>
</td>
<td width=100% valign="top">
<script src="../../includes/ntko/ntkooffice.js"></script>
</td>
</tr>
</table>
<script language="JScript" for=TANGER_OCX event="OnDocumentClosed()">
TANGER_OCX_OnDocumentClosed()
</script>
<script language="JScript">
var TANGER_OCX_str;
var TANGER_OCX_obj;
var close_op_flag=1;
function close_doc()
{
   document.all("TANGER_OCX").setAttribute("IsNoCopy",false);
   if(close_op_flag!=1)
   {
     msg='是否保存对  \'<?php echo $ATTACHMENT_NAME;?>\'  的修改？';
     if(window.confirm(msg))
        TANGER_OCX_SaveDoc(0);
   }
}
</script>
<script language="JScript" for=TANGER_OCX event="OnDocumentOpened(TANGER_OCX_str,TANGER_OCX_obj)">
TANGER_OCX_OnDocumentOpened(TANGER_OCX_str,TANGER_OCX_obj)
</script>
<SPAN ID="TANGER_OCX_op" style="display:none"><?php echo $OP;?></SPAN>
<SPAN ID="TANGER_OCX_filename" style="display:none"><?php echo $ATTACHMENT_NAME;?></SPAN>
<SPAN ID="TANGER_OCX_attachName" style="display:none"><?php echo $ATTACHMENT_NAME;?></SPAN>
<SPAN ID="TANGER_OCX_attachURL" style="display:none">read_doc.php?filename=<?php echo $ATTACHMENT_NAME;?>&uploaddir=<?php echo $ATTACHMENT_ID;?></SPAN>
<SPAN ID="TANGER_OCX_user" style="display:none"><?php echo $USERNAME;?></SPAN>
</FORM>
</BODY>
</HTML>
