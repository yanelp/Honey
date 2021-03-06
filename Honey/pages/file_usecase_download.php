<?php
# MantisBT - a php based bugtracking system

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

	/**
	 * Add file and redirect to the referring page
	 * @package MantisBT
	 * @copyright Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
	 * @copyright Copyright (C) 2002 - 2012  MantisBT Team - mantisbt-dev@lists.sourceforge.net
	 * @link http://www.mantisbt.org
	 */

	$g_bypass_headers = true; # suppress headers as we will send our own later
	define( 'COMPRESSION_DISABLED', true );
	 /**
	  * MantisBT Core API's
	  */
	require_once( 'core.php' );

	require_once( 'file_api.php' );

	auth_ensure_user_authenticated();

	$f_show_inline = gpc_get_bool( 'show_inline', false );

	# To prevent cross-domain inline hotlinking to attachments we require a CSRF
	# token from the user to show any attachment inline within the browser.
	# Without this security in place a malicious user could upload a HTML file
	# attachment and direct a user to file_download.php?file_id=X&type=bug&show_inline=1
	# and the malicious HTML content would be rendered in the user's browser,
	# violating cross-domain security.
	if ( $f_show_inline ) {
		# Disable errors for form_security_validate as we need to send HTTP
		# headers prior to raising an error (the error handler within
		# error_api.php doesn't check that headers have been sent, it just
		# makes the assumption that they've been sent already).
		if ( !@form_security_validate( 'file_show_inline' ) ) {
			http_all_headers();
			trigger_error( ERROR_FORM_TOKEN_INVALID, ERROR );
		}
	}

	$f_file_id = gpc_get_int( 'file_id' );
	//$f_type = gpc_get_string( 'type' );

	$c_file_id = (integer)$f_file_id;

	# we handle the case where the file is attached to a bug
	# or attached to a project as a project doc.
	$query = '';
	
	$t_bug_file_table = plugin_table( 'file_usecase', 'honey' );
	$query = "SELECT *
				FROM $t_bug_file_table
				WHERE id=" . db_param();
			
	$result = db_query_bound( $query, Array( $c_file_id ) );
	$row = db_fetch_array( $result );
	extract( $row, EXTR_PREFIX_ALL, 'v' );


	$t_project_id = $v_project_id;



	# throw away output buffer contents (and disable it) to protect download
	while ( @ob_end_clean() );

	if ( ini_get( 'zlib.output_compression' ) && function_exists( 'ini_set' ) ) {
		ini_set( 'zlib.output_compression', false );
	}

	http_security_headers();

	# Make sure that IE can download the attachments under https.
	header( 'Pragma: public' );

	# To fix an IE bug which causes problems when downloading
	# attached files via HTTPS, we disable the "Pragma: no-cache"
	# command when IE is used over HTTPS.
	global $g_allow_file_cache;
	if ( http_is_protocol_https() && is_browser_internet_explorer() ) {
		# Suppress "Pragma: no-cache" header.
	} else {
		if ( !isset( $g_allow_file_cache ) ) {
		    header( 'Pragma: no-cache' );
		}
	}
	header( 'Expires: ' . gmdate( 'D, d M Y H:i:s \G\M\T', time() ) );

	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s \G\M\T', $v_date_added ) );

	$t_filename = file_get_display_name( $v_filename );
	# For Internet Explorer 8 as per http://blogs.msdn.com/ie/archive/2008/07/02/ie8-security-part-v-comprehensive-protection.aspx
	# Don't let IE second guess our content-type!
	header( 'X-Content-Type-Options: nosniff' );

	http_content_disposition_header( $t_filename, $f_show_inline );


	# If finfo is available (always true for PHP >= 5.3.0) we can use it to determine the MIME type of files
	$finfo = finfo_get_if_available();

	$t_content_type = $v_file_type;

	$t_content_type_override = file_get_content_type_override ( $t_filename );

	

	echo $v_content;
