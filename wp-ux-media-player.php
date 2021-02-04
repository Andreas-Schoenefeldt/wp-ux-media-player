<?php
/*
Plugin Name: UX Media Player
Plugin URI: https://github.com/Andreas-Schoenefeldt/wp-ux-media-player
Description: Improves the standard wordpress media player to have a better skin and usability.
Version: 1.0.11
Author: Andreas Schönefeldt
Author URI: https://github.com/Andreas-Schoenefeldt
Contributors: Andreas Schönefeldt
Text Domain: wp-ux-media-player
Domain Path: /languages
*/

use WpUxMediaPlayer\Plugin\Plugin;

require __DIR__ . '/vendor/autoload.php';

$GLOBALS['wp-ux-media-player'] = new Plugin(__FILE__);