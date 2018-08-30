<?php
function unonline( $n )
{
    db_query( );
    $db = new db_query;
    $db->connect( );
    $db->query( "delete from `onlinelist` where `username` = '".$n."'" );
    $db->close( );
    session_unregister( "userrank" );
    session_unregister( "username" );
    session_unregister( "roomid" );
}

function getconfig( $configName )
{
    db_query( );
    $db = new db_query;
    $db->connect( );
    $res = $db->query( "select `value` from `config` where `name` = '".$configName."'" );
    if ( $db->numrows( $res ) )
    {
        return $db->result( $res, 0, "value" );
    }
    else
    {
        return "not found: ".$configName;
    }
}

function timer( )
{
    global $_timer;
    if ( isset( $_timer['start'] ) )
    {
        $mtime = explode( " ", microtime( ) );
        $_timer['start'] = $mtime[1] + $mtime[0];
    }
    else
    {
        $mtime = explode( " ", microtime( ) );
        return number_format( $mtime[1] + $mtime[0] - $_timer['start'], 4 );
    }
}

session_start( );
session_register( $USER_ID );
?>
