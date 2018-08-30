<?PHP 
session_start();
$pathStr="attachment/ueditor/".$_SESSION['USER_ID'];
echo "var UEDITOR_UPFILE_URL='".$pathStr."/';";
?>
