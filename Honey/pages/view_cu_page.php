<?php

require_once('functions.php');
require_once( 'core.php' );
require_once('manage_sequences.php');


html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu('view');

$order	= gpc_get_int( 'order', -1 );
if($order==-1){$order=2;}

$p_search=gpc_get_int( 'p_search', -1 );

$project_id =  helper_get_current_project();


if ( ( ALL_PROJECTS == helper_get_current_project() ) && ( 0 == $f_master_bug_id ) ) {
		print_header_redirect( 'login_select_proj_page.php?ref=plugin.php?page=Honey/view_cu_page.php' );
	}


EVENT_LAYOUT_RESOURCES;


$t_repo_table = plugin_table( 'usecase', 'honey' );

if($p_search==-1){//sin busqueda
	$query_symbol = 'SELECT * 
				   FROM '.$t_repo_table.'
				   where id_project=' . db_param().'
				   AND active = 0
				   ORDER BY ' . $order;
	$result = db_query_bound( $query_symbol, array($project_id) );
	$count = db_num_rows( $result );
}
else{//con busqueda
	if($_REQUEST['option_search']==2){//busca por nombre
			$nombre='%'.$_REQUEST['usecase_id'].'%';
			$query_symbol = 'SELECT * 
				   FROM '.$t_repo_table.'
				   where id_project=' . db_param().'
				   AND active = 0 and name like '.db_param().'
				   ORDER BY ' . $order;
			$result = db_query_bound( $query_symbol, array($project_id,$nombre) );
			$count2 = db_num_rows( $result );
	}
	else{//busca por id
		$query_symbol = 'SELECT * 
				   FROM '.$t_repo_table.'
				   where id_project=' . db_param().'
				   AND active = 0 and id=' . db_param().'
				   ORDER BY ' . $order;
		$result = db_query_bound( $query_symbol, array($project_id,$_REQUEST['usecase_id'] ) );
		$count2 = db_num_rows( $result );
	}
}

?>

<?php 

if (($count > 0)||($count2>0)) {

	$t_page = plugin_page( 'view_cu_page' );
	echo '<div align="center">';
	echo '<table class="width90" cellspacing="0">';
	echo '<tr align="right">';
	echo '<td class="menu right nowrap">';
	$t_page=$t_page."&id_usecase=-1&p_search=0";
	echo '<form method="post" action="' .$t_page.'">';
	$t_bug_label = plugin_lang_get( 'usecase_id' );
	echo "<select name='option_search' id='option_search'>
			<option value=1>".plugin_lang_get( 'ID' )."</option>
			<option value=2>".plugin_lang_get( 'name' )."</option>
		</select>&#160";
	echo "<input type=\"text\" name=\"usecase_id\" size=\"10\" class=\"small\" value=\"$t_bug_label\" onfocus=\"if (this.value == '$t_bug_label') this.value = ''\" onblur=\"if (this.value == '') this.value = '$t_bug_label'\" />&#160;";
	echo '<input type="submit" class="button-small" value="' . lang_get( 'search' ) . '" />&#160;';
	echo '</form>';
	echo '</td>';
	echo '</tr>';
	echo '</table>';
	echo '</br>';

?>

<div align="center">
<table class="width90" summary="<?php echo plugin_lang_get( 'summary_uc' ) ?>">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo plugin_lang_get( 'usecases' ) ?>
		</td>
	</tr>

<tr class="row-category"> 
	<td class="category"  width="7%">
		<?php
			$t_page = plugin_page( 'view_cu_page' );	
			$t_page=$t_page."&order=1";	
			echo "<a href=\"$t_page\">".plugin_lang_get( 'ID' )."</a>";
		?>
	</td>
	<td class="category"  width="30%">
		<?php
			$t_page = plugin_page( 'view_cu_page' );	
			$t_page=$t_page."&order=2";	
			echo "<a href=\"$t_page\">".plugin_lang_get( 'name' )."</a>";
			?>
	</td>
	<td class="category"><?php echo plugin_lang_get( 'goal' ) ?></td>
</tr>

<?php 	while( $row = db_fetch_array( $result ) ){
	$t_page=plugin_page( 'usecase_page' );	
	$t_page=$t_page."&id_usecase=".$row['id'];
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
				<?php echo $row['goal'];?>
			</td>
		</tr>
	 <?php 
	$t_page = plugin_page( 'usecase_page' );				
			} 
 } 
 else{
	if($p_search==-1){ 
		echo plugin_lang_get('ucs_not_exist');
		echo '<br><br>';
		$t_page=plugin_page("view_cu_page");
		echo "<a href=\"$t_page\">". plugin_lang_get('back')."</a>";
		echo "<br>";
	}
	else{
		echo plugin_lang_get('uc_do_not_exist');
		echo '<br><br>';
		$t_page=plugin_page("view_cu_page");
		echo "<a href=\"$t_page\">". plugin_lang_get('back')."</a>";
		echo "<br>";
	}
}
 ?>

 </table>

</div>
<?php
html_page_bottom1( );

?>