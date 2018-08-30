<?php
header( "Content-type: application/octet-stream" );
header( "Expires: ".gmdate( "D, d M Y H:i:s" )." GMT" );
header( "Content-Transfer-Encoding: binary" );
header( "Content-Length: ".filesize( $uploaddir ) );
header( "Content-Disposition: attachment; filename=\"".$filename."\"" );
header( "Cache-Control: cache, must-revalidate" );
header( "Pragma: public" );
readfile( $uploaddir );
exit( );
?>
