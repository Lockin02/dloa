<?php
function removefromstart( $sourceString, $charToRemove )
{
    $sPattern = "|^".$charToRemove."+|";
    return preg_replace( $sPattern, "", $sourceString );
}

function removefromend( $sourceString, $charToRemove )
{
    $sPattern = "|".$charToRemove."+\$|";
    return preg_replace( $sPattern, "", $sourceString );
}

function converttoxmlattribute( $value )
{
    return utf8_encode( htmlspecialchars( $value ) );
}

?>
