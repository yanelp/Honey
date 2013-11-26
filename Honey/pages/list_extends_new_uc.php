<?php 
			
//busco todas las reglas del proyecto

$t_repo_table = plugin_table( 'usecase', 'honey' );

$query_extends = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id_project=' . db_param().' and active=0';

$result_extends = db_query_bound( $query_extends, array($project_id) );
$count_extends = db_num_rows( $result_extends );


$total_pag_ex = ceil($count_extends / $TAMANO_PAGINA);

//busco todos los actores y limito por grupo a mostrar

$t_repo_table = plugin_table( 'usecase', 'honey' );
$ini=0;
$j=0;

for($h=0;$h<$total_pag_ex;$h++){

	$query_actors_limit = 'SELECT *
					 FROM '.$t_repo_table.' a 
					 where a.id_project=' . db_param().'
					 AND a.active = 0
					 limit '  . $ini . ' , '. $TAMANO_PAGINA;

	$result_actors_limit = db_query_bound( $query_actors_limit, array($id_project) );
	$count_actors_limit = db_num_rows( $result_actors_limit );

	?>

	<?php if($h==0){?>
	<div id="capa_extends<?php echo $h?>">
	<?php } 
	else{?>
	<div id="capa_extends<?php echo $h?>" style="display:none">
	<?php }?>

	<table>
		<?php while( $row_extends = db_fetch_array( $result_actors_limit )){?>
		<tr>
			<td><input type="checkbox" name="ck_extends_<?php echo $j?>"/>
				<input type="hidden" name="id_extends_<?php echo $j?>" id="id_extends_<?php echo $j?>" value="<?php echo $row_extends['id'] ?>"/>
			</td>
			<td><?php echo $row_extends['name'] ?></td>

			<?php if( $row_extends['goal']!=''){?>
						<td>
						<!---->
								<?php
								$c_id = $row_extends['id'];
								echo "<script type=\"text/javascript\" language=\"JavaScript\">
									<!--
									function swap_content5( span ) {
									displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
									document.getElementById( span ).style.display = displayType;
									}

									 -->
									 </script>";
							echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content5(\"hideSection_" . $c_id . "\");swap_content5(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content5(\"hideSection_" . $c_id . "\");swap_content5(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_extends['goal'];


								echo "</pre></span>\n";

								?>
							<!---->
							</td>
							<?php }
							else { echo "<td class='small'>[".plugin_lang_get('no_goal')."]</td>";}?>	

				</tr>
				<?php
				$j++;
				} ?>
			</table>

			<?php if($count_extends==0){echo "<p class='category'> ".plugin_lang_get('no_uc_created')."<p>";}?>

</div>
<?php 

$ini=$ini+ $TAMANO_PAGINA;}//for ?>

<table align='center'>
<tr><td>
<p id='texto2'><?php echo plugin_lang_get('page');?> 1 <a style="text-decoration:none" href="javascript:mostrarCapaExtends( <?php echo 2?>, <?php echo $total_pag_ex;?>, '<?php echo plugin_lang_get('page');?>' )";><?php echo $i;?> >> </a></p>
</td></tr>
</table>
