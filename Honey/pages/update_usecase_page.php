<?php

require_once('functions.php');
//require_once( 'current_user_api.php' );

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

$id_usecase = gpc_get_int( 'id_usecase' );
$id_project=helper_get_current_project();

$t_page_update="usecase_update_page";
$t_page_update=$t_page_update."&id_usecase=".$id_usecase;

$t_page=plugin_page("view_cu_page");
?>

<form id="form1" action="<?php echo plugin_page( $t_page_update ); ?>" method="POST">
<?php

//busco el caso de uso seleccionado

$t_repo_table = plugin_table( 'usecase', 'honey' );

$query_symbol = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id=' . db_param().'
				 AND active = 0';

$result = db_query_bound( $query_symbol, array($id_usecase) );
$count = db_num_rows( $result );
$row = db_fetch_array( $result );

$name=$row['name'];
$observation=$row['observations'];
$id=$row['id'];
$id= str_pad($row['id'], 7, "0", STR_PAD_LEFT);
$precond=$row['preconditions'];
$postcond=$row['postconditions'];
$goal=$row['goal'];

//busco todos los actores

$t_repo_table = plugin_table( 'actor', 'honey' );

$query_all_actors = 'SELECT a.name , a.id
				 FROM '.$t_repo_table.' a 
				 where a.id_project=' . db_param().'
				 AND a.active = 0 ';

$result_all_actors = db_query_bound( $query_all_actors, array($id_project) );
$count_all_actors = db_num_rows( $result_all_actors );


//busco sus escenarios, sus reglas y sus interfaces (si los tiene)

	//main scenario

	$t_repo_table = plugin_table( 'scenario', 'honey' );

	$query_main_scenario = 'SELECT a.steps, a.id 
					 FROM '.$t_repo_table.' a
					 where id_usecase=' . db_param().' and type=1 and a.active = 0';

	$result_main_scenario = db_query_bound( $query_main_scenario, array($id_usecase) );
	$count_main_scenario = db_num_rows( $result_main_scenario );
	$row_main_scenario = db_fetch_array( $result_main_scenario);
	$main_scenario=$row_main_scenario['steps'];
	$main_scenario=str_replace("<br>", "\n",$main_scenario);
	$id_scenario=$row_main_scenario['id'];

	//rules 

	$t_repo_table2 = plugin_table( 'rule', 'honey' );

	$query_all_rules = 'SELECT b.name , b.id
					 FROM '.$t_repo_table2.' b
					 where id_project=' . db_param().'
					AND b.active = 0';

	$result_all_rules = db_query_bound( $query_all_rules, array($id_project) );
	$count_all_rules = db_num_rows( $result_all_rules);
	
	
	//interfaces main

	$t_repo_table2 = plugin_table( 'file_usecase', 'honey' );

	$query_interface_scenario = 'SELECT b.content 
						FROM  '.$t_repo_table2.' b 
						where id_usecase=' . db_param().'
						';

	$result_interface_scenario = db_query_bound( $query_interface_scenario, array($id_usecase) );
			
	$interface_main='';
				
	while( $row_interface_scenario = db_fetch_array( $result_interface_scenario )){
				if($interface_main==''){
					$interface_main=$row_interface_scenario['description'];
				}
				else{
					$interface_main=$interface_main.', '.$row_interface_scenario['description'];
					}
			}//while


//alternative scenarios


	$t_repo_table = plugin_table( 'scenario', 'honey' );

	$query_alternative_scenario = 'SELECT a.steps, a.id 
					 FROM '.$t_repo_table.' a
					 where id_usecase=' . db_param().' and active = 0 and type<>1';

	$result_alternative_scenario = db_query_bound( $query_alternative_scenario, array($id_usecase) );


