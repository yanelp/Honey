<?php
function print_lel_menu( $p_page = '' ) {
	$t_manage_user_page = 'new_subject_page.php';
	$t_manage_project_menu_page = 'new_object_page.php';
	$t_manage_custom_field_page = 'new_state_page.php';
	$t_manage_plugin_page = 'new_verb_page.php';

	switch( $p_page ) {
		case $t_manage_user_page:
			$t_manage_user_page = '';
			break;
		case $t_manage_project_menu_page:
			$t_manage_project_menu_page = '';
			break;
		case $t_manage_custom_field_page:
			$t_manage_custom_field_page = '';
			break;
		case $t_manage_config_page:
			$t_manage_config_page = '';
			break;
		case $t_manage_plugin_page:
			$t_manage_plugin_page = '';
			break;
		case $t_manage_prof_menu_page:
			$t_manage_prof_menu_page = '';
			break;
		case $t_manage_tags_page:
			$t_manage_tags_page = '';
			break;
	}

	echo '<div align="center"><p>';

	if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
		print_bracket_link(  plugin_page( 'new_symbol_page' ), plugin_lang_get( 'Lel_symbol_link' ) );
	}
	if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
		print_bracket_link(  plugin_page( 'view_symbols_page' ), plugin_lang_get( 'Lel_view_symbols_link' ) );
	}
	/*if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
		print_bracket_link(  plugin_page( 'new_subject_page' ), plugin_lang_get( 'Lel_subject_link' ) );
	}
	if( access_has_project_level( config_get( 'manage_project_threshold' ) ) ) {
		print_bracket_link( helper_mantis_url( 'new_object_page.php' ), plugin_lang_get( 'Lel_object_link' ) );
	}
	if( access_has_global_level( config_get( 'tag_edit_threshold' ) ) ) {
		print_bracket_link( helper_mantis_url( 'new_state_page.php' ), plugin_lang_get( 'Lel_state_link' ) );
	}
	if( access_has_global_level( config_get( 'manage_custom_fields_threshold' ) ) ) {
		print_bracket_link( helper_mantis_url( 'new_verb_page.php' ), plugin_lang_get( 'Lel_verb_link' ) );
	}*/


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
