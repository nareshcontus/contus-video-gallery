<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function get_playlist_id($play_name) {
    global $wpdb;
    $playlist_id = $wpdb->get_var("select pid from " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE playlist_slugname='" . $play_name . "' LIMIT 1");
    return $playlist_id;
}

function get_playlist_name($play_id) {
    global $wpdb;
    $playlist_name = $wpdb->get_var("select playlist_slugname from " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='" . $play_id . "' LIMIT 1");
    return $location = get_site_url() . "/category/" . $playlist_name;
}
function get_video_permalink($vid){
    global $wp_rewrite;
    $link = $wp_rewrite->get_page_permastruct();
    $video_details = get_post($vid);
//    echo "<pre>";print_r($video_details);exit;
    if ( !empty($link)){
        return get_site_url() . "/videogallery/" . $video_details->post_name;
    } else {
       return $video_details->guid;
    }
}
function get_playlist_permalink($morepageid,$playlist_id,$playlist_name){
    global $wp_rewrite;
    $link = $wp_rewrite->get_page_permastruct();
    if ( !empty($link)){
        return get_site_url() . "/category/" . $playlist_name;
    } else {
       return get_site_url() . '/?page_id=' . $morepageid . '&amp;playid=' . $playlist_id;
    }
}
function get_morepage_permalink($morepageid,$morePage){
    global $wp_rewrite;
    $link = $wp_rewrite->get_page_permastruct();

    if ( !empty($link)){
        if (isset($morePage)) {
            $type = $morePage;
            switch ($type) {
                case 'popular':
                    $location = get_site_url() . "/popular-videos";
                    break;
                case 'recent':
                    $location = get_site_url() . "/recent-videos";
                    break;
                case 'featured':
                    $location = get_site_url() . "/featured-videos";
                    break;
                case 'categories':
                    $location = get_site_url() . "/video-more";
                    break;
            }
        }
        return $location;
    } else {
       return get_site_url() . '/?page_id=' . $morepageid . '&amp;more=' . $morePage;
    }
}
?>
    