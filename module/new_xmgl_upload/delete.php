<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
$dirs = $FLOW_ID;
@deltree( "../../attachment/xmgl/".$dirs );
$msql->query( "delete from  xmgl_uploadlist where FLOW_ID='".$FLOW_ID."' and  PRCS_ID ='$id' " );
?>
