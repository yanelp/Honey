<?php

require_once('functions.php');
require_once( 'file_api.php' );
//require_once( 'current_user_api.php' );

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

$id_usecase = gpc_get_int( 'id_usecase' );
$id_project=helper_get_current_project();

$t_page_update="update_usecase";
$t_page_update=$t_page_update."&id_usecase=".$id_usecase;

$t_page=plugin_page("view_cu_page");
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
$row = db_fetch_array( $result );

$name=$row['name'];
$observation=$row['observations'];
$id=$row['id'];
$id= str_pad($row['id'], 7, "0", STR_PAD_LEFT);

$precond= $row['preconditions'];
$precond=str_replace("<br>", "\n",$precond);	

$postcond=$row['postconditions'];
$postcond=str_replace("<br>", "\n",$postcond);	



$goal=$row['goal'];

//busco todos los actores

$t_repo_table = plugin_table( 'actor', 'honey' );

$query_all_actors = 'SELECT a.name , a.id, a.description
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

	$query_all_rules = 'SELECT b.name , b.id, b.description
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
	$count_alternative_scenarios= db_num_rows( $result_alternative_scenario);


//busco todos los cus menos el seleccionado

	$t_repo_table2 = plugin_table( 'usecase', 'honey' );

	$query_all_ucs = 'SELECT b.id, b.name , b.goal
					 FROM '.$t_repo_table2.' b
					 where id_project=' . db_param().'
					  AND b.active = 0 and id!=' . db_param().'';	

?>

