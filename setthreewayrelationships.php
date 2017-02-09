<?php

require(dirname(__FILE__) . '/../wp-load.php');
require(dirname(__FILE__) . '/../wp-content/plugins/sitepress-multilingual-cms/sitepress.php');

function get_translated_term($term_id, $taxonomy, $language) {

    $translated_term_id = icl_object_id($term_id, $taxonomy, true, $language);

    $translated_term_object = get_term_by('id', $translated_term_id, $taxonomy);

    return $translated_term_object->name;
}

function langcode_post_id($post_id){
    global $wpdb;
 
    $query = $wpdb->prepare('SELECT language_code FROM ' . $wpdb->prefix . 'icl_translations WHERE element_id="%d"', $post_id);
    $query_exec = $wpdb->get_row($query);
 
    return $query_exec->language_code;
}

    ///////////// LOOP THROUGH ALL POSTS OF A CERTAIN POST TYPE - WORKS ON BOTH LANGUAGES

    $events = get_posts(array(
        'post_type' => 'events'
        , 'posts_per_page' => 1000000000
    ));
    if ($events)
        foreach($events as $event) {
            $postid = $event->ID;
            $postlang = langcode_post_id( $postid  );

            // $participantstosearch = array(
            //     ''
            // );

            ////////// INSERT PARTICIPANTS RELATIONSHIP TO EVENTS
            // $participant = array(
            //     '2135',
            //     '2129'
            // );

            // update_post_meta($postid , 'participants_names', serialize($participant) );

        };




?>