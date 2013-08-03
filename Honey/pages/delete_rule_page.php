<?php

require_once('functions.php');
require_once( 'file_api.php' );


html_page_top( plugin_lang_get( 'title' ) );
auth_ensure_user_authenticated();

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

$t_page_return= plugin_page( 'update_rule_page' );
$t_page_return=$t_page_return."&id_rule=".$id_rule;

//regla a borrar
$id_rule = gpc_get_int( 'id_rule' );

$t_page_return= plugin_page( 'update_rule_page' );
$t_page_return=$t_page_return."&id_rule=".$id_rule;

$t_repo_table= plugin_table( 'rule_usecase', 'honey' );

								$query_search = 'SELECT *
												  FROM '.$t_repo_table.' 
												  WHERE id_rule=' . db_param();

$result_search = db_query_bound( $query_search, array($id_rule) );

$count_cus = db_num_rows( $result_search );

if($count_cus > 0){
?>
<div align="center">
<table class="width90">
<tr class="row-category">
<td>The rule can not be deleted because the rule <?php echo "<a  href=\"$t_page_return\">".str_pad($id_rule, 7, "0", STR_PAD_LEFT)."</a>";?> is being referenced  by the following use cases:</td>
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
	$t_page=plugin_page( 'delete_rule' );
	$t_page=$t_page."&id_rule=".$id_rule;
?>

<div align="center">

<form id="form1" action="<?php echo plugin_page( $t_page ); ?>" method="POST" enctype="multipart/form-data">


<table class="width90">

<tr class="row-category" <?php echo helper_alternate_class() ?>>
		<td colspan="2">Are you sure you wish to delete these use rule</td>
</tr>
<tr class="row-category">
		<td class="form-title" colspan="2">
		<input type="button" value="Delete Rule" onClick="javascript:go_page(null, null ,'<?php echo $t_page?>')"/></td>
</tr>
</table>
</form>

<?php
}


?>