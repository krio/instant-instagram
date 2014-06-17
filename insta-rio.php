<?php
/**
 * Plugin Name: Insta Rio
 * Plugin URI: http://riows.com
 * Description: A plugin to show instagram photos and videos
 * Version: 0.1
 * Author: Kevin Allen Rio
 * Author URI: http://riows.com
 */
require_once 'InstaRio.php';

function insta_rio_init($atts)
{
    $insta_rio = new InstaRio($atts['clientid'], $atts['userid'], $atts['number']);

    wp_enqueue_style('insta_rio_style');
    wp_enqueue_script('insta_rio_script');

    return $insta_rio->getHTML();
}

function insta_rio_scripts_register()
{
    wp_register_style('insta_rio_style', plugins_url('css/style.css', __FILE__), array(), '1.0.2');
    wp_register_script('insta_rio_script', plugins_url('js/script.js', __FILE__), array(), '1.0.2', true);
}

add_action('wp_enqueue_scripts', 'insta_rio_scripts_register');
add_shortcode('instario', 'insta_rio_init');
