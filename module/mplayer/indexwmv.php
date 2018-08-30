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
echo "</a></td>\r\n  </tr>\r\n  <tr>\r\n    <td align=center valign=top>\r\n<object classid=\"clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6\" id=\"phx\" width=\"100%\" height=\"100%\">\r\n<param name=\"URL\" value=\"";
echo $MEDIA_URL;
echo "\">\r\n<param name=\"rate\" value=\"1\">\r\n<param name=\"balance\" value=\"0\">\r\n<param name=\"currentPosition\" value=\"0\">\r\n<param name=\"defaultFrame\" value>\r\n<param name=\"playCount\" value=\"1\">\r\n<param name=\"autoStart\" value=\"-1\">\r\n<param name=\"currentMarker\" value=\"0\">\r\n<param name=\"invokeURLs\" value=\"-1\">\r\n<param name=\"baseURL\" value>\r\n<param name=\"volume\" value=\"50\">\r\n<param name=\"mute\" value=\"0\">\r\n<param name=\"uiMode\" value=\"full\">\r\n<param name=\"stretchToFit\" value=\"0\">\r\n<param name=\"windowlessVideo\" value=\"0\">\r\n<param name=\"enabled\" value=\"-1\">\r\n<param name=\"enableContextMenu\" value=\"-1\">\r\n<param name=\"fullScreen\" value=\"0\">\r\n<param name=\"SAMIStyle\" value>\r\n<param name=\"SAMILang\" value>\r\n<param name=\"SAMIFilename\" value>\r\n<param name=\"captioningID\" value>\r\n<param name=\"enableErrorDialogs\" value=\"0\">\r\n<param name=\"_cx\" value=\"6482\">\r\n<param name=\"_cy\" value=\"6350\">\r\n</object>\r\n</td>\r\n</tr>\r\n</table>\r\n\r\n</BODY>\r\n</HTML>\r\n";
?>
