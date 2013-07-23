<?php

require_once('functions.php');
$id_usecase = gpc_get_int( 'id_usecase' );
$t_comment=$_REQUEST['new_note'];
add_uc_note( $id_usecase,$t_comment);
$page='usecase_page';
print_successful_redirect_honey(  $id_usecase  , $page );

?>