<?php
function doresponse( )
{
    do
    {
        if ( isset( $_GET['Command'] ) && isset( $_GET['Type'] ) && isset( $_GET['CurrentFolder'] ) )
            break;
        break;
    } while( 0 );
    $sCommand = $_GET['Command'];
    $sResourceType = $_GET['Type'];
    $sCurrentFolder = $_GET['CurrentFolder'];
    do
    {
        if ( in_array( $sResourceType, array( "File", "Image", "Flash", "Media" ) ) )
            break;
        break;
    } while( 0 );
    do
    {
        if ( ereg( "/\$", $sCurrentFolder ) )
            break;
        $sCurrentFolder .= "/";
    } while( 0 );
    if ( strpos( $sCurrentFolder, "/" ) !== 0 )
    {
        $sCurrentFolder = "/".$sCurrentFolder;
    }
    if ( strpos( $sCurrentFolder, ".." ) )
    {
        senderror( 102, "" );
    }
    if ( $sCommand == "FileUpload" )
    {
        fileupload( $sResourceType, $sCurrentFolder );
    }
    else
    {
        createxmlheader( $sCommand, $sResourceType, $sCurrentFolder );
    case "GetFolders" :
        getfolders( $sResourceType, $sCurrentFolder );
    case "GetFoldersAndFiles" :
        getfoldersandfiles( $sResourceType, $sCurrentFolder );
    case "CreateFolder" :
        createfolder( $sResourceType, $sCurrentFolder );
    default :
        createxmlfooter( );
        exit( );
    }

ob_start( );
include( "config.php" );
include( "util.php" );
include( "io.php" );
include( "basexml.php" );
include( "commands.php" );
do
{
    if ( $Config['Enabled'] )
        break;
    senderror( 1, "This connector is disabled. Please check the \"editor/filemanager/browser/default/connectors/php/config.php\" file" );
} while( 0 );
$UserFilesPath = "";
if ( isset( $Config['UserFilesPath'] ) )
{
    $UserFilesPath = $Config['UserFilesPath'];
}
else if ( isset( $_GET['ServerPath'] ) )
{
    $UserFilesPath = $_GET['ServerPath'];
}
else
{
    $UserFilesPath = "/UserFiles/";
}
do
{
    if ( ereg( "/\$", $UserFilesPath ) )
        break;
    $UserFilesPath .= "/";
} while( 0 );
do
{
    if ( 0 < strlen( $Config['UserFilesAbsolutePath'] ) )
    {
        $UserFilesDirectory = $Config['UserFilesAbsolutePath'];
        if ( ereg( "/\$", $UserFilesDirectory ) )
            break;
        $UserFilesDirectory .= "/";
        break;
    }
    else
    {
        $UserFilesDirectory = getrootpath( ).$UserFilesPath;
    }
} while( 0 );
doresponse( );
?>
