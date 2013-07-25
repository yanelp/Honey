<?php

require_once('functions.php');
require_once( 'custom_field_api.php' );
require_once('manage_sequences.php');

html_page_top( plugin_lang_get( 'title' ) );

?>
<br/>

<?php

$t_project_id= helper_get_current_project();


/*CREACION DE CASOS DE USO*/

//Buscamos los simbolos de tipo verbo



$type_verb = 4;

$t_repo_table_symbol = plugin_table( 'symbol', 'honey' );

								$query_search = 'SELECT *
												  FROM '.$t_repo_table_symbol.' 
												  WHERE type=' . db_param().'
												  AND active = 0';

$result_search = db_query_bound( $query_search, array($type_verb) );

$count_verb = db_num_rows( $result_search );

$a=0;
$actores_id;
$existenCondiciones = false;

if ($count_verb  == 0){

	print_lel_menu();

   echo "no existen simbolos de tipo verbo para derivar a casos de uso";

   $t_page = plugin_page( 'view_symbols_page' );

echo '<br><br>';
echo "<a href=\"$t_page\">Back</a>";
echo "<br>";
html_page_bottom1( );
};

if ($count_verb > 0){
    
     /*Insert de info de la derivaci�n*/

			$date = date('Y-m-d H:i:s'); 
			$active = 0;

			$t_repo_table_derivation = plugin_table( 'derivation', 'honey' );

			$t_query_derivation = 'INSERT INTO '.$t_repo_table_derivation .' (date, active, id_project)
						VALUES ( ' . db_param() . ', ' . db_param() . ', '.db_param() . ')';
			$g_result_insert_derivation =db_query_bound( $t_query_derivation, array($date, $active, $t_project_id));

			$id_derivation = mysql_insert_id();

			
        /*CREACION DE ACTORES*/

		$type_subject = 1;

		$t_repo_table_symbol_subject = plugin_table( 'symbol', 'honey' );

										$query_search_actor = 'SELECT *
														  FROM '.$t_repo_table_symbol_subject.' 
														  WHERE type=' . db_param().'
														  AND active = 0';

		 

		$result_search_actor = db_query_bound( $query_search_actor, array($type_subject) );

        

		while($row_search_actor  = db_fetch_array( $result_search_actor )){
			 
	         $id_symbol_subject= $row_search_actor['id'];

			 $actor_name = $row_search_actor['name'];

			 $actor_description = $row_search_actor['notion'];
			  
			//busco si el actor ya existe

			$t_repo_table_actor = plugin_table( 'actor', 'honey' );

			
			$t_query_actor= 'select * FROM '.$t_repo_table_actor.' WHERE id_project='. db_param() .' and active = 0 and name='. db_param();
				
			$g_result_actor_name=db_query_bound( $t_query_actor, array( $t_project_id, $actor_name) );

			$row = db_fetch_array( $g_result_actor_name );

			$count_actors = db_num_rows( $g_result_actor_name);

			$row_name=$row['name'];
			
			if($count_actors==0){//si no existe el actor
		
				//actor insert
				$t_repo_table_actor = plugin_table( 'actor', 'honey' );

				$t_query_actor = 'INSERT INTO '.$t_repo_table_actor.' (name, description, id_derivation, id_symbol, id_project)
							VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ','. db_param() . ','. db_param() . ')';

				$g_result_insert_actor=db_query_bound( $t_query_actor, array( $actor_name, $actor_description, $id_derivation, $id_symbol_subject, $t_project_id));

				$id_actor_insert=mysql_insert_id();

				$actores[$a] = $row_name;
				$actores_id[$a] = $id_actor_insert;
				

			}
			 if ($count_actors>0){ //si el actor existe

				 //me guardo los datos para relacionarlo con el nuevo caso de uso
				 $actores[$a] = $row_name;
				 $actores_id[$a] = $row_name=$row['id'];
				
			 }
				$a=$a+1;


		}

     /*FIN CREACION DE ACTORES*/ 

     /*INSERT DE CU�s*/ 
        
	    //matriz con el texto de las condiciones por cada verbo
        $matrizVerbo;

	   //matriz con el ID de las condiciones por cada verbo
        $matrizCondiciones;

	   $x=0;
	  //por cada verbo
		while($row_search  = db_fetch_array( $result_search )){

			$verb_id = $row_search['id'];
			
			$uc_name =  $row_search['name'];
	 
			//la noci�n en el verbo del LEL es el objetivo del CU
			$uc_goal = $row_search['notion'];

			$postconditions = "";
			$observations = "";
			$precondition = "";

			$t_repo_table = plugin_table( 'usecase', 'honey' );

			$t_query_symbol = 'INSERT INTO '.$t_repo_table.' (name, goal, postconditions, observations, preconditions, id_derivation, id_symbol, id_project)
						VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' , ' . db_param() . ' , ' . db_param() . ')';

			$g_result_insert_symbol=db_query_bound( $t_query_symbol, array( $uc_name, $uc_goal, $postconditions, $observations, $precondiciones, $id_derivation, $verb_id, $t_project_id)  );

			$id_usecase=mysql_insert_id();
			
			//Creaci�n de escenario principal

			$t_repo_table_impact = plugin_table( 'impact', 'honey' );
			
			$t_query_impact= 'select * FROM '.$t_repo_table_impact.' WHERE id_symbol='. db_param() .' and active = 0';
				
			$g_result_impact =db_query_bound( $t_query_impact, array($verb_id) );
		
			$count_impact = db_num_rows( $g_result_impact);

			$scenarioText="";

			if ($count_impact > 0 ){
         
		
				while($row_search_impact = db_fetch_array( $g_result_impact)){
				
					if($scenarioText == ""){
					
					 //echo "Hay escenario";
					  $scenarioText = $row_search_impact['description'].'<br>';
					
					}//fin if($scenarioText == "")
						else{
					 
						 //echo "no hay escneario";
						 $scenarioText = $scenarioText.'<br>'.$row_search_impact['description'].'<br>';
						 }// fin else

				}//fin while($row_search_impact..

      
			$scenario_type = 1;

			$t_repo_table_scenario = plugin_table( 'scenario', 'honey' );

			$t_query_scenario = 'INSERT INTO '.$t_repo_table_scenario.' (type, steps, id_usecase)
						VALUES ( ' . db_param() . ', ' . db_param() . ' , ' . db_param() . ')';
			
			$g_result_scenario =db_query_bound( $t_query_scenario, array($scenario_type, $scenarioText, $id_usecase) );

			$id_scenario = mysql_insert_id();

		} //fin  if ($count > 0 )
			
		
		//vinculamos los actores: Si entre sus impactos tiene el nombre del actor insertamos un registro en usecase_actor

		 $max = sizeof($actores);

		 for ($i = 0; $i <= $max; $i++) {
					

					$t_repo_table_impact = plugin_table( 'impact', 'honey' );

				     $query_search_impact = 'SELECT *
								  FROM '.$t_repo_table_impact.'
								  WHERE id_symbol=' . db_param().'
								  AND active = 0
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

						}//FIN if ($idactor>0)

					 } //FIN  if ($count > 0)

	}//FIN for

	/* CONDICIONES DEL CASO DE USO */
    
	$preconditions;
	$nextPrecondition=0;

    //buscamos los impactos del simbolo de tipo estado que tienen en su descripcion el string del nombre del verbo o cualquiera de sus sin�nimos.

			//armamos un array con el nombre del verbo y sus sinonimos 
		
			$i=0;

			$arrayverbo[$i] = strtolower($uc_name);

			
			$t_repo_table_synonymous = plugin_table( 'synonymous', 'honey' );
            
			$query_search_synonymous = 'SELECT *
									    FROM '.$t_repo_table_synonymous.' 
									    WHERE id_symbol=' . db_param();
             
			$result_search_synonymous = db_query_bound( $query_search_synonymous, array($verb_id) );

			 while($row_search_synonymous = db_fetch_array( $result_search_synonymous)){

			    $i=$i+1;
				$minusculas = strtolower($row_search_synonymous['name']);
				$arrayverbo[$i] = $minusculas ;
				
			  }
   
			 //Buscamos los simbolos de tipo estado

			$type_state = 3; //tipo estado
     
	     
			$t_repo_table_state = plugin_table( 'symbol', 'honey' );

									$query_search_state = 'SELECT *
													  FROM '.$t_repo_table_state.' 
													  WHERE type=' . db_param();

			$result_search_state= db_query_bound( $query_search_state, array($type_state) );
	
            $i=0; //variable para contar la canridad de impactos
			$idsimpacts;
			while($row_search_state = db_fetch_array( $result_search_state)){
				                    
					 $state_id = $row_search_state['id'];
					
					//recuperamos todos los impactos del estado
					
					 $t_repo_table_impact = plugin_table( 'impact', 'honey' );

					 $query_search_impact = 'SELECT *
								  FROM '.$t_repo_table_impact.' 
								  WHERE id_symbol=' . db_param();
			
					$result_search_impact= db_query_bound($query_search_impact, array($state_id) );
				
				  
				  while($row_search_impact = db_fetch_array( $result_search_impact)){
		
					$text_impact = $row_search_impact['description'];
									
					$isCondition = isCondition(strtolower($text_impact), $arrayverbo);
					
					if ($isCondition == true){
					 $matrizVerbo[$uc_name][$i] = $text_impact;
					 $matrizCondiciones[$uc_name][$i] = $row_search_impact['id'];

					 
					 $idsimpacts[$uc_name][$i] = $row_search_impact['id'];
					 $i++;
					 $existenCondiciones = true;
					}
				
				}//fin while $row_search_impact 
				
		
	   		}//fin while($row_search_state
            
         	if ($i>0){	//si hay condiciones para el verbo creo la tabla
		?>
		
		
		<form name="form1"  id="form1"  method="post"  enctype="multipart/form-data" onsubmit="javascript:validar()" action="<?php echo plugin_page( "save_conditions" ); ?>">

		<!--enviamos el id del CU -->
		<input type='hidden' name="cu_<?php echo $x?>" id="cu_<?php echo $x?>" value='<?php echo $id_usecase?>'/>
		
		<!--enviamos la cantida de condiciones que tiene el CU-->
		<input type='hidden' name="condiciones_<?php echo $x?>" id="condiciones_<?php echo $x?>" value='<?php echo $i?>'/>

			<div align="center">
			<table class="width90">
			<tr class="row-category">
				<td >
				<?php echo $uc_name?>
				</td>
				<td>
				No es condicion
				</td>
				<td>
				Pre-Condicion
				</td>
				<td>
				Post-Condicion
				</td>
			 </tr>
		
			<?php
            $a = 0;
			while ($a < $i) {
			?>
			<tr <?php echo helper_alternate_class() ?>>
			
			<td>
				<?php echo $matrizVerbo[$uc_name][$a];?>
			</td>

			<td class="center">
				<input type="radio" name="cond_<?php echo $id_usecase.$a?>" id="cond_<?php echo $id_usecase.$a?>" value="condicion"/>
			</td>

	        <td><input type="radio"  name="cond_<?php echo $id_usecase.$a?>" id="cond_<?php echo $id_usecase.$a?>" value="precondicion"/>
			<input type="hidden" name="id_cond_<?php echo $id_usecase.$a?>" id="id_cond_<?php echo $id_usecase.$a?>" value="<?php echo $matrizVerbo[$uc_name][$a]?>"/></td>

	        <td><input type="radio"  name="cond_<?php echo $id_usecase.$a?>" id="cond_<?php echo $id_usecase.$a?>" value="postcondicion"/>
			<input type="hidden" name="id_cond_<?php echo $id_usecase.$a?>" id="id_cond_<?php echo $id_usecase.$a?>" value="<?php echo $matrizVerbo[$uc_name][$a]?>"/></td>

			</tr>
           <?php
		    $a++;
			}
		  ?>
		   </table>
		   
		   <BR>
		   <BR>
		
			<?php

				}//fin if

			//} //fin while($row_search_state
  $x++;
}//fin por cada verbo
?>
<input type='hidden' name='cant_cu' id='cant_cu' value='<?php echo $count_verb ?>'/>

</form>

<td class="center"><input type='submit' name='button_ok' value='Save' onclick='validar();'>
<?php
if ($existenCondiciones == false){

$t_url=plugin_page('view_cu_page');
header( "Location: $t_url" );
}
	
}// fin if ($count > 0)



