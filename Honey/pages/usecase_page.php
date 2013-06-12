<?php

require_once('print_cu_menu.php');

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

$id_usecase = gpc_get_int( 'id_usecase' );

$t_page_update="modify_use_page";
$t_page_update=$t_page_update."&id_usecase=".$id_usecase;
?>

<form id="form1" action="<?php echo plugin_page( $t_page_update ); ?>" method="POST">
<?php


$t_repo_table = plugin_table( 'usecase', 'honey' );

$query_symbol = 'SELECT * 
				 FROM '.$t_repo_table.' 
				 where id=' . db_param();

$result = db_query_bound( $query_symbol, array($id_usecase) );
$count = db_num_rows( $result );
$row = db_fetch_array( $result );

$name=$row['name'];
$observation=$row['observations'];
$id=$row['id'];
$precond=$row['preconditions'];
$postcond=$row['postconditions'];
$goal=$row['goal'];

?>

<div align="center">
<table class="width90">
	<tr>
		<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_Honey_usecase_information' )?>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">ID</td><td><?php echo $id ?></td>
		
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Name</td><td><?php echo $name ?></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Observation</td><td><?php echo $observation ?></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Preconditions</td><td><?php echo $precond ?></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Postconditions</td><td><?php echo $postcond ?></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?>>
		<td class="category">Goal</td><td><?php echo $goal ?></td>
	</tr>


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