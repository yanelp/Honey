<style type="text/css">
table.width30 { width: 30%;  border: solid 1px #000000;  } ;
#link_actual { border-bottom:1px solid #ccc; background-color:#fff; line-height: 20px; overflow:auto; font-size:11px; font-weight:bold; }
#link_actual a {color:red;}
</style>

<?php

//Limito la cant de elementos en el paginado
$TAMANO_PAGINA = 7;

require_once( 'bug_group_action_api.php' );

function print_lel_menu( $p_page = '' ) {

	echo '<div align="center" ><p>';

	if($p_page=='new'){
		$t_link= plugin_page( 'new_symbol_page' );
		echo '<span id="link_actual">[&#160;';
		echo "<a  href=\"$t_link\">". plugin_lang_get( 'Lel_symbol_link' )."</a>";
		echo '&#160;]</span> ';
	}
	else{
		if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
			print_bracket_link(  plugin_page( 'new_symbol_page' ), plugin_lang_get( 'Lel_symbol_link' ) );
		}
	}
	
	if($p_page=='view'){
		$t_link= plugin_page( 'view_symbols_page' );
		echo '<span id="link_actual">[&#160;';
		echo "<a  href=\"$t_link\">". plugin_lang_get( 'Lel_view_symbols_link' )."</a>";
		echo '&#160;]</span> ';
	}
	else{
		if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
			print_bracket_link(  plugin_page( 'view_symbols_page' ), plugin_lang_get( 'Lel_view_symbols_link' ) );
		}
	}

	if($p_page=='derivation'){
		$t_link= plugin_page( 'derivation_page' );
		echo '<span id="link_actual">[&#160;';
		echo "<a  href=\"$t_link\">". plugin_lang_get( 'Lel_derivation_link' )."</a>";
		echo '&#160;]</span> ';
	}
	else{
		if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
			print_bracket_link(  plugin_page( 'derivation_page' ), plugin_lang_get( 'Lel_derivation_link' ) );
		}
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

	if($p_page=='new_uc'){
		$t_link= plugin_page( 'new_usecase_page' );
		echo '<span id="link_actual">[&#160;';
		echo "<a  href=\"$t_link\">". plugin_lang_get( 'usecase_new_link' )."</a>";
		echo '&#160;]</span> ';
	}
	else{
		if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
			print_bracket_link(  plugin_page( 'new_usecase_page' ), plugin_lang_get( 'usecase_new_link' ) );
		}
	}

	if($p_page=='view_uc'){
		$t_link= plugin_page( 'view_cu_page' );
		echo '<span id="link_actual">[&#160;';
		echo "<a  href=\"$t_link\">". plugin_lang_get( 'usecase_view_cu_link' )."</a>";
		echo '&#160;]</span> ';
	}
	else{
		if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
			print_bracket_link(  plugin_page( 'view_cu_page' ), plugin_lang_get( 'usecase_view_cu_link' ) );
		}
	}

	if($p_page=='new_rule'){
		$t_link= plugin_page( 'new_rule_page' );
		echo '<span id="link_actual">[&#160;';
		echo "<a  href=\"$t_link\">". plugin_lang_get( 'new_rule_link' )."</a>";
		echo '&#160;]</span> ';
	}
	else{
		if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
			print_bracket_link(  plugin_page( 'new_rule_page' ), plugin_lang_get( 'new_rule_link' ) );
		}
	}

	if($p_page=='view_rule'){
		$t_link= plugin_page( 'view_rules_page' );
		echo '<span id="link_actual">[&#160;';
		echo "<a  href=\"$t_link\">". plugin_lang_get( 'view_rule_link' )."</a>";
		echo '&#160;]</span> ';
	}
	else{
		if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
			print_bracket_link(  plugin_page( 'view_rules_page' ), plugin_lang_get( 'view_rule_link' ) );
		}
	}

	if($p_page=='new_actor'){
		$t_link= plugin_page( 'new_actor_page' );
		echo '<span id="link_actual">[&#160;';
		echo "<a  href=\"$t_link\">". plugin_lang_get( 'new_actor_link' )."</a>";
		echo '&#160;]</span> ';
	}
	else{
		if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
			print_bracket_link(  plugin_page( 'new_actor_page' ), plugin_lang_get( 'new_actor_link' ) );
		}
	}

	if($p_page=='view_actor'){
		$t_link= plugin_page( 'view_actors_page' );
		echo '<span id="link_actual">[&#160;';
		echo "<a  href=\"$t_link\">". plugin_lang_get( 'view_actor_link' )."</a>";
		echo '&#160;]</span> ';
	}
	else{
		if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
			print_bracket_link(  plugin_page( 'view_actors_page' ), plugin_lang_get( 'view_actor_link' ) );
		}
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
						 where id=trim(' . db_param().')';

		$result_note = db_query_bound( $query_note, array($palabra) );
		$count_note = db_num_rows( $result_note );
		$row_search = db_fetch_array( $result_note ) ; 

		if($count_note>0){
			$cant_links++;

			$id_uc_search=$row_search['id'];
			
			$t_page=$t_page."&id_usecase=".$id_uc_search;
			
			$concat= str_pad($palabra, 7, "0", STR_PAD_LEFT);
			
			if($frase==''){$frase=str_replace($palabra, $concat,$p_string);	}
			else {$frase=str_replace($palabra, $concat,$frase);}

			$link="<a href=\"$t_page\">".$concat."</a>";

			$frase=str_replace($concat, $link,$frase);
		}
		
	}
	$frase=str_replace('#', '',$frase);
	if($cant_links==0){$frase=$p_string;}
	return $frase;
}

