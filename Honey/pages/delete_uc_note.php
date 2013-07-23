<?php
	require_once( 'current_user_api.php' );
	require_once('functions.php');

	//form_security_validate( 'bugnote_delete' );

	$f_uc_note_id = gpc_get_int( 'uc_note_id' );

	$t_uc_id = gpc_get_int( 'id_usecase' );

	/*$t_bug = bug_get( $t_bug_id, true );
	if( $t_bug->project_id != helper_get_current_project() ) {
		# in case the current project is not the same project of the bug we are viewing...
		# ... override the current project. This to avoid problems with categories and handlers lists etc.
		$g_project_override = $t_bug->project_id;
	}

	# Check if the current user is allowed to delete the bugnote
	$t_user_id = auth_get_current_user_id();
	$t_reporter_id = bugnote_get_field( $f_bugnote_id, 'reporter_id' );

	if ( ( $t_user_id != $t_reporter_id ) || ( OFF == config_get( 'bugnote_allow_user_edit_delete' ) ) ) {
		access_ensure_bugnote_level( config_get( 'delete_bugnote_threshold' ), $f_bugnote_id );
	}
*/
	helper_ensure_confirmed( lang_get( 'delete_bugnote_sure_msg' ), 'Delete');

	delete_uc_note( $f_uc_note_id );


	# Event integration
	/*event_signal( 'EVENT_BUGNOTE_DELETED', array( $t_bug_id, $f_bugnote_id ) );

	form_security_purge( 'bugnote_delete' );*/
	$page='usecase_page';
	print_successful_redirect_honey(  $t_uc_id  , $page );
