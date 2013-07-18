<?php


require_once( 'file_api.php' );
require_once('functions.php');
require_once( 'custom_field_api.php' );

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

?>
<br/>

<?php

$t_project_id= helper_get_current_project();
$name=$_REQUEST['cu_name'];
$goal=$_REQUEST['goal'];
$preconditions=$_REQUEST['preconditions'];
$postconditions=$_REQUEST['postconditions'];
$obsevations=$_REQUEST['obsevations'];
$mainscenario=$_REQUEST['cursoNormal'];
$row_number_uc_actor=$_REQUEST['row_number_uc_actor'];
$row_number_uc_rule=$_REQUEST['row_number_uc_rule'];
$row_number_uc_course_alt=$_REQUEST['row_number_cursoAlternativo'];
$row_number_uc_includes=$_REQUEST['row_number_uc_includes'];
$row_number_uc_extends=$_REQUEST['row_number_uc_extends'];

$t_page = plugin_page( 'new_usecase_page' );


if ( is_blank( $name ) || is_blank( $mainscenario )) {
//			trigger_error( ERROR_GENERIC, ERROR );
echo "Must add Name and main scenario";
echo '<br><br>';
echo "<a href=\"$t_page\">Back</a>";
echo "<br>";
html_page_bottom1( );
die();
}


//usecase insert

$t_repo_table = plugin_table( 'usecase', 'honey' );

$t_query_usecase = 'INSERT INTO '.$t_repo_table.' (name, goal, postconditions, observations, preconditions, id_project)
			VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' , ' . db_param() . ')';

$g_result_insert_usecase=db_query_bound( $t_query_usecase, array( $name, $goal, $postconditions, $obsevations, $preconditions, $t_project_id)  );

$id_usecase=mysql_insert_id();


/*save actors*/ 

 
for($i=0; $i<$row_number_uc_actor; $i++){
	
	if($_REQUEST['ck_actor_'.$i]=='on'){
	
		$id_actor=$_POST['id_actor_'.$i];
		
		$t_repo_table_uc_actor= plugin_table( 'usecase_actor', 'honey' );

		$t_query_uc_actor = 'INSERT INTO '.$t_repo_table_uc_actor.' (id_usecase, id_actor)
					VALUES ( ' . db_param() . ', ' . db_param() .')';
		$g_result_insert_actors= db_query_bound( $t_query_uc_actor,  array($id_usecase, $id_actor));

	}		 
}

/**
Insert main scenario 

Type: 1 = Principal
Type: 2 = Alternativo
*/

$type = 1;

$t_repo_table_scneario= plugin_table( 'scenario', 'honey' );

$t_query_scenario = 'INSERT INTO '.$t_repo_table_scneario.' (type, steps, id_usecase)
			VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ')';

$g_result_insert_scenario=db_query_bound( $t_query_scenario, array( $type, $mainscenario, $id_usecase));

//$id_scenario =mysql_insert_id();

$type = 2;

for($i=0;$i<$row_number_uc_course_alt;$i++){

	// insert cursoAlternativo
	$course_alt=$_REQUEST['cursoAlternativo'.$i];

	$t_query_scenario = 'INSERT INTO '.$t_repo_table_scneario.' (type, steps, id_usecase)
			VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ')';

	$g_result_insert_scenario=db_query_bound( $t_query_scenario, array( $type, $course_alt, $id_usecase));

}

/* INSERT REGLAS*/

 
for($i=0; $i<$row_number_uc_rule; $i++){
	
	if($_REQUEST['ck_rule_'.$i]=='on'){
	
		$id_rule=$_POST['id_rule_'.$i];
		
		$t_repo_table_uc_rule= plugin_table( 'rule_usecase', 'honey' );

		$t_query_uc_rule = 'INSERT INTO '.$t_repo_table_uc_rule.' (id_rule, id_usecase)
					VALUES ( ' . db_param() . ', ' . db_param() .')';
		$g_result_insert_rules= db_query_bound( $t_query_uc_rule,  array($id_rule, $id_usecase));

	}		 
}

/* INSERT extiende*/

$t_repo_table = plugin_table( 'usecase_extend', 'honey' );
 
for($i=0; $i<$row_number_uc_extends; $i++){
	
	if($_REQUEST['ck_extends_'.$i]=='on'){
	
		$id_parent=$_POST['id_extends_'.$i];

		$t_query_uc_extends = 'INSERT INTO '.$t_repo_table.' (id_usecase_parent, id_usecase_extends)
					VALUES ( ' . db_param() . ', ' . db_param() .')';
		$g_result_insert_extends= db_query_bound( $t_query_uc_extends,  array($id_parent, $id_usecase));

	}		 
}

/* INSERT incluye*/

$t_repo_table = plugin_table( 'usecase_include', 'honey' );
 
for($i=0; $i<$row_number_uc_includes; $i++){
	
	if($_REQUEST['ck_includes_'.$i]=='on'){
	
		$id_child=$_POST['id_includes_'.$i];

		$t_query_uc_includes = 'INSERT INTO '.$t_repo_table.' (id_usecase_parent, id_usecase_include)
					VALUES ( ' . db_param() . ', ' . db_param() .')';
		$g_result_insert_includes= db_query_bound( $t_query_uc_includes,  array($id_usecase, $id_child));

	}		 
}

/*insert files*/

$f_files=gpc_get_file( 'ufile', null );

//echo "count( $f_files ): ".count( $f_files );

for( $i = 0; $i < 10; $i++ ) {
	
		if( !empty( $f_files['name'][$i] ) ) {
			$t_file['name']     = $f_files['name'][$i];
			$t_file['tmp_name'] = $f_files['tmp_name'][$i];
			$t_file['type']     = $f_files['type'][$i];
			$t_file['error']    = $f_files['error'][$i];
			$t_file['size']     = $f_files['size'][$i];

			attach_add( $id_usecase, $t_file );
		}
	}


/*save */

echo "<p>Saved data</p>";


echo '<br><br>';
echo "<a href=\"$t_page\">Back</a>";

echo "<br>";

$t_url= plugin_page( 'view_cu_page' );

html_page_bottom( );

html_meta_redirect_honey( $t_url, $p_time = null);

?>