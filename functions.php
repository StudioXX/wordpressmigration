<?php
/**
 * Author: Ole Fredrik Lie
 * URL: http://olefredrik.com
 *
 * FoundationPress functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @package WordPress
 * @subpackage FoundationPress
 * @since FoundationPress 1.0.0
 */

/** Various clean up functions */
require_once( 'library/cleanup.php' );

/** Required for Foundation to work properly */
require_once( 'library/foundation.php' );

/** Register all navigation menus */
require_once( 'library/navigation.php' );

/** Add menu walkers for top-bar and off-canvas */
require_once( 'library/menu-walkers.php' );

/** Create widget areas in sidebar and footer */
require_once( 'library/widget-areas.php' );

/** Return entry meta information for posts */
require_once( 'library/entry-meta.php' );

/** Enqueue scripts */
require_once( 'library/enqueue-scripts.php' );

/** Add theme support */
require_once( 'library/theme-support.php' );

/** Add Nav Options to Customer */
require_once( 'library/custom-nav.php' );

/** Change WP's sticky post class */
require_once( 'library/sticky-posts.php' );

/** If your site requires protocol relative url's for theme assets, uncomment the line below */
// require_once( 'library/protocol-relative-theme-assets.php' );

add_filter( 'storm_social_icons_use_latest', '__return_true' );

function new_nav_menu_items283238($items,$args) {
    if (function_exists('icl_get_languages') && $args->theme_location == 'top-bar-l') {
        $languages = icl_get_languages('skip_missing=0');
        if(1 < count($languages)){
            foreach($languages as $l){
                if(!$l['active']){
                    $items = $items.'<li id="languagesw" class="menu-item-'.$l['language_code'].'"><a href="'. $l['url'].'">'.$l['native_name'].'</a></li>';
                }
            }
        }
    }
    return $items;
}

add_filter( 'wp_nav_menu_items', 'new_nav_menu_items283238',10,2 );

if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'home-slider-size', 1000, 400 );
    add_image_size( 'sxx-thumbnail', 1000, 400 , true);
}

function myslug_show_search_submit( $bool, $item, $depth, $args ){
  $bool = false;
  return $bool;
}
add_filter( 'bop_nav_search_show_submit_button', 'myslug_nav_search_form', 10, 4 );

function translate_date_format($format) {
	if (function_exists('icl_translate'))
	  $format = icl_translate('Formats', $format, $format);
return $format;
}
add_filter('option_date_format', 'translate_date_format');

function my_attachments( $attachments )
{
  $fields         = array(
    array(
      'name'      => 'title',                         // unique field name
      'type'      => 'text',                          // registered field type
      'label'     => __( 'Title', 'attachments' ),    // label to display
      'default'   => 'title',                         // default value upon selection
    ),
    array(
      'name'      => 'caption',                       // unique field name
      'type'      => 'textarea',                      // registered field type
      'label'     => __( 'Caption', 'attachments' ),  // label to display
      'default'   => 'caption',                       // default value upon selection
    ),
  );

  $args = array(

    // title of the meta box (string)
    'label'         => 'My Attachments',

    // all post types to utilize (string|array)
    'post_type'     => array( 'matricule' ),

    // meta box position (string) (normal, side or advanced)
    'position'      => 'normal',

    // meta box priority (string) (high, default, low, core)
    'priority'      => 'high',

    // allowed file type(s) (array) (image|video|text|audio|application)
    'filetype'      => null,  // no filetype limit

    // include a note within the meta box (string)
    'note'          => 'Attach files here!',

    // by default new Attachments will be appended to the list
    // but you can have then prepend if you set this to false
    'append'        => true,

    // text for 'Attach' button in meta box (string)
    'button_text'   => __( 'Attach Files', 'attachments' ),

    // text for modal 'Attach' button (string)
    'modal_text'    => __( 'Attach', 'attachments' ),

    // which tab should be the default in the modal (string) (browse|upload)
    'router'        => 'browse',

    // whether Attachments should set 'Uploaded to' (if not already set)
    'post_parent'   => false,

    // fields array
    'fields'        => $fields,

  );

  $attachments->register( 'my_attachments', $args ); // unique instance name
}

add_action( 'attachments_register', 'my_attachments' );

////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
/////////////////// ACF MULTI-DIRECTIONAL RELATIONSHIPS ////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////

