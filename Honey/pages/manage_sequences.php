<?php

function getNextSeq($sequence_name) {


$t_repo_table_sequence = plugin_table( 'sequence', 'honey' );

								$query_search = 'SELECT *
												  FROM '.$t_repo_table_sequence.' 
												  WHERE name=' . db_param();
												  												  

$result_search = db_query_bound( $query_search, array($sequence_name) );

$row_search = db_fetch_array($result_search);

$nLast = $row_search['value'];

if($nLast ==''){
  

       //sequence insert
       
	    $nLast = 1;
	        
		$t_query_sequence = 'INSERT INTO '.$t_repo_table_sequence.' (name, value)
					VALUES ( ' . db_param() . ', ' . db_param() . ')';

		$g_result_insert_sequence=db_query_bound( $t_query_sequence, array( $sequence_name, $nLast) );

		$id_secuence=mysql_insert_id(); 
		
}else
	{
      
	  $nLast = $nLast + 1;

	  $t_query_update = 'UPDATE '.$t_repo_table_sequence.' SET value=' . db_param() . '
			WHERE name=' .db_param();

	  $g_result_update_sequence=db_query_bound( $t_query_update, array($nLast, $sequence_name) );
   

}

return $nLast;


}






























?>