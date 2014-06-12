<?php
/**
 * Plugin Name: Insta Rio
 * Plugin URI: http://riows.com
 * Description: A plugin to show instagram photos and videos
 * Version: 0.1
 * Author: Kevin Allen Rio
 * Author URI: http://riows.com
 */

function insta_rio_init($atts) {
    $client_id = trim($atts['clientid']);
    $user_id = trim($atts['userid']);

    $plugin_dir = plugin_dir_path(__FILE__);

    $cache_file_name = $plugin_dir.'instagram_fetched.json';

    $fetch_new = false;
    $insta_recent = null;
    $images_html = '<div class="insta_rio_wrap">';

    if(!file_exists($cache_file_name)) {
        $fetch_new = true;
    } else {
        $response = file_get_contents($cache_file_name);
        $insta_recent = json_decode($response, true);
        if($insta_recent['fetched_at'] < strtotime('-20 minutes') || !$insta_recent['response']) {
            $fetch_new = true;
        }
    }

    if($fetch_new) {
        $insta_params = ['client_id' => $client_id, 'count' => 5];
        $insta_endpoint = 'https://api.instagram.com/v1/users/'.$user_id.'/media/recent/?';
        foreach($insta_params as $ip_k => $ip_v) {
            $insta_endpoint .= '&'.urlencode($ip_k).'='.urlencode($ip_v);
        }
        $insta_recent = [
            'fetched_at' => strtotime('now'),
            'response' => json_decode(file_get_contents($insta_endpoint), true)
        ];
        file_put_contents($cache_file_name, json_encode($insta_recent));
    }

    if($insta_recent && $insta_recent['response']) {
        foreach($insta_recent['response']['data'] as $insta_entry_k => $insta_entry) {
            $img_classes = '';
            if($insta_entry_k === 0) {
                $img_classes .= 'insta_rio_first';
                $url = $insta_entry['images']['standard_resolution']['url'];
            } else {
                $img_classes .= 'insta_rio_small';
                $url = $insta_entry['images']['thumbnail']['url'];
            }
            $images_html .= '<a title="'.$insta_entry['caption']['text'].'" class="insta_rio_link noHover" href="'.$insta_entry['link'].'"><img class="'.$img_classes.'" src="'.$url.'"/></a>';
        }
    }

    $images_html .= '</div>';
    wp_enqueue_style('insta_rio_style');
    wp_enqueue_script('insta_rio_script');
    return $images_html;
}

function insta_rio_scripts_register() {
    wp_register_style('insta_rio_style', plugins_url('style.css', __FILE__), array(), '1.0.2');
    wp_register_script('insta_rio_script', plugins_url('script.js', __FILE__), array(), '1.0.2', true);
}

add_action('wp_enqueue_scripts', 'insta_rio_scripts_register');
add_shortcode('instario', 'insta_rio_init');