function events_related_participants_update_value( $value, $post_id, $field  ) {
	
	// vars
  $post_type = 'events';
  $relationship_post_type = 'participants';
	$field_name = 'events_related_participants';
  $other_field = 'participants_related_events';
	$other_field_key = 'field_56b9ea5e7cd77';
	$global_name = 'is_updating_' . $field_name;
  $current_lang = ICL_LANGUAGE_CODE;
	$other_lang = ($current_lang == 'fr' ? 'en' : 'fr');
  $translated_values = [];
	
	// bail early if this filter was triggered from the update_field() function called within the loop below
	// - this prevents an inifinte loop
	if( !empty($GLOBALS[ $global_name ]) ) return $value;
	
	
	// set global variable to avoid inifite loop
	// - could also remove_filter() then add_filter() again, but this is simpler
	$GLOBALS[ $global_name ] = 1;
	
	
  ///////// CREATE TRANSLATION
  $translated_post = icl_object_id($post_id, $post_type, false, $other_lang);

  //////////////// CREATE RELATIONSHIPS
	// loop over selected posts and add this $post_id
	if( is_array($value) ) {
		foreach( $value as $post_id2 ) {
			// populate translated_values array with post ids of translations
      $translated_values[] = icl_object_id($post_id2, $relationship_post_type, false, $other_lang);
			// load existing related posts
			$value2 = get_field($other_field, $post_id2, false);
			
			// allow for selected posts to not contain a value
			if( empty($value2) ) {				
				$value2 = array();
			}
					
			// bail early if the current $post_id is already found in selected post's $value2
			if( in_array($post_id, $value2) ) continue;
			
			// append the current $post_id to the selected post's 'related_posts' value
			$value2[] = $post_id;
			// update the selected post's value (use field's key for performance)
			update_field($other_field_key, $value2, $post_id2);
		}
	}
	
  ////////// INSERT TRANSLATED VALUES INTO TRANSLATED POST
  update_post_meta($translated_post, $field_name, serialize($translated_values) );

  //////////////// CREATE RELATIONSHIPS WITH TRANSLATED POST ON TRANSLATED VALUES
	// loop over selected posts and add this $post_id
	if( is_array($translated_values) ) {
		foreach( $translated_values as $post_id3 ) {
			// load existing related posts
			$value3 = get_field($other_field, $post_id3, false);
			
			// allow for selected posts to not contain a value
			if( empty($value3) ) {		
				$value3 = array();
			}
					
			// bail early if the current $post_id is already found in selected post's $value2
			if( in_array($translated_post, $value3) ) continue;
			
			// append the current $post_id to the selected post's 'related_posts' value
			$value3[] = $translated_post;
			// update the selected post's value (use field's key for performance)
			update_field($other_field_key, $value3, $post_id3);
		}
	}


  ///////////// DELETE RELATIONSHIPS
	// find posts which have been removed
	$old_value = get_field($field_name, $post_id, false);
	
	if( is_array($old_value) ) {
		
		foreach( $old_value as $post_id4 ) {
			
			// bail early if this value has not been removed
			if( is_array($value) && in_array($post_id4, $value) ) continue;
			
			
			// load existing related posts
			$value4 = get_field($other_field, $post_id4, false);
			

			// bail early if no value
			if( empty($value4) ) continue;
			
			
			// find the position of $post_id within $value2 so we can remove it
			$pos = array_search($post_id, $value4);
			
			
			// remove
			unset( $value4[ $pos] );
			
			
			// update the un-selected post's value (use field's key for performance)
			update_field($other_field_key, $value4, $post_id4);


      // process translation
      $post_id4translated = icl_object_id($post_id4, $relationship_post_type, false, $other_lang);
      
      // load existing related posts
			$value4translated = get_field($other_field, $post_id4translated, false);
			
			// bail early if no value
			if( empty($value4translated) ) continue;
			

			// find the position of $post_id within $value2 so we can remove it
			$pos2 = array_search($translated_post, $value4translated);
			
			// remove
			unset( $value4translated[ $pos2] );
			
			
			// update the un-selected post's value (use field's key for performance)
			update_field($other_field_key, $value4translated, $post_id4translated);
			
		}
		
	}

  ///////////// DELETE RELATIONSHIPS FROM TRANSLATED VALUES
	// find posts which have been removed
	// $old_value_translated = get_field($field_name, $translated_post, false);
	
	// if( is_array($old_value_translated) ) {
	// 	foreach( $old_value_translated as $post_id5 ) {
			
	// 		// bail early if this value has not been removed
  
	// 		if( is_array($translated_values) && in_array($post_id5, $translated_values) ) continue;
			
	// 		// load existing related posts
	// 		$value5 = get_field($other_field, $post_id5, false);
			
			
	// 		// bail early if no value
	// 		if( empty($value5) ) continue;
			
			
	// 		// find the position of $post_id within $value2 so we can remove it
	// 		$pos = array_search($translated_post, $value5);
			
			
	// 		// remove
	// 		unset( $value5[ $pos] );
			
	// 		// update the un-selected post's value (use field's key for performance)
	// 		update_field($other_field_key, $value5, $post_id5);
			
	// 	}
		
	// }
	

  ////////// COMPLETE AND EXIT ///////////////////////
	// reset global varibale to allow this filter to function as per normal
	$GLOBALS[ $global_name ] = 0;
	
	
	// return
    return $value;
    
}

add_filter('acf/update_value/name=events_related_participants', 'events_related_participants_update_value', 10, 3);

?>
