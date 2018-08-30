<?php
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\r\n<html>\r\n\t<head>\r\n\t\t<title>FCKeditor - Samples - Posted Data</title>\r\n\t\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n\t\t<meta name=\"robots\" content=\"noindex, nofollow\">\r\n\t\t<link href=\"../sample.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n\t</head>\r\n\t<body>\r\n\t\t<h1>FCKeditor - Samples - Posted Data</h1>\r\n\t\tThis page lists all data posted by the form.\r\n\t\t<hr>\r\n\t\t<table width=\"100%\" border=\"1\" cellspacing=\"0\" bordercolor=\"#999999\">\r\n\t\t\t<tr style=\"FONT-WEIGHT: bold; COLOR: #dddddd; BACKGROUND-COLOR: #999999\">\r\n\t\t\t\t<td nowrap>Field Name&nbsp;&nbsp;</td>\r\n\t\t\t\t<td>Value</td>\r\n\t\t\t</tr>\r\n";
if ( isset( $_POST ) )
{
    $postArray =& $_POST;
}
else
{
    $postArray =& $HTTP_POST_VARS;
}
foreach ( $postArray as $sForm=>$value )
{
    $postedValue = htmlspecialchars( stripslashes( $value ) );
    echo "\t\t\t<tr>\r\n\t\t\t\t<td valign=\"top\" nowrap><b>";
    echo $sForm;
    echo "</b></td>\r\n\t\t\t\t<td width=\"100%\">";
    echo $postedValue;
    echo "</td>\r\n\t\t\t</tr>\r\n";
}
echo "\t\t</table>\r\n\t</body>\r\n</html>\r\n";
?>
