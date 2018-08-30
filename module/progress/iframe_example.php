<?php
set_time_limit( 0 );
include_once( "cprogbar.php" );
CProgbar( "test" );
$progbar = new CProgbar;
if ( $op == "progbar" )
{
    $progbar->init( 100, "_parent" );
    if ( $cur == $done )
    {
        $progbar->full( );
        $progbar->text( "Done.", "#FFFFFF", "", "BOLD" );
        exit( );
    }
    CProgbar( "test", 100 * $cur / $done );
    $progbar = new CProgbar;
    $progbar->step( );
    ++$cur;
    sleep( 1 );
    exit( );
}
echo "<HTML>\r\n<HEAD>\r\n<TITLE>CProgBar Iframe Example</TITLE>\r\n<STYLE>\r\nBODY,P,TD,DIV,SPAN\r\n{\r\n\tfont-family:Arial;\r\n\tfont-size:11px;\r\n}\r\n</STYLE>\r\n<script>\r\nfunction run_progbar()\r\n{\r\n\tdocument.frames['ProgbarCtrl'].location.href='";
echo basename( $PATH_TRANSLATED );
echo "?op=progbar&done=10&cur=0';\r\n}\r\n</script>\r\n</HEAD>\r\n\t\t\r\n<BODY>\r\n<p><b>Test</b><br>";
echo $progbar->show( );
echo "<p>\r\n<input onclick='run_progbar()' class='button' type='button' value='Run'>\r\n<iframe style='display:none' name='ProgbarCtrl' width=100% height=100></iframe>\r\n</BODY>\r\n</HTML>\r\n";
?>
