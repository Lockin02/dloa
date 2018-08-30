<?php
$online_log = "../module/user_select/count.dat";
$timeout = 300;
$entries = file( $online_log );
$temp = array( );
$i = 0;
for ( ; $i < count( $entries ); ++$i )
{
    $entry = explode( ",", trim( $entries[$i] ) );
    if ( !( $entry[0] != $USER_ID ) || !( time( ) < $entry[1] ) )
    {
            continue;
    }
    else
    {
        array_push( $temp, $entry[0].",".$entry[1]."\n" );
    }
}
array_push( $temp, $USER_ID.",".( time( ) + $timeout )."\n" );
$users_online = count( $temp );
$entries = implode( "", $temp );
$fp = fopen( $online_log, "w" );
flock( $fp, LOCK_EX );
fputs( $fp, $entries );
flock( $fp, LOCK_UN );
fclose( $fp );
?>
