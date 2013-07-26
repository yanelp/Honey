<?php

	require_once( 'core.php' );

	require_once( 'file_api.php' );

	require_once( 'functions.php' );

	helper_ensure_confirmed('Are you sure you wish to delete this file?', 'Delete');

	$f_file_id = gpc_get_int( 'file_id' );

	$t_usecase_id = gpc_get_int( 'usecase_id' );

	$t_repo_table = plugin_table( 'file_usecase', 'honey' );

	$t_query_impact = 'DELETE FROM '.$t_repo_table.' WHERE  id_usecase=' . db_param() .' and id='. db_param().'';

	//echo "sql: ".$t_query_impact;
		
	$g_result_update_impact=db_query_bound( $t_query_impact, array( $t_usecase_id,$f_file_id )  );

	$t_url= plugin_page( 'usecase_page' );

	html_meta_redirect_honey( $t_url.'&id_usecase='.$t_usecase_id, $p_time =0, 'attachment');

?>