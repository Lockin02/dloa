<?php
function geturlfrompath( $resourceType, $folderPath )
{
    if ( $resourceType == "" )
    {
        return removefromend( $UserFilesPath, "/" ).$folderPath;
    }
    else
    {
        return $UserFilesPath.$resourceType.$folderPath;
    }
}

function removeextension( $fileName )
{
    return substr( $fileName, 0, strrpos( $fileName, "." ) );
}

function servermapfolder( $resourceType, $folderPath )
{
    $sResourceTypePath = $UserFilesDirectory.$resourceType."/";
    createserverfolder( $sResourceTypePath );
    return $sResourceTypePath.removefromstart( $folderPath, "/" );
}

function getparentfolder( $folderPath )
{
    $sPattern = "-[/\\\\][^/\\\\]+[/\\\\]?\$-";
    return preg_replace( $sPattern, "", $folderPath );
}

function createserverfolder( $folderPath )
{
    $sParent = getparentfolder( $folderPath );
    do
    {
        if ( file_exists( $sParent ) )
            break;
        $sErrorMsg = createserverfolder( $sParent );
        if ( $sErrorMsg != "" )
        {
        }
        return $sErrorMsg;
    } while( 0 );
    do
    {
        if ( file_exists( $folderPath ) )
            break;
        error_reporting( 0 );
        ini_set( "track_errors", "1" );
        $oldumask = umask( 0 );
        mkdir( $folderPath, 511 );
        umask( $oldumask );
        $sErrorMsg = $php_errormsg;
        ini_restore( "track_errors" );
        ini_restore( "error_reporting" );
        return $sErrorMsg;
    } while( 0 );
    return "";
}

function getrootpath( )
{
    $sRealPath = realpath( "./" );
    $sSelfPath = $_SERVER['PHP_SELF'];
    $sSelfPath = substr( $sSelfPath, 0, strrpos( $sSelfPath, "/" ) );
    return substr( $sRealPath, 0, strlen( $sRealPath ) - strlen( $sSelfPath ) );
}

?>