//busco todos los cus menos el seleccionado

	$t_repo_table2 = plugin_table( 'usecase', 'honey' );

	$query_all_ucs = 'SELECT b.id, b.name 
					 FROM '.$t_repo_table2.' b
					 where id_project=' . db_param().'
					  AND b.active = 0 and id!=' . db_param().'';	

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
		<td class="category">Name</td><td><input type="text" name="cu_name" id="cu_name" value="<?php echo $name ?>" size="133"/></td>
	</tr>
		<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Goal</td><td><textarea cols="100" name="goal" id="goal"><?php echo $goal ?></textarea></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Actor/s</td>
		<td>
			<table>
				<?php 
				$t_repo_table = plugin_table( 'actor', 'honey' );
				$t_repo_table2 = plugin_table( 'usecase_actor', 'honey' );

				$query_actors = 'SELECT a.name , a.id
								 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id=b.id_actor)
								 where b.id_usecase=' . db_param().'
								 AND a.active = 0 AND b.active = 0';

				$result_actors = db_query_bound( $query_actors, array($id_usecase) );
				$count_actors = db_num_rows( $result_actors );

				$j=0;
				while( $row_all_actors = db_fetch_array( $result_all_actors )){
					$ck=false;
					$result_actors = db_query_bound( $query_actors, array($id_usecase) );
					while( $row_actors = db_fetch_array( $result_actors )){
						if($row_actors['id']==$row_all_actors['id']){
							$ck=true;
						}
					}?>
				<tr>
						<td>
							<?php if($ck==false){?>
								<input type="checkbox" name="ck_actor_<?php echo $j?>" id="ck_actor_<?php echo $j?>"/>
							<?php }else{ ?>
									<input type="checkbox" name="ck_actor_<?php echo $j?>" id="ck_actor_<?php echo $j?>" checked/>
							<?php } ?>
							<input type="hidden" name="id_actor_<?php echo $j?>" id="id_actor_<?php echo $j?>" value="<?php echo $row_all_actors['id'] ?>"/>
						</td>
							<td><?php echo $row_all_actors['name'] ?></td>
					

				</tr>
				<?php $j++;	
				} ?>
			</table>

			<?php if($count_actors==0){echo "<p class='category'> No actors created for this project<p>";}?>
		</td>
	</tr>
	 <!--aca van las relaciones extiende-->
	  <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile' ); collapse_icon('profile'); echo 'Extends';}

				$result_all_ucs = db_query_bound( $query_all_ucs, array($id_project, $id_usecase) );
				$count_all_ucs = db_num_rows( $result_all_ucs);
				

				$t_repo_table = plugin_table( 'usecase_extend', 'honey' );
				$t_repo_table2 = plugin_table( 'usecase', 'honey' );

				$query_parents = 'SELECT b.id, b.name 
					 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id_usecase_parent=b.id)
					 where id_usecase_extends=' . db_param().'
					 AND a.active = 0 AND b.active = 0';

				$j=0;
				while( $row_all_ucs = db_fetch_array( $result_all_ucs )){
					$ck=false;
					$result_parents = db_query_bound( $query_parents, array($id_usecase) );
					while( $row_parents = db_fetch_array( $result_parents )){
						if($row_parents['id']==$row_all_ucs['id']){
							$ck=true;
						}
					}?>
				<table>
				<tr>
						<td>
							<?php if($ck==false){?>
								<input type="checkbox" name="ck_rule_<?php echo $j?>" id="ck_rule_<?php echo $j?>"/>
							<?php }else{ ?>
									<input type="checkbox" name="ck_rule_<?php echo $j?>" id="ck_rule_<?php echo $j?>" checked/>
							<?php } ?>
							<input type="hidden" name="id_rule_<?php echo $j?>" id="id_rule_<?php echo $j?>" value="<?php echo $row_all_ucs['id'] ?>"/>
						</td>
							<td><?php echo $row_all_ucs['name'] ?></td>
					

				</tr>
				<?php $j++;	
				} ?>
			</table>

			<?php if($count_all_ucs==0){echo "<p class='category'> No usecases created for this project<p>";}?>
			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile' ); collapse_icon('profile'); echo 'Extends';?>
				<?php collapse_end( 'profile' ); ?>
			<?php } ?>
		


		</td>
	  </tr>		
	<!--aca van las relaciones INCLUYE-->
	  <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile2' ); collapse_icon('profile2'); echo 'Includes';}
				
				$result_all_ucs = db_query_bound( $query_all_ucs, array($id_project, $id_usecase) );
				$count_all_ucs = db_num_rows( $result_all_ucs);				

				$t_repo_table = plugin_table( 'usecase_include', 'honey' );
				$t_repo_table2 = plugin_table( 'usecase', 'honey' );

				$query_childs = 'SELECT b.id , b.name
								 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id_usecase_include=b.id)
								 where id_usecase_parent=' . db_param().'
								 AND a.active = 0 AND b.active = 0';

				$j=0;
				while( $row_all_ucs = db_fetch_array( $result_all_ucs )){
					$ck=false;
					$result_childs = db_query_bound( $query_childs, array($id_usecase) );
					while( $row_childs = db_fetch_array( $result_childs )){
						if($row_childs['id']==$row_all_ucs['id']){
							$ck=true;
						}
					}?>
				<table>
				<tr>
						<td>
							<?php if($ck==false){?>
								<input type="checkbox" name="ck_rule_<?php echo $j?>" id="ck_rule_<?php echo $j?>"/>
							<?php }else{ ?>
									<input type="checkbox" name="ck_rule_<?php echo $j?>" id="ck_rule_<?php echo $j?>" checked/>
							<?php } ?>
							<input type="hidden" name="id_rule_<?php echo $j?>" id="id_rule_<?php echo $j?>" value="<?php echo $row_all_ucs['id'] ?>"/>
						</td>
							<td><?php echo $row_all_ucs['name'] ?></td>
					

				</tr>
				<?php $j++;	
				} ?>
			</table>

			<?php if($count_all_ucs==0){echo "<p class='category'> No usecases created for this project<p>";}?>
			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile2' ); collapse_icon('profile2'); echo 'Includes';?>
				<?php collapse_end( 'profile2' ); ?>
			<?php } ?>
		
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Preconditions</td><td><textarea  cols="100" rows="5"  name="preconditions" id="preconditions"><?php echo $precond ?></textarea></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Postconditions</td><td><textarea  cols="100" rows="5"  name="preconditions" id="preconditions"><?php echo $postcond ?></textarea></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Observation</td><td><Textarea cols="100" name="obsevations" id="obsevations" ><?php echo $observation ?></Textarea></td>
	</tr>
		<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Main Scenario</td><td> <Textarea cols="100" rows="15" name="cursoNormal" id="cursoNormal"><?php echo $main_scenario ?></Textarea></td>
	</tr>
		<!--aca van las reglas-->
	  <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile3' ); collapse_icon('profile3'); echo 'Rules';}

				$t_repo_table = plugin_table( 'rule_usecase', 'honey' );
				$t_repo_table2 = plugin_table( 'rule', 'honey' );

				$query_rules_usecase = 'SELECT b.name , b.id
								 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id_rule=b.id)
								 where id_usecase=' . db_param().'
								AND b.active = 0';

				$j=0;
				while( $row_all_rules = db_fetch_array( $result_all_rules )){
					$ck=false;
					$result_rules_usecase = db_query_bound( $query_rules_usecase, array($id_usecase) );
					while( $row_rules_usecase = db_fetch_array( $result_rules_usecase )){
						if($row_rules_usecase['id']==$row_all_rules['id']){
							$ck=true;
						}
					}?>
				<table>
				<tr>
						<td>
							<?php if($ck==false){?>
								<input type="checkbox" name="ck_rule_<?php echo $j?>" id="ck_rule_<?php echo $j?>"/>
							<?php }else{ ?>
									<input type="checkbox" name="ck_rule_<?php echo $j?>" id="ck_rule_<?php echo $j?>" checked/>
							<?php } ?>
							<input type="hidden" name="id_rule_<?php echo $j?>" id="id_rule_<?php echo $j?>" value="<?php echo $row_all_rules['id'] ?>"/>
						</td>
							<td><?php echo $row_all_rules['name'] ?></td>
					

				</tr>
				<?php $j++;	
				} ?>
			</table>

			<?php if($count_all_rules==0){echo "<p class='category'> No rules created for this project<p>";}?>
			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile3' ); collapse_icon('profile3'); echo 'Rules';?>
				<?php collapse_end( 'profile3' ); ?>
			<?php } ?>
		</td>
	</tr>
		<?php 
		$h=0;
		while( $row_alternative_scenario = db_fetch_array( $result_alternative_scenario )){
			$alt= $row_alternative_scenario['steps'];
			$alt=str_replace("<br>", "\n",$alt);
			?>
			<tr <?php echo helper_alternate_class() ?>>
				<td class="category">Alternative Scenario</td>
				<td><Textarea cols="100" rows="5" name="cursoAlternativo<?php echo $h?>" id="cursoAlternativo<?php echo $h?>"><?php echo $alt?></Textarea>
				</td>
			</tr>
			
			<?php
			$h++;	
		}//while cada escenario ?>

