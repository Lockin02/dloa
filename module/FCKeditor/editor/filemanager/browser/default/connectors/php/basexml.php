<?php
function setxmlheaders( )
{
    ob_end_clean( );
    header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
    header( "Last-Modified: ".gmdate( "D, d M Y H:i:s" )." GMT" );
    header( "Cache-Control: no-store, no-cache, must-revalidate" );
    header( "Cache-Control: post-check=0, pre-check=0", false );
    header( "Pragma: no-cache" );
    header( "Content-Type:text/xml; charset=utf-8" );
}

function createxmlheader( $command, $resourceType, $currentFolder )
{
    setxmlheaders( );
    echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
    echo "<Connector command=\"".$command."\" resourceType=\"".$resourceType."\">";
    echo "<CurrentFolder path=\"".converttoxmlattribute( $currentFolder )."\" url=\"".converttoxmlattribute( geturlfrompath( $resourceType, $currentFolder ) )."\" />";
}

function createxmlfooter( )
{
    echo "</Connector>";
}

function senderror( $number, $text )
{
    setxmlheaders( );
    echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
    echo "<Connector><Error number=\"".$number."\" text=\"".htmlspecialchars( $text )."\" /></Connector>";
    exit( );
}

?>
