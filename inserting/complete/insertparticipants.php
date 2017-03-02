<?php
require(dirname(__FILE__) . '/../../wp-load.php');
require(dirname(__FILE__) . '/../../wp-content/plugins/sitepress-multilingual-cms/sitepress.php');

function __update_post_meta( $post_id, $field_name, $value = '' )
    {
        if ( empty( $value ) OR ! $value )
        {
            delete_post_meta( $post_id, $field_name );
        }
        elseif ( ! get_post_meta( $post_id, $field_name ) )
        {
            add_post_meta( $post_id, $field_name, $value );
        }
        else
        {
            update_post_meta( $post_id, $field_name, $value );
        }
    };

function my_insert_posts($slice) {
    $output = array();
 
    // Create original post object
    $my_original_post = array(
        'post_title'    => $slice['titlefrench'],
        'post_content'  => $slice['bodyfrench'],
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'		=>	'participants'
    );
 
    // Create translation post object
    $my_translated_post = array(
        'post_title'    => $slice['titleenglish'],
        'post_content'  => $slice['bodyenglish'],
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'		=>	'participants'
    );
 
    // Insert the 2 posts into the database
    $original_post_id = wp_insert_post( $my_original_post );
    $translated_post_id = wp_insert_post( $my_translated_post );

    __update_post_meta( $original_post_id, 'migration_related_matricules', $slice['matricules'] );
    __update_post_meta( $translated_post_id, 'migration_related_matricules', $slice['matricules'] );

    wp_set_object_terms( $original_post_id, $slice['parttypefr'], 'participant_type' );
    wp_set_object_terms( $translated_post_id, $slice['parttypeeng'], 'participant_type' );

    wp_set_object_terms( $original_post_id, $slice['keywordsfrench'], 'keywords' );
    wp_set_object_terms( $translated_post_id, $slice['keywordsenglish'], 'keywords' );
 
    return $output = array(
        'original' => $original_post_id,
        'translation' => $translated_post_id
    );
}
 

function element_connect_on_insert($slice) {
    $inserted_post_ids = my_insert_posts($slice);
 
    if ( $inserted_post_ids) {
        // https://wpml.org/wpml-hook/wpml_element_type/
        $wpml_element_type = apply_filters( 'wpml_element_type', 'participants' );
         
        // get the language info of the original post
        // https://wpml.org/wpml-hook/wpml_element_language_details/
        $get_language_args = array('element_id' => $inserted_post_ids['original'], 'element_type' => 'participants' );
        $original_post_language_info = apply_filters( 'wpml_element_language_details', null, $get_language_args );
         
        $set_language_args = array(
            'element_id'    => $inserted_post_ids['translation'],
            'element_type'  => $wpml_element_type,
            'trid'   => $original_post_language_info->trid,
            'language_code'   => 'en',
            'source_language_code' => $original_post_language_info->language_code
        );
 
        do_action( 'wpml_set_element_language_details', $set_language_args );
    }
}

    $slices = json_decode(file_get_contents('participants.json'), true);
    if ($slices) { 
        foreach ($slices as $slice) {
            element_connect_on_insert($slice);
    }} else {
            echo 'error';
        }

?>

