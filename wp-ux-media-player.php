<?php
/*
Plugin Name: JW Player
Plugin URI: https://github.com/DWBN/wp-jw-player
Description: A simple plugin, which allows to include a hosted jw player powered responsive video player.
Version: 1.0.1
Author: Andreas Schönefeldt
Author URI: https://github.com/Andreas-Schoenefeldt
Contributors: Andreas Schönefeldt
Text Domain: dwbn-wp-jw-player
Domain Path: /languages
*/

use WpUxMediaPlayer\Plugin\Plugin;

require __DIR__ . '/vendor/autoload.php';

$GLOBALS['wp-ux-media-player'] = new Plugin(__FILE__);