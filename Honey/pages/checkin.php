#!/usr/bin/php -q
<?php

global $g_bypass_headers;
$g_bypass_headers = 1;
require_once( dirname( dirname( dirname( dirname( __FILE__ ) ))) . DIRECTORY_SEPARATOR . 'core.php' );
require_once('functions.php');

# Estado tras resolver la incidencia: resuelta
$g_source_control_set_status_to = RESOLVED;
# Resolución trasresolver la incidencia: corregida
$g_source_control_set_resolution_to = FIXED;

/**/
//$source_control_account = 'svn';

# Expresión regular a encajar en los comentarios con las incidencias
# Ejemplo: incidencia #1
//$g_source_control_regexp ='/\b(?:bug|issue|incidencia|fallo|error|problema)\s*[#]{0,1}(\d+)\b/i';


# Expresión regular a encajar para resolver una incidencia
# Ejemplo: resuelta incidencia #1
$g_source_control_fixed_regexp = '/\b(?:resuelto|resuelta|arreglado|arreglada|corregido|corregida)\s+(?:bug|issue|incidencia|fallo|error|problema)?\s*[#](\d+)\b/i';

/**/

# Make sure this script doesn't run via the webserver
if( php_sapi_name() != 'cli' ) {
	echo "checkin.php is not allowed to run through the webserver.\n";
	exit( 1 );
}

# Check that the username is set and exists
//$t_username = config_get( 'source_control_account' );
$t_username = 'svn';

if( is_blank( $t_username ) || ( user_get_id_by_name( $t_username ) === false ) ) {
	echo "Invalid source control account ('$t_username').\n";
	exit( 1 );
}

if( !defined( "STDIN" ) ) {
	define( "STDIN", fopen( 'php://stdin', 'r' ) );
}

# Detect references to issues + concat all lines to have the comment log.
//$t_commit_regexp = config_get( 'source_control_regexp' );
$t_commit_regexp ='/\b(?:bug|issue|incidencia|fallo|error|problema)\s*[#]{0,1}(\d+)\b/i';

$t_commit_regexp_honey ='/\b(?:uc|cu|caso de uso|use case)\s*[#]{0,1}(\d+)\b/i';

//$t_commit_fixed_regexp = config_get( 'source_control_fixed_regexp' );
$t_commit_fixed_regexp = '/\b(?:resuelto|resuelta|arreglado|arreglada|corregido|corregida|fixed)\s+(?:bug|issue|incidencia|fallo|error|problema)?\s*[#](\d+)\b/i';



$t_comment = '';
$t_issues = array();
$t_fixed_issues = array();
$t_use_cases = array();


while(( $t_line = fgets( STDIN, 1024 ) ) ) {
	$t_comment .= $t_line;
	if( preg_match_all( $t_commit_regexp, $t_line, $t_matches ) ) {
		$t_count = count( $t_matches[0] );
		for( $i = 0;$i < $t_count;++$i ) {
			$t_issues[] = $t_matches[1][$i];
		}
	}

	if( preg_match_all( $t_commit_fixed_regexp, $t_line, $t_matches ) ) {
		$t_count = count( $t_matches[0] );
		for( $i = 0;$i < $t_count;++$i ) {
			$t_fixed_issues[] = $t_matches[1][$i];
		}
	}

	if( preg_match_all( $t_commit_regexp_honey, $t_line, $t_matches ) ) {
		$t_count = count( $t_matches[0] );
		for( $i = 0;$i < $t_count;++$i ) {
			$t_use_cases[] = $t_matches[1][$i];
		}
	}

}

# If no issues found, then no work to do.
if(( count( $t_issues ) == 0 ) && ( count( $t_fixed_issues ) == 0 ) ) {
	echo plugin_lang_get('comment_issue');
	//exit( 0 );
}
else{
	# Login as source control user
	if( !auth_attempt_script_login( $t_username ) ) {
		echo plugin_lang_get('no_login');
		exit( 1 );
	}

	# history parameters are reserved for future use.
	$t_history_old_value = '';
	$t_history_new_value = '';

	# add note to each bug only once
	$t_issues = array_unique( $t_issues );
	$t_fixed_issues = array_unique( $t_fixed_issues );

	# Call the custom function to register the checkin on each issue.

	foreach( $t_issues as $t_issue_id ) {
		if( !in_array( $t_issue_id, $t_fixed_issues ) ) {
			helper_call_custom_function( 'checkin', array( $t_issue_id, $t_comment, $t_history_old_value, $t_history_new_value, false ) );
		}
	}

	foreach( $t_fixed_issues as $t_issue_id ) {
		helper_call_custom_function( 'checkin', array( $t_issue_id, $t_comment, $t_history_old_value, $t_history_new_value, true ) );
	}

}//else hay issue


/*Comienza a armar las notas para los use cases*/


# If no use case found, then no work to do.
if(( count( $t_use_cases ) == 0 ) && ( count( $t_fixed_use_cases ) == 0 ) ) {
	echo plugin_lang_get('comment_uc');
	exit( 0 );
}


# Login as source control user
if( !auth_attempt_script_login( $t_username ) ) {
	echo plugin_lang_get('no_login');
	exit( 1 );
}


# add note to each use case only once
$t_use_cases = array_unique( $t_use_cases );
//$t_fixed_use_cases = array_unique( $t_fixed_use_cases );

# Call the custom function to register the checkin on each use case.

foreach( $t_use_cases as $t_use_case_id ) {
	if( !in_array( $t_use_case_id, $t_fixed_use_cases ) ) {

		//en vez de llamar a checkin llamamos a nuestra funcion add_uc_comment() que inserte en honey_uc_notes

		add_uc_note( $t_use_case_id,$t_comment );

	}
}


exit( 0 );
