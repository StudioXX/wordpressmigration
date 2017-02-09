<?php

require(dirname(__FILE__) . '/../wp-load.php');
require(dirname(__FILE__) . '/../wp-content/plugins/sitepress-multilingual-cms/sitepress.php');
                
    //////////////////////////////// OPERATE ON QUERIED POSTS
    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'posts_per_page' => 1000000000
        )
    );

    // foreach($attachments as $attachment) {
    //             $postid = $attachment->ID;
    //             $attachmenttitle = $attachment->post_title;
    //             print_r($attachment);
    //             echo '<br><br>';
    //         };

    $matricules = get_posts(array(
        'post_type' => 'matricule',
        'posts_per_page' => 1000000000
        // ,
        // 'meta_query' => array(
        //     array(
        //         'key' => 'related_events', // name of custom field
        //         'value' => true, // matches exaclty "123", not just 123. This prevents a match for "1234"
        //         'compare' => 'LIKE'
        //     )
        // )
    ));
    if ($matricules)
        foreach($matricules as $matricule) {
            $postid = $matricule->ID;
            $posttitle = $matricule->post_title;
            // print_r($posttitle);
            // print_r(get_post_meta($postid));
            // echo '<br>';
            $attachmentsarray = array();





            foreach($attachments as $attachment) {
                // $attachmentid = $attachment->ID;
                $attachmenttitle = $attachment->post_title;
                if (strpos($attachmenttitle, $posttitle) !== false) {
                    $itsattachment = array(
                        "id" => $attachment->ID
                    );
                    $attachmentsarray[] = $itsattachment;
                }
            };

            if (!empty($attachmentsarray)) {
                $attachmentstoappend = array(
                    "my_attachments" => $attachmentsarray
                );
                update_post_meta($postid , 'attachments', json_encode( $attachmentstoappend ));
            }


            
        };


        


?>