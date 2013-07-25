<?php

require_once('functions.php');
$id_usecase = gpc_get_int( 'id_usecase' );
$back=$_REQUEST['backPage'];
$t_comment=$_REQUEST['new_note'];
add_uc_note( $id_usecase,$t_comment);
print_successful_redirect_honey(  $id_usecase  , $back );

?>