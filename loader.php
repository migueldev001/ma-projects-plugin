<?php
/**
 * Loads the plugin files
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Load basic setup
require_once( MAPP_PLUGIN_DIR . 'admin/basic-setup.php' );

// Do plugin operations
require_once( MAPP_PLUGIN_DIR . 'functions/do.php' );