function string_convert_issue_link($p_string) {

	$words = preg_split("/[\s]+/", $p_string);
	$num_words=sizeof($words);
	$t_page= 'view.php';
	$cant_links=0;
	$frase='';

	for($r=0;$r<$num_words;$r++){

		$palabra=str_replace('#', '', $words[$r]) ;
		//echo "*".$palabra;
		//busco en la tabla de casos de uso si la palabra es un id_view

		$t_repo_table = db_get_table( 'mantis_bug_table');

		$query_note = 'SELECT id 
						 FROM '.$t_repo_table.' 
						 where id=trim(' . db_param().')';

		$result_note = db_query_bound( $query_note, array($palabra) );
		$count_note = db_num_rows( $result_note );
		$row_search = db_fetch_array( $result_note ) ; 

		if($count_note>0){
			$cant_links++;

			$id_issue=$row_search['id'];
			
			$t_page=$t_page."?id=".$id_issue;
			
			$concat= str_pad($palabra, 7, "0", STR_PAD_LEFT);
			if($frase==''){$frase=str_replace($palabra, $concat,$p_string);	}
			else {$frase=str_replace($palabra, $concat,$frase);}

			$link="<a href=\"$t_page\">".$concat."</a>";

			$frase=str_replace($concat, $link,$frase);
		}
		
	}
	$frase=str_replace('#', '',$frase);
	if($cant_links==0){$frase=$p_string;}
	return $frase;
}

