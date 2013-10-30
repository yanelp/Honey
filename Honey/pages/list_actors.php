<?php

$total_pag_actors = ceil($count_all_actors / $TAMANO_PAGINA);

//busco todos los actores y limito por grupo a mostrar

$t_repo_table = plugin_table( 'actor', 'honey' );
$ini=0;
$t=0;//para todos los actores
for($h=0;$h<$total_pag_actors;$h++){

	$query_actors_limit = 'SELECT a.name , a.id, a.description
					 FROM '.$t_repo_table.' a 
					 where a.id_project=' . db_param().'
					 AND a.active = 0
					 limit '  . $ini . ' , '. $TAMANO_PAGINA;

	$result_actors_limit = db_query_bound( $query_actors_limit, array($id_project) );
	$count_actors_limit = db_num_rows( $result_actors_limit );

	//busco los actores asociados al uc

	$t_repo_table = plugin_table( 'actor', 'honey' );
	$t_repo_table2 = plugin_table( 'usecase_actor', 'honey' );

	$query_actors = 'SELECT a.name , a.id, a.description
								 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id=b.id_actor)
								 where b.id_usecase=' . db_param().'
								 AND a.active = 0 AND b.active = 0';

	$result_actors = db_query_bound( $query_actors, array($id_usecase) );
	$count_actors = db_num_rows( $result_actors );

	?>

	<?php if($h==0){?>
	<div id="capa_actor<?php echo $h?>">
	<?php } 
	else{?>
	<div id="capa_actor<?php echo $h?>" style="display:none">
	<?php }?>

	<table>
	<?php

		while( $row_actors_limit = db_fetch_array( $result_actors_limit )){
		
					$ck=false;
					$result_actors = db_query_bound( $query_actors, array($id_usecase) );
					while( $row_actors = db_fetch_array( $result_actors )){
						if($row_actors['id']==$row_actors_limit['id']){
							$ck=true;
						}

					}?>
				<tr>
						<td>
							<?php if($ck==false){?>
								<input type="checkbox" name="ck_actor_<?php echo $t?>" id="ck_actor_<?php echo $t?>"/>
							<?php }else{ ?>
									<input type="checkbox" name="ck_actor_<?php echo $t?>" id="ck_actor_<?php echo $t?>" checked/>
							<?php } ?>
							<input type="hidden" name="id_actor_<?php echo $t?>" id="id_actor_<?php echo $t?>" value="<?php echo $row_actors_limit['id'] ?>"/>
						</td>
							<td><?php echo $row_actors_limit['name']; ?></td>

							<!--MUESTRA CONTENIDO-->
							<?php if( $row_actors_limit['description']!=''){?>
							<td>
								
								<?php
								$c_id = $row_actors_limit['id'];
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

								echo $row_actors_limit['description'];


								echo "</pre></span>\n";

								?>
							</td>
							<?php }
							else { echo "<td  class='small'>[". plugin_lang_get('no_goal')."]</td>";}?>
						<!--FIN MUESTRA CONTENIDO-->
		</tr>
	<?php			
		$t++;
		}	
	?>
	</table>

</div>
<?php 

$ini=$ini+ $TAMANO_PAGINA;}//for ?>

<table align='center'>
<tr><td>
<p id='texto'> 1 <a style="text-decoration:none" href="javascript:mostrarCapaActor( <?php echo 2?>, <?php echo $total_pag_actors;?> )";><?php echo $i;?> >> </a></p>
</td></tr>
</table>
