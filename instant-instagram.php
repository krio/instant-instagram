<?php
/**
 * Plugin Name: Instant Instagram
 * Plugin URI: http://riows.com
 * Description: A plugin to show Instagram photos and videos.
 * Version: 1.0.3
 * Author: Kevin Allen Rio
 * Author URI: http://riows.com
 */
require_once 'InstantInstagram.php';
require_once 'InstagramUser.php';

function instant_instagram_init($atts)
{
	if(!isset($atts['number'])) $atts['number'] = 6;
	if(!isset($atts['cache_minutes'])) $atts['cache_minutes'] = 20;

	$users = array();

	foreach($atts as $attK => $attV) {
		if(strpos($attK, 'clientid') === 0) {
			$num = intval(substr($attK, -1));
			if($num && isset($atts['userid'.$num])) {
				$client_id = $atts['clientid'.$num];
				$user_id = $atts['userid'.$num];

				$users[] = new InstagramUser($client_id, $user_id);
			}
		}
	}

    $instant_instagram = new InstantInstagram($users, intval($atts['number']), intval($atts['cache_minutes']));

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
