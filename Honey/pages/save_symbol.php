<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

$t_project_id= helper_get_current_project();
$row_name='';

?>
<br/>
<?php

//obtengo los datos del symbol

$name=trim($_REQUEST['symbol_name']);
$notion=trim($_REQUEST['symbol_notion']);
$type=$_REQUEST['symbol_type'];
$row_number_symbol_synonymous=$_REQUEST['row_number_symbol_synonymous'];
$row_number_symbol_impact=$_REQUEST['row_number_symbol_impact'];

$operation=$_REQUEST['operation'];
$id_symbol=$_REQUEST['id_symbol'];


/****Verifico que estés completos los datos obligatorios*****/

if ( is_blank( $name ) || is_blank( $notion ) || ( $type==0 ) ) {

	?>
	<div align='center'>
	<?php showMessage(plugin_lang_get('must_symbol'), 'error')?>
	</table>
	</div>
	<?php

	if($operation==0){//new
		$t_page = plugin_page( 'new_symbol_page' );
	}
	else{//update
		$t_page= plugin_page( 'update_symbol_page' );	;
		$t_page=$t_page."&id_symbol=".$id_symbol;
	}

	echo '<br><br>';
	?>
	<table align='center'>
	<tr>
	<td class='center'>
	<?php echo "<a href=\"$t_page\">". plugin_lang_get('back')."</a>";?>
	</td>
	</tr>
	</table>

	<?php
	echo "<br>";
	html_page_bottom1( );

	die();
}//if alguno de los datos obligatorios estaba incompleto


/****Verifico que el symbol no exista*****/

$t_repo_table = plugin_table( 'symbol', 'honey' );

/***verificacion para el update teniendo en cuenta el symbol que está siendo modificado****/

if($operation==1){

	$t_query_symbol_name = 'select name FROM '.$t_repo_table.'
								WHERE id_project=' . db_param() .' and active = 0 and name=' . db_param() .' and id!='. db_param() ;
			
	$g_result_symbol_name=db_query_bound( $t_query_symbol_name, array( $t_project_id, $name, $id_symbol) );

	$row = db_fetch_array( $g_result_symbol_name );

	$row_name=$row['name'];

	echo $row_name;

}

/***verificacion para el insert****/
	
else{

	$t_query_symbol_name = 'select name FROM '.$t_repo_table.' WHERE id_project=' . db_param() .' and active = 0 and name=' . db_param();
		
	$g_result_symbol_name=db_query_bound( $t_query_symbol_name, array( $t_project_id, $name) );

	$row = db_fetch_array( $g_result_symbol_name );

	$row_name=$row['name'];

}

/****Si el nombre del simbolo no está repetido continúo con la operación***/

if($row_name==''){//el symbol no está repetido

	if($operation==1){//es update, primero tengo que borrar en cascada el simbolo

		//delete impacts

		$t_repo_table = plugin_table( 'impact', 'honey' );

		$t_query_impact = 'DELETE FROM '.$t_repo_table.' WHERE  id_symbol=' . db_param();
		
		$g_result_update_impact=db_query_bound( $t_query_impact, array( $id_symbol)  );

		//delete synonymous

		$t_repo_table = plugin_table( 'synonymous', 'honey' );

		$t_query_synonymous = 'DELETE FROM '.$t_repo_table.' WHERE  id_symbol=' . db_param();

		$g_result_update_synonymous=db_query_bound( $t_query_synonymous, array( $id_symbol)  );

		//delete symbol

		$t_repo_table = plugin_table( 'symbol', 'honey' );

		$t_query_symbol = 'DELETE FROM '.$t_repo_table.' WHERE id_project=' . db_param() .' and id=' . db_param();
		
		$g_result_update_symbol=db_query_bound( $t_query_symbol, array( $t_project_id, $id_symbol)  );

	}//ES UPDATE

	/***Lo siguiente se comparte tanto para el insert como para el update***/

	//symbol insert

	$t_repo_table = plugin_table( 'symbol', 'honey' );

	$t_query_symbol = 'INSERT INTO '.$t_repo_table.' (name, notion, type,id_project  )
				VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' )';
	$g_result_insert_symbol=db_query_bound( $t_query_symbol, array( $name,$notion, $type, $t_project_id)  );

	$id_symbol=mysql_insert_id();

	//synonymous insert

	$t_repo_table = plugin_table( 'synonymous', 'honey' );

	for($i=0; $i<$row_number_symbol_synonymous;$i++){

		if($_REQUEST['symbol_synonymous'.$i]!=''){
			$t_query_synonymous = 'INSERT INTO '.$t_repo_table.' (name, id_symbol)
						VALUES ( ' . db_param() . ', ' . db_param() . ' )';
			$g_result_insert_synonymous=db_query_bound( $t_query_synonymous, array( $_REQUEST['symbol_synonymous'.$i],$id_symbol)  );
		}
	}

	//impact insert
	$t_repo_table = plugin_table( 'impact', 'honey' );

	for($i=0; $i<$row_number_symbol_impact;$i++){
		
		if($_REQUEST['symbol_impact'.$i]!=''){
		
			$t_query_impact = 'INSERT INTO '.$t_repo_table.' (description, id_symbol)
						VALUES ( ' . db_param() . ', ' . db_param() . ' )';
			$g_result_insert_impact=db_query_bound( $t_query_impact, array( $_REQUEST['symbol_impact'.$i], $id_symbol)  );
		}//if
	}//for

	?>
	<div align='center'>
	<?php showMessage(plugin_lang_get('saved_data'), 'congratulations')?>
	</table>
	</div>
	<?php

	$t_page = plugin_page( 'new_symbol_page' );

	echo '<br><br>';
	
	?>
	<table align='center'>
	<tr>
	<td class='center'>
	<?php echo "<a href=\"$t_page\">". plugin_lang_get('back')."</a>";?>
	</td>
	</tr>
	</table>

	<?php
	$t_url= plugin_page( 'view_symbols_page' );

	html_page_bottom( );

	html_meta_redirect_honey( $t_url, $p_time = null);


}//si no existe el simbolo

else{//el simbolo esta repetido		?>

	<div align='center'>
	<?php showMessage(plugin_lang_get('symbol_exist'), 'error')?>
	</table>
	</div>

	<?php

	if($operation==0){//new
		$t_page = plugin_page( 'new_symbol_page' );
	}
	else{//update
		$t_page= plugin_page( 'update_symbol_page' );	;
		$t_page=$t_page."&id_symbol=".$id_symbol;
	}

	echo '<br><br>';
	?>
	<table align='center'>
	<tr>
	<td class='center'>
	<?php echo "<a href=\"$t_page\">". plugin_lang_get('back')."</a>";?>
	</td>
	</tr>
	</table>

	<?php
	echo '<br>';
}