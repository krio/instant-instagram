<?php
/**
 * Plugin Name: Instant Instagram
 * Plugin URI: http://riows.com
 * Description: A plugin to show Instagram photos and videos.
 * Version: 0.2
 * Author: Kevin Allen Rio
 * Author URI: http://riows.com
 */
require_once 'InstantInstagram.php';

function instant_instagram_init($atts)
{
    $atts = shortcode_atts(array(
        'number' => 6,
        'cache_minutes' => 20,
        'clientid' => null,
        'userid' => null
    ), $atts);

    $instant_instagram = new InstantInstagram($atts['clientid'], $atts['userid'],
        $atts['number'], $atts['cache_minutes']);

    wp_enqueue_style('instant_instagram_style');
    wp_enqueue_script('instant_instagram_script');

    return $instant_instagram->getHTML();
}

function instant_instagram_scripts_register()
{
    wp_register_style('instant_instagram_style', plugins_url('css/style.css', __FILE__), array(), '1.0.2');
    wp_register_script('instant_instagram_script', plugins_url('js/script.js', __FILE__), array(), '1.0.2', true);
}

add_action('wp_enqueue_scripts', 'instant_instagram_scripts_register');
add_shortcode('instant-instagram', 'instant_instagram_init');