<div align="center">
<table class="width90" <?php echo plugin_lang_get( 'summary_modify_uc' );?>>
	<tr>
		<td class="form-title" colspan="2">
		<?php echo plugin_lang_get( 'usecase_information' );
		echo '&#160;<span class="small">';
	   // print_bracket_link( "#uc_notes",'Jump to notes'  );?>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category" width="20%"><?php echo plugin_lang_get('ID')?></td><td><?php echo $id ?></td>
		
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get('name')?></td><td><input type="text" name="cu_name" id="cu_name" value="<?php echo $name ?>" size="133"/></td>
	</tr>
		<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get('goal')?></td><td><textarea cols="100" name="goal" id="goal"><?php echo $goal ?></textarea></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get('actor')?></td>
		<td>
			<table>
				<?php 
				$t_repo_table = plugin_table( 'actor', 'honey' );
				$t_repo_table2 = plugin_table( 'usecase_actor', 'honey' );

				$query_actors = 'SELECT a.name , a.id, a.description
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
							<?php if( $row_all_actors['description']!=''){?>
							<td>
									<!---->
								<?php
								$c_id = $row_all_actors['id'];
								echo "<script type=\"text/javascript\" language=\"JavaScript\">
									<!--
									function swap_content4( span ) {
									displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
									document.getElementById( span ).style.display = displayType;
									}

									 -->
									 </script>";
							echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content4(\"hideSection_" . $c_id . "\");swap_content4(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content4(\"hideSection_" . $c_id . "\");swap_content4(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_all_actors['description'];


								echo "</pre></span>\n";

								?>
							<!---->
							</td>
							<?php }
							else { echo "<td  class='small'>[". plugin_lang_get('no_goal')."]</td>";}?>
				</tr>
				<?php $j++;	
				} ?>
			</table>

			<?php if($count_all_actors==0){echo "<p class='category'>". plugin_lang_get('no_actors_created')."<p>";}?>
		</td>
	</tr>
	 <!--aca van las relaciones extiende-->
	  <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile' ); collapse_icon('profile'); echo  plugin_lang_get('extends');}

				$result_all_ucs = db_query_bound( $query_all_ucs, array($id_project, $id_usecase) );
				$count_all_ucs = db_num_rows( $result_all_ucs);
				

				$t_repo_table = plugin_table( 'usecase_extend', 'honey' );
				$t_repo_table2 = plugin_table( 'usecase', 'honey' );

				$query_parents = 'SELECT b.id, b.name , b.goal
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
								<input type="checkbox" name="ck_extends_<?php echo $j?>" id="ck_extends_<?php echo $j?>"/>
							<?php }else{ ?>
									<input type="checkbox" name="ck_extends_<?php echo $j?>" id="ck_extends_<?php echo $j?>" checked/>
							<?php } ?>
							<input type="hidden" name="id_extends_<?php echo $j?>" id="id_extends_<?php echo $j?>" value="<?php echo $row_all_ucs['id'] ?>"/>
						</td>
							<td><?php echo $row_all_ucs['name'] ?></td>
							<?php if( $row_all_ucs['goal']!=''){?>
							<td>
							<!---->
								<?php
								$c_id = $row_all_ucs['id'];
								echo "<script type=\"text/javascript\" language=\"JavaScript\">
									<!--
									function swap_content5( span ) {
									displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
									document.getElementById( span ).style.display = displayType;
									}

									 -->
									 </script>";
							echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content5(\"hideSection_" . $c_id . "\");swap_content5(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content5(\"hideSection_" . $c_id . "\");swap_content5(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_all_ucs['goal'];


								echo "</pre></span>\n";

								?>
							<!---->
							</td>
							<?php }
							else { echo "<td  class='small'>[". plugin_lang_get('no_goal')."]</td>";}?>					

				</tr>
				<?php $j++;	
				} ?>
			</table>

			<?php if($count_all_ucs==0){echo "<p class='category'> ". plugin_lang_get('no_uc_created')."<p>";}?>
			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile' ); collapse_icon('profile'); echo plugin_lang_get('extends');?>
				<?php collapse_end( 'profile' ); ?>
			<?php } ?>
		


		</td>
	  </tr>		
	<!--aca van las relaciones INCLUYE-->
	  <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile2' ); collapse_icon('profile2'); echo  plugin_lang_get('includes');}
				
				$result_all_ucs = db_query_bound( $query_all_ucs, array($id_project, $id_usecase) );
				$count_all_ucs = db_num_rows( $result_all_ucs);				

				$t_repo_table = plugin_table( 'usecase_include', 'honey' );
				$t_repo_table2 = plugin_table( 'usecase', 'honey' );

				$query_childs = 'SELECT b.id , b.name, b.goal
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
								<input type="checkbox" name="ck_includes_<?php echo $j?>" id="ck_includes_<?php echo $j?>"/>
							<?php }else{ ?>
									<input type="checkbox" name="ck_includes_<?php echo $j?>" id="ck_includes_<?php echo $j?>" checked/>
							<?php } ?>
							<input type="hidden" name="id_includes_<?php echo $j?>" id="id_includes_<?php echo $j?>" value="<?php echo $row_all_ucs['id'] ?>"/>
						</td>
							<td><?php echo $row_all_ucs['name'] ?></td>
							<?php if( $row_all_ucs['goal']!=''){?>
							<td>
							<!---->
								<?php
								$c_id = $row_all_ucs['id']."algo";
								echo "<script type=\"text/javascript\" language=\"JavaScript\">
									<!--
									function swap_content6( span ) {
									displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
									document.getElementById( span ).style.display = displayType;
									}

									 -->
									 </script>";
							echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content6(\"hideSection_" . $c_id . "\");swap_content6(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content6(\"hideSection_" . $c_id . "\");swap_content6(\"showSection_" . $c_id . "\");return false;'". plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_all_ucs['goal'];


								echo "</pre></span>\n";

								?>
							<!---->
							</td>
							<?php }
							else { echo "<td  class='small'>[". plugin_lang_get('no_goal')."]</td>";}?>

				</tr>
				<?php $j++;	
				} ?>
			</table>

			<?php if($count_all_ucs==0){echo "<p class='category'>". plugin_lang_get('no_uc_created')."<p>";}?>
			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile2' ); collapse_icon('profile2'); echo  plugin_lang_get('includes');?>
				<?php collapse_end( 'profile2' ); ?>
			<?php } ?>
		
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get('pre_conditions')?></td><td><textarea  cols="100" rows="5"  name="preconditions" id="preconditions"><?php echo $precond ?></textarea></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get('post_conditions')?></td><td><textarea  cols="100" rows="5"  name="postconditions" id="postconditions"><?php echo $postcond ?></textarea></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get('observations')?></td><td><Textarea cols="100" name="obsevations" id="obsevations" ><?php echo $observation ?></Textarea></td>
	</tr>
		<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get('main_scenario')?></td><td> <Textarea cols="100" rows="15" name="cursoNormal" id="cursoNormal"><?php echo $main_scenario ?></Textarea></td>
	</tr>
		<!--aca van las reglas-->
	  <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile3' ); collapse_icon('profile3'); echo 'Rules';}

				$t_repo_table = plugin_table( 'rule_usecase', 'honey' );
				$t_repo_table2 = plugin_table( 'rule', 'honey' );

				$query_rules_usecase = 'SELECT b.name , b.id, b.description
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
							<?php if( $row_all_rules['description']!=''){?>
							<td>
								<!---->
								<?php
								$c_id = $row_all_rules['id'];
								echo "<script type=\"text/javascript\" language=\"JavaScript\">
									<!--
									function swap_content3( span ) {
									displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
									document.getElementById( span ).style.display = displayType;
									}

									 -->
									 </script>";
							echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content3(\"hideSection_" . $c_id . "\");swap_content3(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content3(\"hideSection_" . $c_id . "\");swap_content3(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_all_rules['description'];


								echo "</pre></span>\n";

								?>
							<!---->
							</td>	
							<?php }
							else { echo "<td  class='small'>[". plugin_lang_get('no_goal')."]</td>";}?>
				</tr>

				<?php $j++;	
				} ?>
			</table>

			<?php if($count_all_rules==0){echo "<p class='category'>". plugin_lang_get('no_rules_created')."<p>";}?>
			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile3' ); collapse_icon('profile3'); echo plugin_lang_get('col_rules');?>
				<?php collapse_end( 'profile3' ); ?>
			<?php } ?>
		</td>
	</tr>

		<!--aca va para agregar nuevos escenarios alterativos-->

		  <tr <?php echo helper_alternate_class() ?>>
		 <td class="category"><?php echo plugin_lang_get('alt_scenario')?></td>
		<td>
	      <Textarea cols="100" rows="5" name="cursoAlternativo" id="cursoAlternativo"></Textarea>
		  <input type='button' name='button_actor_add' value='<?php echo plugin_lang_get('add_alt_scenario')?>' onClick="javascript:insert_row_course('table_course','cursoAlternativo',document.getElementById('cursoAlternativo').value)"/>
		</td>
		</tr>
		<tr <?php echo helper_alternate_class() ?>>
			<td class="category">&nbsp;</td>
			<td><table name='table_course' id='table_course'><thead></thead><tbody valign="bottom"></tbody></table></td>
		 </tr>


		 
		<!--Aca pongo en la tabla dinámica los escenarios alternativos almacenados-->
		<tr <?php echo helper_alternate_class() ?>>
			<td class="category"> &nbsp</td>
			<td class="category"> &nbsp;</td>
		 </tr>

		 <input type='hidden' name='row_number_cursoAlternativo' id='row_number_cursoAlternativo' value='0'/>
	

