<?php

require_once('functions.php');
require_once( 'custom_field_api.php' );
require_once('manage_sequences.php');

html_page_top( plugin_lang_get( 'title' ) );

$t_project_id= helper_get_current_project();

$id_derivation = gpc_get_int( 'id_derivation' );

//echo "DERIVATION: ".$id_derivation;

/*Verificamos si existen actores activos y derivados vinculados a casos e usos activos*/

$t_repo_table= plugin_table( 'usecase_actor', 'honey' );
$t_repo_table_actor = plugin_table( 'actor', 'honey' );
$t_repo_table_usecase= plugin_table( 'usecase', 'honey' );

								$query_search = 'SELECT a.name actor, c.name caso_uso, c.id id_usecase, a.id id_actor
												  FROM '.$t_repo_table.' r, '.$t_repo_table_usecase.' c, '.$t_repo_table_actor.' a
												  WHERE r.id_usecase = c.id and r.id_actor = a.id and a.active=0 and a.id_derivation is not null
												  and c.id_derivation is null
												  order by c.id';


$result_search = db_query_bound( $query_search, array() );

$count = db_num_rows( $result_search );

if( $count>0 ){

 ?>

<div align="center">
<br><table class="width90">
<tr  class="row-category">
	<td colspan="2"> 
	<?php echo plugin_lang_get('derivation_actor_ref');?>
	</td>
</tr>



<tr  class="row-category">
	<td ><?php echo plugin_lang_get('actor');?></td>
	<td><?php echo plugin_lang_get('uc_manual');?></td>
</tr>

<!-- <tr class="row-category"> <td>Existen actores referenciados por los casos de uso:</td></tr>-->
<?php

while($row_search = db_fetch_array( $result_search )){
   
	 
	 $id_usecase = $row_search['id_usecase'];
	 $id_actor = $row_search['id_actor'];
	 $t_page = plugin_page( 'update_actor_page' );	
	 $t_page=$t_page."&id_actor=".$id_actor;
	 $t_page_usecase = plugin_page( 'usecase_page' );	
	 $t_page_usecase=$t_page_usecase."&id_usecase=".$id_usecase;
	 $id_a = str_pad($id_actor, 7, "0", STR_PAD_LEFT);
	 $id_cu = str_pad($id_usecase, 7, "0", STR_PAD_LEFT);
	 $actor_name=$row_search['actor'];
	 $uc_name=$row_search['caso_uso'];
	 ?>


	 <tr <?php echo helper_alternate_class() ?>>
			<td width='50%'>
			<?php echo "<a href=\"$t_page\">".$id_a."</a> - ".$actor_name;?>
			</td>
			<td width='50%'>
			<?php echo "<a href=\"$t_page_usecase\">".$id_cu."</a> - ".$uc_name;?>	
			</td>
	</tr>
	

<?php

}
?>
</table>
</div>
<?php




}

/*Verificamos si existen CUs activos y derivados vinculados a casos e usos activos*/

$t_repo_table_extend = plugin_table( 'usecase_extend', 'honey' );
$t_repo_table_usecase = plugin_table( 'usecase', 'honey' );

								$query_search = 'SELECT e.id_usecase_parent, e.id_usecase_extends, c.name name_parent , cc.name name_child
												  FROM '.$t_repo_table_extend.' e, '.$t_repo_table_usecase.' c , '.$t_repo_table_usecase.' cc
												  WHERE e.id_usecase_parent = c.id and c.active=0 and c.id_derivation is not null and cc.id_derivation is null
												  and e.id_usecase_extends = cc.id
												  order by e.id_usecase_extends,e.id_usecase_parent ';


$result_search = db_query_bound( $query_search, array() );

$count_cu = db_num_rows( $result_search );

