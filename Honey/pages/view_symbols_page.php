<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

$order	= gpc_get_int( 'order', -1 );
if($order==-1){$order=2;}

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
				   ORDER BY ' . $order;

$result = db_query_bound( $query_symbol, array($project_id) );
$count = db_num_rows( $result );
?>

<?php 

if ($count != 0) {
	$t_page = plugin_page( 'symbol_page' );	

echo '<div align="center">';
echo '<table class="width90" cellspacing="0">';
echo '<tr align="right">';
echo '<td class="menu right nowrap">';
$t_page=$t_page."&id_symbol=-1";
echo '<form method="post" action="' .$t_page.'">';
$t_bug_label = plugin_lang_get( 'symbol_id' );
echo "<input type=\"text\" name=\"symbol_id\" size=\"10\" class=\"small\" value=\"$t_bug_label\" onfocus=\"if (this.value == '$t_bug_label') this.value = ''\" onblur=\"if (this.value == '') this.value = '$t_bug_label'\" />&#160;";
echo '<input type="submit" class="button-small" value="' . lang_get( 'jump' ) . '" />&#160;';
echo '</form>';
echo '</td>';
echo '</tr>';
echo '</table>';
echo '</br>';
?>
<table class="width90" summary="<?php echo plugin_lang_get( 'summary_symbols' ) ?>">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo plugin_lang_get( 'symbols' ) ?>
		</td>
	</tr>
	<tr  class="row-category">

		<td>
			<?php
				$t_page = plugin_page( 'view_symbols_page' );	
				$t_page=$t_page."&order=1";	
				echo "<a href=\"$t_page\">".plugin_lang_get( 'ID' )."</a>";
			?>
		</td>

		<td>
			<?php 
				$t_page = plugin_page( 'view_symbols_page' );	
				$t_page=$t_page."&order=2";	
				echo "<a href=\"$t_page\">".plugin_lang_get( 'symbols_name_colum' )."</a>";
			?>
		</td>

		<td>
			<?php
				$t_page = plugin_page( 'view_symbols_page' );	
				$t_page=$t_page."&order=3";
				echo "<a href=\"$t_page\">".plugin_lang_get( 'symbols_type_colum' )."</a>";
			?>
		</td>

	</tr>

<?php 	while( $row = db_fetch_array( $result ) ){
	$t_page=$t_page."&id_symbol=".$row['id'];
	$id= str_pad($row['id'], 7, "0", STR_PAD_LEFT);
	?>
		<tr <?php echo helper_alternate_class() ?>>
			<td width='20%'>
			<?php echo "<a href=\"$t_page\">".$id."</a>";?>
			</td>
			<td width='60%'>
			<?php echo "<a href=\"$t_page\">".$row['name']."</a>";?>
			</td>
			<td width='20%'>
			<?php echo "<a href=\"$t_page\">".get_symbol_type($row['type'])."</a>";?>
			</td>
		</tr>
	 <?php 
	$t_page = plugin_page( 'symbol_page' );				
			} 
 } ?>

 </table>

<br>

<!--<center><a href="<?php echo plugin_page( 'derivation_page' ); ?>">Derive to Use Cases</a></center>-->

</div>
<?php
html_page_bottom1( );

?>