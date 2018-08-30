<?php
echo "<HTML>\r\n<HEAD>\r\n<TITLE>上传文件中......</TITLE>\r\n<STYLE>\r\nBODY,P,TD,DIV,SPAN\r\n{\r\n\tfont-family:Arial;\r\n\tfont-size:11px;\r\n}\r\n</STYLE>\r\n</HEAD>\r\n<BODY>\r\n<p><b>上传文件</b></p>\r\n";
set_time_limit( 0 );
include_once( "cprogbar.php" );
CProgbar( $test2 );
$progbar = new CProgbar;
$progbar->init( $j );
$i = 0;
for ( ; $i < $j; ++$i )
{
    $progbar->step( );
    sleep( 1 );
}
$progbar->full( );
$progbar->text( "完成" );
echo "</BODY>\r\n</HTML>";
?>
