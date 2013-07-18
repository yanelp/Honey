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
	if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
		print_bracket_link(  plugin_page( 'new_rule_page' ), plugin_lang_get( 'new_rule_link' ) );
	}
	if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
		print_bracket_link(  plugin_page( 'view_rules_page' ), plugin_lang_get( 'view_rule_link' ) );
	}
	if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
		print_bracket_link(  plugin_page( 'new_actor_page' ), plugin_lang_get( 'new_actor_link' ) );
	}
	if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
		print_bracket_link(  plugin_page( 'view_actors_page' ), plugin_lang_get( 'view_actor_link' ) );
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



function add_uc_note( $id_uc,$t_comment){

$c_user_id = auth_get_current_user_id();
	
		$t_view_state = VS_PRIVATE;
		$fecha = date('Y-m-d H:i:s'); 
		$submitted=$fecha;
		$modified=$fecha;
		
		$t_note_table = plugin_table( 'uc_note', 'honey' );
		$t_usecase_table = plugin_table( 'usecase', 'honey' );

		/*$t_query_id_uc='select id from  '.$t_usecase_table.' where view_id= ' . db_param();
		$result_id_uc = db_query_bound( $t_query_id_uc, array($t_use_case_id) );
		$row_note = db_fetch_array( $result_id_uc );
		$id_uc=$row_note['id'];
		//$id_uc=1;*/

		$t_query_note = 'INSERT INTO '.$t_note_table.' (id_uc, note, reporter_id, view_state, date_submitted,last_modified  )
						 VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' )';
		$g_result_insert_note=db_query_bound( $t_query_note, array( $id_uc,$t_comment,$c_user_id,$t_view_state,$submitted,$modified)  );

	return true;
}

function set_view_state_uc_note($id_note){

	//busco el estado actual de la NOTA
	
	$t_repo_table = plugin_table( 'uc_note', 'honey' );

	$t_query_state = 'select view_state from '.$t_repo_table.'  WHERE  id=' . db_param();
		
	$g_result_state=db_query_bound( $t_query_state, array($id_note)  );

	$row_state = db_fetch_array( $g_result_state );


	$t_view_state = $row_state['view_state'];

	if($t_view_state==VS_PRIVATE){$t_view_state=VS_PUBLIC;}
	else {$t_view_state=VS_PRIVATE;}
	

	$t_query_impact = 'update '.$t_repo_table.' set view_state='.db_param().' WHERE  id=' . db_param();
		
	$g_result_update_impact=db_query_bound( $t_query_impact, array( $t_view_state, $id_note)  );

  return true;
}

function string_convert_uc_link($p_string) {


	$words = preg_split("/[\s]+/", $p_string);
	$num_words=sizeof($words);
	$t_page= plugin_page( 'usecase_page' );
	$cant_links=0;
	
	for($r=0;$r<$num_words;$r++){

		$palabra=str_replace('#', '', $words[$r]) ;
		//echo "*".$palabra;
		//busco en la tabla de casos de uso si la palabra es un id_view

		$t_repo_table = plugin_table( 'usecase', 'honey' );

		$query_note = 'SELECT id 
						 FROM '.$t_repo_table.' 
						 where view_id=trim(' . db_param().')';

		$result_note = db_query_bound( $query_note, array($palabra) );
		$count_note = db_num_rows( $result_note );
		$row_search = db_fetch_array( $result_note ) ; 

		if($count_note>0){
			$cant_links++;

			$id_uc_search=$row_search['id'];
			
			$t_page=$t_page."&id_usecase=".$id_uc_search;

			$link="<a href=\"$t_page\">".$palabra."</a>";
			$frase=str_replace($palabra, $link,$p_string);
		}
		
	}
	$frase=str_replace('#', '',$frase);
	if($cant_links==0){$frase=$p_string;}
	return $frase;
}

function delete_uc_note($id_note){
	
	$t_repo_table = plugin_table( 'uc_note', 'honey' );

	$t_query_impact = 'DELETE FROM '.$t_repo_table.' WHERE  id=' . db_param();
		
	$g_result_update_impact=db_query_bound( $t_query_impact, array( $id_note)  );
}

function string_get_uc_view_url( $p_uc_id , $page) {

	$t_page= plugin_page( $page );
	return $t_page.'&id_usecase=' . $p_uc_id;
}


function print_successful_redirect_honey($p_uc_id, $page){

	$t_use_iis = config_get( 'use_iis' );

	$t_url = string_get_uc_view_url( $p_uc_id, $page ) ;

	if( !headers_sent() ) {
		header( 'Content-Type: text/html; charset=utf-8' );

		if( ON == $t_use_iis ) {
			header( "Refresh: 0;url=$t_url" );
		} else {
			header( "Location: $t_url" . "#uc_notes" );
		}
	} else {
		trigger_error( ERROR_PAGE_REDIRECTION, ERROR );
		return false;
	}

	return true;
}

function html_meta_redirect_honey( $p_url, $p_time = null){

	if( null === $p_time ) {
		$p_time = current_user_get_pref( 'redirect_delay' );
	}

	echo "\t<meta http-equiv=\"Refresh\" content=\"$p_time;URL=$p_url\" />\n";

}

function get_symbol_type($value) {

switch ($value) {
    case 1:
        return lang_get('plugin_Honey_symbols_type_subject');
    case 2:
         return lang_get('plugin_Honey_symbols_type_object');
    case 3:
       return lang_get('plugin_Honey_symbols_type_state');
	case 4:
       return lang_get('plugin_Honey_symbols_type_verb');
}

}

