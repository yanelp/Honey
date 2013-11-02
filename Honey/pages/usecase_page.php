<?php

require_once('functions.php');
require_once( 'file_api.php' );

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

$id_usecase = gpc_get_int( 'id_usecase' );

if($id_usecase==-1){$id_usecase=$_REQUEST['usecase_id'];}

$t_page_update="update_usecase_page";
$t_page_update=$t_page_update."&id_usecase=".$id_usecase;

$t_page=plugin_page("view_cu_page");
$t_page_delete=plugin_page("delete_usecase_page");
?>

<form id="form1" action="<?php echo plugin_page( $t_page_update ); ?>" method="POST" enctype="multipart/form-data">
<?php

//busco el caso de uso seleccionado

$t_repo_table = plugin_table( 'usecase', 'honey' );

$query_symbol = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id=' . db_param().'
				 AND active = 0';

$result = db_query_bound( $query_symbol, array($id_usecase) );
$count = db_num_rows( $result );

if($count>0){

	$row = db_fetch_array( $result );

	$name=$row['name'];
	$observation=$row['observations'];
	$id=$row['id'];
	$id= str_pad($row['id'], 7, "0", STR_PAD_LEFT);
	$precond=$row['preconditions'];
	$precond=str_replace("\n", "<br>",$precond);
	$postcond=$row['postconditions'];
	$postcond=str_replace("\n", "<br>",$postcond);
	$goal=$row['goal'];

	//busco el símbolo de donde proviene si es que es derivado

	$id_symbol=$row['id_symbol'];
	if($id_symbol!=''){	$id_symbol=str_pad($row['id_symbol'], 7, "0", STR_PAD_LEFT);}

	//busco sus actores (si los tiene)

	$t_repo_table = plugin_table( 'actor', 'honey' );
	$t_repo_table2 = plugin_table( 'usecase_actor', 'honey' );

	$query_actors = 'SELECT a.name , a.id
					 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id=b.id_actor)
					 where b.id_usecase=' . db_param().'
					 AND a.active = 0 AND b.active = 0';

	$result_actors = db_query_bound( $query_actors, array($id_usecase) );
	$count_actors = db_num_rows( $result_actors );

	$actors='';
	while( $row_actors = db_fetch_array( $result_actors )){
		$actor_id=str_pad($row_actors['id'], 7, "0", STR_PAD_LEFT);
		$t_page_link = plugin_page( 'update_actor_page' );
		$t_page_link=$t_page_link."&actor_id=".$row_actors['id'];
		$actor_id="<a  href=\"$t_page_link\">".$actor_id."</a>";
		if($actors==''){
			$actors=$actor_id.'-'.$row_actors['name'];
		}
		else{
			$actors=$actors.', '.$actor_id.'-'.$row_actors['name'];
			}
	}//while


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
		$main_scenario=str_replace("\n", "<br>",$main_scenario);
		$id_scenario=$row_main_scenario['id'];

		//rules 

		$t_repo_table = plugin_table( 'rule_usecase', 'honey' );
		$t_repo_table2 = plugin_table( 'rule', 'honey' );

		$query_rules_scenario = 'SELECT b.name , b.id
						 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id_rule=b.id)
						 where id_usecase=' . db_param().'
						AND b.active = 0';

		$result_rules_scenario = db_query_bound( $query_rules_scenario, array($id_usecase) );
		$count_rules_scenario = db_num_rows( $result_rules_scenario );
		
		$rules_main='';

		while( $row_rules_scenario = db_fetch_array( $result_rules_scenario )){
			$rule_id=str_pad($row_rules_scenario['id'], 7, "0", STR_PAD_LEFT);
			$t_page_link_rule = plugin_page( 'update_rule_page' );
			$t_page_link_rule=$t_page_link_rule."&id_rule=".$row_rules_scenario['id'];
			$rule_id="<a  href=\"$t_page_link_rule\">".$rule_id."</a>";
			if($rules_main==''){
				$rules_main=$rule_id.'-'.$row_rules_scenario['name'];
			}
			else{
				$rules_main=$rules_main.', '.$rule_id.'-'.$row_rules_scenario['name'];
				}
		}
		
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


	//busco si extiende de otro cu 

		$t_repo_table = plugin_table( 'usecase_extend', 'honey' );
		$t_repo_table2 = plugin_table( 'usecase', 'honey' );

		$query_parents = 'SELECT b.id, b.name 
						 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id_usecase_parent=b.id)
						 where id_usecase_extends=' . db_param().'
						 AND a.active = 0 AND b.active = 0';

		$result_parents = db_query_bound( $query_parents, array($id_usecase) );
		
		$parents='';
		
		while( $row_parents = db_fetch_array( $result_parents )){
			$parent_id=string_convert_uc_link($row_parents['id']);
			 
			if($parents==''){
				
				$parents=$parent_id.'-'.$row_parents['name'];
			}
			else{
				$parents=$parents.', '.$parent_id.'-'.$row_parents['name'];
				}
		}//while


		//busco si incluye otros cu 

		$t_repo_table = plugin_table( 'usecase_include', 'honey' );
		$t_repo_table2 = plugin_table( 'usecase', 'honey' );

		$query_childs = 'SELECT b.id , b.name
						 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id_usecase_include=b.id)
						 where id_usecase_parent=' . db_param().'
						 AND a.active = 0 AND b.active = 0';

		$result_childs = db_query_bound( $query_childs, array($id_usecase) );
		
			$childs='';
		
			while( $row_childs = db_fetch_array( $result_childs )){
		
				$child_id=string_convert_uc_link($row_childs['id']);
				if($childs==''){
					$childs=$child_id.'-'.$row_childs['name'];
				}
				else{
					$childs=$childs.', '.$child_id.'-'.$row_childs['name'];
					}
			}//while

		

	?>

	<div align="center">
	<table class="width90" summary="<?php echo plugin_lang_get( 'usecase_information' );?>">
		<tr>
			<td class="form-title" colspan="2">
			<?php echo plugin_lang_get( 'usecase_information' );
			echo '&#160;<span class="small">';
			print_bracket_link( "#uc_notes",'Jump to notes'  );
			print_bracket_link( "#uc_atach",'Jump to Atachmentts'  );?>
			</td>
		</tr>
		<tr <?php echo helper_alternate_class() ?>>
		<?php $t_page_from=plugin_page( 'symbol_page' );;
			  $t_page_from=$t_page_from."&id_symbol=".$row['id_symbol'];?>
			<td class="category" width="20%"><?php echo plugin_lang_get('ID')?></td><td><?php echo $id ?>  <?php if($id_symbol!=''){ echo "(".plugin_lang_get('derived_from'); echo "<a href=\"$t_page_from\">".$id_symbol."</a>".")"; }?>  </td>
		</tr>
		<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('name')?></td><td><?php echo $name ?></td>
		</tr>
			<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('goal')?></td><td><?php echo $goal ?></td>
		</tr>
		<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('actor')?></td><td><?php echo $actors ?></td>
		</tr>
		<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('extends')?></td><td><?php echo $parents ?></td>
		</tr>
		<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('includes')?></td><td><?php echo $childs ?></td>
		</tr>
		<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('pre_conditions')?></td><td><?php echo $precond ?></td>
		</tr>
		<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('post_conditions')?></td><td><?php echo $postcond ?></td>
		</tr>
		<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('observations')?></td><td><?php echo $observation ?></td>
		</tr>
			<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('main_scenario')?></td><td><?php echo $main_scenario ?></td>
		</tr>
			<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('col_rules')?></td><td><?php echo $rules_main?></td>
		</tr>
			<?php 
			while( $row_alternative_scenario = db_fetch_array( $result_alternative_scenario )){?>
				<tr <?php echo helper_alternate_class() ?>><td class="category"><?php echo plugin_lang_get('alternative_scenario')?></td><td><?php echo  $row_alternative_scenario['steps']?></td></tr>
				
				<?php
			}//while cada escenario ?>


	<?php # Attachments
			echo '<tr ', helper_alternate_class(), '>';
			echo '<td class="category"><a name="attachments" id="attachments">', plugin_lang_get('attach'), '</a>','</td>';
			echo '<td colspan="5">';
			$cant_files=print_uc_attachments_list( $id_usecase, 1 );//0 significa sin delete de file
			echo '</td></tr>';
	?>

	<!--aca van los archivos-->


	<?php # UC atach BEGIN (permite el salto a #)?>
	<a name="uc_atach" id="uc_atach" /><br />
	<?php
		// File Upload (if enabled)
			$t_max_file_size = (int)min( ini_get_number( 'upload_max_filesize' ), ini_get_number( 'post_max_size' ), config_get( 'max_file_size' ) );
			//$t_file_upload_max_num = max( 1, config_get( 'file_upload_max_num' ) );
	
		//muestro en total 10 archivos-->10-la cant de ya guardados
		$hasta=10-$cant_files;
								
		if($hasta>0){?>
			 <tr <?php echo helper_alternate_class() ?>>
				<td colspan="2" class="none">
					<?php 
					if( ON == config_get( 'use_javascript' ) ) { ?>
						<?php collapse_open( 'profile7' ); collapse_icon('profile7'); echo plugin_lang_get('add_attach');?>
				
						<input type="hidden" name="max_file_size" value="<?php echo $t_max_file_size ?>" />
						<table>
							<tr><td>(<?php echo plugin_lang_get('file_max_size' ) ?> 990KB</span>)</td></tr>
							<tr>
								<td>
									<?php

										//for( $i = 0; $i < $hasta; $i++ ) {?>

											<input <?php echo helper_get_tab_index() ?> id="ufile[]" name="ufile[]" type="file" size="50" />
											<br>
											
										<?php 
										//}//for	?>
							</td>
						</tr>
						
						<tr align='center'><td><input type="button" onClick="javascript:go_page(0,<?php  echo $id_usecase?>,'<?php  echo plugin_page('save_files_usecase');?>')" value="<?php echo plugin_lang_get('add_file')?>"/></td><td style="text-align:left">
						<address> <?php echo plugin_lang_get('button_add_file');?></address>
						
						</td>
						</tr>
						
					</table>

				<?php if( ON == config_get( 'use_javascript' ) ) { ?>
					<?php collapse_closed( 'profile7' ); collapse_icon('profile7'); echo plugin_lang_get('add_atach');?>
					<?php collapse_end( 'profile7' ); ?>
				<?php }
			}//if( ON == config_get ?>
			</td>
		  </tr>	
	<?php }//si puede poner mas archivos ?>

	<!--aca termina lo de los archivos-->


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

		
		 <table class="width90">
		 <tr <?php echo helper_alternate_class() ?>>
			<td colspan="2" class="none">
				<?php 
				if( ON == config_get( 'use_javascript' ) ) { ?>
					<?php collapse_open( 'profile2' ); collapse_icon('profile2'); echo plugin_lang_get( 'usecase_notes' ) ;}?>
			
	<table width="90%">
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
		  <td  class="<?php echo $t_bugnote_css ?>" width="20%">
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
				  <br />
				  
				  <div class="small">

				  <input type="button" onClick="javascript:go_page(<?php echo $id_note?>,<?php  echo $id_usecase?>,'<?php  echo plugin_page("uc_note_edit_page");?>')" value="<?php echo plugin_lang_get('edit')?>"/>
				  <input type="button" onClick="javascript:go_page(<?php echo $id_note?>,<?php  echo $id_usecase?>,'<?php  echo plugin_page("delete_uc_note");?>')" value="<?php echo plugin_lang_get('delete')?>"/>
				  <?php
					if($state==VS_PRIVATE){ ?>
					 <input type="button" onClick="javascript:go_page(<?php echo $id_note?>,<?php  echo $id_usecase?>,'<?php  echo plugin_page("set_view_state_uc_note");?>')" value="<?php echo plugin_lang_get('make_public')?>"/>
				  <?php }
						else{?>
							<input type="button" onClick="javascript:go_page(<?php echo $id_note?>,<?php  echo $id_usecase?>,'<?php  echo plugin_page("set_view_state_uc_note");?>')" value="<?php echo plugin_lang_get('make_private')?>"/>
						
				  <?php } ?>	

				</div>
		  </td>
		  <td  class="<?php echo $t_bugnote_note_css ?>" width="80%">
		  <?php echo string_convert_uc_link($note); ?>
		  </td>
		</tr>


		<?php }//while  ?>
		</table>

		<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile2' ); collapse_icon('profile2'); echo plugin_lang_get('usecase_notes');?>
				<?php collapse_end( 'profile2' ); ?>
				<?php } ?>
		</td></tr>

	</TABLE>


	<br>
	<!--aca van las notas-->

		<table class="width90">
		  <tr <?php echo helper_alternate_class() ?>>
			<td colspan="2" class="none">
				<?php 
				if( ON == config_get( 'use_javascript' ) ) { ?>
					<?php collapse_open( 'profile' ); collapse_icon('profile'); echo plugin_lang_get('add_notes');}?>
					<table>
					<tr>
						<td class="category" width="25%"><?php  echo plugin_lang_get('note')?></td>
						<td width="75%">
						<textarea cols="88" rows="10" name="new_note"></textarea>
						</td>
					</tr>
					<?php $t_back=plugin_page("add_uc_note")?>
					<tr><td colspan="2"><input type="button" onClick="javascript:go_page(0,<?php  echo $id_usecase?>,'<?php  echo $t_back.'&backPage=usecase_page'?>')" value="<?php echo plugin_lang_get('add_note')?>"/></td></tr>
					</table>
				<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile' ); collapse_icon('profile'); echo plugin_lang_get('add_notes');?>
				<?php collapse_end( 'profile' ); ?>
				<?php } ?>
			</td>
		  </tr>
		</table>  


	<br>
	<table align="center">
		<tr>
			<td><input type="submit" value=" <?php echo plugin_lang_get('edit')?>"/>
			<input type="button" value=" <?php echo plugin_lang_get('delete')?>" onClick="javascript:go_page(null,<?php echo $id_usecase?> ,'<?php echo $t_page_delete?>')"/>
			&#160&#160&#160
			<input type="button" value=" <?php echo plugin_lang_get('cancel')?>"  onClick="javascript:go_page(null,<?php echo $id_usecase?> ,'<?php echo $t_page?>')"/></td>
		</tr>
	</table>
	</div>
	<input type='hidden' name='cant_files' id='cant_files' value='<?php echo $hasta ?>'/>
	<input type='hidden' name='usecase_name' id='usecase_name' value='<?php echo $name ?>'/>

	</form>

<?php
}//if existe el uc buscado
else{?>
	<div align='center'>
	<?php showMessage(plugin_lang_get('uc_do_not_exist'), 'error')?>
	</table>
	</div>
	<?php
	echo '<br><br>';
	$t_page=plugin_page("view_cu_page");
	?>
	<table align='center'>
	<tr>
	<td class='center'>
	<?php echo "<a href=\"$t_page\">".plugin_lang_get('back')."</a>";?>
	</td>
	</tr>
	</table>

	<?php
	echo "<br>";
}
	
html_page_bottom1( );

?>