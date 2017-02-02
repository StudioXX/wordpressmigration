<?php

require(dirname(__FILE__) . '/../wp-load.php');
require(dirname(__FILE__) . '/../wp-content/plugins/sitepress-multilingual-cms/sitepress.php');

function get_translated_term($term_id, $taxonomy, $language) {

    $translated_term_id = icl_object_id($term_id, $taxonomy, true, $language);

    $translated_term_object = get_term_by('id', $translated_term_id, $taxonomy);

    return $translated_term_object->name;
}

///////////////////////// OPERATE ON QUERIED POSTS
// $args = array(
//         'post_type' => 'participants',
//         'posts_per_page' => 1000000000,
//         'fields' => 'ids'
//     );

//     $post_query = new WP_Query($args);
//     $posts = $post_query->posts;
// foreach($posts as $post) {
//     // Do your stuff, e.g.
//         echo $post;
//         echo '<br>';
//         echo print_r(get_metadata('post', $post, $key, $single));
//         echo '<br><br>';

//     // print_r($post);
//     // // print_r($post);
//     // echo '<br><br>';
// }


    ////////////////////////////////// OPERATE ON QUERIED POSTS
    // $related = get_posts(array(
    //     'post_type' => 'events'
    //     // ,
    //     // 'meta_query' => array(
    //     //     array(
    //     //         'key' => 'related_events', // name of custom field
    //     //         'value' => true, // matches exaclty "123", not just 123. This prevents a match for "1234"
    //     //         'compare' => 'LIKE'
    //     //     )
    //     // )
    // ));
    // if ($related)
    //     foreach($related as $relate) {
    //         $posti = $relate->ID;
    //         print_r(get_field("google_maps_link", $posti));
    //         update_post_meta( $posti, 'google_maps_link', 'updating google maps link' );
    //         // __update_post_meta( $relate->ID, 'google_maps_link', 'google map french link here' );

    //         // wp_set_object_terms($posti, 'hoohah', 'entity', true);

    //         // print_r(get_terms( array(
    //         //     'taxonomy' => 'entity',
    //         //     'hide_empty' => false,
    //         // ) ));

    //     };

    /////////////////////////////// GET TAXONOMY TRANSLATIONS
    // $terms = get_terms( 'entity', array(
    //     'hide_empty' => false,
    // ) );
    // if ($terms)
    //     foreach($terms as $term) {
    //         $id = $term->term_id;
    //         $term->translated = get_translated_term($id, 'entity', 'en');
    //         // print_r($term);
    //     };
    //     // $english = array_column($terms, 'first_name');
    //     // echo print_r();
    // print_r($terms)


    ///////////// LOOP THROUGH ALL POSTS OF A CERTAIN POST TYPE - WORKS ON BOTH LANGUAGES

    $events = get_posts(array(
        'post_type' => 'events'
        , 'posts_per_page' => 1000000000
        // ,
        // 'meta_query' => array(
        //     array(
        //         'key' => 'related_events', // name of custom field
        //         'value' => true, // matches exaclty "123", not just 123. This prevents a match for "1234"
        //         'compare' => 'LIKE'
        //     )
        // )
    ));
    if ($events)
        foreach($events as $event) {
            $postid = $event->ID;
            // SET ATTACHMENT TO POST

            // $attachment = array(
            //     "id" => "4088",
            //     "fields" => array(
            //         "title" => "guap",
            //         "caption" => "guapoiii"
            //     )
            // );
            // $attachments = array(
            //     "my_attachments" => array($attachment)
            // );
            // update_post_meta($postid , 'attachments', json_encode( $attachments ));


            ////////// INSERT PARTICIPANTS
            $participant = array(
                '2135',
                '2129'
            );

            update_post_meta($postid , 'participants_names', serialize($participant) );




            print_r(get_post_meta($postid));
            // delete_post_meta($postid, '_participants');
        
            ////////// GET ID OF THE TRANSLATION
            // $translation = icl_object_id($postid, 'events', false);
            // print_r($postid);
            // echo '<br>';
            // print_r($translation);
            // echo '<br>';
            // echo '<br>';
            /////////// GET LANGUAGE
            // $my_post_language_details = apply_filters( 'wpml_post_language_details', NULL, $postid ) ;
            // $language = $my_post_language_details[language_code];
            // print_r($language);
    
            /////////////// SET TAXONOMY
            // wp_set_object_terms($postid, 'hoohhuahuahuaah', 'keyword', true);
        };


///////// SWITCH LANGUAGE
// $sitepress->switch_lang($new_lang);

// ////////// USE WP_QUERY TO LOOP THROUGH ALL POSTS OF A POST-TYPE [ONLY WORKS ON CURRENT LANGUAGE]
//     $args = array(
//         'post_type' => 'events',
//         'posts_per_page' => 1000000000,
//         'supress_filter' => true
//     );

//     $post_query = new WP_Query($args);
//     if($post_query->have_posts() ) {
//     while($post_query->have_posts() ) {
//     $post_query->the_post();
//     $postid = $post_query->post->ID;
//     wp_set_object_terms($postid, 'hoohah', 'keyword', true);
//     echo "<h2>" . $post_query->post->ID . "</h2>";
//     echo "<h2>" . the_title() . "</h2>";
//   }
// }


?>