function isCondition($impactText, $comparationArray){
		
	$coincide;

	$words = preg_split("/[\s]+/", $impactText);
	
	$num_words=sizeof($words);
	
	//Iteracion por cada palabra de la descripción
	for($r=0;$r<$num_words;$r++){

		$i=1;

		while($i<=$num_words){
		 //por cada palabra del impacto//5
		 $new_word='';

		 $j=$r;

			while($j<$i){//desde el ppio hasta el final
			
					$new_word=trim($new_word." ".$words[$j]);

					if (in_array($new_word, $words)==false) {//si no está y es se encuentra en el arreglo $comparationArray)
							
							array_push($words,$new_word);}

						 $j++;

						} //fin while($j<$i)

			$i++;
			} //fin while($i<=$num_words)
		
				
		} //fin for($r=0...
     
	$num_words_comparation=sizeof($comparationArray);

	for($a=0;$a<$num_words_comparation;$a++){
	    
		if (in_array($comparationArray[$a], $words)== true){

			return  true;
						}
											 
	}
    
	return false;
	}//fin function


function attach_add($id_usecase, $p_file){

	file_ensure_uploaded( $p_file );
	
	
	$t_project_id= helper_get_current_project();
	/*$p_title='';
	$p_desc = '';
	$p_user_id = null;

	if( !file_type_check( $t_file_name ) ) {
		trigger_error( ERROR_FILE_NOT_ALLOWED, ERROR );
	}

	if( !file_is_name_unique( $t_file_name, $id_usecase ) ) {
		trigger_error( ERROR_FILE_DUPLICATE, ERROR );
	}

	/*if( $p_user_id === null ) {
		$c_user_id = auth_get_current_user_id();
	} else {
		$c_user_id = (int)$p_user_id;
	}*/

	# prepare variables for insertion
	/*$c_bug_id = db_prepare_int( $p_bug_id );
	$c_project_id = db_prepare_int( $t_project_id );
	$c_file_type = db_prepare_string( $p_file['type'] );
	$c_title = db_prepare_string( $p_title );
	$c_desc = db_prepare_string( $p_desc );*/

	

$t_file_name = $p_file['name'];
$t_tmp_file = $p_file['tmp_name'];
$t_file_size = filesize( $t_tmp_file );
$c_file_type = db_prepare_string( $p_file['type'] );
$c_content = db_prepare_binary_string( fread( fopen( $t_tmp_file, 'rb' ), $t_file_size ) );
$c_new_file_name = db_prepare_string( $t_file_name );
$c_id_usecase=db_prepare_int( $id_usecase );

if( $t_project_id == ALL_PROJECTS ) {
		$t_file_path = config_get( 'absolute_path_default_upload_folder' );
	} else {
		$t_file_path = project_get_field( $t_project_id, 'file_path' );
		if( is_blank( $t_file_path ) ) {
			$t_file_path = config_get( 'absolute_path_default_upload_folder' );
		}
	}

	$t_file_hash =  $id_usecase . config_get( 'document_files_prefix' ) . '-' . $t_project_id;
	$t_unique_name = file_generate_unique_name( $t_file_hash . '-' . $t_file_name, $t_file_path );
	$t_disk_file_name = $t_file_path . $t_unique_name;
	$c_unique_name = db_prepare_string( $t_unique_name );

	
	if( 0 == $t_file_size ) {
		trigger_error( ERROR_FILE_NO_UPLOAD_FAILURE, ERROR );
	}
	$t_max_file_size = (int) min( ini_get_number( 'upload_max_filesize' ), ini_get_number( 'post_max_size' ), config_get( 'max_file_size' ) );
	if( $t_file_size > $t_max_file_size ) {
		trigger_error( ERROR_FILE_TOO_BIG, ERROR );
	}
	//$c_file_size = db_prepare_int( $t_file_size );


$t_method = config_get( 'file_upload_method' );

	switch( $t_method ) {
		case FTP:
		case DISK:
			file_ensure_valid_upload_path( $t_file_path );

			if( !file_exists( $t_disk_file_name ) ) {
				if( FTP == $t_method ) {
					$conn_id = file_ftp_connect();
					file_ftp_put( $conn_id, $t_disk_file_name, $t_tmp_file );
					file_ftp_disconnect( $conn_id );
				}

				if( !move_uploaded_file( $t_tmp_file, $t_disk_file_name ) ) {
					trigger_error( ERROR_FILE_MOVE_FAILED, ERROR );
				}

				chmod( $t_disk_file_name, config_get( 'attachments_file_permissions' ) );

				$c_content = "''";
			} else {
				trigger_error( ERROR_FILE_DUPLICATE, ERROR );
			}
			break;
		case DATABASE:
			$c_content = db_prepare_binary_string( fread( fopen( $t_tmp_file, 'rb' ), $t_file_size ) );
			break;
		default:
			trigger_error( ERROR_GENERIC, ERROR );
	}


$t_file_table = plugin_table( 'file_usecase', 'honey' );

$query = "INSERT INTO $t_file_table
						 (content,  filename, file_type, id_usecase)
					  VALUES
						(  '$c_new_file_name', '$c_file_type',  $c_content, $c_id_usecase)";
db_query( $query );

}

?>