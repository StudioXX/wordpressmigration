<?php
require(dirname(__FILE__) . '/../wp-load.php');
require(dirname(__FILE__) . '/../wp-content/plugins/sitepress-multilingual-cms/sitepress.php');



// $slices = json_decode(file_get_contents('keywordsmap.json'), true);
//     if ($slices) { 
//         foreach ($slices as $slice) {
//             wp_insert_term( $slice['french'], 'keywords' );
//     }} else {
//             echo 'error';
//         }

$terms = get_terms( 'keywords', array(
    'hide_empty' => false,
) );

echo json_encode($terms, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

?>