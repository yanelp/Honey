<?php

require_once('print_lel_menu.php');

html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

EVENT_LAYOUT_RESOURCES

?>




<form name='form1' action="<?php echo plugin_page( "save_symbol" ); ?>" method="post">
<div align="center">
<table class="width90">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_Honey_symbol_detail' )?>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span>Name</td>
		<td><input type="text" name="symbol_name" id='symbol_name' size="50"/></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Synonymous</td>
		<td><input type="text" name="symbol_synonymous" id='symbol_synonymous' size="50"/>
		<input type='button' name='button_synonymous_add' value='add' onClick="javascript:insert_row('table_synonimous','symbol_synonymous', document.getElementById('symbol_synonymous').value)"/></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">&nbsp;</td>
		<td><table  name='table_synonimous' id='table_synonimous' ><thead></thead><tbody></tbody></table></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span>Notion</td><td><textarea name='symbol_notion' id='symbol_notion' cols='45' rows='5'></textarea></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Impact</td>
		<td><input type='text' name='symbol_impact' size="50" id='symbol_impact'/>
		<input type='button' name='button_impact_add' value='add' onClick="javascript:insert_row('table_impacts','symbol_impact',document.getElementById('symbol_impact').value)"/></td></tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">&nbsp;</td>
		<td><table  name='table_impacts' id='table_impacts' ><thead></thead><tbody></tbody></table></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span>Type</td>
		<td>
			<select name='symbol_type' id='symbol_type'>
				<option value='0'>.....</option>
				<option value='1'>Subject</option>
				<option value='2'>Object</option>
				<option value='3'>State</option>
				<option value='4'>Verb</option>
			</select>
		</td>
	</tr>

<input type='hidden' name='row_number_symbol_synonymous' id='row_number_symbol_synonymous' value='0'/>
<input type='hidden' name='row_number_symbol_impact' id='row_number_symbol_impact' value='0'/>



	<tr>
		<td class="left">
			<span class="required"> * <?php echo lang_get( 'required' ) ?></span>
		</td>
		<td class="center"><input type='submit' name='button_ok' value='Save'>
		<input type='button' name='button_cancel' value='Cancel' onClick="javascript:clean_symbol()"></td>
	</tr>
</table>

<input type="hidden" name="operation" id="operation" value="0"/>
</div>

</form>
<?

html_page_bottom1( );

?>