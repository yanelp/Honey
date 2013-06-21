<?php
require_once('functions.php');

html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

echo "<br><br>";

plugin_page(new_subject_page);


html_page_bottom1( );

?>