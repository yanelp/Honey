<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

$t_project_id= helper_get_current_project();
$row_name='';

?>
<br/>
<?php

/*******Obtengo los datos de la regla***********/

$name=trim($_REQUEST['rule_name']);
$descrip=trim($_REQUEST['rule_descrip']);

$operation=$_REQUEST['operation'];
$id_rule=$_REQUEST['id_rule'];

/************Verifico que estén los datos obligarorios completos***********/

if ( is_blank( $name )  ) {?>

	<div align='center'>
	<?php showMessage(plugin_lang_get('must_name'), 'error')?>
	</table>
	</div>
	<?php

	if($operation==0){//new
		$t_page = plugin_page( 'new_rule_page' );
	}
	else{//update
		$t_page= plugin_page( 'update_rule_page' );	;
		$t_page=$t_page."&id_rule=".$id_rule;
	}

	echo '<br><br>';
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
	html_page_bottom1( );

	die();
}


/**********Verifico que la regla no existe*****************/

$t_repo_table = plugin_table( 'rule', 'honey' );

if($operation==1){//es update

	/*******Verifico que no existe teniendo en cuanta la regla que se está modificando************/

	$t_query_rule_name = 'select name FROM '.$t_repo_table.' 
						WHERE id_project=' . db_param() .' and active = 0 and name=' . db_param().' and id!='. db_param();
		
	$g_result_rule_name=db_query_bound( $t_query_rule_name, array( $t_project_id, $name, $id_rule) );

	$row = db_fetch_array( $g_result_rule_name );

	$row_name=$row['name'];
}
else{

	/*******Verifico que no existe la regla************/

	$t_query_rule_name = 'select name FROM '.$t_repo_table.' WHERE id_project=' . db_param() .' and active = 0 and name=' . db_param();
		
	$g_result_rule_name=db_query_bound( $t_query_rule_name, array( $t_project_id, $name) );

	$row = db_fetch_array( $g_result_rule_name );

	$row_name=$row['name'];

}

if($row_name==''){//la regla no existe

	if($operation==1){//es update

		$t_repo_table = plugin_table( 'rule', 'honey' );

		$t_query_rule = 'UPDATE '.$t_repo_table.' set description= ' . db_param() . ', name= ' . db_param() . '
						   where id= ' . db_param();
		$g_result_update_rule=db_query_bound( $t_query_rule, array( $descrip,$name, $id_rule)  );

		?>
		<div align='center'>
		<?php showMessage(plugin_lang_get('saved_data'), 'congratulations')?>
		</table>
		</div>
		<?php


	}//ES UPDATE

	else{//es insert

		$t_repo_table = plugin_table( 'rule', 'honey' );

		$t_query_rule = 'INSERT INTO '.$t_repo_table.' (name, description,  id_project )
					VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' )';
		$g_result_insert_rule=db_query_bound( $t_query_rule, array( $name,$descrip, $t_project_id)  );

		?>
		<div align='center'>
		<?php showMessage(plugin_lang_get('saved_data'), 'congratulations')?>
		</table>
		</div>
		<?php

	}//es insert


	$t_page = plugin_page( 'new_rule_page' );

	echo '<br><br>';
	?>
	<table align='center'>
	<tr>
	<td class='center'>
	<?php echo "<a href=\"$t_page\">".plugin_lang_get('back')."</a>";?>
	</td>
	</tr>
	</table>

	<?php
	echo '<br>';

	$t_url= plugin_page( 'view_rules_page' );
	html_page_bottom( );

	html_meta_redirect_honey( $t_url, $p_time = null);


}//si no existe el actor

else{//el actor esta repetido

		?>
	
		<div align='center'>
		<?php showMessage(plugin_lang_get('rule_exist'), 'error')?>
		</table>
		</div>
		<?php

		if($operation==0){//new
			$t_page = plugin_page( 'new_rule_page' );
		}
		else{//update
			$t_page= plugin_page( 'update_rule_page' );	
			$t_page=$t_page."&id_rule=".$id_rule;
		}

		echo '<br><br>';
		?>
		<table align='center'>
		<tr>
		<td class='center'>
		<?php echo "<a href=\"$t_page\">".plugin_lang_get('back')."</a>";?>
		</td>
		</tr>
		</table>

		<?php
		echo '<br>';

}

