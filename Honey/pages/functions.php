<?php

function print_lel_menu( $p_page = '' ) {

	echo '<div align="center"><p>';

	if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
		print_bracket_link(  plugin_page( 'new_symbol_page' ), plugin_lang_get( 'Lel_symbol_link' ) );
	}
	if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
		print_bracket_link(  plugin_page( 'view_symbols_page' ), plugin_lang_get( 'Lel_view_symbols_link' ) );
	}


	# Plugin / Event added options
	$t_event_menu_options = event_signal( 'EVENT_MENU_MANAGE' );
	$t_menu_options = array();
	foreach( $t_event_menu_options as $t_plugin => $t_plugin_menu_options ) {
		foreach( $t_plugin_menu_options as $t_callback => $t_callback_menu_options ) {
			if( is_array( $t_callback_menu_options ) ) {
				$t_menu_options = array_merge( $t_menu_options, $t_callback_menu_options );
			} else {
				if ( !is_null( $t_callback_menu_options ) ) {
					$t_menu_options[] = $t_callback_menu_options;
				}
			}
		}
	}

	// Plugins menu items
	foreach( $t_menu_options as $t_menu_item ) {
		print_bracket_link_prepared( $t_menu_item );
	}

	echo '</p></div>';
}


function print_cu_menu( $p_page = '' ) {

	echo '<div align="center"><p>';

	if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
		print_bracket_link(  plugin_page( 'new_usecase_page' ), plugin_lang_get( 'usecase_new_link' ) );
	}
	if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
		print_bracket_link(  plugin_page( 'view_cu_page' ), plugin_lang_get( 'usecase_view_cu_link' ) );
	}
	

	# Plugin / Event added options
	$t_event_menu_options = event_signal( 'EVENT_MENU_MANAGE' );
	$t_menu_options = array();
	foreach( $t_event_menu_options as $t_plugin => $t_plugin_menu_options ) {
		foreach( $t_plugin_menu_options as $t_callback => $t_callback_menu_options ) {
			if( is_array( $t_callback_menu_options ) ) {
				$t_menu_options = array_merge( $t_menu_options, $t_callback_menu_options );
			} else {
				if ( !is_null( $t_callback_menu_options ) ) {
					$t_menu_options[] = $t_callback_menu_options;
				}
			}
		}
	}

	// Plugins menu items
	foreach( $t_menu_options as $t_menu_item ) {
		print_bracket_link_prepared( $t_menu_item );
	}

	echo '</p></div>';
}



function add_uc_note( $id_uc,$t_comment, $t_use_case_id){

$c_user_id = auth_get_current_user_id();
	
		$t_view_state = VS_PUBLIC;
		$fecha = date('Y-m-d H:i:s'); 
		$submitted=$fecha;
		$modified=$fecha;
		
		$t_note_table = plugin_table( 'uc_note', 'honey' );
		$t_usecase_table = plugin_table( 'usecase', 'honey' );

		$t_query_id_uc='select id from  '.$t_usecase_table.' where view_id= ' . db_param();
		$result_id_uc = db_query_bound( $t_query_id_uc, array($t_use_case_id) );
		$row_note = db_fetch_array( $result_id_uc );
		$id_uc=$row_note['id'];
		//$id_uc=1;

		$t_query_note = 'INSERT INTO '.$t_note_table.' (id_uc, note, reporter_id, view_state, date_submitted,last_modified  )
						 VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' )';
		$g_result_insert_note=db_query_bound( $t_query_note, array( $id_uc,$t_comment,$c_user_id,$t_view_state,$submitted,$modified)  );

	return true;
}

?>