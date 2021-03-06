<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu('edit_actor');

EVENT_LAYOUT_RESOURCES;

$id_actor = gpc_get_int( 'actor_id' );
if($id_actor==-1){$id_actor=$_REQUEST['actor_id'];}

//busco el s�mbolo

$t_repo_table = plugin_table( 'actor', 'honey' );

$query_actor = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id=' . db_param().'
				 AND active = 0';

$result = db_query_bound( $query_actor, array($id_actor) );
$count = db_num_rows( $result );
$row = db_fetch_array( $result );

if($count>0){

$name=$row['name'];
$descrip=$row['description'];


$t_page=plugin_page( "save_actor" );
$t_page=$t_page."&id_actor=".$id_actor;

$t_page_delete = plugin_page( "delete_actor_page" );
//$t_page_delete= $t_page_delete."&id_rule=".$id_rule;


?>

<form name='form1' action="<?php echo $t_page; ?>" method="post" id="form1" >

<div align="center">
<table class="width90" summary="<?php echo plugin_lang_get( 'summary_modify_actor' )?>">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo plugin_lang_get( 'actor_modify' )?>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span><?php echo plugin_lang_get( 'name' )?></td>
		<td><input type="text" name="actor_name" id='actor_name' size="50" value="<?php echo $name?>" title="<?php echo plugin_lang_get('title_actor_name');?>"/></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><?php echo plugin_lang_get( 'col_description' )?></td><td><textarea name='actor_descrip' id='actor_descrip' cols='38' rows='5' title="<?php echo plugin_lang_get('title_actor_description');?>"><?php echo $descrip?></textarea></td>
	</tr>
	<tr>
		<td class="left">
			<span class="required"> * <?php echo lang_get( 'required' ) ?></span>
		</td>
		<td class="center"><input type='submit' name='button_ok' value='<?php echo plugin_lang_get( 'save' )?>'>
			<?php $t_page_back=plugin_page("view_actors_page");?>
			<input type="button" value="<?php echo plugin_lang_get( 'delete' )?>" onClick="javascript:go_page('null', null ,'<?php echo $t_page_delete?>')"/>&#160&#160&#160
			<input type='button' name='button_cancel' value='<?php echo plugin_lang_get( 'cancel' )?>' onClick="javascript:go_page(null,null ,'<?php echo $t_page_back?>')">
		</td>
	</tr>
</table>


<input type="hidden" name="operation" id="operation" value="1"/>
<input type="hidden" name="id_actor_hidden" id="id_actor_hidden" value="<?php echo $id_actor ?>"/>

</div>
</form>

<?php
}//if existe el actor buscado
else{?>

<div align='center'>
	<?php showMessage(plugin_lang_get('actor_do_not_exist'), 'error')?>
	</table>
	</div>
	<?php
	echo '<br><br>';
	$t_page=plugin_page("view_actors_page");
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