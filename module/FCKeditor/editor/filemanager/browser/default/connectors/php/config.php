<?php
function getbrowserrootpath( )
{
    $sRealPath = realpath( "./" );
    $sSelfPath = $_SERVER['PHP_SELF'];
    $sSelfPath = substr( $sSelfPath, 0, strrpos( $sSelfPath, "/" ) );
    return substr( $sRealPath, 0, strlen( $sRealPath ) - strlen( $sSelfPath ) );
}

global $Config;
$JE_FilesPath = str_replace( "\\", "/", getbrowserrootpath( ) );
$JE_UserFilesPath = "/attachment/UserFiles/";
$UserFilesAbsolutePath = $JE_FilesPath."/attachment/UserFiles/";
$Config['Enabled'] = true;
$Config['UserFilesPath'] = $JE_UserFilesPath;
$Config['UserFilesAbsolutePath'] = $UserFilesAbsolutePath;
$Config['ForceSingleExtension'] = true;
$Config['AllowedExtensions']['File'] = array( );
$Config['DeniedExtensions']['File'] = array( "php", "php2", "php", "php4", "php5", "phtml", "pwml", "inc", "asp", "aspx", "ascx", "jsp", "cfm", "cfc", "pl", "bat", "exe", "com", "dll", "vbs", "js", "reg", "cgi" );
$Config['AllowedExtensions']['Image'] = array( "jpg", "gif", "jpeg", "png" );
$Config['DeniedExtensions']['Image'] = array( );
$Config['AllowedExtensions']['Flash'] = array( "swf", "fla" );
$Config['DeniedExtensions']['Flash'] = array( );
$Config['AllowedExtensions']['Media'] = array( "swf", "fla", "jpg", "gif", "jpeg", "png", "avi", "mpg", "mpeg" );
$Config['DeniedExtensions']['Media'] = array( );
?>