if( $count_cu >0 ){

?>
 <div align="center">
<br><table class="width90">
<tr  class="row-category">
	<td colspan="2"> 
	<?php echo plugin_lang_get('derivation_uc_extend');?>
	</td>
</tr>



<tr  class="row-category"><td><?php echo plugin_lang_get('derivation_uc_be_erased');?></td>
<td ><?php echo plugin_lang_get('uc_manual');?></td></tr>

<!-- <tr class="row-category"> <td>Existen actores referenciados por los casos de uso:</td></tr>-->
<?php

while($row_search = db_fetch_array( $result_search )){
     
	 $usecase = $row_search['id_usecase_parent'];
	 $usecase_extend =  $row_search['id_usecase_extends'];
	 $t_page_usecase =  plugin_page( 'usecase_page' );
	 $t_page_usecase=$t_page_usecase."&id_usecase=".$usecase;
     $t_page_usecase_extend = $t_page_usecase."&id_usecase=".$usecase_extend;
     $id_cu = str_pad($usecase, 7, "0", STR_PAD_LEFT);
	 $id_cu_extend = str_pad($usecase_extend, 7, "0", STR_PAD_LEFT);
	 $name_parent= $row_search['name_parent'];
	 $name_child= $row_search['name_child'];
	 ?>


	 <tr <?php echo helper_alternate_class() ?>>
			<td width='50%'>
			<?php echo "<a href=\"$t_page_usecase\">".$id_cu."</a> - ".$name_parent;?>	
			</td>
			<td width='50%'>
			<?php echo "<a href=\"$t_page_usecase_extend\">".$id_cu_extend."</a> - ".$name_child;?>	
			</td>
		</tr>
	

<?php

}
?>
</table>
</div>
<?php


}


$t_repo_table_include = plugin_table( 'usecase_include', 'honey' );
$t_repo_table_usecase = plugin_table( 'usecase', 'honey' );

								$query_search = 'SELECT  e.id_usecase_parent, e.id_usecase_include, c.name name_child , cc.name name_parent
												  FROM '.$t_repo_table_include.' e, '.$t_repo_table_usecase.' c  , '.$t_repo_table_usecase.' cc
												  WHERE e.id_usecase_include = c.id and c.active=0 and c.id_derivation is not null
												  and cc.id_derivation is null
												  and e.id_usecase_parent = cc.id
												  order by e.id_usecase_parent , e.id_usecase_include';


$result_search = db_query_bound( $query_search, array() );

$count_cu_include = db_num_rows( $result_search );

if( $count_cu_include >0 ){
 
?>
 <div align="center">
<br><table class="width90">
<tr  class="row-category">
	<td colspan="2"> 
	<?php //echo plugin_lang_get('derivation_uc_be_erased');?>
	<?php echo plugin_lang_get('derivation_uc_include');?>
	</td>
</tr>



<tr  class="row-category">
<td ><?php echo plugin_lang_get('derivation_uc_be_erased');?></td>
<td><?php echo plugin_lang_get('uc_manual');?></td>
</tr>

<!-- <tr class="row-category"> <td>Existen actores referenciados por los casos de uso:</td></tr>-->
<?php

while($row_search = db_fetch_array( $result_search )){
     
	 $usecase = $row_search['id_usecase_parent'];
	 $usecase_include =  $row_search['id_usecase_include'];

     $t_page_usecase =  plugin_page( 'usecase_page' );
	 $t_page_usecase=$t_page_usecase."&id_usecase=".$usecase;
     $t_page_usecase_include= $t_page_usecase."&id_usecase=".$usecase_include;
     $id_cu = str_pad($usecase, 7, "0", STR_PAD_LEFT);
	 $id_cu_include = str_pad($usecase_include, 7, "0", STR_PAD_LEFT);
	 $name_parent= $row_search['name_parent'];
	 $name_child= $row_search['name_child'];
	 ?>


	 <tr <?php echo helper_alternate_class() ?>>
			<td width='50%'>
			<?php echo "<a href=\"$t_page_usecase_include\">".$id_cu_include."</a> - ".$name_child;?>	
			</td>
			<td width='50%'>
			<?php echo "<a href=\"$t_page_usecase\">".$id_cu."</a> - ".$name_parent;?>	
			</td>
		</tr>
	

<?php

}
?>
</table>
</div>
<?php
}

$t_tokens = session_get( 'form_security_tokens', array() );//recarga

//for($i=0;$i<10;$i++){var_dump($t_tokens[$i]);}//recarga




