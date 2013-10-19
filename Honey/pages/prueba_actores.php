
<?php
/*nuevo*/

//Limito la busqueda
$TAMANO_PAGINA = 2;

//examino la página a mostrar y el inicio del registro a mostrar
$pagina = $_GET["pagina"];
if (!$pagina) {
    $inicio = 0;
    $pagina=1;
}
else {
    $inicio = ($pagina - 1) * $TAMANO_PAGINA;
} 

//busco todos los actores

$t_repo_table = plugin_table( 'actor', 'honey' );


$query_actors_limit = 'SELECT a.name , a.id, a.description
				 FROM '.$t_repo_table.' a 
				 where a.id_project=' . db_param().'
				 AND a.active = 0
				 limit '  . $inicio . ' , '. $TAMANO_PAGINA;
$result_actors_limit = db_query_bound( $query_actors_limit, array($id_project) );
$count_actors_limit = db_num_rows( $result_actors_limit );



$total_paginas = ceil($count_all_actors / $TAMANO_PAGINA);

//pongo el número de registros total, el tamaño de página y la página que se muestra
/*echo "Número de registros encontrados: " . $count_all_actors . "<br>";
echo "Se muestran páginas de " . $TAMANO_PAGINA . " registros cada una<br>";
echo "Mostrando la página " . $pagina . " de " . $total_paginas . "<br>"; 
echo "total:  " . $total_paginas . "<br>"; */

?>

<table>
				<?php 

				$t_repo_table = plugin_table( 'actor', 'honey' );
				$t_repo_table2 = plugin_table( 'usecase_actor', 'honey' );

				$query_actors = 'SELECT a.name , a.id, a.description
								 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id=b.id_actor)
								 where b.id_usecase=' . db_param().'
								 AND a.active = 0 AND b.active = 0';

				$result_actors = db_query_bound( $query_actors, array($id_usecase) );
				$count_actors = db_num_rows( $result_actors );

				$j=0;

				
				while( $row_all_actors = db_fetch_array( $result_actors_limit )){
				
					$ck=false;
					$result_actors = db_query_bound( $query_actors, array($id_usecase) );
					while( $row_actors = db_fetch_array( $result_actors )){
						if($row_actors['id']==$row_all_actors['id']){
							$ck=true;
						}
					}?>
				<tr>
						<td>
							<?php if($ck==false){?>
								<input type="checkbox" name="ck_actor_<?php echo $j?>" id="ck_actor_<?php echo $j?>"/>
							<?php }else{ ?>
									<input type="checkbox" name="ck_actor_<?php echo $j?>" id="ck_actor_<?php echo $j?>" checked/>
							<?php } ?>
							<input type="hidden" name="id_actor_<?php echo $j?>" id="id_actor_<?php echo $j?>" value="<?php echo $row_all_actors['id'] ?>"/>
						</td>
							<td><?php echo $row_all_actors['name'] ?></td>

<?php if( $row_all_actors['description']!=''){?>
							<td>
									<!---->
								<?php
								$c_id = $row_all_actors['id'];
								echo "<script type=\"text/javascript\" language=\"JavaScript\">
									<!--
									function swap_content77( span ) {
									displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
									document.getElementById( span ).style.display = displayType;
									}

									 -->
									 </script>";
							echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content77(\"hideSection_" . $c_id . "\");swap_content77(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content77(\"hideSection_" . $c_id . "\");swap_content77(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_all_actors['description'];


								echo "</pre></span>\n";

								?>
							<!---->
							</td>
							<?php }
							else { echo "<td  class='small'>[". plugin_lang_get('no_goal')."]</td>";}?>


				</tr>
				<?php $j++;	
				} ?>

			</table>

<br>
<table>
<tr></td>
<?php //muestro los distintos índices de las páginas, si es que hay varias páginas
if ($total_paginas > 1){
    for ($i=1;$i<=$total_paginas;$i++){
       if ($pagina == $i){
          //si muestro el índice de la página actual, no coloco enlace
          echo $pagina . " ";}
       else{
          //si el índice no corresponde con la página mostrada actualmente, coloco el enlace para ir a esa página
		$t_page_limit=plugin_page('update_usecase_page');
		$t_page_limit=$t_page_limit."&id_usecase=".$id_usecase;

          echo "<a href='$t_page_limit&pagina=" . $i. "#uc_actors'>" . $i . "</a> ";
		}
    }//for
}//if
?>
</td></tr></table>