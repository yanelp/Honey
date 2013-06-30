<?php

require_once('functions.php');
//require_once( 'current_user_api.php' );

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

$id_usecase = gpc_get_int( 'id_usecase' );

$t_page_update="modify_uc_page";
$t_page_update=$t_page_update."&id_usecase=".$id_usecase;

$t_page=plugin_page("view_cu_page");
?>

<form id="form1" action="<?php echo plugin_page( $t_page_update ); ?>" method="POST">
<?php

//busco el caso de uso seleccionado

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

//busco sus actores (si los tiene)

$t_repo_table = plugin_table( 'actor', 'honey' );
$t_repo_table2 = plugin_table( 'usecase_actor', 'honey' );

$query_actors = 'SELECT a.name 
				 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id=b.id_actor)
				 where b.id_usecase=' . db_param();

$result_actors = db_query_bound( $query_actors, array($id_usecase) );
$count_actors = db_num_rows( $result_actors );

$actors='';
while( $row_actors = db_fetch_array( $result_actors )){
	if($actors==''){
		$actors=$row_actors['name'];
	}
	else{
		$actors=$actors.', '.$row_actors['name'];
		}
}//while


//busco sus interfaces y sus reglas (si los tiene)

//busco sus escenarios secundarios y sus reglas (si los tiene)

//busco si extiende o incluye otro cu (si los tiene)



?>

<div align="center">
<table class="width90">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_Honey_usecase_information' );
		echo '&#160;<span class="small">';
	    print_bracket_link( "#uc_notes",'Jump to notes'  );?>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category" width="20%">ID</td><td><?php echo $id ?></td>
		
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Name</td><td><?php echo $name ?></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Actor/s</td><td><?php echo $actors ?></td>
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

<?php # UC notes BEGIN (permite el salto a #)?>
<a name="uc_notes" id="uc_notes" /><br />

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
			<?php echo lang_get( 'plugin_Honey_usecase_notes' ) ;	?>
			</td>
		</tr>

<?php

	while( $row_note = db_fetch_array( $result_note ) ){
		$id_note=$row_note['id'];
		$note=$row_note['note'];
		$reporter=$row_note['username'];
		$state=$row_note['view_state'];
		$submitted=$row_note['date_submitted'];
		$modified=$row_note['last_modified'];
		$id_uc=$row_note['id_uc'];

		if ( VS_PRIVATE == $state) {
			$estado='[private]';
			$t_bugnote_css		= 'bugnote-private';
			$t_bugnote_note_css	= 'bugnote-note-private';
		}
		else {$estado='[public]';
			  $t_bugnote_css		= 'bugnote-public';
			  $t_bugnote_note_css	= 'bugnote-note-public';
			  }

		$user_access_level =$row_note['access_level'];

	?>
	
	<tr>
	  <td  class="<?php echo $t_bugnote_css ?>">
		<?php echo $id_note;?>
		<span class="small">
		  <?php if ( user_exists( $reporter ) ) {
			  $t_access_level = $user_access_level;
			  }
			  // Only display access level when higher than 0 (ANYBODY)
			  if( $t_access_level > ANYBODY ) {
				echo '(', get_enum_element( 'access_levels', $user_access_level ), ')';
			  }?>
		</span>
			  <?php if ( VS_PRIVATE == $state) { ?>
				<span class="small">[ <?php echo lang_get( 'private' ) ?> ]</span>
			  <?php } ?>
			  <br />
			  <span class="small"><?php echo date( $submitted); ?></span><br />
			  <?php	if ( $modified ) {
				echo '<span class="small">' . lang_get( 'edited_on') . lang_get( 'word_separator' ) . date($modified ) . '</span><br />';
			   }?>
			  <br /><div class="small">

			  <input type="button" onClick="javascript:go_page(<?php echo $id_note?>,<?php  echo $id_usecase?>,'<?php  echo plugin_page("uc_note_edit_page");?>')" value="Editar"/>
			  <input type="button" onClick="javascript:go_page(<?php echo $id_note?>,<?php  echo $id_usecase?>,'<?php  echo plugin_page("delete_uc_note");?>')" value="Delete"/>
			  <?php
				if($state==VS_PRIVATE){ ?>
				 <input type="button" onClick="javascript:go_page(<?php echo $id_note?>,<?php  echo $id_usecase?>,'<?php  echo plugin_page("set_view_state_uc_note");?>')" value="Make Public"/>
			  <?php }
					else{?>
						<input type="button" onClick="javascript:go_page(<?php echo $id_note?>,<?php  echo $id_usecase?>,'<?php  echo plugin_page("set_view_state_uc_note");?>')" value="Make Private"/>
					
			  <?php } ?>	

			</div>
	  </td>
	  <td  class="<?php echo $t_bugnote_note_css ?>">
	  <?php echo string_convert_uc_link($note); ?>
	  </td>
	</tr>


	<?php }//while  ?>

</TABLE>


<br>
<table align="center">
	<tr>
		<td><input type="submit" value="Update"/></td>
		<td><input type="button" value="Cancel"  onClick="javascript:go_page(null,<?php echo $id_usecase?> ,'<?php echo $t_page?>')"/></td>
	</tr>
</table>
</div>
</form>

<?php

html_page_bottom1( );

?>