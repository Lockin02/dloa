<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
include( $Htmledit_Path."/../includes/msql.php" );
include( $Htmledit_Path."/../includes/fsql.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
if ( $step == "del" )
{
    $msql->query( "delete from htmlupload where ID='".$ifid."' and Userid='$USER_ID'" );
    @unlink( $Fname );
}
if ( $step == "ok" )
{
    echo "<script>parent.parent.sSendURL('".$nowfile."');</script>";
}
$msql->query( "select count(ID) from htmlupload where Userid='".$USER_ID."' and Ftype='image'" );
while ( $msql->next_record( ) )
{
    $Fidnum = $msql->f( "count(ID)" );
}
$ImgPath = "http://".$_SERVER['HTTP_HOST'];
echo "\r\n<HTML><HEAD><TITLE>选择图片</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=gb2312\">\r\n<style type=\"text/css\">\r\n.style1 {color: #FFFFFF}\r\n</style>\r\n<SCRIPT>\r\nfunction cm(nn,fname,fpath){\r\nqus=confirm(\"确实要删除文件吗？\")\r\nif(qus!=0){\r\nwindow.location='sliblook.php?step=del&ifid='+nn+'&Fname='+fname+'&Fpath='+fpath;\r\n}\r\n}\r\n</SCRIPT>\r\n</HEAD>\r\n<BODY leftMargin=0 topMargin=0 marginheight=\"0\" marginwidth=\"0\" bgcolor=\"#666666\">\r\n";
if ( 0 < $Fidnum )
{
    echo "<table width=\"545\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" align=\"center\">\r\n<tr bgcolor=\"#FFFFFF\" align=\"center\">\r\n";
    $kk = 1;
    $msql->query( "select * from htmlupload where Userid='".$USER_ID."' and Ftype='image' order by ID desc" );
    while ( $msql->next_record( ) )
    {
        $Fname = $msql->f( "Pathname" );
        $Filepath = $msql->f( "filepath" );
        $Fid = $msql->f( "ID" );
        $Fpath = $msql->f( "Fpath" );
        $fsql->query( "select * from htmluploadaddress where 1" );
        while ( $fsql->next_record( ) )
        {
            $Pathname = $fsql->f( "Pathname" );
            $Fname = str_replace( $Pathname, $ImgPath, $Fname );
        }
        echo "<td height=136 width=108>\r\n<table width=100% height=128 border=0 cellspacing=0 cellpadding=0 align=center>\r\n<tr>\r\n\r\n<td align=center height=108>\r\n<img src=";
        echo $Fname;
        echo " width=\"100\" height=\"83\" onDblClick=\"window.location='sliblook.php?step=ok&nowfile=";
        echo $Fname;
        echo "'\">\r\n</td></tr><tr><td height=28 bgcolor=#d4d0c8 align=center>\r\n<INPUT onClick=\"window.location='sliblook.php?step=ok&nowfile=";
        echo $Fname;
        echo "'\" type=button value=选中>\r\n<INPUT onClick=\"cm('";
        echo $Fid;
        echo "','";
        echo $Filepath;
        echo "','";
        echo $Fpath;
        echo "')\" type=button value=删除>\r\n</td></tr>\r\n\r\n\r\n\r\n</table>\r\n</td>\r\n\r\n\r\n\r\n";
        if ( $kk == 5 )
        {
            echo "</tr><tr bgcolor='#FFFFFF' align='center'>";
            $kk = 0;
        }
        ++$kk;
    }
    if ( $kk < 6 )
    {
        $i = $kk;
        for ( ; $i <= 5; ++$i )
        {
            echo "<td height=136 width=108></td>";
        }
    }
    echo "\r\n</td>\r\n</table>\r\n\r\n\r\n";
}
else
{
    echo "<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p align=center class=style1>您尚未上传图片！</p>";
}
echo "</BODY></HTML>\r\n";
?>
