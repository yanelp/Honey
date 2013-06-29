<?php

	/*require_once( 'core.php' );

	require_once( 'bug_api.php' );
	require_once( 'bugnote_api.php' );*/

	require_once( 'functions.php' );

	//form_security_validate( 'bugnote_set_view_state' );

	$f_uc_note_id	= gpc_get_int( 'uc_note_id' );
	$f_private		= gpc_get_bool( 'private' );
	$t_uc_id		= gpc_get_int( 'id_usecase' );

	/*$t_bug_id = bugnote_get_field( $f_bugnote_id, 'bug_id' );

	$t_bug = bug_get( $t_bug_id, true );
	if( $t_bug->project_id != helper_get_current_project() ) {
		# in case the current project is not the same project of the bug we are viewing...
		# ... override the current project. This to avoid problems with categories and handlers lists etc.
		$g_project_override = $t_bug->project_id;
	}

	$t_user_id = bugnote_get_field( $f_bugnote_id, 'reporter_id' );

	# allow either the bugnote author or a privileged user to change view status
	if ( $t_user_id != auth_get_current_user_id() ) {
		access_ensure_bugnote_level( config_get( 'update_bugnote_threshold' ), $f_bugnote_id );
		access_ensure_bugnote_level( config_get( 'change_view_status_threshold' ), $f_bugnote_id );
	}

	# Check if the bug is readonly
	$t_bug_id = bugnote_get_field( $f_bugnote_id, 'bug_id' );
	if ( bug_is_readonly( $t_bug_id ) ) {
		error_parameters( $t_bug_id );
		trigger_error( ERROR_BUG_READ_ONLY_ACTION_DENIED, ERROR );
	}*/

	set_view_state_uc_note( $f_uc_note_id );

	//form_security_purge( 'bugnote_set_view_state' );

	//print_successful_redirect_honey( string_get_bug_view_url( $t_bug_id ) . '#bugnotes' );

	$page='usecase_page';
	print_successful_redirect_honey(  $t_uc_id  , $page );
