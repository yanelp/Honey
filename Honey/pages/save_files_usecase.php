<?php

require_once('functions.php');
require_once( 'file_api.php' );

$f_files=gpc_get_file( 'ufile', null );
$cant_files=$_REQUEST['cant_files'];
$id_usecase=$_REQUEST['id_usecase'];
$t_page_update='usecase_page'
?>

<form id="form1" action="<?php echo plugin_page( $t_page_update ); ?>" method="POST" enctype="multipart/form-data">

<?php
//echo "count( $f_files ): ".count( $f_files );
for( $i = 0; $i < $cant_files; $i++ ) {
	
		if( !empty( $f_files['name'][$i] ) ) {
			$t_file['name']     = $f_files['name'][$i];
			$t_file['tmp_name'] = $f_files['tmp_name'][$i];
			$t_file['type']     = $f_files['type'][$i];
			$t_file['error']    = $f_files['error'][$i];
			$t_file['size']     = $f_files['size'][$i];

			if($t_file['size']<=1012790){
				attach_add( $id_usecase, $t_file );
			}
			//else{ echo plugin_lang_get('the_file').$t_file['name'].plugin_lang_get('file_error');}
		}
}//FOR


print_successful_redirect_honey(  $id_usecase  , 'usecase_page', 'attachment' );
?>
</form>