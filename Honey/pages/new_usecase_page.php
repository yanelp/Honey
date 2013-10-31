<?php

require_once('functions.php');
require_once( 'core.php' );
require_once( 'file_api.php' );


html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

$TAMANO_PAGINA=2;

$project_id =  helper_get_current_project();
$id_project=$project_id;


if ( ( ALL_PROJECTS == helper_get_current_project() ) && ( 0 == $f_master_bug_id ) ) {
		print_header_redirect( 'login_select_proj_page.php?ref=plugin.php?page=Honey/new_usecase_page.php' );
	}

$t_page=plugin_page("new_usecase_page");


EVENT_LAYOUT_RESOURCES
?>

<div align="center">
<form name="form1"  id="form1"  method="post"  enctype="multipart/form-data" onsubmit="javascript:validar()" action="<?php echo plugin_page( "save_usecase" ); ?>">
<table  class="width90" cellspacing="1" summary="<?php echo plugin_lang_get( 'summary_new_uc' )?>">
	<tr>
		<td class="form-title" colspan="2">
			<input type="hidden" name="project_id" value="<?php echo $t_project_id ?>" />
			<?php //echo lang_get( 'plugin_Honey_usecase_detail' )." ".$view_id ?>
			<?php echo plugin_lang_get( 'usecase_detail' )?>
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

		$query_all_actors = 'SELECT * 
						 FROM '.$t_repo_table.' 
						 where id_project=' . db_param().' and active=0';

		$result_all_actors = db_query_bound( $query_all_actors, array($project_id) );
		$count_all_actors = db_num_rows( $result_all_actors);

	?>

	 <!--aca va la lista de actores-->
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get('actors_involved')?></td>
		<td><?php include 'list_actors.php';?></td>
	</tr>

	<!--aca van las relaciones extiende-->
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get('extends')?></td>
		<td><?php include 'list_extends_new_uc.php';?></td>
	</tr>

	
	<!--aca van las relaciones incluye-->
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get('includes')?></td>
		<td><?php include 'list_includes_new_uc.php';?></td>
	</tr>

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
		<td class="category"><?php echo plugin_lang_get('assign_rules')?></td>
		<td><?php include 'list_rules_new_uc.php';?></td>
	</tr>


<!--aca va el archivo-->

<?php
	// File Upload (if enabled)
		$t_max_file_size = (int)min( ini_get_number( 'upload_max_filesize' ), ini_get_number( 'post_max_size' ), config_get( 'max_file_size' ) );
		$t_file_upload_max_num = max( 1, config_get( 'file_upload_max_num' ) );
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
					
							<table>
							<thead></thead><tbody valign="bottom">
								<tr>
								<td empty-cells></td><td empty-cells></td>
								<td>
								<input <?php echo helper_get_tab_index() ?> id="ufile[]" name="ufile[]" type="file" size="50" />
								<input type='button' value='<?php echo plugin_lang_get( 'Add_another_file' )?>' onClick="javascript:insert_row_file('table_files','ufile[]',document.getElementById('ufile[]').value, document.getElementById('ufile[]').size)"/>
								</tr></td>
								</tbody>
							</table>
				
						<table name='table_files' id='table_files' ><thead></thead><tbody valign="bottom"></tbody></table>
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

		<td class="center"><input type='submit' name='button_ok' value='<?php  echo plugin_lang_get('save');?>' onclick='validar();'>&#160&#160&#160
		<input type='button' name='button_cancel' value='<?php  echo plugin_lang_get('cancel');?>'  onClick="javascript:go_page(null, null,'<?php echo $t_page?>')"></td>
	</tr>

</table>
<input type='hidden' name='row_number_cursoAlternativo' id='row_number_cursoAlternativo' value='0'/>
<input type='hidden' name='row_number_file' id='row_number_file' value='1'/>
<input type='hidden' name='row_number_uc_actor' id='row_number_uc_actor' value='<?php echo $count_all_actors ?>'/>
<input type='hidden' name='row_number_uc_rule' id='row_number_uc_rule' value='<?php echo $count_rules ?>'/>
<input type='hidden' name='row_number_uc_extends' id='row_number_uc_extends' value='<?php echo $count_extends ?>'/>
<input type='hidden' name='row_number_uc_includes' id='row_number_uc_includes' value='<?php echo $count_includes ?>'/>
<input type="hidden" name="operation" id="operation" value="1"/>
</div>
</form>


<?php

html_page_bottom1( );

?>