if( ($count_cu + $count + $count_cu_include) == 0 ){

if (!(in_array('honey',$t_tokens )) ) { //recarga	
	array_push($t_tokens, 'honey');//recarga
	session_set( 'form_security_tokens', $t_tokens );//recarga



/*Dejamos obsoleta la derivación activa del proyecto, casos de uso y actores generados por esa derivación*/

//me quedo con los ids de los casos de uso a borrar para desactivarles la relacion coon actores

//borrado CUs-actores
$t_repo_table2 = plugin_table( 'usecase', 'honey' );
$t_repo_table = plugin_table( 'usecase_actor', 'honey' );
$t_query_usecase = 'UPDATE '.$t_repo_table.' set active = 1
					where active = 0 AND id_usecase in (select id from '.$t_repo_table2.'
					where active = 0 AND id_derivation = '. db_param().')';
$g_result_delete_usecase = db_query_bound( $t_query_usecase, array( $id_derivation) );


//borrado CUs-escenarios
$t_repo_table2 = plugin_table( 'usecase', 'honey' );
$t_repo_table = plugin_table( 'scenario', 'honey' );
$t_query_usecase = 'UPDATE '.$t_repo_table.' set active = 1
					where active = 0 AND id_usecase in (select id from '.$t_repo_table2.'
					where active = 0 AND id_derivation = '. db_param().')';
$g_result_delete_usecase = db_query_bound( $t_query_usecase, array( $id_derivation) );

//borrado CUs-extends
$t_repo_table2 = plugin_table( 'usecase', 'honey' );
$t_repo_table = plugin_table( 'usecase_extend', 'honey' );
$t_query_usecase = 'UPDATE '.$t_repo_table.' set active = 1
					where active = 0 AND id_usecase_parent in (select id from '.$t_repo_table2.'
					where active = 0 AND id_derivation = '. db_param().')';
$g_result_delete_usecase = db_query_bound( $t_query_usecase, array( $id_derivation) );

//borrado CUs-includes
$t_repo_table2 = plugin_table( 'usecase', 'honey' );
$t_repo_table = plugin_table( 'usecase_include', 'honey' );
$t_query_usecase = 'UPDATE '.$t_repo_table.' set active = 1
					where active = 0 AND id_usecase_parent in (select id from '.$t_repo_table2.'
					where active = 0 AND id_derivation = '. db_param().')';
$g_result_delete_usecase = db_query_bound( $t_query_usecase, array( $id_derivation) );


//borrado CUs
$t_repo_table = plugin_table( 'usecase', 'honey' );
$t_query_usecase = 'UPDATE '.$t_repo_table.' set active = 1
					where active = 0 AND id_derivation = '. db_param();

$g_result_delete_usecase = db_query_bound( $t_query_usecase, array( $id_derivation) );

//borrado actores
$t_repo_table = plugin_table( 'actor', 'honey' );
$t_query_usecase = 'UPDATE '.$t_repo_table.' set active=1
					where active = 0 AND id_derivation = ' . db_param();

$g_result_delete_usecase=db_query_bound( $t_query_usecase, array( $id_derivation) );

//borrado derivación
$t_repo_table = plugin_table( 'derivation', 'honey' );
$t_query_usecase = 'UPDATE '.$t_repo_table.' set active=1
					where active= 0 AND id = ' . db_param();

$g_result_delete_usecase=db_query_bound( $t_query_usecase, array( $id_derivation) );



/*CREACION DE CASOS DE USO*/

//Buscamos los simbolos de tipo verbo

$type_verb = 4;

$t_repo_table_symbol = plugin_table( 'symbol', 'honey' );

								$query_search = 'SELECT *
												  FROM '.$t_repo_table_symbol.' 
												  WHERE type=' . db_param().'
												  AND id_project='. db_param().' AND active = 0';

$result_search = db_query_bound( $query_search, array($type_verb, $t_project_id) );

$count_verb = db_num_rows( $result_search );

$a=0;
$actores_id;
$symbol_actor;
$existenCondiciones = false;

print_lel_menu();

if ($count_verb  == 0){

	//print_lel_menu();

?>

<div align="center">
<table class="width90">
<tr class="row-category">
<td> 
<?php echo lang_get( 'plugin_Honey_usecase_derivation_title' ) ?>
</td>
</tr>
<tr class="row-category">
<td class="form-title" colspan="2"> 
	<?php echo lang_get( 'plugin_Honey_usecase_derivation' ) ?>
</td>
</tr>
</table>
</form>
<?php
html_page_bottom1( );
};

if ($count_verb > 0){
    
     /*Insert de info de la derivación*/

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

			$symbol_actor[$a]=$row_search_actor['id'];
			
			if($count_actors==0){//si no existe el actor
		
				//actor insert
				$t_repo_table_actor = plugin_table( 'actor', 'honey' );

				$t_query_actor = 'INSERT INTO '.$t_repo_table_actor.' (name, description, id_derivation, id_symbol, id_project)
							VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ','. db_param() . ','. db_param() . ')';

				$g_result_insert_actor=db_query_bound( $t_query_actor, array( $actor_name, $actor_description, $id_derivation, $id_symbol_subject, $t_project_id));

				$id_actor_insert=mysql_insert_id();

				$actores[$a] = strtoupper($actor_name);
				$actores_id[$a] = $id_actor_insert;
				
			}
			 if ($count_actors>0){ //si el actor existe

				 //me guardo los datos para relacionarlo con el nuevo caso de uso
				 $actores[$a] = strtoupper($row_name);
				 $actores_id[$a] =$row['id'];
				
			 }
				$a=$a+1;


		}

     /*FIN CREACION DE ACTORES*/ 

     /*INSERT DE CU´s*/ 
        
	    //matriz con el texto de las condiciones por cada verbo
        $matrizVerbo;

	   //matriz con el ID de las condiciones por cada verbo
        $matrizCondiciones;

	   $x=0;
	  //por cada verbo
		while($row_search  = db_fetch_array( $result_search )){

			$verb_id = $row_search['id'];
			
			$uc_name =  $row_search['name'];
	 
			//la noción en el verbo del LEL es el objetivo del CU
			$uc_goal = $row_search['notion'];

			$postconditions = "";
			$observations = "";
			$precondition = "";

			$t_repo_table = plugin_table( 'usecase', 'honey' );

			$t_query_symbol = 'INSERT INTO '.$t_repo_table.' (name, goal, postconditions, observations, preconditions, id_derivation, id_symbol, id_project)
						VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' , ' . db_param() . ' , ' . db_param() . ')';

			$g_result_insert_symbol=db_query_bound( $t_query_symbol, array( $uc_name, $uc_goal, $postconditions, $observations, $precondiciones, $id_derivation, $verb_id, $t_project_id)  );

			$id_usecase=mysql_insert_id();
			
			//Creación de escenario principal

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
			
		//por cada simbolo de tipo verbo
		//vinculamos los actores: Si entre sus impactos tiene el nombre del actor insertamos un registro en usecase_actor

		 actors_usecase($actores, $verb_id, $actores_id, $id_usecase, $symbol_actor);

	/* CONDICIONES DEL CASO DE USO */
    
	$preconditions;
	$nextPrecondition=0;

    //buscamos los impactos del simbolo de tipo estado que tienen en su descripcion el string del nombre del verbo o cualquiera de sus sinónimos.

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

	

			//	print_lel_menu();

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
				<?php echo plugin_lang_get('derivation_not_condition');?>
				</td>
				<td>
				<?php echo plugin_lang_get('derivation_pre_condition');?>
				</td>
				<td>
				<?php echo plugin_lang_get('derivation_post_condition');?>
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
				<input type="radio" name="cond_<?php echo $id_usecase.$a?>" id="cond_<?php echo $id_usecase.$a?>" value="condicion" checked/>
			</td>

	        <td>
				<input type="radio"  name="cond_<?php echo $id_usecase.$a?>" id="cond_<?php echo $id_usecase.$a?>" value="precondicion"/>
				<input type="hidden" name="id_cond_<?php echo $id_usecase.$a?>" id="id_cond_<?php echo $id_usecase.$a?>" value="<?php echo $matrizVerbo[$uc_name][$a]?>"/>
			</td>

	        <td>
				<input type="radio"  name="cond_<?php echo $id_usecase.$a?>" id="cond_<?php echo $id_usecase.$a?>" value="postcondicion"/>
				<input type="hidden" name="id_cond_<?php echo $id_usecase.$a?>" id="id_cond_<?php echo $id_usecase.$a?>" value="<?php echo $matrizVerbo[$uc_name][$a]?>"/>
			</td>

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

}


html_page_bottom1( );


if ($existenCondiciones == false){

$t_url=plugin_page('view_cu_page');
header( "Location: $t_url" );
}


}//recarga
else{
	
	//echo "mal";//recarga
	//$t_tokens=array();//recarga
	//session_set( 'form_security_tokens', $t_tokens );//recarga

	//for($i=0;$i<10;$i++){var_dump($t_tokens[$i]);}//recarga
	trigger_error( ERROR_FORM_TOKEN_INVALID, ERROR );//recarga

}
	
}// fin if ($count > 0)


