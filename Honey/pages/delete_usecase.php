<?php

require_once('functions.php');
require_once( 'file_api.php' );


html_page_top( plugin_lang_get( 'title' ) );
auth_ensure_user_authenticated();

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

//cu a borrar
$id_usecase = gpc_get_int( 'id_usecase' );

//borramos la relacion extend-caso de uso
$t_repo_table = plugin_table( 'usecase_extend', 'honey' );
$t_query_usecase = 'delete from '.$t_repo_table.' 
					where id_usecase_parent= ' . db_param() . '';

$g_result_delete_usecase=db_query_bound( $t_query_usecase, array( $id_usecase)  );

//borramos la relacion include-caso de uso
$t_repo_table = plugin_table( 'usecase_include', 'honey' );
$t_query_usecase = 'delete from '.$t_repo_table.' 
					where id_usecase_include= ' . db_param() . '';

$g_result_delete_usecase=db_query_bound( $t_query_usecase, array( $id_usecase)  );

//borramos logicamente el CU
$t_repo_table = plugin_table( 'usecase', 'honey' );
$t_query_usecase = 'UPDATE '.$t_repo_table.' set active=1
					where id= ' . db_param() . '';

$g_result_delete_usecase=db_query_bound( $t_query_usecase, array( $id_usecase) );


//echo "<p>".plugin_lang_get('deleted_data')."</p>";
?>
<div align='center'>
<?php showMessage(plugin_lang_get('deleted_data'), 'congratulations')?>
</table>
</div>
<?php

$t_page=plugin_page('view_cu_page');
echo '<br><br>';
//echo "<a href=\"$t_page\">".plugin_lang_get('back')."</a>";
?>
<table align='center'>
<tr>
<td class='center'>
<?php echo "<a href=\"$t_page\">". plugin_lang_get('back')."</a>";?>
</td>
</tr>
</table>

<?php echo "<br>";

$t_url= plugin_page( 'view_cu_page' );

html_page_bottom( );

html_meta_redirect_honey( $t_url, $p_time = null);


?>