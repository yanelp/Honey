<?php

require_once('functions.php');
require_once( 'custom_field_api.php' );
require_once('manage_sequences.php');

html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

?>
<br/>

<?php

$t_project_id= helper_get_current_project();

/*CREACION DE ACTORES*/

$type_subject = 1;

$t_repo_table_symbol = plugin_table( 'symbol', 'honey' );

								$query_search = 'SELECT *
												  FROM '.$t_repo_table_symbol.' 
												  WHERE type=' . db_param();

$result_search = db_query_bound( $query_search, array($type_subject) );

$a=0;
$actores_id;


while($row_search  = db_fetch_array( $result_search )){
      
	  $actor_name = $row_search['name'];
	  $actor_description = $row_search['notion'];
	  
	  //actor insert
		$t_repo_table = plugin_table( 'actor', 'honey' );

		$t_query_actor = 'INSERT INTO '.$t_repo_table.' (name, description, id_project  )
					VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' )';
		$g_result_insert_actor=db_query_bound( $t_query_actor, array( $actor_name, $actor_description, $t_project_id)  );

		$id_actor=mysql_insert_id();

		$actores[$a] = $actor_name;
		$actores_id[$a] = $id_actor;
		

		$a=$a+1;

}

/*CREACION DE CASOS DE USO*/

//Buscamos los simbolos de tipo verbo


$type_verb = 4;

$t_repo_table_symbol = plugin_table( 'symbol', 'honey' );

								$query_search = 'SELECT *
												  FROM '.$t_repo_table_symbol.' 
												  WHERE type=' . db_param();

$result_search = db_query_bound( $query_search, array($type_verb) );

$count = db_num_rows( $result_search );

if ($count == 0){
   echo "no existen simbolos de tipo verbo para derivar a casos de uso";

   $t_page = plugin_page( 'view_symbols_page' );

echo '<br><br>';
echo "<a href=\"$t_page\">Back</a>";
echo "<br>";
html_page_bottom1( );
};

if ($count > 0){
    
	//por cada verbo
    while($row_search  = db_fetch_array( $result_search )){

		//cramos el view_id del caso de uso
		$nextId = getNextSeq("usecase_secuence");
   
        $verb_id = $row_search['id'];
			
		$view_id = "CU_".$nextId;

		$uc_name =  $row_search['name'];
 
        //la noción en el verbo del LEL es el objetivo del CU
		$uc_goal = $row_search['notion'];



			/*
		TODO: falta derivar postoconditios y observaciones, por ahora se dejan como variables vacias. Se empezo a hacer derivación de precondiciones
		*/

		$postconditions = "";
		$observations = "";
		$precondition = "";

		$t_repo_table = plugin_table( 'usecase', 'honey' );

		$t_query_symbol = 'INSERT INTO '.$t_repo_table.' (name, goal, view_id, postconditions, observations, preconditions, id_project)
					VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' , ' . db_param() . ')';

		$g_result_insert_symbol=db_query_bound( $t_query_symbol, array( $uc_name, $uc_goal, $view_id, $postconditions, $observations, $precondiciones, $t_project_id)  );

		$id_usecase=mysql_insert_id();


         
	    //vinculamos los actores: Si entre sus impactos tiene el nombre del actor insertamos un registro en usecase_actor

		 $max = sizeof($actores);
    
		 for ($i = 0; $i <= $max; $i++) {
					

					$t_repo_table_impact = plugin_table( 'impact', 'honey' );

				     $query_search_impact = 'SELECT *
								  FROM '.$t_repo_table_impact.'
								  WHERE id_symbol=' . db_param().'
								  AND description LIKE "%'.$actores[$i].'%"';
			        

					$result_search_impact= db_query_bound($query_search_impact, array($verb_id));
					
					$count = db_num_rows($result_search_impact);

					if ($count > 0){
					    
					$idactor = $actores_id[$i];
                         
						if ($idactor>0){

						$t_repo_table = plugin_table( 'usecase_actor', 'honey' );

						$t_query_actor = 'INSERT INTO '.$t_repo_table.' (id_usecase, id_actor )
									VALUES ( ' . db_param() . ', ' . db_param() . ' )';

						$g_result_insert_actor=db_query_bound( $t_query_actor, array($id_usecase, $idactor));
						
						$id_rel= mysql_insert_id();
						}

					 }
				};

		
		
		
/* TODA ESTA PARTE ES LAS PRECONDICIONES 
		
		$preconditions;
		$nextPrecondition=0;

      
	 //Precondicion: StateUsingVerbAsTransition.name
	 //buscamos los impactos del simbolo de tipo estado que tienen en su descripcion el string del nombre del verbo o cualquiera de sus sinónimos.
     
		//armamos un array con el nombre del verbo y sus sinonimos 
			$i=0;

			$arrayverbo[$i] = $uc_name;

			
			$t_repo_table_synonymous = plugin_table( 'synonymous', 'honey' );
            
			$query_search_synonymous = 'SELECT *
									    FROM '.$t_repo_table_synonymous.' 
									    WHERE id_symbol=' . db_param();

			$result_search_synonymous = db_query_bound( $query_search_synonymous, array($verb_id) );
			 
			  while($row_search_synonymous = db_fetch_array( $result_search_synonymous)){

			    $i=$i+1;
				$arrayverbo[$i] = $row_search_synonymous['name'];
				
			  }
			

	  
	    //Buscamos los simbolos de tipo estado

		$type_state = 3; //tipo estado

        $t_repo_table_state = plugin_table( 'symbol', 'honey' );

								$query_search_state = 'SELECT *
												  FROM '.$t_repo_table_state.' 
												  WHERE type=' . db_param();

		$result_search_state= db_query_bound( $query_search_state, array($type_state) );
		
		//recuperamos todos los impactos del estado que tienen el nombre del verbo o sus sinonimos
	  
          while($row_search_state = db_fetch_array( $result_search_state)){
		  
		   $state_id = $row_search_state['id'];
           
				for ($a = 0; $a <= $i; $a++) {
					
					$where[$a] = 'description like "%' .$arrayverbo[$a].'%"';
				};

			$t_repo_table_impact = plugin_table( 'impact', 'honey' );

	         $query_search_impact = 'SELECT *
						  FROM '.$t_repo_table_impact.' 
						  WHERE id_symbol=' . db_param().'
						  AND ('.implode("OR ", $where). ')';
	
			$result_search_impact= db_query_bound($query_search_impact, array($state_id) );

			/*while ($row_search_impact = db_fetch_array($result_search_impact)){

			   $preconditions[$nextPrecondition] = $row_search_impact['description']; ;
		       $nextPrecondition=$nextPrecondition+1;
						   
			 }*/

			/*$count = db_num_rows($result_search_impact);
			if ($count > 0){
			   $preconditions[$nextPrecondition]  = "the ".$ve
			}

		 
		 }//fin "while $row_search_state"

         
		  $count = db_num_rows( $result_search );


$max = sizeof($preconditions);

if ($max > 0){
$precondiciones = implode(',' , $preconditions);

}else
		{$precondiciones = "";}

*/




}//fin por cada verbo
	
}// fin if ($count > 0)



