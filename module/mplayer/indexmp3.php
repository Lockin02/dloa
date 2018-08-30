<?php
include( "../../includes/db.inc.php" );
$MEDIA_URL = "../../inc/attach.php?ATTACHMENT_ID=".$MEDIA_ID."&ATTACHMENT_NAME=$MEDIA_NAME";
if ( $step == "netdisk" )
{
    $MEDIA_URL = "../opendoc/read_doc.php?uploaddir=".$MEDIA_ID."&filename=$MEDIA_NAME";
}
echo "<html>\r\n<head>\r\n<title>";
echo $MEDIA_NAME;
echo "媒体播放器</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n</head>\r\n\r\n<body topmargin=\"0\" leftmargin=\"0\" rightmargin=\"0\" scroll=\"no\" ";
echo base64_decode( $SHOWCODE );
echo ">\r\n\r\n<table border=0 align=\"center\" class=\"small\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"100%\">\r\n  <tr class=\"TableHeader\" height=30>\r\n    <td>\r\n    \t<b>播放文件：";
echo $MEDIA_NAME;
echo "</b>\r\n    </td>\r\n  </tr>\r\n  <tr class=\"TableContent\" height=20>\r\n    <td><b>下载文件：</b><a href=\"";
echo $MEDIA_URL;
echo "\">";
echo $MEDIA_NAME;
echo "</a></td>\r\n  </tr>\r\n  <tr>\r\n    <td align=center valign=top>\r\n<object id=\"mplayer\" width=\"100%\" height=\"68\" classid=\"CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95\"\r\ncodebase=\"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715\"\r\nalign=\"baseline\" border=\"0\" standby=\"Loading Microsoft Windows Media Player components...\"\r\ntype=\"application/x-oleobject\">\r\n  <param name=\"FileName\" value=\"";
echo $MEDIA_URL;
echo "\">\r\n  <param name=\"ShowControls\" value=\"1\">\r\n  <param name=\"ShowPositionControls\" value=\"0\">\r\n  <param name=\"ShowAudioControls\" value=\"1\">\r\n  <param name=\"ShowTracker\" value=\"1\">\r\n  <param name=\"ShowDisplay\" value=\"0\">\r\n  <param name=\"ShowStatusBar\" value=\"1\">\r\n  <param name=\"AutoSize\" value=\"0\">\r\n  <param name=\"ShowGotoBar\" value=\"0\">\r\n  <param name=\"ShowCaptioning\" value=\"0\">\r\n  <param name=\"AutoStart\" value=\"1\">\r\n  <param name=\"PlayCount\" value=\"0\">\r\n  <param name=\"AnimationAtStart\" value=\"0\">\r\n  <param name=\"TransparentAtStart\" value=\"0\">\r\n  <param name=\"AllowScan\" value=\"0\">\r\n  <param name=\"EnableContextMenu\" value=\"1\">\r\n  <param name=\"ClickToPlay\" value=\"0\">\r\n  <param name=\"InvokeURLs\" value=\"1\">\r\n  <param name=\"DefaultFrame\" value=\"datawindow\">\r\n</object>\r\n<script>\r\nself.resizeTo(600,205);\r\n</script>\r\n</td>\r\n</tr>\r\n</table>\r\n\r\n</BODY>\r\n</HTML>\r\n";
?>
