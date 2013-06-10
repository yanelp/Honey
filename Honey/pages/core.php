<?php

require_once('print_cu_menu.php');
require_once( 'custom_field_api.php' );

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

?>
<br/>

<?php


//Buscamos los simbolos de tipo verbo

$type_verb = 4;

$t_repo_table_symbol = plugin_table( 'symbol', 'honey' );

								$query_search = 'SELECT *
												  FROM '.$t_repo_table_symbol.' 
												  WHERE type=' . db_param();

$result_search = db_query_bound( $query_search, array($type_verb) );

$row_search = db_fetch_array($result_search);

$count_verbs = db_num_rows( $result_search);

if ($count_verbs == 0){
   echo "no existen simbolos de tipo verbo para derivar a casos de uso"
}
else {

	for($i=0; $i<$count_verbs ;$i++){

		//cramos el view_id del caso de uso
		$nextId = getNextSeq("usecase_secuence");
		$view_id = "CU_".$nextId;

		$uc_name =  $row_search['name'];

		$uc_goal = $row_search['notion'];
 
        //

}	

}



