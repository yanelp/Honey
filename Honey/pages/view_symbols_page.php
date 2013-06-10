<?php

require_once('print_lel_menu.php');

html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

EVENT_LAYOUT_RESOURCES;


$t_repo_table = plugin_table( 'symbol', 'honey' );

$query_symbol = 'SELECT id, name 
				   FROM '.$t_repo_table.' 
				   ORDER BY name';

$result = db_query_bound( $query_symbol, array() );
$count = db_num_rows( $result );
?>
<br>
<center>Symbols</center>
<br>

<?php 

if ($count != 0) {
	$t_page = plugin_page( 'symbol_page' );	
?>

<table align="center" border="1">

<?php 	while( $row = db_fetch_array( $result ) ){
	$t_page=$t_page."&id_symbol=".$row['id'];
	?>
		<tr>
			<td>
			<?php echo "<a href=\"$t_page\">".$row['name']."</a>";?>
			</td>
		</tr>
	 <?php 
	$t_page = plugin_page( 'symbol_page' );				
			} 
 } ?>

 </table>

<br>

<center><a href="<?php echo plugin_page( "core" ); ?>">Derivar a Casos de Uso</a></center>

<?php
html_page_bottom1( );

?>