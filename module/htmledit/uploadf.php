<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
include( $Htmledit_Path."/../includes/msql.php" );
$Insert_tag = "Yes";
$ServerPathAddress = "http://".$_SERVER['HTTP_HOST'];
$msql->query( "select * from htmluploadaddress where   Pathname='".$ServerPathAddress."'" );
if ( $msql->next_record( ) )
{
    $Insert_tag = "No";
}
$Fext = explode( ".", $filename );
if ( strtolower( $Fext[1] ) != "swf" || $ATTACHMENT == "" )
{
    succ_close( "图像文件格式有问题，可能是SWF文件经过处理了，请检查后再上传!" );
}
$Filename_Path = $USER_ID.time( );
$ServerPath = "http://".$_SERVER['HTTP_HOST'].( "/attachment/htmledit/".$USER_ID."/$Filename_Path/" );
$uploaduseridpath = $Htmledit_Path.( "/../attachment/htmledit/".$USER_ID );
$uploadpath = $Htmledit_Path.( "/../attachment/htmledit/".$USER_ID."/$Filename_Path" );
if ( $ATTACHMENT_NAME != "" )
{
    @mkdir( $uploaduseridpath, 511 );
    @mkdir( $uploadpath, 511 );
    @copy( $ATTACHMENT, $uploadpath."/$filename" );
    $msql->query( "insert into htmlupload values('0','".$USER_ID."','$Filename_Path','$uploadpath/$filename','$ServerPath$filename','$filename',NOW(),'flash')" );
    if ( $Insert_tag == "Yes" )
    {
        $msql->query( "insert into htmluploadaddress values('0','".$ServerPathAddress."')" );
    }
}
echo "<script>\r\nparent.parent.sSendURL('";
echo $ServerPath.$filename;
echo "');</script>";
?>
