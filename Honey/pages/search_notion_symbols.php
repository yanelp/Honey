<?php
$t_repo_table = plugin_table( 'symbol', 'honey' );

$query_notion = 'SELECT notion
				 FROM '.$t_repo_table.' 
				 where id=' . db_param();

$result_notion = db_query_bound( $query_notion, array($id_symbol) );
$count_notion = db_num_rows( $result_notion );


if($count_notion>0){?>
	<tr>
		<td>Notion</td>
		<td>
	<?php while( $row_notion = db_fetch_array( $result_notion ) ){

				$words = preg_split("/[\s]+/", $row_notion['notion']);
				$num_words=sizeof($words);
				//agregar al vector todas las combinaciones posibles
				//ej:1-2-3-4-5
				//12-123-1234-12345
				//23-234-2345
				//34-345
				//45
				
				for($r=0;$r<$num_words;$r++){//5
				//for($r=$num_words;$r>0;$r--){//5
				  $i=1;
					while($i<=$num_words){//por cada palabra del impacto//5
						$new_word='';
						$j=$r;
						
						while($j<$i){//desde el ppio hasta el final
							
						
								$new_word=trim($new_word." ".$words[$j]);
								if (in_array($new_word, $words)==false) {//si no está y es un simbolo lo agrego al vector

									//busco si es un simbolo*****

									//serch word in bd
								$t_repo_table1 = plugin_table( 'symbol', 'honey' );
								$t_repo_table2 = plugin_table( 'synonymous', 'honey' );

								$query_search = 'SELECT id
												  FROM '.$t_repo_table1.' 
												  WHERE name=' . db_param() .'
												  UNION
												  SELECT id_symbol as id
												  FROM '.$t_repo_table2.' 
												  WHERE name=' . db_param();

								$result_search = db_query_bound( $query_search, array($new_word, $new_word) );
								$count_search = db_num_rows( $result_search );
								$row_search = db_fetch_array( $result_search ) ;
								
								if($row_search['id']!=''){

									array_push($words,$new_word);}
								}
							
								
							$j++;
						}
						
					$i++;
					
					}
				}//for*/
				//print_r($words);
				?>
				<table>
					<tr>
						<td>
							<?php
							$num_words=sizeof($words);
							//$cant_simbolos=0;
							$frase=$row_notion['notion'];
							//for($j=0;$j<$num_words;$j++){
							for($j=$num_words;$j>0;$j--){//5
							
								//serch word in bd
								$t_repo_table1 = plugin_table( 'symbol', 'honey' );
								$t_repo_table2 = plugin_table( 'synonymous', 'honey' );

								$query_search = 'SELECT id
												  FROM '.$t_repo_table1.' 
												  WHERE name=' . db_param() .'
												  UNION
												  SELECT id_symbol as id
												  FROM '.$t_repo_table2.' 
												  WHERE name=' . db_param();

								$result_search = db_query_bound( $query_search, array($words[$j], $words[$j]) );
								$count_search = db_num_rows( $result_search );
								$row_search = db_fetch_array( $result_search ) ;
								$id_symbol_search=$row_search['id'];
								$t_page= plugin_page( 'symbol_page' );	;
								$t_page=$t_page."&id_symbol=".$id_symbol_search;

								if($count_search>0){//coincidence found

									$link="<a href=\"$t_page\">".$words[$j]."</a>";
									
									$palabra=$words[$j];
									
									//Expediente->expediente Simple (estado)
									//cuando busca expediente simple encuentra <a>expediente</a> simple
									//como primero pongo los compuestos, si ya está el compuesto el simple no se pone
									

									//busco la posicion de la palabra buscada
									$pos=strrpos($frase, $palabra);
									
									//busco el elemento en la pos anterior a la palabra buscada
									$elem=$frase[$pos-1];
									//echo $elem;

									//si es > no reemplazo nada

									if($elem!='>'){
										//reemplaza la palabra o frase buscada con el link dentro de una oracion
										$frase=str_replace($palabra, $link,$frase);
									}

									
								}
								
							 }//for
							 echo $frase;
							 ?>

						</td>
					</tr>
				</table>

		
	<?php }//while ?>
		</td>
	</tr>
<?php } 
