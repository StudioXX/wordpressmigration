<?php

require(dirname(__FILE__) . '/../wp-load.php');

// lOOP THROUGH ALL IMAGES

$args = array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'orderby' => 'post_date',
        'order' => 'desc',
        'posts_per_page' => '30',
        'post_status'    => 'inherit'
         );

     $loop = new WP_Query( $args );

while ( $loop->have_posts() ) : $loop->the_post();

// $image = wp_get_attachment_image_src( get_the_ID() ); 
// echo "<img src='" . $image[0] . "'>";

// $attachment = array(
//     'ID' => get_the_ID(),
//     'post_parent' => 4022
// );
// wp_insert_attachment( $attachment );



endwhile;

?>