function string_convert_link($p_string){

	
	$words = preg_split("/[\s]+/", $p_string);
	$num_words=sizeof($words);
	$cant_links=0;
	$frase='';
	for($r=0;$r<$num_words;$r++){

		$palabra= $words[$r];//#714
		if(strpos($palabra, '#')!==false){//es un id de issue o de uc
		  $palabra_bd=str_replace('#', '', $words[$r]) ;
		  
		  if(($words[$r-1]=='issue')||($words[$r-1]=='bug')||($words[$r-1]=='incidencia')||($words[$r-1]=='fallo')||($words[$r-1]=='error')||($words[$r-1]=='bug')){
			  
				$t_repo_table = db_get_table( 'mantis_bug_table');

				$query_note = 'SELECT id 
								 FROM '.$t_repo_table.' 
								 where id=trim(' . db_param().')';

				$result_note = db_query_bound( $query_note, array($palabra_bd) );
				$count_note = db_num_rows( $result_note );
				$row_search = db_fetch_array( $result_note ) ; 

				if($count_note>0){
					$cant_links++;

					$id_issue=$row_search['id'];

					$t_page= 'view.php';
					
					$t_page=$t_page."?id=".$id_issue;
					
					$concat= str_pad($palabra_bd, 7, "0", STR_PAD_LEFT);

					if($frase==''){	$frase=str_replace($palabra, $concat,$p_string);}
					else {	$frase=str_replace($palabra, $concat,$frase);}
	
					$link="<a href=\"$t_page\">".$concat."</a>";

					$frase=str_replace($concat, $link,$frase);
				}
				
		  }//es id de issue
		  else{//es id de uc


			  $t_repo_table = plugin_table( 'usecase', 'honey' );

				$query_note = 'SELECT id 
								 FROM '.$t_repo_table.' 
								 where id=trim(' . db_param().')';

				$result_note = db_query_bound( $query_note, array($palabra_bd) );
				$count_note = db_num_rows( $result_note );
				$row_search = db_fetch_array( $result_note ) ; 

				if($count_note>0){
					$cant_links++;

					$id_uc_search=$row_search['id'];

					$t_page= plugin_page( 'usecase_page' );
					
					$t_page=$t_page."&id_usecase=".$id_uc_search;
					
					$concat= str_pad($palabra_bd, 7, "0", STR_PAD_LEFT);
					
					if($frase==''){	$frase=str_replace($palabra, $concat,$p_string);}
					else {	$frase=str_replace($palabra, $concat,$frase);}

					$link="<a href=\"$t_page\">".$concat."</a>";

					$frase=str_replace($concat, $link,$frase);
				}
				
		  }//es un id de uc
		}//es un id
		
	}//for
	$frase=str_replace('#', '',$frase);
	if($cant_links==0){$frase=$p_string;}
	return $frase;
	
}

function string_convert_uc_issue_link($p_string) {

	//busco si es un comentario de un cu solo, issue solo o un comentario que tiene uc e issue

	//sólo uc
	if(((strpos($p_string, 'issue #')==false)&&(strpos($p_string, 'bug #')==false)&&(strpos($p_string, 'incidencia #')==false)&&(strpos($p_string, 'fallo #')==false)&&(strpos($p_string, 'error #')==false)&&(strpos($p_string, 'problema #')==false))&&((strpos($p_string, 'uc #')!=false)||(strpos($p_string, 'cu #')!=false)||(strpos($p_string, 'use case #')!=false)||(strpos($p_string, 'caso de uso #')!=false))) {
		$frase=string_convert_uc_link($p_string);
	}
	else{//sólo issue
		if(((strpos($p_string, 'issue #')!=false)||(strpos($p_string, 'bug #')!=false)||(strpos($p_string, 'incidencia #')!=false)||(strpos($p_string, 'fallo #')!=false)||(strpos($p_string, 'error #')!=false)||(strpos($p_string, 'problema #')!=false))&&((strpos($p_string, 'uc #')==false)&&(strpos($p_string, 'cu #')==false)&&(strpos($p_string, 'use case #')==false)&&(strpos($p_string, 'caso de uso #')==false))) {
			$frase=string_convert_issue_link($p_string);
		}
		else{//ambos
			if(((strpos($p_string, 'issue #')!=false)||(strpos($p_string, 'bug #')!=false)||(strpos($p_string, 'incidencia #')!=false)||(strpos($p_string, 'fallo #')!=false)||(strpos($p_string, 'error #')!=false)||(strpos($p_string, 'problema #')!=false))&&((strpos($p_string, 'uc #')!=false)||(strpos($p_string, 'cu #')!=false)|(strpos($p_string, 'use case #')!=false)||(strpos($p_string, 'caso de uso #')!=false))) {
				$frase=string_convert_link($p_string);
			}
			else {$frase=$p_string;}
		}
	}

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


function print_successful_redirect_honey($p_uc_id, $page, $salto){

	$t_use_iis = config_get( 'use_iis' );

	$t_url = string_get_uc_view_url( $p_uc_id, $page ) ;

	if( !headers_sent() ) {
		header( 'Content-Type: text/html; charset=utf-8' );

		if( ON == $t_use_iis ) {
			header( "Refresh: 0;url=$t_url" );
		}
		else {
			if($salto=='note'){
				header( "Location: $t_url" . "#uc_notes" );
			}
			else{ 
				if($salto=='attachment'){
					header( "Location: $t_url" . "#uc_atach" );
				}
				else{
				header( "Location: $t_url" );
				}
			}//else
		}//else
	}//if
	else {
		trigger_error( ERROR_PAGE_REDIRECTION, ERROR );
		return false;
	}//else

	return true;
}//function

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
	$t_file_size = filesize( $p_file['tmp_name'] );
	file_ensure_uploaded( $p_file );
		
	$t_project_id= helper_get_current_project();
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
	
	if( !file_type_check( $t_file_name ) ) {
		trigger_error( ERROR_FILE_NOT_ALLOWED, ERROR );
	}

	if( !file_is_name_unique( $t_file_name, $id_usecase ) ) {
		trigger_error( ERROR_FILE_DUPLICATE, ERROR );
	}

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
							(  $c_content, '$c_new_file_name', '$c_file_type', $c_id_usecase)";
	db_query( $query );

}//fin function