<?php # Attachments

		echo '<tr ', helper_alternate_class(), '>';
		echo '<td class="category">	<a name="attachments" id="attachments">', 'Attachments', '</a>','</td>';
		echo '<td colspan="5">';
		print_uc_attachments_list( $id_usecase );
		echo '</td></tr>';
?>
</table>

<?php // echo $interface_main?>

 <!--aca muestro las notas-->

<?php # UC notes BEGIN (permite el salto a #)?>
<a name="uc_notes" id="uc_notes" /><br />

<?php

$t_uc_note_table = plugin_table( 'uc_note','honey' );
$t_user_table = db_get_table( 'mantis_user_table' );
$query_note = "SELECT a.id, a.id_uc, a.note, a.reporter_id, a.view_state, a.date_submitted,a.last_modified, b.username, b.access_level
					  FROM ".$t_uc_note_table." a inner join  ".$t_user_table." b on (a.reporter_id=b.id)
					  WHERE id_uc =" . db_param() . " and a.active=0";

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
<!--aca van las notas-->
	<table class="width90">
	  <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile' ); collapse_icon('profile'); echo 'Add Note';}?>
				<table>
				<tr>
					<td class="category" width="25%">Note</td>
					<td width="75%">
					<textarea cols="88" rows="10" name="new_note"></textarea>
					</td>
				</tr>
				<tr><td colspan="2"><input type="button" onClick="javascript:go_page(0,<?php  echo $id_usecase?>,'<?php  echo plugin_page("add_uc_note");?>')" value="Add Note"/></td></tr>
				</table>
			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
			<?php collapse_closed( 'profile' ); collapse_icon('profile'); echo 'Add Note';?>
			<?php collapse_end( 'profile' ); ?>
			<?php } ?>
		</td>
	  </tr>
	</table>  




<br>
<table align="center">
	<tr>
		<td><input type="submit" value="Update Information"/></td>
		<td><input type="button" value="Cancel"  onClick="javascript:go_page(null,<?php echo $id_usecase?> ,'<?php echo $t_page?>')"/></td>
	</tr>
</table>
</div>

</form>

<?php

html_page_bottom1( );

?>