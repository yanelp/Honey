<?php

require_once('functions.php');
require_once( 'core.php' );
require_once('manage_sequences.php');


html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();


$project_id =  helper_get_current_project();


if ( ( ALL_PROJECTS == helper_get_current_project() ) && ( 0 == $f_master_bug_id ) ) {
		print_header_redirect( 'login_select_proj_page.php?ref=plugin.php?page=Honey/new_usecase_page.php' );
	}


EVENT_LAYOUT_RESOURCES;


$t_repo_table = plugin_table( 'usecase', 'honey' );

$query_symbol = 'SELECT id, name, goal, view_id, postconditions, observations, preconditions 
				   FROM '.$t_repo_table.'
				   where id_project=' . db_param().'
				   ORDER BY name';

$result = db_query_bound( $query_symbol, array($project_id) );
$count = db_num_rows( $result );
?>

<?php 

if ($count != 0) {
	$t_page = plugin_page( 'usecase_page' );	
?>

<div align="center">
<table class="width90">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_Honey_usecases' ) ?>
		</td>
	</tr>

<tr class="row-category"> 
	<td>ID</td>
	<td>Name</td>
	<td>Observation</td>
</tr>

<?php 	while( $row = db_fetch_array( $result ) ){
	$t_page=$t_page."&id_usecase=".$row['id'];
	?>
		<tr <?php echo helper_alternate_class() ?>>

			<td>
				<?php echo "<a href=\"$t_page\">".$row['view_id']."</a>";?>
			</td>
			<td>
				<?php echo $row['name'];?>
			</td>
			<td>
				<?php echo $row['observations'];?>
			</td>
		</tr>
	 <?php 
	$t_page = plugin_page( 'usecase_page' );				
			} 
 } ?>

 </table>

</div>
<?php
html_page_bottom1( );

?>