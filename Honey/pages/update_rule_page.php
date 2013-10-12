<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

$id_rule = gpc_get_int( 'id_rule' );

if($id_rule==-1){$id_rule=$_REQUEST['id_rule'];}

//busco el símbolo

$t_repo_table = plugin_table( 'rule', 'honey' );

$query_rule = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id=' . db_param().'
				 AND active = 0';

$result = db_query_bound( $query_rule, array($id_rule) );
$count = db_num_rows( $result );
$row = db_fetch_array( $result );

if($count>0){


$name=$row['name'];
$descrip=$row['description'];


$t_page=plugin_page( "save_rule" );
$t_page=$t_page."&id_rule=".$id_rule;

$t_page_delete = plugin_page( "delete_rule_page" );
$t_page_delete= $t_page_delete."&id_rule=".$id_rule;

?>

<form name='form1' action="<?php echo $t_page; ?>" method="post" id="form1" >

<div align="center">
<table class="width90" summary="<?php echo plugin_lang_get( 'summary_modify_rule' )?>">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo plugin_lang_get( 'rule_modify' )?>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span><?php echo plugin_lang_get( 'name' )?></td>
		<td><input type="text" name="rule_name" id='rule_name' size="50" value="<?php echo $name?>"/></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get( 'col_description' )?></td><td><textarea name='rule_descrip' id='rule_descrip' cols='38' rows='5'><?php echo $descrip?></textarea></td>
	</tr>
	<tr>
		<td class="left">
			<span class="required"> * <?php echo lang_get( 'required' ) ?></span>
		</td>
		<td class="center"><input type='submit' name='button_ok' value='<?php echo plugin_lang_get( 'save' )?>'>
			<?php $t_page_back=plugin_page("view_rules_page");?>
			<input type="button" value="<?php echo plugin_lang_get( 'delete' )?>" onClick="javascript:go_page('id_rule', <?php echo $id_rule?> ,'<?php echo $t_page_delete?>')"/>&#160&#160&#160
			<input type='button' name='button_cancel' value='<?php echo plugin_lang_get( 'cancel' )?>' onClick="javascript:go_page(null,null ,'<?php echo $t_page_back?>')">
	</td>
	</tr>
</table>


<input type="hidden" name="operation" id="operation" value="1"/>
<input type="hidden" name="id_rule_hidden" id="id_rule_hidden" value="<?php echo $id_rule ?>"/>

</div>
</form>

<?php
}//if existe la regla buscada
else{echo "<p>".plugin_lang_get('rule_do_not_exist')."</p>";
echo '<br><br>';
$t_page=plugin_page("view_rules_page");
echo "<a href=\"$t_page\">". plugin_lang_get('back')."</a>";
echo "<br>";}
?>

<?php

html_page_bottom1( );

?>