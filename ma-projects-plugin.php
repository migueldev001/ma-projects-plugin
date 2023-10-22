<?php
/**
 * Plugin Name: MA Projects Plugin
 * Description: Prueba técnica
 * Author: Miguel Álvarez
 * Version: 1.0
 * Text Domain: ma-projects-plugin
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define constants
 */
if ( ! defined( 'MAPP_VERSION_NUM' ) ) 	define( 'MAPP_VERSION_NUM', '1.0' );
if ( ! defined( 'MAPP_PLUGIN' ) )		define( 'MAPP_PLUGIN', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
if ( ! defined( 'MAPP_PLUGIN_DIR' ) )	define( 'MAPP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'MAPP_PLUGIN_URL' ) )	define( 'MAPP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once( MAPP_PLUGIN_DIR . 'loader.php' );

register_activation_hook( __FILE__, 'mapp_activate_plugin' );