<?php

$result_all_ucs = db_query_bound( $query_all_ucs, array($id_project, $id_usecase) );
$count_all_ucs = db_num_rows( $result_all_ucs);	
$total_pag_extends = ceil($count_all_ucs / $TAMANO_PAGINA);

//busco todos los actores y limito por grupo a mostrar

$t_repo_table2 = plugin_table( 'usecase', 'honey' );

$ini=0;
$j=0;

for($h=0;$h<$total_pag_extends;$h++){


	$query_parents_limit = 'SELECT b.id, b.name , b.goal
						 FROM '.$t_repo_table2.' b
						 where id_project=' . db_param().'
						 AND b.active = 0 and id!=' . db_param().'
						 limit '  . $ini . ' , '. $TAMANO_PAGINA;


	if($h==0){?>
		<div id="capa_extends<?php echo $h?>">
		<?php  } 
		else{?>
		<div id="capa_extends<?php echo $h?>" style="display:none">
		<?php  }

		//if( ON == config_get( 'use_javascript' ) ) { ?>
			<?php //collapse_open( 'profile_ex' ); collapse_icon('profile_ex'); echo  plugin_lang_get('extends');}
				
			$t_repo_table = plugin_table( 'usecase_extend', 'honey' );
			$t_repo_table2 = plugin_table( 'usecase', 'honey' );

			$query_parents = 'SELECT b.id, b.name , b.goal
				              FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id_usecase_parent=b.id)
							  where id_usecase_extends=' . db_param().'
							  AND a.active = 0 AND b.active = 0';
?>
		<table>

		<?php

			$result_parents_limit = db_query_bound( $query_parents_limit, array($id_project,$id_usecase) );
			$count_parents_limit = db_num_rows( $result_parents_limit );
			
			while( $row_all_ucs = db_fetch_array( $result_parents_limit )){
				
				$ck=false;
				$result_parents = db_query_bound( $query_parents, array($id_usecase) );
				while( $row_parents = db_fetch_array( $result_parents )){
					if($row_parents['id']==$row_all_ucs['id']){
						$ck=true;
					}
				}?>
			
			<table>
				<tr>
					<td>
						<?php if($ck==false){?>
							<input type="checkbox" name="ck_extends_<?php echo $j?>" id="ck_extends_<?php echo $j?>"/>
						<?php }else{ ?>
								<input type="checkbox" name="ck_extends_<?php echo $j?>" id="ck_extends_<?php echo $j?>" checked/>
						<?php } ?>
							<input type="hidden" name="id_extends_<?php echo $j?>" id="id_extends_<?php echo $j?>" value="<?php echo $row_all_ucs['id'] ?>"/>
					</td>
					<td><?php echo $row_all_ucs['name'] ?></td>
						<?php if( $row_all_ucs['goal']!=''){?>
					<td>
							<!---->
								<?php
								$c_id = $row_all_ucs['id'];
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

								echo $row_all_ucs['goal'];


								echo "</pre></span>\n";

								?>
							<!---->
					</td>
							<?php }//if
							else { echo "<td  class='small'>[". plugin_lang_get('no_goal')."]</td>";}?>					
				</tr>
				<?php $j++;	
				}//while ?>
			</table>
			

			<?php //if($count_all_ucs==0){echo "<p class='category'> ". plugin_lang_get('no_uc_created')."<p>";}?>
			<?php //if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php //collapse_closed( 'profile_ex' ); collapse_icon('profile_ex'); echo plugin_lang_get('extends');?>
				<?php //collapse_end( 'profile_ex' ); ?>
			<?php// } ?>

		</div>

<?php $ini=$ini+ $TAMANO_PAGINA;}//for ?>

<table align='center'>
<tr><td>
<p id='texto2'> 1 <a style="text-decoration:none" href="javascript:mostrarCapaExtends( <?php echo 2?>, <?php echo $total_pag_extends;?> )";><?php echo $i;?> >> </a></p>
</td></tr>
</table>