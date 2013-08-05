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

								$query_search = 'SELECT *
												  FROM '.$t_repo_table.' 
												  WHERE id_actor=' . db_param();

$result_search = db_query_bound( $query_search, array($id_actor) );

$count_cus = db_num_rows( $result_search );

if($count_cus > 0){
?>
<div align="center">
<table class="width90">
<tr class="row-category">
<td>The actor can not be deleted because the rule <?php echo "<a  href=\"$t_page_return\">".str_pad($id_actor, 7, "0", STR_PAD_LEFT)."</a>";?> is being referenced  by the following use cases:</td>
</tr>
<?php
while($row_search = db_fetch_array( $result_search )){
     $usecase = $row_search['id_usecase'];
	 $t_page_go =  plugin_page( 'usecase_page' );
	 $t_page_go=$t_page_go."&id_usecase=".$usecase;
	 ?>
	<tr class="row-category" <?php echo helper_alternate_class() ?> >
	<td class="form-title" colspan="2"> 
						<?php echo "<a  href=\"$t_page_go\">".str_pad($usecase, 7, "0", STR_PAD_LEFT)."</a>";?>
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


<table class="width90">

<tr class="row-category" <?php echo helper_alternate_class() ?>>
		<td colspan="2">Are you sure you wish to delete these actor</td>
</tr>
<tr class="row-category">
		<td class="form-title" colspan="2">
		<input type="button" value="Delete Actor" onClick="javascript:go_page(null, null ,'<?php echo $t_page?>')"/></td>
</tr>
</table>
</form>

<?php
}


?>