function usecase_get_attachments( $p_usecase_id ) {

	$c_usecase_id = db_prepare_int( $p_usecase_id );

	$t_bug_file_table = plugin_table( 'file_usecase', 'honey' );

	$query = "SELECT *
		                FROM $t_bug_file_table
		                WHERE id_usecase=" . db_param() . "
		                ORDER BY id";

	$db_result = db_query_bound( $query, Array( $c_usecase_id ) );
	$num_files = db_num_rows( $db_result );

	$t_result = array();

	for( $i = 0;$i < $num_files;$i++ ) {
		$t_result[] = db_fetch_array( $db_result );
	}

	return $t_result;
}

function file_usecase_get_visible_attachments( $p_usecase_id ) {
	$t_attachment_rows = usecase_get_attachments( $p_usecase_id );
	$t_visible_attachments = array();

	$t_attachments_count = count( $t_attachment_rows );

	if( $t_attachments_count === 0 ) {
		return $t_visible_attachments;
	}

	$t_attachments = array();

	$t_preview_text_ext = config_get( 'preview_text_extensions' );
	$t_preview_image_ext = config_get( 'preview_image_extensions' );

	$image_previewed = false;
	for( $i = 0;$i < $t_attachments_count;$i++ ) {
		$t_row = $t_attachment_rows[$i];
		$t_id = $t_row['id'];
		$t_filename = $t_row['filename'];
		$t_filesize = $t_row['filesize'];
		//$t_diskfile = file_normalize_attachment_path( $t_row['diskfile'], bug_get_field( $p_usecase_id, 'project_id' ) );

		$t_attachment = array();
		$t_attachment['id'] = $t_id;
		$t_attachment['display_name'] = file_get_display_name( $t_filename );
		$t_attachment['size'] = $t_filesize;
		/*$t_attachment['date_added'] = $t_date_added;*/
		$t_attachment['diskfile'] = $t_diskfile;

		$t_page="file_usecase_download";
		$t_page=$t_page."&file_id=".$t_id;

		$t_attachment['download_url'] =plugin_page($t_page);

		if( $image_previewed ) {
			$image_previewed = false;
		}

		$t_attachment['icon'] = file_get_icon_url( $t_attachment['display_name'] );

		$t_attachment['preview'] = false;
		$t_attachment['type'] = '';

		$t_ext = strtolower( file_get_extension( $t_attachment['display_name'] ) );
		$t_attachment['alt'] = $t_ext;

		if ( in_array( $t_ext, $t_preview_text_ext, true ) ) {
				$t_attachment['preview'] = true;
				$t_attachment['type'] = 'text';
			} else if ( in_array( $t_ext, $t_preview_image_ext, true ) ) {
				$t_attachment['preview'] = true;
				$t_attachment['type'] = 'image';
			}

			$t_attachments[] = $t_attachment;

		}//for

	return $t_attachments;
}

