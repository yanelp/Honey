<?php 
			
//busco todas las reglas del proyecto

$t_repo_table = plugin_table( 'rule', 'honey' );

$query_rules = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id_project=' . db_param().' and active=0';

$result_rules = db_query_bound( $query_rules, array($project_id) );
$count_rules = db_num_rows( $result_rules );

$total_pag_rules = ceil($count_rules/ $TAMANO_PAGINA);

//busco todos los actores y limito por grupo a mostrar

$t_repo_table = plugin_table( 'rule', 'honey' );
$ini=0;
$j=0;

for($h=0;$h<$total_pag_rules;$h++){

	$query_actors_limit = 'SELECT *
					 FROM '.$t_repo_table.' a 
					 where a.id_project=' . db_param().'
					 AND a.active = 0
					 limit '  . $ini . ' , '. $TAMANO_PAGINA;

	$result_actors_limit = db_query_bound( $query_actors_limit, array($id_project) );
	$count_actors_limit = db_num_rows( $result_actors_limit );

	?>

	<?php if($h==0){?>
	<div id="capa_rule<?php echo $h?>">
	<?php } 
	else{?>
	<div id="capa_rule<?php echo $h?>" style="display:none">
	<?php }?>
	<table>
		<?php while( $row_rules = db_fetch_array( $result_actors_limit )){?>
		<tr>
			<td><input type="checkbox" name="ck_rule_<?php echo $j?>"/>
				<input type="hidden" name="id_rule_<?php echo $j?>" id="id_rule_<?php echo $j?>" value="<?php echo $row_rules['id'] ?>"/></td>
			<td><?php echo $row_rules['name'] ?></td>

			<?php if( $row_rules['description']!=''){?>
					<td>
								<!---->
								<?php
								$c_id = $row_rules['id'];
								echo "<script type=\"text/javascript\" language=\"JavaScript\">
									<!--
									function swap_content3( span ) {
									displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
									document.getElementById( span ).style.display = displayType;
									}

									 -->
									 </script>";
							echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content3(\"hideSection_" . $c_id . "\");swap_content3(\"showSection_" . $c_id . "\");return false;'>".plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content3(\"hideSection_" . $c_id . "\");swap_content3(\"showSection_" . $c_id . "\");return false;'>".plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_rules['description'];


								echo "</pre></span>\n";

								?>
							<!---->
							</td>	
							<?php }
							else { echo "<td  class='small'>[".plugin_lang_get('no_goal')."]</td>";}?>

				</tr>
				<?php
				$j++;
				} ?>
			</table>

			<?php if($count_rules==0){echo "<p class='category'> ".plugin_lang_get('no_rules_created')."<p>";}?>

			</div>
<?php 

$ini=$ini+ $TAMANO_PAGINA;}//for ?>

<table align='center'>
<tr><td>
<p id='texto4'><?php echo plugin_lang_get('page');?> 1 <a style="text-decoration:none" href="javascript:mostrarCapaRule( <?php echo 2?>, <?php echo $total_pag_rules;?>, '<?php echo plugin_lang_get('page');?>' )";><?php echo $i;?> >> </a></p>
</td></tr>
</table>	