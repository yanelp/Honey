<?php

require_once('print_lel_menu.php');

html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

EVENT_LAYOUT_RESOURCES;

$id_symbol = gpc_get_int( 'id_symbol' );

//busco el símbolo

$t_repo_table = plugin_table( 'symbol', 'honey' );

$query_symbol = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id=' . db_param();

$result = db_query_bound( $query_symbol, array($id_symbol) );
$count = db_num_rows( $result );
$row = db_fetch_array( $result );

$name=$row['name'];
$type=$row['type'];
$notion=$row['notion'];

/*****************/

//busco los impactos

$t_repo_table = plugin_table( 'impact', 'honey' );

$query_impacts = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id_symbol=' . db_param();

$result_impacts = db_query_bound( $query_impacts, array($id_symbol) );
$count_impacts = db_num_rows( $result_impacts );
//echo count_impacts;


/*****************/

//busco los simónimos

$t_repo_table = plugin_table( 'synonymous', 'honey' );

$query_synonymous = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id_symbol=' . db_param();

$result_synonymous = db_query_bound( $query_synonymous, array($id_symbol) );
$count_synonymous = db_num_rows( $result_synonymous );

//echo "cant: ".$count_synonymous;

$t_page=plugin_page( "save_symbol" );
$t_page=$t_page."&id_symbol=".$id_symbol;

?>

<form name='form1' action="<?php echo $t_page; ?>" method="post">

<div align="center">
<table class="width90">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_Honey_symbol_modify' )?>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span>Name</td>
		<td><input type="text" name="symbol_name" id='symbol_name' size="50" value="<?php echo $name?>"/></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Synonymous</td>
		<td><input type="text" name="symbol_synonymous" id='symbol_synonymous' size="50"/>
			<input type='button' name='button_synonymous_add' value='add' onClick="javascript:insert_row('table_synonimous','symbol_synonymous', document.getElementById('symbol_synonymous').value)"/></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">&nbsp;</td><td><table  name='table_synonimous' id='table_synonimous' ><thead></thead><tbody></tbody></table></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span>Notion</td><td><textarea name='symbol_notion' id='symbol_notion' cols='37' rows='5'><?php echo $notion?></textarea></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Impact</td><td><input type='text' name='symbol_impact' size="50" id='symbol_impact'/>
		<input type='button' name='button_impact_add' value='add' onClick="javascript:insert_row('table_impacts','symbol_impact',document.getElementById('symbol_impact').value)"/></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">&nbsp;</td><td><table  name='table_impacts' id='table_impacts' ><thead></thead><tbody></tbody></table></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span>Type</td><td>
			<select name='symbol_type' id='symbol_type'>
				<option value='0'>.....</option>
				<option value='1'>Subject</option>
				<option value='2'>Object</option>
				<option value='3'>State</option>
				<option value='4'>Verb</option>
			</select>
	</tr>

<input type='hidden' name='row_number_symbol_synonymous' id='row_number_symbol_synonymous' value='0'/>
<input type='hidden' name='row_number_symbol_impact' id='row_number_symbol_impact' value='0'/>
<tr>
	<td class="left">
		<span class="required"> * <?php echo lang_get( 'required' ) ?></span>
	</td>
	<td class="center"><input type='submit' name='button_ok' value='Save'>
		<input type='submit' name='button_cancel' value='Cancel' onClick="javascript:document.form1.action='<?php echo plugin_page( "view_symbols_page" );?>'"></td>
</tr>
</table>

<?php while( $row_synonymous = db_fetch_array( $result_synonymous) ){
	$row= $row_synonymous['name'];
	?>
	
	<script>
	insert_row('table_synonimous','symbol_synonymous','<?php echo $row; ?>');
	</script>
<?php } ?>

<?php while( $row_impacts = db_fetch_array( $result_impacts) ){
	$row= $row_impacts['description'];
	?>
	
	<script>
	insert_row('table_impacts','symbol_impact','<?php echo $row; ?>');
	</script>
<?php } ?>

<input type="hidden" name="operation" id="operation" value="1"/>
<input type="hidden" name="id_symbol_hidden" id="id_symbol_hidden" value="<?php echo $id_symbol ?>"/>

</div>

<script>
document.getElementById('symbol_type').value=<?php echo $type?>;
</script>
</form>
<?php

html_page_bottom1( );

?>