function print_uc_attachments_list( $p_usecase_id, $num ) {
	
	$t_attachments = file_usecase_get_visible_attachments( $p_usecase_id );
	$t_attachments_count = count( $t_attachments );

	$i = 0;
	$image_previewed = false;
	$cant=0;
	foreach ( $t_attachments as $t_attachment ) {
		$t_file_display_name = string_display_line( $t_attachment['display_name'] );
		
		$t_filesize = number_format( $t_attachment['size'] );

		if ( $image_previewed ) {
			$image_previewed = false;
			//echo '<br />';
		}

		$t_href_start = '<a href="' . string_attribute( $t_attachment['download_url'] ) . '">';
		$t_href_end = '</a>';
		
		echo $t_href_start;
		print_file_icon( $t_file_display_name );
		echo $t_href_end . '&#160;' . $t_href_start . $t_file_display_name . $t_href_end  . '<span class="italic">' . $t_date_added . '</span>';

		if($num==1){
			$t_page_delete= plugin_page( 'file_usecase_delete' );
			echo '&#160;[';
			print_link( $t_page_delete.'&file_id=' . $t_attachment['id'].'&usecase_id='.$p_usecase_id  ,'Delete', false, 'small' );
			echo ']';
		}

		if ( $t_attachment['preview'] && ( $t_attachment['type'] == 'text' ) ) {
			 $c_id = db_prepare_int( $t_attachment['id'] );
			 $t_bug_file_table = plugin_table( 'file_usecase', 'honey' );

			echo "<script type=\"text/javascript\" language=\"JavaScript\">
					<!--
					function swap_content( span ) {
					displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
					document.getElementById( span ).style.display = displayType;
					}

					 -->
					 </script>";
			echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content(\"hideSection_" . $c_id . "\");swap_content(\"showSection_" . $c_id . "\");return false;'>" . lang_get( 'show_content' ) . "</a>]</span>";
			echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content(\"hideSection_" . $c_id . "\");swap_content(\"showSection_" . $c_id . "\");return false;'>" . lang_get( 'hide_content' ) . "</a>]";

			echo "<pre>";


			$query = "SELECT *
	                  					FROM $t_bug_file_table
				            			WHERE id=" . db_param();
			$result = db_query_bound( $query, Array( $c_id ) );
			$row = db_fetch_array( $result );
			$v_content = $row['content'];
				

			echo htmlspecialchars( $v_content );
			echo "</pre></span>\n";
		}

		if ( $t_attachment['type'] == 'image' ) {
		
		$t_preview_style = 'border: 0;';
				$t_max_width = config_get( 'preview_max_width' );
				if( $t_max_width > 0 ) {
					$t_preview_style .= ' max-width:' . $t_max_width . 'px;';
				}

				$t_max_height = config_get( 'preview_max_height' );
				if( $t_max_height > 0 ) {
					$t_preview_style .= ' max-height:' . $t_max_height . 'px;';
				}

				$t_preview_style = 'style="' . $t_preview_style . '"';
				$t_title = file_get_field( $t_attachment['id'], 'title' );

				$t_image_url = $t_attachment['download_url'] . '&amp;show_inline=1' . form_security_param( 'file_show_inline' );


				/**/

				$c_id = db_prepare_int( $t_attachment['id'] );
				echo "<script type=\"text/javascript\" language=\"JavaScript\">
					<!--
					function swap_content2( span ) {
					displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
					document.getElementById( span ).style.display = displayType;
					}

					 -->
					 </script>";
			echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content2(\"hideSection_" . $c_id . "\");swap_content2(\"showSection_" . $c_id . "\");return false;'>" . lang_get( 'show_content' ) . "</a>]</span>";
			echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content2(\"hideSection_" . $c_id . "\");swap_content2(\"showSection_" . $c_id . "\");return false;'>" . lang_get( 'hide_content' ) . "</a>]";
				echo "<pre>";

				/**/

				echo "\n<br />$t_href_start<img alt=\"$t_title\" $t_preview_style src=\"$t_image_url\" />$t_href_end";
				$image_previewed = true;

				echo "</pre></span>\n";
		}
			

		if ( $i != ( $t_attachments_count - 1 ) ) {
			echo "<br />\n";
			$i++;
		}
		$cant++;
	}//for
	return $cant;
}//fin function

function actors_usecase($actores, $verb_id, $actores_id, $id_usecase, $symbol_actor){

	//busco los sinomimos de cada actor
	$cant_actores=sizeof($symbol_actor);

	for($i=0;$i<$cant_actores;$i++){
	
		$t_repo_table_syn = plugin_table( 'synonymous', 'honey' );

		$query_search_syn = 'SELECT name
						    FROM '.$t_repo_table_syn.'
							WHERE id_symbol=' . db_param();
		$result_search_syn= db_query_bound($query_search_syn, array($symbol_actor[$i]));

		$cant_syn=db_num_rows( $result_search_syn );

		if($cant_syn>0){//SI TIENE SYN
		
			while( $row_actor = db_fetch_array( $result_search_syn ) ){
				$synonymous=strtoupper($row_actor['name']);
				array_push($actores,$synonymous);
				array_push($actores_id,$actores_id[$i]);
			}//while
		}//if

	}//for

	//busco los impactos de un simbolo de tipo verbo dado

	$t_repo_table_impact = plugin_table( 'impact', 'honey' );

	$query_search_impact = 'SELECT description
						    FROM '.$t_repo_table_impact.'
							WHERE id_symbol=' . db_param().'
							AND active = 0';
 
	$result_search_impact= db_query_bound($query_search_impact, array($verb_id));

	//por cada impacto del simbolo de tipo verbo armo un vector con todas las palabras simples + las compuestas que sean actores o sus simonimos

	while( $row_impact = db_fetch_array( $result_search_impact ) ){
				
				$upper_impact=strtoupper($row_impact['description']);
				$words = preg_split("/[\s]+/", $upper_impact);
				$num_words=sizeof($words);
				
				for($r=0;$r<$num_words;$r++){
				  $i=1;
					while($i<=$num_words){
						$new_word='';
						$j=$r;
						
						while($j<$i){//desde el ppio hasta el final
							
							$new_word=trim($new_word." ".$words[$j]);
							$new_word=strtoupper($new_word);

							if (in_array($new_word, $words)==false) {//si no está en el vector

								//busco si es un actor
							
								if (in_array($new_word, $actores)==true){

									array_push($words,$new_word);
								}
							}
							
							$j++;
						}//while ($j<$i)
						
						$i++;
					
						}//while ($i<=$num_words)
				}//for				
		 }//while  

		 //ahora en words tengo todas las palabras simples del impacto + las compuestas que son actores

		 //si en words hay un actor inserto en usecase_actor

		 $max = sizeof($actores);

		 for($i=0;$i<$max;$i++){
		 
			if (in_array($actores[$i], $words)==true){
		  
				$t_repo_table = plugin_table( 'usecase_actor', 'honey' );

				$t_query_actor = 'INSERT INTO '.$t_repo_table.' (id_usecase, id_actor )
									VALUES ( ' . db_param() . ', ' . db_param() . ' )';

				$g_result_insert_actor=db_query_bound( $t_query_actor, array($id_usecase, $actores_id[$i]));
			}//if	
		 }//for
		
}//function

function path_file($name_file){
    
	$tpage=   dirname( __FILE__ ) . DIRECTORY_SEPARATOR ;
	$tpage=str_replace('\\', '/', $tpage);
	$words = preg_split('[/]', $tpage);
	$cant_words=sizeof($words)-2;//para sacar "/pages"
	for($i=3;$i<$cant_words;$i++){$tpath=$tpath.'/'.$words[$i];}
	$tpath=$tpath. '/files/'.$name_file;
	return $tpath;

}

function showMessage($msg, $type){

	echo "</br>";
	echo "<table class='width30' align='center'>";
	echo "<tr class='row-category'>";
	echo "<td colspan='2'>".plugin_lang_get($type)."</td>";
	echo "</tr>";
	echo "<tr>";
	if($type=='congratulations'){
		echo "<td align='center'><img src='".path_file('congratulations.jpg')."' width='50'  height='50'/></td>";
	}
	else{
		if($type=='warning'){echo "<td><img src='".path_file('warning.jpg')."' width='50'  height='50'/></td>";}
		else{
			if($type=='error'){echo "<td><img src='".path_file('error.jpg')."' width='50'  height='50'/></td>";}
			else{
				if($type=='information'){echo "<td><img src='".path_file('information.jpg')."' width='50'  height='50'/></td>";}
			}
		}
	}
	
	echo "<td>". $msg."</td>";
	echo "</tr>";
	//echo "</table>";

}


?>