<?php

$total_pag_rules = ceil($count_all_rules / $TAMANO_PAGINA);

//busco todos los actores y limito por grupo a mostrar

$t_repo_table7 = plugin_table( 'rule', 'honey' );
$ini=0;
$j=0;

for($h=0;$h<$total_pag_rules;$h++){

	$query_actors_limit = 'SELECT b.name , b.id, b.description
					 FROM '.$t_repo_table7.' b
					 where id_project=' . db_param().'
					AND b.active = 0
					 limit '  . $ini . ' , '. $TAMANO_PAGINA;

	$result_actors_limit = db_query_bound( $query_actors_limit, array($id_project) );
	$count_actors_limit = db_num_rows( $result_actors_limit );

	//busco las reglas del CU

	$t_repo_table = plugin_table( 'rule_usecase', 'honey' );
	$t_repo_table2 = plugin_table( 'rule', 'honey' );

	$query_rules_usecase = 'SELECT b.name , b.id, b.description
							 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id_rule=b.id)
							 where id_usecase=' . db_param().'
							AND b.active = 0';
	$result_rules_usecase = db_query_bound( $query_rules_usecase, array($id_usecase) );
	$count_rules_limit = db_num_rows( $result_rules_usecase );

	?>

	<?php if($h==0){?>
	<div id="capa_rule<?php echo $h?>">
	<?php } 
	else{?>
	<div id="capa_rule<?php echo $h?>" style="display:none">
	<?php }?>
	<table>

	<?php
	
	while( $row_all_rules_limit = db_fetch_array( $result_actors_limit )){
			$ck=false;
			$result_rules_usecase = db_query_bound( $query_rules_usecase, array($id_usecase) );
			while( $row_rules_usecase = db_fetch_array( $result_rules_usecase )){
				if($row_rules_usecase['id']==$row_all_rules_limit['id']){
					$ck=true;
				}
			}//while?>
			<table>
				<tr>
					<td>
						<?php if($ck==false){?>
							<input type="checkbox" name="ck_rule_<?php echo $j?>" id="ck_rule_<?php echo $j?>"/>
						<?php }else{ ?>
								<input type="checkbox" name="ck_rule_<?php echo $j?>" id="ck_rule_<?php echo $j?>" checked/>
						<?php } ?>
						<input type="hidden" name="id_rule_<?php echo $j?>" id="id_rule_<?php echo $j?>" value="<?php echo $row_all_rules_limit['id'] ?>"/>
					</td>
					<td><?php echo $row_all_rules_limit['name'] ?></td>	
						<?php if( $row_all_rules_limit['description']!=''){?>
					<td>
							<!---->
							<?php
							$c_id = $row_all_rules_limit['id'];
							echo "<script type=\"text/javascript\" language=\"JavaScript\">
								<!--
								function swap_content3( span ) {
								displayType = ( document.getElementById( span ).style.display == 'none' ) ? '' : 'none';
								document.getElementById( span ).style.display = displayType;
								}
									 -->
								 </script>";
							echo " <span id=\"hideSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content3(\"hideSection_" . $c_id . "\");swap_content3(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('show_goal')."</a>]</span>";
							echo " <span style='display:none' id=\"showSection_$c_id\">[<a class=\"small\" href='#' id='attmlink_" . $c_id . "' onclick='swap_content3(\"hideSection_" . $c_id . "\");swap_content3(\"showSection_" . $c_id . "\");return false;'>". plugin_lang_get('hide_goal')."</a>]";
								echo "<pre>";

								echo $row_all_rules_limit['description'];


								echo "</pre></span>\n";

								?>
							<!---->
					</td>	
						<?php }
						else { echo "<td  class='small'>[". plugin_lang_get('no_goal')."]</td>";}?>
					</tr>
	<?php			
		$j++;
		}	
	?>
	</table>

</div>
<?php $ini=$ini+ $TAMANO_PAGINA;}//for ?>

<table align='center'>
<tr><td>
<p id='texto4'><?php echo plugin_lang_get('page');?> 1 <a style="text-decoration:none" href="javascript:mostrarCapaRule( <?php echo 2?>, <?php echo $total_pag_rules;?>, '<?php echo plugin_lang_get('page');?>' )";><?php echo $i;?> >> </a></p>
</td></tr>
</table>