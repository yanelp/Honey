<?php

require_once('functions.php');
require_once( 'core.php' );
//require_once('manage_sequences.php');
require_once( 'file_api.php' );


html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();


$project_id =  helper_get_current_project();


if ( ( ALL_PROJECTS == helper_get_current_project() ) && ( 0 == $f_master_bug_id ) ) {
		print_header_redirect( 'login_select_proj_page.php?ref=plugin.php?page=Honey/new_usecase_page.php' );
	}

//Generación de la identificación del CU visible por el usuario

/*$nextId = getNextSeq("usecase_secuence");
$view_id = "CU_".$nextId;*/

$t_page=plugin_page("new_usecase_page");


EVENT_LAYOUT_RESOURCES
?>



<div align="center">
<form name="form1"  id="form1"  method="post"  enctype="multipart/form-data" onsubmit="javascript:validar()" action="<?php echo plugin_page( "save_usecase" ); ?>">
<table  class="width90" cellspacing="1">
	<tr>
		<td class="form-title" colspan="2">
			<input type="hidden" name="project_id" value="<?php echo $t_project_id ?>" />
			<?php //echo lang_get( 'plugin_Honey_usecase_detail' )." ".$view_id ?>
			<?php echo lang_get( 'plugin_Honey_usecase_detail' )?>
	       <!-- <input type="hidden" name="view_id" value="<?php echo $view_id ?>" />-->
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		   <td class="category">
		   <span class="required">*</span><?php echo plugin_lang_get('name')?>
		</td>
		<td>
	      <input type="text" name="cu_name" id='cu_name' size="133">
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		 <td class="category">
		    <?php echo plugin_lang_get('objetive')?>
		</td>
		<td>
		   <Textarea cols="100" name="goal" id="goal"></Textarea>
		</td>
	</tr>

	<?php
		//busco todos los actores del proyecto

		$t_repo_table = plugin_table( 'actor', 'honey' );

		$query_actors = 'SELECT * 
						 FROM '.$t_repo_table.' 
						 where id_project=' . db_param().' and active=0';

		$result_actors = db_query_bound( $query_actors, array($project_id) );
		$count_actors = db_num_rows( $result_actors);
	?>
	<tr <?php echo helper_alternate_class() ?>>
		 <td class="category">
		    <?php echo plugin_lang_get('actors_involved')?>
		</td>
		<td>
			<table>
				<?php 
				$j=0;
				while( $row_actors = db_fetch_array( $result_actors )){?>
				<tr>
					<td><input type="checkbox" name="ck_actor_<?php echo $j?>" id="ck_actor_<?php echo $j?>"/>
					<input type="hidden" name="id_actor_<?php echo $j?>" id="id_actor_<?php echo $j?>" value="<?php echo $row_actors['id'] ?>"/></td>
					<td><?php echo $row_actors['name'] ?></td>
					<?php if( $row_actors['description']!=''){?>
							<td>
									<!---->
								<?php
								$c_id = $row_actors['id'];
								echo "<script type=\"text/javascript\" language=\"JavaScript\">
									<!--
									function swap_content4( span ) {
									displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
									document.getElementById( span ).style.display = displayType;
									}

									 -->
									 </script>";
							echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content4(\"hideSection_" . $c_id . "\");swap_content4(\"showSection_" . $c_id . "\");return false;'>".plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content4(\"hideSection_" . $c_id . "\");swap_content4(\"showSection_" . $c_id . "\");return false;'>".plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_actors['description'];


								echo "</pre></span>\n";

								?>
							<!---->
							</td>
							<?php }
							else { echo "<td class='small'>[".plugin_lang_get('no_descrip')."]</td>";}?>


				</tr>
				<?php 
				$j++;	
				} ?>
			</table>

			<?php if($count_actors==0){echo "<p class='category'> ".plugin_lang_get('no_actors_created')."<p>";}?>
		</td>
	</tr>

	 <!--aca van las relaciones extiende-->
	  <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile3' ); collapse_icon('profile3'); echo plugin_lang_get('extends');

				//busco todas las reglas del proyecto

				$t_repo_table = plugin_table( 'usecase', 'honey' );

				$query_extends = 'SELECT * 
								 FROM '.$t_repo_table.' 
								 where id_project=' . db_param().' and active=0';

				$result_extends = db_query_bound( $query_extends, array($project_id) );
				$count_extends = db_num_rows( $result_extends );

				$j=0;

			}?>
			<table>
				<?php while( $row_extends = db_fetch_array( $result_extends )){?>
				<tr>
					<td><input type="checkbox" name="ck_extends_<?php echo $j?>"/>
					<input type="hidden" name="id_extends_<?php echo $j?>" id="id_extends_<?php echo $j?>" value="<?php echo $row_extends['id'] ?>"/></td>
					<td><?php echo $row_extends['name'] ?></td>

					<?php if( $row_extends['goal']!=''){?>
							<td>
							<!---->
								<?php
								$c_id = $row_extends['id'];
								echo "<script type=\"text/javascript\" language=\"JavaScript\">
									<!--
									function swap_content5( span ) {
									displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
									document.getElementById( span ).style.display = displayType;
									}

									 -->
									 </script>";
							echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content5(\"hideSection_" . $c_id . "\");swap_content5(\"showSection_" . $c_id . "\");return false;'>".plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content5(\"hideSection_" . $c_id . "\");swap_content5(\"showSection_" . $c_id . "\");return false;'".plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_extends['goal'];


								echo "</pre></span>\n";

								?>
							<!---->
							</td>
							<?php }
							else { echo "<td class='small'>[".plugin_lang_get('no_goal')."]</td>";}?>	

				</tr>
				<?php
				$j++;
				} ?>
			</table>

			<?php if($count_extends==0){echo "<p class='category'> ".plugin_lang_get('no_uc_created')."<p>";}?>

			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile3' ); collapse_icon('profile3'); echo plugin_lang_get('extends');?>
				<?php collapse_end( 'profile3' ); ?>
			<?php } ?>
		</td>
	  </tr>		
<!--aca terminan las extiende-->

	 <!--aca van las relaciones incluye-->
	  <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile4' ); collapse_icon('profile4'); echo plugin_lang_get('includes');

				//busco todas las reglas del proyecto

				$t_repo_table = plugin_table( 'usecase', 'honey' );

				$query_includes = 'SELECT * 
								 FROM '.$t_repo_table.' 
								 where id_project=' . db_param().' and active=0';

				$result_includes = db_query_bound( $query_includes, array($project_id) );
				$count_includes = db_num_rows( $result_includes);

				$j=0;

			}?>
			<table>
				<?php while( $row_includes = db_fetch_array( $result_includes )){?>
				<tr>
					<td><input type="checkbox" name="ck_includes_<?php echo $j?>"/>
					<input type="hidden" name="id_includes_<?php echo $j?>" id="id_includes_<?php echo $j?>" value="<?php echo $row_includes['id'] ?>"/></td>
					<td><?php echo $row_includes['name'] ?></td>

					<?php if( $row_includes['goal']!=''){?>
							<td>
							<!---->
								<?php
								$c_id = $row_includes['id']."algo";
								echo "<script type=\"text/javascript\" language=\"JavaScript\">
									<!--
									function swap_content6( span ) {
									displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
									document.getElementById( span ).style.display = displayType;
									}

									 -->
									 </script>";
							echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content6(\"hideSection_" . $c_id . "\");swap_content6(\"showSection_" . $c_id . "\");return false;'>".plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content6(\"hideSection_" . $c_id . "\");swap_content6(\"showSection_" . $c_id . "\");return false;'>".plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_includes['goal'];


								echo "</pre></span>\n";

								?>
							<!---->
							</td>
							<?php }
							else { echo "<td  class='small'>[".plugin_lang_get('no_goal')."]</td>";}?>
				</tr>
				<?php 
				$j++;
				} ?>
			</table>

			<?php if($count_includes==0){echo "<p class='category'>".plugin_lang_get('no_uc_created')."<p>";}?>

			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile4' ); collapse_icon('profile4'); echo plugin_lang_get('includes');?>
				<?php collapse_end( 'profile4' ); ?>
			<?php } ?>
		</td>
	  </tr>		
<!--aca terminan las incluye-->

	<tr <?php echo helper_alternate_class() ?>>
		 <td class="category">
		    <?php echo plugin_lang_get('pre_conditions');?>
		</td>
		<td>
		  <Textarea cols="100" rows="5"  name="preconditions" id="preconditions"></Textarea>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		 <td class="category">
		    <?php echo plugin_lang_get('post_conditions');?>
		</td>
		<td>
	     <Textarea cols="100" rows="5" name="postconditions" id="postconditions"></Textarea>
		</td>
	 </tr>
     <tr <?php echo helper_alternate_class() ?>>
		 <td class="category">
		    <?php echo plugin_lang_get('observations');?>
		</td>
		<td>
	      <Textarea cols="100" name="obsevations" id="obsevations" ></Textarea>
		</td>
	  </tr>
	  <tr <?php echo helper_alternate_class() ?>>
		 <td class="category">
		 <span class="required">*</span>  <?php echo plugin_lang_get('normal_course');?>
		</td>
		<td>
	      <Textarea cols="100" rows="15" name="cursoNormal" id="cursoNormal"></Textarea>
		</td>
	  </tr>
	   <tr <?php echo helper_alternate_class() ?>>
		 <td class="category">  <?php echo plugin_lang_get('alt_courses');?>
		</td>
		<td>
	      <Textarea cols="100" rows="5" name="cursoAlternativo" id="cursoAlternativo"></Textarea>
		  <input type='button' name='button_actor_add' value='Add alternative course' onClick="javascript:insert_row_course('table_course','cursoAlternativo',document.getElementById('cursoAlternativo').value)"/>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">&nbsp;</td>
		<td><table name='table_course' id='table_course' ><thead></thead><tbody valign="bottom"></tbody></table></td>
	 </tr>
	  </tr>

      <!--aca van las reglas-->
	  <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile' ); collapse_icon('profile'); echo plugin_lang_get('assign_rules');

				//busco todas las reglas del proyecto

				$t_repo_table = plugin_table( 'rule', 'honey' );

				$query_rules = 'SELECT * 
								 FROM '.$t_repo_table.' 
								 where id_project=' . db_param().' and active=0';

				$result_rules = db_query_bound( $query_rules, array($project_id) );
				$count_rules = db_num_rows( $result_rules );

				$j=0;

			}?>
			<table>
				<?php while( $row_rules = db_fetch_array( $result_rules )){?>
				<tr>
					<td><input type="checkbox" name="ck_rule_<?php echo $j?>"/>
					<input type="hidden" name="id_rule_<?php echo $j?>" id="id_rule_<?php echo $j?>" value="<?php echo $row_rules['id'] ?>"/></td>
					<td><?php echo $row_rules['name'] ?></td>

					<?php if( $row_rules['description']!=''){?>
							<td>
								<!---->
								<?php
								$c_id = $row_rules['id'];
								echo "<script type=\"text/javascript\" language=\"JavaScript\">
									<!--
									function swap_content3( span ) {
									displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
									document.getElementById( span ).style.display = displayType;
									}

									 -->
									 </script>";
							echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content3(\"hideSection_" . $c_id . "\");swap_content3(\"showSection_" . $c_id . "\");return false;'>".plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content3(\"hideSection_" . $c_id . "\");swap_content3(\"showSection_" . $c_id . "\");return false;'>".plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_rules['description'];


								echo "</pre></span>\n";

								?>
							<!---->
							</td>	
							<?php }
							else { echo "<td  class='small'>[".plugin_lang_get('no_goal')."]</td>";}?>

				</tr>
				<?php
				$j++;
				} ?>
			</table>

			<?php if($count_rules==0){echo "<p class='category'> ".plugin_lang_get('no_rules_created')."<p>";}?>

			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile' ); collapse_icon('profile'); echo plugin_lang_get('assign_rules');?>
				<?php collapse_end( 'profile' ); ?>
			<?php } ?>
		</td>
	  </tr>		
<!--aca terminan las reglas-->

<!--aca va el archivo-->

<?php
	// File Upload (if enabled)
		$t_max_file_size = (int)min( ini_get_number( 'upload_max_filesize' ), ini_get_number( 'post_max_size' ), config_get( 'max_file_size' ) );
		//$t_file_upload_max_num = max( 1, config_get( 'file_upload_max_num' ) );
?>
	 <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile2' ); collapse_icon('profile2'); echo plugin_lang_get('attach');?>
		
				<input type="hidden" name="max_file_size" value="<?php echo $t_max_file_size ?>" />

				<table>
					<tr><td><?php echo lang_get( $t_file_upload_max_num == 1 ? 'upload_file' : 'upload_files' ) ?>
					<?php echo '<span class="small">(' . lang_get( 'max_file_size' ) . ': ' . number_format( $t_max_file_size/1000 ) . 'k)</span>'?></td></tr>
					<tr>
						<td>
							<?php
							// Display multiple file upload fields
							for( $i = 0; $i < 10; $i++ ) {?>

								<input <?php echo helper_get_tab_index() ?> id="ufile[]" name="ufile[]" type="file" size="50" />
								<br>
								
							<?php 
							}//for	?>
						</td>
					</tr>
				</table>

			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile2' ); collapse_icon('profile2'); echo plugin_lang_get('attach');?>
				<?php collapse_end( 'profile2' ); ?>
			<?php }
		}//if( ON == config_get ?>
		</td>
	  </tr>	


<!--aca termina lo del archivo-->


	  <tr>
		<td class="left">
			<span class="required"> * <?php echo lang_get( 'required' ) ?></span>
		</td>

		<td class="center"><input type='submit' name='button_ok' value='<?php  echo plugin_lang_get('save');?>' onclick='validar();'>
		<input type='button' name='button_cancel' value='<?php  echo plugin_lang_get('cancel');?>'  onClick="javascript:go_page(null, null,'<?php echo $t_page?>')"></td>
	</tr>

</table>
<input type='hidden' name='row_number_cursoAlternativo' id='row_number_cursoAlternativo' value='0'/>
<input type='hidden' name='row_number_uc_actor' id='row_number_uc_actor' value='<?php echo $count_actors ?>'/>
<input type='hidden' name='row_number_uc_rule' id='row_number_uc_rule' value='<?php echo $count_rules ?>'/>
<input type='hidden' name='row_number_uc_extends' id='row_number_uc_extends' value='<?php echo $count_extends ?>'/>
<input type='hidden' name='row_number_uc_includes' id='row_number_uc_includes' value='<?php echo $count_includes ?>'/>
<input type="hidden" name="operation" id="operation" value="1"/>
</div>
</form>


<?php

html_page_bottom1( );

?>
