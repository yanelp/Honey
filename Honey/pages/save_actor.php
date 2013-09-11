<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

$t_project_id= helper_get_current_project();
$row_name='';

?>
<br/>
<?php

$name=trim($_REQUEST['actor_name']);
$descrip=trim($_REQUEST['actor_descrip']);

$operation=$_REQUEST['operation'];
$id_actor=$_REQUEST['id_actor'];

if ( is_blank( $name )  ) {
//			trigger_error( ERROR_GENERIC, ERROR );
echo plugin_lang_get('must_name');

if($operation==0){//new
	$t_page = plugin_page( 'new_actor_page' );
}
else{//update
	$t_page= plugin_page( 'update_actor_page' );	;
	$t_page=$t_page."&id_actor=".$id_actor;
}

echo '<br><br>';
echo "<a href=\"$t_page\">".plugin_lang_get('back')."</a>";
echo "<br>";
html_page_bottom1( );

die();
		}


if($operation==1){//es update

	//actor update
	$t_repo_table = plugin_table( 'actor', 'honey' );

	$t_query_actor = 'UPDATE '.$t_repo_table.' set description= ' . db_param() . ', name= ' . db_param() . '
					   where id= ' . db_param();
	$g_result_update_actor=db_query_bound( $t_query_actor, array( $descrip,$name, $id_actor)  );

	echo "<p>Saved data</p>";

	if($operation==0){//new
		$t_page = plugin_page( 'new_actor_page' );
	}
	else{//update
		$t_page= plugin_page( 'update_actor_page' );	
		$t_page=$t_page."&id_symbol=".$id_actor;
	}

	echo '<br><br>';
	echo "<a href=\"$t_page\">".plugin_lang_get('back')."</a>";
	echo '<br>';

	$t_url= plugin_page( 'view_actors_page' );

	html_page_bottom( );

	html_meta_redirect_honey( $t_url, $p_time = null);

}//ES UPDATE

else{//busco si el simbolo ya existe

	$t_repo_table = plugin_table( 'actor', 'honey' );

	$t_query_actor_name = 'select name FROM '.$t_repo_table.' WHERE id_project=' . db_param() .' and active = 0 and name=' . db_param();
		
	$g_result_actor_name=db_query_bound( $t_query_actor_name, array( $t_project_id, $name) );

	$row = db_fetch_array( $g_result_actor_name );

	$row_name=$row['name'];

	if($row_name==''){//si no existe el actor

		//actor insert
		$t_repo_table = plugin_table( 'actor', 'honey' );

		$t_query_actor = 'INSERT INTO '.$t_repo_table.' (name, description, id_project )
					VALUES ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ' )';
		$g_result_insert_actor=db_query_bound( $t_query_actor, array( $name,$descrip, $t_project_id)  );



		echo "<p>".plugin_lang_get('saved_data')."</p>";


		if($operation==0){//new
			$t_page = plugin_page( 'new_actor_page' );
		}
		else{//update
			$t_page= plugin_page( 'update_actor_page' );	
			$t_page=$t_page."&id_symbol=".$id_actor;
		}

		echo '<br><br>';
		echo "<a href=\"$t_page\">".plugin_lang_get('back')."</a>";
		echo '<br>';

		$t_url= plugin_page( 'view_actors_page' );

		html_page_bottom( );

		html_meta_redirect_honey( $t_url, $p_time = null);


	}//si no existe el actor
	else{//el actor esta repetido

		echo  "<p>".plugin_lang_get('actor_exist')."</p>";

		$t_page = plugin_page( 'new_actor_page' );

		echo '<br><br>';
		echo "<a href=\"$t_page\">".plugin_lang_get('back')."</a>";
		echo '<br>';
	}

}

