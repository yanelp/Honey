<?php

require_once('functions.php');
require_once( 'core.php' );


html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();


$project_id =  helper_get_current_project();


if ( ( ALL_PROJECTS == helper_get_current_project() ) && ( 0 == $f_master_bug_id ) ) {
		print_header_redirect( 'login_select_proj_page.php?ref=plugin.php?page=Honey/view_actors_page.php' );
	}


EVENT_LAYOUT_RESOURCES;


$t_repo_table = plugin_table( 'actor', 'honey' );

$query_actor = 'SELECT * 
				   FROM '.$t_repo_table.'
				   where id_project=' . db_param().'
				   AND active = 0
				   ORDER BY id';

$result = db_query_bound( $query_actor, array($project_id) );
$count = db_num_rows( $result );
?>

<?php 

if ($count != 0) {
	$t_page = plugin_page( 'update_actor_page' );
	
	echo '<div align="center">';
	echo '<table class="width90" cellspacing="0">';
	echo '<tr align="right">';
	echo '<td class="menu right nowrap">';
	$t_page=$t_page."&actor_id=-1";
	echo '<form method="post" action="' .$t_page.'">';
	$t_bug_label = plugin_lang_get( 'actor_id' );
	echo "<input type=\"text\" name=\"actor_id\" size=\"10\" class=\"small\" value=\"$t_bug_label\" onfocus=\"if (this.value == '$t_bug_label') this.value = ''\" onblur=\"if (this.value == '') this.value = '$t_bug_label'\" />&#160;";
	echo '<input type="submit" class="button-small" value="' . lang_get( 'jump' ) . '" />&#160;';
	echo '</form>';
	echo '</td>';
	echo '</tr>';
	echo '</table>';
	echo '</br>';
?>

<div align="center">
<table class="width90" summary="<?php echo plugin_lang_get( 'summary_actors' ) ?>">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo plugin_lang_get( 'actors' ) ?>
		</td>
	</tr>

<tr class="row-category"> 
	<td class="category"  width="7%"><?php echo plugin_lang_get( 'ID' ) ?></td>
	<td class="category"  width="30%"><?php echo plugin_lang_get( 'name' ) ?></td>
	<td class="category"><?php echo plugin_lang_get( 'col_description' ) ?></td>
</tr>

<?php 	while( $row = db_fetch_array( $result ) ){
	$t_page=$t_page."&actor_id=".$row['id'];
	$id= str_pad($row['id'], 7, "0", STR_PAD_LEFT);
	?>
		<tr <?php echo helper_alternate_class() ?>>

			<td >
				<?php echo "<a  href=\"$t_page\">".$id."</a>";?>
			</td>
			<td>
				<?php echo $row['name'];?>
			</td>
			<td>
				<?php echo $row['description'];?>
			</td>
		</tr>
	 <?php 
	$t_page = plugin_page( 'update_actor_page' );				
			} 
 } ?>

 </table>

</div>
<?php
html_page_bottom1( );

?>
