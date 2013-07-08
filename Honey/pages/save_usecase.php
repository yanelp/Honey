<?php



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
//$view_id=$_REQUEST['view_id'];
$row_number_uc_actor=$_REQUEST['row_number_uc_actor'];



$t_page = plugin_page( 'new_usecase_page' );


if ( is_blank( $name ) || is_blank( $mainscenario )) {
//			trigger_error( ERROR_GENERIC, ERROR );
echo "Must add Namea and main scenario";
echo '<br><br>';
echo "<a href=\"$t_page\">Back</a>";
echo "<br>";
html_page_bottom1( );
die();
}


//usecase insert

$t_repo_table = plugin_table( 'usecase', 'honey' );

$t_query_symbol = 'INSERT INTO '.$t_repo_table.' (name, goal, postconditions, observations, preconditions, id_project)
			VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' , ' . db_param() . ')';

$g_result_insert_symbol=db_query_bound( $t_query_symbol, array( $name, $goal, $postconditions, $obsevations, $preconditions, $t_project_id)  );

$id_usecase=mysql_insert_id();

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

$id_scenario =mysql_insert_id();

/*save actors*/


$t_repo_table = plugin_table( 'actor', 'honey' );

$description = ""; //la descripción es vacia para los actores agregados desde el formulario de nuevo caso de uso. 
 
for($i=0; $i<$row_number_uc_actor; $i++){

	$t_query_actors = 'INSERT INTO '.$t_repo_table.' (name, id_project, description)
				VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' )';
	$g_result_insert_actors=db_query_bound( $t_query_actors, array( $_REQUEST['uc_actor'.$i], $t_project_id, $description)  );
	
	$id_actor =mysql_insert_id();

	$t_repo_table_uc_actor= plugin_table( 'usecase_actor', 'honey' );

	$t_query_uc_actor = 'INSERT INTO '.$t_repo_table_uc_actor.' (id_usecase, id_actor)
				VALUES ( ' . db_param() . ', ' . db_param() .')';
	$g_result_insert_actors= db_query_bound( $t_query_uc_actor,  array($id_usecase, $id_actor));
     
}

echo "<p>Saved data</p>";


echo '<br><br>';
echo "<a href=\"$t_page\">Back</a>";

echo "<br>";

$t_url= plugin_page( 'view_cu_page' );

html_page_bottom( );

html_meta_redirect_honey( $t_url, $p_time = null);

?>