<?php

require_once('functions.php');
require_once( 'file_api.php' );


html_page_top( plugin_lang_get( 'title' ) );
auth_ensure_user_authenticated();

print_lel_menu();

EVENT_LAYOUT_RESOURCES;

$project_id =  helper_get_current_project();

if ( ( ALL_PROJECTS == helper_get_current_project() ) && ( 0 == $f_master_bug_id ) ) {
		print_header_redirect( 'login_select_proj_page.php?ref=plugin.php?page=Honey/new_usecase_page.php' );
	}

//validamos si existen una derivación activa para el proyecto
$t_repo_table = plugin_table( 'derivation', 'honey' );

								$query_search = 'SELECT *
												  FROM '.$t_repo_table.' 
												  WHERE id_project='. db_param().'
												  AND active = 0';

$result_search = db_query_bound( $query_search, array($project_id) );
$row_search = db_fetch_array( $result_search);

$count = db_num_rows( $result_search );

$t_page = plugin_page('view_symbols_page');

$id_derivation = $row_search['id'];
$t_page_y = plugin_page('core');
$t_page_y =  $t_page_y."&id_derivation=".$id_derivation;

?>

<div align="center">

<form id="form1" action="<?php echo plugin_page($t_page); ?>" method="POST" enctype="multipart/form-data"> 

<table class="width90">
<tr class="row-category">
<td> 

<?php

if ($count > 0){

?>
<?php echo plugin_lang_get('dictionary_derived')?><br>
<?php echo plugin_lang_get('previous_derivation')?>
<?php 

}

else{

?>

<?php echo plugin_lang_get('sure_derivation')?>

<?php

}

?>
</td>
</tr>
<tr class="row-category">
		<td class="form-title" colspan="2">
     	<!--<input type="button" name='button_no' value="No" onClick="javascript:go_page(null,null ,'<?php echo $t_page_no?>')"/> -->
		<input type="button" value="<?php echo plugin_lang_get('no')?>" onClick="javascript:go_page(null, null ,'<?php echo $t_page?>')"/>
		<input type="button" value="<?php echo plugin_lang_get('yes')?>" onClick="javascript:go_page(null, null ,'<?php echo $t_page_y?>')"/>
		<!--<input type="hidden" id="id_derivation" name="id_derivation" value=<?php $id_derivation ?>/>-->
		


</td>
</tr>
</table>
</form>




</div>



<?php

html_page_bottom1( );

?>