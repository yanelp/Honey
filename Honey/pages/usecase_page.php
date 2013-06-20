<?php

require_once('print_cu_menu.php');

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

$id_usecase = gpc_get_int( 'id_usecase' );

$t_page_update="modify_use_page";
$t_page_update=$t_page_update."&id_usecase=".$id_usecase;
?>

<form id="form1" action="<?php echo plugin_page( $t_page_update ); ?>" method="POST">
<?php


$t_repo_table = plugin_table( 'usecase', 'honey' );

$query_symbol = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id=' . db_param();

$result = db_query_bound( $query_symbol, array($id_usecase) );
$count = db_num_rows( $result );
$row = db_fetch_array( $result );

$name=$row['name'];
$observation=$row['observations'];
$id=$row['id'];
$precond=$row['preconditions'];
$postcond=$row['postconditions'];
$goal=$row['goal'];

?>

<div align="center">
<table class="width90">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_Honey_usecase_information' )?>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">ID</td><td><?php echo $id ?></td>
		
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Name</td><td><?php echo $name ?></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Observation</td><td><?php echo $observation ?></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Preconditions</td><td><?php echo $precond ?></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Postconditions</td><td><?php echo $postcond ?></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Goal</td><td><?php echo $goal ?></td>
	</tr>

</table>


 <!--aca muestro las notas-->

 <?php

$t_bugnote_table = db_get_table( 'mantis_bugnote_table' );
$t_bugnote_table_text = db_get_table( 'mantis_bugnote_text_table' );
$query_note = "SELECT bug_id, bugnote_text_id, note
					  FROM ".$t_bugnote_table." a inner join ".$t_bugnote_table_text." b on (a.bugnote_text_id=b.id)
					  WHERE bug_id =" . db_param() . " ";
$result_note = db_query_bound( $query_note,  array($id_usecase) );

$count_notes = db_num_rows( $result_note );

if($count_notes>0){?>

	<br>
	 <table class="width90">
		<tr>
			<td class="form-title" colspan="2">
			<?php echo lang_get( 'plugin_Honey_usecase_notes' )?>
			</td>
		</tr>
		<tr>
			<td class="category" >UC id</td>
			<td class="category">BUG NOTE ID</td>
			<td class="category" >NOTE</td>
		</tr>

<?php

	while( $row_note = db_fetch_array( $result_note ) ){

	$bug_id=$row_note['bug_id'];
	$note_id=$row_note['bugnote_text_id'];
	$note=$row_note['note'];
	?>
		
		<tr <?php echo helper_alternate_class() ?>>
			<td><?php echo $bug_id ?></td>
			<td><?php echo $note_id ?></td>
			<td><?php echo $note ?></td>
		</tr>


	<?php }//while ?>

</TABLE>

<?php }//if ?>

<br>
<table align="center">
	<tr>
		<td><input type="submit" value="Update"/></td><td><input type="button" value="Cancel"/></td>
	</tr>
</table>
</div>

</form>
<?php

html_page_bottom1( );

?>