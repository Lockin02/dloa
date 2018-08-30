<?php
function getfolders( $resourceType, $currentFolder )
{
    $sServerDir = servermapfolder( $resourceType, $currentFolder );
    $aFolders = array( );
    $oCurrentFolder = opendir( $sServerDir );
    do
    {
        if ( $sFile = readdir( $oCurrentFolder ) )
        {
            if ( !( $sFile != "." ) || !( $sFile != ".." ) )
            {
                    continue;
            }
            else
            {
                if ( is_dir( $sServerDir.$sFile ) )
                {
                        continue;
                }
                else
                {
                    $Var_0[$aFolders] = "<Folder name=\"".converttoxmlattribute( $sFile )."\" />";
                }
            }
        }
    } while( 1 );
    closedir( $oCurrentFolder );
    echo "<Folders>";
    natcasesort( $aFolders );
    foreach ( $aFolders as $sFolder )
    {
        echo $sFolder;
    }
    echo "</Folders>";
}

function getfoldersandfiles( $resourceType, $currentFolder )
{
    $sServerDir = servermapfolder( $resourceType, $currentFolder );
    $aFolders = array( );
    $aFiles = array( );
    $oCurrentFolder = opendir( $sServerDir );
    do
    {
        if ( $sFile = readdir( $oCurrentFolder ) )
        {
            if ( !( $sFile != "." ) || !( $sFile != ".." ) )
            {
                    continue;
            }
            else
            {
                if ( is_dir( $sServerDir.$sFile ) )
                {
                    $Var_0[$aFolders] = "<Folder name=\"".converttoxmlattribute( $sFile )."\" />";
                    continue;
                }
                $iFileSize = filesize( $sServerDir.$sFile );
                if ( 0 < $iFileSize )
                {
                    $iFileSize = round( $iFileSize / 1024 );
                    if ( $iFileSize < 1 )
                    {
                        $iFileSize = 1;
                    }
                }
                $Var_0[$aFiles] = "<File name=\"".converttoxmlattribute( $sFile )."\" size=\"".$iFileSize."\" />";
            }
        }
    } while( 1 );
    natcasesort( $aFolders );
    echo "<Folders>";
    foreach ( $aFolders as $sFolder )
    {
        echo $sFolder;
    }
    echo "</Folders>";
    natcasesort( $aFiles );
    echo "<Files>";
    foreach ( $aFiles as $sFiles )
    {
        echo $sFiles;
    }
    echo "</Files>";
}

function createfolder( $resourceType, $currentFolder )
{
    $sErrorNumber = "0";
    $sErrorMsg = "";
    if ( isset( $_GET['NewFolderName'] ) )
    {
        $sNewFolderName = $_GET['NewFolderName'];
        if ( strpos( $sNewFolderName, ".." ) !== FALSE )
        {
            $sErrorNumber = "102";
        }
        $sServerDir = servermapfolder( $resourceType, $currentFolder );
        if ( is_writable( $sServerDir ) )
        {
            $sServerDir .= $sNewFolderName;
            $sErrorMsg = createserverfolder( $sServerDir );
        case "" :
            $sErrorNumber = "0";
        case "Invalid argument" :
        case "No such file or directory" :
        default :
            $sErrorNumber = "102";
        default :
            $sErrorNumber = "110";
        }
        else
        {
            $sErrorNumber = "103";
        }
        else
        {
            $sErrorNumber = "102";
        }
    }
    echo "<Error number=\"".$sErrorNumber."\" originalDescription=\"".converttoxmlattribute( $sErrorMsg )."\" />";
}

function fileupload( $resourceType, $currentFolder )
{
    $sErrorNumber = "0";
    $sFileName = "";
    if ( isset( $_FILES['NewFile'] ) )
    {
        do
        {
            if ( is_null( $_FILES['NewFile']['tmp_name'] ) )
                break;
            global $Config;
            $oFile = $_FILES['NewFile'];
            $sServerDir = servermapfolder( $resourceType, $currentFolder );
            $sFileName = $oFile['name'];
            if ( $Config['ForceSingleExtension'] )
            {
                $sFileName = preg_replace( "/\\.(?![^.]*\$)/", "_", $sFileName );
            }
            $sOriginalFileName = $sFileName;
            $sExtension = substr( $sFileName, strrpos( $sFileName, "." ) + 1 );
            $sExtension = strtolower( $sExtension );
            $arAllowed = $Config['AllowedExtensions'][$resourceType];
            $arDenied = $Config['DeniedExtensions'][$resourceType];
            do
            {
                if ( count( $arAllowed ) == 0 )
                    break;
            } while( 0 );
            do
            {
                do
                {
                    if ( count( $arDenied ) == 0 )
                        break;
                    if ( in_array( $sExtension, $arDenied ) )
                        break;
                } while( 0 );
                $iCounter = 0;
                do
                {
                    if ( true )
                    {
                        $sFilePath = $sServerDir.$sFileName;
                        if ( is_file( $sFilePath ) )
                        {
                            ++$iCounter;
                            $sFileName = removeextension( $sOriginalFileName )."(".$iCounter.").".$sExtension;
                        }
                        $sErrorNumber = "201";
                    }
                } while( 1 );
                is_uploaded_file( $oFile['tmp_name'], $sFilePath );
                if ( is_file( $sFilePath ) )
                {
                    $oldumask = umask( 0 );
                    chmod( $sFilePath, 511 );
                    umask( $oldumask );
                }
            } while( 0 );
            $sErrorNumber = "202";
        }
        else
        {
        } while( 0 );
        $sErrorNumber = "202";
    }
    echo "<script type=\"text/javascript\">";
    echo "window.parent.frames[\"frmUpload\"].OnUploadCompleted(".$sErrorNumber.",\"".str_replace( "\"", "\\\"", $sFileName )."\") ;";
    echo "</script>";
    exit( );
}

?>
