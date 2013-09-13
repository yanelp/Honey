<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

EVENT_LAYOUT_RESOURCES;

$project_id =  helper_get_current_project();


if ( ( ALL_PROJECTS == helper_get_current_project() ) && ( 0 == $f_master_bug_id ) ) {
		print_header_redirect( 'login_select_proj_page.php?ref=plugin.php?page=Honey/new_symbol_page.php' );
	}

$t_page=plugin_page("new_symbol_page"); 

?>

<form name='form1' action="<?php echo plugin_page( "save_symbol" ); ?>" id="form1" method="post">
<div align="center">
<table class="width90">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_Honey_symbol_detail' )?>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span><?php echo plugin_lang_get('name')?></td>
		<td><input type="text" name="symbol_name" id='symbol_name' size="50"/></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get('synonymous')?></td>
		<td><input type="text" name="symbol_synonymous" id='symbol_synonymous' size="50"/>
		<input type='button' name='button_synonymous_add' value='<?php echo plugin_lang_get('add')?>' onClick="javascript:insert_row('table_synonimous','symbol_synonymous', document.getElementById('symbol_synonymous').value)"/></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">&nbsp;</td>
		<td><table  name='table_synonimous' id='table_synonimous' ><thead></thead><tbody></tbody></table></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span><?php echo plugin_lang_get('notion')?></td><td><textarea name='symbol_notion' id='symbol_notion' cols='45' rows='5'></textarea></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get('impact')?></td>
		<td><input type='text' name='symbol_impact' size="50" id='symbol_impact'/>
		<input type='button' name='button_impact_add' value='<?php echo plugin_lang_get('add')?>' onClick="javascript:insert_row('table_impacts','symbol_impact',document.getElementById('symbol_impact').value)"/></td></tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">&nbsp;</td>
		<td><table  name='table_impacts' id='table_impacts' ><thead></thead><tbody></tbody></table></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span><?php echo plugin_lang_get('type')?></td>
		<td>
			<select name='symbol_type' id='symbol_type'>
				<option value='0'>.....</option>
				<option value='1'><?php echo plugin_lang_get('subject')?></option>
				<option value='2'><?php echo plugin_lang_get('object')?></option>
				<option value='3'><?php echo plugin_lang_get('state')?></option>
				<option value='4'><?php echo plugin_lang_get('verb')?></option>
			</select>
		</td>
	</tr>

<input type='hidden' name='row_number_symbol_synonymous' id='row_number_symbol_synonymous' value='0'/>
<input type='hidden' name='row_number_symbol_impact' id='row_number_symbol_impact' value='0'/>

	<tr>
		<td class="left">
			<span class="required"> * <?php echo lang_get( 'required' ) ?></span>
		</td>
		<td class="center"><input type='submit' name='button_ok' value='<?php echo plugin_lang_get('save')?>'>
		<input type='button' name='button_cancel' value='<?php echo plugin_lang_get('cancel')?>' onClick="javascript:go_page(null, null,'<?php echo $t_page?>')"></td>
	</tr>
</table>

<input type="hidden" name="operation" id="operation" value="0"/>
</div>

</form>
<?php

html_page_bottom1( );

?>