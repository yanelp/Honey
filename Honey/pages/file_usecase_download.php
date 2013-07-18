<?php

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
	$f_type = gpc_get_string( 'type' );

	$c_file_id = (integer)$f_file_id;

	$t_bug_file_table =plugin_table( 'file_usecase', 'honey' );
	$query = "SELECT *
				FROM $t_bug_file_table
				WHERE id=" . db_param();
			
	$result = db_query_bound( $query, Array( $c_file_id ) );
	$row = db_fetch_array( $result );
	extract( $row, EXTR_PREFIX_ALL, 'v' );

	$t_project_id = helper_get_current_project();
	

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

	header( 'Content-Length: ' . $v_filesize );

	# If finfo is available (always true for PHP >= 5.3.0) we can use it to determine the MIME type of files
	$finfo = finfo_get_if_available();

	$t_content_type = $v_file_type;

	$t_content_type_override = file_get_content_type_override ( $t_filename );

	# dump file content to the connection.
	switch ( config_get( 'file_upload_method' ) ) {
		case DISK:
			$t_local_disk_file = file_normalize_attachment_path( $v_diskfile, $t_project_id );

			if ( file_exists( $t_local_disk_file ) ) {
				if ( $finfo ) {
					$t_file_info_type = $finfo->file( $t_local_disk_file );

					if ( $t_file_info_type !== false ) {
						$t_content_type = $t_file_info_type;
					}
				}

				if ( $t_content_type_override ) {
					$t_content_type = $t_content_type_override;
				}

				header( 'Content-Type: ' . $t_content_type );
				readfile( $t_local_disk_file );
			}
			break;
		case FTP:
			$t_local_disk_file = file_normalize_attachment_path( $v_diskfile, $t_project_id );

			if ( !file_exists( $t_local_disk_file ) ) {
				$ftp = file_ftp_connect();
				file_ftp_get ( $ftp, $t_local_disk_file, $v_diskfile );
				file_ftp_disconnect( $ftp );
			}

			if ( $finfo ) {
				$t_file_info_type = $finfo->file( $t_local_disk_file );

				if ( $t_file_info_type !== false ) {
					$t_content_type = $t_file_info_type;
				}
			}

			if ( $t_content_type_override ) {
				$t_content_type = $t_content_type_override;
			}

			header( 'Content-Type: ' . $t_content_type );
			readfile( $t_local_disk_file );
			break;
		default:
			if ( $finfo ) {
				$t_file_info_type = $finfo->buffer( $v_content );

				if ( $t_file_info_type !== false ) {
					$t_content_type = $t_file_info_type;
				}
			}

			if ( $t_content_type_override ) {
				$t_content_type = $t_content_type_override;
			}

			header( 'Content-Type: ' . $t_content_type );
			echo $v_content;
	}