<?php # Attachments

		echo '<tr ', helper_alternate_class(), '>';
		echo '<td class="category">	<a name="attachments" id="attachments">', plugin_lang_get('attach'), '</a>','</td>';
		echo '<td colspan="5">';
		$cant_files=print_uc_attachments_list( $id_usecase , 0);//1 significa con delete file
		echo '</td></tr>';
?>


<!--aca van los archivos-->

<?php
	// File Upload (if enabled)
		$t_max_file_size = (int)min( ini_get_number( 'upload_max_filesize' ), ini_get_number( 'post_max_size' ), config_get( 'max_file_size' ) );
		//$t_file_upload_max_num = max( 1, config_get( 'file_upload_max_num' ) );
?>
	 <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile7' ); collapse_icon('profile7'); echo plugin_lang_get('add_attach');?>
		
				<input type="hidden" name="max_file_size" value="<?php echo $t_max_file_size ?>" />

				<table>
					<tr><td><?php echo lang_get( $t_file_upload_max_num == 1 ? 'upload_file' : 'upload_files' ) ?>
					<?php echo '<span class="small">(' . lang_get( 'max_file_size' ) . ': ' . number_format( $t_max_file_size/1000 ) . 'k)</span>'?></td></tr>
					<tr>
						<td>
							<?php
							// Display multiple file upload fields

							//muestro en total 10 archivos-->10-la cant de ya guardados
							$hasta=10-$cant_files;
							
							for( $i = 0; $i < $hasta; $i++ ) {?>

								<input <?php echo helper_get_tab_index() ?> id="ufile[]" name="ufile[]" type="file" size="50" />
								<br>
								
							<?php 
							}//for	?>
						</td>
					</tr>
				</table>

			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile7' ); collapse_icon('profile7'); echo plugin_lang_get('add_attach');?>
				<?php collapse_end( 'profile7' ); ?>
			<?php }
		}//if( ON == config_get ?>
		</td>
	  </tr>	


