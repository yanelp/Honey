<?php
require_once('print_lel_menu.php');


html_page_top( plugin_lang_get( 'title' ) );

print_lel_menu();

echo "<br><br>";

//echo "<center><p>Aca va todo lo que tengamos que poner de LEL</p></center>";

plugin_page(new_subject_page);


html_page_bottom1( );

?>