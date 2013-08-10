<?php

require_once('functions.php');
require_once( 'file_api.php' );


html_page_top( plugin_lang_get( 'title' ) );
auth_ensure_user_authenticated();

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

//actor a borrar
$id_actor = gpc_get_int( 'id_actor' );

//borramos logicamente el actor
$t_repo_table = plugin_table( 'actor', 'honey' );

$t_query_rule = 'UPDATE '.$t_repo_table.' set active=1
					where id= ' . db_param() . '';

$g_result_delet =db_query_bound( $t_query_rule, array( $id_actor) );


echo "<p>Deleted data</p>";

$t_page=plugin_page('view_actors_page');
echo '<br><br>';
echo "<a href=\"$t_page\">Back</a>";

echo "<br>";

$t_url= plugin_page( 'view_actors_page' );

html_page_bottom( );

html_meta_redirect_honey( $t_url, $p_time = null);

?>