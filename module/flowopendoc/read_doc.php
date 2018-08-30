<?php
header( "Content-type: application/octet-stream" );
header( "Content-Transfer-Encoding: binary" );
header( "Content-Disposition: attachment; filename=".$filename );
readfile( $uploaddir );
exit( );
?>
