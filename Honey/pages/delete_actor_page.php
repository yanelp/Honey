<?php

require_once('functions.php');
require_once( 'file_api.php' );


html_page_top( plugin_lang_get( 'title' ) );
auth_ensure_user_authenticated();

print_cu_menu();

EVENT_LAYOUT_RESOURCES;



//regla a borrar
$id_actor =$_REQUEST['id_actor_hidden'];


$t_page_return= plugin_page( 'update_actor_page' );
$t_page_return=$t_page_return."&id_actor=".$id_actor;

$t_repo_table= plugin_table( 'usecase_actor', 'honey' );
$t_repo_table_usecase= plugin_table( 'usecase', 'honey' );

								$query_search = 'SELECT *
												  FROM '.$t_repo_table.' r, '.$t_repo_table_usecase.' c 
												  WHERE r.id_usecase = c.id and c.active=0 and id_actor=' . db_param();


$result_search = db_query_bound( $query_search, array($id_actor) );

$count_cus = db_num_rows( $result_search );

if($count_cus > 0){
?>
<div align="center">
<table class="width90">
<tr class="row-category">
<td><?php echo plugin_lang_get('not_delete_actor')?></td>
</tr>
<?php
while($row_search = db_fetch_array( $result_search )){
     $usecase_id = $row_search['id_usecase'];
	 $usecase_name = $row_search['name'];
	 $t_page_go =  plugin_page( 'usecase_page' );
	 $t_page_go=$t_page_go."&id_usecase=".$usecase_id;
	 ?>
	<tr  <?php echo helper_alternate_class() ?> >
	<td class="center" colspan="2"> 
		<?php echo "<a  href=\"$t_page_go\">".str_pad($usecase_id, 7, "0", STR_PAD_LEFT)."</a> - ".$usecase_name;?>
	</td>

	</tr>
<?php

}
?>
</table>
</div>
<?php

}
else 
{
	$t_page=plugin_page( 'delete_actor' );
	$t_page=$t_page."&id_actor=".$id_actor;
?>

<div align="center">

<form id="form1" action="<?php echo plugin_page( $t_page ); ?>" method="POST" enctype="multipart/form-data">

<?php showMessage(plugin_lang_get('sure_delete_actor'), 'warning')?>
		<tr class="row-category">
		<td class="form-title" colspan="2">
		<input type="button" value="<?php echo plugin_lang_get('delete')?>" onClick="javascript:go_page(null, null ,'<?php echo $t_page;?>')"/></td>
		</tr>
		</table>
</form>

<?php
}


?>