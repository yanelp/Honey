<?php

require_once('functions.php');
require_once( 'file_api.php' );


html_page_top( plugin_lang_get( 'title' ) );
auth_ensure_user_authenticated();

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

//cu a borrar
$id_rule = gpc_get_int( 'id_rule' );

//borramos logicamente la reglas
$t_repo_table = plugin_table( 'rule', 'honey' );

$t_query_rule = 'UPDATE '.$t_repo_table.' set active=1
					where id= ' . db_param() . '';

$g_result_delet =db_query_bound( $t_query_rule, array( $id_rule) );


echo "<p>Deleted data</p>";

$t_page=plugin_page('view_rules_page');
echo '<br><br>';
echo "<a href=\"$t_page\">Back</a>";

echo "<br>";

$t_url= plugin_page( 'view_rules_page' );

html_page_bottom( );

html_meta_redirect_honey( $t_url, $p_time = null);

?>