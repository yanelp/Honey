<?php

require_once('functions.php');
require_once( 'file_api.php' );


html_page_top( plugin_lang_get( 'title' ) );
auth_ensure_user_authenticated();

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

//simbolo a borrar
$id_symbol = gpc_get_int( 'id_symbol' );

//borramos logicamente el simbolo
$t_repo_table = plugin_table( 'symbol', 'honey' );
$t_query_usecase = 'UPDATE '.$t_repo_table.' set active=1
					where id= ' . db_param() . '';

$g_result_delete_usecase=db_query_bound( $t_query_usecase, array( $id_symbol) );


echo "<p>".plugin_lang_get('deleted_data')."</p>";

$t_page=plugin_page('view_symbols_page');
echo '<br><br>';
echo "<a href=\"$t_page\">".plugin_lang_get('back')."</a>";

echo "<br>";

$t_url= plugin_page( 'view_symbols_page' );

html_page_bottom( );

html_meta_redirect_honey( $t_url, $p_time = null);


?>