<?php

require_once('functions.php');
require_once( 'core.php' );


html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();


$project_id =  helper_get_current_project();


if ( ( ALL_PROJECTS == helper_get_current_project() ) && ( 0 == $f_master_bug_id ) ) {
		print_header_redirect( 'login_select_proj_page.php?ref=plugin.php?page=Honey/new_actor_page.php' );
	}


$t_page=plugin_page("new_actor_page");


EVENT_LAYOUT_RESOURCES
?>


<form name='form1' action="<?php echo plugin_page( "save_actor" ); ?>" id="form1" method="post">
<div align="center">
<table class="width90">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_Honey_actor_detail' )?>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category"><span class="required">*</span>Name</td>
		<td><input type="text" name="actor_name" id='actor_name' size="59"/></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Description</td><td><textarea name='actor_descrip' id='actor_descrip' cols='45' rows='5'></textarea></td>
	</tr>
	<tr>
		<td class="left">
			<span class="required"> * <?php echo lang_get( 'required' ) ?></span>
		</td>
		<td class="center"><input type='submit' name='button_ok' value='Save'>
		<input type='button' name='button_cancel' value='Cancel' onClick="javascript:go_page(null, null,'<?php echo $t_page?>')"></td>
	</tr>
</table>

<input type="hidden" name="operation" id="operation" value="0"/>
</div>

</form>
<?php

html_page_bottom1( );

?>


