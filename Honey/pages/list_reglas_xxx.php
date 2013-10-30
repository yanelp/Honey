	<!--aca van las reglas-->
	  <tr <?php echo helper_alternate_class() ?>>
		<td colspan="2" class="none">
			<?php 
			if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_open( 'profile3' ); collapse_icon('profile3'); echo 'Rules';}

				$t_repo_table = plugin_table( 'rule_usecase', 'honey' );
				$t_repo_table2 = plugin_table( 'rule', 'honey' );

				$query_rules_usecase = 'SELECT b.name , b.id, b.description
								 FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id_rule=b.id)
								 where id_usecase=' . db_param().'
								AND b.active = 0';

				$j=0;
				while( $row_all_rules = db_fetch_array( $result_all_rules )){
					$ck=false;
					$result_rules_usecase = db_query_bound( $query_rules_usecase, array($id_usecase) );
					while( $row_rules_usecase = db_fetch_array( $result_rules_usecase )){
						if($row_rules_usecase['id']==$row_all_rules['id']){
							$ck=true;
						}
					}?>
				<table>
				<tr>
						<td>
							<?php if($ck==false){?>
								<input type="checkbox" name="ck_rule_<?php echo $j?>" id="ck_rule_<?php echo $j?>"/>
							<?php }else{ ?>
									<input type="checkbox" name="ck_rule_<?php echo $j?>" id="ck_rule_<?php echo $j?>" checked/>
							<?php } ?>
							<input type="hidden" name="id_rule_<?php echo $j?>" id="id_rule_<?php echo $j?>" value="<?php echo $row_all_rules['id'] ?>"/>
						</td>
							<td><?php echo $row_all_rules['name'] ?></td>	
							<?php if( $row_all_rules['description']!=''){?>
							<td>
								<!---->
								<?php
								$c_id = $row_all_rules['id'];
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

								echo $row_all_rules['description'];


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

			<?php if($count_all_rules==0){echo "<p class='category'>". plugin_lang_get('no_rules_created')."<p>";}?>
			<?php if( ON == config_get( 'use_javascript' ) ) { ?>
				<?php collapse_closed( 'profile3' ); collapse_icon('profile3'); echo plugin_lang_get('col_rules');?>
				<?php collapse_end( 'profile3' ); ?>
			<?php } ?>
		</td>
	</tr>