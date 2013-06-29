<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

$t_project_id= helper_get_current_project();


?>
<br/>
<?php

$name=$_REQUEST['symbol_name'];
$notion=$_REQUEST['symbol_notion'];
$type=$_REQUEST['symbol_type'];
$row_number_symbol_synonymous=$_REQUEST['row_number_symbol_synonymous'];
$row_number_symbol_impact=$_REQUEST['row_number_symbol_impact'];

$operation=$_REQUEST['operation'];
$id_symbol=$_REQUEST['id_symbol'];

if ( is_blank( $name ) || is_blank( $notion ) || ( $type==0 ) ) {
//			trigger_error( ERROR_GENERIC, ERROR );
echo "Must add Name, Notion and Type";

if($operation==0){//new
	$t_page = plugin_page( 'new_symbol_page' );
}
else{//update
	$t_page= plugin_page( 'update_symbol_page' );	;
	$t_page=$t_page."&id_symbol=".$id_symbol;
}

echo '<br><br>';
echo "<a href=\"$t_page\">Back</a>";
echo "<br>";
html_page_bottom1( );

die();
		}


if($operation==1){//es update, primero tengo que borrar en cascada el simbolo

//echo "id simbolo: ".$id_symbol;
//echo "id proyecto: ".$t_project_id;

	//delete impacts

	$t_repo_table = plugin_table( 'impact', 'honey' );

	$t_query_impact = 'DELETE FROM '.$t_repo_table.' WHERE  id_symbol=' . db_param();

	//echo "sql: ".$t_query_impact;
		
	$g_result_update_impact=db_query_bound( $t_query_impact, array( $id_symbol)  );

	//delete synonymous

	$t_repo_table = plugin_table( 'synonymous', 'honey' );

	$t_query_synonymous = 'DELETE FROM '.$t_repo_table.' WHERE  id_symbol=' . db_param();

	//echo "sql2: ".$t_query_synonymous;
		
	$g_result_update_synonymous=db_query_bound( $t_query_synonymous, array( $id_symbol)  );

	//delete symbol

	$t_repo_table = plugin_table( 'symbol', 'honey' );

	$t_query_symbol = 'DELETE FROM '.$t_repo_table.' WHERE id_project=' . db_param() .' and id=' . db_param();

	//echo "sql3: ".$t_query_symbol;
		
	$g_result_update_symbol=db_query_bound( $t_query_symbol, array( $t_project_id, $id_symbol)  );

}

//symbol insert
$t_repo_table = plugin_table( 'symbol', 'honey' );

$t_query_symbol = 'INSERT INTO '.$t_repo_table.' (name, notion, type,id_project  )
			VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' )';
$g_result_insert_symbol=db_query_bound( $t_query_symbol, array( $name,$notion, $type, $t_project_id)  );

$id_symbol=mysql_insert_id();

//synonymous insert
$t_repo_table = plugin_table( 'synonymous', 'honey' );

for($i=0; $i<$row_number_symbol_synonymous;$i++){

	$t_query_synonymous = 'INSERT INTO '.$t_repo_table.' (name, id_symbol)
				VALUES ( ' . db_param() . ', ' . db_param() . ' )';
	$g_result_insert_synonymous=db_query_bound( $t_query_synonymous, array( $_REQUEST['symbol_synonymous'.$i],$id_symbol)  );

}

//impact insert
$t_repo_table = plugin_table( 'impact', 'honey' );

for($i=0; $i<$row_number_symbol_impact;$i++){

	$t_query_impact = 'INSERT INTO '.$t_repo_table.' (description, id_symbol)
				VALUES ( ' . db_param() . ', ' . db_param() . ' )';
	$g_result_insert_impact=db_query_bound( $t_query_impact, array( $_REQUEST['symbol_impact'.$i], $id_symbol)  );

}

echo "<p>Saved data</p>";


echo 'name: '.$name;
echo '<br><br>';
for($i=0;$i<$row_number_symbol_synonymous;$i++){
	$synonimous=$_REQUEST['symbol_synonymous'.$i];
	echo 'synonimous: '.$synonimous;
	echo '<br><br>';
}
echo 'notion: '.$notion;
echo '<br><br>';
for($i=0;$i<$row_number_symbol_impact;$i++){
	$impact=$_REQUEST['symbol_impact'.$i];
	echo 'impact: '.$impact;
	echo '<br><br>';
}

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
echo 'type: '.$type;

if($operation==0){//new
	$t_page = plugin_page( 'new_symbol_page' );
}
else{//update
	$t_page= plugin_page( 'update_symbol_page' );	
	$t_page=$t_page."&id_symbol=".$id_symbol;
}

echo '<br><br>';
echo "<a href=\"$t_page\">Back</a>";
echo '<br>';

$t_url= plugin_page( 'view_symbols_page' );

html_page_bottom( );

html_meta_redirect_honey( $t_url, $p_time = null);