<!--aca termina lo de los archivos-->

</table>

<?php // echo $interface_main?>

 <!--aca muestro las notas-->
<!--
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
-->
<br>
<!--aca van las notas-->
	<!--<table class="width90">
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
				<?php $t_back=plugin_page("add_uc_note")?>
				<tr><td colspan="2"><input type="button" onClick="javascript:go_page(0,<?php  echo $id_usecase?>,'<?php  echo $t_back.'&backPage=update_usecase_page' ;?>')" value="Add Note"/></td></tr>
				</table>
			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
			<?php collapse_closed( 'profile' ); collapse_icon('profile'); echo 'Add Note';?>
			<?php collapse_end( 'profile' ); ?>
			<?php } ?>
		</td>
	  </tr>
	</table>  -->




<br>
<table align="center">
	<tr>
		<td><input type="submit" value="<?php echo plugin_lang_get('update')?>"/></td>
		<td><input type="button" value="<?php echo plugin_lang_get('cancel')?>"  onClick="javascript:go_page(null,<?php echo $id_usecase?> ,'<?php echo $t_page?>')"/></td>
	</tr>
</table>
</div>


<input type='hidden' name='row_number_uc_actor' id='row_number_uc_actor' value='<?php echo $count_all_actors ?>'/>
<input type='hidden' name='row_number_uc_rule' id='row_number_uc_rule' value='<?php echo $count_all_rules ?>'/>
<input type='hidden' name='row_number_uc_extends' id='row_number_uc_extends' value='<?php echo $count_all_ucs ?>'/>
<input type='hidden' name='row_number_uc_includes' id='row_number_uc_includes' value='<?php echo $count_all_ucs ?>'/>
<input type='hidden' name='cant_files' id='cant_files' value='<?php echo $hasta ?>'/>


	<?php

		$h=0;
		while( $row_alternative_scenario = db_fetch_array( $result_alternative_scenario )){
			$alt=trim( $row_alternative_scenario['steps']);
			$alt=str_replace("<br>", "\n",$alt);
			?>
			<div id="capa_oculta_<?php echo $h?>">
			<textarea name="oculto_alt_<?php echo $h?>" id="oculto_alt_<?php echo $h?>"  ><?php echo $alt?></textarea>
			</div>
			<?php
			echo "<script>";
			echo "insert_row_course('table_course', 'cursoAlternativo',document.getElementById('oculto_alt_".$h."').value)";
			echo "</script>";
			$h++;	
		}//while cada escenario ?>


<script>
var l=<?php echo $h?>;
for(i=0;i<l;i++){
	//document.getElementById('capa_oculta_'+i).style.visibility = 'hidden'; 
}
</script>
</form>

<?php

html_page_bottom1( );

?>