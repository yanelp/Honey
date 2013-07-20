<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

$id_actor = gpc_get_int( 'id_actor' );

//busco el símbolo

$t_repo_table = plugin_table( 'actor', 'honey' );

$query_actor = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id=' . db_param().'
				 AND active = 0';

$result = db_query_bound( $query_actor, array($id_actor) );
$count = db_num_rows( $result );
$row = db_fetch_array( $result );

$name=$row['name'];
$descrip=$row['description'];


$t_page=plugin_page( "save_actor" );
$t_page=$t_page."&id_actor=".$id_actor;

?>

<form name='form1' action="<?php echo $t_page; ?>" method="post" id="form1" >

<div align="center">
<table class="width90">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_Honey_actor_modify' )?>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span>Name</td>
		<td><input type="text" name="actor_name" id='actor_name' size="50" value="<?php echo $name?>"/></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Description</td><td><textarea name='actor_descrip' id='actor_descrip' cols='37' rows='5'><?php echo $descrip?></textarea></td>
	</tr>
	<tr>
		<td class="left">
			<span class="required"> * <?php echo lang_get( 'required' ) ?></span>
		</td>
		<td class="center"><input type='submit' name='button_ok' value='Save'>
			<?php $t_page_back=plugin_page("view_actors_page");?>
			<input type='button' name='button_cancel' value='Cancel' onClick="javascript:go_page(null,null ,'<?php echo $t_page_back?>')">
		</td>
	</tr>
</table>


<input type="hidden" name="operation" id="operation" value="1"/>
<input type="hidden" name="id_actor_hidden" id="id_actor_hidden" value="<?php echo $id_actor ?>"/>

</div>
</form>
<?php

html_page_bottom1( );

?>