<?php 
			
//busco todas las reglas del proyecto

$t_repo_table = plugin_table( 'usecase', 'honey' );

$query_includes = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id_project=' . db_param().' and active=0';

$result_includes = db_query_bound( $query_includes, array($project_id) );
$count_includes = db_num_rows( $result_includes );


$total_pag_in = ceil($count_includes/ $TAMANO_PAGINA);

//busco todos los actores y limito por grupo a mostrar

$t_repo_table = plugin_table( 'usecase', 'honey' );
$ini=0;
$j=0;

for($h=0;$h<$total_pag_in;$h++){

	$query_actors_limit = 'SELECT *
					 FROM '.$t_repo_table.' a 
					 where a.id_project=' . db_param().'
					 AND a.active = 0
					 limit '  . $ini . ' , '. $TAMANO_PAGINA;

	$result_actors_limit = db_query_bound( $query_actors_limit, array($id_project) );
	$count_actors_limit = db_num_rows( $result_actors_limit );

	?>

	<?php if($h==0){?>
	<div id="capa_includes<?php echo $h?>">
	<?php } 
	else{?>
	<div id="capa_includes<?php echo $h?>" style="display:none">
	<?php }?>

	<table>
		<?php while( $row_includes = db_fetch_array( $result_actors_limit )){?>
		<tr>
			<td><input type="checkbox" name="ck_includes_<?php echo $j?>"/>
				<input type="hidden" name="id_includes_<?php echo $j?>" id="id_includes_<?php echo $j?>" value="<?php echo $row_includes['id'] ?>"/>
			</td>
			<td><?php echo $row_includes['name'] ?></td>

			<?php if( $row_includes['goal']!=''){?>
						<td>
							<!---->
								<?php
								$c_id2 = $row_includes['id']."algo";
								echo "<script type=\"text/javascript\" language=\"JavaScript\">
									<!--
									function swap_content6( span ) {
									displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
									document.getElementById( span ).style.display = displayType;
									}

									 -->
									 </script>";
							echo " <span id=\"hideSection_$c_id2\">[<a class=\"small\" href='#' id='attmlink_" . $c_id2 . "' onclick='swap_content6(\"hideSection_" . $c_id2 . "\");swap_content6(\"showSection_" . $c_id2 . "\");return false;'>". plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id2\">[<a class=\"small\" href='#' id='attmlink_" . $c_id2 . "' onclick='swap_content6(\"hideSection_" . $c_id2 . "\");swap_content6(\"showSection_" . $c_id2 . "\");return false;'>". plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_includes['goal'];


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

			<?php if($count_includes==0){echo "<p class='category'> ".plugin_lang_get('no_uc_created')."<p>";}?>

</div>
<?php 

$ini=$ini+ $TAMANO_PAGINA;}//for ?>

<?php if($count_includes>$TAMANO_PAGINA){?>
<table align='center'>
<tr><td>
<p id='texto3'><?php echo plugin_lang_get('page');?> 1 <a style="text-decoration:none" href="javascript:mostrarCapaIncludes( <?php echo 2?>, <?php echo $total_pag_in;?>, '<?php echo plugin_lang_get('page');?>' )";><?php echo $i;?> >> </a></p>
</td></tr>
</table>
<?php } ?>
