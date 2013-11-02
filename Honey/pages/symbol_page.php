<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

EVENT_LAYOUT_RESOURCES;

$id_symbol = gpc_get_int( 'id_symbol' );

if($id_symbol==-1){$id_symbol=$_REQUEST['symbol_id'];}

$t_page_update="update_symbol_page";
$t_page_update=$t_page_update."&id_symbol=".$id_symbol;

?>

<form id="form1" action="<?php echo plugin_page( $t_page_update ); ?>" method="POST">
<?php


$t_repo_table = plugin_table( 'symbol', 'honey' );

$query_symbol = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id=' . db_param().'
				 AND active = 0';

$result = db_query_bound( $query_symbol, array($id_symbol) );
$count = db_num_rows( $result );

if($count>0){
	$row = db_fetch_array( $result );

	$id_sym=str_pad($row['id'], 7, "0", STR_PAD_LEFT);
	$name=$row['name'];
	$type=$row['type'];
	$type_symbol = $type;
	$notion=$row['notion'];

	$t_page_delete = plugin_page( "delete_symbol_page" );

	//include('search_symbols.php');

	if($type==1){$type=plugin_lang_get('subject');}
	else{
		if($type==2){$type=plugin_lang_get('object');}
		else{
			if($type==3){$type=plugin_lang_get('state');}
			else{
				if($type==4){$type=plugin_lang_get('verb');}
				}
			}
		}
	?>

	<div align="center">
	<table class="width90" summary="<?php echo plugin_lang_get( 'symbol_information' )?>">
		<tr>
			<td class="form-title" colspan="2">
			<?php echo plugin_lang_get( 'symbol_information' )?>
			</td>
		</tr>
		<tr <?php echo helper_alternate_class() ?>>
			<td class="category" width='20%'><?php echo plugin_lang_get('ID')?></td><td><?php echo $id_sym ?></td>
		</tr>
		<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('name')?></td><td><?php echo $name ?></td>
			
			<?php include('search_notion_symbols.php');?>

		<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('type')?></td><td><?php echo $type ?></td>

			<?php include('search_impacts_symbols.php');?>


	<?php
	$t_repo_table = plugin_table( 'synonymous', 'honey' );

	$query_synonymous = 'SELECT * 
					 FROM '.$t_repo_table.' 
					 where id_symbol=' . db_param().'
					 AND active = 0';

	$result_synonymous = db_query_bound( $query_synonymous, array($id_symbol) );
	$count_synonymous = db_num_rows( $result_synonymous );

	if($count_synonymous>0){?>

		<tr <?php echo helper_alternate_class() ?>>
			<td class="category"><?php echo plugin_lang_get('synonyms')?></td>
			<td>
		<?php while( $row_synonymous = db_fetch_array( $result_synonymous) ){?>
			
					<table>
					<tr><td><?php echo $row_synonymous['name'];?> </td></tr>
					</table>		
		<?php } ?>
			</td>
		</tr>

	<?php } ?>

	</table>

	<form>
	<br>
	<table align="center">
		<tr>
			<td><input type="submit" value="<?php echo plugin_lang_get('edit')?>"/>
			<?php $t_page=plugin_page("view_symbols_page");?>
			<input type="button" value="<?php echo plugin_lang_get('delete')?>" onClick="javascript:go_page('null', null ,'<?php echo $t_page_delete?>')"/>&#160&#160&#160
			<input type="button" value="<?php echo plugin_lang_get('cancel')?>" onClick="javascript:go_page(null,null ,'<?php echo $t_page?>')"/></td>
			<input type="hidden" name="id_symbol_hidden" id="id_symbol_hidden" value="<?php echo $id_symbol ?>"/>
			<input type="hidden" name="type_symbol_hidden" id="type_symbol_hidden" value="<?php echo $type_symbol?>"/>

		</td>
		</tr>
	</table>
	</div>

<?php
}//if existe el simbolo buscado
else{?>
	<div align='center'>
	<?php showMessage(plugin_lang_get('symbol_do_not_exist'), 'error')?>
	</table>
	</div>
	<?php
	echo '<br><br>';
	$t_page=plugin_page("view_symbols_page");
	?>
	<table align='center'>
	<tr>
	<td class='center'>
	<?php echo "<a href=\"$t_page\">".plugin_lang_get('back')."</a>";?>
	</td>
	</tr>
	</table>

	<?php
	echo "<br>";
}

html_page_bottom1( );

?>