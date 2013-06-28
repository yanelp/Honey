<?php

require_once('functions.php');
require_once( 'core.php' );
require_once('manage_sequences.php');


html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();


$project_id =  helper_get_current_project();


if ( ( ALL_PROJECTS == helper_get_current_project() ) && ( 0 == $f_master_bug_id ) ) {
		print_header_redirect( 'login_select_proj_page.php?ref=plugin.php?page=Honey/new_usecase_page.php' );
	}

//Generación de la identificación del CU visible por el usuario

$nextId = getNextSeq("usecase_secuence");
$view_id = "CU_".$nextId;


EVENT_LAYOUT_RESOURCES
?>



<div align="center">
<form name="new_usecase_form"  id="new_usecase_form"  method="post" onsubmit="javascript:validar()" action="<?php echo plugin_page( "save_usecase" ); ?>">
<table  class="width90" cellspacing="1">
	<tr>
		<td class="form-title" colspan="2">
			<input type="hidden" name="project_id" value="<?php echo $t_project_id ?>" />
			<?php echo lang_get( 'plugin_Honey_usecase_detail' )." ".$view_id ?>
	        <input type="hidden" name="view_id" value="<?php echo $view_id ?>" />
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		   <td class="category">
		   <span class="required">*</span>Nombre del caso de uso
		</td>
		<td>
	      <input type="text" name="cu_name" id='cu_name' size="133">
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		 <td class="category">
		    Objetivo
		</td>
		<td>
		   <Textarea cols="100" name="goal" id="goal" form="new_usecase_form""></Textarea>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		 <td class="category">
		    Actores Involucrados
		</td>
		<td><input type='text' name='uc_actor' id='uc_actor'size="133"/>
			<input type='button' name='button_actor_add' value='Agregar Actor' onClick="javascript:insert_row('table_actors','uc_actor',document.getElementById('uc_actor').value)"/>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">&nbsp;</td>
		<td><table name='table_actors' id='table_actors' ><thead></thead><tbody></tbody></table></td>
	 </tr>
	<tr <?php echo helper_alternate_class() ?>>
		 <td class="category">
		    Pre-condiciones
		</td>
		<td>
		  <Textarea cols="100"  name="preconditions" id="preconditions" form="new_usecase_form"></Textarea>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		 <td class="category">
		    Post-condiciones
		</td>
		<td>
	     <Textarea cols="100" name="postconditions" id="postconditions" form="new_usecase_form"></Textarea>
		</td>
	 </tr>
     <tr <?php echo helper_alternate_class() ?>>
		 <td class="category">
		    Observaciones
		</td>
		<td>
	      <Textarea cols="100" name="obsevations" id="obsevations" form="new_usecase_form"></Textarea>
		</td>
	  </tr>
	  <tr <?php echo helper_alternate_class() ?>>
		 <td class="category">
		 <span class="required">*</span>Curso Normal
		</td>
		<td>
	      <Textarea cols="100" rows="15" name="cursoNormal" id="cursoNormal" form="new_usecase_form"></Textarea>
		</td>
	  </tr>

<tr>
		<td class="left">
			<span class="required"> * <?php echo lang_get( 'required' ) ?></span>
		</td>

	<td class="center"><input type='submit' name='button_ok' value='Save' onclick='validar();'>
	<input type='button' name='button_cancel' value='Cancel' onClick="javascript:clean_symbol()"></td>
	</tr>

</table>

<input type='hidden' name='row_number_usecas_actors' id='row_number_usecas_actors' value='0'/>
<input type="hidden" name="operation" id="operation" value="1"/>
</div>
</form>


<?php

html_page_bottom1( );

?>
