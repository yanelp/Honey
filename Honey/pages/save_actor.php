<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

$t_project_id= helper_get_current_project();
$row_name='';

?>
<br/>
<?php

/********Obtengo todos los datos del actor**********/

$name=trim($_REQUEST['actor_name']);
$descrip=trim($_REQUEST['actor_descrip']);

$operation=$_REQUEST['operation'];
$id_actor=$_REQUEST['id_actor'];


/************Verifico que los datos obligatorios estén completos*********/

if ( is_blank( $name )  ) {?>
	
	<div align='center'>
	<?php showMessage(plugin_lang_get('must_name'), 'error')?>
	</table>
	</div>
	<?php

	if($operation==0){//new
		$t_page = plugin_page( 'new_actor_page' );
	}
	else{//update
		$t_page= plugin_page( 'update_actor_page' );	;
		$t_page=$t_page."&actor_id=".$id_actor;
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

/********Verifico que el actor no existe ************/

$t_repo_table = plugin_table( 'actor', 'honey' );

if($operation==1){//es update

	/***********Busco repetido teniendo en cuenta que no sea el actor que está siendo modificado**************/

	$t_query_actor_name = 'select name FROM '.$t_repo_table.' WHERE id_project=' . db_param() .' and active = 0 and name=' . db_param().' and id!='. db_param();
		
	$g_result_actor_name=db_query_bound( $t_query_actor_name, array( $t_project_id, $name, $id_actor) );

	$row = db_fetch_array( $g_result_actor_name );

	$row_name=$row['name'];

}
else{//es insert

	/*********Busco si está repetido***************/

	$t_query_actor_name = 'select name FROM '.$t_repo_table.' WHERE id_project=' . db_param() .' and active = 0 and name=' . db_param();
		
	$g_result_actor_name=db_query_bound( $t_query_actor_name, array( $t_project_id, $name) );

	$row = db_fetch_array( $g_result_actor_name );

	$row_name=$row['name'];

}

if($row_name==''){//el actor no existe

	if($operation==1){//es update

		//actor update
		$t_repo_table = plugin_table( 'actor', 'honey' );

		$t_query_actor = 'UPDATE '.$t_repo_table.' set description= ' . db_param() . ', name= ' . db_param() . '
					   where id= ' . db_param();
		$g_result_update_actor=db_query_bound( $t_query_actor, array( $descrip,$name, $id_actor)  );

		?>
		
		<div align='center'>
		<?php showMessage(plugin_lang_get('saved_data'), 'congratulations')?>
		</table>
		</div>
		

	<?php }//ES UPDATE

	else{//es insert

		$t_repo_table = plugin_table( 'actor', 'honey' );

		$t_query_actor = 'INSERT INTO '.$t_repo_table.' (name, description, id_project )
					VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' )';
		$g_result_insert_actor=db_query_bound( $t_query_actor, array( $name,$descrip, $t_project_id)  );

		?>
	
		<div align='center'>
		<?php showMessage(plugin_lang_get('saved_data'), 'congratulations')?>
		</table>
		</div>
		<?php

	}//es insert
		
			
		$t_page = plugin_page( 'new_actor_page' );

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

		$t_url= plugin_page( 'view_actors_page' );

		html_page_bottom( );

		html_meta_redirect_honey( $t_url, $p_time = null);


}//si no existe el actor
else{//el actor esta repetido

	?>		
	<div align='center'>
	<?php showMessage(plugin_lang_get('actor_exist'), 'error')?>
	</table>
	</div>
	<?php
	if($operation==0){//insert
		$t_page = plugin_page( 'new_actor_page' );
	}
	else{//update
		$t_page= plugin_page( 'update_actor_page' );	
		$t_page=$t_page."&actor_id=".$id_actor;
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
	
}//actor repetido

