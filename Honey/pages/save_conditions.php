<?php

$cant_cus=$_REQUEST['cant_cu'];

echo plugin_lang_get('cant_uc').$cant_cus;

 for ($i = 0; $i <= $cant_cus; $i++) {
 	 $cu = $_REQUEST['cu_'.$i];

	 if ($cu != ""){
           
			$cant_conditions=$_REQUEST['condiciones_'.$i];
			
			$textPrecondition="";
			$textPostcondition="";

			for ($a = 0; $a <= $cant_conditions; $a++) {
				    
					if($_REQUEST['cond_'.$cu.$a]=='precondicion'){
						 $precondition = $_REQUEST['id_cond_'.$cu.$a];					
						
						 if ($textPrecondition==""){
						       $textPrecondition = $precondition.'<br>';
	
						 }
						 else{
						 $textPrecondition = $textPrecondition.'<br>'.$precondition.'<br>';
						 
						 }

						 
						
						} //fin if

					if($_REQUEST['cond_'.$cu.$a]=='postcondicion'){

						 $postcondition = $_REQUEST['id_cond_'.$cu.$a];					
					
							if ($textPostcondition==""){
									   $textPostcondition = $postcondition.'<br>';
								 }
								 else{
									 $textPostcondition =  $textPostcondition.'<br>'.$postcondition.'<br>';
								 }
					}//fin if
	 
	       } //fin for CONDITIONS

			//INSERT DE CONDICIONES POR CU
    
		    if ($textPrecondition !=""){
			
				$t_repo_table = plugin_table( 'usecase', 'honey' );
				
				$t_query_usecase = 'UPDATE '.$t_repo_table.' set preconditions= ' . db_param() . '
									where id= ' . db_param() . '';

				$g_result_insert_usecase=db_query_bound( $t_query_usecase, array( $textPrecondition, $cu));

			}
			
			if ($textPostcondition !=""){
			
				$t_repo_table = plugin_table( 'usecase', 'honey' );
				
				$t_query_usecase = 'UPDATE '.$t_repo_table.' set postconditions= ' . db_param() . '
									where id= ' . db_param() . '';

				$g_result_insert_usecase=db_query_bound( $t_query_usecase, array( $textPostcondition, $cu));

			}


	
	
	}//fin if ($cu != "")

 }//fin for


$t_url=plugin_page('view_cu_page');
header( "Location: $t_url" );

?>