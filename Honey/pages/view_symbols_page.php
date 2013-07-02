<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

$project_id =  helper_get_current_project();


if ( ( ALL_PROJECTS == helper_get_current_project() ) && ( 0 == $f_master_bug_id ) ) {
		print_header_redirect( 'login_select_proj_page.php?ref=plugin.php?page=Honey/view_symbols_page.php' );
}

EVENT_LAYOUT_RESOURCES;


$t_repo_table = plugin_table( 'symbol', 'honey' );

$query_symbol = 'SELECT id, name, type 
				   FROM '.$t_repo_table.'
				   where id_project=' . db_param().'
				   AND active = 0
				   ORDER BY name';

$result = db_query_bound( $query_symbol, array($project_id) );
$count = db_num_rows( $result );
?>

<?php 

if ($count != 0) {
	$t_page = plugin_page( 'symbol_page' );	
?>

<div align="center">
<table class="width90">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_Honey_symbols' ) ?>
		</td>
	</tr>
	<tr  class="row-category">
		<td>
		<?php echo lang_get( 'plugin_Honey_symbols_name_colum' ) ?>
		</td>

		<td>
		<?php echo lang_get( 'plugin_Honey_symbols_type_colum' ) ?>
		</td>

	</tr>

<?php 	while( $row = db_fetch_array( $result ) ){
	$t_page=$t_page."&id_symbol=".$row['id'];
	?>
		<tr <?php echo helper_alternate_class() ?>>
			<td width='50%'>
			<?php echo "<a href=\"$t_page\">".$row['name']."</a>";?>
			</td>
			<td width='50%'>
			<?php echo "<a href=\"$t_page\">".get_symbol_type($row['type'])."</a>";?>
			</td>
		</tr>
	 <?php 
	$t_page = plugin_page( 'symbol_page' );				
			} 
 } ?>

 </table>

<br>

<center><a href="<?php echo plugin_page( "core" ); ?>">Derivar a Casos de Uso</a></center>

</div>
<?php
html_page_bottom1( );

?>