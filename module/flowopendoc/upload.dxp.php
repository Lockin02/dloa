<?php
$uploaddir = "../../attachment/".$ATTACHMENT_ID."/";
$uploadfile = $uploaddir.$_FILES['ATTACHMENT']['name'];
if ( is_uploaded_file( $_FILES['ATTACHMENT']['tmp_name'] ) )
{
    if ( copy( $_FILES['ATTACHMENT']['tmp_name'], $uploadfile ) )
    {
    }
}
?>
