<?php

/**
 * Plugin Name: Word Filter
 * Description: Filter your post word exclude from the content
 * version: 1.0
 * Author: Shakil Hossain
 * Author URI: https://www.shakilhossain.com
 * Text Domain: word-filter
 * Domain Path: /languages
 */

use Inc\init;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

require_once 'vendor/autoload.php';

define('PLUGIN_PATH', plugin_dir_path(__FILE__));

if (! class_exists('WordFilter')) :
class WordFilter
{
    public function __construct()
    {
        add_action('init', [__CLASS__, 'languages']);
        register_activation_hook(__FILE__, [__CLASS__, 'activate']);
        register_deactivation_hook(__FILE__, [__CLASS__, 'deactivate']);
        register_uninstall_hook(__FILE__, [__CLASS__, 'unstall']);
    }

    public function activate()
    {
        flush_rewrite_rules();
    }

    public function deactivate()
    {
        flush_rewrite_rules();
    }

    public function unstall()
    {
        // Do whatever You want
    }

    public function run()
    {
        $init = new init;
        $init->register_services();
    }

    public function languages()
    {
        load_plugin_textdomain('word-filter', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
}
endif;

$wordFilter = new WordFilter();

$wordFilter->run();