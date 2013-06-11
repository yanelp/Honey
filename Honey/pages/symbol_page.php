<?php

require_once('print_lel_menu.php');

html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

EVENT_LAYOUT_RESOURCES;

$id_symbol = gpc_get_int( 'id_symbol' );

$t_page_update="update_symbol_page";
$t_page_update=$t_page_update."&id_symbol=".$id_symbol;
?>

<form id="form1" action="<?php echo plugin_page( $t_page_update ); ?>" method="POST">
<?php


$t_repo_table = plugin_table( 'symbol', 'honey' );

$query_symbol = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id=' . db_param();

$result = db_query_bound( $query_symbol, array($id_symbol) );
$count = db_num_rows( $result );
$row = db_fetch_array( $result );

$name=$row['name'];
$type=$row['type'];
$notion=$row['notion'];

//include('search_symbols.php');

if($type==1){$type='Subject';}
else{
	if($type==2){$type='Object';}
	else{
		if($type==3){$type='State';}
		else{
			if($type==4){$type='Verb';}
			}
		}
	}
?>

<div align="center">
<table class="width90">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_Honey_symbol_information' )?>
		</td>
	</tr>

	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Name</td><td><?php echo $name ?></td>
		
		<?php include('search_notion_symbols.php');?>

	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Type</td><td><?php echo $type ?></td>

		<?php include('search_impacts_symbols.php');?>


<?php
$t_repo_table = plugin_table( 'synonymous', 'honey' );

$query_synonymous = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id_symbol=' . db_param();

$result_synonymous = db_query_bound( $query_synonymous, array($id_symbol) );
$count_synonymous = db_num_rows( $result_synonymous );

if($count_synonymous>0){?>

	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Synonyms</td>
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
		<td><input type="submit" value="Update"/></td><td><input type="button" value="Cancel"/></td>
	</tr>
</table>
</div>
<?php

html_page_bottom1( );

?>