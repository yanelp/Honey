<?php
	require_once( 'core.php' );
	require_once( 'bug_api.php' );
	require_once( 'bugnote_api.php' );
	require_once( 'string_api.php' );
	require_once( 'current_user_api.php' );

	$f_uc_note_id = gpc_get_int( 'uc_note_id' );
	$id_usecase=  gpc_get_int( 'id_usecase' );
	//$t_bug_id = bugnote_get_field( $f_bugnote_id, 'bug_id' );

	/*$t_bug = bug_get( $t_bug_id, true );
	if( $t_bug->project_id != helper_get_current_project() ) {
		# in case the current project is not the same project of the bug we are viewing...
		# ... override the current project. This to avoid problems with categories and handlers lists etc.
		$g_project_override = $t_bug->project_id;
	}*/

	# Check if the current user is allowed to edit the bugnote
	/*$t_user_id = auth_get_current_user_id();
	$t_reporter_id = bugnote_get_field( $f_bugnote_id, 'reporter_id' );

	if ( ( $t_user_id != $t_reporter_id ) ||
	 	( OFF == config_get( 'bugnote_allow_user_edit_delete' ) ) ) {
		access_ensure_bugnote_level( config_get( 'update_bugnote_threshold' ), $f_bugnote_id );
	}

	# Check if the bug is readonly
	if ( bug_is_readonly( $t_bug_id ) ) {
		error_parameters( $t_bug_id );
		trigger_error( ERROR_BUG_READ_ONLY_ACTION_DENIED, ERROR );
	}*/

	//$t_bugnote_text = string_textarea( bugnote_get_text( $f_uc_note_id ) );

	//busco la nota seleccionada del caso de uso

	$t_repo_table = plugin_table( 'uc_note', 'honey' );

	$query_note = 'SELECT * 
					 FROM '.$t_repo_table.' 
					 where id=' . db_param();

	$result = db_query_bound( $query_note, array($f_uc_note_id) );
	$count = db_num_rows( $result );
	$row = db_fetch_array( $result );

	$uc_note_text=$row['note'];


	# No need to gather the extra information if not used
	/*if ( config_get('time_tracking_enabled') &&
		access_has_bug_level( config_get( 'time_tracking_edit_threshold' ), $t_bug_id ) ) {
		$t_time_tracking = bugnote_get_field( $f_bugnote_id, "time_tracking" );
		$t_time_tracking = db_minutes_to_hhmm( $t_time_tracking );
	}*/

	# Determine which view page to redirect back to.
	//$t_redirect_url = string_get_bug_view_url( $t_bug_id );
	$t_page= plugin_page( 'usecase_page' );	;
	$t_redirect_url=$t_page."&id_usecase=".$id_usecase;

html_page_top( plugin_lang_get( 'title' ) );

//print_uc_menu();

?>
<br />
<div align="center">
<form name='form1' action="<?php echo plugin_page( "uc_note_update" ); ?>" method="post">
<table class="width75" cellspacing="1">
<tr>
	<td class="form-title">
		<input type="hidden" name="uc_note_id" value="<?php echo $f_uc_note_id ?>" />
		<input type="hidden" name="usecase_id" value="<?php echo $id_usecase ?>" />
		<?php echo "Edit usecase note" ?>
	</td>
	<td class="right">
		<?php print_bracket_link( $t_redirect_url, lang_get( 'go_back' ) ) ?>
	</td>
</tr>
<tr class="row-1">
	<td class="center" colspan="2">
		<textarea cols="80" rows="10" name="note_text"><?php echo $uc_note_text ?></textarea>
	</td>
</tr>


<tr>
	<td class="center" colspan="2">
		<input type="submit" class="button" value="<?php echo "Update" ?>" />
	</td>
</tr>
</table>
</form>
</div>

<?php html_page_bottom();
