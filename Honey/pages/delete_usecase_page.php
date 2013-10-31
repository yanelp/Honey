<?php

require_once('functions.php');
require_once( 'file_api.php' );


html_page_top( plugin_lang_get( 'title' ) );
auth_ensure_user_authenticated();

print_cu_menu();

EVENT_LAYOUT_RESOURCES;

$id_usecase = gpc_get_int( 'id_usecase' );
$id=str_pad($id_usecase, 7, "0", STR_PAD_LEFT);
$t_redirect_url=plugin_page("view_cu_page");
$t_page=plugin_page( 'delete_usecase' );
$usecase_name=$_REQUEST['usecase_name'];
$t_page_return= plugin_page( 'usecase_page' );
$t_page_return=$t_page_return."&id_usecase=".$id_usecase;
$t_page_go= plugin_page( 'usecase_page' );

//validamos si existen casos de uso que usen el caso de uso que se quiere eliminar en su relacion extend
$t_repo_table = plugin_table( 'usecase_extend', 'honey' );
$t_repo_table2 = plugin_table( 'usecase', 'honey' );

								$query_search = 'SELECT *
												  FROM '.$t_repo_table.' a inner join '.$t_repo_table2.' b on (a.id_usecase_extends=b.id)
												  WHERE id_usecase_parent='. db_param().'
												  AND a.active = 0';

$result_search = db_query_bound( $query_search, array($id_usecase) );

$count_extends = db_num_rows( $result_search );

if ($count_extends > 0){
?>
<div align="center">
<form id="form2" action="<?php echo plugin_page( $t_page_go ); ?>" method="POST" enctype="multipart/form-data">

<table class="width90">
<tr class="row-category">
<td><?php echo plugin_lang_get('the_uc')?> "<?php echo "<a  href=\"$t_page_return\">".$id."</a>";?> -&nbsp;<?php echo $usecase_name;?>" <?php echo plugin_lang_get('usecase_referenced_extend')?></td>
</tr>
<?php
    while($row_search = db_fetch_array( $result_search )){
	$uc_extend = $row_search['id_usecase_extends'];
	$uc_extend_name = $row_search['name'];
	$t_page_go=$t_page_go."&id_usecase=".str_pad($uc_extend, 7, "0", STR_PAD_LEFT);

      ?>
	    	<tr  <?php echo helper_alternate_class() ?> >
		
				<td class="center" colspan="2"> 
						<?php echo "<a  href=\"$t_page_go\">".str_pad($uc_extend, 7, "0", STR_PAD_LEFT)."</a> - ".$uc_extend_name;?>
				</td>
		
			 </tr>
<?php
	}		 
}
?>
</table>

<?php
//validamos si existen casos de uso que usen el caso de uso que se quiere eliminar en su relacion include

$t_repo_table = plugin_table( 'usecase_include', 'honey' );
$t_repo_table2 = plugin_table( 'usecase', 'honey' );

								$query_search = 'SELECT *
												  FROM '.$t_repo_table.'  a inner join '.$t_repo_table2.' b on (a.id_usecase_include=b.id)
												  WHERE id_usecase_parent='. db_param().'
												  AND a.active = 0';

$result_search = db_query_bound( $query_search, array($id_usecase) );

$count_include = db_num_rows( $result_search );

if ($count_include > 0){
?>

<br>

<table class="width90">
<tr class="row-category" >
<td ><?php echo plugin_lang_get('the_uc')?> "<?php echo "<a  href=\"$t_page_return\">".$id."</a>";?>-&nbsp;<?php echo $usecase_name;?>" <?php echo plugin_lang_get('usecase_referenced_include')?></td>
</tr>
<?php
    while($row_search = db_fetch_array( $result_search )){
	$uc_include = $row_search['id_usecase_include'];
	$uc_include_name = $row_search['name'];
	$t_page_go=$t_page_go."&id_usecase=".str_pad($uc_include, 7, "0", STR_PAD_LEFT);

      ?>
	    	<tr <?php echo helper_alternate_class() ?> >
		
				<td class="center" colspan="2"> 
						<?php echo "<a  href=\"$t_page_go\">".str_pad($uc_include, 7, "0", STR_PAD_LEFT)."</a> - ".$uc_include_name;?>
				</td>
		
			 </tr>
<?php

	}		 
}
?>
</table>

</form>
</div>


<div align="center">
<form id="form1" action="<?php echo plugin_page( $t_page ); ?>" method="POST" enctype="multipart/form-data">

<?php showMessage(plugin_lang_get('sure_delete_uc'), 'warning')?>
		<tr class="row-category">
		<td class="form-title" colspan="2">
		<input type="button" value="<?php echo plugin_lang_get('delete')?>" onClick="javascript:go_page(null,<?php echo $id_usecase?> ,'<?php echo $t_page?>')"/></td>
		</tr>
		</table>

</form>


</div>



<?php

html_page_bottom1( );

?>