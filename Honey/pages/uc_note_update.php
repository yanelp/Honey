<?php

require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_cu_menu();

$t_project_id= helper_get_current_project();


?>
<br/>
<?php

$note=$_REQUEST['note_text'];
$id_note=$_REQUEST['uc_note_id'];
$id_usecase=$_REQUEST['usecase_id'];


if ( is_blank( $note ) ) {

echo "Must add Note text";


	$t_page= plugin_page( 'usecase_page' );	;
	$t_page=$t_page."&id_usecase=".$id_usecase;


echo '<br><br>';
echo "<a href=\"$t_page\">Back</a>";
echo "<br>";
html_page_bottom1( );

die();
		}

//hago update del texto la nota

	$t_repo_table = plugin_table( 'uc_note', 'honey' );

	$t_query_note = 'update '.$t_repo_table.' set note=' . db_param().' WHERE  id=' . db_param();

	//echo "sql: ".$t_query_impact;
		
	$g_result_update_note=db_query_bound( $t_query_note, array( $note, $id_note)  );

	

echo "<p>Saved data</p>";


echo 'Note: '.$note;
echo '<br><br>';


	$t_page= plugin_page( 'usecase_page' );	;
	$t_page=$t_page."&id_usecase=".$id_usecase;


echo '<br><br>';
echo "<a href=\"$t_page\">Back</a>";
echo "<br>";
html_page_bottom1( );
