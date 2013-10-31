
<?php
require_once('functions.php');
require_once( 'file_api.php' );
require_once( 'plugin_api.php' );

html_page_top( plugin_lang_get( 'title' ) );
auth_ensure_user_authenticated();

print_lel_menu();

EVENT_LAYOUT_RESOURCES;


//symbol a borrar
$id_symbol =$_REQUEST['id_symbol_hidden'];
$type_symbol =$_REQUEST['type_symbol_hidden'];


if ($type_symbol == 1){

		$t_repo_table= plugin_table( 'actor', 'honey' );

										$query_search = 'SELECT *
														  FROM '.$t_repo_table.' 
														  WHERE id_symbol=' . db_param().
														  ' AND active=0';

		$result_search = db_query_bound( $query_search, array($id_symbol) );

		$count_actors = db_num_rows( $result_search );

		if($count_actors > 0){
		
		?>
			<div align="center">
			<table class="width90">
			<tr class="row-category">
			<td><?php echo plugin_lang_get('the_symbol') ?> <?php echo "<a  href=\"$t_page_return\">".str_pad($id_symbol, 7, "0", STR_PAD_LEFT)."</a>";?> <?php echo plugin_lang_get('symbol_referenced') ?></td>
			</tr>
			<?php
			while($row_search = db_fetch_array( $result_search )){
				 $id_actor = $row_search['id'];
				 $actor_name = $row_search['name'];
				 $t_page_go =  plugin_page( 'update_actor_page' );
				 $t_page_go=$t_page_go."&id_actor=".$id_actor;
				 ?>
				<tr  <?php echo helper_alternate_class() ?> >
				<td class="center" colspan="2"> 
									<?php echo "<a  href=\"$t_page_go\">".str_pad($id_actor, 7, "0", STR_PAD_LEFT)."</a>";?>-<?php echo $actor_name;?>
				</td>

				</tr>
			<?php

		
		}//fin while
		?>
</table>
		<?php

	}//fin if ($count_actors)

   } //fin if ($type_symbol == 4)
    

    if ($type_symbol == 4){

		$t_repo_table= plugin_table( 'usecase', 'honey' );

										$query_search = 'SELECT *
														  FROM '.$t_repo_table.' 
														  WHERE id_symbol=' . db_param().
														  ' AND active=0';

		$result_search = db_query_bound( $query_search, array($id_symbol) );

		$count_cu = db_num_rows( $result_search );

		if($count_cu > 0){
			
			?>
				<div align="center">
				<table class="width90">
				<tr class="row-category">
				<td><?php echo plugin_lang_get('the_symbol') ?> <?php echo "<a  href=\"$t_page_return\">".str_pad($id_symbol, 7, "0", STR_PAD_LEFT)."</a>";?> <?php echo plugin_lang_get('usecase_referenced') ?></td>
				</tr>
				<?php
				while($row_search = db_fetch_array( $result_search )){
					 $id_usecase = $row_search['id'];
					 $uc_name = $row_search['name'];
					 $t_page_go =  plugin_page( 'usecase_page' );
					 $t_page_go=$t_page_go."&id_usecase=".$id_usecase;
					 ?>
					<tr class="row-category" <?php echo helper_alternate_class() ?> >
					<td class="form-title" colspan="2"> 
										<?php echo "<a  href=\"$t_page_go\">".str_pad($id_usecase, 7, "0", STR_PAD_LEFT)."</a>";?>-<?php echo $uc_name;?>
					</td>

					</tr>
				<?php

			
			}//fin while

		}//fin if ($count_cu)

	
	}//fin ($type_symbol == 4)

$t_page=plugin_page( 'delete_symbol' );
$t_page=$t_page."&id_symbol=".$id_symbol;

?>


<div align="center">
<form id="form1" action="<?php echo  $t_page; ?>" method="POST" enctype="multipart/form-data">


		<?php showMessage(plugin_lang_get('sure_delete_symbol'), 'warning')?>
		<tr class="row-category">
		<td class="form-title" colspan="2">
		<input type="button" value="<?php echo plugin_lang_get('delete')?>" onClick="javascript:go_page(null, null ,'<?php echo $t_page;?>')"/></td>
		</tr>
		</table>

</form>


</div>



<?php

html_page_bottom1( );

?>