<?php

require_once('print_cu_menu.php');

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

$id_usecase = gpc_get_int( 'id_usecase' );

$t_page_update="modify_uc_page";
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
$id=$row['view_id'];
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

$t_uc_note_table = plugin_table( 'uc_note','honey' );
$t_user_table = db_get_table( 'mantis_user_table' );
$query_note = "SELECT a.id, a.id_uc, a.note, a.reporter_id, a.view_state, a.date_submitted,a.last_modified, b.username, b.access_level
					  FROM ".$t_uc_note_table." a inner join  ".$t_user_table." b on (a.reporter_id=b.id)
					  WHERE id_uc =" . db_param() . " ";
$result_note = db_query_bound( $query_note,  array($id_usecase) );

$count_notes = db_num_rows( $result_note );

?>

	<br>
	 <table class="width90">
		<tr>
			<td class="form-title" colspan="2">
			<?php echo lang_get( 'plugin_Honey_usecase_notes' )?>
			</td>
		</tr>
<?php 
if($count_notes>0){?>
		<tr>
			<td class="category">NOTE ID</td>
			<td class="category" >NOTE</td>
			<td class="category" >REPORTER</td>
			<td class="category" >STATE</td>
			<td class="category" >DATE SUMITTED</td>
			<td class="category" >LAST MODIFIED</td>
		</tr>

<?php

	while( $row_note = db_fetch_array( $result_note ) ){
		$id_note=$row_note['id'];
		$note=$row_note['note'];
		$reporter=$row_note['username'];
		$state=$row_note['view_state'];
		$submitted=$row_note['date_submitted'];
		$modified=$row_note['last_modified'];

		if ( VS_PRIVATE == $state) {$estado='[private]';}
		else {$estado='[public]';}


		$user_access_level =$row_note['access_level'];

	?>
		
		<tr <?php echo helper_alternate_class() ?>>
			<td><?php echo $id_note ?></td>
			<td><?php echo $note ?></td>
			<td><?php echo $reporter ?> <?php echo '(', get_enum_element( 'access_levels', $user_access_level ), ')';?></td>
			<td><?php echo $estado ?></td>
			<td><?php echo $submitted ?></td>
			<td><?php echo $modified ?></td>
		</tr>


	<?php }//while 

  }//if ?>

</TABLE>
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